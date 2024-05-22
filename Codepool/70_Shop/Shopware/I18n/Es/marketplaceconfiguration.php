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
MLI18n::gi()->{'Shopware_Ebay_Configuration_ArticleDescriptionTemplate_sExternalDesc'} = 'Lista de marcadores de posición disponibles para el Contenido: 
 <dl> 
 <dt>#TITLE#</dt> 
 <dd>Nombre del producto (Título)</dd> 
 <dt>#ARTNR#</dt> 
 <dd>Número de artículo en la tienda</dd> 
 <dt>#PID#</dt> 
 <dd>Identificación del producto en la tienda</dd> 
 <!--<dt>#PRICE#</dt> 
 <dd>Precio</dd> 
 <dt>#VPE#</dt> 
 <dd>Precio por unidad de embalaje</dd>--> 
 <dt>#SHORTDESCRIPTION#</dt> <dd>Descripción breve de la 
 Tienda</dd> 
 <dt>#DESCRIPTION#</dt> 
 <dd>Descripción de la tienda</dd> 
 <dt>#MOBILEDESCRIPTION#</dt> 
 <dd>Descripción breve para dispositivos móviles (si está definida)</dd> 
 <dt>#PICTURE1#</dt> 
 <dd>Primera imagen del producto</dd> 
 <dt>#PICTURE2# etc.</dt> 
 <dd>Segunda imagen del producto; con #PICTURE3#, #PICTURE4# etc, puedes transferir tantas imágenes como tengas disponibles en tu tienda. </dd></dl>';
MLI18n::gi()->{'orderimport_vatcustomergroup_label'} = 'Iniciar la importación desde';
MLI18n::gi()->{'orderimport_vatcustomergroup_help'} = '<p>
 Por defecto, magnalister utiliza para el cálculo de los impuestos de la importación de pedidos, las reglas de impuestos de la tienda virtual para el grupo de clientes que usted seleccionó en la configuración de "Grupo de clientes".
 </p>
 <p>
 Con la opción "Aplicar las reglas de impuestos de la tienda virtual para los pedidos del grupo de clientes" puede elegir un grupo de clientes diferente para el cálculo de impuestos.
 </p>
 <p>
 Las reglas de impuestos de la tienda web se pueden encontrar en su tienda en:<br>
 Configuración básica -> Configuración de la tienda -> Impuestos</p>';

MLI18n::gi()->{'Shopware_EBay_Configuration_ShippingMethod_Info'} = '<p>Tipo de envío que se asigna a todos los pedidos {#platformName#} durante la importación de pedidos.  Por defecto: "Asignación automática"</p>.
<p>Si seleccionas "Asignación automática", magnalister adopta el método de envío que el comprador ha seleccionado en {#platformName#}. Esto se crea entonces también en Shopware > Configuración > Gastos de envío.</p>
<p>También puedes definir todos los demás métodos de envío disponibles en la lista bajo Shopware > Configuración > Costes de envío y luego utilizarlos aquí.</p>
<p>Este ajuste es importante para la configuración del método de envío.</p>
<p>Este ajuste es importante para la impresión de facturas y albaranes, así como para el posterior procesamiento del pedido en la tienda y en los sistemas de gestión de mercancías.</p>';
MLI18n::gi()->{'Shopware_Ebay_Configuration_Updateable_PaymentStatus_Info'} = 'Estado de los pedidos que se pueden cambiar para los pagos de eBay.
			                Si el pedido tiene un estado diferente, no se cambiará para los pagos de eBay.<br /><br />
			                Si no deseas cambiar el estado del pago para los pagos de eBay en absoluto, desactiva la casilla de verificación.<br /><br />
			                <b>Nota:</b> El estado de los pedidos combinados sólo se cambiará cuando se hayan pagado todos los artículos.';
MLI18n::gi()->{'form_config_orderimport_exchangerate_update_help'} = '<strong>Básicamente:</strong
<p>Si la moneda predeterminada de la tienda web difiere de la moneda del mercado, magnalister calcula la importación de pedidos y la carga de artículos utilizando el tipo de moneda almacenado en la tienda web.
Al importar los pedidos, magnalister guarda las divisas y los importes 1:1 de la misma forma que lo hace la tienda web al recibir los pedidos.</p>
<strong>Atención:</strong>
<p>Al activar esta función aquí, el tipo de cambio almacenado en la tienda web se actualiza con el tipo de cambio actual de "alphavantage".
<u>Esto también mostrará los precios en su tienda web para la venta con el tipo de cambio actualizado:</u></p>
<p>Las siguientes funciones activan la actualización
<ul>
<li>Importación de pedidos</li>
<li>Preparación de artículos</li>
<li>Carga de artículos</li>
<li>Sincronización de existencias/precios</li>
</ul>
</p>
<p>Si el tipo de moneda de un marketplace no se ha creado en la configuración de moneda de la tienda web, magnalister emite un mensaje de error.</p>';
MLI18n::gi()->{'Shopware_Amazon_Configuration_PaymentStatus_sLabel'} = 'Estado del pago en la tienda';
MLI18n::gi()->{'Shopware_Amazon_Configuration_PaymentStatus_sDescription'} = 'El estado de pago en la tienda web que un nuevo pedido recibido de Amazon debe recibir automáticamente en la tienda.';
MLI18n::gi()->{'Shopware_Ebay_Configuration_PaidStatus_Order_sLabel'} = 'Estado del pedido';
MLI18n::gi()->{'Shopware_Ebay_Configuration_Updateable_PaymentStatus_Label'} = 'Permitir el estado del número y cambiarlo si';
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
MLI18n::gi()->{'Shopware_Amazon_Configuration_ShippingMethod_Info'} = '<p>Amazon no proporciona ninguna información sobre el método de envío al importar pedidos.</p>
<p>Selecciona aquí los métodos de envío disponibles en la tienda web. Puedes definir el contenido desde el dropdown en Shopware > Configuración > Gastos de envío.</p>
<p>Este ajuste es importante para la impresión de facturas y albaranes, y para el posterior procesamiento del pedido en la tienda y en los sistemas de gestión de mercancías.</p>';
MLI18n::gi()->{'Shopware_Ebay_Configuration_PaidStatus_Payment_sLabel'} = 'Estado de los pagos';
MLI18n::gi()->{'global_config_price_field_price.discountmode_label'} = 'Modo de descuento';
MLI18n::gi()->{'Shopware_Ebay_Configuration_ArticleDescriptionTemplate_sDefault'} = '<style>
ul.magna_properties_list {
    margin: 0 0 20px 0;
    list-style: none;
    padding: 0;
    display: inline-block;
    anchura: 100%
}
ul.magna_properties_list li {
    border-bottom: none;
    anchura: 100%;
    altura: 20px;
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
    altura de línea: 20px;
    text-align: left;
    font-size: 12px;
    anchura: 50%;
}
ul.magna_properties_list li span.magna_property_value {
    color: #666;
    altura de línea: 20px;
    text-align: left;
    font-size: 12px;

    anchura: 50%;
}
</style>
<p>#TITULO#</p>
<p>#ARTNR#</p>
<p>#SHORTDESCRIPTION#</p>
<p>#Imagen1#</p>
<p>#Imagen2#</p>
<p>#Imagen3#</p>
<p>#DESCRIPCIÓN#</p>
<p>#DescripciónMóvil#</p>
<p>#Description1# #Freitextfield1#</p>
<p>#Description2# #Campo de texto libre2#</p>
<div>#PROPIEDADES#</div>';
MLI18n::gi()->{'form_config_orderimport_exchangerate_update_alert'} = '<strong>Atención:</strong>
<p>
Al activar, el tipo de cambio almacenado en la tienda web se actualiza con el tipo de cambio actual de "alphavantage".
<u>Esto también hará que los precios en su tienda web se muestren a la venta con el tipo de cambio actualizado:</u
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