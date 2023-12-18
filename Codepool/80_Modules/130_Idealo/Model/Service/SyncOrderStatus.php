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

class ML_Idealo_Model_Service_SyncOrderStatus extends ML_Modul_Model_Service_SyncOrderStatus_Abstract {
	
    protected function manipulateRequest($aRequest){
        $aRequest['SUBSYSTEM'] = 'ComparisonShopping';
        $aRequest['SEARCHENGINE'] = $this->getMarketPlaceName();
        $aConfig = MLModul::gi()->getConfig();
        if($aRequest['ACTION'] === 'CancelShipment'){
            foreach($aRequest['DATA'] as &$aOrder){
                $aOrder['Reason'] = $aConfig['orderstatus.cancelreason'];
                $aOrder['Comment'] = $aConfig['orderstatus.cancelcomment'];
            }
        }
        return $aRequest;
    }

    /**
     * @param $oOrder ML_Shop_Model_Order_Abstract
     */
    public function otherActions($oOrder) {
        $aOrderData = $oOrder->get('data');
        if (!isset($aOrderData['refund']) && MLModul::gi()->idealoHaveDirectBuy() && $oOrder->getShopOrderStatus() === MLModul::gi()->getConfig('orderstatus.refund')) {
            $aRequest = array(
                'ACTION' => 'DoRefund',
                'DATA' => array(
                    array(
                        'MOrderID' => $oOrder->get('special'),
                    )
                )
            );

            try {
                $aResponse = MagnaConnector::gi()->submitRequest($aRequest);
                $aOrderData['refund'] = 'requested';
                $oOrder->set('data', $aOrderData);
            } catch (MagnaException $oEx) {
                $aErrorData = array(
                    'MOrderID' => $oOrder->get('special'),
                );
                if (is_numeric($oEx->getCode())) {
                    $aErrorData['origin'] = 'idealo';
                } else {
                    $aErrorData['origin'] = 'magnalister';
                }

                MLErrorLog::gi()->addError(0, ' ', $oEx->getMessage(), $aErrorData);
            }
        }
    }
}
