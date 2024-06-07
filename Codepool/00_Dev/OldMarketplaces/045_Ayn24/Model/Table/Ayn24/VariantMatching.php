<?php

class ML_Ayn24_Model_Table_Ayn24_VariantMatching extends ML_Database_Model_Table_Abstract {

    protected $sTableName = 'magnalister_ayn24_variantmatching';
    protected $aFields = array(
        'mpID' => array(
            'isKey' => true,
            'Type' => 'int(11) unsigned', 'Null' => 'NO', 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'Identifier' => array(
            'isKey' => true,
            'Type' => 'varchar(50)', 'Null' => 'NO', 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
        'CustomIdentifier' => array(
            'isKey' => true,
            'Type' => 'varchar(255)', 'Null' => 'NO', 'Default' => '', 'Extra' => '', 'Comment' => ''
        ),
        'ShopVariation' => array(
            'Type' => 'mediumtext', 'Null' => 'NO', 'Default' => NULL, 'Extra' => '', 'Comment' => ''
        ),
    );

    protected $aTableKeys = array(
        'UniqueEntry' => array('Non_unique' => '0', 'Column_name' => 'mpID, Identifier, CustomIdentifier'),
    );

    /**
     * Gets hte list of all saved custom variations.
     * 
     * @return array Array of all custom variations
     */
    public function getCustomVariations() {
        $sQuery = 'SELECT * '
                . '  FROM ' . $this->getTableName()
                . " WHERE NULLIF(CustomIdentifier, '') IS NOT NULL "
                . '   AND mpID = ' . (int)$this->get('mpID');
        $aResult = array();
        foreach (MLDatabase::getDbInstance()->fetchArray($sQuery, true) as $aRecord) {
            $sKey = $aRecord['Identifier'] . ':' . $aRecord['CustomIdentifier'];
            $aResult[$sKey] = $aRecord['CustomIdentifier'];
        }
        
        return $aResult;
    }

    public function getAllItems() {
        $sQuery = 'SELECT * '
                . '  FROM ' . $this->getTableName()
                . ' WHERE mpID = ' . (int)$this->get('mpID');
        $aResult = array();
        foreach (MLDatabase::getDbInstance()->fetchArray($sQuery, true) as $aRecord) {
            $sKey = $aRecord['Identifier'] . ':' . $aRecord['CustomIdentifier'];
            $aResult[$sKey] = $aRecord['CustomIdentifier'] ?: $aRecord['Identifier'];
        }
        
        return $aResult;
    }
 
    public function deleteCustomVariation($sCustomIdentifier) {
        MLDatabase::getDbInstance()->delete($this->getTableName(), array(
            'mpID' => $this->get('mpID'),
            'CustomIdentifier' => $sCustomIdentifier,
        ));
    }
    
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
}
