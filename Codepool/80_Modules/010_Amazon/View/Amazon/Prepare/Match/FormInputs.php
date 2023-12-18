<?php class_exists('ML', false) or die() ?>
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
 * (c) 2010 - 2019 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

$aPreparedData = $this->getCurrentProduct();
$oModul = MLModul::gi();
$oModulHelper = MLFormHelper::getModulInstance();
//    $oShop=  MLShop::gi();
$aRequest = $this->getRequest('amazonProperties');
$sPreparedTs = isset($aRequest['preparedts']) ? $aRequest['preparedts'] : date('Y-m-d H:i:s');
$iShipping = isset($aRequest['shipping']) ? $aRequest['shipping'] : (isset($aPreparedData) ? $aPreparedData['WillShipInternationally'] : $oModul->getConfig('internationalshipping'));
$sCondition = isset($aRequest['conditiontype']) ? $aRequest['conditiontype'] : (isset($aPreparedData) ? $aPreparedData['ConditionType'] : $oModul->getConfig('itemcondition'));
$sNote = isset($aRequest['ConditionNote']) ? $aRequest['ConditionNote'] : (isset($aPreparedData) && isset($aPreparedData['ConditionNote']) ? $aPreparedData['ConditionNote'] : '');
$iShippingTime = isset($aPreparedData) ? $aPreparedData['ShippingTime'] : MLModul::gi()->getConfig('leadtimetoship');
$bB2bActive = isset($aRequest['B2BActive']) ? $aRequest['B2BActive'] : (isset($aPreparedData) && isset($aPreparedData['B2BActive']) ? $aPreparedData['B2BActive'] : MLDatabase::factory('preparedefaults')->getValue('b2bactive'));

// Merchant Shipping Group Support
if (MLModul::gi()->getConfig('shipping.template.active') == '1') {
    $aTemplates = MLModul::gi()->getConfig('shipping.template');
    $aTemplateName = MLModul::gi()->getConfig('shipping.template.name');

    $sPreparedTemplate = isset($aPreparedData) && isset($aPreparedData['ShippingTemplate']) ? $aTemplateName[$aPreparedData['ShippingTemplate']] : '';

    $aMSGValues = array();
    foreach ($aTemplates as $iKey => $aValue) {
        if ($aValue['default'] == '1' && empty($sPreparedTemplate)) {
            $sPreparedTemplate = $aTemplateName[$iKey];
        }
        $aMSGValues[$iKey] = $aTemplateName[$iKey];
    }

} else {
    $aMSGValues = array();
}

?>
<div class="clear"></div>
<input  type="hidden" name="<?php echo MLHttp::gi()->parseFormFieldName('amazonProperties[preparedts]'); ?>" value="<?php echo $sPreparedTs; ?>" />
<table class="amazon_properties amazon_properties2">
    <thead><tr><th colspan="2"><?php echo $this->__('ML_GENERIC_PRODUCTDETAILS'); ?></th></tr></thead>
    <tbody>
    <tr>
        <td class="label top">
            <?php echo $this->__('ML_AMAZON_CONDITION_DESCRIPTION') ?><br>
            <span class="normal"><?php echo sprintf($this->__('ML_AMAZON_X_CHARS_LEFT'), '<span id="charsLeft">0</span>') ?></span>
        </td>
        <td class="options">
            <textarea id="item_note" name="<?php echo MLHttp::gi()->parseFormFieldName('amazonProperties[ConditionNote]') ?>" wrap="soft" cols="100" rows="10" class="fullwidth"><?php echo $sNote ?></textarea>
        </td>
    </tr>
    <tr class="odd">
        <td class="label"><?php echo $this->__('ML_GENERIC_CONDITION'); ?></td>
        <td class="options">
            <select id="item_condition" name="<?php echo MLHttp::gi()->parseFormFieldName('amazonProperties[conditiontype]') ?>">
                <?php foreach ($oModulHelper->getConditionValues() as $sKey => $sValue) { ?>
                    <option <?php echo ($sCondition == $sKey ? 'selected="selected" ' : '') ?>value="<?php echo $sKey ?>"><?php echo $sValue; ?></option>
                <?php } ?>
            </select>
        </td>
    </tr>
    <tr class="odd">
        <td class="label"><?php echo $this->__('ML_AMAZON_SHIPPING_TIME'); ?></td>
        <td class="options">
            <select name="<?php echo MLHttp::gi()->parseFormFieldName('amazonProperties[ShippingTime]') ?>">
                <option><?php echo $this->__('ML_AMAZON_SHIPPING_TIME_DEFAULT_VALUE'); ?></option>
                <?php for ($i = 1; $i < 31; $i++) { ?>
                    <option <?php echo($iShippingTime == $i ? 'selected="selected" ' : '') ?>value="<?php echo $i ?>"><?php echo $i ?></option>
                <?php } ?>
            </select>
        </td>
    </tr>
    <tr class="odd">
        <td class="label top">
            <?php echo  $this->__('amazon_prepare_apply_form__field__b2bactive__label'); ?><br>
            <span class="normal"><?php echo sprintf($this->__('amazon_prepare_apply_form__field__b2bactive__help_matching')) ?></span>
        </td>
        <td class="radio">
            <input type="radio" id="b2bactive_true" value="true" name="b2bactive" <?php echo ($bB2bActive) ? 'checked="checked"': '' ?>>
            <label for="b2bactive_true"><?php echo  $this->__('amazon_config_prepare__field__b2bactive__values__true'); ?></label>
            <input type="radio" id="b2bactive_false" value="false" name="b2bactive" <?php echo (!$bB2bActive) ? 'checked="checked"': '' ?>>
            <label for="b2bactive_false"><?php echo  $this->__('amazon_config_prepare__field__b2bactive__values__false'); ?></label>
            <script type="text/javascript">
                (function ($) {
                    jqml(document).ready(function() {
                        jqml('input[name="b2bactive"]').on('change', function () {
                            jqml('#b2bactive').val(jqml(this).val());
                        });
                    });
                })(jqml);
            </script>
            <input type="hidden" id="b2bactive" name="<?php echo MLHttp::gi()->parseFormFieldName('amazonProperties[B2BActive]') ?>" value="<?php echo $bB2bActive ? 'true' : 'false'; ?>">
        </td>
    </tr>
    <?php if (!empty($aMSGValues)) { ?>
        <tr class="last">
            <td class="label"><?php echo $this->__('ML_AMAZON_MERCHANT_SHIPPING_GROUP'); ?></td>
            <td class="options">
                <select name="<?php echo MLHttp::gi()->parseFormFieldName('amazonProperties[ShippingTemplate]') ?>">
                    <?php foreach($aMSGValues as $iKey => $MSGValue) { ?>
                        <option <?php echo($MSGValue == $sPreparedTemplate ? 'selected="selected" ' : '') ?>value="<?php echo $iKey ?>"><?php echo $MSGValue ?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>
    <?php } ?>
    <?php if (MLModul::gi()->getConfig('shipping.template.active') != '1') { ?>
        <tr class="last">
            <td class="label"><?php echo $this->__('ML_GENERIC_SHIPPING'); ?></td>
            <td class="options">
                <select id="amazon_shipping" name="<?php echo MLHttp::gi()->parseFormFieldName('amazonProperties[shipping]') ?>">
                    <?php foreach ($oModulHelper->getShippingLocationValues() as $iKey => $sValue) { ?>
                        <option <?php echo ($iShipping == $iKey ? 'selected="selected" ' : '') ?>value="<?php echo $iKey ?>"><?php echo $sValue; ?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>
<div class="clear"></div>
<?php MLSettingRegistry::gi()->addJs('magnalister.amazon.countChars.js'); ?>
