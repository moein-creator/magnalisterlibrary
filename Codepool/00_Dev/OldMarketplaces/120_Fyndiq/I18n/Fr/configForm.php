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

MLI18n::gi()->{'fyndiq_config_account_title'} = 'Mes coordonnées';
MLI18n::gi()->{'fyndiq_config_account_prepare'} = 'Préparation d\'article';
MLI18n::gi()->{'fyndiq_config_account_price'} = 'Calcul du prix';
MLI18n::gi()->{'fyndiq_config_account_sync'} = 'Synchronisation du stock';
MLI18n::gi()->{'fyndiq_config_account_orderimport'} = 'Importation des commandes';
MLI18n::gi()->{'fyndiq_config_checkin_badshippingcost'} = 'La valeur saisie doit être de type numérique.';
MLI18n::gi()->{'fyndiq_config_account_emailtemplate'} = '{#i18n:configform_emailtemplate_legend#}';
MLI18n::gi()->{'fyndiq_config_account_emailtemplate_sender'} = 'Nom de votre boutique, de votre société, ...';
MLI18n::gi()->{'fyndiq_config_account_emailtemplate_sender_email'} = 'exemple@votre-boutique.fr';
MLI18n::gi()->{'fyndiq_config_account_emailtemplate_subject'} = 'Votre commande sur #SHOPURL#';
MLI18n::gi()->{'fyndiq_config_account_producttemplate'} = 'Gabarit pour fiche de produit';
MLI18n::gi()->{'fyndiq_config_account__legend__account'} = 'Mes coordonnées';
MLI18n::gi()->{'fyndiq_config_account__legend__tabident'} = '';
MLI18n::gi()->{'fyndiq_config_account__field__tabident__label'} = '{#i18n:ML_LABEL_TAB_IDENT#}';
MLI18n::gi()->{'fyndiq_config_account__field__tabident__help'} = '{#i18n:ML_TEXT_TAB_IDENT#}';
MLI18n::gi()->{'fyndiq_config_account__field__mpusername__label'} = 'Nom d\'utilisateur';
MLI18n::gi()->{'fyndiq_config_account__field__mppassword__label'} = 'Mot de passe';
MLI18n::gi()->{'fyndiq_config_account__field__mpapitoken__label'} = 'Clés de l\'API médias';
MLI18n::gi()->{'fyndiq_config_account__field__mpapitoken__help'} = 'Rendez vous sur <a href="https://fyndiq.de/merchant/settings/api/" target="_blank">page</a> et cliquez sur créer un  compte. Une fois l\'enregistrement terminé, connectez vous et rendez vous sur "Réglages" -> "API". Cliquez su "generate API v2 token" pour générer  le jeton et copiez le contenu du champs. Le nom d\'utilisateur est le même que le pseudo de votre compte Fyndiq.';
MLI18n::gi()->{'fyndiq_config_prepare__legend__prepare'} = 'Préparation de l\'article';
MLI18n::gi()->{'fyndiq_config_prepare__legend__upload'} = 'Préréglages pour le téléchargement d\'article';
MLI18n::gi()->{'fyndiq_config_prepare__field__prepare.status__label'} = 'Statut du filtre';
MLI18n::gi()->{'fyndiq_config_prepare__field__prepare.status__valuehint'} = 'Ne prendre en charge que les articles actifs';
MLI18n::gi()->{'fyndiq_config_prepare__field__lang__label'} = 'Description de l\'article';
MLI18n::gi()->{'fyndiq_config_prepare__field__imagepath__label'} = 'Chemin d\'accès des images';
MLI18n::gi()->{'fyndiq_config_prepare__field__identifier__label'} = 'Identifieur';
MLI18n::gi()->{'fyndiq_config_prepare__field__vat__label'} = 'Comparateur des classes d\'impôt';
MLI18n::gi()->{'fyndiq_config_prepare__field__vat__hint'} = '';
MLI18n::gi()->{'fyndiq_config_prepare__field__vat__matching__titlesrc'} = 'Taux d\'imposition boutique';
MLI18n::gi()->{'fyndiq_config_prepare__field__vat__matching__titledst'} = 'Taux d\'imposition Fyndiq';
MLI18n::gi()->{'fyndiq_config_prepare__field__shippingcost__label'} = 'Frais de port (EUR)';
MLI18n::gi()->{'fyndiq_config_prepare__field__checkin.status__label'} = 'Filtre (Statut)';
MLI18n::gi()->{'fyndiq_config_prepare__field__checkin.status__valuehint'} = 'Ne prendre en charge que les articles actifs';
MLI18n::gi()->{'fyndiq_config_prepare__field__checkin.quantity__label'} = 'Gestion du stock';
MLI18n::gi()->{'fyndiq_config_prepare__field__checkin.quantity__help'} = 'Cette rubrique vous permet d’indiquer les quantités disponibles d’un article de votre stock, pour une place de marché particulière. <br>
<br>
Elle vous permet aussi de gérer le problème de ventes excédentaires. Pour cela activer dans la liste de choix, la fonction : "reprendre le stock de l\'inventaire en boutique, moins la valeur du champ de droite". <br>
Cette option ouvre automatiquement un champ sur la droite, qui vous permet de donner des quantités à exclure de la comptabilisation de votre inventaire général, pour les réserver à un marché particulier. <br>
<br>
<b>Exemple :</b> Stock en boutique : 10 (articles) &rarr; valeur entrée: 2 (articles) &rarr; Stock alloué à Fyndiq: 8 (articles).<br>
<br>
<b>Remarque :</b> Si vous souhaitez cesser la vente sur Fyndiq, d’un article que vous avez encore en stock, mais que vous avez désactivé de votre boutique, procédez comme suit :
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
MLI18n::gi()->{'fyndiq_config_prepare__field__customshipping__keytitle'} = 'Information d\'expédition';
MLI18n::gi()->{'fyndiq_config_prepare__field__customshipping__valuetitle'} = 'Frais de port';
MLI18n::gi()->{'fyndiq_config_prepare__field__imagesize__label'} = 'Taille d\'image';
MLI18n::gi()->{'fyndiq_config_prepare__field__imagesize__help'} = 'Saisissez ici la largeur maximale en pixel, que votre image doit avoir sur votre page. La hauteur sera automatiquement ajustée. <br>
Vos images originales se trouvent dans le dossier image sous l’adresse : <br>shop-root/media/image. Après ajustage, elles sont versées dans le dossier : <br>shop-root/media/image/magnalister, et sont prêtes à être utilisées par les places de marché.';
MLI18n::gi()->{'fyndiq_config_prepare__field__imagesize__hint'} = 'Enregistrée sous: {#setting:sImagePath#}';
MLI18n::gi()->{'fyndiq_config_price__legend__price'} = 'Calcul du prix';
MLI18n::gi()->{'fyndiq_config_price__field__price__label'} = 'Prix';
MLI18n::gi()->{'fyndiq_config_price__field__price__help'} = 'Veuillez saisir un pourcentage, un prix majoré, un rabais ou un prix fixe prédéfini. 
Pour indiquer un rabais faire précéder le chiffre d’un moins. ';
MLI18n::gi()->{'fyndiq_config_price__field__price.addkind__label'} = '';
MLI18n::gi()->{'fyndiq_config_price__field__price.factor__label'} = '';
MLI18n::gi()->{'fyndiq_config_price__field__price.signal__label'} = 'Champ décimal';
MLI18n::gi()->{'fyndiq_config_price__field__price.signal__hint'} = 'Champ décimal';
MLI18n::gi()->{'fyndiq_config_price__field__price.signal__help'} = '                Cette zone de texte sera utilisée dans les transmissions de données vers la place de marché, (prix après la virgule).<br/><br/>

                <strong>Par exemple :</strong> <br /> 
                 Valeur dans la zone de texte: 99 <br />
                 Prix d\'origine: 5.58 <br />
                 Prix final: 5.99 <br /><br />
                 La fonction aide en particulier, pour les majorations ou les rabais en pourcentage sur les prix. <br/>
                 Laissez le champ vide si vous souhaitez ne pas transmettre de prix avec une virgule.<br/>
                 Le format d\'entrée est un chiffre entier avec max. 2 chiffres.';
MLI18n::gi()->{'fyndiq_config_price__field__priceoptions__label'} = 'Options de tarification ';
MLI18n::gi()->{'fyndiq_config_price__field__price.group__label'} = '';
MLI18n::gi()->{'fyndiq_config_price__field__price.usespecialoffer__label'} = 'Utilisez également des tarifs spéciaux';
MLI18n::gi()->{'fyndiq_config_price__field__exchangerate_update__label'} = 'Taux de change';
MLI18n::gi()->{'fyndiq_config_price__field__exchangerate_update__valuehint'} = 'Mise à jour automatique du taux de change';
MLI18n::gi()->{'fyndiq_config_price__field__exchangerate_update__help'} = 'Si la devise utilisé dans votre boutique en ligne est différente de celle de la place de marché, magnalister calcule le taux de change par rapport au taux que vous avez défini dans votre boutique en ligne. <br>
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
MLI18n::gi()->{'fyndiq_config_price__field__exchangerate_update__alert'} = 'Si la devise utilisé dans votre boutique en ligne est différente de celle de la place de marché, magnalister calcule le taux de change par rapport au taux que vous avez défini dans votre boutique en ligne. <br>
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
MLI18n::gi()->{'fyndiq_config_sync__legend__sync'} = 'Synchronisation de l\'inventaire';
MLI18n::gi()->{'fyndiq_config_sync__field__stocksync.tomarketplace__label'} = 'Variation du stock boutique';
MLI18n::gi()->{'fyndiq_config_sync__field__stocksync.tomarketplace__help'} = 'Utilisez la fonction “synchronisation automatique”, pour synchroniser votre stock Fyndiq et votre stock boutique. L’actualisation de base se fait toutes les quatre heures, - à moins que vous n’ayez définit d’autres paramètres - et commence à 00:00 heure. Si la synchronisation est activée, les données de votre base de données seront appliquées à Fyndiq.
Vous pouvez à tous moment effectuer une synchronisation manuelle de votre stock, en cliquant sur le bouton “synchroniser les prix et les stocks”, dans le groupe de boutons en haut à droite de la page. <br>
<br>
Il est aussi possible de synchroniser votre stock en utilisant une fonction CronJob personnelle. Cette fonction n’est disponible qu’à partir du tarif “flat”. Elle vous permet de réduire le délais maximal de  synchronisation de vos données à 15 minutes d\'intervalle. 
Pour opérer la synchronisation utilisez le lien suivant:<br>
{#setting:sSyncInventoryUrl#} <br>
<br>
Attention, toute importation provenant d’un client n’utilisant pas le tarif “flat” ou ne respectant pas le délai de 15 minute sera bloqué. <br>
 <br>
<b>Commande ou modification d’un article; l’état du stock Fyndiq  est comparé avec celui de votre boutique. </b> <br>
Chaque changement dans le stock de votre boutique, lors d’une commande ou de la modification d’un article, sera transmis à Fyndiq. <br>
Attention, les changements ayant lieu uniquement dans votre base de données, c’est-à-dire ne résultant pas d’une action opérée par une place de marché synchronisé ou sur magnalister, <b>ne seront ni pris en compte, ni transmis!</b> <br>
<br>
<b>Commande ou modification d’un article; l’état du stock Fyndiq est modifié (différence)</b> <br>
Si par exemple, un article a été acheté deux fois en boutique, le stock Fyndiq sera réduit de 2 unités. <br>
Si vous modifiez la quantité d’un article dans votre boutique, sous la rubrique “Fyndiq” &rarr; “configuration” &rarr; “préparation d’article”, ce changement sera appliqué sur Fyndiq. <br>
<br>
<b>Attention</b>, les changements ayant lieu uniquement dans votre base de données, c’est-à-dire ne résultant pas d’une action opérée sur une place de marché synchronisé ou sur magnalister, ne seront ni pris en compte, ni transmis!<br>
<br>
<br>
<b>Remarque :</b> Cette fonction n’est effective, que si vous choisissez une de deux première option se trouvant sous la rubrique: Configuration &rarr;  Préparation de l’article &rarr; Préréglages de téléchargement d’article. ';
MLI18n::gi()->{'fyndiq_config_sync__field__stocksync.frommarketplace__label'} = 'Variation du stock Fyndiq';
MLI18n::gi()->{'fyndiq_config_sync__field__stocksync.frommarketplace__help'} = 'Si cette fonction est activée le nombre de commandes effectués et payés sur Fyndiq sera soustrait de votre stock boutique.<br>
<br>
<b>Attention :</b> cette fonction ne s’exécute que si  l’importation des commandes est activée!';
MLI18n::gi()->{'fyndiq_config_sync__field__inventorysync.price__label'} = 'Prix de l&apos;article';
MLI18n::gi()->{'fyndiq_config_sync__field__inventorysync.price__help'} = '                <p>
                    La fonction "synchronisation automatique" compare toutes les 4 heures (à partir de 0:00 dans la nuit) l\'état actuel des prix sur Fyndiq et les prix de votre boutique.<br>
                    Ainsi les valeurs venant de la base de données sont vérifiées et appliquées même si des changements, par exemple, dans la gestion des marchandises, sont seulement réalisés dans la base de données.<br><br> 

                    <b>Remarque :</b> Les réglages sous l\'onglet "Configuration" → "Calcul du prix" seront pris en compte.
                 </p>';
MLI18n::gi()->{'fyndiq_config_orderimport__legend__importactive'} = 'Importation des commandes';
MLI18n::gi()->{'fyndiq_config_orderimport__legend__mwst'} = 'TVA';
MLI18n::gi()->{'fyndiq_config_orderimport__legend__orderstatus'} = 'Synchronisation du statut de la commande de la boutique vers Fyndiq';
MLI18n::gi()->{'fyndiq_config_orderimport__field__orderimport.shop__label'} = '{#i18n:form_config_orderimport_shop_lable#}';
MLI18n::gi()->{'fyndiq_config_orderimport__field__orderimport.shop__hint'} = '';
MLI18n::gi()->{'fyndiq_config_orderimport__field__orderimport.shop__help'} = '{#i18n:form_config_orderimport_shop_help#}';
MLI18n::gi()->{'fyndiq_config_orderimport__field__orderstatus.shipped__label'} = 'Confirmer la livraison avec';
MLI18n::gi()->{'fyndiq_config_orderimport__field__orderstatus.shipped__help'} = 'Définissez ici le statut dan votre boutique, qui doit automatiquement confirmer la livraison sur Fyndiq.';
MLI18n::gi()->{'fyndiq_config_orderimport__field__service__label'} = 'Service de livraison';
MLI18n::gi()->{'fyndiq_config_orderimport__field__service__help'} = 'Fyndiq n\'accepte que certains service de livraison.';
MLI18n::gi()->{'fyndiq_config_orderimport__field__mwst.fallback__label'} = 'TVA';
MLI18n::gi()->{'fyndiq_config_orderimport__field__mwst.fallback__hint'} = 'Le taux d\'imposition lors d\'une importation de commandes d\'articles ne venant pas de la boutique sera alors calculé en %.';
MLI18n::gi()->{'fyndiq_config_orderimport__field__mwst.fallback__help'} = 'Si l\'article n\'a pas été enregistré sur magnalister, la TVA ne peut pas être déterminée.<br />
                 Comme solution alternative, la valeur sera fixée en pourcentage pour chaque produit enregistré, dont la TVA n\'est pas connue par Fyndiq, lors de l\'importation.';
MLI18n::gi()->{'fyndiq_config_orderimport__field__importactive__label'} = 'Activer l\'importation';
MLI18n::gi()->{'fyndiq_config_orderimport__field__importactive__hint'} = '';
MLI18n::gi()->{'fyndiq_config_orderimport__field__importactive__help'} = 'Les importations de commandes doivent elles  être effectuées à partir de la place de marché? <br>
<br>
Si la fonction est activée, les commandes seront automatiquement importées toutes les heures.<br>
<br>
Vous pouvez à tout moment effectuer une synchronisation manuelle de votre stock, en cliquant sur le bouton “synchroniser les prix et les stocks”, dans le groupe de boutons en haut à droite de la page. <br>
<br>
Il est aussi possible de synchroniser votre stock en utilisant une fonction CronJob personnelle. Cette fonction n’est disponible qu’à partir du tarif “flat”. Elle vous permet de réduire le délai maximal de  synchronisation de vos données à 15 minutes d\'intervalle. 
Pour opérer la synchronisation, utilisez le lien suivant : <br>
<i>{#setting:sSyncInventoryUrl#}</i> <br>
<br>

<b>Attention</b>, toute importation provenant d’un client n’utilisant pas le tarif “flat” ou ne respectant pas le délai de 15 minute sera bloqué.
';
MLI18n::gi()->{'fyndiq_config_orderimport__field__import__label'} = '';
MLI18n::gi()->{'fyndiq_config_orderimport__field__preimport.start__label'} = 'Premier lancement de l\'importation';
MLI18n::gi()->{'fyndiq_config_orderimport__field__preimport.start__hint'} = 'Point de départ du lancement de l\'importation';
MLI18n::gi()->{'fyndiq_config_orderimport__field__preimport.start__help'} = 'Les commandes seront importées à partir de la date que vous saisissez dans ce champ. Veillez cependant à ne pas donner une date trop éloignée dans le temps pour le début de l’importation, car les données sur les serveurs de Fyndiq ne peuvent être conservées, que quelques semaines au maximum. <br>
<br>
<b>Attention</b> : les commandes non importées ne seront après quelques semaines plus importables!';
MLI18n::gi()->{'fyndiq_config_orderimport__field__customergroup__label'} = 'Groupe de clients';
MLI18n::gi()->{'fyndiq_config_orderimport__field__customergroup__help'} = 'Vous pouvez choisir ici un groupe dans lesquel vos clients seront classés. Pour créer des groupes, rendez-vous dans le menu de l\'administration de votre boutique PrestaShop ->Clients ->Groupes. Lorsqu\'ils sont créés, ils apparaissent dans la liste de choix proposée. ';
MLI18n::gi()->{'fyndiq_config_orderimport__field__orderstatus.open__label'} = 'Statut de la commande dans votre boutique';
MLI18n::gi()->{'fyndiq_config_orderimport__field__orderstatus.open__help'} = 'Définissez le statut de la commande dans votre boutique qui sera attribué automatiquement aux commandes effectuées sur Fyndiq.<br>
';
MLI18n::gi()->{'fyndiq_config_orderimport__field__orderimport.shippingmethod__label'} = 'Mode d\'expédition des commandes';
MLI18n::gi()->{'fyndiq_config_orderimport__field__orderimport.shippingmethod__help'} = 'fyndiq_config_orderimport__field__orderimport.shippingmethod__help';
