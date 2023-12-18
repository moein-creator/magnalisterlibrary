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

MLI18n::gi()->Vendor = 'Distributeur';
MLI18n::gi()->SKU = 'SKU (unité de gestion des stocks)';
MLI18n::gi()->Barcode = 'Code-barre (ISBN, UPC, GTIN usw.)';
MLI18n::gi()->Description = 'Description de l\'article';
MLI18n::gi()->ItemName = 'Titre de l\'article';
MLI18n::gi()->Weight = 'Poid';
MLI18n::gi()->ProductType = 'Type de produit';
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

MLI18n::gi()->set('formfields_config_uploadInvoiceOption_help_erp', '', true);
MLI18n::gi()->set('formfields_config_uploadInvoiceOption_help_webshop', '', true);