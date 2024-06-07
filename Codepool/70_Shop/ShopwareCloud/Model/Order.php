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

include_once(M_DIR_LIBRARY . 'request/shopware/ShopwareOrder.php');
include_once(M_DIR_LIBRARY . 'request/shopware/ShopwareDocument.php');
include_once(M_DIR_LIBRARY . 'request/shopware/ShopwareStateMachine.php');
include_once(M_DIR_LIBRARY . 'request/shopware/ShopwareOrderDocument.php');
include_once(M_DIR_LIBRARY . 'request/shopware/ShopwareOrderDelivery.php');
include_once(M_DIR_LIBRARY . 'request/shopware/ShopwareShippingMethod.php');
include_once(M_DIR_LIBRARY . 'request/shopware/ShopwareOrderTransaction.php');
include_once(M_DIR_LIBRARY . 'request/shopware/ShopwareStateMachineHistory.php');

use Doctrine\DBAL\DBALException;
use library\request\shopware\ShopwareDocument;
use library\request\shopware\ShopwareOrder;
use library\request\shopware\ShopwareOrderDelivery;
use library\request\shopware\ShopwareOrderDocument;
use library\request\shopware\ShopwareOrderTransaction;
use library\request\shopware\ShopwareShippingMethod;
use library\request\shopware\ShopwareStateMachine;
use library\request\shopware\ShopwareStateMachineHistory;

MLFilesystem::gi()->loadClass('Shop_Model_Order_Abstract');

class ML_ShopwareCloud_Model_Order extends ML_Shop_Model_Order_Abstract {

    protected $blUpdatablePaymentStatus;
    protected $blUpdatableOrderStatus;
    public static $ShopId = null;


    public function getUpdatablePaymentStatus() {
        return $this->blUpdatablePaymentStatus;
    }

    public function setUpdatablePaymentStatus($blUpdatable) {
        $this->blUpdatablePaymentStatus = $blUpdatable;
    }

    public function getUpdatableOrderStatus() {
        return $this->blUpdatableOrderStatus;
    }

    public function setUpdatableOrderStatus($blUpdatable) {
        $this->blUpdatableOrderStatus = $blUpdatable;
    }

    /**
     *
     * @param string[] $aAssociations
     * @return object
     * @throws Exception
     */
    public function  getShopOrderObject($sCurrentOrderId = null, $wholeObject = false) {
        $sCurrentOrderId = ($sCurrentOrderId == null) ? $this->get('current_orders_id') : $sCurrentOrderId;
        if (!isset(self::$aOrdersCache[$sCurrentOrderId])) {
            $sShopId = MLShopwareCloudAlias::getShopHelper()->getShopId();
            $oShopwareOrderRequest = new ShopwareOrder($sShopId);
            $oResponse = $oShopwareOrderRequest->getShopwareOrders('/api/order/'.$sCurrentOrderId);
            if (isset($oResponse) && is_object($oResponse)) {
                self::$aOrdersCache[$sCurrentOrderId] = $oResponse;
            } else {
                throw new Exception('This order is not found in shop');
            }
        }
        if ($wholeObject) {
            $result = self::$aOrdersCache[$sCurrentOrderId];
        } else {
            $result = self::$aOrdersCache[$sCurrentOrderId]->getData();
        }

        return $result;
    }

    public static $aOrdersCache = array();

    public static function getOutOfSyncOrdersArray($iOffset = 0, $blCount = false) {
        $sLastSync = MLModule::gi()->getConfig('last.order.sync');
        //number of days we get the orders in the past it can be set in the query string as "shopwarecloud_order_sync_days"
        $numberOfDays = 30;
        if (isset($_GET['shopwarecloud_order_sync_days'])) {
            $numberOfDays = (int)$_GET['shopwarecloud_order_sync_days'];
        }
        if (!$sLastSync) {
            $oDate = new DateTime();
            //set time limit to 60 days in the past
            $sLastSync = date('Y-m-d', $oDate->getTimestamp() - 86400 * $numberOfDays);
        } else {
            $oDate = new DateTime();
            $oLastSyncDate = new DateTime($sLastSync);
            //set time limit to 60 days in the past
            $sLastSync = date('Y-m-d', $oLastSyncDate->getTimestamp() - 86400 * $numberOfDays);
        }

        MLModule::gi()->setConfig('last.order.sync', $oDate->format('Y-m-d'));
        $sShopId = MLShopwareCloudAlias::getShopHelper()->getShopId();
        $oShopwareOrderRequest = new ShopwareOrder($sShopId);
        $aFilters = [
            "filter" => [
                [
                    "type" => "range",
                    "field" => "updatedAt",
                    "parameters" => [
                        "gte" => $sLastSync,
                    ]
                ]
            ]
        ];
        $aResponse = $oShopwareOrderRequest->getShopwareOrders('/api/search/order', 'POST', $aFilters, true);
        $aShopOrderIds = array();
        foreach ($aResponse['data'] as $key => $aShopOrder) {
            foreach ($aResponse['included'] as $aStateMachine) {
                if ($aStateMachine['type'] == 'state_machine_state' && $aStateMachine['id'] == $aShopOrder['attributes']['stateId']) {
                    $aResponse['data'][$key]['attributes']['technicalName'] = $aStateMachine['attributes']['technicalName'];
                }
            }
            $aShopOrderIds[] = "'".$aShopOrder['id']."'";

        }
        if (count($aShopOrderIds) > 0) {
            $aMagnalisterOrders = MLDatabase::getDbInstance()->fetchArray("SELECT `orders_id`, `current_orders_id`, `status` FROM `magnalister_orders` WHERE `current_orders_id` IN (" . implode(',', $aShopOrderIds) . ") AND `mpID` = " . MLModule::gi()->getMarketPlaceId());
        }
        $aOrderIds = array();
        if ($aMagnalisterOrders) {
            foreach ($aMagnalisterOrders as $aMagnalisterOrder) {
                foreach ($aResponse['data'] as $aShopOrder) {
                    if (
                        $aShopOrder['id'] === $aMagnalisterOrder['current_orders_id'] &&
                        $aShopOrder['attributes']['technicalName'] !== $aMagnalisterOrder['status']
                    ) {
                        $aOrderIds[] = $aShopOrder['id'];
                    }
                }
            }
        }

        if ($blCount) {
            return count($aOrderIds);
        } else {
            return array_slice($aOrderIds, $iOffset, 100);
        }
    }

    public function existsInShop() {
        try {
            $this->getShopOrderObject();
        } catch (Exception $ex) {
            if ($ex->getCode() === 1622809739) {
                return false;
            } else {
                throw $ex;
            }
        }
        return true;
    }

    /**
     * return uuid of current order state
     * @return string|null
     */
    public function getShopOrderStatus(): ?string {
        try {
            $sOrderStatus = null;
            $sCurrentOrderId = $this->get('current_orders_id');
            if (!isset(self::$aOrdersCache[$sCurrentOrderId])) {
                $sShopId = MLShopwareCloudAlias::getShopHelper()->getShopId();
                $oShopwareOrderRequest = new ShopwareOrder($sShopId);
                $aResponse = $oShopwareOrderRequest->getShopwareOrders('/api/order/'.$sCurrentOrderId)->getIncluded();
            } else {
                $aResponse = self::$aOrdersCache[$sCurrentOrderId]->getIncluded();
            }

            foreach ($aResponse as $oIncluded) {
                if ($oIncluded->getType() == 'state_machine_state') {
                    $sOrderStatus = $oIncluded->getAttributes()['technicalName'];
                }
            }
            return $sOrderStatus;
        } catch (Exception $oExc) {
            return null;
        }
    }

    public function getShopPaymentStatus() {
        try {
            return $this->getStateMachineName();
        } catch (Exception $oExc) {
            MLMessage::gi()->addDebug($oExc);
        }

        return null;
    }

    /**
     *
     * @return string|null
     */
    public function getShopOrderStatusName() {
        try {
            return $this->getStateMachineName('name');
        } catch (Exception $oExc) {
            return null;
        }
    }

    private function getStateMachineName($fieldName = 'technicalName') {
        $sShopPaymentStatus = null;
        $oOrder = $this->getShopOrderObject();
        $sShopId = MLShopwareCloudAlias::getShopHelper()->getShopId();

        $oTransactionRequest = new ShopwareOrderTransaction($sShopId);
        $oTransactions = $oTransactionRequest->getOrderTransaction('/api/order/'.$oOrder->getId().'/transactions');

        foreach ($oTransactions->getIncluded() as $oIncluded) {
            if ($oIncluded->getType() == 'state_machine_state') {
                $sShopPaymentStatus = $oIncluded->getAttributes()[$fieldName];
            }
        }
        return $sShopPaymentStatus;
    }

    public function getEditLink() {
        self::$ShopId = $_GET['shop-url'];
        if (!isset($_GET['shop-url']) && isset($_GET['shop-id'])) {
            self::$ShopId = MagnaDB::gi()->fetchOne("SELECT `Shopware_ShopUrl` FROM `Customer` WHERE `Shopware_ShopId` = '". $_GET['shop-id'] . "'");
        }
        return self::$ShopId.'/admin#/sw/order/detail/'.$this->get('current_orders_id');
    }

    public function getShippingCarrier() {
        $sDefaultCarrier = $this->getMarketplaceDefaultCarrier();
        if ($sDefaultCarrier == '-1') {
            return $this->getShopOrderCarrierOrShippingMethod();
        } elseif ($sDefaultCarrier == '') {
            return null;
        } else {
            return $sDefaultCarrier;
        }
    }

    public function getShopOrderCarrierOrShippingMethod() {
        try {
            $sCarrier = null;
            $oOrder = $this->getShopOrderObject();
            $sShopId = MLShopwareCloudAlias::getShopHelper()->getShopId();

            $oDeliveryRequest = new ShopwareOrderDelivery($sShopId);
            $oDelivery = $oDeliveryRequest->getOrderDelivery('/api/order/'.$oOrder->getId().'/deliveries');

            if (count($oDelivery->getData())) {
                $oShippingRequest = new ShopwareShippingMethod($sShopId);
                $oShippingMethod = $oShippingRequest->getShippingMethods('/api/order-delivery/'.$oDelivery->getData()[0]->getId().'/shipping-method');

                if (count($oShippingMethod->getData())) {
                    $sCarrier = $oShippingMethod->getData()[0]->getAttributes()->getName();
                }
            }

            return $sCarrier;
        } catch (Exception $oEx) {
            return 'failed';
        }
    }

    public function getShopOrderCarrierOrShippingMethodId() {
        $sReturn = null;
        $oOrder = $this->getShopOrderObject();
        $sShopId = MLShopwareCloudAlias::getShopHelper()->getShopId();
        $oDeliveryRequest = new ShopwareOrder($sShopId);
        $oDelivery = $oDeliveryRequest->getShopwareOrderDeliveries('/api/order/'.$oOrder->getId().'/deliveries');
        if (count($oDelivery->getData())) {
            $sReturn = $oDelivery->getData()[0]->getAttributes()->getShippingMethodId();
        }

        return $sReturn;
    }

    public function getShippingDateTime() {
        $mTime = null;
        try {
            $oOrder = $this->getShopOrderObject();
            $sShopId = MLShopwareCloudAlias::getShopHelper()->getShopId();
            $oStateMachineRequest = new ShopwareStateMachine($sShopId);
            $oStateMachine = $oStateMachineRequest->getStateMachine('/api/order/'.$oOrder->getId().'/state-machine-state', 'get', [], false);
            $technicalName = $oStateMachine->getData()[0]->getAttributes()->getTechnicalName();

            if (MLService::getSyncOrderStatusInstance()->isShipped($technicalName)) {
                $filter = [
                    "filter" => [
                        [ 
                            "type" => "contains", 
                            "field" => "entityId", 
                            "value" => $oOrder->getId()
                        ],
                        [
                            "type" => "equals", 
                            "field" => "toStateId", 
                            "value" => $oOrder->getAttributes()->getStateId()
                        ],
                        [
                            "type" => "equals", 
                            "field" => "entityName", 
                            "value" => "order"
                        ]
                    ],
                    "sort" => [
                        [ "field" => "createdAt", "order" => "DESC" ]
                    ],
                    "limit" => 1
                ];

                $oShopwareStateMachineHistoryRequest = new ShopwareStateMachineHistory($sShopId);
                $oHistory = $oShopwareStateMachineHistoryRequest->getStateMachineHistory('/api/search/state-machine-history', 'POST', $filter, false);

                if (is_array($oHistory->getData()) && count($oHistory->getData())) {
                    $mTime = date_format(new DateTime($oHistory->getData()[0]->getAttributes()->getCreatedAt()), "Y-m-d H:i:s");
                }
            }
        } catch (Exception $oEx) {
        }
        return $mTime;
    }

    public function getShopOrderId() {
        try {
            return $this->getShopOrderObject()->getId();
        } catch (Exception $oEx) {//if order deosn't exist in shopware
            return $this->get('orders_id');
        }
    }

    public function setSpecificAcknowledgeField(&$aOrderParameters, $aOrder) {

    }

    public function getShippingDate() {
        return substr($this->getShippingDateTime(), 0, 10);
    }

    public function getShippingTrackingCode() {
        $sTracking = null;
        try {
            $oOrder = $this->getShopOrderObject();
            $sShopId = MLShopwareCloudAlias::getShopHelper()->getShopId();

            $oDeliveryRequest = new ShopwareOrderDelivery($sShopId);
            $oDelivery = $oDeliveryRequest->getOrderDelivery('/api/order/'.$oOrder->getId().'/deliveries');

            if (count($oDelivery->getData()) && count($oDelivery->getData()[0]->getAttributes()->getTrackingCodes()) > 0) {
                $sTracking = $oDelivery->getData()[0]->getAttributes()->getTrackingCodes()[0];
            }
        } catch (Exception $exc) {
            MLMessage::gi()->addDebug($exc);
        }
        return $sTracking;
    }

    public function getShopOrderLastChangedDate() {
        try {
            if ($this->getShopOrderObject()->getAttributes()->getUpdatedAt() !== null) {
                return $this->getShopOrderObject()->getAttributes()->getUpdatedAt();
            }
        } catch (Exception $oEx) {//if order deosn't exist in shopware

        }
        return null;
    }

    /**
     * @param array $aData
     * @return array
     * @throws DBALException
     */
    public function shopOrderByMagnaOrderData($aData) {
        $oSHopOrderHelper = MLShopwareCloudAlias::getShopOrderHelper();
        $mReturn = $oSHopOrderHelper
            ->setOrder($this)
            ->setNewOrderData($aData)
            ->shopOrder();
        return $mReturn;
    }

    public function getShopOrderTotalAmount() {
        try {
            return $this->getShopOrderObject()->getAttributes()->getPrice()->getTotalPrice();
        } catch (Exception $exc) {
            MLMessage::gi()->addDebug($exc);
        }
    }

    public function getShopOrderTotalTax() {
        try {
            $oOrderPrice = $this->getShopOrderObject()->getAttributes()->getPrice();
            return $oOrderPrice->getTotalPrice() - $oOrderPrice->getNetPrice();
        } catch (Exception $exc) {
            MLMessage::gi()->addDebug($exc);
        }
    }

    /**
     * Check if document is specific type
     *  See Database: document_type | document_type_translation
     *
     * @param $sType
     * @return string
     */
    public function getShopOrderInvoice($sType) {
        $sFileContent = '';
        try {
            //use configuration value from expert settings if provided - fallback is "invoice"
            $configInvoiceType = MLModule::gi()->getConfig('invoice.invoice_documenttype');
            if ($configInvoiceType === null) {
                $configInvoiceType = 'invoice';
            }
            //use configuration value from expert settings if provided - fallback is "cancellation"
            $configCreditNoteType = MLModule::gi()->getConfig('invoice.creditnote_documenttype');
            if ($configCreditNoteType === null) {
                $configCreditNoteType = 'storno';
            }

            $oOrder = $this->getShopOrderObject();
            $sShopId = MLShopwareCloudAlias::getShopHelper()->getShopId();
            $oDocumentRequest = new ShopwareOrderDocument($sShopId);
            $oDocuments = $oDocumentRequest->getOrderDocuments('/api/order/'.$oOrder->getId().'/documents');

            foreach ($oDocuments->getData() as $oDocument) {
                $oDocumentTypeRequest = new ShopwareDocument($sShopId);
                $oDocumentType = $oDocumentTypeRequest->getShopwareDocumentType('/api/document/'.$oDocument->getId().'/document-type', 'GET', [], false);

                if ($this->isInvoiceDocumentType($sType) && $oDocumentType->getData()[0]->getAttributes()->getTechnicalName() === $configInvoiceType) {
                    $sFileContent = base64_encode($oDocumentTypeRequest->getShopwareDocumentType('/api/_action/document/'.$oDocument->getId().'/'.$oDocument->getAttributes()->getDeepLinkCode(),'GET', array(), true));
                } elseif ($this->isCreditNoteDocumentType($sType) && $oDocumentType->getData()[0]->getAttributes()->getTechnicalName() === $configCreditNoteType) {
                    $sFileContent = base64_encode($oDocumentTypeRequest->getShopwareDocumentType('/api/_action/document/'.$oDocument->getId().'/'.$oDocument->getAttributes()->getDeepLinkCode(),'GET', array(), true));
                }
            }
        } catch (Exception $exc) {
            MLMessage::gi()->addDebug($exc);
        }
        return $sFileContent;
    }

    public function getShopOrderInvoiceNumber($sType) {
        $sInvoiceNumber = '';
        try {
            $configInvoiceType = MLModule::gi()->getConfig('invoice.invoice_documenttype');
            if ($configInvoiceType === null) {
                $configInvoiceType = 'invoice';
            }
            //use configuration value from expert settings if provided - fallback is "cancellation"
            $configCreditNoteType = MLModule::gi()->getConfig('invoice.creditnote_documenttype');
            if ($configCreditNoteType === null) {
                $configCreditNoteType = 'storno';
            }

            $oOrder = $this->getShopOrderObject();
            $sShopId = MLShopwareCloudAlias::getShopHelper()->getShopId();
            $oDocumentRequest = new ShopwareOrderDocument($sShopId);
            $oDocuments = $oDocumentRequest->getOrderDocuments('/api/order/'.$oOrder->getId().'/documents');

            foreach ($oDocuments->getData() as $oDocument) {
                $oDocumentTypeRequest = new ShopwareDocument($sShopId);
                $oDocumentType = $oDocumentTypeRequest->getShopwareDocumentType('/api/document/'.$oDocument->getId().'/document-type', 'GET', [], false);

                if ($this->isInvoiceDocumentType($sType) && $oDocumentType->getData()[0]->getAttributes()->getTechnicalName() === $configInvoiceType) {
                    $sInvoiceNumber = $oDocument->getAttributes()->getConfig()['documentNumber'];
                    break;
                } elseif ($this->isCreditNoteDocumentType($sType) && $oDocumentType->getData()[0]->getAttributes()->getTechnicalName() === $configCreditNoteType) {
                    $sInvoiceNumber = $oDocument->getAttributes()->getConfig()['documentNumber'];
                    break;
                }
            }
        } catch (Exception $exc) {
            MLMessage::gi()->addDebug($exc);
        }
        return $sInvoiceNumber;
    }

    public function getShopOrderProducts() {
        return array();
    }

    /**
     *
     * @param $sKey
     * @return mixed|null
     */
    public function getAdditionalOrderField($sKey) {
        $oOrder = $this->getShopOrderObject();
        if (is_array($oOrder->getAttributes()->getCustomFields())) {
            foreach ($oOrder->getAttributes()->getCustomFields() as $sAttributeKey => $sAttribute) {
                if ($sAttributeKey === $sKey) {
                    return $sAttribute;
                }
            }
            return null;
        }

        return null;
    }

    public function getAttributeValue($sName) {
        $sReturn = null;
        if (strpos($sName, 'a_') === 0) {
            $sName = substr($sName, 2);
            $oOrder = $this->getShopOrderObject();
            if (isset($oOrder->getAttributes()->getCustomFields()[$sName])) {
                $sReturn = $oOrder->getAttributes()->getCustomFields()[$sName];
            }
        }
        return $sReturn;
    }

    public function getLogo() {
        if ($this->get('platform') !== null) {
            if ($this->get('logo') !== null) {
                $sLogo = $this->get('logo');
            } else {
                $sOrderLogoClass = 'ML_' . ucfirst($this->get('platform')) . '_Model_OrderLogo';
                if (class_exists($sOrderLogoClass, false)) {
                    $oOrderLogo = new $sOrderLogoClass;
                    $sLogo = $oOrderLogo->getLogo($this);
                    $this->set('logo', $sLogo);
                } else {
                    return null;
                }
            }
            return MLHttp::gi()->getResourceUrl('images/logos/' . $sLogo, true);
        } else {
            return null;
        }
    }


}
