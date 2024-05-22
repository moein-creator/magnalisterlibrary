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


include_once(DIR_MAGNALISTER_HELPER . 'APIHelper.php');
include_once(M_DIR_LIBRARY . 'request/shopware/ShopwareCurrency.php');

use library\request\shopware\ShopwareCurrency;
class ML_ShopwareCloud_Helper_Model_Price {

    private $cache = array();

    public function getPriceByCurrency($mValue, $sCurrency = null, $blFormatted = false) {
        $format = array();

        // build up cache
        if (!array_key_exists('getDefaultShopSystemCurrencyData', $this->cache)) {
            $this->cache['getDefaultShopSystemCurrencyData'] = MLShopwareCloudAlias::getDefaultShopSystemCurrencyData();
        }

        $oShopConfiguration = $this->cache['getDefaultShopSystemCurrencyData'];

        // reads the currency marketplace
        $currencyMarketplace = mlmodule::gi()->getConfig('currency');
        // create cache also for this query
        if (!array_key_exists('getShopwareCurrencyByIsoCode'.$currencyMarketplace, $this->cache)) {
            $this->cache['getShopwareCurrencyByIsoCode'.$currencyMarketplace] = MLShopwareCloudAlias::getShopwareCurrencyByIsoCode($currencyMarketplace);
        }
        $MarketplaceConfiguration = $this->cache['getShopwareCurrencyByIsoCode'.$currencyMarketplace];

        // Compare the currency of the webshop and marketplace
        if ($currencyMarketplace != null && $oShopConfiguration['ShopwareIsoCode'] !== $currencyMarketplace) {
            $format = MLCurrency::gi()->getList()[ $MarketplaceConfiguration['ShopwareIsoCode'] ];
        } else {
            $format = MLCurrency::gi()->getList()[ $MarketplaceConfiguration['ShopwareIsoCode']];
        }

        $mValue = (float)$mValue;
        $decimalPlaces = $format['decimal_places'] != "" ? $format['decimal_places'] : 2;
        $decimalPoint = $format['decimal_point'];
        $thousandsPoint =  $format['thousands_point'] ;
        $mValue = number_format($mValue, $decimalPlaces, $decimalPoint, $thousandsPoint);

        if ($sCurrency != null || $blFormatted == true) {
            $mValue =  $format['symbol_left'] . $mValue . $format['symbol_right'];
            return $mValue;
        } else if ($sCurrency === null) {
            $oMLPrice = MLPrice::factory();
            $mValue = $oMLPrice->unformat($mValue);

            return $mValue;
        }

        return $mValue;
    }

}
