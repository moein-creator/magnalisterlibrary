<?php
/**
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
 * $Id$
 *
 * (c) 2010 - 2017 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */
MLFilesystem::gi()->loadClass('WooCommerce_Model_ProductList_Abstract');

abstract class ML_WooCommerce_Model_ProductList_UploadAbstract extends ML_WooCommerce_Model_ProductList_Abstract {
    protected $iFrom = 0;
    protected $iCount = 5;
    protected $aList = null;
    protected $blAdditemMode = false;

    public function setAdditemMode($blMode) {
        $this->blAdditemMode = $blMode;

        return $this;
    }

    public function getList() {
        if ($this->blAdditemMode) {
            if ($this->aList === null) {
                $aData = $this->getAdditemListData($this->iFrom, $this->iCount);
                $this->aList = $aData['List'];
                $this->iCountTotal = $aData['CountTotal'];
            }
            $aList = new ArrayIterator($this->aList);
        } else {
            $aList = parent::getList();
        }

        return $aList;
    }

    public function setLimit($iFrom, $iCount) {
        if ($this->blAdditemMode) {
            $this->iFrom = (int)$iFrom;
            $this->iCount = ((int)$iCount > 0) ? ((int)$iCount) : 5;

            return $this;
        } else {

            return parent::setLimit($iFrom, $iCount);
        }
    }

    public function getStatistic() {
        if ($this->blAdditemMode) {
            $this->getList();
            $aOut = array(
                'iCountPerPage' => $this->iCount,
                'iCurrentPage' => $this->iFrom / $this->iCount,
                'iCountTotal' => $this->iCountTotal,
                'aOrder'=> array(
                    'name' => '',
                    'direction' => ''
                )
            );

            return $aOut;
        } else {

            return parent::getStatistic();
        }
    }

    public function getMasterIds($blPage = false) {
        if ($this->blAdditemMode) {
            $this->getList();

            return array_keys($this->aList);
        } else {

            return parent::getMasterIds($blPage);
        }
    }

    protected function executeFilter() {
        $this->oFilter
            ->registerDependency('searchfilter')
            ->limit()
            ->registerDependency('selectionstatusfilter', array('selectionname' => $this->getSelectionName()))
            ->registerDependency('categoryfilter')
            ->registerDependency('preparestatusfilter', array('blPrepareMode' => false))
            ->registerDependency('lastpreparedfilter')
            ->registerDependency('productstatusfilter', array('blPrepareMode' => false))
            ->registerDependency('manufacturerfilter')
            ->checkNoSku();

        //var_dump($this->oFilter);die;
        return $this;
    }

    public function getSelectionName() {
        return 'checkin';
    }

    public function variantInList(ML_Shop_Model_Product_Abstract $oProduct) {
        if ($this->blAdditemMode) {

            return MLDatabase::factory( 'selection' )->loadByProduct( $oProduct,
                    'checkin' )->get( 'expires' ) !== null;
        }

        return parent::variantInList($oProduct);
    }
}