<?php

MLFilesystem::gi()->loadClass('Database_Model_Table_Prepare_Abstract');

class ML_Shopify_Model_Table_ShopifyAttribute extends ML_Database_Model_Table_Prepare_Abstract {

    protected $sTableName = 'magnalister_shopify_attributes';

    protected $aFields = array(
        'id' => array(
            'Type' => 'int(11) unsigned', 'Null' => 'NO', 'Default' => NULL,  'Extra' => 'auto_increment','Comment'=>''),
        'ProductID'     => array(
            'isKey' => true,
            'Type' => 'varchar(30)','Null' => 'NO', 'Default' => NULL,  'Extra' => '' ,'Comment'=>''),
        'AttributeName'  => array(
            'isKey' => true,
            'Type' => 'varchar(255)','Null' => 'NO', 'Default' => NULL,  'Extra' => '' ,'Comment'=>''),
        'AttributeValues'  => array(
            'Type' => 'text', 'Null' => 'YES', 'Default' => NULL,  'Extra' => '','Comment'=>''),
    );

    protected $aTableKeys = array(
        'UC_products_id' => array('Non_unique' => '0', 'Column_name' => 'id'),
        //'UC_productsId_AttributeName' => array('Non_unique' => '0', 'Column_name' => 'ProductID, AttributeName'),
    );

    protected function setDefaultValues() {
        return $this;
    }


}