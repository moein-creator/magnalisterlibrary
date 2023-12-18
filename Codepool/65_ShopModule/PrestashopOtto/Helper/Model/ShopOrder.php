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


MLFilesystem::gi()->loadClass('Prestashop_Helper_Model_ShopOrder');

class ML_PrestashopOtto_Helper_Model_ShopOrder extends ML_Prestashop_Helper_Model_ShopOrder {

    public function UpdateAnouncedOrder($updateProductQuantity = false) {

        // update order status
            try {
                $oOrder = $this->oOrder;
                if (is_object($this->oExistingShopOrder)) {
                    $oPrestashopOrder  = $this->oExistingShopOrder;
                    $this->oCurrentShopOrder  = $this->oExistingShopOrder;
                    //updating order status
                    $iNewOrderStatus = (int)$oOrder->get('status');
                    if ($iNewOrderStatus !== $oPrestashopOrder->current_state) {
                        $this->setState($iNewOrderStatus);
                    }
                }
            } catch (Exception $oExc) {
                MLLog::gi()->add(MLSetting::gi()->get('sCurrentOrderImportLogFileName'), array(
                    'MOrderId'  => MLSetting::gi()->get('sCurrentOrderImportMarketplaceOrderId'),
                    'PHP'       => get_class($this).'::'.__METHOD__.'('.__LINE__.')',
                    'Exception' => $oExc->getMessage()
                ));
            }

            // update stock of products
            if ($updateProductQuantity) {
                /** @var ML_Shopware_Model_Product $oProduct */
                $oProduct = MLProduct::factory();
                foreach ($this->aNewData['Products'] as $product)
                    if ($oProduct->getByMarketplaceSKU($product['SKU'])->exists()
                        && MLModul::gi()->getConfig('stocksync.frommarketplace') === 'rel') {
                        $oProduct->setStock($oProduct->getStock() + $product['Quantity']);
                    }
            }
        }


}