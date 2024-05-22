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

MLI18n::gi()->{'check24_config_orderimport__field__orderimport.paymentmethod__label'} = 'Mode de paiement de la commande';
MLI18n::gi()->{'check24_config_orderimport__field__orderimport.paymentmethod__help'} = 'Mode de paiement qui sera attribué à toutes les commandes Check24 lors de l\'importation des commandes. <br><br>
Vous pouvez définir d\'autres modes de paiements qui s\'afficheront dans le menu déroulant en vous rendant sur "Shopware" > "moyens de paiement".<br><br>
Ce réglage est important pour l\'impression des bons de livraison et des factures, mais aussi pour le traitement ultérieur des commandes dans votre boutique ainsi que dans votre gestion des marchandises.';
MLI18n::gi()->{'check24_config_orderimport__field__orderimport.paymentmethod__hint'} = '';
MLI18n::gi()->{'check24_config_orderimport__field__orderimport.shippingmethod__label'} = 'Mode de livraison de la commande';
MLI18n::gi()->{'check24_config_orderimport__field__orderimport.shippingmethod__help'} = '<p>Lors des importations des commandes, Check24 ne transmet pas d\'information sur le mode d\'expédition. </p>
<p>Veuillez sélectionner dans le menu déroulant, les modes de livraison de votre boutique. Vous pouvez définir les modes de livraison de votre boutique en vous rendant sur "Shopware" > "paramètres" > "Frais de port". </p>
<p>Ce réglage est important pour l\'impression des bons de livraison et des factures, mais aussi pour le traitement ultérieur des commandes dans votre boutique ainsi que dans votre gestion des marchandises.</p> ';
MLI18n::gi()->{'check24_config_orderimport__field__orderimport.shippingmethod__hint'} = '';
MLI18n::gi()->{'check24_config_orderimport__field__paymentstatus__label'} = 'Statut du paiement dans votre boutique';
MLI18n::gi()->{'check24_config_orderimport__field__paymentstatus__help'} = 'Sélectionnez ici le statut de paiement qui sera automatiquement attribué aux commandes lors de l’importation des commandes depuis la place de marché.';
MLI18n::gi()->{'check24_config_orderimport__field__paymentstatus__hint'} = '';
MLI18n::gi()->{'check24_config_orderimport__field__customergroup__help'} = '{#i18n:global_config_orderimport_field_customergroup_help#}';
MLI18n::gi()->{'check24_config_price__field__priceoptions__help'} = '<p>Avec cette fonction, vous pouvez transférer des prix divergents sur le marché et les faire synchroniser automatiquement.<br />
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
