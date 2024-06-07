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
 * (c) 2010 - 2015 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

MLFilesystem::gi()->loadClass('Database_Model_Table_Abstract');

class ML_ZzzzDummyShop_Model_Table_ZzzzDummyShopProduct extends ML_Database_Model_Table_Abstract {

    protected $sTableName = 'zzzzdummy_products';
    
    protected $aFields = array(
        'Id'                        => array('Type' => 'int(11)',           'Null' => 'NO',     'Default' => NULL, 'Extra' => 'auto_increment', 'Comment' => '', 'isKey' => true,   ),
        'ParentId'                  => array('Type' => 'int(11)',           'Null' => 'YES',    'Default' => NULL, 'Extra' => '',               'Comment' => '',                    ),
        'SKU'                       => array('Type' => 'varchar(11)',       'Null' => 'YES',    'Default' => NULL, 'Extra' => '',               'Comment' => '',                    ),
        'Name'                      => array('Type' => 'varchar(64)',       'Null' => 'YES',    'Default' => NULL, 'Extra' => '',               'Comment' => '',                    ),
        'Price'                     => array('Type' => 'float(11)',         'Null' => 'YES',    'Default' => NULL, 'Extra' => '',               'Comment' => '',                    ),
        'Stock'                     => array('Type' => 'int(11)',           'Null' => 'YES',    'Default' => NULL, 'Extra' => '',               'Comment' => '',                    ),
        'Tax'                       => array('Type' => "enum('19','7')",    'Null' => 'YES',    'Default' => NULL, 'Extra' => '',               'Comment' => '',                    ),
        'Description'               => array('Type' => 'text',              'Null' => 'YES',    'Default' => NULL, 'Extra' => '',               'Comment' => '',                    ),
        'ShortDescription'          => array('Type' => 'text',              'Null' => 'YES',    'Default' => NULL, 'Extra' => '',               'Comment' => '',                    ),
        'MetaDescription'           => array('Type' => 'text',              'Null' => 'YES',    'Default' => NULL, 'Extra' => '',               'Comment' => '',                    ),
        'MetaKeywords'              => array('Type' => 'text',              'Null' => 'YES',    'Default' => NULL, 'Extra' => '',               'Comment' => '',                    ),
        'Active'                    => array('Type' => 'int(1)',            'Null' => 'YES',    'Default' => NULL, 'Extra' => '',               'Comment' => '',                    ),
        'Weight'                    => array('Type' => 'text',              'Null' => 'YES',    'Default' => NULL, 'Extra' => '',               'Comment' => '',                    ),
        'Manufacturer'              => array('Type' => 'varchar(64)',       'Null' => 'YES',    'Default' => NULL, 'Extra' => '',               'Comment' => '',                    ),
        'ManufacturerPartNumber'    => array('Type' => 'varchar(64)',       'Null' => 'YES',    'Default' => NULL, 'Extra' => '',               'Comment' => '',                    ),
        'Images'                    => array('Type' => 'text',              'Null' => 'YES',    'Default' => NULL, 'Extra' => '',               'Comment' => '',                    ),
        'Ean'                       => array('Type' => 'varchar(32)',       'Null' => 'YES',    'Default' => NULL, 'Extra' => '',               'Comment' => '',                    ),
    );
    
    protected $aTableKeys = array(
        'PRIMARY'   => array('Non_unique' => '0', 'Column_name' => 'Id'),
        'ParentId'  => array('Non_unique' => '1', 'Column_name' => 'ParentId'),
        'SKU'       => array('Non_unique' => '0', 'Column_name' => 'SKU'),
    );

    protected function setDefaultValues() {
        return $this;
    }
    
    public function get($sName) {
        $mParent = parent::get($sName);
        if ($mParent === null) {
            $mValue = null;
            switch (strtolower($sName)) {
                case 'name' : {
                    $mValue = 'name: '.$this->get('sku');
                    break;
                }
                case 'price' : {
                    $mValue = rand(1, 10000)/100;
                    break;
                }
                case 'stock' : {
                    $mValue = rand(0, 200);
                    break;
                }
                case 'tax' : {
                    $mValue = rand(0, 4) == 0 ? 7 : 19;
                    break;
                }
                case 'description' : {
                    $mValue = str_repeat($this->get('sku').' ', rand(1, 50));
                    break;
                }
                case 'shortdescription' : {
                    $mValue = str_repeat($this->get('sku').' ', rand(1, 10));
                    break;
                }
                case 'metadescription' : {
                    $mValue = str_repeat($this->get('sku').' ', rand(1, 10));
                    break;
                }
                case 'metakeywords' : {
                    $mValue = str_repeat($this->get('sku').', ', rand(1, 10));
                    break;
                }
                case 'active' : {
                    $mValue = rand(0, 4) == 0 ? 0 : 1;
                    break;
                }
                case 'weight' : {
                    $aUnits = array('mg', 'g', 'kg');
                    $mValue = array(
                        "Unit" => $aUnits[rand(0, count($aUnits)-1)],
                        "Value"=>  rand(1, 1000),
                    );
                    break;
                }
                case 'manufacturer' : {
                    $aManufacturers = array('Merzädez', 'RedGecko', 'Porschä', 'Sonie', 'Indäl', '');
                    $mValue = $aManufacturers[rand(0, count($aManufacturers)-1)];
                    break;
                }
                case 'manufacturerpartnumber' : {
                    $mValue = uniqid();
                    break;
                }
                case 'images' : {
                    $mValue = array();
                    foreach(glob(getcwd().'/_images/*') as $sImage) {
                        if (!is_dir($sImage) && rand(0, 3) == 0) {
                            $mValue[] = substr($sImage, strlen(getcwd()));
                        }
                    }
                    break;
                }
                case 'ean' : {
                    $mValue = uniqid();
                    break;
                }
            }
            if ($mValue !== null) {
                $this->set($sName, $mValue)->save();
                $mParent = parent::get($sName);
            }
        }
        if ($sName == 'images') {
            foreach ($mParent as &$sImage) {
                $sImage = getcwd().$sImage;
            }
        }
        return $mParent;
    }
    
    public function set($sName, $mValue) {
        $sName = strtolower($sName);
        if ($sName == 'stock') {
            if ($this->get('parentid') === null) {
                $oVariantList = MLDatabase::factory('ZzzzDummyShopProduct')->set('parentid', $this->get('id'))->getList();
                if ($oVariantList->getCountTotal() != 0) {//have variants
                    $mValue = 0;
                    foreach ($oVariantList->get('stock') as $iVariantStock) {
                        $mValue += $iVariantStock;
                    }
                }
            }
        }
        parent::set($sName, $mValue);
        return $this;
    }

    public function zzzzDummyProduct($sSku, $iParentId = null) {
        $this->aKeys = array('sku');
        $this->set('sku', $sSku)->set('parentid', $iParentId);
        if (!$this->exists()) {
            $this->save();
            if (empty($iParentId)) {
                $iVariants = rand(0, 10);
                for($i = 0; $i < $iVariants; $i++) {
                    MLDatabase::factory('ZzzzDummyShopProduct')->zzzzDummyProduct($sSku.'-'.$i, $this->get('id'));
                }
            }
        }
        return $this->init(true);
    }

}