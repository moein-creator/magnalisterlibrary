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

MLI18n::gi()->{'dawanda_config_account_title'} = 'Login Details';
MLI18n::gi()->{'dawanda_config_account_prepare'} = 'Item Preparation';
MLI18n::gi()->{'dawanda_config_account_price'} = 'Price Calculation';
MLI18n::gi()->{'dawanda_config_account_sync'} = 'Synchronization';
MLI18n::gi()->{'dawanda_config_account_orderimport'} = 'Order Import';
MLI18n::gi()->{'dawanda_config_account__legend__account'} = 'Login Details';
MLI18n::gi()->{'dawanda_config_account__legend__tabident'} = 'Tab';
MLI18n::gi()->{'dawanda_config_account__field__tabident__label'} = '{#i18n:ML_LABEL_TAB_IDENT#}';
MLI18n::gi()->{'dawanda_config_account__field__tabident__hint'} = '';
MLI18n::gi()->{'dawanda_config_account__field__tabident__help'} = '{#i18n:ML_TEXT_TAB_IDENT#}';
MLI18n::gi()->{'dawanda_config_account__field__mpusername__label'} = 'Username';
MLI18n::gi()->{'dawanda_config_account__field__mpusername__hint'} = '';
MLI18n::gi()->{'dawanda_config_account__field__mppassword__label'} = 'Password';
MLI18n::gi()->{'dawanda_config_account__field__mppassword__hint'} = '';
MLI18n::gi()->{'dawanda_config_account__field__apikey__label'} = 'API key';
MLI18n::gi()->{'dawanda_config_account__field__apikey__hint'} = '';
MLI18n::gi()->{'dawanda_config_prepare__legend__prepare'} = 'Prepare Items';
MLI18n::gi()->{'dawanda_config_prepare__legend__upload'} = 'Upload Items: Presets';
MLI18n::gi()->{'dawanda_config_prepare__field__prepare.status__label'} = 'Status Filter';
MLI18n::gi()->{'dawanda_config_prepare__field__prepare.status__valuehint'} = 'only take prepared articles';
MLI18n::gi()->{'dawanda_config_prepare__field__producttype__label'} = 'Product type';
MLI18n::gi()->{'dawanda_config_prepare__field__producttype__hint'} = '';
MLI18n::gi()->{'dawanda_config_prepare__field__returnpolicy__label'} = 'Return Policy';
MLI18n::gi()->{'dawanda_config_prepare__field__returnpolicy__hint'} = '';
MLI18n::gi()->{'dawanda_config_prepare__field__checkin.status__label'} = 'Status Filter';
MLI18n::gi()->{'dawanda_config_prepare__field__checkin.status__valuehint'} = 'Only take active articles';
MLI18n::gi()->{'dawanda_config_prepare__field__langs__label'} = 'Item Description';
MLI18n::gi()->{'dawanda_config_prepare__field__langs__hint'} = '';
MLI18n::gi()->{'dawanda_config_prepare__field__langs__matching__titlesrc'} = 'DaWanda language';
MLI18n::gi()->{'dawanda_config_prepare__field__langs__matching__titledst'} = 'shop language';
MLI18n::gi()->{'dawanda_config_prepare__field__quantity__label'} = 'Inventory Item Count';
MLI18n::gi()->{'dawanda_config_prepare__field__quantity__hint'} = '';
MLI18n::gi()->{'dawanda_config_prepare__field__quantity__help'} = 'Please enter how much of the inventory should be available on the marketplace.<br/>
                        <br/>
You can change the individual item count directly under \'Upload\'. In this case it is recommended that you turn off automatic<br/>
synchronization under \'Synchronization of Inventory\' > \'Stock Sync to Marketplace\'.<br/>
                        <br/>
To avoid overselling, you can activate \'Transfer shop inventory minus value from the right field\'.
                        <br/>
<strong>Example:</strong> Setting the value at 2 gives &#8594; Shop inventory: 10 &#8594; Amazon inventory: 8<br/>
                        <br/>
                        <strong>Please note:</strong>If you want to set an inventory count for an item in the Marketplace to \'0\', which is already set as Inactive in the Shop, independent of the actual inventory count, please proceed as follows:<br/>
                        <ul>
                        <li>\'Synchronize Inventory"> Set "Edit Shop Inventory" to "Automatic Synchronization with CronJob"</li>
                        <li>"Global Configuration" > "Product Status" > Activate setting "If product status is inactive, treat inventory count as 0"</li>
                        </ul>';
MLI18n::gi()->{'dawanda_config_prepare__field__checkin.leadtimetoship__label'} = 'Shipping';
MLI18n::gi()->{'dawanda_config_prepare__field__checkin.leadtimetoship__hint'} = '';
MLI18n::gi()->{'dawanda_config_prepare__field__checkin.manufacturerfallback__label'} = 'Alternative Manufacturer';
MLI18n::gi()->{'dawanda_config_prepare__field__checkin.manufacturerfallback__hint'} = '';
MLI18n::gi()->{'dawanda_config_prepare__field__checkin.manufacturerfallback__help'} = 'If a product has consigned a manufacturer, the here specified manufacturer will be used.';
MLI18n::gi()->{'dawanda_config_prepare__field__imagesize__label'} = '{#i18n:form_config_orderimport_imagesize_lable#}';
MLI18n::gi()->{'dawanda_config_prepare__field__imagesize__help'} = '{#i18n:form_config_orderimport_imagesize_help#}';
MLI18n::gi()->{'dawanda_config_prepare__field__imagesize__hint'} = '{#i18n:form_config_orderimport_imagesize_hint#}';
MLI18n::gi()->{'dawanda_config_price__legend__price'} = 'Price Calculation';
MLI18n::gi()->{'dawanda_config_price__field__price__label'} = 'Price';
MLI18n::gi()->{'dawanda_config_price__field__price__hint'} = '';
MLI18n::gi()->{'dawanda_config_price__field__price__help'} = 'Please enter a price markup or markdown, either in percentage or fixed amount. Use a minus sign (-) before the amount to denote markdown.';
MLI18n::gi()->{'dawanda_config_price__field__price.addkind__label'} = '';
MLI18n::gi()->{'dawanda_config_price__field__price.addkind__hint'} = '';
MLI18n::gi()->{'dawanda_config_price__field__price.factor__label'} = '';
MLI18n::gi()->{'dawanda_config_price__field__price.factor__hint'} = '';
MLI18n::gi()->{'dawanda_config_price__field__price.signal__label'} = 'Decimal Amount';
MLI18n::gi()->{'dawanda_config_price__field__price.signal__hint'} = 'Decimal Amount';
MLI18n::gi()->{'dawanda_config_price__field__price.signal__help'} = 'This textfield shows the decimal value that will appear in the item price on DaWanda.<br/><br/>
                <strong>Example:</strong> <br />
Value in textfeld: 99 <br />
                Original price: 5.58 <br />
                Final amount: 5.99 <br /><br />
This function is useful when marking the price up or down***. <br/>
Leave this field empty if you do not wish to set any decimal amount. <br/>
The format requires a maximum of 2 numbers.';
MLI18n::gi()->{'dawanda_config_price__field__priceoptions__label'} = 'price options';
MLI18n::gi()->{'dawanda_config_price__field__priceoptions__help'} = '{#i18n:configform_price_field_priceoptions_help#}';
MLI18n::gi()->{'dawanda_config_price__field__priceoptions__hint'} = '';
MLI18n::gi()->{'dawanda_config_price__field__price.group__label'} = '';
MLI18n::gi()->{'dawanda_config_price__field__price.group__hint'} = '';
MLI18n::gi()->{'dawanda_config_price__field__price.usespecialoffer__label'} = 'Use special offer prices';
MLI18n::gi()->{'dawanda_config_price__field__price.usespecialoffer__hint'} = '';
MLI18n::gi()->{'dawanda_config_price__field__exchangerate_update__label'} = 'Exchange Rate';
MLI18n::gi()->{'dawanda_config_price__field__exchangerate_update__hint'} = 'Automatically update exchange rate';
MLI18n::gi()->{'dawanda_config_price__field__exchangerate_update__help'} = '{#i18n:form_config_orderimport_exchangerate_update_help#}';
MLI18n::gi()->{'dawanda_config_price__field__exchangerate_update__alert'} = '{#i18n:form_config_orderimport_exchangerate_update_alert#}';
MLI18n::gi()->{'dawanda_config_sync__legend__sync'} = 'Inventory Synchronization';
MLI18n::gi()->{'dawanda_config_sync__field__stocksync.tomarketplace__label'} = 'inventory variation shop';
MLI18n::gi()->{'dawanda_config_sync__field__stocksync.tomarketplace__hint'} = '';
MLI18n::gi()->{'dawanda_config_sync__field__stocksync.tomarketplace__help'} = '<dl>
            <dt>Automatic Synchronization via CronJob (recommended)</dt>
                    <dd>Current DaWanda stock will be synchronized with shop stock every 4 hours, beginning at 0.00am (with ***, depending on configuration).<br>Values will be transferred from the database, including the changes that occur through an ERP or similar.<br><br>
Manual comparison can be activated by clicking the corresponding button in the magnalister header (left of the shopping cart).<br><br>
Additionally, you can activate the stock comparison through CronJon (flat tariff*** - maximum every 4 hours) with the link:<br>
            <i>{#setting:sSyncInventoryUrl#}</i><br>

Some CronJob requests may be blocked, if they are made through customers not on the flat tariff*** or if the request is made more than once every 4 hours.
</dd>
                            <dt>Editing orders / items will synchronize DaWanda and shop stock. </dt>
                                    <dd>If the Shop inventory is changed because of an order or editing an item, the current shop inventory will then be transferred to DaWanda.<br>
Changes made only to the database, for example, through an ERP, <b>will not</b> be recorded and transferred!</dd>
                            <dt>Editing orders / items changes the DaWanda inventory.</dt>
                                    <dd>For example, if a Shop item is purchased twice, the DaWanda inventory will be reduced by 2.<br />
  If the item amount is changed in the shop under \'Edit Item\', the difference from the previous amount will be added or subtracted.<br>
';
MLI18n::gi()->{'dawanda_config_sync__field__stocksync.frommarketplace__label'} = 'inventory change DaWanda';
MLI18n::gi()->{'dawanda_config_sync__field__stocksync.frommarketplace__hint'} = '';
MLI18n::gi()->{'dawanda_config_sync__field__stocksync.frommarketplace__help'} = 'If, for example, an item is purchased 3 times on DaWanda, the Shop inventory will be reduced by 3.<br /><br />
<strong>Important:</strong>This function will only work if you have Order Imports activated!
';
MLI18n::gi()->{'dawanda_config_sync__field__inventorysync.price__label'} = 'Item Price';
MLI18n::gi()->{'dawanda_config_sync__field__inventorysync.price__hint'} = '';
MLI18n::gi()->{'dawanda_config_sync__field__inventorysync.price__help'} = '<p> Current DaWanda price  will be synchronized with shop stock every 4 hours, beginning at 0.00am (with ***, depending on configuration)<br>
Values will be transferred from the database, including the changes that occur through an ERP or similar.<br><br>
<b>Hint:</b> The settings in \'Configuration\', \'price calculation\' will be taken into account.
';
MLI18n::gi()->{'dawanda_config_orderimport__legend__importactive'} = 'Order Import';
MLI18n::gi()->{'dawanda_config_orderimport__legend__mwst'} = 'VAT';
MLI18n::gi()->{'dawanda_config_orderimport__legend__orderstatus'} = 'Synchronization of the order status from shop to DaWanda';
MLI18n::gi()->{'dawanda_config_orderimport__field__orderstatus.shipped__label'} = 'Confirm shipping with';
MLI18n::gi()->{'dawanda_config_orderimport__field__orderstatus.shipped__hint'} = '';
MLI18n::gi()->{'dawanda_config_orderimport__field__orderstatus.shipped__help'} = 'Select the shop status that will automatically set the daWanda status to "confirm shipment".';
MLI18n::gi()->{'dawanda_config_orderimport__field__orderimport.shop__label'} = '{#i18n:form_config_orderimport_shop_lable#}';
MLI18n::gi()->{'dawanda_config_orderimport__field__orderimport.shop__hint'} = '';
MLI18n::gi()->{'dawanda_config_orderimport__field__orderimport.shop__help'} = '{#i18n:form_config_orderimport_shop_help#}';
MLI18n::gi()->{'dawanda_config_orderimport__field__mwst.fallback__label'} = 'VAT on Non-Shop*** Items';
MLI18n::gi()->{'dawanda_config_orderimport__field__mwst.fallback__hint'} = 'The tax rate to apply to non-Shop items on order imports, in %.';
MLI18n::gi()->{'dawanda_config_orderimport__field__mwst.fallback__help'} = 'If an item is not entered in the web-shop, magnalister uses the VAT from here since marketplaces give no details to VAT within the order import.<br />
<br />
Further explanation:<br />
Basically, magnalister calculates the VAT the same way the shop-system does itself.<br />
VAT per country can only be considered if the article can be found in the web-shop with his number range (SKU).<br />
magnalister uses the configured web-shop-VAT-classes.
';
MLI18n::gi()->{'dawanda_config_orderimport__field__importactive__label'} = 'Activate Import';
MLI18n::gi()->{'dawanda_config_orderimport__field__importactive__hint'} = '';
MLI18n::gi()->{'dawanda_config_orderimport__field__importactive__help'} = 'Import orders from the Marketplace? <br/><br/>When activated, orders will be automatically imported every hour.<br><br>
Manual import can be activated by clicking the corresponding button in the magnalister header (left of the shopping cart).<br><br>Additionally, you can activate the stock comparison through CronJon (flat tariff*** - maximum every 4 hours) with the link:<br>
            <i>{#setting:sImportOrdersUrl#}</i><br>
Some CronJob requests may be blocked, if they are made through customers not on the flat tariff*** or if the request is made more than once every 4 hours. 
';
MLI18n::gi()->{'dawanda_config_orderimport__field__import__label'} = '';
MLI18n::gi()->{'dawanda_config_orderimport__field__import__hint'} = '';
MLI18n::gi()->{'dawanda_config_orderimport__field__preimport.start__label'} = 'first time from date';
MLI18n::gi()->{'dawanda_config_orderimport__field__preimport.start__hint'} = 'Start Date';
MLI18n::gi()->{'dawanda_config_orderimport__field__preimport.start__help'} = 'The date from which orders will start being imported. Please note that it is not possible to set this too far in the past, as the data only remains available on DaWanda for a few weeks.***';
MLI18n::gi()->{'dawanda_config_orderimport__field__customergroup__label'} = 'Customer Group';
MLI18n::gi()->{'dawanda_config_orderimport__field__customergroup__hint'} = '';
MLI18n::gi()->{'dawanda_config_orderimport__field__customergroup__help'} = 'The customer group that customers from new orders should be sorted into.';
MLI18n::gi()->{'dawanda_config_orderimport__field__orderstatus.open__label'} = 'Order Status in Shop';
MLI18n::gi()->{'dawanda_config_orderimport__field__orderstatus.open__hint'} = '';
MLI18n::gi()->{'dawanda_config_orderimport__field__orderstatus.open__help'} = 'The status that should be transferred automatically to the Shop after a new order on DaWanda. <br />
If you are using a connected dunning process***, it is recommended to set the Order Status to ‘Paid’ (‘Configuration’ > ‘Order Status’).
';
MLI18n::gi()->{'dawanda_config_orderimport__field__order.importonlypaid__label'} = 'Only import paid orders';
MLI18n::gi()->{'dawanda_config_orderimport__field__order.importonlypaid__hint'} = '';
MLI18n::gi()->{'dawanda_config_orderimport__field__customersync__label'} = 'recurrent customers';
MLI18n::gi()->{'dawanda_config_orderimport__field__customersync__hint'} = '';
MLI18n::gi()->{'dawanda_config_orderimport__field__customersync__help'} = 'DaWanda generates a buyer-email-address (forwarding) for each order that can be used for the communication per order.<br />
Please select from the drop-down if that email-address and other data shall be updated and thereby overwritten for recurrent customers or if a new customer data set shall be gernerated.';
MLI18n::gi()->{'dawanda_config_orderimport__field__customersync__values__1'} = 'Update customer data';
MLI18n::gi()->{'dawanda_config_orderimport__field__customersync__values__0'} = 'Add new Customer';
MLI18n::gi()->{'dawanda_config_orderimport__field__orderimport.shippingmethod__label'} = 'Shipping Service of the Orders';
MLI18n::gi()->{'dawanda_config_orderimport__field__orderimport.shippingmethod__help'} = 'Shipping methods that will be assigned to all DaWanda orders. Standard: "DaWanda"<br><br>
This setting is necessary for the invoice and shipping notice, and for editing orders later in the Shop or via ERP.';
