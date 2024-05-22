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

MLI18n::gi()->{'fyndiq_config_account_title'} = 'Login Details';
MLI18n::gi()->{'fyndiq_config_account_prepare'} = 'Item Preparation';
MLI18n::gi()->{'fyndiq_config_account_price'} = 'Price Calculation';
MLI18n::gi()->{'fyndiq_config_account_sync'} = 'Synchronization';
MLI18n::gi()->{'fyndiq_config_account_orderimport'} = 'Order Import';
MLI18n::gi()->{'fyndiq_config_checkin_badshippingcost'} = 'Shipping cost must be a number.';
MLI18n::gi()->{'fyndiq_config_account_emailtemplate'} = '{#i18n:configform_emailtemplate_legend#}';
MLI18n::gi()->{'fyndiq_config_account_emailtemplate_sender'} = 'Example Shop';
MLI18n::gi()->{'fyndiq_config_account_emailtemplate_sender_email'} = 'example@onlineshop.com';
MLI18n::gi()->{'fyndiq_config_account_emailtemplate_subject'} = 'Your Order from #SHOPURL#';
MLI18n::gi()->{'fyndiq_config_account_producttemplate'} = 'Product Template';
MLI18n::gi()->{'fyndiq_config_account__legend__account'} = 'Login Details';
MLI18n::gi()->{'fyndiq_config_account__legend__tabident'} = '';
MLI18n::gi()->{'fyndiq_config_account__field__tabident__label'} = '{#i18n:ML_LABEL_TAB_IDENT#}';
MLI18n::gi()->{'fyndiq_config_account__field__tabident__help'} = '{#i18n:ML_TEXT_TAB_IDENT#}';
MLI18n::gi()->{'fyndiq_config_account__field__mpusername__label'} = 'Username';
MLI18n::gi()->{'fyndiq_config_account__field__mppassword__label'} = 'Password';
MLI18n::gi()->{'fyndiq_config_account__field__mpapitoken__label'} = 'Authentication token';
MLI18n::gi()->{'fyndiq_config_account__field__mpapitoken__help'} = 'Go to the <a href="https://fyndiq.de/merchant/settings/api/" target="_blank">page</a> and click on create account. After registering your account,
            login and go to Settings -> API. Click the generate API v2 token, which will generate the token in the API-token field. Copy the
            content from the field. The username will be the same username as the merchant account previously registered on the Fyndiq
            merchant pages.';
MLI18n::gi()->{'fyndiq_config_prepare__legend__prepare'} = 'Prepare Items';
MLI18n::gi()->{'fyndiq_config_prepare__legend__upload'} = 'Upload Items: Presets';
MLI18n::gi()->{'fyndiq_config_prepare__field__prepare.status__label'} = 'Status Filter';
MLI18n::gi()->{'fyndiq_config_prepare__field__prepare.status__valuehint'} = 'only take active article';
MLI18n::gi()->{'fyndiq_config_prepare__field__lang__label'} = 'Item Description';
MLI18n::gi()->{'fyndiq_config_prepare__field__imagepath__label'} = 'Picture path';
MLI18n::gi()->{'fyndiq_config_prepare__field__identifier__label'} = 'Identifier';
MLI18n::gi()->{'fyndiq_config_prepare__field__vat__label'} = 'Taxe bracket matching';
MLI18n::gi()->{'fyndiq_config_prepare__field__vat__hint'} = '';
MLI18n::gi()->{'fyndiq_config_prepare__field__vat__matching__titlesrc'} = 'shop taxe rate';
MLI18n::gi()->{'fyndiq_config_prepare__field__vat__matching__titledst'} = 'Fyndiq taxe rate';
MLI18n::gi()->{'fyndiq_config_prepare__field__shippingcost__label'} = 'Shipping costs (EUR)';
MLI18n::gi()->{'fyndiq_config_prepare__field__checkin.status__label'} = 'Status Filter';
MLI18n::gi()->{'fyndiq_config_prepare__field__checkin.status__valuehint'} = 'only take active article';
MLI18n::gi()->{'fyndiq_config_prepare__field__checkin.quantity__label'} = 'Stock quantity';
MLI18n::gi()->{'fyndiq_config_prepare__field__checkin.quantity__help'} = 'Please enter how much of the inventory should be available on the marketplace.<br/>
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
                        <li>"Synchronize Inventory"> Set "Edit Shop Inventory" to "Automatic Synchronization with CronJob"</li>
                        <li>"Global Configuration" > "Product Status" > Activate setting "If product status is inactive, treat inventory count as 0"</li>
                        </ul>';
MLI18n::gi()->{'fyndiq_config_prepare__field__customshipping__keytitle'} = 'Shipping text';
MLI18n::gi()->{'fyndiq_config_prepare__field__customshipping__valuetitle'} = 'Shipping costs';
MLI18n::gi()->{'fyndiq_config_prepare__field__imagesize__label'} = 'Image Size';
MLI18n::gi()->{'fyndiq_config_prepare__field__imagesize__help'} = '<p>Please enter the pixel width for the image as should appear on the Marketplace. The height will be automatically matched based on the original aspect ratio.</p>
<p>The source files will be processed from the image folder {#setting:sSourceImagePath#}, and will be stored in the folder {#setting:sImagePath#} with the selected pixel width for use on the Marketplace.</p>';
MLI18n::gi()->{'fyndiq_config_prepare__field__imagesize__hint'} = 'Saved under: {#setting:sImagePath#}';
MLI18n::gi()->{'fyndiq_config_price__legend__price'} = 'Price Calculation';
MLI18n::gi()->{'fyndiq_config_price__field__price__label'} = 'Price';
MLI18n::gi()->{'fyndiq_config_price__field__price__help'} = 'Please enter a price markup or markdown, either in percentage or fixed amount. Use a minus sign (-) before the amount to denote markdown.';
MLI18n::gi()->{'fyndiq_config_price__field__price.addkind__label'} = '';
MLI18n::gi()->{'fyndiq_config_price__field__price.factor__label'} = '';
MLI18n::gi()->{'fyndiq_config_price__field__price.signal__label'} = 'Decimal Amount';
MLI18n::gi()->{'fyndiq_config_price__field__price.signal__hint'} = 'Decimal Amount';
MLI18n::gi()->{'fyndiq_config_price__field__price.signal__help'} = 'This textfield will be taken as position after decimal point for transmitted data to Fyndiq.<br><br>
                <strong>Example:</strong><br>
value in textfield: 99<br>
                price origin: 5.58<br>
                final result: 5.99<br><br>
This function is usefull for percentage markups and markdowns.<br>
Leave this field open if you don’t want to transmit a position after decimal point.<br> The input format is an integral number with a maximum of 2 digits.
';
MLI18n::gi()->{'fyndiq_config_price__field__priceoptions__label'} = 'price options';
MLI18n::gi()->{'fyndiq_config_price__field__price.group__label'} = '';
MLI18n::gi()->{'fyndiq_config_price__field__price.usespecialoffer__label'} = 'Also use special prices';
MLI18n::gi()->{'fyndiq_config_price__field__exchangerate_update__label'} = 'Exchange rate';
MLI18n::gi()->{'fyndiq_config_price__field__exchangerate_update__valuehint'} = 'automatically update exchange rate';
MLI18n::gi()->{'fyndiq_config_price__field__exchangerate_update__help'} = '{#i18n:form_config_orderimport_exchangerate_update_help#}';
MLI18n::gi()->{'fyndiq_config_price__field__exchangerate_update__alert'} = '{#i18n:form_config_orderimport_exchangerate_update_alert#}';
MLI18n::gi()->{'fyndiq_config_sync__legend__sync'} = 'Inventory synchronization';
MLI18n::gi()->{'fyndiq_config_sync__field__stocksync.tomarketplace__label'} = 'stock change shop';
MLI18n::gi()->{'fyndiq_config_sync__field__stocksync.tomarketplace__help'} = '<dl>
            <dt>Automatic Synchronization via CronJob (recommended)</dt>
                    <dd>Current Fyndiq stock will be synchronized with shop stock every 4 hours, beginning at 0.00am (with ***, depending on configuration).<br>Values will be transferred from the database, including the changes that occur through an ERP or similar.<br><br>
Manual comparison can be activated by clicking the corresponding button in the magnalister header (left of the shopping cart).<br><br>
Additionally, you can activate the stock comparison through CronJon (flat tariff*** - maximum every 4 hours) with the link:<br>
            <i>{#setting:sSyncInventoryUrl#}</i><br>

Some CronJob requests may be blocked, if they are made through customers not on the flat tariff*** or if the request is made more than once every 4 hours.
</dd>
                        
                    </dl>
                    <b>Note:</b> The settings in \'Configuration\' ,&rarr; ‘Article upload:preset’  &rarr; ‘Stock quantity’ will the taken into account.';
MLI18n::gi()->{'fyndiq_config_sync__field__stocksync.frommarketplace__label'} = 'stock change Fyndiq';
MLI18n::gi()->{'fyndiq_config_sync__field__stocksync.frommarketplace__help'} = 'If, for example, an item is purchased 3 times on Fyndiq, the Shop inventory will be reduced by 3.<br /><br />
<strong>Important:</strong>This function will only work if you have Order Imports activated!';
MLI18n::gi()->{'fyndiq_config_sync__field__inventorysync.price__label'} = 'Article price';
MLI18n::gi()->{'fyndiq_config_sync__field__inventorysync.price__help'} = '<p> Current Fyndiq price  will be synchronized with shop stock every 4 hours, beginning at 0.00am (with ***, depending on configuration)<br>
Values will be transferred from the database, including the changes that occur through an ERP or similar.<br><br>
<b>Hint:</b> The settings in \'Configuration\', \'price calculation\' will be taken into account.
';
MLI18n::gi()->{'fyndiq_config_orderimport__legend__importactive'} = 'Order import';
MLI18n::gi()->{'fyndiq_config_orderimport__legend__mwst'} = 'VAT';
MLI18n::gi()->{'fyndiq_config_orderimport__legend__orderstatus'} = 'Synchronization of the order status from shop to Fyndiq';
MLI18n::gi()->{'fyndiq_config_orderimport__field__orderimport.shop__label'} = '{#i18n:form_config_orderimport_shop_lable#}';
MLI18n::gi()->{'fyndiq_config_orderimport__field__orderimport.shop__hint'} = '';
MLI18n::gi()->{'fyndiq_config_orderimport__field__orderimport.shop__help'} = '{#i18n:form_config_orderimport_shop_help#}';
MLI18n::gi()->{'fyndiq_config_orderimport__field__orderstatus.shipped__label'} = 'Confirm shipping with';
MLI18n::gi()->{'fyndiq_config_orderimport__field__orderstatus.shipped__help'} = 'Select the shop status that will automatically set the Fyndiq status to "confirm shipment".';
MLI18n::gi()->{'fyndiq_config_orderimport__field__service__label'} = 'Delivery service';
MLI18n::gi()->{'fyndiq_config_orderimport__field__service__help'} = 'Fyndiq allows only certain delivery services';
MLI18n::gi()->{'fyndiq_config_orderimport__field__mwst.fallback__label'} = 'VAT shop-external article';
MLI18n::gi()->{'fyndiq_config_orderimport__field__mwst.fallback__hint'} = 'Taxe rate used in the order import for shop-external article in %.';
MLI18n::gi()->{'fyndiq_config_orderimport__field__mwst.fallback__help'} = 'The VAT can not be determinded if the article is not transmitted via magnalister.<br />
Solution: The here inserted %-value will be assigned to all products where no VAT is known while the order import from Fyndiq.';
MLI18n::gi()->{'fyndiq_config_orderimport__field__importactive__label'} = 'activate import';
MLI18n::gi()->{'fyndiq_config_orderimport__field__importactive__hint'} = '';
MLI18n::gi()->{'fyndiq_config_orderimport__field__importactive__help'} = 'Import orders from the Marketplace? <br/><br/>When activated, orders will be automatically imported every hour.<br><br>
Manual import can be activated by clicking the corresponding button in the magnalister header (left of the shopping cart).<br><br>Additionally, you can activate the stock comparison through CronJon (flat tariff*** - maximum every 4 hours) with the link:<br>
            <i>{#setting:sImportOrdersUrl#}</i><br>
Some CronJob requests may be blocked, if they are made through customers not on the flat tariff*** or if the request is made more than once every 4 hours. 
';
MLI18n::gi()->{'fyndiq_config_orderimport__field__import__label'} = '';
MLI18n::gi()->{'fyndiq_config_orderimport__field__preimport.start__label'} = 'first from date';
MLI18n::gi()->{'fyndiq_config_orderimport__field__preimport.start__hint'} = 'Start time';
MLI18n::gi()->{'fyndiq_config_orderimport__field__preimport.start__help'} = 'Start time for first import of orders. Please note that this is not possible for a random time in the past. Data are utmost available for one week on Fyndiq.';
MLI18n::gi()->{'fyndiq_config_orderimport__field__customergroup__label'} = 'Customer Group';
MLI18n::gi()->{'fyndiq_config_orderimport__field__customergroup__help'} = 'The customer group that customers from new orders should be sorted into.';
MLI18n::gi()->{'fyndiq_config_orderimport__field__orderstatus.open__label'} = 'Order status in the shop';
MLI18n::gi()->{'fyndiq_config_orderimport__field__orderstatus.open__help'} = 'The status that should be transferred automatically to the Shop after a new order on Fyndiq. <br />
If you are using a connected dunning process***, it is recommended to set the Order Status to ‘Paid’ (‘Configuration’ > ‘Order Status’).
';
MLI18n::gi()->{'fyndiq_config_orderimport__field__orderimport.shippingmethod__label'} = 'Shipping Service of the Orders';
MLI18n::gi()->{'fyndiq_config_orderimport__field__orderimport.shippingmethod__help'} = 'Shipping methods that will be assigned to all Fyndiq orders. Standard: "Fyndiq"<br><br>
This setting is necessary for the invoice and shipping notice, and for editing orders later in the Shop or via ERP.';
