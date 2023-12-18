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

MLI18n::gi()->{'hood_config_producttemplate_content'} = '<style>
ul.magna_properties_list {
    margin: 0 0 20px 0;
    list-style: none;
    padding: 0;
    display: inline-block;
    width: 100%
}
ul.magna_properties_list li {
    border-bottom: none;
    width: 100%;
    height: 20px;
    padding: 6px 5px;
    float: left;
    list-style: none;
}
ul.magna_properties_list li.odd {
    background-color: rgba(0, 0, 0, 0.05);
}
ul.magna_properties_list li span.magna_property_name {
    display: block;
    float: left;
    margin-right: 10px;
    font-weight: bold;
    color: #000;
    line-height: 20px;
    text-align: left;
    font-size: 12px;
    width: 50%;
}
ul.magna_properties_list li span.magna_property_value {
    color: #666;
    line-height: 20px;
    text-align: left;
    font-size: 12px;

    width: 50%;
}
</style>
<p>#TITLE#</p>
<p>#ARTNR#</p>
<p>#SHORTDESCRIPTION#</p>
<p>#PICTURE1#</p>
<p>#PICTURE2#</p>
<p>#PICTURE3#</p>
<p>#DESCRIPTION#</p>
<p>#Description1# #Freetextfield1#</p>
<p>#Description2# #Freetextfield2#</p>
<div>#PROPERTIES#</div>';
MLI18n::gi()->{'hood_config_orderimport__field__updateablepaymentstatus__label'} = 'Autoriser les changements de statut de paiement si';
MLI18n::gi()->{'hood_config_orderimport__field__updateablepaymentstatus__help'} = 'Vous pouvez avec cette fonction synchroniser le changement de statut des commandes après paiements sur Hood.de. <br>
Normalement, les changements de statut de commande n\'ont pas d’incidence sur le statut de paiement sur Hood.de. <br>
<br>
Si vous ne souhaitez aucun changement de statut au paiement de la commande, désactivez la case à droite de la fenêtre de choix.<br>
<br>
<strong>Remarque :</strong> Le statut des commandes combinées ne sera modifié, que si toutes les parties ont été payées.';
MLI18n::gi()->{'hood_config_orderimport__field__paidstatus__label'} = 'Statut "payé" d\'Hood.de, en magasin';
MLI18n::gi()->{'hood_config_orderimport__field__paidstatus__help'} = '<p> Définissez ici le statut de la commande et le statut du paiement qui sera attribué à une commande, si elle a été payée via Paypal sur Hood.de.</p>
<p>
Lorsqu\'un client achète l\'un de vos articles sur Hood.de, la commande est directement transmise à votre boutique en ligne.
Le mode de paiement sera alors "Hood.de" ou la valeur que vous avez saisie dans les "réglages d\'experts". </p>';
MLI18n::gi()->{'hood_config_orderimport__field__orderstatus.paid__label'} = 'Statut de la commande';
MLI18n::gi()->{'hood_config_orderimport__field__orderstatus.paid__help'} = '';
MLI18n::gi()->{'hood_config_orderimport__field__paymentstatus.paid__label'} = 'Statut de la commande';
MLI18n::gi()->{'hood_config_orderimport__field__paymentstatus.paid__help'} = '';
MLI18n::gi()->{'hood_config_orderimport__field__updateable.paymentstatus__label'} = '';
MLI18n::gi()->{'hood_config_orderimport__field__updateable.paymentstatus__help'} = '';
MLI18n::gi()->{'hood_config_orderimport__field__update.paymentstatus__label'} = 'Changement de statut de paiement activé';
MLI18n::gi()->{'hood_config_orderimport__field__orderimport.paymentmethod__label'} = 'Mode de paiement de la commande';
MLI18n::gi()->{'hood_config_orderimport__field__orderimport.paymentmethod__help'} = 'Mode de paiement qui sera attribué à toutes les commandes effectuées sur Rakuten lors de l\'importation des commandes. <br><br>
Vous pouvez définir d\'autres modes de paiements qui s\'afficheront dans le menu déroulant en vous rendant sur "Shopware" > "moyens de paiement".<br><br>
Ce réglage est important pour l\'impression des bons de livraison et des factures, mais aussi pour le traitement ultérieur des commandes dans votre boutique ainsi que dans votre gestion des marchandises.';
MLI18n::gi()->{'hood_config_orderimport__field__orderimport.paymentmethod__hint'} = '';
MLI18n::gi()->{'hood_config_orderimport__field__orderimport.shippingmethod__label'} = 'Mode de livraison de la commande';
MLI18n::gi()->{'hood_config_orderimport__field__orderimport.shippingmethod__help'} = '<p> Mode de paiement qui sera attribué à toutes les commandes effectuées sur Hood.de lors de l\'importation des commandes. <br>
Valeur par défaut : "Attribution automatique"</p>
<p>
Si l\'"Attribution automatique" est sélectionnée magnalister reprend le mode de paiement que le client a choisit lors de sa commande sur Hood.de.
Le mode de paiement sera alors ajouté à votre boutique dans "Shopware" > "Paramètres" > "Frais de port". </p>
<p>Vous pouvez définir d\'autres modes de paiements qui s\'afficheront dans le menu déroulant en vous rendant sur "Shopware" > "Frais de port".</p>
<p>Ce réglage est important pour l\'impression des bons de livraison et des factures, mais aussi pour le traitement ultérieur des commandes dans votre boutique ainsi que dans votre gestion des marchandises.</p>
';
MLI18n::gi()->{'hood_config_orderimport__field__orderimport.shippingmethod__hint'} = '';
MLI18n::gi()->{'hood_config_orderimport__field__orderimport.paymentstatus__label'} = 'Statut du paiement dans votre boutique';
MLI18n::gi()->{'hood_config_orderimport__field__orderimport.paymentstatus__help'} = 'Sélectionnez ici le statut de paiement qui sera automatiquement attribué aux commandes lors de l’importation des commandes depuis la place de marché.';
MLI18n::gi()->{'hood_config_orderimport__field__orderimport.paymentstatus__hint'} = '';
MLI18n::gi()->{'hood_config_orderimport__field__customergroup__help'} = '{#i18n:global_config_orderimport_field_customergroup_help#}';
MLI18n::gi()->{'hood_config_producttemplate__field__template.content__label'} = 'Corp du template';
MLI18n::gi()->{'hood_config_producttemplate__field__template.content__hint'} = 'Liste des champs de texte libre disponibles pour la description de l’article : <br>
<br>
#TITLE#<br>
<BLOCKQUOTE>
<p>nom du produit</p>
</BLOCKQUOTE>
#ARTNR#
<BLOCKQUOTE>
<p>numéro d’article dans votre boutique</p>
</BLOCKQUOTE>
#PID#
<BLOCKQUOTE>
<p>identifiant du produit dans votre boutique</p>
</BLOCKQUOTE>
#SHORTDESCRIPTION#
<BLOCKQUOTE>
<p>description abrégée de l’article de votre boutique</p>
</BLOCKQUOTE>
#DESCRIPTION#
	<BLOCKQUOTE>
<p>description de l’article de votre boutique</p>
	</BLOCKQUOTE>
#PICTURE1#
	<BLOCKQUOTE>
<p>première image de l’article</p>
	</BLOCKQUOTE>
#PICTURE2# etc.
<BLOCKQUOTE>
<p>deuxième image de l’article; vous pouvez ajouter plus d’images de l’article (autant que dans votre boutique) en saisissant #PICTURE3#, #PICTURE4# etc.</p>
</BLOCKQUOTE>
<br>
<p><font color="red">(les paramètres suivants ne sont pas disponibles sur Shopware)</font></p>
<br>
<br>

Champs de texte libre pour description d’article :<br>
<br>
#Description1# #Freetextfield1#<br>
#Description2# #Freetextfield2#<br>
#description12# #freetextfield1#<br>
<br>
Prise en charge des champs de texte libre: le chiffre derrière le paramètre générique correspond à la position du texte.<br>
<br>
#PROPERTIES#<br>
liste contenant toutes les caractéristiques du produit. Vous pouvez changer l’apparence de la liste avec CSS.

';
