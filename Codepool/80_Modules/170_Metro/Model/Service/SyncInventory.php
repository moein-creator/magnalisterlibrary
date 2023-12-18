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

class ML_Metro_Model_Service_SyncInventory extends ML_Modul_Model_Service_SyncInventory_Abstract {

    /**
     * Special case of Metro:
     * add Shipping Costs on the top of the price
     *
     * @param ML_Shop_Model_Product_Abstract $oProduct
     * @param array $aResponse api-response of current product
     * @return array for request eg. array('price' => (float))
     */
    protected function getPrice(ML_Shop_Model_Product_Abstract $oProduct, $aResponse) {
        $oPrepareHelper = MLHelper::gi('Model_Table_Metro_PrepareData');
        $oPrepareHelper
            ->setPrepareList(null)
            ->setProduct($oProduct);
        $aProductData = $oPrepareHelper->getPrepareData(array(
            'ShippingCost' => array('optional' => array('active' => true)),
            'ProductPrice' => array('optional' => array('active' => true)),
        ), 'value');

        // If NetPrice dont use GrossPrice
        if (isset($aResponse['NetPrice'])) {
            $aPrice['NetPrice'] = $oProduct->getSuggestedMarketplacePrice(MLModule::gi()->getPriceObject(), false);
            $aPrice['NetPrice'] += (float)round(($aProductData['ShippingCost'] / ((100 + (float)$oProduct->getTax()) / 100)), 2);;
        } else {
            $aPrice = parent::getPrice($oProduct, $aResponse);
            $aPrice['Price'] += (float)$aProductData['ShippingCost'];
        }

        return $aPrice;
    }

    /**
     *
     * @param $item
     * @return mixed
     */
    protected function getProductTitle($item) {
        $productData = unserialize($item['ProductData']);
        return $productData['Title'];
    }
}
