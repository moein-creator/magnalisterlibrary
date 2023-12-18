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

class ML_WooCommerce_Model_Currency extends ML_Shop_Model_Currency_Abstract {
    /**
     * Return array with the data on the currency data, which contains information
     * about the currency from the listCurrencies() and the currency of the shop.
     *
     * {
     * EUR: {
     * title: "EUR",
     * symbol_left: "€",
     * symbol_right: "",
     * decimal_point: ".",
     * thousands_point: ",",
     * decimal_places: "2",
     * value: "1"
     * }
     * }
     *
     * Get currency list
     * @return array
     */
    public function getList() {

        global $wpdb;
        $aCurrencyList = MLDatabase::getDbInstance()->fetchArray("
            SELECT * 
            FROM {$wpdb->options}
            WHERE option_name IN ('woocommerce_currency', 'woocommerce_currency_pos', 'woocommerce_price_thousand_sep', 'woocommerce_price_decimal_sep', 'woocommerce_price_num_decimals')
        ");

        $aCurrency = array(
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

        return array_merge($this->listCurrencies(), $aCurrency);
    }

    /**
     * contains currency data
     *
     * @return array
     */
    public function listCurrencies(){
        $currencies = array();

        foreach (array_flip(get_woocommerce_currencies()) as $currency) {
            $currencies[$currency] = array(
                'title' => $currency,
                'symbol_right' => get_woocommerce_currency_symbol($currency),
            );
        }

        return $currencies;
    }

    /**
     * Return default iso in shop type string. (EUR)
     *
     * @return string
     */
    public function getDefaultIso() {
        global $wpdb;
        $aCurrencyList = MLDatabase::getDbInstance()->fetchArray("
            SELECT * 
            FROM {$wpdb->options}
            WHERE option_name = 'woocommerce_currency'
        ");

        return $this->getCurrencyFieldValue($aCurrencyList, 'woocommerce_currency');
    }

    /**
     * Input parameter is $sCurrency, if it differs current currency and input currency,
     * then update to the table of the store with the new currency.
     *
     * @param string $sCurrency
     *
     * @return $this
     * @throws Exception
     */
    public function updateCurrencyRate($sCurrency) {
//        global $wpdb;
//        $sDefaultCurrency = $this->getDefaultIso();
//        if($sDefaultCurrency != $sCurrency){
//            try {
//                $result = MagnaConnector::gi()->submitRequest(array(
//                    'ACTION' => 'GetExchangeRate',
//                    'SUBSYSTEM' => 'Core',
//                    'FROM' => strtoupper($sDefaultCurrency),
//                    'TO' => strtoupper($sCurrency),
//                ));
//                if ($result['EXCHANGERATE'] > 0) {
//                    MLDatabase::getDbInstance()->query("UPDATE " . $wpdb->options ." SET option_value = '".$sCurrency . "' WHERE option_name = 'woocommerce_currency'");
//                }
//            } catch (MagnaException $e) {
//                throw new Exception('One Problem occured in updating Currency Rate');
//            }
//        }
        return $this;
    }

    /**
     * Return default iso in shop type string (EUR)
     *
     * @param null $iShopId
     *
     * @return string
     */
    public function getShopCurrency($iShopId = null) {

        return $this->getDefaultIso();
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
}
