window.iCMS = $.extend(window.iCMS,{
    API:iCMS.require("api"),
    init: function(options) {
        var config      = iCMS.require("config");
        iCMS.CONFIG     = $.extend(config,options);
        iCMS.CONFIG.API = iCMS.CONFIG.PUBLIC + '/api.php';
        iCMS.UI         = iCMS.require("ui");
        iCMS.FORMER     = iCMS.require("former");
        iCMS.dialog     = iCMS.UI.dialog;
        iCMS.alert      = iCMS.UI.alert;
    }
});
