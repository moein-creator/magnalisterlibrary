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

class MLShopifyAlias {

    /**
     * get shopify product helper class
     * @return ML_Shopify_Helper_Model_Product|Object
     */
    public static function getProductHelper(){
        return MLHelper::gi('model_product');
    }

    /**
     * get shopify price helper class
     * @return ML_Shopify_Helper_Model_Price|Object
     */
    public static function getPriceHelper(){
        return MLHelper::gi('model_price');
    }
    /**
     * get shopify shop helper class
     * @return ML_Shopify_Helper_Model_Shop|Object
     */
    public static function getShopHelper(){
        return MLHelper::gi('model_shop');
    }

    /**
     * @return ML_Shopify_Model_Product|ML_Shop_Model_Product_Abstract
     */
    public static function getProductModel() {
        return MLProduct::factory();
    }

    /**
     * @return ML_Shopify_Model_Table_ShopifyCollection|ML_Database_Model_Table_Abstract
     */
    public static function getCollectionModel() {
        return MLDatabase::factory('ShopifyCollection');
    }

    /**
     * @return ML_Shopify_Model_Table_ShopifyProductCollectionRelation|ML_Database_Model_Table_Abstract
     */
    public static function getProductCollectionRelationModel() {
        return MLDatabase::factory('ShopifyProductCollectionRelation');
    }

    /**
     * @return ML_Shopify_Model_Table_ShopifyMetaField|ML_Database_Model_Table_Abstract
     */
    public static function getMetaFieldModel() {
        return MLDatabase::factory('ShopifyMetaField');
    }

    /**
     * @return ML_Shopify_Model_Table_ShopifyObjectMetaFieldRelation|ML_Database_Model_Table_Abstract
     */
    public static function getObjectMetaFieldRelationModel() {
        return MLDatabase::factory('ShopifyObjectMetaFieldRelation');
    }

    /**
     * @return ML_Shopify_Model_Table_ShopifyProductVendor|ML_Database_Model_Table_Abstract
     */
    public static function getProductVendorModel() {
        return MLDatabase::factory('ShopifyProductVendor');
    }

    /**
     * @return ML_Shopify_Model_Table_ShopifyProductData|ML_Database_Model_Table_Abstract
     */
    public static function getProductDataModel() {
        return MLDatabase::factory('ShopifyProductData');
    }

    /**
     * @return ML_Shopify_Model_Product|ML_Database_Model_Table_Abstract
     */
    public static function getShopifyProductTable() {
        return MLDatabase::factory('shopifyproduct');
    }


    /**
     * @return ML_Shopify_Helper_Model_ShopOrder|Object
     */
    public static function getShopOrderHelper() {
        return MLHelper::gi('Model_ShopOrder');
    }
}