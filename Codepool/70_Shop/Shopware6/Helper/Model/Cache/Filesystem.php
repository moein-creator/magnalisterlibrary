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

use Redgecko\Magnalister\Controller\MagnalisterController;

MLFilesystem::gi()->loadClass("core_model_cache_fs");

/**
 * A filesystem driver class for the cache system.
 * @todo use MLHelper::getFilesystemInstance();
 */
class ML_Shopware6_Helper_Model_Cache_Filesystem extends ML_Core_Model_Cache_Fs {

    protected $sMagnalisterDirectoryName = 'RedMagnalister';

    /**
     * Creates an instance of this class
     * @return self
     */
    public function __construct() {
        if (!MagnalisterController::getFileSystem()->has($this->sMagnalisterDirectoryName)) {
            $this->createDirectory();
        }
    }

    protected function createDirectory(): void {
        if (method_exists(MagnalisterController::getFileSystem(), 'createDir')) {//6.4
            MagnalisterController::getFileSystem()->createDir($this->sMagnalisterDirectoryName);
        } else {//6.5
            MagnalisterController::getFileSystem()->createDirectory($this->sMagnalisterDirectoryName);
        }
    }

    /**
     * Set a cache value.
     * @param string $sKey
     *    Cache id
     * @param mixed $mValue
     *    Value that will be cached
     * @param int $iLifeTime
     *    Life time in seconds
     * @return self
     */
    public function set($sKey, $sContent, $iLifetime) {
        $sFilename = $this->getFilePath($sKey);
        $sContent .= "\n" . (time() + $iLifetime);
        if (MagnalisterController::getFileSystem()->has($sFilename)) {
            MagnalisterController::getFileSystem()->delete($sFilename);
        }
        $sDir = dirname($sFilename);
        $this->fileOrDirectoryExists($sDir);

        $writeResult = $this->writeIntoFile($sFilename, $sContent);
        if (!$writeResult && (MagnalisterController::getFileSystem()->has($sFilename))) {
            MagnalisterController::getFileSystem()->delete($sFilename);
        }
        return $this;
    }

    /**
     * Returns the file path of a cache id.
     * @param string $sKey
     * @return string
     */
    protected function getFilePath($sKey = '') {
        return $this->sMagnalisterDirectoryName . '/' . $sKey;
    }

    /**
     * Delete a cache id from the cache.
     * @param string $sKey
     * return ML_Magnalister_Model_Cache_Abstract
     *    A list of deleted keys
     */
    public function delete($sKey) {
        $sFilename = $this->getFilePath($sKey);
        MagnalisterController::getFileSystem()->delete($sFilename);
        return $this;
    }

    protected function fileOrDirectoryExists(string $sDir): void {
        if (MagnalisterController::getFileSystem()->has($sDir)) {
            if (method_exists(MagnalisterController::getFileSystem(), 'createDir')) {//6.4
                MagnalisterController::getFileSystem()->createDir($sDir);
            } else {//6.5
                MagnalisterController::getFileSystem()->createDirectory($sDir);
            }
        }
    }

    /**
     * @param string $sFilename
     * @param string $sContent
     * @return mixed
     */
    protected function writeIntoFile(string $sFilename, string $sContent) {
        if (method_exists(MagnalisterController::getFileSystem(), 'put')) {//6.4
            $writeResult = MagnalisterController::getFileSystem()->put($sFilename, $sContent);
        } else {//6.5
            MagnalisterController::getFileSystem()->write($sFilename, $sContent);
            $writeResult = true;
        }
        return $writeResult;
    }

    /**
     * Get a value from the cache using a cache id.
     * @param string $sKey
     * @return mixed
     * @throws ML_Filesystem_Exception
     *    In case the cache is too old or the cache id does not exist.
     */
    public function get($sKey) {
        $sFilename = $this->getFilePath($sKey);
        if (MagnalisterController::getFileSystem()->has($sFilename)) {
            $sContent = MagnalisterController::getFileSystem()->read($sFilename);
            $aContent = explode("\n", $sContent);
            if (is_array($aContent)) {
                if (count($aContent) === 2) {
                    $sData = $aContent[0];
                    $sExpirationDate = (int)$aContent[1];
                    if ($sExpirationDate < time()) {
                        MagnalisterController::getFileSystem()->delete($sFilename);
                        throw new ML_Filesystem_Exception("This cache key is too old: $sFilename");
                    }
                    return $sData;
                }
            }
        }
        throw new ML_Filesystem_Exception("This cache key does not exist: $sFilename");
    }

    /**
     * Get information of expiration
     * @param string $sKey
     * @return array
     */
    public function getInfo($sKey) {
        return array();
    }

    /**
     * Flushes the cache.
     * @ToDo $pattern should be implemented
     * @param string $pattern
     * @return $this
     */
    public function flush($pattern = '*') {
        foreach ($this->getList() as $sFile) {
            $this->delete(basename($sFile));
        }
        return $this;
    }

    /**
     * Get a list of all cached cache ids.
     * @return array
     */
    public function getList() {
        $aFileList = array();
        foreach (MagnalisterController::getFileSystem()->listContents($this->sMagnalisterDirectoryName) as $aFile) {
            $aFileList[] = substr($aFile['path'], strlen($this->sMagnalisterDirectoryName) + 1);
        }
        return $aFileList;
    }

}
