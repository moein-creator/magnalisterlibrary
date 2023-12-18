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

class ML_Ebay_Model_Service_SyncOrderStatus extends ML_Modul_Model_Service_SyncOrderStatus_Abstract {

    protected function getCarrier($oOrder) {
        $sCarrier = $oOrder->getShippingCarrier();
        $aCarriers = MLModul::gi()->getCarrier();
        if (in_array($sCarrier, $aCarriers) || MLModul::gi()->getConfig('orderstatus.carrier.default') == null || MLModul::gi()->getConfig('orderstatus.carrier.default') == '-1') {
            return $sCarrier;
        } else {
            return MLModul::gi()->getConfig('orderstatus.carrier.default');
        }
    }

    protected function postProcessError($aError, &$aModels) {
        $sFieldId = 'MOrderID';
        $sMarketplaceOrderId = null;
        if (isset($aError['DETAILS']) && isset($aError['DETAILS'][$sFieldId])) {
            $sMarketplaceOrderId = $aError['DETAILS'][$sFieldId];
        }
        if (empty($sMarketplaceOrderId)) {
            return;
        }

        // it will return if order don't belongs to customer or is to old
        if (isset($aError['ERRORCODE']) && $aError['ERRORCODE'] == 1450279354) {
            $this->saveOrderData($aModels[$sMarketplaceOrderId]);
            unset($aModels[$sMarketplaceOrderId]);
        }
    }

    /**
     * @param $oOrder ML_Shop_Model_Order_Abstract
     */
    public function otherActions($oOrder) {
        $aOrderData = $oOrder->get('data');
        if (!isset($aOrderData['refund']) &&  MLModul::gi()->isPaymentProgramAvailable() && in_array($oOrder->getShopOrderStatus(), MLModul::gi()->getConfig('refundstatus'), true)) {
            $aReasons = MLModul::gi()->getConfig('refundreason');
            $aComments = MLModul::gi()->getConfig('refundcomment');
            $iIndex = array_search($oOrder->getShopOrderStatus(), MLModul::gi()->getConfig('refundstatus'), true);
            $aRequest = array(
                'ACTION' => 'DoRefund',
                'MagnalisterOrderId' => $oOrder->get('special'),
                'ReasonOfRefund' => $aReasons[$iIndex],
                'Comment' => $aComments[$iIndex],
            );

            try {
                MagnaConnector::gi()->submitRequest($aRequest);
                $aOrderData['refund'] = 'requested';
                $oOrder->set('data', $aOrderData);
            } catch (MagnaException $oEx) {
                $aErrorData = array(
                    'MOrderID' => $oOrder->get('special'),
                );

                if (is_numeric(substr($oEx->getMessage(), 0, 5))) {
                    $aErrorData['origin'] = 'eBay';
                } else {
                    $aErrorData['origin'] = 'magnalister';
                }

                MLErrorLog::gi()->addError(0, ' ', $oEx->getMessage(), $aErrorData);
            }
        }
    }

}
