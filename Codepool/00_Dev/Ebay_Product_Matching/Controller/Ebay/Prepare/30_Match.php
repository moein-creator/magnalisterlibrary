<?php
///**
// * 888888ba                 dP  .88888.                    dP
// * 88    `8b                88 d8'   `88                   88
// * 88aaaa8P' .d8888b. .d888b88 88        .d8888b. .d8888b. 88  .dP  .d8888b.
// * 88   `8b. 88ooood8 88'  `88 88   YP88 88ooood8 88'  `"" 88888"   88'  `88
// * 88     88 88.  ... 88.  .88 Y8.   .88 88.  ... 88.  ... 88  `8b. 88.  .88
// * dP     dP `88888P' `88888P8  `88888'  `88888P' `88888P' dP   `YP `88888P'
// *
// *                          m a g n a l i s t e r
// *                                      boost your Online-Shop
// *
// * -----------------------------------------------------------------------------
// * (c) 2010 - 2019 RedGecko GmbH -- http://www.redgecko.de
// *     Released under the MIT License (Expat)
// * -----------------------------------------------------------------------------
// */
//
//MLFilesystem::gi()->loadClass('Productlist_Controller_Widget_ProductList_Selection');
//
//class ML_Ebay_Controller_Ebay_Prepare_Match extends ML_Productlist_Controller_Widget_ProductList_Selection {
//
//    public static function getTabTitle() {
//        return MLI18n::gi()->get('Ebay_Product_Matching');
//    }
//
//    public function __construct() {
//        parent::__construct();
//        try {
//            $mExecute = $this->oRequest->get('view');
//            if ($mExecute == 'unprepare') {
//                $oModel = MLDatabase::factory('ebay_prepare');
//                $oList = MLDatabase::factory('selection')->set('selectionname', 'match')->getList();
//                foreach ($oList->get('pid') as $iPid) {
//                    $oModel->init()->set('products_id', $iPid)->delete();
//                }
//            } elseif (
//                is_array($mExecute)
//                && !empty($mExecute)
//                && (in_array('reset_matching', $mExecute))
//            ) {
//                $oModel = MLDatabase::factory('ebay_prepare');
//                $oList = MLDatabase::factory('selection')->set('selectionname', 'match')->getList();
//                foreach ($oList->get('pid') as $iPid) {
//                    $oModel
//                        ->init()
//                        ->set('products_id', $iPid)
//                        ->set('epid', '')
//                        ->save();
//                }
//            }
//
//        } catch (Exception $oEx) {
//            //echo $oEx->getMessage();
//        }
//    }
//
//    public function getProductListWidget() {
//        $sSubView = MLRequest::gi()->get('controller');
//        $aItem = explode('_', $sSubView);
//        $sExecute = array_pop($aItem);
//        try {
//            return $this->getChildController($sExecute)->render();
//        } catch (Exception $oExc) {
//            if ($oExc->getCode() == 1550742082) {
//                MLMessage::gi()->addFatal($oExc);
//                return $this;
//            }
//            if ($sExecute !== 'match') {
//                MLRequest::gi()->set('controller', str_replace('_'.$sExecute, '', $sSubView), true);
//            }
//            return parent::getProductListWidget();
//        }
//    }
//
//    /**
//     * @return ML_Core_Controller_Abstract
//     * @throws Exception
//     */
//    public function render() {
//        return $this->getProductListWidget();
//    }
//}
