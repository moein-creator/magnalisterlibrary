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
 * (c) 2010 - 2021 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

class ML_Magento_Helper_Model_Product {

    /**
     * To get images of product with its variant images
     * @param $oProduct ML_Magento_Model_Product|ML_Shop_Model_Product_Abstract
     * @param $iStoreId int
     * @param array $sTablePrefix
     * @param Mage_Catalog_Model_Product_Media_Config $oMediaConfig
     * @param array $aOut
     * @return array
     */
    public function getAllImagesOfProduct($oProduct, $iStoreId, $sTablePrefix, $oMediaConfig, $aOut) {
        $aAllImages = MLDatabase::getDbInstance()->fetchArray("
            SELECT DISTINCT `value`,`store_id`, `position`
            FROM `{$sTablePrefix}catalog_product_entity_media_gallery` g
            INNER JOIN `{$sTablePrefix}catalog_product_entity_media_gallery_value` gv ON g.`value_id` = gv.`value_id` AND gv.`store_id` in(".$iStoreId.", 0)  
            WHERE entity_id in(SELECT ProductsId FROM magnalister_products WHERE `ParentId` = '".((int)$oProduct->get('ID'))."' OR `ID` = '".((int)$oProduct->get('ID'))."')
            ORDER BY `store_id` DESC, entity_id, `position`");

        foreach ($aAllImages as $image) {
            if ($image['store_id'] === $iStoreId &&
                $image['value'] !== 'no_selection' && $image['value'] !== null) {
                $aOut[] = $oMediaConfig->getMediaPath($image['value']);
            }
        }
        if (empty($aOut)) {//get image from default store
            foreach ($aAllImages as $image) {
                if ($image['store_id'] === '0' && $image['value'] !== 'no_selection' && $image['value'] !== null) {
                    $aOut[] = $oMediaConfig->getMediaPath($image['value']);
                }
            }
        }
        return $aOut;
    }
}