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

MLFilesystem::gi()->loadClass('Ebay_Controller_Widget_ProductList_Ebay_Abstract');

class ML_Ebay_Controller_Ebay_Prepare_Apply extends ML_Ebay_Controller_Widget_ProductList_Ebay_Abstract {
    public static function getTabTitle() {
        return MLI18n::gi()->get('ebay_prepare_apply');
    }

    public function __construct() {
        parent::__construct();
        try {
            $mExecute = $this->oRequest->get('view');

            // Undo Preparation
            if ($mExecute == 'unprepare') {
                $oModel = MLDatabase::factory('ebay_prepare');
                $oList = MLDatabase::factory('selection')->set('selectionname', 'apply')->getList();
                foreach ($oList->get('pid') as $iPid) {
                    $oModel->init()->set('products_id', $iPid)->delete();
                }
            } elseif ( // partly undo preparation (set to null = use always from web shop)
                is_array($mExecute)
                && !empty($mExecute)
                && (
                    in_array('reset_title', $mExecute)
                    || in_array('reset_subtitle', $mExecute)
                    || in_array('reset_description', $mExecute)
                    || in_array('reset_pictures', $mExecute)
                    || in_array('reset_strikeprices', $mExecute)
                )
            ) {
                $oModel = MLDatabase::factory('ebay_prepare');
                $oList = MLDatabase::factory('selection')->set('selectionname', 'apply')->getList();

                $aProductIds = $oList->get('pid');
                $aProductIdsChunk = array_chunk($aProductIds, 100);

                $aData = array();

                if (in_array('reset_title', $mExecute)) {
                    $aData['Title'] = null;
                }
                if (in_array('reset_subtitle', $mExecute)) {
                    $aData['Subtitle'] = null;
                }
                if (in_array('reset_description', $mExecute)) {
                    $aData['Description'] = null;
                }
                if (in_array('reset_pictures', $mExecute)) {
                    $aData['PictureURL'] = null;
                    $aData['VariationPictures'] = null;
                }
                if (in_array('reset_strikeprices', $mExecute)) {
                    $aData['StrikePrice'] = 'false';
                }

                foreach ($aProductIdsChunk as $aChunk) {
                    $sQuery = " AND products_id IN (";
                    foreach ($aChunk as $iProductId) {
                        $sQuery .= "'".MLDatabase::getDbInstance()->escape($iProductId)."', ";
                    }
                    $sQuery = rtrim($sQuery, ", ");
                    $sQuery .= ") ";

                    MLDatabase::getDbInstance()->update(
                        $oModel->getTableName(),
                        $aData,
                        array('mpID' => MLModul::gi()->getMarketPlaceId()),
                        $sQuery
                    );
                }

            }
        } catch (Exception $oEx) {
            //            echo $oEx->getMessage();
        }
    }

    /**
     * @return $this|ML_Core_Controller_Abstract
     * @throws Exception
     */
    public function getProductListWidget() {
        return ML_Productlist_Controller_Widget_ProductList_Selection::getProductListWidget();
    }

    /**
     * @throws Exception dont need in this view, shows only prepared value
     */
    public function getPriceObject(ML_Shop_Model_Product_Abstract $oProduct) {
        throw new Exception('price config can not loaded yet.');
    }

}
