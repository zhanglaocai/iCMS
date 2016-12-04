<?php /**
* @package iCMS
* @copyright 2007-2010, iDreamSoft
* @license http://www.idreamsoft.com iDreamSoft
* @author coolmoo <idreamsoft@qq.com>
* @$Id: apps.add.php 2365 2014-02-23 16:26:27Z coolmoo $
*/
defined('iPHP') OR exit('What are you doing?');
admincp::head();
?>
<style type="text/css">
#field-default .add-on { width: 70px;text-align: right; }
.iCMS_dialog .ui-dialog-content .chosen-container{position: relative;}
</style>
<link rel="stylesheet" href="./app/admincp/ui/formbuilder.css" type="text/css" />
<script type="text/javascript" src="./app/admincp/ui/jquery/jquery-ui.min.js"></script>
<div class="iCMS-container">
  <div class="widget-box">
    <div class="widget-title"> <span class="icon"> <i class="fa fa-pencil"></i> </span>
    <h5 class="brs"><?php echo empty($this->id)?'添加':'修改' ; ?>应用</h5>
    <ul class="nav nav-tabs" id="apps-add-tab">
      <li><a href="#apps-add-base" data-toggle="tab"><i class="fa fa-info-circle"></i> 基本信息</a></li>
      <li><a href="#apps-add-field" data-toggle="tab"><i class="fa fa-cog"></i> 基础字段</a></li>
      <li class="active"><a href="#apps-add-custom" data-toggle="tab"><i class="fa fa-cog"></i> 自定义字段</a></li>
    </ul>
  </div>
  <div class="widget-content nopadding">
    <form action="<?php echo APP_FURI; ?>&do=save" method="post" class="form-inline" id="iCMS-apps" target="iPHP_FRAME">
      <input name="id" type="hidden" value="<?php echo $this->id; ?>" />
      <div id="apps-add" class="tab-content">
        <div id="apps-add-base" class="tab-pane ">
          <div class="input-prepend"> <span class="add-on">应用名称</span>
          <input type="text" name="title" class="span3" id="title" value="<?php echo $rs['title'] ; ?>"/>
        </div>
        <span class="help-inline">应用中文名称</span>
        <div class="clearfloat mb10"></div>
        <div class="input-prepend"> <span class="add-on">应用标识</span>
        <input type="text" name="name" class="span3" id="name" value="<?php echo $rs['name'] ; ?>"/>
      </div>
      <span class="help-inline">应用唯一标识</span>
      <div class="clearfloat mb10"></div>
      <div class="input-prepend" style="width:100%;"><span class="add-on">应用简介</span>
      <textarea name="description" id="description" class="span6" style="height: 150px;"><?php echo $rs['description'] ; ?></textarea>
    </div>
  </div>
  <div id="apps-add-field" class="tab-pane">
    <div id="field-default">
      <div id="field_id">
        <div class="input-prepend"> <span class="add-on">id</span>
        <span class="input-xlarge uneditable-input">INT(10) UNSIGNED NOT NULL</span>
      </div>
      <span class="help-inline">主键 自增ID</span>
    </div>
    <div class="clearfloat mb10"></div>
    <?php foreach ($BASE_FIELDS[1] as $key => $value) { ?>
    <div id="field_<?php echo $value; ?>">
      <div class="input-prepend"> <span class="add-on"><?php echo $value; ?></span>
      <span class="input-xlarge uneditable-input"><?php echo $BASE_FIELDS[2][$key]; ?></span>
    </div>
    <span class="help-inline"><?php echo $BASE_FIELDS[4][$key]; ?></span>
  </div>
  <div class="clearfloat mb10"></div>
  <?php } ?>
</div>
</div>
<div id="apps-add-custom" class="tab-pane active">
<div id="field-new">
  <?php
  //   var_dump($rs['field']);
  // if(is_array($rs['field'])){
  //   foreach ($rs['field'] as $fname => $value) {
  //     $fid   = 'field-'.$fname;
  //     echo $this->field_html($fid,$fname,$value);
  //   }
  // }
  ?>
</div>
<div class="row-fluid">
  <div class="span8">
    <ul id="custom_field_list">
    </ul>
  </div>
  <div class="span4">
    <div id="fields-container">
      <div class="compHeaderCont" style="margin:0px;">
        字段
      </div>
      <ul>
        <li i="fieldEl" fieldtype="input">
          <span class="fb-icon fb-icon-input"></span>
          <p>单行</p>
        </li>
        <li i="fieldEl" fieldtype="textarea">
          <span class="fb-icon fb-icon-textarea"></span>
          <p>多行</p>
        </li>
        <li i="fieldEl" fieldtype="4">
          <span class="emailIcon fb-icon fb-icon-Mail"></span>
          <p class="emailText">邮箱</p>
        </li>
        <li i="fieldEl" fieldtype="24">
          <span class="textAreaIcon fb-icon fb-icon-RichText"></span>
          <p class="richtextConent">富文本</p>
        </li>
        <li i="fieldEl" fieldtype="10">
          <span class="dateIcon fb-icon fb-icon-Date"></span>
          <p class="dateText">日期</p>
        </li>
        <li i="fieldEl" fieldtype="22">
          <span class="timeIcon fb-icon fb-icon-Date-Time"></span>
          <p class="datetimeText">日期时间</p>
        </li>
        <li i="fieldEl" fieldtype="1100">
          <span class="dropdownIcon fb-icon fb-icon-DropDown"></span>
          <p class="dropdown">下拉列表</p>
        </li>
        <li i="fieldEl" fieldtype="1101">
          <span class="radioIcon fb-icon fb-icon-Radio"></span>
          <p class="radioText">单选框</p>
        </li>
        <li i="fieldEl" fieldtype="102">
          <span class="multiselect fb-icon fb-icon-MultiSelect"></span>
          <p class="multiselectText">多选列表</p>
        </li>
        <li i="fieldEl" fieldtype="103">
          <span class="checkIcon fb-icon fb-icon-CheckBox"></span>
          <p class="checkText">复选框</p>
        </li>
        <li i="fieldEl" fieldtype="5">
          <span class="numberIcon fb-icon fb-icon-Number"></span>
          <p class="numberText">整数</p>
        </li>
        <li i="fieldEl" id="decimalIconnew" fieldtype="19">
          <span class="decimalIcon fb-icon fb-icon-Decimal"></span>
          <p class="percentText">小数</p>
        </li>
        <li i="fieldEl" id="decimalIcon" fieldtype="7">
          <span class="percentIcon fb-icon fb-icon-Percentage"></span>
          <p class="decimalText">百分比</p>
        </li>
        <li i="fieldEl" fieldtype="6">
          <span class="currencyIcon fb-icon fb-icon-Currency2"></span>
          <p class="currencyText">货币</p>
        </li>
        <li i="fieldEl" fieldtype="21">
          <span class="websiteIcon fb-icon fb-icon-Url"></span>
          <p class="urlText">Url</p>
        </li>
        <li i="fieldEl" id="imageIconnewicon" fieldtype="20">
          <span class="imageIcon fb-icon fb-icon-Image"></span>
          <p class="imageText">图片</p>
        </li>
        <li i="fieldEl" fieldtype="9">
          <span class="decisionbox fb-icon fb-icon-Deciton"></span>
          <p class="decisionboxText">选择框</p>
        </li>
        <li i="fieldEl" fieldtype="18">
          <span class="fileuploadIcon fb-icon fb-icon-FileUpload"></span>
          <p class="fileuploadText">上传文件</p>
        </li>
        <li i="fieldEl" fieldtype="32">
          <span class="lookupIcon fb-icon fb-icon-LookUp"></span>
          <p class="lookupConent">查找</p>
        </li>
        <li i="fieldEl" fieldtype="14">
          <span class="addnotesIcon fb-icon fb-icon-AddNotes"></span>
          <p class="addnotestext">添加备注</p>
        </li>
        <li i="fieldEl" fieldtype="99">
          <span class="subformIcon fb-icon fb-icon-SubForm"></span>
          <p class="subformText">子表单</p>
        </li>
        <li i="fieldEl" fieldtype="31">
          <span class="autonumberIcon fb-icon fb-icon-AutoNumber"></span>
          <p class="lookupConent">自动编号</p>
        </li>
        <li i="fieldEl" fieldtype="15">
          <span class="formulaIcon fb-icon fb-icon-Formula"></span>
          <p class="formulaText">公式</p>
        </li>
        <li i="fieldEl" fieldtype="36">
          <span class="signatureIcon fb-icon fb-icon-Signature"></span>
          <p class="lookupConent">签名 </p>
        </li>
        <li i="fieldEl" fieldtype="30">
          <span class="usersIcon fb-icon fb-icon-Name" style="margin-top:1px;"></span>
          <p class="lookupConent">用户</p>
        </li>
      </ul>
    </div>
  </div>
</div>
<div class="clearfloat mb30"></div>
<a href="javascript:;" class="btn btn-inverse addfield"/><i class="fa fa-plus"></i> 增加字段</a>
</div>
</div>
<div class="form-actions">
<button class="btn btn-primary" type="submit"><i class="fa fa-check"></i> 提交</button>
</div>
</form>
</div>
</div>
</div>
<script>
  $( function() {
    $( "#custom_field_list" ).sortable({
      placeholder: "ui-state-highlight",
      revert: true,
      helper: "clone",
      start:function(event, ui){
        console.log('start');
      },
      receive:function(event, ui){
        var fieldtype = $(ui.item).attr('fieldtype');
        console.log('receive',fieldtype);
      }
    });
    $( "[i='fieldEl']" ).draggable({
      connectToSortable: "#custom_field_list",
      helper: "clone",
      revert: "invalid",
      // start:function(event, ui){
      //   console.log('start');
      // },
      stop:function(event, ui){
        console.log('stop',event, ui);
      }
    });
    $("#custom_field_list,#field_type_list").disableSelection();
  } );
</script>
<div id="field_box" class="hide" style="width:600px;">
<form id="field_form">
<div class="input-prepend"> <span class="add-on">字段类型</span>
<select name="type" id="type" class="chosen-select" style="width:285px;" data-placeholder="请选择字段类型...">
<option></option>
<optgroup label="文字">
<option value='varchar' data-len="255">单行文本(最大255字节)</option>
<option value='multivarchar' data-len="10240">多行文本(最大10240字节)</option>
</optgroup>
<optgroup label="数字">
<option value='tinyint' data-len="1" data-default="0">数字[0-255]</option>
<option value='int' data-len="10" data-default="0">数字(最大10位数)</option>
<option value='bigint' data-len="20" data-default="0">超大数字(最大20位数)</option>
</optgroup>
<optgroup label="选项框">
<option value='radio'  data-len="6" data-dialog="1">单选</option>
<option value='checkbox' data-dialog="1">复选</option>
<option value='select' data-len="6" data-dialog="1">下拉</option>
<option value='multiselect' data-dialog="1">下拉多选</option>
</optgroup>
<optgroup label="功能">
<option value='prop'  data-len="255">属性</option>
<option value='image' data-len="255">单图片上传</option>
<option value='multimage' data-len="10240">多图片上传</option>
<option value='file' data-len="255">单文件上传</option>
<option value='multifile' data-len="10240">多文件上传</option>
<option value='time' data-len="10">时间</option>
<option value='seccode' data-len="4">验证码</option>
</optgroup>
<optgroup label="附加表">
<option value='text'>大文本</option>
<option value='mediumtext'>超大文本</option>
<option value='editor'>编辑器</option>
</optgroup>
</select>
</div>
<div class="clearfix"></div>
<div class="input-prepend"> <span class="add-on">字段名称</span>
<input type="text" name="label" class="span3" id="label" value=""/>
</div>
<span class="help-inline">* 必填</span>
<div class="input-prepend"> <span class="add-on">字 段 名</span>
<input type="text" name="fname" class="span3" id="fname" value=""/>
</div>
<span class="help-inline">* 必填</span>
<div class="input-prepend" style="display:none"> <span class="add-on">数据长度</span>
<input type="text" name="length" class="span3" id="length" value=""/>
</div>
<span class="help-inline" style="display:none"><i class="fa fa-question-circle"></i> 最大数据的长度,一个中文字算3个字节.</span>
<div class="input-prepend"> <span class="add-on">默 认 值</span>
<input type="text" name="default" class="span3" id="default" value=""/>
</div>
<span class="help-inline">可不填</span>
<div class="clearfix"></div>
<div class="input-prepend"> <span class="add-on">字段说明</span>
<input type="text" name="tip" class="span3" id="tip" value=""/>
</div>
<span class="help-inline">可不填</span>
<div class="input-prepend"> <span class="add-on">字段样式</span>
<input type="text" name="style" class="span3" id="style" value=""/>
</div>
<span class="help-inline">可不填</span>
<div class="clearfix"></div>
<div class="field-tab-box">
<ul class="nav nav-tabs" id="field-tab">
<li class="active"><a href="#field-tab-1" data-toggle="tab"><i class="fa fa-check-square-o"></i> 验证</a></li>
<li><a href="#field-tab-2" data-toggle="tab"><i class="fa fa-cog"></i> 数据处理</a></li>
<li><a href="#field-tab-3" data-toggle="tab"><i class="fa fa-info-circle"></i> 提示</a></li>
</ul>
<div class="tab-content">
<div id="field-tab-1" class="tab-pane active">
<div class="input-prepend"> <span class="add-on">数据验证</span>
<select name="validate[]" id="validate" class="chosen-select" style="width:360px;" data-placeholder="请选择数据验证方式..." multiple="multiple">
<option value=''>不验证</option>
<option value='empty'>不能为空</option>
<option value='num'>只能为数字</option>
<option value='len'>字数检测</option>
<option value='repeat'>检查重复</option>
<option value='email'>E-Mail地址</option>
<option value='url'>网址</option>
<option value='phone'>手机号码</option>
<option value='telphone'>联系电话</option>
</select>
</div>
<div class="clearfix"></div>
<div class="input-prepend input-append"> <span class="add-on">验证范围</span>
<span class="add-on">最小值</span>
<input type="text" name="validate_min" class="span1" id="validate_min" value=""/>
<span class="add-on">-</span>
<input type="text" name="validate_max" class="span1" id="validate_max" value=""/>
<span class="add-on">最大值</span>
</div>
</div>
<div id="field-tab-2" cla~ss="tab-pane">
<div class="input-prepend"> <span class="add-on">数据处理</span>
<select name="fun[]" id="fun" class="chosen-select" style="width:360px;" data-placeholder="请选择数据处理方式..." multiple="multiple">
<option value=''>不处理</option>
<option value='html'>清除HTML</option>
<option value='format'>格式化HTML</option>
<option value='pinyin'>拼音</option>
<option value='explode-n'>分割成数组(换行符)</option>
<option value='explode-c'>分割成数组(,)</option>
<option value='strtolower'>小写字母</option>
<option value='strtoupper'>大写字母</option>
<option value='rand'>随机数</option>
<option value='json_encode'>数组转json</option>
<option value='base64'>base64</option>
<option value='serialize'>数组序列化</option>
</select>
</div>
</div>
<div id="field-tab-3" class="tab-pane">
<span class="help-inline">支持bootstrap v2.3.2样式 或者请先定义css在填写样式名</span>
<div class="clearfix"></div>
<div class="input-prepend"> <span class="add-on">默认提示</span>
<input type="text" name="holder" class="span3" id="holder" value=""/>
</div>
<div class="input-prepend"> <span class="add-on">错误提示</span>
<input type="text" name="error" class="span3" id="error" value=""/>
</div>
<div class="input-prepend"> <span class="add-on">关联应用</span>
<input type="text" name="foreign" class="span3" id="foreign" value=""/>
</div>
</div>
</div>
</div>
</form>
</div>
<div class="hide" id="type_config_box">
<div class="input-prepend input-append">
<span class="add-on">选项</span>
<input type="text" name="option_name" class="span3" id="option_name" value=""/>
<span class="add-on">选项值</span>
<input type="text" name="option_value" class="span3" id="option_value" value=""/>
<a href="javascript:;" class="btn btn-primary add_option"/><i class="fa fa-plus"></i> 增加</a>
</div>
<hr />
<div class="input-prepend">
<span class="add-on">选项预览</span>
<select name="option_list" id="option_list" class="chosen-select" style="width:285px;" data-placeholder="请在上面添加选项...">
</select>
</div>
</div>
<script type="text/javascript">
$(function(){
  $("#field-new").on("click",'a[name="delete"]',function(event) {
    event.preventDefault();
    $(this).parent().parent().remove();
  });
  $("#field-new").on("click",'a[name="editor"]',function(event) {
    event.preventDefault();
    var p = $(this).parent(),field = $('[name="fields[]"]',p).val();
    var fieldArray = unserialize(field);
    console.log(fieldArray)
    for(key in fieldArray){
       console.log(key,fieldArray[key]);
       $("#"+key).val(fieldArray[key]);
    }
    // $.each(fieldArray, function() {
    //   console.log(this);
    //   // $("#"+n).val(v);
    // });
    field_dialog(true);
  });
  $("#type").change(function(event) {
    event.preventDefault();
    var len = $('option:selected',this).attr('data-len');
    var def = $('option:selected',this).attr('data-default');
    var dialog = $('option:selected',this).attr('data-dialog');
    if(len){
      $("#length").val(len);
    }
    $("#default").val(def);
    if(dialog){
      var type_config_box = document.getElementById("type_config_box");
      iCMS.dialog({
        id:'type-config',
        title: '添加选项',
        content:type_config_box,
        okValue: '添加',
        ok: function () {},
        cancelValue: '取消',
        cancel: function(){
          return true;
        }
      });
    }
  });
  $(".addfield").click(function(){
    event.preventDefault();
    field_dialog();
  });
})
function field_dialog(ed){
  var field_box = document.getElementById("field_box");
  return iCMS.dialog({
      id:'apps-field-dialog',
      title: 'iCMS - 字段设置',
      content:field_box,
      okValue: '添加',
      ok: function () {
        var type  = $("#type option:selected").val(),
        label = $("#label",field_box).val(),
        fname = $("#fname",field_box).val(),
        param = $("form",field_box).serialize(),
        fid   = 'field-'+fname,
        field = $('#'+fid);
        if(!type){
          iCMS.alert("请选择字段类型!");
          return false;
        }
        if(!label){
          iCMS.alert("请填写字段名称!");
          return false;
        }
        if(!fname){
          iCMS.alert("请填写字段名!");
          return false;
        }
        if(field.length){
          if(!ed){
            iCMS.alert("该字段名已经存在");
            return false;
          }
          $('[name="fname[]"]',field).text(fname);
          $('[name="fields[]"]',field).val(param);
        }else{
var html = '<?php echo $this->field_html(); ?>';
          html = html.replace('{fid}',fid)
          .replace('{fname}',fname)
          .replace('{param}',param);
          $('#field-new').append(html);
        }
        field_form_reset();
        return true;
      },
      cancelValue: '取消',
      cancel: function(){
        field_form_reset();
        return true;
      }
    });
}
function field_form_reset() {
  document.getElementById("field_form").reset();
  $(".chosen-select",'#field_form').trigger("chosen:updated");
}
function unserialize(query) {
  query = decodeURI(query);
  var params = Array(), h;
  var hash   = query.split('&');
  for (var i = 0; i < hash.length; i++) {
      h = hash[i].split("=");
      // console.log(h);
      if(h[0].indexOf("[]")=="-1"){
        params[h[0]] = h[1];
      }else{
        var key = h[0].replace('[]','');
        // params[key] = h[1];
        // console.log(key);
        if(!params[key]){
          params[key] = Array();
        }
        params[key].push(h[1]);
      }
  }
  return params;
}
</script>
<?php admincp::foot();?>
