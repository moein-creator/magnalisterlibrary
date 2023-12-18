<?php

MLFilesystem::gi()->loadClass('Core_Update_Abstract');
/**
 * check if magnalister_product is created wrongly
 */
class ML_Shopware_Update_ShopwareDeleteWrongProduct extends ML_Core_Update_Abstract {
    
    protected $aWrongProduct=null;


    /**
     * check, if update is needed
     * @return boolean
     */
    public function needExecution() {
        if (MLDatabase::getDbInstance()->fetchOne("SELECT value FROM magnalister_config WHERE mkey = 'general.keytype'") === 'artNr') {
            $aWrongProductArray = MLDatabase::getDbInstance()->fetchArray("
            SELECT * FROM `magnalister_products`
                WHERE ProductsSku in ( 
                    SELECT ProductsSku from (
                        SELECT p2.parentid,p2.`ProductsSku` FROM `magnalister_products` p2 where p2.parentid = 0 group by p2.`ProductsSku` HAVING count(p2.`ProductsSku`)>1 
                    ) AS p3 
                ) AND ParentId = 0 ORDER BY ProductsSku, ID DESC");

            if (!empty($aWrongProductArray)) {
                foreach ($aWrongProductArray as $aProduct) {
                    $this->aWrongProduct[trim($aProduct['ProductsSku'])][] = $aProduct;
                }
                return true;
            }
        }
        return false;
    }

    public function execute() {
        foreach ($this->aWrongProduct as $aProduct) {
            if (count($aProduct) == 2) {
                $aCorrectProduct = $aProduct[0];
                $aDeleteProduct = $aProduct[1];
                $aDeleteVariation = MLDatabase::getDbInstance()->fetchArray("SELECT * FROM magnalister_products WHERE parentid = " . $aDeleteProduct['ID']);
                $aCorrectVariation = MLDatabase::getDbInstance()->fetchArray("SELECT * FROM magnalister_products WHERE parentid = " . $aCorrectProduct['ID']);
                if (count($aCorrectVariation) == 0) {//delete product without variation
                    $this->safeDelete($aCorrectProduct['ID']);
                } else if (count($aDeleteVariation) == 0) {//delete product without variation
                    $this->safeDelete($aDeleteProduct['ID']);
                } else {



                    $aMap = array();
                    foreach ($aCorrectVariation as $aCv) {
                        $aMap[$aCv['MarketplaceIdentSku']]['correct'] = $aCv['ID'];
                    }
                    foreach ($aDeleteVariation as $aDv) {
                        $aMap[$aDv['MarketplaceIdentSku']]['delete'] = $aDv['ID'];
                    }


                    $aTables = $this->getPrepareTablesInfo();

                    $aCountOfDelete = array();
                    $aTableFoundItem = array();
                    foreach ($aMap as $sVarSku => $aVar) {
                        $aCountOfDelete[$sVarSku] = 0;
                        foreach ($aTables as $sTable => $sColumn) {
                            $aCorrectPrepare = $aDeletePrepare = array();
                            if (isset($aVar['correct'])) {
                                $aCorrectPrepare = MLDatabase::getDbInstance()->fetchArray('select ' . $sColumn . ', mpid, PreparedTS from ' . $sTable . ' where ' . $sColumn . ' = ' . $aVar['correct']);
                                if (!is_array($aCorrectPrepare) || count($aCorrectPrepare) == 0) {
                                    $aCorrectPrepare[]['id'] = $aVar['correct'];
                                }
                            }
                            if (isset($aVar['delete'])) {
                                $aDeletePrepare = MLDatabase::getDbInstance()->fetchArray('select ' . $sColumn . ', mpid, PreparedTS  from ' . $sTable . ' where ' . $sColumn . ' = ' . $aVar['delete']);
                                $aCountOfDelete[$sVarSku] += count($aDeletePrepare);
                            }
                            if ((is_array($aCorrectPrepare) && count($aCorrectPrepare) > 0) || (is_array($aDeletePrepare) && count($aDeletePrepare) > 0)) {
                                $aTableFoundItem[$sTable][$sVarSku]['correct'] = $aCorrectPrepare;
                                $aTableFoundItem[$sTable][$sVarSku]['delete'] = $aDeletePrepare;
                            }
                        }
                    }
                    $aUpdates = array();
                    $aPrepareDeletes = array();
                    foreach ($aMap as $sVarSku => $aVar) {
                        if (isset($aCountOfDelete[$sVarSku]) && $aCountOfDelete[$sVarSku] > 0) {
                            foreach ($aTables as $sTable => $sColumn) {
                                $aCurrentFound = $aTableFoundItem[$sTable][$sVarSku];
                                if (is_array($aCurrentFound['delete'])) {
                                    foreach ($aCurrentFound['delete'] as $aDeleteItem) {
                                        $aDeleteItem = array_values($aDeleteItem);
                                        foreach ($aCurrentFound['correct'] as $aCorrectItem) {
                                            $aCorrectItem = array_values($aCorrectItem);
                                            if ( (empty($aCorrectItem[1]) || $aCorrectItem[1] == $aDeleteItem[1]) && !empty($aDeleteItem[2]) && (empty($aCorrectItem[2]) || strtotime($aDeleteItem[2]) > strtotime($aCorrectItem[2]))) {//compare mpid
                                                $backup  = MLDatabase::getDbInstance()->fetchArray('select *  from ' . $sTable . ' where ' . $sColumn . ' = ' . $aCorrectItem[0].' AND mpid = '. $aDeleteItem[1]);
                                                MLLog::gi()->add('deleted_'.$sTable, $backup);
                                                $aPrepareDeletes[] = array($sTable, array($sColumn => $aCorrectItem[0], 'mpid' => $aDeleteItem[1]));
                                                $aUpdates[] = array($sTable, array($sColumn => $aCorrectItem[0]), array($sColumn => $aDeleteItem[0], 'mpid' => $aDeleteItem[1]));
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

                    foreach ($aMap as $sVarSku => $aVar) {
                        $this->safeDelete($aVar['delete']);
                    }
                    $this->safeDelete($aDeleteProduct['ID']);
                    
                    foreach ($aPrepareDeletes as $aPrepareDelete) {
                        MLDatabase::getDbInstance()->delete($aPrepareDelete[0], $aPrepareDelete[1]);
                    }
                    foreach ($aUpdates as $aUpdate) {
                        MLDatabase::getDbInstance()->update($aUpdate[0], $aUpdate[1],$aUpdate[2]);
                    }
                }
            }
        }
        MLMessage::gi()->addDebug('preparetable',$aTableFoundItem);
    }

    protected function safeDelete($id) {
        if ($id) {
            $backup = MLDatabase::getDbInstance()->fetchArray('select * from magnalister_products where ID = ' . $id);
            MLLog::gi()->add('deleted_magnalister_products', $backup);
            MLDatabase::getDbInstance()->delete('magnalister_products', array('ID' => $id));
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
