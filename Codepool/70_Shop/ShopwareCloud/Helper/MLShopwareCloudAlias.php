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
 * (c) 2010 - 2024 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

include_once(DIR_MAGNALISTER_HELPER . 'APIHelper.php');
include_once(M_DIR_LIBRARY . 'request/shopware/ShopwareCurrency.php');

use library\request\shopware\ShopwareCurrency;

class MLShopwareCloudAlias {

    /**
     * get shopware cloud shop helper class
     *
     * @return ML_ShopwareCloud_Helper_Model_Shop|Object
     */
    public static function getShopHelper(){
        return MLHelper::gi('model_shop');
    }

    /**
     * get Shopware Cloud product helper class
     * @return ML_ShopwareCloud_Helper_Model_Product|Object
     */
    public static function getProductHelper(){
        return MLHelper::gi('model_product');
    }

    /**
     * @return ML_ShopwareCloud_Model_Table_ShopwareCloudCategoryRelation|ML_Database_Model_Table_Abstract
     */
    public static function getProductCategoryRelationModel() {
        return MLDatabase::factory('ShopwareCloudCategoryRelation');
    }

    /**
     * @return ML_ShopwareCloud_Model_Table_ShopwareCloudProductData|ML_Database_Model_Table_Abstract
     */
    public static function getProductDataModel() {
        return MLDatabase::factory('ShopwareCloudProductData');
    }

    /**
     * @return ML_ShopwareCloud_Model_Table_ShopwareCloudTax|ML_Database_Model_Table_Abstract
     */
    public static function getTaxesModel() {
        return MLDatabase::factory('ShopwareCloudTax');
    }

    /**
     * @return ML_ShopwareCloud_Model_Table_ShopwareCloudTax|ML_Database_Model_Table_Abstract
     */
    public static function getCountryModel() {
        return MLDatabase::factory('ShopwareCloudCountry');
    }

    /**
     * @return ML_ShopwareCloud_Model_Table_ShopwareCloudProductManufacturerTranslation|ML_Database_Model_Table_Abstract
     */
    public static function getProductManufacturerTranslationModel() {
        return MLDatabase::factory('ShopwareCloudProductManufacturerTranslation');
    }

    /**
     * @return ML_ShopwareCloud_Model_Table_ShopwareCloudProductTranslation|ML_Database_Model_Table_Abstract
     */
    public static function getProductTranslationModel() {
        return MLDatabase::factory('ShopwareCloudProductTranslation');
    }

    /**
     * @return ML_ShopwareCloud_Model_Table_ShopwareCloudCategory|ML_Database_Model_Table_Abstract
     */
    public static function getCategoryModel() {
        return MLDatabase::factory('ShopwareCloudCategory');
    }

    /**
     * @return ML_ShopwareCloud_Model_Table_ShopwareCloudCategoryRelation|ML_Database_Model_Table_Abstract
     */
    public static function getCategoryRelationModel() {
        return MLDatabase::factory('ShopwareCloudCategoryRelation');
    }

    /**
     * @return ML_ShopwareCloud_Model_Table_ShopwareCloudCategoryTranslation|ML_Database_Model_Table_Abstract
     */
    public static function getCategoryTranslationModel() {
        return MLDatabase::factory('ShopwareCloudCategoryTranslation');
    }

    /**
     * get Shopware 6 price helper class
     * @return ML_ShopwareCloud_Model_Price|Object
     */
    public static function getPriceModel(){
        return MLPrice::factory();
    }
    /**
     * get Shopware Cloud price helper class
     * @return ML_ShopwareCloud_Helper_Model_Price|Object
     */
    public static function getPriceHelper(){
        return MLHelper::gi('model_price');
    }
    /**
     * Get a new instace of magnalister Shopware 6 shop order helper class
     * @return ML_ShopwareCloud_Helper_Model_ShopOrder|Object
     */
    public static function getShopOrderHelper(){
        return MLHelper::factory('model_shoporder');
    }


    /**
     * get Shopware 6 shop order helper class
     * @return ML_ShopwareCloud_Model_Http|Object
     */
    public static function getHttpModel(){
        return MLHttp::gi();
    }

    /**
     * @return ML_ShopwareCloud_Model_Product|ML_Shop_Model_Product_Abstract
     */
    public static function getProductModel(){
        return MLProduct::factory();
    }

    /**
     * @return ML_ShopwareCloud_Model_Order
     */
    public static function getOrderModel(): \ML_ShopwareCloud_Model_Order {
        return MLOrder::factory();
    }

    /**
     * @return library\request\shopware\ShopwareCurrency
     */
    public static function getDefaultShopSystemCurrencyData(){
        return MLDatabase::factorySelectClass()
            ->select('*')
            ->from('magnalister_shopwarecloud_currency' )
            ->where("`ShopwareFactor` = '1'")
            ->getRowResult();
    }

    /**
     * @return library\request\shopware\ShopwareCurrency
     */
    public static function getShopwareCurrencyByIsoCode($IsoCode) {
        return MLDatabase::factorySelectClass()
            ->select('*')
            ->from('magnalister_shopwarecloud_currency')
            ->where("`ShopwareIsoCode` = '$IsoCode'")
            ->getRowResult();
    }


}
