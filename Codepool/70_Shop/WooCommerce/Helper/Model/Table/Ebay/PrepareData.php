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
 * (c) 2010 - 2017 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

MLFilesystem::gi()->loadClass('Ebay_Helper_Model_Table_Ebay_PrepareData');

class ML_WooCommerce_Helper_Model_Table_Ebay_PrepareData extends ML_Ebay_Helper_Model_Table_Ebay_PrepareData {
    /**
     * returns item detail description at prepare page
     * aField variable after parent::descriptionField($aField) line:
     * {
     * "name":"description",
     * "realname":"description",
     * "value":"<p>Woo Album #4 \u0107<\/p>\r\n<p>SPX096<\/p>\r\n
     *           <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. <\/p>\r\n
     *           <p><img src="http:\/\/magnawoo.dev\/wp-content\/uploads\/magnalister\/products\/500px\/cd_5_angle.jpg" \/><\/p>\r\n
     *           <p><img src="http:\/\/magnawoo.dev\/wp-content\/uploads\/magnalister\/products\/500px\/cd_5_flat.jpg" \/><\/p>\r\n
     *           <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. <\/p>"
     * }
     *
     * @param $aField
     */
    public function descriptionField(&$aField) {
        parent::descriptionField($aField);

        $aField['value'] = str_replace("\\", '', $aField['value']);
        $sValue = preg_replace('/#(Bezeichnung|Freitextfeld)\d+#/', '', $aField['value'] );
        $sValue = preg_replace('/#Bezeichnung\d+#/', '', $sValue );
        $aField['value'] = $sValue;
    }
}