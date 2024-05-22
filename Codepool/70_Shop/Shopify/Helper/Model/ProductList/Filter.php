<?php

use Shopify\API\Application\Application;
use Shopify\API\Application\Request\Products\CountProducts\CountProductsParams;
use Shopify\API\Application\Request\Products\ListOfProducts\ListOfProductsParams;

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
 * (c) 2010 - 2017 RedGecko GmbH -- http://www.redgecko.de/
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 *
 * Class ML_Shopify_Helper_Model_ProductList_Filter
 */
class ML_Shopify_Helper_Model_ProductList_Filter {
    protected $sPrefix = '';
    protected $iPage = 0;
    protected $iOffset = 0;
    protected $aOrder = array('name' => '`id`', 'direction' => 'DESC');

    /**
     *
     * @var ML_Database_Model_Query_Select $oSelect
     */
    protected $oSelect = null;
    protected $aFilterInput = array();
    protected $aFilterOutput = array();
    protected $aLimit;

    public function clear() {
        $oRef = new ReflectionClass($this);
        foreach ($oRef->getDefaultProperties() as $sKey => $mValue) {
            $this->$sKey = $mValue;
        }
        return $this;
    }

    public function getOutput() {
        return $this->aFilterOutput;

    }

    /**
     * @param ML_Database_Model_Query_Select $oSelect
     * @return $this
     */
    public function setCollection(ML_Database_Model_Query_Select $oSelect) {
        $this->oSelect = $oSelect;
        return $this;
    }

    public function setPrefix($sPrefix) {
        $this->sPrefix = $sPrefix;
        return $this;
    }

    protected function getDefaultValue($sName, $aPossibleValues) {
        $sValue = isset($this->aFilterInput[$sName]) ? $this->aFilterInput[$sName] : '';
        $sValue = array_key_exists($sValue, $aPossibleValues) ? $sValue : key($aPossibleValues);
        return $sValue;
    }

    /**
     * adds a ML_Productlist_Model_ProductListDependency_Abstract instance to filter
     *
     * @param string $sDependency ident-name of dependency
     * @param array $aDependencyConfig config for dependency
     *
     * @return $this
     */
    public function registerDependency($sDependency, $aDependencyConfig = array()) {
        $oDependency = MLProductList::dependencyInstance($sDependency)->setConfig($aDependencyConfig);
        $sName = $this->sPrefix.$sDependency;
        if (!isset($this->aFilterOutput[$sName])) {
            $oDependency
                ->setFilterValue(isset($this->aFilterInput[$sName]) ? $this->aFilterInput[$sName] : null)
                ->manipulateQuery($this->oSelect)
            ;
            $this->aFilterOutput[$sName] = $oDependency;
            $aIdentFilter = $oDependency->getMasterIdents();

            $sKey = MLDatabase::factory('config')->set('mpid',0)->set('mkey','general.keytype')->get('value');

            $sField = $sKey === 'pID' ? 'productsid' : 'productssku';
            // checks if specific products are required
            if ($aIdentFilter['in'] !== null) {
                $this->oSelect->where("`{$sField}` IN('".implode("', '", array_unique(MLDatabase::getDbInstance()->escape($aIdentFilter['in'])))."')");
            }

            if ($aIdentFilter['notIn'] !== null) {
                $this->oSelect->where("`{$sField}` NOT IN('".implode("', '", array_unique(MLDatabase::getDbInstance()->escape($aIdentFilter['notIn'])))."')");
            }
        }
        return $this;
    }

    public function limit() {
        $sName = $this->sPrefix.__function__;
        if (!isset($this->aFilterOutput[$sName])) {
            $aValues = array();
            foreach (array(5, 10, 25, 50, 75, 100) as $iKey) {
                $aValues[$iKey] = array(
                    'value' => (string)$iKey,
                    'label' => sprintf(MLI18n::gi()->get('Productlist_Filter_sLimit'), (string)$iKey)
                );
            }
            $iValue = (int)$this->getDefaultValue($sName, $aValues);
            if ($this->iPage === 0) {
                $iOffset = $this->iOffset;
            } else {
                $iOffset = $this->iPage * $iValue;
            }
            $this->aLimit = array($iValue, $iOffset);
            $this->oSelect->limit($iOffset, $iValue);
            $this->aFilterOutput[$sName] = array(
                'name' => $sName,
                'type' => 'select',
                'value' => $iValue,
                'values' => $aValues
            );
        }
        return $this;
    }

    /**
     * Returns array with pagination information.
     *
     * @return array
     */
    public function getStatistic() {
        $iCountTotal = (int)$this->oSelect->getCount();
        $iCountPerPage = isset($this->aFilterOutput[$this->sPrefix.'limit']['value']) ? $this->aFilterOutput[$this->sPrefix.'limit']['value'] : $iCountTotal;
        return array(
            'iCountPerPage' => $iCountPerPage,
            'iCurrentPage' => $this->iPage,
            'iCountTotal' => $iCountTotal,
            'aOrder' => array('name' => $this->aOrder['name'], 'direction' => $this->aOrder['direction']),
        );
    }

    public function setFilter($aFilterInput) {
        $this->aFilterInput = $aFilterInput ;
        return $this ;
    }

    public function setPage($iPage) {
        $this->iPage = (int)$iPage;
        return $this;
    }

    public function setOffset($iOffset) {
        $this->iOffset = (int)$iOffset;
        return $this;
    }

    public function setOrder($sOrder) {
        $aOrder = explode('_', $sOrder);
        if (is_array($aOrder) && count($aOrder) == 2 && !empty($aOrder[0])) {
            $this->aOrder = array('name' => $aOrder[0], 'direction' => $aOrder[1]);
            $this->oSelect->orderBy("{$aOrder[0]} {$aOrder[1]}");
        }
    }

    public function variantInList(ML_Shop_Model_Product_Abstract $oProduct) {
        foreach ($this->aFilterOutput as $oDependency) {
            if (is_object($oDependency) && !$oDependency->variantIsActive($oProduct)) {
                return false;
            }
        }
        return true;
    }

    public function getProductCountFromShopify() {
        return MLShopifyAlias::getProductHelper()->getProductListCount();
    }
}

