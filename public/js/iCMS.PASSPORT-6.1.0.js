/**
* iCMS - Intelligence Content Management System
* Copyright (c) 2007-2015 idreamsoft.com iiimon Inc. All rights reserved.
*
* @author coolmoo <idreamsoft@qq.com>
* @site http://www.idreamsoft.com
* @licence http://www.idreamsoft.com/license.php
* @version 6.1.0
*/
define(["jquery","icms"],function($,iCMS){
    return {
        LOGIN:function (param,success,fail) {
            var param = $.extend(param,{'action': 'login'});
            $.post(iCMS.api('user'), param, function(ret) {
                if (ret.code) {
                    iCMS.callback(success,ret);
                } else {
                    iCMS.callback(fail,ret);
                }
            }, 'json');
        },
        REGISTER:function (param,success,fail) {
            var param = $.extend(param,{'action': 'register'});
            $.post(iCMS.api('user'), param, function(ret) {
                if (ret.code) {
                    iCMS.callback(success,ret);
                } else {
                    iCMS.callback(fail,ret);
                }
            }, 'json');
        },
        ajax_check:function (param,success,fail) {
            $.get(iCMS.api('user',"&do=check"),param,function(ret) {
                if (ret.code) {
                    iCMS.callback(success,ret);
                } else {
                    iCMS.callback(fail,ret);
                }
            }, 'json');
        }
    }
});
