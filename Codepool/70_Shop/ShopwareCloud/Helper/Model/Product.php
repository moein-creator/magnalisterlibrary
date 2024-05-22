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

include_once(DIR_MAGNALISTER_HELPER . 'APIHelper.php');
include_once(M_DIR_LIBRARY . 'request/shopware/ShopwareProduct.php');

use library\request\shopware\ShopwareProduct;

class ML_ShopwareCloud_Helper_Model_Product {

    public function getShopwareProductEntityListCount($path, $function,$preparedFilters = array(), $action = 'POST') {
        $filters = [
            'page' => 1,
            'limit' =>  1,
            'total-count-mode' => 1,
        ];
        if ($action == 'POST') {
            $preparedFilters = array_merge($filters, $preparedFilters);
        }

        if ($action == 'GET') {
            $apiHelper = new APIHelper();
            $preparedFilters = array_merge($filters, $preparedFilters);
            $path = $apiHelper->prepareFilters($preparedFilters, 'GET', $path);
        }

        $shopwareProductRequest = new ShopwareProduct(MLShopwareCloudAlias::getShopHelper()->getShopId());
        $entities = $shopwareProductRequest->{$function}($path, $action, $preparedFilters);
        return $entities->getMeta()->getTotal();
    }

    /**
     *
     * @return ML_Database_Model_Query_Select
     */
    public function getProductSelectQuery() {
        $oSqlQuery = MLDatabase::factorySelectClass()
            ->select('DISTINCT p.id')
            ->from(MLShopwareCloudAlias::getProductModel()->getTableName(), 'p')
            ->join(array('magnalister_products', 'd', 'p.`id` = d.`parentid`'), 1)
            ->where('p.parentid = 0 OR d.id != 0');
        return $oSqlQuery;
    }

    public function getProductCreatedAt() {
        $result = '';
        $requestBody['page'] = 1;
        $requestBody['limit'] = 1;
        $requestBody['total-count-mode'] = 1;
        $shopwareProductRequest = new ShopwareProduct(MLShopwareCloudAlias::getShopHelper()->getShopId());
        $products = $shopwareProductRequest->getShopwareProducts('/api/search/product','POST', $requestBody);
        if (isset($products->getData()[0])) {
            $result = $products->getData()[0]->getAttributes()->getCreatedAt();
        }
        return $result;
    }

}
