<?php
/**
* iPHP - i PHP Framework
* Copyright (c) 2012 iiiphp.com. All rights reserved.
*
* @author coolmoo <iiiphp@qq.com>
* @site http://www.iiiphp.com
* @licence http://www.iiiphp.com/license
* @version 1.0.1
* $Id: iPHP.php 2412 2014-05-04 09:52:07Z coolmoo $
*/
// ini_set('display_errors','OFF');
// error_reporting(0);//iPHP默认 不显示错误信息
// error_reporting(E_ALL & ~E_DEPRECATED); //Production
//define('iPHP', TRUE);
defined('iPHP') OR exit('What are you doing?');
version_compare('5.3',PHP_VERSION,'>') && die('iPHP requires PHP version 5.3 or higher. You are running version '.PHP_VERSION.'.');

ini_set('display_errors','ON');
error_reporting(E_ALL & ~E_NOTICE);

@ini_set('magic_quotes_sybase', 0);
@ini_set("magic_quotes_runtime",0);

define('iPHP_PATH',dirname(strtr(__FILE__,'\\','/')));

require_once iPHP_PATH.'/iPHP.version.php';
require_once iPHP_PATH.'/iPHP.define.php';
require_once iPHP_PATH.'/iPHP.compat.php';

header('Content-Type: text/html; charset='.iPHP_CHARSET);
header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');

if(function_exists('ini_get')) {
    $memorylimit = @ini_get('memory_limit');
    if($memorylimit && get_bytes($memorylimit) < 33554432 && function_exists('ini_set')) {
        ini_set('memory_limit', iPHP_MEMORY_LIMIT);
    }
}

function_exists('date_default_timezone_set') && date_default_timezone_set(iPHP_TIME_ZONE);

require_once iPHP_PATH.'/iPHP.class.php';
iPHP::timer_start();

set_error_handler(array('iPHP', 'error_handler'),E_ALL & ~E_NOTICE);
spl_autoload_register(array('iPHP', 'loader'), true, true);

//waf
iWAF::filter();
//security
iSecurity::filter();
iSecurity::GP('page','GP',2);

define('__SELF__',	$_SERVER['PHP_SELF']);
define('__REF__', 	$_SERVER['HTTP_REFERER']);
