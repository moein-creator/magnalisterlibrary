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

MLFilesystem::gi()->loadClass('Core_Controller_Abstract');

/**
 *
 * @see View: /Codepool/90_System/Tools/View/Main/Tools/Products/Search.php
 */
class ML_Tools_Controller_Main_Tools_Products_Search extends ML_Core_Controller_Abstract {

    protected $aParameters = array('controller');
    protected $aMessages = array();

    public function __construct() {
        parent::__construct();
        //        $iRequestMp = $this->getRequestedMpid();
        //        if($iRequestMp != null) {
        //            ML::gi()->init(array('mp' => $iRequestMp));
        //            if (!MLModul::gi()->isConfigured()) {
        //                throw new Exception('module is not configured');
        //            }
        //        }
    }

    protected function getRequestedSku() {
        return $this->getRequest('sku');
    }

    protected function getRequestedMpid() {
        return $this->getRequest('mpid');
    }

    protected function getProduct($blMaster) {
        $sSku = $this->getRequestedSku();
        if (!empty($sSku)) {
            return MLProduct::factory()->getByMarketplaceSKU($sSku, $blMaster);
        }
    }

    protected function getPriceType() {
        $sType = $this->getRequest('pricetype');
        return $sType !== '' ? $sType : null;
    }

    protected function getMasterProductFieldAndMethods(ML_Shop_Model_Product_Abstract $oProduct) {
        return array(
            //'getAttributeValue("pd_BasePriceUnitName")' => $oProduct->getAttributeValue('pd_BasePriceUnitName'),
            //'getAttributeValue("pd_BasePriceUnit")'     => $oProduct->getAttributeValue('pd_BasePriceUnit'),
            //'getAttributeValue("pd_BasePriceValue")'    => $oProduct->getAttributeValue('pd_BasePriceValue'),
            //'getAttributeValue("pd_BasePriceUnitShort")'    => $oProduct->getAttributeValue('pd_BasePriceUnitShort'),
            //'getAttributeValue("pd_BasePriceBasicUnit")'    => $oProduct->getAttributeValue('pd_BasePriceBasicUnit'),
            'getSku()'                                  => $oProduct->getSku(),
            'getName()'                                 => $oProduct->getName(),
            'getDescription()'                          => htmlentities($oProduct->getDescription()),
            'getStock()'                                => $oProduct->getStock(),
            'getShopPrice()'                            => $oProduct->getShopPrice(),
            'getEAN()'                                  => $oProduct->getEAN(),
            'getTax()'                                  => $oProduct->getTax(),
            'getManufacturer()'                         => $oProduct->getManufacturer(),
            'getManufacturerPartNumber()'               => $oProduct->getManufacturerPartNumber(),
            'getImages()'                               => $oProduct->getImages(),
            'isSingle()'                                => $oProduct->isSingle(),
            'getVariantCount()'                         => $oProduct->getVariantCount(),
            'isActive()'                                => $oProduct->isActive(),
            'getVariatonDataOptinalField()'             => $oProduct->getVariatonDataOptinalField(array('code', 'valueid', 'name', 'value')),
            'getFrontendLink()'                         => $oProduct->getFrontendLink(),
            'getCategoryPath()'                         => $oProduct->getCategoryPath(),
            'getCategoryIds()'                          => $oProduct->getCategoryIds(),
            'getCategoryStructure()'                    => $oProduct->getCategoryStructure(),
            'getEditLink()'                             => $oProduct->getEditLink(),
            'getImageUrl()'                             => $oProduct->getImageUrl(),
            'getMetaDescription()'                      => $oProduct->getMetaDescription(),
            'getMetaKeywords()'                         => $oProduct->getMetaKeywords(),
            'getWeight()'                               => $oProduct->getWeight(),
        );
    }


    public function getVariantProductFieldAndMethods(ML_Shop_Model_Product_Abstract $oProduct) {
        return array(
            //'getAttributeValue("pd_BasePriceUnitName")' => $oProduct->getAttributeValue('pd_BasePriceUnitName'),
            //'getAttributeValue("pd_BasePriceUnit")'     => $oProduct->getAttributeValue('pd_BasePriceUnit'),
            //'getAttributeValue("pd_BasePriceValue")'    => $oProduct->getAttributeValue('pd_BasePriceValue'),
            //'getAttributeValue("pd_BasePriceUnitShort")'    => $oProduct->getAttributeValue('pd_BasePriceUnitShort'),
            //'getAttributeValue("pd_BasePriceBasicUnit")'    => $oProduct->getAttributeValue('pd_BasePriceBasicUnit'),
            'getSku()'                      => $oProduct->getSku(),
            'getName()'                     => $oProduct->getName(),
            'getDescription()'              => !is_string($oProduct->getDescription()) ? $oProduct->getDescription() : htmlentities($oProduct->getDescription()),
            'getStock()'                    => $oProduct->getStock(),
            'getShopPrice()'                => $oProduct->getShopPrice(),
            'getImages()'                   => $oProduct->getImages(),
            'getTax()'                      => $oProduct->getTax(),
            'isActive()'                    => $oProduct->isActive(),
            'getVariatonDataOptinalField()' => $oProduct->getVariatonDataOptinalField(array('code', 'valueid', 'name', 'value')),
            'getFrontendLink()'             => $oProduct->getFrontendLink(),
            'getCategoryPath()'             => $oProduct->getCategoryPath(),
            'getCategoryIds()'              => $oProduct->getCategoryIds(),
            'getCategoryStructure()'        => $oProduct->getCategoryStructure(),
            'getEditLink()'                 => $oProduct->getEditLink(),
            'getImageUrl()'                 => $oProduct->getImageUrl(),
            'getMetaDescription()'          => $oProduct->getMetaDescription(),
            'getMetaKeywords()'             => $oProduct->getMetaKeywords(),
            'getWeight()'                   => $oProduct->getWeight(),
        );
    }

    public function getVariantProductModuleDependentFieldAndMethods(ML_Shop_Model_Product_Abstract $oProduct, array $aDataToSendToMarketplace) {
        try {
            $aStockConf = MLModule::gi()->getStockConfig($this->getRequest('pricetype'));
            $aDataToSendToMarketplace['getSuggestedMarketplaceStock()'] = $oProduct->getSuggestedMarketplaceStock($aStockConf['type'], $aStockConf['value'], isset($aStockConf['max']) ? $aStockConf['max'] : null);
            $aDataToSendToMarketplace['getSuggestedMarketplacePrice()'] = $oProduct->getSuggestedMarketplacePrice(MLModule::gi()->getPriceObject($this->getRequest('pricetype')), true, true);
            $aDataToSendToMarketplace['getSuggestedMarketplacePrice(net)'] = $oProduct->getSuggestedMarketplacePrice(MLModule::gi()->getPriceObject($this->getRequest('pricetype')), false, true);
            $sShopGroupId = $this->getVolumePriceCustomerGroup($this);
            $aDataToSendToMarketplace['getVolumePrices()'] = array(
                'Gross' => $oProduct->getVolumePrices($sShopGroupId),
                'Net'   => $oProduct->getVolumePrices($sShopGroupId, false),
            );
            $aDataToSendToMarketplace['getEAN()'] = $oProduct->getEAN();
            $aDataToSendToMarketplace['getManufacturer()'] = $oProduct->getManufacturer();
            $aDataToSendToMarketplace['getManufacturerPartNumber()'] = $oProduct->getManufacturerPartNumber();
            $sCountryCode = $this->getRequest('countrycode');
            $aDataToSendToMarketplace['getTax('.$sCountryCode.')'] = $oProduct->getTax(empty($sCountryCode) ? null : array('Shipping' => array('CountryCode' => $sCountryCode)));
            $fPrice = $oProduct->getSuggestedMarketplacePrice(MLModule::gi()->getPriceObject());
            $aDataToSendToMarketplace['getBasePriceString()'] = $oProduct->getBasePriceString($fPrice);
        } catch (\Exception $ex) {
            $this->aMessages[] = $ex;
            $aDataToSendToMarketplace['getSuggestedMarketplacePrice(20)'] = 20;
            $aDataToSendToMarketplace['getBasePriceString(20)'] = $oProduct->getBasePriceString(20);
        }
        return $aDataToSendToMarketplace;
    }

    public function getMasterProductModuleDependentFieldAndMethods(ML_Shop_Model_Product_Abstract $oProduct, array $aDataToSendToMarketplace) {
        try {
            $aDataToSendToMarketplace['getEAN()'] = $oProduct->getEAN();
            $aDataToSendToMarketplace['getManufacturer()'] = $oProduct->getManufacturer();
            $aDataToSendToMarketplace['getManufacturerPartNumber()'] = $oProduct->getManufacturerPartNumber();
            $sCountryCode = $this->getRequest('countrycode');
            $aDataToSendToMarketplace['getTax('.$sCountryCode.')'] = $oProduct->getTax(empty($sCountryCode) ? null : array('Shipping' => array('CountryCode' => $sCountryCode)));
        } catch (\Exception $ex) {
            $this->aMessages[] = $ex;
        }
        return $aDataToSendToMarketplace;
    }

    public function getVolumePriceCustomerGroup() {
        $sShopGroupId = MLModule::gi()->getConfig('volumepriceswebshopcustomergroup');
        if ($sShopGroupId === null) {
            $sShopGroupId = MLModule::gi()->getPriceObject($this->getPriceType())->getPriceConfig()['group'];
        }
        return $sShopGroupId;
    }

}
