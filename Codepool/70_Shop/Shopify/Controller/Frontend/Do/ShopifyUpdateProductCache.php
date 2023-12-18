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
 * (c) 2010 - 2020 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

use Magna\Library\MLContainer;
use Magna\Library\MLLogger;
use Shopify\API\Application\Request\Products\CountProducts\CountProductsParams;
use Shopify\API\Application\Request\Products\ListOfProducts\ListOfProductsParams;

MLFilesystem::gi()->loadClass('Shopify_Controller_Frontend_Do_ShopifyProductCache');

/**
 * It gets only updated product data from Shopify and update them in magnalister
 */
class ML_Shopify_Controller_Frontend_Do_ShopifyUpdateProductCache extends ML_Shopify_Controller_Frontend_Do_ShopifyProductCache {

    protected $sType = 'UpdatedProductAddOrUpdate';

    protected $sConfigKeyUpdatedAtMin = 'updateShopifyUpdatedAtMin';

    protected $sConfigKeyPage = 'updateShopifyProductPage';

    public function __construct() {
        parent::__construct();
        $iCount = $this->getTotalCountOfProduct();
        $this->iLimitationOfIteration *= ceil($iCount / 2000);
    }


    /**
     * get date to limit products and get only updated product
     * @return string
     */
    protected function getUpdatedAtMin() {
        $sDate = parent::getUpdatedAtMin();
        if ($sDate === null) {
            $oTimeZone = $this->getTimeZone();
            if ($oTimeZone === null) {
                $oUpdateTime = new DateTime('now');
            } else {
                $oUpdateTime = new DateTime('now', $oTimeZone);
            }
            $oUpdateTime->modify('-1 hour');
            $sDate = $oUpdateTime->format('c');
            MLDatabase::factory('config')->set('mpid', 0)->set('mkey', $this->sConfigKeyUpdatedAtMin)->set('value', $sDate);
        }
        return parent::getUpdatedAtMin();
    }

}