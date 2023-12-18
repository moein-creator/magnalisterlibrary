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
 * $Id$
 *
 * (c) 2010 - 2019 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */
//require_once(MLFilesystem::getOldLibPath('php/lib/MagnaConnector.php'));
class ML_Configuration_Model_Modul extends ML_Modul_Model_Modul_Abstract {

    /**
     * constructor prepares MagnaConnector
     */
    public function __construct() {
        if (class_exists('MagnaConnector')) {
            MLShop::gi()->getShopInfo();
        }
    }

    public function getMarketPlaceName($blIntern = true) {
        return $blIntern ? 'global' : 'Global';
    }

    public function getMarketPlaceId() {
        return 0;
    }

    /**
     * @return array('configKeyName'=>array('api'=>'apiKeyName', 'value'=>'currentSantizedValue'))
     */
    protected function getConfigApiKeysTranslation() {
        return array();
    }
    public function isConfigured() {
        return true;
    }
    
}
