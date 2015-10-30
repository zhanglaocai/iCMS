requirejs.config({
  baseUrl: iCMS_CONFIG.PUBLIC+'/js',
  paths: {
    jquery:[
        '//apps.bdimg.com/libs/jquery/1.11.3/jquery.min', //http://cdn.code.baidu.com/
        'libs/jquery-1.11.3.min'
    ],
    artdialog:'libs/artDialog-6.0.4/dialog-plus-min',
    dialog:'iCMS.dialog-6.1.0',
    icms:'iCMS-6.1.0',
    user:'user-6.1.0.min',
    register:'register-6.1.0.min',
    login:'login-6.1.0.min',
    comment:'comment-6.1.0.min',
    poshytip:'jquery.poshytip.min',
    insertContent:'jquery.insertContent.min',
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
// iCMS 初始化
require(['jquery','icms'], function($,iCMS,dialog) {
  iCMS.init(iCMS_CONFIG);
  iCMS.alert("asdasd");
  // $(".iCMS_search_btn").click(function(event) {
  //   event.preventDefault();
  //   require(['dialog'], function(d) {
  //     var box = document.getElementById("iCMS-login-box");
  //     d({title: '用户登陆',content:box,elemBack:'remove'});
  //   });
  // });
});


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
