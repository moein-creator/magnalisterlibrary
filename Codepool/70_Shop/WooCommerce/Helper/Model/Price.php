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
 * (c) 2010 - 2020 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

class ML_WooCommerce_Helper_Model_Price {
    /**
     * Currency conversion takes place if there is a difference between shopping and marketplace.
     * The appearance of the price display is formed
     *
     * @param $mValue
     * @param null $sCurrency
     * @param bool $blFormated
     *
     * @return mixed
     *     A string if $blFormated == true
     *     A float if $blFormated == false     *
     */
    public function getPriceByCurrency($mValue, $sCurrency = null, $blFormated = false) {

        $exchangeRate = 1;
        $result = $this->getCurrencyOptions();
        $format = $result[key($result)];

        // reads the currency marketplace
        $currencyMarketplace = MLModul::gi()->getConfig('currency');

        // Compare the currency of the webshop and marketplace
        if ($currencyMarketplace!=null && $format['title'] != $currencyMarketplace) {

            // checks if cash does not exist
            if ( !MLCache::gi()->exists('currencyExchangeRate')) {
                $currencyExchangeRate = MagnaConnector::gi()->submitRequest(array(
                    'ACTION'    => 'GetExchangeRate',
                    'SUBSYSTEM' => 'Core',
                    'FROM'      => $format['title'],
                    'TO'        => $currencyMarketplace,
                ));
                // Creates cash
                MLCache::gi()->set('currencyExchangeRate', $currencyExchangeRate, 24 * 60 * 60);

                // exchange rate difference between webchop and marketplace
                $exchangeRate = $currencyExchangeRate['EXCHANGERATE'];
            } else {
                $exchangeRate = MLCache::gi()->get('currencyExchangeRate')['EXCHANGERATE'];
            }
            // sets currency for marketplace in default (listCurrencies) currency setting
            //$format = MLCurrency::gi()->listCurrencies()[$currencyMarketplace];

            // sets currency for shop, setting for marketplace only currency sign
            $format = MLCurrency::gi()->getList()[ $format['title'] ];

            $symbolCurrencyMarketplace= get_woocommerce_currency_symbol($currencyMarketplace);
            $symbolShopConfuguration= get_woocommerce_currency_symbol($format['title']);

            $format['symbol_left'] = str_replace($symbolShopConfuguration, $symbolCurrencyMarketplace, $format['symbol_left'] );
            $format['symbol_right'] = str_replace($symbolShopConfuguration, $symbolCurrencyMarketplace, $format['symbol_right'] );
        }

        // account for the exchange rate
        $mValue = (float)$mValue;
        $mValue *= $exchangeRate;

        $decimalPlaces = $format['decimal_places'] != "" ? $format['decimal_places'] : 2;
        $decimalPoint = $format['decimal_point'] != "" ? $format['decimal_point'] : '.';
        $thousandsPoint = $format['thousands_point'] != "" ? $format['thousands_point'] : ',';
        $mValue = number_format($mValue, $decimalPlaces, $decimalPoint, $thousandsPoint);

        if ( $blFormated==false) {
            $oMLPrice = MLPrice::factory();
            $mValue = $oMLPrice->unformat($mValue);
            $mValue = filter_var($mValue, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        }else{
            $mValue = $format['symbol_left'] . $mValue . $format['symbol_right'];
        }
        return $mValue;
    }

    /**
     * @param $mValue
     * @return string
     */
    public function getPriceFormatted($mValue) {
        $result = $this->getCurrencyOptions();
        $format = $result[key($result)];
        $mpDBCurrency = MLModul::gi()->getConfig('currency');
        $shopCurrency = strtoupper(MLHelper::gi('model_price')->getShopCurrency());
        $mpCurrency = strtoupper((empty($mpDBCurrency)) ? getCurrencyFromMarketplace(MLModul::gi()->getMarketPlaceId()) : $mpDBCurrency);

        $format = MLCurrency::gi()->getList()[$format['title']];

        $symbolCurrencyMarketplace = get_woocommerce_currency_symbol($mpCurrency);
        $symbolShopConfuguration = get_woocommerce_currency_symbol($format['title']);

        $format['symbol_left'] = str_replace($symbolShopConfuguration, $symbolCurrencyMarketplace, $format['symbol_left']);
        $format['symbol_right'] = str_replace($symbolShopConfuguration, $symbolCurrencyMarketplace, $format['symbol_right']);
        $decimalPlaces = $format['decimal_places'] != "" ? $format['decimal_places'] : 2;
        $decimalPoint = $format['decimal_point'] != "" ? $format['decimal_point'] : '.';
        $thousandsPoint = $format['thousands_point'] != "" ? $format['thousands_point'] : ',';
        $mValue = number_format($mValue, $decimalPlaces, $decimalPoint, $thousandsPoint);

        return $format['symbol_left'].$mValue.$format['symbol_right'];
    }

    /**
     * Extracts currency data from the databases and form them in a array.
     *
     * @return array
     */
    private function getCurrencyOptions() {
        global $wpdb;
        $aCurrencyList = MLDatabase::getDbInstance()->fetchArray("
                SELECT * 
                FROM $wpdb->options
                WHERE option_name IN ('woocommerce_currency', 'woocommerce_currency_pos', 'woocommerce_price_thousand_sep', 'woocommerce_price_decimal_sep', 'woocommerce_price_num_decimals')
            ");
        $aCurrencies   = array(
            $this->getCurrencyFieldValue($aCurrencyList, 'woocommerce_currency') => array(
                'title' => $this->getCurrencyFieldValue($aCurrencyList, 'woocommerce_currency'),
                'symbol_left' => $this->getCurrencyFieldValue($aCurrencyList,
                    'woocommerce_currency_pos') == 'left' ? get_woocommerce_currency_symbol($this->getCurrencyFieldValue($aCurrencyList,
                    'woocommerce_currency')) : '',
                'symbol_right' => $this->getCurrencyFieldValue($aCurrencyList,
                    'woocommerce_currency_pos') == 'right' ? get_woocommerce_currency_symbol($this->getCurrencyFieldValue($aCurrencyList,
                    'woocommerce_currency')) : '',
                'decimal_point' => $this->getCurrencyFieldValue($aCurrencyList, 'woocommerce_price_decimal_sep'),
                'thousands_point' => $this->getCurrencyFieldValue($aCurrencyList, 'woocommerce_price_thousand_sep'),
                'decimal_places' => $this->getCurrencyFieldValue($aCurrencyList, 'woocommerce_price_num_decimals'),
                'value' => 1
            )
        );

        return $aCurrencies;
    }

    /**
     * Get currency field value regarding currency field key
     *
     * @param array $aCurrencyList
     * @param string $field
     *
     * @return string
     */
    private function getCurrencyFieldValue($aCurrencyList, $field) {
        if ( ! $aCurrencyList) {
            return '';
        }

        foreach ($aCurrencyList as $item) {
            if ($item['option_name'] == $field) {
                return $item['option_value'];
            }
        }
    }

    /**
     * Return shop currency code
     *
     * @return string
     */
    public function getShopCurrency() {
        return get_woocommerce_currency();
    }
}