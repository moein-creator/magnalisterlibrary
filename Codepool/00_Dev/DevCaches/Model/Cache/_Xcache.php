<?php

 MLFilesystem::gi()->loadClass("model_cache_abstract");
 /**
  * This class require Xcache extension
  */
 class ML_DevCaches_Model_Cache_Xcache extends ML_Core_Model_Cache_Abstract {
    /**
      * @var array List all keys of cached data and their associated ttl
      */
     protected $aKeys = array () ;
     public function __construct() {
         $this->aKeys = xcache_get(self::KEYS_NAME) ;
         if ( !is_array($this->aKeys) )
             $this->aKeys = array () ;
     }

     /**
      * @see Cache::_set()
      */
     protected function set($key , $value , $ttl = 0) {
         return xcache_set($key , $value , $ttl) ;
     }

     /**
      * @see Cache::_get()
      */
     protected function get($key) {
         return xcache_isset($key)?xcache_get($key):false ;
     }

     /**
      * @see Cache::_exists()
      */
     protected function exists($key) {
         return xcache_isset($key) ;
     }

     /**
      * @see Cache::_delete()
      */
     protected function delete($key) {
         return xcache_unset($key) ;
     }

     /**
      * @see Cache::flush()
      */
     public function flush($pattern = '*') {
         $this->delete($pattern) ;
         return true ;
     }

     protected function checkAvailablity() {
         if ( function_exists("xcache_get") )
             return true ;
         else
             return false ;
     }

 }

 