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
include_once(M_DIR_LIBRARY . 'request/shopware/ShopwareCountry.php');

use library\request\shopware\ShopwareCountry;

MLFilesystem::gi()->loadClass('ShopwareCloud_Controller_Frontend_Do_ShopwareCloudAbstractCache');

/**
 * This process get all available manufacturer from Shopify daily and update manufacturer data in magnalister
 * if some changes are missing in executeUpdate it will be applied at latest after a day
 */
class ML_ShopwareCloud_Controller_Frontend_Do_ShopwareCloudCountryCache extends ML_ShopwareCloud_Controller_Frontend_Do_ShopwareCloudAbstractCache {

    protected $sType = 'Country';

    public function __construct() {
        parent::__construct();
        $this->shopwareEntityRequest = new ShopwareCountry($this->sShopId);
    }

    protected function getTotalCountOfEntities() {
        $preparedFilters = $this->getUpdatedAtTimeFilter();
        $filters = [
            'page' => 1,
            'limit' => 1,
            'total-count-mode' => 1,
        ];
        $preparedFilters = array_merge($filters, $preparedFilters);
        $entities = $this->shopwareEntityRequest->getShopwareCountries('/api/search/country', 'POST', $preparedFilters);
        return $entities->getMeta()->getTotal();
    }

    /**
     * @param $data \src\Model\Shopware\Country\ShopwareCountry
     * @return void
     * @throws Exception
     */
    public function updateEntity($data) {
        MLDatabase::factory('ShopwareCloudCountry')
            ->set('ShopwareCountryID', $data->getId())
            ->set('ShopwareIso', $data->getAttributes()->getIso())
            ->set('ShopwarePosition', $data->getAttributes()->getPosition())
            ->set('ShopwareTaxFree', $data->getAttributes()->getTaxFree())
            ->set('ShopwareActive', $data->getAttributes()->getActive())
            ->set('ShopwareIso3', $data->getAttributes()->getIso3())
            ->set('ShopwareCustomerTax', json_encode($data->getAttributes()->getCustomerTax()))
            ->set('ShopwareCompanyTax', json_encode($data->getAttributes()->getCompanyTax()))
            ->set('ShopwareCreatedAt', MLShopwareCloudHelper::getStorageDateTime($data->getAttributes()->getCreatedAt()))
            ->set('ShopwareUpdateDate', MLShopwareCloudHelper::getStorageDateTime($data->getAttributes()->getUpdatedAt()))
            ->set('ShopwareShippingAvailable', $data->getAttributes()->getShippingAvailable())
            ->save();
    }

    protected function saveEntityRelationships($data) {
        $oStates = $this->shopwareEntityRequest->getShopwareCountryStates('/api/country/' . $data->getId() . '/states');
        foreach ($oStates->getData() as $oState) {
            $countryStateTranslation = $this->shopwareEntityRequest->getShopwareCountryStatesTranslations($oState->getRelationships()->getTranslations()->getLinks()->getRelated());
            foreach ($countryStateTranslation->getData() as $value) {
                MLDatabase::factory('ShopwareCloudCountryStateTranslation')
                    ->set('ShopwareCountryStateTranslationID', $value->getId())
                    ->set('ShopwareCountryStateID', $value->getAttributes()->GetCountryStateId())
                    ->set('ShopwareCountryLanguageId', $value->getAttributes()->GetLanguageId())
                    ->set('ShopwareName', $value->getAttributes()->getName())
                    ->set('ShopwareCreatedAt', MLShopwareCloudHelper::getStorageDateTime($value->getAttributes()->getCreatedAt()))
                    ->set('ShopwareUpdateDate', MLShopwareCloudHelper::getStorageDateTime($value->getAttributes()->getUpdatedAt()))
                    ->save();
            }

            MLDatabase::factory('ShopwareCloudCountryState')
                ->set('ShopwareCountryStateID', $oState->getId())
                ->set('ShopwareCountryId', $oState->getAttributes()->getCountryId())
                ->set('ShopwareShortCode', $oState->getAttributes()->getShortCode())
                ->set('ShopwarePosition', $oState->getAttributes()->getPosition())
                ->set('ShopwareActive', $oState->getAttributes()->getActive())
                ->set('ShopwareCreatedAt', MLShopwareCloudHelper::getStorageDateTime($oState->getAttributes()->getCreatedAt()))
                ->set('ShopwareUpdateDate', MLShopwareCloudHelper::getStorageDateTime($oState->getAttributes()->getUpdatedAt()))
                ->save();
        }
    }

    protected function getEntities($preparedFilters) {
        return $this->shopwareEntityRequest->getShopwareCountries('/api/search/country', 'POST', $preparedFilters);
    }

}
