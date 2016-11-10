define("user", function(require) {
    var utils = require("utils"),
    API = require("api"),
    User = {};
    return $.extend(User, {
        NOAVATAR: function(img) {
            img.src = iCMS.CONFIG.PUBLIC+'/ui/avatar.gif';
        },
        NOCOVER: function(img,type) {
            var name = 'coverpic';
            if(type=="m"){
                name = 'm_coverpic';
            }
            img.src = iCMS.CONFIG.PUBLIC+'/ui/'+name+'.jpg';
        },
        STATUS: function(param, SUCCESS, FAIL) {
            var a = this;
            $.get(API.url('user', '&do=data'), param, function(ret) {
                utils.callback(ret, SUCCESS, FAIL, a);
            }, 'json');
        },
        AUTH: function() {
            var cookie = require("cookie");
            return cookie.get(iCMS.CONFIG.AUTH) ? true : false;
        },
        LOGOUT: function(param, SUCCESS, FAIL) {
            var a = this;
            $.get(API.url('user', "&do=logout"), param, function(ret) {
                utils.callback(ret, SUCCESS, FAIL, a);
            }, 'json');
        }
    });
});
