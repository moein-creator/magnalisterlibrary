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
MLI18n::gi()->{'ayn24_config_orderimport__field__customergroup__help'} = '';
MLI18n::gi()->{'formfields__orderimport.paymentmethod__label'} = 'Forma de pago del pedido';
MLI18n::gi()->{'formfields__orderimport.paymentmethod__help'} = '<p>Tipo de pago asignado a todos los pedidos de Metro durante la importación del pedido. 
Por defecto: Metro"</p>
<p>
También puedes definir todos los demás métodos de pago disponibles en la lista de Shopware > Configuración > Métodos de pago y utilizarlos aquí.<p>
Esta configuración es importante para la impresión de facturas y albaranes, y para el posterior tratamiento del pedido en la tienda y en los sistemas de gestión de mercancías.</p>&apos;,
        &apos;hint&apos; => &apos;&apos;,
    ),
    &apos;orderimport.shippingmethod&apos; => array(
        &apos;label&apos; => &apos;Forma de envío de los pedidos&apos;,
        &apos;help&apos; => &apos;<p>Metro no facilita información sobre el método de envío al importar pedidos.</p>
<p>Por lo tanto, seleccione aquí los métodos de envío disponibles en la tienda web. Puedes definir el contenido desde el menú desplegable en Shopware > Configuración > Gastos de envío.</p>
<p>Esta configuración es importante para la impresión de facturas y albaranes, y para el posterior tratamiento del pedido en la tienda y en los sistemas de gestión de mercancías.</p>';
MLI18n::gi()->{'formfields__orderimport.shippingmethod__label'} = 'Forma de envío de los pedidos';
MLI18n::gi()->{'formfields__orderimport.shippingmethod__help'} = '<p>Metro no proporciona información sobre el método de envío al importar pedidos.</p>
<p>Por lo tanto, seleccione aquí los métodos de envío disponibles en la tienda web. Puedes definir el contenido desde el menú desplegable en Shopware > Configuración > Gastos de envío.</p>
<p>Esta configuración es importante para la impresión de facturas y albaranes, y para el posterior tratamiento del pedido en la tienda y en los sistemas de gestión de mercancías.</p>';
MLI18n::gi()->{'formfields__orderimport.paymentstatus__label'} = 'Estado del pago en la tienda';
MLI18n::gi()->{'formfields__orderimport.paymentstatus__help'} = 'Seleccione aquí qué estado de pago de la tienda online debe almacenarse en los detalles del pedido durante la importación de pedidos de magnalister.';

MLI18n::gi()->{'metro_config_orderimport__field__customergroup__help'} = '{#i18n:global_config_orderimport_field_customergroup_help#}';
MLI18n::gi()->{'formfields__orderimport.paymentstatus__hint'} = '';
MLI18n::gi()->{'formfields__orderimport.shippingmethod__hint'} = '';
MLI18n::gi()->{'formfields__orderimport.paymentmethod__hint'} = '';