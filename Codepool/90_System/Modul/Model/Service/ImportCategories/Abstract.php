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

abstract class ML_Modul_Model_Service_ImportCategories_Abstract extends ML_Modul_Model_Service_Abstract {

    const iExpiresLiveTime = 86400;

    /**
     * name of table
     * @var string $sTableName
     */
    protected $sTableName = '';

    protected $aRequestTimeouts = array(
        'iGetCategoriesTimeout' => 0,
    );

    protected $iImportCategoriesLimit = 3000;

    public function __construct() {
        MLDatabase::getDbInstance()->logQueryTimes(false);
        MagnaConnector::gi()->setTimeOutInSeconds(600);
        @set_time_limit(60 * 10); // 10 minutes per module
        parent::__construct();
    }

    public function __destruct() {
        MagnaConnector::gi()->resetTimeOut();
        MLDatabase::getDbInstance()->logQueryTimes(true);
    }

    protected function getGetCategoriesRequest() {
        $aRequest = array(
            'ACTION' => 'GetCategories',
            'MODE' => 'GetCategories',
            'OFFSET' => (ctype_digit(MLRequest::gi()->data('offset'))) ? (int)MLRequest::gi()->data('offset') : 0,
            'LIMIT' => ((int)MLRequest::gi()->data('maxitems') > 0) ? (int)MLRequest::gi()->data('maxitems') : $this->iImportCategoriesLimit,
        );
        if ((int)MLRequest::gi()->data('steps') > 0) {
            $aRequest['steps'] = (int)MLRequest::gi()->data('steps');
        }

        return $aRequest;
    }

    public function execute() {
        $timestamp = date('Y-m-d H:i:s', time() + self::iExpiresLiveTime);

        $aRequest = $this->getGetCategoriesRequest();
        $this->log('FetchCategories'."\n\n", self::LOG_LEVEL_LOW);
        try {
            do {
                $dCategory = array();
                MagnaConnector::gi()->setTimeOutInSeconds($this->aRequestTimeouts['iGetCategoriesTimeout']);
                $aResponse = MagnaConnector::gi()->submitRequest($aRequest);
                $this->log(
                    'Received '.count($aResponse['DATA']).' categories '.
                    '('.($aRequest['OFFSET'] + count($aResponse['DATA'])).' of '.$aResponse['NUMBEROFLISTINGS'].') '.
                    'in  '.microtime2human($aResponse['Client']['Time'])."\n",
                    self::LOG_LEVEL_LOW
                );
                $aResponse['DATA'] = empty($aResponse['DATA']) ? array() : $aResponse['DATA'];

                //prepare data for insert in database
                foreach ($aResponse['DATA'] as $rCategory) {
                    $dCategory[] = array(
                        'CategoryID' => $rCategory['CategoryID'],
                        'CategoryName' => $rCategory['CategoryName'],
                        'ParentID' => $rCategory['ParentID'],
                        'LeafCategory' => $rCategory['LeafCategory'],
                        'Expires' => $timestamp,
                    );
                }
                //Insert categories in the database
                $this->log('InsertCategories'."\n\n".'Inserted  '.count($dCategory).' categories ', self::LOG_LEVEL_LOW);
                $chunks = array_chunk($dCategory, 100);
                foreach ($chunks as $chunk) {
                    MLDatabase::getDbInstance()->batchinsert($this->sTableName, $chunk, true);
                }

                $aRequest['OFFSET'] += $aRequest['LIMIT'];
                if (isset($aRequest['steps'])) {
                    $aRequest['steps']--;
                }

                if ($aRequest['OFFSET'] < $aResponse['NUMBEROFLISTINGS']) {
                    $this->out(array(
                        'Finished' => false,
                        'Done' => (int)$aRequest['OFFSET'],
                        'Step' => isset($aRequest['steps']) ? $aRequest['steps'] : false,
                        'Total' => $aResponse['NUMBEROFLISTINGS'],
                    ));
                    $blNext = true;
                } else {
                    $this->out(array(
                        'Finished' => true,
                        'Done' => (int)$aRequest['OFFSET'],
                        'Step' => isset($aRequest['steps']) ? $aRequest['steps'] : false,
                        'Total' => $aResponse['NUMBEROFLISTINGS'],
                    ));
                    $blNext = false;
                }
                if (isset($aRequest['steps']) && $aRequest['steps'] <= 1) {
                    $this->out(array(
                        'Finished' => true,
                        'Done' => (int)$aRequest['OFFSET'],
                        'Step' => isset($aRequest['steps']) ? $aRequest['steps'] : false,
                        'Total' => $aResponse['NUMBEROFLISTINGS'],
                    ));
                    $blNext = false;
                }
            } while ($blNext);

            // delete old categories from the database
            $this->log('RemoveOldCategories'."\n\n", self::LOG_LEVEL_LOW);
            MLDatabase::getDbInstance()->query('DELETE FROM `'.$this->sTableName.'` WHERE Expires <> "'.$timestamp.'"');

        } catch (MagnaExeption $oEx) {
            $this->log($oEx->getMessage(), self::LOG_LEVEL_MEDIUM);
        }
        MLCache::gi()->flush();

        return $this;
    }

    protected function out($mValue) {
        if (!MLHttp::gi()->isAjax()) {
            echo is_array($mValue) ? "\n{#".base64_encode(json_encode(array_merge(array('Marketplace' => MLModul::gi()->getMarketPlaceName(), 'MPID' => MLModul::gi()->getMarketPlaceId(),), $mValue)))."#}\n\n" : $mValue."\n";
            flush();
        } else {
            if (isset($mValue['Done']) && $mValue['Finished'] && $mValue['Total'] ) {
                MLModul::gi()->setConfig('importcategoriesoffset', $mValue['Done']);
                MLSetting::gi()->add(
                    'aAjax',
                    array(
                        'success' => $mValue['Finished'],
                        'error' => '',
                        'offset' => $mValue['Done'],
                        'info' => array(
                            'total' => $mValue['Total'],
                            'current' => $mValue['Done'],
                            'purge' => false,
                        ),
                    )
                );
            }
        }

        return $this;
    }

}
