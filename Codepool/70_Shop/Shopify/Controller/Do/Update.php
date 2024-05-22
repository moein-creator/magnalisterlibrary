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
 * (c) 2010 - 2022 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

MLFilesystem::gi()->loadClass('Core_Controller_Do_Update');

/**
 * Updates and installs the magnalister-plugin.
 */
class ML_Shopify_Controller_Do_Update extends ML_Core_Controller_Do_Update {

    //    /**
    //     * the steps and parameters
    //     * @var array
    //     */
    //    protected $aProgressBar = array(
    //        'init' => array(
    //            'weighting' => 5,
    //        ),
    //        'calcSequences' => array(
    //            'weighting' => 25,
    //        ),
    //        'copyFilesToStaging' => array(
    //            'weighting' => 25,
    //        ),
    //        'addIndexPhp' => array(
    //            'weighting' => 6
    //        ),
    //        'finalizeUpdate' => array(
    //            'weighting' => 15,
    //        ),
    //        'afterUpdate' => array(
    //            'weighting' => 100,
    //        ),
    //        'success' => array(
    //            'weighting' => 4,
    //        ),
    //    );
    //
    //
    //    protected function getUrlExtraParameters() {
    //        return array();
    //    }
}