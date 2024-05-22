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

MLI18n::gi()->add('cdiscount_config_orderimport', array(
    'field' => array(
        'orderimport.paymentmethod' => array(
            'label' => 'Zahlart der Bestellungen',
            'help' => '<p>Zahlart, die allen Cdiscount-Bestellungen beim Bestellimport zugeordnet wird. 
Standard: "Cdiscount"</p>
<p>
Alle weiteren verf&uuml;gbaren Zahlarten in der Liste k&ouml;nnen Sie ebenfalls unter Shopware > Einstellungen > Zahlungsarten definieren und hier&uuml;ber dann verwenden.</p>
<p>
Diese Einstellung ist wichtig f&uuml;r den Rechnungs- und Lieferscheindruck, und f&uuml;r die nachtr&auml;gliche Bearbeitung der Bestellung im Shop, sowie in Warenwirtschaften.</p>',
            'hint' => '',
        ),
        'orderimport.shippingmethod' => array(
            'label' => 'Versandart der Bestellungen',
            'help' => '<p>Cdiscount &uuml;bergibt beim Bestellimport keine Information der Versandart.</p>
<p>W&auml;hlen Sie daher bitte hier die verf&uuml;gbaren Web-Shop-Versandarten. Die Inhalte aus dem Drop-Down k&ouml;nnen Sie unter Shopware > Einstellungen > Versandkosten definieren.</p>
<p>Diese Einstellung ist wichtig f&uuml;r den Rechnungs- und Lieferscheindruck, und f&uuml;r die nachtr&auml;gliche Bearbeitung der Bestellung im Shop, sowie in Warenwirtschaften.</p>',
            'hint' => '',
        ),
        'orderimport.paymentstatus' => array(
            'label' => 'Zahlstatus im Shop',
            'help' => 'Wählen Sie hier, welcher Webshop-Zahlstatus während des magnalister Bestellimports in den Bestelldetails hinterlegt werden soll.',
            'hint' => '',
        ),
    ),
), false);

MLI18n::gi()->{'cdiscount_config_orderimport__field__customergroup__help'} = '{#i18n:global_config_orderimport_field_customergroup_help#}';
MLI18n::gi()->sCdiscount_automatically = '-- Automatisch zuordnen --';
MLI18n::gi()->sCdiscount_match_marketplace = 'Vom Marktplatz übernehmen';
MLI18n::gi()->{'cdiscount_config_orderimport__field__orderstatus.carrier__help'} = '
Wählen Sie hier das Transportunternehmen, das den Cdiscount Bestellungen standardmäßig zugeordnet wird.<br>
<br>
Sie haben folgende Optionen:<br>
<ul>
    <li>
        <span class="bold underline">Von Cdiscount vorgeschlagene Transportunternehmen</span>
        <p>Wählen Sie ein Transportunternehmen aus der Dropdown-Liste. Es werden die Unternehmen angezeigt, die von Cdiscount empfohlen werden.<br>
            <br>
            Diese Option bietet sich an, wenn Sie für Cdiscount Bestellungen <strong>immer das gleiche Transportunternehmen nutzen</strong> möchten.
        </p>
    </li>
    <li>
        <span class="bold underline">{#i18n:cdiscount_config_carrier_option_group_shopfreetextfield_option_carrier#}</span>
        <p>{#i18n:shop_order_attribute_creation_instruction#}<br>
            <br>
            Diese Option bietet sich an, wenn Sie für Cdiscount Bestellungen <strong>unterschiedliche Transportunternehmen</strong> nutzen möchten.
        </p>
    </li>
    <li>
        <span class="bold underline">Von Cdiscount vorgeschlagene Transportunternehmen mit Versanddienstleistern aus Webshop Versandkosten-Modul matchen</span>
        <p>Sie können die von Cdiscount empfohlenen Transportunternehmen mit den im Shopware 5 Versandkosten-Modul angelegten Dienstleistern matchen. Über das “+” Symbol können Sie mehrere Matchings vornehmen.<br>
            <br>
            Infos, welcher Eintrag aus dem Shopware Versandkosten-Modul beim Cdiscount Bestellimport verwendet wird, entnehmen Sie bitte dem Info Icon unter “Bestellimport” -> “Versandart der Bestellungen”.<br>
            <br>
            Diese Option bietet sich an, wenn Sie auf <strong>bestehende Versandkosten-Einstellungen</strong> aus dem <strong>Shopware 5</strong> Versandkosten-Modul zurückgreifen möchten.<br>
        </p>
    </li>
    <li>
        <span class="bold underline">Transportunternehmen pauschal aus Textfeld übernehmen</span><br>
        <p>Diese Option bietet sich an, wenn Sie <strong>für alle Cdiscount Bestellungen ein und dasselbe Transportunternehmen manuell hinterlegen</strong> möchten.<br></p>
    </li>
</ul>
<span class="bold underline">Wichtige Hinweise:</span>
<ul>
    <li>Die Angabe eines Transportunternehmens ist für Versandbestätigungen bei Cdiscount verpflichtend.<br><br></li>
    <li>Die Nicht-Übermittlung des Transportunternehmens kann zu einem vorübergehenden Entzug der Verkaufsberechtigung führen.</li>
</ul>
';
MLI18n::gi()->{'cdiscount_config_price__field__priceoptions__help'} = '<p>Mit dieser Funktion k&ouml;nnen Sie abweichende Preise zu {#setting:currentMarketplaceName#} &uuml;bergeben und automatisch synchronisieren lassen.</p>
<p>Wählen Sie dazu über das nebenstehende Dropdown eine Kundengruppe aus Ihrem Webshop. </p>
<p>Wenn Sie keinen Preis in der neuen Kundengruppe eintragen, wird automatisch der Standard-Preis aus dem Webshop verwendet. Somit ist es sehr einfach, auch für nur wenige Artikel einen abweichenden Preis zu hinterlegen. Die übrigen Konfigurationen zum Preis finden ebenfalls Anwendung.</p>
<p><b>Anwendungsbeispiel:</b></p>
<ul>
<li>Hinterlegen Sie in Ihrem Web-Shop eine Kundengruppe z.B. "{#setting:currentMarketplaceName#}-Kunden"</li>
<li>F&uuml;gen Sie in Ihrem Web-Shop an den Artikeln in der neuen Kundengruppe die gew&uuml;nschten Preise ein.</li>
</ul>
<p>Auch der Rabatt-Modus der Kundengruppen kann genutzt werden. Sie können dort einen (prozentualen) Rabatt hinterlegen. Sofern der Rabatt-Modus im Shopware Artikel aktiviert ist, wird der rabattierte Preis per magnalister an den Marktplatz übermittelt. Wichtig dabei ist, dass der Marktplatz-Preis nicht als Streichpreis angezeigt wird.</p>';
