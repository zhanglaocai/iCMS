<?php
/**
* iCMS - i Content Management System
* Copyright (c) 2007-2017 iCMSdev.com. All rights reserved.
*
* @author icmsdev <master@icmsdev.com>
* @site https://www.icmsdev.com
* @licence https://www.icmsdev.com/LICENSE.html
*/
class tagFunc{
    public static function init($vars){
        return new appsFunc($vars,'tag');
    }
    public static function tag_list($vars){
        $appsFunc = self::init($vars);
        $appsFunc->config['orderby'] = array('hot'=>'count');

        isset($vars['rootid'])&& $appsFunc->add_sql_and('rootid');
        isset($vars['field']) && $appsFunc->add_sql_and('field');

        if(!isset($vars['tcids']) && isset($vars['tcid'])){
            $appsFunc->add_sql_in('tcid');
        }

        if(isset($vars['tcids']) && !isset($vars['tcid'])){
            $appsFunc->add_map_where($vars['tcids'],'category','cid');
        }

        isset($vars['tcid!'])     && $appsFunc->add_sql_in('tcid',$vars['tcid!'],'not');
        isset($vars['keywords'])  && $appsFunc->set_keywords_field('tkey,name,seotitle,keywords');

        return $appsFunc->process_list(array(__CLASS__,'tag_array'));
    }
    public static function tag_search($vars) {
        return self::init($vars)->process_sphinx(array(__CLASS__,'tag_array'));
    }
    public static function tag_prev($vars) {
        return self::init($vars)->process_prev_next('prev');
    }
    public static function tag_next($vars) {
        return self::init($vars)->process_prev_next('next');
    }
    public static function tag_array($vars,$resource=null){
        if($resource===null){
            if(isset($vars['name'])){
                $array = array($vars['name'],'name');
            }else if(isset($vars['id'])){
                $array = array($vars['id'],'id');
            }
            if($array){
                return tagApp::tag($array[0],$array[1],false);
            }else{
                iUI::warning('iCMS&#x3a;tag&#x3a;array 标签出错! 缺少参数"id"或"name".');
            }
        }
        if($resource){
            if($vars['meta']){
                $idArray = iSQL::values($resource,'id','array',null);
                $idArray && $meta_data = (array)apps_meta::data('tag',$idArray);
                unset($idArray);
            }
            foreach ($resource as $key => $value) {
                if($vars['meta'] && $meta_data){
                    $value+= (array)$meta_data[$value['id']];
                }
        		$resource[$key] = tagApp::value($value,$vars);
            }
            $vars['keys'] && iSQL::pickup_keys($resource,$vars['keys'],$vars['is_remove_keys']);
        }
        return $resource;
    }
}
