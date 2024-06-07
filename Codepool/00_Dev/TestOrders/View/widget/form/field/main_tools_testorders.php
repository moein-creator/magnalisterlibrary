<?php if (!class_exists('ML', false))
    throw new Exception(); ?>
<tr class="<?php echo isset($sClass) ? $sClass : '' ?>">
    <th>
        <label for="<?php echo $aField['id'] ?>"><?php echo $aField['i18n']['label'] ?></label>
    </th>
    <td class="input">
        <?php $this->includeType($aField); ?>
    </td>
</tr>