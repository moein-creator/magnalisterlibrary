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

MLFilesystem::gi()->loadClass('Hitmeister_Helper_Model_Table_Hitmeister_ConfigData');

class ML_Shopware6Hitmeister_Helper_Model_Table_Hitmeister_ConfigData extends ML_Hitmeister_Helper_Model_Table_Hitmeister_ConfigData {

    public function orderstatus_carrierField(&$aField) {
        parent::orderstatus_carrierField($aField);
        $aField['values'] =
            array('-1' => MLI18n::gi()->get('orderstatus_carrier_defaultField_value_shippingname'))
            +
            $aField['values'];
    }

}
