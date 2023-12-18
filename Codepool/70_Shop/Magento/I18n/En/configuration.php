<?php
/* Autogenerated file. Do not change! */

MLI18n::gi()->{'Magento_Global_Configuration_Label'} = 'Weight unit';
MLI18n::gi()->{'Magento_Global_Configuration_Description'} = 'Default unit for weight';
MLI18n::gi()->{'form_config_orderimport_exchangerate_update_help'} = '<strong>General:</strong>
<p>
If the currency of the web-shop differs from the marketplace currency, during the order import process and during product uploading, magnalister calculates accordingly to the web-shop default currency.
When importing marketplace orders, concerning the currency settings, magnalister simulates exactly the same behavior like the web-shop saves any frontend-orders.
</p>

<strong>Caution:</strong>
<p>
By activating this function, the currency settings in your web-shop will be updated and overwritten by Magento’s internal Import Service (System > Manage Currencies).
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
</p>
<p>
If an exchange-rate of a marketplace is not configured in the web-shop currency settings, magnalister will display an error message.
</p>';
MLI18n::gi()->{'form_config_orderimport_exchangerate_update_alert'} = '<strong>Caution:</strong>
<p>
By activating this function, the currency settings in your web-shop will be updated and overwritten by Magento’s internal Import Service (System > Manage Currencies).
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
MLI18n::gi()->{'magentospecific_aGeneralForm__orderimport__fields__orderinformation__values__val'} = 'Show the order number and marketplace in invoices.';
MLI18n::gi()->{'magentospecific_aGeneralForm__orderimport__fields__orderinformation__desc'} = 'If you check this field, the order number from the marketplace, and the marketplace name will be stored in the invoice data.<br />The data will be displayed in the invoice, so that the buyer can see where the order comes from.';
MLI18n::gi()->add('configuration', array(
    'field' => array(
        'general.weightunit' => array(
            'label' => '{#i18n:Magento_Global_Configuration_Label#}',
            'help'=> '{#i18n:Magento_Global_Configuration_Description#}',
            )
        )
    )
);