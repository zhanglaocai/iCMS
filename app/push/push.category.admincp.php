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

iPHP::app('category.admincp','include');
class pushCategoryAdmincp extends categoryAdmincp {
    public function __construct() {
        parent::__construct(iCMS_APP_PUSH,'category');
        $this->category_name   = "版块";
        $this->_app            = 'push';
        $this->_app_name       = '推荐';
        $this->_app_table      = 'push';
        $this->_app_cid        = 'cid';
       // /**
       //   *  模板
       //   */
       //  $this->category_template+=array(
       //      'push' => array('推荐','{iTPL}/push.index.htm'),
       //  );

       //  /**
       //   *  URL规则
       //   */
       //  $this->category_rule+= array(
       //      'push' => array('推荐','/{CDIR}/{TKEY}{EXT}','{ID},{0xID}')
       //  );
       //  /**
       //   *  URL规则选项
       //   */
       //  $this->category_rule_list+= array(
       //      'tag' => array(
       //          array('----'),
       //          array('{ID}','推荐ID'),
       //          array('{0xID}','8位ID'),
       //      ),
       //  );
    }
    public function do_add(){
        $this->_view_tpl_dir = $this->_app;
        parent::do_add();
    }
}
