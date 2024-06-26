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
 * (c) 2010 - 2022 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

MLFilesystem::gi()->loadClass('Etsy_Helper_Model_Table_Etsy_ConfigData');

class ML_ShopwareCloudEtsy_Helper_Model_Table_Etsy_ConfigData extends ML_Etsy_Helper_Model_Table_Etsy_ConfigData {

    /**
     * Set config values - shop tax ids
     *
     * @param $aField
     * @return void
     */
    public function orderimport_taxIdField(&$aField) {
        $aField['values'] = MLFormHelper::getShopInstance()->getShopwareCloudTaxes();
    }

    public function orderimport_paymentmethodField(&$aField) {
        $aField['values'] = MLFormHelper::getShopInstance()->getPaymentMethodValues();
    }

    public function orderimport_shippingmethodField(&$aField) {
        $aField['values'] = MLFormHelper::getShopInstance()->getShippingMethodValues();
    }

    public function orderimport_paymentstatusField(&$aField) {
        $aField['values'] = MLFormHelper::getShopInstance()->getPaymentStatusValues();
    }
}
