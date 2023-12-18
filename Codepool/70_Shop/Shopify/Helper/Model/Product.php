<?php

use Shopify\API\Application\Application;
use Shopify\API\Application\Request\Collections\CountCollections\CountCollectionsParams;
use Shopify\API\Application\Request\Products\CountProducts\CountProductsParams;
use Shopify\API\Application\Request\Products\ListOfProductCollections\ListOfProductCollectionsParams;
use Shopify\API\Application\Request\Products\ListOfProducts\ListOfProductsParams;
use Shopify\API\Application\Request\Products\SingleProduct\SingleProductParams;

/**
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
 * $Id$
 *
 * (c) 2010 - 2017 RedGecko GmbH -- http://www.redgecko.de/
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 *
 * Class ML_Shopify_Helper_Model_Product
 */
class ML_Shopify_Helper_Model_Product 
{

    /**
     *
     * @return ML_Database_Model_Query_Select
     */
    public function getProductSelectQuery() {
        $oSqlQuery = MLDatabase::factorySelectClass()
            ->select('DISTINCT id, ShopifyTitle as name')
            ->from(MLShopifyAlias::getProductModel()->getTableName(), 'shopify_product')
            ->where('parentid = 0')
        ;
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
        if(count($aEdges) > 0) {
            $aProduct = current($aEdges)['node'];
        }
        return $aProduct;
    }

    public function getProductListFromShopify(ListOfProductsParams $aListOfProductsParams){
        $application = new Application(MLShopifyAlias::getShopHelper()->getShopId());
        $response = $application->getProductRequest()->getListOfProducts($aListOfProductsParams )->getBodyAsArray();
        if (empty($response['products'])) {
            return array();
        } else {
            return $response['products'];
        }
    }

    public function getProductCollectionListFromShopify(ListOfProductCollectionsParams $aListOfProductsParams){
        $application = new Application(MLShopifyAlias::getShopHelper()->getShopId());
        $response = $application->getProductCollectionRequest()->send($aListOfProductsParams )->getBodyAsArray();
        return isset($response['data']['products']) ? $response['data']['products'] : array();
    }

    public function getProductListCount($countProductsParams = null){
        $application = new Application(MLShopifyAlias::getShopHelper()->getShopId());
        if($countProductsParams === null){
            $countProductsParams = new CountProductsParams();
        }
        $response = $application->getProductRequest()->countProducts($countProductsParams)->getBodyAsArray();
        if (empty($response['count'])) {
            return array();
        } else {
            return $response['count'];
        }
    }


    public function getCollectionsListCount($countCollectionsParams = null){
        $application = new Application(MLShopifyAlias::getShopHelper()->getShopId());
        if($countCollectionsParams === null){
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
        if (is_array($aEdge['node']['collections']['edges'])) {
            $aId = explode('/', $aEdge['node']['id']);
            $sId = end($aId);
            $aCollection = current($aEdge['node']['collections']['edges'])['node'];
            $aCollectionId = explode('/', $aCollection['id']);
            $sCollectionId = end($aCollectionId);
            if (!empty($sCollectionId)) {
                $oMLProduct = MLShopifyAlias::getProductModel()->getProductByShopId($sId);
                if ($oMLProduct->exists()) {
                    $oMLProduct
                        ->set('ShopifyCollectionId', $sCollectionId)
                        ->set('ShopifyCollectionTitle', $aCollection['title'])
                        ->save();
                    return $sId;
                }
            }
        }
        return null;
    }
}
