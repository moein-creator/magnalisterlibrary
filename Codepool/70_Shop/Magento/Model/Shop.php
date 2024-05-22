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

class ML_Magento_Model_Shop extends ML_Shop_Model_Shop_Abstract {
    
    public function getShopSystemName() {
        return 'magento';
    }
    
    public function getDbConnection() {
        $oConnection = Mage::getConfig()->getNode('global/resources/default_setup/connection');
//        new dBug($oConnection->asArray());
        $aReturn = array(
            'host'      => (string)$oConnection->host,
            'user'      => (string)$oConnection->username,
            'password'  => (string)$oConnection->password,
            'database'  => (string)$oConnection->dbname,
            'persistent' => false
        );

        if (!empty($oConnection->port)) {
            $aReturn['port'] = (int)$oConnection->port;
        }

        return $aReturn;
    }
    
    /**
     * magento dont have defined charset, but init statements with charset info
     * @return \ML_Magento_Model_Shop
     */
    public function initializeDatabase () {
        foreach (Mage::getConfig()->getNode('global/resources/default_setup/connection/initStatements') as $sQuery) {
            MLDatabase::getDbInstance()->query($sQuery);
        }
        return $this;
    }

    public function getProductsWithWrongSku() {
        return array();
    }

    public function getOrderSatatistic($sDateBack) {
        return MLDatabase::factorySelectClass()
            ->select(array('mgo.`created_at`', 'mlo.`platform`'))
            ->from(Mage::getSingleton('core/resource')->getTableName('sales_flat_order'), 'mgo')
            ->join(array('magnalister_orders', 'mlo', 'mgo.`increment_id` = mlo.`current_orders_id`'), ML_Database_Model_Query_Select::JOIN_TYPE_LEFT)
            ->where("mgo.`created_at` BETWEEN '$sDateBack' AND NOW()")
            ->getResult()
        ;
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

    public function initMagentoStore($sStoreId) {
        if (Mage::app()->getStore()->getId() != $sStoreId) {
            Mage::app()->getStore()->clearInstance()->setId($sStoreId);
            Mage::app()->getStore()->load($sStoreId);
        }
        return Mage::app()->getStore();
    }
    
    /**
     * magento can manage that by it self , so don't need it
     * @return boolean
     */
    public function needConvertToTargetCurrency() {
        return false;
    }

    public function getDBCollationTableInfo() {
        return array(
            'table' => Mage::getSingleton('core/resource')->getTableName('catalog_product_entity'),
            'field' => 'sku',
        );
    }

    /**
     * @inheritDoc
     */
    public function getTimeZone() {
        return Mage::getStoreConfig('general/locale/timezone');
    }

    public function getShopVersion() {
        return Mage::getVersion();
    }
}
