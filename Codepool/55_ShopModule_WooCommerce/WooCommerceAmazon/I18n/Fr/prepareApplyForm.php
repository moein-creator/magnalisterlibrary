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

MLI18n::gi()->{'amazon_prepare_apply_form__field__bulletpoints__hint'} = 'Puces suivies de mots clés figurant sur la fiche produit de vos articles (par exemple « bracelets montre dorés », « design raffiné »).
<br><br>
Le contenu de la description courte du produit sera utilisé pour remplir les mots clés. Une nouvelle puce sera ajoutée pour chaque suite de mots séparée par une virgule (500 caractères maximum).';
MLI18n::gi()->{'amazon_prepare_apply_form__field__bulletpoints__optional__checkbox__labelNegativ'} = 'Toujours utiliser le contenu du champ de la boutique (Description courte du produit)';

MLI18n::gi()->{'amazon_prepare_apply_form__field__keywords__help'} = '<h3>Optimisez le classement de vos offres avec les mots-clés Amazon</h3>
<br>
Grâce aux mots-clés Amazon, les vendeurs peuvent optimiser le référencement de leurs produits sur la place de marché. Les mots-clés Amazon ne sont pas affichés dans la description du produit, mais sont stockés de manière invisible sur la fiche produit Amazon.
<br><br>
<h2>Prise en charge des mots-clés Amazon avec magnalister</h2>

1. Toujours utiliser les mots-clés actuels de la boutique (Étiquettes produit) : 
<br><br>
Si cette case est cochée, magnalister charge les mots-clés depuis le champ “Metakeywords” de vos fiches produits PrestaShop.
<br><br>
2. Saisir les mots-clés manuellement dans la préparation : 
<br><br>
Si vous ne souhaitez pas utiliser les mots-clés meta de vos fiches produits Amazon, vous pouvez les saisir manuellement dans le champ dédié dans la préparation.
<br><br>
<b>Remarques importantes :</b>
<ul><li>
Lorsque vous saisissez les mots-clés manuellement, veuillez les séparer par un espace (pas une virgule !) et assurez-vous que la taille totale ne dépasse pas 250 octets (en général 1 caractère = 1 octet à l’exception des caractères spéciaux tels que Ä, Ö, Ü = 2 octet) .
</li><li>
Si les mots-clés meta dans la fiche produit de votre boutique sont séparés par des virgules, magnalister converti automatiquement les virgules en espaces lors du téléchargement du produit. Vous n\'avez donc rien à changer dans votre boutique.
</li><li>
Si le nombre d\'octets autorisé est dépassé, Amazon peut renvoyer un message d\'erreur après le téléchargement du produit (les messages d’erreurs peuvent être consultés dans l’onglet “Rapports d’erreurs”). Veuillez noter qu\'il peut s\'écouler jusqu\'à 60 minutes avant que des messages d\'erreur ne soient chargés dans l’onglet “Rapports d’erreurs”.
</li><li>
Si vous êtes vendeur Platinum sur amazon, veuillez en informer le service support de magnalister pour que nous puissions débloquer cette option dans votre plugin. Une fois débloqués, magnalister transmet les mots-clés “normaux” en tant que mots-clés Platinum.
</li><li>
Si vous souhaitez envoyer des mots-clés Platinum différents des mots-clés normaux, veuillez utiliser l’appariement d’attributs dans la préparation de l’article et dans le menu déroulant des attributs facultatifs Amazon, sélectionner "Mots-clés platine 1-5" pour faire un mapping avec le champ de vos fiches produit de votre choix.
</li><li>
Il y d’autres types de mots-clés tels que les mots-clés thesaurus (“thesaurus_attribute_keywords”), les mots-clés groupe cible (“target_audience_keywords”), les mots-clés spécifiques (“specific_uses_keywords”) ou les mots-clé de thème (“subject_keywords”) que vous pouvez également sélectionner dans la liste des attributs facultatifs Amazon.
</li></ul>
';
MLI18n::gi()->{'amazon_prepare_apply_form__field__keywords__optional__checkbox__labelNegativ'} = 'Toujours utiliser les mots clés actuels de la boutique (Étiquettes produit)';