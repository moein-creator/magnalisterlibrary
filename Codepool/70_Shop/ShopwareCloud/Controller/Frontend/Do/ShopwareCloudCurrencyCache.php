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
include_once(M_DIR_LIBRARY . 'request/shopware/ShopwareCurrency.php');

use library\request\shopware\ShopwareCurrency;

MLFilesystem::gi()->loadClass('ShopwareCloud_Controller_Frontend_Do_ShopwareCloudAbstractCache');

/**
 * This process get all available manufacturer from Shopify daily and update manufacturer data in magnalister
 * if some changes are missing in executeUpdate it will be applied at latest after a day
 */
class ML_ShopwareCloud_Controller_Frontend_Do_ShopwareCloudCurrencyCache extends ML_ShopwareCloud_Controller_Frontend_Do_ShopwareCloudAbstractCache {

    protected $sType = 'Currency';

    public function __construct() {
        parent::__construct();
        $this->shopwareEntityRequest = new ShopwareCurrency($this->sShopId);
    }

    protected function getTotalCountOfEntities() {
        $preparedFilters = $this->getUpdatedAtTimeFilter();
        $filters = [
            'page' => 1,
            'limit' => 1,
            'total-count-mode' => 1,
        ];
        $preparedFilters = array_merge($filters, $preparedFilters);
        $entities = $this->shopwareEntityRequest->getShopwareCurrencies('/api/search/currency', 'POST', $preparedFilters);
        return $entities->getMeta()->getTotal();
    }

    public function updateEntity($data) {
        MLDatabase::factory('ShopwareCloudCurrency')
            ->set('ShopwareCurrencyID', $data->getId())
            ->set('ShopwareIsoCode', $data->getAttributes()->getIsoCode())
            ->set('ShopwareFactor', $data->getAttributes()->getFactor())
            ->set('ShopwareSymbol', $data->getAttributes()->getSymbol())
            ->set('ShopwarePosition', $data->getAttributes()->getPosition())
            ->set('ShopwareCreatedAt', MLShopwareCloudHelper::getStorageDateTime($data->getAttributes()->getCreatedAt()))
            ->set('ShopwareUpdateDate', MLShopwareCloudHelper::getStorageDateTime($data->getAttributes()->getUpdatedAt()))
            ->set('ShopwareItemRounding', json_encode($data->getAttributes()->getItemRounding()))
            ->set('ShopwareTotalRounding', json_encode($data->getAttributes()->getTotalRounding()))
            ->set('ShopwareTaxFreeFrom', $data->getAttributes()->getTaxFreeFrom())
            ->save();
    }

    protected function saveEntityRelationships($data) {
        $aCurrencyTranslation = $this->shopwareEntityRequest->getShopwareCurrencyTranslations($data->getRelationships()->getTranslations()->getLinks()->getRelated());
        foreach ($aCurrencyTranslation->getData() as $value) {
            MLDatabase::factory('ShopwareCloudCurrencyTranslation')
                ->set('ShopwareCurrencyTranslationID', $value->getId())
                ->set('ShopwareCurrencyId', $value->getAttributes()->getCurrencyId())
                ->set('ShopwareLanguageId', $value->getAttributes()->getLanguageId())
                ->set('ShopwareShortName', $value->getAttributes()->getShortName())
                ->set('ShopwareName', $value->getAttributes()->getName())
                ->set('ShopwareCreatedAt', MLShopwareCloudHelper::getStorageDateTime($value->getAttributes()->getCreatedAt()))
                ->set('ShopwareUpdateDate', MLShopwareCloudHelper::getStorageDateTime($value->getAttributes()->getUpdatedAt()))
                ->save();
        }
    }

    protected function getEntities($preparedFilters) {
        return $this->shopwareEntityRequest->getShopwareCurrencies('/api/search/currency', 'POST', $preparedFilters);
    }
}
