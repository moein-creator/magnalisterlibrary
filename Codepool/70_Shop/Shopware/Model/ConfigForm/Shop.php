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

class ML_Shopware_Model_ConfigForm_Shop extends ML_Shop_Model_ConfigForm_Shop_Abstract {

    protected $sPlatformName = '';

    /** @var null|array */
    public static $aLanguages = null;

    protected static $aProductFields = array();

    public function getDescriptionValues() {
        if(self::$aLanguages === null) {
            self::$aLanguages = array();
            $shop = Shopware()->Models()->getRepository('Shopware\Models\Shop\Shop')->getBaseListQuery()->getArrayResult();
            #echo print_m($shop, '$shop');
            foreach ($shop as $aRow) {
                /*
                 * Load language of locale
                 */
                $builder = Shopware()->Models()->getRepository('Shopware\Models\Shop\Locale')->createQueryBuilder('Locale')->where('Locale.id = :localeId');
                $builder->setParameters(array(
                    'localeId' => $aRow['localeId'],
                ));
                $locale = $builder->getQuery()->getOneOrNullResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);

                /*
                 * Load main category of shop
                 */
                $builder = Shopware()->Models()->getRepository('Shopware\Models\Category\Category')->createQueryBuilder('Category')->where('Category.id = :categoryId');
                $builder->setParameters(array(
                    'categoryId' => $aRow['categoryId'],
                ));
                $mainCategory = $builder->getQuery()->getOneOrNullResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
                #echo print_m($mainCategory, '$mainCategory');
                /*
                 * Set languages for configuration
                 */
                self::$aLanguages[$aRow['id']] = $aRow['name'].' - '.$locale['language'].' - '.$mainCategory['name'];
            }
        }
        return self::$aLanguages;
    }
    
    public function getShopValues() {
        $aShops = array();
        $aShopData = Shopware()->Models()->getRepository('Shopware\Models\Shop\Shop')->getBaseListQuery()->getArrayResult();
        #echo print_m($shop, '$shop');
        foreach ($aShopData as $aRow) {
            $aShops[$aRow['id']] = $aRow['name'] ;
        }
        return $aShops;
    }

    /**
     * It returns just the CustomerGroups of Shopware with no additons (for config fields "getCustomerGroupValues" is used)
     *
     * @return array
     */
    public function getCustomerGroupsOfShopware() {
        $aGroupsName = array();
        $customerGroups = Shopware()->Models()->getRepository('Shopware\Models\Customer\Customer')->getCustomerGroupsQuery()->getArrayResult();
        foreach ($customerGroups as $aRow) {
            $aGroupsName[$aRow['id']] = $aRow['name'];
        }

        return $aGroupsName;
    }

    /**
     * Returns all customer groups + if set the "guests only" customer group for marketplace configuration
     *
     * @param false $blNotLoggedIn
     * @return array
     */
    public function getCustomerGroupValues($blNotLoggedIn = false) {
        /** @var ML_Shopware_Helper_Model_Shop $shopHelper */
        $shopHelper = MLHelper::gi('model_shop');

        $aGroupsName = $this->getCustomerGroupsOfShopware();
        $oQueryBuilder = Shopware()->Models()->createQueryBuilder();
        if ($blNotLoggedIn) {
            $aRes = $oQueryBuilder
                ->select('snippet.value')
                ->from('Shopware\Models\Snippet\Snippet', 'snippet')
                ->where("snippet.name = 'RegisterLabelNoAccount' AND snippet.namespace = 'frontend/register/personal_fieldset' And snippet.localeId = ".$shopHelper->getLocalOfBackendUser()->getId())
                ->getQuery()
                ->getArrayResult();
            if (!empty($aRes)) {
                $aGroupsName['-'] = $aRes[0]['value'];
            } else {
                $aGroupsName['-'] = MLI18n::gi()->Shopware_Orderimport_CustomerGroup_Notloggedin;
            }
        }
        return $aGroupsName;
    }

    public function getDocumentTypeValues() {
        $aDocumentTypeName = array();
        $documentTypes = Shopware()->Models()->getRepository('Shopware\Models\Document\Document')->createQueryBuilder('Document')->getQuery()->getArrayResult();
        foreach ($documentTypes as $documentType) {
            $aDocumentTypeName[$documentType['key']] = $documentType['name'];
        }

        return $aDocumentTypeName;
    }

    public function getOrderStatusValues() {
        /** @var ML_Shopware_Helper_Model_Shop $shopHelper */
        $shopHelper = MLHelper::gi('model_shop');
        $aStatusI18N = $shopHelper->getI18nSnippets('backend/static/order_status');

        $orderStates = MLDatabase::getDbInstance()->fetchArray("select * from  `s_core_states` where `group` = 'state' order By `position`");
        $aOrderStatesName = array();
        if (isset($orderStates[0]['name'])) {
            foreach ($orderStates as $aRow) {
                $sI18NIndex = $aRow['name'];
                $aOrderStatesName[$aRow['id']] = isset($aStatusI18N[$sI18NIndex]) ? $aStatusI18N[$sI18NIndex] : $aRow['description'];
            }
        } else {
            foreach ($orderStates as $aRow) {
                $sI18NIndex = strtolower(str_replace(array(' / ', ' '), '_', $aRow['description']));
                $sI18NIndex = strtolower(str_replace(
                    array(
                        'in_work',
                        'canceled',
                        'clarification_needed',
                        'partial_delivered',
                        'fully_completed',
                        'delivered_completely'
                    ), array(
                        'in_process',
                        'cancelled',
                        'clarification_required',
                        'partially_delivered',
                        'completed',
                        'completely_delivered'
                    ), $sI18NIndex)
                );

                $aOrderStatesName[$aRow['id']] = isset($aStatusI18N[$sI18NIndex]) ? $aStatusI18N[$sI18NIndex] : $aRow['description'];
            }
        }
        $aCanceledStatus = $aOrderStatesName[-1];
        unset($aOrderStatesName[-1]);
        $aOrderStatesName[-1] = $aCanceledStatus;

        return $aOrderStatesName;
    }

    public function getPaymentStatusValues() {
        /** @var ML_Shopware_Helper_Model_Shop $shopHelper */
        $shopHelper = MLHelper::gi('model_shop');

        MLDatabase::getDbInstance()->setCharset(MLDatabase::getDbInstance()->tableEncoding('s_core_states'));
        $paymentStates = Shopware()->Db()->fetchAll("select id, name, description from `s_core_states` where `group` = 'payment' order By `position` ");

        return $shopHelper->getTranslatedValues('backend/static/payment_status', $paymentStates);
    }

    public function getEan() {
        return $this->getListOfArticleFields();
    }

    public function getUpc() {
        return $this->getListOfArticleFields();
    }

    public function getMarketingDescription() {
        return $this->getListOfArticleFields();
    }

    protected static $aShopwareSnippet = array();
    protected function translateProductFields($sName, $sDefault){
        if(empty(self::$aShopwareSnippet)) {
            $oQueryBuilder = Shopware()->Models()->createQueryBuilder();
            $aSnippets = $oQueryBuilder
                ->select('snippet2.value,snippet2.name')
                ->from('Shopware\Models\Snippet\Snippet', 'snippet2')
                ->where("(snippet2.name LIKE 'columns/product/Article_%' OR snippet2.name LIKE 'columns/product/Detail_%')"
                                    . " AND snippet2.namespace = 'backend/article_list/main' And snippet2.localeId = ".Shopware()->Shop()->getLocale()->getId())
                ->getQuery()
                ->getArrayResult();
            foreach ($aSnippets as $aSnippet){
                self::$aShopwareSnippet[$aSnippet['name']] = $aSnippet['value'];
            }
        }
        return isset(self::$aShopwareSnippet[$sName]) ? self::$aShopwareSnippet[$sName] : $sDefault;
    }

    protected function getProductTableFields() {
        if (empty(self::$aProductFields)) {
            foreach (array(
                         'Shopware\Models\Article\Article' => 'Article',
                         'Shopware\Models\Article\Detail' => 'Detail',
                     ) as $sModel => $sFieldKey
            ) {
                $aColumns = Shopware()->Models()->getClassMetadata($sModel)->columnNames;
                foreach ($aColumns as $sKey => &$sName) {
                    $sSearchName = $sKey;
                    $sName = $this->translateProductFields('columns/product/'.$sFieldKey.'_'.$sSearchName, $sSearchName);
                    if ($sKey !== 'articleId' && $sKey != 'Id' ) {
                        if (substr($sName, -2) == 'Id') {// shopware 4.1 doesn't have some translation
                            $sName = substr($sName, 0, -2);
                        } else{
                            $sName = str_replace('Id ','', $sName);
                        }
                    }
                }
                asort($aColumns);
                self::$aProductFields += $aColumns;
            }
        }
        return self::$aProductFields;
    }

    protected function getFreeTextFields(){
        $aFields = array();
        $oProductHelper = MLHelper::gi('model_product');
        $aOpenTextFields = $oProductHelper->getAttributeFields();
        foreach ($aOpenTextFields as $aOpenTextField) {
            if($aOpenTextField['configured']){
                $aFields['a_' . $aOpenTextField['name']] = (empty($aOpenTextField['label']) ? $aOpenTextField['name'] : $aOpenTextField['label']) . " (Free text fields)";
            }
        }
        asort($aFields);
        return $aFields;
    }

    protected function getListOfArticleFields() {
        $aFields = array_merge(
            array('' => MLI18n::gi()->get('ConfigFormPleaseSelect')),
            $this->getProductTableFields(),
            $this->getFreeTextFields()
        );
        //remove some field that could not be used 
        unset($aFields['filterGroupId']);
        unset($aFields['mainDetailId']);
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
    
    public function getCurrency() {
        $aCurrencyModel = Shopware()->Models()->getRepository('Shopware\Models\Shop\Currency')->createQueryBuilder('Currency')->getQuery()->getArrayResult();
        $aCurrency = array();
        foreach ($aCurrencyModel as $aCur) {
            $aCurrency[$aCur['id']] = $aCur['currency'];
        }
        return $aCurrency;
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
        $aAttributes = array('' => MLI18n::gi()->get('ConfigFormPleaseSelect'));

        $aConfiguratorGroups = Shopware()->Db()->fetchAll('select id, name from '.Shopware()->Models()->getClassMetadata('Shopware\Models\Article\Configurator\Group')->getTableName());
        if (version_compare(MLSHOPWAREVERSION, '5.6.0', '>=')) {
            $oTranslation = new \Shopware_Components_Translation(Shopware()->Container()->get('dbal_connection'), Shopware()->Container());
        } else {
            $oTranslation = new \Shopware_Components_Translation();
        }
        $iLanguage = Shopware()->Shop()->getId();
        foreach ($aConfiguratorGroups as &$aConfiguratorGroup) {
            $aGroupTranslated = $oTranslation->read($iLanguage, 'configuratorgroup', $aConfiguratorGroup['id']);
            if (empty($aGroupTranslated) || !isset($aGroupTranslated['name'])) {
                $aAttributes['c_' . $aConfiguratorGroup['id']] = $aConfiguratorGroup['name'];
            } else {
                $aAttributes['c_' . $aConfiguratorGroup['id']] = $aGroupTranslated['name'];
            }
        }

        $aOpenTextFields = MLShopwareAlias::getProductHelper()->getAttributeFields();
        foreach ($aOpenTextFields as $aOpenTextField) {
            if($aOpenTextField['configured']){
                $aAttributes['a_' . $aOpenTextField['name']] = (empty($aOpenTextField['label']) ? $aOpenTextField['name'] : $aOpenTextField['label']);
            }
        }

        $aAttributes['p_articleName'] = 'Title';
        $aAttributes['pd_Number'] = 'Item number';
        $aAttributes['p_description'] = 'Short description';
        $aAttributes['p_description_long'] = 'Description';
        $aAttributes['pd_Ean'] = 'EAN';
        $aAttributes['pd_Weight'] = 'Weight';

        // NOTE: Properties are multivalue field and therefore are not added unless explicitly requested
        if ($getProperties) {
            foreach (MLHelper::gi('model_product')->getAllProperties() as $option) {
                $aAttributes["pp_{$option['id']}"] = $option['name'];
            }
        }

        return $aAttributes;
    }

    //    /**
    //     * Gets the list of product attributes that have options (displayed as dropdown or multiselect fields).
    //     *
    //     * @return array Collection of attributes with options
    //     */
    //    public function getAttributeListWithOptions() {
    //        $aAttributes = $this->getPossibleVariationGroupNames();
    //        foreach ($aAttributes as $sKey => $sAttribute) {
    //            $aAttributes[$sKey] = mb_convert_encoding($sAttribute, 'HTML-ENTITIES');
    //        }
    //
    //        // NOTE: Properties are multivalue field and therefore are not added
    //
    //        return $aAttributes;
    //    }

    /**
     * Gets the list of product attributes that have options (displayed as dropdown or multiselect fields).
     * If $iLangId is set, use translation for attribute options' labels.
     *
     * @return array Collection of attributes with options
     */
    public function getAttributeOptions($sAttributeCode, $iLangId = null) {
        $aAttributeCode = explode('_', $sAttributeCode, 2);
        $attributes = array();

        // Getting values for variation attributes
		if ($aAttributeCode[0] === 'c') {
            $configuratorOptions = MLDatabase::factorySelectClass()
                ->select('id, name')
                ->from(Shopware()->Models()->getClassMetadata('Shopware\Models\Article\Configurator\Option')->getTableName())
                ->where("group_id = $aAttributeCode[1]")
                ->getResult();
            $oProductHelper = MLHelper::gi('model_product');
            $aConfigurations = $oProductHelper->getVariationOptionsTranslation($configuratorOptions);
            foreach ($aConfigurations as &$configuratorOption) {
                $attributes[$configuratorOption['id']] = $configuratorOption['name'];
            }
        }

        // Getting values for free text fields (there is combobox type in Shopware 5 which acts like single selection)
        if ($aAttributeCode[0] === 'a') {
            // We need to be aware of Shopware version, because in version 5 free text fields are in different table
            try {
                // Shopware 5
                $aOpenTextFieldValues =  MLDatabase::factorySelectClass()
                    ->select('array_store AS attrValues')
                    ->from(Shopware()->Models()->getClassMetadata('Shopware\Models\Attribute\Configuration')->getTableName())
                    ->where(array('column_name' => $aAttributeCode[1], 'table_name' => 's_articles_attributes'))
                    ->limit(1)
                    ->getResult();
            } catch (Exception $ex) {
                // In Shopware 4 class Shopware\Models\Attribute\Configuration doesn't exist so exception will be thrown
            }

            if (!empty($aOpenTextFieldValues)) {
                $values = json_decode($aOpenTextFieldValues[0]['attrValues'], true);
                if (!empty($values) && is_array($values)) {
                    foreach ($values as &$value) {
                        $attributes[$value['key']] = $value['value'];
                    }
                }
            }
        }

        // Getting values for properties attributes
        if ($aAttributeCode[0] === 'pp') {
            /** @var ML_Shopware_Helper_Model_Product $oProductHelper */
            $oProductHelper = MLHelper::gi('model_product');

            $attributes = $oProductHelper->getAllPropertyValues($aAttributeCode[1]);
        }

        // Getting supplier field values
        if ($aAttributeCode[0] === 'sp') {
            /* @var $articleRepository \Shopware\Models\Article\Repository */
            $articleRepository = Shopware()->Models()->getRepository('Shopware\Models\Article\Article');
            $suppliers = $articleRepository->getSupplierListQuery(null, array(array('property' => 'name')))->getArrayResult();
            foreach ($suppliers as $supplier) {
                $attributes[$supplier['id']] = $supplier['name'];
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

    public function getTaxClasses() {
        $oQueryBuilder = Shopware()->Models()->createQueryBuilder();
        $aTaxes = $oQueryBuilder
            ->select('tax.id as value , tax.name as label')
            ->from('Shopware\Models\Tax\Tax', 'tax')->getQuery()->getArrayResult();
        return $aTaxes;
    }

    public function getPaymentMethodValues(){
        /** @var ML_Shopware_Helper_Model_Shop $shopHelper */
        $shopHelper = MLHelper::gi('model_shop');

        $oBuilder = Shopware()->Models()->createQueryBuilder()
            ->select(
                array(
                    'p.id as id',
                    'p.description as description',
                    'p.name as name',
                )
            )
            ->from('Shopware\Models\Payment\Payment', 'p');
//        $oBuilder->where('p.active = 1');

        $aPayments = $oBuilder->getQuery()->getArrayResult();

        return $shopHelper->getTranslatedValues('backend/static/payment', $aPayments);
    }
    
    public function getShopShippingModuleValues(){
        $oBuilder = Shopware()->Models()->createQueryBuilder()
                ->from('Shopware\Models\Dispatch\Dispatch', 'dispatches');
        $oBuilder->select(array(
            'id' => 'dispatches.id',
            'name' => 'dispatches.name',
        ));
//        $oBuilder->where('dispatches.active = 1');
        $aDispatchs = $oBuilder->getQuery()->getArrayResult();
        $aResult = array();
        foreach ($aDispatchs as $aDispatch) {
            $aResult[$aDispatch['id']] = $aDispatch['name'];
        }
        return $aResult;
    }
    
    public function getPossibleVariationGroupNames() {
        $aAttributes = array('' => MLI18n::gi()->get('ConfigFormPleaseSelect'));
        $aConfiguratorGroups = Shopware()->Db()->fetchAll('select id, name from '.Shopware()->Models()->getClassMetadata('Shopware\Models\Article\Configurator\Group')->getTableName());
        foreach ($aConfiguratorGroups as $aConfiguratorGroup) {
                $aAttributes['c_' . $aConfiguratorGroup['id']] = $aConfiguratorGroup['name'];
        }
        return $aAttributes;
    }
         
    public function manipulateForm(&$aForm) {
        try{
            parent::manipulateForm($aForm);
            MLModule::gi();
            $aForm = $this->manipulateShopwareSpecialPriceConfiguration($aForm);
            $aForm = $this->manipulateShopwareVatCustomerGroupConfiguration($aForm);

        } catch (Exception $ex) {

        }
    }

    public function getVatCustomerGroups() {
        return array('' => MLI18n::gi()->get('ML_LABEL_DONT_USE'))
            + MLFormHelper::getShopInstance()->getCustomerGroupsOfShopware();
    }

    protected function manipulateShopwareVatCustomerGroupConfiguration($form) {
        if (isset($form['mwst'])) {
            $form['mwst']['fields']['orderimport.vatcustomergroup'] = array(
                'i18n' => array(
                    'label' => MLI18n::gi()->get('orderimport_vatcustomergroup_label'),
                    'help' => MLI18n::gi()->get('orderimport_vatcustomergroup_help'),
                ),
                'name' => 'orderimport.vatcustomergroup',
                'type' => 'select',
                'expert' => false,
                'values' => $this->getVatCustomerGroups()
            );
        }

        return $form;
    }

    protected function manipulateShopwareSpecialPriceConfiguration($aForm) {
        //setting
        //ebay
        if (isset($aForm['fixedprice'])) {
            foreach ($aForm['fixedprice']['fields'] as $sKey => &$aField) {
                if ($aField['name'] == 'fixed.priceoptions') {
                    $aField['subfields']['usespecialoffer']['name'] = $this->replaceSpecialPriceConfigurationName($aField['subfields']['usespecialoffer']['name']);
                }
            }
        } 
        //ebay
        if (isset($aForm['chineseprice'])) {
            foreach ($aForm['chineseprice']['fields'] as $sKey => &$aField) {
                if ($aField['name'] == 'chinese.priceoptions') {
                    $aField['subfields']['usespecialoffer']['name'] = $this->replaceSpecialPriceConfigurationName($aField['subfields']['usespecialoffer']['name']);
                }
            }
        } 
        
        
        if (isset($aForm['price'])) {
            foreach ($aForm['price']['fields'] as $sKey => &$aField) {
            if ($aField['name'] == 'priceoptions' || $aField['name'] == 'b2b.priceoptions') {
                    $aField['subfields']['usespecialoffer']['name'] = $this->replaceSpecialPriceConfigurationName($aField['subfields']['usespecialoffer']['name']);
                }
            }
        }
        
        //I18n
        if (isset($aForm['field']) && isset($aForm['field']['priceoptions'])) {
            $aForm['field']['price.discountmode'] = array('label' => MLI18n::gi()->{'global_config_price_field_price.discountmode_label'});
        }

        // Form Groups on Metro or OTTO
        if (isset($aForm['price']['fields']['priceoptions']['subfields']['usespecialoffer']['i18n'])) {
            $aForm['price']['fields']['priceoptions']['subfields']['usespecialoffer']['i18n'] = array('label' => MLI18n::gi()->{'global_config_price_field_price.discountmode_label'});
        }

        if (isset($aForm['field']) && isset($aForm['field']['fixed.priceoptions'])) {
            $aForm['field']['fixed.price.discountmode'] = $aForm['field']['chinese.price.discountmode'] = array('label' => MLI18n::gi()->{'global_config_price_field_price.discountmode_label'});
        }
        
        if (isset($aForm['field']) && isset($aForm['field']['priceoptions'])) {
            $aForm['field']['b2b.price.discountmode'] = $aForm['field']['b2c.price.discountmode'] = array('label' => MLI18n::gi()->{'global_config_price_field_price.discountmode_label'});
        }
        return $aForm;
    }
    
    protected function replaceSpecialPriceConfigurationName($sName){
        return str_replace('usespecialoffer', 'discountmode', $sName);
    }

    /**
     * Returns grouped attributes for attribute matching
     *
     * @return array
     */
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

        // Product default fields
        $aShopDefaultFieldsAttributes = $this->getDefaultFieldsAttributes();
        if (!empty($aShopDefaultFieldsAttributes)) {
            $aShopDefaultFieldsAttributes['optGroupClass'] = 'default';
            $aShopAttributes += array(MLI18n::gi()->get('ProductDefaultFieldsOptGroup') => $aShopDefaultFieldsAttributes);
        }

        // Properties
        $aShopPropertiesAttributes = $this->getPropertiesAttributes();
        if (!empty($aShopPropertiesAttributes)) {
            $aShopPropertiesAttributes['optGroupClass'] = 'property';
            $aShopAttributes += array(MLI18n::gi()->get('PropertiesOptGroup') => $aShopPropertiesAttributes);
        }

        // Free text fields
        $aShopFreeTextFieldsAttributes = MLShopwareAlias::getProductHelper()->getFreeTextFieldsAttributes();
        if (!empty($aShopFreeTextFieldsAttributes)) {
            $aShopFreeTextFieldsAttributes['optGroupClass'] = 'freetext';
            $aShopAttributes += array(MLI18n::gi()->get('FreeTextAttributesOptGroup') => $aShopFreeTextFieldsAttributes);
        }

        return $aFirstElement + $aShopAttributes;
    }

    /**
     * Returns flat attribute if attribute code is sent and if not it returns all shop attributes for attribute matching
     * @param string $attributeCode
     * @param ML_Shopware_Model_Product|null $product If present attribute value will be set from given product
     * @return array|mixed
     */
    public function getFlatShopAttributesForMatching($attributeCode = null, $product = null)
    {
        $result =
            $this->getVariationAttributes()
            + $this->getDefaultFieldsAttributes()
            + $this->getPropertiesAttributes()
            + MLShopwareAlias::getProductHelper()->getFreeTextFieldsAttributes();

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
    private function getVariationAttributes() {
        $aShopVariationAttributes = array();
        $aConfiguratorGroups =
            Shopware()->Db()->fetchAll(
                'select id, name from '
                . Shopware()->Models()->getClassMetadata('Shopware\Models\Article\Configurator\Group')->getTableName()
            );
        $oProductHelper = MLHelper::gi('model_product');
        return $oProductHelper->getVariationGroupTranslation($aConfiguratorGroups);
    }

    /**
     * Returns default product fields for attribute matching
     *
     * @return array
     */
    private function getDefaultFieldsAttributes() {
        $aShopDefaultFieldsAttributes = array(
            'p_articleName' => array(
                'name' => MLI18n::gi()->get('ItemName'),
                'type' => 'text',
            ),
            'pd_Number'               => array(
                'name' => MLI18n::gi()->get('ItemNumber'),
                'type' => 'text',
            ),
            'p_description'           => array(
                'name' => MLI18n::gi()->get('ShortDescription'),
                'type' => 'text',
            ),
            'p_description_long'      => array(
                'name' => MLI18n::gi()->get('Description'),
                'type' => 'text',
            ),
            'pd_Ean'                  => array(
                'name' => MLI18n::gi()->get('EAN'),
                'type' => 'text',
            ),
            'pd_Weight'               => array(
                'name' => MLI18n::gi()->get('Product_DefaultAttribute_WeightValue'),
                'type' => 'text',
            ),
            'pd_WeightWithUnit'       => array(
                'name' => MLI18n::gi()->get('Product_DefaultAttribute_WeightWithUnit'),
                'type' => 'text',
            ),
            'pd_WeightUnit'           => array(
                'name' => MLI18n::gi()->get('Product_DefaultAttribute_WeightUnit'),
                'type' => 'text',
            ),
            'pd_WeightMultiplied1000' => array(
                'name' => MLI18n::gi()->get('WeightMultiplied1000'),
                'type' => 'text',
            ),
            'pd_BasePriceUnitName'    => array(
                'name' => MLI18n::gi()->get('Shopware_Product_DefaultAttribute_BasePriceUnitName'),
                'type' => 'text',
            ),
            'pd_BasePriceUnitShort'    => array(
                'name' => MLI18n::gi()->get('Shopware_Product_DefaultAttribute_BasePriceUnitShort'),
                'type' => 'text',
            ),
            'pd_BasePriceBasicUnit'    => array(
                'name' => MLI18n::gi()->get('Shopware_Product_DefaultAttribute_BasePriceBasicUnit'),
                'type' => 'text',
            ),
            'pd_BasePriceUnit'        => array(
                'name' => MLI18n::gi()->get('Shopware_Product_DefaultAttribute_BasePriceUnit'),
                'type' => 'text',
            ),
            'pd_BasePriceValue'       => array(
                'name' => MLI18n::gi()->get('Shopware_Product_DefaultAttribute_BasePriceValue'),
                'type' => 'text',
            ),
            'pd_Width'                => array(
                'name' => MLI18n::gi()->get('Product_DefaultAttribute_WidthValue'),
                'type' => 'text',
            ),
            'pd_Height'               => array(
                'name' => MLI18n::gi()->get('Product_DefaultAttribute_HeightValue'),
                'type' => 'text',
            ),
            'pd_Len'                  => array(
                'name' => MLI18n::gi()->get('Product_DefaultAttribute_LengthValue'),
                'type' => 'text',
            ),
            'sp_Supplier'             => array(
                'name' => MLI18n::gi()->get('Supplier'),
                'type' => 'select',
            ),
            'pd_Suppliernumber'       => array(
                'name' => MLI18n::gi()->get('SupplierNumber'),
                'type' => 'text',
            ),
            'pd_Releasedate'          => array(
                'name' => MLI18n::gi()->get('ReleaseDate'),
                'type' => 'text',
            ),
            'pd_Minpurchase'   => array(
                'name' => MLI18n::gi()->get('MinPurchase'),
                'type' => 'text',
            ),
            'pd_Maxpurchase'   => array(
                'name' => MLI18n::gi()->get('MaxPurchase'),
                'type' => 'text',
            ),
            'pd_Purchasesteps' => array(
                'name' => MLI18n::gi()->get('PurchaseSteps'),
                'type' => 'text',
            ),
            'p_pseudosales'    => array(
                'name' => MLI18n::gi()->get('PseudoSales'),
                'type' => 'text',
            ),
            'p_keywords'       => array(
                'name' => MLI18n::gi()->get('Keywords'),
                'type' => 'text',
            ),
        );

        if (MLSetting::gi()->dimensionUnit !== null) {
            $aShopDefaultFieldsAttributes = $aShopDefaultFieldsAttributes + array(
                    'pd_WidthWithUnit'  => array(
                        'name' => MLI18n::gi()->get('Product_DefaultAttribute_WidthWithUnit'),
                        'type' => 'text',
                    ),
                    'pd_WidthUnit'      => array(
                        'name' => MLI18n::gi()->get('Product_DefaultAttribute_WidthUnit'),
                        'type' => 'text',
                    ),
                    'pd_HeightWithUnit' => array(
                        'name' => MLI18n::gi()->get('Product_DefaultAttribute_HeightWithUnit'),
                        'type' => 'text',
                    ),
                    'pd_HeightUnit'     => array(
                        'name' => MLI18n::gi()->get('Product_DefaultAttribute_HeightUnit'),
                        'type' => 'text',
                    ),
                    'pd_LengthWithUnit' => array(
                        'name' => MLI18n::gi()->get('Product_DefaultAttribute_LengthWithUnit'),
                        'type' => 'text',
                    ),
                    'pd_LengthUnit'     => array(
                        'name' => MLI18n::gi()->get('Product_DefaultAttribute_LengthUnit'),
                        'type' => 'text',
                    ),
                );
        }
        return $aShopDefaultFieldsAttributes;
    }

    /**
     * Returns property attributes for attribute matching
     *
     * @return array
     */
    private function getPropertiesAttributes() {
        $aShopPropertiesAttributes = array();
        foreach (MLShopwareAlias::getProductHelper()->getAllProperties() as $aOption) {
            $aShopPropertiesAttributes["pp_{$aOption['id']}"] = array(
                'name' => $aOption['name'],
                'type' => 'multiSelect',
            );
        }

        return $aShopPropertiesAttributes;
    }

    /**
     * Returns free text fields for attribute matching
     *
     * @return bool
     */
    public function shouldBeDisplayedAsVariationAttribute($sAttributeKey) {
        return substr($sAttributeKey, 0, 2) === 'c_';
    }

    /**
     * Returns free text fields for attribute matching
     *
     * @return array
     */
    public function getOrderFreeTextFieldsAttributes() {
        $aShopFreeTextFieldsAttributes = array();
        // We need to be aware of Shopware version, because in version 5 free text fields are in different table
        try {
            // Shopware 5
            $aOpenTextFields =  MLDatabase::factorySelectClass()
                ->select('column_name AS name, label, column_type AS type')
                ->from(Shopware()->Models()->getClassMetadata('Shopware\Models\Attribute\Configuration')->getTableName())
                ->where(array('table_name' => 's_order_attributes'))
                ->getResult();
        } catch (Exception $ex) {
            // In Shopware 4 class Shopware\Models\Attribute\Configuration doesn't exist so exception will be thrown
            $aOpenTextFields = array();
        }

        foreach ($aOpenTextFields as $aOpenTextField) {

            $aShopFreeTextFieldsAttributes['a_'.$aOpenTextField['name']] = empty($aOpenTextField['label']) ? $aOpenTextField['name'] : $aOpenTextField['label'];
        }

        return $aShopFreeTextFieldsAttributes;
    }

    public function getShippingMethodValues() {
        return $this->getShopShippingModuleValues();
    }

    public function getDefaultCancelStatus() {
        return 4;
    }
}
