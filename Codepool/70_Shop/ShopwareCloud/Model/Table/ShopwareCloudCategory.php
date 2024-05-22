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

class ML_ShopwareCloud_Model_Table_ShopwareCloudCategory extends ML_Database_Model_Table_Abstract {

    protected $sTableName = 'magnalister_shopwarecloud_category';

    protected $aFields = array(
        'ShopwareCategoryID'   => array(
            'isKey' => true,
            'Type'  => 'varchar(255)', 'Null' => self::IS_NULLABLE_NO, 'Default' => '', 'Extra' => '', 'Comment' => '',
        ),
        'ShopwareParentID' => array(
            'Type' => 'varchar(255)', 'Null' => self::IS_NULLABLE_YES, 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'ShopwarePath' => array(
            'Type' => 'longtext', 'Null' => self::IS_NULLABLE_YES, 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'ShopwareAfterCategoryId' => array(
            'Type' => 'varchar(255)', 'Null' => self::IS_NULLABLE_YES, 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'ShopwareLevel' => array(
            'Type' => 'int(11) unsigned', 'Null' => self::IS_NULLABLE_NO, 'Default' => '1', 'Extra' => '', 'Comment' => ''
        ),
        'ShopwareActive' => array(
            'Type' => 'tinyint(1)', 'Null' => self::IS_NULLABLE_YES, 'Default' => '1', 'Extra' => '', 'Comment' => ''
        ),
        'ShopwareChildCount' => array(
            'Type' => 'int(11) unsigned', 'Null' => self::IS_NULLABLE_NO, 'Default' => '0', 'Extra' => '', 'Comment' => ''
        ),
        'ShopwareVisible' => array(
            'Type' => 'tinyint(1)', 'Null' => self::IS_NULLABLE_YES, 'Default' => '1', 'Extra' => '', 'Comment' => ''
        ),
        'ShopwareCreatedAt' => array(
            'Type' => 'datetime', 'Null' => self::IS_NULLABLE_YES, 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'ShopwareUpdateDate' => array(
            'Type' => 'datetime', 'Null' => self::IS_NULLABLE_YES, 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
    );

    protected $aTableKeys = array(
        'PRIMARY' => array('Non_unique' => '0', 'Column_name' => 'ShopwareCategoryID'),
    );

    protected function setDefaultValues() {
        return $this;
    }

    public function set($sName, $sValue) {
        if (in_array($sName, ['ShopwareActive', 'ShopwareVisible'])) {
            if ($sValue === '') {
                $sValue = null;
            } else if ($sValue !== null) {
                $sValue = (int)$sValue;
            }
        }

        return parent::set($sName, $sValue);
    }

}