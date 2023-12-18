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

$enabled = isset($aField['value']) ? $aField['value'] : 'false';
if (!isset($aField['values']) && isset($aField['i18n']['values'])) {
    $aField['values'] = $aField['i18n']['values'];
}
$aField['type'] = 'radio';
$this->includeType($aField);
if (MLModul::gi()->getConfig('directbuyactive') === null && MLModul::gi()->getConfig('checkout.token') !== null) {
    ?>
    <div id="js-ml-modal-idealo-api-migration-warning" style="display:none;" title="<?php echo MLI18n::gi()->get('idealo_switching_to_moapiv2_popup_title') ?>">
        <p><?php echo MLI18n::gi()->get('idealo_switching_to_moapiv2_popup_text') ?></p>
    </div>
    <?php
}

$sMpId = MLModul::gi()->getMarketPlaceId();
$sMpName = MLModul::gi()->getMarketPlaceName();
?>

<script>
    (function ($) {
        function enableDirectbuy(enable, cls) {
            jqml(cls).parent().find('input, select').prop('disabled', !enable);
        }

        function showMessage(message) {
            jqml('<div class="ml-modal dialog2" title="<?php echo addslashes($aField['i18n']['label']) ?>"></div>').html(message)
                .jDialog({
                    width: '500px'
                });
        }

        jqml('#<?php echo $aField['id'].'_true'; ?>').click(function() {
            <?php if (isset($aField['disable'])) { ?>
                jqml('#<?php echo $aField['id'].'_false'; ?>').click();
            <?php } else { ?>
                showMessage('<?php echo str_replace("\n", ' ', addslashes($aField['i18n']['help'])) ?>');
                enableDirectbuy(true, '.js-directbuy');
            <?php } ?>
        });

        jqml('#<?php echo $aField['id'].'_false'; ?>').click(function() {
            enableDirectbuy(false, '.js-directbuy');
        });

        <?php if ($enabled === 'false') { ?>
            jqml(document).ready(function() {
                enableDirectbuy(false, '.js-directbuy');
            });
        <?php } ?>

        var eModal = jqml('#js-ml-modal-idealo-api-migration-warning');
        if (eModal.length > 0) {
            eModal.dialog({
                modal: true,
                width: '600px',
                buttons: [
                    {
                        text: "<?php echo $this->__('ML_BUTTON_LABEL_OK'); ?>",
                        click: function () {
                            jqml(this).dialog("close");
                            mlIdealoRemoveOldCheckoutToken();
                            return false;
                        }
                    }
                ]
            });
        }
        function mlIdealoRemoveOldCheckoutToken() {
                $.ajax({
                    url: '<?php echo $this->getUrl(array('controller' => "{$sMpName}:{$sMpId}_config_account")); ?>',
                    type: 'GET',
                    data: {'<?php echo MLHttp::gi()->parseFormFieldName('method') ?>':'dontShowWarning'}
                });
                jqml('#js-ml-modal-idealo-api-migration-warning').remove();
        }
    })(jqml);
</script>
