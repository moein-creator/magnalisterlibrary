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

MLFilesystem::gi()->loadClass('Prestashop_Model_Product');

class ML_PrestashopOtto_Model_Product extends ML_Prestashop_Model_Product {

    public function getEanDefaultField() {
        $globalEan = MLModule::gi()->getConfig('ean');
        return isset($globalEan) ? $globalEan : 'ean13';
    }

    public function getManufacturerDefaultField() {
        $globalManufacturer = MLModule::gi()->getConfig('manufacturer');
        return isset($globalManufacturer) ? $globalManufacturer : 'manufacturer_name';
    }

    public function getManufacturerPartNumberDefaultField() {
        $globalManPartNumber = MLModule::gi()->getConfig('manufacturerpartnumber');
        return isset($globalManPartNumber) ? $globalManPartNumber : 'reference';
    }

    public function getBrandDefaultField() {
        $globalManufacturer = MLModule::gi()->getConfig('manufacturer');
        return isset($globalManufacturer) ? $globalManufacturer : 'manufacturer_name';
    }

    public function getBulletPointDefaultField() {
        return 'meta_keywords';
    }
}
