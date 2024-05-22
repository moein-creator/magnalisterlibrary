<?php


MLI18n::gi()->{'cdiscount_config_orderimport__field__orderstatus.carrier__help'} = 'Selecciona aquí la empresa de transporte que se asigna por defecto a los pedidos de Cdiscount.<br>
<br>
Dispones de las siguientes opciones:<br>
<ul>
    <li>
        <span class="bold underline">Empresa de transporte sugerida por Cdiscount</span>.
        <p>Selecciona una empresa de transporte de la lista desplegable. Se mostrarán las empresas recomendadas por Cdiscount.<br>
            <br>
            Esta opción es útil si <strong>deseas utilizar siempre la misma empresa de transporte</strong> para los pedidos de Cdiscount.
        </p>
    </li>
    <li>
        <span class="bold underline">Coincidir empresas de transporte sugeridas por Cdiscount con proveedores de servicios de envío del módulo de envío de la tienda web</span>.
        <p>Puedes hacer coincidir las empresas de transporte recomendadas por Cdiscount con los proveedores de servicios creados en el módulo de gastos de envío de Shopify. Puedes realizar múltiples coincidencias utilizando el símbolo "+".<br>
            <br>
            Para obtener información sobre qué entrada del módulo de gastos de envío de Shopify se utiliza para la importación de pedidos de Cdiscount, consulta el icono de información en "Importación de pedidos" -> "Forma de envío de los pedidos".<br> <br>
            <br>
            Esta opción es útil si deseas utilizar <strong>configuraciones de método de envío existentes</strong> del módulo de gastos de envío de <strong>Shopify</strong>.<br>
        </p>
    </li>
    <li>
        <span class="bold underline">magnalister añade un campo de texto libre bajo "Más detalles" en los pedidos</span>.
        <p>Si seleccionas esta opción, magnalister añadirá un campo bajo "Más detalles" en el pedido de Shopify. En este campo puedes introducir la empresa de transporte.<br>
            <br>
            Esta opción es útil si deseas utilizar <strong>diferentes empresas de transporte</strong> para pedidos Cdiscount.<br>
        </p>
    </li>
    <li>
        <span class="bold underline">Introduce manualmente una empresa de transporte para todos los pedidos en un campo de texto magnalister</span><br>
        <p>Esta opción es útil si <strong>deseas introducir manualmente la misma empresa de transporte para todos los pedidos de Cdiscount</strong>.<br></p>
    </li>
</ul>
<span class="bold underline">Notas importantes:</span>
<ul>
    <li>La especificación de una empresa de transporte es obligatoria para las confirmaciones de envío en Cdiscount.<br><br></li>
    <li>El hecho de no proporcionar la empresa de transporte puede suponer la retirada temporal de la autorización de venta.</li> 
    <li>Por favor, ten en cuenta que la empresa de transporte no es obligatoria.</li> 
</ul>
';
MLI18n::gi()->{'cdiscount_config_orderimport__field__customergroup__help'} = '{#i18n:global_config_orderimport_field_customergroup_help#}';
MLI18n::gi()->{'cdiscount_config_orderimport__field__orderimport.paymentstatus__help'} = 'Seleccione aquí qué estado de pago de la tienda online debe almacenarse en los detalles del pedido durante la importación de pedidos de magnalister.';
MLI18n::gi()->{'cdiscount_config_orderimport__field__orderimport.paymentstatus__label'} = 'Estado del pago en la tienda';
MLI18n::gi()->{'cdiscount_config_orderimport__field__orderimport.paymentstatus__hint'} = '';