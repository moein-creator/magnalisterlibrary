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

MLI18n::gi()->{'googleshopping_config_orderimport_automatic_method'} = '-- allocate automatically --';
MLI18n::gi()->{'googleshopping_config_orderimport__field__orderimport.paymentmethod__label'} = 'Payment Methods';
MLI18n::gi()->{'googleshopping_config_orderimport__field__orderimport.paymentmethod__help'} = '<p>Payment method that will apply to all orders imported from Google Shopping.
<p>
Additional payment methods can be added to the list via Shopware > Settings > Payment Methods, then activated here.</p>
<p>
This setting is necessary for the invoice and shipping notice, and for editing orders later in the Shop or via ERP.</p>';
MLI18n::gi()->{'googleshopping_config_orderimport__field__orderimport.paymentmethod__hint'} = '';
MLI18n::gi()->{'googleshopping_config_orderimport__field__orderimport.shippingmethod__label'} = 'Shipping Service of the Orders';
MLI18n::gi()->{'googleshopping_config_orderimport__field__orderimport.shippingmethod__help'} = '<p>Google Shopping does not assign any shipping method to imported orders.</p>
<p>Please choose here the available Web Shop shipping methods. The contents of the drop-down menu can be assigned in Shopware > Settings > Shipping Costs.</p>
<p>This setting is important for bills and shipping notes, the subsequent processing of the order inside the shop, and for some ERPs.</p>';
MLI18n::gi()->{'googleshopping_config_orderimport__field__orderimport.shippingmethod__hint'} = '';
MLI18n::gi()->{'googleshopping_config_orderimport__field__orderimport.paymentstatus__label'} = 'Payment Status in Shop';
MLI18n::gi()->{'googleshopping_config_orderimport__field__orderimport.paymentstatus__hint'} = 'Please select which shop system payment status should be set in the order details during the magnalister order import.';
MLI18n::gi()->{'googleshopping_config_orderimport__field__customergroup__help'} = '{#i18n:global_config_orderimport_field_customergroup_help#}';
