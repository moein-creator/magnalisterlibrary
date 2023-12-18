<?php
return (!class_exists('Redgecko\Magnalister\Controller\MagnalisterController') && (class_exists('Enlight_Application',false) &&  Enlight_Application::Instance()->App() == 'Shopware') ? true : false);
