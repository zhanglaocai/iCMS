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
        // post:function (param) {
        //     var a = this;
        //     $.post(iCMS.API('user'), param,function(ret) {
        //         a.callback(ret);
        //     }, 'json');
        // },
        callback:function (ret,SUCCESS,FAIL) {
            var success = SUCCESS||this.SUCCESS
            var fail = FAIL||this.FAIL
            if (ret.code) {
                iCMS.callback(success,ret);
            } else {
                iCMS.callback(fail,ret);
            }
        },
        // LOGIN:function (param) {
        //     param = $.extend(param,{'action': 'login'});
        //     this.post(param);
        // },

        STATUS:function (param,SUCCESS,FAIL) {
            var a = this;
            $.get(iCMS.API('user', '&do=data'),param,function(ret) {
                a.callback(ret,SUCCESS,FAIL);
            }, 'json');
        },
        AUTH: function() {
            return iCMS.getcookie(iCMS.config.AUTH) ? true : false;
        },
        LOGOUT: function (param,SUCCESS,FAIL) {
            var a = this;
            $.get(iCMS.API('user', "&do=logout"),param,function(ret) {
                a.callback(ret,SUCCESS,FAIL);
            }, 'json');
        }
    }
});
