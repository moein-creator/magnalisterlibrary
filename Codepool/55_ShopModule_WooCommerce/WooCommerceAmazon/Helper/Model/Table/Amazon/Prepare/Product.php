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

MLFilesystem::gi()->loadClass('Amazon_Helper_Model_Table_Amazon_Prepare_Product');

class ML_WooCommerceAmazon_Helper_Model_Table_Amazon_Prepare_Product extends ML_Amazon_Helper_Model_Table_Amazon_Prepare_Product {

    /**
     * @return string
     */
    protected function getMetaKeywords() {
        $sMetaKeywords = parent::getMetaKeywords();

        // Replace WooCommerce MetaKeywords separated by comma with spaces for amazon Generic Keywords
        $sMetaKeywords = str_replace(',', ' ', $sMetaKeywords);

        return $sMetaKeywords;
    }
}