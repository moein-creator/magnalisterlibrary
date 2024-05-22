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
 * (c) 2010 - 2024 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */
MLI18n::gi()->{'ebay_config_orderimport__field__orderimport.paymentstatus__help'} = '';

MLI18n::gi()->set('ebay_prepare_apply_form_field_description_hint_metafield', '<dt>#Espacio de nombres y clave#</dt><dd>P. ej.</dd>', true);
MLI18n::gi()->{'orderstatus_carrier_default_send_order_carrier'} = 'Pass order shipping carrier';
MLI18n::gi()->{'ebay_config_orderimport__field__customergroup__help'} = '{#i18n:global_config_orderimport_field_customergroup_help#}';
MLI18n::gi()->{'ebay_config_orderimport__field__update.paymentstatus__label'} = 'Cambio del estado activo';
MLI18n::gi()->{'ebay_config_orderimport__field__updateablepaymentstatus__help'} = 'Estado de los pedidos que se pueden cambiar para los pagos de eBay.
			                Si el pedido tiene un estado diferente, no se cambiará para los pagos de eBay.<br /><br />
			                Si no deseas que el estado del pago se cambie en absoluto para los pagos de eBay, desactiva la casilla de verificación.';
MLI18n::gi()->{'ebay_prepare_apply_form__field__description__hint'} = 'Lista de marcadores de posición disponibles para la descripción del producto:<dl><dt>#TITLE#</dt><dd>Nombre del producto (título)</dd><dt>#ARTNR#</dt><dd>Número de pieza</dd><dt>#PID#</dt><dd>Nombre del producto.ID</dd><dt>#SHORTDESCRIPTION#</dt><dd>Descripción breve de la tienda</dd><dt>#DESCRIPTION#</dt><dd>Descripción de la tienda</dd><dt>#MOBILEDESCRIPTION#</dt><dd>Descripción breve para dispositivos móviles, si se almacena</dd><dt>#Imagen1#</dt><dd>primera imagen del producto</dd><dt>#Imagen2# etc.</dt><dd>segunda imagen del producto, con #Imagen3#, #Imagen4# etc. se pueden transmitir más imágenes, tantas como haya disponibles en la tienda.</dd> <dt>#TAGS#</dt>
            <dd>TAGS</dd></dl>';
MLI18n::gi()->{'ebay_config_producttemplate__field__template.content__hint'} = 'Lista de marcadores de posición disponibles para la descripción del producto:<dl><dt>#TITLE#</dt><dd>Nombre del producto (título)</dd><dt>#ARTNR#</dt><dd>Número de pieza</dd><dt>#PID#</dt><dd>Nombre del producto.ID</dd><dt>#SHORTDESCRIPTION#</dt><dd>Descripción breve de la tienda</dd><dt>#DESCRIPTION#</dt><dd>Descripción de la tienda</dd><dt>#MOBILEDESCRIPTION#</dt><dd>Descripción breve para dispositivos móviles, si se almacena</dd><dt>#Imagen1#</dt><dd>primera imagen del producto</dd><dt>#Imagen2# etc.</dt><dd>segunda imagen del producto, con #Imagen3#, #Imagen4# etc. se pueden transmitir más imágenes, tantas como haya disponibles en la tienda.</dd> <dt>#TAGS#</dt>
            <dd>TAGS</dd></dl>';
MLI18n::gi()->{'ebay_config_orderimport__field__orderimport.paymentstatus__label'} = 'Estado del pago en la tienda';
MLI18n::gi()->{'ebay_config_orderimport__field__updateablepaymentstatus__label'} = 'Permitir el estado del número y cambiarlo si';
MLI18n::gi()->{'ebay_config_orderimport__field__paidstatus__label'} = 'Estado del pedido/pago para pedidos pagados de eBay';
MLI18n::gi()->{'ebay_config_orderimport__field__paidstatus__help'} = '<p>Los pedidos de eBay a veces los paga el comprador con un tiempo de retraso.
<br><br>
Para que pueda separar los pedidos no pagados de los pedidos pagados, puede seleccionar un estado de pedido de tienda online y un estado de pago independientes para los pedidos pagados de eBay.
<br><br>
Si se importan pedidos de eBay que aún no se han pagado, se aplica el estado de pedido que ha definido anteriormente en "Importación de pedidos" > "Estado del pedido en la tienda"."
<br><br>
Si arriba ha activado "Importar sólo pedidos pagados", también se utilizará el "Estado del pedido en la tienda" de arriba. En este caso, la función aquí aparece atenuada.
';
MLI18n::gi()->{'ebay_config_orderimport__field__paymentstatus.paid__label'} = 'Estado de los pagos';
MLI18n::gi()->{'ebay_config_orderimport__field__orderstatus.paid__label'} = 'Estado del pedido';
MLI18n::gi()->{'ebay_config_orderimport__field__orderimport.paymentstatus__hint'} = '';
MLI18n::gi()->{'ebay_config_orderimport__field__updateable.paymentstatus__label'} = '';
MLI18n::gi()->{'ebay_config_orderimport__field__orderstatus.paid__help'} = '';
MLI18n::gi()->{'ebay_config_orderimport__field__updateable.paymentstatus__help'} = '';
MLI18n::gi()->{'ebay_config_orderimport__field__paymentstatus.paid__help'} = '';

MLI18n::gi()->{'ebay_config_producttemplate__field__template.name__help'} = '<dl> 
    <dt>Nombre del producto en eBay</dt> 
    <dd>Decide cómo nombrar el producto en eBay. 
    El marcador de posición <b>#TITLE#</b> se sustituirá por el nombre del producto de la tienda.</dd></dl>';
MLI18n::gi()->{'ebay_config_producttemplate__field__template.name__hint'} = 'Marcador de posición: #TITLE# - Nombre del producto';
