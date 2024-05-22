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

use Shopify\API\Application\Request\Products\ListOfProductCollections\ListOfProductCollectionsParams;

MLFilesystem::gi()->loadClass('Core_Controller_Abstract');

class ML_Shopify_Controller_Frontend_Do_ShopifyProductCollectionCache extends ML_Core_Controller_Abstract {

    public function __construct() {
        throw new Exception('CRON is deprecated');
        parent::__construct();
    }

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
            for ($countOfIteration = 10; $countOfIteration > 0; $countOfIteration--) {
                $aListOfProductCollectionsParams = (new ListOfProductCollectionsParams)->setLimit(MLSetting::gi()->ShopifyProductRequestLimit);
                if (!empty($this->getCursor())) {
                    $aListOfProductCollectionsParams->setCursor($this->getCursor());
                }
/* it should edit with this solution
{
  collections(first: 2, after:"eyJsYXN0X2lkIjo4MzQ5NjY5Nzk0OSwibGFzdF92YWx1ZSI6IjgzNDk2Njk3OTQ5In0=") {
    edges {
      node {
        title
        products(first: 2) {
          edges {
            node {
              title
            }
            cursor
          }
        }
        updatedAt
      }
      cursor
    }
    pageInfo {
      hasNextPage
    }
  }
}
*/
                $aProductCollection = MLShopifyAlias::getProductHelper()->getProductCollectionListFromShopify2($aListOfProductCollectionsParams);
                //                var_dump($aProductCollection);
                if (isset($aProductCollection['edges'])) {
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
            }
            if (MLRequest::gi()->data('blDebug') === 'true' || MLSetting::gi()->blDebug) {
                echo str_replace("\\n", "\n", print_r(ML_Shopify_Helper_ShopifyInterfaceRequestHelper::gi()->getLogPerRequest(), true));
            }
            $this->out("\n\nComplete (".microtime2human(microtime(true) - $iStartTime).").\n");
        } catch (Throwable $oEx) {
            $this->out($oEx->getMessage()."\n");
        }
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