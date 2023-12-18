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

MLI18n::gi()->amazon_config_carrier_other = 'Autres';
MLI18n::gi()->{'amazon_config_general_mwstoken_help'} = '<p>Afin de transférer des données entre votre boutique et un compte vendeur Amazon, une authentification est nécessaire.
Saisissez ici les différents identifiants dans leurs champs respectifs. Les identifiants peuvent être obtenus dans votre compte vendeur Amazon.
</p><p>
Cliquez sur le lien suivant pour ouvrir un tutoriel vous indiquant étape par étape comment obtenir les codes requis :
</p><p>
<a href="https://otrs.magnalister.com/otrs/public.pl?Action=PublicFAQZoom;ItemID=1176;" target="_blank">Comment obtenir ou renouveler le "MWS Token" Amazon?</a>
</p>
';
MLI18n::gi()->{'amazon_config_general_autosync'} = 'Synchronisation automatique via CronJob (recommandée)';
MLI18n::gi()->{'amazon_config_general_nosync'} = 'Aucune synchronisation';
MLI18n::gi()->{'amazon_config_account_title'} = 'Mes coordonnées';
MLI18n::gi()->{'amazon_config_account_prepare'} = 'Préparation d\'article';
MLI18n::gi()->{'amazon_config_account_price'} = 'Calcul du prix';
MLI18n::gi()->{'amazon_configform_orderstatus_sync_values__auto'} = '{#i18n:amazon_config_general_autosync#}';
MLI18n::gi()->{'amazon_configform_orderstatus_sync_values__no'} = '{#i18n:amazon_config_general_nosync#}';
MLI18n::gi()->{'amazon_configform_sync_values__auto'} = 'Synchronisation automatique via CronJob (recommandée)';
MLI18n::gi()->{'amazon_configform_sync_values__no'} = 'Aucune synchronisation';
MLI18n::gi()->{'amazon_configform_stocksync_values__rel'} = 'Une commande (pas de commande FBA) réduit les stocks en boutique (recommandée)';
MLI18n::gi()->{'amazon_configform_stocksync_values__fba'} = 'Une commande (également une commande FBA) réduit les stocks en boutique';
MLI18n::gi()->{'amazon_configform_stocksync_values__no'} = 'Aucune synchronisation';
MLI18n::gi()->{'amazon_configform_pricesync_values__auto'} = 'Synchronisation automatique via CronJob (recommandée)';
MLI18n::gi()->{'amazon_configform_pricesync_values__no'} = 'Aucune synchronisation';
MLI18n::gi()->{'amazon_configform_orderimport_payment_values__textfield__title'} = 'De la zone de texte';
MLI18n::gi()->{'amazon_configform_orderimport_payment_values__textfield__textoption'} = '1';
MLI18n::gi()->{'amazon_configform_orderimport_payment_values__Amazon__title'} = 'Amazon';
MLI18n::gi()->{'amazon_configform_orderimport_shipping_values__textfield__title'} = 'De la zone de texte';
MLI18n::gi()->{'amazon_configform_orderimport_shipping_values__textfield__textoption'} = '1';
MLI18n::gi()->{'amazon_config_account_sync'} = 'Synchronisation';
MLI18n::gi()->{'amazon_config_account_orderimport'} = 'Importation des commandes';
MLI18n::gi()->{'amazon_config_account_emailtemplate'} = '{#i18n:configform_emailtemplate_legend#}';
MLI18n::gi()->{'amazon_config_account_shippinglabel'} = 'Etiquettes d\'expédition';
MLI18n::gi()->{'amazon_config_account_emailtemplate_sender'} = 'Nom de votre boutique, de votre société, ...';
MLI18n::gi()->{'amazon_config_account_emailtemplate_sender_email'} = 'exemple@votre-boutique.fr';
MLI18n::gi()->{'amazon_config_account_emailtemplate_subject'} = 'Votre commande sur #SHOPURL#';
MLI18n::gi()->{'amazon_config_account_emailtemplate_content'} = ' <style><!--
body {
    font: 12px sans-serif;
}
table.ordersummary {
	width: 100%;
	border: 1px solid #e8e8e8;
}
table.ordersummary td {
	padding: 3px 5px;
}
table.ordersummary thead td {
	background: #cfcfcf;
	color: #000;
	font-weight: bold;
	text-align: center;
}
table.ordersummary thead td.name {
	text-align: left;
}
table.ordersummary tbody tr.even td {
	background: #e8e8e8;
	color: #000;
}
table.ordersummary tbody tr.odd td {
	background: #f8f8f8;
	color: #000;
}
table.ordersummary td.price,
table.ordersummary td.fprice {
	text-align: right;
	white-space: nowrap;
}
table.ordersummary tbody td.qty {
	text-align: center;
}
--></style>
<p>Bonjour  #FIRSTNAME# #LASTNAME#,</p>
<p>Merci beaucoup pour votre commande! Vous avez commander sur #MARKETPLACE# l’article suivant:</p>
#ORDERSUMMARY#
<p>Plus frais de port.</p>

<p>Sincères salutations</p>
<p>Votre équipe #ORIGINATOR#</p>';
MLI18n::gi()->{'amazon_config_tier_error'} = 'Amazon Business (B2B): votre configuration (#{#TierNumber#}) n\'est pas correcte!';

MLI18n::gi()->{'amazon_config_amazonvcsinvoice_invoicenumberoption_values_magnalister'} = 'Charger magnalister de la création des numéros de commande';
MLI18n::gi()->{'amazon_config_amazonvcsinvoice_invoicenumberoption_values_matching'} = 'Tirer le numéro de commande d’un champ de texte libre Shopware';
MLI18n::gi()->{'amazon_config_amazonvcsinvoice_reversalinvoicenumberoption_values_magnalister'} = 'Charger magnalister de la création des numéros de commande';
MLI18n::gi()->{'amazon_config_amazonvcsinvoice_reversalinvoicenumberoption_values_matching'} = 'Tirer le numéro de commande d’un champ de texte libre Shopware';

MLI18n::gi()->{'amazon_config_account__legend__account'} = 'Mes coordonnées';
MLI18n::gi()->{'amazon_config_account__legend__tabident'} = '';
MLI18n::gi()->{'amazon_config_account__field__tabident__label'} = '{#i18n:ML_LABEL_TAB_IDENT#}';
MLI18n::gi()->{'amazon_config_account__field__tabident__help'} = '{#i18n:ML_TEXT_TAB_IDENT#}';
MLI18n::gi()->{'amazon_config_account__field__username__label'} = 'Adresse courriel';
MLI18n::gi()->{'amazon_config_account__field__username__hint'} = '';
MLI18n::gi()->{'amazon_config_account__field__password__label'} = 'Mot de passe général du vendeur';
MLI18n::gi()->{'amazon_config_account__field__password__help'} = 'Saisissez ici, votre mot de passe Amazon';
MLI18n::gi()->{'amazon_config_account__field__mwstoken__label'} = 'MWS Token';
MLI18n::gi()->{'amazon_config_account__field__mwstoken__help'} = '{#i18n:amazon_config_general_mwstoken_help#}';
MLI18n::gi()->{'amazon_config_account__field__merchantid__label'} = 'Commerçant-ID';
MLI18n::gi()->{'amazon_config_account__field__merchantid__help'} = '{#i18n:amazon_config_general_mwstoken_help#}';
MLI18n::gi()->{'amazon_config_account__field__marketplaceid__label'} = 'Place de marché-ID';
MLI18n::gi()->{'amazon_config_account__field__marketplaceid__help'} = '{#i18n:amazon_config_general_mwstoken_help#}';
MLI18n::gi()->{'amazon_config_account__field__site__label'} = 'Localisation du site d\'Amazon';
MLI18n::gi()->{'amazon_config_prepare__legend__prepare'} = 'Préparation d\'article';
MLI18n::gi()->{'amazon_config_prepare__legend__machingbehavior'} = 'Comportement lors de l\'appariement';
MLI18n::gi()->{'amazon_config_prepare__legend__apply'} = 'Création de nouveaux produits';
MLI18n::gi()->{'amazon_config_prepare__legend__shipping'} = 'Expédition';
MLI18n::gi()->{'amazon_config_prepare__legend__upload'} = 'Préréglages de téléchargement';
MLI18n::gi()->{'amazon_config_prepare__legend__shippingtemplate'} = 'Profils d’expédition par région d’expédition';
MLI18n::gi()->{'amazon_config_prepare__legend__b2b'} = 'Amazon Business (B2B)';
MLI18n::gi()->{'amazon_config_prepare__field__prepare.status__label'} = 'Statut de filtre';
MLI18n::gi()->{'amazon_config_prepare__field__prepare.status__valuehint'} = 'Ne prendre en charge que les articles actifs';
MLI18n::gi()->{'amazon_config_prepare__field__checkin.status__label'} = 'Statut du filtre';
MLI18n::gi()->{'amazon_config_prepare__field__checkin.status__valuehint'} = 'Ne prendre en charge que les articles actifs';
MLI18n::gi()->{'amazon_config_prepare__field__lang__label'} = 'Description de l\'article';
MLI18n::gi()->{'amazon_config_prepare__field__internationalshipping__label'} = 'Expédition';
MLI18n::gi()->{'amazon_config_prepare__field__internationalshipping__hint'} = 'Si les groupes d\'expédition du vendeur sont activés, ce paramètre est ignoré.';
MLI18n::gi()->{'amazon_config_prepare__field__multimatching__label'} = 'Appariez de nouveau';
MLI18n::gi()->{'amazon_config_prepare__field__multimatching__valuehint'} = 'Remplacez les produits déjà appariés lors du multi et auto appariement.';
MLI18n::gi()->{'amazon_config_prepare__field__multimatching__help'} = 'Si vous avez activé ce paramètre, les produits déjà appariés seront remplacés par les nouveaux correspondants.';
MLI18n::gi()->{'amazon_config_prepare__field__multimatching.itemsperpage__label'} = 'Résultats';
MLI18n::gi()->{'amazon_config_prepare__field__multimatching.itemsperpage__help'} = 'Ici, vous pouvez définir le nombre de produits par page lorsque le Multi appariement s\'affiche.<br/> Plus le nombre est important, plus long sera le chargement (pour 50 résultats comptez environ 30 secondes).';
MLI18n::gi()->{'amazon_config_prepare__field__multimatching.itemsperpage__hint'} = 'Par page lors du multi appariement ';
MLI18n::gi()->{'amazon_config_prepare__field__prepare.manufacturerfallback__label'} = 'Fabricant par défaut';
MLI18n::gi()->{'amazon_config_prepare__field__prepare.manufacturerfallback__help'} = 'Amazon demande de spécifier les fabricants des produits vendus sur leurs places de marché. Si pour un produit vous n’avez pas spécifié de fabricant, celui que vous pouvez donner dans cette rubrique  sera alors automatiquement utilisé.<br>
<br>
Sous l\'onglet "Configuration globale" "Propriétés du produit", vous pouvez apparier (Matcher) des „fabricants“ à vos attributs.';
MLI18n::gi()->{'amazon_config_prepare__field__quantity__label'} = 'Variation de stock';
MLI18n::gi()->{'amazon_config_prepare__field__quantity__help'} = 'Cette rubrique vous permet d’indiquer les quantités disponibles d’un article de votre stock, pour une place de marché particulière. <br>
<br>
Elle vous permet aussi de gérer le problème de ventes excédentaires. Pour cela activer dans la liste de choix, la fonction : "reprendre le stock de l\'inventaire en boutique, moins la valeur du champ de droite". <br>
Cette option ouvre automatiquement un champ sur la droite, qui vous permet de donner des quantités à exclure de la comptabilisation de votre inventaire général, pour les réserver à un marché particulier. <br>
<br>
<b>Exemple :</b> Stock en boutique : 10 (articles) &rarr; valeur entrée: 2 (articles) &rarr; Stock alloué à Amazon: 8 (articles).<br>
<br>
<b>Remarque :</b> Si vous souhaitez cesser la vente sur Amazon, d’un article que vous avez encore en stock, mais que vous avez désactivé de votre boutique, procédez comme suit :
<ol>
      <li>
Cliquez sur  les onglets  “Configuration” →  “Synchronisation”; 
</li>
      <li>
Rubrique  “Synchronisation des Inventaires" →  "Variation du stock boutique";
</li>
      <li>
Activez dans la liste de choix "synchronisation automatique via CronJob";
</li>
<li>
Cliquez sur  l’onglet  "Configuration globale";
</li>
<li>
    Rubrique “Inventaire”, activez "Si le statut du produit est placé comme étant   inactif, le niveau des stocks sera alors enregistré comme quantité 0".
</li>
</ol>
';
MLI18n::gi()->{'amazon_config_prepare__field__leadtimetoship__label'} = 'Délai de traitement (en jours)';
MLI18n::gi()->{'amazon_config_prepare__field__leadtimetoship__help'} = 'Temps qui s\'écoule entre le moment où l\'acheteur passe commande et celui où vous remettez la commande à votre transporteur.<br>Si vous ne donnez ici aucune valeur, le délai de traitement, par défaut, entre 1 et 2 jours ouvrables.
Utilisez ce champ si le délai de traitement pour un article dépasse les deux jours ouvrables prévus.';
MLI18n::gi()->{'amazon_config_prepare__field__checkin.skuasmfrpartno__label'} = 'Réference fabricant';
MLI18n::gi()->{'amazon_config_prepare__field__checkin.skuasmfrpartno__help'} = 'Le numéro SKU sera transmit comme numéro d\'article du fabricant';
MLI18n::gi()->{'amazon_config_prepare__field__checkin.skuasmfrpartno__valuehint'} = 'Le numéro SKU sera utilisé comme référence fabricant';
MLI18n::gi()->{'amazon_config_prepare__field__imagesize__label'} = 'Taille d\'image';
MLI18n::gi()->{'amazon_config_prepare__field__imagesize__help'} = '<p>Indiquez ici, la largeur en pixels de l\'image, que vous souhaitez avoir sur la place de marché.
La hauteur sera ajustée automatiquement aux caractéristiques de la page d\'origine.</p>
<p>
Les fichiers source seront modifiés à partir du dossier image {#setting:sSourceImagePath#} et déposés avec la largeur en pixels désirée dans le dossier {#setting:sImagePath#} pour la transmission à la place de marché.';
MLI18n::gi()->{'amazon_config_prepare__field__imagesize__hint'} = 'Enregistrée sous: {#setting:sImagePath#}-';
MLI18n::gi()->{'amazon_config_prepare__field__shipping.template.active__label'} = 'Utiliser les profils de Paramètres d’expédition par régions d’expédition.';
MLI18n::gi()->{'amazon_config_prepare__field__shipping.template.active__help'} = 'Les vendeurs peuvent créer des profils de réglages d\'expédition différents, selon leurs exigences et les coûts en vigueurs. 
Selon les régions, des profils particuliers de condition d\'expédition peuvent être établis, - conditions et/ou coût d\'expédition, différents. 
Lorsque le vendeur prépare un produit, il peut donner un des profils de réglages d\'expédition au préalable défini pour le produit à préparer. Les réglages d\'expédition de ce profil sont alors utilisés. Si aucun profil n’est mentionné les conditions d’expéditions standards seront utilisées.';
MLI18n::gi()->{'amazon_config_prepare__field__shipping.template__label'} = 'profil de Paramètres d’expédition par région d’expédition';
MLI18n::gi()->{'amazon_config_prepare__field__shipping.template__hint'} = 'Profil étbli pour une offre particulière. 
<br>Les profils de Paramètres d’expédition par région d’expédition sont déterminés et gérés par les vendeurs';
MLI18n::gi()->{'amazon_config_prepare__field__shipping.template__help'} = 'Les vendeurs peuvent créer des profils de réglages d\'expédition différents, selon leurs exigences et les coûts en vigueurs. 
Selon les régions, des profils particuliers de condition d\'expédition peuvent être établis, - conditions et/ou coût d\'expédition, différents. 
Lorsque le vendeur prépare un produit, il peut donner un des profils de réglages d\'expédition au préalable défini pour le produit à préparer. Les réglages d\'expédition de ce profil sont alors utilisés. Si aucun profil n’est mentionné les conditions d’expéditions standards seront utilisées.';
MLI18n::gi()->{'amazon_config_prepare__field__shipping.template.name__label'} = 'Nom du profil de Paramètres d’expédition par région d’expédition.';
MLI18n::gi()->{'amazon_config_prepare__field__b2bactive__label'} = 'Utilisez Amazon B2B';
MLI18n::gi()->{'amazon_config_prepare__field__b2bactive__help'} = 'Pour pouvoir utiliser cette fonctionnalité, vous devez être inscrit au programme Amazon Business Seller. Si vous n\'êtes pas encore affilié à ce programme, connectez-vous d\'abord à votre compte Amazon vendeur et activez l\'option : Business seller. <br />
La seule exigence est d\'avoir un «compte Amazon vendeur professionnel », si ce n\'est pas le cas, vous pouvez mettre à niveau votre compte existant).
<br /><br />
Lisez, s\'il vous plaît la note, en cliquant sur l\'icône d\'information de la rubrique : "importation de commande" > "Activer l\'importation des commandes".';
MLI18n::gi()->{'amazon_config_prepare__field__itemcondition__label'} = 'Item Condition';
MLI18n::gi()->{'amazon_config_prepare__field__b2bactive__notification'} = 'Pour pouvoir utiliser Amazon Business, vous devez l\'activer sur votre compte Amazon. <b>Veuillez vous assurer, que sur votre compte Amazon, l\'option Amazon business est bien activée. </b>Si ce n\'est pas le cas, le téléchargement des articles B2B entraînera des messages d\'erreurs. <br> Pour activer votre compte Amazon Business, veuillez suivre les indications de cette <a href="https://sellercentral.amazon.fr/business/b2bregistration" target="_blank">page</a>.';
MLI18n::gi()->{'amazon_config_prepare__field__b2bactive__values__true'} = 'Oui';
MLI18n::gi()->{'amazon_config_prepare__field__b2bactive__values__false'} = 'Non';
MLI18n::gi()->{'amazon_config_prepare__field__b2b.tax_code__label'} = 'TVA commerciale - Appariement ';
MLI18n::gi()->{'amazon_config_prepare__field__b2b.tax_code__hint'} = '';
MLI18n::gi()->{'amazon_config_prepare__field__b2b.tax_code__matching__titlesrc'} = 'TVA Boutique';
MLI18n::gi()->{'amazon_config_prepare__field__b2b.tax_code__matching__titledst'} = 'TVA Amazon Business';
MLI18n::gi()->{'amazon_config_prepare__field__b2b.tax_code_container__label'} = 'TVA commerciale par catégorie - Appariement';
MLI18n::gi()->{'amazon_config_prepare__field__b2b.tax_code_container__hint'} = '';
MLI18n::gi()->{'amazon_config_prepare__field__b2b.tax_code_specific__label'} = '';
MLI18n::gi()->{'amazon_config_prepare__field__b2b.tax_code_specific__hint'} = '';
MLI18n::gi()->{'amazon_config_prepare__field__b2b.tax_code_specific__matching__titlesrc'} = 'TVA Boutique';
MLI18n::gi()->{'amazon_config_prepare__field__b2b.tax_code_specific__matching__titledst'} = 'TVA Amazon Business';
MLI18n::gi()->{'amazon_config_prepare__field__b2b.tax_code_category__label'} = '';
MLI18n::gi()->{'amazon_config_prepare__field__b2b.tax_code_category__hint'} = '';
MLI18n::gi()->{'amazon_config_prepare__field__b2bsellto__label'} = 'Vendu à';
MLI18n::gi()->{'amazon_config_prepare__field__b2bsellto__help'} = 'Si vous choisissez l\'option <i>B2B uniquement</i>, ne seront téléchargé que les produits compatibles avec ce marché. ';
MLI18n::gi()->{'amazon_config_prepare__field__b2bsellto__values__b2b_b2c'} = 'B2B et B2C';
MLI18n::gi()->{'amazon_config_prepare__field__b2bsellto__values__b2b_only'} = 'B2B uniquement';
MLI18n::gi()->{'amazon_config_prepare__field__b2b.price__label'} = 'Prix Business';
MLI18n::gi()->{'amazon_config_prepare__field__b2b.price__help'} = 'Indiquez un pourcentage, un prix fixe ou une remise. Les remises doivent être précédées du signe moins.';
MLI18n::gi()->{'amazon_config_prepare__field__b2b.price.addkind__label'} = '';
MLI18n::gi()->{'amazon_config_prepare__field__b2b.price.addkind__hint'} = '';
MLI18n::gi()->{'amazon_config_prepare__field__b2b.price.factor__label'} = '';
MLI18n::gi()->{'amazon_config_prepare__field__b2b.price.factor__hint'} = '';
MLI18n::gi()->{'amazon_config_prepare__field__b2b.price.signal__label'} = 'Champ décimal';
MLI18n::gi()->{'amazon_config_prepare__field__b2b.price.signal__hint'} = 'Champ décimal';
MLI18n::gi()->{'amazon_config_prepare__field__b2b.price.signal__help'} = 'Cette zone de texte sera utilisée dans les transmissions de données vers la place de Amazon, (prix après la virgule).<br/><br/>
                <strong>Par exemple :</strong> <br /> 
                 Valeur dans la zone de texte: 99 <br />
                 Prix d\'origine: 5.58 <br />
                 Prix final: 5.99 <br /><br />
                 La fonction aide en particulier, pour les majorations ou les rabais en pourcentage sur les prix. <br/>
                 Laissez le champ vide si vous souhaitez ne pas transmettre de prix avec une virgule.<br/>
                 Le format d\'entrée est un chiffre entier avec max. 2 chiffres.';
MLI18n::gi()->{'amazon_config_prepare__field__b2b.priceoptions__label'} = 'Option prix du programme Business';
MLI18n::gi()->{'amazon_config_prepare__field__b2b.price.group__label'} = '';
MLI18n::gi()->{'amazon_config_prepare__field__b2b.price.group__hint'} = '';
MLI18n::gi()->{'amazon_config_prepare__field__b2b.price.usespecialoffer__label'} = 'Utiliser aussi les prix spéciaux';
MLI18n::gi()->{'amazon_config_prepare__field__b2bdiscounttype__label'} = 'Calcul des prix';
MLI18n::gi()->{'amazon_config_prepare__field__b2bdiscounttype__help'} = '<b>Calcul des prix</b><br>
Avec cette option, Les commerçants participant au programme Business Seller peuvent déterminer des tarifs particuliers en fonction des quantités commandées.<br><br>';
MLI18n::gi()->{'amazon_config_prepare__field__b2bdiscounttype__values__'} = 'Ne pas tenir compte';
MLI18n::gi()->{'amazon_config_prepare__field__b2bdiscounttype__values__percent'} = 'Pourcentage';
MLI18n::gi()->{'amazon_config_prepare__field__b2bdiscounttier1__label'} = 'Prix plancher 1';
MLI18n::gi()->{'amazon_config_prepare__field__b2bdiscounttier1__help'} = 'La remise doit être supérieure à 0';
MLI18n::gi()->{'amazon_config_prepare__field__b2bdiscounttier2__label'} = 'Prix plancher 2';
MLI18n::gi()->{'amazon_config_prepare__field__b2bdiscounttier3__label'} = 'Prix plancher 3';
MLI18n::gi()->{'amazon_config_prepare__field__b2bdiscounttier4__label'} = 'Prix plancher 4';
MLI18n::gi()->{'amazon_config_prepare__field__b2bdiscounttier5__label'} = 'Prix plancher 5';
MLI18n::gi()->{'amazon_config_prepare__field__b2bdiscounttier1quantity__label'} = 'Nombre de pièce';
MLI18n::gi()->{'amazon_config_prepare__field__b2bdiscounttier2quantity__label'} = 'Nombre de pièce';
MLI18n::gi()->{'amazon_config_prepare__field__b2bdiscounttier3quantity__label'} = 'Nombre de pièce';
MLI18n::gi()->{'amazon_config_prepare__field__b2bdiscounttier4quantity__label'} = 'Nombre de pièce';
MLI18n::gi()->{'amazon_config_prepare__field__b2bdiscounttier5quantity__label'} = 'Nombre de pièce';
MLI18n::gi()->{'amazon_config_prepare__field__b2bdiscounttier1discount__label'} = 'Remise';
MLI18n::gi()->{'amazon_config_prepare__field__b2bdiscounttier2discount__label'} = 'Remise';
MLI18n::gi()->{'amazon_config_prepare__field__b2bdiscounttier3discount__label'} = 'Remise';
MLI18n::gi()->{'amazon_config_prepare__field__b2bdiscounttier4discount__label'} = 'Remise';
MLI18n::gi()->{'amazon_config_prepare__field__b2bdiscounttier5discount__label'} = 'Remise';
MLI18n::gi()->{'amazon_config_price__legend__price'} = 'Calcul du prix';
MLI18n::gi()->{'amazon_config_price__field__price__label'} = 'Prix';
MLI18n::gi()->{'amazon_config_price__field__price__hint'} = '';
MLI18n::gi()->{'amazon_config_price__field__price__help'} = 'Veuillez saisir un pourcentage, un prix majoré, un rabais ou un prix fixe prédéfini. 
Pour indiquer un rabais faire précéder le chiffre d’un moins. ';
MLI18n::gi()->{'amazon_config_price__field__price.addkind__label'} = '';
MLI18n::gi()->{'amazon_config_price__field__price.addkind__hint'} = '';
MLI18n::gi()->{'amazon_config_price__field__price.factor__label'} = '';
MLI18n::gi()->{'amazon_config_price__field__price.factor__hint'} = '';
MLI18n::gi()->{'amazon_config_price__field__price.signal__label'} = 'Champ décimal';
MLI18n::gi()->{'amazon_config_price__field__price.signal__hint'} = 'Champ décimal';
MLI18n::gi()->{'amazon_config_price__field__price.signal__help'} = '                Cette zone de texte sera utilisée dans les transmissions de données vers la place de Amazon, (prix après la virgule).<br/><br/>

                <strong>Par exemple :</strong> <br /> 
                 Valeur dans la zone de texte: 99 <br />
                 Prix d\'origine: 5.58 <br />
                 Prix final: 5.99 <br /><br />
                 La fonction aide en particulier, pour les majorations ou les rabais en pourcentage sur les prix. <br/>
                 Laissez le champ vide si vous souhaitez ne pas transmettre de prix avec une virgule.<br/>
                 Le format d\'entrée est un chiffre entier avec max. 2 chiffres.';
MLI18n::gi()->{'amazon_config_price__field__priceoptions__label'} = 'Options de tarification ';
MLI18n::gi()->{'amazon_config_price__field__priceoptions__help'} = '<p>Avec cette fonction, vous pouvez transmettre vos prix promotionnels aux places de marché, selon les groupes clients, que vous avez déterminés dans l’espace de gestion de votre boutique et opérer leurs synchronisations automatiques. Si vous n’avez pas défini de prix, pour l’un de vos groupes clients, le prix de votre boutique sera utilisé. Ainsi, vous pouvez simplement allouer un prix différent à une certaine quantité d’article, pour un groupe particulier de clients, tout en conservant les configurations initiales inhérentes à ce prix, pour un autre groupe de clients.</p>
<ul>
<li>Créez un groupe de clients dans votre boutique en ligne, par exemple : “clients réguliers”</li>
<li>Vous pouvez alors définir les prix souhaités pour ces groupes de clients et ainsi de suite.</li>
</ul>';
MLI18n::gi()->{'amazon_config_price__field__priceoptions__hint'} = '';
MLI18n::gi()->{'amazon_config_price__field__price.group__label'} = '';
MLI18n::gi()->{'amazon_config_price__field__price.group__hint'} = '';
MLI18n::gi()->{'amazon_config_price__field__price.usespecialoffer__label'} = 'Utilisez également des tarifs spéciaux';
MLI18n::gi()->{'amazon_config_price__field__price.usespecialoffer__hint'} = '';
MLI18n::gi()->{'amazon_config_price__field__exchangerate_update__label'} = 'Taux de change';
MLI18n::gi()->{'amazon_config_price__field__exchangerate_update__hint'} = 'Mise à jour automatique du taux de change';
MLI18n::gi()->{'amazon_config_price__field__exchangerate_update__help'} = 'Si la devise utilisé dans votre boutique en ligne est différente de celle de la place de marché, magnalister calcule le taux de change par rapport au taux que vous avez défini dans votre boutique en ligne. <br>
<br>
En activant cette fonction, le taux de change actuel défini par "alphavantage" sera appliqué à vos articles. Les prix dans votre boutique en ligne seront également mis à jour.<br>
<br>
L’activation et la désactivation de cette fonction prend effet toutes les heures.<br>
<br>
<b>Avertissement :</b> RedGecko GmbH n\'assume aucune responsabilité pour l\'exactitude du taux de change. Veuillez vérifier en contrôlant les prix de vos articles dans votre compte Amazon.            ';
MLI18n::gi()->{'amazon_config_price__field__exchangerate_update__alert'} = 'Si la devise utilisé dans votre boutique en ligne est différente de celle de la place de marché, magnalister calcule le taux de change par rapport au taux que vous avez défini dans votre boutique en ligne. <br>
<br>
En activant cette fonction, le taux de change actuel défini par "alphavantage" sera appliqué à vos articles. Les prix dans votre boutique en ligne seront également mis à jour.<br>
<br>
L’activation et la désactivation de cette fonction prend effet toutes les heures.<br>
<br>
Les fonctions suivantes provoqueront une actualisation du taut de change :
<ul>
<li>Importation des commandes</li>
<li>Préparer les articles</li>
<li>Charger les articles</li>
<li>Synchronisation des prix et des stocks</li>
</ul>
<b>Avertissement :</b> RedGecko GmbH n\'assume aucune responsabilité quand à l\'exactitude du taux de change. Veuillez vérifier en contrôlant les prix de vos articles sur la place de marché.            ';
MLI18n::gi()->{'amazon_config_sync__legend__sync'} = 'Synchronisation de l\'inventaire';
MLI18n::gi()->{'amazon_config_sync__field__stocksync.tomarketplace__label'} = 'Variation du stock boutique';
MLI18n::gi()->{'amazon_config_sync__field__stocksync.tomarketplace__hint'} = '';
MLI18n::gi()->{'amazon_config_sync__field__stocksync.tomarketplace__help'} = 'Utilisez la fonction “synchronisation automatique”, pour synchroniser votre stock Amazon et votre stock boutique. L’actualisation de base se fait toutes les quatre heures, - à moins que vous n’ayez définit d’autres paramètres - et commence à 00:00 heure. Si la synchronisation est activée, les données de votre base de données seront appliquées à Amazon.
Vous pouvez à tous moment effectuer une synchronisation manuelle de votre stock, en cliquant sur le bouton “synchroniser les prix et les stocks”, dans le groupe de boutons en haut à droite de la page. <br>
<br>
Il est aussi possible de synchroniser votre stock en utilisant une fonction CronJob personnelle. Cette fonction n’est disponible qu’à partir du tarif “flat”. Elle vous permet de réduire le délais maximal de  synchronisation de vos données à 15 minutes d\'intervalle. 
Pour opérer la synchronisation utilisez le lien suivant:<br>
{#setting:sSyncInventoryUrl#} <br>
<br>
Attention, toute importation provenant d’un client n’utilisant pas le tarif “flat” ou ne respectant pas le délai de 15 minute sera bloqué. <br>
 <br>
<b>Commande ou modification d’un article; l’état du stock Amazon  est comparé avec celui de votre boutique. </b> <br>
Chaque changement dans le stock de votre boutique, lors d’une commande ou de la modification d’un article, sera transmis à Amazon. <br>
Attention, les changements ayant lieu uniquement dans votre base de données, c’est-à-dire ne résultant pas d’une action opérée par une place de marché synchronisé ou sur magnalister, <b>ne seront ni pris en compte, ni transmis!</b> <br>
<br>
<b>Commande ou modification d’un article; l’état du stock Amazon est modifié (différence)</b> <br>
Si par exemple, un article a été acheté deux fois en boutique, le stock Amazon sera réduit de 2 unités. <br>
Si vous modifiez la quantité d’un article dans votre boutique, sous la rubrique “Amazon” &rarr; “configuration” &rarr; “préparation d’article”, ce changement sera appliqué sur Amazon. <br>
<br>
<b>Attention</b>, les changements ayant lieu uniquement dans votre base de données, c’est-à-dire ne résultant pas d’une action opérée sur une place de marché synchronisé ou sur magnalister, ne seront ni pris en compte, ni transmis!<br>
<br>
<br>
<b>Remarque :</b> Cette fonction n’est effective, que si vous choisissez une de deux première option se trouvant sous la rubrique: Configuration &rarr;  Préparation de l’article &rarr; Préréglages de téléchargement d’article. ';
MLI18n::gi()->{'amazon_config_sync__field__stocksync.frommarketplace__label'} = 'Variation du stock Amazon';
MLI18n::gi()->{'amazon_config_sync__field__stocksync.frommarketplace__hint'} = '';
MLI18n::gi()->{'amazon_config_sync__field__stocksync.frommarketplace__help'} = 'Si cette fonction est activée, le nombre de commandes effectués et payés sur Amazon, sera soustrait de votre stock boutique.<br>
<br>
<b>Important :</b> Cette fonction n’est opérante que lors de l’importation des commandes.
';
MLI18n::gi()->{'amazon_config_sync__field__inventorysync.price__label'} = 'Prix de l\'article';
MLI18n::gi()->{'amazon_config_sync__field__inventorysync.price__hint'} = '';
MLI18n::gi()->{'amazon_config_sync__field__inventorysync.price__help'} = '<b>Synchronisation automatique via CronJob (recommandée)</b><br>
<br>
Utilisez la fonction “synchronisation automatique” pour que les prix de vos articles sur Amazon soient mis à jour par rapport aux prix de vos articles en boutique. Cette mise à jour aura lieu toutes les quatre heures, à moins que vous n’ayez défini d’autres paramètres de configuration. <br>
Les données de votre base de données seront  appliquées sur Amazon, même si les changements n’ont eu lieu que dans votre base de données.<br>
 Vous pouvez à tout moment effectuer une synchronisation des prix en cliquant sur le bouton “synchroniser les prix et les stocks” en haut à droite du module. <br>
<br>
La fonction n’est disponible qu’à partir du tarif “flat” et autorise une synchronisation toutes les 15 minutes maximum. <br>
Pour opérer la synchronisation utilisez le lien suivant:<br>
<i>{#setting:sSyncInventoryUrl#}</i>
<br>
Toute importation provenant d’un client n’utilisant pas le tarif “flat” ou ne respectant pas le délai de 15 minutes sera bloqué.<br>
<br>
<b>Attention :</b> les paramètres configurés dans “Configuration” &rarr;  “calcul du prix”,  affecterons cette fonction.';
MLI18n::gi()->{'amazon_config_orderimport__legend__importactive'} = 'Importation des commandes';
MLI18n::gi()->{'amazon_config_orderimport__legend__mwst'} = 'TVA';
MLI18n::gi()->{'amazon_config_orderimport__legend__orderstatus'} = 'Paramètre de synchronisation des commandes boutiques vers Amazon';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.shipped__label'} = 'Expédition confirmée si :';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.shipped__hint'} = '';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.shipped__help'} = 'Définissez ici le statut de la commande, qui doit automatiquement confirmer la livraison  sur Amazon.';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.canceled__label'} = 'Annuler la commande avec';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.canceled__hint'} = '';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.canceled__help'} = '        Définissez  ici le statut de la boutique, qui doit "annuler la commande" automatiquement sur Amazon. <br/><br/>
                Remarque : une annulation partielle est impossible ici. La commande tout entière est annulée avec cette fonctionnalité et est créditée à l\'acheteur.';
MLI18n::gi()->{'amazon_config_orderimport__field__orderimport.shop__label'} = '{#i18n:form_config_orderimport_shop_lable#}';
MLI18n::gi()->{'amazon_config_orderimport__field__orderimport.shop__hint'} = '';
MLI18n::gi()->{'amazon_config_orderimport__field__orderimport.shop__help'} = '{#i18n:form_config_orderimport_shop_help#}';
MLI18n::gi()->{'amazon_config_orderimport__field__orderimport.paymentmethod__label'} = 'Mode de paiement pour les commandes';
MLI18n::gi()->{'amazon_config_orderimport__field__orderimport.paymentmethod__help'} = '<p>Mode de paiement, qui sera associé à toutes les commandes sur Amazon, lors de l\'importation des commandes. 
Standard: "Amazon"</p>
<p>
Ce paramètre est important pour les factures, l\'impression des bons de livraison, le traitement ultérieur de la commande en magasin, ainsi que dans la gestion des marchandises.</p>';
MLI18n::gi()->{'amazon_config_orderimport__field__orderimport.paymentmethod__hint'} = '';
MLI18n::gi()->{'amazon_config_orderimport__field__orderimport.shippingmethod__label'} = 'Mode de livraison des commandes';
MLI18n::gi()->{'amazon_config_orderimport__field__orderimport.shippingmethod__help'} = 'Mode de livraison, qui sera associé à toutes les commandes sur Amazon. Standard: "marketplace".<br><br>
				           Ce paramètre est important pour les factures, l\'impression des bons de livraison, le traitement ultérieur de la commande en magasin, ainsi que dans la gestion des marchandises';
MLI18n::gi()->{'amazon_config_orderimport__field__orderimport.shippingmethod__hint'} = '';
MLI18n::gi()->{'amazon_config_orderimport__field__mwstfallback__label'} = 'TVA des articles non référencés en boutique ';
MLI18n::gi()->{'amazon_config_orderimport__field__mwstfallback__hint'} = 'Le taux d\'imposition lors d\'une importation de commandes d\'articles ne venant pas de la boutique sera alors calculé en %.';
MLI18n::gi()->{'amazon_config_orderimport__field__mwstfallback__help'} = 'Si pour un article, la TVA n’a pas été spécifiée, vous pouvez ici donner un taux, qui sera automatiquement appliquée à l’importation. Les places de marché même ne donnent aucune indication de TVA.<br>
Par principe, pour l’importation des commandes et la facturation, magnalister applique le même système de TVA que celui configuré par les boutiques. <br>
Afin que les TVA nationales soient automatiquement prisent en compte, il faut que l’article acheté soit trouvé grâce à son numéro d’unité de gestion des stocks (SKU); magnalister utilisant alors la TVA configurée dans la boutique. ';
MLI18n::gi()->{'amazon_config_orderimport__field__importactive__label'} = 'Activer l\'importation des commandes';
MLI18n::gi()->{'amazon_config_orderimport__field__importactive__hint'} = '';
MLI18n::gi()->{'amazon_config_orderimport__field__importactive__help'} = 'Lorsque la fonction est activée, les commandes sont par défaut importées toutes les heures.<br />
                <br />
Vous pouvez déclencher une importation manuelle, en cliquant sur le bouton situé dans le groupe de boutons en haut à droite, face à l\'en-tête Magnalister.<br />
                <br />
À partir du tarif Flat, le déclenchement de l\'importation automatique des commandes peut être réglé au quart d’heure. Pour ce faire vous devez avoir installé, un système Cronjob sur votre serveur, puis l’appeler  à l’aide du lien suivant : <br />
                <i>{#setting:sImportOrdersUrl#}</i><br />
                <br />
                <strong>TVA:</strong><br />
                <br />
Les différents taux de TVA des pays avec lesquels vous êtes  en relation commerciales ne peuvent être correctement appliqué à vos commandes, que si vous avez les avez, au préalable, enregistrées dans votre boutique, sous la rubrique : “Localisation”  —>   Règle de taxes.  Les articles concernés doivent être, dans votre boutique, identifiables avec leur numéro SKU.<br />
                <br />
Si pour un produit, il n\'est pas trouvé d’identification dans votre boutique, magnalister applique alors le taux de TVA, que vous aurez donné sous «Importation de commande» > «TVA d’articles non référencés en boutiques».<br />
                <br />
<strong>Régle de commandes et de facturation Amazon B2B</strong> (nécessite l’adhésion au programme Amazon Business seller) :<br />
                <br />
Lors de la transmission de commandes, Amazon ne transmet pas les informations légales de TVA. En conséquence, magnalister transmet les commandes B2B à votre boutique, mais la facturation n\'est pas toujours légalement correcte.<br />
                <br />
Toutefois, vous avez la possibilité de récupérer les informations légales de TVA, dans votre espace Amazon Seller Central et de les rentrer manuellement dans vos systèmes de gestion boutique et/ou de stock.<br />
                <br />
Vous pouvez également utiliser le service de facturation fourni par Amazon pour les commandes B2B, qui contient toutes les données légales.<br />
                <br />
En tant que commerçant adhérent au programme Amazon Business seller, tous les documents nécessaires pour établir des factures, y compris les informations de TVA, sont accessibles dans votre espace vendeur Amazon sous la rubrique, "rapports" > "documents fiscaux". La mise à disposition des documents varie de 3 ou 30 jours et dépend de votre contrat Amazon B2B.<br />
                <br />
Si vous adhérez au programme logistique FBA, vous obtiendrez également les informations légales de TVA sous la rubrique, "expédié par Amazon" > "rapports".
';
MLI18n::gi()->{'amazon_config_orderimport__field__import__label'} = '';
MLI18n::gi()->{'amazon_config_orderimport__field__import__hint'} = '';
MLI18n::gi()->{'amazon_config_orderimport__field__preimport.start__label'} = 'Premier lancement de l\'importation';
MLI18n::gi()->{'amazon_config_orderimport__field__preimport.start__hint'} = 'Point de départ du lancement de l\'importation';
MLI18n::gi()->{'amazon_config_orderimport__field__preimport.start__help'} = 'Les commandes seront importées à partir de la date que vous saisissez dans ce champ. Veillez cependant à ne pas donner une date trop éloignée dans le temps pour le début de l’importation, car les données sur les serveurs d’Amazon ne peuvent être conservées, que quelques semaines au maximum. <br>
<br>
<b>Attention</b> : les commandes non importées ne seront après quelques semaines plus importables!';
MLI18n::gi()->{'amazon_config_orderimport__field__customergroup__label'} = 'Groupes clients';
MLI18n::gi()->{'amazon_config_orderimport__field__customergroup__hint'} = '';
MLI18n::gi()->{'amazon_config_orderimport__field__customergroup__help'} = 'Vous pouvez choisir ici un groupe dans lesquel vos clients Amazon seront classés. Pour créer des groupes, rendez-vous dans le menu de l\'administration de votre boutique PrestaShop ->Clients ->Groupes. Lorsqu\'ils sont créés, ils apparaissent dans la liste de choix proposée. ';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.open__label'} = 'Statut de la commande boutique';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.open__hint'} = '';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.open__help'} = '               Définissez ici, Le statut qui sera automatiquement attribué aux commandes importé d\'Amazon vers votre boutique. <br>
Si vous utilisez un système interne de gestion des créances, il est recommandé, de définir le statut de la commande comme étant "payé".';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.fba__label'} = 'Statut pour les commandes FBA';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.fba__hint'} = '';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.fba__help'} = 'Une commande FBA est une commande livrée par le service d\'expédition d’Amazon.
Cette fonction est uniquement réservée aux commerçants qui y ont souscrit.<br>
<br>
Définissez ici, le statut qui sera automatiquement attribué aux commandes importé d\'Amazon vers votre boutique. <br>
Si vous utilisez un système interne de gestion des créances, il est recommandé, de définir le statut de la commande comme étant "payé". <br>

';
MLI18n::gi()->{'amazon_config_orderimport__field__orderimport.fbablockimport__label'} = 'Importation de commandes FBA';
MLI18n::gi()->{'amazon_config_orderimport__field__orderimport.fbablockimport__valuehint'} = 'N\'importez pas les commandes FBA';
MLI18n::gi()->{'amazon_config_orderimport__field__orderimport.fbablockimport__help'} = '<b>Ne pas importer les commandes via Amazon FBA</b><br />.
                <br />
                Vous avez la possibilité d\'empêcher l\'importation de commandes FBA dans votre boutique.
                <br />
                Cochez la case pour activer cette fonctionnalité et l\'importation des commandes exclura toute commande FBA.
                <br />
                Si vous retirez à nouveau la coche, les nouvelles commandes FBA seront importées comme d\'habitude.
                <br />
                Notes importantes :
                <br />
                    <li>Si vous activez cette fonction, toutes les autres fonctions FBA dans le cadre de l\'importation de la commande ne sont pas disponibles pour vous pour ce moment.</li>
                </ul>
';
MLI18n::gi()->{'amazon_config_orderimport__field__orderimport.fbapaymentmethod__label'} = 'Mode de paiement de la commande FBA';
MLI18n::gi()->{'amazon_config_orderimport__field__orderimport.fbapaymentmethod__help'} = 'Mode de paiement qui sera attribué à toutes les commandes expédiées par Amazon. Valeur par défaut : "Amazon".<br><br>
Ce réglage est important pour l\'impression des bons de livraison et des factures, mais aussi pour le traitement ultérieur des commandes dans votre boutique ainsi que dans votre gestion des marchandises.';
MLI18n::gi()->{'amazon_config_orderimport__field__orderimport.fbapaymentmethod__hint'} = '';
MLI18n::gi()->{'amazon_config_orderimport__field__orderimport.fbashippingmethod__label'} = 'Mode de livraison de la commande FBA';
MLI18n::gi()->{'amazon_config_orderimport__field__orderimport.fbashippingmethod__help'} = 'Mode de livraison qui sera attribué à chaque commande effectuée sur Amazon. Valeur par défaut : "amazon"<br><br>
Ce réglage est important pour l\'impression des bons de livraison et des factures, mais aussi pour le traitement ultérieur des commandes dans votre boutique ainsi que dans votre gestion des marchandises.';
MLI18n::gi()->{'amazon_config_orderimport__field__orderimport.fbashippingmethod__hint'} = '';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.carrier__label'} = 'Société de livraison';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.carrier__help'} = '';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.carrier__hint'} = 'Sélectionnez la société d’expédition qui sera indiquée dans toutes les commandes Amazon. Cette information doit obligatoirement être renseignée. Pour plus d’informations consultez l’infobulle.';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.carrier.additional__label'} = '&nbsp;&nbsp;&nbsp;&nbsp;Transporteurs supplémentaires';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.carrier.additional__help'} = 'Amazon propose plusieurs transporteurs standards à la présélection. Vous pouvez agrandir cette liste.
Entrez alors d\'autres transporteurs, séparés par une virgule, dans la zone de texte.';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.cancelled__label'} = 'Annulation de la commande si';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.cancelled__hint'} = '';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.cancelled__help'} = 'Définissez ici le statut de la commamde en boutique, qui doit automatiquement annuler la commande  sur Amazon. <br>
<br>
Remarque : Une annulation partielle de commande via l\'API n\'est pas proposée par Amazon. La commande toute entière est annulée avec cette fonctionnalité et est créditée à l\'acheteur.';
MLI18n::gi()->{'amazon_config_orderimport__field__orderimport.amazonpromotionsdiscount__label'} = 'Amazon Promotions';
MLI18n::gi()->{'amazon_config_orderimport__field__orderimport.amazonpromotionsdiscount__help'} = '<p>magnalister importe les articles réduits dans le cadre des promotions Amazon. Dans les commandes importées la réduction sur le prix de l’article ainsi que celle sur l’expédition sont chacune affichées dans la commande en tant que produits.</p> <p>Dans cette option, vous pouvez définir vos propres numéros de poste pour ces remises promotionnelles.</p>';
MLI18n::gi()->{'amazon_config_orderimport__field__orderimport.amazonpromotionsdiscount.products_sku__label'} = 'Référence pour remise sur l’article';
MLI18n::gi()->{'amazon_config_orderimport__field__orderimport.amazonpromotionsdiscount.shipping_sku__label'} = 'Référence pour remise sur l’expédition';
MLI18n::gi()->{'amazon_config_orderimport__field__orderimport.amazoncommunicationrules.blacklisting__label'} = 'Directives de communication Amazon';
MLI18n::gi()->{'amazon_config_orderimport__field__orderimport.amazoncommunicationrules.blacklisting__valuehint'} = 'blacklister l’adresse e-mail Amazon de l’acheteur';
MLI18n::gi()->{'amazon_config_orderimport__field__orderimport.amazoncommunicationrules.blacklisting__help'} = '
<b>Éviter d\'envoyer des notifications d\'expédition aux acheteurs</b><br />
<br />
Les politiques de communication d’Amazon en vigueur stipulent entre autres que les vendeurs ne doivent pas communiquer directement aux acheteurs des informations sur l’expédition.<br />
<br />
L’option “blacklister l’adresse e-mail Amazon de l’acheteur” permet d\'empêcher l’envoi d’e-mails à l’acheteur directement depuis votre boutique. De cette façon, les e-mails ne pourront pas être envoyés.<br />
<br />
Si malgré les directives d’Amazon, vous souhaitez toutefois envoyer des e-mails directement à vos acheteurs, veuillez décocher la case “blacklister l’adresse e-mail Amazon de l’acheteur”. Cependant, cela peut avoir pour conséquence que vous soyez bloqué par Amazon. Nous déconseillons de désactiver cette option et déclinons toute responsabilité pour tout dommage qui pourrait survenir.<br />
<br />
Informations importantes :
<ul>
    <li>Le blacklisting des adresses mails Amazon est automatiquement activé. Vous recevrez un mailer deamon (avertissement de votre serveur mail, vous informant que l’e-mail n’a pas pu être envoyé), lorsque votre boutique essaye d’envoyer un e-mail à l’acheteur.<br /><br /></li>
    <li>magnalister ajoute le préfixe “blacklisted-” à l’adresse e-mail  Amazon (e.g. blacklisted-12345@amazon.fr). Si vous souhaitez quand même contacter l’acheteur Amazon, supprimez le préfixe “blacklisted-”.</li>
</ul>
';
MLI18n::gi()->{'amazon_config_emailtemplate__legend__mail'} = '{#i18n:configform_emailtemplate_legend#}';
MLI18n::gi()->{'amazon_config_emailtemplate__field__mail.send__label'} = '{#i18n:configform_emailtemplate_field_send_label#}';
MLI18n::gi()->{'amazon_config_emailtemplate__field__mail.send__help'} = '{#i18n:configform_emailtemplate_field_send_help#}';
MLI18n::gi()->{'amazon_config_emailtemplate__field__mail.originator.name__label'} = 'Nom de l\'expéditeur';
MLI18n::gi()->{'amazon_config_emailtemplate__field__mail.originator.adress__label'} = 'Adresse de l\'expéditeur';
MLI18n::gi()->{'amazon_config_emailtemplate__field__mail.subject__label'} = 'Objet';
MLI18n::gi()->{'amazon_config_emailtemplate__field__mail.content__label'} = 'Contenu de l\'E-mail';
MLI18n::gi()->{'amazon_config_emailtemplate__field__mail.content__hint'} = 'Liste des champs disponibles pour "objet" et "contenu".
        <dl>
                <dt>#MARKETPLACEORDERID#</dt>
                        <dd>Marketplace Order Id</dd>
                <dt>#FIRSTNAME#</dt>
                        <dd>Prénom de l\'acheteur</dd>
                <dt>#LASTNAME#</dt>
                        <dd>Nom de l\'acheteur</dd>
                <dt>#EMAIL#</dt>
                        <dd>Adresse E-Mail de l\'acheteur</dd>
                <dt>#PASSWORD#</dt>
                        <dd>Mot de passe de l\'acheteur pour vous connecter à votre boutique. Seulement pour les clients qui seront automatiquement placés, sinon l\'espace réservé sera remplacé par \'(comme on le sait)\'.</dd>
                <dt>#ORDERSUMMARY#</dt>
                        <dd>Résumé des articles achetés. Devrait être à part dans une ligne.<br/><i>Ne peut pas être utilisé dans la ligne objet!</i></dd>
                <dt>#ORIGINATOR#</dt>
                        <dd>Nom de l\'expéditeur</dd>
        </dl>';
MLI18n::gi()->{'amazon_config_emailtemplate__field__mail.copy__label'} = 'Copie à l\'expéditeur';
MLI18n::gi()->{'amazon_config_emailtemplate__field__mail.copy__help'} = 'Activez cette fonction si vous souhaitez recevoir une copie du courriel.';
MLI18n::gi()->{'amazon_config_shippinglabel__legend__shippingaddresses'} = 'Adresses de livraison';
MLI18n::gi()->{'amazon_config_shippinglabel__legend__shippingservice'} = 'Paramètres de livraison';
MLI18n::gi()->{'amazon_config_shippinglabel__legend__shippinglabel'} = 'Options de livraisons';
MLI18n::gi()->{'amazon_config_shippinglabel__field__shippinglabel.address__label'} = 'Adresse de livraison';
MLI18n::gi()->{'amazon_config_shippinglabel__field__shippinglabel.address.name__label'} = 'Nom';
MLI18n::gi()->{'amazon_config_shippinglabel__field__shippinglabel.address.company__label'} = 'Nom de la société';
MLI18n::gi()->{'amazon_config_shippinglabel__field__shippinglabel.address.streetandnr__label'} = 'Rue et numéro de rue';
MLI18n::gi()->{'amazon_config_shippinglabel__field__shippinglabel.address.city__label'} = 'Ville';
MLI18n::gi()->{'amazon_config_shippinglabel__field__shippinglabel.address.state__label'} = 'Land / Canton';
MLI18n::gi()->{'amazon_config_shippinglabel__field__shippinglabel.address.zip__label'} = 'Code postale';
MLI18n::gi()->{'amazon_config_shippinglabel__field__shippinglabel.address.country__label'} = 'Pays';
MLI18n::gi()->{'amazon_config_shippinglabel__field__shippinglabel.address.phone__label'} = 'Numéro de téléphone ';
MLI18n::gi()->{'amazon_config_shippinglabel__field__shippinglabel.address.email__label'} = 'Adresse e-Mail';
MLI18n::gi()->{'amazon_config_shippinglabel__field__shippingservice.carrierwillpickup__label'} = 'Enlèvement de colis';
MLI18n::gi()->{'amazon_config_shippinglabel__field__shippingservice.carrierwillpickup__default'} = 'false';
MLI18n::gi()->{'amazon_config_shippinglabel__field__shippingservice.deliveryexperience__label'} = 'Conditions de livraison';
MLI18n::gi()->{'amazon_config_shippinglabel__field__shippinglabel.fallback.weight__label'} = 'Poids alternatif';
MLI18n::gi()->{'amazon_config_shippinglabel__field__shippinglabel.fallback.weight__help'} = 'Si sur l\'un de vos articles le poids n\'est pas indiqué cette valeur sera appliquée.';
MLI18n::gi()->{'amazon_config_shippinglabel__field__shippinglabel.weight.unit__label'} = 'Unité de mesure pour le poids ';
MLI18n::gi()->{'amazon_config_shippinglabel__field__shippinglabel.size.unit__label'} = 'Unité de mesure pour la taille';
MLI18n::gi()->{'amazon_config_shippinglabel__field__shippinglabel.default.dimension__label'} = 'Taille de colis personnalisée';
MLI18n::gi()->{'amazon_config_shippinglabel__field__shippinglabel.default.dimension.text__label'} = 'Dénomination';
MLI18n::gi()->{'amazon_config_shippinglabel__field__shippinglabel.default.dimension.length__label'} = 'Longueur ';
MLI18n::gi()->{'amazon_config_shippinglabel__field__shippinglabel.default.dimension.width__label'} = 'Largeur';
MLI18n::gi()->{'amazon_config_shippinglabel__field__shippinglabel.default.dimension.height__label'} = 'Hauteur';

// Amazon VCS
MLI18n::gi()->{'amazon_config_account_vcs'} = 'Factures | VCS';
MLI18n::gi()->{'amazon_config_vcs__legend__amazonvcs'} = 'Solution de facturation automatisée d’Amazon';
MLI18n::gi()->{'amazon_config_vcs__legend__amazonvcsinvoice'} = 'Données pour la création des factures via magnalister';
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcs.option__label'} = 'Réglages effectués sur Amazon';
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcs.option__values__off'} = 'Je ne participe pas au programme de facturation automatisée Amazon';
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcs.option__values__vcs'} = 'Réglage Amazon: Amazon génère automatiquement mes factures TVA';
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcs.option__values__vcs-lite'} = 'Réglage Amazon: Je télécharge mes propres factures TVA';
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcs.option__hint'} = 'L’option définie ici doit correspondre à votre sélection dans le programme de facturation Amazon (définie dans votre espace vendeur)';
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcs.option__help'} = '
    Veuillez sélectionner sous quelle forme vous participez au programme de facturation Amazon. Le paramétrage de base doit être effectué dans votre espace vendeur Amazon.
    <br>
    Avec magnalister, trois options pour la transmission de vos factures à Amazon s’offrent à vous :
    <ol>
        <li>
            Je ne participe pas au programme de facturation automatisée Amazon<br>
            <br>
            Si vous avez décidé de ne pas participer au programme de facturation automatisée d’Amazon, veuillez sélectionner cette option. Vous pouvez toujours définir si, et comment vous souhaitez télécharger vos factures sur Amazon sous la rubrique “Transmission des factures“. Cependant, vous ne bénéficiez pas des avantages du programme de facturation (obtention d’une certification sous forme d’un badge vendeur et meilleure visibilité).<br>
            <br>
        </li>
        <li>
            Réglage Amazon: Amazon génère automatiquement mes factures TVA<br>
            <br>
            La facturation et le calcul de la TVA sont entièrement pris en charge par Amazon. Le paramétrage doit être effectué directement dans votre espace vendeur Amazon.<br>
            <br>
        </li>
        <li>
            Réglage Amazon: Je télécharge mes propres factures TVA<br>
            <br>
            Sélectionnez cette option si vous souhaitez télécharger vos factures créées dans votre boutique ou par magnalister (la création des factures peut être configurée dans la rubrique “Transmission des factures“). Dans ce cas Amazon ne prends en charge que le calcul de la TVA. Le paramétrage doit être effectué directement dans votre espace vendeur Amazon.<br>
            <br>
        </li>
    </ol>
    <br>
    Notes importantes :
    <ul>
        <li>A chaque importation des commandes, magnalister vérifie si une facture a été créé pour les commandes importées via magnalister et les transmet à Amazon.<br><br></li>
        <li>Si le montant de la taxe sur les ventes d\'une ou de plusieurs factures diffère de celui d\'Amazon, vous recevrez un E-Mail de magnalister (tous les jours entre 9 et 10 h) contenant toutes les données pertinentes telles que le numéro de commande Amazon, le numéro de commande Amazon et les données relatives à la taxe sur les ventes.<br><br></li>
    </ul>
';
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcs.invoice__label'} = 'Transmission des factures';
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcs.invoice__values__'} = 'Veuillez choisir';
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcs.invoice__values__off'} = 'Ne pas transmettre les factures à Amazon';
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcs.invoice__values__webshop'} = 'Transmettre les factures créées dans la boutique';
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcs.invoice__values__magna'} = 'Charger magnalister de la création et de la transmission des factures';
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcs.invoice__values__erp'} = 'Transmettre les factures créées dans un système tiers (par ex. PGI) à Amazon';
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcs.invoice__help'} = '
    Choisissez ici comment vous souhaitez envoyer vos factures à Amazon :
    <ol>
        <li>
            <p>Ne pas transmettre les factures à Amazon</p>
            <p>Si vous sélectionnez cette option, vos factures ne seront pas transmises à Amazon. Cela signifie que vous devrez télécharger vos factures par vos propres soins.</p>
        </li>
        <li>
            <p>Transmettre les factures créées dans la boutique</p>
            <p>Si votre système de e-boutique prend en charge la création des factures, vous pouvez les télécharger sur Amazon.</p>
        </li>  
        <li>
            <p>Charger magnalister de la création et de la transmission des factures</p>
            <p>Sélectionnez cette option si vous souhaitez que magnalister prenne en charge la création et la transmission des factures. Pour ce faire, veuillez remplir les champs sous “Données pour la création des factures via magnalister”.</p>
        </li>        
        <li>
            <p>Les factures créées par des systèmes tiers (par exemple un système ERP) sont transmises à Amazon.</p>
            <p>Les factures que vous avez créées à l’aide d’un système tiers (par exemple un système ERP) peuvent être déposées sur le serveur de votre boutique en ligne, récupérées par magnalister et chargées sur la plateforme Amazon. Des informations complémentaires apparaissent après le choix de cette option dans l’icône info sous “Paramètres pour la transmission des factures créées à partir d’un système tiers [...]”.</p>
        </li> 
    </ol>
';
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcsinvoice.invoicedir__label'} = 'Factures téléchargées';
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcsinvoice.invoicedir__buttontext'} = 'Afficher';
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcsinvoice.mailcopy__label'} = 'Copie de la facture à';
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcsinvoice.mailcopy__hint'} = 'Entrez votre adresse email pour recevoir une copie de la facture téléchargée sur Amazon';
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcsinvoice.invoiceprefix__label'} = 'Préfixe numéro de facture';
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcsinvoice.invoiceprefix__hint'} = 'Si vous définissez un préfixe, celui-ci sera placé automatiquement devant le numéro de facture. Exemple : R10000. Le numéro des factures générées par magnalister commence par 10000';
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcsinvoice.invoiceprefix__default'} = 'R';
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcsinvoice.invoicenumber__label'} = 'Numéro de facture';
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcsinvoice.invoicenumber__help'} = '<p>
Choisissez ici si les numéros de facture doivent être générés par magnalister ou si vous voulez qu’ils soient extraits d’un champ de texte libre de Shopware.
</p><p>
<b>Charger magnalister de la création des numéros de commande</b>
</p><p>
Lors de la création des factures par magnalister, des numéros de factures consécutifs sont automatiquement générés. Saisissez ici un préfixe qui sera automatiquement placé devant le numéro de facture.
Exemple : F10000
</p><p>
Note : Les commandes créées par magnalister commencent par le numéro 10000.
</p><p>
<b>Tirer le numéro de commande d’un champ de texte libre Shopware</b>
</p><p>
Lors de la création de la facture, le numéro de commande est tirée du champ de texte libre Shopware que vous avez sélectionné.
</p><p>
Vous pouvez créer des champs de texte libre dans votre backend Shopware sous "Configuration" -> "Gestion des champs de texte libre" (tableau : commande) et les remplir sous "Clients" -> "Commandes". Pour ce faire, ouvrez la commande correspondante et faites défiler vers le bas jusqu’à "champs de texte libre".
</p><p>
<b>Important :</b> <br/>l’agrandisseur génère et transmet la facture dès que la commande est marquée comme expédiée. Veuillez vous assurer que le champ de texte libre est bien rempli, sinon une erreur sera causée (voir l’onglet "Journal des erreurs").
<br/><br/>
Si vous utilisez la correspondance des champs de texte libre, la société Magnalister n’est pas responsable de la création correcte et consécutive des numéros de facture.
</p>';
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcsinvoice.invoicenumberoption__label'} = '';
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcsinvoice.invoicenumber.matching__label'} = 'Shopware order free text field';
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcsinvoice.reversalinvoiceprefix__label'} = 'Préfixe numéro de facture d\'annulation';
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcsinvoice.reversalinvoiceprefix__hint'} = 'Si vous définissez un préfixe, celui-ci sera placé automatiquement devant le numéro de facture d\'annulation. Exemple : S20000. Le numéro des factures d\'annulation générées par magnalister commence par 20000';
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcsinvoice.reversalinvoiceprefix__default'} = 'S';
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcsinvoice.reversalinvoicenumber__label'} = 'Reversal invoice number';
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcsinvoice.reversalinvoicenumber__help'} = '<p>
Choose here if you want to have your reversal invoice numbers generated by magnalister or if you want them to be taken from a Shopware free text field.
</p><p>
<b>Create reversal invoice numbers via magnalister</b>
</p><p>
magnalister generates consecutive reversal invoice numbers during the invoice creation. You can define a prefix that is set in front of the reversal invoice number. Example: R10000.
</p><p>
Note: Invoices created by magnalister start with the number 10000.
</p><p>
<b>Match reversal invoice numbers with Shopware free text field</b>
</p><p>
When creating the invoice, the value is taken from the Shopware free text field you selected.
</p><p>
You can create free text fields in your Shopware backend under "Configuration" -> "Free text field management" (table: order) and fill them under "Customers" -> "Orders". To do so, open the corresponding order and scroll down to "free text fields".
</p><p>
<b>Important:</b><br/> magnalister generates and transmits the invoice as soon as the order is marked as shipped. Please make sure that the free text field is filled, otherwise an error will be caused (see tab "Error Log").
<br/><br/>
If you use free text field matching, magnalister is not responsible for the correct, consecutive creation of reversal invoice numbers.
</p>';
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcsinvoice.reversalinvoicenumberoption__label'} = MLI18n::gi()->{'amazon_config_vcs__field__amazonvcsinvoice.invoicenumberoption__label'};
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcsinvoice.reversalinvoicenumber.matching__label'} = MLI18n::gi()->{'amazon_config_vcs__field__amazonvcsinvoice.invoicenumber.matching__label'};
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcsinvoice.companyadressleft__label'} = 'Adresse de l’entreprise (champ d’adresse de gauche)';
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcsinvoice.companyadressleft__default'} = 'Your name, Your street 1, 12345 Your town';
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcsinvoice.companyadressright__label'} = 'Champ d’adresse de l\'entreprise (droite)';
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcsinvoice.companyadressright__default'} = "Your name\nYour street 1\n\n12345 Your town";
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcsinvoice.headline__label'} = 'Intitulé de la facture';
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcsinvoice.headline__default'} = 'Votre facture';
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcsinvoice.invoicehintheadline__label'} = 'Intitulé : notes de facturation';
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcsinvoice.invoicehintheadline__default'} = 'Notes de facturation';
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcsinvoice.invoicehinttext__label'} = 'Texte d\'information';
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcsinvoice.invoicehinttext__hint'} = 'Laissez le champ vide si aucune information ne doit figurer sur la facture.';
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcsinvoice.invoicehinttext__default'} = 'Votre texte d\'information pour la facture';
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcsinvoice.footercell1__label'} = 'Pied de page colonne 1';
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcsinvoice.footercell1__default'} = "Your name\nYour street 1\n\n12345 Your town";
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcsinvoice.footercell2__label'} = 'Pied de page colonne 2';
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcsinvoice.footercell2__default'} = "Your telephone number\nYour fax number\nYour homepage\nYour e-mail";
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcsinvoice.footercell3__label'} = 'Pied de page colonne 3';
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcsinvoice.footercell3__default'} = "Your tax number\nYour Ust. ID. No.\nYour jurisdiction\nYour details";
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcsinvoice.footercell4__label'} = 'Pied de page colonne 4';
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcsinvoice.footercell4__default'} = "Additional\nInformation\nin the fourth\ncolumn";
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcsinvoice.preview__label'} = 'Aperçus de la facture';
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcsinvoice.preview__buttontext'} = 'Aperçus';
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcsinvoice.preview__hint'} = 'Vous pouvez ici afficher un aperçu de votre facture avec les données que vous avez saisies.';

// New Shipment Options
MLI18n::gi()->{'amazon_config_carrier_option_group_marketplace_carrier'} = 'Sociétés de livraison Amazon';
MLI18n::gi()->{'amazon_config_carrier_option_group_shopfreetextfield_option_carrier'} = 'Charger la société de livraison  depuis un champ libre dans la commande de votre boutique';
MLI18n::gi()->{'amazon_config_carrier_option_group_shopfreetextfield_option_shipmethod'} = 'Charger le service d\'expédition depuis un champ de texte libre de votre boutique';
MLI18n::gi()->{'amazon_config_carrier_option_group_additional_option'} = 'Options supplémentaires';
MLI18n::gi()->{'amazon_config_carrier_option_matching_option_carrier'} = 'Apparier les sociétés de livraison Amazon avec les sociétés de livraison de votre boutique';
MLI18n::gi()->{'amazon_config_carrier_option_matching_option_shipmethod'} = 'Apparier les services d’expédition Amazon avec les les services d’expédition de votre boutique';
MLI18n::gi()->{'amazon_config_carrier_option_database_option'} = 'Correspondance des bases de données';
MLI18n::gi()->{'amazon_config_carrier_option_orderfreetextfield_option'} = 'magnalister ajoute un champ supplémentaire dans les détails de la commande';
MLI18n::gi()->{'amazon_config_carrier_option_freetext_option_carrier'} = 'Entrer le nom de la société de livraison manuellement dans un champ ';
MLI18n::gi()->{'amazon_config_carrier_option_freetext_option_shipmethod'} = 'Entrer le nom du service d’expédition manuellement dans un champ';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.carrier.freetext__label'} = 'Société de livraison :';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.carrier.freetext__placeholder'} = 'Veuillez saisir le nom du service de livraison ici';
MLI18n::gi()->{'amazon_config_carrier_matching_title_marketplace_carrier'} = 'Sociétés de livraisons Amazon';
MLI18n::gi()->{'amazon_config_carrier_matching_title_marketplace_shipmethod'} = 'Freitext Versandart';
MLI18n::gi()->{'amazon_config_carrier_matching_title_shop_carrier'} = 'Société de livraison définie dans votre boutique';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.shipmethod__label'} = 'Service d’expédition';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.shipmethod__hint'} = 'Sélectionnez le service s’expédition qui sera attribué à toutes les commandes Amazon. Le service d’expédition doit obligatoirement être renseigné.';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.shipmethod.freetext__label'} = 'Service d’expédition :';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.shipmethod.freetext__placeholder'} = 'Entrez le mode d’expédition ici';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.shippedaddress__label'} = 'Confirmer l’expédition avec l’adresse d’expédition';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.shippedaddress__help'} = '
Sous "Statut (état) de la commande", sélectionnez le statut (l’état) de la commande qui confirme automatiquement la livraison sur Amazon.<br>
<br>
À droite, vous pouvez saisir l\'adresse à partir de laquelle les articles seront expédiés. Cela est utile si l\'adresse d\'expédition doit être différente de l\'adresse par défaut enregistrée dans Amazon (par exemple, lorsque expédition est faite à partir d\'un centre de logistique).<br>
<br>
Si vous laissez les champs d\'adresse vides, Amazon utilisera l\'adresse de l\'expéditeur que vous avez spécifiée dans vos paramètres d\'expédition Amazon (Seller Central).
';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.shippedaddress.name__label'} = 'Nom de l’entrepôt / du centre logistique';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.shippedaddress.line1__label'} = 'Adresse 1ère ligne';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.shippedaddress.line2__label'} = 'Adresse 2ème ligne';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.shippedaddress.line3__label'} = 'Adresse 3ème ligne';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.shippedaddress.city__label'} = 'Ville';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.shippedaddress.county__label'} = 'Comté';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.shippedaddress.stateorregion__label'} = 'Département / région';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.shippedaddress.postalcode__label'} = 'Code postal';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.shippedaddress.countrycode__label'} = 'Pays';
