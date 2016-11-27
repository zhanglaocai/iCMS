window.iCMS = {
    version : "6.2.0",
    plugins : {},
    modules : {},
    data : {},
    UI:{},
    API:require("api"),
    $:function(i) {
        var doc = $(document);
        return doc.find('[i=' + i + ']');
    },
    $v:function(a,i) {
        var param = $(a).attr('i').replace(i+':','');
        return param.split(":");
    },
    init: function(options) {
        var config      = require("config");
        iCMS.CONFIG     = $.extend(config,options);
        iCMS.CONFIG.API = iCMS.CONFIG.PUBLIC + '/api.php';
        iCMS.UI         = require("ui");
        iCMS.dialog     = iCMS.UI.dialog;
        iCMS.alert      = iCMS.UI.alert;
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
