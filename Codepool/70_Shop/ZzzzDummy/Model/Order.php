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

/**
 * @todo
 */
class ML_ZzzzDummy_Model_Order extends ML_Shop_Model_Order_Abstract {
    protected $oCurrentOrder = null;

    protected function getShopOrderObject() {
        return $this->oCurrentOrder;
    }

    public function getShopOrderStatus() {
        return '';
    }

    public function getShopOrderLastChangedDate() {
        return '';
    }

    public static function getOutOfSyncOrdersArray($iOffset = 0, $blCount = false) {
        $aOut = array();
        return $aOut;
    }

    public function getShippingDateTime() {
        return '';
    }

    public function getShippingDate() {
        return '';
    }

    public function getShippingCarrier() {
        return '';
    }

    public function getShippingTrackingCode() {
        return '';
    }

    public function getEditLink() {
        return '';
    }

    public function getShopOrderStatusName() {
        return '';
    }

    public function shopOrderByMagnaOrderData($aData) {
        return '';
    }

    public function setSpecificAcknowledgeField(&$aOrderParameters, $aOrder) {
        return '';
    }

    public function getShopOrderTotalAmount() {
        return '';
    }

    public function getShopOrderTotalTax() {
        return '';
    }
}
