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

class ML_ShopwareCloud_Model_Http extends ML_Shop_Model_Http_Abstract {

    public function __construct() {
    }

    /**
     * Gets the url to a file in the 'resources' folder.
     * @param string $sFile
     *    Filename
     * @param bool $blAbsolute
     *
     * @return string
     */
    public function getResourceUrl($sFile = '', $blAbsolute = true) {

        $sResourcesUrl = '';
        $aResource     = [
            'path'     => '',
            'resource' => ''
        ];

        if ($blAbsolute) {
            $sResourcesUrl .= $this->getBackendBaseUrl();
        }
        if ($sFile) {
            try {
                $aResource = MLFilesystem::gi()->findResource('resource_'.$sFile);
            } catch (\Exception $ex) {
                MLMessage::gi()->addDebug($ex);
            }
        }

        $customerName = CustomerHelper::gi()->getCustomer($_GET['shop-id'])['CustomerName'];
        $sResourcesUrl .= 'customers/' . $customerName . '/magnalister/';
        $sResourcesUrl .= substr($aResource['path'], strlen(MLFilesystem::getLibPath()));

        return $sResourcesUrl;
    }

    /**
     * just used for cUrl referer for api request .
     * @return string
     */
    public function getBaseUrl() {
        if (MLSetting::gi()->sDebugHost !== null) {
            return MLSetting::gi()->get('sDebugHost');
        } else {
            $shopUrl = $_GET['shop-url'];
            if (!isset($_GET['shop-url']) && isset($_GET['shop-id'])) {
                $shopUrl = CustomerHelper::gi()->getCustomer($_GET['shop-id'])['Shopware_ShopUrl'];
            }

            return $shopUrl;
        }
    }

    /**
     * return url of administration of current shopware shop
     * @return string
     */
    public function getAdminUrl(): string {
//        $sBaseURL = MagnalisterController::getShopwareRequest()->getHttpHost().'/'.MagnalisterController::getShopwareRequest()->getBasePath().'/admin';
//        return MagnalisterController::getShopwareRequest()->getScheme().'://'.str_replace('//', '/', $sBaseURL);
        return '';
    }

    /**
     * Gets the backend url of the magnalister app.
     * @param array $aParams
     *    name => value
     * @return string
     */
    public function getUrl($aParams = array())
    {
        $sParent = parent::getUrl($aParams);
        if ($sParent)
        {
            $sParent = '&' . $sParent;
        }

        $sUrl = $this->getBackendBaseUrl() . '?' . $this->getShopifyQueryString() . $sParent;

        return $sUrl;
    }

    /**
     * Returns Shopify URL.
     *
     * @return string
     */
    public function getShopifyQueryString()
    {
        $aShopifyUrlParams = $_GET;
        unset($aShopifyUrlParams['ml']);
        $shopifyQueryString = http_build_query($aShopifyUrlParams, '', '&');

        return $shopifyQueryString;
    }

    /**
     * return url that currently used to log in to shopify shop-admin
     * @return string
     */
    public function getBackendBaseUrl() {
        $aServer = $this->getServerRequest();
        $sBaseUrl = 'http';

        if ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || $_SERVER['SERVER_PORT'] == 443
        ) {
            $sBaseUrl = 'https';
        }

        $sBaseUrl .= '://'.$aServer['HTTP_HOST'].'/';

        return $sBaseUrl;
    }


    /**
     * Returns _SERVER.
     * @return array
     */
    public function getServerRequest() {
        return $_SERVER;
    }

    /**
     * Returns filtered request.
     *
     * @return array
     */
    public function getRequest()
    {
        $aOut = MLHelper::getArrayInstance()->mergeDistinct($_GET, $_POST);

        $aFilteredRequest = $this->filterRequest($aOut);

        return $aFilteredRequest;
    }

    /**
     * Returns only request data that has been prefixed by our prefix (currently ml).
     *
     * If sRequestPrefix is setted (MLSetting::gi()->sRequestPrefix = 'ml') it just returns the defined array value
     * eg.
     *    sRequestPrefix= 'ml'
     *    param $aArray = array('key' => 'value', 'session' => '535jkhk345jkh34', 'ml[module]' => 'tools', ml['tools'] => 'config' )
     *    => return array('module' => 'tools', 'tools' => 'config');
     * Otherwise full array
     * @return array
     */
    protected function filterRequest($aArray){
        $sPrefix = MLSetting::gi()->get('sRequestPrefix');
        if ($sPrefix != '') {
            $aArray = isset($aArray[$sPrefix]) ? $aArray[$sPrefix] : array();
            if (isset($aArray['FullSerializedForm'])) {
                $aRequestArray= $this->parseUrlToArray($aArray['FullSerializedForm']);
                foreach ($aRequestArray[$sPrefix] as $sKey => $mValue) {
                    if(!isset($aArray[$sKey])){
                        $aArray[$sKey] = $mValue;
                    }
                }
                unset($aArray['FullSerializedForm']);
            }
            $aArray = $this->validate($aArray);
        }
        return $aArray;
    }

    /**
     * Returns additional fields.
     * Parse hidden fields that are wanted by different shop systems for security measurements.
     *
     * @return array
     *    Assoc of hidden neccessary form fields array(name => value, ...)
     */
    public function getNeededFormFields() {
        return [];
    }

    /**
     * Gets the magnalister cache FS url. This function has been used in "Service and developer -> Filesystem -> log"
     * @return string
     */
    public function getCacheUrl($sFile = '') {
        $customerName = CustomerHelper::gi()->getCustomer($_GET['shop-id'])['CustomerName'];
        return $this->getBackendBaseUrl(). 'customers/' . $customerName . '/writable/cache/'.$sFile;
    }

    /**
     * Gets the frontend url of the magnalister app.
     * @param array $aParams
     * @return string
     */
    public function getFrontendDoUrl($aParams = array()) {
        $sConfig = $this->getConfigFrontCronURL($aParams);
        if ($sConfig !== '') {
            return $sConfig;
        }
        $sParent = parent::getUrl($aParams);
        $aQueryString = $this->getQueryStringAsArray();

        $sUrl = $this->getBackendBaseUrl() . 'do/?' . (isset($aQueryString['shop-id']) ? 'shop-id='.$aQueryString['shop-id'].'&' : '') . (isset($aQueryString['shop-url']) ? 'shop-url='.$aQueryString['shop-url'].'&' : '') .$sParent;

        return $sUrl;
    }

    /**
     * Returns query string.
     *
     * @return string e.g. ?variable1=1&variable2=2&variable3=3...
     */
    public function getQueryString()
    {
        return $_SERVER['QUERY_STRING'];
    }

    /**
     * Returns query string as an array.
     *
     * @return array Example is shown below.
     *      [
     *          variable1 => 1,
     *          variable2 => 2,
     *          variable3 => 3
     *      ]
     */

    public function getQueryStringAsArray()
    {
        parse_str($this->getQueryString(), $aQueryString);

        return $aQueryString;
    }

    /**
     * return directory or path (file system) of specific shop images
     * @return string
     */
    public function getShopImagePath() {
        return DIR_MAGNALISTER_CUSTOMERS . CustomerHelper::gi()->getCustomerDirectory(MLShopwareCloudAlias::getShopHelper()->getShopId()) . '/media/image/';
    }

    /**
     * return directory or path (file system) of specific shop images
     * @param string $sFiles
     */
    public function getImagePath($sFile) {
        if(self::$sImagePath === null ){
            $sShopwareImagePath = $this->getShopImagePath();
            if(!file_exists($sShopwareImagePath.'magnalister/')){
                mkdir($sShopwareImagePath.'magnalister/', 0755, true);
            }
            self::$sImagePath = $sShopwareImagePath.'magnalister/';
        }
        return self::$sImagePath.$sFile;
    }

    /**
     * return url of specific shop images
     * @param string $sFiles
     */
    public function getShopImageUrl() {
        return M_HTTP_WEB_DIR.'/customers/'.CustomerHelper::gi()->getCustomerDirectory(MLShopwareCloudAlias::getShopHelper()->getShopId()).'/media/image/';
    }


}
