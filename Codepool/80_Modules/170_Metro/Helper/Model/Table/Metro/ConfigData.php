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

MLFilesystem::gi()->loadClass('Form_Helper_Model_Table_ConfigData_Abstract');

class ML_Metro_Helper_Model_Table_Metro_ConfigData extends ML_Form_Helper_Model_Table_ConfigData_Abstract {

    public function processingTimeField(&$aField) {
        for ($i = 0; $i < 100; $i++) {
            $aField['values'][$i] = $i;
        }
    }

    /**
     * Get Values for Configuration - use same as processing time
     *
     * @param $aField
     * @return void
     */
    public function maxProcessingTimeField(&$aField) {
        $this->processingTimeField($aField);

        // remove 0 as value because max processing time needs to be at least 1
        unset($aField['values'][0]);
    }

    public function businessModelField(&$aField) {
        $aField['values'] = array(
            '' => 'B2B / B2C',
            'B2B' => 'B2B',
        );
    }

    public function orderstatus_acceptedField(&$aField) {
        $this->orderstatus_canceledField($aField);
        $aField['values'] = array('auto' => 'Auto Acceptance') + $aField['values'];
    }

    public function orderstatus_cancellationreasonField(&$aField) {
        $aField['values'] = MLModul::gi()->getMetroCancellationReasons();
    }

    public function primaryCategoryField(&$aField) {
        $aRequest = MLRequest::gi()->data();
        if (MLModul::gi()->getMarketPlaceName().':'.MLModul::gi()->getMarketPlaceId().'_prepare_variations' === $aRequest['controller']) {
            $aField['values'] = MLDatabase::factory(MLModul::gi()->getMarketPlaceName() . '_variantmatching')->getTopPrimaryCategories();
        } else {
            $aField['values'] = MLDatabase::factory( MLModul::gi()->getMarketPlaceName() . '_prepare')->getTopPrimaryCategories();
        }
    }

    public function orderstatus_carrierField(&$aField) {
    }

}
