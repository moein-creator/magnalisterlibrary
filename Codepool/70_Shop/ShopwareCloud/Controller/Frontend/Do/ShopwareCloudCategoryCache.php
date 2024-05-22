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
use src\Model\Shopware\Category\ShopwareCategory;

MLFilesystem::gi()->loadClass('ShopwareCloud_Controller_Frontend_Do_ShopwareCloudAbstractCache');

/**
 * This process get all available category from Shopify daily and update category data in magnalister
 * if some changes are missing in executeUpdate it will be applied at latest after a day
 */
class ML_ShopwareCloud_Controller_Frontend_Do_ShopwareCloudCategoryCache extends ML_ShopwareCloud_Controller_Frontend_Do_ShopwareCloudAbstractCache {

    protected $sType = 'Category';

    public function __construct() {
        parent::__construct();
        $this->shopwareEntityRequest = new ShopwareProduct($this->sShopId);
    }

    protected function getTotalCountOfEntities() {
        $preparedFilters = $this->getUpdatedAtTimeFilter();
        return (int)MLShopwareCloudAlias::getProductHelper()->getShopwareProductEntityListCount('/api/search/category', 'getShopwareCategories', $preparedFilters);
    }



    protected function getEntities($preparedFilters) {
        return $this->shopwareEntityRequest->getShopwareCategories('/api/search/category', 'POST', $preparedFilters);
    }

    /**
     * @param $data ShopwareCategory
     * @return void
     * @throws Exception
     */
    protected function updateEntity($data) {
        MLDatabase::factory('ShopwareCloudCategory')
            ->set('ShopwareCategoryID', $data->getId())
            ->set('ShopwareParentID', $data->getAttributes()->getParentId())
            ->set('ShopwarePath', $data->getAttributes()->getPath())
            ->set('ShopwareAfterCategoryId', $data->getAttributes()->getAfterCategoryId())
            ->set('ShopwareLevel', $data->getAttributes()->getLevel())
            ->set('ShopwareActive', $data->getAttributes()->getActive())
            ->set('ShopwareChildCount', $data->getAttributes()->getChildCount())
            ->set('ShopwareVisible', $data->getAttributes()->getVisible())
            ->set('ShopwareCreatedAt', MLShopwareCloudHelper::getStorageDateTime($data->getAttributes()->getCreatedAt()))
            ->set('ShopwareUpdateDate', MLShopwareCloudHelper::getStorageDateTime($data->getAttributes()->getUpdatedAt()))
            ->save();
    }

    protected function saveEntityRelationships($data) {
        $sCategoryTranslationUrl = $data->getRelationships()->getTranslations()->getLinks()->getRelated();
        $oCategoriesTranslations = $this->shopwareEntityRequest->getShopwareCategoryTranslations($sCategoryTranslationUrl);
        foreach ($oCategoriesTranslations->getData() as $oCategoryTranslation) {
            if ($oCategoryTranslation->getAttributes()->getName() !== null) {
                MLDatabase::factory('ShopwareCloudCategoryTranslation')
                    ->set('ShopwareTranslationID', $oCategoryTranslation->getId())
                    ->set('ShopwareCategoryID', $oCategoryTranslation->getAttributes()->getCategoryId())
                    ->set('ShopwareLanguageID', $oCategoryTranslation->getAttributes()->getLanguageId())
                    ->set('ShopwareBreadCrumb', $oCategoryTranslation->getAttributes()->getBreadCrumb())
                    ->set('ShopwareName', $oCategoryTranslation->getAttributes()->getName())
                    ->set('ShopwareDescription', $oCategoryTranslation->getAttributes()->getDescription())
                    ->set('ShopwareMetaTitle', $oCategoryTranslation->getAttributes()->getMetaTitle())
                    ->set('ShopwareMetaDescription', $oCategoryTranslation->getAttributes()->getMetaDescription())
                    ->set('ShopwareKeywords', $oCategoryTranslation->getAttributes()->getKeywords())
                    ->set('ShopwareCustomFields', $oCategoryTranslation->getAttributes()->getCustomFields())
                    ->save();
            }
        }
    }

}
