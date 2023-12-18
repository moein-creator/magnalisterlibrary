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

class ML_Shopware_Model_Shop extends ML_Shop_Model_Shop_Abstract {
    protected $sSessionID = null;
    /** @var Shopware\Models\Shop\Shop */
    protected $oDefaultShop = null;

    public function __construct() {
        $oShop = Shopware()->Models()->getRepository('Shopware\Models\Shop\Shop')->getActiveDefault();

        if (!defined('MLSHOPWAREVERSION')) {
            define('MLSHOPWAREVERSION', Shopware()->Config()->get('Version'));
        }

        try {
            // Shopware 5.7 compatiblity
            if (version_compare(MLSHOPWAREVERSION, '5.7', '>=')) {
                Shopware()->Container()->set('shop', $oShop);
            } else {
                Shopware()->Bootstrap()->registerResource('Shop', $oShop);
            }
        } catch (\Exception $e) {
            // In case of exception do nothing
        }
    }

    public function getShopSystemName() {
        return 'shopware';
    }

    public function isComposerInstallation() {
        $sPath = str_replace(DIRECTORY_SEPARATOR, '/', dirname(__FILE__));
        $aMatch = array();
        if (!preg_match('(\/engine\/.*\/[^\/]*Magnalister\/Lib\/)', $sPath, $aMatch)) {
            if (strpos($sPath, '/Plugins/Community/Backend/RedMagnalister/Lib/') !== false) { // if using Shopware git / composer installation
                return true; // /Plugins/Community/Backend/RedMagnalister/Lib/
            } else { // if we are using symbolic link for magnalister in another directory or default installation path
                return false; // /engine/Shopware/Plugins/Community/Backend/RedMagnalister/
            }
        }

        return false;
    }

    public function getDbConnection() {
        if(method_exists(Shopware(), 'getOption')) {
            $dbConnection = Shopware()->getOption('db');
        } else {/** @since Shopware 5.2 */
            $dbConnection = Shopware()->Container()->getParameter('shopware.db');
        }
        $sMlConnection = array(
            'host' => $dbConnection['host'] . ((isset($dbConnection['unix_socket']) && !empty($dbConnection['unix_socket']) )
                    ? ':' . $dbConnection['unix_socket']
                    : (isset($dbConnection['port'])  &&  $dbConnection['port'] !== ''
                        ? ':' . $dbConnection['port']
                        : ''
                    )
                ), //(string) $dbConnection['host'],
            'user' => (string)$dbConnection['username'],
            'password' => (string)$dbConnection['password'],
            'database' => (string)$dbConnection['dbname'],
            'port' => $dbConnection['port'] //for some server that use port and socket
        );
        return $sMlConnection;
    }

    public function initializeDatabase () {
        if(method_exists(Shopware(), 'getOption')) {
            $aDbConfig = Shopware()->getOption('db');
        } else {/** @since Shopware 5.2 */
            $aDbConfig = Shopware()->Container()->getParameter('shopware.db');
        }
        if (array_key_exists('charset', $aDbConfig)) {
            MLDatabase::getDbInstance()->setCharset($aDbConfig['charset']);
        }
        return $this;
    }

    public function getOrderSatatistic($sDateBack) {
        $oMLQB = MLDatabase::factorySelectClass();
        $sTableName = Shopware()->Models()->getClassMetadata('Shopware\Models\Order\Order')->getTableName();
        $aOut = MLDatabase::getDbInstance()->fetchArray("
           SELECT * FROM (
                SELECT so.`ordertime`, mo.`platform` as `platform`, so.id
                  FROM `s_order` so
            INNER JOIN `magnalister_orders` mo ON so.`id` = mo.`current_orders_id`
                 WHERE (so.`ordertime` BETWEEN '$sDateBack' AND NOW())
                 
                 UNION all
                 
                SELECT so.`ordertime`, null as`platform`, so.id
                  FROM `s_order` so
                 WHERE (so.`ordertime` BETWEEN '$sDateBack' AND NOW())
            ) AS T
            Group by T.id
        ");
        return $aOut;
    }

    public function getSessionId() {
        if ($this->sSessionID == null) {
            if (Shopware()->Front()->Request()->{'module'} == 'backend') {
                Shopware()->Session();
                $oShopware = Shopware();
                if (method_exists($oShopware, 'Container')) {
                    // In Shopware 5.7.2 its "auth" not "Auth" anymore
                    if (version_compare(MLSHOPWAREVERSION, '5.7', '>=')) {
                        $oAuth = Shopware()->Container()->get('auth')->getBaseAdapter();
                    } else {
                        $oAuth = Shopware()->Container()->get('Auth')->getBaseAdapter();
                    }
                } else {
                    $oAuth = Shopware()->Auth()->getBaseAdapter();
                }
                /* @var $oAuth Shopware_Components_Auth_Adapter_Default */
                $sId = $oAuth->getResultRowObject()->sessionID;
            } else {
                $sId = 'frontsession';
            }
            $this->sSessionID = md5($sId);
        }
        return $this->sSessionID;
    }

    /**
     * return default shop in shopware
     * @return Shopware\Models\Shop\Shop
     */
    public function getDefaultShop() {
        if ($this->oDefaultShop === null) {
            try {$oBbuilder = Shopware()->Models()->createQueryBuilder();
                $this->oDefaultShop = Shopware()->Models()->getRepository('Shopware\Models\Shop\Shop')->getActiveDefault();
            } catch (Exception $exc) {
                try {
                    $oBbuilder = Shopware()->Models()->createQueryBuilder();
                    $oQuery = $oBbuilder->select(array('shop'))
                        ->from('Shopware\Models\Shop\Shop', 'shop');
                    $aShops = $oQuery
                        ->getQuery()->getArrayResult();
                    foreach ($aShops as $aShop) {
                        if($aShop['host'] != null){
                            $this->oDefaultShop = Shopware()->Models()->getRepository('Shopware\Models\Shop\Shop')->find($aShop['id']);
                        }
                    }
                } catch (Exception $exc) {

                }
            }
        }
        return $this->oDefaultShop;
    }

    public function getProductsWithWrongSku() {
        return array();
    }

    /**
     * will be triggered after plugin update for shop-spec. stuff
     * eg. clean shop-cache
     * @param bool $blExternal if true external files (outside of plugin-folder) was updated
     * @return $this
     */
    public function triggerAfterUpdate($blExternal) {
        try {
            MLDatabase::getDbInstance()->query("
                UPDATE s_order_shippingaddress sa
                INNER JOIN s_order o on sa.orderid = o.id
                SET sa.userid = o.userid
                WHERE ( 
                    o.userid <> sa.userid
                    OR sa.userid IS NULL
                )
                AND o.id IS NOT null
            ");
        } catch (Exception $oEx) {
            //Model_Db is not part of installer
        }
        return $this;
    }

    public function getDBCollationTableInfo() {
        return array(
            'table'=> Shopware()->Models()->getClassMetadata('Shopware\Models\Article\Detail')->getTableName(),
            'field'=> 'ordernumber',
        );
    }


    public function getPluginVersion(){
        if(defined('MAGNALISTER_PLUGIN_VERSION')){
            return MAGNALISTER_PLUGIN_VERSION;
        }else{
            return parent::getPluginVersion();
        }
    }

}
