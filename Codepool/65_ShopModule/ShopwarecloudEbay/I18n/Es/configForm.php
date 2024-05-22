<?php


MLI18n::gi()->{'ebay_config_orderimport__field__updateablepaymentstatus__label'} = 'Permitir el estado del número y cambiarlo si';
MLI18n::gi()->{'ebay_config_orderimport__field__paidstatus__label'} = 'Estado del pedido/pago para pedidos pagados de eBay';
MLI18n::gi()->{'ebay_config_orderimport__field__orderimport.paymentmethod__help'} = '<p>Método de pago que se asigna a todos los pedidos de eBay durante la importación de pedidos.
<p>
También puedes definir todas las formas de pago en la lista en Shopware > Configuración > Formas de pago y luego utilizarlas aquí.
</p>
<p>
Este ajuste es importante para imprimir facturas y albaranes y para el posterior procesamiento del pedido en la tienda y en los sistemas de gestión de mercancías.
</p>';
MLI18n::gi()->{'ebay_config_orderimport__field__orderimport.shippingmethod__label'} = 'Método de envío de los pedidos';
MLI18n::gi()->{'ebay_config_orderimport__field__paidstatus__help'} = '<p>Los pedidos de eBay a veces los paga el comprador con un tiempo de retraso.
<br><br>
Para que puedas separar los pedidos no pagados de los pedidos pagados, puedes seleccionar un estado de pedido de tienda online y un estado de pago independientes para los pedidos pagados de eBay.
<br><br>
Si se importan pedidos de eBay que aún no se han pagado, se aplica el estado de pedido que ha definido anteriormente en "Importación de pedidos" > "Estado del pedido en la tienda"."
<br><br>
Si arriba has activado "Importar sólo pedidos pagados", también se utilizará el "Estado del pedido en la tienda" de arriba. En este caso, la función aquí aparece atenuada.';
MLI18n::gi()->{'ebay_config_orderimport__field__customergroup__help'} = '{#i18n:global_config_orderimport_field_customergroup_help#}';
MLI18n::gi()->{'ebay_config_orderimport__field__orderimport.paymentmethod__label'} = 'Forma de pago de los pedidos';
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
MLI18n::gi()->{'ebay_config_orderimport__field__updateablepaymentstatus__help'} = 'Estados de pedidos que se pueden cambiar para pagos de eBay.
			                Si el pedido tiene un estado diferente, no se cambiará para los pagos de eBay.<br /><br />
			                Si no desea que el estado del pago se cambie en absoluto para los pagos de eBay, desactive la casilla de verificación.';
MLI18n::gi()->{'ebay_config_orderimport__field__orderimport.shippingmethod__help'} = '<p>Tipo de envío que se asigna a todos los pedidos de eBay durante la importación de pedidos.
Valor predeterminado: "Asignación automática"</p> <p>Tipo de envío que se asigna a todos los pedidos de eBay durante la importación de pedidos.
<p>
Si selecciona "Asignación automática", magnalister utilizará el método de envío seleccionado por el comprador en eBay.
A continuación, esto también se crea en Shopware > Configuración > Gastos de envío.</p> <p>
<p>
También puede definir todos los demás métodos de envío disponibles en la lista bajo Shopware > Configuración > Gastos de envío y luego usarlos aquí.</p> <p>
<p>
Este ajuste es importante para la impresión de facturas y albaranes, así como para el posterior procesamiento del pedido en la tienda y en los sistemas de gestión de mercancías.';
MLI18n::gi()->{'ebay_config_orderimport__field__orderstatus.paid__label'} = 'Estado del pedido';
MLI18n::gi()->{'ebay_config_orderimport__field__orderstatus.open__help'} = '
                Aquí puede definir el estado del pedido en la tienda Web que debe recibir automáticamente un nuevo pedido recibido de eBay.
<br><br>
Tenga en cuenta que aquí se importan tanto los pedidos de eBay pagados como los no pagados.
<br><br>
No obstante, puede utilizar la siguiente función "Importar sólo pedidos pagados" para especificar que sólo se importen a su tienda online los pedidos de eBay pagados.
<br><br><br>

Puede definir un estado de pedido independiente para los pedidos pagados de eBay más abajo, en "Sincronización de estado de pedido" > "Estado de pedido/pago para pedidos pagados de eBay".
            ';
MLI18n::gi()->{'ebay_config_orderimport__field__update.paymentstatus__label'} = 'Estado y cambio activo';
MLI18n::gi()->{'ebay_config_orderimport__field__paymentstatus.paid__label'} = 'Estado de los pagos';
MLI18n::gi()->{'ebay_config_producttemplate__field__template.content__hint'} = '
Lista de marcadores de posición disponibles para la descripción del producto:
<dl>
    <dt>#TITLE#</dt>
        <dd>Nombre del producto (título)</dd>
    <dt>#ARTNR#</dt>
        <dd>Número de artículo en la tienda</dd>
    <dt>#PID#</dt>
        <dd>ID del producto en la tienda</dd>
    <!--<dt>#PRECIO#</dt>
            <dd>Precio</dd>
    <dt>#VPE#</dt>
            <dd>Precio por unidad de embalaje</dd>-->
    <dt>#SHORTDESCRIPTION#</dt>
        <dd>Breve descripción de la tienda</dd>
    <dt>#DESCRIPCIÓN#</dt>
        <dd>Descripción de la tienda</dd><br>
    <dt>#ESCRIPCIÓNMOVIL#</dt>
        <dd>Descripción breve para dispositivos móviles, si se almacena</dd><br>
    <dt>#IMÁGEN1#</dt>
        <dd>primera imagen del producto</dd><br>
    <dt>#Imagen2# etc.</dt>
            <dd>segunda imagen del producto; con #Imagen3#, #Imagen4# etc. se pueden proporcionar más imágenes, tantas como estén disponibles en la tienda.</dd><br><dt>Campos adicionales del artículo:</dt><br><dt>#Designación1#&nbsp;#Campo adicional1#</dt><dt>#Designación2#&nbsp;#Campo adicional2#</dt><dt>#Designación..#&nbsp;#campoadicional..#</dt><br><dd>&nbspEl número que aparece tras el marcador de posición (por ejemplo, #campoadicional1#) corresponde a la posición del campo adicional.
                <br> Ver Ajustes > Ajustes básicos > Artículos > Campos adicionales del artículo</dd><br><dt>#PROPERTIES#</dt><dd>Una lista de todas las propiedades del producto. La apariencia se puede controlar mediante CSS (ver código de la plantilla estándar)</dd></dl>';
MLI18n::gi()->{'ebay_config_producttemplate__field__template.content__label'} = 'Plantilla de descripción del producto';
MLI18n::gi()->{'ebay_config_orderimport__field__orderimport.paymentstatus__label'} = 'Estado del pago en la tienda';
MLI18n::gi()->{'ebay_config_orderimport__field__updateable.paymentstatus__help'} = '';
MLI18n::gi()->{'ebay_config_orderimport__field__orderimport.paymentstatus__hint'} = '';
MLI18n::gi()->{'ebay_config_orderimport__field__orderimport.shippingmethod__hint'} = '';
MLI18n::gi()->{'ebay_config_orderimport__field__updateable.paymentstatus__label'} = '';
MLI18n::gi()->{'ebay_config_orderimport__field__orderstatus.paid__help'} = '';
MLI18n::gi()->{'ebay_config_orderimport__field__orderimport.paymentmethod__hint'} = '';
MLI18n::gi()->{'ebay_config_orderimport__field__paymentstatus.paid__help'} = '';