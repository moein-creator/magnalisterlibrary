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
MLFilesystem::gi()->loadClass('Ebay_Model_Service_ImportOrders');
class ML_WooCommerceEbay_Model_Service_ImportOrders extends ML_Ebay_Model_Service_ImportOrders {

    public function canDoOrder(ML_Shop_Model_Order_Abstract $oOrder, &$aOrder) {
        $aOrderData = $oOrder->get('orderdata');
        $aOrderAllData = $oOrder->get('orderData');
        try {
            $oOrder->getShopOrderObject();
            $orderExistInShop = true;
        } catch (Exception $exception) {
            $orderExistInShop = false;
        }
        if (   isset($aOrderData['AddressSets']['Main']['EMail'])
            && isset($aOrder['AddressSets']['Main']['EMail'])
            && $aOrderData['AddressSets']['Main']['EMail'] == $aOrder['AddressSets']['Main']['EMail']
            && isset($aOrderAllData['Order']['Currency'])
            && isset($aOrder['Order']['Currency'])
            && $aOrderAllData['Order']['Currency'] == $aOrder['Order']['Currency']
            && (
                MLModule::gi()->getConfig('importonlypaid') != '1'
                ||
                $this->IfOnlyPaidOrderCouldBeExtended($aOrder, $aOrderAllData)
            )
            && $orderExistInShop
        ) {
            return 'Extend existing order - same customer address';
        } elseif ($oOrder->get('orders_id') === null || !$orderExistInShop) {
            return 'Create order';
        } else {
            //throw new Exception('Order already exists');
            throw MLException::factory('Model_Service_ImportOrders_OrderExist')->setShopOrder($oOrder);
        }
    }

}
