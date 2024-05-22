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

MLI18n::gi()->{'otto_config_orderimport__field__customergroup__help'} = '{#i18n:global_config_orderimport_field_customergroup_help#}';
MLI18n::gi()->add('formfields', array(
    'orderimport.paymentmethod' => array(
        'label' => 'Zahlart der Bestellungen',
        'help' => '
            <p>Zahlart, die allen OTTO-Bestellungen beim Bestellimport zugeordnet wird.
            Standard: "OTTO"</p>
            <p>Alle weiteren verf&uuml;gbaren Zahlarten in der Liste k&ouml;nnen Sie ebenfalls unter
                Shopware > Einstellungen > Zahlungsarten definieren und hier&uuml;ber dann verwenden.</p>
            <p>Diese Einstellung ist wichtig f&uuml;r den Rechnungs- und Lieferscheindruck, und f&uuml;r die nachtr&auml;gliche Bearbeitung der Bestellung im Shop, sowie in Warenwirtschaften.</p>
        ',
        'hint' => '',
    ),
    'orderimport.shippingmethod' => array(
        'label' => 'Versandart der Bestellungen',
        'help' => '<p>Otto &uuml;bergibt beim Bestellimport keine Information der Versandart.</p>
<p>W&auml;hlen Sie daher bitte hier die verf&uuml;gbaren Web-Shop-Versandarten. Die Inhalte aus dem Drop-Down k&ouml;nnen Sie unter Shopware > Einstellungen > Versandkosten definieren.</p>
<p>Diese Einstellung ist wichtig f&uuml;r den Rechnungs- und Lieferscheindruck, und f&uuml;r die nachtr&auml;gliche Bearbeitung der Bestellung im Shop, sowie in Warenwirtschaften.</p>',
        'hint' => '',
    ),
    'orderimport.paymentstatus' => array(
        'label' => 'Zahlstatus im Shop',
        'help' => '<p>Otto &uuml;bergibt beim Bestellimport keine Information der Versandart.</p>
<p>W&auml;hlen Sie daher bitte hier die verf&uuml;gbaren Web-Shop-Versandarten. Die Inhalte aus dem Drop-Down k&ouml;nnen Sie unter Shopware > Einstellungen > Versandkosten definieren.</p>
<p>Diese Einstellung ist wichtig f&uuml;r den Rechnungs- und Lieferscheindruck, und f&uuml;r die nachtr&auml;gliche Bearbeitung der Bestellung im Shop, sowie in Warenwirtschaften.</p>',
        'hint' => '',
    ),
), true);
MLI18n::gi()->{'sOtto_automatically'} = '-- allocate automatically --';
MLI18n::gi()->{'otto_config_free_text_attributes_opt_group'} = 'Freitextfelder';
MLI18n::gi()->{'formfields__priceoptions__help'} = '<p>Mit dieser Funktion k&ouml;nnen Sie abweichende Preise zu {#setting:currentMarketplaceName#} &uuml;bergeben und automatisch synchronisieren lassen.</p>
<p>Wählen Sie dazu über das nebenstehende Dropdown eine Kundengruppe aus Ihrem Webshop. </p>
<p>Wenn Sie keinen Preis in der neuen Kundengruppe eintragen, wird automatisch der Standard-Preis aus dem Webshop verwendet. Somit ist es sehr einfach, auch für nur wenige Artikel einen abweichenden Preis zu hinterlegen. Die übrigen Konfigurationen zum Preis finden ebenfalls Anwendung.</p>      
<p><b>Anwendungsbeispiel:</b></p>
<ul>        
<li>Hinterlegen Sie in Ihrem Web-Shop eine Kundengruppe z.B. "{#setting:currentMarketplaceName#}-Kunden"</li>
<li>F&uuml;gen Sie in Ihrem Web-Shop an den Artikeln in der neuen Kundengruppe die gew&uuml;nschten Preise ein.</li>
</ul>       
<p>Auch der Rabatt-Modus der Kundengruppen kann genutzt werden. Sie können dort einen (prozentualen) Rabatt hinterlegen. Sofern der Rabatt-Modus im Shopware Artikel aktiviert ist, wird der rabattierte Preis per magnalister an den Marktplatz übermittelt. Wichtig dabei ist, dass der Marktplatz-Preis nicht als Streichpreis angezeigt wird.</p>';
