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
MLI18n::gi()->{'dummymodule_config_account_emailtemplate_subject'} = 'Tu pedido en #SHOPURL#';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2bactive__values__true'} = 'Sí';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.default.dimension.width__label'} = 'Anchura';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.weight.unit__label'} = 'Unidad de peso';
MLI18n::gi()->{'dummymodule_config_orderimport__field__mwstfallback__label'} = 'IVA sobre artículos no conocidos en la tienda***.';
MLI18n::gi()->{'dummymodule_config_orderimport__legend__mwst'} = 'IVA';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.default.dimension__label'} = 'Tamaños de paquetes definidos por el usuario';
MLI18n::gi()->{'dummymodule_config_prepare__field__checkin.skuasmfrpartno__valuehint'} = 'Utiliza SKU como número de pieza del fabricante';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2b.price.usespecialoffer__label'} = 'Utilizar los precios de las ofertas especiales';
MLI18n::gi()->{'dummymodule_config_price__field__price.usespecialoffer__label'} = 'Utilizar los precios de las ofertas especiales';
MLI18n::gi()->{'dummymodule_config_prepare__field__shipping.template.active__label'} = 'Utilizar grupos de envío de vendedores';
MLI18n::gi()->{'dummymodule_config_prepare__legend__upload'} = 'Cargar elementos: Presets';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2bactive__help'} = 'Para utilizar esta función, tienes que activar el Servicio de Vendedor de Empresas de DummyModule: 
 Si ya eres vendedor de DummyModule, puedes acceder a tu cuenta de Seller Central y activar el Servicio de Vendedor Profesional. Para ello necesitas una "Cuenta de vendedor profesional". (Se puede ampliar dentro de tu cuenta de vendedor). 
 Lee también las instrucciones del icono de información en "Importar pedidos" > "Activar importación".';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2b.price.signal__help'} = 'Este campo de texto muestra el valor decimal que aparecerá en el precio del artículo en DummyModule.< br/><br/> 
 <strong>Ejemplo:</strong> <br> 
 Valor en textfeld: 99 <br> 
 Precio original: 5,58 <br> 
 Importe final: 5,99 <br><br> 
 Esta función es útil cuando se marca el precio hacia arriba o hacia abajo***. <br> 
 Deja este campo en blanco si no quieres establecer una cantidad decimal. <br> 
 El formato requiere un máximo de 2 números.';
MLI18n::gi()->{'dummymodule_config_price__field__price.signal__help'} = 'Este campo de texto muestra el valor decimal que aparecerá en el precio del artículo en Amazon.< br/><br/> 
 <strong>Ejemplo:</strong> <br /> 
 Valor en textfeld: 99 <br /> 
 Precio original: 5,58 <br /> 
 Importe final: 5,99 <br /><br /> Esta función 
 es útil cuando se marca el precio hacia arriba o hacia abajo***. <br/> 
 Deja este campo en blanco si no quieres establecer una cantidad decimal. <br/> 
 El formato requiere un máximo de 2 números.
 Esta función es útil para marcar el precio al alza o a la baja***.';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderstatus.fba__help'} = 'Esta función sólo es relevante para los vendedores que participan en &apos;Fulfilment by Amazon (FBA)&apos;: <br/>El estado del pedido se definirá como un pedido FBA, y el estado se transferirá automáticamente a su tienda.
 Si utilizas un procedimiento de reclamación conectado***, se recomienda establecer el estado del pedido en "Pagado" ("Ajustes" > "Estado del pedido").';
MLI18n::gi()->{'dummymodule_config_orderimport__field__mwstfallback__hint'} = 'El tipo impositivo que se aplicará a los artículos no pertenecientes a la tienda en las importaciones de pedidos, en %.';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderstatus.open__help'} = 'El estado se transfiere automáticamente a la tienda después de un nuevo pedido en Amazon. <br /> 
 Si utilizas un proceso de reclamación conectado***, se recomienda establecer el estado del pedido en "Pagado" ("Configuración" > "Estado del pedido").';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.fallback.weight__help'} = 'Si no se especifica ningún parámetro de peso para un producto, se tomará el parámetro aquí establecido.';
MLI18n::gi()->{'dummymodule_config_prepare__field__leadtimetoship__help'} = 'El tiempo transcurrido desde que el comprador realiza el pedido hasta que usted lo entrega a su transportista.<br>Si no se introduce ningún valor, el tiempo de manipulación se establecerá en 1-2 días laborables. Utiliza este campo si el plazo de tramitación es superior a 2 días laborables.';
MLI18n::gi()->{'dummymodule_config_orderimport__field__preimport.start__help'} = 'La fecha a partir de la cual deben importarse los pedidos. Ten en cuenta que no es posible establecer esta fecha demasiado lejos en el pasado, ya que los datos sólo están disponibles en Amazon durante unas pocas semanas.';
MLI18n::gi()->{'dummymodule_config_orderimport__field__customergroup__help'} = 'El grupo de clientes en el que deben clasificarse los clientes de los nuevos pedidos.';
MLI18n::gi()->{'dummymodule_config_account_sync'} = 'Sincronización';
MLI18n::gi()->{'dummymodule_config_emailtemplate__field__mail.subject__label'} = 'Asunto';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.address.streetandnr__label'} = 'Nombre y número de la calle';
MLI18n::gi()->{'dummymodule_config_sync__field__stocksync.tomarketplace__label'} = 'Sincronización de acciones con el marketplace';
MLI18n::gi()->{'dummymodule_config_sync__field__stocksync.frommarketplace__label'} = 'Sincronización de existencias desde el marketplace';
MLI18n::gi()->{'dummymodule_config_prepare__field__prepare.status__label'} = 'Filtro de estado';
MLI18n::gi()->{'dummymodule_config_prepare__field__checkin.status__label'} = 'Filtro de estado';
MLI18n::gi()->{'dummymodule_config_orderimport__field__preimport.start__label'} = 'Iniciar la importación desde';
MLI18n::gi()->{'dummymodule_config_orderimport__field__preimport.start__hint'} = 'Fecha de inicio';
MLI18n::gi()->{'dummymodule_config_prepare__field__checkin.skuasmfrpartno__help'} = 'El SKU se utilizará como número de pieza del fabricante.';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.size.unit__label'} = 'Unidad de tamaño';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2b.tax_code__matching__titlesrc'} = 'Clases de impuestos en la tienda';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2b.tax_code_specific__matching__titlesrc'} = 'Clases de impuestos en la tienda';
MLI18n::gi()->{'dummymodule_config_shippinglabel__legend__shippingservice'} = 'configuración de envío';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderimport.fbashippingmethod__label'} = 'Servicio de envío de los pedidos (FBA)';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderimport.shippingmethod__label'} = 'Servicio de envío de los pedidos';
MLI18n::gi()->{'dummymodule_config_shippinglabel__legend__shippinglabel'} = 'Opciones de envío';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderimport.shippingmethod__help'} = 'Métodos de envío que se asignarán a todos los pedidos de Amazon. Estándar: "Marketplace"<br><br> 
 Esta configuración es necesaria para la factura y el aviso de envío, y para editar los pedidos posteriormente en la Tienda o a través del ERP.';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderimport.fbashippingmethod__help'} = 'Método de envío para los pedidos de Amazon, que son cumplidos (enviados) por Amazon (FBA). Estándar: "dummymodule".<br><br> 
 Esta configuración es importante para las facturas y albaranes de envío, el posterior procesamiento del pedido dentro de la tienda y para algunos ERP.';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderimport.dummymodulepromotionsdiscount.shipping_sku__label'} = 'Descuento por envío Número de artículo';
MLI18n::gi()->{'dummymodule_config_account_shippinglabel'} = 'Servicios de envío';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippingservice.deliveryexperience__label'} = 'Condiciones de envío';
MLI18n::gi()->{'dummymodule_config_shippinglabel__legend__shippingaddresses'} = 'Dirección de envío';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.address__label'} = 'Dirección de envío';
MLI18n::gi()->{'dummymodule_config_prepare__legend__shipping'} = 'Envío';
MLI18n::gi()->{'dummymodule_config_emailtemplate__field__mail.originator.name__label'} = 'Nombre del remitente';
MLI18n::gi()->{'dummymodule_config_emailtemplate__field__mail.originator.adress__label'} = 'Dirección de correo electrónico del remitente';
MLI18n::gi()->{'dummymodule_config_prepare__legend__shippingtemplate'} = 'Grupos de envío de vendedores';
MLI18n::gi()->{'dummymodule_config_prepare__field__shipping.template__label'} = 'Grupos de envío de vendedores';
MLI18n::gi()->{'dummymodule_config_prepare__field__shipping.template.name__label'} = 'Etiqueta de grupo de envío del vendedor';
MLI18n::gi()->{'dummymodule_config_account__field__password__label'} = 'Contraseña de la Central de Vendedores';
MLI18n::gi()->{'dummymodule_config_account__field__username__label'} = 'Dirección de correo electrónico de la Central de Vendedores';
MLI18n::gi()->{'dummymodule_config_prepare__field__shipping.template.active__help'} = 'El vendedor puede crear grupos con diferentes servicios/métodos de envío específicos para las necesidades y casos de uso de su empresa. Se pueden seleccionar diferentes grupos de métodos de envío con diferentes condiciones y gastos de envío para diferentes regiones. 
 
 Cuando un vendedor crea un presupuesto para sus productos, debe seleccionar uno de sus grupos de condiciones de envío para su producto. El método de envío de este grupo se utiliza entonces para mostrar la opción de envío del producto en el sitio web.';
MLI18n::gi()->{'dummymodule_config_prepare__field__shipping.template__help'} = 'El vendedor puede crear grupos con diferentes servicios/métodos de envío específicos para las necesidades y casos de uso de su empresa. Se pueden seleccionar diferentes grupos de métodos de envío con diferentes condiciones y gastos de envío para diferentes regiones. 
 
 Cuando un vendedor crea un presupuesto para sus productos, debe seleccionar uno de sus grupos de condiciones de envío para su producto. El método de envío de este grupo se utiliza entonces para mostrar la opción de envío del producto en el sitio web.';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2bsellto__label'} = 'Vender a';
MLI18n::gi()->{'dummymodule_config_prepare__field__imagesize__hint'} = 'Guardado en {#setting:sImagePath#}';
MLI18n::gi()->{'dummymodule_config_prepare__field__multimatching.itemsperpage__label'} = 'Resultados';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2bdiscounttype__label'} = 'Cantidad Tipo de descuento';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2bdiscounttier5__label'} = 'Descuento por cantidad Nivel 5';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2bdiscounttier4__label'} = 'Descuento por cantidad Nivel 4';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2bdiscounttier3__label'} = 'Descuento por cantidad Nivel 3';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2bdiscounttier2__label'} = 'Descuento por cantidad Nivel 2';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2bdiscounttier1__label'} = 'Descuento por cantidad Nivel 1';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2bdiscounttier1quantity__label'} = 'Cantidad';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2bdiscounttier2quantity__label'} = 'Cantidad';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2bdiscounttier3quantity__label'} = 'Cantidad';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2bdiscounttier4quantity__label'} = 'Cantidad';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2bdiscounttier5quantity__label'} = 'Cantidad';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderimport.dummymodulepromotionsdiscount.products_sku__label'} = 'Producto Descuento Número de artículo';
MLI18n::gi()->{'dummymodule_config_price__field__priceoptions__label'} = 'Opciones de precios';
MLI18n::gi()->{'dummymodule_config_account_price'} = 'Cálculo del precio';
MLI18n::gi()->{'dummymodule_config_price__legend__price'} = 'Cálculo del precio';
MLI18n::gi()->{'dummymodule_config_price__field__price__label'} = 'Precio';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderstatus.carrier.default__help'} = 'Transportista preseleccionado con confirmación de distribución a dummymodule.';
MLI18n::gi()->{'dummymodule_config_prepare__legend__prepare'} = 'Preparar artículos';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.address.zip__label'} = 'Código postal';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderstatus.shipped__help'} = 'Por favor, configura el estado de la tienda para activar el estado "Envío confirmado" en Amazon.';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderstatus.cancelled__help'} = 'Introduce el estado de la tienda que debe activar el estado "Pedido cancelado" en Amazon.<br/><br/> 
 Nota: Las cancelaciones parciales no son posibles con la API de Amazon. Con esta función, se cancela todo el pedido y se reembolsa al comprador.';
MLI18n::gi()->{'dummymodule_config_prepare__field__quantity__help'} = 'Por favor, introduce la cantidad de existencias que deben estar disponibles en el marketplace.<br/> 
 <br/> Puedes cambiar el número de elementos individuales directamente en "Subir". En este caso se recomienda desactivar
 la<br/> sincronización automática en "Sincronización de la acción" > "Sincronización de la acción con el marketplace".<br/> 
 <br/> Para evitar la sobreventa, puedes activar "Transferir existencias de la tienda menos el valor del campo derecho".
 <br/> 
 <strong>Ejemplo:</strong> Al establecer el valor en 2 se obtiene &#8594; Inventario de la tienda: 10 &#8594; Inventario del DummyModule: 8<br/> 
 <br/> 
 <strong> Ten en cuenta:</strong>Si quieres establecer en "0" el inventario de un artículo en el marketplace, que ya está establecido en "Inactivo" en la Tienda, independientemente del inventario real, procede de la siguiente forma:<br/> 
 <li>"Sincronizar inventario">Configura "Editar inventario de la tienda" en "Sincronizar automáticamente con CronJob"</li>.
 <li>" Configuración global" > "Estado del producto" > Activa el ajuste "Si el estado del producto es inactivo, trata las existencias como 0"</li>.
 <ul>.';
MLI18n::gi()->{'dummymodule_config_price__field__price__help'} = 'Por favor, introduce un margen o una reducción de precio, ya sea como porcentaje o como importe fijo. Utiliza un signo menos (-) antes del importe para indicar la reducción de precio.';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2b.price__help'} = 'Por favor, introduce un margen o una reducción de precio, ya sea como porcentaje o como importe fijo. Utiliza un signo menos (-) antes del importe para indicar la reducción de precio.';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.address.phone__label'} = 'Número de teléfono';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2bdiscounttype__values__percent'} = 'Porcentaje';
MLI18n::gi()->{'dummymodule_config_prepare__field__multimatching.itemsperpage__hint'} = 'por página de multimatching';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderimport.paymentmethod__label'} = 'Métodos de pago';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderimport.fbapaymentmethod__label'} = 'Forma de pago de los pedidos FBA';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderimport.fbapaymentmethod__help'} = 'Forma de pago para los pedidos de Amazon que son cumplidos (enviados) por Amazon (FBA). Por defecto: "dummymodule". <br><br> 
 Esta configuración es importante para las facturas y albaranes, para el posterior procesamiento de pedidos en la tienda y para algunos ERP.';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippingservice.carrierwillpickup__label'} = 'Recogida de paquetes';
MLI18n::gi()->{'dummymodule_config_prepare__field__multimatching__valuehint'} = 'Sobrescribir los productos ya emparejados por medio de la multi y la auto compensación.';
MLI18n::gi()->{'dummymodule_config_orderimport__field__importactive__help'} = 'Las órdenes se importan cada hora si la función está activada<br />. 
 <br />
 Puedes importar los pedidos manualmente pulsando el botón de la función en la cabecera de la derecha.
 <br />
 También tienes la opción de importar pedidos por cronjob (cada cuarto de hora con una tarifa plana) haciendo clic en el siguiente enlace:<br />
 <i>{#setting:sImportOrdersUrl#}</i><br />
 <br />
 <strong>IVA:</strong><br />
 <br />
 Los tipos impositivos para importar pedidos de países sólo se pueden calcular correctamente si has almacenado los tipos impositivos correspondientes en tu tienda online y si los artículos comprados se pueden identificar en la tienda online mediante el SKU.<br />
 magnalister utiliza el tipo impositivo asignado en "Importación de pedidos" > "IVA artículos externos de la tienda" como "alternativa" si el artículo no se encuentra en la tienda online<br /><br />.
 
 <strong>Sugerencia para los pedidos y la facturación de Amazon B2B</strong> (requiere la participación en Amazon Business Program):<br /><br />
 
 Amazon no transmite los números de identificación fiscal para la importación de pedidos. magnalister, por lo tanto, puede generar los pedidos B2B en la tienda web, pero no será posible crear facturas formalmente correctas en todo momento.
 <br /><br />
 Tienes la opción de activar el número de identificación fiscal manualmente a través de tu Amazon Seller Central y luego puedes mantenerlo manualmente en tu sistema de tienda o ERP.
 <br /><br />
 También puedes utilizar el servicio de facturación para pedidos B2B en Amazon. De esta forma, todos los datos legales relevantes se preparan en el recibo para el cliente.
 <br /><br />
 Todos los datos relacionados con los pedidos, incluidos los identificadores fiscales, se pueden encontrar en Seller Central en "Informes" > "Documentos fiscales" si participas en el Programa de Vendedores de Amazon Empresas. El tiempo hasta que los ID estén disponibles depende de tu contrato B2B con Amazon (después de 3 o después de 30 días).
 <br /><br />
 También puedes encontrar identificadores fiscales en "Envío por Amazon" > deslizador: "Informes" si estás registrado en FBA.';
MLI18n::gi()->{'dummymodule_configform_stocksync_values__fba'} = 'El pedido (también pedido de FBA) reduce el stock de la tienda';
MLI18n::gi()->{'dummymodule_configform_stocksync_values__rel'} = 'El pedido (sin pedido de FBA) reduce el stock de la tienda (recomendado)';
MLI18n::gi()->{'dummymodule_config_orderimport__legend__orderstatus'} = 'Sincronización del estado del pedido entre la tienda y Amazon';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderstatus.open__label'} = 'Estado del pedido en la tienda';
MLI18n::gi()->{'dummymodule_config_account_orderimport'} = 'Importación de pedidos';
MLI18n::gi()->{'dummymodule_config_orderimport__legend__importactive'} = 'Orden de Importación';
MLI18n::gi()->{'dummymodule_config_prepare__field__prepare.status__valuehint'} = 'Sólo transferir los elementos activos';
MLI18n::gi()->{'dummymodule_config_prepare__field__checkin.status__valuehint'} = 'Sólo transferir los elementos activos';
MLI18n::gi()->{'dummymodule_config_general_nosync'} = 'sin sincronización';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2bactive__values__false'} = 'No';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.address.name__label'} = 'Nombre';
MLI18n::gi()->{'dummymodule_config_account__field__mwstoken__label'} = 'Ficha MWS';
MLI18n::gi()->{'dummymodule_config_account__field__merchantid__label'} = 'Identificación del comerciante';
MLI18n::gi()->{'dummymodule_config_prepare__legend__machingbehavior'} = 'Comportamiento de la pareja';
MLI18n::gi()->{'dummymodule_config_prepare__field__multimatching__label'} = 'Partido Nuevo';
MLI18n::gi()->{'dummymodule_config_account__field__marketplaceid__label'} = 'Identificación del marketplace';
MLI18n::gi()->{'dummymodule_config_prepare__field__checkin.skuasmfrpartno__label'} = 'Número de pieza del fabricante';
MLI18n::gi()->{'dummymodule_config_account_title'} = 'Datos de acceso';
MLI18n::gi()->{'dummymodule_config_account__legend__account'} = 'Datos de acceso';
MLI18n::gi()->{'dummymodule_config_emailtemplate__field__mail.content__hint'} = 'Lista de marcadores de posición disponibles para Asunto y Contenido: 
 <dl> 
 <dt>#MARKETPLACEORDERID#</dt> 
 <dd>Identificación de pedido de Marketplace</dd> 
 <dt>#FIRSTNAME#</dt> 
 <dd>Nombre del comprador</dt> 
 <dt>#LASTNAME#</dt> 
 <dd>Apellido del comprador</dt> 
 <dt>#EMAIL#</dt> 
 <dd>Dirección de correo electrónico del comprador</dt> 
 <dt>#PASSWORD#</dt> 
 <dd>Contraseña del comprador para acceder a su Tienda. Sólo para los clientes a los que se les asigna automáticamente una contraseña - de lo contrario el marcador de posición será sustituido por &apos;(como se sabe)&apos;***.< /dd> 
 <dt>#ORDERSUMMARY#</dt> 
 <dd>Resumen de los artículos comprados. Debe escribirse en una línea separada. <br/><i>¡No puede utilizarse en el Asunto!< /i></dd> 
 <dt>#ORIGINATOR#</dt> 
 <dd>Nombre del remitente</dd> 
 </dl>.';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.default.dimension.length__label'} = 'Longitud';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.address.country__label'} = 'Tierra';
MLI18n::gi()->{'dummymodule_config_sync__field__inventorysync.price__label'} = 'Precio del artículo';
MLI18n::gi()->{'dummymodule_config_account_prepare'} = 'Preparación del artículo';
MLI18n::gi()->{'dummymodule_config_prepare__field__lang__label'} = 'Descripción del artículo';
MLI18n::gi()->{'dummymodule_config_prepare__field__itemcondition__label'} = 'Estado del artículo';
MLI18n::gi()->{'dummymodule_config_sync__legend__sync'} = 'Sincronización de inventarios';
MLI18n::gi()->{'dummymodule_config_prepare__field__quantity__label'} = 'Recuento de artículos del inventario';
MLI18n::gi()->{'dummymodule_config_prepare__field__internationalshipping__label'} = 'Envíos internacionales';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2bactive__notification'} = 'Para poder utilizar las funciones de DummyModule Business necesita tener su 
 cuenta de DummyModule activada para ello. <b>Asegúrese de que su cuenta está habilitada para los servicios de DummyModule Business.</b> 
 De lo contrario, podría experimentar errores durante la carga si esta opción está activada. 
 <br> Para actualizar tu cuenta, sigue las instrucciones de
 <a href="https://sellercentral.dummymodule.de/business/b2bregistration" target="_blank">esta página</a>.';
MLI18n::gi()->{'dummymodule_config_prepare__field__imagesize__label'} = 'Tamaño de la imagen';
MLI18n::gi()->{'dummymodule_config_orderimport__field__mwstfallback__help'} = 'Si un artículo no está introducido en la tienda online, magnalister utiliza aquí el IVA, ya que los marketplaces no especifican el IVA al importar el pedido. <br /> 
 <br /> 
 Más explicaciones:<br /> 
 Básicamente, magnalister calcula el IVA de la misma forma que lo hace el propio sistema de la tienda.<br /> El IVA por país sólo se puede tener en cuenta si el artículo se puede encontrar con su rango de números (SKU) en la tienda web.<br /> magnalister utiliza las clases de IVA configuradas de la tienda web.';
MLI18n::gi()->{'dummymodule_config_prepare__field__prepare.manufacturerfallback__help'} = 'Si se selecciona <i>Sólo B2B</i>, los productos cargados con esta opción serán visibles sólo para los clientes de negocios';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2bsellto__help'} = 'Si se selecciona <i>Sólo B2B</i>, los productos subidos con esta opción serán visibles sólo para los clientes de negocios';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderstatus.canceled__help'} = 'Aquí estableces el estado de la tienda que establecerá el estado del pedido de Amazon en "cancelar pedido". <br/><br/> 
 Nota: la cancelación parcial no es posible en esta configuración. Con esta función se cancelará todo el pedido y se abonará al cliente.';
MLI18n::gi()->{'dummymodule_config_prepare__field__multimatching.itemsperpage__help'} = 'Aquí puedes determinar cuántos productos se mostrarán por página de emparejamiento múltiple. 
 <br/>Un número mayor implicará tiempos de carga más largos (por ejemplo, 50 resultados tardarán unos 30 segundos).';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.default.dimension.height__label'} = 'Altura';
MLI18n::gi()->{'dummymodule_config_prepare__field__leadtimetoship__label'} = 'Tiempo de manipulación (en días)';
MLI18n::gi()->{'dummymodule_configform_orderimport_payment_values__textfield__title'} = 'Desde el campo de texto';
MLI18n::gi()->{'dummymodule_configform_orderimport_shipping_values__textfield__title'} = 'Desde el campo de texto';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.address.state__label'} = 'estado federal';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderstatus.fba__label'} = 'Estado del pedido FBA';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippingservice.carrierwillpickup__default'} = 'FALSO';
MLI18n::gi()->{'dummymodule_config_price__field__exchangerate_update__label'} = 'Tipo de cambio';
MLI18n::gi()->{'dummymodule_config_account_emailtemplate_sender_email'} = 'ejemplo@tiendaonline.de';
MLI18n::gi()->{'dummymodule_config_sync__field__stocksync.frommarketplace__help'} = 'Ejemplo: Si un artículo se compra 3 veces en Amazon, el inventario de la Tienda se reducirá en 3.<br /><br /> 
 <strong>Importante:</strong> ¡Esta función sólo se aplica si has activado la Importación de Pedidos!';
MLI18n::gi()->{'dummymodule_config_account_emailtemplate_sender'} = 'Tienda de ejemplo';
MLI18n::gi()->{'dummymodule_config_account__field__password__help'} = 'Introduce tu contraseña actual de DummyModule para acceder a tu cuenta de Seller Central.';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.address.email__label'} = 'Direcciones de correo electrónico';
MLI18n::gi()->{'dummymodule_config_emailtemplate__field__mail.content__label'} = 'Contenido del correo electrónico';
MLI18n::gi()->{'dummymodule_config_account__field__site__label'} = 'Sitio DummyModule';
MLI18n::gi()->{'dummymodule_config_general_mwstoken_help'} = 'El módulo de simulación requiere autenticación para transmitir datos a través de la interfaz. Introduce las claves correspondientes en "ID de comerciante", "ID de marketplace" y "Token MWS". Puedes solicitar estas claves en el marketplace correspondiente del módulo de simulación en el que quieras publicar.
 <br><br>
 Puedes encontrar instrucciones sobre cómo solicitar el token MWS en el siguiente artículo de preguntas frecuentes:
 <br><a href="https://otrs.magnalister.com/otrs/public.pl?Action=PublicFAQZoom;ItemID=997" title="DummyModule MWS" target="_blank">¿Cómo solicitar el token del módulo ficticio MWS</a>?';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2b.tax_code__matching__titledst'} = 'DummyModule Códigos de impuestos comerciales';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2b.tax_code_specific__matching__titledst'} = 'DummyModule Clases de impuestos de negocios';
MLI18n::gi()->{'dummymodule_config_prepare__legend__b2b'} = 'DummyModule Business (B2B)';
MLI18n::gi()->{'dummymodule_configform_orderimport_payment_values__DummyModule__title'} = 'Módulo de simulación';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2bdiscounttype__values__'} = 'No utilices';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2bdiscounttier1discount__label'} = 'Descuento';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2bdiscounttier2discount__label'} = 'Descuento';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2bdiscounttier3discount__label'} = 'Descuento';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2bdiscounttier4discount__label'} = 'Descuento';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2bdiscounttier5discount__label'} = 'Descuento';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.default.dimension.text__label'} = 'Descripción';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2b.price.signal__label'} = 'Importe decimal';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2b.price.signal__hint'} = 'Importe decimal';
MLI18n::gi()->{'dummymodule_config_price__field__price.signal__label'} = 'Importe decimal';
MLI18n::gi()->{'dummymodule_config_price__field__price.signal__hint'} = 'Importe decimal';
MLI18n::gi()->{'dummymodule_config_orderimport__field__customergroup__label'} = 'Grupo de clientes';
MLI18n::gi()->{'dummymodule_config_prepare__legend__apply'} = 'Crear nuevos productos';
MLI18n::gi()->{'dummymodule_config_emailtemplate__field__mail.copy__label'} = 'Copiar al remitente';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderstatus.shipped__label'} = 'Confirma el envío con';
MLI18n::gi()->{'dummymodule_config_tier_error'} = 'Módulo de simulación empresarial (B2B): ¡La configuración para el nivel de precios escalonados B2B %s no es correcta!';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.address.company__label'} = 'nombre de la empresa';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.address.city__label'} = 'ciudad';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderstatus.canceled__label'} = 'Cancelar el pedido con';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderstatus.cancelled__label'} = 'Cancelar el pedido con';
MLI18n::gi()->{'dummymodule_config_prepare__field__multimatching__help'} = 'Al activar esto, los productos ya emparejados se sobrescribirán con los nuevos emparejados ***';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2b.tax_code_container__label'} = 'Correspondencia del impuesto sobre actividades económicas por categoría';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2b.tax_code__label'} = 'Equiparación del Impuesto sobre Actividades Económicas';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2b.priceoptions__label'} = 'Opciones de precios para empresas';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2b.price__label'} = 'Precio del negocio';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2bsellto__values__b2b_only'} = 'Sólo B2B';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2bsellto__values__b2b_b2c'} = 'B2B y B2C';
MLI18n::gi()->{'dummymodule_config_price__field__exchangerate_update__hint'} = 'Actualizar automáticamente el tipo de cambio';
MLI18n::gi()->{'dummymodule_config_general_autosync'} = 'Sincronización automática mediante CronJob (recomendado)';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderstatus.carrier.additional__help'} = 'Amazon ofrece determinados proveedores estándar para su preselección. Puedes ampliar esta lista introduciendo proveedores adicionales en el campo de texto, separados por comas.';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderimport.dummymodulepromotionsdiscount__label'} = 'Promociones de Amazon';
MLI18n::gi()->{'dummymodule_config_shippinglabel__field__shippinglabel.fallback.weight__label'} = 'peso alternativo';
MLI18n::gi()->{'dummymodule_config_prepare__field__prepare.manufacturerfallback__label'} = 'Fabricante alternativo';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderstatus.carrier.additional__label'} = 'Otros transportistas';
MLI18n::gi()->{'dummymodule_config_orderimport__field__importactive__label'} = 'Activa la importación';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2bactive__label'} = 'Activa el negocio';
MLI18n::gi()->{'dummymodule_config_prepare__field__shipping.template__hint'} = 'Un grupo de procedimientos de envío específico que se establecerá para una oferta específica del vendedor. El grupo de envío del vendedor es generado y administrado por el vendedor en la interfaz de usuario para los servicios de envío.';
MLI18n::gi()->{'dummymodule_config_emailtemplate__field__mail.copy__help'} = 'Se enviará una copia a la dirección de correo electrónico del remitente.';
MLI18n::gi()->{'dummymodule_config_prepare__field__imagesize__help'} = '<p>Introduce la anchura en píxeles de la imagen tal y como debe aparecer en el marketplace. La altura se ajustará automáticamente en función de la relación de aspecto original. </p> 
 <p>Los archivos de origen se procesarán desde la carpeta de imágenes {#setting:sSourceImagePath#}, y se almacenarán en la carpeta {#setting:sImagePath#} con la anchura en píxeles seleccionada para su uso en el marketplace.</p>';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderimport.paymentmethod__help'} = '<p>Método de pago que se aplicará a todos los pedidos importados de Amazon. Estándar: "Amazon"</p> 
 <p>Esta configuración es necesaria para la factura y el aviso de envío, y para editar los pedidos posteriormente en la Tienda o a través del ERP.</p>';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderimport.dummymodulepromotionsdiscount__help'} = '<p>Magnalister importa los descuentos promocionales de Amazon como productos independientes a tu tienda online. Se crea un producto en el pedido importado para cada producto y descuento de envío.</p> 
 <p> En esta opción de configuración, puedes definir tus propios números de artículo para estos descuentos promocionales.</p>';
MLI18n::gi()->{'dummymodule_config_sync__field__inventorysync.price__help'} = '<dl> 
 <dt>Sincronización automática medíante CronJob (recomendado)</dt> 
 <dd>La función &apos;Sincronización automática&apos; sincroniza el precio de Amazon con el de la Tienda cada 4 horas, a partir de las 0.00 horas (con ***, dependiendo de la configuración).<br>Los valores serán transferidos desde la base de datos, incluyendo los cambios que se produzcan a través de un ERP o similar.<br><br> La comparación manual se puede activar pulsando el botón correspondiente en la cabecera del magnalister (a la izquierda del carrito de la compra).<br><br> 
 Además, puedes activar la comparación de acciones a través de CronJon (tarifa plana*** - máximo cada 4 horas) con el enlace:<br> 
 <i>{#setting:sSyncInventoryUrl#}</i><br> 
 Algunas peticiones de CronJob pueden ser bloqueadas, si se realizan a través de clientes que no están en la tarifa plana*** o si la petición se realiza más de una vez cada 4 horas. 
 </dd> 
 <dt>La edición de pedidos / artículos sincronizará el precio de Amazon y el de la Tienda. </dt> 
 <dd>Si el precio de la Tienda se modifica al editar un artículo, el precio actual de la Tienda se transferirá entonces a Amazon.<br> 
 ¡Los cambios que sólo se realizan en la base de datos, por ejemplo a través de un ERP, <b>no se</b> registran ni se transmiten!</dd> 
 <dt>La edición de artículos cambia el precio de Amazon.</dt> 
 <dd>Si cambias el precio del artículo en la tienda, en "Editar artículo", el precio actual del artículo se transfiere a Amazon.<br> 
 ¡Los cambios que sólo se realizan en la base de datos, por ejemplo a través de un ERP, <b>no se</b> registran ni se transmiten!</dd>
 </dl> 
 <b>Nota:</b> Se tienen en cuenta los ajustes "Configuración", "Carga de artículos" y "Cantidad de existencias".';
MLI18n::gi()->{'dummymodule_config_sync__field__stocksync.tomarketplace__help'} = '<dl> 
 <dt>Sincronización automática a través de CronJob (recomendado)</dt> 
 <dd>El stock actual de Amazon se sincronizará con el stock de la tienda cada 4 horas, a partir de las 0:00 horas (con ***, dependiendo de la configuración).<br>Los valores serán transferidos desde la base de datos, incluyendo los cambios que se produzcan a través de un ERP o similar.<br><br>La comparación manual se puede activar pulsando el botón correspondiente en la cabecera del magnalister (a la izquierda del carrito de la compra).<br><br> 
 Además, puedes activar la comparación de acciones a través de CronJon (tarifa plana*** - máximo cada 4 horas) con el enlace:<br>
 <i>{#setting:sSyncInventoryUrl#}</i><br> 
 Algunas peticiones de CronJob pueden ser bloqueadas, si se realizan a través de clientes que no están en la tarifa plana*** o si la petición se realiza más de una vez cada 4 horas. 
 </dd> 
 <dt>La edición de pedidos / artículos sincronizará el stock de Amazon y de la tienda. </dt> 
 <dd>Si el inventario de la tienda se modifica debido a un pedido o a la edición de un artículo, el inventario actual de la tienda se transferirá entonces a Amazon.<br> 
 ¡Los cambios que sólo se realizan en la base de datos, por ejemplo a través de un ERP, <b>no se</b> registran ni se transmiten!</dd>
 <dt>La edición de pedidos / artículos cambia el inventario de Amazon.</dt> 
 <dd>Por ejemplo, si un artículo de la Tienda se compra dos veces, el inventario de Amazon se reducirá en 2.<br /> Si cambias el importe del artículo en la tienda en "Editar artículo", se suma o resta la diferencia con el importe anterior.<br> 
 ¡Los cambios que sólo se realizan en la base de datos, por ejemplo a través de un ERP, <b>no se</b> registran ni se transmiten!</dd>
 </dl> 
 <b>Nota:</b> Se tienen en cuenta los ajustes "Configuración", "Carga de artículos" y "Cantidad de existencias".';
MLI18n::gi()->{'dummymodule_config_prepare__field__b2bdiscounttype__help'} = '<b>Descuento por cantidad</b><br>
 El descuento por cantidad representa los descuentos escalonados disponibles para los clientes de Amazon Business para las compras de mayor volumen. Los vendedores del Programa de Vendedores de Empresas de Amazon especifican niveles para los Precios por Cantidad. La opción "porcentaje" significa que se aplicará un porcentaje de descuento específico a las compras que incluyan la cantidad especificada.<br><br>
 <b>Ejemplo</b>: 
 Si el producto cuesta 100 dólares y el tipo de descuento es "Porcentaje", los descuentos aplicados para los <b>clientes empresariales</b>
 podría establecerse así: 
 <table><tr>
 <th style="background-color: #ddd;">Cantidad</th>
 <th style="background-color: #ddd;">Descuento</th>
 <th style="background-color: #ddd;">Precio final por producto</th>
 <tr><td>5 (o más)</td><td style="text-align: right;">10</td><td style="text-align: right;">$90</td></tr>
 <tr><td>8 (o más)</td><td style="text-align: right;">12</td><td style="text-align: right;">$88</td></tr>
 <tr><td>12 (o más)</td><td style="text-align: right;">15</td><td style="text-align: right;">$85</td></tr>
 <tr><td>20 (o más)</td><td style="text-align: right;">20</td><td style="text-align: right;">$80</td></tr>
 </table>';
MLI18n::gi()->{'dummymodule_config_account__field__tabident__help'} = '{#i18n:ML_TEXT_TAB_IDENT#}';
MLI18n::gi()->{'dummymodule_config_account__field__tabident__label'} = '{#i18n:ML_LABEL_TAB_IDENT#}';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderimport.shop__label'} = '{#i18n:form_config_orderimport_shop_lable#}';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderimport.shop__help'} = '{#i18n:form_config_orderimport_shop_help#}';
MLI18n::gi()->{'dummymodule_config_price__field__exchangerate_update__help'} = '{#i18n:form_config_orderimport_exchangerate_update_help#}';
MLI18n::gi()->{'dummymodule_config_price__field__exchangerate_update__alert'} = '{#i18n:form_config_orderimport_exchangerate_update_alert#}';
MLI18n::gi()->{'dummymodule_configform_orderstatus_sync_values__no'} = '{#i18n:dummymodule_config_general_nosync#}';
MLI18n::gi()->{'dummymodule_configform_sync_values__no'} = '{#i18n:dummymodule_config_general_nosync#}';
MLI18n::gi()->{'dummymodule_configform_stocksync_values__no'} = '{#i18n:dummymodule_config_general_nosync#}';
MLI18n::gi()->{'dummymodule_configform_pricesync_values__no'} = '{#i18n:dummymodule_config_general_nosync#}';
MLI18n::gi()->{'dummymodule_config_account__field__mwstoken__help'} = '{#i18n:dummymodule_config_general_mwstoken_help#}';
MLI18n::gi()->{'dummymodule_config_account__field__merchantid__help'} = '{#i18n:dummymodule_config_general_mwstoken_help#}';
MLI18n::gi()->{'dummymodule_config_account__field__marketplaceid__help'} = '{#i18n:dummymodule_config_general_mwstoken_help#}';
MLI18n::gi()->{'dummymodule_configform_orderstatus_sync_values__auto'} = '{#i18n:dummymodule_config_general_autosync#}';
MLI18n::gi()->{'dummymodule_configform_sync_values__auto'} = '{#i18n:dummymodule_config_general_autosync#}';
MLI18n::gi()->{'dummymodule_configform_pricesync_values__auto'} = '{#i18n:dummymodule_config_general_autosync#}';
MLI18n::gi()->{'dummymodule_config_price__field__priceoptions__help'} = '{#i18n:configform_price_field_priceoptions_help#}';
MLI18n::gi()->{'dummymodule_config_account_emailtemplate'} = '{#i18n:configform_emailtemplate_legend#}';
MLI18n::gi()->{'dummymodule_config_emailtemplate__legend__mail'} = '{#i18n:configform_emailtemplate_legend#}';
MLI18n::gi()->{'dummymodule_config_emailtemplate__field__mail.send__label'} = '{#i18n:configform_emailtemplate_field_send_label#}';
MLI18n::gi()->{'dummymodule_config_emailtemplate__field__mail.send__help'} = '{#i18n:configform_emailtemplate_field_send_help#}';
MLI18n::gi()->{'dummymodule_config_orderimport__field__orderstatus.carrier.default__label'} = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Transportista';
MLI18n::gi()->{'dummymodule_config_account_emailtemplate_content'} = '<style><!--
 body {
 font: 12px sans-serif;
 }
 table.ordersummary {
 width: 100%;
 border: 1px solid #e8e8e8;
 }
 table.ordersummary td {
 padding: 3px 5px;
 }
 table.ordersummary thead td {
 background: #cfcfcf;
 color: #000;
 font-weight: bold;
 text-align: center;
 }
 table.ordersummary thead td.name {
 text-align: left;
 }
 table.ordersummary tbody tr.even td {
 background: #e8e8e8;
 color: #000;
 }
 table.ordersummary tbody tr.odd td {
 background: #f8f8f8;
 color: #000;
 }
 table.ordersummary td.price,
 table.ordersummary td.fprice {
 text-align: right;
 white-space: nowrap;
 }
 table.ordersummary tbody td.qty {
 text-align: center;
 }
 --></style>
 <p>Hola, #NOMBRE# #APELLIDO#:</p>
 <p>Muchas gracias por tu pedido. Has realizado un pedido en nuestra tienda a través de #MARKETPLACE#:
 </p>#RESUMENPEDIDO#
 <p>Se aplican además gastos de envío.
 </p><p>&nbsp;</p>
 <p>Saludos,</p>
 <p>El equipo de la tienda online</p>';
MLI18n::gi()->{'dummymodule_configform_orderimport_payment_values__textfield__textoption'} = '1';
MLI18n::gi()->{'dummymodule_configform_orderimport_shipping_values__textfield__textoption'} = '1';
MLI18n::gi()->{'dummymodule_configform_orderimport_payment_values__Amazon__title'} = '';
