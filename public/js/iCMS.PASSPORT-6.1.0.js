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
            if(param.uname==""){
                iCMS.callback(fail,['uname','请输入用户名']);
                return false
            }
            if(param.pass==""){
                iCMS.callback(fail,['pass','请输入密码']);
                return false
            }
            if(param.login_seccode){
                if(param.seccode==""){
                    iCMS.callback(fail,['seccode','请输入验证码']);
                    return false
                }
            }

            $.post(iCMS.api('user'), param, function(ret) {
                if (ret.code) {
                    iCMS.callback(success,[ret.forward,ret.msg]);
                } else {
                    iCMS.callback(fail,[ret.forward,ret.msg]);
                }
            }, 'json');
        },
        REGISTER:function (param,success,fail) {
            var param = $.extend(param,{'action': 'register'});
        },
        ajax_check:function (param,success,fail) {
            $.get(iCMS.api('user',"&do=check"),param,
                // {name: a.name,value: a.value},
                function(ret) {
                    if (ret.code) {
                        $(a).data("check", true);
                        iCMS.callback(success,[a]);
                    } else {
                        $(a).data("check", false);
                        iCMS.callback(fail,[a,ret.msg]);
                    }
                }
            , 'json');

        }
    }
});
