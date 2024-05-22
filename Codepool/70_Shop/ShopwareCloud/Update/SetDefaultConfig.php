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
/**
 * to set default value in global configuration
 */
class ML_ShopwareCloud_Update_SetDefaultConfig extends ML_Core_Update_Abstract {

    public function execute() {
        $blFirstInstallation = false;
        $oConfig = MLDatabase::factory('config')->set('mpid', 0)->set('mkey', 'general.ean');
        if ($oConfig->get('value') === null) {
            $blFirstInstallation = true;
            $oConfig->set('value', 'ean')->save();
        }
        $oConfig = MLDatabase::factory('config')->set('mpid', 0)->set('mkey', 'general.manufacturer');
        if ($oConfig->get('value') === null) {
            $oConfig->set('value', 'manufacturerId')->save();
        }
        $oConfig = MLDatabase::factory('config')->set('mpid', 0)->set('mkey', 'general.manufacturerpartnumber');
        if ($oConfig->get('value') === null) {
            $oConfig->set('value', 'manufacturerNumber')->save();
        }
        if ($blFirstInstallation) {
            MLDatabase::factory('config')->set('mpid', 0)->set('mkey', 'shopwareCloudStartingFirstProductImport')->set('value', '1')->save();
        }
        return parent::execute();
    }

}
