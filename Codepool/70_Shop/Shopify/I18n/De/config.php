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

MLI18n::gi()->Vendor = 'Anbieter';
MLI18n::gi()->SKU = 'Artikelnummer (Stock Keeping Unit)';
MLI18n::gi()->Barcode = 'Barcode (ISBN, UPC, GTIN usw.)';
MLI18n::gi()->Description = 'Artikel-Beschreibung';
MLI18n::gi()->ItemName = 'Artikel-Titel';
MLI18n::gi()->Weight = 'Gewicht';
MLI18n::gi()->ProductType = 'Produkttyp';
MLI18n::gi()->Shopify_Carrier_Other = 'Andere';
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
<p>';
MLI18n::gi()->OrderStatus_Open = 'Nicht ausgeführt';
MLI18n::gi()->OrderStatus_Fulfilled = 'Ausgeführt';
MLI18n::gi()->OrderStatus_Cancelled = 'Storniert';
MLI18n::gi()->FinancialStatus_Empty = 'Lassen Sie magnalister bestimmen, ob der Auftrag "bezahlt" oder "ausstehend" ist.';
MLI18n::gi()->FinancialStatus_Pending = 'Ausstehend';
MLI18n::gi()->FinancialStatus_Authorized = 'Autorisiert';
MLI18n::gi()->FinancialStatus_PartiallyPaid = 'Teilweise bezahlt';
MLI18n::gi()->FinancialStatus_Paid = 'Bezahlt';
MLI18n::gi()->FinancialStatus_PartiallyRefunded = 'Teilweise zurückerstattet';
MLI18n::gi()->FinancialStatus_Refunded = 'Zurückerstattet';
MLI18n::gi()->FinancialStatus_Voided = 'Storniert';
MLI18n::gi()->CustomerGroupSettingNotSupported = 'Diese Option wird von Shopify nicht unterstützt.';

MLI18n::gi()->set('formfields_config_uploadInvoiceOption_help_erp', '', true);
MLI18n::gi()->set('formfields_config_uploadInvoiceOption_help_webshop', '', true);


//shopify collection vat matching
MLI18n::gi()->set('orderimport_shopify_vatmatching_label', 'Mehrwertsteuer-Matching');
MLI18n::gi()->set('orderimport_shopifyvatmatching_help', '<p>Shopify ermöglicht Drittanbieter-Apps keinen Zugriff auf die Steuer-Einstellungen. Sie können diese Einstellungen daher in magnalister direkt vornehmen, um Bestellungen mit dem entsprechenden Mehrwertsteuersatz zu importieren.</p>
<p>Matchen Sie dazu eine Shopify Collection mit dem gewünschten Zielland und Mehrwertsteuersatz. Die Mehrwertsteuersätze werden während des magnalister Bestellimports von Shopify Produkten in den Bestelldetails hinterlegt.</p>
<b>Hinweise:</b>
<ul>
<li>Wenn Produkten mehrere Shopify Collections zugewiesen sind, die unterschiedliche Mehrwertsteuersätze enthalten, so wird beim Bestellimport nur der Steuersatz übernommen, der zuerst gematched wurde.</li>
<li>Wenn die importierte Bestellung nicht mit einer magnalister Steuerkonfiguration gematched werden kann, wird der von der Shopify API zur Verfügung gestellte Standard-Mehrwertsteuersatz angewendet (Beispiel: Für Bestellungen mit dem Zielland Deutschland: 19 %)</li>
<li>Wenn Sie im “Shopify Collection” Dropdown die Option “{#i18n:shopify_global_configuration_vat_matching_option_all#}” auswählen, können Sie allen Produkten einen einheitlichen Mehrwertsteuersatz zuweisen, unabhängig von der von Shopify zugewiesenen Collection.</li>
</ul>');

MLI18n::gi()->{'orderimport_shopify_vatmatching_collection_label'} = 'Shopify Collection';

MLI18n::gi()->{'orderimport_shopify_vatmatching_shipping_country_label'} = 'Zielland der Bestellung';
MLI18n::gi()->{'orderimport_shopifyvatmatching_vatrate_label'} = 'Mehrwertsteuer in %';
MLI18n::gi()->{'shopify_global_configuration_vat_matching_option_all'} = 'Alle Collections';
MLI18n::gi()->{'shopify_global_configuration_vat_matching_option_all_countries'} = 'Alle Länder';