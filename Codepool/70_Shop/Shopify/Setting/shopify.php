<?php
MLSettingRegistry::gi()->addJs(array(
    'shopify.js',
));
MLSetting::gi()->add('aCss',
    array(
        'shopify.core.css',
        'shopify.css'
    ),
    true);