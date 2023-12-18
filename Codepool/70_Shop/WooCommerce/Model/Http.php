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
 * WooCommerce specific implementation of the Http Model.
 */
class ML_WooCommerce_Model_Http extends ML_Shop_Model_Http_Abstract {
    /**
     * Returns the path of the magnalister Lib/ path beginning from the engine/ directory.
     * @return string
     * @throws ML_Filesystem_Exception
     */
    protected function getMlLibPath() {
        return '/' . str_replace(ABSPATH, '', WP_CONTENT_DIR) . '/plugins/magnalister/lib/';
    }

    /**
     * Gets the url to a file in the resources folder.
     *
     * @param string $sFile
     *    Filename
     * @param bool $blAbsolute
     *
     * @return string
     * @throws ML_Filesystem_Exception
     */
    public function getResourceUrl($sFile = '', $blAbsolute = true) {
        $sPath = ($blAbsolute ? get_site_url() : '../../') . $this->getMlLibPath();

        $sExt = pathinfo($sFile, PATHINFO_EXTENSION);
        $aExt = explode('?',
            $sExt);//separate extention of file from url query that most of the time is current version of magnalister

        try {
            $aResource = empty($sFile) ? array('path' => '') : MLFilesystem::gi()->findResource('resource_' . $sFile);
        } catch (Exception $oExc) {//if file was not found , try to find resource by its type
            try {
                $aResource = MLFilesystem::gi()->findResource('resource_' . $aExt[0] . '_' . $sFile);
            } catch (Exception $oExc) {

                return '';//no file is found
            }
        }

        $sLibPath = MLFilesystem::getLibPath();
        $aResourcePath = explode($sLibPath, $aResource['path']);
        if (array_key_exists('1', $aResourcePath)) {
            $sUrl = $sPath . $aResourcePath[1];
        } else {
            $sUrl = $sPath;
        }

        if (count($aExt) > 1) {//add url query part if exist
            $sUrl .= '?' . $aExt[1];
        }
        $sUrl = str_replace('\\', '/', $sUrl);//replace backslashes in windows

        return $sUrl;
    }

    /**
     * get main url of Wordpress
     * @return string
     */
    protected function getWordpressUrl(){
        $aUrl = array();
        global $wpdb;
        $results = $wpdb->get_row("SELECT option_value FROM $wpdb->options WHERE option_name = 'siteurl'");
        $aUrl[] = $results->option_value; // path to shop

        return implode('', $aUrl) . '/';
    }

    /**
     * just used for cUrl referer for api request .
     * @return string
     */
    public function getBaseUrl() {
        if(MLSetting::gi()->data('sDebugHost') !== null){
            return MLSetting::gi()->data('sDebugHost');
        } else {
            return $this->getWordpressUrl();
        }
    }

    /**
     * return base url
     * @return string
     */
    public function getBackendBaseUrl() {
        $sBaseUrl = get_site_url(). '/wp-admin/admin.php?page=magnalister&';

        return $sBaseUrl;
    }

    /**
     * Gets url of the magnalister app.
     *
     * @param array $aParams
     *    name => value
     *
     * @return string
     */
    public function getUrl($aParams = array()) {
        $sParent = parent::getUrl($aParams);
        return $this->getBackendBaseUrl() . $sParent;

    }

    /**
     * Gets the request params merged from POST and GET.
     * @return array
     */
    public function getRequest() {
        $_mlPost = stripslashes_deep( $_POST );
        $_mlGet = stripslashes_deep( $_GET );
        $aOut = MLHelper::getArrayInstance()->mergeDistinct($_mlGet, $_mlPost);
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
     * @param string $sFile
     * @return string
     * @throws ML_Filesystem_Exception
     */
    public function getCacheUrl($sFile = '') {

        return $this->getBackendBaseUrl() . dirname($this->getMlLibPath()) . '/writable/cache/' . $sFile;
    }

    /**
     * Gets the frontend url of the magnalister app.
     *
     * @param array $aParams
     *
     * @return string
     */
    public function getFrontendDoUrl($aParams = array()) {
        $sConfig = $this->getConfigFrontCornURL($aParams);
        if ($sConfig !== '') {
            return $sConfig;
        }
        $sParent = parent::getUrl($aParams);
        $aUrl = array();
        global $wpdb;
        $results = $wpdb->get_row("SELECT option_value FROM $wpdb->options WHERE option_name = 'siteurl'");
        $aUrl[] = $results->option_value; // path to shop
        $aUrl[] = '/magnalister-frontend/?';// path to magnalister front controller
        $aUrl[] = $sParent; // parameter

        return implode('', $aUrl);
    }

    /**
     * return directory or path (file system) of specific shop images
     * @return string
     */
    public function getShopImagePath() {

        return WP_CONTENT_DIR . '/uploads/';
    }

    /**
     * return url of specific shop images
     */
    public function getShopImageUrl() {
        return WP_CONTENT_URL . '/uploads/';
    }
}
