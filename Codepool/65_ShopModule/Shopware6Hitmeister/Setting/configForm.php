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
MLSetting::gi()->get('hitmeister_config_orderimport');//throws exception if not exists
MLSetting::gi()->add('hitmeister_config_orderimport', array(
    'importactive' => array(
        'fields' => array(
            'orderimport.shippingmethod' => array(//use string index to overwrite main setting
                'name' => 'orderimport.shippingmethod',
                'type' => 'select',
                'expert' => true,
            ),
            'orderimport.paymentmethod' => array(//use string index to overwrite main setting
                'name' => 'orderimport.paymentmethod',
                'type' => 'select',
                'expert' => true,
            ),
            array(
                'name' => 'orderimport.paymentstatus',
                'type' => 'select',
            ),
        ),
    ),
), true);

MLSetting::gi()->set('hitmeister_config_invoice__invoice__fields__invoiceDocumentType','{#setting:formfields__config_shopware6_invoice_documenttype#}');
MLSetting::gi()->set('hitmeister_config_invoice__invoice__fields__creditnoteDocumentType','{#setting:formfields__config_shopware6_creditnote_documenttype#}');
