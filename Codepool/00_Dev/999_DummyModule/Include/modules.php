<?php
    MLSetting::gi()->add('aModules', array(
    'dummymodule' => array(
        'title' => '{#i18n:sModuleNameDummyModule#}',
        'logo' => 'dummymodule',
        'displayAlways' => true,
        'requiredConfigKeys' => array(
            'lang',
            'mwstfallback',
            'quantity.type',
            'price.addkind',
            'import',
            'orderstatus.open'
        ),
        'authKeys' => array(
        ),
        'settings' => array(
            'defaultpage' => 'prepare',
            'subsystem' => 'DummyModule',
            'currency' => '__depends__',
            'hasOrderImport' => true,
        ),
        'type' => 'marketplace',
    ),
));
