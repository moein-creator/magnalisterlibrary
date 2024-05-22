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

class ML_Prestashop_Model_Product extends ML_Shop_Model_Product_Abstract  {

    const ID_FIELD_NAME = 'p.`id_product`';
    const SKU_FIELD_NAME = 'p.`reference`';

    /**
     *  @var ProductCore $oProduct
     */
    protected $oProduct = null;
    protected $blSpecialPrice = false;
    protected $iVariantCount = null;

    public function getVariantCount() {
        if ($this->iVariantCount === null) {
            $this->load();
            $sSql = 'SELECT COUNT(*) 
                      FROM `'._DB_PREFIX_.'product_attribute` pa '.Shop::addSqlAssociation('product_attribute', 'pa').'
                     WHERE pa.`id_product` = '.(int)$this->oProduct->id.'
            ';
            if (count(Shop::getContextListShopID()) > 1) {
                $sSql = str_replace('.id_shop IN (' . implode(', ', Shop::getContextListShopID()) . ')', '.id_shop IN (' . Context::getContext()->shop->id . ')', $sSql);
            }
            $this->iVariantCount = Db::getInstance()->getValue($sSql);
        }
        if ($this->iVariantCount == 0) {
            return 1;
        } else {
            return $this->iVariantCount;
        }
    }

    protected function loadShopVariants() {
        if ($this->oProduct !== null) {
            $iVariationCount = $this->getVariantCount();
            if ($iVariationCount > MLSetting::gi()->get('iMaxVariantCount')) {
                $this
                        ->set('data', array('messages' => array(MLI18n::gi()->get('Productlist_ProductMessage_sErrorToManyVariants', array('variantCount' => $iVariationCount, 'maxVariantCount' => MLSetting::gi()->get('iMaxVariantCount'))))))
                        ->save()
                ;
                MLMessage::gi()->addObjectMessage($this, MLI18n::gi()->get('Productlist_ProductMessage_sErrorToManyVariants', array('variantCount' => $iVariationCount, 'maxVariantCount' => MLSetting::gi()->get('iMaxVariantCount'))));
            } else {
                $aAttributes = $this->oProduct->getAttributeCombinations(Context::getContext()->language->id);
                $aVariants = array();
                foreach ($aAttributes as $aAttribute) {
                    $aVariants[$aAttribute['id_product_attribute']]['id_product_attribute'] = $aAttribute['id_product_attribute'];
                    $aVariants[$aAttribute['id_product_attribute']]['reference'] = $aAttribute['reference'];
                    $aVariants[$aAttribute['id_product_attribute']]['info'][] = array('name' => $aAttribute['group_name'], 'value' => $aAttribute['attribute_name'], 'id_shop' => $aAttribute['id_shop']);
                    $aVariants[$aAttribute['id_product_attribute']]['id_shop'] = $aAttribute['id_shop'];

                }

                unset($aAttributes);
                if (count($aVariants) <= 0) {
                    $aVariants[] = array("id_product" => $this->oProduct->id);
                }
                $sKey = MLDatabase::factory('config')->set('mpid', 0)->set('mkey', 'general.keytype')->get('value');
                $this->aVariantDuplicatedSku = array();
                foreach ($aVariants as &$aVariant) {
                    $oVariant =  MLProduct::factory()->loadByShopProduct($this->oProduct, $this->get('id'), $aVariant);
                    if (
                        $sKey !== 'pID'
                        && isset($aVariant['id_shop']) && (int)$aVariant['id_shop'] === Context::getContext()->shop->id // some product could have variants in one sub shop and no variant in another sub shop
                        && $this->hasDuplicatedSKU($aVariant['reference'])
                    ) {
                        MLMessage::gi()->addObjectMessage(
                            $oVariant, MLI18n::gi()->data('Productlist_Cell_Product_VariantsSkuDuplicatesExists')
                        );
                    }
                    $this->addVariant($oVariant);
                }
            }
        }
        return $this;
    }

    public function loadShopProduct() {
        if ($this->oProduct === null) {//not loaded
            $this->oProduct = false; //not null
            if ($this->get('parentid') == 0) {
                $oProduct = $this;
            } else {
                $oProduct = $this->getParent();
            }
            $sKey = MLDatabase::factory('config')->set('mpid', 0)->set('mkey', 'general.keytype')->get('value');
            if ($sKey == 'pID') {
                $oShopProduct = $this->loadPrestashopProduct($oProduct->get('productsid'), true);
            } else {
                $oShopProduct = MLPrestashopAlias::getProductHelper()->getProductByReference($oProduct->get('productssku'), true);
            }
            if (empty($oShopProduct->id) && $this->get('id') != 0) { // $this->get('id')!= 0 because of OldLib/php/modules/amazon/application/applicationviews.php line 556
                $iId = $this->get('id');
                $this->delete();
                MLMessage::gi()->addDebug('Parent :: One of selected product was deleted from shop now it is deleted from magnalister list too, please refresh the page : '.$iId);
                throw new Exception;
            }
            $aData = $this->get('shopdata');
            $this->oProduct = $oShopProduct;
            $this->prepareProductForMarketPlace();
            if ($this->get('parentid') != 0) {//is variant
                $this->loadByShopProduct($oShopProduct, $this->get('parentid'), $aData);
            }
        }

        return $this;
    }

    public function loadByShopProduct($mProduct, $iParentId = 0, $mData = null) {
        $this->oProduct = $mProduct;
        $this->prepareProductForMarketPlace();
        if($mProduct instanceof Combination){
             throw new Exception('bad parameter');
        }
        /* for product who have no reference number ,random refrence is inserted becaue it will made problem when product key is Article number */
        $sKey = MLDatabase::factory('config')->set('mpid', 0)->set('mkey', 'general.keytype')->get('value');
        $this->aKeys = array($sKey == 'pID' ? 'marketplaceidentid' : 'marketplaceidentsku');
        $this->set('parentid', $iParentId)->aKeys[] = 'parentid';
        $sMessage = array();
        if ($iParentId == 0) {
            $this
                ->set('marketplaceidentid', $this->oProduct->id)
                ->set('marketplaceidentsku', $this->oProduct->reference)
                ->set('productsid', $this->oProduct->id)
                ->set('productssku', $this->oProduct->reference)
                ->set('shopdata', array())
                ->set('data', array('messages' => $sMessage))
                ->save()
                ->aKeys = array('id');
            if ($sKey !== 'pID' && empty($this->oProduct->reference)) {
                MLMessage::gi()->addObjectMessage(
                    $this, MLI18n::gi()->data('Productlist_Cell_Product_NoSku')
                );
            }
        } else {
            if (isset($mData['id_product_attribute'])) {
                $oVariation = $this->loadPrestashopVariation($mData['id_product_attribute']);
            } else if (isset($mData['id_product'])) {
                $oVariation = $this->loadPrestashopProduct($mData['id_product'], true);
            } else {
                throw new Exception("not id set to create new variation", "13131313");
            }
            if (empty($oVariation->id)) {
                $this->delete();
                MLMessage::gi()->addDebug('Child ::One of selected product was deleted from shop now it is deleted from magnalister list too, please refresh the page');
                throw new Exception;
            }
            $this
                ->set('marketplaceidentid', $oVariation->id.(isset($mData['id_product']) ? '' : '_'.$this->oProduct->id))
                ->set('marketplaceidentsku', $oVariation->reference)
                ->set("productsid", $oVariation->id)
                ->set("productssku", $oVariation->reference)
                ->set('shopdata', $mData)
                ->set('data', array('messages' => $sMessage));
            if ($this->exists()) {
                $this->aKeys = array('id');
                $this->save();
            } else {
                $this->save()->aKeys = array('id');
            }
            if ($sKey !== 'pID' && empty($oVariation->reference)) {
                MLMessage::gi()->addObjectMessage(
                    $this, MLI18n::gi()->data('Productlist_Cell_ProductVariant_NoSku')
                );
            }

        }
        return $this;
    }

    protected $aVariantDuplicatedSku = array();
    protected function hasDuplicatedSKU($sReference) {
        if (isset($this->aVariantDuplicatedSku) &&  in_array($sReference, $this->aVariantDuplicatedSku, true)) {
            return true;
        } else {
            $this->aVariantDuplicatedSku[] = $sReference;
            return false;
        }
    }


    public function getRealProduct() {
        $this->load();
        $mData = $this->get('shopdata');
        if (isset($mData['id_product_attribute'])) {
            $oPV = $this->loadPrestashopVariation($mData['id_product_attribute']);
        } else {
            $oPV = $this->loadPrestashopProduct($this->oProduct->id, true);
        }
        return $oPV;
    }

    public function __get($sName) {
        $this->load();
		$sValue = 0;
        $oPV = $this->getRealProduct();

        // Check if field exists at master product of not try to get it from variation product
        if (isset($oPV->$sName)) {
            $sValue = $oPV->$sName;
            unset($oPV);
        } elseif ($oPV instanceof Combination && isset($this->getParent()->getRealProduct()->$sName)) {
            $sValue = $this->getParent()->getRealProduct()->$sName;
        }

		if (is_array($sValue)) {
			if (isset($sValue[Context::getContext()->language->id])) {
				return $sValue[Context::getContext()->language->id];
			} else {
				return current($sValue);
			}
		} else {
			return $sValue ;
		}
		
    }

    public function getImages() {
        $this->load();
        $aOut = array();
        $oPV = $this->getRealProduct();
        $aImages = array();
        if ($oPV instanceof Product) {
            $aImages = $this->getLoaddedProduct()->getImages(Context::getContext()->language->id);
        } else {
            $aAllImages = $this->getLoaddedProduct()->getCombinationImages(Context::getContext()->language->id);
            $aImages = isset($aAllImages[$oPV->id]) ? $aAllImages[$oPV->id] : array();
        }
        foreach ($aImages as $iImg) {
            $oImage = new Image((int)$iImg['id_image']);
            $aOut[] = _PS_PROD_IMG_DIR_.$oImage->getExistingImgPath().'.'.$oImage->image_format;
        }
        if (empty($aOut) && $this->get('parentid') != 0) {
            $aOut = $this->getParent()->getImages();
        }
        return $aOut;
    }
    
    public function getImageUrl($iX = 40, $iY = 40) {
        $this->load();
        $iProductCoverId= null;
        $oPV = $this->getRealProduct();
        $aImages = array();
        if ($oPV instanceof Combination) {
            $aImages = $this->getLoaddedProduct()->_getAttributeImageAssociations($oPV->id);
            $iProductCoverId = array_shift($aImages);
        }
        if ($iProductCoverId == null) {
            $iProductCoverId = Product::getCover($this->getLoaddedProduct()->id);
        }
        if ($iProductCoverId > 0) {
            if(is_array($iProductCoverId)){
                $iProductCoverId = array_shift($iProductCoverId);
            }
            $oimage = new Image($iProductCoverId);
            try {
                return MLImage::gi()->resizeImage(_PS_IMG_DIR_ . 'p/' . $oimage->getExistingImgPath() . '.jpg', 'products', $iX, $iY, true);
            } catch (Exception $oEx) {
            }
        }
        return '';
    }

    public function getName() {
        $this->load();
        $sPostFix = '';
        if (($iAttributeId = $this->getAttributeId()) !== null) {
            $sPostFix .= Product::getProductName($this->getId(), $iAttributeId);
        }
        $sProductName = '';
        $aProductName = $this->getLoaddedProduct()->name;
        if ($this->blProductListMode && isset($aProductName[ContextCore::getContext()->employee->id_lang])) {
            $sProductName = $aProductName[Context::getContext()->employee->id_lang];
        } elseif (isset($aProductName[Context::getContext()->language->id])) {
            $sProductName = $aProductName[Context::getContext()->language->id];
        }
        return (!empty($sProductName) && strpos($sPostFix, $sProductName) !== false) ? $sPostFix : $sProductName . $sPostFix;
    }

    public function getEditLink() {
        if(defined('_PS_VERSION_') && version_compare(_PS_VERSION_, '1.7.0.0', '>=')){
            return Context::getContext()->link->getAdminLink('AdminProducts', true, array('id_product'=>  $this->getLoaddedProduct()->id));
        } else {
            return 'index.php?controller=AdminProducts&updateproduct&id_product=' . $this->getId() .
                    '&token=' . Tools::getAdminToken('AdminProducts' . (int) Tab::getIdFromClassName('AdminProducts') . (int) Context::getContext()->employee->id);
        }
    }
    
    public function getFrontendLink() {
        $id_product_attribute = null;
        $mData = $this->get('shopdata');
        if (isset($mData['id_product_attribute'])) {
            $id_product_attribute = $mData['id_product_attribute'];
        }
        $category = null;
        if(!empty($this->getLoaddedProduct()->id_category_default)){
            $category = Category::getLinkRewrite($this->getLoaddedProduct()->id_category_default, Context::getContext()->language->id);
        }

        return Context::getContext()->link->getProductLink(
            $this->getLoaddedProduct(),
            null,
            $category,
            null,
            null,
            null,
            $id_product_attribute,
            null,
            false,
            true);

    }

    public function getShopPrice($blGros = true, $blFormated = false) {
        $this->load();
        $this->blSpecialPrice = false;
        return $this->getPrice($blGros, $blFormated);
    }

    public function getSuggestedMarketplaceStock($sType, $fValue, $iMax = null){
        if(
            MLModule::gi()->getConfig('inventar.productstatus') == 'true'
            && !$this->isActive()
        ) {
            return 0;
        }
        if ($sType == 'lump') {
            $iStock =  (int)$fValue;
        } else {
            $iStock = $this->getStock();
            if ($sType == 'stocksub') {
                $iStock = $iStock - $fValue;
            }

            if (!empty($iMax)) {
                $iStock = min( (int) $iStock,$iMax);
            }
        }

        return $iStock > 0 ? $iStock : 0;
    }

    protected function prepareProductForMarketPlace() {  
        $aConf = null;
        try {
            $aConf = MLModule::gi()->getConfig();
        } catch(Exception $oExc) {
            MLMessage::gi()->addDebug($oExc);
        }      
        if($aConf !== null){
            $context = Context::getContext();
            /* @var  $context  ContextCore */
            if(array_key_exists('orderimport.shop', $aConf)){
                Shop::setContext(Shop::CONTEXT_SHOP, $aConf['orderimport.shop']);
                $context->shop = new Shop($aConf['orderimport.shop']);
                /* lang parameter shouldn't be set here because it can cause that for name, description and ... product
                has single value instead of array of all language and it can break several function that expect array */
                $this->oProduct = new Product($this->oProduct->id, true, null, $context->shop->id);
            }else{
                Shop::setContext(Shop::CONTEXT_SHOP,$context->shop->id);
            }
            $context->currency = isset($aConf['currency']) ? new Currency(Currency::getIdByIsoCode($aConf['currency'])) : $context->currency;
            $context->language = new Language($aConf['lang']);
        }
    }

    public function getSuggestedMarketplacePrice(ML_Shop_Model_Price_Interface $oPrice, $blGros = true, $blFormated = false) {
        $this->load();
        $context = Context::getContext();
        $aConf = $oPrice->getPriceConfig();
        $fTax = $aConf['tax'];
        $sPriceKind = $aConf['kind'];
        $fPriceFactor = (float) $aConf['factor'];
        $iPriceSignal = $aConf['signal'];
        /* active or diactive special price */
        $this->blSpecialPrice = $aConf['special'];
        $sCustomerGroup = $aConf['group'];
        $context->customer = new Customer();
        $context->customer->id_default_group = isset($sCustomerGroup) ? $sCustomerGroup : _PS_DEFAULT_CUSTOMER_GROUP_;

        $mReturn = $this->getPrice($blGros, $blFormated /* , $blSpecial */, $sPriceKind, $fPriceFactor, $iPriceSignal, $fTax);
        /* roll back special price to its current shop state */
        return $mReturn;
    }

    public function getVolumePrices($sGroup, $blGross = true, $sPriceKind = '', $fPriceFactor = 0.0, $iPriceSignal = null) {
        $context = Context::getContext();
        $id_product_attribute = $this->getAttributeId();
        $id_product = (int)$this->oProduct->id;
        $id_currency = $context->currency->id;
        $quantity_discounts = SpecificPrice::getQuantityDiscounts(
            $id_product, $context->shop->id, $id_currency, Configuration::get('PS_COUNTRY_DEFAULT', null, null, $context->shop->id),
            $sGroup, $id_product_attribute);
        $aVolumePrices = array();

        $fTax = $this->getTax();

        foreach ($quantity_discounts as $quantity_discount) {
            if ($quantity_discount['from_quantity'] > 1) {
                $specific_price = null;
                $fPrice = Product::priceCalculation(
                    $context->shop->id, (int)$this->oProduct->id, $this->getAttributeId(),
                    Configuration::get('PS_COUNTRY_DEFAULT', null, null, $context->shop->id),
                    0/*$id_state*/, 0/*$zipcode*/, $context->currency->id /*$id_currency*/,
                    $sGroup/*$id_group*/, (int)$quantity_discount['from_quantity']/*$quantity*/, $blGross /*use_tax*/, 2 /*$decimals*/,
                    false/*$only_reduc*/, true/*$use_reduc*/, true/*$with_ecotax*/,
                    $specific_price, true/*$use_group_reduction*/, null/*$id_customer*/, null/*$use_customer_price*/,
                    null/*$id_cart*/, (int)$quantity_discount['from_quantity']/*$real_quantity*/
                );

                $oPrice = MLPrice::factory();
                $fGrossPrice = $fPrice;
                if (!$blGross) {
                    $fGrossPrice = $oPrice->calcPercentages(null, $fPrice, $fTax);
                }

                // price kind
                if ($sPriceKind == 'percent') {
                    $fGrossPrice = $oPrice->calcPercentages(null, $fGrossPrice, $fPriceFactor);
                } elseif ($sPriceKind == 'addition') {
                    $fGrossPrice = $fGrossPrice + $fPriceFactor;
                }

                // price signal
                if ($iPriceSignal !== null) {
                    //If price signal is single digit then just add price signal as last digit
                    if (strlen((string)$iPriceSignal) == 1) {
                        $fGrossPrice = (0.1 * (int)($fGrossPrice * 10)) + ((int)$iPriceSignal / 100);
                    } else {
                        $fGrossPrice = ((int)$fGrossPrice) + ((int)$iPriceSignal / 100);
                    }
                }

                if ($blGross) {
                    $aVolumePrices[$quantity_discount['from_quantity']] = $fGrossPrice;
                } else {
                    $aVolumePrices[$quantity_discount['from_quantity']] = $oPrice->calcPercentages($fGrossPrice, null, $fTax);
                }

            }
        }
        return $aVolumePrices;
    }

    protected function getPrice($blGros, $blFormated, $sPriceKind = '', $fPriceFactor = 0.0, $iPriceSignal = null, $fTax = null) {
        $context = Context::getContext();
        $fPercent = (float)$this->getLoaddedProduct()->getTaxesRate();
        $iGroupId = $this->blSpecialPrice && is_object($context->customer) ? $context->customer->id_default_group : 0;
        $oCurrency = Currency::getCurrencyInstance($context->currency->id);
        if ($oCurrency->conversion_rate == 0) {
            $oDBCurrency = new Currency($context->currency->id);
            $oCurrency->conversion_rate = $oDBCurrency->conversion_rate;
        }
        $fPrice = Product::priceCalculation(
                $context->shop->id , (int) $this->oProduct->id, $this->getAttributeId(), 
                Configuration::get('PS_COUNTRY_DEFAULT',null ,null ,$context->shop->id), 
                0/*$id_state*/ ,  0/*$zipcode*/ , $context->currency->id /*$id_currency*/ ,
                $iGroupId/*$id_group*/  ,  1/*$quantity*/  , ($fTax === null) /*use_tax*/  , 2 /*$decimals*/  , 
                false/*$only_reduc*/ , $this->blSpecialPrice/*$use_reduc*/ , true/*$with_ecotax*/ , 
                $specific_price  ,  true  ,null , null ,null,1
            )   ;  
        if($fPrice === null){
            $fPrice = 0;
        }
        $oPrice = MLPrice::factory();
        if($fTax !== null) {
            $fPrice = $oPrice->calcPercentages(null, $fPrice, $fTax);
        }
        if ($sPriceKind == 'percent') {
            $fPrice = $oPrice->calcPercentages(null, $fPrice, $fPriceFactor);
        } elseif ($sPriceKind == 'addition') {
            $fPrice = $fPrice + $fPriceFactor;
        }
        if ($iPriceSignal !== null) {
            //If price signal is single digit then just add price signal as last digit
            if (strlen((string)$iPriceSignal) == 1) {
                $fPrice = (0.1 * (int)($fPrice * 10)) + ((int)$iPriceSignal / 100);
            } else {
                $fPrice = ((int)$fPrice) + ((int)$iPriceSignal / 100);
            }
        }
        // 3. calc netprice from modulprice
        $fNetPrice = $oPrice->calcPercentages($fPrice, null, $fPercent);
        // 4. define out price (unformated price of current shop)
        $fUsePrice = $blGros ? $fPrice : $fNetPrice;
        if ($blFormated) {
            return "<span class='price'>".Tools::displayPrice($fUsePrice, Context::getContext()->currency->id)."</span>";
        }
        return $fUsePrice;
    }

    /**
     * @param type $sFieldName
     * @param type $blGeneral
     * @return null
     */
    public function getModulField($sFieldName, $blGeneral = false, $blMultiValue = false) {
        $this->load();
        if ($blGeneral) {
            $sValue = MLDatabase::factory('config')->set('mpid', 0)->set('mkey', $sFieldName)->get('value');
        } else {
            $sValue = MLModule::gi()->getConfig($sFieldName);
        }
		if(strpos($sValue,'product_feature_') === 0){
			$sValue = str_replace('product_feature_', '', $sValue);
			return MLHelper::gi('model_product')->getProductFeatureValue($this->getId() ,$sValue, Context::getContext()->language->id);
		}else{
			return $this->__get($sValue);
		}
    }

    public function getDescription() {
        $aLanguageValue = $this->getLoaddedProduct()->description;
        $languageId = Context::getContext()->language->id;
        return isset($aLanguageValue[$languageId]) ? $aLanguageValue[$languageId] : '';
    }

    public function getShortDescription() {
        $aLanguageValue = $this->getLoaddedProduct()->description_short;
        $languageId = Context::getContext()->language->id;
        return isset($aLanguageValue[$languageId]) ? $aLanguageValue[$languageId] : '';
    }

    public function getMetaDescription() {
        $aLanguageValue = $this->getLoaddedProduct()->meta_description;
        $languageId = Context::getContext()->language->id;
        return isset($aLanguageValue[$languageId]) ? $aLanguageValue[$languageId] : '';
    }

    public function getMetaKeywords() {
        $aLanguageValue = $this->getLoaddedProduct()->meta_keywords;
        $languageId = Context::getContext()->language->id;
        return isset($aLanguageValue[$languageId]) ? $aLanguageValue[$languageId] : '';
    }

    /**
     * 
     * @param type $blIncludeRootCats
     * @return array
     */
    public function getCategoryIds($blIncludeRootCats = true) {
        $aCategories = array();
        foreach (Product::getProductCategoriesFull($this->getLoaddedProduct()->id) as $iCategoryId => $aCategoryData ){
            if( $blIncludeRootCats || !in_array($iCategoryId, $this->getRootCategoriesIds())){
                $aCategories[] = $iCategoryId;
            }
        }
        return $aCategories;
    }

    public function getCategoryPath() {
        $oCategory = new Category(Context::getContext()->shop->getCategory());
        $sCategories = '';
        foreach(Product::getProductCategoriesFull($this->getLoaddedProduct()->id) as $iCategoryId => $aCategoryData) {
            if(method_exists('Tools', 'getPath')){
                $sRootCategory = $oCategory->name[Context::getContext()->language->id];
                if(defined('_PS_VERSION_') && version_compare(_PS_VERSION_, '1.7.0.0', '>=')){
                    $sCategoryPath = Tools::getPath('', $iCategoryId);
                } else{
                    $sCategoryPath = Tools::getPath($iCategoryId);
                }
                $sCategories .= (strpos(trim($sCategoryPath), trim($sRootCategory)) === false ? $sRootCategory.' > ' : '').$sCategoryPath.'<br>';
            } else {
                $oProductCategory = new Category($iCategoryId);
                foreach ($oProductCategory->getAllParents(Context::getContext()->language->id)->orderBy('id_category') as $category) {
                    if($category->name !== 'Root') {
                        $sCategories .= $category->name . ' > ';
                    }
                }
                $sCategories .= '<br>';
            }
        }
        return strip_tags($sCategories, '<br>');
    }

    /**
     * 
     * @param type $blIncludeRootCats
     * @return array
     */
    public function getCategoryStructure($blIncludeRootCats = true) {
        $aCategories = array();
        $aRootCatIds = $aExistedCatId = $blIncludeRootCats ? array() : $this->getRootCategoriesIds();
        foreach(Product::getProductCategoriesFull($this->getLoaddedProduct()->id) as $iCategoryId => $aCategoryData) {
            do {
                if (in_array($iCategoryId , $aExistedCatId)) {
                    break;
                }
                $oCategory = new Category($iCategoryId);
                $aCategory = array(
                    'ID' => $oCategory->id_category,
                    'Name' => $oCategory->name[Context::getContext()->language->id],
                    'Description' => $oCategory->description[Context::getContext()->language->id],
                    'Status' => true,
                );
                $aExistedCatId[] = $oCategory->id_category;
                $iCategoryId = $oCategory->id_parent;
                if ($iCategoryId != 0 && !in_array($iCategoryId, $aRootCatIds)) {
                    $aCategory['ParentID'] = $iCategoryId;
                }
                $aCategories[] = $aCategory;
            } while($iCategoryId != 0);
        }

        return $aCategories;
    }
    
    protected function getRootCategoriesIds () {
        $aTopCategoryIds = array(1);//Root category
        
        /* get top category of each shop in Prestashop */
        foreach (Category::getRootCategories(null, false) as $aCategory){
            $aTopCategoryIds[] = $aCategory['id_category'];
        }
        return $aTopCategoryIds;
    }
    
    public function getStock() {
        $this->load();
        return StockAvailable::getQuantityAvailableByProduct($this->getLoaddedProduct()->id, $this->getAttributeId(), Context::getContext()->shop->id);
    }

    /**
     * return current master product object of Prestashop
     * it is public because it is used in some individual programming
     * @return Product
     */
    public function getLoaddedProduct() {
        if ($this->oProduct === null) {
            $this->load();
        }
        return $this->oProduct;
    }

    /**
     * Gets the tax percentage of the item.
     * if $aAdressData is set, it try to locate tax for $aAddressData['Shipping']
     * @param null $aAddressSets get tax for home country
     * @param array $aAddressSets get tax for $aAddressData array('Main' => [], 'Billing' => [], 'Shipping' => []);
     * @return float
     */
    public function getTax( $aAddressSets = null ) {
        if ($aAddressSets === null) { 
           return $this->getLoaddedProduct()->tax_rate ;
        } else {
            $aAddressData = $aAddressSets['Shipping'];
            $oAddress = Address::initialize();
            $iCountryId = Country::getByIso($aAddressData['CountryCode']);
            $oAddress->id_country = $iCountryId;
            if (array_key_exists('Suburb', $aAddressData) && !empty($aAddressData['Suburb'])) {
                $oAddress->id_state = State::getIdByIso($aAddressData['Suburb'], $iCountryId);
            }
            if (isset($aAddressData['Postcode'])) {
                $oAddress->postcode = $aAddressData['Postcode'];
            }
            return $this->getLoaddedProduct()->getTaxesRate($oAddress);
        }
    }

    public function getVariatonData() {
        return $this->getVariatonDataOptinalField(array('name','value'));
    }

    /**
     * Get name, value, value id and prefixed shop code.
     * 
     * @return array
     */
    public function getPrefixedVariationData() {
        $variationData = $this->getVariatonDataOptinalField(array('name','value', 'code', 'valueid'));

        foreach ($variationData as &$variation) {
            $variation['code'] = 'a_' . $variation['code'];
        }

        return $variationData;
    }
    
    public function getVariatonDataOptinalField($aFields = array()) {
        $aOut = array();
        $oCombination = $this->getRealProduct();
        /* @var  $oCombination CombinationCore */
        $aAtributes = $this->getLoaddedProduct()->getAttributesGroups(Context::getContext()->language->id);
        foreach($aAtributes  as $aAtribute) {
            if($aAtribute['id_product_attribute'] == $oCombination->id){
                $aData = array();
                if (in_array('code',$aFields)) {//an identifier for group of attributes , that used in Meinpaket at the moment
                    $aData['code']=  $aAtribute['id_attribute_group'];                                
                }
                if (in_array('valueid',$aFields)) {//an identifier for group of attributes , that used in Meinpaket at the moment
                    $aData['valueid']=  $aAtribute['id_attribute'];                                
                } 
                if (in_array('name',$aFields)) {
                    $aData['name']=  $aAtribute['group_name'];                                
                }
                if (in_array('value',$aFields)) {
                    $aData['value']=  $aAtribute['attribute_name'];                                
                }
                $aOut[] = $aData;
            }
        }
        return $aOut;
    }

    public function isActive() {
        $this->load();
        return $this->getLoaddedProduct()->active == 1;
    }

    /**
     *  if there some problems, see getByMarketplaceSKU method of magneto_model_product
     */
    public function createModelProductByMarketplaceSku($sSku) {
        $aOut = array('master' => null, 'variant' => null);
        $oMyTable = MLProduct::factory();
        $oShopProduct = null;
        if (MLDatabase::factory('config')->set('mpid', 0)->set('mkey', 'general.keytype')->get('value') == 'pID') {
            $sIdent = 'marketplaceidentid';
            if (strpos($sSku, '_') !== false) {
                $aIds = explode("_", $sSku);
                if (is_int($aIds[0]) && is_int($aIds[1])) {
                    $oProduct = $this->loadPrestashopProduct($aIds[1],true);
                    $oCombination = $this->loadPrestashopVariation($aIds[0]);
                    if (!empty($oProduct->id) && !empty($oCombination->id)) {
                        $oShopProduct=$oProduct;
                    }
                }
            } else {
                if (is_int($sSku)) {
                    $oProduct = $this->loadPrestashopProduct($sSku,true);
                    if (!empty($oProduct->id)) {
                        $oShopProduct = $oProduct;
                    }
                }
            }
        } else {
            $sIdent = 'marketplaceidentsku';
            $oProduct = MLPrestashopAlias::getProductHelper()->getProductByReference($sSku);
            if ($oProduct !== null) {
                if ($oProduct instanceof Combination) {
                    $oShopProduct = $this->loadPrestashopProduct($oProduct->id_product, true);
                } else {
                    $oShopProduct = $oProduct;
                }
            }
        }
        if ($oShopProduct !== null && $oShopProduct->id !== null) {
            $oMyTable->loadByShopProduct($oShopProduct);
            if ($oMyTable->get($sIdent) === $sSku) {
                $aOut['master'] = $oMyTable;
            }
            foreach ($oMyTable->getVariants() as $oVariant) {
                if ($oVariant->get($sIdent) === $sSku) {
                    $aOut['variant'] = $oVariant;
                }
            }
        }
        return $aOut;
    }

    public function setStock($iStock) {
        $this->load();
        StockAvailable::setQuantity($this->getId(), $this->getAttributeId(0), (int)$iStock);
        return $this;
    }

    /**
     * This function is useful only in prestashop 1.6, in 1.7 there is no warehouse
     */
    public function getWarehouse() {
        $this->load();
        if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT') &&
            (int)$this->getLoaddedProduct()->advanced_stock_management == 1) {
            $warehouse_list = Warehouse::getProductWarehouseList($this->getId(), $this->getAttributeId(0));

            $warehouse_in_stock = array();
            $manager = StockManagerFactory::getManager();

            foreach ($warehouse_list as $key => $warehouse) {
                $product_real_quantities = $manager->getProductRealQuantities(
                    $this->getId(),
                    $this->getAttributeId(0),
                    array($warehouse['id_warehouse']),
                    true
                );
                if ($product_real_quantities > 0) {
                    return $warehouse['id_warehouse'];
                }
            }

        }
        return 0;
    }

    public function getAttributeId($mReturn = null) {
        $this->load();
        $mData = $this->get('shopdata');
        if (isset($mData['id_product_attribute'])) {
            return $mData['id_product_attribute'];
        } else {
            return $mReturn;
        }
    }

    public function getId() {
        return $this->getLoaddedProduct()->id;
    }

    public function getBasePriceString($fPrice, $blLong = true) {
        $aBasePrice = $this->getBasePrice();
        if(!empty($aBasePrice)){
            $fPercent = (float)$this->getLoaddedProduct()->getTaxesRate();
            $sBasePrice = Tools::displayPrice(($this->getLoaddedProduct()->unit_price * ($fPercent/100 + 1)), Context::getContext()->currency->id);
            $sBaseWeight = $this->getLoaddedProduct()->unity;
            return "{$sBasePrice} / {$sBaseWeight} ";
        }else{
            return '';
        }
    }

    public function getBasePrice() {
        $sUnit = (string) $this->getLoaddedProduct()->unity;
        $sUnit = trim($sUnit);
        if (empty($sUnit) || $this->getLoaddedProduct()->unit_price_ratio <= 0) {
            return array();
        } else {
            return array(
                "Unit" => $sUnit,
                "Value" => $this->getLoaddedProduct()->unit_price_ratio,
            );
        }
    }

    public function getWeight(){
        $fWeight = (float)($this->getLoaddedProduct()->weight);
        $sUnit = Configuration::get('PS_WEIGHT_UNIT');
        if($fWeight > 0){
            return array(
                "Unit" => $sUnit,
                "Value"=>  $fWeight,
            );
        }else{
            return array();
        }
    }

    public function setLang( $iLang ){
         if (Context::getContext()->language->id != $iLang ) {
             Context::getContext()->language = new Language($iLang);
         }
        return $this;
    }

    public function getTaxClassId() {
        $oProduct = $this->getLoaddedProduct();
        return $oProduct->id_tax_rules_group;
    }

    public function getAttributeValue($mAttributeCode) {
        $mValue = null;
        $aAttributeCode = explode('_', $mAttributeCode);
        if ($aAttributeCode[0] === 'a') {
            $oPV = $this->getRealProduct();
            if ($oPV instanceof Combination) {
                $attributes = $oPV->getAttributesName(Context::getContext()->language->id);
                foreach ($attributes as $attribute) {
                    $result = MLDatabase::factorySelectClass()
                        ->select('id_attribute_group')
                        ->from(_DB_PREFIX_.'attribute')
                        ->where("id_attribute = {$attribute['id_attribute']}")
                        ->getResult();
                    if ($result[0]['id_attribute_group'] === $aAttributeCode[1]) {
                        $mValue = $attribute['name'];
                        break;
                    }
                }
            }
        } else if ($aAttributeCode[0] === 'f') {
            $ifeatureValue = MLDatabase::factorySelectClass()
                ->select('l.value')
                ->from(_DB_PREFIX_.'feature_product', 'p')
                ->join(array(_DB_PREFIX_.'feature_value_lang', 'l', 'p.id_feature_value = l.id_feature_value', ML_Database_Model_Query_Select::JOIN_TYPE_LEFT))
                ->where("l.id_lang = ".Context::getContext()->language->id." and p.id_feature = $aAttributeCode[1] and p.id_product = {$this->getLoaddedProduct()->id}")
                ->getResult();
            $mValue = isset($ifeatureValue[0]['value']) ? $ifeatureValue[0]['value'] : NULL;
        } else if (isset($aAttributeCode[1]) && in_array($aAttributeCode[0], array('width', 'height', 'depth', 'weight'))) {
            if (strpos($aAttributeCode[1], 'WithUnit') !== false) {//e.g. WidthWithUnit
                $mValue = $this->getAttributeValue($aAttributeCode[0]).' '.$this->getAttributeValue($aAttributeCode[0].'_Unit');
            } else if (strpos($aAttributeCode[1], 'Unit') !== false) {//e.g. WidthUnit
                if ($aAttributeCode[0] === 'weight') {
                    $mValue = Configuration::get('PS_WEIGHT_UNIT');
                } else {
                    $mValue = Configuration::get('PS_DIMENSION_UNIT');
                }
            }
        } else if ($this->getRealProduct() instanceof Combination && in_array($mAttributeCode, array('weight'))) {//if the product is a variation
            $mValue = $this->getParent()->getRealProduct()->$mAttributeCode + $this->getRealProduct()->$mAttributeCode;
        } else {
            $mValue = $this->__get($mAttributeCode);
        }
        return $mValue;
    }

    public function getManufacturer() {
        return $this->getModulField('manufacturer');
    }

    public function getManufacturerPartNumber() {
        return $this->getModulField('manufacturerpartnumber');
    }

    public function getEAN() {
        return $this->getModulField('ean');
    }
        
    protected function deleteVariants($aIds = array()) {
        if(Shop::isFeatureActive() && 
                is_object($this->oProduct) // it can be product was deleted before, now we couldn't load variants by not existed product, so we delete normaly all variation
                ){
            $aIds = array();              
            $aBackupVariants = $this->aVariants;
            $this->aVariants = array();
            $iBackupShopId = Shop::getContextShopID();

            Shop::setContext(Shop::CONTEXT_ALL);
            $this->loadShopVariants();            
            foreach ($this->aVariants as $oVariant) {
                $aIds[] = $oVariant->get('id');
            }
        }
        parent::deleteVariants($aIds);           
            
        if(Shop::isFeatureActive() && 
                is_object($this->oProduct) // it can be product was deleted before, now we couldn't load variants by not existed product, so we delete normaly all variation
                ){
            $this->aVariants = $aBackupVariants ;
            Shop::setContext(Shop::CONTEXT_SHOP,$iBackupShopId);
        }
    }
    
    public function getShippingCostByZone($iZoneId, $fPrice, $iCarrierId) {
        $aAllProductCarrier = $this->getLoaddedProduct()->getCarriers();
        if(empty($aAllProductCarrier)){
            $oCarrier = new Carrier($iCarrierId);
        } else {            
            $aCarrier = current($this->getLoaddedProduct()->getCarriers());
            $oCarrier = new Carrier($aCarrier['id_carrier']);
        }
        
        if(empty($oCarrier->id)) {
            return false;
        }
        
        $fShippingCostByWeight = Carrier::checkDeliveryPriceByWeight($oCarrier->id, $this->getLoaddedProduct()->weight, (int) $iZoneId);
        $fShippingCostByPrice = Carrier::checkDeliveryPriceByPrice($oCarrier->id, $fPrice, (int) $iZoneId, (int) Context::getContext()->currency->id);
        
        // Get only carriers that have a range compatible with cart
        if (($oCarrier->getShippingMethod() == Carrier::SHIPPING_METHOD_WEIGHT && !$fShippingCostByWeight) || ($oCarrier->getShippingMethod() == Carrier::SHIPPING_METHOD_PRICE && !$fShippingCostByPrice)) {
            return false;
        }

        if ($oCarrier->getShippingMethod() == Carrier::SHIPPING_METHOD_WEIGHT) {
            $shipping = $oCarrier->getDeliveryPriceByWeight($this->getLoaddedProduct()->weight, (int) $iZoneId);
        } else {
            $shipping = $oCarrier->getDeliveryPriceByPrice($fPrice, (int) $iZoneId, (int) Context::getContext()->currency->id);
        }
        MLMessage::gi()->addInfo(var_dump_pre($shipping,true));
        return $shipping;
    }

    public function isSingle() {
        if ($this->iVariantCount === null) {
            $this->getVariantCount();
        }
        return $this->iVariantCount == 0;
    }
    
    public function loadPrestashopProduct($id_product = null, $full = false, $id_lang = null, $id_shop = null, Context $context = null){
        $this->initShopContext();
        $product =  new Product($id_product , $full, $id_lang , $id_shop, $context);
        return $product;
    }

    /**
     * @param null $id
     * @param null $id_lang
     * @param null $id_shop
     * @return CombinationCore
     */
    public function loadPrestashopVariation($id = null, $id_lang = null, $id_shop = null) {
        $this->initShopContext();
        $product =  new Combination($id , $id_lang , $id_shop);
        return $product;
    }
    
    public function initShopContext() {
        $aConf = null;
        try {
            $aConf = MLModule::gi()->getConfig();
        } catch(Exception $oExc) {
            MLMessage::gi()->addDebug($oExc);
        }      
        if($aConf !== null){
            $context = Context::getContext();
            /* @var  $context  ContextCore */
            if(array_key_exists('orderimport.shop', $aConf)){
                Shop::setContext(Shop::CONTEXT_SHOP,$aConf['orderimport.shop']); 
                $context->shop = new Shop($aConf['orderimport.shop']);
            }else{
                Shop::setContext(Shop::CONTEXT_SHOP,$context->shop->id);
            }
            $context->currency = isset($aConf['currency']) ? new Currency(Currency::getIdByIsoCode($aConf['currency'])) : $context->currency;
            $context->language = new Language($aConf['lang']);                    
        }
    }

    public function getBulletPointDefaultField() {
        return 'meta_keyword';
    }

    public function getEanDefaultField() {
        $globalEan = MLModule::gi()->getConfig('ean');
        $matchingAttributeGroups = MLFormHelper::getShopInstance()->getGroupedAttributesForMatching();

        if (isset($globalEan)) {
            $result = '';
            foreach ($matchingAttributeGroups as $matchingAttributeGroup) {
                if (is_array($matchingAttributeGroup) && array_key_exists($globalEan, $matchingAttributeGroup)) {
                    $result = $globalEan;
                    break;
                }
            }
        } else {
            $result = '';
        }

        return $result;
    }

    public function getManufacturerDefaultField() {
        $globalManufacturer = MLModule::gi()->getConfig('manufacturer');
        $matchingAttributeGroups = MLFormHelper::getShopInstance()->getGroupedAttributesForMatching();
        if (isset($globalManufacturer)) {
            $result = '';
            foreach ($matchingAttributeGroups as $matchingAttributeGroup) {
                if (is_array($matchingAttributeGroup) && array_key_exists($globalManufacturer, $matchingAttributeGroup)) {
                    $result = $globalManufacturer;
                    break;
                }
            }
        } else {
            $result = '';
        }

        return $result;
    }

    public function getManufacturerPartNumberDefaultField() {
        $globalManPartNumber = MLModule::gi()->getConfig('manufacturerpartnumber');
        $matchingAttributeGroups = MLFormHelper::getShopInstance()->getGroupedAttributesForMatching();
        if (isset($globalManPartNumber)) {
            $result = '';
            foreach ($matchingAttributeGroups as $matchingAttributeGroup) {
                if (is_array($matchingAttributeGroup) && array_key_exists($globalManPartNumber, $matchingAttributeGroup)) {
                    $result = $globalManPartNumber;
                    break;
                }
            }
        } else {
            $result = '';
        }

        return $result;
    }

    public function getBrandDefaultField() {
        $globalManufacturer = MLModule::gi()->getConfig('manufacturer');
        $matchingAttributeGroups = MLFormHelper::getShopInstance()->getGroupedAttributesForMatching();
        if (isset($globalManufacturer)) {
            $result = '';
            foreach ($matchingAttributeGroups as $matchingAttributeGroup) {
                if (is_array($matchingAttributeGroup) && array_key_exists($globalManufacturer, $matchingAttributeGroup)) {
                    $result = $globalManufacturer;
                    break;
                }
            }
        } else {
            $result = '';
        }
        return $result;
    }
}
