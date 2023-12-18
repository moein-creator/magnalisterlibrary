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

class ML_Idealo_Update_MigrationToMOAPIv2 extends ML_Core_Update_Abstract {

    public function execute() {
        $blInit = false;
        $oDB = MLDatabase::getDbInstance();
        if ($oDB->tableExists('magnalister_config')) {
            foreach (MLShop::gi()->getMarketplaces() as $iMarketPlace => $sMarketplace) {
                if($sMarketplace === 'idealo') {

                    $oSelectConfig = MLDatabase::factorySelectClass();
                    $aConfigs = $oSelectConfig->from('magnalister_config')->where("mkey IN('checkout.token', 'directbuyactive') AND mpid='".((int)$iMarketPlace)."'")->getResult();
                    $sCheckoutToken = null;
                    $sDirectBuyActive = null;
                    foreach ($aConfigs as $aConfig){
                        if($aConfig['mkey'] === 'checkout.token'){
                            $sCheckoutToken = $aConfig['value'];
                        }
                        if($aConfig['mkey'] === 'directbuyactive'){
                            $sDirectBuyActive = $aConfig['value'];
                        }
                    }
                    if (
                        empty($sDirectBuyActive) && !empty($sCheckoutToken)
                    ) {
                        foreach ($aConfigs as $aConfig) {
                            $oDB->insert('magnalister_config', array('mpid' => $iMarketPlace, 'mkey' => 'directbuyactive', 'value' => 'true'));
//                            MLDatabase::factorySelectClass()->from('magnalister_config')
//                                ->where("mkey = 'checkout.token' AND mpid='".((int)$iMarketPlace)."'")
//                                ->delete('magnalister_config')->doDelete();
                        }
                    }
                }
            }
        }
        return parent::execute();
    }

}