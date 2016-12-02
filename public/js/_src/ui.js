define("ui",{
        widget:{
             'ucard':['<div class="tip_info">',
                    '<img src="'+require("config").PUBLIC+'/ui/loading.gif">',
                    '<span> 用户信息加载中……</span>',
                    '</div>'].join('')
        },

        dialog: function(opts) {
            var dialog = require("ui.dialog");
            return dialog(opts);
        },
        alert: function(msg, ok, callback) {
            var dialog = require("ui.dialog");
            // require(['dialog'], function(d) {
                var opts = ok ? {
                    label: 'success',
                    icon: 'check'
                } : {
                    label: 'warning',
                    icon: 'warning'
                }
                opts.id         = 'iCMS-DIALOG-ALERT';
                opts.skin       = 'iCMS_dialog_alert'
                opts.content    = msg;
                opts.time       = 3000;
                opts.lock       = true;
                // opts.quickClose = false;
                dialog(opts, callback);
            // });
        },
        /**
         * [seccode 验证码刷新]
         * @param  {[type]} a [验证码]
         * @param  {[type]} b [容器]
         */
        seccode:function(a, b) {
            var API = require("api"),
            a = a||'.seccode-img',
            b = b||'body';
            $(a, b).attr('src', API.url('public', '&do=seccode&') + Math.random());
        },
        imgFix: function(im, x, y) {
            x = x || 99999
            y = y || 99999
            im.removeAttribute("width");
            im.removeAttribute("height");
            if (im.width / im.height > x / y && im.width > x) {
                im.height = im.height * (x / im.width)
                im.width = x
                // im.parentNode.style.height = im.height * (x / im.width) + 'px'
            } else if (im.width / im.height <= x / y && im.height > y) {
                im.width = im.width * (y / im.height)
                im.height = y
                // im.parentNode.style.height = y + 'px'
            }
        }
});
