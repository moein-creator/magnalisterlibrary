<?php
/*
 * 888888ba                 dP  .88888.                    dP
 * 88    `8b                88 d8'   `88                   88
 * 88aaaa8P' .d8888b. .d888b88 88        .d8888b. .d8888b. 88  .dP  .d8888b.
 * 88   `8b. 88ooood8 88'  `88 88   YP88 88ooood8 88'  `"" 88888"   88'  `88
 * 88     88 88.  ... 88.  .88 Y8.   .88 88.  ... 88.  ... 88  `8b. 88.  .88
 * dP     dP `88888P' `88888P8  `88888'  `88888P' `88888P' dP   `YP `88888P'
 *
 *                            m a g n a l i s t e r
 *                                        boost your Online-Shop
 *
 *   -----------------------------------------------------------------------------
 *   @author magnalister
 *   @copyright 2010-2022 RedGecko GmbH -- http://www.redgecko.de
 *   @license Released under the MIT License (Expat)
 *   -----------------------------------------------------------------------------
 */

//MLFilesystem::gi()->loadClass('Shop_Model_Shop_Abstract');
class ML_WooCommerce_Model_Shop extends ML_Shop_Model_Shop_Abstract {
    protected $sSessionID = null;

    public function getShopSystemName() {
        return 'woocommerce';
    }

    public function getDbConnection() {
        global $wpdb;
        $sMlConnection = array(
            'host' => isset($wpdb->dbhost) ? $wpdb->dbhost : DB_HOST,
            'user' => isset($wpdb->dbuser) ? $wpdb->dbuser : DB_USER,
            'password' => isset($wpdb->dbpassword) ? $wpdb->dbpassword : DB_PASSWORD,
            'database' => isset($wpdb->dbname) ? $wpdb->dbname : DB_NAME,
            'port' => false
        );

        return $sMlConnection;
    }

    public function initializeDatabase() {
        global $wpdb;
        if (property_exists($wpdb, 'charset')) {
            MLDatabase::getDbInstance()->setCharset($wpdb->charset);
        }

        return $this;
    }

    public function getOrderSatatistic($sDateBack) {
        global $wpdb;
        $aOut = MLDatabase::getDbInstance()->fetchArray("
           SELECT * FROM (
                SELECT so.`post_date`, mo.`platform` as `platform`, so.id
                  FROM {$wpdb->posts} so
            INNER JOIN `magnalister_orders` mo ON so.`id` = mo.`current_orders_id`
                 WHERE (so.`post_date` BETWEEN '$sDateBack' AND NOW()) AND so.post_type = 'shop_order'
                 
                 UNION all
                 
                SELECT so.`post_date`, null as`platform`, so.id
                  FROM {$wpdb->posts} so
                 WHERE (so.`post_date` BETWEEN '$sDateBack' AND NOW()) AND so.post_type = 'shop_order'
            ) AS T
            Group by T.id
        ");

        return $aOut;
    }

    public function getSessionId() {
        if ($this->sSessionID == null) {
            if (is_admin()) {
                if (function_exists('wp_get_session_token')) {
                    $sId = md5(wp_get_session_token());
                } else {
                    $current_user = wp_get_current_user();
                    $sId = $current_user->ID; //wp doesn't have sessions so we will use user id
                }
            } else {
                $sId = 'frontsession';
            }
            $this->sSessionID = md5($sId);
        }

        return $this->sSessionID;
    }

    public function getProductsWithWrongSku() {
        return array();
    }

    /**
     * will be triggered after plugin update for shop-spec. stuff
     * eg. clean shop-cache
     *
     * @param bool $blExternal if true external files (outside of plugin-folder) was updated
     *
     * @return $this
     */
    public function triggerAfterUpdate($blExternal) {
        return $this;
    }

    /**
     * For fix collations script in MISC main_tools
     * @return array
     */
    public function getDBCollationTableInfo() {
        global $wpdb;

        return array(
            'table' => $wpdb->posts,
            'field' => 'post_status',
        );
    }

    public function isCurrencyMatchingNeeded() {
        return true;
    }


    /**
     * @inheritDoc
     */
    public function getTimeZone() {
        return wp_timezone_string();
    }

    public function getShopVersion() {
        return WC()->version;
    }
}
