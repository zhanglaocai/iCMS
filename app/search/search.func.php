<?php
/**
* iCMS - i Content Management System
* Copyright (c) 2007-2017 iCMSdev.com. All rights reserved.
*
* @author icmsdev <master@icmsdev.com>
* @site https://www.icmsdev.com
* @licence https://www.icmsdev.com/LICENSE.html
*/
class searchFunc{
    public static function search_list($vars){
        $vars['default:rows'] = 100;
        $appsFunc = new appsFunc($vars,'search','id',null,'search_log');
        $appsFunc->process_sql_status(false);
        $appsFunc->process_sql_id();

        $vars['orderby'] == 'addtime' && $appsFunc->set_sql_order('addtime');
        $vars['orderby'] == 'times'   && $appsFunc->set_sql_order('times');

        $resource = $appsFunc->process_get_cache();

    	if(empty($resource)){
            $resource = $appsFunc->get_resource();
            foreach ($resource as $key => $value) {
                $value['name']  = $value['search'];
                $value['url']   = searchFunc::search_url(array('query'=>$value['name'],'ret'=>true));
                $resource[$key] = $value;
            }
    		$appsFunc->process_set_cache($resource);
    	}
    	return $resource;
    }
    public static function search_url($vars){
        $q = rawurlencode($vars['query']);
        if(empty($q)){
            return;
        }
        $query = array('app'=>'search','q'=>$q);
        if(isset($vars['_app'])){
            $query['app'] = $vars['_app'];
            $query['do']  = 'search';
        }
        $iURL = searchApp::iurl($q,$query,false);
        if($vars['ret']){
            return $iURL->url;
        }
        echo $iURL->url;
    }
}
