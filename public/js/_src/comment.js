define("comment", function(require) {
    var API = require("api"),UI = require("ui"),USER = require("user"),utils = require("utils"),

    $COMMENT = {
        seccode:iCMS.CONFIG.COMMENT.seccode,
        widget:{
            like_text:'<span class="like-num" i="tip:1 人觉得这个很赞"><em>1</em> <span>赞</span></span>',
            load_more:'<a href="javascript:;" class="load-more" i="loadmore"><span class="text">显示全部评论</a>',
            spinner:$('<div class="commentApp-spinner">正在加载，请稍等 <i class="spinner-lightgray"></i></div>'),
            form:$('<div class="commentApp-form">'+
                    '<div class="commentApp-ipt">' +
                        '<input class="commentApp-textarea form-control" type="text" placeholder="写下你的评论…">' +
                    '</div>' +
                    '<div class="cmt-command">' +
                        '<div class="cmt-seccode">' +
                            '<input type="text" maxlength="4" name="seccode" class="commentApp-seccode form-control" placeholder="验证码">'+
                            '<span class="public_seccode">'+
                                '<img src="'+API.url('public', "&do=seccode")+'" alt="验证码" class="seccode-img r3" title="点击更换验证码图片"/>'+
                                '<a href="javascript:;" class="seccode-text" style="float: none">换一张</a>'+
                            '</span>'+
                        '</div>' +
                        '<a href="javascript:;" i="addnew" class="btn btn-primary">评论</a>' +
                        '<a href="javascript:;" i="close" class="cmt-command-cancel">取消</a>' +
                    '</div>'+
                    '<div class="clearfix"></div>' +
                '</div>'),
            item_tpl:'<div class="commentApp-item" data-id="<%=id%>">' +
                    '<a title="<%=user.name%>" i="ucard:<%=userid%>" class="cmt-item-link-avatar" href="<%=user.url%>">' +
                        '<img src="<%=user.avatar%>" class="cmt-item-img-avatar" onerror="iUSER.NOAVATAR(this);">' +
                    '</a>' +
                    '<div class="commentApp-content-wrap">' +
                        '<div class="commentApp-content-hd">' +
                            '<a i="ucard:<%=userid%>" href="<%=user.url%>" target="_blank" class="zg-link"><%=user.name%></a>'+
                            '<%if(suid == userid){%><span class="desc">（作者）</span><%};%>'+
                            '<%if(typeof(reply)!=="undefined"){%>'+
                            '<span class="desc"> 回复 </span>' +
                            '<a i="ucard:<%=reply.uid%>" href="<%=reply.url%>" target="_blank" class="zg-link"><%=reply.name%></a>'+
                            '<%}%>'+
                        '</div>' +
                        '<div class="commentApp-content"><%=content%></div>' +
                        '<div class="commentApp-content-ft">' +
                            '<span class="date"><%=addtime%></span>' +
                            '<a href="javascript:;" class="reply commentApp-op-link" i="reply" data-param=\'{"id":"<%=id%>","userid":"<%=userid%>","name":"<%=user.name%>"}\'>' +
                                '<i class="commentApp-icon comment-reply-icon"></i>回复' +
                            '</a>' +
                            '<a href="javascript:;" class="like commentApp-op-link" i="like" data-param=\'{"id":"<%=id%>","userid":"<%=userid%>","name":"<%=user.name%>"}\'>' +
                                '<i class="commentApp-icon comment-like-icon"></i>赞'+
                            '</a>'+
                            '<%if(up != "0"){%>'+
                                '<span class="like-num" i="tip:<%=up%> 人觉得这个很赞">' +
                                '<em><%=up%></em> <span>赞</span></span>'+
                            '<%}%>'+
                            '<a href="javascript:;" i="report" data-param=\'{"appid":"5","iid":"<%=id%>","userid":"<%=userid%>"}\' class="report commentApp-op-link needsfocus">' +
                            '<i class="commentApp-icon comment-report-icon"></i>举报</a>' +
                        '</div>' +
                    '</div>' +
                '</div>'
        },
        page_no:{},
        page_total:{}
    };

    return $.extend($COMMENT, {
        form:function () {
            var form = $COMMENT.widget.form.clone();
            if($COMMENT.seccode=="0"){
                $('.cmt-seccode',form).remove();
            }
            return form;
        },
        page:function(pn, a,func) {
            var $this = $(a),
                p = $this.parent(),
                pp = p.parent(),
                query = p.attr('data-query'),
                url = iCMS.CONFIG.API+'?'+query+'&pn='+pn;

            $.get(url,function(ret){
                utils.__callback (func,ret);
            });
        },
        reply:function (a) {
            if (!USER.CHECK.LOGIN()) return;

            var me = this,
                item = $(a).parent().parent(),
                param = API.param($(a)),
                form  = $COMMENT.form(),
                caf = $('.commentApp-form', item);
            if (caf.length > 0) {
                caf.remove();
                return false;
            }
            $('.commentApp-form', '.commentApp-list').remove();
            form.addClass('expanded').removeClass('commentApp-wrap-ft');
            $(a).parent().after(form);

            var textarea = $('.commentApp-textarea', form);
            textarea.data('param', param).focus();
            $('[i="close"]', form).click(function(event) {
                event.preventDefault();
                textarea.val("");
                form.remove();
                //$COMMENT.iframe_height('list');
            });
        },
        like:function (a) {
            if (!USER.CHECK.LOGIN()) return;

            var $this = $(a),param = API.param($this);
            param["do"] = 'like';
            $.get(API.url('comment'), param, function(c) {
                if (c.code) {
                    var p = $this.parent(),
                        like_num = $('.like-num em', p).text();
                    if (like_num) {
                        like_num = parseInt(like_num) + 1;
                        $('.like-num em', p).text(like_num);
                    } else {
                        $this.parent().append($COMMENT.widget.like_text);
                    }
                } else {
                    UI.alert(c.msg);
                }
            }, 'json');
        },
        addnew:function (a,param,SUCCESS, FAIL) {
            if (!USER.CHECK.LOGIN()) return;
            var me = this;
            var form = $(a).parent().parent(),
                textarea = $('.commentApp-textarea', form),
                data = textarea.data('param'),
                cmt_param = $.extend(param, data);

            if($COMMENT.seccode=="1"){
                var seccode = $('.commentApp-seccode', form)
                cmt_param.seccode = seccode.val();
            }

            cmt_param.action  = 'add';
            cmt_param.content = textarea.val();

            if (!cmt_param.content) {
                textarea.focus();
                return false;
            }

            $.post(API.url('comment'), cmt_param, function(ret) {
                if($COMMENT.seccode=="1"){
                    UI.seccode();
                    seccode.val('');
                }
                if(ret.code){
                    textarea.val('');
                }
                utils.callback(ret, SUCCESS, FAIL, me);
            }, 'json');
        },
        list:function (container,iid,id,type) {
            if(!id){
                $COMMENT.page_no[iid]++;
                if($COMMENT.page_total[iid]){
                    if ($COMMENT.page_no[iid] > $COMMENT.page_total[iid]) {
                       return false;
                    }
                }
            }
            if(type){
                var $list = container;
            }else{
                var $list = $('.commentApp-list',container);
            }
            $.get(API.url('comment'),{
                    'do': 'json',
                    'iid': iid,
                    'id': (id||0),
                    'by': 'ASC',
                    page: $COMMENT.page_no[iid]
                },
                function(json) {
                    $COMMENT.widget.spinner.remove();
                    if (!json){
                        return false;
                    }
                    if(!id){
                        $COMMENT.page_total[iid] = json[0].page.total;
                    }
                    $.each(json, function(i, data) {
                        var item = $.parseTmpl($COMMENT.widget.item_tpl,data);
                        if(type=="after"){
                            $list.after(item);
                        }else if(type=="before"){
                            $list.before(item);
                        }else if(type=="append"){
                            $list.append(item);
                        }else{
                            $list.append(item);
                        }
                    });
                    USER.UCARD();
                    if(!id){
                        $(".load-more",container).remove();
                        if ($COMMENT.page_no[iid] < $COMMENT.page_total[iid]) {
                            $list.after($COMMENT.widget.load_more);
                        }
                    }
                }, 'json');
        },
        start: function(a) {
            var $this = $(a),
                p = $this.parent(),
                pp = p.parent(),
                param = API.param(p),
                wrap = $('.commentApp-wrap', pp);
            if (wrap.length > 0) {
                wrap.remove();
                return false;
            }

            var $spike = $('<i class="commentApp-icon comment-spike-icon commentApp-bubble"></i>'),
                $wrap   = $('<div class="commentApp-wrap">'),
                $list  = $('<div class="commentApp-list">'),
                $form  = $COMMENT.form();
            var iid   = param['iid'],
                left = $this.position().left;

            $spike.show().css({'left':left});
            $form.addClass('commentApp-wrap-ft');
            $wrap.html($COMMENT.widget.spinner);
            $wrap.append($spike, $list, $form);
            p.after($wrap);

            //加载评论
            $COMMENT.page_no[iid]    = 0;
            $COMMENT.page_total[iid] = 0;
            $COMMENT.list($wrap,iid);

            //----------绑定事件----------------
            $form.on('focus', '.commentApp-textarea', function(event) {
                $(this).parent().parent().addClass('expanded');
            }).on('click', '[i="close"]', function(event) {
                event.preventDefault();
                var pp = $(this).parent().parent();
                pp.removeClass('expanded');
                $('.commentApp-textarea', pp).val("");
            });
            //加载更多
            $wrap.on('click', '[i="loadmore"]', function(event) {
                event.preventDefault();
                $(".load-more", $list).remove();
                $COMMENT.list($wrap,iid);
            })
            //提交评论
            .on('click', '[i="addnew"]', function(event) {
                event.preventDefault();
                var that = $(this);
                $COMMENT.addnew(this,param,
                    function(ret){
                        var count = parseInt($('[i="comment_num"]', $this).text());
                        $('[i="comment_num"]', $this).text(count + 1);
                        var itemp = that.parents('.commentApp-item');
                        if(itemp.length){
                            var type = 'after';
                        }else{
                            var itemp = that.parents(".commentApp-wrap");
                            var type = null;
                        }
                        $COMMENT.list(itemp,iid,ret.forward,type);
                    }
                )
            })
            //回复评论
            .on('click', '[i="reply"]', function(event) {
                event.preventDefault();
                $COMMENT.reply(this);
            })
            //赞评论
            .on('click', '[i="like"]', function(event) {
                event.preventDefault();
                $COMMENT.like(this);
            });
            //------------
        }
    });
});
