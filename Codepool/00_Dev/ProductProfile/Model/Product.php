<?php
$sShop=MLSetting::gi()->get('sProductProfileShop');
MLFilesystem::gi()->loadClass( $sShop . '_Model_Product');
if(MLSetting::gi()->get('blProductProfileUseOriginal')){
    eval('class ML_ProductProfile_Model_Product_'.$sShop.' extends ML_'.$sShop.'_Model_Product {}');
    $sClass='ML_'.MLSetting::gi()->get('sProductProfileShop').'_Model_Product';
}else{
    MLFilesystem::gi()->loadClass('ProductProfile_Model_Product_'.$sShop);
    $sClass='ML_ProductProfile_Model_Product_'.$sShop;
}
eval('class ML_ProductProfile_Model_Product extends '.$sClass.' {}');
