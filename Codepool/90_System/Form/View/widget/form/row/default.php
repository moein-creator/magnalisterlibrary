<?php
if (!class_exists('ML', false))
    throw new Exception();
foreach ($aFields as $iField => $aField) {
    $this->includeView('widget_form_field', array('aField' => $aField, 'sClass' => (int)$iField % 2 == 0 ? 'odd' : 'even'));
}
?>
