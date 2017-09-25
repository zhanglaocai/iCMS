<?php
/**
* iCMS - i Content Management System
* Copyright (c) 2007-2017 iCMSdev.com. All rights reserved.
*
* @author icmsdev <master@icmsdev.com>
* @site https://www.icmsdev.com
* @licence https://www.icmsdev.com/LICENSE.html
*/

class apps_meta {
    public static $CREATE_TABLE = true;
    public static $data = null;
    public static function data($app,$ids){
        if(empty($ids)) return array();

        $table = self::table($app);

        list($ids,$is_multi)  = iSQL::multi_var($ids);
        $sql  = iSQL::in($ids,'id',false,true);
        $data = array();
        $rs   = iDB::all("SELECT * FROM `#iCMS@__{$table}` where {$sql}");
        if($rs){
            $_count = count($rs);
            for ($i=0; $i < $_count; $i++) {
                $data[$rs[$i]['id']]['meta'] = json_decode($rs[$i]['data'],true);
            }
            $is_multi OR $data = $data[$ids];
        }

        if(empty($data)){
            return;
        }
        return $data;
    }
    public static function post($pkey='metadata'){
        $metadata = iSecurity::escapeStr($_POST[$pkey]);
        if($metadata){
            $_metadata = array();
            foreach($metadata AS $mdk=>$md){
                if(is_array($md)){
                    if($md['name']){
                        $md['key'] OR $md['key'] = strtolower(iPinyin::get($md['name']));
                        preg_match("/[a-zA-Z0-9_\-]/",$md['key']) OR iUI::alert('只能由英文字母、数字或_-组成,不支持中文');
                        $md['key'] = trim($md['key']);
                    }
                    $_metadata[$md['key']] = $md;
                }else{
                    $_metadata[$mdk] = array('name'=>$mdk,'key'=>$mdk,'value'=>$md);
                }
            }
            $metadata = addslashes(json_encode($_metadata));
        }
        return $metadata;
    }
    public static function table_array($app,$create=true){
        self::$CREATE_TABLE = $create;
        $table = self::table($app);
        return array($table=>array($table,'id',null,'动态属性'));
    }
    public static function table($app){
        if(is_numeric($app)){
            $a   = apps::get($app);
            $app = $a['app'];
        }
        empty($app) && trigger_error('META name is empty!',E_USER_ERROR);

        $table = $app.'_meta';
        self::$CREATE_TABLE && self::create($table);
        return $table;
    }
    public static function get($app,$id,$index=true){
        $table = self::table($app);
        $json = iDB::value("SELECT `data` FROM `#iCMS@__{$table}` where `id` = '$id'");
        if($json){
            $data = json_decode($json,true);
            if($index){
                foreach ($data as $key => $value) {
                    $_data[$value['key']] = $value;
                }
                $data = $_data;
                unset($_data);
            }
            self::$data = $data;
            unset($data);
        }
    }
    public static function save($app,$id,$data=null){
        $data===null && $data = self::post();

        $table = self::table($app);
        $check = iDB::value("SELECT `id` FROM `#iCMS@__{$table}` where `id` = '$id'");
        if($check){
            iDB::update($table, array('data'=>$data), array('id'=>$id));
        }else{
            $data && iDB::insert($table,array('id'=>$id,'data'=>$data));
        }
    }
    public static function create($table){
        // if(!self::$CREATE_TABLE) return;
        if(!iDB::check_table($table)){
            iDB::query("
                CREATE TABLE `#iCMS@__{$table}` (
                  `id` int(10) unsigned NOT NULL,
                  `data` mediumtext NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8
            ");
        }
    }
}
