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

MLFilesystem::gi()->loadClass('Shop_Model_ProductListDependency_ManufacturerFilter_Abstract');

class ML_WooCommerce_Model_ProductListDependency_ManufacturerFilter extends ML_Shop_Model_ProductListDependency_ManufacturerFilter_Abstract {
    /**
     * possible filter values
     * @var null | array key => value
     */
    protected $aFilterValues = null;

    /**
     * @param ML_Database_Model_Query_Select $mQuery
     *
     * @return void
     */
    public function manipulateQuery($mQuery) {
        global $wpdb;


        $sType = MLModul::gi()->getConfig('manufacturer');
        $sFilterValue = $this->getFilterValue();
        if (!empty($sFilterValue)) {
            if (strpos($sType, 'cf_') === 0) {
                $sType = ltrim($sType, 'cf_');
                $manufacturerFilterQuery = "SELECT p3.ID, tr2.meta_value AS manufacturer 
							  FROM `$wpdb->posts` p3 
							  INNER JOIN `$wpdb->postmeta` tr2 ON p3.ID=tr2.post_id 
							  WHERE (tr2.meta_key = '$sType')";
            } else {
                if (strpos($sType, 'pa_') !== 0) {
                    $sType = 'pa_manufacturer';
                }
                $manufacturerFilterQuery = "SELECT p3.ID, t2.name AS manufacturer, tt2.term_id as  manufacturerID
							  FROM `$wpdb->posts` p3 
							  INNER JOIN `$wpdb->term_relationships` tr2 ON p3.ID=tr2.object_id 
							  INNER JOIN `$wpdb->term_taxonomy` tt2 ON tt2.term_taxonomy_id = tr2.term_taxonomy_id 
							  INNER JOIN `$wpdb->terms` t2 ON tt2.term_id = t2.term_id 
							  WHERE (tt2.taxonomy = '$sType')";
            }
            $mQuery
            ->join("($manufacturerFilterQuery) 
							  mix2 on mix2.ID = p.ID", ML_Database_Model_Query_Select::JOIN_TYPE_LEFT)
                ->where("mix2.manufacturerID = '$sFilterValue'");
        }
    }

    /**
     * key => value for manufacturers
     * @return array
     */
    protected function getFilterValues() {
        if ($this->aFilterValues === null) {
            $this->aFilterValues = $this->getManufacturers();
        }

        return $this->aFilterValues;
    }

    private function getManufacturers() {
        global $wpdb;
        $manufacturers = array(
            '' => array(
                'value' => '',
                'label' => sprintf(MLI18n::gi()->get('Productlist_Filter_sEmpty'),
                    MLI18n::gi()->get('WooCommerce_Productlist_Filter_sManufacturer'))
            )
        );

        $sType = MLModul::gi()->getConfig('manufacturer');
        if (strpos($sType, 'cf_') === 0) {
            $sType = ltrim($sType, 'cf_');
            $results = $wpdb->get_results(" 
                SELECT `post_id` AS 'value', `meta_value` AS 'label' 
                FROM `$wpdb->postmeta` 
                WHERE `meta_key` = '$sType' GROUP BY `meta_value` 
            ", ARRAY_A);

            foreach ($results as $result) {
                $manufacturers[$result['label']] = array(
                    'value' => $result['label'],
                    'label' => $result['label']
                );
            }
        } elseif (strpos($sType, 'pa_') === 0) {
            $results = $wpdb->get_results(" 
                SELECT wtm.term_id AS 'value', wt.name AS 'label' 
                FROM `$wpdb->termmeta` AS wtm 
                LEFT JOIN `$wpdb->terms` AS wt 
                ON wtm.term_id = wt.term_id 
                WHERE wtm.meta_key = 'order_$sType' 
            ", ARRAY_A);

            foreach ($results as $result) {
                $manufacturers[$result['value']] = array(
                    'value' => (int)$result['value'],
                    'label' => $result['label']
                );
            }
        }

        return $manufacturers;
    }

}
