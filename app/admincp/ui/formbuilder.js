var iFormer = {
    ui:{
        class:'iFormer-ui'
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
    render: function(obj,data) {
        var $container = this.widget('div').addClass(this.ui.class);
        var $fdata     = this.widget('input').prop({'type':'hidden','name':'data[]'});


        if (obj['tag'] == 'br') {
            $container.addClass('pagebreak');
            $fdata.val('br');
            $container.append($fdata);
            return $container;
        }
        var $div = this.widget('div').addClass('input-prepend'),
        $label   = this.widget('span').addClass('add-on'),
        $help    = this.widget('span').addClass('help-inline'),
        $elem    = this.widget(obj['tag']);
console.log(obj);

        obj['class'] = obj['class']||'span3';

        $elem.attr({
            'id': obj['id'],
            'name': obj['name'],
            'type': obj['type'] || 'text',
            'class': obj['class'],
            'value': obj['value'] || ''
        });

        $label.text(obj['label']);
        $help.text(obj['help']);

        // $elem.prop(obj)
        $div.append($label);
        $div.append($elem);

        $container.append($div);
        $container.append($help);

        if(data){
            $fdata.val(data);
        }else{
            $fdata.val(this.urlEncode(obj));
        }

        $container.append($fdata);

        iFormer.edit($container);

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
        $container.dblclick(function(event) {
            event.preventDefault();
            var me   = $(this);
            var data = $("[name='data[]']",$container).val();
            var obj  = iFormer.urlDecode(data);
            iFormer.edit_dialog(obj, function(param,qt) {
                var render = iFormer.render(param,qt);
                $container.replaceWith(render);
            });
        });
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
                func(data,param);
                me.freset(fbox);
                return true;


                // if(field.length){
                //   if(!ed){
                //     iCMS.alert("该字段名已经存在");
                //     return false;
                //   }
                //   $('[name="fname[]"]',field).text(fname);
                //   $('[name="fields[]"]',field).val(param);
                // }else{
                // var html = '<?php echo $this->field_html(); ?>';
                // html = html.replace('{fid}',fid)
                // .replace('{fname}',fname)
                // .replace('{param}',param);
                // $('#field-new').append(html);
                // }
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
        start:function(event, ui) {

        },
        stop:function(event, ui) {
            var target = $(event.target);
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
            var html   = iFormer.render({
                'id': id,
                'label': (label || '表单') + id,
                'field': field,
                'name': id,
                'tag': tag,
                'type': type,
                'len': len
            });
            helper.replaceWith(html);
            var target = $(event.target);
            $(".clearfloat",target).remove();
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
