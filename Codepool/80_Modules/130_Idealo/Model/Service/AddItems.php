<?php
/**
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
 * (c) 2010 - 2019 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

class ML_Idealo_Model_Service_AddItems extends ML_Modul_Model_Service_AddItems_Abstract {

    protected function getSubSystem() {
        return 'ComparisonShopping';
    }

    protected function getProductArray() {
        /* @var $oPrepareHelper ML_Idealo_Helper_Model_Table_Idealo_PrepareData */
        $oPrepareHelper = MLHelper::gi('Model_Table_idealo_PrepareData');
        $aMasterProducts = array();
        foreach ($this->oList->getList() as $oProduct) {
            /* @var $oProduct ML_Shop_Model_Product_Abstract */
            foreach ($this->oList->getVariants($oProduct) as $oVariant) {
                /* @var $oVariant ML_Shop_Model_Product_Abstract */
                if ($this->oList->isSelected($oVariant)) {
                    $aPrepareFields = array(
                        'ItemTitle' => array('optional' => array('active' => true)),
                        'SKU' => array('optional' => array('active' => true)),
                        'Description' => array('optional' => array('active' => true)),
                        'ShippingCostMethod' => array('optional' => array('active' => true)),
                        'ShippingCost' => array('optional' => array('active' => true)),
                        'ShippingTime' => array('optional' => array('active' => true)),
                        'FulFillmentType' => array('optional' => array('active' => true)),
                        'TwoManHandlingFee' => array('optional' => array('active' => true)),
                        'DisposalFee' => array('optional' => array('active' => true)),
                        'PaymentMethod' => array('optional' => array('active' => true)),
                        'Image' => array('optional' => array('active' => true)),
                        'Price' => array('optional' => array('active' => true)),
                        'Quantity' => array('optional' => array('active' => true)),
                        'BasePrice' => array('optional' => array('active' => true)),
                        'BasePriceString' => array('optional' => array('active' => true)),
                        'ItemUrl' => array('optional' => array('active' => true)),
                        'Checkout' => array('optional' => array('active' => true)),
                        'Manufacturer' => array('optional' => array('active' => true)),
                        'ItemWeight' => array('optional' => array('active' => true)),
                        'ManufacturerPartNumber' => array('optional' => array('active' => true)),
                        'EAN' => array('optional' => array('active' => true)),
                        'MerchantCategory' => array('optional' => array('active' => true)),
                    );
                    $iVariantID = $oVariant->get('id');
                    $aMasterProducts[$iVariantID] = $oPrepareHelper
                        ->setPrepareList(null)
                        ->setProduct($oVariant)
                        ->getPrepareData($aPrepareFields, 'value');
                    $aMasterProducts[$iVariantID]['TwoManHandlingFee'] = (string)number_format(priceToFloat($aMasterProducts[$iVariantID]['TwoManHandlingFee']), 2, '.', '');
                    $aMasterProducts[$iVariantID]['DisposalFee'] = (string)number_format(priceToFloat($aMasterProducts[$iVariantID]['DisposalFee']), 2, '.', '');

                    $aMasterProducts[$iVariantID]['Checkout'] = MLModul::gi()->idealoHaveDirectBuy() ? $aMasterProducts[$iVariantID]['checkout'] : false;
                    unset($aMasterProducts[$iVariantID]['checkout']);
                    // shipping-cost = itemWeight
                    if ($aMasterProducts[$iVariantID]['ShippingCostMethod'] === '__ml_weight') {
                        $aMasterProducts[$iVariantID]['ShippingCost'] = $aMasterProducts[$iVariantID]['ItemWeight'];
                    }
                    unset($aMasterProducts[$iVariantID]['ShippingCostMethod']);
                    if ($aMasterProducts[$iVariantID]['ShippingTime']['type'] === '__ml_lump') {
                        $aMasterProducts[$iVariantID]['ShippingTime'] = $aMasterProducts[$iVariantID]['ShippingTime']['value'];
                    } else {
                        $aMasterProducts[$iVariantID]['ShippingTime'] = $aMasterProducts[$iVariantID]['ShippingTime']['type'];
                    }

                    // unset checkout status fields if deactivated
                    if (!$aMasterProducts[$iVariantID]['Checkout']) {MLMessage::gi()->addDebug(__LINE__.':'.microtime(true), array($aMasterProducts[$iVariantID]));
                        unset($aMasterProducts[$iVariantID]['FulFillmentType']);
                        unset($aMasterProducts[$iVariantID]['TwoManHandlingFee']);
                        unset($aMasterProducts[$iVariantID]['DisposalFee']);
                    }


                    // only submit TwoManHandlingFee and DisposalFee if its "Spedition" -> "Haulage"
                    if (array_key_exists('FulFillmentType', $aMasterProducts[$iVariantID])
                        && $aMasterProducts[$iVariantID]['FulFillmentType'] !== 'Spedition'
                    ) {
                        unset($aMasterProducts[$iVariantID]['TwoManHandlingFee']);
                        unset($aMasterProducts[$iVariantID]['DisposalFee']);
                    }

                    foreach ($aMasterProducts[$iVariantID] as $sKey => $mValue) {
                        if ($mValue === null) {
                            unset($aMasterProducts[$iVariantID][$sKey]);
                        }
                        if ($sKey === 'Image' && is_array($mValue)) {
                            $aImages = array();
                            foreach ($mValue as $sImage) {
                                try {
                                    $aImage = MLImage::gi()->resizeImage($sImage, 'products', 500, 500);
                                    $aImages[] = array('URL' => $aImage['url']);
                                } catch (Exception $ex) {
                                    // Happens if image doesn't exist.
                                }
                            }
                            $aMasterProducts[$iVariantID][$sKey] = $aImages;
                        }
                    }
                }
            }
        }
        return $aMasterProducts;
    }

    public function uploadItems() {
        return true;
    }
}
