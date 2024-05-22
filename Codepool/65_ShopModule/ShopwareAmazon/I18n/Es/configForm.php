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
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcsinvoice.invoicenumber.matching__label'} = 'Campo de texto libre del pedido de Shopware';
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcsinvoice.reversalinvoicenumber.matching__label'} = 'Campo de texto libre del pedido de Shopware';
MLI18n::gi()->{'amazon_config_carrier_option_group_shopfreetextfield_option_shipmethod'} = 'Subtítulo (francés)';
MLI18n::gi()->{'amazon_config_carrier_option_group_shopfreetextfield_option_carrier'} = 'Subtítulo (francés)';
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcsinvoice.reversalinvoicenumber__label'} = 'Número de factura de anulación';
MLI18n::gi()->{'amazon_config_orderimport__field__orderimport.paymentstatus__help'} = 'Por favor, selecciona qué estado de pago del sistema de la tienda debe establecerse en los detalles del pedido al importar el pedido de magnalister.';
MLI18n::gi()->{'amazon_config_orderimport__field__orderimport.fbapaymentstatus__help'} = 'Por favor, selecciona qué estado de pago del sistema de la tienda debe establecerse en los detalles del pedido al importar el pedido de magnalister.';
MLI18n::gi()->{'amazon_config_orderimport__field__orderimport.fbapaymentstatus__label'} = 'Estado del pago de los pedidos FBA';
MLI18n::gi()->{'amazon_config_orderimport__field__orderimport.paymentstatus__label'} = 'Estado del pago en la tienda';
MLI18n::gi()->{'amazon_config_amazonvcsinvoice_reversalinvoicenumberoption_values_matching'} = 'Haz coincidir los números de factura de anulación con el campo de texto libre de Shopware';
MLI18n::gi()->{'amazon_config_amazonvcsinvoice_invoicenumberoption_values_matching'} = 'Haz coincidir los números de factura con el campo de texto libre de Shopware';
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcsinvoice.invoicenumber__label'} = 'Subtítulo (francés)';
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcsinvoice.reversalinvoicenumber__help'} = '<p>Elige aquí si quieres que tus números de factura sean generados por magnalister o si quieres que se tomen de un campo de texto libre de Shopware.
 </p><p>
 <b>Crear números de factura a través de magnalister </b>
 </p><p>
 magnalister genera números de factura consecutivos durante la creación de la factura. Se puede definir un prefijo que se antepone al número de factura. Ejemplo: R10000.
 </p><p>
 Nota: Las facturas creadas por magnalister comienzan con el número 10000.</p><p>
 <b>Coincidir los números de factura con el campo de texto libre de Shopware</b>
 </p><p>
 Al crear la factura, el valor se toma del campo de texto libre de Shopware que haya seleccionado.
 </p><p>
 {#i18n:shop_order_attribute_creation_instruction#}
 </p><p>
 <b>Importante:</b><br/> magnalister genera y transmite la factura en cuanto el pedido se marca como enviado. Asegúrate de que el campo de texto libre está rellenado, de lo contrario se producirá un error (consulta la pestaña "Registro de errores").
 <br/><br/>
 Si utilizas la correspondencia de campos de texto libre, magnalister no se hace responsable de la creación correcta y consecutiva de los números de factura.
 </p>';
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcsinvoice.invoicenumber__help'} = '<p>Elige aquí si quieres que tus números de factura sean generados por magnalister o si quieres que se tomen de un campo de texto libre de Shopware.
 </p><p>
 <b>Crear números de factura a través de magnalister</b>
 </p><p>
 magnalister genera números de factura consecutivos durante la creación de la factura. Se puede definir un prefijo que se antepone al número de factura. Ejemplo: R10000.
 </p><p>
 Nota: Las facturas creadas por magnalister comienzan con el número 10000.</p><p>
 <b>Coincidir los números de factura con el campo de texto libre de Shopware</b>
 </p><p>
 Al crear la factura, el valor se toma del campo de texto libre de Shopware que haya seleccionado.
 </p><p>
 {#i18n:shop_order_attribute_creation_instruction#}
 </p><p>
 <b>Importante:</b><br/> magnalister genera y transmite la factura en cuanto el pedido se marca como enviado. Asegúrate de que el campo de texto libre está rellenado, de lo contrario se producirá un error (consulta la pestaña "Registro de errores").
 <br/><br/>
 Si utilizas la correspondencia de campos de texto libre, magnalister no se hace responsable de la creación correcta y consecutiva de los números de factura.
 </p>';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.shipmethod__help'} = 'Selecciona aquí el servicio de entrega (Método de envío) que se asigna de forma predeterminada a todos los pedidos de Amazon.<br>
 <br>
 Dispones de las siguientes opciones:
 <ul>
 <li><span class="bold underline">{#i18n:amazon_config_carrier_option_group_shopfreetextfield_option_shipmethod#}</span>
 <p>
 Selecciona un servicio de entrega de un cuadro de texto libre de la tienda web.<br>
 <br>
 {#i18n:shop_order_attribute_creation_instruction#}<br>
 <br>
 Esta opción es la más indicada si deseas utilizar <strong>diferentes servicios de entrega para los pedidos de Amazon</strong>.<br>
 </p>
 </li>
 <li><span class="bold underline">Vincula el servicio de entrega con las entradas del módulo de gastos de envío de la tienda web</span>
 <p>
 Puedes vincular cualquier servicio de entrega con las entradas creadas en el módulo de gastos de envío de Shopware 5. Utiliza el símbolo «+» para realizar vinculaciones múltiples.<br>
 <br>
 Puedes encontrar información sobre qué entrada del módulo de envío de Shopware se utiliza para importar pedidos de Amazon en el icono de información en "Importación de pedidos" -> "Tipo de envío del pedido".
 <br>
 Esta opción es la más indicada si deseas utilizar <strong>la configuración de envíos existente</strong> del módulo de gastos de envío de <strong>Shopware 5</strong>.<br>
 </p>
 </li>
 <li><span class="bold underline">Introducción de forma manual de un servicio de entrega para todos los pedidos en un campo de texto magnalister</span>
 <p>
 Si seleccionas esta opción en magnalister, puedes introducir de forma manual el nombre de un servicio de entrega en el campo de texto a la derecha.<br>
 <br>
 Esta opción es la más indicada si deseas <strong>registrar de forma manual un mismo servicio de entrega para todos los pedidos de Amazon</strong>.<br>
 </p>
 </li>
 </ul>
 <span class="bold underline">Notas importantes:</span>
 <ul>
 <li>Es obligatorio especificar un servicio de entrega para las confirmaciones de envíos de Amazon.<br><br></li>
 <li>No indicar el servicio de entrega puede dar lugar a la retirada temporal de la autorización de venta.</li>
 </ul>';
MLI18n::gi()->{'amazon_config_orderimport__field__orderstatus.carrier__help'} = 'Selecciona aquí la empresa de transporte que se asignara de forma predeterminada a los pedidos de Amazon.<br>
 <br>
 Tienes las siguientes opciones:<br>
 <ul>
 <li><span class="bold underline">Empresas de transporte propuestas por Amazon</span><p>
 Selecciona un transportista de la lista desplegable. Se muestran a continuación las empresas recomendadas por Amazon.<br><br>
 Esta opción es la más indicada si deseas <strong>utilizar siempre la misma empresa de transporte</strong> para los pedidos de Amazon.</p>
 </li>
 <li><span class="bold underline">{#i18n:amazon_config_carrier_option_group_shopfreetextfield_option_carrier#}</span>
 <p>
 {#i18n:shop_order_attribute_creation_instruction#}<br>
 <br>
 Esta opción es la más indicada si deseas <strong>utilizar diferentes empresas de transporte</strong> para los pedidos de Amazon.</p>
 </li>
 <li><span class="bold underline">Vincula las empresas de transporte sugeridas por Amazon con los proveedores de servicios de envío del módulo de gastos de envío de la tienda web</span>
 <p>
 Puedes vincular las empresas de transporte recomendadas por Amazon con los proveedores de servicios creados en el módulo de gastos de envío de Shopware 5. Utiliza el símbolo «+» para realizar varias vinculaciones.<br>
 <br>
 Para obtener información sobre qué entrada del módulo de gastos de envío de Shopware se utiliza en la importación de pedidos de Amazon, consulta el icono de información en «Importación de pedidos» -> «Método de envío de pedidos».
 <br><br>
 Esta opción es la más indicada si deseas utilizar <strong>la configuración de envíos existente</strong> del módulo de gastos de envío de <strong>Shopware 5</strong>.<br>
 </p>
 </li>
 <li><span class="bold underline">Introducción manual de una empresa de transporte para todos los pedidos en un campo de texto magnalister</span>
 <p>
 En magnalister, si seleccionas «Otro» en la opción «Empresa de transporte», puedes introducir de forma manual el nombre de una empresa de transporte en el cuadro de texto a la derecha.<br>
 <br>
 Esta opción es la más indicada si <strong>deseas registrar de forma manual una misma empresa de transporte para todos los pedidos de Amazon</strong>.<br>
 </p>
 </li>
 </ul>
 <span class="bold underline">Notas importantes:</span>
 <ul>
 <li>Es obligatorio indicar una empresa de transporte para las confirmaciones de los envíos en Amazon.<br><br></li>
 <li>No indicar la empresa de transporte puede dar lugar a la retirada temporal de la autorización de venta.</li>
 </ul>';

MLI18n::gi()->{'amazon_config_orderimport__field__orderimport.shippingmethod__help'} = '<p>Amazon no proporciona ninguna información sobre el método de envío al importar pedidos.</p>
<p>Selecciona aquí los métodos de envío disponibles en la tienda web. Puedes definir el contenido desde el menú desplegable en Shopware > Configuración > Gastos de envío.</p>
<p>Este ajuste es importante para imprimir facturas y albaranes, y para el posterior procesamiento del pedido en la tienda y en los sistemas de gestión de mercancías.</p>';
MLI18n::gi()->{'amazon_config_orderimport__field__orderimport.paymentmethod__help'} = '<p>Método de pago que se asigna a todos los pedidos de Amazon durante la importación de pedidos.
Por defecto: "Amazon"</p>
<p>También puedes definir todos los demás métodos de pago disponibles en la lista en Shopware > Configuración > Métodos de pago y luego utilizarlos aquí.</p>
<p>Este ajuste es importante para la impresión de facturas y albaranes y para el posterior procesamiento del pedido en la tienda y en los sistemas de gestión de mercancías.</p>';
MLI18n::gi()->{'amazon_config_orderimport__field__orderimport.shippingmethod__label'} = 'Método de envío de los pedidos';
MLI18n::gi()->{'amazon_config_orderimport__field__orderimport.fbashippingmethod__label'} = 'Forma de envío de los pedidos (FBA)';
MLI18n::gi()->{'amazon_config_orderimport__field__customergroup__help'} = '{#i18n:global_config_orderimport_field_customergroup_help#}';
MLI18n::gi()->{'amazon_config_orderimport__field__orderimport.fbashippingmethod__help'} = '<p>Amazon no proporciona ninguna información sobre el método de envío al importar pedidos.</p>
<p>Selecciona aquí los métodos de envío disponibles en la tienda web. Puedes definir el contenido desde el menú desplegable en Shopware > Configuración > Gastos de envío.</p>
<p>Este ajuste es importante para la impresión de facturas y albaranes, y para el posterior procesamiento del pedido en la tienda y en los sistemas de gestión de mercancías.</p>';
MLI18n::gi()->{'amazon_config_orderimport__field__orderimport.paymentmethod__label'} = 'Forma de pago de los pedidos';
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcsinvoice.reversalinvoicenumberoption__label'} = '';
MLI18n::gi()->{'amazon_config_vcs__field__amazonvcsinvoice.invoicenumberoption__label'} = '';
MLI18n::gi()->{'amazon_config_orderimport__field__orderimport.shippingmethod__hint'} = '';
MLI18n::gi()->{'amazon_config_orderimport__field__orderimport.fbapaymentstatus__hint'} = '';
MLI18n::gi()->{'amazon_config_orderimport__field__orderimport.fbashippingmethod__hint'} = '';
MLI18n::gi()->{'amazon_config_orderimport__field__orderimport.paymentstatus__hint'} = '';
MLI18n::gi()->{'amazon_config_orderimport__field__orderimport.paymentmethod__hint'} = '';