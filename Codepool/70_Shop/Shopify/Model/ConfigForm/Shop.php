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
 * (c) 2010 - 2024 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

MLFilesystem::gi()->loadClass('Shop_Model_ConfigForm_Shop_Abstract');

use Shopify\API\Application\Application;
use Shopify\API\Application\Request\Products\CountProducts\CountProductsParams;

class ML_Shopify_Model_ConfigForm_Shop extends ML_Shop_Model_ConfigForm_Shop_Abstract {

    /**
     * Returns description values.
     *
     * @return array
     */
    public function getDescriptionValues() {
        $aDescriptionValues = [];
        $aShop = MLHelper::gi('model_shop')->getShopConfigurationAsArray();

        $sShopName = $aShop['name'];
        $sLocale = $aShop['primary_locale'];

        if ($sLocale == 'en') {
            $sLocale = 'English';
        } else if ($sLocale == 'de') {
            $sLocale = 'Deutsch';
        }

        $aDescriptionValues[1] = $sShopName . ' - ' . $sLocale;

        return $aDescriptionValues;
    }

    /**
     * Returns shop values.
     *
     * @return array
     */
    public function getShopValues() {
        $aShop = MLHelper::gi('model_shop')->getShopConfigurationAsArray();
        $sShopName = $aShop['name'];

        return [
            1 => $sShopName
        ];
    }

    /**
     * Returns customer group value.
     *
     * @param bool $blNotLoggedIn
     *
     * @return array
     */
    public function getCustomerGroupValues($blNotLoggedIn = false) {
        $aCustomerSavedSearches = $this->getCustomerGroupsFromShopifyAsArray();
        $aFormattedCustomerGroupValues = array();
        $countCustomers = count($aCustomerSavedSearches);
        for ($i = 0; $i < $countCustomers; $i++) {
            $aFormattedCustomerGroupValues[$i + 1] = $aCustomerSavedSearches[$i]['name'];
        }

        //return $aFormattedCustomerGroupValues;
        return array(0 => MLI18n::gi()->get('CustomerGroupSettingNotSupported'));
    }

    /**
     * Returns order status values.
     *
     * @return array
     */
    public function getOrderStatusValues() {
        //@todo
        //returns all possible status for orders
        return [
            'open' => MLI18n::gi()->get('OrderStatus_Open'),
            'fulfilled' => MLI18n::gi()->get('OrderStatus_Fulfilled'),
            'cancelled' => MLI18n::gi()->get('OrderStatus_Cancelled'),
        ];
    }

    /**
     * @return string[]
     */
    public function getPaymentStatusValues() {
        return [
            '' => MLI18n::gi()->get('FinancialStatus_Empty'),
            'pending' => MLI18n::gi()->get('FinancialStatus_Pending'),
            'authorized' => MLI18n::gi()->get('FinancialStatus_Authorized'),
            //'partially_paid' => MLI18n::gi()->get('FinancialStatus_PartiallyPaid'), // Partially options are generally not supported by magnalister
            'paid' => MLI18n::gi()->get('FinancialStatus_Paid'),
            //'partially_refunded'   => MLI18n::gi()->get('FinancialStatus_PartiallyRefunded'), // Partially options are generally not supported by magnalister
            //'refunded'   => MLI18n::gi()->get('FinancialStatus_Refunded'), // will not be supported by magnalister
            // 'voided'   => MLI18n::gi()->get('FinancialStatus_Voided'), // will not be supported by magnalister
        ];
    }

    // // returns all possible statuses for shipping
    // public function getShippingMethodValues() {
    //    return array();
    // }

    /**
     * Returns EAN.
     *
     * @return array
     */
    public function getEan() {
        return array(
            'barcode' => MLI18n::gi()->get('Barcode')
        );
    }

    /**
     * Returns UPS.
     *
     * @return array
     */
    public function getUpc() {
        return array(
            'barcode' => MLI18n::gi()->get('Barcode')
        );
    }

    /**
     * Returns marketing description.
     *
     * @return array
     */
    public function getMarketingDescription() {
        return $this->getProductFields();
    }

    /**
     * Returns manufacturer.
     *
     * @return array
     */
    public function getManufacturer() {
        return array(
            'vendor' => MLI18n::gi()->get('Vendor'),
        );
    }

    /**
     * Returns manufacturer part number.
     *
     * @return array
     */
    public function getManufacturerPartNumber() {
        return array(
            '' => MLI18n::gi()->get('ConfigFormPleaseSelect'),
            'sku' => MLI18n::gi()->get('SKU'),
            'barcode' => MLI18n::gi()->get('Barcode'),
        );
    }

    /**
     * Returns brand.
     *
     * @return array
     */
    public function getBrand() {
        return $this->getListOfArticleFields();
    }

    public function getShopSystemAttributeList() {
        return $this->getListOfArticleFields();
    }

    /**
     * Returns product fields.
     *
     * @return array
     */
    protected function getProductFields() {
        return $this->getGroupedAttributesForMatching();
    }

    /**
     * Returns attribute list.
     *
     * @return array
     */
    public function getAttributeList() {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getPrefixedAttributeList($getProperties = false) {
        $aAttributes = $this->getPossibleVariationGroupNames() + $this->getPossibleMetaFields();
        $aAttributes['p_title'] = 'Title';
        $aAttributes['pd_sku'] = 'sku';
        $aAttributes['p_body_html'] = 'Description';
        $aAttributes['pd_barcode'] = 'Barcode';
        $aAttributes['p_vendor'] = 'Vendor';
        $aAttributes['pd_weight'] = 'Weight';
        $aAttributes['pd_product_type'] = 'Product type';
        $aAttributes['pd_tags'] = 'Tags';

        return $aAttributes;
    }
    //
    //    /**
    //     * @inheritDoc
    //     */
    //    public function getAttributeListWithOptions() {
    //        return $this->getPossibleVariationGroupNames();
    //    }

    /**
     * @inheritDoc
     */
    public function getAttributeOptions($sAttributeCode, $iLangId = null) {
        $attributes = array();
        $aAttributeCode = explode('_', $sAttributeCode, 2);

        if ($aAttributeCode[0] === 'c') {

            $query = sprintf(' SELECT * FROM `magnalister_shopify_attributes` WHERE `AttributeName`="%s"', $aAttributeCode[1]);

            $attributeValues = MLDatabase::getDbInstance()->fetchArray($query);

            foreach ($attributeValues as $attributeValue) {
                $aAttributeValues = json_decode($attributeValue['AttributeValues']);
                foreach ($aAttributeValues as $value) {
                    $attributes[$aAttributeCode[1] . '_' . $value] = $value;
                }
            }

            return $attributes;
        }
        if ($aAttributeCode[0] === 'm') {
            $aMetaFields = MLShopifyAlias::getMetaFieldModel()
                ->set('Type', 'select')
                ->getList()->getQueryObject()
                ->select('ShopifyMetaFieldValue', true)
                ->join([MLShopifyAlias::getObjectMetaFieldRelationModel()->getTableName(), 'omr', 'omr.MetaFieldId = ' . MLShopifyAlias::getMetaFieldModel()->getTableName() . '.MetaFieldId'], MLDatabase::factorySelectClass()::JOIN_TYPE_INNER)
                ->where(['omr.MetaFieldId', '=', $aAttributeCode[1]])
                ->getResult();
            if (is_array($aMetaFields)) {
                foreach ($aMetaFields as $aMetaField) {
                    $aAttributeValues = json_decode($aMetaField['ShopifyMetaFieldValue'], true);
                    if (is_array($aAttributeValues)) {
                        foreach ($aAttributeValues as $value) {
                            $attributes[$aAttributeCode[1] . '_' . $value] = $value;
                        }
                    }
                }
            }
            return $attributes;
        }

        if (isset($aAttributeCode[1]) && $aAttributeCode[1] === 'vendor') {
            $query = 'SELECT distinct `ShopifyVendor` FROM ' . MLShopifyAlias::getProductModel()->getTableName();

            $attributeValues = MLDatabase::getDbInstance()->fetchArray($query);

            foreach ($attributeValues as $key => $value) {
                if ($value['ShopifyVendor'] !== '') {
                    $attributes[$aAttributeCode[1] . '_' . $key] = $value['ShopifyVendor'];
                }
            }

            return $attributes;
        }

        return $attributes;
    }

    /**
     * Returns prefixed attribute options.
     * @param string $sAttributeCode
     * @param integer $iLangId
     *
     * @return array
     * @todo check
     */
    public function getPrefixedAttributeOptions($sAttributeCode, $iLangId = null) {
        return $this->getAttributeOptions($sAttributeCode, $iLangId);
    }

    /**
     * Returns currency.
     *
     * @return array
     */
    public function getCurrency() {
        return [];
    }

    /**
     * Returns tax classes.
     *
     * @return array
     */
    public function getTaxClasses() {
        $shopId = MLHelper::gi('model_shop')->getShopId();
        $application = new Application($shopId);
        $token = MLHelper::gi('container')->getCustomerModel()->getAccessToken($shopId);
        $aListOfCountries = $application->getCountryRequest($token)->getListOfCountries()->getBodyAsArray();

        if (!is_array($aListOfCountries) || empty($aListOfCountries['countries'])) {

            return [['value' => 1, 'label' => 0]];
        }

        $br = 1;
        $taxClasses = array();
        foreach ($aListOfCountries['countries'] as $aListOfCountry) {
            $taxCollect['value'] = $br;
            $taxCollect['label'] = ($aListOfCountry['tax'] * 100);
            $br++;
            $taxClasses[] = $taxCollect;
        }

        return $taxClasses;
    }

    /**
     * Returns possible variations group names.
     *
     * @return array
     */
    public function getPossibleVariationGroupNames() {

        ML::gi()->instance('Shopify_Model_Table_ShopifyAttribute');

        $aAttributes = array('' => MLI18n::gi()->get('ConfigFormPleaseSelect'));

        $queryAttributeName = 'SELECT Distinct AttributeName FROM magnalister_shopify_attributes';
        $aAttributeNames = MLDatabase::getDbInstance()->fetchArray($queryAttributeName);

        foreach ($aAttributeNames as $aAttributeName) {
            $aAttributes[MLShopifyAlias::getProductModel()::ATTRIBUTE_PREFIX_VARIANT . $aAttributeName['AttributeName']] = $aAttributeName['AttributeName'];

        }
        return $aAttributes;
    }

    /**
     * Returns possible meta field.
     *
     * @return array
     */
    public function getPossibleMetaFields() {
        $aAttributes = [];
        foreach (MLShopifyAlias::getMetaFieldModel()->getList()->getList() as $oMetaField) {
            /** @var $oMetaField  ML_Shopify_Model_Table_ShopifyMetaField */
            if ($oMetaField->get('ShopifyMetaFieldType') !== 'rich_text_field') {
                $aAttributes['m_' . $oMetaField->get('MetaFieldId')] = $oMetaField->getNameOfMetaField();
            }
        }
        return $aAttributes;
    }

    /**
     * @return array
     * @throws MLAbstract_Exception
     */
    protected function getListOfArticleFields() {
        return $this->getPrefixedAttributeList();
    }


    /**
     * @param array $aForm
     */
    public function manipulateFormAfterNormalize(&$aForm) {
        parent::manipulateFormAfterNormalize($aForm);
        try {
            $sController = MLRequest::gi()->data('controller');

            $aForm = $this->manipulateFormAfterNormalizeRemoveCustomerGroup($sController, $aForm);
            $aForm = $this->manipulateFormAfterNormalizeRemoveLanguage($sController, $aForm);
            $aForm = $this->manipulateFormAfterNormalizeEbayProductTemplate($sController, $aForm);

        } catch (\Exception $ex) {
            MLMessage::gi()->addDebug($ex);
        }
    }

    /**
     * Return customer group
     *
     * @return array
     */
    public function getCustomerGroupsFromShopifyAsArray() {
        //        $shopId                 = MLHelper::gi('model_shop')->getShopId();
        //        $application            = new Application($shopId);
        //        $token                  = MLHelper::gi('container')->getCustomerModel()->getAccessToken($shopId);
        //        $response               = $application->getCustomerSavedSearchRequest($token)->getListOfCustomerSavedSearches()->getBodyAsArray();
        //        $aCustomerSavedSearches = $response['customer_saved_searches'];

        //        return $aCustomerSavedSearches;
        return array();
    }

    /**
     * @return array
     */
    public function getShippingTime() {
        return $this->getGroupedAttributesForMatching();
    }



    protected function manipulateFormAfterNormalizeRemoveCustomerGroup($sController, array $aForm): array {
        if (strpos($sController, '_config_price') !== false) {
            foreach ($aForm as $sKey => $aGroups) {
                if (strpos($sKey, 'price') !== false) {
                    MLDatabase::factory('config')->set('mpid', MLModule::gi()->getMarketPlaceId())->set('mkey', 'price.group')->set('value', 0)->save();
                    foreach ($aForm[$sKey]['fields'] as $sInnerKey => $aField) {

                        if (isset($aForm[$sKey]['fields'][$sInnerKey]) && strpos($aForm[$sKey]['fields'][$sInnerKey]['realname'], 'priceoptions') !== false) {
                            //                                echo print_m($aForm[$sKey]['fields'][$sInnerKey]);
                            MLDatabase::factory('config')->set('mpid', MLModule::gi()->getMarketPlaceId())->set('mkey', $aForm[$sKey]['fields'][$sInnerKey]['subfields']['group']['realname'])->set('value', 0)->save();
                            unset($aForm[$sKey]['fields'][$sInnerKey]);
                        }
                    }
                }
            }
        }
        if (strpos($sController, '_config_order') !== false) {
            foreach ($aForm as $sKey => $aGroups) {
                foreach ($aForm[$sKey]['fields'] as $sInnerKey => $aField) {
                    if (isset($aForm[$sKey]['fields'][$sInnerKey]) && isset($aForm[$sKey]['fields'][$sInnerKey]['realname']) && strpos($aForm[$sKey]['fields'][$sInnerKey]['realname'], 'customergroup') !== false) {
                        MLDatabase::factory('config')->set('mpid', MLModule::gi()->getMarketPlaceId())->set('mkey', $aForm[$sKey]['fields'][$sInnerKey]['realname'])->set('value', 0)->save();
                        unset($aForm[$sKey]['fields'][$sInnerKey]);
                    }
                }
            }

        }
        return $aForm;
    }

    protected function manipulateFormAfterNormalizeRemoveLanguage($sController, array $aForm): array {
        if (strpos($sController, '_config_prepare') !== false) {
            foreach ($aForm as $sKey => $aGroups) {
                foreach ($aForm[$sKey]['fields'] as $sInnerKey => $aField) {
                    if ($aField['realname'] === 'lang') {
                        unset($aForm[$sKey]['fields'][$sInnerKey]);
                    }
                }
            }
        }
        return $aForm;
    }

    protected function manipulateFormAfterNormalizeEbayProductTemplate($sController, array $aForm): array {
        if (MLModule::gi()->getMarketPlaceName() === 'ebay' && strpos($sController, '_config_producttemplate') !== false) {
            $aForm['product']['fields']['template.tabs']['subfields']['template.content']['i18n']['hint'] =
                $this->addMetaFieldsToDescriptionHint($aForm['product']['fields']['template.tabs']['subfields']['template.content']['i18n']['hint']);

        }
        return $aForm;
    }

    public function addMetaFieldsToDescriptionHint(string $aField): string {
        $sHint = str_replace('</dl></dl>', '', $aField);

        $sMetaFieldInfo = MLI18n::gi()->ebay_prepare_apply_form_field_description_hint_metafield;

        $i = 5;
        foreach (MLShopifyAlias::getMetaFieldModel()->getList()->getList() as $oMetaField) {
            if ($oMetaField->get('ShopifyMetaFieldType') !== 'rich_text_field') {
                if ($i === 0) {
                    break;
                }
                /** @var $oMetaField  ML_Shopify_Model_Table_ShopifyMetaField */
                $aMetaFieldsKeys = $oMetaField->getMetaFieldExtraInformationAllPossibleKeys();
                $sMetaFieldInfo .= '<dd>#' . current($aMetaFieldsKeys) . '#</dd>';
                $i--;
            }
        }
        return $sHint . $sMetaFieldInfo . '</dl></dl>';
    }

    /**
     * @return mixed
     */
    private function countArticles() {
        $shopId = MLHelper::gi('model_shop')->getShopId();
        $application = new Application($shopId);
        $countProductsParams = new CountProductsParams();
        $response = $application->getProductRequest()->countProducts($countProductsParams);
        return $response->getBodyAsArray()['count'];
    }

    /**
     * Returns default product fields for attribute matching
     *
     * @return array
     */
    private function getDefaultFieldsAttributes() {
        $aShopDefaultFieldsAttributes = array(
            'p_title' => array(
                'name' => MLI18n::gi()->get('Title'),
                'type' => 'text',
            ),
            'pd_sku' => array(
                'name' => MLI18n::gi()->get('SKU'),
                'type' => 'text',
            ),
            'p_body_html' => array(
                'name' => MLI18n::gi()->get('Description'),
                'type' => 'text',
            ),
            'pd_barcode' => array(
                'name' => MLI18n::gi()->get('Barcode'),
                'type' => 'text',
            ),
            'pd_weight' => array(
                'name' => MLI18n::gi()->get('Weight'),
                'type' => 'text',
            ),
            'p_vendor' => array(
                'name' => MLI18n::gi()->get('Vendor'),
                'type' => 'select',
            ),
            'pd_product_type' => array(
                'name' => MLI18n::gi()->get('ProductType'),
                'type' => 'text',
            ),
            'pd_tags' => array(
                'name' => 'Tags',
                'type' => 'text',
            ),
        );

        return $aShopDefaultFieldsAttributes;
    }

    /**
     * Returns attribute list with options.
     *
     * @return array
     * @throws MLAbstract_Exception
     */
    public function getVariationAttributes() {

        ML::gi()->instance('Shopify_Model_Table_ShopifyAttribute');
        $aShopVariationAttributes = array();

        $aAttributes = array('' => MLI18n::gi()->get('ConfigFormPleaseSelect'));

        $queryAttributeName = 'SELECT Distinct (BINARY `AttributeName`) AS AttributeName FROM `magnalister_shopify_attributes`';
        $aAttributeNames = MLDatabase::getDbInstance()->fetchArray($queryAttributeName);

        foreach ($aAttributeNames as $aAttributeName) {

            $aShopVariationAttributes[MLShopifyAlias::getProductModel()::ATTRIBUTE_PREFIX_VARIANT . $aAttributeName['AttributeName']] = array(
                'name' => $aAttributeName['AttributeName'],
                'type' => 'select',
            );

        }
        return $aShopVariationAttributes;
    }

    /**
     * Returns attribute list with options.
     *
     * @return array
     */
    public function getMetaFieldAttributes(): array {
        $aAttributes = [];
        foreach (MLShopifyAlias::getMetaFieldModel()->getList()->getList() as $oMetaField) {
            if ($oMetaField->get('ShopifyMetaFieldType') !== 'rich_text_field') {
                $aAttributes['m_' . $oMetaField->get('MetaFieldId')] = array(
                    'name' => $oMetaField->getNameOfMetaField(),
                    'type' => $oMetaField->get('Type'),
                );
            }
        }
        return $aAttributes;
    }

    /**
     * @return array|array[]
     * @throws MLAbstract_Exception
     */
    public function getGroupedAttributesForMatching($oSelectedProducts = null) {
        //return $this->getPossibleVariationGroupNames();

        $aShopAttributes = array();

        // First element is pure text that explains that nothing is selected so it should not be added
        // nor in Properties or Variations, it is spliced and used just for forming the final array.
        $aFirstElement = array('' => MLI18n::gi()->get('ConfigFormPleaseSelect'));

        // Variation attributes
        $aShopVariationAttributes = $this->getVariationAttributes();
        if (!empty($aShopVariationAttributes)) {
            $aShopVariationAttributes['optGroupClass'] = 'variation';
            $aShopAttributes += array(MLI18n::gi()->get('VariationsOptGroup') => $aShopVariationAttributes);
        }

        // Product default fields
        $aShopDefaultFieldsAttributes = $this->getDefaultFieldsAttributes();
        if (!empty($aShopDefaultFieldsAttributes)) {
            $aShopDefaultFieldsAttributes['optGroupClass'] = 'default';
            $aShopAttributes += array(MLI18n::gi()->get('ProductDefaultFieldsOptGroup') => $aShopDefaultFieldsAttributes);
        }

        // Product meta field
        $aMetaFieldAttributes = $this->getMetaFieldAttributes();
        if (!empty($aMetaFieldAttributes)) {
            $aMetaFieldAttributes['optGroupClass'] = 'property';
            $aShopAttributes += array(MLI18n::gi()->get('MetaFieldOptGroup') => $aMetaFieldAttributes);
        }

        return $aFirstElement + $aShopAttributes;
    }

    /**
     * Returns flat attribute if attribute code is sent and if not it returns all shop attributes for attribute matching
     * @param string $attributeCode
     * @param ML_Shopify_Model_Product|null $product If present attribute value will be set from given product
     * @return array|mixed
     */
    public function getFlatShopAttributesForMatching($attributeCode = null, $product = null) {

        $result = $this->getVariationAttributes() + $this->getDefaultFieldsAttributes() + $this->getMetaFieldAttributes();

        if (!empty($attributeCode) && !empty($result[$attributeCode])) {
            $result = $result[$attributeCode];
            if (!empty($product)) {
                $result['value'] = $product->getAttributeValue($attributeCode);
            }
        }

        return $result;
    }

    /**
     * To see this list, open an order in Shopify and click on fulfillment button, check shipping carrier drop down
     * keys of carriers here are not important, what we get from Shopify for each order is the name of a carrier, not the key of the carrier
     * @return array
     */
    public function getShopShippingModuleValues() {
        $aListOfShopifyCarriers =
            [
                '4PX',
                '90 Minutos',
                'Aeronet',
                'AGS',
                'Amazon Logistics UK',
                'Amazon Logistics US',
                'Amm Spedition',
                'An Post',
                'ANDREANI',
                'Anjun Logistics',
                'APC',
                'APG eCommerce Solutions Ltd.',
                'Apple Express',
                'Arame New Zealand',
                'Aruba Post',
                'Asendia USA',
                'ASL Canada',
                'Australia Post',
                'AxleHire',
                'Better Trucks',
                'Bonshaw',
                'Border Express',
                'BPost',
                'BPost International',
                'Canada Post',
                'Canpar Canada',
                'Canpar USA',
                'Cargo Expreso GT',
                'Cargo Expreso SV',
                'Caribou',
                'CDEK',
                'CDL Last Mile',
                'CEVA logistics',
                'China EMS',
                'China Post',
                'Chronopost',
                'Chit Chats',
                'Chronopost',
                'Chukou1',
                'Colissimo',
                'Comingle',
                'Coordinadora',
                'Correios',
                'Correos',
                'CTT',
                'CTT Express',
                'Cyprus Post',
                'Deliver it',
                'Delnext',
                'Deprisa',
                'Deutsche Post',
                'DHL eCommerce',
                'DHL PAKET',
                'DHL Commerce Asia',
                'DHL Express',
                'DHL Global Mail Asia',
                'DHL Sweden',
                'Dimerco Express Group',
                'DoorDash',
                'DPD',
                'DPD Belgium',
                'DPD Germany',
                'DPD Hungary',
                'DPD Local',
                'DPD UK',
                'DTD Express',
                'DX',
                'Eagle',
                'Emons',
                'Estes',
                'Evri',
                'Fastway Australia',
                'Fastway South Africa',
                'FedEx',
                'First Global Logistics',
                'First Line',
                'Fleet Optics',
                'FSC',
                'Fulfilla',
                'GLS',
                'GLS DE',
                'GP Logistic Service',
                'Guangdong Weisuyi Information Technology (WSE)',
                'Heppner Internationale Spedition GmbH &amp; Co.',
                'HR Parcel',
                'Iceland Post',
                'IDEX',
                'Interparcel',
                'Israel Post',
                'Japan Post',
                'La Poste',
                'La Poste Burkina Faso',
                'Landmark Global',
                'Landmark Global Reference',
                'Lasership',
                'Latvia Post',
                'Libya Post',
                'Lietuvos Paštas',
                'Logisters',
                'Lone Star Overnight',
                'M3 Logistics',
                'Maldives Post',
                'Mauritius Post',
                'Meteor Space',
                'Mondial Relay',
                'moovin',
                'NCS',
                'New Zealand Post',
                'NinjaVan',
                'North Russia Supply Chain (Shenzhen) Co.',
                'NOX Germany',
                'OnTrac',
                'OPT-NC',
                'Packeta',
                'Pago Logistics',
                'Parcel Force',
                'Passport',
                'Ping An Da Tengfei Express',
                'Pitney Bowes',
                'Portal PostNord',
                'Poste Italiane',
                'PostNL',
                'PostNL International',
                'PostNL International 35',
                'PostNord DK',
                'PostNord NO',
                'PostNord SE',
                'Purolator',
                'Qxpress',
                'Qyun Express',
                'Royal Mail',
                'Royal Shipments',
                'Sagawa',
                'Sendle',
                'Servientrega Ecuador',
                'SF Express',
                'SFC Fulfillment',
                'SHREE NANDAN COURIER',
                'Singapore Post',
                'SmartCat',
                'Southwest Air Cargo',
                'StarTrack',
                'Spee-Dee Delivery Service',
                'Sprinter',
                'Step Forward Freight',
                'Surpost',
                'Swiship DE',
                'Swiss Post',
                'Tele Post',
                'TForce Final Mile',
                'Tinghao',
                'TNT',
                'TNT Reference',
                'TNT UK',
                'TNT UK Reference',
                'Toll IPEC',
                'United Delivery Service',
                'UPS',
                'USPS',
                'UPS Canada',
                'USPS',
                'Venipak',
                'We Pick Up',
                'We Post',
                'Whistl',
                'Wizmo',
                'WMYC',
                'Xpedigo',
                'XPO Logistics',
                'XYY Logistics',
                'Yamato',
                'YDH',
                'YiFan Express',
                'YunExpress',
                'YYZ Logistics',
                'Yamato (JA)',
                'Sagawa (JA)',
                'Japan Post (JA)'
            ];
        $aCarriers = array();
        foreach ($aListOfShopifyCarriers as $sCarrier) {
            $aCarriers[$sCarrier] = $sCarrier;
        }
        $aCarriers['Other'] = MLI18n::gi()->get('Shopify_Carrier_Other');
        return $aCarriers;
    }

    public function getShippingMethodValues() {
        $aShippingMethods = array(
            'textfield' => array(
                'title' => '{#i18n:form_orderimport_paymentandshipping_values_textfield_title#}',
                'textoption' => true
            ),
        );
        $aCarriers = $this->getShopShippingModuleValues();
        unset($aCarriers['Other']);
        foreach ($aCarriers as $sCarrier) {
            $aShippingMethods[$sCarrier] = array(
                'title' => $sCarrier,
            );
        }
        return $aShippingMethods;
    }

    /**
     * @param $aVariationOption array ('name'=> ..., 'value'=> ... ,'code'=> ... ,'valueid'=> ...)
     * @return mixed
     */
    public function getVariationValueID($aVariationOption) {
        return $aVariationOption['name'] . '_' . $aVariationOption['valueid'];
    }

    public function getDefaultCancelStatus() {
        return 'cancelled';
    }

}
