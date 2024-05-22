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

use Shopify\API\Application\Application;
use Shopify\API\Application\Request\Products\NotExistingProducts\NotExistingProductParams;

MLFilesystem::gi()->loadClass('Shopify_Controller_Frontend_Do_ShopifyProductCache');

class ML_Shopify_Controller_Frontend_Do_ShopifyDeleteProductCache extends ML_Shopify_Controller_Frontend_Do_ShopifyProductCache {

    /**
     * Starting point for get product list via sql query
     * @var int
     */
    protected $iStart;

    /**
     * Count of row in current sql query
     * @var int
     */
    protected $iCount;

    /**
     * Limit for get product list via sql query
     * @var int
     */
    protected $iLimit = 200;

    protected $sType = 'ProductDelete';

    protected $iLimitationOfIteration = 10;
    private $aDeleted = [];
    private $aCannotBeDeleted = [];

    /**
     * @return int
     */
    protected function getPage() {
        if ($this->iCurrentPage === null) {
            $this->iCurrentPage = (int)MLDatabase::factory('config')->set('mpid', 0)->set('mkey', 'shopifyDeleteProductPage')->get('value');
        }
        return $this->iCurrentPage + $this->iNumberOfRepeat;
    }

    protected function getUpdatedProductsData() {
        $this->iStart = ($this->getPage() - 1) * $this->iLimit;
        $oQuery = MLProduct::factory()->getList()->getQueryObject()->limit($this->iStart, $this->iLimit)->where('`ParentId` = 0');
        $this->iCount = $oQuery->getCount();
        $application = new Application(MLShopifyAlias::getShopHelper()->getShopId());
        $oParams = new NotExistingProductParams();
        $aProducts = [];
        foreach ($oQuery->getResult() as $aProduct) {
            $aProducts[$aProduct['ID']] = $aProduct['ProductsId'];
        }
        $oParams->setProductIds($aProducts);
        $aData = $application->getProductRequest()->notExistingProduct($oParams)->getBodyAsArray()['data'];
        return array_filter($aData, static function ($mItem) {
            return $mItem === null;
        });
    }

    public function execute() {
        $iStartTime = microtime(true);

        while ($this->iNumberOfRepeat < $this->iLimitationOfIteration) {
            $this->showHeaderAndFooter('Shopify deleting product cache');
            try {
                $aProducts = $this->getUpdatedProductsData();
                $aMLIds = array();
                foreach ($aProducts as $sKey => $mValue) {
                    $aMLIds[] = substr($sKey, 1);
                }
                if (count($aMLIds) > 0) {
                    try {
                        MLDatabase::getDbInstance()->query(
                            '
DELETE PCR,P
FROM `magnalister_products` P 
LEFT JOIN `magnalister_shopify_product_collection_relation` PCR  ON PCR.ShopifyProductID = P.MarketplaceIdentId  WHERE P.ID IN('.implode(',', $aMLIds).')'
                        );

                        $this->out(' magnalister Product ids: '.implode(',', $aMLIds)." are deleted\n");
                    } catch (\Exception $oEx) {
                        $this->out(' A problem occurred by deleting magnalister product ids: '.implode(',', $aMLIds)."\n".$oEx->getMessage()."\n".$oEx->getTraceAsString());
                        MLMessage::gi()->addDebug($oEx);
                    }
                } else {
                    $this->out(' There is no product to be deleted'."\n");

                }
                if ($this->iStart + $this->iLimit > $this->iCount) {
                    $this->showHeaderAndFooter('Shopify deleting process is done');
                    MLDatabase::factory('config')->set('mpid', 0)->set('mkey', 'shopifyDeleteProductPage')->set('value', 0)->save();
                    break;
                } else {
                    $this->showHeaderAndFooter('Shopify deleting page '.$this->getPage().' was successful. Next page will be proceeded in next call automatically');
                    MLDatabase::factory('config')->set('mpid', 0)->set('mkey', 'shopifyDeleteProductPage')->set('value', $this->getPage())->save();
                }

            } catch (Exception $oEx) {
                $this->out($oEx->getMessage()."\n");
            }

            $this->iNumberOfRepeat++;
        }

        $this->removeOrphanedVariants();
        $this->removeOrphanedCollections();
        $this->removeDuplicatedProduct();

        $this->out("\n\nComplete (".microtime2human(microtime(true) - $iStartTime).").\n");
    }

    /**
     * @param $sStr string
     * @return ML_Shopify_Controller_Frontend_Do_ShopifyProductCache|void
     */
    protected function out($sStr) {
        echo $sStr;
        $this->oLogger->addLog($sStr);
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
     * By deleting product, collection with no relation with products should be also deleted automatically
     */
    protected function removeOrphanedCollections() {

        $existingCollection = MLDatabase::getDbInstance()->fetchArray("
            SELECT c.`ShopifyCollectionID`
            FROM `" . MLShopifyAlias::getCollectionModel()->getTableName() . "` c
            LEFT JOIN `" . MLShopifyAlias::getProductCollectionRelationModel()->getTableName() . "` pcr ON pcr.`ShopifyCollectionID`=c.`ShopifyCollectionID`
            LEFT JOIN `magnalister_products` p ON p.`ProductsId`= pcr.`ShopifyProductID`
            WHERE  p.`ProductsId` IS NOT NULL", true);
        $probablyDeletedCollection = MLDatabase::getDbInstance()->fetchArray("
            SELECT c.`ShopifyCollectionID`
            FROM `" . MLShopifyAlias::getCollectionModel()->getTableName() . "` c
            LEFT JOIN `" . MLShopifyAlias::getProductCollectionRelationModel()->getTableName() . "` pcr ON pcr.`ShopifyCollectionID`=c.`ShopifyCollectionID`
            LEFT JOIN `magnalister_products` p ON p.`ProductsId`= pcr.`ShopifyProductID`
            WHERE  p.`ProductsId` IS NULL", true);
        $diff = array_unique(array_diff($probablyDeletedCollection, $existingCollection));
        if (count($diff) > 0) {
            MLDatabase::getDbInstance()->query("
            DELETE c
            FROM `" . MLShopifyAlias::getCollectionModel()->getTableName() . "` c
            WHERE  c.`ShopifyCollectionID` IN ('" . implode("', '", $diff) . "')", true);
        }
        $this->showHeaderAndFooter('$probablyDeletedCollection: ' . json_encode($probablyDeletedCollection, true));
        $this->showHeaderAndFooter('$existingCollection: ' . json_encode($existingCollection, true));
        $this->showHeaderAndFooter('Diff: ' . json_encode($diff, true));
        $this->showHeaderAndFooter('Number of deleted collections: ' . MLDatabase::getDbInstance()->affectedRows());
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
