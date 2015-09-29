<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 * @$Id: search.tpl.php 1392 2013-05-20 12:28:08Z coolmoo $
 */
function search_list($vars){
	$maxperpage = isset($vars['row'])?(int)$vars['row']:"100";
	$cache_time	= isset($vars['time'])?(int)$vars['time']:"-1";
    $where_sql  = '';

	$by=$vars['by']=="ASC"?"ASC":"DESC";
    switch ($vars['orderby']) {
        case "id":      $order_sql = " ORDER BY `id` $by";      break;
        case "addtime":	$order_sql = " ORDER BY `addtime` $by"; break;
        case "times":   $order_sql = " ORDER BY `times` $by";   break;
        default:        $order_sql = " ORDER BY `id` DESC";
    }
	if($vars['cache']){
        $cache_name = iPHP_DEVICE.'/search/'.md5($where_sql.$order_sql);
        $resource   = iCache::get($cache_name);
	}
	if(empty($resource)){
        $resource = iDB::all("SELECT * FROM `#iCMS@__search_log` {$where_sql} {$order_sql} LIMIT $maxperpage");
		iPHP_SQL_DEBUG && iDB::debug(1);
        if($resource)foreach ($resource as $key => $value) {
            $value['name']  = $value['search'];
            $value['url']   = search_url(array('query'=>$value['name'],'ret'=>true));
            $resource[$key] = $value;
        }
		$vars['cache'] && iCache::set($cache_name,$resource,$cache_time);
	}
	return $resource;
}
function search_url($vars){
    $q = rawurlencode($vars['query']);
    if(empty($q)){
        return;
    }
    $query['app'] = 'search';
    if(isset($vars['_app'])){
        $query['app'] = $vars['_app'];
        $query['do']  = 'search';
    }
    $query['q'] = $q;
    $url = iPHP::router('/api',iPHP_ROUTER_REWRITE);
    $url = buildurl($url,$query);
    if($vars['ret']){
        return $url;
    }
    echo $url;
}
