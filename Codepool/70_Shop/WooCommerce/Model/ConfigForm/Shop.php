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
 * (c) 2010 - 2023 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

MLFilesystem::gi()->loadClass('Shop_Model_ConfigForm_Shop_Abstract');

class ML_WooCommerce_Model_ConfigForm_Shop extends ML_Shop_Model_ConfigForm_Shop_Abstract {
    protected $sPlatformName = '';

    public function getDescriptionValues() {
        global $wpdb;
        $aLangs = array();
        $shop = MLDatabase::getDbInstance()->fetchRow("
            SELECT * 
            FROM {$wpdb->options}
            WHERE option_name = 'woocommerce_default_country'
        ");
        $aLangs['1'] = $shop['option_value'];

        return $aLangs;
    }

    public function getShopValues() {
        global $wpdb;
        $aShops = array();
        $shop = MLDatabase::getDbInstance()->fetchRow("
            SELECT * 
            FROM {$wpdb->options}
            WHERE option_name = 'blogname'
        ");
        $aShops['1'] = $shop['option_value'];

        return $aShops;
    }

    public function getCustomerGroupValues($blNotLoggedIn = false) {
        $aGroupsName = array();
        $aGroupsName['1'] = 'Customer';
        if ($blNotLoggedIn) {
            $aGroupsName['-'] = MLI18n::gi()->WooCommerce_Orderimport_CustomerGroup_Notloggedin;
        }

        return $aGroupsName;
    }

    public function getOrderStatusValues() {
        $aOrderStatesName = wc_get_order_statuses();

        return $aOrderStatesName;
    }

    public function getPaymentStatusValues() {
        $aOrderStatesName = wc_get_is_paid_statuses();

        return $aOrderStatesName;
    }

    public function getEan() {
        return $this->getListOfArticleFields(array(
            'name',
            'content',
            '_weight',
            '_width',
            '_height',
            '_length',
        ));
    }

    public function getUpc() {
        return $this->getListOfArticleFields(array(
            'name',
            'content',
            '_weight',
            '_width',
            '_height',
            '_length',
        ));
    }

    public function getMarketingDescription() {
        return $this->getListOfArticleFields(array(
            '_weight',
            '_width',
            '_height',
            '_length',
        ));
    }

    static $aListOfArticleFieldsCache = array();

    protected function getListOfArticleFields($aExcludedFields = array()) {
        $sCacheKey = md5(json_encode($aExcludedFields));
        if (!isset(self::$aListOfArticleFieldsCache[$sCacheKey])) {
            $wpFields = array();
            foreach ($this->getVariationAttributes() as $variation_attribute) {
                $wpFields['pa_'.$variation_attribute['slug']] = $variation_attribute['name'].' (attribute)';
            }
            foreach ($this->getDefaultFieldsAttributes($aExcludedFields) as $key => $item) {
                $wpFields[$key] = $item['name'].' (product field)';
            }
            $customFields = $this->getCustomerFieldsFromDatabase();
            foreach ($customFields as $customField) {
                $wpFields['cf_'.$customField['meta_key']] = $customField['meta_key'].' (custom field)';
            }
            $aFields = array_merge(
                array('' => MLI18n::gi()->get('ConfigFormPleaseSelect')),
                $wpFields
            );
            //remove some field that could not be used
            unset($aFields['filterGroupId']);
            self::$aListOfArticleFieldsCache[$sCacheKey] = $aFields;
        }
        return self::$aListOfArticleFieldsCache[$sCacheKey];
    }

    public function getManufacturerPartNumber() {
        return $this->getListOfArticleFields(array(
            '_weight',
            '_width',
            '_height',
            '_length',
        ));
    }

    public function getManufacturer() {
        return $this->getListOfArticleFields(array(
            'name',
            '_sku',
            'content',
            '_weight',
            '_width',
            '_height',
            '_length',
        ));
    }

    public function getBrand() {
        return $this->getListOfArticleFields(array(
            'name',
            '_sku',
            'content',
            '_weight',
            '_width',
            '_height',
            '_length',
        ));
    }
    public function getShopSystemAttributeList() {
        return $this->getListOfArticleFields(array(
            'name',
            '_sku',
            'content',
            '_weight',
            '_width',
            '_height',
            '_length',
        ));
    }
    public function getCurrency() {
        $aCurrency = array(
            1 => get_woocommerce_currency(),
        );

        return $aCurrency;
    }

    /**
     * Gets the list of product attributes prefixed with attribute type.
     *
     * @return array Collection of prefixed attributes
     */
    public function getPrefixedAttributeList($getProperties = false) {
        $aAttributes = $this->getPossibleVariationGroupNames();

        $aAttributes['name'] = 'Title';
        $aAttributes['_sku'] = 'Item number';
        $aAttributes['content'] = 'Short description';
        $aAttributes['_weight'] = 'Weight';
        $aAttributes['_width'] = 'Width';
        $aAttributes['_height'] = 'Height';
        $aAttributes['_length'] = 'Length';


        return $aAttributes;
    }
    //
    //    /**
    //     * Gets the list of product attributes that have options (displayed as dropdown or multiselect fields).
    //     *
    //     * @return array Collection of attributes with options
    //     */
    //    public function getAttributeListWithOptions() {
    //        $aAttributes = $this->getPossibleVariationGroupNames();
    //
    //        return $aAttributes;
    //    }

    /**
     * Gets the list of product attribute values.
     * If $iLangId is set, use translation for attribute options' labels.
     *
     * @return array Collection of attribute values
     */
    public function getPrefixedAttributeOptions($sAttributeCode, $iLangId = null) {
        return $this->getAttributeOptions($sAttributeCode, $iLangId);
    }

    /**
     * Gets the list of product attributes that have options (displayed as dropdown or multiselect fields).
     * If $iLangId is set, use translation for attribute options' labels.
     * Results are form wp_term_taxonomy table
     *
     * @return array Collection of attributes with options
     */
    public function getAttributeOptions($sAttributeCode, $iLangId = null) {
        $aAttributeCode = explode('_', $sAttributeCode, 2);
        $attributes = array();
        global $wpdb;

        if ($aAttributeCode[0] === 'pa' || $aAttributeCode[0] === 'pp') {
            $configuratorOptions = $wpdb->get_results(
                $wpdb->prepare("SELECT t.term_id AS id, t.name AS name
                    FROM {$wpdb->prefix}woocommerce_attribute_taxonomies AS wat
                    INNER JOIN $wpdb->term_taxonomy AS tt ON CONCAT('pa_', wat.attribute_name) = tt.taxonomy
                    INNER JOIN $wpdb->terms AS t ON tt.term_id = t.term_id WHERE wat.attribute_name = %s", $aAttributeCode[1]
                ), ARRAY_A
            );
            foreach ($configuratorOptions as &$configuratorOption) {
                $attributes[$configuratorOption['id']] = $configuratorOption['name'];
            }
        }

        return $attributes;
    }

    public function getTaxClasses() {
        global $wpdb;
        $query = 'SELECT tax_rate_id as value , tax_rate as label FROM '.$wpdb->prefix.'woocommerce_tax_rates';
        $aTaxes = $wpdb->get_results($query, ARRAY_A);

        return $aTaxes;
    }

    public function getPaymentMethodValues() {
        $paymentGateways = new WC_Payment_Gateways();
        $aPayments = $paymentGateways->get_available_payment_gateways();
        $aResult = array();
        if (MLModule::gi()->isOrderShippingMethodAvailable()) {
            $aResult = ['matching' => MLI18n::gi()->get('AutomaticAllocation')];
        } else {
            $aResult = [MLModule::gi()->getMarketPlaceName(false) => MLModule::gi()->getMarketPlaceName(false)];
        }
        foreach ($aPayments as $aPaymentKey => $aPaymentValue) {
            $aResult[$aPaymentKey] = $aPaymentValue->title;
        }

        return $aResult;
    }

    /**
     * To show list of shipping method in configuration to be assign to a new order
     * we should include also Zone, because here we need exact id of shipping method for specific zone
     * @return array
     * @throws MLAbstract_Exception
     */
    public function getShippingMethodValues() {
        if (MLModule::gi()->isOrderShippingMethodAvailable()) {
            $aResult = ['matching' => MLI18n::gi()->get('AutomaticAllocation')];
        } else {
            $aResult = [MLModule::gi()->getMarketPlaceName(false) => MLModule::gi()->getMarketPlaceName(false)];
        }

        global $wpdb;
        $query = 'SELECT option_id as id, zone_name as zone, method_id as method, option_value as value from 
        '.$wpdb->prefix.'woocommerce_shipping_zone_methods as szm
        LEFT JOIN '.$wpdb->prefix.'woocommerce_shipping_zone_locations as szl
        on szm.zone_id = szl.zone_id
        LEFT JOIN '.$wpdb->prefix.'options as wo
        on wo.option_name = concat("woocommerce_flat_rate_", szm.instance_id, "_settings")
        or wo.option_name = concat("woocommerce_local_pickup_", szm.instance_id, "_settings")
        or wo.option_name = concat("woocommerce_free_shipping_", szm.instance_id, "_settings")
        LEFT JOIN '.$wpdb->prefix.'woocommerce_shipping_zones sz
        on szl.zone_id = sz.zone_id
        WHERE szm.is_enabled = 1 AND option_id IS NOT NULL
        GROUP BY option_id ORDER by zone_name ASC';

        $shippingMethods = MLDatabase::getDbInstance()->fetchArray($query);
        foreach ($shippingMethods as &$shippingMethod) {
            $shippingMethod['value'] = unserialize($shippingMethod['value']);
            $aResult[(string)$shippingMethod['id']] = (($shippingMethod['zone']) ? $shippingMethod['zone'].' - ' : '').$shippingMethod['value']['title'];
        }
        unset($shippingMethod);
        return $aResult;
    }

    public function getPossibleVariationGroupNames() {
        global $wpdb;
        $aAttributes = array('' => MLI18n::gi()->get('ConfigFormPleaseSelect'));
        $query = 'SELECT attribute_label AS name, attribute_name AS slug, attribute_id AS id FROM '.$wpdb->prefix.'woocommerce_attribute_taxonomies';
        $aConfiguratorGroups = MLDatabase::getDbInstance()->fetchArray($query);
        foreach ($aConfiguratorGroups as &$aConfiguratorGroup) {
            $aAttributes['pa_'.$aConfiguratorGroup['slug']] = $aConfiguratorGroup['name'];
        }

        return $aAttributes;
    }

    public function getGroupedAttributesForMatching($oSelectedProducts = null) {

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

        // Product properties
        $aShopVariationAttributes = $this->getVariationAttributes(true);
        if (!empty($aShopVariationAttributes)) {
            $aShopVariationAttributes['optGroupClass'] = 'properties';
            $aShopAttributes += array(MLI18n::gi()->get('PropertiesOptGroup') => $aShopVariationAttributes);
        }

        // Product default fields
        $aShopDefaultFieldsAttributes = $this->getDefaultFieldsAttributes();
        if (!empty($aShopDefaultFieldsAttributes)) {
            $aShopDefaultFieldsAttributes['optGroupClass'] = 'default';
            $aShopAttributes += array(MLI18n::gi()->get('ProductDefaultFieldsOptGroup') => $aShopDefaultFieldsAttributes);
        }

        // Product custom fields
        $aCustomFieldsAttributes = $this->getCustomFieldsAttributes();
        if (!empty($aCustomFieldsAttributes)) {
            $aCustomFieldsAttributes['optGroupClass'] = 'custom_fields';
            $aShopAttributes += array(MLI18n::gi()->get('ProductCustomFieldsOptGroup') => $aCustomFieldsAttributes);
        }

        return $aFirstElement + $aShopAttributes;

    }

    /**
     * Returns flat attribute if attribute code is sent and if not it returns all shop attributes for attribute matching
     * @param string $attributeCode
     * @param ML_WooCommerce_Model_Product|null $product If present attribute value will be set from given product
     * @return array|mixed
     * @throws MLAbstract_Exception
     */
    public function getFlatShopAttributesForMatching($attributeCode = null, $product = null) {

        $result = $this->getVariationAttributes() + $this->getVariationAttributes(true) + $this->getDefaultFieldsAttributes() + $this->getCustomFieldsAttributes();
        //+ $this->getPropertiesAttributes() + $this->getFreeTextFieldsAttributes();

        if (!empty($attributeCode) && !empty($result[$attributeCode])) {
            $result = $result[$attributeCode];
            if (!empty($product)) {
                $result['value'] = $product->getAttributeValue($attributeCode);
            }
        }

        return $result;
    }

    /**
     * Returns variation attributes for attribute matching
     *
     * @return array
     */
    public function getVariationAttributes($productProperties = false) {

        global $wpdb;
        $aShopVariationAttributes = array();

        $query = 'SELECT attribute_name AS slug, attribute_label AS name, attribute_id AS id FROM '.$wpdb->prefix.'woocommerce_attribute_taxonomies';
        $aConfiguratorGroups = MLDatabase::getDbInstance()->fetchArray($query);

        foreach ($aConfiguratorGroups as $aConfiguratorGroup) {
            $prefix = $productProperties ? 'pp_' : 'pa_';
            $aShopVariationAttributes[$prefix.$aConfiguratorGroup['slug']] = array(
                'name' => $aConfiguratorGroup['name'],
                'slug' => $aConfiguratorGroup['slug'],
                'type' => 'select',
            );
        }

        return $aShopVariationAttributes;
    }

    /**
     * Returns default product fields for attribute matching
     *
     * @return array
     * @throws MLAbstract_Exception
     */
    private function getDefaultFieldsAttributes($aExcludedFields = array()) {
        $aShopDefaultFieldsAttributes = array(
            'name'              => array(
                'name' => MLI18n::gi()->get('WooCommerce_Product_Attributes_Title'),
                'type' => 'text',
            ),
            '_sku'              => array(
                'name' => MLI18n::gi()->get('WooCommerce_Product_Attributes_ItemNumber'),
                'type' => 'text',
            ),
            'content'           => array(
                'name' => MLI18n::gi()->get('WooCommerce_Product_Attributes_Description'),
                'type' => 'text',
            ),
            'short_description' => array(
                'name' => MLI18n::gi()->get('WooCommerce_Product_Attributes_Short_Description'),
                'type' => 'text',
            ),
            '_weight'           => array(
                'name' => MLI18n::gi()->get('Product_DefaultAttribute_WeightValue'),
                'type' => 'text',
            ),
            'weight_unit_name'  => array(
                'name' => MLI18n::gi()->get('Product_DefaultAttribute_WeightWithUnit'),
                'type' => 'text',
            ),
            'weight_unit'       => array(
                'name' => MLI18n::gi()->get('Product_DefaultAttribute_WeightUnit'),
                'type' => 'text',
            ),
            '_width'            => array(
                'name' => MLI18n::gi()->get('Product_DefaultAttribute_WidthValue'),
                'type' => 'text',
            ),
            'width_unit_name'   => array(
                'name' => MLI18n::gi()->get('Product_DefaultAttribute_WidthWithUnit'),
                'type' => 'text',
            ),
            'width_unit'        => array(
                'name' => MLI18n::gi()->get('Product_DefaultAttribute_WidthUnit'),
                'type' => 'text',
            ),
            '_height'           => array(
                'name' => MLI18n::gi()->get('Product_DefaultAttribute_HeightValue'),
                'type' => 'text',
            ),
            'height_unit_name'  => array(
                'name' => MLI18n::gi()->get('Product_DefaultAttribute_HeightWithUnit'),
                'type' => 'text',
            ),
            'height_unit'    => array(
                'name' => MLI18n::gi()->get('Product_DefaultAttribute_HeightUnit'),
                'type' => 'text',
            ),
            '_length'    => array(
                'name' => MLI18n::gi()->get('Product_DefaultAttribute_LengthValue'),
                'type' => 'text',
            ),
            'length_unit_name' => array(
                'name' => MLI18n::gi()->get('Product_DefaultAttribute_LengthWithUnit'),
                'type' => 'text',
            ),
            'length_unit'     => array(
                'name' => MLI18n::gi()->get('Product_DefaultAttribute_LengthUnit'),
                'type' => 'text',
            ),
            'te_product_tag'    => array(
                'name' => MLI18n::gi()->get('WooCommerce_Product_Attributes_Keywords'),
                'type' => 'text',
            ),
        );
        foreach ($aExcludedFields as $sKey) {
            unset($aShopDefaultFieldsAttributes[$sKey]);
        }

        /**
         * Additional fields attributes from 'Germanized for WooCommerce' plugin
         */
        if (is_plugin_active('woocommerce-germanized/woocommerce-germanized.php')) {
            $aGermanizedPluginFieldsAttributes = array(
                '_ts_gtin'            => array(
                    'name' => 'GTIN - [Germanized Plugin]',
                    'type' => 'text',
                ),
                '_ts_mpn'             => array(
                    'name' => 'MPN - [Germanized Plugin]',
                    'type' => 'text',
                ),
                '_unit_product'       => array(
                    'name' => 'Produkteinheiten - [Germanized Plugin]',
                    'type' => 'text',
                ),
                '_unit_price_regular' => array(
                    'name' => 'Regulärer Grundpreis (€) - [Germanized Plugin]',
                    'type' => 'text',
                ),
            );
            $aShopDefaultFieldsAttributes = array_merge($aShopDefaultFieldsAttributes, $aGermanizedPluginFieldsAttributes);
        }
        /**
         * Additional fields attributes from 'German Market' plugin
         */
        if (is_plugin_active('woocommerce-german-market/WooCommerce-German-Market.php')) {
            $aGermanMarketPluginFieldsAttributes = array(
                '_gm_gtin' => array(
                    'name' => 'GTIN - [GermanMarket Plugin]',
                    'type' => 'text',
                ),
            );
            $aShopDefaultFieldsAttributes = array_merge($aShopDefaultFieldsAttributes, $aGermanMarketPluginFieldsAttributes);
        }
        //https://wordpress.org/plugins/woo-add-gtin/
        if (is_plugin_active('woo-add-gtin/woocommerce-gtin.php')) {
            $aGermanMarketPluginFieldsAttributes = array(
                'hwp_product_gtin' => array(
                    'name' => 'GTIN - [WooCommerce UPC, EAN, and ISBN]',
                    'type' => 'text',
                ),
            );
            $aShopDefaultFieldsAttributes = array_merge($aShopDefaultFieldsAttributes, $aGermanMarketPluginFieldsAttributes);
        }

        //https://wordpress.org/plugins/product-gtin-ean-upc-isbn-for-woocommerce/
        if (is_plugin_active('product-gtin-ean-upc-isbn-for-woocommerce/product-gtin-ean-upc-isbn-for-woocommerce.php')) {
            $aGermanMarketPluginFieldsAttributes = array(
                '_wpm_gtin_code' => array(
                    'name' => 'Product GTIN (EAN, UPC, ISBN) for WooCommerce',
                    'type' => 'text',
                ),
            );
            $aShopDefaultFieldsAttributes = array_merge($aShopDefaultFieldsAttributes, $aGermanMarketPluginFieldsAttributes);
        }

        //https://wordpress.org/plugins/ean-for-woocommerce
        if (is_plugin_active('ean-for-woocommerce/ean-for-woocommerce.php')) {
            $aEANForWooCommercePluginFieldsAttributes = array(
                '_alg_ean' => array(
                    'name' => 'EAN for WooCommerce - Plugin',
                    'type' => 'text',
                ),
            );
            $aShopDefaultFieldsAttributes = array_merge($aShopDefaultFieldsAttributes, $aEANForWooCommercePluginFieldsAttributes);
        }

        return $aShopDefaultFieldsAttributes;
    }

    static $aCustomFieldsAttributesCache = null;

    /**
     * Get post custom fields from postmeta table
     *
     * @return array
     */
    private function getCustomFieldsAttributes() {
        if (self::$aCustomFieldsAttributesCache === null) {
            $customFieldsAttributes = array();
            $customFields = $this->getCustomerFieldsFromDatabase();
            foreach ($customFields as $customField) {
                $customFieldsAttributes['cf_'.$customField['meta_key']] = array(
                    'name' => $customField['meta_key'],
                    'type' => 'text'
                );
            }

            self::$aCustomFieldsAttributesCache = $customFieldsAttributes;
        }
        return self::$aCustomFieldsAttributesCache;
    }

    static $aCustomerFieldsFromDatabaseCache = null;

    private function getCustomerFieldsFromDatabase() {
        if (self::$aCustomerFieldsFromDatabaseCache === null) {
            global $wpdb;
            self::$aCustomerFieldsFromDatabaseCache = MLDatabase::getDbInstance()->fetchArray(
                "SELECT pm.meta_key 
                FROM `$wpdb->postmeta` pm 
                INNER JOIN `$wpdb->postmeta` pm2 ON pm2.`meta_key` = '_sku' AND pm2.`post_id` = pm.`post_id`
                WHERE pm.`meta_key` = '_ts_'
             GROUP BY pm.`meta_key`;"
            );
        }
        return self::$aCustomerFieldsFromDatabaseCache;
    }

    public function getShippingTime() {
        return $this->getListOfArticleFields();
    }

    public function manipulateForm(&$aForm) {
        parent::manipulateForm($aForm);
        $sController = MLRequest::gi()->data('controller');
        if (
            strpos($sController, '_config_vcs') !== false &&//prove tab
            isset($aForm['field']['amazonvcs.invoice']['values']['germanmarket']) &&//prove key
            !is_plugin_active('woocommerce-german-market/WooCommerce-German-Market.php')//prove plugin
        ) {
            unset($aForm['field']['amazonvcs.invoice']['values']['germanmarket']);
        }

        $aForm = $this->addTrackingKeyField($aForm);
    }

    static $aTrackingKeyFieldFormCache = null;

    /**
     * Adds tracking key configuration to order import tab
     *
     * @param $aForm
     * @return array
     */
    private function addTrackingKeyField($aForm) {
        $sController = MLRequest::gi()->data('controller');
        if (strpos($sController, '_config_order') !== false) {
            if (self::$aTrackingKeyFieldFormCache !== null) {
                $aForm['orderstatus']['fields']['orederstatus.trackingkey'] = self::$aTrackingKeyFieldFormCache;
                return $aForm;
            }

            global $wpdb;
            $keys = MLDatabase::getDbInstance()->fetchArray("
                SELECT DISTINCT meta_key
                  FROM $wpdb->postmeta
                 WHERE meta_key NOT BETWEEN '_' AND '_z'
                HAVING meta_key NOT LIKE '\_%'
              ORDER BY meta_key
            ", true);

            $aDisabledItems = array();
            $blWooAdvancedShipmentTrackingActive = is_plugin_active('woo-advanced-shipment-tracking/woocommerce-advanced-shipment-tracking.php');
            if (!$blWooAdvancedShipmentTrackingActive) {
                $aDisabledItems[] = '_wc_shipment_tracking_items';
            }
            $blGermanizedActive = is_plugin_active('woocommerce-germanized/woocommerce-germanized.php');
            if (!$blGermanizedActive) {
                $aDisabledItems[] = 'germanized';
            }
            if ($this->getCurrentTrackingNumberConfig() === '_wc_shipment_tracking_items') {
                $plugin_data = get_plugin_data(ABSPATH.'wp-content/plugins/woo-advanced-shipment-tracking/woocommerce-advanced-shipment-tracking.php');
            } else if ($this->getCurrentTrackingNumberConfig() === 'germanized') {
                $plugin_data = get_plugin_data(ABSPATH.'wp-content/plugins/woocommerce-germanized/woocommerce-germanized.php');
            }
            if (isset($plugin_data)) {
                MLI18n::gi()->set(MLModule::gi()->getMarketPlaceName().'_config_carrier_option_matching_option', MLI18n::gi()->get('marketplace_config_carrier_option_matching_option_plugin', ['pluginname' => $plugin_data['Name']]), true);
                MLI18n::gi()->set(MLModule::gi()->getMarketPlaceName().'_config_carrier_matching_title_shop_carrier', MLI18n::gi()->get('marketplace_config_carrier_matching_title_shop_carrier_plugin', ['pluginname' => $plugin_data['Name']]), true);
                MLI18n::gi()->set('amazon_config_carrier_option_matching_option_carrier', MLI18n::gi()->get('amazon_config_carrier_option_matching_option_carrier_plugin', ['pluginname' => $plugin_data['Name']]), true);
                MLI18n::gi()->set('amazon_config_carrier_option_matching_option_shipmethod', MLI18n::gi()->get('amazon_config_carrier_option_matching_option_shipmethod_plugin', ['pluginname' => $plugin_data['Name']]), true);
            }
            $values = array(
                '' => MLI18n::gi()->get('attributes_matching_option_please_select'),
                MLI18n::gi()->get('woocommerce_config_trackingkey_option_group_customfields')      => array_combine($keys, $keys),
                MLI18n::gi()->get('woocommerce_config_trackingkey_option_group_additional_option') => array(
                    'orderFreetextField'          => MLI18n::gi()->{'woocommerce_config_trackingkey_option_orderfreetextfield_option'},
                    '_wc_shipment_tracking_items' => MLI18n::gi()->{'woocommerce_config_trackingkey_option_plugin_ast'},
                    'germanized'                  => MLI18n::gi()->{'woocommerce_config_trackingkey_option_plugin_germanized'}
                )
            );

            $aForm['orderstatus']['fields']['orederstatus.trackingkey'] = array(
                'name'          => 'orederstatus.trackingkey',
                'type'          => 'select',
                'expert'        => false,
                'values'        => $values,
                'fieldposition' => array('after' => 'orderstatus.sync'),
                'i18n'          => MLI18n::gi()->get('orderimport_trackingkey'),
                'disableditems' => $aDisabledItems,
                'cssclasses'    => ['ml-woocommerce-tracking-number-matching']
            );
            self::$aTrackingKeyFieldFormCache = $aForm['orderstatus']['fields']['orederstatus.trackingkey'];
        }

        return $aForm;
    }

    public function manipulateFormAfterNormalize(&$aForm) {
        $sController = MLRequest::gi()->data('controller');
        if (
            strpos($sController, '_config_invoice') !== false &&//prove tab
            isset($aForm['invoice']['fields']['uploadInvoiceOption']['values']['germanmarket']) &&//prove key
            !is_plugin_active('woocommerce-german-market/WooCommerce-German-Market.php')//prove plugin
        ) {
            unset($aForm['invoice']['fields']['uploadInvoiceOption']['values']['germanmarket']);
        }
        //MLMessage::gi()->addDebug(__LINE__.':'.microtime(true), $aForm  );
        try {
            parent::manipulateFormAfterNormalize($aForm);
            MLModule::gi();
            if (isset($aForm['importactive']['fields']['orderimport.paymentmethod'])) {
                $aForm['importactive']['fields']['orderimport.paymentmethod']['type'] = 'select';
                $aForm['importactive']['fields']['orderimport.paymentmethod']['expert'] = false;
                $aForm['importactive']['fields']['orderimport.paymentmethod']['values'] = $this->getPaymentMethodValues();
                $aForm['importactive']['fields']['orderimport.paymentmethod']['i18n']['help'] = MLModule::gi()->isOrderPaymentMethodAvailable() ? MLI18n::gi()->WooCommerce_Configuration_PaymentMethod_Available_Info : MLI18n::gi()->WooCommerce_Configuration_PaymentMethod_NotAvailable_Info;
                unset($aForm['importactive']['fields']['orderimport.paymentmethod']['subfields']);
            } else if (isset($aForm['orderimport']['fields']['orderimport.paymentmethod'])) {
                $aForm['orderimport']['fields']['orderimport.paymentmethod']['type'] = 'select';
                $aForm['orderimport']['fields']['orderimport.paymentmethod']['expert'] = false;
                $aForm['orderimport']['fields']['orderimport.paymentmethod']['values'] = $this->getPaymentMethodValues();
                $aForm['orderimport']['fields']['orderimport.paymentmethod']['i18n']['help'] = MLModule::gi()->isOrderPaymentMethodAvailable() ? MLI18n::gi()->WooCommerce_Configuration_PaymentMethod_Available_Info : MLI18n::gi()->WooCommerce_Configuration_PaymentMethod_NotAvailable_Info;
                unset($aForm['orderimport']['fields']['orderimport.paymentmethod']['subfields']);
            }
            if (isset($aForm['importactive']['fields']['orderimport.shippingmethod'])) {
                $aForm['importactive']['fields']['orderimport.shippingmethod']['type'] = 'select';
                $aForm['importactive']['fields']['orderimport.shippingmethod']['expert'] = false;
                $aForm['importactive']['fields']['orderimport.shippingmethod']['values'] = $this->getShippingmethodValues();
                $aForm['importactive']['fields']['orderimport.shippingmethod']['i18n']['help'] = MLModule::gi()->isOrderShippingMethodAvailable() ? MLI18n::gi()->get('WooCommerce_Configuration_ShippingMethod_Available_Info') : MLI18n::gi()->get('WooCommerce_Configuration_ShippingMethod_NotAvailable_Info');
                unset($aForm['importactive']['fields']['orderimport.shippingmethod']['subfields']);
            } else if (isset($aForm['orderimport']['fields']['orderimport.shippingmethod'])) {
                $aForm['orderimport']['fields']['orderimport.shippingmethod']['type'] = 'select';
                $aForm['orderimport']['fields']['orderimport.shippingmethod']['expert'] = false;
                $aForm['orderimport']['fields']['orderimport.shippingmethod']['values'] = $this->getShippingmethodValues();
                $aForm['orderimport']['fields']['orderimport.shippingmethod']['i18n']['help'] = MLModule::gi()->isOrderShippingMethodAvailable() ? MLI18n::gi()->get('WooCommerce_Configuration_ShippingMethod_Available_Info') : MLI18n::gi()->get('WooCommerce_Configuration_ShippingMethod_NotAvailable_Info');
                unset($aForm['orderimport']['fields']['orderimport.shippingmethod']['subfields']);
            }

            $sController = MLRequest::gi()->data('controller');

            if (strpos($sController, '_config_price') !== false) {
                foreach ($aForm as $sKey => $aGroups) {
                    if (strpos($sKey, 'price') !== false) {
                        MLDatabase::factory('config')->set('mpid', MLModule::gi()->getMarketPlaceId())->set('mkey', 'price.group')->set('value', 0)->save();
                        foreach ($aForm[$sKey]['fields'] as $sInnerKey => $aField) {
                            if (isset($aForm[$sKey]['fields'][$sInnerKey]) && strpos($aForm[$sKey]['fields'][$sInnerKey]['realname'], 'priceoptions') !== false) {
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

        } catch (Exception $ex) {

        }
    }

    /**
     * To match shipping method is hard to include zone, so we just match name of shipping method
     * @return array
     */
    public function getShopShippingModuleValues() {
        global $wpdb;
        $aResult = array();

        $aResult = $this->getShippingMethodFromASTPlugin($wpdb, $aResult);
        if (!empty($aResult)) {
            return $aResult;
        }
        $blGermanizedActive = is_plugin_active('woocommerce-germanized/woocommerce-germanized.php');
        if ($this->getCurrentTrackingNumberConfig() === 'germanized' && $blGermanizedActive) {
            foreach (wc_gzd_get_shipping_provider_select() as $provider => $title) {
                $aResult[$title] = $title;
            }
        }
        if (!empty($aResult)) {
            return $aResult;
        }
        $query = 'SELECT option_id as id, method_id as method, option_value as value from 
        '.$wpdb->prefix.'woocommerce_shipping_zone_methods as szm
        LEFT JOIN '.$wpdb->prefix.'options as wo
        on wo.option_name = concat("woocommerce_flat_rate_", szm.instance_id, "_settings")
        or wo.option_name = concat("woocommerce_local_pickup_", szm.instance_id, "_settings")
        or wo.option_name = concat("woocommerce_free_shipping_", szm.instance_id, "_settings")
        WHERE szm.is_enabled = 1 AND option_id IS NOT NULL
        GROUP BY option_id ';
        $shippingMethods = MLDatabase::getDbInstance()->fetchArray($query);
        foreach ($shippingMethods as &$shippingMethod) {
            $shippingMethod['value'] = unserialize($shippingMethod['value']);
            $aResult[$shippingMethod['value']['title']] = $shippingMethod['value']['title'];
        }
        unset($shippingMethod);

        return $aResult;
    }

    protected function getCurrentTrackingNumberConfig() {
        $aConfig = MLRequest::gi()->data('field');
        if (isset($aConfig['orederstatus.trackingkey'])) {
            return $aConfig['orederstatus.trackingkey'];
        } else {
            return MLModule::gi()->getConfig('orederstatus.trackingkey');
        }

    }

    /**
     * @param $wpdb
     * @param array $aResult
     * @return array
     */
    protected function getShippingMethodFromASTPlugin($wpdb, $aResult) {
        $blWooAdvancedShipmentTrackingActive = is_plugin_active('woo-advanced-shipment-tracking/woocommerce-advanced-shipment-tracking.php');
        if ($this->getCurrentTrackingNumberConfig() === '_wc_shipment_tracking_items' && $blWooAdvancedShipmentTrackingActive) {
            $sTable = $wpdb->prefix.'woo_shippment_provider';
            $shippment_countries = $wpdb->get_results("SELECT `shipping_country` FROM `$sTable` WHERE `display_in_order` = 1 GROUP BY `shipping_country`");
            foreach ($shippment_countries as $s_c) {
                $country = $s_c->shipping_country;
                $shippment_providers_by_country = $wpdb->get_results($wpdb->prepare("SELECT * FROM `$sTable` WHERE shipping_country = %s AND display_in_order = 1", $country));
                foreach ($shippment_providers_by_country as $providers) {

                    $aResult[$providers->provider_name] = $providers->provider_name;
                }
            }
        }
        return $aResult;
    }

    public function getDefaultCancelStatus() {
        return 'wc-cancelled';
    }
}
