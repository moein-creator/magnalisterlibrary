<?php
/*
 * IMPORTANT:
 *   - This was a workaround for the order import using the Store API on Shopware Cloud it is not used anymore
 *
 * and it should be only used for testing, but it includes bugs that are fixed in ShopOrder Helper
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
include_once(M_DIR_LIBRARY . 'request/shopware/ShopwareContext.php');
include_once(M_DIR_LIBRARY . 'request/shopware/ShopwareProduct.php');

use library\request\shopware\ShopwareProduct;
use library\request\shopware\ShopwareContext;
use library\request\shopware\ShopwareDeliveryTime;
use library\request\shopware\ShopwareRule;
use library\request\shopware\ShopwareShippingMethod;
use library\request\shopware\ShopwareOrderStatus;
use library\request\shopware\ShopwareOrder;
use library\request\shopware\ShopwareCustomer;
use library\request\shopware\ShopwarePaymentMethod;
use library\request\shopware\ShopwareCountry;
use library\request\shopware\ShopwareCurrency;
use library\request\shopware\ShopwareSalesChanel;
use library\request\shopware\ShopwareNumberRange;

// This was a workaround for the order import using the Store APi on shopware cloud it is not used anymore
class ML_ShopwareCloud_Helper_Model_ShopOrderStoreAPI extends ML_Shop_Helper_Model_ShopOrder_Abstract {

    /**
     * @var  $oExistingOrder OrderEntity
     */
    protected $oExistingOrder = null;

    /**
     * @var  $oExistingOrderLineItems
     */
    protected $oExistingOrderLineItems = null;

    /**
     * @var array $aNewProduct
     */
    protected $aNewProduct = array();

    /**
     * @var ML_Shopware6_Model_Order $oOrder
     */
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

    protected $oCustomerRequest;
    protected $oPaymentRequest;
    protected $oCountryRequest;
    protected $oCurrencyRequest;
    protected $sCurrencyId;
    protected $oSalesChanelRequest;
    protected $oShopwareOrderRequest;
    protected $oEntityNumberRequest;
    protected $oApiHelper;
    protected $sShopId;
    protected $sOrderNumberStateId;
    /**
     * set oder object in initializing the order helper
     * @param ML_ShopwareCloud_Model_Order $oOrder
     * @return ML_ShopwareCloud_Helper_Model_ShopOrder
     * @throws Exception
     */
    public function setOrder($oOrder) {
        $this->aCuttedField = [];
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
     * @return ML_Shopware6_Helper_Model_ShopOrder
     * @throws Exception
     */
    public function updateOrder(): \ML_Shopware6_Helper_Model_ShopOrder {
        $sFromStateId = $this->oOrder->getShopOrderObject()->getStateId();
        /*
         * By using orderStateTransition, some state transitions are not allowed and it could prevent to change order state
         */
        //        MagnalisterController::getOrderService()->orderStateTransition($sOrderId, $this->aNewData['Order']['Status'], new ParameterBag(), Context::createDefaultContext());

        $orderData = [
            'id' => $this->oExistingOrder->getId(),
        ];
        if ($this->oOrder->getUpdatableOrderStatus()) {

            $orderData['stateId'] = $this->getOrderStateId($this->aNewData['Order']['Status'], MLSetting::gi()->get('ORDER_STATES_STATE_MACHINE'));
            $orderData['deliveries'] = [
                [
                    'id'               => $this->getExistingDeliveryId(),
                    'shippingMethodId' => $this->getShippingMethod(),
                ],
            ];
        }
        if ($this->oOrder->getUpdatablePaymentStatus()) {
            $configPaymentStatus = $this->getPaymentStatus();
            $orderData['transactions'][] =
                [
                    'id'              => $this->oExistingOrder->getTransactions()->first()->getId(),
                    'paymentMethodId' => $this->getPaymentMethodId(),
                    'stateId'         => $this->getOrderStateId($configPaymentStatus, MLSetting::gi()->get('ORDER_TRANSACTION_STATES_STATE_MACHINE')),
                ];

        }
        if ($this->oOrder->getUpdatableOrderStatus() || $this->oOrder->getUpdatablePaymentStatus()) {
            MLShopwareCloudAlias::getRepository('order')
                ->update([$orderData], Context::createDefaultContext());
            $this->stockManagement($sFromStateId);
        }


        return $this;
    }

    /**
     * create a new order by magnalister order data
     * @return array
     * @throws Exception
     */
    public function createUpdateOrder() {
        try {
            $sSalesChanelId = MLModule::gi()->getConfig('orderimport.shop');
            $this->sShopId = MLShopwareCloudAlias::getShopHelper()->getShopId();
            $this->oCustomerRequest = new ShopwareCustomer($this->sShopId, $sSalesChanelId);
            $this->oPaymentRequest = new ShopwarePaymentMethod($this->sShopId);
            $this->oCountryRequest = new ShopwareCountry($this->sShopId);
            $this->oCurrencyRequest = new ShopwareCurrency($this->sShopId);
            $this->oSalesChanelRequest = new ShopwareSalesChanel($this->sShopId);
            $this->oShopwareOrderRequest = new ShopwareOrder($this->sShopId, $sSalesChanelId);
            $this->oEntityNumberRequest = new ShopwareNumberRange($this->sShopId);
            $this->oApiHelper = new APIHelper();
            $this->oCurrencyRequest = new ShopwareCurrency($this->sShopId);

            $oCustomer = $this->creteGuestCustomer();
            $sContextToken = $oCustomer->getSwContextToken();
            $aLineItems = $this->getProducts();
            $aData = $this->aNewData;

            if ($this->oExistingOrder === null) {
                $this->oShopwareOrderRequest->createShopwareCartStoreAPI(array('sw-context-token: ' . $sContextToken), array('items' => $aLineItems));
                $this->oShopwareOrderRequest->createShopwareOrderStoreAPI(array('sw-context-token: ' . $sContextToken));
            } else {
                $aAddresses = $aData['AddressSets'];

                if (empty($aAddresses['Main'])) {// add new order when Main address is filled
                    throw new Exception('main address is empty');
                }

                if (count($aData['Products']) <= 0) {// add new order when order has any product
                    throw new Exception('product is empty');
                }

                /**
                 * @var $oCurrency CurrencyEntity
                 */
                $preparedCurrencyPath = $this->oApiHelper->prepareFilters(array('isoCode' => $aData['Order']['Currency']), 'GET', '/api/currency');
                $oCurrency = $this->oCurrencyRequest->getShopwareCurrencies($preparedCurrencyPath);
                if (!is_array($oCurrency->getData())) {
                    $sMessage = MLI18n::gi()->get('Orderimport_CurrencyCodeDontExistsError', array(
                            'mpOrderId' => MLSetting::gi()->get('sCurrentOrderImportMarketplaceOrderId'),
                            'ISO' => $aData['Order']['Currency']
                        )
                    );
                    MLErrorLog::gi()->addError(0, ' ', $sMessage, array('MOrderID' => MLSetting::gi()->get('sCurrentOrderImportMarketplaceOrderId')));
                    throw new Exception($sMessage);
                }
                $oCurrency = $oCurrency->getData()[0];
                $this->sCurrencyId = $oCurrency->getId();
                //show  in order detail
                $sInternalComment = isset($aData['MPSpecific']['InternalComment']) ? $aData['MPSpecific']['InternalComment'] : '';

                $iPaymentID = $this->getPaymentMethodId();
                $aBillingAddress = $this->getAddress($aData['AddressSets'], 'Billing');
                $aShippingAddress = $this->getAddress($aData['AddressSets'], 'Shipping');
                $oSalesChannel = $this->getSalesChannel();
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
                    'id' => $this->oExistingOrder === null ? $this->oApiHelper::randomHex() : $this->oExistingOrder->getId(),
                    'orderNumber' => $sOrderNumber,
                    'currencyId' => $oCurrency->getId(),
                    'languageId' => $oSalesChannel->getAttributes()->getLanguageId(), //Defaults::LANGUAGE_SYSTEM use default from SalesChannel
                    'deepLinkCode' => $this->oApiHelper->getBase64UrlString(32),
                    'salesChannelId' => $oSalesChannel->getId(),
                    'currencyFactor' => $oCurrency->getAttributes()->getFactor(),
                    'stateId' => $this->getOrderStateId($aData['Order']['Status'], MLSetting::gi()->get('ORDER_STATES_STATE_MACHINE')),
                    'price' => $aPrice,
                    'shippingCosts' => $aShippingCost,
                    'customerComment' => $sInternalComment,
                    'orderCustomer' => [
                        'id' => $this->oExistingOrder === null ? $this->oApiHelper::randomHex() : $this->oExistingOrder->getRelationships()->getOrderCustomer()->getData()['id'],
                        'customerId' => $oCustomer->getId(),
                        'salutationId' => $oCustomer->getAttributes()->getSalutationId(),
                        'email' => $oCustomer->getAttributes()->getEmail(),
                        'firstName' => $oCustomer->getAttributes()->getFirstName(),
                        'lastName' => $oCustomer->getAttributes()->getLastName(),
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
                    'lineItems' => $aLineItems,
                    'deliveries' => [
                        [
                            'id' => $this->oExistingOrder === null ? $this->oApiHelper::randomHex() : $this->getExistingDeliveryId(),
                            'shippingOrderAddressId' => $blNewDelivery || $this->isNewAddress() ? $aShippingAddress['id'] : $this->getExistingDelivery()[0]->getAttributes()->getShippingOrderAddressId(),
                            'shippingMethodId' => $this->getShippingMethod(),
                            'stateId' => $blNewDelivery ? $this->getOrderStateId(MLSetting::gi()->get('ORDER_DELIVERY_STATES_STATE_OPEN'), MLSetting::gi()->get('ORDER_DELIVERY_STATES_STATE_MACHINE')) : $this->getExistingDelivery()[0]->getAttributes()->getStateId(),
                            'trackingCodes' => $blNewDelivery ? [] : $this->getExistingDelivery()[0]->getAttributes()->getTrackingCodes(),
                            'shippingDateEarliest' => (new \DateTime())->format(MLSetting::gi()->get('DEFAULTS_STORAGE_DATE_TIME_FORMAT')),
                            'shippingDateLatest' => (new \DateTime())->format(MLSetting::gi()->get('DEFAULTS_STORAGE_DATE_TIME_FORMAT')),
                            'shippingCosts' => $aShippingCost,
                            'positions' => $this->getDeliveryPosition($aLineItems),
                        ],
                    ],

                ];
                $orderData['totalRounding'] = $orderData['itemRounding'] = array(
                    'decimals' => 2,
                    'interval' => 0.01,
                    'roundForNet' => true,
                );

                if ($this->isNewAddress() || $this->oExistingOrder === null) {
                    $orderData['orderDateTime'] = (new \DateTime($aData['Order']['DatePurchased']))->format(MLSetting::gi()->get('DEFAULTS_STORAGE_DATE_TIME_FORMAT'));
                    $orderData['billingAddressId'] = $aBillingAddress['id'];
                    $orderData['addresses'] = [
                        $aBillingAddress,
                        $aShippingAddress,
                    ];
                } else {
                    $orderData['billingAddressId'] = $this->oExistingOrder->getAttributes()->getBillingAddressId();
                }

                $this->oShopwareOrderRequest->createShopwareOrders($orderData, 'PATCH', $this->oExistingOrder->getId());

                $this->oOrder->set('orders_id', $orderData['orderNumber']);//important to show order number in backoffice of magnalister
                $this->oOrder->set('current_orders_id', $orderData['id']); //important to find order in shopware database
                //            TODO check if event is created automatically on order create
                //            if ($this->oExistingOrder === null) {
                //                $this->handleOrderEvents($aData);
                //            }
                //            TODO check if event is stock automatically reduced on order create
                //            $orderData['stateId'] = $this->getOrderStateId(MLSetting::gi()->get('ORDER_STATES_STATE_OPEN'), MLSetting::gi()->get('ORDER_STATES_STATE_MACHINE'));
                //            $this->oShopwareOrderRequest->createShopwareOrders($orderData, 'PATCH', $orderData['id']);
                //            $this->stockManagement($this->getOrderStateId(MLSetting::gi()->get('ORDER_STATES_STATE_OPEN'), MLSetting::gi()->get('ORDER_STATES_STATE_MACHINE')));
            }
        } catch (\Exception $ex) {
            MLMessage::gi()->addDebug($ex);
            throw $ex;
        }

        return $aData;
    }

    protected function getProducts() {
        $fOtherAmount = $this->fTotalAmount;
        $aProducts = $this->getProduct($this->aNewData['Products']);
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
        $aAllItems = array_merge($this->getProduct($aTotalProducts), $aProducts);
        $this->fTotalProductAmount = $this->fTotalAmount - $fOtherAmount;
        return $aAllItems;
    }

    protected function getProduct($aProducts) {
        $aItems = [];
        $bOrderExist = $this->oExistingOrder !== null;
        if ($bOrderExist) {
            if ($this->oExistingOrderLineItems === null) {
                $this->getExistingOrderLineItems();
            }
        }
        foreach ($aProducts as &$aProduct) {
            $aProduct['SKU'] = isset($aProduct['SKU']) ? $aProduct['SKU'] : ' ';
            $aDbProduct = MLDatabase::getDbInstance()
                ->fetchRow('SELECT * FROM '. MLShopwareCloudAlias::getProductModel()->getTableName(). ' WHERE ProductsSku="'.$aProduct['SKU'].'"');
            $blFound = false;
            $iProductQuantity = (int)$aProduct['Quantity'];
            $fProductPrice = (float)$aProduct['Price'];

            if ($bOrderExist) {
                $iExistingOrderLineItemsCount = $this->oExistingOrderLineItems->getMeta()->getTotal();
                if ($iExistingOrderLineItemsCount > 0) {
                    // Set once productLastPosition to count of existing products in order
                    if ($this->productLastPosition === 0) {
                        $this->productLastPosition = $iExistingOrderLineItemsCount;
                    }
                    foreach ($this->oExistingOrderLineItems as $item) {
                        if ($item->getAttributes()->getQuantity() === $iProductQuantity && ($item->getAttributes()->getUnitPrice() === $fProductPrice)) {
                            if (trim($aProduct['SKU']) === '' && ($item->getAttributes()->getLabel() === $aProduct['ItemTitle'] || $item->getAttributes()->getLabel() === $aProduct['ItemTitle'].'('.$aProduct['SKU'].')')) {//in ebay sku could be empty
                                $blFound = true;
                            } else if (trim($aProduct['SKU']) !== '') {
                                $sProductNumber = $item->getAttributes()->getPayload()->getProductNumber();
                                if (isset($sProductNumber)) {
                                    if ($sProductNumber === $aProduct['SKU']) {
                                        $blFound = true;
                                    } else {// perhaps sku have changed or wrong key type of total?
                                        if (isset($aDbProduct['SKU']) && $aDbProduct['SKU'] === $sProductNumber) {
                                            $blFound = true;
                                        }
                                    }
                                }
                            }
                        }
                        if ($blFound) {
                            $this->oShopwareOrderRequest->deleteShopwareOrderItem($item->getId());
                            break;
                        }
                    }
                }
            }
            $aItem = [
                'id' => $this->oApiHelper::randomHex(),
            ];
            $sSalesChanelId = MLModule::gi()->getConfig('orderimport.shop');
            $mMarketplaceTax = ($aProduct['Tax'] !== false && array_key_exists('ForceMPTax', $aProduct) && $aProduct['ForceMPTax']) ? (float)$aProduct['Tax'] : null;
            $fProductTaxRate = $mMarketplaceTax ?? $this->getFallbackTax();
            if (isset($aDbProduct['ProductsSku']) && $bOrderExist) {
                //update order products through the admin API
                $product = $this->oShopwareOrderRequest->getShopwareOrderLineItems('/api/product/'. $aDbProduct['ProductsId'] . '/order-line-items')->getData();
                $fProductTaxRate = $mMarketplaceTax ?? MLShopwareCloudAlias::getProductModel()->getTax($this->aNewData['AddressSets']);
                if (is_array($product)) {
                    $product = $product[0];

                    $payload = [
                        'isNew' => method_exists($product, 'isNew') ? $product->getAttributes()->getPayload()->isNew() : null,
                        'isCloseout' => $product->getAttributes()->getPayload()->getIsCloseout(),
                        'customFields' => $product->getAttributes()->getPayload()->getCustomFields(),
                        'createdAt' => (MLShopwareCloudHelper::getStorageDateTime($product->getAttributes()->getPayload()->getCreatedAt())),
                        'releaseDate' => $product->getAttributes()->getPayload()->getReleaseDate()
                            ? (MLShopwareCloudHelper::getStorageDateTime($product->getAttributes()->getPayload()->getReleaseDate()))
                            : null,
                        'markAsTopseller' => $product->getAttributes()->getPayload()->getMarkAsTopseller(),
                        'productNumber' => $product->getAttributes()->getPayload()->getProductNumber(),
                        'manufacturerId' => $product->getAttributes()->getPayload()->getManufacturerId(),
                        'taxId' => $product->getAttributes()->getPayload()->getTaxId(),
                        'tagIds' => $product->getAttributes()->getPayload()->getTagIds(),
                        'categoryIds' => $product->getAttributes()->getPayload()->getCategoryIds(),
                        'propertyIds' => $product->getAttributes()->getPayload()->getPropertyIds(),
                        'optionIds' => $product->getAttributes()->getPayload()->getOptionIds(),
                        'options' => $product->getAttributes()->getPayload()->getOptions(),
                    ];
                    $aItem['coverId'] = $product->getAttributes()->getCoverId();
                    $aItem['payload'] = $payload;
                }
                $aItem['productId'] = $aDbProduct['ProductsId'];
                $aItem['identifier'] = $aItem['productId'];
                $aItem['referencedId'] = $aItem['productId'];
                if ($aProduct['StockSync']) {
                    $aItem['type'] = 'product';//that is useful for existing product in shop, to show link of product in order detail, other possible values: product, credit, custom, promotion
                } else {
                    $aItem['type'] = 'custom';//To prevent reduction in available stock on product
                }
            } else if (isset($aDbProduct['ProductsSku']) && !$bOrderExist) {
                // add order products through the store API
                $aItem['referencedId'] = $aDbProduct['ProductsId'];
            } else if (!isset($aDbProduct['ProductsSku']) && $bOrderExist) {
                // products do not exist they need to be created first
                $aItem = $this->createProductStoreAPI($fProductPrice, $iProductQuantity, $fProductTaxRate, $aProduct['SKU'], $sSalesChanelId);
            } else if (!isset($aDbProduct['ProductsSku']) && !$bOrderExist) {

                // products do not exist they need to be created first
                $aItem = $this->createProductStoreAPI($fProductPrice, $iProductQuantity, $fProductTaxRate, $aProduct['SKU'], $sSalesChanelId);
            }

            $aItem['quantity'] = $iProductQuantity;

            $aItem['label'] = $aProduct['ItemTitle'].($aItem['type'] === 'custom' ? '('.$aProduct['SKU'].')' : '');
            //More than 255 character is not allowed for order label in Shopware 6
            if (strlen($aItem['label']) > 255) {
                $aItem['label'] = mb_substr($aItem['label'], 0, 255 - 3, 'UTF-8').'...';
            }

            if ($bOrderExist) {
                [$aItem['price']] = $this->getProductPrice($fProductPrice, $iProductQuantity, $fProductTaxRate);

                //Position of item (used same logic like shopware 6) increase before usage
                $aItem['position'] = ++$this->productLastPosition;

                if (!$blFound) {
                    //Position of item (used same logic like shopware 6) increase before usage
                    $aItem['position'] = ++$this->productLastPosition;

                }
            }

            $aItems[] = $aItem;
        }
        return $aItems;
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
        if (is_array($oOrderDeliveries->getData()) && isset($oOrderDeliveries->getData()[0])) {
            $result = $oOrderDeliveries->getData();
        }

        return $result;
    }


    protected function getExistingTransactionId() {
        if ($this->oExistingOrder === null) {
            throw new Exception('Use this function only for merging and updating order');
        }
        $sTransactionId = null;
        $sOrderTransactionUrl = $this->oExistingOrder->getRelationships()->getTransactions()->getLinks()->getRelated();
        $oOrderTransactions = $this->oShopwareOrderRequest->getShopwareOrderTransactions($sOrderTransactionUrl);
        if (is_array($oOrderTransactions->getData())) {
            $sTransactionId = $oOrderTransactions->getData()[0]->getId();
        }
        return $sTransactionId;
    }


    protected function getExistingDeliveryId() {
        $oOrderDeliveries = $this->getExistingDelivery();
        return $oOrderDeliveries[0]->getId();
    }

    protected function addTotalAmount($fGrossAmount, $fNetAmount): void {
        $this->fTotalAmount += $fGrossAmount;
        $this->fTotalAmountNet += $fNetAmount;
    }

    private function getExistingOrderLineItems() {
        $sLineItemsUrl = $this->oExistingOrder->getRelationships()->getLineItems()->getLinks()->getRelated();
        $oOrderLineItems = $this->oShopwareOrderRequest->getShopwareOrderLineItems($sLineItemsUrl. '?total-count-mode=1')->getData();

        $this->oExistingOrderLineItems = $oOrderLineItems;
    }

    /**
     * @return float Rate
     * @throws Exception
     */
    protected function getTaxRate(): float {
        $fDefaultProductTax = 0.00;
        if ($this->oExistingOrder !== null) {
            if ($this->oExistingOrderLineItems === null) {
                $this->getExistingOrderLineItems();
            }
            foreach ($this->oExistingOrderLineItems as $item) {
                foreach ($item->getPrice()->getCalculatedTaxes() as $oTax) {
                    $this->fMaxProductTax = max($this->fMaxProductTax, $oTax->getTaxRate());
                }
            }
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

    protected function creteGuestCustomer() {
        $aAddress = $this->aNewData['AddressSets']['Main'];
        $sSalutationId = $this->getSalutationId($aAddress['Gender']);
        $mDefaultShippingAddress = $this->getAddress($this->aNewData['AddressSets'], 'Shipping');
        $mDefaultBillingAddress = $this->getAddress($this->aNewData['AddressSets'], 'Billing');

        if ($aAddress['Firstname'] === '') {
            $aAddress['Firstname'] = '--';
        }

        $customerData = [
            'email' => $aAddress['EMail'],
            'salutationId' => $sSalutationId,
            'firstName' => $aAddress['Firstname'],
            'lastName' => $aAddress['Lastname'],
            'acceptedDataProtection' => true, // check what this is
            //TODO replace with commented line when needs to be comited
//            'storefrontUrl' => MLHttp::gi()->getBaseUrl(),
            'storefrontUrl' => 'http://shopware6.test/public',
            'billingAddress' => $mDefaultBillingAddress,
            'shippingAddress' => $mDefaultShippingAddress,
            'accountType' => 'private',
            'guest' => true,
        ];

        try {
            return $this->oCustomerRequest->createShopwareCustomerStoreAPI($customerData);
        } catch (\Exception $ex) {
            $sError = $ex->getMessage();
            throw new \Exception('Customer cannot be created: '.$sError);
        }
    }

    /**
     * Try to find customer in Shopware by email. if it doesn't exists, it creates new customer, at the end it returns customer id
     * @return string new created or existing customer entity
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
                'salesChannelId'         => $this->getSalesChannel()->getId(),
                'defaultPaymentMethodId' => $mPaymentMethodId,
                'firstName'              => $aAddress['Firstname'],
                'lastName'               => $aAddress['Lastname'],
                'salutationId'           => $sSalutationId,
                'groupId'                => $sConfigCustomerGroup === '-' ? $this->getSalesChannel()->getId() : $sConfigCustomerGroup,
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
        try {
            if ($blNewCustomer) {
                $aCustomerNumberState = $this->generateEntityNumber('customer');
                $customer['email'] = $aAddress['EMail'];
                $customer['password'] = 'not';
                $customer['guest'] = $sConfigCustomerGroup === '-';
                $customer['customerNumber'] = (string)$aCustomerNumberState['entityNumber'];
                $this->oCustomerRequest->createShopwareCustomer($customer);
                $this->oEntityNumberRequest->createShopwareNumberRangeStates(array('lastValue' => (int)$aCustomerNumberState['entityNumber']), 'PATCH', $aCustomerNumberState['entityId']);
            } else {
                $this->oCustomerRequest->createShopwareCustomer($customer, 'PATCH', $sCustomerId);
            }

        } catch (\Exception $ex) {
            $sError = $ex->getMessage();
        }
        $oCustomer = $this->oCustomerRequest->getShopwareCustomers('/api/customer/'.$sCustomerId);
        if (is_string($oCustomer->getData()->getId())) {
            return $oCustomer->getData();
        } else {
            throw new \Exception('Customer cannot be created: '.$sError);
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

            if ($customerNumberRangeStateUlr !== '') {
                $oCustomerNumberRangeTypes = $this->oEntityNumberRequest->getShopwareNumberRangeStates($customerNumberRangeStateUlr);
                if (is_array($oCustomerNumberRangeTypes->getData())) {
                    $nextNumber = $oCustomerNumberRangeTypes->getData()[0]->getAttributes()->getLastValue() + 1;
                    $entityId = $oCustomerNumberRangeTypes->getData()[0]->getId();
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
//            'id'                     => $this->oApiHelper::randomHex(),
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
                $oShippingMethodRequest = new ShopwareShippingMethod($this->sShopId);
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
                    $oRuleRequest = new ShopwareRule($this->sShopId);
                    $oRules = $oRuleRequest->getShopwareRules('/api/search/rule', 'POST', $ruleRequestBody);
                    $oRule = $oRules->getData()[0];


                    $oDeliveryTimeRequest = new ShopwareDeliveryTime($this->sShopId);
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
        $oShippingMethodRequest = new ShopwareShippingMethod($this->sShopId);
        $oDeliveryTimeRequest = new ShopwareDeliveryTime($this->sShopId);
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
        throw new \LogicException('No available ShippingMethod configured');
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
        $sPaymentMethod = $this->getTotal('Payment')['Code'];
        if ($this->oApiHelper->isValidUuid(strtolower($sPaymentMethod))) {
            $sPaymentId = strtolower($sPaymentMethod);
        } else {
            $sPaymentId = $this->checkIfPaymentMethodExist($sPaymentMethod);
            if (!isset($sPaymentId)){
                $sPaymentId = $this->oApiHelper::randomHex();
                $sLanguageIds = MagnaDB::gi()->fetchArray('SELECT  ShopwareLanguageID  FROM ' . MLDatabase::factory('ShopwareCloudLanguage')->getTableName(),true);
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
            $oPaymentMethods = $this->oPaymentRequest->getPaymentMethods('/api/search/payment-method', 'POST', $preparedFilters);

            foreach ($oPaymentMethods->getData() as $paymentMethod) {
                if ($paymentMethod->getAttributes()->getName() == $sPaymentMethod) {
                    $paymentMethodId = $paymentMethod->getId();
                    break;
                } else {
                    $paymentMethodTTranslationUrl = $paymentMethod->getRelationships()->getTranslations()->getLinks()->getRelated();
                    $oPaymentMethodTranslations = $this->oPaymentRequest->getShopwareTranslations($paymentMethodTTranslationUrl);
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
     * @return SalesChannelEntity
     * @throws Exception
     */
    public function getSalesChannel() {
        $oModul = MLModule::gi();
        $sSalesChanelId = $oModul->getConfig('orderimport.shop');
        $oSalesChannel = $this->oSalesChanelRequest->getShopwareSalesChannels('/api/sales-channel/'.$sSalesChanelId);
        if ($oSalesChannel->getData()->getId() == $sSalesChanelId) {
            return $oSalesChannel->getData();
        } else {
            throw new Exception('cannot find configured sales_channel');
        }
    }

    public function getStoreContext($sSwAccessKey) {
        $oContextRequest = new ShopwareContext($this->sShopId);
        $oContext = $oContextRequest->getShopwareContext(array('sw-access-key' => $sSwAccessKey));
        return $oContext;
    }



    public function getOrderStateId(string $state, string $machine) {
        $sOrderStateId = null;
        $oOrderStatusRequest = new ShopwareOrderStatus($this->sShopId);
        $oOrderStatuses = MLHelper::gi('model_shop')->getOrderStatuses($oOrderStatusRequest, $this->oApiHelper, $machine);
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
                //TODO check if we can somehow get this from API
                $this->aNewData['Order']['PaymentStatus'] = MLSetting::gi()->get('ORDER_TRANSACTION_STATES_STATE_OPEN');
            }
        }
        return $this->aNewData['Order']['PaymentStatus'];

    }

    /**
     * @param $fProductPrice
     * @param $iProductQuantity
     * @param $fProductTaxRate
     * @param $SKU
     * @param $sSalesChanelId
     * @return array
     * @throws Exception
     */
    private function createProductStoreAPI($fProductPrice, $iProductQuantity, $fProductTaxRate, $SKU, $sSalesChanelId): array {
        $sProductId = $this->oApiHelper::randomHex();
        $fProductTotalPrice = $fProductPrice * $iProductQuantity;
        $fProductTotalPriceNet = MLPrice::factory()->calcPercentages($fProductTotalPrice, null, $fProductTaxRate);
        $productData = array(
            'id' => $sProductId,
            'taxId' => MLModule::gi()->getConfig('orderimport.taxid'),
            'active' => true,
            'price' => array(
                array(
                    'currencyId' => 'b7d2554b0ce847cd82f3ac9bd1c0dfca',
                    'net' => $fProductTotalPriceNet,
                    'gross' => $fProductTotalPrice,
                    'linked' => true
                )
            ),
            'productNumber' => $SKU,
            'payload' => [
                'productNumber' => $SKU,
            ],
            'type' => 'product',
            'stock' => $iProductQuantity,
            'visibilities' => array(
                array(
                    'productId' => $sProductId,
                    'salesChannelId' => $sSalesChanelId,
                    //ProductVisibilityDefinition::VISIBILITY_ALL;  30 - Visible
                    'visibility' => 30
                )),
            'name' => 'magnalister product '.$SKU,
        );
        $oProductRequest = new ShopwareProduct($this->sShopId);
        try {
            $oProductRequest->createShopwareProducts($productData);
        } catch (\Exception $ex) {
            $sError = $ex->getMessage();
            throw new \Exception('Product cannot be created: '.$sError);
        }

        return array(
            'referencedId' => $sProductId,
            'type' => 'product',
        );
    }


}

