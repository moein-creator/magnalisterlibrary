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

abstract class ML_Magento_Model_ProductList_Abstract extends ML_Productlist_Model_ProductList_ShopAbstract {
    protected $sPrefix='ml_';
    /**
     * filter
     * @var ML_Magento_Helper_Model_ProductList_Filter $oFilter
     */
    protected $oFilter=null;
    /**
     * list/result
     * @var ML_Magento_Helper_Model_ProductList_List $oList
     */
    protected $oList=null;
    protected $sOrder='';
    /**
     *
     * @var Mage_Catalog_Model_Resource_Product_Collection $oCollection
     */
    protected $oCollection=null;
    public function __construct(){
        /* @var $oCollection Mage_Catalog_Model_Resource_Product_Collection*/
        $oCollection=Mage::getResourceModel('catalog/product_collection');
        
        $oCollection->addAttributeToSelect('*');
        $oSelect=$oCollection->getSelectSql();
        // marketplace language
        try {
            $aConfig = MLModule::gi()->getConfig();
            $iStoreId = (int)(isset($aConfig['lang']) ? $aConfig['lang'] : 0);
            $oCollection->setStore($iStoreId);
            $oCollection
                ->joinField(
                    'store_id', 
                    Mage::getSingleton('core/resource')->getTableName('catalog_category_product_index'), 
                    'store_id', 
                    'product_id=entity_id', 
                    '{{table}}.store_id = '.$iStoreId, 
                    'left'
                )
            ;
        } catch (ML_Filesystem_Exception $oEx) {//no modul
        } catch (Mage_Core_Model_Store_Exception $oEx) {//store not exists
        }
        $oSelect->group('e.entity_id');//magento adds field by join attribute or table, so distinct dont work propper $oSelect->distinct(true);
        $this->oCollection=$oCollection;
        $this->oFilter = MLHelper::gi('model_productlist_filter')
            ->clear()
            ->setCollection($oCollection)
            ->setPrefix($this->sPrefix)
        ;
        
        $this->initList();
        $this->oList
            ->clear()
            ->setCollection($oCollection);

//        echo $oSelect->assemble();
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
                ->setOrder(isset($aFilter['meta']['order'])?$aFilter['meta']['order']:'')
            ;
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
        $aMainIds=array();
        if ($blPage) {
            foreach ($this->oCollection->getData() as $aProduct) {
                $aMainIds[] = MLProduct::factory()->loadByShopProduct(
                    Mage::getModel('catalog/product')->load($aProduct['entity_id'])
                )->get('id');
            }
        } else {
            foreach ($this->oCollection->getAllIds() as $iShopId) {
                $aMainIds[] = MLProduct::factory()->loadByShopProduct(
                    Mage::getModel('catalog/product')->load($iShopId)
                )->get('id');
            }
        }
        return $aMainIds;
    }
    abstract protected function executeFilter();
    
    public function getHead(){
        return $this->oList->getHeader();
    }
    public function getList(){
//        $aLimit=$this->oFilter->getLimit();
//        new dBug($this->oCollection->getAllIds($aLimit[0],$aLimit[1]));
//        new dBug($this->oCollection->getSelectSql()->getPart(Zend_Db_Select::FROM));
//        echo $this->oCollection->getSelectSql();
        return new ArrayIterator($this->oList->getList());
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
        $this->oCollection->getSelectSql()->limit($iCount, $iFrom);
        return $this;
    }
}
