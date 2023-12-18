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
MLFilesystem::gi()->loadClass('Shop_Model_ProductListDependency_CategoryFilter_Abstract');

class ML_WooCommerce_Model_ProductListDependency_CategoryFilter extends ML_Shop_Model_ProductListDependency_CategoryFilter_Abstract {
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
     * @param ML_Database_Model_Query_Select $mQuery
     *
     * @return void
     */
    public function manipulateQuery($mQuery) {
        global $wpdb;
        $sFilterValue = (int)$this->getFilterValue(); //gets chosen id from ddl
        if ( ! empty($sFilterValue)
             && $sFilterValue !== 1 //root-category
        ) {
            $mQuery
                ->join(array($wpdb->term_relationships, 'tr', 'p.ID = tr.object_id'),
                    ML_Database_Model_Query_Select::JOIN_TYPE_INNER)
                ->join(array($wpdb->term_taxonomy, 'tt', 'tr.term_taxonomy_id = tt.term_taxonomy_id'),
                    ML_Database_Model_Query_Select::JOIN_TYPE_INNER)
                ->join(array($wpdb->terms, 't', "tt.term_id = t.term_id"),
                    ML_Database_Model_Query_Select::JOIN_TYPE_INNER)
                ->where("t.term_id = $sFilterValue");;
        }
    }

    /**
     * key=>value for categories
     * @return array
     */
    protected function getFilterValues() {
        if ($this->aFilterValues === null) {
            $aCats = array(
                array(
                    'value' => '',
                    'label' => sprintf(MLI18n::gi()->get('Productlist_Filter_sEmpty'),
                        MLI18n::gi()->get('WooCommerce_Productlist_Filter_sCategory')),
                )
            );

            $wcCategories = $this->getWCCategories();

            if ($wcCategories == null || empty($wcCategories)) {
                $categories = array();
            } else {
                $categories = $wcCategories;
            }

            foreach ($categories as $aValue) {
                $aCats[$aValue['value']] = $aValue;
            }
            $this->aFilterValues = $aCats;
        }

        return $this->aFilterValues;
    }

    /**
     * gets all categories
     *
     * @param null $iParentId
     *
     * @return array
     * @internal param array|null $aCats nested cats
     */
    protected function getWCCategories($iParentId = null) {
        $aCats = $this->getWCCategoryByParentId($iParentId === null ? 0 : $iParentId);
        foreach ($aCats as $aCat) {
            $counter = 0;
            $this->noOfParents($aCat['parent'], $counter);
            $this->aCategories[$aCat['term_id']] = array(
                'value' => $aCat['term_id'],
                'label' => str_repeat('&nbsp;', $counter * 2) . $aCat['name'],
            );
            $this->getWCCategories($aCat['term_id']);
        }
        if ($iParentId === null) {

            return $this->aCategories;
        } else {

            return array();
        }
    }

    /**
     * gets categories by parentId
     *
     * @param int $iParentId
     *
     * @return array
     */
    protected function getWCCategoryByParentId($iParentId) {
        global $wpdb;
        $childCategories = $wpdb->get_results("
            SELECT t.term_id, name, parent FROM {$wpdb->terms} AS t
            INNER JOIN {$wpdb->term_taxonomy} AS tt 
            ON t.term_id = tt.term_id
            WHERE tt.parent = $iParentId 
            AND tt.taxonomy = 'product_cat';
        ", ARRAY_A);

        return $childCategories;
    }

    /**
     * Counts number of parents (for indentation)
     *
     * @param $parentId
     * @param $count
     *
     * @return int
     */
    private function noOfParents($parentId, &$count) {
        global $wpdb;
        $parent = $wpdb->get_row("
            SELECT * 
            FROM {$wpdb->term_taxonomy} 
            WHERE taxonomy = 'product_cat' 
            AND term_id = $parentId
        ");

        if ( ! $parent) {
            return $count;
        }
        if ($parent->parent == 0) {
            return ++$count;
        }

        $count++;
        $this->noOfParents($parent->parent, $count);
    }
}