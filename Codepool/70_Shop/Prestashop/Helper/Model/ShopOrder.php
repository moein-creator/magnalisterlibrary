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

class ML_Prestashop_Helper_Model_ShopOrder {

    protected $oCustomer = null;

    /**
     * @var array $aCurrentData
     */
    protected $aCurrentData = array();

    /**
     * @var OrderCore $oExistingShopOrder
     */
    protected $oExistingShopOrder = null;

    /**
     * @var array $aNewData
     */
    protected $aNewData = array();

    /**
     * @var OrderCore $oCurrentShopOrder
     */
    protected $oCurrentShopOrder = null;

    /**
     * true if we have new product in updating order
     * @var bool
     */
    protected $blNewProduct = false;

    /**
     * if order is updating
     * @var bool
     */
    protected $blUpdate = false;

    /**
     * @var ML_Prestashop_Model_Order $oOrder
     */
    protected $oOrder = null;

    /**
     * @var ML_Prestashop_Model_Price $oPrice
     */
    protected $oPrice = null;

    /**
     * @var ML_Prestashop_Model_Product $oProduct
     */
    protected $oProduct = null;

    /**
     * @var array have total warning messages about the
     */
    protected $aTotalWarningMessage = array();

    /**
     * @var Cart|CartCore
     */
    protected $currentCartObject;

    /** @var array - Advanced Stock Management - Product Ids to update stock */
    protected $syncProductStock = array();

    protected function setMessage($sMessage) {
        $this->aTotalWarningMessage[] = "$sMessage";
    }

    public function __construct() {
        $this->oPrice = MLPrice::factory();
    }

    public function setNewOrderData($aData) {
        $this->aNewData = is_array($aData) ? $aData : array();
        return $this;
    }

    public function setOrder($oOrder) {
        $this->oOrder = $oOrder;
        $this->oExistingShopOrder = null;
        if ($this->oOrder->exists()) {
            $this->oExistingShopOrder = new Order($oOrder->get('current_orders_id'));
        }
        $this->aCurrentData = $oOrder->get('orderdata');
        return $this;
    }

    public function shopOrder() {
        if (!is_array($this->aCurrentData) || count($this->aCurrentData) === 0) {
            return $this->createOrder();
        } else {
            if ($this->checkForUpdate()) {
                $this->aNewData = MLHelper::gi('model_service_orderdata_merge')->mergeServiceOrderData($this->aNewData, $this->aCurrentData, $this->oOrder);
                return $this->update();
            } else {
                $this->aNewData = MLHelper::gi('model_service_orderdata_merge')->mergeServiceOrderData($this->aNewData, $this->aCurrentData, $this->oOrder);
                return $this->createOrder();
            }
        }
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

    public function update() {//products are equal
        $aData = $this->aNewData;
        $oOrder = $this->oCurrentShopOrder = $this->oExistingShopOrder;

        /* prestashop create invoice and create shiping depends on order state , it can be managed by user in Order > States
         */
        $new_state = (int)$aData['Order']['Status'];
        $this->setState($new_state);

        //update shipping address
        $oOrder->id_carrier = $this->getCarrier();
        $oCart = new Cart($oOrder->id_cart);
        $oCart->id_carrier = $oOrder->id_carrier;
        $oCart->update();
        $oOrder->update();

        //set Payment method
        foreach ($aData['Totals'] as $aTotal) {
            if ($aTotal['Type'] == 'Payment') {
                $sPaymentMethod = (!isset($aTotal['Code']) || empty($aTotal['Code'])) ? 'magnalister - '.MLModul::gi()->getMarketPlaceName() : $aTotal['Code'];
                $oOrder->payment = $sPaymentMethod;
                $oOrder->save();

                foreach ($oOrder->getOrderPaymentCollection() as $oPayment) {
                    if (is_object($oPayment)) {
                        /* @var $oPayment OrderPaymentCore */
                        $oPayment->payment_method = $sPaymentMethod;
                        $oPayment->update();
                    }

                }

                break;
            }
        }

        return $aData;
    }

    protected function checkForUpdate() {
        if (count($this->aNewData['Products']) > 0) {
            $this->blNewProduct = true;
            return false;
        } else {
            $this->blNewProduct = false;
        }
        foreach (array_keys($this->aNewData['AddressSets']) as $sAddressType) {
            foreach (array('Gender', 'Firstname', 'Company', 'StreetAddress', 'Postcode', 'City', 'Suburb', 'CountryCode', 'Phone', 'EMail', 'DayOfBirth',) as $sField) {
                if (
                    (
                        isset($this->aNewData['AddressSets'][$sAddressType][$sField]) && !isset($this->aCurrentData['AddressSets'][$sAddressType][$sField])
                    ) ||
                    (
                        isset($this->aNewData['AddressSets'][$sAddressType][$sField]) && $this->aNewData['AddressSets'][$sAddressType][$sField] != $this->aCurrentData['AddressSets'][$sAddressType][$sField]
                    )
                ) {
                    return false;
                }
            }
        }
        foreach ($this->aNewData['Totals'] as $aNewTotal) {
            $blFound = false;
            foreach ($this->aCurrentData['Totals'] as $aCurrentTotal) {
                if ($aNewTotal['Type'] == $aCurrentTotal['Type']) {
                    $blFound = true;
                    if (
                        (float)$aNewTotal['Value'] != 0
                        && (
                            (float)$aCurrentTotal['Value'] != (float)$aNewTotal['Value']
                        )
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

    protected function addCustomerToOrder(&$aInfo) {
        // Getting the customer
        $iCustomerId = (int)Db::getInstance()->getValue('SELECT `id_customer` FROM `'._DB_PREFIX_.'customer` WHERE `active` = 1 AND `email` = \''.pSQL($aInfo['EMailIdent']).'\' AND `deleted` = 0'.(substr(_PS_VERSION_, 0, 3) == '1.3' ? '' : ' AND `is_guest` = 0'));
        $oCustomer = null;
        // Add customer if he doesn't exist
        if ($iCustomerId < 1) {
            $sPassword = rand();
            $aInfo['Password'] = $sPassword;//important to send password in Promotion Mail
            $oCustomer = new Customer();
            $oCustomer->id_gender = ($aInfo['Gender'] == 'male' ? 1 : ($aInfo['Gender'] == 'female' ? 2 : 0));
            $oCustomer->id_default_group = 1;
            $oCustomer->secure_key = md5(uniqid(rand(), true));
            $oCustomer->email = $aInfo['EMail'];
            $oCustomer->passwd = md5(pSQL(_COOKIE_KEY_.$sPassword));
            $oCustomer->last_passwd_gen = date('Y-m-d H:i:s');
            $oCustomer->newsletter = 0;
            $oCustomer->optin = 0;
            $oCustomer->lastname = $this->validate($aInfo['Lastname'], 'customerName', 32, 'Customer lastname');
            $oCustomer->firstname = $this->validate($aInfo['Firstname'], 'customerName', 32, 'Customer firstname');

            $oCustomer->active = 1;
            if (!$oCustomer->add()) {
                MLMessage::gi()->addDebug("There is an error in adding cutomer<pre>".print_r($oCustomer, true)."</pre>");
            } else {
                $iCustomerId = $oCustomer->id;
                $sConfigCustomerGroup = MLModul::gi()->getConfig('CustomerGroup');
                if ($sConfigCustomerGroup === null) {
                    $sConfigCustomerGroup = MLModul::gi()->getConfig('customergroup');
                }
                $oCustomer->updateGroup(array($sConfigCustomerGroup));
            }
        } else {
            $oCustomer = new Customer($iCustomerId);
            if ($oCustomer != null) {
                $oCustomer->lastname = $this->validate($aInfo['Lastname'], 'customerName', 32, 'Customer lastname');
                $oCustomer->firstname = $this->validate($aInfo['Firstname'], 'customerName', 32, 'Customer firstname');
                $oCustomer->email = $aInfo['EMail'];
                $oCustomer->update();
            } else {
                MLMessage::gi()->addDebug('There is an error in searching customer');
            }
        }
        $this->oCustomer = $oCustomer;
        return $iCustomerId;
    }

    protected function addAddressToOrder($aAddress, $iCustomerId) {
        if (!isset($iCustomerId)) {
            MLMessage::gi()->addDebug("empty customer");
        }
        $sAlias = MLModul::gi()->getMarketPlaceName(false).'_'.$aAddress['type'];
        if ($aAddress['type'] === 'Main') { // always find and update main address of customer if it exists
            $iAddressId = (int)Db::getInstance()->getValue('SELECT `id_address` FROM `'._DB_PREFIX_.'address` WHERE `id_customer` = '.(int)$iCustomerId.' AND `alias` = \''.pSQL($sAlias).'\'');
        } else if (is_object($this->oExistingShopOrder)) {// find and update shipping and billing address only for specific order
            $iAddressId = (int)Db::getInstance()->getValue('SELECT `id_address` FROM `'._DB_PREFIX_.'address` WHERE `id_customer` = '.(int)$iCustomerId.' AND `alias` = \''.pSQL($sAlias).'\' AND `id_address` IN ('.(int)$this->oExistingShopOrder->id_address_invoice.', '.(int)$this->oExistingShopOrder->id_address_delivery.')');
        } else {
            $iAddressId = 0;
        }

        if ($iAddressId > 0) {
            $oAddress = new Address((int)$iAddressId);
        } else {
            $oAddress = new Address();
            $oAddress->id_customer = $iCustomerId;
        }
        // prestashop doesn't accept '/' in phone numbers
        $aAddress['Phone'] = str_replace('/', '-', $aAddress['Phone']);
        $oAddress->phone = $this->validate($aAddress['Phone'], 'phone', 16, 'Phonenumber');
        $oAddress->id_country = $this->validate($aAddress['CountryCode'], 'isocode', 3, 'Country Iso Code');

        // Check Suburb in shop if exists then set state if not add it to address2 field
        if (!empty($aAddress['Suburb'])) {
            $iState = State::getIdByIso($aAddress['Suburb'], $oAddress->id_country);
            $iState = empty($iState) ? State::getIdByName($aAddress['Suburb']) : $iState;
            if (!empty($iState)) {
                $oAddress->id_state = $iState;
                $aAddress['Suburb'] = '';
            }
        }

        $sAddressField2 = '';

        // Process AddressAddition field if provided from API and is not empty
        if (array_key_exists('AddressAddition', $aAddress) && !empty($aAddress['AddressAddition'])) {
            $sAddressField2 = $aAddress['AddressAddition'];
        }

        /**
         * Add suburb see above to address2 field
         * Check if phone number is valid (by prestashop validator), if not then add it to address2 field
         */
        $sAddressField2 .= (trim($aAddress['Suburb']) != '' ? 'Region: '.$aAddress['Suburb'] : '').($oAddress->phone == '0100000000' ? (trim($aAddress['Phone']) != '' ? ' - Phone number: '.$aAddress['Phone'] : '') : '');

        $oAddress->address2 = $this->validate($sAddressField2, 'address', 128, '', false);
        $oAddress->alias = $sAlias;
        $oAddress->lastname = $this->validate($aAddress['Lastname'], 'customerName', 32, 'Customer lastname');
        $oAddress->firstname = $this->validate($aAddress['Firstname'], 'customerName', 32, 'Customer firstname');
        $oAddress->company = $this->validate($aAddress['Company'], 'genericname', 64, 'Customer Company', false);
        $oAddress->address1 = $this->validate($aAddress['StreetAddress'], 'address', 128, 'Address');
        $oAddress->postcode = $this->validate($aAddress['Postcode'], 'postcode', 12, 'Postcode');
        $oAddress->city = $this->validate($aAddress['City'], 'cityname', 64, 'City Name');
        $oAddress->vat_number = !empty($aAddress['UstId']) ? $aAddress['UstId'] : '';
        $oAddress->active = 1;
        $this->addAddressExtraData($oAddress);
        if (empty($aAddress['UstId'])) {
            if (method_exists('Country', 'isNeedDniByCountryId') && Country::isNeedDniByCountryId($oAddress->id_country)) {
                $oAddress->dni = '0000';
            }
        } else {
            $oAddress->dni = $aAddress['UstId'];
        }
        if ($iAddressId > 0 && Validate::isLoadedObject($oAddress)) {
            try {
                $oAddress->date_upd = date('Y-m-d H:i:s');
                $oAddress->update();
            } catch (Exception $ex) {
                $oAddress->phone_mobile = $oAddress->phone;//normal prestashop needs only one of phone or phone_mobile, but one customer prestashop needed to fill both of them, so we fill both if both are required
                $oAddress->update();
            }
        } else {
            try {
                $oAddress->date_add = date('Y-m-d H:i:s');
                $oAddress->add();
            } catch (Exception $ex) {
                $oAddress->phone_mobile = $oAddress->phone;//normal prestashop needs only one of phone or phone_mobile, but one customer prestashop needed to fill both of them, so we fill both if both are required
                $oAddress->add();
            }
        }
        return $oAddress->id;
    }

    public function createOrder() {
        $aData = $this->aNewData;
        $this->currentCartObject = null;

        if (is_object($this->oExistingShopOrder)) {
            $blUpdate = $this->blUpdate = true;
        } else {
            $blUpdate = $this->blUpdate = false;
        }
        $iLangId = MLModul::gi()->getConfig('lang');
        Context::getContext()->language = new Language($iLangId);
        $iShopId = MLModul::gi()->getConfig('orderimport.shop');
        if ($iShopId !== null) {
            Context::getContext()->shop = new Shop($iShopId);
        }
        $iCurrencyId = Currency::getIdByIsoCode($aData['Order']['Currency'], Context::getContext()->shop->id);
        if (!$iCurrencyId) {
            $sMessage = MLI18n::gi()->get('Orderimport_CurrencyCodeDontExistsError', array(
                    'mpOrderId' => MLSetting::gi()->get('sCurrentOrderImportMarketplaceOrderId'),
                    'ISO' => $aData['Order']['Currency']
                )
            );
            MLErrorLog::gi()->addError(0, ' ', $sMessage, array('MOrderID' => MLSetting::gi()->get('sCurrentOrderImportMarketplaceOrderId')));
            throw new Exception($sMessage);
        }
        Context::getContext()->currency = $oCurrency = new Currency($iCurrencyId, null, Context::getContext()->shop->id);
        $fTotalTaxExcl = $fTotalTaxIncl = $fShipingTaxExcl = $fShipingTaxIncl = $fTotal_products = $fTotal_products_wt = 0.00;
        $aAddresses = $aData['AddressSets'];
        if (!Validate::isEmail($aAddresses['Main']['EMail'])) {
            throw new Exception('Email Address is invalid , order cannot be imported');
        }
        $iCustomerId = $this->addCustomerToOrder($aData['AddressSets']['Main']);
        $aAddresses['Main']['type'] = 'Main';
        $iAddressId = $this->addAddressToOrder($aAddresses['Main'], $iCustomerId);
        $aAddresses['Billing']['type'] = 'Billing';
        $iInvoiceAddressId = $this->addAddressToOrder($aAddresses['Billing'], $iCustomerId);
        $aAddresses['Shipping']['type'] = 'Shipping';
        $iDeliveryAddressId = $this->addAddressToOrder($aAddresses['Shipping'], $iCustomerId);
        if (count($aData['Products']) > 0) {
            $reference = Order::generateReference();
            $this->oCurrentShopOrder = $oOrder = $blUpdate ? $this->oExistingShopOrder : new Order();
            $oOrder->round_mode = Configuration::get('PS_PRICE_ROUND_MODE');
            /* @var $oOrder OrderCore */
            if (Configuration::get('PS_TAX_ADDRESS_TYPE') === 'id_address_delivery') {
                $address = new Address($iAddressId);
                Context::getContext()->country = new Country($address->id_country, Context::getContext()->language->id);
            }

            $sTotalPayment = $this->getPaymentMethod();
            //create one secure key
            $sSecureKey = md5(time());
            //add new cart
            $oCart = $blUpdate ? new Cart($oOrder->id_cart) : new Cart();
            /* @var $oCart CartCore */
            $iCarrierId = $this->getCarrier();
            $oCart->id_carrier = (((int)$iCarrierId) < 0) ? 0 : $iCarrierId;
            if (!$blUpdate) {
                $oCart->id_shop = Context::getContext()->shop->id;
                $oCart->id_shop_group = Context::getContext()->shop->id_shop_group;
                $oCart->delivery_option = '[]';
                $oCart->id_lang = $iLangId;
                $oCart->id_currency = $iCurrencyId;
                $oCart->secure_key = $sSecureKey;
                $oCart->recyclable = 0;
                $oCart->gift = 0;
                $oCart->gift_message = '';
                $oCart->id_customer = (int)$this->oCustomer->id;
                $oCart->id_guest = 0;
                $oCart->allow_seperated_package = 0;
            }
            $oCart->id_address_delivery = (int)$iDeliveryAddressId;
            $oCart->id_address_invoice = (int)$iInvoiceAddressId;

            //ToDo: Carts needs to be filled with products - otherwise customer address of order could not be changed
            $this->currentCartObject = &$oCart;

            $blUpdate ? $oCart->update() : $oCart->add();

            $oOrder->id_carrier = $oCart->id_carrier;
            if (!$blUpdate) {
                $oOrder->id_customer = (int)$this->oCustomer->id;
                $oOrder->id_currency = $iCurrencyId;
                $oOrder->id_lang = $iLangId;
                $oOrder->id_cart = $oCart->id;
                $oOrder->reference = $reference;
                $oOrder->id_shop = (int)Context::getContext()->shop->id;
                $oOrder->id_shop_group = (int)Context::getContext()->shop->id_shop_group;
                $oOrder->secure_key = $sSecureKey;
                $oOrder->module = "magnalister";
                $oOrder->recyclable = 0;
                $oOrder->gift = 0;
                $oOrder->gift_message = '';
                $oOrder->mobile_theme = 0;
                $oOrder->conversion_rate = $oCurrency->conversion_rate;
                $oOrder->invoice_date = '0000-00-00 00:00:00';
                $oOrder->delivery_date = '0000-00-00 00:00:00';
                $oOrder->total_paid_real = $fTotalTaxIncl;
                $oOrder->total_products = $fTotal_products;
                $oOrder->total_products_wt = $fTotal_products_wt;
                $oOrder->total_shipping_tax_excl = $fShipingTaxExcl;
                $oOrder->total_shipping_tax_incl = $fShipingTaxIncl;
                $oOrder->total_shipping = $oOrder->total_shipping_tax_incl;
                $oOrder->total_paid_tax_excl = $fTotalTaxExcl;
                $oOrder->total_paid_tax_incl = $fTotalTaxIncl;
                $oOrder->total_paid = $oOrder->total_paid_tax_incl;
            }
            $oOrder->id_address_invoice = (int)$iInvoiceAddressId;
            $oOrder->id_address_delivery = (int)$iDeliveryAddressId;
            $oOrder->payment = $sTotalPayment;

            if ($blUpdate) {
                $blResult = $oOrder->update();
            } else {
                $blResult = $oOrder->add();
            }
            if (!$blResult || empty($oOrder->id)) {
                throw new Exception('there is problem in insert prestashop order , order id is empty. '.Db::getInstance()->getMsgError());
            }

            $oOrderStatus = new OrderState((int)$aData['Order']['Status'], $iLangId);
            if (!Validate::isLoadedObject($oOrderStatus)) {
                throw new Exception('Can\'t load Order state status');
            }
            if ($this->blUpdate) {
                $aPSOrderDetails = array();
                if (is_object($this->oCurrentShopOrder)) {
                    foreach ($this->oCurrentShopOrder->getOrderDetailList() as $oPSProduct) {
                        $aPSOrderDetails[$oPSProduct['id_order_detail']] = $oPSProduct;
                    }
                }
            }

            foreach ($aData['Products'] as $aProduct) {
                $blAdd = true;
                if ($this->blUpdate) {
                    if ($this->blNewProduct) {
                        $blAdd = !$this->isProductImported($aPSOrderDetails, $aProduct);
                    } else {
                        $blAdd = false;
                    }
                }
                if ($blAdd) {
                    $this->addProductToOrder($aProduct, $oOrderStatus);
                }
            }
            $fMaxTaxRate = $this->getMaxTaxRateFromProducts();
            $aReduction = array();
            foreach ($aData['Totals'] as &$aTotal) {
                switch ($aTotal['Type']) {
                    case 'Shipping':
                    {
                        $fTotalShippingValue = (float)$aTotal['Value'];
                        $fTotalShippingValue = $fTotalShippingValue < 0 ? 0 : $fTotalShippingValue;
                        $fShipingTaxIncl += $fTotalShippingValue;
                        $fTotalTaxIncl += $fTotalShippingValue;
                        $aTotal['Tax'] = $fMaxTaxRate;
                        $fShippingPriceWithoutTax = $this->oPrice->calcPercentages($fShipingTaxIncl, null, $aTotal['Tax']);
                        $oOrder->carrier_tax_rate = $aTotal['Tax'];
                        $fShipingTaxExcl += $fShippingPriceWithoutTax;
                        $fTotalTaxExcl += $fShippingPriceWithoutTax;
                        break;
                    }
                    case 'Payment':
                    {
                        if ((float)$aTotal['Value'] > 0) {
                            // If the value is positive we create a product
                            $aTotal['Tax'] = $fMaxTaxRate;
                            $this->addTotalProductToOrder(array(
                                'ItemTitle' => (isset($aTotal['Code']) && $aTotal['Code'] != '') ? $aTotal['Code'] : $aTotal['Type'],
                                'Price' => $aTotal['Value'],
                                'Tax' => $aTotal['Tax'],
                                'Data' => isset($aTotal['Data']) ? $aTotal['Data'] : array(),
                                'Quantity' => 1,
                            ), $oOrderStatus);
                        } elseif ((float)$aTotal['Value'] < 0) {
                            // If the value is negative, we have to create it as a voucher
                            $aReduction[] = $aTotal;
                        }
                        break;
                    }
                    default:
                    {
                        if ((float)$aTotal['Value'] > 0) {
                            // If the value is positive we create a product
                            $aTotal['Tax'] = $fMaxTaxRate;
                            $this->addTotalProductToOrder(array(
                                'ItemTitle' => (isset($aTotal['Code']) && $aTotal['Code'] != '') ? $aTotal['Code'] : $aTotal['Type'],
                                'SKU' => isset($aTotal['SKU']) ? $aTotal['SKU'] : '',
                                'Price' => $aTotal['Value'],
                                'Tax' => $fMaxTaxRate,
                                'Data' => isset($aTotal['Data']) ? $aTotal['Data'] : array(),
                                'Quantity' => 1,
                            ), $oOrderStatus);
                        } elseif ((float)$aTotal['Value'] < 0) {
                            // If the value is negative, we have to create it as a voucher
                            $aReduction[] = $aTotal;
                        }
                        break;
                    }
                }
            }

            // calculate total product price
            foreach ($oOrder->getOrderDetailList() as $aOrderDetail) {
                $oOrderDetail = new OrderDetail($aOrderDetail['id_order_detail']);
                if (Configuration::get('PS_STOCK_MANAGEMENT') && $oOrderDetail->getStockState()) {
                    $oOrderStatus = new OrderState((int)Configuration::get('PS_OS_OUTOFSTOCK'), $iLangId);;
                }
                $fTotalTaxIncl += (float)$aOrderDetail['total_price_tax_incl'];
                $fTotal_products_wt += (float)$aOrderDetail['total_price_tax_incl'];
                $fTotalTaxExcl += (float)$aOrderDetail['total_price_tax_excl'];
                $fTotal_products += (float)$aOrderDetail['total_price_tax_excl'];
            }

            // reduction and add voucher
            foreach ($aReduction as $aTotal) {
                $iDiscount = (float)abs($aTotal['Value']);
                $iDiscountTaxExcl = round($this->oPrice->calcPercentages($iDiscount, null, $fMaxTaxRate), 5);
                $oOrder->total_discounts += $iDiscount;
                $oOrder->total_discounts_tax_incl += $iDiscount;
                $oOrder->total_discounts_tax_excl += $iDiscountTaxExcl;
                $fTotalTaxIncl -= $iDiscount;
                $fTotalTaxExcl -= $iDiscountTaxExcl;
                $sVoucherName = (isset($aTotal['Type']) ? $aTotal['Type'] : '').(isset($aTotal['Code']) ? ' '.$aTotal['Code'] : '');
                $this->addVoucher($sVoucherName, $iDiscount);
            }
            $fTotalTaxIncl = round($fTotalTaxIncl, 5);
            $sTrackingId = MLModul::gi()->getMarketPlaceName().time().rand().'';
            if ($blUpdate) {
                foreach ($oOrder->getOrderPayments() as $oPayment) {
                    if (is_object($oPayment)) {
                        /* @var $oPayment OrderPaymentCore */
                        $oPayment->delete();
                    }
                }
                foreach ($oOrder->getInvoicesCollection() as $oInvoice) {
                    if (is_object($oInvoice)) {
                        $oInvoice->total_paid_tax_excl = round($fTotalTaxExcl, 5);
                        $oInvoice->total_paid_tax_incl = $fTotalTaxIncl;
                        $oInvoice->total_products = round($fTotal_products, 5);
                        $oInvoice->total_products_wt = round($fTotal_products_wt, 5);
                        $oInvoice->total_shipping_tax_excl = round($fShipingTaxExcl, 5);
                        $oInvoice->total_shipping_tax_incl = round($fShipingTaxIncl, 5);
                        $oInvoice->update();

                        if (!$oOrder->addOrderPayment($fTotalTaxIncl, null, $sTrackingId, null, null, $oInvoice)) {
                            throw new Exception('Cannot save Order Payment');
                        }
                        break;
                    }
                }
            } else if ($oOrderStatus->logable) {
                if (!$oOrder->addOrderPayment($fTotalTaxIncl, null, $sTrackingId)) {
                    throw new Exception('Cannot save Order Payment');
                }
            }

            $oOrder->total_paid_real = $fTotalTaxIncl;
            $oOrder->total_products = round($fTotal_products, 5);
            $oOrder->total_products_wt = round($fTotal_products_wt, 5);
            $oOrder->total_shipping_tax_excl = round($fShipingTaxExcl, 5);
            $oOrder->total_shipping_tax_incl = round($fShipingTaxIncl, 5);
            $oOrder->total_shipping = $oOrder->total_shipping_tax_incl;
            $oOrder->total_paid_tax_excl = round($fTotalTaxExcl, 5);
            $oOrder->total_paid_tax_incl = $fTotalTaxIncl;
            $oOrder->total_paid = $oOrder->total_paid_tax_incl;
            if (!$oOrder->update()) {
                throw new Exception("Cannot add Order");
            }
            $this->createCarrier();
            $this->setState($oOrderStatus->id);
            $oOrder->current_state = $oOrderStatus->id;
            if ($this->oExistingShopOrder !== null) {
                $this->oOrder->set('current_orders_id', $oOrder->id); //important
            } else {
                $this->oOrder->set('orders_id', $oOrder->id);
                $this->oOrder->set('current_orders_id', $oOrder->id); //important
            }
            $oOrder->date_add = $aData['Order']['DatePurchased'];
            if (!$oOrder->update()) {
                throw new Exception("Cannot add Order");
            }

            if (!empty($oOrder->id) && !empty($aData['MPSpecific']['InternalComment'])) {
                $this->addMessageToOrder($aData['MPSpecific']['InternalComment']);
            }
            if (!empty($oOrder->id) && !empty($aData['MPSpecific']['InternalComment'])) {
                $this->addMessageToOrder($aData['MPSpecific']['InternalComment']);
                if (MLModul::gi()->getConfig('order.information')) {
                    //add message to invoice
                    foreach ($oOrder->getInvoicesCollection() as $oInvoice) {
                        /* @var $oInvoice OrderInvoiceCore */
                        $oInvoice->note = $aData['MPSpecific']['InternalComment'];
                        $oInvoice->update();
                    }
                }
            }

            /*
             * Some third party plugin could change inserted shipping value from order object
             * Here as correction we insert shipping cost directly into database
             */
            $oCheckOrder = new Order($oOrder->id);
            if((float)($oCheckOrder->total_shipping_tax_incl) !== round($fShipingTaxIncl, 5)){
                MLDatabase::getDbInstance()->update(_DB_PREFIX_ .'orders', array(
                    'total_shipping_tax_incl' => round($fShipingTaxIncl, 5),
                    'total_shipping_tax_excl' => round($fShipingTaxExcl, 5)
                )
                    ,array(
                        'id_order' => $oOrder->id
                    )
                );
            }
            if (count($this->aTotalWarningMessage) > 0) {
                foreach ($this->aTotalWarningMessage as $sMessage) {
                    if (!empty($oOrder->id)) {
                        $this->addMessageToOrder($sMessage);
                    } else {
                        MLLog::gi()->add(MLSetting::gi()->get('sCurrentOrderImportLogFileName'), array(
                            'MOrderId' => MLSetting::gi()->get('sCurrentOrderImportMarketplaceOrderId'),
                            'PHP' => get_class($this).'::'.__METHOD__.'('.__LINE__.')',
                            'WarningMessage' => $sMessage
                        ));
                    }
                }
            }
        }

        // Prestashop - Advanced Stock Management - Update product quantities after order is created (per found product)
        foreach ($this->syncProductStock as $productId) {
            if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')) {
                // if the available quantities depends on the physical stock
                if (StockAvailable::dependsOnStock($productId)) {
                    // synchronizes
                    StockAvailable::synchronize($productId, Context::getContext()->shop->id);
                }
            }
        }

        unset($oOrder);
        unset($oCart);
        unset($oOrderStatus);
        return $aData;
    }

    /**
     * return a proper paymentmethod, at them moment it create just one fake payment method name
     * @return string
     * @todo payment method should be configurable like shopware
     */
    protected function getPaymentMethod() {
        //get Payment method
        $sTotalPayment = '';
        foreach ($this->aNewData['Totals'] as $aTotal) {
            if ($aTotal['Type'] == 'Payment') {
                $sTotalPayment = (!isset($aTotal['Code']) || empty($aTotal['Code'])) ? 'magnalister - '.MLModul::gi()->getMarketPlaceName() : $aTotal['Code'];
                break;
            }
        }

        if (empty($sTotalPayment)) {//some marketplace don't send any information about payment
            $sTotalPayment = 'magnalister - '.MLModul::gi()->getMarketPlaceName();
        }

        return $sTotalPayment;
    }

    /**
     * create a carrier object for current order
     */
    protected function createCarrier() {
        $oOrder = $this->oCurrentShopOrder;
        $oCarrier = new Carrier($oOrder->id_carrier);
        if ($oCarrier->id > 0) {
            $idOrderCarrier = Db::getInstance()->getValue('
                SELECT `id_order_carrier`
                FROM `'._DB_PREFIX_.'order_carrier`
                WHERE `id_order` = '.(int)$oOrder->id.'
            ');

            if ($idOrderCarrier) {
                $oOrderCarrier = new OrderCarrier($idOrderCarrier);
            } else {
                $oOrderCarrier = new OrderCarrier();
                $oOrderCarrier->id_order = (int)$oOrder->id;
            }
            $oOrderCarrier->id_carrier = $oCarrier->id;
            $oOrderCarrier->weight = (float)$oOrder->getTotalWeight();
            $oOrderCarrier->shipping_cost_tax_excl = (float)$oOrder->total_shipping_tax_excl;
            $oOrderCarrier->shipping_cost_tax_incl = (float)$oOrder->total_shipping_tax_incl;
            if ($idOrderCarrier) {
                $oOrderCarrier->update();
            } else {
                $oOrderCarrier->add();
            }
        }
    }

    /**
     * add a Voucher to current order (reduction or coupon)
     * @param string $sName
     * @param float $fValue
     */
    protected function addVoucher($sName, $fValue) {
        $oOrder = $this->oCurrentShopOrder;
        //check if vouncher exist, just for update order
        $aRules = $oOrder->getCartRules();
        if (is_array($aRules)) {
            foreach ($aRules as $aRule) {
                if ((float)$aRule['value'] > 0 && $aRule['name'] == $sName) {
                    return;
                }
            }
        }
        $oCartRule = new CartRule();
        $oCartRule->date_from = date('Y-m-d H:i:s', strtotime('-1 hour', strtotime($oOrder->date_add)));
        $oCartRule->date_to = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $oCartRule->name[Configuration::get('PS_LANG_DEFAULT')] = $sName;
        $oCartRule->name[Context::getContext()->language->id] = $sName;
        $oCartRule->quantity = 0;
        $oCartRule->quantity_per_user = 1;
        $oCartRule->reduction_amount = $fValue;
        $oCartRule->active = 0;
        if ($oCartRule->add()) {
            $oOrderCartRule = new OrderCartRule();
            $oOrderCartRule->id_order = $oOrder->id;
            $oOrderCartRule->id_cart_rule = $oCartRule->id;
            $oOrderCartRule->id_order_invoice = 0;
            $oOrderCartRule->name = $sName;
            $oOrderCartRule->value = $fValue;
            $oOrderCartRule->value_tax_excl = $fValue;
            $oOrderCartRule->add();
        }
    }

    /**
     * add a message to current order
     * @param string $sMessage
     */
    protected function addMessageToOrder($sMessage) {
        if (Db::getInstance()->getValue("SELECT COUNT(*) FROM `"._DB_PREFIX_."message` WHERE message LIKE '".pSQL($sMessage)."'") == 0) {
            $oMsg = new Message();
            $oMsg->message = $sMessage;
            $oMsg->id_order = (int)$this->oCurrentShopOrder->id;
            $oMsg->private = 1;
            $oMsg->add();
        }
    }

    /**
     * update posotive total when they are added as order detail
     * @param array $aProduct
     * @param OrderState $oOrderStatus
     * @param int $iCurrentQty
     * @return float price tax excluded
     */
    protected function addTotalProductToOrder($aProduct, OrderState $oOrderStatus) {
        $fTaxPercent = $aProduct['Tax'];
        $fGros = (float)$aProduct['Price'];
        foreach ($this->oCurrentShopOrder->getOrderDetailList() as $aOrderDetail) {
            if ($this->blUpdate) {
                if (isset($aProduct['SKU'])) { // other total
                    $blUpdateDetail = $aOrderDetail['product_name'] == $aProduct['ItemTitle'] && $aOrderDetail['product_reference'] == $aProduct['SKU'];
                } else { // payment
                    $blUpdateDetail = $aOrderDetail['product_reference'] == '' && $aOrderDetail['product_name'] == $aProduct['ItemTitle'];
                }

                if ($blUpdateDetail) {
                    if ($fGros > $aOrderDetail['total_price_tax_incl']) {
                        $oOrderDetail = new OrderDetail($aOrderDetail['id_order_detail']);
                        $fNet = round($this->oPrice->calcPercentages($fGros, null, $fTaxPercent), 5);
                        $oOrderDetail->original_product_price = $fNet;
                        $oOrderDetail->product_price = $fNet;
                        $oOrderDetail->unit_price_tax_incl = $fGros;
                        $oOrderDetail->unit_price_tax_excl = $fNet;
                        $oOrderDetail->total_price_tax_incl = $fGros * $aProduct['Quantity'];
                        $oOrderDetail->total_price_tax_excl = $fNet * $aProduct['Quantity'];
                        $oOrderDetail->update();
                    }
                    return $this;
                }
            }
        }
        return $this->addProductToOrder($aProduct, $oOrderStatus);
    }

    /**
     * add product to current order detail
     * @param array $aProduct
     * @param OrderState $oOrderStatus object of status of order
     * @param int $iCurrentQty quantity that should be reduced from shop product
     * @param bool $blUpdate if we are updating order
     * @return float tax excluded price
     */
    protected function addProductToOrder(&$aProduct, OrderState $oOrderStatus) {
        $oOrder = $this->oCurrentShopOrder;
        $oOrderDetail = new OrderDetail();
        /* @var $oOrderDetail  OrderdetailCore */
        $oOrderDetail->id_order = $oOrder->id;
        $oOrderDetail->download_deadline = '0000-00-00 00:00:00';
        $oOrderDetail->download_hash = null;
        $oOrderDetail->product_name = $this->validate($aProduct['ItemTitle'], 'genericname', 256, "Product name");
        $fTaxPercent = $aProduct['Tax'];
        /** @var ML_Prestashop_Model_Product $oProduct */
        $oProduct = MLProduct::factory();

        if (
            isset($aProduct['SKU']) &&
            $oProduct->getByMarketplaceSKU($aProduct['SKU'])->exists()
        ) {
            $oOrderDetail->product_id = (int)($oProduct->getId());
            $oOrderDetail->product_attribute_id = $oProduct->getAttributeId();
            $oOrderDetail->product_ean13 = $oProduct->ean13;
            $oOrderDetail->product_upc = $oProduct->upc;
            $oOrderDetail->product_reference = $oProduct->reference;
            $oOrderDetail->product_supplier_reference = $oProduct->supplier_reference;
            $oOrderDetail->product_weight = $oProduct->weight;

            if ($oOrderStatus->logable) {
                ProductSale::addProductSale((int)$oOrderDetail->product_id, (int)$oOrderDetail->product_attribute_id);
            }

            // Add some informations for virtual products
            if (($id_product_download = ProductDownload::getIdFromIdProduct((int)$oProduct->getId())) !== false) {
                $productDownload = new ProductDownload((int)($id_product_download));
                $oOrderDetail->download_deadline = $productDownload->getDeadLine();
                $oOrderDetail->download_hash = $productDownload->getHash();
                unset($productDownload);
            }

            $oOrderDetail->ecotax = 0.000000;
            $oOrderDetail->tax_computation_method = 0;
            $oOrderDetail->ecotax_tax_rate = 0;
            if (
                $fTaxPercent === false
                || !array_key_exists('ForceMPTax', $aProduct)
                || !$aProduct['ForceMPTax']
            ) {
                $mProductTax = $oProduct->getTax($this->aNewData['AddressSets']);
                $fTaxPercent = is_float($mProductTax) ? $mProductTax : $fTaxPercent;
            }

            $oOrderDetail->id_warehouse = $oProduct->getWarehouse();
            if (is_object($this->currentCartObject) && MLDatabase::getDbInstance()->tableExists(_DB_PREFIX_.'cart_product')) {
                // Add cart Product for Prestashop 1.7.6+
                try {
                    Db::getInstance()->insert('cart_product', array(
                        'id_product' => (int)$oProduct->getId(),
                        'id_product_attribute' => (int)$oProduct->getAttributeId(),
                        'id_cart' => (int)$this->currentCartObject->id,
                        'id_address_delivery' => (int)$this->currentCartObject->id_address_delivery,
                        'id_shop' => (int)Context::getContext()->shop->id,
                        //'id_customization' => 0, // leave blank we dont use this at the moment
                        'quantity' => (int)$aProduct['Quantity'],
                        'date_add' => date('Y-m-d H:i:s'),
                    ));
                } catch (PrestaShopDatabaseException $ex) {
                    // do nothing
                }
            }
        } else {
            $oOrderDetail->id_warehouse = 0;
            if (isset($aProduct['SKU'])) {
                $oOrderDetail->product_reference = $aProduct['SKU'];
            }
        }
        $oTax = $this->getTax($fTaxPercent);
        $oOrderDetail->tax_rate = $fTaxPercent;
        $oOrderDetail->tax_name = $oTax->name[Context::getContext()->language->id];
        $oOrderDetail->id_shop = Context::getContext()->shop->id;
        $oOrderDetail->product_quantity = (int)$aProduct['Quantity'];
        //price
        $fGros = (float)$aProduct['Price'];
        $fNet = round($this->oPrice->calcPercentages($fGros, null, $fTaxPercent), 5);

        $oOrderDetail->id = null;
        $oOrderDetail->total_shipping_price_tax_incl = 0.00000;
        $oOrderDetail->total_shipping_price_tax_excl = 0.00000;
        $oOrderDetail->original_product_price = $fNet;
        $oOrderDetail->product_price = $fNet;
        $oOrderDetail->unit_price_tax_incl = $fGros;
        $oOrderDetail->unit_price_tax_excl = $fNet;
        $oOrderDetail->total_price_tax_incl = $fGros * $aProduct['Quantity'];
        $oOrderDetail->total_price_tax_excl = $fNet * $aProduct['Quantity'];
        $oOrderDetail->purchase_supplier_price = 0.00;
        $oOrderDetail->group_reduction = 0.00;
        $oOrderDetail->product_quantity_discount = 0.00;
        $oOrderDetail->discount_quantity_applied = 0;
        $oOrderDetail->id_order_invoice = 0;
        if ($this->blUpdate) {
            foreach ($oOrder->getInvoicesCollection() as $oInvoice) {
                if (is_object($oInvoice)) {
                    $oOrderDetail->id_order_invoice = $oInvoice->id;
                    break;
                }
            }
        }
        // Add new entry to the table
        if ($oOrderDetail->add()) {
            $fUnitTax = $fGros - $fNet;
            $fTotalTax = $fUnitTax * $aProduct['Quantity'];
            $sql = 'INSERT INTO `'._DB_PREFIX_."order_detail_tax` (id_order_detail, id_tax, unit_amount, total_amount)
                            VALUES ({$oOrderDetail->id},'{$oTax->id}',{$fUnitTax},{$fTotalTax})";

            Db::getInstance()->execute($sql);
        }
        if (
            isset($aProduct['SKU']) &&
            $oProduct->getByMarketplaceSKU($aProduct['SKU'])->exists() &&
            isset($aProduct['StockSync']) && $aProduct['StockSync']
        ) {
            MLLog::gi()->add(MLSetting::gi()->get('sCurrentOrderImportLogFileName'), array(
                'MOrderId' => MLSetting::gi()->get('sCurrentOrderImportMarketplaceOrderId'),
                'PHP' => get_class($this).'::'.__METHOD__.'('.__LINE__.')',
                'StockReduction' => array(
                    'SKU' => $aProduct['SKU'],
                    'Reduced quantity' => $aProduct['Quantity'],
                    'Old quantity' => $oProduct->getStock(),
                    'New quantity' => $oProduct->getStock() - (float)$aProduct['Quantity'],
                )
            ));
            $oProduct->setStock($oProduct->getStock() - (float)$aProduct['Quantity']);
            $this->syncProductStock[] = $oProduct->getId();
        }
        unset($oOrderDetail);
        return $this;
    }

    /**
     * check if tax exist return tax , if it doesn't exist it add a new tax
     * @param float $fTaxPercent
     * @return TaxCore
     */
    protected function getTax($fTaxPercent) {
        $fTaxPercent = (float)$fTaxPercent;
        $tax_id = Db::getInstance()->getValue('SELECT `id_tax` FROM `'._DB_PREFIX_."tax` WHERE rate = ".(float)$fTaxPercent)." ORDER BY active DESC limit 1";
        if (!empty($tax_id)) {
            $oTax = new Tax($tax_id);
        } else {
            $oTax = new Tax();
            $sName = MLModul::gi()->getMarketPlaceName().' - Tax - '.$fTaxPercent;
            $oTax->name[Configuration::get('PS_LANG_DEFAULT')] = $sName;
            $oTax->name[Context::getContext()->language->id] = $sName;
            $oTax->rate = $fTaxPercent;
            $oTax->active = 1;
            $oTax->add();
        }
        return $oTax;
    }

    protected function getMaxTaxRateFromProducts() {
        $aDetails = $this->oCurrentShopOrder->getOrderDetailList();
        $fTotalTax = 0.00;
        if (count($aDetails) > 0) {
            foreach ($aDetails as $aDetail) {
                $fTotalTax = max($fTotalTax, (float)$aDetail['tax_rate']);
            }
        }
        return (float)$fTotalTax;
    }

    /**
     * set state or status of current order
     * @param type $iStateId
     * @return ML_Prestashop_Helper_Model_ShopOrder
     */
    protected function setState($iStateId) {
        $oOrder = $this->oCurrentShopOrder;
        $oHistory = new OrderHistory();
        //check state change here, otherwise order state will be updatein changeIdOrderState
        $blNewHistory = $oOrder->current_state != $iStateId;
        $oHistory->id_order = $oOrder->id;
        // we need to execute this function anyhow, because it updates amount of invoice and payment
        $oHistory->changeIdOrderState((int)$iStateId, $oOrder, true);
        //we don't need to add new History when state isn't changed
        if ($blNewHistory) {
            $oHistory->add(true);
        }
        return $this;
    }

    /**
     * do prestashop validation and replace some data (those are not necessary) by valid data
     * @param string $sName
     * @param string $sType
     * @param int $iLength
     * @param string $sRelatedField
     * @param bool $blRequired
     * @return mixed
     * @throws Exception
     */
    protected function validate($sName, $sType, $iLength = 0, $sRelatedField = '', $blRequired = true) {
        $sValidated = trim($sName);
        $blCheckUnicode = false;
        if (in_array($sType, array('customerName', 'name', 'genericname', 'address', 'cityname', 'postcode'))) {
            $blCheckUnicode = method_exists('Tools', 'cleanNonUnicodeSupport');
        }
        switch ($sType) {
            case 'customerName':
            {
                if (!method_exists('Validate', 'isCustomerName') ) {
                    return $this->validate($sName, 'name', $iLength , $sRelatedField , $blRequired );
                }
                if (!Validate::isCustomerName($sValidated)) {
                    ///^(?:[^0-9!<>,;?=+()\/\\@#"°*`{}_^$%:¤\[\]|\.。]|[\.。](?:\s|$))*$/u
                    $sPattern = $blCheckUnicode ?  Tools::cleanNonUnicodeSupport('/[0-9!<>,;?=+()\/\\@#"°*`{}_^$%:¤\[\]|]|\.(?!\s)/u') : '/[0-9!<>,;?=+()\/\\@#"°*`{}_^$%:¤\[\]|]|\.(?!\s)/u';

                    $sValidated = preg_replace($sPattern, '', stripslashes($sValidated));
                    $this->setMessage("'$sName' for $sType is invalid , replaced by '$sValidated'");
                } else {

                }
                if (empty($sValidated) && $blRequired) {
                    $sValidated = "empty--";
                    $this->setMessage("$sRelatedField is empty");
                }
                break;
            }
            case 'name':
            {
                if (!Validate::isName($sValidated)) {
                    $sPatern = $blCheckUnicode ? Tools::cleanNonUnicodeSupport('/[0-9!<>,;?=+()@#"{}_$%:|¤]/u') : '/[0-9!<>,;?=+()@#"{}_$%:|¤]/u';
                    $sValidated = preg_replace($sPatern, '', stripslashes($sValidated));
                    $this->setMessage("'$sName' for $sType is invalid , replaced by '$sValidated'");
                }
                if (empty($sValidated) && $blRequired) {
                    $sValidated = "empty--";
                    $this->setMessage("$sRelatedField is empty");
                }
                break;
            }
            case 'genericname':
            {
                if (!Validate::isGenericName($sValidated)) {
                    $sPatern = $blCheckUnicode ? Tools::cleanNonUnicodeSupport('/[<>=#{}]/u') : '/[<>=#{}]/u';
                    $sValidated = preg_replace($sPatern, '', stripslashes($sValidated));
                    $this->setMessage("'$sName' for $sType is invalid , replaced by $sValidated");
                }

                if (empty($sValidated) && $blRequired) {
                    $sValidated = 'empty--';
                    $this->setMessage("$sRelatedField is empty");
                }
                break;
            }
            case 'address':
            {
                if (!Validate::isAddress($sValidated)) {
                    $this->setMessage("'$sName' for $sType is invalid , replaced by $sValidated");
                    $sPatern = $blCheckUnicode ? Tools::cleanNonUnicodeSupport('/[!<>?=+@{}_$%]/u') : '/[!<>?=+@{}_$%]/u';
                    $sValidated = preg_replace($sPatern, '', stripslashes($sValidated));
                }

                if (empty($sValidated) && $blRequired) {
                    $sValidated = "empty--";
                    $this->setMessage("$sRelatedField is empty");
                }
                break;
            }
            case 'isocode':
            {
                if (!empty($sValidated)) {
                    if (!Validate::isLanguageIsoCode($sValidated)) {
                        $this->setMessage("'$sValidated' $sType isocode is invalid , replaced by default iso");
                        return Configuration::get('PS_COUNTRY_DEFAULT');
                    } else if (Country::getByIso($sValidated)) {
                        return Country::getByIso($sValidated);
                    } else {
                        $this->setMessage("'$sValidated' this $sType is not existed , replaced by default iso");
                        return Configuration::get('PS_COUNTRY_DEFAULT');
                    }
                } else {
                    $this->setMessage("$sRelatedField is empty, default contry was set");
                    return Configuration::get('PS_COUNTRY_DEFAULT');
                }

                break;
            }
            case 'cityname':
            {
                if (!Validate::isCityName($sValidated)) {
                    $sPatern = $blCheckUnicode ? Tools::cleanNonUnicodeSupport('/[!<>;?=+@#{}_$%]/u') : '/[!<>;?=+@#{}_$%]/u';
                    $sValidated = preg_replace($sPatern, '', stripslashes($sValidated));
                    $this->setMessage("'$sName' for $sType name is invalid , replaced by '$sValidated'");
                }

                if (empty($sValidated)) {
                    $sValidated = "empty--";
                    $this->setMessage("$sRelatedField is empty");
                }
                break;
            }
            case 'phone':
            {
                if (!empty($sValidated)) {
                    if (!Validate::isPhoneNumber($sValidated)) {
                        $sValidated = '0100000000';
                        $this->setMessage("'$sName' for $sType number is invalid , replaced by '$sValidated'");
                    }
                } else {
                    $sValidated = '0100000000';
                    $this->setMessage("$sRelatedField is empty");
                }
                break;
            }
            case 'postcode':
            {
                if (!empty($sValidated)) {
                    if (!Validate::isPostCode($sValidated)) {
                        $sPatern = $blCheckUnicode ? Tools::cleanNonUnicodeSupport('/[^a-zA-Z 0-9-]/u') : '/[^a-zA-Z 0-9-]/u';
                        $sValidated = preg_replace($sPatern, '', stripslashes($sValidated));
                        $this->setMessage("'$sName' for $sType number is invalid , replaced by '$sValidated'");
                    }
                } else {
                    $sValidated = '00000';
                    $this->setMessage("$sRelatedField is empty");
                }
                break;
            }
        }

        if (strlen($sValidated) > $iLength) {
            $sValidated = $this->cutValidatedText($sValidated, $iLength, !in_array($sType, array('postcode', 'customerName')));
            $this->setMessage("'$sName' was long , replaced by $sValidated");
        }

        return $sValidated;
    }


    protected function cutValidatedText($sValidated, $iLength, $blDottedEnd = true) {
        if ($blDottedEnd) {
            $sValidated = mb_substr($sValidated, 0, $iLength - 4, 'UTF8')."...";
        } else {
            $sValidated = mb_substr($sValidated, 0, $iLength, 'UTF8');
        }
        return $sValidated;
    }

    /**
     * try to find shipping code of order data in list of prestashop carrier and return id of found carrier
     * if carrier doesn't exist it will create it and return new carrier id
     *
     * @return int
     */
    protected function getCarrier() {
        $aTotalShipping = $this->getTotal('Shipping');
        $iCarrier = Configuration::get('PS_CARRIER_DEFAULT');
        if (isset($aTotalShipping['Code'])) {
            if (is_numeric($aTotalShipping['Code'])) {
                $iCarrier = $aTotalShipping['Code'];
            } else {
                $sql = '
                    SELECT id_carrier
                    FROM `'._DB_PREFIX_.'carrier` c'
                    .' WHERE name = \''.pSQL($aTotalShipping['Code']).'\'';
                $aCarriers = Db::getInstance()->executeS($sql);
                if (!empty($aCarriers) && is_array($aCarriers)) {
                    $aCarrier = current($aCarriers);
                    $iCarrier = $aCarrier['id_carrier'];
                } else {
                    $iCarrier = MLHelper::gi('model_carrier')->createCarrier($aTotalShipping['Code'], Context::getContext()->shop);
                }
            }
        }
        return $iCarrier;
    }

    /**
    * To make easier to do individual programming
    */
    protected function addAddressExtraData(AddressCore $oAddress) {
    }

    /**
     * @param array $aPSOrderDetails
     * @param $aProduct
     * @return false
     */
    private function isProductImported(&$aPSOrderDetails, $aProduct) {
        $blFound = false;
        if (!empty($aPSOrderDetails)) {
            foreach ($aPSOrderDetails as $sKey => $oPSProduct) {
                if ((int)$oPSProduct['product_quantity'] == (int)$aProduct['Quantity']
                    && ((float)$oPSProduct['unit_price_tax_incl'] == (float)$aProduct['Price'])
                ) {
                    if (trim($aProduct['SKU']) == '' && $oPSProduct['product_reference'] == $aProduct['ItemTitle']) {//in ebay sku could be empty
                        $blFound = true;
                    } elseif (trim($aProduct['SKU']) !== '') {
                        if ($oPSProduct['product_reference'] === $aProduct['SKU']) {
                            $blFound = true;
                        } else {// perhaps sku have changed or wrong keytype?
                            $oProduct = MLProduct::factory();
                            $sPSProductNumber = $oPSProduct['product_reference'];
                            if ($oProduct->getByMarketplaceSKU($aProduct['SKU'])->exists() && $oProduct->getSku() === $sPSProductNumber) {
                                $blFound = true;
                            }
                        }
                    }
                }
                if ($blFound) {
                    unset($aPSOrderDetails[$sKey]);
                    break;
                }
            }
        }
        return $blFound;
    }

}
