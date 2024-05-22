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

use Shopify\API\Application\Application;

/**
 * Class ML_Shopify_Model_Currency
 *
 */
class ML_Shopify_Model_Currency extends ML_Shop_Model_Currency_Abstract {

    private $currencyList;

    private $currency;


    /**
     * Returns list of currencies.
     *
     * @return array Example is shown below
     *
     * array(
     *      ...
     *      'EUR' = array (
     *          'title'           => 'Euro',
     *          'symbol_left'     => '',
     *          'symbol_right'    => '€',
     *          'decimal_point'   => '.',
     *          'thousands_point' => '',
     *          'decimal_places'  => 2,
     *          'value'           => 1
     *      )
     *      ....
     * )
     * @throws ML_Filesystem_Exception
     */
    public function getList() {
        if (!MLCache::gi()->exists('aShop')) {
            $aShop = MLShopifyAlias::getShopHelper()->getShopConfigurationAsArray();
            MLCache::gi()->set('aShop', $aShop, 10 * 60); // 10 min
        } else {
            $aShop = MLCache::gi()->get('aShop');
        }

        $this->currency = $aShop;
        $sTitle = $aShop['currency'];
        $aCurrencyPosition = $this->getCurrencyPosition($aShop);

        $result = [
            $sTitle => [
                'title'           => $sTitle,
                'symbol_left'     => $aCurrencyPosition['symbol_left'],
                'symbol_right'    => $aCurrencyPosition['symbol_right'],
                'decimal_point'   => '.',
                'thousands_point' => '',
                'decimal_places'  => 2,
                'value'           => 1
            ]
        ];

        return array_merge($this->listCurrencies(), $result);
    }

    public function listCurrencies(): array {
        $aCurrencies = [];
        foreach ($this->getListOfCurrenciesWithSymbols() as $sISO => $sSymbol) {
            if (in_array($sSymbol, ['EUR', 'CHF'])) {

                $aCurrencies[$sISO] = [
                    'title'           => $sISO,
                    'symbol_left'     => '',
                    'symbol_right'    => $sSymbol,
                    'decimal_point'   => ',',
                    'thousands_point' => '',
                    'decimal_places'  => 2,
                    'value'           => 1
                ];
            } else {
                $aCurrencies[$sISO] = [
                    'title'           => $sISO,
                    'symbol_left'     => $sSymbol,
                    'symbol_right'    => '',
                    'decimal_point'   => ',',
                    'thousands_point' => '',
                    'decimal_places'  => 2,
                    'value'           => 1
                ];
            }
        }
        return $aCurrencies;
    }

    public function getCurrencySymbol($code) {
        return $this->getListOfCurrenciesWithSymbols()[$code];
    }

    protected function getListOfCurrenciesWithSymbols() {
        return array(
            'AED' => '&#x62f;.&#x625;',
            'AFN' => '&#x60b;',
            'ALL' => 'L',
            'AMD' => 'AMD',
            'ANG' => '&fnof;',
            'AOA' => 'Kz',
            'ARS' => '&#36;',
            'AUD' => '&#36;',
            'AWG' => '&fnof;',
            'AZN' => 'AZN',
            'BAM' => 'KM',
            'BBD' => '&#36;',
            'BDT' => '&#2547;&nbsp;',
            'BGN' => '&#1083;&#1074;.',
            'BHD' => '.&#x62f;.&#x628;',
            'BIF' => 'Fr',
            'BMD' => '&#36;',
            'BND' => '&#36;',
            'BOB' => 'Bs.',
            'BRL' => '&#82;&#36;',
            'BSD' => '&#36;',
            'BTC' => '&#3647;',
            'BTN' => 'Nu.',
            'BWP' => 'P',
            'BYR' => 'Br',
            'BZD' => '&#36;',
            'CAD' => '&#36;',
            'CDF' => 'Fr',
            'CHF' => '&#67;&#72;&#70;',
            'CLP' => '&#36;',
            'CNY' => '&yen;',
            'COP' => '&#36;',
            'CRC' => '&#x20a1;',
            'CUC' => '&#36;',
            'CUP' => '&#36;',
            'CVE' => '&#36;',
            'CZK' => '&#75;&#269;',
            'DJF' => 'Fr',
            'DKK' => 'DKK',
            'DOP' => 'RD&#36;',
            'DZD' => '&#x62f;.&#x62c;',
            'EGP' => 'EGP',
            'ERN' => 'Nfk',
            'ETB' => 'Br',
            'EUR' => '&euro;',
            'FJD' => '&#36;',
            'FKP' => '&pound;',
            'GBP' => '&pound;',
            'GEL' => '&#x10da;',
            'GGP' => '&pound;',
            'GHS' => '&#x20b5;',
            'GIP' => '&pound;',
            'GMD' => 'D',
            'GNF' => 'Fr',
            'GTQ' => 'Q',
            'GYD' => '&#36;',
            'HKD' => '&#36;',
            'HNL' => 'L',
            'HRK' => 'Kn',
            'HTG' => 'G',
            'HUF' => '&#70;&#116;',
            'IDR' => 'Rp',
            'ILS' => '&#8362;',
            'IMP' => '&pound;',
            'INR' => '&#8377;',
            'IQD' => '&#x639;.&#x62f;',
            'IRR' => '&#xfdfc;',
            'IRT' => '&#x062A;&#x0648;&#x0645;&#x0627;&#x0646;',
            'ISK' => 'kr.',
            'JEP' => '&pound;',
            'JMD' => '&#36;',
            'JOD' => '&#x62f;.&#x627;',
            'JPY' => '&yen;',
            'KES' => 'KSh',
            'KGS' => '&#x441;&#x43e;&#x43c;',
            'KHR' => '&#x17db;',
            'KMF' => 'Fr',
            'KPW' => '&#x20a9;',
            'KRW' => '&#8361;',
            'KWD' => '&#x62f;.&#x643;',
            'KYD' => '&#36;',
            'KZT' => 'KZT',
            'LAK' => '&#8365;',
            'LBP' => '&#x644;.&#x644;',
            'LKR' => '&#xdbb;&#xdd4;',
            'LRD' => '&#36;',
            'LSL' => 'L',
            'LYD' => '&#x644;.&#x62f;',
            'MAD' => '&#x62f;.&#x645;.',
            'MDL' => 'MDL',
            'MGA' => 'Ar',
            'MKD' => '&#x434;&#x435;&#x43d;',
            'MMK' => 'Ks',
            'MNT' => '&#x20ae;',
            'MOP' => 'P',
            'MRO' => 'UM',
            'MUR' => '&#x20a8;',
            'MVR' => '.&#x783;',
            'MWK' => 'MK',
            'MXN' => '&#36;',
            'MYR' => '&#82;&#77;',
            'MZN' => 'MT',
            'NAD' => '&#36;',
            'NGN' => '&#8358;',
            'NIO' => 'C&#36;',
            'NOK' => '&#107;&#114;',
            'NPR' => '&#8360;',
            'NZD' => '&#36;',
            'OMR' => '&#x631;.&#x639;.',
            'PAB' => 'B/.',
            'PEN' => 'S/.',
            'PGK' => 'K',
            'PHP' => '&#8369;',
            'PKR' => '&#8360;',
            'PLN' => '&#122;&#322;',
            'PRB' => '&#x440;.',
            'PYG' => '&#8370;',
            'QAR' => '&#x631;.&#x642;',
            'RMB' => '&yen;',
            'RON' => 'lei',
            'RSD' => '&#x434;&#x438;&#x43d;.',
            'RUB' => '&#8381;',
            'RWF' => 'Fr',
            'SAR' => '&#x631;.&#x633;',
            'SBD' => '&#36;',
            'SCR' => '&#x20a8;',
            'SDG' => '&#x62c;.&#x633;.',
            'SEK' => '&#107;&#114;',
            'SGD' => '&#36;',
            'SHP' => '&pound;',
            'SLL' => 'Le',
            'SOS' => 'Sh',
            'SRD' => '&#36;',
            'SSP' => '&pound;',
            'STD' => 'Db',
            'SYP' => '&#x644;.&#x633;',
            'SZL' => 'L',
            'THB' => '&#3647;',
            'TJS' => '&#x405;&#x41c;',
            'TMT' => 'm',
            'TND' => '&#x62f;.&#x62a;',
            'TOP' => 'T&#36;',
            'TRY' => '&#8378;',
            'TTD' => '&#36;',
            'TWD' => '&#78;&#84;&#36;',
            'TZS' => 'Sh',
            'UAH' => '&#8372;',
            'UGX' => 'UGX',
            'USD' => '&#36;',
            'UYU' => '&#36;',
            'UZS' => 'UZS',
            'VEF' => 'Bs F',
            'VND' => '&#8363;',
            'VUV' => 'Vt',
            'WST' => 'T',
            'XAF' => 'Fr',
            'XCD' => '&#36;',
            'XOF' => 'Fr',
            'XPF' => 'Fr',
            'YER' => '&#xfdfc;',
            'ZAR' => '&#82;',
            'ZMW' => 'ZK',
        );
    }

    public function getCurrencyPosition($aShop) {
        // CURRENCY FORMATTING - it reads the field "HTML without currency"
        $priceWithCurrencyFormat = $aShop['money_format'];
        $sCurrencyISOCode = $aShop['currency'];

        $blank = (strrpos($priceWithCurrencyFormat, ' ') > 0) ? '&nbsp;' : '';

        if (strpos($priceWithCurrencyFormat, '{{amount}}') == 0) {
            return [
                'symbol_right' => $blank . $this->getCurrencySymbol($sCurrencyISOCode),
                'symbol_left' => ''
            ];
        } else {
            return [
                'symbol_right' => '',
                'symbol_left' => $this->getCurrencySymbol($sCurrencyISOCode) . $blank,
            ];
        }
    }

    /**
     * Returns shop configuration
     *
     * @return array
     */
    public function getShopConfigurationAsArray() {
        $sShopId = MLHelper::gi('model_shop')->getShopId();
        $application = new Application($sShopId);
        $sToken = MLHelper::gi('container')->getCustomerModel()->getAccessToken($sShopId);
        $aShop = $application->getShopRequest($sToken)->getSingleShop()->getBodyAsArray()['shop'];

        return $aShop;
    }

    /**
     * Returns default ISO code.
     *
     * @todo Investigate and implement function.
     *
     * @return string Return string is 3 character long
     */
    public function getDefaultIso(){
        $aShop = MLHelper::gi('model_shop')->getShopConfigurationAsArray();

        return $aShop['currency'];
    }

    /**
     * Updates currency rate.
     *
     * @todo Investigate and implement function.
     *
     * @param string $sCurrency
     *
     * @return $this
     */
    public function updateCurrencyRate($sCurrency) {
        return $this;
    }

}
