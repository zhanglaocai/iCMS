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
    var doc = $(document);

    iCMS.run('user', function($USER) {
        //用户状态
        $USER.STATUS({},
            //登陆后事件
            function($info) {
                console.log($info);
                iCMS.$('user_nickname').text($info.nickname);
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
        doc.on('click', "[i^='follow']", function(event) {
            event.preventDefault();
            $USER.FOLLOW(this,
                //关注成功
                function(ret,$param){
                    if(ret.code){
                        var show = ($param.follow == '1' ? '0' : '1');
                        $("[i='follow:"+$param.uid+":"+$param.follow+"']").hide();
                        $("[i='follow:"+$param.uid+":"+show+"']").show();
                    }
                },
                //关注失败
                function (ret) {
                    iCMS.UI.alert(ret.msg);
                }
            );
        });
        doc.on('click', '[i^="pm"]', function(event) {
            event.preventDefault();
            $USER.PM(this);
        });
        doc.on('click', '[i="report"]', function(event) {
            event.preventDefault();
            $USER.REPORT(this);
        });
        $USER.UCARD();
    });
    iCMS.run('common', function($COMMON) {
        doc.on('click', '[i^="vote"]', function(event) {
            event.preventDefault();
            var me = this;
            $COMMON.vote(this,function(ret,param) {
                if (ret.code) {
                   var numObj = iCMS.$('vote_'+param['type']+'_num',me),
                       count = parseInt(numObj.text());
                    numObj.text(count + 1);
                }
            });
        });
        doc.on('click', '[i^="favorite"]', function(event) {
            event.preventDefault();
            var me = this;
            $COMMON.favorite(this);
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
