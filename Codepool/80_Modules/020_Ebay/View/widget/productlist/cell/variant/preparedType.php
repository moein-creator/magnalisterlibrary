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
/* @var $this  ML_Productlist_Controller_Widget_ProductList_Abstract */
/* @var $oList ML_Productlist_Model_ProductList_Abstract */
/* @var $oProduct ML_Shop_Model_Product_Abstract */
if (!class_exists('ML', false))
    throw new Exception();
if ($this instanceof ML_Productlist_Controller_Widget_ProductList_Abstract) {
    try {
        $aI18n = MLI18n::gi()->get(ucfirst(MLModule::gi()->getMarketPlaceName()) . '_Productlist_Cell_aPreparedType');
        $iCurrencyId = $this->getPreparedInfo($oProduct, 'currencyid');
        $sCValue = !empty($iCurrencyId) ? current($this->getPreparedInfo($oProduct, 'currencyid')) : ''; // determine if the Item is prepared at all
        $sEpid = $this->getPreparedInfo($oProduct, 'epid');
        $sEValue = !empty($sEpid) ? current($this->getPreparedInfo($oProduct, 'epid')) : '';
        if (empty($sCValue))
            echo '&mdash;';
        else echo strlen($sEValue) > 0 ? $aI18n['Matched'] : $aI18n['NotMatched'];
    } catch (Exception $oEx) {
        echo $oEx->getMessage();
    }
}