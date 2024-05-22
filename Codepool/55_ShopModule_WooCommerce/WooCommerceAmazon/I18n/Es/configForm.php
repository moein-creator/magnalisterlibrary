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

MLI18n::gi()->{'amazon_config_vcs__field__amazonvcs.invoice__values__germanmarket'} = 'Las facturas creadas en el mercado alemán se cargan en Amazon.';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.carrier__help'} = 'Selecciona aquí la empresa de transporte que se asigna por defecto a los pedidos de Amazon.<br>
<br>
Dispones de las siguientes opciones:<br>
<ul>
	<li><span class="bold underline">Transportista sugerido por Amazon</span></li>.
</ul>
Selecciona una empresa de transporte de la lista desplegable. Aparecerán las empresas recomendadas por Amazon.<br>
<br>
Esta opción es útil si <strong>quieres utilizar siempre la misma empresa de transporte</strong> para los pedidos de Amazon.<br>
<ul>
	<li><span class="bold underline">Coincidir empresas de transporte sugeridas por Amazon con proveedores de servicios de envío de los métodos de envío de la tienda web</span></li>.
</ul>
Puedes hacer coincidir las empresas de transporte recomendadas por Amazon con los proveedores de servicios creados en los métodos de envío de WooCommerce. Puedes hacer múltiples coincidencias utilizando el símbolo "+".<br>
<br>
Para obtener información sobre qué entrada de los métodos de envío de WooCommerce se utiliza para la importación de pedidos de Amazon, consulta el icono de información en "Importación de pedidos" -> "Método de envío de pedidos".<br>
<br>
Esta opción es útil si deseas utilizar <strong>configuraciones de métodos de envío existentes</strong> de los métodos de envío de <strong>WooCommerce</strong>.<br>
<ul>
    <li><span class="bold underline">magnalister añade un "Campo personalizado" en los detalles del pedido</span></li>.
</ul>
Si seleccionas esta opción, magnalister añadirá un campo en los detalles del pedido de WooCommerce. En este campo podrás introducir la empresa de transporte.<br>
<br>
Esta opción es útil si quieres utilizar <strong>diferentes empresas de transporte</strong> para los pedidos de Amazon.<br>
<ul>
	<li><span class="bold underline">Introduce manualmente una empresa de transporte para todos los pedidos en un campo de texto magnalister</span></li>.
</ul>
Si seleccionas la opción "Otros" en "Empresa de transporte" en magnalister, puedes introducir manualmente el nombre de una empresa de transporte en el campo de texto situado a su derecha.<br>
<br>
Esta opción es útil si <strong>deseas introducir manualmente la misma empresa de transporte para todos los pedidos de Amazon</strong>.<br>
<br>
<span class="bold underline">Notas importantes:</span>
<ul>
	<li>La especificación de una empresa de transporte es obligatoria para las confirmaciones de envío en Amazon.<br><br></li>
	<li>La no indicación de la empresa de transporte puede suponer la retirada temporal de la autorización de venta.</li>
	<li>Si no se indica la empresa de transporte, la autorización de venta puede ser retirada temporalmente.</li>
</ul>
';
MLI18n::gi()->{'amazon_config_carrier_option_orderfreetextfield_option'} = 'magnalister añade un "campo personalizado" en los detalles del pedido';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.shipmethod__help'} = '
Seleccione aquí el servicio de entrega ( = método de envío), que se asigna por defecto a todos los pedidos de Amazon.<br>
<br>
Tienes las siguientes opciones:
<ul>
	<li><span class="negrita subrayada">Coincidir el servicio de entrega con las entradas de los métodos de envío de la tienda web</span></li>.
</ul>
Puedes hacer coincidir cualquier servicio de entrega con las entradas creadas en el método de envío de WooCommerce. Puede hacer múltiples coincidencias utilizando el símbolo "+".<br>
<br>
Para obtener información sobre qué entrada de los métodos de envío de WooCommerce se utiliza para la importación de pedidos de Amazon, consulte el icono de información en "Importación de pedidos" -> "Método de envío de pedidos"<br>.
<br>
Esta opción es útil si desea utilizar <strong>configuraciones de métodos de envío existentes de</strong> los métodos de envío de <strong>WooCommerce</strong>.<br>
<ul>
	<li><span class="negrita subrayada">magnalister añade un "Campo personalizado" en los detalles del pedido</span></li>.
</ul>
Si seleccionas esta opción, magnalister añadirá un "Campo personalizado" en los detalles del pedido de WooCommerce. Puede introducir el servicio de entrega en este campo.<br>
<br>
Esta opción es útil si desea utilizar <strong>diferentes servicios de entrega</strong> para los pedidos de Amazon.<br>
<ul>
	<li><span class="negrita subrayada">Introduzca manualmente un servicio de entrega para todos los pedidos en un campo de texto magnalister</span></li>.
</ul>
Si selecciona esta opción en magnalister, puede introducir manualmente el nombre de un servicio de entrega en el campo de texto situado a la derecha del mismo.<br>
<br>
Esta opción es útil si <strong>desea introducir manualmente el mismo servicio de entrega para todos los pedidos de Amazon</strong>.<br>
<br>
<span class="negrita subrayada">Notas importantes:</span
<ul>
	<li>La especificación de un servicio de entrega es obligatoria para las confirmaciones de envío en Amazon.<br><br></li>.
	<li>El no proporcionar el servicio de entrega puede dar lugar a una retirada temporal de la autorización de venta.</li> <li><li>Por favor, tenga en cuenta que el servicio de entrega es obligatorio.
</ul>
';