<?php


MLI18n::gi()->{'amazon_prepare_apply_form__field__keywords__help'} = '<h3>Optimiza el ranking de productos con las palabras clave de Amazon</h3>
<br>
Las palabras clave generales se utilizan para optimizar los rankings y mejorar la filtrabilidad en Amazon. Se almacenan de forma invisible en el producto durante la carga del producto magnalister.
<br><br>
<h2>Opciones para la transferencia de palabras clave generales</h2>
1. utilizar siempre las palabras clave actuales de la tienda web (palabras clave del producto):
<br><br>
Las palabras clave se toman del campo de palabras clave del producto respectivo en la tienda web y se transfieren a Amazon.
<br><br>
2. introducir manualmente palabras clave generales en magnalister:
<br><br>
Si no deseas utilizar las palabras clave de producto almacenadas en el producto de la tienda web, puedes introducir tus propias palabras clave en este campo de texto libre.
<br><br>
<b>Notas importantes:</b>
<br><ul>
<li>Si introduces las palabras clave manualmente, sepáralas con un espacio (¡no con una coma!) y asegúrese de introducir un total de 250 bytes (regla general: 1 carácter = 1 byte. Excepción: caracteres especiales como Ä, Ö, Ü = 2 bytes).
</li><li>
Si las palabras clave en el campo de palabras clave del producto de la tienda web están separadas por comas, magnalister convierte automáticamente las comas en espacios al cargar el producto. Aquí también se aplica el límite de 250 bytes.
</li><li>
Si se excede el número de bytes permitido, Amazon puede devolver un mensaje de error después de la carga del producto, que puede ver en el registro de errores de magnalister (tiempo de espera de hasta 60 minutos).
</li><li>
Transferencia de palabras clave Platinum: Si eres un comerciante Platinum de Amazon, por favor informa al soporte de magnalister. A continuación, activaremos la transferencia de palabras clave Platinum. Al hacerlo, magnalister utiliza las palabras clave generales y las transfiere 1:1 a Amazon. Por lo tanto, las palabras clave generales y las palabras clave platino son idénticas.
</li><li>
Envía diferentes palabras clave platino: Utiliza la concordancia de atributos de magnalister en la preparación del producto. Para ello, selecciona "Palabras clave platino 1-5" en la lista de atributos disponibles de Amazon y haz coincidir el atributo correspondiente de la tienda web.
</li><li>
Además de las palabras clave generales, existen otras palabras clave relevantes para Amazon (por ejemplo, palabras clave de atributo de tesauro, palabras clave de grupo objetivo o palabras clave temáticas) que también puedes transferir a Amazon mediante la concordancia de atributos.
</li></ul>
';
MLI18n::gi()->{'amazon_prepare_apply_form__field__bulletpoints__hint'} = 'Características clave del artículo (por ejemplo, "Accesorios chapados en oro", "Diseño extremadamente elegante")<br /><br />Estos datos se toman de "Descripción breve del producto" y deben ir separados por comas.<br />Máximo 500 caracteres cada uno.';
MLI18n::gi()->{'amazon_prepare_apply_form__field__bulletpoints__optional__checkbox__labelNegativ'} = 'Utiliza siempre los bullet points actualizados de la tienda web (descripción breve del producto)';
MLI18n::gi()->{'amazon_prepare_apply_form__field__keywords__optional__checkbox__labelNegativ'} = 'Utilice siempre las últimas palabras clave de la tienda web (palabras clave del producto)';