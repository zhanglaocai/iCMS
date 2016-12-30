<?php
/**
 * @package iCMS
 * @copyright 2007-2017, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
function category_array($vars){
	$cid = (int)$vars['cid'];
	return iPHP::app("category")->category($cid,false);
}
function category_list($vars){
	$appid      = isset($vars['appid'])?(int)$vars['appid']:iCMS_APP_ARTICLE;
	$row        = isset($vars['row'])?(int)$vars['row']:"100";
	$cache_time = isset($vars['time'])?(int)$vars['time']:"-1";
	$status     = isset($vars['status'])?(int)$vars['status']:"1";
	$maxperpage	= isset($vars['row'])?(int)$vars['row']:"10";
	$where_sql  =" WHERE `appid`='$appid' AND `status`='$status'";
	$resource   = array();

	isset($vars['mode']) && $where_sql.=" AND `mode` = '{$vars['mode']}'";
	isset($vars['cid']) && !isset($vars['stype']) && $where_sql.= iPHP::where($vars['cid'],'cid');
	isset($vars['cid!']) && $where_sql.= iPHP::where($vars['cid!'],'cid','not');
	switch ($vars['stype']) {
		case "top":
			$vars['cid'] && $where_sql.= iPHP::where($vars['cid'],'cid');
			$where_sql.=" AND rootid='0'";
		break;
		case "sub":
			$vars['cid'] && $where_sql.= iPHP::where($vars['cid'],'rootid');
		break;
		// case "subtop":
		// 	$vars['cid'] && $where_sql.= iPHP::where($vars['cid'],'cid');
		// break;
		case "suball":
			$cids = iPHP::app("category")->get_cids($vars['cid'],false);
			$where_sql.= iPHP::where($cids,'cid');
		break;
		case "self":
			$parent = iCache::get('iCMS/category/parent',$vars['cid']);
			$where_sql.=" AND `rootid`='$parent'";
		break;
	}
	if(isset($vars['pids'])){
		iMap::init('prop',iCMS_APP_CATEGORY);
		$where_sql.= iMap::exists($vars['pids'],'`#iCMS@__category`.cid'); //主表小 map表大
//		$map_where=iMap::where($vars['pids']); //主表大 map表大
//		$map_ids    = iMap::ids($vars['pid']);
//		$map_sql    = iMap::sql($vars['pid']); //map 表小的用 in
//		$where_sql.=" AND `pid` = '{$vars['pid']}'";
		//if(empty($map_ids)) return $resource;
		//$where_sql.=" AND `cid` IN ($map_ids)";
		//$where_sql.=" AND `cid` IN ($map_sql)";
	}
	$by = $vars['by']=='ASC'?"ASC":"DESC";

	switch ($vars['orderby']) {
		case "hot":		$order_sql=" ORDER BY `count` $by";		break;
		case "new":		$order_sql=" ORDER BY `cid` $by";			break;
		default:		$order_sql=" ORDER BY `sortnum` $by";
	}

	$offset	= 0;
	$limit  = "LIMIT {$maxperpage}";
	if($vars['page']){
		$total	= iPHP::total('sql.md5',"SELECT count(*) FROM `#iCMS@__category` {$where_sql} ");
		$multi  = iPHP::page(array('total'=>$total,'perpage'=>$maxperpage,'unit'=>iUI::lang('iCMS:page:list'),'nowindex'=>$GLOBALS['page']));
		$offset = $multi->offset;
		$limit  = "LIMIT {$offset},{$maxperpage}";
		iPHP::assign("category_list_total",$total);
	}

    if($vars['orderby']=='rand'){
        $ids_array = iCMS::get_rand_ids('#iCMS@__category',$where_sql,$maxperpage,'cid');
    }

	$hash = md5($where_sql.$order_sql.$limit);

	if($vars['cache']){
		$cache_name = iPHP_DEVICE.'/category/'.$hash;
        $vars['page'] && $cache_name.= "/".(int)$GLOBALS['page'];
		$resource = iCache::get($cache_name);
        if($resource){
            return $resource;
        }
	}

	$resource = iDB::all("SELECT `cid` FROM `#iCMS@__category` {$where_sql} {$order_sql} {$limit}");
	if($resource){
		$categoryApp = iPHP::app("category");
		foreach ($resource as $key => $value) {
			$cate = iCache::get(CACHE_CATEGORY_ID.$value['cid']);
			$cate && $resource[$key] = $categoryApp->get_lite($cate);
		}
	}
	$vars['cache'] && iCache::set($cache_name,$resource,$cache_time);

	return $resource;
}
