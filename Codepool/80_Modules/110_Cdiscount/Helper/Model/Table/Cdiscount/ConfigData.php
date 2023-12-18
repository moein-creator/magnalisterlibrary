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
MLFilesystem::gi()->loadClass('Form_Helper_Model_Table_ConfigData_Abstract');

class ML_Cdiscount_Helper_Model_Table_Cdiscount_ConfigData extends ML_Form_Helper_Model_Table_ConfigData_Abstract {

    public function siteIdField(&$aField) {
        $aSites = $this->callApi(array('ACTION' => 'GetSites'), 60);
        $aField['type'] = 'select';
        $aField['values'] = array();
        $aField['values'][] = MLI18n::gi()->get('ML_AMAZON_LABEL_APPLY_PLEASE_SELECT');
        foreach ($aSites as $aSite) {
            $aField['values'][$aSite['id']] = $aSite['name'];
        }
    }

    public function getSiteDetails() {
        return $this->callApi(array('ACTION' => 'GetSiteDetails'), 60);
    }

    public function primaryCategoryField(&$aField) {
        $aRequest = MLRequest::gi()->data();
        if (MLModul::gi()->getMarketPlaceName().':'.MLModul::gi()->getMarketPlaceId().'_prepare_variations' === $aRequest['controller']) {
            $aField['values'] = MLDatabase::factory(MLModul::gi()->getMarketPlaceName() . '_variantmatching')->getTopPrimaryCategories();
        } else {
            $aField['values'] = MLDatabase::factory( MLModul::gi()->getMarketPlaceName() . '_prepare')->getTopPrimaryCategories();
        }
    }

    public function checkin_currencyField(&$aField) {
        $currencies = MLModul::gi()->getConfig('site.currencies');
        $aField['values'][''] = ML_AMAZON_LABEL_APPLY_PLEASE_SELECT;
        foreach ($currencies as $code => $symbol) {
            $aField['values'][$code] = $code;
        }
    }

    public function checkin_listingTypeField(&$aField) {
        $aListingTypes = MLModul::gi()->getConfig('site.listing_types');
        $aField['values'][''] = ML_AMAZON_LABEL_APPLY_PLEASE_SELECT;
        foreach ($aListingTypes as $code => $name) {
            $aField['values'][$code] = $name;
        }
    }

    public function langField (&$aField) {
        $aField['values'] = MLFormHelper::getShopInstance()->getDescriptionValues();
    }

    public function imagePathField (&$aField) {
        if (isset($aField['value']) === false || empty($aField['value'])) {
            $aField['value'] = MLHttp::gi()->getShopImageUrl();
        }
    }

    public function marketingDescriptionField (&$aField) {
        $aField['values'] = MLFormHelper::getShopInstance()->getMarketingDescription();
    }

    public function standardDescriptionField (&$aField) {
        $aField['values'] = MLFormHelper::getShopInstance()->getMarketingDescription();
    }

    public function itemConditionField(&$aField) {
        $aField['values'] = $this->callApi(array('ACTION' => 'GetOfferCondition'), 60);
    }

    public function shippingProfileNameField(&$aField) {
        $aField['values'] = $this->callApi(array('ACTION' => 'GetDeliveryModes'), 60);
        MLMessage::gi()->addDebug($aField['values']);
    }

    public function preparationTimeField(&$aField) {
        $aField['value'] = isset($aField['value']) ? str_replace(',', '.', trim($aField['value'])) : 0;
        if ((string)((int)$aField['value']) != $aField['value'] || (int)$aField['value'] < 1 || (int)$aField['value'] > 10) {
            $this->addError($aField, MLI18n::gi()->get('cdiscount_config_checkin_badshippingtime'));
        } else {
            $aField['value'] = number_format($aField['value'], 0);
        }
    }

    public function shippingfeeField(&$aField) {
        if (!empty($aField['value']) && is_array($aField['value'])) {
            foreach ($aField['value'] as &$shippingFee) {
                $shippingFee = str_replace(',', '.', trim($shippingFee));
                if (empty($shippingFee)) {
                    $shippingFee = 0;
                }
                if ((string)((float)$shippingFee) != $shippingFee) {
                    $this->addError($aField, MLI18n::gi()->get('cdiscount_config_checkin_badshippingcost'));
                } else {
                    $shippingFee = number_format($shippingFee, 2);
                }
            }
        }
    }

    public function shippingfeeadditionalField(&$aField) {
        if (!empty($aField['value']) && is_array($aField['value'])) {
            foreach ($aField['value'] as &$shippingFee) {
                $shippingFee = str_replace(',', '.', trim($shippingFee));
                if (empty($shippingFee)) {
                    $shippingFee = 0;
                }
                if ((string)((float)$shippingFee) != $shippingFee) {
                    $this->addError($aField, MLI18n::gi()->get('cdiscount_config_checkin_badshippingcost'));
                } else {
                    $shippingFee = number_format($shippingFee, 2);
                }
            }
        }
    }

    public function itemCountryField(&$aField) {
        $aField['values'] = $this->callApi(array('ACTION' => 'GetDeliveryCountries'), 60);
    }

    //	public function orderstatus_carrierField(&$aField) {
    //        $orderStatusData = $this->callApi(array('ACTION' => 'GetOrderStatusData'), 60);;
    //        $aField['values'] = $orderStatusData['CarrierCodes'];
    //    }

    public function orderstatus_cancelreasonField(&$aField) {
        $orderStatusData = $this->callApi(array('ACTION' => 'GetOrderStatusData'), 60);;
        $aField['values'] = $orderStatusData['Reasons'];
    }

    public function orderstatus_cancelledField(&$aField) {
        $this->orderstatus_canceledField($aField);
    }

    public function mail_sendField(&$aField) {
        $aField['values'] = array(
            1 => MLI18n::gi()->get('ML_BUTTON_LABEL_YES'),
            0 => MLI18n::gi()->get('ML_BUTTON_LABEL_NO'));
    }

    public function mail_copyField(&$aField) {
        $aField['values'] = array(
            1 => MLI18n::gi()->get('ML_BUTTON_LABEL_YES'),
            0 => MLI18n::gi()->get('ML_BUTTON_LABEL_NO'));
    }

    public function shippingTimeMatchingField (&$aField) {
        //		$shippingTimeShop = MLFormHelper::getShopInstance();
        if (empty($shippingTimeShop) === false && isset($shippingTimeShop)) {
            $aField['type'] = 'matching';
            $shippingTimes = $this->callApi(array('ACTION' => 'GetDeliveryTimes'), 60);
            foreach ($shippingTimes as $shippingTime) {
                $aField['valuessrc'][$shippingTime] = array(
                    'i18n' => $shippingTime,
                    'required' => true,
                );
            }

            $aField['valuesdst'] = $shippingTimeShop;
        } else {
            $aField['type'] = 'information';
            $aField['value'] = MLI18n::gi()->cdiscount_config_checkin_shippingmatching;
        }
    }

    public function orderstatus_autoacceptanceField(&$aField) {
        if( MLModul::gi()->getConfig('orderstatus.autoacceptance') === null ){
            $aField['value'] = true;
        }else{
            $aField['value'] = MLModul::gi()->getConfig('orderstatus.autoacceptance');
        }
    }

    public function orderimport_shippingmethodField (&$aField) {
        if(method_exists(MLFormHelper::getShopInstance(), 'getShippingMethodValues')){
            $aField['values'] = MLFormHelper::getShopInstance()->getShippingMethodValues();
        }else{
            $aField['values'] = MLI18n::gi()->get('cdiscount_configform_orderimport_shipping_values');
        }
    }

    public function orderimport_paymentmethodField (&$aField) {
        $aField['values'] = MLI18n::gi()->get('cdiscount_configform_orderimport_payment_values');
    }

    public function orderstatus_carrier_defaultField (&$aField) {
        $aField['values'] = MLModul::gi()->getCarriers();
    }

    // TODO: Check with Tim

    protected $shipmethodOptions = array(
        'shopFreeTextField',
        'matchShopShippingOptions',
        'databaseMatching',
        'orderFreeTextField',
        'freeText',
    );

    protected function selectWithMatchingOptionsFromTypeValueGenerator($options, $aField, $matchType) {
        // list of available options
        if (empty($options)) {
            $options = array(
                'marketplaceCarrier',
                'shopFreeTextField',
                'matchShopShippingOptions',
                'databaseMatching',
                'orderFreeTextField',
                'freeText',
            );
        }

        $optGroups = array();
        $marketplaceCarriers = array();
        $matchingElement = array();
        // First element is pure text that explains that nothing is selected so it should not be added
        $aFirstElement = array('' => MLI18n::gi()->get('ML_AMAZON_LABEL_APPLY_PLEASE_SELECT'));

        // Marketplace carriers
        if (in_array('marketplaceCarrier', $options)) {
            $apiMarketplaceCarriers = $this->callApi(array('ACTION' => 'GetDeliveryModes'), 60);
            foreach ($apiMarketplaceCarriers as $key => $marketplaceCarrier) {
                if ($marketplaceCarrier === 'Other') {
                    continue;
                }
                $marketplaceCarriers[$marketplaceCarrier] = $marketplaceCarrier;
            }

            if (!empty($apiMarketplaceCarriers)) {
                $marketplaceCarriers['optGroupClass'] = 'marketplaceCarriers';
                $optGroups += array(MLI18n::gi()->get('cdiscount_config_carrier_option_group_marketplace_carrier') => $marketplaceCarriers);
            }
        }

        // Free text fields - additional fields
        if (in_array('shopFreeTextField', $options) && method_exists(MLFormHelper::getShopInstance(), 'getOrderFreeTextFieldsAttributes')) {
            $aShopFreeTextFieldsAttributes = MLFormHelper::getShopInstance()->getOrderFreeTextFieldsAttributes();
            if (!empty($aShopFreeTextFieldsAttributes)) {
                $aShopFreeTextFieldsAttributes['optGroupClass'] = 'freetextfield';
                $optGroups += array(MLI18n::gi()->get('cdiscount_config_carrier_option_group_shopfreetextfield_option_'.$matchType).':' => $aShopFreeTextFieldsAttributes);
            }
        }

        // matching option key value "returnCarrierMatching" must be the same as "matching" value on form fields
        if (in_array('matchShopShippingOptions', $options)) {
            $matchingElement['matchShopShippingOptions'] = MLI18n::gi()->get('cdiscount_config_carrier_option_matching_option_'.$matchType);
        }

        // Check for Order FreeText Field Option
        if (in_array('orderFreeTextField', $options)) {
            $matchingElement['orderFreetextField'] = MLI18n::gi()->get('cdiscount_config_carrier_option_orderfreetextfield_option');
        }

        // Check for FreeText Option
        if (in_array('freeText', $options)) {
            $matchingElement['freetext'] = MLI18n::gi()->get('cdiscount_config_carrier_option_freetext_option_'.$matchType);
        }
        if (!empty($matchingElement)) {
            $matchingElement['optGroupClass'] = 'matching';
            $optGroups += array(MLI18n::gi()->get('cdiscount_config_carrier_option_group_additional_option') => $matchingElement);
        }

        $aField['values'] = $aFirstElement + $optGroups;
        return $aField;
    }

    public function orderstatus_shipmethod_selectField(&$field) {
        $field = $this->selectWithMatchingOptionsFromTypeValueGenerator($this->shipmethodOptions, $field, 'shipmethod');
    }

    /**
     * Populates two drop downs for select
     *
     * @param $field
     * @param bool $carrierMatching
     * @return mixed
     * @throws MLAbstract_Exception
     */
    public function carrierOrShipMethodMatching($field, $carrierMatching = true) {
        $field['i18n']['matching'] = array(
            'titlesrc' => MLI18n::gi()->get('cdiscount_config_carrier_matching_title_marketplace_shipmethod'),
            'titledst' => MLI18n::gi()->get('cdiscount_config_carrier_matching_title_shop_carrier')
        );
        $field['valuesdst'] = array('' => MLI18n::gi()->get('ML_AMAZON_LABEL_APPLY_PLEASE_SELECT'));
        $field['valuessrc'] = array('' => MLI18n::gi()->get('ML_AMAZON_LABEL_APPLY_PLEASE_SELECT'));

        $shopCarriers = MLFormHelper::getShopInstance()->getShopShippingModuleValues();
        if (!empty($shopCarriers)) {
            $field['valuesdst'] = $field['valuesdst'] + $shopCarriers;
        }

        if ($carrierMatching) {
            $field['i18n']['matching']['titlesrc'] = MLI18n::gi()->get('cdiscount_config_carrier_matching_title_marketplace_carrier');
            $aMarketplaceCarriers = array();
            foreach ($this->callApi(array('ACTION' => 'GetDeliveryModes'), 60) as $key => $marketplaceCarrier) {
                $aMarketplaceCarriers[$marketplaceCarrier] = $marketplaceCarrier;
            }
            $aMarketplaceCarriers['Other'] = MLI18n::gi()->cdiscount_config_carrier_other;
            if (!empty($aMarketplaceCarriers)) {
                $field['valuessrc'] = $field['valuessrc'] + $aMarketplaceCarriers;
            }
        } else {
            $aMarketplaceDeliveryModes = array();
            foreach ($this->callApi(array('ACTION' => 'GetDeliveryModes'), 60) as $key => $marketplaceDeliveryMode) {
                $aMarketplaceDeliveryModes[$marketplaceDeliveryMode] = $marketplaceDeliveryMode;
            }
            $aMarketplaceDeliveryModes['Other'] = MLI18n::gi()->cdiscount_config_carrier_other;
            if (!empty($aMarketplaceDeliveryModes)) {
                $field['valuessrc'] = $field['valuessrc'] + $aMarketplaceDeliveryModes;
            }
        }

        return $field;
    }

    public function orderstatus_shipmethod_matchingField(&$field) {
        $field = $this->carrierOrShipMethodMatching($field, false);
    }
}
