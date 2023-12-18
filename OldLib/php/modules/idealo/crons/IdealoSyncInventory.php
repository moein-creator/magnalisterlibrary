<?php

if (!defined('_ML_INSTALLED'))
    throw new Exception('Direct Access to this location is not allowed.');


require_once(DIR_MAGNALISTER_MODULES . 'magnacompatible/crons/MagnaCompatibleSyncInventory.php');

class IdealoSyncInventory extends MagnaCompatibleSyncInventory {

    protected function getBaseRequest() {
        return array(
            'SEARCHENGINE' => MLModul::gi()->getMarketPlaceName(),
            'SUBSYSTEM' => 'ComparisonShopping');
    }


    protected function getPriceObject() {
        return MLModul::gi()->getPriceObject();
    }

    protected function getStockConfig() {
        return MLModul::gi()->getStockConfig();
    }

}
