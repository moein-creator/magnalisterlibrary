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

/**
 * all fields include i18n directly
 */
MLSetting::gi()->add('formfields_metro', array(
    'mwst' => array(
        'i18n' => '{#i18n:formfields_metro__mwst#}',
        'name' => 'mwst',
        'type' => 'string',
    ),
    'usevariations' => array(
        'i18n' => '{#i18n:formfields_metro__usevariations#}',
        'name' => 'usevariations',
        'type' => 'bool',
        'default' => true
    ),
    'processingtime' => array(
        'i18n' => '{#i18n:formfields_metro__processingtime#}',
        'name' => 'processingtime',
        'type' => 'select',
    ),
    'maxprocessingtime' => array(
        'i18n' => '{#i18n:formfields_metro__maxprocessingtime#}',
        'name' => 'maxprocessingtime',
        'type' => 'select',
    ),
    'businessmodel' => array(
        'i18n' => '{#i18n:formfields_metro__businessmodel#}',
        'name' => 'businessmodel',
        'type' => 'select',
        'default' => '',
    ),
    'freightforwarding' => array(
        'i18n' => '{#i18n:formfields_metro__freightforwarding#}',
        'name' => 'freightforwarding',
        'type' => 'radio',
        'values' => '{#i18n:formfields_metro_freightforwarding_values#}',
        'default' => 'false',
    ),
    'shippingprofile' => array(
        'i18n' => '{#i18n:formfields_metro__shippingprofile#}',
        'name' => 'ShippingProfile',
    ),
    'prepare_title' => array(
        'i18n' => '{#i18n:formfields_metro__prepare_title#}',
        'name' => 'Title',
        'type' => 'string',
        'singleproduct' => true,
    ),
    'prepare_shortdescription' => array(
        'i18n' => '{#i18n:formfields_metro__prepare_shortdescription#}',
        'name' => 'ShortDescription',
        'type' => 'string',
        'singleproduct' => true,
    ),
    'prepare_description' => array(
        'i18n' => '{#i18n:formfields_metro__prepare_description#}',
        'name' => 'Description',
        'type' => 'wysiwyg',
        'singleproduct' => true,
    ),
    'prepare_image' => array(
        'i18n' => '{#i18n:formfields_metro__prepare_image#}',
        'name' => 'Images',
        'type' => 'imagemultipleselect',
        'singleproduct' => true,
    ),

    'prepare_gtin' => array(
        'i18n' => '{#i18n:formfields_metro__prepare_gtin#}',
        'name' => 'GTIN',
        'type' => 'string',
        'singleproduct' => true,
    ),

    'prepare_manufacturer' => array(
        'i18n' => '{#i18n:formfields_metro__prepare_manufacturer#}',
        'name' => 'Manufacturer',
        'type' => 'string',
        'singleproduct' => true,
    ),
    'prepare_manufacturerpartnumber' => array(
        'i18n' => '{#i18n:formfields_metro__prepare_manufacturerpartnumber#}',
        'name' => 'ManufacturerPartNumber',
        'type' => 'string',
        'singleproduct' => true,
    ),
    'prepare_brand' => array(
        'i18n' => '{#i18n:formfields_metro__prepare_brand#}',
        'name' => 'Brand',
        'type' => 'string',
        'singleproduct' => true,
    ),
    'prepare_msrp' => array(
        'i18n' => '{#i18n:formfields_metro__prepare_msrp#}',
        'name' => 'ManufacturersSuggestedRetailPrice',
        'type' => 'string',
        'optional' => array('defaultvalue' => false),//it set optional select to false by default
        'singleproduct' => true,
    ),

    'prepare_feature' => array(
        'i18n' => '{#i18n:formfields_metro__prepare_feature#}',
        'name' => 'Feature',
        'type' => 'metro_multiple',
        'metro_multiple' => array(
            'max' => 5,
            'field' => array(
                'type' => 'string'
            ),
        ),
        'singleproduct' => true,
    ),
    'prepare_category' => array(
        'label' => '{#i18n:formfields_metro__prepare_category#}',
        'name' => 'variationgroups',
        'type' => 'categoryselect',
        'subfields' => array(
            'variationgroups.value' => array('name' => 'variationgroups.value', 'type' => 'categoryselect', 'cattype' => 'marketplace'),
        ),
    ),
    'orderstatus.carrier' => array(
        'i18n' => '{#i18n:formfields_metro__orderstatus.carrier#}',
        'name' => 'orderstatus.carrier',
        'type' => 'string',
    ),
    'orderstatus.cancellationreason' => array(
        'i18n' => '{#i18n:formfields_metro__orderstatus.cancellationreason#}',
        'name' => 'orderstatus.cancellationreason',
        'type' => 'select',
    ),
    'prepare_saveaction'           => array(
        'name' => 'saveaction',
        'type' => 'submit',
        'value' => 'save',
        'position' => 'right',
    ),
    'prepare_resetaction'          => array(
        'name'     => 'resetaction',
        'type'     => 'submit',
        'value'    => 'reset',
        'position' => 'left',
    ),
    'prepare_variationgroups'      => array(
        'label'     => '{#i18n:formfields__prepare_variationgroups#}',
        'name'      => 'variationgroups',
        'type'      => 'categoryselect',
        'subfields' => array(
            'variationgroups.value' => array('name' => 'variationgroups.value', 'type' => 'categoryselect', 'cattype' => 'marketplace', 'value' => null),
        ),
    ),
    'config_magnaInvoice'          => array(
        'i18n' => '{#i18n:formfields_metro__erpInvoiceDestination#}',
        'name' => 'invoice.erpinvoicedestination',
        'type' => 'string',
    ),

));
