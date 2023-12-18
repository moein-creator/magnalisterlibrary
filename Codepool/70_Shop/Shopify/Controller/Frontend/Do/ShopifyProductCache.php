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
 * (c) 2010 - 2021 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

use Magna\Library\MLLogger;
use Shopify\API\Application\Request\Products\CountProducts\CountProductsParams;
use Shopify\API\Application\Request\Products\ListOfProducts\ListOfProductsParams;

MLFilesystem::gi()->loadClass('Core_Controller_Abstract');

/**
 * This process get all available product from Shopify daily and update product data in magnalister
 * if some changes are missing in executeUpdate it will be applied at latest after a day
 */
class ML_Shopify_Controller_Frontend_Do_ShopifyProductCache extends ML_Core_Controller_Abstract {

    protected $sType = 'ProductAddOrUpdate';

    protected $sMethodOfUpdate = 'cron';

    protected $iNumberOfRepeat = 1;

    protected $iCurrentPage = null;

    protected $oTimeZone = null;

    protected $sConfigKeyUpdatedAtMin = 'shopifyUpdatedAtMin';

    protected $sConfigKeyPage = 'shopifyProductPage';
    /**
     * Because of limitation in Shopify request to get only limited number of product (e.g. 10) update and cron could update product in magnalister very slowly
     * but to do it faster in magnalister, it iterates process in magnalister several time (e.g. 40 times),
     * this variable is number of iteration by each magnalister request(e.g. each ajax request in magnalister update or each cron request).
     * Iteration number will be increased with more count of product
     * @var int
     */
    protected $iLimitationOfIteration = 0;
    /**
     * @var MLLogger
     */
    protected $oLogger;

    /** @var int Count of Products that will be iterate through per page */
    protected $iShopifyLimitPerPage = 250;

    protected $iTotalCountOfProducts = 0;

    public function __construct() {
        $this->iLimitationOfIteration = $this->iShopifyLimitPerPage;

        parent::__construct();
        $this->oLogger = new MLLogger(MLShopifyAlias::getShopHelper()->getShopId(), $this->sType);
        $iExpirationTime = 3 * 24 * 60 * 60;//3 days
        $mValue = MLDatabase::factory('config')->set('mpid', 0)->set('mkey', 'shopifyProductCacheResetTime')->get('value');
        if (time() - (int)$mValue > $iExpirationTime) {
            //Resetting filter parameter of Shopify product list every 24 hours
            //if date filter of Shopify product-list doesn't work properly or if update date of shopify product was not set properly,
            //resetting will be helpful to force to get all products from scratch
            $this->out('Resetting date and page filters');
            MLDatabase::getDbInstance()->query("DELETE FROM `magnalister_config` WHERE mkey in('".$this->sConfigKeyPage."','shopifyProductCollectionCursor', 'shopifyCollectionUpdatedAtMin', '".$this->sConfigKeyUpdatedAtMin."');");
            MLDatabase::factory('config')->set('mpid', 0)->set('mkey', 'shopifyProductCacheResetTime')->set('value', time())->save();
        }

        $this->iTotalCountOfProducts = $this->getTotalCountOfProduct();
        $this->iLimitationOfIteration *= ceil($this->iTotalCountOfProducts / 2000);
    }

    public function renderAjax() {
        try {
            $this->execute();
            $aAjax = MLSetting::gi()->get('aAjax');
            if (empty($aAjax)) {
                throw new Exception;
            }
        } catch (Exception $oEx) {//if there is no data to be sync or if there is an error
            MLSetting::gi()->add('aAjax', array('success' => true));
        }
        if (MLHttp::gi()->isAjax()) {
            if (MLSetting::gi()->sMainController === null) {//after $this->execute sMainController is null, so we reset again ,
                MLSetting::gi()->set('sMainController', get_class($this));
            }
            $this->finalizeAjax();
        }
    }

    public function render() {
        $this->execute();
    }

    /**
     * @param $sStr
     * @return $this
     */
    protected function out($sStr) {
        if (!MLHttp::gi()->isAjax()) {//in ajax call in plugin we break maxitems and steps of each request so we don't need echo
            echo $sStr;
            $this->oLogger->addLog($sStr);
        }
        return $this;
    }

    public function execute() {
        $iStartTime = microtime(true);
        //Lock to prevent to run updating same product in same time from two different call
        //here we don't use MLCache, instead we use database cache
        //because this will be executed also during update process of plugin, and all cache files will be deleted automatically by updating
        $sCacheName = $this->sType.'_LOCK';
        $oCacheDB = MLDatabase::factory('config')->set('mpid', 0)->set('mkey', $sCacheName);
        if ($oCacheDB->exists() && (time() - (int)$oCacheDB->get('value')) < 15 * 60) {
            $this->out('Other process is running');
            return;
        }
        $oCacheDB->set('value', time())->save();

        $iIter = 0;

        while ($this->iNumberOfRepeat <= $this->iLimitationOfIteration) {
            try {
                $this->showHeaderAndFooter('Updating Shopify product cache');
                $aProducts = $this->getUpdatedProductsData();
                $blAnyProduct = false;
                foreach ($aProducts as $aProduct) {
                    $iIter++;
                    $blAnyProduct = true;
                    try {
                        $aProduct['MethodOfUpdate'] = $this->sMethodOfUpdate;
                        MLShopifyAlias::getProductModel()
                            ->loadByShopProduct($aProduct) //insert master product
                            ->getVariants() // insert variants
                        ;

                        $this->out('('.$iIter.' / '.$this->iTotalCountOfProducts.') Product id: '.$aProduct['id'].' is updated'."\n");
                    } catch (\Exception $oEx) {
                        $this->out(' A problem occurred by updating product id: '.$aProduct['id']."\n".$oEx->getMessage()."\n".$oEx->getTraceAsString());
                        MLMessage::gi()->addDebug($oEx);
                    }
                }

                if (!$blAnyProduct) {
                    $this->out(' There is no product to be added or updated'."\n");
                }

                if ((int)$this->getProgress() >= 100) {
                    $oTimeZone = $this->getTimeZone();
                    if ($oTimeZone === null) {
                        $oUpdateTime = new DateTime('now');
                    } else {
                        $oUpdateTime = new DateTime('now', $oTimeZone);
                    }
                    $oUpdateTime->modify('-5 minute');// To be sure if some products change during loading updating products
                    MLDatabase::factory('config')->set('mpid', 0)->set('mkey', $this->sConfigKeyUpdatedAtMin)->set('value', $oUpdateTime->format('c'))->save();
                    MLDatabase::factory('config')->set('mpid', 0)->set('mkey', $this->sConfigKeyPage)->set('value', 0)->save();
                    $this->showHeaderAndFooter(' Shopify update process is done');
                    break;
                } else {
                    MLDatabase::factory('config')->set('mpid', 0)->set('mkey', $this->sConfigKeyPage)->set('value', $this->getPage())->save();
                    $this->showHeaderAndFooter('Updating page '.$this->getPage().' was successful. Next page will be proceeded in next call automatically');
                }
            } catch (Exception $oEx) {
                $this->out($oEx->getMessage()."\n");

            }
            $this->iNumberOfRepeat++;
        }
        $this->out("\n\nComplete (".microtime2human(microtime(true) - $iStartTime).").\n");
        $oCacheDB->delete();
    }

    /**
     * return a number between 0 and 100 to present the percent of progress
     * @return float|int
     */
    public function getProgress() {
        $totalCount = $this->getTotalCountOfProduct();
        if ($totalCount === 0 || $this->getPage() > $totalCount) {
            return 100;
        } else {
            return $this->getPage() * $this->iShopifyLimitPerPage / $totalCount * 100;
        }
    }

    protected function getTotalCountOfProduct() {
        $countProductsParams = new CountProductsParams();
        $sUpdatedAtMin = $this->getUpdatedAtMin();
        if ($sUpdatedAtMin !== null) {
            $countProductsParams->setUpdatedAtMin($sUpdatedAtMin);
        }
        return (int)MLShopifyAlias::getProductHelper()->getProductListCount($countProductsParams);
    }

    /**
     * get date to limit products and get only updated product
     * @return string
     */
    protected function getUpdatedAtMin() {
        return MLDatabase::factory('config')->set('mpid', 0)->set('mkey', $this->sConfigKeyUpdatedAtMin)->get('value');
    }


    /**
     * @return int
     */
    protected function getPage() {
        if ($this->iCurrentPage === null) {
            $this->iCurrentPage = (int)MLDatabase::factory('config')->set('mpid', 0)->set('mkey', $this->sConfigKeyPage)->get('value');
        }
        return $this->iCurrentPage + $this->iNumberOfRepeat;
    }

    protected function getUpdatedProductsData() {
        $aListOfProductsParams = new ListOfProductsParams();
        $aListOfProductsParams->setLimit($this->iShopifyLimitPerPage);
        $aListOfProductsParams->setPage($this->getPage());
        $sUpdatedAtMin = $this->getUpdatedAtMin();
        if ($sUpdatedAtMin !== null) {
            $aListOfProductsParams->setUpdatedAtMin($sUpdatedAtMin);
        }
        return MLShopifyAlias::getProductHelper()->getProductListFromShopify($aListOfProductsParams);

    }

    protected function getTimeZone() {
        if ($this->oTimeZone === null) {
            $aShop = MLShopifyAlias::getShopHelper()->getShopConfigurationAsArray();
            if (!empty($aShop['timezone'])) {
                $this->oTimeZone = new DateTimeZone($aShop['timezone']);
            }
        }
        return $this->oTimeZone;
    }

    /**
     * show footer and header in each log
     * @param $sMsg
     */
    protected function showHeaderAndFooter($sMsg) {
        $this->out(
            "\n#######################################\n##\n".
            '##  '.$sMsg.
            "\n##\n#######################################\n\n");
    }

}