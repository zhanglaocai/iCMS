<?php /**
* @package iCMS
* @copyright 2007-2010, iDreamSoft
* @license http://www.idreamsoft.com iDreamSoft
* @author coolmoo <idreamsoft@qq.com>
* @$Id: setting.php 2412 2014-05-04 09:52:07Z coolmoo $
*/
defined('iPHP') OR exit('What are you doing?');
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
  <div id="setting-weixin" class="tab-pane hide">
    <h3 class="title">微信公众平台</h3>
    <span class="help-inline">
      申请地址:https://mp.weixin.qq.com/
    </span>
    <div class="clearfloat">
    </div>
    <div class="input-prepend">
      <span class="add-on">
        appID
      </span>
      <input type="text" name="config[api][weixin][appid]" class="span3" id="weixin_appid" value="<?php echo $config['api']['weixin']['appid'] ; ?>"/>
    </div>
    <div class="clearfloat mt10">
    </div>
    <div class="input-prepend">
      <span class="add-on">
        appsecret
      </span>
      <input type="text" name="config[api][weixin][appsecret]" class="span3" id="weixin_appsecret" value="<?php echo $config['api']['weixin']['appsecret'] ; ?>"/>
    </div>
    <div class="clearfloat mt10">
    </div>
    <div class="input-prepend input-append">
      <span class="add-on">
        Token(令牌)
      </span>
      <input type="text" name="config[api][weixin][token]" class="span3" id="weixin_token" value="<?php echo $config['api']['weixin']['token'] ; ?>"/>
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
        <input disabled type="text" class="span7" id="weixin_interface" value="<?php echo iCMS::$config['router']['public_url'] ; ?>/api.php?app=weixin&do=interface&api_token=<?php echo $config['api']['weixin']['token']?$config['api']['weixin']['token']:'Token(令牌)' ; ?>"/>
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
      <input type="text" name="config[api][weixin][name]" class="span3" id="weixin_name" value="<?php echo $config['api']['weixin']['name'] ; ?>"/>
    </div>
    <div class="clearfloat mt10">
    </div>
    <div class="input-prepend">
      <span class="add-on">
        微信号
      </span>
      <input type="text" name="config[api][weixin][account]" class="span3" id="weixin_account" value="<?php echo $config['api']['weixin']['account'] ; ?>"/>
    </div>
    <div class="clearfloat mt10">
    </div>
    <div class="input-prepend">
      <span class="add-on">
        二维码
      </span>
      <input type="text" name="config[api][weixin][qrcode]" class="span3" id="weixin_qrcode" value="<?php echo $config['api']['weixin']['qrcode'] ; ?>"/>
    </div>
    <span class="help-inline">
      公众号的二维码链接
    </span>
    <hr />
    <div class="input-prepend">
      <span class="add-on">
        关注事件
      </span>
      <textarea name="config[api][weixin][subscribe]" id="weixin_subscribe" class="span6" style="height: 90px;"><?php echo $config['api']['weixin']['subscribe'] ; ?></textarea>
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
      <textarea name="config[api][weixin][unsubscribe]" id="weixin_unsubscribe" class="span6" style="height: 90px;"><?php echo $config['api']['weixin']['unsubscribe'] ; ?></textarea>
    </div>
    <div class="clearfloat"></div>
    <span class="help-inline">
      用户取消关注后的信息回复，留空将使用系统默认信息回复
    </span>
    <div class="mt20">
    </div>
    <hr />
    <h3 class="title">自定义菜单</h3>
    <style>
.weixin-menu .add-on{width: 36px;}
.weixin-menu select{width: 120px;}
    </style>
  <div class="weixin-menu">
    <?php
      function get_type($type,$out='value'){
        $type_map = array(
          'click'              =>'key',
          'view'               =>'url',
          'scancode_push'      =>'key',
          'scancode_waitmsg'   =>'key',
          'pic_sysphoto'       =>'key',
          'pic_photo_or_album' =>'key',
          'pic_weixin'         =>'key',
          'location_select'    =>'key',
          'media_id'           =>'media_id',
          'view_limited'       =>'media_id'
        );
        if($out=='value'){
          empty($type) && $type='click';
          return $type_map[$type];
        }
        if($out=='opt'){
          $option = '';
          foreach ($type_map as $key => $value) {
            $seltext = '';
            if($type==$key){
              $seltext =' selected="selected"';
            }
            $option.='<option value="'.$key.'"'.$seltext.'>'.$key.'</option>';
          }
          return $option;
        }
      }
      function wx_button_li($key='~KEY~',$i='~i~',$a=array()){
        $keyname = get_type($a['type']);
        $html = '<li>'.
          '<div class="input-prepend input-append">'.
            '<span class="add-on">类型</span>'.
            '<select name="wx_button['.$key.'][sub_button]['.$i.'][type]">'.
              get_type($a['type'],'opt').
            '</select>'.
            '<span class="add-on">名称</span>'.
            '<input type="text" name="wx_button['.$key.'][sub_button]['.$i.'][name]" value="'.$a['name'].'">'.
            '<span class="button_key">'.
              '<span class="add-on">'.strtoupper($keyname).'</span>'.
              '<input type="text" name="wx_button['.$key.'][sub_button]['.$i.']['.$keyname.']" value="'.$a[$keyname].'">'.
            '</span>'.
            '<a href="javascript:void(0);" class="btn wx_del_sub_button"><i class="fa fa-del"></i>删除</a>'.
          '</div>'.
        '</li>';
        return $html;
      }
    ?>

    <ul id="tree" class="treeview wx_button_tree">
      <?php
        $wx_button = $config['api']['weixin']['menu'];
        for ($i=0; $i <3 ; $i++) {
          $type = $wx_button[$i]['type'];
          $keyname = get_type($type);
      ?>
      <li>
        <div class="input-prepend input-append">
            <span class="add-on">类型</span>
            <select name="wx_button[<?php echo $i;?>][type]">
              <?php echo get_type($type,'opt');?>
            </select>
          <span class="add-on">名称</span><input type="text" name="wx_button[<?php echo $i;?>][name]" value="<?php echo $wx_button[$i]['name'];?>">
          <span class="button_key <?php if(empty($wx_button[$i]['sub_button'])){ echo 'hide'; }?>">
            <span class="add-on"><?php echo strtoupper($keyname);?></span><input type="text" name="wx_button[<?php echo $i;?>][<?php echo $keyname;?>]" value="<?php echo $wx_button[$i][$keyname];?>">
          </span>

          <a href="javascript:void(0);" subkey="<?php echo $i;?>" class="btn addsub"/><i class="fa fa-plus"></i> 子菜单</a>
        </div>
        <ul class="sub_button sub_button_<?php echo $i;?>">
          <?php
          foreach ((array)$wx_button[$i]['sub_button'] as $key => $value) {
            echo wx_button_li($i,$key,$value);
          }
          ?>
        </ul>
      </li>
      <?php } ?>
    </ul>
  </div>

<link rel="stylesheet" href="./app/admincp/ui/jquery/treeview-0.1.0.css" type="text/css" />
<script type="text/javascript" src="./app/admincp/ui/jquery/treeview-0.1.0.js"></script>
<script type="text/javascript">
$(function(){
    $("#tree").treeview({collapsed: false});
    $(".addsub").click(function(){
      var that = this;
      $(this).prev('.button_key').hide();
      var subkey = $(this).attr("subkey");
      var ul   = $('.sub_button_'+subkey);
      var length = $("li",ul).size();
      if(length>4){
        iCMS.alert("每个一级菜单最多包含5个二级菜单");
        return false;
      }
      var clone  = '<?php echo html2js(wx_button_li());?>';
      ul.append(clone);

      $('li',ul).each(function(i){
        $('[name^=wx_button]',this).each(function(ii){
          this.name = this.name.replace('~KEY~',subkey);
          this.name = this.name.replace('~i~',i);
        });
      });
      return false;
    });
    var doc = $('ul.wx_button_tree');
    doc.on("click",'.wx_del_sub_button',function() {
      var li = $(this).parent().parent(),ul=li.parent();
      li.remove();
      var length = $("li",ul).size();
      if(length<1){
        ul.siblings('div').find('.button_key').show();
      }
    });

    doc.on("change",'select',function() {
        var button_key = $(this).siblings('.button_key');
        var name = $('input',button_key).attr('name');
        name = name.replace('[url]','[key]');
        name = name.replace('[media_id]','[key]');
        var text = 'KEY';
        switch (this.value) {
          case 'view':
            text = 'URL';
            name = name.replace('[key]','[url]');
          break;
          case 'media_id':
          case 'view_limited':
            text = 'media_id';
            name = name.replace('[key]','[media_id]');
          break;

        }
        $('.add-on',button_key).text(text);
        $('input',button_key).attr('name',name);
    });

});
</script>
    <div class="mt20"></div>
    <div class="alert alert-block">
      <h4>注意事项</h4>
      微信功能目前只能接收关键字并自动回复相关信息．其它功能在开发中．．．．
<hr />
1、自定义菜单最多包括3个一级菜单，每个一级菜单最多包含5个二级菜单。<br />
2、一级菜单最多4个汉字，二级菜单最多7个汉字，多出来的部分将会以“...”代替。<br />
3、创建自定义菜单后，菜单的刷新策略是，在用户进入公众号会话页或公众号profile页时，如果发现上一次拉取菜单的请求在5分钟以前，就会拉取一下菜单，如果菜单有更新，就会刷新客户端的菜单。测试时可以尝试取消关注公众账号后再次关注，则可以看到创建后的效果。

    </div>
  </div>
