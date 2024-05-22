<?php

class ML_ShopwareCloud_Helper_ShopwareCloudInterfaceRequestHelper {
    /**
     * @var ML_ShopwareCloud_Helper_ShopwareCloudInterfaceRequestHelper|null
     */
    private static $instance = null;

    /**
     * @var array
     */
    private $logPerRequest = array();

    /**
     * @var int
     */
    private $totalRequestTime = 0;

    /**
     * @var array
     */
    private $aRequestCache = array();

    /**
     * @var MLCache
     */
    private $mlCache = null;

    /**
     * @var array - Of regex urls and cache time
     */
    private $cacheUrlAndTimes = array(
        '/https:\/\/[a-z.]*\/admin\/shop.json/m' => 3600,
        '/https:\/\/[a-z.]*\/admin\/custom_collections\.json\?limit=[0-9]*&page=[0-9]*/m' => 1800,
        '/https:\/\/[a-z.]*\/admin\/smart_collections\.json\?limit=[0-9]*&page=[0-9]*/m' => 1800,
    );

    /**
     * @var int
     */
    private $currentCacheTime = 0;

    public function __construct() {
        $this->mlCache = MLCache::gi();
    }

    /**
     * @return ML_ShopwareCloud_Helper_ShopwareCloudInterfaceRequestHelper
     */
    public static function gi() {
        if (self::$instance == NULL) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @return array
     */
    public function getLogPerRequest() {
        return $this->logPerRequest;
    }

    /**
     * @param array $logPerRequest
     */
    public function setLogPerRequest($logPerRequest) {
        // increase the total request time
        $this->totalRequestTime += $logPerRequest['time'];

        // add request to logging
        $this->logPerRequest[] = $logPerRequest;
    }

    /**
     * Returns the total time in seconds of all requests
     *
     * @return int
     */
    public function getTotalRequestTime() {
        return $this->totalRequestTime;
    }

    private function getCacheKey($requestParams) {
        $data = array(
            'request' => json_encode(array(
                'url' => $requestParams['url'],
                'method' => $requestParams['method'],
                'headers' => $requestParams['headers'],
                'body' => $requestParams['body'],
            )),
        );

        return md5(json_encode($data));
    }

    private function getMlCacheName($cacheKey) {
        $sCacheName =
            strtoupper(__class__).'__'.
            strtolower(
                $cacheKey
            ).'.json';

        return $sCacheName;
    }

    private function checkMlCache($url, $cacheKey) {
        if (!$this->allowedToStoreCacheByUrl($url)) {
            return;
        }

        $sMlCacheName = $this->getMlCacheName($cacheKey);

        $oCache = MLCache::gi();
        if ($this->mlCache->exists($sMlCacheName)) {
            $this->aRequestCache[$cacheKey] = $oCache->get($sMlCacheName);
        }
    }

    private function allowedToStoreCacheByUrl($url) {
        foreach ($this->cacheUrlAndTimes as $regEx => $iCacheTime) {
            preg_match($regEx, $url, $matches, 0);
            if (!empty($matches)) {
                $this->currentCacheTime = $iCacheTime;
                return true;
            }
        }

        return false;
    }

    /**
     * @param $requestParams
     * @return bool
     */
    public function requestIsCached($requestParams) {
        $sKey = $this->getCacheKey($requestParams);

        if (!isset($this->aRequestCache[$sKey])) {
            $this->checkMlCache($requestParams['url'], $sKey);
        }

        return isset($this->aRequestCache[$sKey]);
    }

    /**
     * @param $requestParams
     * @return array|null
     */
    public function getRequestCache($requestParams) {
        $sKey = $this->getCacheKey($requestParams);

        // check if its already cached
        if (!$this->requestIsCached($requestParams)) {
            return null;
        }

        return $this->aRequestCache[$sKey];
    }

    /**
     * @param $requestParams
     * @param $response
     * @param $responseCode
     * @param $responseHeaderSize
     */
    public function setRequestCache($requestParams, $response, $responseCode, $responseHeaderSize) {
        $sKey = $this->getCacheKey($requestParams);

        $this->aRequestCache[$sKey] = array(
            'response' => $response,
            'responseCode' => $responseCode,
            'responseHeaderSize' => $responseHeaderSize,
        );

        $sMlCacheName = $this->getMlCacheName($sKey);
        $this->mlCache->set($sMlCacheName, $this->aRequestCache[$sKey], $this->currentCacheTime);
    }

}
