<?php
abstract class ML_Shopware_Model_ProductList_Abstract extends ML_Productlist_Model_ProductList_ShopAbstract {
    protected $sPrefix='ml_';
    /**
     * filter
     * @var ML_Shopware_Helper_Model_ProductList_Filter $oFilter
     */
    protected $oFilter=null;
    /**
     * list/result
     * @var ML_Shopware_Helper_Model_ProductList_List $oList
     */
    protected $oList=null;
    protected $sOrder='';
    
    /**
     * @var ML_Database_Model_Query_Select $oSelectQuery
     */
    protected $oSelectQuery = null ;
    public function __construct(){
        /* @var $oSelectquery ML_Database_Model_Query_Select */
        $oSelectquery= MLHelper::gi("model_product")->getProductSelectQuery();                
        
        // marketplace language
        try{
            $aConfig=MLModul::gi()->getConfig();
            foreach(MLModul::gi()->getPriceGroupKeys() as $sGroupKey){
                $oCustomerGroup = Shopware()->Models()->getRepository('\Shopware\Models\Customer\Group')->find($aConfig[$sGroupKey]);
                if(!is_object($oCustomerGroup)){
                    $sCurrentController = MLRequest::gi()->get('controller');
                    MLHttp::gi()->redirect(MLHttp::gi()->getUrl(array(
                        'controller' => substr($sCurrentController, 0, strpos($sCurrentController, '_')).'_config_price'
                    )));
                }
            }
            
            $iLangId=$aConfig['lang'];
        }catch (Exception $oExc ){
            $iLangId= MLShop::gi()->getDefaultShop()->getId();
        }
        $oShop = Shopware()->Models()->getRepository('Shopware\Models\Shop\Shop')->find($iLangId);
        // Shopware 5.7 compatiblity
        if (version_compare(MLSHOPWAREVERSION, '5.7', '>=')) {
            Shopware()->Container()->set('shop', $oShop);
        } else {
            Shopware()->Bootstrap()->registerResource('Shop', $oShop);
        }
        
        $this->oSelectQuery = $oSelectquery;
        $this->oFilter=  MLHelper::gi('model_productlist_filter')
            ->clear()
            ->setCollection($oSelectquery)
            ->setPrefix($this->sPrefix)
        ;
        $this->initList();
        $this->oList
            ->clear()
            ->setCollection($oSelectquery)
        ;
    }

    protected function initList() {
        $this->oList = MLHelper::gi('model_productlist_list');
    }

    public function setFilters($aFilter){
        if(is_array($aFilter)){
            $this->oFilter
                ->setFilter($aFilter)
                ->setPage(isset($aFilter['meta']['page'])?$aFilter['meta']['page']:0)
                ->setOffset(isset($aFilter['meta']['offset'])?$aFilter['meta']['offset']:0)
                ->setOrder(isset($aFilter['meta']['order'])?$aFilter['meta']['order']:'p.id_DESC')
            ;                       
        }else{
            $this->oFilter->setOrder('p.id_DESC');
        }
        $this->sOrder=isset($aFilter['meta']['order'])?$aFilter['meta']['order']:'';
        $this->executeList();
        $this->executeFilter();
        return $this;
    }
    public function getFilters(){
        return $this->oFilter->getOutput();
    }
    public function getStatistic(){
        return $this->oFilter->getStatistic();
    }
    public function getMasterIds($blPage = false) {
        $aMainIds = array () ;
         if ( $blPage ) {
             $aMainIds = $this->oList->getLoadedList() ;
         } else {
             $aIdArrays = $this->oSelectQuery->getAll() ;
             foreach ($aIdArrays as $aItem){
                 $aMainIds[] = current($aItem);
             }
         }
         $aIds = array();//array_unique($aMainIds);
         foreach ( $aMainIds as $sId ) {
             $oProduct = Shopware()->Models()->getRepository('Shopware\Models\Article\Article')->find((int)$sId)  ;
             $aIds[] = MLProduct::factory()->loadByShopProduct($oProduct)->get('id');
         }
         return $aIds;
    }
    
    abstract protected function executeFilter();
    
    public function getHead(){
        return $this->oList->getHeader();
    }
    
    /**
     *
     * @var array
     */
    protected $aListOfProduct = null;
    public function getList(){
        return new ArrayIterator($this->getArrayList());
   }
   
    public function getArrayList(){
        if($this->aListOfProduct === null){
            $this->aListOfProduct = $this->oList->getList();
        }
        return $this->aListOfProduct;
    }
   public function additionalRows(ML_Shop_Model_Product_Abstract $oProduct){
       return array();
   }
   public function getMixedData(ML_Shop_Model_Product_Abstract $oProduct, $sKey){
       return $this->oList->getMixedData($oProduct,$sKey);
   }
   public function variantInList(ML_Shop_Model_Product_Abstract $oProduct){
       return $this->oFilter->variantInList($oProduct);
   }    
   public function setLimit($iFrom, $iCount){
        $this->oSelectQuery->limit($iFrom,$iCount ) ;
        return $this;
    }
}
