<?php /**
* @package iCMS
* @copyright 2007-2017, iDreamSoft
* @license http://www.idreamsoft.com iDreamSoft
* @author coolmoo <idreamsoft@qq.com>
*/
defined('iPHP') OR exit('What are you doing?');
admincp::head();
?>
<style type="text/css">
#field-default .add-on { width: 70px;text-align: right; }
.iCMS_dialog .ui-dialog-content .chosen-container{position: relative;}
</style>

<script type="text/javascript" src="./app/admincp/ui/jquery/jquery-ui.min.js"></script>
<script type="text/javascript" src="./app/apps/ui/iFormer/iFormer.js"></script>
<link rel="stylesheet" href="./app/apps/ui/iFormer/iFormer.css" type="text/css" />
<div class="iCMS-container">
  <div class="widget-box">
    <div class="widget-title">
      <span class="icon"> <i class="fa fa-pencil"></i> </span>
      <h5 class="brs"><?php echo empty($this->id)?'添加':'修改' ; ?>应用</h5>
      <ul class="nav nav-tabs" id="apps-add-tab">
        <li class="active"><a href="#apps-add-base" data-toggle="tab"><i class="fa fa-info-circle"></i> 基本信息</a></li>
        <?php if($rs['table'])foreach ($rs['table'] as $key => $table) {?>
        <li><a href="#apps-add-<?php echo $table[0]; ?>-field" data-toggle="tab"><i class="fa fa-database"></i> <?php echo $table[2]?$table[2]:$table[0]; ?>表字段</a></li>
        <?php }else if($rs['table']!='0'){?>
        <li><a href="#apps-add-field" data-toggle="tab"><i class="fa fa-cog"></i> 基础字段</a></li>
        <?php }?>
        <?php if($rs['table']!='0'){?>
        <li><a href="#apps-add-custom" data-toggle="tab"><i class="fa fa-cog"></i> 自定义字段</a></li>
        <?php }?>
      </ul>
    </div>
    <div class="widget-content nopadding">
      <form action="<?php echo APP_FURI; ?>&do=save" method="post" class="form-inline" id="iCMS-apps" target="iPHP_FRAME">
        <input name="_id" type="hidden" value="<?php echo $this->id; ?>" />
        <div id="apps-add" class="tab-content">
          <div id="apps-add-base" class="tab-pane active">
            <div class="input-prepend">
              <span class="add-on">应用名称</span>
              <input type="text" name="_name" class="span3" id="_name" value="<?php echo $rs['name'] ; ?>"/>
            </div>
            <span class="help-inline">应用中文名称</span>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend">
              <span class="add-on">应用标识</span>
              <input type="text" name="_app" class="span3" id="_app" value="<?php echo $rs['app'] ; ?>"/>
            </div>
            <span class="help-inline">应用唯一标识</span>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend" style="width:100%;">
              <span class="add-on">应用简介</span>
              <textarea name="config[info]" id="config_info" class="span6" style="height: 150px;"><?php echo $rs['config']['info'] ; ?></textarea>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend" style="width:100%;">
              <span class="add-on">应用路径</span>
              <input type="text" name="config[path]" class="span6" id="config_path" value="<?php echo $rs['config']['path'] ; ?>"/>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend" style="width:100%;">
              <span class="add-on">模板标签</span>
              <textarea name="config[template]" id="config_template" class="span6" style="height: 150px;"><?php echo implode("\n", (array)$rs['config']['template'])  ; ?></textarea>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend">
              <span class="add-on">应用菜单</span>
              <input type="text" name="config[menu]" class="span3" id="config_menu" value="<?php echo $rs['config']['menu'] ; ?>"/>
            </div>
            <span class="help-inline">应用的菜单</span>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend">
              <span class="add-on">管理入口</span>
              <input type="text" name="config[admincp]" class="span3" id="config_admincp" value="<?php echo $rs['config']['admincp'] ; ?>"/>
            </div>
            <span class="help-inline">应用的后台管理入口</span>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend">
              <span class="add-on">应用类型</span>
              <select name="type" id="type" class="chosen-select span3" data-placeholder="请选择应用类型...">
                <?php echo self::app_type_select() ; ?>
              </select>
            </div>
            <script>$(function(){iCMS.select('type',"<?php echo $rs['type']?$rs['type']:0 ; ?>");})</script>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend">
              <span class="add-on">应用状态</span>
              <div class="switch" data-on-label="启用" data-off-label="禁用">
                <input type="checkbox" data-type="switch" name="status" id="status" <?php echo $rs['status']?'checked':''; ?>/>
              </div>
              <span class="help-inline"></span>
              <div class="clearfloat mb10"></div>
              <div class="input-prepend">
                <span class="add-on">含有钩子</span>
                <div class="switch" data-on-label="有" data-off-label="无">
                  <input type="checkbox" data-type="switch" name="config[hook]" id="config_hook" <?php echo $rs['config']['hook']?'checked':''; ?>/>
                </div>
                <div class="clearfloat mb10"></div>
              </div>
            </div>
            <div class="clearfloat mb10"></div>
            <?php if($rs['table']){?>
            <h3 class="title">数据表</h3>
            <table class="table table-bordered bordered" style="width:360px;">
              <thead>
                <tr>
                  <th style="width:120px;">表名</th>
                  <th>主键</th>
                  <th>名称</th>
                </tr>
              </thead>
              <tbody>
                <?php
                foreach ((array)$rs['table'] as $tkey => $tvalue) {
                ?>
                <tr>
                  <td><input type="text" name="table[<?php echo $tkey; ?>][0]" class="span2" id="table_<?php echo $tkey; ?>0" value="<?php echo $tvalue[0] ; ?>"/></td>
                  <td><input type="text" name="table[<?php echo $tkey; ?>][1]" class="span2" id="table_<?php echo $tkey; ?>1" value="<?php echo $tvalue[1] ; ?>"/></td>
                  <td><input type="text" name="table[<?php echo $tkey; ?>][2]" class="span2" id="table_<?php echo $tkey; ?>2" value="<?php echo $tvalue[2] ; ?>"/></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
            <?php }else{ ?>
            <input name="table" type="hidden" value="<?php echo $rs['table']; ?>" />
            <?php } ?>
            <div class="clearfloat mb10"></div>

          </div>
          <?php
          if($rs['table'])foreach ($rs['table'] as $key => $table) {
          $tbn = iPHP_DB_PREFIX.$table[0];
          if(!apps_db::check_table($tbn)){
          echo $tbn ."表不存在!";
          continue;
          }
          ?>
          <div id="apps-add-<?php echo $table[0]; ?>-field" class="tab-pane">
            <table class="table table-hover table-bordered">
              <thead>
                <tr>
                  <th style="width:100px;">字段</th>
                  <th>数据类型</th>
                  <th>长度</th>
                  <th>主键</th>
                  <th>非空</th>
                  <th>Unsigned</th>
                  <th>自增</th>
                  <th>核对</th>
                  <th>注释</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $orig_fields  = apps_db::fields($tbn);
                foreach ((array)$orig_fields as $field => $value) {
                ?>
                <tr>
                  <td><b><?php echo $field; ?></b></td>
                  <td><?php echo $value['type']; ?></td>
                  <td><?php echo $value['length']; ?></td>
                  <td><?php if($value['primary']){?>
                    <font color="green"><i class="fa fa-check"></i></font>
                  <?php }?></td>
                  <td><?php echo $value['null']?'NULL':'NOT NULL'; ?></td>
                  <td><?php echo strtoupper($value['unsigned']); ?></td>
                  <td><?php if($value['auto_increment']){?>
                    <font color="green"><i class="fa fa-check"></i></font>
                  <?php }?></td>
                  <td><?php echo $value['collation']; ?></td>
                  <td><?php echo $value['comment']; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
            <div class="clearfloat mb10"></div>
            <div class="span4">
              <table class="table table-bordered bordered">
                <thead>
                  <tr>
                    <th colspan="2">索引</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $indexes  = apps_db::indexes($tbn);
                  foreach ((array)$indexes as $ikey => $ivalue) {
                  ?>
                  <tr>
                    <td><b><?php echo $ivalue['type']; ?></b></td>
                    <td><?php echo implode(',', (array)$ivalue['columns']); ?></td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
            <div class="span4 hide table_status">
              <table class="table table-bordered bordered">
                <thead>
                  <tr>
                    <th colspan="2">表信息</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $table_status  = apps_db::table_status($tbn);
                  foreach ((array)$table_status as $tskey => $tsvalue) {
                  ?>
                  <tr>
                    <td><b><?php echo $tskey; ?></b></td>
                    <td><?php echo $tsvalue; ?></td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
          <?php }else{ ?>
          <?php }?>
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
              <?php foreach ((array)$BASE_FIELDS[1] as $key => $value) { ?>
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
          <div id="apps-add-custom" class="tab-pane">
            <div class="fields-fluid">
              <ul id="custom_field_list">
              </ul>
              <div class="clearfloat mt10"></div>
            </div>
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
                    <li i="field" tag="input" type="date" field="INT" len="10" label="日期">
                      <span class="fb-icon fb-icon-date"></span>
                      <p>日期</p>
                    </li>
                    <li i="field" tag="input" type="datetime" field="INT" len="10" label="时间">
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
                    <li i="field" tag="select" type="select" field="TINYINT" len="1" label="列表">
                      <span class="fb-icon fb-icon-dropdown"></span>
                      <p>下拉列表</p>
                    </li>
                    <li i="field" tag="select" type="multiple" field="VARCHAR" len="255" label="多选">
                      <span class="multiselect fb-icon fb-icon-multiselect"></span>
                      <p>多选列表</p>
                    </li>
                    <li i="field" tag="input" type="number" field="TINYINT" len="1" label="数字">
                      <span class="fb-icon fb-icon-number"></span>
                      <p>数字</p>
                    </li>
                    <li i="field" tag="input" type="number" field="INT" len="10" label="数字">
                      <span class="fb-icon fb-icon-number"></span>
                      <p>大数字</p>
                    </li>
                    <li i="field" tag="input" type="number" field="BIGINT" len="20" label="数字">
                      <span class="fb-icon fb-icon-number"></span>
                      <p>超大数字</p>
                    </li>
                    <li i="field" tag="input" type="decimal" field="DECIMAL" len="6,2" label="小数">
                      <span class="fb-icon fb-icon-decimal"></span>
                      <p>小数</p>
                    </li>
                    <li i="field" tag="input" type="percentage" field="DECIMAL" len="3,2" label="百分比" label-after="%">
                      <span class="fb-icon fb-icon-percentage"></span>
                      <p>百分比</p>
                    </li>
                    <li i="field" tag="input" type="currency2" field="INT" len="10" label="货币" label-after="¥">
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
                    <li i="field" tag="multimage" field="TEXT" label="多图">
                      <span class="fb-icon fb-icon-image"></span>
                      <p>多图上传</p>
                    </li>
                    <li i="field" tag="file" field="VARCHAR" len="255" label="上传">
                      <span class="fb-icon fb-icon-fileupload"></span>
                      <p>上传文件</p>
                    </li>
                    <li i="field" tag="multifile" field="TEXT" label="批量上传">
                      <span class="fb-icon fb-icon-fileupload"></span>
                      <p>批量上传</p>
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
            <div class="clearfloat mt10"></div>
            <div class="alert alert-info alert-block">
              <h5><i class="fa fa-support"></i> 注意事项</h5>
              <p><i class="fa fa-arrows-h"></i> 换行符 双击可删除</p>
              <p><i class="fa fa-arrows-h"></i> 换行符 属于占位符 最终界面上将以10px空白替换.效果请参考文章添加页</p>
              <p>本界面元素只作编辑用,最终界面展现效果请使用预览功能</p>
            </div>
          </div>
          <div class="clearfloat"></div>
          <div class="form-actions">
            <button class="btn btn-primary btn-large" type="submit"><i class="fa fa-check"></i> 提交</button>
          </div>
        </form>
      </div>
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
    <div id="iFormer-value-text">
      <div class="input-prepend">
        <span class="add-on">默&nbsp;&nbsp;认&nbsp;值</span>
        <input type="text" name="value" class="span3" id="iFormer-value" value=""/>
      </div>
      <span class="help-inline">选填</span>
    </div>
    <div id="iFormer-value-select" class="hide">
      <div class="input-prepend">
        <span class="add-on">选项列表</span>
        <textarea type="text" name="value" class="span3" disabled/></textarea>
      </div>
      <span class="help-inline">* 必填.<br />
        格式: 选项=值;<br />
      例:电脑=pc</span>
    </div>
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
        <li><a href="#field-tab-4" data-toggle="tab"><i class="fa fa-user"></i> 用户</a></li>
        <li><a href="#field-tab-5" data-toggle="tab"><i class="fa fa-cog"></i> 优化</a></li>
      </ul>
      <div class="tab-content">
        <div id="field-tab-1" class="tab-pane active">
          <div class="input-prepend">
            <span class="add-on">数据验证</span>
            <select name="validate[]" id="iFormer-validate" class="chosen-select" style="width:360px;" data-placeholder="请选择数据验证方式..." multiple="multiple">
              <option value='empty'>不能为空</option>
              <option value='num'>只能为数字</option>
              <option value='minmax'>验证范围</option>
              <option value='len'>字数检测</option>
              <option value='repeat'>检查重复</option>
              <option value='email'>E-Mail地址</option>
              <option value='url'>网址</option>
              <option value='phone'>手机号码</option>
              <option value='telphone'>联系电话</option>
            </select>
          </div>
          <span class="help-inline">选填</span>
          <div class="clearfix"></div>
          <div id="minmax" class="input-prepend input-append hide">
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
              <option value='redirect'>网址跳转</option>
            </select>
          </div>
          <span class="help-inline">选填</span>
          <div class="input-prepend">
            <span class="add-on">关联应用</span>
            <input type="text" name="app" class="span3" id="iFormer-app" value=""/>
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
        </div>
        <div id="field-tab-4" class="tab-pane">
          <div class="input-prepend">
            <span class="add-on">用户选项</span>
            <select name="user[]" id="iFormer-user" class="chosen-select" style="width:360px;" data-placeholder="请选择数据处理方式..." multiple="multiple">
              <option value='show'>用户后台显示</option>
              <option value='fill'>用户后台可填写</option>
            </select>
          </div>
          <span class="help-inline">选填</span>
        </div>
        <div id="field-tab-5" class="tab-pane">
          <div class="input-prepend">
            <span class="add-on">数据优化</span>
            <select name="db[]" id="iFormer-db" class="chosen-select" style="width:360px;" data-placeholder="请选择数据处理方式..." multiple="multiple">
              <option value='index'>搜索项/索引</option>
            </select>
          </div>
          <span class="help-inline">选填</span>
        </div>
      </div>
    </div>
  </form>
</div>
<script type="text/javascript">
$(function(){
  //{handle: ".widget-title"}
  $( ".fields-container" ).draggable();
  $("#iCMS-apps").submit(function(){
    var name =$("#app_name").val();
    if(name==''){
      $("#app_name").focus();
      iCMS.alert("应用名称不能为空");
      return false;
    }
    var app =$("#app_app").val();
    if(app==''){
      $("#app_app").focus();
      iCMS.alert("应用标识不能为空");
      return false;
    }
  })
})
</script>
<?php admincp::foot();?>
