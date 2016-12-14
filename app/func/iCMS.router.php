<?php
/**
 * @package iCMS
 * @copyright 2007-2017, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
function iCMS_router($vars){
	if(empty($vars['url'])){
		echo 'javascript:;';
		return;
	}
	$router = $vars['url'];
	unset($vars['url'],$vars['app']);
	$url = iPHP::router($router);
	$vars['query'] && $url = buildurl($url,$vars['query']);

	if($url && !iFS::checkHttp($url) && $vars['host']){
		$url = rtrim(iCMS_URL,'/').'/'.ltrim($url, '/');;
	}
	echo $url?$url:'javascript:;';
}
