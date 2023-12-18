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
 * (c) 2010 - 2020 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

class_exists('ML', false) or die();
/** @var ML_InstallerPackage_Controller_Main_Tools_InstallerPackage $this */
?>
<?php ?>    
<form action="<?php echo $this->getCurrentUrl() ?>" method="post">
    <div>
        Instruction
        <ul>
            <li>
                Create new zip package via https://medusa.magnalister.com/magnalisterSetup/
            </li>
            <li>
                Download zip data from magnalister download https://www.magnalister.com/download/
            </li>
            <li>
                Extract zip file(in Prestashop you should extract magnalister.zip too)
            </li>
            <li>
                Select correct shop-system here
            </li>
            <li>
                Copy correct system path of extracted directory in Path of whole package.<br>
                example for prestashop : /Users/<username>/Downloads/magnalister_prestashop_v3.0.3/magnalister<br>
                example for shopware :
                /Users/<username>/Downloads/magnalister_shopware_v3.0.3/files/engine/Shopware/Plugins/Community/Backend/RedMagnalister<br>
            </li>
            <?php
            $sPath = realpath(MLFilesystem::getCachePath('../'.$this->getShopSystem().'/magnalister.zip'));
            if ($this->getShopSystem() !== null && file_exists($sPath)) {

                echo '<li><span style="color: green; ">You can find zip data here: '.$sPath.'</span></li>
                    <li><span style="color: green; ">Try to install it on one shopware shop to test it</span></li>
                    <li><span style="color: green; ">After testing you can upload it in Shopware app store</span></li>';
            } ?>

        </ul>
    </div>
    <?php foreach (MLHttp::gi()->getNeededFormFields() as $sName => $sValue) { ?>
        <input type="hidden" name="<?php echo $sName ?>" value="<?php echo $sValue ?>" />
    <?php } ?>

    <div><label for="wholepackagepath">Shop-System : </label>
        <select name="<?php echo MLHttp::gi()->parseFormFieldName('shopsystem'); ?>">
            <?php foreach (array('Prestashop' => 'Prestashop', 'Shopware' => 'Shopware', 'Shopify' => 'Shopify', 'WooCommerce' => 'WooCommerce') as $sShopSystem) { ?>
                <option<?php echo $sShopSystem == MLRequest::gi()->data('shopsystem') ? ' selected="selected"' : ''; ?> value="<?php echo $sShopSystem ?>"><?php echo $sShopSystem ?></option>
            <?php } ?>
        </select>
    </div>
    <div>
        <label for="wholepackagepath">Path of whole package : </label>
        <input style="width: 100%;"  id="wholepackagepath" type="text" name="<?php echo MLHttp::gi()->parseFormFieldName('wholepackagepath') ?>" value="<?php echo MLRequest::gi()->data('wholepackagepath') ?>" />
    </div>
    <div>
        <label for="version">Version of plugin(needed for prestashop) : </label>
        <input id="version" type="text" name="<?php echo MLHttp::gi()->parseFormFieldName('version') ?>" value="<?php echo MLShop::gi()->getPluginVersion() ?>" />
    </div>
    <div>
        <input class="mlbtn" type="submit" value="Create Installer" name="<?php echo MLHttp::gi()->parseFormFieldName('create') ?>"/>
    </div>

</form>