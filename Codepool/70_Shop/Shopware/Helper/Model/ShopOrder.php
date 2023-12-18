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

class ML_Shopware_Helper_Model_ShopOrder {

    /**
     * @var Enlight_Components_Db_Adapter_Pdo_Mysql
     */
    protected $oShopwareDB = null;

    /**
     * @var int $iCustomerId
     */
    protected $iCustomerId = null;

    /**
     * @var array $aCurrentData
     */
    protected $aCurrentData = array();

    /**
     * @var \Shopware\Models\Order\Order $oCurrentOrder
     */
    protected $oCurrentOrder = null;

    /**
     * @var array $aNewData
     */
    protected $aNewData = array();

    /**
     * @var array $aNewProduct
     */
    protected $aNewProduct = array();

    /**
     * @var \Shopware\Models\Order\Order $oNewOrder
     */
    protected $oNewOrder = null;

    /**
     * @var ML_Shopware_Model_Order $oOrder
     */
    protected $oOrder = null;

    /**
     * @var ML_Shopware_Model_Price $oPrice
     */
    protected $oPrice = null;

    /**
     * @var bool
     */
    protected $blVersionGreaterThan52 = false;

    /**
     * need just for order update, to know if address needs to be updated
     * @var boolean
     */
    protected $blNewAddress = true;

    /**
     * useful in rollback order number in shopware >= 5.1.5
     * @var string
     */
    protected $sOrderNumber = null;

    /**
     * Useful when inserting products for order when extending the function addProductToOrder()
     * @var string
     */
    protected $sCurrentOrderDetailId = null;

    /*
     * construct
     */
    public function __construct() {
        $this->oPrice = MLPrice::factory();
        $version = Shopware()->Config()->version;
        if (empty($version)) {
            $version = MLSHOPWAREVERSION;
        }
        $this->blVersionGreaterThan52 = version_compare($version, '5.2', '>=');
    }

    /**
     * @return Enlight_Components_Db_Adapter_Pdo_Mysql
     */
    protected function getShopwareDb() {
        if ($this->oShopwareDB === null) {
            $this->oShopwareDB = Shopware()->Db();
        }
        return $this->oShopwareDB;
    }

    /**
     * get configured shop from module configuration and set oShop object
     *  if there comes a error: Fatal error:
     *      Call to a member function toArray() on a non-object in
     *      ./engine/Shopware/Plugins/Default/Core/System/Bootstrap.php on line 95
     *  perhaps we need to init doctrine "Shopware()->Models()->Clear()"
     *      $system->sCurrency = $shop->getCurrency()->toArray(); //in this case $shop->getCurrency() === null
     */
    public function selectShop() {
        $oModul = MLModule::gi();
        $iShopId = $oModul->getConfig('orderimport.shop');
        if ($iShopId === null) {
            $iShopId = $oModul->getConfig('lang');
        }
        $oShop = Shopware()->Models()->getRepository('Shopware\Models\Shop\Shop')->find($iShopId);
        // Shopware 5.7 compatiblity
        if (version_compare(MLSHOPWAREVERSION, '5.7', '>=')) {
            Shopware()->Container()->set('shop', $oShop);
        } else {
            Shopware()->Bootstrap()->registerResource('Shop', $oShop);
        }
    }

    /**
     * Used to get ShopId from config and use as fallback of current loaded shop
     *
     * @return array|mixed|null
     */
    private function getShopwareShopId() {
        $oModul = MLModule::gi();
        $iShopId = $oModul->getConfig('orderimport.shop');
        if ($iShopId === null) {
            $iShopId = $oModul->getConfig('lang');
        }
        // as fallback use the current of loaded shop
        if ($iShopId === null) {
            $iShopId = Shopware()->Shop()->getId();
        }
        return $iShopId;
    }

    /**
     * Sql method for extended logging
     * @param string $sQuery
     * @return bool
     * @throws Exception rethrow Exception
     */
    protected function executeSql($sQuery, $aArray = array()) {
        try {
            if (MLSetting::gi()->data('blOrderImportSqlLogActivated')) {//ever developer can activate it if it is needed
                MLLog::gi()->add(MLSetting::gi()->get('sCurrentOrderImportLogFileName'), array(
                    'MOrderId' => MLSetting::gi()->get('sCurrentOrderImportMarketplaceOrderId'),
                    'PHP'      => get_class($this).'::'.__METHOD__.'('.__LINE__.')',
                    'Query'    => array($sQuery => $aArray)
                ));
            }
            $this->getShopwareDb()->query($sQuery, $aArray);
            return true;
        } catch (Exception $oEx) {
            MLLog::gi()->add(MLSetting::gi()->get('sCurrentOrderImportLogFileName'), array(
                'MOrderId'  => MLSetting::gi()->get('sCurrentOrderImportMarketplaceOrderId'),
                'PHP'       => get_class($this).'::'.__METHOD__.'('.__LINE__.')',
                'Exception' => $oEx->getMessage(),
                'Query'     => array($sQuery => $aArray),
            ));
            throw $oEx;
        }
    }

    /**
     * set new order data
     */
    public function setNewOrderData($aData) {
        $this->aNewData = is_array($aData) ? $aData : array();
        return $this;
    }


    /**
     * set oder object in initializing the order helper
     * @param ML_Shopware_Model_Order $oOrder
     * @return \ML_Shopware_Helper_Model_ShopOrder
     */
    public function setOrder($oOrder) {
        $this->blNewAddress = true;
        $this->oOrder = $oOrder;
        $this->oCurrentOrder = null;
        $this->iCustomerId = null;
        $this->sOrderNumber = null;
        $this->sCurrentOrderDetailId = null;
        if ($this->oOrder->exists()) {
            $this->oCurrentOrder = Shopware()->Models()->getRepository('\Shopware\Models\Order\Order')->find($oOrder->get('current_orders_id'));
        }
        $this->aCurrentData = $oOrder->get('orderdata');
        return $this;
    }

    /**
     * @return $this
     * @throws Zend_Db_Adapter_Exception
     */
    public function rollBackIncrementedOrderNumber() {
        if (!empty($this->sOrderNumber)
            && is_numeric($this->sOrderNumber) //some users use marketplace order id as Shopware order id
        ) {
            $iCount = MLDatabase::factorySelectClass()->from(Shopware()->Models()->getClassMetadata('\Shopware\Models\Order\Order')->getTableName())
                ->where("CONVERT(`ordernumber`, SIGNED INTEGER) >= ".$this->sOrderNumber."")->getCount();
            if ($iCount == 0) {
                $oDb = Shopware()->Db();
                try {
                    $mAutoCommit = $oDb->fetchOne("SELECT @@autocommit");
                    $oDb->query("SET autocommit = 0;");
                    $oDb->query("SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;");
                } catch (Exception $oEx) {
                    // no mysql - we don't care
                }
                $oDb->query("BEGIN");
                try {
                    $this->executeSql("UPDATE s_order_number SET number = number - 1 WHERE name='invoice'");
                    $oDb->query("commit");
                    try {
                        $oDb->query("SET autocommit = ".$mAutoCommit.";");
                    } catch (Exception $oEx) {
                        // no mysql - we don't care
                    }
                } catch (Exception $oEx) {
                    $oDb->query("rollback");
                    try {
                        $oDb->query("SET autocommit = ".$mAutoCommit.";");
                    } catch (Exception $oEx) {
                        // no mysql - we don't care
                    }
                }
            }
        }
        return $this;
    }

    /**
     * initializing order import and update
     * @return array
     * @throws Exception
     */
    public function shopOrder() {
        if (!is_array($this->aCurrentData) || count($this->aCurrentData) === 0) {
            $aReturnData = $this->createOrder();
        } elseif (!is_object($this->oCurrentOrder)) {// if order doesn't exist in shop  we create new order
            $this->aNewData = MLHelper::gi('model_service_orderdata_merge')->mergeServiceOrderData($this->aNewData, $this->aCurrentData, $this->oOrder);
            $aReturnData = $this->createOrder();
        } else {//update order if exist
            $this->iCustomerId = $this->oCurrentOrder->getCustomer()->getId();
            if ($this->checkForUpdate()) {
                $this->aNewData = MLHelper::gi('model_service_orderdata_merge')->mergeServiceOrderData($this->aNewData, $this->aCurrentData, $this->oOrder);
                return $this->updateOrder();
            } else {
                $this->aNewData = MLHelper::gi('model_service_orderdata_merge')->mergeServiceOrderData($this->aNewData, $this->aCurrentData, $this->oOrder);
                $aReturnData = $this->updateOrder(true);
            }
        }
        return $aReturnData;
    }

    /**
     * check if order should be updated or should we added or extended
     * @return boolean
     */
    protected function checkForUpdate() {
        if (count($this->aNewData['Products']) > 0) {
            return false;
        }
        foreach (array('Shipping', 'Billing') as $sAddressType) {//in shopware we use just shipping and billing address , we use just email of main address
            foreach (array('Gender', 'Firstname', 'Company', 'Street', 'Housenumber', 'Postcode', 'City', 'Suburb', 'CountryCode', 'Phone', 'EMail', 'DayOfBirth',) as $sField) {
                if ((isset($this->aNewData['AddressSets'][$sAddressType][$sField]) && !isset($this->aCurrentData['AddressSets'][$sAddressType][$sField]))
                    || (isset($this->aNewData['AddressSets'][$sAddressType][$sField]) && $this->aNewData['AddressSets'][$sAddressType][$sField] != $this->aCurrentData['AddressSets'][$sAddressType][$sField])
                ) {
                    return false;
                }
            }
        }
        $this->blNewAddress = false;
        foreach ($this->aNewData['Totals'] as $aNewTotal) {
            $blFound = false;
            foreach ($this->aCurrentData['Totals'] as $aCurrentTotal) {
                // we need to check if shipping is may the same as before - because value from api is different to calculated value in shop
                // ToDo: check Shopify ShopOrder Helper for "orgValue" in Shipping Total
                if ($aNewTotal['Type'] == $aCurrentTotal['Type']) {
                    $blFound = true;
                    if ((float)$aCurrentTotal['Value'] != (float)$aNewTotal['Value']
                        //|| // we don't need to compare the Tax , because it is false in ebay and most of the marketplaces
                        //(float) $aCurrentTotal['Tax'] != (float) $aNewTotal['Tax']
                    ) {
                        return false;
                    }
                }
            }
            if (!$blFound) {
                return false;
            }
        }
        return true;
    }

    /**
     * get random number as transaction id , we have this function individually because some customer need to change this behavior by overriding this function
     * @return string
     */
    protected function getTransactionId() {
        $aPayment = $this->getTotal('Payment');
        if (/*isset($aPayment['Code']) && $aPayment['Code'] == 'PayPal' && *///we cannot check for Code because it is already changed in normalize class
            isset($aPayment['ExternalTransactionID']) && !empty($aPayment['ExternalTransactionID'])) {
            return $aPayment['ExternalTransactionID'];
        } else {
            return '';
        }
    }

    /**
     * update payment method
     * @throws Exception
     */
    protected function updatePaymentMethod() {
        $sSql = 'UPDATE `s_order` SET';
        $aPayment = $this->getTotal('Payment');
        if (/*isset($aPayment['Code']) && $aPayment['Code'] == 'PayPal' && *///we cannot check for Code because it is already changed in normalize class
            isset($aPayment['ExternalTransactionID']) && !empty($aPayment['ExternalTransactionID'])) {
            $sSql .= ' transactionID = '.$this->getShopwareDB()->quote($aPayment['ExternalTransactionID']).',';
        }

        if (MLDatabase::getDbInstance()->columnExistsInTable('changed', 's_order')) {
            $sSql .= '`changed` = NOW(),';
        }

        $sSql .= ' paymentID = '.$this->getPaymentMethod($aPayment['Code']).
            ' WHERE id = '.$this->oCurrentOrder->getId();

        $this->executeSql($sSql);
        return $this;
    }

    /**
     * update shipping method
     * @throws Exception
     */
    public function updateShippingMethod() {
        if (is_object($this->oCurrentOrder)) {//shipping can update from ML_ShopwareEbay_Model_Service_UpdateOrders so we should check existing of order
            $iDispatchId = $this->getDispatch();
            //updating shipping method
            $this->executeSql(
                'UPDATE `s_order` SET'.
                ' dispatchID = '.$iDispatchId.
                (MLDatabase::getDbInstance()->columnExistsInTable('changed', 's_order') ? ', `changed` = NOW()' : '').
                ' WHERE id = '.$this->oCurrentOrder->getId()
            );
            return $this;
        } else {
            throw new Exception('order doesn\'t exist in shop');
        }
    }

    /**
     * update payment status
     * @return \ML_Shopware_Helper_Model_ShopOrder
     * @throws Exception
     */
    public function updatePaymentStatus() {
        try {
            $oShopwareOrder = $this->oCurrentOrder;
            $iPaymentStatusId = $this->getPaymentStatus();
            //updating payment status
            if ((int)$iPaymentStatusId !== $oShopwareOrder->getPaymentStatus()->getId()) {
                Shopware()->Modules()->Order()->setPaymentStatus($oShopwareOrder->getId(), (int)$iPaymentStatusId, false);
            }
            $this->setPaidTime($oShopwareOrder->getId());
        } catch (Exception $oExc) {
            MLLog::gi()->add(MLSetting::gi()->get('sCurrentOrderImportLogFileName'), array(
                'MOrderId'  => MLSetting::gi()->get('sCurrentOrderImportMarketplaceOrderId'),
                'PHP'       => get_class($this).'::'.__METHOD__.'('.__LINE__.')',
                'Exception' => $oExc->getMessage()
            ));
        }
        return $this;
    }

    /**
     * update order status
     * @throws Exception
     */
    public function updateOrderStatus() {
        try {
            Shopware()->Models()->clear();

            // Shopware <= 5.6
            if (class_exists('\Zend_Session')) {
                Zend_Session::$_unitTestEnabled = true; //if it is not true , it make problem in session creation in frontend url call
            }
            $aData = $this->aNewData;
            if (is_object($this->oCurrentOrder)) {
                $oShopwareOrder = $this->oCurrentOrder;
                //updating order status
                $iNewOrderStatus = (int)$aData['Order']['Status'];
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
    }

    /**
     * this functions is only implemented to prevent errors for duplicated of code
     * @param $iOrderId
     * @param $sOrderNumber
     * @param $aData
     * @param $oShopwareModel
     * @throws Exception
     */
    protected function addProductsAndTotals($iOrderId, $sOrderNumber, &$aData, $oShopwareModel) {
        $fTotalTaxExcl = $fTotalTaxIncl = $fShippingTaxExcl = $fShippingTaxIncl = $fTotalPriceProductTaxIncl = $fTotalPriceProductTaxExcl = 0.00;
        $aDetails = array();
        if (is_object($this->oCurrentOrder)) {
            foreach ($this->oCurrentOrder->getDetails() as $oDetail) {
                $aDetails[$oDetail->getId()] = $oDetail;
            }
        }
        $fMaxTaxPercent = 0.00; // fallback if getMaxTaxRateFromProducts fails
        // add products
        if (count($aData['Products']) > 0) {
            foreach ($aData['Products'] as $aProduct) {
                $fPPrice = (float)$aProduct['Price'];
                $iPQty = (int)$aProduct['Quantity'];
                $fTotalTaxIncl += $fPPrice * $iPQty;
                $fTotalPriceProductTaxExcl += $fPPrice * $iPQty;
                $aProduct['Modus'] = 0;
                $blFound = false;
                if (!empty($aDetails)) {
                    foreach ($aDetails as $sKey => $oSWProduct) {
                        /* @var  $oSWProduct \Shopware\Models\Order\Detail */
                        if ($oSWProduct->getQuantity() == $iPQty
                            && ((float)$oSWProduct->getPrice() == (float)$aProduct['Price'])
                        ) {
                            if (trim($aProduct['SKU']) == '' && $oSWProduct->getArticleName() == $aProduct['ItemTitle']) {//in ebay sku could be empty
                                $blFound = true;
                            } elseif (trim($aProduct['SKU']) !== '') {
                                if ($oSWProduct->getArticleNumber() === $aProduct['SKU']) {
                                    $blFound = true;
                                } else {// perhaps sku have changed or wrong keytype?
                                    $oProduct = MLProduct::factory();
                                    $sSWProductNumber = $oSWProduct->getArticleNumber();
                                    if ($oProduct->getByMarketplaceSKU($aProduct['SKU'])->exists() && $oProduct->getSku() === $sSWProductNumber) {
                                        $blFound = true;
                                    }
                                }
                            }
                        }
                        if ($blFound) {
                            $fTaxPercent = $oSWProduct->getTaxRate();
                            if ($fTaxPercent > $fMaxTaxPercent)
                                $fMaxTaxPercent = $fTaxPercent;
                            unset($aDetails[$sKey]);
                            break;
                        }
                    }
                }
                if ($blFound) {
                    $fGros = (float)$aProduct['Price'];
                    $fProductPriceWithoutTax = round($this->oPrice->calcPercentages($fGros, null, (float)$fTaxPercent), 5);
                } else {
                    $fProductPriceWithoutTax = $this->addProductToOrder($iOrderId, $sOrderNumber, $aProduct, $aProduct['Quantity']);
                }
                $fTotalTaxExcl += $fProductPriceWithoutTax * $iPQty;
                $fTotalPriceProductTaxIncl += $fProductPriceWithoutTax * $iPQty;
            }
        }

        $aTaxInfo = $this->getMaxTaxRateFromProducts($iOrderId);
        $fMaxTaxRate = $aTaxInfo['Rate'];
        $fMaxTaxClassId = $aTaxInfo['ClassId'];
        if ($fMaxTaxRate < $fMaxTaxPercent)
            $fMaxTaxRate = $fMaxTaxPercent;

        // add orders totals
        foreach ($aData['Totals'] as &$aTotal) {
            switch ($aTotal['Type']) {
                case 'Shipping':
                {
                    $fShippingTaxIncl += (float)$aTotal['Value'];
                    $fTotalTaxIncl += $fShippingTaxIncl;
                    $aTotal['Tax'] = $fMaxTaxRate;
                    $fShippingPriceWithoutTax = $this->oPrice->calcPercentages($fShippingTaxIncl, null, $aTotal['Tax']);
                    $iNumberOfFractionalPart = MLSHOPWARE_VERSION >= 5 ? 4 : 2;
                    $fShippingTaxExcl += round($fShippingPriceWithoutTax, $iNumberOfFractionalPart);
                    $fTotalTaxExcl += round($fShippingPriceWithoutTax, $iNumberOfFractionalPart);
                    break;
                }
                case 'Payment':
                {
                    if ((float)$aTotal['Value'] !== 0.0) {
                        $sPaymentMethod = $aTotal['Type'].'_'.(isset($aTotal['Code']) ? $aTotal['Code'] : '');
                        if (isset($aTotal['Code']) && is_numeric($aTotal['Code'])) {
                            /** @var \Shopware\Models\Payment\Payment $oPayment */
                            $oPayment = $oShopwareModel->getRepository('Shopware\Models\Payment\Payment')->find($aTotal['Code']);
                            if (is_object($oPayment)) {
                                $sPaymentMethod = $oPayment->getDescription();
                            }
                        }
                        $fTotalTaxIncl += (float)$aTotal['Value'];
                        $fTotalPriceProductTaxExcl += (float)$aTotal['Value'];
                        $aTotal['Tax'] = $fMaxTaxRate;
                        $aTotal['TaxClassId'] = $fMaxTaxClassId;
                        $fProductPriceWithoutTax = $this->checkPayment($iOrderId, '', array(
                            'ItemTitle'  => $sPaymentMethod,
                            'SKU'        => '',
                            'Price'      => $aTotal['Value'],
                            'Tax'        => $aTotal['Tax'],
                            'TaxClassId' => $aTotal['TaxClassId'],
                            'Data'       => isset($aTotal['Data']) ? $aTotal['Data'] : array(),
                            'Quantity'   => 1,
                            'Modus'      => 4,
                        ));
                        $fTotalTaxExcl += $fProductPriceWithoutTax;
                        $fTotalPriceProductTaxIncl += $fProductPriceWithoutTax;
                    }
                    break;
                }
                default:
                {
                    if ((float)$aTotal['Value'] !== 0.0) {
                        $fTotalTaxIncl += (float)$aTotal['Value'];
                        $fTotalPriceProductTaxExcl += (float)$aTotal['Value'];
                        $fProductPriceWithoutTax = $this->addProductToOrder($iOrderId, $sOrderNumber, array(
                            'ItemTitle'  => (isset($aTotal['Code']) && $aTotal['Code'] != '') ? $aTotal['Code'] : $aTotal['Type'],
                            'SKU'        => isset($aTotal['SKU']) ? $aTotal['SKU'] : '',
                            'Price'      => $aTotal['Value'],
                            'Tax'        => isset($aTotal['Tax']) && $aTotal['Tax'] !== false ? $aTotal['Tax'] : $fMaxTaxRate,
                            'TaxClassId' => isset($aTotal['Tax']) && $aTotal['Tax'] !== false ? null : $fMaxTaxClassId,
                            'Data'       => isset($aTotal['Data']) ? $aTotal['Data'] : array(),
                            'Quantity'   => 1,
                            'Modus'      => 4,
                        ));
                        $fTotalTaxExcl += $fProductPriceWithoutTax;
                        $fTotalPriceProductTaxIncl += $fProductPriceWithoutTax;
                    }
                    break;
                }
            }
        }
        unset($aTotal);

        if (MLDatabase::getDbInstance()->columnExistsInTable('invoice_shipping_tax_rate', $oShopwareModel->getClassMetadata('Shopware\Models\Order\Order')->getTableName())) {
            $this->executeSql("
                UPDATE ".$oShopwareModel->getClassMetadata('Shopware\Models\Order\Order')->getTableName()."
                   SET invoice_amount = ?,
                       invoice_amount_net = ?,
                       invoice_shipping = ?,
                       invoice_shipping_net = ?,
                       invoice_shipping_tax_rate= ?
                 WHERE id = ?
            ", array(
                $fTotalTaxIncl,
                $fTotalTaxExcl,
                $fShippingTaxIncl,
                $fShippingTaxExcl,
                $fMaxTaxRate,
                $iOrderId,
            ));
        } else {
            $this->executeSql("
                UPDATE ".$oShopwareModel->getClassMetadata('Shopware\Models\Order\Order')->getTableName()."
                   SET invoice_amount = ?,
                       invoice_amount_net = ?,
                       invoice_shipping = ?,
                       invoice_shipping_net = ?
                 WHERE id = ?
            ", array(
                $fTotalTaxIncl,
                $fTotalTaxExcl,
                $fShippingTaxIncl,
                $fShippingTaxExcl,
                $iOrderId,
            ));
        }
    }

    /**
     * update existed order
     * @return array
     * @throws Exception
     */
    public function updateOrder($blUpdateProduct = false) {
        $oShopwareModel = Shopware()->Models();
        $this->selectShop();
        $aData = $this->aNewData;

        if ($this->oOrder->getUpdatablePaymentStatus()) {
            $this->updatePaymentStatus();
        }
        //update order statuses
        if ($this->oOrder->getUpdatableOrderStatus()) {
            $this->updateOrderStatus();
        }
        $aAddresses = $aData['AddressSets'];
        if (empty($aAddresses['Main'])) {
            throw new Exception("Main address is empty");
        }

        /*
        update shipping address, products and shipping cost only if order status is updatable
        $this->oOrder->getUpdatableOrderStatus() === null, by merging
        $this->oOrder->getUpdatableOrderStatus() === true, if order status is updatable
        */
        if ($this->oOrder->getUpdatableOrderStatus() !== false) {
            //update payment method
            $this->updatePaymentMethod();
            //update shipping method
            $this->updateShippingMethod();
            //update payment status
            $sOrderNumber = $this->oCurrentOrder->getNumber();
            $aPayment = $this->getTotal('Payment');
            $iCustomerPaymentID = $this->getPaymentMethod($aPayment['Code'], true);
            $iCustomerPaymentID = empty($iCustomerPaymentID) ? $this->getPaymentMethod($aPayment['Code'], false) : $iCustomerPaymentID;
            $this->addCustomerToOrder($aAddresses, $iCustomerPaymentID);
            //update address data
            $this->updateShippingAddress();
            $this->updateBillingAddress();
            $iTaxfree = $this->getTaxFree($aAddresses['Shipping']);


            $this->executeSql(
                'UPDATE `s_order` SET `taxfree` = '.$iTaxfree.
                (MLDatabase::getDbInstance()->columnExistsInTable('changed', 's_order') ? ' ,`changed` = NOW()' : '').
                ' WHERE id = '.$this->oCurrentOrder->getId()
            );

            if ($blUpdateProduct) {
                $iOrderId = $this->oCurrentOrder->getId();
                //            $this->executeSql('DELETE od, oda from `s_order_details` od inner join `s_order_details_attributes` oda on oda.detailID = od.id WHERE od.orderID = '.$iOrderId);

                // products and totals data will be added in this function
                $this->addProductsAndTotals($iOrderId, $sOrderNumber, $aData, $oShopwareModel);

                $this->oOrder->set('current_orders_id', $iOrderId); //important
                $aData['ShopwareOrderNumber'] = $sOrderNumber;
            }
        }
        return $aData;
    }

    /**
     * shopware create automatically new order number for new order
     * with this function it is easier to override this number
     * @return string
     */
    protected function getShopwareOrderNumber() {
        return Shopware()->Modules()->Order()->sGetOrderNumber();
    }

    /**
     * create a new order by magnalister order data
     * @return array
     * @throws Exception
     */
    public function createOrder() {
        $oShopwareModel = Shopware()->Models();
        $this->selectShop();
        $aData = $this->aNewData;
        $fTotalTaxExcl = $fTotalTaxIncl = $fShipingTaxExcl = $fShipingTaxIncl = $fTotal_products = $fTotal_products_wt = $fMaxTaxRate = 0.00;
        $aAddresses = $aData['AddressSets'];

        if (empty($aAddresses['Main'])) {// add new order when Main address is filled
            throw new Exception('main address is empty');
        }

        if (count($aData['Products']) <= 0) {// add new order when order has any product
            throw new Exception('product is empty');
        }

        $oShopwareOrder = Shopware()->Modules()->Order();
        $this->sOrderNumber = $this->getShopwareOrderNumber();
        $aPayment = $this->getTotal('Payment');
        $iCustomerPaymentID = $this->getPaymentMethod($aPayment['Code'], true);
        $iCustomerPaymentID = empty($iCustomerPaymentID) ? $this->getPaymentMethod($aPayment['Code'], false) : $iCustomerPaymentID;
        $iCustomerNumber = $this->addCustomerToOrder($aData['AddressSets'], $iCustomerPaymentID);//importand I pass main data object to set password of customer

        $iDispatchId = $this->getDispatch();
        $iCleared = $this->getPaymentStatus();
        $fNet = 0;
        $iTransactionID = $this->getTransactionId();

        $iTaxfree = $this->getTaxFree($aAddresses['Shipping']);
        $iTemporaryID = '';
        $oCurrency = $oShopwareModel->getRepository('\Shopware\Models\Shop\Currency')->findOneBy(array('currency' => $aData['Order']['Currency']));
        if (!is_object($oCurrency)) {
            $sMessage = MLI18n::gi()->get('Orderimport_CurrencyCodeDontExistsError', array(
                    'mpOrderId' => MLSetting::gi()->get('sCurrentOrderImportMarketplaceOrderId'),
                    'ISO'       => $aData['Order']['Currency']
                )
            );
            MLErrorLog::gi()->addError(0, ' ', $sMessage, array('MOrderID' => MLSetting::gi()->get('sCurrentOrderImportMarketplaceOrderId')));
            throw new Exception($sMessage);
        }
        //show  in order detail
        $sInternalComment = isset($aData['MPSpecific']['InternalComment']) ? $aData['MPSpecific']['InternalComment'] : '';
        //show in order detail and invoice pdf
        $sCustomerComment = '';
        if (MLModul::gi()->getConfig('order.information')) {
            $sCustomerComment .= isset($aData['MPSpecific']['InternalComment']) ? $aData['MPSpecific']['InternalComment'] : '';
        }


        /* @var $oCurrency \Shopware\Models\Shop\Currency */
        $iPaymentID = $this->getPaymentMethod($aPayment['Code']);
        $sSql = "
            INSERT INTO ".$oShopwareModel->getClassMetadata('Shopware\Models\Order\Order')->getTableName()."
                    SET ordernumber = ?, userID = ?, invoice_amount = ?, invoice_amount_net = ?, invoice_shipping = ?,
                        invoice_shipping_net = ?, ordertime = ?, status = ?, cleared = ?, paymentID = ?,
                        transactionID = ?, customercomment = ?, internalcomment = ?, net = ?, taxfree = ?,
                        partnerID = ?, temporaryID = ?, referer = ?, language = ?, dispatchID = ?,
                        currency = ?, currencyFactor = ?, subshopID = ?, remote_addr = ?";
        if (MLDatabase::getDbInstance()->columnExistsInTable('changed', $oShopwareModel->getClassMetadata('Shopware\Models\Order\Order')->getTableName())) {
            $sSql .= ', changed = NOW()';
        }

        $aParams = array(
            $this->sOrderNumber, $this->iCustomerId, $fTotalTaxIncl,
            $fTotalTaxExcl, floatval($fShipingTaxIncl), floatval($fShipingTaxExcl),
            $aData['Order']['DatePurchased'], $aData['Order']['Status'], $iCleared,
            $iPaymentID, $iTransactionID, $sCustomerComment, $sInternalComment, $fNet, $iTaxfree, '',
            $iTemporaryID, '', $this->getShopwareShopId(), $iDispatchId, $oCurrency->getCurrency(),
            $oCurrency->getFactor(), $this->getShopwareShopId(), ((string)$_SERVER['REMOTE_ADDR'])
        );
        if (MLDatabase::getDbInstance()->columnExistsInTable('invoice_shipping_tax_rate', $oShopwareModel->getClassMetadata('Shopware\Models\Order\Order')->getTableName())) {
            $sSql .= ',invoice_shipping_tax_rate= ?';
            $aParams[] = floatval($fMaxTaxRate);
        }
        $blOrderResult = $this->executeSql($sSql, $aParams);
        if (!$blOrderResult) {
            throw new Exception('there is a problem to insert order data');
        }
        $iOrderId = $this->getShopwareDb()->lastInsertId();
        $sAttributeSql = "
            INSERT INTO s_order_attributes (orderID, attribute1, attribute2, attribute3, attribute4, attribute5, attribute6)
                 VALUES ($iOrderId ,'','','','','','')
       ";

        /*
          modus 0 = default article
          modus 1 = premium articles
          modus 2 = voucher
          modus 3 = customergroup discount
          modus 4 = payment surcharge / discount
          modus 10 = bundle discount
          modus 12 = trusted shops article
         */
        $this->executeSql($sAttributeSql);
        $this->setPaidTime($iOrderId);
        // products and totals data will be added in this function
        $this->addProductsAndTotals($iOrderId, $this->sOrderNumber, $aData, $oShopwareModel);

        $this->oOrder->set('orders_id', $iOrderId);
        $this->oOrder->set('current_orders_id', $iOrderId); //important
        $aData['ShopwareOrderNumber'] = $this->sOrderNumber;

        $aShippingAddress = $this->prepareAddress($aAddresses['Shipping'], "$iCustomerNumber", array(), false, 'Shipping');
        $oDhlHelper = MLHelper::gi('model_order_dhl')->setAdditionalAddressExists($this->additionalAddressExists());

        /* @var $oDhlHelper ML_Shopware_Helper_Model_Order_Dhl */
        $blExistingProduct = $oDhlHelper->checkExistingArticle($iOrderId);
        try {//some shopware hook or event can break this process
            if ($blExistingProduct) {
                $oShopwareOrder->sSaveShippingAddress($aShippingAddress, $iOrderId);
            } else {
                throw new Exception('not found detail can be problematic for dhl plugin');
            }
        } catch (Exception $oEx) {
            if (!$oDhlHelper->checkShippingAddress($iOrderId)) {//add shipping address normal
                $oDhlHelper->sSaveShippingAddress($aShippingAddress, $iOrderId);
            }
        }

        $aBillingAddress = $this->prepareAddress($aAddresses['Billing'], "$iCustomerNumber");
        try {//some shopware hook or event can break this process
            if ($blExistingProduct) {
                $oShopwareOrder->sSaveBillingAddress($aBillingAddress, $iOrderId);
            } else {
                throw new Exception('not found detail can be problematic for dhl plugin');
            }
        } catch (Exception $oEx) {
            if (!$oDhlHelper->checkBillingAddress($iOrderId)) {//add billing address normaly
                $oDhlHelper->sSaveBillingAddress($aBillingAddress, $iOrderId);
            }
        }
        // creates 'Return Carrier' and 'Return Tracking Key' custom fields (required for OTTO)
        $this->addCustomFields($iOrderId);

        return $aData;
    }

    /**
     * Fetching the maximal tax rate and taxId from order products table (s_order_details)
     * @param $iOrderId
     * @return array it should be in this form array(Rate, ClassId)
     */
    protected function getMaxTaxRateFromProducts($iOrderId) {
        $oOrder = Shopware()->Models()->getRepository('\Shopware\Models\Order\Order')->find($iOrderId);
        $oDetails = $oOrder->getDetails();
        $fTotalTaxRate = 0.00;
        $iTotalTaxClassId = null;
        $blShopwareModel = false;
        $blShopwareDB = false;
        $aTax = null;
        $iNumberOfDetails = 0;
        foreach ($oDetails as $oDetail) {
            $iNumberOfDetails ++;
            /* @var $oDetail Shopware\Models\Order\Detail */
            if ((float)$oDetail->getTaxRate() > $fTotalTaxRate) {
                $blShopwareModel = true;
                $fTotalTaxRate = (float)$oDetail->getTaxRate();
                $iTotalTaxClassId = is_object($oDetail->getTax()) ? $oDetail->getTax()->getId() : null;
            }

        }

        if (!$blShopwareModel) {
            $aTax = $this->getShopwareDb()->fetchRow('SELECT `taxID`, `tax_rate` FROM `s_order_details` WHERE `orderID` = '.((int)$iOrderId).' ORDER BY `tax_rate` DESC LIMIT 1');
            $blShopwareDB = true;
            $fTotalTaxRate = (float)$aTax['tax_rate'];
            $iTotalTaxClassId = $aTax['taxID'];
        }


        MLLog::gi()->add(MLSetting::gi()->get('sCurrentOrderImportLogFileName'), array(
            'MOrderId' => MLSetting::gi()->get('sCurrentOrderImportMarketplaceOrderId'),
            'PHP'      => get_class($this).'::'.__METHOD__.'('.__LINE__.')',
            'Max Tax Calculation'     => array(
                'Number of Detail' => $iNumberOfDetails,
                'DBTax'            => $aTax,
                '$fTotalTaxRate'   => $fTotalTaxRate,
                '$iTotalTaxClassId'=> $iTotalTaxClassId,
                'ShopwareDB'       => $blShopwareDB,
                'ShopwareModel'    => $blShopwareModel,
            )
        ));


        return array(
            'Rate'    => $fTotalTaxRate,//Rate
            'ClassId' => $iTotalTaxClassId,//ClassId
        );

    }

    /**
     * get specific total of Order data by total Type
     * @param string $sName
     * @return array
     */
    public function getTotal($sName) {
        $aTotals = $this->aNewData['Totals'];
        foreach ($aTotals as $aTotal) {
            if ($aTotal['Type'] == $sName) {
                return $aTotal;
            }
        }
        return array();
    }

    /**
     * if no payment status is set it return 17 as open status
     */
    protected function getPaymentStatus() {
        if (empty($this->aNewData['Order']['PaymentStatus'])) {// it could be filled in Normalize files (e.g. eBay)
            $this->aNewData['Order']['PaymentStatus'] = MLModul::gi()->getConfig('orderimport.paymentstatus');
            if ($this->aNewData['Order']['PaymentStatus'] === null) {
                $this->aNewData['Order']['PaymentStatus'] = MLModul::gi()->getConfig('paymentstatus');
            }
            if (empty($this->aNewData['Order']['PaymentStatus'])) {
                $this->aNewData['Order']['PaymentStatus'] = 17;
            }
        }
        return $this->aNewData['Order']['PaymentStatus'];
    }

    /**
     * try to find matched shipping method in shopware otherwise it create new shipping method(Dispatch)
     * @return int
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws Exception
     */
    protected function getDispatch() {
        $aTotalShipping = $this->getTotal('Shipping');
        if (isset($aTotalShipping['Code'])) {
            try {
                if (is_numeric($aTotalShipping['Code'])) {
                    $oDispatch = Shopware()->Models()->getRepository('Shopware\Models\Dispatch\Dispatch')->find($aTotalShipping['Code']);
                    if (!is_object($oDispatch)) {
                        $oDispatch = Shopware()->Models()->getRepository('Shopware\Models\Dispatch\Dispatch')->findOneBy(array('name' => $aTotalShipping['Code']));
                    }
                } else {
                    $oDispatch = Shopware()->Models()->getRepository('Shopware\Models\Dispatch\Dispatch')->findOneBy(array('name' => $aTotalShipping['Code']));
                }
                if (!is_object($oDispatch)) {
                    $oDispatch = new Shopware\Models\Dispatch\Dispatch();
                    $oDispatch->setType(0);
                    $oDispatch->setName($aTotalShipping['Code']);
                    $oDispatch->setDescription('');
                    $oDispatch->setComment($aTotalShipping['Code']);
                    $oDispatch->setActive(0);
                    $oDispatch->setPosition(20);
                    $oDispatch->setCalculation(1);
                    $oDispatch->setStatusLink('');
                    $oDispatch->setSurchargeCalculation(0);
                    $oDispatch->setTaxCalculation(0);
                    $oDispatch->setBindLastStock(0);
                    $oDispatch->setBindShippingFree(0);
                    Shopware()->Models()->persist($oDispatch);
                    Shopware()->Models()->flush($oDispatch);
                }

                $oShippingCosts = Shopware()->Models()->getRepository('Shopware\Models\Dispatch\ShippingCost')->findBy(array('dispatchId' => $oDispatch->getId()));
                if (!$oShippingCosts) {
                    $oShippingCosts = new Shopware\Models\Dispatch\ShippingCost();
                    $oShippingCosts->setFrom('0');
                    $oShippingCosts->setValue(1);
                    $oShippingCosts->setFactor(0);
                    $oShippingCosts->setDispatch($oDispatch);

                    Shopware()->Models()->persist($oShippingCosts);
                    Shopware()->Models()->flush($oShippingCosts);
                }
                return $oDispatch->getId();
            } catch (Exception $oExc) {
                // shipping-code-dispatcher not found, use default in following-code
                MLLog::gi()->add(MLSetting::gi()->get('sCurrentOrderImportLogFileName'), array(
                    'MOrderId'       => MLSetting::gi()->get('sCurrentOrderImportMarketplaceOrderId'),
                    'PHP'            => get_class($this).'::'.__METHOD__.'('.__LINE__.')',
                    'Exception'      => 'problem to get dispatch or create it : '.$oExc->getMessage(),
                    'ExcpetionTrace' => $oExc->getTraceAsString(),
                ));
            }
        }
        //choose first and active dispatch as default dispatch to use in order import
        $oDispatch = Shopware()->Models()->getRepository('Shopware\Models\Dispatch\Dispatch');
        /* @var $oDispatch Shopware\Models\Dispatch\Repository */
        $aDefaultDispatch = $oDispatch->getDispatchesQueryBuilder()
            ->select(array('id' => 'dispatches.id'))->setMaxResults(1)->orderBy('dispatches.position')->where('dispatches.position <> 0')->getQuery()->getOneOrNullResult();
        return $aDefaultDispatch['id'];
    }

    /**
     * try to find matched payment method in shopware otherwise it will create new payment method
     * @param string $sMethodName
     * @param boolean $blActive
     * @return int
     * @throws Exception
     */
    public function getPaymentMethod($sMethodName, $blActive = false) {
        try {
            Shopware()->Models()->clear();
            $oShopwareModel = Shopware()->Models();
            if (!$blActive && isset($sMethodName) && !empty($sMethodName)) {
                if (is_numeric($sMethodName)) {
                    $oPayment = $oShopwareModel->getRepository('Shopware\Models\Payment\Payment')->find($sMethodName);
                } else {
                    if (strtolower($sMethodName) === 'paypal') {// Some of PayPal plugin have another name, to support them, magnalister checks them at first
                        $oPayment = $oShopwareModel->getRepository('Shopware\Models\Payment\Payment')->findOneBy(array('name' => 'SwagPaymentPayPalUnified'));
                    }
                    if (!isset($oPayment)) {
                        $oPayment = $oShopwareModel->getRepository('Shopware\Models\Payment\Payment')->findOneBy(array('name' => $sMethodName));
                    }
                }
                if (!isset($oPayment)) {
                    $oPayment = new \Shopware\Models\Payment\Payment();
                    $oPayment->fromArray(array(
                        'active'       => false, 'additionalDescription' => $sMethodName,
                        'attribute'    => array(), 'class' => '',
                        'countries'    => array(), 'debitPercent' => '0',
                        'description'  => $sMethodName, 'embedIFrame' => '',
                        'esdActive'    => false, 'hide' => 0,
                        'hideProspect' => false, 'iconCls' => '',
                        'id'           => 0, 'leaf' => false,
                        'name'         => $sMethodName, 'parentId' => 0,
                        'pluginId'     => 0, 'position' => 0,
                        'shops'        => array(), 'source' => 1,
                        'surcharge'    => '', 'surchargeString' => '',
                        'table'        => '', 'template' => $sMethodName,
                        'text'         => ''
                    ));

                    $oShopwareModel->persist($oPayment);
                    $oShopwareModel->flush($oPayment);
                }
                if (!$blActive || $oPayment->getActive()) {
                    return $oPayment->getId();
                }
            }
        } catch (Exception $oExc) {
            MLLog::gi()->add(MLSetting::gi()->get('sCurrentOrderImportLogFileName'), array(
                'MOrderId'  => MLSetting::gi()->get('sCurrentOrderImportMarketplaceOrderId'),
                'PHP'       => get_class($this).'::'.__METHOD__.'('.__LINE__.')',
                'Exception' => 'problem to get payment or create it : '.$oExc->getMessage()
            ));
        }
        //choose first position and active payment as default payment to use in order import
        $oPayment = Shopware()->Models()->getRepository('Shopware\Models\Payment\Payment');
        /* @var $dispatch Shopware\Models\Payment\Repository */
        if (method_exists($oPayment, 'getActivePaymentsQueryBuilder')) {
            $aDefaultPayment = $oPayment->getActivePaymentsQueryBuilder()
                ->select(array('id' => 'p.id'))->setMaxResults(1)->orderBy('p.position')->where('p.active = 1')->getQuery()->getOneOrNullResult();

        } else {
            $aDefaultPayment = $oPayment->getPaymentsQueryBuilder()
                ->select(array('id' => 'p.id'))->setMaxResults(1)->orderBy('p.position')->where('p.active = 1')->getQuery()->getOneOrNullResult();
        }
        return $aDefaultPayment['id'];
    }

    /**
     * @param array $aAddress $aAddress['CountryCode'] is only in use
     * @return \Shopware\Models\Country\Country
     * @throws Exception
     */
    protected function getCountry($aAddress) {
        $iCountryCode = trim($aAddress['CountryCode']);
        $sMlOrderId = MLSetting::gi()->get('sCurrentOrderImportMarketplaceOrderId');
        if (!empty($iCountryCode)) {
            /** @var \Shopware\Models\Country\Country $oCountry */
            $oCountry = Shopware()->Models()->getRepository('\Shopware\Models\Country\Country')->findOneBy(array('iso' => $iCountryCode));
            if (!is_object($oCountry)) {
                $message = MLI18n::gi()->get('Shopware_Orderimport_CountryCodeDontExistsError', array('mpOrderId' => $sMlOrderId, 'ISO' => $iCountryCode));
                MLErrorLog::gi()->addError(0, ' ', $message, array('MOrderID' => $sMlOrderId));
                throw new Exception($message);
            }
        } else {
            $message = MLI18n::gi()->get('Shopware_Orderimport_CountryCodeIsEmptyError', array('mpOrderId' => $sMlOrderId));
            MLErrorLog::gi()->addError(0, ' ', $message, array('MOrderID' => $sMlOrderId));
            throw new Exception($message);
        }
        return $oCountry;
    }

    /**
     *
     * @param array $aAddress $aAddress['Suburb'] and $aAddress['CountryCode'] are only in use
     * @return \Shopware\Models\Country\State
     */
    protected function getState($aAddress) {
        try {
            $oCountry = $this->getCountry($aAddress);
            if (array_key_exists('Suburb', $aAddress) && !empty($aAddress['Suburb'])) {
                /** @var \Shopware\Models\Country\State $oState */
                foreach ($oCountry->getStates() as $oState) {
                    $tmpName = strtolower(str_replace(array(' ', '-'), '', $oState->getName()));
                    $tmpSuburb = strtolower(str_replace(array(' ', '-'), '', $aAddress['Suburb']));
                    if ($tmpName == $tmpSuburb) {
                        unset($tmpName);
                        unset($tmpSuburb);
                        return $oState;
                    }
                }
                unset($tmpName);
                unset($tmpSuburb);
            }
        } catch (Exception $oExc) {
        }
        return null;
    }

    /**
     * generic function to manage address data (billing and shipping )
     * @param array $aAddress
     * @param string $sCustomerNumber
     * @param array $aFilter
     * @param bool $blClassField
     * @param null $sType
     * @return array
     * @throws Exception
     */
    protected function prepareAddress($aAddress, $sCustomerNumber, $aFilter = array(), $blClassField = false, $sType = null) {
        $blSNExist = $this->isStreetNumberExist();
        $blAAExist = $this->additionalAddressExists();
        $oCountry = $this->getCountry($aAddress);
        $oState = $this->getState($aAddress);
        $iCountryId = $oCountry->getId();
        $sCity = trim($aAddress['City']);
        if ($oState !== null) {
            $iStateId = $oState->getId();
        } else {
            $iStateId = NULL;
            if (!empty($aAddress['Suburb'])) {
                $sCity .= ' - '.trim($aAddress['Suburb']);
            }
        }

        $aResultAddress = array(
            'userID'                => $this->getCustomerId(),
            'company'               => (empty($aAddress['Company'])) ? '' : $aAddress['Company'],
            'department'            => '',
            'salutation'            => $aAddress['Gender'] === 'f' ? 'ms' : 'mr',
            'firstname'             => $aAddress['Firstname'],
            'lastname'              => $aAddress['Lastname'],
            'street'                => empty($aAddress['Street']) || !$blSNExist ? (isset($aAddress['StreetAddress']) ? $aAddress['StreetAddress'] : '--') : $aAddress['Street'],
            'streetnumber'          => $aAddress['Housenumber'],
            'magna_origstreet'      => empty($aAddress['Street']) ? (isset($aAddress['StreetAddress']) ? $aAddress['StreetAddress'] : '') : $aAddress['Street'],
            'zipcode'               => $aAddress['Postcode'],
            'city'                  => $sCity,
            'countryID'             => $iCountryId,
            'magna_origcountrycode' => trim($aAddress['CountryCode']),
            'customernumber'        => $sCustomerNumber,
            'stateID'               => $iStateId,
            'phone'                 => $aAddress['Phone'],
            'fax'                   => '',
            'ustid'                 => (empty($aAddress['UstId'])) ? '' : $aAddress['UstId'],
            'text1'                 => '',
            'text2'                 => '',
            'text3'                 => '',
            'text4'                 => '',
            'text5'                 => '',
            'text6'                 => '',
        );

        if ($blAAExist) {
            if ($blClassField) {
                $aResultAddress['additionalAddressLine1'] = '';
            } else {
                $aResultAddress['additional_address_line1'] = '';
            }

        }

        // Process AddressAddition field if provided from API and is not empty
        if (array_key_exists('AddressAddition', $aAddress) && !empty($aAddress['AddressAddition'])) {
            if ($blAAExist) {
                if ($blClassField) {
                    $aResultAddress["additionalAddressLine1"] = $aAddress['AddressAddition'];
                } else {
                    $aResultAddress["additional_address_line1"] = $aAddress['AddressAddition'];
                }
            } else {
                if ($blSNExist) {
                    // add information to company because "streetnumber" field is length restricted (6 chars)
                    if (empty($aResultAddress['company'])) {
                        $aResultAddress['company'] = $aAddress['AddressAddition'];
                    } else {
                        $aResultAddress['company'] .= ' - '.$aAddress['AddressAddition'];
                    }
                } else {
                    $aResultAddress['street'] .= ' - '.$aAddress['AddressAddition'];
                }
            }
        }

        if (count($aFilter) > 0) {
            if (in_array('country', $aFilter)) {
                $aResultAddress['country'] = $oCountry;
            }

            if (in_array('customer', $aFilter)) {
                $aResultAddress['customer'] = Shopware()->Models()->getRepository('\Shopware\Models\Customer\Customer')->find($aResultAddress['userID']);
            }

            if (in_array('state', $aFilter) && !empty($aResultAddress['stateID'])) {
                $aResultAddress['state'] = Shopware()->Models()->getRepository('\Shopware\Models\Country\State')->find($aResultAddress['stateID']);
            }
            foreach (array_keys($aResultAddress) as $sField) {
                if (!in_array($sField, $aFilter)) {
                    unset($aResultAddress[$sField]);
                }
            }
        }
        return $aResultAddress;
    }

    /**
     * Save user billing address
     * @param $aAddress
     * @param bool $blCustomerExists
     * @return bool
     * @throws Exception
     */
    public function SaveCustomerBillingAddress($aAddress, $blCustomerExists = false) {
        $blSNExist = $this->isStreetNumberExist();
        $blAAExist = $this->additionalAddressExists();
        $sSqlCommand = "  `s_user_billingaddress`  SET ";
        foreach (
            array(
                'customernumber', 'company', 'department', 'salutation', 'firstname', 'lastname',
                'street', 'streetnumber', 'zipcode', 'city', 'phone', 'fax', 'countryID',
                'stateID', 'ustid'
            ) as $sKey) {
            if (
                ($sKey == 'streetnumber' && !$blSNExist)//seince shopware 5 we don't have this field
                ||
                ($sKey == 'customernumber' && $this->blVersionGreaterThan52)//seince shopware 5.2 we don't have this field
                ||
                ($sKey == 'fax' && $this->blVersionGreaterThan52)//seince shopware 5.2 we don't have this field
            ) {
                continue;
            }
            $sSqlCommand .= " $sKey = {$this->getShopwareDB()->quote($aAddress[$sKey])} ,";
        }
        if ($blAAExist) {
            $sSqlCommand .= ' additional_address_line1 = '.$this->getShopwareDB()->quote($aAddress['additional_address_line1']).',';
        }
        $sSql = $blCustomerExists ? '   UPDATE '.substr($sSqlCommand, 0, -1)." WHERE userID =  {$this->getShopwareDB()->quote($aAddress['userID'])} " : 'INSERT INTO'.$sSqlCommand." userID = {$this->getShopwareDB()->quote($aAddress['userID'])} ";

        $result = $this->executeSql($sSql);
        if ($blCustomerExists === false) {
            $billingID = $this->getShopwareDB()->lastInsertId();
            $this->executeSql("INSERT INTO s_user_billingaddress_attributes (billingID, text1, text2, text3, text4, text5, text6) VALUES (?,?,?,?,?,?,?)", array(
                $billingID,
                $aAddress["text1"], $aAddress["text2"], $aAddress["text3"], $aAddress["text4"], $aAddress["text5"], $aAddress["text6"]
            ));
        }

        return $result;
    }

    /**
     * save order shipping address
     * @param $aAddress
     * @param bool $blCustomerExists
     * @return bool
     * @throws Exception
     */
    public function SaveCustomerShippingAddress($aAddress, $blCustomerExists = false) {
        $blSNExist = $this->isStreetNumberExist();
        $blAAExist = $this->additionalAddressExists();
        $sSqlCommand = "  `s_user_shippingaddress`  SET ";
        foreach (array(
                     'company', 'department',
                     'salutation', 'firstname',
                     'lastname', 'street',
                     'streetnumber', 'zipcode',
                     'city', 'countryID',
                     'stateID'
                 ) as $sKey) {
            if ($sKey == 'streetnumber' && !$blSNExist) {//in shopware 5 we don't have this field
                continue;
            }
            $sSqlCommand .= " $sKey = {$this->getShopwareDB()->quote($aAddress[$sKey])} ,";
        }

        if ($blAAExist) {
            $sSqlCommand .= ' additional_address_line1 = '.$this->getShopwareDB()->quote($aAddress['additional_address_line1']).',';
        }
        $sSqlCommand = $blCustomerExists ? '   UPDATE '.substr($sSqlCommand, 0, -1).' WHERE userID = '.$this->getShopwareDB()->quote($aAddress['userID']) : 'INSERT INTO'.$sSqlCommand.' userID = '.$this->getShopwareDB()->quote($aAddress['userID']);

        $result = $this->executeSql($sSqlCommand);

        //new attribute table
        $shippingId = $this->getShopwareDB()->lastInsertId();
        if ($blCustomerExists === false) {
            $this->executeSql("INSERT INTO s_user_shippingaddress_attributes (shippingID, text1, text2, text3, text4, text5, text6) VALUES (?,?,?,?,?,?,?)", array(
                $shippingId, $aAddress["text1"], $aAddress["text2"], $aAddress["text3"], $aAddress["text4"], $aAddress["text5"], $aAddress["text6"]
            ));
        }
        return $result;
    }

    /**
     * update billing address
     * @return ML_Shopware_Helper_Model_ShopOrder
     * @throws Exception
     */
    public function updateBillingAddress() {
        $sCustomerNumber = '';
        if ($this->oCurrentOrder->getBilling() !== null) {
            $sCustomerNumber = $this->oCurrentOrder->getBilling()->getNumber();
        }
        $iOrderId = $this->oCurrentOrder->getId();
        $blSNExist = $this->isStreetNumberExist();
        $blAAExist = $this->additionalAddressExists();
        $aAddress = $this->prepareAddress($this->aNewData['AddressSets']['Billing'], $sCustomerNumber);
        $sSql = "
            UPDATE s_order_billingaddress
               SET userID = ?, orderID = ?,".
            "customernumber = ?,".
            "company = ?, department = ?,
                salutation = ?, firstname = ?, lastname = ?, street = ?, ".
            ($blSNExist ? "streetnumber = ?," : "").
            ($blAAExist ? "additional_address_line1 = ?," : "").
            " zipcode = ?, city = ?, phone = ?,".
            ($this->blVersionGreaterThan52 ? "" : "fax = ?,")
            ." countryID = ?,  stateID = ? , ustid = ?
             WHERE orderID = ".$iOrderId;

        $aValues = array();
        $aValues[] = $aAddress['userID'];
        $aValues[] = $iOrderId;
        $aValues[] = $aAddress['customernumber'];
        $aValues[] = $aAddress['company'];
        $aValues[] = $aAddress['department'];
        $aValues[] = $aAddress['salutation'];
        $aValues[] = $aAddress['firstname'];
        $aValues[] = $aAddress['lastname'];
        $aValues[] = $aAddress['street'];
        if ($blSNExist) {
            $aValues[] = $aAddress['streetnumber'];
        }
        if ($blAAExist) {
            $aValues[] = $aAddress['additional_address_line1'];
        }
        $aValues[] = $aAddress['zipcode'];
        $aValues[] = $aAddress['city'];
        $aValues[] = $aAddress['phone'];
        if (!$this->blVersionGreaterThan52) {
            $aValues[] = $aAddress['fax'];
        }
        $aValues[] = $aAddress['countryID'];
        $aValues[] = $aAddress['stateID'];
        $aValues[] = $aAddress['ustid'];
        $this->executeSql($sSql, $aValues);
        return $this;
    }

    /**
     * update shipping address
     * @return \ML_Shopware_Helper_Model_ShopOrder
     * @throws Exception
     */
    public function updateShippingAddress() {
        $blSNExist = $this->isStreetNumberExist();
        $blAAExist = $this->additionalAddressExists();
        $aAddress = $this->prepareAddress($this->aNewData['AddressSets']['Shipping'], '', array(), false, 'Shipping');
        $iOrderId = $this->oCurrentOrder->getId();
        $sSql = "
            UPDATE s_order_shippingaddress
            SET userID = ?,  orderID = ?,  company = ?,  department = ?,  salutation = ?, firstname = ?,  lastname = ?, street = ?, ".
            ($blSNExist ? "streetnumber = ?," : "").
            ($blAAExist ? "additional_address_line1 = ?," : "").
            "zipcode = ?, city = ?, countryID = ?, stateID = ?  WHERE orderID = ".$iOrderId;
        $aValues = array();
        $aValues[] = $aAddress['userID'];
        $aValues[] = $iOrderId;
        $aValues[] = $aAddress['company'];
        $aValues[] = $aAddress['department'];
        $aValues[] = $aAddress['salutation'];
        $aValues[] = $aAddress['firstname'];
        $aValues[] = $aAddress['lastname'];
        $aValues[] = $aAddress['street'];
        if ($blSNExist) {
            $aValues[] = $aAddress['streetnumber'];
        }
        if ($blAAExist) {
            $aValues[] = $aAddress['additional_address_line1'];
        }
        $aValues[] = $aAddress['zipcode'];
        $aValues[] = $aAddress['city'];
        $aValues[] = $aAddress['countryID'];
        $aValues[] = $aAddress['stateID'];
        $this->executeSql($sSql, $aValues);

        $oDhlHelper = MLHelper::gi('model_order_dhl')->setAdditionalAddressExists($this->additionalAddressExists());
        $oDhlHelper->fillDhlAttributes($iOrderId, array(
            'firstName'    => $aAddress['firstname'],
            'lastName'     => $aAddress['lastname'],
            'city'         => $aAddress['city'],
            'zip'          => $aAddress['zipcode'],
            'country'      => $aAddress['magna_origcountrycode'],
            'street'       => $aAddress['magna_origstreet'],
            'streetNumber' => $aAddress['streetnumber']
        ));
        return $this;
    }

    /**
     * if we have payment in order we add it as order detail
     * @param int $iOrderId
     * @param string $sOrderNumber
     * @param array $aProduct
     * @return float
     * @throws Exception
     */
    public function checkPayment($iOrderId, $sOrderNumber, $aProduct) {
        if (is_object($this->oCurrentOrder)) {
            $oDetails = $this->oCurrentOrder->getDetails();
            /* @var $oDetail Shopware\Models\Order\Detail */
            foreach ($oDetails as $oDetail) {
                $sName = $oDetail->getArticleName();
                if (trim($sName) == trim($aProduct['ItemTitle'])) {
                    return $this->updateProduct($oDetail->getId(), $aProduct);
                }
            }
        }
        return $this->addProductToOrder($iOrderId, $sOrderNumber, $aProduct);
    }


    /**
     * if same product should be added to order, this function just update current product
     * and return net price of product
     * @param int $iDetailId
     * @param array $aProduct
     * @return float
     */
    protected function updateProduct($iDetailId, $aProduct) {
        //price
        $fGros = (float)$aProduct['Price'];
        $fTaxPercent = (float)$aProduct['Tax'];
        $oTax = Shopware()->Models()->getRepository('\Shopware\Models\Tax\Tax')->findOneBy(array('tax' => $fTaxPercent));
        $sSql = "
           UPDATE `s_order_details`
              SET
                  price = ?,
                  quantity = ?,
                  taxID = ?,
                  tax_rate = ?
            WHERE id = ?
        ";
        $this->executeSql($sSql, array(
            $fGros,
            $aProduct['Quantity'],
            is_object($oTax) ? $oTax->getId() : null,
            $fTaxPercent,
            $iDetailId
        ));
        return round($this->oPrice->calcPercentages($fGros, null, $fTaxPercent), 5);
    }

    /**
     * add product to order detail and return net price of product
     * @param int $iOrderId
     * @param string $sOrderNumber
     * @param array $aProduct
     * @param int $iCurrentQty
     * @return float
     * @throws Exception
     */
    protected function addProductToOrder($iOrderId, $sOrderNumber, $aProduct, $iCurrentQty = 0) {
        //reset orderDetailId when adding new product
        $this->sCurrentOrderDetailId = null;

        /** @var ML_Shopware_Model_Product $oProduct */
        $oProduct = MLProduct::factory();

        $sEanSql = '';
        $iTaxClass = null;

        if (isset($aProduct['SKU'])
            && $oProduct->getByMarketplaceSKU($aProduct['SKU'])->exists()
        ) {
            $sArticleNumber = $oProduct->getProductField('number');
            $iArticleId = (int)$oProduct->getId();
            if (MLDatabase::getDbInstance()->columnExistsInTable('ean', 's_order_details')) {
                $sEanSql = ",ean = ".$this->getShopwareDB()->quote($oProduct->getEAN());
            }
            $blIsEsdarticle = is_object($oProduct->getProductField('esd', 'object')) ? 1 : 0;
            $oTax = null;
            if (
                $aProduct['Tax'] === false
                || !array_key_exists('ForceMPTax', $aProduct)
                || !$aProduct['ForceMPTax']
            ) {
                $fTaxPercent = $oProduct->getTax($this->aNewData['AddressSets']);
            } else {
                $fTaxPercent = $aProduct['Tax'];
                $oTax = Shopware()->Models()->getRepository('\Shopware\Models\Tax\Tax')->findOneBy(array('tax' => $fTaxPercent));
                $iTaxClass = (is_object($oTax) ? $oTax->getId() : null);
            }
            if ($oTax === null) {
                $oProductTax = $oProduct->getLoadedProduct()->getTax();
                //its possible that products has not tax set (#2020111210003559) then $oProductTax is not an object
                if (is_object($oProductTax)) {
                    $iTaxClass = $oProductTax->getId();
                }
            }
        } else {
            $sArticleNumber = $aProduct['SKU'];
            $iArticleId = 0;
            $blIsEsdarticle = 0;
            $fDefaultProductTax = MLModul::gi()->getConfig('mwst.fallback');
            // fallback
            if ($fDefaultProductTax === null) {
                $fDefaultProductTax = MLModul::gi()->getConfig('mwstfallback'); // some modules have this, other that
            }
            $fTaxPercent = (($aProduct['Tax'] === false) ? $fDefaultProductTax : $aProduct['Tax']);
            //            $aProduct['Modus'] = 12;
        }
        //price
        $fGros = (float)$aProduct['Price'];
        $fNet = round($this->oPrice->calcPercentages($fGros, null, (float)$fTaxPercent), 5);

        // $oTax could be null
        if ($iTaxClass === null) {
            if (isset($aProduct['TaxClassId'])) {
                // $aProduct['TaxClassId'] is filled for extra payment and promotions and discounts and items that are
                // not a product but they should be added as a product or position in the order
                $iTaxClass = $aProduct['TaxClassId'];
            } else {
                $oTax = Shopware()->Models()->getRepository('\Shopware\Models\Tax\Tax')->findOneBy(array('tax' => $fTaxPercent));
                $iTaxClass = (is_object($oTax) ? $oTax->getId() : 'null');
            }
        }

        // Fallback if product is found in shop but tax for product is not set
        if ($fTaxPercent === null) {
            $fTaxPercent = MLModule::gi()->getConfig('mwst.fallback');
            // fallback
            if ($fTaxPercent === null) {
                $fTaxPercent = MLModule::gi()->getConfig('mwstfallback'); // some modules have this, other that
            }
        }

        $sName = $this->getShopwareDB()->quote($aProduct['ItemTitle']);
        $sSql = "
            INSERT INTO `s_order_details`
                    SET orderID = $iOrderId,
                        ordernumber = '$sOrderNumber',
                        articleID = $iArticleId,
                        articleordernumber = '$sArticleNumber',
                        price = {$fGros},
                        quantity = {$aProduct['Quantity']},
                        name = {$sName},
                        status = 0 ,
                        releasedate = '0000-00-00',
                        modus = {$aProduct['Modus']},
                        esdarticle = {$blIsEsdarticle},
                        taxID = ".$iTaxClass.",
                        tax_rate = {$fTaxPercent}
        ".$sEanSql;

        MLLog::gi()->add(MLSetting::gi()->get('sCurrentOrderImportLogFileName'), array(
            'MOrderId'              => MLSetting::gi()->get('sCurrentOrderImportMarketplaceOrderId'),
            'PHP'                   => get_class($this).'::'.__METHOD__.'('.__LINE__.')',
            'Tax by adding product' => array(
                '$fTotalTaxRate'    => $fTaxPercent,
                '$iTotalTaxClassId' => $iTaxClass,
            )
        ));
        // Add new entry to the table
        if ($this->executeSql($sSql)) {
            $iOrderDetailsId = $this->sCurrentOrderDetailId = $this->getShopwareDB()->lastInsertId();
            $sAttributeSql = "
                INSERT INTO s_order_details_attributes (detailID, attribute1, attribute2, attribute3, attribute4, attribute5, attribute6)
                     VALUES ($iOrderDetailsId ,'','','','','','')
            ";
            $this->executeSql($sAttributeSql);
            if ($blIsEsdarticle) {
                $aEsdProductData = array(
                    "quantity"    => $aProduct['Quantity'],
                    "articleID"   => $iArticleId,
                    "ordernumber" => $sArticleNumber,
                    "articlename" => $sName
                );
                /**
                 * merged orders - keep imported product in Order_ESD
                 */
                $blImportedBefore = false;
                foreach (isset($this->aCurrentData['Products']) ? $this->aCurrentData['Products'] : array() as $aOldProduct) {
                    if ($aProduct['SKU'] == $aOldProduct['SKU']) {
                        $blImportedBefore = true;
                    }
                }
                if (!$blImportedBefore) {
                    $oShopwareOrderModule = Shopware()->Modules()->Order();
                    $oCustomer = $this->getCustomer($this->aNewData['AddressSets']['Main']['EMail']);
                    if (is_object($oCustomer)) {
                        $oShopwareOrderModule->sUserData = array(
                            "additional" => array(
                                "user" => array(
                                    "id"    => $oCustomer->getId(),
                                    "email" => $oCustomer->getEmail(),
                                )
                            )
                        );
                        $oShopwareOrderHandler = Shopware()->Modules()->Order();
                        if (method_exists($oShopwareOrderHandler, 'sManageEsdOrder')) {
                            $oShopwareOrderHandler->sManageEsdOrder($aEsdProductData, $iOrderId, $iOrderDetailsId);
                        } else {
                            $oShopwareOrderHandler->handleEsdOrder($aEsdProductData, $iOrderId, $iOrderDetailsId);
                        }
                        $oShopwareOrderModule->sUserData = null;
                    }
                }
            }
        }
        if (isset($aProduct['SKU'])
            && $oProduct->getByMarketplaceSKU($aProduct['SKU'])->exists()
            && isset($aProduct['StockSync'])
            && $aProduct['StockSync']
        ) {
            MLLog::gi()->add(MLSetting::gi()->get('sCurrentOrderImportLogFileName'), array(
                'MOrderId'       => MLSetting::gi()->get('sCurrentOrderImportMarketplaceOrderId'),
                'PHP'            => get_class($this).'::'.__METHOD__.'('.__LINE__.')',
                'StockReduction' => array(
                    'SKU'              => $aProduct['SKU'],
                    'Reduced quantity' => $iCurrentQty,
                    'Old quantity'     => $oProduct->getStock(),
                    'New quantity'     => $oProduct->getStock() - $iCurrentQty,
                )
            ));
            $oProduct->setStock($oProduct->getStock() - $iCurrentQty);
        }
        return $fNet;
    }

    /**
     * @param string $sEmail
     * @return \Shopware\Models\Customer\Customer
     */
    protected function getCustomer($sEmail) {
        return Shopware()->Models()->getRepository('\Shopware\Models\Customer\Customer')->findOneBy(array('email' => $sEmail));
    }

    /**
     * set $this->CustomerId by found customer , and then return CustomerNumber
     *  -CustomerNumber is different from customer id-
     * @param array $aAddresses
     * @param int $iPaymentID
     * @return int
     * @throws Exception
     */
    protected function addCustomerToOrder(&$aAddresses, $iPaymentID) {
        $iCustomerNumber = '';
        //$oCustomer = $this->getCustomer($aAddresses['Main']['EMail']);
        $oCustomer = $this->getCustomer($aAddresses['Main']['EMailIdent']);
        $blCustomerExists = ($oCustomer != null);
        // Add customer if customer isn't existed
        if (!$blCustomerExists) {
            $sPassword = "";
            for ($i = 0; $i < 10; $i++) {
                $iRandomNumber = function_exists('random_int') ? random_int(0, 35) : mt_rand(0, 35);
                if ($iRandomNumber < 10) {
                    $sPassword .= $iRandomNumber;
                } else {
                    $sPassword .= chr($iRandomNumber + 87);
                }
            }
            $sEncoderName = 'md5'; //Shopware()->PasswordEncoder()->getDefaultPasswordEncoderName();
            $aAddresses['Main']['Password'] = $sPassword; //important to send password in Promotion Mail
            $sEncodedPassword = md5($sPassword);

            $sConfigCustomerGroup = MLModul::gi()->getConfig('CustomerGroup');
            if ($sConfigCustomerGroup === null) {
                $sConfigCustomerGroup = MLModul::gi()->getConfig('customergroup');
            }
            if ($sConfigCustomerGroup == '-') {
                $oCustomerGroup = null;
            } else {
                $oCustomerGroup = Shopware()->Models()->getRepository('\Shopware\Models\Customer\Group')->find($sConfigCustomerGroup);
            }
            if ($oCustomerGroup !== null) {
                $sCustomerGroup = $oCustomerGroup->getKey();
            } else {
                $sCustomerGroup = 'EK';
            }
            $aFields = array_merge(Shopware()->Models()->getClassMetadata('Shopware\Models\Article\Article')->columnNames, Shopware()->Models()->getClassMetadata('Shopware\Models\Customer\Customer')->columnNames);
            $sCustomerSql = "
                INSERT INTO s_user
                   SET password = ".$this->getShopwareDB()->quote($sEncodedPassword).",
                       active = 1 ,
                       customergroup = ".$this->getShopwareDB()->quote($sCustomerGroup).",
                       firstlogin = CURRENT_DATE(),
                       lastlogin = NOW(),
                       validation = '',".
                (in_array('encoder', $aFields) ? "encoder = '".$sEncoderName."'," : '').
                "email = ".$this->getShopwareDB()->quote(trim($aAddresses['Main']['EMail'])).",
                       language = ".$this->getShopwareShopId().",
                       referer = '',
                       accountmode = ".($sConfigCustomerGroup == '-' ? '1' : '0').",
                       newsletter = 0,
                       paymentID = $iPaymentID,
                       paymentpreset = 0,
                       subshopID = ".$this->getShopwareShopId();
            if ($this->blVersionGreaterThan52) {
                if (Shopware()->Config()->get('sSHOPWAREMANAGEDCUSTOMERNUMBERS')) {
                    $iCustomerNumber = $this->getNextCustomerNumber();
                    $sCustomerSql .= " ,customernumber = ".$this->getShopwareDB()->quote($iCustomerNumber);
                }
                $sCustomerSql .= " ,salutation = ".$this->getShopwareDB()->quote($aAddresses['Main']["Gender"] == 'f' ? 'ms' : 'mr');

                if (trim($aAddresses['Main']['Firstname']) != '') {
                    $sCustomerSql .= " ,firstname = ".$this->getShopwareDB()->quote($aAddresses['Main']['Firstname']);
                }
                if (trim($aAddresses['Main']['Lastname']) != '') {
                    $sCustomerSql .= " ,lastname = ".$this->getShopwareDB()->quote($aAddresses['Main']['Lastname']);
                }
            }
            /* account mode should be 0, otherwise user cannot login with this account */
            if ($this->executeSql($sCustomerSql)) {
                $this->iCustomerId = $this->getShopwareDB()->lastInsertId();
                if (MLDatabase::getDbInstance()->tableExists('s_user_attributes') && MLDatabase::getDbInstance()->columnExistsInTable('userID', 's_user_attributes')) {
                    try {
                        $this->executeSql("INSERT INTO `s_user_attributes` (`userID`) VALUES (?) ", array($this->iCustomerId));
                    } catch (Exception $oEx) {
                    }
                }
            } else {
                throw new Exception('error in adding user');
            }
        } else {
            try {
                if ($this->blVersionGreaterThan52) {
                    $iCustomerNumber = $oCustomer->getNumber();
                } else {
                    $iCustomerNumber = is_object($oCustomer->getBilling()) ? $oCustomer->getBilling()->getNumber() : null;
                }
            } catch (Exception $oExc) {
            }
            $sCustomerSql = "
                UPDATE s_user
                   SET email = ".$this->getShopwareDB()->quote(trim($aAddresses['Main']['EMail']))."
                 WHERE id = ".$oCustomer->getId()."
            ";
            if (!$this->executeSql($sCustomerSql)) {
                throw new Exception('error in adding user');
            }
            $this->iCustomerId = $oCustomer->getId();
        }
        if ($iCustomerNumber === '' && Shopware()->Config()->get('sSHOPWAREMANAGEDCUSTOMERNUMBERS')) {
            $iCustomerNumber = $this->getNextCustomerNumber();
        }

        if ($this->blVersionGreaterThan52 && $this->blNewAddress) {
            $oShopwareModel = Shopware()->Models();
            $oCustomer = Shopware()->Models()->getRepository('\Shopware\Models\Customer\Customer')->find($this->iCustomerId);

            $oBillingAddress = new Shopware\Models\Customer\Address();
            $oBillingAddress->fromArray($this->prepareAddress($aAddresses['Billing'], $iCustomerNumber, array('customer', 'company', 'department', 'salutation', 'firstname', 'lastname', 'street', 'zipcode', 'city', 'country', 'customernumber', 'state', 'phone', 'ustid', 'additional_address_line1'), true));
            $oShopwareModel->persist($oBillingAddress);
            $oShopwareModel->flush($oBillingAddress);
            $oCustomer->setDefaultBillingAddress($oBillingAddress);

            $oShippingAddress = new Shopware\Models\Customer\Address();
            $oShippingAddress->fromArray($this->prepareAddress($aAddresses['Shipping'], $iCustomerNumber, array('customer', 'company', 'department', 'salutation', 'firstname', 'lastname', 'street', 'zipcode', 'city', 'country', 'customernumber', 'state', 'phone', 'ustid', 'additional_address_line1'), true, 'Shipping'));
            $oShopwareModel->persist($oShippingAddress);
            $oShopwareModel->flush($oShippingAddress);
            $oCustomer->setDefaultShippingAddress($oShippingAddress);
            $oShopwareModel->persist($oShippingAddress);
            $oShopwareModel->flush($oShippingAddress);
            $oShopwareModel->flush($oCustomer);

            $oShopwareModel->refresh($oBillingAddress);
            $oShopwareModel->refresh($oShippingAddress);
            $oShopwareModel->refresh($oCustomer);

            $aDBUser = MLDatabase::getDbInstance()->fetchRow('select * FROM `s_user` WHERE id= '.$this->iCustomerId);

            if (null === $aDBUser['default_shipping_address_id'] || null === $aDBUser['default_billing_address_id']) {
                if ($oShippingAddress->getId() == null || $oBillingAddress->getId() == null) {
                    throw new Exception('shipping or billing address is not correctly created');
                }
                MLDatabase::getDbInstance()->update('s_user', array('default_billing_address_id' => $oBillingAddress->getId()), array('id' => $oCustomer->getId()));
                MLDatabase::getDbInstance()->update('s_user', array('default_shipping_address_id' => $oShippingAddress->getId()), array('id' => $oCustomer->getId()));
            }
        }
        $this->SaveCustomerShippingAddress($this->prepareAddress($aAddresses['Shipping'], $iCustomerNumber, array(), false, 'Shipping'), $blCustomerExists);
        $this->SaveCustomerBillingAddress($this->prepareAddress($aAddresses['Billing'], $iCustomerNumber), $blCustomerExists);
        return $iCustomerNumber;
    }

    /**
     * @return false|mixed|string|null
     * @throws Exception
     */
    protected function getNextCustomerNumber() {
        try {//shopware >= 5.2
            $incrementer = Shopware()->Container()->get('shopware.number_range_incrementer');
            $iCustomerNumber = $incrementer->increment('user');
        } catch (Exception $ex) {//shopware < 5.2
            $iCustomerNumber = $this->getShopwareDb()->fetchOne("SELECT `number`+1 FROM `s_order_number` WHERE name = 'user'");
            $iCountUsedBefore = (int)$this->getShopwareDb()->fetchOne("SELECT COUNT(*) FROM ".(!$this->blVersionGreaterThan52 ? "s_user_billingaddress" : "s_user")." WHERE customernumber = '".$iCustomerNumber."'");
            if ($iCountUsedBefore > 0) {
                $iCustomerNumberShipping = $this->getShopwareDb()->fetchOne('SELECT MAX( (CAST(customernumber AS unsigned)) ) +1 FROM '.(!$this->blVersionGreaterThan52 ? "s_user_billingaddress" : "s_user"));
                $iCustomerNumber = max(array((int)$iCustomerNumber, (int)$iCustomerNumberShipping));
            }
            $this->executeSql("
                UPDATE `s_order_number`
                   SET `number` = '".$iCustomerNumber."'
                 WHERE `name` = 'user'
            ");
        }
        return $iCustomerNumber;
    }

    /**
     * try to find customer id related to current order
     * @return integer
     * @throws Exception if customer not found
     */
    protected function getCustomerId() {
        if ($this->iCustomerId === null) {// try to get it from existing Order
            if ($this->oCurrentOrder !== null) {
                $this->iCustomerId = $this->oCurrentOrder->getCustomer()->getId();
            }
        }
        if ($this->iCustomerId === null) {
            throw new Exception('Customer not found');
        }
        return $this->iCustomerId;
    }

    /**
     * shopware 5 doesn't have StreetNumber field in address tables
     * @var bool
     */
    protected $blIsStreetNumberExist = null;

    /**
     * shopware 5 doesn't have StreetNumber field in address tables
     * @return bool
     */
    protected function isStreetNumberExist() {
        if ($this->blIsStreetNumberExist === null) {
            $this->blIsStreetNumberExist = MLDatabase::getDbInstance()->columnExistsInTable('streetnumber', 's_order_billingaddress');
        }
        return $this->blIsStreetNumberExist;
    }

    protected $blAdditionalAddressExists = null;

    /**
     * shopware 5 has AdditionalAddress field in address tables
     * @return bool
     */
    protected function additionalAddressExists() {
        if ($this->blAdditionalAddressExists === null) {
            $this->blAdditionalAddressExists = MLDatabase::getDbInstance()->columnExistsInTable('additional_address_line1', 's_order_billingaddress');
        }
        return $this->blAdditionalAddressExists;
    }

    /**
     * it fills ClearedDate in Shopware s_order table
     * @param int $iOrderId id of order that should be updated
     * @throws Exception
     */
    protected function setPaidTime($iOrderId) {
        $aPayment = $this->getTotal('Payment');
        $sClearedDate = trim($aPayment['PaidTime']);
        if (!empty($sClearedDate) && new DateTime($sClearedDate) > new DateTime('0000-00-00 00:00:00')) {
            $sSql = "
                UPDATE ".Shopware()->Models()->getClassMetadata('Shopware\Models\Order\Order')->getTableName()."
                   SET cleareddate = ?".
                (MLDatabase::getDbInstance()->columnExistsInTable('changed', 's_order') ? ' ,`changed` = NOW()' : '').
                "WHERE id = ?
            ";

            $aSqlValue = array($aPayment['PaidTime'], $iOrderId);
            //update order data
            $this->executeSql($sSql, $aSqlValue);
        }
    }

    /**
     * @throws Exception
     */
    public function recreateProducts() {
        $this->aNewData = MLHelper::gi('model_service_orderdata_merge')->mergeServiceOrderData($this->aNewData, $this->aCurrentData, $this->oOrder);
        echo print_m($this->aNewData);
        $iOrderId = $this->oCurrentOrder->getId();
        $sOrderNumber = $this->oCurrentOrder->getNumber();
        $oShopwareModel = Shopware()->Models();
        $this->executeSql('DELETE od, oda from `s_order_details` od inner join `s_order_details_attributes` oda on oda.detailID = od.id WHERE od.orderID = '.$iOrderId);
        $this->addProductsAndTotals($iOrderId, $sOrderNumber, $this->aNewData, $oShopwareModel);
    }

    protected function addCustomFields() {
        //
    }

    protected function getTaxFree($aAddress) {

        if ($this->getCountry($aAddress)->getTaxFree()) {
            $iTaxfree = 1;
        } else if ($this->getCountry($aAddress)->getTaxFreeUstId() && !empty($aAddress['UstId'])) {
            $iTaxfree = 1;
        } else {
            $iTaxfree = 0;
        }
        return $iTaxfree;
    }
}
