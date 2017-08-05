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

define('__ADMINCP__', iPHP_SELF . '?app');
define('ACP_PATH', iPHP_APP_DIR . '/admincp');
define('ACP_HOST', (($_SERVER['SERVER_PORT'] == 443)?'https':'http')."://" . $_SERVER['HTTP_HOST']);

$git_ver_file = iPHP_APP_CORE.'/git.version.php';
file_exists($git_ver_file) && require_once $git_ver_file;

class admincp {
	public static $apps       = NULL;
	public static $callback   = NULL;
	public static $APP_OBJ    = NULL;
	public static $APP_NAME   = NULL;
	public static $APP_DO     = NULL;
	public static $APP_METHOD = NULL;
	public static $APP_PATH   = NULL;
	public static $APP_TPL    = NULL;
	public static $APP_FILE   = NULL;
	public static $APP_DIR    = NULL;
	public static $APP_ARGS   = NULL;

	public static function init() {
		self::check_seccode(); //验证码验证

		iUI::$dialog['title'] = iPHP_APP;
		iDB::$show_errors     = true;
		iDB::$show_trace      = false;
		iDB::$show_explain    = false;

		members::$LOGIN_PAGE  = ACP_PATH.'/template/admincp.login.php';
		members::$GATEWAY     = iPHP::PG('gateway');
		members::check_login(); //用户登陆验证
		members::check_priv('ADMINCP','page');//检查是否有后台权限

		files::init(array('userid'=> members::$userid));
		//菜单
		menu::init();
		menu::$callback = array(
			"priv" => array("members","check_priv"),
			"hkey" => members::$userid
        );

        admincp::$callback = array(
			"history" => array("menu","history"),
			"priv"    => array("members","check_priv")
        );
	}

	public static function get_seccode() {
		iSeccode::run('admincp');
	}
	public static function check_seccode() {
		if ($_POST['admincp_seccode'] === iPHP_KEY) {
			return true;
		}

		if ($_POST['username'] && $_POST['password']) {
			$seccode = iSecurity::escapeStr($_POST['admincp_seccode']);
			iSeccode::check($seccode, true, 'admincp_seccode') OR iUI::code(0, 'iCMS:seccode:error', 'seccode', 'json');
		}
	}

	public static function run($args = NULL, $prefix = "do_") {
		self::init();
		$app = $_GET['app'];
		$app OR $app = 'admincp';
		$do OR $do = $_GET['do'] ? (string) $_GET['do'] : 'iCMS';
		if ($_POST['action']) {
			$do = $_POST['action'];
			$prefix = 'ACTION_';
		}

		strpos($app, '..') === false OR exit('what the fuck');

		self::$APP_NAME   = $app;
		self::$APP_DO     = $do;
		self::$APP_METHOD = $prefix . $do;

		self::$APP_PATH   = ACP_PATH;
		self::$APP_TPL    = ACP_PATH . '/template';
		self::$APP_FILE   = ACP_PATH . '/' . $app . '.app.php';

		$obj_name = self::$APP_NAME . 'App';

		//app_category.admincp.php
		if(!is_file(self::$APP_FILE)){
			$app_file = $app . '.admincp.php';
			$obj_name = $app.'Admincp';
	        if(stripos($app, '_')!== false){
	            list($app,$sapp) = explode('_', $app);
	        }
			self::$APP_PATH = iPHP_APP_DIR . '/' . $app;
			self::$APP_TPL  = self::$APP_PATH . '/admincp';
			self::$APP_FILE = self::$APP_PATH . '/'.$app_file;
		}
		//自定义APP内容管理
		if(!is_file(self::$APP_FILE)){
			$appData = apps::get_app($app);
			if($appData){
				$sapp && $sapp_name = '_'.$sapp;
				$app_file = 'content'.$sapp_name.'.admincp.php';
				$obj_name = 'content'.$sapp_name.'Admincp';
				self::$APP_PATH = iPHP_APP_DIR . '/content';
				self::$APP_TPL  = self::$APP_PATH . '/admincp';
				self::$APP_FILE = self::$APP_PATH . '/'.$app_file;
			}else{

			}
		}

		is_file(self::$APP_FILE) OR iPHP::error_throw('Unable to find admincp file <b>' .self::$APP_NAME. '.admincp.php</b>('.self::$APP_FILE.')', 1002);

		define('APP_URI', __ADMINCP__ . '=' . self::$APP_NAME);
		// define('APP_FURI', APP_URI . '&frame=iPHP');
		define('APP_FURI', APP_URI );
		define('APP_DOURI', APP_URI . ($do != 'iCMS' ? '&do=' . $do : ''));
		define('APP_BOXID', self::$APP_NAME . '-box');
		define('APP_FORMID', 'iCMS-' . APP_BOXID);

		self::$APP_OBJ = new $obj_name($appData?$appData:null);
		$app_methods   = get_class_methods(self::$APP_OBJ);
		in_array(self::$APP_METHOD, $app_methods) OR iPHP::error_throw('Call to undefined method <b>' . $obj_name . '::' . self::$APP_METHOD . '</b>', 1003);

		//访问记录
		iPHP::callback(self::$callback['history'],APP_DOURI);
		//检查URL权限
		iPHP::callback(self::$callback['priv'],array(APP_DOURI,'page'));
		//默认开启
		iCMS::$config['debug']['access_log'] OR self::access_log();

		$method = self::$APP_METHOD;
		$args === null && $args = self::$APP_ARGS;

		if ($args) {
			if ($args === 'object') {
				return self::$APP_OBJ;
			}
			return self::$APP_OBJ->$method($args);
		} else {
			return self::$APP_OBJ->$method();
		}
	}

	public static function view($p = NULL, $app=null) {
		if ($p === NULL && self::$APP_NAME) {
			$p = self::$APP_NAME;
			self::$APP_DO && $p .= '.' . self::$APP_DO;
		}
		$APP_TPL = self::$APP_TPL;
		if($app){
			if($app=='admincp'){
				$APP_TPL = ACP_PATH . '/template';
			}else{
				$APP_TPL = iPHP_APP_DIR.'/'.$app.'/admincp';
			}
		}
		$_path = $APP_TPL . '/' . $p . '.def.php';
		if(file_exists($_path)){
			return $_path;
		}
		$path = $APP_TPL . '/' . $p . '.php';
		return $path;
	}

	public static function head($navbar = true) {
		$body_class = '';
		if (iCMS::$config['other']['sidebar_enable']) {
			iCMS::$config['other']['sidebar'] OR $body_class = 'sidebar-mini';
			$body_class = iPHP::get_cookie('ACP_sidebar_mini') ? 'sidebar-mini' : '';
		} else {
			$body_class = 'sidebar-display';
		}
		$navbar === false && $body_class = 'iframe ';

		include self::view("admincp.header",'admincp');
		$navbar === true && include self::view("admincp.navbar",'admincp');
	}

	public static function foot() {
		include self::view("admincp.footer",'admincp');
	}
	public static function error_page($msg) {
		admincp::head();
		echo '
		<div class="alert">
		  <button type="button" class="close" data-dismiss="alert">&times;</button>
		  <strong>Warning!</strong> '.$msg.'
		</div>';
		admincp::foot();
		exit();
	}
	public static function access_log() {
		$access = array(
			'uid'       => members::$userid,
			'username'  => members::$nickname,
			'app'       => self::$APP_NAME,
			'uri'       => $_SERVER['REQUEST_URI'],
			'useragent' => $_SERVER['HTTP_USER_AGENT'],
			'ip'        => iPHP::get_ip(),
			'method'    => $_SERVER['REQUEST_METHOD'],
			'referer'   => $_SERVER['HTTP_REFERER'],
			'addtime'   => $_SERVER['REQUEST_TIME'],
		);
		iDB::insert("access_log",$access);
	}
    public static function uri($q,$a){
    	$qs = $q;
    	is_array($q) OR parse_str($q, $qs);
        $query = array_merge((array)$a,(array)$qs);
        return iURL::make($query,APP_DOURI);
    }
	public static function debug_info(){
		$memory = memory_get_usage();
		return "使用内存:".iFS::sizeUnit($memory)." 执行时间:".iPHP::timer_stop()."s";
	}
}

if($_GET['do'] == 'seccode'){
	admincp::get_seccode();
}
