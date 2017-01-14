<?php
/**
 * @package iCMS
 * @copyright 2007-2017, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
function public_ui($vars=null){
	$dir = isset($vars['dir'])?$vars['dir'].'/':'';
	iView::assign("ui",$vars);
	echo iView::render("iCMS://{$dir}public.ui.htm");
}
function public_seccode($vars=null){
	echo iView::render('iCMS://public.seccode.htm');
}
function public_crontab(){
	echo '<img src="'.iCMS_API.'?app=public&do=crontab&'.$_SERVER['REQUEST_TIME'].'" id="iCMS_public_crontab"/>';
}

function public_qrcode($vars=null){
	$data  = $vars['data'];
	$query = array('app'=>'public','do'=>'qrcode','url'=>$data);
	isset($vars['cache']) && $query['cache'] = true;
	$url = iURL::router('api');
	echo buildurl($url,$query);
}
