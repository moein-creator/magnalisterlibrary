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

MLI18n::gi()->{'WooCommerce_Configuration_ShippingMethod_Available_Info'} = '<p> Mode de livraison qui sera attribué à toutes les commandes effectuées sur {#setting:currentMarketplaceName#} lors de l\'importation des commandes. <br>
Valeur par défaut : "Attribution automatique"</p>
<p>
Si l\'"Attribution automatique" est sélectionnée magnalister reprend le mode de livraison que le client a choisit lors de sa commande sur {#setting:currentMarketplaceName#}. </p>
<p>Vous pouvez définir d\'autres modes de livraison qui s\'afficheront dans le menu déroulant en vous rendant sur "WooCommerce" > "Settings" > "Shipping" > "Zone" > "Shipping methods".</p>
<p>Ce réglage est important pour l\'impression des bons de livraison et des factures, mais aussi pour le traitement ultérieur des commandes dans votre boutique ainsi que dans votre gestion des marchandises.</p>';

MLI18n::gi()->{'WooCommerce_Configuration_ShippingMethod_NotAvailable_Info'} = 'WooCommerce_Configuration_ShippingMethod_NotAvailable_Info';

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
</p>
<p>
Le taux de change est également mis à jour automatiquement toutes les 24 heures. Le dernier taux de change mis à jour et la date de sa dernière mise à jour s’affiche dans ce champ.
</p>
';
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
<b>Avertissement :</b> RedGecko GmbH n\'assume aucune responsabilité pour l\'exactitude du taux de change. Veuillez vérifier en contrôlant les prix de vos articles sur la place de marché.';

MLI18n::gi()->{'WooCommerce_Configuration_PaymentMethod_NotAvailable_Info'} = 'Mode de paiement qui sera attribué à toutes les commandes effectuées sur Rakuten lors de l\'importation des commandes. <br><br>
Vous pouvez définir d\'autres modes de paiements qui s\'afficheront dans le menu déroulant en vous rendant sur "WooCommerce" > "Settings" > "Payments".<br><br>
Ce réglage est important pour l\'impression des bons de livraison et des factures, mais aussi pour le traitement ultérieur des commandes dans votre boutique ainsi que dans votre gestion des marchandises.';
MLI18n::gi()->{'WooCommerce_Configuration_PaymentMethod_Available_Info'} = 'Mode de paiement qui sera attribué à toutes les commandes {#setting:currentMarketplaceName#} lors de l\'importation des commandes. <br><br>
Vous pouvez définir d\'autres modes de paiements, qui s\'afficheront dans le menu déroulant en vous rendant sur "WooCommerce" > "Settings" > "Payments".<br><br>
Ce réglage est important pour l\'impression des bons de livraison et des factures, mais aussi pour le traitement ultérieur des commandes dans votre boutique ainsi que dans votre gestion des marchandises.';

MLI18n::gi()->set('formfields_config_uploadInvoiceOption_help_webshop', '', true);
MLI18n::gi()->{'woocommerce_config_trackingkey_help_warning'} = '<b>Attention</b> : magnalister transmet aussi le nom du transporteur des plugins tiers susmentionnés aux places de marché.';
MLI18n::gi()->orderimport_trackingkey = array(
    'label' => 'Numéro de suivi',
    'help'  => '<p>Vous pouvez choisir parmi les options suivantes pour soumettre le numéro de suivi d\'une commande de la place de marché importée via magnalister à la place de marché/à l\'acheteur via la synchronisation de l\'état de la commande :
</p>
<ol>
<li><p>
<h5>Créez un champ personnalisé dans WooCommerce et sélectionnez-le dans magnalister</h5>
</p><p>
Les champs personnalisés peuvent être créés dans l\'administration de WooCommerce sous "Commandes" -> [Commande] en bas de page. Nommez le champ personnalisé (par exemple "Numéro de suivi") dans la colonne "Nom" et entrez le numéro de suivi de la commande dans la colonne " Terme ".
</p><p>

Ensuite, retournez dans la configuration de magnalister et sélectionnez le champ créé dans les détails de la commande (suivant l\'exemple ci-dessus avec le nom "Numéro de suivi") dans la liste déroulante à droite sous "Champs personnalisés WooCommerce".

</p>
</li><li><p>
<h5>magnalister ajoute un "champ personnalisé" dans les détails de la commande</h5>
</p><p>
Si vous sélectionnez cette option, magnalister ajoutera automatiquement un champ personnalisé sous "Commandes" -> [Commande] -> "Champs personnalisés" lors de l\'importation de la commande.
</p><p>
Veuillez y entrer le numéro de suivi avant que vous changiez le statut de la commande pour confirmer l\'expédition.
</p>
</li><li><p>
<h5>magnalister accède au champ du numéro de suivi d\'un plugins tiers</h5>
</p><p>
magnalister peut accéder aux champs de clé de suivi de certains plugins tiers de WooCommerce. Il s\'agit notamment des plugins suivants :
</p><p>
Plugin germanisé
</p><p>
Pour transférer la clé de suivi du plugin germanisé vers la place de marché via magnalister, sélectionnez "Plugin germanisé : utiliser la clé de suivi à partir de là" dans la liste déroulante à droite.
</p><p>
Lorsque vous utilisez le plugin germanisé, saisissez la clé de suivi dans les détails de la commande sous "Envois" -> "Numéro d\'envoi".
</p><p>
Plugin avancé de suivi des expéditions
</p><p>
Pour transmettre le numéro de suivi de l’extension “Suivi avancé des envois pour WooCommerce” sur la place de marché via magnalister, sélectionnez l\'option "Suivi avancé des envois pour WooCommerce  utiliser le numéro de suivi de “Suivi avancé des envois pour WooCommerce” dans la liste déroulante de droite.
</p><p>
Si vous utilisez l\'Advanced Shipment Tracking Plugin, saisissez la clé de suivi dans les détails de la commande sous "Shipment Tracking" -> "Shipment Code".
</p><p>
{#i18n:woocommerce_config_trackingkey_help_warning#}
</p>
</li>
</ol>
',
);
MLI18n::gi()->{'woocommerce_config_trackingkey_option_group_customfields'} = 'Champs personnalisés Wordpress';
MLI18n::gi()->{'woocommerce_config_trackingkey_option_group_additional_option'} = 'Options supplémentaires';
MLI18n::gi()->{'woocommerce_config_trackingkey_option_orderfreetextfield_option'} = 'magnalister ajoute un champ personnalisé dans les détails de la commande';
MLI18n::gi()->{'woocommerce_config_trackingkey_option_plugin_ast'} = 'Suivi avancé des envois pour WooCommerce : utiliser le numéro de suivi de l’extension “Suivi avancé des envois pour WooCommerce”';
MLI18n::gi()->{'woocommerce_config_trackingkey_option_plugin_germanized'} = 'Plugin Germanized : utiliser le numéro de suivi de Germanized';

MLI18n::gi()->{'marketplace_config_carrier_option_matching_option_plugin'} = 'Match marketplace supported carrier with carriers defined in "{#pluginname#}"-plugin ';
MLI18n::gi()->{'marketplace_config_carrier_matching_title_shop_carrier_plugin'} = 'Shipping carrier defined in "{#pluginname#}"-plugin';
MLI18n::gi()->{'amazon_config_carrier_option_matching_option_carrier_plugin'} = 'Match shipping carriers suggested by Amazon with carriers defined in "{#pluginname#}"-plugin';
MLI18n::gi()->{'amazon_config_carrier_option_matching_option_shipmethod_plugin'} = 'Match shipping method with entries from "{#pluginname#}"-plugin';