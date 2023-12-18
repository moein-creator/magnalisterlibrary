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

MLSetting::gi()->cdiscount_prepare_apply_form = array(
    'details' => array(
        'fields' => array(
            array(
                'name' => 'title',
                'type' => 'string',
                'singleproduct' => true,
            ),
            array(
                'name' => 'subtitle',
                'type' => 'string',
                'singleproduct' => true,
            ),
            array(
                'name' => 'description',
                'type' => 'text',
                'attributes' => array(
                    'rows' => '30',
                ),
                'singleproduct' => true,
            ),
            array(
                'name' => 'marketingdescription',
                'type' => 'wysiwyg',
                'singleproduct' => true,
            ),
            array(
                'name' => 'price',
                'type' => 'price',
                'currency' => 'EUR',
                'singleproduct' => true,
                'enabled' => false,
            ),
            array(
                'name' => 'images',
                'type' => 'imagemultipleselect',
                'singleproduct' => true,
            ),
        ),
    ),
    'categories' => array(
        'fields' => array(
            array(
                'name' => 'variationgroups',
                'type' => 'categoryselect',
                'subfields' => array(
                    'variationgroups.value' => array('name' => 'variationgroups.value', 'type' => 'categoryselect', 'cattype' => 'marketplace'),
                ),
            ),
            array(
                'name' => 'variationthemealldata',
                'classes' => array('ml-force-hidden'),
            ),
            array(
                'name' => 'variationthemecode'
            ),
        ),
    ),
    'variationmatching' => array(
        'type' => 'ajaxfieldset',
        'legend' => array(
            'template' => 'two-columns',
        ),
        'field' => array (
            'name' => 'variationmatching',
            'type' => 'ajax',
        ),
    ),
    'unit' => array(
        'fields' => array(
            array(
                'name' => 'itemcondition',
                'type' => 'select',
            ),
            array(
                'name' => 'preparationtime',
                'type' => 'string',
            ),
            array(
                'name' => 'shippingprofile',
                'type' => 'duplicate',
                'duplicate' => array(
                    'field' => array('type' => 'subFieldsContainer'),
                ),
                'subfields' => array(
                    array('name' => 'shippingprofilename', 'type' => 'select'),
                    array('name' => 'shippingfee', 'type' => 'string'),
                    array('name' => 'shippingfeeadditional', 'type' => 'string'),
                ),
            ),
            array(
                'name' => 'comment',
                'type' => 'text',
            ),
        ),
    ),
);

MLSetting::gi()->cdiscount_prepare_variations = array(
    'variations' => array(
        'fields' => array(
            array(
                'name' => 'variationgroups',
                'type' => 'categoryselect',
                'subfields' => array(
                    'variationgroups.value' => array('name' => 'variationgroups.value', 'type' => 'categoryselect', 'cattype' => 'marketplace'),
                ),
            ),
        ),
    ),
    'variationmatching' => array(
        'type' => 'ajaxfieldset',
        'legend' => array(
            'template' => 'two-columns',
        ),
        'field' => array (
            'name' => 'variationmatching',
            'type' => 'ajax',
        ),
    ),
    'action' => array(
        'legend' => array(
            'classes' => array(
                'mlhidden',
            ),
        ),
        'row' => array(
            'template' => 'action-row-row-row',
        ),
        'fields' => array(
            array(
                'name' => 'saveaction',
                'value' => 'save',
                'type' => 'submit',
                'position' => 'right',
            ),
            array(
                'name' => 'resetaction',
                'value' => 'reset',
                'type' => 'submit',
                'position' => 'left',
            ),
        ),
    ),
);
