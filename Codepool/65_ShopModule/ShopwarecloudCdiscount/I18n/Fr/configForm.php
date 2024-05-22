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
 * (c) 2010 - 2022 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

MLI18n::gi()->{'cdiscount_config_orderimport__field__orderimport.paymentmethod__label'} = 'Mode de paiement des commandes';
MLI18n::gi()->{'cdiscount_config_orderimport__field__orderimport.paymentmethod__help'} = 'Mode de paiement assigné à toutes les commandes Cdiscount. Standard : « Cdiscount ».<br><br>
Veuillez sélectionner dans le menu déroulant, les modes de livraison de votre boutique. Vous pouvez définir les modes de livraison de votre boutique en vous rendant sur "Shopware" > "paramètres" > "définir le mode de paiement et l\'utiliser". 
Ce réglage est important pour l\'impression des bons de livraison et des factures, mais aussi pour le traitement ultérieur des commandes dans votre boutique ainsi que dans votre gestion des marchandises.';
MLI18n::gi()->{'cdiscount_config_orderimport__field__orderimport.paymentmethod__hint'} = '';
MLI18n::gi()->{'cdiscount_config_orderimport__field__orderimport.shippingmethod__label'} = 'Mode d\'expédition des commandes';
MLI18n::gi()->{'cdiscount_config_orderimport__field__orderimport.shippingmethod__help'} = '<p>Lors des importations des commandes, Cdiscount ne transmet pas d\'information sur le mode d\'expédition. </p>
<p>Veuillez sélectionner dans le menu déroulant, les modes de livraison de votre boutique. Vous pouvez définir les modes de livraison de votre boutique en vous rendant sur "Shopware" > "paramètres" > "Frais de port". </p>
<p>Ce réglage est important pour l\'impression des bons de livraison et des factures, mais aussi pour le traitement ultérieur des commandes dans votre boutique ainsi que dans votre gestion des marchandises.</p>';
MLI18n::gi()->{'cdiscount_config_orderimport__field__orderimport.shippingmethod__hint'} = '';
MLI18n::gi()->{'cdiscount_config_orderimport__field__orderimport.paymentstatus__label'} = 'Statut du payement en boutique';
MLI18n::gi()->{'cdiscount_config_orderimport__field__orderimport.paymentstatus__help'} = '<p>Lors des importations des commandes, Amazon ne transmet pas d\'information sur le mode d\'expédition. </p>
<p>Veuillez sélectionner dans le menu déroulant, les modes de livraison de votre boutique. Vous pouvez définir les modes de livraison de votre boutique en vous rendant sur "Shopware" > "paramètres" > "Frais de port". </p>
<p>Ce réglage est important pour l\'impression des bons de livraison et des factures, mais aussi pour le traitement ultérieur des commandes dans votre boutique ainsi que dans votre gestion des marchandises.</p>';
MLI18n::gi()->{'cdiscount_config_orderimport__field__orderimport.paymentstatus__hint'} = '';
MLI18n::gi()->{'cdiscount_config_orderimport__field__customergroup__help'} = '{#i18n:global_config_orderimport_field_customergroup_help#}';
MLI18n::gi()->{'sCdiscount_automatically'} = '-- attribution automatique --';

MLI18n::gi()->{'cdiscount_config_orderimport__field__orderstatus.carrier__help'} = '
Sélectionnez le transporteur qui sera utilisé pour les commandes Cdiscount.<br>
<br>
Les options suivantes s’offrent à vous :<br>
<ul>
    <li>
        <span class="bold underline">Société de livraison Cdiscount</span>
        <p>Sélectionnez un transporteurs dans le menu déroulant. Les sociétés recommandées par Cdiscount y sont affichées.<br>
            <br>
            Cette option est idéale si vous souhaitez <strong>toujours utiliser le même transporteur</strong> pour les commandes Cdiscount.
        </p>
    </li>
    <li>
        <span class="bold underline">{#i18n:amazon_config_carrier_option_group_shopfreetextfield_option_carrier#}</span>
        <p>{#i18n:shop_order_attribute_creation_instruction#}
            <br>
            Choisissez cette option si vous souhaitez <strong>utiliser différents transporteurs</strong> pour les commandes Cdiscount.<br>
        </p>
    </li>
    <li>
        <span class="bold underline">Apparier les sociétés de livraison Cdiscount avec les sociétés de livraison de votre boutique</span>
        <p>Vous pouvez faire correspondre les transporteurs proposés par Cdiscount avec les transporteurs créés dans le module d\'expédition de Shopware. Pour ajouter un nouvel appariement, cliquez sur le symbole "+".<br>
            <br>
            Pour savoir quelle entrée du module d\'expédition de Shopware est utilisée pour l\'importation des commandes Cdiscount, veuillez vous référer à l\'icône info sous "Importation des commandes" -> "Service d\'expédition des commandes".<br>
            <br>
            Choisissez cette option si vous souhaitez <strong>utiliser les sociétés de livraisons existantes du module d\'expédition de Shopware.</strong><br>
        </p>
    </li>
    <li>
        <span class="bold underline">magnalister ajoute un champ supplémentaire dans les détails de la commande</span>
        <p>Si vous sélectionnez cette option, un champ sera ajouté dans l’aperçu de la commande dans Shopware dans lequel vous pouvez renseigner la société de livraison.<br>
            <br>
            Choisissez cette option si vous souhaitez <strong>utiliser différents transporteurs</strong> pour les commandes Cdiscount.<br>
        </p>
    </li>
    <li>
        <span class="bold underline">Saisir manuellement le nom de la société de transport pour toutes les commande</span><br>
        <p>Sélectionnez cette option, si vous souhaitez <strong>définir une société de livraison qui sera utilisée pour toutes les commandes Cdiscount.</strong><br></p>
    </li>
</ul>
<span class="bold underline">Important :</span>
<ul>
    <li>La société de livraison doit obligatoirement être renseignée pour que l’expédition de la commande puisse être confirmée sur Cdiscount.<br><br></li>
    <li>Le non-renseignement de la société de livraison lors de la confirmation de l’expédition sur Cdiscount peut entrainer la suspension de votre compte vendeur.</li>
</ul>
';
