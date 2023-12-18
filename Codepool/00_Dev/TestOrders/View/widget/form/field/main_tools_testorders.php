<?php class_exists('ML', false) or die() ?>
<tr class="<?php echo $sClass?>">
    <th>
        <label for="<?php echo $aField['id'] ?>"><?php echo $aField['i18n']['label'] ?></label>
    </th>
    <td class="input">
        <?php $this->includeType($aField);?>
    </td>
</tr>