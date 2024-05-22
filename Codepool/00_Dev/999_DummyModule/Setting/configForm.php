<?php

MLSetting::gi()->add('dummymodule_config_prepare', array(
    'prepare' => array(
        'fields' => array(
            array(
                'name' => 'prepare.status',
                'type' => 'bool',
            ),
            array(
                'name' => 'lang',
                'type' => 'select',
            ),
        ),
    ),
    'upload' => array(
        'fields' => array(
            array(
                'name' => 'checkin.status',
                'type' => 'bool',
            ),
            array(
                'name' => 'quantity',
                'type' => 'selectwithtextoption',
                'subfields' => array(
                    'select' => array('name' => 'quantity.type', 'type' => 'select'),
                    'string' => array('name' => 'quantity.value', 'type' => 'string')
                )
            ),           
            array(
                'name' => 'checkin.skuasmfrpartno',
                'type' => 'bool',
            ),
            array(
                'name' => 'imagesize',
                'type' => 'select',
            ), 
        )
    ),
    'shipping' => array(
        'fields' => array(
            array(
                'name' => 'leadtimetoship',
                'type' => 'select'
            ),
            array(
                'name' => 'internationalShipping',
                'type' => 'select',
            ),
        )
    ),
), false);

MLSetting::gi()->add('dummymodule_config_price', array(
    'price' => array(
        'fields' => array(
            array(
                'name' => 'price',
                'type' => 'subFieldsContainer',
                'subfields' => array(
                    'addkind' => array('name' => 'price.addkind', 'type' => 'select'),
                    'factor' => array('name' => 'price.factor', 'type' => 'string'),
                    'signal' => array('name' => 'price.signal', 'type' => 'string')
                )
            ),
            array(
                'name' => 'priceoptions',
                'type' => 'subFieldsContainer',
                'subfields' => array(
                    'group' => array('name' => 'price.group', 'type' => 'select'),
                    'usespecialoffer' => array('name' => 'price.usespecialoffer', 'type' => 'bool'),
                ),
            ),
            array(
                'name' => 'exchangerate_update',
                'type' => 'bool',
            ),
        )
    )
), false);



MLSetting::gi()->add('dummymodule_config_orderimport', array(
    'importactive' => array(
        'fields' => array(
            array(
                'name' => 'importactive',
                'type' => 'subFieldsContainer',
                'subfields' => array(
                    'import' => array('name' => 'import', 'type' => 'radio', ),
                    'preimport.start' => array('name' => 'preimport.start', 'type' => 'datepicker'),
                ),
            ),
            array(
                'name' => 'customergroup',
                'type' => 'select',
            ),
            array(
                'name' => 'orderimport.shop',
                'type' => 'select',
            ),
            array(
                'name' => 'orderstatus.open',
                'type' => 'select',
            ),
            'orderimport.shippingmethod' => array(
                'name' => 'orderimport.shippingmethod',
                'type' => 'selectwithtextoption',
                'subfields' => array(
                    'select' => array('name' => 'orderimport.shippingmethod', 'type' => 'select'),
                    'string' => array('name' => 'orderimport.shippingmethod.name', 'type' => 'string','default' => 'dummymodule',)
                ),
            ),  
            'orderimport.paymentmethod' => array(
                'name' => 'orderimport.paymentmethod',
                'type' => 'selectwithtextoption',
                'subfields' => array(
                    'select' => array('name' => 'orderimport.paymentmethod', 'type' => 'select'),
                    'string' => array('name' => 'orderimport.paymentmethod.name', 'type' => 'string','default' => 'dummymodule',)
                ),
            ),
        ),
    ),
    'mwst' => array(
        'fields' => array(
            array(
                'name' => 'mwstfallback',
                'type' => 'string',
                'default' => 19,
            ),
        ),
    ),
    'orderstatus' => array(
        'fields' => array(
            array(
                'i18n' => '{#i18n:formfields__orderstatus.sync#}',
                'name' => 'orderstatus.sync',
                'type' => 'select',
            ),
            array(
                'name' => 'orderstatus.shipped',
                'type' => 'select'
            ),
            array(
                'name' => 'orderstatus.carrier.default',
                'type' => 'ajax'
            ),
            array(
                'name' => 'orderstatus.carrier.additional',
                'type' => 'string'
            ),
            array(
                'name' => 'orderstatus.cancelled',
                'type' => 'select'
            ),
        ),
    )
), false);


