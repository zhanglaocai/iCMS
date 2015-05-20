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

iACP::app('category','import');
class tagcategoryApp extends categoryApp {
    protected $name_text;
    protected $_uri;
    protected $_name;
    protected $_table;
    protected $_primary;
    function __construct() {
        parent::__construct(iCMS_APP_TAG);
        $this->name_text    = "分类";
        $this->_uri      = "tags";
        $this->_name     = "标签";
        $this->_table    = "tags";
        $this->_primary  = "tcid";
    }
    function merge($tocid,$cid){
        iDB::query("UPDATE `#iCMS@__".$this->_table."` SET `".$this->_primary."` ='$tocid' WHERE `".$this->_primary."` ='$cid'");
    }
    function update_count($cid){
        $cc = iDB::value("SELECT count(*) FROM `#iCMS@__".$this->_table."` where `".$this->_primary."`='$cid'");
        iDB::query("UPDATE `#iCMS@__category` SET `count` ='$cc' WHERE `".$this->_primary."` ='$cid'");
    }
    function listbtn($C){
        return $this->treebtn($C);
    }
    function treebtn($C){
        return '<a href="'.__ADMINCP__.'='.$this->_uri.'&do=add&'.$this->_primary.'='.$C['cid'].'" class="btn btn-small"><i class="fa fa-edit"></i> '.$this->_name.'</a>
        <a href="'.__ADMINCP__.'='.$this->_uri.'&do=manage&'.$this->_primary.'='.$C['cid'].'&sub=on" class="btn btn-small"><i class="fa fa-list-alt"></i> '.$this->_name.'管理</a> ';
    }
    function batchbtn(){}
}
