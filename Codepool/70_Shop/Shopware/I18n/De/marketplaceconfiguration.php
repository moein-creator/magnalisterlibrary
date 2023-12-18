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

//after ebay new configuration these translation are deprecated , please remove them by test
MLI18n::gi()->Shopware_Marketplace_Configuration_PaymentMethod_Info = '<p>Zahlart, die allen {#platformName#}-Bestellungen beim Bestellimport zugeordnet wird. 
Standard: "Automatische Zuordnung"</p>
<p>
Wenn Sie „Automatische Zuordnung" wählen, &uuml;bernimmt magnalister die Zahlart, die der K&auml;ufer auf {#platformName#} gew&auml;hlt hat.
Diese wird dann zus&auml;tzlich auch unter Shopware > Einstellungen > Zahlungsarten angelegt.</p>
<p>
Alle weiteren verf&uuml;gbaren Zahlarten in der Liste k&ouml;nnen Sie ebenfalls unter Shopware > Einstellungen > Zahlungsarten definieren und hier&uuml;ber dann verwenden.</p>
<p>
Diese Einstellung ist wichtig f&uuml;r den Rechnungs- und Lieferscheindruck, und f&uuml;r die nachtr&auml;gliche Bearbeitung der Bestellung im Shop, sowie in Warenwirtschaften.</p>';

MLI18n::gi()->Shopware_EBay_Configuration_ShippingMethod_Info ='<p>Versandart, die allen {#platformName#}-Bestellungen beim Bestellimport zugeordnet wird.  Standard: "Automatische Zuordnung"</p>
<p>Wenn Sie „Automatische Zuordnung" w&auml;hlen, &uuml;bernimmt magnalister die Versandart, die der K&auml;ufer auf {#platformName#} gew&auml;hlt hat. Diese wird dann zus&auml;tzlich auch unter Shopware > Einstellungen > Versandkosten angelegt.</p>
<p>Alle weiteren verf&uuml;gbaren Versandarten in der Liste k&ouml;nnen Sie ebenfalls unter Shopware > Einstellungen > Versandkosten definieren und hier&uuml;ber dann verwenden.</p>
<p>Diese Einstellung ist wichtig f&uuml;r den Rechnungs- und Lieferscheindruck, und f&uuml;r die nachtr&auml;gliche Bearbeitung der Bestellung im Shop, sowie in Warenwirtschaften.</p>';


MLI18n::gi()->Shopware_Amazon_Configuration_ShippingMethod_Info ='<p>Amazon &uuml;bergibt beim Bestellimport keine Information der Versandart.</p>
<p>W&auml;hlen Sie daher bitte hier die verf&uuml;gbaren Web-Shop-Versandarten. Die Inhalte aus dem Drop-Down k&ouml;nnen Sie unter Shopware > Einstellungen > Versandkosten definieren.</p>
<p>Diese Einstellung ist wichtig f&uuml;r den Rechnungs- und Lieferscheindruck, und f&uuml;r die nachtr&auml;gliche Bearbeitung der Bestellung im Shop, sowie in Warenwirtschaften.</p> ';
MLI18n::gi()->Shopware_Ebay_Configuration_Updateable_OrderStatus_Label = 'Bestell-Status-&Auml;nderung zulassen wenn';
MLI18n::gi()->Shopware_Ebay_Configuration_Updateable_PaymentStatus_Label = 'Zahl-Status-&Auml;nderung zulassen wenn';
MLI18n::gi()->Shopware_Ebay_Configuration_Updateable_PaymentStatus_Info = 'Status der Bestellungen, die bei eBay-Zahlungen ge&auml;ndert werden d&uuml;rfen.
			                Wenn die Bestellung einen anderen Status hat, wird er bei eBay-Zahlungen nicht ge&auml;ndert.<br /><br />
			                Wenn Sie gar keine &Auml;nderung des Zahlstatus bei eBay-Zahlung w&uuml;nschen, deaktivieren Sie die Checkbox.<br /><br />
			                <b>Hinweis:</b> Der Status von zusammengefa&szlig;ten Bestellungen wird nur dann ge&auml;ndert, wenn alle Teile bezahlt sind.';
MLI18n::gi()->Shopware_Ebay_Configuration_ArticleDescriptionTemplate_sExternalDesc = '
Liste verf&uuml;gbarer Platzhalter f&uuml;r die Produktbeschreibung:
<dl>
    <dt>#TITLE#</dt>
            <dd>Produktname (Titel)</dd>
    <dt>#ARTNR#</dt>
            <dd>Artikelnummer im Shop</dd>
    <dt>#PID#</dt>
            <dd>Products ID im Shop</dd>
    <!--<dt>#PRICE#</dt>
            <dd>Preis</dd>
    <dt>#VPE#</dt>
            <dd>Preis pro Verpackungseinheit</dd>-->
    <dt>#SHORTDESCRIPTION#</dt>
            <dd>Kurzbeschreibung aus dem Shop</dd>
    <dt>#DESCRIPTION#</dt>
            <dd>Beschreibung aus dem Shop</dd>
    <dt>#MOBILEDESCRIPTION#</dt>
        <dd>Kurzbeschreibung für mobile Ger&auml;te, falls hinterlegt</dd>
    <dt>#PICTURE1#</dt>
            <dd>erstes Produktbild</dd>
    <dt>#PICTURE2# usw.</dt>
            <dd>zweites Produktbild; mit #PICTURE3#, #PICTURE4# usw. k&ouml;nnen weitere Bilder &uuml;bermittelt werden, so viele wie im Shop vorhanden.</dd>'
        . '<br><dt>Artikel-Freitextfelder:</dt><br>'
        . '<dt>#Bezeichnung1#&nbsp;#Freitextfeld1#</dt>'
        . '<dt>#Bezeichnung2#&nbsp;#Freitextfeld2#</dt>'
        . '<dt>#Bezeichnung..#&nbsp;#Freitextfeld..#</dt><br>'
        . '<dd>&Uuml;bernahme der Artikel-Freitextfelder:&nbsp;'
        . 'Die Ziffer hinter dem Platzhalter (z.B. #Freitextfeld1#) entspricht der Position des Freitextfelds.
                           <br> Siehe Einstellungen > Grundeinstellungen > Artikel > Artikel-Freitextfelder</dd>' 
        .'<dt>#PROPERTIES#</dt>'
        .'<dd>Eine Liste aller Produkteigenschaften des Produktes. Aussehen kann &uuml;ber CSS gesteuert werden (siehe Code vom Standard Template)</dd>'.
        '</dl>';
MLI18n::gi()->Shopware_Ebay_Configuration_ArticleDescriptionTemplate_sDefault = "<style>
ul.magna_properties_list {
    margin: 0 0 20px 0;
    list-style: none;
    padding: 0;
    display: inline-block;
    width: 100%
}
ul.magna_properties_list li {
    border-bottom: none;
    width: 100%;
    height: 20px;
    padding: 6px 5px;
    float: left;
    list-style: none;
}
ul.magna_properties_list li.odd {
    background-color: rgba(0, 0, 0, 0.05);
}
ul.magna_properties_list li span.magna_property_name {
    display: block;
    float: left;
    margin-right: 10px;
    font-weight: bold;
    color: #000;
    line-height: 20px;
    text-align: left;
    font-size: 12px;
    width: 50%;
}
ul.magna_properties_list li span.magna_property_value {
    color: #666;
    line-height: 20px;
    text-align: left;
    font-size: 12px;

    width: 50%;
}
</style>
<p>#TITLE#</p>
<p>#ARTNR#</p>
<p>#SHORTDESCRIPTION#</p>
<p>#PICTURE1#</p>
<p>#PICTURE2#</p>
<p>#PICTURE3#</p>
<p>#DESCRIPTION#</p>
<p>#MOBILEDESCRIPTION#</p>
<p>#Bezeichnung1# #Freitextfeld1#</p>
<p>#Bezeichnung2# #Freitextfeld2#</p>
<div>#PROPERTIES#</div>";
MLI18n::gi()->Shopware_Ebay_Configuration_PaidStatus_Payment_sLabel = 'Zahlstatus';
MLI18n::gi()->Shopware_Ebay_Configuration_PaidStatus_Order_sLabel = 'Bestellstatus';

MLI18n::gi()->Shopware_Amazon_Configuration_PaymentStatus_sLabel = 'Zahlstatus im Shop';
MLI18n::gi()->Shopware_Amazon_Configuration_PaymentStatus_sDescription = 'Der Zahlstatus im Web-Shop, den eine von Amazon neu eingegangene Bestellung im Shop automatisch bekommen soll.';

MLI18n::gi()->form_config_orderimport_exchangerate_update_help = '<strong>Grundsätzlich:</strong>
<p>
Wenn die Web-Shop Standard-Währung von der Marktplatz-Währung abweicht, berechnet magnalister beim Bestellimport und beim Artikelupload anhand des Währungskurses, der im Web-Shop hinterlegt ist. 
Beim Bestellimport verhält sich magnalister beim Speichern der Währungen und Beträge 1:1 so, wie der Web-Shop sie bei Bestelleingang auch anlegt.
</p>
<strong>Achtung:</strong>
<p>
Durch Aktivieren dieser Funktion hier wird der im Web-Shop hinterlegte Wechselkurs mit dem aktuellen Kurs aus "alphavantage" aktualisiert. 
<u>Dadurch werden auch die Preise in Ihrem Web-Shop mit dem aktualisierten Wechselkurs zum Verkauf angezeigt:</u>
</p>
<p>
Folgende Funktionen lösen die Aktualisierung aus:
<ul>
<li>Bestellimport</li>
<li>Artikel-Vorbereitung</li>
<li>Artikel-Upload</li>
<li>Lager-/Preis-Synchronisation</li>
</ul>
</p>
<p>
Sollte der Währungskurs eines Marktplatzes in der Währungskonfiguration des Web-Shops nicht angelegt sein, gibt magnalister eine Fehlermeldung aus.
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

MLI18n::gi()->{'global_config_price_field_price.discountmode_label'} = 'Rabatt-Modus';
MLI18n::gi()->{'orderimport_vatcustomergroup_label'} = 'Webshop-Steuerregeln für Bestellungen aus Kundengruppe übernehmen';
MLI18n::gi()->{'orderimport_vatcustomergroup_help'} = '
<p>
Standardmäßig verwendet magnalister für die Berechnung der Steuern beim Bestellimport, die Webshop-Steuerregeln für die Kundengruppe, welche Sie in der Konfiguration "Kundengruppe" gewählt haben.
</p>
<p>
Mit der Option "Webshop-Steuerregeln für Bestellungen aus Kundengruppe übernehmen" können Sie für die Steuerberechnung eine abweichende Kundengruppe wählen.
</p>
<p>
Die Webshop-Steuerregeln finden Sie in Ihrem Shop unter:<br>
Grundeinstellungen -> Shopeinstellungen -> Steuern
</p>
';
