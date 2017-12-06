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

class favoriteFunc{
	public static function favorite_list($vars=null){
		$appsFunc = new appsFunc($vars,'favorite');
		$appsFunc->process_sql_status(false);
		$appsFunc->process_sql_id();

		isset($vars['userid'])&& $appsFunc->add_sql_and('uid',$vars['userid']);
		isset($vars['appid']) && $appsFunc->add_sql_and('appid');
		isset($vars['mode'])  && $appsFunc->add_sql_and('mode');
		isset($vars['where']) && $appsFunc->add_sql_where();
		isset($vars['page'])  && $appsFunc->process_page();

		$appsFunc->process_sql_orderby(array(
			'hot'=>'count'
		));

		$resource = $appsFunc->process_get_cache();

		if(empty($resource)){
			$resource = array();
			$rs = $appsFunc->get_resource();
			if($rs)foreach ($rs as $key => $value) {
				$value['url']  = iURL::router(array('favorite:id',$value['id']));
				$vars['user'] && $value['user'] = user::info($value['uid'],$value['nickname']);
				if(isset($vars['loop'])){
					$resource[$key] = $value;
				}else{
					$resource[$value['id']]=$value;
				}
			}
			$appsFunc->process_keys($resource);
			$appsFunc->process_set_cache($resource);
		}

		return $resource;
	}
	public static function favorite_data($vars=null){
		$appsFunc = new appsFunc($vars,'favorite_data','id',iCMS_APP_FAVORITE);
		$appsFunc->process_sql_status(false);
		$appsFunc->process_sql_id();

		$vars['fid'] && $appsFunc->add_sql_in('fid');
		isset($vars['userid'])&& $appsFunc->add_sql_and('uid',$vars['userid']);
		isset($vars['appid']) && $appsFunc->add_sql_and('appid');
		isset($vars['where']) && $appsFunc->add_sql_where();
		isset($vars['page'])  && $appsFunc->process_page();

		$resource = $appsFunc->process_get_cache();

		if(empty($resource)){
			$resource = $appsFunc->get_resource();
			foreach ($resource as $key => $value) {
				$value['param'] = array(
					"id"    => $value['id'],
					"fid"   => $value['fid'],
					"appid" => $value['appid'],
					"iid"   => $value['iid'],
					"uid"   => $value['uid'],
					"title" => $value['title'],
					"url"   => $value['url'],
				);
				$resource[$key] = $value;
			}

			$appsFunc->process_keys($resource);
			$appsFunc->process_set_cache($resource);
		}

		return $resource;
	}
}
