<?php

MLFilesystem::gi()->loadClass('Magento_Model_ProductList_Abstract');

/**
 * select all products 
 * hitmeister-config: 
 *  - cdiscount.lang isset
 *  - amzon.prepare.ean !=''
 */
class ML_MagentoCdiscount_Model_ProductList_Cdiscount_Prepare_Apply extends ML_Magento_Model_ProductList_Abstract {

    protected function executeFilter() {
        $this->oFilter
            ->registerDependency('magentonovariantsfilter')
            ->registerDependency('searchfilter')
            ->limit()
            ->registerDependency('categoryfilter')
            ->registerDependency('preparestatusfilter')
            ->registerDependency('magentoattributesetfilter')    
            ->registerDependency('Cdiscountpreparetypefilter',array('PrepareType'=>'apply'))
            ->registerDependency('productstatusfilter')
            ->registerDependency('manufacturerfilter')
            ->registerDependency('magentoproducttypefilter')
            ->registerDependency('magentosaleablefilter')
        ;
        return $this;
    }

    public function getSelectionName() {
        return 'apply';
    }

}
