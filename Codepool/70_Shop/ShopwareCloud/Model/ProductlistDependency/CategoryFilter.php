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

class ML_ShopwareCloud_Model_ProductListDependency_CategoryFilter extends ML_Shop_Model_ProductListDependency_CategoryFilter_Abstract {

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
     * @param ML_Core_Controller_Abstract $oController
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
        $sFilterValue = (string)$this->getFilterValue();
        if (
            !empty($sFilterValue)
            && $sFilterValue !== 1 //root-category            
        ) {
            $mQuery
                ->where('cpr.ShopwareCategoryID = \''.(string)$sFilterValue.'\' OR ccr.`ShopwarePath` like \'%'.(string)$sFilterValue.'%\'')
                ->join(array('magnalister_shopwarecloud_category_relation','cpr' , 'p.`ProductsID` = cpr.`ShopwareProductID`'), ML_Database_Model_Query_Select::JOIN_TYPE_INNER)
                ->join(array('magnalister_shopwarecloud_category','ccr' , 'cpr.`ShopwareCategoryID` = ccr.`ShopwareCategoryID`'), ML_Database_Model_Query_Select::JOIN_TYPE_INNER);                                               
        }
    }

    /**
     * key=>value for categories
     * @return array
     */
    protected function getFilterValues() {
        if ($this->aFilterValues === null) {
            $aCats = array(
                '' =>
                    array(
                        'value' => '',
                        'label' => 'Filter ('.MLI18n::gi()->Shopware_Productlist_Filter_sCategory.')',
                    ),
            );
            $aShopwareCategories = $this->getShopwareCategories();
            if (is_array($aShopwareCategories)) {
                foreach ($aShopwareCategories as $aCat) {
                    $aCats[$aCat['value']] = array(
                        'value' => $aCat['value'],
                        'label' => $aCat['label'],
                    );
                }
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
        $lang = MLShopwareCloudTranslationHelper::gi()->getLanguage();
        $sWhere = $iParentId ? "= '".$iParentId."'" : "IS NULL";

        $aCats = MLDatabase::getDbInstance()->fetchArray("
            SELECT `c`.`ShopwareCategoryID` AS id, 
                   `ct`.`ShopwareName` AS `title`,
                   `c`.`ShopwarePath` AS `path`, 
                   `c`.`ShopwareParentID` AS `parentid`
              FROM `magnalister_shopwarecloud_category` `c`
              JOIN `magnalister_shopwarecloud_category_translation` `ct` ON `c`.ShopwareCategoryID = `ct`.ShopwareCategoryID 
             WHERE      `ct`.ShopwareLanguageID = '".$lang."'
                    AND `c`.`ShopwareParentID` " . $sWhere . "
          ORDER BY `ShopwareName` ASC
        ");

        foreach ($aCats as $aCat) {
            $path = !empty($aCat['path']) ? $aCat['path'] : '';
            $this->aCategories[$aCat['id']] = array(
                'value' => $aCat['id'],
                'label' => str_repeat('&nbsp;', substr_count($path, '|') * 2) . $aCat['title'],
            );
            if (empty($this->aCatsFilter) 
                || $aCat['parentid'] == $this->getFilterValue() 
                || in_array($aCat['id'], $this->aCatsFilter)
            ) {
                $this->getShopwareCategories($aCat['id']);
            } else {
                $CategoryCount = MLDatabase::getDbInstance()->fetchArray("
                    SELECT `c`.`ShopwareCategoryID` AS id, 
                           `ct`.`ShopwareName` AS `title`,
                           `c`.`ShopwarePath` AS `path`, 
                           `c`.`ShopwareParentID` AS `parentid`
                      FROM `magnalister_shopwarecloud_category` `c`
                      JOIN `magnalister_shopwarecloud_category_translation` `ct` ON `c`.ShopwareCategoryID = `ct`.ShopwareCategoryID 
                     WHERE      `ct`.ShopwareLanguageID = '".$lang."'
                            AND `c`.`ShopwareParentID` = '".$aCat['id']."'
                  ORDER BY `ShopwareName` ASC
                ");

                $this->aCategories[$aCat['id']]['label'] .= (count($CategoryCount) > 0) ? '  ' : '';
            }
        }
        if ($iParentId === null) {
            return $this->aCategories;
        } else {
            return array();
        }
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

    protected function getFilterValuesBase() {
        if ($this->aFilterValues === null) {
            $aCats = array(array(
                'value' => '',
                'label' => sprintf(MLI18n::gi()->get('Productlist_Filter_sEmpty'), MLI18n::gi()->get('Shopware_Productlist_Filter_sCategory')),
            ));
            $this->aFilterValues = $aCats;
        }

        return $this->aFilterValues;
    }
}
