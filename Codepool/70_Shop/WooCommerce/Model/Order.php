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

class ML_WooCommerce_Model_Order extends ML_Shop_Model_Order_Abstract {
    /**
     * @return WC_Order|bool
     * @throws Exception
     */
    public function getShopOrderObject() {
        $oOrder = WC_Order_Factory::get_order($this->get('current_orders_id'));
        if (is_object($oOrder)) {

            return $oOrder;
        } else {
            throw new Exception("this order is not found in shop");
        }
    }

    public function getShopOrderStatus() {
        try {
            /** @var WC_Order $oOrder */
            $oOrder = $this->getShopOrderObject();
            $status = 'wc-' . $oOrder->get_status();

            return $status;
        } catch (Exception $oExc) {

            return null;
        }
    }

    /**
     * WooCommerce doesn't support payment status, so we will use order status
     * @return null|string
     */
    public function getShopPaymentStatus() {
        return $this->getShopOrderStatus();
    }

    /**
     * @return null|string
     */
    public function getShopOrderStatusName() {
        return $this->getShopOrderStatus();
    }

    public function getEditLink() {
        return 'post.php?post='.$this->get('current_orders_id').'&action=edit';
    }

    public function getShippingCarrier() {
        return $this->getShopOrderCarrierOrShippingMethod();
    }

    public function getShopOrderCarrierOrShippingMethod() {
        global $wpdb;
        $mCarrier = null;
        try {
            $aConfigTrackingKey = MLModule::gi()->getConfig('orederstatus.trackingkey');
            if ($aConfigTrackingKey === '_wc_shipment_tracking_items') {
                $mCarrier = $this->getASTShippingProvider($aConfigTrackingKey, $wpdb);
            } else if ($aConfigTrackingKey === 'germanized') {
                $mCarrier = $this->getGermanizedShippingProvider();
            }

            if (empty($mCarrier)) {
                /** @var WC_Order $oOrder */
                $oOrder = $this->getShopOrderObject();
                $tableName = $wpdb->prefix.'woocommerce_order_items';
                $shipping = $wpdb->get_row("
                SELECT order_item_name
                FROM $tableName
                WHERE order_item_type = 'shipping' AND order_id = ".$oOrder->get_order_number()
                );
                $mCarrier = isset($shipping->order_item_name) ? $shipping->order_item_name : null;
            }
        } catch (Exception $oEx) {
        }
        return $mCarrier;
    }

    public function getShippingCarrierId() {
        try {
            /** @var WC_Order $oOrder */
            $oOrder = $this->getShopOrderObject();
            $shopOrder = $oOrder->get_shipping_method();
            return empty($shopOrder) ? null : $shopOrder;
        } catch (Exception $oEx) {
            return null;
        }
    }

    public function getShippingDateTime() {
        /** @var WC_Order $oOrder */
        $oOrder = $this->getShopOrderObject();
        $oOrderHistory = date_format($oOrder->get_date_modified(), "Y-m-d H:i:s");
        if ( ! isset($oOrderHistory)) {
            return date('Y-m-d H:i:s');
        } else {
            return $oOrderHistory;
        }
    }

    public function setSpecificAcknowledgeField(&$aOrderParameters, $aOrder) {
        try {
            $aOrderParameters['ShopOrderIDPublic'] = $this->getShopOrderObject()->get_id();
        } catch (Exception $oEx) {//if order deosn't exist in woocommerce
            $aData = $this->get('orderdata');
            if (array_key_exists('WoocommerceOrderNumber', $aOrder)) {//
                $aOrderParameters['ShopOrderIDPublic'] = $aOrder['WoocommerceOrderNumber'];
            } else if (array_key_exists('WoocommerceOrderNumber', $aData)) {//if order existed see in existed orderdata
                $aOrderParameters['ShopOrderIDPublic'] = $aData['WoocommerceOrderNumber'];
            }
        }
    }

    public function getShippingDate() {
        return substr($this->getShippingDateTime(), 0, 10);
    }

    /**
     * WooCommerce doesn't support tracking code but we implemented configuration orderstatus.trackingkey
     * @return string
     */
    public function getShippingTrackingCode() {
        $oModule = MLModule::gi();
        $aConfigTrackingKey = $oModule->getConfig('orederstatus.trackingkey');
        $mData = null;
        if ($aConfigTrackingKey === 'orderFreetextField') {
            $mData = $this->getAdditionalOrderField('trackingKey');
        } else if ($aConfigTrackingKey === '_wc_shipment_tracking_items') {
            if (is_plugin_active('woo-advanced-shipment-tracking/woocommerce-advanced-shipment-tracking.php')) {
                $result = get_post_meta($this->get('current_orders_id'), $aConfigTrackingKey, true);
                $mData = is_array($result) && end($result)['tracking_number'] !== null ? end($result)['tracking_number'] : null;
            }
        } else if ($aConfigTrackingKey === 'germanized') {
            $mData = $this->getGermanizedTrackingCode();
        } else if ($aConfigTrackingKey !== null) {
            $mData = get_post_meta($this->get('current_orders_id'), $aConfigTrackingKey, true);
        }

        if ($mData !== null && !is_array($mData) && !is_object($mData)) {
            return $mData;
        } else if (is_array($mData) && isset($mData['carrier_tracking_no'])) {//support shipcloud plugin by selecting shipcloud_shipment_data a custom field
            return $mData['carrier_tracking_no'];
        } else {
            MLMessage::gi()->addDebug('Value for tracking number is not appropriate', array($mData));
            MLLog::gi()->add('SyncOrderStatus_'.MLModule::gi()->getMarketPlaceId().'_WrongConfiguration', array(
                'Problem' => '#tracking-key# is not filled in order detail of shop or it is not correctly configured!',
            ));
            return null;
        }

    }

    public function getShopOrderLastChangedDate() {
        return $this->getShippingDateTime();
    }

    /**
     * @param int $iOffset
     * @param bool $blCount
     *
     * @return array|type
     */
    public static function getOutOfSyncOrdersArray($iOffset = 0, $blCount = false) {
        global $wpdb;
        $oQueryBuilder = MLDatabase::factorySelectClass()->select('ID')
            ->from($wpdb->posts, 'so')
            ->join(array('magnalister_orders', 'mo', 'so.ID = mo.current_orders_id'), ML_Database_Model_Query_Select::JOIN_TYPE_LEFT)
            ->where("so.post_status != mo.status AND mo.mpID = '".MLModule::gi()->getMarketPlaceId()."'");

        if ($blCount) {
            return $oQueryBuilder->getCount();
        } else {
            $aOrders = $oQueryBuilder->limit($iOffset, 100)
                ->getResult();

            $aOut = array();
            if (!is_array($aOrders)) {
                MLHelper::gi('stream')->outWithNewLine(MLDatabase::getDbInstance()->getLastError());
            } else {
                foreach ($aOrders as $aOrder) {
                    $aOut[] = $aOrder['ID'];
                }
            }
            return $aOut;
        }
    }

    public function shopOrderByMagnaOrderData($aData) {
        try {
            $mReturn = MLHelper::gi('model_shoporder')
                               ->setOrder($this)
                               ->setNewOrderData($aData)
                               ->shopOrder();

            return $mReturn;
        } catch (Exception $oEx) {
            $Model = MLDatabase::factory('config')->set('mpid', 0)->set('mkey', 'woocommerce_prices_include_tax');
            update_option('woocommerce_prices_include_tax', $Model->get('value'));
            $Model->delete();
            throw $oEx;
        }
    }

    public function getShopOrderTotalAmount() {
        $oOrder = $this->getShopOrderObject();
        return $oOrder->get_total();
    }

    public function getShopOrderTotalTax() {
        $oOrder = $this->getShopOrderObject();
        return $oOrder->get_total_tax();
    }

    /**
     *
     * @param $sKey
     * @return mixed|null
     */
    public function getAdditionalOrderField($sKey) {
        $aSetting = MLSetting::gi()->{'magnalister_shop_order_additional_field'};
        if (is_array($aSetting) && isset($aSetting[$sKey])) {
            return get_post_meta($this->get('current_orders_id'), $aSetting[$sKey], true);
        }
    }

    protected function getGermanizedTrackingCode() {
        if (function_exists('wc_gzd_get_shipment_order')) {
            $order_shipment = wc_gzd_get_shipment_order($this->getShopOrderObject());
            $shipments = $order_shipment->get_shipments();
            foreach ($shipments as $shipment) {
                if ($shipment->get_tracking_id() != '') {
                    return $shipment->get_tracking_id();
                }
            }
        }
        return null;
    }


    protected function getGermanizedShippingProvider() {
        if (function_exists('wc_gzd_get_shipment_order')) {
            $order_shipment = wc_gzd_get_shipment_order($this->getShopOrderObject());
            $shipments = $order_shipment->get_shipments();
            foreach ($shipments as $shipment) {
                if ($shipment->get_shipping_provider_title() != '') {
                    return $shipment->get_shipping_provider_title();
                }
            }
        }
        return null;
    }

    /**
     * @param string $aConfigTrackingKey
     * @param $wpdb
     * @return array
     */
    protected function getASTShippingProvider(string $aConfigTrackingKey, $wpdb) {
        if (is_plugin_active('woo-advanced-shipment-tracking/woocommerce-advanced-shipment-tracking.php')) {
            $result = get_post_meta($this->get('current_orders_id'), $aConfigTrackingKey, true);
            $mData = is_array($result) && end($result)['tracking_provider'] !== null ? end($result)['tracking_provider'] : null;
            $sTable = $wpdb->prefix.'woo_shippment_provider';
            $shippment_countries = $wpdb->get_results("SELECT `shipping_country` FROM `$sTable` WHERE `display_in_order` = 1 GROUP BY `shipping_country`");
            foreach ($shippment_countries as $s_c) {
                $country = $s_c->shipping_country;
                $shippment_providers_by_country = $wpdb->get_results($wpdb->prepare("SELECT * FROM `$sTable` WHERE shipping_country = %s AND display_in_order = 1", $country));
                foreach ($shippment_providers_by_country as $providers) {
                    if ($mData === $providers->ts_slug) {
                        return $providers->provider_name;
                    }
                }
            }
        }
    }

    public function getShopOrderInvoiceNumber($sType) {
        if (is_plugin_active('woocommerce-german-market/WooCommerce-German-Market.php') && MLService::getUploadInvoices()->getInvoiceOptionConfig() === 'germanmarket') {
            return $this->getShopOrderObject()->get_id();
        }
        return '';
    }
    public function getShopOrderInvoice($sType) {
        if (is_plugin_active('woocommerce-german-market/WooCommerce-German-Market.php') && MLService::getUploadInvoices()->getInvoiceOptionConfig() === 'germanmarket') {
            // load Wordpress order first
            $order = wc_get_order($this->getShopOrderObject()->get_id());

            $generateFileName = function ($fileName, $documentFilter, $documentOption, $order) {
                return WP_WC_Invoice_Pdf_Email_Attachment::repair_filename(
                    apply_filters($documentFilter,
                        get_option($documentOption,
                            __($fileName, 'woocommerce-german-market')
                        ), $order)
                );
            };

            // invoice generation args
            $args = array(
                'order' => $order,
                'output_format' => 'pdf',
                'output' => 'cache',
                'filename' => null,
                'admin' => true
            );

            if ($this->isInvoiceDocumentType($sType)) {
                $fileName = 'Invoice {{order-number}}';
                $documentOption = 'wp_wc_invoice_pdf_file_name_backend';
                $documentFilter = 'wp_wc_invoice_pdf_backend_filename';
            } elseif ($this->isCreditNoteDocumentType($sType)) {
                $refund_id = $order->get_refunds()[0];
                $refund = wc_get_order($refund_id);
                $args['refund'] = $refund;

                $documentOption = 'wp_wc_invoice_pdf_refund_file_name_backend';
                $documentFilter = 'wp_wc_invoice_pdf_refund_backend_filename';
                $fileName = 'Refund {{refund-id}} - {{order-number}}';
            } else {
                // invoice type not detected
                return '';
            }

            // set filename
            $args['filename'] = $generateFileName($fileName, $documentFilter, $documentOption, $order);

            // generate the invoice
            $document = new WP_WC_Invoice_Pdf_Create_Pdf($args);
            $filePath = WP_WC_INVOICE_PDF_CACHE_DIR.$document->cache_dir.DIRECTORY_SEPARATOR.$document->filename;

            if (isset($filePath) && file_exists($filePath)){
                $pdf = file_get_contents($filePath);
                return base64_encode($pdf);
            }
        }
        return '';
    }

    public function getShopOrderProducts() {
        return array();
    }
}
