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
include_once(M_DIR_LIBRARY . 'request/shopware/ShopwareLanguage.php');

use library\request\shopware\ShopwareLanguage;

MLFilesystem::gi()->loadClass('ShopwareCloud_Controller_Frontend_Do_ShopwareCloudAbstractCache');

class ML_ShopwareCloud_Controller_Frontend_Do_ShopwareCloudDeleteLanguageCache extends ML_ShopwareCloud_Controller_Frontend_Do_ShopwareCloudAbstractCache {

    protected $sType = 'LanguageDelete';
    protected $iLimitationOfIteration = 10;

    public function __construct() {
        parent::__construct();
        $this->shopwareEntityRequest = new ShopwareLanguage($this->sShopId);
    }


    protected function getEntities($preparedFilters) {
        return $this->shopwareEntityRequest->getShopwareLanguages('/api/search/language', 'POST', $preparedFilters);
    }

    protected function getDBEntityIds() {
        $oQuery = MLDatabase::factory('ShopwareCloudLanguage')->getList()->getQueryObject()->limit($this->iStart, $this->iShopwareCloudLimitPerPage)->where('`ShopwareLanguageID` IS NOT NULL');
        $this->iCount = $oQuery->getCount();
        $aEntities = array();
        foreach ($oQuery->getResult() as $entity) {
            if (!in_array($entity['ShopwareLanguageID'], $aEntities)) {
                $aEntities[] = $entity['ShopwareLanguageID'];
            }
        }
        return $aEntities;
    }

    protected function updateEntity($data) {
        MLDatabase::getDbInstance()->query(
            '
DELETE L
FROM `magnalister_shopwarecloud_language` L 
WHERE L.ShopwareLanguageID IN("' . implode('","', $data) . '")', true
        );
    }

}
