<?php /**
 * @package iCMS
 * @copyright 2007-2017, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
defined('iPHP') OR exit('What are you doing?');
?>
<?php
if($this->category_template)foreach ($this->category_template as $key => $value) {
    $template_id = 'template_'.$key;
?>
<div class="input-prepend input-append"> <span class="add-on"><?php echo $value[0];?>模板</span>
  <input type="text" name="template[<?php echo $key;?>]" class="span3" id="<?php echo $template_id;?>" value="<?php echo $rs['template'][$key]?$rs['template'][$key]:$value[1];; ?>"/>
  <?php echo filesAdmincp::modal_btn('模板',$template_id);?>
</div>
<div class="clearfloat mb10"></div>
<?php }?>
