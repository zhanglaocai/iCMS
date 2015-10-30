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
version_compare('5.3',PHP_VERSION,'>') && die('您的服务器运行的 PHP 版本是'.PHP_VERSION.' 但 iPHP 要求至少 5.3。');

ini_set('display_errors','ON');
error_reporting(E_ALL & ~E_NOTICE);

@ini_set('magic_quotes_sybase', 0);
@ini_set("magic_quotes_runtime",0);

define('iPHP_PATH',dirname(strtr(__FILE__,'\\','/')));

require iPHP_PATH.'/iPHP.version.php';
require iPHP_PATH.'/iPHP.define.php';
header('Content-Type: text/html; charset='.iPHP_CHARSET);
header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');


if (function_exists('memory_get_usage') && ((int) @ini_get('memory_limit') < abs(intval(iPHP_MEMORY_LIMIT))))
    @ini_set('memory_limit', iPHP_MEMORY_LIMIT);

@ini_set('date.timezone',iPHP_TIME_ZONE);//设置时区
function_exists('date_default_timezone_set') && date_default_timezone_set(iPHP_TIME_ZONE);

require iPHP_PATH.'/iPHP.compat.php';
require iPHP_PATH.'/iPHP.class.php';

set_error_handler('iPHP_ERROR_HANDLER');

iPHP::timer_start();
//security
iPHP::LoadClass("Security",'S');
iS::filter();
iS::GP('page','GP',2);

define('__SELF__',	$_SERVER['PHP_SELF']);
define('__REF__', 	$_SERVER['HTTP_REFERER']);

$iDB_CLASS = 'Mysql';
(iPHP_DB_TYPE =='mysql'  && version_compare(PHP_VERSION,'5.5','>=')) && $iDB_CLASS='Mysqli';
iPHP_DB_TYPE  =='pgsql'  && $iDB_CLASS = 'Pgsql';
iPHP_DB_TYPE  =='sqlite' && $iDB_CLASS = 'SQLite';

iPHP::loadClass($iDB_CLASS,'DB'); //加载数据库操作类
iPHP::loadClass("FileSystem",'FS');	//加载文件操作类
iPHP::loadClass('Cache');			//加载缓存操作类
iPHP::loadClass("Template");		//加载模板操作类
