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

class ML_Shopify_Helper_Model_Price {

    public function getPriceByCurrency($mValue, $sCurrency = null, $blFormatted = false, $applyExchangeRate = true) {
        $exchangeRate = 1;

        // cache the ShopConfiguration (to prevent errors from Shipify)
        $sShopConfigurationCacheKey = 'shopConfigurationAsArray';
        if (!MLCache::gi()->exists($sShopConfigurationCacheKey)) {
            $oShopConfiguration = MLCurrency::gi()->getShopConfigurationAsArray();
            MLCache::gi()->set($sShopConfigurationCacheKey, $oShopConfiguration, 30 * 60);
        } else {
            $oShopConfiguration = MLCache::gi()->get($sShopConfigurationCacheKey);
        }

        // reads the currency marketplace
        $currencyMarketplace = MLModule::gi()->getConfig('currency');

        // Compare the currency of the webshop and marketplace
        if ($currencyMarketplace != null && $oShopConfiguration['currency'] != $currencyMarketplace) {

            // only apply when exchange rate needs to be applied
            if ($applyExchangeRate) {
                $cacheKey = 'currencyExchangeRate'.$oShopConfiguration['currency'].$currencyMarketplace;

                // checks if cache does not exist
                if (!MLCache::gi()->exists($cacheKey)) {
                    $currencyExchangeRate = MagnaConnector::gi()->submitRequest(array(
                        'ACTION'    => 'GetExchangeRate',
                        'SUBSYSTEM' => 'Core',
                        'FROM'      => $oShopConfiguration['currency'],
                        'TO'        => $currencyMarketplace,
                    ));
                    // Creates cache
                    MLCache::gi()->set($cacheKey, $currencyExchangeRate, 4 * 60 * 60);

                    // exchange rate difference between webshop and marketplace
                    $exchangeRate = $currencyExchangeRate['EXCHANGERATE'];
                } else {
                    $exchangeRate = MLCache::gi()->get($cacheKey)['EXCHANGERATE'];
                }
            }

            // sets currency for marketplace in default (listCurrencies) currency setting
            //$format = MLCurrency::gi()->getList()[ $currencyMarketplace ];

            //sets currency for marketplace, setting for shop only currency sign change
            $format = MLCurrency::gi()->getList()[$oShopConfiguration['currency']];

            $symbolCurrencyMarketplace = MLCurrency::gi()->getCurrencySymbol($currencyMarketplace);
            $symbolShopConfiguration = MLCurrency::gi()->getCurrencySymbol($oShopConfiguration['currency']);

            $format['symbol_left'] = str_replace($symbolShopConfiguration, $symbolCurrencyMarketplace, $format['symbol_left']);
            $format['symbol_right'] = str_replace($symbolShopConfiguration, $symbolCurrencyMarketplace, $format['symbol_right']);

        } else {
            $format = MLCurrency::gi()->getList()[$oShopConfiguration['currency']];
        }

        // account for the exchange rate
        $mValue = $mValue * $exchangeRate;

        $mValue = (float)$mValue;

        $decimalPlaces = $format['decimal_places'] != "" ? $format['decimal_places'] : 2;
        $decimalPoint = $format['decimal_point'] != "" ? $format['decimal_point'] : '.';
        $thousandsPoint = $format['thousands_point'] != "" ? $format['thousands_point'] : ',';
        $mValue = number_format($mValue, $decimalPlaces, $decimalPoint, $thousandsPoint);

        if ($sCurrency != null || $blFormatted == true) {
            $mValue = $format['symbol_left'].$mValue.$format['symbol_right'];
        } else if ($sCurrency === null) {
            $oMLPrice = MLPrice::factory();
            $mValue = $oMLPrice->unformat($mValue);
        }

        return $mValue;
    }

    public function convertPriceFromToCurrency($mValue, $sFromCurrency, $sToCurrency) {
        $exchangeRate = 1;

        if ($sFromCurrency !== $sToCurrency) {
            $exchangeRate = $this->getExchangeRate($sFromCurrency, $sToCurrency);
        }
        // account for the exchange rate
        $mValue = (float)($mValue * $exchangeRate);
        return $mValue;
    }

    protected static $aShopConfiguration = null;

    /**
     * Returns shop's currency with its position
     *
     * @return array
     */
    public function getCurrencyAndCurrencyPosition() {
        if (self::$aShopConfiguration === null) {
            $aShop = MLCurrency::gi()->getShopConfigurationAsArray();

            $priceWithCurrencyFormat = $aShop['money_format'];
            $currencyISOCode = $aShop['currency'];

            if ($currencyISOCode == 'EUR') {
                $currency = '&euro;';
                if (strpos($priceWithCurrencyFormat, '{{amount}}') == 0) {
                    self::$aShopConfiguration = [
                        'position' => 'right',
                        'currency' => $currency
                    ];
                } else {
                    self::$aShopConfiguration = [
                        'position' => 'left',
                        'currency' => $currency
                    ];
                }

            } else {
                if (strpos($priceWithCurrencyFormat, '{{amount}}') == 0) {

                    self::$aShopConfiguration = [
                        'position' => 'right',
                        'currency' => $currencyISOCode.' '
                    ];
                } else {
                    self::$aShopConfiguration = [
                        'position' => 'left',
                        'currency' => $currencyISOCode.' '
                    ];
                }
            }
        }
        return self::$aShopConfiguration;

    }

    protected function getExchangeRate($sShopifyCurrency, $sCurrency) {
        $currencyExchangeRate = MagnaConnector::gi()->submitRequestCached(array(
            'ACTION'    => 'GetExchangeRate',
            'SUBSYSTEM' => 'Core',
            'FROM'      => $sShopifyCurrency,
            'TO'        => $sCurrency,
        ), 4 * 60 * 60);
        Kint::dump($currencyExchangeRate);
        // exchange rate difference between webshop and marketplace
        $exchangeRate = $currencyExchangeRate['EXCHANGERATE'];
        return $exchangeRate;
    }

}
