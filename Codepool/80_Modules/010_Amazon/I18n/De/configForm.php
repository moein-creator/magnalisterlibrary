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

MLI18n::gi()->amazon_config_carrier_other = 'Andere';
MLI18n::gi()->amazon_config_general_mwstoken_help = '
Amazon ben&ouml;tigt eine Authentifizierung zum &uuml;bermitteln von Daten &uuml;ber die Schnittstelle. Bitte tragen Sie unter "H&auml;ndler-ID", "Marktplatz-ID" und "MWS Token“ die jeweiligen Schl&uuml;ssel ein. Sie k&ouml;nnen diese Schl&uuml;ssel auf dem jeweiligen Amazon Marketplace beantragen, auf dem Sie einstellen wollen.<br>
<br>
Eine Anleitung, um den MWS Token zu beantragen finden Sie unter dem folgenden FAQ Artikel:<br>
<a href="https://otrs.magnalister.com/otrs/public.pl?Action=PublicFAQZoom;ItemID=997" title="Amazon MWS" target="_blank">Wie beantragt man den Amazon MWS Token?</a>';
MLI18n::gi()->amazon_config_general_autosync = 'Automatische Synchronisierung per CronJob (empfohlen)';
MLI18n::gi()->amazon_config_general_nosync = 'keine Synchronisierung';
MLI18n::gi()->amazon_config_account_title = 'Zugangsdaten';
MLI18n::gi()->amazon_config_account_prepare = 'Artikelvorbereitung';
MLI18n::gi()->amazon_config_account_price = 'Preisberechnung';
MLI18n::gi()->amazon_configform_orderstatus_sync_values = array(
    'auto' => '{#i18n:amazon_config_general_autosync#}',
    'no' => '{#i18n:amazon_config_general_nosync#}',
);
MLI18n::gi()->amazon_configform_sync_values = array(
    'auto' => '{#i18n:amazon_config_general_autosync#}',
    //'auto_fast' => 'Schnellere automatische Synchronisation cronjob (auf 15 Minuten)',
    'no' => '{#i18n:amazon_config_general_nosync#}',
);
MLI18n::gi()->amazon_configform_stocksync_values = array(
    'rel' => 'Bestellung (keine FBA-Bestellung) reduziert Shop-Lagerbestand (empfohlen)',
    'fba' => 'Bestellung (auch FBA-Bestellung) reduziert Shop-Lagerbestand',
    'no' => '{#i18n:amazon_config_general_nosync#}',
);
MLI18n::gi()->amazon_configform_pricesync_values = array(
    'auto' => '{#i18n:amazon_config_general_autosync#}',
    'no' => '{#i18n:amazon_config_general_nosync#}',
);
MLI18n::gi()->amazon_configform_orderimport_payment_values = array(    
    'textfield' => array(
        'title' => 'Aus Textfeld',
        'textoption' => true
    ),
    'Amazon' => array(
        'title' => 'Amazon',
    ),
);

MLI18n::gi()->amazon_configform_orderimport_shipping_values = array(
    'textfield' => array(
        'title' => 'Aus Textfeld',
        'textoption' => true
    ),
);
MLI18n::gi()->amazon_config_account_sync = 'Synchronisation';
MLI18n::gi()->amazon_config_account_orderimport = 'Bestellimport';
MLI18n::gi()->amazon_config_account_emailtemplate = '{#i18n:configform_emailtemplate_legend#}';
MLI18n::gi()->amazon_config_account_shippinglabel = 'Versandentgelt';
MLI18n::gi()->amazon_config_account_vcs = 'Rechnungen | VCS';
MLI18n::gi()->amazon_config_account_emailtemplate_sender = 'Beispiel-Shop';
MLI18n::gi()->amazon_config_account_emailtemplate_sender_email = 'beispiel@onlineshop.de';
MLI18n::gi()->amazon_config_account_emailtemplate_subject = 'Ihre Bestellung bei #SHOPURL#';
MLI18n::gi()->amazon_config_account_emailtemplate_content = '
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
MLI18n::gi()->amazon_config_tier_error = 'Amazon Business (B2B): Konfiguration f&uuml;r die B2B Staffelpreis-Ebene {#TierNumber#} is nicht korrekt!';

MLI18n::gi()->{'amazon_config_amazonvcsinvoice_invoicenumberoption_values_magnalister'} = 'Rechnungsnummern über magnalister erzeugen';
MLI18n::gi()->{'amazon_config_amazonvcsinvoice_invoicenumberoption_values_matching'} = 'Rechnungsnummern mit Freitextfeld matchen';
MLI18n::gi()->{'amazon_config_amazonvcsinvoice_reversalinvoicenumberoption_values_magnalister'} = 'Stornorechnungsnummer über magnalister erzeugen';
MLI18n::gi()->{'amazon_config_amazonvcsinvoice_reversalinvoicenumberoption_values_matching'} = 'Stornorechnungsnummer mit Freitextfeld matchen';
MLI18n::gi()->add('amazon_config_account', array(
    'legend' => array(
        'account' => 'Zugangsdaten',
        'tabident' => ''
    ),
    'field' => array(
        'tabident' => array(
            'label' => '{#i18n:ML_LABEL_TAB_IDENT#}',
            'help' => '{#i18n:ML_TEXT_TAB_IDENT#}'
        ),
        'username' => array(
            'label' => 'Seller Central E-Mail-Adresse',
            'hint' => '',
        ),
        'password' => array(
            'label' => 'Seller Central Kennwort',
            'help' => 'Tragen Sie hier Ihr aktuelles Amazon-Passwort ein, mit dem Sie sich auch auf Ihrem Seller-Central-Account einloggen.',
        ),
        'mwstoken' => array(
            'label' => 'MWS Token',
            'help' => '{#i18n:amazon_config_general_mwstoken_help#}',
        ),
        'merchantid' => array(
            'label' => 'H&auml;ndler-ID',
            'help' => '{#i18n:amazon_config_general_mwstoken_help#}',
        ),
        'marketplaceid' => array(
            'label' => 'Marktplatz-ID',
            'help' => '{#i18n:amazon_config_general_mwstoken_help#}',
        ),
        'site' => array(
            'label' => 'Amazon Site',
        ),
    ),
), false);


MLI18n::gi()->add('amazon_config_prepare', array(
    'legend' => array(
        'prepare' => 'Artikelvorbereitung',
        'machingbehavior' => 'Matchingverhalten',
        'apply' => 'Neue Produkte erstellen',
        'shipping' => 'Versandeinstellungen',
        'upload' => 'Artikel hochladen: Voreinstellungen',
        'shippingtemplate' => 'Verk&auml;uferversandgruppen',
        'b2b' => 'Amazon Business (B2B)',
    ),
    'field' => array(
        'prepare.status' => array(
            'label' => 'Statusfilter',
            'valuehint' => 'nur aktive Artikel anzeigen',
        ),
        'checkin.status' => array(
            'label' => 'Statusfilter',
            'valuehint' => 'nur aktive Artikel anzeigen',
        ),
        'lang' => array(
            'label' => 'Artikelbeschreibung',
        ),
        'itemcondition' => array(
            'label' => 'Artikelzustand',
        ),
        'internationalshipping' => array(
            'label' => 'Versandeinstellungen für gematchte Produkte',
            'hint' => 'Wenn die Verkäuferversandgruppen aktiviert sind wird diese Einstellung ignoriert',
        ),
        'multimatching' => array(
            'label' => 'Neu matchen',
            'valuehint' => 'Bereits gematchte Produkte beim Multi- und Automatching &uuml;berschreiben.',
            'help' => 'Sollten Sie diese Einstellung aktivieren, werden die bereits gematcheten Produkte durch das neue Matching &uuml;berschrieben.'
        ),
        'multimatching.itemsperpage' => array(
            'label' => 'Ergebnisse',
            'help' => 'Hier k&ouml;nnen Sie festlegen, wie viele Produkte pro Seite beim Multimatching angezeigt werden sollen. <br/>
					Je h&ouml;her die Anzahl, desto h&ouml;her auch die Ladezeit (bei 50 Ergebnissen ca. 30 Sekunden).',
            'hint' => 'pro Seite beim Multimatching',
        ),
        'prepare.manufacturerfallback' => array(
            'label' => 'Alternativ-Hersteller',
            'help' => 'Falls ein Produkt keinen Hersteller hinterlegt hat, wird der hier angegebene Hersteller verwendet.<br />
                        Unter „Globale Konfiguration“ > „Produkteigenschaften“ k&ouml;nnen Sie auch generell „Hersteller“ auf Ihre Attribute matchen.
                    ',
        ),
        'quantity' => array(
            'label' => 'St&uuml;ckzahl Lagerbestand',
            'help' => 'Geben Sie hier an, wie viel Lagermenge eines Artikels auf dem Marktplatz verf&uuml;gbar sein soll.<br/>
                        <br/>
                        Um &Uuml;berverk&auml;ufe zu vermeiden, k&ouml;nnen Sie den Wert<br/>
                        "Shop-Lagerbestand &uuml;bernehmen abzgl. Wert aus rechtem Feld" aktivieren.<br/>
                        <br/>
                        <strong>Beispiel:</strong> Wert auf "2" setzen. Ergibt &#8594; Shoplager: 10 &#8594; Amazon-Lager: 8<br/>
                        <br/>
                        <strong>Hinweis:</strong>Wenn Sie Artikel, die im Shop inaktiv gesetzt werden, unabh&auml;ngig der verwendeten Lagermengen<br/>
                        auch auf dem Marktplatz als Lager "0" behandeln wollen, gehen Sie bitte wie folgt vor:<br/>
                        <ul>
                        <li>Synchronisation des Inventars" > "Lagerver&auml;nderung Shop" auf "automatische Synchronisation per CronJob" einstellen</li>
                        <li>"Globale Konfiguration" > "Produktstatus" > "Wenn Produktstatus inaktiv ist, wird der Lagerbestand wie 0 behandelt" aktivieren</li>
                        </ul>',
        ),
        'leadtimetoship' => array(
            'label' => 'Bearbeitungszeit (in Tagen)',
            'help' => 'Die Zeit, die zwischen der Bestellaufgabe durch den Käufer bis zur Übergabe der Sendung vom Verkäufer an den Transporteur vergeht.<br>
					Sofern Sie hier keinen Wert angeben, bel&auml;uft sich die Bearbeitungszeit standardm&auml;&szlig;ig auf 1-2 Werktage.
					Verwenden Sie dieses Feld, wenn die Bearbeitungszeit f&uuml;r einen Artikel mehr als zwei Werktage betr&auml;gt.',
        ),
        'checkin.skuasmfrpartno' => array(
            'label' => 'Herstellerartikelnummer',
            'help' => 'SKU wird als Herstellerartikelnummer &uuml;bertragen.',
            'valuehint' => 'SKU wird als Herstellerartikelnummer verwenden',
        ),
        'imagesize' => array(
            'label' => 'Bildgr&ouml;&szlig;e',
            'help' => '<p>Geben Sie hier die Pixel-Breite an, die Ihr Bild auf dem Marktplatz haben soll.
Die H&ouml;he wird automatisch dem urspr&uuml;nglichen Seitenverh&auml;ltnis nach angepasst.</p>
<p>
Die Quelldateien werden aus dem Bildordner {#setting:sSourceImagePath#} verarbeitet und mit der hier gew&auml;hlten Pixelbreite im Ordner {#setting:sImagePath#}  f&uuml;r die &Uuml;bermittlung zum Marktplatz abgelegt.</p>',
            'hint' => 'Gespeichert unter: {#setting:sImagePath#}'
        ),
        'shipping.template.active' => array(
            'label' => 'Verk&auml;uferversandgruppen nutzen',
            'help' => 'Verk&auml;ufer k&ouml;nnen eine Gruppe mit verschiedenen Versandeinstellungen erstellen, je nach gesch&auml;ftlichen Erfordernissen und Anwendungsf&auml;llen. F&uuml;r verschiedene Regionen k&ouml;nnen unterschiedliche Gruppen von Versandeinstellungen gew&auml;hlt werden, mit unterschiedlichen Versandbedingungen und &ndash;geb&uuml;hren f&uuml;r die jeweilige Region. Wenn der Verk&auml;ufer ein Produkt als Angebot erstellt, muss der Verk&auml;ufer eine seiner angelegten Gruppen von Versandeinstellungen f&uuml;r das jeweilige Produkt festlegen. Die Versandeinstellungen dieser Gruppe werden dann genutzt, um die jeweils g&uuml;ltige Versandoption je Produkt auf der Website anzuzeigen.',
        ),
        'shipping.template' => array(
            'label' => 'Verk&auml;uferversandgruppen',
            'hint' => 'Eine bestimmte Gruppe von Versandeinstellungen, die verk&auml;uferspezifisch f&uuml;r ein Angebot festgelegt wird. Die Verk&auml;uferversandgruppe wird in der Benutzeroberfl&auml;che f&uuml;r Versandeinstellungen vom Verk&auml;ufer erstellt und verwaltet.',
            'help' => 'Verk&auml;ufer k&ouml;nnen eine Gruppe mit verschiedenen Versandeinstellungen erstellen, je nach gesch&auml;ftlichen Erfordernissen und Anwendungsf&auml;llen. F&uuml;r verschiedene Regionen k&ouml;nnen unterschiedliche Gruppen von Versandeinstellungen gew&auml;hlt werden, mit unterschiedlichen Versandbedingungen und &ndash;geb&uuml;hren f&uuml;r die jeweilige Region. Wenn der Verk&auml;ufer ein Produkt als Angebot erstellt, muss der Verk&auml;ufer eine seiner angelegten Gruppen von Versandeinstellungen f&uuml;r das jeweilige Produkt festlegen. Die Versandeinstellungen dieser Gruppe werden dann genutzt, um die jeweils g&uuml;ltige Versandoption je Produkt auf der Website anzuzeigen.',
        ),
        'shipping.template.name' => array(
            'label' => 'Verk&auml;uferversandgruppen Bezeichnung',
        ),
        'b2bactive' => array(
            'label' => 'Amazon B2B verwenden',
            'help' => '
                Zur Nutzung der Funktion m&uuml;ssen Sie am Amazon Business-Verk&auml;uferprogramm teilnehmen: Als bestehender Amazon H&auml;ndler loggen Sie sich dazu in Ihrem Seller Central Account ein und aktivieren dort das Amazon Business-Verk&auml;uferprogramm. Die einzige Voraussetzung ist ein "Professional Seller Account" (kann in Ihrem bestehenden Seller Account upgegradet werden).<br />
                <br />
                Bitte lesen Sie auch die Hinweise im Info-Icon bei "Bestellimport" > "Import aktivieren".
            ',
            'notification' => 'Um Amazon Business zu nutzen, brauchen Sie eine Aktivierung in Ihrem Amazon-Konto.  <b>Bitte stellen Sie sicher, dass Ihr Amazon Konto f&uuml;r Amazon Business freigeschaltet ist.</b> Andernfalls wird das Hochladen von B2B-Artikeln zu Fehlern f&uuml;hren.<br>Um Ihr Konto f&uuml;r Amazon Business freizuschalten, folgen Sie bitte der Anleitung unter <a href="https://sellercentral.amazon.de/business/b2bregistration" target="_blank">diesem Link</a>.',
            'values' => array(
                'true' => 'Ja',
                'false' => 'Nein',
            ),
        ),
        'b2b.tax_code' => array(
            'label' => 'Business Steuerklassen-Matching',
            'hint' => '',
            'matching' => array(
                'titlesrc' => 'Shop Steuers&auml;tze',
                'titledst' => 'Amazon Business Steuers&auml;tze',
            )
        ),
        'b2b.tax_code_container' => array(
            'label' => 'Business Steuerklassen-Matching - F&uuml;r Kategorie',
            'hint' => '',
        ),
        'b2b.tax_code_specific' => array(
            'label' => '',
            'hint' => '',
            'matching' => array(
                'titlesrc' => 'Shop Steuers&auml;tze',
                'titledst' => 'Amazon Business Steuers&auml;tze',
            )
        ),
        'b2b.tax_code_category' => array(
            'label' => '',
            'hint' => '',
        ),
        'b2bsellto' => array(
            'label' => 'Verkauf an',
            'help' => 'Wenn <i>B2B Only</i> ausgew&auml;hlt, werden hochgeladene Produkte nur f&uuml;r Gesch&auml;ftskunden sichtbar sein. Andernfalls sowohl f&uuml;r Gesch&auml;fts- als auch Privatkunden.',
            'values' => array(
                'b2b_b2c' => 'B2B und B2C',
                'b2b_only' => 'B2B Only',
            ),
        ),
        'b2b.price' => array(
            'label' => 'Business Preis',
            'help' => 'Geben Sie einen prozentualen oder fest definierten Preis Auf- oder Abschlag an. Abschlag mit vorgesetztem Minus-Zeichen.',
        ),
        'b2b.price.addkind' => array(
            'label' => '',
            'hint' => '',
        ),
        'b2b.price.factor' => array(
            'label' => '',
            'hint' => '',
        ),
        'b2b.price.signal' => array(
            'label' => 'Nachkommastelle',
            'hint' => 'Nachkommastelle',
            'help' => '
                Dieses Textfeld wird beim &Uuml;bermitteln der Daten zu Amazon als Nachkommastelle an Ihrem Preis &uuml;bernommen.<br/><br/>
                <strong>Beispiel:</strong> <br />
                Wert im Textfeld: 99 <br />
                Preis-Ursprung: 5.58 <br />
                Finales Ergebnis: 5.99 <br /><br />
                Die Funktion hilft insbesondere bei prozentualen Preis-Auf-/Abschl&auml;gen.<br/>
                Lassen Sie das Feld leer, wenn Sie keine Nachkommastelle &uuml;bermitteln wollen.<br/>
                Das Eingabe-Format ist eine ganzstellige Zahl mit max. 2 Ziffern.
            '
        ),
        'b2b.priceoptions' => array(
            'label' => 'Business Preisoptionen',
        ),
        'b2b.price.group' => array(
            'label' => '',
            'hint' => '',
        ),
        'b2b.price.usespecialoffer' => array(
            'label' => 'auch Sonderpreise verwenden',
        ),
        'b2bdiscounttype' => array(
            'label' => 'Staffelpreis-Berechnung',
            'help' => '<b>Staffelpreise</b><br>
          Staffelpreise sind erm&auml;&szlig;igte Preise, die f&uuml;r Business-Kunden beim Kauf
          gr&ouml;&szlig;erer St&uuml;ckzahlen verf&uuml;gbar sind. Verk&auml;ufer, die am Amazon
          Business Seller Program teilnehmen, k&ouml;nnen entsprechende Mindestmengen
          und Preisabschl&auml;ge definieren.<br><br>
          <b>Beispiel</b>:
          F&uuml;r ein Produkt, das 100 &euro; kostet, k&ouml;nnten folgende
          Prozent-Abschl&auml;ge (f&uuml;r Gesch&auml;ftskunden) definiert werden:
          <table><tr>
              <th style="background-color: #ddd;">Mindestmenge</th>
              <th style="background-color: #ddd;">Abschlag</th>
              <th style="background-color: #ddd;">Endpreis pro St&uuml;ck</th>
		  <tr><td>5 (or more)</td><td style="text-align: right;">10</td><td style="text-align: right;">$90</td></tr>
		  <tr><td>8 (or more)</td><td style="text-align: right;">12</td><td style="text-align: right;">$88</td></tr>
		  <tr><td>12 (or more)</td><td style="text-align: right;">15</td><td style="text-align: right;">$85</td></tr>
		  <tr><td>20 (or more)</td><td style="text-align: right;">20</td><td style="text-align: right;">$80</td></tr>
	  </table>',
            'values' => array(
                '' => 'Nicht verwenden',
                'percent' => 'Prozent',
            ),
        ),
        'b2bdiscounttier1' => array(
            'label' => 'Staffelpreis Ebene 1',
            'help' => 'Der Rabatt muss größer als 0 sein'
        ),
        'b2bdiscounttier2' => array(
            'label' => 'Staffelpreis Ebene 2',
        ),
        'b2bdiscounttier3' => array(
            'label' => 'Staffelpreis Ebene 3',
        ),
        'b2bdiscounttier4' => array(
            'label' => 'Staffelpreis Ebene 4',
        ),
        'b2bdiscounttier5' => array(
            'label' => 'Staffelpreis Ebene 5',
        ),
        'b2bdiscounttier1quantity' => array(
            'label' => 'St&uuml;ckzahl',
        ),
        'b2bdiscounttier2quantity' => array(
            'label' => 'St&uuml;ckzahl',
        ),
        'b2bdiscounttier3quantity' => array(
            'label' => 'St&uuml;ckzahl',
        ),
        'b2bdiscounttier4quantity' => array(
            'label' => 'St&uuml;ckzahl',
        ),
        'b2bdiscounttier5quantity' => array(
            'label' => 'St&uuml;ckzahl',
        ),
        'b2bdiscounttier1discount' => array(
            'label' => 'Rabatt',
        ),
        'b2bdiscounttier2discount' => array(
            'label' => 'Rabatt',
        ),
        'b2bdiscounttier3discount' => array(
            'label' => 'Rabatt',
        ),
        'b2bdiscounttier4discount' => array(
            'label' => 'Rabatt',
        ),
        'b2bdiscounttier5discount' => array(
            'label' => 'Rabatt',
        )
    )
), false);

MLI18n::gi()->add('amazon_config_price', array(
    'legend' => array(
        'price' => 'Preisberechnung',
    ),
    'field' => array(
        'price' => array(
            'label' => 'Preis',
            'hint' => '',
            'help' => 'Geben Sie einen prozentualen oder fest definierten Preis Auf- oder Abschlag an. Abschlag mit vorgesetztem Minus-Zeichen.'
        ),
        'price.addkind' => array(
            'label' => '',
            'hint' => '',
        ),
        'price.factor' => array(
            'label' => '',
            'hint' => '',
        ),
        'price.signal' => array(
            'label' => 'Nachkommastelle',
            'hint' => 'Nachkommastelle',
            'help' => '
                Dieses Textfeld wird beim &Uuml;bermitteln der Daten zu Amazon als Nachkommastelle an Ihrem Preis &uuml;bernommen.<br/><br/>
                <strong>Beispiel:</strong> <br />
                Wert im Textfeld: 99 <br />
                Preis-Ursprung: 5.58 <br />
                Finales Ergebnis: 5.99 <br /><br />
                Die Funktion hilft insbesondere bei prozentualen Preis-Auf-/Abschl&auml;gen.<br/>
                Lassen Sie das Feld leer, wenn Sie keine Nachkommastelle &uuml;bermitteln wollen.<br/>
                Das Eingabe-Format ist eine ganzstellige Zahl mit max. 2 Ziffern.
            '
        ),
        'priceoptions' => array(
            'label' => 'Preisoptionen',
            'help' => '{#i18n:configform_price_field_priceoptions_help#}',
            'hint' => '',
        ),
        'price.group' => array(
            'label' => '',
            'hint' => '',
        ),
        'price.usespecialoffer' => array(
            'label' => 'auch Sonderpreise verwenden',
            'hint' => '',
            
        ),
        'exchangerate_update' => array(
            'label' => 'Wechselkurs',
            'hint' => 'Wechselkurs automatisch aktualisieren',
            'help' => '{#i18n:form_config_orderimport_exchangerate_update_help#}',
            'alert' => '{#i18n:form_config_orderimport_exchangerate_update_alert#}',
        ),
    ),
), false);


MLI18n::gi()->add('amazon_config_sync',  array(
    'legend' => array(
        'sync' => 'Synchronisation des Inventars',
    ),
    'field' => array(
        'stocksync.tomarketplace' => array(
            'label' => 'Lagerver&auml;nderung Shop',
            'hint' => '',
            'help' => '
                <dl>
                    <dt>Automatische Synchronisierung per CronJob (empfohlen)</dt>
                    <dd>
                        Die Funktion "Automatische Synchronisierung" gleicht alle 4 Stunden (beginnt um 0:00 Uhr nachts) den aktuellen {#setting:currentMarketplaceName#}-Lagerbestand an der Shop-Lagerbestand an (je nach Konfiguration ggf. mit Abzug).<br />
                        <br />
                        Dabei werden die Werte aus der Datenbank geprüft und übernommen, auch wenn die Änderungen durch z.B. eine Warenwirtschaft nur in der Datenbank erfolgten.<br />
                        <br />
                        Einen manuellen Abgleich können Sie anstoßen, indem Sie den entsprechenden Funktionsbutton "Preis- und Lagersynchronisation" oben rechts im magnalister Plugin anklicken.<br />
                        Zusätzlich können Sie den Lagerabgleich (ab Tarif Flat - maximal viertelstündlich) auch durch einen eigenen CronJob anstoßen, indem Sie folgenden Link zu Ihrem Shop aufrufen:<br />
                        <i>{#setting:sSyncInventoryUrl#}</i><br />
                        Eigene CronJob-Aufrufe durch Kunden, die nicht im Tarif Flat sind, oder die häufiger als viertelstündlich laufen, werden geblockt.<br />
                    </dd>
                </dl>
                <br />
                <strong>Hinweis:</strong> Die Einstellungen unter "Konfiguration" → "Artikelvorbereitung" → "Stückzahl Lagerbestand" werden berücksichtigt.
            ',
        ),
        'stocksync.frommarketplace' => array(
            'label' => 'Lagerver&auml;nderung Amazon',
            'hint' => '',
            'help' => '
                Wenn z. B. bei {#setting:currentMarketplaceName#} ein Artikel 3 mal gekauft wurde, wird der Lagerbestand im Shop um 3 reduziert.<br />
                <br />
                <strong>Wichtig:</strong> Diese Funktion läuft nur, wenn Sie den Bestellimport aktiviert haben!
            ',
        ),
        'inventorysync.price' => array(
            'label' => 'Artikelpreis',
            'hint' => '',
            'help' => '
                <dl>
                    <dt>Automatische Synchronisierung per CronJob (empfohlen)</dt>
                    <dd>
                        Mit der Funktion "Automatische Synchronisierung" wird der im Webshop hinterlegte Preis an den {#setting:currentMarketplaceName#}-Marktplatz übermittelt (sofern in magnalister konfiguriert, mit Preisauf- oder abschlägen). Synchronisiert wird alle 4 Stunden (Startpunkt: 0:00 Uhr nachts).<br />
                        Dabei werden die Werte aus der Datenbank geprüft und übernommen, auch wenn die Änderungen durch z.B. eine Warenwirtschaft nur in der Datenbank erfolgten.<br />
                        <br />
                        Einen manuellen Abgleich können Sie anstoßen, indem Sie den entsprechenden Funktionsbutton "Preis- und Lagersynchronisation" oben rechts im magnalister Plugin anklicken.<br />
                        <br />
                        Zusätzlich können Sie den Preisabgleich auch durch einen eigenen CronJob anstoßen, indem Sie folgenden Link zu Ihrem Shop aufrufen:<br />
                        <i>{#setting:sSyncInventoryUrl#}</i><br />
                        Eigene CronJob-Aufrufe durch Kunden, die nicht im Tarif Flat sind, oder die häufiger als viertelstündlich laufen, werden geblockt.<br />
                    </dd>
                </dl>
                <br />
                <strong>Hinweis:</strong> Die Einstellungen unter "Konfiguration" → "Preisberechnung" werden berücksichtigt.
            ',
        ),
    ),
), false);

MLI18n::gi()->add('amazon_config_orderimport', array(
    'legend' => array(
        'importactive' => 'Bestellimport',
        'mwst' => 'Mehrwertsteuer',
        'orderstatus' => 'Synchronisation des Bestell-Status vom Shop zu Amazon',
    ),
    'field' => array(
        'orderstatus.shipped' => array(
            'label' => 'Versand best&auml;tigen mit',
            'hint' => '',
            'help' => 'Setzen Sie hier den Shop-Status, der auf Amazon automatisch den Status "Versand best&auml;tigen" setzen soll.',
        ),
        'orderstatus.canceled' => array(
            'label' => 'Bestellung stornieren mit',
            'hint' => '',
            'help' => '
                Setzen Sie hier den Shop-Status, der auf  Amazon automatisch den Status "Bestellung stornieren" setzen soll. <br/><br/>
                Hinweis: Teilstorno ist hier&uuml;ber nicht m&ouml;glich. Die gesamte Bestellung wird &uuml;ber diese Funktion storniert
                und dem K&auml;ufer gutgeschrieben.
            ',
        ),
        'orderimport.shop' => array(
            'label' => '{#i18n:form_config_orderimport_shop_lable#}',
            'hint' => '',
            'help' => '{#i18n:form_config_orderimport_shop_help#}',
        ),
        'orderimport.paymentmethod' => array(
            'label' => 'Zahlart der Bestellungen',
            'help' => 'Zahlart, die allen Amazon-Bestellungen zugeordnet wird. Standard: "Amazon".<br><br>
				           Diese Einstellung ist wichtig f&uuml;r den Rechnungs- und Lieferscheindruck und f&uuml;r die nachtr&auml;gliche
				           Bearbeitung der Bestellung im Shop sowie einige Warenwirtschaften.',
            'hint' => '',
        ),
        'orderimport.shippingmethod' => array(
            'label' => 'Versandart der Bestellungen',
            'help' => '<b>Versandart der Bestellungen</b><br />
<br />
Wählen Sie hier die Versandart, die allen Bestellungen standardmäßig zugeordnet wird.<br />
<br />
Sie haben folgende Optionen:
<ol>
<li><b>Vom Marktplatz unterstützte Versandarten</b><br />
<br />
Wählen Sie eine Versandart aus der Liste im Dropdown-Feld. Es werden nur die Optionen angezeigt, die vom Marktplatz unterstützt werden.<br />
<br />
</li>
<li><b>Versandarten aus der Freitextfeld-Verwaltung des Webshops</b><br />
<br />
Wählen Sie eine Versandart aus einem Freitextfeld des Webshops.<br />
<br />
</li>
<li><b>Automatisch zuordnen</b><br />
<br />
magnalister wählt automatisch die Versandart aus, die für das Zielland der Bestellung im Versandkostenmodul des Webshops an erster Stelle hinterlegt ist.
</li>
</ol>',
           'hint' => '',
        ),
        'mwstfallback' => array(
            'label' => 'MwSt. Shop-fremder Artikel',
            'hint' => 'Steuersatz, der f&uuml;r Shop-fremde Artikel bei Bestellimport verwendet wird in %.',
            'help' => '
                Sollte der Artikel im Web-Shop nicht gefunden werden, verwendet magnalister den hier hinterlegten Steuersatz, da die Marktpl&auml;tze beim Bestellimport keine Angabe zur Mehrwertsteuer machen.<br />
                <br />
                Weitere Erl&auml;uterungen:<br />
                Grunds&auml;tzlich verh&auml;lt sich magnalister beim Bestellimport bei der Berechnung der Mehrwertsteuer so wie das Shop-System selbst.<br />
                <br />
                Damit die Mehrwertsteuer pro Land automatisch ber&uuml;cksichtigt werden kann, muss der gekaufte Artikel mit seinem des Nummernkreis (SKU) im Web-Shop gefunden werden.<br />
                magnalister verwendet dann die im Web-Shop konfigurierten Steuerklassen.
            ',
        ),
        /*//{search: 1427198983}
        'mwst.shipping' => array(
            'label' => 'MwSt. Versandkosten',
            'hint' => 'Steuersatz f&uuml;r Versandkosten in %.',
            'help' => '
                Amazon &uuml;bermittelt nicht den Steuersatz der Versandkosten, sondern nur die Brutto-Preise. Daher muss der Steuersatz zur korrekten Berechnung der Mehrwertsteuer f&uuml;r die Versandkosten hier angegeben werden. Falls Sie mehrwertsteuerbefreit sind, tragen Sie in das Feld 0 ein.
            ',
        ),
        //*/
        'importactive' => array(
            'label' => 'Import aktivieren',
            'hint' => '',
            'help' => '
                Wenn die Funktion aktiviert ist, werden Bestellungen voreingestellt st&uuml;ndlich importiert.<br />
                <br />
                Einen manuellen Import k&ouml;nnen Sie ansto&szlig;en, indem Sie den entsprechenden Funktionsbutton rechts in der Kopfzeile von magnalister anklicken.<br />
                <br />
                Zus&auml;tzlich k&ouml;nnen Sie den Bestellimport (ab Tarif Flat - maximal viertelst&uuml;ndlich) auch durch einen eigenen CronJob ansto&szlig;en, indem Sie folgenden Link zu Ihrem Shop aufrufen:<br />
                <i>{#setting:sImportOrdersUrl#}</i><br />
                <br />
                <strong>Mehrwertsteuer:</strong><br />
                <br />
                Die Steuers&auml;tze f&uuml;r den Bestellimport k&ouml;nnen f&uuml;r die L&auml;nder, mit denen Sie handeln, nur korrekt ermittelt werden, wenn Sie die entsprechenden Mehrwertsteuers&auml;tze im Web-Shop gepflegt haben und die gekauften Artikel anhand der SKU im Web-Shop identifiziert werden k&ouml;nnen.<br />
                Wenn der Artikel nicht im Web-Shop gefunden wird, verwendet magnalister den unter "Bestellimport"  > "MwSt. Shop-fremder Artikel" hinterlegten Steuersatz als "Fallback".<br />
                <br />
                <strong>Hinweis f&uuml;r Rechnungsstellung und Amazon B2B Bestellungen</strong> (setzt Teilnahme am Amazon Business-Verk&auml;uferprogramm voraus):<br />
                <br />
                Amazon &uuml;bergibt f&uuml;r den Bestellimport keine Umsatzsteuer-Identnummer. Somit kann magnalister zwar die B2B-Bestellungen im Web-Shop anlegen, jedoch sind formell korrekte Rechnungsstellungen somit nicht immer m&ouml;glich.<br />
                <br />
                Es besteht jedoch die Option, dass Sie die Umsatzsteuer-IDs &uuml;ber Ihre Amazon Seller Central abrufen und manuell in Ihre Shop-/ bzw. Warenwirtschaftssysteme nachpflegen. <br />
                <br />
                Auch k&ouml;nnen Sie den f&uuml;r B2B Bestellungen von Amazon angebotenen Rechnungsservice nutzen, der alle rechtlich relevanten Daten auf den Belegen an Ihre Kunden bereith&auml;lt.<br />
                <br />
                Sie erhalten als am Amazon Business-Verk&auml;uferprogramm teilnehmender H&auml;ndler alle f&uuml;r die Bestellungen notwendigen Unterlagen inkl. Umsatzsteuer-IDs in Ihrer Seller Central unter dem Punkt "Berichte" > "Steuerdokumente". Wann die IDs zur Verf&uuml;gung stehen, h&auml;ngt von Ihrem B2B Vertrag mit Amazon ab (entweder nach 3 oder 30 Tagen).<br />
                <br />
                Sollten Sie f&uuml;r FBA angemeldet sein, erhalten Sie die Umsatzsteuer-IDs auch unter dem Punkt "Versand durch Amazon" im Reiter "Berichte".
            '
        ),
        'import' => array(
            'label' => '',
            'hint' => '',
        ),
        'preimport.start' => array(
            'label' => 'erstmalig ab Zeitpunkt',
            'hint' => 'Startzeitpunkt',
            'help' => 'Startzeitpunkt, ab dem die Bestellungen erstmalig importiert werden sollen. Bitte beachten Sie, dass dies nicht beliebig weit in die Vergangenheit m&ouml;glich ist, da die Daten bei Amazon h&ouml;chstens einige Wochen lang vorliegen.',
        ),
        'customergroup' => array(
            'label' => 'Kundengruppe',
            'hint' => '',
            'help' => 'Kundengruppe, zu der Kunden bei neuen Bestellungen zugeordnet werden sollen.',
        ),
        'orderstatus.open' => array(
            'label' => 'Bestellstatus im Shop',
            'hint' => '',
            'help' => '
                Der Status, den eine von Amazon neu eingegangene Bestellung im Shop automatisch bekommen soll.<br />
                Sollten Sie ein angeschlossenes Mahnwesen verwenden, ist es empfehlenswert, den Bestellstatus auf "Bezahlt" zu setzen (Konfiguration → Bestellstatus).
            ',
        ),
        'orderimport.fbablockimport' => array(
            'label' => 'FBA Bestellimport',
            'valuehint' => 'FBA Bestellungen nicht importieren',
            'help' => '<b>Bestellungen über Amazon FBA nicht importieren</b><br />
                <br />
                Sie haben die Möglichkeit den Import von FBA Bestellungen in Ihren Shop zu unterbinden.
                <br />
                Setzen Sie dafür das Häckchen und der Bestellimport wird für absofort exklusive der FBA Bestellungen stattfinden.
                <br />
                Sollten Sie den Haken wieder entfernen, so werden neue FBA Bestellungen wie gewohnt importiert.
                <br />
                Wichtige Hinweise:
                <ul>
                    <li>Sollten Sie diese Funktion aktivieren, stehen Ihnen alle anderen FBA Funktionen im Rahmen des Bestellimports für diese Zeit nicht zur Verfügung.</li>
                </ul>
            ',
        ),
        'orderstatus.fba' => array(
            'label' => 'Status f&uuml;r FBA-Bestellungen',
            'hint' => '',
            'help' => 'Funktion nur f&uuml;r H&auml;ndler, die am Programm "Versand durch Amazon (FBA)" teilnehmen: <br/>Definiert wird der Bestellstatus, 
				           den eine von Amazon importierte FBA-Bestellung im Shop automatisch bekommen soll. <br/><br/>
				           Sollten Sie ein angeschlossenes Mahnwesen verwenden, ist es empfehlenswert, den Bestellstatus auf "Bezahlt" zu setzen (Konfiguration &rarr; 
						   Bestellstatus).',
        ),
        'orderimport.fbapaymentmethod' => array(
            'label' => 'Zahlart der Bestellungen (FBA)',
            'help' => 'Zahlart, die allen Amazon-Bestellungen zugeordnet wird, die durch Amazon versendet werden. Standard: "Amazon".<br><br>
                        Diese Einstellung ist wichtig f&uuml;r den Rechnungs- und Lieferscheindruck und f&uuml;r die nachtr&auml;gliche
                        Bearbeitung der Bestellung im Shop sowie einige Warenwirtschaften.',
            'hint' => '',
        ),
        'orderimport.fbashippingmethod' => array(
            'label' => 'Versandart der Bestellungen (FBA)',
            'help' => 'Versandart, die allen Amazon-Bestellungen zugeordnet wird, die durch Amazon versendet werden. Standard: "amazon".<br><br>
				           Diese Einstellung ist wichtig f&uuml;r den Rechnungs- und Lieferscheindruck und f&uuml;r die nachtr&auml;gliche
				           Bearbeitung der Bestellung im Shop sowie einige Warenwirtschaften.',
            'hint' => '',
        ),
        'orderstatus.carrier'=>array(
            'label' => 'Transportunternehmen',
            'help' => '',
            'hint' => 'Wählen Sie hier das Transportunternehmen (Versanddienstleister), das allen Amazon Bestellungen standardmäßig zugeordnet wird. Eine Angabe ist seitens Amazon verpflichtend. Weitere Details siehe Info-Icon.',
        ),
        'orderstatus.cancelled' => array(
            'label' => 'Bestellung stornieren mit',
            'hint' => '',
            'help' => 'Setzen Sie hier den Shop-Status, der auf Amazon automatisch den Status "Bestellung stornieren" setzen soll. <br/><br/>
                Hinweis: Teilstorno wird &uuml;ber die API von Amazon nicht angeboten. Die gesamte Bestellung wird &uuml;ber diese Funktion storniert
                und dem K&auml;ufer gutgeschrieben.',
        ),
        'orderimport.amazonpromotionsdiscount' => array(
            'label' => 'Amazon Werbeaktionen',
            'help' => '<p>magnalister importiert die Amazon Werbeaktionen-Rabatte als eigenst&auml;ndige Produkte in Ihren Webshop. Es wird jeweils pro Produkt- und Versandrabatt ein Artikel in der importierten Bestellung angelegt.</p>
                           <p>In dieser Einstellm&ouml;glichkeit k&ouml;nnen Sie eigene Artikelnummern f&uuml;r diese Werbeaktionen-Rabatte hinterlegen.</p>',
        ),
        'orderimport.amazonpromotionsdiscount.products_sku' => array(
            'label' => 'Produktrabatt Artikelnummer',
        ),
        'orderimport.amazonpromotionsdiscount.shipping_sku' => array(
            'label' => 'Versandrabatt Artikelnummer',
        ),
        'orderimport.amazoncommunicationrules.blacklisting' => array(
            'label' => 'Amazon Kommunikationsrichtlinien',
            'valuehint' => 'Amazons Kunden-E-Mail Adresse blacklisten',
            'help' => '<b>Versandbenachrichtigungen an Amazon Käufer vermeiden</b><br />
                <br />
                Aufgrund von Amazon Kommunikationsrichtlinien dürfen Amazon Verkäufer u.a. keine Versandbenachrichtigungen (E-Mails) direkt an Käufer senden.<br />
                <br />
                Die Einstellung “Amazons Kunden-E-Mail Adresse blacklisten” dient dazu E-Mails zu blacklisten, die (für über magnalister importierte Bestellungen) aus dem Shopsystem heraus versandt werden. Sie kommen dann beim Amazon Käufer nicht an.<br />
                <br />
                Wenn Sie trotz der Amazon Kommunikationsrichtlinien den Versand von E-Mails aus dem Shopsystem heraus an Käufer aktivieren möchten, so entfernen Sie den Haken bei “Amazons Kunden-E-Mail Adresse blacklisten”. Dies kann jedoch zur Folge haben, dass Sie von Amazon gesperrt werden. Daher raten wir ausdrücklich davon ab und übernehmen keine Haftung für eventuell entstehende Schäden.<br />
                <br />
                Wichtige Hinweise:
                <ul>
                    <li>Das Blacklisting ist standardmäßig aktiviert. Sie erhalten in dem Moment einen Mailer Daemon (Information des Mailservers, dass die E-Mail nicht zugestellt werden konnte), wenn durch das Shopsystem eine E-Mail an den Amazon Käufer versandt wird.<br /><br /></li>
                    <li>magnalister setzt vor die Amazon E-Mail-Adresse lediglich das Prefix “blacklisted-” (z. B. blacklisted-12345@amazon.de). Möchten Sie dennoch mit dem Amazon Käufer Kontakt aufnehmen, entfernen Sie einfach das Prefix “blacklisted-”.</li>
                </ul>
            ',
        ),
    ),
), false);

MLI18n::gi()->add('amazon_config_emailtemplate', array(
    'legend' => array(
        'mail' => '{#i18n:configform_emailtemplate_legend#}',
    ),
    'field' => array(
        'mail.send' => array(
            'label' => '{#i18n:configform_emailtemplate_field_send_label#}',
            'help' => '{#i18n:configform_emailtemplate_field_send_help#}',
        ),
        'mail.originator.name' => array(
            'label' => 'Absender Name',
        ),
        'mail.originator.adress' => array(
            'label' => 'Absender E-Mail Adresse',
        ),
        'mail.subject' => array(
            'label' => 'Betreff',
        ),
        'mail.content' => array(
            'label' => 'E-Mail Inhalt',
            'hint' => 'Liste verf&uuml;gbarer Platzhalter f&uuml;r Betreff und Inhalt:
        <dl>
                <dt>#MARKETPLACEORDERID#</dt>
                        <dd>Marktplatz Bestellnummer</dd>
                <dt>#FIRSTNAME#</dt>
                        <dd>Vorname des K&auml;ufers</dd>
                <dt>#LASTNAME#</dt>
                        <dd>Nachname des K&auml;ufers</dd>
                <dt>#EMAIL#</dt>
                        <dd>E-Mail Adresse des K&auml;ufers</dd>
                <dt>#PASSWORD#</dt>
                        <dd>Password des K&auml;ufers zum Einloggen in Ihren Shop. Nur bei Kunden, die dabei automatisch angelegt werden, sonst wird der Platzhalter durch \'(wie bekannt)\' ersetzt.</dd>
                <dt>#ORDERSUMMARY#</dt>
                        <dd>Zusammenfassung der gekauften Artikel. Sollte extra in einer Zeile stehen.<br/><i>Kann nicht im Betreff verwendet werden!</i></dd>
                <dt>#ORIGINATOR#</dt>
                        <dd>Absender Name</dd>
        </dl>',
        ),
        'mail.copy' => array(
            'label' => 'Kopie an Absender',
            'help' => 'Die Kopie wird an die Absender E-Mail Adresse gesendet.',
        ),
    ),
), false);

MLI18n::gi()->add('amazon_config_shippinglabel', array(
    'legend' => array(
        'shippingaddresses' => 'Versandadressen {#i18n:Amazon_Productlist_Apply_Requiered_Fields#}',
        'shippingservice' => 'Versandeinstellungen',
        'shippinglabel' => 'Versandoptionen',
    ),
    'field' => array(
        'shippinglabel.address' => array(
            'label' => 'Versandadresse'
        ),
        'shippinglabel.address.name' => array(
            'label' => 'Name<span class="bull">&bull;</span>'
        ),
        'shippinglabel.address.company' => array(
            'label' => 'Firmenname'
        ),
        'shippinglabel.address.streetandnr' => array(
            'label' => 'Straße und Hausnummer<span class="bull">&bull;</span>'
        ),
        'shippinglabel.address.city' => array(
            'label' => 'Stadt<span class="bull">&bull;</span>'
        ),
        'shippinglabel.address.state' => array(
            'label' => 'Bundesland / Kanton'
        ),
        'shippinglabel.address.zip' => array(
            'label' => 'Postleitzahl<span class="bull">&bull;</span>'
        ),
        'shippinglabel.address.country' => array(
            'label' => 'Land<span class="bull">&bull;</span>'
        ),
        'shippinglabel.address.phone' => array(
            'label' => 'Telefonnummer<span class="bull">&bull;</span>'
        ),
        'shippinglabel.address.email' => array(
            'label' => 'E-Mail-Adresse<span class="bull">&bull;</span>'
        ),
        'shippingservice.carrierwillpickup' => array(
            'label' => 'Paket Abholung',
            'default' => 'false',
        ),
        'shippingservice.deliveryexperience' => array(
            'label' => 'Versandbedingung',
        ),
        'shippinglabel.fallback.weight' => array(
            'label' => 'Alternativ Gewicht',
            'help' => ' Falls ein Produkt kein Gewicht hinterlegt hat, wird der hier angegebene Wert verwendet.',
        ),
        'shippinglabel.weight.unit' => array(
            'label' => 'Maßeinheit Gewicht',
        ),
        'shippinglabel.size.unit' => array(
            'label' => 'Maßeinheit Gr&ouml;ße',
        ),
        'shippinglabel.default.dimension' => array(
            'label' => 'Benutzerdefinierte Paketgr&ouml;ßen',
        ),
        'shippinglabel.default.dimension.text' => array(
            'label' => 'Bezeichnung',
        ),
        'shippinglabel.default.dimension.length' => array(
            'label' => 'L&auml;nge',
        ),
        'shippinglabel.default.dimension.width' => array(
            'label' => 'Breite',
        ),
        'shippinglabel.default.dimension.height' => array(
            'label' => 'H&ouml;he',
        ),
    ),
), false);

MLI18n::gi()->add('amazon_config_vcs', array(
    'legend' => array(
        'amazonvcs' => 'Rechnungsübermittlung und Amazon VCS-Programm',
        'amazonvcsinvoice' => 'Daten für die Rechnungserzeugung durch magnalister',
    ),
    'field' => array(
        'amazonvcs.option' => array(
            'label' => 'Im Seller Central vorgenommene VCS-Einstellungen',
            'values' => array(
                'off' => 'Ich nehme nicht am Amazon VCS-Programm teil',
                'vcs' => 'Amazon Einstellung: Amazon erstellt meine Rechnungen',
                'vcs-lite' => 'Amazon Einstellung: Ich lade meine eigenen Rechnungen zu Amazon hoch',
            ),
            'hint' => 'Die hier eingestellte Option sollte Ihrer Auswahl im Amazon VCS-Programm (Eingabe im Amazon Seller Central) entsprechen.',
            'help' => '
                Bitte wählen Sie hier aus, ob und in welcher Form Sie bereits am Amazon VCS-Programm teilnehmen. Die Grundeinrichtung nehmen Sie im Seller Central vor.
                <br>
                Seitens magnalister stehen Ihnen drei Optionen zur Verfügung:
                <ol>
                    <li>
                        Ich nehme nicht am Amazon VCS-Programm teil<br>
                        <br>
                        Wenn Sie sich gegen eine Teilnahme am Amazon VCS-Programm entschlossen haben, wählen Sie diese Option. Sie können unter “Rechnungsübermittlung” dennoch wählen, ob und wie Sie Ihre Rechnungen zu Amazon hochladen möchten. Allerdings profitieren Sie dann nicht mehr von den Vorteilen des VCS-Programms (z.B. Verkäufer-Abzeichen und besseres Ranking).<br>
                        <br>
                    </li>
                    <li>
                        Amazon Einstellung: Amazon erstellt meine Rechnungen<br>
                        <br>
                        Die Rechnungserstellung und Umsatzsteuerberechnung erfolgt vollständig auf Amazons Seite im Rahmen des VCS-Programms. Die Konfiguration dazu nehmen Sie im Seller Central vor.<br>
                        <br>
                    </li>
                    <li>
                        Amazon Einstellung: Ich lade meine eigenen Rechnungen zu Amazon hoch<br>
                        <br>
                        Wählen Sie diese Option, wenn Sie entweder vom Shopsystem oder von magnalister erstellte Rechnungen (konkrete Auswahl im Feld “Rechnungsübermittlung”) zu Amazon hochladen möchten. Amazon übernimmt dann nur die Umsatzsteuerberechnung. Auch diese Auswahl erfolgt zuerst in der Seller Central.<br>
                        <br>
                    </li>
                </ol>
                <br>
                Wichtige Hinweise:
                <ul>
                    <li>Sofern Sie Option 1 oder 3 wählen, prüft magnalister bei jedem Bestellimport, ob eine Rechnung für eine von magnalister importierte Amazon Bestellung vorliegt. Ist dies der Fall, überträgt magnalister die Rechnung innerhalb von 60 Minuten an Amazon. Im Falle von Option 3 geschieht dies, sobald die Bestellung im Webshop den Versendet-Status erhalten hat.<br><br></li>
                    <li>Sollten die Umsatzsteuerbeträge einer oder mehrerer Rechnungen abweichend von Amazon sein, so erhalten Sie hierzu von magnalister täglich zwischen 9 und 10 Uhr CET+1 eine E-Mail mit allen relevanten Daten wie z.B. Amazon Bestellnummer, Shop-Bestellnummer und die zugehörigen MwSt.-Daten.<br><br></li>
                </ul>
            '
        ),
        'amazonvcs.invoice' => array(
            'label' => 'Rechnungsübermittlung',
            'values' => array(
                'off'     => 'Rechnungen nicht zu Amazon übermitteln',
                'webshop' => 'Im Webshop erstellte Rechnungen werden zu Amazon übermittelt',
                'magna'   => 'Rechnungserstellung und -übermittlung erfolgt durch magnalister',
                'erp'     => 'Im Drittanbieter-System (z. B. ERP) erstellte Rechnungen werden zu Amazon übermittelt',
            ),
            'help' => '
                Hier können Sie wählen, ob und wie Sie Ihre Rechnungen zu Amazon übermitteln möchten. Zur Auswahl stehen folgende Optionen:
                <ol>
                    <li>
                        <p>Rechnungen nicht zu Amazon übermitteln</p>
                        
                        <p>Wählen Sie diese Option, werden Ihre Rechnungen nicht zu Amazon übermittelt. Heißt: Sie organisieren die Bereitstellung von Rechnungen selbst.</p>
                       
                    </li>
                    <li>
                        <p>Im Webshop erstellte Rechnungen werden zu Amazon übermittelt</p>
                        
                        <p>Sofern Ihr Shopsystem über die Möglichkeit verfügt, Rechnungen zu erstellen, können Sie diese zu Amazon hochladen.</p>
                    </li>  
                    <li>
                        <p>magnalister soll die Rechnungserstellung übernehmen und zu Amazon übermitteln</p>
                        <p>Wählen Sie diese Option, wenn magnalister die Erstellung und Übermittlung von Rechnung für Sie übernehmen soll. Füllen Sie dazu die Felder unter “Daten für die Rechnungserzeugung durch magnalister” aus.</p>
                    </li>  
                    <li>
                        <p>Von Drittanbieter-Systemen (z. B. ERP-System) erstellte Rechnungen werden zu Amazon übermittelt</p>
                        <p>Wählen Sie diese Option, wenn magnalister die Erstellung und Übermittlung von Rechnung für Sie übernehmen soll. Füllen Sie dazu die Felder unter “Daten für die Rechnungserzeugung durch magnalister” aus. Die Übertragung erfolgt alle 60 Min.</p>
                    </li>  
                </ol>
            ',
        ),
        'amazonvcsinvoice.invoicedir' => array(
            'label' => 'Übermittelte Rechnungen',
            'buttontext' => 'Anzeigen',
        ),
        'amazonvcsinvoice.mailcopy' => array(
            'label' => 'Rechnungskopie an',
            'hint' => 'Tragen Sie hier Ihre E-Mail-Adresse ein, um eine Kopie der hochgeladenen Rechnung per Mail zu erhalten.',
        ),
        'amazonvcsinvoice.invoiceprefix' => array(
            'label' => 'Präfix Rechnungsnummer',
            'hint' => 'Wenn Sie hier ein Präfix eintragen, wird es vor die Rechnungsnummer gesetzt. Beispiel: R10000. Von magnalister generierte Rechnungen beginnen mit der Nummer 10000.',
            'default' => 'R', //@see ML_Amazon_Controller_Amazon_Config_VCS -> useI18nDefault
        ),
        'amazonvcsinvoice.invoicenumber' => array(
            'label' => 'Rechnungsnummer',
            'help' => '<p>
Wählen Sie hier, ob Sie Ihre Rechnungsnummern von magnalister erzeugen lassen möchten oder ob diese aus einem Shopware Freitextfeld übernommen werden sollen.
</p><p>
<b>Rechnungsnummern über magnalister erzeugen</b>
</p><p>
magnalister generiert bei der Rechnungserstellung fortlaufende Rechnungsnummern. Sie können ein Präfix definieren, das vor die Rechnungsnummer gesetzt wird. Beispiel: R10000.
</p><p>
Hinweis: Von magnalister erstellte Rechnungen beginnen mit der Nummer 10000.
</p><p>
<b>Rechnungsnummern mit Shopware Freitextfeld matchen</b>
</p><p>
Bei der Rechnungserstellung wird der Wert aus dem von Ihnen ausgewählten Shopware Freitextfeld übernommen.
</p><p>
Freitextfelder können Sie in Ihrem Shopware-Backend unter “Einstellungen” -> “Freitextfeld-Verwaltung” anlegen (Tabelle: Bestellung) und unter “Kunden” -> “Bestellungen” befüllen. Öffnen Sie dazu die entsprechende Bestellung und scrollen Sie in der Bestellübersicht nach unten zu “Freitextfelder”.
</p><p>
<b>Wichtig:</b><br/> magnalister erzeugt und übermittelt die Rechnung, sobald die Bestellung als versendet markiert wird. Bitte achten Sie darauf, dass zu diesem Zeitpunkt das Freitextfeld gefüllt sein muss, da sonst ein Fehler erzeugt wird (Ausgabe im Tab “Fehlerlog”).
<br/><br/>
Nutzen Sie das Freitextfeld-Matching, ist magnalister nicht für die korrekte, fortlaufende Erstellung von Rechnungsnummern verantwortlich.
</p>
',
        ),
        'amazonvcsinvoice.invoicenumber.matching' => array(
            'label' => 'Shopware-Bestellung-Freitextfelder',
        ),
        'amazonvcsinvoice.invoicenumberoption' =>array(
            'label' => '',
        ),

        'amazonvcsinvoice.reversalinvoicenumber' => array(
            'label' => 'Stornorechnungsnummer',
            'help' => '<p>
Wählen Sie hier, ob Sie Ihre Stornorechnungsnummer von magnalister erzeugen lassen möchten oder ob diese aus einem Shopware Freitextfeld übernommen werden sollen.
</p><p>
<b>Stornorechnungsnummer über magnalister erzeugen</b>
</p><p>
magnalister generiert bei der Rechnungserstellung fortlaufende Stornorechnungsnummer. Sie können ein Präfix definieren, das vor die Rechnungsnummer gesetzt wird. Beispiel: R10000.
</p><p>
Hinweis: Von magnalister erstellte Rechnungen beginnen mit der Nummer 10000.
</p><p>
<b>Stornorechnungsnummer mit Shopware Freitextfeld matchen</b>
</p><p>
Bei der Rechnungserstellung wird der Wert aus dem von Ihnen ausgewählten Shopware Freitextfeld übernommen.
</p><p>
Freitextfelder können Sie in Ihrem Shopware-Backend unter “Einstellungen” -> “Freitextfeld-Verwaltung” anlegen (Tabelle: Bestellung) und unter “Kunden” -> “Bestellungen” befüllen. Öffnen Sie dazu die entsprechende Bestellung und scrollen Sie in der Bestellübersicht nach unten zu “Freitextfelder”.
</p><p>
<b>Wichtig:</b><br/> magnalister erzeugt und übermittelt die Rechnung, sobald die Bestellung als versendet markiert wird. Bitte achten Sie darauf, dass zu diesem Zeitpunkt das Freitextfeld gefüllt sein muss, da sonst ein Fehler erzeugt wird (Ausgabe im Tab “Fehlerlog”).
<br/><br/>
Nutzen Sie das Freitextfeld-Matching, ist magnalister nicht für die korrekte, fortlaufende Erstellung von Stornorechnungsnummer verantwortlich.
</p>
',
        ),
        'amazonvcsinvoice.reversalinvoicenumber.matching' => array(
            'label' => 'Shopware-Bestellung-Freitextfelder',
        ),
        'amazonvcsinvoice.reversalinvoicenumberoption' =>array(
            'label' => '',
        ),
        'amazonvcsinvoice.reversalinvoiceprefix' => array(
            'label' => 'Präfix Stornorechnung',
            'hint' => 'Wenn Sie hier ein Präfix eintragen, wird es vor die Stornorechnungsnummer gesetzt. Beispiel: S20000. Von magnalister generierte Stornorechnungen beginnen mit der Nummer 20000.',
            'default' => 'S', //@see ML_Amazon_Controller_Amazon_Config_VCS -> useI18nDefault
        ),
        'amazonvcsinvoice.companyadressleft' => array(
            'label' => 'Firmenadresse Anschriftfeld (links)',
            'default' => 'Ihr Name, Ihre Strasse 1, 12345 Ihr Ort', //@see ML_Amazon_Controller_Amazon_Config_VCS -> useI18nDefault
        ),
        'amazonvcsinvoice.companyadressright' => array(
            'label' => 'Adresse Informationsblock rechts',
            'default' => "Ihr Name\nIhre Strasse 1\n\n12345 Ihr Ort", //@see ML_Amazon_Controller_Amazon_Config_VCS -> useI18nDefault
        ),
        'amazonvcsinvoice.headline' => array(
            'label' => 'Überschrift Rechnung',
            'default' => 'Ihre Rechnung', //@see ML_Amazon_Controller_Amazon_Config_VCS -> useI18nDefault
        ),
        'amazonvcsinvoice.invoicehintheadline' => array(
            'label' => 'Überschrift Rechnungshinweise',
            'default' => "Rechnungshinweis", //@see ML_Amazon_Controller_Amazon_Config_VCS -> useI18nDefault
        ),
        'amazonvcsinvoice.invoicehinttext' => array(
            'label' => 'Hinweistext',
            'hint' => 'Leer lassen wenn kein Hinweistext auf der Rechnung erscheinen sollen',
            'default' => "Ihr Hinweistext für die Rechnung", //@see ML_Amazon_Controller_Amazon_Config_VCS -> useI18nDefault
        ),
        'amazonvcsinvoice.footercell1' => array(
            'label' => 'Fußzeile Spalte 1',
            'default' => "Ihr Name\nIhre Strasse 1\n\n12345 Ihr Ort", //@see ML_Amazon_Controller_Amazon_Config_VCS -> useI18nDefault
        ),
        'amazonvcsinvoice.footercell2' => array(
            'label' => 'Fußzeile Spalte 2',
            'default' => "Ihre Telefonnummer\nIhre Faxnummer\nIhre Homepage\nIhre E-Mail", //@see ML_Amazon_Controller_Amazon_Config_VCS -> useI18nDefault
        ),
        'amazonvcsinvoice.footercell3' => array(
            'label' => 'Fußzeile Spalte 3',
            'default' => "Ihre Steuernummer\nIhre Ust. ID. Nr.\nIhre Gerichtsbarkeit\nIhre Informationen", //@see ML_Amazon_Controller_Amazon_Config_VCS -> useI18nDefault
        ),
        'amazonvcsinvoice.footercell4' => array(
            'label' => 'Fußzeile Spalte 4',
            'default' => "Zusätzliche\nInformationen\nin der vierten\nSpalte", //@see ML_Amazon_Controller_Amazon_Config_VCS -> useI18nDefault
        ),
        'amazonvcsinvoice.preview' => array(
            'label' => 'Rechnungsvorschau',
            'buttontext' => 'Vorschau',
            'hint' => 'Hier können Sie sich eine Vorschau Ihrer Rechnung mit den von Ihnen hinterlegten Daten anzeigen lassen.',
        ),
    ),
), false);

// New Shipment Options
MLI18n::gi()->{'amazon_config_carrier_option_group_marketplace_carrier'} = 'Von Amazon vorgeschlagene Transportunternehmen';
MLI18n::gi()->{'amazon_config_carrier_option_group_shopfreetextfield_option_carrier'} = 'Transportunternehmen aus einem Webshop-Freitextfeld (Bestellungen) wählen';
MLI18n::gi()->{'amazon_config_carrier_option_group_shopfreetextfield_option_shipmethod'} = 'Lieferservice aus einem Webshop-Freitextfeld (Bestellungen) wählen';
MLI18n::gi()->{'amazon_config_carrier_option_group_additional_option'} = 'Zusätzliche Optionen';
MLI18n::gi()->{'amazon_config_carrier_option_matching_option_carrier'} = 'Von Amazon vorgeschlagene Transportunternehmen mit Versanddienstleistern aus Webshop Versandkosten-Modul matchen';
MLI18n::gi()->{'amazon_config_carrier_option_matching_option_shipmethod'} = 'Lieferservice mit Einträgen aus Webshop Versandkosten-Modul matchen';
MLI18n::gi()->{'amazon_config_carrier_option_database_option'} = 'Datenbank Matching';
MLI18n::gi()->{'amazon_config_carrier_option_orderfreetextfield_option'} = 'magnalister fügt ein Freitextfeld in den Bestelldetails hinzu';
MLI18n::gi()->{'amazon_config_carrier_option_freetext_option_carrier'} = 'Transportunternehmen pauschal aus Textfeld übernehmen';
MLI18n::gi()->{'amazon_config_carrier_option_freetext_option_shipmethod'} = 'Lieferservice pauschal aus Textfeld übernehmen';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.carrier.freetext__label'} = 'Transportunternehmen:';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.carrier.freetext__placeholder'} = 'Tragen Sie hier ein Transportunternehmen ein';
MLI18n::gi()->{'amazon_config_carrier_matching_title_marketplace_carrier'} = 'Von Amazon vorgeschlagenes Transportunternehmen';
MLI18n::gi()->{'amazon_config_carrier_matching_title_marketplace_shipmethod'} = 'Manuelle Eingabe eines Lieferservice';
MLI18n::gi()->{'amazon_config_carrier_matching_title_shop_carrier'} = 'Versanddienstleister aus Webshop Versandkosten-Modul';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.shipmethod__label'} = 'Lieferservice (Versandart / Versandmethode)';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.shipmethod__hint'} = 'Wählen Sie hier den Lieferservice (Versandart / Versandmethode), der allen Amazon Bestellungen standardmäßig zugeordnet wird. Eine Angabe ist seitens Amazon verpflichtend. Weitere Details siehe Info-Icon.';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.shipmethod.freetext__label'} = 'Lieferservice:';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.shipmethod.freetext__placeholder'} = 'Tragen Sie hier einen Lieferservice ein';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.shippedaddress__label'} = 'Versand bestätigen und Absenderadresse festlegen';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.shippedaddress__help'} = '
Wählen Sie unter “Bestellstatus” den Webshop-Status aus, mit dem der Versand der Ware bestätigt werden soll.<br>
<br>
Rechts daneben können Sie die Adresse eintragen, von der die Ware versendet wird. Das bietet sich an, wenn die Versandadresse von der in Amazon hinterlegten Standard-Adresse abweichen soll (z. B. bei Versand aus einem externen Warenlager).<br>
<br>
Wenn Sie die Adressfelder leer lassen, verwendet Amazon die Absenderadresse, die Sie in Ihren Amazon Versandeinstellungen (Seller Central) angegeben haben.
';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.shippedaddress.name__label'} = 'Name des Lagerstandortes';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.shippedaddress.line1__label'} = 'Adresse (Zeile 1)';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.shippedaddress.line2__label'} = 'Adresse (Zeile 2)';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.shippedaddress.line3__label'} = 'Adresse (Zeile 3)';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.shippedaddress.city__label'} = 'Stadt';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.shippedaddress.county__label'} = 'Bezirk';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.shippedaddress.stateorregion__label'} = 'Bundesland';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.shippedaddress.postalcode__label'} = 'Postleitzahl';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.shippedaddress.countrycode__label'} = 'Land';
