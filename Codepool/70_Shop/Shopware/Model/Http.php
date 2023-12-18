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

/**
 * Shopware specific implementation of the Http Model.
 */
class ML_Shopware_Model_Http extends ML_Shop_Model_Http_Abstract {

    /** @var Shopware\Models\Shop\Shop   */
    protected $oDefaultShop = null;
    /**
     * Returns the path of the magnalister Lib/ path beginning from the engine/ directory.
     *
     * @return string
     */
    protected function getMlLibPath() {
        static $libPath = '';
        if (empty($libPath)) {
            $path = str_replace(DIRECTORY_SEPARATOR, '/', dirname(__FILE__));
            $m = array();
             if (!preg_match('(\/engine\/.*\/[^\/]*Magnalister\/Lib\/)', $path, $m)) {
                 if (strpos($path, '/Plugins/Community/Backend/RedMagnalister/Lib/') !== false) {// if using Shopware git installation
                     return '/Plugins/Community/Backend/RedMagnalister/Lib/';
                 } else {// if we are using symbolic link for magnalister in another directory
                     return '/engine/Shopware/Plugins/Community/Backend/RedMagnalister/Lib/';
                 }
             }
            $libPath = $m[0];
        }
        return $libPath;
    }

    /**
     * Gets the url to a file in the resources folder.
     * @param string $sFile
     *    Filename
     * @param bool $blAbsolute
     *
     * @return string
     */
    public function getResourceUrl($sFile = '', $blAbsolute = true) {
        $sPath = ($blAbsolute ? $this->getBackendBaseUrl() : '../../') . $this->getMlLibPath();
        $sExt = pathinfo($sFile, PATHINFO_EXTENSION);
        $aExt = explode('?', $sExt);//separate extention of file from url query that most of the time is current version of magnalister
        try{
            $aResource = empty($sFile) ? array('path' => '') : MLFilesystem::gi()->findResource('resource_' . $sFile);  
        }  catch (Exception $oExc){//if file was not found , try to find resource by its type
            try{
                $aResource = MLFilesystem::gi()->findResource('resource_'.$aExt[0].'_' . $sFile); 
            }  catch (Exception $oExc){
                return '';//no file is found
            }
        }
        
        $sLibPath = MLFilesystem::getLibPath();
        $aResourcePath = explode($sLibPath, $aResource['path']);
        $sUrl = $sPath . $aResourcePath[1];
        if(count($aExt)>1){//add url query part if exist 
            $sUrl .= '?'.$aExt[1];
        }
        $sUrl = str_replace('\\', '/', $sUrl);//replace backslashes in windows 
        return $sUrl;
    }
    /**
     * just used for cUrl referer for api request .
     * @return string
     */
    public function getBaseUrl() { 
        try {
            return MLSetting::gi()->get('sDebugHost');
        } catch (Exception $ex) {
            return $this->getShopwareDefaultShopURL();
        }
    }

    /**
     * return url that currently used to log in to shopware backend
     * @return string
     */
    public function getBackendBaseUrl(){
        $reffer = Shopware()->Front()->Request()->isSecure() ? "https://" : "http://";
        $sBaseUrl = $reffer . Shopware()->Front()->Request()->getHttpHost() . Shopware()->Front()->Request()->getBaseUrl();
        return $sBaseUrl;
    }
    
    /**
     * Gets the backend url of the magnalister app.
     * @param array $aParams
     *    name => value
     * @return string
     */
    public function getUrl($aParams = array()) {
        $sParent = parent::getUrl($aParams);
        // Append no question mark if there are no parameters. Otherwise we will be redirected to the frontend controller, which doesn't exist.
        if (!empty($sParent)) {
            $sParent = '?'.$sParent;
        }
        return $this->getBackendBaseUrl() . '/backend/Magnalister/app' . $sParent;
    }

    /**
     * Gets the request params merged from _POST and _GET.
     * @return array
     */
    public function getRequest() {
        $aOut = MLHelper::getArrayInstance()->mergeDistinct(Shopware()->Front()->Request()->getQuery(), Shopware()->Front()->Request()->getPost());
        return $this->filterRequest($aOut);
    }

    /**
     * Returns _SERVER.
     * @return array
     */
    public function getServerRequest() {
        return $_SERVER;
    }

    /**
     * Parse hidden fields that are wanted by different shop systems for security measurements.
     * @return array
     *    Assoc of hidden neccessary form fields array(name => value, ...)
     */
    public function getNeededFormFields() {
        return array();
    }

    /**
     * Gets the magnalister cache FS url.
     * @return string
     */
    public function getCacheUrl($sFile = '') {
        return $this->getBackendBaseUrl() . dirname($this->getMlLibPath()) . '/writable/cache/' . $sFile;
    }

    /**
     * Gets the frontend url of the magnalister app.
     * @param array $aParams
     * @return string
     */
    public function getFrontendDoUrl($aParams = array()) {
        $sConfig = $this->getConfigFrontCornURL($aParams);
        if ($sConfig !== '') {
            return $sConfig;
        }
        $sParent = parent::getUrl($aParams);
        $oShop = $this->getDefaultShop();
        $aUrl = array();
        $aUrl[] = ($oShop->getSecure() ? 'https' : 'http').'://'; //http protocol
        $sHost = trim((method_exists($oShop, 'getSecureHost') && $oShop->getSecure() ? $oShop->getSecureHost() : $oShop->getHost()));
        $aUrl[] = empty($sHost) ? Shopware()->Front()->Request()->getHttpHost() : $sHost;//domain or host name
        $sBasePath = trim($oShop->getBaseUrl());
        $aUrl[] = empty($sBasePath) ? Shopware()->Front()->Request()->getBaseUrl() : $sBasePath; // path to shop
        $aUrl[] = '/Magnalister/index?';// path to magnalister front controller
        $aUrl[] = $sParent; // parameter
        return implode('', $aUrl);        
    }     

    /**
     * return directory or path (file system) of specific shop images
     * @return string
     */
    public function getShopImagePath() {
        return 'media/image/';
    }
    
    /**
     * return url of specific shop images
     * @param string $sFiles
     */
    public function getShopImageUrl(){
        return rtrim($this->getShopwareDefaultShopURL() ,"/").'/media/image/';
    }

    /**
     * return default shop in shopware
     * @return Shopware\Models\Shop\Shop
     */
  public function getDefaultShop() {
        
        
        if ($this->oDefaultShop === null) {
            try {$oBbuilder = Shopware()->Models()->createQueryBuilder();
                $this->oDefaultShop = Shopware()->Models()->getRepository('Shopware\Models\Shop\Shop')->getActiveDefault();  
            } catch (Exception $exc) {
                try {
                    $oBbuilder = Shopware()->Models()->createQueryBuilder();
                    $oQuery = $oBbuilder->select(array('shop'))
                ->from('Shopware\Models\Shop\Shop', 'shop');
                    $aShops = $oQuery
                                    ->getQuery()->getArrayResult();
                    foreach ($aShops as $aShop) {
                        if($aShop['host'] != null){
                            $this->oDefaultShop = Shopware()->Models()->getRepository('Shopware\Models\Shop\Shop')->find($aShop['id']);
                        }
                    }
                } catch (Exception $exc) {
                    
                }
            }
        }
        return $this->oDefaultShop;
    }

    protected function getShopwareDefaultShopURL() {
        $oShop = $this->getDefaultShop();
        $aUrl = array();
        $aUrl[] = ($oShop->getSecure() ? 'https' : 'http').'://'; //http protocol
        $sHost = trim((method_exists($oShop, 'getSecureHost') && $oShop->getSecure() ? $oShop->getSecureHost() : $oShop->getHost()));
        $aUrl[] = empty($sHost) ? Shopware()->Front()->Request()->getHttpHost() : $sHost;//domain or host name
        $aUrl[] = $oShop->getBasePath(); // path to shop
        return implode('', $aUrl).'/';
    }
}
