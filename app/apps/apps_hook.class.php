<?php
/**
 * @package iCMS
 * @copyright 2007-2017, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */

class apps_hook {
    public static function check($app=null) {
        $obj_name = $app.'App';
        return class_exists($obj_name);
    }

    public static function app_select($app=null) {
        foreach (apps::get_array(array("table"=>true)) as $key => $value) {
            list($path,$obj_name)= apps::path($value['app'],'app',true);
            if(is_file($path) && method_exists($obj_name,'hooked')){
                $option[]='<option '.($app==$value['app']?' selected="selected"':'').' value="'.$value['app'].'">'.$value['app'].':'.$value['name'].'</option>';
            }
        }
        return implode('', (array)$option);
    }
    public static function app_fields($app=null) {
        $rs = apps::get($app,'app');
        if($rs['table'])foreach ($rs['table'] as $key => $table) {
            $tbn = iPHP_DB_PREFIX.$table[0];
            if(apps_db::check_table($tbn)){
                $option[] = '<optgroup label="'.$table[0].'表">';
                $orig_fields  = apps_db::fields($tbn);
                foreach ((array)$orig_fields as $field => $value) {
                    $option[]='<option value="'.$field.'">'.($value['comment']?$value['comment'].' ('.$field.')':$field).'</option>';
                }
                $option[] = '</optgroup>';
            }

        }
        return implode('', (array)$option);
    }
    public static function app_method() {
        $option = '';
        foreach (apps::get_array(array("status"=>'1')) as $key => $value) {
            $option.=self::app_hook_method($value['app']);
        }
        return $option;
    }
    public static function app_hook_method($app=null) {
        list($path,$obj_name)= apps::path($app,'app',true);

        if(!is_file($path)){
            return false;
        }
        $class_methods = get_class_methods ($obj_name);
        foreach ($class_methods as $key => $method) {
            if(stripos($method, 'HOOK_') !== false||$method=="HOOK"){
                $option[]='<option value="'.$obj_name.'::'.$method.'">'.$obj_name.'::'.$method.'</option>';
            }
        }
        return implode('', (array)$option);
    }

}
