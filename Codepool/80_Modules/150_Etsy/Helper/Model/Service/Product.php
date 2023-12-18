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

class ML_Etsy_Helper_Model_Service_Product {

    /** @var ML_Database_Model_Table_Selection $oSelection */
    protected $oSelection = null;
    protected $aSelectionData = array();

    /** @var ML_Etsy_Model_Table_Etsy_Prepare $oPrepare */
    protected $oPrepare = null;

    /** @var ML_Shop_Model_Product_Abstract $oProduct */
    protected $oProduct = null;
    /** @var ML_Shop_Model_Product_Abstract */
    protected $oVariant = null;
    protected $aData = null;

    public function __call($sName, $mValue) {
        return $sName.'()';
    }

    public function __construct() {
        $this->oPrepare = MLDatabase::factory('etsy_prepare');
        $this->oSelection = MLDatabase::factory('selection');
    }

    public function setProduct(ML_Shop_Model_Product_Abstract $oProduct) {
        $this->oProduct = $oProduct;
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

    public function getData() {
        if ($this->aData === null) {
            $this->oPrepare->init()->set('products_id', $this->oVariant->get('id'));
            $aData = array();
            foreach (
                array(
                    'SKU',
                    'MasterSKU',
                    'Images',
                    'Quantity',
                    'Price',
                    'Attributes',
                    'Whomade',
                    'Whenmade',
                    'IsSupply',
                    'Language',
                    'Currency',
                    'ShippingTemplate',
                    'Primarycategory',
                    'Description',
                    'Title',
                    'ProductId',
                    'CategoryAttributes',
                    'MasterTitle',
                    'MasterDescription',
                ) as $sField) {

                if (method_exists($this, 'get'.$sField)) {
                    $mValue = $this->{'get'.$sField}();
                    if (is_array($mValue)) {
                        foreach ($mValue as $sKey => $mCurrentValue) {
                            if (empty($mCurrentValue)) {
                                unset ($mValue[$sKey]);
                            }
                        }
                        $mValue = empty($mValue) ? null : $mValue;
                    }
                    if ($mValue !== null) {
                        $aData[$sField] = $mValue;
                    }
                } else {
                    MLMessage::gi()->addWarn("function  ML_Etsy_Helper_Model_Service_Product::get".$sField."() doesn't exist");
                }
            }
            if (empty($aData['BasePrice'])) {
                unset($aData['BasePrice']);
            }
            $this->aData = $aData;
        }
        return $this->aData;
    }

    protected function getSKU() {
        return $this->oVariant->getMarketPlaceSku();
    }

    protected function getMasterSKU() {
        if ($this->oProduct->getVariantCount() === 1) {
            return $this->oVariant->getMarketPlaceSku();
        }
        return $this->oProduct->getMarketPlaceSku();
    }

    protected function getImageSize() {
        $sSize = MLModul::gi()->getConfig('imagesize');
        $iSize = $sSize == null ? 500 : (int)$sSize;
        return $iSize;
    }

    protected function getImages() {
        $aImagesPrepare = $this->oPrepare->get('Image');
        $iSize = $this->getImageSize();
        $aOut = array();
        if (empty($aImagesPrepare) === false) {
            $aImages = $this->oVariant->getImages();
            $aImages = empty($aImages) ? $this->oProduct->getImages() : $aImages;

            foreach ($aImages as $sImage) {
                try {
                    $aImage = MLImage::gi()->resizeImage($sImage, 'products', $iSize, $iSize);
                    $aOut[]['URL'] = $aImage['url'];
                } catch (Exception $ex) {
                    // Happens if image doesn't exist.
                }
            }
        }

        return $aOut;
    }

    protected function getQuantity() {
        $aStockConf = MLModul::gi()->getStockConfig();
        $iQty = $this->oVariant->getSuggestedMarketplaceStock(
            $aStockConf['type'], $aStockConf['value'],(int)$aStockConf['max'] > 0 ?$aStockConf['max']:null
        );
        return $iQty < 0 ? 0 : $iQty;
    }

    protected function getPrice() {
        if (isset($this->aSelectionData['price'])) {
            return $this->aSelectionData['price'];
        } else {
            return $this->oVariant->getSuggestedMarketplacePrice(MLModul::gi()->getPriceObject());
        }
    }

    protected function getBasePrice() {
        return $this->oVariant->getBasePrice();
    }

    protected function getMarketplaceCategories() {
        return array(
            $this->oPrepare->get('PrimaryCategory'),
        );
    }

    protected function getAttributes() {
        $iCategorie = $this->oPrepare->get('PrimaryCategory');
        if (!empty($iCategorie)) {
            $aCatAttributes = $this->oPrepare->get('Attributes');
            if (isset($aCatAttributes[$iCategorie])) {
                $aAttributes = array();
                foreach ($aCatAttributes[$iCategorie]['specifics'] as $aCatAttribute) {
                    $aAttributes = array_merge($aAttributes, $aCatAttribute);
                }
                return $aAttributes;
            }
        }
        return array();
    }

    protected function getWhenmade() {
        return $this->oPrepare->get('Whenmade');
    }

    protected function getWhomade() {
        return $this->oPrepare->get('Whomade');
    }

    protected function getIsSupply() {
        return $this->oPrepare->get('IsSupply');
    }

    protected function getLanguage() {
        return MLModul::gi()->getConfig('shop.language');
    }

    protected function getCurrency() {
        return MLModul::gi()->getConfig('currency');
    }

    protected function getShippingTemplate() {
        return $this->oPrepare->get('ShippingTemplate');
    }

    protected function getVerified() {
        return $this->oPrepare->get('Verified');
    }

    protected function getPrimarycategory() {
        return $this->oPrepare->get('Primarycategory');
    }

    protected function getTitle() {
        $sTitle = $this->oPrepare->get('Title');
        return (!empty($sTitle) ? $sTitle : $this->oVariant->getName());
    }

    protected function getDescription() {
        $sDescription = $this->oPrepare->get('Description');


        if (!empty($sDescription)) {
            return $sDescription;
        } else {
            /** @var $oStringHelper ML_Modul_Helper_String */
            $oStringHelper = MLHelper::gi('String');
            $sDescription = $this->oVariant->getDescription();
            return $oStringHelper->removeHtml($sDescription);
        }
    }

    protected function getMasterTitle() {
        return $this->getTitle();
    }

    protected function getMasterDescription() {
        return $this->getDescription();
    }

    protected function getProductId() {
        return $this->oPrepare->get('products_id');
    }

    protected function getPreparedTS() {
        return $this->oPrepare->get('PreparedTS');
    }

    protected function getCategoryAttributes() {
        $aVariants = $this->oVariant->getVariatonDataOptinalField(array('name', 'value', 'valueid', 'code'));
        $shopVariants = $this->oPrepare->get('shopVariation');
        if (!is_array($shopVariants)) {
            return json_encode(array());
        }
        // Attributes for simple Items
        if (empty($aVariants)) {
            foreach ($shopVariants as $key => $shopVariations) {
                if (strpos($shopVariations['Code'], 'pp_') === 0) {
                    $fAttVal = $this->oProduct->getAttributeValue($shopVariations['Code']);
                    $aVariants[] = array(
                        'code'    => $shopVariations['Code'],
                        'valueid' => current(array_keys($fAttVal)),
                        'name'    => $shopVariations['AttributeName'],
                        'value'   => current($fAttVal)
                    );
                }
            }
        }
        $shopVariants = $this->manipulateShopVariationData($shopVariants);
#MLMessage::gi()->addDebug(__FUNCTION__.' '.__LINE__.' $shopVariants', $shopVariants);
        $propertyValue = '';
        $propertyName = '';
        $propertyId = '';
        $results = array();
        $valueIds = array();

        foreach ($shopVariants as $key => $shopVariations) {
            if (is_array($shopVariations['Values'])) {
                foreach ($shopVariations['Values'] as $shopVariationKey => $shopVariationValue) {
                    foreach ($aVariants as $aVariant) {

                        if (
                            $shopVariationValue['Shop']['Key'] == $aVariant['valueid']
                            && strtolower($shopVariationValue['Shop']['Value']) == strtolower($aVariant['value'])
                        ) {
                            $propertyId = explode('-', $shopVariationValue['Marketplace']['Key'])[0];
                            if (strpos($shopVariationValue['Marketplace']['Key'], '-') !== false) {
                                $valueIds = array(explode('-', $shopVariationValue['Marketplace']['Key'])[1]);
                            } else {
                                $valueIds = array(explode('-', $shopVariationValue['Marketplace']['Key']));
                            }
                        }
                        if ($aVariant['code'] === $shopVariations['Code']) {
                            if ($key === 'Custom1') {
                                $propertyValue = $aVariant['value'];
                                $propertyName = $aVariant['name'];
                                $propertyId = 513;
                                $valueIds = array();
                            } elseif ($key === 'Custom2') {
                                $propertyValue = $aVariant['value'];
                                $propertyName = $aVariant['name'];
                                $propertyId = 514;
                                $valueIds = array();
                            }
                        }
                        // these should not be empty
                        if (empty($propertyName) && !empty($aVariant['name'])) {
                                $propertyName = $aVariant['name'];
                        }
                        if (empty($propertyValue) && !empty($aVariant['value'])) {
                                $propertyValue = $aVariant['value'];
                        }
                    }
                }
            } else {
                // Attributes stored directly in the Preparation
                if (strpos($shopVariations['Values'], '-')) {
                    list($propertyId, $valueIds) = explode('-', $shopVariations['Values']);
                    $valueIds = array($valueIds);
                    $propertyName = $shopVariations['AttributeName'];
                    $propertyValue = '';
                }
            }

            // if not matched value is found don't add it to the request
            if (empty($propertyId)) {
                continue;
            }

            $results["property_values"][] =
                array(
                    "property_id"   => $propertyId,
                    "value_ids"     => $valueIds,
                    "property_name" => ucfirst($propertyName),
                    "values"        => array($propertyValue),
                );
            $propertyName = '';
        }

        return json_encode($results);
    }

    /**
     * it is used only in Shopify to fix some old data structure
     * @param $shopVariants
     * @return array
     */
    protected function manipulateShopVariationData($shopVariants) {
        return $shopVariants;
    }
}
