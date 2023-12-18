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
abstract class ML_WooCommerce_Model_ProductList_Abstract extends ML_Productlist_Model_ProductList_ShopAbstract {
    protected $sPrefix = 'ml_';
    /**
     * filter
     * @var ML_WooCommerce_Helper_Model_ProductList_Filter $oFilter
     */
    protected $oFilter = null;
    /**
     * list/result
     * @var ML_WooCommerce_Helper_Model_ProductList_List $oList
     */
    protected $oList = null;
    protected $sOrder = '';
    /**
     * @var ML_Database_Model_Query_Select $oSelectQuery
     */
    protected $oSelectQuery = null;

    public function __construct() {
        /* @var $oSelectquery ML_Database_Model_Query_Select */
        $oSelectquery = MLHelper::gi("model_product")->getProductSelectQuery();
        // marketplace language
        try {
            $aConfig = MLModul::gi()->getConfig();
        } catch (Exception $oExc) {

        }

        $this->oSelectQuery = $oSelectquery;
        $this->oFilter = MLHelper::gi('model_productlist_filter')
                                  ->clear()
                                  ->setCollection($oSelectquery)
                                  ->setPrefix($this->sPrefix);
        $this->initList();
        $this->oList
            ->clear()
            ->setCollection($oSelectquery);
    }

    protected function initList() {
        $this->oList = MLHelper::gi('model_productlist_list');
    }

    public function setFilters($aFilter) {
        if (is_array($aFilter)) {
            $this->oFilter
                ->setFilter($aFilter)
                ->setPage(isset($aFilter['meta']['page']) ? $aFilter['meta']['page'] : 0)
                ->setOffset(isset($aFilter['meta']['offset']) ? $aFilter['meta']['offset'] : 0)
                ->setOrder(isset($aFilter['meta']['order']) ? $aFilter['meta']['order'] : 'p.id_DESC');
        } else {
            $this->oFilter->setOrder('p.id_DESC');
        }
        $this->sOrder = isset($aFilter['meta']['order']) ? $aFilter['meta']['order'] : '';
        $this->executeList();
        $this->executeFilter();

        return $this;
    }

    public function getFilters() {
        return $this->oFilter->getOutput();
    }

    public function getStatistic() {
        return $this->oFilter->getStatistic();
    }

    public function getMasterIds($blPage = false) {
        $aMainIds = array();
        if ($blPage) {
            $aMainIds = $this->oList->getLoadedList();
        } else {
            $aIdArrays = $this->oSelectQuery->getAll();
            foreach ($aIdArrays as $aItem) {
                $aMainIds[] = current($aItem);
            }
        }
        $aIds = array();//array_unique($aMainIds);
        foreach ($aMainIds as $sId) {
            $oProduct = new WC_Product((int)$sId);
            $aIds[] = MLProduct::factory()->loadByShopProduct($oProduct)->get('id');
        }

        return $aIds;
    }

    abstract protected function executeFilter();

    /*abstract protected function executeList();*/
    public function getHead() {

        return $this->oList->getHeader();
    }

    public function getList() {

        return new ArrayIterator($this->oList->getList());
    }

    public function additionalRows(ML_Shop_Model_Product_Abstract $oProduct) {

        return array();
    }

    public function getMixedData(ML_Shop_Model_Product_Abstract $oProduct, $sKey) {

        return $this->oList->getMixedData($oProduct, $sKey);
    }

    public function variantInList(ML_Shop_Model_Product_Abstract $oProduct) {

        return $this->oFilter->variantInList($oProduct);
    }

    public function setLimit($iFrom, $iCount) {
        $this->oSelectQuery->limit($iFrom, $iCount);

        return $this;
    }
}