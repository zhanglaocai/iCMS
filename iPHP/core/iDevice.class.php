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
/**
 * 多终端适配
 * @param  [type] &$config [系统配置]
 * @return [type]          [description]
 */
class iDevice {
    public static function init(&$config) {
        $template = $config['template'];
        /**
         * 判断指定设备
         */
        iPHP::PG('device') && $flag ='device';
        /**
         * 无指定设备 判断USER_AGENT
         *
         */
        empty($def_tpl) && $flag ='ua';
        /**
         * 无指定USER_AGENT  判断域名模板
         *
         */
        empty($def_tpl) && $flag ='domain';

        list($device_name, $def_tpl, $domain) = self::check($template['device'], $flag);

        iPHP::$mobile = false;
        if (empty($def_tpl)) {
            //检查是否移动设备
            if (self::agent($template['mobile']['agent'])) {
                iPHP::$mobile = true;
                $mobile_tpl   = $template['mobile']['tpl'];
                $device_name  = 'mobile';
                $def_tpl      = $mobile_tpl;
                $domain       = $template['mobile']['domain'];
            }
        }

        if (empty($def_tpl)) {
            $device_name = 'desktop';
            $def_tpl     = $template['desktop']['tpl'];
            $domain      = false;
        }
        define('iPHP_DEFAULT_TPL', $def_tpl);
        define('iPHP_MOBILE_TPL', $mobile_tpl);
        define('iPHP_DEVICE', $device_name);
        define('iPHP_DOMAIN', $domain);

        iPHP_DOMAIN && $config['router'] = str_replace($config['router']['url'], iPHP_DOMAIN, $config['router']);
        // self::redirect();
    }
    private static function redirect(){
        define('iPHP_REQUEST_SCHEME',($_SERVER['SERVER_PORT'] == 443)?'https':'http');
        define('iPHP_REQUEST_HOST',iPHP_REQUEST_SCHEME.'://'.($_SERVER['HTTP_X_HTTP_HOST']?$_SERVER['HTTP_X_HTTP_HOST']:$_SERVER['HTTP_HOST']));
        define('iPHP_REQUEST_URI',$_SERVER['REQUEST_URI']);
        define('iPHP_REQUEST_URL',iPHP_REQUEST_HOST.iPHP_REQUEST_URI);

        if(stripos(iPHP_REQUEST_URL, iPHP_HOST) === false){
            $redirect_url = str_replace(iPHP_REQUEST_HOST,iPHP_HOST, iPHP_REQUEST_URL);
            header("Expires:1 January, 1970 00:00:01 GMT");
            header("Cache-Control: no-cache");
            header("Pragma: no-cache");
            // header("X-REDIRECT-REF: ".iPHP_REQUEST_URL);
            // header("X-iPHP_HOST: ".iPHP_HOST);
            // header("X-REDIRECT_URL: ".$redirect_url);
            // header("X-STRIPOS: ".(stripos(iPHP_REQUEST_URL, iPHP_HOST) === false));
            // iPHP::http_status(301);
            // exit($redirect_url);
            // iPHP::redirect($redirect_url);
        }
    }
    private static function check($deviceArray = null, $flag = false) {
        foreach ((array) $deviceArray as $key => $device) {
            if ($device['tpl']) {
                $check = false;
                if ($flag == 'ua') {
                    $device['ua'] && $check = self::agent($device['ua']);
                } elseif ($flag == 'device') {
                    $_device = iPHP::PG('device');
                    if ($device['ua'] == $_device || $device['name'] == $_device) {
                        $check = true;
                    }
                } elseif ($flag == 'domain') {
                    if (stripos($device['domain'], $_SERVER['HTTP_HOST']) !== false && empty($device['ua'])) {
                        $check = true;
                    }
                }
                if ($check) {
                    return array($device['name'], $device['tpl'], $device['domain']);
                }
            }
        }
    }
    private static function agent($user_agent) {
        $user_agent = str_replace(',','|',preg_quote($user_agent,'/'));
        return ($user_agent && preg_match('@'.$user_agent.'@i',$_SERVER["HTTP_USER_AGENT"]));
    }
}
