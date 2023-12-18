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

MLFilesystem::gi()->loadClass('Shop_Model_Order_Abstract');

use Shopify\API\Application\Application;
use Shopify\API\Application\Request\Orders\ListOfOrders\ListOfOrdersParams;
use Shopify\API\Application\Request\Orders\SingleOrder\SingleOrderParams;


class ML_Shopify_Model_Order extends ML_Shop_Model_Order_Abstract {

    protected $blUpdatablePaymentStatus;
    protected $blUpdatableOrderStatus;

    /**
     * Creates order and manipulates $aData
     * eg. $aData['AddressSets']['Main']['Password'] if possible.
     *
     * @param array $aData
     *
     * @return array
     * @throws Exception
     * @see /Doku/orderexport.json
     */
    public function shopOrderByMagnaOrderData($aData) {
        $oShopOrderHelper = MLShopifyAlias::getShopOrderHelper();
        try {
            $mReturn = $oShopOrderHelper->setOrder($this)->setNewOrderData($aData)->shopOrder();

            return $mReturn;
        } catch (Exception $oEx) {
            throw $oEx;
        }

    }

    /**
     * @return string|null
     * @throws Exception
     */
    public function getShopOrderCarrierOrShippingMethod() {
        $oOrder = $this->getShopOrderObject();
        if (count($oOrder->fulfillments) > 0) {
            foreach ($oOrder->fulfillments as $aFulfillment) {
                if (isset($aFulfillment->status) && $aFulfillment->status === 'success') {
                    $sShippingCarrier = $aFulfillment->tracking_company;

                    if ($sShippingCarrier !== null) {
                        return $sShippingCarrier;
                    }
                }
            }
        }
        return null;
    }

    /**
     * Gets the translation of the order status from this order.
     *
     * @return string
     *
     */
    public function getShopOrderStatusName() {
        try {
            $oOrder = $this->getShopOrderObject();
            $status = ($oOrder->fulfillment_status) ? $oOrder->fulfillment_status : 'open';

            return $status;
        } catch (Exception $oExc) {
            return null;
        }
    }

    /**
     * @return string
     *    A Timestamp with the format YYYY-mm-dd
     *
     */
    public function getShippingDate() {
        return '';
    }

    public function getShippingDateTime() {
        $oOrder = $this->getShopOrderObject();
        if (is_array($oOrder->fulfillments) && count($oOrder->fulfillments) > 0) {
            $sOrderShippmentDateTime = date_format(
                new DateTime($oOrder->fulfillments[0]->updated_at), "Y-m-d H:i:s");

            if (!isset($sOrderShippmentDateTime)) {

                return date("Y-m-d H:i:s");
            } else {

                return $sOrderShippmentDateTime;
            }
        } else {
            return null;
        }

    }

    /**
     * Get the carrier for this order.
     * If there is no carrier information available for this order
     * this method will return the setting orderstatus.carrier.default.
     *
     * @return string|null - The shipping carrier
     * @throws Exception
     */
    public function getShippingCarrier() {
        $sDefaultCarrier = MLModule::gi()->getConfig('orderstatus.carrier.default');
        $sDefaultCarrier = $sDefaultCarrier === null ? MLModule::gi()->getConfig('orderstatus.carrier') : $sDefaultCarrier;

        if ($sDefaultCarrier == '-1') {
            $oOrder = $this->getShopOrderObject();
            if (count($oOrder->fulfillments) > 0) {
                $sShippingCarrier = $oOrder->fulfillments[0]->tracking_company;

                if ($sShippingCarrier !== null) {
                    return $sShippingCarrier;
                }
            }
        } elseif (in_array($sDefaultCarrier, array('--', ''))) {
            return null;
        } else {
            return $sDefaultCarrier;
        }

        return '';
    }

    /**
     * Gets the tracking code for this order.
     * If there is no tracking code available the setting
     * orderstatus.carrier.additional will be
     * returned (which does not make any sense.
     * An empty string should be returned instead.)
     *
     * @return string
     * @throws Exception
     * @todo Investigate and implement function.
     *
     */
    public function getShippingTrackingCode() {
        $oOrder = $this->getShopOrderObject();
        if (count($oOrder->fulfillments) > 0) {
            $sTrackingCode = $oOrder->fulfillments[0]->tracking_number;

            if ($sTrackingCode !== null) {
                return $sTrackingCode;
            }
        }

        return '';
    }

    /**
     * Returns a link to the order detail page if possible.
     *
     * @return string
     *
     */
    public function getEditLink() {
        $aQueryString = MLHelper::gi('model_http')->getQueryStringAsArray();
        $shopId = $aQueryString['shop'];
        return 'https://'.$shopId.'/admin/orders/'.$this->get('current_orders_id');
    }

    /**
     * Gets the "last modified" timestamp of this order.
     *
     * @return string
     *    Timestamp with the format YYYY-mm-dd h:i:s
     * @todo Investigate and implement function.
     *
     */
    public function getShopOrderLastChangedDate() {
        return '';
    }

    public function getPlatformName() {
        return $this->aData;
    }

    /**
     * Gets the order status from this order.
     *
     *
     * @return string be careful about return value , if the status is id , you should convert it to string,
     * otherwise there could be some problem in comparison with config data
     * @throws Exception
     *
     */
    public function getShopOrderStatus() {
        $oOrder = $this->getShopOrderObject();
        if ($oOrder->cancelled_at !== null) {
            return 'cancelled';
        }
        if ($oOrder->fulfillment_status === null) {
            return 'open';
        }

        return $oOrder->fulfillment_status.'';
    }

    /**
     * Send specific field in order Acknowledge.
     *
     *
     * @param $aOrderParameters
     * @param $aOrder
     */
    public function setSpecificAcknowledgeField(&$aOrderParameters, $aOrder) {
        try {
            $aOrderParameters['ShopOrderIDPublic'] = str_replace('#', '', $this->getShopOrderObject()->name);
        } catch (Exception $oEx) {
            $aData = $this->get('orderdata');
            if (array_key_exists('ShopifyOrderNumber', $aOrder)) {
                $aOrderParameters['ShopOrderIDPublic'] = $aOrder['ShopifyOrderNumber'];
            } else if (array_key_exists('ShopwareOrderNumber', $aData)) {//if order existed see in existed orderdata
                $aOrderParameters['ShopOrderIDPublic'] = $aData['ShopifyOrderNumber'];
            }
        }
    }

    /**
     * Returns order from Shopify by provided order Id.
     * @throws Exception
     */
    public function getShopOrderObject($sCurrentOrderId = null) {
        $sCurrentOrderId = ($sCurrentOrderId == null) ? $this->get('current_orders_id') : $sCurrentOrderId;
        if (!isset(self::$aOrdersCache[$sCurrentOrderId])) {
            $sShopId = MLHelper::gi('model_shop')->getShopId();
            $application = new Application($sShopId);
            $singleOrderParams = new SingleOrderParams();
            $singleOrderParams->setOrderId($sCurrentOrderId);

            $oResponse = $application->getOrderRequest()->getSingleOrder($singleOrderParams)->getBodyAsObject();
            if (isset($oResponse->order) && is_object($oResponse->order)) {
                self::$aOrdersCache[$sCurrentOrderId] = $oResponse->order;
            } else {
                throw new Exception('This order is not found in shop');
            }
        }
        return self::$aOrdersCache[$sCurrentOrderId];
    }

    public static $aOrdersCache = array();

    public static function getOutOfSyncOrdersArray($iOffset = 0, $blCount = false) {
        $sShopId = MLHelper::gi('model_shop')->getShopId();
        $application = new Application($sShopId);
        $oMLOrderQuery = MLOrder::factory()->getList()->getQueryObject();
        $oMLOrderQuery->where("insertTime > '".date('Y-m-d H:i:s', time() - 60 * 60 * 24 * 60 /* shopify return orders of last 60 days in order_read scope*/)."'");
        $aListOfStatus = self::getConfirmedAndCancelledStatuses();
        if (count($aListOfStatus) > 0) {
            $oMLOrderQuery->where("status NOT IN ('".implode("','", $aListOfStatus)."')");
        }
        $iCount = $oMLOrderQuery->getCount();
        $iLimit = 50;
        if ($blCount) {
            return (int)$iCount;
        } else {
            $aMLOrders = $oMLOrderQuery->getResult();
            //            var_dump(__FILE__,array_column($aMLOrders, 'current_orders_id'));
            $aListOfOrderIds = array();
            $i = 1;
            $aIds = [];
            foreach ($aMLOrders as $aMLOrder) {
                // dont use orders_id -> because it could change so we use current_orders_id for sync
                $aIds[] = $aMLOrder['current_orders_id'];
                if ($i % $iLimit === 0 || $i === (int)$iCount) {
                    $listOfOrderParams = new ListOfOrdersParams();
                    $listOfOrderParams->setIds(implode(',', $aIds));
                    $aOrders = $application->getOrderRequest()->getListOfOrders($listOfOrderParams)->getBodyAsArray()['orders'];
                    //                    var_dump($aOrders);
                    foreach ($aOrders as $aOrder) {
                        $aOrdersCache[$aOrder['id']] = $aOrder;
                        $aListOfOrderIds[] = $aOrder['id'];
                    }
                    $aIds = [];
                }
                $i++;
            }
            //            var_dump($aListOfOrderIds);
            //                        $line = __FILE__.__LINE__;die($line);
            return $aListOfOrderIds;
        }

    }

    private static function getConfirmedAndCancelledStatuses() {
        $aListOfStatus = array();
        try {
            foreach (MLModul::gi()->getStatusConfigurationKeyToBeConfirmedOrCanceled() as $sKey) {
                $sListOfStatus = MLModul::gi()->getConfig($sKey);
                if (is_array($sListOfStatus)) {
                    $aListOfStatus = array_merge($aListOfStatus, $sListOfStatus);
                } else if ($sListOfStatus !== null) {
                    $aListOfStatus[] = $sListOfStatus;
                }
            }
        } catch (\Exception $ex) {

        }
        return $aListOfStatus;
    }


    public function getShopOrderTotalAmount() {
        $oOrder = $this->getShopOrderObject();
        return $oOrder->total_price;
    }

    public function getShopOrderTotalTax() {

        $oOrder = $this->getShopOrderObject();
        return $oOrder->total_tax;
    }

    public function getShopPaymentStatus() {
        $oOrder = $this->getShopOrderObject();
        return $oOrder->financial_status;
    }

    public function getUpdatablePaymentStatus(){
        return $this->blUpdatablePaymentStatus;
    }

    public function setUpdatablePaymentStatus($blUpdatable){
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
     * @param $sKey
     * @return mixed|null
     */
    public function getAdditionalOrderField($sKey) {
        $oOrder = $this->getShopOrderObject();
        $aSetting = MLSetting::gi()->{'magnalister_shop_order_additional_field'};
        if (is_array($oOrder->note_attributes)) {
            foreach ($oOrder->note_attributes as $aAttribute) {
                if ($aAttribute->name === $aSetting[$sKey]) {
                    return $aAttribute->value;
                }
            }
            return null;
        }
    }

}
