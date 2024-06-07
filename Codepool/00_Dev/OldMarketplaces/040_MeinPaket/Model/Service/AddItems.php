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
class ML_MeinPaket_Model_Service_AddItems extends ML_Modul_Model_Service_AddItems_Abstract {

    protected function uploadItems() {
        return $this;
    }

    protected function getProductArray() {
        /* @var $oHelper ML_MeinPaket_Helper_Model_Service_Product */
        $oHelper = MLHelper::gi('Model_Service_Product');
        $aMasterProducts = array();
        foreach ($this->oList->getList() as $oProduct) {
            /* @var $oProduct ML_Shop_Model_Product_Abstract */
            $oHelper->setProduct($oProduct);
            $aVariants = $this->oList->getVariants($oProduct);

            $oMasterProduct = $oHelper->setVariant($oProduct)->getData(true);
            $oMasterProduct['Variations'] = array();
            if (count($aVariants) == 1 && current($aVariants)->getVariatonData() == array()) {
                $oMaster = current($aVariants);                
                $iAdditemIndex = $oMaster->get('id');
                if ($this->oList->isSelected($oMaster)) {
                    $oHelper->resetData();
                    $oMasterProduct = array_merge($oMasterProduct, $oHelper->setVariant($oMaster)->getData());
                }
            } else {
                $iAdditemIndex = $oProduct->get('id');
                foreach ($aVariants as $oVariant) {
                    /* @var $oVariant ML_Shop_Model_Product_Abstract */
                    if ($this->oList->isSelected($oVariant)) {
                        $oHelper->resetData();

                        $oVariantProduct = $oHelper->setVariant($oVariant)->getData();
                        $oMasterProduct['ShippingDetails'] = $oVariantProduct['ShippingDetails'];
                        $oMasterProduct['RawAttributesMatching'] = $oVariantProduct['RawAttributesMatching'];
                        $oMasterProduct['variation_theme'] = $oVariantProduct['variation_theme'];
                        $oMasterProduct['Variations'][] = $oVariantProduct;
                    }
                }

                $this->processVariations($oMasterProduct);
            }

            if(!isset($oMasterProduct['ShippingDetails']['ShippingType'])){
                $oMasterProduct['ShippingDetails']['ShippingType'] = '';
            }
            // Implemented here because master item is not stored in prepare table.
            $oMasterProduct['MarketplaceCategory'] = MLDatabase::factory('meinpaket_prepare')
                ->set('products_id', reset($aVariants)->get('id'))
                ->get('PrimaryCategory');

            $oMasterProduct['MarketplaceCategory'] = str_replace('_', '.', $oMasterProduct['MarketplaceCategory']);

            $aMasterProducts[$iAdditemIndex] = $oMasterProduct;
        }

        return $aMasterProducts;
    }

    protected function processVariations(&$aProduct) {
        foreach ($aProduct['Variations'] as &$aVariant) {
            unset($aVariant['ShippingDetails']);//only fo master
            $oPreparedItem = MLDatabase::factory('meinpaket_prepare')
                ->set('products_id', $aVariant['VariationId']);

            $variationConfiguration = $oPreparedItem->get('VariationConfiguration');

            if (empty($variationConfiguration)) {
                continue;
            }

            $aProduct['MPVariationConfiguration'] = array(
                'MpIdentifier' => $variationConfiguration,
                'CustomIdentifier' => ''
            );

            // @todo: Check reduced price
            // if the reduced price is available here it has been enabled in the module configuration and should be used.
            if (isset($aVariant['PriceReduced'])) {
                $aVariant['Price'] = $aVariant['PriceReduced'];
            }
        }

        if (empty($aProduct['Variations'])) {
            MLErrorLog::gi()->addError(
                $aProduct['VariationId'], $aProduct['SKU'], MLI18n::gi()->get('meinpaket_error_checkin_variation_config_cannot_calc_variations'), $aProduct);
            return false;
        }

        return true;
    }
	
    protected function additionalErrorManagement($aResponse) {        
        if (isset($aResponse["CHECKINERRORS"]) && !empty($aResponse['CHECKINERRORS'])) {
            foreach ($aResponse['CHECKINERRORS'] as $aError) {
                MLMessage::gi()->addWarn($aError['ErrorMessage'], '', false);
                $aMessage = array();
                if(isset($aError['ErrorMessage'])){
                    $aMessage['ERRORMESSAGE'] = $aError['ErrorMessage'];
                }
                if(isset($aError['AdditionalData'])){
                    $aMessage['ERRORDATA'] = $aError['AdditionalData'];
                }
                MLErrorLog::gi()->addApiError($aMessage);
            }
        }
    }

    protected function hasAttributeMatching()
    {
        return true;
    }

    protected function setVariationDefinition($categoryAttributes)
    {
        $newVariationDefinitions = array();
        foreach ($categoryAttributes as $categoryAttributeKey => $categoryAttributeValue) {
            $newVariationDefinitions[] = array(
                'MPName' => $categoryAttributeKey,
                'MPValue'=> $categoryAttributeValue,
            );
        }

        return $newVariationDefinitions;
    }

	/**
     * meinpaket can add item with quantity <= 0
     * @return boolean
     */
    protected function checkQuantity() {
        return true;
    }
}
