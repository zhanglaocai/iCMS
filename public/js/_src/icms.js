window.iCMS = {
    plugins : {},
    modules : {},
    version : "6.2.0",
    UI:require("ui"),
    API:require("api"),
    init: function(options) {
        var config      = require("config");
        iCMS.CONFIG     = $.extend(config,options);
        iCMS.CONFIG.API = iCMS.CONFIG.PUBLIC + '/api.php';
    },
    run:function (id,callback) {
        var mod = require(id);
        if (typeof callback === "function") {
            return callback(mod);
        }else{
            return mod;
        }
    }
};
