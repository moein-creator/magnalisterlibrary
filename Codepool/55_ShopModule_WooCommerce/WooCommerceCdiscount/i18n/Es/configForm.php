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
        <span class="negrita subrayada">Coincidir empresas de transporte sugeridas por Cdiscount con proveedores de servicios de envío de los métodos de envío de la tienda web</span>.
        <p>Puede hacer coincidir las empresas de transporte recomendadas por Cdiscount con los proveedores de servicios creados en los métodos de envío de WooCommerce. Puede realizar múltiples coincidencias utilizando el símbolo "+".<br>
            <br>
            Para obtener información sobre qué entrada de los métodos de envío de WooCommerce se utiliza para la importación de pedidos de Cdiscount, consulte el icono de información en "Importación de pedidos" -> "Método de envío de pedidos".<br> <br>
            <br>
            Esta opción es útil si desea utilizar <strong>configuraciones de métodos de envío existentes</strong> de los métodos de envío de <strong>WooCommerce</strong>.<br>
        </p>
    </li> <li>
    <li>
        <span class="negrita subrayada">magnalister añade un "Campo personalizado" en los detalles del pedido</span>.
        <p>Si seleccionas esta opción, magnalister añadirá un campo en los detalles del pedido de WooCommerce. En este campo podrás introducir la empresa de transporte.<br>
            <br>
            Esta opción es útil si quieres utilizar <strong>diferentes empresas de transporte</strong> para pedidos Cdiscount.<br>
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