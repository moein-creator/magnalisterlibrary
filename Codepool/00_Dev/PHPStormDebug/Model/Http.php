<?php
/**
 * This class is useful only for Shopware,
 * for other shop-system we need only renaming parent class, but it isn't needed to change function
 */

MLFilesystem::gi()->loadClass('Shopware_Model_Http');
class ML_PHPStormDebug_Model_Http extends ML_Shopware_Model_Http {

    public function getNeededFormFields() {
        $aNeededFormFields = parent::getNeededFormFields();
        $aNeededFormFields['XDEBUG_SESSION_START'] = 'PHPSTORM';
        return $aNeededFormFields;
    }

}
