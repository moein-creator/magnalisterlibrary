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

class ML_WooCommerce_Model_Product extends ML_Shop_Model_Product_Abstract {
    /**
     * @var WC_Product $oProduct
     */
    protected $oProduct = null;

    /**
     * image-cache
     * @var null default
     * @var array loaded images for not calculating multiple times
     */
    protected $aImages = null;

    public function getId() {
        $this->load();

        return $this->oProduct->get_id();
    }

    /**
     * Loads shop-specific product(-data).
     * Uses self::getMarketPlaceSku() to identify the product.
     *
     * Should check, if shop-specific product(-data) already is loaded
     *
     * @throws Exception product don't exists in shop
     * @return $this
     */
    protected function loadShopProduct() {
        if ($this->oProduct === null) {//not loaded
            $this->oProduct = false; //not null
            if ($this->get('parentid') == 0) {
                $oProduct = $this;
            } else {
                $oProduct = $this->getParent();
            }

            $sKey = MLDatabase::factory('config')->set('mpid', 0)->set('mkey', 'general.keytype')->get('value');

            if ($sKey == 'pID') {
                $oShopProduct = $this->getProductById($oProduct->get('productsid'));
            } else {
                $oShopProduct = new WC_Product(wc_get_product_id_by_sku($oProduct->get('productssku')));
            }

            if (empty($oShopProduct->get_id()) && $this->get('id') !== 0) {
                $iId = $this->get('id');
                $this->delete();
                MLMessage::gi()->addDebug('One of selected product was deleted from shop now it is deleted from magnalister list too, please refresh the page : '.$iId);
                throw new Exception;
            }

            $aData = $this->get('shopdata');
            $this->oProduct = $oShopProduct;

            if ($this->get('parentid') != 0) {//is variant
                $this->loadByShopProduct($oShopProduct, $this->get('parentid'), $aData);
            }
        }


        return $this;
    }

    /**
     * Gets all loaded variations for this product
     */
    protected function loadShopVariants() {
        $iVariationCount = $this->getVariantCount();
        if ($iVariationCount > MLSetting::gi()->get('iMaxVariantCount')) {
            $this
                ->set('data', array(
                    'messages' => array(
                        MLI18n::gi()->get('Productlist_ProductMessage_sErrorToManyVariants', array(
                            'variantCount'    => $iVariationCount,
                            'maxVariantCount' => MLSetting::gi()->get('iMaxVariantCount')
                        ))
                    )
                ))
                ->save();
            MLMessage::gi()->addObjectMessage($this,
                MLI18n::gi()->get('Productlist_ProductMessage_sErrorToManyVariants', array(
                    'variantCount'    => $iVariationCount,
                    'maxVariantCount' => MLSetting::gi()->get('iMaxVariantCount')
                )));
        } else {
            /** @var WC_Product $aDetails */
            $productVariants = MLHelper::gi('model_product')->getProductDetails($this->getLoadedProduct()->get_id());

            foreach ($productVariants as $productVariant) {
                $aVariant = array();
                foreach ($productVariant['attributes'] as $variantAttributeKey => $variantAttributeValue) {
                    $aVariant['variation_id'] = $productVariant[$this->idVariation()];
                    $aVariant['info'][] = array(
                        'name'  => $variantAttributeKey,
                        'value' => $variantAttributeValue
                    );
                }

                if (isset($aVariant['variation_id'])) {
                    $this->addVariant(
                        MLProduct::factory()->loadByShopProduct($this->oProduct, $this->get('id'), $aVariant)
                    );
                }
            }

            if (!count($productVariants)) {
                $aVariant = array();
                $aVariant['variation_id'] = $this->getLoadedProduct()->get_id();
                $aVariant['info'][] = array();

                if (isset($aVariant['variation_id'])) {
                    $this->addVariant(
                        MLProduct::factory()->loadByShopProduct($this->oProduct, $this->get('id'), $aVariant)
                    );
                }
            }
        }

        return $this;
    }

    /**
     * load data with shop-product-info (main-product)
     * if not exist, create entree in db
     * also creates variants of main-product
     * @use self::addVariant()
     *
     * @param mixed $mProduct depends by shop
     * @param integer $iParentId $this->get("parentid");
     * @param mixed $mData Shopspecific
     *
     * @return mixed
     * @throws Exception
     */
    public function loadByShopProduct($oProduct, $iParentId = 0, $mData = null) {
        $oVariation = array();
        $this->oProduct = $oProduct;
        /* for product which has no reference number ,random refrence is inserted becaue it will made problem when product key is Article number */
        $sKey = MLDatabase::factory('config')->set('mpid', 0)->set('mkey', 'general.keytype')->get('value');
        $this->aKeys = array($sKey == 'pID' ? 'marketplaceidentid' : 'marketplaceidentsku');
        $this->set('parentid', $iParentId)->aKeys[] = 'parentid';
        $sMessage = array();
        $idProduct = $this->oProduct->get_id();

        if ($mData) {
            if (array_key_exists('variation_id', $mData)) {
                $oVariation = $this->getOneArticleVariant($idProduct, $mData['variation_id']);
            }
        }

        if ($iParentId == 0 || !$oVariation) {
            //if product doesn't have variation
            $sSku = get_post_meta($idProduct, '_sku', true);
            $this
                ->set('marketplaceidentid', $idProduct)
                ->set('marketplaceidentsku', empty($sSku) ? '__ERROR__'.$idProduct : $sSku)
                ->set('productsid', $idProduct)
                ->set('productssku', empty($sSku) ? '__ERROR__'.$idProduct : $sSku)
                ->set('shopdata', array())
                ->set('data', array('messages' => $sMessage))
                ->save()
                ->aKeys = array('id');

            if ($sKey !== 'pID' && empty($sSku)) {
                MLMessage::gi()->addObjectMessage(
                    $this, MLI18n::gi()->data('Productlist_Cell_Product_NoSku')
                );
            }
            if ($sKey !== 'pID' && $this->hasVariantsSkuDuplicates()) {
                MLMessage::gi()->addObjectMessage(
                    $this, MLI18n::gi()->data('Productlist_Cell_Product_VariantsSkuDuplicatesExists')
                );
            }

        } else {
            $this
                ->set('marketplaceidentid', $oVariation[$this->idVariation()].'_'.$idProduct)
                ->set('marketplaceidentsku', $oVariation['sku'])
                ->set("productsid", $oVariation[$this->idVariation()])
                ->set('shopdata', $mData)
                ->set("productssku", $oVariation['sku'])
                ->set('data', array('messages' => $sMessage));
            if ($this->exists()) {
                $this->aKeys = array('id');
                $this->save();
            } else {
                $this->save()->aKeys = array('id');
            }
        }

        return $this;
    }

    static $aVariantSkuDuplicated = array();

    public function hasVariantsSkuDuplicates() {
        global $wpdb;
        $sProductId = $this->oProduct->get_id();
        if (!isset(self::$aVariantSkuDuplicated[$sProductId])) {
            $oDb = MLDatabase::getDbInstance();
            $sSql = "
            SELECT (
            SELECT COUNT(*) FROM $wpdb->posts 
            WHERE (post_parent = $sProductId AND post_type = 'product_variation') OR (ID = $sProductId AND post_type = 'product') AND post_status = 'publish'
            ) AS `total`, COUNT(DISTINCT skus) AS `unique`
            FROM ( SELECT if(TRIM(wpm.meta_value) = '', '__ERROR__', wpm.meta_value) AS skus
            FROM ( SELECT *
            FROM $wpdb->posts 
            WHERE (post_parent = $sProductId AND post_type = 'product_variation') OR (ID = $sProductId AND post_type = 'product')AND post_status = 'publish') AS wp 
            LEFT JOIN
            $wpdb->postmeta as wpm
            ON wp.ID = wpm.post_id
            WHERE wpm.meta_key = '_sku') as result;
        ";
            $result = MLDatabase::getDbInstance()->fetchArray($sSql)[0];

            self::$aVariantSkuDuplicated[$sProductId] = $result['total'] !== $result['unique'];
        }
        return self::$aVariantSkuDuplicated[$sProductId];
    }


    /**
     * gets one article variant
     *
     * @param int $articleId
     * @param int $articleVariantId
     *
     * @return array
     */
    private function getOneArticleVariant($articleId, $articleVariantId) {
        /** @var WC_Product_Variable $product */
        $product = new WC_Product_Variable($articleId);
        $variations = MLHelper::gi('model_product')->getAvailableVariationsFromWooCommerceProductVariable($product);
        /*
         * !!! IMPORTANT !!! - Cant use "get_available_variations" Function - because there are filters
         *      - "Hide out of stock variations if 'Hide out of stock items from the catalog' is checked."
         *      - "Filter 'woocommerce_hide_invisible_variations' to optionally hide invisible variations (disabled variations and variations with empty price)."
         */
        //$variations = $product->get_available_variations();
        $variableProduct = array();
        foreach ($variations as $variation) {
            if ($variation[$this->idVariation()] == $articleVariantId) {
                $variableProduct = $variation;
                break;
            }
        }

        return $variableProduct;
    }

    /**
     * Try go get Master Product with all variations if they product has some
     *
     * if there are some problems, see getByMarketplaceSKU method of magneto_model_product
     */
    public function createModelProductByMarketplaceSku($sSku) {
        $aOut = array('master' => null, 'variant' => null);
        $oMyTable = MLProduct::factory();
        $oShopProduct = null;
        $checkProductOrVariationId = null;
        if (MLDatabase::factory('config')->set('mpid', 0)->set('mkey', 'general.keytype')->get('value') == 'pID') {
            $sIdent = 'marketplaceidentid';
            if (strpos($sSku, '_') !== false) {
                $aIds = explode("_", $sSku);
                if (is_int($aIds[1])) {
                    $checkProductOrVariationId = $aIds[1];
                }
            } else if (is_int($sSku)) {
                $checkProductOrVariationId = wc_get_product_id_by_sku($sSku);
            }
        } else {
            $sIdent = 'marketplaceidentsku';
            $checkProductOrVariationId = wc_get_product_id_by_sku($sSku);
        }
        if ($checkProductOrVariationId !== null) {
            // if Product is a variation try to get parent product from variation product
            if ($this->isProductVariation($checkProductOrVariationId)) {
                /** @var WC_Product_Variation $oVariant */
                $oVariant = new WC_Product_Variation($checkProductOrVariationId);
                $sParentSku = $oVariant->get_parent_data()['sku'];
                $oShopProduct = new WC_Product(wc_get_product_id_by_sku($sParentSku));
            } else {
                /** @var WC_Product $oShopProduct */
                $oShopProduct = new WC_Product($checkProductOrVariationId);
            }

            if ($oShopProduct) {
                $oMyTable->loadByShopProduct($oShopProduct);
                $oMyTable->getVariants();
                if ($oMyTable->get($sIdent) === $sSku) {
                    $aOut['master'] = $oMyTable;
                }
                foreach ($oMyTable->getVariants() as $oVariant) {
                    if ($oVariant->get($sIdent) === $sSku) {
                        $aOut['variant'] = $oVariant;
                    }
                }
            }
        }

        return $aOut;
    }

    /**
     * @inheritDoc
     */
    public function getSku() {
        if ($this->hasVariantsSkuDuplicates()) {
            return '';
        }
        return (strpos($sku = parent::getSku(), '__ERROR__') !== false) ? '' : $sku;
    }

    /**
     * @inheritDoc
     */
    public function getProductlistSku() {
        return (strpos($sku = parent::getProductlistSku(), '__ERROR__') !== false) ? '' : $sku;
    }

    /**
     * @inheritDoc
     */
    public function getMarketPlaceSku() {
        return (strpos($sku = parent::getMarketPlaceSku(), '__ERROR__') !== false) ? '' : $sku;
    }

    /**
     * Gets the short description of the item
     * @return string
     */
    public function getShortDescription() {
        $this->load();
        /** @var WC_Product $aDesc */
        $aDesc = new WC_Product($this->oProduct->get_id());

        return $aDesc->get_short_description();
    }

    /**
     * Gets product description
     * @return string
     */
    public function getDescription() {
        $this->load();
        /** @var WC_Product $aDesc */
        $aDesc = new WC_Product($this->oProduct->get_id());
        return $aDesc->get_description();
    }

    /**
     * Returns the link to edit the product in the shop.
     *
     * @return string (url)
     */
    public function getEditLink() {
        $this->load();

        return get_edit_post_link($this->oProduct->get_id());
    }

    /**
     * return product detail front url
     * @return string
     */
    public function getFrontendLink() {
        $sFrontBaseUrl = get_site_url();
        $productDetailUrl = $sFrontBaseUrl.'?post_type=product&p='.$this->getLoadedProduct()->get_id();

        return $productDetailUrl;
    }

    /**
     * Returns the url of the main item image in the requested resolution.
     * If the url does not yet exist an image will be generated.
     *
     * @param int $iX
     * @param int $iY
     *
     * @return string
     */
    public function getImageUrl($iX = 40, $iY = 40) {
        $this->load();
        try {
            $aImg = wp_get_attachment_image_src(get_post_thumbnail_id($this->oProduct->get_id()),
                'single-post-thumbnail');
            if ($aImg) {
                $aImg = $aImg[0];

                return MLImage::gi()->resizeImage($aImg, 'products', $iX, $iY, true);
            } else {
                return '';
            }
        } catch (Exception $oEx) {
            return '';
        }
    }

    /**
     * Gets all images for the current item.
     * @return array
     *    /file/path/to/image/
     */
    public function getImages() {
        if ($this->aImages === null) {
            $this->load();
            $aImgs = array();
            $idProduct = $this->oProduct->get_id();
            $aGalleryImages = MagnalisterWooCommerceAlias::getProductHelper()->getWCProductImages($idProduct);
            $variationImages = MagnalisterWooCommerceAlias::getProductHelper()->getProductDetails($this->getLoadedProduct()->get_id());
            if (!empty($variationImages)) {
                foreach ($variationImages as $aVariation) {
                    $aVarCoverImage = $aVariation['image']['url'];
                    $aVarImage = $aGalleryImages;
                    $aImgs = array_merge($aImgs, array($aVarCoverImage), $aVarImage);
                }
            } else {
                $aImgs = $aGalleryImages;
            }
            $aCoverImg = MLHelper::gi('model_product')->getFeaturedWCProductImage($idProduct);

            $aImgs = array_merge(array($aCoverImg), $aImgs);

            if (empty($aImgs) || $aImgs[0] == '') {
                $aImgs = array();
            }

            $this->aImages = array_unique($aImgs);
        }

        return $this->aImages;
    }

    /**
     * Gets the meta description of the item.
     * WooCommerce does not support meta description
     * @return string
     */
    public function getMetaDescription() {
        return $this->getShortDescription();
    }

    /**
     * Gets the meta keywords of the item. Keywords need to be comma-separated string.
     * @return string
     */
    public function getMetaKeywords() {
        $aKeywords = array();
        $aTagObjects = get_the_terms($this->getLoadedProduct()->get_id(), 'product_tag');
        if ($aTagObjects) {
            /** @var WP_Term $oTagObject */
            foreach ($aTagObjects as $oTagObject) {
                $aKeywords[] = $oTagObject->name;
            }
        }

        return implode(',', $aKeywords);
    }

    public function getModulField($sFieldName, $blGeneral = false) {
        $this->load();
        if ($blGeneral) {
            $sField = MLDatabase::factory('config')->set('mpid', 0)->set('mkey', $sFieldName)->get('value');
        } else {
            $sField = MLModul::gi()->getConfig($sFieldName);
        }
        $sField = empty($sField) ? $sFieldName : $sField;

        return $this->getProductField($sField);
    }

    /**
     * @var WC_Product
     */
    protected $oRealProduct = null;

    /**
     * @return WC_Product|WC_Product_Variation
     * @throws Exception
     */
    protected function getArticleDetail() {
        if ($this->oProduct === null) {
            $this->load();
        }
        if ($this->oRealProduct === null) {
            $oProduct = $this->wcObjProduct();
            if (!is_object($oProduct)) {
                MLMessage::gi()->addDebug('Product doesn\'t exist . ID : '.$this->get('productsid'));
                $this->delete(); //delete from magnalister_product
                throw new Exception;
            }
            $this->oRealProduct = $oProduct;

            return $oProduct;
        } else {

            return $this->oRealProduct;
        }
    }

    /**
     * Gets the title of the product.
     * @return string
     */
    public function getName() {
        $this->load();
        $product = $this->wcObjProduct();

        return $product->get_name();
    }

    /**
     * Gets the price of current shop without special offers.
     * @param bool $blGros
     * @param bool $blFormated
     * @return mixed
     *     A string if $blFormated == true
     *     A float if $blFormated == false
     */
    public function getShopPrice($blGros = true, $blFormated = false) {
        $this->load();
        $mReturn = $this->getPrice($blFormated);

        return $mReturn;
    }

    /**
     * Gets the price depending on the marketplace config.
     * @param \ML_Shop_Model_Price_Interface $oPrice
     * @param bool $blGros
     * @param bool $blFormated
     *
     * @return mixed
     *     A string if $blFormated == true
     *     A float if $blFormated == false
     *
     */
    public function getSuggestedMarketplacePrice(\ML_Shop_Model_Price_Interface $oPrice, $blGros = true, $blFormated = false) {
        $this->load();
        $mpDBcurrency = MLModul::gi()->getConfig('currency');
        $shopCurrency = strtoupper(MLHelper::gi('model_price')->getShopCurrency());
        $mpCurrency = strtoupper(empty($mpDBcurrency) ? getCurrencyFromMarketplace(MLModul::gi()->getMarketPlaceId()) : $mpDBcurrency);

        $aConf = $oPrice->getPriceConfig();
        $sPriceKind = $aConf['kind'];
        $fPriceFactor = (float)$aConf['factor'];
        $iPriceSignal = $aConf['signal'];

        $fShopPrice = (float)$this->getPriceProduct();
        if (!empty($mpCurrency) && MLModul::gi()->getConfig('exchangerate_update')) {
            $fShopPrice *= MLModul::gi()->getExchangeRateRatio($shopCurrency, $mpCurrency) ?: $fShopPrice;
        }

        if ($sPriceKind == 'addition') {
            $fBrutPrice = $fShopPrice + $fPriceFactor;
        } else {
            $fBrutPrice = $fShopPrice + (($fPriceFactor / 100) * $fShopPrice);
        }

        if ($iPriceSignal !== null) {
            if (strlen((string)$iPriceSignal) == 1) {
                $fBrutPrice = (0.1 * (int)($fBrutPrice * 10)) + ((int)$iPriceSignal / 100);
            } else {
                $fBrutPrice = ((int)$fBrutPrice) + ((int)$iPriceSignal / 100);
            }
        }

        return $blFormated ? MLHelper::gi('model_price')->getPriceFormatted($fBrutPrice) : $fBrutPrice;
    }

    /**
     * Gets the price of product
     * @param $blFormatted
     *
     * @return mixed
     *     A string if $blFormated == true
     *     A float if $blFormated == false
     *
     */
    protected function getPrice($blFormatted) {
        $price = $this->getPriceProduct();
        return $this->formattedPrice($price, $blFormatted);
    }

    /**
     * Get shop price product for WooCoomerce
     *
     * @return float
     */
    public function getPriceProduct() {
        $product = $this->wcObjProduct();

        return wc_get_price_including_tax($product, array(
            'qty'   => '',
            'price' => $product->get_price(),
        ));
    }

    /**
     * Formatting price
     *
     * @param $blFormated
     *
     * @return mixed
     *     A string if $blFormated == true
     *     A float if $blFormated == false
     *
     */
    public function formattedPrice($price, $blFormated) {

        if ($blFormated) {

            return MLHelper::gi('model_price')->getPriceByCurrency($price, null, true);
        }

        return MLHelper::gi('model_price')->getPriceByCurrency($price, null);
    }

    /**
     * @return WC_Product
     */
    public function getLoadedProduct() {
        if ($this->oProduct === null) {
            $this->load();
        }

        return $this->oProduct;
    }

    /**
     * returns tax-value for product
     *
     * @param array $aAddressData
     *
     * @return float
     */
    public function getTax($aAddressData = null) {
        $product = wc_get_product($this->get('productsid'));
        $rates = WC_Tax::get_rates($product->get_tax_class());
        $taxSum = 0;
        foreach ($rates as $rate) {
            $taxSum += $rate['rate'];
        }

        return $taxSum;
    }

    /**
     * Gets tax class id for current product.
     *
     * @return int Tax Class Id.
     */
    public function getTaxClassId() {
        $product = wc_get_product($this->get('productsid'));
        $taxClassIds = WC_Tax::get_rates_for_tax_class($product->get_tax_class());

        foreach ($taxClassIds as $taxClassId) {

            return $taxClassId->tax_rate_id;
        }
    }

    /**
     * Returns product stock
     *
     *  If product has variations normally stock is not managed but it can be.
     *      So if master product has managed stock and a variation doesn't manage stock "get_stock_quantity()" will return stock of master
     *
     * @return int
     */
    public function getStock() {
        $product = wc_get_product($this->get('productsid'));

        // If stock for a given product or variation is not managed return 999 or 0 based on the stock status
        if (!$product->get_manage_stock()) {
            $quantity = 0;

            switch ($product->get_stock_status()) {
                case "instock":
                {
                    $quantity = 999;
                    break;
                }
                case "outofstock":
                {
                    $quantity = 0;
                    break;
                }
                case "onbackorder":
                { // not supported yet
                    break;
                }
            }

            return $quantity;
        }

        // We need to check for get_stock_quantity() because if product has variations it return null otherwise see below
        if ($product->get_stock_quantity() !== null && $product->get_manage_stock()) {
            return $product->get_stock_quantity();
        }

        return 999;
    }

    /**
     * Get the quantity of the product based on the module configuation.
     * Also cares about options-child-articles.
     *
     * @param string $sType
     * @param float $fValue
     * @param null $iMax
     *
     * @return int
     */
    public function getSuggestedMarketplaceStock($sType, $fValue, $iMax = null) {
        if (
            MLModul::gi()->getConfig('inventar.productstatus') == 'true'
            && !$this->isActive()
        ) {
            return 0;
        }
        if ($sType == 'lump') {
            $iStock = $fValue;
        } else {
            $iStock = $this->getStock();
            if ($sType == 'stocksub') {
                $iStock = $iStock - $fValue;
            }

            if (!empty($iMax)) {
                $iStock = min($iStock, $iMax);
            }
        }

        $iStock = (int)$iStock;

        return $iStock > 0 ? $iStock : 0;
    }

    /**
     * @return array
     */
    public function getVariatonData() {
        return $this->getVariatonDataOptinalField(array('name', 'value'));
    }

    /**
     * @param array $aFields
     *
     * Gets distinct data of variant.
     *
     * eg:
     * array(
     *  array(
     *      'name'=>'color'
     *      'value'=>'red'
     *  ),...
     * );
     * if empty, variant == master     *
     *
     * @return array
     */
    public function getVariatonDataOptinalField($aFields = array()) {
        $aOut = array();
        $productVariantId = $this->getArticleDetail()->get_id();

        if ($this->isProductVariation($productVariantId) == false) {
            return $aOut;
        }

        $product = new WC_Product_Variation($productVariantId);
        $aProperties = $product->get_variation_attributes();

        foreach ($aProperties as $key => $value) {
            $aData = array();
            $sKey = null;
            if (in_array('name', $aFields, true) && strpos($key, '_pa_')) {
                $sKey = explode('attribute_pa_', $key)[1];
            }
            if (in_array('name', $aFields, true) && !strpos($key, '_pa_')) {
                $sKey = explode('attribute_', $key)[1];
            }
            $aData['name'] = $this->getAttributeGroupName($sKey);
            if (in_array('value', $aFields, true)) {
                $aData['value'] = $this->getAttributeValueName($value);
            }
            if (in_array('code', $aFields, true)) {//an identifier for group of id_attribute_group , that used in eBay, Etsy and Hood at the moment
                $aData['code'] = 'pa_'.$sKey;
            }
            if (in_array('valueid', $aFields, true)) {//an identifier for group of id_attribute , that used in eBay, Etsy and Hood at the moment
                $aData['valueid'] = $this->getAttributeValueId($value);
            }

            $aOut[] = $aData;
        }

        return $aOut;
    }

    /**
     * Get attribute id
     *
     * @param $attributeName
     * @return integer
     */
    public function getAttributeValueId($attributeName) {
        global $wpdb;
        $configuratorOptions = $wpdb->get_results(
            $wpdb->prepare("SELECT term_id AS id, name FROM $wpdb->terms WHERE `slug` = %s", $attributeName), ARRAY_A);
        return $configuratorOptions[0]['id'];
    }

    public function getAttributeValueName($attributeName) {
        global $wpdb;
        $configuratorOptions = $wpdb->get_results(
            $wpdb->prepare("SELECT * FROM `$wpdb->terms` WHERE `slug` = %s", $attributeName), ARRAY_A);


        return isset($configuratorOptions[0]['name']) ? $configuratorOptions[0]['name'] : null;
    }


    public function getAttributeCodeId($attributeName) {
        global $wpdb;
        $configuratorOptions = $wpdb->get_results(
            $wpdb->prepare("SELECT attribute_id AS id FROM `".$wpdb->prefix."woocommerce_attribute_taxonomies` WHERE `attribute_name` = %s", $attributeName), ARRAY_A);

        return $configuratorOptions[0]['id'];
    }


    public function getAttributeGroupName($attributeName) {
        global $wpdb;
        $configuratorOptions = $wpdb->get_results(
            $wpdb->prepare("SELECT `attribute_label` FROM `".$wpdb->prefix."woocommerce_attribute_taxonomies` WHERE `attribute_name` = %s", $attributeName), ARRAY_A);
        return isset($configuratorOptions[0]['attribute_label']) ? $configuratorOptions[0]['attribute_label'] : $attributeName;
    }


    /**
     * Gets the item status
     * @return bool
     *    true: product is active
     *    false: product is inactive and can't be bought.
     */
    public function isActive() {
        $product = wc_get_product($this->get('productsid'));
        $blIsVariantActive = $product->get_status() == 'publish' ? true : false;

        return $blIsVariantActive;
    }

    /**
     * Changes the quantity of the item.
     * @param int $iStock new stock
     *
     * @return bool
     */
    public function setStock($iStock) {
        try {
            $oDetail = $this->getArticleDetail();
            $oDetail->set_stock_quantity($iStock);
            $oDetail->save();

            return true;
        } catch (Exception $oExc) {
            MLMessage::gi()->addDebug($oExc);

            return false;
        }
    }

    /**
     * gets ids of root categories
     * @return array
     */
    protected function getRootCategoriesIds() {
        global $wpdb;
        $aResult = $wpdb->get_results("
            SELECT t.term_id AS id 
            FROM {$wpdb->terms} AS t
            INNER JOIN {$wpdb->term_taxonomy} AS tt 
            ON t.term_id = tt.term_id
            WHERE tt.taxonomy = 'product_cat'
            AND tt.parent = 0", ARRAY_A);

        $invalidIds = array();
        foreach ($aResult as $id) {
            $invalidIds[] = $id['id'];
        }

        return $invalidIds;
    }

    static $aWooCommerceProductCategories = array();

    /**
     * get product leaf category
     * @return array
     */
    protected function getCategories() {
        $sKey = MLDatabase::factory('config')->set('mpid', 0)->set('mkey',
            'general.keytype')->get('value') == 'pID' ? 'productsid' : 'productssku';
        $sField = $this->get($sKey);
        if (!array_key_exists($sField, self::$aWooCommerceProductCategories)) {
            self::$aWooCommerceProductCategories[$sField] = array();
            $categoryIds = $this->getLoadedProduct()->get_category_ids();
            $leafCategoryId = end($categoryIds);
            $oCategory = $this->getWCCategoryById($leafCategoryId);
            self::$aWooCommerceProductCategories[$sField][] = $oCategory;
        }

        return self::$aWooCommerceProductCategories[$this->get($sKey)];
    }

    /**
     * makes drop down list with product categories
     * @return string
     */
    public function getCategoryPath() {
        $sCatPath = '';
        $oCat = $this->getCategories()[0];
        $sInnerCat = '';
        $counter = 0;
        while ($oCat) {
            $hasNext = $counter ? '&nbsp;&gt;&nbsp;' : '&nbsp;';
            $sInnerCat = $oCat['name'].$hasNext.$sInnerCat;
            $oCat = $this->getWCCategoryById($oCat['parent']);
            $counter++;
        }
        $sCatPath .= $sInnerCat.'<br>';

        return $sCatPath;
    }

    /**
     * @param bool $blIncludeRootCats
     *
     * @return array
     */
    public function getCategoryIds($blIncludeRootCats = true) {
        $aCategories = array();
        $aFilterCats = $blIncludeRootCats ? array() : $this->getRootCategoriesIds();
        foreach ($this->getCategories() as $oCat) {
            if (!isset($oCat['term_id'])) {

                return $aCategories;
            }
            if (!in_array($oCat['term_id'], $aFilterCats)) {
                $aCategories[] = $oCat['term_id'];
            }
        }

        return $aCategories;
    }

    /**
     * @param bool $blIncludeRootCats
     *
     * @return array
     */
    public function getCategoryStructure($blIncludeRootCats = true) {
        $aCategories = array();
        $aInvalidIds = $aExistedCatId = $blIncludeRootCats ? array() : $this->getRootCategoriesIds();
        foreach ($this->getCategories() as $oCat) {
            do {
                if (!isset($oCat['term_id'])) {
                    break;
                }

                if (
                    in_array($oCat['term_id'], $aInvalidIds)
                    ||
                    in_array($oCat['term_id'], $aExistedCatId)
                ) {
                    break;
                }
                $sDescription = ($oCat['description'] === null) ? '' : $oCat['description'];
                $aCurrentCat = array(
                    'ID'          => $oCat['term_id'],
                    'Name'        => $oCat['name'],
                    'Description' => $sDescription,
                    'Status'      => true,
                );
                $aExistedCatId[] = $oCat['term_id'];
                $oCat = $this->getWCCategoryById($oCat['parent']);
                if ($oCat && !in_array($oCat['term_id'], $aInvalidIds)) {
                    $aCurrentCat['ParentID'] = $oCat['term_id'];
                }
                $aCategories[] = $aCurrentCat;
            } while ($oCat);
        }

        return $aCategories;
    }

    /**
     * Get value for input parameter $sName
     *
     * @param $sName
     * @param null $sMethod
     *
     * @return int|string
     * @throws Exception
     */
    public function getProductField($sName, $sMethod = null) {
        if (strpos($sName, 'pp_') === 0) {
            $mValue = explode(', ', $this->oProduct->get_attribute(str_replace('pp_', '', $sName)));
            $mValue = $mValue[0];
        } elseif (strpos($sName, 'pa_') === 0) {
            $aVariationAttributes = $this->wcObjProduct()->get_attributes();
            if(isset($aVariationAttributes[$sName]) ) {
                $mValue = explode(', ', $this->wcObjProduct()->get_attribute($sName));
            } else {
                $mValue = explode(', ', $this->oProduct->get_attribute($sName));
            }
            $mValue = $mValue[0];
        } elseif (strpos($sName, 'cf_') === 0) {
            $aCustomField = get_post_meta($this->getId(), str_replace('cf_', '', $sName));
            $mValue = empty($aCustomField) ? '' : $aCustomField[0];
        } elseif (strpos($sName, '_') === 0) {
            $aCustomField = get_post_meta((int)$this->get('ProductsId'), $sName);
            $mValue = empty($aCustomField) ? '' : $aCustomField[0];
        } elseif (strpos($sName, 'hwp') === 0) {
            if ($this->get('ParentId') === '0') {
                $aCustomField = get_post_meta((int)$this->get('ProductsId'), $sName);
            } else {
                $aCustomField = get_post_meta((int)$this->get('ProductsId'), 'hwp_var_gtin');
            }
            $mValue = empty($aCustomField) ? '' : $aCustomField[0];
        } elseif (strpos($sName, 'te_') === 0) {
            $aTermObjects = get_the_terms((int)$this->get('ProductsId'), str_replace('te_', '', $sName));
            $aTerms = array();
            if ($aTermObjects) {
                /** @var WP_Term $aTermObjects */
                foreach ($aTermObjects as $oTermObject) {
                    $aTerms[] = $oTermObject->name;
                }
            }
            $mValue = implode(',', $aTerms);
        } else {
            if (method_exists($this->getArticleDetail(), 'get_'.$sName)) {
                $mValue = $this->getArticleDetail()->{'get_'.$sName}();
            } elseif (method_exists($this->oProduct, 'get_'.$sName)) {
                $mValue = $this->oProduct->{'get_'.$sName}();
            } else {
                MLMessage::gi()->addDebug('method get_'.$sName.' does not exist in Article or ArticleDetails');

                return '';
            }
        }

        return $mValue;
    }

    /**
     * @return array empty array or array( "Unit"=><Unit of weight>, "Value"=> <amount of weight>)
     */
    public function getWeight() {
        $sUnit = get_option('woocommerce_weight_unit');
        $sValue = $this->getLoadedProduct()->get_weight();

        return array(
            'Unit'  => $sUnit,
            'Value' => $sValue,
        );
    }

    /**
     * WooCommerce doesn't support this
     *
     * gets formatted baseprice string
     * @param float $fPrice price to format
     * @param bool $blLong use lang or short unit-names eg. kg <=> kilogram
     * @return string
     */
    public function getBasePriceString($fPrice, $blLong = true) {
        return 'Not supported';
    }

    /**
     * Gets the base price of the current item.
     * @return array
     *     array('Unit'=>(string),'Value'=>(float))
     */
    public function getBasePrice() {
        return array();
    }

    /**
     * return html list, that contain property name and values
     * @return string
     */
    protected function getProperties() {
        try {
            $sPropertiesHtml = '';
            $aProperties = MLHelper::gi('model_product')->getProperties($this->getLoadedProduct()->get_id());
            if ($aProperties) {
                $sRowClass = 'odd';
                $sPropertiesHtml .= '<ul class="magna_properties_list">';
                foreach ($aProperties as $sName => $sValues) {
                    $sPropertiesHtml .= '<li class="magna_property_item '.$sRowClass.'">'
                        .'<span class="magna_property_name">'.$sName
                        .'</span>'
                        .'<span  class="magna_property_value">'.implode(', ', $sValues)
                        .'</span>'
                        .'</li>';
                    $sRowClass = $sRowClass === 'odd' ? 'even' : 'odd';
                }
                $sPropertiesHtml .= '</ul>';
            }

            return $sPropertiesHtml;
        } catch (Exception $oEx) {
            return '';
        }
    }

    /**
     * output format:
     * {
     *   #TITLE#: "Woo Album #2",
     *   #ARTNR#: "SPX087",
     *   #PID#: "87",
     *   #SHORTDESCRIPTION#: "Lorem ipsum.",
     *   #DESCRIPTION#: "Lorem ipsum.",
     *   #PROPERTIES#: ""
     * }
     * @return array
     */
    public function getReplaceProperty() {
        $aReplace = parent::getReplaceProperty();
        $aReplace['#PROPERTIES#'] = $this->getProperties();

        return $aReplace;
    }

    protected $iVariantCount = null;

    /**
     * Gets number of variations of article
     * @return int
     */
    public function getRealVariantCount() {
        $productId = $this->oProduct->get_id();
        /** @var ML_WooCommerce_Helper_Model_Product $productModel */
        $productModel = MLHelper::gi('model_product');
        $product = $productModel->getProductDetails($productId);

        if (is_array($product)) {
            return count($product);
        }

        return 0;
    }

    /**
     *Gets number of variations of product
     * @return int
     */
    public function getVariantCount() {
        if ($this->iVariantCount === null) {
            $this->load();
            $iVariantCount = $this->getRealVariantCount();
            $this->iVariantCount = ($iVariantCount == 0) ? 0 : $iVariantCount;
        }
        return $this->iVariantCount;
    }

    /**
     * @var WC_Product $oRepository
     */
    protected $oRepository = null;

    /**
     * Gets product by id (custom written)
     *
     * @param $id
     *
     * @return WC_Product
     */
    private function getProductById($id) {
        if ($this->oRepository === null) {
            $this->oRepository = new WC_Product($id);
        }

        return $this->oRepository;
    }

    /**
     * Returns the manufacturer
     * @return string
     */
    public function getManufacturer() {
        return $this->getModulField('manufacturer');
    }

    /**
     * Returns the manufacturer part number
     * @return string
     */
    public function getManufacturerPartNumber() {
        return $this->getModulField('manufacturerpartnumber');
    }

    /**
     * Returns the EAN
     * @return string
     */
    public function getEAN() {
        return $this->getModulField('ean');
    }

    /**
     * @return WC_Product|WC_Product_Variation
     */
    public function wcObjProduct() {
        if ($this->isProductVariation($this->get('productsid'))) {
            /** @var WC_Product_Variation $oProduct */
            $oProduct = new WC_Product_Variation($this->get('productsid'));
        } else {
            /** @var WC_Product $product */
            $oProduct = new WC_Product($this->get('productsid'));
        }

        return $oProduct;
    }

    /**
     * @param $id
     *
     * @return bool
     */
    public function isProductVariation($id) {
        if (get_post_type($id) == 'product_variation') {

            return true;
        } else {

            return false;
        }
    }

    /**
     * get category by id
     * {
     * term_id: "22",
     * name: "Singles",
     * slug: "singles",
     * term_group: "0",
     * term_taxonomy_id: "22",
     * taxonomy: "product_cat",
     * description: "",
     * parent: "20",
     * count: "2"
     * }
     *
     * @param int $termId
     *
     * @return array
     */
    private function getWCCategoryById($termId) {
        global $wpdb;
        $category = $wpdb->get_row("
            SELECT * FROM {$wpdb->terms} AS t
            INNER JOIN {$wpdb->term_taxonomy} AS tt 
            ON t.term_id = tt.term_id
            WHERE tt.term_id = $termId 
            AND tt.taxonomy = 'product_cat';
        ", ARRAY_A);

        if (empty($category)) {
            $category = array();
        }

        return $category;
    }

    /**
     * if product single product, and it doesn't have any variant
     * @return boolean
     */
    public function isSingle() {
        $isSingle = ($this->getRealVariantCount() == 0) ? true : false;

        return $isSingle;
    }

    private function idVariation() {
        global $woocommerce;
        if (version_compare($woocommerce->version, '3.0.6', ">")) {

            return 'variation_id';
        } else {

            return 'id';
        }
    }

    /**
     * WooCommerce does not support multilingual
     * title and description of the product natively.
     *
     * @param $iLang
     *
     * @return $this|ML_Shop_Model_Product_Abstract
     */
    public function setLang($iLang) {
        return $this;
    }


    /**
     * Gets attribute name
     *
     * @param $sAttribute
     *
     * @return string|null
     */
    public function getAttributeValue($sAttribute) {
        return $this->getProductField($sAttribute);
    }

    public function getPrefixedVariationData() {
        $variationData = $this->getVariatonDataOptinalField(array('name', 'value', 'code', 'valueid'));

        foreach ($variationData as &$variation) {
            $variation['code'] = 'pa_'.$variation['code'];
        }

        return $variationData;
    }

    public function getWidth() {
        $sUnit = get_option('woocommerce_dimension_unit');
        $sValue = $this->getLoadedProduct()->get_width();

        return array(
            'Unit'  => $sUnit,
            'Value' => $sValue,
        );
    }

    public function getLength() {
        $sUnit = get_option('woocommerce_dimension_unit');
        $sValue = $this->getLoadedProduct()->get_length();

        return array(
            'Unit'  => $sUnit,
            'Value' => $sValue,
        );
    }

    public function getHeight() {
        $sUnit = get_option('woocommerce_dimension_unit');
        $sValue = $this->getLoadedProduct()->get_height();

        return array(
            'Unit'  => $sUnit,
            'Value' => $sValue,
        );
    }

    //    public function update() {
    //        unset($this->aData['id']);
    //        parent::update();
    //    }

    public function getBulletPointDefaultField() {
        return 'te_product_tag';
    }

    public function getEanDefaultField()
    {
        $globalEan = MLModul::gi()->getConfig('ean');
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

    public function getManufacturerDefaultField()
    {
        $globalManufacturer = MLModul::gi()->getConfig('manufacturer');
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

    public function getManufacturerPartNumberDefaultField()
    {
        $globalManPartNumber = MLModul::gi()->getConfig('manufacturerpartnumber');
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

    public function getBrandDefaultField()
    {
        $globalManufacturer = MLModul::gi()->getConfig('manufacturer');
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
