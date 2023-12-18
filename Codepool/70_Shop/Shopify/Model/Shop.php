<?php

/**
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
 * (c) 2010 - 2020 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */
class ML_Shopify_Model_Shop extends ML_Shop_Model_Shop_Abstract
{

    const SHOP_SYSTEM_NAME = 'shopify';

    /**
     * Returns shop system name. The same one must be registered
     * in magnalister API Server side.
     *
     * @return string
     */
    public function getShopSystemName()
    {
        return self::SHOP_SYSTEM_NAME;
    }

    /**
     * Returns database connection as array.
     *
     * @return array
     */
    public function getDbConnection()
    {
        $aDatabaseConnection = [
            'host'     => MLHelper::gi('database')->getHostName(),
            'user'     => MLHelper::gi('database')->getUserName(),
            'password' => MLHelper::gi('database')->getPassword(),
            'database' => MLHelper::gi('database')->getDatabaseName(),
            'port'     => MLHelper::gi('database')->getport()
        ];

        return $aDatabaseConnection;
    }

    /**
     * Initializes database and return $this instance.
     *
     * @return $this
     */
    public function initializeDatabase()
    {
        MLDatabase::getDbInstance()->setCharset('utf8');

        return $this;
    }

    /**
     * Returns hashed current session id.
     *
     * @return string
     */
    public function getSessionId()
    {
        return md5(session_id());
    }

    /**
     * Triggers after shop-specific plugin update. The most of
     * of shops have clear cache action.
     *
     * @param bool $blExternal
     *
     * @return $this
     */
    public function triggerAfterUpdate($blExternal)
    {
        return $this;
    }

    /**
     * Returns a list of products with missing or double assigned SKUs.
     *
     * @todo Investigate and implement function.
     *
     * @return array
     */
    public function getProductsWithWrongSku()
    {
        return [];
    }

    /**
     * Returns statistic information of orders.
     *
     * @param string $sDateBack Start date to get order info up to now.
     *
     * @return array
     */
    public function getOrderSatatistic($sDateBack) {
        $oMLQB = MLDatabase::factorySelectClass();
        $result= $oMLQB->select(array('insertTime', 'platform'))
                       ->from('magnalister_orders')
                       ->where("insertTime BETWEEN '$sDateBack' AND NOW()")->getResult();
        return $result;
    }

    /**
     * get table name and field name to get collation of shop database
     * @return array
     */
    public function getDBCollationTableInfo() {
        return array();
    }

    public function getShopCronActions() {
        return ['ShopifyProductCache', 'ShopifyDeleteProductCache', 'ShopifyProductCollectionCache'];
    }
}
