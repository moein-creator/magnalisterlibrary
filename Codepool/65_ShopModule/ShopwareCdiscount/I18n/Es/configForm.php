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
MLI18n::gi()->{'sCdiscount_match_marketplace'} = 'Filtro de estado';
MLI18n::gi()->{'cdiscount_config_orderimport__field__orderimport.paymentstatus__help'} = 'Por favor, selecciona qué estado de pago del sistema de la tienda debe establecerse en los detalles del pedido al importar el pedido de magnalister.';
MLI18n::gi()->{'cdiscount_config_orderimport__field__orderimport.paymentstatus__label'} = 'Estado del pago en la tienda';
MLI18n::gi()->{'cdiscount_config_orderimport__field__orderstatus.carrier__help'} = 'Selecciona aquí la empresa de transporte que se asignara de forma predeterminada a los pedidos de Cdiscount.<br>
 <br>
 Dispones de las siguientes opciónes:<br>
 <ul>
 <li>
 <span class="bold underline">Empresas de transporte propuestas por Cdiscount</span>
 <p>Selecciona una empresa de transporte en la lista desplegable. Se muestran a continuación las empresas recomendadas por Cdiscount.<br>
 <br>
 Esta opción es la más indicada si deseas <strong>utilizar siempre la misma empresa de transporte</strong> para los pedidos de Cdiscount.
 </p>
 </li>
 <li>
 <span class="bold underline">{#i18n:cdiscount_config_carrier_option_group_shopfreetextfield_option_carrier#}</span>
 <p>{#i18n:shop_order_attribute_creation_instruction#}<br>
 <br>
 Esta opción es la más indicada si deseas <strong>utilizar diferentes empresas de transporte para los pedidos de Cdiscount</strong>.
 </p>
 </li>
 <li>
 <span class="bold underline">Vincula las empresas de transporte sugeridas por Cdiscount con los proveedores de servicios de expedición del módulo de gastos de envío de la tienda web</span>
 <p>Puedes vincular las empresas de transporte recomendadas por Cdiscount con los proveedores de servicios de expedición creados en el módulo de gastos de envío de Shopware 5. Utiliza el símbolo «+» para realizar vinculaciones múltiples.<br>
 <br>
 Puedes averiguar qué entrada del módulo de envío de Shopware se utiliza para importar pedidos de Cdiscount en el icono de información en "Importación de pedidos" -> "Método de envío del pedido".<br>
 <br>
 Esta opción es la más indicada si deseas utilizar <strong>los ajustes de gastos de envío existente</strong> del módulo de gastos de envío de <strong>Shopware 5</strong>.<br>
 </p>
 </li>
 <li>
 <span class="bold underline">Tomar en bloque la empresa de transporte desde el campo de texto</span><br>
 <p>Esta opción es la más indicada si deseas <strong>registrar de forma manual una misma empresa de transporte para todos los pedidos de Cdiscount</strong>.<br></p>
 </li>
 </ul>
 <span class="bold underline">Notas importantes:</span>
 <ul>
 <li>Es obligatorio indicar una empresa de transporte para las confirmaciones de los envíos en Cdiscount.<br><br></li>
 <li>No indicar la empresa de transporte puede dar lugar a la retirada temporal de la autorización de venta.</li>
 </ul>';
MLI18n::gi()->{'sCdiscount_automatically'} = '-- asignar automáticamente --';

MLI18n::gi()->{'cdiscount_config_orderimport__field__orderimport.paymentmethod__help'} = '<p>Método de pago que se asigna a todos los pedidos Cdiscount durante la importación del pedido.
Por defecto: "Cdiscount"</p>
<p>También puedes definir todos los demás métodos de pago disponibles en la lista en Shopware > Configuración > Métodos de pago y luego utilizarlos aquí.</p>
<p>Este ajuste es importante para la impresión de facturas y albaranes y para el posterior procesamiento del pedido en la tienda y en los sistemas de gestión de mercancías.</p>';
MLI18n::gi()->{'cdiscount_config_orderimport__field__customergroup__help'} = '{#i18n:global_config_orderimport_field_customergroup_help#}';
MLI18n::gi()->{'cdiscount_config_orderimport__field__orderimport.shippingmethod__help'} = '<p>Cdiscount no proporciona ninguna información sobre el método de envío al importar pedidos.</p>
<p>Selecciona aquí los métodos de envío disponibles en la tienda web. Puedes definir el contenido desde el menú desplegable en Shopware > Configuración > Gastos de envío.</p>
<p>Este ajuste es importante para imprimir facturas y albaranes, y para el posterior procesamiento del pedido en la tienda y en los sistemas de gestión de mercancías.</p>';
MLI18n::gi()->{'cdiscount_config_orderimport__field__orderimport.paymentmethod__label'} = 'Tipo de pago de los pedidos';
MLI18n::gi()->{'cdiscount_config_orderimport__field__orderimport.shippingmethod__label'} = 'Método de envío de los pedidos';
MLI18n::gi()->{'cdiscount_config_orderimport__field__orderimport.paymentstatus__hint'} = '';
MLI18n::gi()->{'cdiscount_config_orderimport__field__orderimport.shippingmethod__hint'} = '';
MLI18n::gi()->{'cdiscount_config_orderimport__field__orderimport.paymentmethod__hint'} = '';