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
 * (c) 2010 - 2021 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

MLFilesystem::gi()->loadClass('Database_Model_Table_Prepare_Abstract');

class ML_Otto_Model_Table_Otto_Prepare extends ML_Database_Model_Table_Prepare_Abstract {

    protected $sTableName = 'magnalister_otto_prepare';

    protected $aFields = array(
        'products_id' => array(
            'isKey' => true,
            'Type' => 'int(11)', 'Null' => 'NO', 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'PreparedTS' => array(
            'isInsertCurrentTime' => true,
            'Type' => 'datetime', 'Null' => 'NO', 'Default' => '0000-00-00 00:00:00', 'Extra' => '', 'Comment' => ''
        ),
        'StartTime' => array(
            'Type' => 'datetime', 'Null' => 'YES', 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'mpID' => array(
            'isKey' => true,
            'Type' => 'int(11) unsigned', 'Null' => 'NO', 'Default' => '0', 'Extra' => '', 'Comment' => ''
        ),
        'Title' => array(
            'Type' => 'varchar(256)', 'Null' => 'YES', 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'Manufacturer' => array(
            'Type' => 'varchar(55)', 'Null' => 'YES', 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'ManufacturerPartNumber' => array(
            'Type' => 'varchar(55)', 'Null' => 'YES', 'Default' => '', 'Extra' => '', 'Comment' => ''
        ),
        'ShortDescription' => array(
            'Type' => 'longtext', 'Null' => 'YES', 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'Brand' => array(
            'Type' => 'varchar(256)', 'Null' => 'YES', 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'Feature' => array(
            'Type' => 'text', 'Null' => 'YES', 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'Description' => array(
            'Type' => 'longtext', 'Null' => 'YES', 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'Images' => array(
            'Type' => 'text', 'Null' => 'YES', 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'PrimaryCategory' => array(
            'Type' => 'varchar(50)', 'Null' => 'NO', 'Default' => '', 'Extra' => '', 'Comment' => ''
        ),
        'PrimaryCategoryName' => array(
            'Type' => 'varchar(128)', 'Null' => 'NO', 'Default' => '', 'Extra' => '', 'Comment' => ''
        ),
        'Features' => array(
            'Type' => 'text', 'Null' => 'NO', 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'DeliveryType' => array(
            'Type' => 'enum(\'PARCEL\',\'FORWARDER_PREFERREDLOCATION\', \'FORWARDER_CURBSIDE\')', 'Null' => 'NO', 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'DeliveryTime' => array(
            'Type' => 'tinyint(4)', 'Null' => 'NO', 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'noidentifierflag' => array(
            'Type' => 'varchar(10)', 'Null' => 'NO', 'Default' => '0', 'Extra' => '', 'Comment' => ''
        ),
        'Verified' => array(
            'Type' => 'enum(\'OK\',\'ERROR\',\'OPEN\')', 'Null' => 'NO', 'Default' => 'OPEN', 'Extra' => '', 'Comment' => ''
        ),
        'Transferred' => array(
            'Type' => 'tinyint(1)', 'Null' => 'NO', 'Default' => '0', 'Extra' => '', 'Comment' => ''
        ),
        'ShopVariation' => array(
            'Type' => 'longtext', 'Null' => 'NO', 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'CategoryIndependentShopVariation' => array(
            'Type' => 'longtext', 'Null' => 'NO', 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'VariationThemeBlacklist' => array(
            'Type' => 'text', 'Null' => 'YES', 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'deletedBy' => array(
            'Type' => 'enum(\'\',\'empty\',\'Sync\',\'Button\',\'expired\',\'notML\')', 'Null' => 'NO', 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'topPrimaryCategory' => array(
            'Type' => 'varchar(64)', 'Null' => 'NO', 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
    );

    protected $aTableKeys = array(
        'UniqueEntry' => array('Non_unique' => '0', 'Column_name' => 'mpID, products_id'),
    );

    protected function setDefaultValues() {
        try {
            $sId = MLRequest::gi()->get('mp');
            if (is_numeric($sId)) {
                $this->set('mpid', $sId);
            }
        } catch (Exception $oEx) {

        }
        return $this;
    }

    public function set($sName, $mValue) {
        $sName = strtolower($sName);
        if ($sName == 'starttime' && $mValue !== null) {
            $iTime = strtotime(str_replace('/', '-', $mValue));
            $mValue = empty($iTime) ? null : date('Y-m-d H:i:s', $iTime);
        } elseif (in_array($sName, array('price', 'buyitnowprice')) && $mValue !== null) {
            $mValue = (float)str_replace(',', '.', $mValue);
        }
        if (
            $mValue !== NULL
            && in_array($sName, array('primarycategory'))
        ) {
            $this->set('top'.$sName, $mValue);
        }
        return parent::set($sName, $mValue);
    }

    public function getVariantCount($mProduct) {
        $iMasterProductId = (int)(
        $mProduct instanceof ML_Database_Model_Table_Abstract ? $mProduct->get('id') : $mProduct
        );
        $sSql = "
            SELECT COUNT(*)
            FROM magnalister_products p
            INNER JOIN magnalister_otto_prepare s ON p.id = s.products_id
            WHERE     p.parentid = '".$iMasterProductId."'
                  AND s.mpid = '".MLRequest::gi()->get('mp')."'
                  AND s.verified = 'OK'
        ";
        return MLDatabase::getDbInstance()->fetchOne($sSql);
    }

    protected function getTopCategories($sClass, $sField) {
        $aCats = array();
        $oCat = MLDatabase::factory($sClass);
        /* @var $oCat ML_Modul_Model_Table_Categories_Abstract */
        $query = "
            SELECT ".$sField."
            FROM ".$this->sTableName." prepare
            INNER JOIN ".MLDatabase::factory('product')->getTableName()." product on product.id = prepare.products_id
            INNER JOIN ".$oCat->getTableName()." cat on cat.categoryid = ".$sField."
            WHERE prepare.mpid = ".MLModul::gi()->getMarketPlaceId()."
            GROUP BY ".$sField."
            ORDER BY count(".$sField.")/count(product.parentid)+count(distinct product.parentid)-1 desc
            LIMIT 10
        ";

        foreach (MLDatabase::getDbInstance()->fetchArray($query, true) as $iCatId) {
            $oCat->init(true)->set('CategoryID', $iCatId);
            $sCat = $oCat->get('CategoryName');
            foreach ($oCat->getCategoryPath() as $oParentCat) {
                $sCat = $oParentCat->get('CategoryName').' &gt; '.$sCat;
            }

            $aCats[$iCatId] = $sCat;
        }

        return $aCats;
    }

    public function getTopPrimaryCategories() {
        return $this->getTopCategories('otto_categoriesmarketplace', 'topprimarycategory');
    }

    public function getCategoryIndependentShopVariationFieldName() {
        return 'CategoryIndependentShopVariation';
    }
}
