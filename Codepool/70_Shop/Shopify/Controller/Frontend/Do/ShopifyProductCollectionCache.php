<?php

use Shopify\API\Application\Request\Products\CountProducts\CountProductsParams;
use Shopify\API\Application\Request\Products\ListOfProductCollections\ListOfProductCollectionsParams;

MLFilesystem::gi()->loadClass('Core_Controller_Abstract');

class ML_Shopify_Controller_Frontend_Do_ShopifyProductCollectionCache extends ML_Core_Controller_Abstract {

    public function renderAjax() {//@todo in future renderAjax could be more clear
        try {
            $this->execute();
            $aAjax = MLSetting::gi()->get('aAjax');
            if (empty($aAjax)) {
                throw new Exception;
            }
        } catch (Exception $oEx) {//if there is no data to be sync or if there is an error
            MLSetting::gi()->add('aAjax', array('success' => true));
        }
        if (MLHttp::gi()->isAjax()) {
            if (MLSetting::gi()->sMainController === null) {//after $this->execute sMainController is null, so we reset again ,
                MLSetting::gi()->set('sMainController', get_class($this));
            }
            $this->finalizeAjax();
        }
    }

    public function render() {
        $this->execute();
    }

    protected function out($sStr) {
        if (!MLHttp::gi()->isAjax()) {//in ajax call in pluin we break maxitems and steps of each request so we don't need echo
            echo $sStr;
        }
        return $this;
    }


    public function execute() {
        $iStartTime = microtime(true);
        try {
            $this->out(
                '#######################################'."\n##\n".
                '## Updating Shopify product collection cache'.
            "\n##\n".'#######################################'."\n");
            $aListOfProductCollectionsParams = (new ListOfProductCollectionsParams)->setLimit(MLSetting::gi()->ShopifyProductRequestLimit);
            if (!empty($this->getCursor())) {
                $aListOfProductCollectionsParams->setCursor($this->getCursor());
            }
            $aProductCollection = MLShopifyAlias::getProductHelper()->getProductCollectionListFromShopify($aListOfProductCollectionsParams);
            if(isset($aProductCollection['edges'])) {
                $oShopifyProductHelper = MLShopifyAlias::getProductHelper();
                foreach ($aProductCollection['edges'] as $aEdge) {
                    try {
                        $sId = $oShopifyProductHelper->updateShopifyCollection($aEdge);
                        if ($sId !== null) {
                            $this->out('## Collection of product id: '.$sId.' is updated'."\n");
                        }
                    } catch (Exception $e) {
                        $this->out('## A problem occurred by updating collection of product id: '.$sId."\n");
                        MLMessage::gi()->addDebug($e);
                    }
                }
                if (!$aProductCollection['pageInfo']['hasNextPage']) {
                    $oTimeZone = new DateTimeZone('UTC');
                    $oUpdateTime = new DateTime('now', $oTimeZone);
                    MLDatabase::factory('config')->set('mpid', 0)->set('mkey', 'shopifyCollectionUpdatedAtMin')->set('value', str_replace('+00:00', 'Z', $oUpdateTime->format('c')))->save();
                    MLDatabase::factory('config')->set('mpid', 0)->set('mkey', 'shopifyProductCollectionCursor')->set('value', '')->save();
                    $this->out(
                        '#######################################'."\n##\n".
                        '## Shopify product collection update process is done'.
                        "\n##\n".'#######################################'."\n");
                } else {
                    MLDatabase::factory('config')->set('mpid', 0)->set('mkey', 'shopifyProductCollectionCursor')->set('value', end($aProductCollection['edges'])['cursor'])->save();
                    $this->out(
                        '#######################################'."\n##\n".
                        '## Updating cursor '.$this->getCursor().' was successful. Next page will be executed in next call automatically'.
                        "\n##\n".'#######################################'."\n");
                }
            } else {
                $this->out('## There could be a problem in collection request'."\n");
            }
        } catch (Exception $oEx) {
            $this->out( $oEx->getMessage().'<br />');
        }
        if(MLRequest::gi()->get('bldebug') === 'true') {
            echo str_replace("\\n", "\n", print_r(ML_Shopify_Helper_ShopifyInterfaceRequestHelper::gi()->getLogPerRequest(), true));
        }
        $this->out("\n\nComplete (".microtime2human(microtime(true) - $iStartTime).").\n");
    }


    /**
     * get date to limit products and get only updated product
     * @return string
     */
    protected function getUpdatedAtMin() {
        return MLDatabase::factory('config')->set('mpid', 0)->set('mkey', 'shopifyCollectionUpdatedAtMin')->get('value');
    }

    /**
     * @return int
     */
    protected function getCursor() {
        return MLDatabase::factory('config')->set('mpid', 0)->set('mkey', 'shopifyProductCollectionCursor')->get('value');
    }
}