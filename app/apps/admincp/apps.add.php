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
<script type="text/javascript">
$(function(){
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

<div class="iCMS-container">
  <div class="widget-box">
    <div class="widget-title">
      <span class="icon"> <i class="fa fa-pencil"></i> </span>
      <h5 class="brs"><?php echo empty($this->id)?'添加':'修改' ; ?>应用</h5>
      <ul class="nav nav-tabs" id="apps-add-tab">
        <li class="active"><a href="#apps-add-base" data-toggle="tab"><i class="fa fa-info-circle"></i> 基本信息</a></li>
        <?php if($rs['table'])foreach ($rs['table'] as $key => $tval) {?>
        <li><a href="#apps-add-<?php echo $key; ?>-field" data-toggle="tab"><i class="fa fa-database"></i> <?php echo $tval['label']?$tval['label']:$tval['name']; ?>表字段</a></li>
        <?php }?>
        <?php if($rs['config']['iFormer']){?>
          <?php if(!$rs['table']){?>
          <li><a href="#apps-add-field" data-toggle="tab"><i class="fa fa-cog"></i> 基础字段</a></li>
          <?php }?>
          <li><a href="#apps-add-custom" data-toggle="tab"><i class="fa fa-cog"></i> 自定义字段</a></li>
        <?php }?>
      </ul>
    </div>
    <div class="widget-content nopadding">
      <form action="<?php echo APP_FURI; ?>&do=save" method="post" class="form-inline" id="iCMS-apps" target="iPHP_FRAME">
        <input name="_id" type="hidden" value="<?php echo $this->id; ?>" />
        <input name="apptype" type="hidden" value="<?php echo $rs['apptype']; ?>" />
        <input name="config[iFormer]" type="hidden" value="<?php echo $rs['config']['iFormer']; ?>" />
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
            <div class="input-prepend">
              <span class="add-on">应用简介</span>
              <textarea name="config[info]" id="config_info" class="span6" style="height: 150px;"><?php echo $rs['config']['info'] ; ?></textarea>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend">
              <span class="add-on">模板标签</span>
              <textarea name="config[template]" id="config_template" class="span6" style="height: 150px;" readonly><?php
              if($rs['app']){
                $_app = $rs['app'];
                if($rs['config']['iFormer'] && $rs['apptype']=="2"){
                  $_app = 'app';
                }
                $template = (array)apps::get_func($_app,true);
                list($path,$obj_name)= apps::get_path($_app,'app',true);
                if(@is_file($path)){
                    //判断是否有APP同名方法存在 如果有 $appname 模板标签可用
                    $class_methods = get_class_methods ($obj_name);
                    if(array_search ($_app ,  $class_methods )!==FALSE){
                      array_push ($template,'$'.$rs['app']);
                      $rs['config']['router'] = '1';
                    }
                }
              }
              $template = implode("\n", (array)$template);
              if($rs['config']['iFormer'] && $rs['apptype']=="2"){
                $template = str_replace(array(':app:','$app'), array(':'.$rs['app'].':','$'.$rs['app']), $template);
              }
              echo $template;
            ?></textarea>
              <input name="config[router]" type="hidden" value="<?php echo $rs['config']['router']; ?>" />
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend">
              <span class="add-on">管理入口</span>
              <input type="text" name="config[admincp]" class="span3" id="config_admincp" value="<?php echo $rs['config']['admincp'] ; ?>"/>
            </div>
            <span class="help-inline">应用的后台管理入口</span>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend">
              <span class="add-on">应用菜单</span>
              <select name="config[menu]" id="config_menu" class="chosen-select span3" data-placeholder="请选择应用类型...">
                <option value="0">无菜单</option>
                <option value="main">主菜单[main]</option>
                <?php
                  foreach (menu::$menu_array as $key => $value) {
                    if($value['caption']=='-'||$key==$rs['app']){
                      continue;
                    }
                ?>
                  <option value="<?php echo $key?>"><?php echo $value['caption']?>菜单[<?php echo $key?>]</option>
                <?php }?>
              </select>
            </div>
            <script>$(function(){iCMS.select('config_menu',"<?php echo $rs['config']['menu']?$rs['config']['menu']:'0'; ?>");})</script>
            <span class="help-inline">应用的菜单</span>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend">
              <span class="add-on">应用类型</span>
              <select name="type" id="type" class="chosen-select span3" data-placeholder="请选择应用类型...">
                <?php echo apps::get_type_select() ; ?>
              </select>
            </div>
            <script>$(function(){iCMS.select('type',"<?php echo $rs['type']?$rs['type']:'1'; ?>");})</script>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend">
              <span class="add-on">用户中心</span>
              <div class="switch" data-on-label="启用" data-off-label="禁用">
                <input type="checkbox" data-type="switch" name="usercp" id="usercp" <?php echo $rs['config']['usercp']?'checked':''; ?>/>
              </div>
              <span class="help-inline">启用后,用户中心将显示此应用并根据字段设计</span>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend">
              <span class="add-on">应用状态</span>
              <div class="switch" data-on-label="启用" data-off-label="禁用">
                <input type="checkbox" data-type="switch" name="status" id="status" <?php echo $rs['status']?'checked':''; ?>/>
              </div>
              <span class="help-inline"></span>
            </div>
            <div class="clearfloat mb10"></div>
            <?php if($rs['table']){?>
            <h3 class="title" style="width:450px;">数据表</h3>
            <table class="table table-bordered bordered" style="width:460px;">
              <thead>
                <tr>
                  <th style="width:120px;">表名</th>
                  <th>主键</th>
                  <th>关联</th>
                  <th>名称</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ((array)$rs['table'] as $tkey => $tval) {?>
                <tr>
                  <td><input type="hidden" name="table[<?php echo $tkey; ?>][0]" value="<?php echo $tval['name'] ; ?>"/> <?php echo $tval['name'] ; ?></td>
                  <td><input type="hidden" name="table[<?php echo $tkey; ?>][1]" value="<?php echo $tval['primary'] ; ?>"/> <?php echo $tval['primary'] ; ?></td>
                  <td><input type="hidden" name="table[<?php echo $tkey; ?>][2]" value="<?php echo $tval['union'] ; ?>"/> <?php echo $tval['union'] ; ?></td>
                  <td><input type="text" name="table[<?php echo $tkey; ?>][3]" class="span2" id="table_<?php echo $tkey; ?>_2" value="<?php echo $tval['label'] ; ?>"/></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
            <?php }else{ ?>
            <input name="table" type="hidden" value="<?php echo $rs['table']; ?>" />
            <?php } ?>
            <div class="clearfloat mb10"></div>
          </div>
          <!-- 数据表字段 -->
          <?php include admincp::view("apps.table");?>
          <?php if($rs['config']['iFormer']){?>
          <div id="apps-add-field" class="tab-pane">
            <!-- 基础字段 -->
            <?php include admincp::view("apps.base");?>
          </div>
          <div id="apps-add-custom" class="tab-pane">
            <?php include admincp::view("apps.iFormer.build");?>
          </div>
          <?php } ?>
          <div class="clearfloat"></div>
          <div class="form-actions">
            <button class="btn btn-primary btn-large" type="submit"><i class="fa fa-check"></i> 提交</button>
            <a href="<?php echo APP_FURI; ?>&do=uninstall&id=<?php echo $rs['id'] ; ?>" target="iPHP_FRAME" class="del btn btn-small btn-danger" title='永久删除'  onclick="return confirm('卸载应用会清除应用所有数据！\n卸载应用会清除应用所有数据！\n卸载应用会清除应用所有数据！\n确定要卸载?\n确定要卸载?\n确定要卸载?');"/><i class="fa fa-trash-o"></i> 卸载</a>

          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include admincp::view("apps.iFormer.editor");?>
<?php admincp::foot();?>
