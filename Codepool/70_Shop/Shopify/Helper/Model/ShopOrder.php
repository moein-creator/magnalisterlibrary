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

use Magna\Library\MLContainer;
use Shopify\API\Application\Application;
use Shopify\API\Application\Model\Fulfillment;
use Shopify\API\Application\Model\Transaction;
use Shopify\API\Application\Request\Customer\ListOfCustomers\ListOfCustomersParams;
use Shopify\API\Application\Request\Customer\ModifyCustomer\ModifyCustomerParams;
use Shopify\API\Application\Request\Customer\NewCustomer\NewCustomerParams;
use Shopify\API\Application\Request\InventoryLevel\AdjustInventoryLevel\AdjustInventoryLevelParams;
use Shopify\API\Application\Request\InventoryLevel\InventoryLevelRequest;
use Shopify\API\Application\Request\InventoryLevel\ListOfInventoryLevels\ListOfInventoryLevelsParams;
use Shopify\API\Application\Request\Orders\ModifyOrder\ModifyOrderParams;
use Shopify\API\Application\Request\Orders\NewOrder\NewOrderParams;
use Shopify\API\Application\Request\Orders\OrderCaptureInfo\OrderCaptureInfoParams;
use Shopify\API\Application\Request\Orders\RemoveOrder\RemoveOrderParams;
use Shopify\API\Application\Request\Orders\SingleOrder\SingleOrderParams;

class ML_Shopify_Helper_Model_ShopOrder {

    /**
     * @var array $aNewData
     */
    protected $aNewData = array();

    /**
     * @var ML_Shopify_Model_Order $oOrder
     */
    protected $oOrder = null;

    /**
     * It is useful by merging a new order with existing ore that are imported previously (especially in eBay)
     * @var object $oExistingOrder
     */
    protected $oExistingOrder = null;

    /**
     * It is useful by merging a new order with existing ore that are imported previously (especially in eBay)
     * @var array $aExistingOrderData
     */
    protected $aExistingOrderData = array();

    /**
     * @var int $iCustomerId
     */
    protected $iCustomerId = null;

    /**
     * @var ML_Shopware_Model_Price $oPrice
     */
    protected $oPrice = null;

    /**
     * need just for order update, to know if address needs to be upddated
     * @var boolean
     */
    protected $blNewAddress = true;

    /**
     * @var Application $application
     */
    protected $application;

    /**
     * @var string $token
     */
    protected $token;

    /**
     * Init tax for Shop
     *
     * @var float
     */
    protected $getTaxDecimal = 0.00;

    /**
     * https://help.shopify.com/en/api/reference/rest-admin-api-rate-limits
     * @var int number of imported order from last delay
     */
    protected static $numberOfImportedOrder = 0;
    /**
     * @var array
     */
    protected $aWarning = array();

    protected $fProductMaxTax = null;

    /**
     * ML_Shopify_Helper_Model_ShopOrder constructor.
     */
    public function __construct() {
        $this->oPrice = MLPrice::factory();
        $sShopId = MLHelper::gi('model_shop')->getShopId();
        $this->application = new Application($sShopId);
        $this->token = MLHelper::gi('container')->getCustomerModel()->getAccessToken($sShopId);
    }

    /**
     * @param ML_Shopify_Model_Order $oOrder
     * @return $this
     * @throws Exception
     */
    public function setOrder($oOrder) {
        $this->aWarning = array();
        $this->fProductMaxTax = null;
        $this->blNewAddress = true;
        $this->oOrder = $oOrder;
        $this->oExistingOrder = null;
        $this->iCustomerId = null;
        $this->sOrderNumber = null;
        if ($this->oOrder->exists()) {
            $oShopOrder = new ML_Shopify_Model_Order();
            $this->oExistingOrder = $oShopOrder->getShopOrderObject($this->oOrder->get('current_orders_id'));
        }
        $this->aExistingOrderData = $oOrder->get('orderdata');

        return $this;
    }

    /**
     * Sets new order data
     * @param $aData
     * @return ML_Shopify_Helper_Model_ShopOrder
     */
    public function setNewOrderData($aData) {
        $this->aNewData = is_array($aData) ? $aData : array();
        $this->normalizePriceForShopify();

        return $this;
    }

    /**
     * Initializing order import and update.
     *
     * @return array
     * @throws Exception
     */
    public function shopOrder() {
        self::$numberOfImportedOrder++;
        //delay after each 5 order import, by shopify api it could reach request limitation for  a lot of orders
        if (self::$numberOfImportedOrder === 5) {
            sleep(90);
            self::$numberOfImportedOrder = 0;
        }
        if (!is_array($this->aExistingOrderData)
            || count($this->aExistingOrderData) === 0) {
            return $this->createOrder();
        } else {
            $this->iCustomerId = $this->oExistingOrder->customer->id;
            if ($this->checkForUpdate()) {
                $this->aNewData = MLHelper::gi('model_service_orderdata_merge')->mergeServiceOrderData($this->aNewData, $this->aExistingOrderData, $this->oOrder);

                return $this->updateStatus();
            } else {
                $idOldOrder = $this->oExistingOrder->id;
                $this->aNewData = MLHelper::gi('model_service_orderdata_merge')->mergeServiceOrderData($this->aNewData, $this->aExistingOrderData, $this->oOrder);

                return $this->createOrder($idOldOrder);
            }
        }
    }

    /**
     * update payment method
     * @throws Exception
     */
    public function updatePayment() {
        if (is_object($this->oExistingOrder)) {
            if ($this->aNewData['Order']['Payed'] == false) {
                return;
            }
            $this->payStatus($this->oExistingOrder->id, $this->aNewData);
        } else {
            throw new Exception('order doesn\'t exist in shop');
        }
    }

    /**
     * @todo without location we couldn't fulfill the order. it returns error {"errors":{"location_id":"expected String to be a id"}}
     *
     * update order status in shopify
     * @throws Exception
     */
    public function updateOrderStatus() {
        return $this;
        $aData = $this->aNewData;
        $ShopifyOrder = $this->oExistingOrder;

        $status = ($ShopifyOrder->fulfillment_status == null) ? 'open' : $ShopifyOrder->fulfillment_status;

        $iNewOrderStatus = $aData['Order']['Status'];

        if ($iNewOrderStatus !== $status && $status === 'open' && $iNewOrderStatus === 'fulfilled') {

            $oFulfillment = new Fulfillment();
            $oFulfillment->setOrderId($this->oExistingOrder->id);
            $oFulfillment->setLocationId('??');///???
            $oFulfillment->setStatus($iNewOrderStatus);
            $response = $this->application->getFulFillmentRequest($this->token)->setFulFillmentService($oFulfillment);
            if ($response->getCode() == 201) {
                //                    \Kint::dump($response->getBodyAsArray());
                return $this;
            } else {
                $this->logShopifyError($response);
                throw new Exception('There is a problem to create fulfillment data: '.$response->getBody());
            }
        }
    }

    /**
     *
     * We cannot update shipping method in Shopify API
     * this request doesn't return any error but it cannot update shipping methods
     * May be Shopify change it in future
     * update shipping method
     * @throws Exception
     */
    public function updateShippingMethod() {
        if (is_object($this->oExistingOrder)) {

            $aShipping = $this->getTotal('Shipping');

            if (!isset($aShipping['Code'])) {
                return '';
            }

            $orderBody = [
                'id'             => $this->oExistingOrder->id,
                'shipping_lines' => $this->getShippingLine()
            ];

            $modifyOrderParams = new ModifyOrderParams();
            $modifyOrderParams->setOrderBody($orderBody);
            $modifyOrderParams->setOrderId($this->oExistingOrder->id);
            $response = $this->application->getModifyOrderRequest()->send($modifyOrderParams);

            if ($response->getCode() == 200) {
                return $this;
            } else {
                $this->logShopifyError($response);
                throw new Exception('There is a problem to update shipping data: '.$response->getBody());
            }

        } else {
            throw new Exception('order doesn\'t exist in shop');
        }
    }

    /**
     * @return array
     * @throws Exception
     */
    protected function getShippingLine() {
        $aShipping = $this->getTotal('Shipping');
        $sShippingMethod = !empty($aShipping['Code']) ? $aShipping['Code'] : $aShipping['Type'];
        $fShippingCost = (float)$aShipping['Value'];
        $fTaxRatePercent = $this->getTaxPercentForShippingCost();
        $fShippingCostNet = $fShippingCost == 0 ? $fShippingCost : MLPrice::factory()->calcPercentages($fShippingCost, null, $fTaxRatePercent);
        return [
            [
                'price'     => $aShipping['Value'],
                'title'     => $sShippingMethod,
                'code'      => $sShippingMethod,
                'tax_lines' => [
                    [
                        'title' => 'VAT',
                        'price' => $fShippingCost - $fShippingCostNet,
                        'rate'  => $fTaxRatePercent / 100,
                    ]
                ]
            ]
        ];
    }

    /**
     * Updates existing order
     *
     * @return array
     * @throws Exception
     */
    public function updateStatus() {
        $this->updateOrderStatus();
        $this->updateShippingMethod();
        $this->updatePayment();
        return $this->aNewData;
    }

    /**
     * @param $aAddresses
     * @return array [countryCode, countryName, provinceCode, provinceName]
     */
    public function getCustomerCountry($aAddresses) {
        $aCountries = $this->getListOfCountriesFromShopifyAsArray();
        $sCustomerCountryCode = '';
        $sCustomerCountryName = '';
        $sCustomerProvincesName = '';
        $sCustomerProvincesCode = '';
        $sMarketplaceCountryCode = $aAddresses['CountryCode'];
        $sMarketplaceProvinceName = $aAddresses['Suburb'];

        foreach ($aCountries as $country) {
            if ($country['code'] == $sMarketplaceCountryCode) {
                $sCustomerCountryName = $country['name'];
                $sCustomerCountryCode = $country['code'];
                break;
            }
            if (isset($country['provinces']) && is_array($country['provinces'])) {
                foreach ($country['provinces'] as $aProvinces) {
                    if ($aProvinces['name'] == $sMarketplaceProvinceName) {
                        $sCustomerCountryCode = $country['code'];
                        $sCustomerCountryName = $country['name'];
                        $sCustomerProvincesCode = $aProvinces['code'];
                        $sCustomerProvincesName = $aProvinces['name'];
                        break;
                    }
                }
            }
        }

        /**
         * if country is not set in shop - provide marketplace country code as customer country code
         * Otherwise shipping or billing address in order will not be shown
         */
        if (empty($sCustomerCountryCode)) {
            $sCustomerCountryCode = $sMarketplaceCountryCode;
        }

        return array($sCustomerCountryCode, $sCustomerCountryName, $sCustomerProvincesCode, $sCustomerProvincesName);
    }

    /**
     * Data is extracted from Shopify orders.
     *
     * If totalPrice==true return Order_Total ( ( Price * Quantity ) + Shipment )
     * else return total_line_items for all products ( Price * Quantity )
     *
     * @param bool $totalPrice
     * @param null $oldOrderId
     * @return int
     */
    protected function getOrderTotal($totalPrice = true, $oldOrderId = null) {

        if ($oldOrderId != null) {
            $objSingle = new SingleOrderParams();
            $objSingle->setOrderId($oldOrderId);
            $responseJsonOrder = $this->application->getOrderRequest($this->token)->getSingleOrder($objSingle);
            $obj = $responseJsonOrder->getBodyAsObject();
            $_return = ($totalPrice == true) ? $obj->order->total_price : $obj->order->total_line_items_price;

            return $_return;
        }

        return 0;
    }

    /**
     * Creates a new order by magnalister order data
     *
     * @param null $oldOrderId
     * @return array
     * @throws Exception
     */
    public function createOrder($oldOrderId = null) {
        $aData = $this->aNewData;
        $aAddresses = $aData['AddressSets'];

        if (empty($aAddresses['Main'])) {// add new order when Main address is filled
            throw new Exception('main address is empty');
        }

        if (count($aData['Products']) <= 0) {// add new order when order has any product
            throw new Exception('product is empty');
        }

        $this->addCustomerToOrder($aData['AddressSets']);

        $sOrderCurrency = $aData['Order']['Currency'];

        $newOrderParams = new NewOrderParams();
        $orderBody = new stdClass();
        $address = $aAddresses['Main'];

        $orderBody->customer = array(
            'first_name' => $address['Firstname'],
            'last_name'  => $address['Lastname'],
            'email'      => $address['EMail'],
        );

        /* add discount to order */
        $Discount = $this->getTotal('Discount');
        if (!empty($Discount)) {
            $orderBody->discount_codes = $this->getDiscount($Discount);
            $orderBody->total_discounts = abs($Discount['Value']);
        }

        // Load Config Payment Status
        $sConfigPaymentStatus = MLModul::gi()->getConfig('orderimport.paymentstatus');

        // Add transaction to order
        $this->addTransactionToOrderBody($orderBody, $aData, $sConfigPaymentStatus);

        $orderBody->processed_at = $aData['Order']['DatePurchased'];
        $orderBody->financial_status = $this->getFinancialStatus($aData['Order']['Payed'], $sConfigPaymentStatus);
        $orderBody->currency = $sOrderCurrency;
        $orderBody->billing_address = $this->formAddress($aAddresses, 'Billing');
        $orderBody->shipping_address = $this->formAddress($aAddresses, 'Shipping');
        $orderBody->note_attributes = $this->getAdditionalDetails();
        $orderBody = $this->addProductsAndTotals($aData, $orderBody);
        $orderBody = $this->setOrderStatus($aData, $orderBody);
        $aPayment = $this->getTotal('Payment');
        if (!empty($aPayment['Code'])) {
            $orderBody->gateway = $aPayment['Code'];
        }
        // check at first product if stock should be reduced when importing order
        $firstProduct = reset($aData['Products']);
        if (isset($firstProduct['StockSync']) && $firstProduct['StockSync'] && $this->oExistingOrder === null
        ) {
            // see https://help.shopify.com/en/api/reference/orders/order?api[version]=2019-04#create-2019-04
            $orderBody->inventory_behaviour = 'decrement_ignoring_policy';
        }

        $this->addTotalTax($orderBody);

        $newOrderParams->setOrderBody($orderBody);
        $response = $this->application->getOrderRequest($this->token)->createOrder($newOrderParams);
        if ($response->getCode() == 201) {// order is created successfully
            $shopOrder = $response->getBodyAsObject();
            $iOrderId = $shopOrder->order->id;

            $this->oOrder->set('orders_id', ($oldOrderId != null) ? $oldOrderId : $iOrderId);
            $this->oOrder->set('current_orders_id', $iOrderId);

            // set shopify order as paid if its imported as paid
            if ($sConfigPaymentStatus == '' && $aData['Order']['Payed'] == true) {
                $this->payStatus($iOrderId, $aData);
            }

            if ($oldOrderId != null) {
                $removeOrderParams = new RemoveOrderParams();
                $removeOrderParams->setOrderId($oldOrderId);
                $response = $this->application->getOrderRequest($this->token)->removeOrder($removeOrderParams);
                if ($response->getCode() > 201) {
                    throw new Exception('There is a problem to delete order data: '.$response->getBody());
                }
            }

        } else {
            $this->logShopifyError($response);
            throw new Exception('There is a problem to insert order data: '.$response->getBody());
        }
        $aData['ShopifyOrderId'] = $iOrderId;

        return $aData;
    }

    /**
     * Form to add discount to order
     *
     * @param array $data
     * @return array
     */
    protected function getDiscount($data) {
        return array(
            array(
                'code'   => $data['Code'],
                'amount' => abs($data['Value']),
                'type'   => 'fixed_amount',
            ),
        );
    }

    /**
     * @param array $aData
     * @return float|int
     */
    protected function getTotalOrder($aData) {
        $totalOrder = 0.00;
        foreach ($aData['Products'] as $aProduct) {
            $totalOrder += $aProduct['Quantity'] * $aProduct['Price'];
        }
        foreach ($aData['Totals'] as $aTotal) {
            if ($aTotal['Type'] !== 'Discount') {
                $totalOrder += $aTotal['Value'];
            } else {
                $totalOrder -= $aTotal['Value'];
            }
        }
        return round((float)$totalOrder, 2);
    }

    /**
     * @param array $aProducts
     * @return float
     */
    protected function getProductsTotalPrice($aProducts) {
        $totalPriceItems = 0;
        foreach ($aProducts as $product) {
            $totalPriceItems += $product['Price'] * $product['Quantity'];
        }

        return $totalPriceItems;
    }

    /**
     * Update pay status for Order_ID
     *
     * @param $orderId
     * @param $aData
     * @throws Exception
     */
    protected function payStatus($orderId, $aData) {
        $order = $aData['Order'];
        $aPayment = $this->getTotal('Payment');
        //check if payment is already captured
        $aTransactions = $this->application->getTransactionRequest($this->token)->getAllTransactions($orderId)->getBodyAsArray();
        if (isset($aTransactions['transactions'])) {
            foreach ($aTransactions['transactions'] as $aTransaction) {
                if ($aTransaction['kind'] === 'capture' || $aTransaction['kind'] === 'sale') {
                    return $this;
                }
            }
        }

        // if no transactions exists
        if (isset($aTransactions['transactions']) && empty($aTransactions['transactions'])) {
            $fPrice = $this->getTotalOrder($aData);
            $transaction = new Transaction();
            $transaction->setAmount((string)$fPrice);
            $transaction->setCurrency($order['Currency']);
            $transaction->setOrderId($orderId);
            $transaction->setStatus('success'); //is as default success so we dont need to set
            $transaction->setKind('sale');
            $transaction->setGateway($aPayment['Code']);
            $transaction->setSource('external'); // we need to set it to create a transaction after order creation
            $response = $this->application->getTransactionRequest($this->token)->setOrderTransaction($transaction);

            if ($response->getCode() == 201) {
                return $this;
            } else {
                $this->logShopifyError($response, array('price' => $fPrice));
            }
        } else {
            // if transactions exists update them
            $orderCaptureInfoParams = new OrderCaptureInfoParams($orderId);
            $aCaptureInfo = $this->application->getOrderRequest()->getOrderCaptureInfo($orderCaptureInfoParams)->getBodyAsArray();
            $fPrice = null;
            if (isset($aCaptureInfo['data']['o']['totalCapturableSet']) && is_array($aCaptureInfo['data']['o']['totalCapturableSet'])) {
                foreach ($aCaptureInfo['data']['o']['totalCapturableSet'] as $aPrice) {
                    if ($aPrice['currencyCode'] === $order['Currency']) {
                        $fPrice = $aPrice['amount'];

                        $transaction = new Transaction();
                        $transaction->setAmount((string)$fPrice);
                        $transaction->setCurrency($order['Currency']);
                        $transaction->setOrderId($orderId);
                        $transaction->setStatus('success'); //is as default success so we dont need to set
                        $transaction->setKind('capture');
                        $transaction->setGateway($aPayment['Code']);
                        $response = $this->application->getTransactionRequest($this->token)->setOrderTransaction($transaction);

                        if ($response->getCode() == 201) {
                            return $this;
                        } else {
                            $this->logShopifyError($response, array('price' => $fPrice));
                        }
                        break;
                    }
                }
            }
        }

    }

    /**
     * https://help.shopify.com/en/api/reference/customers/customer-address
     *
     * @param array $aAddresses
     * @param string $typeData : 'Shipping' , 'Billing' , 'Main'
     * @param bool $orderBody
     *
     * @return array
     */
    public function formAddress(array $aAddresses, $typeData, $orderBody = true) {
        [$countryCode, $countryName, $provinceCode, $provinceName] = ($orderBody) ? $this->getCustomerCountry($aAddresses['Shipping']) : $this->getCustomerCountry($aAddresses['Main']);

        $aData = array(
            'address1'     => $aAddresses[$typeData]['StreetAddress'],
            'city'         => $aAddresses[$typeData]['City'],
            'company'      => empty($aAddresses['Shipping']['Company']) ? '' : $aAddresses['Shipping']['Company'],
            'country'      => $countryName,
            'default'      => true,
            'first_name'   => empty($aAddresses[$typeData]['Firstname']) ? '--' : $aAddresses[$typeData]['Firstname'],
            'last_name'    => empty($aAddresses[$typeData]['Lastname']) ? '--' : $aAddresses[$typeData]['Lastname'],
            'phone'        => $aAddresses[$typeData]['Phone'],
            'province'     => $provinceName === '' ? $aAddresses[$typeData]['Suburb'] : $provinceName,
            'zip'          => $aAddresses[$typeData]['Postcode'],
            'country_code' => $countryCode,
        );
        if ($provinceCode !== '') {
            $aData['province_code'] = $provinceCode;
        }

        if (array_key_exists('AddressAddition', $aAddresses[$typeData]) && !empty($aAddresses[$typeData]['AddressAddition'])) {
            $aData['address2'] = $aAddresses[$typeData]['AddressAddition'];
        }

        return $aData;
    }

    /**
     * @param $aData
     * @param $orderBody
     * @return mixed
     * @throws Exception
     */
    protected function addProductsAndTotals(&$aData, $orderBody) {
        $aLineItemArray = array();

        //The price of the order in the shop currency with discounts but without shipping, taxes, and tips.
        $fSubtotalPrice = 0.00;

        //calculate quantity for each product (when order is merged it is possible to have several product with same SKU)
        $aCurrentQty = array();
        foreach ($aData['Products'] as $aNewProduct) {
            if (isset($aCurrentQty[$aNewProduct['SKU']])) {
                $aCurrentQty[$aNewProduct['SKU']] += (int)$aNewProduct['Quantity'];
            } else {
                $aCurrentQty[$aNewProduct['SKU']] = (int)$aNewProduct['Quantity'];
            }
        }

        if (count($aData['Products']) > 0) {
            foreach ($aData['Products'] as $aProduct) {
                //reduction quantity in merged order if needed
                if ($aCurrentQty[$aProduct['SKU']] != 0) {
                    foreach (isset($this->aExistingOrderData['Products']) ? $this->aExistingOrderData['Products'] : array() as $aOldProduct) {
                        if ($aProduct['SKU'] == $aOldProduct['SKU']) {
                            $aCurrentQty[$aProduct['SKU']] -= $aOldProduct['Quantity'];
                        }
                    }
                }
                $iPQty = (int)$aProduct['Quantity'];
                $aProduct['Modus'] = 0;
                [$fProductPriceWithoutTax, $aLineItem] = $this->addProductToOrder($aProduct, $aCurrentQty[$aProduct['SKU']], $orderBody);
                $aCurrentQty[$aProduct['SKU']] = 0;//in merged order we set quantity for each Sku just one time
                $fSubtotalPrice += $fProductPriceWithoutTax * $iPQty;
                $aLineItemArray[] = $aLineItem;
            }
        }

        $orderBody->source_name = $this->oOrder->getPlatformName()['platform'];

        foreach ($aData['Totals'] as &$aTotal) {
            switch ($aTotal['Type']) {
                case 'Shipping':
                {
                    $orderBody->shipping_lines = $this->getShippingLine();
                    break;
                }
                case 'Payment':
                {
                    if ((float)$aTotal['Value'] != 0.0) {
                        $uuid = $this->guid();
                        [$fNetPrice, $aLineItem] = $this->addProductToOrder(array(
                            'ItemTitle' => (isset($aTotal['Code']) && $aTotal['Code'] != '') ? $aTotal['Code'] : $aTotal['Type'],
                            'SKU'       => isset($aTotal['SKU']) ? $aTotal['SKU'] : $uuid,
                            'Price'     => $aTotal['Value'],
                            'Quantity'  => 1,
                            'Tax'       => array_key_exists('Tax', $aTotal) ? $aTotal['Tax'] : false,
                        ), $aCurrentQty[$aProduct['SKU']], $orderBody);
                        $fSubtotalPrice += $fNetPrice;
                        $aLineItemArray[] = $aLineItem;
                    }
                    break;
                }
                default:
                {
                    if ((float)$aTotal['Value'] !== 0.0) {
                        [$fNetPrice, $aLineItem] = $this->addProductToOrder(array(
                            'ItemTitle' => (isset($aTotal['Code']) && $aTotal['Code'] != '') ? $aTotal['Code'] : $aTotal['Type'],
                            'SKU'       => isset($aTotal['SKU']) ? $aTotal['SKU'] : '',
                            'Price'     => $aTotal['Value'],
                            'Quantity'  => 1,
                            'Tax'       => array_key_exists('Tax', $aTotal) ? $aTotal['Tax'] : false,
                        ), null, $orderBody);
                        $fSubtotalPrice += $fNetPrice;
                        $aLineItemArray[] = $aLineItem;
                    }
                }
            }
        }
        unset($aTotal);
        $orderBody->line_items = $aLineItemArray;

        //Price of the order before shipping and taxes
        $orderBody->subtotal_price = $fSubtotalPrice;

        //The sum of all the prices of all the items in the order, taxes and discounts included (must be positive).
        $orderBody->total_price = $this->getTotalOrder($aData);
        //Kint::dump($orderBody);
        return $orderBody;
    }

    /**
     * @return bool|string
     */
    private function guid() {
        if (function_exists('com_create_guid')) {
            return com_create_guid();
        } else {
            mt_srand((double)microtime() * 10000);//optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $uuid = substr($charid, 0, 8);
            return $uuid;
        }
    }

    /**
     * @param $aData
     * @param $orderBody
     * @return mixed
     */
    protected function setOrderStatus($aData, $orderBody) {
        $status = $aData['Order']['Status'];
        $orderBody->fulfillment_status = ($status == 'open') ? null : $status;

        return $orderBody;
    }

    /**
     * @param $orderBody
     * @throws Exception
     */
    protected function addTotalTax(&$orderBody) {
        $orderBody->taxes_included = true;
        if ($this->getTaxDecimal == 0) {
            $orderBody->taxes_included = false;
        }
        $netPrice = MLPrice::factory()->calcPercentages($orderBody->total_price, null, 100 * $this->getTaxDecimal);
        $orderBody->total_tax = $orderBody->total_price - $netPrice;
    }

    /**
     * @param $aProduct
     * @param int $iCurrentQty
     * @param $orderBody
     * @return array
     * @throws Exception
     */
    protected function addProductToOrder($aProduct, $iCurrentQty, $orderBody) { //@ToDo: $orderBody is never used
        /** @var ML_Shopify_Model_Product $oProduct */
        $oProduct = MLProduct::factory();
        $iVariantId = 0;
        if (MLDatabase::factory('config')->set('mpid', 0)->set('mkey', 'general.keytype')->get('value') === 'pID' && strpos($aProduct['SKU'], '_') === false) {
            $oProduct->getByMarketplaceSKU($aProduct['SKU'], true);
        } else {
            $oProduct->getByMarketplaceSKU($aProduct['SKU']);
        }
        if (isset($aProduct['SKU']) && $oProduct->exists()) {
            $aRealProduct = $oProduct->getRealProduct();
            if (!empty($aRealProduct)) {
                $iVariantId = $aRealProduct['id'];
            }
        }
        $fTaxPercent = $this->getTaxPercentForProducts($aProduct, $oProduct);
        if ($fTaxPercent >= (float)$this->fProductMaxTax) {
            $this->fProductMaxTax = $fTaxPercent;
        }
        $this->getTaxDecimal = $fTaxPercent / 100;
        $fGross = (float)$aProduct['Price'];
        $fNet = $fGross == 0 ? $fGross : MLPrice::factory()->calcPercentages($fGross, null, $fTaxPercent);

        $aProductArray = array(
            'variant_id' => $iVariantId,
            'price'      => (string)$fGross.'',
            'quantity'   => (int)$aProduct['Quantity'],
            'name'       => $aProduct['ItemTitle'],
            'title'      => $aProduct['ItemTitle'],
            'tax_lines'  => [
                [
                    'title' => 'VAT',
                    'price' => ($fGross - $fNet) * (int)$aProduct['Quantity'],
                    'rate'  => $this->getTaxDecimal
                ]
            ]
        );
        if (isset($aProduct['SKU'])) {
            $aProductArray['sku'] = $aProduct['SKU'];
        }

        if (isset($aProduct['SKU']) && $oProduct->getByMarketplaceSKU($aProduct['SKU'])->exists() && isset($aProduct['StockSync']) && $aProduct['StockSync'] && $this->oExistingOrder !== null && $iCurrentQty > 0) {
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
            //Note the stock will be reduced by shopify API see "inventory_behaviour" parameter while creating order
            $this->reduceStock($oProduct->getProductField('inventory_item_id'), -1 * $iCurrentQty);
        }

        return array($fNet, $aProductArray);
    }

    protected function getFallbackTax() {
        $fDefaultProductTax = MLModul::gi()->getConfig('mwst.fallback');
        // fallback
        if ($fDefaultProductTax === null) {
            $fDefaultProductTax = MLModul::gi()->getConfig('mwstfallback'); // some modules have this, other that
        }
        return $fDefaultProductTax;
    }

    /**
     * Returns list of countries from Shopify
     *
     * @return array
     */
    protected function getListOfCountriesFromShopifyAsArray() {
        return $this->application->getCountryRequest($this->token)->getListOfCountries()->getBodyAsArray()['countries'];
    }

    /**
     * Gets random number as transactionid , we have this function individually because some customer need to change this behavior by overriding this function
     *
     * @return string
     */
    protected function getTransactionId() {
        $aPayment = $this->getTotal('Payment');
        if (isset($aPayment['ExternalTransactionID']) && !empty($aPayment['ExternalTransactionID'])) {

            return $aPayment['ExternalTransactionID'];
        } else {

            return md5(uniqid(mt_rand(), true));
        }
    }

    /**
     * @param $aAddresses
     * @return int
     * @throws Exception
     */
    protected function addCustomerToOrder(&$aAddresses) {
        //gets customer id by provided email
        $iCustomerId = $this->getCustomer($aAddresses['Main']['EMailIdent']);

        $customerBody = new stdClass();

        $customerBody->first_name = $aAddresses['Main']['Firstname'];
        $customerBody->last_name = $aAddresses['Main']['Lastname'];
        $customerBody->addresses = array($this->formAddress($aAddresses, 'Main', false));
        $customerBody->default_address = $this->formAddress($aAddresses, 'Main', false);
        $customerBody->email = $aAddresses['Main']['EMailIdent'];

        if (!$iCustomerId) {
            $sPassword = '';
            for ($i = 0; $i < 10; $i++) {
                $randnum = mt_rand(0, 35);
                if ($randnum < 10) {
                    $sPassword .= $randnum;
                } else {
                    $sPassword .= chr($randnum + 87);
                }
            }

            $aAddresses['Main']['Password'] = $sPassword; //important to send password in Promotion Mail
            $this->sendCustomerData($customerBody);
        } else {
            //customer exists, update customer
            $modifyCustomerParams = new ModifyCustomerParams();

            $modifyCustomerParams->setCustomerId($iCustomerId);
            $modifyCustomerParams->setCustomerBody($customerBody);

            $response = $this->application->getCustomerRequest($this->token)->modifyCustomer($modifyCustomerParams);

            if ($response->getCode() == 200) {
                $this->iCustomerId = $response->getBodyAsObject()->customer->id;
            } else {// if it is not possible to update customer data with email, magnalister try to update customer data without email
                unset($customerBody->email);
                $modifyCustomerParams->setCustomerBody($customerBody);
                $response = $this->application->getCustomerRequest($this->token)->modifyCustomer($modifyCustomerParams);
                if ($response->getCode() == 200) {
                    $this->iCustomerId = $response->getBodyAsObject()->customer->id;
                } else {
                    MLLog::gi()->add(MLSetting::gi()->get('sCurrentOrderImportLogFileName'), array(
                        'MOrderId' => MLSetting::gi()->get('sCurrentOrderImportMarketplaceOrderId'),
                        'PHP'      => get_class($this).'::'.__METHOD__.'('.__LINE__.')',
                        'DEBUG'    => (array)$response
                    ));
                }
            }
        }

        return $this->iCustomerId;
    }

    /**
     * @param $customerBody
     * @param int $iNumberOfTry
     * @throws Exception
     */
    protected function sendCustomerData($customerBody, $iNumberOfTry = 1) {
        //insert customer
        $newCustomerParams = new NewCustomerParams();

        $newCustomerParams->setCustomerBody($customerBody);
        $response = $this->application->getCustomerRequest($this->token)->createCustomer($newCustomerParams);

        if ($response->getCode() == 201) {
            $this->iCustomerId = $response->getBodyAsObject()->customer->id;
        } else {
            $this->logShopifyError($response);
            // Special Cases if there is something wrong with the province
            if (strpos($response->getBody(), '"addresses.province":["is not valid"]') !== false && isset($customerBody->addresses[0]['province'])) {//in some special case province is validated
                unset($customerBody->addresses[0]['province']);
                unset($customerBody->addresses[0]['province_code']);
            }
            if (strpos($response->getBody(), '"email":["has already been taken"]') !== false) {//Sometime shopify return this error without any reason
                $customerBody->note = 'This customer data is imported to skip a strange bug in Shopify API that sometimes prevents importing customer data, you can delete this customer if no order is associated with it.';
                $customerBody->email = 'shopify-rejected-email-'.$customerBody->email;
            }
            if ($iNumberOfTry < 3) {
                $iNumberOfTry++;
                $this->sendCustomerData($customerBody, $iNumberOfTry);
            } else {
                throw new Exception('Error inserting customer: '.$response->getBody());
            }
        }
    }

    /**
     * Checks if customer exists
     *
     * @param $sEmail string
     *
     * @return boolean
     */
    protected function getCustomer($sEmail) {
        $customer = $this->getCustomerFromShopifyAsArray($sEmail);
        if (!empty($customer)) {
            $iCustomerId = $customer[0]['id'];

            return $iCustomerId;
        }

        return false;
    }

    /**
     * Returns customer from Shopify based on customer email
     *
     * @param $sEmail string
     *
     * @return array
     */
    protected function getCustomerFromShopifyAsArray($sEmail) {
        $listOfCustomersParams = new ListOfCustomersParams();
        $listOfCustomersParams->setQuery($sEmail);
        $aCustomer = $this->application->getCustomerRequest($this->token)->getListOfCustomers($listOfCustomersParams)->getBodyAsArray()['customers'];

        return $aCustomer;
    }

    /**
     * Check if order should be updated or create a new order with new data
     *  If returns false a new order will be created
     *
     * @return boolean
     */
    protected function checkForUpdate() {
        if (count($this->aNewData['Products']) > 0) {
            $this->blNewProduct = true;

            return false;
        }

        if (!$this->checkForShippingAddress()) {
            return false;
        }
        $this->blNewAddress = false;

        $sNewOrderMarketplaceID = $this->aNewData['MPSpecific']['MOrderID'];
        foreach ($this->aNewData['Totals'] as $aNewTotal) {
            $blFound = false;
            foreach ($this->aExistingOrderData['Totals'] as $aExistingOrderTotal) {
                // we need to check if shipping is may the same as before - because value from api is different to calculated value in shop
                if ($aNewTotal['Type'] == $aExistingOrderTotal['Type']) {
                    $blFound = true;
                    // specific when ebay recalculates the shipping costs
                    // Fallback for orgValue changes
                    if ($aNewTotal['Type'] == 'Shipping' && isset($aExistingOrderTotal['orgValue'][$sNewOrderMarketplaceID])) {
                        $fExistingOrderTotalValue = (float)$aExistingOrderTotal['orgValue'][$sNewOrderMarketplaceID];
                    } else {
                        $fExistingOrderTotalValue = (float)$aExistingOrderTotal['Value'];
                    }

                    if ((float)$aNewTotal['Value'] != $fExistingOrderTotalValue) {
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
     * If there are changes on Address Sets it returns false
     *
     * @return bool
     */
    protected function checkForShippingAddress() {
        foreach (array('Shipping', 'Billing') as $sAddressType) {// we use just shipping and billing address , we use just email of main address
            foreach (array('Gender', 'Firstname', 'Company', 'Street', 'Housenumber', 'Postcode', 'City', 'Suburb', 'CountryCode', 'Phone', 'EMail', 'DayOfBirth',) as $sField) {
                if ((isset($this->aNewData['AddressSets'][$sAddressType][$sField]) && !isset($this->aExistingOrderData['AddressSets'][$sAddressType][$sField]))
                    || (isset($this->aNewData['AddressSets'][$sAddressType][$sField]) && $this->aNewData['AddressSets'][$sAddressType][$sField] != $this->aExistingOrderData['AddressSets'][$sAddressType][$sField])
                ) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Gets specific total of Order data by total Type
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
     * @return array
     * @throws MLAbstract_Exception
     * @throws Exception
     */
    protected function getAdditionalDetails() {
        $oI18n = MLI18n::gi();

        $aAdditionalDetails = array();
        //        foreach ($this->aWarning as $sKey => $sWarning){
        //            $aAdditionalDetails['Warning'][] = $sWarning;
        //        }
        $oOrder = $this->oOrder;
        foreach ($this->aNewData['MPSpecific'] as $sKey => $mValue) {
            $aPrefixes = array("_platformName_" => MLI18n::gi()->get('sModuleName'.ucfirst($oOrder->get('platform'))));
            $sTitle = $oI18n->get($sKey, $aPrefixes);
            $sInfo = '';
            $sDate = null;
            if (in_array($sKey, array('MOrderID', 'MPreviousOrderID', 'MPreviousOrderIDS'))) {
                if ($sKey === 'MPreviousOrderIDS' && !MLSetting::gi()->get('blDebug')) {
                    continue;
                } elseif ($sKey === 'MPreviousOrderID') {
                    if (is_array($mValue)) {
                        $sDate = $mValue['date'];
                        $mValue = $mValue['id'];
                    }
                } elseif ($sKey === 'MOrderID') {
                    $aOrderData = $oOrder->get('orderdata');
                    $sDate = isset($aOrderData['Order']['DatePurchased']) ? $aOrderData['Order']['DatePurchased'] : '--';
                }
            }
            if (is_array($mValue)) {
                foreach ($mValue as $sValueKey => $sValue) {
                    $sInfo .= (is_numeric($sValueKey) ? '' : $sValueKey.': ').$oI18n->get($sValue, $aPrefixes)."\r\n";
                }
            } else {
                $sInfo .= $oI18n->get($mValue, $aPrefixes).(isset($sDate) ? " ({$sDate})" : '');
            }
            $aAdditionalDetails[$sTitle] = $sInfo;
        }

        // adds free text fields on each imported order implemented on OTTO and Amazon
        $this->addFreeTextCustomFields($aAdditionalDetails);

        return $aAdditionalDetails;
    }

    protected function logShopifyError(\Shopify\API\Application\Response\Response $response, $aExtraData = null) {
        MLLog::gi()->add(MLSetting::gi()->get('sCurrentOrderImportLogFileName'), array(
            'MOrderId'        => MLSetting::gi()->get('sCurrentOrderImportMarketplaceOrderId'),
            'PHP'             => get_class($this).'::'.__METHOD__.'('.__LINE__.')',
            'ShopifyResponse' => $response->getBodyAsArray(),
            'Extra'           => $aExtraData
        ));
    }

    protected function normalizePriceForShopify() {
        $fTotalBeforeNormalize = $this->getTotalOrder($this->aNewData);
        foreach ($this->aNewData['Products'] as &$aProduct) {
            $aProduct['Price'] = round((float)$aProduct['Price'], 2);
        }
        unset($aProduct);
        foreach ($this->aNewData['Totals'] as &$aTotal) {
            $aTotal['Value'] = round((float)$aTotal['Value'], 2);
        }
        unset($aTotal);
        $fTotalAfterNormalize = $this->getTotalOrder($this->aNewData);
        if ($fTotalAfterNormalize !== $fTotalBeforeNormalize) {
            //content should be discussed
            $sMessage = 'For this order '.MLModul::gi()->getMarketPlaceName(false).' transfers magnalister price with more than 2 decimal places. But in price fields of the Shopify order you can only enter the price with 2 decimal places.Therefore the amount of the order in Shopify and Amazon are different in second decimal place. This is a limitation in Shopify, so we can\'t show correct price in order details. '.MLModul::gi()->getMarketPlaceName(false).'-Total: '.$fTotalBeforeNormalize.', Shopify-Total: '.$fTotalAfterNormalize;
            MLLog::gi()->add(MLSetting::gi()->get('sCurrentOrderImportLogFileName'), array(
                'MOrderId' => MLSetting::gi()->get('sCurrentOrderImportMarketplaceOrderId'),
                'PHP'      => get_class($this).'::'.__METHOD__.'('.__LINE__.')',
                'Warning'  => $sMessage
            ));
            $this->aWarning[] = $sMessage;
        }
    }

    /**
     * Returns the Shopify Financial Status for an order
     *
     * @param $orderPayedStatus
     * @return string
     */
    protected function getFinancialStatus($orderPayedStatus, $sConfigPaymentStatus) {
        // so if not set use fallback and also if is empty string (Empty String means let magnalister decide) then also use fallback
        if (empty($sConfigPaymentStatus)) {
            return $orderPayedStatus ? 'paid' : 'pending';
        }

        return $sConfigPaymentStatus;
    }

    /**
     * Creates a transaction during order creation
     *  If $sConfigPaymentStatus is empty or 'pending' no transactions will bet set
     *
     * @param $orderBody
     * @param $aData
     * @param $sConfigPaymentStatus
     */
    private function addTransactionToOrderBody(&$orderBody, $aData, $sConfigPaymentStatus) {
        if ($sConfigPaymentStatus == 'authorized') {
            $orderBody->transactions = array(
                array(
                    'amount' => (string)$this->getTotalOrder($aData),
                    'kind'   => 'authorization',
                ),
            );
        } elseif ($sConfigPaymentStatus == 'paid') {
            $orderBody->transactions = array(
                array(
                    'amount' => (string)$this->getTotalOrder($aData),
                    'kind'   => 'sale',
                ),
            );
            return;
        } elseif($sConfigPaymentStatus == 'refunded') {
            $orderBody->transactions = array(
                array(
                    'amount' => (string)$this->getTotalOrder($aData),
                    'kind'   => 'refund',
                ),
            );
            return;
        } elseif($sConfigPaymentStatus == 'voided') {
            $orderBody->transactions = array(
                array(
                    'amount' => (string)$this->getTotalOrder($aData),
                    'kind'   => 'void',
                ),
            );
            return;
        }
    }


    /**
     * Implemented this function to add free text fields on each order
     *
     * @param $aAdditionalDetails
     */
    protected function addFreeTextCustomFields(&$aAdditionalDetails) {
        if (is_array(MLSetting::gi()->{'magnalister_shop_order_additional_field'})) {
            foreach (MLSetting::gi()->{'magnalister_shop_order_additional_field'} as $sFieldName) {
                $aAdditionalDetails[$sFieldName] = '';
            }
        }
    }

    /**
     * @param $aProduct
     * @param ML_Shopify_Model_Product $oProduct
     * @return float
     */
    protected function getTaxPercentForProducts($aProduct, ML_Shopify_Model_Product $oProduct): float {
        if (isset($aProduct['SKU']) && $oProduct->exists()) {
            $fTaxPercent = $oProduct->getTax($this->aNewData['AddressSets']);
        } else {
            $fTaxPercent = (($aProduct['Tax'] === false) ? $this->getFallbackTax() : $aProduct['Tax']);
        }
        return (float)$fTaxPercent;
    }

    /**
     * @param $aProduct
     * @param ML_Shopify_Model_Product $oProduct
     * @return float
     */
    protected function getTaxPercentForShippingCost(): float {
        return $this->fProductMaxTax === null ? $this->getFallbackTax() : $this->fProductMaxTax;
    }

    /**
     * @param $iInventoryItemId int Inventory item id its stock should be reduced
     * @param $iAdjustmentStock int Number that should be reduced from stock
     */
    public function reduceStock($iInventoryItemId, $iAdjustmentStock) {
        $sShopId = MLHelper::gi('model_shop')->getShopId();
        $sToken = MLContainer::getCustomer()->getAccessToken($sShopId);
        $singleProductParams = new ListOfInventoryLevelsParams();
        $singleProductParams->setInventoryItemIds(array($iInventoryItemId));
        $ProductRequest = new InventoryLevelRequest($sToken);
        $aData = $ProductRequest->getListOfInventoryItemLevels($singleProductParams)->getBodyAsArray();
        if (isset($aData['inventory_levels']) && is_array($aData['inventory_levels'])) {
            $aSelectedLevel = current($aData['inventory_levels']);
            foreach ($aData['inventory_levels'] as $aLevel) {
                if ((int)$aLevel['available'] > $iAdjustmentStock) {
                    $aSelectedLevel = $aLevel;
                    break;
                }
            }
            $adjustInventoryLevelParams = new AdjustInventoryLevelParams();
            $adjustInventoryLevelParams->setAvailableAdjustment($iAdjustmentStock);
            $adjustInventoryLevelParams->setInventoryItemId($aSelectedLevel['inventory_item_id']);
            $adjustInventoryLevelParams->setLocationId($aSelectedLevel['location_id']);
            $response = $ProductRequest->adjustInventoryLevel($adjustInventoryLevelParams);
            MLLog::gi()->add(MLSetting::gi()->get('sCurrentOrderImportLogFileName'), array(
                'MOrderId'                   => MLSetting::gi()->get('sCurrentOrderImportMarketplaceOrderId'),
                'PHP'                        => get_class($this).'::'.__METHOD__.'('.__LINE__.')',
                'Reuslt of manual reduction' => array(
                    'Code'     => $response->getCode(),
                    'Renponse' => $response->getBodyAsArray(),
                )
            ));
        }
    }
}
