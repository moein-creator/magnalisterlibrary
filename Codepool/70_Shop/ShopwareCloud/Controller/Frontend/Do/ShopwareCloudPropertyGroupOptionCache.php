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
 * This process get all available property group from Shopify daily and update Property Group data in magnalister
 * if some changes are missing in executeUpdate it will be applied at latest after a day
 */
class ML_ShopwareCloud_Controller_Frontend_Do_ShopwareCloudPropertyGroupOptionCache extends ML_ShopwareCloud_Controller_Frontend_Do_ShopwareCloudAbstractCache {

    protected $sType = 'PropertyGroupOption';

    public function __construct() {
        parent::__construct();
        $this->shopwareEntityRequest = new ShopwareProduct($this->sShopId);
    }

    protected function getTotalCountOfEntities() {
        $preparedFilters = $this->getUpdatedAtTimeFilter();
        return (int)MLShopwareCloudAlias::getProductHelper()->getShopwareProductEntityListCount('/api/search/property-group-option', 'getShopwarePropertyGroups', $preparedFilters);
    }

    protected function updateEntity($data) {
        $sPropertyGroupId = $data->getAttributes()->getGroupId();
        //Import Property group translations
        $sPropertyGroupOptionTranslationUrl = $data->getRelationships()->getTranslations()->getLinks()->getRelated();
        $this->importPropertyGroupOptionTranslation($sPropertyGroupOptionTranslationUrl, $sPropertyGroupId);
    }


    private function importPropertyGroupOptionTranslation($sPropertyGroupOptionTranslationUrl, $sPropertyGroupId) {
        $oPropertyGroupOptionTranslations = $this->shopwareEntityRequest->getShopwarePropertyGroupOptionTranslations($sPropertyGroupOptionTranslationUrl);
        foreach ($oPropertyGroupOptionTranslations->getData() as $oPropertyGroupOptionTranslation) {
            MLDatabase::factory('ShopwareCloudPropertyGroupOptionTranslation')
                ->set('ShopwarePropertyGroupOptionID', $oPropertyGroupOptionTranslation->getAttributes()->getPropertyGroupOptionId())
                ->set('ShopwarePropertyGroupID', $sPropertyGroupId)
                ->set('ShopwareLanguageID', $oPropertyGroupOptionTranslation->getAttributes()->getLanguageId())
                ->set('ShopwareName', $oPropertyGroupOptionTranslation->getAttributes()->getName())
                ->set('ShopwarePosition', $oPropertyGroupOptionTranslation->getAttributes()->getPosition())
                ->set('ShopwareCustomFields', $oPropertyGroupOptionTranslation->getAttributes()->getCustomFields())
                ->save();
        }
    }


    protected function getEntities($preparedFilters) {
        return $this->shopwareEntityRequest->getShopwarePropertyGroupOptions('/api/search/property-group-option', 'POST', $preparedFilters);
    }

}
