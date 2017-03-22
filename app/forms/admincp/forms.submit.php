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
            <h5 class="brs"><?php echo ($this->id?'添加':'修改'); ?><?php echo $app['title'];?></h5>
      <?php } ?>
    </div>
    <div class="widget-content">
      <form action="<?php echo APP_FURI; ?>&do=savedata" method="post" class="form-inline" id="<?php echo APP_FORMID;?>" target="iPHP_FRAME">
        <input id="form_id" name="form_id" type="hidden"  value="<?php echo $this->form_id;?>" />
        <input name="REFERER" type="hidden" value="<?php echo iPHP_REFERER ; ?>" />
        <?php
          echo former::head();
          echo former::form();
        ?>
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
<?php admincp::foot();?>
