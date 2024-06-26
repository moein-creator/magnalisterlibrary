<?php
/*
 * 888888ba                 dP  .88888.                    dP
 * 88    `8b                88 d8'   `88                   88
 * 88aaaa8P' .d8888b. .d888b88 88        .d8888b. .d8888b. 88  .dP  .d8888b.
 * 88   `8b. 88ooood8 88'  `88 88   YP88 88ooood8 88'  `"" 88888"   88'  `88
 * 88     88 88.  ... 88.  .88 Y8.   .88 88.  ... 88.  ... 88  `8b. 88.  .88
 * dP     dP `88888P' `88888P8  `88888'  `88888P' `88888P' dP   `YP `88888P'
 *
 *                          m a g n a l i s t e r
 *                                      boost your Online-Shop
 *
 * -----------------------------------------------------------------------------
 * (c) 2010 - 2020 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

MLFilesystem::gi()->loadClass('ShopwareCloud_Model_ProductList_UploadAbstract');
class ML_ShopwareCloudEbay_Model_ProductList_Ebay_Checkin extends ML_ShopwareCloud_Model_ProductList_UploadAbstract {
    
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