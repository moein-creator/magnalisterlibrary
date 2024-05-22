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

include_once(M_DIR_LIBRARY . 'request/shopware/ShopwareProduct.php');
include_once(DIR_MAGNALISTER_HELPER . 'APIHelper.php');
include_once(M_DIR_LIBRARY . 'request/shopware/ShopwareTax.php');
include_once(M_DIR_LIBRARY . 'request/shopware/ShopwareCountry.php');
include_once(M_DIR_LIBRARY . 'request/shopware/ShopwareRule.php');
include_once(M_DIR_LIBRARY . 'request/shopware/ShopwareCurrency.php');
include_once(M_DIR_LIBRARY . 'request/shopware/ShopwareUnit.php');


use Doctrine\ORM\PersistentCollection;
use library\request\shopware\ShopwareProduct;

class ML_Shopwarecloud_Model_Product extends ML_Shop_Model_Product_Abstract {

    /**
     *
     * {
     *  "id": "11dc680240b04f469ccba354cbf0b967",
     *  "type": "product",
     *  "attributes": {
     *      "versionId": "0fa91ce3e96a4bc2be4bd9ce752c3425",
     *      "parentId": null,
     *      "parentVersionId": null,
     *      "manufacturerId": "cc1c20c365d34cfb88bfab3c3e81d350",
     *      "productManufacturerVersionId": null,
     *      "unitId": null,
     *      "taxId": "8744b86f0c9b4a0ba4d4a761d457206a",
     *      "coverId": "e648140ff1f04177b40128ac6b649d8a",
     *      "productMediaVersionId": null,
     *      "deliveryTimeId": null,
     *      "featureSetId": null,
     *      "canonicalProductId": null,
     *      "cmsPageId": null,
     *      "cmsPageVersionId": null,
     *      "price": [
     *          {
     *          "currencyId": "b7d2554b0ce847cd82f3ac9bd1c0dfca",
     *          "net": 798.3199999999999,
     *          "gross": 950.0,
     *          "linked": true,
     *          "listPrice": null,
     *          "percentage": null,
     *          "regulationPrice": null,
     *          "extensions": []
     *          }
     *      ],
     *      "productNumber": "SWDEMO10002",
     *      "stock": 10,
     *      "restockTime": null,
     *      "autoIncrement": 1,
     *      "active": true,
     *      "availableStock": 10,
     *      "available": true,
     *      "isCloseout": false,
     *      "variation": [],
     *      "displayGroup": "9cc83811832d4b1f1593acb545f36678",
     *      "configuratorGroupConfig": null,
     *      "mainVariantId": null,
     *      "variantRestrictions": null,
     *      "manufacturerNumber": null,
     *      "ean": null,
     *      "purchaseSteps": 1,
     *      "maxPurchase": null,
     *      "minPurchase": 1,
     *      "purchaseUnit": 1.0,
     *      "referenceUnit": 1.0,
     *      "shippingFree": true,
     *      "purchasePrices": [
     *          {
     *          "currencyId": "b7d2554b0ce847cd82f3ac9bd1c0dfca",
     *          "net": 0.0,
     *          "gross": 0.0,
     *          "linked": true,
     *          "listPrice": null,
     *          "percentage": null,
     *          "regulationPrice": null,
     *          "extensions": []
     *          }
     *      ],
     *      "markAsTopseller": null,
     *      "weight": 45.0,
     *      "width": 590.0,
     *      "height": 600.0,
     *      "length": 840.0,
     *      "releaseDate": "2021-09-30T21:38:39.987+00:00",
     *      "ratingAverage": null,
     *      "categoryTree": [
     *          "07771ffc09bf4435b91853ef47bedded",
     *          "251448b91bc742de85643f5fccd89051"
     *          ],
     *      "propertyIds": null,
     *      "optionIds": null,
     *      "streamIds": null,
     *      "tagIds": null,
     *      "categoryIds": [
     *          "251448b91bc742de85643f5fccd89051"
     *          ],
     *      "childCount": 0,
     *      "customFieldSetSelectionActive": null,
     *      "sales": 0,
     *      "metaDescription": null,
     *      "name": "Main product with advanced pricess",
     *      "keywords": null,
     *      "description": "Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor...",
     *      "metaTitle": null,
     *      "packUnit": null,
     *      "packUnitPlural": null,
     *      "customFields": null,
     *      "slotConfig": null,
     *      "customSearchKeywords": null,
     *      "cheapestPrice": {
     *      "hasRange": false,
     *      "variantId": "11dc680240b04f469ccba354cbf0b967",
     *      "parentId": "11dc680240b04f469ccba354cbf0b967",
     *      "ruleId": null,
     *      "purchase": 1.0,
     *      "reference": 1.0,
     *      "unitId": null,
     *      "price": [
     *          {
     *          "currencyId": "b7d2554b0ce847cd82f3ac9bd1c0dfca",
     *          "net": 798.3199999999999,
     *          "gross": 950.0,
     *          "linked": true,
     *          "listPrice": null,
     *          "percentage": null,
     *          "regulationPrice": null,
     *          "extensions": []
     *          }
     *      ],
     *      "extensions": []
     *      },
     *  "createdAt": "2021-09-30T21:38:39.997+00:00",
     *  "updatedAt": "2022-06-23T12:38:11.731+00:00",
     *  "translated": {
     *      "metaDescription": null,
     *      "name": "Main product with advanced pricess",
     *      "keywords": null,
     *      "description": "Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.",
     *      "metaTitle": null,
     *      "packUnit": null,
     *      "packUnitPlural": null,
     *      "customFields": {},
     *      "slotConfig": null,
     *      "customSearchKeywords": null
     *      },
     *  "apiAlias": null
     *  },
     *  "links": {
     *      "self": "http://shopware6.test/public/api/product/11dc680240b04f469ccba354cbf0b967"
     *  },
     *  "relationships": {
     *      "parent": {
     *          "data": null,
     *          "links": {
     *          "related": "http://shopware6.test/public/api/product/11dc680240b04f469ccba354cbf0b967/parent"
     *          }
     *      },
     *      "children": {
     *          "data": [],
     *          "links": {
     *          "related": "http://shopware6.test/public/api/product/11dc680240b04f469ccba354cbf0b967/children"
     *          }
     *      },
     *      "deliveryTime": {
     *          "data": null,
     *          "links": {
     *          "related": "http://shopware6.test/public/api/product/11dc680240b04f469ccba354cbf0b967/delivery-time"
     *          }
     *      },
     *      "tax": {
     *          "data": {
     *          "type": "tax",
     *          "id": "8744b86f0c9b4a0ba4d4a761d457206a"
     *          },
     *      "links": {
     *          "related": "http://shopware6.test/public/api/product/11dc680240b04f469ccba354cbf0b967/tax"
     *          }
     *      },
     *      "manufacturer": {
     *          "data": null,
     *          "links": {
     *          "related": "http://shopware6.test/public/api/product/11dc680240b04f469ccba354cbf0b967/manufacturer"
     *          }
     *       },
     *      "unit": {
     *          "data": null,
     *          "links": {
     *          "related": "http://shopware6.test/public/api/product/11dc680240b04f469ccba354cbf0b967/unit"
     *          }
     *      },
     *      "cover": {
     *          "data": null,
     *          "links": {
     *          "related": "http://shopware6.test/public/api/product/11dc680240b04f469ccba354cbf0b967/cover"
     *          }
     *      },
     *      "featureSet": {
     *          "data": null,
     *          "links": {
     *          "related": "http://shopware6.test/public/api/product/11dc680240b04f469ccba354cbf0b967/feature-set"
     *          }
     *      },
     *      "cmsPage": {
     *          "data": null,
     *          "links": {
     *          "related": "http://shopware6.test/public/api/product/11dc680240b04f469ccba354cbf0b967/cms-page"
     *          }
     *      },
     *      "canonicalProduct": {
     *          "data": null,
     *          "links": {
     *          "related": "http://shopware6.test/public/api/product/11dc680240b04f469ccba354cbf0b967/canonical-product"
     *          }
     *      },
     *      "prices": {
     *          "data": [],
     *          "links": {
     *          "related": "http://shopware6.test/public/api/product/11dc680240b04f469ccba354cbf0b967/prices"
     *          }
     *      },
     *      "media": {
     *          "data": [],
     *          "links": {
     *          "related": "http://shopware6.test/public/api/product/11dc680240b04f469ccba354cbf0b967/media"
     *          }
     *      },
     *      "crossSellings": {
     *          "data": [],
     *          "links": {
     *          "related": "http://shopware6.test/public/api/product/11dc680240b04f469ccba354cbf0b967/cross-sellings"
     *          }
     *      },
     *      "crossSellingAssignedProducts": {
     *          "data": [],
     *          "links": {
     *          "related": "http://shopware6.test/public/api/product/11dc680240b04f469ccba354cbf0b967/cross-selling-assigned-products"
     *          }
     *      },
     *      "configuratorSettings": {
     *          "data": [],
     *          "links": {
     *          "related": "http://shopware6.test/public/api/product/11dc680240b04f469ccba354cbf0b967/configurator-settings"
     *          }
     *      },
     *      "visibilities": {
     *          "data": [],
     *          "links": {
     *          "related": "http://shopware6.test/public/api/product/11dc680240b04f469ccba354cbf0b967/visibilities"
     *          }
     *      },
     *      "searchKeywords": {
     *          "data": [],
     *          "links": {
     *          "related": "http://shopware6.test/public/api/product/11dc680240b04f469ccba354cbf0b967/search-keywords"
     *          }
     *      },
     *      "productReviews": {
     *          "data": [],
     *          "links": {
     *          "related": "http://shopware6.test/public/api/product/11dc680240b04f469ccba354cbf0b967/product-reviews"
     *          }
     *      },
     *      "mainCategories": {
     *          "data": [],
     *          "links": {
     *          "related": "http://shopware6.test/public/api/product/11dc680240b04f469ccba354cbf0b967/main-categories"
     *          }
     *      },
     *      "seoUrls": {
     *          "data": [],
     *          "links": {
     *          "related": "http://shopware6.test/public/api/product/11dc680240b04f469ccba354cbf0b967/seo-urls"
     *          }
     *      },
     *      "orderLineItems": {
     *          "data": [],
     *          "links": {
     *          "related": "http://shopware6.test/public/api/product/11dc680240b04f469ccba354cbf0b967/order-line-items"
     *          }
     *      },
     *      "wishlists": {
     *          "data": [],
     *          "links": {
     *          "related": "http://shopware6.test/public/api/product/11dc680240b04f469ccba354cbf0b967/wishlists"
     *          }
     *      },
     *      "options": {
     *          "data": [],
     *          "links": {
     *          "related": "http://shopware6.test/public/api/product/11dc680240b04f469ccba354cbf0b967/options"
     *          }
     *      },
     *      "properties": {
     *          "data": [],
     *          "links": {
     *          "related": "http://shopware6.test/public/api/product/11dc680240b04f469ccba354cbf0b967/properties"
     *          }
     *      },
     *      "categories": {
     *          "data": [],
     *          "links": {
     *          "related": "http://shopware6.test/public/api/product/11dc680240b04f469ccba354cbf0b967/categories"
     *          }
     *      },
     *      "streams": {
     *          "data": [],
     *          "links": {
     *          "related": "http://shopware6.test/public/api/product/11dc680240b04f469ccba354cbf0b967/streams"
     *          }
     *      },
     *      "categoriesRo": {
     *          "data": [],
     *          "links": {
     *          "related": "http://shopware6.test/public/api/product/11dc680240b04f469ccba354cbf0b967/categories-ro"
     *          }
     *      },
     *      "tags": {
     *          "data": [],
     *          "links": {
     *          "related": "http://shopware6.test/public/api/product/11dc680240b04f469ccba354cbf0b967/tags"
     *          }
     *      },
     *      "customFieldSets": {
     *          "data": [],
     *          "links": {
     *          "related": "http://shopware6.test/public/api/product/11dc680240b04f469ccba354cbf0b967/custom-field-sets"
     *          }
     *      },
     *      "translations": {
     *          "data": [],
     *          "links": {
     *          "related": "http://shopware6.test/public/api/product/11dc680240b04f469ccba354cbf0b967/translations"
     *          }
     *      },
     *      "extensions": {
     *          "data": {
     *          "type": "extension",
     *          "id": "11dc680240b04f469ccba354cbf0b967"
     *          }
     *      }
     *  },
     * "meta": null
     *  }
     *
     * @var object Showpare Cloud Product
     */
    protected $oProduct;
    protected $oRealProduct;
    protected $sPriceRules = '';
    protected $blIsMarketplacePrice = false;
    protected $shopwareProductRequest;
    protected $apiHelper;
    public static $ShopId = null;
    private $customFieldCache = array();

    /**
     * @var array Shopware cloud Product data
     */
    protected $aProduct = null;

    /**
     * @var array Shopware Cloud category
     */
    protected $aCategory = null;

    /**
     * @var Context
     */
    protected $oShopwareCloudLanguageId;

    protected $currencyByIsoCodeCache = array();
    /**
     * @var CurrencyEntity|null
     */
    protected $defaultCurrency = null;


    /**
     * @return Context
     */
    public function getShopwareCloudLanguageId() {
        if ($this->oShopwareCloudLanguageId === null) {
            $this->prepareProductForMarketPlace();
        }
        if ($this->oShopwareCloudLanguageId === null) {//If no marketplace is loaded
            $this->oShopwareCloudLanguageId = MLShopwareCloudTranslationHelper::gi()->getLanguage(true);
        }
        return $this->oShopwareCloudLanguageId;

    }

    protected function prepareProductForMarketPlace()
    {
        try {
            $aConfig = MLModule::gi()->getConfig();
            $iLangId = $aConfig['lang'];
            /*$currency = MLShopware6Alias::getRepository('currency')
                ->search((new Criteria())->addFilter(new EqualsFilter('isoCode', (string)$aConfig['currency'])), Context::createDefaultContext())->first();
            if ($currency == null) {
                throw new Exception('Currency '.$aConfig['currency'].' doesn\'t exist in your shop ');
            }*/
            try {
                if ($iLangId) {
                    $this->oShopwareCloudLanguageId = $iLangId;
                }


            } catch (Exception $ex) {
            }
            if ($this->oShopwareCloudLanguageId === null) {
                $sCurrentController = MLRequest::gi()->get('controller');
                if ($sCurrentController !== null && strpos($sCurrentController, ':') !== false) {
                    MLHttp::gi()->redirect(MLHttp::gi()->getUrl(array(
                        'controller' => substr($sCurrentController, 0, strpos($sCurrentController, '_')) . '_config_prepare'
                    )));
                }
            }


        } catch (Exception $oExc) {
            MLMessage::gi()->addDebug($oExc);
        }

    }

    /**
     * @return $this|ML_Shop_Model_Product_Abstract
     * @throws Exception
     */
    protected function loadShopProduct() {
        if ($this->oProduct === null) {//product is not loaded
            $this->oProduct = false; //not null
            if ((int)$this->get('parentid') === 0) {
                $oProduct = $this;
            } else {
                $oProduct = $this->getParent();
            }
            $this->prepareProductForMarketPlace();
            $oProductData = MLShopwareCloudAlias::getProductDataModel();
            $this->aProduct = $oProductData->set('ShopwareProductID', $oProduct->get('ProductsId'))->get('ShopwareProductData');
            $this->oProduct = (object)$this->aProduct;
        }

        return $this;
    }

    /**
     * @return object
     */
    public function getCorrespondingProductEntity() {
        if ($this->oProduct === null) {
            $this->load();
        }
        if ($this->oRealProduct === null) {
            $sku = $this->get('MarketplaceIdentId');
            if (strpos($sku, '_') !== false) {
                $oProductData = MLShopwareCloudAlias::getProductDataModel();
                $this->aProduct = $oProductData->set('ShopwareProductID', $this->get('productsid'))->get('ShopwareProductData');
                $oProduct = (object)$this->aProduct;
            } else {
                //Else it is a Master product then it fil the "$oProduct" with master product object ."$this->oProduct" is master product object.
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
     * We do not need this function to be implemented because we imported the variations with
     * loadByShopVariants function
     *
     * @return $this
     * @throws MLAbstract_Exception
     */
    protected function loadShopVariants() {

        //$this->addVariant(MLProduct::factory()->setKeys(array('productsid'))->set('productsid', '6485f4fb6d024ce6bcc35b0621a86390'));
        $iVariationCount = $this->getVariantCount();

        if ($iVariationCount > MLSetting::gi()->get('iMaxVariantCount')) {

            $this
                ->set('data', array(
                        'messages' => array(
                            MLI18n::gi()->get(
                                'Productlist_ProductMessage_sErrorToManyVariants', array(
                                    'variantCount' => $iVariationCount, 'maxVariantCount' => MLSetting::gi()->get('iMaxVariantCount')
                                )
                            )
                        )
                    )
                )
                ->save();
            MLMessage::gi()->addObjectMessage($this, MLI18n::gi()->get('Productlist_ProductMessage_sErrorToManyVariants', array('variantCount' => $iVariationCount, 'maxVariantCount' => MLSetting::gi()->get('iMaxVariantCount'))));
        } else {
            $mMasterProduct = $this->getMasterProductEntity();
            $ParentId= (int)$this->get('id');
            $oVariantProductEntities =  MLDatabase::getDbInstance()
                ->fetchArray("SELECT ID
                    FROM magnalister_products                                         
                    WHERE parentId = ('".$ParentId."')");
            $aVariant['variationObject'] = $this->oProduct;
            // adding Master product as variation product
            if ($mMasterProduct->attributes['childCount'] == 0) {
                foreach ($oVariantProductEntities as $oVariantProductEntity) {
                    $this->addVariant(
                        MLProduct::factory()->set('Id', $oVariantProductEntity['ID'])
                    );
                }
            } else {
                //adding variations products
                foreach ($oVariantProductEntities as $oVariantProductEntity) {
                    $aVariant = array();
                    $aVariant['variation_id'] = $oVariantProductEntity['ID'];
                    $aVariant['variationObject'] = $oVariantProductEntity;
                    if (isset($aVariant['variation_id'])) {
                        $this->addVariant(
                            MLProduct::factory()->set('ID', $aVariant['variation_id'])
                        );
                    }
                }
            }
        }
        return $this;
    }

    /**
     * @param ProductEntity $mProduct
     * @param int $iParentId parent id in magnalister_products
     * @param null $aProduct
     * @return $this|mixed
     * @throws Exception
     *
     */
    public function loadByShopProduct($aProduct, $iParentId = 0, $mData = null) {
        $this->apiHelper = new APIHelper();
        $sShopId = MLShopwareCloudAlias::getShopHelper()->getShopId();
        $this->shopwareProductRequest = new ShopwareProduct($sShopId);
        $this->aProduct = $aProduct;
        $this->oProduct = (object)$aProduct;
        $sKey = MLDatabase::factory('config')->set('mpid', 0)->set('mkey', 'general.keytype')->get('value');

        $this->aKeys = array('marketplaceidentid');
        $this->set('parentid', $iParentId)->aKeys[] = 'parentid';
        $sSku = $this->oProduct->attributes['productNumber'];
        $sPrice = null;
        if (isset($this->oProduct->attributes['price'])) {
            foreach ($this->oProduct->attributes['price'] as $price) {
                $sPrice = $price['gross'];
                break;
            }
        }
        $iQuantity = isset($this->oProduct->attributes['stock']) ? $this->oProduct->attributes['stock'] : null;
        $sManufacturerName = $this->getManufacturerName($sShopId);

        if ($this->oProduct->attributes['active'] === '') {
            $this->oProduct->attributes['active'] = NULL;
        } elseif ($this->oProduct->attributes['active'] === false) {
            $this->oProduct->attributes['active'] = 0;
        } elseif ($this->oProduct->attributes['active'] === true) {
            $this->oProduct->attributes['active'] = 1;
        }

        if ($iParentId == 0) {
            $this
                ->set('marketplaceidentid', $this->oProduct->id)
                ->set('marketplaceidentsku', empty($sSku) ? '__ERROR__'.$this->oProduct->id : $sSku)
                ->set('productsid', $this->oProduct->id)
                ->set('productssku', empty($sSku) ? '__ERROR__'.$this->oProduct->id : $sSku)
                ->set('shopdata', array())
                ->set('data', array())
                ->set('ShopwareQuantity', $iQuantity)
                ->set('ShopwarePrice', $sPrice)
                ->set('ShopwareManufacturer', $sManufacturerName)
                ->set('ShopwareManufacturerId', $this->oProduct->attributes['manufacturerId'])
                ->set('ShopwareManufacturerNumber',$this->oProduct->attributes['manufacturerNumber'])
                ->set('ShopwareEAN',$this->oProduct->attributes['ean'])
                ->set('ShopwareCreatedAt', MLShopwareCloudHelper::getStorageDateTime($this->oProduct->attributes['createdAt']))
                ->set('ShopwareUpdateDate', MLShopwareCloudHelper::getStorageDateTime($this->oProduct->attributes['updatedAt']))
                ->set('ShopwareActive',$this->oProduct->attributes['active'])
                ->save()
                ->aKeys = array('id');

            MLDatabase::factory('ShopwareCloudProductData')
                ->set('ShopwareProductID', $this->oProduct->id)
                ->set('ShopwareProductData', $aProduct)
                ->save();

            if ($sKey !== 'pID' && empty($sSku)) {
                MLMessage::gi()->addObjectMessage(
                    $this, MLI18n::gi()->data('Productlist_Cell_Product_NoSku')
                );
            }
            $this->prepareProductForMarketPlace();
            $this->loadByShopVariants();
        } else {
            if (!isset($mData['variation_id'])) {
                throw new Exception('not key set for product variation');
            }
            $sVariantSku = $this->oProduct->attributes['productNumber'];
            $parentId = isset($this->oProduct->attributes['parentId']) ? $this->oProduct->attributes['parentId'] : $this->oProduct->id;
            if (!is_double($sPrice) && $sPrice !== null) {
                $sPrice = null;
            }
            $this
                ->set('marketplaceidentid', $this->oProduct->id.'_'.$parentId)
                ->set('marketplaceidentsku', empty($sVariantSku) ? '__ERROR__'.$this->oProduct->id : $sVariantSku)
                ->set('productsid', $this->oProduct->id)
                ->set('productssku', empty($sVariantSku) ? '__ERROR__'.$this->oProduct->id : $sVariantSku)
                ->set('shopdata', $mData)
                ->set('data', array())
                ->set('ShopwareQuantity', $iQuantity)
                ->set('ShopwarePrice', $sPrice)
                ->set('ShopwareManufacturer', $sManufacturerName)
                ->set('ShopwareManufacturerId', $this->oProduct->attributes['manufacturerId'])
                ->set('ShopwareManufacturerNumber',$this->oProduct->attributes['manufacturerNumber'])
                ->set('ShopwareEAN',$this->oProduct->attributes['ean'])
                ->set('ShopwareCreatedAt', MLShopwareCloudHelper::getStorageDateTime($this->oProduct->attributes['createdAt']))
                ->set('ShopwareUpdateDate', MLShopwareCloudHelper::getStorageDateTime($this->oProduct->attributes['updatedAt']))
                ->set('ShopwareActive', $this->oProduct->attributes['active']);


            if ($this->exists()) {
                $this->aKeys = array('id');
                $this->save();
            } else {

                $this->save()->aKeys = array('id');
            }
            MLDatabase::factory('ShopwareCloudProductData')
                ->set('ShopwareProductID', $this->oProduct->id)
                ->set('ShopwareProductData', $aProduct)
                ->save();


        }
        $this->storeTranslations();
        $this->storeProductPrice();
        $this->populateProductImages($this->oProduct->id, $this->oProduct->attributes['coverId'], $this->oProduct->relationships['media']['links']['related'], $sShopId);
        return $this;
    }

    private function getManufacturerName($sShopId) {
        $sManufacturerName = null;
        $sProductManufacturerUrl = $this->oProduct->relationships['manufacturer']['links']['related'];

        $oManufacturers = $this->shopwareProductRequest->getShopwareProductManufacturers($sProductManufacturerUrl);
        foreach ($oManufacturers->getData() as $oManufacturer) {
            if ($oManufacturer->getAttributes()->getName() !== null) {
                $sManufacturerName = $oManufacturer->getAttributes()->getName();
                break;
            }
        }

        return $sManufacturerName;
    }


    private function loadByShopVariants() {
        $iVariationCount = $this->oProduct->attributes['childCount'];
        $masterProductId = $this->oProduct->id;
        if ($iVariationCount > MLSetting::gi()->get('iMaxVariantCount')) {
            $this
                ->set('data', array('messages' => array(MLI18n::gi()->get('Productlist_ProductMessage_sErrorToManyVariants', array('variantCount' => $iVariationCount, 'maxVariantCount' => MLSetting::gi()->get('iMaxVariantCount'))))))
                ->save();
            MLMessage::gi()->addObjectMessage($this, MLI18n::gi()->get('Productlist_ProductMessage_sErrorToManyVariants', array('variantCount' => $iVariationCount, 'maxVariantCount' => MLSetting::gi()->get('iMaxVariantCount'))));
        } else {
            $aVariant = array();
            if (isset($iVariationCount) && $iVariationCount === 0) {//add one variation for single product
                $aVariant['variation_id'] = $masterProductId;
                $aVariant['info'][] = array();
                if (isset($aVariant['variation_id'])) {
                    $this->addVariant(
                        MLShopwareCloudAlias::getProductModel()->loadByShopProduct($this->aProduct, $this->get('id'), $aVariant)
                    );
                }
            } else if (isset($iVariationCount) && $iVariationCount > 0) {//add variation which have price and configuration option
                $variations = $this->shopwareProductRequest->getShopwareProducts('/api/product/' . $masterProductId . '/children', 'GET', [], true);

                foreach ($variations['data'] as $variation) {
                    $aVariant['variation_id'] = $variation['id'];
                    $aVariant['info'][] = array('name' => $variation['attributes']['name'], 'value' => $variation['attributes']);
                    if (isset($aVariant['variation_id'])) {
                        $this->addVariant(
                            MLShopwareCloudAlias::getProductModel()->loadByShopProduct($variation, $this->get('id'), $aVariant)
                        );
                    }
                }
            }
        }

        return $this;
    }

    private function storeTranslations() {
        $masterProductId = $this->oProduct->id;
        $translations = $this->shopwareProductRequest->getShopwareProductTranslations('/api/product/' . $masterProductId . '/translations');
        foreach ($translations->getData() as $translation) {
                $translationAttributes = $translation->getAttributes();
                MLDatabase::factory('ShopwareCloudProductTranslation')
                    ->set('ShopwareProductID', $translationAttributes->getProductId())
                    ->set('ShopwareLanguageID', $translationAttributes->getLanguageId())
                    ->set('ShopwareName', $translationAttributes->getName())
                    ->set('ShopwareMetaTitle',  $translationAttributes->getMetaTitle())
                    ->set('ShopwareDescription',  $translationAttributes->getDescription())
                    ->set('ShopwareMetaDescription',  $translationAttributes->getMetaDescription())
                    ->set('ShopwareKeywords',  $translationAttributes->getKeywords())
                    ->set('ShopwarePackUnit',  $translationAttributes->getPackUnit())
                    ->set('ShopwarePackUnitPlural',  $translationAttributes->getPackUnitPlural())
                    ->set('ShopwareCustomSearchKeywords',  $translationAttributes->getCustomSearchKeywords())
                    ->set('ShopwareSlotConfig',  $translationAttributes->getSlotConfig())
                    ->set('ShopwareCustomFields',  $translationAttributes->getCustomFields())
                    ->save();
        }
    }

    protected function populateProductImages($productId, $sCoverId, $sMediaUrl, $sShopId) {
        $limitPerPage = 200;
        $filters = array(
            'page' => 1,
            'limit' => $limitPerPage,
            'total-count-mode' => 1
        );
        $iLimitationOfIteration = ceil($this->getTotalCountOfProductMedia() / $limitPerPage);
        while ($filters['page'] <= $iLimitationOfIteration) {
            $preparedProductMediaPath = $this->apiHelper->prepareFilters($filters, 'GET', $sMediaUrl);
            $oProductMedias = $this->shopwareProductRequest->getShopwareProductMedia($preparedProductMediaPath);
            foreach ($oProductMedias->getData() as $oProductMedia) {
                $isCoverImage = $sCoverId === $oProductMedia->getId() ? 1 : 0;
                $imagePosition = $oProductMedia->getAttributes()->getPosition();
                $sProductMediaId = $oProductMedia->getId();

                $oMedias = $this->shopwareProductRequest->getShopwareMedia($oProductMedia->getRelationships()->getMedia()->getLinks()->getRelated());

                foreach ($oMedias->getData() as $oMedia) {
                    MLDatabase::factory('ShopwareCloudProductImages')
                        ->set('ShopwareProductID', $productId)
                        ->set('ShopwareProductMediaID', $sProductMediaId)
                        ->set('ShopwareMediaID', $oMedia->getId())
                        ->set('ShopwareImagePosition', $imagePosition)
                        ->set('ShopwareIsCoverImage', $isCoverImage)
                        ->set('ShopwareImageURL', $oMedia->getAttributes()->getUrl())
                        ->save();
                }
            }
            $filters['page']++;
        }
    }

    protected function storeProductPrice() {
        $masterProductId = $this->oProduct->id;
        $sShopId = MLShopwareCloudAlias::getShopHelper()->getShopId();
        $this->shopwareProductRequest = new ShopwareProduct($sShopId);
        $productPriceList = $this->shopwareProductRequest->getShopwareProductPrice('/api/product/'.$masterProductId.'/prices' , 'GET', [], true);
        foreach ($productPriceList['data'] as $productPriceItem) {
            MLDatabase::factory('ShopwareCloudProductPrice')
                ->set('ShopwareProductPriceID', $productPriceItem['id'])
                ->set('ShopwareRuleID', $productPriceItem['attributes']['ruleId'])
                ->set('ShopwareProductID',  $productPriceItem['attributes']['productId'])
                ->set('ShopwarePrice', json_encode($productPriceItem['attributes']['price']))
                ->set('ShopwareQuantityStart',  $productPriceItem['attributes']['quantityStart'])
                ->set('ShopwareQuantityEnd',  $productPriceItem['attributes']['quantityEnd'])
                ->set('ShopwareCustomFields',  $productPriceItem['attributes']['customFields'])
                ->set('ShopwareCreatedAt', MLShopwareCloudHelper::getStorageDateTime($productPriceItem['attributes']['createdAt']))
                ->set('ShopwareUpdateDate', MLShopwareCloudHelper::getStorageDateTime($productPriceItem['attributes']['updatedAt']))
                ->save();
        }
    }

    protected function getTotalCountOfProductMedia() {
        $filters = [
            'productId' => [
                'type' => 'equals',
                'values' => $this->oProduct->id
            ],
        ];
        $preparedFilters = $this->apiHelper->prepareFilters($filters, 'POST');
        return (int)MLShopwareCloudAlias::getProductHelper()->getShopwareProductEntityListCount('/api/search/product-media', 'getShopwareProductMedia', $preparedFilters);
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
     * @return string
     */
    public function getShortDescription() {
        $this->load();
        $oRealProduct = $this->getCorrespondingProductEntity();
        $sDescription = MLShopwareCloudTranslationHelper::gi()->getShopwareCloudProductTranslation($oRealProduct, 'ShopwareMetaDescription', $this->getShopwareCloudLanguageId());
        if (!isset($sDescription)) {
            $sDescription = MLShopwareCloudTranslationHelper::gi()->getShopwareCloudProductTranslation($oRealProduct, 'ShopwareMetaDescription', $this->getShopwareCloudLanguageId());
        }

        return $sDescription;
    }

    /**
     * @return string|string[]|null
     */
    public function getDescription() {
        $this->load();
        $oRealProduct = $this->getCorrespondingProductEntity();
        $sDescription = MLShopwareCloudTranslationHelper::gi()->getShopwareCloudProductTranslation($oRealProduct, 'ShopwareDescription', $this->getShopwareCloudLanguageId());
        if (!isset($sDescription)) {
            $sDescription = MLShopwareCloudTranslationHelper::gi()->getShopwareCloudProductTranslation($oRealProduct, 'ShopwareDescription', $this->getShopwareCloudLanguageId());
        }

        return $sDescription;
    }

    /**
     * Return a url to edit product in admin panel of Shopware 6
     * @return string
     * @throws Exception
     */
    public function getEditLink(): string {
        self::$ShopId = $_GET['shop-url'];
        if (!isset($_GET['shop-url']) && isset($_GET['shop-id'])) {
            self::$ShopId = MagnaDB::gi()->fetchOne("SELECT `Shopware_ShopUrl` FROM `Customer` WHERE `Shopware_ShopId` = '". $_GET['shop-id'] . "'");
        }
        $hostname = self::$ShopId;
        $path = '/admin#/sw/product/detail/';
        $this->load();
        $oRealProduct = $this->getCorrespondingProductEntity();
        $id = $oRealProduct->id;

        return $hostname.$path.$id.'/base';
    }

    /**
     * return product front url
     * @return string
     */
    public function getFrontendLink() {
        $this->load();
        $oRealProduct = $this->getCorrespondingProductEntity();
        $hostname = MLHttp::gi()->getBaseUrl().'/';
        $path ='/detail/';
        $id = $oRealProduct->id;

        return $hostname.$path.$id;
    }

    /**
     * @param int $iX
     * @param int $iY
     * @return array|string
     */
    public function getImageUrl($iX = 40, $iY = 40) {
        $result = '';
        $this->load();
        $oRealProduct = $this->getCorrespondingProductEntity();
        try {
            if ($oRealProduct->attributes['parentId'] == null) {
                //Parent Product
                $ProductMediaId = $oRealProduct->attributes['coverId'];
            } else {
                //Variation

                if ($oRealProduct->attributes['coverId'] == null) {
                    //Replacing the Variation cover image by Parent product image if the  Variation cover image  is null
                    $ProductMediaId = $this->getParent()->load()->oProduct->attributes['coverId'];
                } else {
                    // Variation cover image
                    $ProductMediaId = $oRealProduct->attributes['coverId'];
                }
            }

            if ($ProductMediaId !== null) {
                $aImagePath = $this->getImageByProductMediaId($ProductMediaId);
                $sImagePath = isset($aImagePath['ShopwareImageURL']) ? $aImagePath['ShopwareImageURL']: null;
                if (isset($sImagePath)) {
                    $result = MLImage::gi()->resizeImage($sImagePath, 'products', $iX, $iY, true);
                }
            }

            return $result;

        } catch (Exception $oEx) {
            return '';
        }
    }

    private function getImageByProductMediaId($sProductMediaId) {
        $oProductImagesModel =  MLDatabase::factory('ShopwareCloudProductImages');
        $aProductImages = MLDatabase::getDbInstance()
            ->fetchRow("SELECT ShopwareImageURL
                    FROM ".$oProductImagesModel->getTableName()."
                    WHERE ShopwareProductMediaID = '".$sProductMediaId."'");

        return $aProductImages;
    }

    /**
     * @return array|null
     * @throws Exception
     */
    public function getImages() {
        $this->load();
        $oRealProduct = $this->getCorrespondingProductEntity();
        $aVariantProductImages = array();
        if ($this->get('parentid') == 0) {
            //get master product images
            $aMasterProductImages = $this->getProductImageUrls($oRealProduct->id);
            //get variant images
            if ($oRealProduct->attributes['childCount'] > 0) {
                $aVariationIds = MLDatabase::getDbInstance()
                    ->fetchArray("SELECT ProductsId
                    FROM ".$this->getTableName()."
                    WHERE ParentId = '".$this->get('id')."'", true);
                $aVariantProductImages = $this->getProductImageUrls($aVariationIds);
            }
            $aImages = array_merge($aMasterProductImages, $aVariantProductImages);
        } else {
            // get variant images
            $aImages = $this->getProductImageUrls($oRealProduct->id);
            // if variant images are empty get master product images
            if (empty($aImages)) {
                $aImages = $this->getProductImageUrls($this->getParent()->get('ProductsId'));
            }
        }

        return $aImages;
    }

    private function getProductImageUrls($productIds) {
        if (is_array($productIds)) {
            $sVariantIds = implode("','", $productIds);
        } else {
            $sVariantIds = $productIds;
        }
        $oProductImagesModel =  MLDatabase::factory('ShopwareCloudProductImages');
        $aProductImages = MLDatabase::getDbInstance()
            ->fetchArray("SELECT ShopwareImageURL
                    FROM ".$oProductImagesModel->getTableName()."
                    WHERE ShopwareProductID IN ('".$sVariantIds."')
                    ORDER BY ShopwareImagePosition ASC, ShopwareIsCoverImage DESC", true);

        return $aProductImages;
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
        $oRealProduct = $this->getCorrespondingProductEntity();
        $sDescription = MLShopwareCloudTranslationHelper::gi()->getShopwareCloudProductTranslation($oRealProduct, 'ShopwareKeywords');
        if (!isset($sDescription)) {
            $sDescription = MLShopwareCloudTranslationHelper::gi()->getShopwareCloudProductTranslation($oRealProduct, 'ShopwareKeywords');
        }
        return $sDescription;
    }

    /**
     * @param $sFieldName
     * @param bool $blGeneral
     * @return PersistentCollection|int|string|null
     * @throws Exception
     */
    public function getModulField($sFieldName, $blGeneral = false, $blMultiValue = false) {
        $this->load();
        if ($blGeneral) {
            $sField = MLDatabase::factory('config')->set('mpid', 0)->set('mkey', $sFieldName)->get('value');
        } else {
            $sField = MLModule::gi()->getConfig($sFieldName);
        }
        $sField = empty($sField) ? $sFieldName : $sField;

        return $this->getProductField($sField);
    }

    /**
     * @return string
     */
    public function getName()
    {
        $this->load();
        $attribute = '';
        $oRealProduct = $this->getCorrespondingProductEntity();
        $name = MLShopwareCloudTranslationHelper::gi()->getShopwareCloudProductTranslation($oRealProduct, 'ShopwareName', $this->getShopwareCloudLanguageId());
        if ((int)$this->get('parentid') !== 0 && $this->oProduct->attributes['childCount'] > 1) {
            if ($this->getCorrespondingProductEntity()->attributes['optionIds'] != null) {
                foreach ($this->getCorrespondingProductEntity()->attributes['optionIds'] as $aAtribute) {
                    $options = $this->getPropertiesGroupOptionById($aAtribute);
                    if (isset($options['ShopwareName'])) {
                        $attribute .= ' : ' . $options['ShopwareName'];
                    }else{
                        $options = $this->getPropertiesGroupOptionById($aAtribute, true);
                        $attribute .= ' : ' . $options['ShopwareName'];
                    }
                }
            }
            if (MLShopwareCloudTranslationHelper::gi()->getProductVariationIsinheritance($oRealProduct, 'ShopwareName', $this->getShopwareCloudLanguageId())) {
                $name = MLShopwareCloudTranslationHelper::gi()->getShopwareCloudProductTranslation($oRealProduct, 'ShopwareName', $this->getShopwareCloudLanguageId()) . $attribute;
            } else {
                $name = MLShopwareCloudTranslationHelper::gi()->getShopwareCloudProductTranslation($oRealProduct, 'ShopwareName', $this->getShopwareCloudLanguageId());
            }
            //$name = MLShopwareCloudTranslationHelper::gi()->getShopwareCloudProductTranslation($oRealProduct, 'ShopwareName',  $this->getShopwareCloudLanguageId()) . $attribute;
        }

        return $name;
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
     * @param ML_Shop_Model_Price_Interface $oPrice
     * @param bool $blGros
     * @param bool $blFormated
     * @return mixed
     *
     * @throws Exception
     */
    public function getSuggestedMarketplacePrice(ML_Shop_Model_Price_Interface $oPrice, $blGros = true, $blFormated = false) {
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

        $price = $oProductEntity->attributes['price'];

        if ($price === null) {
            //Variations of a "Parent Product" don't have a TaxId and the Tax Id has been stored in "Parent Product". Getting the "Parent Product Id" from Variations via "$oProductEntity->getParentId()" and put it on the "product.repository" search section to get the "Parent Product" object with Tax Id.
            //$oProductEntity = $this->getParent()->load()->oProduct->attributes;
            //$oProductEntity = MLProduct::factory()->getByMarketplaceSKU($oProductEntity->attributes['productNumber'], true)->oProduct->attribute;//master product
            $oProductData = MLShopwareCloudAlias::getProductDataModel();
            $oProductData = (object)$oProductData->set('ShopwareProductID',$oProductEntity->attributes['parentId'])->get('ShopwareProductData');
            $oProductEntity =$oProductData;
        }

            $CurrencyObject = $this->getShopwareCurrencyByIsoCode(MLModule::gi()->getConfig('currency'));

            if (!isset($advancedPrice['net'])) {
                $fBrutPrice = 0.00;
                foreach ($oProductEntity->attributes['price'] as $value) {
                    if ($value['currencyId'] == $CurrencyObject['ShopwareCurrencyID']) {
                        $fBrutPrice = $value['gross'];
                        break;
                    }
                    elseif($value['currencyId'] == $this->getDefaultCurrency()['ShopwareCurrencyID']){
                        $fBrutPrice = $value['gross'];
                        $fBrutPrice = $fBrutPrice * $CurrencyObject['ShopwareFactor'];
                    }
                }

            } else {

                $fBrutPrice = $advancedPrice['gross'] *  $CurrencyObject['ShopwareFactor'];
            }

            if (!isset($advancedPrice['net'])) {
                $fNetPrice = 0.00;
                foreach ($oProductEntity->attributes['price'] as $value) {
                    if ($value['currencyId'] == $CurrencyObject['ShopwareCurrencyID']) {
                        $fNetPrice = $value['net'];
                    }elseif($value['currencyId'] == $this->getDefaultCurrency()['ShopwareCurrencyID']){
                        $fNetPrice = $value['net'];
                        $fNetPrice = $fNetPrice * $CurrencyObject['ShopwareFactor'];
                    }
                }
            } else {
                $fNetPrice = $advancedPrice['net'] * $CurrencyObject['ShopwareFactor'];
            }
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

            $fPrice = MLShopwareCloudAlias::getPriceModel()->format($fPrice, MLModule::gi()->getConfig('currency'), false);
        }
        return $fPrice;
    }

    /**
     * @param ProductEntity|null $oProductEntity
     * @param array $advancedPrice
     * @return array
     */
    protected function getAdvancedPrice($oProductEntity, array $advancedPrice): array
    {
        try {
            $aProductPrice = $this->getShopwareProductPrice($oProductEntity->id);
            if (!empty($aProductPrice['ShopwarePrice'])) {
                $aDecodedPrice = json_decode($aProductPrice['ShopwarePrice'], true);
                foreach ($aDecodedPrice as $value) {
                    $advancedPrice['net'] = $value['net'];
                    $advancedPrice['gross'] = $value['gross'];
                    /*list price functionality
                     * $value->getListPrice()->getNet();
                     * $value->getListPrice()->getGross();
                    */
                }
            } elseif ($oProductEntity->attributes['parentId'] !== null) {
                $ParentProductAdvancedPrice = $this->getParent()->load()->oProduct;
                $aProductPrice = $this->getShopwareProductPrice($ParentProductAdvancedPrice->id);
                if (!empty($aProductPrice['ShopwarePrice'])) {
                    $aDecodedPrice = json_decode($aProductPrice['ShopwarePrice'], true);
                    foreach ($aDecodedPrice as $value) {
                        $advancedPrice['net'] = $value['net'];
                        $advancedPrice['gross'] = $value['gross'];
                        /*list price functionality
                         * $value->getListPrice()->getNet();
                         * $value->getListPrice()->getGross();
                        */
                    }
                }
            }
        } catch (Throwable $ex) {
            MLMessage::gi()->addDebug($ex);
            return [];
        }
        return $advancedPrice;
    }

    private function getShopwareProductPrice($shopwareProductId) {
        return MLDatabase::getDbInstance()->fetchRow("SELECT *
                FROM `magnalister_shopwarecloud_product_price`
                WHERE `ShopwareProductID` = '$shopwareProductId' and `ShopwareRuleID` = '$this->sPriceRules';");
    }

    public function priceAdjustment($fPrice) {
        return $fPrice;
    }

    public function getSalesChannel() {

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
        $oShopwareProduct = $this->getCorrespondingProductEntity();
        $oTaxId = $oShopwareProduct->attributes['taxId'];
            if ($oTaxId === null && $oShopwareProduct->attributes['parentId'] != null) {
                //replacing Variation with empty tax with parent product tax id
                $oProductData = MLShopwareCloudAlias::getProductDataModel();
                $oShopwareProduct = (object)$oProductData->set('ShopwareProductID',$this->getCorrespondingProductEntity()->attributes['parentId'])->get('ShopwareProductData');
                $oTaxId = $oShopwareProduct->attributes['taxId'];
            }
            $fTax = $this->getShopwareTaxById($oTaxId,'ShopwareTaxRate');
            if ($aAddressSets !== null) {
                $aAddressData = $aAddressSets['Shipping'];
                $oCountry= $this->getShopwareCountryIdByCode($aAddressData['CountryCode']);
                if ($oCountry) {//when country exist or when country doesn't have any area, we cannot use address to calculate tax, we retrun normal tax
                    foreach ($this->getShopwareTaxRuleByCountryId($oCountry) as $taxRuleEntity) {
                        $zipCode = $aAddressData['Postcode'] ?? 0;
                        if($taxRuleEntity['ShopwareTaxId'] == $oTaxId ) {
                            switch ($taxRuleEntity['ShopwareTechnicalName']) {
                                //ZipCodeRangeRuleTypeFilter::TECHNICAL_NAME
                                case 'zip_code_range':
                                    $toZipCode = isset(json_decode($taxRuleEntity['ShopwareData'])->toZipCode) ? json_decode($taxRuleEntity['ShopwareData'])->toZipCode : null;
                                    $fromZipCode = isset(json_decode($taxRuleEntity['ShopwareData'])->fromZipCode) ? json_decode($taxRuleEntity['ShopwareData'])->fromZipCode : null;
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
                                //ZipCodeRuleTypeFilter::TECHNICAL_NAME
                                case 'zip_code':
                                    if (isset(json_decode($taxRuleEntity['ShopwareData'])->zipCode) && json_decode($taxRuleEntity['ShopwareData'])->zipCode === $zipCode) {
                                        $selectedTaxRule = $taxRuleEntity;
                                        break 2;
                                    }
                                    break;
                                //IndividualStatesRuleTypeFilter::TECHNICAL_NAME
                                case 'individual_states':
                                    //To test the individual_states type please uncomment following line
                                    //$aAddressData['Suburb']= 'Berlin';
                                    if (array_key_exists('Suburb', $aAddressData) && !empty($aAddressData['Suburb'])) {
                                        $countryStateId = $this->getShopwareCountryStateIdByCountryStateName($aAddressData['Suburb']);
                                        if ($countryStateId !== null) {
                                            $states = json_decode($taxRuleEntity['ShopwareData'])->states;
                                            if (in_array($countryStateId, $states, true)) {
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
                    }
                    if (isset($selectedTaxRule) && $selectedTaxRule) {
                        $fTax = $selectedTaxRule['ShopwareTaxRate'];
                    }
                }
            }

        } catch (Throwable $ex) {
            echo($ex->getMessage().$ex->getFile().$ex->getLine().$ex->getTraceAsString());
            $fTax = MLModule::gi()->getConfig('mwstfallback');
        }

        return $fTax;
    }

    /**
     * @return int
     */
    public function getTaxClassId() {
        if ($this->getCorrespondingProductEntity()->attributes['taxId'] !== null) {//if it is single product
            return $this->getCorrespondingProductEntity()->attributes['taxId'];
        } else if ($this->getMasterProductEntity()->attributes['taxId'] !== null) {//if it is variant tax should be got from master product
            return $this->getMasterProductEntity()->attributes['taxId'];
        }
        return null;
    }

    /**
     * @return int
     * @throws Exception
     */
    public function getStock() {
        if ($this->get('parentid') == '0') {
            return $this->getCorrespondingProductEntity()->attributes['availableStock'];
        } else {
            return $this->getCorrespondingProductEntity()->attributes['availableStock'];
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
            $variation['code'] = $variation['code'];
        }

        return $variationData;
    }

    /**
     * @param array $aFields
     * @return array
     * @throws Exception
     */
    public function getVariatonDataOptinalField($aFields = array())
    {
        //Variation Product
        $aOut = array();
        if ($this->getCorrespondingProductEntity()->attributes['optionIds'] != null) {
            foreach ($this->getCorrespondingProductEntity()->attributes['optionIds'] as $aAtribute) {
                // Product has options and an attribute but the option themselves doesn't exist
                $options = $this->getPropertiesGroupOptionById($aAtribute);
                if (!$options) {
                    continue;
                }

                $group = $this->getPropertiesGroupById($options['ShopwarePropertyGroupID']);
                $aData = array();
                if (in_array('code', $aFields)) {//an identifier for group of attributes , that used in Meinpaket at the moment
                    $aData['code'] = 'a_' . $group['ShopwarePropertyGroupID'];
                }
                if (in_array('valueid', $aFields)) {//an identifier for group of attributes , that used in Meinpaket at the moment
                    $aData['valueid'] = $options['ShopwarePropertyGroupOptionID'];
                }
                if (in_array('name', $aFields)) {
                    $aData['name'] = $group['ShopwareName'];
                }
                if (in_array('value', $aFields)) {
                    if ($options['ShopwareName'] !== null) {
                        $aData['value'] = $options['ShopwareName'];
                    } else {
                        //get the name from defualt language
                        $optionsReplace = $this->getPropertiesGroupOptionById($aAtribute, true);
                        $aData['value'] = $optionsReplace['ShopwareName'];
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
        //Notes: the active field store as false and true in database, but it is appeared as 1 and null in codes
        //Variation product with true value
        if ($this->getCorrespondingProductEntity()->attributes['active'] == 1 && $this->getCorrespondingProductEntity()->attributes['parentId'] != null) {
            $mStatus = true;
        }//Variation product with null or false value
        elseif ($this->getCorrespondingProductEntity()->attributes['active'] == null && $this->getCorrespondingProductEntity()->attributes['parentId'] != null) {
            //Parent product with null or false value
            if ($this->getMasterProductEntity()->attributes['active'] == null) {
                $mStatus = false;
            }//Parent product with true value
            else {
                $mStatus = true;
            }
        }//Master product with true
        elseif ($this->getCorrespondingProductEntity()->attributes['active'] == 1 && $this->getCorrespondingProductEntity()->attributes['parentId'] == null) {
            $mStatus = true;
        }//Master product with false value
        elseif ($this->getCorrespondingProductEntity()->attributes['active'] == null && $this->getCorrespondingProductEntity()->attributes['parentId'] == null) {
            $mStatus = false;
        } else {
            $mStatus = true;
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
        $oCategoryModel = MLShopwareCloudAlias::getCategoryModel();
        $sCatPath = '';
        $categoryIds = array();

        $aProductCategories = $this->getProductsCategory();

        foreach ($aProductCategories as $aProductCategory) {
            $sCategories = '';
            if (isset($aProductCategory['ShopwarePath'])) {
                // explode - converts path to array, array_filter - removes empty arrays, array_values - resets the array keys
                $aCategoryPath = array_values(array_filter(explode('|', $aProductCategory['ShopwarePath'])));
                foreach ($aCategoryPath as $categoryId) {
                    if (!in_array($categoryId, $categoryIds)) {
                        $sCategories .= "','".$categoryId;
                        $categoryIds[] = $categoryId;
                    }
                }
                if (!in_array($aProductCategory['ShopwareCategoryID'], $categoryIds)) {
                    $sCategories .= "','".$aProductCategory['ShopwareCategoryID'];
                    $categoryIds[] = $aProductCategory['ShopwareCategoryID'];
                }
                $oCategoryTranslationModel = MLShopwareCloudAlias::getCategoryTranslationModel();
                $aCategoryNames = MLDatabase::getDbInstance()
                    ->fetchArray("SELECT ct.ShopwareName
                    FROM ".$oCategoryTranslationModel->getTableName()." ct 
                    INNER JOIN `".$oCategoryModel->getTableName()."` c
                    ON ct.ShopwareCategoryID = c.ShopwareCategoryID
                    WHERE c.ShopwareCategoryID IN ('".$sCategories."') 
                    AND c.ShopwareParentID IS NOT NULL 
                    AND ct.ShopwareLanguageID = '".$this->getShopwareCloudLanguageId()."' 
                    AND c.ShopwareActive=1
                    ORDER BY c.ShopwareLevel ASC");

                foreach ($aCategoryNames as $categoryName) {
                    $sCatPath .= '<br>'.$categoryName['ShopwareName'].'&nbsp;&gt;&nbsp;';
                }
                $sCatPath .= '<br>';
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
        $oCategoryModel = MLShopwareCloudAlias::getCategoryModel();
        $aProductCategories = $this->getProductsCategory();

        foreach ($aProductCategories as $aProductCategory) {
            if (isset($aProductCategory['ShopwarePath'])) {
                if ($blIncludeRootCats) {
                    // explode - converts path to array, array_filter - removes empty arrays, array_values - resets the array keys
                    $aCategoryPath = array_values(array_filter(explode('|', $aProductCategory['ShopwarePath'])));
                    $aCategories = array_unique(array_merge($aCategories, $aCategoryPath));
                } else {
                    $sCategories = str_replace("|", "','",  trim($aProductCategory['ShopwarePath'], "|"));
                    $aCategories = array_unique(array_merge($aCategories, MLDatabase::getDbInstance()
                        ->fetchArray("SELECT ShopwareCategoryID
                    FROM ".$oCategoryModel->getTableName()." 
                    WHERE ShopwareCategoryID IN ('".$sCategories."') AND ShopwareParentID IS NOT NULL AND ShopwareActive=1")));
                }
            }

            if (isset($aProductCategory['ShopwareCategoryID'])) {
                $aCategories[] = $aProductCategory['ShopwareCategoryID'];
            }
        }


        return $aCategories;
    }

    protected function getProductsCategory() {
        $oCategoryModel = MLShopwareCloudAlias::getCategoryModel();
        $oCategoryRelationModel = MLShopwareCloudAlias::getCategoryRelationModel();
        $aProductCategory = MLDatabase::getDbInstance()
            ->fetchArray("SELECT c.ShopwareCategoryID, c.ShopwarePath
                    FROM ".$oCategoryModel->getTableName()." c
                    INNER JOIN `".$oCategoryRelationModel->getTableName()."` cr
                    ON cr.ShopwareCategoryID = c.ShopwareCategoryID
                    WHERE (cr.ShopwareProductID = '".$this->oProduct->id."')
                    AND (c.ShopwareActive=1)");

        return $aProductCategory;
    }

    /**
     * @param bool $blIncludeRootCats
     * @return array
     */
    public function getCategoryStructure($blIncludeRootCats = true) {
        $oCategoryModel = MLShopwareCloudAlias::getCategoryModel();
        $aCategoryStructure = array();
        $categoryIds = array();

        $aProductCategories = $this->getProductsCategory();
        foreach ($aProductCategories as $aProductCategory) {
            $sCategories = '';
            if (isset($aProductCategory['ShopwarePath'])) {
                // explode - converts path to array, array_filter - removes empty arrays, array_values - resets the array keys
                $aCategoryPath = array_values(array_filter(explode('|', $aProductCategory['ShopwarePath'])));
                foreach ($aCategoryPath as $categoryId) {
                    if (!in_array($categoryId, $categoryIds)) {
                        $sCategories .= "','".$categoryId;
                        $categoryIds[] = $categoryId;
                    }
                }
                if (!in_array($aProductCategory['ShopwareCategoryID'], $categoryIds)) {
                    $sCategories .= "','".$aProductCategory['ShopwareCategoryID'];
                    $categoryIds[] = $aProductCategory['ShopwareCategoryID'];
                }
                $oCategoryTranslationModel = MLShopwareCloudAlias::getCategoryTranslationModel();
                $aCategories = MLDatabase::getDbInstance()
                    ->fetchArray("SELECT ct.ShopwareCategoryID, ct.ShopwareName, ct.ShopwareDescription
            FROM ".$oCategoryTranslationModel->getTableName()." ct 
            INNER JOIN `".$oCategoryModel->getTableName()."` c
            ON ct.ShopwareCategoryID = c.ShopwareCategoryID
            WHERE c.ShopwareCategoryID IN ('".$sCategories."') 
            AND ct.ShopwareLanguageID = '".$this->getShopwareCloudLanguageId()."' 
            AND c.ShopwareActive=1
            ORDER BY c.ShopwareLevel ASC");

                foreach ($aCategories as $aCategory) {
                    $aCategoryStructure[] = array(
                        'ID' => $aCategory['ShopwareCategoryID'],
                        'Name' => $aCategory['ShopwareName'],
                        'Description' => $aCategory['ShopwareDescription'],
                        'Status' => true,
                    );
                }
            }
        }

        return $aCategoryStructure;
    }

    /**
     * @param $sName
     * @param null $sMethod
     * @return PersistentCollection|int|string|null
     * @throws Exception
     */
    public function getProductField($sName, $sMethod = null)
    {
        $mValue = '';
        if (strpos($sName, 'a_') === 0) {
            //Variation product properties
            if ($this->getCorrespondingProductEntity()->attributes['optionIds'] !== null) {
                $sName = substr($sName, 2);
                foreach ($this->getCorrespondingProductEntity()->attributes['optionIds'] as $value) {
                    if ($this->getPropertiesGroupOptionById($value)['ShopwarePropertyGroupID'] == $sName) {
                        if ($this->getPropertiesGroupOptionById($value)['ShopwareName'] !== NULL) {
                            $mValue = $this->getPropertiesGroupOptionById($value)['ShopwareName'];
                            break;
                        } else {
                            $mValue = $this->getPropertiesGroupOptionById($value,true)['ShopwareName'];
                            break;
                        }
                    }
                }
            }
        } elseif (strpos($sName, 'p_') === 0) {
            if ($this->getCorrespondingProductEntity()->attributes['propertyIds'] !== null) {
                $sName = substr($sName, 2);
                foreach ($this->getCorrespondingProductEntity()->attributes['propertyIds']  as $value) {
                    if ($this->getPropertiesGroupOptionById($value)['ShopwarePropertyGroupID']  == $sName) {
                        if ($this->getPropertiesGroupOptionById($value)['ShopwareName'] !== NULL) {
                            $mValue = $this->getPropertiesGroupOptionById($value)['ShopwareName'];
                            break;
                        } else {
                            $mValue = $this->getPropertiesGroupOptionById($value,true)['ShopwareName'];
                            break;
                        }
                    }
                }
            }

        } elseif (strpos($sName, 'c_') === 0) {
            $sName = substr($sName, 2);
            if (isset($this->getCorrespondingProductEntity()->attributes['customFields'][$sName])) {
                $aAttribute['value'] = $this->getCorrespondingProductEntity()->attributes['customFields'][$sName];
                $mValue = $aAttribute['value'];
            } elseif (isset($this->getMasterProductEntity()->attributes['customFields'][$sName])) {
                $aAttribute['value'] = $this->getMasterProductEntity()->attributes['customFields'][$sName];
                $mValue = $aAttribute['value'];
            }
        } else {
            if (array_key_exists($sName, $this->getCorrespondingProductEntity()->attributes)) {
                $mValue = $this->getCorrespondingProductEntity()->attributes[$sName];
                if ($mValue === null && $this->getCorrespondingProductEntity()->attributes['parentId'] !== null) {//if it is variant and value is inherited
                    $oProductData = MLShopwareCloudAlias::getProductDataModel();
                    $oMasterProduct = (object)$oProductData->set('ShopwareProductID', $this->getCorrespondingProductEntity()->attributes['parentId'])->get('ShopwareProductData');
                    $mValue = $oMasterProduct->attributes[$sName];
                }
                if ($sName == 'manufacturerId' && $this->getCorrespondingProductEntity()->attributes['manufacturerId'] !== '') {
                    //Master(parent) product Manufacturer and Variation product without inherent from Master
                        if ($mValue !== null) {
                            $oProductManufacrerTranslationResult=$this->getShopwareManufacturerByIdAndLang($mValue,$this->getShopwareCloudLanguageId());
                            if(!$oProductManufacrerTranslationResult){
                                $oProductManufacrerTranslationResult = $this->getShopwareManufacturerByIdAndLang($mValue, MLShopwareCloudTranslationHelper::gi()->getLanguage(true));
                            }
                            $mValue = $oProductManufacrerTranslationResult['ShopwareName'];

                        }

                } elseif ($sName == 'ManufacturerId' && $this->getCorrespondingProductEntity()->attributes['manufacturerId'] == '' && $this->getCorrespondingProductEntity()->attributes['parentId'] !== '') {
                    //Variation product Manufacturer is inherent from Master (Parent) product
                        if ($mValue !== null) {
                            $oProductManufacrerTranslationResult=$this->getShopwareManufacturerByIdAndLang($mValue,$this->getShopwareCloudLanguageId());
                            if(!$oProductManufacrerTranslationResult){
                                $oProductManufacrerTranslationResult = $this->getShopwareManufacturerByIdAndLang($mValue, MLShopwareCloudTranslationHelper::gi()->getLanguage(true));
                            }
                            $mValue = $oProductManufacrerTranslationResult['ShopwareName'];
                        }

                }
            } else if (strpos($sName, '_ValueWithUnit') !== false) {
                $aName = explode('_', $sName);
                if (isset($aName[0]) && in_array($aName[0], ['Width', 'Height', 'Length', 'Weight'])) {
                    $mValue = $this->getProductField($aName[0]) . ' ' . $this->getProductField($aName[0] . '_Unit');
                }
            } else if (strpos($sName, '_Unit') !== false) {
                $aName = explode('_', $sName);
                if (isset($aName[0])) {
                    if (in_array($aName[0], ['Width', 'Height', 'Length'])) {
                        $mValue = 'mm';
                    } else if ($aName[0] === 'Weight') {
                        $mValue = 'KG';
                    }
                }
            } else {
                MLMessage::gi()->addDebug('method get' . $sName . ' does not exist in Article or ArticleDetails');
                return '';
            }
        }
        return $mValue;
    }

    private function getPropertiesGroupOptionById( $value,$DefaultLanguage = false ) {
        $sLangId = MLShopwareCloudTranslationHelper::gi()->getLanguage($DefaultLanguage);
        return MLDatabase::getDbInstance()->fetchRow("SELECT ShopwarePropertyGroupOptionID,ShopwarePropertyGroupID, ShopwareName  
    FROM `magnalister_shopwarecloud_property_group_option_translation` 
    WHERE `ShopwareLanguageID` = '$sLangId' and `ShopwarePropertyGroupOptionID` = '$value';");
    }

    private function getPropertiesGroupById( $value,$DefaultLanguage = false ) {
        $sLangId = MLShopwareCloudTranslationHelper::gi()->getLanguage($DefaultLanguage);
        return MLDatabase::getDbInstance()->fetchRow("SELECT ShopwarePropertyGroupID,ShopwareName  
    FROM `magnalister_shopwarecloud_property_group_translation` 
    WHERE `ShopwareLanguageID` = '$sLangId' and `ShopwarePropertyGroupID` = '$value';");
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getWeight() {
        $sWeight = (float)$this->getCorrespondingProductEntity()->attributes['weight'];
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
       //In Shopware 6: New scale units can only be added in the system default language.
        $oDetail = $this->getCorrespondingProductEntity();
        if (!$oDetail) {
            return array();
        }
        if ($oDetail->attributes['unitId'] !== null) {
            //unitId has been set for Parent product and variation product
            $BasePrice = $this->getShopwareUnitbyId($oDetail->attributes['unitId']);
            try {
                $fReferenceUnit = $oDetail->attributes['referenceUnit'];
                $fPurchaseUnit = $oDetail->attributes['purchaseUnit'];
                $sUnitName = $BasePrice['ShopwareName'];
                $sUnit = $BasePrice['ShopwareShortCode'];
            } catch (Exception $oEx) {
                return array();
            }
        } elseif ($oDetail->attributes['unitId'] == null && $oDetail->attributes['parentId'] !== null) {
            //unitId is inherited  for variation product and unitId is null
            $oDetail = $this->getMasterProductEntity();
            if ($oDetail->attributes['unitId'] !== null) {
                $BasePrice = $this->getShopwareUnitbyId($oDetail->attributes['unitId']);
                try {
                    $fReferenceUnit = $oDetail->attributes['referenceUnit'];
                    $fPurchaseUnit = $oDetail->attributes['purchaseUnit'];
                    $sUnitName = $BasePrice['ShopwareName'];
                    $sUnit = $BasePrice['ShopwareShortCode'];
                } catch (Exception $oEx) {
                    return array();
                }
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
    public function getProperties($sSeparator = null) {
        try {
            $sPropertiesHtml = ' ';
            if ($this->getCorrespondingProductEntity()->attributes['propertyIds'] !== null) {
                $aProperties = $this->getPropertiesGrouped();
                $sPropertiesHtml .= '<ul class="magna_properties_list">';
                $sRowClass = 'odd';
                foreach ($aProperties as $sPropertyGroup => $aPropertyValues) {
                    natsort($aPropertyValues);
                    $sPropertiesHtml .= '<li class="magna_property_item '.$sRowClass.'">'
                        .'<span class="magna_property_name">'.$sPropertyGroup
                        .'</span>'
                        .($sSeparator !== null ? $sSeparator : '')
                        .'<span  class="magna_property_value">'.implode(', ', $aPropertyValues)
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

    public function getPropertiesGrouped(): array {
        $aProperties = array();
        foreach ($this->getCorrespondingProductEntity()->attributes['propertyIds'] as $value) {
            if ($this->getPropertiesGroupOptionById($value)) {
                $OptionPropertiesEntites = $this->getPropertiesGroupOptionById($value);
            } else {
                $OptionPropertiesEntites = $this->getPropertiesGroupOptionById($value, true);
            }
            if (!isset($OptionPropertiesEntites['ShopwarePropertyGroupID'])) {
                MLMessage::gi()->addDebug('ShopwarePropertyGroupID doesn\'t exist:', array($value, $OptionPropertiesEntites));
            }

            $group = $this->getPropertiesGroupById($OptionPropertiesEntites['ShopwarePropertyGroupID']);
            if ($OptionPropertiesEntites) {
                $OptionPropertiesEntites = $this->getPropertiesGroupOptionById($value, true);
                if (!$group) {
                    $group = $this->getPropertiesGroupById($OptionPropertiesEntites['ShopwarePropertyGroupID'], true);
                } else {
                    $group = $this->getPropertiesGroupById($OptionPropertiesEntites['ShopwarePropertyGroupID']);
                }
            }
            $aProperties[$group['ShopwareName']][] = $OptionPropertiesEntites['ShopwareName'];
        }
        return $aProperties;
    }

    /**
     *
     * @return array of freetextfield in shopware
     */
    public function getCustomField() {

    }

    /**
     * @return array
     */
    public function getReplaceProperty() {
        $aReplace = parent::getReplaceProperty();
        $customFields = $this->getCorrespondingProductEntity()->attributes['customFields'];
        if(is_array($customFields)) {
            foreach ($customFields as $technicalName => $sAttrValue) {
                $CustomfieldDataRow = json_decode($this->getCustomFieldDataByTechnicalName($technicalName)['ShopwareCustomFieldLabel']);
                $Customfieldlabel = '';
                foreach ($CustomfieldDataRow as $item => $value){
                    if ($item == MLShopwareCloudTranslationHelper::gi()->getLanguageCode()) {
                        $Customfieldlabel =$value;
                        break;
                    }
                }
                $aReplace['#Freetextfield'.$this->getCustomFieldDataByTechnicalName($technicalName)['ShopwarePosition'] .'#'] = $aReplace['#Freitextfeld'.$this->getCustomFieldDataByTechnicalName($technicalName)['ShopwarePosition'] .'#'] =
                $aReplace['#Customfield'.$this->getCustomFieldDataByTechnicalName($technicalName)['ShopwarePosition'] .'#'] = $aReplace['#Zusatzfeld'.$this->getCustomFieldDataByTechnicalName($technicalName)['ShopwarePosition'] .'#'] = $sAttrValue;
                $aReplace['#Description'.$this->getCustomFieldDataByTechnicalName($technicalName)['ShopwarePosition'] .'#'] = $aReplace['#Bezeichnung'.$this->getCustomFieldDataByTechnicalName($technicalName)['ShopwarePosition'] .'#'] = $Customfieldlabel;

            }
        }

        $aReplace['#PROPERTIES#'] = $this->getProperties();
        return $aReplace;
    }

    /**
     * Returns the custom field data by its technical name
     *
     * @param $technicalName
     * @return array|mixed
     */
    public function getCustomFieldDataByTechnicalName($technicalName) {
        if (!array_key_exists($technicalName, $this->customFieldCache)) {
            $this->customFieldCache[$technicalName] = MLDatabase::factorySelectClass()
                ->select('*')
                ->from('magnalister_shopwarecloud_custom_fields')
                ->where("`ShopwareCustomFieldName` = '$technicalName'")
                ->getRowResult();
        }

        return $this->customFieldCache[$technicalName];
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
            $iVariantCount = $mMasterProduct->attributes['childCount'];
        }
        return $iVariantCount;
    }

    /**
     * @return bool
     */
    public function isSingle() {

        $this->load();
        $mConfiguratorSet = $this->getCorrespondingProductEntity()->attributes['childCount'] ;

        if ((int)$mConfiguratorSet == 0) {
            return true;
        } else {
            return false;
        }

    }
    /**
     * change current shop, so we can get product information in different languages
     * @param int $iLang
     * @return ML_Shopware6_Model_Product
     */
    public function setLang($iLang) {
        $this->oShopwareCloudLanguageId = $iLang;
        return $this;

    }

    /**
     * @param string $mAttributeCode
     * @return float|null
     * @throws Exception
     */
    public function getAttributeValue($mAttributeCode) {
        return $this->getProductField($mAttributeCode);
    }

    /**
     * @return PersistentCollection|int|string|null
     */
    public function getManufacturer() {
        return $this->getModulField('manufacturer');
    }

    /**
     * @return PersistentCollection|int|string|null
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
     * @param $iProductID
     * @param null $oContext
     * @param array $aAssociations
     * @return ProductEntity
     */
    protected function getShopwareProduct($iProductID, $oContext = null, array $aAssociations = []) {

    }



    protected $aFields = array(
        'ID'                    => array(
            'isKey' => true,
            'Type'  => 'int(11)', 'Null' => self::IS_NULLABLE_NO, 'Default' => NULL, 'Extra' => 'auto_increment', 'Comment' => ''
        ),
        'ParentId'              => array(
            'Type' => 'int(11)', 'Null' => self::IS_NULLABLE_NO, 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'ProductsId'            => array(
            'Type' => 'varchar(255)', 'Null' => self::IS_NULLABLE_NO, 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'ProductsSku'           => array(
            'Type' => 'varchar(255)', 'Null' => self::IS_NULLABLE_NO, 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'MarketplaceIdentId'    => array(
            'Type' => 'varchar(150)', 'Null' => self::IS_NULLABLE_NO, 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'MarketplaceIdentSku'   => array(
            'Type' => 'varchar(255)', 'Null' => self::IS_NULLABLE_NO, 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'LastUsed'              => array(
            'Type' => 'date', 'Null' => self::IS_NULLABLE_NO, 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'Data'                  => array(
            'Type' => 'text', 'Null' => self::IS_NULLABLE_NO, 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'ShopwareQuantity'        => array(
            'Type' => 'int(11)', 'Null' => self::IS_NULLABLE_YES, 'Default' => NULL, 'Extra' => '', 'Comment' => 'Use separated column to sort by quantity faster.'
        ),
        'ShopwarePrice'           => array(
            'Type' => 'decimal(20,3)', 'Null' => self::IS_NULLABLE_YES, 'Default' => NULL, 'Extra' => '', 'Comment' => 'Use separated column to sort by quantity faster.'
        ),
        'ShopwareManufacturer'          => array(
            'Type' => 'varchar(255)', 'Null' => self::IS_NULLABLE_YES, 'Default' => NULL, 'Extra' => '', 'Comment' => 'Deprecated after vendor id'
        ),
        'ShopwareManufacturerNumber'          => array(
            'Type' => 'varchar(255)', 'Null' => self::IS_NULLABLE_YES, 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'ShopwareManufacturerId'        => array(
            'Type' => 'varchar(255)', 'Null' => self::IS_NULLABLE_YES, 'Default' => NULL, 'Extra' => '', 'Comment' => 'Use vendor id of vendor name to make it faster'
        ),
        'ShopwareEAN'        => array(
            'Type' => 'varchar(255)', 'Null' => self::IS_NULLABLE_YES, 'Default' => NULL, 'Extra' => '', 'Comment' => 'Use vendor id of vendor name to make it faster'
        ),
        'ShopwareActive' => array(
            'Type' => 'tinyint(1)', 'Null' => self::IS_NULLABLE_YES, 'Default' => '1', 'Extra' => '', 'Comment' => ''
        ),
        'ShopwareUpdateDate'      => array(
            'Type' => 'datetime', 'Null' => self::IS_NULLABLE_YES, 'Default' => NULL, 'Extra' => '', 'Comment' => 'date of update'
        ),
        'ShopwareCreatedAt'      => array(
            'Type' => 'datetime', 'Null' => self::IS_NULLABLE_NO, 'Default' => NULL, 'Extra' => '', 'Comment' => 'date of update'
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

    /**
     * @return mixed
     * @throws Exception
     */
    protected function getShopwareTaxById($TaxId,$Field)
    {
        $oProductData = MLShopwareCloudAlias::getTaxesModel();
        $TaxData = $oProductData->set('ShopwareTaxID', $TaxId)->get($Field);
        return $TaxData;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    protected function getShopwareCountryIdByCode($CountryCode)
    {
        $CountryTalbe = MLShopwareCloudAlias::getCountryModel()->getTableName();
        $CountryId= MLDatabase::getDbInstance()
            ->fetchOne("SELECT ShopwareCountryID
                    FROM $CountryTalbe                                         
                    WHERE ShopwareIso = ('".$CountryCode."')");
        return $CountryId;

    }

    /**
     * @return mixed
     * @throws Exception
     */
    protected function getShopwareCountryStateIdByCountryStateName($CountryStateName)
    {
        $CountryStateId= MLDatabase::getDbInstance()
            ->fetchOne("SELECT ShopwareCountryStateID
                    FROM magnalister_shopwarecloud_country_state_translation                                         
                    WHERE ShopwareName = ('".$CountryStateName."')
                    AND ShopwareCountryLanguageId = '".$this->getShopwareCloudLanguageId()."' ");
        return $CountryStateId;

    }

    /**
     * @return mixed
     * @throws Exception
     */
    protected function getShopwareTaxRuleByCountryId($CountryId)
    {

        return MLDatabase::factorySelectClass()
            ->select('*')
            ->from('magnalister_shopwarecloud_tax_rule', 'mgo')
            ->join(array('magnalister_shopwarecloud_tax_rule_Type', 'mlo', 'mgo.`ShopwareTaxRuleTypeId` = mlo.`ShopwareTaxRuleTypeID`'), ML_Database_Model_Query_Select::JOIN_TYPE_LEFT)
            ->where("mgo.`ShopwareCountryId` = '$CountryId'")
            ->getResult();

    }

    /**
     * @return mixed
     * @throws Exception
     */
    protected function getShopwareUnitbyId($UnitId)
    {

        return MLDatabase::factorySelectClass()
            ->select('*')
            ->from('magnalister_shopwarecloud_unit')
            ->where("`ShopwareUnitId` = '$UnitId'")
            ->getRowResult();
    }

    /**
     * @return mixed
     * @throws Exception
     */
    protected function getShopwareManufacturerByIdAndLang($ManufactureId,$LangId)
    {
        return MLDatabase::factorySelectClass()
            ->select('*')
            ->from('magnalister_shopwarecloud_product_manufacturer_translation')
            ->where("`ShopwareProductManufacturerID` = '$ManufactureId' AND `ShopwareLanguageID` = '$LangId'")
            ->getRowResult();
    }

    /**
     * @return mixed
     * @throws Exception
     */
    protected function getShopwareCurrencyByIsoCode($IsoCode)
    {
        if (!array_key_exists($IsoCode, $this->currencyByIsoCodeCache)) {
            $this->currencyByIsoCodeCache[$IsoCode] = MLDatabase::factorySelectClass()
                ->select('*')
                ->from('magnalister_shopwarecloud_currency')
                ->where("`ShopwareIsoCode` = '$IsoCode'")
                ->getRowResult();
        }

        return $this->currencyByIsoCodeCache[$IsoCode];
    }

    /**
     * @return CurrencyEntity|null
     */
    public function getDefaultCurrency() {
        if (empty($this->defaultCurrency)) {
            $this->defaultCurrency =  MLDatabase::factorySelectClass()
                ->select('*')
                ->from('magnalister_shopwarecloud_currency')
                ->where("`ShopwareFactor` = '1'")
                ->getRowResult();
        }

        return $this->defaultCurrency;
    }

}
