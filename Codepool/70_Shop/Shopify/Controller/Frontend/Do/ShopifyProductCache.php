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
 * (c) 2010 - 2023 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

use Magna\Library\MLLogger;
use Magna\Service\MailService;
use Shopify\API\Application\Request\MetaField\ListOfMetaField\ListOfMetaFieldParams;
use Shopify\API\Application\Request\Products\CountProducts\CountProductsParams;
use Shopify\API\Application\Request\Products\ListOfCollectionsOfProduct\ListOfCollectionsOfProductParams;
use Shopify\API\Application\Request\Products\ListOfProducts\ListOfProductsParams;

MLFilesystem::gi()->loadClass('Core_Controller_Abstract');

/**
 * This process get all available product from Shopify daily and update product data in magnalister
 * if some changes are missing in executeUpdate it will be applied at latest after a day
 */
class ML_Shopify_Controller_Frontend_Do_ShopifyProductCache extends ML_Core_Controller_Abstract {

    protected $sType = 'Product';

    protected $sMethodOfUpdate = 'cron';

    protected $iNumberOfRepeat = 1;

    protected $iCurrentPage = null;

    protected $oTimeZone = null;

    protected $sConfigKeyUpdatedAtMin = 'shopifyUpdatedAtMin';

    protected $sConfigKeyPage = 'shopifyProductPage';

    protected $sConfigKeyNextPage = 'shopifyProductNextPage';

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

    /** @var int Count of Products that will be iterated through per page */
    protected $iShopifyLimitPerPage = 100;

    protected $iTotalCountOfProducts = 0;

    protected $oCacheDB = null;

    protected $sNext = '';

    public function __construct() {
        if (!class_exists('MLShopifyAlias')) {
            include_once __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Helper'.DIRECTORY_SEPARATOR.'ShopifyInterfaceRequestHelper.php';
            include_once __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Helper'.DIRECTORY_SEPARATOR.'MLShopifyAlias.php';

        }
        parent::__construct();
        $this->oLogger = new MLLogger(MLShopifyAlias::getShopHelper()->getShopId(), $this->sType);
        $iExpirationTime = 5 * 24 * 60 * 60;//5 days
        $mValue = MLDatabase::factory('config')->set('mpid', 0)->set('mkey', 'shopifyProductCacheResetTime')->get('value');
        if (time() - (int)$mValue > $iExpirationTime) {
            $this->resetRequestParameter();
        }
    }

    protected function initiatePaginationParameter() {
        $this->setNext(MLDatabase::factory('config')->set('mpid', 0)->set('mkey', $this->sConfigKeyNextPage)->get('value'));
        $this->iTotalCountOfProducts = $this->getTotalCountOfProduct();
        $this->iLimitationOfIteration = ceil($this->iTotalCountOfProducts / 1000);
    }

    public function execute() {
        $iStartTime = microtime(true);
        if ($this->checkExistingProcess()) {
            $this->out('Other process is running'."\n");
            return;
        }
        $this->initiatePaginationParameter();
        $iCounter = $this->getOffset();

        while ($this->iNumberOfRepeat <= $this->iLimitationOfIteration) {
            try {
                $this->showHeaderAndFooter('Updating Shopify product cache');
                $aProducts = $this->getUpdatedProductsData();
                $blAnyProduct = false;
                foreach ($aProducts as $aProduct) {
                    $iCounter++;
                    $blAnyProduct = true;
                    try {
                        $aProduct['MethodOfUpdate'] = $this->sMethodOfUpdate;
                        MLShopifyAlias::getProductModel()
                            ->loadByShopProduct($aProduct) //insert master product
                            ->getVariants(); // insert variants
                        $this->populateProductCollections($aProduct['id']);
                        $this->populateProductMetaFields($aProduct['id'], 'product');
                        foreach ($aProduct['variants'] as $variant) {
                            $this->populateProductMetaFields($variant['id'], 'productVariant');
                        }
                        $this->out('('.$iCounter.' / '.$this->iTotalCountOfProducts.') Product id: '.$aProduct['id'].' is updated'."\n");
                    } catch (\Throwable $oEx) {
                        $this->out(' A problem occurred by updating product id: '.$aProduct['id']."\n".$oEx->getMessage()."\n".$oEx->getTraceAsString());
                        $this->outThrowable($oEx);
                    }
                }

                if (!$blAnyProduct) {
                    $this->out(' There is no product to be added or updated'."\n");
                }
                MLDatabase::factory('config')->set('mpid', 0)->set('mkey', $this->sConfigKeyNextPage)->set('value', $this->getNext())->save();

                if ($this->getNext() === '') {
                    $this->finalize();
                    break;
                } else {
                    MLDatabase::factory('config')->set('mpid', 0)->set('mkey', $this->sConfigKeyPage)->set('value', $this->getPage())->save();
                    $this->showHeaderAndFooter('Updating page '.$this->getPage().' was successful. Next page will be proceeded in next call automatically');
                }
            } catch (Throwable $oEx) {
                $this->outThrowable($oEx);

            }
            //later this could be removed
            if ($this->iNumberOfRepeat > 10) {
                break;
            }
            $this->iNumberOfRepeat++;
        }
        try {
            if (MLRequest::gi()->data('blDebug') === 'true' || MLSetting::gi()->blDebug) {
                echo str_replace("\\n", "\n", print_r(ML_Shopify_Helper_ShopifyInterfaceRequestHelper::gi()->getLogPerRequest(), true));
            }
        } catch (\Throwable $oEx) {
            $this->outThrowable($oEx);
        }
        $this->out("\n\nComplete (".microtime2human(microtime(true) - $iStartTime).").\n");
        $this->oCacheDB->delete();
    }

    /**
     * return a number between 0 and 100 to present the percent of progress
     * It is approximate progress of iterating products of shopify-shop
     * It used in after-update of magnalister, and it is always one page more than processed page
     * @return float|int
     */
    public function getProgress() {
        $totalCount = $this->getTotalCountOfProduct();
        $sNext = MLDatabase::factory('config')->set('mpid', 0)->set('mkey', $this->sConfigKeyNextPage)->get('value');
        if (empty($sNext)) {
            return 100;
        } else if ($totalCount === 0) {//something in getTotalCountOfProduct and getUpdatedProductData are different
            return 'error';
        } else {
            return $this->getPage() * $this->iShopifyLimitPerPage / $totalCount * 100;
        }
    }


    /**
     * return a number between 0 and 100 to present the percent of progress
     * It is approximate progress of iterating products of shopify-shop
     * It is exactly the percent of processed page of products, is used to show progress of downloading product to user
     * @return float|int
     */
    public function getDoneProgress() {
        $totalCount = $this->getTotalCountOfProduct();
        if ($this->getDonePage() > 0) {
            return $this->getDonePage() * $this->iShopifyLimitPerPage / $totalCount * 100;
        }
    }

    protected function getOffset() {
        return ($this->getPage() - 1) * $this->iShopifyLimitPerPage;
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
        if ($this->getNext() === '') {
            return MLDatabase::factory('config')->set('mpid', 0)->set('mkey', $this->sConfigKeyUpdatedAtMin)->get('value');
        } else {
            return null;
        }
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

    /**
     * @return int
     */
    protected function getDonePage() {
        if ($this->iCurrentPage === null) {
            $this->iCurrentPage = (int)MLDatabase::factory('config')->set('mpid', 0)->set('mkey', $this->sConfigKeyPage)->get('value');
        }
        return $this->iCurrentPage;
    }

    protected function getUpdatedProductsData() {
        $aListOfProductsParams = new ListOfProductsParams();
        //        $this->showD($this->getNext(), 'Next before');
        if ($this->getNext() !== '') {
            $aListOfProductsParams->setNext($this->getNext());
        }
        $aListOfProductsParams->setLimit($this->iShopifyLimitPerPage);
        $sUpdatedAtMin = $this->getUpdatedAtMin();
        if ($sUpdatedAtMin !== null) {
            $this->showD($sUpdatedAtMin, __LINE__);
            $aListOfProductsParams->setUpdatedAtMin($sUpdatedAtMin);
        }
        $aProduct = MLShopifyAlias::getProductHelper()->getProductListFromShopify($aListOfProductsParams);
        $this->setNext($aListOfProductsParams->getNext());
        //        $this->showD($aListOfProductsParams->getNext(), 'Next after');
        return $aProduct;
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


    protected function checkExistingProcess() {
        //Lock to prevent to run updating same product in same time from two different call
        //here we don't use MLCache, instead we use database cache
        //because this will be executed also during update process of plugin, and all cache files will be deleted automatically by updating
        $sCacheName = $this->sType.'_LOCK';
        $this->oCacheDB = MLDatabase::factory('config')->set('mpid', 0)->set('mkey', $sCacheName);
        if ($this->oCacheDB->exists() && (time() - (int)$this->oCacheDB->get('value')) < 5 * 60) {
            $blReturn = true;
        } else {
            $this->oCacheDB->set('value', time())->save();
            $blReturn = false;
        }
        return $blReturn;
    }

    protected function finalize(): void {
        $oTimeZone = $this->getTimeZone();
        if ($oTimeZone === null) {
            $oUpdateTime = new DateTime('now');
        } else {
            $oUpdateTime = new DateTime('now', $oTimeZone);
        }
        $oUpdateTime->modify('-5 minute');// To be sure if some products change during loading updating products
        MLDatabase::factory('config')->set('mpid', 0)->set('mkey', $this->sConfigKeyUpdatedAtMin)->set('value', $oUpdateTime->format('c'))->save();
        MLDatabase::getDbInstance()->query("DELETE FROM `magnalister_config` WHERE `mkey` = 'shopifyStartingFirstProductImport';");
        MLDatabase::factory('config')->set('mpid', 0)->set('mkey', $this->sConfigKeyPage)->set('value', 0)->save();
        $this->showHeaderAndFooter(' Shopify update process is done');
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

    public function resetRequestParameter(): void {
        //Resetting filter parameter of Shopify product list every 24 hours
        //if date filter of Shopify product-list doesn't work properly or if update date of shopify product was not set properly,
        //resetting will be helpful to force to get all products from scratch
        $this->out('Resetting date and page filters');
        MLDatabase::getDbInstance()->query("DELETE FROM `magnalister_config` WHERE mkey in(
                                               '".$this->sConfigKeyPage."',
                                               'shopifyProductCollectionCursor', 
                                               'shopifyCollectionUpdatedAtMin', 
                                               '".$this->sConfigKeyUpdatedAtMin."', 
                                               '".$this->sConfigKeyNextPage."', 
                                               '".$this->sType.'_LOCK'."');");
        //the query is very resource consuming, the old product with missing vendor will be updated till 3 day after update
        //$this->populateProductVendor();
        MLDatabase::factory('config')->set('mpid', 0)->set('mkey', 'shopifyProductCacheResetTime')->set('value', time())->save();
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

    /**
     * @param $oExc Throwable
     * @return $this
     */
    protected function outThrowable($oExc) {
        if (!MLHttp::gi()->isAjax()) {//in ajax call in plugin we break max items and steps of each request so we don't need echo
            $sStr = $oExc->getMessage()."\n";
            echo $sStr;
            $this->oLogger->addLog($sStr);
        }
        $this->developerNotification('Shopify Cron Exception Class:'.__CLASS__,
            'Shop Base URL: '.MLHttp::gi()->getBaseUrl()."\n".
            'Error: '.$oExc->getMessage()."\n".
            'Error Trace: '.
            $oExc->getFile().'('.$oExc->getLine().')'."\n".
            $oExc->getTraceAsString()."\n"
        );
        return $this;
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

    protected function showD($sMsg, $line = '') {
        $this->out(
            "\n".
            $line.'---  '.$sMsg.
            "\n\n");
    }

    protected function setNext($sNext) {
        if (empty($sNext)) {
            $sNext = '';
        }
        $this->sNext = $sNext;
        return $this;
    }

    protected function getNext() {
        if (empty($this->sNext)) {
            $this->sNext = '';
        }
        return $this->sNext;
    }

    protected function populateProductCollections($id) {
        $aListOfCollectionsOfProductParams = (new ListOfCollectionsOfProductParams())->setShopifyProductGId($id);
        $aProductCollection = MLShopifyAlias::getProductHelper()->getCollectionsOfProductFromShopify($aListOfCollectionsOfProductParams);
        MLShopifyAlias::getProductHelper()->updateShopifyCollection($aProductCollection);
    }

    private function populateProductMetaFields($id, $objectName) {
        $aListOfMetaFieldOfProductParams = (new ListOfMetaFieldParams())
            ->setObjectName($objectName)
            ->setShopifyProductGId($id);
        $aProductMetaField = MLShopifyAlias::getProductHelper()->getMetaFieldOfAnObjectFromShopify($aListOfMetaFieldOfProductParams);
        MLShopifyAlias::getProductHelper()->updateShopifyMetaField($aProductMetaField);
    }

    /**
     * If it is the first time after update, that is populating vendor, with these query we are sure tha all vendor are imported correctly
     * It could be removed later
     */
    protected function populateProductVendor() {
        try {
            if ((MLRequest::gi()->data('blDebug') === 'true' || MLSetting::gi()->blDebug) && !MLHttp::gi()->isAjax()) {
                echo str_replace("\\n", "\n", print_r(ML_Shopify_Helper_ShopifyInterfaceRequestHelper::gi()->getLogPerRequest(), true));
            }
            $vendors = MLDatabase::getDbInstance()->fetchArray(
                'SELECT `ShopifyVendor`'.
                ' FROM `'.MLShopifyAlias::getProductModel()->getTableName().'`'.
                ' GROUP BY `ShopifyVendor` '.
                " HAVING `ShopifyVendor` != '' ".
                ' ORDER BY `ShopifyVendor` ASC'
            );
            MLShopifyAlias::getProductHelper()->updateShopifyVendor($vendors);
            MLDatabase::getDbInstance()->query('
        UPDATE `magnalister_products`
        INNER JOIN `magnalister_shopify_product_vendor` ON `magnalister_products`.`ShopifyVendor` =`magnalister_shopify_product_vendor`.`ShopifyVendorTitle`
        SET `ShopifyVendorId` = `VendorId`');

        } catch (\Throwable $oEx) {
            $this->outThrowable($oEx);
        }
    }

    protected function developerNotification($sTitle, $sContent): void {
        $oMail = new MailService();
        $oMail->from('shopify-cron-problem@magnalister.com', ' magnalister Shopify Server')
            ->addTo('masoud.khodaparast@magnalister.com')
            ->subject($sTitle)
            ->content($sContent);
        $success = $oMail->send();
    }


}
