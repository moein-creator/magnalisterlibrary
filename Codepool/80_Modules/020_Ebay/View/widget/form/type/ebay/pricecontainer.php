<?php class_exists('ML', false) or die() ?>
<?php
/* @var  $this  ML_Ebay_Controller_Ebay_Prepare_Apply_Form */
$sListingType = $this->getField('listingType', 'value'); //StoresFixedPrice
$blStrikeprice = (boolean)MLModul::gi()->getConfig('strikeprice.active');
?>
<table class="ebayPrice ">
    <tbody>
    <tr>
        <th><?php echo $this->__('ML_EBAY_PRICE_CALCULATED') ?>:</th>
        <td colspan="2">
            <input type="hidden" name="<?php echo MLHTTP::gi()->parseFormFieldName($this->sOptionalIsActivePrefix.'[startprice]') ?>" value="<?php echo ($sListingType == 'Chinese') ? 'true' : 'false'; ?>"/>
            <?php echo $this->oProduct->getSuggestedMarketplacePrice(MLModul::gi()->getPriceObject($sListingType), true, true) ?>
        </td>
    </tr>
    <?php if ($blStrikeprice && $sListingType != 'Chinese'): ?>
        <tr id="tr_strikeprice" style="visibility:<?php if ($blStrikeprice)
            echo 'visible'; else echo 'hidden'; ?>">
            <th><?php echo $this->__('ML_EBAY_STRIKE_PRICE_CALCULATED') ?>:</th>
            <td colspan="2">
                <?php echo $this->oProduct->getSuggestedMarketplacePrice(MLModul::gi()->getPriceObject('strikeprice'), true, true) ?>
            </td>
        </tr>
    <?php endif; ?>
    <script type="text/javascript">/*<![CDATA[*/
        (function ($) {
            $(document).ready(function () {
                $('select[id="ebay_prepare_apply_form_field_strikeprice"]').change(function () {
                    var strike_price_select = $(this);
                    if (strike_price_select.val() !== 'true') {
                        document.getElementById('tr_strikeprice').style.visibility = 'hidden';
                    } else {
                        document.getElementById('tr_strikeprice').style.visibility = 'visible';
                    }
                });
                if ($('select[id="ebay_prepare_apply_form_field_strikeprice"]').val() !== 'true' && document.getElementById('tr_strikeprice') !== null) {
                    document.getElementById('tr_strikeprice').style.visibility = 'hidden';
                }
            });
        })(jqml);
        /*]]>*/</script>
    <?php
    if ($sListingType !== null) {
        if (in_array($sListingType, array('StoresFixedPrice', 'FixedPriceItem'))) {
            ?>
            <?php
        } else {//chinese
            ?>
            <tr>
                <?php
                $aPrice = $this->getField('startprice');
                $aPrice['type'] = isset($aPrice['optional']['field']['type']) ? $aPrice['optional']['field']['type'] : $aPrice['type'];
                $aPrice['value'] = number_format((float)$aPrice['value'], 2, '.', '');
                $this->includeType($aPrice);
                ?>
            </tr>
            <?php
            $aBuyItNow = $this->getField('buyitnowprice');
            $aBuyItNow['value'] = number_format((float)$aBuyItNow['value'], 2, '.', '');
            ?>
            <tr class="buynow"><?php $this->includeType($aBuyItNow); ?></tr>
            <?php
        }
    }
    ?>
    </tbody>
</table>
