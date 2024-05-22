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
 * (c) 2010 - 2022 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

include_once(DIR_MAGNALISTER_HELPER . 'APIHelper.php');
include_once(M_DIR_LIBRARY . 'request/shopware/ShopwareSalesChanel.php');
include_once(M_DIR_LIBRARY.'request/shopware/ShopwareCustomerGroup.php');
include_once(M_DIR_LIBRARY . 'request/shopware/ShopwareLanguage.php');
include_once(M_DIR_LIBRARY . 'request/shopware/ShopwareOrderStatus.php');
include_once(M_DIR_LIBRARY . 'request/shopware/ShopwareRule.php');
include_once(M_DIR_LIBRARY . 'request/shopware/ShopwareTax.php');
include_once(M_DIR_LIBRARY . 'request/shopware/ShopwareShippingMethod.php');
include_once(M_DIR_LIBRARY . 'request/shopware/ShopwarePaymentMethod.php');
include_once(M_DIR_LIBRARY . 'request/shopware/ShopwareUnit.php');

use library\request\shopware\ShopwareCustomerGroup;
use library\request\shopware\ShopwareLanguage;
use library\request\shopware\ShopwareOrderStatus;
use library\request\shopware\ShopwarePaymentMethod;
use library\request\shopware\ShopwareRule;
use library\request\shopware\ShopwareSalesChanel;
use library\request\shopware\ShopwareShippingMethod;
use library\request\shopware\ShopwareTax;

MLFilesystem::gi()->loadClass('Shop_Model_ConfigForm_Shop_Abstract');

class ML_Shopwarecloud_Model_ConfigForm_Shop extends ML_Shop_Model_ConfigForm_Shop_Abstract {

    protected $sPlatformName = '';
    public static $aLanguages = null;
    protected $shopwareLanguageRequest = null;
    protected $shopwareRuleRequest = null;

    public function getDescriptionValues() {
        $this->shopwareLanguageRequest = new ShopwareLanguage(MLShopwareCloudAlias::getShopHelper()->getShopId());
        $aLanguageList = $this->shopwareLanguageRequest->getShopwareLanguages('/api/language', 'GET',array(),  false);
        if (self::$aLanguages === null) {
            self::$aLanguages = array();
            foreach ($aLanguageList->getData() as $aRow) {
                self::$aLanguages[$aRow->getId()] = $aRow->getAttributes()->getName();
            }
        }
        return self::$aLanguages;
    }

    /**
     *
     * @return array
     * {
     *      "123":"channel 1",
     *      ....
     * }
     */
    public function getShopValues() {
        $sLangId = MLShopwareCloudTranslationHelper::gi()->getShopwareLanguageId();
        if (!MLCache::gi()->exists($sLangId.'__saleschannelvalues.json')) {
            $aShops = array();
            $salesChannel = new ShopwareSalesChanel(MLShopwareCloudAlias::getShopHelper()->getShopId());
            foreach ($salesChannel->getShopwareSalesChannels()->getData() as $oData) {
                $aShops[$oData->getId()] = MLShopwareCloudTranslationHelper::gi()->getShopwareCloudConfigTranslation($oData->getId(), 'sales-channel');
            }
            MLCache::gi()->set($sLangId.'__saleschannelvalues.json', $aShops, 60 * 60 * 24);
        }

        return MLCache::gi()->get($sLangId.'__saleschannelvalues.json');
    }

    /**
     * @param type $blNotLoggedIn
     * @return type
     */
    public function getCustomerGroupValues($blNotLoggedIn = false) {
        $sLangId = MLShopwareCloudTranslationHelper::gi()->getShopwareLanguageId();
        if (!MLCache::gi()->exists($sLangId.'__customergroupvalues.json')) {
            $aGroupsName = array();
            $oShopwareCustomerGroup = new ShopwareCustomerGroup(MLShopwareCloudAlias::getShopHelper()->getShopId());
            foreach ($oShopwareCustomerGroup->getShopwareCustomerGroup()->getData() as $oData) {
                $aGroupsName[$oData->getId()] = MLShopwareCloudTranslationHelper::gi()->getShopwareCloudConfigTranslation($oData->getId(), 'customer-group');
            }
            $aGroupsName['-'] = MLI18n::gi()->Shopware_Orderimport_CustomerGroup_Notloggedin;
            MLCache::gi()->set($sLangId.'__customergroupvalues.json', $aGroupsName, 60 * 60 * 24);
        }

        return MLCache::gi()->get($sLangId.'__customergroupvalues.json');
    }

    public function getDocumentTypeValues() {

        $aDocumentTypeName = array();
        $documentTypes = $this->getDocumentTypeList();
        foreach ($documentTypes as $documentType) {
                $aDocumentTypeName[$documentType['ShopwareTechnicalName']] = $documentType['ShopwareName'];
        }
        return $aDocumentTypeName;
    }

    /**
     * @param bool $blNotLoggedIn
     * @return array
     */
    public function getAdvancedPriceRules($blNotLoggedIn = false) {
        $aRulesName = array();
        $this->shopwareRuleRequest = new ShopwareRule(MLShopwareCloudAlias::getShopHelper()->getShopId());
        $customerGroups = $this->shopwareRuleRequest->getShopwareRules('/api/rule', 'GET',array(),  false);
        foreach ($customerGroups->getData()  as $aRow) {
            $aRulesName[$aRow->getId()] = $aRow->getAttributes()->getName();
        }
        return $aRulesName;
    }

    /**
     * @return array
     */
    public function getOrderStatusValues() {
        $sLangId = MLShopwareCloudTranslationHelper::gi()->getShopwareLanguageId();
        if (!MLCache::gi()->exists($sLangId.'__orderstatusvalues.json')) {
            $apiHelper = new APIHelper();
            $oOrderStatusRequest = new ShopwareOrderStatus(MLShopwareCloudAlias::getShopHelper()->getShopId());
            $oOrderStatuses = MLHelper::gi('model_shop')->getOrderStatuses($oOrderStatusRequest, $apiHelper, 'order.state');
            
            $aOrderStatus = array();
            foreach ($oOrderStatuses->getData() as $oData) {
                $aOrderStatus[$oData->getAttributes()->getTechnicalName()] = MLShopwareCloudTranslationHelper::gi()->getShopwareCloudConfigTranslation($oData->getId(), 'state-machine-state');
            }
            MLCache::gi()->set($sLangId.'__orderstatusvalues.json', $aOrderStatus, 60 * 60 * 24);
        }

        return MLCache::gi()->get($sLangId.'__orderstatusvalues.json');
    }

    /**
     * @return type
     */
    public function getPaymentStatusValues() {
        $sLangId = MLShopwareCloudTranslationHelper::gi()->getShopwareLanguageId();
        if (!MLCache::gi()->exists($sLangId.'__orderpaymentstatusvalues.json')) {
            $apiHelper = new APIHelper();
            $oOrderStatusRequest = new ShopwareOrderStatus(MLShopwareCloudAlias::getShopHelper()->getShopId());
            $oOrderStatuses = MLHelper::gi('model_shop')->getOrderStatuses($oOrderStatusRequest, $apiHelper, 'order_transaction.state');

            $aPaymentStatesName = array();
            foreach ($oOrderStatuses->getData() as $oData) {
                $aPaymentStatesName[$oData->getAttributes()->getTechnicalName()] = MLShopwareCloudTranslationHelper::gi()->getShopwareCloudConfigTranslation($oData->getId(), 'state-machine-state');
            }
            MLCache::gi()->set($sLangId.'__orderpaymentstatusvalues.json', $aPaymentStatesName, 60 * 60 * 24);
        }

        return MLCache::gi()->get($sLangId.'__orderpaymentstatusvalues.json');
    }

    /**
     * @return array
     */
    public function getEan() {
        return $this->getListOfArticleFields();
    }

    /**
     * @return array
     */
    public function getUpc() {
        return $this->getListOfArticleFields();
    }

    /**
     * @return array
     */
    public function getMarketingDescription() {
        return $this->getListOfArticleFields();
    }

    /**
     * @param false $blAttributeMatching
     * @return array
     * @throws MLAbstract_Exception
     */
    protected function getCustomFields($blAttributeMatching = false): array {
        $customFields = array();

        $CustomFieldSetItems = MLDatabase::getDbInstance()->fetchArray("SELECT * FROM `magnalister_shopwarecloud_custom_fields` WHERE `ShopwareCustomFieldRelation` LIKE '%product%'");

        $LangCode = MLShopwareCloudTranslationHelper::gi()->getLanguageCode();
        if ($blAttributeMatching) {
            foreach ($CustomFieldSetItems as $value) {
                    $Label = $this->getCustomFieldLabel($value, $LangCode);
                    if ($value['ShopwareCustomFieldType'] == 'select') {
                        $customFields['c_'.$value['ShopwareCustomFieldName']] = array(
                            'name' => $Label,
                            'type' => 'select',
                        );
                    } else {
                        $customFields['c_'.$value['ShopwareCustomFieldName']] = array(
                            'name' => $Label,
                            'type' => 'text',
                        );
                    }
            }
        } else {
            foreach ($CustomFieldSetItems as $value) {
                $Label = $this->getCustomFieldLabel($value, $LangCode);
                $customFields['c_'.$value['ShopwareCustomFieldName']] = $Label.' ('.MLI18n::gi()->get('FreeTextAttributesOptGroup').')';
            }
        }

        return $customFields;
    }

    private function getCustomFieldLabel($aCustomField, $LangCode) {
        $customFieldTranslations = json_decode($aCustomField['ShopwareCustomFieldLabel'],true);
        if (isset($customFieldTranslations[$LangCode])) {
            $Label = $customFieldTranslations[$LangCode] . ' ('.$aCustomField['ShopwareCustomFieldName'].')';
        } else {
            // adding the techincal name as label because the Label is optional in shopware cloud
            $Label = $aCustomField['ShopwareCustomFieldName'];
        }

        return $Label;
    }

    /**
     * Returns free text fields for attribute matching
     * @use this function is uest in magnalister/Codepool/90_System/Tools/View/Main/Tools/Products/Search.php:204
     * @access it is appeared in service/Developer->Products->search tab in the "Master Product functions" section
     * @return array
     */
    public function getOrderFreeTextFieldsAttributes($blAttributeMatching = false) {
        //get the language code
        $LangCode = I18N::gi()->getShopwareQueryLocale(true);

        $oCustomFieldEntities = MLDatabase::getDbInstance()->fetchArray(
            "SELECT * FROM `magnalister_shopwarecloud_custom_fields` 
         WHERE `ShopwareCustomFieldRelation`
                   LIKE '%order%'"
        );

        $aCustomFields = array();
        foreach ($oCustomFieldEntities as $oCustomField) {
            //To prevent adding magnalister_order_details custom field in to the list of order custom fields
            if($oCustomField['ShopwareCustomFieldName'] == 'magnalister_order_details'){
                continue;
            }
            $Label = $this->getCustomFieldLabel($oCustomField, $LangCode);
            $aCustomFields['a_'.$oCustomField['ShopwareCustomFieldName']] = $Label;
        }
        return $aCustomFields;
    }


    /**
     *
     * @return array
     */
    public function getProductFreeTextFieldsAttributes($blAttributeMatching = false) {
        $this->getOrderFreeTextFieldsAttributes();
    }

    /**
     * @return type
     */
    protected function getProductPropertiesGroupVariations($blAttributeMatching = false) {
        $VariationsGroupItems = array();
        $aVariationsGroupEntites = $this->getPropertiesGroup();

        if ($blAttributeMatching) {
            foreach ($aVariationsGroupEntites as $aVariationsGroup) {
                $VariationsGroupItems['a_'.$aVariationsGroup['ShopwarePropertyGroupID']] = array(
                        'name' => $aVariationsGroup['ShopwareName'],
                        'type' => 'select',
                    );
            }
        } else {
            foreach ($aVariationsGroupEntites as $aVariationsGroup) {
                $VariationsGroupItems['a_'.$aVariationsGroup['ShopwarePropertyGroupID']] = $aVariationsGroup['ShopwareName'].' ('.MLI18n::gi()->get('VariationsOptGroup').')';
            }
        }

        return $VariationsGroupItems;
    }

    /**
     * @return type
     */
    protected function getProductPropertiesGroup($blAttributeMatching = false) {
        $PropertiesGroupEntites = array();
        $PropertiesGroupEntites = $this->getPropertiesGroup();

        $PropertiesGroupItems = array();
        if ($blAttributeMatching) {
            foreach ($PropertiesGroupEntites as $value) {
                $PropertiesGroupItems['p_'.$value['ShopwarePropertyGroupID']] = array(
                        'name' => $value['ShopwareName'],
                        'type' => 'select',
                    );
            }
        } else {
            foreach ($PropertiesGroupEntites as $value) {
                $PropertiesGroupItems['p_'.$value['ShopwarePropertyGroupID']] = $value['ShopwareName'].' ('.MLI18n::gi()->get('PropertiesOptGroup').')';
            }
        }
        return $PropertiesGroupItems;
    }

    private function getPropertiesGroup() {
        $sLangId = MLShopwareCloudTranslationHelper::gi()->getLanguage();
        return MLDatabase::getDbInstance()->fetchArray("SELECT ShopwarePropertyGroupID, ShopwareName 
    FROM `magnalister_shopwarecloud_property_group_translation` 
    WHERE `ShopwareLanguageID` = '$sLangId';");
    }

    /**
     * @return type
     * @throws MLAbstract_Exception
     */
    protected function getProductFields($blAttributeMatching = false) {
        if ($blAttributeMatching) {
            $productProperty = array(
                'name'           => array(
                    'name' => MLI18n::gi()->get('ProductName'),
                    'type' => 'text',
                ),
                'productNumber'  => array(
                    'name' => MLI18n::gi()->get('ProductNumber'),
                    'type' => 'text',
                ),
                'description'    => array(
                    'name' => MLI18n::gi()->get('Description'),
                    'type' => 'text',
                ),
                'ean'            => array(
                    'name' => MLI18n::gi()->get('EAN'),
                    'type' => 'text',
                ),
                'weight'         => array(
                    'name' => MLI18n::gi()->get('Weight'),
                    'type' => 'text',
                ),
                'width'          => array(
                    'name' => MLI18n::gi()->get('Width'),
                    'type' => 'text',
                ),
                'height'         => array(
                    'name' => MLI18n::gi()->get('Height'),
                    'type' => 'text',
                ),
                'length'         => array(
                    'name' => MLI18n::gi()->get('Length'),
                    'type' => 'text',
                ),
                'manufacturerNumber' => array(
                    'name' => MLI18n::gi()->get('ManufacturerNumber'),
                    'type' => 'select',
                ),
                'manufacturerId' => array(
                    'name' => MLI18n::gi()->get('ManufacturerId'),
                    'type' => 'select',
                ),
            );
        } else {
            $productProperty = array(
                'name'               =>
                    MLI18n::gi()->get('ProductName'),
                'productNumber'      =>
                    MLI18n::gi()->get('ProductNumber'),
                'description'        =>
                    MLI18n::gi()->get('Description'),
                'ean'                =>
                    MLI18n::gi()->get('EAN'),
                'weight'             =>
                    MLI18n::gi()->get('Weight'),
                'width'              =>
                    MLI18n::gi()->get('Width'),
                'height'             =>
                    MLI18n::gi()->get('Height'),
                'length'             =>
                    MLI18n::gi()->get('Length'),
                'manufacturerNumber' =>
                    MLI18n::gi()->get('ManufacturerNumber'),
                'manufacturerId'     =>
                    MLI18n::gi()->get('ManufacturerId'),
            );
        }
        return $productProperty;
    }

    /**
     * @return array
     * @throws MLAbstract_Exception
     */
    protected function getListOfArticleFields() {
        $aFields = array_merge(
            array('' => MLI18n::gi()->get('ConfigFormPleaseSelect')),
            $this->getProductFields(),
            $this->getCustomFields(),
            $this->getProductPropertiesGroupVariations(),
            $this->getProductPropertiesGroup()
        );
        return $aFields;
    }

    public function getManufacturerPartNumber() {
        return $this->getListOfArticleFields();
    }

    public function getManufacturer() {
        return $this->getListOfArticleFields();
    }

    public function getBrand() {
        return $this->getListOfArticleFields();
    }

    public function getShopSystemAttributeList() {
        return $this->getListOfArticleFields();
    }

    public function getShippingTime() {
        return $this->getListOfArticleFields();
    }

    /**
     * @return array
     */
    public function getCurrency() {
        return [];
    }

    /**
     * @param false $getProperties
     * @return array
     * @throws MLAbstract_Exception
     */
    public function getPrefixedAttributeList($getProperties = false) {
        return $this->getListOfArticleFields();
    }

    public function getAttributeOptions($sAttributeCode, $iLangId = null) {
        $aAttributeCode = explode('_', $sAttributeCode, 2);
        $attributes = array();

        // Getting values for Variation attributes
        if ($aAttributeCode[0] === 'a') {
            $VariationconfiguratorOptions = $this->getPropertyOptionsByPropertyGroupOptions($aAttributeCode[1]);
            foreach ($VariationconfiguratorOptions as $VariationconfiguratorOption) {
                $attributes[$VariationconfiguratorOption['ShopwarePropertyGroupOptionID']] = $VariationconfiguratorOption['ShopwareName'];
            }
        }

        // Getting values for product properties attributes
        if ($aAttributeCode[0] === 'p') {
            $configuratorOptions = $this->getPropertyOptionsByPropertyGroupOptions($aAttributeCode[1]);
            foreach ($configuratorOptions as $configuratorOption) {
                $attributes[$configuratorOption['ShopwarePropertyGroupOptionID']] = $configuratorOption['ShopwareName'];
            }
        }

        // Getting values for custom field (there is combobox type in Shopware 5 which acts like single selection)
        // Getting supplier field values
        if ($aAttributeCode[0] === 'c') {
            $CustomFields = MLDatabase::factory('ShopwareCloudCustomFields')->set('ShopwareCustomFieldName', $aAttributeCode[1])->getList()->getList();
            foreach ($CustomFields as $CustomField) {

                    if ($CustomField->data()['shopwarecustomfieldconfigoptions'] != "null" ) {
                        foreach ($CustomField->data()['shopwarecustomfieldconfigoptions'] as  $value) {
                            $attributes[$value['value']] = $value['value'];
                        }
                    }
            }
        }
        if ($sAttributeCode == 'manufacturerId') {
            $oProductManufacrerTranslation = $this->getProductManufacrerTranslationList();
            foreach ($oProductManufacrerTranslation as $ManufacturValue) {
                $attributes[$ManufacturValue['ShopwareProductManufacturerID']] = $ManufacturValue['ShopwareName'];
            }
        }

        return $attributes;
    }

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
     * Return a list of tax classes id and name pair in ShopwareCloud
     * @return array
     */
    public function getTaxClasses() {
        $sLangId = MLShopwareCloudTranslationHelper::gi()->getShopwareLanguageId();
        if (!MLCache::gi()->exists($sLangId.'__taxvalues.json')) {
            $aTaxes = array();
            $oTaxRequest = new ShopwareTax(MLShopwareCloudAlias::getShopHelper()->getShopId());
            $oTaxes = $oTaxRequest->getShopwareTaxes();
            foreach ($oTaxes->getData() as $oData) {
                $aTaxes[] = ['value' => $oData->getId(), 'label' => $oData->getAttributes()->getName()];
            }
            MLCache::gi()->set($sLangId.'__taxvalues.json', $aTaxes, 60 * 60 * 24);
        }

        return MLCache::gi()->get($sLangId.'__taxvalues.json');
    }

    public function getPaymentMethodValues() {
        $sLangId = MLShopwareCloudTranslationHelper::gi()->getShopwareLanguageId();
        if (!MLCache::gi()->exists($sLangId.'__paymentmethodvalues.json')) {
            $aPaymentMethods = array();
            $request = new ShopwarePaymentMethod(MLShopwareCloudAlias::getShopHelper()->getShopId());
            foreach ($request->getPaymentMethods()->getData() as $oData) {
                $aPaymentMethods[$oData->getId()] = MLShopwareCloudTranslationHelper::gi()->getShopwareCloudConfigTranslation($oData->getId(), 'payment-method');
            }
            MLCache::gi()->set($sLangId.'__paymentmethodvalues.json', $aPaymentMethods, 60 * 60 * 24);
        }

        return MLCache::gi()->get($sLangId.'__paymentmethodvalues.json');
    }

    public function getShippingMethodValues() {
        return $this->getShopShippingModuleValues();
    }

    public function getShopShippingModuleValues() {
        $sLangId = MLShopwareCloudTranslationHelper::gi()->getShopwareLanguageId();
        if (!MLCache::gi()->exists($sLangId.'__shippingmethodvalues.json')) {
            $aShippingMethods = array();
            $request = new ShopwareShippingMethod(MLShopwareCloudAlias::getShopHelper()->getShopId());
            foreach ($request->getShippingMethods()->getData() as $oData) {
                $aShippingMethods[$oData->getId()] = MLShopwareCloudTranslationHelper::gi()->getShopwareCloudConfigTranslation($oData->getId(), 'shipping-method');
            }
            MLCache::gi()->set($sLangId.'__shippingmethodvalues.json', $aShippingMethods, 60 * 60 * 24);
        }

        return MLCache::gi()->get($sLangId.'__shippingmethodvalues.json');
    }

    /**
     * @return array
     * array(
     * "attribute-id"=>"color",...
     * )
     */
    public function getPossibleVariationGroupNames() {
        $aAttributes = array('' => MLI18n::gi()->get('ConfigFormPleaseSelect'));
        $aConfiguratorGroups =  $this->getPropertiesGroup();
        foreach ($aConfiguratorGroups as $aConfiguratorGroup) {
            $aAttributes['a_'.$aConfiguratorGroup['ShopwarePropertyGroupID']] = $aConfiguratorGroup['ShopwareName'];
        }
        return $aAttributes;
    }

    /**
     * @return type
     */
    public function getGroupedAttributesForMatching($oSelectedProducts = null) {
        $aShopAttributes = array();

        // keep it as it is
        $aFirstElement = array('' => MLI18n::gi()->get('ConfigFormPleaseSelect'));

        // Variation attributes
        $aShopVariationAttributes = $this->getProductPropertiesGroupVariations(true);
        if (!empty($aShopVariationAttributes)) {
            $aShopVariationAttributes['optGroupClass'] = 'variation';
            $aShopAttributes += array(MLI18n::gi()->get('VariationsOptGroup') => $aShopVariationAttributes);
        }
        // Properties product attributes
        $aShopProductPropertyAttributes = $this->getProductPropertiesGroup(true);
        if (!empty($aShopProductPropertyAttributes)) {
            $aShopProductPropertyAttributes['optGroupClass'] = 'property';
            $aShopAttributes += array(MLI18n::gi()->get('PropertiesOptGroup') => $aShopProductPropertyAttributes);
        }


        // Product default fields: Shopware product table field
        $aShopDefaultFieldsAttributes = $this->getProductFields(true);
        if (!empty($aShopDefaultFieldsAttributes)) {
            $aShopDefaultFieldsAttributes['optGroupClass'] = 'default';
            $aShopAttributes += array(MLI18n::gi()->get('ProductDefaultFieldsOptGroup') => $aShopDefaultFieldsAttributes);
        }

        // custom field shopware 6
        $aShopPropertiesAttributes = $this->getCustomFields(true);
        if (!empty($aShopPropertiesAttributes)) {
            $aShopPropertiesAttributes['optGroupClass'] = 'freetext';
            $aShopAttributes += array(MLI18n::gi()->get('FreeTextAttributesOptGroup') => $aShopPropertiesAttributes);
        }


        return $aFirstElement + $aShopAttributes;
    }

    /**
     * @param string $attributeCode
     * @param ML_Shopware_Model_Product|null $product If present attribute value will be set from given product
     * @return array|mixed
     * @throws Exception
     */
    public function getFlatShopAttributesForMatching($attributeCode = null, $product = null) {
        $result = $this->getProductPropertiesGroupVariations(true) + $this->getProductPropertiesGroup(true) + $this->getProductFields(true) +
            $this->getCustomFields(true);

        //for testing
        //#Start
        //$product =  MLProduct::factory()->getByMarketplaceSKU('fd77b77d3ffd4476b67684ed2837996b', true); // master product
        //$product =  MLProduct::factory()->getByMarketplaceSKU('ff8f7d46e1574eaf9e502a8d4a3de285.1', false); // variation product
        //#End

        if (!empty($attributeCode) && !empty($result[$attributeCode])) {
            $result = $result[$attributeCode];
            if (!empty($product)) {

                $result['value'] = $product->getAttributeValue($attributeCode);
            }
        }

        return $result;
    }

    /**
     * @param string $sAttributeKey
     * @return bool
     * @return bool
     */
    public function shouldBeDisplayedAsVariationAttribute($sAttributeKey) {
        return substr($sAttributeKey, 0, 2) === 'a_';
    }

    public function manipulateFormAfterNormalize(&$aForm) {
        try {
            parent::manipulateFormAfterNormalize($aForm);
            $sController = MLRequest::gi()->data('controller');
            MLModule::gi();
            if (isset($aForm['importactive']['fields']['orderimport.paymentmethod'])) {
                $aForm['importactive']['fields']['orderimport.paymentmethod']['type'] = 'select';
                $aForm['importactive']['fields']['orderimport.paymentmethod']['expert'] = false;
                $aForm['importactive']['fields']['orderimport.paymentmethod']['values'] = $this->getPaymentMethodValues();
                $aForm['importactive']['fields']['orderimport.paymentmethod']['help'] = MLModule::gi()->isOrderPaymentMethodAvailable() ? MLI18n::gi()->ShopwareCloud_Configuration_PaymentMethod_Available_Info : MLI18n::gi()->ShopwareCloud_Configuration_PaymentMethod_NotAvailable_Info;
                unset($aForm['importactive']['fields']['orderimport.paymentmethod']['subfields']);
            } else if (isset($aForm['orderimport']['fields']['orderimport.paymentmethod'])) {
                $aForm['orderimport']['fields']['orderimport.paymentmethod']['type'] = 'select';
                $aForm['orderimport']['fields']['orderimport.paymentmethod']['expert'] = false;
                $aForm['orderimport']['fields']['orderimport.paymentmethod']['values'] = $this->getPaymentMethodValues();
                $aForm['orderimport']['fields']['orderimport.paymentmethod']['help'] = MLModule::gi()->isOrderPaymentMethodAvailable() ? MLI18n::gi()->ShopwareCloud_Configuration_PaymentMethod_Available_Info : MLI18n::gi()->ShopwareCloud_Configuration_PaymentMethod_NotAvailable_Info;
                unset($aForm['orderimport']['fields']['orderimport.paymentmethod']['subfields']);
            }
            if (isset($aForm['importactive']['fields']['orderimport.shippingmethod'])) {
                $aForm['importactive']['fields']['orderimport.shippingmethod']['type'] = 'select';
                $aForm['importactive']['fields']['orderimport.shippingmethod']['expert'] = false;
                $aForm['importactive']['fields']['orderimport.shippingmethod']['values'] = $this->getShippingmethodValues();
                $aForm['importactive']['fields']['orderimport.shippingmethod']['help'] = MLModule::gi()->isOrderShippingMethodAvailable() ? MLI18n::gi()->get('ShopwareCloud_Configuration_ShippingMethod_Available_Info') : MLI18n::gi()->get('ShopwareCloud_Configuration_ShippingMethod_NotAvailable_Info');
                unset($aForm['importactive']['fields']['orderimport.shippingmethod']['subfields']);
            } else if (isset($aForm['orderimport']['fields']['orderimport.shippingmethod'])) {
                $aForm['orderimport']['fields']['orderimport.shippingmethod']['type'] = 'select';
                $aForm['orderimport']['fields']['orderimport.shippingmethod']['expert'] = false;
                $aForm['orderimport']['fields']['orderimport.shippingmethod']['values'] = $this->getShippingmethodValues();
                $aForm['orderimport']['fields']['orderimport.shippingmethod']['help'] = MLModule::gi()->isOrderShippingMethodAvailable() ? MLI18n::gi()->get('ShopwareCloud_Configuration_ShippingMethod_Available_Info') : MLI18n::gi()->get('ShopwareCloud_Configuration_ShippingMethod_NotAvailable_Info');
                unset($aForm['orderimport']['fields']['orderimport.shippingmethod']['subfields']);
            }
            if (isset($aForm['importactive']['fields']['orderimport.paymentstatus'])) {
                $aForm['importactive']['fields']['orderimport.paymentstatus']['type'] = 'select';
                $aForm['importactive']['fields']['orderimport.paymentstatus']['expert'] = false;
                $aForm['importactive']['fields']['orderimport.paymentstatus']['values'] = $this->getPaymentStatusValues();
                $aForm['importactive']['fields']['orderimport.paymentstatus']['help'] = MLI18n::gi()->get('ShopwareCloud_Configuration_PaymentStatus_Available_Info');
                unset($aForm['importactive']['fields']['orderimport.paymentstatus']['subfields']);
            } else if (isset($aForm['orderimport']['fields']['orderimport.paymentstatus'])) {
                $aForm['orderimport']['fields']['orderimport.paymentstatus']['type'] = 'select';
                $aForm['orderimport']['fields']['orderimport.paymentstatus']['expert'] = false;
                $aForm['orderimport']['fields']['orderimport.paymentstatus']['values'] = $this->getPaymentstatusValues();
                $aForm['orderimport']['fields']['orderimport.paymentstatus']['help'] = MLI18n::gi()->get('ShopwareCloud_Configuration_PaymentStatus_Available_Info');
                unset($aForm['orderimport']['fields']['orderimport.paymentstatus']['subfields']);
            }

            if (isset($aForm['importactive']['fields']['orderimport.shop'])) {
                $aForm['importactive']['fields']['orderimport.shop']['i18n']['label'] = MLI18n::gi()->get('Shopware6_Marketplace_Configuration_SalesChannel_Label');
                $aForm['importactive']['fields']['orderimport.shop']['i18n']['help'] = MLI18n::gi()->get('Shopware6_Marketplace_Configuration_SalesChannel_Info');
            } else if (isset($aForm['orderimport']['fields']['orderimport.shop'])) {

                $aForm['orderimport']['fields']['orderimport.shop']['i18n']['label'] = MLI18n::gi()->get('Shopware6_Marketplace_Configuration_SalesChannel_Label');
                $aForm['orderimport']['fields']['orderimport.shop']['i18n']['help'] = MLI18n::gi()->get('Shopware6_Marketplace_Configuration_SalesChannel_Info');
            }
            if (strpos($sController, '_config_price') !== false || strpos($sController, '_config_prepare') !== false) {
                foreach ($aForm as $sKey => $aGroups) {
                    foreach ($aForm[$sKey]['fields'] as $sInnerKey => $aField) {

                        //Change customer group selection to price rules selection
                        if (isset($aForm[$sKey]['fields'][$sInnerKey]) && isset($aForm[$sKey]['fields'][$sInnerKey]['realname']) && strpos($aForm[$sKey]['fields'][$sInnerKey]['realname'], 'fixed.priceoptions') !== false) {
                            $aForm[$sKey]['fields'][$sInnerKey]['i18n']['help'] = MLI18n::gi()->get('Shopware6_eBay_Marketplace_Configuration_fixedPriceoptions_help');
                            $aForm[$sKey]['fields'][$sInnerKey]['i18n']['label'] = MLI18n::gi()->get('Shopware6_eBay_Marketplace_Configuration_fixedPriceoptions_label');
                            $aForm[$sKey]['fields'][$sInnerKey]['subfields']['group']['values'] = $this->getAdvancedPriceRules();
                        } else if (
                            (
                                isset($aForm[$sKey]['fields'][$sInnerKey]) && isset($aForm[$sKey]['fields'][$sInnerKey]['realname'])
                                && strpos($aForm[$sKey]['fields'][$sInnerKey]['realname'], 'strikepriceoptions') !== false
                            )
                            ||
                            (
                                isset($aForm[$sKey]['fields'][$sInnerKey]) && isset($aForm[$sKey]['fields'][$sInnerKey]['realname'])
                                && strpos($aForm[$sKey]['fields'][$sInnerKey]['realname'], 'b2b.priceoptions') !== false
                            )
                        ) {
                            $aForm[$sKey]['fields'][$sInnerKey]['subfields']['group']['values'] = $this->getAdvancedPriceRules();
                        } else if (isset($aForm[$sKey]['fields'][$sInnerKey]) && isset($aForm[$sKey]['fields'][$sInnerKey]['realname']) && strpos($aForm[$sKey]['fields'][$sInnerKey]['realname'], 'chinese.priceoptions') !== false) {
                            $aForm[$sKey]['fields'][$sInnerKey]['i18n']['help'] = MLI18n::gi()->get('Shopware6_eBay_Marketplace_Configuration_fixedPriceoptions_help');
                            $aForm[$sKey]['fields'][$sInnerKey]['i18n']['label'] = MLI18n::gi()->get('Shopware6_eBay_Marketplace_Configuration_chinesePriceoptions_label');
                            $aForm[$sKey]['fields'][$sInnerKey]['subfields']['group']['values'] = $this->getAdvancedPriceRules();
                        } else if (isset($aForm[$sKey]['fields'][$sInnerKey]) && isset($aForm[$sKey]['fields'][$sInnerKey]['realname']) && strpos($aForm[$sKey]['fields'][$sInnerKey]['realname'], 'priceoptions') !== false) {
                            $aForm[$sKey]['fields'][$sInnerKey]['i18n']['help'] = MLI18n::gi()->get('Shopware6_eBay_Marketplace_Configuration_fixedPriceoptions_help');
                            $aForm[$sKey]['fields'][$sInnerKey]['i18n']['label'] = MLI18n::gi()->get('Shopware6_eBay_Marketplace_Configuration_fixedPriceoptions_label');
                            $aForm[$sKey]['fields'][$sInnerKey]['subfields']['group']['values'] = $this->getAdvancedPriceRules();
                        }

                        //Removing special price check box from "Marketplaces->Price calculation"
                        if (isset($aForm[$sKey]['fields'][$sInnerKey]['subfields'])) {
                            foreach ($aForm[$sKey]['fields'][$sInnerKey]['subfields'] as $sInnerKey2 => $aField2) {
                                if (isset($aForm[$sKey]['fields'][$sInnerKey]['subfields'][$sInnerKey2]) && isset($aForm[$sKey]['fields'][$sInnerKey]['subfields'][$sInnerKey2]['realname']) && strpos($aForm[$sKey]['fields'][$sInnerKey]['subfields'][$sInnerKey2]['realname'], 'chinese.price.usespecialoffer') !== false) {
                                    unset($aForm[$sKey]['fields'][$sInnerKey]['subfields'][$sInnerKey2]);
                                }
                                if (isset($aForm[$sKey]['fields'][$sInnerKey]['subfields'][$sInnerKey2]) && isset($aForm[$sKey]['fields'][$sInnerKey]['subfields'][$sInnerKey2]['realname']) && strpos($aForm[$sKey]['fields'][$sInnerKey]['subfields'][$sInnerKey2]['realname'], 'fixed.price.usespecialoffer') !== false) {
                                    unset($aForm[$sKey]['fields'][$sInnerKey]['subfields'][$sInnerKey2]);
                                }
                                if (isset($aForm[$sKey]['fields'][$sInnerKey]['subfields'][$sInnerKey2]) && isset($aForm[$sKey]['fields'][$sInnerKey]['subfields'][$sInnerKey2]['realname']) && strpos($aForm[$sKey]['fields'][$sInnerKey]['subfields'][$sInnerKey2]['realname'], 'price.usespecialoffer') !== false) {
                                    unset($aForm[$sKey]['fields'][$sInnerKey]['subfields'][$sInnerKey2]);
                                }

                            }
                        }
                    }
                }
            }
        } catch (Exception $ex) {

        }
    }

    public function getDefaultCancelStatus() {
        return 'cancelled';
    }

    /**
     * @return array|false
     */
    protected function getProductManufacrerTranslationList()
    {
        $sLangId = MLShopwareCloudTranslationHelper::gi()->getLanguage();
        $oProductManufacrerTranslation = MLDatabase::getDbInstance()->fetchArray(
            "SELECT ShopwareProductManufacturerID, ShopwareName FROM
                                                       `magnalister_shopwarecloud_product_manufacturer_translation` 
                                                   WHERE `ShopwareLanguageID` = '$sLangId';"
        );
        return $oProductManufacrerTranslation;
    }

    /**
     * @param $aAttributeCode
     * @return array|false
     */
    protected function getPropertyOptionsByPropertyGroupOptions($aAttributeCode)
    {
        $sLangId = MLShopwareCloudTranslationHelper::gi()->getLanguage();
        $VariationconfiguratorOptions = MLDatabase::getDbInstance()->fetchArray("SELECT ShopwarePropertyGroupOptionID, ShopwareName 
    FROM `magnalister_shopwarecloud_property_group_option_translation` 
    WHERE `ShopwareLanguageID` = '$sLangId' 
      AND `ShopwarePropertyGroupID` = '$aAttributeCode' 
      ORDER BY `ShopwarePosition`,`ShopwareName` ASC;");
        return $VariationconfiguratorOptions;
    }

    /**
     * @param $aAttributeCode
     * @return array|false
     */
    protected function getDocumentTypeList()
    {
        $sLangId = MLShopwareCloudTranslationHelper::gi()->getShopwareLanguageId();
        return MLDatabase::factorySelectClass()
            ->select('*')
            ->from('magnalister_shopwarecloud_document_type', 'mgo')
            ->join(array('magnalister_shopwarecloud_document_type_translation', 'mlo', 'mgo.`ShopwareDocumentTypeId` = mlo.`ShopwareDocumentTypeId`'), ML_Database_Model_Query_Select::JOIN_TYPE_LEFT)
            ->where("mlo.`ShopwareLanguageId` = '$sLangId'")
            ->getResult();
    }

    public function getShopwareCloudTaxes() {
        $preparedTaxes = array();
        $taxes = MLDatabase::getDbInstance()->fetchArray('SELECT ShopwareTaxID, ShopwareName FROM magnalister_shopwarecloud_tax');
        foreach ($taxes as $tax) {
            $preparedTaxes[$tax['ShopwareTaxID']] = $tax['ShopwareName'];
        }
        return $preparedTaxes;
    }
}
