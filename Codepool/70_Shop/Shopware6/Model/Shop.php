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

use Redgecko\Magnalister\Controller\MagnalisterController;
use Shopware\Core\Framework\Context;

class ML_Shopware6_Model_Shop extends ML_Shop_Model_Shop_Abstract {

    protected static $sSession;
    public function getShopSystemName() {
        return 'shopware6';
    }

    public function getDbConnection() {        
        $sMlConnection = MagnalisterController::getShopwareConnection()->getParams();
        $sMlConnection['database'] = $sMlConnection['dbname'];
        return $sMlConnection;
    }

    public function initializeDatabase () {
        MLDatabase::getDbInstance()->setCharset(MagnalisterController::getShopwareConnection()->getParams()['charset']);
        return $this;
    }
  
    public function getOrderSatatistic($sDateBack) {
        $result = MLDatabase::getDbInstance()->fetchArray("
           SELECT * FROM (
                SELECT so.`order_date_time`, mo.`platform` as `platform`, HEX(so.`id`) AS `id`
                  FROM `order` so
            INNER JOIN `magnalister_orders` mo ON HEX(so.`id`) = mo.`current_orders_id`
                 WHERE (so.`order_date_time` BETWEEN '$sDateBack' AND NOW()) AND so.`version_id` =  UNHEX('" . Context::createDefaultContext()->getVersionId() . "')
                 
                 UNION all
                 
                SELECT so.`order_date_time`, null as`platform`, HEX(so.`id`) AS `id`
                  FROM `order` so
                 WHERE (so.`order_date_time` BETWEEN '$sDateBack' AND NOW())  AND so.`version_id` =  UNHEX('" . Context::createDefaultContext()->getVersionId() . "')
            ) AS T
            Group by T.id
        ");

        return $result;
    }

    public function getSessionId() {
        if (self::$sSession === null) {
            if (MagnalisterController::getShopwareUserId() !== null) {
                self::$sSession = md5(session_id());
            } else {
                self::$sSession = 'shopware6sessionid____6';
            }
        }
        return self::$sSession;
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
        return $this;
    }

    public function getDBCollationTableInfo() {
        return array(
            'table'=> MagnalisterController::getShopwareMyContainer()->get('product.repository')->getDefinition()->getEntityName(),
            'field'=> 'product_number',
        );
    }

    /**
     * Returns the Shopware 6 Plugin Version based on the composer.json file of shopspecific
     *
     * @return string
     */
    public function getPluginVersion(){
        $path = MagnalisterController::getShopwareMyContainer()->get('kernel')->locateResource('@RedMagnalisterSW6');
        $composerFile = realpath($path.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'composer.json');

        if (file_exists($composerFile)) {
            $jsonString = file_get_contents($composerFile);
            $jsonObject = json_decode($jsonString);
            return str_replace('v', '', $jsonObject->version);
        }

       return parent::getPluginVersion();
    }

}
