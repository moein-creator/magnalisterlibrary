<?php


MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.carrier__help'} = 'Selecciona aquí la empresa de transporte que se asigna por defecto a los pedidos de Amazon.<br>
<br>
Tienes las siguientes opciones:<br>
<ul>
	<li><span class="bold underline">Transportista sugerido por Amazon</span></li>.
</ul>
Selecciona una empresa de transporte de la lista desplegable. Aparecerán las empresas recomendadas por Amazon.<br>
<br>
Esta opción es útil si <strong>quieres utilizar siempre la misma empresa de transporte</strong> para los pedidos de Amazon.<br>
<ul>
	<li><span class="bold underline">Coincidir empresas de transporte sugeridas por Amazon con proveedores de servicios de envío del módulo de gastos de envío de la tienda web</span></li>.
</ul>
Puedes hacer coincidir las empresas de transporte recomendadas por Amazon con los proveedores de servicios creados en el módulo de gastos de envío de Magento. Puedes realizar múltiples coincidencias utilizando el símbolo "+".<br>
<br>
Para obtener información sobre qué entrada del módulo de gastos de envío de Magento se utiliza para la importación de pedidos de Amazon, consulta el icono de información en "Importación de pedidos" -> "Método de envío de pedidos".<br>
<br>
Esta opción es útil si deseas utilizar <strong>configuraciones de gastos de envío existentes</strong> del módulo de gastos de envío de <strong>Magento</strong>.<br>
<ul>
    <li><span class="bold underline">magnalister añade un campo de texto libre en los detalles del pedido</span></li>.
</ul>
Si seleccionas esta opción, magnalister añadirá un campo en los detalles del pedido de Magento. En este campo puedes introducir la empresa de transporte.<br>
<br>
Esta opción es útil si deseas utilizar <strong>diferentes empresas de transporte</strong> para los pedidos de Amazon.<br>
<ul>
	<li><span class="bold underline">Introduce manualmente una empresa de transporte para todos los pedidos en un campo de texto magnalister</span></li>.
</ul>
Si selecciona la opción "Otros" en "Empresa de transporte" en magnalister, puedes introducir manualmente el nombre de una empresa de transporte en el campo de texto situado a su derecha.<br>
<br>
Esta opción es útil si <strong>deseas introducir manualmente la misma empresa de transporte para todos los pedidos de Amazon</strong>.<br>
<br>
<span class="bold underline">Notas importantes:</span
<ul>
	<li>La especificación de una empresa de transporte es obligatoria para las confirmaciones de envío en Amazon.<br><br></li>
	<li>La no indicación de la empresa de transporte puede suponer la retirada temporal de la autorización de venta.</li> 
	<li>Los gastos de envío son gratuitos.</li> 
</ul>
';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.shipmethod__help'} = 'Selecciona aquí el servicio de entrega ( = método de envío), que se asigna por defecto a todos los pedidos de Amazon.<br>
<br>
Tienes las siguientes opciones:
<ul>
	<li><span class="bold underline">Coincidir el servicio de entrega con las entradas del módulo de envío de la tienda web</span></li>.
</ul>
Puedes hacer coincidir cualquier servicio de entrega con las entradas creadas en el módulo de gastos de envío de Magento. Puedes hacer múltiples coincidencias utilizando el símbolo "+".<br>
<br>
Para obtener información sobre qué entrada del módulo de gastos de envío de Magento se utiliza para la importación de pedidos de Amazon, consulta el icono de información en "Importación de pedidos" -> "Método de envío de pedidos".<br>
<br>
Esta opción es útil si deseas utilizar <strong>configuraciones de gastos de envío existentes de</strong> el módulo de gastos de envío de <strong>Magento</strong>.<br>
<ul>
	<li><span class="bold underline">magnalister añade un campo de texto libre en los detalles del pedido</span></li>.
</ul>
Si seleccionas esta opción, magnalister añade un campo en los detalles del pedido de Magento durante la importación del pedido. En este campo puedes introducir el servicio de entrega.<br>
<br>
Esta opción es útil si desea utilizar <strong>diferentes servicios de entrega</strong> para los pedidos de Amazon.<br>
<ul>
	<li><span class="bold underline">Introduce manualmente un servicio de entrega para todos los pedidos en un campo de texto magnalister</span></li>.
</ul>
Si seleccionas esta opción en magnalister, puedes introducir manualmente el nombre de un servicio de entrega en el campo de texto situado a la derecha del mismo.<br>
<br>
Esta opción es útil si <strong>deseas introducir manualmente el mismo servicio de entrega para todos los pedidos de Amazon</strong>.<br>
<br>
<span class="bold underline">Notas importantes:</span
<ul>
	<li>La especificación de un servicio de entrega es obligatoria para las confirmaciones de envío en Amazon.<br><br></li>.
	<li>El no proporcionar el servicio de entrega puede dar lugar a una retirada temporal de la autorización de venta.</li>
	<li>Por favor, ten en cuenta que el servicio de entrega es obligatorio.</li>
</ul>
';