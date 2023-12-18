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
 * (c) 2010 - 2014 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */
MLFilesystem::gi()->loadClass('Prestashop_Model_ProductList_Abstract');

class ML_PrestashopMeinPaket_Model_ProductList_MeinPaket_Prepare_Prepare extends ML_Prestashop_Model_ProductList_Abstract {

    protected function executeFilter() {
        $this->oFilter
            ->registerDependency('searchfilter')
            ->limit()
            ->registerDependency('categoryfilter')
            ->registerDependency('preparestatusfilter')
            ->registerDependency('prestashopproducttypefilter')
            ->registerDependency('productstatusfilter')
            ->registerDependency('manufacturerfilter')
        ;
        return $this;
    }

    public function getSelectionName() {
        return 'match';
    }

    /**
     * Filters prepared attributes matching data additionally before comparing it to global matching.
     *
     * @param array $preparedData Attributes matching data from Prepare table for product
     * @param string $categoryCode Category code
     */
    protected function filterAMPreparedDataBeforeComparison(&$preparedData, $categoryCode)
    {
        $preparedData = $this->convertOldShopVariationValue($preparedData, $categoryCode);
    }

    /**
     * Gets matched values for selected identifier
     *
     * @param string $sIdentifier Matching identifier (usually category name or ID).
     * @return array|bool
     */
    protected function getMatchedAttributes($sIdentifier)
    {
        $aData = parent::getMatchedAttributes($sIdentifier);
        if (!empty($aData)) {
            $aData = $this->convertOldShopVariationValue($aData, $sIdentifier);
        }

        return $aData;
    }

    protected function convertOldShopVariationValue($oldShopVariation, $sIdentifier)
    {
        /* @var $oHelper ML_MeinPaket_Helper_Model_Service_Product */
        $oHelper = MLHelper::gi('Model_Service_Product');
        return $oHelper->convertOldShopVariationValue($oldShopVariation, $sIdentifier);
    }
}
