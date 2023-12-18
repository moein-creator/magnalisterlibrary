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

MLI18n::gi()->add('amazon_config_orderimport', array(
     'field' => array(
        'orderimport.paymentmethod' => array(
            'label' => 'Zahlart der Bestellungen',
            'help' => '<p>Zahlart, die allen Amazon-Bestellungen beim Bestellimport zugeordnet wird. 
Standard: "Amazon"</p>
<p>
Alle weiteren verf&uuml;gbaren Zahlarten in der Liste k&ouml;nnen Sie ebenfalls unter Shopware > Einstellungen > Zahlungsarten definieren und hier&uuml;ber dann verwenden.</p>
<p>
Diese Einstellung ist wichtig f&uuml;r den Rechnungs- und Lieferscheindruck, und f&uuml;r die nachtr&auml;gliche Bearbeitung der Bestellung im Shop, sowie in Warenwirtschaften.</p>',
            'hint' => '',
        ),
        'orderimport.shippingmethod' => array(
            'label' => 'Versandart der Bestellungen',
            'help' => '<p>Amazon &uuml;bergibt beim Bestellimport keine Information der Versandart.</p>
<p>W&auml;hlen Sie daher bitte hier die verf&uuml;gbaren Web-Shop-Versandarten. Die Inhalte aus dem Drop-Down k&ouml;nnen Sie unter Shopware > Einstellungen > Versandkosten definieren.</p>
<p>Diese Einstellung ist wichtig f&uuml;r den Rechnungs- und Lieferscheindruck, und f&uuml;r die nachtr&auml;gliche Bearbeitung der Bestellung im Shop, sowie in Warenwirtschaften.</p>',
           'hint' => '',
        ),
        'orderimport.paymentstatus' => array(
            'label' => 'Zahlstatus im Shop',
            'help' => 'Wählen Sie hier, welcher Webshop-Zahlstatus während des magnalister Bestellimports in den Bestelldetails hinterlegt werden soll.',
            'hint' => '',
        ),
        'orderimport.fbashippingmethod' => array(
            'label' => 'Versandart der Bestellungen (FBA)',
            'help' => '<p>Amazon &uuml;bergibt beim Bestellimport keine Information der Versandart.</p>
<p>W&auml;hlen Sie daher bitte hier die verf&uuml;gbaren Web-Shop-Versandarten. Die Inhalte aus dem Drop-Down k&ouml;nnen Sie unter Shopware > Einstellungen > Versandkosten definieren.</p>
<p>Diese Einstellung ist wichtig f&uuml;r den Rechnungs- und Lieferscheindruck, und f&uuml;r die nachtr&auml;gliche Bearbeitung der Bestellung im Shop, sowie in Warenwirtschaften.</p>',
           'hint' => '',
        ),
        'orderimport.fbapaymentstatus' => array(
            'label' => 'Zahlstatus im Shop (FBA)',
            'help' => 'Wählen Sie hier, welcher Webshop-Zahlstatus während des magnalister Bestellimports in den Bestelldetails hinterlegt werden soll.',
            'hint' => '',
        ),
    ),
), false);
MLI18n::gi()->{'amazon_config_orderimport__field__customergroup__help'} = '{#i18n:global_config_orderimport_field_customergroup_help#}';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.carrier__help'} = '
Wählen Sie hier das Transportunternehmen, das den Amazon Bestellungen standardmäßig zugeordnet wird.<br>
<br>
Sie haben folgende Optionen:<br>
<ul>
	<li><span class="bold underline">Von Amazon vorgeschlagene Transportunternehmen</span></li>
</ul>
Wählen Sie ein Transportunternehmen aus der Dropdown-Liste. Es werden die Unternehmen angezeigt, die von Amazon empfohlen werden.<br>
<br>
Diese Option bietet sich an, wenn Sie für Amazon Bestellungen <strong>immer das gleiche Transportunternehmen nutzen</strong> möchten.<br>
<ul>
	<li><span class="bold underline">Transportunternehmen aus einem Webshop-Freitextfeld (Bestellungen) wählen</span></li>
</ul>
Freitextfelder können Sie in Ihrem Shopware 5 Backend unter “Einstellungen” -> “Freitextfeld-Verwaltung” anlegen (Tabelle: Bestellung) und unter “Kunden” -> “Bestellungen” befüllen. Öffnen Sie dazu die entsprechende Bestellung und scrollen Sie in der Bestellübersicht nach unten zu “Freitextfelder”.<br>
<br>
Diese Option bietet sich an, wenn Sie für Amazon Bestellungen <strong>unterschiedliche Transportunternehmen</strong> nutzen möchten.
<ul>
	<li><span class="bold underline">Von Amazon vorgeschlagene Transportunternehmen mit Versanddienstleistern aus Webshop Versandkosten-Modul matchen</span></li>
</ul>
Sie können die von Amazon empfohlenen Transportunternehmen mit den im Shopware 5 Versandkosten-Modul angelegten Dienstleistern matchen. Über das “+” Symbol können Sie mehrere Matchings vornehmen.<br>
<br>
Infos, welcher Eintrag aus dem Shopware Versandkosten-Modul beim Amazon Bestellimport verwendet wird, entnehmen Sie bitte dem Info Icon unter “Bestellimport” -> “Versandart der Bestellungen”.<br>
<br>
Diese Option bietet sich an, wenn Sie auf <strong>bestehende Versandkosten-Einstellungen</strong> aus dem <strong>Shopware 5</strong> Versandkosten-Modul zurückgreifen möchten.<br>
<ul>
	<li><span class="bold underline">Manuelle Eingabe eines Transportunternehmens für alle Bestellungen in ein magnalister Textfeld</span></li>
</ul>
Wenn Sie in magnalister unter “Transportunternehmen” die Option “Andere” wählen, können Sie im Textfeld rechts daneben manuell den Namen eines Transportunternehmens eingeben.<br>
<br>
Diese Option bietet sich an, wenn Sie <strong>für alle Amazon Bestellungen ein und dasselbe Transportunternehmen manuell hinterlegen</strong> möchten.<br>
<br>
<span class="bold underline">Wichtige Hinweise:</span>
<ul>
	<li>Die Angabe eines Transportunternehmens ist für Versandbestätigungen bei Amazon verpflichtend.<br><br></li>
	<li>Die Nicht-Übermittlung des Transportunternehmens kann zu einem vorübergehenden Entzug der Verkaufsberechtigung führen.</li>
</ul>
';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.shipmethod__help'} = '
Wählen Sie hier den Lieferservice ( = Versandart), der allen Amazon Bestellungen standardmäßig zugeordnet wird.<br>
<br>
Sie haben folgende Optionen:
<ul>
	<li><span class="bold underline">Lieferservice aus einem Webshop-Freitextfeld (Bestellungen) wählen</span></li>
</ul>
Wählen Sie einen Lieferservice aus einem Webshop Freitextfeld.<br>
<br>
Freitextfelder können Sie in Ihrem Shopware 5 Backend unter “Einstellungen” -> “Freitextfeld-Verwaltung” anlegen (Tabelle: Bestellung) und unter “Kunden” -> “Bestellungen” befüllen. Öffnen Sie dazu die entsprechende Bestellung und scrollen Sie in der Bestellübersicht nach unten zu “Freitextfelder”.<br>
<br>
Diese Option bietet sich an, wenn Sie für Amazon Bestellungen <strong>unterschiedliche Lieferservices</strong> nutzen möchten.<br>
<ul>
	<li><span class="bold underline">Lieferservice mit Einträgen aus Webshop Versandkosten-Modul matchen</span></li>
</ul>
Sie können einen beliebigen Lieferservice mit den im Shopware 5 Versandkosten-Modul angelegten Einträgen matchen. Über das “+” Symbol können Sie mehrere Matchings vornehmen.<br>
<br>
Infos, welcher Eintrag aus dem Shopware Versandkosten-Modul beim Amazon Bestellimport verwendet wird, entnehmen Sie bitte dem Info Icon unter “Bestellimport” -> “Versandart der Bestellungen”.<br>
<br>
Diese Option bietet sich an, wenn Sie auf <strong>bestehende Versandkosten-Einstellungen aus</strong> dem <strong>Shopware 5</strong> Versandkosten-Modul zurückgreifen möchten.<br>
<ul>
	<li><span class="bold underline">Manuelle Eingabe eines Lieferservices für alle Bestellungen in ein magnalister Textfeld</span></li>
</ul>
Wenn Sie diese Option in magnalister wählen, können Sie im Textfeld rechts daneben manuell den Namen eines Lieferservices eingeben.<br>
<br>
Diese Option bietet sich an, wenn Sie <strong>für alle Amazon Bestellungen ein und denselben Lieferservice manuell hinterlegen</strong> möchten.<br>
<br>
<span class="bold underline">Wichtige Hinweise:</span>
<ul>
	<li>Die Angabe eines Lieferservices ist für Versandbestätigungen bei Amazon verpflichtend.<br><br></li>
	<li>Die Nicht-Übermittlung des Lieferservices kann zu einem vorübergehenden Entzug der Verkaufsberechtigung führen.</li>
</ul>
';
