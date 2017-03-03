<?php
/**
 * @package iCMS
 * @copyright 2007-2017, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */

class contentFunc {
    public static $app = null;
    public static $table = null;
    public static $primary = null;
    private static function data($vars){
        self::$app     = apps::get_app($vars['app']);
        self::$table   = apps::get_table($data);
        self::$primary = self::$table['primary'];
    }
    public static function content_list($vars){
        self::data($vars);

    	$maxperpage = isset($vars['row'])?(int)$vars['row']:"100";
    	$cache_time	= isset($vars['time'])?(int)$vars['time']:"-1";

        $where_sql	= "WHERE `status`='1'";

        isset($vars['userid']) && $where_sql.=" AND `userid`='{$vars['userid']}'";

        if(isset($vars['cid!'])){
            $ncids    = explode(',',$vars['cid!']);
            $vars['sub'] && $ncids+=categoryApp::get_cids($ncids,true);
            $where_sql.= iSQL::in($ncids,'cid','not');
        }
        if(isset($vars['cid'])){
            $cid = explode(',',$vars['cid']);
            $vars['sub'] && $cid+=categoryApp::get_cids($cid,true);
            $where_sql.= iSQL::in($cid,'cid');
        }

        isset($vars['pid']) 	&& $where_sql.= " AND `pid` ='{$vars['pid']}'";

    	isset($vars['startdate'])    && $where_sql.=" AND `pubdate`>='".strtotime($vars['startdate'])."'";
    	isset($vars['enddate'])     && $where_sql.=" AND `pubdate`<='".strtotime($vars['enddate'])."'";

    	$by=$vars['by']=="ASC"?"ASC":"DESC";
        switch ($vars['orderby']) {
            case "id":		$order_sql=" ORDER BY `id` $by";			break;
            case "pubdate":	$order_sql=" ORDER BY `pubdate` $by";    break;
            case "sort":    $order_sql=" ORDER BY `sortnum` $by";    break;
            default:        $order_sql=" ORDER BY `id` DESC";
        }
    	if($vars['cache']){
            $cache_name = iPHP_DEVICE.'/'.self::$app['app'].'/'.md5($where_sql);
            $resource   = iCache::get($cache_name);
    	}
    	if(empty($resource)){
            $resource = iDB::all("SELECT * FROM `#iCMS@__push` {$where_sql} {$order_sql} LIMIT $maxperpage");
            if($resource)foreach ($resource as $key => $value) {
                $value['pic']     && $value['pic']  = iFS::fp($value['pic'],'+http');
                $value['pic2']    && $value['pic2'] = iFS::fp($value['pic2'],'+http');
                $value['pic2']    && $value['pic2'] = iFS::fp($value['pic2'],'+http');
                $value['metadata']&& $value['metadata'] = unserialize($value['metadata']);
                $resource[$key] = $value;
            }
    		$vars['cache'] && iCache::set($cache_name,$resource,$cache_time);
    	}
    	return $resource;
    }
}
