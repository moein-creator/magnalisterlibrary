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

use Redgecko\Magnalister\Controller\MagnalisterController;
use Shopware\Core\Checkout\Order\Aggregate\OrderTransaction\OrderTransactionStates;
use Shopware\Core\Checkout\Order\OrderStates;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Api\Context\SystemSource;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Sorting\FieldSorting;
use Shopware\Core\Framework\Uuid\Uuid;

MLFilesystem::gi()->loadClass('Shop_Model_ConfigForm_Shop_Abstract');

class ML_Shopware6_Model_ConfigForm_Shop extends ML_Shop_Model_ConfigForm_Shop_Abstract {

    protected $sPlatformName = '';
    public static $aLanguages = null;

    public function getDescriptionValues() {
        // $p=MagnalisterController::getShopwareMyContainer()->get('product.repository')->search(  $testy->addFilter(new EqualsFilter('product.searchKeywords.languageId', Context::createDefaultContext()->getLanguageId())),Context::createDefaultContext())->getEntities();                  
        //$criteria = new Criteria();
        //$locale =MagnalisterController::getShopwareMyContainer()->get('locale.repository')->search($criteria->addFilter(new EqualsFilter('locale.id', $aRow->getLocaleId())),Context::createDefaultContext())->getEntities(); 
        if (self::$aLanguages === null) {
            self::$aLanguages = array();
            $shop = MLShopware6Alias::getRepository('language.repository')->search(new Criteria(), Context::createDefaultContext())->getEntities();

            foreach ($shop as $aRow) {
                self::$aLanguages[$aRow->getId()] = $aRow->getName();
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
        $aShops = array();
        //here we don't use current language because, the name of sales-channel could be empty in that lang
        //        $context = new Context(
        //            new SystemSource(), [], Defaults::CURRENCY, [MagnalisterController::getShopwareLanguageId()], Defaults::LIVE_VERSION
        //        );
        foreach (MagnalisterController::getShopwareMyContainer()->get('sales_channel.repository')->search(new Criteria(), Context::createDefaultContext())->getEntities() as $oSaleChannel) {
            $aShops[$oSaleChannel->getId()] = $oSaleChannel->getName();
        }
        return $aShops;
    }

    /**
     * @param type $blNotLoggedIn
     * @return array
     * @todo : the user.repository should be checked
     */
    public function getCustomerGroupValues($blNotLoggedIn = false) {
        $aGroupsName = array();
        $lang = MLShopware6Alias::getRepository('language')->search((new Criteria())->addFilter(new EqualsFilter('locale.id', MagnalisterController::getShopwareLocaleId())), Context::createDefaultContext())->first();
        $context = new Context(
            new SystemSource(), [], Defaults::CURRENCY, [$lang->getId()], Defaults::LIVE_VERSION
        );
        $customerGroups = MagnalisterController::getShopwareMyContainer()->get('customer_group.repository')->search(new Criteria(), $context)->getEntities();
        foreach ($customerGroups as $aRow) {
            if ($aRow->getName() !== NULL) {
                $aGroupsName[$aRow->getId()] = $aRow->getName();
            } else {
                //If the "customer group" name didn't translate in the admin language then it translates  it in default language.
                $aGroupsName[$aRow->getId()] = MagnalisterController::getShopwareMyContainer()->get('customer_group.repository')->search(new Criteria(['id' => $aRow->getId()]), Context::createDefaultContext())->first()->getName();
            }
        }
        $aGroupsName['-'] = MLI18n::gi()->Shopware_Orderimport_CustomerGroup_Notloggedin;

        return $aGroupsName;
    }


    public function getDocumentTypeValues() {
        $aDocumentTypeName = array();
        $context = MLShopware6Alias::getContext(MagnalisterController::getShopwareLanguageId());
        $documentTypes = MLShopware6Alias::getRepository('document_type')->search(new Criteria(), $context)->getEntities();
        foreach ($documentTypes as $documentType) {
            try {
                $aDocumentTypeName[$documentType->getTechnicalName()] = $documentType->getName();
            } catch (\Throwable $ex) {
                MLMessage::gi()->addDebug($ex);
            }
        }
        return $aDocumentTypeName;
    }

    /**
     * @param bool $blNotLoggedIn
     * @return array
     */
    public function getAdvancedPriceRules($blNotLoggedIn = false) {
        $aGroupsName = array();
        $lang = MLShopware6Alias::getRepository('language')->search((new Criteria())->addFilter(new EqualsFilter('locale.id', MagnalisterController::getShopwareLocaleId())), Context::createDefaultContext())->first();
        $context = new Context(
            new SystemSource(), [], Defaults::CURRENCY, [$lang->getId()], Defaults::LIVE_VERSION
        );
        $customerGroups = MagnalisterController::getShopwareMyContainer()->get('rule.repository')->search(new Criteria(), $context)->getEntities();
        foreach ($customerGroups as $aRow) {
            $aGroupsName[$aRow->getId()] = $aRow->getName();

        }
        return $aGroupsName;
    }

    /**
     * @return array
     */
    public function getOrderStatusValues() {
        $lang = MLShopware6Alias::getRepository('language')->search((new Criteria())->addFilter(new EqualsFilter('locale.id', MagnalisterController::getShopwareLocaleId())), Context::createDefaultContext())->first();
        $context = new Context(
            new SystemSource(), [], Defaults::CURRENCY, [$lang->getId()], Defaults::LIVE_VERSION
        );
        $orderStatus = MagnalisterController::getShopwareStateMachineRegistry()->getStateMachine(OrderStates::STATE_MACHINE, $context)->getTransitions();

        foreach ($orderStatus->getElements() as $aRow) {
            if ($aRow->getFromStateMachineState()->getTranslated()['name'] !== null) {
                $aOrderStatesName[$aRow->getFromStateMachineState()->getTechnicalName()] = $aRow->getFromStateMachineState()->getName();
            } else {

                $aOrderStatesName[$aRow->getFromStateMachineState()->getTechnicalName()] = MagnalisterController::getShopwareMyContainer()->get('state_machine_state.repository')->search(new Criteria(['id' => $aRow->getFromStateMachineState()->getId()]), Context::createDefaultContext())->first()->getName();
            }
        }
        return $aOrderStatesName;
    }

    /**
     * @return type
     */
    public function getPaymentStatusValues() {
        $lang = MLShopware6Alias::getRepository('language')->search((new Criteria())->addFilter(new EqualsFilter('locale.id', MagnalisterController::getShopwareLocaleId())), Context::createDefaultContext())->first();
        $context = new Context(
            new SystemSource(), [], Defaults::CURRENCY, [$lang->getId()], Defaults::LIVE_VERSION
        );
        $paymentStates = MagnalisterController::getShopwareStateMachineRegistry()->getStateMachine(OrderTransactionStates::STATE_MACHINE, $context)->getTransitions();
        foreach ($paymentStates as $aRow) {
            if ($aRow->getFromStateMachineState()->getTranslated()['name'] !== null) {
                $aPaymentStatesName[$aRow->getFromStateMachineState()->getTechnicalName()] = $aRow->getFromStateMachineState()->getName();
            } else {
                $aPaymentStatesName[$aRow->getFromStateMachineState()->getTechnicalName()] = MagnalisterController::getShopwareMyContainer()->get('state_machine_state.repository')->search(new Criteria(['id' => $aRow->getFromStateMachineState()->getId()]), Context::createDefaultContext())->first()->getName();
            }
        }
        return $aPaymentStatesName;
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
        $iLangId = $this->getLanguage();
        $Language = MLShopware6Alias::getRepository('language.repository')->search((new Criteria())->addFilter(new EqualsFilter('id', $iLangId)), Context::createDefaultContext())->first();
        if ($Language === null) {
            $Language = MLShopware6Alias::getRepository('language.repository')->search((new Criteria()), Context::createDefaultContext())->first();
        }
        $locale = MLShopware6Alias::getRepository('locale.repository')->search((new Criteria())->addFilter(new EqualsFilter('locale.id', $Language->getLocaleId())), Context::createDefaultContext())->first();
        $LangCode = $locale->getCode();


        $CustomeFildSetEntites = MagnalisterController::getShopwareMyContainer()
            ->get('custom_field.repository')
            ->search((new Criteria())->addSorting(new FieldSorting('name', FieldSorting::ASCENDING)), MLShopware6Alias::getContextByLanguageId($iLangId))
            ->getEntities();

        $CustomFieldSetItems = array();
        if ($blAttributeMatching) {
            foreach ($CustomeFildSetEntites as $value) {
                if (!empty($value->getConfig())) {
                    $Label = '';
                    if (!empty($value->getConfig()['label']) && is_array($value->getConfig()['label'])) {
                        foreach ($value->getConfig()['label'] as $index => $value2) {
                            if ($LangCode === $index) {
                                $Label = $value2;
                            } elseif (!isset($value->getConfig()['label'][$LangCode])) {
                                $Label = $value2;
                            }
                        }
                    } else
                        MLMessage::gi()->addDebug(__LINE__ . ':' . microtime(true), array());
                    if (empty($Label)) {
                        $Label = $value->getName();
                    } else {
                        $Label .= ' ('.$value->getName().')';
                    }
                    if ($value->getType() == 'select') {
                        $CustomFieldSetItems['c_'.$value->getName()] = array(
                            'name' => $Label,
                            'type' => 'select',
                        );
                    } else {
                        $CustomFieldSetItems['c_'.$value->getName()] = array(
                            'name' => $Label,
                            'type' => 'text',
                        );
                    }
                }
            }
        } else {
            foreach ($CustomeFildSetEntites as $value) {
                if (!empty($value->getConfig())) {
                    $Label = '';
                    if (!empty($value->getConfig()['label']) && is_array($value->getConfig()['label'])) {
                        foreach ($value->getConfig()['label'] as $index => $value2) {
                            if ($LangCode === $index) {
                                $Label = $value2;
                            } elseif (!isset($value->getConfig()['label'][$LangCode])) {
                                $Label = $value2;
                            }
                        }
                    }
                    if (empty($Label)) {
                        $Label = $value->getName();
                    } else {
                        $Label .= ' - '.$value->getName();
                    }
                    $CustomFieldSetItems['c_'.$value->getName()] = $Label.' ('.MLI18n::gi()->get('FreeTextAttributesOptGroup').')';
                }
            }
        }

        return $CustomFieldSetItems;
    }

    /**
     * Returns free text fields for attribute matching
     *
     * @return array
     */
    public function getOrderFreeTextFieldsAttributes() {
        $aConfig = MLModule::gi()->getConfig();
        if (isset($aConfig['lang']) && $aConfig['lang'] != NULL) {
            $sLangId = $aConfig['lang'];
        } else {
            $sLangId = Defaults::LANGUAGE_SYSTEM;
        }
        $LanguageCriteria = new Criteria();
        $LanguageCriteria->addAssociations(['locale'])->addFilter(new EqualsFilter('id', $sLangId));
        $oLanguage = MLShopware6Alias::getRepository('language')->search($LanguageCriteria, MLShopware6Alias::getContext($sLangId))->first();
        $LangCode = $oLanguage->getLocale()->getId();
        $oCustomFieldCriteria = new Criteria();
        $oCustomFieldCriteria->addAssociations(['customFieldSet.relation'])->addFilter(new EqualsFilter('customFieldSet.relations.entityName', 'order'));
        $oCustomFieldEntities = MLShopware6Alias::getRepository('custom_field')
            ->search($oCustomFieldCriteria, MLShopware6Alias::getContextByLanguageId($sLangId))
            ->getEntities();

        $aCustomFields = array();
        foreach ($oCustomFieldEntities as $oCustomField) {
            /* @var $oCustomField \Shopware\Core\System\CustomField\CustomFieldEntity */
            if (!empty($oCustomField->getConfig())) {
                $Label = '';
                foreach ($oCustomField->getConfig()['label'] as $index => $value2) {
                    if ($LangCode === $index) {
                        $Label = $value2;
                    } elseif (!isset($oCustomField->getConfig()['label'][$LangCode])) {
                        $Label = $value2;
                    }
                }
                $aCustomFields['a_'.$oCustomField->getName()] = $Label;
            }
        }

        return $aCustomFields;
    }

    /**
     *
     * @return array
     */
    public function getProductFreeTextFieldsAttributes($blAttributeMatching = false) {

        $aConfig = MLModule::gi()->getConfig();
        if (isset($aConfig['lang']) && $aConfig['lang'] != NULL) {
            $sLangId = $aConfig['lang'];
        } else {
            $sLangId = Defaults::LANGUAGE_SYSTEM;
        }
        $LanguageCriteria = new Criteria();
        $LanguageCriteria->addAssociations(['locale'])->addFilter(new EqualsFilter('id', $sLangId));
        $oLanguage = MLShopware6Alias::getRepository('language')->search($LanguageCriteria, MLShopware6Alias::getContext($sLangId))->first();
        $LangCode = $oLanguage->getLocale()->getId();
        $oCustomFieldCriteria = new Criteria();
        $oCustomFieldCriteria->addAssociations(['customFieldSet.relation'])->addFilter(new EqualsFilter('customFieldSet.relations.entityName', 'product'));
        $oCustomFieldEntities = MLShopware6Alias::getRepository('custom_field')
            ->search($oCustomFieldCriteria, MLShopware6Alias::getContextByLanguageId($sLangId))
            ->getEntities();

        $aCustomFields = array();
        foreach ($oCustomFieldEntities as $oCustomField) {
            /* @var $oCustomField \Shopware\Core\System\CustomField\CustomFieldEntity */
            if (!empty($oCustomField->getConfig())) {
                $Label = '';
                foreach ($oCustomField->getConfig()['label'] as $index => $value2) {
                    if ($LangCode === $index) {
                        $Label = $value2;
                    } elseif (!isset($oCustomField->getConfig()['label'][$LangCode])) {
                        $Label = $value2;
                    }
                }
                $aCustomFields['a_'.$oCustomField->getName()] = $Label;
            }
        }

        return $aCustomFields;
    }

    /**
     * If $blAttributeMatching is false, the value is the variation name, otherwise it will return an array with the
     * name and the type 'select'.
     *
     * @param bool $blAttributeMatching
     * @return array
     */
    protected function getProductPropertiesGroupVariations($blAttributeMatching = false) {
        $iLangId = $this->getLanguage();
        $propertyGroupRepo = MagnalisterController::getShopwareMyContainer()->get('property_group.repository');
        $VariationsGroupEntities = $propertyGroupRepo
            ->search(new Criteria(), MLShopware6Alias::getContextByLanguageId($iLangId))->getEntities();

        $VariationsGroupItems = array();
        foreach ($VariationsGroupEntities as $value) {
            if ($value->getName() !== null) {
                $VariationsGroupItems['a_'.$value->getId()] = $value->getName();
            } else {
                $DefaultLangCriteria = new Criteria();
                $VariationsGroupItems['a_'.$value->getId()] = $propertyGroupRepo
                    ->search($DefaultLangCriteria->addFilter(new EqualsFilter('id', $value->getId())), MLShopware6Alias::getContextByLanguageId())
                    ->first()->getName();
            }
        }

        if ($blAttributeMatching) {
            // expand the value for attribute matching
            foreach ($VariationsGroupItems as $idx => $item) {
                $VariationsGroupItems[$idx] = [
                    'name' => $item,
                    'type' => 'select'
                ];
            }
        }

        return $VariationsGroupItems;
    }

    public function getLanguage() {
        try {
            $aConfig = MLModule::gi()->getConfig();
        } catch (\Exception $ex) {

        }
        if (isset($aConfig['lang']) && $aConfig['lang'] != NULL && Uuid::isValid($aConfig['lang'])) {
            $iLangId = $aConfig['lang'];
        } else {
            $iLangId = Defaults::LANGUAGE_SYSTEM;
        }
        return $iLangId;
    }

    /**
     * @return type
     * @todo Done
     */
    protected function getProductPropertiesGroup($blAttributeMatching = false) {
        $iLangId = $this->getLanguage();
        $PropertiesGroupEntites = MagnalisterController::getShopwareMyContainer()->get('property_group.repository')->search(new Criteria(), MLShopware6Alias::getContextByLanguageId($iLangId))->getEntities();

        $PropertiesGroupItems = array();
        if ($blAttributeMatching) {
            foreach ($PropertiesGroupEntites as $value) {
                if ($value->getName() !== null) {
                    $PropertiesGroupItems['p_'.$value->getId()] = array(
                        'name' => $value->getName(),
                        'type' => 'select',
                    );
                } else {
                    $DefaultLangCriteria = new Criteria();
                    $PropertiesGroupItems['p_'.$value->getId()] = array(
                        'name' => MagnalisterController::getShopwareMyContainer()->get('property_group.repository')->search($DefaultLangCriteria->addFilter(new EqualsFilter('id', $value->getId())), MLShopware6Alias::getContextByLanguageId())->first()->getName(),
                        'type' => 'select',
                    );
                }
            }
        } else {
            foreach ($PropertiesGroupEntites as $value) {
                $PropertiesGroupItems['p_'.$value->getId()] = $value->getName().' ('.MLI18n::gi()->get('PropertiesOptGroup').')';
            }
        }
        return $PropertiesGroupItems;
    }

    /**
     * @return type
     * @throws MLAbstract_Exception
     */
    protected function getProductFields($blAttributeMatching = false) {

        if ($blAttributeMatching) {
            $productProperty = array(
                'Name'                 => array(
                    'name' => MLI18n::gi()->get('ProductName'),
                    'type' => 'text',
                ),
                'ProductNumber'        => array(
                    'name' => MLI18n::gi()->get('ProductNumber'),
                    'type' => 'text',
                ),
                'Description'          => array(
                    'name' => MLI18n::gi()->get('Description'),
                    'type' => 'text',
                ),
                'Ean'                  => array(
                    'name' => MLI18n::gi()->get('EAN'),
                    'type' => 'text',
                ),
                'PurchaseUnit'               => array(
                    'name' => MLI18n::gi()->get('PurchaseUnit'),
                    'type' => 'text',
                ),
                'UnitId'               => array(
                    'name' => MLI18n::gi()->get('UnitId'),
                    'type' => 'select',
                ),
                'Weight'               => array(
                    'name' => MLI18n::gi()->get('Product_DefaultAttribute_WeightValue'),
                    'type' => 'text',
                ),
                'Weight_ValueWithUnit' => array(
                    'name' => MLI18n::gi()->get('Product_DefaultAttribute_WeightWithUnit'),
                    'type' => 'text',
                ),
                'Weight_Unit'          => array(
                    'name' => MLI18n::gi()->get('Product_DefaultAttribute_WeightUnit'),
                    'type' => 'text',
                ),
                'ManufacturerNumber'   => array(
                    'name' => MLI18n::gi()->get('ManufacturerNumber'),
                    'type' => 'select',
                ),
                'ManufacturerId'       => array(
                    'name' => MLI18n::gi()->get('ManufacturerId'),
                    'type' => 'select',
                ),
                'Width'                => array(
                    'name' => MLI18n::gi()->get('Product_DefaultAttribute_WidthValue'),
                    'type' => 'text',
                ),
                'Width_ValueWithUnit'  => array(
                    'name' => MLI18n::gi()->get('Product_DefaultAttribute_WidthWithUnit'),
                    'type' => 'text',
                ),
                'Width_Unit'           => array(
                    'name' => MLI18n::gi()->get('Product_DefaultAttribute_WidthUnit'),
                    'type' => 'text',
                ),
                'Height'             => array(
                    'name' => MLI18n::gi()->get('Product_DefaultAttribute_HeightValue'),
                    'type' => 'text',
                ),
                'Height_ValueWithUnit' => array(
                    'name' => MLI18n::gi()->get('Product_DefaultAttribute_HeightWithUnit'),
                    'type' => 'text',
                ),
                'Height_Unit'          => array(
                    'name' => MLI18n::gi()->get('Product_DefaultAttribute_HeightUnit'),
                    'type' => 'text',
                ),
                'Length'             => array(
                    'name' => MLI18n::gi()->get('Product_DefaultAttribute_LengthValue'),
                    'type' => 'text',
                ),
                'Length_ValueWithUnit' => array(
                    'name' => MLI18n::gi()->get('Product_DefaultAttribute_LengthWithUnit'),
                    'type' => 'text',
                ),
                'Length_Unit'          => array(
                    'name' => MLI18n::gi()->get('Product_DefaultAttribute_LengthUnit'),
                    'type' => 'text',
                ),
            );
        } else {
            $productProperty = array(
                'Name'               =>
                    MLI18n::gi()->get('ProductName'),
                'ProductNumber'      =>
                    MLI18n::gi()->get('ProductNumber'),
                'Description'        =>
                    MLI18n::gi()->get('Description'),
                'Ean'                =>
                    MLI18n::gi()->get('EAN'),
                'PurchaseUnit'                =>
                    MLI18n::gi()->get('PurchaseUnit'),
                'UnitId'                =>
                    MLI18n::gi()->get('UnitId'),
                'Weight'             =>
                    MLI18n::gi()->get('Product_DefaultAttribute_Weight'),
                'Width'              =>
                    MLI18n::gi()->get('Product_DefaultAttribute_WidthValue'),
                'WidthWithUnit'      =>
                    MLI18n::gi()->get('Product_DefaultAttribute_WidthWithUnit'),
                'WidthUnit'          =>
                    MLI18n::gi()->get('Product_DefaultAttribute_WidthUnit'),
                'Height'             =>
                    MLI18n::gi()->get('Product_DefaultAttribute_HeightValue'),
                'HeightWithUnit'     =>
                    MLI18n::gi()->get('Product_DefaultAttribute_HeightWithUnit'),
                'HeightUnit'         =>
                    MLI18n::gi()->get('Product_DefaultAttribute_HeightUnit'),
                'Length'             =>
                    MLI18n::gi()->get('Product_DefaultAttribute_LengthValue'),
                'LengthUnitName'     =>
                    MLI18n::gi()->get('Product_DefaultAttribute_LengthWithUnit'),
                'LengthUnit'         =>
                    MLI18n::gi()->get('Product_DefaultAttribute_LengthUnit'),
                'ManufacturerNumber' =>
                    MLI18n::gi()->get('ManufacturerNumber'),
                'ManufacturerId'     =>
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
            array('' => MLI18n::gi()->get('ConfigFormPleaseSelect')), $this->getProductFields(), $this->getCustomFields(), $this->getProductPropertiesGroupVariations(), $this->getProductPropertiesGroup()
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
        $aCurrencyModel = MagnalisterController::getShopwareMyContainer()->get('currency.repository')->search(new Criteria(), Context::createDefaultContext())->getEntities();
        $aCurrency = array();
        foreach ($aCurrencyModel as $aCur) {
            $aCurrency[$aCur->getId()] = $aCur->getisoCode();
        }
        return $aCurrency;
    }

    /**
     * @param false $getProperties
     * @return array
     * @throws MLAbstract_Exception
     */
    public function getPrefixedAttributeList($getProperties = false) {
        return $this->getListOfArticleFields();
    }
    //
    //    /**
    //     * @return array
    //     */
    //    public function getAttributeListWithOptions() {
    //        $aAttributes = $this->getPossibleVariationGroupNames();
    //        foreach ($aAttributes as $sKey => $sAttribute) {
    //            $aAttributes[$sKey] = mb_convert_encoding($sAttribute, 'HTML-ENTITIES');
    //        }
    //        return $aAttributes;
    //    }

    public function getAttributeOptions($sAttributeCode, $iLangId = null) {
        $aAttributeCode = explode('_', $sAttributeCode, 2);
        $attributes = array();
        $iLangId = $this->getLanguage();
        $oContext = MLShopware6Alias::getContext($iLangId);
        // Getting values for Variation and properties attributes
        if (in_array($aAttributeCode[0],  ['a', 'p'], true)) {
            $VariationGroupCriteria = new Criteria();
            $VariationGroupCriteria->addFilter(new EqualsFilter('groupId', $aAttributeCode[1]));
            // Sorting variation attributes in attribute matching by name and position
            $VariationGroupCriteria->addSorting(new FieldSorting('position', FieldSorting::ASCENDING));
            $VariationGroupCriteria->addSorting(new FieldSorting('name', FieldSorting::ASCENDING));
            $VariationConfiguratorOptions = MLShopware6Alias::getRepository('property_group_option')->search($VariationGroupCriteria, $oContext)->getEntities();
            foreach ($VariationConfiguratorOptions as $VariationConfiguratorOption) {
                /** @var $VariationConfiguratorOption Shopware\Core\Content\Property\Aggregate\PropertyGroupOption\PropertyGroupOptionEntity */
                $attributes[$VariationConfiguratorOption->getId()] = $VariationConfiguratorOption->getName();
            }
            $attributes = $this->fillMissingTranslationForPropertyGroupOption($attributes);
        }
        // Getting values for custom field (there is combobox type in Shopware 5 which acts like single selection)
        if ($aAttributeCode[0] === 'c') {
            $CustomFieldsCriteria = new Criteria();
            $tableName = MLShopware6Alias::getRepository('custom_field')->getDefinition()->getEntityName();
            $CustomFields = MLDatabase::getDbInstance()->fetchArray("SELECT * FROM `$tableName` WHERE name='{$aAttributeCode[1]}'");
            foreach ($CustomFields as $CustomField) {
                if (!empty($CustomField['config'])) {
                    $config = json_decode($CustomField['config'], true);
                    if(isset($config['options']) && is_array($config['options'])) {
                        foreach ($config['options'] as $option) {
                            $attributeLabel = '';
                            if(isset($option['label']['en-GB'])){
                                $attributeLabel = $option['label']['en-GB'];
                            }
                            if (empty($attributeLabel)) {
                                if (is_array($option['label'])) {
                                    $attributeLabel = current($option['label']);
                                }
                            }
                            if (empty($attributeLabel) && isset($option['value'])) {
                                $attributeLabel = $option['value'];
                            }
                            $attributes[$attributeLabel] = $attributeLabel;
                        }
                    }
                }
            }
        }
        if ($sAttributeCode == 'ManufacturerId') {
            $ManufacturerCriteria = new Criteria();
            $Manufacturer = MagnalisterController::getShopwareMyContainer()
                ->get('product_manufacturer.repository')
                ->search($ManufacturerCriteria, $oContext)
                ->getEntities();
            foreach ($Manufacturer as $ManufacturValue) {
                $attributes[$ManufacturValue->getId()] = $ManufacturValue->getName();
            }
        }
        if ($sAttributeCode == 'UnitId') {
            $UnitsCriteria = new Criteria();
            $Units = MagnalisterController::getShopwareMyContainer()
                ->get('unit.repository')
                ->search($UnitsCriteria, $oContext)
                ->getEntities();
            foreach ($Units as $UnitsValue) {
                $attributes[$UnitsValue->getId()] = $UnitsValue->getName();
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
     * Return a list of tax classes id and name pair in Shopware
     * @return array
     */
    public function getTaxClasses() {
        $aTaxes = array();
        foreach (MagnalisterController::getShopwareMyContainer()->get('tax.repository')->search(new Criteria(), Context::createDefaultContext())->getEntities() as $oTax) {
            $aTaxes[] = ['value' => $oTax->getId(), 'label' => $oTax->getName()];
        }
        return $aTaxes;
    }

    public function getPaymentMethodValues() {
        $iLangId = $this->getLanguage();
        $aPayments = MagnalisterController::getShopwareMyContainer()->get('payment_method.repository')->search(new Criteria(), MLShopware6Alias::getContextByLanguageId($iLangId))->getEntities();
        $aResult = array();
        foreach ($aPayments as $aPayment) {
            if ($aPayment->getName() !== NULL) {
                $aResult[$aPayment->getId()] = $aPayment->getName();
            } else {
                $CriteriaPayment = new Criteria();
                $ReplaceEmptyTranslationPayment = MagnalisterController::getShopwareMyContainer()->get('payment_method.repository')->search($CriteriaPayment->addFilter(new EqualsFilter('id', $aPayment->getId())), Context::createDefaultContext())->first();
                $aResult[$aPayment->getId()] = $ReplaceEmptyTranslationPayment->getName();
            }
        }
        return $aResult;
    }

    public function getShippingMethodValues() {
        return $this->getShopShippingModuleValues();
    }

    public function getShopShippingModuleValues() {
        $iLangId = $this->getLanguage();
        $Shipping = MagnalisterController::getShopwareMyContainer()->get('shipping_method.repository')->search(new Criteria(), MLShopware6Alias::getContextByLanguageId($iLangId))->getEntities();

        $aResult = array();
        foreach ($Shipping as $aShipping) {
            if ($aShipping->getName() !== NULL) {
                $aResult[$aShipping->getId()] = $aShipping->getName();
            } else {
                $CriteriaShipping = new Criteria();
                $ReplaceEmptyTranslationShipping = MagnalisterController::getShopwareMyContainer()->get('shipping_method.repository')->search($CriteriaShipping->addFilter(new EqualsFilter('id', $aShipping->getId())), Context::createDefaultContext())->first();
                $aResult[$aShipping->getId()] = $ReplaceEmptyTranslationShipping->getName();
            }
        }
        return $aResult;
    }

    /**
     * @return array
     * array(
     * "attribute-id"=>"color",...
     * )
     * @todo using  property_group.repository
     */
    public function getPossibleVariationGroupNames() {

        $aAttributes = array('' => MLI18n::gi()->get('ConfigFormPleaseSelect'));
        $aConfiguratorGroups = MagnalisterController::getShopwareMyContainer()->get('property_group.repository')->search(new Criteria(), Context::createDefaultContext())->getEntities();
        foreach ($aConfiguratorGroups as $aConfiguratorGroup) {
            $aAttributes['a_'.$aConfiguratorGroup->getId()] = $aConfiguratorGroup->getName();
        }
        return $aAttributes;
    }

    /**
     * @return type
     * @todo
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
     * @todo to be checked
     */
    public function getFlatShopAttributesForMatching($attributeCode = null, $product = null) {
        $result = $this->getProductPropertiesGroupVariations(true) + $this->getProductPropertiesGroup(true) + $this->getProductFields(true) +
            $this->getCustomFields(true);

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
            $aForm = $this->manipulateFormAfterNormalizeEbayProductTemplate($sController, $aForm);
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
                        if (isset($aForm[$sKey]['fields'][$sInnerKey]['realname'])) {
                            $sRealName = $aForm[$sKey]['fields'][$sInnerKey]['realname'];
                            $sHelpText = MLI18n::gi()->get('Shopware6_eBay_Marketplace_Configuration_fixedPriceoptions_help');
                            $sLabelText = MLI18n::gi()->get('Shopware6_eBay_Marketplace_Configuration_fixedPriceoptions_label');
                            if (in_array($sRealName, ['fixed.priceoptions']) !== false) {
                                $aForm[$sKey]['fields'][$sInnerKey]['i18n']['help'] = $sHelpText;
                                $aForm[$sKey]['fields'][$sInnerKey]['i18n']['label'] = $sLabelText;
                                $aForm[$sKey]['fields'][$sInnerKey]['subfields']['group']['values'] = $this->getAdvancedPriceRules();
                            } else if (
                                (strpos($sRealName, 'strikepriceoptions') !== false)
                                ||
                                (strpos($sRealName, 'b2b.priceoptions') !== false)
                            ) {
                                $aForm[$sKey]['fields'][$sInnerKey]['subfields']['group']['values'] = $this->getAdvancedPriceRules();
                            } else if (strpos($sRealName, 'chinese.priceoptions') !== false) {
                                $aForm[$sKey]['fields'][$sInnerKey]['i18n']['help'] = $sHelpText;
                                $aForm[$sKey]['fields'][$sInnerKey]['i18n']['label'] = MLI18n::gi()->get('Shopware6_eBay_Marketplace_Configuration_chinesePriceoptions_label');
                                $aForm[$sKey]['fields'][$sInnerKey]['subfields']['group']['values'] = $this->getAdvancedPriceRules();
                            } else if (strpos($sRealName, 'priceoptions') !== false) {
                                $aForm[$sKey]['fields'][$sInnerKey]['i18n']['help'] = $sHelpText;
                                $aForm[$sKey]['fields'][$sInnerKey]['i18n']['label'] = $sLabelText;
                                $aForm[$sKey]['fields'][$sInnerKey]['subfields']['group']['values'] = $this->getAdvancedPriceRules();
                            } else if (strpos($sRealName, 'volumepriceswebshopcustomergroup') !== false) {
                                $aForm[$sKey]['fields'][$sInnerKey]['i18n']['help'] = $sHelpText;
                                $aForm[$sKey]['fields'][$sInnerKey]['i18n']['label'] = $sLabelText;
                                $aForm[$sKey]['fields'][$sInnerKey]['values'] = $this->getAdvancedPriceRules();
                            } else if (in_array($sRealName,
                                    ['volumepriceprice2', 'volumepriceprice3', 'volumepriceprice4', 'volumepriceprice5', 'volumepricepricea', 'volumepricepriceb']//METRO volume price
                                ) !== false) {
                                $aForm[$sKey]['fields'][$sInnerKey]['subfields']['CustomerGroup']['values'] = $this->getAdvancedPriceRules();
                            }
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

   public function getCustomFieldsList(){
       $CustomeFildSetEntites = MagnalisterController::getShopwareMyContainer()
           ->get('custom_field.repository')
           ->search((new Criteria())->addSorting(new FieldSorting('name', FieldSorting::ASCENDING)), Context::createDefaultContext())
           ->getEntities();
       return $CustomeFildSetEntites;
   }
    protected function manipulateFormAfterNormalizeEbayProductTemplate($sController, array $aForm): array {
        if (MLModule::gi()->getMarketPlaceName() === 'ebay' && strpos($sController, '_config_producttemplate') !== false) {
           $aForm['product']['fields']['template.tabs']['subfields']['template.content']['i18n']['hint'] =
           $this->addCustomFieldsToDescriptionHint($aForm['product']['fields']['template.tabs']['subfields']['template.content']['i18n']['hint']);
        }
        return $aForm;
    }

    public function addCustomFieldsToDescriptionHint(string $aField): string {
        $sHint = str_replace('</dl></dl>', '', $aField);
        $sCustomFieldInfo = MLI18n::gi()->ebay_prepare_apply_form_field_description_hint_customfield;
        $i = 5;
        foreach ($this->getCustomFieldsList() as $oCustomField) {
            if ($i === 0) {
                break;
            }
            $aCustomFieldsKeys = $oCustomField->getName();
            $sCustomFieldInfo .= '<dd>#LABEL_' . $aCustomFieldsKeys .'# #VALUE_'.$aCustomFieldsKeys. '#</dd><br />';
            $i--;
        }
        return $sHint . $sCustomFieldInfo . '</dl></dl>';
    }

    public function getDefaultCancelStatus() {
        return 'cancelled';
    }

    protected function fillMissingTranslationForPropertyGroupOption(array $attributes) {
        $attributeWithEmptyNames = array();
        foreach ($attributes as $attributeKey => &$attribute) {
            if(in_array($attribute, ['', null], true)){
                $attributeWithEmptyNames[$attributeKey] = $attribute;
            }
        }
        if(count($attributeWithEmptyNames) > 0){
            foreach ($attributeWithEmptyNames as $attributeKey => $attributeWithEmptyName) {
                if (!isset(self::$aPropertyGroupOptionTranslations[$attributeKey])) {
                    $this->populatePropertyGroupTranslationCache(array_keys($attributes));
                }
                if (isset(self::$aPropertyGroupOptionTranslations[$attributeKey])) {
                    if(isset(self::$aPropertyGroupOptionTranslations[$attributeKey][$this->getLanguage()])){
                        $attributes[$attributeKey] = self::$aPropertyGroupOptionTranslations[$attributeKey][$this->getLanguage()];
                    } else if(isset(self::$aPropertyGroupOptionTranslations[$attributeKey][Defaults::LANGUAGE_SYSTEM])) {
                        $attributes[$attributeKey] = self::$aPropertyGroupOptionTranslations[$attributeKey][Defaults::LANGUAGE_SYSTEM];
                    }else if(isset(self::$aPropertyGroupOptionTranslations[$attributeKey][MagnalisterController::getShopwareLanguageId()])) {
                        $attributes[$attributeKey] = self::$aPropertyGroupOptionTranslations[$attributeKey][MagnalisterController::getShopwareLanguageId()];
                    }else {
                        $attributes[$attributeKey] = current(self::$aPropertyGroupOptionTranslations[$attributeKey]);
                    }
                }
            }
        }

        return $attributes;
    }
    static protected $aPropertyGroupOptionTranslations = array();
    protected function populatePropertyGroupTranslationCache(array $keys) {
        foreach ($keys as $key){//initialize all searching attribute keys with empty to prevent to search them again in database
            self::$aPropertyGroupOptionTranslations[$key] = [];
        }
        $sWhere = "'".implode("', '", $keys)."'";
        $tableName = MLShopware6Alias::getRepository('property_group_option_translation')->getDefinition()->getEntityName();
        $aTranslationsData = MLDatabase::getDbInstance()->fetchArray("SELECT HEX(`property_group_option_id`) as id, HEX(`language_id`) as laguageID, name FROM `$tableName` WHERE HEX(`property_group_option_id`) IN ($sWhere)");
        foreach ($aTranslationsData as $aTranslation) {
            self::$aPropertyGroupOptionTranslations[strtolower($aTranslation['id'])][strtolower($aTranslation['laguageID'])] = $aTranslation['name'];
        }
    }
}
