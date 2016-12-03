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
        var q = $('[name="q"]',"#search-form").val();
        if(q==""){
            iCMS.UI.alert("请输入关键词");
            return false;
        }
    });
    var doc = $(document);
    doc.on('click', ".seccode-img,.seccode-text", function(event) {
        event.preventDefault();
        iCMS.UI.seccode();
    });
    //user模块
    iCMS.run('user', function($USER) {
        //用户状态
        $USER.STATUS({},
            //登陆后事件
            function($info) {
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
        //点击关注
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
        //点击私信
        doc.on('click', '[i^="pm"]', function(event) {
            event.preventDefault();
            $USER.PM(this);
        });
        //点击举报
        doc.on('click', '[i="report"]', function(event) {
            event.preventDefault();
            $USER.REPORT(this);
        });
        //用户信息浮层
        $USER.UCARD();
    });
    //通用事件
    iCMS.run('common', function($COMMON) {
        //点赞
        doc.on('click', '[i^="vote"]', function(event) {
            event.preventDefault();
            var me = this;
            $COMMON.vote(this,
                //点赞成功后
                function(ret,param) {
                       var numObj = iCMS.$('vote_'+param['type']+'_num',me),
                           count = parseInt(numObj.text());
                        numObj.text(count + 1);
                }
            );
        });
        //收藏
        doc.on('click', '[i^="favorite"]', function(event) {
            event.preventDefault();
            $COMMON.favorite(this);
        });
    });
    //用户主页评论
    iCMS.run('comment', function($COMMENT) {
        doc.on('click', '[i^="comment"]', function(event) {
            event.preventDefault();
            $COMMENT.start(this);
        });
    });
});
//user模块API
var iUSER = iCMS.run('user');
//comment模块API
var iCOMMENT = iCMS.run('comment');

