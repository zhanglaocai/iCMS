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
class iView {
    public static $handle       = NULL;
    public static $gateway      = null;

    public static function init() {
        self::$handle = new iTemplateLite();
        self::$handle->debugging    = iPHP_TPL_DEBUGGING;
        self::$handle->template_dir = iPHP_TPL_DIR;
        self::$handle->compile_dir  = iPHP_TPL_CACHE;
        self::$handle->reserved_template_varname  = iPHP_APP;
        self::$handle->left_delimiter = '<!--{';
        self::$handle->right_delimiter = '}-->';
        self::$handle->register_modifier("date", "get_date");
        self::$handle->register_modifier("cut", "csubstr");
        self::$handle->register_modifier("htmlcut", "htmlcut");
        self::$handle->register_modifier("cnlen", "cstrlen");
        self::$handle->register_modifier("html2txt", "html2text");
        self::$handle->register_modifier("key2num", "key2num");
        //self::$handle->register_modifier("pinyin","GetPinyin");
        self::$handle->register_modifier("unicode", "get_unicode");
        //self::$handle->register_modifier("small","gethumb");
        self::$handle->register_modifier("thumb", "small");
        self::$handle->register_modifier("random", "random");
        self::$handle->register_modifier("fields", "select_fields");
        self::$handle->register_block("cache", array("iView", "block_cache"));
        self::$handle->template_callback = array(
            "resource" => array("iView","callback_path"),
            "output"   => array("iView","callback_output"),
            "app"      => array("iView","callback_appfunc"),
            "app"      => array("iView","callback_appfunc"),
        );
        self::$handle->assign('GET', $_GET);
        self::$handle->assign('POST', $_POST);
        iPHP_TPL_DEBUG && self::$handle->clear_compiled_tpl();
    }
    public static function callback_appfunc($args,$tpl) {
        $keys = isset($args['as'])?$args['as']:$args['app'];
        if($args['method']){
            $callback   = $args['app'].'_'.$args['method'];
            function_exists($callback) OR require_once(iPHP_APP_DIR."/".$args['app']."/".$args['app'].".func.php");
            isset($args['as']) OR $keys.= '_'.$args['method'];
        }else{
            $callback   = iPHP_APP.'_' . $args['app'];
            function_exists($callback) OR require_once(iPHP_TPL_FUN."/".iPHP_APP.".".$args['app'].".php");
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
    public static function app_vars($app_name = true, $out = false) {
        $app_name === true && $app_name = iPHP::$app_name;
        $rs = self::get_vars($app_name);
        return $rs['param'];
    }
    public static function get_vars($key = null) {
        return self::$handle->get_template_vars($key);
    }
    public static function clear_tpl($file = null) {
        if(empty(self::$handle)){
            self::init();
        }
        self::$handle->clear_compiled_tpl($file);
    }
    public static function value($key, $value) {
        self::$handle->assign($key, $value);
    }
    public static function assign($key, $value) {
        self::$handle->assign($key, $value);
    }
    public static function append($key, $value = null, $merge = false) {
        self::$handle->append($key, $value, $merge);
    }
    public static function clear($key) {
        self::$handle->clear_assign($key);
    }
    public static function display($tpl) {
        self::$handle->display($tpl);
    }
    public static function fetch($tpl) {
        return self::$handle->fetch($tpl);
    }
    public static function render($tpl, $p = 'index') {
        $tpl OR iPHP::error_404('Please set the template file', '001', 'TPL');
        if (self::$gateway == 'html') {
            return self::$handle->fetch($tpl);
        } else {
            self::$handle->display($tpl);
            iPHP::debug_info($tpl);
        }
    }
}
