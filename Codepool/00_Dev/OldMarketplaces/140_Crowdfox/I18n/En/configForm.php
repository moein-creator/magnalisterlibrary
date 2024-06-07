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

MLI18n::gi()->{'crowdfox_config_account_title'} = 'Login data';
MLI18n::gi()->{'crowdfox_config_account_prepare'} = 'Article preparation';
MLI18n::gi()->{'crowdfox_config_account_price'} = 'Price Calculation';
MLI18n::gi()->{'crowdfox_config_account_sync'} = 'Inventory synchronization';
MLI18n::gi()->{'crowdfox_config_account_orderimport'} = 'Order import';
MLI18n::gi()->{'crowdfox_config_checkin_badshippingcost'} = 'The field for shipping costs must be numeric.';
MLI18n::gi()->{'crowdfox_config_account__legend__account'} = 'Login data';
MLI18n::gi()->{'crowdfox_config_account__legend__tabident'} = '';
MLI18n::gi()->{'crowdfox_config_account__field__tabident__label'} = '{#i18n:ML_LABEL_TAB_IDENT#}';
MLI18n::gi()->{'crowdfox_config_account__field__tabident__help'} = '{#i18n:ML_TEXT_TAB_IDENT#}';
MLI18n::gi()->{'crowdfox_config_account__field__username__label'} = 'User name';
MLI18n::gi()->{'crowdfox_config_account__field__password__label'} = 'Password';
MLI18n::gi()->{'crowdfox_config_account__field__companyname__label'} = 'Company';
MLI18n::gi()->{'crowdfox_config_account__field__companyname__hint'} = 'Company depositied on Crowdfox with the registration.';
MLI18n::gi()->{'crowdfox_config_prepare__legend__prepare'} = 'Article preparation';
MLI18n::gi()->{'crowdfox_config_prepare__legend__upload'} = 'Article upload: Pre-Settings';
MLI18n::gi()->{'crowdfox_config_prepare__field__prepare.status__label'} = 'Statusfilter';
MLI18n::gi()->{'crowdfox_config_prepare__field__prepare.status__valuehint'} = 'Only take active articles';
MLI18n::gi()->{'crowdfox_config_prepare__field__lang__label'} = 'Article description';
MLI18n::gi()->{'crowdfox_config_prepare__field__imagepath__label'} = 'Picture path';
MLI18n::gi()->{'crowdfox_config_prepare__field__identifier__label'} = 'Identifier';
MLI18n::gi()->{'crowdfox_config_prepare__field__checkin.status__label'} = 'Statusfilter';
MLI18n::gi()->{'crowdfox_config_prepare__field__checkin.status__valuehint'} = 'only take active articles';
MLI18n::gi()->{'crowdfox_config_prepare__field__checkin.quantity__label'} = 'Stock Quantity';
MLI18n::gi()->{'crowdfox_config_prepare__field__checkin.quantity__help'} = 'Please enter how much of the inventory should be available on the marketplace.<br/>
                        <br/>
You can change the individual item count directly under \'Upload\'. In this case it is recommended that you turn off automatic<br/>
synchronization under \'Synchronization of Inventory\' > \'Stock Sync to Marketplace\'.<br/>
                        <br/>
To avoid overselling, you can activate \'Transfer shop inventory minus value from the right field\'.
                        <br/>
<strong>Example:</strong> Setting the value at 2 gives &#8594; Shop inventory: 10 &#8594; Crowdfox  inventory: 8<br/>
                        <br/>
                        <strong>Please note:</strong>If you want to set an inventory count for an item in the Marketplace to \'0\', which is already set as Inactive in the Shop, independent of the actual inventory count, please proceed as follows:<br/>
                        <ul>
                        <li>\'Synchronize Inventory"> Set "Edit Shop Inventory" to "Automatic Synchronization with CronJob"</li>
                        <li>"Global Configuration" > "Product Status" > Activate setting "If product ';
MLI18n::gi()->{'crowdfox_config_prepare__field__gtincolumn__label'} = 'EAN';
MLI18n::gi()->{'crowdfox_config_prepare__field__gtincolumn__help'} = 'Global Trade Item number: Among counted are EAN, ISBN, UPS and JAN. You can get those from the Manufacturer or Supplier. (13 Numbers)<br/>
Products will be listed on Crowdfox in an optimal way.';
MLI18n::gi()->{'crowdfox_config_prepare__field__deliverytime__label'} = 'Delivery time';
MLI18n::gi()->{'crowdfox_config_prepare__field__deliverytime__help'} = 'Delivery time of the offer (Max. 50 Characters) Example: 1-3 working days';
MLI18n::gi()->{'crowdfox_config_prepare__field__deliverycost__label'} = 'Shipping costs (EUR)';
MLI18n::gi()->{'crowdfox_config_prepare__field__deliverycost__help'} = 'Shipping costs of the offer. This is equivalent to the cheapest shipping-cost-type. (Numeric field, seperated by . or ,)';
MLI18n::gi()->{'crowdfox_config_prepare__field__shippingmethod__label'} = 'Shipping method';
MLI18n::gi()->{'crowdfox_config_price__legend__price'} = 'Price Calculation';
MLI18n::gi()->{'crowdfox_config_price__field__price__label'} = 'Price';
MLI18n::gi()->{'crowdfox_config_price__field__price__help'} = 'Please specify a fixed or percental price markup or markdown. Markdown starts with a "-"';
MLI18n::gi()->{'crowdfox_config_price__field__price.addkind__label'} = '';
MLI18n::gi()->{'crowdfox_config_price__field__price.factor__label'} = '';
MLI18n::gi()->{'crowdfox_config_price__field__price.signal__label'} = 'Position after decimal point';
MLI18n::gi()->{'crowdfox_config_price__field__price.signal__hint'} = 'Position after decimal point';
MLI18n::gi()->{'crowdfox_config_price__field__price.signal__help'} = 'This textfield will be taken as position after decimal point for transmitted data to Crowdfox.<br><br>
                <strong>Example:</strong><br>
value in textfield: 99<br>
                price origin: 5.58<br>
                final result: 5.99<br><br>
This function is usefull for percentage markups and markdowns.<br>
Leave this field open if you don’t want to transmit a position after decimal point.<br> The input format is an integral number with a maximum of 2 digits.
';
MLI18n::gi()->{'crowdfox_config_price__field__priceoptions__label'} = 'price options';
MLI18n::gi()->{'crowdfox_config_price__field__price.group__label'} = '';
MLI18n::gi()->{'crowdfox_config_price__field__price.usespecialoffer__label'} = 'Also use special prices';
MLI18n::gi()->{'crowdfox_config_price__field__exchangerate_update__label'} = 'Exchange rate';
MLI18n::gi()->{'crowdfox_config_price__field__exchangerate_update__valuehint'} = 'update exchange rate automatically';
MLI18n::gi()->{'crowdfox_config_price__field__exchangerate_update__help'} = '{#i18n:form_config_orderimport_exchangerate_update_help#}';
MLI18n::gi()->{'crowdfox_config_price__field__exchangerate_update__alert'} = '{#i18n:form_config_orderimport_exchangerate_update_alert#}';
MLI18n::gi()->{'crowdfox_config_sync__legend__sync'} = 'Inventory synchronization';
MLI18n::gi()->{'crowdfox_config_sync__field__stocksync.tomarketplace__label'} = 'stock change shop';
MLI18n::gi()->{'crowdfox_config_sync__field__stocksync.tomarketplace__help'} = '<dl>
            <dt>Automatic Synchronization via CronJob (recommended)</dt>
                    <dd>Current Crowdfox stock will be synchronized with shop stock every 4 hours, beginning at 0.00am (with ***, depending on configuration).<br>Values will be transferred from the database, including the changes that occur through an ERP or similar.<br><br>
Manual comparison can be activated by clicking the corresponding button in the magnalister header (left of the shopping cart).<br><br>
Additionally, you can activate the stock comparison through CronJon (flat tariff*** - maximum every 4 hours) with the link:<br>
            <i>{#setting:sSyncInventoryUrl#}</i><br>

Some CronJob requests may be blocked, if they are made through customers not on the flat tariff*** or if the request is made more than once every 4 hours.
</dd>
                        
                    </dl>
                    <b>Note:</b> The settings in \'Configuration\' ,&rarr; ‘Article upload:preset’  &rarr; ‘Stock quantity’ will the taken into account.';
MLI18n::gi()->{'crowdfox_config_sync__field__stocksync.frommarketplace__label'} = 'stock change Crowdfox';
MLI18n::gi()->{'crowdfox_config_sync__field__stocksync.frommarketplace__help'} = 'If, for example, an item is purchased 3 times on Crowdfox, the Shop inventory will be reduced by 3.<br /><br />
<strong>Important:</strong>This function will only work if you have Order Imports activated!';
MLI18n::gi()->{'crowdfox_config_sync__field__inventorysync.price__label'} = 'Article price';
MLI18n::gi()->{'crowdfox_config_sync__field__inventorysync.price__help'} = '<p> Current Crowdfox price  will be synchronized with shop stock every 4 hours, beginning at 0.00am (with ***, depending on configuration)<br>
Values will be transferred from the database, including the changes that occur through an ERP or similar.<br><br>
<b>Hint:</b> The settings in \'Configuration\', \'price calculation\' will be taken into account.
';
MLI18n::gi()->{'crowdfox_config_orderimport__legend__importactive'} = 'Order improt';
MLI18n::gi()->{'crowdfox_config_orderimport__legend__mwst'} = 'VAT';
MLI18n::gi()->{'crowdfox_config_orderimport__legend__orderstatus'} = 'Synchronization of the order status from shop to Crowdfox';
MLI18n::gi()->{'crowdfox_config_orderimport__field__orderstatus.sync__label'} = 'Status Synchronization';
MLI18n::gi()->{'crowdfox_config_orderimport__field__orderstatus.sync__help'} = '<dl>
            <dt>Automatic Synchronization via CronJob (recommended)</dt>
                    <dd>Current Crowdfox stock will be synchronized with shop stock every 4 hours, beginning at 0.00am (with ***, depending on configuration).<br>Values will be transferred from the database, including the changes that occur through an ERP or similar.<br><br>
Manual comparison can be activated by clicking the corresponding button in the magnalister header (left of the shopping cart).<br><br>
Additionally, you can activate the stock comparison through CronJon (flat tariff*** - maximum every 4 hours) with the link:<br>
            <i>{#setting:sSyncInventoryUrl#}</i><br>

Some CronJob requests may be blocked, if they are made through customers not on the flat tariff*** or if the request is made more than once every 4 hours.
</dd>
                        
                    </dl>
                    <b>Note:</b> The settings in \'Configuration\' ,&rarr; ‘Article upload:preset’  &rarr; ‘Stock quantity’ will the taken into account.';
MLI18n::gi()->{'crowdfox_config_orderimport__field__orderimport.shop__label'} = '{#i18n:form_config_orderimport_shop_lable#}';
MLI18n::gi()->{'crowdfox_config_orderimport__field__orderimport.shop__hint'} = '';
MLI18n::gi()->{'crowdfox_config_orderimport__field__orderimport.shop__help'} = '{#i18n:form_config_orderimport_shop_help#}';
MLI18n::gi()->{'crowdfox_config_orderimport__field__orderstatus.shipped__label'} = 'Confirm shipping with';
MLI18n::gi()->{'crowdfox_config_orderimport__field__orderstatus.shipped__help'} = 'Select the shop status that will automatically set the Crowdfox status to "confirm shipment".';
MLI18n::gi()->{'crowdfox_config_orderimport__field__mwst.fallback__label'} = 'VAT shop-external article';
MLI18n::gi()->{'crowdfox_config_orderimport__field__mwst.fallback__hint'} = 'Taxe rate in % used for shop-external article while the order import.';
MLI18n::gi()->{'crowdfox_config_orderimport__field__mwst.fallback__help'} = 'The VAT can not be determinded if the article is not transmitted via magnalister.<br />
Solution: The here inserted %-value will be assigned to all products where no VAT is known while the order import from Crowdfox.';
MLI18n::gi()->{'crowdfox_config_orderimport__field__importactive__label'} = 'Activate import';
MLI18n::gi()->{'crowdfox_config_orderimport__field__importactive__hint'} = '';
MLI18n::gi()->{'crowdfox_config_orderimport__field__importactive__help'} = 'Import orders from the Marketplace? <br/><br/>When activated, orders will be automatically imported every hour.<br><br>
Manual import can be activated by clicking the corresponding button in the magnalister header (left of the shopping cart).<br><br>Additionally, you can activate the stock comparison through CronJon (flat tariff*** - maximum every 4 hours) with the link:<br>
            <i>{#setting:sImportOrdersUrl#}</i><br>
Some CronJob requests may be blocked, if they are made through customers not on the flat tariff*** or if the request is made more than once every 4 hours. 
';
MLI18n::gi()->{'crowdfox_config_orderimport__field__import__label'} = '';
MLI18n::gi()->{'crowdfox_config_orderimport__field__preimport.start__label'} = 'First from date';
MLI18n::gi()->{'crowdfox_config_orderimport__field__preimport.start__hint'} = 'Start time';
MLI18n::gi()->{'crowdfox_config_orderimport__field__preimport.start__help'} = 'Start time for first import of orders. Please note that this is not possible for a random time in the past. Data are utmost available for one week on Crowdfox.';
MLI18n::gi()->{'crowdfox_config_orderimport__field__customergroup__label'} = 'Customer Group';
MLI18n::gi()->{'crowdfox_config_orderimport__field__customergroup__help'} = 'The customer group that customers from new orders should be sorted into.';
MLI18n::gi()->{'crowdfox_config_orderimport__field__orderstatus.open__label'} = 'Order status in the shop';
MLI18n::gi()->{'crowdfox_config_orderimport__field__orderstatus.open__help'} = 'The status that should be transferred automatically to the Shop after a new order on Crowdfox. <br />
If you are using a connected dunning process***, it is recommended to set the Order Status to ‘Paid’ (‘Configuration’ > ‘Order Status’).
';
MLI18n::gi()->{'crowdfox_config_producttemplate__legend__product__title'} = 'Product-Template';
MLI18n::gi()->{'crowdfox_config_producttemplate__legend__product__info'} = 'Template for product description on Crowdfox. (You can switch the editor in "Global Configuration" > "Expert settings")';
MLI18n::gi()->{'crowdfox_config_producttemplate__field__template.name__label'} = 'Template Product name';
MLI18n::gi()->{'crowdfox_config_producttemplate__field__template.name__help'} = '<dl>
                <dt>Product name on Crowdfox</dt>
                 <dd>Configuration: How the product is named on Crowdfox.
                     The placeholder <b>#TITLE#</b> will replaced by the product name from the shop,
                     <b>#BASEPRICE#</b>by price per unit, as far as deposited in the shop.</dd>
                <dt>Please note:</dt>
                 <dd><b>#BASEPRICE#</b>will be replaced with the product upload because it can be changed in the item preparation.</dd>
                 <dd>Since the base price is a fix value in the titel that can not be updated, the price shouldn’t be changed. This would lead to wrong prices.<br />
                    You can use this placeholder on your own risk. In this case we suggest to  <b>turn off the price sync.</b> (Configuration in the magnalister Crowdfox synchronization).</dd>
                <dt>Important:</dt>
                 <dd>Please note that Crowdfox limits the length for the title to 40 signs. magnalister cuts the title while the product upload to the maximum length.</dd>
            </dl>';
MLI18n::gi()->{'crowdfox_config_producttemplate__field__template.content__label'} = 'Template product description';
MLI18n::gi()->{'crowdfox_config_producttemplate__field__template.content__hint'} = 'List of available placeholder for the product description:
<dl>
                        <dt>#TITLE#</dt>
                                <dd>Product name (title)</dd>
                        <dt>#ARTNR#</dt>
                                <dd>Article number in the Shop</dd>
                        <dt>#PID#</dt>
                                <dd>Products ID in Shop</dd>
                        <!--<dt>#PRICE#</dt>
                                <dd>Price</dd>
                        <dt>#VPE#</dt>
                                <dd>Price per packaging unit</dd>-->
                        <dt>#SHORTDESCRIPTION#</dt>
                                <dd>Short-description from Shop</dd>
                        <dt>#DESCRIPTION#</dt>
                                <dd>Description from  Shop</dd>
                        <dt>#PICTURE1#</dt>
                                <dd>First product picture</dd>
                        <dt>#PICTURE2# and so on.</dt>
                                <dd>Second Product picture; with #PICTURE3#, #PICTURE4# aso. you can transmit additional pictures - as much as available in the shop.</dd>
                </dl>';
MLI18n::gi()->{'crowdfox_config_emailtemplate__legend__mail'} = '{#i18n:configform_emailtemplate_legend#}';
MLI18n::gi()->{'crowdfox_config_emailtemplate__field__mail.send__label'} = '{#i18n:configform_emailtemplate_field_send_label#}';
MLI18n::gi()->{'crowdfox_config_emailtemplate__field__mail.send__help'} = '{#i18n:configform_emailtemplate_field_send_help#}';
MLI18n::gi()->{'crowdfox_config_emailtemplate__field__mail.originator.name__label'} = 'Senders Name';
MLI18n::gi()->{'crowdfox_config_emailtemplate__field__mail.originator.adress__label'} = 'Sender\'s E-Mail-address';
MLI18n::gi()->{'crowdfox_config_emailtemplate__field__mail.subject__label'} = 'Subject';
MLI18n::gi()->{'crowdfox_config_emailtemplate__field__mail.content__label'} = 'E-Mail content';
MLI18n::gi()->{'crowdfox_config_emailtemplate__field__mail.content__hint'} = 'Available place-holder for subject and content:
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
MLI18n::gi()->{'crowdfox_config_emailtemplate__field__mail.copy__label'} = 'Copy to sender';
MLI18n::gi()->{'crowdfox_config_emailtemplate__field__mail.copy__help'} = 'A copy will be sent to sender\'s E-Mail-address';
