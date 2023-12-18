<?php

use Magna\Library\MLContainer;

MLFilesystem::gi()->loadClass('Shopify_Controller_Frontend_Do_ShopifyProductCache');
class ML_Shopify_Controller_Do_ShopifyProductCacheByHook extends ML_Shopify_Controller_Frontend_Do_ShopifyProductCache {

    protected $sType = 'HookProductAddOrUpdate';
    protected $sMethodOfUpdate = 'hook';

    protected function getUpdatedProductsData() {
        $data = json_decode(file_get_contents('php://input'), true);
        return array($data);
    }

}
