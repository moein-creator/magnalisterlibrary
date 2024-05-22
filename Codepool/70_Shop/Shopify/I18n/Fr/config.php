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
 * (c) 2010 - 2023 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

MLI18n::gi()->Vendor = 'Distributeur';
MLI18n::gi()->SKU = 'SKU (unité de gestion des stocks)';
MLI18n::gi()->Barcode = 'Code-barre (ISBN, UPC, GTIN usw.)';
MLI18n::gi()->Description = 'Description de l\'article';
MLI18n::gi()->ItemName = 'Titre de l\'article';
MLI18n::gi()->Weight = 'Poid';
MLI18n::gi()->ProductType = 'Type de produit';
MLI18n::gi()->Shopify_Carrier_Other = 'Other';
MLI18n::gi()->{'form_config_orderimport_exchangerate_update_help'} = '<strong>Généralités:</strong>
<p>
Si la monnaie utilisée dans votre boutique en ligne diffère de celle en vigueur sur la place de marché, magnalister calcule le prix de l’article à l’aide d’un convertisseur de monnaie automatique.
</p>

<strong>Attention:</strong>
<p>
Pour ce faire, le Plug-in recourt au taux de change du convertisseur de devises externe « alphavantage ».
Important : nous n’assumons aucune responsabilité pour l’exactitude de la conversion. 
</p>
<p>
Les fonctions suivantes déclenchent une mise à jour du taux de change de la monnaie:
<ul>
<li>Importation des commandes</li>
<li>Préparation des articles</li>
<li>Chargement des articles</li>
<li>Synchronisation des stocks et des prix</li>
</ul>
</p>';
MLI18n::gi()->{'form_config_orderimport_exchangerate_update_alert'} = '<b>Attention!</b> <br>
En activant cette fonction, le taux de change actuel défini par "alphavantage" sera appliqué à vos articles. Les prix dans votre boutique en ligne seront également mis à jour.<br>
<br>
les fonctions suivantes provoquent une mise à jour des prix:
<ul>
<li>Importation des commandes</li>
<li>Préparer les articles</li>
<li>Charger les articles</li>
<li>Synchroniser les prix et les stocks</li>
<br>
<b>Avertissement :</b> RedGecko GmbH n\'assume aucune responsabilité pour l\'exactitude du taux de change. Veuillez vérifier en contrôlant les prix de vos articles sur la place de marché.
';
MLI18n::gi()->OrderStatus_Open = 'Non traité';
MLI18n::gi()->OrderStatus_Fulfilled = 'Traité';
MLI18n::gi()->OrderStatus_Cancelled = 'Annulé';
MLI18n::gi()->FinancialStatus_Empty = 'Let magnalister determine whether the order is "paid" or "pending".';
MLI18n::gi()->FinancialStatus_Pending = 'En cours';
MLI18n::gi()->FinancialStatus_Authorized = 'Autorisé';
MLI18n::gi()->FinancialStatus_PartiallyPaid = 'Partiellement payé';
MLI18n::gi()->FinancialStatus_Paid = 'Payé';
MLI18n::gi()->FinancialStatus_PartiallyRefunded = 'Partiellement remboursé';
MLI18n::gi()->FinancialStatus_Refunded = 'Remboursé';
MLI18n::gi()->FinancialStatus_Voided = 'Annulé';
MLI18n::gi()->CustomerGroupSettingNotSupported = 'Cette option n\'est pas prise en charge par Shopify.';

MLI18n::gi()->set('formfields_config_uploadInvoiceOption_help_erp', '', true);
MLI18n::gi()->set('formfields_config_uploadInvoiceOption_help_webshop', '', true);

//shopify collection vat matching
MLI18n::gi()->set('orderimport_shopify_vatmatching_label', 'Matching de la TVA');
MLI18n::gi()->set('orderimport_shopifyvatmatching_help', '
<p>Shopify ne permet pas aux applications tierces d\'accéder aux paramètres fiscaux. Vous pouvez donc effectuer ces réglages directement dans magnalister afin d\'importer les commandes avec le taux de TVA correspondant.
</p>
<p>Pour cela, il suffit de faire correspondre une "Shopify Collection" avec le pays de destination et le taux de TVA souhaités. Les taux de TVA sont enregistrés dans les détails de la commande lorsque la commande remonte dans votre boutique Shopify.</p>
<b>Remarques : </b>
<ul>
<li>Si plusieurs Collections Shopify contenant des taux de TVA différents sont attribuées à des produits, seul le taux de TVA qui a été apparié en premier sera appliqué lors de l\'importation de la commande.</li>
<li>Si aucun taux de TVA défini dans la configuration ne correspond au produit dans la commande, le taux de TVA par défaut de l\'API Shopify sera appliqué (exemple : pour les commandes dont le pays d’expédition est la France : 20 %)</li>
<li>Si vous sélectionnez l\'option "{#i18n:shopify_global_configuration_vat_matching_option_all#}" dans le menu déroulant " Collection Shopify ", vous pouvez attribuer un taux de TVA uniforme à tous les produits, indépendamment de la collection attribuée par Shopify.</li>
</ul>
');


MLI18n::gi()->{'orderimport_shopify_vatmatching_collection_label'} = 'Collection Shopify';

MLI18n::gi()->{'orderimport_shopify_vatmatching_shipping_country_label'} = 'Pays de destination de la commande';
MLI18n::gi()->{'orderimport_shopifyvatmatching_vatrate_label'} = '% de TVA';
MLI18n::gi()->{'shopify_global_configuration_vat_matching_option_all'} = 'Toutes les collections';
MLI18n::gi()->{'shopify_global_configuration_vat_matching_option_all_countries'} = 'Toutes les collections';