<?php
/*
 * 888888ba                 dP  .88888.                    dP
 * 88    `8b                88 d8'   `88                   88
 * 88aaaa8P' .d8888b. .d888b88 88        .d8888b. .d8888b. 88  .dP  .d8888b.
 * 88   `8b. 88ooood8 88'  `88 88   YP88 88ooood8 88'  `"" 88888"   88'  `88
 * 88     88 88.  ... 88.  .88 Y8.   .88 88.  ... 88.  ... 88  `8b. 88.  .88
 * dP     dP `88888P' `88888P8  `88888'  `88888P' `88888P' dP   `YP `88888P'
 *
 *                          m a g n a l i s t e r
 *                                      boost your Online-Shop
 *
 * -----------------------------------------------------------------------------
 * (c) 2010 - 2022 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

MLSetting::gi()->add('aPredefinedQuerys', array(
    '<dl><dt>Shopify:</dt><dd>Refresh Product Update Date</dd></dl>'            => "DELETE FROM `magnalister_config` WHERE mkey in(
                                               'shopifyProductCollectionCursor',
                                               'shopifyCollectionUpdatedAtMin',
                                               'shopifyProductNextPage',
                                               'shopifyProductPage',
                                               'shopifyUpdatedAtMin',
                                               'updateShopifyProductPage',
                                               'updateShopifyUpdatedAtMin',
                                              'updateShopifyProductNextPage');",
    '<dl><dt>Shopify:</dt><dd>Show Product Vendor</dd></dl>'                    => 'SELECT * FROM `magnalister_shopify_product_vendor`;',
    '<dl><dt>Shopify:</dt><dd>Show Collections and Products Relation</dd></dl>' => 'SELECT * FROM `magnalister_shopify_product_collection_relation`;',
    '<dl><dt>Shopify:</dt><dd>Show Meta Fields and Object Relation</dd></dl>'   => 'SELECT * FROM `magnalister_shopify_object_metafield_relation`;',
    '<dl><dt>Shopify:</dt><dd>Show Collections</dd></dl>'                       => 'SELECT * FROM `magnalister_shopify_collection`;',
    '<dl><dt>Shopify:</dt><dd>Show Meta Fields</dd></dl>'                       => 'SELECT * FROM `magnalister_shopify_metafield`;',
    //'<dl><dt>Shopify:</dt><dd>Clear Product cache table</dd></dl>'   => 'TRUNCATE `magnalister_products`;',
));

MLSetting::gi()->ShopifyProductRequestLimit =  10;