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

MLFilesystem::gi()->loadClass('I18n_Model_Table_Translation');

class ML_I18n_Model_Table_TranslationUpdate extends ML_I18n_Model_Table_Translation {

    protected $sTableName = 'magnalister_translation_update';
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
        'Status' => array(
            'Type' => 'varchar(200)', 'Null' => self::IS_NULLABLE_YES, 'Default' => 'open', 'Extra' => '', 'Comment' => ''
        ),
        'StatusHistory' => array(
            'Type' => 'text', 'Null' => self::IS_NULLABLE_YES, 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'Comment' => array(
            'Type' => 'text', 'Null' => self::IS_NULLABLE_YES, 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        )

    );

    /**
     * @param $sTableName string
     * @return ML_I18n_Model_Table_TranslationUpdate
     */
    public function setTableName($sTableName) {
        $this->sTableName = $sTableName;
        return $this;
    }

    public function set($sName, $mValue) {
        parent::set($sName, $mValue);
        if (strtolower($sName) === 'status' && $mValue !== null) {
            $aHistory = [
                date('Y-m-d H:i:s') => $mValue
            ];
            parent::set('StatusHistory', $aHistory);
        }
        return $this;
    }

    protected function update() {
        if (!empty($this->aOrginData['statushistory'])) {
            $this->aData['statushistory'] = MLHelper::getEncoderInstance()->encode(MLHelper::getEncoderInstance()->decode($this->aData['statushistory']) + MLHelper::getEncoderInstance()->decode($this->aOrginData['statushistory']));
        }
        return parent::update();
    }
}
