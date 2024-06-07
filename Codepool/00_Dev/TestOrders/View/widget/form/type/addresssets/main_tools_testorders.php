<?php if (!class_exists('ML', false))
    throw new Exception(); ?>
<table class="datagrid">
    <thead>
    <tr>
        <th>Main</th>
        <th>Billing</th>
        <th>Shipping</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <?php foreach ($aField['addresssets'] as $sAddress) { ?>
            <td>
                <?php $this->includeType($this->getField($sAddress . 'Address'), $aField); ?>
            </td>
        <?php } ?>
    </tr>
    </tbody>
</table>