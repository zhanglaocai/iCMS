<?php
/**
* iCMS - i Content Management System
* Copyright (c) 2007-2017 iCMSdev.com. All rights reserved.
*
* @author icmsdev <master@icmsdev.com>
* @site https://www.icmsdev.com
* @licence https://www.icmsdev.com/LICENSE.html
*/

class contentFunc {
    public static $interface = null; //应用信息接口
    public static $app       = null;
    public static $table     = null;
    public static $primary   = null;
    /**
    * 已在 categoryApp contentApp 设置数据回调,
    * 在应用范围内可以不用设置 app="应用名/应用ID"
    **/
    public static function interfaced($value=null) {
        self::$interface = $value;
    }
    private static function init($vars,$func='list'){
        if((empty($vars['app'])||$vars['app']=='content') && self::$interface){
            $vars['app'] = self::$interface['app'];
        }
        self::$app = apps::get_app($vars['app']);
        if(empty(self::$app)||$vars['app']=='content'){
            iUI::warning('iCMS&#x3a;content&#x3a;'.$func.' 标签出错! 缺少参数"app"或"app"值为空.');
        }
        self::$table   = apps::get_table(self::$app);
        self::$primary = self::$table['primary'];
        unset($vars['pic']);
        return new appsFunc(
            $vars,
            self::$app['app'],
            self::$table['primary'],
            self::$app['id'],
            self::$table['table']
        );
    }
    public static function content_list($vars) {
        $appsFunc = self::init($vars,'list');
        isset($vars['keywords']) && $appsFunc->keywords = 'title';
        return $appsFunc->process_list(array(__CLASS__,'content_array'));
    }

    public static function content_prev($vars) {
        return self::init($vars,'prev')->process_prev_next('prev');
    }

    public static function content_next($vars) {
        return self::init($vars,'next')->process_prev_next('next');
    }

    public static function content_array($vars, $variable) {
        $resource = array();
        if ($variable) {
            $contentApp = new contentApp(self::$app);
            if($vars['data']){
                $idArray = iSQL::values($variable,'id','array',null);
                $idArray && $content_data = (array)$contentApp->data($idArray);
                unset($idArray);
            }
            if($vars['meta']){
                $idArray = iSQL::values($variable,'id','array',null);
                $idArray && $meta_data = (array)apps_meta::data(self::$app['app'],$idArray);
                unset($idArray);
            }
            if($vars['tags']){
                $tagArray = iSQL::values($variable,'tags','array',null,'id');
                $tagArray && $tags_data = (array)tagApp::multi_tag($tagArray);
                unset($tagArray);
                $vars['tags'] = false;
            }
            foreach ($variable as $key => $value) {
                $value = $contentApp->value($value,$vars);

                if ($value === false) {
                    continue;
                }
                if(($vars['data']) && $content_data){
                    $value['data']  = (array)$content_data[$value['id']];
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
                $resource[$key] = $value;
            }
        }
        return $resource;
    }
}
