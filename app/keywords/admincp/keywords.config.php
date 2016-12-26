<?php /**
* @package iCMS
* @copyright 2007-2017, iDreamSoft
* @license http://www.idreamsoft.com iDreamSoft
* @author coolmoo <idreamsoft@qq.com>
*/
defined('iPHP') OR exit('What are you doing?');
configAdmincp::head("内链系统设置");
?>
<div class="input-prepend">
    <span class="add-on">关键字替换</span>
    <input type="text" name="config[limit]" class="span3" id="keyword_limit" value="<?php echo $config['limit'] ; ?>"/>
</div>
<span class="help-inline">内链关键字替换次数 0为不替换，-1全部替换</span>
<?php configAdmincp::foot();?>
