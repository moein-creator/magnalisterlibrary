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

include_once(M_DIR_LIBRARY . 'request/shopware/ShopwareLanguage.php');

use library\request\shopware\ShopwareLanguage;

MLFilesystem::gi()->loadClass('ShopwareCloud_Controller_Frontend_Do_ShopwareCloudAbstractCache');

/**
 * This process get all available Language from Shopware Cloud daily and update Language data in magnalister
 * if some changes are missing in executeUpdate it will be applied at latest after a day
 */
class ML_ShopwareCloud_Controller_Frontend_Do_ShopwareCloudLanguageCache extends ML_ShopwareCloud_Controller_Frontend_Do_ShopwareCloudAbstractCache {

    protected $sType = 'Language';

    public function __construct() {
        parent::__construct();
        $this->shopwareEntityRequest = new ShopwareLanguage($this->sShopId);
    }

    protected function getTotalCountOfEntities() {
        $preparedFilters = $this->getUpdatedAtTimeFilter();
        $filters = [
            'page' => 1,
            'limit' => 1,
            'total-count-mode' => 1,
        ];
        $preparedFilters = array_merge($filters, $preparedFilters);
        $entities = $this->shopwareEntityRequest->getShopwareLanguages('/api/search/language', 'POST', $preparedFilters);
        return $entities->getMeta()->getTotal();
    }

    public function updateEntity($data) {
        $oCodes = $this->shopwareEntityRequest->getShopwareLanguageTranslationCode('/api/language/' . $data->getId() . '/translation-code');
        MLDatabase::factory('ShopwareCloudLanguage')
            ->set('ShopwareLanguageID', $data->getId())
            ->set('ShopwareLanguageCode', $oCodes->getData()[0]->getAttributes()->getCode())
            ->set('ShopwareLanguageName', $oCodes->getData()[0]->getAttributes()->getName())
            ->save();
    }

    protected function getEntities($preparedFilters) {
        return $this->shopwareEntityRequest->getShopwareLanguages('/api/search/language', 'POST', $preparedFilters);
    }

}
