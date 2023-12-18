<?php

MLFilesystem::gi()->loadClass('Ebay_Model_Service_UpdateOrders');

class ML_ShopwareEbay_Model_Service_UpdateOrders extends ML_Ebay_Model_Service_UpdateOrders {

    public function canDoOrder(ML_Shop_Model_Order_Abstract $oOrder, &$aOrder) {
        if ($oOrder->get('orders_id') !== null) { // only existing orders
            /* @var $oOrder ML_Shopware_Model_Order */
            $aMessage = array();
            $aIsActiv = MLModul::gi()->getConfig('update.paymentstatus');
            if (
                    !isset($aIsActiv) //if someone didn't set this configuration , it doesn't update payment status
                    ||
                    ((bool)$aIsActiv  && in_array($oOrder->getShopPaymentStatus(), MLModul::gi()->getConfig('updateable.paymentstatus'), true))
            ) {
                $oOrder->setUpdatablePaymentStatus(true);
                $aMessage[] = 'Update payment status';
            } else {
                $oOrder->setUpdatablePaymentStatus(false);
            }
            $aIsActiv = MLModul::gi()->getConfig('update.orderstatus');
            $aUpdateableStatusses = MLModul::gi()->getConfig('updateable.orderstatus');
            if ((bool)$aIsActiv && is_array($aUpdateableStatusses) && in_array($oOrder->getShopOrderStatus(), $aUpdateableStatusses, true)) {
                $oOrder->setUpdatableOrderStatus(true);
                $aMessage[] = 'Update order status';
            } else {
                $oOrder->setUpdatableOrderStatus(false);
            }

            return empty($aMessage) ? 'cannot update order and payment status' : implode($aMessage, ', ');
        } else {
            throw new Exception("Order doesn't exist");
        }
    }

}
