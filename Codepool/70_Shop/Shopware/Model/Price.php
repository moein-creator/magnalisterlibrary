<?php

class ML_Shopware_Model_Price extends ML_Shop_Model_Price_Abstract implements ML_Shop_Model_Price_Interface {

    public function format($fPrice, $sCode, $blConvert = true) {        
        if (!isset($sCode) || $sCode == null) {
            throw new Exception("the sCode should not be empty");
         }
        if ($blConvert) {            
                $oCurrency = Shopware()->Models()->getRepository('\Shopware\Models\Shop\Currency')->findOneBy(array("currency" => $sCode));
                if (is_null($oCurrency)) {
                    throw new Exception("Please add the currency '".$sCode."' to your shopware shop");
                }
                if ($oCurrency->getFactor()) {
                    $fPrice = floatval($fPrice) * floatval($oCurrency->getFactor());
                }            
        }

        return MLHelper::gi('model_price')->getPriceByCurrency($fPrice, $sCode, true);
        
    }
    
    public function getSpecialPriceConfigKey(){
        return 'price.discountmode';
    }

}