<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author c00lt3a <idreamsoft@qq.com>
 */

class APPS {
    public static $table   = 'article';
    public static $primary = 'id';
    public static $appid   = '1';
    public static $etc     = 'etc';
    public static $array   = array();
    // public static $app_paths   = array();
    //
    // public static function installed($app){
    //     $path = self::$etc."/install.lock.php";
    //     return self::get_file($app,$path);
    // }
    public static function check($app,$package='admincp'){
        if(stripos($app, '_')!== false){
            list($app,$sapp) = explode('_', $app);
            $package = "{$sapp}.{$package}";
        }
        $filename = "{$app}.{$package}.php";
        $app_path = iPHP_APP_DIR."/$app/".$filename;
        if(file_exists($app_path)){
            return array($app,$filename,$sapp);
        }else{
            return false;
        }
        // return self::get_file($app,$filename,$sapp);
    }
    // public static function get_file($app,$filename,$sapp=null){
    //     $app_path = iPHP_APP_DIR."/$app/".$filename;
    //     if(file_exists($app_path)){
    //         return array($app,$filename,$sapp);
    //     }else{
    //         return false;
    //     }
    // }
    public static function scan($pattern='*.app',$appdir='*',$ret=false){
        $array = array();
        foreach (glob(iPHP_APP_DIR."/{$appdir}/{$pattern}.php") as $filename) {

            // if($check){
            //     var_dump($filename, $pattern);
            //     if(stripos($filename, $pattern) === false){
            //         continue;
            //     }
            // }

            $parts = pathinfo($filename);

            $app = str_replace(iPHP_APP_DIR.'/','',$parts['dirname']);
            if(stripos($app, '/') !== false){
                list($app,) = explode('/', $app);
            }
            $path = str_replace(iPHP_APP_DIR.'/','',$filename);
            list($a,$b,$c) = explode('.', $parts['filename']);
            $array[$app] = $path;

            // if($b=='admincp' && $c===null){
            // }else{
                // self::$array[$app][$b] = $path;
            // }
            // print_r($app.PHP_EOL);
            // print_r($parts['filename'].PHP_EOL);
            // self::$array[$app] = $path;

            // self::$app_paths[$app] = $filename;

            // var_dump($dirname);
            // if (!in_array($dirname,array('admincp','usercp'))) {
            //     $app = str_replace('.app','',$parts['filename']);
            //     in_array($app,$this->apps) OR array_push($this->apps,$app);
            // }
        }
        if($ret){
            return $array;
        }
        self::$array = $array;
        // var_dump(self::$array);
    }
    public static function config($pattern='app.json',$dir='*'){
        $array = self::scan('config/'.$pattern,$dir,true);
        $data  = array();
        foreach ($array as $key => $path) {
            if(stripos($path, $pattern) !== false){
                $path  = iPHP_APP_DIR.'/'.$path;
                $json  = file_get_contents($path);
                $json  = substr($json, 56);
                $jdata = json_decode($json,true);
                if($jdata && is_array($jdata)){
                    $data[$jdata['app']] = $jdata;
                }
            }
        }
        return $data;
    }
    public static function setting($t='tabs',$appdir='*',$pattern='*.setting'){

        $array = self::scan('admincp/'.$pattern,$appdir,true);
        // var_dump($array);
        $app_array = iCache::get('iCMS/app/cache_name');
        // var_dump($app_array);
        $paths = array();
        foreach ($array as $key => $path) {
            $appinfo = $app_array[$key];
            if($t=='tabs'){
                echo '<li><a href="#setting-'.$key.'" data-toggle="tab">'.$appinfo['title'].'</a></li>';
            }
            if ($t == 'content'){
                $paths[$key] =  iPHP_APP_DIR.'/'.$path;
            }
        }
        return $paths;
    }
    public static function table($appId){
        $appMap = array(
            '1'  => 'article',   //文章
            '2'  => 'category',  //分类
            '3'  => 'tags',      //标签
            '4'  => 'push',      //推送
            '5'  => 'comment',   //评论
            '6'  => 'prop',      //属性
            '7'  => 'message',   //私信
            '8'  => 'favorite',  //收藏
            '9'  => 'user',      //用户
            '10' => 'weixin',    //微信
            '11' => 'download',  //下载
        );
        return $appMap[$appId];
    }

	public static function init($table = 'article',$appid='1',$primary = 'id'){
		self::$table   = $table;
		self::$primary = $primary;
		self::$appid   = $appid;
		return self;
	}
	public static function cache(){
        $rs = iDB::all("SELECT * FROM `#iCMS@__app`");

        foreach((array)$rs AS $a) {
        	$tb_array = json_decode($a['table']);
        	$table = array(
				'name'    => '#iCMS@__'.$tb_array[0][0],
				'primary' => $tb_array[0][1],
        	);
        	if($tb_array[1]){
				$table['join'] = '#iCMS@__'.$tb_array[1][0];
				$table['on']   = $tb_array[1][1];
        	}
        	$a['table'] = $table;
			$app_id_array[$a['id']]     = $a;
			$app_name_array[$a['name']] = $a;

			iCache::delete('iCMS/app/'.$a['id']);
			iCache::set('iCMS/app/'.$a['id'],$a,0);

			iCache::delete('iCMS/app/'.$a['name']);
			iCache::set('iCMS/app/'.$a['name'],$a,0);

        }
        iCache::set('iCMS/app/cache_id',  $app_id_array,0);
        iCache::set('iCMS/app/cache_name',$app_name_array,0);
	}
	public static function get_app($appid=1){
		$rs	= iCache::get('iCMS/app/'.$appid);
       	$rs OR iPHP::throwException('app no exist', '0005');
       	return $rs;
	}
	public static function get_url($appid=1,$primary=''){
		$rs	= self::get_app($appid);
		return iCMS_URL.'/'.$rs['name'].'.php?'.$rs['table']['primary'].'='.$primary;
	}
	public static function get_table($appid=1){
		$rs	= self::get_app($appid);
       	return $rs['table'];
	}
	public static function get_label($appid=0,$key='title'){
		$array	= iCache::get('iCMS/app/cache_id');
		if($appid){
			return $array[$appid][$key];
		}
       	return $array;
	}


}
