<?php
/**
 * @package iCMS
 * @copyright 2007-2017, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */

class apps_app {
    public static function drop_table($addons_fieldata,&$table_array,$addons_name) {
      if(empty($addons_fieldata) && $table_array[$addons_name] && apps_db::check_table(iDB::table($addons_name))){
        apps_db::drop_tables(array(iPHP_DB_PREFIX.$addons_name));
        unset($table_array[$addons_name]);
      }
    }
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
    public static function data_base_fields($name) {
      $union_id = $name.'_id';
      $a['data_id'] = "id=data_id&label=附加表id&comment=主键%20自增ID&field=PRIMARY&name=data_id&default=&type=PRIMARY&len=10&";
      $a[$union_id] = "id=".$union_id."&label=内容ID&comment=内容ID%20关联".$name."表&field=INT&name=".$union_id."&default=&type=union&len=10";
      return $a;
    }
    /**
     * 创建xxx_data附加表
     * @param  [type] $fieldata [description]
     * @param  [type] $name     [description]
     * @return [type]           [description]
     */
    public static function data_create_table($fieldata,$name,$union_id) {
        $table = apps_db::create_table(
          $name,
          apps_app::get_field_array($fieldata),//获取字段数组
          false,'data_id',true
        );
        array_push ($table,$union_id,'正文');
        return array($name=>$table);
    }
    public static function data_create_table2($fieldata,$name,$union_id) {
        $table = apps_db::create_table2(
          $name,
          apps_app::get_field_array($fieldata),//获取字段数组
          'data_id'
        );
        array_push ($table,$union_id,'正文');
        return array($name=>$table);
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
