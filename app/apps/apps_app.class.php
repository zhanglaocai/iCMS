<?php
/**
 * @package iCMS
 * @copyright 2007-2017, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */

class apps_app {
    public static function find_MEDIUMTEXT(&$json_field) {
        $addons_json_field = array();
        foreach ($json_field as $key => $value) {
            $a = json_decode($value,true);
            if(strtoupper($a['field'])=="MEDIUMTEXT"){
              $addons_json_field[$key] = $value;
              unset($json_field[$key]);//不参与基本表比较
            }
        }
        return $addons_json_field;
    }
    public static function create_data_table($fieldata,$name) {
        $table = apps_db::create_table(
          $name,
          apps_app::get_field_array($fieldata),//获取字段数组
          false,'data_id',true
        );
        array_push ($table,'iid');
        array_push ($table,'正文');
        // $table_array[$name]= $table;
        return $table;
    }
    /**
     * 将由查询字符串(query string)组成的数组转换成二维数组
     * @param  [type]  $data [查询字符串 数组]
     * @param  boolean $ui   [是否把UI标识返回数组]
     * @return [type]        [description]
     */
    public static function get_field_array($data,$ui=false) {
        $array = array();
        foreach ($data as $key => $value) {
          $output = array();
          if($value=='UI:BR'){
              $ui && $output = array('type'=>'br');
          }else{
              parse_str($value,$output);
              // if($keys){
              //   extract ($output);
              //   $output = compact ($keys);
              //   ksort($output);
              // }
          }
          $output && $array[$key] = $output;
        }
        return $array;
    }
    public static function get_data($app,$id) {
        $data  = array();
        if(empty($id) ){
            return $data;
        }

        $table = $app['table'];
        foreach ($table as $key => $value) {
            $data+= (array)iDB::row("SELECT * FROM `{$value['table']}` WHERE `{$value['primary']}`='$id' LIMIT 1;",ARRAY_A);
        }
        return $data;
    }


}
