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
 * (c) 2010 - 2024 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

MLFilesystem::gi()->loadClass('Shop_Model_Order_Abstract');

class ML_Shopware_Model_Order extends ML_Shop_Model_Order_Abstract {

    protected $blUpdatablePaymentStatus;
    protected $blUpdatableOrderStatus;
    /**
     * @var Shopware\Models\Order\Order
     */
    protected $oShopwareOrder = null;

    public function getUpdatablePaymentStatus(){
        return $this->blUpdatablePaymentStatus;
    }

    public function setUpdatablePaymentStatus($blUpdatable){
        $this->blUpdatablePaymentStatus = $blUpdatable;
    }

    public function getUpdatableOrderStatus(){
        return $this->blUpdatableOrderStatus;
    }

    public function setUpdatableOrderStatus($blUpdatable){
        $this->blUpdatableOrderStatus = $blUpdatable;
    }

    /**
     *
     * @return Shopware\Models\Order\Order
     * @throws Exception
     */
    public function getShopOrderObject() {
        if($this->oShopwareOrder === null) {
            $oOrder = Shopware()->Models()->getRepository('Shopware\Models\Order\Order')->find($this->get('current_orders_id'));
            if (is_object($oOrder)) {
                $this->oShopwareOrder = $oOrder;
            } else {
                throw new Exception("this order is not found in shop");
            }
        }
        return $this->oShopwareOrder;
    }

    public function getShopOrderStatus() {
        try{
            $oOrder = $this->getShopOrderObject();
        /* @var $oOrder Shopware\Models\Order\Order */
                      return $oOrder->getOrderStatus()->getId().'';//convert status id to string
        }  catch (Exception $oExc){
            return null;
        }
    }


    public function getShopPaymentStatus() {
        try{
            $oOrder = $this->getShopOrderObject();
        /* @var $oOrder Shopware\Models\Order\Order */
                      return $oOrder->getPaymentStatus()->getId().'';//convert status id to string
        }  catch (Exception $oExc){
            return null;
        }
    }

    public function getShopOrderStatusName() {
        try{
            $oOrder = $this->getShopOrderObject();
        /* @var $oOrder Shopware\Models\Order\Order */
            if(method_exists($oOrder->getOrderStatus(), 'getName')){
                return $oOrder->getOrderStatus()->getName() ;
            } else {
                return $oOrder->getOrderStatus()->getDescription() ;
            }
        } catch (Exception $oExc){
            return null;
        }
    }
    public function getEditLink() {
        return $this->get('current_orders_id');
    }

    public function getShippingCarrier() {
        $sDefaultCarrier = $this->getDefaultCarrier();
        if ($sDefaultCarrier == '-1') {
            return $this->getShopOrderCarrierOrShippingMethod();
        } elseif (in_array($sDefaultCarrier, array('--', ''))) {
            return null;
        } else {
            return $sDefaultCarrier;
        }
    }

    public function getShopOrderCarrierOrShippingMethod() {
        try {
            $oOrder = $this->getShopOrderObject();
            /* @var $oOrder Shopware\Models\Order\Order */
            $sCarrier = $oOrder->getDispatch()->getName();
            return empty($sCarrier) ? null : $sCarrier;
        } catch (Exception $oEx) {
            return null;
        }
    }

    public function getShopOrderCarrierOrShippingMethodId() {
        try {
            $oOrder = $this->getShopOrderObject();
            /* @var $oOrder Shopware\Models\Order\Order */
            $sCarrier = $oOrder->getDispatch()->getId();
            return empty($sCarrier) ? null : $sCarrier;
        } catch (Exception $oEx) {
            return null;
        }
    }

    public function getShippingCarrierId() {
        try {
            $oOrder = $this->getShopOrderObject();
            /* @var $oOrder Shopware\Models\Order\Order */
            $sCarrier = $oOrder->getDispatch()->getId();
            return empty($sCarrier) ? null : $sCarrier;
        } catch (Exception $oEx) {
            return null;
        }
    }

    public function getShippingDateTime() {
        $oSelect = MLDatabase::factorySelectClass();
        $aChnageDate = $oSelect->from(Shopware()->Models()->getClassMetadata('Shopware\Models\Order\History')->getTableName())
            ->where(array('orderid' => $this->get('current_orders_id'), 'order_status_id' => $this->getShopOrderStatus()))
            ->orderBy('change_date DESC')
            ->getResult();
        $oOrderHistory = current($aChnageDate);
        if (!isset($oOrderHistory['change_date'])) {
            return date('Y-m-d H:i:s');
        } else {
            return $oOrderHistory['change_date'];
        }
    }

    public function getShopOrderId() {
        try {
            return $this->getShopOrderObject()->getNumber();
        } catch (Exception $oEx) {//if order deosn't exist in shopware
            return $this->get('orders_id');
        }

    }

    public function setSpecificAcknowledgeField(&$aOrderParameters, $aOrder) {
        try {
            $aOrderParameters['ShopOrderIDPublic'] = $this->getShopOrderObject()->getNumber();
        } catch (Exception $oEx) {//if order deosn't exist in shopware
            $aData = $this->get('orderdata');
            if (array_key_exists('ShopwareOrderNumber', $aOrder)) {//
                $aOrderParameters['ShopOrderIDPublic'] = $aOrder['ShopwareOrderNumber'];
            } else if (array_key_exists('ShopwareOrderNumber', $aData)) {//if order existed see in existed orderdata
                $aOrderParameters['ShopOrderIDPublic'] = $aData['ShopwareOrderNumber'];
            }
        }
    }

    public function getShippingDate() {
        return substr($this->getShippingDateTime(),0,10);
    }

    public function getShippingTrackingCode() {
        try {
            $oOrder = $this->getShopOrderObject($this->get('current_orders_id'));
            $sTrackingCode = $oOrder->getTrackingCode();
            if (empty($sTrackingCode)) {
                throw new Exception('Empty Tracking code');
            }
            if ($this->onlyFirstTrackingCode()) {
                $sSeparator = '';
                if (strpos($sTrackingCode, ',') !== false) {
                    $sSeparator = ',';
                } else if (strpos($sTrackingCode, ';') !== false) {
                    $sSeparator = ';';
                }
                if ($sSeparator !== '') {
                    $sTrackingCode = current(explode($sSeparator, $sTrackingCode));
                }
            }
            return $sTrackingCode;
        } catch (Exception $exc) {
            return '';
        }
    }

    public function getShopOrderLastChangedDate() {
        $oOrderRep = Shopware()->Models()->getRepository('Shopware\Models\Order\Order');
        /* @var $oOrderRep \Shopware\Models\Order\Repository */
        $oOrder = $oOrderRep->getOrderStatusHistoryListQuery($this->get('current_orders_id'), array(array('property' => 'history.changeDate', 'direction' => 'ASC')), NULL, 1)
                ->getOneOrNullResult();
        if (!is_object($oOrder['changeDate'])) {
            $oOrder = Shopware()->Models()->getRepository('Shopware\Models\Order\Order')->find($this->get('current_orders_id'));
            /* @var $oOrder Shopware\Models\Order\Order */
            return $oOrder->getOrderTime()->format('Y-m-d h:i:s');
        } else {
            return $oOrder['changeDate']->format('Y-m-d h:i:s');
        }
    }

    public function getRetrunCarrier() {
        $oModule = MLModule::gi();
        return $oModule->getConfig('customfieldcarrier');
    }

    public static function getOutOfSyncOrdersArray($iOffset = 0,$blCount = false) {
        $oQueryBuilder = MLDatabase::factorySelectClass()->select('id')
            ->from(Shopware()->Models()->getClassMetadata('Shopware\Models\Order\Order')->getTableName(), 'so')
            ->join(array('magnalister_orders', 'mo', 'so.id = mo.current_orders_id'), ML_Database_Model_Query_Select::JOIN_TYPE_LEFT)
            ->where("so.status != mo.status AND mo.mpID='" . MLModule::gi()->getMarketPlaceId() . "'");

        if ($blCount) {
            return $oQueryBuilder->getCount();
        } else {
            $aOrders = $oQueryBuilder->limit($iOffset, 100)
                ->getResult();
            $aOut = array();

            if (!is_array($aOrders)) {
                MLHelper::gi('stream')->outWithNewLine(MLDatabase::getDbInstance()->getLastError());
            }

            foreach ($aOrders as $aOrder) {
                $aOut[] = $aOrder['id'];
            }
            return $aOut;
        }
    }

    public function shopOrderByMagnaOrderData($aData) {
        Shopware()->Models()->clear(); // clean doctrine objects for don't have confusing data in multiple merged order in one request
        $mConfigExistingTransaction = MLModule::gi()->getConfig('order_import_transaction_exists');
        if ($mConfigExistingTransaction !== '1') {
            $oDb = Shopware()->Db();
            try {
                $mAutoCommit = $oDb->fetchOne("SELECT @@autocommit");
                $oDb->query("SET autocommit = 0;");
                $oDb->query("SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;");
            } catch (Exception $oEx) {
                // no mysql - we don't care
            }
            $oDb->query("BEGIN");
        }
        $oSHopOrderHelper = MLHelper::gi('model_shoporder');
        try {
            $mReturn = $oSHopOrderHelper
                ->setOrder($this)
                ->setNewOrderData($aData)
                ->shopOrder();
            if ($mConfigExistingTransaction !== '1') {
                $oDb->query("commit");
                try {
                    $oDb->query("SET autocommit = ".$mAutoCommit.";");
                } catch (Exception $oEx) {
                    // no mysql - we don't care
                }
            }
            return $mReturn;
        } catch (Exception $oEx) {
            if ('There is already an active transaction' === $oEx->getMessage()) {
                MLModule::gi()->setConfig('order_import_transaction_exists', 1);
            }
            if ($mConfigExistingTransaction !== '1') {
                $oDb->query("rollback");
                if (version_compare(Shopware()->Config()->version, '5.1.5', '>=')) {
                    $oSHopOrderHelper->rollBackIncrementedOrderNumber();
                }
                try {
                    $oDb->query("SET autocommit = ".$mAutoCommit.";");
                } catch (Exception $oEx) {
                    // no mysql - we don't care
                }
            }
            throw $oEx;
        }
    }

    public function triggerAfterShopOrderByMagnaOrderData() {
        MLHelper::gi('model_order_dhl')->fillMissingDhlData();
        return $this;
    }

    public static function unAcknowledgeImportedOrder($sSubsystem, $iMpId, $iMorderID, $iShopOrderID, $blSentApiRequest = true) {
        parent::unAcknowledgeImportedOrder($sSubsystem, $iMpId, $iMorderID, $iShopOrderID, $blSentApiRequest);
        $oDB = MLDatabase::getDbInstance();
        $oDB->delete("s_order", array('id' => $iShopOrderID));
        $oDB->delete("s_order_details", array('orderId' => $iShopOrderID));
        if ($oDB->columnExistsInTable('orderId', 's_order_shippingaddress')) {
            $oDB->delete("s_order_shippingaddress", array('orderId' => $iShopOrderID));
        }
        if ($oDB->columnExistsInTable('orderId', 's_order_billingaddress')) {
            $oDB->delete("s_order_billingaddress", array('orderId' => $iShopOrderID));
        }
        return true;
    }

    /**
     * @return float
     * @throws Exception
     */
    public function getShopOrderTotalAmount() {
        return $this->getShopOrderObject()->getInvoiceAmount();
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getShopOrderInvoice($sType) {
        $sFileContent = '';
        foreach ($this->getShopOrderObject()->getDocuments()->toArray() as $oDocument) {
            /**
             * @var $oDocument Shopware\Models\Order\Document\Document
             */
            if ($this->isInvoiceDocumentType($sType) && $this->isDocumentType($oDocument, 'invoice')) {
                $file = sprintf('files/documents/%s.pdf', basename($oDocument->getHash()));
                $sFileContent = base64_encode(file_get_contents($file));
                break;
            } elseif ($this->isCreditNoteDocumentType($sType) && $this->isDocumentType($oDocument, 'creditNote')) {
                $file = sprintf('files/documents/%s.pdf', basename($oDocument->getHash()));
                $sFileContent = base64_encode(file_get_contents($file));
                break;
            }
        }
        return $sFileContent;
    }

    /**
     * @return false|float
     * @throws Exception
     */
    public function getShopOrderTotalTax() {
        return round($this->getShopOrderObject()->getInvoiceAmount() - $this->getShopOrderObject()->getInvoiceAmountNet(), 2);
    }

    /**
     * @return float
     * @throws Exception
     */
    public function getShopOrderInvoiceNumber($sType) {
        $sInvoiceNumber = '';
        foreach ($this->getShopOrderObject()->getDocuments()->toArray() as $oDocument) {
            /**
             * @var $oDocument Shopware\Models\Order\Document\Document
             */
            if ($this->isInvoiceDocumentType($sType) && $this->isDocumentType($oDocument, 'invoice')) {
                $sInvoiceNumber = $oDocument->getDocumentId();
                break;
            } elseif ($this->isCreditNoteDocumentType($sType) && $this->isDocumentType($oDocument, 'creditNote')) {
                $sInvoiceNumber = $oDocument->getDocumentId();
                break;
            }
        }
        return $sInvoiceNumber;
    }

    /**
     * Check if document is specific type
     *  also with fallback for older Shopware versions below 5.5
     *  See Database: s_core_documents
     *
     * @param $oDocument Shopware\Models\Order\Document\Document
     * @param $type
     * @return bool
     */
    private function isDocumentType($oDocument, $type) {
        $blResult = false;
        if ($type === 'invoice') {
            if (!method_exists($oDocument->getType(), 'getKey')) {// in older versions of shopware before 5.5 getKey() doesn't exists
                $blResult = $oDocument->getType()->getName() === 'Rechnung';
            } else {
                //use configuration value from expert settings if provided - fallback is "invoice"
                $configValue = MLModule::gi()->getConfig('invoice.invoice_documenttype');
                if ($configValue === null) {
                    $configValue = 'invoice';
                }

                $blResult = $oDocument->getType()->getKey() === $configValue;
            }
            if (!$blResult && method_exists($oDocument->getType(), 'getId')) {
                $blResult = 1 === (int)$oDocument->getType()->getId();
            }
        } else if ($type === 'creditNote') {
            if (!method_exists($oDocument->getType(), 'getKey')) { // in older versions of shopware before 5.5 getKey() doesn't exists
                 $blResult = $oDocument->getType()->getName() === 'Stornorechnung';
            } else {
                //use configuration value from expert settings if provided - fallback is "cancellation"
                $configValue = MLModule::gi()->getConfig('invoice.creditnote_documenttype');
                if ($configValue === null) {
                    $configValue = 'cancellation';
                }

                 $blResult = $oDocument->getType()->getKey() === $configValue;
            }

            if (!$blResult && method_exists($oDocument->getType(), 'getId')) {
                $blResult = (int)$oDocument->getType()->getId() === 4;
            }
        }
        return $blResult;
    }

    public function getAttributeValue($sKey) {
        $sReturn = null;
        if (strpos($sKey, 'a_') === 0) {
            $sKey = substr($sKey, 2);

            // methods are defined without underscores...
            $function = str_replace('_', '', 'get'.$sKey);

            if (method_exists($this->getShopOrderObject()->getAttribute(), $function)) {
                $sReturn = $this->getShopOrderObject()->getAttribute()->{$function}();
            }
            if ($sReturn === null) {
                $sTableName = Shopware()->Models()->getClassMetadata('Shopware\Models\Attribute\Order')->getTableName();
                if (MLDatabase::getDbInstance()->columnExistsInTable($sKey, $sTableName)) {
                    $sReturn = Shopware()->Db()
                        ->fetchOne('select `'.$sKey.'` from '.$sTableName.' where orderID='.$this->getShopOrderObject()->getId());
                }
            }
        }
        return $sReturn;
    }

    public function getShopOrderProducts() {
        return array();
    }
}
