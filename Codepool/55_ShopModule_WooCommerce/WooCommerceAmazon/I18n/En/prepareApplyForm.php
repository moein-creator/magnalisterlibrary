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
 * (c) 2010 - 2021 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

MLI18n::gi()->{'amazon_prepare_apply_form__field__bulletpoints__hint'} = 'Key features of the product (e.g. "gold plated taps")<br /><br />These data are taken from the (Product short description), and must be comma separated.<br />Up to 500 characters each.';
MLI18n::gi()->{'amazon_prepare_apply_form__field__bulletpoints__optional__checkbox__labelNegativ'} = 'Always use Bullet Points from the shop (Product short description)';


MLI18n::gi()->{'amazon_prepare_apply_form__field__keywords__help'} = '
<h3>Optimize product ranking with Amazon keywords</h3>
<br>
General keywords are used to optimize the ranking and for better filterability on Amazon. They are stored with the product invisibly, when uploaded.
<br><br>
<h2>Options for the Submission of General Keywords</h2>

1. Always use up-to-date keywords from the web shop (Product tags): 
<br><br>
Here, the keywords are taken from the "Product Tags" field of the corresponding product in the web shop and submitted to Amazon.
<br><br>
2. Manually enter general keywords in magnalister 
<br><br>
If you do not want to use the "product tags" stored in the web shop product, you can enter your own keywords in the empty text field provided by magnalister.
<br><br>
<b>Important Notes:</b>
<ul><li>
If you enter keywords manually, separate them with a space (not a comma!). The maximum length of all keywords (rule of thumb: 1 character = 1 byte. Exception: special characters such as Ä, Ö, Ü = 2 bytes) may not exceed 250 bytes.
</li><li>
If the keywords of your web shop product are separated by commas, magnalister automatically converts these commas into spaces when uploading the product. The limitation to 250 bytes also applies here.
</li><li>
If the allowed byte count is exceeded, Amazon may return an error message after the product upload, which can be viewed in the magnalister error log. Please note that it can take up to 60 minutes until error messages are displayed in the magnalister error log.
</li><li>
Submission of Platinum Keywords: Transfer of Platinum Keywords: If you are an Amazon Platinum seller, please inform the magnalister support about it. We will then activate the submission of Platinum keywords. magnalister uses the general keywords and adopts them 1:1. General keywords and Platinum keywords are therefore identical.
</li><li>
If you want to transfer Platinum keywords to Amazon that differ from the "General Keywords", use the magnalister attribute matching under "Prepare Items" -> "Create New Products" -> "Amazon Optional Attributes". To do so, select "Platinum keywords 1-5" from the list of available Amazon attributes and match the corresponding web shop attribute.
</li><li>
In addition to general keywords, there are other Amazon-relevant keywords (e.g. thesaurus attribute keywords, target group keywords or topic keywords), which you can also be submitted to Amazon via attribute matching.
</li></ul>
';
MLI18n::gi()->{'amazon_prepare_apply_form__field__keywords__optional__checkbox__labelNegativ'} = 'Always use the Keywords from the shop (Product tags)';