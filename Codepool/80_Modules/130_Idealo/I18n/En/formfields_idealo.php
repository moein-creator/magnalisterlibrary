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

// example for overwriting global element
MLI18n::gi()->add('formfields__quantity', array('help' => 'As stock {#setting:currentMarketplaceName#} supports only "availible" or "not availible".<br />Here you can define how the threshold for availible items.'));
MLI18n::gi()->add('formfields__stocksync.frommarketplace', array('help' => '
     <b>Note</b>: {#setting:currentMarketplaceName#} knows only "available" or "not available". Therefore:<br>
    <br>
    <ul>
        <li>Shop&apos;s stock quantity  &gt; 0 = available on {#setting:currentMarketplaceName#}</li>
        <li>Shop&apos;s stock quantity  &lt; 0 = not available on {#setting:currentMarketplaceName#}</li>
    </ul>
    <br>
    Function:<br>
    Automatic Synchronization by CronJob (recommended)<br>
    <br>
    <br>
    The function "Automatic Synchronisation by CronJob" equalizes the current {#setting:currentMarketplaceName#}-stock with the shop-stock every 4 hours.*<br>
    <br>
    <br>
    By this procedure, the database values are checked for changes. The new data will be submitted, also when the changes have been set by an inventory management system.<br>
    <br>
    You can manually synchronize stock changes, by clicking the assigned button in the magnalister-header, next left to the ant-logo.<br>
    <br>
    Additionally, you can synchronize stock changes, by setting a own cronjob to your following shop-link:<br>    <i>{#setting:sSyncInventoryUrl#}</i><br>
    <br>
    Setting an own cronjob is permitted for customers within the service plan "Flat", only.<br>
    Own cronjob-calls, exceeding a quarter of an hour, or calls from customers, who are not within the service plan "Flat", will be blocked.<br>
    <br>
    <br>
    <br>
    <b>Note:</b>Settings in "Configuration" + "Listing Process" ...<br>
    <br>
    + "order limit per day"
    + "quantity"  for the first two options.<br><br>... will be considered.
'));

MLI18n::gi()->add('formfields__maxquantity', array(
    'label' => 'Direct-Buy Orderlimit for one Day',
    'help' => '
        Order limit per day for direct-buy<br />
        <br />
        Here you can define, how many items per day you allow to be sold via idealo direct-buy. Without this indication, your item will remain available in direct-buy until you delete the listing or change any settings.<br />
        <br />
        Please note: This is not your item stock. This is a daily limit defined for idealo direct buy.<br />
        <br />
        Hint:<br />
        Settings made in function "Shop Stock" will be taken into consideration as soon as you configured a value.<br />
        In case you chose "General (from right field)", the daily limit field will not take effect.
    ',
));


MLI18n::gi()->add('formfields_idealo', array(
    'directbuyactive' => array(
        'label' => 'Use idealo Direktkauf',
        'help' => 'Choose here if you use idealo Checkout.
<br><br>
If you click on "Yes", please follow the steps below to get credentials for the idealo Direct Buy Merchant Order API v2:
<ul>
<li>
Log in to your <a target="_blank" href="https://business.idealo.com/">idealo business account</a>.
</li><li>
Navigate to “Direktkauf” (“Checkout”)-> “Integration” -> “Neuen API Client erstellen”.
</li><li>
Select the option “Neuen Produktiv-Client erstellen”.
</li><li>
Copy and save the generated "Client ID" and the "Client Password".
</li>
</ul>
Now you can enter the credentials under magnalister > “idealo Configuration” > "idealo Checkout Client ID" and "idealo Checkout Password". After entering the data, click on "Save". 
',
        'values' => array(
            'true' => 'Yes',
            'false' => 'No',
        ),
    ),
    'idealoclientid'                  => array(
        'label' => '{#setting:currentMarketplaceName#} Checkout "Client ID"',
        'help'  => '
            Enter the "Client ID" here that you generated via your idealo Business Account under “Direktkauf” (“Checkout”) -> “Integration” -> “Neuen API Client erstellen”.
<br><br>
You can find more information about this in the info icon next to "Use idealo Direktkauf".
        '
    ),
    'idealopassword'                  => array(
        'label' => '{#setting:currentMarketplaceName#} Checkout “Client-Password”',
        'help'  => '
            Enter the "Client Password" here that you generated via your idealo Business Account under “Direktkauf” (“Checkout”) -> “Integration” -> “Neuen API Client erstellen”.
<br><br>
You can find more information about this in the info icon next to "Use idealo Checkout".
            '
    ),
    'shippingcountry' => array(
        'label' => 'Shipping to',
    ),
    'shippingmethodandcost' => array(
        'label' => 'Shipping Cost',
        'help' => 'Please specify the default shipping costs here. You can then adjust the values for the chosen items in the item preparation form.',
    ),
    'shippingcostmethod' => array(
        'values' => array(
            '__ml_lump' => MLI18n::gi()->ML_COMPARISON_SHOPPING_LABEL_LUMP,
            '__ml_weight' => 'Shipping cost = Product weight',
        ),
    ),
    'subheader.pd' => array(
        'label' => 'Price Comparision Shopping and Direct-buy'
    ),
    'paymentmethod' => array(
        'label' => 'Payment Methods',
        'help' => '
            Select here the default payment methods for comparison shopping portal and direct-buy (multi selection is possible).<br />
            You can change these payment methods during item preparation.<br />
            <br />
            <strong>Caution:</strong> {#setting:currentMarketplaceName#} exclusively accepts PayPal, Sofortüberweisung and credit card as payment methods for direct-buy.',
        'values' => array(
            'Direktkauf & Suchmaschine:' => array(
                'PAYPAL' => 'PayPal',
                'CREDITCARD' => 'Credit Card',
                'SOFORT' => 'Sofort&uuml;berweisung'
            ),
            'Nur Suchmaschine:' => array(
                'PRE' => 'payment in advance',
                'COD' => 'cash on delivery',
                'BANKENTER' => 'bank enter',
                'BILL' => 'bill',
                'GIROPAY' => 'Giropay',
                'CLICKBUY' => 'Click&Buy',
                'SKRILL' => 'Skrill'
            ),
        ),
    ),
    'checkout' => array(
        'label' => 'Activate Direct-Buy',
    ),
    'checkoutenabled' => array(
        'label' => '{#setting:currentMarketplaceName#} Direct-Buy',
        'help' => 'To use this function, please insert your idealo “Client-ID” and “Client-Password” into the appropriate field of the "<a href="{#setting:idealo.activatedirectbuyconfigurl#}">Login Details</a>" tab.',
    ),
    'oldtokenmigrationpopup' => array(
        'label' => 'Switch to idealo Checkout Merchant Order API v2',
        'help' => 'Since January 01, 2021 magnalister supports the idealo Checkout Merchant Order API v2. The Merchant Order API v1 will be deactivated soon.
<br><br>
Please generate a "Client ID" and a "Client Password" in your idealo Business Account and enter the data in the magnalister idealo configuration under „Login Details" -> "idealo Checkout".
<br><br>
Find instructions for the switchover in the info icon next to "Use idealo Checkout".
',
    ),
    'access.inventorypath' => array(
        'label' => 'Direction to your CSV table',
    ),
    'shippingmethod' => array(
        'label' => 'Direct-buy Shipping Methods',
        'help' => 'Select which shipping method should be used for direct-buy offers.',
        'values' => array(
            'Paketdienst' => 'Parcel Service',
            'Spedition' => 'Haulage',
            'Download' => 'Download',
        ),
    ),
    'twomanhandlingfee' => array(
        'label' => 'Direct-buy - Delivery Costs to the place of installation',
        'help' => 'Für Artikel mit der Lieferart „Spedition“ kann im idealo Direktkauf vom User die zus&aumltzliche Dienstleistung „Lieferung bis zum Aufstellort“ bestellt werden. Die Ware wird nicht, wie bei der &uumlblichen Spedition, bis zur Bordsteinkante, sondern bis zum gewünschten Aufstellort geliefert. Die angegebene Kosten werden mit dem Gesamtpreis verrechnet. Bitte geben Sie die Kosten inkl. MwSt. an. Bei Aussparung dieses Feldes wird diese Option nicht bei idealo Direktkauf angeboten.',
        'hint' => 'Applies only to the shipping method "Haulage"',
    ),
    'disposalfee' => array(
        'label' => 'Direct-buy - Costs for taking old appliances',
        'help' => 'Für Artikel mit der Lieferart „Spedition“ und der Auswahl „Lieferung bis zum Aufstellort“ kann im idealo Direktkauf vom User die zus&aumltzliche Dienstleistung „Altger&aumltemitnahme“ bestellt werden. Das Altger&aumlt wird vom Spediteur mitgenommen und entsorgt. Die angegebenen Kosten werden mit dem Gesamtpreis verrechnet. Bitte geben Sie die Kosten inkl. MwSt. an. Bei Aussparung dieses Feldes wird diese Option nicht bei idealo Direktkauf angeboten.',
        'hint' => 'Applies only to the shipping method "Haulage"',
    ),
    'shippingtime' => array(
        'label' => 'Shipping Time',
        'optional' => array(
            'checkbox' => array(
                'labelNegativ' => 'use from configuration',
            ),
        )
    ),
    'shippingtimetype' => array(
        'values' => array(
            '__ml_lump' => array('title' => 'General (taken from right field)',),
            'immediately' => array('title' => 'immediately',),
            '4-6days' => array('title' => '4-6 days',),
            '1-2days' => array('title' => '1-2 days',),
            '2-3days' => array('title' => '2-3 days',),
            '4weeks' => array('title' => '4 weeks',),
            '24h' => array('title' => '24 houers',),
            '1-3days' => array('title' => '1-3 days',),
            '3days' => array('title' => '3 days',),
            '3-5days' => array('title' => '3-5 days',),
        ),
    ),
    'shippingtimeproductfield' => array(
        'label' => 'Shipping Time (matching)',
    ),
    'orderstatus.cancelreason' => array(
        'label' => 'Cancel Order Reason',
        'help' => 'Please select the default cancellation reason'
    ),
    'orderstatus.cancelcomment' => array(
        'label' => 'Cancel Order Comment',
    ),
    'orderstatus.refund'              => array(
        'label'       => 'Initiate refund with',
        'firstoption' => array('--' => 'Select an option ...'),
        'help'        => 'With the store order status selected here, a merchant can initiate a refund for an order made with "Idealo Checkout Payments". 
<br><br>
Orders made with other payment methods (e.g. Paypal) must still be managed manually and will not be refunded via idealo.',
    ),
    'prepare_title' => array(
        'label' => 'Title',
        'optional' => array(
            'checkbox' => array(
                'labelNegativ' => '{#i18n:ML_PRODUCTPREPARATION_ALWAYS_USE_FROM_WEBSHOP#}',
            ),
        )
    ),
    'prepare_description' => array(
        'label' => 'Description',
        'optional' => array(
            'checkbox' => array(
                'labelNegativ' => '{#i18n:ML_PRODUCTPREPARATION_ALWAYS_USE_FROM_WEBSHOP#}',
            ),
        )
    ),
    'prepare_image' => array(
        'label' => 'Product Images',
        'hint' => 'Maximum 3 images',
        'optional' => array(
            'checkbox' => array(
                'labelNegativ' => '{#i18n:ML_PRODUCTPREPARATION_ALWAYS_USE_FROM_WEBSHOP#}',
            ),
        )
    ),
));