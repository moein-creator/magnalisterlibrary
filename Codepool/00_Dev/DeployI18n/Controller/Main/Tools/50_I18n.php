<?php

MLFilesystem::gi()->loadClass('Core_Controller_Abstract');

class ML_DeployI18n_Controller_Main_Tools_I18n extends ML_Core_Controller_Abstract {
    protected $aParameters=array('controller');
    
    protected function createLangFiles(){
        $sDs=DIRECTORY_SEPARATOR;
        $oI18n=  MLI18n::gi()->setReplaceMode(false);
        $oRef=new ReflectionClass($oI18n);
        $oProp=$oRef->getProperty('aData');
        $oProp->setAccessible(true);
        $oZip=new ZipArchive();
        $sZipFile=  strtoupper(__class__).'__'.strtolower($this->getRequest('lang')).'.zip';
        $oZip->open(MLFilesystem::getCachePath($sZipFile), ZipArchive::OVERWRITE);
        $sLang=ucfirst($this->getRequest('lang'));
        foreach(MLFilesystem::gi()->glob(MLFilesystem::getLibPath().$sDs.'Codepool'.$sDs.'*'.$sDs.'*'.$sDs.'[iI]18[nN]'.$sDs.'[dD][eE]', GLOB_BRACE) as $sModulPath){
            $oProp->setValue($oI18n,array());
            foreach(MLFilesystem::gi()->glob($sModulPath.DIRECTORY_SEPARATOR.'*.php', GLOB_BRACE) as $sPath){
                include($sPath);
            }
            $sPath=  substr($sModulPath,0,-2);
            if(count($oI18n->data())!=0){
                $sCsvFileName=substr($sPath,strlen(MLFilesystem::getLibPath().$sDs)).DIRECTORY_SEPARATOR.$sLang.DIRECTORY_SEPARATOR.'lang.csv';
                $rCsvFile=fopen('php://memory','r+');
                fputcsv($rCsvFile, array('[Name]','['.$sLang.']','[De]'));
                $aFlatData=  MLHelper::getArrayInstance()->nested2Flat($oI18n->data());
                $aExistingData=array();
                if(file_exists(MLFilesystem::getLibPath().$sCsvFileName)){//merge with already translated data
                    $rExisting=fopen(MLFilesystem::getLibPath().$sCsvFileName,'r');
                    while($aRow=  fgetcsv($rExisting)){
                        if(isset($aFlatData[$aRow[0]])){
                            $aExistingData[$aRow[0]]=$aRow[1];
                        }
                    }
                }
                foreach($aFlatData as $sKey=>$sValue){
                    fputcsv($rCsvFile, array($sKey,isset($aExistingData[$sKey])?$aExistingData[$sKey]:$sKey, $sValue));
                }
                rewind($rCsvFile);
                $sCsv='';
                while($sLine=fgets($rCsvFile)){
                    $sCsv.=$sLine;
                }
                $oZip->addFromString(dirname(dirname($sCsvFileName)).DIRECTORY_SEPARATOR.$sLang.DIRECTORY_SEPARATOR.pathinfo($sCsvFileName, PATHINFO_FILENAME).'.csv', $sCsv);
                fclose($rCsvFile);
            }
        }
        $oZip->close();
        $oProp->setAccessible(false);
        $oI18n->init();
        return MLHttp::gi()->getCacheUrl($sZipFile);
    }
    
    protected function downloadPackage(){
        $sBasePath = MLSetting::gi()->get('sUpdateUrl') . 'magnalister/';
        $sDstPath = MLFilesystem::getCachePath().'I18n/';
        $oFilesystem = MLHelper::getFilesystemInstance();
        $oRemote = MLHelper::gi('remote');
        /* @var $oRemote ML_Core_Helper_Remote  */
        if(file_exists($sDstPath)){
            $oFilesystem->rm($sDstPath);
        }
        $aPluginFiles = $oRemote->getFileList($sBasePath);
        foreach ($aPluginFiles['__plugin'] as $aFile) {
            if(preg_match('/Codepool(.+)I18n(.+)\.php$/Uis',$aFile['dst'])){
                $oFilesystem->write($sDstPath.$aFile['dst'],  $oRemote->fileGetContents($sBasePath.$aFile['dst']));
            }
        }
    }
}