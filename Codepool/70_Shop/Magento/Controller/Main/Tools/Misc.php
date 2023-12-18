<?php
MLFilesystem::gi()->loadClass('Tools_Controller_Main_Tools_Misc');

class ML_Magento_Controller_Main_Tools_Misc extends ML_Tools_Controller_Main_Tools_Misc {
    
    
    protected function callAjax_magentoProductReflection() {
        ob_start();
        $oProduct = Mage::helper('catalog/product')->getProduct();
        $oReflectionClass = new ReflectionClass($oProduct);
        $aDebug = array();
        while ($oReflectionClass->getParentClass()) {
            $aDebug[$oReflectionClass->getName()] = array(
                'path' => $oReflectionClass->getFileName(),
                'toString' => '<pre>'.htmlentities($oReflectionClass->__toString()).'</pre>',
                'methods' => array(),
            );
            foreach ($oReflectionClass->getMethods() as $oReflectionMethod) {
                if ($oReflectionMethod->class == $oReflectionClass->getName()) {
                    $aDebug[$oReflectionClass->getName()]['methods'][$oReflectionMethod->getName()] = '<pre>'.htmlentities($oReflectionMethod->__toString()).'</pre>';
                }
            }
            $oReflectionClass = $oReflectionClass->getParentClass();
        }
        new dBug($aDebug, '', true);
        return ob_get_clean();
    }
    
}
