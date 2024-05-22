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

class ML_ShopwareCloud_Controller_Frontend_Do_ShopwareCloudDeleteManufacturerCache extends ML_ShopwareCloud_Controller_Frontend_Do_ShopwareCloudAbstractCache {

    protected $sType = 'ManufacturerDelete';
    protected $iLimitationOfIteration = 10;

    public function __construct() {
        parent::__construct();
        $this->shopwareEntityRequest = new ShopwareProduct($this->sShopId);
    }


    protected function getEntities($preparedFilters) {
        return $this->shopwareEntityRequest->getShopwareProducts('/api/search/product-manufacturer', 'POST', $preparedFilters);
    }

    protected function getDBEntityIds() {
        $oQuery = MLDatabase::factory('ShopwareCloudProductManufacturerTranslation')->getList()->getQueryObject()->limit($this->iStart, $this->iShopwareCloudLimitPerPage)->where('`ShopwareProductManufacturerID` IS NOT NULL');
        $this->iCount = $oQuery->getCount();
        $aEntities = array();
        foreach ($oQuery->getResult() as $entity) {
            if (!in_array($entity['ShopwareProductManufacturerID'], $aEntities)) {
                $aEntities[] = $entity['ShopwareProductManufacturerID'];
            }
        }
        return $aEntities;
    }

    protected function updateEntity($data) {
        MLDatabase::getDbInstance()->query(
            '
DELETE PMT
FROM `magnalister_shopwarecloud_product_manufacturer_translation` PMT 
WHERE PMT.ShopwareProductManufacturerID IN("'.implode('","', $data).'")', true
        );
    }

}
