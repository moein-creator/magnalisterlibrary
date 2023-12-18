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

class ML_Prestashop_Model_Shop extends ML_Shop_Model_Shop_Abstract {

    public function getShopSystemName() {
        return 'prestashop';
    }
    
    public function getShopVersion() {
         $aVersion = explode('.', _PS_VERSION_);
         return "{$aVersion[1]}" ;
    }

    public function getDbConnection() {
        $aReturn = array(
            'user' => _DB_USER_,
            'password' => _DB_PASSWD_,
            'database' => _DB_NAME_,
            'persistent' => false
        );
        if (
            strpos(_DB_SERVER_, ':') === false 
            || !is_numeric(substr(_DB_SERVER_, strpos(_DB_SERVER_, ':') + 1))//socket
        ) {
            $aReturn['host'] = _DB_SERVER_;
        } else {
            $aReturn['host'] = substr(_DB_SERVER_, 0, strpos(_DB_SERVER_, ':'));
            $aReturn['port'] = substr(_DB_SERVER_, strpos(_DB_SERVER_, ':') + 1);
        }
        return $aReturn;
    }
    
    /**
     * hardcoded utf8
     * @see MySQLCore::connect || DbMySQLiCore::connect
     * @return \ML_Prestashop_Model_Shop
     */
    public function initializeDatabase () {
        MLDatabase::getDbInstance()->setCharset('utf8');
        return $this;
    }

    public function getProductsWithWrongSku() {
        return array();
    }

    public function getOrderSatatistic($sDateBack) {
        $oMLQB = MLDatabase::factorySelectClass();
        $result= $oMLQB->select(array('date_add', 'mo.platform'))
                                ->from(_DB_PREFIX_ . 'orders')
                                ->join(array('magnalister_orders', 'mo', 'id_order = mo.current_orders_id'), ML_Database_Model_Query_Select::JOIN_TYPE_INNER)
                                ->where("date_add BETWEEN '$sDateBack' AND NOW()")->getResult();
        return $result;
    }

    public function getSessionId() {
        // Context::getContext()->cookie->checksum doesn't works as we expected correctly , it changes sometime
        // 
//        if (isset(Context::getContext()->cookie->checksum) && Context::getContext()->cookie->checksum != '') {
//            return md5(Context::getContext()->cookie->checksum);
//        } else
        if (isset(Context::getContext()->employee->id)) {
            return md5(Context::getContext()->employee->id.$this->getRandomNumberForEachCookie());
        } elseif (isset(Context::getContext()->cookie->id_employee)) {
            return md5(Context::getContext()->cookie->id_employee.$this->getRandomNumberForEachCookie());
        } elseif(isset(Context::getContext()->customer) && isset (Context::getContext()->customer->id)) {
            return md5(Context::getContext()->customer->id);
        }else{
            return 'tempsessionid____123';
        }
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
    
    
    protected function getRandomNumberForEachCookie(){
        $aOut = array();
        foreach (array('date_add', 'id_employee', /*'email', 'profile', 'passwd', 'remote_addr',*/ ) as $sessionKey) {
            $aOut[$sessionKey] = Context::getContext()->cookie->{$sessionKey};
        }
        return md5(json_encode($aOut));
    }


    public function getPluginVersion(){
        if(class_exists('Magnalister')){
            $oPlugin = new Magnalister();
            return $oPlugin->version;
        } else {//prestashop >= 1.7
            $moduleManagerBuilder = \PrestaShop\PrestaShop\Core\Addon\Module\ModuleManagerBuilder::getInstance();
            $moduleRepository = $moduleManagerBuilder->buildRepository();
            $oPlugin = $moduleRepository->getModule('Magnalister');
            return $oPlugin->get('version');
        }
    }

    public function getDBCollationTableInfo() {
        return array(
            'table' => _DB_PREFIX_.'product',
            'field' => 'reference',
        );
    }

    /**
     * @inheritDoc
     */
    public function getTimeZone() {
        return Configuration::get('PS_TIMEZONE');
    }

}