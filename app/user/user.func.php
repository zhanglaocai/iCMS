<?php
/**
* iCMS - i Content Management System
* Copyright (c) 2007-2017 iCMSdev.com. All rights reserved.
*
* @author icmsdev <master@icmsdev.com>
* @site https://www.icmsdev.com
* @licence https://www.icmsdev.com/LICENSE.html
*/
defined('iPHP') OR exit('What are you doing?');
class userFunc{
    public static function user_cookie($vars=null){
        return user::get_cookie();
    }
    public static function user_data($vars=null){
        if($vars['cookie']){
            return user::get_cookie();
        }

    	$vars['uid']   OR iUI::warning('iCMS&#x3a;user&#x3a;data 标签出错! 缺少"uid"属性或"uid"值为空.');
    	$uid = $vars['uid'];
    	if($uid=='me'){
    		$uid  = 0;
    		$auth = user::get_cookie();
    		$auth && $uid = user::$userid;
    	}
        $user = (array)user::get($uid);
        if(isset($user['uid'])){
            $vars['data'] && $user['data']= (array)user::data($uid);
        }else{
            if($vars['data']){
                $userdata = user::data($uid);
                foreach ($user as $key => $value) {
                    $user[$key] = (array)$value;
                    $user[$key]['data'] = (array)$userdata[$key];
                }
            }

        }
        return $user;
    }

    public static function user_list($vars=null){
        $vars['default:rows'] = 100;

        $appsFunc = new appsFunc($vars,'user','uid');
        $appsFunc->process_sql_status();
        $appsFunc->process_sql_id();
        $appsFunc->process_sql_pid();

        isset($vars['userid'])&& $appsFunc->add_sql_and('uid',$vars['userid']);
        isset($vars['gid'])   && $appsFunc->add_sql_and('gid');
        isset($vars['type'])  && $appsFunc->add_sql_and('type');

        $appsFunc->process_sql_orderby(array('article','follow','fans'));
        isset($vars['where'])&& $appsFunc->add_sql_where();

        $map_order_sql = $appsFunc->process_map_where();
        isset($vars['page']) && list($total,$multi) = $appsFunc->process_page();
        $appsFunc->process_ids_array($total,$map_order_sql);

        $resource = $appsFunc->process_get_cache();

    	if(empty($resource)){
            $resource = $appsFunc->get_resource();
            if($vars['data']){
                $uidArray = iSQL::values($resource,'uid','array',null);
                $uidArray && $user_data = (array) user::data($uidArray);
            }
            if($resource)foreach ($resource as $key => $value) {
                unset($value['password']);
    			$value['url']    = user::router($value['uid'],"url");
    			$value['urls']   = user::router($value['uid'],"urls");
                $value+=user::info($value['uid'],$value['nickname'],$vars['size']);
    			$value['gender'] = $value['gender']?'male':'female';
                if($vars['data'] && $user_data){
                    $value['data']  = (array)$user_data[$value['uid']];
                }
    			$resource[$key]  = $value;
            }
            $appsFunc->process_keys($resource);
    		$appsFunc->process_set_cache($resource);
    	}
    	return $resource;
    }

    public static function user_category($vars=null){
    	$row       = isset($vars['row'])?(int)$vars['row']:"10";
    	$where_sql = "WHERE `uid`='".(int)$vars['userid']."' ";
    	$where_sql.= " AND `appid`='".(int)$vars['appid']."'";
    	$rs  = iDB::all("SELECT * FROM `#iCMS@__user_category` {$where_sql} ORDER BY `cid` ASC LIMIT $row");
    	$resource = array();
    	if($rs)foreach ($rs as $key => $value) {
    		if($value['appid']==iCMS_APP_ARTICLE){
    			$router ='uid:cid';
    		}else if($value['appid']==iCMS_APP_FAVORITE){
    			$router ='uid:fav:cid';
    		}
    		$value['url'] = iURL::router(array($router,array($value['uid'],$value['cid'])));
    		if(isset($vars['loop'])){
    			$resource[$key] = $value;
    		}else{
    			$resource[$value['cid']]=$value;
    		}
    	}
    	return $resource;
    }
    public static function user_follow($vars=null){
    	$maxperpage = isset($vars['row'])?(int)$vars['row']:"30";
    	if($vars['fuid']){
    		$where_sql = "WHERE `fuid`='".$vars['fuid']."'"; //fans
    	}else{
    		$where_sql = "WHERE `uid`='".$vars['userid']."'";//follow
    	}

    	$offset	= 0;
    	$limit  = "LIMIT {$maxperpage}";
    	if($vars['page']){
    		$total	= iCMS::page_total_cache("SELECT count(*) FROM `#iCMS@__user_follow` {$where_sql}",null,iCMS::$config['cache']['page_total']);
    		$multi  = iUI::page(array('total'=>$total,'perpage'=>$maxperpage,'unit'=>iUI::lang('iCMS:page:sql'),'nowindex'=>$GLOBALS['page']));
    		$offset = $multi->offset;
    		$limit  = "LIMIT {$offset},{$maxperpage}";
            iView::assign("user_follow_total",$total);
    	}
        $hash = md5($where_sql.$limit);

        if($vars['cache']){
    		$cache_name = iPHP_DEVICE.'/user_follow/'.$hash;
    		$resource   = iCache::get($cache_name);
        }
    	$resource = iDB::all("SELECT * FROM `#iCMS@__user_follow` {$where_sql} {$limit}");
        if($vars['data']){
            $uidArray = iSQL::values($resource,array('uid','fuid'),'array',null);
            $uidArray && $user_data = (array) user::data($uidArray);
        }
        $vars['followed'] && $follow_data = user::follow($vars['followed'],'all');

    	if($resource)foreach ($resource as $key => $value) {
    		if($vars['fuid']){
    			$value['avatar'] = user::router($value['uid'],'avatar');
    			$value['url']    = user::router($value['uid'],'url');
    		}else{
    			$value['avatar'] = user::router($value['fuid'],'avatar');
    			$value['url']    = user::router($value['fuid'],'url');
    			$value['uid']    = $value['fuid'];
    			$value['name']   = $value['fname'];
    		}
            if($vars['data'] && $user_data){
                $value['data']  = (array)$user_data[$value['uid']];
            }
    		$vars['followed'] && $value['followed'] = $follow_data[$value['uid']]?1:0;
    		$resource[$key] = $value;
    	}
    	//var_dump($rs);
    	return $resource;
    }
    public static function user_stat($vars=null){

    }
}
