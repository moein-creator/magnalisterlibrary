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

class ML_ZzzzDummy_Model_Shop extends ML_Shop_Model_Shop_Abstract {
    
    public function getShopSystemName(){
        return 'zzzzdummy';
    }
    
    public function getDbConnection(){
        return json_decode(ZZZZDUMMY_DB_CONNECTION, true);
    }
    public function initializeDatabase () {
        MLDatabase::getDbInstance()->setCharset('utf8');
        return $this;
    }
    public function getProductsWithWrongSku(){
        return MLDatabase::getDbInstance()->fetchArray("
            SELECT
                `sku`
            FROM
                `".MLDatabase::factory('ZzzzDummyShopProduct')->getTableName()."`
            GROUP BY 
                `sku`
            HAVING 
                COUNT(`sku`) > 1
            ;
        ", true);
    }

    public function getSessionId() {
        return md5(session_id());
    }
    
    /**
     * will be triggered after plugin update for shop-spec. stuff
     * eg. clean shop-cache
     * @param bool $blExternal if true external files (outside of plugin-folder) was updated
     * @return $this
     */
    public function triggerAfterUpdate($blExternal) {
        return $this;
    }

    /**
     * @todo: order is not implemented
     */
    public function getOrderSatatistic($sDateBack) {
        $aOut = array();
        return $aOut; 
    }
    public function getDBCollationTableInfo() {
        
        throw new Exception('not implemented');
    }
    
}
