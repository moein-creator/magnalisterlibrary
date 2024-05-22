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
 * (c) 2010 - 2023 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */
MLFilesystem::gi()->loadClass('Shop_Model_ProductListDependency_CategoryFilter_Abstract');

class ML_Shopware_Model_ProductListDependency_CategoryFilter extends ML_Shop_Model_ProductListDependency_CategoryFilter_Abstract {

    /**
     * if count of categories more then $iTreeMaxCount, display just part of cat-tree
     * @var int $iTreeMaxCount
     */
    protected $iTreeMaxCount = 200;

    /**
     * life-time for cache
     * @var int $iCacheLifeTime
     */
    protected $iCacheLifeTime = 1800;


    /**
     * @var null not initalised
     * @var array array(catId=>count childs) displays only cats, count childs is needed to display arrow in select>option
     */
    protected $aCatsFilter = null;

    /**
     * key=>value for filtering (eg. validation and form-select)
     * @var array|null
     */
    protected $aFilterValues = null;

    /**
     * all categories
     * @var array|null
     */
    protected $aCategories = null;

    /**
     * render form-select for current filter
     * @param \ML_Core_Controller_Abstract $oController
     * @param string $sFilterName
     * @return string
     * @throws MLAbstract_Exception
     */
    public function renderFilter(ML_Core_Controller_Abstract $oController, $sFilterName) {
        return $oController->includeViewBuffered('widget_productlist_filter_searchable_select_snippet', array(
            'aFilter' => array(
                'name' => $sFilterName,
                'value' => $this->getFilterValue(),
                'values' => $this->getFilterValuesBase(),
            ),
        ));
    }

    /**
     * @param ML_Database_Model_Query_Select $mQuery
     * @return void
     * @throws Exception
     */
    public function manipulateQuery($mQuery) {
        $sFilterValue = (int)$this->getFilterValue();
        if (
            !empty($sFilterValue)
            && $sFilterValue !== 1 //root-category
            && array_key_exists($sFilterValue, $this->getFilterValues())
        ) {
            $mQuery
                ->join(array('s_categories', 'c', "c.id = $sFilterValue"), ML_Database_Model_Query_Select::JOIN_TYPE_LEFT)
                ->join(array(MLDatabase::getDbInstance()->tableExists('s_articles_categories_ro') ? 's_articles_categories_ro' : 's_articles_categories', 'pc', 'pc.articleID  = p.id AND pc.categoryID = c.id'), ML_Database_Model_Query_Select::JOIN_TYPE_INNER);
        }
    }

    /**
     * parent checks if value is possible - we dont know in this moment because perhaps not all categories are loaded
     * @param string $sValue
     * @return \ML_Shopware_Model_ProductListDependency_CategoryFilter
     */
    public function setFilterValue($sValue) {
        $this->sFilterValue = $sValue;
        return $this;
    }

    protected function getFilterValuesBase() {
        if ($this->aFilterValues === null) {
            $aCats = array(array(
                'value' => '',
                'label' => sprintf(MLI18n::gi()->get('Productlist_Filter_sEmpty'), MLI18n::gi()->get('Shopware_Productlist_Filter_sCategory')),
            ));
            $this->aFilterValues = $aCats;

            // only if a value is selected
            /*if ($this->getFilterValue() !== null) {
                foreach ($this->getShopwareCategories() as $aValue) {
                    if ($this->getFilterValue() == $aValue['value']) {
                        $aCats[$aValue['value']] = $aValue;
                        break;
                    }
                }
            }
            */
        }

        return $this->aFilterValues;
    }

    /**
     * key=>value for categories
     * @return array
     * @throws MLAbstract_Exception
     * @deprecated
     */
    protected function getFilterValues() {
        if ($this->aFilterValues === null) {
            if (
                $this->getFilterValue() !== null
                && Shopware()->Db()->fetchOne(
                    'SELECT COUNT(*) FROM '.Shopware()->Models()->getClassMetadata('Shopware\Models\Category\Category')->getTableName()
                ) > $this->iTreeMaxCount
            ) {
                $this->aCatsFilter = array(
                    1, trim($this->getFilterValue()),
                );
                $oBuilder = Shopware()->Models()->createQueryBuilder();
                $oBuilder->from('Shopware\Models\Category\Category', 'category')
                    ->select(array(
                        'category',
                    ))
                    ->andWhere('category.id = :id')
                    ->setParameter('id', (int)$this->getFilterValue())
                    ->addOrderBy('category.position');

                foreach ($oBuilder->getQuery()->getArrayresult() as $aCurrent) {
                    foreach (explode('|', $aCurrent['path']) as $iCurrent) {
                        $iCurrent = trim($iCurrent);
                        if (!empty($iCurrent) && !in_array($iCurrent, $this->aCatsFilter)) {
                            $this->aCatsFilter[] = $iCurrent;
                        }
                    }
                    if (!in_array(trim($aCurrent['id']), $this->aCatsFilter)) {
                        $this->aCatsFilter[] = trim($aCurrent['id']);
                    }
                }

            }
            $aCats = array(array(
                'value' => '',
                'label' => sprintf(MLI18n::gi()->get('Productlist_Filter_sEmpty'), MLI18n::gi()->get('Shopware_Productlist_Filter_sCategory')),
            ));
            foreach ($this->getShopwareCategories() as $aValue) {
                $aCats[$aValue['value']] = $aValue;
            }
            $this->aFilterValues = $aCats;
        }
        return $this->aFilterValues;
    }

    /**
     * gets all categories
     * @param array|null $aCats nested cats
     * @return array
     */
    protected function getShopwareCategories($iParentId = null) {
        $aCats = $this->getShopwareCategoryByParentId($iParentId === null ? 1 : $iParentId);
        foreach ($aCats as $aCat) {
            $this->aCategories[$aCat['id']] = array(
                'value' => $aCat['id'],
                'label' => str_repeat('&nbsp;', substr_count($aCat['path'], '|') * 2) . $aCat['description'],
            );
            if (
                empty($this->aCatsFilter)
                || $aCat['parent'] == $this->getFilterValue()
                || in_array($aCat['id'], $this->aCatsFilter)
            ) {
                $this->getShopwareCategories((int)$aCat['id']);
            } else {
                $this->aCategories[$aCat['id']]['label'] .=
                    ($this->getShopwareCategoryByParentId($aCat['id'], 1) !== false)
                        ? '  '
                        : '';
            }
        }
        if ($iParentId === null) {
            return $this->aCategories;
        } else {
            return array();
        }
    }

    protected function getShopwareCategoryByParentId($iParentId, $iMaxResults = null) {
        $iParentId = (int)$iParentId;
        $sSQL = "
SELECT *
FROM `s_categories`
WHERE `parent` = $iParentId
ORDER BY `position` ASC
";
        if ($iMaxResults !== null) {
            $iMaxResults = (int)$iMaxResults;
            $sSQL .= "
LIMIT $iMaxResults
";
        }
        return MLDatabase::getDbInstance()->fetchArray($sSQL);
    }

    /**
     * Return the categories for Select2 box (in needed ajax structure)
     *
     * @return array
     */
    protected function getCategoriesForSelect2() {
        $sCacheName = strtoupper(get_class($this)) . '_Categories_' . MLModule::gi()->getMarketPlaceId() . '.json';
        if (!MLCache::gi()->exists($sCacheName)) {
            $aFinalCategories = array();

            $aShopCategories = $this->getShopwareCategories();

            foreach ($aShopCategories as $aCategory) {
                $aFinalCategories[] = array(
                    'id'   => $aCategory['value'],
                    'text' => html_entity_decode($aCategory['label'], null, 'UTF-8'),
                );
            }
            MLCache::gi()->set($sCacheName, $aFinalCategories, $this->iCacheLifeTime);
        } else {
            try {
                $aFinalCategories = MLCache::gi()->get($sCacheName);
            } catch (ML_Filesystem_Exception $e) {
                $aFinalCategories = array();
            }
        }

        return $aFinalCategories;
    }

    /**
     * @return $this|ML_Shop_Model_ProductListDependency_CategoryFilter_Abstract
     */
    public function callAjax() {
        // preload the category cache
        if (MLRequest::gi()->data('categoryfilter') == 'PreloadCategoryCache') {
            $this->getCategoriesForSelect2();
        }

        // Ajax call for select2
        if (MLRequest::gi()->data('categoryfilter') == 'GetCategories') {
            // get categories
            $aFinalCategories = $this->getCategoriesForSelect2();

            // Search field
            $sSearch = MLRequest::gi()->data('categoryfilterSearch');
            if (!empty($sSearch)) {
                foreach ($aFinalCategories as $sKey => &$aCategory) {
                    if (stripos($aCategory['text'], $sSearch) === false) {
                        unset($aFinalCategories[$sKey]);
                    }
                }
            }

            // Pagination
            $iLength = 250;
            $iPageLength = (int)MLRequest::gi()->data('categoryfilterPage') * $iLength;
            $iOffset = (($iPageLength) - $iLength);

            // response
            MLSetting::gi()->add('aAjax', array(
                'results' => array_slice($aFinalCategories, $iOffset, $iLength),
                'pagination' => array(
                    'more' => (count($aFinalCategories) > $iPageLength) ? true : false,
                )
            ));
        }

        return $this;
    }

}
