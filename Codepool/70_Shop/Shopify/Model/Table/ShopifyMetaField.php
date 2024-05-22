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

MLFilesystem::gi()->loadClass('Database_Model_Table_Abstract');

class ML_Shopify_Model_Table_ShopifyMetaField extends ML_Database_Model_Table_Abstract {

    protected $sTableName = 'magnalister_shopify_metafield';

    protected $aFields = array(
        'MetaFieldId' => array(
            'isKey' => true,
            'Type' => 'varchar(50)', 'Null' => self::IS_NULLABLE_NO, 'Default' => NULL, 'Extra' => '', 'Comment' => 'md5 of namespace+key+ownerType'
        ),
        'ShopifyMetaFieldKey' => array(
            'Type' => 'varchar(100)', 'Null' => self::IS_NULLABLE_NO, 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'ShopifyMetaFieldName' => array(
            'Type' => 'varchar(255)', 'Null' => self::IS_NULLABLE_YES, 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'ShopifyMetaFieldType' => array(
            'Type' => 'varchar(100)', 'Null' => self::IS_NULLABLE_NO, 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'ShopifyMetaFieldOwnerType' => array(
            'Type' => 'varchar(100)', 'Null' => self::IS_NULLABLE_NO, 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'ShopifyMetaFieldNamespace' => array(
            'Type' => 'varchar(100)', 'Null' => self::IS_NULLABLE_NO, 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'Type' => array(
            'Type' => 'varchar(50)', 'Null' => self::IS_NULLABLE_NO, 'Default' => NULL, 'Extra' => '', 'Comment' => 'freetext, select'
        )
    );

    protected $aTableKeys = array(
        'PRIMARY' => array('Non_unique' => '0', 'Column_name' => 'MetaFieldId'),
        'Type' => array('Non_unique' => '1', 'Column_name' => 'Type'),
    );

    /**
     * @return string
     */
    public function getNameOfMetaField($blAddKey = true) {
        $sName = $this->get('ShopifyMetaFieldName');
        if (empty($sName)) {
            $sName = $this->get('ShopifyMetaFieldKey');
            $aNameParts = explode('_', $sName);
            foreach ($aNameParts as &$sNamePart) {
                $sNamePart = ucfirst($sNamePart);
            }
            $sName = implode(' ', $aNameParts);
            switch ($sName) {
                case 'Title Tag':
                    $sName = MLI18n::gi()->shopify_metafield_labels_page_title_tag;
                    break;
                case 'Description Tag':
                    $sName = MLI18n::gi()->shopify_metafield_labels_page_description_tag;
                    break;

            }
        }
        if ($blAddKey) {
            $sName .= ' (' . $this->getMetaFieldExtraInformation() . ')';
        }
        return $sName;
    }

    /**
     * @return string
     */
    public function getMetaFieldExtraInformation() {
        $type = $this->get('ShopifyMetaFieldOwnerType') === 'PRODUCTVARIANT' ? 'variant' : strtolower($this->get('ShopifyMetaFieldOwnerType'));
        return $type . '.' . $this->get('ShopifyMetaFieldNamespace') . '.' . $this->get('ShopifyMetaFieldKey');
    }

    /**
     * @return array
     */
    public function getMetaFieldExtraInformationAllPossibleKeys() {
        $aReturn = [];
        $type = $this->get('ShopifyMetaFieldOwnerType') === 'PRODUCTVARIANT' ? 'variant' : strtolower($this->get('ShopifyMetaFieldOwnerType'));
        $aReturn[] = $type . '.metafields.' . $this->get('ShopifyMetaFieldNamespace') . '.' . $this->get('ShopifyMetaFieldKey');
        $aReturn[] = $type . '.' . $this->get('ShopifyMetaFieldNamespace') . '.' . $this->get('ShopifyMetaFieldKey');
        $aReturn[] = $this->get('ShopifyMetaFieldNamespace') . '.' . $this->get('ShopifyMetaFieldKey');
        return $aReturn;
    }
    protected function setDefaultValues() {
        return $this;
    }


}