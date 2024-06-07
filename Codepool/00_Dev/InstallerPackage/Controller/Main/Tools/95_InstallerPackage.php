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
 * (c) 2010 - 2020 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */
MLFilesystem::gi()->loadClass('Form_Controller_Widget_Form_Abstract');

class ML_InstallerPackage_Controller_Main_Tools_InstallerPackage extends ML_Core_Controller_Abstract {

    protected $aParameters = array('controller');
    protected $sShopSystem = null;
    protected $blIndexNeeded = false;

    protected function getShopSystem() {
        if ($this->sShopSystem === null) {
            $sShopsystem = $this->getRequest('shopsystem');
            if (in_array($sShopsystem, array('Prestashop', 'Shopware', 'Shopify', 'WooCommerce'))) {
                $this->sShopSystem = $sShopsystem;
            }
            $this->blIndexNeeded = $this->sShopSystem == 'Prestashop';
        }
        return $this->sShopSystem;
    }

    public function __construct() {
        parent::__construct();
        if ($this->getShopSystem() !== null) {
            $this->createInstaller();
        }
    }

    protected function addSpecificFiles($sWholePackagePath) {
        $this->addPartnerFiles($sWholePackagePath);
        if ($this->getShopSystem() === "Prestashop") {
            $sVersion = $this->getRequest('version');
            $this->mkdirIfNotExist($sWholePackagePath . '/upgrade/');
            file_put_contents($sWholePackagePath . '/upgrade/upgrade_module-' . $sVersion . '.php', '<?php
/**
 *
 * @author magnalister
 * @copyright 2010-'.date('Y').' RedGecko GmbH -- http://www.redgecko.de
 * @license Released under the MIT License (Expat)
 */
 
if (!defined(\'_PS_VERSION_\')) {
    exit;
}

function upgrade_module_' . str_replace('.', '_', $sVersion) . '($object)
{
    $s_ds = DIRECTORY_SEPARATOR;
    $s_cache_path = dirname(__FILE__).$s_ds.\'..\'.$s_ds.\'writable\'.$s_ds.\'cache\';
    if (is_dir($s_cache_path)) {
        $a_files = glob($s_cache_path.\'/*\');
        foreach ($a_files as $s_file) {
            if (file_exists($s_file) && !is_dir($s_file)) {
                unlink($s_file);
            }
        }
    }
    try {
        Db::getInstance()->delete(\'magnalister_config\', \'mpID = 0 AND mkey = "after-update"\', 0, true, false);
    } catch (\Exception $ex) {
    }
    return true;
}
');
//            $sConfigFile = file_get_contents($sWholePackagePath.'/config.xml');
//            $sConfigFile = preg_replace('/<version>(.*)<\/version>/', '<version><![CDATA['.$sVersion.']]></version>',$sConfigFile);
//            file_put_contents($sWholePackagePath.'/config.xml', $sConfigFile);
//
//            $sMagnalisterFile = file_get_contents($sWholePackagePath.'/magnalister.php');
//            $sMagnalisterFile = preg_replace('/\$this->version =(.*);/', '$this->version = \''.$sVersion.'\';',$sMagnalisterFile);
//            file_put_contents($sWholePackagePath.'/magnalister.php', $sMagnalisterFile);
//
            $this->mkdirIfNotExist( $sWholePackagePath.'/writable/' );
            $this->addHtaccess($sWholePackagePath.'/writable/');
            $this->addIndex($sWholePackagePath);
        }

//        if ($this->sShopSystem === 'Shopware') {
//            $sVersion = $this->getRequest('version');
//            $sMagnalisterFile = file_get_contents($sWholePackagePath.'/Bootstrap.php');
//            $sMagnalisterFile = preg_replace('/\$magnalisterVersion = (.*);/', '$magnalisterVersion = \''.$sVersion.'\';',$sMagnalisterFile);
//            file_put_contents($sWholePackagePath.'/Bootstrap.php', $sMagnalisterFile);
//        }
    }

    protected function createInstaller() {
        $sWholePackagePath = str_replace('\\', '/', $this->getRequest('wholepackagepath'));
        if (substr($sWholePackagePath, -1) == '/') {
            $sWholePackagePath = substr($sWholePackagePath, 0, -1);
        }
        if ($this->resourceValidation($sWholePackagePath)) {
            $sDestination = MLFilesystem::getCachePath('../'.$this->getShopSystem().'/');
            $this->mkdirIfNotExist($sDestination);
            $oZip = new ZipArchive();
            $sZipPath = $sDestination . 'magnalister.zip';
            if ($oZip->open($sZipPath, ZipArchive::CREATE) !== true) {
                throw new Exception('there is a problem to open zip file');
            }
            
            $this->addSpecificFiles($sWholePackagePath);

            $sParentDir = '';
            foreach ($this->getParentDirectories() as $sParentDir) {
                $this->addEmptyDir($oZip, $sParentDir);
            }
            foreach ($this->getPluginFiles() as $sPackageRequirement) {
                $this->addToZip($oZip, $sWholePackagePath, $sParentDir.'/', $sPackageRequirement);
            }
            $oZip->close();
        } else {
            MLMessage::gi()->addWarn('selected directory seems wrong');
        }
    }

    /**
     * 
     * @param string $sPath
     * @return array
     */
    protected function recursiveGetFiles($sPath) {
        $files = array();
        if (is_dir($sPath)) {
            $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($sPath)
                    );
            foreach ($rii as $file) {
                $files[] = $file->getPathname();
            }
        } else {
            $files[] = $sPath;
        }

        return $files;
    }

    protected function addToZip(&$oZip, $sResourcePath, $sDestinationPath, $sPatern) {
        foreach ($this->recursiveGetFiles($sResourcePath . $sPatern) as $sPath) {
            $sPath = str_replace('\\', '/', $sPath);

            // Ignore "." and ".." folders
            if (in_array(substr($sPath, strrpos($sPath, '/') + 1), array('.', '..'))) {
                continue;
            }

            $sPath = realpath($sPath);

            if (is_dir($sPath) === true) {
                $oZip->addEmptyDir(str_replace($sResourcePath . '/', $sDestinationPath, $sPath . '/'));
            } else if (is_file($sPath) === true) {
                $oZip->addFromString(str_replace($sResourcePath . '/', $sDestinationPath, $sPath), $this->prepareFileContents($sPath));
            }
        }
    }

    /**
     * If shop-system is 'WooCommerce' this method get all .php files contents
     * and add check for existence of ABSPATH constant. This is mandatory for
     * all .php files in Wordpress plugin.
     * For other shopsystems contents of files stay untouched.
     *
     * @param $sPath
     * @return bool|string
     */
    protected function prepareFileContents($sPath) {
        if ($this->getShopSystem() === 'WooCommerce' && substr($sPath, -4) === '.php') {
            $sFileContent = file_get_contents($sPath);
            $aSplited = explode('<?php', $sFileContent, 2);
            if (is_array($aSplited) && isset($aSplited[1])) {
                return "<?php if ( ! defined( 'ABSPATH' ) ) exit; " .$aSplited[1];
            }
        }

        return file_get_contents($sPath);
    }

    protected function addIndex($sPath){
        foreach ($this->recursiveGetFiles($sPath) as $sPath) {
            $sPath = str_replace('\\', '/', $sPath);
            if (substr($sPath,-2) == '/.') {
                $sPath = substr($sPath, 0, -2);
                file_put_contents($sPath.'/index.php', '<?php 
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");

header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

header("Location: ../");
exit;
');
            }
        }
        
    }

    protected function addHtaccess($sPath){
        $sPath = str_replace('\\', '/', $sPath);
        file_put_contents($sPath.'.htaccess', 'Order deny,allow
Deny from all                
');
               
    }

    protected function addPartnerFiles($sPath){
        $sShopsystem = $this->getShopSystem();
        $sShopsystem = strtolower($sShopsystem);
        if($sShopsystem == 'prestashop' || $sShopsystem == 'woocommerce'){
            $sLib = '/lib';
        } else {
            $sLib = '/Lib';
        }
        $this->mkdirIfNotExist( $sPath.$sLib.'/Codepool/' );
        $this->mkdirIfNotExist( $sPath.$sLib.'/Codepool/10_Customer/' );
        $this->mkdirIfNotExist( $sPath.$sLib.'/Codepool/10_Customer/Partner/' );
        $this->mkdirIfNotExist($sPath.$sLib.'/Codepool/10_Customer/Partner/Setting/' );
        
        if($sShopsystem == 'prestashop'){
            file_put_contents($sPath.$sLib.'/Codepool/10_Customer/Partner/Setting/'.$sShopsystem.'.php', '<?php 
MLSetting::gi()->set(\'magnaPartner\', \''.$sShopsystem.'\');
MLSetting::gi()->set(\'blHideUpdate\',  true);
');
        } else {
            file_put_contents($sPath.$sLib.'/Codepool/10_Customer/Partner/Setting/'.$sShopsystem.'.php', '<?php 
MLSetting::gi()->set(\'magnaPartner\', \''.$sShopsystem.'\');
MLSetting::gi()->set(\'blHideUpdate\',  true);
');
        }
    }
    
    protected function addEmptyDir(ZipArchive $oZip,$sPath){
        $oZip->addEmptyDir($sPath);
    }
    
    protected function getPluginFiles() {
        $sShopsystem = $this->getShopSystem();
        switch ($sShopsystem) {
            case 'Prestashop':
                return array(
                    '/index.php',
//                    '/config.xml',
                    '/logo.png',
                    '/magnalister.php',
                    '/Readme.md',
                    '/controllers/',
                    '/translations/',
                    '/upgrade/',
                    '/views/',
                    '/writable/',
                    '/lib/index.php',
                    '/lib/Alias/',
                    '/lib/Codepool/index.php',
                    '/lib/Codepool/10_Customer/',
                    '/lib/Codepool/80_Modules/',
                    '/lib/Codepool/70_Shop/index.php',
                    '/lib/Codepool/70_Shop/Prestashop/',
                    '/lib/Codepool/90_System/',
                    '/lib/OldLib/',
                    '/lib/Core/',
                    '/lib/ChangeLog',
                    '/lib/ClientVersion',
                    '/lib/external.list',
                    '/lib/Codepool/65_ShopModule/index.php',
                    '/lib/Codepool/65_ShopModule/PrestashopAmazon/',
                    '/lib/Codepool/65_ShopModule/PrestashopCdiscount/',
                    '/lib/Codepool/65_ShopModule/PrestashopCheck24/',
                    '/lib/Codepool/65_ShopModule/PrestashopEbay/',
                    '/lib/Codepool/65_ShopModule/PrestashopEtsy/',
                    '/lib/Codepool/65_ShopModule/PrestashopHitmeister/',
                    '/lib/Codepool/65_ShopModule/PrestashopHood/',
                    '/lib/Codepool/65_ShopModule/PrestashopIdealo/',
                    '/lib/Codepool/65_ShopModule/PrestashopMercadoLivre/',
                    '/lib/Codepool/65_ShopModule/PrestashopPriceMinister/',
                    '/lib/Codepool/65_ShopModule/PrestashopRicardo/',
                    '/lib/Codepool/65_ShopModule/PrestashopMetro/',
                );
            case 'Shopware':
                return array(
                    '/Bootstrap.php',
                    '/BootstrapLegacy.php',
                    '/plugin.png',
                    '/plugin.xml',
                    '/Controllers/',
                    '/Views/',
                    '/Lib/Alias/',
                    '/Lib/Codepool/10_Customer/',
                    '/Lib/Codepool/70_Shop/Shopware/',
                    '/Lib/Codepool/80_Modules/',
                    '/Lib/Codepool/70_Shop/index.php',
                    '/Lib/Codepool/70_Shop/Shopware/',
                    '/Lib/Codepool/90_System/',
                    '/Lib/OldLib/',
                    '/Lib/Core/',
                    '/Lib/ChangeLog',
                    '/Lib/ClientVersion',
                    '/Lib/external.list',
                    '/Lib/Codepool/65_ShopModule/',
                );
            case 'Shopify':
                return array(
                    '/Alias/',
                    '/Codepool/70_Shop/Shopify/isActive.php',
                    '/Codepool/70_Shop/Shopify/isShop.php',
                    '/Codepool/70_Shop/Shopify/Helper/Model/Http.php',
                    '/Codepool/70_Shop/Shopify/Helper/Model/Shop.php',
                    '/Codepool/70_Shop/Shopify/Helper/Container.php',
                    '/Codepool/70_Shop/Shopify/Helper/Database.php',
                    '/Codepool/70_Shop/Shopify/Model/Http.php',
                    '/Codepool/70_Shop/Shopify/Model/Shop.php',
                    '/Codepool/70_Shop/Shopify/Model/Language.php',
                    '/Codepool/90_System/Core/',
                    '/Codepool/90_System/Shop/',
                    '/Core/',
                );
            case 'WooCommerce':
                return array(
                    '/magnalister.php',
                    '/plugin.png',
                    '/readme.txt',
                    '/lib/Alias/',
                    '/lib/Codepool/10_Customer/',
                    '/lib/Codepool/80_Modules/',
                    '/lib/Codepool/70_Shop/WooCommerce/',
                    '/lib/Codepool/90_System/',
                    '/lib/OldLib/',
                    '/lib/Core/',
                    '/lib/ChangeLog',
                    '/lib/ClientVersion',
                    '/lib/external.list',
                    '/lib/CodePool/55_ShopModule_WooCommerce',
                    '/migrations/',
                    '/writable/',
                );
            default:
                return null;
        }
    }

    protected function getParentDirectories() {
        $sShopsystem = $this->getShopSystem();
        switch ($sShopsystem) {
            case 'Prestashop':
            case 'WooCommerce':
                return array('magnalister');
            case 'Shopware':
                return array(
                    'Backend',
                    'Backend/RedMagnalister'
                );
            default:
                return array();
        }
    }
    
    
    protected function resourceValidation($sResource) {
        $sShopsystem = $this->getShopSystem();
        switch ($sShopsystem) {
            case 'WooCommerce':
                return $sResource != '' && file_exists($sResource . '/magnalister.php') &&  file_exists($sResource . '/readme.txt');
            case 'Prestashop':
                return $sResource != '' && file_exists($sResource . '/magnalister.php') &&  file_exists($sResource . '/Readme.md');
            case 'Shopware':
                return $sResource != '' && file_exists($sResource . '/Bootstrap.php');
            case 'Shopify':
                return $sResource != '' && file_exists($sResource . '/ChangeLog');
            default:
                return null;
        }
    }
    
    protected function mkdirIfNotExist($sPath){
        if(!file_exists($sPath)){
            mkdir( $sPath);
        }
    }

    private function addDirectAccessProtectionWooCommerce($sWholePackagePath)
    {
        $files = $this->recursiveGetFiles($sWholePackagePath);

        foreach($files as $file) {
            if (substr($file, -4) === '.php') {
                $fileContent = file_get_contents($file);
                if (strpos($fileContent, 'defined( \'ABSPATH\'') === false || strpos($fileContent, 'defined(\'ABSPATH') === false) {
                    $modifedFileContent = preg_replace('/(\<(.*?)php)/', '<?php if ( ! defined( \'ABSPATH\' ) ) exit;', $fileContent, 1);
                    $d = umask(0);
                    file_put_contents($file, $modifedFileContent);
                    umask($d);
                }
            }
        }
    }
}
