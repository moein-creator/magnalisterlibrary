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
include_once(M_DIR_LIBRARY . 'request/shopware/ShopwareDocument.php');

use library\request\shopware\ShopwareDocument;

MLFilesystem::gi()->loadClass('ShopwareCloud_Controller_Frontend_Do_ShopwareCloudAbstractCache');

/**
 * This process get all available manufacturer from Shopify daily and update manufacturer data in magnalister
 * if some changes are missing in executeUpdate it will be applied at latest after a day
 */
class ML_ShopwareCloud_Controller_Frontend_Do_ShopwareCloudDocumentCache extends ML_ShopwareCloud_Controller_Frontend_Do_ShopwareCloudAbstractCache {

    protected $sType = 'Document';

    public function __construct() {
        parent::__construct();
        $this->shopwareEntityRequest = new ShopwareDocument($this->sShopId);
    }

    protected function getTotalCountOfEntities() {
        $preparedFilters = $this->getUpdatedAtTimeFilter();
        $filters = [
            'page' => 1,
            'limit' => 1,
            'total-count-mode' => 1,
        ];
        $preparedFilters = array_merge($filters, $preparedFilters);
        $entities = $this->shopwareEntityRequest->getShopwareDocumentType('/api/search/document-type', 'POST', $preparedFilters);
        return $entities->getMeta()->getTotal();
    }

    public function updateEntity($data) {
        MLDatabase::factory('ShopwareCloudDocumentType')
            ->set('ShopwareDocumentTypeId', $data->getId())
            ->set('ShopwareTechnicalName', $data->getAttributes()->getTechnicalName())
            ->set('ShopwareCreatedAt', MLShopwareCloudHelper::getStorageDateTime($data->getAttributes()->getCreatedAt()))
            ->set('ShopwareUpdateDate', MLShopwareCloudHelper::getStorageDateTime($data->getAttributes()->getUpdatedAt()))
            ->save();
    }

    protected function saveEntityRelationships($data) {
        $aDocumentTypeTranslation = $this->shopwareEntityRequest->getShopwareDocumentTypeTranslations($data->getRelationships()->getTranslations()->getLinks()->getRelated());
        foreach ($aDocumentTypeTranslation->getData() as $value) {
            MLDatabase::factory('ShopwareCloudDocumentTypeTranslation')
                ->set('ShopwareDocumentTypeTranslationID', $value->getId())
                ->set('ShopwareDocumentTypeId', $value->getAttributes()->getDocumentTypeId())
                ->set('ShopwareLanguageId', $value->getAttributes()->getLanguageId())
                ->set('ShopwareName', $value->getAttributes()->getName())
                ->set('ShopwareCreatedAt', MLShopwareCloudHelper::getStorageDateTime($value->getAttributes()->getCreatedAt()))
                ->set('ShopwareUpdateDate', MLShopwareCloudHelper::getStorageDateTime($value->getAttributes()->getUpdatedAt()))
                ->save();
        }
    }

    protected function getEntities($preparedFilters) {
        return $this->shopwareEntityRequest->getShopwareDocumentType('/api/search/document-type', 'POST', $preparedFilters);
    }
}
