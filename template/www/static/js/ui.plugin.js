/**
* iCMS - Intelligence Content Management System
* Copyright (c) 2007-2015 idreamsoft.com iiimon Inc. All rights reserved.
*
* @author coolmoo <idreamsoft@qq.com>
* @site http://www.idreamsoft.com
* @licence http://www.idreamsoft.com/license.php
* @version 6.1.0
*/
(function($){
	$.fn.slider = function(options){
		var defaults = {
			left_btn:'#slider-left',
			right_btn:'#slider-right',
			num_btn:"#slider-btn",
			classname:"active",
			item:"li",
			time:3000,
			sync:null
		}
		var options = $.extend(defaults, options);
		return this.each(function(){
			var a = $(this),
				b = $(options.num_btn),
				l = $(options.left_btn),
				r = $(options.right_btn),
				current = 0,
				timeOut = null,
				auto    = true,
				len     = $(options.item, a).length;

			if(options.sync){
				var s = $(options.sync);
			}
			if(l){
				$(l).click(function(event) {
					event.preventDefault();
					clearTimeout(timeOut);
					current--;
					show(current);
				}).mouseout(function() {
					Timeout();
				});
			}
			if(r){
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
			overout($(options.item,a));

			if(options.sync){
				overout($(options.item,s));
			}
			if(b){
				$(options.item,b).each(function(i) {
					overout($(this),i);
				});
			}
			function Timeout() {
				timeOut = setTimeout(function(){
					current++;
					// console.log(current);
					start(current);
				}, options.time);
			}
			function overout (that,i) {
				that.mouseover(function() {
					clearTimeout(timeOut);
					if(typeof i!=="undefined"){
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
				if(i>=len){
					i = 0;
				}
				if(i<0){
					i = len-1;
				}
				current = i;
				if(options.sync){
					$(options.item,s).hide().eq(i).fadeIn();
				}
				$(options.item,a).hide().eq(i).fadeIn();

				if(b){
					$(options.item,b)
						.removeClass(options.classname)
						.eq(i)
						.addClass(options.classname);
				}
			}
		});
	}
})(jQuery);

(function($){
    $.fn.tabs = function(options){
        var defaults = {
            item:".tabs-pane",
            action:'mouseover'
        }
        var options   = $.extend(defaults, options);
        var container = $(this);
		$('[data-toggle="tab"]',container).each(function(i) {
			if(options.action=='click'){
		        $(this).click(function(event) {
		           event.preventDefault();
		           show(this)
		        });
			}
			if(options.action=='mouseover'){
		        $(this).mouseover(function(event) {
		           event.preventDefault();
		           show(this)
		        });
			}
		});

		function show(that) {
           var a = $(that),target = a.attr('date-target');
           $(options.item,container).hide();
           $(target,container).show();
           $('[data-toggle="tab"]',container).parent().removeClass('active');
           a.parent().addClass('active');
        }
    }
})(jQuery);
/*!
 * Lazy Load - jQuery plugin for lazy loading images
 *
 */
(function(a){
    a.fn.lazyload=function(b){
    	var c={attr:"data-src",container:a(window),callback:a.noop};
    	var d=a.extend({},c,b||{});
    	d.cache=[];
    	a(this).each(function(){
    		var h=this.nodeName.toLowerCase(),g=a(this).attr(d.attr);
    		var i={obj:a(this),tag:h,url:g};
    		d.cache.push(i)
    	});
    	var f=function(g){
    		if(a.isFunction(d.callback)){
    			d.callback.call(g.get(0))
    		}
    	};
    	var e=function(){
    		var g=d.container.height();
    		if(a(window).get(0)===window){
    			contop=a(window).scrollTop()
    		}else{
    			contop=d.container.offset().top
    		}
    		a.each(d.cache,function(m,n){
    			var p=n.obj,j=n.tag,k=n.url,l,h;
    			if(p){
    				l=p.offset().top-contop,l+p.height();
    				if((l>=0&&l<g)||(h>0&&h<=g)){
	    				if(k){
	    					if(j==="img"){
	    						f(p.attr("src",k))
	    					}else{
	    						p.load(k,{},function(){f(p)})
	    					}
	    				}else{
	    					f(p)
	    				}
    					n.obj=null
    				}
    			}
    		})
    	};
    	e();
    	d.container.bind("scroll",e)
    }
})(jQuery);
