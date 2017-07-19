iCMS.define("comment", function() {
    var API = iCMS.require("api"),UI = iCMS.require("ui"),USER = iCMS.require("user"),utils = iCMS.require("utils"),

    $COMMENT = {
        seccode:iCMS.CONFIG.COMMENT.seccode,
        widget:{
            like:'<span class="text-num" i="tip:1 人觉得这个很赞"><em>1</em> <span>赞</span></span>',
            more:'<a href="javascript:;" class="load-more" i="comment_load"><span class="text">显示全部评论</a>',
            spinner:$('<div class="commentApp-spinner">正在加载，请稍等 <i class="spinner-lightgray"></i></div>'),
            item:null,
        },
        page_no:{},
        page_total:{},
        container:{}
    };

    return $.extend($COMMENT, {
        template:function (name,func) {
            if($COMMENT.container[name]){
                return func($COMMENT.container[name]);
            }
            $.get(API.url('comment'),'&do=widget&name='+name,
                function(tpl){
                    $COMMENT.container[name] = tpl;
                    func(tpl)
                }
            )
        },
        miniForm:function (callback) {

            $COMMENT.template('mini.form',function (f) {

                var $f = $(f);

                $f.on('focus', '[i="comment_content"]', function(event) {
                    event.preventDefault();
                    $f.addClass('expanded');
                }).on('click', '[i="comment_close"]', function(event) {
                    event.preventDefault();
                    $f.removeClass('expanded');
                    $('[i="comment_content"]', $f).val("");
                });

                if(callback){
                    utils.__callback (callback,$f);
                }

            });
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
                caf = $('.commentApp-form', item);
            if (caf.length > 0) {
                caf.remove();
                return false;
            }
            $('.commentApp-form','.commentApp-list').remove();
            $('.commentApp-form').removeClass('expanded');

            $COMMENT.miniForm(function($f){
                $f.addClass('expanded');
                $('[i="comment_close"]', $f).click(function(event) {
                    event.preventDefault();
                    var $c = $('[i="comment_content"]', $f);
                    $c.data('param', param).focus();
                    $c.val("");
                    $f.remove();
                    //$COMMENT.iframe_height('list');
                });

                $(a).parent().after($f);
            });
        },
        like:function (a,SUCCESS, FAIL) {
            if (!USER.CHECK.LOGIN()) return;

            var me=this,$this = $(a),param = API.param($this);

            me.SUCCESS = function (ret,param) {
                var p = $this.parent(),
                    num = $('[i="comment_up"]', p).text();
                if (num) {
                    num = parseInt(num) + 1;
                    $('[i="comment_up"]', p).text(num);
                } else {
                    p.append($COMMENT.widget.like);
                }
            };

            param["do"] = 'like';
            $.get(API.url('comment'), param, function(ret) {
                utils.callback(ret, SUCCESS, FAIL, me);
            }, 'json');
        },
        add:function ($form,param,SUCCESS, FAIL) {
            if (!USER.CHECK.LOGIN()) return;
            var me = this;
            if($COMMENT.seccode=="1"){
                var comment_seccode = $('[i="comment_seccode"]', $form);
                param.seccode = comment_seccode.val();
                if (!param.seccode) {
                    comment_seccode.focus();
                    return false;
                }
            }

            var comment_content = $('[i="comment_content"]', $form);
            param.content = comment_content.val();
            if (!param.content) {
                comment_content.focus();
                return false;
            }
           var refresh = function (ret) {
                if(ret.forward!='seccode'){
                    comment_content.val('');
                }
                if(typeof(comment_seccode)!=="undefined"){
                    comment_seccode.val('');
                    UI.seccode();
                }
            }

            var _param = comment_content.data('param');

            if(typeof(_param)!=="undefined"){
                param = $.extend(param, _param);
            }

            param.action  = 'add';
            $.post(API.url('comment'), param, function(ret) {
                refresh(ret);
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
                        var item = $.parseTmpl($COMMENT.widget.item,data);
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
                    if(!id){
                        $(".load-more",container).remove();
                        if ($COMMENT.page_no[iid] < $COMMENT.page_total[iid]) {
                            $list.after($COMMENT.widget.more);
                        }
                    }
                }, 'json');
        },

        create: function(a) {
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
                $wrap = $('<div class="commentApp-wrap">'),
                $list = $('<div class="commentApp-list">'),
                iid   = param['iid'];

            var pos = $this.position();
            var _isMobile = 'createTouch' in document && !('onmousemove' in document)
                || /(iPhone|iPad|iPod)/i.test(navigator.userAgent);

            $spike.css({'left':_isMobile?event.offsetX:pos.left,'display':'inline'});


            $wrap.html($COMMENT.widget.spinner);
            $wrap.append($spike, $list);

            $COMMENT.miniForm(function($f){
                $f.addClass('commentApp-wrap-ft');
                $wrap.append($f);
            });

            p.after($wrap);

            //加载评论列表模板
            $COMMENT.template('item',function (tmpl) {
               $COMMENT.widget.item = tmpl;
                //加载评论
                $COMMENT.page_no[iid]    = 0;
                $COMMENT.page_total[iid] = 0;
                $COMMENT.list($wrap,iid);
            });

            //----------绑定事件----------------
            //加载更多
            $wrap.on('click', '[i="comment_load"]', function(event) {
                event.preventDefault();
                $(".load-more", $list).remove();
                $COMMENT.list($wrap,iid);
            })
            //提交评论
            .on('click', '[i="comment_put"]', function(event) {
                event.preventDefault();
                var that = $(this),tpp = that.parent().parent();

                $COMMENT.add(tpp,param,
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
                    },
                    function (ret) {
                        UI.alert(ret.msg);
                    }
                )
            })
            //回复评论
            .on('click', '[i="comment_reply"]', function(event) {
                event.preventDefault();
                $COMMENT.reply(this);
            })
            //赞评论
            .on('click', '[i="comment_like"]', function(event) {
                event.preventDefault();
                $COMMENT.like(this);
            });
            //------------
        }
    });
});
