<?php
/* @var $this  ML_Productlist_Controller_Widget_ProductList_Abstract */
/* @var $oList ML_Productlist_Model_ProductList_Abstract */
/* @var $oProduct ML_Shop_Model_Product_Abstract */
if (!class_exists('ML', false))
    throw new Exception();
?>
<?php if ($this instanceof ML_Productlist_Controller_Widget_ProductList_Abstract) {
    $oModel = MLDatabase::factory('tradoria_prepare')->set('products_id', $oProduct->get('id'));
    if ($oModel->exists()) {
        $iCategoryId = $oModel->get('PrimaryCategory');
        $cat = MLDatabase::getTableInstance('tradoria_categoriesmarketplace')->set('categoryid', $iCategoryId);
        $cat->getCategoryPath();
        $data = $cat->data();

        echo $data['categoryname'];
    } else {
        echo MLI18n::gi()->Productlist_Cell_sNotPreparedYet;
    }
}