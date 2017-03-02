<?php
/**
 * @package iCMS
 * @copyright 2007-2017, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */

class apps_store {
    const STORE_URL = "http://store.idreamsoft.com";
    const STORE_DIR = 'cache/iCMS/store/';

    public static function app_select($app=null) {
        foreach (apps::get_array(array("!table"=>0)) as $key => $value) {
            list($path,$obj_name)= apps::get_path($value['app'],'app',true);
            if(is_file($path) && method_exists($obj_name,'hooked')){
                $option[]='<option '.($app==$value['app']?' selected="selected"':'').' value="'.$value['app'].'">'.$value['app'].':'.$value['name'].'</option>';
            }
        }
        return implode('', (array)$option);
    }


}
