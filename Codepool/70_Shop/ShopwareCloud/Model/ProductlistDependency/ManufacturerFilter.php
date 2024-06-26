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
include_once(M_DIR_LIBRARY . 'request/shopware/ShopwareUnit.php');

use library\request\shopware\ShopwareUnit;

MLFilesystem::gi()->loadClass('Shop_Model_ProductListDependency_ManufacturerFilter_Abstract');
class ML_ShopwareCloud_Model_ProductListDependency_ManufacturerFilter extends ML_Shop_Model_ProductListDependency_ManufacturerFilter_Abstract {
    
    /**
     * possible filter values
     * @var null | array key => value
     */
    protected $aFilterValues = null;
    
    /**
     * @param ML_Database_Model_Query_Select $mQuery
     * @return void
     */
    public function manipulateQuery($mQuery) {
        $sFilterValue = $this->getFilterValue();
        if (
            !empty($sFilterValue)
            && array_key_exists($sFilterValue, $this->getFilterValues())
        ) {            
            $mQuery->where('p.`ShopwareManufacturerId` =\''. (string)$sFilterValue.'\'');
        }
    }

    /**
     * key => value for manufacturers
     * @return array
     */
    protected function getFilterValues() {
        if ($this->aFilterValues === null) {
            $aOut = array(
                '' => array(
                    'value' => '',
                    'label' => sprintf(MLI18n::gi()->get('Productlist_Filter_sEmpty'), MLI18n::gi()->get('Shopware_Productlist_Filter_sManufacturer'))
                )
            );
            $oManufacturer = $this->getProductManufacrerTranslationList();
            foreach ($oManufacturer as $aValue) {
                $aOut[$aValue['ShopwareProductManufacturerID']] = array(
                    'value' => $aValue['ShopwareProductManufacturerID'],
                    'label' => $aValue['ShopwareName'],
                );
            }
            $this->aFilterValues = $aOut;
        }
        return $this->aFilterValues;
    }

    /**
     * @return array|false
     */
    protected function getProductManufacrerTranslationList()
    {
        $sLangId = MLShopwareCloudTranslationHelper::gi()->getLanguage();
        $oProductManufacrerTranslation = MLDatabase::getDbInstance()->fetchArray(
            "SELECT ShopwareProductManufacturerID, ShopwareName FROM
                                                       `magnalister_shopwarecloud_product_manufacturer_translation` 
                                                   WHERE `ShopwareLanguageID` = '$sLangId';"
        );
        return $oProductManufacrerTranslation;
    }

}
