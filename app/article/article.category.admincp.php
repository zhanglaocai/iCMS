<?php
/**
* iCMS - i Content Management System
* Copyright (c) 2007-2012 idreamsoft.com iiimon Inc. All rights reserved.
*
* @author coolmoo <idreamsoft@qq.com>
* @site http://www.idreamsoft.com
* @licence http://www.idreamsoft.com/license.php
* @version 6.0.0
* @$Id: article category.admincp.php 2374 2014-03-17 11:46:13Z coolmoo $
*/
defined('iPHP') OR exit('What are you doing?');

iPHP::app('category.admincp','include');

class articlecategoryAdmincp extends categoryAdmincp {
    function __construct() {
        parent::__construct(iCMS_APP_ARTICLE,'category');
        $this->category_name     = "栏目";
        $this->_app              = 'article';
        $this->_app_name         = '文章';
        $this->_app_table        = 'article';
        $this->_app_cid          = 'cid';
        $this->_app_contentTPL   = '{iTPL}/article.htm';
        $this->_contentRule_name = '文章';
    }
}
