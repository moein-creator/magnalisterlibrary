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

class ML_Prestashop_Model_Order extends ML_Shop_Model_Order_Abstract {
    /**  @var OrderCore */
    protected $oShopOrder = null;

    /**
     * find order in shop and return order object
     * @return OrderCore
     * @throws Exception
     */
    public function getShopOrder() {
        if ($this->oShopOrder === null) {
            $this->oShopOrder = new Order($this->get('current_orders_id'));
        }
        if (!$this->oShopOrder instanceof Order) {
            throw new Exception("order is not found. shop order id : ".$this->get('current_orders_id'));
        }
        return $this->oShopOrder;
    }

    public function getShopOrderStatus() {
        return $this->getShopOrder()->current_state."";//convert status id to string
    }

    public function getEditLink() {
        return (isset(Context::getContext()->employee) && is_object(Context::getContext()->employee)) ? 'index.php?controller=AdminOrders&vieworder&id_order='.$this->get('current_orders_id').
            '&token='.Tools::getAdminToken('AdminOrders'.(int)Tab::getIdFromClassName('AdminOrders').(int)Context::getContext()->employee->id) : '';
    }

    public function getShippingCarrier() {
        $oOrder = new Order($this->get('current_orders_id'));
        $oCarrier = new Carrier($oOrder->id_carrier);
        if (defined('_PS_VERSION_') && version_compare(_PS_VERSION_, '1.7.0.0', '>=')) {
            $aOrderCarrier = $oOrder->getShipping();
            $aOrderCarrier = current($aOrderCarrier);
            if (!empty($aOrderCarrier['id_carrier'])) {
                $oCarrier = new Carrier($aOrderCarrier['id_carrier']);
            }
        }
        return isset($oCarrier->name) ? $oCarrier->name : $this->getModul()->getConfig('orderstatus.carrier.default');
    }

    public function getShopOrderCarrierOrShippingMethodId() {
        $oOrder = new Order($this->get('current_orders_id'));
        return $oOrder->id_carrier;
    }

    public function getShippingDateTime() {
        $oOrder = $this->getShopOrder();
        $aOrderHistory = $oOrder->getHistory(
            $this->getModul()->getConfig('lang'),
            (int)($this->getModul()->getConfig('orderstatus.shipped')),
            FALSE);
        $sShippedOrder = '';
        if (count($aOrderHistory) > 0) {
            $aShippedOrder = current($aOrderHistory);
            $sShippedOrder = (string)$aShippedOrder['date_add'];
        } else {
            $sShippedOrder = $oOrder->date_upd;
        }
        #return substr($sShippedOrder,0,10);
        return $sShippedOrder;
    }

    public function getShippingDate() {
        return substr($this->getShippingDateTime(), 0, 10);
    }

    public function getShippingTrackingCode() {
        $aTracking = MLDatabase::factorySelectClass()->select('tracking_number')
            ->from(_DB_PREFIX_.'order_carrier')
            ->where('id_order ='.$this->get('current_orders_id'))
            ->getResult();
        if (count($aTracking) > 0) {
            return $aTracking[0]['tracking_number'];
        } else {
            return '';
        }
    }

    public function getShopOrderLastChangedDate() {
        $oOrder = new Order($this->get('current_orders_id'));
        if (!isset($oOrder->date_upd)) {
            throw new Exception("order update date is empty ");
        } else {
            return $oOrder->date_upd;
        }
    }

    public static function getOutOfSyncOrdersArray($iOffset = 0, $blCount = false) {
        $oQueryBuilder = MLDatabase::factorySelectClass()->select('id_order')
            ->from(_DB_PREFIX_.'orders', 'po')
            ->join(array('magnalister_orders', 'mo', 'po.id_order = mo.current_orders_id'), ML_Database_Model_Query_Select::JOIN_TYPE_LEFT)
            ->where("po.current_state != mo.status AND mo.mpID = '".MLModul::gi()->getMarketPlaceId()."'");
        try {
            $aListOfStatus = array();
            foreach (MLModul::gi()->getStatusConfigurationKeyToBeConfirmedOrCanceled() as $sKey) {
                $sListOfStatus = MLModul::gi()->getConfig($sKey);
                if (is_array($sListOfStatus)) {
                    $aListOfStatus = array_merge($aListOfStatus, $sListOfStatus);
                } else
                    if ($sListOfStatus !== null) {
                        $aListOfStatus[] = $sListOfStatus;
                    }
            }
            if (count($aListOfStatus) > 0) {
                $oQueryBuilder->where("po.current_state IN ('".implode("','", $aListOfStatus)."')");
            }
        } catch (\Exception $ex) {

        }

        if ($blCount) {
            return $oQueryBuilder->getCount();
        } else {

            $aOrders = $oQueryBuilder->limit($iOffset, 100)
                ->getResult();

            if (!is_array($aOrders)) {
                MLHelper::gi('stream')->outWithNewLine(MLDatabase::getDbInstance()->getLastError());
            }

            $aOut = array();
            foreach ($aOrders as $aOrder) {
                $aOut[] = $aOrder['id_order'];
            }
            return $aOut;
        }
    }

    public function shopOrderByMagnaOrderData($aData) {
        return MLHelper::gi('model_shoporder')
            ->setOrder($this)
            ->setNewOrderData($aData)
            ->shopOrder();
    }

    public function getShopOrderStatusName() {
        $oState = new OrderState($this->getShopOrder()->current_state);
        /* @var $oState OrderStateCore */
        return $oState->name[Context::getContext()->language->id];
    }

    public function setSpecificAcknowledgeField(&$aOrderParameters, $aOrder) {

    }

    public static function unAcknowledgeImportedOrder($sSubsystem, $iMpId, $iMorderID, $iShopOrderID, $blSentApiRequest = true) {
        parent::unAcknowledgeImportedOrder($sSubsystem, $iMpId, $iMorderID, $iShopOrderID, $blSentApiRequest);
        $oDB = MLDatabase::getDbInstance();

        if ($oDB->columnExistsInTable('id_order', _DB_PREFIX_.'message')) {
            $oDB->delete(_DB_PREFIX_."message", array('id_order' => $iShopOrderID));
        }

        if ($oDB->columnExistsInTable('id_order', _DB_PREFIX_.'order_carrier')) {
            $oDB->delete(_DB_PREFIX_."order_carrier", array('id_order' => $iShopOrderID));
        }

        if ($oDB->columnExistsInTable('id_order', _DB_PREFIX_.'order_cart_rule')) {
            $oDB->delete(_DB_PREFIX_."order_cart_rule", array('id_order' => $iShopOrderID));
        }

        if ($oDB->columnExistsInTable('id_order', _DB_PREFIX_.'order_detail')) {
            $oDB->delete(_DB_PREFIX_."order_detail", array('id_order' => $iShopOrderID));
        }

        if ($oDB->columnExistsInTable('id_order', _DB_PREFIX_.'order_history')) {
            $oDB->delete(_DB_PREFIX_."order_history", array('id_order' => $iShopOrderID));
        }

        if ($oDB->columnExistsInTable('id_order', _DB_PREFIX_.'order_invoice')) {
            $oDB->delete(_DB_PREFIX_."order_invoice", array('id_order' => $iShopOrderID));
        }

        if ($oDB->columnExistsInTable('id_order', _DB_PREFIX_.'order_invoice_payment')) {
            $oDB->delete(_DB_PREFIX_."order_invoice_payment", array('id_order' => $iShopOrderID));
        }

        if ($oDB->columnExistsInTable('id_order', _DB_PREFIX_.'order_return')) {
            $oDB->delete(_DB_PREFIX_."order_return", array('id_order' => $iShopOrderID));
        }

        if ($oDB->columnExistsInTable('id_order', _DB_PREFIX_.'order_slip')) {
            $oDB->delete(_DB_PREFIX_."order_slip", array('id_order' => $iShopOrderID));
        }

        if ($oDB->columnExistsInTable('id_order', _DB_PREFIX_.'orders')) {
            $oDB->delete(_DB_PREFIX_."orders", array('id_order' => $iShopOrderID));
        }
        return true;
    }

    /**
     * @throws Exception
     */
    public function getShopOrderTotalAmount() {
        return $this->getShopOrder()->total_paid_tax_incl;
    }

    /**
     * @return float
     * @throws Exception
     */
    public function getShopOrderTotalTax() {
        return $this->getShopOrder()->total_paid_tax_incl - $this->getShopOrder()->total_paid_tax_excl;
    }

    /**
     * @return string|void
     * @throws Exception
     */
    public function getShopOrderInvoice($sType) {

        if ($this->isInvoiceDocumentType($sType)) {
            $order_invoice_list = $this->getShopOrder()->getInvoicesCollection();
            Hook::exec('actionPDFInvoiceRender', array('order_invoice_list' => $order_invoice_list));

            $pdf = new PDF($order_invoice_list, PDF::TEMPLATE_INVOICE, Context::getContext()->smarty);
            $pdf = $pdf->render('S');
            return base64_encode($pdf);
        } elseif ($this->isCreditNoteDocumentType($sType)) {
            foreach ($this->getShopOrder()->getOrderSlipsCollection() as $order_slip) {
                $pdf = new PDF($order_slip, PDF::TEMPLATE_ORDER_SLIP, Context::getContext()->smarty);
                $pdf = $pdf->render('S');
                return base64_encode($pdf);
            }
        }
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getShopOrderInvoiceNumber($sType) {
        if ($this->isInvoiceDocumentType($sType)) {
            foreach ($this->getShopOrder()->getInvoicesCollection() as $oInvoice) {
                /**
                 * @var $oInvoice OrderInvoiceCore
                 */
                return $oInvoice->getInvoiceNumberFormatted(Context::getContext()->language->id, (int)$this->getShopOrder()->id_shop);
            }
        } elseif ($this->isCreditNoteDocumentType($sType)) {
            foreach ($this->getShopOrder()->getOrderSlipsCollection() as $oOrderSlip) {
                /**
                 * @var $oOrderSlip OrderSlipCore
                 */
                return sprintf(
                    '%s%06d',
                    Configuration::get('PS_CREDIT_SLIP_PREFIX', Context::getContext()->language->id),
                    $oOrderSlip->id
                );
            }
        }
        return '';
    }
}
