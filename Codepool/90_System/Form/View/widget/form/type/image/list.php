<?php if (!class_exists('ML', false))
    throw new Exception(); ?>
<?php if (array_key_exists('values', $aField) && is_array($aField['values'])): ?>
    <?php foreach ($aField['values'] as $sOptionKey => $aImage) { ?>
        <td class="image">
            <label for="<?php echo $aField['id'] ?>_<?php echo $sOptionKey ?>">
                <?php if (is_array($aImage)) { ?>
                    <img height="<?php echo $aImage['height'] ?>" width="<?php echo $aImage['width'] ?>" alt="<?php echo $aImage['alt'] ?>" src="<?php echo $aImage['url'] ?>"/>
                <?php } else { ?>
                    <div style="padding:.5em"><?php echo $aImage; ?></div>
                <?php } ?>
            </label>
        </td>
    <?php } ?>
<?php endif; ?>
