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
<div id="counter"></div>
<script src="./app/admincp/ui/jquery/jquery.timer.js" type="text/javascript"></script>
<style>
.app_list_desc{font-size: 14px;color: #666;}
.nopadding .tab-content{padding: 0px;}
</style>
<script type="text/javascript">
$(function(){
  $("#<?php echo APP_FORMID;?>").batch();
});
var pay_notify_timer,clear_timer;
function pay_notify (url,j,d) {
  clear_timer = false;
  pay_notify_timer = $.timer(function(){
    pay_notify_timer.stop();
    $.getJSON("<?php echo APP_URI;?>&do=pay_notify&callback=?",{key:j[0],sid:j[1],name:j[3]},
      function(o){
          // console.log(o);
          if(o.code=="1" && o.url && o.t){
            iCMS.success("数据下载中...请稍候!",false,10000000);
            $("#iPHP_FRAME").attr("src","<?php echo APP_URI;?>&do=<?php echo admincp::$APP_DO; ?>_premium_install&url="+o.url+'&transaction_id='+o.transaction_id+'&sapp='+j[2]+'&name='+j[3]+'&key='+j[0]+'&sid='+j[1]+'&version='+j[4])
            d.close().remove();
            return;
          }else if(o.code=="0"){
            //等待支付
            if(!clear_timer){
              pay_notify_timer.play();
            }
          }else {
            alert(o.msg);
            window.location.reload();
          }
      }
    );
  });
  pay_notify_timer.set({ time : 1000, autostart : true });
  d.addEventListener('close', function(){
    clear_pay_notify_timer();
  });
}
function clear_pay_notify_timer() {
  clear_timer = true;
  pay_notify_timer.stop();
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
            foreach ((array)$dataArray as $key => $value) {
              $is_update = false;
              $sid       = $value['id'];
              $appconf   = $storeArray[$sid];
              if($appconf){
                version_compare($value['version'],$appconf['version'],'>') && $is_update = true;
                ($appconf['git_time'] && $appconf['git_time']<$value['git_time']) && $is_update = true;
                ($appconf['git_sha'] && $appconf['git_sha']!=$value['git_sha']) && $is_update = true;
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
                    <b>
                      <span class="label label-inverse">@<?php echo $value['author'];?></span>
                      <?php if($appconf && $value['qq']){?>
                        <span class="label label-info">QQ:<?php echo $value['qq'];?></span>
                      <?php } ?>
                    </b>
                    <span class="version">版本:<?php echo $value['version'];?></span>
                    <span class="size">大小:<?php echo $value['size'];?></span>
                    <?php if($value['demo']){?>
                      <a class="demo" href="<?php echo $value['demo'];?>" target="_blank">
                        <span class="label">演示&gt;&gt;</span>
                      </a>
                    <?php } ?>
                  </p>
                </div>
                <div class="item-description">
                    <?php if($value['premium']){?>
                      <span class="label label-important">付费<?php echo $title;?></span>
                      <span class="label label-success">
                        <?php if($value['coupon']){?>
                        <del>原价:<?php echo $value['price'];?><i class="fa fa-rmb"></i></del>
                        优惠价:<?php echo $value['coupon'];?>
                        <?php }else{ ?>
                        价格:<?php echo $value['price'];?>
                        <?php } ?>
                        <i class="fa fa-rmb"></i>
                      </span>
                    <?php } ?>
                    <?php if($value['git_time']){?>
                    <span class="label label-info tip-top" title="更新时间"><i class="fa fa-clock-o"></i> <?php echo get_date($value['git_time'],'Y-m-d H:i');?></span>
                    <?php } ?>
                    <?php if($value['iCMS_VERSION']||$value['iCMS_GIT_TIME']){?>
                    <a href="javascript:;" class="tip-top" title="
                    <?php if($value['iCMS_VERSION']){?>
                    版本要求:iCMS V<?php echo $value['iCMS_VERSION'];?>[<?php echo $value['iCMS_RELEASE'];?>]
                    <?php } ?>
                    <?php if($value['iCMS_GIT_TIME']){?>
                    <hr />
                    git版本:iCMS[git:<?php echo get_date($value['iCMS_GIT_TIME'],'Y-m-d H:i');?>]
                    <?php } ?>
                    ">
                      <span class="label label-important">版本要求</span>
                    </a>
                    <?php } ?>
                    <p style="overflow-y: auto;max-height: 70px;">
                      <?php echo $value['description'];?>
                    </p>
                </div>
                <hr />
                <div class="item-action">
                  <?php if($appconf){?>
                    <a
                    <?php if($is_update){?>
                      title="
                      当前版本:<?php echo $appconf['version'];?>
                      安装时间:<?php echo get_date($appconf['addtime'],'Y-m-d H:i');?>
                      <br />
                      SHA-1:<?php echo $appconf['git_sha'];?>
                      <hr />
                      最新版本:v<?php echo $value['version'];?>
                      更新时间:<?php echo get_date($value['git_time'],'Y-m-d H:i');?>
                      <br />
                      SHA-1:<?php echo $value['git_sha'];?>
                      "
                      href="<?php echo APP_FURI; ?>&do=<?php echo admincp::$APP_DO; ?>_update&sid=<?php echo $sid;?>&id=<?php echo $appconf['appid'];?>&commit_id=<?php echo $appconf['git_sha'];?>"
                    <?php }else{ ?>
                      title="暂无可用更新"
                      disabled="disabled"
                      href="javascript:;"
                    <?php } ?>
                      target="iPHP_FRAME" class="btn btn-large btn50 btn-success tip-top">
                      <i class="fa fa-repeat"></i> 更新
                    </a>
                    <a href="<?php echo APP_FURI; ?>&do=<?php echo admincp::$APP_DO; ?>_uninstall&sid=<?php echo $sid;?>&id=<?php echo $appconf['appid'];?>"
                      target="iPHP_FRAME" class="btn btn-large btn50 btn-danger tip-top fr"
                      <?php if($value['type']){?>
                      title="删除此模板文件夹下的所有文件"
                      onclick="return confirm('确定要删除此模板?');"
                      <?php }else{ ?>
                      title="卸载应用会清除应用所有数据！"
                      onclick="return confirm('卸载应用会清除应用所有数据！\n卸载应用会清除应用所有数据！\n卸载应用会清除应用所有数据！\n确定要卸载?\n确定要卸载?\n确定要卸载?');"
                      <?php } ?>
                    >
                      <i class="fa fa-trash-o"></i> 卸载
                    </a>
                  <?php }else{ ?>
                  <a href="<?php echo APP_FURI; ?>&do=<?php echo admincp::$APP_DO; ?>_install&sid=<?php echo $sid;?>"
                    target="iPHP_FRAME" class="btn btn-large btn-primary">
                    <i class="fa fa-download"></i>
                    <?php if($value['premium']){?>
                    付费安装
                    <?php }else{ ?>
                    立即安装
                    <?php } ?>
                  </a>
                  <?php } ?>
                </div>
                <div class="clearfix"></div>
              </div>
            <?php } ?>
          </div>
          <div class="clearfix"></div>
      </form>
    </div>
  </div>
<style>
#store-container{margin-left: -15px;}
.store-item{margin-left: 15px; border: 1px solid #CDCDCD; border-radius: 5px;padding: 8px;margin-bottom: 10px;height: 330px;background-color: #fff;}
.item-head{margin-bottom: 10px;height: 100px; background-color: #333;text-align: center;overflow: hidden;}
.item-head h3{height: 100px;line-height: 90px;font-size: 36px;color: #f5f5f5;}
.item-head p{height: 100px; background-color: #dadada;}

.item-author{height: 60px;}
.item-author .avatar img {width: 60px;height: 60px;border-radius: 30px;float: left;}
.item-author .name{margin-left: 70px; line-height: 30px;}
.item-author .name span.version{color: #666;}
.item-author .name span.size{color: #666;}
.item-author .name b{display: block;}
.item-author .name .demo{font-size: 12px;}
.item-description{height: 70px;overflow: hidden;color: #666;}
.item-description p{font-size: 14px;}
.item-action {margin-top: 10px;}
.item-action .btn-large{ width: 100%; height: 35px;line-height: 35px;padding: 0px;}
.item-action a.btn50{width: 45%;}
@media (min-width:1200px) {
  .store-item{width: 22%;}
}
</style>

<?php admincp::foot();?>
