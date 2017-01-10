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
    public static $config   = null;
    public static $uriArray = null;

    public static function router($key, $var = null) {
        if(isset($GLOBALS['iPHP_ROUTER'])){
            $routerArray = $GLOBALS['iPHP_ROUTER'];
        }else{
            $path = iPHP_APP_CONF . '/router.json';
            @is_file($path) OR iPHP::error_throw($path . ' not exist', 0013);
            $routerArray = json_decode(file_get_contents($path), true);
            $GLOBALS['iPHP_ROUTER'] = $routerArray;
        }
        $routerKey = $key;
        is_array($key) && $routerKey = $key[0];
        $router = $routerArray[$routerKey];
        $url = iPHP_ROUTER_REWRITE?$router[0]:$router[1];

        if (iPHP_ROUTER_REWRITE && stripos($routerKey, 'uid:') === 0) {
            $url = rtrim(iPHP_ROUTER_USER, '/') . $url;
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
        $url = str_replace('__API__', self::$config['conf']['API'], $url);
        return $url;
    }

	public static function init($config=null,$conf=null){
        self::$config           = $config['router'];
        self::$config['tag']    = $config['tag'];
        self::$config['router'] = array(
            'http'     => array('rule'=>'0','primary'=>''),
            'index'    => array('rule'=>'0','primary'=>''),
            'category' => array('rule'=>'1','primary'=>'cid'),
            'article'  => array('rule'=>'2','primary'=>'id','PHP_PAGE'=>'p'),
            'software' => array('rule'=>'2','primary'=>'id'),
            'tag'      => array('rule'=>'3','primary'=>'id'),
        );
        self::$config['conf'] = (array) $conf;

        // foreach (glob(iPHP_APP_DIR."/*/etc/iURL.router.php",GLOB_NOSORT) as $index=> $filename) {
        //     $app = str_replace(array(iPHP_APP_DIR,'etc/iURL.router.php'), '', $filename);
        //     $app = trim($app,'/');
        //     self::$config['router'][$app] = include $filename;
        // }
        // var_dump(self::$config);
        // exit;
	}

    public static function rule($matches) {
    	$b	= $matches[1];
    	list($a,$c,$tc) = self::$uriArray;
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

            case 'EXT':		$e = $c['htmlext']?$c['htmlext']:self::$config['html_ext'];break;
            case 'P':       $e = iPHP_PAGE_SIGN;break;
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
        $i        = new stdClass();
        $surl     = self::$config['URL'];
        $html_dir = self::$config['html_dir'];
        $router   = self::$config['router'];
        $category = array();
        $array    = (array)$a;
        $primary  = $router[$uri]['primary'];
        $rule     = $router[$uri]['rule'];
        $document_uri = $uri.'.php?';
        switch($rule) {
            case '0':
                $i->href = $array['url'];
                $url     = $array['rule'];
                break;
            case '1':
                $category = $array;
                $i->href  = $category['url'];
                $url      = self::rule_data($category,'index');
                // $purl     = self::rule_data($category,'list');
                break;
            case '2':
                $array    = (array)$a[0];
                $category = (array)$a[1];
                $i->href  = $array['url'];
                $url      = self::rule_data($category,$uri);
                break;
            case '3':
                $array     = (array)$a[0];
                $category  = (array)$a[1];
                $_category = (array)$a[2];
                $i->href   = $array['url'];
                $url       = self::rule_data($category,$uri);

                if($_category['rule'][$uri]){
                    $url = self::rule_data($_category,$uri);
                }

                $tagconf  = self::$config[$uri];
                $html_dir = $tagconf['dir'];
                $surl     = $tagconf['url'];
                $url OR $url = $tagconf['rule'];
                break;
             default:
                $url = $array['rule'];
        }
        if($url=='{PHP}'){
            $document_uri.= $primary.'='.$array[$primary];
            if($router[$uri]['PHP_PAGE']){
                $i->pageurl = $document_uri.'&'.$router[$uri]['PAGE'].'='.iPHP_PAGE_SIGN;
                iFS::checkHttp($i->pageurl) OR $i->pageurl = rtrim($surl,'/').'/'.$i->pageurl;
            }
            iFS::checkHttp($document_uri) OR $document_uri = rtrim($surl,'/').'/'.$document_uri;
            $i->href = $document_uri;
        }
        if($i->href) return $i;

        if(strpos($url,'{PHP}')===false) {
        	self::$uriArray	= array($array,$category,$_category);
            $i = self::build($url,$html_dir,$surl,$category['htmlext']);
            $pfile = $i->file;
            if(strpos($pfile,iPHP_PAGE_SIGN)===false) {
                $pfile = $i->name.'_'.iPHP_PAGE_SIGN.$i->ext;
            }
            $i->pageurl  = $i->hdir.'/'.$pfile ;
            $i->pagepath = $i->dir.'/'.$pfile;

            if($purl){
                $ii = self::build($purl,$html_dir,$surl,$category['htmlext']);
                $i->pageurl  = $ii->href;
                $i->pagepath = $ii->path;
                unset($ii);
            }
// var_dump($i);

			if($rule=='1') {
                $domainArray = iCache::get('category/domain');
// var_dump($domainArray);
                if($domainArray){
                    $m = $domainArray[$category['cid']];
                    if($m->domain) {
                        $i->href   = str_replace($i->hdir,$m->dmpath,$i->href);
                        $i->hdir   = $m->dmpath;
                        $i->dmdir  = iFS::path(iPATH.$html_dir.'/'.$m->pd);
                        $bits      = parse_url($i->href);
                        $i->domain = $bits['scheme'].'://'.$bits['host'];
                    }
                }
                if(iFS::checkHttp($category['domain'])){
                    $i->href = $category['domain'];
		        }
            }

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
            $i->ext  = self::$config['html_ext'];
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
