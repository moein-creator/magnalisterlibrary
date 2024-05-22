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
MLI18n::gi()->{'ConditionUsed'} = 'Usado';
MLI18n::gi()->{'ConditionRefurbished'} = 'Obsoleto';
MLI18n::gi()->{'ConditionNew'} = 'Nuevo';
MLI18n::gi()->{'generic_config_generic_title'} = 'General';
MLI18n::gi()->{'generic_config_generic__legend__generic'} = 'Ajustes de la base';
MLI18n::gi()->{'generic_config_generic__field__orderimport.shop__label'} = '{#i18n:form_config_orderimport_shop_lable#}';
MLI18n::gi()->{'generic_config_generic__field__orderimport.shop__help'} = '';

MLI18n::gi()->{'form_config_orderimport_exchangerate_update_alert'} = '<strong>Atención:</strong>
<p>
Al activar, el tipo de cambio almacenado en la tienda web se actualiza con el servicio web de Prestashop para tipos de cambio.
<u>Esto también mostrará los precios en su tienda web con el tipo de cambio actualizado para las ventas.</u
</p>
<p>
Las siguientes funciones activan la actualización
<ul>
<li>Importación de pedidos</li>
<li>Preparación de artículos</li>
<li>Carga de artículos</li>
<li>Sincronización de existencias/precios</li>
</ul>
</p>
';
MLI18n::gi()->{'form_config_orderimport_exchangerate_update_help'} = '<strong>Básicamente:</strong
<p>
Si la moneda predeterminada de la tienda web difiere de la moneda del mercado, magnalister calcula la importación de pedidos y la carga de artículos utilizando el tipo de moneda almacenado en la tienda web.
Al importar los pedidos, magnalister guarda las divisas y los importes 1:1 de la misma forma que lo hace la tienda web al recibir los pedidos.
</p>

<strong>Atención:</strong>
<p>
Activando esta función aquí, el tipo de cambio almacenado en la tienda web se actualiza con el servicio web de Prestashop para tipos de cambio.
<u>Esto también mostrará los precios en su tienda web con el tipo de cambio actualizado para las ventas.</u
</p>
<p>
Las siguientes funciones activan la actualización
<ul>
<li>Importación de pedidos</li>
<li>Preparación de artículos</li>
<li>Carga de artículos</li>
<li>Sincronización de existencias/precios</li>
</ul>
</p>
<p>
Si el tipo de moneda de un marketplace no se ha creado en la configuración de moneda de la tienda web, magnalister emite un mensaje de error.
</p>';
MLI18n::gi()->{'generic_config_generic__legend__tabident'} = '';