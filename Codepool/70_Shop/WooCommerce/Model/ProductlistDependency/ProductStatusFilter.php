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
MLFilesystem::gi()->loadClass('Shop_Model_ProductListDependency_ProductStatusFilter_Abstract');

class ML_WooCommerce_Model_ProductListDependency_ProductStatusFilter extends ML_Shop_Model_ProductListDependency_ProductStatusFilter_Abstract {
    /**
     * possible filter values
     * @var null | array key => value
     */
    protected $aFilterValues = null;

    /**
     * Linking the part of the sql query for product status,
     * with basic part from ML_WooCommerce_Helper_Model_Product function getProductSelectQuery()
     *
     * @param ML_Database_Model_Query_Select $mQuery
     *
     * @return void
     */
    public function manipulateQuery($mQuery) {
        $sFilterValue = $this->getFilterValue();
        if ( ! empty($sFilterValue)) {
            if ($sFilterValue == 'active') {
                $mQuery->where("p.post_status = 'publish'");
            } else {
                $mQuery->where("p.post_status = 'draft' OR p.post_status = 'pending'");
            }
        }
    }

    /**
     * List of possible values for product status
     *
     * key => value for status
     * @return array
     */
    protected function getFilterValues() {
        if ($this->aFilterValues === null) {
            $this->aFilterValues = array(
                '' => array(
                    'value' => '',
                    'label' => sprintf(MLI18n::gi()->get('Productlist_Filter_sEmpty'),
                        MLI18n::gi()->get('WooCommerce_Productlist_Filter_sStatus')),
                ),
                'active'   => array(
                    'value' => 'active',
                    'label' => MLI18n::gi()->get('WooCommerce_Productlist_Filter_sActive'),
                ),
                'inactive' => array(
                    'value' => 'inactive',
                    'label' => MLI18n::gi()->get('WooCommerce_Productlist_Filter_sInactive'),
                ),
            );
        }

        return $this->aFilterValues;
    }

    protected function setFilterOnlyActive() {

        return $this->setFilterValue('active');
    }
}