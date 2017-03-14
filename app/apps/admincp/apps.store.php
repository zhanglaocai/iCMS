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
function pay_notify (key,sid,d) {
  var timer = window.setInterval(function(){
    console.log(key,sid);
    $.getJSON(
      "<?php echo apps_store::STORE_URL;?>/store.pay.notify?callback=?",{key,sid},
      function(o){
          console.log(o);
          // d.close().remove();
          // clearInterval(timer);
      }
    );
  },1000);
}
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
    <div class="widget-title">
      <span class="icon">
        <i class="fa fa-bank"></i> <span>应用市场</span>
      </span>
    </div>
    <div class="widget-content">
      <form action="<?php echo APP_FURI; ?>&do=batch" method="post" class="form-inline" id="<?php echo APP_FORMID;?>" target="iPHP_FRAME">
            <div class="row" id="store-container">


            </div>
          <div class="clearfloat"></div>
      </form>
    </div>
  </div>
  <script type="text/javascript" src="./app/admincp/ui/template-3.0.js"></script>
<style>
#store-container{margin-left: -15px;}
.store-item{margin-left: 15px;width: 22%; border: 1px solid #CDCDCD; border-radius: 5px;padding: 8px;margin-bottom: 10px;height: 300px;background-color: #fff;}
.item-head{margin-bottom: 10px;height: 100px; background-color: #333;text-align: center;}
.item-head h3{height: 100px;line-height: 90px;font-size: 36px;color: #f5f5f5;}
.item-head img{height: 100px;width: 100%; background-color: #dadada;}

.item-author{height: 60px;}
.item-author .avatar img {width: 60px;height: 60px;border-radius: 30px;float: left;}
.item-author .name{margin-left: 70px; line-height: 30px;}
.item-author .name span.version{color: #666;}
.item-author .name b{display: block;}
.item-author .name .demo{font-size: 12px;}
.item-description{height: 68px;margin-top: 10px;overflow: hidden;color: #666;}
.item-description p{font-size: 14px;}
.item-action {margin-top: 10px;}
.item-action .btn-large{ width: 100%; height: 35px;line-height: 35px;padding: 0px;}

</style>
<script id="store-item" type="text/html">
{{each data as value i}}
  <div id="store-item-{{value.app}}" class="store-item span3">
    <div class="item-head">
      {{if value.pic}}
        <img src="{{value.pic}}"/>
      {{else}}
        <h3>{{value.name}}</h3>
      {{/if}}
    </div>
    <div class="item-author">
      <p class="avatar"><img src="{{value.avatar}}" /></p>
      <p class="name">
        <b>@{{value.author}}</b>
        <span class="version">当前版本:{{value.version}}</span>
        {{if value.demo}}
          <a class="demo" href="{{value.demo}}" target="_blank">&gt;&gt;演示</a>
        {{/if}}
      </p>
    </div>
    <div class="item-description">
        {{if value.premium}}
          <span class="label label-important">付费版</span>
          <span class="label label-success">价格:{{value.price}} <i class="fa fa-rmb"></i></span>
        {{/if}}
        <p>{{value.description}}</p>
    </div>
    <div class="item-action">
      <a href="<?php echo APP_FURI; ?>&do=store_install&sid={{value.id}}" target="iPHP_FRAME" class="btn btn-large btn-success">
        <i class="fa fa-download"></i>
        {{if value.premium}}
        付费安装
        {{else}}
        立即安装
        {{/if}}
      </a>
    </div>
    <div class="clearfloat"></div>
  </div>
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
