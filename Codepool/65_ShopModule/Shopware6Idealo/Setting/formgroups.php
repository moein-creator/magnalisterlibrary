<?php

MLSetting::gi()->get('formgroups_idealo__orderimport');

MLSetting::gi()->add('formgroups_idealo__orderimport__fields__orderimport.shippingmethod', array(
    'i18n' => '{#i18n:formfields__orderimport.shippingmethod#}',
    'name' => 'orderimport.shippingmethod',
    'type' => 'select',
    'expert' => false,
    'cssclasses' => array('mljs-directbuy',),
        )
        , true);
MLSetting::gi()->add('formgroups_idealo__orderimport__fields__orderimport.paymentmethod', array(
    'i18n' => '{#i18n:formfields__orderimport.paymentmethod#}',
    'name' => 'orderimport.paymentmethod',
    'type' => 'select',
    'expert' => false,
    'cssclasses' => array('mljs-directbuy',),
        ), true);
MLSetting::gi()->add('formgroups_idealo__orderimport__fields__orderimport.paymentstatus', array(
    'i18n' => '{#i18n:formfields__orderimport.paymentstatus#}',
    'name' => 'orderimport.paymentstatus',
    'type' => 'select',
    'cssclasses' => array('mljs-directbuy',),
        ), true);
