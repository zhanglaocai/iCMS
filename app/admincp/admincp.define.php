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
define('__ADMINCP__', __SELF__ . '?app');
define('ACP_PATH', iPHP_APP_DIR . '/admincp');
define('ACP_HOST', (($_SERVER['SERVER_PORT'] == 443)?'https':'http')."://" . $_SERVER['HTTP_HOST']);

iDB::$debug        = true;
iDB::$show_errors  = true;
iDB::$show_explain = false;
iUI::$dialog['title'] = 'iCMS';

iCMS::core('Menu');
iCMS::core('Member');

iMember::$LOGIN_PAGE = ACP_PATH.'/template/admincp.login.php';
iMember::$AUTH       = 'ADMIN_AUTH';
iMember::$AJAX       = iPHP::PG('ajax');
