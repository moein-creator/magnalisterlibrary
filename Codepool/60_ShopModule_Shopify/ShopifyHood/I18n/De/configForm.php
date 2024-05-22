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

MLI18n::gi()->add('hood_config_orderimport', array(
    'field' => array(
        'orderimport.paymentstatus' => array(
            'label' => 'Zahlstatus im Shop',
            'help' => '',
            'hint' => '',
        ),
    ),
), false);

MLI18n::gi()->{'hood_config_orderimport__field__customergroup__help'} = '{#i18n:global_config_orderimport_field_customergroup_help#}';

MLI18n::gi()->{'hood_config_producttemplate__field__template.name__help'} = '<dl>
    <dt>Name des Produkts auf Hood.de</dt>
    <dd>Einstellung, wie das Produkt auf Hood.de hei&szlig;en soll.
        Der Platzhalter <b>#TITLE#</b> wird automatisch durch den Produktnamen aus dem Shop ersetzt..</dd></dl>';
MLI18n::gi()->{'hood_config_producttemplate__field__template.name__hint'} = 'Platzhalter: #TITLE# - Produktname';
