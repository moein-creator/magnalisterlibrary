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

class ML_Shopware_Helper_Model_Product {

    protected static $aMarketplacesLanguage = array();
    protected static $aTranslatedProperties = null;

    protected static function getMarketplaceLanguage(){
        $sMessage = '';
        try {
            $iLanguage = Shopware()->Shop()->getId();
        } catch (\Exception $ex) {
            $sMessage = $ex->getMessage() . ':' . $ex->getFile() . ':' . $ex->getLine();
            MLMessage::gi()->addDebug($ex);
            $iLanguage = null;
        }
        if ($iLanguage === null) {
            $sMarketplace = MLModule::gi()->getModuleBaseUrl();
            $sController = MLRequest::gi()->get('controller');
            if (strpos($sController, $sMarketplace) !== false && !MLHttp::gi()->isAjax()) {
                MLHttp::gi()->redirect(MLHttp::gi()->getUrl(array('controller' => $sMarketplace . '_config_prepare')), 302, $sMessage);
            } else {
                throw new Exception('Language for product description should be configured again:' . MLHttp::gi()->getUrl(array('controller' => $sMarketplace . '_config' . MLModule::gi()->getPriceConfigurationUrlPostfix())));
            }
        }

        try {
            $iMarketplaceId = MLModule::gi()->getMarketPlaceId();
            if(!isset(self::$aMarketplacesLanguage[$iMarketplaceId])) {

                $aConf = MLModule::gi()->getConfig();
                //set shopware default shop
                self::$aMarketplacesLanguage[$iMarketplaceId] = (int)$aConf['lang'];
            }
            $iLanguage = self::$aMarketplacesLanguage[$iMarketplaceId];
        } catch (Exception $oExc) {
            MLMessage::gi()->addDebug($oExc);
        }

        return $iLanguage;
    }

    public function getVariationGroupTranslation($aConfiguratorGroups) {
        $aShopVariationAttributes = array();
        if (version_compare(MLSHOPWAREVERSION, '5.6.0', '>=')) {
            $oTranslation = new \Shopware_Components_Translation(Shopware()->Container()->get('dbal_connection'), Shopware()->Container());
        } else {
            $oTranslation = new \Shopware_Components_Translation();
        }

        $iLanguage = self::getMarketplaceLanguage();

        foreach ($aConfiguratorGroups as $aConfiguratorGroup) {
            $aGroupTranslated = $oTranslation->read($iLanguage, 'configuratorgroup', $aConfiguratorGroup['id']);

            if (empty($aGroupTranslated) || !isset($aGroupTranslated['name'])) {
                $aShopVariationAttributes['c_' . $aConfiguratorGroup['id']] = array(
                    'name' => $aConfiguratorGroup['name'],
                    'type' => 'select',
                );
            }else {
                $aShopVariationAttributes['c_' . $aConfiguratorGroup['id']] = array(
                    'name' => $aGroupTranslated['name'],
                    'type' => 'select',
                );
            }
        }
        return $aShopVariationAttributes;
    }

    public function getVariationOptionsTranslation($configuratorOptions) {
        $attributes = array();
        if (version_compare(MLSHOPWAREVERSION, '5.6.0', '>=')) {
            $oTranslation = new \Shopware_Components_Translation(Shopware()->Container()->get('dbal_connection'), Shopware()->Container());
        } else {
            $oTranslation = new \Shopware_Components_Translation();
        }

        $iLanguage = self::getMarketplaceLanguage();
        foreach ($configuratorOptions as &$configuratorOption) {
            $aGroupTranslated = $oTranslation->read($iLanguage, 'configuratoroption', $configuratorOption['id']);
            if (empty($aGroupTranslated) || !isset($aGroupTranslated['name'])) {
                $attributes[] = array('id'=>$configuratorOption['id'],'name'=> $configuratorOption['name']);

            }else{
                $attributes[] = array('id'=>$configuratorOption['id'],'name'=> $aGroupTranslated['name']);
            }
        }
        return $attributes;
    }

    public function getProductSelectQuery() {
        return MLDatabase::factorySelectClass()
                        ->select('DISTINCT p.id')
                        ->from(Shopware()->Models()->getClassMetadata('Shopware\Models\Article\Article')->getTableName(), 'p')
                        ->join(array(Shopware()->Models()->getClassMetadata('Shopware\Models\Article\Detail')->getTableName(), 'details', 'p.id = details.articleid AND p.main_detail_id = details.id '), ML_Database_Model_Query_Select::JOIN_TYPE_INNER)
                        ->join(array(Shopware()->Models()->getClassMetadata('Shopware\Models\Article\Price')->getTableName(), 'pp', 'pp.articledetailsID = details.id'), ML_Database_Model_Query_Select::JOIN_TYPE_INNER);
    }

    /**
     * Get name from a certain article by ordernumber
     * @param string $ordernumber
     * @param bool $returnAll return only name or additional data, too
     * @access public
     * @return string or array
     */
    public function getTranslatedInfo($ordernumber) {
        $checkForArticle = Shopware()->Db()->fetchRow("
            SELECT s_articles.id, s_articles.name AS articleName, description ,description_long,keywords  FROM s_articles WHERE
            id=?
		", array($ordernumber));
        if (!empty($checkForArticle)) {
            return Shopware()->Modules()->Articles()->sGetTranslation($checkForArticle, $checkForArticle["id"], "article");
        } else {
            return false;
        }
    }

    /**
     * use sConfigurator::getDefaultPrices
     * @param int $detailId
     * @return float
     * @throws Exception
     */
    public function getDefaultPrices($detailId, $sUserGroup = null) {
        if ($sUserGroup === null) {
            $sUserGroup = Shopware()->System()->sUSERGROUPDATA['key'];
        }

        $aPriceRows = MLDatabase::getDbInstance()->fetchArray(
            'SELECT price FROM `s_articles_prices` 
                WHERE `articledetailsID` = '.(int)$detailId." AND `pricegroup` = '".MLDatabase::getDbInstance()->escape($sUserGroup)."' ORDER BY `from` ASC");

        if (    $sUserGroup == 'EK'
             && (count($aPriceRows) <= 0 || $aPriceRows[0]['price'] == 0)) {
            throw new Exception('Error to get Price : there is no Price for this product. Detail id = '.$detailId);
        }

        // especially in shopware if the price is on all customer groups the same only at EK group the price is set - so try to load it from there
        if (count($aPriceRows) > 0) {
            $aPrice = array_shift($aPriceRows);
            if ($aPrice['price'] == 0) {
                $aPrice = array('price' => $this->getDefaultPrices($detailId, 'EK'));
            }
        } else {
            $aPrice = array('price' => $this->getDefaultPrices($detailId, 'EK'));
        }
        if (empty($aPrice['price'])) {
            throw new Exception('The price of product could not be empty', 1548775463);
        }
        return $aPrice['price'];
    }

    /**
     * use sConfigurator::getDefaultPrices
     * @param int $detailId
     * @return array
     * @throws Exception
     */
    public function getVolumePrices($detailId, $sUserGroup = null) {
        if ($sUserGroup === null) {
            $sUserGroup = Shopware()->System()->sUSERGROUPDATA['key'];
        }

        $aPriceRows = MLDatabase::getDbInstance()->fetchArray("
            SELECT * 
              FROM `s_articles_prices` 
             WHERE     `articledetailsID` = ".(int)$detailId." 
                   AND `pricegroup` = '".MLDatabase::getDbInstance()->escape($sUserGroup)."' 
          ORDER BY `from` ASC
        ");

        return $aPriceRows;
    }

    /**
     * return all variants related to this product id
     * @param int $iProductId
     * @return array
     */
    public function getProductDetails($iProductId) {
        $oShopwareProduct = Shopware()->Models()->getRepository('Shopware\Models\Article\Article')->find($iProductId);
        /* @var $oShopwareProduct Shopware\Models\Article\Article */
        $oQueryBuilder = Shopware()->Models()->createQueryBuilder();
        $oQueryBuilder->select(array('details', 'attribute', 'prices', 'configuratorOptions', 'configuratorGroup'))->distinct('details.id')
                ->from('Shopware\Models\Article\Detail', 'details')
                ->leftJoin('details.configuratorOptions', 'configuratorOptions')
                ->leftJoin('configuratorOptions.group', 'configuratorGroup')
                ->leftJoin('details.prices', 'prices')
                ->leftJoin('details.attribute', 'attribute')
        ;

        $mConfiguratorSet = $oShopwareProduct->getConfiguratorSet();
        if (empty($mConfiguratorSet)) {
            $oQueryBuilder->where('details.articleId = ?1 AND details.id = ?2 ')
                ->setParameter(2, $oShopwareProduct->getMainDetail()->getId());
        } else {
            $oQueryBuilder->where('details.articleId = ?1 ');
        }
        $oQueryBuilder->setParameter(1, $iProductId);
        return $oQueryBuilder->getQuery()->getArrayResult();
    }

    public function getAllProperties() {
        if (self::$aTranslatedProperties === null) {
            self::$aTranslatedProperties = array();
            $oBuilder = Shopware()->Models()->createQueryBuilder()
                ->from('Shopware\Models\Property\Option', 'po')
                ->innerJoin('po.values', 'pv', 'with', 'po.id = pv.optionId')
                ->select(array('PARTIAL po.{id,name}'))
                ->groupBy('po.id');
            foreach ($oBuilder->getQuery()->getArrayResult() as $option) {
                $option['name'] = $this->translate($option['id'], 'propertyoption', 'optionName', $option['name']);
                self::$aTranslatedProperties[$option['id']] = $option;
            }
        }
        return self::$aTranslatedProperties;
    }

    public function getAllPropertyValues($iOptionId) {
        $result = array();

        $oBuilder = Shopware()->Models()->createQueryBuilder()
            ->select(array('PARTIAL pv.{id,value}'))
            ->from('Shopware\Models\Property\Value', 'pv')
            ->innerJoin('pv.option', 'po', 'with', 'po.id = :optionId')
            ->setParameter('optionId', $iOptionId);

        $aValues = $oBuilder->getQuery()->getArrayResult();
        foreach ($aValues as $value) {
            $value['value'] = $this->translate($value['id'], 'propertyvalue', 'optionValue', $value['value']);
            $result[$value['id']] = $value['value'];
        }

        return $result;
    }

    public function getPropertyValuesFor($iArticleId, $iOptionId)
    {
        $result = array();

        $oBuilder = Shopware()->Models()->createQueryBuilder()
            ->select(array('PARTIAL pv.{id,value}'))
            ->from('Shopware\Models\Property\Value', 'pv')
            ->innerJoin('pv.articles', 'pa', 'with', 'pa.id = :articleId')
            ->innerJoin('pv.option', 'po', 'with', 'po.id = :optionId')
            ->setParameter('articleId', $iArticleId)
            ->setParameter('optionId', $iOptionId);

        $aValues = $oBuilder->getQuery()->getArrayResult();
        foreach ($aValues as $value) {
            $value['value'] = $this->translate($value['id'], 'propertyvalue', 'optionValue', $value['value']);
            $result[$value['id']] = $value['value'];
        }

        return $result;
    }

    public function getProperties($iArticleId, $iPropertyGroupId) {
        $aProperties = array();
        $dbalConnection = Shopware()->Models()->getConnection();
        $aPropertiesData = $dbalConnection->fetchAll('SELECT fo.name, fv.value, fv.optionID, fv.id '
                . 'FROM s_filter_values fv '
                . 'INNER JOIN s_filter_articles fa ON fv.id = fa.valueID '
                . 'INNER JOIN s_articles a ON a.id = fa.articleID AND (a.id = :articleId) '
                . 'INNER JOIN s_filter_options fo ON fv.optionID = fo.id '
                . 'INNER JOIN s_filter_relations fr ON fo.id = fr.optionID '
                . 'INNER JOIN s_filter f ON f.id = fr.groupID AND (f.id = :propertyGroupId) ORDER BY fr.position ASC', array(
            'articleId' => $iArticleId,
            'propertyGroupId' => $iPropertyGroupId)
                )
        ;
        foreach ($aPropertiesData as $aProp) {
            $sName = $this->translate($aProp['optionID'], 'propertyoption', 'optionName', $aProp['name']);
            $sValue = $this->translate($aProp['id'], 'propertyvalue', 'optionValue', $aProp['value']);
            $aProperties[$sName][] = $sValue;
        }
        return $aProperties;
    }

    public function translate($iId, $sType, $sTranlationIndex, $sFallback) {
        if (version_compare(MLSHOPWAREVERSION, '5.6.0', '>=')) {
            $translationWriter = new \Shopware_Components_Translation(Shopware()->Container()->get('dbal_connection'), Shopware()->Container());
        } else {
            $translationWriter = new \Shopware_Components_Translation();
        }
//        $iLanguage = Shopware()->Shop()->getId();
//        $aTranslate = $translationWriter->read($iLanguage, $sType, $iId);
        $aTranslate = $translationWriter->read(self::getMarketplaceLanguage(), $sType, $iId);
        if (empty($aTranslate) || !isset($aTranslate[$sTranlationIndex])) {
            $sTranslate = $sFallback;
        } else {
            $sTranslate = $aTranslate[$sTranlationIndex];
        }
        return $sTranslate;
    }

    public function getFreeTextFieldValue(\Shopware\Models\Article\Detail $oArticle, $aField) {
        $oAttribute = $oArticle->getAttribute();
        if(!is_object($oAttribute)){
            return array(
                'description' => '',
                'value' => ''
            );
        }
//        may be in future we use service to get attributes
//        $sAttributeData =  Shopware()->Container()->get('shopware_attribute.data_loader')->load(
//            's_articles_attributes',
//            $oArticle->getId()
//        );
        $sValue = null;

        //try to get attribute value from object function, this method doesn't work properly in new version of Shopware
        if (method_exists($oAttribute, 'get' . $aField['name'])) {
            $sValue = $oAttribute->{'get' . $aField['name']}();
        }

        //try to get attribute value from table
        if($sValue === null) {
            $sTableName = Shopware()->Models()->getClassMetadata('Shopware\Models\Attribute\Article')->getTableName();
            if (MLDatabase::getDbInstance()->columnExistsInTable($aField['name'], $sTableName)) {
                $sValue = Shopware()->Db()
                        ->fetchOne('select `' . $aField['name'] . '` from ' . $sTableName . ' where id=' . $oAttribute->getId());
            }
        }

        if($sValue !== null) {
            // if attribute is multi options attribute(combobox), try to find value of attribute with key
            $aAttributeOptions = MLFormHelper::getShopInstance()->getAttributeOptions('a_'.$aField['name']);
            if (!empty($aAttributeOptions) && isset($aAttributeOptions[$sValue])) {
                $sValue = $aAttributeOptions[$sValue];
            }
        }
        if (version_compare(MLSHOPWAREVERSION, '5.6.0', '>=')) {
            $oTranslationWriter = new \Shopware_Components_Translation(Shopware()->Container()->get('dbal_connection'), Shopware()->Container());
        } else {
            $oTranslationWriter = new \Shopware_Components_Translation();
        }
        if ($oArticle->getKind() != 1) {
            $aTranslated = $oTranslationWriter->read(Shopware()->Shop()->getId(), 'variant', $oArticle->getId());
        } else {
            $aTranslated = $oTranslationWriter->read(Shopware()->Shop()->getId(), 'article', $oArticle->getArticle()->getId());
        }
        $aLabelsTranslated = array();

        // DetailAttributeField%Label for older versions
        if (    MLDatabase::getDbInstance()->tableExists('s_core_snippets')
             && (int)MLDatabase::getDbInstance()->fetchOne('SELECT COUNT(*) FROM s_core_snippets WHERE name LIKE \'s_articles_attributes_attr%_label\'') > 0) {
            $sName1 = 's_articles_attributes_attr';
            $sName2 = '_label';
            $sNamespace = 'backend/attribute_columns';
        } else {
            $sName1 = 'DetailAttributeField';
            $sName2 = 'Label';
            $sNamespace = 'frontend/detail/index';
        }
        $sLike = $sName1.'%'.$sName2;

        $oQueryBuilder = Shopware()->Models()->createQueryBuilder();
        $oQuery = $oQueryBuilder
                ->select('snippet.name,snippet.value')
                ->from('Shopware\Models\Snippet\Snippet', 'snippet')
                ->where("
                snippet.namespace = '" .$sNamespace. "'
                AND snippet.localeId = " . Shopware()->Shop()->getLocale()->getId() . "
                AND snippet.name like '" . $sLike . "'
            ")
                ->getQuery();
        $data = $oQuery->getArrayResult();
        
        foreach ($data as $aRow) {
            $aLabelsTranslated ['attr' . substr($aRow['name'], strlen($sName1), -strlen($sName2))] = $aRow['value'];
        }

        if (!isset($aField['translatable']) || !isset($aField['label'])) {
            foreach ($this->getAttributeFields() as $aAtrribute) {
                if ($aField['name'] == $aAtrribute['name']) {
                    $aField = $aAtrribute;
                    break;
                }
            }
        }

        // check for object since we can only handle strings - no datetime objects
        if ((is_string($sValue) || is_numeric($sValue)) && trim((string)$sValue) != '') {
            // try to translate
            if ($aField['translatable']) {
                if (array_key_exists($aField['name'], $aTranslated)) {
                    $sValue = $aTranslated[$aField['name']];
                } else if (array_key_exists('__attribute_'.$aField['name'], $aTranslated)) {//shopware 5
                    $sValue = $aTranslated['__attribute_'.$aField['name']];
                }
                if (array_key_exists($aField['name'], $aLabelsTranslated)) {
                    $aField['label'] = $aLabelsTranslated[$aField['name']];
                }
            }
            return array(
                'description' => $aField['label'],
                'value' => $sValue
            );
        }
        return array(
            'description' => '',
            'value' => ''
        );
    }

    static $aAttributeFields = array();

    /**
     * Get list of free text field or attribute
     * @TODO this function is very similar to getFreeTextFieldsAttributes, they should be merge together for maintenance and to prevent redundancy
     * @return array|mixed
     */
    public function getAttributeFields() {
        if (count(self::$aAttributeFields) == 0) {
            try {//shopware 5.2
                $sTableName = Shopware()->Models()->getClassMetadata('Shopware\Models\Attribute\Article')->getTableName();
                $columns = Shopware()->Container()->get('shopware_attribute.crud_service')->getList($sTableName);
                self::$aAttributeFields = json_decode(json_encode($columns), true);
                foreach (self::$aAttributeFields as &$aField) {
                    $aField['name'] = $aField['columnName'];
                }
            } catch (Exception $oEx) {//shopware < 5.2
                $builder = Shopware()->Models()->createQueryBuilder();
                self::$aAttributeFields = $builder->select(array('elements'))
                        ->from('Shopware\Models\Article\Element', 'elements')
                        ->orderBy('elements.position')
                        ->getQuery()
                        ->getArrayResult();
                foreach (self::$aAttributeFields as &$aField) {
                    $aField['configured'] = true;
                }
            }
        }
        return self::$aAttributeFields;
    }

    /**
     * Get list of free text field or attribute
     * @TODO this function is very similar to getAttributeFields, they should be merge together for maintenance and to prevent redundancy
     * @return array
     */
    public function getFreeTextFieldsAttributes() {
        $aShopFreeTextFieldsAttributes = array();
        // We need to be aware of Shopware version, because in version 5 free text fields are in different table
        try {
            // Shopware 5
            $aOpenTextFields =  MLDatabase::factorySelectClass()
                ->select('column_name AS name, label, column_type AS type')
                ->from(Shopware()->Models()->getClassMetadata('Shopware\Models\Attribute\Configuration')->getTableName())
                ->where(array('table_name' => 's_articles_attributes'))
                ->getResult();
        } catch (Exception $ex) {
            // In Shopware 4 class Shopware\Models\Attribute\Configuration doesn't exist so exception will be thrown
            $aOpenTextFields = MLDatabase::factorySelectClass()
                ->select('name, label, type')
                ->from(Shopware()->Models()->getClassMetadata('Shopware\Models\Article\Element')->getTableName())
                ->getResult();
        }

        foreach ($aOpenTextFields as $aOpenTextField) {
            if ($aOpenTextField['type'] === 'select' || $aOpenTextField['type'] === 'multi_selection' || $aOpenTextField['type'] === 'single_selection') {
                // For these attributes it is complicated to get all values, and list could be too long,
                // because list is filled with Entities from DB (e.g. Article, Variations...)
                continue;
            } elseif ($aOpenTextField['type'] === 'combobox') {
                $sType = 'select';
            } else {
                $sType = 'text';
            }

            $aShopFreeTextFieldsAttributes['a_' . $aOpenTextField['name']] = array(
                'name' => (empty($aOpenTextField['label']) ? $aOpenTextField['name']: $aOpenTextField['label']),
                'type' => $sType,
            );
        }

        return $aShopFreeTextFieldsAttributes;
    }

    /**
     * if product has any variation it will return sum of quantity of all variation, otherwise it will return quantity of product
     * @param object $oArticle
     * @return int
     */
    public function getTotalCount($oArticle) {
        $iStock = $oArticle->getMainDetail()->getInStock();
        $mConfiguratorSet = $oArticle->getConfiguratorSet();
        if(!empty($mConfiguratorSet)){
            try{
                $oQueryBuilder = Shopware()->Models()->createQueryBuilder();
                $oQuery = $oQueryBuilder->select('details.inStock','details.id')->distinct('details.id')
                    ->from('Shopware\Models\Article\Detail', 'details')
                    ->leftJoin('details.configuratorOptions', 'configuratorOptions')
                    ->leftJoin('details.prices', 'prices')
                    ->where('details.articleId = ?1 AND configuratorOptions.id is not NULL AND prices.id is not NULL')
                    ->setParameter(1, $oArticle->getId())
                    ->getQuery();
                
                $aStock = $oQuery->getResult(); 
                $iStock = 0;
                foreach($aStock as $aRow){
                    $iStock += (int)$aRow['inStock'];
                }
            }  catch (Exception $oExc){}
        }
        return $iStock;
    }

    /**
     * @param $oProduct ML_Shopware_Model_Product
     * @param string $attributeCode
     */
    public function getProductDefaultFieldValue($oProduct, $attributeCode) {
        switch ($attributeCode) {
            case 'WeightMultiplied1000':
            {
                $calc = $oProduct->getAttributeValue('pd_Weight') * 1000;
                return number_format((float)$calc, 2, '.', '');
            }
            case 'BasePriceUnitName':
            {
                $baseprice = $oProduct->getBasePrice();
                return $baseprice['ShopwareDefaults']['$sUnitName'];
            }
            case 'BasePriceUnit':
            {
                $baseprice = $oProduct->getBasePrice();
                return $baseprice['Unit'];
            }
            case 'BasePriceValue':
            {
                $baseprice = $oProduct->getBasePrice();
                return $baseprice['ShopwareDefaults']['$fPurchaseUnit'];
            }
            case 'BasePriceUnitShort':
            {
                $baseprice = $oProduct->getBasePrice();
                return $baseprice['ShopwareDefaults']['$sUnit'];
            }
            case 'BasePriceBasicUnit':
            {
                $baseprice = $oProduct->getBasePrice();
                return $baseprice['ShopwareDefaults']['$fReferenceUnit'];
            }
            case 'WidthWithUnit':
            {
                $width = $oProduct->getDimension('width');
                return $width['UnitShort'];
            }
            case 'WidthUnit':
            {
                $width = $oProduct->getDimension('width');
                return $width['Unit'];
            }
            case 'Width':
            {
                $width = $oProduct->getDimension('width');
                return $width['Value'];
            }
            case 'HeightWithUnit':
            {
                $height = $oProduct->getDimension('height');
                return $height['UnitShort'];
            }
            case 'HeightUnit':
            {
                $height = $oProduct->getDimension('height');
                return $height['Unit'];
            }
            case 'Height':
            {
                $height = $oProduct->getDimension('height');
                return $height['Value'];
            }
            case 'LengthWithUnit':
            {
                $length = $oProduct->getDimension('length');
                return $length['UnitShort'];
            }
            case 'LengthUnit':
            {
                $length = $oProduct->getDimension('length');
                return $length['Unit'];
            }
            case 'Len':
            {
                $length = $oProduct->getDimension('length');
                return $length['Value'];
            }
            case 'Weight':
            {
                $weight = $oProduct->getWeight();
                return $weight['Value'];
            }
            case 'WeightUnit':
            {
                $weight = $oProduct->getWeight();
                return $weight['Unit'];
            }
            case 'WeightWithUnit':
            {
                $weight = $oProduct->getWeight();
                return $weight['Value'].' '.$weight['Unit'];
            }
            default:
                break;
        }

        $mainDetailMethod = "get$attributeCode";
        if (method_exists($oProduct->getArticleDetail(), $mainDetailMethod) && $oProduct->getArticleDetail()->$mainDetailMethod() != null) {
            $attributeValue = $oProduct->getArticleDetail()->$mainDetailMethod();
        } else {
            $oMainDetail = $oProduct->getLoadedProduct()->getMainDetail();
            $attributeValue = $oMainDetail->$mainDetailMethod();
        }
        if (in_array(strtolower($attributeCode), array('width', 'height', 'len'))) {
            $attributeValue = (float)$attributeValue;
        }

        return $attributeValue;
    }

}
