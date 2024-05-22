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
 * (c) 2010 - 2024 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

MLI18n::gi()->add('ebay_config_orderimport', array(
    'field' => array(
        'orderimport.paymentstatus' => array(
            'label' => 'Zahlstatus im Shop',
            'help'  => '',
            'hint'  => '',
        ),
        'updateablepaymentstatus'   => array(
            'label' => 'Zahl-Status-&Auml;nderung zulassen wenn',
            'help'  => 'Status der Bestellungen, die bei eBay-Zahlungen ge&auml;ndert werden d&uuml;rfen.
			                Wenn die Bestellung einen anderen Status hat, wird er bei eBay-Zahlungen nicht ge&auml;ndert.<br /><br />
			                Wenn Sie gar keine &Auml;nderung des Zahlstatus bei eBay-Zahlung w&uuml;nschen, deaktivieren Sie die Checkbox.',
        ),
        'paidstatus'                => array(
            'label' => 'Bestell-/Zahlstatus für bezahlte eBay Bestellungen',
            'help'  => '<p>eBay-Bestellungen werden vom Käufer teils zeitverzögert bezahlt.
<br><br>
Damit Sie nicht-bezahlte Bestellungen von bezahlten Bestellungen trennen zu können, können Sie hier für bezahlte eBay-Bestellungen einen eigenen Webshop Bestellstatus, sowie einen Zahlstatus wählen.
<br><br>
Wenn Bestellungen von eBay importiert werden, die noch nicht bezahlt sind, so greift der Bestellstatus, den Sie oben unter "Bestellimport" > "Bestellstatus im Shop" festgelegt haben." 
<br><br>
Wenn Sie oben “Nur bezahlt-markierte Bestellungen importieren” aktiviert haben, wird ebenfalls der “Bestellstatus im Shop” von oben verwendet. Die Funktion hier ist in dem Fall dann ausgegraut.
'
        ),
        'orderstatus.paid'          => array(
            'label' => 'Bestellstatus',
            'help'  => '',
        ),
        'paymentstatus.paid'        => array(
            'label' => 'Zahlstatus',
            'help'  => '',
        ),
        'updateable.paymentstatus'  => array(
            'label' => '',
            'help'  => '',
        ),
        'update.paymentstatus'      => array(
            'label' => 'Status-&Auml;nderung aktiv',
        ),
    ),
), false);

MLI18n::gi()->{'ebay_config_orderimport__field__customergroup__help'} = '{#i18n:global_config_orderimport_field_customergroup_help#}';
MLI18n::gi()->set('ebay_prepare_apply_form__field__description__hint', 'Liste verf&uuml;gbarer Platzhalter f&uuml;r die Produktbeschreibung:'
    .'<dl>'
    .'<dt>#TITLE#</dt>'
    .'<dd>Produktname (Titel)</dd>'
    .'<dt>#ARTNR#</dt>'
    .'<dd>Artikelnummer</dd>'
    .'<dt>#PID#</dt>'
    .'<dd>Produkt-ID</dd>'
    .'<dt>#SHORTDESCRIPTION#</dt>'
    .'<dd>Kurzbeschreibung aus dem Shop</dd>'
    .'<dt>#DESCRIPTION#</dt>'
    .'<dd>Beschreibung aus dem Shop</dd>'
    .'<dt>#MOBILEDESCRIPTION#</dt>'
    .'<dd>Kurzbeschreibung f&uuml;r mobile Ger&auml;te, falls hinterlegt</dd><dt>#PICTURE1#</dt>'
    .'<dd>erstes Produktbild</dd>'
    .'<dt>#PICTURE2# etc.</dt>'
    .'<dd>zweites Produktbild, mit #PICTURE3#, #PICTURE4# usw. können weitere Bilder übermittelt werden, so viele wie im Shop vorhanden.</dd>'
    .' <dt>#TAGS#</dt>
            <dd>TAGS</dd>'
    .'</dl>'
    , true);
MLI18n::gi()->set('ebay_config_producttemplate__field__template.content__hint', MLI18n::gi()->{'ebay_prepare_apply_form__field__description__hint'}, true);
MLI18n::gi()->set('ebay_prepare_apply_form_field_description_hint_metafield', '<dt>#Namensraum und Schlüssel des Metafeldes#</dt><dd>z.B.</dd>', true);
MLI18n::gi()->{'orderstatus_carrier_default_send_order_carrier'} = 'Versanddienstleister von Bestellung &uuml;bergeben';


MLI18n::gi()->{'ebay_config_producttemplate__field__template.name__help'} = '<dl>
    <dt>Name des Produkts auf eBay</dt>
    <dd>Einstellung, wie das Produkt auf eBay hei&szlig;en soll.
        Der Platzhalter <b>#TITLE#</b> wird automatisch durch den Produktnamen aus dem Shop ersetzt.</dd></dl>';
MLI18n::gi()->{'ebay_config_producttemplate__field__template.name__hint'} = 'Platzhalter: #TITLE# - Produktname';
