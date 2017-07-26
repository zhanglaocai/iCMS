$(function() {
    //图片延迟加载
    $("img.lazy").lazyload();

    var doc = $(document);
    //验证码
    doc.on('click', ".seccode-img,.seccode-text", function(event) {
        event.preventDefault();
        iCMS.UI.seccode();
    });
    //user模块
    iCMS.run('user', function($USER) {
        //定义登陆事件
        // $USER.LOGIN = function () {}
        //
        //用户状态
        $USER.STATUS({},
            //登陆后事件
            function($info) {
                iCMS.$('user_nickname').text($info.nickname);
                iCMS.$('user_avatar').attr("src",$info.avatar).show();
                iCMS.$('login').hide();
                iCMS.$('profile').show();
            },
            //未登陆事件
            function(f) {
                // console.log(f)
            }
        );
        //点击退出登陆
        doc.on('click', "[i='logout']", function(event) {
            event.preventDefault();
            $USER.LOGOUT({
                'forward': window.top.location.href
            },
            //退出成功事件
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
        doc.on('click', '[i^="vote:"]', function(event) {
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
        doc.on('click', '[i^="favorite:"]', function(event) {
            event.preventDefault();
            $COMMON.favorite(this);
        });
    });
    //用户主页评论
    iCMS.run('comment', function($COMMENT) {
        doc.on('click', '[i="comment:article"]', function(event) {
            event.preventDefault();
            var me = this;
            $COMMENT.create(me);
            //加载评论框模板
            // $COMMENT.template('form',function (tmpl) {
            //     $COMMENT._widget._form = $(tmpl);
            // });
        });
    });
});
//user模块API
var iUSER = iCMS.run('user');
//comment模块API
// var iCOMMENT = iCMS.run('comment');

function imgFix (im, x, y) {
    x = x || 99999
    y = y || 99999
    im.removeAttribute("width");
    im.removeAttribute("height");
    if (im.width / im.height > x / y && im.width > x) {
        im.height = im.height * (x / im.width)
        im.width  = x
        im.parentNode.style.height = im.height * (x / im.width) + 'px';
    } else if (im.width / im.height <= x / y && im.height > y) {
        im.width  = im.width * (y / im.height)
        im.height = y
        im.parentNode.style.height = y + 'px';
    }
}
/*!
 * Lazy Load - jQuery plugin for lazy loading images
 *
 */
(function(a){
    a.fn.lazyload=function(b){var c={attr:"data-src",container:a(window),callback:a.noop};var d=a.extend({},c,b||{});d.cache=[];a(this).each(function(){var h=this.nodeName.toLowerCase(),g=a(this).attr(d.attr);var i={obj:a(this),tag:h,url:g};d.cache.push(i)});var f=function(g){if(a.isFunction(d.callback)){d.callback.call(g.get(0))}};var e=function(){var g=d.container.height();if(a(window).get(0)===window){contop=a(window).scrollTop()}else{contop=d.container.offset().top}a.each(d.cache,function(m,n){var p=n.obj,j=n.tag,k=n.url,l,h;if(p){l=p.offset().top-contop,l+p.height();if((l>=0&&l<g)||(h>0&&h<=g)){if(k){if(j==="img"){f(p.attr("src",k))}else{p.load(k,{},function(){f(p)})}}else{f(p)}n.obj=null}}})};e();d.container.bind("scroll",e)}}
)(jQuery);
