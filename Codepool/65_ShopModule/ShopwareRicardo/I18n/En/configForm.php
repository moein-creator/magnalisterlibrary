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
 * (c) 2010 - 2020 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

MLI18n::gi()->{'ricardo_config_producttemplate__field__template.content__label'} = 'Product Description Template';
MLI18n::gi()->{'ricardo_config_producttemplate__field__template.content__hint'} = 'List of available placeholders for Content:
<dl>
    <dt>#TITLE#</dt>
            <dd>Product Name (Title)</dd>
    <dt>#ARTNR#</dt>
            <dd>Item Number in Shop</dd>
    <dt>#PID#</dt>
            <dd>Product ID in Shop</dd>
    <!--<dt>#PRICE#</dt>
            <dd>Price</dd>
    <dt>#VPE#</dt>
            <dd>Price per packing unit</dd>-->
    <dt>#SHORTDESCRIPTION#</dt>
            <dd>Short description from the Shop</dd>
    <dt>#DESCRIPTION#</dt>
            <dd>Description from the Shop</dd>
    <dt>#PICTURE1#</dt>
            <dd>First product image</dd>
    <dt>#PICTURE2# etc.</dt>
            <dd>Second product image; with #PICTURE3#, #PICTURE4# etc, you can transfer as many pictures as you have available in your Shop.</dd><br><dt>Free text field:</dt><br><dt>#Description1#&nbsp;#Freetextfield1#</dt><dt>#Description2#&nbsp;#Freetextfield2#</dt><dt>#Description..#&nbsp;#Freetextfield..#</dt><br><dd>Transfer of item free text field&nbsp;the characters after the placeholder (e.g. #Freetextfield1#) designates the position of the free text field.***
                           <br> See Settings > Basic Settings > Items > Item Free Text Field***</dd><dt>#PROPERTIES#</dt><dd>A list of all product attributes. Appearance can be determined with CSS (see the code from the Standard Template)</dd></dl>';
MLI18n::gi()->{'ricardo_config_orderimport__field__customergroup__help'} = '{#i18n:global_config_orderimport_field_customergroup_help#}';
MLI18n::gi()->{'ricardo_config_orderimport__field__orderimport.paymentmethod__label'} = 'Payment Methods';
MLI18n::gi()->{'ricardo_config_orderimport__field__orderimport.paymentmethod__help'} = '<p>Payment method that will apply to all orders imported from Ricardo.
<p>
Additional payment methods can be added to the list via Shopware > Settings > Payment Methods, then activated here.</p>
<p>
This setting is necessary for the invoice and shipping notice, and for editing orders later in the Shop or via ERP.</p>';
MLI18n::gi()->{'ricardo_config_orderimport__field__orderimport.paymentmethod__hint'} = '';
MLI18n::gi()->{'ricardo_config_orderimport__field__orderimport.shippingmethod__label'} = 'Shipping Service of the Orders';
MLI18n::gi()->{'ricardo_config_orderimport__field__orderimport.shippingmethod__help'} = '<p>Shipping service which will be assigned to all Ricardo orders within the order import</p>
<p>Please choose the available shipping service from the web shop here. You can define the parameters for the drop down menu in Shopware-admin > Shipping > carriers</p>
<p>That configuration is important for printing of the invoice and shipping documents as well as for later adaptation of the invoice in the shop and for your ERP system.</p>';
MLI18n::gi()->{'ricardo_config_orderimport__field__orderimport.shippingmethod__hint'} = '';
MLI18n::gi()->{'ricardo_config_orderimport__field__orderimport.paymentstatus__label'} = 'Payment Status in Shop';
MLI18n::gi()->{'ricardo_config_orderimport__field__orderimport.paymentstatus__help'} = 'Please select which shop system payment status should be set in the order details during the magnalister order import.';
MLI18n::gi()->{'ricardo_config_orderimport__field__orderimport.paymentstatus__hint'} = '';
MLI18n::gi()->{'ricardo_config_price__field__priceoptions__help'} = '<p>With this function you can transfer different prices to the marketplace and synchronize them automatically.<br />
<br />
Select a customer group from your webshop using the dropdown on the right.<br />
<br />
If you do not enter a price in the new customer group, the default price from the webshop will be used automatically. This makes it very easy to enter a different price even for just a few items. The other price configurations are also applied.<br />
<br />
<b>Example:</b></p>
<ul>
<li>Create a customer group in your webshop, e.g. "{#setting:currentMarketplaceName#} customers".</li>
<li>In your webshop, add the wanted prices to the new customer group\'s items.</li>
</ul>
<p>The discount mode of the customer groups can also be used. You can enter a  discount there (in percent). If the discount mode is activated in the Shopware item, the discounted price is transferred to the marketplace via magnalister. Important: the marketplace price is not displayed as a strike price.</p>';
