<?php

use Shopify\API\Application\Application;
use Shopify\API\Application\Request\Collections\CollectionsParams;
use Shopify\API\Application\Request\Products\ListOfProducts\ListOfProductsParams;

MLFilesystem::gi()->loadClass( 'Shop_Model_ProductListDependency_CategoryFilter_Abstract' );

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
 * (c) 2010 - 2017 RedGecko GmbH -- http://www.redgecko.de/
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 *
 * Class ML_Shopify_Model_ProductListDependency_CategoryFilter
 */
class ML_Shopify_Model_ProductListDependency_CategoryFilter extends ML_Shop_Model_ProductListDependency_CategoryFilter_Abstract {

	protected $aFilterValues = null;

    /**
     * @param ML_Database_Model_Query_Select $mQuery
     * @return mixed|void
     */
    public function manipulateQuery($mQuery) {
        $sFilterValue = $this->getFilterValue();
        if(!empty($sFilterValue)) {
            $mQuery->where('`ShopifyCollectionId` = '.(float)$sFilterValue);
        }
    }

	/**
	 * @todo Investigate and implement function.
	 *
	 * Returns categories as array:
	 * [
	 *  'key' => 'value',
	 *  'key' => 'value',
	 *  ...
	 * ]
	 *
	 * @return array
	 */
	protected function getFilterValues() {
        $collections = MLDatabase::getDbInstance()->fetchArray(
            'SELECT `ShopifyCollectionId` AS id, `ShopifyCollectionTitle` AS `title`'.
            ' FROM '.MLShopifyAlias::getProductModel()->getTableName().
            ' GROUP BY `ShopifyCollectionId` '.
            " HAVING ShopifyCollectionId != '' ".
            ' ORDER BY `title` ASC'
        );
		$this->aFilterValues = array(
			'' =>
				array(
	                'value' => '',
	                'label' => 'Filter (Collection)',
				),
			);

		foreach ( $collections as $collection ) {
			$this->aFilterValues[$collection['id']] = array (
				'value' => $collection['id'],
				'label' => $collection['title'],
			);
		}

		return $this->aFilterValues;
	}

}
