<?php
/**
 * @package iCMS
 * @copyright 2007-2016, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
function tag_list($vars){
	$where_sql ="WHERE status='1' ";
	$map_where = array();
    if(isset($vars['rootid'])){
        $where_sql.= " AND `rootid`='".(int)$vars['rootid']."'";
    }
    if(!isset($vars['tcids']) && isset($vars['tcid'])){
        $where_sql.= iPHP::where($vars['tcid'],'tcid');
    }
    if(isset($vars['tcids']) && !isset($vars['tcid'])){
        iCMS::core('Map');
        iMap::init('category',iCMS_APP_TAG);
        //$where_sql.= iMap::exists($vars['tcid'],'`#iCMS@__tags`.id'); //map 表大的用exists
        $map_where+=iMap::where($vars['tcid']);
    }
    if(isset($vars['tcid!'])){
        $where_sql.= iPHP::where($vars['tcid!'],'tcid','not');
    }

    if(!isset($vars['pids']) && isset($vars['pid'])){
        $where_sql.= iPHP::where($vars['pid'],'pid');
    }
    if(isset($vars['pids']) && !isset($vars['pid'])){
        iCMS::core('Map');
        iMap::init('prop',iCMS_APP_TAG);
        //$where_sql.= iMap::exists($vars['pids'],'`#iCMS@__tags`.id'); //map 表大的用exists
        $map_where+= iMap::where($vars['pids']);
    }
    if(isset($vars['pid!'])){
        $where_sql.= iPHP::where($vars['pid!'],'pid','not');
    }

    if(!isset($vars['cids']) && isset($vars['cid'])){
        $cid = explode(',',$vars['cid']);
        $vars['sub'] && $cid+=iPHP::app("category")->get_cids($cid,true);
        $where_sql.= iPHP::where($cid,'cid');
    }
    if(isset($vars['cids']) && !isset($vars['cid'])){
        $cids = explode(',',$vars['cids']);
        $vars['sub'] && $cids+=iPHP::app("category")->get_cids($vars['cids'],true);

        if($cids){
            iCMS::core('Map');
            iMap::init('category',iCMS_APP_TAG);
            $map_where+=iMap::where($cids);
        }
    }
    if(isset($vars['cid!'])){
        $ncids    = explode(',',$vars['cid!']);
        $vars['sub'] && $ncids+=iPHP::app("category")->get_cids($ncids,true);
        $where_sql.= iPHP::where($ncids,'cid','not');
    }

    if(isset($vars['keywords'])){//最好使用 iCMS:tag:search
        if(empty($vars['keywords'])) return;

        if(strpos($vars['keywords'],',')===false){
            $vars['keywords'] = str_replace(array('%','_'),array('\%','\_'),$vars['keywords']);
            $where_sql.= " AND CONCAT(tkey,name,seotitle,keywords) like '%".addslashes($vars['keywords'])."%'";
        }else{
            $kws = explode(',',$vars['keywords']);
            foreach($kws AS $kwv){
                $keywords.= addslashes($kwv)."|";
            }
            $keywords = substr($keywords,0,-1);
            $where_sql.= " AND CONCAT(tkey,name,seotitle,keywords) REGEXP '$keywords' ";
        }
    }
    $maxperpage	= isset($vars['row'])?(int)$vars['row']:"10";
	$cache_time	= isset($vars['time'])?(int)$vars['time']:-1;
	$by			= $vars['by']=='ASC'?"ASC":"DESC";
	switch ($vars['orderby']) {
		case "hot":		$order_sql=" ORDER BY `count` $by";		break;
		case "new":		$order_sql=" ORDER BY `id` $by";			break;
		case "sort":	$order_sql=" ORDER BY `sortnum` $by";	break;
		default:		$order_sql=" ORDER BY `id` $by";
	}
    if($map_where){
        $map_sql   = iCMS::map_sql($map_where);
        $where_sql = ",({$map_sql}) map {$where_sql} AND `id` = map.`iid`";
    }

	$offset	= 0;
	$limit  = "LIMIT {$maxperpage}";
	if($vars['page']){
		$total	= iPHP::total('sql.md5',"SELECT count(*) FROM `#iCMS@__tags` {$where_sql} ");
		$multi  = iPHP::page(array('total'=>$total,'perpage'=>$maxperpage,'unit'=>iPHP::lang('iCMS:page:list'),'nowindex'=>$GLOBALS['page']));
		$offset = $multi->offset;
		$limit  = "LIMIT {$offset},{$maxperpage}";
        iPHP::assign("tags_list_total",$total);
	}

    if($vars['orderby']=='rand'){
        $ids_array = iCMS::get_rand_ids('#iCMS@__tags',$where_sql,$maxperpage,'id');
    }

	$hash = md5($where_sql.$order_sql.$limit);

	if($vars['cache']){
		$cache_name = iPHP_DEVICE.'/tags/'.$hash;
        $vars['page'] && $cache_name.= "/".(int)$GLOBALS['page'];
		$resource = iCache::get($cache_name);
        if($resource){
            return $resource;
        }
	}
    if($map_sql || $offset){
        if($vars['cache']){
			$map_cache_name = iPHP_DEVICE.'/tags_map/'.$hash;
			$ids_array      = iCache::get($map_cache_name);
        }
        if(empty($ids_array)){
            $ids_array = iDB::all("SELECT `id` FROM `#iCMS@__tags` {$where_sql} {$order_sql} {$limit}");
            $vars['cache'] && iCache::set($map_cache_name,$ids_array,$cache_time);
        }
    }
    if($ids_array){
        $ids       = iPHP::values($ids_array);
        $ids       = $ids?$ids:'0';
        $where_sql = "WHERE `#iCMS@__tags`.`id` IN({$ids})";
        $limit     = '';
    }

	$resource = iDB::all("SELECT * FROM `#iCMS@__tags` {$where_sql} {$order_sql} {$limit}");
	if($resource){
        $resource = tag_array($vars,$resource);
        $vars['cache'] && iCache::set($cache_name,$resource,$cache_time);
    }
	return $resource;
}

function tag_array($vars,$resource=null){
	$tagApp = iPHP::app("tag");
    if($resource===null){
        if(isset($vars['name'])){
            $array = array($vars['name'],'name');
        }else if(isset($vars['id'])){
            $array = array($vars['id'],'id');
        }
        if($array){
            return $tagApp->tag($array[0],$array[1],false);
        }else{
            iPHP::warning('iCMS&#x3a;tag&#x3a;array 标签出错! 缺少参数"id"或"name".');
        }
    }
    if($resource)foreach ($resource as $key => $value) {
		$resource[$key] = $tagApp->value($value);
    }
    return $resource;
}
