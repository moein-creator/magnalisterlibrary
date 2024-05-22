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
include_once(M_DIR_LIBRARY . 'request/shopware/ShopwareCountry.php');

use library\request\shopware\ShopwareCountry;

MLFilesystem::gi()->loadClass('ShopwareCloud_Controller_Frontend_Do_ShopwareCloudAbstractCache');

class ML_ShopwareCloud_Controller_Frontend_Do_ShopwareCloudDeleteCountryCache extends ML_ShopwareCloud_Controller_Frontend_Do_ShopwareCloudAbstractCache {

    protected $sType = 'CountryDelete';
    protected $iLimitationOfIteration = 10;

    public function __construct() {
        parent::__construct();
        $this->shopwareEntityRequest = new ShopwareCountry($this->sShopId);
    }


    protected function getEntities($preparedFilters, $type = '') {
        $result = array();
        if ($type === 'ShopwareCountryID') {
            $result = $this->shopwareEntityRequest->getShopwareCountries('/api/search/country', 'POST', $preparedFilters);
        }
        if ($type === 'ShopwareCountryStateID') {
            $result = $this->shopwareEntityRequest->getShopwareCountryStates('/api/search/country-state', 'POST', $preparedFilters);
        }

        return $result;
    }

    protected function getDBEntityIds() {
        $oQuery = MLDatabase::factory('ShopwareCloudCountry')->getList()->getQueryObject()->limit($this->iStart, $this->iShopwareCloudLimitPerPage)->where('`ShopwareCountryID` IS NOT NULL');
        $this->iCount = $oQuery->getCount();
        $aEntities = array();
        foreach ($oQuery->getResult() as $entity) {
            if (!isset($aEntities['ShopwareCountryID']) || !in_array($entity['ShopwareCountryID'], $aEntities['ShopwareCountryID'])) {
                $aEntities['ShopwareCountryID'][] = $entity['ShopwareCountryID'];
            }
        }
        $oQuery = MLDatabase::factory('ShopwareCloudCountryState')->getList()->getQueryObject()->limit($this->iStart, $this->iShopwareCloudLimitPerPage)->where('`ShopwareCountryStateID` IS NOT NULL');
        $this->iCount = $oQuery->getCount();
        foreach ($oQuery->getResult() as $entity) {
            if (!isset($aEntities['ShopwareCountryStateID']) || !in_array($entity['ShopwareCountryStateID'], $aEntities['ShopwareCountryStateID'])) {
                $aEntities['ShopwareCountryStateID'][] = $entity['ShopwareCountryStateID'];
            }
        }
        return $aEntities;
    }

    protected function updateEntity($data) {
        foreach ($data as $key => $ids) {
            if ($key === 'ShopwareCountryID' && count($ids) > 0) {
                MLDatabase::getDbInstance()->query(
                    '
DELETE SC,SCS
FROM `magnalister_shopwarecloud_country` SC 
LEFT JOIN `' . MLDatabase::factory('ShopwareCloudCountryState')->getTableName() . '` SCS  ON SCS.ShopwareCountryId = SC.ShopwareCountryID  
WHERE SC.ShopwareCountryId IN("' . implode('","', $ids) . '")', true
                );
            }

            if ($key === 'ShopwareCountryStateID' && count($ids) > 0) {
                MLDatabase::getDbInstance()->query(
                    '
DELETE SCS, SCST
FROM `magnalister_shopwarecloud_country_state` SCS 
LEFT JOIN `' . MLDatabase::factory('ShopwareCloudCountryStateTranslation')->getTableName() . '` SCST  ON SCST.ShopwareCountryStateID = SCS.ShopwareCountryStateID  
WHERE SCS.ShopwareCountryStateID IN("' . implode('","', $ids) . '")', true
                );
            }
        }
    }

}
