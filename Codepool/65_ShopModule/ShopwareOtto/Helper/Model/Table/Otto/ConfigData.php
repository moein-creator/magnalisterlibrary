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

class ML_ShopwareOtto_Helper_Model_Table_Otto_ConfigData extends ML_Otto_Helper_Model_Table_Otto_ConfigData {

    public function orderimport_paymentstatusField (&$aField) {
        $aField['values'] = MLFormHelper::getShopInstance()->getPaymentStatusValues();
    }

    public function orderimport_shippingmethodField (&$aField) {
        $aField['values'] = MLFormHelper::getShopInstance()->getShippingMethodValues();
        $aField['values']['__automatic__'] = MLI18n::gi()->get('sOtto_automatically');
    }

    public function providerstandardField(&$aField) {
        parent::providerstandardField($aField);

        $aField['type'] = 'select';
        $aField['values']['-1'] = MLI18n::gi()->get('orderstatus_carrier_defaultField_value_shippingname');
    }

    public function providerforwardingField(&$aField) {
        parent::providerforwardingField($aField);

        $aField['type'] = 'select';
        $aField['values']['-1'] = MLI18n::gi()->get('orderstatus_carrier_defaultField_value_shippingname');
    }

    public function customfieldtrackingnumberField(&$aField) {
        $aField['values'] = array('' => 'Choose one');
        $sSql = "SELECT column_name, label FROM s_attribute_configuration WHERE table_name = 's_order_attributes'";
        $aSqlResults = MLDatabase::getDbInstance()->fetchArray($sSql, true);

        foreach ($aSqlResults as $key => $result) {
            $aField['values'] = $aField['values'] + array($key => $result['label']);
        }

        $aField['values'] = $aField['values'] + array('auto' => 'Let magnalister create this field');
    }

    /**
     * On Shopware we only offer select option
     *
     * @param $aField
     * @throws MLAbstract_Exception
     */
    public function orderstatus_returncarrier_selectField (&$aField) {
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
        // Free text fields - additional fields
        if (method_exists(MLFormHelper::getShopInstance(), 'getOrderFreeTextFieldsAttributes')) {
            $aShopFreeTextFieldsAttributes = MLFormHelper::getShopInstance()->getOrderFreeTextFieldsAttributes();
            $aField['values'] = array('' => MLI18n::gi()->get('ML_AMAZON_LABEL_APPLY_PLEASE_SELECT'));
            foreach ($aShopFreeTextFieldsAttributes as $value => $aShopFreeTextFieldsAttribute) {
                $aField['values'][$value] = $aShopFreeTextFieldsAttribute;
            }
        }
    }
}
