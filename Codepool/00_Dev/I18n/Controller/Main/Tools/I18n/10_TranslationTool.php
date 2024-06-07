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

MLFilesystem::gi()->loadClass('Core_Controller_Abstract');

class ML_I18n_Controller_Main_Tools_I18n_TranslationTool extends ML_Core_Controller_Abstract {
    protected $aParameters = array('controller');

    /** @var ML_Database_Model_Query_Select */
    protected $oSelect;

    public function getTargetOption() {
        $targetOption = MLRequest::gi()->data('target_option');
        return $targetOption === null ? 'All' : $targetOption;
    }

    public function callAjaxUpdateTranslation() {
        try {
            MLMessage::gi()->addDebug(MLHttp::gi()->getRequest());
            $hash = hash('sha3-256', MLRequest::gi()->get('translationkey') . MLRequest::gi()->get('filerelativepath'));
            $oTable = MLDatabase::factory('translationupdate')
                ->setTableName($this->getChangesTable())
                ->set('SHA3256Key', $hash)
                ->set('TranslationKey', MLRequest::gi()->get('translationkey'))
                ->set('FileRelativePath', MLRequest::gi()->get('filerelativepath'));
            if (MLRequest::gi()->data('value') !== null) {
                $oTable->set(MLRequest::gi()->data('language'), MLRequest::gi()->get('value'));
            }
            if (MLRequest::gi()->data('status') !== null) {
                $oTable->set('Status', MLRequest::gi()->get('status'));
            }
            if (MLRequest::gi()->data('comment') !== null) {
                $oTable->set('Comment', MLRequest::gi()->get('comment'));
            }
            $oTable->save();

            $oTable = MLDatabase::factory('translationupdate')
                ->setTableName($this->getChangesTable());
            if ($oTable
                ->set('SHA3256Key', $hash)
                ->set('TranslationKey', MLRequest::gi()->data('translationkey'))
                ->set('FileRelativePath', MLRequest::gi()->data('filerelativepath'))
                ->exists()) {
                MLMessage::gi()->addSuccess('Tranlation Key"' . $oTable->get('TranslationKey') . '" With SHA3256 Key "' . $oTable->get('SHA3256Key') . '" is saved');
            }
        } catch (\Exception $ex) {
            MLMessage::gi()->addDebug($ex);
        }
    }

    public function getBaseOption() {
        $baseOption = MLRequest::gi()->data('base_option');
        return $baseOption === null ? 'Filled' : $baseOption;
    }

    public function getUpdatedOption() {
        $updatedOption = MLRequest::gi()->data('updated_option');
        return $updatedOption === null ? 'All' : $updatedOption;
    }

    public function __construct() {
        parent::__construct();
        MLSetting::gi()->add('aCss', 'translation.tool.css?%s');
        //MLMessage::gi()->addDebug(__LINE__.':'.microtime(true), $this->deeplIt('Wie können wir das schreiben?'));
        $this->manageSelectQuery();
        if (MLRequest::gi()->data('deepltoken') !== null) {
            MLSession::gi()->set('deepltoken', MLRequest::gi()->data('deepltoken'));
        }

    }

    public function getChangesTable() {
        $changeTable = MLRequest::gi()->data('changes_table');
        return $changeTable === null ? MLDatabase::factory('translationupdate')->getTableName() : $changeTable;
    }

    public function getListOfStatuses() {
        return [
            'open' => 'Open',
            'translated_via_deepl' => 'Translated via DeepL',
            'to_be_checked_by_Developer' => 'To be checked by Developer',
            'to_be_reviewed' => 'To be reviewed',
            'approved' => 'Approved'
        ];
    }


    protected function callAjaxInitializeTranslationTable() {
        try {
            $aFileList = MLCache::gi()->get('List_Of_I18n_File.json');
        } catch (\Exception $ex) {
            $this->getNewestI18nFiles();
            $this->cacheFileList();
            $aFileList = MLCache::gi()->get('List_Of_I18n_File.json');
        }

        $total = count($aFileList['DE']);
        $offset = (int)MLRequest::gi()->data('offset');
        $aMissingI18ns = [];
        foreach (['DE', 'EN', 'FR', 'ES'] as $lang) {
            if (isset($aFileList[$lang][$offset]) && file_exists($aFileList[$lang][$offset])) {
                $this->initializeTranslationTable($lang, $aFileList[$lang][$offset]);
            } else {
                $aMissingI18ns[$lang] = $aFileList['DE'][$offset];
            }
        }

        MLSetting::gi()->add(
            'aAjax',
            array(
                'success' => $offset >= $total,
                'error' => '',
                'offset' => $offset + 1,
                'info' => array(
                    'total' => $total,
                    'current' => $offset,
                ),
            )
        );
    }


    protected function callAjaxDeeplTranslation() {
        MLDatabase::getDbInstance()->setDoLogQueryTimes(false);
        $offset = (int)MLRequest::gi()->data('offset');
        MLMessage::gi()->addDebug($this->oSelect->getQuery(false));
        $total = $this->oSelect->getCount();

        $this->oSelect->limit($offset, 1);
        $this->oSelect->where('t.`' . $this->getBaseLanguage() . '` IS NOT NULL AND t.`' . $this->getBaseLanguage() . '` <> ""');
        $this->oSelect->where('t.`' . $this->getTargetLanguage() . '` IS NULL');// translate only missing translation via DeepL
        $aData = $this->oSelect->getRowResult();
        MLMessage::gi()->addDebug($this->oSelect->getQuery(false));
        //MLMessage::gi()->addDebug($aData['TranslationKey']);
        //file_put_contents(__DIR__.'/deepl.log', "\n"."\n". $aData['TranslationKey']."\n",8);
        try {
            $oTable = MLDatabase::factory('translationupdate')->setTableName($this->getChangesTable());
            if (!empty($aData[$this->getBaseLanguage()])) {
                $deeplTranslation = $this->deeplIt($aData[$this->getBaseLanguage()]);
                if (!empty($deeplTranslation)) {
                    $oTable
                        ->set('SHA3256Key', $aData['SHA3256Key'])
                        ->set('TranslationKey', $aData['TranslationKey'])
                        ->set('FileRelativePath', $aData['FileRelativePath'])
                        ->set('Status', 'translated_via_deepl')
                        ->set($this->getTargetLanguage(), $deeplTranslation)
                ->save();
                    //MLMessage::gi()->addDebug($aData['TranslationKey'], array($oTable->exists()));
                    MLSetting::gi()->add(
                        'aAjax',
                        array(
                            'success' => $offset >= $total,
                            'error' => '',
                            'offset' => $offset + 1,
                            'info' => array(
                                'total' => $total,
                                'current' => $offset,
                            ),
                        )
                    );
                } else {
                    throw new \Exception('Receiving empty or wrong response from DeepL');
                }

            }
        } catch (\Exception $ex) {
            MLMessage::gi()->addError($ex);
            $this->showErrorInPopupProgressBar($ex->getMessage());
            MLSetting::gi()->add(
                'aAjax',
                array(
                    'success' => true,
                    'error' => true,
                    'offset' => $offset + 1,
                    'info' => array(
                        'total' => $total,
                        'current' => $offset,
                    ),
                )
            );
        }
    }

    public function getListOfLang() {
        return ['DE', 'EN', 'FR', 'ES'];
    }

    public function getStatistic() {
        return array(
            'total' => $this->oSelect->getCount(),
            'current' => MLRequest::gi()->data('page') !== null ? (int)MLRequest::gi()->data('page') : 1,
        );
    }

    public function getKeySearch() {
        $lang = MLRequest::gi()->data('key_search');
        return $lang === null ? null : $lang;
    }

    public function getFromLanguageSearch() {
        $lang = MLRequest::gi()->data('from_lang_search');
        return $lang === null ? null : $lang;
    }

    public function getToLanguageSearch() {
        $lang = MLRequest::gi()->data('to_lang_search');
        return $lang === null ? null : $lang;
    }

    public function getTranslationStatus() {
        $lang = MLRequest::gi()->data('translation_status');
        return $lang === null ? null : $lang;
    }

    public function getTranslationComment() {
        $lang = MLRequest::gi()->data('translation_comment');
        return $lang === null ? null : $lang;
    }

    public function getFilepathSearch() {
        $lang = MLRequest::gi()->data('filepath_search');
        return $lang === null ? null : $lang;
    }

    public function getBaseLanguage() {
        $lang = MLRequest::gi()->data('base_language');
        return $lang === null ? $this->getListOfLang()[0] : $lang;
    }

    public function getTargetLanguage() {
        $lang = MLRequest::gi()->data('target_language');
        return $lang === null ? $this->getListOfLang()[1] : $lang;
    }

    protected function getListOfContent() {
        $aContent = [];
        $base = $this->getBaseLanguage();
        $target = $this->getTargetLanguage();
        if ($base !== null && $target !== null) {
            $aContent = $this->oSelect->getResult();
        }
        return $aContent;
    }

    protected function initializeTranslationTable($slang, $sPath) {

        MLDatabase::getDbInstance()->setDoLogQueryTimes(false);
        $oI18n = MLI18n::gi()->setReplaceMode(false);
        $oRef = new ReflectionClass($oI18n);
        $oProp = $oRef->getProperty('aData');
        $oProp->setAccessible(true);
        $oProp->setValue($oI18n, array());
        include($sPath);
//        var_dump(array($slang, $sPath), count($oI18n->data()));
        if (count($oI18n->data()) != 0) {
            $aFlatData = MLHelper::getArrayInstance()->nested2Flat($oI18n->data());
            foreach ($aFlatData as $sKey => $sValue) {
                $sPath = str_ireplace(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $sPath);
                $sGenericPath = str_ireplace(['/' . $slang . '/', MLFilesystem::getLibPath()], ['/#lang#/', ''], $sPath);
                MLDatabase::factory('translation')->set('TranslationKey', $sKey)->set($slang, $sValue)->set('FileRelativePath', $sGenericPath)->save();
            }
        }

        $oProp->setValue($oI18n, array());
        $oProp->setAccessible(false);
    }

    protected function cacheFileList() {
        $aListOfI18nFiles = [];
        foreach (['DE' => '[dD][eE]', 'EN' => '[eE][nN]', 'FR' => '[fF][rR]', 'ES' => '[eE][sS]'] as $slang => $sRegEx) {
            $sDs = DIRECTORY_SEPARATOR;
            foreach (MLFilesystem::gi()->glob(MLFilesystem::getLibPath() . $sDs . 'Codepool' . $sDs . '*' . $sDs . '*' . $sDs . '[iI]18[nN]' . $sDs . $sRegEx, GLOB_BRACE) as $sModulPath) {

                foreach (MLFilesystem::gi()->glob($sModulPath . DIRECTORY_SEPARATOR . '*.php', GLOB_BRACE) as $sPath) {
                    if (strpos($sPath, '00_Dev') === false) {
                        $aListOfI18nFiles[$slang][] = $sPath;
                    }

                }
            }
        }
        MLCache::gi()->set('List_Of_I18n_File.json', $aListOfI18nFiles, 5 * 60);
    }

    protected function getNewestI18nFiles() {
        $sLibDir = MLFilesystem::getLibPath();
        $sCacheI18nDir = MLFilesystem::getWritablePath() . 'i18n';
        $sBaseUrl = str_replace('/api.', '/api-hades.', MLSetting::gi()->get('sUpdateUrl'));//force to get file from hades to make faster
        $sZipPath = $sBaseUrl . 'magnalister/files.list?format=zip&build=12345';
        $sDstPath = MLFilesystem::getCachePath() . 'I18nZip/';
        $oRemote = MLHelper::gi('remote');
        /* @var $oRemote ML_Core_Helper_Remote */
        $zipFileName = 'I18nZip.zip';
        $sContent = $oRemote->fileGetContents($sZipPath, $warnings, 20);
        MLCache::gi()->set($zipFileName, $sContent, 60);
        $oZip = new ZipArchive();
        $oZip->open(MLFilesystem::getCachePath($zipFileName));
        @set_time_limit(60 * 10); // 10 minutes
        $oZip->extractTo($sCacheI18nDir);
        $oZip->close();
        $aListOfI18nFiles = [];
        foreach (['DE' => '[dD][eE]', 'EN' => '[eE][nN]', 'FR' => '[fF][rR]', 'ES' => '[eE][sS]'] as $slang => $sRegEx) {
            $sDs = DIRECTORY_SEPARATOR;
            foreach (MLFilesystem::gi()->glob($sCacheI18nDir . $sDs . 'Codepool' . $sDs . '*' . $sDs . '*' . $sDs . '[iI]18[nN]' . $sDs . $sRegEx, GLOB_BRACE) as $sModulPath) {

                foreach (MLFilesystem::gi()->glob($sModulPath . DIRECTORY_SEPARATOR . '*.php', GLOB_BRACE) as $sPath) {
                    if (strpos($sPath, '00_Dev') === false) {
                        //MLMessage::gi()->addDebug(__LINE__.':'.microtime(true), array($sPath,  str_replace($sCacheI18nDir, $sLibDir, $sPath)));
                        MLHelper::getFilesystemInstance()->cp($sPath, str_replace($sCacheI18nDir, $sLibDir, $sPath));
                    }
                }
            }
        }
    }


    public function getDeeplToken() {
        $token = MLSession::gi()->get('deepltoken');
        return empty(trim($token)) ? null : $token;
    }

    public function deeplIt($text) {
        if ($this->getDeeplToken() !== null) {
            $curl = curl_init();
            $aRequest = array('text' => $text, 'target_lang' => $this->getTargetLanguage(), 'source_lang' => $this->getBaseLanguage());
            //file_put_contents(__DIR__.'/deepl.log',  json_encode($aRequest)."\n",8);

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api-free.deepl.com/v2/translate',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $aRequest,
                CURLOPT_HTTPHEADER => array(
                    'Authorization: DeepL-Auth-Key ' . $this->getDeeplToken()
                ),
            ));

            $response = curl_exec($curl);
            curl_close($curl);
            //file_put_contents(__DIR__.'/deepl.log', $response."\n",8);
            $aResponse = json_decode($response, true);
            if (isset($aResponse, $aResponse['translations'][0]['text'])) {
                return $aResponse['translations'][0]['text'];
            } else {
                MLMessage::gi()->addError('Response is not in a correct format', array($response, $aResponse));
                MLMessage::gi()->addDebug('Response is not in a correct format', array($response, $aResponse));
            }
            return $response;
        } else {
            throw new Exception('DeepL token is not set');
        }
        return '';
    }

    public function getLimit() {
        return MLRequest::gi()->data('limit') !== null ? (int)MLRequest::gi()->data('limit') : 50;
    }

    protected function manageSelectQuery() {
        $this->oSelect = MLDatabase::factorySelectClass()->from(MLDatabase::factory('translation')->getTableName(), 't');
        $from = MLRequest::gi()->data('page') !== null ? ((int)MLRequest::gi()->data('page') - 1) * $this->getLimit() : 0;
        $limit = $this->getLimit();
        $this->oSelect->limit($from, $limit);
        $baseOption = $this->getBaseOption();
        $updatedOption = $this->getUpdatedOption();
        $targetOption = $this->getTargetOption();
        $keySearch = $this->getKeySearch();
        $fromLanguageSearch = $this->getFromLanguageSearch();
        $toLanguageSearch = $this->getToLanguageSearch();
        $filepathSearch = $this->getFilepathSearch();
        $translationStatus = $this->getTranslationStatus();
        $translationComment = $this->getTranslationComment();

        if (!empty($keySearch)) {
            if (substr($keySearch, 0, 4) === 'not:') {
                $keySearch = substr($keySearch, 4);
                $this->oSelect->where("t.`TranslationKey` NOT LIKE '%$keySearch%'");
            } else {
                $keySearch = $this->getSearchingPhrase($keySearch);
                $this->oSelect->where("t.`TranslationKey` LIKE '$keySearch'");
            }
        }

        if (!empty($fromLanguageSearch)) {
            $fromLanguageSearch = $this->getSearchingPhrase($fromLanguageSearch);
            $this->oSelect->where("t.`" . $this->getBaseLanguage() . "` LIKE '$fromLanguageSearch'");
        }

        if (!empty($toLanguageSearch)) {
            $toLanguageSearch = $this->getSearchingPhrase($toLanguageSearch);
            $this->oSelect->where("t.`" . $this->getTargetLanguage() . "` LIKE '$toLanguageSearch'");
        }


        if (!empty($translationStatus)) {
            if ($translationStatus === 'open') {
                $this->oSelect->where("tu.`Status` LIKE '$translationStatus' OR tu.`Status` IS NULL ");
            } else {
                $this->oSelect->where("tu.`Status` LIKE '$translationStatus'");
            }

        }
        if (!empty($translationComment)) {
            $translationComment = $this->getSearchingPhrase($translationComment);
            $this->oSelect->where("tu.`Comment` LIKE '$translationComment'");
        }

        if (!empty($filepathSearch)) {
            if (substr($filepathSearch, 0, 4) === 'not:') {
                $filepathSearch = substr($filepathSearch, 4);
                $this->oSelect->where("t.`FileRelativePath` NOT LIKE '%$filepathSearch%'");
            } else {
                $filepathSearch = $this->getSearchingPhrase($filepathSearch);
                $this->oSelect->where("t.`FileRelativePath` LIKE '$filepathSearch'");
            }
        }

        if ($baseOption === 'Empty') {
            $this->oSelect->where("t.`" . $this->getBaseLanguage() . "` IS NULL OR t.`" . $this->getBaseLanguage() . "` =''");
        } elseif ($baseOption === 'Blank') {
            $this->oSelect->where("t.`" . $this->getBaseLanguage() . "` =''");
        } elseif ($baseOption === 'Null') {
            $this->oSelect->where("t.`" . $this->getBaseLanguage() . "` IS NULL");
        } elseif ($baseOption === 'Filled') {
            $this->oSelect->where("t.`" . $this->getBaseLanguage() . "` IS NOT NULL");
        }

        if ($updatedOption === 'Empty') {
            $this->oSelect->where(" tu.`" . $this->getTargetLanguage() . "` IS NULL OR tu.`" . $this->getTargetLanguage() . "`  =''");
        } elseif ($updatedOption === 'Blank') {
            $this->oSelect->where("tu.`" . $this->getTargetLanguage() . "`  =''");
        } elseif ($updatedOption === 'Null') {
            $this->oSelect->where(" tu.`" . $this->getTargetLanguage() . "` IS NULL");
        } elseif ($updatedOption === 'Filled') {
            $this->oSelect->where("tu.`" . $this->getTargetLanguage() . "`  IS NOT NULL");
        }

        if ($targetOption === 'Empty') {
            $this->oSelect->where("t.`" . $this->getTargetLanguage() . "` IS NULL OR t.`" . $this->getTargetLanguage() . "` =''");
        } elseif ($targetOption === 'Blank') {
            $this->oSelect->where("t.`" . $this->getTargetLanguage() . "` =''");
        } elseif ($targetOption === 'Null') {
            $this->oSelect->where("t.`" . $this->getTargetLanguage() . "` IS NULL");
        } elseif ($targetOption === 'Filled') {
            $this->oSelect->where("t.`" . $this->getTargetLanguage() . "` IS NOT NULL");
        }
        $this->oSelect->join(array($this->getChangesTable(), 'tu', 't.`SHA3256Key` = tu.`SHA3256Key`'), ML_Database_Model_Query_Select::JOIN_TYPE_LEFT)
            ->select('t.*, tu.`' . $this->getTargetLanguage() . '` AS updated, tu.`Status`, tu.`Comment`, tu.`StatusHistory`');
    }

    protected function callAjaxCheckKeys() {
        $limit = 100;
        $offset = (int)MLRequest::gi()->data('offset');
        $this->oSelect = MLDatabase::factorySelectClass()->from(MLRequest::gi()->data('tablename'), 't')
            ->limit($offset, $limit);
        $total = (int)$this->oSelect->getCount();
        foreach ($this->oSelect->getResult() as $aRow) {
            $hash = hash('sha3-256', $aRow['TranslationKey'] . $aRow['FileRelativePath']);
            if ($aRow['SHA3256Key'] !== $hash) {
                MLMessage::gi()->addWarn('false' . __LINE__ . ':' . microtime(true), array($aRow['TranslationKey'], $hash, $aRow['SHA3256Key']));
                MLDatabase::getDbInstance()->update(MLRequest::gi()->data('tablename'),
                    [
                        'SHA3256Key' => $hash
                    ],
                    [
                        'TranslationKey' => $aRow['TranslationKey'],
                        'FileRelativePath' => $aRow['FileRelativePath']
                    ]
                );
                $result = (int)MLDatabase::getDbInstance()->affectedRows() > 0;
                if (!$result) {
                    $this->checkRecordsWithOldAndNewKey($aRow, $hash);

                }
            }
        }
        $blSuccess = $total <= $offset;
        if (!$blSuccess && MLRequest::gi()->data('saveSelection') !== 'true') {
            $offset = $offset + $limit;
        }
        MLSetting::gi()->add(
            'aAjax',
            array(
                'success' => $blSuccess,
                'error' => '',
                'offset' => $offset,
                'info' => array(
                    'total' => $total,
                    'current' => $offset,
                ),
            )
        );
    }

    /**
     * Import changes from magnalister_translation_update into magnalister_translation to make live later
     * @return void
     * @see /Codepool/00_Dev/I18n/View/widget/list/applychanges.php
     */
    protected function callAjaxApplyTranslationChanges() {
        $fileList = [];

        if (MLRequest::gi()->data('saveSelection') === 'true') {
            $offset = 0;
        } else {
            $offset = (int)MLRequest::gi()->data('offset');
        }
        $limit = $this->getLimit();
//        MLDatabase::getDbInstance()->setDoLogQueryTimes(false);
        $aData = $this->getUpdatedTranslations($offset, $limit);
        $language = MLRequest::gi()->data('apply_language');
        $aBlankTranslation = MLDatabase::getDbInstance()->fetchArray("
SELECT t.* FROM `magnalister_translation` t
 LEFT JOIN `" . $this->getChangesTable() . "` tu ON t.`SHA3256Key` = tu.`SHA3256Key`
WHERE (t.`DE` ='') AND (t.`$language` IS NULL)");
        if (is_array($aBlankTranslation)) {
            foreach ($aBlankTranslation as $aRow) {
                MLMessage::gi()->addDebug(__LINE__ . ':' . microtime(true), $aRow);
                MLDatabase::factory('translationupdate')
                    ->set('SHA3256Key', $aRow['SHA3256Key'])
                    ->set('TranslationKey', $aRow['TranslationKey'])
                    ->set('FileRelativePath', $aRow['FileRelativePath'])
                    ->set($language, '')
                    ->setTableName($this->getChangesTable())
                    ->save();
            }
        }

        $aListOfFiles = array_unique(array_column($aData, 'FileRelativePath'));
        foreach ($aListOfFiles as $sFile) {
            $filePath = $this->getFullPath($language, $sFile);
            $wholeContent = "<?php\n\n";
            foreach (MLDatabase::factory('translation')->set('FileRelativePath', $sFile)
                         ->getList()
                         ->getQueryObject()
                         ->getResult() as $aRow) {
                $wholeContent .= 'MLI18n::gi()->{' . var_export($aRow['TranslationKey'], true) . '} = ' . var_export($aRow[$language], true) . ";\n";

            }
            file_put_contents($filePath, $wholeContent);
        }
        foreach ($aData as $aRow) {
            $errors = [];
            //MLMessage::gi()->addDebug($aRow['TranslationKey'].':'.microtime(true));
            if (isset($aRow[$language])) {
                $filePath = $this->getFullPath($language, $aRow['FileRelativePath']);//De, Fr
                foreach (
                    [
                        $language,//DE, FR
                        strtolower($language)// de, fr
                    ] as $languageAlternative) {
                    if (!file_exists($filePath)) {
                        $filePathAlternative = str_replace('#lang#', $languageAlternative, $aRow['FileRelativePath']);
                        if (file_exists($filePathAlternative)) {
                            $filePath = $filePathAlternative;
                        }
                    }
                }

                if (!file_exists($filePath)) {
                    $s = "<?php\n\n";
                } else {
                    $s = '';
                }
                $fileList[$filePath] = $filePath;
                $s .= $this->replaceExistingKeyAndGetNotExisting($filePath, $aRow, $language);
                $dir = dirname($filePath);
                if (!is_dir($dir)) {
                    MLHelper::getFilesystemInstance()->write($dir);
                }
                if (is_dir($dir)) {
                    try {
                        file_put_contents($filePath, $s, 8);
                        //MLMessage::gi()->addDebug($filePath.':'.microtime(true), array());
                    } catch (Throwable $e) {
                        MLMessage::gi()->addError($e);
                    }
                } else {
                    MLMessage::gi()->addError('There was a problem by creating a directory', $dir);
                }
            }
            MLMessage::gi()->addDebug($aRow['TranslationKey']);
            if (!empty($errors)) {
                MLMessage::gi()->addError($errors, [$aRow['TranslationKey']]);
            }
        }
        $cacheFileName = 'List_Of_Changes_I18n_Files.json';
        if ($offset > 0) {
            $existingFiles = MLCache::gi()->get($cacheFileName);
            $fileList = array_merge($fileList, $existingFiles);
            $fileList = array_unique($fileList);
        }
        $total = (int)$this->oSelect->getCount();
        MLMessage::gi()->addDebug('Total: ' . $total);
        MLCache::gi()->set($cacheFileName, $fileList);
        $blSuccess = $total <= $offset + $limit;
        if ($blSuccess || MLRequest::gi()->data('saveSelection') === 'true') {
            $this->createTranslatinZipFile($fileList);
        }
        MLSetting::gi()->add(
            'aAjax',
            array(
                'success' => $blSuccess,
                'error' => '',
                'offset' => $offset + $limit,
                'info' => array(
                    'total' => $total,
                    'current' => $offset,
                ),
            )
        );
    }


    protected function getUpdatedTranslations($offset, $limit) {

        $sSelect = 't.SHA3256Key, t.TranslationKey, t.FileRelativePath';
        foreach ($this->getListOfLang() as $language) {
            $sSelect .= ',tu.' . $language;
        }
        $this->oSelect = MLDatabase::factorySelectClass()->from(MLDatabase::factory('translation')->getTableName(), 't');
//        $this->oSelect->where("t.`DE` IS NOT NULL AND t.`DE` !=''");
//        $this->oSelect->where("t.`FileRelativePath` LIKE '%90_System%'");
        $this->oSelect->join(array($this->getChangesTable(), 'tu', 't.`SHA3256Key` = tu.`SHA3256Key`'), ML_Database_Model_Query_Select::JOIN_TYPE_INNER)
            ->select($sSelect)
            ->limit($offset, $limit);

        return $this->oSelect->getResult();
    }

    private function replaceExistingKeyAndGetNotExisting($filePath, $aRow, $language) {
        $translationLine = 'MLI18n::gi()->{' . var_export($aRow['TranslationKey'], true) . '} = ' . var_export($aRow[$language], true) . ";";
        // Read the content of the PHP file
        $content = file_get_contents($filePath);

        // Escape special characters in the beginning of the command
        $escapedBeginning = preg_quote('MLI18n::gi()->{' . var_export($aRow['TranslationKey'], true) . '}', '/');
        $regex = '/^' . $escapedBeginning . '.*?\';/ms';
        MLMessage::gi()->addDebug(__LINE__ . ':' . microtime(true), array($regex));
        // Identify and remove the lines starting with the specified beginning and ending with a semicolon
        $content = preg_replace($regex, $translationLine, $content, -1, $count);
        // Write the modified content back to the file
        if ($count > 0) {
            file_put_contents($filePath, $content);
            return '';
        } else {
            return "\n" . $translationLine;
        }
    }

    protected $nameOfI18nZipFile = 'translations_changes.zip';

    public function getNameOfI18ZipFile() {
        return $this->nameOfI18nZipFile;
    }

    protected function createTranslatinZipFile($fileList) {
        $oZip = new ZipArchive();
        $sZipFile = $this->getNameOfI18ZipFile();
        $aCacheFile = MLFilesystem::getCachePath($sZipFile);
        if ($oZip->open($aCacheFile, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE) !== TRUE) {
            echo("<div>An error occurred creating your ZIP file.</div>");
        }
        foreach ($fileList as $filePath) {
            $zipPath = '/Codepool/' . explode('/Codepool/', $filePath)[1];
            $oZip->addFromString($zipPath, file_get_contents($filePath));

        }
        $oZip->close();
        return MLHttp::gi()->getCacheUrl($sZipFile);
    }

    protected function getListOfSearchOption() {
        return [
            'All' => 'All',
            'Filled' => 'Filled',
            'Null' => 'Never assigned',
            'Blank' => 'Blank',
            'Empty' => 'Never assigned or Blank ',
        ];
    }

    protected function getDownloadLink() {
        $sBasePath = MLSetting::gi()->get('sUpdateUrl') . 'magnalister/';
        $sDstPath = MLFilesystem::getCachePath() . 'I18n/';
        $oFilesystem = MLHelper::getFilesystemInstance();
        $oRemote = MLHelper::gi('remote');
        /* @var $oRemote ML_Core_Helper_Remote */
        if (file_exists($sDstPath)) {
            $oFilesystem->rm($sDstPath);
        }
        $aPluginFiles = $oRemote->getFileList($sBasePath);
        foreach ($aPluginFiles['__plugin'] as $aFile) {
            if (preg_match('/Codepool(.+)I18n(.+)\.php$/Uis', $aFile['dst'])) {
                $oFilesystem->write($sDstPath . $aFile['dst'], $oRemote->fileGetContents($sBasePath . $aFile['dst']));
            }
        }
    }

    protected function getSearchingPhrase($searchingString) {
        $searchString = trim($searchingString);
        if ($searchString[0] === '"' && $searchString[strlen($searchString) - 1] === '"') {

            $searchingString = substr($searchString, 1, -1);
        } else {
            $searchingString = "%$searchingString%";
        }
        return $searchingString;
    }

    protected function checkRecordsWithOldAndNewKey(array $aRow, string $hash): void {
        [$first, $second] = MLDatabase::getDbInstance()->fetchArray('SELECT * FROM `' . MLRequest::gi()->data('tablename') . '` WHERE `TranslationKey` =\'' . $aRow['TranslationKey'] . '\' AND `FileRelativePath` = \'' . $aRow['FileRelativePath'] . '\'');
        if (!empty($first) && !empty($second)) {
            $main = null;
            $copy = null;
            if ($first['SHA3256Key'] === $hash) {
                $main = $first;
                $copy = $second;
            } elseif ($second['SHA3256Key'] === $hash) {
                $main = $second;
                $copy = $first;
            }
            if ($main !== null) {
                $isDifferent = false;
                $aUpdate = [];
                foreach ($this->getListOfLang() as $column) {
                    if (
                        $main[$column] !== $copy[$column] &&
                        !in_array($copy[$column], ['', null], true)
                    ) {
                        if ($main[$column] === null) {
                            $aUpdate[$column] = $copy[$column];
                        }
                        $isDifferent = true;
                        break;
                    }
                }
                if ($isDifferent) {
                    if (!empty($aUpdate)) {
                        MLDatabase::getDbInstance()->update(MLRequest::gi()->data('tablename'),
                            $aUpdate,
                            [
                                'SHA3256Key' => $hash
                            ]
                        );
                    }
                    MLMessage::gi()->addWarn('first and second are different. Hash:' . $hash, [$first, $second]);
                } else {
                    MLDatabase::getDbInstance()->delete(MLRequest::gi()->data('tablename'),
                        [
                            'SHA3256Key' => $copy['SHA3256Key']
                        ]
                    );
                    MLMessage::gi()->addWarn('delete - ' . $hash, [$first, $second]);
                }
            } else {
                MLMessage::gi()->addDebug('main cannot be found. Hash:' . $hash, [$first, $second]);
            }

            MLMessage::gi()->addDebug(__LINE__ . ':' . microtime(true), array($hash, [$first, $second]));


        } else {

            MLMessage::gi()->addDebug('first or second are empty. Hash:' . $hash, [$first, $second]);
        }
    }

    /**
     * @param string $language
     * @param string $fileRelativePath
     * @return string
     */
    protected function getFullPath($language, $fileRelativePath) {
        return MLFilesystem::getLibPath() . str_replace('#lang#', ucfirst(strtolower($language)), $fileRelativePath);
    }
}
