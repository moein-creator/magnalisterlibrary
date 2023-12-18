<?php

MLFilesystem::gi()->loadClass('Magento_Model_ProductList_Abstract');

class ML_MagentoEtsy_Model_ProductList_Etsy_Prepare_Apply extends ML_Magento_Model_ProductList_Abstract {

	protected function executeFilter() {
        $this->oFilter
            ->registerDependency('magentonovariantsfilter')
            ->registerDependency('searchfilter')
            ->limit()
            ->registerDependency('preparestatusfilter')
            ->registerDependency('magentoattributesetfilter')
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
