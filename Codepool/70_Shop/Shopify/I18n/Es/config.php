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
MLI18n::gi()->{'FinancialStatus_Voided'} = 'Cancelado';
MLI18n::gi()->{'Vendor'} = 'Proveedor';
MLI18n::gi()->{'OrderStatus_Open'} = 'No se ha realizado';
MLI18n::gi()->{'SKU'} = 'Número de artículo (SKU)';
MLI18n::gi()->{'FinancialStatus_Refunded'} = 'Reembolsado';
MLI18n::gi()->{'ProductType'} = 'Tipo de producto';
MLI18n::gi()->{'FinancialStatus_Pending'} = 'Pendiente';
MLI18n::gi()->{'FinancialStatus_PartiallyRefunded'} = 'Reembolsado parcialmente';
MLI18n::gi()->{'FinancialStatus_PartiallyPaid'} = 'Parcialmente pagado';
MLI18n::gi()->{'FinancialStatus_Paid'} = 'Pagado';
MLI18n::gi()->{'Shopify_Carrier_Other'} = 'Otros';
MLI18n::gi()->{'FinancialStatus_Empty'} = 'Deja que magnalister determine si el pedido este «pagado» o «pendiente».';
MLI18n::gi()->{'OrderStatus_Fulfilled'} = 'Se ha realizado';
MLI18n::gi()->{'OrderStatus_Cancelled'} = 'Cancelado';
MLI18n::gi()->{'Barcode'} = 'Código de barras (ISBN, UPC, GTIN, etc.)';
MLI18n::gi()->{'FinancialStatus_Authorized'} = 'Autorizado';

MLI18n::gi()->{'orderimport_shopifyvatmatching_help'} = '<p>Shopify no permite que aplicaciones de terceros accedan a la configuración de impuestos. Por lo tanto, puedes realizar estos ajustes directamente en magnalister para importar pedidos con el tipo de IVA adecuado.
<p>Asigna una colección de Shopify con el país de destino y el tipo de IVA deseados. Los tipos de IVA se almacenan en los detalles del pedido durante la importación de pedidos magnalister de productos Shopify.</p>
<b>Notas:</b>
<ul>
<li>Si se asignan varias colecciones de Shopify que contienen diferentes tipos de IVA a los productos, al importar el pedido solo se aplicará el tipo impositivo que se haya emparejado en primer lugar.</li>
<li>Si el pedido importado no se puede emparejar con una configuración de impuestos magnalister, se aplicará el tipo de IVA predeterminado proporcionado por la API de Shopify (ejemplo: para pedidos con el país de destino Alemania: 19%)</li>.
<li>Si seleccionas la opción "{#i18n:shopify_global_configuration_vat_matching_option_all#}" en el desplegable "Colección Shopify", podrás asignar un tipo de IVA uniforme a todos los productos, independientemente de la colección asignada por Shopify.</li>
</ul>';
MLI18n::gi()->{'orderimport_shopify_vatmatching_collection_label'} = 'Colección Shopify';
MLI18n::gi()->{'shopify_global_configuration_vat_matching_option_all_countries'} = 'Todos los países';
MLI18n::gi()->{'orderimport_shopify_vatmatching_shipping_country_label'} = 'País de destino del pedido';
MLI18n::gi()->{'form_config_orderimport_exchangerate_update_help'} = '<strong>Básicamente:</strong
<p>
Si la moneda establecida en la tienda web difiere de la del marketplace, magnalister calcula el precio del artículo utilizando una conversión de moneda automática.
</p>
<strong>Atención:</strong>
<p>
Para ello, utilizamos el tipo de cambio emitido por el conversor de divisas externo "alphavantage". Importante: No asumimos ninguna responsabilidad por la conversión de divisas de servicios externos.
</p>
<p>
Las siguientes funciones activan una actualización del tipo de cambio
<ul>
<li>Importación de pedidos</li>
<li>Preparación de artículos</li>
<li>Carga de artículos</li>
<li>Sincronización de existencias/precios</li>
</ul>
</p>';
MLI18n::gi()->{'shopify_global_configuration_vat_matching_option_all'} = 'Todas las colecciones';
MLI18n::gi()->{'orderimport_shopify_vatmatching_label'} = 'Ajuste del impuesto sobre el valor añadido';
MLI18n::gi()->{'CustomerGroupSettingNotSupported'} = 'Esta opción no está soportada por Shopify.';
MLI18n::gi()->{'orderimport_shopifyvatmatching_vatrate_label'} = 'Impuesto sobre el valor añadido en %.';
MLI18n::gi()->{'ItemName'} = 'Título del artículo';
MLI18n::gi()->{'Weight'} = 'Peso';
MLI18n::gi()->{'form_config_orderimport_exchangerate_update_alert'} = '<strong>Atención:</strong>
<p>
Al activar, el tipo de cambio almacenado en la tienda web se actualiza con el tipo de cambio actual de "alphavantage".
<u>Esto también hará que los precios en su tienda web se muestren a la venta con el tipo de cambio actualizado:</u>
</p><p>
Las siguientes funciones activan la actualización
<ul>
<li>Importación de pedidos</li>
<li>Preparación de artículos</li>
<li>Carga de artículos</li>
<li>Sincronización de existencias/precios</li>
</ul>
</p>';
MLI18n::gi()->{'Description'} = 'Descripción del artículo';
MLI18n::gi()->{'formfields_config_uploadInvoiceOption_help_erp'} = '';
MLI18n::gi()->{'formfields_config_uploadInvoiceOption_help_webshop'} = '';