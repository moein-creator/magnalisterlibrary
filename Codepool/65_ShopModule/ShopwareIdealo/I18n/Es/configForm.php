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
MLI18n::gi()->{'formfields__orderimport.shippingmethod__label'} = 'Servicio de envío de los pedidos';
MLI18n::gi()->{'formfields__orderimport.paymentstatus__help'} = 'Por favor, selecciona qué estado de pago del sistema de la tienda debe establecerse en los detalles del pedido al importar el pedido de magnalister.';
MLI18n::gi()->{'formfields__orderimport.paymentstatus__label'} = 'Estado del pago en la tienda';
MLI18n::gi()->{'formfields__orderimport.paymentmethod__label'} = 'Métodos de pago';
MLI18n::gi()->{'formfields__orderimport.paymentmethod__help'} = '<p>Método de pago que se aplicará a todos los pedidos importados desde idealo. 
 <p>Los métodos de pago se pueden añadir a la lista a través de Shopware > Configuración > Métodos de pago, y luego activarlos aquí. </p> 
 <p>Esta configuración es necesaria para la factura y el aviso de envío, y para editar los pedidos más tarde en la Tienda o a través del ERP.</p>';
MLI18n::gi()->{'formfields__orderimport.shippingmethod__help'} = '<p>Idealo no pasa la información del servicio de envío con la importación del pedido</p> 
 <p> Por este motivo, elige la información sobre el tipo de envío disponible en la tienda web. Puedes encontrarlos en el menú desplegable de Shopware-Admin> Envío > transportistas.</p> 
 <p> Estos ajustes son importantes para la impresión de la factura y los documentos de envío, así como para la posterior adaptación de la factura en la tienda y para tu sistema ERP.</p>';
MLI18n::gi()->{'idealo_config_orderimport__field__customergroup__help'} = '{#i18n:global_config_orderimport_field_customergroup_help#}';

MLI18n::gi()->{'idealo_config_carrier_option_group_shopfreetextfield_option_carrier'} = 'Seleccionar empresa de transporte desde un campo de texto libre de la tienda web (pedidos)';
MLI18n::gi()->{'formfields__orderimport.paymentstatus__hint'} = '';
MLI18n::gi()->{'formfields__orderimport.paymentmethod__hint'} = '';
MLI18n::gi()->{'formfields__orderimport.shippingmethod__hint'} = '';