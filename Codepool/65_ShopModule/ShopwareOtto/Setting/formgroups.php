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
 * (c) 2010 - 2020 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

$Config = MLSetting::gi()->get('formgroups__orderimport');
//Kint::dump($Config); // To check if you are overwriting correct fields compare it after change
MLSetting::gi()->add('formgroups_otto_paymentandshipping__fields__orderimport.shippingmethod', array(
        'i18n' => '{#i18n:formfields__orderimport.shippingmethod#}',
        'name' => 'orderimport.shippingmethod',
        'type' => 'select',
        'expert' => false,
        'cssclasses' => array('mljs-directbuy',),
    )
);

MLSetting::gi()->add('formgroups_otto_paymentandshipping__fields__orderimport.paymentmethod', array(
    'i18n' => '{#i18n:formfields__orderimport.paymentmethod#}',
    'name' => 'orderimport.paymentmethod',
    'type' => 'select',
    'expert' => false,
    'cssclasses' => array('mljs-directbuy',),
));

MLSetting::gi()->add('formgroups_otto__orderstatusimport__fields__orderimport.paymentstatus', array(
    'i18n' => '{#i18n:formfields_otto__orderimport.paymentstatus#}',
    'name' => 'orderimport.paymentstatus',
    'type' => 'select',
    'expert' => false,
    'cssclasses' => array('mljs-directbuy',),
));

MLSetting::gi()->add('formgroups_paymentandshipping', array(
    'legned' => array('i18n' => '{#i18n:formgroups_otto__paymentandshipping#}'),
    'fields' => array(
        'orderimport.paymentmethod' => '{#setting:formfields_otto__orderimport.paymentmethod#}',
        'shippingservice' => '{#setting:formfields_otto__shippingservice#}',
    ),
));

//$Config = MLSetting::gi()->get('formgroups__orderimport');
//Kint::dump($Config); // compare change in you see changes after that
