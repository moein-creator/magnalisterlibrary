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

MLFilesystem::gi()->loadClass('Amazon_Helper_Model_Table_Amazon_ConfigData');

class ML_MagentoAmazon_Helper_Model_Table_Amazon_ConfigData extends ML_Amazon_Helper_Model_Table_Amazon_ConfigData {

    protected $carrierOptions = array(
        'marketplaceCarrier',
        'matchShopShippingOptions',
        'orderFreeTextField',
        'freeText',
    );

    protected $shipmethodOptions = array(
        'matchShopShippingOptions',
        'orderFreeTextField',
        'freeText',
    );

    public function orderstatus_carrier_defaultField(&$aField){
        $aField['ajax']=array(
            'selector' => '#' . $this->getFieldId('orderstatus.carrier.additional'),
            'trigger' => 'change',
            'field' => array(
                'type' => 'select',
            ),
        );
        $aField['values'] =
                array('-1'=>MLI18n::gi()->get('orderstatus_carrier_defaultField_value_shippingname'))
                +
                MLFormHelper::getModulInstance()->getCarrierCodeValues();

    }
    
}
