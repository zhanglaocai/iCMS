<?php
/**
* iCMS - i Content Management System
* Copyright (c) 2007-2017 iCMSdev.com. All rights reserved.
*
* @author icmsdev <master@icmsdev.com>
* @site https://www.icmsdev.com
* @licence https://www.icmsdev.com/LICENSE.html
*/
class categoryFunc{
	public static $appid = iCMS_APP_CATEGORY;
	public static function category_array($vars){
		$cid = (int)$vars['cid'];
		return categoryApp::category($cid,false);
	}
	public static function category_list($vars){
		$vars['default:rows'] = 100;

		$appsFunc = new appsFunc($vars,'category','cid');
		$appsFunc->set_default_orderby('ASC','sortnum');
		$appsFunc->process_sql_status();

		isset($vars['appid'])&& $appsFunc->add_sql_and('appid');
		isset($vars['mode']) && $appsFunc->add_sql_and('mode');
		isset($vars['cid!']) && $appsFunc->add_sql_in('cid!',$vars['cid!'],'not');

		if(isset($vars['cid']) && !isset($vars['stype'])){
			$appsFunc->add_sql_in('cid');
		}

		if($vars['stype']=='sub' && isset($vars['sub'])){
			$vars['stype']='suball';
		}
		switch ($vars['stype']) {
			case "top":
				$vars['cid'] && $appsFunc->add_sql_in('cid');
				$appsFunc->add_sql_and('rootid','0');
			break;
			case "sub":
				$vars['cid'] && $appsFunc->add_sql_and('rootid',$vars['cid']);
			break;
			case "suball":
				$cids = categoryApp::get_cids($vars['cid'],true);
				$appsFunc->add_sql_in('cid',$cids);
			break;
			case "self":
				$parentid = categoryApp::get_cahce('parent',$vars['cid']);
				$appsFunc->add_sql_and('rootid',$parentid);
			break;
		}

		$appsFunc->process_sql_pid(true);

		isset($vars['where'])&& $appsFunc->add_sql_where();
		isset($vars['page']) && $appsFunc->process_page();

		$appsFunc->process_sql_orderby(array(
			'hot'=>'count'
		));

		$resource = $appsFunc->process_get_cache();

		if(empty($resource)){
			$resource = $appsFunc->get_resource('cid');
			if($resource){
		        if($vars['meta']){
		            $cidArray = iSQL::values($resource,'cid','array',null);
					$cidArray && $meta_data = (array)apps_meta::data('category',$cidArray);
		            unset($cidArray);
		        }
				foreach ($resource as $key => $value) {
					$cate = categoryApp::get_cahce_cid($value['cid']);
		            if($vars['meta'] && $meta_data){
		                $cate+= (array)$meta_data[$value['cid']];
		            }
					$cate && $resource[$key] = categoryApp::get_lite($cate);
				}
			}
			$appsFunc->process_keys($resource);
			$appsFunc->process_set_cache($resource);
		}

		return $resource;
	}
	public static function category_select($vars){
		$selected  = $vars['selected'];
		$cid   = (int)$vars['cid'];
		$level = $vars['level'];
		empty($level) && $level =1;
		$rootid = categoryApp::get_cahce('rootid');
		$html = null;
		foreach ((array) $rootid[$cid] AS $root => $_cid) {
			$C = categoryApp::get_cahce_cid($_cid);
			if($C['status']=='2'){
				continue;
			}
			if ($C['status'] && $C['config']['ucshow'] && $C['config']['send'] && empty($C['outurl'])) {
				$tag = ($level == '1' ? "" : "├ ");
				$selected = ($selected == $C['cid']) ? "selected" : "";
				$text = str_repeat("│　", $level - 1) . $tag . $C['name'] . "[cid:{$C['cid']}]" . ($C['outurl'] ? "[∞]" : "");
				$C['config']['examine'] && $text .= '[审核]';
				$option = "<option value='{$C['cid']}' $selected>{$text}</option>";
				if(isset($vars['echo'])){
					echo $option;
				}else{
					 $html.= $option;
				}
			}
			if($rootid[$C['cid']]){
				$option = self::category_select(array(
					'selected'  => $selected,
					'cid'   => $C['cid'],
					'level' => $level+1,
				));
				if(isset($vars['echo'])){
					echo $option;
				}else{
					 $html.= $option;
				}
			}
		}
		if(!isset($vars['echo'])){
			return $html;
		}
	}
}
