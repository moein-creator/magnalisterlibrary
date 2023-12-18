<?php
/*
 * 888888ba                 dP  .88888.                    dP
 * 88    `8b                88 d8'   `88                   88
 * 88aaaa8P' .d8888b. .d888b88 88        .d8888b. .d8888b. 88  .dP  .d8888b.
 * 88   `8b. 88ooood8 88'  `88 88   YP88 88ooood8 88'  `"" 88888"   88'  `88
 * 88     88 88.  ... 88.  .88 Y8.   .88 88.  ... 88.  ... 88  `8b. 88.  .88
 * dP     dP `88888P' `88888P8  `88888'  `88888P' `88888P' dP   `YP `88888P'
 *
 *                            m a g n a l i s t e r
 *                                        boost your Online-Shop
 *
 *   -----------------------------------------------------------------------------
 *   @author magnalister
 *   @copyright 2010-2022 RedGecko GmbH -- http://www.redgecko.de
 *   @license Released under the MIT License (Expat)
 *   -----------------------------------------------------------------------------
 */

MLFilesystem::gi()->loadClass('Modul_Helper_Model_Service_OrderData_Normalize');

class ML_Amazon_Helper_Model_Service_OrderData_Normalize extends ML_Modul_Helper_Model_Service_OrderData_Normalize {
    
    protected $oModul = null;
    protected function getModul(){
        if($this->oModul === null ){
            $this->oModul = MLModul::gi();
        }
        return $this->oModul;
    }

    protected function normalizeTotalTypeShipping(&$aTotal) {
        parent::normalizeTotalTypeShipping($aTotal);
        if ($this->aOrder['MPSpecific']['FulfillmentChannel'] == 'AFN') { //amazon payed and shipped
            if (isset($this->aOrder['MPSpecific']['Carrier'])) {
                $aTotal['Data']['Carrier'] = $this->aOrder['MPSpecific']['Carrier'];
            }
            if (isset($this->aOrder['MPSpecific']['Trackingcode'])) {
                $aTotal['Data']['Trackingcode'] = $this->aOrder['MPSpecific']['Trackingcode'];
            }
        }
        return $this;
    }
    
    protected function getPaymentCode($aTotal, $sPaymentMethodConfigKey = 'orderimport.paymentmethod') {//till this version amazon doesn't send any paymentmethod information
        $sPaymentMethodConfigKey = 
            ($this->aOrder['MPSpecific']['FulfillmentChannel'] == 'AFN' && $this->getModul()->getConfig('orderimport.fbapaymentmethod') !== null) 
            ? 'orderimport.fbapaymentmethod'
            : 'orderimport.paymentmethod'
        ;
        return parent::getPaymentCode($aTotal, $sPaymentMethodConfigKey);
    }
    
    protected function getShippingCode($aTotal) {//till this version amazon doesn't send any paymentmethod information
        if ($this->aOrder['MPSpecific']['FulfillmentChannel'] == 'AFN' && $this->getModul()->getConfig('orderimport.fbashippingmethod') !== null) { //amazon payed and shipped
            $sStatusKey = 'orderimport.fbashippingmethod';
        }else{
            $sStatusKey = 'orderimport.shippingmethod';
        }
        if ('textfield' === $this->getModul()->getConfig($sStatusKey)) {
            $sPayment = $this->getModul()->getConfig($sStatusKey.'.name');
            return $sPayment == '' ? MLModul::gi()->getMarketPlaceName() : $sPayment;
        } elseif($this->getModul()->getConfig($sStatusKey) === null){//'matching'
            return MLModul::gi()->getMarketPlaceName();
        }else{
            return $this->getModul()->getConfig($sStatusKey);
        }
    }
    
    protected function normalizeOrder () {
        parent::normalizeOrder();
        $this->aOrder['Order']['Payed'] = true;
        $this->aOrder['Order']['PaymentStatus'] = MLModul::gi()->getConfig('orderimport.paymentstatus');
        if ($this->aOrder['MPSpecific']['FulfillmentChannel'] == 'AFN') { //amazon payed and shipped
            $this->aOrder['Order']['Shipped'] = true;
            $this->aOrder['Order']['Status'] = MLModul::gi()->getConfig('orderstatus.fba');
        }
        return $this;
    }
    
    protected function normalizeProduct (&$aProduct, $fDefaultTax) {
        parent::normalizeProduct($aProduct, $fDefaultTax);
        $aProduct['StockSync'] = 
               (MLModul::gi()->getConfig('stocksync.frommarketplace') == 'rel' && $this->aOrder['MPSpecific']['FulfillmentChannel'] != 'AFN')
            || MLModul::gi()->getConfig('stocksync.frommarketplace') == 'fba'
        ;
        return $this;
    }
    
    protected function normalizeMpSpecific () {
        parent::normalizeMpSpecific();
        if (array_key_exists('FulfillmentChannel', $this->aOrder['MPSpecific'])) {
            switch ($this->aOrder['MPSpecific']['FulfillmentChannel']){
                case 'MFN-Prime':
                    $sTitle = MLModul::gi()->getMarketPlaceName(false).' Prime'; 
                    break;
                case 'AFN':
                    $sTitle = MLModul::gi()->getMarketPlaceName(false).'FBA';
                    break;
                default :
                    $sTitle = MLModul::gi()->getMarketPlaceName(false);
                    break;
            }
            $this->aOrder['MPSpecific']['InternalComment'] =
                sprintf(MLI18n::gi()->get('ML_GENERIC_AUTOMATIC_ORDER_MP_SHORT'), $sTitle)."\n".
                MLI18n::gi()->get('ML_LABEL_MARKETPLACE_ORDER_ID').': '.$this->aOrder['MPSpecific']['MOrderID']."\n\n"
                .$this->aOrder['Order']['Comments'];
        }
        return $this;
    }

    /**
     * Filling $aOrder['AddressSets']['Shipping']['UstId'] with $aOrder['AddressSets']['Main']['UstId']
     */
    protected function normalizeAddressSets() {
        parent::normalizeAddressSets();
        if (!isset($this->aOrder['AddressSets']['Shipping']['UstId']) && isset($this->aOrder['AddressSets']['Main']['UstId'])) {
            $this->aOrder['AddressSets']['Shipping']['UstId'] = $this->aOrder['AddressSets']['Main']['UstId'];
        }
        return $this;
    }
}
