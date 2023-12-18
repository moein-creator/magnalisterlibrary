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
 * (c) 2010 - 2020 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

MLSetting::gi()->add('formgroups_otto__orderstatus', array(
    'legend' => array('i18n' => '{#i18n:formgroups_otto__orderstatus#}'),
    'fields' => array(
        'orderstatus.sync' => '{#setting:formfields__orderstatus.sync#}',
        'orderstatus.standardshipping' => '{#setting:formfields_otto__orderstatus.sendcarrier#}',
        'orderstatus.forwardershipping' => '{#setting:formfields_otto__orderstatus.forwardercarrier#}',
        'orderstatus.shippedaddress' => '{#setting:formfields_otto__orderstatus.shippedaddress#}',
        'orderstatus.returncarrier' => '{#setting:formfields_otto__orderstatus.returncarrier#}',
        'orderstatus.canceled' => '{#setting:formfields__orderstatus.canceled#}',
    ),
));

MLSetting::gi()->add('formgroups_otto__orderstatusimport__fields__orderimport.paymentstatus', array(
    'i18n' => '{#i18n:formfields_otto__orderimport.paymentstatus#}',
    'name' => 'orderimport.paymentstatus',
    'type' => 'select',
    'expert' => false,
    'cssclasses' => array('mljs-directbuy',),
));
