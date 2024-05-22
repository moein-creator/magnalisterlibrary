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

include_once(M_DIR_LIBRARY . 'request/shopware/ShopwareProduct.php');

use library\request\shopware\ShopwareProduct;

MLFilesystem::gi()->loadClass('ShopwareCloud_Controller_Frontend_Do_ShopwareCloudAbstractCache');

/**
 * This process get all available product from Shopware Cloud daily and update product data in magnalister
 * if some changes are missing in executeUpdate it will be applied at latest after a day
 */
class ML_ShopwareCloud_Controller_Frontend_Do_ShopwareCloudProductCache extends ML_ShopwareCloud_Controller_Frontend_Do_ShopwareCloudAbstractCache {

    protected $sType = 'Product';

    public function __construct() {
        parent::__construct();
        $this->shopwareEntityRequest = new ShopwareProduct($this->sShopId);
    }

    protected function getTotalCountOfEntities() {
        $preparedFilters = $this->getUpdatedAtTimeFilter();
        return (int)MLShopwareCloudAlias::getProductHelper()->getShopwareProductEntityListCount('/api/search/product', 'getShopwareProducts', $preparedFilters);
    }

    protected function getEntities($preparedFilters) {
        return $this->shopwareEntityRequest->getShopwareProducts('/api/search/product', 'POST', $preparedFilters, true);
    }

    protected function updateEntity($data) {
        $data['MethodOfUpdate'] = $this->sMethodOfUpdate;
        if (!isset($data['attributes']['parentId'])) {
            MLShopwareCloudAlias::getProductModel()->loadByShopProduct($data);  //insert master porduct
        }
    }

    protected function saveEntityRelationships($data) {
        $this->populateProductCategories($data['id'], $data['attributes']['categoryIds']);
    }

    protected function populateProductCategories($productId, $categoryIds) {
        $sValues = '';
        foreach ($categoryIds as $categoryId) {
            $rowPresent = MLDatabase::getDbInstance()->fetchRow("
                SELECT ShopwareCategoryID FROM ".MLDatabase::factory('ShopwareCloudCategoryRelation')->getTableName()."
                WHERE ShopwareCategoryID = '".$categoryId."'
                AND ShopwareProductID = '".$productId."'
                ");
            if (!$rowPresent) {
                $sValues .= "('".$categoryId . "','" . $productId."'),";
            }
        }

        if (!empty($sValues)) {
            MLDatabase::getDbInstance()->query("INSERT INTO ".MLDatabase::factory('ShopwareCloudCategoryRelation')->getTableName()." 
                                            (ShopwareCategoryID, ShopwareProductID) VALUES ".rtrim($sValues, ","));
        }
    }

}
