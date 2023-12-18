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

class ML_Cdiscount_Model_Service_SyncOrderStatus extends ML_Modul_Model_Service_SyncOrderStatus_Abstract {
    
    protected function postProcessError($aError, &$aModels) {
        $sMarketplaceOrderId = null;
        if (isset($aError['DETAILS']) && isset($aError['DETAILS'][$this->sOrderIdentifier])) {
            $sMarketplaceOrderId = $aError['DETAILS'][$this->sOrderIdentifier];
        }
        if (empty($sMarketplaceOrderId)) {
            return;
        }

        if (   isset($aError['DETAILS']['ErrorType'])
            && in_array ($aError['DETAILS']['ErrorType'], array(
                'OrderStateIncoherent', // OrderStateIncoherent: Shipped state is not possible to set at this stage
            ))
        ) {
            $this->saveOrderData($aModels[$sMarketplaceOrderId]);
            unset($aModels[$sMarketplaceOrderId]);
        }
    }

    protected function getCarrier($oOrder) {
        $sConfigCarrier = MLModul::gi()->getConfig('orderstatus.shipmethod.select');

        switch ($sConfigCarrier) {
            case 'matchShopShippingOptions':
                $sCarrier = $oOrder->getShopOrderCarrierOrShippingMethodId();

                $aCarrierShippingMethodMatching = MLModul::gi()->getConfig('orderstatus.shipmethod.matching');
                $sMatchedKey = $this->findTheConfigKey($aCarrierShippingMethodMatching, $sCarrier, 'shopCarrier');
                echo print_m($sMatchedKey, __LINE__);
                if (isset($sMatchedKey)) {
                    if($aCarrierShippingMethodMatching[$sMatchedKey]['marketplaceCarrier'] === 'Other'){
                        return $oOrder->getShopOrderCarrierOrShippingMethod();
                    }
                    return $aCarrierShippingMethodMatching[$sMatchedKey]['marketplaceCarrier'];
                } else {
                    MLLog::gi()->add('SyncOrderStatus_'.MLModul::gi()->getMarketPlaceId().'_WrongConfiguration', array(
                        'Problem' => '#carrier-code# is not matched correctly!',
                    ));
                    return null;
                }
            case 'orderFreetextField':
                $mData = $oOrder->getAdditionalOrderField('carrierCode');
                if ($mData !== null) {
                    return $mData;
                } else {
                    MLLog::gi()->add('SyncOrderStatus_'.MLModul::gi()->getMarketPlaceId().'_WrongConfiguration', array(
                        'Problem' => '#carrier-code# is not filled in order detail of shop or it is empty!',
                    ));
                    return null;
                }
            case 'freetext':
                $sTextField = MLModul::gi()->getConfig('orderstatus.shipmethod.freetext');
                if (!empty($sTextField)) {
                    return $sTextField;
                } else {
                    MLLog::gi()->add('SyncOrderStatus_'.MLModul::gi()->getMarketPlaceId().'_WrongConfiguration', array(
                        'Problem' => 'For #carrier-code# "freetext" configuration right text field is empty!',
                    ));
                    return null;
                }
            default:
            {
                //order attribute freetext field(only in Shopware 5)
                if (strpos($sConfigCarrier, 'a_') === 0) {
                    return $oOrder->getAttributeValue($sConfigCarrier);
                }

                //Cdiscount predefined carrier
                if (!empty($sConfigCarrier)) {
                    return $sConfigCarrier;
                }
                MLLog::gi()->add('SyncOrderStatus_'.MLModul::gi()->getMarketPlaceId().'_WrongConfiguration', array(
                    'Problem' => '#carrier-code# is not configured!',
                ));
                return null;
            }
        }

    }
    
    protected function getShippingMethod($oOrder) {
        $sConfigShippingMethod = MLModul::gi()->getConfig('orderstatus.shipmethod.select');
        switch ($sConfigShippingMethod) {
            case 'matchShopShippingOptions':
                $sShippingMethod = $oOrder->getShopOrderCarrierOrShippingMethodId();
                $aCarrierShippingMethodMatching = MLModul::gi()->getConfig('orderstatus.shipmethod.matching');
                $sMatchedKey = $this->findTheConfigKey($aCarrierShippingMethodMatching, $sShippingMethod, 'shopCarrier');
                if (isset($sMatchedKey)) {
                    return $aCarrierShippingMethodMatching[$sMatchedKey]['marketplaceValue'];
                } else {
                    MLLog::gi()->add('SyncOrderStatus_'.MLModul::gi()->getMarketPlaceId().'_WrongConfiguration', array(
                        'Problem' => '#ship-method# is not matched correctly!',
                    ));
                    return null;
                }
            case 'orderFreetextField':
                $mData = $oOrder->getAdditionalOrderField('shipMethod');
                if ($mData !== null) {
                    return $mData;
                } else {
                    MLLog::gi()->add('SyncOrderStatus_'.MLModul::gi()->getMarketPlaceId().'_WrongConfiguration', array(
                        'Problem' => '#ship-method# is not filled in order detail of shop or it is empty!',
                    ));
                    return null;
                }
            case 'freetext':
                $sTextField = MLModul::gi()->getConfig('orderstatus.shipmethod.freetext');
                if (!empty($sTextField)) {
                    return $sTextField;
                } else {
                    MLLog::gi()->add('SyncOrderStatus_'.MLModul::gi()->getMarketPlaceId().'_WrongConfiguration', array(
                        'Problem' => 'For #ship-method# "freetext" configuration right text field is empty!',
                    ));
                    return null;
                }
            default:
            {
                //order attribute freetext field(only in Shopware 5)
                if (strpos($sConfigShippingMethod, 'a_') === 0) {
                    return $oOrder->getAttributeValue($sConfigShippingMethod);
                }
                MLLog::gi()->add('SyncOrderStatus_'.MLModul::gi()->getMarketPlaceId().'_WrongConfiguration', array(
                    'Problem' => '#ship-method# is not configured!',
                ));
                return null;
            }
        }

    }

    protected function findTheConfigKey($aConfigMatching, $sSearchedKey, $sKey) {
        foreach ($aConfigMatching as $iIndex => $aMatching) {
            if ($aMatching[$sKey] === (string)$sSearchedKey) {
                return $iIndex;
            }
        }
        return null;
    }
}
