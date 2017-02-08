var iFormer = {
    ui:{
        class:'iFormer-ui',
        fbox:null
    },
    widget: function(t) {
        var tags = {
            input: '<input/>',
            textarea: '<textarea/>',
            btngroup: function (text) {
                var btngroup = iFormer.widget('div').addClass('btn-group btn-group-vertical');
                btngroup.append('<a class="btn dropdown-toggle">'+
                    ' <span class="caret"></span> '+text+'</a>');
                return btngroup;
            }
        };
        if(typeof(tags[t])==="function"){
            return tags[t];
        }
        if(tags[t]){
          return $(tags[t]);
        }else{
          return $('<'+t+'/>');
        }
    },
    render: function(helper,obj,data,origin) {
        var $container = this.widget('div').addClass(this.ui.class)
            $action    = this.widget('span').addClass('action'),
            $edit      = this.widget('a').addClass('fa fa-edit')
            $del       = this.widget('a').addClass('fa fa-trash-o');

        $action.append($edit,$del);

        if (obj['tag'] == 'br') {
            data = 'UI:BR';
            $container.addClass('pagebreak');
            $container.dblclick(function(event) {
                $container.remove();
            });
        }else{
            var $div  = this.widget('div').addClass('input-prepend'),
                $label= this.widget('span').addClass('add-on'),
                $help = this.widget('span').addClass('help-inline');

            switch (obj['tag']) {
                case 'multimage':
                case 'multifile':
                    var $elem = this.widget('textarea');
                case 'file':
                case 'image':
                case 'prop':
                    var tagText = {
                        prop:'选择属性',
                        image:'图片上传',
                        multimage:'多图上传',
                        file:'文件上传',
                        multifile:'批量上传',
                    }
                    var $elem = $elem||this.widget('input');
                    var handle2 = function () {
                        var btngroup = iFormer.widget('btngroup');
                        $div.addClass('input-append');
                        $div.append(btngroup(tagText[obj['tag']]));
                    }
                break;
                case 'seccode':
                    var $elem = iFormer.widget('input').addClass('seccode');
                    $elem.attr('maxlength',"4");
                    var handle2 = function () {
                        var span = iFormer.widget('span').addClass('add-on');
                        span.append('<img src="'+iCMS.config.API+'?do=seccode" alt="验证码" class="seccode-img r3">'+
                            '<a href="javascript:;" class="seccode-text">换一张</a>'
                        );
                        $div.addClass('input-append');
                        $div.append(span);
                    }

                break;
                case 'textarea':
                    var $elem = this.widget('textarea');
                    obj['class'] = "span6";
                    $elem.css('height','300px');
                break;
                case 'editor':
                    var $elem = this.widget('textarea').hide();
                    var handle2 = function () {
                        var img = iFormer.widget('img');
                        img.prop('src', './app/apps/ui/iFormer/img/editor.png');
                        $div.append(img);
                    }

                break;
                default:var $elem = this.widget(obj['tag']);
            }

            var elem_type  = obj['type']||'text';
            obj['class'] = obj['class']||'span3';

            switch (obj['type']) {
                case 'text':
                    if(obj['len']=="5120"){
                        obj['class'] = 'span6';
                    }
                break;
                case 'radio':
                case 'checkbox':
                    obj['class'] = 'checkbox';
                    var handle = function () {
                        var parent = iFormer.widget('span').addClass('add-on')
                        parent.append($elem);
                        $div.append($label,parent);
                    }
                break;
                case 'multiple':
                case 'select':
                    obj['class'] = 'chosen-select';
                    if(obj['type']=='multiple'){
                        $elem.attr('multiple',true);
                    }
                    elem_type  = null;
                    if(obj['value']){
                        console.log(obj['value']);
                    }
                break;
                case 'number':
                    if(obj['len']=="1"){
                        obj['class'] = 'span1';
                    }
                    if(obj['len']=="10"){
                        obj['class'] = 'span2';
                    }
                    elem_type  = 'text';
                break;
                case 'currency':
                case 'percentage':
                    var handle2 = function () {
                        var label2 = iFormer.widget('span').addClass('add-on');
                        label2.append(obj['label-after']);
                        $div.append(label2).addClass('input-append');
                    }
                    obj['class'] = 'span2';
                    elem_type  = 'text';
                break;
                case 'decimal':
                    obj['class'] = 'span2';
                    elem_type  = 'text';
                break;
            }

            $elem.attr({
                'id': obj['id'],
                'name': obj['name'],
                'type': elem_type,
                'class': obj['class'],
                'value': obj['default']|| '',
            });

            $label.text(obj['label']);
            $help.text(obj['help']);

            if(typeof(handle)==="function"){
                handle();
            }else{
                $div.append($label,$elem);
            }

            if(typeof(handle2)==="function"){
                handle2();
            }

            $container.append($div,$action,$help);
        }
        var $fdata = this.widget('input').prop({'type':'hidden','name':'fields[]'});
        if(origin){
            var $origin = this.widget('input').prop({
                'type':'hidden',
                'name':'origin['+origin+']',
                'value':obj['id']
            });
            $container.append($origin);
        }
        if(data){
            $fdata.val(data);
        }else{
            $fdata.val(this.urlEncode(obj));
        }

        $container.append($fdata);
        $(':checkbox,:radio',$container).uniform();

        $edit.click(function(event) {
            iFormer.edit($container);
        }).attr('href','javascript:;');

        $del.click(function(event) {
            $container.remove();
        }).attr('href','javascript:;');

        // $container.dblclick(function(event) {
        //     iFormer.edit($container);
        // });

        return $container;
    },

    urlEncode:function(param, key) {
      if(param==null) return '';

      var query = [],t = typeof (param);
      if (t == 'string' || t == 'number' || t == 'boolean') {
        query.push(key + '=' + encodeURIComponent(param));
      } else {
        for (var i in param) {
          var k = key == null ? i : key + (param instanceof Array ? '[' + i + ']' : '.' + i);
          var q = this.urlEncode(param[i], k);
          if(q!=='') query.push(q);
        }
      }
      return query.join('&');
    },
    urlDecode: function(query) {
        var args = [],pairs = query.split("&");
        for (var i = 0; i < pairs.length; i++) {
            var pos = pairs[i].indexOf('=');
            if (pos == -1) continue;
            var argname = pairs[i].substring(0, pos);
            argname = decodeURIComponent(argname);
            var value = pairs[i].substring(pos + 1);
            value = decodeURIComponent(value);

            if(argname.indexOf('[')!=-1){
              argname = argname.replace(/\[\d+\]/g, "[]");
              argname = argname.replace('[]', '');
              if(!args[argname]){
                args[argname] = [];
              }
              args[argname].push(value);
            }else{
              args[argname] = value;
            }
        };
        return args; // Return the object
    },
    callback: function(func,ret,param) {
        if (typeof(func) === "function") {
            func(ret,param);
        } else {
            var msg = ret;
            if (typeof(ret) === "object") {
                msg = ret.msg || 'error';
            }
            var UI = require("ui");
            UI.alert(msg);
        }
    },
    freset: function(a) {
        // a.reset();
        document.getElementById("field_form").reset();
        // $("#field_form",$(a))[0].reset();
        // $(".chosen-select", $(a)).trigger("chosen:updated");
        $(".chosen-select", $(a)).chosen("destroy");
    },
    edit: function($container) {
        // $container.dblclick(function(event) {
            event.preventDefault();
            var me = $(this),
            data   = $("[name='fields[]']",$container).val(),
            origin  = $("[name^='origin']",$container).val(),
            obj    = iFormer.urlDecode(data);
            // console.log(origin,obj,data);
            iFormer.edit_dialog(obj,
                function(param,qt) {
                    var render = iFormer.render($container,param,qt,origin);
                    $container.replaceWith(render);
                }
            );
        // });
    },
    edit_dialog: function(obj, callback) {
        var me = this;
        var fbox = document.getElementById("field_edit");
        $("select",$(fbox)).chosen(chosen_config);

        for(var i in obj) {
            $("#iFormer-"+i, fbox).val(obj[i]);
            if(typeof(obj[i])==='object'){
                $("#iFormer-"+i, fbox).trigger("chosen:updated");
            }
        }
        console.log(obj);
        if(obj['tag']=='select'||obj['type']=='radio'||obj['type']=='checkbox'||obj['type']=='select'||obj['type']=='multiple'){
            $("#iFormer-option-wrap", fbox).show();
            $("[name='option']").removeAttr('disabled');
        }else{
            $("#iFormer-option-wrap", fbox).hide();
            $("[name='option']").attr("disabled",true);
        }

        return iCMS.dialog({
            id: 'apps-field-dialog',
            title: 'iCMS - 表单字段设置',
            content: fbox,
            okValue: '确定',
            ok: function() {
                var data = $.extend(obj,{
                    'label': $("#iFormer-label", fbox).val(),
                    'name': $("#iFormer-name", fbox).val(),
                    'class': $("#iFormer-class", fbox).val(),
                    'help': $("#iFormer-help", fbox).val(),
                    'default': $("#iFormer-default", fbox).val()
                });
                if(data['id']!= data['name']){
                    data['id'] = data['name'];
                    $("#iFormer-id", fbox).val(data['id']);
                }
                param = $("form", fbox).serialize();
                // param = $("form", fbox).serializeArray();
// console.log(param);

                if(!data.label){
                  iCMS.alert("请填写字段名称!");
                  return false;
                }
                if(!data.name){
                  iCMS.alert("请填写字段名!");
                  return false;
                }
                if(obj['type']!='radio' &&obj['type']!='checkbox'){
                    var dname = $('[name="'+data.name+'"]',"#custom_field_list").not('#'+obj.id);
                    if(dname.length){
                        iCMS.alert("该字段名已经存在,请重新填写");
                        return false;
                    }
                }
                callback(data,param);
                me.freset(fbox);
                return true;
            },
            cancelValue: '取消',
            cancel: function() {
                me.freset(fbox);
                return true;
            }
        });
    }
};
$(function() {
    $("#custom_field_list").sortable({
        placeholder: "ui-state-highlight",
        cancel: ".clearfloat",
        delay:100,
        // helper: "clone",
        // start: function(event, ui) {
        //     $(ui.item).show().css({
        //         'opacity': 0.5
        //     });
        // },
        // stop: function(event, ui) {
        //     $(ui.item).css({
        //         'opacity': 1
        //     });
        // },
        sort: function( event, ui ) {
            var target = $(event.target);
            $(".clearfloat",target).remove();
            target.append('<div class="clearfloat"></div>');
        },
        receive: function(event, ui) {
            var helper = ui.helper,
            tag        = helper.attr('tag'),
            field      = helper.attr('field'),
            type       = helper.attr('type'),
            label      = helper.attr('label'),
            after      = helper.attr('label-after'),
            len        = helper.attr('len'),
            id         = iCMS.random(6, true);
            var html   = iFormer.render(helper,{
                'id': id,
                'label': (label || '表单') + id,
                'label-after':after,
                'field': field,
                'name': id,
                'tag': tag,
                'default': '',
                'type': type,
                'len': len
            });
            helper.replaceWith(html);
        }
    });
    $("[i='layout'],[i='field']").draggable({
        placeholder: "ui-state-highlight",
        connectToSortable: "#custom_field_list",
        helper: "clone",
        revert: "invalid",
    });
    $("#custom_field_list,.fields-container").disableSelection();
});
