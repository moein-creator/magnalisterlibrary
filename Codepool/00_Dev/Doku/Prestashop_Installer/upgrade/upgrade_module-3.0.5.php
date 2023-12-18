<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_3_0_5($object)
{
    $file_client_version = dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'ClientVersion';
    if (file_exists($file_client_version)) {
        unlink($file_client_version);
    }
    if (file_exists($file_client_version)) {
        return false;
    } else {
        return true;
    }
}
