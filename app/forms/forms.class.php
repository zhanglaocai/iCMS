<?php
/**
* iCMS - i Content Management System
* Copyright (c) 2007-2012 idreamsoft.com iiimon Inc. All rights reserved.
*
* @author coolmoo <idreamsoft@qq.com>
* @site http://www.idreamsoft.com
* @licence http://www.idreamsoft.com/license.php
* @version 6.2.0
*/
defined('iPHP') OR exit('What are you doing?');

class forms{

    public static function base_fields_index(){
        return array(
            'index_id' =>'KEY `id` (`status`,`id`)',
        );
    }
    public static function base_fields_json(){
      return '{
        "id": "id=id&label=内容id&comment=主键%20自增ID&field=PRIMARY&name=id&default=&type=PRIMARY&len=10&class=span2",
        "status": "id=status&label=状态&comment=0:草稿;1:正常;2:回收;3:审核;4:不合格&option=草稿=0;正常=1;回收=2;审核=3;不合格=4;&field=TINYINT&name=status&default=1&type=select&len=1&class=chosen-select span3"
      }';
    }
    public static function base_fields_array(){
      $sql = implode(",\n", self::base_fields_sql());
      preg_match_all("@`(.+)`\s(.+)\sDEFAULT\s'(.*?)'\sCOMMENT\s'(.+)'@", $sql, $matches);
      return $matches;
    }
    public static function base_fields_sql(){
        return array(
            'status' =>"`status` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '状态 0:草稿;1:正常;2:回收;3:审核;4:不合格'",
        );
    }
    public static function get($vars=0,$field='id'){
        if(empty($vars)) return array();
        if($vars=='all'){
            $sql      = '1=1';
            $is_multi = true;
        }else{
            list($vars,$is_multi)  = iSQL::multi_var($vars);
            $sql  = iSQL::in($vars,$field,false,true);
        }
        $data = array();
        $rs   = iDB::all("SELECT * FROM `#iCMS@__forms` where {$sql}",OBJECT);
        if($rs){
            if($is_multi){
                $_count = count($rs);
                for ($i=0; $i < $_count; $i++) {
                    $data[$rs[$i]->$field]= apps::item($rs[$i]);
                }
            }else{
                $data = apps::item($rs[0]);
            }
        }
        if(empty($data)){
            return;
        }
        return $data;
    }
}
