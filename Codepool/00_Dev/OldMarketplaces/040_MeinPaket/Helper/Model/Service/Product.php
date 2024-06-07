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
 * (c) 2010 - 2015 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */
class ML_MeinPaket_Helper_Model_Service_Product {

    /** @var ML_Database_Model_Table_Selection $oSelection     */
    protected $oSelection = null;
    protected $aSelectionData = array();

    /** @var ML_MeinPaket_Model_Table_MeinPaket_Prepare $oPrepare     */
    protected $oPrepare = null;

    /** @var ML_Shop_Model_Product_Abstract $oProduct     */
    protected $oProduct = null;
    protected $oVariant = null;
    protected $aData = null;

    public function __call($sName, $mValue) {
        return $sName . '()';
    }

    public function __construct() {
        $this->oPrepare = MLDatabase::factory('meinpaket_prepare');
        $this->oSelection = MLDatabase::factory('selection');
    }

    public function setProduct(ML_Shop_Model_Product_Abstract $oProduct) {
        $this->oProduct = $oProduct;
        $this->sPrepareType = '';
        $this->aData = null;
        return $this;
    }

    public function setVariant(ML_Shop_Model_Product_Abstract $oProduct) {
        $this->oVariant = $oProduct;
        return $this;
    }

    public function resetData() {
        $this->aData = null;
        return $this;
    }

    public function getData($blIsMaster = false) {
        if ($this->aData === null) {
            $this->oPrepare->init()->set('products_id', $this->oVariant->get('id'));
            $aData = array();
            $aData['ShopProductInstance'] = $this->oVariant;
            $aFields = array(
                'SKU',
                'EAN',
                'ManufacturerPartNumber',
                'Quantity',
                'Price',
                'BasePrice',
                'ShippingTime',
                'ShippingDetails',
                'Currency',
                'MarketplaceCategory',
                'CategoryAttributes',
                'RawShopVariation',
                'RawAttributesMatching',
                'variation_theme',
            );
            if($blIsMaster){
                $aFields = array_merge($aFields, array(
                        'ItemTitle',
                        'ItemTax',
                        'Description',
                        'ShortDescription',
                        'Images',
                        'Manufacturer',
                    )
                ); 
            }else{               
                $aFields =  array_merge($aFields, array(
                        'Variation',
                        'VariationId',
                        'ItemTitle',
                        'Images',
                        'Description',
                        'ShortDescription',
                    )
                );
            }
            foreach ($aFields as $sField) {
                if (method_exists($this, 'get' . $sField)) {
                    $mValue = $this->{'get' . $sField}();
                    if (is_array($mValue) && ($sField !== 'variation_theme')) {
                        foreach ($mValue as $sKey => $mCurrentValue) {
                            if (empty($mCurrentValue)) {
                                unset($mValue[$sKey]);
                            }
                        }

                        $mValue = empty($mValue) ? null : $mValue;
                    }

                    if ($mValue !== null) {
                        $aData[$sField] = $mValue;
                    }
                } else {
                    MLMessage::gi()->addWarn("function ML_MeinPaket_Helper_Model_Service_Product::get" . $sField . "() doesn't exist");
                }
            }

            if (empty($aData['BasePrice'])) {
                unset($aData['BasePrice']);
            }

            $this->aData = $aData;
        }

        return $this->aData;
    }

    public function convertOldShopVariationValue($oldShopVariation, $sIdentifier)
    {
        if (empty($oldShopVariation['mpKeyIsEncoded'])) {
            return $oldShopVariation;
        }

        // MP key is encoded and it needs to be converted with data from API
        unset($oldShopVariation['mpKeyIsEncoded']);

        $categoryDetails = $this->callGetCategoryDetails($sIdentifier);
        if (empty($categoryDetails['DATA']['attributes'])) {
            return array();
        }

        $encodedMPKeyToMPKeyMap = array();
        foreach (array_keys($categoryDetails['DATA']['attributes']) as $mpKey) {
            $encodedMPKeyToMPKeyMap[$this->encodeText($mpKey)] = $mpKey;
        }

        $decodedMatching = array();
        foreach ($oldShopVariation as $encodedMPKey => $matchedAttribute) {
            if (array_key_exists($encodedMPKey, $encodedMPKeyToMPKeyMap)) {
                $mpKey = $encodedMPKeyToMPKeyMap[$encodedMPKey];
                $matchedAttribute['AttributeName'] = $mpKey;
                $decodedMatching[$mpKey] = $matchedAttribute;
            }
        }

        return $decodedMatching;
    }

    protected function callGetCategoryDetails($sCategoryId)
    {
        if (empty($sCategoryId)) {
            $sCategoryId = 'splitAll';
        }

        $categoryDetails = array();
        try {
            $aResponse = MagnaConnector::gi()->submitRequestCached(array(
                'ACTION' => 'GetCategoryDetails',
                'DATA' => array('CategoryID' => $sCategoryId),
            ));
            if ($aResponse['STATUS'] == 'SUCCESS' && isset($aResponse['DATA']) && is_array($aResponse['DATA'])) {
                $categoryDetails = $aResponse;
            }
        } catch (MagnaException $e) {
        }

        return $categoryDetails;
    }

    protected function encodeText($sText, $blLower = true)
    {
        return MLHelper::gi('text')->encodeText($sText, $blLower);
    }

    protected function getSKU() {
        return $this->oVariant->getMarketPlaceSku();
    }

    protected function getItemTitle() {
        $iLangId = MLModule::gi()->getConfig('lang');
        $this->oVariant->setLang($iLangId);	

        return $this->oVariant->getName();
    }    
    
    protected function getManufacturer() {
        $iLangId = MLModule::gi()->getConfig('lang');
        return $this->oVariant->getManufacturer();
    }

    protected function getEAN() {
        return $this->oVariant->getModulField('general.ean', true);
    }
    
    protected function getManufacturerPartNumber() {
        return $this->oVariant->getModulField('general.manufacturerpartnumber', true);
    }

    protected function getImageSize(){
        $sSize = MLModule::gi()->getConfig('imagesize');
        $iSize = $sSize == null ? 500 : (int)$sSize;
        return $iSize;
    }
    
    protected function getImages() {
        $aOut = array();
        $iSize = $this->getImageSize();
        foreach ($this->oVariant->getImages() as $sImage) {
            try {
                $aImage = MLImage::gi()->resizeImage($sImage, 'product', $iSize, $iSize);
                $aOut[]['URL'] = $aImage['url'];
            } catch(Exception $ex) {
                // Happens if image doesn't exist.
            }
        }
        return $aOut;
    }

    protected function getQuantity() {
        if (isset($this->aSelectionData['stock'])) {
            return $this->aSelectionData['stock'];
        }

        $iQty = $this->oVariant->getSuggestedMarketplaceStock(
            MLModule::gi()->getConfig('quantity.type'), MLModule::gi()->getConfig('quantity.value')
        );

        return $iQty < 0 ? 0 : $iQty;
    }

    protected function getPrice() {
        if (isset($this->aSelectionData['price'])) {
            return round($this->aSelectionData['price'], 2);
        }

        return round($this->oVariant->getSuggestedMarketplacePrice(MLModule::gi()->getPriceObject()), 2);
    }

    protected function getBasePrice() {
        return $this->oVariant->getBasePrice();
    }

    protected function getItemTax() {
        $taxClassId = $this->oVariant->getTaxClassId();
        $taxMatchConfig = MLModule::gi()->getConfig('checkin.taxmatching');
        return $taxMatchConfig[$taxClassId];
    }

    protected function getDescription() {
        $sLongDescAttribute = MLModule::gi()->getConfig('checkin.longdesc');
        if (empty($sLongDescAttribute) === false) {
            return $this->oVariant->getAttributeValue($sLongDescAttribute);
        } else {
            return $this->oVariant->getDescription();
        }
    }

    protected function getShortDescription() {
        $sShortDescAttribute = MLModule::gi()->getConfig('checkin.shortdesc');
        if (empty($sShortDescAttribute) === false) {
            return $this->oVariant->getAttributeValue($sShortDescAttribute);
        } else {
            return $this->oVariant->getShortDescription();
        }
    }

    /**
     * @todo use new custom field to each shop(magento , shopware, prestashop) to return each product leadtimetoship
     */
    protected function getShippingTime() {
        return MLModule::gi()->getConfig('checkin.leadtimetoship');
    }
        
    protected function getShippingDetails() {
        $dShippingCost = $this->oPrepare->get('ShippingCost');
        $sShippingType = $this->oPrepare->get('ShippingType');
        
        return array(
            'ShippingCost' => $dShippingCost,
            'ShippingType' => $sShippingType,
        );
    }

    protected function getCurrency() {
        return MLModule::gi()->getConfig('currency');
    }

    protected function getMarketplaceCategory() {
        $sMarketplaceCategory = str_replace('_', '.', $this->oPrepare->get('PrimaryCategory'));
        return $sMarketplaceCategory;
    }

    protected function getVariation() {
        $aResult = $this->oVariant->getVariatonDataOptinalField(array('code','valueid','name','value'));
        return $aResult;
    }

    protected function getVariationId() {
        $aResult = $this->oVariant->getVariatonData();
        if (count($aResult) != 0) {
            return $this->oVariant->get('id');
        } else {
            return null;
        }
    }

    protected function getRawShopVariation()
    {
        return $this->oVariant->getPrefixedVariationData();
    }

    protected function getRawAttributesMatching()
    {
        return $this->convertOldShopVariationValue(
            $this->oPrepare->get('ShopVariation'),
            $this->oPrepare->get('VariationConfiguration')
        );
    }

    protected function getvariation_theme()
    {
        $variationTheme = $this->oPrepare->get('variation_theme');

        // In case of old prepare data, try to reconstruct variation theme. Old implementation only had variation attributes,
        // therefore if matching exists all matched attributes are variation theme attributes
        if (is_null($variationTheme)) {
            $variationConfiguration = $this->oPrepare->get('VariationConfiguration');
            $attributesMatching = $this->getRawAttributesMatching();
            if (!empty($variationConfiguration) && !empty($attributesMatching)) {
                $variationTheme = array($variationConfiguration => array_keys($attributesMatching));
            }
        }

        if (!is_array($variationTheme)) {
            $variationTheme = array();
        }

        return $variationTheme;
    }

    protected function getCategoryAttributes()
    {
        $nonVariationAttributes = array();

        $variationTheme = $this->getvariation_theme();
        $variationThemeAttributes = array_shift($variationTheme);

        /* @var $attributesMatchingService ML_Modul_Helper_Model_Service_AttributesMatching */
        $attributesMatchingService = MLHelper::gi('Model_Service_AttributesMatching');
        $allMatchedAttributes = $attributesMatchingService->mergeConvertedMatchingToNameValue(
            $this->getRawAttributesMatching(),
            $this->oVariant,
            $this->oProduct
        );

        foreach ($allMatchedAttributes as $mpCode => $matchedAttribute) {
            if (!in_array($mpCode, $variationThemeAttributes)) {
                $nonVariationAttributes[$mpCode] = $matchedAttribute;
            }
        }

        return $nonVariationAttributes;
    }
}
