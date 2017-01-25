<?php /**
* @package iCMS
* @copyright 2007-2017, iDreamSoft
* @license http://www.idreamsoft.com iDreamSoft
* @author coolmoo <idreamsoft@qq.com>
*/
defined('iPHP') OR exit('What are you doing?');
?>
<link rel="stylesheet" href="./app/admincp/ui/jquery/treeview-0.1.0.css" type="text/css" />
<script type="text/javascript" src="./app/admincp/ui/template-3.0.js"></script>
<script type="text/javascript" src="./app/admincp/ui/jquery/treeview-0.1.0.js"></script>
<script type="text/javascript" src="./app/admincp/ui/jquery/treeview-0.1.0.async.js"></script>
<script id="cpower_item" type="text/html">
<div class="input-prepend input-append">
    <span class="add-on">APPID:{{appid}}</span>
    <span class="add-on">{{name}}</span>
    <span class="add-on"><input type="checkbox" name="cpower[]" value="{{cid}}:s"> 查询</span>
    <span class="add-on"><input type="checkbox" name="cpower[]" value="{{cid}}:a" /> 添加子级</span>
    <span class="add-on"><input type="checkbox" name="cpower[]" value="{{cid}}:e" /> 编辑</span>
    <span class="add-on"><input type="checkbox" name="cpower[]" value="{{cid}}:d" /> 删除</span>
</div>
<div class="input-prepend input-append">
    <span class="add-on">内容权限</span>
    <span class="add-on"><input type="checkbox" name="cpower[]" value="{{cid}}:cs" /> 查询</span>
    <span class="add-on"><input type="checkbox" name="cpower[]" value="{{cid}}:ca" /> 添加</span>
    <span class="add-on"><input type="checkbox" name="cpower[]" value="{{cid}}:ce" /> 编辑</span>
    <span class="add-on"><input type="checkbox" name="cpower[]" value="{{cid}}:cd" /> 删除</span>
</div>
</script>
<script id="power_item" type="text/html">
<div class="input-prepend input-append li2">
  <span class="add-on"><input type="checkbox" name="power[]" value="{{id}}"></span>
  {{if caption=='-'}}
  <span class="add-on tip" title="分隔符权限,仅为UI美观">────────────</span>
  {{else}}
  <span class="add-on">{{caption}}</span>
  {{/if}}
</div>
</script>
<script type="text/javascript">
$(function(){
  var power  = <?php echo $rs->power?$rs->power:'{}'?>,
  cpower = <?php echo $rs->cpower?$rs->cpower:'{}'?>;
  get_tree('power','<?php echo __ADMINCP__;?>=menu&do=ajaxtree&expanded=1','power_item');
  get_tree('cpower','<?php echo __ADMINCP__;?>=category&do=ajaxtree&expanded=0','cpower_item');
  set_select(power,'<?php echo admincp::$APP_NAME; ?>-power');
  set_select(cpower,'<?php echo admincp::$APP_NAME; ?>-cpower');
});
function get_tree(e,url,tpl){
  return $("#"+e+"_tree").treeview({
      tpl:tpl,
      url:url,
      collapsed: false,
      animated: "medium",
      control:"#"+e+"_treecontrol"
  });
}
function set_select(vars,el){
    if(!vars) return;
    $.each(vars, function(i,val){
      $('input[value="'+val+'"]',$("#"+el))
        .prop("checked", true)
        .closest('.checker > span')
        .addClass('checked');
    });
}
</script>
<style>
.separator .checker{margin-top: -20px !important;}
</style>
<div id="<?php echo admincp::$APP_NAME; ?>-power" class="tab-pane hide">
  <div class="input-prepend input-append">
    <span class="add-on">全选</span>
    <span class="add-on">
      <input type="checkbox" class="checkAll checkbox" data-target="#<?php echo admincp::$APP_NAME; ?>-power"/>
    </span>
    <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i> 提交</button>
  </div>
  <div class="clearfloat mb10"></div>
  <div class="input-prepend input-append">
    <span class="add-on"><i class="fa fa-cog"></i> 全局权限</span>
    <span class="add-on">::</span>
    <span class="add-on"><input type="checkbox" name="power[]" value="ADMINCP" /> 允许登陆后台</span>
  </div>
  <div class="clearfloat mb10"></div>
  <div class="input-prepend input-append">
    <span class="add-on"><i class="fa fa-list-alt"></i> 文章权限</span>
    <span class="add-on">::</span>
    <span class="add-on"><input type="checkbox" name="power[]" value="ARTICLE.VIEW" /> 查看所有</span>
    <span class="add-on"><input type="checkbox" name="power[]" value="ARTICLE.EDIT" /> 编辑所有</span>
    <span class="add-on"><input type="checkbox" name="power[]" value="ARTICLE.DELETE" /> 删除所有</span>
  </div>
  <div class="clearfloat mb10"></div>
  <div class="input-prepend input-append">
    <span class="add-on"><i class="fa fa-folder"></i> 文件权限</span>
    <span class="add-on">::</span>
    <span class="add-on"><input type="checkbox" name="power[]" value="FILE.UPLOAD" /> 上传</span>
    <span class="add-on"><input type="checkbox" name="power[]" value="FILE.MKDIR" /> 创建目录</span>
    <span class="add-on"><input type="checkbox" name="power[]" value="FILE.MANAGE" /> 管理</span>
    <span class="add-on"><input type="checkbox" name="power[]" value="FILE.BROWSE" /> 浏览</span>
    <span class="add-on"><input type="checkbox" name="power[]" value="FILE.EDIT" /> 编辑</span>
    <span class="add-on"><input type="checkbox" name="power[]" value="FILE.DELETE" /> 删除</span>
  </div>
  <div class="clearfloat"></div>
  <span class="label label-important">注:工具中的上传文件/文件管理为操作链接权限,是否有文件(上传/管理)权限以文件权限的设置为主</span>
  <div class="clearfloat mb10 solid"></div>
  <div id="power_treecontrol">
    <a style="display:none;"></a>
    <a style="display:none;"></a>
    <a class="btn btn-info" href="javascript:;">展开/收缩</a>
  </div>
  <ul id="power_tree">

  </ul>
</div>
<div id="<?php echo admincp::$APP_NAME; ?>-cpower" class="tab-pane hide">
  <div class="input-prepend input-append">
    <span class="add-on">全选</span>
    <span class="add-on">
      <input type="checkbox" class="checkAll checkbox" data-target="#<?php echo admincp::$APP_NAME; ?>-cpower"/>
    </span>
    <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i> 提交</button>
  </div>
  <div class="clearfloat mb10"></div>
  <div class="input-prepend input-append">
    <span class="add-on"><i class="fa fa-cog"></i> 全局权限</span>
    <span class="add-on">::</span>
    <span class="add-on">允许添加顶级栏目</span>
    <span class="add-on"><input type="checkbox" name="cpower[]" value="0:a" /></span>
  </div>
  <div class="clearfloat mb10"></div>
  <div id="cpower_treecontrol">
    <a style="display:none;"></a>
    <a style="display:none;"></a>
    <a class="btn btn-info" href="javascript:;">展开/收缩</a>
  </div>
  <ul id="cpower_tree">

  </ul>
</div>
