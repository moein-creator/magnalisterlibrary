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

MLI18n::gi()->{'bepado_config_account_title'} = 'Login Details';
MLI18n::gi()->{'bepado_config_account_prepare'} = 'Item Preparation';
MLI18n::gi()->{'bepado_config_account_price'} = 'Price Calculation';
MLI18n::gi()->{'bepado_config_account_sync'} = 'Synchronization';
MLI18n::gi()->{'bepado_config_account_orderimport'} = 'Order Import';
MLI18n::gi()->{'bepado_config_account_emailtemplate'} = '{#i18n:configform_emailtemplate_legend#}';
MLI18n::gi()->{'bepado_config_account_emailtemplate_sender'} = 'Example Shop';
MLI18n::gi()->{'bepado_config_account_emailtemplate_sender_email'} = 'beispiel@onlineshop.de';
MLI18n::gi()->{'bepado_config_account_emailtemplate_subject'} = 'Your Order from #SHOPURL#';
MLI18n::gi()->{'bepado_config_account_emailtemplate_content'} = '  <style>
        <!--body { font: 12px sans-serif; }
        table.ordersummary { width: 100%; border: 1px solid #e8e8e8; }
        table.ordersummary td { padding: 3px 5px; }
        table.ordersummary thead td { background: #cfcfcf; color: #000; font-weight: bold; text-align: center; }
        table.ordersummary thead td.name { text-align: left; }
        table.ordersummary tbody tr.even td { background: #e8e8e8; color: #000; }
        table.ordersummary tbody tr.odd td { background: #f8f8f8; color: #000; }
        table.ordersummary td.price, table.ordersummary td.fprice { text-align: right; white-space: nowrap; }
        table.ordersummary tbody td.qty { text-align: center; }-->
    </style>
    <p>Hello #FIRSTNAME# #LASTNAME#,</p>
    <p>thank you for your order! You ordered via #MARKETPLACE# the following in our shop:</p>
    #ORDERSUMMARY#
    <p>plus possible shipping costs.</p>
    <p>Find more interesting offers in our shop  <strong>#SHOPURL#</strong>.</p>
    <p>&nbsp;</p>
    <p>Best Regards,</p>
    <p>Your Online-Shop-Team</p>';
MLI18n::gi()->{'bepado_config_account__legend__account'} = 'Login Details';
MLI18n::gi()->{'bepado_config_account__legend__tabident'} = '';
MLI18n::gi()->{'bepado_config_account__field__tabident__label'} = '{#i18n:ML_LABEL_TAB_IDENT#}';
MLI18n::gi()->{'bepado_config_account__field__tabident__help'} = '{#i18n:ML_TEXT_TAB_IDENT#}';
MLI18n::gi()->{'bepado_config_account__field__apikey__label'} = 'API key';
MLI18n::gi()->{'bepado_config_account__field__apikey__hint'} = '';
MLI18n::gi()->{'bepado_config_account__field__mpusername__label'} = 'Username';
MLI18n::gi()->{'bepado_config_account__field__mppassword__label'} = 'Password';
MLI18n::gi()->{'bepado_config_account__field__ftpusername__label'} = 'FTP User name';
MLI18n::gi()->{'bepado_config_account__field__ftppassword__label'} = 'FTP Password';
MLI18n::gi()->{'bepado_config_account__field__shopid__label'} = 'Shop ID';
MLI18n::gi()->{'bepado_config_prepare__legend__prepare'} = 'Prepare Items';
MLI18n::gi()->{'bepado_config_prepare__legend__shipping'} = 'Shipping';
MLI18n::gi()->{'bepado_config_prepare__legend__upload'} = 'Upload Items: Presets';
MLI18n::gi()->{'bepado_config_prepare__field__prepare.status__label'} = 'Status Filter';
MLI18n::gi()->{'bepado_config_prepare__field__prepare.status__valuehint'} = 'Only transfer active items';
MLI18n::gi()->{'bepado_config_prepare__field__checkin.status__label'} = 'Status Filter';
MLI18n::gi()->{'bepado_config_prepare__field__checkin.status__valuehint'} = 'Only transfer active items';
MLI18n::gi()->{'bepado_config_prepare__field__lang__label'} = 'Item Description';
MLI18n::gi()->{'bepado_config_prepare__field__quantity__label'} = 'Inventory Item Count';
MLI18n::gi()->{'bepado_config_prepare__field__quantity__hint'} = '';
MLI18n::gi()->{'bepado_config_prepare__field__quantity__help'} = 'Please enter how much of the inventory should be available on the marketplace.<br/>
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
MLI18n::gi()->{'bepado_config_prepare__field__shippingtime__label'} = 'Delivery time in days';
MLI18n::gi()->{'bepado_config_prepare__field__shippingcontainer__label'} = 'Shipping costs';
MLI18n::gi()->{'bepado_config_prepare__field__shippingcountry__label'} = 'Shipping country';
MLI18n::gi()->{'bepado_config_prepare__field__shippingservice__label'} = 'Carrier';
MLI18n::gi()->{'bepado_config_prepare__field__shippingcost__label'} = 'Shipping costs';
MLI18n::gi()->{'bepado_config_price__legend__price'} = 'Price Calculation';
MLI18n::gi()->{'bepado_config_price__field__price__label'} = 'Price (B2C)';
MLI18n::gi()->{'bepado_config_price__field__price__hint'} = '';
MLI18n::gi()->{'bepado_config_price__field__price__help'} = 'Please enter a price markup or markdown, either in percentage or fixed amount. Use a minus sign (-) before the amount to denote markdown.';
MLI18n::gi()->{'bepado_config_price__field__b2c.price.addkind__label'} = '';
MLI18n::gi()->{'bepado_config_price__field__b2c.price.addkind__hint'} = '';
MLI18n::gi()->{'bepado_config_price__field__b2c.price.factor__label'} = '';
MLI18n::gi()->{'bepado_config_price__field__b2c.price.factor__hint'} = '';
MLI18n::gi()->{'bepado_config_price__field__b2c.price.signal__label'} = 'Decimal Amount';
MLI18n::gi()->{'bepado_config_price__field__b2c.price.signal__hint'} = 'Decimal Amount';
MLI18n::gi()->{'bepado_config_price__field__b2c.price.signal__help'} = 'This textfield will be taken as position after decimal point for transmitted data to bepado.<br><br>
                <strong>Example:</strong><br>
value in textfield: 99<br>
                price origin: 5.58<br>
                final result: 5.99<br><br>
This function is usefull for percentage markups and markdowns.<br>
Leave this field open if you don’t want to transmit a position after decimal point.<br> The input format is an integral number with a maximum of 2 digits.
';
MLI18n::gi()->{'bepado_config_price__field__priceoptions__label'} = 'Calculate price (B2C) from';
MLI18n::gi()->{'bepado_config_price__field__priceoptions__help'} = '{#i18n:configform_price_field_priceoptions_help#}';
MLI18n::gi()->{'bepado_config_price__field__priceoptions__hint'} = '';
MLI18n::gi()->{'bepado_config_price__field__b2c.price.group__label'} = '';
MLI18n::gi()->{'bepado_config_price__field__b2c.price.group__hint'} = '';
MLI18n::gi()->{'bepado_config_price__field__b2c.price.usespecialoffer__label'} = 'Use special offer prices';
MLI18n::gi()->{'bepado_config_price__field__b2c.price.usespecialoffer__hint'} = '';
MLI18n::gi()->{'bepado_config_price__field__b2b.price__label'} = 'Price (B2B)';
MLI18n::gi()->{'bepado_config_price__field__b2b.price__hint'} = '';
MLI18n::gi()->{'bepado_config_price__field__b2b.price__help'} = 'A special feature of bepado is to let third-party-seller<br /> offer your products (B2B trading partner).<br />
                <br />
Here you define the purchase price for trading partner (without tax).';
MLI18n::gi()->{'bepado_config_price__field__b2b.price.addkind__label'} = '';
MLI18n::gi()->{'bepado_config_price__field__b2b.price.addkind__hint'} = '';
MLI18n::gi()->{'bepado_config_price__field__b2b.price.active__label'} = 'Purchase price (B2B) active';
MLI18n::gi()->{'bepado_config_price__field__b2b.price.active__hint'} = '';
MLI18n::gi()->{'bepado_config_price__field__b2b.price.factor__label'} = '';
MLI18n::gi()->{'bepado_config_price__field__b2b.price.factor__hint'} = '';
MLI18n::gi()->{'bepado_config_price__field__b2b.price.signal__label'} = 'Decimal Amount';
MLI18n::gi()->{'bepado_config_price__field__b2b.price.signal__hint'} = 'Decimal Amount';
MLI18n::gi()->{'bepado_config_price__field__b2b.price.signal__help'} = 'This textfield will be taken as position after decimal point for transmitted data to bepado.<br><br>
                <strong>Example:</strong><br>
value in textfield: 99<br>
                price origin: 5.58<br>
                final result: 5.99<br><br>
This function is usefull for percentage markups and markdowns.<br>
Leave this field open if you don’t want to transmit a position after decimal point.<br> The input format is an integral number with a maximum of 2 digits.
';
MLI18n::gi()->{'bepado_config_price__field__b2b.priceoptions__label'} = 'Calulate price (B2C) from';
MLI18n::gi()->{'bepado_config_price__field__b2b.priceoptions__help'} = '{#i18n:configform_price_field_priceoptions_help#}';
MLI18n::gi()->{'bepado_config_price__field__b2b.priceoptions__hint'} = '';
MLI18n::gi()->{'bepado_config_price__field__b2b.price.group__label'} = '';
MLI18n::gi()->{'bepado_config_price__field__b2b.price.group__hint'} = '';
MLI18n::gi()->{'bepado_config_price__field__b2b.price.usespecialoffer__label'} = 'Use special offer prices';
MLI18n::gi()->{'bepado_config_price__field__b2b.price.usespecialoffer__hint'} = '';
MLI18n::gi()->{'bepado_config_price__field__exchangerate_update__label'} = 'Exchange Rate';
MLI18n::gi()->{'bepado_config_price__field__exchangerate_update__hint'} = 'Automatically update exchange rate';
MLI18n::gi()->{'bepado_config_price__field__exchangerate_update__help'} = '{#i18n:form_config_orderimport_exchangerate_update_help#}';
MLI18n::gi()->{'bepado_config_price__field__exchangerate_update__alert'} = '{#i18n:form_config_orderimport_exchangerate_update_alert#}';
MLI18n::gi()->{'bepado_config_sync__legend__sync'} = 'Inventory Synchronization';
MLI18n::gi()->{'bepado_config_sync__field__stocksync.tomarketplace__label'} = 'stock change shop';
MLI18n::gi()->{'bepado_config_sync__field__stocksync.tomarketplace__hint'} = '';
MLI18n::gi()->{'bepado_config_sync__field__stocksync.tomarketplace__help'} = '<dl>
            <dt>Automatic Synchronization via CronJob (recommended)</dt>
                    <dd>Current DaWanda stock will be synchronized with shop stock every 4 hours, beginning at 0.00am (with ***, depending on configuration).<br>Values will be transferred from the database, including the changes that occur through an ERP or similar.<br><br>
Manual comparison can be activated by clicking the corresponding button in the magnalister header (left of the shopping cart).<br><br>
Additionally, you can activate the stock comparison through CronJon (flat tariff*** - maximum every 4 hours) with the link:<br>
            <i>{#setting:sSyncInventoryUrl#}</i><br>

Some CronJob requests may be blocked, if they are made through customers not on the flat tariff*** or if the request is made more than once every 4 hours.
</dd>
                        
                    </dl>
                    <b>Note:</b> The settings in \'Configuration\' ,&rarr; ‘Article upload:preset’  &rarr; ‘Stock quantity’ will the taken into account.';
MLI18n::gi()->{'bepado_config_sync__field__stocksync.frommarketplace__label'} = 'stock change DaWanda';
MLI18n::gi()->{'bepado_config_sync__field__stocksync.frommarketplace__hint'} = '';
MLI18n::gi()->{'bepado_config_sync__field__stocksync.frommarketplace__help'} = 'If, for example, an item is purchased 3 times on DaWanda, the Shop inventory will be reduced by 3.<br /><br />
<strong>Important:</strong>This function will only work if you have Order Imports activated!';
MLI18n::gi()->{'bepado_config_sync__field__inventorysync.price__label'} = 'Item Price';
MLI18n::gi()->{'bepado_config_sync__field__inventorysync.price__hint'} = '';
MLI18n::gi()->{'bepado_config_sync__field__inventorysync.price__help'} = '<p> Current DaWanda price  will be synchronized with shop stock every 4 hours, beginning at 0.00am (with ***, depending on configuration)<br>
Values will be transferred from the database, including the changes that occur through an ERP or similar.<br><br>
<b>Hint:</b> The settings in \'Configuration\', \'price calculation\' will be taken into account.
';
MLI18n::gi()->{'bepado_config_orderimport__legend__importactive'} = 'Order Import';
MLI18n::gi()->{'bepado_config_orderimport__legend__mwst'} = 'VAT';
MLI18n::gi()->{'bepado_config_orderimport__legend__orderstatus'} = 'Synchronization of the order status from shop to DaWanda';
MLI18n::gi()->{'bepado_config_orderimport__field__orderimport.shop__label'} = '{#i18n:form_config_orderimport_shop_lable#}';
MLI18n::gi()->{'bepado_config_orderimport__field__orderimport.shop__hint'} = '';
MLI18n::gi()->{'bepado_config_orderimport__field__orderimport.shop__help'} = '{#i18n:form_config_orderimport_shop_help#}';
MLI18n::gi()->{'bepado_config_orderimport__field__orderstatus.shipped__label'} = 'Confirm shipping with';
MLI18n::gi()->{'bepado_config_orderimport__field__orderstatus.shipped__hint'} = '';
MLI18n::gi()->{'bepado_config_orderimport__field__orderstatus.shipped__help'} = 'Select the shop status that will automatically set the daWanda status to "confirm shipment".';
MLI18n::gi()->{'bepado_config_orderimport__field__orderstatus.canceled__label'} = 'Cancel Order With';
MLI18n::gi()->{'bepado_config_orderimport__field__orderstatus.canceled__hint'} = '';
MLI18n::gi()->{'bepado_config_orderimport__field__orderstatus.canceled__help'} = 'Here you set the shop status which will set the DaWanda order status to „cancel order“. <br/><br/>
Note: partial cancellation is not possible in this setting. The whole order will be cancelled with this function und credited tot he customer
';
MLI18n::gi()->{'bepado_config_orderimport__field__mwst.fallback__label'} = 'VAT on Non-Shop*** Items';
MLI18n::gi()->{'bepado_config_orderimport__field__mwst.fallback__hint'} = 'The tax rate to apply to non-Shop items on order imports, in %.';
MLI18n::gi()->{'bepado_config_orderimport__field__mwst.fallback__help'} = 'If an item is not entered in the web-shop, magnalister uses the VAT from here since marketplaces give no details to VAT within the order import.<br />
<br />
Further explanation:<br />
Basically, magnalister calculates the VAT the same way the shop-system does itself.<br />
VAT per country can only be considered if the article can be found in the web-shop with his number range (SKU).<br />
magnalister uses the configured web-shop-VAT-classes.
';
MLI18n::gi()->{'bepado_config_orderimport__field__importactive__label'} = 'Activate Import';
MLI18n::gi()->{'bepado_config_orderimport__field__importactive__hint'} = '';
MLI18n::gi()->{'bepado_config_orderimport__field__importactive__help'} = 'Import orders from the Marketplace? <br/><br/>When activated, orders will be automatically imported every hour.<br><br>
Manual import can be activated by clicking the corresponding button in the magnalister header (left of the shopping cart).<br><br>Additionally, you can activate the stock comparison through CronJon (flat tariff*** - maximum every 4 hours) with the link:<br>
            <i>{#setting:sImportOrdersUrl#}</i><br>
Some CronJob requests may be blocked, if they are made through customers not on the flat tariff*** or if the request is made more than once every 4 hours. 
';
MLI18n::gi()->{'bepado_config_orderimport__field__import__label'} = '';
MLI18n::gi()->{'bepado_config_orderimport__field__import__hint'} = '';
MLI18n::gi()->{'bepado_config_orderimport__field__preimport.start__label'} = 'first from date';
MLI18n::gi()->{'bepado_config_orderimport__field__preimport.start__hint'} = 'Start Date';
MLI18n::gi()->{'bepado_config_orderimport__field__preimport.start__help'} = 'The date from which orders will start being imported. Please note that it is not possible to set this too far in the past, as the data only remains available on DaWanda for a few weeks.***';
MLI18n::gi()->{'bepado_config_orderimport__field__customergroup__label'} = 'Customer Group';
MLI18n::gi()->{'bepado_config_orderimport__field__customergroup__hint'} = '';
MLI18n::gi()->{'bepado_config_orderimport__field__customergroup__help'} = 'The customer group that customers from new orders should be sorted into. ';
MLI18n::gi()->{'bepado_config_orderimport__field__orderstatus.open__label'} = 'Order Status in Shop';
MLI18n::gi()->{'bepado_config_orderimport__field__orderstatus.open__hint'} = '';
MLI18n::gi()->{'bepado_config_orderimport__field__orderstatus.open__help'} = 'The status that should be transferred automatically to the Shop after a new order on DaWanda. <br />
If you are using a connected dunning process***, it is recommended to set the Order Status to ‘Paid’ (‘Configuration’ > ‘Order Status’).
';
MLI18n::gi()->{'bepado_config_orderimport__field__orderimport.shippingmethod__label'} = 'Shipping Service of the Orders';
MLI18n::gi()->{'bepado_config_orderimport__field__orderimport.shippingmethod__help'} = 'Shipping methods that will be assigned to all Bepado orders. Standard: "Bepado"<br><br>
This setting is necessary for the invoice and shipping notice, and for editing orders later in the Shop or via ERP.';
MLI18n::gi()->{'bepado_config_emailtemplate__legend__mail'} = '{#i18n:configform_emailtemplate_legend#}';
MLI18n::gi()->{'bepado_config_emailtemplate__field__mail.send__label'} = '{#i18n:configform_emailtemplate_field_send_label#}';
MLI18n::gi()->{'bepado_config_emailtemplate__field__mail.send__help'} = '{#i18n:configform_emailtemplate_field_send_help#}';
MLI18n::gi()->{'bepado_config_emailtemplate__field__mail.originator.name__label'} = 'Sender\'s name';
MLI18n::gi()->{'bepado_config_emailtemplate__field__mail.originator.adress__label'} = 'Sender\'s E-Mail-address';
MLI18n::gi()->{'bepado_config_emailtemplate__field__mail.subject__label'} = 'Subject';
MLI18n::gi()->{'bepado_config_emailtemplate__field__mail.content__label'} = 'E-Mail content';
MLI18n::gi()->{'bepado_config_emailtemplate__field__mail.content__hint'} = 'Available place-holder for subject and content:
<dl>
                    <dt>#FIRSTNAME#</dt>
                    <dd>Buyer’s first name</dd>
                    <dt>#LASTNAME#</dt>
                    <dd>Buyer’s last name</dd>
                    <dt>#EMAIL#</dt>
                    <dd>E-Mail address of the buyer</dd>
                    <dt>#PASSWORD#</dt>
                    <dd>Customer’s password for login to your shop. Only for customers which are added new automatically. Otherwise the place-holder is replaced by ‘(as known)’.</dd>
                    <dt>#ORDERSUMMARY#</dt>
                    <dd>Summary of bought articles. Should be in an extra row.<br><i>Can’t be used in the subject!</i>
                    </dd>
                    <dt>#MARKETPLACE#</dt>
                    <dd>Name of the marketplace</dd>
                    <dt>#SHOPURL#</dt>
                    <dd>URL to your Shop</dd>
                    <dt>#ORIGINATOR#</dt>
                    <dd>Senders Name</dd>
                </dl>';
MLI18n::gi()->{'bepado_config_emailtemplate__field__mail.copy__label'} = 'Copy to sender';
MLI18n::gi()->{'bepado_config_emailtemplate__field__mail.copy__help'} = 'Copy will be sent to sender\'s E-Mail address';
