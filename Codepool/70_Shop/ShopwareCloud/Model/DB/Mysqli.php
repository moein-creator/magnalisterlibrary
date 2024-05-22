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

MLFilesystem::gi()->loadClass("Database_Model_Db_Mysqli");

class ML_ShopwareCloud_Model_Db_Mysqli extends ML_Database_Model_Db_Mysqli {
    protected $lastPingExecTime = 0;
    protected $pingResult = false;

    public function isConnected() {
        try {
            $timeDifferenceSeconds = microtime(true) - $this->lastPingExecTime;
            if (is_object($this->oInstance) && $timeDifferenceSeconds > 1) { //each 1s make a ping
                $this->pingResult = @$this->oInstance->ping();
                $this->lastPingExecTime = microtime(true);
            }

            // there seems to be no other way than to surpress the error message
            // in order to detect that the connection has been closed,
            // which is a shame.
            //*/
            return is_object($this->oInstance) && $this->pingResult;


        } catch (Exception $e) {
            MLMessage::gi()->addError($e);
            return false;
        }
    }
}
