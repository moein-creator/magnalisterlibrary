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
 * (c) 2010 - 2020 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

include_once(M_DIR_LIBRARY . 'request/shopware/ShopwareCurrency.php');

use library\request\shopware\ShopwareCurrency;

class ML_ShopwareCloud_Model_Price extends ML_Shop_Model_Price_Abstract implements ML_Shop_Model_Price_Interface {

    protected $shopwareCurrencyRequest = null;
    /*
     * @Description: the lines from 34 til 45 it is not usable ,it is used in "Service and Developer Tools" of magnalister
     */
    public function format($fPrice, $sCode, $blConvert = true) { 
   
        if (!isset($sCode) || $sCode == null) {
            throw new Exception("the sCode should not be empty");
         }
        if ($blConvert) {
            $this->shopwareCurrencyRequest = new ShopwareCurrency(MLShopwareCloudAlias::getShopHelper()->getShopId());
            $aCurrencyList = $this->shopwareCurrencyRequest->getShopwareCurrencies('/api/currency', 'GET',array(),  false);
            foreach ($aCurrencyList->getData() as $value){
                if($value->getAttributes()->getIsoCode() == (string)$sCode){
                    if ($value->getAttributes()->getFactor() != null) {
                        $fPrice = (float)$fPrice * (float)$value->getAttributes()->getFactor();
                    }
                }
                break;
            }
                //@hex2bin
        }
        $mPrice= MLShopwareCloudAlias::getPriceHelper()->getPriceByCurrency($fPrice, $sCode, true);
        return $mPrice;
    }

}