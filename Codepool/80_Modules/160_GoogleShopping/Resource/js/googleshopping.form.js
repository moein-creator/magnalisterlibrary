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

(function($) {
   jqml(document).ready(function() {
        var directBuyRows =jqml(".magnalisterForm .mljs-directbuy").closest("tr.js-field");
        var directBuyValue =jqml(".magnalisterForm [name='ml[field][checkoutenabled]']").last();
        var directBuyHelp = directBuyValue.closest("td.input").find('.mlhelp');
        if (directBuyHelp.length > 0) {
            directBuyHelp.hide();
            directBuyValue.on('change', function () {
                directBuyValue.prop('checked', false);
                directBuyHelp.find("> a").trigger('click');
            });
        }
        if(directBuyRows.length > 0) {
            /**
             * deactivates all directbuy-rows in form table and shows popup how activate directbuy
             */
            var showDirectBuyPopup = function () {
                directBuyValue.closest("tr.js-field").find(">td.mlhelp>a").trigger("click");
                document.activeElement.blur();// link inside popup was focused by dialog
            };
            directBuyValue.on('change', function() {
                var directBuyInputColumns = directBuyRows.find("td.input");
                if (
                    (directBuyValue.attr("type") === "checkbox" && directBuyValue.is(':checked')) // prepare
                    || (directBuyValue.attr("type") === "hidden" && directBuyValue.val() === "1") // config
                ) {
                    // reset - unblock, allow labels
                    directBuyInputColumns.find("> div").unblock();
                    directBuyRows.find("label").off("click");
                } else {
                    // deactivate all input-columns with directbuy fields
                    directBuyInputColumns.each(function() {
                        if ($(this).find('.ml-wrapped').length === 0) { // build wrapper element if not exists, only block makes problems with td
                           jqml(this).wrapInner('<div class="ml-wrapped" style="display:block; position:relative;"></div>');
                        }
                    })
                    // block and popup
                    directBuyInputColumns
                        .find("> div").block({css: {}, message: "", overlayCSS: {cursor: "default", background: "white"}})
                        .find(".blockOverlay").on("click", function() {
                            showDirectBuyPopup();
                        })
                    ;
                    //deactivate all labels in directbuy rows
                    directBuyRows.find("label").on("click", function() {
                        return false;
                    });
                    // if all rows are directbuy - show popup directly
                    if ($(".magnalisterForm td.input").closest("tr.js-field").not(":hidden").length <= directBuyRows.length) {
                        showDirectBuyPopup();
                    }
                }
            }).trigger('change');
        }
    });
})(jqml);