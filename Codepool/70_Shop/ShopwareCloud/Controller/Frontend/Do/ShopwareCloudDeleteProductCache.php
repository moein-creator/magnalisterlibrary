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

include_once(M_DIR_LIBRARY . 'request/shopware/ShopwareProduct.php');

use library\request\shopware\ShopwareProduct;

MLFilesystem::gi()->loadClass('ShopwareCloud_Controller_Frontend_Do_ShopwareCloudAbstractCache');

class ML_ShopwareCloud_Controller_Frontend_Do_ShopwareCloudDeleteProductCache extends ML_ShopwareCloud_Controller_Frontend_Do_ShopwareCloudAbstractCache {


    protected $sType = 'ProductDelete';

    protected $iLimitationOfIteration = 10;

    /**
     * @return int
     */
    public function __construct() {
        parent::__construct();
        $this->shopwareEntityRequest = new ShopwareProduct($this->sShopId);
    }

    protected function getEntities($preparedFilters) {
        return $this->shopwareEntityRequest->getShopwareProducts('/api/search/product', 'POST', $preparedFilters);
    }

    protected function getDBEntityIds() {
        $oQuery = MLProduct::factory()->getList()->getQueryObject()->limit($this->iStart, $this->iShopwareCloudLimitPerPage);
        $this->iCount = $oQuery->getCount();
        $aEntities = array();
        foreach ($oQuery->getResult() as $entity) {
            if (!in_array($entity['ProductsId'], $aEntities)) {
                $aEntities[] = $entity['ProductsId'];
            }
        }
        return $aEntities;
    }

    protected function updateEntity($data) {
        MLDatabase::getDbInstance()->query(
            'DELETE P FROM `magnalister_products` P WHERE P.`ProductsId` IN("' . implode('","', $data) . '")'
            , true
        );
        $this->showD("deleted from magnalister_products:" . MLDatabase::getDbInstance()->affectedRows());
        foreach ([
                     MLDatabase::factory('ShopwareCloudCategoryRelation')->getTableName(),
                     MLDatabase::factory('ShopwareCloudProductData')->getTableName(),
                     MLDatabase::factory('ShopwareCloudProductTranslation')->getTableName(),
                     MLDatabase::factory('ShopwareCloudProductPrice')->getTableName(),
                     MLDatabase::factory('ShopwareCloudProductImages')->getTableName()
                 ] as $sTable) {
            MLDatabase::getDbInstance()->query(
                'DELETE T FROM `' . $sTable . '` T WHERE `ShopwareProductID` IN("' . implode('","', $data) . '")', true);
            $this->showD("deleted from $sTable:" . MLDatabase::getDbInstance()->affectedRows());
            $this->showD("deleted from $sTable:" . MLDatabase::getDbInstance()->getLastError());
        }

    }

    protected function removeAdditionalEntities() {
        $this->removeOrphanedVariants();
        $this->removeDuplicatedProduct();
    }

    /**
     * By deleting product, variant should be also deleted automatically,
     * it seems, always it doesn't work , so here it checks variant without parent, and it deletes them
     * It should be checked more later
     */
    protected function removeOrphanedVariants() {
        MLDatabase::getDbInstance()->query("
            DELETE v
            FROM `magnalister_products` v
            LEFT JOIN `magnalister_products` p ON v.ParentID=p.ID
            WHERE  v.ParentID != '0' AND p.ID IS NULL");
        $this->showHeaderAndFooter('Number of deleted variants: '.MLDatabase::getDbInstance()->affectedRows());
    }

    /**
     * Running same CRON simultaneously can generate duplicated product
     * it tries to remove these products
     */
    protected function removeDuplicatedProduct() {
        $aDuplicatedProductData = MLDatabase::getDbInstance()->fetchArray('
SELECT
    p.ID,
    p.ProductsSku,
    p.ProductsId
FROM
    `magnalister_products` p
INNER JOIN(
    SELECT
        ProductsSku,
        ProductsId
    FROM
        magnalister_products
    GROUP BY
        ProductsSku,
        ProductsId,
        ParentId
    HAVING
        COUNT(ProductsSku) > 1 AND COUNT(ProductsId) > 1 AND ParentId = 0
) d
ON
    p.ProductsId = d.ProductsId AND p.ProductsSku = d.ProductsSku
ORDER BY
    p.ProductsSku,
    p.ProductsId,
    p.ID
DESC
    ');
        $aDeletingProducts = [];
        $iNumberOfDeletingProduct = 0;
        if (is_array($aDuplicatedProductData)) {
            $iID = null;
            $sProductsSku = null;
            $sProductsId = null;
            foreach ($aDuplicatedProductData as $aRow) {
                if ($sProductsSku === $aRow['ProductsSku'] && $sProductsId === $aRow['ProductsId']) {//next duplicated product
                    $iNumberOfDeletingProduct++;
                    $aDeletingProducts[$iID] = $aRow['ID'];
                } else {//First duplicated product
                    $iID = $aRow['ID'];
                    $sProductsSku = $aRow['ProductsSku'];
                    $sProductsId = $aRow['ProductsId'];
                }
            }
        }
        $this->showHeaderAndFooter(' Deleting duplicated products: '.$iNumberOfDeletingProduct);

        $this->deleteProductSafely($aDeletingProducts);
        $this->outLabel('Deleting products');
        $this->out(json_indent(json_encode($aDeletingProducts)));
    }

    public function deleteProductSafely(array $aDeletingProducts) {
        if (count($aDeletingProducts) > 0) {
            $aTables = $this->getPrepareTablesInfo();
            foreach ($aDeletingProducts as $aCorrectProductID => $aDeleteProductID) {
                $aDeleteVariations = MLDatabase::getDbInstance()->fetchArray("SELECT * FROM magnalister_products WHERE parentid = ".$aDeleteProductID);
                $aCorrectVariation = MLDatabase::getDbInstance()->fetchArray("SELECT * FROM magnalister_products WHERE parentid = ".$aCorrectProductID);
                $this->logCurrentDuplicatedProduct($aDeleteProductID, $aDeleteVariations[0]['ID']);

                if (count($aDeleteVariations) == 0) {//delete duplicated product without variation
                    $this->safeDelete($aDeleteProductID);
                } else {//delete duplicated variation product
                    foreach ($aDeleteVariations as $aDeleteVariation) {
                        foreach ($aTables as $sTable => $sColumn) {
                            if (MLDatabase::getDbInstance()->recordExists($sTable, [$sColumn => $aDeleteVariation['ID']])) {
                                $this->aCannotBeDeleted[$aDeleteProductID][] = $aDeleteVariation['ID'];
                                break;
                            }
                        }
                        if (!isset($this->aCannotBeDeleted[$aDeleteProductID]) || !in_array($aDeleteVariation['ID'], $this->aCannotBeDeleted[$aDeleteProductID])) {
                            $this->safeDelete($aDeleteVariation['ID']);
                        }
                    }
                    $this->deleteParentWithoutChild($aDeleteProductID);

                }

                $this->outLabel('Deleted');
                $this->out(json_indent(json_encode($this->aDeleted)));
            }
            if (count($this->aCannotBeDeleted) > 0) {
                $this->outLabel('Cannot be deleted');
                $this->out(json_indent(json_encode($this->aCannotBeDeleted)));
                $this->developerNotification('Removing duplicated product',
                    'Shop URL: '.MLHttp::gi()->getBaseUrl().'<br>'.
                    'Deleted: <br>'.json_indent(json_encode($this->aDeleted)).
                    '<br><br>Cannot be deleted Deleted: <br>'.json_indent(json_encode($this->aCannotBeDeleted)));
            }
        }
    }

    protected function safeDelete($iId) {
        if ($iId) {
            $backup = MLDatabase::getDbInstance()->fetchArray('SELECT * FROM magnalister_products WHERE ID = '.$iId);
            MLLog::gi()->add('deleted_magnalister_products', $backup);
            MLDatabase::getDbInstance()->delete('magnalister_products', array('ID' => $iId));
            $this->aDeleted[] = $iId;
        }
    }

    /**
     * @return array
     * {
     *     "magnalister_amazon_prepare": "ProductsID",
     *     "magnalister_cdiscount_prepare": "products_id",
     *     "magnalister_ebay_prepare": "products_id",
     *     "magnalister_etsy_prepare": "products_id",
     *     "magnalister_idealo_prepare": "products_id",
     *     "magnalister_ricardo_prepare": "products_id"
     * }
     */
    protected function getPrepareTablesInfo(): array {
        $aTables = [];
        $aAllTables = MLDatabase::getDbInstance()->fetchArray('show tables', true);
        foreach ($aAllTables as $sTable) {
            $aT = explode('_', $sTable);
            if (count($aT) == 3 && $aT[0] == 'magnalister' && $aT[2] == 'prepare') {
                $aT = explode('_', $sTable);
                if (count($aT) == 3 && $aT[0] == 'magnalister' && $aT[2] == 'prepare') {
                    $sColumn = null;
                    if (MLDatabase::getDbInstance()->columnExistsInTable('products_id', $sTable)) {
                        $sColumn = 'products_id';
                    } else if ($sTable == 'magnalister_amazon_prepare' && MLDatabase::getDbInstance()->columnExistsInTable('ProductsID', $sTable)) {
                        $sColumn = 'ProductsID';
                    }
                    if ($sColumn !== null) {
                        $aTables[$sTable] = $sColumn;
                    }
                }
            }
        }
        $this->outLabel('Prepare Data');
        $this->out(json_indent(json_encode($aTables)));
        return $aTables;
    }

    protected function outLabel($sLabel) {
        $this->out("\n\n$sLabel\n");
    }

    /**
     * @param $aDeleteProductID
     */
    protected function deleteParentWithoutChild($aDeleteProductID): void {
        $backup = MLDatabase::getDbInstance()->fetchArray('SELECT * FROM magnalister_products WHERE ID = '.$aDeleteProductID);

        MLDatabase::getDbInstance()->query('DELETE p
FROM
    `magnalister_products` p
LEFT JOIN `magnalister_products` v ON p.ID = v.ParentId
WHERE p.ParentId = 0 AND v.ID IS NULL AND p.ID='.(int)$aDeleteProductID);
        if (MLDatabase::getDbInstance()->affectedRows() > 0) {
            MLLog::gi()->add('deleted_magnalister_products', $backup);
        }
    }

    /**
     * @param $aDeleteProductID
     * @param $ID
     */
    protected function logCurrentDuplicatedProduct($aDeleteProductID, $ID): void {
        $this->outLabel('Product ID');
        $this->out(json_indent(json_encode($aDeleteProductID)));
        $this->outLabel('Variation ID');
        $this->out(json_indent(json_encode($ID)));
    }
}
