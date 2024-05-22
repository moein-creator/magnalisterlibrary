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
MLI18n::gi()->{'ebay_config_orderimport__field__updateablepaymentstatus__label'} = 'Actualizar el estado del pago cuando';
MLI18n::gi()->{'ebay_config_orderimport__field__update.paymentstatus__label'} = 'Modificación de estado activa';
MLI18n::gi()->{'ebay_config_orderimport__field__paidstatus__help'} = 'A veces los pedidos de eBay son pagados con retraso por el cliente.
 <br><br>
 Para separar los pedidos impagados de los pagados, puedes seleccionar el estado de pedido/pago de tu propia tienda online para los pedidos pagados de eBay.
 <br><br>
 Para separar los pedidos pendientes de pago de los pedidos pagados, puedes seleccionar el estado de pedido/pago de tu propia tienda online para los pedidos pagados de eBay.
 <br><br>
 Si has activado "Sólo importar pedidos marcados como "Pagados"" más arriba, también se utilizará el "Estado del pedido en la tienda" más arriba. En ese caso, la función aparece en gris.';
MLI18n::gi()->{'ebay_config_orderimport__field__orderimport.paymentstatus__hint'} = 'Por favor, selecciona qué estado de pago del sistema de la tienda debe establecerse en los detalles del pedido al importar el pedido de magnalister.';
MLI18n::gi()->{'ebay_config_orderimport__field__orderimport.paymentstatus__label'} = 'Estado del pago en la tienda';
MLI18n::gi()->{'ebay_config_orderimport__field__paymentstatus.paid__label'} = 'Estado de los pagos';
MLI18n::gi()->{'ebay_config_orderimport__field__paidstatus__label'} = 'Estado de los pedidos pagados en eBay';
MLI18n::gi()->{'ebay_config_orderimport__field__updateablepaymentstatus__help'} = 'Estado del pedido que se puede cambiar en Pagos de eBay.
 Si el pedido tiene un estado diferente, no se cambiará en Pagos de eBay.<br /><br />. 
 Si no deseas cambios en el estado de pago de Pagos de eBay, desactiva la casilla de verificación.<br /><br />.
 <b>Nota:</b> El estado de los pedidos colectivos sólo cambia cuando se han pagado todas las piezas.';

MLI18n::gi()->{'ebay_config_price__field__chinese.priceoptions__help'} = '<p>Con esta función puedes introducir diferentes precios para {#setting:currentMarketplaceName#} y sincronizarlos automáticamente.</p>
<p>Para ello, selecciona un grupo de clientes de tu tienda online utilizando el dropdown que está al lado.</p>
<p>Si no introduces un precio en el nuevo grupo de clientes, se utilizará automáticamente el precio por defecto de la tienda online. Esto hace que sea muy fácil introducir un precio diferente para unos pocos artículos. Las demás configuraciones de precios también se aplican.</p>
<p><b>Ejemplo de aplicación:</b></p>
<ul>
<li>Crea un grupo de clientes en su tienda web, por ejemplo, "{#setting:currentMarketplaceName#} clientes".</li>
<li>En tu tienda web, introduce los precios deseados para los artículos del nuevo grupo de clientes.</li>
<li>Crea un grupo de clientes en tu tienda web.</li>
</ul>
<p>También se puede utilizar el modo de descuento de los grupos de clientes. Allí puedes introducir un descuento (porcentual). Si el modo de descuento está activado en el artículo Shopware, el precio con descuento se transmite al marketplace a través de magnalister. Es importante que el precio del marketplace no se muestre como un precio rebajado.</p>';
MLI18n::gi()->{'ebay_config_price__field__fixed.priceoptions__help'} = '<p>Con esta función puedes introducir diferentes precios para {#setting:currentMarketplaceName#} y sincronizarlos automáticamente.</p>
<p>Para ello, selecciona un grupo de clientes de su tienda online utilizando el dropdown que está al lado.</p>
<p>Si no introduces un precio en el nuevo grupo de clientes, se utilizará automáticamente el precio por defecto de la tienda online. Esto hace que sea muy fácil introducir un precio diferente para unos pocos artículos. Las demás configuraciones de precios también se aplican.</p>
<p><b>Ejemplo de aplicación:</b></p>
<ul>
<li>Crea un grupo de clientes en su tienda web, por ejemplo, "{#setting:currentMarketplaceName#} clientes"</li>
<li>En tu tienda web, introduce los precios deseados para los artículos del nuevo grupo de clientes.</li>
<li>Crea un grupo de clientes en su tienda web.</li>
</ul>
<p>También se puede utilizar el modo de descuento de los grupos de clientes. Allí puedes introducir un descuento (porcentual). Si el modo de descuento está activado en el artículo Shopware, el precio con descuento se transmite al marketplace a través de magnalister. Es importante que el precio del marketplace no se muestre como un precio rebajado.</p>';
MLI18n::gi()->{'ebay_config_orderimport__field__orderimport.paymentmethod__label'} = 'Forma de pago de los pedidos';
MLI18n::gi()->{'ebay_config_orderimport__field__orderstatus.paid__label'} = 'Estado del pedido';
MLI18n::gi()->{'ebay_config_orderimport__field__orderimport.shippingmethod__help'} = '<p>Tipo de envío que se asigna a todos los pedidos de eBay durante la importación de pedidos.
Valor predeterminado: "Asignación automática"</p>
<p>Tipo de envío que se asigna a todos los pedidos de eBay durante la importación de pedidos.</p>
<p>Si seleccionas "Asignación automática", magnalister utilizarás el método de envío seleccionado por el comprador en eBay.
A continuación, esto también se crea en Shopware > Configuración > Gastos de envío.</p>
<p>También puedes definir todos los demás métodos de envío disponibles en la lista bajo Shopware > Configuración > Gastos de envío y luego usarlos aquí.</p>
<p>Este ajuste es importante para la impresión de facturas y albaranes, así como para el posterior procesamiento del pedido en la tienda y en los sistemas de gestión de mercancías.</p>';
MLI18n::gi()->{'ebay_config_carrier_option_group_shopfreetextfield_option_carrier'} = 'Seleccionar empresa de transporte desde un campo de texto libre de la tienda web (pedidos)';
MLI18n::gi()->{'ebay_config_producttemplate__field__template.content__label'} = 'Plantilla de descripción del producto';
MLI18n::gi()->{'ebay_config_orderimport__field__orderimport.paymentmethod__help'} = '<p>Método de pago que se asigna a todos los pedidos de eBay durante la importación de pedidos.
</p>
<p>
También puedes definir todas las formas de pago en la lista en Shopware > Configuración > Formas de pago y luego utilizarlas aquí.
</p>
<p>
Este ajuste es importante para imprimir facturas y albaranes y para el posterior procesamiento del pedido en la tienda y en los sistemas de gestión de mercancías.
</p>';
MLI18n::gi()->{'ebay_config_orderimport__field__orderimport.shippingmethod__label'} = 'Método de envío de los pedidos';
MLI18n::gi()->{'ebay_config_carrier_option_group_marketplace_carrier'} = 'Empresas de transporte sugeridas por eBay';
MLI18n::gi()->{'ebay_config_producttemplate_content'} = '<style>
ul.magna_properties_list {
    margin: 0 0 20px 0;
    list-style: none;
    padding: 0;
    display: inline-block;
    width: 100%
}
ul.magna_properties_list li {
    border-bottom: none;
    width: 100%;
    height: 20px;
    padding: 6px 5px;
    float: left;
    list-style: none;
}
ul.magna_properties_list li.odd {
    background-color: rgba(0, 0, 0, 0.05);
}
ul.magna_properties_list li span.magna_property_name {
    display: block;
    float: left;
    margin-right: 10px;
    font-weight: bold;
    color: #000;
    line-height: 20px;
    text-align: left;
    font-size: 12px;
    width: 50%;
}
ul.magna_properties_list li span.magna_property_value {
    color: #666;
    line-height: 20px;
    text-align: left;
    font-size: 12px;

    width: 50%;
}
</style>
<p>#TITLE#</p>
<p>#ARTNR#</p>
<p>#SHORTDESCRIPTION#</p>
<p>#PICTURE1#</p>
<p>#PICTURE2#</p>
<p>#PICTURE3#</p>
<p>#DESCRIPTION#</p>
<p>#Descripción1# #Campo de texto libre1#</p>
<p>#Descripción2# #Campo de texto libre2#</p>
<div>#PROPERTIES#</div>';
MLI18n::gi()->{'ebay_config_price__field__fixed.priceoptions__label'} = 'Precio de venta del grupo de clientes';
MLI18n::gi()->{'ebay_config_carrier_option_group_additional_option'} = 'Opción adicional';
MLI18n::gi()->{'ebay_config_orderimport__field__orderstatus.open__help'} = '                Aquí puedes definir el estado del pedido en la tienda Web que debe recibir automáticamente un nuevo pedido recibido de eBay.
<br><br>
Ten en cuenta que aquí se importan tanto los pedidos de eBay pagados como los no pagados.
<br><br>
No obstante, puedes utilizar la siguiente función "Importar sólo pedidos pagados" para especificar que sólo se importen a su tienda online los pedidos de eBay pagados.
<br><br><br>

Puedes definir un estado de pedido independiente para los pedidos pagados de eBay más abajo, en "Sincronización de estado de pedido" > "Estado de pedido/pago para pedidos pagados de eBay".
            ';
MLI18n::gi()->{'ebay_config_price__field__chinese.priceoptions__label'} = 'Precio del grupo de clientes';
MLI18n::gi()->{'ebay_config_orderimport__field__customergroup__help'} = '{#i18n:global_config_orderimport_field_customergroup_help#}';
MLI18n::gi()->{'ebay_config_producttemplate__field__template.content__hint'} = '
Lista de marcadores de posición disponibles para la descripción del producto:
<dl>
    <dt>#TITLE#</dt>
        <dd>Nombre del producto (título)</dd><br>
    <dt>#ARTNR#</dt>
        <dd>Número de artículo en la tienda</dd><br>
    <dt>#PID#</dt>
        <dd>Identificación del producto en la tienda</dd><br>
    <!--<dt>#PRECIO#</dt>
            <dd>Precio</dd>
    <dt>#VPE#</dt>
            <dd>Precio por unidad de embalaje</dd>-->
    <dt>#SHORTDESCRIPTION#</dt>
        <dd>Breve descripción de la tienda</dd><br>
    <dt>#DESCRIPCIÓN#</dt>
        <dd>Descripción de la tienda</dd><br>
    <dt>#ESCRIPCIÓNMOVIL#</dt>
        <dd>Descripción breve para dispositivos móviles, si se almacena</dd><br>
    <dt>#IMÁGEN1#</dt>
        <dd>primera imagen del producto</dd><br>
    <dt>#Imagen2# etc.</dt>
            <dd>segunda imagen del producto; con #Imagen3#, #Imagen4# etc. se pueden proporcionar más imágenes, tantas como estén disponibles en la tienda.</dd><br><dt>Campos de texto libre del artículo:</dt><br><dt>#Descripción1#&nbsp;#Campo de texto libre1#</dt><dt>#Descripción2#&nbsp;#Campo de texto libre2#</dt><dt>#Descripción..#&nbsp;#Campo de texto libre..#</dt><br><dd>&nbspEl número después del marcador de posición (por ejemplo #Campo de texto libre1#) corresponde a la posición del campo de texto libre.
                <br> Ver Ajustes > Ajustes básicos > Artículos > Campos de texto libre del artículo</dd><br><dt>#PROPIEDADES#</dt><dd>Una lista de todas las propiedades del producto. La apariencia se puede controlar mediante CSS (ver código de la plantilla estándar)</dd></dl>';
MLI18n::gi()->{'ebay_config_orderimport__field__updateable.paymentstatus__label'} = '';
MLI18n::gi()->{'ebay_config_orderimport__field__orderstatus.paid__help'} = '';
MLI18n::gi()->{'ebay_config_price__field__fixed.priceoptions__hint'} = '';
MLI18n::gi()->{'ebay_config_orderimport__field__paymentstatus.paid__help'} = '';
MLI18n::gi()->{'ebay_config_orderimport__field__orderimport.shippingmethod__hint'} = '';
MLI18n::gi()->{'ebay_config_price__field__chinese.priceoptions__hint'} = '';
MLI18n::gi()->{'ebay_config_orderimport__field__updateable.paymentstatus__help'} = '';
MLI18n::gi()->{'ebay_config_orderimport__field__orderimport.paymentmethod__hint'} = '';