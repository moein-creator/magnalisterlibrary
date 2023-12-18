<?php
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
 * (c) 2010 - 2020 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */


MLFilesystem::gi()->loadClass('Core_Update_Abstract');

/**
 * check if magnalister_product is created wrongly
 */
class ML_Shopify_Update_ShopifyDeleteWrongProduct extends ML_Core_Update_Abstract {

    protected $aWrongProductIds = array();


    /**
     * check, if update is needed
     * @return boolean
     */
    public function needExecution() {
        $aWrongProductArray = MLDatabase::getDbInstance()->fetchArray("
                SELECT
                    p.`ProductsId`  
                FROM
                    `magnalister_products` p  
                WHERE `ParentId` <> 0
                group by
                    p.`ProductsId` 
                having
                    count(*)>1
                ");
        //            MLMessage::gi()->addDebug(__LINE__.':'.microtime(true), array($aWrongProductArray));
        if (!empty($aWrongProductArray)) {
            foreach ($aWrongProductArray as $aProduct) {
                $this->aWrongProductIds[] = $aProduct['ProductsId'];
            }
            return true;
        }
        return false;
    }

    public function execute() {
        $aTables = $this->getPrepareTablesInfo();
        $aWrongProductIDs = MLDatabase::getDbInstance()->fetchArray("
                SELECT
                    p.`ID`, p.`ParentId` 
                FROM
                    `magnalister_products` p                  
                WHERE `ProductsId` IN (".implode(',', $this->aWrongProductIds).")
                ");
        $aWrongIDParent = array();
        foreach ($aWrongProductIDs as $aRow) {
            $aWrongIDParent[$aRow['ID']] = $aRow['ParentId'];
        }
        $aWrongIDs = array_keys($aWrongIDParent);
        if (!empty($aWrongIDs)) {
            //Excluding product ids are already prepared
            foreach ($aTables as $sTable => $sColumn) {
                $aPreparedProductIds = MLDatabase::getDbInstance()->fetchArray("
                SELECT
                    `".MLDatabase::getDbInstance()->escape($sColumn)."`
                FROM
                    `".MLDatabase::getDbInstance()->escape($sTable)."`
                WHERE
                    `".MLDatabase::getDbInstance()->escape($sColumn)."`  IN (".implode(',', $aWrongIDs).")
                ");
                $aPreparedProductIds = array_column($aPreparedProductIds, 'ProductsId');
                $aWrongIDs = array_diff($aWrongIDs, $aPreparedProductIds);
            }

            // For now in no case was necessary to remove parent product, if yes we should uncomment these lines
            //        if(!empty($aWrongIDs)){
            //            $aParentIds = array();
            //            foreach ($aWrongIDs as $sId){
            //                $aParentIds[]=$aWrongIDParent[$sId];
            //            }
            //            $aParentIds = array_unique($aParentIds);
            ////            MLMessage::gi()->addDebug(__LINE__.':'.microtime(true), array($aParentIds));
            //            MLDatabase::getDbInstance()->query('DELETE FROM `magnalister_products` WHERE ID IN ('.implode(',', $aParentIds).') AND ParentID = 0');
            //        }

            //        MLMessage::gi()->addDebug(__LINE__.':'.microtime(true), array($aWrongIDs));
            MLDatabase::getDbInstance()->query('DELETE FROM `magnalister_products` WHERE ID IN ('.implode(',', $aWrongIDs).')');
        }
    }

    protected function getPrepareTablesInfo() {
        $aTables = array();
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
        return $aTables;
    }

}
