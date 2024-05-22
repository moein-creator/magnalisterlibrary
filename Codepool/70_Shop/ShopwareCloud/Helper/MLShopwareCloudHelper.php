<?php

class MLShopwareCloudHelper {
    public static function getStorageDateTime($time) {
        if ($time !== null) {
            $dateTime = new DateTime($time);
            return $dateTime->format(MLSetting::gi()->get('DEFAULTS_STORAGE_DATE_TIME_FORMAT'));
        }
        return null;
    }
}
