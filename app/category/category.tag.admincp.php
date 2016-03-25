<?php
/**
* iCMS - i Content Management System
* Copyright (c) 2007-2012 idreamsoft.com iiimon Inc. All rights reserved.
*
* @author coolmoo <idreamsoft@qq.com>
* @site http://www.idreamsoft.com
* @licence http://www.idreamsoft.com/license.php
* @version 6.0.0
* @$Id: tagcategory.app.php 2372 2014-03-16 07:24:56Z coolmoo $
*/
defined('iPHP') OR exit('What are you doing?');

iPHP::app('category.admincp','import');
class tagcategoryAdmincp extends categoryAdmincp {
    function __construct() {
        parent::__construct(iCMS_APP_TAG);
        $this->category_name   = "分类";
        $this->_app            = 'tags';
        $this->_app_name       = '标签';
        $this->_app_table      = 'tags';
        $this->_app_cid        = 'tcid';
        // $this->_app_indexTPL   = '';
        // $this->_app_listTPL    = '';
        // $this->_app_contentTPL = '';
    }

}
