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
 * (c) 2010 - 2021 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

MLFilesystem::gi()->loadClass('Otto_Helper_Model_Table_Otto_ConfigData');

class ML_Shopware6Otto_Helper_Model_Table_Otto_ConfigData extends ML_Otto_Helper_Model_Table_Otto_ConfigData {
    /**
     * On Shopware we only offer select option
     *
     * @param $aField
     * @throws MLAbstract_Exception
     */
    public function orderstatus_returncarrier_selectField(&$aField) {
        if (isset($aField['matching'])) {
            $aField = $this->carrierSelect($aField['matching'], $aField, 'return');
        }
    }

    /**
     * On Shopware we offer Shop Order FreeText fields as option
     *
     * @param $aField
     * @throws MLAbstract_Exception
     */
    public function orderstatus_returntrackingkeyField(&$aField) {
        $aField['values'] = array(
            ''                   => MLI18n::gi()->get('ML_AMAZON_LABEL_APPLY_PLEASE_SELECT'),
            'orderFreeTextField' => MLI18n::gi()->get('otto_config_free_text_attributes_opt_group_value'),
        );
        // Free text fields - additional fields
        if (method_exists(MLFormHelper::getShopInstance(), 'getOrderFreeTextFieldsAttributes')) {
            $aShopFreeTextFieldsAttributes = MLFormHelper::getShopInstance()->getOrderFreeTextFieldsAttributes();
            foreach ($aShopFreeTextFieldsAttributes as $value => $aShopFreeTextFieldsAttribute) {
                $aField['values'][$value] = $aShopFreeTextFieldsAttribute;
            }
        }
    }

}
