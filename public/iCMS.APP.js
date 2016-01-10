/**
* iCMS - i Content Management System
* Copyright (c) 2007-2012 idreamsoft.com iiimon Inc. All rights reserved.
*
* @author coolmoo <idreamsoft@qq.com>
* @site http://www.idreamsoft.com
* @licence http://www.idreamsoft.com/license.php
* @version 6.1.0
* @$Id: iCMS.APP.js 176 2015-12.21 02:52:17Z cool.tea $
*/
"use strict"

requirejs.config({
  baseUrl: iCMS.CONFIG.PUBLIC+'/js',
  paths: {
    jquery:[
        '//apps.bdimg.com/libs/jquery/1.11.3/jquery.min', //http://cdn.code.baidu.com/
        'libs/jquery-1.11.3.min'
    ],
    artdialog:'libs/artDialog-6.0.4/dialog-plus-min',
    icms:'iCMS-6.1.0',
    dialog:'iCMS.DIALOG-6.1.0',
    passport:'iCMS.PASSPORT-6.1.0',
    user:'iCMS.USER-6.1.0',
    comment:'comment-6.1.0',
    poshytip:'jquery.poshytip.min',
    // insertContent:'jquery.insertContent.min',
    // css:'style.css',
  },
  shim: {
    // 'poshytip': ['jquery'],
    // 'insertContent': ['jquery'],
    // 'jquery.scroll': {
    //     deps: ['jquery'],
    //     exports: 'jQuery.fn.scroll'
    // },
    'poshytip':{
      deps:['jquery'],
      exports:'jQuery.fn.poshytip'
    },
    'artdialog':{
      deps:['jquery'],
      exports:'window.dialog'
    }
  }
});

/**
 * iCMS js 接口
 * @param  {[type]} REQ  [require]
 * @param  {[type]} iCMS [iCMS]
 */
require(['jquery','icms'], function($,icms) {
  icms.init(window.iCMS.CONFIG);
  window.iCMS = $.extend(window.iCMS,icms);
  /**
   * [seccode 验证码刷新]
   * @param  {[type]} a [验证码]
   * @param  {[type]} b [容器]
   */
  iCMS.seccode = function(a,b) {
    $(a,b).attr('src', iCMS.API('public', '&do=seccode&') + Math.random());
  };
});


  //
  // // require(['user'],function ($USER) {

//     $USER.STATUS({},
//         //登陆后事件
//         function($info) {
//             console.log($info);
//             $("#user-login").hide();
//             $("#user-profile").show();
//         },
//         //未登陆事件
//         function(f) {
//             console.log(f)
//         }
//     );
//     $(".logout").click(function(event) {
//         event.preventDefault();
//         $USER.LOGOUT({'forward':window.top.location.href},
//         function(s) {
//             console.log(s);
//             // window.top.location.reload();
//         });
//     });
// });
// console.log('--------',iCMS);

// (function(require,iCMS){

  /**
   * [$user 用户]
   * @type {Object}
   */

  // iCMS.USER = {};
  // require(['user'], function($user) {
  //   iCMS.USER = $.extend(iCMS.USER,$user);
  // });
  // iCMS.USER.init = function(){

  // };
  // require(['user'], function($user) {
  //   iCMS.USER = $user;
  // });


  // iCMS.USER.STATUS = function (param,SUCCESS,FAIL) {
  //   require(['user'], function($user) {
  //     $user.STATUS(param,SUCCESS,FAIL);
  //   });
  // }

  /**
   * [$passport 注册/登陆]
   * @type {Object}
   */
//   iCMS.PASSPORT = {};
//   iCMS.PASSPORT.init = function(){
//     require(['passport'], function($passport) {
//       iCMS.PASSPORT = $.extend(iCMS.PASSPORT,$passport);
//     });
//   };

// })(require,iCMS);




// require(['jquery', 'iCMS'], function($,iCMS) {
//     console.log($);
// });
// require(['jquery'], function($) {
//   // var mod = require("insertContent");
//     // var cssUrl = require.toUrl("./style.css");
//     console.log(mod,$().jquery);
// });
// define(["jquery"],function($){
//     console.log($().jquery);
// });
