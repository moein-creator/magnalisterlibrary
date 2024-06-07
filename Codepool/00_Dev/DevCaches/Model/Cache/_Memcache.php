<?php

MLFilesystem::gi()->loadClass("model_cache_abstract");
/**
 * This class require PECL Memcache extension
 */
class ML_DevCaches_Model_Cache_Memcache extends ML_Core_Model_Cache_Abstract {
    /**
      * @var array List all keys of cached data and their associated ttl
      */
     protected $aKeys = array () ;
	/**
	 * @var Memcache $oMemcache
	 */
	protected $oMemcache;

	/**
	 * @var bool $blIsConnected Connection status
	 */
	protected $blIsConnected = false;

	public function __construct(){
		$this->connect();

        $this->aKeys = array();
        $all_slabs = $this->oMemcache->getExtendedStats('slabs');

        foreach ($all_slabs as $server => $slabs)
        {
            if (is_array($slabs))
            {
                foreach (array_keys($slabs) as $slab_id)
                {
                    $dump = $this->oMemcache->getExtendedStats('cachedump', (int)$slab_id);
                    if ($dump)
                    {
                       foreach ($dump as $entries)
                       {
                            if ($entries)
                                $this->aKeys = array_merge($this->aKeys, array_keys($entries));
                       }
                    }
                }
            }
        }
	}

	/**
	 * Connect to memcache server
	 */
	public function connect(){
		$this->oMemcache = new Memcache();
		$aServers = $this->getMemcachedServers();
		$this->oMemcache->addServer($aServers['ip'], $aServers['port'], $aServers['weight']);

		$this->blIsConnected = true;
        return $this;
	}

	/**
	 * @see Cache::_set()
	 */
	public function set($sKey , $mValue , $iLifetime = 0)	{
		$this->checkCOnnection();
		$this->oMemcache->set($sKey, $mValue, false, $iLifetime);
        $this->aKeys[$sKey] = $iLifetime ;
        return $this;
	}

	/**
	 * @see Cache::_get()
	 */
	public function get($sKey){
		checkCOnnection();
		return $this->oMemcache->get($sKey);
	}

	/**
	 * @see Cache::_exists()
	 */
	public function exists($sKey){
		return isset($this->aKeys[$sKey]) ;
	}

	/**
	 * @see Cache::_delete()
	 */
	public function delete($sKey)
	{
		$this->checkConnection();
		 $this->oMemcache->delete($sKey);
         return $this;
	}

	/**
	 * @see Cache::flush()
	 */
	public function flush($pattern = '*')
	{
		$this->checkConnection();
		return $this->oMemcache->flush();
	}

	/**
	 * Close connection to memcache server
	 *
	 * @return bool
	 */
	public function close(){
		$this->checkConnection();
		return $this->oMemcache->close();
	}

	/**
	 * Get list of memcached servers
	 *
	 * @return array
	 */
	public static function getMemcachedServers(){
		return array('ip'=>'127.0.0.1', 'port'=>'11211', 'weight'=>'1');
	}


     protected function checkAvailablity() {
        if( class_exists('Memcache') && extension_loaded('memcache') && function_exists('memcache_connect'))
            return true;
        else
            return false;
     }
     
     private function checkConnection(){
         if (!$this->blIsConnected)
        {
            throw new Exception("There is no memcache connection");
        }
     }
}
