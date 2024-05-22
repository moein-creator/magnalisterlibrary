<?php
class ML_ZzzzDummy_Model_Language extends ML_Shop_Model_Language_Abstract {
    public function getCurrentIsoCode() {
        if (MLSetting::gi()->sTranslationLanguage) {
            return MLSetting::gi()->sTranslationLanguage;
        }

        return 'de';
    }

    public function getCurrentCharset() {
        return 'UTF-8';
    }
    
}