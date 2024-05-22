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
use Shopware\Storefront\Framework\Csrf\CsrfModes;
use Shopware\Storefront\Framework\Csrf\CsrfPlaceholderHandler;
use Shopware\Storefront\Framework\Twig\Extension\CsrfFunctionExtension;

MLFilesystem::gi()->loadClass('Shopware6_Model_Http');
class ML_Shopware66_Model_Http extends ML_Shopware6_Model_Http {

    public function getImageUrl($sFile) {
        $media = MLImage::gi()->getMediaObject($sFile);
        $sUrl=MagnalisterController::getMediaConverter()->generate(array($media));
        $sBaseUrl = '';
        if (strpos($sUrl[0], '/media/') !== false) {
            $sBaseUrl = explode('/media/', $sUrl[0])[0].'/media/';
        }
        $sMagnalisterImageUrl = $sBaseUrl.'magnalister/'.$sFile;
        return $sMagnalisterImageUrl;
    }
}
