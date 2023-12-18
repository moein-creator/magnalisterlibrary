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
 * (c) 2010 - 2020 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

use Shopware\Core\Checkout\Order\OrderStates;
use Shopware\Core\Framework\Context;

MLFilesystem::gi()->loadClass('Shopware6_Helper_Model_ShopOrder');

class ML_Shopware6Otto_Helper_Model_ShopOrder extends ML_Shopware6_Helper_Model_ShopOrder {

    public function updateAnnouncedOrder($updateProductQuantity = false) {

        try {
            //$this->oOrder->get('status') returns the status of order in magnalister_orders table
            //$this->oExistingOrder->getStateMachineState()->getTechnicalName() returns the "State_name(status)" of order.repository of Shopware 6 on shopsystem in  table
            if (is_object($this->oExistingOrder)) {
                //if status of order in magnalister_order is not equale to  State_name(status)" of order.repository then update State_name(status)" of order.repository with status of order in magnalister_order
                if ($this->oOrder->get('status') !== $this->oExistingOrder->getStateMachineState()->getTechnicalName()) {
                    $orderData = [
                        'id' => $this->oExistingOrder->getId(),
                    ];
                    $orderData['stateId'] = $this->getOrderStateId($this->oOrder->get('status'), OrderStates::STATE_MACHINE);
                    MLShopware6Alias::getRepository('order')
                            ->update([$orderData], Context::createDefaultContext());
                }
            }
        } catch (Exception $oExc) {
            MLLog::gi()->add(MLSetting::gi()->get('sCurrentOrderImportLogFileName'), array(
                'MOrderId' => MLSetting::gi()->get('sCurrentOrderImportMarketplaceOrderId'),
                'PHP' => get_class($this) . '::' . __METHOD__ . '(' . __LINE__ . ')',
                'Exception' => $oExc->getMessage()
            ));
        }

        // There is no need to manipulation "Order Stock" in Shopware 6 because Shopware 6 updates the stock of products automatically by changing the status.
        /*if ($updateProductQuantity) {
            
        }*/
    }

}
