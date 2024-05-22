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
use Redgecko\Magnalister\Service\MediaConverter;
use Shopware\Core\Checkout\Cart\Price\Struct\CartPrice;
use Shopware\Core\Checkout\Order\Aggregate\OrderTransaction\OrderTransactionStates;
use Shopware\Core\Checkout\Order\OrderStates;
use Shopware\Core\Content\Media\Aggregate\MediaThumbnail\MediaThumbnailEntity;
use Shopware\Core\Content\Media\MediaCollection;
use Shopware\Core\Content\Media\MediaEntity;
use Shopware\Core\Content\Media\Thumbnail\ThumbnailService;
use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Api\Context\SystemSource;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Sorting\FieldSorting;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\Currency\CurrencyFormatter;
use Shopware\Core\System\SalesChannel\Context\SalesChannelContextService;

MLFilesystem::gi()->loadClass('Base_Model_Image');

class ML_Shopware6_Model_Image extends ML_Base_Model_Image {

    private $startTime = null;

    public function __construct() {
        $this->startTime = microtime(true);
    }

    /**
     * @var mixed
     */
    protected static $shopwareMedia = [];

    public function getimagesize($sSrc) {
        $size = parent::getimagesize($sSrc);
        if (!$size) {
            $size = $this->getShopwareImageSize($sSrc);
        }
        return $size;
    }

    protected function normalizeURL($sSrc) {
        try {
            $imagePath = $this->getShopwareImageUrlWithImageName($sSrc);
            $sSrc = $imagePath;
        } catch (\Throwable $ex) {
            MLMessage::gi()->addDebug($ex);
        }
        return $sSrc;
    }

    /**
     * Use Shopware thumbnail generator as fallback, useful by cloud media server.
     * @param $sSrc
     * @param $sType
     * @param $iMaxWidth
     * @param $iMaxHeight
     * @param bool $blUrlOnly
     * @return array|string
     * @throws Exception
     */
    protected function resizeImageFallback($sSrc, $sType, $iMaxWidth, $iMaxHeight, $blUrlOnly, $imgHash, $originalSrc) {
        try {
            $sDst = $this->getShopwareImageUrlWithImageName($sSrc);
        } catch (Throwable $oEx) {
            $sDst = $originalSrc;
            MLMessage::gi()->addDebug($oEx);
        }
        if ($blUrlOnly) {
            $mResult = self::$cache[$imgHash] = $sDst;
        } else {
            $mResult = self::$cache[$imgHash] = array(
                'url'    => $sDst,
                'width'  => $iMaxWidth,
                'height' => $iMaxHeight,
                'alt'    => basename($sSrc)
            );
        }
        return $mResult;
    }

    /**
     * @param $sSrc
     * @param $sDst
     * @return bool
     */
    protected function cacheFoundImageInDB($sSrc, $sDst) {
        /** @var ML_Base_Model_Table_Image $oTable */
        $oTable = MLDatabase::getTableInstance('image')
            ->init()
            ->set('sourcePath', $sSrc);
        if (@copy($sSrc, $sDst)) {
            $oTable->set('destinationPath', MLHttp::gi()->getImagePath($sDst));
        } else {
            $oTable->set('destinationPath', $sSrc);
        }
        $oTable->load();
        $oTable->destinationIsActual(true);
        return true;
    }

    protected function getShopwareImageSize($sSrc) {
        try {
            $imagePath = $this->getShopwareImageUrlWithImageName($sSrc);
            $oGDImage = imagecreatefromstring(MagnalisterController::getFileSystem()->read($imagePath));
            return [imagesx($oGDImage), imagesy($oGDImage)];

        } catch (\Throwable $ex) {
            MLMessage::gi()->addDebug($ex);
            return false;
        }

    }

    protected function removeExistingImage($sDst): void {
        if (MagnalisterController::getFileSystem()->has($sDst)) {
            MagnalisterController::getFileSystem()->delete($sDst);
        }
    }

    protected function copyImage($sSrc, $sDst): bool {
        try {
            MagnalisterController::getFileSystem()->copy($sSrc, $sDst);
            return true;
        } catch (\Throwable $ex) {
            MLMessage::gi()->addDebug($ex);
            return false;
        }
    }

    /**
     * @param $sSrc
     * @return array|false|string
     * @throws \League\Flysystem\FileNotFoundException
     */
    protected function getImageContent($sSrc) {
        $sImageContent = MagnalisterController::getFileSystem()->read($sSrc);
        return array('', $sImageContent);
    }

    protected function saveGeneratedImage($image, $sDst, $iCompression) {
        $temporaryImagePath = MLFilesystem::getCachePath('tempimage'.Uuid::randomHex());
        $success = @imagejpeg($image, $temporaryImagePath, $iCompression);
        if ($success) {
            if (method_exists(MagnalisterController::getFileSystem(), 'put')) {//6.4
                MagnalisterController::getFileSystem()->put($sDst, file_get_contents($temporaryImagePath));
            } else {//6.5
                MagnalisterController::getFileSystem()->write($sDst, file_get_contents($temporaryImagePath));
            }
            unlink($temporaryImagePath);
            $success = true;
        }
        return $success;
    }


    /**
     * @param $sSrc
     * @return MediaEntity
     */
    public function getMediaObject($sSrc) {
        $aSrc = parse_url($sSrc);
        if (isset($aSrc['path'])) {
            $sSrcPath = $aSrc['path'];
        }
        $sExtension = pathinfo($sSrc, PATHINFO_EXTENSION);
        $sFileName = str_replace(array('.'.$sExtension), '', pathinfo($sSrcPath, PATHINFO_BASENAME));
        $sSrcHash = md5($sFileName);
        try {
            if (!array_key_exists($sSrcHash, self::$shopwareMedia)) {
                //                MLMessage::gi()->addDebug($sSrc.'---'.__LINE__.':'.microtime(true),
                //                    array(
                //                        self::$shopwareMedia,
                //                        array_key_exists($sSrcHash, self::$shopwareMedia),
                //                        isset(self::$shopwareMedia[$sSrcHash])
                //                    ));
                $criteria = new Criteria();

                // check if in media cache a value exists
                try {
                    $aMediaCache = MLSetting::gi()->get('oShopwareMediaCache');
                } catch (Exception $ex) {
                    $aMediaCache = array();
                }

                if (array_key_exists($sSrc, $aMediaCache)) {
                    $oMedia = $aMediaCache[$sSrc];
                } else {
                    $oMedia = MLShopware6Alias::getRepository('media')
                        ->search($criteria->addFilter(new EqualsFilter('fileName', $sFileName)), Context::createDefaultContext())->first();
                }

                // Fallback try to find image by FileName
                if ($oMedia === null) {
                    if ($sFileName === strtolower($sFileName)) {
                        $sFileName = strtoupper($sFileName);
                    } else {
                        $sFileName = strtolower($sFileName);
                    }
                    $oMedia = MLShopware6Alias::getRepository('media')
                        ->search($criteria->addFilter(new EqualsFilter('fileName', $sFileName)), Context::createDefaultContext())->first();
                }
                self::$shopwareMedia[$sSrcHash] = $oMedia;
            }

        } catch (\Throwable $ex) {
            MLMessage::gi()->addDebug($ex);
        }
        return self::$shopwareMedia[$sSrcHash];
    }

    /**
     * @param $ex Exception
     * @return null
     * @throws Exception
     */
    protected function handleExceptions($ex) {
        if ($ex->getCode() === 1652878471) {
            throw $ex;
        }
        MLMessage::gi()->addDebug($ex);
    }

    protected function getShopwareImageUrlWithImageName($sSrc): string {
        $media = $this->getMediaObject($sSrc);
        if ($media === null) {
            throw new \Exception('The image cannot be found in media table of shopware:'.$sSrc);
        }
        $imagePath = MagnalisterController::getMediaConverter()->getRelativeMediaUrl($media);
        return $imagePath;
    }


    public function getDestinationPath($sSrc, $sType, $iMaxWidth, $iMaxHeight) {
        if (MLHelper::getRemote()->isUrl($sSrc)) {
            $sSrc = parse_url($sSrc, PHP_URL_PATH);
        }
        $sFileName = pathinfo($sSrc, PATHINFO_BASENAME);
        $sDst = $sType.'/'.$iMaxWidth.($iMaxWidth === $iMaxHeight ? '' : 'x'.$iMaxHeight).'px/'.$sFileName;
        return $sDst;
    }

}
