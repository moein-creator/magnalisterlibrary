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

class ML_Hitmeister_Model_Modul extends ML_Modul_Model_Modul_Abstract {

    public function getMarketPlaceName($blIntern = true) {
        return $blIntern ? 'hitmeister' : MLI18n::gi()->get('sModuleNameHitmeister');
    }

    public function getConfig($sName = null) {
        if ($sName == 'currency') {
            $mReturn = 'EUR';
        } else {
            $mReturn = parent::getConfig($sName);
        }
        
        if ($sName === null) {// merge
            $mReturn = MLHelper::getArrayInstance()->mergeDistinct($mReturn, array(
                'lang' => parent::getConfig('lang'), 
                'currency' => 'EUR'
            ));
        }
        
        return $mReturn;
    }

    public function getStockConfig($sType = null) {
        return array(
            'type' => $this->getConfig('quantity.type'),
            'value' => $this->getConfig('quantity.value')
        );
    }
    
    /**
     * @return array('configKeyName'=>array('api'=>'apiKeyName', 'value'=>'currentSantizedValue'))
     */
    protected function getConfigApiKeysTranslation() {
        $aConfig = $this->getConfig();
        $sDate = $aConfig['preimport.start'];
        //magento tip to find empty date
        $sDate = (preg_replace('#[ 0:-]#', '', $sDate) === '') ? date('Y-m-d') : $sDate;
        $sDate = date('Y-m-d', strtotime($sDate));
        $sSync = $this->getConfig('stocksync.tomarketplace');
        $sMinimumPrice = $this->getConfig('minimumpriceautomatic');
        return array_merge(
            array(
                'import'                  => array('api' => 'Orders.Import', 'value' => ($this->getConfig('import') ? 'true' : 'false')),
                'preimport.start'         => array('api' => 'Orders.Import.Start', 'value' => $sDate),
                'stocksync.tomarketplace' => array('api' => 'Callback.SyncInventory', 'value' => isset($sSync) ? $sSync : 'no'),
                'minimumpriceautomatic' => array('api' => 'Price.MinimumPriceAutomatic', 'value' => $sMinimumPrice === '0' ? '0' : '1'),
            ), $this->getInvoiceAPIConfigParameter()
        );
    }
    
    public function isMultiPrepareType(){
        return true;
    }

    public function getCarriers(){
        try {
            $aResponse = MagnaConnector::gi()->submitRequestCached(array('ACTION' => 'GetOrderStatusData'), 60);
            if ($aResponse['STATUS'] == 'SUCCESS' && isset($aResponse['DATA']) && is_array($aResponse['DATA'])) {
                return $aResponse['DATA']['CarrierCodes'];
            }else{
                return array();
            }
        } catch (MagnaException $e) {
            return array();
        }

    }

    /**
     * @param $oShop ML_Shopware_Model_Product|ML_Shopware6_Model_Product
     * @param $mAttributeCode
     * @return |null
     */
    public function shopProductGetAttributeValue($oShop, $mAttributeCode) {
        // pd_Weight in Shopware 5 & Shopify
        if (strpos('pd_', $mAttributeCode) === 0) {
            $aAttribute = explode('_', $mAttributeCode, 2);
            $sAttributeCode = $aAttribute[1];

            $sFunction = 'get'.ucfirst($sAttributeCode);

            if (in_array($mAttributeCode, array('pd_Weight')) && method_exists($oShop, $sFunction)) {
                return $oShop->$sFunction();
            }
        } elseif ($mAttributeCode === 'Weight' && $oShop instanceof ML_Shopware6_Model_Product) { // $mAttributeCode == 'Weight' in Shopware 6
            $weightArray = $oShop->getWeight();

            // check if it is only inherited by parent
            if (empty($weightArray)) {
                return $oShop->getParent()->getWeight();
            }
            return $weightArray;
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function submitFirstTrackingNumber() {
        return false;
    }
}
