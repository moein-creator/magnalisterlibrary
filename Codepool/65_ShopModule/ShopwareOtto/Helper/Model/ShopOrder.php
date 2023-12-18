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

MLFilesystem::gi()->loadClass('Shopware_Helper_Model_ShopOrder');

class ML_ShopwareOtto_Helper_Model_ShopOrder extends ML_Shopware_Helper_Model_ShopOrder {

    public function updateAnnouncedOrder($updateProductQuantity = false) {
        // update order status
        try {
            Shopware()->Models()->clear();
            Zend_Session::$_unitTestEnabled = true; //if it is not true , it make problem in session creation in frontend url call
            $oOrder = $this->oOrder;
            if (is_object($this->oCurrentOrder)) {
                $oShopwareOrder = $this->oCurrentOrder;
                //updating order status
                $iNewOrderStatus = (int)$oOrder->get('status');
                if ($iNewOrderStatus !== $oShopwareOrder->getOrderStatus()->getId()) {
                    Shopware()->Modules()->Order()->setOrderStatus($oShopwareOrder->getId(), $iNewOrderStatus, false);
                }
            }
        } catch (Exception $oExc) {
            MLLog::gi()->add(MLSetting::gi()->get('sCurrentOrderImportLogFileName'), array(
                'MOrderId'  => MLSetting::gi()->get('sCurrentOrderImportMarketplaceOrderId'),
                'PHP'       => get_class($this).'::'.__METHOD__.'('.__LINE__.')',
                'Exception' => $oExc->getMessage()
            ));
        }

        // update stock of products
        if ($updateProductQuantity) {
            /** @var ML_Shopware_Model_Product $oProduct */
            $oProduct = MLProduct::factory();
            foreach ($this->aNewData['Products'] as $product) {
                if ($oProduct->getByMarketplaceSKU($product['SKU'])->exists()
                    && MLModul::gi()->getConfig('stocksync.frommarketplace') === 'rel') {
                    $oProduct->setStock($oProduct->getStock() + $product['Quantity']);
                }
            }
        }
    }

    /**
     * Checks if we need to create custom fields when trucking number is configured 'auto'
     *
     * @param $orderId
     * @return void
     */
    protected function addCustomFields($orderId) {
        if (MLModul::gi()->getConfig('customfieldtrackingnumber') === 'auto') {
            $usedAttributeColumns = $this->getUsedAttributeColumns();
            $returnTrackingKeyExists = false;

            foreach ($usedAttributeColumns as $used) {
                if ($used['label'] == 'Return Tracking Key') {
                    $returnTrackingKeyExists = true;
                }
            }

            if (!$returnTrackingKeyExists) {
                $this->createCustomFieldsInShopwareDb('Return Tracking Key');
            }
        }
    }

    /**
     * Tries to create custom attribute for 'Return Carrier' or 'Return Carrier Key'
     *
     * @param $field
     * @return void
     * @throws Exception
     */
    private function createCustomFieldsInShopwareDb($field) {
        $columnSql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 's_order_attributes' AND COLUMN_TYPE = 'text'";
        $columns = MLDatabase::getDbInstance()->fetchArray($columnSql, true);

        $usedAttributeColumns = $this->getUsedAttributeColumns();
        $usedColumns = array();

        foreach ($usedAttributeColumns as $used) {
            array_push($usedColumns, $used['column_name']);
        }

        $unused = array_values(array_diff($columns, $usedColumns));
        if (count($unused) > 0) {
            $data = array(
                'table_name' => 's_order_attributes',
                'column_name' => $unused[0],
                'column_type' => 'text',
                'position' => 0,
                'translatable' => 0,
                'display_in_backend' => 1,
                'custom' => 0,
                'label' => $field,
                'readonly' => 0
            );
            MLDatabase::getDbInstance()->insert('s_attribute_configuration', $data);
        } else {
            throw new Exception("Error Processing Request. Can't create custom field.", 1);
        }
    }

    /**
     * Returns all used columns on 's_order_attributes' table
     *
     * @return array
     */
    private function getUsedAttributeColumns() {
        $sql = "SELECT column_name, label FROM s_attribute_configuration WHERE table_name = 's_order_attributes'";
        return MLDatabase::getDbInstance()->fetchArray($sql, true);
    }
}
