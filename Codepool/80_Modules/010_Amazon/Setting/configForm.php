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

MLSetting::gi()->add('amazon_config_account', array(
    'tabident' => array(
        'legend' => array(
            'classes' => array('mlhidden'),
        ),
        'fields' => array(
            array(
                'name' => 'tabident',
                'type' => 'string',
            ),
        ),
    ),
    'account' => array(
        'fields' => array(
            array(
                'name' => 'site',
                'type' => 'selectsite'
            ),
            array(
                'name' => 'mwstoken',
                'type' => 'password',
                'savevalue' => '__saved__'
            ),
            array(
                'name' => 'merchantid',
                'type' => 'string',
            ),
            array(
                'name' => 'marketplaceid',
                'type' => 'string',
            ),
        ),
    )
), false);

MLSetting::gi()->add('amazon_config_prepare', array(
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
            array(
                'name' => 'itemcondition',
                'type' => 'select',
                'default' => 'New'
            ),
        ),
    ),
    'machingbehavior' => array(
        'fields' => array(
            array(
                'name' => 'multimatching',
                'type' => 'bool',
                'expert' => true,
            ),
            array(
                'name' => 'multimatching.itemsperpage',
                'type' => 'string',
                'expert' => true,
            ),
        ),
    ),
    'apply' => array(
        'fields' => array(
            array(
                'name' => 'prepare.manufacturerfallback',
                'type' => 'string',
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
                'default' => '1000'
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
    'shippingtemplate' => array(
        'fields' => array(
            array(
                'name' => 'shipping.template.active',
                'type' => 'bool'
            ),
            array(
                'name' => 'shipping.template',
                'type' => 'duplicate',
                'duplicate' => array(
                    'radiogroup' => 'default',
                    'field' => array('type' => 'subFieldsContainer')
                ),
                'subfields' => array(
                    array('name' => 'shipping.template.name', 'type' => 'string'),
                )
            ),
        )
    ),
    'b2b' => array(
        'fields' => array(
            array(
                'name' => 'b2bactive',
                'type' => 'b2bradio',
                'default' => 'false'
            ),
            array(
                'name' => 'b2b.tax_code',
                'type' => 'matching',
                'cssclass' => 'js-b2b',
            ),
            array(
                'name' => 'b2b.tax_code_container',
                'type' => 'duplicate',
                'cssclasses' => array('js-b2b'),
                'duplicate' => array(
                    'field' => array('type' => 'subFieldsContainer')
                ),
                'subfields' => array(
                    'select' => array(
                        'name' => 'b2b.tax_code_category',
                        'type' => 'select',
                        'cssclass' => 'js-b2b',
                        'breakbefore' => true,
                    ),
                    'ajax' => array(
                        'name' => 'b2b.tax_code_specific',
                        'type' => 'ajax',
                        'cssclass' => 'js-b2b',
                        'cascading' => true,
                        'breakbefore' => true,
                    ),
                ),
            ),
            array(
                'name' => 'b2bsellto',
                'type' => 'select',
                'cssclass' => 'js-b2b',
            ),
            array(
                'name' => 'b2b.price',
                'type' => 'subFieldsContainer',
                'subfields' => array(
                    'addkind' => array('name' => 'b2b.price.addkind', 'type' => 'select', 'cssclass' => 'js-b2b'),
                    'factor' => array('name' => 'b2b.price.factor', 'type' => 'string', 'cssclasses' => array('js-b2b')),
                    'signal' => array('name' => 'b2b.price.signal', 'type' => 'string', 'cssclasses' => array('js-b2b'))
                ),
            ),
            array(
                'name' => 'b2b.priceoptions',
                'type' => 'subFieldsContainer',
                'subfields' => array(
                    'group' => array('name' => 'b2b.price.group', 'type' => 'select', 'cssclass' => 'js-b2b'),
                    'usespecialoffer' => array('name' => 'b2b.price.usespecialoffer', 'type' => 'bool', 'cssclasses' => array('js-b2b')),
                ),
            ),
            array(
                'name' => 'b2bdiscounttype',
                'type' => 'b2bselect',
                'cssclass' => 'js-b2b',
            ),
            array(
                'name' => 'b2bdiscounttier1',
                'type' => 'subFieldsContainer',
                'subfields' => array(
                    'quantity' => array(
                        'name' => 'b2bdiscounttier1quantity',
                        'type' => 'string',
                        'default' => '0',
                        'cssclasses' => array('autoWidth', 'rightSpacer', 'js-b2b', 'js-b2b-tier'),
                    ),
                    'discount' => array(
                        'name' => 'b2bdiscounttier1discount',
                        'type' => 'string',
                        'default' => '0',
                        'cssclasses' => array('autoWidth', 'rightSpacer', 'js-b2b', 'js-b2b-tier'),
                    ),
                ),
            ),
            array(
                'name' => 'b2bdiscounttier2',
                'type' => 'subFieldsContainer',
                'subfields' => array(
                    'quantity' => array(
                        'name' => 'b2bdiscounttier2quantity',
                        'type' => 'string',
                        'default' => '0',
                        'cssclasses' => array('autoWidth', 'rightSpacer', 'js-b2b', 'js-b2b-tier'),
                    ),
                    'discount' => array(
                        'name' => 'b2bdiscounttier2discount',
                        'type' => 'string',
                        'default' => '0',
                        'cssclasses' => array('autoWidth', 'rightSpacer', 'js-b2b', 'js-b2b-tier'),
                    ),
                ),
            ),
            array(
                'name' => 'b2bdiscounttier3',
                'type' => 'subFieldsContainer',
                'subfields' => array(
                    'quantity' => array(
                        'name' => 'b2bdiscounttier3quantity',
                        'type' => 'string',
                        'default' => '0',
                        'cssclasses' => array('autoWidth', 'rightSpacer', 'js-b2b', 'js-b2b-tier'),
                    ),
                    'discount' => array(
                        'name' => 'b2bdiscounttier3discount',
                        'type' => 'string',
                        'default' => '0',
                        'cssclasses' => array('autoWidth', 'rightSpacer', 'js-b2b', 'js-b2b-tier'),
                    ),
                ),
            ),
            array(
                'name' => 'b2bdiscounttier4',
                'type' => 'subFieldsContainer',
                'subfields' => array(
                    'quantity' => array(
                        'name' => 'b2bdiscounttier4quantity',
                        'type' => 'string',
                        'default' => '0',
                        'cssclasses' => array('autoWidth', 'rightSpacer', 'js-b2b', 'js-b2b-tier'),
                    ),
                    'discount' => array(
                        'name' => 'b2bdiscounttier4discount',
                        'type' => 'string',
                        'default' => '0',
                        'cssclasses' => array('autoWidth', 'rightSpacer', 'js-b2b', 'js-b2b-tier'),
                    ),
                ),
            ),
            array(
                'name' => 'b2bdiscounttier5',
                'type' => 'subFieldsContainer',
                'subfields' => array(
                    'quantity' => array(
                        'name' => 'b2bdiscounttier5quantity',
                        'type' => 'string',
                        'default' => '0',
                        'cssclasses' => array('autoWidth', 'rightSpacer', 'js-b2b', 'js-b2b-tier'),
                    ),
                    'discount' => array(
                        'name' => 'b2bdiscounttier5discount',
                        'type' => 'string',
                        'default' => '0',
                        'cssclasses' => array('autoWidth', 'rightSpacer', 'js-b2b', 'js-b2b-tier'),
                    ),
                ),
            ),
        ),
    ),
), false);

MLSetting::gi()->add('amazon_config_price', array(
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

MLSetting::gi()->add('amazon_config_sync', array(
    'sync' => array(
        'fields' => array(
            array(
                'name' => 'stocksync.tomarketplace',
                'type' => 'select',/*
                'type' => 'addon_select',
                'addonsku' => 'FastSyncInventory',*/
            ),
            array(
                'name' => 'stocksync.frommarketplace',
                'type' => 'select',
            ),
            array(
                'name' => 'inventorysync.price',
                'type' => 'select',
            ),
        )
    )
), false);

MLSetting::gi()->add('amazon_config_orderimport', array(
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
            'orderimport.amazoncommunicationrules.blacklisting' => array(
                'name' => 'orderimport.amazoncommunicationrules.blacklisting',
                'type' => 'bool',
                'default' => true,
            ),
            array(
                'name' => 'customergroup',
                'type' => 'select',
            ),
            'orderimport.shop' => array(
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
                    'string' => array('name' => 'orderimport.shippingmethod.name', 'type' => 'string','default' => 'amazon',)
                ),
                'expert' => true,
            ),
            'orderimport.paymentmethod' => array(
                'name' => 'orderimport.paymentmethod',
                'type' => 'selectwithtextoption',
                'subfields' => array(
                    'select' => array('name' => 'orderimport.paymentmethod', 'type' => 'select'),
                    'string' => array('name' => 'orderimport.paymentmethod.name', 'type' => 'string','default' => 'amazon',)
                ),
                'expert' => true,
            ),
            'orderimport.fbablockimport' => array(
                'name' => 'orderimport.fbablockimport',
                'type' => 'bool',
                'default' => false,
                'expert' => true,
            ),
            array(
                'name' => 'orderstatus.fba',
                'type' => 'select',
            ),
            'orderimport.fbashippingmethod' => array(
                'name' => 'orderimport.fbashippingmethod',
                'type' => 'selectwithtextoption',
                'subfields' => array(
                    'select' => array('name' => 'orderimport.fbashippingmethod', 'type' => 'select'),
                    'string' => array('name' => 'orderimport.fbashippingmethod.name', 'type' => 'string','default' => 'amazon',)
                ),
                'expert' => true,
            ),
            'orderimport.fbapaymentmethod' => array(
                'name' => 'orderimport.fbapaymentmethod',
                'type' => 'selectwithtextoption',
                'subfields' => array(
                    'select' => array('name' => 'orderimport.fbapaymentmethod', 'type' => 'select'),
                    'string' => array('name' => 'orderimport.fbapaymentmethod.name', 'type' => 'string','default' => 'amazon',)
                ),
                'expert' => true,
            ),
            'orderimport.amazonpromotionsdiscount' => array(
                'name' => 'orderimport.amazonpromotionsdiscount',
                'type' => 'amazon_promotions_settings',
                'subfields' => array(
                    'product' => array(
                        'name' => 'orderimport.amazonpromotionsdiscount.products_sku',
                        'type' => 'string',
                        'default' => '__AMAZON_DISCOUNT__',
                    ),
                    'shipping' => array(
                        'name' => 'orderimport.amazonpromotionsdiscount.shipping_sku',
                        'type' => 'string',
                        'default' => '__AMAZON_SHIPPING_DISCOUNT__',
                    ),
                ),
                'expert' => true,
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
            /*//{search: 1427198983}
            array(
                'name' => 'mwst.shipping',
                'type' => 'string',
                'default' => 19,
            ),
            //*/
        ),
    ),
    'orderstatus' => array(
        'fields' => array(
            array(
                'i18n' => '{#i18n:formfields__orderstatus.sync#}',
                'name' => 'orderstatus.sync',
                'type' => 'select',
            ),
            'orderstatus.carrier' => array(
                'name' => 'orderstatus.carrier',
                'type' => 'selectwithtmatchingoption',
                'subfields' => array(
                    'select' => array(
                        'i18n' => array('label' => '',),
                        'name' => 'orderstatus.carrier.select',
                        'required' => true,
                        'matching' => 'matchShopShippingOptions', //must be the same as value defined in ConfigData key value for matching
                        'type' => 'am_attributesselect'
                    ),
                    'matching' => array(
                        'i18n' => array('label' => '', ),
                        'name' => 'orderstatus.carrier.duplicate',
                        'norepeat_included' => true,
                        'type' => 'duplicate',
                        'duplicate' => array(
                            'field' => array(
                                'type' => 'subFieldsContainer'
                            )
                        ),
                        'subfields' => array(
                            array(
                                'i18n' => array('label' => ''),
                                'name' => 'orderstatus.carrier.matching',
                                'breakbefore' => true,
                                'type' => 'matchingcarrier',
                                'cssclasses' => array('tableHeadCarrierMatching')

                            ),
                        ),
                    ),
                    'database' => array(
                        'i18n' => array('label' => '',),
                        'name' => 'orderstatus.carrier.database',
                        'type' => 'subFieldsContainer',
                        'subfields' => array(
                            'table' => array('name' => 'orderstatus.carrier.database.table', 'type' => 'select', 'i18n' => array('label' => '',),),
                            'column' => array('name' => 'orderstatus.carrier.database.column', 'type' => 'select', 'i18n' => array('label' => '',),),
                            'alias' => array('name' => 'orderstatus.carrier.database.alias', 'type' => 'string', 'default' => '', 'i18n' => array('label' => '',),),
                        ),
                    ),
                    'freetext' => array(
                        'name' => 'orderstatus.carrier.freetext',
                        'type' => 'string'
                    ),
                ),
            ),
            'orderstatus.shipmethod' => array(
                'name' => 'orderstatus.shipmethod',
                'type' => 'selectwithtmatchingoption',
                'subfields' => array(
                    'select' => array(
                        'i18n' => array('label' => '',),
                        'name' => 'orderstatus.shipmethod.select',
                        'required' => true,
                        'matching' => 'matchShopShippingOptions', //must be the same as value defined in ConfigData key value for matching
                        'type' => 'am_attributesselect'
                    ),
                    'matching' => array(
                        'i18n' => array('label' => '', ),
                        'name' => 'orderstatus.shipmethod.duplicate',
                        'norepeat_included' => true,
                        'type' => 'duplicate',
                        'duplicate' => array(
                            'field' => array(
                                'type' => 'subFieldsContainer'
                            )
                        ),
                        'subfields' => array(
                            array(
                                'i18n' => array('label' => ''),
                                'name' => 'orderstatus.shipmethod.matching',
                                'breakbefore' => true,
                                'type' => 'amazon_matchingshipmethod',
                                'cssclasses' => array('tableHeadCarrierMatching')

                            ),
                        ),
                    ),
                    'database' => array(
                        'i18n' => array('label' => '',),
                        'name' => 'orderstatus.shipmethod.database',
                        'type' => 'subFieldsContainer',
                        'subfields' => array(
                            'table' => array('name' => 'orderstatus.carrier.database.table', 'type' => 'select', 'i18n' => array('label' => '',),),
                            'column' => array('name' => 'orderstatus.carrier.database.column', 'type' => 'select', 'i18n' => array('label' => '',),),
                            'alias' => array('name' => 'orderstatus.carrier.database.alias', 'type' => 'string', 'default' => '', 'i18n' => array('label' => '',),),
                        ),
                    ),
                    'freetext' => array(
                        'i18n' => array('label' => '',),
                        'name' => 'orderstatus.shipmethod.freetext',
                        'type' => 'string'
                    ),
                ),
            ),
            'orderstatus.shippedaddress' => array(
                'name' => 'orderstatus.shippedaddress',
                'type' => 'duplicate',
                'duplicate' => array(
                    'field' => array('type' => 'orderstatus_shipped')
                ),
                'subfields' => array(
                    array(
                        'name' => 'orderstatus.shippedaddress.name',
                        'type' => 'string'
                    ),
                    array(
                        'name' => 'orderstatus.shippedaddress.line1',
                        'type' => 'string'
                    ),
                    array(
                        'name' => 'orderstatus.shippedaddress.line2',
                        'type' => 'string'
                    ),
                    array(
                        'name' => 'orderstatus.shippedaddress.line3',
                        'type' => 'string'
                    ),
                    array(
                        'name' => 'orderstatus.shippedaddress.city',
                        'type' => 'string'
                    ),
                    array(
                        'name' => 'orderstatus.shippedaddress.county',
                        'type' => 'string'
                    ),
                    array(
                        'name' => 'orderstatus.shippedaddress.stateorregion',
                        'type' => 'string'
                    ),
                    array(
                        'name' => 'orderstatus.shippedaddress.postalcode',
                        'type' => 'string'
                    ),
                    array(
                        'name' => 'orderstatus.shippedaddress.countrycode',
                        'type' => 'select'
                    ),
                ),
            ),

            /* OLD
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
            */
            array(
                'name' => 'orderstatus.cancelled',
                'type' => 'select'
            ),
        ),
    ),
), false);

MLSetting::gi()->add('amazon_config_emailtemplate', array(
    'mail' => array(
        'fields' => array(
            array(
                'name' => 'mail.send',
                'type' => 'radio',
                'default' => 'false',
            ),
            array(
                'name' => 'mail.originator.name',
                'type' => 'string',
                'default' => '{#i18n:amazon_config_account_emailtemplate_sender#}',
            ),
            array(
                'name' => 'mail.originator.adress',
                'type' => 'string',
                'default' => '{#i18n:amazon_config_account_emailtemplate_sender_email#}',
            ),
            array(
                'name' => 'mail.subject',
                'type' => 'string',
                'default' => '{#i18n:amazon_config_account_emailtemplate_subject#}',
            ),
            array(
                'name' => 'mail.content',
                'type' => 'configMailContentContainer',
                'default' => '{#i18n:amazon_config_account_emailtemplate_content#}',
                'resetdefault' => '{#i18n:amazon_config_account_emailtemplate_content#}',
            ),
            array(
                'name' => 'mail.copy',
                'type' => 'radio',
                'default' => 'true',
            ),
        ),
    ),
), false);

MLSetting::gi()->add('amazon_config_shippinglabel', array(
    'shippingaddresses' => array(
        'fields' => array(
            array(
                'name' => 'shippinglabel.address',
                'type' => 'duplicate',
                'duplicate' => array(
                    'radiogroup' => 'default',
                    'field' => array('type' => 'amazon_shippinglabel_address')
                ),
                'subfields' => array(
                    array('name' => 'shippinglabel.address.name',        'type' => 'string'),
                    array('name' => 'shippinglabel.address.company',     'type' => 'string'),
                    array('name' => 'shippinglabel.address.streetandnr', 'type' => 'string'),
                    array('name' => 'shippinglabel.address.city',        'type' => 'string'),
                    array('name' => 'shippinglabel.address.state',       'type' => 'string'),
                    array('name' => 'shippinglabel.address.zip',         'type' => 'string'),
                    array('name' => 'shippinglabel.address.country',     'type' => 'select'),
                    array('name' => 'shippinglabel.address.phone',       'type' => 'string'),
                    array('name' => 'shippinglabel.address.email',       'type' => 'string'),
                )
            )
        ),
    ),
    'shippingservice' => array(
        'fields' => array(
            array(
                'name' => 'shippingservice.carrierwillpickup',
                'type' => 'radio',
                'default' => 'false',
            ),
            array(
                'name' => 'shippingservice.deliveryexperience',
                'type' => 'select',
            ),
        ),
    ),
    'shippinglabel' => array(
        'fields' => array(
            array(
                'name' => 'shippinglabel.fallback.weight',
                'type' => 'string',
                'default' => 0.00
            ),
            array(
                'name' => 'shippinglabel.weight.unit',
                'type' => 'select',
            ),
            array(
                'name' => 'shippinglabel.default.dimension',
                'type' => 'duplicate',
                'duplicate' => array(
                    'radiogroup' => 'default',
                    'field' => array('type' => 'amazon_shippinglabel_dimension')
                ),
                'subfields' => array(
                    array('name' => 'shippinglabel.default.dimension.text', 'type' => 'string'),
                    array('name' => 'shippinglabel.default.dimension.length', 'type' => 'string', 'default' => 0.00),
                    array('name' => 'shippinglabel.default.dimension.width', 'type' => 'string', 'default' => 0.00),
                    array('name' => 'shippinglabel.default.dimension.height', 'type' => 'string', 'default' => 0.00),
                )
            ),
            array(
                'name' => 'shippinglabel.size.unit',
                'type' => 'select',
            ),
        )
    ),
), false);

MLSetting::gi()->add('amazon_config_vcs', array(
    'amazonvcs' => array(
        'fields' => array(
            array(
                'name' => 'amazonvcs.option',
                'type' => 'select',
                'default' => 'off',
            ),
            array(
                'name' => 'amazonvcs.invoice',
                'type' => 'select',
                'default' => '',
                'cssclasses' => array('ml-uploadInvoiceOption')
            ),
        ),
    ),
    'amazonvcsinvoice' => array(
        'fields' => array(
            array(
                'name' => 'amazonvcsinvoice.invoicedir',
                'type' => 'button',
                'target' => 'blank',
            ),
            array(
                'name' => 'amazonvcsinvoice.mailcopy',
                'type' => 'string',
                'default' => '',
                'placeholder' => 'your@mail.com'
            ),
            'amazonvcsinvoice.invoiceprefix' => array(
                'name' => 'amazonvcsinvoice.invoiceprefix',
                'type' => 'string',
            ),
            'amazonvcsinvoice.reversalinvoiceprefix' => array(
                'name' => 'amazonvcsinvoice.reversalinvoiceprefix',
                'type' => 'string',
            ),
            array(
                'name' => 'amazonvcsinvoice.companyadressleft',
                'type' => 'string',
            ),
            array(
                'name' => 'amazonvcsinvoice.companyadressright',
                'type' => 'text',
                'attributes' => array(
                    'rows' => '4',
                ),
            ),
            array(
                'name' => 'amazonvcsinvoice.headline',
                'type' => 'string',
            ),
            array(
                'name' => 'amazonvcsinvoice.invoicehintheadline',
                'type' => 'string',
            ),
            array(
                'name' => 'amazonvcsinvoice.invoicehinttext',
                'type' => 'text',
                'attributes' => array(
                    'rows' => '8',
                ),
            ),
            array(
                'name' => 'amazonvcsinvoice.footercell1',
                'type' => 'text',
                'attributes' => array(
                    'rows' => '4',
                ),
            ),
            array(
                'name' => 'amazonvcsinvoice.footercell2',
                'type' => 'text',
                'attributes' => array(
                    'rows' => '4',
                ),
            ),
            array(
                'name' => 'amazonvcsinvoice.footercell3',
                'type' => 'text',
                'attributes' => array(
                    'rows' => '4',
                ),
            ),
            array(
                'name' => 'amazonvcsinvoice.footercell4',
                'type' => 'text',
                'attributes' => array(
                    'rows' => '4',
                ),
            ),
            array(
                'name' => 'amazonvcsinvoice.preview',
                'type' => 'amazon_invoicepreview',
            ),
        ),
        'cssclasses' => array('ml-magnalisterInvoiceGenerator')
    ),
    'erpInvoice' => '{#setting:formgroups__config_erpInvoice#}',
), false);
