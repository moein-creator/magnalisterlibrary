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


MLI18n::gi()->WooCommerce_Configuration_ShippingMethod_NotAvailable_Info = '<p>{#setting:currentMarketplaceName#} &uuml;bergibt beim Bestellimport keine Information der Versandart.</p>
<p>W&auml;hlen Sie daher bitte hier die verf&uuml;gbaren Web-Shop-Versandarten. Die Inhalte aus dem Drop-Down k&ouml;nnen Sie unter WooCommerce > Einstellungen > Versand > Versandzone > Versandart definieren.</p>
<p>Diese Einstellung ist wichtig f&uuml;r den Rechnungs- und Lieferscheindruck, und f&uuml;r die nachtr&auml;gliche Bearbeitung der Bestellung im Shop, sowie in Warenwirtschaften.</p>';
MLI18n::gi()->WooCommerce_Configuration_ShippingMethod_Available_Info = '<p>Versandart, die allen {#setting:currentMarketplaceName#}-Bestellungen beim Bestellimport zugeordnet wird.  Standard: "Automatische Zuordnung"</p>
<p>Wenn Sie „Automatische Zuordnung" w&auml;hlen, &uuml;bernimmt magnalister die Versandart, die der K&auml;ufer auf {#setting:currentMarketplaceName#} gew&auml;hlt hat.</p>
<p>Alle weiteren verf&uuml;gbaren Versandarten in der Liste k&ouml;nnen Sie ebenfalls unter WooCommerce > Einstellungen > Versand >  Versandzone > Versandart definieren und hier&uuml;ber dann verwenden.</p>
<p>Diese Einstellung ist wichtig f&uuml;r den Rechnungs- und Lieferscheindruck, und f&uuml;r die nachtr&auml;gliche Bearbeitung der Bestellung im Shop, sowie in Warenwirtschaften.</p>';
MLI18n::gi()->form_config_orderimport_exchangerate_update_help = '<strong>Grundsätzlich:</strong>
<p>
Wenn die im Webshop eingestellte Währung von der des Marktplatzes abweicht, berechnet magnalister den Artikelpreis mithilfe einer automatischen Währungsumrechnung.
</p>
<strong>Achtung:</strong>
<p>
Dazu greifen wir auf den vom externen Währungsumrechner “alphavantage” ausgegebenen Wechselkurs zurück. Wichtig: Für die Währungsumrechnung von externen Diensten übernehmen wir keine Haftung.
</p>
<p>
Folgende Funktionen lösen eine Aktualisierung des Währungskurses aus:
<ul>
<li>Bestellimport</li>
<li>Artikel-Vorbereitung</li>
<li>Artikel-Upload</li>
<li>Lager-/Preis-Synchronisation</li>
</ul>
</p>
<p>
Der Währungskurs wird außerdem alle 24 Stunden automatisch aktualisiert. In diesem Feld sehen Sie den zuletzt aktualisierten Umrechnungskurs und wann dieser zuletzt aktualisiert wurde.
</p>';
MLI18n::gi()->form_config_orderimport_exchangerate_update_alert = '<strong>Achtung:</strong>
<p>
Durch Aktivieren wird der im Web-Shop hinterlegte Wechselkurs mit dem aktuellen Kurs aus "alphavantage" aktualisiert. 
<u>Dadurch werden auch die Preise in Ihrem Web-Shop mit dem aktualisierten Wechselkurs zum Verkauf angezeigt:</u>
</p><p>
Folgende Funktionen lösen die Aktualisierung aus:
<ul>
<li>Bestellimport</li>
<li>Artikel-Vorbereitung</li>
<li>Artikel-Upload</li>
<li>Lager-/Preis-Synchronisation</li>
</ul>
<p>
';


MLI18n::gi()->WooCommerce_Configuration_PaymentMethod_NotAvailable_Info = '
                <p>Zahlart, die allen {#setting:currentMarketplaceName#}-Bestellungen beim Bestellimport zugeordnet wird.</p>
                <p>Alle weiteren verf&uuml;gbaren Zahlarten in der Liste k&ouml;nnen Sie ebenfalls unter WooCommerce > Einstellungen > Zahlungen definieren und hier&uuml;ber dann verwenden.</p>
                <p>Diese Einstellung ist wichtig f&uuml;r den Rechnungs- und Lieferscheindruck, und f&uuml;r die nachtr&auml;gliche Bearbeitung der Bestellung im Shop, sowie in Warenwirtschaften.</p>';

MLI18n::gi()->WooCommerce_Configuration_PaymentMethod_Available_Info =
    '<p>Zahlart, die allen eBay-Bestellungen beim Bestellimport zugeordnet wird. 
Standard: "Automatische Zuordnung"</p>
<p>
Wenn Sie „Automatische Zuordnung" w&auml;hlen, &uuml;bernimmt magnalister die Zahlart, die der K&auml;ufer auf eBay gew&auml;hlt hat.</p>
<p>
Diese Einstellung ist wichtig f&uuml;r den Rechnungs- und Lieferscheindruck, und f&uuml;r die nachtr&auml;gliche Bearbeitung der Bestellung im Shop, sowie in Warenwirtschaften.</p>';

MLI18n::gi()->set('formfields_config_uploadInvoiceOption_help_webshop', '', true);

MLI18n::gi()->{'woocommerce_config_trackingkey_help_warning'} = '<b>Gut zu wissen für o.g. Drittanbieter-Plugins</b>: magnalister übermittelt auch den Namen des Versandunternehmens aus dem Drittanbieter-Plugin.';
MLI18n::gi()->orderimport_trackingkey = array(
    'label' => 'Sendungsnummer',
    'help'  => '<p>Sie haben folgende Möglichkeiten, die Sendungsnummer einer über magnalister importierten Marktplatz-Bestellung per Bestellstatus-Abgleich an den Marktplatz/Käufer zu übermitteln:
</p>
<ol>
<li><p><h5>“Individuelles Feld” in WooCommerce anlegen und in magnalister auswählen</h5>
</p><p>
In der WooCommerce Verwaltung unter “Orders” -> [Bestellung] lassen sich “individuelle Felder” anlegen. Nennen Sie das “individuelle Feld” in der Spalte “Name” z. B. “Sendungsnummer” und tragen Sie in der Spalte “Value” die Sendungsnummer der entsprechenden Bestellung ein.
</p><p>
Danach kehren Sie zu dieser Stelle im magnalister Plugin zurück und wählen aus der nebenstehenden Dropdown-Liste unter “Individuelle Felder” aus WooCommerce” das in den Bestelldetails angelegte Feld aus (dem o.g. Beispiel folgend mit dem Namen “Sendungsnummer”).
</p>
</li>
<li>
<p>
<h5>magnalister fügt “individuelles Feld” in den Bestelldetails hinzu</h5>
</p><p>
Wenn Sie diese Option wählen, fügt magnalister automatisch beim Bestellimport ein “individuelles Feld” unter “Orders” -> [Bestellung] -> “Individuelle Felder” hinzu.
</p><p>
Sie haben nun die Möglichkeit, die entsprechende Sendungsnummer dort einzutragen.
</p>
</li><li>
<p>
<h5>magnalister greift auf Sendungsnummer-Feld von Drittanbieter-Plugins zu</h5>
</p><p>
magnalister kann auf Sendungsnummer-Felder aus bestimmten WooCommerce Drittanbieter-Plugins zugreifen. Darunter fallen folgende Plugins:
</p><p>
Germanized Plugin
</p><p>
Um die Sendungsnummer aus dem Germanized Plugin per magnalister an den Marktplatz zu übertragen, wählen Sie in der nebenstehenden Dropdown-Liste die Option “Germanized Plugin: Sendungsnummer von dort verwenden”
</p><p>
Die Sendungsnummer tragen Sie bei Verwendung des  Germanized Plugins in den Bestelldetails unter “Sendungen” -> “Sendungsnummer” ein.
</p><p>
Advanced Shipment Tracking Plugin
</p><p>
Um die Sendungsnummer aus dem Advanced Shipment Tracking Plugin per magnalister an den Marktplatz zu übertragen, wählen Sie in der nebenstehenden Dropdown-Liste die Option “Advanced Shipment Tracking Plugin: Sendungsnummer von dort verwenden”.
</p><p>
Die Sendungsnummer tragen Sie bei Verwendung des  Advanced Shipment Tracking Plugins in den Bestelldetails unter “Sendungsverfolgung” -> “Sendungscode” ein.
</p><p>
{#i18n:woocommerce_config_trackingkey_help_warning#}
</p>
</li>
</ol>',
);
MLI18n::gi()->{'woocommerce_config_trackingkey_option_group_customfields'} = '“Individuelle Felder” aus WooCommerce';
MLI18n::gi()->{'woocommerce_config_trackingkey_option_group_additional_option'} = 'Zusätzliche Optionen';
MLI18n::gi()->{'woocommerce_config_trackingkey_option_orderfreetextfield_option'} = 'magnalister fügt ein “individuelles Feld” in den Bestelldetails hinzu';
MLI18n::gi()->{'woocommerce_config_trackingkey_option_plugin_ast'} = 'Advanced Shipment Tracking Plugin: Sendungsnummer von dort verwenden';
MLI18n::gi()->{'woocommerce_config_trackingkey_option_plugin_germanized'} = 'Germanized Plugin: Sendungsnummer von dort verwenden';

MLI18n::gi()->{'marketplace_config_carrier_option_matching_option_plugin'} = 'Matchen der vom Marktplatz unterstützten Versanddienstleister mit den im "{#pluginname#}"-Plugin definierten Versanddienstleister';
MLI18n::gi()->{'marketplace_config_carrier_matching_title_shop_carrier_plugin'} = 'Im "{#pluginname#}"-Plugin definierte Versanddienstleister (Versandoptionen)';
MLI18n::gi()->{'amazon_config_carrier_option_matching_option_carrier_plugin'} = 'Von Amazon vorgeschlagene Transportunternehmen mit Versanddienstleistern aus "{#pluginname#}"-Plugin matchen';
MLI18n::gi()->{'amazon_config_carrier_option_matching_option_shipmethod_plugin'} = 'Lieferservice mit Einträgen aus "{#pluginname#}"-Plugin Versanddienstleistern matchen';