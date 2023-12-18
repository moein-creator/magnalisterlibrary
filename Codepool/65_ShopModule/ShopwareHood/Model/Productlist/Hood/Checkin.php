<?php

MLFilesystem::gi()->loadClass('Shopware_Model_ProductList_UploadAbstract');
class ML_ShopwareHood_Model_ProductList_Hood_Checkin extends ML_Shopware_Model_ProductList_UploadAbstract {
    
    protected function executeFilter(){
         $this->oFilter
            ->registerDependency('searchfilter')
            ->limit()
            ->registerDependency('selectionstatusfilter', array('selectionname' => $this->getSelectionName()))
            ->registerDependency('categoryfilter')
            ->registerDependency('preparestatusfilter', array('blPrepareMode' => false))
            ->registerDependency('lastpreparedfilter', array('blPrepareMode' => false))
            ->registerDependency('marketplacesyncfilter', array('blPrepareMode' => false))
            ->registerDependency('productstatusfilter', array('blPrepareMode' => false))
            ->registerDependency('manufacturerfilter')
        ;
        return $this;
    }

}