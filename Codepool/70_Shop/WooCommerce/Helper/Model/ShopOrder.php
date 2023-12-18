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
 * (c) 2010 - 2021 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

class ML_WooCommerce_Helper_Model_ShopOrder {

    /**
     * @var wpdb $oPressDB
     */
    protected $oPressDB = null;

    /**
     * @var int $iCustomerId
     */
    protected $iCustomerId = null;

    /**
     * @var array $aCurrentData
     */
    protected $aCurrentData = array();

    /**
     * @var WC_Order $oCurrentOrder
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
     * @var ML_WooCommerce_Model_Order $oOrder
     */
    protected $oOrder = null;

    /**
     * @var ML_WooCommerce_Model_Price $oPrice
     */
    protected $oPrice = null;

    protected $tempTaxClasses = array();

    /**
     * required for order update, if address should be updated
     * @var boolean
     */
    protected $blNewAddress = true;

    /**
     * true if we have new product in updating order
     * @var bool
     */
    protected $blNewProduct = false;

    /**
     * construct
     */
    public function __construct() {
        $this->oPrice = MLPrice::factory();
    }

    /**
     * @return wpdb
     */
    protected function getPressDb() {
        if ($this->oPressDB === null) {
            global $wpdb;
            $this->oPressDB = $wpdb;
        }

        return $this->oPressDB;
    }

    /**
     * set new order data
     */
    public function setNewOrderData($aData) {
        $this->aNewData = is_array($aData) ? $aData : array();

        return $this;
    }

    /**
     * @param WC_Order $oOrder
     *
     * @return \ML_WooCommerce_Helper_Model_ShopOrder
     */
    public function setOrder($oOrder) {
        $this->blNewAddress = true;
        $this->oOrder = $oOrder;
        $this->oCurrentOrder = null;
        $this->iCustomerId = null;
        if ($this->oOrder->exists()) {
            $this->oCurrentOrder = new WC_Order($oOrder->get('current_orders_id'));
        }
        $this->aCurrentData = $oOrder->get('orderdata');

        return $this;
    }

    /**
     * order import or update
     * @return array
     */
    public function shopOrder() {  //$this->aCurrentData = [];
        if (empty($this->aCurrentData)) {
            $aReturnData = $this->createOrder();
        } elseif (!is_object($this->oCurrentOrder)) {// if order doesn't exist in shop  we create new order
            $this->aNewData = MLHelper::gi('model_service_orderdata_merge')->mergeServiceOrderData($this->aNewData,
                $this->aCurrentData, $this->oOrder);
            $aReturnData = $this->createOrder();
        } else {//update order if exist
            $this->iCustomerId = $this->oCurrentOrder->get_customer_id();
            if ($this->checkForUpdate()) {
                $this->aNewData = MLHelper::gi('model_service_orderdata_merge')->mergeServiceOrderData($this->aNewData,
                    $this->aCurrentData, $this->oOrder);
                $this
                    ->updateOrderStatus();
                $aReturnData = $this->aNewData;
            } else {
                $this->aNewProduct = $this->aNewData['Products'];
                $this->aNewData = MLHelper::gi('model_service_orderdata_merge')->mergeServiceOrderData($this->aNewData,
                    $this->aCurrentData, $this->oOrder);
                $aReturnData = $this->updateOrder();
            }
        }

        return $aReturnData;
    }

    /**
     * check if order should be updated or should be added or extended
     * @return boolean
     */
    protected function checkForUpdate() {
        if (count($this->aNewData['Products']) > 0) {
            $this->blNewProduct = true;
            return false;
        }
        $this->blNewProduct = false;
        foreach (array('Shipping', 'Billing') as $sAddressType) {
            foreach (
                array(
                    'Gender',
                    'Firstname',
                    'Company',
                    'Street',
                    'Housenumber',
                    'Postcode',
                    'City',
                    'Suburb',
                    'CountryCode',
                    'Phone',
                    'EMail',
                    'DayOfBirth',
                ) as $sField
            ) {
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
                if ($aNewTotal['Type'] == $aCurrentTotal['Type']) {
                    $blFound = true;
                    if ((float)$aCurrentTotal['Value'] != (float)$aNewTotal['Value']) {
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
     * get random number as transactionid , we have this function individually because some customer need to change this behavior by overriding this function
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
     * update payment method
     * @throws Exception
     */
    public function updatePaymentMethod() {
        if (is_object($this->oCurrentOrder)) {
            $aPayment = $this->getTotal('Payment');
            $idCurrentOrder = $this->oCurrentOrder->get_id();
            if (isset($aPayment['ExternalTransactionID']) && !empty($aPayment['ExternalTransactionID'])) {
                update_post_meta($idCurrentOrder, '_transaction_id', $aPayment['ExternalTransactionID']);
            }

            update_post_meta($idCurrentOrder, '_payment_method', $aPayment['Code']);
            update_post_meta($idCurrentOrder, '_payment_method_title', $aPayment['Code']);

            return $this;
        }

        throw new Exception('order doesn\'t exist in shop');
    }

    /**
     * update shipping method
     * @throws Exception
     */
    public function updateShippingMethod() {
        if (is_object($this->oCurrentOrder)) {
            $iDispatchId = $this->getDispatch();
            //updating shipping method
            foreach ($this->oCurrentOrder->get_shipping_methods() as $method) {
                $method['method_title'] = $iDispatchId->get_method_title();
                $method['method_id'] = $iDispatchId->id.':'.$iDispatchId->method_order;
            }
            $this->oCurrentOrder->save();

            return $this;
        }

        throw new Exception('order doesn\'t exist in shop');
    }

    /**
     * update order status
     */
    public function updateOrderStatus() {
        try {
            $aData = $this->aNewData;
            if (is_object($this->oCurrentOrder)) {
                $oWooCommerceOrder = $this->oCurrentOrder;
                //updating order status
                $iNewOrderStatus = $aData['Order']['Status'];
                if ($iNewOrderStatus !== 'wc-'.$oWooCommerceOrder->get_status()) {
                    $oWooCommerceOrder->set_status($iNewOrderStatus);
                    $oWooCommerceOrder->save();
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
     * update existing order
     * @return array
     * @throws Exception
     */
    public function updateOrder() {
        $aData = $this->aNewData;

        //update payment method
        $this->updatePaymentMethod();
        //update shipping method
        $this->updateShippingMethod();
        //update order statuses
        $this->updateOrderStatus();

        $aAddresses = $aData['AddressSets'];
        if (empty($aAddresses['Main'])) {
            throw new Exception("Main address is empty");
        }

        $aPayment = $this->getTotal('Payment');
        $iCustomerPaymentID = $this->getPaymentMethod($aPayment['Code'], false);
        $this->addCustomerToOrder($aAddresses, $iCustomerPaymentID);
        //update address data
        $addressShipping = $this->formAddress($aAddresses, 'Shipping');
        $addressBilling = $this->formAddress($aAddresses, 'Billing');
        $this->oCurrentOrder->set_address($addressShipping, 'shipping');
        $this->oCurrentOrder->set_address($addressBilling, 'billing');
        $this->oCurrentOrder->apply_changes();
        $iOrderId = $this->oCurrentOrder->get_id();

        $this->oCurrentOrder->remove_order_items();

        $this->createOrder($iOrderId);
        $order = wc_get_order($iOrderId);
        $order->add_order_note('Order updated from API magnalister.');
        $order->save();
        $aData['WooCommerceOrderNumber'] = $iOrderId;

        //Reduce stock levels for items within an order
        if ('yes' === get_option('woocommerce_manage_stock')) {
            wc_reduce_stock_levels($iOrderId);
        }

        return $aData;
    }

    public function formAddress($aData, $typeData) {
        $sAddressField2 = '';

        // Process AddressAddition field if provided from API and is not empty
        if (array_key_exists('AddressAddition', $aData[$typeData]) && !empty($aData[$typeData]['AddressAddition'])) {
            $sAddressField2 = $aData[$typeData]['AddressAddition'];
        }

        return $address = array(
            'first_name' => $aData[$typeData]['Firstname'],
            'last_name'  => $aData[$typeData]['Lastname'],
            'company'    => $aData[$typeData]['Company'],
            'email'      => $aData[$typeData]['EMail'],
            'phone'      => $aData[$typeData]['Phone'],
            'address_1'  => $aData[$typeData]['StreetAddress'],
            'address_2'  => $sAddressField2,
            'city'       => $aData[$typeData]['City'],
            'state'      => $aData[$typeData]['CountryCode'],
            'postcode'   => $aData[$typeData]['Postcode'],
            'country'    => $aData[$typeData]['CountryCode'],
        );
    }

    /**
     * creates imported orders (saves order into shop db, set addresses....)
     * @return array
     * @throws Exception
     */
    public function createOrder($id = null) {
        $includeTax = get_option('woocommerce_prices_include_tax');
        update_option('woocommerce_prices_include_tax', 'yes');
        $aData = $this->aNewData;
        $aAddresses = $aData['AddressSets'];

        if (empty($aAddresses['Main'])) {
            throw new Exception('main address is empty');
        }

        if (count($aData['Products']) <= 0) {
            throw new Exception('product is empty');
        }


        // Create a new order, and set the billing and shipping addresses.
        /** @var WC_Order $order */
        /**
         * "Type":"Payment",
         * "Code":"None", // "ebay"
         * "Value":"0",
         * "Tax":false,
         * "Complete":false,
         * "PaidTime":"0000-00-00 00:00:00",
         * "ExternalTransactionID":""
         */
        $aPayment = $this->getTotal('Payment');

        if ($id !== null) {
            $order = $this->oCurrentOrder;
        } else {
            $iCustomerPaymentID = $this->getPaymentMethod($aPayment['Code'], true);
            $iCustomerNumber = $this->addCustomerToOrder($aAddresses, $iCustomerPaymentID);
            $addressShipping = $this->formAddress($aAddresses, 'Shipping');
            $addressBilling = $this->formAddress($aAddresses, 'Billing');

            $order = new WC_Order(['customer_id' => $iCustomerNumber]);
            $order->set_customer_id($iCustomerNumber);
            $order->set_id($id);
            $order->set_address($addressShipping, 'shipping');
            $order->set_address($addressBilling, 'billing');
            // If Currency is given by API apply it to the order on WooCommerce
            if (array_key_exists( 'Currency', $aData['Order']) && !empty($aData['Order']['Currency'])) {
                $order->set_currency($aData['Order']['Currency']);
            }

            //show in order detail and invoice pdf
            $sCustomerComment = '';
            if (MLModule::gi()->getConfig('order.information')) {
                $sCustomerComment .= isset($aData['MPSpecific']['InternalComment']) ? $aData['MPSpecific']['InternalComment'] : '';
                try {
                    $order->set_customer_note($sCustomerComment);
                } catch (WC_Data_Exception $e) {
                    // just accept that data could not be set
                }
            }

            $order->apply_changes();
        }
        $aTotalProducts = array();
        // add orders totals
        foreach ($aData['Totals'] as $aTotal) {
            switch ($aTotal['Type']) {
                case 'Shipping':
                {
                    //Shipping cost will be edited separately
                    break;
                }
                case 'Payment':
                default:
                {
                    if ((float)$aTotal['Value'] !== 0.0) {
                        $aTotalProducts[] = [
                            'SKU'        => isset($aTotal['SKU']) ? $aTotal['SKU'] : '',
                            'ItemTitle'  => (isset($aTotal['Code']) && $aTotal['Code'] !== '' && !is_numeric($aTotal['Code'])) ? $aTotal['Code'] : $aTotal['Type'],
                            'Quantity'   => 1,
                            'Price'      => $aTotal['Value'],
                            'Tax'        => array_key_exists('Tax', $aTotal) ? $aTotal['Tax'] : false,
                            'ForceMPTax' => array_key_exists('Tax', $aTotal) ? $aTotal['Tax'] : false,
                        ];

                    }
                    break;
                }
            }
        }
        $aAllItems = array_merge($aData['Products'], $aTotalProducts);

        foreach ($aAllItems as $aProduct) {
            $oMLProduct = MLProduct::factory();
            if (empty($aProduct['SKU']) || !$oMLProduct->getByMarketplaceSKU($aProduct['SKU'])->exists()) {
                $oProduct = new WC_Product_Simple();
                if (!array_key_exists('SKU', $aProduct)) {
                    $aProduct['SKU'] = '';
                }
                $oProduct->set_name($aProduct['ItemTitle'].' (SKU: '.$aProduct['SKU'].')');
            } else {
                $oProduct = wc_get_product($oMLProduct->get('ProductsId'));
            }

            $oProduct->set_price($aProduct['Price']);

            if (
                $aProduct['Tax']
                && (array_key_exists('ForceMPTax', $aProduct))
                && $aProduct['ForceMPTax']
            ) {
                $taxClass = $this->resolveTaxClass($aProduct, $addressShipping);
                $oProduct->set_tax_class($taxClass[0]);
                $this->tempTaxClasses[] = $taxClass;
            }

            $item_id = $order->add_product($oProduct, $aProduct['Quantity']);
            $item = new WC_Order_Item_Product($item_id);
            if ($id !== null) {//if order exists
                $blAdd = true;
                if ($id !== null) {
                    if ($this->blNewProduct) {
                        if (!(isset($aProduct['MOrderID']))) { // old product without mlorderid
                            $blAdd = false;
                        } else if ($aProduct['MOrderID'] != $this->aNewData['MPSpecific']['MOrderID']) { // product related to older imported with mlorderid
                            $blAdd = false;
                        }
                    } else {
                        $blAdd = false;
                    }
                }
                if (!$blAdd) {
                    //This solution get from WooCommerce "wc-stock-function". It is used only for merging order, and prevent to reduce previously imported item
                    $item->add_meta_data('_reduced_stock', $aProduct['Quantity'], true);
                    $item->save();
                }
            }

            // For like Amazon FBA Orders StockSync could be false and for this products we should not reduce the stock
            if (isset($aProduct['StockSync']) && !$aProduct['StockSync']) {
                $item->add_meta_data('_reduced_stock', $aProduct['Quantity'], true);
                $item->save();
            }
        }
        $order->apply_changes();

        if (!$id) {
            $order->add_order_note('Order automatically imported from API magnalister.');
        }
        $order->calculate_totals();
        $this->addShipping($order);
        $order->update_taxes();
        $order->set_status($this->manageOrderStatus($aData));
        $order->calculate_totals(false);

        $order->save();
        update_option('woocommerce_prices_include_tax', $includeTax);

        // Remove temp tax classes
        if (!empty($this->tempTaxClasses)) {
            foreach ($this->tempTaxClasses as $tempTaxClass) {
                WC_Tax::delete_tax_class_by('slug', $tempTaxClass[0]);
                WC_Tax::_delete_tax_rate($tempTaxClass[1]);
            }
        }
        $orderId = $order->get_id();

        // adds free text fields on each imported order implemented on OTTO and Amazon
        $this->addFreeTextCustomFields($orderId);

        $this->reduceStock($orderId);
        $this->setOrderPayment($orderId, $aPayment);

        return $aData;
    }

    private function resolveTaxClass($product, $address) {

        $country = $address['country'];
        $mp = MLModul::gi()->getMarketPlaceName();
        $taxClassName = ucfirst($mp).' Tax '.$product['Tax'].'%';
        $taxClassSlug = str_replace(array(' ', '%'), array('-', ''), strtolower($taxClassName)).$product['Tax'];
        WC_Tax::create_tax_class($taxClassName, $taxClassSlug);
        WC_Cache_Helper::invalidate_cache_group('taxes');
        $taxRate = array(
            'tax_rate_country'  => strtoupper($country),
            'tax_rate_state'    => '*',
            'tax_rate'          => $product['Tax'],
            'tax_rate_name'     => $taxClassName,
            'tax_rate_priority' => 1,
            'tax_rate_compound' => 0,
            'tax_rate_shipping' => 1,
            'tax_rate_order'    => 0,
            'tax_rate_class'    => $taxClassSlug,
        );

        $tax_rate_id = WC_Tax::_insert_tax_rate($taxRate);


        return array($taxClassSlug, $tax_rate_id);
    }

    private function manageOrderStatus(&$aData) {
        // Order status
        $orderStatus = isset($aData['Order']['Status']) && !empty($aData['Order']['Status']) ? $aData['Order']['Status'] : '';
        $aData['Order']['Status'] = $orderStatus;
        if ($orderStatus === 'wc-pending' && $aData['Order']['Payed'] == true) {
            $orderStatus = 'wc-processing';
        }

        return $orderStatus;
    }

    private function reduceStock($idOrder) {
        //Reduce stock levels for items within an order if stock management is active
        if ('yes' === get_option('woocommerce_manage_stock')) {
            wc_reduce_stock_levels($idOrder);
        }
    }

    private function setOrderPayment($idOrder, $aPayment) {
        if (isset($aPayment['ExternalTransactionID'])) {
            update_post_meta($idOrder, '_transaction_id', $aPayment['ExternalTransactionID']);
        }
        update_post_meta($idOrder, '_payment_method', $aPayment['Code']);
        update_post_meta($idOrder, '_payment_method_title', $aPayment['Code']);

        $this->oOrder->set('orders_id', $idOrder); //very important
        $this->oOrder->set('current_orders_id', $idOrder); //very important
    }

    /**
     * {
     * "Type":"Shipping",
     * "Code":"DE_DHLPaket",
     * "Value":4.5,
     * "Tax":false
     * }
     * get specific total of Order data by total Type
     *
     * @param string $sName
     *
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
     * @param $order WC_Order
     *
     * @throws WC_Data_Exception
     */
    public function addShipping($order) {
        $iDispatch = $this->getDispatch();
        $aShipping = $this->getTotal('Shipping');
        $optionId = (int)$aShipping['Code'];
        $method = $this->getShippingMethod($optionId);

        if ($method) {
            $iDispatch->method_title = $method['title'];
            $taxable = isset($method['tax_status']) && $method['tax_status'] === 'taxable';
        } elseif (!is_int($aShipping['Code'])) {
            if (empty($aShipping['Value'])) {
                $taxable = false;
                $iDispatch->method_title = 'Free shipping';
            } else {
                $taxable = $this->matchShippingMethod($aShipping['Code'], $order->get_data()['shipping']['country']);
                $iDispatch->method_title = $aShipping['Code'];
            }
        } else {
            $taxable = true;
        }

        $shippingItem = new WC_Order_Item_Shipping();
        $shippingItem->set_name($iDispatch->method_title);
        $taxData = array();

        foreach ($order->get_items('tax') as $itemId => $itemTax) {
            $taxData[] = $itemTax->get_data();
        }
        usort($taxData, function ($a, $b) {
            $a = $a['rate_percent'];
            $b = $b['rate_percent'];
            if ($a === $b) {
                return 0;
            }

            return ($a > $b) ? -1 : 1;
        });

        $shippingTotal = $taxable ? $this->reverseTax($aShipping['Value'], count($taxData) > 0 ? $taxData[0]['rate_percent'] : null) : $aShipping['Value'];
        //setting added for germinized plugin, shipping costs set in the database do not include taxes when we import orders
        if ('yes' === get_option( 'woocommerce_gzd_shipping_tax' ) && $taxable) {
            $order->update_meta_data( '_additional_costs_include_tax', 'no');
        } else if ('yes' === get_option( 'woocommerce_gzd_shipping_tax' ) && !$taxable) {
            $order->update_meta_data( '_additional_costs_include_tax', 'yes');
        }
        $shippingItem->set_total($shippingTotal);
        $shippingItem->calculate_taxes();
        if ($taxable && count($taxData) > 0) {
            $shippingItem->set_taxes(array(
                'total' => array(
                    (string)$taxData[0]['rate_id'] => wc_format_decimal($aShipping['Value']) - $shippingTotal
                ),
            ));
        }
        $shippingItem->apply_changes();

        $order->add_item($shippingItem);

    }

    private function matchShippingMethod($title, $location) {
        $zones = WC_Shipping_Zones::get_zones();
        foreach ($zones as $zone) {
            if ($zone['zone_locations'][0]->code == $location) {
                foreach ($zone['shipping_methods'] as $method) {
                    if (strtolower($method->get_instance_option('title')) === strtolower($title)) {
                        if ($method->id === 'free_shipping' || empty($method->get_instance_option('tax_status'))) {
                            return false;
                        }

                        return $method->get_instance_option('tax_status') === 'taxable';
                    }
                }
            }
        }

        return true;
    }

    private function reverseTax($val, $tax) {
        return (float)$val / (1 + ((float)$tax / 100));
    }

    private function getShippingMethod($optionId) {
        global $wpdb;
        $query = 'SELECT * from '.$wpdb->prefix.'options WHERE (option_name LIKE "woocommerce_flat_rate_%" OR option_name LIKE "woocommerce_local_pickup_%" OR
option_name LIKE "woocommerce_free_shipping_%") AND option_id = '.$optionId;
        $shippingMethod = MLDatabase::getDbInstance()->fetchRow($query);

        if (!$shippingMethod) {
            return false;
        }

        return unserialize($shippingMethod['option_value']);
    }

    /**
     * tries to find match for shipping method in WooCommerce, else return last id for shipping method
     * @return WC_Shipping_Flat_Rate $oWCShippingFlatRate
     */
    protected function getDispatch() {
        $aTotalShipping = $this->getTotal('Shipping');

        $all_zones = WC_Shipping_Zones::get_zones();
        foreach ($all_zones as $zone) {
            /** @var WC_Shipping_Flat_Rate $oWCShippingFlatRate */
            foreach ($zone['shipping_methods'] as $oWCShippingFlatRate) {
                //$default = $oWCShippingFlatRate;
                if (isset($aTotalShipping['Code'])) {
                    if (strpos(strtolower($oWCShippingFlatRate->title),
                            strtolower($aTotalShipping['Code'])) !== false) {
                        $oWCShippingFlatRate->method_title = $oWCShippingFlatRate->get_title();

                        return $oWCShippingFlatRate;
                    }
                }
            }
        }
        $default = new WC_Shipping_Flat_Rate();
        if (!empty($aTotalShipping['Code'])) {
            $default->method_title = $aTotalShipping['Code'].' - Flat Rate';
            $default->title = $aTotalShipping['Code'].' - Flat Rate';
        } else {
            $default->method_title = 'Flat Rate';
            $default->title = 'Flat Rate';
        }
        $default->tax_status = 'none';
        $default->method_order = 0;

        return $default;
    }

    /**
     * tries to find matched payment method otherwise it will return default payment method id
     *
     * @param string $sMethodName
     * @param boolean $blActive
     *
     * @return string  (cod, cheque, paypal)
     */
    public function getPaymentMethod($sMethodName, $blActive = false) {
        $availablePaymentMeans = WC()->payment_gateways()->get_available_payment_gateways();
        if (!$blActive && isset($sMethodName) && !empty($sMethodName)) {
            foreach ($availablePaymentMeans as $availablePaymentMean) {
                if ($availablePaymentMean->id == $sMethodName || $availablePaymentMean->title == $sMethodName) {
                    return $availablePaymentMean->id;
                }
            }
        }
        //choose first position and active payment as default payment to use in order import
        foreach ($availablePaymentMeans as $availablePaymentMean) {
            return $availablePaymentMean->id;
        }
    }

    /**
     * save order shipping address
     */
    public function SaveCustomerAddress($aAddress, $fieldType, $customerId) {
        update_post_meta($customerId, $fieldType.'_first_name', $aAddress['Firstname']);
        update_post_meta($customerId, $fieldType.'_last_name', $aAddress['Lastname']);
        update_post_meta($customerId, $fieldType.'_company', $aAddress['Company']);
        update_post_meta($customerId, $fieldType.'_address_1', $aAddress['StreetAddress']);
        update_post_meta($customerId, $fieldType.'_address_2', $aAddress['Street']);
        update_post_meta($customerId, $fieldType.'_address_2', $aAddress['Street'].' '.$aAddress['Housenumber']);
        update_post_meta($customerId, $fieldType.'_postcode', $aAddress['Postcode']);
        update_post_meta($customerId, $fieldType.'_city', $aAddress['City'].', Suburb : '.$aAddress['Suburb']);
        update_post_meta($customerId, $fieldType.'_country', $aAddress['CountryCode']);
        update_post_meta($customerId, $fieldType.'_phone', $aAddress['Phone']);
        update_post_meta($customerId, $fieldType.'_email', $aAddress['EMail']);
        update_post_meta($customerId, $fieldType.'_state', $aAddress['CountryCode']);
    }

    /**
     * creates user for imported order
     *
     * @param array $aAddresses
     * @param int $iPaymentID
     *
     * @return int
     * @throws Exception
     */
    protected function addCustomerToOrder(&$aAddresses, $iPaymentID) {
        $customerEmail = $aAddresses['Main']['EMail'];
        // remove all special chars from first and lastname and use this for Username of WooCommerce
        $userName = preg_replace('/[^A-Za-z0-9\-]/', '', $aAddresses['Main']['Firstname']).'_';
        $userName .= preg_replace('/[^A-Za-z0-9\-]/', '', $aAddresses['Main']['Lastname']);

        $customerId = email_exists($customerEmail);
        // Add customer if customer doesn't exists
        if (!$customerId) {
           if (username_exists($userName)) {
                $userName .= uniqid('', false);
            }
            $sPassword = wp_generate_password(10, false);
            $aAddresses['Main']['Password'] = $sPassword; //important to send password in Promotion Mail

            $newCustomersId = wc_create_new_customer($customerEmail, $userName, $sPassword);

            if ($newCustomersId instanceof WP_Error) {
                if($newCustomersId->get_error_message() === 'Du musst der Datenschutzerklärung zustimmen.') {
                    $sMessage = MLI18n::gi()->get('WooCommerce_OrderImport_ThirdParty_GDPR_Error', array('MPOrderId' => MLSetting::gi()->get('sCurrentOrderImportMarketplaceOrderId')));
                    MLErrorLog::gi()->addError(0, ' ', $sMessage, array('MOrderID' => MLSetting::gi()->get('sCurrentOrderImportMarketplaceOrderId')));
                    MLLog::gi()->add(MLSetting::gi()->get('sCurrentOrderImportLogFileName'), array(
                        'MOrderId' => MLSetting::gi()->get('sCurrentOrderImportMarketplaceOrderId'),
                        'PHP'      => get_class($this).'::'.__METHOD__.'('.__LINE__.')',
                        'Error Object'    => serialize($newCustomersId)
                    ));
                }
                throw new Exception($sMessage . ' ('. $newCustomersId->get_error_message().')');
            } else if (!is_numeric($newCustomersId)) {
                MLLog::gi()->add(MLSetting::gi()->get('sCurrentOrderImportLogFileName'), array(
                    'MOrderId'                => MLSetting::gi()->get('sCurrentOrderImportMarketplaceOrderId'),
                    'PHP'                     => get_class($this).'::'.__METHOD__.'('.__LINE__.')',
                    'Problem By New Customer' => array(
                        '$customerEmail' => $customerEmail,
                        '$userName'      => $userName,
                        '$sPassword'     => $sPassword,
                    )
                ));
                throw new Exception('There is problem by adding new customer');
            }
            if ($newCustomersId) {
                $this->iCustomerId = $newCustomersId;
            } else {
                throw new Exception('error in adding user');
            }
        } else {
            $this->iCustomerId = $customerId;
        }

        $this->SaveCustomerAddress($aAddresses['Shipping'], '_shipping', $this->iCustomerId);
        $this->SaveCustomerAddress($aAddresses['Billing'], '_billing', $this->iCustomerId);

        return $this->iCustomerId;
    }

    /**
     * Returns user object
     *
     * [
     * {
     * ID: "2",
     * user_login: "pera",
     * user_pass: "$P$BO.83BTiyhQ0cpAx2QZPYDj1J2s1cl1",
     * user_nicename: "pera",
     * user_email: "pera@peric.com",
     * user_url: "",
     * user_registered: "2017-05-25 08:07:36",
     * user_activation_key: "",
     * user_status: "0",
     * display_name: "Pera Peric",
     * nickname: "pera",
     * first_name: "Pera",
     * last_name: "Peric",
     * description: "",
     * rich_editing: "true",
     * comment_shortcuts: "false",
     * admin_color: "fresh",
     * use_ssl: "0",
     * show_admin_bar_front: "true",
     * locale: "",
     * wp_capabilities: "a:1:{s:8:"customer";b:1;}",
     * wp_user_level: "0",
     * session_tokens: "...",
     * last_update: "1495785701",
     * billing_first_name: "Pera",
     * billing_last_name: "Peric",
     * billing_address_1: "Ulica 2",
     * billing_address_2: "24",
     * billing_city: "Belgrade",
     * billing_postcode: "11000",
     * billing_country: "DE",
     * billing_email: "pera@peric.com",
     * billing_phone: "555333",
     * shipping_first_name: "Pera",
     * shipping_last_name: "Peric",
     * shipping_address_1: "Ulica 2",
     * shipping_address_2: "24",
     * shipping_city: "Belgrade",
     * shipping_postcode: "11000",
     * shipping_country: "DE",
     * shipping_method: "a:1:{i:0;s:14:"local_pickup:1";}",
     * paying_customer: "1"
     * }
     *]
     *
     * @param string $sEmail
     *
     * @return object
     */
    protected function getCustomer($sEmail) {
        $user = $this->oPressDB->get_row($this->oPressDB->prepare("SELECT * FROM ".$this->oPressDB->users." WHERE user_email = %s",
            $sEmail), ARRAY_A);
        $userMeta = $this->oPressDB->get_results("SELECT meta_key, meta_value FROM ".$this->oPressDB->usermeta." um WHERE user_id = {$user['ID']}",
            ARRAY_A);
        $newMeta = array();
        foreach ($userMeta as $meta) {
            $newMeta[$meta['meta_key']] = $meta['meta_value'];
        }
        $userData = array_merge($user, $newMeta);
        $userObj = (object)$userData;

        return $userObj;
    }

    /**
     * tries to find customer id regarding current order
     * @return integer
     * @throws Exception if customer not found
     */
    protected function getCustomerId() {
        if ($this->iCustomerId === null) {// tries to get id from existing Order
            if ($this->oCurrentOrder !== null) {
                $this->iCustomerId = $this->oCurrentOrder->get_customer_id();
            }
        }
        if ($this->iCustomerId === null) {
            throw new Exception('Customer not found');
        }

        return $this->iCustomerId;
    }

    /**
     * Implemented this function to add free text fields on each order
     *
     * @param
     * @return
     */
    protected function addFreeTextCustomFields($orderId) {
        if ($orderId) {
            if (is_array(MLSetting::gi()->{'magnalister_shop_order_additional_field'})) {
                foreach (MLSetting::gi()->{'magnalister_shop_order_additional_field'} as $sFieldName) {
                    update_post_meta($orderId, $sFieldName, '');
                }
            }

        } else {
            throw new Exception('order doesn\'t exist in shop');
        }
    }
}
