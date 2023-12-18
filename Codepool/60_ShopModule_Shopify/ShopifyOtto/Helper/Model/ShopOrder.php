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

MLFilesystem::gi()->loadClass('Shopify_Helper_Model_ShopOrder');

class ML_ShopifyOtto_Helper_Model_ShopOrder extends ML_Shopify_Helper_Model_ShopOrder {

    public function updateAnnouncedOrder($updateProductQuantity = false) {
        // update order status
        return $this;
        $aData = $this->aNewData;
        $ShopifyOrder = $this->oExistingOrder;

        $status = ($ShopifyOrder->fulfillment_status == null) ? 'open' : $ShopifyOrder->fulfillment_status;

        $iNewOrderStatus = $aData['Order']['Status'];

        if ($iNewOrderStatus !== $status && $status === 'open' && $iNewOrderStatus === 'fulfilled') {

            $oFulfillment = new Fulfillment();
            $oFulfillment->setOrderId($this->oExistingOrder->id);
            $oFulfillment->setLocationId('??');///???
            $oFulfillment->setStatus($iNewOrderStatus);
            $response = $this->application->getFulFillmentRequest($this->token)->setFulFillmentService($oFulfillment);
            if ($response->getCode() == 201) {
                return $this;
            } else {
                $this->logShopifyError($response);
                throw new Exception('There is a problem to create fulfillment data: '.$response->getBody());
            }
        }

        // update stock of products
        if ($updateProductQuantity) {
            /** @var ML_Shopify_Model_Product $oProduct */
            $oProduct = MLProduct::factory();

            foreach ($this->aNewData['Products'] as $product) {
                if ($oProduct->getByMarketplaceSKU($product['SKU'])->exists()
                    && MLModul::gi()->getConfig('stocksync.frommarketplace') === 'rel') {
                    // this returns empty string. Check ML_Shopify_Model_Product setStock()
                    $oProduct->setStock($oProduct->getStock() + $product['Quantity']);
                }
            }
        }
    }
}
