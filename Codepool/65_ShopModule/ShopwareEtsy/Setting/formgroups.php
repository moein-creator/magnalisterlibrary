<?php

MLSetting::gi()->get('formgroups__orderimport');
MLSetting::gi()->add('formgroups__orderimport__fields__orderimport.shippingmethod', array(
    'i18n' => '{#i18n:formfields__orderimport.shippingmethod#}',
    'name' => 'orderimport.shippingmethod',
    'type' => 'select',
    'expert' => false,
), true);
MLSetting::gi()->add('formgroups__orderimport__fields__orderimport.paymentmethod', array(
    'i18n' => '{#i18n:formfields__orderimport.paymentmethod#}',
    'name' => 'orderimport.paymentmethod',
    'type' => 'select',
    'expert' => false,
), true);
MLSetting::gi()->add('formgroups__orderimport__fields__orderimport.paymentstatus', array(
    'i18n' => '{#i18n:formfields__orderimport.paymentstatus#}',
    'name' => 'orderimport.paymentstatus',
    'type' => 'select',
), true);
