<?php

/**
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
 * $Id$
 *
 * (c) 2010 - 2014 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

MLFilesystem::gi()->loadClass('Magento_Model_Product');
class ML_MagentoRicardo_Model_Product extends ML_Magento_Model_Product {
    
    protected function getPrice($blGros, $blFormated, $sPriceKind = '', $fPriceFactor = 0.0, $iPriceSignal = null, $fTax = null) {
        $aData = $this->get('shopdata');
        if (Mage::helper('core')->isModuleEnabled('OrganicInternet_SimpleConfigurableProducts') && class_exists('OrganicInternet_SimpleConfigurableProducts_Helper_Data')) {
            $oProduct = $this->getRealProduct();
        } else {
            $oProduct = $this->getMagentoProduct();
        }
        $oProduct->setFinalPrice(null);
        // force group-price
        $fPriceBackup = $oProduct->getPrice();
        $oProduct->setPrice($this->getMagentoForecedGroupPrice());
        /* @var $oTax Mage_Tax_Helper_Data */
        $oTax = Mage::helper('tax');
        /* @var $oCurrency Mage_Core_Helper_Data */
        $oCurrency = Mage::helper('core');
        $oPrice = MLPrice::factory();
        // 1. calc brutprice
        $fBrutPrice = $oCurrency->currencyByStore($oTax->getPrice($oProduct, $oProduct->getFinalPrice(), true), $oProduct->getStore(), false, false);
        $fPercent = $oProduct->getTaxPercent();
        if ($fPercent === null) {
            $fPercent = 0;
        }
        if($fTax !== null) {
            $fNetOriginalPrice = $oPrice->calcPercentages($fBrutPrice, null, $fPercent);
            $fBrutPrice = $oPrice->calcPercentages(null, $fNetOriginalPrice, $fTax);
            $fPercent = $fTax;
        }
        // 2. add modulprice to brut
        if ($sPriceKind == 'percent') {
            $fBrutPrice = $oPrice->calcPercentages(null, $fBrutPrice, $fPriceFactor);
        } elseif ($sPriceKind == 'addition') {
            $fBrutPrice = $fBrutPrice + $fPriceFactor;
        }
        if ($iPriceSignal !== null) {
            //If price signal is single digit then just add price signal as last digit
            if (strlen((string)$iPriceSignal) == 1) {
                $fBrutPrice = (0.1 * (int)($fBrutPrice * 10)) + ($iPriceSignal / 100);
            } else {
                $fBrutPrice = ((int)$fBrutPrice) + ($iPriceSignal / 100);
            }
        }
        // 3. calc netprice from modulprice
        $fNetPrice = $oPrice->calcPercentages($fBrutPrice, null, $fPercent);
        // 4. define out price (unformated price of current shop)
        $fUsePrice = $blGros ? $fBrutPrice : $fNetPrice;
        $fUsePrice = round($fUsePrice, 2);
        //Check if last digit (second decimal) is 0 or 5. If not set 5 as default last digit
        $fUsePrice = 
            (((int)(string)($fUsePrice * 100)) % 5) == 0 // cast to string because it seems php have float precision in background
            ? $fUsePrice 
            : (((int)(string)($fUsePrice * 10)) / 10) + 0.05
        ;
        // round again, to be sure that precision is 2
        $fUsePrice = round($fUsePrice, 2);
        $oProduct->setPrice($fPriceBackup);
        if ($blFormated) {//recalc currency and format
            $fOutPrice = $fUsePrice / ($oProduct->getStore()->getCurrentCurrencyRate());
            return $oCurrency->currencyByStore($fOutPrice, $oProduct->getStore(), true);
        } else {
            return $fUsePrice;
        }
    }
    
}