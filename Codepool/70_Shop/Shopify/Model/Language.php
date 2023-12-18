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
 * (c) 2010 - 2017 RedGecko GmbH -- http://www.redgecko.de/
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 *
 * Class ML_Shopify_Model_Language
 *
 */
class ML_Shopify_Model_Language extends ML_Shop_Model_Language_Abstract
{

    const LANGUAGE_DEFAULT_CHARSET  = 'UTF-8';

    /**
     * Returns current iso code.
     *
     * @return string
     */
    public function getCurrentIsoCode()
    {
        if (MLSetting::gi()->blTranslateInline && MLSetting::gi()->sTranslationLanguage) {
            return MLSetting::gi()->sTranslationLanguage;
        }

        $current_lang = 'en';
        $lang_variable = isset ($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : $current_lang;
        $allowed_languages = MLI18n::getPossibleLanguages();
        // Den Header auftrennen
        $accepted_languages = preg_split('/,\s*/', $lang_variable);
        // Die Standardwerte einstellen
        $current_q = 0;
        // Nun alle mitgegebenen Sprachen abarbeiten
        foreach ($accepted_languages as $accepted_language) {
            // Alle Infos ueber diese Sprache rausholen
            $res = preg_match ('/^([a-z]{1,8}(?:-[a-z]{1,8})*)'.
                '(?:;\s*q=(0(?:\.[0-9]{1,3})?|1(?:\.0{1,3})?))?$/i', $accepted_language, $matches);

            // war die Syntax gueltig?
            if (!$res) {
                // Nein? Dann ignorieren
                continue;
            }

            // Sprachcode holen und dann sofort in die Einzelteile trennen
            $lang_code = explode ('-', $matches[1]);

            // Wurde eine Qualitaet mitgegeben?
            if (isset($matches[2])) {
                // die Qualitaet benutzen
                $lang_quality = (float)$matches[2];
            } else {
                // Kompabilitaetsmodus: Qualitaet 1 annehmen
                $lang_quality = 1.0;
            }
            // Bis der Sprachcode leer ist...
            while (count ($lang_code)) {
                // mal sehen, ob der Sprachcode angeboten wird
                if (in_array (strtolower (join ('-', $lang_code)), $allowed_languages)) {
                    // Qualitaet anschauen
                    if ($lang_quality > $current_q) {
                        // diese Sprache verwenden
                        $current_lang = strtolower (join ('-', $lang_code));
                        $current_q = $lang_quality;
                        // Hier die innere while-Schleife verlassen
                        break;
                    }
                }
                // den rechtesten Teil des Sprachcodes abschneiden
                array_pop ($lang_code);
            }
        }
        // die gefundene Sprache zurueckgeben
        return $current_lang;
    }

    /**
     * Returns current and charset.
     */
    public function getCurrentCharset()
    {
        return self::LANGUAGE_DEFAULT_CHARSET;
    }

}