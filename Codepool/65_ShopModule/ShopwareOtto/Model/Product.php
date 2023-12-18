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
 * (c) 2010 - 2020 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

MLFilesystem::gi()->loadClass('Shopware_Model_Product');

class ML_ShopwareOtto_Model_Product extends ML_Shopware_Model_Product {


    public function getDeliveryType() {
        // TODO: Return delivery type
    }

    /**
     * @return string
     */
    public function getName() {
        $this->load();
        $aName = Shopware()->Modules()->Articles()->sGetArticleNameByOrderNumber($this->get('productssku'), true);
        $oPHelper = MLHelper::gi('model_product');
        /* @var $oPHelper ML_Shopware_Helper_Model_Product */
        if(Shopware()->Shop()->getDefault() || count(MLFormHelper::getShopInstance()->getDescriptionValues()) === 1){// If configured Shop is default shop or there is only one language in Shopware, we should use main title from s_articles table like shopware frontend
            $sMasterProductName = $aName['articleName'];
        } else {
            $sMasterProductName = $oPHelper->translate($this->getLoadedProduct()->getId(), 'article', 'name', $aName['articleName']);
        }

        return $sMasterProductName;
    }

}
