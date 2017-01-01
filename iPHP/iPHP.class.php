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
defined('iPHP') OR exit('What are you doing?');

class iPHP {
	public static $apps       = null;
	public static $app        = null;
	public static $app_name   = null;
	public static $app_do     = null;
	public static $app_method = null;
	public static $app_tpl    = null;
	public static $app_path   = null;
	public static $app_file   = null;
	public static $app_args   = null;
	public static $app_vars   = null;

	public static $iTPL       = NULL;
	public static $iVIEW      = null;
	public static $mobile     = false;
	public static $time_start = false;

    /**
     * Autoload function for HTML Purifier
     * @param string $class Class to load
     * @return bool
     */
	public static function loader($name,$core=null){
		//app_mo.class.php
		if(strpos($name,'_') !== false) {
			if(strpos($name,'Admincp') === false) {
				$file = $name.'.class';
				list($name,) = explode('_', $name);
			}
		}
		//app.app.php
		if(strpos($name,'App') !== false) {
			$app  = substr($name,0,-3);
			$file = $app.'.app';
			$path = iPHP_APP_DIR . '/' . $app . '/' . $file . '.php';
		}else if(strpos($name,'Admincp') !== false) {
			//app.admincp.php
			$app  = substr($name,0,-7);
			$file = $app.'.admincp';
			if(strpos($app,'_') !== false) {
				//app_mo.admincp.php
				list($app,) = explode('_', $name);
			}
			$path = iPHP_APP_DIR . '/' . $app . '/' . $file . '.php';
		}else if (strncmp('i', $name, 1) === 0) {
			//iclass.class.php
			$map = array(
				'iFS' => "iFileSystem",
				'iDB' => version_compare(PHP_VERSION,'5.5','>=')?'iMysqli':'iMysql'
			);
			$map[$name] && $name = $map[$name];
			$core===null && $core = iPHP_CORE;
			$path = $core.'/'.$name.'.class.php';
		}else if(iPHP::$apps[$name]){
			//app.class.php
			$file OR $file = $name.'.class';
			$path = iPHP_APP_DIR . '/' . $name . '/' . $file . '.php';
		}
		if (@is_file($path)) {
			$key = str_replace(iPATH, '/', $path);
			$GLOBALS['iPHP_REQ'][$key] = true;
			require_once $path;
		} else {
			self::error_throw("Unable to find class '$name'", 0020);
		}
	}
	public static function config() {
		$site = iPHP_MULTI_SITE ? $_SERVER['HTTP_HOST'] : iPHP_APP;
		if (iPHP_MULTI_DOMAIN) {
			//只绑定主域
			preg_match("/[^\.\/][\w\-]+\.[^\.\/]+$/", $site, $matches);
			$site = $matches[0];
		}
		iPHP_MULTI_SITE && define('iPHP_APP_SITE', $site);
		strpos($site, '..') === false OR self::error_throw('What are you doing','001');

		//config.php 中开启iPHP_APP_CONF后 此处设置无效,
		define('iPHP_APP_CONF', iPHP_CONF_DIR . '/' . $site); //网站配置目录
		define('iPHP_APP_CONFIG', iPHP_APP_CONF . '/config.php'); //网站配置文件
		@is_file(iPHP_APP_CONFIG) OR self::error_throw('Unable to find "' . $site . '" config file ('.iPHP_APP_CONFIG.').Please install '.iPHP_APP, '0001');

		$config = require iPHP_APP_CONFIG;
		//config.php 中开启后 此处设置无效
		defined('iPHP_DEBUG') OR define('iPHP_DEBUG', $config['debug']['php']); //程序调试模式
		defined('iPHP_DEBUG_TRACE') OR define('iPHP_DEBUG_TRACE', $config['debug']['php_trace']); //程序调试模式
		defined('iPHP_DB_DEBUG') OR define('iPHP_DB_DEBUG', $config['debug']['db']); //数据调试
		defined('iPHP_DB_TRACE') OR define('iPHP_DB_TRACE', $config['debug']['db_trace']); //SQL跟踪
		defined('iPHP_DB_EXPLAIN') OR define('iPHP_DB_EXPLAIN', $config['debug']['db_explain']); //SQL解释

		defined('iPHP_TPL_DEBUG') OR define('iPHP_TPL_DEBUG', $config['debug']['tpl']); //模板调试
		defined('iPHP_TPL_DEBUGGING') OR define('iPHP_TPL_DEBUGGING', $config['debug']['tpl_trace']); //模板数据调试

		defined('iPHP_TIME_CORRECT') OR define('iPHP_TIME_CORRECT', $config['time']['cvtime']);
		defined('iPHP_ROUTER_REWRITE') OR define('iPHP_ROUTER_REWRITE', $config['router']['rewrite']);
		defined('iPHP_APP_SITE') && $config['cache']['prefix'] = iPHP_APP_SITE;

		define('iPHP_ROUTER_USER', $config['router']['user_url']);
		define('iPHP_URL_404', $config['router']['404']); //404定义

		//config.php --END--

		ini_set('display_errors', 'OFF');
		error_reporting(0);

		if (iPHP_DEBUG ||iPHP_DB_DEBUG||iPHP_TPL_DEBUG) {
			ini_set('display_errors', 'ON');
			error_reporting(E_ALL & ~E_NOTICE);
		}

		$timezone = $config['time']['zone'];
		$timezone OR $timezone = 'Asia/Shanghai'; //设置中国时区
		function_exists('date_default_timezone_set') && @date_default_timezone_set($timezone);

		self::$apps = $config['apps'];
		self::define_app();
		iPHP_DB_DEBUG   && iDB::$show_errors  = true;
		iPHP_DB_TRACE   && iDB::$debug        = true;
		iPHP_DB_EXPLAIN && iDB::$show_explain = true;
		return $config;
	}
	public static function define_app() {
		foreach (self::$apps as $_app => $_appid) {
			define(iPHP_APP.'_APP_'.strtoupper($_app),$_appid);
		}
	}
	public static function run($app = NULL, $do = NULL, $args = NULL, $prefix = "do_") {
		//empty($app) && $app   = $_GET['app']; //单一入口
		if (empty($app)) {
			$fi = iFS::name(iPHP_SELF);
			$app = $fi['name'];
		}

		if (!self::$apps[$app] && iPHP_DEBUG) {
			iPHP::error_404('Unable to find application <b>' . $app . '</b>', '0001');
		}
		self::$app_path = iPHP_APP_DIR . '/' . $app;
		self::$app_file = self::$app_path . '/' . $app . '.app.php';
		is_file(self::$app_file) OR iPHP::error_404('Unable to find application <b>' . $app . '.app.php</b>', '0002');
		if ($do === NULL) {
			$do = iPHP_APP;
			$_GET['do'] && $do = iSecurity::escapeStr($_GET['do']);
		}
		if ($_POST['action']) {
			$do = iSecurity::escapeStr($_POST['action']);
			$prefix = 'ACTION_';
		}

		self::$app_name = $app;
		self::$app_do = $do;
		self::$app_method = $prefix . $do;
		self::$app_tpl = iPHP_APP_DIR . '/' . $app . '/template';
		self::$app_vars = array(
			"MOBILE" => iPHP::$mobile,
			'COOKIE_PRE' => iPHP_COOKIE_PRE,
			'REFER' => iPHP_REFERER,
			"APP" => array(
				'NAME' => self::$app_name,
				'DO' => self::$app_do,
				'METHOD' => self::$app_method,
			),
		);
		iPHP::$iTPL->_iVARS['SAPI'] .= self::$app_name;
		iPHP::$iTPL->_iVARS += self::$app_vars;

		$obj_name = $app.'App';
		self::$app = new $obj_name();

		if (self::$app_do && self::$app->methods) {
			in_array(self::$app_do, self::$app->methods) OR iPHP::error_404('Call to undefined method <b>' . self::$app_name . '::'.self::$app_method.'</b>', '0003');
			$method = self::$app_method;
			$args === null && $args = self::$app_args;
			if ($args) {
				if ($args === 'object') {
					return self::$app;
				}
				return call_user_func_array(array(self::$app, $method), (array) $args);
			} else {
				method_exists(self::$app, self::$app_method) OR iPHP::error_404('Call to undefined method <b>' . self::$app_name . '::'.self::$app_method.'</b>', '0004');
				return self::$app->$method();
			}
		} else {
			iPHP::error_404('Call to undefined method <b>' . self::$app_name . '::'.self::$app_method.'</b>', '0005');
		}
	}

	public static function app_vars($app_name = true, $out = false) {
		$app_name === true && $app_name = self::$app_name;
		$rs = iPHP::get_vars($app_name);
		return $rs['param'];
	}
	public static function get_vars($key = null) {
		return self::$iTPL->get_template_vars($key);
	}
	public static function clear_tpl($file = null) {
		if(empty(self::$iTPL)){
			iTemplate::init();
		}
		self::$iTPL->clear_compiled_tpl($file);
	}
	public static function assign($key, $value) {
		self::$iTPL->assign($key, $value);
	}
	public static function append($key, $value = null, $merge = false) {
		self::$iTPL->append($key, $value, $merge);
	}
	public static function clear($key) {
		self::$iTPL->clear_assign($key);
	}
	public static function display($tpl) {
		self::$iTPL->display($tpl);
	}
	public static function fetch($tpl) {
		return self::$iTPL->fetch($tpl);
	}
	public static function view($tpl, $p = 'index') {
		$tpl OR self::error_404('Please set the template file', '001', 'TPL');
		if (self::$iVIEW == 'html') {
			return self::$iTPL->fetch($tpl);
		} else {
			self::$iTPL->display($tpl);
			self::debug_info($tpl);
		}
	}
	public static function debug_info($tpl) {
		if (iPHP_DEBUG && iPHP_DEBUG_TRACE) {
			echo '<div class="well">';
			echo '<h3 class="label label-default">调试信息</h3>';
			echo '<span class="label label-success">模板:'.$tpl.' 内存:'.iFS::sizeUnit(memory_get_usage()).', 执行时间:'.self::timer_stop().'s, SQL累计执行:'.iDB::$num_queries.'次</span>';
			if(iDB::$debug_info && iPHP_DB_TRACE){
				echo '<br /><h3 class="label label-default">数据调用汇总:</h3>';
				echo '<pre class="alert alert-info">';
				print_r(iDB::$debug_info);
				echo '</pre>';
				iDB::$debug_info = null;
			}
			echo '</div>';
		}
	}

	public static function is_ajax() {
		return ($_SERVER["HTTP_X_REQUESTED_WITH"] == "XMLHttpRequest"||$_SERVER["X-Requested-With"] == "XMLHttpRequest");
	}
	public static function PG($key) {
		return isset($_POST[$key]) ? $_POST[$key] : $_GET[$key];
	}
	// 获取客户端IP
	public static function get_ip($format = 0) {
		if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
			$onlineip = getenv('HTTP_CLIENT_IP');
		} elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
			$onlineip = getenv('HTTP_X_FORWARDED_FOR');
		} elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
			$onlineip = getenv('REMOTE_ADDR');
		} elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
			$onlineip = $_SERVER['REMOTE_ADDR'];
		}
		preg_match("/[\d\.]{7,15}/", $onlineip, $onlineipmatches);
		$ip = $onlineipmatches[0] ? $onlineipmatches[0] : 'unknown';
		if ($format) {
			$ips = explode('.', $ip);
			for ($i = 0; $i < 3; $i++) {
				$ips[$i] = intval($ips[$i]);
			}
			return sprintf('%03d%03d%03d', $ips[0], $ips[1], $ips[2]);
		} else {
			return $ip;
		}
	}
	//设置COOKIE
	public static function set_cookie($name, $value = "", $life = 0, $httponly = false) {
		// $cookiedomain = iPHP_COOKIE_DOMAIN;
		$cookiedomain = '';
		$cookiepath = iPHP_COOKIE_PATH;

		$value = rawurlencode($value);
		$life = ($life ? $life : iPHP_COOKIE_TIME);
		$name = iPHP_COOKIE_PRE . '_' . $name;

		if (strpos(iPHP_SESSION, 'SESSION') !== false) {
			$_SESSION[$name] = $value;
		}
		if (strpos(iPHP_SESSION, 'COOKIE') !== false) {
			$_COOKIE[$name] = $value;
			$timestamp = time();
			$life = $life > 0 ? $timestamp + $life : ($life < 0 ? $timestamp - 31536000 : 0);
			$path = $httponly && PHP_VERSION < '5.2.0' ? $cookiepath . '; HttpOnly' : $cookiepath;
			$secure = $_SERVER['SERVER_PORT'] == 443 ? 1 : 0;
			if (PHP_VERSION < '5.2.0') {
				setcookie($name, $value, $life, $path, $cookiedomain, $secure);
			} else {
				setcookie($name, $value, $life, $path, $cookiedomain, $secure, $httponly);
			}
		}
	}
	//取得COOKIE
	public static function get_cookie($name) {
		$name = iPHP_COOKIE_PRE . '_' . $name;

		if (strpos(iPHP_SESSION, 'COOKIE') !== false) {
			$cvalue = $_COOKIE[$name];
			$cvalue = rawurldecode($cvalue);
		}
		if (strpos(iPHP_SESSION, 'SESSION') !== false) {
			$svalue = $_SESSION[$name];
			$svalue = rawurldecode($svalue);
		}
		if (iPHP_SESSION == 'SESSION+COOKIE') {
			if ($cvalue == $svalue) {
				return $svalue;
			} else if ($svalue) {
				return $svalue;
			} else if ($cvalue) {
				return $cvalue;
			}
		} else if (iPHP_SESSION == 'SESSION') {
			return $svalue;
		} else if (iPHP_SESSION == 'COOKIE') {
			return $cvalue;
		}
		return false;
	}

	public static function import($path, $dump = false) {
		$key = str_replace(iPATH, '/', $path);
		// $key =substr(md5($path), 8,16) ;
		if ($dump) {
			if (!isset($GLOBALS['iPHP_REQ'][$key])) {
				$GLOBALS['iPHP_REQ'][$key] = include $path;
			}
			return $GLOBALS['iPHP_REQ'][$key];
		}

		if (isset($GLOBALS['iPHP_REQ'][$key])) {
			return;
		}

		$GLOBALS['iPHP_REQ'][$key] = true;
		require $path;
	}

	public static function appid($app=null,$trans=false) {
		$array = self::$apps;
		$trans && $array = array_flip($array);
        if($array[$app]){
            return $array[$app];
        }
        return '0';
	}
	public static function app($app = NULL, $args = NULL) {
		$app_dir = $app_name = $app;
		$file_type = 'app';
		if (strpos($app, '.') !== false) {
			list($app_dir, $app_name, $file_type) = explode('.', $app);
			if (empty($file_type)) {
				$file_type = $app_name;
				$app_name = $app_dir;
			}
		}
		$app_file = $app_name.'.'.$file_type;
		switch ($file_type) {
			case 'class':$object = $app_name;break;
			case 'func':break;
			default:$object = $app_name . 'App';
		}
		$path = iPHP_APP_DIR . '/' . $app_dir . '/' . $app_file . '.php';
		if (@is_file($path)) {
			self::import($path);
		}else{
			return false;
		}
	}
	public static function vendor($name, $args = null) {
		iPHP::import(iPHP_LIB . '/vendor/Vendor.' . $name . '.php');
		if (function_exists($name)) {
			if($args === null){
				return $name();
			}
			return call_user_func_array($name, (array)$args);
		} else {
			return false;
		}
	}
    //------------------------------------
    public static function timeline(){
        $_timeline = iCache::get(iPHP_APP.'/timeline');
        //list($_today,$_week,$_month) = $_timeline ;
        $time     = $_SERVER['REQUEST_TIME'];
        $today    = get_date($time,"Ymd");
        $yday     = get_date($time-86400+1,"Ymd");
        $week     = get_date($time,"YW");
        $month    = get_date($time,"Ym");
        $timeline = array($today,$week,$month);
        $_timeline[0]==$today OR iCache::set(iPHP_APP.'/timeline',$timeline,0);
        //var_dump($_timeline,$timeline);
        return array(
            'yday'  => ($today-$_timeline[0]),
            'today' => ($_timeline[0]==$today),
            'week'  => ($_timeline[1]==$week),
            'month' => ($_timeline[2]==$month),
        );
    }
	/**
	 * Starts the timer, for debugging purposes
	 */
	public static function timer_start() {
		$mtime = microtime();
		$mtime = explode(' ', $mtime);
		self::$time_start = $mtime[1] + $mtime[0];
	}

	/**
	 * Stops the debugging timer
	 * @return int total time spent on the query, in milliseconds
	 */
	public static function timer_stop($restart=false) {
		$mtime = microtime();
		$mtime = explode(' ', $mtime);
		$time_end = $mtime[1] + $mtime[0];
		$time_total = $time_end - self::$time_start;
		$restart && self::$time_start = $time_end;
		return round($time_total, 4);
	}
	public static function p2num($path, $page = false) {
		$page === false && $page = $GLOBALS['page'];
		if ($page < 2) {
			return str_replace(array('_{P}', '&p={P}'), '', $path);
		}
		return str_replace('{P}', $page, $path);
	}
    public static function set_page_url($iurl){
        if(isset($GLOBALS['iPage'])) return;
        $iurl = (array)$iurl;
        $GLOBALS['iPage']['url']  = $iurl['pageurl'];
        $GLOBALS['iPage']['html'] = array('enable'=>true,'index'=>$iurl['href'],'ext'=>$iurl['ext']);
    }
	//模板翻页函数
	public static function page($conf) {
		$conf['lang'] = iUI::lang(iPHP_APP . ':page');
		$iPages = new iPages($conf);
		if ($iPages->totalpage > 1) {
			$pagenav = $conf['pagenav'] ? strtoupper($conf['pagenav']) : 'NAV';
			$pnstyle = $conf['pnstyle'] ? $conf['pnstyle'] : 0;
			iPHP::$iTPL->_iVARS['PAGE'] = array(
				$pagenav => $iPages->show($pnstyle),
				'COUNT' => $conf['total'],
				'TOTAL' => $iPages->totalpage,
				'CURRENT' => $iPages->nowindex,
				'PN' => $iPages->nowindex,
				'PREV' => $iPages->prev_page(),
				'NEXT' => $iPages->next_page(),
			);
			iPHP::$iTPL->_iVARS['PAGES'] = $iPages;
		}
		return $iPages;
	}
	public static function total($tnkey, $sql, $type = null) {
		$tnkey == 'sql.md5' && $tnkey = md5($sql);
		$tnkey = substr($tnkey, 8, 16);
		$total = (int) $_GET['total_num'];
		$cache_key = 'total/'.$tnkey;
		if (empty($total) && $type === null && !isset($_GET['total_cahce'])) {
			$total = (int) iCache::get($cache_key);
		}
		if (empty($total) || $type === 'nocache' || isset($_GET['total_cahce'])) {
			$total = iDB::value($sql);
			if ($type === null) {
				iCache::set($cache_key,$total,3600);
			}
		}
		return $total;
	}
	public static function redirect($URL = '') {
		$URL OR $URL = iPHP_REFERER;
		if (headers_sent()) {
			echo '<meta http-equiv=\'refresh\' content=\'0;url=' . $URL . '\'><script type="text/javascript">window.location.replace(\'' . $URL . '\');</script>';
		} else {
			header("Location: $URL");
		}
		exit;
	}
	public static function http_status($code, $ECODE = '') {
		static $_status = array(
			// Success 2xx
			200 => 'OK',
			// Redirection 3xx
			301 => 'Moved Permanently',
			302 => 'Moved Temporarily ', // 1.1
            304 => 'Not Modified',
			// Client Error 4xx
			400 => 'Bad Request',
			403 => 'Forbidden',
			404 => 'Not Found',
			// Server Error 5xx
			500 => 'Internal Server Error',
			503 => 'Service Unavailable',
		);
		if (isset($_status[$code])) {
			header('HTTP/1.1 ' . $code . ' ' . $_status[$code]);
			$ECODE && header("X-iPHP-ECODE:" . $ECODE);
		}
	}
	public static function error_throw($msg, $code) {
		trigger_error($msg, E_USER_ERROR);
	}
	public static function error_404($msg = "", $code = "") {
		iPHP_DEBUG && self::error_throw($msg, $code);
		self::http_status(404, $code);
		if (defined('iPHP_URL_404')) {
			iPHP_URL_404 && self::redirect(iPHP_URL_404 . '?url=' . urlencode($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']));
		}
		exit();
	}
	public static function error_handler($errno, $errstr, $errfile, $errline) {
		$errno = $errno & error_reporting();
	    if($errno == 0) return;
		defined('E_STRICT') OR define('E_STRICT', 2048);
		defined('E_RECOVERABLE_ERROR') OR define('E_RECOVERABLE_ERROR', 4096);
		switch ($errno) {
	        case E_ERROR:              $type = "Error";                  break;
	        case E_WARNING:            $type = "Warning";                break;
	        case E_PARSE:              $type = "Parse Error";            break;
	        case E_NOTICE:             $type = "Notice";                 break;
	        case E_CORE_ERROR:         $type = "Core Error";             break;
	        case E_CORE_WARNING:       $type = "Core Warning";           break;
	        case E_COMPILE_ERROR:      $type = "Compile Error";          break;
	        case E_COMPILE_WARNING:    $type = "Compile Warning";        break;
	        case E_USER_ERROR:         $type = "iPHP Error";             break;
	        case E_USER_WARNING:       $type = "iPHP Warning";           break;
	        case E_USER_NOTICE:        $type = "iPHP Notice";            break;
	        case E_STRICT:             $type = "Strict Notice";          break;
	        case E_RECOVERABLE_ERROR:  $type = "Recoverable Error";      break;
	        default:                   $type = "Unknown error ($errno)"; break;
		}
		$html = "<pre style='font-size: 14px;'>";
		$html.= "<b>{$type}:</b> {$errstr}\n";//in <b>{$errfile}</b> on line <b>{$errline}</b>
		if (function_exists('debug_backtrace')) {
			$backtrace = debug_backtrace();
			foreach ($backtrace as $i => $l) {
				$html .= "[$i] in function <b>{$l['class']}{$l['type']}{$l['function']}</b>";
				$l['file'] && $html .= " in <b>{$l['file']}</b>";
				$l['line'] && $html .= " on line <b>{$l['line']}</b>";
				$html .= "\n";
			}
		}
		$html .= "</pre>";
		$html = str_replace('\\', '/', $html);
		$html = str_replace(iPATH, 'iPHP://', $html);
	    if(PHP_SAPI=='cli'){
	        $html = str_replace(array("<b>", "</b>", "<pre>", "</pre>"), array("\033[31m","\033[0m",''), $html);
	        echo $html."\n";
	        exit;
	    }
		if (isset($_GET['frame'])) {
			iUI::$dialog['lock'] = true;
			$html = str_replace("\n", '<br />', $html);
			iUI::dialog(array("warning:#:warning-sign:#:{$html}", '系统错误!可发邮件到 idreamsoft@qq.com 反馈错误!我们将及时处理'), 'js:1', 30000000);
			exit;
		}
		if ($_POST) {
	        if(iPHP::is_ajax()){
	            $array = array('code'=>'0','msg'=>$html);
	            echo json_encode($array);
	        }else{
	            $html = str_replace(array("\r", "\\", "\"", "\n", "<b>", "</b>", "<pre>", "</pre>"), array(' ', "\\\\", "\\\"", '\n', ''), $html);
	            echo '<script>top.alert("' . $html . '")</script>';
	        }
	        exit;
	    }
	    @header('HTTP/1.1 500 Internal Server Error');
	    @header('Status: 500 Internal Server Error');
	    @header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	    @header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	    @header("Cache-Control: no-store, no-cache, must-revalidate");
	    @header("Cache-Control: post-check=0, pre-check=0", false);
	    @header("Pragma: no-cache");
	    @header("X-iPHP-ERROR:" . $errstr);
		$html = str_replace("\n", '<br />', $html);
		exit($html);
	}
}
