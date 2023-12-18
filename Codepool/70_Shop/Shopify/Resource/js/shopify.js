(function($, self, top) {
    $(document).ready(function() {
        var safari   = navigator.userAgent.indexOf("Safari") > -1;
        var chrome   = navigator.userAgent.indexOf('Chrome') > -1;
        if ((chrome) && (safari)) safari = false;
        if (self !== top && safari) {
            $("a").attr("target", "_blank");
        }
    });
})(jqml, self, top );