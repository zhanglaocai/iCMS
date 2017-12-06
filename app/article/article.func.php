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
class articleFunc{
    public static function init($vars){
        return new appsFunc($vars,'article');
    }
	public static function article_list($vars) {
		return self::init($vars)->process_list(array(__CLASS__,'article_array'));
	}
	public static function article_search($vars) {
		return self::init($vars)->process_sphinx(array(__CLASS__,'article_array'));
	}
	public static function article_prev($vars) {
		return self::init($vars)->process_prev_next('prev');
	}
	public static function article_next($vars) {
		return self::init($vars)->process_prev_next('next');
	}
	public static function article_data($vars) {
		$vars['id'] OR iUI::warning('iCMS&#x3a;article&#x3a;data 标签出错! 缺少"id"属性或"id"值为空.');
		$data = articleApp::data($vars['id']);
		articleApp::hooked($data);
		return $data;
	}
	public static function article_array($vars, $variable) {
		$resource = array();
		if ($variable) {
	        if($vars['data']||$vars['pics']){
	            $aidArray = iSQL::values($variable,'id','array',null);
	            $aidArray && $article_data = (array) articleApp::data($aidArray);
	            unset($aidArray);
	        }
	        if($vars['meta']){
	            $aidArray = iSQL::values($variable,'id','array',null);
				$aidArray && $meta_data = (array)apps_meta::data('article',$aidArray);
	            unset($aidArray);
	        }

	        if($vars['tags']){
	            $tagArray = iSQL::values($variable,'tags','array',null,'id');
				$tagArray && $tags_data = (array)tagApp::multi_tag($tagArray);
	            unset($tagArray);
	            $vars['tag'] = false;
	        }

			foreach ($variable as $key => $value) {
				$value = articleApp::value($value, false, $vars);

				if ($value === false) {
					continue;
				}
	            if(($vars['data']||$vars['pics']) && $article_data){
	                $value['data']  = (array)$article_data[$value['id']];
	                if($vars['pics']){
						$value['pics'] = filesApp::get_content_pics($value['data']['body']);
						if(!$value['data']){
							unset($value['data']);
						}
	                }
	            }

	            if($vars['tags'] && $tags_data){
	                $value+= (array)$tags_data[$value['id']];
	            }
	            if($vars['meta'] && $meta_data){
	                $value+= (array)$meta_data[$value['id']];
	            }

				if ($vars['page']) {
					$value['page'] = $GLOBALS['page'] ? $GLOBALS['page'] : 1;
					$value['total'] = $total;
				}
				if ($vars['archive'] == "date") {
					$_date = archive_date($value['postime']);
					$resource[$_date][$key] = $value;
				} else {
					$resource[$key] = $value;
				}
				unset($variable[$key]);
			}
			$vars['keys'] && iSQL::pickup_keys($resource,$vars['keys'],$vars['is_remove_keys']);
		}
		return $resource;
	}
}
