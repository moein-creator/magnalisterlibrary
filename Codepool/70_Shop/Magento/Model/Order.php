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

class ML_Magento_Model_Order extends ML_Shop_Model_Order_Abstract {
    protected $oCurrentOrder = null;

    /**
     * @return Mage_Sales_Model_Order
     */
    public function getShopOrder() {
        if ($this->oCurrentOrder === null) {
            $this->oCurrentOrder = Mage::getModel('sales/order')->loadByIncrementId($this->get('current_orders_id'));
        }
        return $this->oCurrentOrder;
    }

    public function getShopOrderStatus(){
        return strtolower($this->getShopOrder()->getStatus());
    }

    public function getShopOrderLastChangedDate(){
        // DateTimes in Magento are stored in Database in UTC timezone
        $date = new DateTime($this->getShopOrder()->getUpdatedAt(), new DateTimeZone('UTC'));
        $date->setTimezone(new DateTimeZone(MLShop::gi()->getTimeZone()));

        return $date->format('Y-m-d H:i:s');
    }

    public static function getOutOfSyncOrdersArray($iOffset = 0 ,$blCount = false){
        $oCollection = Mage::getModel('sales/order')->getCollection();
        /* @var $oCollection Mage_Sales_Model_Resource_Order_Collection */
        $oSelect = $oCollection->getSelect();
        /* @var $oSelect Varien_Db_Select */
        $oSelect
            ->joinRight(array('magnalister_orders' => 'magnalister_orders'), 'main_table.increment_id = magnalister_orders.current_orders_id')
            ->where("main_table.status != magnalister_orders.status and mpID = '" . MLModule::gi()->getMarketPlaceId() . "'");
            
        if($blCount){
            return $oCollection->count();
        }  else {
            $oSelect->limit(100 ,$iOffset);
            $aOut = array();
            foreach ($oCollection as $oShopOrder) {
                $aOut[] = $oShopOrder->getIncrementId();
            }
            return $aOut;
        }
    }
    public function getShippingDateTime() {
        $sShipDate = $this->getShopOrder()->getShipmentsCollection()->getLastItem()->getUpdatedAt();
        if (empty($sShipDate)) {//no shipping found, walk status history
            foreach ($this->getShopOrder()->getStatusHistoryCollection()->getItemsByColumnValue('status', $this->getShopOrderStatus()) as $oHistory) {
                $sShipDate = empty($sShipDate) ? $oHistory->getCreatedAt() : $sShipDate;
                if ($oHistory->getEntityName() == 'shipment') {//is shipment, force!
                    $sShipDate = $oHistory->getCreatedAt();
                    break;
                }
            }
        }

        // DateTimes in Magento are stored in Database in UTC timezone
        $date = new DateTime($sShipDate, new DateTimeZone('UTC'));
        $date->setTimezone(new DateTimeZone(MLShop::gi()->getTimeZone()));

        return empty($sShipDate) ? date('Y-m-d H:i:s') : $date->format('Y-m-d H:i:s');
    }

    public function getShippingDate() {
        return substr($this->getShippingDateTime(),0,10);
    }

    public function getShippingCarrier() {
        $sDefaultCarrier = $this->getDefaultCarrier();
        if ($sDefaultCarrier == '-1') {
            return $this->getShopOrderCarrierOrShippingMethod();
        } elseif ($sDefaultCarrier == '') {
            return null;
        } else {
            return $sDefaultCarrier;
        }
    }

    public function getShopOrderCarrierOrShippingMethod() {
        $oOrder = $this->getShopOrder();
        $oShip = $oOrder->getShipmentsCollection()->getLastItem();
        $oTrack = $oShip->getTracksCollection()->getLastItem();
        return $oTrack->getTitle() !== null ? $oTrack->getTitle() : $this->getModul()->getConfig('orderstatus.carrier.default');
    }

    public function getShippingTrackingCode() {
        $oOrder = $this->getShopOrder();
        $oShip = $oOrder->getShipmentsCollection()->getLastItem();
        $oTrack = $oShip->getTracksCollection()->getLastItem();
        return $oTrack->getNumber() !== null ? $oTrack->getNumber() : '';
    }

    public function getEditLink() {
        $oShopOrder=$this->getShopOrder();
        return Mage::getModel('adminhtml/url')->getUrl('adminhtml/sales_order/view/order_id/'.$oShopOrder->getId());
    }
    public function shopOrderByMagnaOrderData($aData) {
        MLShop::gi()->initMagentoStore(MLModule::gi()->getConfig('orderimport.shop'));
        $aOrder = MLHelper::gi('model_shoporder')
            ->init()
            ->setMlOrder($this)
            ->setCurrentOrderData($aData)
            ->execute()
        ;
        $this->oCurrentOrder = null;// to reload after creating
        return $aOrder;
    }

    public function getShopOrderStatusName() {
        return '';
    }

    public function setSpecificAcknowledgeField(&$aOrderParameters, $aOrder) {
        
    }
    
    public static function unAcknowledgeImportedOrder($sSubsystem, $iMpId, $iMorderID, $iShopOrderID, $blSentApiRequest = true) {
        parent::UnAcknowledgeImportedOrder($sSubsystem, $iMpId, $iMorderID, $iShopOrderID, $blSentApiRequest);
        /* @var $oShopOrder Mage_Sales_Model_Order */
        $oShopOrder = Mage::getModel('sales/order')->loadByIncrementId($iShopOrderID);
        while ($oShopOrder->getRelationChildRealId()) {
            $oShopOrder = Mage::getModel('sales/order')->loadByIncrementId($oShopOrder->getRelationChildRealId());
        }
        $oShopOrder->cancel()->save();
        return true;
    }

    public function getShopOrderTotalAmount() {
        return $this->getShopOrder()->getGrandTotal();
    }

    public function getShopOrderTotalTax() {
        return $this->getShopOrder()->getTaxAmount();
    }

    /**
     * @inheritDoc
     */
    public function getShopOrderInvoice($sType) {
        $sPdf = '';

        if ($this->isInvoiceDocumentType($sType)) {
            foreach ($this->getShopOrder()->getInvoiceCollection() as $oInvoice) {
                /**
                 * @var $oInvoice Mage_Sales_Model_Order_Invoice
                 */
                $pdf = Mage::getModel('sales/order_pdf_invoice')->getPdf(array($oInvoice));
                if(!empty($pdf)) {
                    $pdfFile = $pdf->render();
                    $sPdf = base64_encode($pdfFile);
                    break;
                }
            }
        } elseif ($this->isCreditNoteDocumentType($sType)) {
            foreach ($this->getShopOrder()->getCreditmemosCollection() as $oCreditMemo) {
                /**
                 * @var $oCreditMemo Mage_Sales_Model_Order_Creditmemo
                 */
                $pdf = Mage::getModel('sales/order_pdf_creditmemo')->getPdf(array($oCreditMemo));
                if(!empty($pdf)) {
                    $pdfFile = $pdf->render();
                    $sPdf = base64_encode($pdfFile);
                    break;
                }
            }
        }
        return $sPdf;
    }


    public function getShopOrderInvoiceNumber($sType) {
        if ($this->isInvoiceDocumentType($sType)) {
            foreach ($this->getShopOrder()->getInvoiceCollection() as $oInvoice) {
                /**
                 * @var $oInvoice Mage_Sales_Model_Order_Invoice
                 */
                return $oInvoice->getIncrementId();
                break;
            }
        } elseif ($this->isCreditNoteDocumentType($sType)) {
            foreach ($this->getShopOrder()->getCreditmemosCollection() as $oCreditMemo) {
                /**
                 * @var $oCreditMemo Mage_Sales_Model_Order_Creditmemo
                 */
                return $oCreditMemo->getIncrementId();
                break;
            }
        }
        return '';
    }

    public function getShopOrderProducts() {
        return array();
    }

}
