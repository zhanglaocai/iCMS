var iFormer = {
    ui:{
        class:'iFormer-ui',
        fbox:null
    },
    widget: function(t) {
        var tags = {
            input: '<input/>',
            textarea: '<textarea/>'
        };
        if(tags[t]){
          return $(tags[t]);
        }else{
          return $('<'+t+'/>');
        }
    },
    render: function(helper,obj,data) {
        var $container = this.widget('div').addClass(this.ui.class)
            $fdata     = this.widget('input').prop({'type':'hidden','name':'fields[]'}),
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
                $help = this.widget('span').addClass('help-inline'),
                // $span = this.widget('span'),
                $elem = this.widget(obj['tag']);

            obj['type']  = obj['type']||'text';
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
                    var handle = function (div,label,elem) {
                        var parent = iFormer.widget('span').addClass('add-on')
                        parent.append(elem);
                        div.append(label,parent);
                        return div;
                    }
                break;
                case 'multiple':
                case 'select':
                    obj['class'] = 'chosen-select';
                    if(obj['type']=='multiple'){
                        $elem.attr('multiple',true);
                    }
                    obj['type']  = null;
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
                    obj['type']  = 'text';
                break;
                case 'currency2':
                case 'percentage':
                    var handle = function (div,label,elem) {
                        var text = helper.attr('label-after');
                        var label2 = iFormer.widget('span').addClass('add-on');
                        label2.append(text);
                        $div.append($label,$elem,label2).addClass('input-append');
                        return div;
                    }
                    obj['class'] = 'span2';
                    obj['type']  = 'text';
                break;
                case 'decimal':
                    obj['class'] = 'span2';
                    obj['type']  = 'text';
                break;
            }

            $elem.attr({
                'id': obj['id'],
                'name': obj['name'],
                'type': obj['type'],
                'class': obj['class'],
                'value': obj['value']|| '',
            });

            $label.text(obj['label']);
            $help.text(obj['help']);

            if(typeof(handle)!=="undefined"){
                $div = handle($div,$label,$elem);
            }else{
                $div.append($label,$elem);
            }
            $container.append($div,$action,$help);
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
          query.push(q);
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

            if(argname.indexOf('[]')!=-1){
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
        $(".chosen-select", $(a)).trigger("chosen:updated");
    },
    edit: function($container) {
        // $container.dblclick(function(event) {
            event.preventDefault();
            var me   = $(this);
            var data = $("[name='data[]']",$container).val();
            var obj  = iFormer.urlDecode(data);
            iFormer.edit_dialog(obj, function(param,qt) {
                var render = iFormer.render($container,param,qt);
                $container.replaceWith(render);
            });
        // });
    },
    edit_dialog: function(obj, func) {
        var me = this;
        var fbox = document.getElementById("field_box");

        for(var i in obj) {
            $("#iFormer-"+i, fbox).val(obj[i]);
            if(typeof(obj[i])==='object'){
                $("#iFormer-"+i, fbox).trigger("chosen:updated");
            }
        }
        var vs_tag = 'text';
        var vh_tag = 'select';
        if(obj['tag']=='select'){
            var vs_tag = 'select';
            var vh_tag = 'text';
        }
        var vs = $("#iFormer-value-"+vs_tag, fbox).show();
        $("[name='value']",vs).removeAttr('disabled').attr('id','iFormer-value');
        var vh = $("#iFormer-value-"+vh_tag, fbox).hide();
        $("[name='value']",vh).removeAttr('id').attr("disabled",true);


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
                    'value': $("#iFormer-value", fbox).val()
                });

                param = $("form", fbox).serialize();

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
                func(data,param);
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
            len        = helper.attr('len'),
            id         = iCMS.random(6, true);
            var html   = iFormer.render(helper,{
                'id': id,
                'label': (label || '表单') + id,
                'field': field,
                'name': id,
                'tag': tag,
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
