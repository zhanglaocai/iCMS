$(function() {
    $("img.lazy").lazyload();
    $(".carousel-box").slider({
        left_btn: '#carousel-left',
        right_btn: '#carousel-right',
    });
    $(".tabs-wrap").tabs({
        action: 'mouseover'
    });
    $(".rank").tabs({
        item: '.rank-list',
        action: 'mouseover'
    });

    $(".search-btn").click(function(event) {
        event.preventDefault();
        var q = $('[name="q"]',"#search-form").val();
        if(q==""){
            iCMS.UI.alert("请输入关键词");
            return false;
        }
    });
    $(".seccode-img,.seccode-text").click(function(event) {
        event.preventDefault();
        iCMS.UI.seccode();
    });

    iCMS.run('user', function($USER) {
        //用户状态
        $USER.STATUS({},
            //登陆后事件
            function($info) {
                console.log($info);
                $("#user-login").hide();
                $("#user-profile").show();
            },
            //未登陆事件
            function(f) {
                console.log(f)
            }
        );
        //退出登陆
        $(".logout").click(function(event) {
            event.preventDefault();
            $USER.LOGOUT({
                'forward': window.top.location.href
            },
            function(s) {
                window.top.location.reload();
            });
        });
    });

});

var iUSER = iCMS.run('user');


// function hover (a, t, l) {
//     var pop,timeOutID = null,t = t || 0, l = l || 0;
//     a.hover(function() {
//         pop = $(".popover",$(this).parent());
//         $(".popover").hide();
//         var position = $(this).position();
//         pop.show().css({
//             top: position.top + t,
//             left: position.left + l
//         }).hover(function() {
//             window.clearTimeout(timeOutID);
//             $(this).show();
//         }, function() {
//             $(this).hide();
//         });
//         window.clearTimeout(timeOutID);
//     }, function() {
//         timeOutID = window.setTimeout(function() {
//             pop.hide();
//         }, 2500);
//     });
// }
