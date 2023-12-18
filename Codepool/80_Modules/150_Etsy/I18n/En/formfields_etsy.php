<?php
/**
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
 * $Id$
 *
* (c) 2010 - 2018 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */
// example for overwriting global element
MLI18n::gi()->add('formfields__stocksync.tomarketplace', array('help' => '
<p>Here you can set up if or how magnalister transfers inventory changes in your online shop to Etsy:</p>
<p>1. No synchronization</p>
<p>Your online shop&rsquo;s inventory will not be synchronized with Etsy.</p>
<p>2. Automatic synchronization <strong>with</strong> zero stock (recommended)</p>
<p>Your online shop&rsquo;s inventory is automatically synchronized with Etsy. Products with &lt; 1 item in stock will be inactivated, but automatically reactivated as soon as inventory increases to &gt; 0.</p>
<p><strong>Important note:</strong> Etsy charges fees when reactivating items.</p>
<p>3. Automatic synchronization <strong>without</strong> zero stock</p>
<p>Your inventory will only be synchronized automatically if stock is &gt; 0. Items on Etsy will <strong>not automatically be reactivated</strong> - even when you have them in stock in your online shop. This prevents non-transparent fees.</p>
<p><strong>General Hints:</strong></p>
<ul>
<li>Automatic inventory synchronization takes place every 4 hours via CronJob. The cycle starts daily at midnight.<br /><br /></li>
<li>Item variants: Automatic inventory synchronization of item variants (even if stock is &lt;1) is free of charge on Etsy, as long as one variant of the product stock &gt; 0.<br /><br /></li>
<li>You can reactivate single inactive products by setting your online shop&rsquo;s inventory to &gt; 0 and initiate your product upload via the magnalister plugin again.<br /><br /></li>
<li>The automatic inventory synchronization takes place every 4 hours via CronJob. The cycle starts daily at 0:00 am. The values from the database are checked and transferred, even if the changes were only made in the database by - for example - stock management applications.<br /><br /></li>
<li>In addition, in our "Flat" plan, you can trigger the stock synchronization with your own CronJob in an interval of no more than 15 minutes by calling the following link of your webshop:<br />{#setting:sSyncInventoryUrl#}<br /><br /></li>
<li>CronJobs calls from customers, who are not in the "Flat" plan or who run the calls more frequently than every 15 minutes, are blocked.<br /><br /></li>
<li>You can manually trigger a synchronization by clicking on the corresponding button in the top right-hand corner of the header.<br /><br /></li>
<li>Find more information about Etsy&rsquo;s fees via <a href="https://help.etsy.com/hc/en-us/articles/360000344908">Etsy&rsquo;s Help Center</a></li>
</ul>
'));

MLI18n::gi()->add('formfields_etsy', array(
    'shippingtemplatetitle' => array(
        'label' => 'Shipping template title',
    ),
    'shippingtemplatecountry' => array(
        'label' => 'Origin country',
        'help' => 'Country from which the listing ships',
    ),
    'shippingtemplateprimarycost' => array(
        'label' => 'Primary cost',
        'help' => 'The shipping fee for this item, if shipped alone',
    ),
    'shippingtemplatesecondarycost' => array(
        'label' => 'Secondary cost',
        'help' => 'The shipping fee for this item, if shipped with another item',
    ),
    'shippingtemplatesend' => array(
        'label' => 'Save shipping template',
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
    'whomade' => array(
        'values' => array(
            'i_did' => 'I did',
            'collective' => 'A member of my shop',
            'someone_else' => 'Another company or person',
        ),
    ),
    'whenmade' => array(
        'values' => array(
            'made_to_order' => 'Made to order',
            '2020_2021' => '2020-2021',
            '2010_2019' => '2010-2019',
            '2002_2009' => '2002-2009',
            'before_2002' => 'Before 2002',
            '1990s' => '1990s',
            '1980s' => '1980s',
            '1970s' => '1970s',
            '1960s' => '1960s',
            '1950s' => '1950s',
            '1940s' => '1940s',
            '1930s' => '1930s',
            '1920s' => '1920s',
            '1910s' => '1910s',
            '1900s' => '1900s',
            '1800s' => '1800s',
            '1700s' => '1700s',
            'before_1700' => 'Before 1700'
        ),
    ),
    'issupply' => array(
        'values' => array(
            'false' => 'A finished product',
            'true' => 'A supply or tool to make things',
        ),
    ),
    'access.username' => array(
        'label' => 'Etsy Username',
    ),
    'access.password' => array(
        'label' => 'Etsy Password',
    ),
    'access.token' => array(
        'label' => 'Etsy Token',
    ),
    'shop.language' => array(
        'label' => 'Etsy Language',
        'values' => array(
            'de' => 'Deutsch',
            'en' => 'English',
            'es' => 'Español',
            'fr' => 'Français',
            'it' => 'Italiano',
            'ja' => '日本語',
            'nl' => 'Nederlands',
            'pl' => 'Polski',
            'pt' => 'Português',
            'ru' => 'Русский',
        ),
    ),
    'shop.currency' => array(
        'label' => 'Etsy Currency',
        'values' => array(
            'EUR' => '€ Euro',
            'USD' => '$ United States Dollar',
            'CAD' => '$ Canadian Dollar',
            'GBP' => '£ British Pound',
            'AUD' => '$ Australian Dollar',
            'DDK' => 'kr Danish Krone',
            'HKD' => '$ Hong Kong Dollar',
            'NZD' => '$ New Zealand Dollar',
            'NOK' => 'kr Norwegian Krone',
            'SGD' => '$ Singapore Dollar',
            'SEK' => 'kr Swedish Krona',
            'CHF' => 'Swiss Franc',
            'TWD' => 'NT$ Taiwan New Dollar',
        ),
    ),
    'prepare.imagesize' => array(
        'label' => 'Image size',
    ),
    'prepare.whomade' => array(
        'label' => 'Who made it?',
    ),
    'prepare.whenmade' => array(
        'label' => 'When did you make it?',
    ),
    'prepare.issupply' => array(
        'label' => 'What is it?',
    ),
    'fixed.price' => array(
        'label' => 'Price',
        'help' => 'Please enter a price markup or markdown, either in percentage or fixed amount. Use a minus sign (-) before the amount to denote markdown.'
    ),
    'fixed.price.addkind' => array(
        'label' => '',
    ),
    'fixed.price.factor' => array(
        'label' => '',
    ),
    'fixed.price.signal' => array(
        'label' => 'Decimal Amount',
        'hint' => 'Decimal Amount',
        'help' => 'This textfield shows the decimal value that will appear in the item price on Etsy.'
    ),
    'prepare.language' => array(
        'label' => 'Language',
    ),
    'shippingtemplate' => array(
        'label' => 'Default shipping template',
        'hint' => '<button id="shippingtemplateajax" class="mlbtn action add-matching" value="Secondary_color" style="display: inline-block;">+</button>',
    ),
    'prepare_title' => array(
        'label' => 'Title',
        'optional' => array(
            'checkbox' => array(
                'labelNegativ' => 'Always use product title from web-shop',
            )
        ),
    ),
    'prepare_description' => array(
        'label' => 'Description',
        'help' => 'Maximum number of characters is 63000.',
        'optional' => array(
            'checkbox' => array(
                'labelNegativ' => 'Always use product description from web-shop',
            )
        ),
    ),
    'prepare_image' => array(
        'label' => 'Product Images',
        'hint' => 'Maximum 10 images',
        'help' => 'A maximum 10 images can be set.<br/>The maximum allowed image size is 3000 x 3000 px.',
    ),
    'category' => array(
        'label' => 'Category',
    ),
    'prepare_price' => array(
        'label' => 'Price',
        'help' => 'Minimum item price on Etsy is 0.17£',
    ),
    'prepare_quantity' => array(
        'label' => 'Quantity',
        'help' => 'Maximum item on Etsy is 999',
    ),
    'orderstatus.shipping' => array(
        'label' => 'Shipping provider'
    )
));
