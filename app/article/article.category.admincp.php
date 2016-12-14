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

class articlecategoryAdmincp extends categoryAdmincp {
    function __construct() {
        parent::__construct(iCMS_APP_ARTICLE,'category');
        $this->category_name            = "栏目";
        $this->_app                     = 'article';
        $this->_app_name                = '文章';
        $this->_app_table               = 'article';
        $this->_app_cid                 = 'cid';
        /**
         *  模板
         */
        $this->category_template+=array(
            'article' => array('文章','{iTPL}/article.htm'),
            'tag'     => array('标签','{iTPL}/tag.index.htm'),
        );

        /**
         *  URL规则
         */
        $this->category_rule+= array(
            'article' => array('文章','/{CDIR}/{YYYY}/{MM}{DD}/{ID}{EXT}','{ID},{0xID},{LINK}'),
            'tag'     => array('标签','/{CDIR}/{TKEY}{EXT}','{ID},{0xID},{TKEY},{NAME},{ZH_CN}')
        );
        /**
         *  URL规则选项
         */
        $this->category_rule_list+= array(
            'article' => array(
                array('----'),
                array('{ID}','文章ID'),
                array('{0xID}','8位ID'),
                array('{LINK}','自定义链接'),
                array('{0x3ID}','8位ID(前3位)',fasle),
                array('{0x3,2ID}','8位ID',fasle),
                array('{TITLE}','文章标题',fasle),
            ),
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
