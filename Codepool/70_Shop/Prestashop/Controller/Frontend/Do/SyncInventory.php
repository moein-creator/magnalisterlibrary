<?php

MLFilesystem::gi()->loadClass('Sync_Controller_Frontend_Do_SyncInventory');

/**
 * If a magnalister user has managed different marketplaces onther magnalister plugin,
 * and if different currency are related to configured mandant-shop for marketplaces, they couldn't be synchronized in same load of PHP
 * we prevent to sync, because it is problematic in prestashop
 *
 * @ToDo The procedure of preventing is wrong, we should try to synchronize one of marketplace in each synchronization CRON
 * Information: Till now I didn't see any client with the same case, so it is not needed to do this task very soon
 */
class ML_Prestashop_Controller_Frontend_Do_SyncInventory extends ML_Sync_Controller_Frontend_Do_SyncInventory {
    
    public function execute(){
        $aMps = $this->getMps();
        try{
            if($this->syncMultiMPAllowed(array_keys($aMps))){
                parent::execute();
            } else {
                $this->out( "##################################################################################################"
                        . "\n#\n#  Prestashop-Sync : Synchronisation of several marketplaces in same load is not possible.\n#\n"
                        . "##################################################################################################\n");
            }
        } catch (Exception $oEx){//if prestashop checking is failed in some cases we will run execute anyway
            parent::execute();
        }
    }
    
    protected function getMps(){
        $aMps = array();
        
        require_once MLFilesystem::getOldLibPath('php/callback/callbackFunctions.php');
        $iRequestMp=  MLRequest::gi()->data('mpid');
        foreach(magnaGetInvolvedMarketplaces() as $sMarketPlace){
            foreach(magnaGetInvolvedMPIDs($sMarketPlace) as $iMarketPlace){
                if($iRequestMp===null||$iRequestMp==$iMarketPlace){
                    $aMps[$iMarketPlace] = $sMarketPlace;
                }
            }
        }
        return $aMps;
    }
    
    public function syncMultiMPAllowed($aMpIds) {
        $blReturn = true;
        if(!empty($aMpIds)) {
            $aShopIds = MLDatabase::factorySelectClass()->select('Distinct(value)')->from(MLDatabase::factory('config')->getTableName())
                ->where("mkey = 'orderimport.shop' AND mpid in (".implode(',', $aMpIds).")")->getResult();
            $aFirstShop = array_shift($aShopIds);
            $oCurrency = MLCurrency::gi();
            if (isset($aFirstShop['value'])) {
                $iCurrency = $oCurrency->getShopCurrency($aFirstShop['value']);
                foreach ($aShopIds as $iId) {
                    if ($iCurrency != $oCurrency->getShopCurrency($iId['value'])) {
                        $blReturn = false;
                        break;
                    }

                }
            }
        }
        return $blReturn;
    }
}