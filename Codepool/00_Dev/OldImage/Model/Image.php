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

MLFilesystem::gi()->loadClass('Base_Model_Image');

class ML_OldImage_Model_Image extends ML_Base_Model_Image {

    public function __construct() {  
        if (!file_exists(MLHttp::gi()->getImagePath('oldimages'))) {
            @mkdir(MLHttp::gi()->getImagePath('oldimages'), 0777, true);
        }
    }

    public function resizeImage($sSrc, $sType, $iMaxWidth, $iMaxHeight, $blUrlOnly = false) {
        $aResultImage = array();
        foreach (array('product', 'Product') as $sType) {
            $sDst = 'oldimages/' . $sType . '_' . $iMaxWidth . 'x' . $iMaxHeight . '_' . md5($sSrc) . '.' . pathinfo($sSrc, PATHINFO_EXTENSION);
            if (!file_exists(MLHttp::gi()->getImagePath($sDst))) {
                if (@getimagesize($sSrc)) { 
                    $this->resize($sSrc, $iMaxWidth, $iMaxHeight, MLHttp::gi()->getImagePath($sDst));
                    $aResultImage[] = MLHttp::gi()->getImageUrl($sDst);
                } else {
                    throw new Exception('Image doesn\'t exists :' . $sSrc);
                }
            }
        }
        return $aResultImage;
    }

}