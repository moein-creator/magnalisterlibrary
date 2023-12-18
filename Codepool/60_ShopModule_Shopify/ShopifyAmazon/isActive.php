<?php
global $magnaConfig;
return (
        isset($magnaConfig['maranon']['Marketplaces'][MLRequest::gi()->data('mp')]) && $magnaConfig['maranon']['Marketplaces'][MLRequest::gi()->data('mp')]=='amazon' //amazon module activation
        &&
        (isset($GLOBALS['APP_SHOPIFY_DETECTOR']) && $GLOBALS['APP_SHOPIFY_DETECTOR'] == true) //shopify shop activation
)?true:false;