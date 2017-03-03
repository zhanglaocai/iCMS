<?php
/**
 * @package iCMS
 * @copyright 2007-2017, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
class publicFunc{
	public static function public_ui($vars=null){
		$dir = isset($vars['dir'])?$vars['dir'].'/':'';
		iView::assign("ui",$vars);
		echo iView::render("iCMS://{$dir}public.ui.htm");
	}
	public static function public_seccode($vars=null){
		echo publicApp::seccode();
	}
	public static function public_crontab(){
		echo '<img src="'.iCMS_API.'?app=public&do=crontab&'.$_SERVER['REQUEST_TIME'].'" id="iCMS_public_crontab"/>';
	}
	public static function public_qrcode($vars=null){
		$data  = $vars['data'];
		$query = array('app'=>'public','do'=>'qrcode','url'=>$data);
		isset($vars['cache']) && $query['cache'] = true;
		$url = iURL::router('api');
		echo buildurl($url,$query);
	}
}
