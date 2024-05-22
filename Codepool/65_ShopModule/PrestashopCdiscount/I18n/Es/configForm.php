<?php


MLI18n::gi()->{'cdiscount_config_orderimport__field__orderstatus.carrier__help'} = '
Seleccione aquí la empresa de transporte que se asigna por defecto a los pedidos de Cdiscount.<br>
<br>
Dispone de las siguientes opciones:<br>
<ul>
    <li>
        <span class="negrita subrayada">Empresa de transporte sugerida por Cdiscount</span>.
        <p>Seleccione una empresa de transporte de la lista desplegable. Aparecerán las empresas recomendadas por Cdiscount.<br>
            <br>
            Esta opción es útil si <strong>desea utilizar siempre la misma empresa de transporte</strong> para los pedidos de Cdiscount.
        </p>
    </li> <li
    <li>
        <span class="negrita subrayada">Coincidir empresas de transporte sugeridas por Cdiscount con proveedores de servicios de envío del módulo de envío de la tienda web</span>.
        <p>Puede hacer coincidir las empresas de transporte recomendadas por Cdiscount con los proveedores de servicios creados en el módulo de gastos de envío de PrestaShop. Puede realizar múltiples coincidencias utilizando el símbolo "+".<br>
            <br>
            Para obtener información sobre qué entrada del módulo de gastos de envío de PrestaShop se utiliza para la importación de pedidos de Cdiscount, consulte el icono de información en "Importación de pedidos" -> "Método de envío de pedidos".<br> <br>
            <br>
            Esta opción es útil si desea utilizar <strong>configuraciones de método de envío existentes</strong> del módulo de gastos de envío de <strong>PrestaShop</strong>.<br>
        </p>
    </li> <li>
    <li>
        <span class="negrita subrayada">magnalister añade un campo de texto libre en los detalles del pedido</span>.
        <p>Si selecciona esta opción, magnalister añadirá un campo en los detalles del pedido de PrestaShop. En este campo podrá introducir la empresa de transporte.<br>
            <br>
            Esta opción es útil si desea utilizar <strong>diferentes empresas de transporte</strong> para los pedidos Cdiscount.<br>
        </p>
    </li> <li>
    <li>
        <span class="negrita subrayada">Introduzca manualmente una empresa de transporte para todos los pedidos en un campo de texto magnalister</span><br>
        <p>Esta opción es útil si <strong>desea introducir manualmente la misma empresa de transporte para todos los pedidos de Cdiscount</strong>.<br></p>
    </li> <li>
</ul>
<span class="negrita subrayada">Notas importantes:</span>
<ul>
    <li>La especificación de una empresa de transporte es obligatoria para las confirmaciones de envío en Cdiscount.<br><br></li>.
    <li>El hecho de no facilitar la empresa de transporte puede suponer la retirada temporal de la autorización de venta.</li> <li>La empresa de transporte es obligatoria para la confirmación de los envíos en Cdiscount.
</ul>
';
MLI18n::gi()->{'cdiscount_config_orderimport__field__orderimport.shippingmethod__label'} = 'Método de envío de los pedidos';
MLI18n::gi()->{'cdiscount_config_orderimport__field__orderimport.shippingmethod__help'} = '<p>Cdiscount no proporciona ninguna información sobre el método de envío al importar pedidos.</p>
<p>Selecciona aquí los métodos de envío disponibles en la tienda web. Puedes definir el contenido desde el desplegable en Prestashop Admin > Envío > Servicios de envío.</p>
<p>Este ajuste es importante para la impresión de facturas y albaranes, así como para el posterior procesamiento del pedido en la tienda y en los sistemas de gestión de mercancías.</p> <p>Por favor, selecciona aquí los métodos de envío disponibles al importar un pedido.</p>';
MLI18n::gi()->{'cdiscount_config_orderimport__field__orderimport.shippingmethod__hint'} = '';