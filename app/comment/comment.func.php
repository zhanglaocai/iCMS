<?php
/**
 * @package iCMS
 * @copyright 2007-2017, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
defined('iPHP') OR exit('What are you doing?');

function comment_user_info($uid=0,$username=null,$facesize=null){
	return user::info($uid,$username,$facesize);
}

function comment_array($vars){
	$where_sql = " `status`='1'";
	$is_multi = false;
	if(isset($vars['id'])){
		if(is_array($vars['id'])){
			$is_multi = true;
			$where_sql.= iPHP::where($vars['id'],'id',false,false);
		}else{
			$where_sql.= " AND `id`='".(int)$vars['id']."'";
		}
	}
	isset($vars['userid']) && $where_sql.= " AND `userid`='".(int)$vars['userid']."'";
	$rs = iDB::all("SELECT * FROM `#iCMS@__comment` WHERE {$where_sql}",ARRAY_A);
	if($is_multi){
		$_count = count($rs);
        for ($i=0; $i < $_count; $i++) {
        	$data[$rs[$i]['id']] = $rs[$i];
        	$data[$rs[$i]['id']]['user']= comment_user_info($rs[$i]['userid'],$rs[$i]['username'],$vars['facesize']);;
        }
	}else{
		$data = $rs[0];
		$data['user'] = comment_user_info($data['userid'],$data['username'],$vars['facesize']);
	}
	return $data;
}
function comment_list_display($vars){
	$vars['do']          = 'list';
	$vars['page_ajax']   = 1;
	$vars['total_cahce'] = 1;
	$tpl = 'list.default';
	isset($vars['_display']) && $vars['display'] = $vars['_display'];
	unset($vars['method'],$vars['_display']);
	$vars['query'] = http_build_query($vars);
	$vars['param'] = array(
		'suid'  => $vars['suid'],
		'iid'   => $vars['iid'],
		'cid'   => $vars['cid'],
		'appid' => $vars['appid'],
		'title' => $vars['title'],
	);
	iPHP::assign('comment_vars',$vars);
	echo iPHP::view("iCMS://comment/{$tpl}.htm");
}
function comment_list($vars){
	if(iCMS::$config['comment']['plugin']['changyan']['enable']){
		return;
	}

	// if(!isset($vars['ref'])){
	// 	$_vars = iPHP::app_vars(true);
	// 	$vars  = array_merge($vars,$_vars);
	// 	unset($vars['ref'],$_vars);
	// }

	if ($vars['display'] && empty($vars['loop'])) {
		$_vars = iPHP::app_vars(true);
		$vars  = array_merge($vars,$_vars);
		$vars['iid']   OR iUI::warning('iCMS&#x3a;comment&#x3a;list 标签出错! 缺少参数"iid"或"iid"值为空.');
		$vars['appid'] OR iUI::warning('iCMS&#x3a;comment&#x3a;list 标签出错! 缺少参数"appid"或"appid"值为空.');
		return comment_list_display($vars);
	}

	$where_sql = " `status`='1'";
	if(isset($vars['appid'])){
		$appid    = (int)$vars['appid'];
		$where_sql.= " AND `appid`='$appid'";
	}
    if(isset($vars['cid!'])){
    	$ncids    = explode(',',$vars['cid!']);
        $vars['sub'] && $ncids+=categoryApp::get_cids($ncids,true);
        $where_sql.= iPHP::where($ncids,'cid','not');
    }
    if(isset($vars['cid'])){
        $cid = explode(',',$vars['cid']);
        $vars['sub'] && $cid+=categoryApp::get_cids($cid,true);
        $where_sql.= iPHP::where($cid,'cid');
    }
    isset($vars['userid'])&& $where_sql.= " AND `userid`='{$vars['userid']}'";

	$vars['pid'] && $where_sql .=" AND `pid`='".(int)$vars['pid']."'";
	$vars['iid'] && $where_sql .=" AND `iid`='".(int)$vars['iid']."'";
	$vars['id']  && $where_sql .=" AND `id`='".(int)$vars['id']."'";

	$maxperpage	= isset($vars['row'])?(int)$vars['row']:"10";
	$cache_time	= isset($vars['time'])?(int)$vars['time']:-1;
	$by			= $vars['by']=='ASC'?"ASC":"DESC";
	switch ($vars['orderby']) {
		default: $order_sql = " ORDER BY `id` $by";
	}
	$md5	= md5($where_sql.$order_sql);
	$offset	= 0;
	$limit  = "LIMIT {$maxperpage}";
	if($vars['page']){
		isset($vars['total_cache']) && $_GET['total_cahce'] = true;
		$total  = iPHP::total($md5,"SELECT count(*) FROM `#iCMS@__comment` WHERE {$where_sql} limit 1");
		$pgconf = array(
			'total'     => $total,
			'perpage'   => $maxperpage,
			'unit'      => iUI::lang('iCMS:page:comment'),
			'ajax'      => $vars['page_ajax']?'comment_page_ajax':FALSE,
			'nowindex'  => $GLOBALS['page'],
		);
		if($vars['display'] == 'iframe' || $vars['page_ajax']){
			iSecurity::gp('pn','GP',2);
			$pgconf['page_name'] = 'pn';
			$pgconf['nowindex']  = $GLOBALS['pn'];
		}

		isset($vars['total_cache']) && $pgconf['total_type'] = $vars['total_cache'];

		$multi  = iPHP::page($pgconf);
		$offset = $multi->offset;
		$limit  = "LIMIT {$offset},{$maxperpage}";
		// if($offset>1000){
			//$where_sql.=" AND `id` >= (SELECT `id` FROM `#iCMS@__comment` WHERE {$where_sql} {$order_sql} LIMIT {$offset},1)";
			//$limit  = "LIMIT {$maxperpage}";
		// }
		iPHP::assign("comment_list_total",$total);
	}
	if($vars['cache']){
		$cache_name = iPHP_DEVICE.'/comment/'.$md5."/".(int)$offset;
		$resource   = iCache::get($cache_name);
	}
	if(empty($resource)){
		$resource = iDB::all("SELECT * FROM `#iCMS@__comment` WHERE {$where_sql} {$order_sql} {$limit}");
        if($vars['reply']){
            $ridArray = iPHP::values($resource,'reply_id','array',null);
            if($ridArray){
            	$rkey = array_search (0,$ridArray);
            	unset($ridArray[$rkey]);
            }
            $ridArray && $reply_array = comment_array(array('id'=>$ridArray));
        }
		$ln = ($pgconf['nowindex']-1)<0?0:$pgconf['nowindex']-1;

		if($resource)foreach ($resource as $key => $value) {
			if($vars['date_format']){
				$value['addtime'] = get_date($value['addtime'],$vars['date_format']);
			}
			$value['url']     = iCMS_API.'?app=comment&do=goto&iid='.$value['iid'].'&appid='.$value['appid'].'&cid='.$value['cid'];
			if($vars['by']=='ASC'){
				$value['lou'] = $key+$ln*$maxperpage+1;
			}else{
				$value['lou'] = $total-($key+$ln*$maxperpage);
			}
			$value['content'] = nl2br($value['content']);
			$value['user']    = comment_user_info($value['userid'],$value['username'],$vars['facesize']);
			$value['reply_uid'] && $value['reply'] = comment_user_info($value['reply_uid'],$value['reply_name'],$vars['facesize']);

			$value['total'] = $total;
			if($vars['reply'] && $reply_array){
				$value['reply_data'] = $reply_array[$value['reply_id']];
				unset($reply_array[$value['reply_id']]);
			}
			if($vars['page']){
				$value['page']  = array('total'=>$multi->totalpage,'perpage'=>$multi->perpage);
			}
	        $value['param'] = array(
				"appid"  => $value['appid'],
				"iid"    => $value['iid'],
				"id"     => $value['id'],
				"userid" => $value['userid'],
				"name"   => $value['username'],
	        );
			$resource[$key] = $value;
		}
		$vars['cache'] && iCache::set($cache_name,$resource,$cache_time);
	}

	return $resource;
}
function comment_form($vars){
	// if(!iCMS::$hooks['enable_comment']){
	// 	iUI::warning('此页面禁止调用 iCMS&#x3a;comment&#x3a;form 标签！');
	// }
	if(iCMS::$config['comment']['plugin']['changyan']['enable']|| $vars['display']==="changyan"){
		iCMS::$config['comment']['plugin']['changyan']['appid'] OR iUI::warning('iCMS&#x3a;comment&#x3a;form 标签出错! 畅言评论插件缺少参数"appid"或"appid"值为空.');
		iCMS::$config['comment']['plugin']['changyan']['appkey'] OR iUI::warning('iCMS&#x3a;comment&#x3a;form 标签出错! 畅言评论插件缺少参数"appkey"或"appkey"值为空.');

		if(iPHP::$mobile){
			echo iPHP::view('iCMS://comment/changyan.mobile.htm');
		}else{
			echo iPHP::view('iCMS://comment/changyan.pc.htm');
		}
		return;
	}
	if(!isset($vars['ref'])){
		$_vars = iPHP::app_vars(true);
		$vars  = array_merge($vars,$_vars);
		unset($vars['ref'],$_vars);
	}

	$vars['iid']   OR iUI::warning('iCMS&#x3a;comment&#x3a;form 标签出错! 缺少参数"iid"或"iid"值为空.');
	$vars['cid']   OR iUI::warning('iCMS&#x3a;comment&#x3a;form 标签出错! 缺少参数"cid"或"cid"值为空.');
	$vars['appid'] OR iUI::warning('iCMS&#x3a;comment&#x3a;form 标签出错! 缺少参数"appid"或"appid"值为空.');
	$vars['title'] OR iUI::warning('iCMS&#x3a;comment&#x3a;form 标签出错! 缺少参数"title"或"title"值为空.');
	switch ($vars['display']) {
		case 'iframe':
			$tpl        = 'form.iframe';
			$vars['do'] = 'form';
			break;
		default:
			isset($vars['_display']) && $vars['display'] = $vars['_display'];
			$vars['param'] = array(
				'suid'  => $vars['suid'],
				'iid'   => $vars['iid'],
				'cid'   => $vars['cid'],
				'appid' => $vars['appid'],
				'title' => $vars['title'],
			);
			$tpl = 'form.default';
			break;
	}
	unset($vars['method'],$vars['_display']);
	$vars['query'] = http_build_query($vars);
	iPHP::assign('comment_vars',$vars);
	echo iPHP::view('iCMS://comment/'.$tpl.'.htm');
}
