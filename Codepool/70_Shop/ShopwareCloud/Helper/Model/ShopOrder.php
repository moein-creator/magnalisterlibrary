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

MLFilesystem::gi()->loadClass('Shop_Helper_Model_ShopOrder_Abstract');

include_once(M_DIR_LIBRARY . 'request/shopware/ShopwareCustomer.php');
include_once(M_DIR_LIBRARY . 'request/shopware/ShopwarePaymentMethod.php');
include_once(M_DIR_LIBRARY . 'request/shopware/ShopwareCountry.php');
include_once(M_DIR_LIBRARY . 'request/shopware/ShopwareCurrency.php');
include_once(M_DIR_LIBRARY . 'request/shopware/ShopwareSalesChanel.php');
include_once(M_DIR_LIBRARY . 'request/shopware/ShopwareNumberRange.php');
include_once(M_DIR_LIBRARY . 'request/shopware/ShopwareOrder.php');
include_once(M_DIR_LIBRARY . 'request/shopware/ShopwareOrderStatus.php');
include_once(M_DIR_LIBRARY . 'request/shopware/ShopwareShippingMethod.php');
include_once(M_DIR_LIBRARY . 'request/shopware/ShopwareRule.php');
include_once(M_DIR_LIBRARY . 'request/shopware/ShopwareDeliveryTime.php');
include_once(M_DIR_LIBRARY . 'request/shopware/ShopwareProduct.php');

use library\request\shopware\ShopwareCountry;
use library\request\shopware\ShopwareCurrency;
use library\request\shopware\ShopwareCustomer;
use library\request\shopware\ShopwareDeliveryTime;
use library\request\shopware\ShopwareNumberRange;
use library\request\shopware\ShopwareOrder;
use library\request\shopware\ShopwareOrderStatus;
use library\request\shopware\ShopwareOrderTransaction;
use library\request\shopware\ShopwarePaymentMethod;
use library\request\shopware\ShopwareProduct;
use library\request\shopware\ShopwareRule;
use library\request\shopware\ShopwareSalesChanel;
use library\request\shopware\ShopwareShippingMethod;

class ML_ShopwareCloud_Helper_Model_ShopOrder extends ML_Shop_Helper_Model_ShopOrder_Abstract {

    /** @var src\Model\Shopware\Order\ShopwareOrder  $oExistingOrder */
    protected $oExistingOrder = null;

    /** @var src\Model\Shopware\SalesChannel\ShopwareSalesChannel $oSalesChanel */
    protected $oSalesChanel;

    /** @var src\Model\Shopware\Order\ShopwareOrderLineItems  $oExistingOrderLineItems */
    protected $oExistingOrderLineItems = null;

    /** @var  $iExistingOrderLineItemsCount */
    protected $iExistingOrderLineItemsCount = null;

    /**
     * @var array $aNewProduct
     */
    protected $aNewProduct = array();

    /** @var ML_ShopwareCloud_Model_Order $oOrder */
    protected $oOrder = null;

    /**
     * @var float
     */
    protected $fTotalAmount = 0.00;

    /**
     * @var float
     */
    protected $fTotalAmountNet = 0.00;

    /**
     * @var float
     */
    protected $fMaxProductTax;

    /**
     * Position of order line item
     * @var int
     */
    protected $productLastPosition = 0;

    protected $aCuttedField = [];

    /** @var library\request\shopware\ShopwareCustomer $oCustomerRequest */
    protected $oCustomerRequest;
    /** @var library\request\shopware\ShopwarePaymentMethod $oPaymentRequest */
    protected $oPaymentRequest;
    /** @var library\request\shopware\ShopwareCountry $oCountryRequest */
    protected $oCountryRequest;
    /** @var library\request\shopware\ShopwareCurrency $oCurrencyRequest */
    protected $oCurrencyRequest;
    /** @var library\request\shopware\ShopwareSalesChanel $oSalesChanelRequest */
    protected $oSalesChanelRequest;

    /** @var library\request\shopware\ShopwareOrder $oShopwareOrderRequest */
    protected $oShopwareOrderRequest;

    /** @var library\request\shopware\ShopwareNumberRange $oEntityNumberRequest */
    protected $oEntityNumberRequest;

    /** @var library\request\shopware\ShopwareOrderStatus $oOrderStatusRequest */
    protected $oOrderStatusRequest;


    /** @var APIHelper $oApiHelper */
    protected $oApiHelper;
    protected $sShopId;
    protected $sOrderNumberStateId;
    protected $states;
    protected $aItemLines = [];

    public function __construct() {
        $this->sShopId = MLShopwareCloudAlias::getShopHelper()->getShopId();
        $this->oShopwareOrderRequest = new ShopwareOrder($this->sShopId, null, false);
        $this->oApiHelper = new APIHelper();
        $this->oPaymentRequest = new ShopwarePaymentMethod($this->sShopId, null, false);
        $this->oOrderStatusRequest = new ShopwareOrderStatus($this->sShopId, null, false);
    }


    /**
     * set oder object in initializing the order helper
     * @param ML_ShopwareCloud_Model_Order $oOrder
     * @return ML_ShopwareCloud_Helper_Model_ShopOrder
     * @throws Exception
     */
    public function setOrder($oOrder) {
        $this->aCuttedField = [];
        $this->aItemLines = [];
        $this->fTotalAmount = 0.00;
        $this->fTotalAmountNet = 0.00;
        $this->fMaxProductTax = 0.00;
        $this->productLastPosition = 0;
        $this->oOrder = $oOrder;
        $this->oExistingOrder = null;
        if ($this->oOrder->exists() && $this->oOrder->existsInShop()) {
            $this->oExistingOrder = $oOrder->getShopOrderObject();
        }
        $this->aExistingOrderData = $oOrder->get('orderdata');
        return $this;
    }

    /**
     * initializing order import and update
     * @return array
     * @throws Exception
     */
    public function shopOrder(): array {
        if ($this->aExistingOrderData === null || count($this->aExistingOrderData) == 0) {
            $aReturnData = $this->createUpdateOrder();
        } elseif (!is_object($this->oExistingOrder)) {// if order doesn't exist in shop  we create new order
            $this->aNewData = MLHelper::gi('model_service_orderdata_merge')->mergeServiceOrderData($this->aNewData, $this->aExistingOrderData, $this->oOrder);
            $aReturnData = $this->createUpdateOrder();
        } else {//update order if exist
            if ($this->checkForUpdate()) {
                $this->aNewData = MLHelper::gi('model_service_orderdata_merge')->mergeServiceOrderData($this->aNewData, $this->aExistingOrderData, $this->oOrder);
                $this->updateOrder();
                $aReturnData = $this->aNewData;
            } else {
                $this->aNewProduct = $this->aNewData['Products'];
                $this->aNewData = MLHelper::gi('model_service_orderdata_merge')->mergeServiceOrderData($this->aNewData, $this->aExistingOrderData, $this->oOrder);
                $aReturnData = $this->createUpdateOrder();
            }
        }
        return $aReturnData;
    }

    /**
     * Only if shipping method name or payment method name or order, payment status is changed
     * @return ML_ShopwareCloud_Helper_Model_ShopOrder
     * @throws Exception
     */
    public function updateOrder() {
        $orderData = [
            'id' => $this->oExistingOrder->getId(),
        ];
        if ($this->oOrder->getUpdatableOrderStatus()) {
            $sTransitionOrderAction = $this->getStatusTransition(MLSetting::gi()->get('ORDER_STATES_STATE_MACHINE'), $this->aNewData['Order']['Status']);
            if (isset($sTransitionOrderAction)) {
                $this->states['orderState'][$this->oExistingOrder->getId()] = $sTransitionOrderAction;
            }
            $orderData['deliveries'] = [
                [
                    'id'               => $this->getExistingDeliveryId(),
                    'shippingMethodId' => $this->getShippingMethod(),
                ],
            ];
        }

        if ($this->oOrder->getUpdatablePaymentStatus()) {
            $transactionId = $this->getExistingTransactionId();
            $sTransitionTransactionAction = $this->getStatusTransition(MLSetting::gi()->get('ORDER_TRANSACTION_STATES_STATE_MACHINE'), $this->getPaymentStatus());
            if (isset($sTransitionTransactionAction)) {
                $this->states['transactionState'][$transactionId] = $sTransitionTransactionAction;
            }
            $orderData['transactions'][] =
                [
                    'id'              => $transactionId,
                    'paymentMethodId' => $this->getPaymentMethodId(),
                ];

        }

        // add magnalister order details to custom field
        if ($this->oApiHelper->checkIfCustomFieldExists($this->sShopId)) {
            $orderData['customFields'] = array(
                $this->oApiHelper::ORDER_DETAILS_FIELD => $this->getMagnalisterOrderDetails($this->aNewData)
            );
        }
        if ($this->oOrder->getUpdatableOrderStatus() || $this->oOrder->getUpdatablePaymentStatus()) {
            $this->updateOrderStates();
            $this->oShopwareOrderRequest->createShopwareOrders($orderData, 'PATCH', $this->oExistingOrder->getId());
        }


        return $this;
    }


    /**
     * Based on status type and on the technical name of the
     * status we filter out transition name action
     *
     * @param $stateType
     * @param $status
     * @return mixed|null
     */
    public function getStatusTransition($statusType, $status) {
        $sTransitionAction = null;
        /** @var ML_ShopwareCloud_Helper_Model_Shop  $shopHelper  */
        $shopHelper =  MLHelper::gi('model_shop');
        /** @var src\Model\Shopware\OrderStatus\ShopwareOrderStatuses $oOrderStatuses */
        $oOrderStatuses = $shopHelper->getOrderStatuses($this->oOrderStatusRequest, $this->oApiHelper, $statusType, $status, array('toStateMachineTransitions' => array()));
        if (isset($oOrderStatuses->getData()[0])) {
            if (isset($oOrderStatuses->getIncluded()[0])) {
                foreach ($oOrderStatuses->getIncluded() as $oTransitionAction) {
                    // get the state based on the status set in the configuration
                    $aTransitionActionAttributes = $oTransitionAction->getAttributes();
                    // get the state from the existing order
                    $sFromStateId = $this->getFromStateId($statusType);
                    if ($aTransitionActionAttributes['toStateId'] == $oOrderStatuses->getData()[0]->getId() && $aTransitionActionAttributes['fromStateId'] == $sFromStateId) {
                        $sTransitionAction = $aTransitionActionAttributes['actionName'];
                    }
                }
                if (!isset($sTransitionAction)) {
                    $sMlOrderId = MLSetting::gi()->get('sCurrentOrderImportMarketplaceOrderId');
                    $message = MLI18n::gi()->get('Shopware_Orderimport_StateTransitionError', array('status' => $status, 'statusType' => $statusType));
                    MLErrorLog::gi()->addError(0, ' ', $message, array('MOrderID' => $sMlOrderId));
                    throw new Exception($message);
                }
            }
        }

        return $sTransitionAction;
    }

    /**
     * Gets the state id based on the state type (order state and order transaction state)
     *
     * @param $stateType
     * @return string|null
     * @throws Exception
     */
    private function getFromStateId($stateType) {
        $sFromStateId = null;
        if ($stateType === MLSetting::gi()->get('ORDER_STATES_STATE_MACHINE')) {
            $sFromStateId = $this->oExistingOrder->getAttributes()->getStateId();
        } else if ($stateType === MLSetting::gi()->get('ORDER_TRANSACTION_STATES_STATE_MACHINE')) {
            /** @var src\Model\Shopware\OrderTransaction\ShopwareOrderTransaction $oTransaction */
            $oTransaction = $this->getFirstTransaction();
            $sFromStateId = $oTransaction->getAttributes()->getStateId();
        }
        return $sFromStateId;
    }

    /**
     * create a new order by magnalister order data
     * @return array
     * @throws Exception
     * @see \Shopware\Core\Framework\Test\DataAbstractionLayer\Dbal\EntityForeignKeyResolverTest::createOrder
     */
    public function createUpdateOrder() {
        try {
            $this->oCustomerRequest = new ShopwareCustomer($this->sShopId, null, false);
            $this->oCountryRequest = new ShopwareCountry($this->sShopId, null, false);
            $this->oCurrencyRequest = new ShopwareCurrency($this->sShopId, null, false);
            $this->oEntityNumberRequest = new ShopwareNumberRange($this->sShopId, null, false);
            $this->oSalesChanelRequest = new ShopwareSalesChanel($this->sShopId, null, false);

            $aData = $this->aNewData;
            $aAddresses = $aData['AddressSets'];
            $this->oSalesChanel = $this->getSalesChannel();

            if (empty($aAddresses['Main'])) {// add new order when Main address is filled
                throw new Exception('main address is empty');
            }

            if (count($aData['Products']) <= 0) {// add new order when order has any product
                throw new Exception('product is empty');
            }

            // START HACK - it's not possible to get correct result when using as this parameter ?isoCode=EUR
            $currencyFilters = [
                'isoCode' => [
                    'type' => 'equals',
                    'values' => $aData['Order']['Currency']
                ],
            ];
            $currencyRequestFilter = $this->oApiHelper->prepareFilters($currencyFilters,'POST','/api/currency/');
            // END this works: ?filter[0][field]=isoCode&filter[0][type]=equals&filter[0][value]=EUR

            $preparedCurrencyPath = $this->oApiHelper->prepareFilters($currencyRequestFilter,'GET','/api/currency/');
            /** @var src\Model\Shopware\Currency\ShopwareCurrencies $oCurrency */
            $oCurrency = $this->oCurrencyRequest->getShopwareCurrencies($preparedCurrencyPath);
            if (!is_array($oCurrency->getData())) {
                $sMessage = MLI18n::gi()->get('Orderimport_CurrencyCodeDontExistsError', array(
                        'mpOrderId' => MLSetting::gi()->get('sCurrentOrderImportMarketplaceOrderId'),
                        'ISO'       => $aData['Order']['Currency']
                    )
                );
                MLErrorLog::gi()->addError(0, ' ', $sMessage, array('MOrderID' => MLSetting::gi()->get('sCurrentOrderImportMarketplaceOrderId')));
                throw new Exception($sMessage);
            }
            $oCurrency = $oCurrency->getData()[0];
            //show  in order detail
            $sInternalComment = isset($aData['MPSpecific']['InternalComment']) ? $aData['MPSpecific']['InternalComment'] : '';
            //show in order detail and invoice pdf
//            $sCustomerComment = '';
//            if (MLModule::gi()->getConfig('order.information')) {
//                $sCustomerComment .= isset($aData['MPSpecific']['InternalComment']) ? $aData['MPSpecific']['InternalComment'] : '';
//            }

            $iPaymentID = $this->getPaymentMethodId();
            $aBillingAddress = $this->getAddress($aData['AddressSets'], 'Billing');
            $aShippingAddress = $this->getAddress($aData['AddressSets'], 'Shipping');
            /** @var src\Model\Shopware\Customer\ShopwareCustomer $oCustomer */
            $oCustomer = $this->getCustomer();
            $this->aItemLines = $this->addProductsAndTotals();
            $aShippingCost = $this->getShippingCost();
            $configPaymentStatus = $this->getPaymentStatus();
            if (count($this->aCuttedField) > 0) {
                $sInternalComment .= "\n".'Truncated fields:'."\n";
            }

            foreach ($this->aCuttedField as $sFiledName => $sFieldValue) {
                $sInternalComment .= 'Original value of "'.$sFiledName.'":'.$sFieldValue."\n";
            }
            $blNewDelivery = $this->oExistingOrder === null || $this->getExistingDelivery() === null;
            $this->fTotalAmountNet = round($this->fTotalAmountNet, 2);
            $taxRate = $this->getTaxRate();
            $aPrice = array(
                'netPrice' => $this->fTotalAmountNet,
                'totalPrice' => $this->fTotalAmount,
                'positionPrice' => $this->fTotalProductAmount,
                'calculatedTaxes' => array(array(
                    'tax' => $this->fTotalAmount - $this->fTotalAmountNet,
                    'taxRate' => $taxRate,
                    'price' => $this->fTotalAmount,
                    'extensions' => [],
                )),
                'taxRules' => array(array(
                    'taxRate' => $taxRate,
                    'percentage' => 100.0
                )),
                'rawTotal' => $this->fTotalAmount,
                'taxStatus' => MLSetting::gi()->get('CART_PRICE_TAX_STATE_GROSS'),
            );
            $sOrderNumber = $this->getShopwareOrderNumber();
            $orderData = [
                'id'              => $this->oExistingOrder === null ? $this->oApiHelper::randomHex() : $this->oExistingOrder->getId(),
                'orderNumber'     => $sOrderNumber,
                'currencyId'      => $oCurrency->getId(),
                'languageId'      => $this->oSalesChanel->getAttributes()->getLanguageId(), //Defaults::LANGUAGE_SYSTEM use default from SalesChannel
                'deepLinkCode'    => $this->oApiHelper->getBase64UrlString(32),
                'salesChannelId'  => $this->oSalesChanel->getId(),
                'currencyFactor'  => $oCurrency->getAttributes()->getFactor(),
                'stateId'         => $this->getOrderStateId($aData['Order']['Status'], MLSetting::gi()->get('ORDER_STATES_STATE_MACHINE')),
                'price'           => $aPrice,
                'shippingCosts'   => $aShippingCost,
                'customerComment' => $sInternalComment,
                'orderCustomer'   => [
                    'id'             => $this->oExistingOrder === null ? $this->oApiHelper::randomHex() : $this->oExistingOrder->getRelationships()->getOrderCustomer()->getData()['id'],
                    'customerId'     => $oCustomer->getId(),
                    'salutationId'   => $oCustomer->getAttributes()->getSalutationId(),
                    'email'          => $oCustomer->getAttributes()->getEmail(),
                    'firstName'      => $oCustomer->getAttributes()->getFirstName(),
                    'lastName'       => $oCustomer->getAttributes()->getLastName(),
                    'customerNumber' => $oCustomer->getAttributes()->getCustomerNumber(),
                ],
                'transactions' => [
                    [
                        'id' => $this->oExistingOrder === null ? $this->oApiHelper::randomHex() : $this->getExistingTransactionId(),
                        'amount' =>
                            array(
                                'unitPrice' => $this->fTotalAmount,
                                'totalPrice' => $this->fTotalAmount,
                                'quantity' => 1,
                                'calculatedTaxes' => array(array(
                                    'tax' => $this->fTotalAmount - $this->fTotalAmountNet,
                                    'taxRate' => $taxRate,
                                    'price' => $this->fTotalAmount,
                                    'extensions' => [],
                                )),
                                'taxRules' => array(array(
                                    'taxRate' => $taxRate,
                                    'percentage' => 100.0
                                ))
                            ),
                        'paymentMethodId' => $iPaymentID,
                        'stateId' => $this->getOrderStateId($configPaymentStatus, MLSetting::gi()->get('ORDER_TRANSACTION_STATES_STATE_MACHINE')),

                    ]
                ],
                'lineItems'       => $this->aItemLines,
                'deliveries'      => [
                    [
                        'id'                     => $this->oExistingOrder === null ? $this->oApiHelper::randomHex() : $this->getExistingDeliveryId(),
                        'shippingOrderAddressId' => $blNewDelivery || $this->isNewAddress() ? $aShippingAddress['id'] : $this->getExistingDelivery()[0]->getAttributes()->getShippingOrderAddressId(),
                        'shippingMethodId'       => $this->getShippingMethod(),
                        'stateId'                => $blNewDelivery ? $this->getOrderStateId(MLSetting::gi()->get('ORDER_DELIVERY_STATES_STATE_OPEN'), MLSetting::gi()->get('ORDER_DELIVERY_STATES_STATE_MACHINE')) : $this->getExistingDelivery()[0]->getAttributes()->getStateId(),
                        'trackingCodes'          => $blNewDelivery ? [] : $this->getExistingDelivery()[0]->getAttributes()->getTrackingCodes(),
                        'shippingDateEarliest'   => (new DateTime())->format(MLSetting::gi()->get('DEFAULTS_STORAGE_DATE_TIME_FORMAT')),
                        'shippingDateLatest'     => (new DateTime())->format(MLSetting::gi()->get('DEFAULTS_STORAGE_DATE_TIME_FORMAT')),
                        'shippingCosts'          => $aShippingCost,
                        'positions'              => $this->getDeliveryPosition($this->aItemLines),
                    ],
                ],

            ];
            $orderData['totalRounding'] = $orderData['itemRounding'] = array(
                'decimals' => 2,
                'interval' => 0.01,
                'roundForNet' => true,
            );

            // add magnalister order details to custom field
            if ($this->oApiHelper->checkIfCustomFieldExists($this->sShopId)) {
                $orderData['customFields'] = array(
                    $this->oApiHelper::ORDER_DETAILS_FIELD => $this->getMagnalisterOrderDetails($aData)
                );
            }

            if ($this->isNewAddress() || $this->oExistingOrder === null) {
                $orderData['orderDateTime'] = (new DateTime($aData['Order']['DatePurchased']))->format(MLSetting::gi()->get('DEFAULTS_STORAGE_DATE_TIME_FORMAT'));
                $orderData['billingAddressId'] = $aBillingAddress['id'];
                $orderData['addresses'] = [
                    $aBillingAddress,
                    $aShippingAddress,
                ];
            } else {
                $orderData['billingAddressId'] = $this->oExistingOrder->getAttributes()->getBillingAddressId();
            }

            if ($this->oExistingOrder === null) {
               $this->oShopwareOrderRequest->createShopwareOrders($orderData);
               $this->oEntityNumberRequest->createShopwareNumberRangeStates(array('lastValue' => (int)$sOrderNumber), 'PATCH', $this->sOrderNumberStateId);
            } else {
//              removes state ids from order data because it can not be updated using the patch orders call
                $orderData = $this->updateOrderData($orderData, $configPaymentStatus);
                $this->oShopwareOrderRequest->createShopwareOrders($orderData, 'PATCH', $this->oExistingOrder->getId());
                // updates the transaction and order state
                $this->updateOrderStates();

            }

            $this->oOrder->set('orders_id', $orderData['orderNumber']);//important to show order number in backoffice of magnalister
            $this->oOrder->set('current_orders_id', $orderData['id']); //important to find order in shopware database


        } catch (Exception $ex) {
            MLMessage::gi()->addDebug($ex);
            throw $ex;
        }
        return $aData;
    }

    /**
     * Updates the state for the order and for order payment
     * needs to contain ['orderState' => ['orderId' => 'stateTechnicalName']]
     *
     * @return void
     * @throws Exception
     */
    private function updateOrderStates() {
        foreach ($this->states as $stateType => $states) {
            switch ($stateType) {
                case 'orderState':
                    foreach ($states as $orderId => $state) {
                        $this->oShopwareOrderRequest->updateShopwareOrderState($orderId, $state);
                    }
                    break;
                case 'transactionState':
                    foreach ($states as $transactionId => $state) {
                        $oRequestTransactions = new ShopwareOrderTransaction($this->sShopId, null, false);
                        $oRequestTransactions->updateShopwareOrderTransactionState($transactionId, $state);
                    }
                    break;
            }

        }
    }

    /**
     * Remove order states and set State ids to be updated later wit different call
     *
     * @param $orderData
     * @return mixed
     */
    private function updateOrderData($orderData, $configPaymentStatus) {
        $this->states = array();
        $existingOrderStateId = $this->oExistingOrder->getAttributes()->getStateId();
        if (isset($orderData['stateId'])) {
            if ($orderData['stateId'] !== $existingOrderStateId) {
                $sTransitionOrderAction = $this->getStatusTransition(MLSetting::gi()->get('ORDER_STATES_STATE_MACHINE'), $this->aNewData['Order']['Status']);
                if (isset($sTransitionOrderAction)) {
                    $this->states['orderState'][$this->oExistingOrder->getId()] = $sTransitionOrderAction;
                }
            }
            unset($orderData['stateId']);
        }

        foreach ($orderData['transactions'] as &$transaction) {
            if (isset($transaction['stateId'])) {
                $sExistingTransactionStateId = $this->getFirstTransaction()->getAttributes()->getStateId();
                if ($transaction['stateId'] !== $sExistingTransactionStateId) {
                    $sTransitionTransactionAction = $this->getStatusTransition(MLSetting::gi()->get('ORDER_TRANSACTION_STATES_STATE_MACHINE'), $configPaymentStatus);
                    if (isset($sTransitionTransactionAction)) {
                        $this->states['transactionState'][$sExistingTransactionStateId] = $sTransitionTransactionAction;
                    }
                }
                unset($transaction['stateId']);
            }
        }

        // we do not update the state id of the delivery
        foreach ($orderData['deliveries'] as &$delivery) {
            if (isset($delivery['stateId'])) {
                unset($delivery['stateId']);
            }
        }

        return $orderData;

    }

    /**
     * shopware create automatically new order number for new order
     * with this function it is easier to override this number
     * @return string
     * @throws Exception
     */
    protected function getShopwareOrderNumber(): ?string {
        if ($this->oExistingOrder !== null) {
            $result = $this->oExistingOrder->getAttributes()->getOrderNumber();
        } else {
            $aOrderNumberState = $this->generateEntityNumber('order');
            $result = (string)$aOrderNumberState['entityNumber'];
            $this->sOrderNumberStateId = $aOrderNumberState['entityId'];
        }
        return $result;
    }

    /**
     * @return array
     * @throws Exception
     */
    protected function getShippingCost() {
        $fMaxTaxRate = $this->getTaxRate();
        $fShippingCostBrut = round((float)$this->getTotal('Shipping')['Value'], 2);
        $fShippingCostNet = MLPrice::factory()->calcPercentages($fShippingCostBrut, null, $fMaxTaxRate);
        $this->addTotalAmount($fShippingCostBrut, $fShippingCostNet);
        $taxRate = $this->getTaxRate();
        return array(
            'unitPrice' => $fShippingCostBrut,
            'totalPrice' => $fShippingCostBrut,
            'quantity' => 1,
            'calculatedTaxes' => array(array(
                'tax' => $fShippingCostBrut - $fShippingCostNet,
                'taxRate' => $taxRate,
                'price' => $fShippingCostBrut,
                'extensions' => [],
            )),
            'taxRules' => array(array(
                'taxRate' => $taxRate,
                'percentage' => 100.0
            ))
        );
    }

    protected function getExistingDelivery() {
        if ($this->oExistingOrder === null) {
            throw new Exception('Use this function only for merging and updating order');
        }
        $result = null;
        $sOrderDeliveryUrl = $this->oExistingOrder->getRelationships()->getDeliveries()->getLinks()->getRelated();
        $oOrderDeliveries = $this->oShopwareOrderRequest->getShopwareOrderDeliveries($sOrderDeliveryUrl);
        if (isset($oOrderDeliveries) && is_array($oOrderDeliveries->getData()) && isset($oOrderDeliveries->getData()[0])) {
            $result = $oOrderDeliveries->getData();
        }

        return $result;
    }


    /**
     * Get the first transaction id of the existing order
     * @return mixed
     * @throws Exception
     */
    protected function getExistingTransactionId() {
        return $this->getFirstTransaction()->getId();
    }


    /**
     * Get the first transaction of the existing order
     *
     * @return mixed|null
     * @throws Exception
     */
    private function getFirstTransaction() {
        if ($this->oExistingOrder === null) {
            throw new Exception('Use this function only for merging and updating order');
        }
        $oTransaction = null;
        $sOrderTransactionUrl = $this->oExistingOrder->getRelationships()->getTransactions()->getLinks()->getRelated();
        $oOrderTransactions = $this->oShopwareOrderRequest->getShopwareOrderTransactions($sOrderTransactionUrl);
        if (isset($oOrderTransactions) && is_array($oOrderTransactions->getData()) && isset($oOrderTransactions->getData()[0])) {
            $oTransaction = $oOrderTransactions->getData()[0];
        }

        return $oTransaction;
    }


    protected function getExistingDeliveryId() {
        $result = null;
        $oOrderDeliveries = $this->getExistingDelivery();
        if (is_array($oOrderDeliveries)) {
            $result = $oOrderDeliveries[0]->getId();
        }

        return $result;
    }

    /**
     * This functions is only implemented to prevent errors for duplicated of code
     * @return array
     */
    protected function addProductsAndTotals(): array {
        $fOtherAmount = $this->fTotalAmount;
        $aProducts = $this->getProductArray($this->aNewData['Products']);
        $aTotalProducts = [];
        // add orders totals
        foreach ($this->aNewData['Totals'] as &$aTotal) {
            switch ($aTotal['Type']) {
                case 'Shipping':
                {
                    //it is already managed in caller functions
                    break;
                }
                case 'Payment':
                default:
                {
                    if ((float)$aTotal['Value'] !== 0.0) {
                        $aTotalProducts[] = [
                            'SKU'        => $aTotal['SKU'] ?? '',
                            'ItemTitle'  => (isset($aTotal['Code']) && $aTotal['Code'] !== '') ? $aTotal['Code'] : $aTotal['Type'],
                            'Quantity'   => 1,
                            'Price'      => $aTotal['Value'],
                            'Tax'        => array_key_exists('Tax', $aTotal) ? $aTotal['Tax'] : false,
                            'ForceMPTax' => false
                        ];

                    }
                    break;
                }
            }
        }
        $aAllItems = array_merge($this->getProductArray($aTotalProducts), $aProducts);
        $this->fTotalProductAmount = $this->fTotalAmount - $fOtherAmount;
        return $aAllItems;
    }

    protected function getProductArray($aProducts) {
        $aItems = [];
        if ($this->oExistingOrder !== null) {
            if ($this->oExistingOrderLineItems === null) {
                $this->getExistingOrderLineItems();
            }
        }
        $oProductRequest = new ShopwareProduct($this->sShopId, null, false);
        foreach ($aProducts as &$aProduct) {
            $aProduct['SKU'] = isset($aProduct['SKU']) ? $aProduct['SKU'] : ' ';
            $oProduct = MLShopwareCloudAlias::getProductModel()->getByMarketplaceSKU($aProduct['SKU']);
            $blFound = false;
            $iProductQuantity = (int)$aProduct['Quantity'];
            $fProductPrice = (float)$aProduct['Price'];

            if ($this->oExistingOrder !== null) {
                if ($this->iExistingOrderLineItemsCount > 0) {
                    // Set once productLastPosition to count of existing products in order
                    if ($this->productLastPosition === 0) {
                        $this->productLastPosition = $this->iExistingOrderLineItemsCount;
                    }
                    /** @var src\Model\Shopware\Order\ShopwareOrderLineItem  $item */
                    foreach ($this->oExistingOrderLineItems as $key => $item) {
                        if ($item->getAttributes()->getQuantity() === $iProductQuantity && ($item->getAttributes()->getUnitPrice() === $fProductPrice)) {
                            if (trim($aProduct['SKU']) === '' && ($item->getAttributes()->getLabel() === $aProduct['ItemTitle'] || $item->getAttributes()->getLabel() === $aProduct['ItemTitle'].'('.$aProduct['SKU'].')')) {//in ebay sku could be empty
                                $blFound = true;
                            } else if (trim($aProduct['SKU']) !== '') {
                                $sProductNumber = $item->getAttributes()->getPayload()->getProductNumber();
                                if (isset($sProductNumber)) {
                                    if ($sProductNumber === $aProduct['SKU']) {
                                        $blFound = true;
                                    } else {// perhaps sku have changed or wrong key type of total?
                                        if ($oProduct->exists() && $oProduct->getSku() === $sProductNumber) {
                                            $blFound = true;
                                        }
                                    }
                                }
                            }
                        }
                        if ($blFound) {
                            unset($this->oExistingOrderLineItems[$key]);
                            break;
                        }
                    }
                }
            }
            $aItem = [
                'id' => $this->oApiHelper::randomHex(),
            ];
            $mMarketplaceTax = ($aProduct['Tax'] !== false && array_key_exists('ForceMPTax', $aProduct) && $aProduct['ForceMPTax']) ? (float)$aProduct['Tax'] : null;
            if ($oProduct->exists()) {
                $sProductId = $oProduct->getCorrespondingProductEntity()->id;
                $oProductEntity = $oProduct->getCorrespondingProductEntity();
                /** @var src\Model\Shopware\Product\ShopwareProducts $product */
                $product = $oProductRequest->getShopwareProducts('/api/product/'.$sProductId);
                $fProductTaxRate = $mMarketplaceTax ?? $oProduct->getTax($this->aNewData['AddressSets']);
                if (!is_object($product)) {
                    $product = $oProductEntity->attributes;
                    $payload = [
                        'isNew'           => null,
                        'isCloseout'      => $product['isCloseout'],
                        'customFields'    => $product['customFields'],
                        'createdAt'       => $product['createdAt'],
                        'releaseDate'     => $product['releaseDate'] ?? null,
                        'markAsTopseller' => $product['markAsTopseller'],
                        'productNumber'   => $product['productNumber'],
                        'manufacturerId'  => $product['manufacturerId'],
                        'taxId'           => $product['taxId'],
                        'tagIds'          => $product['tagIds'],
                        'categoryIds'     => $product['categoryTree'],
                        'propertyIds'     => $product['propertyIds'],
                        'optionIds'       => $product['optionIds'],
                        'options'         => $product['variation'],
                    ];
                } else {
                    $product = $product->getData()->getAttributes();
                    $payload = [
                        'isNew'           => method_exists($product, 'isNew') ? $product->isNew() : null,
                        'isCloseout'      => $product->getIsCloseout(),
                        'customFields'    => $product->getCustomFields(),
                        'createdAt'       => $product->getCreatedAt(),
                        'releaseDate'     => $product->getReleaseDate() ?? null,
                        'markAsTopseller' => $product->getMarkAsTopseller(),
                        'productNumber'   => $product->getProductNumber(),
                        'manufacturerId'  => $product->getManufacturerId(),
                        'taxId'           => $product->getTaxId(),
                        'tagIds'          => $product->getTagIds(),
                        'categoryIds'     => $product->getCategoryTree(),
                        'propertyIds'     => $product->getPropertyIds(),
                        'optionIds'       => $product->getOptionIds(),
                        'options'         => $product->getVariation(),
                    ];
                }

                $aItem['payload'] = $payload;

                $aItem['productId'] = $oProduct->get('ProductsId');
                $aItem['identifier'] = $aItem['productId'];
                $aItem['referencedId'] = $aItem['productId'];

                if ($aProduct['StockSync']) {
                    $aItem['type'] = 'product';//that is useful for existing product in shop, to show link of product in order detail, other possible values: product, credit, custom, promotion
                } else {
                    $aItem['type'] = 'custom';//To pervent reduction in availibles tock on product
                }
            } else {
                $fProductTaxRate = $mMarketplaceTax ?? $this->getFallbackTax();
                $sIdentifier = $this->oApiHelper::randomHex();
                $aItem['identifier'] = $sIdentifier;
                $aItem['referencedId'] = $sIdentifier;
                $aItem['payload'] = [
                    'productNumber' => $aProduct['SKU'],
                ];
                $aItem['type'] = 'custom';//If product doesn't exist in shop we couldn't use "product" as type, possible values: product, credit, custom, promotion
            }
            $aItem['quantity'] = $iProductQuantity;

            $aItem['label'] = $aProduct['ItemTitle'].($aItem['type'] === 'custom' ? '('.$aProduct['SKU'].')' : '');
            //More than 255 character is not allowed for order label in Shopware 6
            if (strlen($aItem['label']) > 255) {
                $aItem['label'] = mb_substr($aItem['label'], 0, 255 - 3, 'UTF-8').'...';
            }
            [$aItem['price']] = $this->getProductPrice($fProductPrice, $iProductQuantity, $fProductTaxRate);

            //Position of item (used same logic like shopware 6) increase before usage
            $aItem['position'] = ++$this->productLastPosition;

            if (!$blFound) {
                //Position of item (used same logic like shopware 6) increase before usage
                $aItem['position'] = ++$this->productLastPosition;

                $aItems[] = $aItem;
            }
        }
        return $aItems;
    }

    protected function addTotalAmount($fGrossAmount, $fNetAmount): void {
        $this->fTotalAmount += $fGrossAmount;
        $this->fTotalAmountNet += $fNetAmount;
    }

    private function getExistingOrderLineItems() {
        $sLineItemsUrl = $this->oExistingOrder->getRelationships()->getLineItems()->getLinks()->getRelated();
        $oOrderLineItems = $this->oShopwareOrderRequest->getShopwareOrderLineItems($sLineItemsUrl. '?total-count-mode=1');

        $this->oExistingOrderLineItems = $oOrderLineItems->getData();
        $this->iExistingOrderLineItemsCount = $oOrderLineItems->getMeta()->getTotal();
    }

    /**
     * @return float Rate
     * @throws Exception
     */
    protected function getTaxRate(): float {
        $fMaxProductTax = null;
        $fDefaultProductTax = 0.00;
        if (!empty($this->aItemLines)) {
            foreach ($this->aItemLines as $item) {
                foreach ($item['price']['calculatedTaxes'] as $aTax) {
                    $fMaxProductTax = max($this->fMaxProductTax, $aTax['taxRate']);
                }
            }
        }
        if ($this->oExistingOrder !== null && $this->oExistingOrderLineItems !== null) {
            foreach ($this->oExistingOrderLineItems as $item) {
                foreach ($item->getAttributes()->getPrice()->getCalculatedTaxes() as $oTax) {
                    $fMaxProductTax = max($this->fMaxProductTax, $oTax->getTaxRate());
                }
            }
        }
        if ($fMaxProductTax !== null) {
            $this->fMaxProductTax = $fMaxProductTax;
        } else {
            // fallback
            $fDefaultProductTax = $this->getFallbackTax();
        }

        return max((float)$fDefaultProductTax, $this->fMaxProductTax);

    }

    protected function getFallbackTax() {
        $fDefaultProductTax = MLModule::gi()->getConfig('mwst.fallback');
        if ($fDefaultProductTax === null) {
            $fDefaultProductTax = MLModule::gi()->getConfig('mwstfallback'); // some modules have this, other that
        }
        return $fDefaultProductTax;
    }

    /**
     * Try to find customer in Shopware by email. if it doesn't exists, it creates new customer, at the end it returns customer id
     * new created or existing customer entity
     * @return mixed
     * @throws Exception
     * @see \Shopware\Storefront\Test\Controller\CheckoutControllerTest::createCustomer
     */
    protected function getCustomer() {
        $blNewCustomer = false;
        $aAddress = $this->aNewData['AddressSets']['Main'];
        $sCustomerId = $this->findCustomerByEmail();
        if ($sCustomerId === null) {
            $blNewCustomer = true;
            $sCustomerId = $this->oApiHelper::randomHex();
        }
        $sSalutationId = $this->getSalutationId($aAddress['Gender']);
        $mPaymentMethodId = $this->getPaymentMethodId();
        $mDefaultShippingAddress = $this->getAddress($this->aNewData['AddressSets'], 'Shipping');
        $mDefaultBillingAddress = $this->getAddress($this->aNewData['AddressSets'], 'Billing');
        $sConfigCustomerGroup = $this->getCustomerGroup();

        if ($aAddress['Firstname'] === '') {
            $aAddress['Firstname'] = '--';
        }

        $vatIds = null;
        if (isset($aAddress['UstId'])) {
            $vatIds = [$aAddress['UstId']];
        }

        $customer =
            [
                'id'                     => $sCustomerId,
                'salesChannelId'         => $this->oSalesChanel->getId(),
                'defaultPaymentMethodId' => $mPaymentMethodId,
                'firstName'              => $aAddress['Firstname'],
                'lastName'               => $aAddress['Lastname'],
                'salutationId'           => $sSalutationId,
                'groupId'                => $sConfigCustomerGroup === '-' ? $this->oSalesChanel->getAttributes()->getCustomerGroupId() : $sConfigCustomerGroup,
                'vatIds'                 => $vatIds,
            ];

        $blNewShippingAddress = $this->checkForDuplicateAddress($mDefaultShippingAddress, $sCustomerId);
        $blNewBillingAddress = $this->checkForDuplicateAddress($mDefaultBillingAddress, $sCustomerId);


        if (($blNewCustomer || $this->isNewAddress() || $this->oExistingOrder === null)) {
            if ($blNewShippingAddress || $blNewCustomer) {
                $customer['defaultShippingAddress'] = $mDefaultShippingAddress;
            }
            if ($blNewBillingAddress || $blNewCustomer) {
                $customer['defaultBillingAddress'] = $mDefaultBillingAddress;
            }
        }

        $sError = '';
            if ($blNewCustomer) {
                $aCustomerNumberState = $this->generateEntityNumber('customer');
                $customer['email'] = $aAddress['EMail'];
                // password: Zeichenkette ist zu kurz. Sie sollte mindestens 8 Zeichen haben.
                $customer['password'] = 'notnotnotnotnot';
                $customer['guest'] = $sConfigCustomerGroup === '-';
                $customer['customerNumber'] = (string)$aCustomerNumberState['entityNumber'];
                $this->oCustomerRequest->createShopwareCustomer($customer);
                $this->oEntityNumberRequest->createShopwareNumberRangeStates(array('lastValue' => (int)$aCustomerNumberState['entityNumber']), 'PATCH', $aCustomerNumberState['entityId']);
            } else {
                $this->oCustomerRequest->createShopwareCustomer($customer, 'PATCH', $sCustomerId);
            }

        /** @var src\Model\Shopware\Customer\ShopwareCustomers $oCustomer */
        $oCustomer = $this->oCustomerRequest->getShopwareCustomers('/api/customer/'.$sCustomerId);
        if (is_string($oCustomer->getData()->getId())) {
            return $oCustomer->getData();
        } else {
            throw new Exception('Customer cannot be created: '.$sError);
        }
    }

    /**
     * Returns the first number range value for the given type
     * In shopware 6 these are the following types: order, document_storno, customer, document_credit_note, document_invoice, product
     *
     * @param $type
     * @return null
     * @throws Exception
     */
    public function generateEntityNumber($type) {
        $numberRangesUlr = '';
        $customerNumberRangeStateUlr = '';
        $nextNumber = null;
        $entityId = null;
        $oNumberRangeTypes = $this->oEntityNumberRequest->getShopwareNumberRangeTypes();
        foreach ($oNumberRangeTypes->getData() as $oNumberRangeType) {
            if ($oNumberRangeType->getAttributes()->getTechnicalName() == $type) {
                $numberRangesUlr = $oNumberRangeType->getRelationships()->getNumberRanges()->getLinks()->getRelated();
                break;
            }
        }

        if ($numberRangesUlr !== '') {
            $oCustomerNumberRanges = $this->oEntityNumberRequest->getShopwareNumberRanges($numberRangesUlr);
            if (is_array($oCustomerNumberRanges->getData())) {
                $customerNumberRangeStateUlr = $oCustomerNumberRanges->getData()[0]->getRelationships()->getState()->getLinks()->getRelated();
            }

            // when we have first order in the shop the order number is missing
            if ($customerNumberRangeStateUlr !== '') {
                $oCustomerNumberRangeTypes = $this->oEntityNumberRequest->getShopwareNumberRangeStates($customerNumberRangeStateUlr);
                if (is_array($oCustomerNumberRangeTypes->getData())) {
                    $nextNumber = $oCustomerNumberRangeTypes->getData()[0]->getAttributes()->getLastValue() + 1;
                    $entityId = $oCustomerNumberRangeTypes->getData()[0]->getId();
                } else {
                    $entityId = $this->oApiHelper::randomHex();
                    $nextNumber = (int)$oCustomerNumberRanges->getData()[0]->getAttributes()->getStart();
                    $this->oEntityNumberRequest->createShopwareNumberRangeStates(array(
                        'id' => $entityId,
                        'numberRangeId' => $oCustomerNumberRanges->getData()[0]->getId(),
                        'lastValue' => $nextNumber
                    ));
                }
            }
        }
        return array(
            'entityId' => $entityId,
            'entityNumber' => $nextNumber
        );
    }

    protected function findCustomerByEmail(){
        $iCustomerId = null;
        $filters = array(
            'email' =>  array(
                'type' => 'equals',
                'values' => $this->aNewData['AddressSets']['Main']['EMail']
            )
        );
        $preparedFilters = $this->oApiHelper->prepareFilters($filters, 'POST');
        $aCustomer = $this->oCustomerRequest->getShopwareCustomers('/api/search/customer', 'POST', $preparedFilters)->getData();
        if (is_array($aCustomer)) {
            $iCustomerId = $aCustomer[0]->getId();
        }

        return $iCustomerId;
    }

    /**
     * Returns customer from Shopify based on customer email
     *
     * @param $sEmail string
     *
     * @return array
     */
    protected function getCustomerFromShopifyAsArray($sEmail) {
        return '';
    }

    /**
     * Transforms address data format to the required format for shopware 6 order model.
     * @param $addressSets Array with all address sets, addressType decides if its shipping, billing or main
     * @param null|string $addressType
     * @return []|array
     * @throws Exception
     */
    protected function getAddress($addressSets, $addressType=null) {
        $aAddress = $addressSets[$addressType];
        $sCountryId = $this->getCountryId($aAddress['CountryCode']);
        $sSalutationId = $this->getSalutationId($aAddress['Gender']);
        $iStateId = $this->getStateId($aAddress['Suburb'], $sCountryId);
        $sCity = trim($aAddress['City']);
        if ($iStateId === null) {
            if (!empty($aAddress['Suburb'])) {
                $sCity .= ' - '.trim($aAddress['Suburb']);
            }
        }

        if ($aAddress['Firstname'] === '') {
            $aAddress['Firstname'] = '--';
        }
        if ($aAddress['Lastname'] === '') {
            $aAddress['Lastname'] = '--';
        }

        if ($aAddress['Company'] == false) {
            $aAddress['Company'] = null;
        }

        if ($aAddress['AddressAddition'] == false) {
            $aAddress['AddressAddition'] = null;
        }

        // Necessary to be null or a string - [/0/defaultBillingAddress/phoneNumber] Dieser Wert sollte vom Typ string sein.
        if ($aAddress['Phone'] == false) {
            $aAddress['Phone'] = null;
        }

        // Check if PackstationCustomerID is same as AddressAddition, so we can leave AddressAddition empty
        if (isset($this->aNewData['MPSpecific']['DeliveryPackstation']['PackstationCustomerID'])
            && $this->aNewData['MPSpecific']['DeliveryPackstation']['PackstationCustomerID'] === $aAddress['AddressAddition']
        ) {
            $aAddress['AddressAddition'] = null;
        }

        $address = [
            'id'                     => $this->oApiHelper::randomHex(),
            'company'                => $aAddress['Company'],
            'firstName'              => (strlen($aAddress['Firstname'].'') > 50) ? $this->cutText('Address.Firstname', $aAddress['Firstname'], 50) : $aAddress['Firstname'],
            'lastName'               => (strlen($aAddress['Lastname'].'') > 60) ? $this->cutText('Address.Lastname', $aAddress['Lastname'], 60) : $aAddress['Lastname'],
            'city'                   => $sCity,
            'street'                 => $aAddress['StreetAddress'],
            'zipcode'                => $aAddress['Postcode'],
            'salutationId'           => $sSalutationId,
            'countryId'              => $sCountryId,
            'phoneNumber'            => $aAddress['Phone'],
            'additionalAddressLine1' => $aAddress['AddressAddition'],
            'additionalAddressLine2' => null,
            'email'                  => $aAddress['EMail'],
        ];

        if ($addressType === 'Shipping') {
            $address = array_merge($address,
                array(
                    //In Amazon orders if PackstationID is set then it is filling "order -> street" with PackstationID value else it is filling  with NULL value
                    'street' => (isset($this->aNewData['MPSpecific']['DeliveryPackstation']['PackstationID'])) ? 'Packstation '.$this->aNewData['MPSpecific']['DeliveryPackstation']['PackstationID'] : $aAddress['StreetAddress'],
                    //In Amazon orders if PackstationCustomerID is set then it is filling "order -> additionalAddressLine1" with PackstationCustomerID value else it is filling with NULL value
                    'additionalAddressLine1' => (isset($this->aNewData['MPSpecific']['DeliveryPackstation']['PackstationCustomerID'])) ? $this->aNewData['MPSpecific']['DeliveryPackstation']['PackstationCustomerID'] : $aAddress['AddressAddition'],
                    'additionalAddressLine2' => (isset($this->aNewData['MPSpecific']['DeliveryPackstation']['PackstationCustomerID'])) ? $aAddress['AddressAddition'] : null,
                )
            );
        }

        if ($iStateId !== null) {
            $address['countryStateId'] = $iStateId;
        }

        return $address;
    }

    protected function cutText($sFieldName, $sValidated, $iLength, $blDottedEnd = true) {
        $this->aCuttedField[$sFieldName] = $sValidated;
        if (function_exists('mb_substr')) {
            if ($blDottedEnd) {
                $sValidated = mb_substr($sValidated, 0, $iLength - 4, 'UTF8')."...";
            } else {
                $sValidated = mb_substr($sValidated, 0, $iLength, 'UTF8');
            }
        } else {
            if ($blDottedEnd) {
                $sValidated = substr($sValidated, 0, $iLength - 4)."...";
            } else {
                $sValidated = substr($sValidated, 0, $iLength);
            }
        }
        return $sValidated;
    }


    /**
     * Check for duplicate address date for a specific customer
     *
     * @param $type
     * @param $address
     * @param $customerId
     * @return bool
     */
    protected function checkForDuplicateAddress($address, $customerId) {
        $filters = array('customerId' => $customerId,'firstName' => $address['firstName']);
        $preparedCustomerAddressPath = $this->oApiHelper->prepareFilters($filters, 'GET', '/api/customer-address');
        $oExistingAddresses = $this->oCustomerRequest->getShopwareCustomerAddresses($preparedCustomerAddressPath);

        $blNewAddress = true;
        foreach ($oExistingAddresses->getData() as $existingAddress) {
            $existingAddress = $existingAddress->getAttributes();
            if (
                (
                    $existingAddress->getPhoneNumber() === $address['phoneNumber']
                    || (empty($existingAddress->getPhoneNumber()) && empty($address['phoneNumber'])) // phoneNumber in shop could be empty string or null
                )
                && ($existingAddress->getFirstName() === $address['firstName'])
                && ($existingAddress->getLastName() === $address['lastName'])
                && ($existingAddress->getStreet() === $address['street'])
                && ($existingAddress->getZipcode() === $address['zipcode'])
                && ($existingAddress->getCity() === $address['city'])
            ) {
                $blNewAddress = false;
            }
        }
        return $blNewAddress;
    }

    /**
     * try to find matched shipping method in shopware otherwise it create new shipping method
     * @return string
     */
    protected function getShippingMethod() {
        $aTotalShipping = $this->getTotal('Shipping');

        if (!empty($aTotalShipping['Code'])) {
            if ($this->oApiHelper->isValidUuid($aTotalShipping['Code'])) {
                return $aTotalShipping['Code'];
            } else {
                $sShippingMethodId = null;
                $oShippingMethodRequest = new ShopwareShippingMethod($this->sShopId, null, false);
                $shippingMethodFilters = [
                    'name' => [
                        'type' => 'equals',
                        'values' => $aTotalShipping['Code']
                    ]
                ];
                $shippingMethodRequestBody = $this->oApiHelper->prepareFilters($shippingMethodFilters, 'POST');
                $oShippingMethods = $oShippingMethodRequest->getShippingMethods('/api/search/shipping-method', 'POST', $shippingMethodRequestBody);
                if (is_array($oShippingMethods->getData()) && isset($oShippingMethods->getData()[0])) {
                    $sShippingMethodId = $oShippingMethods->getData()[0]->getId();
                }

                if ($sShippingMethodId === null) {//try to create a new shipping method
                    $sShippingMethodId = $this->oApiHelper->randomHex();
                    $ruleFilters = [
                        'invalid' => [
                            'type' => 'equals',
                            'values' => false
                        ]
                    ];
                    $ruleRequestBody = $this->oApiHelper->prepareFilters($ruleFilters, 'POST');
                    $oRuleRequest = new ShopwareRule($this->sShopId, null, false);
                    $oRules = $oRuleRequest->getShopwareRules('/api/search/rule', 'POST', $ruleRequestBody);
                    $oRule = $oRules->getData()[0];


                    $oDeliveryTimeRequest = new ShopwareDeliveryTime($this->sShopId, null, false);
                    $oDeliveryTimes = $oDeliveryTimeRequest->getShopwareDeliveryTimes();
                    $oDeliveryTime = $oDeliveryTimes->getData()[0];
                    $aShippingMethodData = [
                        'id' => $sShippingMethodId,
                        'name' => $aTotalShipping['Code'],
                        'active' => false,
                        'availabilityRuleId' => $oRule->getId(),
                        'deliveryTimeId' => $oDeliveryTime->getId(),
                        'translations' => [
                            'de-DE' => [
                                'name' => $aTotalShipping['Code'],
                            ],
                            'en-GB' => [
                                'name' => $aTotalShipping['Code'],
                            ],
                        ],
                    ];
                    $oShippingMethodRequest->createShopwareShippingMethod($aShippingMethodData);

                }
            }
            return $sShippingMethodId;
        }
        return $this->getAvailableShippingMethod()->getId();
    }

    /**
     * Get first available shipping method
     * @see \Shopware\Core\Framework\Test\TestCaseBase\BasicTestDataBehaviour::getAvailableShippingMethod
     */
    protected function getAvailableShippingMethod() {
        $oShippingMethodRequest = new ShopwareShippingMethod($this->sShopId, null, false);
        $oDeliveryTimeRequest = new ShopwareDeliveryTime($this->sShopId, null, false);
        $oShippingMethods = $oShippingMethodRequest->getShippingMethods();
        foreach ($oShippingMethods->getData() as $oShippingMethod) {
            $sShippingMethodPriceUrl = $oShippingMethod->getRelationships()->getPrices()->getLinks()->getRelated();
            $oShippingMethodPrices = $oDeliveryTimeRequest->getShopwareDeliveryTimes($sShippingMethodPriceUrl);
            foreach ($oShippingMethodPrices->getData() as $oShippingMethodPrice) {
                if ($oShippingMethodPrice->getAttributes()->getCalculation() === 1 && $oShippingMethod->getAttributes()->getAvailabilityRuleId() !== null) {
                    return $oShippingMethod;
                }
            }

        }
        throw new LogicException('No available ShippingMethod configured');
    }

    protected function getStateId($sState, $sCountryId) {
        $stateId = null;
        $result = MLDatabase::getDbInstance()->fetchRow('SELECT cst.ShopwareCountryStateID FROM '. MLDatabase::factory('ShopwareCloudCountryStateTranslation')->getTableName() . ' AS cst
        INNER JOIN ' . MLDatabase::factory('ShopwareCloudCountryState')->getTableName() . ' AS cs ON cs.ShopwareCountryStateID = cst.ShopwareCountryStateID
        WHERE cst.ShopwareName LIKE "'. $sState .'" AND cs.ShopwareCountryId ="' . $sCountryId . '"');
        if (isset($result['ShopwareCountryStateID'])) {
            $stateId = $result['ShopwareCountryStateID'];
        }
        return $stateId;
    }

    /**
     * Get Salutation Id of Shopware cloud for a given gender
     *  Use als Fallback "not_specified" or just the first salutation
     *
     * @param $sGender === 'f' ? 'mrs' : 'mr'
     * @return mixed
     */
    protected function getSalutationId($sGender) {
        $salutationId = null;
        // 'mrs' and 'mr' are default values of shopware cloud that can be adjusted
        if ($sGender === 'f') {
            $sGender = 'mrs';
        }

        if ($sGender === 'm') {
            $sGender = 'mr';
        }

        $oSalutations = $this->oCustomerRequest->getShopwareSalutations()->getData();
        $first = false;
        foreach ($oSalutations as $oSalutation) {
            if (!$first) {
                $salutationId = $oSalutation->getId();
                $first = true;
            }
            $salutationKey = $oSalutation->getAttributes()->getSalutationKey();
            if ($salutationKey == $sGender) {
                $salutationId = $oSalutation->getId();
                break;
            } else if ($salutationKey == 'not_specified') {
                // use fallback for shopware 6 default for unspecified gender original key "not_specified"
                $salutationId = $oSalutation->getId();
            }

        }

        return $salutationId;
    }

    protected function getCountryId($iCountryCode) {
        $sCountryId = null;
        $result = MLDatabase::getDbInstance()->fetchRow('SELECT ShopwareCountryID FROM '. MLShopwareCloudAlias::getCountryModel()->getTableName() . ' WHERE ShopwareIso = "'. $iCountryCode .'"');
        if (isset($result['ShopwareCountryID'])) {
            $sCountryId = $result['ShopwareCountryID'];
        }
        return $sCountryId;
    }

    /**
     * find or create payment method with payment method code
     * @return string
     * @throws Exception
     */
    protected function getPaymentMethodId() {
        // set to 'Marketplace' if the order data don't provide it (like for Check24)
        $sPaymentMethod = $this->getTotal('Payment')['Code'] OR $sPaymentMethod = 'Marketplace';
        if ($this->oApiHelper->isValidUuid(strtolower($sPaymentMethod))) {
            $sPaymentId = strtolower($sPaymentMethod);
        } else {
            $sPaymentId = $this->checkIfPaymentMethodExist($sPaymentMethod);
            if (!isset($sPaymentId)){
                $sPaymentId = $this->oApiHelper::randomHex();
                $sLanguageIds = MLDatabase::getDbInstance()->fetchArray('SELECT  ShopwareLanguageID  FROM ' . MLDatabase::factory('ShopwareCloudLanguage')->getTableName(),true);
                $data = array(
                    'id' => $sPaymentId
                );
                foreach ($sLanguageIds as  $sLanguageId) {
                    $data['translations'][$sLanguageId]  = array('name' => $sPaymentMethod);
                }

                $this->oPaymentRequest->createShopwarePaymentMethod($data);
            }

        }

        return $sPaymentId;
    }

    protected function checkIfPaymentMethodExist($sPaymentMethod){
        $paymentMethodId = null;
        $iLimitPerPage = 250;
        $page = 1;
        $iTotalCountPaymentMethods = $this->getShopwareEntityListCount('/api/payment-method', 'library\request\shopware\ShopwarePaymentMethod', 'getPaymentMethods');
        $iLimitationOfIteration = ceil($iTotalCountPaymentMethods / $iLimitPerPage);
        while ($page <= $iLimitationOfIteration) {
            $preparedFilters['page'] = $page;
            $preparedFilters['limit'] = $iLimitPerPage;
            $preparedFilters['total-count-mode'] = 1;
            /** @var src\Model\Shopware\PaymentMethods\ShopwarePaymentMethods $oPaymentMethods */
            $oPaymentMethods = $this->oPaymentRequest->getPaymentMethods('/api/search/payment-method', 'POST', $preparedFilters);

            /** @var src\Model\Shopware\PaymentMethods\ShopwarePaymentMethod $paymentMethod */
            foreach ($oPaymentMethods->getData() as $paymentMethod) {
                if ($paymentMethod->getAttributes()->getName() == $sPaymentMethod) {
                    $paymentMethodId = $paymentMethod->getId();
                    break;
                } else {
                    $paymentMethodTTranslationUrl = $paymentMethod->getRelationships()->getTranslations()->getLinks()->getRelated();
                    /** @var src\Model\Shopware\PaymentMethods\ShopwarePaymentMethodsTranslations $oPaymentMethodTranslations */
                    $oPaymentMethodTranslations = $this->oPaymentRequest->getShopwareTranslations($paymentMethodTTranslationUrl);
                    /** @var src\Model\Shopware\PaymentMethods\ShopwarePaymentMethodsTranslation $oPaymentMethodTranslation */
                    foreach ($oPaymentMethodTranslations->getData() as $oPaymentMethodTranslation) {
                        if ($oPaymentMethodTranslation->getAttributes()->getName() == $sPaymentMethod) {
                            $paymentMethodId = $paymentMethod->getId();
                            break;
                        }
                    }
                }
            }

            $page++;
        }

        return $paymentMethodId;
    }

    protected function getShopwareEntityListCount($path, $class, $function) {
        $filters = [
            'page' => 1,
            'limit' =>  1,
            'total-count-mode' => 1,
        ];
        $preparedPath = $this->oApiHelper->prepareFilters($filters, 'GET', $path);
        $shopwareProductRequest = new $class(MLShopwareCloudAlias::getShopHelper()->getShopId());
        $entities = $shopwareProductRequest->{$function}($preparedPath);
        return $entities->getMeta()->getTotal();
    }



    /**
     * @return src\Model\Shopware\SalesChannel\ShopwareSalesChannel
     * @throws Exception
     */
    public function getSalesChannel() {
        $oModul = MLModule::gi();
        $sSalesChanelId = $oModul->getConfig('orderimport.shop');
        /** @var src\Model\Shopware\SalesChannel\ShopwareSalesChannels $oSalesChannels */
        if (isset($sSalesChanelId)) {
            $oSalesChannels = $this->oSalesChanelRequest->getShopwareSalesChannels('/api/sales-channel/'.$sSalesChanelId);
            if (isset($sSalesChanelId) && $oSalesChannels->getData()->getId() == $sSalesChanelId) {
                return $oSalesChannels->getData();
            } else {
                throw new Exception('cannot find configured sales_channel');
            }
        } else {
            throw new Exception('sales_channel not configured');
        }
    }



    public function getOrderStateId(string $state, string $machine) {
        $sOrderStateId = null;
        /** @var ML_ShopwareCloud_Helper_Model_Shop  $shopHelper  */
        $shopHelper =  MLHelper::gi('model_shop');
        $oOrderStatuses = $shopHelper->getOrderStatuses($this->oOrderStatusRequest, $this->oApiHelper, $machine, $state);
        foreach ($oOrderStatuses->getData() as $oData) {
            if ($state === $oData->getAttributes()->getTechnicalName()) {
                $sOrderStateId = $oData->getId();
                break;
            }
        }

        return $sOrderStateId;
    }

    protected function getDeliveryPosition($aLineItems): array {
        $aPositions = [];
        foreach ($aLineItems as $item) {
            $aPositions[] = [
                'id'              => $this->oApiHelper->randomHex(),
                'orderLineItemId' => $item['id'],
                'price'           => $item['price'],
                'quantity'        => $item['quantity'],
            ];
        }
        return $aPositions;
    }

    /**
     * @param float $fProductPrice
     * @param int $iProductQuantity
     * @param float $fProductTaxRate
     * @return [CalculatedPrice, QuantityPriceDefinition]
     * @throws Exception
     */
    protected function getProductPrice(float $fProductPrice, int $iProductQuantity, float $fProductTaxRate): array {
        $fProductTotalPrice = $fProductPrice * $iProductQuantity;
        $fProductTotalPriceNet = MLPrice::factory()->calcPercentages($fProductTotalPrice, null, $fProductTaxRate);
        $this->fMaxProductTax = max($this->fMaxProductTax, $fProductTaxRate);
        $this->addTotalAmount($fProductTotalPrice, $fProductTotalPriceNet);
        $sCalculatedTax = $fProductTotalPrice - $fProductTotalPriceNet;

        return [
            array(
               'unitPrice' => $fProductPrice,
               'totalPrice' => $fProductTotalPrice,
               'quantity' => $iProductQuantity,
                'calculatedTaxes' => array(array(
                    'tax' => $sCalculatedTax,
                    'taxRate' => $fProductTaxRate,
                    'price' => $fProductTotalPrice,
                )),
                'taxRules' => array(array(
                    'taxRate' => $fProductTaxRate,
                    'percentage' => 100.0
                ))
            ),
        ];
    }





    /**
     * If no payment status is set it return 'open' as open status
     *
     * @return null|string
     */
    protected function getPaymentStatus() {
        // E.g.: Idealo and Check24 do not set PaymentStatus in Normalize
        if (empty($this->aNewData['Order']['PaymentStatus'])) {// it could be filled in Normalize files (e.g. eBay)
            $this->aNewData['Order']['PaymentStatus'] = MLModule::gi()->getConfig('orderimport.paymentstatus');

            // in some marketplaces its just "paymentstatus"
            if ($this->aNewData['Order']['PaymentStatus'] === null) {
                $this->aNewData['Order']['PaymentStatus'] = MLModule::gi()->getConfig('paymentstatus');
            }

            // Last Fallback
            if (empty($this->aNewData['Order']['PaymentStatus'])) {
                $this->aNewData['Order']['PaymentStatus'] = MLSetting::gi()->get('ORDER_TRANSACTION_STATES_STATE_OPEN');
            }
        }
        return $this->aNewData['Order']['PaymentStatus'];

    }

    /**
     * NOTE:
     *  Error "Field 'orders_id' doesn't have a default value (1364)" could occur and can be ignored
     *  It's because new orders doesn't exists at this moment this function is triggered
     *
     * @param array $aData
     * @return string
     * @throws Exception
     */
    private function getMagnalisterOrderDetails($aData) {
        //check if order exist
        $oOrder = MLOrder::factory()->getByMagnaOrderId($aData['MPSpecific']['MOrderID']);
        if (isset($aData['MPSpecific']['MPreviousOrderID']) && !empty($aData['MPSpecific']['MPreviousOrderID'])) { // check for extend order
            $sMPreviousOrderID = is_array($aData['MPSpecific']['MPreviousOrderID']) ? $aData['MPSpecific']['MPreviousOrderID']['id'] : $aData['MPSpecific']['MPreviousOrderID'];
            $oOrder = MLOrder::factory()->getByMagnaOrderId($sMPreviousOrderID);
        }
        $oOrder
            ->set('mpid', MLModule::gi()->getMarketPlaceId())
            ->set('platform', MLModule::gi()->getMarketPlaceName())
            ->set('logo', null)//reset oder-logo when order is updated
            ->set('status', $aData['Order']['Status'])
            ->set('data', $aData['MPSpecific'])
            ->set('special', $aData['MPSpecific']['MOrderID'])
            ->set('orderdata', $aData);
        ob_start();
        include(MLFilesystem::gi()->getViewPath('hook_orderdetails'));
        $string = ob_get_clean();
        return '<img alt="" src="'.$oOrder->getLogo().'"> <br/>'.$string;
    }

}
