<?php
/**
 * @package iCMS
 * @copyright 2007-2017, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
function iCMS_lang($vars){
	if(empty($vars['key']))return;

	echo iUI::lang($vars['key']);
}
