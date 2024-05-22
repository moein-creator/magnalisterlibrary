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
include_once(M_DIR_LIBRARY . 'request/shopware/ShopwareProduct.php');

use library\request\shopware\ShopwareProduct;

MLFilesystem::gi()->loadClass('ShopwareCloud_Controller_Frontend_Do_ShopwareCloudAbstractCache');

class ML_ShopwareCloud_Controller_Frontend_Do_ShopwareCloudDeleteCategoryCache extends ML_ShopwareCloud_Controller_Frontend_Do_ShopwareCloudAbstractCache {

    protected $sType = 'CategoryDelete';
    protected $iLimitationOfIteration = 10;

    public function __construct() {
        parent::__construct();
        $this->shopwareEntityRequest = new ShopwareProduct($this->sShopId);
    }


    protected function getEntities($preparedFilters) {
        return $this->shopwareEntityRequest->getShopwareProducts('/api/search/category', 'POST', $preparedFilters);
    }

    protected function getDBEntityIds() {
        $oQuery = MLDatabase::factory('ShopwareCloudCategory')->getList()->getQueryObject()->limit($this->iStart, $this->iShopwareCloudLimitPerPage)->where('`ShopwareCategoryID` IS NOT NULL');
        $this->iCount = $oQuery->getCount();
        $aEntities = array();
        foreach ($oQuery->getResult() as $entity) {
            if (!in_array($entity['ShopwareCategoryID'], $aEntities)) {
                $aEntities[] = $entity['ShopwareCategoryID'];
            }
        }
        return $aEntities;
    }

    protected function updateEntity($data) {
        MLDatabase::getDbInstance()->query(
            '
DELETE PCR,CA,CT
FROM `magnalister_shopwarecloud_category` CA 
LEFT JOIN `'.MLDatabase::factory('ShopwareCloudCategoryRelation')->getTableName().'` PCR  ON PCR.ShopwareCategoryID = CA.ShopwareCategoryID  
LEFT JOIN `'.MLDatabase::factory('ShopwareCloudCategoryTranslation')->getTableName().'` CT  ON CT.ShopwareCategoryID = CA.ShopwareCategoryID  
WHERE CA.ShopwareCategoryID IN("'.implode('","', $data).'")', true
        );
    }

}
