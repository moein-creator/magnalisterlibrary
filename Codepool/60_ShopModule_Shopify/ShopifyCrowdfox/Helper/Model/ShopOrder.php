<?php

MLFilesystem::gi()->loadClass('Shopify_Helper_Model_ShopOrder');

class ML_ShopifyCrowdfox_Helper_Model_ShopOrder extends ML_Shopify_Helper_Model_ShopOrder {

    /**
     * if no payment status is set it reuturn 17 as open status 
     */
    protected function getPaymentStatus() {
        if (!isset($this->aNewData['Order']['PaymentStatus']) || empty($this->aNewData['Order']['PaymentStatus'])) {
            return MLModul::gi()->getConfig('paymentstatus') ?: 17;
        } else {
            return $this->aNewData['Order']['PaymentStatus'];
        }
    }
	
}
