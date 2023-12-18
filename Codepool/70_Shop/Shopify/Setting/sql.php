<?php
MLSetting::gi()->add('aPredefinedQuerys', array(
    '<dl><dt>Shopify:</dt><dd>Refresh Product Update Date</dd></dl>' => "DELETE FROM `magnalister_config` WHERE mkey in('shopifyProductPage','shopifyProductCollectionCursor', 'shopifyCollectionUpdatedAtMin', 'shopifyUpdatedAtMin');",
    '<dl><dt>Shopify:</dt><dd>Clear Product cache table</dd></dl>' => 'TRUNCATE `magnalister_products`;',
));

MLSetting::gi()->ShopifyProductRequestLimit =  10;