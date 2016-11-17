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
        STATUS: function($param, SUCCESS, FAIL) {
            var me = this;
            $.get(API.url('user', '&do=data'), $param, function(ret) {
                utils.callback(ret, SUCCESS, FAIL, me);
            }, 'json');
        },
        AUTH: function() {
            var cookie = require("cookie");
            return cookie.get(iCMS.CONFIG.AUTH) ? true : false;
        },
        LOGOUT: function($param, SUCCESS, FAIL) {
            var me = this;
            $.get(API.url('user', "&do=logout"), $param, function(ret) {
                utils.callback(ret, SUCCESS, FAIL, me);
            }, 'json');
        },
        FOLLOW: function($param,SUCCESS,FAIL) {
            var me = this;
            var auth = this.AUTH();
            if (!auth) {
              return utils.__callback(FAIL,me);
            }
            var data = API.param($param);
            data.action = "follow";
            $.post(API.url('user'), data, function(ret) {
                utils.callback(ret,SUCCESS,SUCCESS,me,data);
            }, 'json');
        },
    });
});
