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

MLI18n::gi()->{'dummymodule_config_general_mwstoken_help'} = 'Pour pouvoir transmettre vos données, Amazon demande une authentification. 
Saisissez ici, votre numéro d’accès MWS Token.<br>
<br>
Vous trouverez dans l’article ci-dessous, l’information pour savoir où se procurer le MWS Token :<br>
<a href="https://otrs.magnalister.com/otrs/public.pl?Action=PublicFAQZoom;ItemID=1000" title="Amazon MWS" target="_blank">Comment générer un Token MWS Amazon?</a>';
MLI18n::gi()->{'dummymodule_config_general_autosync'} = 'Synchronisation automatique via CronJob (recommandée)';
MLI18n::gi()->{'dummymodule_config_general_nosync'} = 'Aucune synchronisation';
MLI18n::gi()->{'dummymodule_config_account_title'} = 'Mes coordonnées';
MLI18n::gi()->{'dummymodule_config_account_prepare'} = 'Préparation d\'article';
MLI18n::gi()->{'dummymodule_config_account_price'} = 'Calcul du prix';
MLI18n::gi()->{'dummymodule_configform_orderstatus_sync_values__auto'} = '{#i18n:dummymodule_config_general_autosync#}';
MLI18n::gi()->{'dummymodule_configform_orderstatus_sync_values__no'} = '{#i18n:dummymodule_config_general_nosync#}';
MLI18n::gi()->{'dummymodule_configform_sync_values__auto'} = 'Synchronisation automatique via CronJob (recommandée)';
MLI18n::gi()->{'dummymodule_configform_sync_values__no'} = 'Aucune synchronisation';
MLI18n::gi()->{'dummymodule_configform_stocksync_values__rel'} = 'Une commande (pas de commande FBA) réduit les stocks en boutique (recommandée)';
MLI18n::gi()->{'dummymodule_configform_stocksync_values__fba'} = 'Une commande (également une commande FBA) réduit les stocks en boutique';
MLI18n::gi()->{'dummymodule_configform_stocksync_values__no'} = 'Aucune synchronisation';
MLI18n::gi()->{'dummymodule_configform_pricesync_values__auto'} = 'Synchronisation automatique via CronJob (recommandée)';
MLI18n::gi()->{'dummymodule_configform_pricesync_values__no'} = 'Aucune synchronisation';
MLI18n::gi()->{'dummymodule_configform_orderimport_payment_values__textfield__title'} = 'De la zone de texte';
MLI18n::gi()->{'dummymodule_configform_orderimport_payment_values__textfield__textoption'} = '1';
MLI18n::gi()->{'dummymodule_configform_orderimport_payment_values__Amazon__title'} = 'Amazon';
MLI18n::gi()->{'dummymodule_configform_orderimport_shipping_values__textfield__title'} = 'De la zone de texte';
MLI18n::gi()->{'dummymodule_configform_orderimport_shipping_values__textfield__textoption'} = '1';
MLI18n::gi()->{'dummymodule_config_account_sync'} = 'Synchronisation';
MLI18n::gi()->{'dummymodule_config_account_orderimport'} = 'Importation des commandes';
MLI18n::gi()->{'dummymodule_config_account_emailtemplate'} = '{#i18n:configform_emailtemplate_legend#}';
MLI18n::gi()->{'dummymodule_config_account_shippinglabel'} = 'Etiquettes d\'expédition';
MLI18n::gi()->{'dummymodule_config_account_emailtemplate_sender'} = 'Nom de votre boutique, de votre société, ...';
MLI18n::gi()->{'dummymodule_config_account_emailtemplate_sender_email'} = 'exemple@votre-boutique.fr';
MLI18n::gi()->{'dummymodule_config_account_emailtemplate_subject'} = 'Votre commande sur #SHOPURL#';
MLI18n::gi()->{'dummymodule_config_account_emailtemplate_content'} = ' <style><!--
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
MLI18n::gi()->{'dummymodule_config_tier_error'} = 'Amazon Business (B2B): votre configuration n\'est pas correcte!';
MLI18n::gi()->{'dummymodule_config_account__legend__account'} = 'Mes coordonnées';
MLI18n::gi()->{'dummymodule_config_account__legend__tabident'} = '';
MLI18n::gi()->{'dummymodule_config_account__field__tabident__label'} = '{#i18n:ML_LABEL_TAB_IDENT#}';
MLI18n::gi()->{'dummymodule_config_account__field__tabident__help'} = '{#i18n:ML_TEXT_TAB_IDENT#}';
MLI18n::gi()->{'dummymodule_config_account__field__username__label'} = 'Adresse courriel';
MLI18n::gi()->{'dummymodule_config_account__field__username__hint'} = '';
MLI18n::gi()->{'dummymodule_config_account__field__password__label'} = 'Mot de passe général du vendeur';
MLI18n::gi()->{'dummymodule_config_account__field__password__help'} = 'Saisissez ici, votre mot de passe Amazon';
MLI18n::gi()->{'dummymodule_config_account__field__mwstoken__label'} = 'MWS Token';
MLI18n::gi()->{'dummymodule_config_account__field__mwstoken__help'} = 'Pour pouvoir transmettre vos données, Amazon demande une authentification. 
Saisissez ici, votre numéro d’accès MWS. <br>
<br>
Pour obtenir ces données, vous devez cliquer sur un des liens correspondant à la localisation de votre place de marché.<br>
<ul>
  <li><a href="https://developer.dummymoduleservices.de/index.html" title="Amazon MWS" target="_blank">MWS Deutschland Website</a></li>
  <li><a href="https://developer.dummymoduleservices.co.uk/index.html" title="Amazon MWS" target="_blank">MWS UK Website</a></li>
  <li><a href="https://developer.dummymoduleservices.fr/index.html" title="Amazon MWS" target="_blank">MWS France Website</a></li>
  <li><a href="https://developer.dummymoduleservices.com/index.html" title="Amazon MWS" target="_blank">MWS US Website</a></li>
  <li><a href="https://developer.dummymoduleservices.jp/index.html" title="Amazon MWS" target="_blank">MWS Japanese Website</a></li>
</ul>
   <br> <br />
Suivez les instructions et indiquez comme ci dessous : <br>
<br>
Pour les places de marché Amazon europe <br>
&nbsp;&nbsp;Nom de l’ application : magnalister <br>
&nbsp;&nbsp;Numéro de compte de développeur d\'applications : 4141-0616-7444<br>
<br>
Pour Amazon USA <br>
&nbsp;&nbsp;Nom de l’ application : magnalister-us <br>
&nbsp;&nbsp;Numéro de compte de développeur d\'applications : 8260-4311-6738 <br>
<br>';
MLI18n::gi()->{'dummymodule_config_account__field__merchantid__label'} = 'Commerçant-ID';
MLI18n::gi()->{'dummymodule_config_account__field__merchantid__help'} = 'Pour pouvoir transmettre vos données, Amazon demande une authentification. 
Saisissez ici, votre numéro d’identification commerçant (commerçant-ID). <br>
<br>
Pour obtenir ces données, vous devez cliquer sur un des liens correspondant à la localisation de votre place de marché.<br>
<ul><li><a href="https://developer.dummymoduleservices.de/index.html" title="Amazon MWS" target="_blank">MWS Deutschland Website</a></li>
                               <li><a href="https://developer.dummymoduleservices.co.uk/index.html" title="Amazon MWS" target="_blank">MWS UK Website</a></li>
                               <li><a href="https://developer.dummymoduleservices.fr/index.html" title="Amazon MWS" target="_blank">MWS France Website</a></li>
                               <li><a href="https://developer.dummymoduleservices.com/index.html" title="Amazon MWS" target="_blank">MWS US Website</a></li>
                               <li><a href="https://developer.dummymoduleservices.jp/index.html" title="Amazon MWS" target="_blank">MWS Japanese Website</a></li>
                           </ul>
                           <br /><br />
   
Suivez les instructions et indiquez comme ci dessous : <br>
<br>
Pour les places de marché Amazon europe <br>
&nbsp;&nbsp;Nom de l’ application : magnalister  <br>
&nbsp;&nbsp;Numéro de compte de développeur d\'applications : 4141-0616-7444<br>
<br>
Pour Amazon USA <br>
&nbsp;&nbsp;Nom de l’ application : magnalister-us <br>
&nbsp;&nbsp;Numéro de compte de développeur&nbsp;d\'applications : 8260-4311-6738 <br>
<br />
';
MLI18n::gi()->{'dummymodule_config_account__field__marketplaceid__label'} = 'Place de marché-ID';
MLI18n::gi()->{'dummymodule_config_account__field__marketplaceid__help'} = 'Pour pouvoir transmettre vos données, Amazon demande une authentification. <br>
Saisissez ici, votre numéro d’identification de place de marché (place de marché-ID). <br>
<br />
Pour obtenir ces données, vous devez cliquer sur un des liens correspondant à la localisation de votre place de marché.

                              <ul><li><a href="https://developer.dummymoduleservices.de/index.html" title="Amazon MWS" target="_blank">MWS Deutschland Website</a></li>
                               <li><a href="https://developer.dummymoduleservices.co.uk/index.html" title="Amazon MWS" target="_blank">MWS UK Website</a></li>
                               <li><a href="https://developer.dummymoduleservices.fr/index.html" title="Amazon MWS" target="_blank">MWS France Website</a></li>
                               <li><a href="https://developer.dummymoduleservices.com/index.html" title="Amazon MWS" target="_blank">MWS US Website</a></li>
                               <li><a href="https://developer.dummymoduleservices.jp/index.html" title="Amazon MWS" target="_blank">MWS Japanese Website</a></li>
                           </ul>
                           <br /><br />
   
Suivez les instructions et indiquez comme ci dessous : <br>
<br />
Pour les places de marché Amazon europe <br>
&nbsp;&nbsp;Nom de l’ application : magnalister <br>
&nbsp;&nbsp;Numéro de compte de développeur d\'applications : 4141-0616-7444<br>
<br />
Pour Amazon USA <br>
&nbsp;&nbsp;Nom de l’ application : magnalister-us <br>
&nbsp;&nbsp;Numéro de compte de développeur d\'applications : 8260-4311-6738li/li';
MLI18n::gi()->{'dummymodule_config_account__field__site__label'} = 'Localisation du site d\'Amazon';
MLI18n::gi()->{'dummymodule_config_prepare__legend__prepare'} = 'Préparation d\'article';
MLI18n::gi()->{'dummymodule_config_prepare__legend__machingbehavior'} = 'Comportement lors de l\'appariement';
MLI18n::gi()->{'dummymodule_config_prepare__legend__apply'} = 'Création de nouveaux produits';
MLI18n::gi()->{'dummymodule_config_prepare__legend__shipping'} = 'Expédition';
MLI18n::gi()->{'dummymodule_config_prepare__legend__upload'} = 'Préréglages de téléchargement';
MLI18n::gi()->{'dummymodule_config_prepare__legend__shippingtemplate'} = 'Profils d’expédition par région d’expédition';
MLI18n::gi()->{'dummymodule_config_prepare__legend__b2b'} = 'Amazon Business (B2B)';
MLI18n::gi()->{'dummymodule_config_prepare__field__prepare.status__label'} = 'Statut de filtre';
MLI18n::gi()->{'dummymodule_config_prepare__field__prepare.status__valuehint'} = 'Ne prendre en charge que les articles actifs';
MLI18n::gi()->{'dummymodule_config_prepare__field__checkin.status__label'} = 'Statut du filtre';
MLI18n::gi()->{'dummymodule_config_prepare__field__checkin.status__valuehint'} = 'Ne prendre en charge que les articles actifs';
MLI18n::gi()->{'dummymodule_config_prepare__field__lang__label'} = 'Description de l\'article';
MLI18n::gi()->{'dummymodule_config_prepare__field__internationalshipping__label'} = 'Expédition';
MLI18n::gi()->{'dummymodule_config_prepare__field__multimatching__label'} = 'Appariez de nouveau';
MLI18n::gi()->{'dummymodule_config_prepare__field__multimatching__valuehint'} = 'Remplacez les produits déjà appariés lors du multi et auto appariement.';
MLI18n::gi()->{'dummymodule_config_prepare__field__multimatching__help'} = 'Si vous avez activé ce paramètre, les produits déjà appariés seront remplacés par les nouveaux correspondants.';
MLI18n::gi()->{'dummymodule_config_prepare__field__multimatching.itemsperpage__label'} = 'Résultats';
MLI18n::gi()->{'dummymodule_config_prepare__field__multimatching.itemsperpage__help'} = 'Ici, vous pouvez définir le nombre de produits par page lorsque le Multi appariement s\'affiche.<br/> Plus le nombre est important, plus long sera le chargement (pour 50 résultats comptez environ 30 secondes).';
MLI18n::gi()->{'dummymodule_config_prepare__field__multimatching.itemsperpage__hint'} = 'Par page lors du multi appariement ';
MLI18n::gi()->{'dummymodule_config_prepare__field__prepare.manufacturerfallback__label'} = 'Fabricant par défaut';
MLI18n::gi()->{'dummymodule_config_prepare__field__prepare.manufacturerfallback__help'} = 'Amazon demande de spécifier les fabricants des produits vendus sur leurs places de marché. Si pour un produit vous n’avez pas spécifié de fabricant, celui que vous pouvez donner dans cette rubrique  sera alors automatiquement utilisé.<br>
<br>
Sous l\'onglet "Configuration globale" "Propriétés du produit", vous pouvez apparier (Matcher) des „fabricants“ à vos attributs.';
MLI18n::gi()->{'dummymodule_config_prepare__field__quantity__label'} = 'Variation de stock';
MLI18n::gi()->{'dummymodule_config_prepare__field__quantity__help'} = 'Cette rubrique vous permet d’indiquer les quantités disponibles d’un article de votre stock, pour une place de marché particulière. <br>
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
MLI18n::gi()->{'dummymodule_config_prepare__field__leadtimetoship__label'} = 'Délai de traitement (en jours)';
MLI18n::gi()->{'dummymodule_config_prepare__field__leadtimetoship__help'} = 'Temps qui s\'écoule entre le moment où l\'acheteur passe commande et celui où vous remettez la commande à votre transporteur.<br>Si vous ne donnez ici aucune valeur, le délai de traitement, par défaut, entre 1 et 2 jours ouvrables.
Utilisez ce champ si le délai de traitement pour un article dépasse les deux jours ouvrables prévus.';
MLI18n::gi()->{'dummymodule_config_prepare__field__checkin.skuasmfrpartno__label'} = 'Réference fabricant';
MLI18n::gi()->{'dummymodule_config_prepare__field__checkin.skuasmfrpartno__help'} = 'Le numéro SKU sera transmit comme numéro d\'article du fabricant';
MLI18n::gi()->{'dummymodule_config_prepare__field__checkin.skuasmfrpartno__valuehint'} = 'Le numéro SKU sera utilisé comme référence fabricant';
MLI18n::gi()->{'dummymodule_config_prepare__field__imagesize__label'} = 'Taille d\'image';
MLI18n::gi()->{'dummymodule_config_prepare__field__imagesize__help'} = '<p>Indiquez ici, la largeur en pixels de l\'image, que vous souhaitez avoir sur la place de marché.
La hauteur sera ajustée automatiquement aux caractéristiques de la page d\'origine.</p>
<p>
Les fichiers source seront modifiés à partir du dossier image {#setting:sSourceImagePath#} et déposés avec la largeur en pixels désirée dans le dossier {#setting:sImagePath#} pour la transmission à la place de marché.';
MLI18n::gi()->{'dummymodule_config_prepare__field__imagesize__hint'} = 'Enregistrée sous: {#setting:sImagePath#}-';
MLI18n::gi()->{'dummymodule_config_prepare__field__shipping.template.active__label'} = 'Utiliser les profils de Paramètres d’expédition par régions d’expédition.';
MLI18n::gi()->{'dummymodule_config_prepare__field__shipping.template.active__help'} = 'Les vendeurs peuvent créer des profils de réglages d\'expédition différents, selon leurs exigences et les coûts en vigueurs. 
Selon les régions, des profils particuliers de condition d\'expédition peuvent être établis, - conditions et/ou coût d\'expédition, différents. 
Lorsque le vendeur prépare un produit, il peut donner un des profils de réglages d\'expédition au préalable défini pour le produit à préparer. Les réglages d\'expédition de ce profil sont alors utilisés. Si aucun profil n’est mentionné les conditions d’expéditions standards seront utilisées.';
MLI18n::gi()->{'dummymodule_config_prepare__field__shipping.template__label'} = 'profil de Paramètres d’expédition par région d’expédition';
MLI18n::gi()->{'dummymodule_config_prepare__field__shipping.template__hint'} = 'Profil étbli pour une offre particulière. 
<br>Les profils de Paramètres d’expédition par région d’expédition sont déterminés et gérés par les vendeurs';
MLI18n::gi()->{'dummymodule_config_prepare__field__shipping.template__help'} = 'Les vendeurs peuvent créer des profils de réglages d\'expédition différents, selon leurs exigences et les coûts en vigueurs. 
Selon les régions, des profils particuliers de condition d\'expédition peuvent être établis, - conditions et/ou coût d\'expédition, différents. 
Lorsque le vendeur prépare un produit, il peut donner un des profils de réglages d\'expédition au préalable défini pour le produit à préparer. Les réglages d\'expédition de ce profil sont alors utilisés. Si aucun profil n’est mentionné les conditions d’expéditions standards seront utilisées.';
MLI18n::gi()->{'dummymodule_config_prepare__field__shipping.template.name__label'} = 'Nom du profil de Paramètres d’expédition par région d’expédition.';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2bactive__label'} = 'Activer l\'option Business';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2bactive__help'} = 'Pour pouvoir utiliser cette fonctionnalité, vous devez être inscrit au programme Amazon Business Seller. Si vous n\'êtes pas encore affilié à ce programme, connectez-vous d\'abord à votre compte Amazon vendeur et activez l\'option : Business seller. <br />
La seule exigence est d\'avoir un «compte Amazon vendeur professionnel », si ce n\'est pas le cas, vous pouvez mettre à niveau votre compte existant).
<br /><br />
Lisez, s\'il vous plaît la note, en cliquant sur l\'icône d\'information de la rubrique : "importation de commande" > "Activer l\'importation des commandes".';
MLI18n::gi()->{'dummymodule_config_prepare__field__itemcondition__label'} = 'Item Condition';
MLI18n::gi()->{'dummymodule_config_price__legend__price'} = 'Calcul du prix';
MLI18n::gi()->{'dummymodule_config_price__field__price__label'} = 'Prix';
MLI18n::gi()->{'dummymodule_config_price__field__price__hint'} = '';
MLI18n::gi()->{'dummymodule_config_price__field__price__help'} = 'Veuillez saisir un pourcentage, un prix majoré, un rabais ou un prix fixe prédéfini. 
Pour indiquer un rabais faire précéder le chiffre d’un moins. ';
MLI18n::gi()->{'dummymodule_config_price__field__price.addkind__label'} = '';
MLI18n::gi()->{'dummymodule_config_price__field__price.addkind__hint'} = '';
MLI18n::gi()->{'dummymodule_config_price__field__price.factor__label'} = '';
MLI18n::gi()->{'dummymodule_config_price__field__price.factor__hint'} = '';
MLI18n::gi()->{'dummymodule_config_price__field__price.signal__label'} = 'Champ décimal';
MLI18n::gi()->{'dummymodule_config_price__field__price.signal__hint'} = 'Champ décimal';
MLI18n::gi()->{'dummymodule_config_price__field__price.signal__help'} = '                Cette zone de texte sera utilisée dans les transmissions de données vers la place de Amazon, (prix après la virgule).<br/><br/>

                <strong>Par exemple :</strong> <br /> 
                 Valeur dans la zone de texte: 99 <br />
                 Prix d\'origine: 5.58 <br />
                 Prix final: 5.99 <br /><br />
                 La fonction aide en particulier, pour les majorations ou les rabais en pourcentage sur les prix. <br/>
                 Laissez le champ vide si vous souhaitez ne pas transmettre de prix avec une virgule.<br/>
                 Le format d\'entrée est un chiffre entier avec max. 2 chiffres.';
MLI18n::gi()->{'dummymodule_config_price__field__priceoptions__label'} = 'Options de tarification ';
MLI18n::gi()->{'dummymodule_config_price__field__priceoptions__help'} = '<p>Avec cette fonction, vous pouvez transmettre vos prix promotionnels aux places de marché, selon les groupes clients, que vous avez déterminés dans l’espace de gestion de votre boutique et opérer leurs synchronisations automatiques. Si vous n’avez pas défini de prix, pour l’un de vos groupes clients, le prix de votre boutique sera utilisé. Ainsi, vous pouvez simplement allouer un prix différent à une certaine quantité d’article, pour un groupe particulier de clients, tout en conservant les configurations initiales inhérentes à ce prix, pour un autre groupe de clients.</p>
<ul>
<li>Créez un groupe de clients dans votre boutique en ligne, par exemple : “clients réguliers”</li>
<li>Vous pouvez alors définir les prix souhaités pour ces groupes de clients et ainsi de suite.</li>
</ul>';
MLI18n::gi()->{'dummymodule_config_price__field__priceoptions__hint'} = '';
MLI18n::gi()->{'dummymodule_config_price__field__price.group__label'} = '';
MLI18n::gi()->{'dummymodule_config_price__field__price.group__hint'} = '';
MLI18n::gi()->{'dummymodule_config_price__field__price.usespecialoffer__label'} = 'Utilisez également des tarifs spéciaux';
MLI18n::gi()->{'dummymodule_config_price__field__price.usespecialoffer__hint'} = '';
MLI18n::gi()->{'dummymodule_config_price__field__exchangerate_update__label'} = 'Taux de change';
MLI18n::gi()->{'dummymodule_config_price__field__exchangerate_update__hint'} = 'Mise à jour automatique du taux de change';
MLI18n::gi()->{'dummymodule_config_price__field__exchangerate_update__help'} = 'Si la devise utilisé dans votre boutique en ligne est différente de celle de la place de marché, magnalister calcule le taux de change par rapport au taux que vous avez défini dans votre boutique en ligne. <br>
<br>
En activant cette fonction, le taux de change actuel défini par "alphavantage" sera appliqué à vos articles. Les prix dans votre boutique en ligne seront également mis à jour.<br>
<br>
L’activation et la désactivation de cette fonction prend effet toutes les heures.<br>
<br>
<b>Avertissement :</b> RedGecko GmbH n\'assume aucune responsabilité pour l\'exactitude du taux de change. Veuillez vérifier en contrôlant les prix de vos articles dans votre compte Amazon.            ';
MLI18n::gi()->{'dummymodule_config_price__field__exchangerate_update__alert'} = 'Si la devise utilisé dans votre boutique en ligne est différente de celle de la place de marché, magnalister calcule le taux de change par rapport au taux que vous avez défini dans votre boutique en ligne. <br>
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
MLI18n::gi()->{'dummymodule_config_sync__legend__sync'} = 'Synchronisation de l\'inventaire';
MLI18n::gi()->{'dummymodule_config_sync__field__stocksync.tomarketplace__label'} = 'Variation du stock boutique';
MLI18n::gi()->{'dummymodule_config_sync__field__stocksync.tomarketplace__hint'} = '';
MLI18n::gi()->{'dummymodule_config_sync__field__stocksync.tomarketplace__help'} = 'Utilisez la fonction “synchronisation automatique”, pour synchroniser votre stock Amazon et votre stock boutique. L’actualisation de base se fait toutes les quatre heures, - à moins que vous n’ayez définit d’autres paramètres - et commence à 00:00 heure. Si la synchronisation est activée, les données de votre base de données seront appliquées à Amazon.
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
MLI18n::gi()->{'dummymodule_config_sync__field__stocksync.frommarketplace__label'} = 'Variation du stock Amazon';
MLI18n::gi()->{'dummymodule_config_sync__field__stocksync.frommarketplace__hint'} = '';
MLI18n::gi()->{'dummymodule_config_sync__field__stocksync.frommarketplace__help'} = 'Si cette fonction est activée, le nombre de commandes effectués et payés sur Amazon, sera soustrait de votre stock boutique.<br>
<br>
<b>Important :</b> Cette fonction n’est opérante que lors de l’importation des commandes.
';
MLI18n::gi()->{'dummymodule_config_sync__field__inventorysync.price__label'} = 'Prix de l\'article';
MLI18n::gi()->{'dummymodule_config_sync__field__inventorysync.price__hint'} = '';
MLI18n::gi()->{'dummymodule_config_sync__field__inventorysync.price__help'} = '<b>Synchronisation automatique via CronJob (recommandée)</b><br>
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
MLI18n::gi()->{'dummymodule_config_orderimport__legend__importactive'} = 'Importation des commandes';
MLI18n::gi()->{'dummymodule_config_orderimport__legend__mwst'} = 'TVA';
MLI18n::gi()->{'dummymodule_config_orderimport__legend__orderstatus'} = 'Paramètre de synchronisation des commandes boutiques vers Amazon';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderstatus.shipped__label'} = 'Expédition confirmée si :';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderstatus.shipped__hint'} = '';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderstatus.shipped__help'} = 'Définissez ici le statut de la commande, qui doit automatiquement confirmer la livraison  sur Amazon.';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderstatus.canceled__label'} = 'Annuler la commande avec';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderstatus.canceled__hint'} = '';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderstatus.canceled__help'} = '        Définissez  ici le statut de la boutique, qui doit "annuler la commande" automatiquement sur Amazon. <br/><br/>
                Remarque : une annulation partielle est impossible ici. La commande tout entière est annulée avec cette fonctionnalité et est créditée à l\'acheteur.';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderimport.shop__label'} = '{#i18n:form_config_orderimport_shop_lable#}';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderimport.shop__hint'} = '';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderimport.shop__help'} = '{#i18n:form_config_orderimport_shop_help#}';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderimport.paymentmethod__label'} = 'Mode de paiement pour les commandes';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderimport.paymentmethod__help'} = '<p>Mode de paiement, qui sera associé à toutes les commandes sur Amazon, lors de l\'importation des commandes. 
Standard: "Classement automatique"</p>
<p>
Si vous sélectionnez „Classement automatique", magnalister reprend le mode de paiement, que l\'acheteur a choisi sur Amazon.
</p>
<p>
Ce paramètre est important pour les factures, l\'impression des bons de livraison, le traitement ultérieur de la commande en magasin, ainsi que dans la gestion des marchandises.</p>';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderimport.paymentmethod__hint'} = '';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderimport.shippingmethod__label'} = 'Mode de livraison des commandes';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderimport.shippingmethod__help'} = 'Mode de livraison, qui sera associé à toutes les commandes sur Amazon. Standard: "marketplace".<br><br>
				           Ce paramètre est important pour les factures, l\'impression des bons de livraison, le traitement ultérieur de la commande en magasin, ainsi que dans la gestion des marchandises';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderimport.shippingmethod__hint'} = '';
MLI18n::gi()->{'dummymodule_config_orderimport__field__mwstfallback__label'} = 'TVA des articles non référencés en boutique ';
MLI18n::gi()->{'dummymodule_config_orderimport__field__mwstfallback__hint'} = 'Le taux d\'imposition lors d\'une importation de commandes d\'articles ne venant pas de la boutique sera alors calculé en %.';
MLI18n::gi()->{'dummymodule_config_orderimport__field__mwstfallback__help'} = 'Si pour un article, la TVA n’a pas été spécifiée, vous pouvez ici donner un taux, qui sera automatiquement appliquée à l’importation. Les places de marché même ne donnent aucune indication de TVA.<br>
Par principe, pour l’importation des commandes et la facturation, magnalister applique le même système de TVA que celui configuré par les boutiques. <br>
Afin que les TVA nationales soient automatiquement prisent en compte, il faut que l’article acheté soit trouvé grâce à son numéro d’unité de gestion des stocks (SKU); magnalister utilisant alors la TVA configurée dans la boutique. ';
MLI18n::gi()->{'dummymodule_config_orderimport__field__importactive__label'} = 'Activer l\'importation des commandes';
MLI18n::gi()->{'dummymodule_config_orderimport__field__importactive__hint'} = '';
MLI18n::gi()->{'dummymodule_config_orderimport__field__importactive__help'} = 'Lorsque la fonction est activée, les commandes sont par défaut importées toutes les heures.<br />
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
MLI18n::gi()->{'dummymodule_config_orderimport__field__import__label'} = '';
MLI18n::gi()->{'dummymodule_config_orderimport__field__import__hint'} = '';
MLI18n::gi()->{'dummymodule_config_orderimport__field__preimport.start__label'} = 'Premier lancement de l\'importation';
MLI18n::gi()->{'dummymodule_config_orderimport__field__preimport.start__hint'} = 'Point de départ du lancement de l\'importation';
MLI18n::gi()->{'dummymodule_config_orderimport__field__preimport.start__help'} = 'Les commandes seront importées à partir de la date que vous saisissez dans ce champ. Veillez cependant à ne pas donner une date trop éloignée dans le temps pour le début de l’importation, car les données sur les serveurs d’Amazon ne peuvent être conservées, que quelques semaines au maximum. <br>
<br>
<b>Attention</b> : les commandes non importées ne seront après quelques semaines plus importables!';
MLI18n::gi()->{'dummymodule_config_orderimport__field__customergroup__label'} = 'Groupes clients';
MLI18n::gi()->{'dummymodule_config_orderimport__field__customergroup__hint'} = '';
MLI18n::gi()->{'dummymodule_config_orderimport__field__customergroup__help'} = 'Vous pouvez choisir ici un groupe dans lesquel vos clients Amazon seront classés. Pour créer des groupes, rendez-vous dans le menu de l\'administration de votre boutique PrestaShop ->Clients ->Groupes. Lorsqu\'ils sont créés, ils apparaissent dans la liste de choix proposée. ';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderstatus.open__label'} = 'Statut de la commande boutique';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderstatus.open__hint'} = '';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderstatus.open__help'} = '               Définissez ici, Le statut qui sera automatiquement attribué aux commandes importé d\'Amazon vers votre boutique. <br>
Si vous utilisez un système interne de gestion des créances, il est recommandé, de définir le statut de la commande comme étant "payé".';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderstatus.fba__label'} = 'Statut pour les commandes FBA';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderstatus.fba__hint'} = '';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderstatus.fba__help'} = 'Une commande FBA est une commande livrée par le service d\'expédition d’Amazon.
Cette fonction est uniquement réservée aux commerçants qui y ont souscrit.<br>
<br>
Définissez ici, le statut qui sera automatiquement attribué aux commandes importé d\'Amazon vers votre boutique. <br>
Si vous utilisez un système interne de gestion des créances, il est recommandé, de définir le statut de la commande comme étant "payé". <br>

';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderimport.fbapaymentmethod__label'} = 'Mode de paiement de la commande FBA';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderimport.fbapaymentmethod__help'} = 'Mode de paiement qui sera attribué à toutes les commandes expédiées par Amazon. Valeur par défaut : "Amazon".<br><br>
Ce réglage est important pour l\'impression des bons de livraison et des factures, mais aussi pour le traitement ultérieur des commandes dans votre boutique ainsi que dans votre gestion des marchandises.';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderimport.fbapaymentmethod__hint'} = '';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderimport.fbashippingmethod__label'} = 'Mode de livraison de la commande FBA';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderimport.fbashippingmethod__help'} = 'Mode de livraison qui sera attribué à chaque commande effectuée sur Amazon. Valeur par défaut : "dummymodule"<br><br>
Ce réglage est important pour l\'impression des bons de livraison et des factures, mais aussi pour le traitement ultérieur des commandes dans votre boutique ainsi que dans votre gestion des marchandises.';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderimport.fbashippingmethod__hint'} = '';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderstatus.carrier.default__label'} = '&nbsp;&nbsp;&nbsp;&nbsp;Transporteurs Standards';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderstatus.carrier.default__help'} = 'Transporteur principal automatiquement proposé à la confirmation de la commande.';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderstatus.carrier.additional__label'} = '&nbsp;&nbsp;&nbsp;&nbsp;Transporteurs supplémentaires';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderstatus.carrier.additional__help'} = 'Amazon propose plusieurs transporteurs standards à la présélection. Vous pouvez agrandir cette liste.
Entrez alors d\'autres transporteurs, séparés par une virgule, dans la zone de texte.';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderstatus.cancelled__label'} = 'Annulation de la commande si';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderstatus.cancelled__hint'} = '';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderstatus.cancelled__help'} = 'Définissez ici le statut de la commamde en boutique, qui doit automatiquement annuler la commande  sur Amazon. <br>
<br>
Remarque : Une annulation partielle de commande via l\'API n\'est pas proposée par Amazon. La commande toute entière est annulée avec cette fonctionnalité et est créditée à l\'acheteur.';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderimport.dummymodulepromotionsdiscount__label'} = 'Amazon Promotions';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderimport.dummymodulepromotionsdiscount__help'} = '<p>magnalister importe les rabais d\'Amazon Promotions en tant que produits indépendants dans votre boutique en ligne. Un produit est créé dans la commande importée pour chaque produit et remise d\'expédition.</p>
                           <p>Dans cette option de paramétrage, vous pouvez définir vos propres numéros de poste pour ces remises promotionnelles.</p>';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderimport.dummymodulepromotionsdiscount.products_sku__label'} = 'Remise d\'article - Numéro d\'article';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderimport.dummymodulepromotionsdiscount.shipping_sku__label'} = 'Remise d\'expédition - Numéro d\'article';
MLI18n::gi()->{'dummymodule_config_emailtemplate__legend__mail'} = '{#i18n:configform_emailtemplate_legend#}';
MLI18n::gi()->{'dummymodule_config_emailtemplate__field__mail.send__label'} = '{#i18n:configform_emailtemplate_field_send_label#}';
MLI18n::gi()->{'dummymodule_config_emailtemplate__field__mail.send__help'} = '{#i18n:configform_emailtemplate_field_send_help#}';
MLI18n::gi()->{'dummymodule_config_emailtemplate__field__mail.originator.name__label'} = 'Nom de l\'expéditeur';
MLI18n::gi()->{'dummymodule_config_emailtemplate__field__mail.originator.adress__label'} = 'Adresse de l\'expéditeur';
MLI18n::gi()->{'dummymodule_config_emailtemplate__field__mail.subject__label'} = 'Objet';
MLI18n::gi()->{'dummymodule_config_emailtemplate__field__mail.content__label'} = 'Contenu de l\'E-mail';
MLI18n::gi()->{'dummymodule_config_emailtemplate__field__mail.content__hint'} = 'Liste des champs disponibles pour "objet" et "contenu".
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
MLI18n::gi()->{'dummymodule_config_emailtemplate__field__mail.copy__label'} = 'Copie à l\'expéditeur';
MLI18n::gi()->{'dummymodule_config_emailtemplate__field__mail.copy__help'} = 'Activez cette fonction si vous souhaitez recevoir une copie du courriel.';
MLI18n::gi()->{'dummymodule_config_shippinglabel__legend__shippingaddresses'} = 'Adresses de livraison';
MLI18n::gi()->{'dummymodule_config_shippinglabel__legend__shippingservice'} = 'Paramètres de livraison';
MLI18n::gi()->{'dummymodule_config_shippinglabel__legend__shippinglabel'} = 'Options de livraisons';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.address__label'} = 'Adresse de livraison';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.address.name__label'} = 'Nom';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.address.company__label'} = 'Nom de la société';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.address.streetandnr__label'} = 'Rue et numéro de rue';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.address.city__label'} = 'Ville';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.address.state__label'} = 'Land / Canton';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.address.zip__label'} = 'Code postale';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.address.country__label'} = 'Pays';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.address.phone__label'} = 'Numéro de téléphone ';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.address.email__label'} = 'Adresse e-Mail';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippingservice.carrierwillpickup__label'} = 'Enlèvement de colis';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippingservice.carrierwillpickup__default'} = 'false';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippingservice.deliveryexperience__label'} = 'Conditions de livraison';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.fallback.weight__label'} = 'Poids alternatif';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.fallback.weight__help'} = 'Si sur l\'un de vos articles le poids n\'est pas indiqué cette valeur sera appliquée.';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.weight.unit__label'} = 'Unité de mesure pour le poids ';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.size.unit__label'} = 'Unité de mesure pour la taille';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.default.dimension__label'} = 'Taille de colis personnalisée';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.default.dimension.text__label'} = 'Dénomination';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.default.dimension.length__label'} = 'Longueur ';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.default.dimension.width__label'} = 'Largeur';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.default.dimension.height__label'} = 'Hauteur';
