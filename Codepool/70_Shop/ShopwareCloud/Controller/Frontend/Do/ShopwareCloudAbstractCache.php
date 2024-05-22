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

include_once(DIR_MAGNALISTER_HELPER . 'APIHelper.php');
include_once(M_DIR_LIBRARY.'CronLogger.php');
include_once(M_DIR_LIBRARY.'MailService.php');

use library\CronLogger;

MLFilesystem::gi()->loadClass('Core_Controller_Abstract');

/**
 * This process get all available entities from Shopware Cloud daily and update entities data in magnalister
 * if some changes are missing in executeUpdate it will be applied at latest after a day
 */
abstract class ML_ShopwareCloud_Controller_Frontend_Do_ShopwareCloudAbstractCache extends ML_Core_Controller_Abstract {

    protected $sType = '';

    protected $sMethodOfUpdate = 'cron';

    protected $iNumberOfRepeat = 1;

    protected $iCurrentPage = null;

    protected $oTimeZone = null;


    protected $sConfigKeyUpdatedAtMin = '';
    protected $sReservedTimeConfigKeyUpdatedAtMin = '';
    protected $sConfigKeyPage = '';
    protected $sConfigKeyNextPage = '';
    /**
     * Because of limitation in shopware cloud request to get only limited number of entities (e.g. 10) update and cron could entities product in magnalister very slowly
     * but to do it faster in magnalister, it iterates process in magnalister several time (e.g. 40 times),
     * this variable is number of iteration by each magnalister request(e.g. each ajax request in magnalister update or each cron request).
     * Iteration number will be increased with more count of product
     * @var int
     */
    protected $iLimitationOfIteration = 0;
    /**
     * @var CronLogger
     */
    protected $oLogger;

    /** @var int Count of entities that will iterate through per page */
    protected $iShopwareCloudLimitPerPage = 10;

    protected $iTotalCountOfEntities = 0;

    protected $oCacheDB = null;

    protected $sNext = '';

    protected $apiHelper = null;

    protected $sShopId = null;

    protected $shopwareEntityRequest = '';

    /**
     * Starting point for get product list via sql query
     * @var int
     */
    protected $iStart;

    /**
     * Count of row in current sql query
     * @var int
     */
    protected $iCount;

    protected $isDelete = null;

    public function __construct() {
        parent::__construct();
        $this->isDelete = mb_stripos($this->sType, 'delete');
        $this->sShopId = MLShopwareCloudAlias::getShopHelper()->getShopId();
        $this->oLogger = new CronLogger(MLShopwareCloudAlias::getShopHelper()->getCustomerName(), $this->sType);
        $this->sConfigKeyPage = 'shopwareCloud' . $this->sType . 'Page';
        if (!$this->isDelete) {
            $this->sConfigKeyUpdatedAtMin = 'shopwareCloud' . $this->sType . 'UpdatedAtMin';
            $this->sReservedTimeConfigKeyUpdatedAtMin = $this->sType . 'ReservedTimeConfigKeyUpdatedAtMin';
            $this->sConfigKeyNextPage = $this->sType . 'shopwareCloud' . $this->sType . 'NextPage';

            $this->apiHelper = new APIHelper();
            $iExpirationTime = 3 * 24 * 60 * 60;//3 days
            $mValue = MLDatabase::factory('config')->set('mpid', 0)->set('mkey', 'shopwareCloud'.$this->sType.'CacheResetTime')->get('value');
            if (time() - (int)$mValue > $iExpirationTime) {
                $this->resetRequestParameter();
            }
        }
    }

    protected function initiatePaginationParameter() {
        $this->setNext(MLDatabase::factory('config')->set('mpid', 0)->set('mkey', $this->sConfigKeyNextPage)->get('value'));
        $this->iTotalCountOfEntities = $this->getTotalCountOfEntities();
        // adding one more iteration in case we have exact number of elements on the last page as we set in pagination
        // in order to trigger of the finalize function
        $iLimitationOfIteration = $this->iTotalCountOfEntities / $this->iShopwareCloudLimitPerPage;
        $iRoundedLimitationOfIteration = ceil($iLimitationOfIteration);
        $this->iLimitationOfIteration = $iLimitationOfIteration == $iRoundedLimitationOfIteration ? $iLimitationOfIteration + 1 : $iRoundedLimitationOfIteration;
    }

    public function execute() {
        if ($this->isDelete) {
            $this->executeDelete();
        } else {
            $this->executeCreateUpdate();
        }
    }

    private function executeCreateUpdate() {
        $iStartTime = microtime(true);
        if ($this->checkExistingProcess()) {
            $this->out('Other process is running'."\n");
            return;
        }
        $this->initiatePaginationParameter();
        $iCounter = $this->getOffset();

        $this->showHeaderAndFooter('Updating Shopware Cloud '. $this->sType .' cache');
        while ($this->iNumberOfRepeat <= $this->iLimitationOfIteration) {
            try {
                $responseEntities = $this->getUpdatedEntitiesDataCreate();
                if (is_array($responseEntities)) {
                    $entities = $responseEntities['data'];
                } else {
                    $entities = $responseEntities->getData();
                }
                $blAnyEntity = false;
                foreach ($entities as $entity) {
                    $iCounter++;
                    $blAnyEntity = true;
                    try {
                        $this->updateEntity($entity);
                        $this->saveEntityRelationships($entity);
                        if (is_array($entity)) {
                            $entityId = $entity['id'];
                        } else {
                            $entityId = $entity->getId();
                        }
                        $this->out('('.$iCounter.' / '.$this->iTotalCountOfEntities.') ' . $this->sType . ' id: '. $entityId.' is updated'."\n");
                    } catch (Throwable $oEx) {
                        $this->out(' A problem occurred by updating entity id: '. $entityId ."\n".$oEx->getMessage()."\n".$oEx->getTraceAsString());
                        $this->outThrowable($oEx);
                    }
                }

                if (!$blAnyEntity) {
                    $this->out(' There is no '. $this->sType .' to be added or updated'."\n");
                }
                MLDatabase::factory('config')->set('mpid', 0)->set('mkey', $this->sConfigKeyNextPage)->set('value', $this->getNext())->save();

                if ($this->getPage() === 1) {
                    $this->saveReservedUpdateTime();
                }

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
            $this->iNumberOfRepeat++;
        }
        try {
            if (MLRequest::gi()->data('blDebug') === 'true' || MLSetting::gi()->blDebug) {
                 echo str_replace("\\n", "\n", print_r(ML_ShopwareCloud_Helper_ShopwareCloudInterfaceRequestHelper::gi()->getLogPerRequest(), true));
            }
        } catch (Throwable $oEx) {
            $this->outThrowable($oEx);
        }
        $this->out("\n\nComplete (".microtime2human(microtime(true) - $iStartTime).").\n");
        $this->oCacheDB->delete();
    }

    private function executeDelete() {
        $iStartTime = microtime(true);

        while ($this->iNumberOfRepeat < $this->iLimitationOfIteration) {
            $this->showHeaderAndFooter('Shopware Cloud '. $this->sType .' cache');
            try {
                $aMPIds = $this->getUpdatedEntitiesDataDelete();
                if (array_values($aMPIds) === $aMPIds && count($aMPIds) > 0) {
                    $this->updateData($aMPIds);
                } else if (count($aMPIds) > 0) {
                    foreach ($aMPIds as $key => $ids) {
                        if (count($ids) == 0) {
                            $this->out(' There is no ' . $key . ' to be deleted'."\n");
                            unset($aMPIds[$key]);
                        }
                    }
                    $this->updateData($aMPIds);

                } else {
                    $this->out(' There is no ' . $this->sType . ' to be deleted'."\n");

                }
                if ($this->iStart + $this->iShopwareCloudLimitPerPage > $this->iCount) {
                    $this->showHeaderAndFooter('Shopware Cloud deleting process is done');
                    MLDatabase::factory('config')->set('mpid', 0)->set('mkey', $this->sConfigKeyPage)->set('value', 0)->save();
                    break;
                } else {
                    $this->showHeaderAndFooter('Shopware Cloud deleting page '.$this->getPage().' was successful. Next page will be proceeded in next call automatically');
                    MLDatabase::factory('config')->set('mpid', 0)->set('mkey', $this->sConfigKeyPage)->set('value', $this->getPage())->save();
                }

            } catch (Exception $oEx) {
                $this->out($oEx->getMessage()."\n");
            }

            $this->iNumberOfRepeat++;
        }

        $this->removeAdditionalEntities();
        $this->out("\n\nComplete (".microtime2human(microtime(true) - $iStartTime).").\n");
    }

    protected abstract function updateEntity($data);
    protected function saveEntityRelationships($data) {

    }

    protected function getTotalCountOfEntities() {
        $this->out('getTotalCountOfEntities function needs to be implemented');
        return 0;
    }

    protected function removeAdditionalEntities() {

    }

    /**
     * return a number between 0 and 100 to present the percent of progress
     * It is approximate progress of iterating products of shopify-shop
     * Because of new approach of Shopify with next and previous without page filter we cannot show the progress properly
     * @return float|int
     */
    public function getProgress() {
        $totalCount = $this->getTotalCountOfEntities();
        $sNext = MLDatabase::factory('config')->set('mpid', 0)->set('mkey', $this->sConfigKeyNextPage)->get('value');
        if (empty($sNext)) {
            return 100;
        } else if ($totalCount === 0) {//something in getTotalCountOfEntity and getUpdatedEntityData are different
            return 'error';
        } else {
            return $this->getPage() * $this->iShopwareCloudLimitPerPage / $totalCount * 100;
        }
    }

    protected function getOffset() {
        return ($this->getPage() - 1) * $this->iShopwareCloudLimitPerPage;
    }

    /**
     * return a number between 0 and 100 to present the percent of progress
     * It is approximate progress of iterating products of shopify-shop
     * It is exactly the percent of processed page of products, is used to show progress of downloading product to user
     * @return float|int
     */
    public function getDoneProgress() {
        $totalCount = $this->getTotalCountOfEntities();
        if ($this->getDonePage() > 0 && $totalCount > 0) {
            return $this->getDonePage() * $this->iShopwareCloudLimitPerPage / $totalCount * 100;
        }

        return 100;
    }

    /**
     * get date to limit entities and get only updated entity
     * @return string
     */
    protected function getUpdatedAtMin() {
        if ($this->getNext() === '') {
            return MLDatabase::factory('config')->set('mpid', 0)->set('mkey', $this->sConfigKeyUpdatedAtMin)->get('value');
        } else {
            return null;
        }
    }

    protected function getUpdatedAtTimeFilter() {
        $preparedFilters = array();
        $sUpdatedAtMin = $this->getUpdatedAtMin();
        if ($sUpdatedAtMin !== null) {
            $filters['or'] = [
                'type' => 'multi',
                'values' => [
                    'updatedAt' => [
                        'type' => 'range',
                        'values' => ['gte' => $sUpdatedAtMin]
                    ],
                    'createdAt' => [
                        'type' => 'range',
                        'values' => ['gte' => $sUpdatedAtMin]
                    ]

                ]
            ];

            $preparedFilters = $this->apiHelper->prepareFilters($filters, 'POST');
        }

        return $preparedFilters;
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

    protected function getDonePage() {
        if ($this->iCurrentPage === null) {
            $this->iCurrentPage = (int)MLDatabase::factory('config')->set('mpid', 0)->set('mkey', $this->sConfigKeyPage)->get('value');
        }
        return $this->iCurrentPage;
    }

    private function getUpdatedEntitiesDataCreate() {
        if ($this->getNext() !== '') {
            $page = $this->getNext();
        } else {
            $page = 1;
        }

        $preparedFilters = $this->getUpdatedAtTimeFilter();
        $preparedFilters['page'] = $page;
        $preparedFilters['limit'] = $this->iShopwareCloudLimitPerPage;
        $preparedFilters['total-count-mode'] = 1;
        $entities = $this->getEntities($preparedFilters);
        if (is_array($entities)) {
            $count = count($entities['data']);
        } else {
            $count = count((array) $entities->getData());
        }

        if ($count == $this->iShopwareCloudLimitPerPage) {
            $this->setNext($page + 1);
        } else {
            $this->setNext(null);
        }
        return $entities;
    }

    private function getUpdatedEntitiesDataDelete() {
        $result = array();
        $this->iStart = ($this->getPage() - 1) * $this->iShopwareCloudLimitPerPage;
        $aEntities = $this->getDBEntityIds();
        if (array_values($aEntities) === $aEntities) {
            $preparedFilters['ids'] = $aEntities;
            $result = $this->getDeleteEntityIds($preparedFilters, $aEntities);
        } else {
            foreach ($aEntities as $key => $entity) {
                $preparedFilters['ids'] = $entity;
                $result[$key] = $this->getDeleteEntityIds($preparedFilters, $entity, $key);
            }
        }

        return $result;
    }

    private function getDeleteEntityIds($preparedFilters, $aEntities, $type = '') {
        $oShopEntities = $this->getEntities($preparedFilters, $type);
        if (is_object($oShopEntities) && method_exists($oShopEntities, 'getData')) {
            foreach ($oShopEntities->getData() as $oShopEntity) {
                if (in_array($oShopEntity->getId(), $aEntities)) {
                    unset($aEntities[array_search($oShopEntity->getId(), $aEntities)]);
                }
            }
        }

        return array_values($aEntities);
    }

    protected abstract function getEntities($preparedFilters);

    protected function getDBEntityIds() {
        return array();
    }

    protected function checkExistingProcess() {
        //Lock to prevent to run updating same entity in same time from two different call
        //here we don't use MLCache, instead we use database cache
        //because this will be executed also during update process of plugin, and all cache files will be deleted automatically by updating
        $sCacheName = $this->sType . '_LOCK';
        $this->oCacheDB = MLDatabase::factory('config')->set('mpid', 0)->set('mkey', $sCacheName);
        if ($this->oCacheDB->exists() && (time() - (int)$this->oCacheDB->get('value')) < 5 * 60 && MLRequest::gi()->data('ignoreLock') === null) {
            $blReturn = true;
        } else {
            $this->oCacheDB->set('value', time())->save();
            $blReturn = false;
        }
        return $blReturn;
    }

    protected function finalize(): void {
        if ($this->sType === 'Product') {
            MLDatabase::getDbInstance()->query("DELETE FROM `magnalister_config` WHERE `mkey` = 'shopwareCloudStartingFirstProductImport';");
        }
        $reservedTime = MLDatabase::factory('config')->set('mpid', 0)->set('mkey', $this->sReservedTimeConfigKeyUpdatedAtMin)->get('value');
        MLDatabase::factory('config')->set('mpid', 0)->set('mkey', $this->sConfigKeyUpdatedAtMin)->set('value', $reservedTime)->save();
        MLDatabase::getDbInstance()->query("DELETE FROM `magnalister_config` WHERE `mkey` = '" . $this->sReservedTimeConfigKeyUpdatedAtMin . "';");
        MLDatabase::factory('config')->set('mpid', 0)->set('mkey', $this->sConfigKeyPage)->set('value', 0)->save();
        $this->showHeaderAndFooter(' Shopware Cloud update process is done');
    }

    protected function getTimeZone() {
        if ($this->oTimeZone === null) {
            $sCreatedAt = MLShopwareCloudAlias::getProductHelper()->getProductCreatedAt();
            if (!empty($sCreatedAt)) {
                $dateTime = new DateTime($sCreatedAt);
                $this->oTimeZone = $dateTime->getTimezone();
            }
        }
        return $this->oTimeZone;
    }

    protected function saveReservedUpdateTime() {
        $oTimeZone = $this->getTimeZone();
        if ($oTimeZone === null) {
            $oUpdateTime = new DateTime('now');
        } else {
            $oUpdateTime = new DateTime('now', $oTimeZone);
        }
        $oUpdateTime->modify('-5 minute');// To be sure if some entities change during loading updating entities
        MLDatabase::factory('config')->set('mpid', 0)->set('mkey',$this->sReservedTimeConfigKeyUpdatedAtMin)->set('value', $oUpdateTime->format('Y-m-d H:i:s'))->save();
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

    protected function resetRequestParameter(): void {
        //Resetting filter parameter of Shopware cloud entity list every 24 hours
        //if date filter of Shopware Cloud entity-list doesn't work properly or if update date of Shopware Cloud entity was not set properly,
        //resetting will be helpful to force to get all entities from scratch
        $this->out('Resetting date and page filters');
        MLDatabase::getDbInstance()->query("DELETE FROM `magnalister_config` WHERE mkey in(
                                               '".$this->sConfigKeyPage."',
                                               '".$this->sConfigKeyUpdatedAtMin."', 
                                               '".$this->sConfigKeyNextPage."', 
                                               '".$this->sType.'_LOCK'."');");
        MLDatabase::factory('config')->set('mpid', 0)->set('mkey', 'shopwareCloud'.$this->sType.'CacheResetTime')->set('value', time())->save();
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

        // skip developer notification - when we do not reach the api...
        if ($oExc->getMessage() === 'Too many request. Request failed two times. Received no data from Shopware API.') {
            return $this;
        }

        $this->developerNotification('Shopware Cron Exception Class:'.__CLASS__,
            'Shop Base URL: '.MLHttp::gi()->getBaseUrl()."\n".
            'HTTP Code: '.$oExc->getCode()."\n".
            'Error: '.$oExc->getMessage()."\n".
            'Backtrace: '.$oExc->getTraceAsString()."\n"
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

    protected function populateProductCategories($productId, $categoryIds) {
        $sValues = '';
        foreach ($categoryIds as $categoryId) {
            $rowPresent = MLDatabase::getDbInstance()->fetchRow("
                SELECT ShopwareCategoryID FROM ".MLDatabase::factory('ShopwareCloudCategoryRelation')->getTableName()."
                WHERE ShopwareCategoryID = '".$categoryId."'
                AND ShopwareProductID = '".$productId."'
                ");
            if (!$rowPresent) {
                $sValues .= "('".$categoryId . "','" . $productId."'),";
            }
        }

        if (!empty($sValues)) {
            MLDatabase::getDbInstance()->query("INSERT INTO ".MLDatabase::factory('ShopwareCloudCategoryRelation')->getTableName()." 
                                            (ShopwareCategoryID, ShopwareProductID) VALUES ".rtrim($sValues, ","));
        }
    }

    protected function developerNotification($sTitle, $sContent): void {
        $oMail = new MailService();
        $oMail->from('shopware-cron-problem@magnalister.com', ' magnalister Shopware Cloud Server')
            ->addTo('scrum@magnalister.com')
            ->subject($sTitle)
            ->content($sContent);
        $oMail->send();
    }

    /**
     * @param array $aMPIds
     */
    private function updateData(array $aMPIds) {
        try {
            $this->updateEntity($aMPIds);
            if (array_values($aMPIds) === $aMPIds) {
                $this->out(' magnalister ' . $this->sType . ' ids: ' . implode(',', $aMPIds) . " are deleted\n");
            } else {
                foreach ($aMPIds as $type => $ids) {
                    $this->out(' magnalister ' . $this->sType . ' ' . $type . ': ' . implode(',', $ids) . " are deleted\n");
                }
            }
        } catch (Exception $oEx) {
            $this->out(' A problem occurred by deleting magnalister product ids: '.implode(',', $aMPIds)."\n".$oEx->getMessage()."\n".$oEx->getTraceAsString());
            MLMessage::gi()->addDebug($oEx);
        }
    }

}
