<?php 
MLSetting::gi()->blDebug = true;
MLSetting::gi()->blDev = true;
MLSetting::gi()->blShowInfos = true;
MLSetting::gi()->blShowWarnings = true;
MLSetting::gi()->blShowFatal = true;
MLSetting::gi()->sDebugHost = 'http://localhost:8888/sw529/';
MLSetting::gi()->blIterativeRequest = false;
//MLSetting::gi()->iOrderImportOrderCount = 4;
//MLSetting::gi()->sApiUrl = 'https://api.magnalister.com/API/';
//MLSetting::gi()->sApiUrl = 'https://timne.developers.herakles.magnalister.com/API/';
//MLRequest::gi()->mp = 'dummymodule';
//MLSetting::gi()->sApiRelatedUrl = 'http://dev.magnalister.com/APIRelated/';
//MLSetting::gi()->sUpdateUrl = 'http://api.magnalister.com/update/v3/;
//MLSetting::gi()->sDebugHost = 'https://devshops.magnalister.com/shops/magento/1.9';
/**
 * @var array aClassTreePatterns
 * regex pattern if class name match will displayed in class-tree tab in dev-bar
 */
MLSetting::gi()->aClassTreePatterns = array('/normalize/Uis');

if (file_exists(__DIR__ . '/localdev.php')) {
	require __DIR__ . '/localdev.php';
}
