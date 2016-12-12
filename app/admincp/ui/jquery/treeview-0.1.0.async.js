/*
 * Async Treeview 0.1 - Lazy-loading extension for Treeview
 *
 * http://bassistance.de/jquery-plugins/jquery-plugin-treeview/
 *
 * Copyright (c) 2007 Jörn Zaefferer
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 *
 * Revision: $Id: jquery.treeview.async.js 179 2013-03-29 03:21:28Z coolmoo $
 *
 */

;(function($) {
	function load(settings, root, child, container) {
		$.getJSON(settings.url, {root: root}, function(response) {
			$("#tree-loading").remove();
			function createNode(parent) {
				var html = template('tree_li', this.data);
				var current = $("<li/>")
				.attr("id", this.id)
				.html(html)
				.appendTo(parent)
				.mouseover(function() {
					$(this).css("background-color", "#E7E7E7");
				}).mouseout(function() {
					$(this).css("background-color", "#FFFFFF");
				});
				if (this.expanded) {
					current.addClass("open");
				}
				if (this.hasChildren || this.children && this.children.length) {
					var branch = $("<ul/>").appendTo(current);
					if (this.hasChildren) {
						current.addClass("hasChildren");
					}
					if (this.children && this.children.length) {
						$.each(this.children, createNode, [branch])
					}
				}
			}
			$.each(response, createNode, [child]);
			$(container).treeview({
				add: child
			});

			function update_ordernum (ui) {
				var ul = ui.item.parent();
				var ordernum = new Array();
				$(".ordernum > input", ul).each(function(i) {
					$(this).val(i);
					var cid = $(this).attr("cid");
					ordernum.push(cid);
				});
				$.post(upordurl, {
					ordernum: ordernum
				});
			}
			if (settings.sortable) {
				$(container).sortable({
					delay: 300,
					helper: "clone",
					placeholder: "ui-state-highlight",
					start: function(event, ui) {
						$(ui.item).show().css({
							'opacity': 0.5
						});
					},
					stop: function(event, ui) {
						$(ui.item).css({
							'opacity': 1
						});
						update_ordernum (ui);
					}
				}).disableSelection();
			}
		});
	}

	var proxied = $.fn.treeview;
	$.fn.treeview = function(settings) {
		if (!settings.url) {
			return proxied.apply(this, arguments);
		}
		var container = this;
		load(settings,0,this, container);
		var userToggle = settings.toggle;
		return proxied.call(this, $.extend({}, settings, {
			collapsed: true,
			toggle: function() {
				var $this = $(this);
				if ($this.hasClass("hasChildren")) {
					var childList = $this.removeClass("hasChildren").find("ul");
					childList.empty();
					load(settings, this.id, childList, container);
				}
				if (userToggle) {
					userToggle.apply(this, arguments);
				}
			}
		}));
	};

})(jQuery);
