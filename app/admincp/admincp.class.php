<?php

/**
 * iCMS - i Content Management System
 * Copyright (c) 2007-2012 idreamsoft.com iiimon Inc. All rights reserved.
 *
 * @author coolmoo <idreamsoft@qq.com>
 * @site http://www.idreamsoft.com
 * @licence http://www.idreamsoft.com/license.php
 * @version 6.0.0
 */
defined('iPHP') OR exit('What are you doing?');

define('iCMS_SUPERADMIN_UID', '1');
define('__ADMINCP__', iPHP_SELF . '?app');
define('ACP_PATH', iPHP_APP_DIR . '/admincp');
define('ACP_HOST', (($_SERVER['SERVER_PORT'] == 443)?'https':'http')."://" . $_SERVER['HTTP_HOST']);

iDB::$show_errors     = true;
iDB::$show_trace      = false;
iDB::$show_explain    = false;
iUI::$dialog['title'] = iPHP_APP;

members::$LOGIN_PAGE  = ACP_PATH.'/template/admincp.login.php';
members::$AUTH        = 'ADMIN_AUTH';
members::$AJAX        = iPHP::PG('ajax');


class admincp {
	public static $apps       = NULL;
	public static $menu       = NULL;
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
		members::checkLogin(); //用户登陆验证
		self::$menu = new menu(); //初始化菜单
		self::MP('ADMINCP', 'page'); //检查是否有后台权限
		self::MP('__MID__', 'page'); //检查菜单ID
		iFile::init(array(
			'userid'    => members::$userid,
			'watermark' => iCMS::$config['watermark']
		));
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
		self::$menu->url  = __ADMINCP__.'='.$app.(($do&&$do!='iCMS')?'&do='.$do:'');
		$obj_name = self::$APP_NAME . 'App';

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
		is_file(self::$APP_FILE) OR iPHP::error_throw('Unable to find admincp file <b>' .self::$APP_NAME. '.admincp.php</b>', 1002);

		define('APP_URI', __ADMINCP__ . '=' . self::$APP_NAME);
		// define('APP_FURI', APP_URI . '&frame=iPHP');
		define('APP_FURI', APP_URI );
		define('APP_DOURI', APP_URI . ($do != 'iCMS' ? '&do=' . $do : ''));
		define('APP_BOXID', self::$APP_NAME . '-box');
		define('APP_FORMID', 'iCMS-' . APP_BOXID);

		self::$APP_OBJ = new $obj_name();
		$app_methods = get_class_methods(self::$APP_OBJ);
		in_array(self::$APP_METHOD, $app_methods) OR iPHP::error_throw('Call to undefined method <b>' . $obj_name . '::' . self::$APP_METHOD . '</b>', 1003);

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

	// public static function set_app_tpl($app){
	// 	self::$APP_TPL = iPHP_APP_DIR.'/'.$app.'/admincp';
	// }
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
		$path = $APP_TPL . '/' . $p . '.php';
		return $path;
	}

	public static function update_args($data = '') {
		$array = array();
		$dA = explode('_', $data);
		foreach ((array) $dA as $d) {
			list($f, $v) = explode(':', $d);
			$v == 'now' && $v = time();
			$v = (int) $v;
			$array[$f] = $v;
		}
		return $array;
	}
	public static function MP($p, $ret = '') {
		if (self::is_superadmin()) {
			return true;
		}

		self::$menu->power = (array) members::$mpower;
		if ($p === '__MID__') {
			$rt1 = $rt2 = $rt3 = true;
			self::$menu->rootid && $rt1 = self::$menu->check_power(self::$menu->rootid);
			self::$menu->parentid && $rt2 = self::$menu->check_power(self::$menu->parentid);
			self::$menu->do_mid && $rt3 = self::$menu->check_power(self::$menu->do_mid);
			if ($rt1 && $rt2 && $rt3) {
				return true;
			}
			self::permission_msg($p, $ret);
		}
		$rt = self::$menu->check_power($p);
		$rt OR self::permission_msg($p, $ret);
		return $rt;
	}
	public static function CP($p, $act = '', $ret = '') {
		if (self::is_superadmin()) {
			return true;
		}

		if ($p === '__CID__') {
			foreach ((array) members::$cpower as $key => $_cid) {
				if (!strstr($value, ':')) {
					self::CP($_cid, $act) && $cids[] = $_cid;
				}
			}
			return $cids;
		}

		$act && $p = $p . ':' . $act;

		$rt = members::check_power((string) $p, members::$cpower);
		$rt OR self::permission_msg($p, $ret);
		return $rt;
	}
	public static function permission_msg($p = '', $ret = '') {
		if ($ret == 'alert') {
			iUI::alert('您没有相关权限!');
			exit;
		} elseif ($ret == 'page') {
			include self::view("admincp.permission",'admincp');
			exit;
		}
	}
	public static function is_superadmin() {
		return (members::$data->gid === iCMS_SUPERADMIN_UID);
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
	public static function pic_btn_group($callback, $indexid = 0, $type = 'pic') {
		include self::view("admincp.picbtngroup",'admincp');
	}

	public static function callback($id, &$that, $type = null) {
		if ($type === null || $type == 'primary') {
			if ($that->callback['primary']) {
				$PCB = $that->callback['primary'];
				$handler = $PCB[0];
				$params = (array) $PCB[1] + array('indexid' => $id);
				if (is_callable($handler)) {
					call_user_func_array($handler, $params);
				}
			}
		}
		if ($type === null || $type == 'data') {
			if ($that->callback['data']) {
				$DCB = $that->callback['data'];
				$handler = $DCB[0];
				$params = (array) $DCB[1];
				if (is_callable($handler)) {
					call_user_func_array($handler, $params);
				}
			}
		}

	}
	public static function debug_info(){
		$memory = memory_get_usage();
		return "使用内存:".iFS::sizeUnit($memory)." 执行时间:".iPHP::timer_stop()."s";
	}
}

if($_GET['do'] == 'seccode'){
	admincp::get_seccode();
}
