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
 * (c) 2010 - 2023 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

MLSetting::gi()->add('generic_config_generic', array(
    'generic' => array(
        'fields' => array(
            array(
                'name' => 'orderimport.shop',
                'type' => 'select'
            ),
        ),
    )
), false);

/*
 * Preset a global required config value
 */
if (MLModule::gi()->getConfig('lang') == null) {
    MLModule::gi()->setConfig('lang', '1', true);
}
MLSetting::gi()->get('configuration');//throws exception if not exists
MLSetting::gi()->add('configuration', array(
    'orderimport' => array(
        'fields' => array(
            array(
                'name' => 'global.vat.matching',
                'i18n' => array(
                    'label' => MLI18n::gi()->get('orderimport_shopify_vatmatching_label'),
                    'help' => MLI18n::gi()->get('orderimport_shopifyvatmatching_help'),
                ),
                'type' => 'duplicate',
                'duplicate' => array(
                    'field' => array('type' => 'subFieldsContainer')
                ),
                'subfields' => array(
                    'selectVAT' => array(
                        'name' => 'global.vat.matching.collection',
                        'type' => 'select',
                        'i18n' => array(
                            'label' => MLI18n::gi()->get('orderimport_shopify_vatmatching_collection_label'),
                        ),
                    ),
                    'selectShippingCountry' => array(
                        'name' => 'global.vat.matching.shipping.country',
                        'type' => 'select',
                        'i18n' => array(
                            'label' => MLI18n::gi()->get('orderimport_shopify_vatmatching_shipping_country_label'),
                        ),
                    ),
                    'string' => array(
                        'name' => 'global.vat.matching.rate',
                        'type' => 'string',
                        'cascading' => true, 'i18n' => array(
                            'label' => MLI18n::gi()->get('orderimport_shopifyvatmatching_vatrate_label'),
                        ),
                    ),
                ),
            )
        )
    ),
), true);
