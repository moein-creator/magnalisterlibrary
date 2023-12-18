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

MLI18n::gi()->Vendor = 'Anbieter';
MLI18n::gi()->SKU = 'Artikelnummer (Stock Keeping Unit)';
MLI18n::gi()->Barcode = 'Barcode (ISBN, UPC, GTIN usw.)';
MLI18n::gi()->Description = 'Artikel-Beschreibung';
MLI18n::gi()->ItemName = 'Artikel-Titel';
MLI18n::gi()->Weight = 'Gewicht';
MLI18n::gi()->ProductType = 'Produkttyp';
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

MLI18n::gi()->set('formfields_config_uploadInvoiceOption_help_erp', '', true);
MLI18n::gi()->set('formfields_config_uploadInvoiceOption_help_webshop', '', true);