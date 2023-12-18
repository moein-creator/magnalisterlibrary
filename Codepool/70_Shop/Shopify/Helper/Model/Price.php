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
 * Class ML_Shopify_Helper_Model_Price
 */
class ML_Shopify_Helper_Model_Price 
{

    public function getPriceByCurrency($mValue, $sCurrency = null, $blFormated = false)
    {
        $exchangeRate = 1;
        $format = array();

        $oShopConfuguration = MLCurrency::gi()->getShopConfigurationAsArray();

        // reads the currency marketplace
        $currencyMarketplace = mlmodul::gi()->getConfig('currency');

        // Compare the currency of the webshop and marketplace
        if ($currencyMarketplace!=null && $oShopConfuguration['currency'] != $currencyMarketplace) {

            // checks if cash does not exist
            if ( ! MLCache::gi()->exists( 'currencyExchangeRate' ) ) {
                $currencyExchangeRate = MagnaConnector::gi()->submitRequest( array(
                    'ACTION'    => 'GetExchangeRate',
                    'SUBSYSTEM' => 'Core',
                    'FROM'      => $oShopConfuguration['currency'],
                    'TO'        => $currencyMarketplace,
                ) );
                // Creates cash
                MLCache::gi()->set( 'currencyExchangeRate', $currencyExchangeRate, 24 * 60 * 60 );

                // exchange rate difference between webchop and marketplace
                $exchangeRate = $currencyExchangeRate['EXCHANGERATE'];
            } else {
                $exchangeRate = MLCache::gi()->get( 'currencyExchangeRate' )['EXCHANGERATE'];
            }
              // sets currency for marketplace in default (listCurrencies) currency setting
              //$format = MLCurrency::gi()->getList()[ $currencyMarketplace ];

              //sets currency for marketplace, setting for shop only currency sign change
              $format = MLCurrency::gi()->getList()[  $oShopConfuguration['currency'] ];

              $symboCurrencyMarketplacel= MLCurrency::gi()->getCurrencySymbol($currencyMarketplace);
              $symboShopConfuguration= MLCurrency::gi()->getCurrencySymbol($oShopConfuguration['currency']);

              $format['symbol_left'] = str_replace($symboShopConfuguration, $symboCurrencyMarketplacel, $format['symbol_left'] );
              $format['symbol_right'] = str_replace($symboShopConfuguration, $symboCurrencyMarketplacel, $format['symbol_right'] );

        }else{
            $format = MLCurrency::gi()->getList()[ $oShopConfuguration['currency' ]];
        }

        // account for the exchange rate
        $mValue = $mValue * $exchangeRate;

        $mValue = (float)$mValue;

        $decimalPlaces = $format['decimal_places'] != "" ? $format['decimal_places'] : 2;
        $decimalPoint = $format['decimal_point'] != "" ? $format['decimal_point'] : '.';
        $thousandsPoint = $format['thousands_point'] != "" ? $format['thousands_point'] : ',';
        $mValue = number_format($mValue, $decimalPlaces, $decimalPoint, $thousandsPoint);

        if ($sCurrency != null || $blFormated == true) {
            $mValue =  $format['symbol_left'] . $mValue . $format['symbol_right'];

            return $mValue;
        } else if ($sCurrency === null) {
            $oMLPrice = MLPrice::factory();
            $mValue = $oMLPrice->unformat($mValue);

            return $mValue;
        }
    }

    protected static $aShopConfiguration = null;

    /**
     * Returns shop's currency with its position
     *
     * @return array
     */
    public function getCurrencyAndCurrencyPosition()
    {
        if(self::$aShopConfiguration === null) {
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

}
