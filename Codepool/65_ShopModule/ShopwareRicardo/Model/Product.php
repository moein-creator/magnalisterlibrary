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

MLFilesystem::gi()->loadClass('Shopware_Model_Product');
class ML_ShopwareRicardo_Model_Product extends ML_Shopware_Model_Product {
    
    protected function getPrice($blGros, $blFormated, $sPriceKind = '', $fPriceFactor = 0.0, $iPriceSignal = null, $fTax = null) {
        $oPHelper = MLHelper::gi('model_product');
        /* @var $oPHelper ML_Shopware_Helper_Model_Product */
        $fNet = $oPHelper->getDefaultPrices($this->getArticleDetail()->getId());

        if($fTax !== null){
            $fPercent = $fTax;
            $oTax = Shopware()->Models()->getRepository('\Shopware\Models\Tax\Tax')->findOneBy(array('tax' => $fTax));
            if(!is_object($oTax)){
                throw new Exception('tax "'.$fTax.'" doesn\'t exist');
            }
        } else {
            $fPercent = $this->getTax();
            $oTax = $this->oProduct->getTax();
        }
        $fBrutPrice = Shopware()->Modules()->Articles()->sCalculatingPriceNum($fNet, $fPercent, false, false, $oTax->getId());
        $oPrice = MLPrice::factory();
        //check usergroup tax if it is disabled we include tax manualy
        if (!Shopware()->System()->sUSERGROUPDATA["tax"]) {
            $fBrutPrice = $oPrice->calcPercentages(null, $fBrutPrice, $fPercent);
        }
        // check user group special price
        if($this->blDiscountMode){
            $fDiscount = -1 * (float)Shopware()->System()->sUSERGROUPDATA['basketdiscount'];
            $fBrutPrice = $oPrice->calcPercentages(null, $fBrutPrice, $fDiscount);
        }
        // add modulprice to brut
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
        if ($fPercent === null) {
            $fPercent = 0;
        }
        //calc netprice from modulprice
        $fNetPrice = $oPrice->calcPercentages($fBrutPrice, null, $fPercent);
        // define out price (unformated price of current shop)
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
        if ($blFormated) {
            return MLHelper::gi('model_price')->getPriceByCurrency($fUsePrice, null, true);
        } else {
            return MLHelper::gi('model_price')->getPriceByCurrency($fUsePrice, null);
        }
    }
     protected function getProperties() {
        try {
            $oArticle = $this->getLoadedProduct();
            $sPropertiesHtml = ' ';
            if (is_object($oArticle->getPropertyGroup())) {
                $aProperties = MLHelper::gi('model_product')->getProperties($this->getLoadedProduct()->getId(), $this->getLoadedProduct()->getPropertyGroup()->getId());

                if (isset($aProperties)) {
                    $sRowClass = 'odd';
                    $sPropertiesHtml .= '<ul class="magna_properties_list">';
                    foreach ($aProperties as $sName => $sValues) {
                        $sPropertiesHtml .= '<li class="magna_property_item ' . $sRowClass . '">'
                                . '<span class="magna_property_name">' . $sName
                                . '</span>: '
                                . '<span  class="magna_property_value">' . implode(', ', $sValues)
                                . '</span>'
                                . '</li>';
                        $sRowClass = $sRowClass === 'odd' ? 'even' : 'odd';
                    }
                    $sPropertiesHtml .= '</ul>';
                }
            }
            return $sPropertiesHtml;
        } catch (Exception $oEx) {
            return '';
        }
    }
}