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
MLI18n::gi()->{'hood_config_orderimport__field__updateablepaymentstatus__label'} = 'Actualizar el estado del pago cuando';
MLI18n::gi()->{'hood_config_orderimport__field__paymentstatus.paid__help'} = 'Por favor, selecciona qué estado de pago del sistema de la tienda debe establecerse en los detalles del pedido al importar el pedido de magnalister.';
MLI18n::gi()->{'hood_config_orderimport__field__orderimport.paymentstatus__label'} = 'Estado del pago en la tienda';
MLI18n::gi()->{'hood_config_orderimport__field__paymentstatus.paid__label'} = 'Estado de los pagos';
MLI18n::gi()->{'hood_config_orderimport__field__updateablepaymentstatus__help'} = 'Estado del pedido que puede ser activado por los pagos de Hood.de. 
 Si el pedido tiene un estado diferente, éste no puede ser modificado por un pago de Hood.de.<br /><br />.
 Si no deseas que se modifique tu estado debido al pago de Hood.de, desmarca la casilla.<br /><br />
 <b>Ten en cuenta:</b>El estado de los pedidos combinados no se modificará hasta que se paguen en su totalidad.';
MLI18n::gi()->{'hood_config_orderimport__field__paidstatus__label'} = 'hitmeister_prepareform_category';
MLI18n::gi()->{'hood_config_orderimport__field__paidstatus__help'} = '<p>Aquí defines el pago y el estado del pedido que obtendrá un pedido en la tienda al pagar a través de PayPal en Hood.de.</p> 
 <p>Si un cliente compra tu producto en Hood.de, el pedido se transferirá a tu tienda inmedíatamente. Por lo tanto, el parámetro de arte de pago se tomará de tu configuración en "método de pago para pedidos" o se establecerá en "Hood.de".</p>
 <p>Además, magnalister controla cada hora si un cliente ha pagado después de la primera importación del pedido o si ha cambiado su dirección de envío durante 16 días. 
 Por lo tanto, comprobamos los cambios de pedido en el siguiente intervalo de tiempo:
 <ul> 
 <li> 1.5 horas después de la orden cada 15 minutos,</li> <li> base horaria 
 24 horas después de la orden,</li> 
 <li> hasta 48 horas - cada 2 horas</li> 
 <li> hasta 1 semana - cada 3 horas</li> 
 <li> hasta 16 días después de la orden cada 6 horas.</li> 
 </ul></p>';
MLI18n::gi()->{'hood_config_orderimport__field__orderimport.paymentstatus__help'} = 'Selecciona aquí qué estado de pago de la tienda online debe almacenarse en los detalles del pedido durante la importación de pedidos de magnalister.';
MLI18n::gi()->{'hood_config_orderimport__field__update.paymentstatus__label'} = 'Cambio del estado activo';

MLI18n::gi()->{'hood_config_producttemplate__field__template.content__label'} = 'Plantilla de descripción del producto';
MLI18n::gi()->{'hood_config_orderimport__field__customergroup__help'} = '{#i18n:global_config_orderimport_field_customergroup_help#}';
MLI18n::gi()->{'hood_config_producttemplate__field__template.content__hint'} = 'Lista de marcadores de posición disponibles para la descripción del producto:
<dl>
    <dt>#TITLE#</dt>
        <dd>Nombre del producto (título)</dd>
    <dt>#ARTNR#</dt>
        <dd>Número de artículo en la tienda</dd>
    <dt>#PID#</dt>
        <dd>ID del producto en la tienda</dd>
    <!--<dt>#PRICE#</dt>
            <dd>Precio</dd>
    <dt>#VPE#</dt>
            <dd>Precio por unidad de embalaje</dd>-->
    <dt>#SHORTDESCRIPTION#</dt>
        <dd>Breve descripción de la tienda</dd>
    <dt>#DESCRIPTION#</dt>
        <dd>Descripción de la tienda</dd>
    <dt>#Imagen1#</dt>
        <dd>Primera imagen del producto</dd>
    <dt>#Imagen2# etc.</dt>
            <dd>segunda imagen del producto; con #Imagen3#, #Imagen4# etc. se pueden proporcionar más imágenes, tantas como estén disponibles en la tienda.</dd><br><dt>Campos de texto libre del artículo:</dt><br><dt>#Descripción1# #Campo de texto libre1#</dt><dt>#Descripción2# #Campo de texto libre2#</dt><dt>#Descripción..# #Campo de texto libre..#</dt><br><dd> El número después del marcador de posición (por ejemplo #Campo de texto libre1#) corresponde a la posición del campo de texto libre.
                <br> Ver Ajustes > Ajustes básicos > Artículos > Campos de texto libre del artículo</dd><dt>#PROPERTIES#</dt><dd>Una lista de todas las propiedades del producto. La apariencia se puede controlar mediante CSS (ver código de la plantilla estándar)</dd></dl>';
MLI18n::gi()->{'hood_config_orderimport__field__orderimport.shippingmethod__help'} = '<p>Tipo de envío que se asigna a todos los pedidos de Hood.de durante la importación del pedido.
Por defecto: "Transferencia desde el mercado"</p>.
<p>
Si seleccionas "Adoptar del mercado", magnalister adopta el método de envío seleccionado por el comprador en Hood.de.
Esto también se crea entonces en Shopware > Configuración > Gastos de envío.</p>
<p>
También puedes definir todos los demás métodos de envío disponibles en la lista bajo Shopware > Configuración > Gastos de envío y luego utilizarlos aquí.</p> <p>
<p>
Este ajuste es importante para la impresión de facturas y albaranes, así como para el posterior procesamiento del pedido en la tienda y en los sistemas de gestión de mercancías.';
MLI18n::gi()->{'hood_config_orderimport__field__orderimport.shippingmethod__label'} = 'Método de envío de los pedidos';
MLI18n::gi()->{'hood_config_orderimport__field__orderimport.paymentmethod__help'} = '<p>Método de pago que se asigna a todos los pedidos de Hood.de durante la importación del pedido.
</p>
<p>
También puedes definir todos los métodos de pago en la lista en Shopware > Configuración > Métodos de pago y luego utilizarlos aquí.
</p>
<p>
Este ajuste es importante para la impresión de facturas y albaranes y para el posterior procesamiento del pedido en la tienda y en los sistemas de gestión de mercancías.
</p>';
MLI18n::gi()->{'hood_config_orderimport__field__orderimport.paymentmethod__label'} = 'Forma de pago de los pedidos';
MLI18n::gi()->{'hood_config_orderimport__field__orderstatus.paid__label'} = 'Estado del pedido';
MLI18n::gi()->{'hood_config_producttemplate_content'} = '<style>
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
MLI18n::gi()->{'hood_config_orderimport__field__updateable.paymentstatus__help'} = '';
MLI18n::gi()->{'hood_config_orderimport__field__updateable.paymentstatus__label'} = '';
MLI18n::gi()->{'hood_config_orderimport__field__orderimport.paymentstatus__hint'} = '';
MLI18n::gi()->{'hood_config_orderimport__field__orderimport.paymentmethod__hint'} = '';
MLI18n::gi()->{'hood_config_orderimport__field__orderstatus.paid__help'} = '';
MLI18n::gi()->{'hood_config_orderimport__field__orderimport.shippingmethod__hint'} = '';