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
<script type="text/javascript" src="./app/admincp/ui/jquery/jquery-ui.min.js"></script>
<script type="text/javascript" src="./app/admincp/ui/iFormer/iFormer.js"></script>
<link rel="stylesheet" href="./app/admincp/ui/iFormer/iFormer.css" type="text/css" />
<div class="iCMS-container">
  <div class="widget-box">
    <div class="widget-title">
      <span class="icon"> <i class="fa fa-pencil"></i> </span>
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
            <div class="input-prepend">
              <span class="add-on">应用名称</span>
              <input type="text" name="title" class="span3" id="title" value="<?php echo $rs['title'] ; ?>"/>
            </div>
            <span class="help-inline">应用中文名称</span>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend">
              <span class="add-on">应用标识</span>
              <input type="text" name="name" class="span3" id="name" value="<?php echo $rs['name'] ; ?>"/>
            </div>
            <span class="help-inline">应用唯一标识</span>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend" style="width:100%;">
              <span class="add-on">应用简介</span>
              <textarea name="description" id="description" class="span6" style="height: 150px;"><?php echo $rs['description'] ; ?></textarea>
            </div>
          </div>
          <div id="apps-add-field" class="tab-pane">
            <div id="field-default">
              <div id="field_id">
                <div class="input-prepend">
                  <span class="add-on">id</span>
                  <span class="input-xlarge uneditable-input">INT(10) UNSIGNED NOT NULL</span>
                </div>
                <span class="help-inline">主键 自增ID</span>
              </div>
              <div class="clearfloat mb10"></div>
              <?php foreach ($BASE_FIELDS[1] as $key => $value) { ?>
              <div id="field_<?php echo $value; ?>">
                <div class="input-prepend">
                  <span class="add-on"><?php echo $value; ?></span>
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
            <div class="fields-fluid">
              <div class="col-left">
                <ul id="custom_field_list">
                </ul>
                <div class="clearfloat"></div>
              </div>
              <div class="col-right">
                <div class="fields-container">
                  <div class="widget-title">
                    <span class="icon"> <i class="fa fa-cog"></i> </span>
                    <h5 class="brs">字段</h5>
                    <ul class="nav nav-tabs" id="fields-tab">
                      <li class="active"><a href="#fields-base" data-toggle="tab"><i class="fa fa-info-circle"></i> 基础字段</a></li>
                      <li><a href="#fields-func" data-toggle="tab"><i class="fa fa-cog"></i> 功能字段</a></li>
                      <li><a href="#fields-addons" data-toggle="tab"><i class="fa fa-cog"></i> 附加字段</a></li>
                    </ul>
                  </div>

                  <div id="fields-tab-content" class="tab-content">
                    <div id="fields-base" class="tab-pane active">
                      <ul>
                        <li i="layout" tag="br" class="br">
                          <span class="fa fa-arrows-h"></span>
                          <p>换行符</p>
                        </li>
                        <li i="field" tag="input" field="VARCHAR" len="255">
                          <span class="fb-icon fb-icon-input"></span>
                          <p>单行</p>
                        </li>
                        <li i="field" tag="input" field="VARCHAR" len="5120">
                          <span class="fb-icon fb-icon-input"></span>
                          <p>单行长文本</p>
                        </li>
                        <li i="field" tag="textarea" field="TEXT">
                          <span class="fb-icon fb-icon-textarea"></span>
                          <p>多行</p>
                        </li>

                        <li i="field" tag="input" field="VARCHAR" len="255" label="邮箱">
                          <span class="fb-icon fb-icon-mail"></span>
                          <p>邮箱</p>
                        </li>
                        <li i="field" tag="input" field="INT" len="10" label="日期">
                          <span class="fb-icon fb-icon-date"></span>
                          <p>日期</p>
                        </li>
                        <li i="field" tag="input" field="INT" len="10" label="时间">
                          <span class="timeIcon fb-icon fb-icon-datetime"></span>
                          <p>日期时间</p>
                        </li>
                        <li i="field" tag="input" type="radio" field="TINYINT" len="1" label="单选">
                          <span class="fb-icon fb-icon-radio"></span>
                          <p>单选框</p>
                        </li>
                        <li i="field" tag="input" type="checkbox" field="TINYINT" len="1" label="复选">
                          <span class="fb-icon fb-icon-checkbox"></span>
                          <p>复选框</p>
                        </li>
                        <li i="field" tag="select" field="TINYINT" len="1" label="列表">
                          <span class="fb-icon fb-icon-dropdown"></span>
                          <p>下拉列表</p>
                        </li>
                        <li i="field" tag="select" type="multiple" field="VARCHAR" len="255" label="多选">
                          <span class="multiselect fb-icon fb-icon-multiselect"></span>
                          <p>多选列表</p>
                        </li>
                        <li i="field" tag="input" field="TINYINT" len="1" label="数字">
                          <span class="fb-icon fb-icon-number"></span>
                          <p>数字</p>
                        </li>
                        <li i="field" tag="input" field="INT" len="10" label="数字">
                          <span class="fb-icon fb-icon-number"></span>
                          <p>数字</p>
                        </li>
                        <li i="field" tag="input" field="BIGINT" len="20" label="数字">
                          <span class="fb-icon fb-icon-number"></span>
                          <p>超大数字</p>
                        </li>
                        <li i="field" tag="input" field="DECIMAL" len="6,2" label="小数">
                          <span class="fb-icon fb-icon-decimal"></span>
                          <p>小数</p>
                        </li>
                        <li i="field" tag="input" field="DECIMAL" len="3,2" label="百分比" label-after="%">
                          <span class="fb-icon fb-icon-percentage"></span>
                          <p>百分比</p>
                        </li>
                        <li i="field" tag="input" field="INT" len="10" label="货币" label-after="¥">
                          <span class="fb-icon fb-icon-currency2"></span>
                          <p>货币</p>
                        </li>
                        <li i="field" tag="input" field="VARCHAR" len="255" label="链接">
                          <span class="fb-icon fb-icon-url"></span>
                          <p>Url</p>
                        </li>

<!--                         <li i="field" fieldtype="32">
                          <span class="lookupIcon fb-icon fb-icon-lookup"></span>
                          <p class="lookupConent">查找</p>
                        </li>
                        <li i="field" fieldtype="14">
                          <span class="addnotesIcon fb-icon fb-icon-addnotes"></span>
                          <p class="addnotestext">添加备注</p>
                        </li>
                        <li i="field" fieldtype="99">
                          <span class="subformIcon fb-icon fb-icon-subform"></span>
                          <p class="subformText">子表单</p>
                        </li>
                        <li i="field" fieldtype="31">
                          <span class="autonumberIcon fb-icon fb-icon-autonumber"></span>
                          <p class="lookupConent">自动编号</p>
                        </li>
                        <li i="field" fieldtype="15">
                          <span class="formulaIcon fb-icon fb-icon-formula"></span>
                          <p class="formulaText">公式</p>
                        </li>
                        <li i="field" fieldtype="36">
                          <span class="signatureIcon fb-icon fb-icon-signature"></span>
                          <p class="lookupConent">签名 </p>
                        </li>
                        <li i="field" fieldtype="30">
                          <span class="usersIcon fb-icon fb-icon-name" style="margin-top:1px;"></span>
                          <p class="lookupConent">用户</p>
                        </li> -->
                      </ul>
                    </div>
                    <div id="fields-func" class="tab-pane">
                      <ul>
                        <li i="field" tag="dialog" field="VARCHAR" len="255" label="选择框">
                          <span class="fb-icon fb-icon-deciton"></span>
                          <p>选择框</p>
                        </li>

                        <li i="field" tag="image" field="VARCHAR" len="255" label="图片">
                          <span class="fb-icon fb-icon-image"></span>
                          <p>图片上传</p>
                        </li>
                        <li i="field" tag="multimage" field="text" label="多图">
                          <span class="fb-icon fb-icon-image"></span>
                          <p>多图上传</p>
                        </li>

                        <li i="field" tag="file" field="VARCHAR" len="255" label="上传">
                          <span class="fb-icon fb-icon-fileupload"></span>
                          <p>上传文件</p>
                        </li>
                        <li i="field" tag="multifile" field="text" label="批量上传">
                          <span class="fb-icon fb-icon-fileupload"></span>
                          <p>上传文件</p>
                        </li>

                        <li i="field" tag="prop" field="VARCHAR" len="255" label="属性">
                          <span class="fb-icon fb-icon-prop"></span>
                          <p>属性</p>
                        </li>
                        <li i="field" tag="seccode" label="验证码">
                          <span class="fb-icon fb-icon-url"></span>
                          <p>验证码</p>
                        </li>
                      </ul>
                    </div>
                    <div id="fields-addons" class="tab-pane">
                      <ul>
                        <li i="field" tag="textarea" field="MEDIUMTEXT">
                          <span class="fb-icon fb-icon-textarea"></span>
                          <p>超大文本</p>
                        </li>
                        <li i="field" tag="editor" field="MEDIUMTEXT">
                          <span class="fb-icon fb-icon-richtext"></span>
                          <p>编辑器</p>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="clearfloat"></div>
<div class="alert alert-info alert-block">
<h5><i class="fa fa-support"></i> 注意事项</h5>
<p><i class="fa fa-arrows-h"></i> 换行符 双击可删除</p>
<p><i class="fa fa-arrows-h"></i> 换行符 属于占位符 最终界面上将以10px空白替换.效果请参考文章添加页</p>

</div>
          <div class="form-actions">
            <button class="btn btn-primary btn-large" type="submit"><i class="fa fa-check"></i> 提交</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div id="field_box" class="hide" style="width:600px;">
    <form id="field_form">
      <input type="hidden" name="id" id="iFormer-id"/>
      <input type="hidden" name="tag" id="iFormer-tag"/>
      <input type="hidden" name="field" id="iFormer-field"/>
      <div class="input-prepend">
        <span class="add-on">字段名称</span>
        <input type="text" name="label" class="span3" id="iFormer-label" value=""/>
      </div>
      <span class="help-inline">* 必填</span>
      <div class="input-prepend">
        <span class="add-on">字&nbsp;&nbsp;段&nbsp;名</span>
        <input type="text" name="name" class="span3" id="iFormer-name" value=""/>
      </div>
      <span class="help-inline">* 必填</span>
      <div class="clearfix"></div>
      <div class="input-prepend">
        <span class="add-on">默&nbsp;&nbsp;认&nbsp;值</span>
        <input type="text" name="value" class="span3" id="iFormer-value" value=""/>
      </div>
      <span class="help-inline">选填</span>
      <div class="clearfix"></div>
      <div class="input-prepend">
        <span class="add-on">字段说明</span>
        <input type="text" name="help" class="span3" id="iFormer-help" value=""/>
      </div>
      <span class="help-inline">选填</span>
      <div class="input-prepend">
        <span class="add-on">字段样式</span>
        <input type="text" name="class" class="span3" id="iFormer-class" value=""/>
      </div>
      <span class="help-inline">选填</span>
      <div class="input-prepend">
        <span class="add-on">数据长度</span>
        <input type="text" name="len" class="span3" id="iFormer-len" value=""/>
      </div>
      <span class="help-inline">选填</span>

      <div class="clearfix"></div>
      <div class="field-tab-box">
        <ul class="nav nav-tabs" id="field-tab">
          <li class="active"><a href="#field-tab-1" data-toggle="tab"><i class="fa fa-check-square-o"></i> 验证</a></li>
          <li><a href="#field-tab-2" data-toggle="tab"><i class="fa fa-cog"></i> 数据处理</a></li>
          <li><a href="#field-tab-3" data-toggle="tab"><i class="fa fa-info-circle"></i> 提示</a></li>
        </ul>
        <div class="tab-content">
          <div id="field-tab-1" class="tab-pane active">
            <div class="input-prepend">
              <span class="add-on">数据验证</span>
              <select name="validate[]" id="iFormer-validate" class="chosen-select" style="width:360px;" data-placeholder="请选择数据验证方式..." multiple="multiple">
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
            <div class="input-prepend input-append">
              <span class="add-on">验证范围</span>
              <span class="add-on">最小值</span>
              <input type="text" name="validate_min" class="span1" id="iFormer-validate_min" value=""/>
              <span class="add-on">-</span>
              <input type="text" name="validate_max" class="span1" id="iFormer-validate_max" value=""/>
              <span class="add-on">最大值</span>
            </div>
          </div>
          <div id="field-tab-2" class="tab-pane">
            <div class="input-prepend">
              <span class="add-on">数据处理</span>
              <select name="fun[]" id="iFormer-fun" class="chosen-select" style="width:360px;" data-placeholder="请选择数据处理方式..." multiple="multiple">
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
            <div class="input-prepend">
              <span class="add-on">默认提示</span>
              <input type="text" name="holder" class="span3" id="iFormer-holder" value=""/>
            </div>
            <div class="input-prepend">
              <span class="add-on">错误提示</span>
              <input type="text" name="error" class="span3" id="iFormer-error" value=""/>
            </div>
            <div class="input-prepend">
              <span class="add-on">关联应用</span>
              <input type="text" name="app" class="span3" id="iFormer-app" value=""/>
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
  //{handle: ".widget-title"}
  $( ".fields-container" ).draggable();

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
</script>
  <?php admincp::foot();?>
