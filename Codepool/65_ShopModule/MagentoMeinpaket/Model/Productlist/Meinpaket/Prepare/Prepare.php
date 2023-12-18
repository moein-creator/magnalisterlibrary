<?php


MLFilesystem::gi()->loadClass('Magento_Model_ProductList_Abstract');

class ML_MagentoMeinpaket_Model_ProductList_Meinpaket_Prepare_Prepare extends ML_Magento_Model_ProductList_Abstract {

    public function getSelectionName() {
        return 'match';
    }

    protected function executeFilter() {
        $this->oFilter
            ->registerDependency('magentonovariantsfilter')
            ->registerDependency('searchfilter')
            ->limit()
            ->registerDependency('categoryfilter')
            ->registerDependency('preparestatusfilter')
            ->registerDependency('magentoattributesetfilter')
            ->registerDependency('productstatusfilter')
            ->registerDependency('manufacturerfilter')
            ->registerDependency('magentoproducttypefilter')
            ->registerDependency('magentosaleablefilter')
        ;
        return $this;
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
