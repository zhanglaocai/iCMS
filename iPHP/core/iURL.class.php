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
define('iPHP_PAGE_SIGN', '{P}');

class iURL {
    public static $CONFIG   = null;
    public static $ARRAY    = null;
    public static $APP_CONF = null;

    public static function init($config=null){
        self::$CONFIG = $config;
    }

    public static function router($key, $var = null) {
        $routerArray = self::$CONFIG['config'];
        $routerKey   = $key;
        is_array($key) && $routerKey = $key[0];
        $router = $routerArray[$routerKey];
        $url = iPHP_ROUTER_REWRITE?$router[0]:$router[1];

        if (iPHP_ROUTER_REWRITE && stripos($routerKey, 'uid:') === 0) {
            $url = rtrim(self::$CONFIG['user_url'], '/') . $url;
        }

        if (is_array($key)) {
            if (is_array($key[1])) {
                /* 多个{} 例:/{uid}/{cid}/ */
                preg_match_all('/\{(\w+)\}/i', $url, $matches);
                $url = str_replace($matches[0], $key[1], $url);
            } else {
                $url = preg_replace('/\{\w+\}/i', $key[1], $url);
            }
            $key[2] && $url = $key[2] . $url;
        }

        if ($var == '?&') {
            $url .= iPHP_ROUTER_REWRITE ? '?' : '&';
        }
        if(!iPHP_ROUTER_REWRITE){
            $url = self::$CONFIG['api_url'].'/'.$url;
        }
        return $url;
    }

    public static function rule($matches) {
    	$b	= $matches[1];
    	list($a,$c,$tc) = self::$ARRAY;
        switch($b) {
            case 'ID':		$e = $a['id'];break;
            case '0xID':	$e = sprintf("%08s",$a['id']);break;
            case '0x3ID':	$e = substr(sprintf("%08s",$a['id']), 0, 4);break;
            case '0x3,2ID':	$e = substr(sprintf("%08s",$a['id']), 4, 2);break;
            case 'MD5':     $e = substr(md5($c['id']),8,16);break;

            case 'CID':     $e = $c['cid'];break;
            case '0xCID':   $e = sprintf("%08s",$c['cid']);break;
            case 'CDIR':    $e = $c['dir'];break;
            case 'CDIRS':   $e = $c['dirs'];break;

            case 'TIME':	$e = $a['pubdate'];break;
            case 'YY':		$e = get_date($a['pubdate'],'y');break;
            case 'YYYY':	$e = get_date($a['pubdate'],'Y');break;
            case 'M':		$e = get_date($a['pubdate'],'n');break;
            case 'MM':		$e = get_date($a['pubdate'],'m');break;
            case 'D':		$e = get_date($a['pubdate'],'j');break;
            case 'DD':		$e = get_date($a['pubdate'],'d');break;

            case 'NAME':    $e = urlencode(iSecurity::escapeStr($a['name']));break;
            case 'TITLE':   $e = urlencode(iSecurity::escapeStr($a['title']));break;
            case 'ZH_CN':	$e = ($a['name']?$a['name']:$a['title']);break;
            case 'TKEY':    $e = $a['tkey'];break;
            case 'LINK':    $e = $a['clink'];break;

            case 'TCID':	$e = $tc['tcid'];break;
            case 'TCDIR':	$e = $tc['dir'];break;

            case 'EXT':		$e = $c['htmlext']?$c['htmlext']:self::$CONFIG['ext'];break;
            case 'P':       $e = iPHP_PAGE_SIGN;break;
            default:
                $key = strtolower($b);
                $a[$key] && $e = $a[$key];
        }
        return $e;
    }
    public static function rule_data($C,$key) {
        if(empty($C['mode'])||$C['password']){
            return '{PHP}';
        }else{
            is_object($C['rule']) && $C['rule'] = (array)$C['rule'];
            is_array($C['rule'])  OR $C['rule'] = json_decode($C['rule'],true);

            return $C['rule'][$key];
        }
    }
    public static function get($uri,$a=array()) {
        $i          = new stdClass();
        $default    = array();
        $category   = array();
        $array      = (array)$a;
        $router_url = self::$CONFIG['url'];
        $router_dir = self::$CONFIG['dir'];
        $app_conf   = self::$CONFIG['iurl'][$uri];

        switch($app_conf['rule']) {
            case '0':
                $i->href = $array['url'];
                $url     = $array['rule'];
            break;
            case '1'://分类
                $category = $array;
                $i->href  = $category['url'];
                $url      = self::rule_data($category,'index');
                $purl     = self::rule_data($category,'list');
            break;
            case '2'://内容
                $array    = (array)$a[0];
                $category = (array)$a[1];
                $i->href  = $array['url'];
                $url      = self::rule_data($category,$uri);
            break;
            case '3'://标签
                $array     = (array)$a[0];
                $category  = (array)$a[1];
                $_category = (array)$a[2];
                $i->href   = $array['url'];
                $category && $url = self::rule_data($category,$uri);

                if($_category['rule'][$uri]){
                    $url = self::rule_data($_category,$uri);
                }
            break;
            case '4'://自定义
                $array    = (array)$a[0];
                $category = (array)$a[1];
                $i->href  = $array['url'];
                $url      = self::rule_data($category,$uri);
                $href     = 'index.php?app='.$uri.'&';
            break;
            default:
                $url  = '{PHP}';
                $href = 'index.php?app='.$uri.'&';
            break;
        }

        $default  = self::$CONFIG[$uri];
        if($default){
            $router_dir = $default['dir'];
            $router_url = $default['url'];
            empty($url) && $url = $default['rule'];
        }

        if($url=='{PHP}'){
            $primary = $app_conf['primary'];
            empty($href) && $href = $uri.'.php?';
            $href.= $primary.'='.$array[$primary];
            if($app_conf['page']){
                $i->pageurl = $href.'&'.$app_conf['page'].'='.iPHP_PAGE_SIGN;
                iFS::checkHttp($i->pageurl) OR $i->pageurl = rtrim($router_url,'/').'/'.$i->pageurl;
            }
            iFS::checkHttp($href) OR $href = rtrim($router_url,'/').'/'.$href;
            $i->href = $href;
        }else if(strpos($url,'{PHP}')===false) {
        	self::$ARRAY = array($array,$category,$_category);
            $i = self::build($url,$router_dir,$router_url,$category['htmlext']);

            $pfile = $i->file;
            if(strpos($pfile,iPHP_PAGE_SIGN)===false) {
                $pfile = $i->name.'_'.iPHP_PAGE_SIGN.$i->ext;
            }
            if($purl){
                $ii = self::build($purl,$router_dir,$router_url,$category['htmlext']);
                $i->pageurl  = $ii->href;
                $i->pagepath = $ii->path;
                unset($ii);
            }else{
                $i->pageurl  = $i->hdir.'/'.$pfile ;
                $i->pagepath = $i->dir.'/'.$pfile;
            }
            // call_user_func_array(self::$callback, array($uri,$i,self::$ARRAY,$app_conf));
        }
        $category && $i = self::domain($i,$category['cid'],$router_url);
        return $i;
    }
    public static function domain($i,$cid,$base_url) {
        if(self::$CONFIG['domain']){
            $i = call_user_func_array(self::$CONFIG['domain'], array($i,$cid,$base_url));
        }
        return $i;
    }
    public static function build($url,$_dir,$_url,$_ext) {
        if(strpos($url,'{')!==false){
            $url = preg_replace_callback("/\{(.*?)\}/",array(__CLASS__,'rule'),$url);
        }

        $i = new stdClass();
        $i->href = $url;
        if(strpos($_dir,'..')===false) {
            $i->href = $_dir.$url;
        }
        $i->href = ltrim(iFS::path($i->href),'/');
        $i->path = rtrim(iFS::path(iPATH.$_dir.$url),'/') ;

        if(iFS::checkHttp($i->href)===false){
            $i->href = rtrim($_url,'/').'/'.$i->href;
        }
        $pathA = pathinfo($i->path);
        $i->hdir = pathinfo($i->href,PATHINFO_DIRNAME);
        $i->dir  = $pathA['dirname'];
        $i->file = $pathA['basename'];
        $i->name = $pathA['filename'];
        $i->ext  = '.'.$pathA['extension'];
        $i->name OR $i->name = $i->file;

        if(empty($i->file)||substr($url,-1)=='/'||empty($pathA['extension'])) {
            $i->name = 'index';
            $i->ext  = self::$CONFIG['ext'];
            $_ext && $i->ext = $_ext;
            $i->file = $i->name.$i->ext;
            $i->path = $i->path.'/'.$i->file;
            $i->dir  = dirname($i->path);
            $i->hdir = dirname($i->href.'/'.$i->file);
        }

        return $i;
    }
    // public static function page($i) {
    //     // $i->pfile = $i->file;
    //     // if(strpos($i->file,iPHP_PAGE_SIGN)===false) {
    //     //     $i->pfile = $i->name.'_'.iPHP_PAGE_SIGN.$i->ext;
    //     // }
    //     // $i->pageurl  = $i->hdir.'/'.$i->pfile ;
    //     // $i->pagepath = $i->dir.'/'.$i->pfile;
    //     $i->href = str_replace(iPHP_PAGE_SIGN,1,$i->href);
    //     $i->path = str_replace(iPHP_PAGE_SIGN,1,$i->path);
    //     $i->file = str_replace(iPHP_PAGE_SIGN,1,$i->file);
    //     $i->name = str_replace(iPHP_PAGE_SIGN,1,$i->name);
    //     return $i;
    // }
    public static function page_num($path, $page = false) {
        $page === false && $page = $GLOBALS['page'];
        if ($page < 2) {
            return str_replace(array('_'.iPHP_PAGE_SIGN, '&p='.iPHP_PAGE_SIGN), '', $path);
        }
        return str_replace(iPHP_PAGE_SIGN, $page, $path);
    }
    public static function page_url($iurl){
        if(isset($GLOBALS['iPage'])) return;

        $iurl = (array)$iurl;
        $GLOBALS['iPage']['url']  = $iurl['pageurl'];
        $GLOBALS['iPage']['html'] = array(
            'enable' =>true,
            'index'  =>$iurl['href'],
            'ext'    =>$iurl['ext']
        );
    }
}
