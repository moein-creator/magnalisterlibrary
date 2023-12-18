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
 * $Id$
 *
* (c) 2010 - 2018 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */
MLFilesystem::gi()->loadClass('Form_Controller_Widget_Form_PrepareWithVariationMatchingAbstract');

class ML_Etsy_Controller_Etsy_Prepare_Apply_Form extends ML_Form_Controller_Widget_Form_PrepareWithVariationMatchingAbstract {

    protected function shippingTemplateField(&$aField) {
        $shippingTemplates = $this->callApi('GetShippingTemplates');
        foreach ($shippingTemplates['ShippingTemplates'] as $shippingTemplate) {
            $aField['values'][$shippingTemplate['shippingTemplateId'].''] = $shippingTemplate['title'];
        }
    }

    protected function callGetCategoryDetails($sCategoryId) {
        return MagnaConnector::gi()->submitRequestCached(array(
                'ACTION' => 'GetCategoryDetails',
                'DATA' => array(
                    'CategoryID' => $sCategoryId,
                    'Language' => MLModul::gi()->getConfig('shop.language'),
                )
            )
        );
    }

    public function callAjaxSaveShippingTemplate() {
        $results = array(
            'title'             => MLRequest::gi()->data('title'),
            'origin_country_id' => MLRequest::gi()->data('originCountry'),
            'primary_cost'      => MLRequest::gi()->data('primaryCost'),
            'secondary_cost'    => MLRequest::gi()->data('secondaryCost')
        );
        try {
            $result = MagnaConnector::gi()->submitRequest(array(
                'ACTION' => 'SaveShippingTemplate',
                'DATA'   => $results
            ));
        } catch (MagnaException $e) {
            MLMessage::gi()->addDebug($e);
        }
    }

}
