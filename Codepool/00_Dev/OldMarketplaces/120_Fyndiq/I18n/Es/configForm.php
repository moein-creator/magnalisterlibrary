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
MLI18n::gi()->{'fyndiq_config_account_emailtemplate_subject'} = 'Tu pedido en #SHOPURL#';
MLI18n::gi()->{'fyndiq_config_orderimport__field__mwst.fallback__label'} = 'Tienda del IVA-artículo externo';
MLI18n::gi()->{'fyndiq_config_orderimport__legend__mwst'} = 'IVA';
MLI18n::gi()->{'fyndiq_config_account__field__mpusername__label'} = 'Nombre de usuario';
MLI18n::gi()->{'fyndiq_config_prepare__legend__upload'} = 'Cargar elementos: Presets';
MLI18n::gi()->{'fyndiq_config_price__field__price.signal__help'} = 'Este campo de texto se tomará como posición después del punto decimal para los datos transmitidos a Fyndiq.<br><br>
 <strong>Example:</strong><br>
 value in textfield: 99<br>
 price origin: 5.58<br>
 final result: 5.99<br><br>
 Esta función es útil para los porcentajes de recargo y rebaja.<br>
 Deja este campo abierto si no quieres transferir un decimal.<br> El formato de entrada es un número entero con un máximo de 2 dígitos.';
MLI18n::gi()->{'fyndiq_config_orderimport__field__mwst.fallback__help'} = 'El IVA no se puede determinar si el artículo no se transmite a través de magnalister.< br /> 
 Solución: El valor % aquí insertado se asignará a todos los productos en los que no se conoce el IVA mientras la importación de la orden de Fyndiq.';
MLI18n::gi()->{'fyndiq_config_orderimport__field__orderstatus.open__help'} = 'El estado se transfiere automáticamente a la tienda tras un nuevo pedido en Fyndiq. <br /> 
 Si utilizas un procedimiento de reclamación conectado***, se recomienda establecer el estado del pedido en "Pagado" ("Ajustes" > "Estado del pedido").';
MLI18n::gi()->{'fyndiq_config_orderimport__field__customergroup__help'} = 'El grupo de clientes en el que deben clasificarse los clientes de los nuevos pedidos.';
MLI18n::gi()->{'fyndiq_config_orderimport__field__mwst.fallback__hint'} = 'El tipo impositivo utilizado al importar el pedido del artículo fuera de la tienda en %.';
MLI18n::gi()->{'fyndiq_config_prepare__field__vat__label'} = 'Correspondencia de los tramos de impuestos';
MLI18n::gi()->{'fyndiq_config_orderimport__legend__orderstatus'} = 'Sincronización del estado del pedido desde la tienda a Fyndiq';
MLI18n::gi()->{'fyndiq_config_account_sync'} = 'Sincronización del inventario';
MLI18n::gi()->{'fyndiq_config_prepare__field__checkin.quantity__label'} = 'Cantidad de existencias';
MLI18n::gi()->{'fyndiq_config_sync__field__stocksync.tomarketplace__label'} = 'Tienda de cambio de inventario';
MLI18n::gi()->{'fyndiq_config_sync__field__stocksync.frommarketplace__label'} = 'cambio de acciones Fyndiq';
MLI18n::gi()->{'fyndiq_config_prepare__field__prepare.status__label'} = 'Filtro de estado';
MLI18n::gi()->{'fyndiq_config_prepare__field__checkin.status__label'} = 'Filtro de estado';
MLI18n::gi()->{'fyndiq_config_orderimport__field__preimport.start__help'} = 'Hora de inicio de la primera importación de pedidos. Ten en cuenta que esto no es posible para cualquier momento del pasado. Los datos están disponibles en Fyndiq durante una semana como máximo.';
MLI18n::gi()->{'fyndiq_config_orderimport__field__preimport.start__hint'} = 'Hora de inicio';
MLI18n::gi()->{'fyndiq_config_prepare__field__vat__matching__titlesrc'} = 'tipo de impuesto sobre el comercio';
MLI18n::gi()->{'fyndiq_config_prepare__field__customshipping__keytitle'} = 'Texto de envío';
MLI18n::gi()->{'fyndiq_config_orderimport__field__orderimport.shippingmethod__label'} = 'Servicio de envío de los pedidos';
MLI18n::gi()->{'fyndiq_config_orderimport__field__orderimport.shippingmethod__help'} = 'Métodos de envío que se asignarán a todos los pedidos de Fyndiq. Estándar: "Fyndiq"<br><br> 
 Esta configuración es necesaria para la factura y el aviso de envío, y para editar los pedidos posteriormente en la Tienda o a través del ERP.';
MLI18n::gi()->{'fyndiq_config_prepare__field__shippingcost__label'} = 'Gastos de envío (EUR)';
MLI18n::gi()->{'fyndiq_config_prepare__field__customshipping__valuetitle'} = 'Gastos de envío';
MLI18n::gi()->{'fyndiq_config_checkin_badshippingcost'} = 'El campo para los gastos de envío debe ser numérico.';
MLI18n::gi()->{'fyndiq_config_orderimport__field__orderstatus.shipped__help'} = 'Selecciona el estado de la tienda, que establecerá automáticamente el estado de Ricardo en "Confirmar envío".';
MLI18n::gi()->{'fyndiq_config_prepare__field__imagesize__hint'} = 'Guardado en: {#setting:sImagePath#}';
MLI18n::gi()->{'fyndiq_config_account_producttemplate'} = 'Plantilla de producto';
MLI18n::gi()->{'fyndiq_config_price__field__priceoptions__label'} = 'opciones de precio';
MLI18n::gi()->{'fyndiq_config_price__legend__price'} = 'Cálculo del precio';
MLI18n::gi()->{'fyndiq_config_account_price'} = 'Marca';
MLI18n::gi()->{'fyndiq_config_price__field__price__label'} = 'Precio';
MLI18n::gi()->{'fyndiq_config_prepare__legend__prepare'} = 'Preparar artículos';
MLI18n::gi()->{'fyndiq_config_prepare__field__checkin.quantity__help'} = 'Por favor, introduce la cantidad de existencias que deben estar disponibles en el marketplace.<br/> 
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
MLI18n::gi()->{'fyndiq_config_price__field__price__help'} = 'Por favor, introduce un margen o una reducción de precio, ya sea como porcentaje o como importe fijo. Utiliza un signo menos (-) antes del importe para indicar la reducción de precio.';
MLI18n::gi()->{'fyndiq_config_prepare__field__imagepath__label'} = 'Ruta de la imagen';
MLI18n::gi()->{'fyndiq_config_account__field__mppassword__label'} = 'Contraseña';
MLI18n::gi()->{'fyndiq_config_orderimport__field__orderstatus.open__label'} = 'Estado del pedido en la tienda';
MLI18n::gi()->{'fyndiq_config_orderimport__legend__importactive'} = 'Ordenar la importación';
MLI18n::gi()->{'fyndiq_config_account_orderimport'} = 'Importación de pedidos';
MLI18n::gi()->{'fyndiq_config_prepare__field__prepare.status__valuehint'} = 'sólo tomar el artículo activo';
MLI18n::gi()->{'fyndiq_config_prepare__field__checkin.status__valuehint'} = 'sólo tomar el artículo activo';
MLI18n::gi()->{'fyndiq_config_account__legend__account'} = 'Datos de acceso';
MLI18n::gi()->{'fyndiq_config_account_title'} = 'Marca';
MLI18n::gi()->{'fyndiq_config_account_prepare'} = 'Preparación del artículo';
MLI18n::gi()->{'fyndiq_config_prepare__field__lang__label'} = 'Descripción del artículo';
MLI18n::gi()->{'fyndiq_config_sync__legend__sync'} = 'Sincronización de inventarios';
MLI18n::gi()->{'fyndiq_config_orderimport__field__importactive__help'} = '¿Importar pedidos del marketplace? <br/><br/>Si está activada, los pedidos se importan automáticamente cada hora.<br><br>La importación manual se puede activar haciendo clic en el botón correspondiente en la cabecera del magnalister (a la izquierda de la cesta de la compra). <br><br>Además, puedes activar la comparación de existencias a través de CronJon (tarifa plana*** - máximo cada 4 horas) con el enlace:<br> 
 <i>{#setting:sImportOrdersUrl#}</i><br> 
 Algunas solicitudes de CronJob pueden bloquearse si se realizan a través de clientes que no están en tarifa plana*** o si la solicitud se realiza más de una vez cada 4 horas';
MLI18n::gi()->{'fyndiq_config_prepare__field__imagesize__label'} = 'Tamaño de la imagen';
MLI18n::gi()->{'fyndiq_config_sync__field__stocksync.frommarketplace__help'} = 'Si, por ejemplo, un artículo se compra 3 veces en Fyndiq, el inventario de la tienda se reducirá en 3.<br /><br /> 
 <strong>Importante:</strong> ¡Esta función sólo funciona si has activado la importación de pedidos!';
MLI18n::gi()->{'fyndiq_config_prepare__field__identifier__label'} = 'Identificador';
MLI18n::gi()->{'fyndiq_config_account__field__mpapitoken__help'} = 'Ve a la página <a href="https://fyndiq.de/merchant/settings/api/" target="_blank"> y haz clic en crear cuenta. Después de registrar tu cuenta, inicia sesión y ve a Configuración -> API. Haz clic en generar token de API v2, que generará el token en el campo API-token. Copia el contenido del campo. El nombre de usuario será el mismo que el de la cuenta de vendedor registrada previamente en las páginas de vendedor de Fyndiq.';
MLI18n::gi()->{'fyndiq_config_prepare__field__vat__matching__titledst'} = 'Tipo impositivo de Fyndiq';
MLI18n::gi()->{'fyndiq_config_orderimport__field__service__help'} = 'Fyndiq sólo permite ciertos servicios de entrega';
MLI18n::gi()->{'fyndiq_config_orderimport__field__preimport.start__label'} = 'primero de la fecha';
MLI18n::gi()->{'fyndiq_config_price__field__exchangerate_update__label'} = 'Tipo de cambio';
MLI18n::gi()->{'fyndiq_config_account_emailtemplate_sender_email'} = 'ejemplo@tiendaonline.de';
MLI18n::gi()->{'fyndiq_config_account_emailtemplate_sender'} = 'Tienda de ejemplo';
MLI18n::gi()->{'fyndiq_config_orderimport__field__service__label'} = 'Servicio de entrega';
MLI18n::gi()->{'fyndiq_config_price__field__price.signal__label'} = 'Importe decimal';
MLI18n::gi()->{'fyndiq_config_price__field__price.signal__hint'} = 'Importe decimal';
MLI18n::gi()->{'fyndiq_config_orderimport__field__customergroup__label'} = 'Grupo de clientes';
MLI18n::gi()->{'fyndiq_config_orderimport__field__orderstatus.shipped__label'} = 'Confirma el envío con';
MLI18n::gi()->{'fyndiq_config_price__field__exchangerate_update__valuehint'} = 'actualizar automáticamente el tipo de cambio';
MLI18n::gi()->{'fyndiq_config_account__field__mpapitoken__label'} = 'Ficha de autenticación';
MLI18n::gi()->{'fyndiq_config_sync__field__inventorysync.price__label'} = 'Precio del artículo';
MLI18n::gi()->{'fyndiq_config_price__field__price.usespecialoffer__label'} = 'Utiliza también precios especiales';
MLI18n::gi()->{'fyndiq_config_orderimport__field__importactive__label'} = 'Activa la importación';
MLI18n::gi()->{'fyndiq_config_prepare__field__imagesize__help'} = '<p>Introduce la anchura en píxeles de la imagen tal y como debe aparecer en el marketplace. La altura se ajustará automáticamente en función de la relación de aspecto original. </p> 
 <p>Los archivos de origen se procesarán desde la carpeta de imágenes {#setting:sSourceImagePath#}, y se almacenarán en la carpeta {#setting:sImagePath#} con la anchura en píxeles seleccionada para su uso en el marketplace.</p>';
MLI18n::gi()->{'fyndiq_config_sync__field__inventorysync.price__help'} = '<p>El precio actual de Fyndiq se sincronizará con el stock de la tienda cada 4 horas, a partir de las 0:00 horas (con ***, dependiendo de la configuración)<br> 
 Los valores se transferirán desde la base de datos, incluyendo los cambios que se produzcan a través de un ERP o similar.<br><br> 
 <b>Pista:</b> Se tendrán en cuenta los ajustes en &apos;Configuración&apos;, &apos;cálculo de precios&apos;.';
MLI18n::gi()->{'fyndiq_config_sync__field__stocksync.tomarketplace__help'} = '<dl> 
 <dt>Sincronización automática a través de CronJob (recomendado)</dt> 
 <dd>El stock actual de Fyndiq se sincronizará con el stock de la tienda cada 4 horas, a partir de las 0:00 horas (con ***, dependiendo de la configuración).<br>Los valores se transferirán desde la base de datos, incluyendo los cambios que se produzcan a través de un ERP o similar.<br><br>La comparación manual se puede activar pulsando el botón correspondiente en la cabecera del magnalister (a la izquierda del carrito de la compra).<br><br> 
 Además, puedes activar la comparación de acciones a través de CronJon (tarifa plana*** - máximo cada 4 horas) con el enlace:<br>
 <i>{#setting:sSyncInventoryUrl#}</i><br>
 
 Algunas solicitudes de CronJob pueden bloquearse, si se realizan a través de clientes que no están en la tarifa plana*** o si la solicitud se realiza más de una vez cada 4 horas. 
 </dd> 
 </dl> 
 <b>Nota:</b> Se tienen en cuenta los ajustes "Configuración", "Carga de artículos" y "Cantidad de existencias".';
MLI18n::gi()->{'fyndiq_config_account__field__tabident__help'} = '{#i18n:ML_TEXT_TAB_IDENT#}';
MLI18n::gi()->{'fyndiq_config_account__field__tabident__label'} = '{#i18n:ML_LABEL_TAB_IDENT#}';
MLI18n::gi()->{'fyndiq_config_orderimport__field__orderimport.shop__label'} = '{#i18n:form_config_orderimport_shop_lable#}';
MLI18n::gi()->{'fyndiq_config_orderimport__field__orderimport.shop__help'} = '{#i18n:form_config_orderimport_shop_help#}';
MLI18n::gi()->{'fyndiq_config_price__field__exchangerate_update__help'} = '{#i18n:form_config_orderimport_exchangerate_update_help#}';
MLI18n::gi()->{'fyndiq_config_price__field__exchangerate_update__alert'} = '{#i18n:form_config_orderimport_exchangerate_update_alert#}';
MLI18n::gi()->{'fyndiq_config_account_emailtemplate'} = '{#i18n:configform_emailtemplate_legend#}';
