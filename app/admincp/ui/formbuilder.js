var iFormer ={
  // element:{
      //   input:'<input/>',
  //   textarea:'<textarea/>',
  //   // input:function (type) {
  //   //   type = type||'text';
  //   //   // button
  //   //   // checkbox
  //   //   // file
  //   //   // hidden
  //   //   // image
  //   //   // password
  //   //   // radio
  //   //   // reset
  //   //   // submit
  //   //   // text
      //   //   return '<input type="'+type+'"/>'
  //   // },
  //   // textarea:function(){
  //   //   return '<textarea/>';
  //   // }
  // },
  ui:function (t) {
    var tags = {
          input:'<input/>',
      textarea:'<textarea/>'
    };
    return $(tags[t]);
  },
  render:function (a) {
    if(a.field=='br'){
          return $('<div class="clearfloat"></div>');
    }
    var $div = $('<div class="input-prepend"></div>'),
    $label   = $('<label class="add-on"></label>'),
    $help   = $('<span class="help-inline">asdasdasd</span>'),
    $ui     = this.ui(a.ui);

    $label.text(a.label);
    a.type  = a.type||'text'
    a.class = a.class||'span6'
    a.value = a.value||''
    $ele.prop(a)
    $div.append($label);
    $div.append($ui);
    // $div.push($help);

    $div.dblclick(function(event) {
      event.preventDefault();
      var me = this;
      iFormer.field.edit(a,function(ui){
          ui.name  = 'iformer['+ui.id+']';
          ui.field = a.field;
          var ndiv  = iFormer.render(ui);
          $div.replaceWith(ndiv);
      });
    });
    return $div;
  },
  unserialize:function(query) {
      var pairs = query.split("&");                 // Break at ampersand
      for(var i = 0; i < pairs.length; i++) {
          var pos = pairs[i].indexOf('=');          // Look for "name=value"
          if (pos == -1) continue;                  // If not found, skip
          var argname = pairs[i].substring(0,pos);  // Extract the name
          var value = pairs[i].substring(pos+1);    // Extract the value
          value = decodeURIComponent(value);        // Decode it, if needed
          args[argname] = value;                    // Store as a property
      }
      return args;                                  // Return the object
  },
  field:{
    freset:function(a) {
      // a.reset();
      document.getElementById("field_form").reset();
      // $("#field_form",$(a))[0].reset();
      $(".chosen-select",$(a)).trigger("chosen:updated");
    },
    edit:function (data,callback){
      var me = this;
      var fbox = document.getElementById("field_box");
      me.freset(fbox);

      $("#label",fbox).val(data.label);
      $("#id",fbox).val(data.id);
      $("#class",fbox).val(data.class);
      $("#value",fbox).val(data.value);

      return iCMS.dialog({
          id:'apps-field-dialog',
          title: 'iCMS - 字段设置',
          content:fbox,
          okValue: '确定',
          ok: function () {
            var ui = {
              'label':$("#label",fbox).val(),
              'id':$("#id",fbox).val(),
              'class':$("#class",fbox).val(),
              'value':$("#value",fbox).val()
            }
            param = $("form",fbox).serialize();

            // if(!label){
            //   iCMS.alert("请填写字段名称!");
            //   return false;
            // }
            // if(!fname){
            //   iCMS.alert("请填写字段名!");
            //   return false;
            // }
            console.log(param);
            callback(ui);
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
            me.freset(fbox);
            return true;
          },
          cancelValue: '取消',
          cancel: function(){
            me.freset(fbox);
            return true;
          }
      });
    }
  }
};
$( function() {
  $( "#custom_field_list" ).sortable({
    placeholder: "ui-state-highlight",
    receive:function(event, ui){
      var helper = ui.helper;
      var ui     = helper.attr('ui');
      var field  = helper.attr('field');
      var type   = helper.attr('type');
      var label  = helper.attr('label');
      var id     = iCMS.random(6,true);
      var html   = iFormer.render({
        'id':id,
        'label':(label||'表单')+id,
        'name':'iformer['+id+']',
        'ui':ui,
        'type':type,
        'class':'span6',
      });
      helper.replaceWith(html);
    }
  });
  $( "[i='layout'],[i='field']").draggable({
    connectToSortable: "#custom_field_list",
    helper: "clone",
    revert: "invalid",
  });
  $("#custom_field_list,.fields-container").disableSelection();
});
