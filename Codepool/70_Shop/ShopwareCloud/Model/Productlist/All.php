<?php
MLFilesystem::gi()->loadClass('ShopwareCloud_Model_ProductList_Abstract');
class ML_ShopwareCloud_Model_ProductList_All extends ML_ShopwareCloud_Model_ProductList_Abstract{
    protected function executeFilter() {
        return $this;
    }

    protected function executeList() {
        
    }

    public function getSelectionName() {
        
    }

}