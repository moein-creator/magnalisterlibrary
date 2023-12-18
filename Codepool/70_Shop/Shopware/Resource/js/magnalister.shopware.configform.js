(function($) {
    jqml(document).ready( function() {
        var $vatCustomerGroupRow = jqml('select[name="ml[field][orderimport.vatcustomergroup]"]').closest('tr');
        var $customerGroupField = jqml('select[name="ml[field][customergroup]"]');

        // init to show or hide field
        if (jqml('input[name="ml[action][expertaction]"]').val() === 'expert') {
            // check if an expert field exists (may there are no)
            if (jqml('tr.mlexpert').length > 0) {
                $vatCustomerGroupRow.addClass('mlexpert');
            }
            hideOrShowSelectAndParent($vatCustomerGroupRow, true);
        } else {
            hideOrShowSelectAndParent($vatCustomerGroupRow, $customerGroupField.val());
        }

        $customerGroupField.on('change', function() {
            $vatCustomerGroupRow.addClass('magnatext');
            if (jqml('input[name="ml[action][expertaction]"]').val() !== 'expert') {
                hideOrShowSelectAndParent($vatCustomerGroupRow, jqml(this).val());
            }
        });

        function hideOrShowSelectAndParent($field, bShowField) {
            if (bShowField === '-' || bShowField === true) {
                $field.show();
            } else {
                $field.hide();
            }
        }
    })
})(jqml);
