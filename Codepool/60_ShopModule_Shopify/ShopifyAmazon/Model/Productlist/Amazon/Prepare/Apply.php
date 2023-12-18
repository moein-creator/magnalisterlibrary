<?php

MLFilesystem::gi()->loadClass('Shopify_Model_ProductList_Abstract');

/**
 * select all products 
 * amazon-config: 
 *  - amazon.lang isset
 *  - amzon.prepare.ean !=''
 */
class ML_ShopifyAmazon_Model_ProductList_Amazon_Prepare_Apply extends ML_Shopify_Model_ProductList_Abstract {

    protected function executeFilter() {
        $this->oFilter
            ->registerDependency('searchfilter')
            ->limit()
            ->registerDependency('categoryfilter')
            ->registerDependency('preparestatusfilter')
            ->registerDependency('amazonpreparetypefilter',array('PrepareType'=>'apply'))
            ->registerDependency('productstatusfilter')
            ->registerDependency('manufacturerfilter')
        ;
        return $this;
    }


    public function getSelectionName() {
        return 'apply';
    }

}
