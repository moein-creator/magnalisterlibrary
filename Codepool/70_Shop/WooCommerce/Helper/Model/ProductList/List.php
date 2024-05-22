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
MLFilesystem::gi()->loadClass('Productlist_Helper_Model_ProductList_List_Abstract');

class ML_WooCommerce_Helper_Model_ProductList_List extends ML_Productlist_Helper_Model_ProductList_List_Abstract {
    protected $oI18n = null;
    /** @var ML_Database_Model_Query_Select $oSelect */
    protected $oSelect = null;
    protected $aFields = array();
    protected $aHeader = array();
    protected $aList = array();
    /** @var WC_Product $oLoadedProduct */
    protected $oLoadedProduct = null;
    protected $aAttributes = array();
    protected $aMixedData = array();

    public function __construct() {
        $this->oI18n = MLI18n::gi();
        $this->aAttributes = array(
            'categories' => $this->oI18n->get('WooCommerce_Productlist_Filter_sCategory'),
            'ean' => $this->oI18n->get('Productlist_Header_Field_sEan')
        );
    }

    public function clear() {
        $oRef = new ReflectionClass($this);
        foreach ($oRef->getDefaultProperties() as $sKey => $mValue) {
            $this->$sKey = $mValue;
        }
        $this->__construct();

        return $this;
    }

    public function setCollection($oSelect) {
        $this->oSelect = $oSelect;

        return $this;
    }

    public function getLoadedList() {
        if (count($this->aList) <= 0) {
            $aIDs = array();
            $aPrducts = $this->oSelect->getResult();
            foreach ($aPrducts as $aProduct) {
                $aIDs[] = $aProduct['ID'];
            }

            return $aIDs;
        } else {

            return array_keys($this->aList);
        }
    }

    public function getList() {
        $aProducts = $this->oSelect->getResult();
        foreach ($aProducts as $aProduct) {
            $oProduct = wc_get_product($aProduct['ID']);

            $oMLProduct = MLProduct::factory();
            /* @var $oMLProduct ML_WooCommerce_Model_Product */
            $oMLProduct->loadByShopProduct($oProduct);
            $this->oLoadedProduct = $oMLProduct;
            foreach ($this->aFields as $sField) {
                $mReturn = method_exists($this, $sField) ? $this->{$sField}() : $this->shopSystemAttribute($sField);
                if ($mReturn !== null) {
                    $this->aMixedData[$oMLProduct->get("id")][$sField] = $mReturn;
                }
            }
            $this->aList[$oProduct->get_id()] = $oMLProduct;
        }

        return $this->aList;
    }

    public function getMixedData($oProduct, $sKey) {
        $id = $oProduct->get("id");
        if (isset($this->aMixedData[$id]) && isset($this->aMixedData[$id][$sKey])) {

            return $this->aMixedData[$id][$sKey];
        } else {
            $this->oLoadedProduct = $oProduct;

            return method_exists($this, $sKey) ? $this->{$sKey}() : $this->shopSystemAttribute($sKey);
        }
    }

    public function quantityShop() {
        if ($this->oLoadedProduct === null) {
            $this->aFields[] = 'quantityShop';
            $this->aHeader['quantityShop'] = array(
                'title' => $this->oI18n->get('Productlist_Header_sStockShop'),
                'order' => 'CONVERT(productQuantity.meta_value, DECIMAL)',
                'type' => 'simpleText'
            );

            return $this;
        } else {

            return $this->oLoadedProduct->getStock();
        }
    }

    public function priceShop($sTypeVariant = null) {
        if ($this->oLoadedProduct === null) {
            if (!in_array(__function__, $this->aFields)) {
                $this->aFields[] = __function__;
                $this->aHeader[__function__] = array(
                    'title' => $this->oI18n->get('Productlist_Header_sPriceShop'),
                    'order' => 'CONVERT(productPrice.meta_value, DECIMAL)',
                    'type' => 'priceShop',
                    'type_variant' => $sTypeVariant === null ? 'priceShop' : $sTypeVariant
                );
            }

            return $this;
        }
    }

    public function shopSystemAttribute($sCode, $blUse = true, $sTitle = null, $sTypeVariant = null) {
        $wpFields = array(
            'id' => 'id',
            'releaseDate' => 'releasedate', //post_date
            'additionalText' => 'additionaltext', //post_content
            'name' => 'name',//post_title
            'active' => 'active', //post_status
            'metaTitle' => 'metaTitle', //post_name
            'inStock' => 'instock', //postmeta
            'weight' => 'weight',
            'width' => 'width',
            'length' => 'length',
            'height' => 'height',
            'sku' => 'sku',
        );

        $wpFields['manufacturer'] = MLModule::gi()->getConfig('manufacturer');
        $wpFields['mpn'] = MLModule::gi()->getConfig('manufacturerpartnumber');
        $wpFields['ean'] = MLModule::gi()->getConfig('ean');
        $wpFields['upc'] = MLModule::gi()->getConfig('upc');

        if ($this->oLoadedProduct === null) {
            if (!in_array($sCode, $this->aFields)) {
                $aFieldList = $wpFields;
                $sFieldOrder = isset($aFieldList[$sCode]) ? $aFieldList[$sCode] : $sCode;

                if ($sFieldOrder == 'ean' || $sFieldOrder == 'mpn' || $sFieldOrder == 'manufacturer') {
                    $sFieldOrder = $sFieldOrder;
                } elseif (array_key_exists($sCode, $wpFields)) {
                    $sFieldOrder = 'details.'.$sFieldOrder;
                }

                if ($blUse) {
                    $this->aFields[] = '_'.$sCode;
                    $this->aHeader['_'.$sCode] = array(
                        'title' => $sTitle === null ? (isset($this->aAttributes[$sCode]) ? $this->aAttributes[$sCode] : ucfirst($sCode)) : $sTitle,
//                        'order' => $sFieldOrder,
                        'type' => 'simpleText',
                        'type_variant' => $sTypeVariant === null ? 'simpleText' : $sTypeVariant
                    );
                }
            }

            return $this;
        } else {
            $sCode = substr($sCode, 1);
            $mValue = $this->oLoadedProduct->getProductField($sCode);

            return in_array($mValue, array('', null)) ? '-' : $mValue;
        }
    }
}