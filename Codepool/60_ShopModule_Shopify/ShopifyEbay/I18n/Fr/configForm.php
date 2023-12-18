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

MLI18n::gi()->{'ebay_config_orderimport__field__updateablepaymentstatus__label'} = 'Autoriser les changements de statut de paiement si';
MLI18n::gi()->{'ebay_config_orderimport__field__updateablepaymentstatus__help'} = 'Vous pouvez avec cette fonction synchroniser le changement de statut des commandes après paiements sur eBay. <br>
Normalement, les changements de statut de commande n\'ont pas d’incidence sur le statut de paiement sur eBay. <br>
<br>
Si vous ne souhaitez aucun changement de statut au paiement de la commande, désactivez la case à droite de la fenêtre de choix.<br>
<br>
<strong>Remarque :</strong> Le statut des commandes combinées ne sera modifié, que si toutes les parties ont été payées.';
MLI18n::gi()->{'ebay_config_orderimport__field__paidstatus__label'} = 'Statut de la commande/paiement pour les commandes eBay payantes';
MLI18n::gi()->{'ebay_config_orderimport__field__paidstatus__help'} = 'Les commandes sur eBay sont en partie réglées par les acheteurs avec un délai. 
<br><br>
Pour séparer les commandes non payées des commandes payées, vous pouvez choisir votre propre statut de commande pour la boutique en ligne et le statut de paiement pour les commandes payées sur eBay 
<br><br>
Quand les commandes qui sont importées par eBay n’ont pas encore été réglées, l’Etat de la commande qui s’applique est celui que vous avez défini en haut sous «  Importation des commandes » > « Statut de la commande en boutique ».
<br><br>
Si vous avez activé en haut "Importer uniquement les commandes marquées "payées"", c’est également l’ « état de commande dans la boutique en ligne » qui est utilisé. Cette fonction apparaît alors comme grisée.';
MLI18n::gi()->{'ebay_config_orderimport__field__orderstatus.paid__label'} = 'Statut de la commande';
MLI18n::gi()->{'ebay_config_orderimport__field__orderstatus.paid__help'} = '';
MLI18n::gi()->{'ebay_config_orderimport__field__paymentstatus.paid__label'} = 'Statut des paiement';
MLI18n::gi()->{'ebay_config_orderimport__field__paymentstatus.paid__help'} = '';
MLI18n::gi()->{'ebay_config_orderimport__field__updateable.paymentstatus__label'} = '';
MLI18n::gi()->{'ebay_config_orderimport__field__updateable.paymentstatus__help'} = '';
MLI18n::gi()->{'ebay_config_orderimport__field__update.paymentstatus__label'} = 'Changement de statut de paiement activé';
MLI18n::gi()->{'ebay_config_orderimport__field__orderimport.paymentmethod__label'} = 'Mode de paiement de la commande';
MLI18n::gi()->{'ebay_config_orderimport__field__orderimport.paymentmethod__help'} = 'Mode de paiement qui sera attribué à toutes les commandes effectuées sur Rakuten lors de l\'importation des commandes. <br><br>
Vous pouvez définir d\'autres modes de paiements qui s\'afficheront dans le menu déroulant en vous rendant sur "Shopware" > "moyens de paiement".<br><br>
Ce réglage est important pour l\'impression des bons de livraison et des factures, mais aussi pour le traitement ultérieur des commandes dans votre boutique ainsi que dans votre gestion des marchandises.';
MLI18n::gi()->{'ebay_config_orderimport__field__orderimport.paymentmethod__hint'} = '';
MLI18n::gi()->{'ebay_config_orderimport__field__orderimport.shippingmethod__label'} = 'Mode de livraison de la commande';
MLI18n::gi()->{'ebay_config_orderimport__field__orderimport.shippingmethod__help'} = '<p> Mode de paiement qui sera attribué à toutes les commandes effectuées sur eBay lors de l\'importation des commandes. <br>
Valeur par défaut : "Attribution automatique"</p>
<p>
Si l\'"Attribution automatique" est sélectionnée magnalister reprend le mode de paiement que le client a choisit lors de sa commande sur eBay.
Le mode de paiement sera alors ajouté à votre boutique dans "Shopware" > "Paramètres" > "Frais de port". </p>
<p>Vous pouvez définir d\'autres modes de paiements qui s\'afficheront dans le menu déroulant en vous rendant sur "Shopware" > "Frais de port".</p>
<p>Ce réglage est important pour l\'impression des bons de livraison et des factures, mais aussi pour le traitement ultérieur des commandes dans votre boutique ainsi que dans votre gestion des marchandises.</p>
';
MLI18n::gi()->{'ebay_config_orderimport__field__orderimport.shippingmethod__hint'} = '';
MLI18n::gi()->{'ebay_config_orderimport__field__orderimport.paymentstatus__label'} = 'Statut du paiement dans votre boutique';
MLI18n::gi()->{'ebay_config_orderimport__field__orderimport.paymentstatus__help'} = 'Sélectionnez ici le statut de paiement qui sera automatiquement attribué aux commandes lors de l’importation des commandes depuis la place de marché.';
MLI18n::gi()->{'ebay_config_orderimport__field__orderimport.paymentstatus__hint'} = '';
MLI18n::gi()->{'ebay_config_orderimport__field__customergroup__help'} = '{#i18n:global_config_orderimport_field_customergroup_help#}';
