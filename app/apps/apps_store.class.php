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
    public static $zipname = null;
    public static $next = false;
    public static $test = false;
    public static $app_name = null;

    public static function download($url=null,$name) {
        iFS::mkdir(STORE_DIR);
        self::$zipname = basename($url);
        $zipFile = STORE_DIR . self::$zipname; //临时文件
        $msg = '正在下载 [' . $name . '] 安装包 ' . $url . '<iCMS>下载完成....<iCMS>';
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
    public static function install() {
        @set_time_limit(0);
        // Unzip uses a lot of memory
        @ini_set('memory_limit', '256M');
        iPHP::import(iPHP_LIB . '/pclzip.class.php'); //加载zip操作类
        $zipFile = STORE_DIR . '/' . self::$zipname; //临时文件
        $msg = '正在对 [' . self::$zipname . '] 安装包进行解压缩<iCMS>';
        $zip = new PclZip($zipFile);
        if (false == ($archive_files = $zip->extract(PCLZIP_OPT_EXTRACT_AS_STRING))) {
            exit("ZIP包错误");
        }

        if (0 == count($archive_files)) {
            exit("空的ZIP文件");
        }

        $msg .= '解压完成<iCMS>';
        $msg .= '开始测试目录权限<iCMS>';
        $update = true;
        if (!iFS::checkDir(iPATH)) {
            $update = false;
            $msg .= iPATH . '根目录无写权限<iCMS>';
        }

        if (!iFS::checkDir(iPHP_APP_DIR)) {
            $update = false;
            $msg .= iPHP_APP_DIR . '目录无写权限<iCMS>';
        }

        if (!iFS::checkDir(iPHP_TPL_DIR)) {
            $update = false;
            $msg .= iPHP_TPL_DIR . '目录无写权限<iCMS>';
        }

        //测试APP目录是否存在
        $dp = iPHP_APP_DIR.'/'.self::$app_name;

        if (iFS::ex($dp)) {
            $update = false;
            $msg .= $dp.' 目录已经存在<iCMS>';
        }

        // foreach ($archive_files as $file) {
        //     $folder = $file['folder'] ? $file['filename'] : dirname($file['filename']);
        //     $dp = iPHP_APP_DIR.'/'.$folder;

        //     $pos = stripos($dp, '/template/');
        //     if ($pos!==false){
        //         $dp = iPHP_TPL_DIR.'/'.substr($dp, $pos+10);
        //     }else{

        //     }
        // }
        if (!$update) {
            $msg .= '权限测试无法完成<iCMS>';
            $msg .= '请设置根据提示重新设置<iCMS>';
            $msg .= '然后重新安装<iCMS>';
            self::$next = false;
            $msg = str_replace(iPATH,'iPHP://',$msg);
            return $msg;
        }
        //测试通过！
        self::$next = true;
        $msg .= '权限测试通过<iCMS>';
        $msg .= '开始安装应用<iCMS>';


        foreach ($archive_files as $file) {
            $folder = $file['folder'] ? $file['filename'] : dirname($file['filename']);
            $dp = iPHP_APP_DIR .'/'.trim($folder,'/').'/';
            $dp = self::check_tpl_path($dp);

            if (!iFS::ex($dp)) {
                $msg .= '创建文件夹 [' . $dp . '] <iCMS>';
                iFS::mkdir($dp);
                $msg .= '创建完成!<iCMS>';
            }
            if (!$file['folder']) {
                $fp = iPHP_APP_DIR .'/'. $file['filename'];
                $fp = self::check_tpl_path($fp);
                $msg .= '安装文件 [' . $fp . '] <iCMS>';
                self::$test OR iFS::write($fp, $file['content']);
                $msg .= '安装完成!<iCMS>';
            }
        }
        $msg .= '清除临时文件!<iCMS>';
        // self::$test OR iFS::rmdir(STORE_DIR, true);
        $msg = str_replace(iPATH,'iPHP://',$msg);
        return $msg;
    }
    public static function setup(){
        var_dump(self::$appdir);
        $appdir =  iPHP_APP_DIR .'/'.self::$appdir;
        $sql_file = $appdir.'/app.table.sql';
        $msg.= '安装数据表<iCMS>';
        if(file_exists($sql_file)){
            $sql = file_get_contents($sql_file);
            // self::run_query($sql);
        }
        $data_sql_file = $appdir.'/app-data.sql';
        if(file_exists($data_sql_file)){
            $sql = file_get_contents($data_sql_file);
            $msg .= '清除临时文件!<iCMS>';
            // var_dump($sql);
            // self::run_query($sql);
        }
        $msg.= '安装完成!<iCMS>';
    }
    public static function check_tpl_path($path){
        $pos = stripos($path, '/template/');
        if ($pos!==false){
             $path = iPHP_TPL_DIR.'/'.substr($path, $pos+10);
        }
        return $path;
    }
}
