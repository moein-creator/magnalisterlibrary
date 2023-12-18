<?php
/*
 * 888888ba                 dP  .88888.                    dP
 * 88    `8b                88 d8'   `88                   88
 * 88aaaa8P' .d8888b. .d888b88 88        .d8888b. .d8888b. 88  .dP  .d8888b.
 * 88   `8b. 88ooood8 88'  `88 88   YP88 88ooood8 88'  `"" 88888"   88'  `88
 * 88     88 88.  ... 88.  .88 Y8.   .88 88.  ... 88.  ... 88  `8b. 88.  .88
 * dP     dP `88888P' `88888P8  `88888'  `88888P' `88888P' dP   `YP `88888P'
 *
 *                            m a g n a l i s t e r
 *                                        boost your Online-Shop
 *
 *   -----------------------------------------------------------------------------
 *   @author magnalister
 *   @copyright 2010-2022 RedGecko GmbH -- http://www.redgecko.de
 *   @license Released under the MIT License (Expat)
 *   -----------------------------------------------------------------------------
 */

class ML_Shopware_Model_Product extends ML_Shop_Model_Product_Abstract {

    /**
     * @var Shopware\Models\Article\Article $oProduct
     */
    protected $oProduct = null;

    /**
     * image-cache
     * @var null default
     * @var array loaded images for not calculating multiple times
     */
    protected $aImages = null;

    /**
     *it is true if we should return special price
     * @var bool
     */
    protected $blDiscountMode = false;

    /**
     * @return int
     */
    public function getId() {
        $this->load();
        return $this->oProduct->getId();
    }

    /**
     * @return $this|ML_Shop_Model_Product_Abstract
     * @throws Exception
     */
    protected function loadShopProduct() {
        // Shopware 5.7 compatiblity
        if (version_compare(MLSHOPWAREVERSION, '5.7', '>=')) {
            if (!Shopware()->Container()->initialized('shop')) {
                // marketplace language
                $aConfig = MLModule::gi()->getConfig();
                $iLangId = $aConfig['lang'];
                $oShop = $this->getShopRepository()->find($iLangId);
                Shopware()->Container()->set('shop', $oShop);
            }
        } else {
            if (!Shopware()->Bootstrap()->issetResource('Shop')) {
                // marketplace language
                $aConfig = MLModule::gi()->getConfig();
                $iLangId = $aConfig['lang'];
                $oShop = $this->getShopRepository()->find($iLangId);
                Shopware()->Bootstrap()->registerResource('Shop', $oShop);
            }
        }

        if ($this->oProduct === null) {//not loaded
            $this->oProduct = false; //not null
            if ($this->get('parentid') == 0) {
                $oProduct = $this;
            } else {
                $oProduct = $this->getParent();
            }
            $sKey = MLDatabase::factory('config')->set('mpid', 0)->set('mkey', 'general.keytype')->get('value');
            if ($sKey == 'pID') {
                $oShopProduct = $this->getRepository()->find((int)$oProduct->get('productsid'));
            } else {
                $oDetail = current($this->getDetailRepository()->findBy(array('number' => $oProduct->get('productssku'))));
                $oShopProduct = is_object($oDetail) ? $oDetail->getArticle() : null;
            }
            if (!($oShopProduct instanceof \Shopware\Models\Article\Article) && $this->get('id') !== 0) { // $this->get('id')!== 0 because of OldLib/php/modules/amazon/application/applicationviews.php line 556
                $iId = $this->get('id').' + '.$oProduct->get('productsid');
                $this->delete();
                MLMessage::gi()->addDebug('One of selected product was deleted from shop now it is deleted from magnalister list too, please refresh the page : '.$iId);
                throw new Exception;
            }
            $aData = $this->get('shopdata');
            $this->oProduct = $oShopProduct;
            $this->getArticleDetail(); //check if detail exists in shop or not
            $this->prepareProductForMarketPlace();
            if ($this->get('parentid') != 0) {//is variant
                $this->loadByShopProduct($oShopProduct, $this->get('parentid'), $aData);
            }
        }
        return $this;
    }

    /**
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
            $aDetails = MLHelper::gi('model_product')->getProductDetails($this->getLoadedProduct()->getId());
            foreach ($aDetails as $aRow) {
                $aVariant = array();
                if (count($aDetails) == 1 && (empty($aRow['prices']) || empty($aRow['configuratorOptions']))) {//add one variation for singel product
                    $aVariant['variation_id'] = $aRow['id'];
                    $aVariant['info'][] = array();
                } elseif(!empty($aRow['prices']) && !empty($aRow['configuratorOptions'])) {//add variation which have price and cofiguration option
                    foreach ($aRow['configuratorOptions'] as $oOption) {
                        $aVariant['variation_id'] = $aRow['id'];
                        $aVariant['info'][] = array('name' => $oOption["name"], 'value' => $oOption["group"]["name"]);
                    }
                }
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
     * @param mixed $oProduct
     * @param int $iParentId
     * @param null $mData
     * @return $this|mixed
     * @throws Exception
     */
    public function loadByShopProduct($oProduct, $iParentId = 0, $mData = null) {
        $this->oProduct = $oProduct;
        /* for product who have no reference number ,random reference is inserted because it will made problem when product key is Article number */
        $sKey = MLDatabase::factory('config')->set('mpid', 0)->set('mkey', 'general.keytype')->get('value');
        if($sKey == 'pID' ){
            $sProductKeyField = 'marketplaceidentid';
        }else{
            //here we use productssku instead of marketplaceidentsku, because if product was single product and now it has several variants we can find old product with productssku
            $sProductKeyField = 'productssku';
        }
        $this->aKeys = array($sProductKeyField, 'parentid');
        $this->set('parentid', $iParentId);
        $sMessage = array();
        if ($iParentId == 0) {
            $sSku = $this->oProduct->getMainDetail()->getNumber().($this->getRealVariantCount() > 0 ? '_Master' : '');
            $this
                ->set('marketplaceidentid', $this->oProduct->getId())
                ->set('marketplaceidentsku', $sSku)
                ->set('productsid', $this->oProduct->getId())
                ->set('productssku', $this->oProduct->getMainDetail()->getNumber())
                ->set('shopdata', array())
                ->set('data', array('messages' => $sMessage))
                ->save()
                ->aKeys = array('id');
            $this->prepareProductForMarketPlace();
        } else {
            if (!isset($mData['variation_id'])) {
                throw new Exception('not key set for product variation');
            }
            $oVariation = $this->getDetailRepository()->find((int)$mData['variation_id']);
            if (!($oVariation instanceof \Shopware\Models\Article\Detail)) {
                $this->delete();
                MLMessage::gi()->addDebug('One of selected product was deleted from shop now it is deleted from magnalister list too, please refresh the page');
                throw new Exception;
            }
            $this
                ->set('marketplaceidentid', $oVariation->getId().'_'.$this->oProduct->getId())
                ->set('marketplaceidentsku', $oVariation->getNumber())
                ->set("productsid", $oVariation->getId())
                ->set("productssku", $oVariation->getNumber())
                ->set('shopdata', $mData)
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

    static protected $createdObjectCache = array();

    /**
     *  if there some problems, see getByMarketplaceSKU method of magneto_model_product
     * @param $sSku
     * @return array
     */
    public function createModelProductByMarketplaceSku($sSku) {
        $aOut = array('master' => null, 'variant' => null);
        $oMyTable = MLProduct::factory();
        /* @var $oMyTable ML_Shopware_Model_Product  */
        $oShopProduct = null;
        if (MLDatabase::factory('config')->set('mpid', 0)->set('mkey', 'general.keytype')->get('value') == 'pID') {
            $sIdent = 'marketplaceidentid';
            if (strpos($sSku, '_') !== false) {
                $aIds = explode("_", $sSku);
                if (is_numeric($aIds[1])) {
                    $oShopProduct = $this->getRepository()->find($aIds[1]);
                }
            } else if (is_numeric($sSku)) {
                $oShopProduct = $this->getRepository()->find($sSku);
            }
        } else {
            $sIdent = 'marketplaceidentsku';
            $sMainSku = str_replace('_Master', '', $sSku);
            $oArticleDetail = $this->getDetailRepository()->findOneBy(array('number' => $sMainSku));
            /* @var  $oArticleDetail Shopware\Models\Article\Detail */

            if ($oArticleDetail !== null) {
                $oShopProduct = $oArticleDetail->getArticle();
            }
        }
        try {
            if ($oShopProduct != null && is_object($oShopProduct->getMainDetail())) {
                $iShopProductId = $oShopProduct->getId();
                if (!isset(self::$createdObjectCache[$iShopProductId])) {
                    $oMyTable->loadByShopProduct($oShopProduct);
                    $oMyTable->getVariants();
                    self::$createdObjectCache[$iShopProductId]['master'] = $oMyTable;
                    foreach ($oMyTable->getVariants() as $oVariant) {
                        self::$createdObjectCache[$iShopProductId]['variants'][$oVariant->get($sIdent)] = $oVariant;
                    }
                }
                if (self::$createdObjectCache[$iShopProductId]['master']->get($sIdent) === $sSku) {
                    $aOut['master'] = self::$createdObjectCache[$iShopProductId]['master'];
                }
                if (isset(self::$createdObjectCache[$iShopProductId]['variants'][$sSku])) {
                    $aOut['variant'] = self::$createdObjectCache[$iShopProductId]['variants'][$sSku];
                }
            }
        } catch (Exception $ex) {
            MLMessage::gi()->addDebug($ex);
        }
        return $aOut;
    }

    /**
     * @param string $sSku
     * @param bool $blMaster
     * @return $this|ML_Shop_Model_Product_Abstract|ML_Shopware_Model_Product
     */
    public function getByMarketplaceSKU($sSku, $blMaster = false) {
        if (empty($sSku)) {
            return $this->set('id', 0);
        } else {
            $aCreated = $this->createModelProductByMarketplaceSku($sSku);
            $this->init(true);
            if ($aCreated[$blMaster ? 'master' : 'variant'] instanceof ML_Shop_Model_Product_Abstract ) {
                $this->set('id', $aCreated[$blMaster ? 'master' : 'variant']->get('id'));
            }
            return $this;
        }
    }

    /**
     * @return string
     */
    public function getShortDescription() {
        $this->load();
        $aDesc = MLHelper::gi('model_product')->getTranslatedInfo($this->oProduct->getId());
        return $aDesc['description'];
    }

    /**
     * @return string|string[]|null
     */
    public function getDescription() {
        $this->load();
        $aDesc = MLHelper::gi('model_product')->getTranslatedInfo($this->oProduct->getId());
        $callback = array(Shopware()->Plugins()->Core()->PostFilter(),'rewriteSrc');
        $sDescription = preg_replace_callback('#<(link|img|script|input|a|form|iframe|td)[^<>]*(href|src|action|background)="([^"]*)".*>#Umsi', $callback, $aDesc['description_long']);
//      alternative replacing: normal replacing doesn't work correct for some older version of shopware 4.3.7
        $sDescription = preg_replace_callback('#<a[^<>]*href="(/backend/)[^"]*".*>#Umsi', array($this, 'correctUrls'), $sDescription);
        return $sDescription;
    }

    /**
     * @param $link
     * @return mixed
     */
    protected function correctUrls($link) {
        $sFrontBaseUrl = str_replace('Magnalister/index?', '', MLHttp::gi()->getFrontendDoUrl());
        return isset($link[1])? str_replace($link[1], $sFrontBaseUrl, $link[0]): $link[0];
    }

    /**
     * @return int|string
     */
    public function getEditLink() {
        $this->load();
        return $this->oProduct->getId();
    }

    /**
     * return product front url
     * @return string
     */
    public function getFrontendLink() {
        $oModule = Shopware()->Models();
        $aConf = MLModul::gi()->getConfig();
        $oShop = $oModule->getRepository('\Shopware\Models\Shop\Shop')->find($aConf['lang']);
        $aUrl = array();
        $aUrl[] = ($oShop->getSecure() ? 'https' : 'http' ) . '://'; //http protocol
        $sHost = trim((method_exists($oShop, 'getSecureHost') && $oShop->getSecure() ? $oShop->getSecureHost() : $oShop->getHost()));
        $aUrl[] = empty($sHost) ? Shopware()->Front()->Request()->getHttpHost() : $sHost; //domain or host name
        $sBasePath = trim($oShop->getBaseUrl());
        $aUrl[] = (empty($sBasePath) ? Shopware()->Front()->Request()->getBaseUrl() : $sBasePath).'/'; // path to shop
        $sFrontBaseUrl = implode('', $aUrl);
        $this->load();
        if ($this->get('parentid') == 0) {
            return $sFrontBaseUrl . Shopware()->Modules()->System()->sSYSTEM->sCONFIG['sBASEFILE'] . '?sViewport=detail&sArticle=' . $this->getLoadedProduct()->getId();
        } else {
            return $sFrontBaseUrl . Shopware()->Modules()->System()->sSYSTEM->sCONFIG['sBASEFILE'] . '?sViewport=detail&sArticle=' . $this->getLoadedProduct()->getId() . '&number=' . $this->get('productssku');
        }
    }

    /**
     * @param $aImageUrl
     * @return bool|string
     */
    protected function getImagePathbyUrl($aImageUrl) {
        $sShopImgPath = MLHttp::gi()->getShopImagePath();

        // If field "src"->"original" is empty try to get image file path from shopware and set it manually to be loaded from media server
        // If original images does not exist on server try to get it from media server by name
        if (empty($aImageUrl['src']['original'])) {
            /** @var \Shopware\Models\Article\Image $oImage */
            $oImage = Shopware()->Models()->getRepository('\Shopware\Models\Article\Image')->find($aImageUrl['id']);
            if ($oImage->getMedia() !== null) {
                $imgFilePath = $oImage->getMedia()->getPath();
                if (!empty($imgFilePath)) {
                    $aImageUrl['src']['original'] = $imgFilePath;
                }
            }
        }

        if (!empty($aImageUrl) && !empty($aImageUrl['src']['original']) && strpos($aImageUrl['src']['original'], $sShopImgPath) !== false) {
            try {
                $mediaService = Shopware()->Container()->get('shopware_media.media_service');
                $mediaPath = $mediaService->getUrl($aImageUrl['src']['original']);
            } catch (Exception $oEx) {
                $mediaPath = $aImageUrl['src']['original'];
            }
            //convert all url to file path
            $mediaPath = substr($mediaPath, strpos($mediaPath, $sShopImgPath));
            return $mediaPath;
        }
        return false;
    }

    /**
     * @param int $iX
     * @param int $iY
     * @return array|string
     */
    public function getImageUrl($iX = 40, $iY = 40) {
        $this->load();
        try {
            if (version_compare(MLSHOPWAREVERSION, '5.5.0', '>=') && $this->get('parentid') == 0) {
                $aImg = Shopware()->Modules()->Articles()->sGetArticlePictures((int) $this->oProduct->getId(), true, 0, $this->get('productssku'), false, false, true);
            } else {
                $aImg = Shopware()->Modules()->Articles()->sGetArticlePictures((int) $this->oProduct->getId(), true, 0, $this->get('productssku'));
            }
            if (($sImagePath = $this->getImagePathbyUrl($aImg)) !== false) {
                return MLImage::gi()->resizeImage($sImagePath, 'products', $iX, $iY, true);
            } else {//get images by media
                $oImage = Shopware()->Models()->getRepository('\Shopware\Models\Article\Image')->find($aImg['id']);
                if (is_object($oImage) && is_object($oImage->getMedia())) {
                    $sImage = $oImage->getMedia()->getPath();
                    return MLImage::gi()->resizeImage($sImage, 'products', $iX, $iY, true);
                } else {
                    return '';
                }
            }
        } catch (Exception $oEx) {
            return '';
        }
    }

    /**
     * @return array|null
     */
    public function getImages() {
        if ($this->aImages === null) {
            $this->load();
            $aImages = array();
            $aImgs = array();
            if ($this->get('parentid') == 0) {
                foreach (MLHelper::gi('model_product')->getProductDetails($this->getLoadedProduct()->getId()) as $aVariation) {
                    $aVarCoverImage = Shopware()->Modules()->Articles()->sGetArticlePictures((int)$this->oProduct->getId(), true, 0, $aVariation['number']);
                    $aVarImage = Shopware()->Modules()->Articles()->sGetArticlePictures((int)$this->oProduct->getId(), false, 0, $aVariation['number']);
                    $aImgs = array_merge($aImgs, array($aVarCoverImage), $aVarImage);
                }
            } else {
                $aImgs = Shopware()->Modules()->Articles()->sGetArticlePictures((int)$this->oProduct->getId(), false, 0, $this->get('productssku'));
            }
            $aCoverImg = Shopware()->Modules()->Articles()->sGetArticlePictures((int)$this->oProduct->getId(), true, 0, $this->get('productssku'));
            $aImgs = array_merge(array($aCoverImg), $aImgs);
            foreach ($aImgs as $aImg) {
                $aImages = array_merge($aImages, $this->getImagesArray($aImg));
            }
            $this->aImages = array_unique($aImages);
        }
        return $this->aImages;
    }

    /**
     * @param $aImg
     * @return array
     */
    protected function getImagesArray($aImg){
        $aImages = array();
        try {
            if (isset($aImg['id'])) {
                if (($sImagePath = $this->getImagePathbyUrl($aImg)) !== false) {
                    $aImages[] = $sImagePath;
                } else {
                    $oCover = Shopware()->Models()->getRepository('\Shopware\Models\Article\Image')->find($aImg['id']);
                    if (is_object($oCover) && is_object($oCover->getMedia())) {
                        $aImages[] = $oCover->getMedia()->getPath();
                    }
                }
            }
        } catch (Exception $oExc) {}
        return $aImages;
    }

    /**
     * @param int $iX
     * @param int $iY
     * @return array|string
     */
    public function getOldImageUrl($iX = 40, $iY = 40) {
        $this->load();
        try {
            $aImg = Shopware()->Modules()->Articles()->sGetArticlePictures((int)$this->oProduct->getId(), true, 0, $this->get('productssku'));
            $sImage = Shopware()->Models()->getRepository('\Shopware\Models\Article\Image')->find($aImg['id'])->getMedia()->getPath();
            return MLImage::gi()->resizeImage($sImage, 'product', $iX, $iY, true);
        } catch (Exception $oEx) {
            return '';
        }
    }

    /**
     * @return array
     */
    public function getOldImages() {
        $this->load();
        $aImgs = Shopware()->Modules()->Articles()->sGetArticlePictures((int)$this->oProduct->getId(), false, 0, $this->get('productssku'));
        $aCoverImg = Shopware()->Modules()->Articles()->sGetArticlePictures((int)$this->oProduct->getId(), true, 0, $this->get('productssku'));

        $aImages = array();
        $aImages[] = $aCoverImg['src']['original'];
        foreach ($aImgs as $aImg) {
            $aImages[] = $aImg['src']['original'];
        }
        return $aImages;
    }

    /**
     * @return string
     */
    public function getMetaDescription() {
        return $this->getShortDescription();
    }

    /**
     * @return string
     */
    public function getMetaKeywords() {
        $this->load();
        $aDesc = MLHelper::gi('model_product')->getTranslatedInfo($this->oProduct->getId());
        return $aDesc['keywords'];
    }

    /**
     * @param $sFieldName
     * @param bool $blGeneral
     * @return \Doctrine\ORM\PersistentCollection|int|string|null
     * @throws Exception
     */
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
     *
     * @var Shopware\Models\Article\Detail
     */
    protected $oRealProduct = null;

    /**
     *
     * @return Shopware\Models\Article\Detail
     */
    public function getArticleDetail() {
        if ($this->oProduct === null) {
            $this->load();
        }
        if ($this->oRealProduct === null) {
            $sku = $this->get('MarketplaceIdentId');
            if (strpos($sku, '_') !== false) {
                $oProduct = $this->getDetailRepository()->find((int)$this->get('productsid'));
            } else {
                try {
                    $oProduct = $this->oProduct->getMainDetail();
                } catch (Exception $oEx) {
                    $oProduct = null;
                }
            }
            if (!is_object($oProduct)) {
                MLMessage::gi()->addDebug('Product doesn\'t exist . SKU : '.$this->get('productssku'));
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
     * @return string
     */
    public function getName() {
        $this->load();
        $aName = Shopware()->Modules()->Articles()->sGetArticleNameByOrderNumber($this->get('productssku'), true);
        $mConfiguratorSet = $this->getLoadedProduct()->getConfiguratorSet();
        $oPHelper = MLHelper::gi('model_product');
        /* @var $oPHelper ML_Shopware_Helper_Model_Product */
        if(Shopware()->Shop()->getDefault() || count(MLFormHelper::getShopInstance()->getDescriptionValues()) === 1){// If configured Shop is default shop or there is only one language in Shopware, we should use main title from s_articles table like shopware frontend
            $sMasterProductName = $aName['articleName'];
        } else {
            $sMasterProductName = $oPHelper->translate($this->getLoadedProduct()->getId(), 'article', 'name', $aName['articleName']);
        }

        return $sMasterProductName.(!empty($mConfiguratorSet) && $aName['additionaltext'] != '' && $this->get('parentid') != 0 ? " : {$aName['additionaltext']}" : '');
    }

    /**
     * @param bool $blGros
     * @param bool $blFormated
     * @return mixed
     * @throws Enlight_Exception
     */
    public function getShopPrice($blGros = true, $blFormated = false) {
        $this->load();
        $mReturn = $this->getPrice($blGros, $blFormated/* ,false */);
        return $mReturn;
    }

    /**
     *
     * @param \ML_Shop_Model_Price_Interface $oPrice
     * @param bool $blGros
     * @param bool $blFormated
     * @return mixed
     *
     * @throws Exception
     * @todo check price groups for special price
     */
    public function getSuggestedMarketplacePrice(\ML_Shop_Model_Price_Interface $oPrice, $blGros = true, $blFormated = false) {
        $this->load();
        $aConf = $oPrice->getPriceConfig();
        $fTax = $aConf['tax'];
        $sPriceKind = $aConf['kind'];
        $fPriceFactor = (float)$aConf['factor'];
        $iPriceSignal = $aConf['signal'];
        $this->blDiscountMode = $aConf['special'];
        $oCustomerGroup = $this->getGroupRepository()->find($aConf['group']);
        if (is_object($oCustomerGroup)) {
            $aCurrentUSERGROUPDATA = Shopware()->System()->sUSERGROUPDATA;
            Shopware()->System()->sUSERGROUPDATA = $oCustomerGroup->toArray();
        } else {
            $sMarketplace = MLModule::gi()->getModuleBaseUrl();
            $sController = MLRequest::gi()->get('controller');
            if (strpos($sController, $sMarketplace) !== false) {
                MLHttp::gi()->redirect(MLHttp::gi()->getUrl(array('controller' => $sMarketplace.'_config'.MLModule::gi()->getPriceConfigurationUrlPostfix())));
            } else {
                throw new Exception('Customer-group should be configured again:'.MLHttp::gi()->getUrl(array('controller' => $sMarketplace.'_config'.MLModule::gi()->getPriceConfigurationUrlPostfix())));
            }
        }

        $mReturn = $this->getPrice($blGros, $blFormated, $sPriceKind, $fPriceFactor, $iPriceSignal, $fTax);
        Shopware()->System()->sUSERGROUPDATA = $aCurrentUSERGROUPDATA;
        return $mReturn;
    }

    /**
     * @param $blGros
     * @param $blFormated
     * @param string $sPriceKind
     * @param float $fPriceFactor
     * @param null $iPriceSignal
     * @param null $fTax
     * @return mixed
     * @throws Enlight_Exception
     */
    protected function getPrice($blGros, $blFormated, $sPriceKind = '', $fPriceFactor = 0.0, $iPriceSignal = null, $fTax = null) {
        if(!isset(Shopware()->System()->sUSERGROUPDATA)){
            Shopware()->System()->sUSERGROUPDATA = Shopware()->Shop()->getCustomerGroup()->toArray();
        }
        $oPHelper = MLHelper::gi('model_product');
        /* @var $oPHelper ML_Shopware_Helper_Model_Product */
        $fNet = $oPHelper->getDefaultPrices($this->getArticleDetail()->getId());

        if($fTax !== null){
            $fPercent = $fTax;
            $oTax = Shopware()->Models()->getRepository('\Shopware\Models\Tax\Tax')->findOneBy(array('tax' => $fTax));
            if(!is_object($oTax)){
                throw new Exception('tax "'.$fTax.'" doesn\'t exist');
            }
        } else {
            $fPercent = $this->getTax();
            $oTax = $this->oProduct->getTax();
        }

        //initialize the right shop and customer group in order to get correct tax
        try {
            Shopware()->Container()->get('shopware_storefront.context_service')->initializeShopContext();
        } catch (Exception $e) {
        }

        $fBrutPrice = Shopware()->Modules()->Articles()->sCalculatingPriceNum($fNet, $fPercent, false, false, $oTax->getId());
        $oPrice = MLPrice::factory();
        //check user group tax if it is disabled we include tax manually
        if (!Shopware()->System()->sUSERGROUPDATA["tax"]) {
            $fBrutPrice = $oPrice->calcPercentages(null, $fBrutPrice, $fPercent);
        }
        // check user group special price
        if($this->blDiscountMode){
            $fDiscount = -1 * (float)Shopware()->System()->sUSERGROUPDATA['basketdiscount'];
            $fBrutPrice = $oPrice->calcPercentages(null, $fBrutPrice, $fDiscount);
        }
        // The code after this line could be replace with this commented line, it should be tested in future
        // return $this->configurePrice($fBrutPrice, $fPercent, $blGros, $blFormated, $sPriceKind , $fPriceFactor , $iPriceSignal );

        // add modul price to brut
        if ($sPriceKind == 'percent') {
            $fBrutPrice = $oPrice->calcPercentages(null, $fBrutPrice, $fPriceFactor);
        } elseif ($sPriceKind == 'addition') {
            $fBrutPrice = $fBrutPrice + $fPriceFactor;
        }
        if ($iPriceSignal !== null) {
            //If price signal is single digit then just add price signal as last digit
            if (strlen((string)$iPriceSignal) == 1) {
                $fBrutPrice = (0.1 * (int)($fBrutPrice * 10)) + ((int)$iPriceSignal / 100);
            } else {
                $fBrutPrice = ((int)$fBrutPrice) + ((int)$iPriceSignal / 100);
            }
        }
        if ($fPercent === null) {
            $fPercent = 0;
        }
        //calc net price from modul price
        $fNetPrice = $oPrice->calcPercentages($fBrutPrice, null, $fPercent);
        // define out price (unformated price of current shop)
        $fUsePrice = $blGros ? $fBrutPrice : $fNetPrice;
        if ($blFormated) {
            return MLHelper::gi('model_price')->getPriceByCurrency($fUsePrice, null, true);
        } else {
            return MLHelper::gi('model_price')->getPriceByCurrency($fUsePrice, null);
        }

    }

    /**
     * return article object from s_articles
     * @return Shopware\Models\Article\Article
     */
    public function getLoadedProduct() {
        if ($this->oProduct === null) {
            $this->load();
        }
        return $this->oProduct;
    }

    /**
     * Gets the tax percentage of the item.
     * if $aAddressData is set, it try to locate tax for $aAddressData['Shipping']
     * @param null $aAddressSets get tax for home country
     * @param array $aAddressSets get tax for $aAddressData array('Main' => [], 'Billing' => [], 'Shipping' => []);
     * @return float
     */
    public function getTax($aAddressSets = null) {
        if ($aAddressSets !== null) {
            $aAddressData = $aAddressSets['Shipping'];
            $oCountry = Shopware()->Models()
                ->getRepository('\Shopware\Models\Country\Country')
                ->findOneBy(array('iso' => $aAddressData['CountryCode']))
            ;
            if ($oCountry === null || !is_object($oCountry->getArea())) {//when country doesn't exist or when country doesn't have any area, we cannot use address to calculate tax, we retrun normal tax
                return $this->getTax();// without address
            }
            $iCustomerGroupId = MLModule::gi()->getConfig('customergroup');
            $vatCustomerGroudId = MLModule::gi()->getConfig('orderimport.vatcustomergroup');
            if (!empty($vatCustomerGroudId)) {
                $iCustomerGroupId = $vatCustomerGroudId;
            }

            $iCountryId = $oCountry->getId();
            $iAreaId = $oCountry->getArea()->getId();
            $iStateId = null;
            if (array_key_exists('Suburb', $aAddressData) && !empty($aAddressData['Suburb'])) {
                foreach ($oCountry->getStates() as $oState) {
                    if ($oState->getShortCode() == $aAddressData['Suburb']) {
                        $iStateId = $oState->getId();
                        break;
                    }
                }
            }

            $oProductTax = $this->getLoadedProduct()->getTax();
            //its possible that products has not tax set (#2020111210003559) then $oProductTax is not an object
            if (!is_object($oProductTax)) {
                return MLModul::gi()->getConfig('mwstfallback');
            }

            $mTaxRate = $this->getTaxRateByConditions(
                $oProductTax->getId(),
                $iAreaId,
                $iCountryId,
                $iStateId,
                $iCustomerGroupId
            );
            if (is_numeric($mTaxRate)) {
                return $mTaxRate;
            }

        }

        try {
            $fTax = $this->getLoadedProduct()->getTax()->getTax();
        } catch (Exception $oEx) {
            // its possible that products has not tax set (#2016091510000747)
            $fTax = MLModul::gi()->getConfig('mwstfallback');
        }

        return $fTax;
    }

    /**
     * @see \Shopware\Models\Tax\Repository;
     * its not part of shopware version 4.1
     */
    protected function getTaxRateByConditions($taxId, $areaId, $countryId, $stateId, $customerGroupId) {
        $sql = "
            SELECT id, tax FROM s_core_tax_rules WHERE
                active = 1 AND groupID = :taxId
                AND (areaID = :areaId OR areaID IS NULL)
                AND (countryID = :countryId OR countryID IS NULL)
                AND (stateID = :stateId OR stateID IS NULL)
                AND (customer_groupID = :customerGroupId OR customer_groupID = 0 OR customer_groupID IS NULL)
            ORDER BY customer_groupID DESC, areaID DESC, countryID DESC, stateID DESC
            LIMIT 1
        ";

        $parameters = array(
            'taxId' => $taxId,
            'areaId' => $areaId,
            'countryId' => $countryId,
            'stateId' => $stateId,
            'customerGroupId' => $customerGroupId
        );

        $dbalConnection = Shopware()->Models()->getConnection();
        $taxRate = $dbalConnection->fetchAssoc($sql, $parameters);

        if (!is_array($taxRate) || empty($taxRate['id'])) {
            $taxRate = $dbalConnection->fetchAssoc("SELECT tax FROM s_core_tax WHERE id = ?", array($taxId));
        }
        return $taxRate['tax'];
    }

    /**
     * @return int
     */
    public function getTaxClassId() {
        return $this->getLoadedProduct()->getTax()->getId();
    }

    /**
     * @return int
     * @throws Exception
     */
    public function getStock() {
        if($this->get('parentid') == '0'){
            return MLHelper::gi('model_product')->getTotalCount($this->getLoadedProduct());
        }else{
            return $this->getArticleDetail()->getInStock();
        }
    }

    /**
     * @param string $sType
     * @param float $fValue
     * @param null $iMax
     * @return int
     * @throws Exception
     */
    public function getSuggestedMarketplaceStock($sType, $fValue, $iMax = null) {
        if(
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
     * @throws Exception
     */
    public function getVariatonData() {
        return $this->getVariatonDataOptinalField(array('name', 'value'));
    }

    /**
     * Get name, value, value id and prefixed shop code.
     *
     * @return array
     */
    public function getPrefixedVariationData() {
        $variationData = $this->getVariatonDataOptinalField(array('name','value', 'code', 'valueid'));

        foreach ($variationData as &$variation) {
            if (is_numeric($variation['code'])) {
                $variation['code'] = 'c_' . $variation['code'];
            }
        }

        return $variationData;
    }

    protected static $aVariationGroupIds = array();

    /**
     * @return mixed
     */
    public function getVariatonGroupIds() {
        $iProductId = $this->getLoadedProduct()->getId();
        if(!isset(self::$aVariationGroupIds[$iProductId])) {
            $builder = Shopware()->Models()->createQueryBuilder();
            $builder->select(array('configuratorSet', 'groups'))
                ->from('Shopware\Models\Article\Configurator\Set', 'configuratorSet')
                ->innerJoin('configuratorSet.articles', 'article')
                ->leftJoin('configuratorSet.groups', 'groups')
                ->where('article.id = :articleId')
                ->addOrderBy('groups.position')
                ->addOrderBy('groups.id')
                ->setParameters(array('articleId' => $this->getLoadedProduct()->getId()));
            self::$aVariationGroupIds[$iProductId] = array();
            $aGroupsData = $builder->getQuery()->getarrayResult();
            foreach($aGroupsData[0]['groups'] as $aGroupData){
                self::$aVariationGroupIds[$iProductId][] = $aGroupData['id'];
            }
        }
        return self::$aVariationGroupIds[$iProductId];
    }

    /**
     * @param array $aFields
     * @return array
     * @throws Exception
     */
    public function getVariatonDataOptinalField($aFields = array()) {
        $aOut = array();
        $aOption = $this->getArticleDetail()->getConfiguratorOptions();
        if (version_compare(MLSHOPWAREVERSION, '5.6.0', '>=')) {
            $translationWriter = new \Shopware_Components_Translation(Shopware()->Container()->get('dbal_connection'), Shopware()->Container());
        } else {
            $translationWriter = new \Shopware_Components_Translation();
        }
        $iLanguage = Shopware()->Shop()->getId();
        $mConfiguratorSet = $this->getLoadedProduct()->getConfiguratorSet();
        try{
            if ($aOption->count() > 0 && !empty($mConfiguratorSet)) {
                $aGroupIds = $this->getVariatonGroupIds();
                foreach ($aOption as $oConfigur) {
                    $iGroupId = $oConfigur->getGroup()->getId();
                    $iPosition = array_search($iGroupId, $aGroupIds);
                    if($iPosition !== false) {
                        $iValueId = $oConfigur->getId();
                        $aData = array();
                        if (in_array('code', $aFields)) {//an identifier for group of attributes , that used in Meinpaket at the moment
                            $aData['code'] = 'c_'.$oConfigur->getGroup()->getId();
                        }
                        if (in_array('valueid', $aFields)) {//an identifier for group of attributes , that used in Meinpaket at the moment
                            $aData['valueid'] = $oConfigur->getId();
                        }
                        if (in_array('name', $aFields)) {
                            $sGroupName = $translationWriter->read($iLanguage, 'configuratorgroup', $iGroupId);
                            if (empty($sGroupName) || !isset($sGroupName['name'])) {
                                $aData['name'] = $oConfigur->getGroup()->getName();
                            } else {
                                $aData['name'] = $sGroupName['name'];
                            }
                        }
                        if (in_array('value', $aFields)) {
                            $sValueName = $translationWriter->read($iLanguage, 'configuratoroption', $iValueId);
                            if (empty($sValueName) || !isset($sValueName['name'])) {
                                $aData['value'] = $oConfigur->getName();
                            } else {
                                $aData['value'] = $sValueName['name'];
                            }
                        }
                        $aOut[$iPosition] = $aData;
                    }
                }
            }
            ksort($aOut);
        }catch(Exception $oExc){
          MLMessage::gi()->addDebug($oExc);
        }
        return $aOut;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function isActive() {
        $blIsMasterActive = (bool)$this->getLoadedProduct()->getActive();
        if ($this->getVariantCount() > 1) {
            $blIsVariantActive = (bool)$this->getArticleDetail()->getActive();
            return $blIsMasterActive && $blIsVariantActive;
        } else {
            return $blIsMasterActive;
        }

    }

    /**
     * @param int $iStock
     * @return bool|ML_Shop_Model_Product_Abstract
     */
    public function setStock($iStock) {
        try {
            $oEntityManager = Shopware()->Models();
            $oDetail = $this->getArticleDetail();
            $oDetail->setInStock($iStock);
            $oEntityManager->persist($oDetail);
            $oEntityManager->flush($oDetail);
            $oEntityManager->clear();
            return true;
        } catch (Exception $oExc) {
            MLMessage::gi()->addDebug($oExc);
            return false;
        }
    }

    protected function prepareProductForMarketPlace() {
        try {
            $oModule = Shopware()->Models();
            $aConf = MLModule::gi()->getConfig();
            //set shopware default shop
            $oShop = $oModule->getRepository('\Shopware\Models\Shop\Shop')->find($aConf['lang']);
            // Shopware 5.7 compatiblity
            if (version_compare(MLSHOPWAREVERSION, '5.7', '>=')) {
                Shopware()->Container()->set('shop', $oShop);
            } else {
                Shopware()->Bootstrap()->registerResource('Shop', $oShop);
            }
            //set shopware default currency
            $oCurrency = $oModule->getRepository('\Shopware\Models\Shop\Currency')->findOneBy(array('currency' => $aConf['currency']));
            //set module currency
            Shopware()->Modules()->System()->sSYSTEM->sCurrency = Shopware()->Models()->toArray($oCurrency);
            //set default shop currency
            //Shopware()->Shop()->setCurrency($oCurrency);
        } catch (Exception $oExc) {
            MLMessage::gi()->addDebug($oExc);
        }
    }

    /**
     * @return array
     */
    protected function getRootCategoriesIds() {
        $repository = Shopware()->Models()->getRepository('Shopware\Models\Category\Category')->createQueryBuilder('c');
        $aResult = $repository->select('c.id')
            ->where('c.parentId = 1')
            ->getQuery()
            ->getArrayResult();

        $invalidIds = array(1);
        foreach ($aResult as $id) {
            $invalidIds[] = $id['id'];
        }

        return $invalidIds;
    }

    static $aShopwareProductCategories = null;

    /**
     * @return mixed
     */
    protected function getCategories(){
        $sKey = MLDatabase::factory('config')->set('mpid', 0)->set('mkey', 'general.keytype')->get('value') == 'pID' ? 'productsid' : 'productssku';
        $sField = $this->get($sKey);
        if(self::$aShopwareProductCategories[$sField] === null){
            self::$aShopwareProductCategories[$sField] = array();
            foreach ($this->getLoadedProduct()->getCategories() as $oCategory) {
                /* @var $oCategory \Shopware\Models\Category\Category */
                $iShopCategoryId = Shopware()->Shop()->getCategory()->getId();
                if (strpos($oCategory->getPath(), '|'.$iShopCategoryId.'|') !== false) {
                    self::$aShopwareProductCategories[$sField][] = $oCategory;
                }
            }
        }
        return self::$aShopwareProductCategories[$this->get($sKey)] ;
    }

    /**
     * @return mixed|string
     */
    public function getCategoryPath() {
        $sCatPath = '';
        $aInvalidIds = $this->getRootCategoriesIds();
        foreach ($this->getCategories() as $oCat) {
            $sInnerCat = '';
            while (is_object($oCat)) {
                if (!in_array($oCat->getId(), $aInvalidIds)) {
                    $sInnerCat = $oCat->getName().'&nbsp;&gt;&nbsp;'.$sInnerCat;
                }
                $oCat = $oCat->getParent();
            }
            $sCatPath .= $sInnerCat.'<br>';
        }
        return $sCatPath;
    }

    /**
     * @param bool $blIncludeRootCats
     * @return array
     */
    public function getCategoryIds($blIncludeRootCats = true) {
        $aCategories = array();
        $aFilterCats = $blIncludeRootCats ? array() : $this->getRootCategoriesIds();
        foreach ($this->getCategories() as $oCat) {
            /* @var $oCat \Shopware\Models\Category\Category */
            if (!in_array($oCat->getId(), $aFilterCats)) {
                $aCategories[] = $oCat->getId();
            }
        }
        return $aCategories;
    }

    /**
     * @param bool $blIncludeRootCats
     * @return array
     */
    public function getCategoryStructure($blIncludeRootCats = true) {
        $aCategories = array();
        $aInvalidIds = $aExistedCatId = $blIncludeRootCats ? array() : $this->getRootCategoriesIds();
        foreach ($this->getCategories() as $oCat) {
            /* @var $oCat \Shopware\Models\Category\Category */
            do {
                if (
                    in_array($oCat->getId(), $aInvalidIds)
                    ||
                    in_array($oCat->getId(), $aExistedCatId)
                ) {
                    break;
                }
                $sDescription = ($oCat->getMetaDescription() === null) ? '' : $oCat->getMetaDescription();
                $aCurrentCat = array(
                    'ID' => $oCat->getId(),
                    'Name' => $oCat->getName(),
                    'Description' => $sDescription,
                    'Status' => true,
                );
                $aExistedCatId[] = $oCat->getId();
                $oCat = $oCat->getParent();
                if (is_object($oCat) && !in_array($oCat->getId(), $aInvalidIds)) {
                    $aCurrentCat['ParentID'] = $oCat->getId();
                }
                $aCategories[] = $aCurrentCat;
            } while (is_object($oCat));
        }

        return $aCategories;
    }

    /**
     * @param $sName
     * @param null $sMethod
     * @return \Doctrine\ORM\PersistentCollection|int|string|null
     * @throws Exception
     */
    public function getProductField($sName, $sMethod = null) {
        if (strpos($sName, 'a_') === 0) {
            $sName = substr($sName, 2);
            $aAttribute = MLHelper::gi('model_product')->getFreeTextFieldValue($this->getArticleDetail(), array('name'=>$sName));
            return $aAttribute['value'];
        } else {
            if ($sName === 'articleId' ){
                return $this->oProduct->getId();
            } elseif ($sName === 'descriptionLong') {
                return $this->getDescription();
            } elseif ($sName === 'name') {
                return $this->getName();
            } elseif ($sName === 'description') {
                return $this->getShortDescription();
            } elseif ($sName === 'keywords') {
                return $this->getMetaKeywords();
            } elseif ($sName !== 'Id' && substr($sName, -2) === 'Id') {
                $sName = substr($sName, 0, -2);
            }
            if (method_exists($this->getArticleDetail(), 'get'.$sName)) {
                $mValue = $this->getArticleDetail()->{'get'.$sName}();
            } elseif (method_exists($this->oProduct, 'get'.$sName)) {
                $mValue = $this->oProduct->{'get'.$sName}();
            } else {
                MLMessage::gi()->addDebug('method get'.$sName.' does not exist in Article or ArticleDetails');
                return '';
            }
            /* @var $mValue \Doctrine\ORM\PersistentCollection */

            if (isset($mValue) && ($mValue instanceof \Doctrine\ORM\PersistentCollection)) {
                if (is_object($mValue->first())) {
                    try {
                        return $mValue->first()->getName();
                    } catch (Exception $ex) {
                        MLMessage::gi()->addDebug($ex);
                        return '';
                    }
                } else {
                    return '';
                }
            } elseif (is_object($mValue)) {
                try {
                    if ($sMethod === null) {
                        if(method_exists($mValue, 'getName')){
                            return $mValue->getName();
                        }else if($mValue instanceof \DateTime){
                            return $mValue->format('Y-m-d H:i:s');
                        }
                    } elseif ($sMethod === 'object') {
                        return $mValue;
                    } elseif (method_exists($mValue, $sMethod)) {
                        return $mValue->{$sMethod}();
                    } else {
                        throw new Exception('method connot be found in article object');
                    }

                } catch (Exception $ex) {
                    MLMessage::gi()->addDebug($ex);
                    return null;
                }

            } else {
                return $mValue;
            }
        }
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getWeight() {
        $sUnit = null;
        try {
            $oQueryBuilder = Shopware()->Models()->createQueryBuilder();
            $aRes = $oQueryBuilder
                ->select('snippet.value')
                ->from('Shopware\Models\Snippet\Snippet', 'snippet')
                ->where("snippet.namespace = 'backend/article/view/main' And snippet.name = 'detail/settings/weight_bw' AND snippet.localeId = ".Shopware()->Shop()->getLocale()->getId())
                ->getQuery()->getOneOrNullResult();
            if (stripos($aRes['value'], 'kg') !== false) {
                $sUnit = 'KG';
            } elseif (stripos($aRes['value'], 'lb') !== false) {
                $sUnit = 'LB';
            }
        } catch (Exception $ex) {
            MLMessage::gi()->addDebug($ex);
        }
        $oDetail = $this->getArticleDetail();
        $sWeight = (float)$oDetail->getWeight();
        if ($sWeight > 0) {
            return array(
                "Unit" => $sUnit === null ? "KG" : $sUnit,
                "Value" => $sWeight,
            );
        } else {
            return array();
        }
    }

    /**
     * @param float $fPrice
     * @param bool $blLong
     * @return string
     * @throws Exception
     */
    public function getBasePriceString($fPrice, $blLong = true) {
        $aBasePrice = $this->getBasePrice();
        if (empty($aBasePrice)) {
            return '';
        } else {

            $sBasePrice = str_replace('&euro;', '€', MLHelper::gi('model_price')->getPriceByCurrency(($fPrice / $aBasePrice['Value']), null, true));
            return
                round($aBasePrice['ShopwareDefaults']['$fPurchaseUnit'], 2).' '
                .$aBasePrice['ShopwareDefaults'][$blLong ? '$sUnitName' : '$sUnit']
                .' ('.$sBasePrice.' / '.round($aBasePrice['ShopwareDefaults']['$fReferenceUnit'], 2).' '.$aBasePrice['ShopwareDefaults'][$blLong ? '$sUnitName' : '$sUnit'].')';
        }
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getBasePrice() {
        $oDetail = $this->getArticleDetail();
        if (!is_object($oDetail)) {
            return array();
        }
        $oUnit = $oDetail->getUnit();
        if (!is_object($oUnit)) {
            return array();
        }
        try {
            $fReferenceUnit = $oDetail->getReferenceUnit();
            $fPurchaseUnit = $oDetail->getPurchaseUnit();
            $sUnitName = $oUnit->getName();
            $sUnit = $oUnit->getUnit();
        } catch (Exception $oEx) {
            return array();
        }
        if (empty($fReferenceUnit) && empty($fPurchaseUnit)) {//not configured base-price
            return array();
        }
        $fReferenceUnit = $fReferenceUnit <= 0 ? 1 : $fReferenceUnit;
        $fPurchaseUnit = $fPurchaseUnit <= 0 ? 1 : $fPurchaseUnit;
        return array(
            'ShopwareDefaults' => array(
                '$sUnit' => $sUnit,
                '$sUnitName' => $sUnitName,
                '$fReferenceUnit' => $fReferenceUnit,
                '$fPurchaseUnit' => $fPurchaseUnit,
            ),
            'Unit' => ((string)((float)$fReferenceUnit)).' '.$sUnitName,
            'UnitShort' => ((string)((float)$fReferenceUnit)).' '.$sUnit,
            'Value' => ((string)((float)$fPurchaseUnit / $fReferenceUnit)),
        );
    }

    /**
     * return html list, that contain property name and values
     * @return string
     */
    protected function getProperties() {
        try {
            $oArticle = $this->getLoadedProduct();
            $sPropertiesHtml = ' ';
            if (is_object($oArticle->getPropertyGroup())) {
                $aProperties = MLHelper::gi('model_product')->getProperties($this->getLoadedProduct()->getId(), $this->getLoadedProduct()->getPropertyGroup()->getId());

                if (isset($aProperties)) {
                    $sRowClass = 'odd';
                    $sPropertiesHtml .= '<ul class="magna_properties_list">';
                    foreach ($aProperties as $sName => $sValues) {
                        $sPropertiesHtml .= '<li class="magna_property_item ' . $sRowClass . '">'
                                . '<span class="magna_property_name">' . $sName
                                . '</span>'
                                . '<span  class="magna_property_value">' . implode(', ', $sValues)
                                . '</span>'
                                . '</li>';
                        $sRowClass = $sRowClass === 'odd' ? 'even' : 'odd';
                    }
                    $sPropertiesHtml .= '</ul>';
                }
            }
            return $sPropertiesHtml;
        } catch (Exception $oEx) {
            return '';
        }
    }

    /**
     *
     * @return array of freetextfield in shopware
     */
    public function getOpenTextField() {
        $aAttributes = array();
        $oProductHelper = MLHelper::gi('model_product');
        $aFields = $oProductHelper->getAttributeFields();
        foreach ($aFields as $aField) {
            if (!in_array($aField['type'], array('boolean')) && $aField['configured']  ) {
                $aAttribute = $oProductHelper->getFreeTextFieldValue($this->getArticleDetail(), $aField);
                if($aAttribute['value'] != ''){
                    $aAttributes[$aField['position']] = $aAttribute;
                }
            }
        }
        return $aAttributes;
    }

    /**
     * @return array
     */
    public function getReplaceProperty() {
        $aReplace = parent::getReplaceProperty();
        foreach ($this->getOpenTextField() as $iPosition => $sAttrValue) {
            $aReplace['#Freetextfield'.$iPosition.'#'] = $aReplace['#Freitextfeld'.$iPosition.'#'] = $sAttrValue['value'];
            $aReplace['#Description'.$iPosition.'#'] = $aReplace['#Bezeichnung'.$iPosition.'#'] = $sAttrValue['description'];
        }
        $aReplace['#PROPERTIES#'] = $this->getProperties();
        return $aReplace;
    }

    protected $oDetail = null;

    /**
     * @param $articleId
     * @return |null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    protected function getMainDetail($articleId) {
        if ($this->oDetail === null) {
            $builder = Shopware()->Models()->createQueryBuilder();
            $builder->select(array('details.id'))
                ->from('Shopware\Models\Article\Article', 'article')
                ->innerJoin('article.details', 'details')
                ->where('details.kind = 1 And article.id = ?1')
                ->setParameter(1, $articleId);
            $aDetail = $builder->getQuery()->getOneOrNullResult();
            $this->oDetail = $this->getDetailRepository()->find($aDetail['id']);
        }
        return $this->oDetail;
    }

    /** @var null|int $iVariantCount */
    protected $iVariantCount = null;

    /**
     * @return int|null
     */
    public function getRealVariantCount() {
        $mConfiguratorSet = $this->getLoadedProduct()->getConfiguratorSet();
        if(empty($mConfiguratorSet)){
            $iVariantCount = 0;
        } else {
            $oQueryBuilder = Shopware()->Models()->createQueryBuilder();
            $oQuery = $oQueryBuilder->select('details')->distinct('details.id')
                ->from('Shopware\Models\Article\Detail', 'details')
                ->leftJoin('details.configuratorOptions', 'configuratorOptions')
                ->leftJoin('details.prices', 'prices')
                ->where('details.articleId = ?1 AND configuratorOptions.id is not NULL AND prices.id is not NULL')
                ->setParameter(1, $this->oProduct->getId())
                ->getQuery();
            $iVariantCount = Shopware()->Models()->getQueryCount($oQuery);
        }
        return $iVariantCount;
    }

    /**
     * @return int|null
     */
    public function getVariantCount() {
        if ($this->iVariantCount === null) {
            $this->load();
            $iVariantCount = $this->getRealVariantCount();
            $this->iVariantCount = ($iVariantCount == 0) ? 1 : $iVariantCount;
        }
        return $this->iVariantCount;
    }

    /**
     * change current shop, so we can get product information in different languages
     * @param int $iLang
     * @return \ML_Shopware_Model_Product
     */
    public function setLang($iLang) {
        $oShop = $this->getShopRepository()->find($iLang);
        // Shopware 5.7 compatiblity
        if (version_compare(MLSHOPWAREVERSION, '5.7', '>=')) {
            Shopware()->Container()->set('shop', $oShop);
        } else {
            Shopware()->Bootstrap()->registerResource('Shop', $oShop);
        }
        return $this;
    }

    /** @var Shopware\Models\Article\Repository $oRepository */
    protected $oRepository = null;

    /**
     * Internal helper function to get access to the Article repository.
     *
     * @return Shopware\Models\Article\Repository
     */
    protected function getRepository() {
        if ($this->oRepository === null) {
            $this->oRepository = Shopware()->Models()->getRepository('Shopware\Models\Article\Article');
        }

        return $this->oRepository;
    }

    /** @var Shopware\Models\Article\Repository $oDetailRepository */
    protected $oDetailRepository = null;

    /**
     * Internal helper function to get access to the Article Detail repository.
     *
     * @return Shopware\Models\Article\Repository
     */
    protected function getDetailRepository() {
        if ($this->oDetailRepository === null) {
            $this->oDetailRepository = Shopware()->Models()->getRepository('Shopware\Models\Article\Detail');
        }
        return $this->oDetailRepository;
    }

    /** @var Shopware\Models\Customer\Group $oGroupRepository */
    protected $oGroupRepository = null;

    /**
     * Internal helper function to get access to the Customer Group repository.
     *
     * @return Shopware\Models\Customer\Group
     */
    protected function getGroupRepository() {
        if ($this->oGroupRepository === null) {
            $this->oGroupRepository = Shopware()->Models()->getRepository('\Shopware\Models\Customer\Group');
        }
        return $this->oGroupRepository;
    }

    /** @var Shopware\Models\Shop\Shop $oShopRepository */
    protected $oShopRepository = null;

    /**
     * Internal helper function to get access to the Shop repository.
     *
     * @return Shopware\Models\Shop\Shop
     */
    protected function getShopRepository() {
        if ($this->oShopRepository === null) {
            $this->oShopRepository = Shopware()->Models()->getRepository('Shopware\Models\Shop\Shop');
        }
        return $this->oShopRepository;
    }

    /** @var Shopware\Models\Shop\Shop $oConfiguratorOptionRepository */
    protected $oConfiguratorOptionRepository = null;

    /**
     * Internal helper function to get access to the Shop repository.
     *
     * @return Shopware\Models\Shop\Shop
     */
    protected function getConfiguratorOptionRepository() {
        if ($this->oConfiguratorOptionRepository === null) {
            $this->oConfiguratorOptionRepository = Shopware()->Models()->getRepository('Shopware\Models\Shop\Shop');
        }
        return $this->oConfiguratorOptionRepository;
    }

    /**
     * @param string $mAttributeCode
     * @return float|null
     * @throws Exception
     */
    public function getAttributeValue($mAttributeCode) {
        $aAttribute = explode('_', $mAttributeCode, 2);
        $sAttributeCode = $aAttribute[1];
        $mAttributeValue = null;

        try {
            // Overwrite getAttributeValue by Marketplace
            $moduleValue =  MLModule::gi()->shopProductGetAttributeValue($this, $mAttributeCode);

            // only if returned value is not null continue checking default behavior
            if ($moduleValue !== null) {
                return $moduleValue;
            }
        } catch (Exception $e) {
            // check default behavior
        }

        if ($aAttribute[0] === 'c') {
            $aOption = $this->getArticleDetail()->getConfiguratorOptions();
            if ($aOption->count() > 0) {
                foreach ($aOption as $oConfigur) {
                    if ($oConfigur->getGroup()->getId() === (int) $sAttributeCode) {
                        $mAttributeValue = $oConfigur->getName();
                    }
                }
            }
        } else if ($aAttribute[0] === 'p') {
            $aDesc = MLHelper::gi('model_product')->getTranslatedInfo($this->getLoadedProduct()->getId());
            $mAttributeValue = $aDesc[$sAttributeCode];
        } else if ($aAttribute[0] === 'pd') {
            $mAttributeValue = MLHelper::gi('model_product')->getProductDefaultFieldValue($this, $sAttributeCode);
        } else if ($aAttribute[0] === 'a') {
            $aAttribute = MLHelper::gi('model_product')->getFreeTextFieldValue($this->getArticleDetail(), array('name' => $sAttributeCode));
            $mAttributeValue = $aAttribute['value'];
        } else if ($aAttribute[0] === 'pp') {
            $mAttributeValue = MLHelper::gi('model_product')->getPropertyValuesFor($this->getLoadedProduct()->getId(), (int) $sAttributeCode);
        }
        else if ($aAttribute[0] === 'sp') {
            $mAttributeValue = $this->getLoadedProduct()->getSupplier()->getName();
        }
        return $mAttributeValue;
    }

    /**
     * @return \Doctrine\ORM\PersistentCollection|int|string|null
     */
    public function getManufacturer() {
        return $this->getModulField('manufacturer');
    }

    /**
     * @return \Doctrine\ORM\PersistentCollection|int|string|null
     */
    public function getManufacturerPartNumber() {
        return $this->getModulField('manufacturerpartnumber');
    }

    /**
     * @return \Doctrine\ORM\PersistentCollection|int|string|null
     */
    public function getEAN() {
        return $this->getModulField('ean');
    }

    /**
     * @return bool
     */
    public function isSingle() {
        $mConfiguratorSet = $this->getLoadedProduct()->getConfiguratorSet();
        return empty($mConfiguratorSet);
    }

    public function getBulletPointDefaultField() {
        return 'p_keywords';
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
            $result = 'pd_Ean';
        }

        if ($result === '') {
            $result = 'pd_Ean';
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
            $result = 'sp_Supplier';
        }

        if ($result === '') {
            $result = 'sp_Supplier';
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
            $result = 'pd_Suppliernumber';
        }

        if ($result === '') {
            $result = 'pd_Suppliernumber';
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
            $result = 'sp_Supplier';
        }

        if ($result === '') {
            $result = 'sp_Supplier';
        }
        return $result;
    }

    public function getSuggestedRetailPriceDefaultField()
    {
        return '';
    }
}
