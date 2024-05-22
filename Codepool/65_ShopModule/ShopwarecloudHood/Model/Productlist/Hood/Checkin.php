<?php

MLFilesystem::gi()->loadClass('ShopwareCloud_Model_ProductList_UploadAbstract');
class ML_ShopwarecloudHood_Model_ProductList_Hood_Checkin extends ML_ShopwareCloud_Model_ProductList_UploadAbstract {
    
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