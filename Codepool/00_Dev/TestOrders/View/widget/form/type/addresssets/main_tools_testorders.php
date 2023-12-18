<?php class_exists('ML', false) or die() ?>
<table class="datagrid">
    <thead><tr><th>Main</th><th>Billing</th><th>Shipping</th></tr></thead>
    <tbody>
        <tr>
            <?php foreach ($aField['addresssets'] as $sAddress) { ?>
                <td>
                    <?php $this->includeType($this->getField($sAddress.'Address'));?>
                </td>
            <?php } ?>
        </tr>
    </tbody>
</table>