<?php
/**
 * @package iCMS
 * @copyright 2007-2017, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */

class apps {
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
    public static function path($app,$type='app',$arr=false){
        $path = iPHP_APP_DIR . '/' . $app . '/' . $app.'.'.$type.'.php';
        if($arr){
            $obj  = $app.ucfirst($type);
            return array($path,$obj);
        }
        return $path;
    }

    public static function uninstall($appid){
        $data = self::get($appid);

        if($data){
            $appname = $data['app'].'Admincp';
            $appname = $data['app'].'App';
var_dump(@class_exists($appname));
            // $app OR $app = iPHP::app($data['app'].'.app');

            // if(is_object($app)){
            //     $app_methods = get_class_methods($app);
            //     in_array('__uninstall', $app_methods) OR iUI::alert('卸载出错！ ['.$data['name'].']应用没有设置反安装程序[uninstall],请直接手动删除！');
            //     return $app->__uninstall($data,self);
            // }
        }
        return false;
    }
    public function __uninstall($app){
        $appdir  = dirname(strtr(__FILE__,'\\','/'));
        $appname = strtolower(__CLASS__);
        //删除分类
        categoryAdmincp::del_app_data($app['id']);
        //删除属性
        propAdmincp::del_app_data($app['id']);
        //删除文件
        iFile::del_app_data($app['id']);
        //删除配置
        configAdmincp::del($app['id'],$app['app']);

        //删除表
        apps::drop_app_table($app['table']);
        // 删除APP
        iFS::rmdir($appdir);
        iUI::success('应用删除完成!','js:1');
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
        $rs   = iDB::all("SELECT * FROM `#iCMS@__apps` where {$sql}",OBJECT);
        if($rs){
            if($is_multi){
                $_count = count($rs);
                for ($i=0; $i < $_count; $i++) {
                    $data[$rs[$i]->$field]= self::item($rs[$i]);
                }
            }else{
                $data = self::item($rs[0]);
            }
        }
        if(empty($data)){
            return;
        }
        return $data;
    }

    public static function item($rs){
        if($rs){
            $rs = (array)$rs;
            $rs['table'] && $rs['table']  = json_decode($rs['table'],true);
            $rs['config']&& $rs['config'] = json_decode($rs['config'],true);
            $rs['fields']&& $rs['fields'] = json_decode($rs['fields'],true);
        }
        return $rs;
    }
    public static function drop_app_table($table){
        foreach ((array)$table as $key => $value) {
            $value[0] && iDB::query("DROP TABLE IF EXISTS `#iCMS@__".$value[0]."`");
        }
    }
    public static function get_array($vars){
        $sql = '1=1';
        $vars['type'] && $sql.=" AND `type`='".(int)$vars['type']."'";
        $vars['status'] && $sql.=" AND `status`='".(int)$vars['status']."'";
        $vars['table'] && $sql.=" AND `table`!='0'";
        $rs  = iDB::all("SELECT * FROM `#iCMS@__apps` where {$sql}",OBJECT);
        $_count = count($rs);
        for ($i=0; $i < $_count; $i++) {
            $data[$rs[$i]->id]= self::item($rs[$i]);
        }
        return $data;
    }
    public static function get_apps($app=null,$trans=false){
        $rs = apps::get_array(array('status'=>'1'));
        foreach ($rs as $key => $value) {
            $array[$value['app']] = $value['id'];
        }
        $trans && $array = array_flip($array);

        if($app){
            return $array[$app];
        }
        // asort($array);
        // $appArray = apps::scan('*.app','*',true);
        // $acpArray = apps::scan('*.admincp','*',true);
        // $array    = array_merge((array)$appArray,(array)$acpArray);
        // $array    = array_filter($array);
        // $array    = array_keys($array);
        return $array;
    }
    public static function get_hooks(){
        $rs = apps::get_array(array('status'=>'1'));
        foreach ($rs as $key => $value) {
            $config = $value['config'];
            if($config['hooks']){
                foreach ($config['hooks'] as $_app => $hooks) {
                    foreach ($hooks as $field => $callback) {
                        $array[$_app][$field][]= (array)$callback;
                    }
                }
            }

        }
        return $array;
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
            $parts = pathinfo($filename);
            $app   = str_replace(iPHP_APP_DIR.'/','',$parts['dirname']);

            if(stripos($app, '/') !== false){
                list($app,) = explode('/', $app);
            }
            $path = str_replace(iPHP_APP_DIR.'/','',$filename);
            list($a,$b,) = explode('.', $parts['filename']);
            $array[$app] = array($a,$b,$path);
        }
        if($ret){
            return $array;
        }
        self::$array = $array;
        // var_dump(self::$array);
    }
    public static function config($pattern='iAPP.json',$dir='*'){
        $array = self::scan('etc/'.$pattern,$dir,true);
        $data  = array();
        foreach ($array as $key => $path) {
            if(stripos($path, $pattern) !== false){
                $rpath  = iPHP_APP_DIR.'/'.$path;
                $json  = file_get_contents($rpath);
                $json  = substr($json, 56);
                $jdata = json_decode($json,true);
                $error = json_last_error();
                if($error!==JSON_ERROR_NONE){
                    $data[$path] = array(
                        'title'        => $path,
                        'description' => json_last_error_msg()
                    );
                }
                if($jdata && is_array($jdata)){
                    $data[$jdata['app']] = $jdata;
                }
            }
        }
        return $data;
    }
    public static function installed($app,$r=false){
        $path  = iPHP_APP_DIR.'/'.$app.'/etc/iAPP.install.lock';
        if($r){
            return $path;
        }
        return file_exists($path);
    }
    public static function setting($t='setting',$appdir='*',$pattern='*.setting'){

        $array = self::scan('admincp/'.$pattern,$appdir,true);
        // var_dump($array);
        $app_array = iCache::get('app/cache_name');
        // var_dump($app_array);
        $paths = array();
        foreach ($array as $key => $path) {
            $appinfo = $app_array[$key];
            if($t=='tabs'){
                echo '<li><a href="#setting-'.$key.'" data-toggle="tab">'.$appinfo['title'].'</a></li>';
            }
            if ($t == 'setting'){
                $paths[$key] =  iPHP_APP_DIR.'/'.$path;
            }
        }
        return $paths;
    }
    public static function table_json($json){
        $tb_array = json_decode($json);
        foreach ($tb_array as $key => $value) {
            $table[$key] = array(
                iPHP_DB_PREFIX.$value[0],
                $value[1],
                $value[2],
            );
        }
        var_dump($table,$tb_array);
        // $table = array(
        //     'name'    => $tb_array[0][0]?'#iCMS@__'.$tb_array[0][0]:'',
        //     'primary' => $tb_array[0][1],
        // );
        // if($tb_array[1]){
        //     $table['join'] = $tb_array[1][0]?'#iCMS@__'.$tb_array[1][0]:'';
        //     $table['on']   = $tb_array[1][1];
        // }
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
        $rs = iDB::all("SELECT * FROM `#iCMS@__apps`");

        foreach((array)$rs AS $a) {
            $a = self::item($a);
			$appid_array[$a['id']] = $a;
			$app_array[$a['app']]  = $a;

			iCache::set('app/'.$a['id'],$a,0);
			iCache::set('app/'.$a['app'],$a,0);
        }
        iCache::set('app/idarray',  $appid_array,0);
        iCache::set('app/array',$app_array,0);
	}
	public static function get_app($appid=1){
		$rs	= iCache::get('app/'.$appid);
       	$rs OR iPHP::error_throw('app no exist', '0005');
       	return $rs;
	}
	public static function get_url($appid=1,$primary=''){
		$rs	= self::get_app($appid);
		return iCMS_URL.'/'.$rs['app'].'.php?'.$rs['table']['primary'].'='.$primary;
	}
	public static function get_table($appid=1){
		$rs	= self::get_app($appid);
       	return $rs['table'];
	}
	public static function get_label($appid=0,$key='title'){
		$array	= iCache::get('app/cache_id');
		if($appid){
			return $array[$appid][$key];
		}
       	return $array;
	}
}
