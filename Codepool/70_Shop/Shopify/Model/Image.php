<?php

MLFilesystem::gi()->loadClass('Base_Model_Image');

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
 * @todo Investigate and implement class.
 *
 * Class ML_Shopify_Model_Image
 *
 */
class ML_Shopify_Model_Image extends ML_Base_Model_Image
{

    /**
     * The Shopify-API has an easy mechanism to resize product images, not needed to resize them in magnalister server.
     * @param $sSrc
     * @param $sType
     * @param $iMaxWidth
     * @param $iMaxHeight
     * @param bool $blUrlOnly
     * @return array|string
     * @throws Exception
     */
    public function resizeImage($sSrc, $sType, $iMaxWidth, $iMaxHeight, $blUrlOnly = false) {
        $sType = strtolower($sType);
        $iPos = strrpos($sSrc ,'.');
        if($iPos !== false) {
            $sDst = substr($sSrc, 0, $iPos) . '_' . $iMaxWidth . 'x' . $iMaxHeight . substr($sSrc, $iPos);
            $iQuestionPos = strpos($sDst, '?v=');
            if ($iQuestionPos !== false) {
                $sDst = substr($sDst, 0, $iQuestionPos);
            }
            if ($blUrlOnly) {
                return $sDst;
            } else {
                return array(
                    'url' => $sDst,
                    'width' => $iMaxWidth,
                    'height' => $iMaxHeight,
                    'alt' => basename($sSrc)
                );
            }
        }else {
            MLMessage::gi()->addDebug('Cannot be found where we can add the size of the image.');
        }
    }

}
