<?php
/**
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
 * $Id$
 *
 * (c) 2010 - 2015 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

MLI18n::gi()->add('ricardo_config_producttemplate', array(
    'field' => array(
        'template.content' => array(
            'label' => 'Template Produktbeschreibung',
            'hint' => '
                Liste verf&uuml;gbarer Platzhalter f&uuml;r die Produktbeschreibung:
                <dl>
                    <dt>#TITLE#</dt>
                        <dd>Produktname (Titel)</dd>
                    <dt>#VARIATIONDETAILS#</dt>
                            <dd>Da ricardo.ch keine Varianten unterstützt, übermittelt magnalister Varianten als einzelne Artikel zu ricardo.ch. 
                            Nutzen Sie diesen Platzhalter, um die Varianten-Details in Ihrer Artikelbeschreibung anzuzeigen</dd>
                    <dt>#ARTNR#</dt>
                        <dd>Artikelnummer im Shop</dd>
                    <dt>#PID#</dt>
                        <dd>Produkt ID im Shop</dd>
                    <!--<dt>#PRICE#</dt>
                            <dd>Preis</dd>
                    <dt>#VPE#</dt>
                            <dd>Preis pro Verpackungseinheit</dd>-->
                    <dt>#SHORTDESCRIPTION#</dt>
                        <dd>Kurzbeschreibung aus dem Shop</dd>
                    <dt>#DESCRIPTION#</dt>
                        <dd>Beschreibung aus dem Shop</dd>
                    <dt>#PICTURE1#</dt>
                        <dd>erstes Produktbild</dd>
                    <dt>#PICTURE2# usw.</dt>
                        <dd>zweites Produktbild; mit #PICTURE3#, #PICTURE4# usw. k&ouml;nnen weitere Bilder &uuml;bermittelt werden, so viele wie im Shop vorhanden.</dd>
                    <dt>Artikel-Freitextfelder:</dt>
                    <dt>#Bezeichnung1#&nbsp;#Freitextfeld1#</dt>
                    <dt>#Bezeichnung2#&nbsp;#Freitextfeld2#</dt>
                    <dt>#Bezeichnung..#&nbsp;#Freitextfeld..#</dt>
                        <dd>&Uuml;bernahme der Artikel-Freitextfelder:&nbsp;
                        Die Ziffer hinter dem Platzhalter (z.B. #Freitextfeld1#) entspricht der Position des Freitextfelds.
                        Siehe Einstellungen > Grundeinstellungen > Artikel > Artikel-Freitextfelder</dd>
                    <dt>#PROPERTIES#</dt>
                        <dd>Eine Liste aller Produkteigenschaften des Produktes. Aussehen kann &uuml;ber CSS gesteuert werden (siehe Code vom Standard Template)</dd>
                </dl>',
        ),
    ),
), false);
MLI18n::gi()->{'ricardo_config_orderimport__field__customergroup__help'} = '{#i18n:global_config_orderimport_field_customergroup_help#}';
MLI18n::gi()->add('ricardo_config_orderimport', array(
     'field' => array(
        'orderimport.paymentmethod' => array(
            'label' => 'Zahlart der Bestellungen',
            'help' => '<p>Zahlart, die allen Ricardo-Bestellungen beim Bestellimport zugeordnet wird. 
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
            'help' => '<p>Versandart, die allen Ricardo-Bestellungen beim Bestellimport zugeordnet wird. </p>
<p>Alle Versandarten in der Liste k&ouml;nnen Sie ebenfalls unter Shopware > Einstellungen > Versandkosten definieren und hier&uuml;ber dann verwenden.</p>
<p>Diese Einstellung ist wichtig f&uuml;r den Rechnungs- und Lieferscheindruck, und f&uuml;r die nachtr&auml;gliche Bearbeitung der Bestellung im Shop, sowie in Warenwirtschaften.</p>',
            'hint' => '',
        ),
        'orderimport.paymentstatus' => array(
            'label' => 'Zahlstatus im Shop',
            'hint' => '',
        ),
    ),
), false);
