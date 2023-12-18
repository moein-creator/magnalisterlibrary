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

class_exists('ML', false) or die();
?>
<?php
/* @var $this  ML_Amazon_Controller_Amazon_ShippingLabel_Orderlist */
/* @var $oList ML_Amazon_Model_List_Amazon_Order */
/* @var $aStatistic array */

$sMpId = MLModul::gi()->getMarketPlaceId();
$sMpName = MLModul::gi()->getMarketPlaceName();

$sUrlPrefix = "{$sMpName}:{$sMpId}_";
$sI18nPrefix = 'ML_' . ucfirst($sMpName) . '_';
?>
<table class="actions">
    <tbody class="firstChild">
        <tr>
            <td>
                <div class="actionBottom">
                    <form action="<?php echo $this->getUrl(array('controller' => "{$sUrlPrefix}shippinglabel_overview")); ?>" method="POST">
                        <?php foreach (MLHttp::gi()->getNeededFormFields() as $sName => $sValue) { ?>
                            <input type="hidden" name="<?php echo $sName ?>" value="<?php echo $sValue ?>"/>
                        <?php } ?>
                        <button type="submit" name="<?php echo MLHttp::gi()->parseFormFieldName('method')?>" value="delete" class="mlbtn ml-js-config-reset left" >
                            <?php echo $this->__('ML_Amazon_Shippinglabel_Delete') ?>
                        </button>
                        <button type="submit" name="<?php echo MLHttp::gi()->parseFormFieldName('method')?>" value="cancel" class="mlbtn action right">
                            <?php echo $this->__('ML_Amazon_Shippinglabel_Cancel') ?>
                        </button>
                        <button type="submit" name="<?php echo MLHttp::gi()->parseFormFieldName('method')?>" value="download" class="mlbtn ml-js-config-reset right" style="margin-right: 1em;">
                            <?php echo $this->__('ML_Amazon_Shippinglabel_Download') ?>
                        </button>
                    </form>
                    <div class="clear"></div>
                </div>
            </td>
        </tr>
    </tbody>
</table>
<?php if($this->getDownloadLink() !== null){ ?>
<script type="text/javascript">//<![CDATA[
    (function ($) {
        jqml(document).ready(function () {
            var eModal = jqml('<div title="<?php echo MLI18n::gi()->get('ML_Amazon_Shippinglabel_Download_Title') ?>"><?php echo MLI18n::gi()->get('ML_Amazon_Shippinglabel_Overview_Popup_Afterconfirm_Infocontent') ?><a class="ml-downloadshippinglabel" target="_blank" href="<?php echo $this->getDownloadLink() ?>"></a></div>');
            eModal.dialog({
                modal: true,
                width: '600px',
                buttons: [
                    {
                        text: "DOWNLOAD",
                        click: function () {
                                if (jqml('.ml-downloadshippinglabel').length > 0) {
                                    jqml('.ml-downloadshippinglabel')[0].click();
                                }
                                return false;
                        }
                    },
                    {
                        text: "OK",
                        click: function () {
                            jqml(this).dialog("close");
                            return false;
                        }
                    }
                ]
            });
        });
    })(jqml);
    //]]></script>
<?php }