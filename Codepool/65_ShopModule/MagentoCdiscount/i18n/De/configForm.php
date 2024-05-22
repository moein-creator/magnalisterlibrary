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
        <span class="bold underline">Von Cdiscount vorgeschlagene Transportunternehmen mit Versanddienstleistern aus dem Webshop Versandkosten-Modul matchen</span>
        <p>Sie können die von Cdiscount empfohlenen Transportunternehmen mit den im Magento Versandkosten-Modul angelegten Dienstleistern matchen. Über das “+” Symbol können Sie mehrere Matchings vornehmen.<br>
            <br>
            Infos, welcher Eintrag aus dem Magento Versandkosten-Modul beim Cdiscount Bestellimport verwendet wird, entnehmen Sie bitte dem Info Icon unter “Bestellimport” -> “Versandart der Bestellungen”.<br>
            <br>
            Diese Option bietet sich an, wenn Sie auf <strong>bestehende Versandart-Einstellungen</strong> aus dem <strong>Magento</strong> Versandkosten-Modul zurückgreifen möchten.<br>
        </p>
    </li>
    <li>
        <span class="bold underline">magnalister fügt ein Freitextfeld in den Bestelldetails hinzu</span>
        <p>Wenn Sie diese Option wählen, fügt magnalister beim Bestellimport ein Feld in den Bestelldetails bei der Magento Bestellung hinzu. In dieses Feld können Sie das Transportunternehmen eintragen.<br>
            <br>
            Diese Option bietet sich an, wenn Sie für Cdiscount Bestellungen <strong>unterschiedliche Transportunternehmen</strong> nutzen möchten.<br>
        </p>
    </li>
    <li>
        <span class="bold underline">Manuelle Eingabe eines Transportunternehmens für alle Bestellungen in ein magnalister Textfeld</span><br>
        <p>Diese Option bietet sich an, wenn Sie <strong>für alle Cdiscount Bestellungen ein und dasselbe Transportunternehmen manuell hinterlegen</strong> möchten.<br></p>
    </li>
</ul>
<span class="bold underline">Wichtige Hinweise:</span>
<ul>
    <li>Die Angabe eines Transportunternehmens ist für Versandbestätigungen bei Cdiscount verpflichtend.<br><br></li>
    <li>Die Nicht-Übermittlung des Transportunternehmens kann zu einem vorübergehenden Entzug der Verkaufsberechtigung führen.</li>
</ul>
';
