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



class MLMagentoAlias {

    /**
     * get Magento product helper class
     * @return ML_Magento_Helper_Model_Product|Object
     */
    public static function getProductHelper(){
        return MLHelper::gi('model_product');
    }
    /**
     * get Magento price helper class
     * @return ML_Magento_Model_Price|Object
     */
    public static function getPriceModel(){
        return MLPrice::factory();
    }

    /**
     * get Magento shop order helper class
     * @return ML_Magento_Helper_Model_ShopOrder|Object
     */
    public static function getShopOrderHelper(){
        return MLHelper::gi('model_shoporder');
    }


    /**
     * get Magento shop order helper class
     * @return ML_Magento_Model_Http|Object
     */
    public static function getHttpModel(){
        return MLHttp::gi();
    }

    /**
     * @return ML_Magento_Model_Product|ML_Shop_Model_Product_Abstract
     */
    public static function getProductModel(){
        return MLProduct::factory();
    }


    /**
     * @return ML_Magento_Model_Order|ML_Shop_Model_Order_Abstract
     */
    public static function getOrderModel() {
        return MLOrder::factory();
    }

}