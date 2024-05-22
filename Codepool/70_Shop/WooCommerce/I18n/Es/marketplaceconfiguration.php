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
MLI18n::gi()->{'woocommerce_config_trackingkey_option_group_customfields'} = '«Campos personalizados» de WooCommerce';
MLI18n::gi()->{'orderimport_trackingkey__label'} = 'Número de envió';
MLI18n::gi()->{'marketplace_config_carrier_matching_title_shop_carrier_plugin'} = 'Proveedores de servicios de envío definidos en el complemento «{#pluginname#}» (opciones de envió)';
MLI18n::gi()->{'amazon_config_carrier_option_matching_option_shipmethod_plugin'} = 'Vincula el servicio de entrega a las entradas de los proveedores de servicios de entrega en el plug-in "{#pluginname#}".';
MLI18n::gi()->{'amazon_config_carrier_option_matching_option_carrier_plugin'} = 'Conecta las empresas de transporte sugeridas por Amazon con los proveedores de servicios de transporte del complemento "{#pluginname#}".';
MLI18n::gi()->{'marketplace_config_carrier_option_matching_option_plugin'} = 'Vincular los proveedores de servicios de envió admitidos por el marketplace con los proveedores de servicios de envió definidos en el complemento «{#pluginname#}»';
MLI18n::gi()->{'woocommerce_config_trackingkey_option_orderfreetextfield_option'} = 'magnalister añade un «campo personalizado» en los detalles del pedido';
MLI18n::gi()->{'woocommerce_config_trackingkey_option_plugin_germanized'} = 'Complemento de español: usar el número de seguimiento desde allí';
MLI18n::gi()->{'woocommerce_config_trackingkey_option_plugin_ast'} = 'Complemento de seguimiento de envío avanzado: usar el número de seguimiento desde allí';
MLI18n::gi()->{'woocommerce_config_trackingkey_option_group_additional_option'} = 'Opciones adicionales';
MLI18n::gi()->{'form_config_orderimport_exchangerate_update_alert'} = '<strong>Precaución:</strong>
 <p>
 Al activar esta función, la configuración de divisas de tu tienda virtual se actualizará y sobrescribirá con el tipo de cambio actual de "alphavantage".
 <u>Como resultado, esto afectará a tu moneda extranjera en el frontend de la tienda virtual.</u>
 </p>
 <p>
 Las siguientes funciones de Magnalister activan la actualización del tipo de cambio:<ul>
 <li>Order import</li>
 <li>Preparación de los productos</li>
 <li>Carga de productos</li>
 <li>Sincronización de acciones y precios</li>
 </ul>
 </p>';
MLI18n::gi()->{'orderimport_trackingkey__help'} = '<p>Tienes las siguientes opciones para enviar el número de envío de un pedido del marketplace importado a través de magnalister al marketplace/comprador comparando el estado del pedido:
 </p>
 <ol>
 <li><p><h5>Crear un campo personalizado en WooCommerce y seleccionarlo en magnalister
 </h5></p><p>
 Crea campos personalizados en la administración de WooCommerce en «Orders» («Pedidos»). Asigna un nombre al campo personalizado en la columna «Nombre», por ejemplo, «Número de envío», e introduce el número de envío del pedido correspondiente en la columna «Valor».
 </p><p>
 A continuación, vuelve a este punto en el plugin magnalister y selecciona el campo creado en los detalles del pedido de la lista desplegable adyacente en "Campos personalizados" en WooCommerce (siguiendo el ejemplo anterior con el nombre "Número de envío").
 </p>
 </li>
 <li>
 <p>
 <h5>magnalister añadirá un «campo personalizado» en los detalles del pedido</h5>
 </p><p>
 Si seleccionas esta opción, magnalister añadirá automáticamente un «campo personalizado» en «Orders» («Pedidos») -> «Campos personalizados» al importar el pedido.
 </p><p>
 Ahora tendrás la posibilidad de introducir ahí el número de envió correspondiente.
 </p>
 </li><li>
 <p>
 <h5>magnalister accede al campo de número de seguimiento de complementos de terceros
 </h5></p><p>
 magnalister puede acceder a los campos de número de seguimiento desde ciertos complementos de terceros de WooCommerce. Esto incluye los siguientes complementos:
 </p><p>
 Complemento de español
 </p><p>
 Para transferir el número de envío desde el complemento de español al marketplace a través de magnalister, selecciona la opción «Complemento de español: usar el número de envío desde allí» en la lista desplegable adyacente.
 </p><p>
 Cuando utilices el complemento de español, introduce el número de envío en los detalles del pedido en «Envíos» -> «Número de envío».
 </p><p>
 Complemento de seguimiento de envío avanzado
 </p><p>
 Para transferir el número de envío desde el complemento de seguimiento de envío avanzado al marketplace a través de magnalister, selecciona la opción «Complemento de seguimiento de envío avanzado: usar número de envío desde allí» en la lista desplegable adyacente.
 </p><p>
 Si utilizas el complemento de seguimiento de envío avanzado, introduce el número de envío en los detalles del pedido en «Seguimiento de envío» -> «Código de envío».
 </p><p>
 {#i18n:woocommerce_config_trackingkey_help_warning#}
 </p>
 </li>
 </ol>';
MLI18n::gi()->{'WooCommerce_Configuration_ShippingMethod_Available_Info'} = '<p>Método de envío que se asigna a todos los pedidos {#setting:currentMarketplaceName#} al importar el pedido. Estándar: «Asignación automática»</p>
 <p> Si seleccionas "Asignación automática", magnalister utilizará el método de envío que el comprador haya elegido en {#setting:currentMarketplaceName#}.</p>
 <p>También puedes definir todos los demás Métodos de envío disponibles en la lista en WooCommerce > Ajustes > Envío > Zona de envío > Método de envío y luego usarlos aquí.</p><p>Este ajuste es importante para la impresión de facturas y albaranes, y para el procesamiento posterior del pedido en la tienda, así como en la gestión de mercancías.</p>';
MLI18n::gi()->{'WooCommerce_Configuration_PaymentMethod_Available_Info'} = '<p>Método de pago que se asigna a todos los pedidos de eBay durante la importación del pedido. 
 Estándar: «Asignación automática»</p>
 <p>Si eliges "Asignación automática", magnalister aceptará el método de pago elegido por el comprador en eBay.</p>
 <p>Este ajuste es importante para la impresión de facturas y albaranes, y para el procesamiento posterior del pedido en la tienda, así como en la gestión de mercancías.</p>';
MLI18n::gi()->{'WooCommerce_Configuration_PaymentMethod_NotAvailable_Info'} = '<p>Método de pago asignado a todos los pedidos {#setting:currentMarketplaceName#} durante la importación.</p>
 <p>También puedes definir y utilizar todos los demás Métodos de pago disponibles en el listado en WooCommerce > Ajustes > Pago.</p>
 <p>Este ajuste es importante para la impresión de facturas y albaranes, y para el procesamiento posterior del pedido en la tienda, así como en la gestión de mercancías.</p>';
MLI18n::gi()->{'WooCommerce_Configuration_ShippingMethod_NotAvailable_Info'} = '<p>{#setting:currentMarketplaceName#} no proporciona ninguna informacion sobre el Método de envío al importar el pedido.</p>
 <p> Por lo tanto, debes seleccionar aquí los métodos de envío disponibles en la tienda online. Puedes definir el contenido desde el menú desplegable en WooCommerce > Configuración > Envío > Zona de envío > Tipo de envío.
 <p> Esta configuración es importante para la impresión de facturas y albaranes, y para el tratamiento posterior del pedido en la tienda, así como en la gestión de mercancías.</p>';
MLI18n::gi()->{'woocommerce_config_trackingkey_help_warning'} = '<b>Información interesante para los complementos de terceros mencionados con anterioridad</b>: magnalister También proporciona el nombre de la empresa de envío desde el complemento de terceros.';
MLI18n::gi()->{'form_config_orderimport_exchangerate_update_help'} = '<strong>General:</strong>
 <p>
 Si la moneda establecida en la tienda web difiere de la del marketplace, magnalister calcula el precio del artículo con la ayuda de una conversión de moneda automática.
 </p>
 <strong>Caution:</strong>
 <p>
 Para ello utilizamos el tipo de cambio generado por el conversor de divisas externo "alphavantage". Importante: No asumimos ninguna responsabilidad por la conversión de moneda de los servicios externos.
 </p>
 <p>
 Las siguientes funciones activan la actualización del tipo de cambio:
 <ul>
 <li>Orden de Importación</li>
 <li>Preparación de la partida</li>
 <li>Carga de artículos</li>
 <li>Sincronización de existencias y precios</li>
 </ul>
 </p>
 <p>
 Además, el tipo de cambio se actualiza automáticamente cada 24 horas. En este campo puedes ver el último tipo de cambio actualizado y cuándo se actualizó por última vez.
 </p>';

MLI18n::gi()->{'formfields_config_uploadInvoiceOption_help_webshop'} = '';