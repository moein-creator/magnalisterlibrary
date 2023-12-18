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

MLFilesystem::gi()->loadClass('Etsy_Helper_Model_Service_Product');

class ML_ShopifyEtsy_Helper_Model_Service_Product extends ML_Etsy_Helper_Model_Service_Product {

    protected function getCategoryAttributes() {
        $aVariants = $this->oVariant->getVariatonDataOptinalField(array('name', 'value', 'valueid'));
        $shopVariants = $this->oPrepare->get('shopVariation');
        // Attributes for simple Items
        if (empty($aVariants)) {
            foreach ($shopVariants as $key => $shopVariations) {
                if (strpos($shopVariations['Code'], 'pp_') === 0) {
                    $fAttVal = $this->oProduct->getAttributeValue($shopVariations['Code']);
                    $aVariants[] = array(
                        'valueid' => current(array_keys($fAttVal)),
                        'name' => $shopVariations['AttributeName'],
                        'value' => current($fAttVal)
                    );
                }
            }
        }
        // Extension for Shopify: oPrepare->get('shopVariation') gives back shop variation keys
        // like 'color_black', which can't be assigned to vaueid like 'black'
        // from oVariant->getVariatonDataOptinalField(array('name', 'value', 'valueid'));
        foreach ($shopVariants as &$sv) {
            foreach ($sv['Values'] as &$val) {
                if (strpos($val['Shop']['Key'], '_') === false)
                    continue;
                $val['Shop']['Key'] = substr($val['Shop']['Key'], strpos($val['Shop']['Key'], '_') + 1);
            }
        }
        # End Extension
        $propertyValue = '';
        $propertyName = '';
        $propertyId = '';
        $results = array();
        $valueIds = array();

        foreach ($shopVariants as $key => $shopVariations) {
            if (is_array($shopVariations['Values'])) {
                foreach ($shopVariations['Values'] as $shopVariationKey => $shopVariationValue) {
                    foreach ($aVariants as $aVariant) {

                        if (
                            $shopVariationValue['Shop']['Key'] == $aVariant['valueid']
                            && strtolower($shopVariationValue['Shop']['Value']) == strtolower($aVariant['value'])
                        ) {
                            $propertyId = explode('-', $shopVariationValue['Marketplace']['Key'])[0];
                            if (strpos($shopVariationValue['Marketplace']['Key'], '-') !== false) {
                                $valueIds = array(explode('-', $shopVariationValue['Marketplace']['Key'])[1]);
                            } else {
                                $valueIds = array(explode('-', $shopVariationValue['Marketplace']['Key']));
                            }
                        }

                        if ($key == 'Custom1') {
                            $propertyValue = $aVariant['value'];
                            $propertyName = $aVariant['name'];
                            $propertyId = 513;
                            $valueIds = array();
                        } elseif ($key == 'Custom2') {
                            $propertyValue = $aVariant['value'];
                            $propertyName = $aVariant['name'];
                            $propertyId = 514;
                            $valueIds = array();
                        }
                    }
                }
            } else {
                // Attributes stored directly in the Preparation
                if (strpos($shopVariations['Values'], '-')) {
                    list($propertyId, $valueIds) = explode('-', $shopVariations['Values']);
                    $valueIds = array($valueIds);
                    $propertyName = $shopVariations['AttributeName'];
                    $propertyValue = '';
                }
            }

            // if not matched value is found don't add it to the request
            if (empty($propertyId)) {
                continue;
            }

            $results["property_values"][] =
                array(
                    "property_id" => $propertyId,
                    "value_ids" => $valueIds,
                    "property_name" => ucfirst($propertyName),
                    "values" => array($propertyValue),
                );
            unset($propertyName);
        }

        return json_encode($results);
    }
}
