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
 * (c) 2010 - 2023 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */


class ML_Shopwarecloud_Model_Shop extends ML_Shop_Model_Shop_Abstract
{

    protected static $sSession;


    public function __construct() {

    }

    public function getShopSystemName()
    {
        return 'shopwarecloud';
    }

    public function getDbConnection()
    {
        global $config;
        $data = CustomerHelper::gi()->getCustomer($_GET['shop-id']);
        if ($data['DatabasePassword'] == null) {
            CustomerHelper::gi()->createDBUser($data['CustomerName'], $_GET['shop-id']);
            $data = CustomerHelper::gi()->getCustomer($_GET['shop-id'], true);
        }

        $aDatabaseConnection = [
            'host'     => $config['db']['host'],
            'port'     => $config['db']['port'],
            'user'     => $data['CustomerName'],
            'password' => $data['DatabasePassword'],
            'database' => $data['CustomerName'],
            'encrypt'  => $config['db']['encrypt'] ?? array(),
        ];

        return $aDatabaseConnection;
    }

    public function initializeDatabase()
    {
        MLDatabase::getDbInstance()->setCharset('utf8');
        return $this;
    }

    public function getOrderSatatistic($sDateBack)
    {
        $oMLQB = MLDatabase::factorySelectClass();
        $result= $oMLQB->select(array('insertTime', 'platform'))
            ->from('magnalister_orders')
            ->where("insertTime BETWEEN '$sDateBack' AND NOW()")->getResult();
        return $result;
    }

    public function getSessionId()
    {
        if (self::$sSession === null) {
            self::$sSession = md5(session_id());
        }
        return self::$sSession;
    }


    public function getProductsWithWrongSku()
    {
        return array();
    }

    /**
     * will be triggered after plugin update for shop-spec. stuff
     * eg. clean shop-cache
     * @param bool $blExternal if true external files (outside of plugin-folder) was updated
     * @return $this
     */
    public function triggerAfterUpdate($blExternal)
    {
        return $this;
    }

    public function getDBCollationTableInfo()
    {
        return array(
            'table' => 'magnalister_products',
            'field' => 'ProductsSku',
        );
    }

    public function getShopNameForMarketingContent() {
        return 'shopware';
    }

    public function getShopCronActions() {
        return [
            'ShopwareCloudProductCache',
            'ShopwareCloudDeleteProductCache',
            'ShopwareCloudCategoryCache',
            'ShopwareCloudDeleteCategoryCache',
            'ShopwareCloudManufacturerCache',
            'ShopwareCloudDeleteManufacturerCache',
            'ShopwareCloudPropertyGroupCache',
            'ShopwareCloudPropertyGroupOptionCache',
            'ShopwareCloudDeletePropertyGroupCache',
            'ShopwareCloudCustomFieldsCache',
            'ShopwareCloudDeleteCustomFieldsCache',
            'ShopwareCloudLanguageCache',
            'ShopwareCloudDeleteLanguageCache',
            'ShopwareCloudShippingMethodCache',
            'ShopwareCloudDeleteShippingMethodCache',
            'ShopwareCloudSalesChannelCache',
            'ShopwareCloudDeleteSalesChannelCache',
            'ShopwareCloudCustomerGroupCache',
            'ShopwareCloudDeleteCustomerGroupCache',
            'ShopwareCloudStateMachineStateCache',
            'ShopwareCloudDeleteStateMachineStateCache',
            'ShopwareCloudPaymentMethodCache',
            'ShopwareCloudDeletePaymentMethodCache',
            'ShopwareCloudCountryCache',
            'ShopwareCloudCountryStateCache',
            'ShopwareCloudDeleteCountryCache',
            'ShopwareCloudTaxCache',
            'ShopwareCloudTaxRuleCache',
            'ShopwareCloudDeleteTaxCache',
            'ShopwareCloudUnitCache',
            'ShopwareCloudDeleteUnitCache',
            'ShopwareCloudCurrencyCache',
            'ShopwareCloudDeleteCurrencyCache',
            'ShopwareCloudDocumentCache',
        ];
    }

    public function addShopMessages() {
        /** @var ML_ShopwareCloud_Controller_Frontend_Do_ShopwareCloudProductCache $oController */
        $oController = ML::gi()->instance('ShopwareCloud_Controller_Frontend_Do_ShopwareCloudProductCache');
        $iImportedProductPercent = round($oController->getDoneProgress(), 2);
        $sFirstProductImport = MLDatabase::factory('config')->set('mpid', 0)->set('mkey', 'shopwareCloudStartingFirstProductImport')->get('value');
        if ($iImportedProductPercent < 100) {
            if ($sFirstProductImport === '1') {
                MLMessage::gi()->addWarn(MLI18n::gi()->get('installation_product_import_progress_message', ['iImportedProductPercent' => $iImportedProductPercent]));
            } else {
                MLMessage::gi()->addDebug(MLI18n::gi()->get('installation_product_import_progress_message', ['iImportedProductPercent' => $iImportedProductPercent]));
            }

        }
    }

}
