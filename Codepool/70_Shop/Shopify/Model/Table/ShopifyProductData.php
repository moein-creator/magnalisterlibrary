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

MLFilesystem::gi()->loadClass('Database_Model_Table_Abstract');

class ML_Shopify_Model_Table_ShopifyProductData extends ML_Database_Model_Table_Abstract {

    protected $sTableName = 'magnalister_shopify_product_data';

    protected $aFields = array(
        'ShopifyProductID'   => array(
            'isKey' => true,
            'Type'  => 'bigint(11)', 'Null' => 'NO', 'Default' => NULL, 'Extra' => '', 'Comment' => '',
        ),
        'ShopifyProductData' => array(
            'Type' => 'longtext', 'Null' => 'NO', 'Default' => NULL, 'Extra' => '', 'Comment' => 'Cache product data in this column'
        )
    );

    protected $aTableKeys = array(
        'PRIMARY' => array('Non_unique' => '0', 'Column_name' => 'ShopifyProductID'),
    );

    protected function setDefaultValues() {
        return $this;
    }


}