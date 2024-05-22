<?php
if (!class_exists('ML', false))
    throw new Exception();
$latestReport = MLModule::gi()->getConfig('inventory.import');
?>

<table class="magnaframe">
    <thead>
    <tr>
        <th><?= $this->__('ML_LABEL_NOTE') ?></th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td class="fullWidth">
            <table>
                <tbody>
                <tr>
                    <td><?= $this->__('CROWDFOX_INVENTORY_INFO') ?></td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>