/**
 * iCMS - Intelligence Content Management System
 * Copyright (c) 2007-2015 idreamsoft.com iiimon Inc. All rights reserved.
 *
 * @author coolmoo <idreamsoft@qq.com>
 * @site http://www.idreamsoft.com
 * @licence http://www.idreamsoft.com/license.php
 * @version 6.1.0
 */
"use strict"
define(["jquery", "icms"], function($, iCMS) {
    return {
        post: function(param) {
            var a = this;
            $.post(iCMS.API('user'), param, function(ret) {
                a.callback(ret);
            }, 'json');
        },
        callback: function(ret, SUCCESS, FAIL) {
            iCMS.callback(ret, SUCCESS, FAIL, this);
        },
        LOGIN: function(param) {
            param = $.extend(param, {
                'action': 'login'
            });
            this.post(param);
        },
        REGISTER: function(param) {
            param = $.extend(param, {
                'action': 'register'
            });
            this.post(param);
        },
        CHECK: function(param, SUCCESS, FAIL) {
            var a = this;
            $.get(iCMS.API('user', "&do=check"), param, function(ret) {
                a.callback(ret, SUCCESS, FAIL);
            }, 'json');
        }
    }
});
