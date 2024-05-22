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

class ML_ShopwareCloud_Controller_Frontend_Do_ShopwareCloudDeletePropertyGroupCache extends ML_ShopwareCloud_Controller_Frontend_Do_ShopwareCloudAbstractCache {

    protected $sType = 'PropertyGroupDelete';
    protected $iLimitationOfIteration = 10;

    public function __construct() {
        parent::__construct();
        $this->shopwareEntityRequest = new ShopwareProduct($this->sShopId);
    }


    protected function getEntities($preparedFilters, $type = '') {
        $result = array();
        if ($type === 'PropertyGroupIDs') {
            $result = $this->shopwareEntityRequest->getShopwareProducts('/api/search/property-group', 'POST', $preparedFilters);
        }
        if ($type === 'PropertyGroupOptionIDs') {
            $result = $this->shopwareEntityRequest->getShopwareProducts('/api/search/property-group-option', 'POST', $preparedFilters);
        }

        return $result;
    }

    protected function getDBEntityIds() {
        $oQuery = MLDatabase::factory('ShopwareCloudPropertyGroup')->getList()->getQueryObject()->limit($this->iStart, $this->iShopwareCloudLimitPerPage)->where('`ShopwarePropertyGroupID` IS NOT NULL');
        $this->iCount = $oQuery->getCount();
        $aEntities = array();
        foreach ($oQuery->getResult() as $entity) {
            if (isset($aEntities['ShopwarePropertyGroupID']) && !in_array($entity['ShopwarePropertyGroupID'], $aEntities['ShopwarePropertyGroupID'])) {
                $aEntities['PropertyGroupIDs'][] = $entity['ShopwarePropertyGroupID'];
            }
        }
        $oQuery = MLDatabase::factory('ShopwareCloudPropertyGroupOptionTranslation')->getList()->getQueryObject()->limit($this->iStart, $this->iShopwareCloudLimitPerPage)->where('`ShopwarePropertyGroupOptionID` IS NOT NULL');
        $this->iCount = $oQuery->getCount();
        foreach ($oQuery->getResult() as $entity) {
            if (isset($aEntities['PropertyGroupOptionIDs']) && !in_array($entity['ShopwarePropertyGroupOptionID'], $aEntities['PropertyGroupOptionIDs'])) {
                $aEntities['PropertyGroupOptionIDs'][] = $entity['ShopwarePropertyGroupOptionID'];
            }
        }
        return $aEntities;
    }

    protected function updateEntity($data) {
        foreach ($data as $key => $ids) {
            if ($key === 'PropertyGroupIDs' && count($ids) > 0) {
                MLDatabase::getDbInstance()->query(
                    '
DELETE PG,PGOT,PGT
FROM `magnalister_shopwarecloud_property_group` PG 
LEFT JOIN `'.MLDatabase::factory('ShopwareCloudPropertyGroupOptionTranslation')->getTableName().'` PGOT  ON PGOT.ShopwarePropertyGroupID = PG.ShopwarePropertyGroupID  
LEFT JOIN `'.MLDatabase::factory('ShopwareCloudPropertyGroupTranslation')->getTableName().'` PGT  ON PGT.ShopwarePropertyGroupID = PG.ShopwarePropertyGroupID  
WHERE PG.ShopwarePropertyGroupID IN("'.implode('","', $ids).'")', true
                );
            }

            if ($key === 'PropertyGroupOptionIDs' && count($ids) > 0) {
                MLDatabase::getDbInstance()->query(
                    '
DELETE PGOT
FROM `magnalister_shopwarecloud_property_group_option_translation` PGOT 
WHERE PGOT.ShopwarePropertyGroupOptionID IN("'.implode('","', $ids).'")', true
                );
            }
        }
    }

}
