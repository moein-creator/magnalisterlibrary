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

MLI18n::gi()->metro_config_general_autosync = 'Automatische Synchronisierung per CronJob (empfohlen)';
MLI18n::gi()->metro_config_general_nosync = 'keine Synchronisierung';
MLI18n::gi()->metro_config_account_title = 'Zugangsdaten';
MLI18n::gi()->metro_config_account_prepare = 'Artikelvorbereitung';
MLI18n::gi()->metro_config_account_price = 'Preisberechnung';
MLI18n::gi()->metro_config_account_sync = 'Preis und Lager';
MLI18n::gi()->metro_config_account_orderimport = 'Bestellungen';
MLI18n::gi()->metro_config_invoice = 'Invoices';
MLI18n::gi()->metro_config_account_emailtemplate = 'Promotion-E-Mail Template';
MLI18n::gi()->metro_config_account_producttemplate = 'Produkt Template';

MLI18n::gi()->{'formfields_metro_freightforwarding_values'} = array(
    'true' => 'Ja',
    'false' => 'Nein',
);
MLI18n::gi()->{'formfields_metro__orderstatus.accepted'} = array(
    'label' => 'Bestellung akzeptiert  mit',
    'help' => '',
);

MLI18n::gi()->{'formgroups_legend_quantity'} = 'Lager';


MLI18n::gi()->metro_configform_orderstatus_sync_values = array(
    'auto' => '{#i18n:metro_config_general_autosync#}',
    'no' => '{#i18n:metro_config_general_nosync#}',
);
MLI18n::gi()->metro_configform_sync_values = array(
    'auto' => '{#i18n:metro_config_general_autosync#}',
    //'auto_fast' => 'Schnellere automatische Synchronisation cronjob (auf 15 Minuten)',
    'no' => '{#i18n:metro_config_general_nosync#}',
);
MLI18n::gi()->metro_configform_stocksync_values = array(
    'rel' => 'Bestellung reduziert Shop-Lagerbestand (empfohlen)',
    'no' => '{#i18n:metro_config_general_nosync#}',
);
MLI18n::gi()->metro_configform_pricesync_values = array(
    'auto' => '{#i18n:metro_config_general_autosync#}',
    'no' => '{#i18n:metro_config_general_nosync#}',
);

MLI18n::gi()->metro_configform_orderimport_payment_values = array(
    'textfield' => array(
        'title' => 'Aus Textfeld',
        'textoption' => true
    ),
    'matching' => array(
        'title' => 'Automatische Zuordnung',
    ),
);

MLI18n::gi()->metro_configform_orderimport_shipping_values = array(
    'textfield' => array(
        'title' => 'Aus Textfeld',
        'textoption' => true
    ),
    'matching' => array(
        'title' => 'Automatische Zuordnung',
    ),
);

MLI18n::gi()->metro_config_sync_inventory_import = array(
    'true' => 'Ja',
    'false' => 'Nein'
);

MLI18n::gi()->metro_config_account_emailtemplate_sender = 'Beispiel-Shop';
MLI18n::gi()->metro_config_account_emailtemplate_sender_email = 'beispiel@onlineshop.de';
MLI18n::gi()->metro_config_account_emailtemplate_subject = 'Ihre Bestellung bei #SHOPURL#';
MLI18n::gi()->metro_config_producttemplate_content = '<p>#TITLE#</p>'.
    '<p>#ARTNR#</p>'.
    '<p>#SHORTDESCRIPTION#</p>'.
    '<p>#PICTURE1#</p>'.
    '<p>#PICTURE2#</p>'.
    '<p>#PICTURE3#</p>'.
    '<p>#DESCRIPTION#</p>';
MLI18n::gi()->metro_config_emailtemplate_content = '
 <style><!--
body {
    font: 12px sans-serif;
}
table.ordersummary {
	width: 100%;
	border: 1px solid #e8e8e8;
}
table.ordersummary td {
	padding: 3px 5px;
}
table.ordersummary thead td {
	background: #cfcfcf;
	color: #000;
	font-weight: bold;
	text-align: center;
}
table.ordersummary thead td.name {
	text-align: left;
}
table.ordersummary tbody tr.even td {
	background: #e8e8e8;
	color: #000;
}
table.ordersummary tbody tr.odd td {
	background: #f8f8f8;
	color: #000;
}
table.ordersummary td.price,
table.ordersummary td.fprice {
	text-align: right;
	white-space: nowrap;
}
table.ordersummary tbody td.qty {
	text-align: center;
}
--></style>
<p>Hallo #FIRSTNAME# #LASTNAME#,</p>
<p>vielen Dank f&uuml;r Ihre Bestellung! Sie haben &uuml;ber #MARKETPLACE# in unserem Shop folgendes bestellt:</p>
#ORDERSUMMARY#
<p>Zuz&uuml;glich etwaiger Versandkosten.</p>
<p>&nbsp;</p>
<p>Mit freundlichen Gr&uuml;&szlig;en,</p>
<p>Ihr Online-Shop-Team</p>';

MLI18n::gi()->add('metro_config_account', array(
    'legend' => array(
        'account' => 'Zugangsdaten',
        'tabident' => ''
    ),
    'field' => array(
        'tabident' => array(
            'label' => '{#i18n:ML_LABEL_TAB_IDENT#}',
            'help' => '{#i18n:ML_TEXT_TAB_IDENT#}'
        ),
        'clientkey' => array(
            'label' => 'METRO-Client-Key',
            'help' => 'Geben Sie hier den “METRO-Client-Key” ein.<br>Aktuell können Sie diesen ausschließlich beim METRO Marktplatz Seller-Support anfordern. Schreiben Sie dazu eine E-Mail an: seller@metro-marketplace.eu',
        ),
        'secretkey' => array(
            'label' => 'METRO-Secret-Key',
            'help' => 'Geben Sie hier den “METRO-Secret-Key” ein.<br>Aktuell können Sie diesen ausschließlich beim METRO Marktplatz Seller-Support anfordern. Schreiben Sie dazu eine E-Mail an: seller@metro-marketplace.eu',
        ),
        'shippingdestination' => array(
            'label' => 'METRO Site',
            'help' => 'METRO requirement: Currently no cross border trade is possible.',
            'hint' => 'On which METRO Marketplace country should your products be sold',
        ),
        'shippingorigin' => array(
            'label' => 'Shipping from',
            'help' => 'METRO requirement: Currently no cross border trade is possible.',
            'hint' => 'From which country are your products shipped',
        ),
    ),
), false);


MLI18n::gi()->add('metro_config_prepare', array(
    'legend' => array(
        'prepare' => 'Artikelvorbereitung',
        'pictures' => 'Einstellungen f&uuml;r Bilder',
        'shipping' => 'Versand',
        'upload' => 'Artikel hochladen: Voreinstellungen',
    ),
    'field' => array(
        'processingtime' => array(
            'label' => 'Min. Lieferzeit in Werktagen',
            'help' => 'Tragen Sie hier ein, wie viele Werktage mindestens vom Zeitpunkt der Bestellung durch den Kunden es bis zum Erhalt des Pakets dauert',
        ),
        'maxprocessingtime' => array(
            'label' => 'Max. Lieferzeit in Werktagen',
            'help' => 'Tragen Sie hier ein, wie viele Werktage maximal vom Zeitpunkt der Bestellung durch den Kunden es bis zum Erhalt des Pakets dauert',
        ),
        'businessmodel' => array(
            'label' => 'Käufergruppe festlegen',
            'help' => 'Ordnen Sie das Produkt einer Käufergruppe zu:<br>
                <ul>
                    <li>B2C und B2B: Produkt richtet sich an beide Käufergruppen</li>
                    <li>B2B: Produkt richtet sich an gewerbliche Endkunden</li>
                </ul>
                ',
        ),
        'freightforwarding' => array(
            'label' => 'Lieferung per Spedition',
            'help' => 'Geben Sie an, ob Ihr Produkt per Spedition versendet wird.',
        ),
        'shippingprofile' => array(
            'label' => 'Versandkosten-Profile',
            'help' => 'Legen Sie hier ihre Versandkosten-Profile an. Sie können für jedes Profil unterschiedliche Versandkosten angeben (Beispiel: 4.95) und ein Standard-Profil bestimmen. Die angegebenen Versandkosten werden beim Produkt-Upload zum Artikelpreis hinzugerechnet, da Waren auf dem METRO Marktplatz ausschließlich versandkostenfrei eingestellt werden können.',
            'hint' => '<span style="color: red">Der hier definierte Versandkostenaufschlag addiert sich zu der "Preisberechung" (Reiter: "Preis und Lager")</span><br><br>Bitte verwenden Sie den Punkt (.) als Trennzeichen für Dezimalstellen.',
        ),
        'shippingprofile.name' => array(
            'label' => 'Name des Versandkosten-Profils',
        ),
        'shippingprofile.cost' => array(
            'label' => 'Versandkostenaufschlag (Betrag)',
        )
    )
), false);

MLI18n::gi()->add('formgroups_metro', array(
    'orderstatus' => 'Synchronisation des Bestell-Status vom Shop zu METRO',
));
MLI18n::gi()->{'metro_config_priceandstock__field__price.addkind__label'} = '';
MLI18n::gi()->{'metro_config_priceandstock__field__price.factor__label'}='';
MLI18n::gi()->{'metro_config_priceandstock__field__price__label'}='Preis';
MLI18n::gi()->{'metro_config_priceandstock__field__price__hint'} = '<span style="color: red">Zu dem hier definierten Preis addiert sich der unter "Artikelvorbereitung" ausgewählte Versandkostenaufschlag</span>';
MLI18n::gi()->{'metro_config_priceandstock__field__price__help'} = 'Geben Sie einen prozentualen oder fest definierten Preis Auf- oder Abschlag an. Abschlag mit vorgesetztem Minus-Zeichen.<br><br><span style="color: red">Zu dem hier definierten Preis addiert sich der unter "Artikelvorbereitung" ausgewählte Versandkostenaufschlag</span>';
MLI18n::gi()->{'metro_config_priceandstock__field__price.signal__label'} = 'Nachkommastelle';
MLI18n::gi()->{'metro_config_priceandstock__field__price.signal__help'} = 'Dieses Textfeld wird beim &Uuml;bermitteln der Daten zu ebay als Nachkommastelle an Ihrem Preis &uuml;bernommen.<br/><br/>
                <strong>Beispiel:</strong> <br />
                Wert im Textfeld: 99 <br />
                Preis-Ursprung: 5.58 <br />
                Finales Ergebnis: 5.99 <br /><br />
                Die Funktion hilft insbesondere bei prozentualen Preis-Auf-/Abschl&auml;gen.<br/>
                Lassen Sie das Feld leer, wenn Sie keine Nachkommastelle &uuml;bermitteln wollen.<br/>
                Das Eingabe-Format ist eine ganzstellige Zahl mit max. 2 Ziffern.';
MLI18n::gi()->{'formfields__importactive__hint'} = 'Bitte beachten Sie: Bestellungen vom METRO Marktplatz werden automatisch mit der Übergabe an den Webshop (Bestellimport) akzeptiert.';
