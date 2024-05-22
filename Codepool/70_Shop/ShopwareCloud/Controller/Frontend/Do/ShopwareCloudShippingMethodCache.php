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

include_once(M_DIR_LIBRARY . 'request/shopware/ShopwareShippingMethod.php');

use library\request\shopware\ShopwareShippingMethod;

MLFilesystem::gi()->loadClass('ShopwareCloud_Controller_Frontend_Do_ShopwareCloudAbstractCache');

/**
 * This process get all available Shipping methods from Shopware Cloud daily and update shipping method data in magnalister
 * if some changes are missing in executeUpdate it will be applied at latest after a day
 */
class ML_ShopwareCloud_Controller_Frontend_Do_ShopwareCloudShippingMethodCache extends ML_ShopwareCloud_Controller_Frontend_Do_ShopwareCloudAbstractCache {

    protected $sType = 'ShippingMethod';

    public function __construct() {
        parent::__construct();
        $this->shopwareEntityRequest = new ShopwareShippingMethod($this->sShopId);
    }

    public function updateEntity($data) {
        $oTranslation = $this->shopwareEntityRequest->getShopwareTranslations('/api/shipping-method/' . $data->getId() . '/translations');
        foreach ($oTranslation->getData() as $value) {
            MLDatabase::factory('ShopwarecloudTranslations')
                ->set('ShopwareTranslationID', $value->getId())
                ->set('ShopwareEntityID', $data->getId())
                ->set('ShopwareLanguageID', $value->getAttributes()->getLanguageId())
                ->set('ShopwareTranslationType', 'shipping-method')
                ->set('ShopwareTranslation', $value->getAttributes()->getName())
                ->save();
        }
    }

    protected function getTotalCountOfEntities() {
        $preparedFilters = $this->getUpdatedAtTimeFilter();
        $filters = [
            'page' => 1,
            'limit' => 1,
            'total-count-mode' => 1,
        ];
        $preparedFilters = array_merge($filters, $preparedFilters);
        $entities = $this->shopwareEntityRequest->getShippingMethods('/api/search/shipping-method', 'POST', $preparedFilters);
        return $entities->getMeta()->getTotal();
    }

    protected function getEntities($preparedFilters) {
        return $this->shopwareEntityRequest->getShippingMethods('/api/search/shipping-method', 'POST', $preparedFilters);
    }

}
