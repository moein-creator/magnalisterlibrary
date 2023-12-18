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
 * (c) 2010 - 2021 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

MLFilesystem::gi()->loadClass('Otto_Helper_Model_Table_Otto_ConfigData');

class ML_Shopware6Otto_Helper_Model_Table_Otto_ConfigData extends ML_Otto_Helper_Model_Table_Otto_ConfigData {
    /**
     * Custom fields(FreeTextField) has been removed from the attributes as a option to select because  Shopware 6 does not support "Orders Custom fields(Order FreeTextField)" specifically
     *
     * @param $aField
     * @param $carrierType
     * @return mixed
     * @throws MLAbstract_Exception
     */
    public function carrierSelect($matchingElementValue, $aField, $carrierType = 'standard') {
        $optGroups = array();
        $marketplaceCarriers = array();
        $matchingElement = array();
        // First element is pure text that explains that nothing is selected so it should not be added
        $aFirstElement = array('' => MLI18n::gi()->get('ML_AMAZON_LABEL_APPLY_PLEASE_SELECT'));

        // Marketplace carriers
        $apiMarketplaceCarriers = MLModule::gi()->getOttoShippingSettings($carrierType);
        foreach ($apiMarketplaceCarriers as $key => $marketplaceCarrier) {
            $marketplaceCarriers[$key] = $marketplaceCarrier;
        }
        if (!empty($apiMarketplaceCarriers)) {
            $marketplaceCarriers['optGroupClass'] = 'marketplaceCarriers';
            $optGroups += array(MLI18n::gi()->get('otto_config_carrier_option_group_marketplace_carrier') => $marketplaceCarriers);
        }

        // matching option key value "returnCarrierMatching" must be the same as "matching" value on form fields
        $matchingElement[$matchingElementValue] = MLI18n::gi()->get('otto_config_carrier_option_matching_option');
        $matchingElement['optGroupClass'] = 'matching';
        $optGroups += array(MLI18n::gi()->get('otto_config_carrier_option_group_additional_option') => $matchingElement);

        $aField['values'] = $aFirstElement + $optGroups;
        return $aField;
    }

}
