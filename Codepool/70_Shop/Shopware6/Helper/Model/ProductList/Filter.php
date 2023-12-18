<?php

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Redgecko\Magnalister\Controller\MagnalisterController;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Api\Context\SystemSource;
use Shopware\Core\Defaults;
use Shopware\Core\Checkout\Order\OrderStates;
use Shopware\Core\Checkout\Order\Aggregate\OrderTransaction\OrderTransactionStates;

class ML_Shopware6_Helper_Model_ProductList_Filter{
    protected $sPrefix='';
    protected $iPage=0;
    protected $iOffset=0;
    protected $aOrder=array ('name' => 'p.`id`' , 'direction' => 'DESC') ;

     /**
      *
      * @var ML_Database_Model_Query_Select $oSelect
      */
    protected $oSelect = null ;
    protected $oI18n=null;
    protected $aFilterInput=array();
    protected $aFilterOutput=array();
    public function __construct() {
        $this->oI18n=  MLI18n::gi();
    }
    public function clear(){
        $oRef=new ReflectionClass($this);
        foreach($oRef->getDefaultProperties() as $sKey=>$mValue){
            $this->$sKey=$mValue;
        }
        $this->__construct();
        return $this;
    }
    public function setCollection($oSelect){
        $this->oSelect=$oSelect;
        return $this;
    }
    public function setFilter($aFilterInput){
        $this->aFilterInput=$aFilterInput;
        return $this;
    }
    public function setOffset($iOffset){
        $this->iOffset=(int)$iOffset;
        return $this;
    }
    public function setPage($iPage){
        $this->iPage=(int)$iPage;
        return $this;
    }
    public function setOrder($sOrder){      
         $aOrder = explode('_' , $sOrder) ;
          $aConf = MLModul::gi()->getConfig();
          $iLangId = $aConf['lang']; 
         if ( count($aOrder) == 2 && $aOrder[0] != '' && $aOrder[1] != '' ) {
            if ($aOrder[0] == 'ManufacturerNumber') {
                 $aOrder[0] = 'p.manufacturer_number';                 
             }elseif ($aOrder[0] == 'name' ) {                
                $aOrder[0] = 't.`name`';
                $this->oSelect->join(array(MagnalisterController::getShopwareMyContainer()->get('product.repository')->getDefinition()->getEntityName().'_translation', 't', 't.`product_id` = p.`id` AND HEX(t.`language_id`)=\''.(string)$iLangId.'\''), 1);                
            }elseif ($aOrder[0] == 'quantity' ) {                
                $aOrder[0] = 'p.`stock`';
            }
            $this->aOrder = array ('name' => $aOrder[0] , 'direction' => $aOrder[1]) ;
            $this->oSelect->orderBy("{$aOrder[0]} {$aOrder[1]}") ;
             //echo $this->oSelect->getQuery(false);  
         }
     }
    public function setPrefix($sPrefix){
        $this->sPrefix=$sPrefix;
        return $this;
    }
    public function getOutput(){
        return $this->aFilterOutput;
    }
    public function getStatistic(){
        $iCountTotal = (int)$this->oSelect->getCount(true, 'Distinct p.`id`') ;
        $iCountPerPage=isset($this->aFilterOutput[$this->sPrefix.'limit']['value'])?$this->aFilterOutput[$this->sPrefix.'limit']['value']:$iCountTotal;
        return array(
            'iCountPerPage'=>$iCountPerPage,
            'iCurrentPage'=>$this->iPage,
            'iCountTotal'=>$iCountTotal,
            'aOrder'=>$this->aOrder,
        );
    }
    
    protected function getDefaultValue($sName, $aPossibleValues){
        $sValue = isset($this->aFilterInput[$sName]) ? $this->aFilterInput[$sName] : '';
        $sValue = array_key_exists($sValue, $aPossibleValues)?$sValue:key($aPossibleValues);
        return $sValue;
    }
    
    public function limit(){
        $sName=$this->sPrefix.__function__;
        if(!isset($this->aFilterOutput[$sName])){
            $oI18n=$this->oI18n;
            $aValues=array();
            foreach(array(5,10,25,50,75,100) as $iKey){
                $aValues[$iKey]=array(
                    'value' => (string)$iKey,
                    'label' => sprintf($oI18n->get('Productlist_Filter_sLimit'), (string)$iKey)
                );
            }
            $iValue=(int)$this->getDefaultValue($sName, $aValues);
            if($this->iPage==0){
                $iOffset=$this->iOffset;
            }else{
                $iOffset=$this->iPage*$iValue;
            }
            $this->aLimit=array($iValue,$iOffset);
            $this->oSelect->limit($iOffset,$iValue);
            $this->aFilterOutput[$sName]= array(
                'name'=>$sName,
                'type'=>'select',
                'value'=>$iValue,
                'values'=>$aValues
            );
        }
        return $this;
    }
    
    /**
     * adds a ML_Productlist_Model_ProductListDependency_Abstract instance to filter
     * @param string $sDependency ident-name of dependency
     * @param array $aDependecyConfig config for dependency
     * @return \ML_Shopware_Helper_Model_ProductList_Filter
     */
    public function registerDependency ($sDependency, $aDependecyConfig = array()) {
        $oDependency = MLProductList::dependencyInstance($sDependency)->setConfig($aDependecyConfig);
        $sName = $this->sPrefix.$sDependency;
        if (!isset($this->aFilterOutput[$sName])) {
            $oDependency
                ->setFilterValue(isset($this->aFilterInput[$sName]) ? $this->aFilterInput[$sName] : null)
                ->manipulateQuery($this->oSelect)
            ;
            $this->aFilterOutput[$sName] = $oDependency;
            $aIdentFilter = $oDependency->getMasterIdents();
            if ($aIdentFilter['in'] !== null) {
                $sField = MLDatabase::factory('config')->set('mpid',0)->set('mkey','general.keytype')->get('value') == 'pID' ? 'HEX(p.`id`)' : 'p.`product_number`';
                $this->oSelect->where($sField." IN('".implode("', '",array_unique(MLDatabase::getDbInstance()->escape($aIdentFilter['in'])))."')");
            }
            if ($aIdentFilter['notIn'] !== null) {
                $sField = MLDatabase::factory('config')->set('mpid',0)->set('mkey','general.keytype')->get('value') == 'pID' ? 'HEX(p.`id`)' : 'p.`product_number`';
                $this->oSelect->where($sField." NOT IN('".implode("', '", array_unique(MLDatabase::getDbInstance()->escape($aIdentFilter['notIn'])))."')");
            }
        }
        return $this;
    }
    
    public function variantInList(ML_Shop_Model_Product_Abstract $oProduct){
        foreach ($this->aFilterOutput as $oDependency) {
            if (is_object($oDependency) && !$oDependency->variantIsActive($oProduct)) {
                return false;
            }
        }
        return true;
    }
    
}
