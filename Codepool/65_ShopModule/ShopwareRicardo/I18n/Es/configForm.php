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
MLI18n::gi()->{'ricardo_config_orderimport__field__orderimport.paymentstatus__help'} = 'Por favor, selecciona qué estado de pago del sistema de la tienda debe establecerse en los detalles del pedido al importar el pedido de magnalister.';
MLI18n::gi()->{'ricardo_config_orderimport__field__orderimport.paymentstatus__label'} = 'Estado del pago en la tienda';
MLI18n::gi()->{'ricardo_config_orderimport__field__orderimport.paymentmethod__label'} = 'Métodos de pago';
MLI18n::gi()->{'ricardo_config_orderimport__field__orderimport.paymentmethod__help'} = '<p>Método de pago que se aplicará a todos los pedidos importados desde Ricardo. 
 <p>Los métodos de pago se pueden añadir a la lista a través de Shopware > Configuración > Métodos de pago, y luego activarlos aquí. </p> 
 <p>Esta configuración es necesaria para la factura y el aviso de envío, y para editar los pedidos más tarde en la Tienda o a través del ERP.</p>';

MLI18n::gi()->{'ricardo_config_orderimport__field__orderimport.shippingmethod__label'} = 'Método de envío de los pedidos';
MLI18n::gi()->{'ricardo_config_orderimport__field__customergroup__help'} = '{#i18n:global_config_orderimport_field_customergroup_help#}';
MLI18n::gi()->{'ricardo_config_orderimport__field__orderimport.shippingmethod__help'} = '<p>Tipo de expedición que se asigna a todos los pedidos de Ricardo durante la importación de pedidos. </p>
<p>También puedes definir todos los métodos de envío en la lista en Shopware > Ajustes > Gastos de envío y luego utilizarlos aquí.</p> <p>También puedes definir todos los métodos de envío en la lista en Shopware > Ajustes > Gastos de envío y luego utilizarlos aquí.</p>
<p>Este ajuste es importante para imprimir facturas y albaranes, y para el posterior procesamiento del pedido en la tienda y en los sistemas de gestión de mercancías.</p>';
MLI18n::gi()->{'ricardo_config_producttemplate__field__template.content__label'} = 'Plantilla de descripción del producto';
MLI18n::gi()->{'ricardo_config_producttemplate__field__template.content__hint'} = '
                Lista de marcadores de posición disponibles para la descripción del producto:
                <dl>
                    <dt>#TITLE#</dt>
                        <dd>Nombre del producto (título)</dd>
                    <dt>#VARIATIONDETAILS#</dt>
                            <dd>Dado que ricardo.ch no soporta variantes, magnalister transmite las variantes como artículos individuales a ricardo.ch.
                            Utilice este marcador de posición para mostrar los detalles de la variante en la descripción de su artículo</dd>
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
                        <dd>Descripción de la tienda</dd>
                    <dt>#Imagen1#</dt>
                        <dd>Primera imagen del producto</dd>
                    <dt>#Imagen2# etc.</dt>
                        <dd>segunda imagen del producto; con #Imagen3#, #Imagen4# etc. se pueden transmitir más imágenes, tantas como haya disponibles en la tienda.</dd>
                    <dt>Campos de texto libre del artículo:</dt>
                    <dt>#Descripción1#&nbsp;#Campo de texto libre1#</dt>
                    <dt>#Nombre2#&nbsp;#Campo de texto libre2#</dt>
                    <dt>#descripción..#&nbsp;#campo de texto libre..#</dt>
                        <dd>&uml;bernahme der Artikel-Freitextfelder:&nbsp;
                        El número después del marcador de posición (por ejemplo, #Freitextfeld1#) corresponde a la posición del campo de texto libre.
                        Véase Ajustes > Ajustes básicos > Artículos > Campos de texto libre del artículo</dd>.
                    <dt>#PROPIEDADES#</dt>
                        <dd>Una lista de todas las propiedades del producto. La apariencia se puede controlar mediante CSS (ver código de la plantilla estándar)</dd>
                </dl>';
MLI18n::gi()->{'ricardo_config_orderimport__field__orderimport.paymentmethod__hint'} = '';
MLI18n::gi()->{'ricardo_config_orderimport__field__orderimport.shippingmethod__hint'} = '';
MLI18n::gi()->{'ricardo_config_orderimport__field__orderimport.paymentstatus__hint'} = '';