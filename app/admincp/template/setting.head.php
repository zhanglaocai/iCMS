<?php /**
* @package iCMS
* @copyright 2007-2017, iDreamSoft
* @license http://www.idreamsoft.com iDreamSoft
* @author coolmoo <idreamsoft@qq.com>
*/
defined('iPHP') OR exit('What are you doing?');
admincp::head();
?>
<div class="iCMS-container">
  <div class="widget-box">
    <div class="widget-title"> <span class="icon"> <i class="fa fa-cog"></i> </span>
      <h5><?php echo $title;?></h5>
    </div>
    <div class="widget-content nopadding iCMS-setting">
      <form action="<?php echo APP_FURI; ?>&do=save_config" method="post" class="form-inline" id="iCMS-setting" target="iPHP_FRAME">
        <div id="setting" class="tab-content">
          <div id="setting-content" class="tab-pane active">
