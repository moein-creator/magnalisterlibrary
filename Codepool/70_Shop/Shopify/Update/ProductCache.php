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
 * (c) 2010 - 2022 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

//if ML::isInstalled() === false then it won't load init folder, we should include these classes like this
include_once __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Helper'.DIRECTORY_SEPARATOR.'MLShopifyAlias.php';
MLFilesystem::gi()->loadClass('Core_Update_Abstract');

/**
 * Fill or update product cache table of Shopify
 */
class ML_Shopify_Update_ProductCache extends ML_Core_Update_Abstract {

    protected $iNumberOfRepeat = 0;

    protected $oTimeZone = null;


    protected $oCacheDB;
    /**
     * Because of limitation in Shopify request to get only limited number of product (e.g. 10) update and cron could update product in magnalister very slowly
     * but to do it faster in magnalister, it iterates process in magnalister several time (e.g. 40 times),
     * this variable is number of iteration by each magnalister request(e.g. each ajax request in magnalister update or each cron request)
     * @var int
     */
    protected $iLimitationOfIteration = 40;

    public function needExecution() {
        //skip that for update process, it can make update process very long
        return false;
        //Lock prevent to run updating same product in same time from two different call
        //here we don't use MLCache, instead we use database cache
        //because this will be executed also during update process of plugin, and all cache files will be deleted automatically by updating
        $sCacheName = 'ProductAddOrUpdate_LOCK';
        $this->oCacheDB = MLDatabase::factory('config')->set('mpid', 0)->set('mkey', $sCacheName);
        if ($this->oCacheDB->exists() && (time() - (int)$this->oCacheDB->get('value')) < 15 * 60) {
            return false;
        }
        return $this->getTotalCountOfProduct() > 0;
    }

    //    public function execute() {
    //        $this->oCacheDB->set('value', time())->save();
    //        $iCurrentPage = $this->getPage();
    //        while ($this->iNumberOfRepeat < $this->iLimitationOfIteration) {
    //            $aListOfProductsParams = new ListOfProductsParams();
    //            $aListOfProductsParams->setLimit(MLSetting::gi()->ShopifyProductRequestLimit);
    //            $aListOfProductsParams->setPage($iCurrentPage);
    //            $sUpdatedAtMin = $this->getUpdatedAtMin();
    //            if ($sUpdatedAtMin !== null) {
    //                $aListOfProductsParams->setUpdatedAtMin($sUpdatedAtMin);
    //            }
    //            $aProducts = MLShopifyAlias::getProductHelper()->getProductListFromShopify($aListOfProductsParams);
    //
    //            foreach ($aProducts as $aProduct) {
    //                $aProduct['MethodOfUpdate'] = 'update';
    //                try {
    //                    MLShopifyAlias::getProductModel()
    //                        ->loadByShopProduct($aProduct) //insert master product
    //                        ->getVariants() // insert variants
    //                    ;
    //                } catch (\Exception $oEx) {
    //                    MLMessage::gi()->addDebug($oEx, array());
    //                }
    //            }
    //
    //            if ((int)$this->getProgress() >= 100) {
    //                $oTimeZone = $this->getTimeZone();
    //                if ($oTimeZone === null) {
    //                    $oUpdateTime = new DateTime('now');
    //                } else {
    //                    $oUpdateTime = new DateTime('now', $oTimeZone);
    //                }
    //                MLDatabase::factory('config')->set('mpid', 0)->set('mkey', 'shopifyUpdatedAtMin')->set('value', $oUpdateTime->format('c'))->save();
    //            }
    //            $this->iNumberOfRepeat++;
    //        }
    //        $this->oCacheDB->delete();
    //        return $this;
    //    }
    //
    //    /**
    //     * return a number between 0 and 100 to present the percent of progress
    //     * @return float|int
    //     */
    //    public function getProgress() {
    //        $totalCount = $this->getTotalCountOfProduct();
    //        if($totalCount === 0 || $this->getPage() > $totalCount ) {
    //            return 100;
    //        } else {
    //            return $this->getPage() * MLSetting::gi()->ShopifyProductRequestLimit / $totalCount * 100;
    //        }
    //    }
    //
    //    protected function getTotalCountOfProduct(){
    //        $countProductsParams = new CountProductsParams();
    //        $sUpdatedAtMin = $this->getUpdatedAtMin();
    //        if($sUpdatedAtMin !== null) {
    //            $countProductsParams->setUpdatedAtMin($sUpdatedAtMin);
    //        }
    //        return (int)MLShopifyAlias::getProductHelper()->getProductListCount($countProductsParams);
    //    }
    //
    //    /**
    //     * If it is needed to send extra parameter to manage steps of process in
    //     * @return array
    //     */
    //    public function getUrlExtraParameters() {
    //        return array(
    //            'shopifyProductPage' => $this->getPage(),
    //            'shopifyUpdatedAtMin' => $this->getUpdatedAtMin(),
    //        );
    //    }
    //
    //    public function getInfo() {
    //        return MLI18n::gi()->get('installation_message_importing_shopify_product_into_magnalister');
    //    }
    //
    //    /**
    //     * @return int
    //     */
    //    protected function getPage() {
    //        return (int)MLRequest::gi()->data('shopifyProductPage') + $this->iNumberOfRepeat;
    //    }
    //
    //
    //    /**
    //     * get date to limit products and get only updated product
    //     * @return string
    //     */
    //    protected function getUpdatedAtMin() {
    //        $sRequestTime = MLRequest::gi()->data('shopifyUpdatedAtMin');
    //        if($sRequestTime === null) {
    //            $sRequestTime = MLDatabase::factory('config')->set('mpid', 0)->set('mkey', 'shopifyUpdatedAtMin')->get('value');
    //        }
    //        return $sRequestTime;
    //    }
    //
    //    protected function getTimeZone() {
    //        if ($this->oTimeZone === null) {
    //            $aShop = MLShopifyAlias::getShopHelper()->getShopConfigurationAsArray();
    //            if (!empty($aShop['timezone'])) {
    //                $this->oTimeZone = new DateTimeZone($aShop['timezone']);
    //            }
    //        }
    //        return $this->oTimeZone;
    //    }

}
