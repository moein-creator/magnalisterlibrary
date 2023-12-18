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
MLFilesystem::gi()->loadClass('Shop_Model_ConfigForm_Shop_Abstract');

class ML_Magento_Model_ConfigForm_Shop extends ML_Shop_Model_ConfigForm_Shop_Abstract {

    protected $aAttributeList = null;
    protected $aAttributeOptions = null;

    /**
     * Gets list of languages to be selected for description field.
     * Pulls list of all stores and pulles language from each since Magento does not have separated list of languages.
     * 
     * @return array Collection of languages for Description field in config.
     */
    public function getDescriptionValues() {
        $aOut = array();
        foreach (Mage::app()->getWebsites() as $oWebsite) {
            $sLabel = $oWebsite->name;
            foreach ($oWebsite->getGroups() as $oGroup) {
                $sLabel.=' - ' . $oGroup->name;
                foreach ($oGroup->getStores() as $oView) {
                    $sLabel.=' - ' . $oView->name;
                    $aOut[$oView->store_id] = $sLabel;
                }
            }
        }

        return $aOut;
    }
    
    public function getShopValues() {
        return $this->getDescriptionValues();
    }

    public function getCustomerGroupValues($blNotLoggedIn = false) {
        $oGroup = Mage::getModel('customer/group');
        return $oGroup->getCollection()->toOptionHash();
    }

    public function getOrderStatusValues() {
        $collection = Mage::getResourceModel('sales/order_status_collection');
        $collection->joinStates();
        $collection->getSelect()->where('state_table.state not like ?', null);
        $aOut = array();
        foreach ($collection as $oStatus) {
            $aOut[strtolower($oStatus->getstatus())] = $oStatus->getLabel() . ' (' . $oStatus->getState() . ')';
        }

        return $aOut;
    }
    
    public function getBrand() {
        return $this->getAttributeList();
    }

    public function getShippingTime() {
        return $this->getAttributeList();
    }
    
    public function getEan() {
        return $this->getAttributeList();
    }

    public function getUpc() {
        return $this->getAttributeList();
    }

    public function getMarketingDescription() {
        return $this->getAttributeList();
    }

    public function getManufacturer() {
        return $this->getAttributeList();
    }

    public function getManufacturerPartNumber() {
        return $this->getAttributeList();
    }

    /**
     * Gets list of custom attributes from shop.
     * 
     * @return array Collection of all attributes
     */
    public function getAttributeList() {
        return $this->getProductAttributes();
    }

    /**
     * Gets the list of product attributes prefixed with attribute type.
     *
     * @param bool $getProperties Indicates whether to get properties with attributes
     * @TODO Check all usage of this method and if properties should always be present, remove this parameter,
     *      since it is used only in Shopware.
     *
     * @return array Collection of prefixed attributes
     */
    public function getPrefixedAttributeList($getProperties = false) {
        return $this->getProductAttributes();
    }

    /**
     * Gets the list of product attributes that have options (displayed as dropdown or multiselect fields).
     * 
     * @return array Collection of attributes with options
     */
    public function getAttributeListWithOptions() {
        $aResult = $this->getProductAttributes('frontend_input', array('in' => array('select', 'multiselect')));

        // filter out attributes without options
        foreach (array_keys($aResult) as $sAttributeCode) {
            if ($sAttributeCode != '' && count($this->getAttributeOptions($sAttributeCode)) === 0) {
                unset($aResult[$sAttributeCode]);
            }
        }

        return $aResult;
    }

    /**
     * Gets the list of product attribute options for attributes that have options
     * (displayed as dropdown or multiselect fields).
     * If $iLangId is set, uses languages from selected store ($iLangId is store id in magento).
     *
     * @param string $sAttributeCode
     * @param int $iLangId
     * @return array Collection of attributes with options
     */
    public function getAttributeOptions($sAttributeCode, $iLangId = null) {
        $sKey = md5(json_encode(array($sAttributeCode, $iLangId)));
        if (empty($this->aAttributeOptions[$sKey])) {
            /** @var Mage_Catalog_Model_Resource_Eav_Attribute */
            $oAttribute = Mage::getSingleton('eav/config')->getAttribute('catalog_product', $sAttributeCode);
            $aResult = array();
            if ($oAttribute->usesSource()) {
                $oAttribute->setStoreId($iLangId === null ? '0' : $iLangId);
                foreach ($oAttribute->getSource()->getAllOptions(false) as $aOption) {
                    if (is_array($aOption)) { 
                        if (!is_array($aOption['value'])) {
                            $aResult[$aOption['value']] = $aOption['label'];
                        }
                    }
                }
            }

            $this->aAttributeList[$sKey] = $aResult;
        }

        return $this->aAttributeList[$sKey];
    }

    /**
     * Gets the list of product attribute values.
     * If $iLangId is set, use translation for attribute options' labels.
     *
     * @param string $sAttributeCode
     * @param int $iLangId
     * @return array Collection of attribute values
     */
    public function getPrefixedAttributeOptions($sAttributeCode, $iLangId = null) {
        return $this->getAttributeOptions($sAttributeCode, $iLangId);
    }

    public function getCurrency() {
        // this is apparently not used in Magento
        return array();
    }

    /**
     * Gets tax classes for product.
     * 
     * @return array Tax classes
     * array(
     *  array(
     *      'value' => string
     *      'label' => string
     *  ),
     *   ...
     * )
     */
    public function getTaxClasses() {
        return Mage::getModel('tax/class_source_product')->toOptionArray();
    }

    public function manipulateForm(&$aForm) {
        $oI18n = MLI18n::gi();
        try {
            MLRequest::gi()->get('mode');
        } catch (Exception $oEx) {
            if (isset($aForm['orderimport'])) {
                $aForm = MLHelper::getArrayInstance()->mergeDistinct(
                    $aForm,
                    MLI18n::gi()->get('magentospecific_aGeneralForm'));
            }
//            new dBug($aForm);
//            if (isset($aForm['productfields'])) {
//                $aForm['productfields']['fields']['weightunit'] = array(
//                    'label' => $oI18n->get('Magento_Global_Configuration_Label'),
//                    'help' => $oI18n->get('Magento_Global_Configuration_Description'),
//                    'name' => 'general.weightunit',
//                    'type' => 'select',
//                    'values' => ,
//                );
//            }
        }
    }

    protected function getProductAttributes($filterBy = null, $condition = null) {
        $sKey = md5(json_encode(array($filterBy, $condition)));
        if (empty($this->aAttributeList[$sKey])) {
            /* @var $oAttributeCollection Mage_Eav_Model_Resource_Entity_Attribute_Collection */
            $oAttributeCollection = Mage::getResourceModel('eav/entity_attribute_collection')
                ->setEntityTypeFilter(Mage::getModel('catalog/product')
                ->getResource()
                ->getTypeId())
            ;
            if ($filterBy) {
                $oAttributeCollection->addFieldToFilter($filterBy, $condition);
            }
            $oAttributeCollection->load();
            $aOut = array('' => MLI18n::gi()->get('ConfigFormPleaseSelect'));
            foreach ($oAttributeCollection as $oAttribute) {
                if ($oAttribute->frontend_label != '') {
                    $aOut[$oAttribute->attribute_code] = $oAttribute->frontend_label;
                }
            }
            $this->aAttributeList[$sKey] = $aOut;
        }
        return $this->aAttributeList[$sKey];
    }
    
    public function getPossibleVariationGroupNames () {
        return $this->getProductAttributes('is_configurable', 1);
    }

    /**
     * Returns grouped attributes for attribute matching
     * 
     * @throws Exception
     */
    public function getGroupedAttributesForMatching($oSelectedProducts = null) {
        $shopAttributes = array();

        // First element is pure text that explains that nothing is selected so it should not be added
        // nor in Properties or Variations, it is spliced and used just for forming the final array.
        $firstElement = array('' => MLI18n::gi()->get('ConfigFormPleaseSelect'));

        // Variation attributes
        $shopVariationAttributes = $this->getVariationAttributes();
        if (!empty($shopVariationAttributes)) {
            $shopVariationAttributes['optGroupClass'] = 'variation';
            $shopAttributes += array(MLI18n::gi()->get('VariationsOptGroup') => $shopVariationAttributes);
        }

        // Product default fields
        $shopDefaultFieldsAttributes = $this->getDefaultFieldsAttributes();
        if (!empty($shopDefaultFieldsAttributes)) {
            $shopDefaultFieldsAttributes['optGroupClass'] = 'default';
            $shopAttributes += array(MLI18n::gi()->get('ProductDefaultFieldsOptGroup') => $shopDefaultFieldsAttributes);
        }

        // Properties
        $shopPropertiesAttributes = $this->getPropertiesAttributes();
        if (!empty($shopPropertiesAttributes)) {
            $shopPropertiesAttributes['optGroupClass'] = 'property';
            $shopAttributes += array(MLI18n::gi()->get('PropertiesOptGroup') => $shopPropertiesAttributes);
        }

        return $firstElement + $shopAttributes;
    }

    /**
     * Returns flat attribute if attribute code is sent and if not it returns all shop attributes for attribute matching
     * @param string $attributeCode
     * @param ML_Magento_Model_Product|null $product If present attribute value will be set from given product
     * @return array|mixed
     */
    public function getFlatShopAttributesForMatching($attributeCode = null, $product = null)
    {
        $result = $this->getVariationAttributes() + $this->getDefaultFieldsAttributes() + $this->getPropertiesAttributes();

        if (!empty($attributeCode) && !empty($result[$attributeCode])) {
            $result = $result[$attributeCode];
            if (!empty($product)) {
                // First try to get multi select values as array, if that doesn't work get value in a standard way
                $result['value'] = $product->getAttributeText($attributeCode);
                if (empty($result['value'])) {
                    $result['value'] = $product->getAttributeValue($attributeCode);
                }

            }
        }

        return $result;
    }

    /**
     * Returns variation attributes for attribute matching
     *
     * @return array
     */
    private function getVariationAttributes()
    {
        $shopVariationAttributes = array();

        /* @var $attributeCollection Mage_Eav_Model_Resource_Entity_Attribute_Collection */
        $attributeCollection = Mage::getResourceModel('eav/entity_attribute_collection')
            ->setEntityTypeFilter(Mage::getModel('catalog/product')->getResource()->getTypeId())
            ->addFieldToFilter('is_configurable', true)
            ->addFieldToFilter('frontend_input', 'select')
            ->addFieldToFilter('is_global', true)
            ->load();

        foreach ($attributeCollection as $attribute) {
            $attributeLabel = $attribute->frontend_label;
            if (!empty($attributeLabel) &&
                $this->attributeProductTypeIsSupported($attribute) &&
                count($this->getAttributeOptions($attribute->attribute_code)) > 0
            ) {
                $shopVariationAttributes[$attribute->attribute_code] = array(
                    'name' => $attributeLabel,
                    'type' => 'select',
                );
            }
        }

        return $shopVariationAttributes;
    }

    /**
     * Returns default product fields for attribute matching
     *
     * @return array
     */
    private function getDefaultFieldsAttributes()
    {
        return $this->getNonVariationAttributes();
    }

    /**
     * Returns property attributes for attribute matching
     *
     * @return array
     */
    private function getPropertiesAttributes()
    {
        return $this->getNonVariationAttributes(true);
    }

    /**
     * Returns non variation attributes for attribute matching
     *
     * @param boolean $userDefined Which type of non variation attributes to fetch - user defined or system
     * @return array
     */
    private function getNonVariationAttributes($userDefined = false)
    {
        $shopNonVariationAttributes = array();

        /** @var $attributeCollection Mage_Eav_Model_Resource_Entity_Attribute_Collection */
        $attributeCollection = Mage::getResourceModel('eav/entity_attribute_collection')
            ->setEntityTypeFilter(Mage::getModel('catalog/product')->getResource()->getTypeId())
            ->addFieldToFilter('is_user_defined', $userDefined)
            ->load();

        foreach ($attributeCollection as $attribute) {
            $label = $attribute->frontend_label;
            $code = $attribute->attribute_code;

            if (!empty($label)
                && !$this->isVariationAttribute($attribute)
                && $this->attributeProductTypeIsSupported($attribute)
                && $this->attributeTypeIsSupported($attribute)
                && !$this->attributeIsInIgnoreList($attribute)
            ) {
                $type = $this->convertAttributeType($attribute->frontend_input);
                $attributeIsSelect = in_array($type, array('select', 'multiSelect'));
                $attributeIsSelectWithValidOptions = $attributeIsSelect && count($this->getAttributeOptions($code)) > 0;

                if (!$attributeIsSelect || $attributeIsSelectWithValidOptions) {
                    $shopNonVariationAttributes[$code] = array(
                        'name' => $label,
                        'type' => $type,
                    );
                }
            }
        }

        return $shopNonVariationAttributes;
    }

    /**
     * Checks if $attribute::apply_to property is in at lest one of magnalister supported values:
     *   'simple', 'configurable', 'virtual'
     *
     * @param Mage_Eav_Model_Entity_Attribute $attribute
     * @return bool True if attribute product type is supported false otherwise
     */
    private function attributeProductTypeIsSupported($attribute)
    {
        $attributeSupportedProductType = array('all');
        $applyTo = $attribute->apply_to;
        if (!empty($applyTo)) {
            $attributeSupportedProductType = explode(',', $applyTo);
        }

        $magnalisterSupportedProductTypes = array('all', 'simple', 'configurable', 'virtual');
        return count(array_intersect($magnalisterSupportedProductTypes, $attributeSupportedProductType)) > 0;
    }

    /**
     * Checks if $attribute::frontend_input property is in magnalister supported values:
     *   'text', 'textarea', 'date', 'boolean', 'multiselect', 'select', 'price'
     *
     * @param Mage_Eav_Model_Entity_Attribute $attribute
     *
     * @return bool <b>TRUE</b> if attribute type is supported; otherwise, <b>FALSE</b>
     */
    private function attributeTypeIsSupported($attribute)
    {
        $attributeType = $attribute->frontend_input;
        return !in_array($attributeType, array('media_image', 'weee'));
    }

    /**
     * Converts Magento attribute type to one that is supported by Attributes matching
     *
     * @param string $attributeType Magento attribute type
     *
     * @return string Attribute type supported by attributes matching
     */
    private function convertAttributeType($attributeType)
    {
        $type = 'text';
        switch ($attributeType) {
            case 'boolean':
            case 'select':
                $type = 'select';
                break;
            case 'multiselect':
                $type = 'multiSelect';
                break;
        }

        return $type;
    }

    /**
     * Checks if attribute is in ignore list
     *
     * @param Mage_Eav_Model_Entity_Attribute $attribute
     *
     * @return bool <b>TRUE</b> True if attribute is in ignore list; otherwise, <b>FALSE</b>
     */
    private function attributeIsInIgnoreList($attribute)
    {
        $ignoreList = array(
            'custom_design',
            'custom_design_from',
            'custom_design_to',
            'custom_layout_update',
            'gallery',
            'image_label',
            'is_recurring',
            'media_gallery',
            'options_container',
            'page_layout',
            'recurring_profile',
            'small_image_label',
            'thumbnail_label',
            'visibility',
        );

        return in_array($attribute->attribute_code, $ignoreList);
    }

    /**
     * Checks if attribute is variation attribute by Magento definition (configurable drop-down in global scope)
     * @param Mage_Eav_Model_Entity_Attribute $attribute Magento attribute to check
     *
     * @return bool <b>TRUE</b> if attribute is variation; otherwise, <b>FALSE</b>
     */
    private function isVariationAttribute($attribute) {
        return $attribute->is_configurable && $attribute->frontend_input === 'select' && $attribute->is_global;
    }

    public function getShopShippingModuleValues() {
        $aMagentoShippingMethods = Mage::getSingleton('shipping/config')->getActiveCarriers(Mage::app()->getStore());
        $aShippingMethods = array();
        foreach ($aMagentoShippingMethods as $sCarrierGroup => $oCarrier) {
            if ($aMethods = $oCarrier->getAllowedMethods()) {
                if (!$sTitle = Mage::getStoreConfig("carriers/$sCarrierGroup/title")) {
                    $sTitle = $sCarrierGroup;
                }
                $aShippingMethods[$sTitle] = "$sTitle";
            }
        }
        return $aShippingMethods;
    }

    public function getDefaultCancelStatus() {
        return 'canceled';
    }
}
