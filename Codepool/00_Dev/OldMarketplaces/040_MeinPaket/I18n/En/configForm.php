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

MLI18n::gi()->{'meinpaket_config_account_title'} = 'Login Details';
MLI18n::gi()->{'meinpaket_config_account_prepare'} = 'Item Preparation';
MLI18n::gi()->{'meinpaket_config_account_price'} = 'Price Calculation';
MLI18n::gi()->{'meinpaket_config_account_sync'} = 'Synchronization';
MLI18n::gi()->{'meinpaket_config_account_orderimport'} = 'Order Import';
MLI18n::gi()->{'meinpaket_config_account_emailtemplate'} = '{#i18n:configform_emailtemplate_legend#}';
MLI18n::gi()->{'meinpaket_config_account_emailtemplate_sender'} = 'Example Shop';
MLI18n::gi()->{'meinpaket_config_account_emailtemplate_sender_email'} = 'example@onlineshop.com';
MLI18n::gi()->{'meinpaket_config_account_emailtemplate_subject'} = 'Your Order from #SHOPURL#';
MLI18n::gi()->{'meinpaket_config_account_emailtemplate_content'} = '    <style>
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
    <p>Thank you for your order! The following items were purchased via #MARKETPLACE#:</p>
    #ORDERSUMMARY#
    <p>Shipping costs are included.</p>
    <p>You\'ll find more great offers in our shop at <strong>#SHOPURL#</strong>.</p>
    <p>&nbsp;</p>
    <p>Sincerely,</p>
    <p>Your Online Shop Team</p>
';
MLI18n::gi()->{'meinpaket_preimport_start_help'} = 'The date from which orders will start being imported. Please note that it is not possible to set this too far in the past, as the data only remains available on Allyouneed for a few weeks.***';
MLI18n::gi()->{'meinpaket_config_account__legend__account'} = 'Login Details';
MLI18n::gi()->{'meinpaket_config_account__legend__tabident'} = '';
MLI18n::gi()->{'meinpaket_config_account__field__tabident__label'} = '{#i18n:ML_LABEL_TAB_IDENT#}';
MLI18n::gi()->{'meinpaket_config_account__field__tabident__help'} = '{#i18n:ML_TEXT_TAB_IDENT#}';
MLI18n::gi()->{'meinpaket_config_account__field__username__label'} = 'Username';
MLI18n::gi()->{'meinpaket_config_account__field__password__label'} = 'Password';
MLI18n::gi()->{'meinpaket_config_prepare__legend__prepare'} = 'Prepare Items';
MLI18n::gi()->{'meinpaket_config_prepare__legend__shipping'} = 'shipping option';
MLI18n::gi()->{'meinpaket_config_prepare__legend__checkin'} = 'Upload Items: Presets';
MLI18n::gi()->{'meinpaket_config_prepare__field__prepare.status__label'} = 'Status Filter';
MLI18n::gi()->{'meinpaket_config_prepare__field__prepare.status__valuehint'} = 'Only transfer active items';
MLI18n::gi()->{'meinpaket_config_prepare__field__lang__label'} = 'Item Description';
MLI18n::gi()->{'meinpaket_config_prepare__field__catmatch.mpshopcats__label'} = 'Own Categories';
MLI18n::gi()->{'meinpaket_config_prepare__field__catmatch.mpshopcats__valuehint'} = 'Use categories from this shop as own Allyouneed category';
MLI18n::gi()->{'meinpaket_config_prepare__field__shippingcost__label'} = 'Shipping costs';
MLI18n::gi()->{'meinpaket_config_prepare__field__shippingcost__help'} = 'specific shipping costs for product.';
MLI18n::gi()->{'meinpaket_config_prepare__field__shippingcostfixed__label'} = 'fixed shipping costs';
MLI18n::gi()->{'meinpaket_config_prepare__field__shippingcostfixed__valuehint'} = 'shipping costs fixed';
MLI18n::gi()->{'meinpaket_config_prepare__field__shippingcostfixed__help'} = 'Specification if the shipping costs for a product are charged completely.<br><br>
Needs on of the following shipping methods:
<ul><li>Bulk goods</li><li>shipping company goods</li></ul>
';
MLI18n::gi()->{'meinpaket_config_prepare__field__shippingtype__label'} = 'Shipping type';
MLI18n::gi()->{'meinpaket_config_prepare__field__checkin.status__label'} = 'Status Filter';
MLI18n::gi()->{'meinpaket_config_prepare__field__checkin.status__valuehint'} = 'Only transfer active items';
MLI18n::gi()->{'meinpaket_config_prepare__field__checkin.quantity__label'} = 'Inventory Item Count';
MLI18n::gi()->{'meinpaket_config_prepare__field__checkin.quantity__help'} = 'Please enter how much of the inventory should be available on the marketplace.<br/>
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
MLI18n::gi()->{'meinpaket_config_prepare__field__checkin.skipean__label'} = 'Transmit EAN';
MLI18n::gi()->{'meinpaket_config_prepare__field__checkin.skipean__valuehint'} = 'transmit';
MLI18n::gi()->{'meinpaket_config_prepare__field__checkin.skipean__help'} = 'If the checkboy is activated an article-assigned EAN will be transmitted to Allyouneed.<br><br> 
Please note that Allyouneed tries to match the EAN to existing articles. Allyouneed can deny the article in case of different article information. The EAN is not mandantory on Allyouneed.';
MLI18n::gi()->{'meinpaket_config_prepare__field__checkin.leadtimetoship__label'} = 'Lead Time in Days';
MLI18n::gi()->{'meinpaket_config_prepare__field__checkin.leadtimetoship__help'} = 'Please enter the time period (in days) from the receipt of an order and the sending of the item. If no value is entered, the lead time will be set at 1-2 working days. Use this field if the lead time is more that 2 working days.';
MLI18n::gi()->{'meinpaket_config_prepare__field__checkin.taxmatching__label'} = 'Tax class<br> Category-matching';
MLI18n::gi()->{'meinpaket_config_prepare__field__checkin.taxmatching__help'} = 'Shop\'s tax class assigned by MeinPaket';
MLI18n::gi()->{'meinpaket_config_prepare__field__checkin.taxmatching__matching__titlesrc'} = '';
MLI18n::gi()->{'meinpaket_config_prepare__field__checkin.taxmatching__matching__titledst'} = '';
MLI18n::gi()->{'meinpaket_config_prepare__field__checkin.taxmatching__matching__labelsdst__Standard'} = 'Standard';
MLI18n::gi()->{'meinpaket_config_prepare__field__checkin.taxmatching__matching__labelsdst__Reduced'} = 'Reduced';
MLI18n::gi()->{'meinpaket_config_prepare__field__checkin.taxmatching__matching__labelsdst__Free'} = 'tax-free';
MLI18n::gi()->{'meinpaket_config_prepare__field__checkin.manufacturerfallback__label'} = 'Alternative Manufacturer';
MLI18n::gi()->{'meinpaket_config_prepare__field__checkin.manufacturerfallback__help'} = 'If a product has no manufacturer assigned, the alternative manufacturer will be used here.<br />
You can also match the general \'Manufacturer\' to your attributes under \'Global Configurations\' > \'Product Attributes\'. ';
MLI18n::gi()->{'meinpaket_config_prepare__field__checkin.shortdesc__label'} = 'Short description';
MLI18n::gi()->{'meinpaket_config_prepare__field__checkin.shortdesc__help'} = 'The short description is a mandantroy field on Allyouneed. The long description of your shop is here used by default. You can also use the short description from another data field.';
MLI18n::gi()->{'meinpaket_config_prepare__field__checkin.longdesc__label'} = 'Description';
MLI18n::gi()->{'meinpaket_config_prepare__field__checkin.longdesc__help'} = 'Long description os not a mandantory field on Allyouneed. The long description is not transmitted by default. You can also use the long description from another data field to transmit it to Allyouneed.';
MLI18n::gi()->{'meinpaket_config_prepare__field__imagesize__label'} = 'Image Size';
MLI18n::gi()->{'meinpaket_config_prepare__field__imagesize__help'} = '<p>Please enter the pixel width for the image as should appear on the Marketplace. The height will be automatically matched based on the original aspect ratio.</p>
<p>The source files will be processed from the image folder {#setting:sSourceImagePath#}, and will be stored in the folder {#setting:sImagePath#} with the selected pixel width for use on the Marketplace.</p>';
MLI18n::gi()->{'meinpaket_config_prepare__field__imagesize__hint'} = 'Saved under {#setting:sImagePath#}';
MLI18n::gi()->{'meinpaket_config_price__legend__price'} = 'Price Calculation';
MLI18n::gi()->{'meinpaket_config_price__field__price__label'} = 'Price';
MLI18n::gi()->{'meinpaket_config_price__field__price__help'} = 'Please enter a price markup or markdown, either in percentage or fixed amount. Use a minus sign (-) before the amount to denote markdown.';
MLI18n::gi()->{'meinpaket_config_price__field__price.addkind__label'} = '';
MLI18n::gi()->{'meinpaket_config_price__field__price.factor__label'} = '';
MLI18n::gi()->{'meinpaket_config_price__field__price.signal__label'} = 'Decimal Amount';
MLI18n::gi()->{'meinpaket_config_price__field__price.signal__help'} = 'This textfield shows the decimal value that will appear in the item price on Allyouneed.<br/><br/>
                <strong>Example:</strong> <br />
Value in textfeld: 99 <br />
                Original price: 5.58 <br />
                Final amount: 5.99 <br /><br />
This function is useful when marking the price up or down***. <br/>
Leave this field empty if you do not wish to set any decimal amount. <br/>
The format requires a maximum of 2 numbers.';
MLI18n::gi()->{'meinpaket_config_price__field__priceoptions__label'} = 'price options';
MLI18n::gi()->{'meinpaket_config_price__field__priceoptions__help'} = '{#i18n:configform_price_field_priceoptions_help#}';
MLI18n::gi()->{'meinpaket_config_price__field__price.group__label'} = '';
MLI18n::gi()->{'meinpaket_config_price__field__price.usespecialoffer__label'} = '';
MLI18n::gi()->{'meinpaket_config_price__field__price.usespecialoffer__valuehint'} = 'Use special offer prices';
MLI18n::gi()->{'meinpaket_config_price__field__exchangerate_update__label'} = 'Exchange Rate';
MLI18n::gi()->{'meinpaket_config_price__field__exchangerate_update__valuehint'} = 'Automatically update exchange rate';
MLI18n::gi()->{'meinpaket_config_price__field__exchangerate_update__help'} = '{#i18n:form_config_orderimport_exchangerate_update_help#}';
MLI18n::gi()->{'meinpaket_config_price__field__exchangerate_update__alert'} = '{#i18n:form_config_orderimport_exchangerate_update_alert#}';
MLI18n::gi()->{'meinpaket_config_orderimport__legend__importactive'} = 'Order Import';
MLI18n::gi()->{'meinpaket_config_orderimport__legend__mwst'} = 'VAT';
MLI18n::gi()->{'meinpaket_config_orderimport__legend__orderstatus'} = 'synchonization of order status from shop to Allyouneed';
MLI18n::gi()->{'meinpaket_config_orderimport__field__importactive__label'} = 'Activate Import';
MLI18n::gi()->{'meinpaket_config_orderimport__field__importactive__help'} = 'Import orders from the Marketplace? <br/><br/>When activated, orders will be automatically imported every hour.<br><br>
Manual import can be activated by clicking the corresponding button in the magnalister header (left of the shopping cart).<br><br>Additionally, you can activate the stock comparison through CronJon (flat tariff*** - maximum every 4 hours) with the link:<br>
            <i>{#setting:sImportOrdersUrl#}</i><br>
Some CronJob requests may be blocked, if they are made through customers not on the flat tariff*** or if the request is made more than once every 4 hours. 
';
MLI18n::gi()->{'meinpaket_config_orderimport__field__import__label'} = '';
MLI18n::gi()->{'meinpaket_config_orderimport__field__preimport.start__label'} = 'first time from date';
MLI18n::gi()->{'meinpaket_config_orderimport__field__preimport.start__hint'} = 'Start Date';
MLI18n::gi()->{'meinpaket_config_orderimport__field__preimport.start__help'} = 'The date from which orders will start being imported. Please note that it is not possible to set this too far in the past, as the data only remains available on Allyouneed for a few weeks.***';
MLI18n::gi()->{'meinpaket_config_orderimport__field__customergroup__label'} = 'Customer Group';
MLI18n::gi()->{'meinpaket_config_orderimport__field__customergroup__help'} = 'The customer group that customers from new orders should be sorted into.';
MLI18n::gi()->{'meinpaket_config_orderimport__field__orderstatus.open__label'} = 'Order Status in Shop';
MLI18n::gi()->{'meinpaket_config_orderimport__field__orderstatus.open__help'} = 'The status that should be transferred automatically to the Shop after a new order on Allyouneed. <br />
If you are using a connected dunning process***, it is recommended to set the Order Status to ‘Paid’ (‘Configuration’ > ‘Order Status’).
';
MLI18n::gi()->{'meinpaket_config_orderimport__field__orderimport.shippingmethod__label'} = 'Shipping Service of the Orders';
MLI18n::gi()->{'meinpaket_config_orderimport__field__orderimport.shippingmethod__help'} = 'Shipping methods that will be assigned to all Allyouneed orders. Standard: "Marketplace"<br><br>
This setting is necessary for the invoice and shipping notice, and for editing orders later in the Shop or via ERP.';
MLI18n::gi()->{'meinpaket_config_orderimport__field__orderimport.shop__label'} = '{#i18n:form_config_orderimport_shop_lable#}';
MLI18n::gi()->{'meinpaket_config_orderimport__field__orderimport.shop__hint'} = '';
MLI18n::gi()->{'meinpaket_config_orderimport__field__orderimport.shop__help'} = '{#i18n:form_config_orderimport_shop_help#}';
MLI18n::gi()->{'meinpaket_config_orderimport__field__orderimport.paymentmethod__label'} = 'Payment Methods';
MLI18n::gi()->{'meinpaket_config_orderimport__field__orderimport.paymentmethod__help'} = '<p>Payment method that will apply to all orders imported from Allyouneed. Standard: “marketplace”</p>
<p>
This setting is necessary for the invoice and shipping notice, and for editing orders later in the Shop or via ERP.</p>';
MLI18n::gi()->{'meinpaket_config_orderimport__field__mwst.fallback__label'} = 'VAT on Non-Shop*** Items';
MLI18n::gi()->{'meinpaket_config_orderimport__field__mwst.fallback__hint'} = 'The tax rate to apply to non-Shop items on order imports, in %.';
MLI18n::gi()->{'meinpaket_config_orderimport__field__mwst.fallback__help'} = 'If an item is not entered in the web-shop, magnalister uses the VAT from here since marketplaces give no details to VAT within the order import.<br />
<br />
Further explanation:<br />
Basically, magnalister calculates the VAT the same way the shop-system does itself.<br />
VAT per country can only be considered if the article can be found in the web-shop with his number range (SKU).<br />
magnalister uses the configured web-shop-VAT-classes.
';
MLI18n::gi()->{'meinpaket_config_orderimport__field__orderstatus.shipped__label'} = 'Confirm Shipping With';
MLI18n::gi()->{'meinpaket_config_orderimport__field__orderstatus.shipped__help'} = 'Select the shop status that will automatically set the Allyouneed status to "confirm shipment".';
MLI18n::gi()->{'meinpaket_config_orderimport__field__orderstatus.canceled.customerrequest__label'} = 'Cancel (customer request) with';
MLI18n::gi()->{'meinpaket_config_orderimport__field__orderstatus.canceled.customerrequest__help'} = 'Allyouneed demands a reason for a cnancellation.<br><br> 
Here you set the shop status which will set the marketplace  order status to „cancel order“. <br/><br/>
Note: partial cancellation is not possible in this setting. The whole order will be cancelled with this function und credited to the customer
';
MLI18n::gi()->{'meinpaket_config_orderimport__field__orderstatus.canceled.outofstock__label'} = 'cancel (our of stock)';
MLI18n::gi()->{'meinpaket_config_orderimport__field__orderstatus.canceled.outofstock__help'} = 'Allyouneed demands a reason for a cnancellation.<br><br> 
Here you set the shop status which will set the marketplace  order status to „cancel, out of stock“. <br/><br/>
Note: partial cancellation is not possible in this setting. The whole order will be cancelled with this function und credited to the customer
';
MLI18n::gi()->{'meinpaket_config_orderimport__field__orderstatus.canceled.damagedgoods__label'} = 'Cancel (damaged goods)';
MLI18n::gi()->{'meinpaket_config_orderimport__field__orderstatus.canceled.damagedgoods__help'} = 'Allyouneed demands a reason for a cnancellation.<br><br> 
Here you set the shop status which will set the marketplace  order status to „cancel, damaged goods“. <br/><br/>
Note: partial cancellation is not possible in this setting. The whole order will be cancelled with this function und credited to the customer
';
MLI18n::gi()->{'meinpaket_config_orderimport__field__orderstatus.canceled.dealerrequest__label'} = 'Cancel (dealer request)';
MLI18n::gi()->{'meinpaket_config_orderimport__field__orderstatus.canceled.dealerrequest__help'} = 'Allyouneed demands a reason for a cnancellation.<br><br> 
Here you set the shop status which will set the marketplace  order status to „cancel, dealer request“. <br/><br/>
Note: partial cancellation is not possible in this setting. The whole order will be cancelled with this function und credited to the customer
';
MLI18n::gi()->{'meinpaket_config_sync__legend__sync'} = 'Inventory Synchronization';
MLI18n::gi()->{'meinpaket_config_sync__field__stocksync.tomarketplace__label'} = 'Stock Sync to Marketplace';
MLI18n::gi()->{'meinpaket_config_sync__field__stocksync.tomarketplace__help'} = '<dl>
            <dt>Automatic Synchronization via CronJob (recommended)</dt>
                    <dd>Current MeinPaket stock will be synchronized with shop stock every 4 hours, beginning at 0.00am (with ***, depending on configuration).<br>Values will be transferred from the database, including the changes that occur through an ERP or similar.<br><br>
Manual comparison can be activated by clicking the corresponding button in the magnalister header (left of the shopping cart).<br><br>
Additionally, you can activate the stock comparison through CronJon (flat tariff*** - maximum every 4 hours) with the link:<br>
            <i>{#setting:sSyncInventoryUrl#}</i><br>
Some CronJob requests may be blocked, if they are made through customers not on the flat tariff*** or if the request is made more than once every 4 hours. 
</dd>
                            <dt>Editing orders / items will synchronize MeinPaket and shop stock. </dt>
                                    <dd>If the Shop inventory is changed because of an order or editing an item, the current shop inventory will then be transferred to MeinPaket.<br>
Changes made only to the database, for example, through an ERP, <b>will not</b> be recorded and transferred!</dd>
                            <dt>Editing orders / items changes the MeinPaket inventory.</dt>
                                    <dd>For example, if a Shop item is purchased twice, the MeinPaket inventory will be reduced by 2.<br />
  If the item amount is changed in the shop under \'Edit Item\', the difference from the previous amount will be added or subtracted.<br>
                                        Changes made only to the database, for example, through an ERP, <b>will not</b> be recorded and transferred!</dd>
                    </dl>
                    <b>Note:</b> The settings in \'Configuration\', \'Adjusting Procedure\' and \'Inventory Item Count\' will be taken into account.';
MLI18n::gi()->{'meinpaket_config_sync__field__stocksync.tomarketplace__values__auto'} = '{#i18n:configform_sync_value_auto#}';
MLI18n::gi()->{'meinpaket_config_sync__field__stocksync.tomarketplace__values__no'} = '{#i18n:configform_sync_value_no#}';
MLI18n::gi()->{'meinpaket_config_sync__field__stocksync.frommarketplace__label'} = 'inventory variation Allyouneed';
MLI18n::gi()->{'meinpaket_config_sync__field__stocksync.frommarketplace__help'} = 'If, for example, an item is purchased 3 times on Allyouneed, the Shop inventory will be reduced by 3.<br /><br />
<strong>Important:</strong>This function will only work if you have Order Imports activated!
';
MLI18n::gi()->{'meinpaket_config_sync__field__inventorysync.price__label'} = 'Item Price';
MLI18n::gi()->{'meinpaket_config_sync__field__inventorysync.price__help'} = '<p> Current Allyouneed price  will be synchronized with shop stock every 4 hours, beginning at 0.00am (with ***, depending on configuration)<br>
Values will be transferred from the database, including the changes that occur through an ERP or similar.<br><br>
<b>Hint:</b> The settings in \'Configuration\', \'price calculation\' will be taken into account.
';
MLI18n::gi()->{'meinpaket_config_sync__field__inventorysync.price__values__auto'} = '{#i18n:configform_sync_value_auto#}';
MLI18n::gi()->{'meinpaket_config_sync__field__inventorysync.price__values__no'} = '{#i18n:configform_sync_value_no#}';
MLI18n::gi()->{'meinpaket_config_emailtemplate__legend__mail'} = '{#i18n:configform_emailtemplate_legend#}';
MLI18n::gi()->{'meinpaket_config_emailtemplate__field__mail.send__label'} = '{#i18n:configform_emailtemplate_field_send_label#}';
MLI18n::gi()->{'meinpaket_config_emailtemplate__field__mail.send__help'} = '{#i18n:configform_emailtemplate_field_send_help#}';
MLI18n::gi()->{'meinpaket_config_emailtemplate__field__mail.originator.name__label'} = 'Sender Name';
MLI18n::gi()->{'meinpaket_config_emailtemplate__field__mail.originator.adress__label'} = 'Sender E-Mail Address';
MLI18n::gi()->{'meinpaket_config_emailtemplate__field__mail.subject__label'} = 'Subject';
MLI18n::gi()->{'meinpaket_config_emailtemplate__field__mail.content__label'} = 'E-Mail Content';
MLI18n::gi()->{'meinpaket_config_emailtemplate__field__mail.content__hint'} = 'List of available placeholders for Subject and Content:
<dl>
                    <dt>#FIRSTNAME#</dt>
                    <dd>Buyer\'s first name</dd>
                    <dt>#LASTNAME#</dt>
                    <dd>Buyer\'s last name</dd>
                    <dt>#EMAIL#</dt>
                    <dd>Buyer\'s email address</dd>
                    <dt>#PASSWORD#</dt>
                    <dd>Buyer’s password for logging in to your Shop. Only for customers that are automatically assigned passwords – otherwise the placeholder will be replaced with ‘(as known)’***.</dd>
                    <dt>#ORDERSUMMARY#</dt>
                    <dd>Summary of the purchased items. Should be written on a separate line. <br/><i>Cannot be used in the Subject!</i>
                    </dd>
                    <dt>#MARKETPLACE#</dt>
                    <dd>Marketplace Name</dd>
                    <dt>#SHOPURL#</dt>
                    <dd>Your Shop URL</dd>
                    <dt>#ORIGINATOR#</dt>
                    <dd>Sender Name</dd>
                </dl>dddt';
MLI18n::gi()->{'meinpaket_config_emailtemplate__field__mail.copy__label'} = 'Copy to Sender';
MLI18n::gi()->{'meinpaket_config_emailtemplate__field__mail.copy__help'} = 'A copy will be sent to the sender email address.';
