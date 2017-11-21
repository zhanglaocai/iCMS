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
    <div class="widget-content nopadding">
      <form action="<?php echo APP_FURI; ?>&do=batch" method="post" class="form-inline" id="<?php echo APP_FORMID;?>" target="iPHP_FRAME">
          <table class="table table-bordered table-condensed table-hover">
            <thead>
              <tr>
                <th style="width:90px;">安装/更新</th>
                <th style="width:auto;">应用信息</th>
              </tr>
            </thead>
            <tbody>
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
            <tr id="store-item-<?php echo $value['app'];?>">
              <td style="vertical-align: middle;">
                <?php if($appconf){?>
                  <a
                  <?php if($is_update){?>
                    title="
                    当前版本:<?php echo $appconf['version'];?>
                    <br />
                    安装时间:<?php echo get_date($appconf['addtime'],'Y-m-d H:i');?>
                    <br />
                    SHA-1:<?php echo $appconf['git_sha'];?>
                    <hr />
                    最新版本:v<?php echo $value['version'];?>
                    <br />
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
                    target="iPHP_FRAME" class="btn btn-success btn-block tip-top">
                    <i class="fa fa-repeat"></i> 更新
                  </a>
                  <p class="clearfix mt5"></p>
                  <a href="<?php echo APP_FURI; ?>&do=<?php echo admincp::$APP_DO; ?>_uninstall&sid=<?php echo $sid;?>&id=<?php echo $appconf['appid'];?>"
                    target="iPHP_FRAME" class="btn btn-danger btn-block tip-top"
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
                  target="iPHP_FRAME" class="btn btn-primary btn-block">
                  <i class="fa fa-download"></i>
                  <?php if($value['premium']){?>
                  付费安装
                  <?php }else{ ?>
                  立即安装
                  <?php } ?>
                </a>
                <?php } ?>
              </td>
              <td>
                <table class="table table-bordered">
                  <tr>
                    <td style="width:60px;">名称</td>
                    <td class="span4"><a href="<?php echo $value['store_url'];?>" target="_blank" class="tip-top" title="查看详细介绍"><b><?php echo $value['title'];?></b></a></td>
                    <td style="width:60px;">开发</td>
                    <td><?php echo $value['author'];?></td>
                  </tr>
                  <?php if($value['premium']){?>
                  <tr>
                    <td>类型</td><td><span class="label label-inverse">付费<?php echo $title;?></span></td>
                    <td>价格</td>
                    <td>
                      <?php if($value['coupon']){?>
                      <span class="badge badge-inverse"><del>原价:<?php echo $value['price'];?><i class="fa fa-rmb"></i></del></span>
                      <span class="badge badge-success">优惠价:<?php echo $value['coupon'];?><i class="fa fa-rmb"></i></span>
                      <?php }else{ ?>
                      <span class="badge badge-success"><?php echo $value['price'];?><i class="fa fa-rmb"></i></span>
                      <?php } ?>
                    </td>
                  </tr>
                  <?php }else{ ?>
                  <tr>
                    <td>类型</td><td colspan="3">免费<?php echo $title;?></td>
                  </tr>
                  <?php } ?>
                  <tr>
                    <td>大小</td><td><?php echo $value['size'];?></td>
                    <td>版本</td><td><?php echo $value['version'];?></td>
                  </tr>
                  <tr>
                    <td>介绍</td><td colspan="3"><?php echo $value['description'];?></td>
                  </tr>
                  <?php if($value['demo']){?>
                  <tr>
                    <td>演示</td>
                    <td colspan="3">
                      <a href="<?php echo $value['demo'];?>" target="_blank">
                        <?php echo $value['demo'];?>
                      </a>
                    </td>
                  </tr>
                  <?php } ?>
                  <?php if($appconf && $value['qq']){?>
                  <tr>
                    <td>技术支持</td>
                    <td colspan="3">
                      <a href="<?php echo $value['demo'];?>" target="_blank">
                        QQ:<?php echo $value['qq'];?>
                      </a>
                    </td>
                  </tr>
                  <?php } ?>
                  <?php if($value['iCMS_VERSION']||$value['iCMS_GIT_TIME']){?>
                  <tr>
                    <?php if($value['iCMS_VERSION']){?>
                    <td>版本要求</td>
                    <td<?php if(!$value['iCMS_GIT_TIME']){?> colspan="3"<?php } ?>>
                      <span class="label label-inverse">iCMS V<?php echo $value['iCMS_VERSION'];?>[<?php echo $value['iCMS_RELEASE'];?>] 及以上版本</span>
                    </td>
                    <?php } ?>
                    <?php if($value['iCMS_GIT_TIME']){?>
                    <td>版本要求</td>
                    <td>
                      <span class="label label-inverse">git:<?php echo get_date($value['iCMS_GIT_TIME'],'Y-m-d H:i');?> 及以上开发版</span>
                    </td>
                    <?php } ?>
                  </tr>
                  <?php } ?>
                  <?php if($appconf){?>
                  <tr>
                    <td>当前</td><td><?php echo $appconf['version'];?></td>
                    <td>安装</td><td><?php echo get_date($appconf['addtime'],'Y-m-d H:i');?></td>
                  </tr>
                    <?php if($is_update){?>
                    <tr>
                      <td>状态</td>
                      <td>
                        <span class="label label-important">发现可用更新</span></td>
                        <td>时间</td><td>
                        <?php if($value['git_time']){?>
                        <?php echo get_date($value['git_time'],'Y-m-d H:i');?>
                        <?php } ?>
                      </td>
                    </tr>
                    <?php } ?>
                  <?php } ?>
                </table>
              </td>
            </tr>
            <tr><td colspan="2" style="height:10px;padding: 0px;"></td></tr>
            <?php } ?>
          </tbody>
          </table>
      </form>
    </div>
  </div>
<style>
.demo{font-size: 12px;}
</style>

<?php admincp::foot();?>
