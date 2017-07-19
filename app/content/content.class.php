<?php
/**
* iCMS - i Content Management System
* Copyright (c) 2007-2017 iCMSdev.com. All rights reserved.
*
* @author icmsdev <master@icmsdev.com>
* @site https://www.icmsdev.com
* @licence https://www.icmsdev.com/LICENSE.html
*/
class content {
    public static $table = null;
    public static $union_key = null;

    public static function count_sql($sql=''){
        return "SELECT count(*) FROM `".self::$table."` {$sql}";
    }
    public static function check($value,$id=0,$field='title'){
        $sql = "SELECT `id` FROM `".self::$table."` where `{$field}` = '$value'";
        $id && $sql.=" AND `id` !='$id'";
        return iDB::value($sql);
    }

    public static function value($field='id',$id=0){
        if(empty($id)){
            return;
        }
        return iDB::value("SELECT {$field} FROM `".self::$table."` WHERE `id`='$id';");
    }
    public static function row($id=0,$field='*',$sql=''){
        return iDB::row("SELECT {$field} FROM `".self::$table."` WHERE `id`='$id' {$sql} LIMIT 1;",ARRAY_A);
    }
    public static function data($id=0,$adid=0,$userid=0){
        $userid && $sql = " AND `userid`='$userid'";
        $rs    = iDB::row("SELECT * FROM `".self::$table."` WHERE `id`='$id' {$sql} LIMIT 1;",ARRAY_A);
        if($rs){
            $aid   = $rs['id'];
            $adsql = "SELECT * FROM `".self::$table."_cdata` WHERE `".self::$union_key."`='$aid'";
            $adid && $adsql.= " AND `id`='{$adid}'";

            if($rs['chapter']){
                $adrs  = iDB::all($adsql,ARRAY_A);
            }else{
                $adrs  = iDB::row($adsql,ARRAY_A);
            }
        }
        return array($rs,$adrs);
    }
    public static function body($id=0){
        $body = iDB::value("SELECT * FROM `".self::$table."_cdata` WHERE `".self::$union_key."`='$id'");
        return $body;
    }

    public static function batch($data,$ids){
        if(empty($ids)){
            return;
        }
        foreach ( array_keys($data) as $k ){
            $bits[] = "`$k` = '$data[$k]'";
        }
        iDB::query("UPDATE `".self::$table."` SET " . implode( ', ', $bits ) . " WHERE `id` IN ($ids)");
    }
    public static function insert($data){
        return iDB::insert(self::$table,$data);
    }
    public static function update($data,$where){
        return iDB::update(self::$table,$data,$where);
    }
// --------------------------------------------------
    public static function data_fields($update=false){
        $fields  = array('subtitle', 'body');
        $update OR $fields  = array_merge ($fields,array('aid'));
        return $fields;
    }
    public static function data_insert($data){
        return iDB::insert(self::$table.'_cdata',$data);
    }
    public static function data_update($data,$where){
        return iDB::update(self::$table.'_cdata',$data,$where);
    }

    public static function del($id){
        iDB::query("DELETE FROM `".self::$table."` WHERE id='$id'");
    }
    public static function del_cdata($id,$f='aid'){
        iDB::query("DELETE FROM `".self::$table."_cdata` WHERE `$f`='$id'");
    }
}

