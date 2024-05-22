<?php


MLI18n::gi()->{'Shopware6_Marketplace_Configuration_SalesChannel_Info'} = 'Determina a qué canal de ventas debe asignarse el pedido.';
MLI18n::gi()->{'Shopware_Ebay_Configuration_ArticleDescriptionTemplate_sDefault'} = '<style>
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
<p>#MOBILEDESCRIPTION#</p>
<p>#Descripción1# #Campo adicional1#</p>
<p>#etiqueta2# #campoadicional2#</p>
<div>#PROPERTIES#</div>';
MLI18n::gi()->{'Shopware_EBay_Configuration_ShippingMethod_Info'} = '<p>Tipo de envío que se asigna a todos los pedidos {#platformName#} durante la importación de pedidos.  Por defecto: "Asignación automática"</p>
<p>Si seleccionas "Asignación automática", magnalister adopta el método de envío que el comprador ha seleccionado en {#platformName#}. Esto se crea entonces también en Shopware > Configuración > Gastos de envío.</p>
<p>También puedes definir todos los demás métodos de envío disponibles en la lista bajo Shopware > Configuración > Costes de envío y luego utilizarlos aquí.</p>
<p>Este ajuste es importante para la configuración del método de envío.</p>
<p>Este ajuste es importante para la impresión de facturas y albaranes, así como para el posterior procesamiento del pedido en la tienda y en los sistemas de gestión de mercancías.</p>';
MLI18n::gi()->{'Shopware6_Marketplace_Configuration_SalesChannel_Label'} = 'Canal de ventas';
MLI18n::gi()->{'form_config_orderimport_exchangerate_update_alert'} = '<strong>Atención:</strong>
<p>
Al activar, el tipo de cambio almacenado en la tienda web se actualiza con el tipo de cambio actual de Yahoo Finanzas.
<u>Esto también mostrará los precios en su tienda web con el tipo de cambio actualizado para las ventas:</u>
</p><p>
Las siguientes funciones activan la actualización
<ul>
<li>Importación de pedidos</li>
<li>Preparación de artículos</li>
<li>Subida de artículos</li>
<li>Sincronización de existencias/precios</li>
</ul>
<p>
';
MLI18n::gi()->{'Shopware6_eBay_Marketplace_Configuration_chinesePriceoptions_label'} = 'Precio de la regla de precios';
MLI18n::gi()->{'Shopware6_Configuration_PaymentMethod_Available_Info'} = '<p>Método de pago que se asigna a todos los pedidos de eBay durante la importación de pedidos.
Valor predeterminado: "Asignación automática"</p>
<p>
Si seleccionas "Asignación automática", magnalister utilizará la forma de pago seleccionada por el comprador en eBay.</p>
<p>
Este ajuste es importante para la impresión de facturas y albaranes y para el posterior procesamiento del pedido en la tienda y en los sistemas de gestión de mercancías.</p>';
MLI18n::gi()->{'Shopware_Ebay_Configuration_Updateable_PaymentStatus_Label'} = 'Permitir el estado del número y cambiarlo si';
MLI18n::gi()->{'form_config_orderimport_exchangerate_update_help'} = '<strong>Básicamente:</strong>
<p>
Si la moneda predeterminada de la tienda web difiere de la moneda del mercado, magnalister calcula la importación de pedidos y la carga de artículos utilizando el tipo de moneda almacenado en la tienda web.
Al importar los pedidos, magnalister guarda las divisas y los importes 1:1 de la misma forma que lo hace la tienda web al recibir los pedidos.
</p>

<strong>Atención:</strong>
<p>
Al activar esta función aquí, el tipo de cambio almacenado en la tienda web se actualiza con el servicio de importación que está activado en Magento en "Sistema" > "Gestionar divisas".
<u>Así también se mostrarán los precios en su tienda web a la venta con el tipo de cambio actualizado:</u>
</p>
<p>
Las siguientes funciones activan la actualización
<ul>
<li>Importación de pedidos</li>
<li>Preparación de artículos</li>
<li>Carga de artículos</li>
<li>Sincronización de existencias/precios</li>
</ul>
</p>
<p>
Si el tipo de moneda de un marketplace no se ha creado en la configuración de moneda de la tienda web, magnalister emite un mensaje de error.
</p>';
MLI18n::gi()->{'Shopware_Amazon_Configuration_ShippingMethod_Info'} = '<p>Amazon no proporciona ninguna información sobre el método de envío al importar pedidos.</p>
<p>Selecciona aquí los métodos de envío disponibles en la tienda web. Puedes definir el contenido desde el menú desplegable en Shopware > Configuración > Gastos de envío.</p>
<p>Este ajuste es importante para imprimir facturas y albaranes, y para el posterior procesamiento del pedido en la tienda y en los sistemas de gestión de mercancías.</p>';
MLI18n::gi()->{'Shopware_Ebay_Configuration_Updateable_PaymentStatus_Info'} = 'Estados de pedidos que se pueden cambiar para pagos de eBay.
			                Si el pedido tiene un estado diferente, no se cambiará para los pagos de eBay.<br /><br />
			                Si no deseas cambiar el estado de pago para los pagos de eBay en absoluto, desactiva la casilla de verificación.<br /><br />
			                <b>Nota:</b> El estado de los pedidos combinados sólo se cambiará cuando se hayan pagado todos los artículos.';
MLI18n::gi()->{'Shopware_Marketplace_Configuration_PaymentMethod_Info'} = '<p>Método de pago que se asigna a todos los pedidos {#platformName#} durante la importación de pedidos.
Por defecto: "Asignación automática"</p>
<p>
Si seleccionas "Asignación automática", magnalister adopta el método de pago que el comprador ha seleccionado en {#platformName#}.
Esto también se crea entonces en Shopware > Configuración > Métodos de pago.</p>
<p>
También puedes definir todos los demás métodos de pago disponibles en la lista bajo Shopware > Configuración > Métodos de pago y luego usarlos aquí.</p>
<p>
Este ajuste es importante para imprimir facturas y albaranes y para el posterior procesamiento del pedido en la tienda y en los sistemas de gestión de mercancías.</p>';
MLI18n::gi()->{'Shopware_Ebay_Configuration_Updateable_OrderStatus_Label'} = 'Permitir el estado del pedido y cambiarlo si';
MLI18n::gi()->{'Shopware6_Configuration_PaymentMethod_NotAvailable_Info'} = '                <p>Método de pago que se asigna a todos los pedidos {#setting:currentMarketplaceName#} durante la importación del pedido.</p> <p>
                <p>También puedes definir todos los demás métodos de pago disponibles en la lista de WooCommerce > Configuración > Pagos y luego utilizarlos aquí.</p> <p>Esta configuración es importante para la impresión de facturas y albaranes, así como para la importación de pedidos.
                <p>Este ajuste es importante para la impresión de facturas y albaranes, y para el posterior procesamiento del pedido en la tienda y en los sistemas de gestión de mercancías.';
MLI18n::gi()->{'Shopware6_eBay_Marketplace_Configuration_fixedPriceoptions_label'} = 'Precio de venta de la regla de precios';
MLI18n::gi()->{'Shopware6_eBay_Marketplace_Configuration_fixedPriceoptions_help'} = '<p>Con esta función puedes introducir diferentes precios para {#setting:currentMarketplaceName#} y hacer que se sincronicen automáticamente.</p>
<p>Para ello, selecciona una regla de precios de su tienda online utilizando el desplegable adyacente (véase más abajo).</p>
<p>Si no introduces un precio para la nueva regla de precios, se utilizará automáticamente el precio predeterminado de la tienda online. Esto hace que sea muy fácil introducir un precio diferente para unos pocos artículos. Las demás configuraciones de precios también se aplican.</p>
<p><b>Ejemplo de aplicación:</b></p>
<ul>
<li>Crea una regla para su tienda web en Configuración > Tienda > Creador de reglas, por ejemplo, "{#setting:currentMarketplaceName#}-Clientes"</li>.
<li>Introduce los precios deseados para la regla en "Precios ampliados" en la vista de productos de tu tienda web.</li> <li>Introduce los precios deseados para la regla en "Precios ampliados" en la vista de productos de su tienda web.</li>
</ul></p>';