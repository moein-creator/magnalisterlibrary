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

MLI18n::gi()->{'otto_config_orderimport__field__orderimport.paymentmethod__label'} = 'Payment Methods';
MLI18n::gi()->{'otto_config_orderimport__field__orderimport.paymentmethod__help'} = '<p>Payment method that will apply to all orders imported from DaWanda.
<p>
Additional payment methods can be added to the list via Shopware > Settings > Payment Methods, then activated here.</p>
<p>
This setting is necessary for the invoice and shipping notice, and for editing orders later in the Shop or via ERP.</p>';
MLI18n::gi()->{'otto_config_orderimport__field__orderimport.paymentmethod__hint'} = '';
MLI18n::gi()->{'otto_config_orderimport__field__orderimport.shippingmethod__label'} = 'Shipping Service of the Orders';
MLI18n::gi()->{'otto_config_orderimport__field__orderimport.shippingmethod__help'} = '<p>OTTO does not assign any shipping method to imported orders.</p>
<p>Please choose here the available Web Shop shipping methods. The contents of the drop-down menu can be assigned in Shopware > Settings > Shipping Costs.</p>
<p>This setting is important for bills and shipping notes, the subsequent processing of the order inside the shop, and for some ERPs.</p>';
MLI18n::gi()->{'otto_config_orderimport__field__orderimport.shippingmethod__hint'} = '';
MLI18n::gi()->{'otto_config_orderimport__field__orderimport.paymentstatus__label'} = 'Payment Status in Shop';
MLI18n::gi()->{'otto_config_orderimport__field__orderimport.paymentstatus__hint'} = '';
MLI18n::gi()->{'otto_config_orderimport__field__orderimport.paymentstatus__help'} = '<p>Otto does not assign any shipping method to imported orders.</p>
<p>Please choose here the available Web Shop shipping methods. The contents of the drop-down menu can be assigned in Shopware > Settings > Shipping Costs.</p>
<p>This setting is important for bills and shipping notes, the subsequent processing of the order inside the shop, and for some ERPs.</p>';
MLI18n::gi()->{'otto_config_orderimport__field__customergroup__help'} = '{#i18n:global_config_orderimport_field_customergroup_help#}';
MLI18n::gi()->{'otto_config_free_text_attributes_opt_group'} = 'Free text fields';
MLI18n::gi()->{'formfields__priceoptions__help'} = '<p>Avec cette fonction, vous pouvez transférer des prix divergents sur le marché et les faire synchroniser automatiquement.<br />
<br />
Pour ce faire, sélectionnez un groupe clients de votre boutique dans le menu déroulant de droite.<br />
<br />
Si vous ne saisissez pas de prix divergent dans le nouveau groupe de clients, le prix par défaut de votre boutique sera automatiquement transmis à eBay. Ainsi vous pouvez facilement appliquer un prix adapté pour un nombre limité d’articles Les autres paramètres relatifs au prix seront également appliqués au prix de vente.<br />
<br />
<b>Exemple :</b></p>
<ul>
<li>Ajoutez un groupe client dans votre boutique, par exemple "Clients eBay"</li>
<li>Sur les fiches produits dans votre boutique, saisissez le prix souhaité</li>
</ul>
<p>Le mode de rabais des groupes de clients peut également être utilisé. Vous pouvez y enregistrer une remise (en pourcentage). À condition que le mode de remise soit activé dans l\'article Shopware, le prix soldé est transmis via magnalister à la place de marché. Il est important que le prix du marché ne soit pas affiché comme prix d\'exercice.</p>';
