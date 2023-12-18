<?php

MLSetting::gi()->add('generic_config_generic', array(
    'generic' => array(
        'fields' => array(
            array(
                'name' => 'orderimport.shop',
                'type' => 'select'
            ),
        ),
    )
), false);

MLSetting::gi()->add('magnalister_shop_order_additional_field', array(
    'trackingKey' => 'magnalister {#setting:currentMarketplaceName#} tracking key',
));