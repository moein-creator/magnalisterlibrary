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

class ML_Tradoria_Model_Service_SyncOrderStatus extends ML_Modul_Model_Service_SyncOrderStatus_Abstract {

    protected function getCarrier($oOrder) {
        $sCarrier = $oOrder->getShippingCarrier();
        $aCarriers = MLModul::gi()->getCarrier();
        $sConfig = MLModul::gi()->getConfig('orderstatus.carrier.default');
        if (in_array($sCarrier, $aCarriers) || $sConfig == null || $sConfig === '-1') {
            return $sCarrier;
        } else {
            return $sConfig;
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

        if (
            isset($aError['DETAILS']['ErrorCode'])
            && in_array($aError['DETAILS']['ErrorCode'], array(
                3710, // 3710: order cant be updated (not in edit mode)
                3753, // 3753: order is already canceled
                3752, // 3752:order is already sent
            ))
        ) {
            $this->saveOrderData($aModels[$sMarketplaceOrderId]);
            unset($aModels[$sMarketplaceOrderId]);
        }
    }

    /**
     * In Rakuten the order status for shipped has multiple options
     * so we compare the set values with the current status
     *
     * @param $sShopStatus
     * @return bool
     */
    public function isShipped($sShopStatus) {
        $oModule = MLModule::gi();
        $aShippedState = $oModule->getConfig('orderstatus.shipped');

        return in_array($sShopStatus, array_values($aShippedState));
    }


    protected function extendShippedRequest(&$aRequest, $sShopOrderStatus, $oOrder = null) {
        $oModule = MLModule::gi();
        $aShippedState = $oModule->getConfig('orderstatus.shipped');

        $sKey = array_search($sShopOrderStatus, $aShippedState);
        $aCountry = $oModule->getConfig('orderstatus.shipped.country');
        $aCity = $oModule->getConfig('orderstatus.shipped.city');
        $aStreet = $oModule->getConfig('orderstatus.shipped.streetandnr');
        $aZip = $oModule->getConfig('orderstatus.shipped.zip');

        $aRequest['Country'] = $aCountry[$sKey];
        $aRequest['City'] = $aCity[$sKey];
        $aRequest['Street'] = $aStreet[$sKey];
        $aRequest['Zip'] = $aZip[$sKey];
    }
}
