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
 * Class ML_Shopify_Helper_Container
 *
 */
class ML_Shopify_Helper_Container
{

    /**
     * Returns "Slim Router" container.
     *
     * @return Container The object contains instances:
     *
     *  - $object->get('settings') Static settings.
     *  - $object->get('pdo') PDO instance.
     *  - $object->get('customer') Customer model.
     *  - $object->get('view') Twig instance.
     */
    public function getContainer()
    {
        return $GLOBALS['APP_CONTAINER'];
    }

    /**
     * Returns settings as an array.
     *
     * @return array Example is shown below.
     *       ...
     *
     *       'git' => [
     *           'url' => '#',
     *           'branch' => 'master'
     *       ],
     *       'database' => [
     *           'host'     => 'localhost',
     *           'username' => 'root',
     *           'password' => 'root',
     *           'database' => 'shopify_prod_redgecko_magnalister_loc'
     *       ],
     *
     *       ...
     */
    public function getSettings()
    {
        return $this->getContainer()->get('settings');
    }

    /**
     * Returns customer model.
     *
     * Model contains all functions necessary for customers manipulation.
     *
     * @return CustomerModel The object contains functions:
     *
     *  - ...
     *  - getAll(),
     *  - getOne($customerId),
     *  - updateAccessToken($customerId, $accessToken),
     *  - ...
     */
    public function getCustomerModel()
    {
        return $this->getContainer()->get('customer');
    }

    /**
     * Returns new PDO instance.
     *
     * @link http://php.net/manual/en/book.pdo.php
     *
     * @return PDO
     */
    public function getPdo()
    {
        return $this->getContainer()->get('pdo');
    }

}