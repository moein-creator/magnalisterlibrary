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

MLI18n::gi()->{'dummymodule_config_general_mwstoken_help'} = 'DummyModule requires authentication to transfer data via magnalister. Please enter the respective keys for Merchant ID, Marketplace ID and MWS Token.<br>
You can request these keys through the relevant DummyModule marketplace that you wish to connect.<br>
<br>
Please find instructions on how to apply for the DummyModule MWS Token in the following FAQ:<br>
<a href="https://otrs.magnalister.com/otrs/public.pl?Action=PublicFAQZoom;ItemID=999" title="DummyModule MWS" target="_blank">How do you request an DummyModule MWS Token?</a>';
MLI18n::gi()->{'dummymodule_config_general_autosync'} = 'Automatic synchronization with CronJob (recommended)';
MLI18n::gi()->{'dummymodule_config_general_nosync'} = 'No synchronization';
MLI18n::gi()->{'dummymodule_config_account_title'} = 'Login Details';
MLI18n::gi()->{'dummymodule_config_account_prepare'} = 'Item Preparation';
MLI18n::gi()->{'dummymodule_config_account_price'} = 'Price Calculation';
MLI18n::gi()->{'dummymodule_configform_orderstatus_sync_values__auto'} = '{#i18n:dummymodule_config_general_autosync#}';
MLI18n::gi()->{'dummymodule_configform_orderstatus_sync_values__no'} = '{#i18n:dummymodule_config_general_nosync#}';
MLI18n::gi()->{'dummymodule_configform_sync_values__auto'} = '{#i18n:dummymodule_config_general_autosync#}';
MLI18n::gi()->{'dummymodule_configform_sync_values__no'} = '{#i18n:dummymodule_config_general_nosync#}';
MLI18n::gi()->{'dummymodule_configform_stocksync_values__rel'} = 'Orders (except for FBA orders) reduce Shop inventory (recommended)';
MLI18n::gi()->{'dummymodule_configform_stocksync_values__fba'} = 'Orders (including FBA orders) reduce Shop inventory';
MLI18n::gi()->{'dummymodule_configform_stocksync_values__no'} = '{#i18n:dummymodule_config_general_nosync#}';
MLI18n::gi()->{'dummymodule_configform_pricesync_values__auto'} = '{#i18n:dummymodule_config_general_autosync#}';
MLI18n::gi()->{'dummymodule_configform_pricesync_values__no'} = '{#i18n:dummymodule_config_general_nosync#}';
MLI18n::gi()->{'dummymodule_configform_orderimport_payment_values__textfield__title'} = 'From textfield';
MLI18n::gi()->{'dummymodule_configform_orderimport_payment_values__textfield__textoption'} = '1';
MLI18n::gi()->{'dummymodule_configform_orderimport_payment_values__DummyModule__title'} = 'DummyModule';
MLI18n::gi()->{'dummymodule_configform_orderimport_shipping_values__textfield__title'} = 'From textfield';
MLI18n::gi()->{'dummymodule_configform_orderimport_shipping_values__textfield__textoption'} = '1';
MLI18n::gi()->{'dummymodule_config_account_sync'} = 'Synchronization';
MLI18n::gi()->{'dummymodule_config_account_orderimport'} = 'Order Import';
MLI18n::gi()->{'dummymodule_config_account_emailtemplate'} = '{#i18n:configform_emailtemplate_legend#}';
MLI18n::gi()->{'dummymodule_config_account_shippinglabel'} = 'Shipping Costs';
MLI18n::gi()->{'dummymodule_config_account_emailtemplate_sender'} = 'Example Shop';
MLI18n::gi()->{'dummymodule_config_account_emailtemplate_sender_email'} = 'example@onlineshop.com';
MLI18n::gi()->{'dummymodule_config_account_emailtemplate_subject'} = 'Your Order from #SHOPURL#';
MLI18n::gi()->{'dummymodule_config_account_emailtemplate_content'} = ' <style><!--
body {
    font: 12px sans-serif;
}
table.ordersummary {
	width: 100%;
	border: 1px solid #e8e8e8;
}
table.ordersummary td {
	padding: 3px 5px;
}
table.ordersummary thead td {
	background: #cfcfcf;
	color: #000;
	font-weight: bold;
	text-align: center;
}
table.ordersummary thead td.name {
	text-align: left;
}
table.ordersummary tbody tr.even td {
	background: #e8e8e8;
	color: #000;
}
table.ordersummary tbody tr.odd td {
	background: #f8f8f8;
	color: #000;
}
table.ordersummary td.price,
table.ordersummary td.fprice {
	text-align: right;
	white-space: nowrap;
}
table.ordersummary tbody td.qty {
	text-align: center;
}
--></style>
<p>Hello #FIRSTNAME# #LASTNAME#,</p>
<p>Thank you for your order! The following items were purchased via #MARKETPLACE#:</p>
#ORDERSUMMARY#
<p>Shipping costs are included.</p>
<p>&nbsp;</p>
<p>Sincerely,</p>
<p>Your Online Shop Team</p>';
MLI18n::gi()->{'dummymodule_config_tier_error'} = 'Configuration for B2B Quantity Discount Tier %s is not valid!';
MLI18n::gi()->{'dummymodule_config_account__legend__account'} = 'Login Details';
MLI18n::gi()->{'dummymodule_config_account__legend__tabident'} = '';
MLI18n::gi()->{'dummymodule_config_account__field__tabident__label'} = '{#i18n:ML_LABEL_TAB_IDENT#}';
MLI18n::gi()->{'dummymodule_config_account__field__tabident__help'} = '{#i18n:ML_TEXT_TAB_IDENT#}';
MLI18n::gi()->{'dummymodule_config_account__field__username__label'} = 'Seller Central E-Mail Address';
MLI18n::gi()->{'dummymodule_config_account__field__username__hint'} = '';
MLI18n::gi()->{'dummymodule_config_account__field__password__label'} = 'Seller Central Password';
MLI18n::gi()->{'dummymodule_config_account__field__password__help'} = 'Enter your current DummyModule password in order to log into your Seller Central account.';
MLI18n::gi()->{'dummymodule_config_account__field__mwstoken__label'} = 'MWS Token';
MLI18n::gi()->{'dummymodule_config_account__field__mwstoken__help'} = '{#i18n:dummymodule_config_general_mwstoken_help#}';
MLI18n::gi()->{'dummymodule_config_account__field__merchantid__label'} = 'Merchant ID';
MLI18n::gi()->{'dummymodule_config_account__field__merchantid__help'} = '{#i18n:dummymodule_config_general_mwstoken_help#}';
MLI18n::gi()->{'dummymodule_config_account__field__marketplaceid__label'} = 'Marketplace ID';
MLI18n::gi()->{'dummymodule_config_account__field__marketplaceid__help'} = '{#i18n:dummymodule_config_general_mwstoken_help#}';
MLI18n::gi()->{'dummymodule_config_account__field__site__label'} = 'DummyModule Site';
MLI18n::gi()->{'dummymodule_config_prepare__legend__prepare'} = 'Prepare Items';
MLI18n::gi()->{'dummymodule_config_prepare__legend__machingbehavior'} = 'Matching Behaviour';
MLI18n::gi()->{'dummymodule_config_prepare__legend__apply'} = 'Create New Products';
MLI18n::gi()->{'dummymodule_config_prepare__legend__shipping'} = 'Shipping';
MLI18n::gi()->{'dummymodule_config_prepare__legend__upload'} = 'Upload Items: Presets ';
MLI18n::gi()->{'dummymodule_config_prepare__legend__shippingtemplate'} = 'Seller shipping groups';
MLI18n::gi()->{'dummymodule_config_prepare__legend__b2b'} = 'DummyModule Business (B2B)';
MLI18n::gi()->{'dummymodule_config_prepare__field__itemcondition__label'} = 'Item Condition';
MLI18n::gi()->{'dummymodule_config_prepare__field__prepare.status__label'} = 'Status Filter';
MLI18n::gi()->{'dummymodule_config_prepare__field__prepare.status__valuehint'} = 'Only transfer active items';
MLI18n::gi()->{'dummymodule_config_prepare__field__checkin.status__label'} = 'Status Filter';
MLI18n::gi()->{'dummymodule_config_prepare__field__checkin.status__valuehint'} = 'Only transfer active items';
MLI18n::gi()->{'dummymodule_config_prepare__field__lang__label'} = 'Item Description';
MLI18n::gi()->{'dummymodule_config_prepare__field__internationalshipping__label'} = 'International Shipping';
MLI18n::gi()->{'dummymodule_config_prepare__field__multimatching__label'} = 'Match New';
MLI18n::gi()->{'dummymodule_config_prepare__field__multimatching__valuehint'} = 'Overwrite products already matched by multi- and automatching. ';
MLI18n::gi()->{'dummymodule_config_prepare__field__multimatching__help'} = 'By activating this, already matched products will be overwritten by new matching ***';
MLI18n::gi()->{'dummymodule_config_prepare__field__multimatching.itemsperpage__label'} = 'Results';
MLI18n::gi()->{'dummymodule_config_prepare__field__multimatching.itemsperpage__help'} = 'Here you can determine how many products will be shown per page of multimatching. <br/>
A higher number will mean longer loading times (e.g. 50 results will take around 30 seconds). ';
MLI18n::gi()->{'dummymodule_config_prepare__field__multimatching.itemsperpage__hint'} = 'per page of multimatching';
MLI18n::gi()->{'dummymodule_config_prepare__field__prepare.manufacturerfallback__label'} = 'Alternative Manufacturer';
MLI18n::gi()->{'dummymodule_config_prepare__field__prepare.manufacturerfallback__help'} = 'If a product has no manufacturer assigned, the alternative manufacturer will be used here.<br />
You can also match the general \'Manufacturer\' to your attributes under \'Global Configurations\' > \'Product Attributes\'. ';
MLI18n::gi()->{'dummymodule_config_prepare__field__quantity__label'} = 'Inventory Item Count';
MLI18n::gi()->{'dummymodule_config_prepare__field__quantity__help'} = 'Please enter how much of the inventory should be available on the marketplace.<br/>
                        <br/>
You can change the individual item count directly under \'Upload\'. In this case it is recommended that you turn off automatic<br/>
synchronization under \'Synchronization of Inventory\' > \'Stock Sync to Marketplace\'.<br/>
                        <br/>
To avoid overselling, you can activate \'Transfer shop inventory minus value from the right field\'.
                        <br/>
<strong>Example:</strong> Setting the value at 2 gives &#8594; Shop inventory: 10 &#8594; DummyModule inventory: 8<br/>
                        <br/>
                        <strong>Please note:</strong>If you want to set an inventory count for an item in the Marketplace to \'0\', which is already set as Inactive in the Shop, independent of the actual inventory count, please proceed as follows:<br/>
                        <ul>
                        <li>\'Synchronize Inventory"> Set "Edit Shop Inventory" to "Automatic Synchronization with CronJob"</li>
                        <li>"Global Configuration" > "Product Status" > Activate setting "If product status is inactive, treat inventory count as 0"</li>
                        </ul>';
MLI18n::gi()->{'dummymodule_config_prepare__field__leadtimetoship__label'} = 'Handling time (in days)';
MLI18n::gi()->{'dummymodule_config_prepare__field__leadtimetoship__help'} = 'The elapsed time between when the buyer places the order until you hand the order over to your carrier.<br>If no value is entered, the handling time will be set at 1-2 working days. Use this field if the handling time is more that 2 working days.';
MLI18n::gi()->{'dummymodule_config_prepare__field__checkin.skuasmfrpartno__label'} = 'Manufacturer-Part-Number';
MLI18n::gi()->{'dummymodule_config_prepare__field__checkin.skuasmfrpartno__help'} = 'SKU will be used as Manufacturer-Part-Number.';
MLI18n::gi()->{'dummymodule_config_prepare__field__checkin.skuasmfrpartno__valuehint'} = 'Use the SKU as Manufacturer-Part-Number';
MLI18n::gi()->{'dummymodule_config_prepare__field__imagesize__label'} = 'Image Size';
MLI18n::gi()->{'dummymodule_config_prepare__field__imagesize__help'} = '<p>Please enter the pixel width for the image as should appear on the Marketplace. The height will be automatically matched based on the original aspect ratio.</p>
<p>The source files will be processed from the image folder {#setting:sSourceImagePath#}, and will be stored in the folder {#setting:sImagePath#} with the selected pixel width for use on the Marketplace.</p>';
MLI18n::gi()->{'dummymodule_config_prepare__field__imagesize__hint'} = 'Saved under {#setting:sImagePath#}';
MLI18n::gi()->{'dummymodule_config_prepare__field__shipping.template.active__label'} = 'Use seller shipping groups';
MLI18n::gi()->{'dummymodule_config_prepare__field__shipping.template.active__help'} = 'Seller can generate groups with different shipping services / methods specially for personal commercial needs and use cases. Different groups of shipping methods with different shipping conditions and shipping fees can be chosen for different regions. 

If a seller generates an offer out of his products, he has to choose one of his shipping condition groups fort he product. That shipping procedure of this group is then used to show the shipping option for the product on the website.';
MLI18n::gi()->{'dummymodule_config_prepare__field__shipping.template__label'} = 'Seller shipping groups';
MLI18n::gi()->{'dummymodule_config_prepare__field__shipping.template__hint'} = 'A specific shipping procedures group that will be set for a seller specific offer. The seller shipping group is generated and administered by the seller in the user interface for shipping services.';
MLI18n::gi()->{'dummymodule_config_prepare__field__shipping.template__help'} = 'Seller can generate groups with different shipping services / methods specially for personal commercial needs and use cases. Different groups of shipping methods with different shipping conditions and shipping fees can be chosen for different regions. 

If a seller generates an offer out of his products, he has to choose one of his shipping condition groups fort he product. That shipping procedure of this group is then used to show the shipping option for the product on the website.';
MLI18n::gi()->{'dummymodule_config_prepare__field__shipping.template.name__label'} = 'Seller shipping group label';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2bactive__label'} = 'Activate Business';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2bactive__help'} = 'To be able to use this function you must activate the DummyModule Business Seller Service: 
If you are already an DummyModule merchant you can therefore login to your Seller Central Account and activate the Business Seller Service. A "Professional Seller Account" is required for that. (Can be upgraded within your Seller Account).<br />
Please also read the notes in the Info-Icon under "Order Import" > "Activate Import".';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2bactive__notification'} = 'In order to use DummyModule Business features you need to have your 
DummyModule account activated for this. <b>Please make sure that your account is enabled for DummyModule Business services.</b> 
Otherwise, you might experience errors during upload if this option is enabled.
<br>To upgrade your account, please follow instructions on 
<a href="https://sellercentral.dummymodule.de/business/b2bregistration" target="_blank">this page</a>.';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2bactive__values__true'} = 'Yes';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2bactive__values__false'} = 'No';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2b.tax_code__label'} = 'Business Tax Matching';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2b.tax_code__hint'} = '';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2b.tax_code__matching__titlesrc'} = 'Shop Tax Classes';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2b.tax_code__matching__titledst'} = 'DummyModule Business Tax Codes';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2b.tax_code_container__label'} = 'Business Tax Matching by Category';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2b.tax_code_container__hint'} = '';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2b.tax_code_specific__label'} = '';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2b.tax_code_specific__hint'} = '';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2b.tax_code_specific__matching__titlesrc'} = 'Shop Tax Classes';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2b.tax_code_specific__matching__titledst'} = 'DummyModule Business Tax Classes';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2b.tax_code_category__label'} = '';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2b.tax_code_category__hint'} = '';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2bsellto__label'} = 'Sell to';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2bsellto__help'} = 'If <i>B2B Only</i> is selected, uploaded products with this option will be visible only for 
        Business customers on DummyModule. Otherwise, products will be available for both regular and business customers.';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2bsellto__values__b2b_b2c'} = 'B2B and B2C';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2bsellto__values__b2b_only'} = 'B2B Only';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2b.price__label'} = 'Business Price';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2b.price__help'} = 'Please enter a price markup or markdown, either in percentage or fixed amount. 
        Use a minus sign (-) before the amount to denote markdown.';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2b.price.addkind__label'} = '';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2b.price.addkind__hint'} = '';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2b.price.factor__label'} = '';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2b.price.factor__hint'} = '';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2b.price.signal__label'} = 'Decimal Amount';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2b.price.signal__hint'} = 'Decimal Amount';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2b.price.signal__help'} = 'This textfield shows the decimal value that will appear in the item price on DummyModule.<br/><br/>
        <strong>Example:</strong> <br>
        Value in textfeld: 99 <br>
        Original price: 5.58 <br>
        Final amount: 5.99 <br><br>
        This function is useful when marking the price up or down***. <br>
        Leave this field empty if you do not wish to set any decimal amount. <br>
        The format requires a maximum of 2 numbers.';
MLI18n::gi()->{'dummymodule_config_price__legend__price'} = 'Price Calculation';
MLI18n::gi()->{'dummymodule_config_price__field__price__label'} = 'Price';
MLI18n::gi()->{'dummymodule_config_price__field__price__hint'} = '';
MLI18n::gi()->{'dummymodule_config_price__field__price__help'} = 'Please enter a price markup or markdown, either in percentage or fixed amount. Use a minus sign (-) before the amount to denote markdown.';
MLI18n::gi()->{'dummymodule_config_price__field__price.addkind__label'} = '';
MLI18n::gi()->{'dummymodule_config_price__field__price.addkind__hint'} = '';
MLI18n::gi()->{'dummymodule_config_price__field__price.factor__label'} = '';
MLI18n::gi()->{'dummymodule_config_price__field__price.factor__hint'} = '';
MLI18n::gi()->{'dummymodule_config_price__field__price.signal__label'} = 'Decimal Amount';
MLI18n::gi()->{'dummymodule_config_price__field__price.signal__hint'} = 'Decimal Amount';
MLI18n::gi()->{'dummymodule_config_price__field__price.signal__help'} = 'This textfield shows the decimal value that will appear in the item price on Amazon.<br/><br/>
                <strong>Example:</strong> <br />
Value in textfeld: 99 <br />
                Original price: 5.58 <br />
                Final amount: 5.99 <br /><br />
This function is useful when marking the price up or down***. <br/>
Leave this field empty if you do not wish to set any decimal amount. <br/>
The format requires a maximum of 2 numbers.
This function is useful when marking the price up or down***. ';
MLI18n::gi()->{'dummymodule_config_price__field__priceoptions__label'} = 'Price Options';
MLI18n::gi()->{'dummymodule_config_price__field__priceoptions__help'} = '{#i18n:configform_price_field_priceoptions_help#}';
MLI18n::gi()->{'dummymodule_config_price__field__priceoptions__hint'} = '';
MLI18n::gi()->{'dummymodule_config_price__field__price.group__label'} = '';
MLI18n::gi()->{'dummymodule_config_price__field__price.group__hint'} = '';
MLI18n::gi()->{'dummymodule_config_price__field__price.usespecialoffer__label'} = 'Use special offer prices';
MLI18n::gi()->{'dummymodule_config_price__field__price.usespecialoffer__hint'} = '';
MLI18n::gi()->{'dummymodule_config_price__field__exchangerate_update__label'} = 'Exchange Rate';
MLI18n::gi()->{'dummymodule_config_price__field__exchangerate_update__hint'} = 'Automatically update exchange rate';
MLI18n::gi()->{'dummymodule_config_price__field__exchangerate_update__help'} = '{#i18n:form_config_orderimport_exchangerate_update_help#}';
MLI18n::gi()->{'dummymodule_config_price__field__exchangerate_update__alert'} = '{#i18n:form_config_orderimport_exchangerate_update_alert#}';
MLI18n::gi()->{'dummymodule_config_sync__legend__sync'} = 'Inventory Synchronization';
MLI18n::gi()->{'dummymodule_config_sync__field__stocksync.tomarketplace__label'} = 'Stock Sync to Marketplace';
MLI18n::gi()->{'dummymodule_config_sync__field__stocksync.tomarketplace__hint'} = '';
MLI18n::gi()->{'dummymodule_config_sync__field__stocksync.tomarketplace__help'} = '<dl>
            <dt>Automatic Synchronization via CronJob (recommended)</dt>
                    <dd>Current Amazon stock will be synchronized with shop stock every 4 hours, beginning at 0.00am (with ***, depending on configuration).<br>Values will be transferred from the database, including the changes that occur through an ERP or similar.<br><br>
Manual comparison can be activated by clicking the corresponding button in the magnalister header (left of the shopping cart).<br><br>
Additionally, you can activate the stock comparison through CronJon (flat tariff*** - maximum every 4 hours) with the link:<br>
            <i>{#setting:sSyncInventoryUrl#}</i><br>
Some CronJob requests may be blocked, if they are made through customers not on the flat tariff*** or if the request is made more than once every 4 hours. 
</dd>
                            <dt>Editing orders / items will synchronize Amazon and shop stock. </dt>
                                    <dd>If the Shop inventory is changed because of an order or editing an item, the current shop inventory will then be transferred to Amazon.<br>
Changes made only to the database, for example, through an ERP, <b>will not</b> be recorded and transferred!</dd>
                            <dt>Editing orders / items changes the Amazon inventory.</dt>
                                    <dd>For example, if a Shop item is purchased twice, the Amazon inventory will be reduced by 2.<br />
  If the item amount is changed in the shop under \'Edit Item\', the difference from the previous amount will be added or subtracted.<br>
                                        Changes made only to the database, for example, through an ERP, <b>will not</b> be recorded and transferred!</dd>
                    </dl>
                    <b>Note:</b> The settings in \'Configuration\', \'Adjusting Procedure\' and \'Inventory Item Count\' will be taken into account.';
MLI18n::gi()->{'dummymodule_config_sync__field__stocksync.frommarketplace__label'} = 'Stock Sync from Marketplace';
MLI18n::gi()->{'dummymodule_config_sync__field__stocksync.frommarketplace__hint'} = '';
MLI18n::gi()->{'dummymodule_config_sync__field__stocksync.frommarketplace__help'} = 'Example: If an item is purchased 3 times on Amazon, the Shop inventory will be reduced by 3.<br /><br />
				           <strong>Important:</strong> This function only applies if you have activated Order Imports!';
MLI18n::gi()->{'dummymodule_config_sync__field__inventorysync.price__label'} = 'Item Price';
MLI18n::gi()->{'dummymodule_config_sync__field__inventorysync.price__hint'} = '';
MLI18n::gi()->{'dummymodule_config_sync__field__inventorysync.price__help'} = '<dl>
            <dt>Automatic Synchronization via CronJob (recommended)</dt>
                    <dd>The function \'Automatic Synchronization\' synchronizes the Amazon price with the Shop price every 4 hours, beginning at 0.00am (with ***, depending on configuration).<br>Values will be transferred from the database, including the changes that occur through an ERP or similar.<br><br>
Manual comparison can be activated by clicking the corresponding button in the magnalister header (left of the shopping cart).<br><br>
Additionally, you can activate the stock comparison through CronJon (flat tariff*** - maximum every 4 hours) with the link:<br>
            <i>{#setting:sSyncInventoryUrl#}</i><br>
Some CronJob requests may be blocked, if they are made through customers not on the flat tariff*** or if the request is made more than once every 4 hours. 
</dd>
                            <dt>Editing orders / items will synchronize Amazon and Shop price. </dt>
                                    <dd>If the Shop price is changed when editing an item, the current Shop price will then be transferred to Amazon.<br>
Changes made only to the database, for example, through an ERP, <b>will not</b> be recorded and transferred!</dd>
                            <dt>Editing items changes the Amazon price.</dt>
                                    <dd>If the item price is changed in the Shop under \'Edit Item\', the current item price will be transferred to Amazon.<br>
                                        Changes made only to the database, for example, through an ERP, <b>will not</b> be recorded and transferred!</dd>
                    </dl>
                    <b>Note:</b> The settings in \'Configuration\', \'Adjusting Procedure\' and \'Inventory Item Count\' will be taken into account.';
MLI18n::gi()->{'dummymodule_config_orderimport__legend__importactive'} = 'Order Import';
MLI18n::gi()->{'dummymodule_config_orderimport__legend__mwst'} = 'VAT';
MLI18n::gi()->{'dummymodule_config_orderimport__legend__orderstatus'} = 'Order Status Synchronization Between Shop and Amazon';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderstatus.shipped__label'} = 'Confirm Shipping With';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderstatus.shipped__hint'} = '';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderstatus.shipped__help'} = 'Please set the Shop Status that should trigger the \'Shipping Confirmed\' status on Amazon.';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderstatus.canceled__label'} = 'Cancel Order With';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderstatus.canceled__hint'} = '';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderstatus.canceled__help'} = 'Here you set the shop status which will set the Amazon order status to „cancel order“. <br/><br/>
Note: partial cancellation is not possible in this setting. The whole order will be cancelled with this function und credited tot he customer';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderimport.shop__label'} = '{#i18n:form_config_orderimport_shop_lable#}';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderimport.shop__hint'} = '';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderimport.shop__help'} = '{#i18n:form_config_orderimport_shop_help#}';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderimport.paymentmethod__label'} = 'Payment Methods';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderimport.paymentmethod__help'} = '<p>Payment method that will apply to all orders imported from Amazon. Standard: "Amazon"</p>
<p>
This setting is necessary for the invoice and shipping notice, and for editing orders later in the Shop or via ERP.</p>';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderimport.paymentmethod__hint'} = '';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderimport.shippingmethod__label'} = 'Shipping Service of the Orders';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderimport.shippingmethod__help'} = 'Shipping methods that will be assigned to all Amazon orders. Standard: "Marketplace"<br><br>
This setting is necessary for the invoice and shipping notice, and for editing orders later in the Shop or via ERP.';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderimport.shippingmethod__hint'} = '';
MLI18n::gi()->{'dummymodule_config_orderimport__field__mwstfallback__label'} = 'VAT on Non-Shop*** Items';
MLI18n::gi()->{'dummymodule_config_orderimport__field__mwstfallback__hint'} = 'The tax rate to apply to non-Shop items on order imports, in %.';
MLI18n::gi()->{'dummymodule_config_orderimport__field__mwstfallback__help'} = 'If an item is not entered in the web-shop, magnalister uses the VAT from here since marketplaces give no details to VAT within the order import.<br />
<br />
Further explanation:<br />
Basically, magnalister calculates the VAT the same way the shop-system does itself.<br />
VAT per country can only be considered if the article can be found in the web-shop with his number range (SKU).<br />
magnalister uses the configured web-shop-VAT-classes.
';
MLI18n::gi()->{'dummymodule_config_orderimport__field__importactive__label'} = 'Activate Import';
MLI18n::gi()->{'dummymodule_config_orderimport__field__importactive__hint'} = '';
MLI18n::gi()->{'dummymodule_config_orderimport__field__importactive__help'} = 'Orders will be imported on an hourly base if the function is activated<br />
                <br />
Via clicking the Function-Button in the headline up right you can manually import the orders.<br />
                <br />
You also have the possibility to do the Order Import using a Cronjob (every quarter of an hour in the Flat tariff) by clicking the following Link:<br />
 <i>{#setting:sImportOrdersUrl#}</i><br />
                <br />
                <strong>VAT:</strong><br />
                <br />
The taxe rates for the order import for the countries can only be calculated correct if you deposited the concerning tax rates in your web-shop and if the bought articles can be identified in the web shop on the basis of the SKU.<br />
magnalister uses the under "Order Import" > "VAT shop-external article" assigned tax rate as "fallback" if the article can not be found in the web-shop<br /><br />

<strong>Hint for Amazon B2B Orders and billing</strong> (requires participation in Amazon Business Programm):<br /><br />

Amazon does not transmit tax ID numbers for the order import. magnalister consequently can generate the B2B-orders in the web-shop but it won\'t be possible to create formally correct invoicings at all time.
<br /><br />
You have the option to trigger the taxe ID number manually via your Amazon Seller Central and can then maintain it manually in your shop- or ERP-system.
<br /><br />
You can also use the invoicing service for B2B orders from Amazon. All legal relevant data are thereby prepared on the proof for the customer.
<br /><br />
All order-relevant data incl. taxe IDs can be found in the Seller Central under "Reports" > "Tax Documents" if you participate in the Amazon Business Seller Program. The time for IDs to be available depends on your B2B contract with Amazon (Either after 3 or after 30 days).
<br /><br />
Tax IDs can also be found under "Shippment by Amazon" > slider: "Reports" if you are registered for FBA.';
MLI18n::gi()->{'dummymodule_config_orderimport__field__import__label'} = '';
MLI18n::gi()->{'dummymodule_config_orderimport__field__import__hint'} = '';
MLI18n::gi()->{'dummymodule_config_orderimport__field__preimport.start__label'} = 'Start import from';
MLI18n::gi()->{'dummymodule_config_orderimport__field__preimport.start__hint'} = 'Start Date';
MLI18n::gi()->{'dummymodule_config_orderimport__field__preimport.start__help'} = 'The date from which orders will start being imported. Please note that it is not possible to set this too far in the past, as the data only remains available on Amazon for a few weeks.***';
MLI18n::gi()->{'dummymodule_config_orderimport__field__customergroup__label'} = 'Customer Group';
MLI18n::gi()->{'dummymodule_config_orderimport__field__customergroup__hint'} = '';
MLI18n::gi()->{'dummymodule_config_orderimport__field__customergroup__help'} = 'The customer group that customers from new orders should be sorted into.';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderstatus.open__label'} = 'Order Status in Shop';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderstatus.open__hint'} = '';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderstatus.open__help'} = 'The status that should be transferred automatically to the Shop after a new order on Amazon. <br />
If you are using a connected dunning process***, it is recommended to set the Order Status to ‘Paid’ (‘Configuration’ > ‘Order Status’).
';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderstatus.fba__label'} = 'FBA Order Status';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderstatus.fba__hint'} = '';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderstatus.fba__help'} = 'This function is only relevant for sellers participating in \'Fulfilment by Amazon (FBA)\': <br/>The order status will be defined as an FBA-order, and the status will be transferred automatically to your shop.
If you are using a connected dunning process***, it is recommended to set the Order Status to \'Paid\' (\'Configuration\' > \'Order Status\').';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderimport.fbapaymentmethod__label'} = 'Payment Method of FBA Orders';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderimport.fbapaymentmethod__help'} = 'Payment method for Amazon Orders, which are fulfilled (sent) by Amazon (FBA). Standard: "dummymodule".<br><br>
				           This setting is important for bills and shipping notes, the subsequent processing of the order inside the shop, and for some ERPs.';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderimport.fbapaymentmethod__hint'} = '';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderimport.fbashippingmethod__label'} = 'Shipping Service of the Orders (FBA)';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderimport.fbashippingmethod__help'} = 'Shipping method for Amazon Orders, which are fulfilled (sent) by Amazon (FBA). Standard: "dummymodule".<br><br>
				           This setting is important for bills and shipping notes, the subsequent processing of the order inside the shop, and for some ERPs.';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderimport.fbashippingmethod__hint'} = '';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderstatus.carrier.default__label'} = '&nbsp;&nbsp;&nbsp;&nbsp;Carrier';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderstatus.carrier.default__help'} = 'Pre-selected carrier with confirmation of distribution to dummymodule.';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderstatus.carrier.additional__label'} = 'Additional Carriers';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderstatus.carrier.additional__help'} = 'Amazon provides certain default carriers for preselection. You can add to this list by entering additional carriers in the textfield, separated by commas. ';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderstatus.cancelled__label'} = 'Cancel order with';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderstatus.cancelled__hint'} = '';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderstatus.cancelled__help'} = 'Please set the Shop Status that should trigger the ‘Order Cancelled’ status on Amazon.<br/><br/>
Note: Part cancellations are not possible with the Amazon API. This function will cancel the complete order and refund the buyer.';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderimport.dummymodulepromotionsdiscount__label'} = 'Amazon Promotions';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderimport.dummymodulepromotionsdiscount__help'} = '<p>magnalister imports the Amazon Promotions discounts as independent products into your webshop. One product is created in the imported order for each product and shipping discount.</p>
                           <p>In this setting option, you can define your own item numbers for these promotional discounts.</p>';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderimport.dummymodulepromotionsdiscount.products_sku__label'} = 'Product Discount Item number';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderimport.dummymodulepromotionsdiscount.shipping_sku__label'} = 'Shipping Discount Item number';
MLI18n::gi()->{'dummymodule_config_emailtemplate__legend__mail'} = '{#i18n:configform_emailtemplate_legend#}';
MLI18n::gi()->{'dummymodule_config_emailtemplate__field__mail.send__label'} = '{#i18n:configform_emailtemplate_field_send_label#}';
MLI18n::gi()->{'dummymodule_config_emailtemplate__field__mail.send__help'} = '{#i18n:configform_emailtemplate_field_send_help#}';
MLI18n::gi()->{'dummymodule_config_emailtemplate__field__mail.originator.name__label'} = 'Sender Name';
MLI18n::gi()->{'dummymodule_config_emailtemplate__field__mail.originator.adress__label'} = 'Sender E-Mail Address';
MLI18n::gi()->{'dummymodule_config_emailtemplate__field__mail.subject__label'} = 'Subject';
MLI18n::gi()->{'dummymodule_config_emailtemplate__field__mail.content__label'} = 'E-Mail Content';
MLI18n::gi()->{'dummymodule_config_emailtemplate__field__mail.content__hint'} = 'List of available placeholders for Subject and Content:
<dl>
                <dt>#MARKETPLACEORDERID#</dt>
                        <dd>Marketplace Order Id</dd>
                <dt>#FIRSTNAME#</dt>
                        <dd>Buyer\'s first name</dd>
                <dt>#LASTNAME#</dt>
                        <dd>Buyer\'s last name</dd>
                <dt>#EMAIL#</dt>
                        <dd>Buyer\'s email address</dd>
                <dt>#PASSWORD#</dt>
                        <dd>Buyer\'s password for logging in to your Shop. Only for customers that are automatically assigned passwords – otherwise the placeholder will be replaced with \'(as known)\'***.</dd>
                <dt>#ORDERSUMMARY#</dt>
                        <dd>Summary of the purchased items. Should be written on a separate line. <br/><i>Cannot be used in the Subject!</i></dd>
                <dt>#ORIGINATOR#</dt>
                        <dd>Sender name</dd>
        </dl>';
MLI18n::gi()->{'dummymodule_config_emailtemplate__field__mail.copy__label'} = 'Copy to Sender';
MLI18n::gi()->{'dummymodule_config_emailtemplate__field__mail.copy__help'} = 'A copy will be sent to the sender email address.';
MLI18n::gi()->{'dummymodule_config_shippinglabel__legend__shippingaddresses'} = 'shipping adresses';
MLI18n::gi()->{'dummymodule_config_shippinglabel__legend__shippingservice'} = 'shipping settings';
MLI18n::gi()->{'dummymodule_config_shippinglabel__legend__shippinglabel'} = 'shipping options';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.address__label'} = 'shipping adress';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.address.name__label'} = 'Name';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.address.company__label'} = 'company name';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.address.streetandnr__label'} = 'Street name and number';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.address.city__label'} = 'city';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.address.state__label'} = 'federal state';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.address.zip__label'} = 'post code';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.address.country__label'} = 'Land';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.address.phone__label'} = 'Phone number';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.address.email__label'} = 'E-Mail-adresses';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippingservice.carrierwillpickup__label'} = 'package pickup';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippingservice.carrierwillpickup__default'} = 'false';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippingservice.deliveryexperience__label'} = 'Shipping conditions';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.fallback.weight__label'} = 'alternative weight';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.fallback.weight__help'} = 'The here set parameter will be taken, if there is no weight parameter specified for a product.';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.weight.unit__label'} = 'Weight Unit';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.size.unit__label'} = 'Size Unit';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.default.dimension__label'} = 'user-defined package sizes';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.default.dimension.text__label'} = 'Description';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.default.dimension.length__label'} = 'Length';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.default.dimension.width__label'} = 'Width';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.default.dimension.height__label'} = 'Height';
