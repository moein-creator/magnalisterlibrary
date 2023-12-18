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
 * Shopware6 specific implementation of the Http Model.
 */

use Redgecko\Magnalister\Controller\MagnalisterController;
use Shopware\Storefront\Framework\Csrf\CsrfPlaceholderHandler;
use Shopware\Storefront\Framework\Twig\Extension\CsrfFunctionExtension;


class ML_Shopware6_Model_Http extends ML_Shop_Model_Http_Abstract {

    /**
     * Gets the url to a file in the resources folder.
     * @param string $sFile
     *    Filename
     * @param bool $blAbsolute
     *
     * @return string
     */
    public function getResourceUrl($sFile = '', $blAbsolute = true) {

        if ($sFile == '') {
            if ($blAbsolute) {
                $returnValue = str_replace('public/index.php', 'public/css', MagnalisterController::getShopwareRequest()->server->get('SCRIPT_FILENAME'));
                $returnValue = str_replace('public_html/index.php', 'public_html/css', $returnValue);
                return $returnValue;
            } else {
                return '';
            }
        }

        $sBaseURL = MagnalisterController::getShopwareRequest()->getHttpHost().'/'.MagnalisterController::getShopwareRequest()->getBasePath();
        $sUrl = MagnalisterController::getShopwareRequest()->getScheme().'://'.str_replace('//', '/', $sBaseURL);
        $aResource = MLFilesystem::gi()->findResource('resource_'.$sFile);
        $sRelLibPath = substr($aResource['path'], strlen(MLFilesystem::getLibPath().'Codepool'));
        $sResourceType = strtolower(preg_replace('/^.*\/resource\/(.*)\/.*$/Uis', '$1', $sRelLibPath));
        if (basename($sResourceType) === 'js') {
            $mediaPath = MagnalisterController::getShopwareMyContainer()->get('kernel')->getProjectDir() . '/public/js';
            $sDstPath = $mediaPath . '/magnalister' . $sRelLibPath;
        }
        if (basename($sResourceType) === 'css') {
            $mediaPath = MagnalisterController::getShopwareMyContainer()->get('kernel')->getProjectDir() . '/public/css';
            $sDstPath = $mediaPath . '/magnalister' . $sRelLibPath;
        }
        if (basename($sResourceType) === 'images') {
            $mediaPath = MagnalisterController::getShopwareMyContainer()->get('kernel')->getProjectDir() . '/public/css';
            $sDstPath = $mediaPath . '/magnalister' . $sRelLibPath;
        }
        if (!file_exists($sDstPath)) {// we copy complete resource-type-folder if one file not exists
            $sSubPath = preg_replace('/^(.*\/resource\/.*)\/.*$/Uis', '$1', $sRelLibPath);
            $sSrcPath = substr($aResource['path'], 0, stripos($aResource['path'], $sSubPath) + strlen($sSubPath) + 1);
            $sDstPath = substr($sDstPath, 0, stripos($sDstPath, $sSubPath) + strlen($sSubPath) + 1);
            try {
                MLHelper::getFilesystemInstance()->cp($sSrcPath, $sDstPath);
            } catch (Exception $oEx) {
                MLMessage::gi()->addDebug($oEx, array(
                    '$sSrcPath' => $sSrcPath,
                    '$sDstPath' => $sDstPath,
                    '$sSubPath' => $sSubPath
                ));
                MLMessage::gi()->addError(MLI18n::gi()->get('sMessageCannotLoadResource'));
                MLSetting::gi()->set('blInlineResource', true, true);
            }
        }

        if ($sResourceType === 'js') {
            $sUrl .= '/js/magnalister'.$sRelLibPath;
        } elseif ($sResourceType === 'css') {
            $sUrl .= '/css/magnalister'.$sRelLibPath;
        } else {
            $sUrl .= '/css/magnalister'.$sRelLibPath;
        }

        return str_replace('\\', '/', $sUrl);
    }

    /**
     * just used for cUrl referer for api request .
     * @return string
     */
    public function getBaseUrl() {
        if (MLSetting::gi()->sDebugHost !== null) {
            return MLSetting::gi()->get('sDebugHost');
        } else {
            $sBaseURL = MagnalisterController::getShopwareRequest()->getHttpHost().'/'.MagnalisterController::getShopwareRequest()->getBasePath();
            return MagnalisterController::getShopwareRequest()->getScheme().'://'.str_replace('//', '/', $sBaseURL);
        }
    }

    /**
     * return url of administration of current shopware shop
     * @return string
     */
    public function getAdminUrl(): string {
        $sBaseURL = MagnalisterController::getShopwareRequest()->getHttpHost().'/'.MagnalisterController::getShopwareRequest()->getBasePath().'/admin';
        return MagnalisterController::getShopwareRequest()->getScheme().'://'.str_replace('//', '/', $sBaseURL);
    }

    /**
     * Gets the backend url of the magnalister app.
     * @param array $aParams
     *    name => value
     * @return string
     */
    public function getUrl($aParams = array()) {
        $sParent = parent::getUrl($aParams);

        return MagnalisterController::getShopwareRequest()->getRequestUri().(($sParent == '') ? '' : '?'.$sParent);
    }

    /**
     * Gets the request params merged from _POST and _GET.
     * @return array
     */
    public function getRequest() {
        $aOut = MLHelper::getArrayInstance()->mergeDistinct(MagnalisterController::getShopwareRequest()->request->all(), MagnalisterController::getShopwareRequest()->query->all());
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

        return array(
            '_csrf_token' =>
                MagnalisterController::getShopwareMyContainer()->get('security.csrf.token_manager')
                    ->getToken('magnalister.admin.page')
                    ->getValue()
        );
    }

    /**
     * Gets the magnalister cache FS url.
     * @return string
     */
    public function getCacheUrl($sFile = '') {
        return $this->getBackendBaseUrl().dirname($this->getMlLibPath()).'/writable/cache/'.$sFile;
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
        $sBaseURL = MagnalisterController::getShopwareRequest()->getHttpHost().'/'.MagnalisterController::getShopwareRequest()->getBasePath().'/magnalister/'.(($sParent == '') ? '' : '?'.$sParent);
        return MagnalisterController::getShopwareRequest()->getScheme().'://'.str_replace('//', '/', $sBaseURL);
    }

    /**
     * return directory or path (file system) of specific shop images
     * @return string
     */
    public function getShopImagePath() {
        return 'media/';
    }

    /**
     * return url of specific shop images
     * @param string $sFiles
     */
    public function getShopImageUrl() {
        $sBaseURL = MagnalisterController::getShopwareRequest()->getHttpHost().'/'.MagnalisterController::getShopwareRequest()->getBasePath().'/media/';
        return MagnalisterController::getShopwareRequest()->getScheme().'://'.str_replace('//', '/', $sBaseURL);
    }


}
