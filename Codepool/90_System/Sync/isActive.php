<?php

// PHP 8.1+ fix issue with strtolower() on null
if (MLRequest::gi()->data('do') == null) {
    return 0;
}

return count(array_intersect(
    explode(',', strtolower(MLRequest::gi()->data('do'))),
    array(
        'importorders',
        'syncorderstatus',
        'syncinventory',
        'syncproductidentifiers',
        'update',
        'updateorders',
        'uploadinvoices',
        'importcategories',
    )
)) > 0;
