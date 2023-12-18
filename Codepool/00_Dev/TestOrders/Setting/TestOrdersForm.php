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
$sMarketplaceOrderId = 'TestOrder-'.uniqid();
$sMarketplaceOrderNumber = 'TestOrder-'.uniqid();
MLSetting::gi()->main_tools_testorders = array(
    'details'     => array(
        'legend' => array('i18n' => 'Marketplace'),
        'fields' => array(
            array(
                'name' => 'MarketPlace',
                'type' => 'select',
                'i18n' => array('label' => 'Marketplace', 'hint' => ''),
            ),
            array(
                'name'   => 'MPSpecific',
                'i18n'   => array('label' => 'MPSpecific', 'hint' => ''),
                'values' => array(
                    'amazon'  => array(
                        'FulfillmentChannel'  => 'AFN',
                        'ShipServiceLevel_'   => 'Info for MFN-Prime you can remove underline and use NextDay or SameDay as value',
                        'DeliveryPackstation' => array(
                            'PackstationID'         => '111',
                            'PackstationCustomerID' => '1111111'
                        )
                    ),
                    'ebay'    => array(
                        'BuyerUsername'         => 'test_user',
                        'ExtendedOrderID'       => '12-12345-1234X',
                        'EbayRefundUrl'         => 'https://www.ebay.de/sh/ord/details?orderid=12-12345-12345',
                        'eBaySalesRecordNumber' => '1111X'
                    ),
                    'dawanda' => array(
                        'BuyerUsername' => 'BuyerUsername-'.uniqid()
                    ),
                    'default' => array(
                        'MOrderID' => $sMarketplaceOrderId
                    ),
                    'metro'   => array(
                        'MetroOrderId'     => $sMarketplaceOrderId,
                        'MetroOrderNumber' => $sMarketplaceOrderNumber,
                    ),
                    'otto'    => array(
                        'OttoOrderId'     => $sMarketplaceOrderId,
                        'OttoOrderNumber' => $sMarketplaceOrderNumber,
                    ),
                )
            ),
            array(
                'name'   => 'MPSpecificInfo',
                'type'   => 'ajax',
                'i18n'   => array('label' => 'MPSpecific Info', 'hint' => ''),
                'values' => array(
                    'amazon'  => '
                        <table class="datagrid">
                            <thead>
                                <tr><th colspan="2">FulfillmentChannel</th></tr>
                            </thead>
                            <tbody>
                                <tr><td>MFN</td><td>order is payed</td></tr>
                                <tr><td>AFN</td><td>order is payed and shipped</td></tr>
                            </tbody>
                        </table>
                        <table class="datagrid">
                            <thead>
                                <tr><th>DHL extra information, you can add it into specific data</th></tr>
                            </thead>
                            <tbody>
                                <tr><td>
                                    &nbsp;&nbsp;&nbsp;&nbsp;"DeliveryPackstation": {<br />
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"PackstationID": "111",<br />
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"PackstationCustomerID": "1111111"<br />
                                    &nbsp;&nbsp;&nbsp;&nbsp;}
                                </td></tr>
                            </tbody>
                        </table>
                    ',
                    'ebay'    =>

                        '<p>If a new order-id and there is a not finalized existing order with same customer, the existing will be merged with new order.</p>'.
                        '<p>To update an order disable Product or remove product part from your json and use same MOrderID</p>'.
                        '<p>If Totals[Type=Payment] have "Complete":true, this order is already payed.</p>
                    '.'<p>you use these option for ebay plus cases in MPSpecific <br />"eBayPlus" : "ML_EBAY_PLUS_ORDER"<br />
                     "ML_TEXT_WARNING":"ML_EBAY_PLUS_ORDER_WRONG_SHIPPING"</p>'
                    ,
                    'dawanda' => 'Depend on dawanda-setting, "BuyerUsername" will identify customer.',
                    'default' => 'No specific info',
                ),
            ),
        )
    ),
    'AddressSets' => array(
        'legend' => array('i18n' => 'AddressSets'),
        'fields' => array(
            array(
                'name'  => 'AddressSets',
                'type'  => 'addresssets',
                'i18n'  => array('label' => 'AddressSets', 'hint' => ''),
                'value' => array(
                    'Gender'        => false,
                    'Firstname'     => 'Hans',
                    'Lastname'      => 'Mustermann',
                    'Company'       => false,
                    'StreetAddress' => 'Teststrasse 43',
                    'Street'        => 'Teststrasse',
                    'Housenumber'   => '43',
                    'Postcode'      => '1234',
                    'City'          => 'Teststadt',
                    'Suburb'        => false,
                    'CountryCode'   => 'DE',
                    'Phone'         => '5678 901234',
                    'EMail'         => 'test@example.com',
                    'DayOfBirth'    => false,
                    'DateAdded'     => date('Y-m-d H:i:s'),
                    'LastModified'  => date('Y-m-d H:i:s'),
                )
            ),
        )
    ),
    'Order'       => array(
        'legend' => array('i18n' => 'Order'),
        'fields' => array(
            array(
                'name'  => 'Order',
                'type'  => 'text',
                'i18n'  => array('label' => 'Order', 'hint' => ''),
                'value' => array(
                    'Currency'      => 'EUR',
                    'DatePurchased' => date('Y-m-d H:i:s'),
                    'ImportDate'    => date('Y-m-d H:i:s'),
                    'Comments'      => '',
                )
            ),
            array(
                'name'  => 'Totals',
                'i18n'  => array('label' => 'Totals', 'hint' => ''),
                'value' => array(
                    array(
                        'Type'  => 'Shipping',
                        'Code'  => 'Test',
                        'Value' => (float)rand(1, 10),
                        'Tax'   => false
                    ),
                    array(
                        'Type'  => 'Payment',
                        'Value' => 0,
                        'Tax'   => false
                    )
                )
            ),
            array(
                'name'  => 'Products',
                'i18n'  => array('label' => 'Products', 'hint' => ''),
                'value' => array(
                    array(
                        'SKU'        => 'Test-'.uniqid(),
                        'ItemTitle'  => 'Testarticle',
                        'Quantity'   => rand(1, 3),
                        'Price'      => (float)rand(1, 100),
                        'Tax'        => false,
                        'ForceMPTax' => false,
                    )
                )
            ),
        )
    ),
    'BaseData'    => array(
        'legend' => array('i18n' => 'Whole Json Data To Import Orders (when this part is enabled , other part values are ignored)'),
        'fields' => array(
            array(
                'optional' => array(),
                'name'     => 'CompleteJsonData',
                'i18n'     => array('label' => 'Complete Json Data', 'hint' => 'You can put complete json data here and import'),
                'value'    => array(
                    array()
                )
            ),
        )
    )
);
