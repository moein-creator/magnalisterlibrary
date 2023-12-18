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
 * Class ML_Shopify_Model_Price
 *
 */
class ML_Shopify_Model_Price extends ML_Shop_Model_Price_Abstract implements ML_Shop_Model_Price_Interface
{

    /**
     * Formats price with its currency code.
     *
     * @todo blConvert should be implemented.
     *
     * @param float $fPrice product price
     * @param string $sCode currency
     *
     * @return string
     */
    public function format($fPrice, $sCode, $blConvert = true)
    {
        return  MLHelper::gi('model_price')->getPriceByCurrency($fPrice, $sCode,true);
    }

}