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

// example for overwriting global element
MLI18n::gi()->add('formfields__quantity', array('help' => '{#setting:currentMarketplaceName#} n\'autorise, que la quantité de stock "disponible" ou "non disponible". Indiquez si l\'article doit être offert en fonction de l\'inventaire de votre magasin.<br /><br />Pour éviter les surventes, vous pouvez utiliser la valeur «prendre en charge le stock de la boutique, moins, la valeur du champ ci-contre» et donner une valeur de reserve dans le champ mentionné.<br /><br />Exemple : Stock en boutique : 10 (articles) → valeur entrée: 2 (articles) → Stock alloué à {#setting:currentMarketplaceName#}: 8 (articles).<br /><br />Note: Si, vous voulez que les articles inactifs, indépendamment des quantités disponibles, aient sur le marché une valeur de stock "0", veuillez procéder comme suit:<br /<br />Cliquez sur les onglets “Configuration” → “Synchronisation”;<br />Rubrique “Synchronisation des Inventaires" → "Variation du stock boutique";<br />Activez dans la liste de choix "synchronisation automatique via CronJob",<br />Cliquez sur l’onglet "Configuration globale", Rubrique “Inventaire”,<br />activez<br />"Si le statut du produit est placé comme étant inactif, le niveau des stocks sera alors enregistré comme quantité 0".'));
MLI18n::gi()->add('formfields__stocksync.frommarketplace', array(
    'help' => '
    Note : {#setting:currentMarketplaceName#} ne reconnaît les produits, que "disponible ou "non disponible pour vos offres, en conséquence:<br />
    <br />
    Quantité boutique > 0 = disponible sur {#setting:currentMarketplaceName#}<br />
    Quantité stock < 1 = non disponible sur {#setting:currentMarketplaceName#}<br />
    <br />
    <br />
    Fonction :<br />
    Synchronisation automatique via CronJob (recommandée)<br />
    <br />
    <br />
    La fonction "Synchronisation automatique" ajuste l\'inventaire {#setting:currentMarketplaceName#} actuel à, l\'inventaire boutique, toutes les 4 heures.<br />
    <br />
    Les données sont vérifiées et transférées de la base de données, même si les modifications ont été effectuées directement dans la base de données, par exemple, par un système  de gestion de marchandise.<br />
    <br />
    Vous pouvez déclencher une synchronisation manuelle, en cliquant sur le bouton correspondant, dans le groupe de boutons gris en haut à gauche de l\'en-tête magnalister.<br />
    <br />
    Il est aussi possible de synchroniser votre stock, en utilisant un CronJob personnel. Cela n’est possible qu’à partir du tarif “flat”. CronJob vous permet de réduire le délai maximal de synchronisation de vos données à 15 minutes d\'intervalle. Pour opérer la synchronisation, utilisez le lien suivant :<br />
    <i>{#setting:sSyncInventoryUrl#}</i><br />
    <br />
    Toute importation provenant d’un client n’utilisant pas le tarif “flat” ou ne respectant pas le délai de 15 minutes sera bloqué.<br />
    <br />
    <b>Remarque :</b> les paramètres sous ""Configuration"" → ""Téléchargement d\'article"" ...<br />
    <br />
    → “Limite journalière de commandes"" et<br />
    → “Stock” pour les deux premières options.<br />
    <br />
... sont pris en compte.
'
));
MLI18n::gi()->add('formfields__maxquantity', array(
    'label' => 'Limite journalière de commandes',
    'help'  => '
        Limite autorisée, pour {#setting:currentMarketplaceName#} achat direct, de commande journalière d\'un produit. Vous pouvez régler ici, combien de pièces d\'un article peuvent être vendues par jour, par l\'intermédiaire de l\'achat direct {#setting:currentMarketplaceName#}. Sans cette information, l\'article reste disponible à l\'achat direct, jusqu\'à ce qu\'il soit supprimé ou que ses paramètres soient modifiés.<br />
        <br />
        Ce champ ne doit pas être confondu avec celui concernant votre inventaire ("quantité de stock"). Il s\'agit ici ,d\'une limite journalière, qui n\'est déterminée que pour l\'achat direct {#setting:currentMarketplaceName#}. <br />
        <br />
        <br />
        <strong>Note:</strong><br />
        Les paramètres du champ "quantité de stock" sont pris en compte, jusqu\'à la limite de commande définie ici dans le champ "quantité". Si le paramètre du champ "quantité" est réglé sur "Pourcentage (du champ de droite)", la limite n\'a aucun effet.
    ',
));


MLI18n::gi()->add('formfields_idealo', array(
    'directbuyactive'                 => array(
        'label'  => 'Utiliser l’achat direct idealo',
        'help'   => '
Choisissez ici si vous utiliser l’option achat direct de idealo (“idealo Direktkauf”). Veuillez suivre les étapes suivante pour obtenir les codes d’accès de l\'API v2 :
<ul>
<li>
Connectez-vous à votre <a target="_blank" href="https://business.idealo.com/">compte Business idealo</a>.
</li><li>
Cliquez sur “Direktkauf” -> “Integration” -> “Neuen API Client erstellen”.
</li><li>
Sélectionnez l’option “Neuen Produktiv-Client erstellen”.
</li><li>
Copiez et enregistrez le "Client ID" et le "Client Passwort" générés.
</li>
</ul>
Vous pouvez désormais saisir vos données d’accès dans les champs "idealo Direktkauf Client ID" et "idealo Direktkauf Passwort" et cliquer sur “sauvegarder” 
',
        'values' => array(
            'true'  => 'Oui',
            'false' => 'Non',
        ),
    ),
    'idealoclientid'                  => array(
        'label' => 'ID client pour l\'achat direct {#setting:currentMarketplaceName#}"',
        'help'  => '
            Saisissez le "Client ID" que vous avez généré via votre compte business idealo sous “Direktkauf” -> “Integration” -> “Neuen API Client erstellen”.
<br><br>
Trouvez plus d\'informations à ce sujet dans l\'infobulle à côté de "Utiliser l\’achat direct idealo".
        '
    ),
    'idealopassword'                  => array(
        'label' => 'Mot de passe client pour l\'achat direct {#setting:currentMarketplaceName#}',
        'help'  => '
            Saisissez le "mot de passe client" que vous avez généré via votre compte business idealo sous “Direktkauf” -> “Integration” -> “Neuen API Client erstellen”.
<br><br>
Trouvez plus d\'informations à ce sujet dans l\'infobulle à côté de "Utiliser l’achat direct idealo".
            '
    ),
    'shippingcountry'                 => array(
        'label' => 'Expédié vers',
    ),
    'shippingmethodandcost'           => array(
        'label' => 'Frais d\'expédition',
        'help'  => 'Entrez ici les frais d\'expédition standards de vos articles. Dans "Préparation d\'articles", vous pouvez donner des frais particuliers pour les produits sélectionnés .',
    ),
    'shippingcostmethod'              => array(
        'values' => array(
            '__ml_lump'   => MLI18n::gi()->ML_COMPARISON_SHOPPING_LABEL_LUMP,
            '__ml_weight' => 'Frais d\'expédition = articles poids',
        ),
    ),
    'subheader.pd'                    => array(
        'label' => 'Moteur de recherche de prix et achat direct'
    ),
    'paymentmethod'                   => array(
        'label'  => 'Mode de payement',
        'help'   => '
            indiquez les modes de paiement standard souhaités sur idealo et pour l\'achat direct (multiple possible sélection).<br />
            Sous "préparer les produits", vous pouvez, à tout moment et individuellement, ajuster les modes de paiement  par produit.<br />
            <br />
            Note: {#setting:currentMarketplaceName#} n\'autorise pour l\'achat direct, que le paiement avec PayPal, le transfert instantané ou les cartes de crédit.<br />
            Le mode de paiement, que vous avez sélectionné pour l\'achat direct, sera également affiché sur idealo.
        ',
        'values' => array(
            'Direktkauf & Suchmaschine:' => array(
                'PAYPAL'     => 'PayPal',
                'CREDITCARD' => 'carte de crédit',
                'SOFORT'     => 'Sofort&uuml;berweisung'
            ),
            'Nur Suchmaschine:'          => array(
                'PRE'       => 'paiement anticipé',
                'COD'       => 'paiement à la livraison',
                'BANKENTER' => 'banque entrer',
                'BILL'      => 'facture',
                'GIROPAY'   => 'Giropay',
                'CLICKBUY'  => 'Click&Buy',
                'SKRILL'    => 'Skrill'
            ),
        ),
    ),
    'checkout'                        => array(
        'label' => 'Activer l\'achat direct',
    ),
    'checkoutenabled'                 => array(
        'label' => '{#setting:currentMarketplaceName#} Vente direct',
        'help' => 'Pour utiliser cette fonction, veuillez insérer votre "Client-ID" et "Mot de passe Client" idealo dans le champ approprié de l\'onglet "<a target="_blank" href="{#setting:idealo.activatedirectbuyconfigurl#}">données d’accès</a>"',
    ),
    'oldtokenmigrationpopup'          => array(
        'label' => 'Passez à la version 2 de l\'API d\'achat direct par le commerçant d\'idealo',
        'help'  => 'À partir du 01.01.2021, magnalister prend en charge l\'API v2 d\'achat direct idéalo. L\'API v1 sera bientôt désactivée.

Veuillez générer un "ID client" et un "mot de passe client" dans votre compte idealo business et saisir les données dans la configuration idealo dans magnalister sous "Données d\'accès" -> "Achat direct idealo".

Vous trouverez les instructions pour le passage à la nouvelle API dans l\'infobulle de la fonction "Utiliser l\'achat direct idealo".
',
    ),
    'access.inventorypath'            => array(
        'label' => 'Lien vers votre fichier CSV',
    ),
    'shippingmethod'                  => array(
        'label'  => 'Mode d\'expédition',
        'help'   => 'Spécifiez la méthode d\'expédition qui sera utilisée pour vos devis d\'achat direct.',
        'values' => array(
            'Paketdienst' => 'service de colis',
            'Spedition'   => 'entreprise de transport',
            'Download'    => 'Download',
        ),
    ),
    'twomanhandlingfee' => array(
        'label' => 'Direct-buy - Delivery Costs to the place of installation',
        'help'  => 'Für Artikel mit der Lieferart „Spedition“ kann im idealo Direktkauf vom User die zus&aumltzliche Dienstleistung „Lieferung bis zum Aufstellort“ bestellt werden. Die Ware wird nicht, wie bei der &uumlblichen Spedition, bis zur Bordsteinkante, sondern bis zum gewünschten Aufstellort geliefert. Die angegebene Kosten werden mit dem Gesamtpreis verrechnet. Bitte geben Sie die Kosten inkl. MwSt. an. Bei Aussparung dieses Feldes wird diese Option nicht bei idealo Direktkauf angeboten.',
        'hint'  => 'Applies only to the shipping method "Haulage"',
    ),
    'disposalfee'       => array(
        'label' => 'Direct-buy - Costs for taking old appliances',
        'help'  => 'Für Artikel mit der Lieferart „Spedition“ und der Auswahl „Lieferung bis zum Aufstellort“ kann im idealo Direktkauf vom User die zus&aumltzliche Dienstleistung „Altger&aumltemitnahme“ bestellt werden. Das Altger&aumlt wird vom Spediteur mitgenommen und entsorgt. Die angegebenen Kosten werden mit dem Gesamtpreis verrechnet. Bitte geben Sie die Kosten inkl. MwSt. an. Bei Aussparung dieses Feldes wird diese Option nicht bei idealo Direktkauf angeboten.',
        'hint'  => 'Applies only to the shipping method "Haulage"',
    ),
    'shippingtime'                    => array(
        'label'    => 'délai de livraison',
        'optional' => array(
            'checkbox' => array(//*todo 'labelNegativ' => 'immer aus Konfiguration übernehmen',
            ),
        )
    ),
    'shippingtimetype'                => array(
        'values' => array(
            '__ml_lump'   => array('title' => 'Forfait (dans le champs de droite)',),
            'immediately' => array('title' => 'immédiatement',),
            '4-6days'     => array('title' => '4-6 journées',),
            '1-2days'     => array('title' => '1-2 journées',),
            '2-3days'     => array('title' => '2-3 journées',),
            '4weeks'      => array('title' => '4 semaines',),
            '24h'         => array('title' => '24 heures',),
            '1-3days'     => array('title' => '1-3 journées',),
            '3days'       => array('title' => '3 journées',),
            '3-5days'     => array('title' => '3-5 journées',),
        ),
    ),
    'shippingtimeproductfield'        => array(
        'label' => 'délai de livraison (matching)',
    ),
    'orderstatus.cancelreason'        => array(
        'label' => 'Raison par défaut de l\'annulation de commande ',
        'help'  => '
            Sélectionnez la raison d\'annulation par défaut:<br />
            -"Annulée par le client"<br />
            -"Défaut de livraison"<br />
            -"Retour"
        '
    ),
    'orderstatus.cancelcomment'       => array(
        'label' => 'Commantaire de l\'annulation de commande',
    ),
    'orderstatus.refund'              => array(
        'label'       => 'Initier le remboursement avec',
        'firstoption' => array('--' => 'Sélectionnez une option ...'),
        'help'        => 'Le Statut Statut sélectionné ici, permet lorsqu’il est renseigné dans une commande de déclencher le remboursement d’une commande payée via “Idealo paiement direct” sur idealo.  
<br><br>
Les commandes dont le paiement a été effectué moyennant un autre moyen de paiement, ne sont pas remboursées sur idealo et doivent être mis à jour manuellement.',
    ),
    'prepare_title' => array(
        'label' => 'Titre',
        'optional' => array(
            'checkbox' => array(
                'labelNegativ' => '{#i18n:ML_PRODUCTPREPARATION_ALWAYS_USE_FROM_WEBSHOP#}',
            ),
        )
    ),
    'prepare_description' => array(
        'label' => 'Description',
        'optional' => array(
            'checkbox' => array(
                'labelNegativ' => '{#i18n:ML_PRODUCTPREPARATION_ALWAYS_USE_FROM_WEBSHOP#}',
            ),
        )
    ),
    'prepare_image' => array(
        'label' => 'Images de produits',
        'hint' => 'Maximal 3 images',
        'optional' => array(
            'checkbox' => array(
                'labelNegativ' => '{#i18n:ML_PRODUCTPREPARATION_ALWAYS_USE_FROM_WEBSHOP#}',
            ),
        )
    ),
));