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

define(["jquery"],function($){
    var iCMS = {
        CONFIG:{
            API: 'api.php',
            PUBLIC: '/public/',
            COOKIE: 'iCMS_',
            AUTH:'USER_AUTH',
            DIALOG:[],
            APP:'iCMS'
        },
        _$: function (i) {
            var doc = $(document);
            return doc.find('[iCMS=' + i + ']');
        },
        init: function(options) {
            //console.log(options);
            this.CONFIG = $.extend(this.CONFIG,options);
            this.CONFIG.API = this.CONFIG.PUBLIC+'api.php';
            // var cssUrl = this.CONFIG.PUBLIC+"/ui/css/iCMS-6.1.0.css";
            // this.style(cssUrl,"iCMS_UI_CSS");
            return this;
        },
        API: function(app, _do) {
            return this.CONFIG.API + '?app=' + app + (_do || '');
        },
        params: function(a) {
            var $this = $(a),$parent   = $this.parent();
            return $.extend(this.param($this),this.param($parent));
        },
        param: function(a,param) {
            if(param){
                a.attr('data-param',this.json2str(param));
                return;
            }
            var param = a.attr('data-param') || false;
            if (!param) return {};
            return $.parseJSON(param);
        },
        style:function(cssUrl,id){
            css = '<link id="'+id+'" href="' + cssUrl + '" type="text/css" rel="stylesheet"/>';
            if(!$("#"+id)[0]){
                if ($('base')[0]) {
                    $('base').before(css);
                } else {
                    $('head').append(css);
                }
            }
        },
        json2str:function(o){
            var arr = [];
            var fmt = function(s) {
                if (typeof s == 'object' && s != null) return this.json2str(s);
                return /^(string|number)$/.test(typeof s) ? '"' + s + '"' : s;
            }
            for (var i in o)
                 arr.push('"' + i + '":'+ fmt(o[i]));

            return '{' + arr.join(',') + '}';
        },
        setcookie: function(cookieName, cookieValue, seconds, path, domain, secure) {
            var expires = new Date();
            expires.setTime(expires.getTime() + seconds);
            cookieName = this.CONFIG.COOKIE + '_' + cookieName;
            document.cookie = escape(cookieName) + '=' + escape(cookieValue) + (expires ? '; expires=' + expires.toGMTString() : '') + (path ? '; path=' + path : '/') + (domain ? '; domain=' + domain : '') + (secure ? '; secure' : '');
        },
        getcookie: function(name) {
            name = this.CONFIG.COOKIE + '_' + name;
            var cookie_start = document.cookie.indexOf(name);
            var cookie_end = document.cookie.indexOf(";", cookie_start);
            return cookie_start == -1 ? '' : unescape(document.cookie.substring(cookie_start + name.length + 1, (cookie_end > cookie_start ? cookie_end : document.cookie.length)));
        },
        random: function(len) {
            len = len || 16;
            var chars = "abcdefhjmnpqrstuvwxyz23456789ABCDEFGHJKLMNPQRSTUVWYXZ",
                code = '';
            for (i = 0; i < len; i++) {
                code += chars.charAt(Math.floor(Math.random() * chars.length))
            }
            return code;
        },
        api_iframe_height:function(a,iframe){
            var height = a.height();
            $(iframe).height(height);
        },
        alert:function (msg,ok,callback) {
          require(['dialog'], function(d) {
              var opts = ok ? {
                  label: 'success',
                  icon: 'check'
              } : {
                  label: 'warning',
                  icon: 'warning'
              }
              opts.id      = 'iPHP-DIALOG-ALERT';
              opts.content = msg;
              opts.time    = 30000000;
              d(opts,callback);
          });
        },
        callback:function (ret,SUCCESS,FAIL,a) {
            var success = SUCCESS||a.SUCCESS
            var fail = FAIL||a.FAIL
            if (ret.code) {
                iCMS.callback_func(success,ret);
            } else {
                iCMS.callback_func(fail,ret);
            }
        },
        callback_func:function (func,ret) {
            if (typeof(func) === "function") {
              func(ret);
            }else{
                var msg = ret;
                if (typeof(ret) === "object") {
                    msg = ret.msg||'error';
                }
                iCMS.alert(msg);
            }
        },
    }
    return iCMS;
});
// (function($) {
//     var _iCMS = {
//         run: function() {
//             iCMS.start();
//             var doc = $(document);
//             this.user.ucard();
//             if (this.user_status) {
//                 this.hover($(".iCMS_user_home"),20,-10);
//             }
//             doc.on("click", '.iCMS_user_follow', function(event) {
//                 event.preventDefault();
//                 var $this = $(this);
//                 iCMS.user.follow(this,function(c,param){
//                     param.follow = (param.follow=='1'?'0':'1');
//                     iCMS.param($this,param);
//                     $this.removeClass((param.follow=='1'? 'follow' : 'unfollow'));
//                     $this.addClass((param.follow=='1' ? 'unfollow' : 'follow'));
//                 });
//             });

//             doc.on('click', 'a[name="iCMS-follow"]', function(event) {
//                 event.preventDefault();
//                 var $this = $(this),$parent = $this.parent();
//                 iCMS.user.follow(this,function(){
//                     $('a[name="iCMS-follow"]',$parent).removeClass('hide');
//                     $this.addClass('hide');
//                 });
//             });
//         },
//         LoginBox: function(a) {
//             var box = document.getElementById("iCMS-login-box");
//             iCMS.user.login(box);
//             iCMS.dialog({title: '用户登陆',content:box,elemBack:'remove'});
//         },
//         hover: function(a, t, l) {
//             var pop,timeOutID = null,t = t || 0, l = l || 0;
//             a.hover(function() {
//                 pop = $(".popover",$(this).parent());
//                 $(".popover").hide();
//                 var position = $(this).position();
//                 pop.show().css({
//                     top: position.top + t,
//                     left: position.left + l
//                 }).hover(function() {
//                     window.clearTimeout(timeOutID);
//                     $(this).show();
//                 }, function() {
//                     $(this).hide();
//                 });
//                 window.clearTimeout(timeOutID);
//             }, function() {
//                 timeOutID = window.setTimeout(function() {
//                     pop.hide();
//                 }, 2500);
//             });
//         }
//     };
//     window.iCMS = $.extend(window.iCMS,_iCMS);//扩展 or 替换 iCMS方法
// })(jQuery);
