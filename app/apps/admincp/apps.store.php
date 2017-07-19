<?php
/**
* iCMS - i Content Management System
* Copyright (c) 2007-2017 iCMSdev.com. All rights reserved.
*
* @author icmsdev <master@icmsdev.com>
* @site https://www.icmsdev.com
* @licence https://www.icmsdev.com/LICENSE.html
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
var pay_notify_timer;
function pay_notify (j,d) {
  pay_notify_timer = window.setInterval(function(){
    $.getJSON(
      "<?php echo apps_store::STORE_URL;?>/store.pay.notify?callback=?",
      {key:j[0],sid:j[1],name:j[3]},
      function(o){
          //console.log(o);
          if(o.code=="1" && o.url && o.t){
            $("#iPHP_FRAME").attr("src","<?php echo APP_URI;?>&do=<?php echo admincp::$APP_DO; ?>_premium_install&url="+o.url+'&sapp='+j[2]+'&name='+j[3]+'&key='+j[0]+'&sid='+j[1]+'&version='+j[4])
            clear_pay_notify_timer();
            d.close().remove();
          }else if(o.code=="2"){
            alert(o.msg);
            window.location.reload();
          }
      }
    );
  },1000);
}
function clear_pay_notify_timer () {
  clearInterval(pay_notify_timer);
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
        <i class="fa fa-bank"></i> <span><?php echo $title;?>市场</span>
      </span>
    </div>
    <div class="widget-content">
      <form action="<?php echo APP_FURI; ?>&do=batch" method="post" class="form-inline" id="<?php echo APP_FORMID;?>" target="iPHP_FRAME">
          <div class="row" id="store-container">
            <?php
            foreach ((array)$data as $key => $value) {
              $is_update = false;
              $has = $rs[$value['app']];
              $version = preg_replace('/^[a-zA-Z]+/', '', $has['version']);
              if($has && version_compare($value['version'],$version,'>')){
                $is_update = true;
              }
            ?>
              <div id="store-item-<?php echo $value['app'];?>" class="store-item span4">
                <div class="item-head">
                  <?php if($value['pic']){?>
                    <p style="background:url('<?php echo $value['pic'];?>') center center"></p>
                  <?php }else{ ?>
                    <h3><?php echo $value['name'];?></h3>
                  <?php } ?>
                </div>
                <div class="item-author">
                  <p class="avatar"><img src="<?php echo $value['avatar'];?>" /></p>
                  <p class="name">
                    <b>@<?php echo $value['author'];?></b>
                    <span class="version">版本:<?php echo $value['version'];?></span>
                    <span class="size">大小:<?php echo $value['size'];?></span>
                  </p>
                </div>
                <div class="item-description">
                    <?php if($value['premium']){?>
                      <span class="label label-important">付费<?php echo $title;?></span>
                      <span class="label label-success">价格:<?php echo $value['price'];?> <i class="fa fa-rmb"></i></span>
                    <?php } ?>
                    <p>
                    <?php echo $value['description'];?>
                    <?php if($value['demo']){?>
                      <a class="demo" href="<?php echo $value['demo'];?>" target="_blank">&gt;&gt;演示</a>
                    <?php } ?>
                    </p>
                </div>
                <div class="item-action">
                  <?php if($has){?>
                    <?php if($is_update){?>
                    <a title="当版本:<?php echo $has['version'];?>" href="<?php echo APP_FURI; ?>&do=<?php echo admincp::$APP_DO; ?>_update&sid=<?php echo $value['id'];?>" target="iPHP_FRAME" class="btn btn-large btn-success tip-top">
                      <i class="fa fa-repeat"></i> 可更新
                    </a>
                    <?php }else{ ?>
                    <a href="<?php echo APP_FURI; ?>&do=uninstall&id=<?php echo $has['id'];?>" target="iPHP_FRAME" class="btn btn-large btn-danger" title="永久删除" onclick="return confirm('卸载应用会清除应用所有数据！\n卸载应用会清除应用所有数据！\n卸载应用会清除应用所有数据！\n确定要卸载?\n确定要卸载?\n确定要卸载?');"><i class="fa fa-trash-o"></i> 卸载</a>
                    <?php } ?>
                  <?php }else{ ?>
                  <a href="<?php echo APP_FURI; ?>&do=<?php echo admincp::$APP_DO; ?>_install&sid=<?php echo $value['id'];?>" target="iPHP_FRAME" class="btn btn-large btn-primary">
                    <i class="fa fa-download"></i>
                    <?php if($value['premium']){?>
                    付费安装
                    <?php }else{ ?>
                    立即安装
                    <?php } ?>
                  </a>
                  <?php } ?>
                </div>
                <div class="clearfloat"></div>
              </div>
            <?php } ?>
          </div>
          <div class="clearfloat"></div>
      </form>
    </div>
  </div>
<style>
#store-container{margin-left: -15px;}
.store-item{margin-left: 15px; border: 1px solid #CDCDCD; border-radius: 5px;padding: 8px;margin-bottom: 10px;height: 300px;background-color: #fff;}
.item-head{margin-bottom: 10px;height: 100px; background-color: #333;text-align: center;overflow: hidden;}
.item-head h3{height: 100px;line-height: 90px;font-size: 36px;color: #f5f5f5;}
.item-head p{height: 100px;max-width: 360px; background-color: #dadada;}

.item-author{height: 60px;}
.item-author .avatar img {width: 60px;height: 60px;border-radius: 30px;float: left;}
.item-author .name{margin-left: 70px; line-height: 30px;}
.item-author .name span.version{color: #666;}
.item-author .name span.size{color: #666;}
.item-author .name b{display: block;}
.item-author .name .demo{font-size: 12px;}
.item-description{height: 68px;margin-top: 10px;overflow: hidden;color: #666;}
.item-description p{font-size: 14px;}
.item-action {margin-top: 10px;}
.item-action .btn-large{ width: 100%; height: 35px;line-height: 35px;padding: 0px;}
@media (min-width:1200px) {
  .store-item{width: 22%;}
}
</style>

<?php admincp::foot();?>
