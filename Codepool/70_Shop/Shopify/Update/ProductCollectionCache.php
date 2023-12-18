<?php
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
 * (c) 2010 - 2019 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

use Shopify\API\Application\Request\Products\CountProducts\CountProductsParams;
use Shopify\API\Application\Request\Products\ListOfProductCollections\ListOfProductCollectionsParams;
//if ML::isInstalled() === false then it won't load init folder, we should include these classes like this
include_once __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Helper'.DIRECTORY_SEPARATOR.'MLShopifyAlias.php';

MLFilesystem::gi()->loadClass('Core_Update_Abstract');

/**
 * it is executed during update, and it set collection for each available product
 */
class ML_Shopify_Update_ProductCollectionCache extends ML_Core_Update_Abstract {

    protected $sCursor;

    public function needExecution() {
        // We couldn't filter this action only for changed product
        // because of that this update request could takes a lot of time.
        // And collection could be updated via cron too.
        // Also Collection is only important by productlist filtering
        // we disable it now and let them to be executed with cron ,
        // later we should investigate more time find better solution to update only changed collection
        return false;
        //        return $this->getTotalCountOfProduct() > 0;
    }

    /**
     * @return ML_Shopify_Update_ProductCollectionCache
     * @throws Exception
     */
    public function execute() {
        $aListOfProductCollectionsParams = (new ListOfProductCollectionsParams)->setLimit(MLSetting::gi()->ShopifyProductRequestLimit);
        if ($this->getCursor() !== null) {
            $aListOfProductCollectionsParams->setCursor($this->getCursor());
        }
        $aProductCollection = MLShopifyAlias::getProductHelper()->getProductCollectionListFromShopify($aListOfProductCollectionsParams);
        $oShopifyProductHelper = MLShopifyAlias::getProductHelper();
        foreach ($aProductCollection['edges'] as $aEdge) {
            $aId = explode('/', $aEdge['node']['id']);
            $sId = end($aId);
            try {
                $sId = $oShopifyProductHelper->updateShopifyCollection($aEdge);
            } catch (Exception $e) {
            }
        }
        $this->sCursor = end($aProductCollection['edges'])['cursor'];
        return $this;
    }


    /**
     * return a number between 0 and 100 to present the percent of progress
     * @return float|int
     */
    public function getProgress() {
        if (!$this->needExecution()) {
            return 100;
        }
        $totalCount = $this->getTotalCountOfProduct();
        if($totalCount === 0 || $this->getPage() > $totalCount ) {
            return 100;
        } else {
            return $this->getPage() * MLSetting::gi()->ShopifyProductRequestLimit / $totalCount * 100;
        }
    }

    protected function getTotalCountOfProduct(){
        $countProductsParams = new CountProductsParams();
        return (int)MLShopifyAlias::getProductHelper()->getProductListCount($countProductsParams);
    }

    /**
     * If it is needed to send extra parameter to manage steps of process in
     * @return array
     */
    public function getUrlExtraParameters() {
        return array(
            'shopifyProductCollectionPage' => $this->getPage(),
            'shopifyProductCollectionCursor' => $this->sCursor,
        );
    }

    public function getInfo() {
        return MLI18n::gi()->get('installation_message_importing_shopify_collection_into_magnalister');
    }

    /**
     * @return int
     */
    protected function getPage() {
        return (int)MLRequest::gi()->data('shopifyProductCollectionPage') + 1;
    }

    /**
     * Cursor is useful in pagination for graphql query, it is similar to page number in REST request
     * @return string
     */
    protected function getCursor() {
        return MLRequest::gi()->data('shopifyProductCollectionCursor');
    }

}
