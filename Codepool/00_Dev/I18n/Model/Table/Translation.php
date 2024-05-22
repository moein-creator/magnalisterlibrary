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
 * (c) 2010 - 2024 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

MLFilesystem::gi()->loadClass('Database_Model_Table_Abstract');

class ML_I18n_Model_Table_Translation extends ML_Database_Model_Table_Abstract {

    protected $aFields = array(
        'SHA3256Key' => array(
            'isKey' => true,
            'Type' => 'varchar(256)', 'Null' => self::IS_NULLABLE_NO, 'Default' => NULL, 'Extra' => '', 'Comment' => '', 'Collation' => 'ascii_general_ci'
        ),
        'TranslationKey' => array(
            'isKey' => true,
            'Type' => 'varchar(1000)', 'Null' => self::IS_NULLABLE_NO, 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'FileRelativePath' => array(
            'isKey' => true,
            'Type' => 'varchar(10000)', 'Null' => self::IS_NULLABLE_NO, 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'DE' => array(
            'Type' => 'text', 'Null' => self::IS_NULLABLE_YES, 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'EN' => array(
            'Type' => 'text', 'Null' => self::IS_NULLABLE_YES, 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'FR' => array(
            'Type' => 'text', 'Null' => self::IS_NULLABLE_YES, 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'ES' => array(
            'Type' => 'text', 'Null' => self::IS_NULLABLE_YES, 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
    );

    protected $sTableName = 'magnalister_translation';

    protected $aTableKeys = array(
        'PRIMARY' => array('Non_unique' => '0', 'Column_name' => 'SHA3256Key'),
    );

    protected function setDefaultValues() {

    }

    public function save() {
        $hash = hash('sha3-256', $this->aData['translationkey'] . $this->aData['filerelativepath']);
        $this->set('SHA3256Key', $hash);
        return parent::save();
    }

}
