<?php
/**
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

/* Autogenerated file. Do not change! */

MLI18n::gi()->{'Magento_Global_Configuration_Label'} = 'Unité de poids';
MLI18n::gi()->{'Magento_Global_Configuration_Description'} = 'Unité de poids';
MLI18n::gi()->{'form_config_orderimport_exchangerate_update_help'} = 'Si la devise utilisé dans votre boutique en ligne est différente de celle de la place de marché, magnalister calcule le taux de change par rapport au taux que vous avez défini dans votre boutique en ligne. <br>
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
MLI18n::gi()->{'form_config_orderimport_exchangerate_update_alert'} = 'Si la devise utilisé dans votre boutique en ligne est différente de celle de la place de marché, magnalister calcule le taux de change par rapport au taux que vous avez défini dans votre boutique en ligne. <br>
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
MLI18n::gi()->{'magentospecific_aGeneralForm__orderimport__fields__orderinformation__values__val'} = 'Afficher le numéro de commande et le nom de la place de marché sur la facture.';
MLI18n::gi()->{'magentospecific_aGeneralForm__orderimport__fields__orderinformation__desc'} = 'Si vous activez la fonctionnalité, le numéro de la commande et le nom de la place de marché seront enregistrés dans les commentaires du client, à l\'importation des commandes.<br/>
Cette fonction est prise en charge par la plupart des programmes de boutique en ligne. Elle dépend donc de la programmation de votre boutique en ligne. 
Ces commentaires peuvent apparaître sur la facture de sorte que le client soit informé de l’origine de ses achats. <br/> <br/>

Vous pouvez également faire programmer des extensions pour obtenir plus d\'analyses statistiques sur l\'évolution du chiffre d\'affaires.<br/> <br/>

<strong>Important</strong>: <u>Certains systèmes de gestion de marchandises</u> ne tiennent pas compte des  commandes si les commentaires clients sont intégrés. Pour plus d\'informations à ce sujet, contactez s\'il vous plaît directement votre fournisseur de service.';
MLI18n::gi()->add('configuration', array(
    'field' => array(
        'general.weightunit' => array(
            'label' => '{#i18n:Magento_Global_Configuration_Label#}',
            'help'=> '{#i18n:Magento_Global_Configuration_Description#}',
            )
        )
    )
);