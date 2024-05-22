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

include_once(M_DIR_LIBRARY . 'request/shopware/ShopwareUnit.php');

use library\request\shopware\ShopwareUnit;

class MLShopwareCloudTranslationHelper {
    protected static $instance = null;
    protected $productParentId = null;

    protected $languageIdCache = array();
    protected $languageCodeCache = array();

    /**
     *
     * @param string $locale
     * @return MLShopwareCloudTranslationHelper
     */
    public static function gi() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getProductVariationIsinheritance($productModel, $translationField, $languageId = false) {
        if (isset($productModel->attributes['parentId']) && $productModel->attributes['parentId'] != NULL) {//Variation product
            //Variations product with translation
            $result = $this->getShopwareCloudTranslation(
                'ShopwareCloudProductTranslation',
                'ShopwareProductID',
                $productModel->id,
                $translationField,
                '',
                $languageId
            );
            //Variation product with inheritance
            if (empty($result)) {
                return true;
            } else {
                return false;
            }
        }
        return true;
    }

    public function getShopwareCloudProductTranslation($productModel, $translationField, $languageId = false) {
        if (!isset($productModel->attributes)) {
            return '';
        }

        if (isset($productModel->attributes['parentId']) && $productModel->attributes['parentId'] != NULL) {//Variation product
            //Variations product with translation
            $result = $this->getShopwareCloudTranslation(
                'ShopwareCloudProductTranslation',
                'ShopwareProductID',
                $productModel->id,
                $translationField,
                '',
                $languageId
            );
            //Variation product with inheritance
            if (empty($result)) {
                $result = $this->getShopwareCloudTranslation(
                    'ShopwareCloudProductTranslation',
                    'ShopwareProductID',
                    $productModel->attributes['parentId'],
                    $translationField,
                    '',
                    $languageId
                );

            }
        } else {
            $result = $this->getShopwareCloudTranslation(
                'ShopwareCloudProductTranslation',
                'ShopwareProductID',
                $productModel->id,
                $translationField,
                '',
                $languageId
            );
        }

        return $result;
    }
    
    /**
     * Get translation for configuration fields. $sTranslationType is passed to getShopwareCloudTranslation to check for
     * right type (check ShopwareTrasnlationType column in ShopwareCloudTranslations table)
     *
     * @param $sModelId
     * @param $sTranslationType
     * @return string 
     */
    public function getShopwareCloudConfigTranslation($sModelId, $sTranslationType) {
        $sQuery = "AND et.ShopwareTranslationType = '".$sTranslationType."'";
        return $this->getShopwareCloudTranslation('ShopwareCloudTranslations', 'ShopwareEntityID', $sModelId, 'ShopwareTranslation', false, $sQuery);
    }

    /**
     * 
     *
     * @param $sModelName
     * @param $entityName
     * @param $entityId
     * @param $translationField
     * @param $sTranslationType
     * @return array|false|mixed
     * @throws Exception
     */
    private function getShopwareCloudTranslation($sModelName, $entityName, $entityId, $translationField, $sTranslationType = '', $languageId = false) {
        $result = '';
        $mlDatabse = MLDatabase::getDbInstance();
        if ($sModelName === 'ShopwareCloudProductTranslation') {
            if(!$languageId) {
                $sLanguageId = $this->getLanguage();
                $sLanguageFallbackId = $this->getLanguage(true);
            }else{
                $sLanguageId = $languageId;
                $sLanguageFallbackId = $this->getLanguage(true);
            }
        } else {
                $sLanguageId = $this->getShopwareLanguageId();
                $sLanguageFallbackId = $this->getLanguage(true);
        }

        $oEntityDBObject = MLDatabase::factory($sModelName);

        $aEntityTranslations = MLDatabase::getDbInstance()->fetchArray("
            SELECT et.".$mlDatabse->escape($translationField).", et.ShopwareLanguageID, et.".$mlDatabse->escape($entityName)."
              FROM ".$oEntityDBObject->getTableName()." et 
             WHERE      et.".$mlDatabse->escape($entityName)." = '".$mlDatabse->escape($entityId)."'
                    AND (   et.ShopwareLanguageID =  '".$mlDatabse->escape($sLanguageId)."'
                            OR et.ShopwareLanguageID = '".$mlDatabse->escape($sLanguageFallbackId)."'
                    ) 
                    ".$sTranslationType."
            ");

        foreach ($aEntityTranslations as $aEntityTranslation) {
            // Checks if translation is set for the entity for the selected language (in product preparation or user interface language) or for fallback language
            if ($aEntityTranslation['ShopwareLanguageID'] === $sLanguageId && isset($aEntityTranslation[$translationField])) {
                $result = $aEntityTranslation[$translationField];
                break;
            } elseif ($aEntityTranslation['ShopwareLanguageID'] === $sLanguageFallbackId && isset($aEntityTranslation[$translationField])) {
                $result = $aEntityTranslation[$translationField];
            } else {
                $result = '';
            }
        }

        return $result;
    }

    public function getShopwareLanguageId() {
        $sLanguageCode = I18N::gi()->getShopwareQueryLocale(true);

        if (!array_key_exists($sLanguageCode, $this->languageIdCache)) {
            $this->languageIdCache[$sLanguageCode] = MLDatabase::getDbInstance()->fetchOne("
                SELECT `ShopwareLanguageID` 
                  FROM ".MLDatabase::factory('ShopwareCloudLanguage')->getTableName()." 
                 WHERE ShopwareLanguageCode = '".MLDatabase::getDbInstance()->escape($sLanguageCode)."'
            ");
        }

        return $this->languageIdCache[$sLanguageCode];
    }

    public function getLanguageCode($getFallback = false) {
        if ($getFallback) {
            $iLangCode = $this->getLanguageCodeByLanguageID($this->getShopwareStandardLanguage());
        } else {
            try {
                $aConfig = MLModule::gi()->getConfig();
            } catch (Exception $ex) {
                $aConfig = [];
            }
            if (isset($aConfig['lang']) && $aConfig['lang'] != NULL) {
                $iLangCode = $this->getLanguageCodeByLanguageID($aConfig['lang']);
            } else {
                $iLangCode = $this->getLanguageCodeByLanguageID($this->getShopwareStandardLanguage());
            }
        }

        return $iLangCode;
    }

    /**
     * @param $languageId
     * @return array|bool|mixed
     */
    public function getLanguageCodeByLanguageID($languageId) {
        if (!array_key_exists($languageId, $this->languageCodeCache)) {
            $this->languageCodeCache[$languageId] = MLDatabase::getDbInstance()->fetchOne("
                SELECT `ShopwareLanguageCode` 
                  FROM `magnalister_shopwarecloud_language` 
                 WHERE `ShopwareLanguageID` ='".$languageId."' 
            ");
        }

        return $this->languageCodeCache[$languageId];
    }

    public function getLanguage($getFallback = false) {
        try {
            $aConfig = MLModule::gi()->getConfig();
        } catch (Exception $ex) {
            $aConfig = [];
        }
        if ($getFallback) {
            if ($this->getShopwareStandardLanguage() != null) {
                $iLangId = $this->getShopwareStandardLanguage();
            } else if (isset($aConfig['lang'])) {
                $iLangId = $aConfig['lang'];
            }
        } else {
            if (isset($aConfig['lang']) && $aConfig['lang'] != NULL) {
                $iLangId = $aConfig['lang'];
            } else {
                $iLangId = $this->getShopwareStandardLanguage();
            }
        }
        if (!$iLangId) {
            if (isset($_GET['sw-context-language'])) {
                $iLangId = $_GET['sw-context-language'];
            } else {
                //
            }
        }
        return $iLangId;
    }

    public function getShopwareStandardLanguage(){
        if (!MLCache::gi()->exists('DefaultLang.json')) {
            $shopwareUnitRequest = new ShopwareUnit(MLShopwareCloudAlias::getShopHelper()->getShopId());
            $oUnit = $shopwareUnitRequest->getShopwareUnit();
            if (is_array($oUnit->getData()) && isset($oUnit->getData()[0])) {
                $aCurrencyTranslation = $shopwareUnitRequest->getShopwareUnitTranslations($oUnit->getData()[0]->getRelationships()->getTranslations()->getLinks()->getRelated());
                $languageID = $aCurrencyTranslation['data'][0]['attributes']['languageId'];
                MLCache::gi()->set('DefaultLang.json', $languageID, 60 * 60 * 24);
            } else {
                $languageID = MLDatabase::getDbInstance()->fetchOne('
                    SELECT l.ShopwareLanguageID 
                      FROM magnalister_shopwarecloud_language l 
                     WHERE l.ShopwareLanguageID IN (
                                SELECT DISTINCT pt.ShopwareLanguageID
                                  FROM `magnalister_shopwarecloud_product_translation` pt
                            INNER JOIN `magnalister_products` p ON p.ProductsId = pt.ShopwareProductID
                                 WHERE (pt.ShopwareName IS NOT NULL) AND (p.ParentId = 0)
                            ) 
                     LIMIT 1
                ');
                MLCache::gi()->set('DefaultLang.json', $languageID, 60 * 60 * 24);
            }
        }
        return MLCache::gi()->get('DefaultLang.json');
    }
}
