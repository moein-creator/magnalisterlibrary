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
 * (c) 2010 - 2019 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

MLFilesystem::gi()->loadClass('Ebay_Model_Service_AddItems');

class ML_ShopifyEbay_Model_Service_AddItems extends ML_Ebay_Model_Service_AddItems {
    /**
     * set variation in master product data
     */
    protected function setMasterVariation() {
        parent::setMasterVariation();
        foreach ($this->aOut as &$aProduct) {
            // only if product has variations - because if product in shopify has variations there is no master product sku
            if (array_key_exists('Variations', $aProduct) && !empty($aProduct['Variations'])) {
                $aProduct['SKU'] .= '_Master';
            }
        }
    }
}
