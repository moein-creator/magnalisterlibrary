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

MLI18n::gi()->add('formfields', array(
    'config_shopware5_invoice_documenttype'    => array(
        'label' => 'Rechnung<br>Shopware Dokument-Typ',
        'help'  => 'Wählen Sie hier den Dokument-Typen aus Ihrem Shopware Shop, welche Sie unter "Einstellungen > Grundeinstellungen > Shopeinstellungen > PDF-Belegerstellung" angelegt haben.',
    ),
    'config_shopware5_creditnote_documenttype' => array(
        'label' => 'Gutschrift<br>Shopware Dokument-Typ',
        'help'  => 'Wählen Sie hier den Dokument-Typen aus Ihrem Shopware Shop, welche Sie unter "Einstellungen > Grundeinstellungen > Shopeinstellungen > PDF-Belegerstellung" angelegt haben.',
    ),
));

MLI18n::gi()->shop_order_attribute_name = 'Shopware Freitextfeld';
MLI18n::gi()->shop_order_attribute_creation_instruction = 'Freitextfelder können Sie in Ihrem Shopware-Backend unter “Einstellungen” -> “Freitextfeld-Verwaltung” anlegen (Tabelle: Bestellung) und unter “Kunden” -> “Bestellungen” befüllen. Öffnen Sie dazu die entsprechende Bestellung und scrollen Sie in der Bestellübersicht nach unten zu “Freitextfelder”.';
