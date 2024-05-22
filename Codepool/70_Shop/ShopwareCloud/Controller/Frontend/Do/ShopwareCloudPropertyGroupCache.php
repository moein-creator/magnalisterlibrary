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
class ML_ShopwareCloud_Controller_Frontend_Do_ShopwareCloudPropertyGroupCache extends ML_ShopwareCloud_Controller_Frontend_Do_ShopwareCloudAbstractCache {

    protected $sType = 'PropertyGroup';

    public function __construct() {
        parent::__construct();
        $this->shopwareEntityRequest = new ShopwareProduct($this->sShopId);
    }

    protected function getTotalCountOfEntities() {
        $preparedFilters = $this->getUpdatedAtTimeFilter();
        return (int)MLShopwareCloudAlias::getProductHelper()->getShopwareProductEntityListCount('/api/search/property-group', 'getShopwarePropertyGroups', $preparedFilters);
    }

    protected function updateEntity($data) {
        //Import Property group options
        $sPropertyGroupOptionsUrl = $data->getRelationships()->getOptions()->getLinks()->getRelated();
        $this->importPropertyGroupOptions($sPropertyGroupOptionsUrl);

        //Import Property group translations
        $sPropertyGroupTranslationUrl = $data->getRelationships()->getTranslations()->getLinks()->getRelated();
        $this->importPropertyGroupTranslation($sPropertyGroupTranslationUrl);
        MLDatabase::factory('ShopwareCloudPropertyGroup')
            ->set('ShopwarePropertyGroupID', $data->getId())
            ->set('ShopwareSortingType', $data->getAttributes()->getSortingType())
            ->set('ShopwareDisplayType', $data->getAttributes()->getDisplayType())
            ->set('ShopwareFilterable', $data->getAttributes()->getFilterable())
            ->set('ShopwareVisibleOnProductPage', $data->getAttributes()->getVisibleOnProductDetailPage())
            ->set('ShopwareCreatedDate', MLShopwareCloudHelper::getStorageDateTime($data->getAttributes()->getCreatedAt()))
            ->set('ShopwareUpdateDate', MLShopwareCloudHelper::getStorageDateTime($data->getAttributes()->getUpdatedAt()))
            ->save();
    }

    private function importPropertyGroupTranslation($sPropertyGroupTranslationPath) {
        $oPropertyGroupTranslations = $this->shopwareEntityRequest->getShopwarePropertyGroupTranslations($sPropertyGroupTranslationPath);
        foreach ($oPropertyGroupTranslations->getData() as $oPropertyGroupTranslation) {
            MLDatabase::factory('ShopwareCloudPropertyGroupTranslation')
                ->set('ShopwarePropertyGroupID', $oPropertyGroupTranslation->getAttributes()->getPropertyGroupId())
                ->set('ShopwareLanguageID', $oPropertyGroupTranslation->getAttributes()->getLanguageId())
                ->set('ShopwareName', $oPropertyGroupTranslation->getAttributes()->getName())
                ->set('ShopwareDescription', $oPropertyGroupTranslation->getAttributes()->getDescription())
                ->set('ShopwarePosition', $oPropertyGroupTranslation->getAttributes()->getPosition())
                ->set('ShopwareCustomFields', $oPropertyGroupTranslation->getAttributes()->getCustomFields())
                ->save();
        }
    }

    private function importPropertyGroupOptions($sPropertyGroupOptionsPath) {
        $filters = [
            'page' => 1,
            'limit' => $this->iShopwareCloudLimitPerPage,
            'total-count-mode' => 1,
        ];
        $preparedPath = $this->apiHelper->prepareFilters($filters, 'GET', $sPropertyGroupOptionsPath);
        $iTotalCountOfPropertyGroups = $this->getTotalCountOfPropertyGroupOptions($sPropertyGroupOptionsPath);
        $iLimitationOfIteration = ceil($iTotalCountOfPropertyGroups / 250);
        while ($filters['page'] <= $iLimitationOfIteration) {
            $oPropertyGroups = $this->shopwareEntityRequest->getShopwarePropertyGroupOptions($preparedPath);
            foreach ($oPropertyGroups->getData() as $oPropertyGroup) {
                $sPropertyGroupId = $oPropertyGroup->getAttributes()->getGroupId();
                //Import Property group translations
                $sPropertyGroupOptionTranslationUrl = $oPropertyGroup->getRelationships()->getTranslations()->getLinks()->getRelated();
                $this->importPropertyGroupOptionTranslation($sPropertyGroupOptionTranslationUrl, $sPropertyGroupId);
            }
            $filters['page'] = $filters['page'] + 1;
            $preparedPath = $this->apiHelper->prepareFilters($filters, 'GET', $sPropertyGroupOptionsPath);
        }
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

    protected function getTotalCountOfPropertyGroupOptions($sPropertyGroupOptionsPath) {
        return (int)MLShopwareCloudAlias::getProductHelper()->getShopwareProductEntityListCount($sPropertyGroupOptionsPath, 'getShopwarePropertyGroupOptions', array(), 'GET');
    }


    protected function getEntities($preparedFilters) {
        return $this->shopwareEntityRequest->getShopwarePropertyGroups('/api/search/property-group', 'POST', $preparedFilters);
    }

}
