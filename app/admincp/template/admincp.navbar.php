<?php /**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 * @$Id: navbar.php 2412 2014-05-04 09:52:07Z coolmoo $
 */
defined('iPHP') OR exit('What are you doing?');
?>

<script type="text/javascript">
$(function(){
  var uri = {
    app:'<?php echo admincp::$APP_NAME; ?>',
    ado:'<?php echo admincp::$APP_DO; ?>'
  }
  $("a",".iMenu-nav,.iMenu-sidebar,.iMenu-tabs").each(function(i,a){
    if(this.href==window.location.href){
      find_parent(this);
      return;
    }else{
      var pos   = this.href.indexOf('?')+1;
      var query = this.href.substring(pos);
      var args  = get_args(query);
      if(args['app']==uri.app && args['do']==uri.ado){
        find_parent(this);
        return;
      }
    }
  });
  $("li.active>a","#iCMS-menu").each(function(){
    var a = $(this).clone();
    a.removeClass('dropdown-toggle').removeAttr('data-toggle');
    $("#breadcrumb").append(a);
  });

  function find_parent (a) {
    var p = $(a).parent();
    if(p[0].nodeName=="LI"||p[0].nodeName=="UL"){
      $(p).addClass("active");
      if(p[0].nodeName=="UL" && !$(p).hasClass("dropdown-menu")){
        $(p).addClass("open").show();
      }
      find_parent(p[0]);
    }
  }
  function get_args(query) {
      var args = new Object( );
      var query = query||location.search.substring(1);
      var pairs = query.split("&");
      for(var i = 0; i < pairs.length; i++) {
          var pos = pairs[i].indexOf('=');
          if (pos == -1) continue;
          var argname = pairs[i].substring(0,pos);
          var value = pairs[i].substring(pos+1);
          value = decodeURIComponent(value);
          args[argname] = value;
      }
      return args;
  }
})
</script>

<div id="header" class="navbar navbar-static-top">
  <div class="navbar-inner">
    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
    <span class="fa fa-bars"></span>
    </a>
    <a class="brand iCMS-logo" href="http://www.idreamsoft.com" target="_blank">
      <img src="./app/admincp/ui/iCMS.logo.mini.png" />
    </a>
      <div class="nav-collapse collapse">
        <ul class="nav iMenu-nav" id="iCMS-menu">
          <?php echo admincp::$menu->nav(); ?>
        </ul>
        <ul class="nav pull-right">
          <li><a href="<?php echo iCMS_URL;?>" target="_blank" title="网站首页"><i class="fa fa-home fa-lg"></i></a></li>
          <li class="divider-vertical"></li>
          <li class="dropdown"> <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" title="<?php echo iMember::$data->nickname;?>"><i class="fa fa-user"></i> <b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li><a href="javascript:;"><?php echo iMember::$group->name;?></a></li>
              <li class="divider"></li>
              <li><a href="<?php echo __ADMINCP__; ?>=account&do=job"><i class="fa fa-bar-chart-o"></i> 工作统计</a></li>
              <li><a href="<?php echo __ADMINCP__; ?>=account&do=edit"><i class="fa fa-user"></i> 更改信息</a></li>
              <li class="divider"></li>
              <!--li class="nav-header">导航头</li-->
              <li><a href="<?php echo __ADMINCP__; ?>=home&do=logout&frame=iPHP" target="iPHP_FRAME"><i class="fa fa-sign-out"></i> 注销</a></li>
            </ul>
          </li>
        </ul>
      </div>
  </div>
</div>
<?php if(iCMS::$config['other']['sidebar_enable']){?>
<div id="sidebar" class="navbar">
	<div id="sidebartop" class="navbar-inner">
    <a class="brand iCMS-logo" href="http://www.idreamsoft.com" target="_blank">
    <img src="./app/admincp/ui/iCMS.logo.mini.png" />
    </a>
  </div>
  <ul class="iMenu-sidebar">
    <?php echo admincp::$menu->sidebar(); ?>
    <li class="last"></li>
  </ul>
  <div class="clearfloat"></div>
  <span id="mini"> <i class="fa fa-arrow-circle-left"></i> </span>
</div>
<?php }?>
<div id="content">
  <div id="breadcrumb">
    <a href="<?php echo __SELF__; ?>" title="返回管理首页" class="tip-bottom"><i class="fa fa-home"></i> 管理中心</a>
  </div>

