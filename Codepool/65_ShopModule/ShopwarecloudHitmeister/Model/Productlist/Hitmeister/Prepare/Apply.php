<?php

MLFilesystem::gi()->loadClass('ShopwareCloud_Model_ProductList_Abstract');

/**
 * select all products 
 * hitmeister-config: 
 *  - hitmeister.lang isset
 *  - amzon.prepare.ean !=''
 */
class ML_ShopwareCloudHitmeister_Model_ProductList_Hitmeister_Prepare_Apply extends ML_ShopwareCloud_Model_ProductList_Abstract {

    protected function executeFilter() {
        $this->oFilter
            ->registerDependency('searchfilter')
            ->limit()
            ->registerDependency('categoryfilter')
            ->registerDependency('preparestatusfilter')
            ->registerDependency('hitmeisterpreparetypefilter',array('PrepareType'=>'apply'))
            ->registerDependency('productstatusfilter')
            ->registerDependency('manufacturerfilter')
        ;
        return $this;
    }
    
    public function getSelectionName() {
        return 'apply';
    }

}
