<?php


MLI18n::gi()->{'general_shopware6_flow_skipped__valuehint'} = 'Saltar Flow Builder durante la importación de pedidos';
MLI18n::gi()->{'general_shopware6_flow_skipped__label'} = 'Compatibilidad con Shopware 6 Flow Builder';
MLI18n::gi()->{'general_shopware6_flow_skipped__help'} = 'Actualmente soportamos los siguientes eventos:<br
* "El pedido alcanza el estado ..." (state_enter.order.state....)<br>
* "El pedido ha sido recibido" (checkout.order.placed)';
MLI18n::gi()->{'general_shopware6_master_sku_migration_options__help'} = '<p>Este ajuste sólo es relevante para los comerciantes que ya han utilizado Shopware 5 para transmitir artículos variantes a los marketplaces a través de magnalister.
    marketplaces con magnalister:
<ul>
    <li>Si <b>no activas</b> el ajuste, los productos definidos en la gestión de productos de Shopware 6 como
        productos definidos como artículos "maestros" en la gestión de productos de Shopware 6 con todas las variantes asociadas se crean como productos nuevos en los marketplaces.
        creados.
    </li>
    <li><b>Si activas</b> el ajuste, el SKU (Stock Keeping Unit) del artículo "maestro" se ajusta automáticamente por magnalister
        para que el artículo existente en el marketplace se actualice cuando se carga un nuevo producto.
    </li>
</ul></p>
<p>
    <b>Contexto:</b> Shopware 6 diferencia entre artículos "maestros" y variantes a la hora de asignar un SKU. Si utiliza el
    Asistente de migración de Shopware 6 para migrar sus productos de Shopware 5 a 6, al SKU del artículo "maestro" se le asigna un
    una "M" (ejemplo SKU: "1234M"). Las variantes no reciben esta adición.
</p><p>
    La distinción entre "maestro" y variante no existe en Shopware 5. Para algunos marketplaces, la
    identificación de un artículo "maestro" es relevante. Por esta razón, magnalister marca automáticamente el SKU al cargar productos desde
    Shopware 5, magnalister identifica de forma independiente el SKU de la variante principal del artículo con el añadido "_Master" (ejemplo: "1234_Master").
</p><p>
    Si el ajuste "Shopware 5 Master SKU" está activado, magnalister convierte automáticamente el sufijo "M"
    a "_Master" automáticamente durante la carga del producto.
</p>
<p><b>Otras notas:</b>
<ul>
    <li>La sincronización de precios y existencias entre la tienda web y los marketplaces para los artículos que se transfirieron a través de magnalister desde Shopware 5
        funciona en Shopware 6 aunque este ajuste no esté activado.
    </li>
    <li>En la vista general de la preparación de productos, la carga de productos y la pestaña de inventario, puede reconocer los artículos "maestros" por la
        adición después del SKU.
    </li>
</ul></p>
    ';
MLI18n::gi()->{'general_shopware6_flow_skipped__hint'} = 'Más información en el icono de información';
MLI18n::gi()->{'general_shopware6_master_sku_migration_options__label'} = 'Utilizar Shopware 5 Master SKU';