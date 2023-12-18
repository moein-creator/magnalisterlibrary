<?php

 MLFilesystem::gi()->loadClass("model_cache_abstract");
/**
 * This class require PECL APC extension
 */
class ML_DevCaches_Model_Cache_Apc extends ML_Core_Model_Cache_Abstract {
    /**
      * @var array List all keys of cached data and their associated ttl
      */
     protected $aKeys = array () ;
	public function __construct()
	{
		$this->aKeys = array();
		$cache_info = apc_cache_info('user');
		foreach ($cache_info['cache_list'] as $entry)
			$this->aKeys[$entry['info']] = $entry['ttl'];
	}

	/**
	 * @see Cache::_set()
	 */
	protected function set($key, $value, $ttl = 0)
	{
		return apc_store($key, $value, $ttl);
	}

	/**
	 * @see Cache::_get()
	 */
	protected function get($key)
	{
		return apc_fetch($key);
	}

	/**
	 * @see Cache::_exists()
	 */
	protected function exists($key)
	{
		return isset($this->aKeys[$key]);
	}

	/**
	 * @see Cache::_delete()
	 */
	protected function delete($key)
	{
		return apc_delete($key);
	}

	/**
	 * @see Cache::flush()
	 */
	public function flush($pattern = '*')
	{
		return apc_clear_cache();
	}

     protected function checkAvailablity() {
         if( function_exists("apc_store"))
             return true;
         else
             return false;
     }
}
