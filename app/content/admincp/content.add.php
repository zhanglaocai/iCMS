<?php /**
* @package iCMS
* @copyright 2007-2017, iDreamSoft
* @license http://www.idreamsoft.com iDreamSoft
* @author coolmoo <idreamsoft@qq.com>
*/
defined('iPHP') OR exit('What are you doing?');
$preview = isset($_GET['preview']);
admincp::head(!$preview);
?>
<div class="iCMS-container">
  <div class="widget-box" id="<?php echo APP_BOXID;?>">
    <div class="widget-title">
      <span class="icon"> <i class="fa fa-pencil"></i> </span>
      <?php if($preview){?>
            <h5 class="brs">预览表单</h5>
      <?php }else{ ?>
            <h5 class="brs"><?php echo ($this->id?'添加':'修改'); ?><?php echo $app['name'];?></h5>
      <?php } ?>
      <ul class="nav nav-tabs" id="-add-tab">
        <li class="active"><a href="#-add-base" data-toggle="tab"><i class="fa fa-info-circle"></i> 基本信息</a></li>
      </ul>
    </div>
    <div class="widget-content nopadding">
      <form action="<?php echo APP_FURI; ?>&do=save" method="post" class="form-inline" id="<?php echo APP_FORMID;?>" target="iPHP_FRAME">
        <input id="appid" name="appid" type="hidden"  value="<?php echo $appid;?>" />
        <input name="REFERER" type="hidden" value="<?php echo iPHP_REFERER ; ?>" />
        <div class="tab-content">
          <div id="-add-base" class="tab-pane active">
            <?php echo iFormer::$html;?>
          </div>
        </div>
        <?php if($preview){?>
        <?php }else{ ?>
        <?php }?>
        <div class="form-actions">
          <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i> 提交</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script>
$(function(){
  $("#<?php echo APP_FORMID;?>").submit(function(){
      <?php echo iFormer::$validate;?>

  });
  <?php echo iFormer::$script;?>
})
</script>
<?php admincp::foot();?>
