<?php
/**
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
 * $Id$
 *
 * (c) 2015 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the GNU General Public License v2 or later
 * -----------------------------------------------------------------------------
 */
if (!class_exists('ML', false))
    throw new Exception();

$localClientBuild = MLSetting::gi()->get('sClientBuild');
$localClientBuild = empty($localClientBuild) ? $this->__('ML_LABEL_UNKNOWN') : $localClientBuild;
$currentClientBuild = MLSetting::gi()->get('sCurrentBuild');
$currentClientBuild = empty($currentClientBuild) ? $this->__('ML_LABEL_UNKNOWN') : $currentClientBuild;

?>

<div class="bold">
    <span class="version-text">magnalister Version</span> <span class="version"><?php echo MLShop::gi()->getPluginVersion(); ?></span>
</div>