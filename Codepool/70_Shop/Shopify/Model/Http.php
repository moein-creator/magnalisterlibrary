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

use Magna\Library\DirectoryLibrary;

/**
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
 * $Id$
 *
 * (c) 2010 - 2017 RedGecko GmbH -- http://www.redgecko.de/
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 *
 * Class ML_Shopify_Model_Http
 *
 */
class ML_Shopify_Model_Http extends ML_Shop_Model_Http_Abstract
{

    /**
     * Returns resources URL.
     *
     * @param string $sFile
     * @param bool $blAbsolute
     *
     * @return string
     */
    public function getResourceUrl($sFile = '', $blAbsolute = true)
    {

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

        $sResourcesUrl .= 'customers/' . $this->getCustomerDirectoryName() . '/magnalister/';
        $sResourcesUrl .= substr($aResource['path'], strlen(MLFilesystem::getLibPath()));

        return $sResourcesUrl;
    }

    /**
     * Returns customer directory name.
     *
     * @return string
     */
    private function getCustomerDirectoryName()
    {
        $oDirectoryLibrary = new DirectoryLibrary();
        $sShopId = MLHelper::gi('model_shop')->getShopId();

        return $oDirectoryLibrary->getCustomerDirectoryName($sShopId);
    }

    /**
     * Returns execution URL.
     *
     * @param array $aParams
     *
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
        $shopifyQueryString = http_build_query($aShopifyUrlParams, null, '&');

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
     * Returns shop URL for magnalister authentication.
     *
     * @return string
     */
    public function getBaseUrl() {
        try {
            $sBaseUrl = MLSetting::gi()->get('sDebugHost');
        } catch (Exception $ex) {
            $aQueryString = MLHelper::gi('model_http')->getQueryStringAsArray();
            $sBaseUrl = 'https://'.(isset($aQueryString['shop']) ? $aQueryString['shop'] : '');
        }

        return $sBaseUrl;
    }

    /**
     * Returns cache url.
     *
     * @param string $sFile
     *
     * @return string
     */
    public function getCacheUrl($sFile = '')
    {
        return $this->getBackendBaseUrl(). 'customers/' . $this->getCustomerDirectoryName() . '/writable/cache/' . $sFile;
    }

    /**
     * @todo Investigate and implement function.
     *
     * @param array $aParams
     *
     * @return string
     */
    public function getFrontendDoUrl($aParams = array()) {
        $sParent = parent::getUrl($aParams);
        $aQueryString = MLHelper::gi('model_http')->getQueryStringAsArray();
        $sUrl = $this->getBackendBaseUrl() . 'do/?' . (isset($aQueryString['shop']) ? 'shop='.$aQueryString['shop'].'&' : '') . $sParent;

        return $sUrl;
    }

    /**
     * Returns server request.
     *
     * @return mixed
     */
    public function getServerRequest()
    {
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
     * Returns additional fields.
     * Parse hidden fields that are wanted by different shop systems for security measurements.
     *
     * @return array
     *    Assoc of hidden neccessary form fields array(name => value, ...)
     */
    public function getNeededFormFields()
    {
        return [];
    }

    /**
     * Returns directory or path (file system) of specific shop images
     **
     * @todo Investigate and implement function.
     */
    public function getShopImagePath()
    {
        return 'customers/' . $this->getCustomerDirectoryName() . '/media/image/';
    }

    /**
     * Returns full path to the image
     *
     * @todo Investigate and implement function.
     */
    public function getShopImageUrl()
    {
        return $this->getBackendBaseUrl(). 'customers/' . $this->getCustomerDirectoryName() . '/media/image/';
    }


}
