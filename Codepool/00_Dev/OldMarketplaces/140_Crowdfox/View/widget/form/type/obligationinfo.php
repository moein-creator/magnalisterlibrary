<?php if (!class_exists('ML', false))
    throw new Exception(); ?>
<span style="display:table-cell;">
    <?php echo(isset($aField['value']) ? htmlspecialchars($aField['value'], ENT_COMPAT) : '') ?>
    <?php echo $aField['currency'] ?>
</span>