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

            </div>
            <div class="clearfloat mb10"></div>
            <?php if($rs['table']){?>
            <h3 class="title" style="width:462px;">数据表</h3>
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
          <?php }else{?>
          <?php }?>
          <div id="apps-add-field" class="tab-pane">
            <div id="field-default">
              <div id="field_id">
                <div class="input-prepend input-append">
                  <span class="add-on">id</span>
                  <span class="input-xlarge uneditable-input">INT(10) UNSIGNED NOT NULL</span>
                  <span class="add-on" style="width:auto">主键 自增ID</span>
                </div>
              </div>
              <div class="clearfloat mb10"></div>
              <?php foreach ((array)$base_fields[1] as $key => $value) { ?>
              <div id="field_<?php echo $value; ?>">
                <div class="input-prepend input-append">
                  <span class="add-on"><?php echo $value; ?></span>
                  <span class="input-xlarge uneditable-input"><?php echo $base_fields[2][$key]; ?></span>
                  <span class="add-on" style="width:auto"><?php echo $base_fields[4][$key]; ?></span>
                </div>
              </div>
              <div class="clearfloat mb10"></div>
              <?php } ?>
            </div>
          </div>
          <div id="apps-add-custom" class="tab-pane">
            <?php include admincp::view("apps.iFormer.build");?>
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
<?php include admincp::view("apps.iFormer.edit");?>
<?php admincp::foot();?>
