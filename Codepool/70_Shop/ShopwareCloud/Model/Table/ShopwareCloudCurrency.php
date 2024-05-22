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

class ML_ShopwareCloud_Model_Table_ShopwareCloudCurrency extends ML_Database_Model_Table_Abstract {

    protected $sTableName = 'magnalister_shopwarecloud_currency';

    protected $aFields = array(
        'ShopwareCurrencyID'   => array(
            'isKey' => true,
            'Type'  => 'varchar(255)', 'Null' => self::IS_NULLABLE_NO, 'Default' => NULL, 'Extra' => '', 'Comment' => '',
        ),
        'ShopwareIsoCode' => array(
            'Type' => 'char(3)', 'Null' => self::IS_NULLABLE_NO, 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'ShopwareFactor' => array(
            'Type' => 'double', 'Null' => self::IS_NULLABLE_NO, 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'ShopwareSymbol' => array(
            'Type' => 'varchar(255)', 'Null' => self::IS_NULLABLE_NO, 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'ShopwarePosition' => array(
            'Type' => 'int(11)', 'Null' => self::IS_NULLABLE_YES, 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'ShopwareCreatedAt' => array(
            'Type' => 'datetime', 'Null' => self::IS_NULLABLE_NO, 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'ShopwareUpdateDate' => array(
            'Type' => 'datetime', 'Null' => self::IS_NULLABLE_YES, 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'ShopwareItemRounding' => array(
            'Type' => 'longtext', 'Null' => self::IS_NULLABLE_YES, 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'ShopwareTotalRounding' => array(
            'Type' => 'longtext', 'Null' => self::IS_NULLABLE_YES, 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'ShopwareTaxFreeFrom' => array(
            'Type' => 'double', 'Null' => self::IS_NULLABLE_YES, 'Default' => '0', 'Extra' => '', 'Comment' => ''
        ),
    );

    protected $aTableKeys = array(
        'PRIMARY' => array('Non_unique' => '0', 'Column_name' => 'ShopwareCurrencyID'),
    );

    protected function setDefaultValues() {
        return $this;
    }


}