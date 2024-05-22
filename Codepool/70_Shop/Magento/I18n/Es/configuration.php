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
MLI18n::gi()->{'Magento_Global_Configuration_Label'} = 'Unidad de peso';
MLI18n::gi()->{'magentospecific_aGeneralForm__orderimport__fields__orderinformation__values__val'} = 'Mostrar el número de pedido y el marketplace en las facturas.';
MLI18n::gi()->{'magentospecific_aGeneralForm__orderimport__fields__orderinformation__desc'} = 'Si marcas esta casilla, el número de pedido del marketplace y el nombre del marketplace se almacenarán en los datos de la factura.<br />Los datos se mostrarán en la factura para que el comprador pueda ver de dónde procede el pedido.';
MLI18n::gi()->{'Magento_Global_Configuration_Description'} = 'Unidad por defecto para el peso';

MLI18n::gi()->{'form_config_orderimport_exchangerate_update_help'} = '<strong>Básicamente:</strong
<p>
Si la moneda predeterminada de la tienda web difiere de la moneda del marketplace, magnalister calcula la importación de pedidos y la carga de artículos utilizando el tipo de moneda almacenado en la tienda web.
Al importar los pedidos, magnalister guarda las divisas y los importes 1:1 de la misma forma que lo hace la tienda web al recibir los pedidos.
</p>

<strong>Atención:</strong>
<p>
Al activar esta función aquí, el tipo de cambio almacenado en la tienda web se actualiza con el servicio de importación que está activado en Magento en "Sistema" > "Gestionar divisas".
<u>Así también se mostrarán los precios en su tienda web a la venta con el tipo de cambio actualizado:</u>
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
Si el tipo de moneda de un mercado no se ha creado en la configuración de moneda de la tienda web, magnalister emite un mensaje de error.
</p>';
MLI18n::gi()->{'configuration__field__general.weightunit__label'} = '{#i18n:Magento_Global_Configuration_Label#}';
MLI18n::gi()->{'configuration__field__general.weightunit__help'} = '{#i18n:Magento_Global_Configuration_Description#}';
MLI18n::gi()->{'form_config_orderimport_exchangerate_update_alert'} = '<strong>Atención:</strong>
<p>
Al activar, el tipo de cambio almacenado en la tienda web se actualiza con el servicio de importación, que se activa en Magento en "Sistema" > "Gestionar divisas".
<u>Así también se mostrarán los precios en su tienda web con el tipo de cambio actualizado para la venta:</u
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