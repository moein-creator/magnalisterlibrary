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
/**
 * @see formfiled.php
 */
MLI18n::gi()->add('formfields_metro', array(
    'prepare_title' => array(
        'label' => 'Titel<span class="bull">•</span>',
        'hint' => 'Maximal 150 Zeichen',
        'optional' => array(
            'checkbox' => array(
                'labelNegativ' => 'immer aktuell aus Web-Shop verwenden',
            ),
        )
    ),
    'prepare_description' => array(
        'label' => 'Beschreibung<span class="bull">•</span>',
        'hint' => 'Detaillierte und informative Beschreibung des Produkts mit seinen Spezifikationen und Eigenschaften. Angebotsdetails, Versand- oder Shopinformationen wie Preise, Lieferbedingungen, etc. sind nicht erlaubt. Bitte beachten Sie, dass es nur eine Produktdetailseite pro Produkt gibt, die von allen Verkäufern, die dieses Produkt anbieten, geteilt wird. Fügen Sie keine Hyperlinks, Bilder oder Videos hinzu.<br><br>Folgende HTML-Tags sind erlaubt: P, B, BR, A, UL, OL, LI, SPAN<br><br>Maximal 4000 Zeichen',
        'optional' => array(
            'checkbox' => array(
                'labelNegativ' => 'immer aktuell aus Web-Shop verwenden',
            ),
        )
    ),
    'prepare_shortdescription' => array(
        'label' => 'Kurzbeschreibung',
        'hint' => 'Kurze Beschreibung des Produkts mit einer Zusammenfassung der wichtigsten Produkteigenschaften.<br><br>Maximal 150 Zeichen',
        'optional' => array(
            'checkbox' => array(
                'labelNegativ' => 'immer aktuell aus Web-Shop verwenden',
            ),
        )
    ),
    'prepare_image' => array(
        'label' => 'Produktbilder<span class="bull">•</span>',
        'hint' => 'Maximal 10 Produktbilder',
        'optional' => array(
            'checkbox' => array(
                'labelNegativ' => 'immer aktuell aus Web-Shop verwenden',
            ),
        )
    ),
    'prepare_gtin' => array(
        'label' => 'GTIN (Global Trade Item Number)<span class="bull">•</span>',
        'hint' => 'Zum Beispiel: EAN, ISBN, ...<br><br>Maximal 14 Zeichen',
        'optional' => array(
            'checkbox' => array(
                'labelNegativ' => 'immer aktuell aus Web-Shop verwenden',
            ),
        )
    ),
    'prepare_manufacturer' => array(
        'label' => 'Hersteller',
        'hint' => 'Maximal 100 Zeichen',
        'optional' => array(
            'checkbox' => array(
                'labelNegativ' => 'immer aktuell aus Web-Shop verwenden',
            ),
        )
    ),
    'prepare_manufacturerpartnumber' => array(
        'label' => 'Herstellerartikelnummer',
        'hint' => 'Maximal 100 Zeichen',
        'optional' => array(
            'checkbox' => array(
                'labelNegativ' => 'immer aktuell aus Web-Shop verwenden',
            ),
        )
    ),
    'prepare_brand' => array(
        'label' => 'Marke',
        'hint' => 'Maximal 100 Zeichen',
        'optional' => array(
            'checkbox' => array(
                'labelNegativ' => 'immer aktuell aus Web-Shop verwenden',
            ),
        )
    ),
    'prepare_feature' => array(
        'label' => 'Wichtige Merkmale',
        'hint' => 'Maximal 200 Zeichen je Merkmal',
        'optional' => array(
            'checkbox' => array(
                'labelNegativ' => 'immer aktuell aus Web-Shop verwenden',
            ),
        )
    ),
    'prepare_msrp' => array(
        'label' => 'Unverbindliche Preisempfehlung des Herstellers',
        'hint' => '',
    ),
    'prepare_saveaction' => array(
        'name' => 'saveaction',
        'type' => 'submit',
        'value' => 'save',
        'position' => 'right',
    ),
    'prepare_resetaction' => array(
        'name' => 'resetaction',
        'type' => 'submit',
        'value' => 'reset',
        'position' => 'left',
    ),
    'processingtime' => array(
        'label' => 'Min. Lieferzeit in Werktagen',
        'help' => 'Tragen Sie hier ein, wie viele Werktage mindestens vom Zeitpunkt der Bestellung durch den Kunden es bis zum Erhalt des Pakets dauert',
    ),
    'maxprocessingtime' => array(
        'label' => 'Max. Lieferzeit in Werktagen',
        'help' => 'Tragen Sie hier ein, wie viele Werktage maximal vom Zeitpunkt der Bestellung durch den Kunden es bis zum Erhalt des Pakets dauert',
    ),
    'freightforwarding' => array(
        'label' => 'Lieferung per Spedition',
        'hint' => 'Geben Sie an, ob Ihr Produkt per Spedition versendet wird.',
    ),
    'businessmodel' => array(
        'label' => 'Käufergruppe festlegen',
        'hint' => '',
    ),
    'shippingprofile' => array(
        'label' => 'Versandkosten-Profile',
        'hint' => '',
    ),
    'orderstatus.carrier' => array(
        'label' => '&nbsp;&nbsp;&nbsp;&nbsp;Spediteur',
        'help' => 'Vorausgew&auml;hlter Spediteur beim Best&auml;tigen des Versandes nach METRO.',
    ),
    'orderstatus.cancellationreason' => array (
        'label' => 'Bestellung stornieren - Grund',
        'hint' => 'Um eine Bestellung auf METRO zu stornieren muss ein Grund angebeben werden',
    ),
));

MLI18n::gi()->add('metro_prepare_form', array(
    'field' => array(
        'variationgroups' => array(
            'label' => 'Marktplatz-Kategorie<span class="bull">•</span>',
            'hint' => '',
        ),
        'variationgroups.value' => array(
            'label' => 'Marktplatz-Kategorie:',
        ),
    ),
), false);
