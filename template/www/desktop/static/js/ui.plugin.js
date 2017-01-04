/**
* iCMS - Intelligence Content Management System
* Copyright (c) 2007-2015 idreamsoft.com iiimon Inc. All rights reserved.
*
* @author coolmoo <idreamsoft@qq.com>
* @site http://www.idreamsoft.com
* @licence http://www.idreamsoft.com/license.php
* @version 6.1.0
*/
(function($) {
    $.fn.slider = function(options) {
        var defaults = {
            left_btn: '#slider-left',
            right_btn: '#slider-right',
            num_btn: "#slider-btn",
            classname: "active",
            item: "li",
            time: 3000,
            sync: null
        }
        var options = $.extend(defaults, options);
        return this.each(function() {
            var a = $(this)
              ,
            b = $(options.num_btn)
              ,
            l = $(options.left_btn)
              ,
            r = $(options.right_btn)
              ,
            current = 0
              ,
            timeOut = null
              ,
            auto = true
              ,
            len = $(options.item, a).length;

            if (options.sync) {
                var s = $(options.sync);
            }
            if (l) {
                $(l).click(function(event) {
                    event.preventDefault();
                    clearTimeout(timeOut);
                    current--;
                    show(current);
                }).mouseout(function() {
                    Timeout();
                });
            }
            if (r) {
                $(r).click(function(event) {
                    event.preventDefault();
                    clearTimeout(timeOut);
                    current++;
                    start(current);
                }).mouseout(function() {
                    Timeout();
                });
            }
            start();
            overout($(options.item, a));

            if (options.sync) {
                overout($(options.item, s));
            }
            if (b) {
                $(options.item, b).each(function(i) {
                    overout($(this), i);
                });
            }
            function Timeout() {
                timeOut = setTimeout(function() {
                    current++;
                    // console.log(current);
                    start(current);
                }, options.time);
            }
            function overout(that, i) {
                that.mouseover(function() {
                    clearTimeout(timeOut);
                    if (typeof i !== "undefined") {
                        show(i);
                    }
                }).mouseout(function() {
                    Timeout();
                })
            }
            function start() {
                show(current);
                Timeout();
            }
            function show(i) {
                if (i >= len) {
                    i = 0;
                }
                if (i < 0) {
                    i = len - 1;
                }
                current = i;
                if (options.sync) {
                    $(options.item, s).hide().eq(i).fadeIn();
                }
                $(options.item, a).hide().eq(i).fadeIn();

                if (b) {
                    $(options.item, b)
                    .removeClass(options.classname)
                    .eq(i)
                    .addClass(options.classname);
                }
            }
        });
    }
})(jQuery);

(function($) {
    $.fn.tabs = function(options) {
        var defaults = {
            item: ".tabs-pane",
            action: 'mouseover'
        }
        var options = $.extend(defaults, options);
        var container = $(this);
        $('[data-toggle="tab"]', container).each(function(i) {
            if (options.action == 'click') {
                $(this).click(function(event) {
                    event.preventDefault();
                    show(this)
                });
            }
            if (options.action == 'mouseover') {
                $(this).mouseover(function(event) {
                    event.preventDefault();
                    show(this)
                });
            }
        });

        function show(that) {
            var a = $(that)
              , target = a.attr('date-target');
            $(options.item, container).hide();
            $(target, container).show();
            $('[data-toggle="tab"]', container).parent().removeClass('active');
            a.parent().addClass('active');
        }
    }
})(jQuery);
/*!
 * Lazy Load - jQuery plugin for lazy loading images
 *
 */
(function($) {
    $.fn.lazyload = function(b) {
        var c = {
            attr: "data-src",
            container: $(window),
            callback: $.noop
        };
        var d = $.extend({}, c, b || {});
        d.cache = [];
        $(this).each(function() {
            var h = this.nodeName.toLowerCase()
              , g = $(this).attr(d.attr);
            var i = {
                obj: $(this),
                tag: h,
                url: g
            };
            d.cache.push(i)
        });
        var f = function(g) {
            if ($.isFunction(d.callback)) {
                d.callback.call(g.get(0))
            }
        }
        ;
        var e = function() {
            var g = d.container.height();
            if ($(window).get(0) === window) {
                contop = $(window).scrollTop()
            } else {
                contop = d.container.offset().top
            }
            $.each(d.cache, function(m, n) {
                var p = n.obj, j = n.tag, k = n.url, l, h;
                if (p) {
                    l = p.offset().top - contop,
                    l + p.height();
                    if ((l >= 0 && l < g) || (h > 0 && h <= g)) {
                        if (k) {
                            if (j === "img") {
                                f(p.attr("src", k))
                            } else {
                                p.load(k, {}, function() {
                                    f(p)
                                })
                            }
                        } else {
                            f(p)
                        }
                        n.obj = null
                    }
                }
            })
        }
        ;
        e();
        d.container.bind("scroll", e)
    }
})(jQuery);
/*!
 * Bootstrap v3.3.7 (http://getbootstrap.com)
 * Copyright 2011-2016 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 */

/*!
 * Generated using the Bootstrap Customizer (http://getbootstrap.com/customize/?id=a556e738720fd8ef0772443b15557dd7)
 * Config saved to config.json and https://gist.github.com/a556e738720fd8ef0772443b15557dd7
 */
+function(t){"use strict";function e(e){var r=e.attr("data-target");r||(r=e.attr("href"),r=r&&/#[A-Za-z]/.test(r)&&r.replace(/.*(?=#[^\s]*$)/,""));var o=r&&t(r);return o&&o.length?o:e.parent()}function r(r){r&&3===r.which||(t(n).remove(),t(a).each(function(){var o=t(this),n=e(o),a={relatedTarget:this};n.hasClass("open")&&(r&&"click"==r.type&&/input|textarea/i.test(r.target.tagName)&&t.contains(n[0],r.target)||(n.trigger(r=t.Event("hide.bs.dropdown",a)),r.isDefaultPrevented()||(o.attr("aria-expanded","false"),n.removeClass("open").trigger(t.Event("hidden.bs.dropdown",a)))))}))}function o(e){return this.each(function(){var r=t(this),o=r.data("bs.dropdown");o||r.data("bs.dropdown",o=new i(this)),"string"==typeof e&&o[e].call(r)})}var n=".dropdown-backdrop",a='[data-toggle="dropdown"]',i=function(e){t(e).on("click.bs.dropdown",this.toggle)};i.VERSION="3.3.7",i.prototype.toggle=function(o){var n=t(this);if(!n.is(".disabled, :disabled")){var a=e(n),i=a.hasClass("open");if(r(),!i){"ontouchstart"in document.documentElement&&!a.closest(".navbar-nav").length&&t(document.createElement("div")).addClass("dropdown-backdrop").insertAfter(t(this)).on("click",r);var d={relatedTarget:this};if(a.trigger(o=t.Event("show.bs.dropdown",d)),o.isDefaultPrevented())return;n.trigger("focus").attr("aria-expanded","true"),a.toggleClass("open").trigger(t.Event("shown.bs.dropdown",d))}return!1}},i.prototype.keydown=function(r){if(/(38|40|27|32)/.test(r.which)&&!/input|textarea/i.test(r.target.tagName)){var o=t(this);if(r.preventDefault(),r.stopPropagation(),!o.is(".disabled, :disabled")){var n=e(o),i=n.hasClass("open");if(!i&&27!=r.which||i&&27==r.which)return 27==r.which&&n.find(a).trigger("focus"),o.trigger("click");var d=" li:not(.disabled):visible a",s=n.find(".dropdown-menu"+d);if(s.length){var p=s.index(r.target);38==r.which&&p>0&&p--,40==r.which&&p<s.length-1&&p++,~p||(p=0),s.eq(p).trigger("focus")}}}};var d=t.fn.dropdown;t.fn.dropdown=o,t.fn.dropdown.Constructor=i,t.fn.dropdown.noConflict=function(){return t.fn.dropdown=d,this},t(document).on("click.bs.dropdown.data-api",r).on("click.bs.dropdown.data-api",".dropdown form",function(t){t.stopPropagation()}).on("click.bs.dropdown.data-api",a,i.prototype.toggle).on("keydown.bs.dropdown.data-api",a,i.prototype.keydown).on("keydown.bs.dropdown.data-api",".dropdown-menu",i.prototype.keydown)}(jQuery);
