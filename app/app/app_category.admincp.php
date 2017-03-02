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

class app_categoryAdmincp extends categoryAdmincp {
    public function __construct($app) {
        $table = apps::get_table($app);
        parent::__construct($app['id'],'category');

        $this->category_name   = "栏目";
        $this->_app            = $app['app'];
        $this->_app_name       = $app['name'];
        $this->_app_table      = $table['name'];
        $this->_app_cid        = 'cid';
        /**
         *  模板
         */
        $this->category_template = array(
            'index'     => array('首页','{iTPL}/app.category.index.htm'),
            'list'      => array('列表','{iTPL}/app.category.list.htm'),
            $app['app'] => array($app['name'],'{iTPL}/app.content.htm')
        );

        /**
         *  URL规则
         */
        $this->category_rule+= array(
            $app['app'] => array($app['name'],'/{CDIR}/{YYYY}/{MM}{DD}/{ID}{EXT}','{ID},{0xID},{LINK}')
        );
        /**
         *  URL规则选项
         */
        $this->category_rule_list+= array(
            $app['app'] => array(
                array('----'),
                array('{ID}',$app['name'].'ID'),
                array('{0xID}','8位ID'),
                array('{0x3ID}','8位ID(前3位)',fasle),
                array('{0x3,2ID}','8位ID',fasle),
                array('{TITLE}','标题',fasle),
            )
        );
    }
    // public function do_add(){
    //     $this->_view_tpl_dir = $this->_app;
    //     parent::do_add();
    // }
}
