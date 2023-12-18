<?php
class ML_Magento_Model_Currency extends ML_Shop_Model_Currency_Abstract {
    
    /**
     * 
     * @todo method is a stub, make app without simple prict
     * @todo check if we really need foramtting option, guess its all done inside shpspecific classes.
     * @return array
     */
    public function getList(){
        if (!MLCache::gi()->exists(strtoupper(get_class($this)).'__currencylist.json')) {
            $aCurrencies = array();
            $aAllowedCurrencies = Mage::getModel('directory/currency')->getConfigAllowCurrencies();
            $aCurrencyRates = Mage::getModel('directory/currency')->getCurrencyRates(Mage::app()->getBaseCurrencyCode(), array_values($aAllowedCurrencies));
            foreach ($aAllowedCurrencies as $sCurrency) {
                if ($sCurrency == 'EUR') {
                    $aCurrency = array ('thousands_point' => '', );
                } elseif ($sCurrency == 'USD') {
                    $aCurrency = array ('decimal_point' => '.', 'thousands_point' => ',', );
                }elseif ($sCurrency == 'GBP') {
                    $aCurrency = array ('decimal_point' => '.', 'thousands_point' => ',',);
                } else {
                    $aCurrency = array();
                }
                /* @var $oCurrency Zend_Currency */
                $oCurrency = Mage::app()->getLocale()->currency($sCurrency);
                $oCurrencyReflectionPropertyOptions = new ReflectionProperty($oCurrency, '_options');
                $oCurrencyReflectionPropertyOptions->setAccessible(true);
                $aCurrencyOptions = $oCurrencyReflectionPropertyOptions->getValue($oCurrency);
                $oCurrencyReflectionPropertyOptions->setAccessible(false);
                $aLocaleSymbols = Zend_Locale_Data::getList($aCurrencyOptions['locale'], 'symbols');
                $aCurrencyOptions['symbol'] = $oCurrency->getSymbol() === null ? $sCurrency : $oCurrency->getSymbol();
                foreach (array('title', 'symbol_left', 'symbol_right', 'decimal_point', 'thousands_point', 'decimal_places', 'value') as $sKey) {
                    if (!array_key_exists($sKey, $aCurrency)) {
                        switch ($sKey) {
                            case 'title': {
                                $sValue = $oCurrency->getName();
                                break;
                            }
                            case 'symbol_left': {
                                $sValue = $aCurrencyOptions['position'] == Zend_Currency::LEFT ? $aCurrencyOptions['symbol'] : '';
                                break;
                            }
                            case 'symbol_right': {
                                $sValue = $aCurrencyOptions['position'] == Zend_Currency::LEFT ? '' : $aCurrencyOptions['symbol'];
                                break;
                            }
                            case 'decimal_point': {
                                $sValue = $aLocaleSymbols['decimal'];
                                break;
                            }
                            case 'thousands_point': {
                                $sValue = $aLocaleSymbols['group'];
                                break;
                            }
                            case 'decimal_places': {
                                $sValue = $aCurrencyOptions['precision'];
                                break;
                            }
                            case 'value': {
                                $sValue = $aCurrencyRates[$sCurrency] === null ? 1 : $aCurrencyRates[$sCurrency];
                                break;
                            }
                            default: {
                                $sValue = null;
                            }
                        }
                        $aCurrency[$sKey] = $sValue;
                    }
                }
                $aCurrencies[$sCurrency] = $aCurrency;
            }
            MLCache::gi()->set(strtoupper(get_class($this)).'__currencylist.json', $aCurrencies, 5 * 60);
        }
        return MLCache::gi()->get(strtoupper(get_class($this)).'__currencylist.json');
    }
    
    public function getDefaultIso(){
        try {
            $iLang = MLModul::gi()->getConfig('lang');
        } catch (Exception $oEx) {
            $iLang = 0;
        }
        return Mage::app()->getStore($iLang)->getCurrentCurrency()->getCode();
    }
    
    /**
     * magento updates all
     */
    public function updateCurrencyRate($sCurrency) {
        Mage::app()->getStore()->setConfig(Mage_Directory_Model_Observer::IMPORT_ENABLE, true);
        $oC=new Mage_Directory_Model_Observer;
        $oC->scheduledUpdateCurrencyRates('');
        MLCache::gi()->delete(strtoupper(get_class($this)).'__currencylist.json');
        return $this;
    }
    
}