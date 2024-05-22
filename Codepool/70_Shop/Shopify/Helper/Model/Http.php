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

class ML_Shopify_Helper_Model_Http
{

    /**
     * Returns query string.
     *
     * @return string e.g. ?variable1=1&variable2=2&variable3=3...
     */
    public function getQueryString()
    {
        return $_SERVER['QUERY_STRING'];
    }

    /**
     * Returns query string as an array.
     *
     * @return array Example is shown below.
     *      [
     *          variable1 => 1,
     *          variable2 => 2,
     *          variable3 => 3
     *      ]
     */
    public function getQueryStringAsArray()
    {
        parse_str($this->getQueryString(), $aQueryString);

        return $aQueryString;
    }

    /**
     * Returns shop id from calculated query string.
     *
     * @return string e.g. my-store.myshopify.com
     */
    public function getShopIdFromQueryString()
    {
        $aQueryString = $this->getQueryStringAsArray();
        if(isset($aQueryString['shop'])) {
            $shopId = $aQueryString['shop'];
        } elseif (isset($_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN'])){
            $shopId = $_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN'];
        } else {
            throw new \Exception('Url is set wrong', 1557761192);
        }

        return $shopId;
    }

}
