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

iACP::app('category','import');
class pushcategoryApp extends categoryApp {
    protected $name_text;
    protected $_uri;
    protected $_name;
    protected $_table;
    protected $_primary;

    function __construct() {
        parent::__construct(iCMS_APP_PUSH);
        $this->name_text = "版块";
        $this->_uri      = "push";
        $this->_name     = "推送";
        $this->_table    = "push";
        $this->_primary  = "cid";
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
