<?php /**
* @package iCMS
* @copyright 2007-2017, iDreamSoft
* @license http://www.idreamsoft.com iDreamSoft
* @author coolmoo <idreamsoft@qq.com>
*/
defined('iPHP') OR exit('What are you doing?');
admincp::head();
?>
<script>
$(function () {
  $("#weixin_token_make").click(function(event) {
    var token = iCMS.random(20);
    $("#weixin_token").val(token);
    $("#weixin_interface").val('<?php echo iCMS::$config['router']['public_url'] ; ?>/api.php?app=weixin&do=interface&api_token='+token);
  });
  $("#weixin_token").keypress(function(event) {
    $("#weixin_interface").val('<?php echo iCMS::$config['router']['public_url'] ; ?>/api.php?app=weixin&do=interface&api_token='+this.value);
  });
})
</script>
<div class="iCMS-container">
  <div class="widget-box">
    <div class="widget-title"> <span class="icon"> <i class="fa fa-cog"></i> </span>
      <h5>配置微信接口</h5>
      <span class="label right">申请地址:https://mp.weixin.qq.com/</span>
    </div>
    <div class="widget-content nopadding iCMS-setting">
      <form action="<?php echo APP_FURI; ?>&do=save_config" method="post" class="form-inline" id="iCMS-setting" target="iPHP_FRAME">
        <div id="setting" class="tab-content">
          <div id="setting-content" class="tab-pane active">
            <div class="input-prepend">
              <span class="add-on">
                appID
              </span>
              <input type="text" name="config[appid]" class="span3" id="weixin_appid" value="<?php echo $config['appid'] ; ?>"/>
            </div>
            <div class="clearfloat mt10">
            </div>
            <div class="input-prepend">
              <span class="add-on">
                appsecret
              </span>
              <input type="text" name="config[appsecret]" class="span3" id="weixin_appsecret" value="<?php echo $config['appsecret'] ; ?>"/>
            </div>
            <div class="clearfloat mt10">
            </div>
            <div class="input-prepend input-append">
              <span class="add-on">
                Token(令牌)
              </span>
              <input type="text" name="config[token]" class="span3" id="weixin_token" value="<?php echo $config['token'] ; ?>"/>
              <a class="btn" id="weixin_token_make">
                生成令牌
              </a>
            </div>
            <div class="clearfloat mt10">
            </div>
            <div id="wxmp_interface">
              <div class="input-prepend input-append">
                <span class="add-on">
                  接口URL
                </span>
                <input disabled type="text" class="span7" id="weixin_interface" value="<?php echo iCMS::$config['router']['public_url'] ; ?>/api.php?app=weixin&do=interface&api_token=<?php echo $config['token']?$config['token']:'Token(令牌)' ; ?>"/>
                <a class="btn" href="http://www.idreamsoft.com/doc/iCMS/weixin_interface.html" target="_blank">
                  <i class="fa fa-question-circle"></i> 配置帮助
                </a>
              </div>
              <div class="clearfloat mt10">
              </div>
            </div>
            <div class="clearfloat mt10">
            </div>
            <div class="input-prepend">
              <span class="add-on">
                名称
              </span>
              <input type="text" name="config[name]" class="span3" id="weixin_name" value="<?php echo $config['name'] ; ?>"/>
            </div>
            <div class="clearfloat mt10">
            </div>
            <div class="input-prepend">
              <span class="add-on">
                微信号
              </span>
              <input type="text" name="config[account]" class="span3" id="weixin_account" value="<?php echo $config['account'] ; ?>"/>
            </div>
            <div class="clearfloat mt10">
            </div>
            <div class="input-prepend">
              <span class="add-on">
                二维码
              </span>
              <input type="text" name="config[qrcode]" class="span3" id="weixin_qrcode" value="<?php echo $config['qrcode'] ; ?>"/>
            </div>
            <span class="help-inline">
              公众号的二维码链接
            </span>
            <hr />
            <div class="input-prepend">
              <span class="add-on">
                关注事件
              </span>
              <textarea name="config[subscribe]" id="weixin_subscribe" class="span6" style="height: 90px;"><?php echo $config['subscribe'] ; ?></textarea>
            </div>
            <div class="clearfloat"></div>
            <span class="help-inline">
              用户未关注时，进行关注后的信息回复，留空将使用系统默认信息回复
            </span>
            <div class="clearfloat mt10">
            </div>
            <div class="input-prepend">
              <span class="add-on">
                取消关注
              </span>
              <textarea name="config[unsubscribe]" id="weixin_unsubscribe" class="span6" style="height: 90px;"><?php echo $config['unsubscribe'] ; ?></textarea>
            </div>
            <div class="clearfloat"></div>
            <span class="help-inline">
              用户取消关注后的信息回复，留空将使用系统默认信息回复
            </span>
            <div class="form-actions">
              <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i> 提交</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<?php admincp::foot();?>
