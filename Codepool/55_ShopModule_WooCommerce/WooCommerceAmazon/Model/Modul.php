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
MLFilesystem::gi()->loadClass('Amazon_Model_Modul');
class ML_WooCommerceAmazon_Model_Modul extends ML_Amazon_Model_Modul {

    public function isConfigured() {
        $ret = parent::isConfigured();

        // if false and error occurs - so return false
        if (!$ret) {
            return $ret;
        }

        // Check for missing tax classes of b2b tax code matching if enabled
        $value = MLDatabase::factory('preparedefaults')->getValue('b2bactive');
        if ($this->getConfig('b2b.tax_code') === null && $value === 'true') {
            MLMessage::gi()->addError(ucfirst($this->getMarketPlaceName()).'('.$this->getMarketPlaceId().') '.MLI18n::gi()->get('woocommerce_missing_taxclasses_error_message'));
            MLHttp::gi()->redirect(MLHttp::gi()->getUrl(array(
                'controller' => $this->getMarketPlaceName().':'.$this->getMarketPlaceId().'_config_prepare'
            )));

            return false;
        }
        return true;
    }
}
