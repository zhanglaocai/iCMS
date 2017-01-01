<?php
/**
 * iPHP - i PHP Framework
 * Copyright (c) 2012 iiiphp.com. All rights reserved.
 *
 * @author coolmoo <iiiphp@qq.com>
 * @website http://www.iiiphp.com
 * @license http://www.iiiphp.com/license
 * @version 2.0.0
 */
class iTemplate {
    public static function init() {
        iPHP::$iTPL = new iTemplateLite();
        iPHP::$iTPL->debugging    = iPHP_TPL_DEBUGGING;
        iPHP::$iTPL->template_dir = iPHP_TPL_DIR;
        iPHP::$iTPL->compile_dir  = iPHP_TPL_CACHE;
        iPHP::$iTPL->left_delimiter = '<!--{';
        iPHP::$iTPL->right_delimiter = '}-->';
        iPHP::$iTPL->register_modifier("date", "get_date");
        iPHP::$iTPL->register_modifier("cut", "csubstr");
        iPHP::$iTPL->register_modifier("htmlcut", "htmlcut");
        iPHP::$iTPL->register_modifier("cnlen", "cstrlen");
        iPHP::$iTPL->register_modifier("html2txt", "html2text");
        iPHP::$iTPL->register_modifier("key2num", "key2num");
        //iPHP::$iTPL->register_modifier("pinyin","GetPinyin");
        iPHP::$iTPL->register_modifier("unicode", "get_unicode");
        //iPHP::$iTPL->register_modifier("small","gethumb");
        iPHP::$iTPL->register_modifier("thumb", "small");
        iPHP::$iTPL->register_modifier("random", "random");
        iPHP::$iTPL->register_modifier("fields", "select_fields");
        iPHP::$iTPL->register_block("cache", array("iTemplate", "block_cache"));
        iPHP::$iTPL->template_callback = array(
            "resource" => array("iTemplate","callback_path"),
            "output"   => array("iTemplate","callback_output"),
            "app"      => array("iTemplate","callback_appfunc"),
        );
        iPHP::$iTPL->assign('GET', $_GET);
        iPHP::$iTPL->assign('POST', $_POST);
        iPHP_TPL_DEBUG && iPHP::$iTPL->clear_compiled_tpl();
    }
    public static function callback_appfunc($args,$tpl) {
        $keys = isset($args['as'])?$args['as']:$args['app'];
        if($args['method']){
            $callback   = $args['app'].'_'.$args['method'];
            function_exists($callback) OR require_once(iPHP_APP_DIR."/".$args['app']."/".$args['app'].".func.php");
            isset($args['as']) OR $keys.= '_'.$args['method'];
        }else{
            $callback   = iPHP_TPL_VAR.'_' . $args['app'];
            function_exists($callback) OR require_once(iPHP_TPL_FUN."/".iPHP_TPL_VAR.".".$args['app'].".php");
        }
        if(isset($args['vars'])){
            $vars = $args['vars'];
            unset($args['vars']);
            $args = array_merge($args,$vars);
        }

        $tpl->assign($keys,$callback($args));
    }
    public static function block_cache($vars, $content, $tpl) {
        $vars['id'] OR iUI::warning('cache 标签出错! 缺少"id"属性或"id"值为空.');
        $cache_time = isset($vars['time']) ? (int) $vars['time'] : -1;
        $cache_name = iPHP_DEVICE . '/part/' . $vars['id'];
        $cache = iCache::get($cache_name);
        if (empty($cache)) {
            if ($content === null) {
                return null;
            }
            $cache = $content;
            iCache::set($cache_name, $content, $cache_time);
            unset($content);
        }
        if ($vars['assign']) {
            $tpl->assign($vars['assign'], $cache);
            return ture;
        }
        if ($content === null) {
            return $cache;
        }
        // return $cache;
    }
    /**
     * 模板路径
     * @param  [type] $tpl [description]
     * @return [type]      [description]
     */
    public static function callback_path($tpl){
        if (strpos($tpl, iPHP_APP . ':/') !== false) {
            $_tpl = str_replace(iPHP_APP . ':/', iPHP_DEFAULT_TPL, $tpl);
            if (@is_file(iPHP_TPL_DIR . "/" . $_tpl)) {
                return $_tpl;
            }

            if (iPHP_DEVICE != 'desktop') {//移动设备
                $_tpl = str_replace(iPHP_APP . ':/', iPHP_MOBILE_TPL, $tpl); // mobile/
                if (@is_file(iPHP_TPL_DIR . "/" . $_tpl)) {
                    return $_tpl;
                }

            }
            $tpl = str_replace(iPHP_APP . ':/', iPHP_APP, $tpl);
        } elseif (strpos($tpl, '{iTPL}') !== false) {
            $tpl = str_replace('{iTPL}', iPHP_DEFAULT_TPL, $tpl);
        }
        if (iPHP_DEVICE != 'desktop' && strpos($tpl, iPHP_APP) === false) {
            $current_tpl = dirname($tpl);
            if (!in_array($current_tpl, array(iPHP_DEFAULT_TPL, iPHP_MOBILE_TPL))) {
                $tpl = str_replace($current_tpl . '/', iPHP_DEFAULT_TPL . '/', $tpl);
            }
        }
        if (@is_file(iPHP_TPL_DIR . "/" . $tpl)) {
            return $tpl;
        } else {
            iPHP::error_404('Unable to find the template file <b>iPHP:://template/' . $tpl . '</b>', '002', 'TPL');
        }
    }
    public static function callback_output($html,$file=null){
        return $html;
    }
}
