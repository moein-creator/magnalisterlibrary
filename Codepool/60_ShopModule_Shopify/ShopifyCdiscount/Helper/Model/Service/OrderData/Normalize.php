<?php

MLFilesystem::gi()->loadClass('Cdiscount_Helper_Model_Service_OrderData_Normalize');

class ML_ShopifyCdiscount_Helper_Model_Service_OrderData_Normalize extends ML_Cdiscount_Helper_Model_Service_OrderData_Normalize {

    protected function getShippingCode($aTotal) {
        $sShippingMethod = MLModul::gi()->getConfig('orderimport.shippingmethod');
        if (!empty($sShippingMethod)) {
            if ('textfield' == $sShippingMethod) {
                $sShipping = MLModul::gi()->getConfig('orderimport.shippingmethod.name');
                return $sShipping == '' ? $aTotal['Code'] : $sShipping;
            } else if ('matching' == $sShippingMethod) {
                if (in_array($aTotal['Code'], array('', 'none', 'None'))) {
                    return MLModul::gi()->getMarketPlaceName();
                } else {
                    return $aTotal['Code'];
                }
            }

            return $sShippingMethod;
        }

        return MLModul::gi()->getMarketPlaceName();
    }

    protected function getPaymentCode($aTotal) {
       $sPaymentMethod =  MLModul::gi()->getConfig('orderimport.paymentmethod');
        if (!empty($sPaymentMethod)) {
            if ('textfield' == $sPaymentMethod) {
                $sPayment = MLModul::gi()->getConfig('orderimport.paymentmethod.name');
                $sPaymentMethod = $sPayment == '' ? MLModul::gi()->getMarketPlaceName() : $sPayment;
            } else if ('matching' == $sPaymentMethod) {
                $sPaymentMethod = MLModul::gi()->getMarketPlaceName();
            }

            return $sPaymentMethod;
        }

        return MLModul::gi()->getMarketPlaceName();
    }

    protected function normalizeOrder() {
        parent::normalizeOrder();
        $this->aOrder['Order']['PaymentStatus'] = MLModul::gi()->getConfig('orderimport.paymentstatus');

        return $this;
    }

}
