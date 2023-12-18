<?php

use Magna\Library\MLContainer;

MLFilesystem::gi()->loadClass('Shopify_Controller_Frontend_Do_ShopifyProductCache');
class ML_Shopify_Controller_Do_ShopifyDeleteProductCacheByHook extends ML_Shopify_Controller_Frontend_Do_ShopifyProductCache {

    protected $sType = 'HookDeleteProduct';

    protected $sMethodOfUpdate = 'hook';

    protected function getUpdatedProductsData() {
        $data = json_decode(file_get_contents('php://input'), true);
        return array($data);
    }

    public function execute() {
        $iStartTime = microtime(true);
        try {
            $aProducts = $this->getUpdatedProductsData();
            foreach ($aProducts as $aProduct) {
                try {
                    $aProduct['MethodOfUpdate'] = $this->sMethodOfUpdate;
                    MLShopifyAlias::getProductModel()
                        ->loadByShopProduct($aProduct)->delete() //insert master product
                    ;

                    $this->out('## Product id: '.$aProduct['id'].' is deleted'."\n");
                } catch (\Exception $oEx) {
                    $this->out('## A problem occurred by deleting product id: '.$aProduct['id']."\n".$oEx->getMessage()."\n".$oEx->getTraceAsString());
                    MLMessage::gi()->addDebug($oEx);
                }
            }
        } catch (Exception $oEx) {
            $this->out( $oEx->getMessage().'<br />');
        }
    }

}
