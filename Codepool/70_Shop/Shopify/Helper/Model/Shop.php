<?php

use Shopify\API\Application\Application;

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
 * Class ML_Shopify_Helper_Model_Shop
 *
 */
class ML_Shopify_Helper_Model_Shop
{

    /**
     * Returns shop id from calculated query string.
     * Shop id is a string, and presents customer's
     * domain name.
     *
     * @return string e.g. my-store.myshopify.com
     */
    public function getShopId()
    {
        $sShopId = MLHelper::gi('model_http')->getShopIdFromQueryString();

        return $sShopId;
    }

    /**
     * Returns shop configuration
     *
     * @return array
     */
    public function getShopConfigurationAsArray()
    {
        $sShopId = MLHelper::gi('model_shop')->getShopId();
        $application = new Application($sShopId);
        $sToken = MLHelper::gi('container')->getCustomerModel()->getAccessToken($sShopId);
        $aShop = $application->getShopRequest($sToken)->getSingleShop()->getBodyAsArray();

        if (empty($aShop['shop'])) {
            return array();
        } else {
            return $aShop['shop'];
        }
    }

    public function getDefaultCountry() {

        $sShopId = MLHelper::gi('model_shop')->getShopId();
        $application = new Application($sShopId);
        $sToken = MLHelper::gi('container')->getCustomerModel()->getAccessToken($sShopId);
        $aShop = $application->getShopRequest($sToken)->getSingleShop()->getBodyAsArray()['shop'];
        return $aShop['country'] ?? null;
    }

}
