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
 * (c) 2010 - 2023 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

MLFilesystem::gi()->loadClass('Shop_Model_ProductListDependency_SearchFilter_Abstract');

/**
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
 * $Id$
 *
 * (c) 2010 - 2019 RedGecko GmbH -- http://www.redgecko.de/
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 *
 * Class ML_Shopify_Model_ProductListDependency_SearchFilter
 */
class ML_Shopify_Model_ProductListDependency_SearchFilter extends ML_Shop_Model_ProductListDependency_SearchFilter_Abstract {

    /**
     * @param ML_Database_Model_Query_Select $mQuery
     * @return $this|mixed
     */
    public function manipulateQuery($mQuery) {
        $sFilterValue = $this->getFilterValue();
        if (!empty($sFilterValue)) {
            $sFilterValue = MLDatabase::getDbInstance()->escape($this->getFilterValue());
            $sFoundVariants = $this->getIdsFromVariantSearch($sFilterValue);
            $aWhere = array(
                array('`productsid`', '=', (float)$sFilterValue),
                array('`productssku`', 'LIKE', "%{$sFilterValue}%"),
                array('`ShopifyTitle`', 'LIKE', "%{$sFilterValue}%")
            );
            if ($sFoundVariants !== '') {
                $aWhere[] = '`id` IN ('.$sFoundVariants.')';
            }
            $mQuery->where(
                array(
                    'or' => $aWhere
                )
            );
        }
        return $this;
    }

    protected function getIdsFromVariantSearch($sFilterValue): string {
        $aFoundVariants = MLDatabase::getDbInstance()->fetchArray(
            'SELECT `ParentId` 
                            FROM `'.MLShopifyAlias::getProductModel()->getTableName().'`
                             WHERE `ParentId` != 0'.'
                                  AND (
                                      `productsid`='.(float)$sFilterValue.'
                                     OR `productssku` LIKE'."'%{$sFilterValue}%'".'
                                     OR `ShopifyTitle` LIKE'."'%{$sFilterValue}%'".'
                                     )'
            , true);
        if (is_array($aFoundVariants) && count($aFoundVariants)) {
            return implode(',', $aFoundVariants);
        } else {
            return '';
        }
    }

}
