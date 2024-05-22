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
 * (c) 2010 - 2024 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

MLI18n::gi()->{'ebay_config_orderimport__field__updateablepaymentstatus__label'} = 'Update Payment Status When';
MLI18n::gi()->{'ebay_config_orderimport__field__updateablepaymentstatus__help'} = 'Order statuses that can be triggered by eBay payments. 
If the order has a different status, this cannot be changed by an eBay payment.<br /><br />
If you don\'t wish any status changes based on eBay payment, please deactivate the checkbox.<br /><br />
<b>Please note:</b>The status of summarised orders will only be changed when paid in full.';
MLI18n::gi()->{'ebay_config_orderimport__field__paidstatus__label'} = 'Order-/Paid Status for Paid eBay Orders';
MLI18n::gi()->{'ebay_config_orderimport__field__paidstatus__help'} = 'Sometimes eBay orders are paid by the customer with delay.
<br><br>
In order to separate unpaid orders from paid orders, you can choose your own online store order-/paid status for paid eBay orders.
<br><br>
If orders imported from eBay have not yet been paid, the order status you defined above under "Order Import" > "Order Status in Shop" will be applied.
<br><br>
If above you have activated  "Import only „Paid“-marked orders", the "Order status in the Shop" from above will also be used. The feature is then grayed out.
';
MLI18n::gi()->{'ebay_config_orderimport__field__orderstatus.open__help'} = 'In your online store this info determines the order status that is automatically assigned to every new order that arrives from eBay.
<br><br>
Please note that both paid and unpaid eBay orders are imported.
<br><br>
However, when using the function "Import only „Paid“-marked orders", you can choose to import only paid eBay orders into your online store.
<br><br>
For paid eBay orders, you can set your own order status a little bit further down, under "Order Status Synchronization" > "Order-/Paid Status for Paid eBay Orders".
<br><br>
<b>Note for your dunning process:</b>
<br><br>
If you are using an inventory management and/or invoicing tool connected to your online store, it is recommended to adjust the order status so that your inventory  management/invoicing tool can process the order according to your business process.';
MLI18n::gi()->{'ebay_config_orderimport__field__orderstatus.paid__label'} = 'Order Status';
MLI18n::gi()->{'ebay_config_orderimport__field__orderstatus.paid__help'} = '';
MLI18n::gi()->{'ebay_config_orderimport__field__paymentstatus.paid__label'} = 'Payment Status';
MLI18n::gi()->{'ebay_config_orderimport__field__paymentstatus.paid__help'} = '';
MLI18n::gi()->{'ebay_config_orderimport__field__updateable.paymentstatus__label'} = '';
MLI18n::gi()->{'ebay_config_orderimport__field__updateable.paymentstatus__help'} = '';
MLI18n::gi()->{'ebay_config_orderimport__field__update.paymentstatus__label'} = 'Status modification active';
MLI18n::gi()->{'ebay_config_orderimport__field__orderimport.paymentstatus__label'} = 'Payment Status in Shop';
MLI18n::gi()->{'ebay_config_orderimport__field__orderimport.paymentstatus__hint'} = 'Please select which shop system payment status should be set in the order details during the magnalister order import.';
MLI18n::gi()->{'ebay_config_orderimport__field__customergroup__help'} = '{#i18n:global_config_orderimport_field_customergroup_help#}';
MLI18n::gi()->{'ebay_prepare_apply_form__field__description__hint'} = 'List of available place holders for product description:<dl><dt>#TITLE#</dt><dd>Product name (Titel)</dd><dt>#ARTNR#</dt><dd>Article number from the shop</dd><dt>#PID#</dt><dd>Products ID</dd><!--<dt>#PRICE#</dt><dd>Price</dd><dt>#VPE#</dt><dd>Price per packaging unit</dd>--><dt>#SHORTDESCRIPTION#</dt><dd>Short Description from Shop</dd><dt>#DESCRIPTION#</dt><dd>Description from Shop</dd><dt>#WEIGHT#</dt><dd>Products weight</dd><dt>#PICTURE1#</dt><dd>First Product-Image</dd><dt>#PICTURE2# etc.</dt><dd>Second Product-Image; with #PICTURE3#, #PICTURE4# etc. More Images can be sent, as many as available in the shop.</dd><dt>#TAGS#</dt><dd>TAGS</dd></dl></dl>';

MLI18n::gi()->set('ebay_config_producttemplate__field__template.content__hint', MLI18n::gi()->{'ebay_prepare_apply_form__field__description__hint'}, true);
MLI18n::gi()->set('ebay_prepare_apply_form_field_description_hint_metafield', '<dt>#Namespace and key of the meta field#</dt><dd>e.g.</dd>', true);
MLI18n::gi()->{'orderstatus_carrier_default_send_order_carrier'} = 'Pass order shipping carrier';

MLI18n::gi()->{'ebay_config_producttemplate__field__template.name__help'} = '<dl>
    <dt>Name of the product on eBay</dt>
    <dd>Decide how to name the product on eBay.
        The placeholder <b>#TITLE#</b> will be replaced by the shop\'s product name.</dd></dl>';
MLI18n::gi()->{'ebay_config_producttemplate__field__template.name__hint'} = 'Placeholder: #TITLE# - Product name';
