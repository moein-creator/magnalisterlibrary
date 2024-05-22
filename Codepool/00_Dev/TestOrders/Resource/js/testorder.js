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
 * (c) 2010 - 2024 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

(function ($) {
    $(document).ready(function () {
        if ($('h4.ml-translate-toolbar-wrapper').length > 4) {
            let tbli = '';
            tbli += '<li><a title="Top" href="#magnalogo">^</a></li>';
            $('h4.ml-translate-toolbar-wrapper:visible').each(function () {
                const maxLength = 20;
                const title = $.trim($(this).text());
                tbli += '<li><a title="' + title + '" href="#' + $(this).attr('id') + '">' + title.substring(0, maxLength) + (title.length > maxLength ? '...' : '') + '</a></li>';
            })
            let tableOfContent = $('<ul style="position: fixed; right:0; top: 200px; background-color: white;border-radius: 5px;box-shadow: -5px 5px 10px gray  ; padding: 20px 20px 30px 30px;margin-right: 40px;">' + tbli + '</ul>');
            $('body').append(tableOfContent);
        }
    });
})(jqml);