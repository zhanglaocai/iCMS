<?php
/**
 * @package iCMS
 * @copyright 2007-2017, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
class linksFunc{
	public static function links_list($vars){
		$limit      = isset($vars['row'])?(int)$vars['row']:"100";
		$cache_time = isset($vars['time'])?(int)$vars['time']:-1;

		switch($vars['type']){
			case "text":$sql[]=" `logo`='' ";break;
			case "logo":$sql[]=" `logo`!='' ";break;
		}
		isset($vars['cid']) && $sql[]=" cid='".$vars['cid']."'";
		$sql && $where ='WHERE '.implode(' AND ',$sql);
		$iscache	= true;
		if($vars['cache']==false||isset($vars['page'])){
			$iscache= false;
			$rs 	= '';
		}else{
			$cacheName	= 'links/'.md5($sql);
			$rs			= iCache::get($cacheName);
		}
		if(empty($rs)){
			$rs=iDB::all("SELECT * FROM `#iCMS@__links`{$where} ORDER BY sortnum ASC,id ASC LIMIT 0 , $limit");
			$iscache && iCache::set($cacheName,$rs,$cache_time);
		}
		return $rs;
	}
}
