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
 * $Id$
 *
 * (c) 2010 - 2017 RedGecko GmbH -- http://www.redgecko.de/
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 *
 * Class ML_Shopify_Helper_Database
 *
 */
class ML_Shopify_Helper_Database
{

    const DATABASE_DEFAULT_HOSTNAME = 'localhost';
    const DATABASE_DEFAULT_PORT     = '';

    /**
     * Returns database configuration of main database.
     * The user name and password will be the same for all
     * newly created customer databases.
     *
     * @return array Example is shown below.
     *
     *      ...
     *      [
     *           'host'     => 'localhost',
     *           'username' => 'root',
     *           'password' => 'root',
     *           'database' => 'shopify_prod_redgecko_magnalister_loc'
     *      ],
     *      ...
     */
    public function getConfiguration()
    {
        $aConfiguration = MLHelper::gi('container')->getSettings();

        return $aConfiguration['database'];
    }

    /**
     * Returns database user name.
     *
     * @return string
     */
    public function getUserName()
    {
        $aDatabaseConfiguration = $this->getConfiguration();

        return $aDatabaseConfiguration['username'];
    }

    /**
     * Returns database password.
     *
     * @return string
     */
    public function getPassword()
    {
        $aDatabaseConfiguration = $this->getConfiguration();

        return $aDatabaseConfiguration['password'];
    }

    /**
     * Returns customer's database name.
     *
     * @return string
     */
    public function getDatabaseName()
    {
        $sShopId        = MLHelper::gi('model_shop')->getShopId();
        $oCustomerModel = MLHelper::gi('container')->getCustomerModel();

        $oCustomer = $oCustomerModel->getOne($sShopId);

        return $oCustomer->DatabaseName;
    }

    /**
     * Returns default host name.
     *
     * @return string
     */
    public function getHostName()
    {
        return self::DATABASE_DEFAULT_HOSTNAME;
    }

    /**
     * Returns default port.
     *
     * @return string
     */
    public function getPort()
    {
        return self::DATABASE_DEFAULT_PORT;
    }

}