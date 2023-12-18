<?php if (!class_exists('ML', false))
    throw new Exception(); ?>
<table class="imageBox">
    <tbody>
    <tr>
        <?php
        $aField['type'] = 'image_list';
        $this->includeType($aField);
        ?>
    </tr>
    <tr>
        <?php if (array_key_exists('values', $aField) && is_array($aField['values'])): ?>
            <?php foreach ($aField['values'] as $sOptionKey => $aImage) { ?>
                <td class="cb">
                    <input type="radio" id="<?php echo $aField['id'] ?>_<?php echo $sOptionKey ?>" value="<?php echo $sOptionKey ?>"<?php echo($aField['value'] == $sOptionKey || (is_array($aField['value']) && current($aField['value']) == $sOptionKey) ? ' checked="checked"' : ''); ?> name="<?php echo MLHTTP::gi()->parseFormFieldName($aField['name']).(array_key_exists('asarray', $aField) && $aField['asarray'] ? '[]' : ''); ?>">
                </td>
            <?php } ?>
        <?php endif; ?>
    </tr>
    </tbody>
</table>