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
 * (c) 2010 - 2023 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */
include_once(M_DIR_LIBRARY . 'request/shopware/ShopwareTax.php');

use library\request\shopware\ShopwareTax;

MLFilesystem::gi()->loadClass('ShopwareCloud_Controller_Frontend_Do_ShopwareCloudAbstractCache');

class ML_ShopwareCloud_Controller_Frontend_Do_ShopwareCloudDeleteTaxCache extends ML_ShopwareCloud_Controller_Frontend_Do_ShopwareCloudAbstractCache {

    protected $sType = 'TaxDelete';
    protected $iLimitationOfIteration = 10;

    public function __construct() {
        parent::__construct();
        $this->shopwareEntityRequest = new ShopwareTax($this->sShopId);
    }


    protected function getEntities($preparedFilters, $type = '') {
        $result = array();
        if ($type === 'ShopwareTaxID') {
            $result = $this->shopwareEntityRequest->getShopwareTaxes('/api/search/tax', 'POST', $preparedFilters);
        }
        if ($type === 'ShopwareTaxRuleID') {
            $result = $this->shopwareEntityRequest->getShopwareTaxRule('/api/search/tax-rule', 'POST', $preparedFilters);
        }

        return $result;
    }

    protected function getDBEntityIds() {
        $oQuery = MLDatabase::factory('ShopwareCloudTax')->getList()->getQueryObject()->limit($this->iStart, $this->iShopwareCloudLimitPerPage)->where('`ShopwareTaxID` IS NOT NULL');
        $this->iCount = $oQuery->getCount();
        $aEntities = array();
        foreach ($oQuery->getResult() as $entity) {
            if (!isset($aEntities['ShopwareTaxID']) || !in_array($entity['ShopwareTaxID'], $aEntities['ShopwareTaxID'])) {
                $aEntities['ShopwareTaxID'][] = $entity['ShopwareTaxID'];
            }
        }
        $oQuery = MLDatabase::factory('ShopwareCloudTaxRule')->getList()->getQueryObject()->limit($this->iStart, $this->iShopwareCloudLimitPerPage)->where('`ShopwareTaxRuleID` IS NOT NULL');
        $this->iCount = $oQuery->getCount();
        foreach ($oQuery->getResult() as $entity) {
            if (!isset($aEntities['ShopwareTaxRuleID']) || !in_array($entity['ShopwareTaxRuleID'], $aEntities['ShopwareTaxRuleID'])) {
                $aEntities['ShopwareTaxRuleID'][] = $entity['ShopwareTaxRuleID'];
            }
        }
        return $aEntities;
    }

    protected function updateEntity($data) {
        foreach ($data as $key => $ids) {
            if ($key === 'ShopwareTaxID' && count($ids) > 0) {
                MLDatabase::getDbInstance()->query(
                    '
DELETE T, TR
FROM `magnalister_shopwarecloud_tax` T 
LEFT JOIN `' . MLDatabase::factory('ShopwareCloudTaxRule')->getTableName() . '` TR  ON TR.ShopwareTaxID = T.ShopwareTaxID  
WHERE T.ShopwareTaxID IN("' . implode('","', $ids) . '")', true
                );
            }

            if ($key === 'ShopwareTaxRuleID' && count($ids) > 0) {
                MLDatabase::getDbInstance()->query(
                    '
DELETE TR
FROM `magnalister_shopwarecloud_tax_rule` TR 
WHERE TR.ShopwareTaxRuleID IN("' . implode('","', $ids) . '")', true
                );
            }
        }
    }

}
