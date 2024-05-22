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

MLFilesystem::gi()->loadClass('Etsy_Helper_Model_Service_Product');

class ML_ShopifyEtsy_Helper_Model_Service_Product extends ML_Etsy_Helper_Model_Service_Product {


    /**
     * for Shopify: oPrepare->get('shopVariation') gives back shop variation keys
     * like 'color_black', which can't be assigned to vaueid like 'black'
     * from oVariant->getVariatonDataOptinalField(array('name', 'value', 'valueid'));
     * @param $shopVariants
     * @return array
     */
    protected function manipulateShopVariationData($shopVariants) {

        foreach ($shopVariants as &$sv) {
            foreach ($sv['Values'] as &$val) {
                if (strpos($val['Shop']['Key'], '_') !== false) {
                    $val['Shop']['Key'] = substr($val['Shop']['Key'], strpos($val['Shop']['Key'], '_') + 1);
                }
            }
        }
        return $shopVariants;
    }


}
