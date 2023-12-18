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
 * (c) 2010 - 2020 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

if (!class_exists('ML', false))
    throw new Exception(); ?>

    <input type="hidden" id="<?php echo $aField['id'] ?>_hidden" value="false" name="<?php echo MLHTTP::gi()->parseFormFieldName($aField['name']); ?>[]">

<?php
if (!empty($aField['values'])) {
    $aValuesChunk = array_chunk($aField['values'], 15, true);
    $i = 0;

    foreach ($aValuesChunk as $aValues) {
        $aField['values'] = $aValues;
        ?>
        <table class="imageBox">
            <tbody>
            <tr>
                <?php
                $aField['type'] = 'image_list';
                $this->includeType($aField);
                ?>
            </tr>
            <tr>
                <?php
                foreach ($aField['values'] as $sOptionKey => $aImage) { ?>
                    <td class="cb">
                        <input type="checkbox" id="<?php echo $aField['id'] ?>_<?php echo $sOptionKey ?>" value="<?php echo $sOptionKey ?>"<?php echo(in_array($sOptionKey, $aField['value']) ? ' checked="checked"' : ''); ?> name="<?php echo MLHTTP::gi()->parseFormFieldName($aField['name']).'['.$i.']';
                        $i++; ?>">
                    </td>
                <?php } ?>
            </tr>
            </tbody>
        </table>
        <?php
    }
}