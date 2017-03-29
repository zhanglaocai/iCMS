window.iCMS = {
    version : "7.0.0",
    plugins : {},
    modules : {},
    data : {},
    UI:{},
    API:{},
    $:function(i,doc) {
        var doc = doc||document;
        return $('[i=' + i + ']',$(doc));
    },
    $v:function(a,i) {
        var param = $(a).attr('i').replace(i+':','');
        return param.split(":");
    },
    require:function (id) {
        var mod = iCMS.modules[id];
        var exports = 'exports';

        if (typeof mod === 'object') {
            return mod;
        }
        if (!mod[exports]) {
            mod[exports] = {};
            mod[exports] = mod.call(mod[exports], iCMS.require, mod[exports], mod) || mod[exports];
        }
        return mod[exports];
    },
    define:function (name, fn) {
        iCMS.modules[name] = fn;
    },
    run:function (id,callback) {
        var mod = iCMS.require(id);
        if (typeof callback === "function") {
            return callback(mod);
        }else{
            return mod;
        }
    }
};
