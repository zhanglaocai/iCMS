<?php /**
* @package iCMS
* @copyright 2007-2017, iDreamSoft
* @license http://www.idreamsoft.com iDreamSoft
* @author coolmoo <idreamsoft@qq.com>
*/
defined('iPHP') OR exit('What are you doing?');
admincp::head();
?>
<style>
.app_list_desc{font-size: 14px;color: #666;}
.nopadding .tab-content{padding: 0px;}
</style>
<script type="text/javascript">
$(function(){
  $("#<?php echo APP_FORMID;?>").batch();
});
</script>
<div class="iCMS-container">
  <div class="widget-box">
    <div class="widget-title"> <span class="icon"> <i class="fa fa-search"></i> </span>
    <h5>搜索</h5>
  </div>
  <div class="pull-right">
    <a style="margin: 10px;" class="btn btn-mini" href="<?php echo APP_FURI; ?>&do=cache" target="iPHP_FRAME"><i class="fa fa-refresh"></i> 更新缓存</a>
  </div>
  <div class="widget-content">
    <form action="<?php echo iPHP_SELF ; ?>" method="get" class="form-inline">
      <input type="hidden" name="app" value="<?php echo admincp::$APP_NAME;?>" />
      <div class="input-prepend input-append">
        <span class="add-on">每页</span>
        <input type="text" name="perpage" id="perpage" value="<?php echo $maxperpage ; ?>" style="width:36px;"/>
        <span class="add-on">条记录</span> </div>
        <div class="input-prepend input-append">
          <span class="add-on">关键字</span>
          <input type="text" name="keywords" class="span2" id="keywords" value="<?php echo $_GET['keywords'] ; ?>" />
          <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i> 搜 索</button>
        </div>
      </form>
    </div>
  </div>
  <div class="widget-box" id="<?php echo APP_BOXID;?>">
    <div class="widget-title"> <span class="icon">
      <input type="checkbox" class="checkAll" data-target="#<?php echo APP_BOXID;?>" />
    </span>
  </div>
  <div class="widget-content nopadding">
    <form action="<?php echo APP_FURI; ?>&do=batch" method="post" class="form-inline" id="<?php echo APP_FORMID;?>" target="iPHP_FRAME">
      <div class="tab-content">
          <table class="table table-bordered table-condensed table-hover">
            <thead>
              <tr>
                <th style="width:90px;">标识</th>
                <th>名称</th>
                <th>简介</th>
                <th class="span2">数据表</th>
                <th class="span3">模板标签</th>
                <th>操作</th>
              </tr>
            </thead>
            <tbody id="store-container">
            </tbody>
            </table>
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript" src="./app/admincp/ui/template-3.0.js"></script>
<script id="store-item" type="text/html">
{{each data as value i}}
<tr id="store-item-{{i}}">
  <td>{{value.app}}</td>
  <td>
    {{value.name}}
    {{if value.premium}}
      <br />
      <span class="label label-important">付费版</span>
      <br />
      <span class="label label-success">价格:{{value.price}} <i class="fa fa-rmb"></i></span>
    {{/if}}
  </td>
  <td>
    <img src="{{value.pic}}" height="30"/>
    <p>
    {{value.description}}
    {{if value.demo}}
      <a href="{{value.demo}}" target="_blank">&gt;&gt;&gt;&gt;&gt;演示</a>
    {{/if}}
    </p>
  </td>
  <td>
    {{each value.table as v}}
        {{v[2]}}[{{v[0]}}]<br />
    {{/each}}
  </td>
  <td>
    {{each value.template as v}}
        {{if v[1]}}
          <a href="{{v[1]}}" target="_blank" title="点击查看模板标签说明">&lt;!--&#x7B;{{v[0]}}&#x7D;--&gt;</a>
        {{else}}
          <a href="http://www.idreamsoft.com/iCMS/doc/{{v[0]}}" target="_blank" title="点击查看模板标签说明">&lt;!--&#x7B;{{v[0]}}&#x7D;--&gt;</a>
        {{/if}}<br />
    {{/each}}
  </td>
  <td>
    <a href="<?php echo APP_FURI; ?>&do=store_install&sid={{value.id}}" target="iPHP_FRAME" class="btn btn-success" title='安装'><i class="fa fa-plug"></i> 安装应用</a>
  </td>
</tr>
{{/each}}
</script>
<script type="text/javascript">
$(function(){
    $.getJSON("<?php echo APP_URI; ?>&do=store_json",
        function(json){
            var html = template('store-item', {"data":json});
            $("#store-container").html(html);
        }
    );
});
</script>
<?php admincp::foot();?>
