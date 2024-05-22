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
MLI18n::gi()->{'check24_config_orderimport__field__paymentstatus__hint'} = 'Por favor, selecciona qué estado de pago del sistema de la tienda debe establecerse en los detalles del pedido al importar el pedido de magnalister.';
MLI18n::gi()->{'check24_config_orderimport__field__paymentstatus__label'} = 'Estado del pago en la tienda';
MLI18n::gi()->{'check24_config_orderimport__field__orderimport.paymentmethod__label'} = 'Métodos de pago';
MLI18n::gi()->{'check24_config_orderimport__field__orderimport.paymentmethod__help'} = '<p>Método de pago que se aplicará a todos los pedidos importados desde Check24. 
 <p>Los métodos de pago se pueden añadir a la lista a través de Shopware > Configuración > Métodos de pago, y luego activarlos aquí. </p> 
 <p>Esta configuración es necesaria para la factura y el aviso de envío, y para editar los pedidos más tarde en la Tienda o a través del ERP.</p>';
MLI18n::gi()->{'check24_config_orderimport__field__paymentstatus__help'} = '';

MLI18n::gi()->{'check24_config_orderimport__field__customergroup__help'} = '{#i18n:global_config_orderimport_field_customergroup_help#}';
MLI18n::gi()->{'check24_config_orderimport__field__orderimport.shippingmethod__help'} = '                <p>Check24 no proporciona ninguna información sobre el método de envío al importar pedidos.</p>
                <p>Selecciona aquí los métodos de envío disponibles en la tienda web. Puedes definir el contenido desde el menú desplegable en Shopware > Configuración > Gastos de envío.</p>
                <p>Este ajuste es importante para imprimir facturas y albaranes, y para el posterior procesamiento del pedido en la tienda y en los sistemas de gestión de mercancías.</p>';
MLI18n::gi()->{'check24_config_orderimport__field__orderimport.shippingmethod__label'} = 'Método de envío de los pedidos';
MLI18n::gi()->{'check24_config_orderimport__field__orderimport.shippingmethod__hint'} = '';
MLI18n::gi()->{'check24_config_orderimport__field__orderimport.paymentmethod__hint'} = '';