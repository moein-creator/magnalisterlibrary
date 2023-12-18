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

use Redgecko\Magnalister\Controller\MagnalisterController;
use Shopware\Core\Checkout\Cart\Price\Struct\CartPrice;
use Shopware\Core\Checkout\Order\Aggregate\OrderTransaction\OrderTransactionStates;
use Shopware\Core\Checkout\Order\OrderStates;
use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Api\Context\SystemSource;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Sorting\FieldSorting;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\Country\Aggregate\CountryState\CountryStateEntity;
use Shopware\Core\System\Currency\CurrencyFormatter;
use Shopware\Core\System\SalesChannel\Context\SalesChannelContextService;
use Shopware\Core\System\Tax\Aggregate\TaxRule\TaxRuleEntity;
use Shopware\Core\System\Tax\TaxRuleType\IndividualStatesRuleTypeFilter;
use Shopware\Core\System\Tax\TaxRuleType\ZipCodeRangeRuleTypeFilter;
use Shopware\Core\System\Tax\TaxRuleType\ZipCodeRuleTypeFilter;

class ML_Shopware6_Model_Product extends ML_Shop_Model_Product_Abstract {

    /** @var ProductEntity */
    protected $oProduct;
    protected $sPriceRules = '';
    protected $blIsMarketplacePrice = false;

    /**
     * @var Context
     */
    protected $oShopwareContext;

    /**
     * @return $this|ML_Shop_Model_Product_Abstract
     * @throws Exception
     */
    protected function loadShopProduct() {
        if ($this->oProduct === null) {//not loaded
            $this->oProduct = false; //not null
            if ($this->get('parentid') == 0) {
                $oProduct = $this;
            } else {
                $oProduct = $this->getParent();
            }
            $this->prepareProductForMarketPlace();
            $sKey = MLDatabase::factory('config')->set('mpid', 0)->set('mkey', 'general.keytype')->get('value');
            if ($sKey == 'pID') {
                $oShopProduct = $this->getShopwareProduct($oProduct->get('productsid'));
            } else {
                $criteria = new Criteria();
                $oShopwareProduct = MLShopware6Alias::getRepository('product')->search($criteria->addFilter(new EqualsFilter('productNumber', $oProduct->get('productssku'))), $this->getShopwareContext())->first();
                $oShopProduct = is_object($oShopwareProduct) ? $oShopwareProduct : null;
            }
            $this->oProduct = $oShopProduct;
            $aData = $this->get('shopdata');
            if (!isset($aData['variationObject'])) {
                if (isset($aData['variation_id'])) {
                    $aData['variationObject'] = $this->getShopwareProduct($aData['variation_id']);
                } else {
                    $aData['variationObject'] = $this->oProduct;
                }
            }
            $this->prepareProductForMarketPlace();
            if ($this->get('parentid') != 0) {//is variant
                $this->loadByShopProduct($oShopProduct, $this->get('parentid'), $aData);
            }
        }
        return $this;
    }

    protected function prepareProductForMarketPlace() {
        try {
            $aConfig = MLModule::gi()->getConfig();
            $iLangId = $aConfig['lang'];
            $currency = MLShopware6Alias::getRepository('currency')
                ->search((new Criteria())->addFilter(new EqualsFilter('isoCode', (string)$aConfig['currency'])), Context::createDefaultContext())->first();
            if ($currency == null) {
                throw new Exception('Currency '.$aConfig['currency'].' doesn\'t exist in your shop ');
            }

            try {
                if (Uuid::isValid($iLangId)) {
                    $this->oShopwareContext = MLShopware6Alias::getContext($iLangId, $currency->getId());
                }
            } catch (\Exception $ex) {
            }
            if ($this->oShopwareContext === null) {
                $sCurrentController = MLRequest::gi()->get('controller');
                if ($sCurrentController !== null && strpos($sCurrentController, ':') !== false) {
                    MLHttp::gi()->redirect(MLHttp::gi()->getUrl(array(
                        'controller' => substr($sCurrentController, 0, strpos($sCurrentController, '_')).'_config_prepare'
                    )));
                }
            }


        } catch (Exception $oExc) {
            MLMessage::gi()->addDebug($oExc);
        }

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

            $criteriaIVariantCount = new Criteria();
            //Add variations attributes to the 'product.repository'
            $criteriaIVariantCount->addAssociations(['options']);
            //Sorting 'product.repository' via name of variation attributes. Variation attribute called 'options'
            $criteriaIVariantCount->addSorting(new FieldSorting('options.position', FieldSorting::ASCENDING));
            $criteriaIVariantCount->addSorting(new FieldSorting('options.name', FieldSorting::ASCENDING));
            $criteriaIVariantCount->addFilter(new EqualsFilter('product.parentId', $this->getMasterProductEntity()->getId()));
            $oVariantProductEntities = MLShopware6Alias::getRepository('product')->search($criteriaIVariantCount, $this->getShopwareContext())->getEntities();

            $aVariant['variationObject'] = $this->oProduct;
            if ($this->oProduct->getChildCount() == 0) {
                $this->addVariant(
                    MLProduct::factory()->loadByShopProduct($this->oProduct, $this->get('id'), $aVariant)
                );
            }
            foreach ($oVariantProductEntities as $oVariantProductEntity) {
                $aVariant = array();
                $aVariant['variation_id'] = $oVariantProductEntity->getId();
                $aVariant['variationObject'] = $oVariantProductEntity;
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
     * @param ProductEntity $mProduct
     * @param int $iParentId parent id in magnalister_products
     * @param null $mData
     * @return $this|mixed
     * @throws Exception
     */
    public function loadByShopProduct($mProduct, $iParentId = 0, $mData = null) {
        $this->oProduct = $mProduct;
        /* for product who have no reference number ,random reference is inserted because it will made problem when product key is Article number */
        $sKey = MLDatabase::factory('config')->set('mpid', 0)->set('mkey', 'general.keytype')->get('value');
        if ($sKey == 'pID') {
            $sProductKeyField = 'marketplaceidentid';
        } else {
            //here we use productssku instead of marketplaceidentsku, because if product was single product and now it has several variants we can find old product with productssku
            $sProductKeyField = 'productssku';
        }
        $this->aKeys = array($sProductKeyField, 'parentid');
        $this->set('parentid', $iParentId);
        $sMessage = array();
        if ($iParentId == 0) {

            //   die('test1');
            $this
                ->set('marketplaceidentid', $this->oProduct->getId())
                ->set('marketplaceidentsku', $this->oProduct->getProductNumber())
                ->set('productsid', $this->oProduct->getId())
                ->set('productssku', $this->oProduct->getProductNumber())
                ->set('shopdata', array())
                ->set('data', array('messages' => $sMessage))
                ->save()
                ->aKeys = array('id');
            $this->prepareProductForMarketPlace();
        } else {
            $oVariation = $mData['variationObject'];
            unset($mData['variationObject']);
            $this
                ->set('marketplaceidentid', $oVariation->getId().'_'.$this->oProduct->getId())
                ->set('marketplaceidentsku', $oVariation->getProductNumber())
                ->set("productsid", $oVariation->getId())
                ->set("productssku", $oVariation->getProductNumber())
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
     * @param $sSku
     * @return array
     * @todo to be test with product-id
     *  if there some problems, see getByMarketplaceSKU method of magneto_model_product
     */
    public function createModelProductByMarketplaceSku($sSku) {
        $aOut = array('master' => null, 'variant' => null);
        $oMyTable = MLProduct::factory();
        /* @var $oMyTable ML_Shopware_Model_Product */
        $oShopProduct = null;
        if (MLDatabase::factory('config')->set('mpid', 0)->set('mkey', 'general.keytype')->get('value') == 'pID') {
            $sIdent = 'marketplaceidentid';
            if (strpos($sSku, '_') !== false) {
                $aIds = explode("_", $sSku);
                if (is_numeric($aIds[1])) {
                    $oShopProductIDCriteria = new Criteria();
                    $oShopProduct = MLShopware6Alias::getRepository('product')->search($oShopProductIDCriteria->addFilter(new EqualsFilter('product.id', $aIds[1])), $this->getShopwareContext())->first();
                }
            } else if (is_numeric($sSku)) {
                $oShopProductSKUCriteria = new Criteria();
                $oShopProduct = MLShopware6Alias::getRepository('product')->search($oShopProductSKUCriteria->addFilter(new EqualsFilter('product.productNumber', $sSku)), $this->getShopwareContext())->first();
            }
        } else {
            $sIdent = 'marketplaceidentsku';
            $criteria = new Criteria();
            $oArticleDetail = MLShopware6Alias::getRepository('product')
                ->search($criteria->addFilter(new EqualsFilter('product.productNumber', $sSku)), $this->getShopwareContext())
                ->first();

            if ($oArticleDetail !== null) {
                if ($oArticleDetail->getParentId() === null) {
                    $oShopProduct = $oArticleDetail;
                } else {
                    $sProductId = $oArticleDetail->getParentId();
                    $oShopProduct = MLShopware6Alias::getRepository('product')
                        ->search(
                            (new Criteria())->addFilter(new EqualsFilter('product.id', $sProductId)),
                            $this->getShopwareContext())
                        ->first();
                }
            }
        }

        try {
            if ($oShopProduct != null && is_object($oShopProduct)) {

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
                //moein question: the $sSku is master product sku but here my varient has a diffrent sku and it never run the cindition

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
        if ($blMaster && substr($sSku, -7) === '_Master') {
            $sSku = substr($sSku, 0, strrpos($sSku, '_Master')).'M';
        }
        if (empty($sSku)) {
            return $this->set('id', 0);
        } else {
            $aCreated = $this->createModelProductByMarketplaceSku($sSku);
            $this->init(true);
            if ($aCreated[$blMaster ? 'master' : 'variant'] instanceof ML_Shop_Model_Product_Abstract) {
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
        $aDesc = $this->oProduct;
        if ($aDesc->getDescription() !== null) {
            return $aDesc->getMetaDescription();
        } else {
            $criteria = new Criteria();
            return MLShopware6Alias::getRepository('product')
                ->search($criteria->addFilter(new EqualsFilter('product.id', $aDesc->getId())), MLShopware6Alias::getContextByLanguageId())
                ->first()->getMetaDescription();
        }
    }

    /**
     * @return string|string[]|null
     */
    public function getDescription() {
        $this->load();
        $mDescription = MLShopware6Alias::getProductHelper()->getDescription($this->getCorrespondingProductEntity(), $this->getMasterProductEntity(), $this->getShopwareContext());
        if ($mDescription === null) {
            $mDescription = MLShopware6Alias::getProductHelper()->getDescription($this->getCorrespondingProductEntity(Context::createDefaultContext()), $this->getMasterProductEntity(), Context::createDefaultContext());
        }
        return $mDescription;
    }


    /**
     * Return a url to edit product in admin panel of Shopware 6
     * @return string
     * @throws Exception
     */
    public function getEditLink(): string {
        $this->load();
        return MLShopware6Alias::getHttpModel()->getAdminUrl().'#/sw/product/detail/'.$this->getCorrespondingProductEntity()->getId();
    }

    /**
     * return product front url
     * @return string
     */
    public function getFrontendLink() {
        $this->load();
        if ($this->get('parentid') == 0) {
            return MagnalisterController::getShopwareRequest()->server->get('APP_URL').'/detail/'.$this->getMasterProductEntity()->getId();
        } else {
            return MagnalisterController::getShopwareRequest()->server->get('APP_URL').'/detail/'.$this->get('productsid');
        }
    }


    /**
     * @param int $iX
     * @param int $iY
     * @return array|string
     */
    public function getImageUrl($iX = 40, $iY = 40) {
        $this->load();
        try {
            if ($this->getCorrespondingProductEntity()->getParentId() == null) {
                //Parent Product
                $ProductMediaId = $this->getCorrespondingProductEntity()->getCoverId();
            } else {
                //Variation
                if ($this->getCorrespondingProductEntity()->getCoverId() === null) {
                    //Replacing the Variation cover image by Parent product image if the  Variation cover image  is null
                    $ProductMediaId = $this->getShopwareProduct($this->getCorrespondingProductEntity()->getParentId())->getCoverId();
                } else {
                    // Variation cover image 
                    $ProductMediaId = $this->getCorrespondingProductEntity()->getCoverId();
                }
            }

            if ($ProductMediaId !== null) {
                $criteriaPM = new Criteria();
                $PM = MagnalisterController::getShopwareMyContainer()->get('product_media.repository')->search($criteriaPM->addFilter(new EqualsFilter('id', $ProductMediaId)), $this->getShopwareContext())->first();

                if ($PM !== null) {
                    $criteria = new Criteria();
                    $aImg = MagnalisterController::getShopwareMyContainer()->get('media.repository')->search($criteria->addFilter(new EqualsFilter('id', $PM->getMediaId())), $this->getShopwareContext())->first()->getUrl();
                    $sImagePath = $aImg;
                } else {
                    $sImagePath = false;
                }
            } else {
                $sImagePath = false;
            }
            if (($sImagePath) !== false) {
                return MLImage::gi()->resizeImage($sImagePath, 'products', $iX, $iY, true);
            } else {//get images by media
                //die('there is no imageurl C:\MAMP\htdocs\magnagit\v3\magnalister\Codepool\70_Shop\Shopware6\Model\Product.php::getImageUrl ');
                return '';
            }
        } catch (Exception $oEx) {
            return '';
        }
    }

    /**
     * @return array|null
     * @throws Exception
     */
    public function getImages() {
        if ($this->aImages === null) {
            $this->load();
            $aImages = array();
            $aImgs = array();
            $VariationaImgs = array();
            if ($this->get('parentid') == 0) {
                //Parent Product
                $productMediacriteria = new Criteria();
                $productMediacriteria->addFilter(new EqualsFilter('productId', $this->getCorrespondingProductEntity()->getId()));
                $productMediacriteria->addSorting(new FieldSorting('position', FieldSorting::ASCENDING));
                $productMedia = MagnalisterController::getShopwareMyContainer()->get('product_media.repository')->search($productMediacriteria, $this->getShopwareContext())->getEntities();
                $CoverImage = array();
                foreach ($productMedia as $media) {
                    if ($media->getId() == $this->getCorrespondingProductEntity()->getCoverId()) {
                        $criteriaCoverImage = new Criteria();
                        $CoverImage[] = MLShopware6Alias::getRepository('media')->search($criteriaCoverImage->addFilter(new EqualsFilter('id', $media->getMediaId())), $this->getShopwareContext())->first()->getUrl();
                    } else {
                        $criteria = new Criteria();
                        $aImgs[] = MLShopware6Alias::getRepository('media')->search($criteria->addFilter(new EqualsFilter('id', $media->getMediaId())), $this->getShopwareContext())->first()->getUrl();
                    }
                }
                //Collect all variations picture and merge it with master product
                $VariationListcriteria = new Criteria();
                $VariationList = MLShopware6Alias::getRepository('product')
                    ->search($VariationListcriteria->addFilter(new EqualsFilter('parentId', $this->getCorrespondingProductEntity()->getId())), $this->getShopwareContext())->getEntities();
                if ($VariationList->getElements()) {
                    foreach ($VariationList->getElements() as $value) {
                        $VariationMediacriteria = new Criteria();
                        $VariationMediacriteria->addSorting(new FieldSorting('position', FieldSorting::ASCENDING));
                        $VariationMedia = MLShopware6Alias::getRepository('product_media')->search($VariationMediacriteria->addFilter(new EqualsFilter('productId', $value->getId())), $this->getShopwareContext())->getEntities();

                        foreach ($VariationMedia as $media) {
                            //Find the cover image of variation and add it in the first place
                            foreach ($VariationMedia as $media2) {
                                $vmcriteria2 = new Criteria();
                                if ($value->getCoverId() == $media2->getId()) {
                                    $VariationaImgs[] = MagnalisterController::getShopwareMyContainer()->get('media.repository')->search($vmcriteria2->addFilter(new EqualsFilter('id', $media2->getMediaId())), $this->getShopwareContext())->first()->getUrl();
                                }
                            }
                            $vmcriteria = new Criteria();
                            if ($value->getCoverId() == $media->getId()) {
                                //escape the cover image because with add the cover image for variation on the foreach above
                            } else {
                                $VariationaImgs[] = MagnalisterController::getShopwareMyContainer()->get('media.repository')->search($vmcriteria->addFilter(new EqualsFilter('id', $media->getMediaId())), $this->getShopwareContext())->first()->getUrl();
                            }
                        }
                    }
                }
                $aImgs = array_merge($CoverImage, $aImgs, $VariationaImgs);
            } else {
                //variations Images
                $productMediacriteria = new Criteria();
                $productMediacriteria->addFilter(new EqualsFilter('productId', $this->getCorrespondingProductEntity()->getId()));
                $productMediacriteria->addSorting(new FieldSorting('position', FieldSorting::ASCENDING));
                $productMedia = MagnalisterController::getShopwareMyContainer()->get('product_media.repository')->search($productMediacriteria, $this->getShopwareContext())->getEntities();
                $CoverImage = array();
                //If variation images is not inherited and the variation images has been set
                if (!empty($productMedia->getElements())) {
                    foreach ($productMedia as $media) {
                        if ($media->getId() == $this->getCorrespondingProductEntity()->getCoverId()) {
                            $criteriaCoverImage = new Criteria();
                            $CoverImage[] = MagnalisterController::getShopwareMyContainer()->get('media.repository')->search($criteriaCoverImage->addFilter(new EqualsFilter('id', $media->getMediaId())), $this->getShopwareContext())->first()->getUrl();
                        }
                        $criteria = new Criteria();
                        $aImgs[] = MagnalisterController::getShopwareMyContainer()->get('media.repository')->search($criteria->addFilter(new EqualsFilter('id', $media->getMediaId())), $this->getShopwareContext())->first()->getUrl();
                    }
                } else {
                    //If variation images is inherited and the variation is empty then it will fill by parent product images
                    $productParentMediacriteria = new Criteria();
                    $productParentMediacriteria->addFilter(new EqualsFilter('productId', $this->getCorrespondingProductEntity()->getParentId()));
                    $productParentMediacriteria->addSorting(new FieldSorting('position', FieldSorting::ASCENDING));
                    $productParentMedia = MagnalisterController::getShopwareMyContainer()->get('product_media.repository')->search($productParentMediacriteria, $this->getShopwareContext())->getEntities();
                    foreach ($productParentMedia as $media) {
                        if ($media->getId() == $this->getCorrespondingProductEntity()->getCoverId()) {
                            $criteriaParentCoverImage = new Criteria();
                            $CoverImage[] = MagnalisterController::getShopwareMyContainer()->get('media.repository')->search($criteriaParentCoverImage->addFilter(new EqualsFilter('id', $media->getMediaId())), $this->getShopwareContext())->first()->getUrl();
                        }
                        $criteria = new Criteria();
                        $aImgs[] = MagnalisterController::getShopwareMyContainer()->get('media.repository')->search($criteria->addFilter(new EqualsFilter('id', $media->getMediaId())), $this->getShopwareContext())->first()->getUrl();
                    }
                }
                $aImgs = array_merge($CoverImage, $aImgs);
            }
            $this->aImages = array_unique($aImgs);
        }

        return $this->aImages;
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
        if ($this->getCorrespondingProductEntity()->getKeywords() !== null) {
            return $this->getCorrespondingProductEntity()->getKeywords();
        } else {
            $criteria = new Criteria();
            return MLShopware6Alias::getRepository('product')
                ->search($criteria->addFilter(new EqualsFilter('product.id', $this->getCorrespondingProductEntity()->getId())), MLShopware6Alias::getContextByLanguageId())
                ->first()->getKeywords();
        }
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
     * @var ProductEntity
     */
    protected $oRealProduct = null;

    /**
     * Check if magnalister product is a variation (MarketplaceIdentId contains "_") then it return ProductEntity object of variation
     * otherwise it returns ProductEntity object of master product($this->oProduct)
     * @return ProductEntity
     * @throws Exception
     */
    public function getCorrespondingProductEntity($oShopwareContext = null): ?ProductEntity {
        $oShopwareContext = $oShopwareContext === null ? $this->getShopwareContext() : $oShopwareContext;
        if ($this->oProduct === null) {
            $this->load();
        }

        if ($this->oRealProduct === null) {
            $sku = $this->get('MarketplaceIdentId');
            if (strpos($sku, '_') !== false) {
                //Checked if it is a variation then it fills the "$oProduct" with variation product object .
                $oProduct = $this->getShopwareProduct($this->get('productsid'), $oShopwareContext);
            } else {
                //Else it is a Master product then it fill the "$oProduct" with master product object ."$this->oProduct" is master product object.
                $oProduct = $this->oProduct;
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
        $attribute = '';
        $optionsentity = MagnalisterController::getShopwareMyContainer()->get('property_group_option.repository');
        if ($this->getCorrespondingProductEntity()->getOptionIds() !== null) {//add variation which have price and configuration option
            foreach ($this->getCorrespondingProductEntity()->getOptionIds() as $oOption) {

                $optionsName = $optionsentity->search(new Criteria(['id' => $oOption]), $this->getShopwareContext())->first();
                // @Masoud please check
                // Product has options and an attribute but the option themselves doesn't exist
                if (!is_object($optionsName)) {
                    continue;
                }
                if ($optionsName->getName() !== null) {
                    $attribute .= ' : '.$optionsName->getName();
                } else {
                    $optionsNameReplace = $optionsentity->search(new Criteria(['id' => $oOption]), Context::createDefaultContext())->first();
                    $attribute .= ' : '.$optionsNameReplace->getName();
                }

            }
        }
        if ($this->getCorrespondingProductEntity()->getName() == null && $this->getCorrespondingProductEntity()->getParentId() !== null) {
            $oProduct = $this->getShopwareProduct($this->getCorrespondingProductEntity()->getParentId());
            if ($oProduct->getName() !== NULL) {
                $sMasterProductName = $oProduct->getName().$attribute;
            } else {
                $oProduct3 = $this->getShopwareProduct($this->getCorrespondingProductEntity()->getParentId(), Context::createDefaultContext());
                $sMasterProductName = $oProduct3->getName().$attribute;
            }
        } elseif ($this->getCorrespondingProductEntity()->getName() == null && $this->getCorrespondingProductEntity()->getParentId() == null) {
            $oProduct2 = $this->getShopwareProduct($this->getCorrespondingProductEntity()->getId(), Context::createDefaultContext());
            $sMasterProductName = $oProduct2->getName().$attribute;
        } else {
            $sMasterProductName = $this->getCorrespondingProductEntity()->getName().$attribute;
        }

        return $sMasterProductName;
    }

    /**
     * @param bool $blGros
     * @param bool $blFormated
     * @return mixed
     * @throws Enlight_Exception
     */
    public function getShopPrice($blGros = true, $blFormated = false) {
        $this->load();
        $this->blIsMarketplacePrice = false;
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
        $this->blIsMarketplacePrice = true;
        $this->sPriceRules = $aConf['group'];
        $fTax = $aConf['tax'];
        $sPriceKind = $aConf['kind'];
        $fPriceFactor = (float)$aConf['factor'];
        $iPriceSignal = $aConf['signal'];
        $this->blDiscountMode = $aConf['special'];
        $mReturn = $this->getPrice($blGros, $blFormated, $sPriceKind, $fPriceFactor, $iPriceSignal, $fTax);
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
     * @throws Exception
     * @see \Shopware\Core\Content\Test\Product\SalesChannel\ProductPriceDefinitionBuilderTest
     */
    protected function getPrice($blGros, $blFormated, $sPriceKind = '', $fPriceFactor = 0.0, $iPriceSignal = null, $fTax = null) {
        $oProductEntity = $this->getCorrespondingProductEntity();
        $advancedPrice = array();
        if ($this->blIsMarketplacePrice) {
            $advancedPrice = $this->getAdvancedPrice($oProductEntity, $advancedPrice);
        }

        $price = $oProductEntity->getPrice();
        if ($price === null) {
            //Variations of a "Parent Product" don't have a TaxId and the Tax Id has been stored in "Parent Product". Getting the "Parent Product Id" from Variations via "$oProductEntity->getParentId()" and put it on the "product.repository" search section to get the "Parent Product" object with Tax Id.
            $CriteriaPrice = new Criteria();
            $oProductEntity = $this->getShopwareProduct($oProductEntity->getParentId());
        }

        if ($oProductEntity->getTaxId() === null) {
            //Variations of a "Parent Product" don't have a TaxId and the Tax Id has been stored in "Parent Product". Getting the "Parent Product Id" from Variations via "$oProductEntity->getParentId()" and put it on the "product.repository" search section to get the "Parent Product" object with Tax Id.
            $oProductTaxID = $this->getShopwareProduct($oProductEntity->getParentId());
            $oProductEntity->setTaxId($oProductTaxID->getTaxId());
        }

        $defaultSalesChannel = $this->getSalesChannel();

        $context = MagnalisterController::getSalesChannelContextFactory()
            ->create($oProductEntity->getId(), $defaultSalesChannel, [SalesChannelContextService::CURRENCY_ID => $this->getShopwareContext()->getCurrencyId()]);

        /** @var \Shopware\Models\Shop\Currency $CurrencyObject */
        $CurrencyObject = MLShopware6Alias::getRepository('currency')
            ->search((new Criteria())->addFilter(new EqualsFilter('isoCode', MLModule::gi()->getConfig('currency'))), Context::createDefaultContext())->first();

        if (version_compare(MLSHOPWAREVERSION, '6.4.0.0', '>=')) {
            if (!isset($advancedPrice['net'])) {
                $fBrutPrice = 0.00;
                // iterate through all prices per currency
                foreach ($oProductEntity->getPrice() as $value) {
                    if ($value->getCurrencyId() == $this->getShopwareContext()->getCurrencyId()) {
                        $fBrutPrice = $value->getGross();
                        break;
                    }
                }
                $fBrutPrice = $fBrutPrice * $CurrencyObject->getFactor();
            } else {

                $fBrutPrice = $advancedPrice['gross'] * $CurrencyObject->getFactor();
            }

        } else {
            if (!isset($advancedPrice['net'])) {
                $fBrutPrice = MagnalisterController::getProductPriceDefinitionBuilder()->build($oProductEntity, $context)->getPrice()->getPrice();
            } else {
                $fBrutPrice = $advancedPrice['gross'] * $CurrencyObject->getFactor();
            }
        }

        $context->setTaxState(CartPrice::TAX_STATE_NET);

        if (version_compare(MLSHOPWAREVERSION, '6.4.0.0', '>=')) {
            if (!isset($advancedPrice['net'])) {
                $fNetPrice = 0.00;
                // iterate through all prices per currency
                foreach ($oProductEntity->getPrice() as $value) {
                    if ($value->getCurrencyId() == $this->getShopwareContext()->getCurrencyId()) {
                        $fNetPrice = $value->getNet();
                    }
                }
                $fNetPrice = $fNetPrice * $CurrencyObject->getFactor();
            } else {
                $fNetPrice = $advancedPrice['net'] * $CurrencyObject->getFactor();
            }

        } else {
            if (!isset($advancedPrice['net'])) {
                $fNetPrice = MagnalisterController::getProductPriceDefinitionBuilder()->build($oProductEntity, $context)->getPrice()->getPrice();
            } else {
                $fNetPrice = $advancedPrice['net'] * $CurrencyObject->getFactor();
            }
        }
        //        Kint::dump(__FUNCTION__,$this->getShopwareContext()->getCurrencyId(), $fBrutPrice,$fNetPrice);
        $oPrice = MLPrice::factory();
        if ($fTax !== null) {
            $fBrutPrice = $oPrice->calcPercentages(null, $fNetPrice, $fTax);
        }

        // Marketplace Configuration - Addition or percentage price change#
        if ($sPriceKind === 'percent') {
            $fBrutPrice = $oPrice->calcPercentages(null, $fBrutPrice, $fPriceFactor);
            $fNetPrice = $oPrice->calcPercentages(null, $fNetPrice, $fPriceFactor);
        } elseif ($sPriceKind === 'addition') {
            $fBrutPrice = $fBrutPrice + $fPriceFactor;
            $fNetPrice = $fNetPrice + $fPriceFactor;
        }

        if ($iPriceSignal !== null) {
            //If price signal is single digit then just add price signal as last digit
            if (strlen((string)$iPriceSignal) == 1) {
                $fBrutPrice = (0.1 * (int)($fBrutPrice * 10)) + ((int)$iPriceSignal / 100);
                $fNetPrice = (0.1 * (int)($fNetPrice * 10)) + ((int)$iPriceSignal / 100);
            } else {
                $fBrutPrice = ((int)$fBrutPrice) + ((int)$iPriceSignal / 100);
                $fNetPrice = ((int)$fNetPrice) + ((int)$iPriceSignal / 100);
            }
        }

        $fPrice = round($blGros ? $fBrutPrice : $fNetPrice, 2);
        $fPrice = $this->priceAdjustment($fPrice);
        if ($blFormated) {
            $fPrice = MLShopware6Alias::getPriceModel()->format($fPrice, MLModul::gi()->getConfig('currency'), false);
        }
        return $fPrice;
    }

    public function priceAdjustment($fPrice) {
        return $fPrice;
    }

    public function getSalesChannel() {
        //Get sale channel from order configuration
        $iSalesChannelId = MLModule::gi()->getConfig('orderimport.shop');
        /** @var $oSalesChannel \Shopware\Core\System\SalesChannel\SalesChannelEntity|null */
        $oSalesChannel = null;
        if ($iSalesChannelId !== null) {
            $oCriteria = new Criteria(['id' => $iSalesChannelId]);
            $oCriteria->addAssociation('type');
            $oSalesChannel = MLShopware6Alias::getRepository('sales_channel')
                ->search($oCriteria, $this->getShopwareContext())->first();
            if ($oSalesChannel === null || $oSalesChannel->getType()->getIconName() !== 'default-building-shop') {
                $oCriteria = new Criteria();
                $oCriteria->addAssociation('type');
                $oCriteria->addSorting(new FieldSorting('active', 'DESC'));
                $oRepository = MLShopware6Alias::getRepository('sales_channel')
                    ->search($oCriteria, $this->getShopwareContext());
                $oSalesChannelStoreFront = null;
                foreach ($oRepository->getEntities() as $oItem) {
                    if ($oItem->getType()->getIconName() === 'default-building-shop') {//trying to search storefront sale channel
                        $oSalesChannelStoreFront = $oItem;
                        break;
                    }
                }
                $oSalesChannel = ($oSalesChannelStoreFront === null ? $oRepository->first() : $oSalesChannelStoreFront);
            }
        }
        return $oSalesChannel === null ? $iSalesChannelId : $oSalesChannel->getId();
    }

    /**
     * return master ProductEntity of Shopware
     * @return ProductEntity
     */
    public function getMasterProductEntity() {
        if ($this->oProduct === null) {
            $this->load();
        }
        return $this->oProduct;
    }


    /**
     * @inheritDoc
     */
    public function getTax($aAddressSets = null) {
        try {
            $oShopwareProduct = $this->getShopwareProduct($this->getCorrespondingProductEntity()->getId(), null, ['tax.rules.tax']);
            if ($oShopwareProduct->getTax() === null && $oShopwareProduct->getParentId() != null) {
                $oShopwareProduct = $this->getShopwareProduct($oShopwareProduct->getParentId(), null, ['tax.rules.tax']);
            }
            $fTax = $oShopwareProduct->getTax()->getTaxRate();

            if ($aAddressSets !== null) {
                $aAddressData = $aAddressSets['Shipping'];
                $countryCriteria = new Criteria();
                $countryCriteria->addFilter(new EqualsFilter('country.iso', $aAddressData['CountryCode']));
                $oCountry = MLShopware6Alias::getRepository('country')
                    ->search($countryCriteria, $this->getShopwareContext())->first();
                if (is_object($oCountry)) {//when country exist or when country doesn't have any area, we cannot use address to calculate tax, we retrun normal tax
                    foreach ($oShopwareProduct->getTax()->getRules()->filterByProperty('countryId', $oCountry->getId()) as $taxRuleEntity) {
                        /** @var $taxRuleEntity TaxRuleEntity */
                        $zipCode = $aAddressData['Postcode'] ?? 0;
                        switch ($taxRuleEntity->getType()->getTechnicalName()) {
                            case ZipCodeRangeRuleTypeFilter::TECHNICAL_NAME:
                                $toZipCode = isset($taxRuleEntity->getData()['toZipCode']) ? $taxRuleEntity->getData()['toZipCode'] : null;
                                $fromZipCode = isset($taxRuleEntity->getData()['fromZipCode']) ? $taxRuleEntity->getData()['fromZipCode'] : null;
                                if (
                                    ($fromZipCode !== null && $toZipCode !== null && $zipCode > $fromZipCode && $zipCode < $toZipCode)
                                    ||
                                    ($fromZipCode !== null && $toZipCode === null && $zipCode > $fromZipCode)
                                    ||
                                    ($fromZipCode === null && $toZipCode !== null && $zipCode < $toZipCode)
                                ) {
                                    $selectedTaxRule = $taxRuleEntity;
                                    break 2;
                                }
                                break;
                            case ZipCodeRuleTypeFilter::TECHNICAL_NAME:
                                if (isset($taxRuleEntity->getData()['zipCode']) && $taxRuleEntity->getData()['zipCode'] === $zipCode) {
                                    $selectedTaxRule = $taxRuleEntity;
                                    break 2;
                                }
                                break;
                            case IndividualStatesRuleTypeFilter::TECHNICAL_NAME:
                                if (array_key_exists('Suburb', $aAddressData) && !empty($aAddressData['Suburb'])) {
                                    $countryStateCriteria = new Criteria();
                                    /** @var CountryStateEntity $countryState */
                                    $countryState = MLShopware6Alias::getRepository('country_state')
                                        ->search($countryStateCriteria->addFilter(new EqualsFilter('name', $aAddressData['Suburb'])), $this->getShopwareContext())->first();

                                    if ($countryState !== null) {
                                        $stateId = $countryState->getId();
                                        $states = $taxRuleEntity->getData()['states'];
                                        if (\in_array($stateId, $states, true)) {
                                            $selectedTaxRule = $taxRuleEntity;
                                            break 2;
                                        }
                                    }
                                }
                                break;
                            default ://set default country rule
                                $selectedTaxRule = $taxRuleEntity;
                                break;
                        }
                    }
                    if (isset($selectedTaxRule) && $selectedTaxRule) {
                        $fTax = $selectedTaxRule->getTaxRate();
                    }
                }
            }

        } catch (\Exception $ex) {
            echo($ex->getMessage().$ex->getFile().$ex->getLine().$ex->getTraceAsString());
            $fTax = MLModule::gi()->getConfig('mwstfallback');
        }

        return $fTax;
    }

    /**
     * @return int
     */
    public function getTaxClassId() {
        if ($this->getCorrespondingProductEntity()->getTax() !== null) {//if it is single product
            return $this->getCorrespondingProductEntity()->getTax()->getId();
        } else if ($this->getMasterProductEntity()->getTax() !== null) {//if it is variant tax should be got from master product
            return $this->getMasterProductEntity()->getTax()->getId();
        }
        return null;
    }

    /**
     * @return int
     * @throws Exception
     */
    public function getStock() {
        if ($this->get('parentid') == '0') {
            return MLHelper::gi('model_product')->getTotalCount($this->getMasterProductEntity());
        } else {
            return $this->getCorrespondingProductEntity()->getAvailableStock();
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
        if (
            MLModule::gi()->getConfig('inventar.productstatus') == 'true' && !$this->isActive()
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

    public function getPrefixedVariationData() {
        $variationData = $this->getVariatonDataOptinalField(array('name', 'value', 'code', 'valueid'));

        foreach ($variationData as &$variation) {
            $variation['code'] = 'a_'.$variation['code'];
        }

        return $variationData;
    }

    /**
     * @param array $aFields
     * @return array
     * @throws Exception
     */
    public function getVariatonDataOptinalField($aFields = array()) {
        $oVariantProduct = $this->getShopwareProduct($this->getCorrespondingProductEntity()->getId());
        $optionsentity = MagnalisterController::getShopwareMyContainer()->get('property_group_option.repository');
        $groupentity = MagnalisterController::getShopwareMyContainer()->get('property_group.repository');

        $aOut = array();

        if ($oVariantProduct->getOptionIds() != null) {
            foreach ($oVariantProduct->getOptionIds() as $aAtribute) {
                $options = $optionsentity->search(new Criteria(['id' => $aAtribute]), $this->getShopwareContext())->first();
                // @Masoud please check
                // Product has options and an attribute but the option themselves doesn't exist
                if (!is_object($options)) {
                    continue;
                }
                $group = $groupentity->search(new Criteria(['id' => $options->getGroupId()]), $this->getShopwareContext())->first();
                $aData = array();
                if (in_array('code', $aFields)) {//an identifier for group of attributes , that used in Meinpaket at the moment
                    $aData['code'] = 'a_'.$group->getId();
                }
                if (in_array('valueid', $aFields)) {//an identifier for group of attributes , that used in Meinpaket at the moment
                    $aData['valueid'] = $options->getId();
                }
                if (in_array('name', $aFields)) {
                    $aData['name'] = $group->getName();
                }
                if (in_array('value', $aFields)) {
                    if ($options->getName() !== null) {
                        $aData['value'] = $options->getName();
                    } else {
                        $optionsReplace = $optionsentity->search(new Criteria(['id' => $aAtribute]), Context::createDefaultContext())->first();
                        $aData['value'] = $optionsReplace->getName();
                    }
                }
                $aOut[] = $aData;
            }
        }
        return $aOut;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function isActive() {
        $mStatus = null;
        try {
            $mStatus = $this->getCorrespondingProductEntity()->getActive();
        } catch (\Throwable $ex) {
            // If corresponding product object is a variant and active status is inherited from parent,
            // in some versions of Shopware 6 "$this->getCorrespondingProductEntity()->getActive()" will throw a PHP Error, because of that used \Throwable
            // to catch any PHP error, as fallback we use active status of parent product
        }
        if ($mStatus === null) {
            $mStatus = $this->getMasterProductEntity()->getActive();
        }
        return $mStatus;
    }

    /**
     * This function is not used for Shopware, by importing order there is Shopware core function to reduce the stock
     * @param int $iStock
     */
    public function setStock($iStock) {

    }

    /**
     * @return mixed|string
     */
    public function getCategoryPath() {
        $category = MagnalisterController::getShopwareMyContainer()->get('category.repository');
        $aInvalidIds = $this->getRootCategoriesIds();

        $sCatPath = '';
        if ($this->getMasterProductEntity()->getCategoryTree() != null) {
            foreach ($this->getMasterProductEntity()->getCategoryTree() as $oCat) {
                $cat = $category->search(new Criteria(['id' => $oCat]), $this->getShopwareContext())->first();
                $sInnerCat = '';
                //this condition will remove root category 
                if (!in_array($oCat, $aInvalidIds)) {
                    $sInnerCat = $cat->getName().'&nbsp;&gt;&nbsp;'.$sInnerCat;
                }
                $sCatPath .= $sInnerCat.'<br>';
            }
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
        if ($this->getMasterProductEntity()->getCategoryTree() != null) {
            foreach ($this->getMasterProductEntity()->getCategoryTree() as $oCat) {
                if (!in_array($oCat, $aFilterCats)) {
                    $aCategories[] = $oCat;
                }
            }
        }

        return $aCategories;
    }

    protected function getRootCategoriesIds() {
        $repository = MagnalisterController::getShopwareMyContainer()->get('category.repository');
        $criteriaCAT = new Criteria();
        $aResult = $repository->search($criteriaCAT->addFilter(new EqualsFilter('parentId', NULL)), $this->getShopwareContext())->getEntities();
        $invalidIds = array();
        foreach ($aResult as $id) {
            $invalidIds[] = $id->getId();
        }

        return $invalidIds;
    }

    /**
     * @param bool $blIncludeRootCats
     * @return array
     */
    public function getCategoryStructure($blIncludeRootCats = true) {
        $category = MagnalisterController::getShopwareMyContainer()->get('category.repository');
        $aCategories = array();
        $aInvalidIds = $aExistedCatId = $blIncludeRootCats ? array() : $this->getRootCategoriesIds();
        if (is_array($this->getMasterProductEntity()->getCategoryTree())) {
            foreach ($this->getMasterProductEntity()->getCategoryTree() as $oCat) {
                /* @var $oCat \Shopware\Models\Category\Category */
                $cat = $category->search(new Criteria(['id' => $oCat]), $this->getShopwareContext())->first();

                if (
                    in_array($cat->getId(), $aInvalidIds)
                    || in_array($cat->getId(), $aExistedCatId)
                ) {
                    break;
                }
                $sDescription = ($cat->getMetaDescription() === null) ? '' : $cat->getMetaDescription();
                $aCurrentCat = array(
                    'ID'          => $cat->getId(),
                    'Name'        => $cat->getName(),
                    'Description' => $sDescription,
                    'Status'      => true,
                );
                $aExistedCatId[] = $cat->getId();

                if ($cat->getParentId() !== null) {
                    $aCurrentCat['ParentID'] = $cat->getParentId();
                }
                $aCategories[] = $aCurrentCat;

            }
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
        //"Color" group id
        //763a00fca43a4dd8895036ea6b6e41c2            
        if (strpos($sName, 'a_') === 0) {
            if ($this->getCorrespondingProductEntity()->getOptionIds() !== null) {
                $sName = substr($sName, 2);
                foreach ($this->getCorrespondingProductEntity()->getOptionIds() as $value) {
                    $OptionEntitesCereteria = new Criteria();
                    $OptionEntites = MagnalisterController::getShopwareMyContainer()->get('property_group_option.repository')->search($OptionEntitesCereteria->addFilter(new EqualsFilter('id', $value)), $this->getShopwareContext())->first();

                    // @Masoud please check
                    // Product has options and an attribute but the option themselves doesn't exist
                    if (!is_object($OptionEntites)) {
                        continue;
                    }
                    if ($OptionEntites->getGroupId() == $sName) {
                        if ($OptionEntites->getName() !== NULL) {
                            return $OptionEntites->getName();
                        } else {
                            $DefaultLangCriteria = new Criteria();
                            $DefaultLangOptionEntites = MagnalisterController::getShopwareMyContainer()->get('property_group_option.repository')->search($DefaultLangCriteria->addFilter(new EqualsFilter('id', $value)), Context::createDefaultContext())->first();
                            return $DefaultLangOptionEntites->getName();
                        }
                    }
                }
            }
        } elseif (strpos($sName, 'p_') === 0) {
            if ($this->getCorrespondingProductEntity()->getPropertyIds() !== null) {
                $sName = substr($sName, 2);
                foreach ($this->getCorrespondingProductEntity()->getPropertyIds() as $value) {
                    $OptionPropertiesEntitesCereteria = new Criteria();
                    $OptionPropertiesEntites = MagnalisterController::getShopwareMyContainer()->get('property_group_option.repository')->search($OptionPropertiesEntitesCereteria->addFilter(new EqualsFilter('id', $value)), $this->getShopwareContext())->first();
                    if ($OptionPropertiesEntites->getGroupId() == $sName) {
                        if ($OptionPropertiesEntites->getName() !== NULL) {
                            return $OptionPropertiesEntites->getName();
                        } else {
                            $DefaultLangCriteria2 = new Criteria();
                            $DefaultLangOptionPropertiesEntites = MagnalisterController::getShopwareMyContainer()->get('property_group_option.repository')->search($DefaultLangCriteria2->addFilter(new EqualsFilter('id', $value)), Context::createDefaultContext())->first();
                            return $DefaultLangOptionPropertiesEntites->getName();
                        }
                    }
                }
            }
        } elseif (strpos($sName, 'c_') === 0) {
            $sName = substr($sName, 2);
            if (isset($this->getCorrespondingProductEntity()->getCustomFields()[$sName])) {
                $aAttribute['value'] = $this->getCorrespondingProductEntity()->getCustomFields()[$sName];
                return $aAttribute['value'];
            } else if (isset($this->getMasterProductEntity()->getCustomFields()[$sName])) {
                $aAttribute['value'] = $this->getMasterProductEntity()->getCustomFields()[$sName];
                return $aAttribute['value'];
            } else {
                return '';
            }
        } else {
            if (method_exists($this->getCorrespondingProductEntity(), 'get'.$sName)) {
                $mValue = $this->getCorrespondingProductEntity()->{'get'.$sName}();
                if ($mValue === null && $this->getCorrespondingProductEntity()->getParentId() !== null) {//if it is variant and value is inherited
                    $oMasterProduct = $this->getShopwareProduct($this->getCorrespondingProductEntity()->getParentId());
                    $mValue = $oMasterProduct->{'get'.$sName}();
                }

                if ($sName == 'ManufacturerId' && $this->getCorrespondingProductEntity()->getManufacturerId() !== null) {
                    $criteria = new Criteria();
                    $mValue1 = MagnalisterController::getShopwareMyContainer()->get('product_manufacturer.repository')->search($criteria->addFilter(new EqualsFilter('id', $mValue)), $this->getShopwareContext())->first();
                    if ($mValue1 !== null) {
                        if ($mValue1->getName() !== null) {
                            $mValue = $mValue1->getName();
                        } else {
                            $criteriaReplaceTranslation = new Criteria();
                            $mValue2 = MagnalisterController::getShopwareMyContainer()->get('product_manufacturer.repository')->search($criteriaReplaceTranslation->addFilter(new EqualsFilter('id', $mValue)), Context::createDefaultContext())->first();
                            $mValue = $mValue2->getName();
                        }
                    }
                } elseif ($sName == 'ManufacturerId' && $this->getCorrespondingProductEntity()->getManufacturerId() == null && $this->getCorrespondingProductEntity()->getParentId() !== null) {
                    $criteria = new Criteria();
                    $mValue1 = MagnalisterController::getShopwareMyContainer()->get('product_manufacturer.repository')->search($criteria->addFilter(new EqualsFilter('id', $mValue)), $this->getShopwareContext())->first();
                    if ($mValue1 !== null) {
                        if ($mValue1->getName() !== null) {
                            $mValue = $mValue1->getName();
                        } else {
                            $criteriaReplaceTranslation = new Criteria();
                            $mValue2 = MagnalisterController::getShopwareMyContainer()->get('product_manufacturer.repository')->search($criteriaReplaceTranslation->addFilter(new EqualsFilter('id', $mValue)), Context::createDefaultContext())->first();
                            $mValue = $mValue2->getName();
                        }
                    }
                }
            } else {
                MLMessage::gi()->addDebug('method get'.$sName.' does not exist in Article or ArticleDetails');
                return '';
            }
            return $mValue;
        }

        return '';
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getWeight() {
        $oDetail = $this->getCorrespondingProductEntity();
        $sWeight = (float)$oDetail->getWeight();
        if ($sWeight > 0) {
            return array(
                "Unit"  => "KG",
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
            $fPrice = ((float)$fPrice / (float)$aBasePrice['ShopwareDefaults']['$fPurchaseUnit']) * ((float)$aBasePrice['ShopwareDefaults']['$fReferenceUnit']);
            $sBasePrice = str_replace('&euro;', '€', MLHelper::gi('model_price')->getPriceByCurrency(($fPrice), null, true));
            return
                round($aBasePrice['ShopwareDefaults']['$fPurchaseUnit'], 2).' '
                .$aBasePrice['ShopwareDefaults'][$blLong ? '$sUnitName' : '$sUnit']
                .' ('.$sBasePrice.' */ '.round($aBasePrice['ShopwareDefaults']['$fReferenceUnit'], 2).' '.$aBasePrice['ShopwareDefaults'][$blLong ? '$sUnitName' : '$sUnit'].')';
        }
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getBasePrice() {
        $oDetail = $this->getCorrespondingProductEntity();
        if (!is_object($oDetail)) {
            return array();
        }
        if ($oDetail->getUnitId() !== null) {
            $BasePriceCereteria = new Criteria();
            $BasePrice = MagnalisterController::getShopwareMyContainer()->get('unit.repository')->search($BasePriceCereteria->addFilter(new EqualsFilter('id', $oDetail->getUnitId())), $this->getShopwareContext())->first();
            if ($BasePrice->getName() == null) {
                $BasePriceCereteriaReplace = new Criteria();
                $BasePriceReplace = MagnalisterController::getShopwareMyContainer()->get('unit.repository')->search($BasePriceCereteriaReplace->addFilter(new EqualsFilter('id', $oDetail->getUnitId())), Context::createDefaultContext())->first();
                $BasePrice = $BasePriceReplace;
            }

            try {
                $fReferenceUnit = $oDetail->getReferenceUnit();
                $fPurchaseUnit = $oDetail->getPurchaseUnit();
                $sUnitName = $BasePrice->getName();
                $sUnit = $BasePrice->getShortCode();
            } catch (Exception $oEx) {
                return array();
            }
        } else {
            return array();
        }
        if (empty($fReferenceUnit) && empty($fPurchaseUnit)) {//not configured base-price
            return array();
        }
        $fReferenceUnit = $fReferenceUnit <= 0 ? 1 : $fReferenceUnit;
        $fPurchaseUnit = $fPurchaseUnit <= 0 ? 1 : $fPurchaseUnit;
        return array(
            'ShopwareDefaults' => array(
                '$sUnit'          => $sUnit,
                '$sUnitName'      => $sUnitName,
                '$fReferenceUnit' => $fReferenceUnit,
                '$fPurchaseUnit'  => $fPurchaseUnit,
            ),
            'Unit'             => ((string)((float)$fReferenceUnit)).' '.$sUnitName,
            'UnitShort'        => ((string)((float)$fReferenceUnit)).' '.$sUnit,
            'Value'            => ((string)((float)$fPurchaseUnit / $fReferenceUnit)),
        );
    }

    /**
     * return html list, that contain property name and values
     * @return string
     */
    protected function getProperties() {
        try {
            $sPropertiesHtml = ' ';
            if ($this->getCorrespondingProductEntity()->getPropertyIds() !== null) {
                $sRowClass = 'odd';
                $sPropertiesHtml .= '<ul class="magna_properties_list">';
                foreach ($this->getCorrespondingProductEntity()->getPropertyIds() as $value) {
                    $OptionPropertiesEntitesCereteria = new Criteria();
                    $OptionPropertiesEntites = MagnalisterController::getShopwareMyContainer()->get('property_group_option.repository')->search($OptionPropertiesEntitesCereteria->addFilter(new EqualsFilter('id', $value)), $this->getShopwareContext())->first();
                    $groupentity = MagnalisterController::getShopwareMyContainer()->get('property_group.repository');
                    $group = $groupentity->search(new Criteria(['id' => $OptionPropertiesEntites->getGroupId()]), $this->getShopwareContext())->first();
                    if ($OptionPropertiesEntites->getName() !== NULL) {
                        $sPropertiesHtml .= '<li class="magna_property_item '.$sRowClass.'">'
                            .'<span class="magna_property_name">'.$group->getName()
                            .'</span>'
                            .'<span  class="magna_property_value">'.$OptionPropertiesEntites->getName()
                            .'</span>'
                            .'</li>';
                        $sRowClass = $sRowClass === 'odd' ? 'even' : 'odd';
                    } else {
                        $DefaultLangCriteria2 = new Criteria();
                        $DefaultLangOptionPropertiesEntites = MagnalisterController::getShopwareMyContainer()->get('property_group_option.repository')->search($DefaultLangCriteria2->addFilter(new EqualsFilter('id', $value)), Context::createDefaultContext())->first();

                        $DefaultGroupentity = MagnalisterController::getShopwareMyContainer()->get('property_group.repository');
                        if ($group->getName() == null) {
                            $DefaultGroup = $DefaultGroupentity->search(new Criteria(['id' => $DefaultLangOptionPropertiesEntites->getGroupId()]), Context::createDefaultContext())->first();
                        } else {
                            $DefaultGroup = $DefaultGroupentity->search(new Criteria(['id' => $DefaultLangOptionPropertiesEntites->getGroupId()]), $this->getShopwareContext())->first();
                        }

                        $sPropertiesHtml .= '<li class="magna_property_item '.$sRowClass.'">'
                            .'<span class="magna_property_name">'.$DefaultGroup->getName()
                            .'</span>'
                            .'<span  class="magna_property_value">'.$DefaultLangOptionPropertiesEntites->getName()
                            .'</span>'
                            .'</li>';
                        $sRowClass = $sRowClass === 'odd' ? 'even' : 'odd';
                    }
                }
                $sPropertiesHtml .= '</ul>';
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
    public function getCustomField() {
        $iLangId = $this->getLanguage();
        $Language = MLShopware6Alias::getRepository('language.repository')->search((new Criteria())->addFilter(new EqualsFilter('id', $iLangId)), Context::createDefaultContext())->first();
        $locale = MLShopware6Alias::getRepository('locale.repository')->search((new Criteria())->addFilter(new EqualsFilter('locale.id', $Language->getLocaleId())), Context::createDefaultContext())->first();
        $LangCode = $locale->getCode();
        $CustomeFildSetEntites = MagnalisterController::getShopwareMyContainer()
            ->get('custom_field.repository');

        $aAttributes = array();
        if ($this->getCorrespondingProductEntity()->getCustomFields() !== NULL) {
            foreach ($this->getCorrespondingProductEntity()->getCustomFields() as $key => $value) {
                $CustomeFildData = $CustomeFildSetEntites
                    ->search((new Criteria())->addFilter(new EqualsFilter('name', $key)), MLShopware6Alias::getContextByLanguageId($iLangId))
                    ->first();

                $Label = '';
                if ($CustomeFildData !== null) {
                    if (!empty($CustomeFildData->getConfig())) {
                        foreach ($CustomeFildData->getConfig()['label'] as $index => $value2) {
                            if ($LangCode === $index) {
                                $Label = $value2;
                            } elseif (!isset($CustomeFildData->getConfig()['label'][$LangCode])) {
                                $Label = $value2;
                            }
                        }
                        $iCustomFieldPosition = null;
                        if (isset($CustomeFildData->getConfig()['customFieldPosition'])) {
                            $iCustomFieldPosition = $CustomeFildData->getConfig()['customFieldPosition'];
                        }
                        $aAttributes[$CustomeFildData->getName()] = array('label' => $Label, 'customFieldPosition' => $iCustomFieldPosition, 'value' => $value);
                    }
                }
            }
        }

        return $aAttributes;
    }

    protected function getLanguage() {
        $aConfig = MLModul::gi()->getConfig();
        if (isset($aConfig['lang']) && $aConfig['lang'] != NULL && Uuid::isValid($aConfig['lang'])) {
            $iLangId = $aConfig['lang'];
        } else {
            $iLangId = Defaults::LANGUAGE_SYSTEM;
        }
        return $iLangId;
    }

    /**
     * @return array
     */
    public function getReplaceProperty() {
        $aReplace = parent::getReplaceProperty();
        foreach ($this->getCustomField() as $iPosition => $sAttrValue) {
            $aReplace['#Freetextfield'.$sAttrValue['customFieldPosition'].'#'] = $aReplace['#Freitextfeld'.$sAttrValue['customFieldPosition'].'#'] =
            $aReplace['#Customfield'.$sAttrValue['customFieldPosition'].'#'] = $aReplace['#Zusatzfeld'.$sAttrValue['customFieldPosition'].'#'] = $sAttrValue['value'];
            $aReplace['#Description'.$sAttrValue['customFieldPosition'].'#'] = $aReplace['#Bezeichnung'.$sAttrValue['customFieldPosition'].'#'] = $sAttrValue['label'];
        }
        $aReplace['#PROPERTIES#'] = $this->getProperties();
        return $aReplace;
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
     * @return int|null
     */
    public function getRealVariantCount() {
        $mMasterProduct = $this->getMasterProductEntity();
        if (empty($mMasterProduct)) {
            $iVariantCount = 0;
        } else {
            $criteria = new Criteria();
            $criteria->addFilter(new EqualsFilter('product.parentId', $mMasterProduct->getId()));
            $iVariantCount = MLShopware6Alias::getRepository('product')->search($criteria, $this->getShopwareContext())->count();
        }
        return $iVariantCount;
    }

    /**
     * change current shop, so we can get product information in different languages
     * @param int $iLang
     * @return \ML_Shopware6_Model_Product
     */
    public function setLang($iLang) {

        $this->oShopwareContext = MLShopware6Alias::getContextByLanguageId($iLang);
        return $this;
    }

    /**
     * @param string $mAttributeCode
     * @return float|null
     * @throws Exception
     */
    public function getAttributeValue($mAttributeCode) {
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

        $mAttributeValue = $this->getProductField($mAttributeCode);
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
     * @return int|string|null
     */
    public function getEAN() {
        return $this->getModulField('ean');
    }

    /**
     * @return bool
     */
    public function isSingle() {
        $this->load();
        $mConfiguratorSet = $this->getCorrespondingProductEntity()->getChildCount();
        // echo print_m($this->getArticleDetail());
        if ((int)$mConfiguratorSet == 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return Context
     */
    protected function getShopwareContext() {
        if ($this->oShopwareContext === null) {
            $this->prepareProductForMarketPlace();
        }
        if ($this->oShopwareContext === null) {//If no marketplace is loaded
            $this->oShopwareContext = Context::createDefaultContext();
        }
        return $this->oShopwareContext;

    }

    /**
     * @param string $sName
     * @return string|null
     */
    public function get($sName) {
        $mValue = parent::get($sName);
        if (
            strtolower($sName) === 'marketplaceidentsku' && !empty($mValue) &&
            parent::get('ParentID') === '0' &&
            MLModule::gi()->getConfig('shopware6.master.sku.migration.options') === '1'

        ) {
            $iLastIndex = strlen((string)$mValue) - 1;
            if ($iLastIndex > 0 && $mValue[$iLastIndex] === 'M') {
                return substr($mValue, 0, $iLastIndex).'_Master';
            }
        }
        return $mValue;

    }

    /**
     * @param ProductEntity|null $oProductEntity
     * @param array $advancedPrice
     * @return array
     */
    protected function getAdvancedPrice(?ProductEntity $oProductEntity, array $advancedPrice): array {
        $CriteriaProductPrice = new Criteria();
        $CriteriaProductPrice->addFilter(new EqualsFilter('productId', $oProductEntity->getId()));
        $CriteriaProductPrice->addFilter(new EqualsFilter('quantityStart', '1'));
        $CriteriaProductPrice->addFilter(new EqualsFilter('ruleId', $this->sPriceRules));
        $ProductPrice = MagnalisterController::getShopwareMyContainer()->get('product_price.repository')->search($CriteriaProductPrice, $this->getShopwareContext())->first();
        if ($ProductPrice != null) {
            foreach ($ProductPrice->getPrice() as $value) {

                $advancedPrice['net'] = $value->getNet();
                $advancedPrice['gross'] = $value->getGross();
                /*list price functionality
                 * $value->getListPrice()->getNet();
                 * $value->getListPrice()->getGross();
                */
            }
        } elseif ($oProductEntity->getParentId() !== null) {
            $ParentProductAdvancedPrice = $this->getShopwareProduct($oProductEntity->getParentId());
            $CriteriaProductPrice = new Criteria();
            $CriteriaProductPrice->addFilter(new EqualsFilter('productId', $ParentProductAdvancedPrice->getId()));
            $CriteriaProductPrice->addFilter(new EqualsFilter('quantityStart', '1'));
            $CriteriaProductPrice->addFilter(new EqualsFilter('ruleId', $this->sPriceRules));
            $ProductPrice = MagnalisterController::getShopwareMyContainer()->get('product_price.repository')->search($CriteriaProductPrice, $this->getShopwareContext())->first();
            if ($ProductPrice != null) {
                foreach ($ProductPrice->getPrice() as $value) {
                    $advancedPrice['net'] = $value->getNet();
                    $advancedPrice['gross'] = $value->getGross();
                    /*list price functionality
                     * $value->getListPrice()->getNet();
                     * $value->getListPrice()->getGross();
                    */
                }
            }
        }

        return $advancedPrice;
    }

    /**
     * @param $iProductID
     * @param null $oContext
     * @param array $aAssociations
     * @return ProductEntity
     */
    protected function getShopwareProduct($iProductID, $oContext = null, array $aAssociations = []) {
        $oContext = $oContext !== null ? $oContext : $this->getShopwareContext();
        $oShopProductIDCriteria = new Criteria();
        if ($aAssociations !== []) {
            $oShopProductIDCriteria->addAssociations($aAssociations);
        }
        $oShopProduct = MLShopware6Alias::getRepository('product')
            ->search($oShopProductIDCriteria->addFilter(new EqualsFilter('product.id', $iProductID)), $oContext)->first();
        return $oShopProduct;
    }

}
