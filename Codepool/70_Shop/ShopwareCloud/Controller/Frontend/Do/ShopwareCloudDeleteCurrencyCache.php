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
include_once(M_DIR_LIBRARY . 'request/shopware/ShopwareCurrency.php');

use library\request\shopware\ShopwareCurrency;

MLFilesystem::gi()->loadClass('ShopwareCloud_Controller_Frontend_Do_ShopwareCloudAbstractCache');

class ML_ShopwareCloud_Controller_Frontend_Do_ShopwareCloudDeleteCurrencyCache extends ML_ShopwareCloud_Controller_Frontend_Do_ShopwareCloudAbstractCache {

    protected $sType = 'CurrencyDelete';
    protected $iLimitationOfIteration = 10;

    public function __construct() {
        parent::__construct();
        $this->shopwareEntityRequest = new ShopwareCurrency($this->sShopId);
    }


    protected function getEntities($preparedFilters) {
        return $this->shopwareEntityRequest->getShopwareCurrencies('/api/search/currency', 'POST', $preparedFilters);
    }

    protected function getDBEntityIds() {
        $oQuery = MLDatabase::factory('ShopwareCloudCurrency')->getList()->getQueryObject()->limit($this->iStart, $this->iShopwareCloudLimitPerPage)->where('`ShopwareCurrencyID` IS NOT NULL');
        $this->iCount = $oQuery->getCount();
        $aEntities = array();
        foreach ($oQuery->getResult() as $entity) {
            if (!in_array($entity['ShopwareCurrencyID'], $aEntities)) {
                $aEntities[] = $entity['ShopwareCurrencyID'];
            }
        }
        return $aEntities;
    }

    protected function updateEntity($data) {
        MLDatabase::getDbInstance()->query(
            '
DELETE CR, CT
FROM `magnalister_shopwarecloud_currency` CR 
LEFT JOIN `' . MLDatabase::factory('ShopwareCloudCurrencyTranslation')->getTableName() . '` CT  ON CT.ShopwareCurrencyID = CR.ShopwareCurrencyID  
WHERE CR.ShopwareCurrencyID IN("' . implode('","', $data) . '")', true
        );
    }

}
