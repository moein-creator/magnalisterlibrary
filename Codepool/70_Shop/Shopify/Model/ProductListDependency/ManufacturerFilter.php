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
 * (c) 2010 - 2022 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

MLFilesystem::gi()->loadClass('Shop_Model_ProductListDependency_ManufacturerFilter_Abstract');

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
 * Class ML_Shopify_Model_ProductListDependency_ManufacturerFilter
 */
class ML_Shopify_Model_ProductListDependency_ManufacturerFilter extends ML_Shop_Model_ProductListDependency_ManufacturerFilter_Abstract {
    /**
     * possible filter values
     * @var null | array key => value
     */
    protected $aFilterValues = null;
    /**
     * @param ML_Database_Model_Query_Select $mQuery
     * @return mixed|void
     */
    public function manipulateQuery($mQuery) {
        $sFilterValue = $this->getFilterValue();
        if(!empty($sFilterValue)) {
            $mQuery->where("`ShopifyVendorId` = '".MLDatabase::getDbInstance()->escape($sFilterValue)."'");
        }
    }

    protected function getFilterValues() {
        $this->aFilterValues = array(
            '' =>
                array(
                    'value' => '',
                    'label' => 'Filter (Vendor)',
                ),
        );

        foreach (MLShopifyAlias::getProductVendorModel()->getList()->getQueryObject()->getResult() as $aVendor) {
            $this->aFilterValues[$aVendor['VendorId']] = array(
                'value' => $aVendor['VendorId'],
                'label' => $aVendor['ShopifyVendorTitle'],
            );
        }

        return $this->aFilterValues;
    }


}
