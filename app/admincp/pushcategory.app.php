<?php
/**
* iCMS - i Content Management System
* Copyright (c) 2007-2012 idreamsoft.com iiimon Inc. All rights reserved.
*
* @author coolmoo <idreamsoft@qq.com>
* @site http://www.idreamsoft.com
* @licence http://www.idreamsoft.com/license.php
* @version 6.0.0
* @$Id: pushcategory.app.php 2374 2014-03-17 11:46:13Z coolmoo $
*/
defined('iPHP') OR exit('What are you doing?');

iPHP::app('category.admincp','import');
class pushcategoryApp extends categoryApp {
    function __construct() {
        parent::__construct(iCMS_APP_PUSH);
        $this->category_name   = "版块";
        $this->_app            = 'push';
        $this->_app_name       = '推送';
        $this->_app_table      = 'push';
        $this->_app_cid        = 'cid';
        // $this->_app_indexTPL   = '';
        // $this->_app_listTPL    = '';
        // $this->_app_contentTPL = '';
    }
}
