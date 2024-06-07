<?php
$sShop=MLSetting::gi()->get('sProductProfileShop');
MLFilesystem::gi()->loadClass( $sShop . '_Helper_Model_Product_Variants');
if(MLSetting::gi()->get('blProductProfileUseOriginal')){
    eval('class ML_ProductProfile_Helper_Model_Product_Variants_'.$sShop.' extends ML_'.$sShop.'_Helper_Model_Product_Variants{}');
    $sClass='ML_'.MLSetting::gi()->get('sProductProfileShop').'_Helper_Model_Product_Variants';
}else{
    MLFilesystem::gi()->loadClass('ProductProfile_Helper_Model_Product_Variants_'.$sShop);
    $sClass='ML_ProductProfile_Helper_Model_Product_Variants_'.$sShop;
}
eval('class ML_ProductProfile_Helper_Model_Product_Variants extends '.$sClass.'{}');
