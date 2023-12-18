<?php

MLFilesystem::gi()->loadClass('Shopify_Model_ProductList_Abstract');

/**
 * select all products 
 * hitmeister-config: 
 *  - hitmeister.lang isset
 *  - amzon.prepare.ean !=''
 */
class ML_ShopifyPriceMinister_Model_ProductList_PriceMinister_Prepare_Apply extends ML_Shopify_Model_ProductList_Abstract {

    protected function executeFilter() {
        $this->oFilter
            ->registerDependency('searchfilter')
            ->limit()
            ->registerDependency('categoryfilter')
            ->registerDependency('preparestatusfilter')
            ->registerDependency('priceministerpreparetypefilter',array('PrepareType'=>'apply'))
            ->registerDependency('productstatusfilter')
            ->registerDependency('manufacturerfilter')
        ;
        return $this;
    }


    public function getSelectionName() {
        return 'apply';
    }

}
