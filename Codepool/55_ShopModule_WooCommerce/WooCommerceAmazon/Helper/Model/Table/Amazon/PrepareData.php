<?php

MLFilesystem::gi()->loadClass('Amazon_Helper_Model_Table_Amazon_PrepareData');

class ML_WooCommerceAmazon_Helper_Model_Table_Amazon_PrepareData extends ML_Amazon_Helper_Model_Table_Amazon_PrepareData {

    /**
     * @param $aField
     */
    public function keywordsField(&$aField) {
        parent::keywordsField($aField);

        // Replace WooCommerce MetaKeywords separated by comma with spaces for amazon Generic Keywords
        $aField['value'] = str_replace(',', ' ', $aField['value']);
    }
}