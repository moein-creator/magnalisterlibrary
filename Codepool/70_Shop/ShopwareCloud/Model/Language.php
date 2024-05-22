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

//require_once(M_DIR_LIBRARY.'I18N.php');


class ML_Shopwarecloud_Model_Language extends ML_Shop_Model_Language_Abstract {

    public function getCurrentIsoCode() {
        if (MLSetting::gi()->sTranslationLanguage) {
            $IsoCode = MLSetting::gi()->sTranslationLanguage;
        } else {
            $IsoCode = I18N::gi()->getShopwareQueryLocale();
        }
        return $IsoCode;
    }

    public function getCurrentCharset() {
        return 'UTF-8';
    }

}
