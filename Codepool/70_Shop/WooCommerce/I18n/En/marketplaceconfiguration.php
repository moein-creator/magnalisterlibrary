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
 * (c) 2010 - 2021 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

/* Autogenerated file. Do not change! */

MLI18n::gi()->{'WooCommerce_Configuration_ShippingMethod_NotAvailable_Info'} = '<p>{#setting:currentMarketplaceName#} does not assign any shipping method to imported orders.</p>
<p>Please choose here the available Web Shop shipping methods. The contents of the drop-down menu can be assigned in WooCommerce > Settings > Shipping > Zone > Shipping methods.</p>
<p>This setting is important for bills and shipping notes, the subsequent processing of the order inside the shop, and for some ERPs.</p>';

MLI18n::gi()->{'WooCommerce_Configuration_ShippingMethod_Available_Info'} = '<p>Shipping service that will apply to all orders imported from {#setting:currentMarketplaceName#}. Standard: “Automatic Allocation”</p>
<p>
If you choose “Automatic Allocation”, magnalister will accept the shipping method chosen by the buyer on {#setting:currentMarketplaceName#}.</p>
<p>
Additional payment methods can be added to the list via WooCommerce > Settings > Shipping > Zone > Shipping methods , then activated here.</p>
<p>
This setting is necessary for the invoice and shipping notice, and for editing orders later in the Shop or via ERP.</p>';

MLI18n::gi()->{'form_config_orderimport_exchangerate_update_help'} = '
<strong>General:</strong>
<p>
If the currency set up in the web shop differs from that of the marketplace, magnalister calculates the article price with the help of an automatic currency conversion.
</p>
<strong>Caution:</strong>
<p>
For this we use the exchange rate generated by the external currency converter "alphavantage". Important: We assume no liability for the currency conversion of external services.
</p>
<p>
The following functions trigger an update of the currency exchange rate:
<ul>
<li>Order Import</li>
<li>Item Preparation</li>
<li>Item Upload</li>
<li>Stock/Price Synchronization</li>
</ul>
</p>
<p>
Furthermore, the exchange rate is automatically updated every 24 hours. In this field you can see the last updated exchange rate and when it was last updated.
</p>';
MLI18n::gi()->{'form_config_orderimport_exchangerate_update_alert'} = '<strong>Caution:</strong>
<p>
By activating this function, the currency settings in your web-shop will be updated and overwritten with the current "alphavantage" exchange-rate.
<u>As a result, this will affect your foreign currency in the web-shop frontend.</u>
</p>
<p>
The following magnalister functions trigger the exchange-rate update:
<ul>
<li>Order import</li>
<li>Preparation of products</li>
<li>Upload of products</li>
<li>Synchronization of stock and prices</li>
</ul>
</p>';


MLI18n::gi()->{'WooCommerce_Configuration_PaymentMethod_NotAvailable_Info'} = '<p>Payment method that will apply to all orders imported from {#setting:currentMarketplaceName#}. Standard: "{#setting:currentMarketplaceName#}"</p>
<p>
Additional payment methods can be added to the list via WooCommerce > Settings > Payments Methods, then activated here.</p>
<p>
This setting is important for bills and shipping notes, the subsequent processing of the order inside the shop, and for some ERPs.</p>';
MLI18n::gi()->{'WooCommerce_Configuration_PaymentMethod_Available_Info'} = '<p>Payment method that will apply to all orders imported from {#setting:currentMarketplaceName#}. Standard: “Automatic Allocation”</p>
<p>
If you choose “Automatic Allocation”, magnalister will accept the payment method chosen by the buyer on {#setting:currentMarketplaceName#}.</p>
<p>
Additional payment methods can be added to the list via WooCommerce > Settings > Payments, then activated here.</p>
<p>
This setting is necessary for the invoice and shipping notice, and for editing orders later in the Shop or via ERP.</p>';

MLI18n::gi()->set('formfields_config_uploadInvoiceOption_help_webshop', '', true);

MLI18n::gi()->{'woocommerce_config_trackingkey_help_warning'} = '<b>Important for the above-mentioned third-party plugins</b>: magnalister also transmits the name of the shipping company from the third-party plugin.';

MLI18n::gi()->orderimport_trackingkey = array(
    'label' => 'Tracking Key',
    'help'  => '<p>These are the options to submit the tracking key of a marketplace order imported via magnalister to the marketplace/buyer via order status synchronization:
</p>
<ol>
<li>
<p>
<h5>Create “custom field” in WooCommerce and select it in magnalister</h5>
</p><p>
Custom Fields can be created in the WooCommerce administration under "Orders" -> [Order]. Name the Custom Field for example „Tracking Key" in the "Name" column and enter the tracking key of the corresponding order in the "Value" column.
</p><p>
After that, return to this location in the magnalister plugin and select the field created in the order details (following the above example with the name „Tracking Key") from the dropdown list on the right under "Custom Fields from WooCommerce".
</p>
</li>
<li>
<p>
<h5>magnalister adds a “custom field” in the order details</h5>
</p><p>
If you select this option, magnalister will automatically add a Custom Field under "Orders" -> [Order] -> “Custom Fields” during the order import.
</p><p>
You can now enter the corresponding tracking key there.
</p>
</li><li>
<p>
<h5>magnalister accesses tracking key field of third party plugins</h5>
</p><p>
magnalister can access tracking key fields from certain WooCommerce third-party plugins. These include the following plugins:
</p><p>
Germanized Plugin
</p><p>
To transfer the tracking key from the Germanized plugin to the marketplace via magnalister, select "Germanized Plugin: use tracking key from there" in the dropdown list to the right.
</p><p>
When using the Germanized Plugin, enter the tracking key in the order details under "Shipments" -> "Shipment number".
</p><p>
Advanced Shipment Tracking Plugin
</p><p>
To transfer the tracking key from the Advanced Shipment Tracking Plugin to the marketplace via magnalister, select the option "Advanced Shipment Tracking Plugin: Use tracking key from there" in the dropdown list on the right.
</p><p>
When using the Advanced Shipment Tracking Plugin, enter the tracking key in the order details under "Shipment Tracking" -> "Shipment Code".
</p><p>
{#i18n:woocommerce_config_trackingkey_help_warning#}
</p>
</li>
</ol>
',
);
MLI18n::gi()->{'woocommerce_config_trackingkey_option_group_customfields'} = 'Wordpress Custom Fields';
MLI18n::gi()->{'woocommerce_config_trackingkey_option_group_additional_option'} = 'Additional Options';
MLI18n::gi()->{'woocommerce_config_trackingkey_option_orderfreetextfield_option'} = 'magnalister adds a “custom field” in the order details';
MLI18n::gi()->{'woocommerce_config_trackingkey_option_plugin_ast'} = 'Advanced Shipment Tracking Plugin: use tracking key from there';
MLI18n::gi()->{'woocommerce_config_trackingkey_option_plugin_germanized'} = 'Germanized Plugin: use tracking key from there';

MLI18n::gi()->{'marketplace_config_carrier_option_matching_option_plugin'} = 'Match marketplace supported carrier with carriers defined in "{#pluginname#}"-plugin ';
MLI18n::gi()->{'marketplace_config_carrier_matching_title_shop_carrier_plugin'} = 'Shipping carrier defined in "{#pluginname#}"-plugin';
MLI18n::gi()->{'amazon_config_carrier_option_matching_option_carrier_plugin'} = 'Match shipping carriers suggested by Amazon with carriers defined in "{#pluginname#}"-plugin';
MLI18n::gi()->{'amazon_config_carrier_option_matching_option_shipmethod_plugin'} = 'Match shipping method with entries from "{#pluginname#}"-plugin';