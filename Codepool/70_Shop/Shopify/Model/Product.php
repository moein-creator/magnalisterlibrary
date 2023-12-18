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

use Shopify\API\Application\Application;

/**
 * Class ML_Shopify_Model_Product
 */
class ML_Shopify_Model_Product extends ML_Shop_Model_Product_Abstract {

    /**
     * @var array Shopify Product
     */
    protected $aProduct = null;

    /**
     * if get Variant is loaded it contain variant data of product
     * @var array
     */
    protected $aVariant;

    /**
     * image-cache
     * @var null default
     * @var array loaded images for not calculating multiple times
     */
    protected $aImages = null;

    /**
     * @param int $iProductId
     * @return ML_Shopify_Model_Product
     */
    public function getProductByShopId($iProductId){
        $this->aKeys = array('marketplaceidentid');
        return $this->set('MarketplaceIdentId', $iProductId);
    }
    /**
     * Returns loaded object
     *
     * @return object Shopify Product
     */
    public function getLoadedProduct() {
        if ($this->oProduct === null) {
            $this->load();
        }
        return $this->oProduct;
    }



    /**
     * Gets the price of current shop without special offers.
     *
     * @todo Investigate and implement function.
     *
     * @param bool $blGros
     * @param bool $blFormated
     *
     * @return mixed
     *     A string if $blFormated == true
     *     A float if $blFormated == false
     * @throws Exception
     */
    public function getShopPrice($blGros = true, $blFormated = false) {
        $this->load();
        $mReturn = $this->getPrice($blGros, $blFormated/* ,false */);

        if ($blFormated == false) {
            if (property_exists($this->oProduct, 'currency')) {
                $currencyWithPosition = MLShopifyAlias::getPriceHelper()->getCurrencyAndCurrencyPosition();
                if ($currencyWithPosition['position'] === 'right') {
                    return $mReturn.$currencyWithPosition['currency'];
                } else {
                    return $currencyWithPosition['currency'].$mReturn;
                }
            }
            return $mReturn;

        } else {

            return $mReturn;
        }
    }

    function getReplaceProperty() {
        return parent::getReplaceProperty() + ['#TAGS#' => $this->getMetaKeywords()];
    }

    /**
     * @param $blGros
     * @param $blFormated
     * @param string $sPriceKind
     * @param float $fPriceFactor
     * @param null $iPriceSignal
     * @param null $fTax
     * @return mixed
     * @throws Exception
     */
    protected function getPrice($blGros, $blFormated, $sPriceKind = '', $fPriceFactor = 0.0, $iPriceSignal = null, $fTax = null) {
        if ($fTax !== null) {
            $fPercent = $fTax;
        } else {
            $fPercent = $this->getTax();
        }
        $fBrutPrice = $this->getCurrentVariant()['price'];
        return $this->configurePrice($fBrutPrice, $fPercent, $blGros, $blFormated, $sPriceKind , $fPriceFactor , $iPriceSignal );

    }


    /**
     * @return array|null
     */
    protected function getCurrentVariant() {
        if ($this->oProduct === null) {
            $this->load();
        }
        if ($this->aVariant === null) {
            $sVariationId = (float)$this->get('productsid');
            foreach ($this->oProduct->variants as $aVariant) {
                if ((float)$aVariant['id'] === $sVariationId) {
                    $this->aVariant = $aVariant;
                    break;
                }
            }
            if($this->aVariant === null){
                $this->aVariant = current($this->oProduct->variants);
            }
        }
        return $this->aVariant;
    }

   /**
     * public alias for getCurrentVariant()
     * for the getRealProduct call from Shopify/Helper/Model/ShopOrder.php
     * @return array|null
     */
   public function getRealProduct() {
      return $this->getCurrentVariant();
   }


    /**
     * Gets the price depending on the marketplace config.
     *
     * @todo Investigate and implement function.
     *
     * @param \ML_Shop_Model_Price_Interface $oPrice
     * @param bool $blGros
     * @param bool $blFormated
     *
     * @return mixed
     *     A string if $blFormated == true
     *     A float if $blFormated == false
     * @throws Exception
     */
    public function getSuggestedMarketplacePrice(ML_Shop_Model_Price_Interface $oPrice, $blGros = true, $blFormated = false) {
        $this->load();
        $aConf = $oPrice->getPriceConfig();
        $fTax = $aConf['tax'];
        $sPriceKind = $aConf['kind'];
        $fPriceFactor = (float)$aConf['factor'];
        $iPriceSignal = $aConf['signal'];
        $mReturn = $this->getPrice($blGros, $blFormated, $sPriceKind, $fPriceFactor, $iPriceSignal, $fTax);

        return $mReturn;
    }

    /**
     * Get an element of this item. Used for matchings defined in modulconfig.
     *
     * @todo Investigate and implement function.
     *
     * @param $sFieldName
     * @param bool $blGeneral
     *
     * @return mixed value of product-attribute or null if the value does not exist.
     */
    public function getModulField($sFieldName, $blGeneral = false) {
        if ($sFieldName === 'general.ean') {
            return $this->getEAN();
            //return isset($this->getVariants()[0]->oProduct->variants[0]->barcode) ? $this->getVariants()[0]->oProduct->variants[0]->barcode : '';
        }

        if ($sFieldName === 'manufacturer' || $sFieldName === 'general.manufacturer') {
            return isset($this->getVariants()[0]->oProduct->vendor) ? $this->getVariants()[0]->oProduct->vendor : '';
        }

        if (!isset($this->getVariants()[0]->oProduct->variants[0]->$sFieldName)) {
            return '';
        }

        return $this->getVariants()[0]->oProduct->variants[0]->$sFieldName;
    }

    /**
     * Returns title of the product.
     *
     * @todo Investigate and implement function.
     *
     * @return string
     */
    public function getName() {
        $this->load();
        $name = $this->oProduct->title;
        if ((int)$this->get('parentid') !== 0 && $this->getVariantCount() > 1) {
            $name .= ': '.$this->getCurrentVariant()['title'];
        }

        return $name;
    }

    /**
     * Returns the url of the main item image in the requested resolution.
     * If the url does not yet exist an image will be generated.
     *
     * @todo Investigate and implement function.
     *
     * @param int $iX
     * @param int $iY
     *
     * @return array|string
     */
    public function getImageUrl($iX = 40, $iY = 40) {
        $this->load();
        $oProduct = $this->oProduct;
        $sImagePath = '';

        try {
            $sCurrentProductVariantId = (float)$this->aOrginData['productsid'];
            $productVariants = $oProduct->variants;
            $aProductImages = $this->oProduct->images;

            if (!strpos($this->aOrginData['marketplaceidentid'], '_')) {
                    $sImagePath = $this->oProduct->image['src'];
            } else {
                foreach ($aProductImages as $aProductImage) {
                    $aVariantIds = $aProductImage['variant_ids'];
                    foreach ($aVariantIds as $aVariantId) {
                        if ((float)$aVariantId === $sCurrentProductVariantId) {
                            $sImagePath = $aProductImage['src'];
                            return MLImage::gi()->resizeImage($sImagePath, 'products', $iX, $iY, true);
                        }
                    }
                }
            }

            if (empty($sImagePath) && !empty($this->oProduct->image)) {
                $sImagePath = $this->oProduct->image['src'];
            }

            return MLImage::gi()->resizeImage($sImagePath, 'products', $iX, $iY, true);

        } catch (Exception $oEx) {
            return '';
        }
    }

    /**
     * Returns the link to edit the product in the shop.
     *
     * @todo Investigate and implement function.
     *
     * @return string
     */
    public function getEditLink() {
        $port = 'https://';
        $hostname = MLHelper::gi('model_shop')->getShopId();
        $path = '/admin/products/';
        $this->load();
        $id = $this->oProduct->id;

        if ((int)$this->get('parentid') !== 0) {
            $id .= '/variants/'.$this->getCurrentVariant()['id'];
        }

        return $port.$hostname.$path.$id;
    }

    /**
     * Returns the link to frontend of the product in the shop
     *
     * @return string
     */
    public function getFrontendLink() {
        $sShopId = MLHelper::gi('model_shop')->getShopId();
        $application = new Application($sShopId);
        $sToken = MLHelper::gi('container')->getCustomerModel()->getAccessToken($sShopId);

        $oProductParams = new \Shopify\API\Application\Request\Products\ProductGraphQL\ProductGraphQLParams();
        $oProductParams->setId($this->oProduct->admin_graphql_api_id);

        $aProduct = $application->getProductRequest($sToken)->productGraphQL($oProductParams)->getBodyAsArray()['data']['product'];

        return $aProduct['onlineStoreUrl'];
    }

    /**
     * Returns product Sku
     *
     * @param $oProduct
     *
     * @return string
     */
    public function getShopifySku($oProduct) {
        //@todo gets first sku
        if (is_array($oProduct->variants[0])) {
            $sku = $oProduct->variants[0]['sku'];
        } else {
            $sku = $oProduct->variants[0]->sku;
        }
        if ($sku == '') {
            //@todo
        }

        return $sku;
    }

    /**
     * load data with shop-product-info (main-product)
     * if not exist, create entree in db
     * also creates variants of main-product
     *
     * implementation of this method in Shopify is different from other shop-system,
     * it isn't used during working with product,
     * it is used only by importing products( through Cron, Update and Hook) because of that we used only id as key, not sku,
     * otherwise if the sku of a product changes, it will create a new product row in magnalister_products table
     *
     * @todo implement location
     *
     * @param array $aProduct depends by shop
     * @param integer $iParentId $this->get("parentid");
     * @param mixed $mData Shopspecific
     *
     * @return $this
     * @throws Exception
     */
    public function loadByShopProduct($aProduct, $iParentId = 0, $mData = null) {
        $this->aProduct = $aProduct;
        $this->oProduct = (object)$aProduct;
        $sKey = MLDatabase::factory('config')->set('mpid', 0)->set('mkey', 'general.keytype')->get('value');

        $this->aKeys = array('marketplaceidentid');
        $this->set('parentid', $iParentId)->aKeys[] = 'parentid';

        //to implement location in shopify we need this line in future
        //$this->set('ShopifyLocation', $oProduct->location)->aKeys[] = 'ShopifyLocation';

        if ((int)$iParentId === 0) {

            $sSku = current($this->oProduct->variants)['sku'];
            $sPrice = current($this->oProduct->variants)['price'];
            $iQuantity = 0;
            foreach ($this->oProduct->variants as $aVariant) {
                $iQuantity += $aVariant['inventory_quantity'];
            }

            $this
                ->set('marketplaceidentid', $this->oProduct->id)
                ->set('marketplaceidentsku', empty($sSku) ? '__ERROR__'.$this->oProduct->id : $sSku)
                ->set('productsid', $this->oProduct->id)
                ->set('productssku', empty($sSku) ? '__ERROR__'.$this->oProduct->id : $sSku)
                ->set('shopdata', array())
                ->set('data', array())
                ->set('ShopifyTitle', $this->oProduct->title)
                ->set('ShopifyQuantity', $iQuantity)
                ->set('ShopifyPrice', $sPrice)
                ->set('ShopifyVendor', $this->oProduct->vendor)
                ->set('ShopifyPublication', $this->oProduct->published_at)
                ->set('ShopifyStatus', $this->oProduct->status)
                ->set('ShopifyData', $aProduct)
                ->set('ShopifyMethodOfUpdate', $this->oProduct->MethodOfUpdate)
                ->set('ShopifyUpdateDate', date('Y-m-d H:i:s'))
                ->save()
                ->aKeys = array('id');

            $this->refreshAttributeTable();

            if ($sKey !== 'pID' && empty($sSku)) {
                MLMessage::gi()->addObjectMessage(
                    $this, MLI18n::gi()->data('Productlist_Cell_Product_NoSku')
                );
            }
            //            $this->prepareProductForMarketPlace();
        } else {
            if (!isset($mData['variation_id'])) {
                throw new Exception('not key set for product variation');
            }
            $aVariation = $this->getProductVariant($mData['variation_id']);
            $sVariantSku = $aVariation['sku'];
            $this
                ->set('marketplaceidentid', $aVariation['id'].'_'.$this->oProduct->id)
                ->set('marketplaceidentsku', empty($sVariantSku) ? '__ERROR__'.$this->oProduct->id : $sVariantSku)
                ->set('productsid', $aVariation['id'])
                ->set('productssku', empty($sVariantSku) ? '__ERROR__'.$this->oProduct->id : $sVariantSku)
                ->set('shopdata', $mData)
                ->set('data', array());
            if ($this->exists()) {
                $this->aKeys = array('id');
                $this->save();
            } else {
                $this->save()->aKeys = array('id');
            }
        }

        return $this;
    }

    /**
     * to update attributes of shopify after each change in product we check options of product if they are changed
     * @throws Exception
     */
    protected function refreshAttributeTable() {
        foreach ($this->aProduct['options'] as $attribute) {
            if ($attribute['values'][0] !== 'Default Title') {
                MLDatabase::factory('shopifyattribute')->set('AttributeName', $attribute['name'])
                    ->set('AttributeValues', $attribute['values'])
                    ->set('ProductID', $attribute['product_id'])
                    ->save();
            }
        }
    }

    static protected  $aAnyEmptySkuVariant = array();
    protected function findEmptySku($iProductId){

        if(!isset(self::$aAnyEmptySkuVariant[$iProductId])) {
            self::$aAnyEmptySkuVariant[$iProductId] = false;
            foreach ($this->oProduct->variants as $variant) {
                if (empty($variant['sku'])) {
                    self::$aAnyEmptySkuVariant[$iProductId] = true;
                }
            }
        }
        return self::$aAnyEmptySkuVariant[$iProductId];
    }


    /**
     * Loads shop-specific product(-data).
     * Uses self::getMarketPlaceSku() to identify the product.
     *
     * Should check, if shop-specific product(-data) already is loaded.
     *
     * @return $this
     *
     * @throws Exception product doesn't exist in shop
     */
    protected function loadShopProduct() {
        if ($this->oProduct === null) {//product is not loaded
            $this->oProduct = false; //not null
            if ((int)$this->get('parentid') === 0) {
                $oProduct = $this;
            } else {
                $oProduct = $this->getParent();
            }
            $this->aProduct = $oProduct->get('shopifydata');
            $this->oProduct = (object)$this->aProduct;
        }
        if ($this->aProduct !== null) {
            $sKey = MLDatabase::factory('config')->set('mpid', 0)->set('mkey', 'general.keytype')->get('value');
            if ($sKey !== 'pID' && $this->findEmptySku($this->aProduct['id'])) {
                MLMessage::gi()->addObjectMessage(
                    $this, MLI18n::gi()->data('Productlist_Cell_Product_NoSku')
                );
            }
        }
        return $this;
    }

    /**
     * Loads all shop-variants of this product.
     *
     * @return $this
     * @throws MLAbstract_Exception
     */
    protected function loadShopVariants() {
        $iVariationCount = $this->getVariantCount();

        if ($iVariationCount > MLSetting::gi()->get('iMaxVariantCount')) {
            $this
                ->set('data', array('messages' => array(MLI18n::gi()->get('Productlist_ProductMessage_sErrorToManyVariants', array('variantCount' => $iVariationCount, 'maxVariantCount' => MLSetting::gi()->get('iMaxVariantCount'))))))
                ->save();
            MLMessage::gi()->addObjectMessage($this, MLI18n::gi()->get('Productlist_ProductMessage_sErrorToManyVariants', array('variantCount' => $iVariationCount, 'maxVariantCount' => MLSetting::gi()->get('iMaxVariantCount'))));
        } else {
            $productId = $this->getLoadedProduct()->id;
            $options = $this->oProduct->options;
            $aDetails = $this->oProduct->variants;
            foreach ($aDetails as $aRow) {
                $aVariant = array();
                if (count($aDetails) === 1) {//add one variation for single product
                    $aVariant['variation_id'] = $aRow['id'];
                    $aVariant['info'][] = array();
                } else {//add variation which have price and configuration option
                    $iOptionIndex = 1;
                    foreach ($options as $aOption) {
                        $aVariant['variation_id'] = $aRow['id'];
                        $aVariant['info'][] = array('name' => $aOption['name'], 'value' => $aRow['option'.$iOptionIndex]);
                        $iOptionIndex ++;
                    }
                }
                if (isset($aVariant['variation_id'])) {
                    $this->addVariant(
                        MLShopifyAlias::getProductModel()->loadByShopProduct($this->aProduct, $this->get('id'), $aVariant)
                    );
                }

            }
        }

        return $this;
    }
    /**
     *
     * @todo Investigate and implement function.
     *
     */
    protected function prepareProductForMarketPlace() {
        $aConf = MLModule::gi()->getConfig();
    }

    /**
     * Gets all images for the current item.
     * If the object is master product, returns all product images,
     * if the object is variant, move the variant image to the beginning of the list (As standard behaviour of Shopify)
     *
     * @return array
     *    /file/path/to/image/
     */
    public function getImages() {
        if ($this->aImages === null) {
            $this->load();
            $aImages = array();
            $aProductImages = $this->oProduct->images;

            foreach ($aProductImages as $aProductImage) {
                $aImages[$this->getShopifyEntityField($aProductImage, 'id')] = $this->getShopifyEntityField($aProductImage, 'src');
            }
            if (
                (int)$this->get('parentid') !== 0 && $this->getVariantCount() > 1
                && isset($this->getCurrentVariant()['image_id']) && isset($aImages[$this->getCurrentVariant()['image_id']])
            ) {
                $sImageTemp = $aImages[$this->getCurrentVariant()['image_id']];
                unset($aImages[$this->getCurrentVariant()['image_id']]);
                array_splice($aImages, 0, 0, array($this->getCurrentVariant()['image_id'] => $sImageTemp));
            }
            $this->aImages = $aImages;
        }

        return $this->aImages;
    }

    /**
     * From first implementation of shopify app for magnalister,
     * product data is converted sometime to an array and sometimes to an object
     * this function will help us to handle both of them.
     * @param $mEntity
     * @param $sFieldName
     * @return mixed|null
     */
    protected function getShopifyEntityField($mEntity, $sFieldName) {
        $mValue = null;
        if (is_array($mEntity)) {
            $mValue = $mEntity[$sFieldName];
        }
        if (is_object($mEntity)) {
            $mValue = $mEntity->{$sFieldName};
        }
        return $mValue;
    }

    /**
     * Gets the description of the item.
     *
     * @return string
     */
    public function getDescription() {
        $this->load();

        return $this->oProduct->body_html;
    }

    /**
     * There is no short description in shopify currently, magnalister uses title instead of that
     *
     * @return string
     */
    public function getShortDescription() {
        $this->load();

        return $this->oProduct->title;
    }

    /**
     * @TODO Product meta field should be get with separated request like ../admin/products/{id}/metafields.json
     * https://help.shopify.com/en/api/reference/metafield
     *
     * @return string
     */
    public function getMetaDescription() {
        $this->load();
        return $this->oProduct->tags;
    }

    /**
     * Gets the meta description of the item.
     *
     * @return string
     */
    public function getMetaKeywords() {
        return $this->oProduct->tags;
    }

    /**
     * Gets the quantity of the item.
     *
     * @todo Investigate and implement function.
     *
     * @return int
     */
    public function getStock() {
        $iTotalNumberOfVariants = 0;
        if((int)$this->get('parentid') === 0) {
            $aVariants = $this->oProduct->variants;
            foreach ($aVariants as $aVariant) {
                $iTotalNumberOfVariants += $aVariant['inventory_quantity'];
            }
        } else {
            $iTotalNumberOfVariants = $this->getCurrentVariant()['inventory_quantity'];
        }
        return $iTotalNumberOfVariants;
    }


    /**
     * Changes the quantity of the item.
     *  Not used in shopify because of the "inventory_behaviour" parameter when creating orders with Shopify API
     *      see: magnalister/Codepool/70_Shop/Shopify/Helper/Model/ShopOrder.php - createOrder()
     *
     *  Alternative we can use: https://gitlab.magnalister.com/snippets/3
     *
     * @param int $iStock
     *
     * @return mixed
     */
    public function setStock($iStock) {
        return '';
    }

    /**
     * Get the quantity of the product based on the module configuration.
     * Also cares about options-child-articles.
     * @param string $sType
     * @param float $fValue
     * @param null|int $iMax max quantity set for product upload: price calculation tab, quantity limit field
     *
     * @return int|mixed
     */
    public function getSuggestedMarketplaceStock($sType, $fValue, $iMax = null) {
        if (
            MLModule::gi()->getConfig('inventar.productstatus') === 'true'
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
     * Gets distinct data of variant.
     *
     *
     * eg:
     * array(
     *  array(
     *      'name'=>'color'
     *      'value'=>'red'
     *  ),...
     * );
     * if empty, variant == master
     *
     * @return array
     */
    public function getVariatonData() {
        return $this->getVariatonDataOptinalField(array('name', 'value'));
    }

    public function getVariatonDataOptinalField($aFields = array()) {
        $this->load();
        $oProduct = $this->oProduct;
        $aProductOptions = $oProduct->options;
        $oCurrentProductVariant = null;
        $aOut = array();
        $iVariantCount = $this->getVariantCount();

        //if product has variations
        if ($iVariantCount > 1) {
            //$oCurrentProductVariant = $aProductVariants[1];

            $oCurrentProductVariant = $this->getCurrentVariant();
            foreach ($aProductOptions as $aProductOption) {
                $aOut[] = array(
                    'name' => $aProductOption['name']
                );
            }

            $aPossibleOptionValues = array('option1', 'option2', 'option3');

            $int = 0;
            foreach ($aPossibleOptionValues as $aPossibleOptionValue) {
                $sVariantOptionValue = $oCurrentProductVariant[$aPossibleOptionValue];
                if ($sVariantOptionValue != '' && $sVariantOptionValue == $oCurrentProductVariant[$aPossibleOptionValue]) {
                    $aOut[$int]['value'] = $sVariantOptionValue;

                    if (in_array('code', $aFields)) {//an identifier for group of id_attribute_group , that used in Meinpaket at the moment
                        $aOut[$int]['code'] = $aOut[$int]['name'];
                    }
                    if (in_array('valueid', $aFields)) {//an identifier for group of id_attribute , that used in Meinpaket at the moment
                        $aOut[$int]['valueid'] = $aOut[$int]['value'];
                    }

                    $int++;
                }
            }
        }

        return $aOut;
    }


    /**
     * Returns tax-value for product. If $aAdressData is set, it try to locate tax for address.
     *
     * @todo get state tax, currently returns country tax
     *
     * @param array $aAddressData
     *
     * @return float
     */
    public function getTax($aAddressSets = null) {
        $fTax = 0.0;
        $sCountryCode = MLShopifyAlias::getShopHelper()->getDefaultCountry();
        if ($aAddressSets !== null) {
            $sCountryCode = $aAddressSets['Shipping']['CountryCode'];
        }
        if ($sCountryCode !== null) {
            $fTaxDecimal = 0.0;
            $aCountries = $this->getListOfCountriesFromShopifyAsArray();
            //@todo get state tax, currently returns country tax
            foreach ($aCountries as $aCountry) {
                if ($aCountry['code'] == $sCountryCode) {
                    $fTaxDecimal = $aCountry['tax'];
                    break;
                }
            }
            $fTax = $fTaxDecimal * 100;
        }
        return $fTax;
    }

    /**
     * Returns list of countries from Shopify
     *
     * @return array
     */
    protected function getListOfCountriesFromShopifyAsArray() {
        $sShopId = MLHelper::gi('model_shop')->getShopId();
        $application = new Application($sShopId);
        $sToken = MLHelper::gi('container')->getCustomerModel()->getAccessToken($sShopId);
        $aCountries = $application->getCountryRequest($sToken)->getListOfCountries()->getBodyAsArray()['countries'];

        return $aCountries;
    }

    /**
     * Gets tax class id for current product.
     *
     * @todo Investigate and implement function.
     *
     * @return int Tax Class Id.
     */
    public function getTaxClassId() {
        return 1;
    }

    /**
     * Returns the item status.
     *
     * @return bool
     *    true: product is active
     *    false: product is inactive and can't be bought.
     */
    public function isActive() {
        return $this->oProduct->status === 'active';
    }

    /**
     * In shopify we don't need to generate product in process of product loading
     * All products are already generated through cron, hook or update process
     *
     * @param string $sSku depend by general.keytype
     * @uses self::loadByShopProduct() as new instance
     *
     * @return mixed
     */
    public function createModelProductByMarketplaceSku($sSku) {
        return '';
    }

    /**
     * Get the category oath of the current item.
     *
     * @return mixed|string
     */
    public function getCategoryPath() {
        if((int)$this->get('parentid') === 0) {
            return $this->get('ShopifyCollectionTitle');
        } else {
            return $this->getParent()->get('ShopifyCollectionTitle');
        }
    }

    /**
     * Gets the base price of the current item.
     *
     * @todo we should use meta-field to create unit price, https://help.shopify.com/en/api/reference/metafield
     *
     * @return array
     *     array('Unit'=>(string),'Value'=>(float))
     */
    public function getBasePrice() {
        return [];
    }

    /**
     * Returns formatted base price string.
     *
     * @todo we should use meta-field to create unit price, https://help.shopify.com/en/api/reference/metafield
     *
     * @param float $fPrice price to format
     * @param bool $blLong use lang or short unit-names eg. kg <=> kilogram
     *
     * @return string
     */
    public function getBasePriceString($fPrice, $blLong = true) {
        return '';
    }

    /**
     * Returns the number of variant items if this item is a master item.
     *
     * @todo Investigate and implement function.
     *
     * @return int|null
     */
    public function getVariantCount() {
        return count($this->oProduct->variants);
    }

    /**
     * Returns the EAN.
     *
     * @todo Investigate and implement function.
     *
     * @return string
     * @throws Exception
     */
    public function getEAN() {
        $this->loadShopProduct();
        return $this->getCurrentVariant()['barcode'];
    }

    /**
     * Returns the manufacturer.
     *
     * @todo Investigate and implement function.
     *
     * @return string
     */
    public function getManufacturer() {
        $this->loadShopProduct();

        return $this->oProduct->vendor;
    }

    /**
     * Returns the manufacturer part number.
     *
     * @todo Investigate and implement function.
     *
     * @return string
     */
    public function getManufacturerPartNumber() {
        return '';
    }

    /**
     * @todo Investigate and implement function.
     * In Shopify we have collection, and collection doesn't have hierarchy, or tree structure
     * @param bool $blIncludeRootCats
     *
     * @return array
     */
    public function getCategoryStructure($blIncludeRootCats = true) {
        if((int)$this->get('parentid') === 0) {
            return $this->get('ShopifyCollectionTitle');
        } else {
            return $this->getParent()->get('ShopifyCollectionTitle');
        }
    }

    /**
     * @todo Investigate and implement function.
     *
     * @param bool $blIncludeRootCats
     *
     * @return array
     */
    public function getCategoryIds($blIncludeRootCats = true) {
        if((int)$this->get('parentid') === 0) {
            return $this->get('ShopifyCollectionId');
        } else {
            return $this->getParent()->get('ShopifyCollectionId');
        }
    }

    /**
     * @todo
     */
    public function getId() {
        $this->load();
        // return product id - check if this is the way
        return $this->oProduct->id;
    }

    /**
     *
     * @return array empty array or array(
     *     "Unit"=><Unit of weight>,
     *     "Value"=> <amount of weight>
     * )
     */
    public function getWeight() {
        return array(
            'Unit' => $this->getCurrentVariant()['weight_unit'],
            'Value' => $this->getCurrentVariant()['weight'],
        );
    }

    /**
     * Get any field that exist in shopify product or variant data
     *
     * @param $sName
     * @param null $sMethod
     * @return string|null
     * @throws Exception
     */
    public function getProductField($sName, $sMethod = null) {
        if (array_key_exists($sName, $this->oProduct->variants[0])) {
            if ($this->isSingle()) {
                $mValue = $this->oProduct->variants[0][$sName];
            } else {
                $mValue = $this->getCurrentVariant()[$sName];
            }
        } else {
            MLMessage::gi()->addDebug('method get'.$sName.' does not exist in Article or ArticleDetails');
            return '';
        }
        return $mValue;
    }

    /**
     * Returns single product by provided product Id
     *
     * @param int $productVariantId
     *
     * @return array
     */
    protected function getProductVariant($productVariantId) {
        foreach ($this->oProduct->variants as $aVariant) {
            if($productVariantId === $aVariant['id']){
                return $aVariant;
            }
        }

        return array();
    }

    /**
     * If product single product, and it doesn't have any variant
     * @return boolean
     */
    public function isSingle() {
        $aVariants = $this->getLoadedProduct()->variants;
        return count($aVariants) <= 1;
    }

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
        $aAttribute = explode('_', $sAttribute, 2);
        $sAttributeName = $aAttribute[1];
        $sValue = null;
        if ($aAttribute[0] === 'c') {
            $aRow = $this->getCurrentVariant();

            $iOptionIndex = 1;
            foreach ($this->oProduct->options as $aOption) {
                if ($sAttributeName === $aOption['name']) {
                    $sValue = $aRow['option'.$iOptionIndex];
                    break;
                }
                $iOptionIndex++;
            }
        } elseif ($aAttribute[0] === 'pd') {
            if ($sAttributeName !== 'vendor') {
                $sValue = $this->getCurrentVariant()[strtolower($sAttributeName)];
            } else {
                $sValue = null;
            }

            // If value is not specified at variation level used it from master product
            if (empty($sValue)) {
                $sValue = $this->oProduct->{strtolower($sAttributeName)};
            }
        } elseif ($aAttribute[0] === 'p') {
            $sValue = $this->oProduct->{strtolower($sAttributeName)};
        } elseif ($aAttribute[1] === 'barcode') {
            $sValue = $this->getCurrentVariant()['barcode'];
            if (empty($sValue)) {
                $sValue = $this->oProduct->{'barcode'};
            }
        } elseif ($aAttribute[1] === 'tags') {
            $sValue = $this->oProduct->{strtolower($sAttributeName)};
        }

        return $sValue;
    }

    public function getPrefixedVariationData() {
        $variationData = $this->getVariatonDataOptinalField(array('name', 'value', 'code', 'valueid'));

        foreach ($variationData as &$variation) {
            $variation['code'] = 'c_'.$variation['code'];
        }

        return $variationData;
    }

    /**
     * @inheritDoc
     */
    public function getSku() {
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
     * {
     *   "id": 1990848774237,
     *   "title": "Soft Winter Jacket",
     *   "body_html": "<p>Thick black winter jacket, with soft fleece lining. Perfect for those cold weather days.</p>",
     *   "vendor": "partners-demo",
     *   "product_type": "",
     *   "created_at": "2019-02-14T21:13:29-12:00",
     *   "handle": "dark-winter-jacket",
     *   "updated_at": "2019-03-21T17:27:35-12:00",
     *   "published_at": "2019-02-14T21:13:29-12:00",
     *   "template_suffix": null,
     *   "tags": "Jacket, women",
     *   "published_scope": "web",
     *   "admin_graphql_api_id": "gid://shopify/Product/1990848774237",
     *   "variants": [
     *       {
     *           "id": 19711243911261,
     *           "product_id": 1990848774237,
     *           "title": "Default Title",
     *           "price": "14.33",
     *           "sku": "erw234wereörwer,werw3e23424234234234234234234234234234234234rwerwerwerwerwerwerwerwerwerwer",
     *           "position": 1,
     *           "inventory_policy": "deny",
     *           "compare_at_price": null,
     *           "fulfillment_service": "manual",
     *           "inventory_management": null,
     *           "option1": "Default Title",
     *           "option2": null,
     *           "option3": null,
     *           "created_at": "2019-02-14T21:13:29-12:00",
     *           "updated_at": "2019-03-21T17:10:06-12:00",
     *           "taxable": true,
     *           "barcode": "",
     *           "grams": 0,
     *           "image_id": null,
     *           "weight": 0,
     *           "weight_unit": "kg",
     *           "inventory_item_id": 20225189937245,
     *           "inventory_quantity": 1,
     *           "old_inventory_quantity": 1,
     *           "requires_shipping": true,
     *           "admin_graphql_api_id": "gid://shopify/ProductVariant/19711243911261"
     *       }
     *   ],
     *   "options": [
     *       {
     *           "id": 2814212112477,
     *           "product_id": 1990848774237,
     *           "name": "Title",
     *           "position": 1,
     *           "values": [
     *           "Default Title"
     *           ]
     *       }
     *   ],
     *   "images": [
     *       {
     *           "id": 7031155327069,
     *           "product_id": 1990848774237,
     *           "position": 1,
     *           "created_at": "2019-02-14T21:13:29-12:00",
     *           "updated_at": "2019-02-14T21:13:29-12:00",
     *           "alt": null,
     *           "width": 925,
     *           "height": 617,
     *           "src": "https://cdn.shopify.com/s/files/1/2618/5740/products/smiling-woman-on-snowy-afternoon_925x_fdb62edf-1079-4ed0-9cc2-5561347d6117.jpg?v=1550222009",
     *           "variant_ids": [],
     *           "admin_graphql_api_id": "gid://shopify/ProductImage/7031155327069"
     *       }
     *   ],
     *   "image": {
     *       "id": 7031155327069,
     *       "product_id": 1990848774237,
     *       "position": 1,
     *       "created_at": "2019-02-14T21:13:29-12:00",
     *       "updated_at": "2019-02-14T21:13:29-12:00",
     *       "alt": null,
     *       "width": 925,
     *       "height": 617,
     *       "src": "https://cdn.shopify.com/s/files/1/2618/5740/products/smiling-woman-on-snowy-afternoon_925x_fdb62edf-1079-4ed0-9cc2-5561347d6117.jpg?v=1550222009",
     *       "variant_ids": [],
     *       "admin_graphql_api_id": "gid://shopify/ProductImage/7031155327069"
     *   }
     * }
     *
     * @var object Shopify Product
     */
    protected $oProduct = null;


    protected $aFields = array(
        'ID'                    => array(
            'isKey' => true,
            'Type'  => 'int(11)', 'Null' => 'NO', 'Default' => NULL, 'Extra' => 'auto_increment', 'Comment' => ''
        ),
        'ParentId'              => array(
            'Type' => 'int(11)', 'Null' => 'NO', 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'ProductsId'            => array(
            'Type' => 'varchar(255)', 'Null' => 'NO', 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'ProductsSku'           => array(
            'Type' => 'varchar(255)', 'Null' => 'NO', 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'MarketplaceIdentId'    => array(
            'Type' => 'varchar(150)', 'Null' => 'NO', 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'MarketplaceIdentSku'   => array(
            'Type' => 'varchar(255)', 'Null' => 'NO', 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'LastUsed'              => array(
            'Type' => 'date', 'Null' => 'NO', 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'Data'                  => array(
            'Type' => 'text', 'Null' => 'NO', 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'ShopData'              => array(
            'Type' => 'text', 'Null' => 'NO', 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'ShopifyTitle'          => array(
            'Type' => 'text', 'Null' => 'NO', 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'ShopifyQuantity'       => array(
            'Type' => 'int(11)', 'Null' => 'NO', 'Default' => NULL, 'Extra' => '', 'Comment' => 'Use separated column to sort by quantity faster.'
        ),
        'ShopifyPrice'          => array(
            'Type' => 'decimal(20,3)', 'Null' => 'NO', 'Default' => NULL, 'Extra' => '', 'Comment' => 'Use separated column to sort by quantity faster.'
        ),
        'ShopifyCollectionId'   => array(
            'Type' => 'varchar(255)', 'Null' => 'NO', 'Default' => NULL, 'Extra' => '', 'Comment' => 'CollectionID:CollectionTitle, It is used separated column to filter by collection faster. The collections are like categories'
        ),
        'ShopifyCollectionTitle' => array(
            'Type' => 'varchar(255)', 'Null' => 'NO', 'Default' => NULL, 'Extra' => '', 'Comment' => 'CollectionID:CollectionTitle, It is used separated column to filter by collection faster. The collections are like categories'
        ),
        'ShopifyVendor'         => array(
            'Type' => 'text', 'Null' => 'NO', 'Default' => NULL, 'Extra' => '', 'Comment' => 'Use separated column to filter by vendor faster. The vendors are like manufactures'
        ),
        'ShopifyPublication'    => array(
            'Type' => 'datetime', 'Null' => 'YES', 'Default' => NULL, 'Extra' => '', 'Comment' => 'Use separated column to filter by availability faster. It is used for filtering availability, it is null if product is not published'
        ),
        'ShopifyStatus'         => array(
            'Type' => 'varchar(50)', 'Null' => 'YES', 'Default' => NULL, 'Extra' => '', 'Comment' => 'Use separated column to filter by status faster. It is used for filtering by status of product, it could be active or draft'
        ),
        'ShopifyData'           => array(
            'Type' => 'longtext', 'Null' => 'NO', 'Default' => NULL, 'Extra' => '', 'Comment' => 'Cache product data in this column'
        ),
        'ShopifyLocation'       => array(
            'Type' => 'bigint(11)', 'Null' => 'NO', 'Default' => 0, 'Extra' => '', 'Comment' => 'It is important in future if we want to support locations'
        ),
        'ShopifyMethodOfUpdate' => array(
            'Type' => "enum('hook', 'cron', 'update', '-')", 'Null' => 'NO', 'Default' => '-', 'Extra' => '', 'Comment' => ''
        ),
        'ShopifyUpdateDate'     => array(
            'Type' => 'datetime', 'Null' => 'NO', 'Default' => NULL, 'Extra' => '', 'Comment' => 'date of update'
        ),
    );

    protected $aTableKeys = array(
        'PRIMARY'             => array('Non_unique' => '0', 'Column_name' => 'ID'),
        'ParentId'            => array('Non_unique' => '1', 'Column_name' => 'ParentId'),
        'MarketplaceIdentId'  => array('Non_unique' => '0', 'Column_name' => 'MarketplaceIdentId'),
        'MarketplaceIdentSku' => array('Non_unique' => '1', 'Column_name' => 'MarketplaceIdentSku'),
        'ProductsId'          => array('Non_unique' => '1', 'Column_name' => 'ProductsId'),
        'ProductsSku'         => array('Non_unique' => '1', 'Column_name' => 'ProductsSku'),
    );

    public function getBulletPointDefaultField() {
        return 'pd_tags';
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
            $result = 'pd_barcode';
        }

        if ($result === '') {
            $result = 'pd_barcode';
        }
        return $result;
    }

    public function getManufacturerDefaultField()
    {
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

    public function getManufacturerPartNumberDefaultField()
    {
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

    public function getBrandDefaultField()
    {
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
            $result = 'p_vendor';
        }

        if ($result === '') {
            $result = 'p_vendor';
        }
        return $result;
    }
}
