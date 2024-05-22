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
MLI18n::gi()->{'etsy_config_orderimport__field__customergroup__help'} = '{#i18n:global_config_orderimport_field_customergroup_help#}';

MLI18n::gi()->{'formfields__orderimport.paymentstatus__help'} = 'Selecciona aquí qué estado de pago de la tienda online debe almacenarse en los detalles del pedido durante la importación de pedidos de magnalister.';
MLI18n::gi()->{'formfields__orderimport.paymentmethod__help'} = '<p>Método de pago que se asigna a todos los pedidos de Etsy durante la importación del pedido.
Valor predeterminado: "Etsy"</p>
<p>También puedes definir todos los demás métodos de pago disponibles en la lista en Shopware > Configuración > Métodos de pago y luego utilizarlos aquí.</p>
<p>Este ajuste es importante para imprimir facturas y albaranes y para el posterior procesamiento del pedido en la tienda y en los sistemas de gestión de mercancías.</p>';
MLI18n::gi()->{'formfields__orderimport.shippingmethod__help'} = '<p>Etsy no proporciona ninguna información sobre el método de envío al importar un pedido.</p>
<p>Selecciona aquí los métodos de envío disponibles en la tienda web. Puedes definir el contenido desde el menú desplegable en Shopware > Configuración > Gastos de envío.</p>
<p>Este ajuste es importante para la impresión de facturas y albaranes, y para el posterior procesamiento del pedido en la tienda y en los sistemas de gestión de mercancías.</p>';
MLI18n::gi()->{'formfields__orderimport.paymentstatus__label'} = 'Estado del pago en la tienda';
MLI18n::gi()->{'formfields__orderimport.shippingmethod__label'} = 'Método de envío de los pedidos';
MLI18n::gi()->{'formfields__orderimport.paymentmethod__label'} = 'Forma de pago de los pedidos';
MLI18n::gi()->{'formfields__orderimport.paymentmethod__hint'} = '';
MLI18n::gi()->{'formfields__orderimport.shippingmethod__hint'} = '';
MLI18n::gi()->{'formfields__orderimport.paymentstatus__hint'} = '';