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
      iCMS.alert("表单名称不能为空");
      return false;
    }
    var app =$("#app_app").val();
    if(app==''){
      $("#app_app").focus();
      iCMS.alert("表单标识不能为空");
      return false;
    }
  })
})
</script>

<div class="iCMS-container">
  <div class="widget-box">
    <div class="widget-title">
      <span class="icon"> <i class="fa fa-pencil"></i> </span>
      <h5 class="brs"><?php echo empty($this->id)?'创建':'修改' ; ?>表单</h5>
      <ul class="nav nav-tabs" id="apps-add-tab">
        <li class="active"><a href="#apps-add-base" data-toggle="tab"><i class="fa fa-info-circle"></i> 基本信息</a></li>
        <?php if($rs['table'])foreach ($rs['table'] as $key => $tval) {?>
        <li><a href="#apps-add-<?php echo $key; ?>-field" data-toggle="tab"><i class="fa fa-database"></i> <?php echo $tval['label']?$tval['label']:$tval['name']; ?>表字段</a></li>
        <?php }?>
        <?php if(!$rs['table']){?>
        <li><a href="#apps-add-field" data-toggle="tab"><i class="fa fa-cog"></i> 基础字段</a></li>
        <?php }?>
        <li><a href="#apps-add-custom" data-toggle="tab"><i class="fa fa-cog"></i> 自定义字段</a></li>
      </ul>
    </div>
    <div class="widget-content nopadding">
      <form action="<?php echo APP_FURI; ?>&do=save" method="post" class="form-inline" id="iCMS-apps" target="iPHP_FRAME">
        <input name="_id" type="hidden" value="<?php echo $this->id; ?>" />
        <div id="apps-add" class="tab-content">
          <div id="apps-add-base" class="tab-pane active">
            <div class="input-prepend">
              <span class="add-on">表单名称</span>
              <input type="text" name="_name" class="span3" id="_name" value="<?php echo $rs['name'] ; ?>"/>
            </div>
            <span class="help-inline">表单中文名称</span>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend">
              <span class="add-on">表单标识</span>
              <input type="text" name="_app" class="span3" id="_app" value="<?php echo $rs['app'] ; ?>"/>
            </div>
            <span class="help-inline">表单唯一标识</span>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend">
              <span class="add-on">表单标题</span>
              <input type="text" name="_title" class="span3" id="_title" value="<?php echo $rs['title'] ; ?>"/>
            </div>
            <span class="help-inline">表单标题.例:表单名称(文章系统),表单标题(文章)</span>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend">
              <span class="add-on">表单简介</span>
              <textarea name="config[info]" id="config_info" class="span6" style="height: 150px;"><?php echo $rs['config']['info'] ; ?></textarea>
            </div>
            <span class="help-inline">版本号</span>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend">
              <span class="add-on">用户中心</span>
              <div class="switch" data-on-label="启用" data-off-label="禁用">
                <input type="checkbox" data-type="switch" name="usercp" id="usercp" <?php echo $rs['config']['usercp']?'checked':''; ?>/>
              </div>
              <span class="help-inline">启用后,用户中心将显示此表单并根据字段设计</span>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend">
              <span class="add-on">表单状态</span>
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
          <?php include admincp::view("apps.table","apps");?>
          <div id="apps-add-field" class="tab-pane">
            <!-- 基础字段 -->
            <?php include admincp::view("apps.base","apps");?>
          </div>
          <div id="apps-add-custom" class="tab-pane">
            <?php include admincp::view("former.build","former");?>
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
<?php include admincp::view("former.editor","former");?>
<?php admincp::foot();?>
