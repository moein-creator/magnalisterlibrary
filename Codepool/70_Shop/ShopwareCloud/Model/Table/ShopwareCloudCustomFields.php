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

class ML_ShopwareCloud_Model_Table_ShopwareCloudCustomFields extends ML_Database_Model_Table_Abstract {

    protected $sTableName = 'magnalister_shopwarecloud_custom_fields';

    protected $aFields = array(
        'ShopwareCustomFieldID'   => array(
            'isKey' => true,
            'Type' => 'varchar(255)', 'Null' => self::IS_NULLABLE_NO, 'Default' => NULL, 'Extra' => '', 'Comment' => '',
        ),
        'ShopwareCustomFieldSetID'   => array(
            'isKey' => true,
            'Type' => 'varchar(255)', 'Null' => self::IS_NULLABLE_NO, 'Default' => NULL, 'Extra' => '', 'Comment' => '',
        ),
        'ShopwareCustomFieldName'   => array(
            'Type' => 'varchar(128)', 'Null' => self::IS_NULLABLE_NO, 'Default' => NULL, 'Extra' => '', 'Comment' => '',
        ),
        'ShopwarePosition' => array(
            'Type' => 'int(11)', 'Null' => self::IS_NULLABLE_YES, 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'ShopwareCustomFieldType'   => array(
            'Type' => 'varchar(128)', 'Null' => self::IS_NULLABLE_NO, 'Default' => NULL, 'Extra' => '', 'Comment' => '',
        ),
        'ShopwareCustomFieldRelation'   => array(
            'Type' => 'varchar(255)', 'Null' => self::IS_NULLABLE_NO, 'Default' => NULL, 'Extra' => '', 'Comment' => '',
        ),
        'ShopwareCustomFieldLabel' => array(
            'Type' => 'longtext', 'Null' => self::IS_NULLABLE_NO, 'Default' => NULL, 'Extra' => '', 'Comment' => 'Cache product data in this column'
        ),
        'ShopwareCustomFieldConfigOptions'=> array(
            'Type' => 'longtext', 'Null' => self::IS_NULLABLE_NO, 'Default' => NULL, 'Extra' => '', 'Comment' => 'Cache product data in this column'
        )
    );

    protected $aTableKeys = array(
        'PRIMARY' => array('Non_unique' => '0', 'Column_name' => 'ShopwareCustomFieldID'),
    );

    protected function setDefaultValues() {
        return $this;
    }
}
