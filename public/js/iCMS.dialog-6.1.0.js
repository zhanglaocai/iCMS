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
define(["jquery", "icms", "artdialog"], function($, iCMS, artdialog) {
    return function(options, callback) {
        var cssUrl = require.toUrl("libs/artDialog-6.0.4/ui-dialog.css");
        iCMS.style(cssUrl, "ui_dialog_css");
        var defaults = {
                id: 'iCMS-DIALOG',
                title: 'iCMS - 提示信息',
                // width:360,height:150,
                className: 'iCMS_UI_DIALOG', //skin:'iCMS_dialog',
                backdropBackground: '#666',
                backdropOpacity: 0.5,
                fixed: true,
                autofocus: false,
                quickClose: true,
                lock: true,
                time: null,
                label: 'success',
                icon: 'check',
                api: false,
                elemBack: 'beforeremove'
            },
            timeOutID = null,
            opts = $.extend(defaults, iCMS.CONFIG.DIALOG, options);
        if (opts.follow) {
            opts.fixed = false;
            opts.lock = false;
            opts.skin = 'iCMS_tooltip_popup'
            opts.className = 'ui-popup';
            opts.backdropOpacity = 0;
        }
        var content = opts.content;
        //console.log(typeof content);
        if (content instanceof jQuery) {
            opts.content = content.html();
        } else if (typeof content === "string") {
            opts.content = __msg(content);
        }
        opts.onclose = function() {
            __callback('close');
        };
        opts.onbeforeremove = function() {
            __callback('beforeremove');
        };
        opts.onremove = function() {
            __callback('remove');
        };
        var d = artdialog(opts);
        //console.log(opts.api);
        if (opts.lock) {
            d.showModal();
            // $(d.backdrop).addClass("ui-popup-overlay").click(function(){
            //     d.close().remove();
            // })
        } else {
            d.show(opts.follow);
            if (opts.follow) {
                //$(d.backdrop).remove();
                // $("body").bind("click",function(){
                //     d.close().remove();
                // })
            }
            //$(d.backdrop).css("opacity","0");
        }
        if (opts.time) {
            timeOutID = window.setTimeout(function() {
                d.destroy();
            }, opts.time);
        }
        d.destroy = function() {
            d.close().remove();
        }

        function __callback(type) {
            window.clearTimeout(timeOutID);
            //console.log('opts.elemBack:'+opts.elemBack,'type:'+type);
            // if(opts.elemBack==type){
            //     //console.log('_elemBack:'+_elemBack);
            //     if (_elemBack) { //删除前把元素返回原来的地方
            //         _elemBack();
            //     }
            // }
            if (typeof(callback) === "function") {
                callback(type);
            }
        }

        function __msg(content) {
            return '<table class=\"ui-dialog-table\" align=\"center\"><tr><td valign=\"middle\">' + '<div class=\"iPHP-msg\">' + '<span class=\"label label-' + opts.label + '\">' + '<i class=\"fa fa-' + opts.icon + '\"></i> ' + content + '</span></div>' + '</td></tr></table>';
        }
        return d;
    };
});
