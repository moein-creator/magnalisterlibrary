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
MLFilesystem::gi()->loadClass('Core_Update_Abstract');

use Redgecko\Magnalister\Controller\MagnalisterController;

/**
 * Class ML_Shopware6_Update_ResourcesToShopFolder
 * This update class is copied from Magento, but here it is very simpler
 * It goes through all js, css and images of magnalister plugin and copies them in Shopware 6 public folder
 * Where they are accessible
 */
class ML_Shopware6_Update_ResourcesToShopFolder extends ML_Core_Update_Abstract {

    public function execute() {
        $this->copy();
        return $this;
    }


    protected function copy() {
        foreach (MLFilesystem::glob(MLFilesystem::getLibPath('Codepool') . '/*/*/[rR]esource' . DIRECTORY_SEPARATOR . '*') as $sSrcPath) {
            $sBasePath = substr($sSrcPath, strlen(MLFilesystem::gi()->getLibPath('codepool')) + 1);
            if (basename($sBasePath) === 'js') {
                $mediaPath = MagnalisterController::getShopwareMyContainer()->get('kernel')->getProjectDir() . '/public/js';
            }
            if (basename($sBasePath) === 'css') {
                $mediaPath = MagnalisterController::getShopwareMyContainer()->get('kernel')->getProjectDir() . '/public/css';
            }
            if (basename($sBasePath) === 'images') {
                $mediaPath = MagnalisterController::getShopwareMyContainer()->get('kernel')->getProjectDir() . '/public/css';
                $sDstPath = $mediaPath . '/magnalister' . $sBasePath;
            }
            if (isset($mediaPath)) {
                $sDstPath = $mediaPath . '/magnalister' . (strpos($sBasePath, '/') === 0 ? '' : '/') . $sBasePath;
            }
            MLHelper::getFilesystemInstance()->cp($sSrcPath, $sDstPath);
        }
    }

}
