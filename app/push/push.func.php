<?php
/**
 * @package iCMS
 * @copyright 2007-2017, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
class pushFunc{
    public static function push_list($vars){
    	$maxperpage = isset($vars['row'])?(int)$vars['row']:"100";
    	$cache_time	= isset($vars['time'])?(int)$vars['time']:"-1";

        $where_sql	= "WHERE `status`='1'";

        isset($vars['userid'])    &&     $where_sql.=" AND `userid`='{$vars['userid']}'";

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

        isset($vars['pid'])  && $where_sql.= " AND `pid` ='{$vars['pid']}'";
        isset($vars['pid!']) && $where_sql.= " AND `pid` !='{$vars['pid!']}'";
        isset($vars['pic'])  && $where_sql.= " AND `haspic`='1'";
        isset($vars['nopic'])&& $where_sql.= " AND `haspic`='0'";

    	isset($vars['startdate'])    && $where_sql.=" AND `addtime`>='".strtotime($vars['startdate'])."'";
    	isset($vars['enddate'])     && $where_sql.=" AND `addtime`<='".strtotime($vars['enddate'])."'";

    	$by=$vars['by']=="ASC"?"ASC":"DESC";
        switch ($vars['orderby']) {
            case "id":		$order_sql=" ORDER BY `id` $by";			break;
            case "addtime":	$order_sql=" ORDER BY `addtime` $by";    break;
            case "sort":$order_sql=" ORDER BY `sortnum` $by";    break;
            default:        $order_sql=" ORDER BY `id` DESC";
        }
    	if($vars['cache']){
            $cache_name = iPHP_DEVICE.'/push/'.md5($where_sql);
            $resource   = iCache::get($cache_name);
    	}
    	if(empty($resource)){
            $resource = iDB::all("SELECT * FROM `#iCMS@__push` {$where_sql} {$order_sql} LIMIT $maxperpage");
            if($resource)foreach ($resource as $key => $value) {
                $value['pic']     && $value['pic']  = iFS::fp($value['pic'],'+http');
                $value['pic2']    && $value['pic2'] = iFS::fp($value['pic2'],'+http');
                $value['pic2']    && $value['pic2'] = iFS::fp($value['pic2'],'+http');
                $resource[$key] = $value;
            }
    		$vars['cache'] && iCache::set($cache_name,$resource,$cache_time);
    	}
    	return $resource;
    }
}
