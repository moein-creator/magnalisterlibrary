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
 * (c) 2010 - 2020 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */


MLFilesystem::gi()->loadClass('Shop_Model_ProductListDependency_SearchFilter_Abstract');
class ML_Magento2_Model_ProductListDependency_SearchFilter extends ML_Shop_Model_ProductListDependency_SearchFilter_Abstract {

    /**
     * @param ML_Database_Model_Query_Select $mQuery
     * @return void
     */
    public function manipulateQuery($mQuery) {
        $sFilterValue = $this->getFilterValue();
        if (!empty($sFilterValue)) {

            $sFilterValue = str_replace("'", "''", $sFilterValue);
            // Tables used for seach: catalog_product_entity, catalog_product_entity_text, catalog_product_entity_varchar
            $mQuery->getSelectSql()
                /*->joinLeft(
                    array('catalog_product_entity_text' => MLMagento2Alias::getMagento2Db()->getTableName('catalog_product_entity_text')),
                    "e.entity_id = catalog_product_entity_text.entity_id",
                    array()
                )*/
                ->joinLeft(
                    array('catalog_product_entity_varchar' => MLMagento2Alias::getMagento2Db()->getTableName('catalog_product_entity_varchar')),
                    "e.entity_id = catalog_product_entity_varchar.entity_id",
                    array()
                )
                ->where(
                //'catalog_product_entity_text.value LIKE \'%'. $sFilterValue .'%\'
                    'catalog_product_entity_varchar.value LIKE \'%'. $sFilterValue .'%\'
                    OR e.entity_id = \''.$sFilterValue.'\'
                    OR e.sku = \''.$sFilterValue.'\''
                )->distinct(true);
            MLMessage::gi()->addDebug('Magento Product list search Filter query',array($mQuery->getSelect()->__toString()));
        }
    }
}
