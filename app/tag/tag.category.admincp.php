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
class tagCategoryAdmincp extends categoryAdmincp {
    public function __construct() {
        parent::__construct(iCMS_APP_TAG,'category');
        $this->category_name     = "分类";
        $this->_app              = 'tags';
        $this->_app_name         = '标签';
        $this->_app_table        = 'tags';
        $this->_app_cid          = 'tcid';
       /**
         *  模板
         */
        $this->category_template+=array(
            'tag'     => array('标签','{iTPL}/tag.index.htm'),
        );

        /**
         *  URL规则
         */
        $this->category_rule+= array(
            'tag'     => array('标签','/{CDIR}/{TKEY}{EXT}','{ID},{0xID},{TKEY},{NAME},{ZH_CN}')
        );
        /**
         *  URL规则选项
         */
        $this->category_rule_list+= array(
            'tag' => array(
                array('----'),
                array('{ID}','标签ID'),
                array('{0xID}','8位ID'),
                array('{TKEY}','标签标识'),
                array('{ZH_CN}','标签名(中文)'),
                array('{NAME}','标签名'),
                array('----'),
                array('{TCID}','分类ID',fasle),
                array('{TCDIR}','分类目录',fasle),
            ),
        );
    }

}
