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

MLI18n::gi()->add('formfields', array(
    'config_shopware5_invoice_documenttype'    => array(
        'label' => 'Création d\'un document PDF Shopware Facture "Nom technique"',
    ),
    'config_shopware5_creditnote_documenttype' => array(
        'label' => 'Création d\'un justificatif Shopware PDF Note de crédit "Nom technique"',
    ),
));
MLI18n::gi()->shop_order_attribute_name = 'Champs supplémentaires de Shopware';
MLI18n::gi()->shop_order_attribute_creation_instruction = 'Vous pouvez créer des champs de texte libre dans votre backend Shopware sous "Configuration" -> "Gestion des champs de texte libre" (tableau : commande) et les remplir sous "Clients" -> "Commandes". Pour ce faire, ouvrez la commande correspondante et faites défiler vers le bas jusqu’à "Champs supplémentaires".';
