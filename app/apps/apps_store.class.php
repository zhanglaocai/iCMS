<?php
/**
 * @package iCMS
 * @copyright 2007-2017, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */

define('STORE_DIR', iPATH . 'cache/iCMS/store/');//临时文件夹

class apps_store {
    const STORE_URL = "http://store.idreamsoft.com";

    public static function app_select($app=null) {
        foreach (apps::get_array(array("!table"=>0)) as $key => $value) {
            list($path,$obj_name)= apps::get_path($value['app'],'app',true);
            if(is_file($path) && method_exists($obj_name,'hooked')){
                $option[]='<option '.($app==$value['app']?' selected="selected"':'').' value="'.$value['app'].'">'.$value['app'].':'.$value['name'].'</option>';
            }
        }
        return implode('', (array)$option);
    }
    public static function download($url=null,$name) {
        $path_parts  =  pathinfo ($url);
        $zipFile = STORE_DIR . $path_parts['basename']; //临时文件
        $msg = '正在下载 [' . $name . '] 应用包 ' . $url . '<iCMS>下载完成....<iCMS>';
        if (iFS::ex($zipFile)) {
            return $msg;
        }
        $FileData = iHttp::remote($url);
        if ($FileData) {
            iFS::mkdir(STORE_DIR);
            iFS::write($zipFile, $FileData); //下载更新包
            return $msg;
        }
    }
    public static function install($name=null) {
    }

}
