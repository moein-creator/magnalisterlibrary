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
MLFilesystem::gi()->loadClass('Configuration_Helper_Model_Table_Configuration_ConfigData');

class ML_Shopify_Helper_Model_Table_Configuration_ConfigData extends ML_Configuration_Helper_Model_Table_Configuration_ConfigData {

    public function global_vat_matching_shipping_countryField(&$aField) {
        if ($this->checkPassPhrase()) {
            $countries = [
                '' => MLI18n::gi()->{'form_type_matching_select_optional'},
                'all' => MLI18n::gi()->{'shopify_global_configuration_vat_matching_option_all_countries'}
            ];
            try {
                $aData = MagnaConnector::gi()->submitRequestCached(array('ACTION' => 'GetCountries', 'SUBSYSTEM' => 'Core'), 60 * 60 * 24 * 7);
                if (isset($aData['DATA'])) {
                    $countries = $countries + $aData['DATA'];
                }
            } catch (\MagnaException $ex) {
                MLMessage::gi()->addDebug($ex);
            }
//            MLMessage::gi()->addDebug(__LINE__.':'.microtime(true), array($countries));
            $aField['values'] = $countries;
        } else {
            $aField = array();
        }
    }

    public function global_vat_matching_collectionField(&$aField) {

        if ($this->checkPassPhrase()) {
            $collectionsDB = MLDatabase::getDbInstance()->fetchArray(
                'SELECT DISTINCT `ShopifyCollectionId` AS id, `ShopifyCollectionTitle` AS `title`' .
                ' FROM ' . MLShopifyAlias::getCollectionModel()->getTableName() .
                ' ORDER BY `title` ASC'
            );
            $aCollection = [
                '' => MLI18n::gi()->{'form_type_matching_select_optional'},
                'all' => MLI18n::gi()->{'shopify_global_configuration_vat_matching_option_all'}
            ];

            foreach ($collectionsDB as $collection) {
                $aCollection[$collection['id']] = $collection['title'];
            }
            $aField['values'] = $aCollection;
        } else {
            $aField = array();
        }
    }

}
