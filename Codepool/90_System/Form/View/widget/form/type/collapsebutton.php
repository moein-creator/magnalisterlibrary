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
 * (c) 2010 - 2014 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */
if (!class_exists('ML', false))
    throw new Exception();
$marketplaceName = MLModul::gi()->getMarketPlaceName();
$sName = str_replace('field', '', $aField['name']);

// Getting type of tab (is it variation tab or apply form)
$selectorArray = explode('_button', $aField['id']);
$selector = $selectorArray[0];
$selector = $this->aFields[strtolower($selector)]['id'];

$sChangedSelector = ' ' . $selector;
$ini = strpos($sChangedSelector, $marketplaceName . '_prepare_');
if ($ini == 0) return '';
$ini += strlen($marketplaceName . '_prepare_');
$len = strpos($sChangedSelector, '_field', $ini) - $ini;
$tabType = substr($sChangedSelector, $ini, $len);

if ($tabType === 'variations') {
    $id = $marketplaceName . '_prepare_variations_field_saveaction';
} else {
    $id = $marketplaceName . '_prepare_apply_form_field_prepareaction';
}

?>
<button type="button" class="mlCollapse" id="<?php echo $aField['id']?>" name="<?php echo MLHTTP::gi()->parseFormFieldName($aField['name']);?>">
    <span class="mlCollapse" name="<?php echo $aField['id']?>"></span>
</button>
<script>
    (function($) {
        $(document).ready(function() {
            var matchedTable = $('div#attributeMatchedTable_<?php echo $selector ?>_sub');
            if (matchedTable.css('display') == 'none') {
                $('span.mlCollapse[name="<?php echo $aField['id']?>"]').css('background-position', '0 0px');
                matchedTable.hide();
            } else {
                $('span.mlCollapse[name="<?php echo $aField['id']?>"]').css('background-position', '0 -23px');
                matchedTable.show();
            }
        });
        
        $('button[name="<?php echo MLHTTP::gi()->parseFormFieldName($aField['name']);?>"]').click(function() {
            var matchedTable = $('div#attributeMatchedTable_<?php echo $selector ?>_sub');
            if (matchedTable.css('display') == 'none') {
                $('span.mlCollapse[name="<?php echo $aField['id']?>"]').css('background-position', '0 -23px');
                matchedTable.show();
            } else {
                $('span.mlCollapse[name="<?php echo $aField['id']?>"]').css('background-position', '0 0px');
                matchedTable.hide();
            }
        });
    })(jqml);
</script>