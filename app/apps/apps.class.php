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
    public static $type_array = array(
        '1' => '应用',
        '2' => '插件',
        '0' => '系统组件',
      );
    // public static $app_paths   = array();
    //
    // public static function installed($app){
    //     $path = self::$etc."/install.lock.php";
    //     return self::get_file($app,$path);
    // }
    public static function menu($menu){
        $path     = iPHP_APP_DIR.'/apps/etc/app.menu.json.php';
        $json     = file_get_contents($path);
        $json     = str_replace("<?php defined('iPHP') OR exit('What are you doing?');?>\n", '', $json);
        $variable = array();
        $array    = apps::get_array(array("apptype"=>'2'));
        if($array)foreach ($array as $key => $value) {
            if($value['config']['menu']){
                $sort = 200000+$key;

                $json = str_replace(
                    array('{appid}','{app}','{name}','{sort}'),
                    array($value['id'],$value['app'],$value['name'],$sort), $json);

                if($value['config']['menu']!='main'){
                    $json = '[{"id": "'.$value['config']['menu'].'","children":[{"caption": "-"},'.$json.']}]';
                }else{
                    $json = '['.$json.']';
                }

                $array  = json_decode($json,ture);
                if($array){
                    $array = $menu::mid($array,$sort);
                    $variable[] = $array;
                }
            }
        }
        return $variable;
    }
    public static function former_create($appid,$rs){
        $app = apps::get($appid);
        if($app['fields']){
            iFormer::$config['app']     = $app;
            iFormer::$config['gateway'] = 'admincp';
            iFormer::$config['value']   = array(
                'userid'   => members::$userid,
                'username' => members::$data->username,
                'nickname' => members::$data->nickname
            );
            iFormer::render($app,$rs);
        }
    }
    public static function former_data($appid,&$data,$table){
        $app = apps::get($appid);
        if($app['fields']){
          list($variable,$keys,$orig_post) = iFormer::post($app);
          foreach ($variable as $table_name => $_data) {
            if($table_name==$table){
                $data = array_merge($data,$_data);
            }
          }
        }
    }
    public static function uninstall($appid){
        $data = self::get($appid);
        if($data){
            self::__uninstall($data);
            // $obj_name = $data['app'].'Admincp';
            // var_dump(@class_exists($obj_name));
            // $obj_name = $data['app'].'App';
            // var_dump(@class_exists($obj_name));
            // $obj_name = $data['app'];
            // var_dump(@class_exists($obj_name));
            // $app = iPHP::app($data['app'].'.app');
            // if(is_object($app)){
            //     $app_methods = get_class_methods($app);
            //     in_array('__uninstall', $app_methods) OR iUI::alert('卸载出错！ ['.$data['name'].']应用没有设置反安装程序[uninstall],请直接手动删除！');
            //     return $app->__uninstall($data,self);
            // }
        }
        // return false;
    }
    public static function __uninstall($app){
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
        //删除数据
        apps::del_app_data($app['id']);
        //查找app目录
        $appdir = iPHP_APP_DIR . '/' . $app['app'];
        if(file_exists($appdir)){
            // 删除APP
            // iFS::rmdir($appdir);
        }
        // $path = self::get_path($app['app'],);
        iUI::success('应用删除完成!','js:1');
    }
    public static function installed($app,$r=false){
        $path  = iPHP_APP_DIR.'/'.$app.'/etc/iAPP.install.lock';
        if($r){
            return $path;
        }
        return file_exists($path);
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
            if($rs['table']){
                $table = json_decode($rs['table'],true);
                var_dump($table);

                $table && $rs['table']  = self::table_item($table);
            }
            $rs['config']&& $rs['config'] = json_decode($rs['config'],true);

            if($rs['fields']){
                // $rs['_fields'] = $rs['fields'];
                $rs['fields']  = json_decode($rs['fields'],true);
            }
        }
        return $rs;
    }
    public static function del_app_data($id){
        $id && iDB::query("DELETE FROM `#iCMS@__apps` WHERE `id` = '{$id}'; ");
    }
    public static function drop_app_table($table){
        if($table)foreach ((array)$table as $key => $value) {
            $value['table'] && iDB::query("DROP TABLE IF EXISTS `".$value['table']."`");
        }
    }
    public static function get_array($vars){
        $sql = '1=1';
        $vars['type']   && $sql.=" AND `type`='".(int)$vars['type']."'";
        $vars['status'] && $sql.=" AND `status`='".(int)$vars['status']."'";
        $vars['table']  && $sql.=" AND `table`!='0'";
        $vars['apptype']&& $sql.=" AND `apptype`='".(int)$vars['apptype']."'";
        $rs  = iDB::all("SELECT * FROM `#iCMS@__apps` where {$sql}",OBJECT);
        $_count = count($rs);
        for ($i=0; $i < $_count; $i++) {
            $data[$rs[$i]->id]= self::item($rs[$i]);
        }
        return $data;
    }
    public static function get_router(){
        $array = array(
            'http'     => array('rule'=>'0','primary'=>''),
            'index'    => array('rule'=>'0','primary'=>''),
            'category' => array('rule'=>'1','primary'=>'cid'),
            'article'  => array('rule'=>'2','primary'=>'id','page'=>'p'),
            'software' => array('rule'=>'2','primary'=>'id'),
            'tag'      => array('rule'=>'3','primary'=>'id'),
        );
        $rs = apps::get_array(array('status'=>'1'));
        foreach ($rs as $key => $value) {;
            if($value['table'] && $value['config']['router']){
                $table = self::get_table($value);
                $array[$value['app']] = array('rule'=>'4','primary'=>$table['primary'],'page'=>'p');
            }
        }
        return $array;
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
    // public static function get_hooks(){
    //     $rs = apps::get_array(array('status'=>'1'));
    //     foreach ($rs as $key => $value) {
    //         $config = $value['config'];
    //         if($config['hooks']){
    //             foreach ($config['hooks'] as $_app => $hooks) {
    //                 foreach ($hooks as $field => $callback) {
    //                     $array[$_app][$field][]= (array)$callback;
    //                 }
    //             }
    //         }

    //     }
    //     return $array;
    // }
    public static function get_type_select(){
      $option = '';
      foreach (self::$type_array as $key => $type) {
        $option.='<option value="'.$key.'">'.$type.'[type=\''.$key.'\']</option>';
      }
      $option.= propAdmincp::get("type");
      return $option;
    }
    // public static function get_file($app,$filename,$sapp=null){
    //     $app_path = iPHP_APP_DIR."/$app/".$filename;
    //     if(file_exists($app_path)){
    //         return array($app,$filename,$sapp);
    //     }else{
    //         return false;
    //     }
    // }
    // public static function scan($pattern='*.app',$appdir='*',$ret=false){
    //     $array = array();
    //     foreach (glob(iPHP_APP_DIR."/{$appdir}/{$pattern}.php") as $filename) {
    //         $parts = pathinfo($filename);
    //         $app   = str_replace(iPHP_APP_DIR.'/','',$parts['dirname']);

    //         if(stripos($app, '/') !== false){
    //             list($app,) = explode('/', $app);
    //         }
    //         $path = str_replace(iPHP_APP_DIR.'/','',$filename);
    //         list($a,$b,) = explode('.', $parts['filename']);
    //         $array[$app] = array($a,$b,$path);
    //     }
    //     if($ret){
    //         return $array;
    //     }
    //     self::$array = $array;
    //     // var_dump(self::$array);
    // }
    // public static function config($pattern='iAPP.json',$dir='*'){
    //     $array = self::scan('etc/'.$pattern,$dir,true);
    //     $data  = array();
    //     foreach ($array as $key => $path) {
    //         if(stripos($path, $pattern) !== false){
    //             $rpath  = iPHP_APP_DIR.'/'.$path;
    //             $json  = file_get_contents($rpath);
    //             $json  = substr($json, 56);
    //             $jdata = json_decode($json,true);
    //             $error = json_last_error();
    //             if($error!==JSON_ERROR_NONE){
    //                 $data[$path] = array(
    //                     'title'        => $path,
    //                     'description' => json_last_error_msg()
    //                 );
    //             }
    //             if($jdata && is_array($jdata)){
    //                 $data[$jdata['app']] = $jdata;
    //             }
    //         }
    //     }
    //     return $data;
    // }

    // public static function setting($t='setting',$appdir='*',$pattern='*.setting'){

    //     $array = self::scan('admincp/'.$pattern,$appdir,true);
    //     // var_dump($array);
    //     $app_array = iCache::get('app/cache_name');
    //     // var_dump($app_array);
    //     $paths = array();
    //     foreach ($array as $key => $path) {
    //         $appinfo = $app_array[$key];
    //         if($t=='tabs'){
    //             echo '<li><a href="#setting-'.$key.'" data-toggle="tab">'.$appinfo['title'].'</a></li>';
    //         }
    //         if ($t == 'setting'){
    //             $paths[$key] =  iPHP_APP_DIR.'/'.$path;
    //         }
    //     }
    //     return $paths;
    // }

    public static function table_item($variable){
        is_array($variable) OR $variable = json_decode($variable,true);
        if($variable){
            foreach ($variable as $key => $value) {
                if(count($value)>3){
                    $table[$value[0]]=array(
                            'table'   => iPHP_DB_PREFIX.$value[0],
                            'name'    => $value[0],
                            'primary' => $value[1],
                            'union'   => $value[2],
                            'label'   => $value[3],
                        );
                }else{
                    $table[$value[0]]=array(
                        'table'   => iPHP_DB_PREFIX.$value[0],
                        'name'    => $value[0],
                        'primary' => $value[1],
                        'label'   => $value[2],
                    );
                }
            }
            return $table;
        }
    }

	public static function cache(){
        $rs = iDB::all("SELECT * FROM `#iCMS@__apps`");

        foreach((array)$rs AS $a) {
            $a = self::item($a);
			$appid_array[$a['id']] = $a;
			$app_array[$a['app']]  = $a;

            self::set_app_cache($a);
        }
        iCache::set('app/idarray',  $appid_array,0);
        iCache::set('app/array',$app_array,0);
	}
    public static function set_app_cache($a){
        if(!is_array($a)){
            $a = self::get($a);
        }
        iCache::set('app/'.$a['id'],$a,0);
        iCache::set('app/'.$a['app'],$a,0);
    }
    public static function get_path($app,$type='app',$arr=false){
        $path = iPHP_APP_DIR . '/' . $app . '/' . $app.'.'.$type.'.php';
        if($arr){
            $obj  = $app.ucfirst($type);
            return array($path,$obj);
        }
        return $path;
    }
    public static function get_func($app,$tag=false){
        $path   = self::get_path($app,'func');
        if(file_exists($path)){
            $arr    =  get_defined_functions ();
            $array1 = $arr['user'];
            include $path;
            $arr    = get_defined_functions ();
            $array2 = $arr['user'];
            $result = array_diff ( $array2 ,  $array1);

            if($result){
                if($tag){
                    $tag_array = array();
                    foreach ($result as $key => $value) {
                        if(substr($value,0,strlen($app))==$app){
                            $tag_array[]= iPHP_APP.':'.str_replace('_', ':', $value);
                        }
                    }
                    return $tag_array;
                }else{
                    return $result;
                }
            }
        }
    }
	public static function get_app($appid=1){
		$rs	= iCache::get('app/'.$appid);
       	$rs OR iPHP::error_throw('application no exist', '0005');
       	return $rs;
	}
	public static function get_url($appid=1,$primary=''){
        $rs    = self::get_app($appid);
        if($rs['table']){
            $table = reset($rs['table']);
            $key   = $table['primary'];
        }
        empty($key) && $key = 'id';

		return iCMS_URL.'/'.$rs['app'].'.php?'.$key.'='.$primary;
	}
	public static function get_table($app=1,$master=true){
		if(is_array($app)){
            $rs = $app;
        }else{
            $rs = self::get_app($app);
        }
        $table = $rs['table'];
        $master && $table = reset($rs['table']);
       	return $table;
	}
	public static function get_label($appid=0){
        $rs = self::get_app($appid);
        $table = reset($rs['table']);

		if($table['label']){
			return $table['label'];
		}else{
            return $rs['name'];
        }
	}
}
