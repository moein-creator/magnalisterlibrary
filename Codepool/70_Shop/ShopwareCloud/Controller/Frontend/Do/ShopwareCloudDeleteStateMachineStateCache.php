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
include_once(M_DIR_LIBRARY . 'request/shopware/ShopwareOrderStatus.php');

use library\request\shopware\ShopwareOrderStatus;

MLFilesystem::gi()->loadClass('ShopwareCloud_Controller_Frontend_Do_ShopwareCloudAbstractCache');

class ML_ShopwareCloud_Controller_Frontend_Do_ShopwareCloudDeleteStateMachineStateCache extends ML_ShopwareCloud_Controller_Frontend_Do_ShopwareCloudAbstractCache {

    protected $sType = 'StateMachineStateDelete';
    protected $iLimitationOfIteration = 10;
    protected $sTranslationType = 'state-machine-state';

    public function __construct() {
        parent::__construct();
        $this->shopwareEntityRequest = new ShopwareOrderStatus($this->sShopId);
    }


    protected function getEntities($preparedFilters) {
        $aShippingMethodIDs = array();
        $aTranslations = array();
        foreach ($preparedFilters['ids'] as $id) {
            $ids = explode('-', $id);
            if (!in_array($ids[0], $aShippingMethodIDs)) {
                $aShippingMethodIDs[] = $ids[0];
            }
        }
        $preparedFilters['ids'] = $aShippingMethodIDs;
        $orderStatuses = $this->shopwareEntityRequest->getShopwareOrderStatuses('/api/search/' . $this->sTranslationType, 'POST', $preparedFilters);
        foreach ($orderStatuses->getData() as $orderStatus) {
            $oTranslations = $this->shopwareEntityRequest->getShopwareTranslations('/api/' . $this->sTranslationType . '/' . $orderStatus->getId() . '/translations');
            $aTranslations = array_merge($aTranslations, $oTranslations->getData());
        }
        $oTranslations->setData($aTranslations);
        return $oTranslations;
    }

    protected function getDBEntityIds() {
        $oQuery = MLDatabase::factory('ShopwarecloudTranslations')->getList()->getQueryObject()->limit($this->iStart, $this->iShopwareCloudLimitPerPage)->where('`ShopwareTranslationType` = "' . $this->sTranslationType . '"');
        $this->iCount = $oQuery->getCount();
        $aEntities = array();
        foreach ($oQuery->getResult() as $entity) {
            if (!in_array($entity['ShopwareTranslationID'], $aEntities)) {
                $aEntities[] = $entity['ShopwareTranslationID'];
            }
        }
        return $aEntities;
    }

    protected function updateEntity($data) {
        MLDatabase::getDbInstance()->query(
            '
DELETE T
FROM `magnalister_shopwarecloud_translations` T 
WHERE T.ShopwareTranslationID IN("' . implode('","', $data) . '")', true
        );
    }

}
