<?php

class ML_Shopware_Helper_Model_Price {

    public function getPriceByCurrency($mValue, $sCurrency = null, $blFormated = false) {
        if ($sCurrency === null) {
            $format = Shopware()->Modules()->System()->sSYSTEM->sCurrency;
        } else {
            $oCurrency = Shopware()->Models()->getRepository('\Shopware\Models\Shop\Currency')->findBy(array('currency' => $sCurrency));
            if ($oCurrency == null) {
                throw new Exception('Currency '.$sCurrency.' doesn\'t exist in your shop ');
            }
            $format = Shopware()->Models()->toArray(current($oCurrency));
        }
        if (isset($format['symbolPosition'])) {
            $format['position'] = $format['symbolPosition'];
        }
        if ($format['position'] == 0) { //customer selected standard position , that mean in Right position
            $format['position'] = 16;
        }
        $config = array(
            'display' => ($blFormated ? "{$format['symbol']} " : 1),
            'format' => ($blFormated ? null : '###0.00')
        );
        $oMLPrice = MLPrice::factory();

        try {
            if (!class_exists('Enlight_Application') || !method_exists(Enlight_Application::Instance(),'Instance')){
                throw new Exception();
            }
            $currency = Enlight_Application::Instance()->Currency();
        } catch (\Exception $ex) {
            $currency = Shopware()->Container()->get('currency');
        }
        $sLocale = Shopware()->Shop()->getLocale()->getLocale();
        if(!empty($sLocale)){
            try {
            $currency->setLocale($sLocale);
            } catch (Exception $ex) {
                $currency->setLocale('DE');
            }
        }
        try {
            $currency->setFormat($format);
        } catch (Exception $ex) { //set format is wrong
            $format['position'] = 32;
            $currency->setFormat($format);
        }
        $mValue = (float)$oMLPrice->unformat((string)$mValue);
        $mValue = $currency->toCurrency($mValue, $config);
        if (!$blFormated) {
            $mValue = $oMLPrice->unformat($mValue);
        }
        return $mValue;
    }

}