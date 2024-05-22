(function($) {
    $(document).ready(function() {
        checkAdminLoginStatus()
        //informs the shopware cloud that the iframe is finished loading
        //this part is mandatory in order to show the iframe in shopware cloud
        window.parent.postMessage("sw-app-loaded", "*");
    });
})(jqml);

/**
 * Checks if user is not in the iframe longer than 15min
 * and redirect him to shopware magnalister plugin
 */
function checkAdminLoginStatus() {
    if (window.self === window.top) {
        var urlParams = new URLSearchParams(window.location.search),
            shopUrl = urlParams.get('shop-url'),
            redirectUrl = shopUrl + '/admin#/my-apps/magnalister/magnalister',
            urlTimestamp = urlParams.get('timestamp'),
            // time is 15 min in the past
            time = (Date.now() / 1000) - (15 * 60);
        if (urlTimestamp <= time) {
            window.location.href = redirectUrl;
        }
    }
}
