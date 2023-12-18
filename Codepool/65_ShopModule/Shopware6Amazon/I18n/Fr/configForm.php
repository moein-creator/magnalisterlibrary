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

MLI18n::gi()->{'amazon_config_orderimport__field__orderimport.paymentmethod__label'} = 'Mode de paiement de la commande';
MLI18n::gi()->{'amazon_config_orderimport__field__orderimport.paymentmethod__help'} = 'Mode de paiement qui sera attribué à toutes les commandes Amazon lors de l\'importation des commandes. <br><br>
Vous pouvez définir d\'autres modes de paiements, qui s\'afficheront dans le menu déroulant en vous rendant sur "Shopware" > "moyens de paiement".<br><br>
Ce réglage est important pour l\'impression des bons de livraison et des factures, mais aussi pour le traitement ultérieur des commandes dans votre boutique ainsi que dans votre gestion des marchandises.';
MLI18n::gi()->{'amazon_config_orderimport__field__orderimport.paymentmethod__hint'} = '';
MLI18n::gi()->{'amazon_config_orderimport__field__orderimport.shippingmethod__label'} = 'Mode de livraison de la commande';
MLI18n::gi()->{'amazon_config_orderimport__field__orderimport.shippingmethod__help'} = '<b>Mode d’envoi des commandes</b><br />
<br />
Choisissez ici le mode d’envoi qui sera associé uniformément à toutes les commandes.<br />
<br />
Voici les différentes options qui s’offrent à vous :<br />
<ol>
<li><b>Modes d’envoi pris en charge par la place de marché</b><br />
<br />
Choisissez un mode d’envoi dans la liste du menu déroulant. Seules les options prises en charge par la place de marché s’affichent.<br />
<br />
</li>
<li><b>Modes d’envoi sur le gestionnaire des champs libres de la boutique en ligne</b><br />
<br />
Choisissez un mode d’envoi à partir d’un champ libre de la boutique en ligne. <br />
<br />
</li>
<li><b>Sélection automatique</b><br />
<br />
Pour chaque pays de destination, magnalister sélectionne automatiquement le mode d’envoi placé en tête de liste dans le module de calcul des frais de port pour la boutique en ligne concernée. 
</li>
</ol>';
MLI18n::gi()->{'amazon_config_orderimport__field__orderimport.shippingmethod__hint'} = '';
MLI18n::gi()->{'amazon_config_orderimport__field__orderimport.paymentstatus__label'} = 'Statut de la commande en boutique';
MLI18n::gi()->{'amazon_config_orderimport__field__orderimport.paymentstatus__help'} = '<p>Lors des importations des commandes, Amazon ne transmet pas d\'information sur le mode d\'expédition. </p>
<p>Veuillez sélectionner dans le menu déroulant, les modes de livraison de votre boutique. Vous pouvez définir les modes de livraison de votre boutique en vous rendant sur "Shopware" > "paramètres" > "Frais de port". </p>
<p>Ce réglage est important pour l\'impression des bons de livraison et des factures, mais aussi pour le traitement ultérieur des commandes dans votre boutique ainsi que dans votre gestion des marchandises.</p>';
MLI18n::gi()->{'amazon_config_orderimport__field__orderimport.paymentstatus__hint'} = '';
MLI18n::gi()->{'amazon_config_orderimport__field__orderimport.fbashippingmethod__label'} = 'Mode de livraison des commandes FBA';
MLI18n::gi()->{'amazon_config_orderimport__field__orderimport.fbashippingmethod__help'} = '<b>Mode d’envoi des commandes</b><br />
<br />
Choisissez ici le mode d’envoi qui sera associé uniformément à toutes les commandes.<br />
<br />
Voici les différentes options qui s’offrent à vous :<br />
<ol>
<li><b>Modes d’envoi pris en charge par la place de marché</b><br />
<br />
Choisissez un mode d’envoi dans la liste du menu déroulant. Seules les options prises en charge par la place de marché s’affichent.<br />
<br />
</li>
<li><b>Modes d’envoi sur le gestionnaire des champs libres de la boutique en ligne</b><br />
<br />
Choisissez un mode d’envoi à partir d’un champ libre de la boutique en ligne. <br />
<br />
</li>
<li><b>Sélection automatique</b><br />
<br />
Pour chaque pays de destination, magnalister sélectionne automatiquement le mode d’envoi placé en tête de liste dans le module de calcul des frais de port pour la boutique en ligne concernée. 
</li>
</ol>';
MLI18n::gi()->{'amazon_config_orderimport__field__orderimport.fbashippingmethod__hint'} = '';
MLI18n::gi()->{'amazon_config_orderimport__field__orderimport.fbapaymentstatus__label'} = 'Statut de la commande FBA dans votre boutique';
MLI18n::gi()->{'amazon_config_orderimport__field__orderimport.fbapaymentstatus__help'} = '<p>Lors des importations des commandes, Amazon ne transmet pas d\'information sur le mode d\'expédition. </p>
<p>Veuillez sélectionner dans le menu déroulant, les modes de livraison de votre boutique. Vous pouvez définir les modes de livraison de votre boutique en vous rendant sur "Shopware" > "paramètres" > "Frais de port". </p>
<p>Ce réglage est important pour l\'impression des bons de livraison et des factures, mais aussi pour le traitement ultérieur des commandes dans votre boutique ainsi que dans votre gestion des marchandises.</p>';
MLI18n::gi()->{'amazon_config_orderimport__field__orderimport.fbapaymentstatus__hint'} = '';
MLI18n::gi()->{'amazon_config_orderimport__field__customergroup__help'} = '{#i18n:global_config_orderimport_field_customergroup_help#}';

MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.carrier__help'} = '
Sélectionnez le transporteur qui sera utilisé pour les commandes Amazon.<br>
<br>
Les options suivantes s’offrent à vous :<br>
<ul>
	<li><span class="bold underline">Société de livraison Amazon</span></li>
</ul>
Sélectionnez un transporteurs dans le menu déroulant. Les sociétés recommandées par Amazon y sont affichées.<br>
<br>
Cette option est idéale si vous souhaitez <strong>toujours utiliser le même transporteur</strong> pour les commandes Amazon.<br>
<ul>
	<li><span class="bold underline">Apparier les sociétés de livraison Amazon avec les sociétés de livraison de votre boutique</span></li>
</ul>
Vous pouvez faire correspondre les transporteurs proposés par Amazon avec les transporteurs créés dans le module d\'expédition de Shopware. Pour ajouter un nouvel appariement, cliquez sur le symbole "+".<br>
<br>
Pour savoir quelle entrée du module d\'expédition de Shopware est utilisée pour l\'importation des commandes Amazon, veuillez vous référer à l\'icône info sous "Importation des commandes" -> "Service d\'expédition des commandes".<br>
<br>
Choisissez cette option si vous souhaitez <strong>utiliser les sociétés de livraisons existantes du module d\'expédition de Shopware.</strong><br>
<ul>
    <li><span class="bold underline">magnalister ajoute un champ supplémentaire dans les détails de la commande</span></li>
</ul>
Si vous sélectionnez cette option, un champ sera ajouté dans l’aperçu de la commande dans Shopware dans lequel vous pouvez renseigner la société de livraison.<br>
<br>
Choisissez cette option si vous souhaitez <strong>utiliser différents transporteurs</strong> pour les commandes Amazon.<br>
<ul>
	<li><span class="bold underline">Saisir manuellement le nom de la société de transport pour toutes les commande</span></li>
</ul>
Si vous sélectionnez "Entrer le nom de la société de livraison manuellement dans un champ " sous l’option "Société de livraison" vous pouvez saisir le nom de la société de livraison manuellement.<br>
<br>
Sélectionnez cette option, si vous souhaitez <strong>définir une société de livraison qui sera utilisée pour toutes les commandes Amazon.</strong><br>
<br>
<span class="bold underline">Important :</span>
<ul>
	<li>La société de livraison doit obligatoirement être renseignée pour que l’expédition de la commande puisse être confirmée sur Amazon.<br><br></li>
	<li>Le non-renseignement de la société de livraison lors de la confirmation de l’expédition sur amazon peut entrainer la suspension de votre compte vendeur.</li>
</ul>
';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.shipmethod__help'} = '
Sélectionnez le service de livraison qui sera utilisé pour les commandes Amazon.<br>
<br>
Les options suivantes s’offrent à vous :
<ul>
	<li><span class="bold underline">Apparier les services de livraison Amazon avec les services de livraison de votre boutique</span></li>
</ul>
Vous pouvez faire correspondre les services de livraison proposés par Amazon avec les services de livraisons créés dans le module d\'expédition de Shopware. Pour ajouter un nouvel appariement, cliquez sur le symbole "+".<br>
<br>
Pour savoir quelle entrée du module d\'expédition de Shopware est utilisée pour l\'importation des commandes Amazon, veuillez vous référer à l\'icône info sous "Importation des commandes" -> "Service d\'expédition des commandes".<br>
<br>
Choisissez cette option si vous souhaitez <strong>utiliser les services d’expédition existants du module d’expédition de Shopware.</strong><br>
<ul>
	<li><span class="bold underline">magnalister ajoute un champ supplémentaire dans les détails de la commande</span></li>
</ul>
Si vous sélectionnez cette option, un champ sera ajouté dans l’aperçu de la commande dans <strong>Shopware</strong> dans lequel vous pouvez renseigner le service de livraison.<br>
<br>
Choisissez cette option si vous souhaitez <strong>utiliser différents transporteurs</strong> pour les commandes Amazon.<br>
<ul>
	<li><span class="bold underline">Saisir manuellement le nom du service d\'expédition pour toutes les commande</span></li>
</ul>
Si vous sélectionnez "Entrer le nom du service d’expédition manuellement dans un champ " sous l’option "Service d’expédition" vous pouvez saisir le nom de la société de livraison manuellement dans le champ de droite.<br>
<br>
Sélectionnez cette option, si vous souhaitez <strong>définir un service d’expédition qui sera utilisée pour toutes les commandes Amazon.</strong><br>
<br>
<span class="bold underline">Important :</span>
<ul>
	<li>Le service d’expédition doit obligatoirement être renseigné pour que l’expédition de la commande puisse être confirmée sur Amazon<br><br></li>
	<li>Le non-renseignement du service d’expédition lors de la confirmation de l’expédition sur amazon peut entrainer la suspension de votre compte vendeur.</li>
</ul>
';
