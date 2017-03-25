<?php
/**
* iCMS - i Content Management System
* Copyright (c) 2007-2017 idreamsoft.com iiimon Inc. All rights reserved.
*
* @author coolmoo <idreamsoft@qq.com>
* @site http://www.idreamsoft.com
* @licence http://www.idreamsoft.com/license.php
*/
define('iPHP_DEBUG', true);
define('iPHP_WAF_POST',false);
require dirname(__file__) . '/iCMS.php';
admincp::run();
