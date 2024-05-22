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
MLI18n::gi()->{'ebay_config_carrier_option_group_shopfreetextfield_option_carrier'} = 'Shipping Carriers from webshop custom fields for orders';
MLI18n::gi()->{'ebay_config_carrier_option_group_marketplace_carrier'} = 'Shipping Carriers suggested by eBay';
MLI18n::gi()->{'ebay_config_carrier_option_group_additional_option'} = 'Additional options';

MLI18n::gi()->{'ebay_config_producttemplate_content'} = '<style>
ul.magna_properties_list {
    margin: 0 0 20px 0;
    list-style: none;
    padding: 0;
    display: inline-block;
    width: 100%
}
ul.magna_properties_list li {
    border-bottom: none;
    width: 100%;
    height: 20px;
    padding: 6px 5px;
    float: left;
    list-style: none;
}
ul.magna_properties_list li.odd {
    background-color: rgba(0, 0, 0, 0.05);
}
ul.magna_properties_list li span.magna_property_name {
    display: block;
    float: left;
    margin-right: 10px;
    font-weight: bold;
    color: #000;
    line-height: 20px;
    text-align: left;
    font-size: 12px;
    width: 50%;
}
ul.magna_properties_list li span.magna_property_value {
    color: #666;
    line-height: 20px;
    text-align: left;
    font-size: 12px;

    width: 50%;
}
</style>
<p>#TITLE#</p>
<p>#ARTNR#</p>
<p>#SHORTDESCRIPTION#</p>
<p>#PICTURE1#</p>
<p>#PICTURE2#</p>
<p>#PICTURE3#</p>
<p>#DESCRIPTION#</p>
<p>#MOBILEDESCRIPTION#</p>
<p>#Description1# #Freetextfield1#</p>
<p>#Description2# #Freetextfield2#</p>
<div>#PROPERTIES#</div>';
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
MLI18n::gi()->{'ebay_config_orderimport__field__orderimport.paymentmethod__label'} = 'Payment Methods';
MLI18n::gi()->{'ebay_config_orderimport__field__orderimport.paymentmethod__help'} = '<p>Payment method that will apply to all orders imported from eBay***.
<p>
Additional payment methods can be added to the list via Shopware > Settings > Payment Methods, then activated here.</p>
<p>
This setting is necessary for the invoice and shipping notice, and for editing orders later in the Shop or via ERP.</p>';
MLI18n::gi()->{'ebay_config_orderimport__field__orderimport.paymentmethod__hint'} = '';
MLI18n::gi()->{'ebay_config_orderimport__field__orderimport.shippingmethod__label'} = 'Shipping Service of the Orders';
MLI18n::gi()->{'ebay_config_orderimport__field__orderimport.shippingmethod__help'} = '<p>Shipping service that will apply to all orders imported from {#platformName#}. Standard: “Automatic Allocation”</p>
<p>
If you choose “Automatic Allocation”, magnalister will accept the payment method chosen by the buyer on {#platformName#}. This method will then also be added to your payment methods under Shopware > Settings > Payment Methods.</p>
<p>
Additional payment methods can be added to the list via Shopware > Settings > Payment Methods, then activated here.</p>
<p>
This setting is necessary for the invoice and shipping notice, and for editing orders later in the Shop or via ERP.</p>';
MLI18n::gi()->{'ebay_config_orderimport__field__orderimport.shippingmethod__hint'} = '';
MLI18n::gi()->{'ebay_config_orderimport__field__orderimport.paymentstatus__label'} = 'Payment Status in Shop';
MLI18n::gi()->{'ebay_config_orderimport__field__orderimport.paymentstatus__hint'} = 'Please select which shop system payment status should be set in the order details during the magnalister order import.';
MLI18n::gi()->{'ebay_config_orderimport__field__customergroup__help'} = '{#i18n:global_config_orderimport_field_customergroup_help#}';
MLI18n::gi()->{'ebay_config_producttemplate__field__template.content__label'} = 'Product Description Template';
MLI18n::gi()->{'ebay_config_producttemplate__field__template.content__hint'} = 'List of available placeholders for Content:
<dl>
    <dt>#TITLE#</dt>
            <dd>Product Name (Title)</dd><br>
    <dt>#ARTNR#</dt>
            <dd>Item Number in Shop</dd><br>
    <dt>#PID#</dt>
            <dd>Product ID in Shop</dd><br>
    <!--<dt>#PRICE#</dt>
            <dd>Price</dd>
    <dt>#VPE#</dt>
            <dd>Price per packing unit</dd>-->
    <dt>#SHORTDESCRIPTION#</dt>
            <dd>Short description from the Shop</dd><br>
    <dt>#DESCRIPTION#</dt>
            <dd>Description from the Shop</dd><br>
    <dt>#MOBILEDESCRIPTION#</dt>
        <dd>Short description for mobile devices (if defined)</dd><br>
    <dt>#PICTURE1#</dt>
            <dd>First product image</dd><br>
    <dt>#PICTURE2# etc.</dt>
            <dd>Second product image; with #PICTURE3#, #PICTURE4# etc, you can transfer as many pictures as you have available in your Shop.</dd><br><dt>Free text field:</dt><br><dt>#Description1#&nbsp;#Freetextfield1#</dt><dt>#Description2#&nbsp;#Freetextfield2#</dt><dt>#Description..#&nbsp;#Freetextfield..#</dt><br><dd>Transfer of item free text field&nbsp;the characters after the placeholder (e.g. #Freetextfield1#) designates the position of the free text field.***
                   <br> See Settings > Basic Settings > Items > Item Free Text Field***</dd><br>
   <dt>#PROPERTIES#</dt>
        <dd>A list of all product attributes. Appearance can be determined with CSS (see the code from the Standard Template)</dd>
    </dl>';
MLI18n::gi()->add('ebay_config_price', array(
    'field' => array(
        'fixed.priceoptions'   => array(
            'label' => 'Sales Price from Customer Group',
            'help'  => '<p>With this function you can transfer different prices to the marketplace and synchronize them automatically.<br />
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
<p>The discount mode of the customer groups can also be used. You can enter a  discount there (in percent). If the discount mode is activated in the Shopware item, the discounted price is transferred to the marketplace via magnalister. Important: the marketplace price is not displayed as a strike price.</p>',
            'hint' => '',
        ),
        'chinese.priceoptions' => array(
            'label' => 'Price from Customer Group',
            'help' => '<p>With this function you can transfer different prices to the marketplace and synchronize them automatically.<br />
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
<p>The discount mode of the customer groups can also be used. You can enter a  discount there (in percent). If the discount mode is activated in the Shopware item, the discounted price is transferred to the marketplace via magnalister. Important: the marketplace price is not displayed as a strike price.</p>',
            'hint' => '',
        ),
    ),
), true);
