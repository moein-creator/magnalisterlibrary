<?php
class ML_Magento_Helper_Model_Product_MagentoTax {
    
    public static function getTax ($oMagentoProduct, $aAddressSets = null) {
        return Mage::getSingleton('tax/calculation')->getRate(self::getRateRequest($oMagentoProduct, $aAddressSets));
    }
    
    public static function getAppliedRates($oMagentoProduct, $aAddressSets = null) {
        return Mage::getSingleton('tax/calculation')->getAppliedRates(self::getRateRequest($oMagentoProduct, $aAddressSets));
    }
    
    private static function getRateRequest ($oMagentoProduct, $aAddressSets = null) {
        if ($aAddressSets === null) {
            $aAdresses = array(
                'Shipping' => null, 
                'Billing' => null,
            );
        } else {
            $aAdresses = array();
            foreach (array('Shipping', 'Billing') as $sAddressType) {
                $aAddressData = $aAddressSets[$sAddressType];
                $oAddress = new Varien_Object();
                $oAddress 
                    ->setCountryId($aAddressData['CountryCode'])
                    ->setPostcode($aAddressData['Postcode'])//some postcode
                ;
                if (array_key_exists('Suburb', $aAddressData) && !empty($aAddressData['Suburb'])) {
                    $oAddress->setRegionId(Mage::getModel('directory/region')
                        ->loadByCode($aAddressData['Suburb'], $aAddressData['CountryCode'])
                        ->getId()
                    );
                }
                $aAdresses[$sAddressType] = $oAddress;
            }
        }
        $oRateRequest = Mage::getSingleton('tax/calculation')->getRateRequest(
            $aAdresses['Shipping'], 
            $aAdresses['Billing'], 
            null, 
            $oMagentoProduct->getStore()
        );
        $oRateRequest->setProductClassId($oMagentoProduct->getTaxClassId());
        return $oRateRequest;
    }
    
}