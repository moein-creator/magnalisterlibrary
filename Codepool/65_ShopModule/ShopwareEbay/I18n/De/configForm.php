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
MLI18n::gi()->{'ebay_config_carrier_option_group_shopfreetextfield_option_carrier'} = 'Transportunternehmen aus einem Webshop-Freitextfeld (Bestellungen) wählen';
MLI18n::gi()->{'ebay_config_carrier_option_group_marketplace_carrier'} = 'Von eBay vorgeschlagene Transportunternehmen';
MLI18n::gi()->{'ebay_config_carrier_option_group_additional_option'} = 'Zusätzliche Option';
MLI18n::gi()->ebay_config_producttemplate_content =
    '<style>
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
<div>#PROPERTIES#</div>';

MLI18n::gi()->add('ebay_config_orderimport', array(
     'field' => array(
         'updateablepaymentstatus' => array(
             'label' => 'Zahl-Status-&Auml;nderung zulassen wenn',
             'help' => 'Status der Bestellungen, die bei eBay-Zahlungen ge&auml;ndert werden d&uuml;rfen.
			                Wenn die Bestellung einen anderen Status hat, wird er bei eBay-Zahlungen nicht ge&auml;ndert.<br /><br />
			                Wenn Sie gar keine &Auml;nderung des Zahlstatus bei eBay-Zahlung w&uuml;nschen, deaktivieren Sie die Checkbox.',
         ),
        'paidstatus'=> array(
            'label' => 'Bestell-/Zahlstatus für bezahlte eBay Bestellungen',
            'help' => '<p>eBay-Bestellungen werden vom Käufer teils zeitverzögert bezahlt.
<br><br>
Damit Sie nicht-bezahlte Bestellungen von bezahlten Bestellungen trennen zu können, können Sie hier für bezahlte eBay-Bestellungen einen eigenen Webshop Bestellstatus, sowie einen Zahlstatus wählen.
<br><br>
Wenn Bestellungen von eBay importiert werden, die noch nicht bezahlt sind, so greift der Bestellstatus, den Sie oben unter "Bestellimport" > "Bestellstatus im Shop" festgelegt haben." 
<br><br>
Wenn Sie oben “Nur bezahlt-markierte Bestellungen importieren” aktiviert haben, wird ebenfalls der “Bestellstatus im Shop” von oben verwendet. Die Funktion hier ist in dem Fall dann ausgegraut.
'
        ),
        'orderstatus.paid' => array(
            'label' => 'Bestellstatus',
            'help' => '',
        ),
        'paymentstatus.paid' => array(
            'label' => 'Zahlstatus',
            'help' => '',
        ),
        'updateable.paymentstatus' => array(
            'label' => '',
            'help' => '',
        ),
        'update.paymentstatus' => array(
            'label' => 'Status-&Auml;nderung aktiv',
        ),
        'orderimport.paymentmethod' => array(
            'label' => 'Zahlart der Bestellungen',
            'help' => '<p>Zahlart, die allen eBay-Bestellungen beim Bestellimport zugeordnet wird. 
<p>
Alle Zahlarten in der Liste k&ouml;nnen Sie ebenfalls unter Shopware > Einstellungen > Zahlungsarten definieren und hier&uuml;ber dann verwenden.
</p>
<p>
Diese Einstellung ist wichtig f&uuml;r den Rechnungs- und Lieferscheindruck, und f&uuml;r die nachtr&auml;gliche Bearbeitung der Bestellung im Shop, sowie in Warenwirtschaften.
</p>',
            'hint' => '',
        ),
        'orderimport.shippingmethod' => array(
            'label' => 'Versandart der Bestellungen',
            'help' => '<p>Versandart, die allen eBay-Bestellungen beim Bestellimport zugeordnet wird. 
Standard: "Automatische Zuordnung"</p>
<p>
Wenn Sie „Automatische Zuordnung" wählen, &uuml;bernimmt magnalister die Versandart, die der K&auml;ufer auf eBay gew&auml;hlt hat.
Diese wird dann zus&auml;tzlich auch unter Shopware > Einstellungen > Versandkosten angelegt.</p>
<p>
Alle weiteren verf&uuml;gbaren Versandart in der Liste k&ouml;nnen Sie ebenfalls unter Shopware > Einstellungen > Versandkosten definieren und hier&uuml;ber dann verwenden.</p>
<p>
Diese Einstellung ist wichtig f&uuml;r den Rechnungs- und Lieferscheindruck, und f&uuml;r die nachtr&auml;gliche Bearbeitung der Bestellung im Shop, sowie in Warenwirtschaften.</p>',
            'hint' => '',
        ),
        'orderimport.paymentstatus' => array(
            'label' => 'Zahlstatus im Shop',
            'hint' => 'Wählen Sie hier, welcher Webshop-Zahlstatus während des magnalister Bestellimports in den Bestelldetails hinterlegt werden soll.',
        ),
    ),
), true);

MLI18n::gi()->{'ebay_config_orderimport__field__orderstatus.open__help'} = '
                Legen Sie hier den Bestellstatus im Webshop fest, den eine von eBay neu eingegangene Bestellung automatisch bekommen soll.
<br><br>
Bitte beachten Sie, dass hierbei sowohl bezahlte, als auch nicht bezahlte eBay Bestellungen importiert werden.
<br><br>
Sie können jedoch in der folgenden Funktion "Nur bezahlt-markierte Bestellungen importieren" festlegen, ausschließlich bezahlte eBay-Bestellungen in Ihren Webshop übernehmen zu lassen. 
<br><br><br>

Für bezahlte eBay-Bestellungen können Sie einen eigenen Bestellstatus weiter unten, bei "Bestellstatus-Synchronisation" > "Bestell-/Zahlstatus für bezahlte eBay Bestellungen" festlegen.
            ';
MLI18n::gi()->add('ebay_config_producttemplate', array(
    'field' => array(
        'template.content' => array(
            'label' => 'Template Produktbeschreibung',
            'hint' => '
Liste verf&uuml;gbarer Platzhalter f&uuml;r die Produktbeschreibung:
<dl>
    <dt>#TITLE#</dt>
        <dd>Produktname (Titel)</dd><br>
    <dt>#ARTNR#</dt>
        <dd>Artikelnummer im Shop</dd><br>
    <dt>#PID#</dt>
        <dd>Produkt ID im Shop</dd><br>
    <!--<dt>#PRICE#</dt>
            <dd>Preis</dd>
    <dt>#VPE#</dt>
            <dd>Preis pro Verpackungseinheit</dd>-->
    <dt>#SHORTDESCRIPTION#</dt>
        <dd>Kurzbeschreibung aus dem Shop</dd><br>
    <dt>#DESCRIPTION#</dt>
        <dd>Beschreibung aus dem Shop</dd><br>
    <dt>#MOBILEDESCRIPTION#</dt>
        <dd>Kurzbeschreibung für mobile Ger&auml;te, falls hinterlegt</dd><br>
    <dt>#PICTURE1#</dt>
        <dd>erstes Produktbild</dd><br>
    <dt>#PICTURE2# usw.</dt>
            <dd>zweites Produktbild; mit #PICTURE3#, #PICTURE4# usw. k&ouml;nnen weitere Bilder &uuml;bermittelt werden, so viele wie im Shop vorhanden.</dd>'
        .'<br><dt>Artikel-Freitextfelder:</dt><br>'
        .'<dt>#Bezeichnung1#&nbsp;#Freitextfeld1#</dt>'
        .'<dt>#Bezeichnung2#&nbsp;#Freitextfeld2#</dt>'
        .'<dt>#Bezeichnung..#&nbsp;#Freitextfeld..#</dt><br>'
        .'<dd>&Uuml;bernahme der Artikel-Freitextfelder:&nbsp;'
        .'Die Ziffer hinter dem Platzhalter (z.B. #Freitextfeld1#) entspricht der Position des Freitextfelds.
                <br> Siehe Einstellungen > Grundeinstellungen > Artikel > Artikel-Freitextfelder</dd><br>'
        .'<dt>#PROPERTIES#</dt>'
        .'<dd>Eine Liste aller Produkteigenschaften des Produktes. Aussehen kann &uuml;ber CSS gesteuert werden (siehe Code vom Standard Template)</dd>'.
        '</dl>',
        ),
    ),
), true);
MLI18n::gi()->{'ebay_config_orderimport__field__customergroup__help'} = '{#i18n:global_config_orderimport_field_customergroup_help#}';
MLI18n::gi()->add('ebay_config_price', array(
    'field' => array(
        'fixed.priceoptions' => array(
            'label' => 'Verkaufspreis aus Kundengruppe',
            'help' => '<p>Mit dieser Funktion k&ouml;nnen Sie abweichende Preise zu {#setting:currentMarketplaceName#} &uuml;bergeben und automatisch synchronisieren lassen.</p>
<p>Wählen Sie dazu über das nebenstehende Dropdown eine Kundengruppe aus Ihrem Webshop. </p>
<p>Wenn Sie keinen Preis in der neuen Kundengruppe eintragen, wird automatisch der Standard-Preis aus dem Webshop verwendet. Somit ist es sehr einfach, auch für nur wenige Artikel einen abweichenden Preis zu hinterlegen. Die übrigen Konfigurationen zum Preis finden ebenfalls Anwendung.</p>
<p><b>Anwendungsbeispiel:</b></p>
<ul>
<li>Hinterlegen Sie in Ihrem Web-Shop eine Kundengruppe z.B. "{#setting:currentMarketplaceName#}-Kunden"</li>
<li>F&uuml;gen Sie in Ihrem Web-Shop an den Artikeln in der neuen Kundengruppe die gew&uuml;nschten Preise ein.</li>
</ul>
<p>Auch der Rabatt-Modus der Kundengruppen kann genutzt werden. Sie können dort einen (prozentualen) Rabatt hinterlegen. Sofern der Rabatt-Modus im Shopware Artikel aktiviert ist, wird der rabattierte Preis per magnalister an den Marktplatz übermittelt. Wichtig dabei ist, dass der Marktplatz-Preis nicht als Streichpreis angezeigt wird.</p>',
            'hint' => '',
        ),
        'chinese.priceoptions' => array(
            'label' => 'Preis aus Kundengruppe',
            'help' => '<p>Mit dieser Funktion k&ouml;nnen Sie abweichende Preise zu {#setting:currentMarketplaceName#} &uuml;bergeben und automatisch synchronisieren lassen.</p>
<p>Wählen Sie dazu über das nebenstehende Dropdown eine Kundengruppe aus Ihrem Webshop. </p>
<p>Wenn Sie keinen Preis in der neuen Kundengruppe eintragen, wird automatisch der Standard-Preis aus dem Webshop verwendet. Somit ist es sehr einfach, auch für nur wenige Artikel einen abweichenden Preis zu hinterlegen. Die übrigen Konfigurationen zum Preis finden ebenfalls Anwendung.</p>
<p><b>Anwendungsbeispiel:</b></p>
<ul>
<li>Hinterlegen Sie in Ihrem Web-Shop eine Kundengruppe z.B. "{#setting:currentMarketplaceName#}-Kunden"</li>
<li>F&uuml;gen Sie in Ihrem Web-Shop an den Artikeln in der neuen Kundengruppe die gew&uuml;nschten Preise ein.</li>
</ul>
<p>Auch der Rabatt-Modus der Kundengruppen kann genutzt werden. Sie können dort einen (prozentualen) Rabatt hinterlegen. Sofern der Rabatt-Modus im Shopware Artikel aktiviert ist, wird der rabattierte Preis per magnalister an den Marktplatz übermittelt. Wichtig dabei ist, dass der Marktplatz-Preis nicht als Streichpreis angezeigt wird.</p>',
            'hint' => '',
        ),
    ),
), true);
