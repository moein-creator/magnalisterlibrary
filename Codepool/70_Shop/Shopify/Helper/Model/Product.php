<?php
/*
 * 888888ba                 dP  .88888.                    dP
 * 88    `8b                88 d8'   `88                   88
 * 88aaaa8P' .d8888b. .d888b88 88        .d8888b. .d8888b. 88  .dP  .d8888b.
 * 88   `8b. 88ooood8 88'  `88 88   YP88 88ooood8 88'  `"" 88888"   88'  `88
 * 88     88 88.  ... 88.  .88 Y8.   .88 88.  ... 88.  ... 88  `8b. 88.  .88
 * dP     dP `88888P' `88888P8  `88888'  `88888P' `88888P' dP   `YP `88888P'
 *
 *                          m a g n a l i s t e r
 *                                      boost your Online-Shop
 *
 * -----------------------------------------------------------------------------
 * (c) 2010 - 2024 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

use Shopify\API\Application\Application;
use Shopify\API\Application\Request\Collections\CountCollections\CountCollectionsParams;
use Shopify\API\Application\Request\MetaField\ListOfMetaField\ListOfMetaFieldParams;
use Shopify\API\Application\Request\Products\CountProducts\CountProductsParams;
use Shopify\API\Application\Request\Products\ListOfCollectionsOfProduct\ListOfCollectionsOfProductParams;
use Shopify\API\Application\Request\Products\ListOfProductCollections\ListOfProductCollectionsParams;
use Shopify\API\Application\Request\Products\ListOfProducts\ListOfProductsParams;
use Shopify\API\Application\Request\Products\SingleProduct\SingleProductParams;

class ML_Shopify_Helper_Model_Product {

    /**
     *
     * @return ML_Database_Model_Query_Select
     */
    public function getProductSelectQuery() {
        $oSqlQuery = MLDatabase::factorySelectClass()
            ->select('DISTINCT id, ShopifyTitle as name')
            ->from(MLShopifyAlias::getProductModel()->getTableName(), 'shopify_product')
            ->where('parentid = 0');
        return $oSqlQuery;
    }

    /**
     * Search for product by sku in Shopify api
     * @param $sSku
     * @return array
     */
    public function getProductBySku($sSku) {
        $application = new Application(MLShopifyAlias::getShopHelper()->getShopId());
        $singleProductParams = new SingleProductParams();
        $singleProductParams->setProductSKU($sSku);
        $aEdges = $application->getProductRequest()->getSingleProduct($singleProductParams)->getBodyAsArray()['data']['productVariants']['edges'];
        $aProduct = array();
        if (count($aEdges) > 0) {
            $aProduct = current($aEdges)['node'];
        }
        return $aProduct;
    }

    public function getProductListFromShopify(ListOfProductsParams $aListOfProductsParams) {
        $application = new Application(MLShopifyAlias::getShopHelper()->getShopId());
        $response = $application->getProductRequest()->getListOfProducts2($aListOfProductsParams)->getBodyAsArray();
        if (empty($response['products'])) {
            return array();
        } else {
            return $response['products'];
        }
    }

    public function getProductCollectionListFromShopify(ListOfProductCollectionsParams $aListOfProductsParams) {
        $application = new Application(MLShopifyAlias::getShopHelper()->getShopId());
        $response = $application->getProductCollectionRequest()->send($aListOfProductsParams)->getBodyAsArray();
        return isset($response['data']['products']) ? $response['data']['products'] : array();
    }

    public function getProductCollectionListFromShopify2(ListOfProductCollectionsParams $aListOfProductsParams) {
        $application = new Application(MLShopifyAlias::getShopHelper()->getShopId());
        $response = $application->getProductCollectionRequest()->send2($aListOfProductsParams)->getBodyAsArray();
        return isset($response['data']['products']) ? $response['data']['products'] : array();
    }

    public function getCollectionsOfProductFromShopify(ListOfCollectionsOfProductParams $aListOfCollectionsOfProductsParams) {
        $application = new Application(MLShopifyAlias::getShopHelper()->getShopId());
        $response = $application->getCollectionsOfProductRequest()->send($aListOfCollectionsOfProductsParams)->getBodyAsArray();
        return isset($response['data']['product']) ? $response['data']['product'] : array();
    }

    public function getMetaFieldOfAnObjectFromShopify(ListOfMetaFieldParams $aListOfMetaFieldOfProductsParams) {
        $application = new Application(MLShopifyAlias::getShopHelper()->getShopId());
        $response = $application->getMetaFieldRequest()->send($aListOfMetaFieldOfProductsParams)->getBodyAsArray();
        return isset($response['data'][$aListOfMetaFieldOfProductsParams->getObjectName()]) ? $response['data'][$aListOfMetaFieldOfProductsParams->getObjectName()] : array();
    }

    public function getProductListCount($countProductsParams = null) {
        $application = new Application(MLShopifyAlias::getShopHelper()->getShopId());
        if ($countProductsParams === null) {
            $countProductsParams = new CountProductsParams();
        }
        $response = $application->getProductRequest()->countProducts($countProductsParams)->getBodyAsArray();
        if (empty($response['count'])) {
            return array();
        } else {
            return $response['count'];
        }
    }


    public function getCollectionsListCount($countCollectionsParams = null) {
        $application = new Application(MLShopifyAlias::getShopHelper()->getShopId());
        if ($countCollectionsParams === null) {
            $countCollectionsParams = new CountCollectionsParams();
        }
        $response = $application->getCollectionsRequest()->countCollections($countCollectionsParams)->getBodyAsArray()['count'];

        return $response;
    }

    /**
     * @param $aEdge array it contain product id, collection id in shopify API
     * @return string|null
     * @throws Exception
     */
    public function updateShopifyCollection($aEdge) {
        $sId = null;
        if (isset($aEdge['node'])) {
            $aEdge = $aEdge['node'];
        }
        if (is_array($aEdge['collections']['edges'])) {
            $aId = explode('/', $aEdge['id']);//"id": "gid://shopify/Product/6706038964317"
            $sId = end($aId);
            //var_dump($aEdge['collections']['edges']);
            $oMLProduct = MLShopifyAlias::getProductModel()->getProductByShopId($sId);
            if ($oMLProduct->exists()) {
                MLShopifyAlias::getProductCollectionRelationModel()
                    ->set('ShopifyProductID', $sId)
                    ->getList()->getQueryObject()->doDelete();
                foreach ($aEdge['collections']['edges'] as $edge) {
                    $aCollection = $edge['node'];

                    $aCollectionId = explode('/', $aCollection['id']);
                    $sCollectionId = end($aCollectionId);
                    if (!empty($sCollectionId)) {
                        MLShopifyAlias::getCollectionModel()
                            ->set('ShopifyCollectionID', $sCollectionId)
                            ->set('ShopifyCollectionTitle', $aCollection['title'])
                            ->set('ShopifyCollectionLanguage', 'en')
                            ->save();
                        MLShopifyAlias::getProductCollectionRelationModel()
                            ->set('ShopifyProductID', $sId)
                            ->set('ShopifyCollectionID', $sCollectionId)
                            ->save();
                        // var_dump($sId, $sCollectionId);
                    }
                }
            }
        }
        return $sId;
    }

    /**
     * @param $aEdge array it contain product id, collection id in shopify API
     * @return string|null
     * @throws Exception
     */
    public function updateShopifyMetaField($aEdge) {
        $sId = null;
        if (is_array($aEdge['metafields']['edges'])) {
            $aId = explode('/', $aEdge['id']);//"id": "gid://shopify/Product/6706038964317"
            $sId = end($aId);
            foreach ($aEdge['metafields']['edges'] as $edge) {
                $aMetaField = $edge['node'];
                $aMetaFieldId = explode('/', $aMetaField['id']);
                $sShopifyMetaFieldId = end($aMetaFieldId);
                if (!empty($sShopifyMetaFieldId)) {
                    $sMetaFieldId = md5($aMetaField['namespace'].$aMetaField['key'].$aMetaField['ownerType']);
                    MLShopifyAlias::getMetaFieldModel()
                        ->set('MetaFieldId', $sMetaFieldId)
                        ->set('ShopifyMetaFieldKey', $aMetaField['key'])
                        ->set('ShopifyMetaFieldName', $aMetaField['definition']['name'] ?? '')
                        ->set('ShopifyMetaFieldType', $aMetaField['type'])
                        ->set('ShopifyMetaFieldOwnerType', $aMetaField['ownerType'])
                        ->set('ShopifyMetaFieldNamespace', $aMetaField['namespace'])
                        ->set('Type', $this->getTypeOfMetaField($aMetaField))
                        ->save();
                    MLShopifyAlias::getObjectMetaFieldRelationModel()
                        ->set('MetaFieldId', $sMetaFieldId)
                        ->set('ShopifyObjectID', $sId)
                        ->set('ShopifyMetaFieldID', $sShopifyMetaFieldId)
                        ->set('ShopifyMetaFieldValue', $aMetaField['value'])
                        ->save();
                    // var_dump($sId, $sCollectionId);
                }
            }

        }
        return $sId;
    }

    protected function getTypeOfMetaField($aMetaField) {
        return $aMetaField['type'] === 'list.single_line_text_field' ? MLFormHelper::getShopInstance()::Shop_Attribute_Type_Key_Select : MLFormHelper::getShopInstance()::Shop_Attribute_Type_Key_Text;
    }

    /**
     * @param $aVendors array list vendor
     * @throws Exception
     */
    public function updateShopifyVendor($aVendors) {
        foreach ($aVendors as $aVendor) {
            $sVendorTitle = current($aVendor);
            MLShopifyAlias::getProductVendorModel()
                ->set('VendorTitleMD5', md5($sVendorTitle))
                ->set('ShopifyVendorTitle', $sVendorTitle)
                ->save();
            //var_dump(md5($sVendorTitle), $sVendorTitle);

        }
    }
}
