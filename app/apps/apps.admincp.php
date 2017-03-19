<?php
/**
* iCMS - i Content Management System
* Copyright (c) 2007-2012 idreamsoft.com iiimon Inc. All rights reserved.
*
* @author coolmoo <idreamsoft@qq.com>
* @site http://www.idreamsoft.com
* @licence http://www.idreamsoft.com/license.php
* @version 6.2.0
*/
defined('iPHP') OR exit('What are you doing?');

class appsAdmincp{

    public function __construct() {
      $this->appid = iCMS_APP_APPS;
    	$this->id = (int)$_GET['id'];
    }

    public function do_add(){
        $this->id && $rs = apps::get($this->id);
        if(empty($rs)){
          $rs['config']['iFormer'] = '1';
          $rs['apptype'] = "2";
          $rs['type']    = "2";
          $rs['status']  = "1";
          $base_fields   = apps_mod::base_fields_array();

          $rs['fields'] = get_php_file(iPHP_APP_DIR.'/apps/json/fields.php');
          $rs['fields'] = json_decode($rs['fields'],true);

          $rs['menu']   = get_php_file(iPHP_APP_DIR.'/apps/json/menu.php');
          $rs['menu']   = json_decode($rs['menu'],true);

        }else{
          if($rs['apptype']=="2"){
            $rs['config']['iFormer'] = '1';
          }
        }

        $rs['config']['template'] = apps_mod::get_template_tag($rs);
        if(empty($rs['config']['router'])){
          $rs['config']['router'] = apps_mod::get_router($rs);
        }

        include admincp::view("apps.add");
    }

    public function do_save(){
        $id      = (int)$_POST['_id'];
        $name    = iSecurity::escapeStr($_POST['_name']);
        $title   = iSecurity::escapeStr($_POST['_title']);
        $app     = iSecurity::escapeStr($_POST['_app']);
        $apptype = (int)$_POST['apptype'];
        $type    = (int)$_POST['type'];
        $status  = (int)$_POST['status'];

        $fieldata = $_POST['fields'];
        $config_array = $_POST['config'];
        $table_array  = $_POST['table'];

        $menu = json_decode(stripcslashes($_POST['menu']));
        $menu = json_encode($menu);
        $menu = addslashes($menu);

        $name OR iUI::alert('应用名称不能为空!');
        empty($app) && $app = iPinyin::get($name);
        empty($title) && $title = $name;

        if($table_array){
          $table_array  = array_filter($table_array);
          $table  = addslashes(json_encode($table_array));
        }

        if($config_array['template']){
          $config_array['template'] = explode("\n", $config_array['template']);
          $config_array['template'] = array_map('trim', $config_array['template']);
        }
        if($config_array['router']){
          $config_array['router'] = json_decode(stripcslashes($config_array['router']),true);
        }
        $config_array = array_filter($config_array);
        $config = addslashes(json_encode($config_array));

        $fields = '';
        if(is_array($fieldata)){
          $field_array = array();
          foreach ($fieldata as $key => $value) {
            $output = array();
            parse_str($value,$output);
            if(isset($output['UI:BR'])){
              $field_array[$key] = 'UI:BR';
            }else{
              $output['label'] OR iUI::alert('发现自定义字段中空字段名称!');
              $output['comment'] = $output['label'].($output['comment']?':'.$output['comment']:'');
              $fname = $output['name'];
              $fname OR iUI::alert('发现自定义字段中有空字段名!');
              $field_array[$fname] = $value;
              if($output['field']=="MEDIUMTEXT"){
                $addons_fieldata[$key] = $value;
                unset($fieldata[$key]);//从基本表移除
              }
            }
          }
          //字段数据存入数据库
          $fields = addslashes(json_encode($field_array));
        }

        $addtime = time();
        $array   = compact(array('app','name','title','menu','table','config','fields','addtime','apptype','type','status'));
        // $array['menu'] = str_replace(array("\r","\n"),'',$array['menu']);

        if(empty($id)) {

            iDB::value("SELECT `id` FROM `#iCMS@__apps` where `app` ='$app'") && iUI::alert('该应用已经存在!');
            apps_db::check_table(iDB::table($array['app'])) && iUI::alert('['.$array['app'].']数据表已经存在!');

            // iDB::$print_sql = true;

            if($addons_fieldata){
              $addons_name = apps_mod::data_table_name($array['app']);
              apps_db::check_table(iDB::table($addons_name)) && iUI::alert('['.$addons_name.']附加表已经存在!');
            }

            //创建基本表
            $tb = apps_db::create_table(
              $array['app'],
              apps_mod::get_field_array($fieldata),//获取字段数组
              apps_mod::base_fields_index()//索引
            );
            array_push ($tb,null,$array['name']);
            $table_array = array();
            $table_array[$array['app']]= $tb;//记录基本表名

            //有MEDIUMTEXT类型字段就创建xxx_data附加表
            if($addons_fieldata){
              $union_id = apps_mod::data_union_id($array['app']);//关联基本表id
              $addons_base_fields = apps_mod::base_fields($array['app']);//xxx_data附加表的基础字段
              $addons_fieldata = $addons_base_fields+$addons_fieldata;
              $table_array += apps_mod::data_create_table($addons_fieldata,$addons_name,$union_id);
              // //添加到字段数据里
              // $field_array = array_merge($field_array,$addons_base_fields);
              // $array['fields'] = addslashes(json_encode($field_array));
            }
            $array['table']  = $table_array;
            $array['config'] = $config_array;

            $config_array['template'] = apps_mod::get_template_tag($array,'array');
            $config_array['router']   = apps_mod::get_router($array);

            $array['table'] = addslashes(json_encode($table_array));
            $array['config'] = addslashes(json_encode($config_array));

            $id = iDB::insert('apps',$array);
            if(stripos($array['menu'], '{app}') !== false){
              $menu = str_replace(
                  array('{appid}','{app}','{name}','{sort}'),
                  array($id,$array['app'],$array['name'],$id*1000),
                  $array['menu']
              );
              iDB::update('apps', array('menu'=>$menu), array('id'=>$id));
            }

            $msg = "应用创建完成!";
        }else {
            iDB::value("SELECT `id` FROM `#iCMS@__apps` where `app` ='$app' AND `id` !='$id'") && iUI::alert('该应用已经存在!');
            $_fields     = iDB::value("SELECT `fields` FROM `#iCMS@__apps` where `id` ='$id'");//json
            $_json_field = apps_mod::json_field($_fields);//旧数据
            $json_field  = apps_mod::json_field($fields); //新数据
            /**
             * 找出字段数据中的 MEDIUMTEXT类型字段
             * PS:函数内会unset(json_field[key]) 所以要在 基本表make_alter_sql前执行
             */
            $_addons_json_field = apps_mod::find_MEDIUMTEXT($_json_field);
            $addons_json_field = apps_mod::find_MEDIUMTEXT($json_field);

            // print_r($_addons_json_field);
            // print_r($addons_json_field);

            //基本表 新旧数据计算交差集 origin 为旧字段名
            $sql_array = apps_db::make_alter_sql($json_field,$_json_field,$_POST['origin']);
            $sql_array && apps_db::alter_table($array['app'],$sql_array);

            //MEDIUMTEXT类型字段 新旧数据计算交差集 origin 为旧字段名
            $addons_sql_array = apps_db::make_alter_sql($addons_json_field,$_addons_json_field,$_POST['origin']);

            $addons_name = apps_mod::data_table_name($array['app']);
            //存在附加表数据
            if($addons_fieldata){
              if($addons_sql_array){
                //附加表名
                //检测附加表是否存在
                if($table_array[$addons_name] && apps_db::check_table(iDB::table($addons_name))){
                  //表存在执行 alter
                  apps_db::alter_table($addons_name,$addons_sql_array);
                }else{
                  // 不存在 创建
                  if($addons_fieldata){
                    apps_db::check_table(iDB::table($addons_name)) && iUI::alert('['.$addons_name.']附加表已经存在!');
                    //有MEDIUMTEXT类型字段创建xxx_data附加表
                    $union_id = apps_mod::data_union_id($array['app']);
                    $addons_base_fields = apps_mod::base_fields($array['app']);//xxx_data附加表的基础字段
                    $addons_fieldata = $addons_base_fields+$addons_fieldata;
                    $table_array += apps_mod::data_create_table($addons_fieldata,$addons_name,$union_id);
                    $array['table'] = addslashes(json_encode($table_array));
                    // //添加到字段数据里
                    // $field_array = array_merge($field_array,$addons_base_fields);
                    // $array['fields'] = addslashes(json_encode($field_array));
                  }
                }
              }
            }else{
              if($apptype=="2"){ //只删除自定义应用的表
                //不存在附加表数据 直接删除附加表 返回 table的json值 $table_array为引用参数
                apps_mod::drop_table($addons_fieldata,$table_array,$addons_name);
                $array['table'] = addslashes(json_encode($table_array));
              }else{
                $data_tables = next($table_array);
                $union_id = apps_mod::data_union_id($array['app']);
                //判断是否自动生成的表
                if(is_array($data_tables) &&
                  in_array("data_id" ,$data_tables) &&
                  in_array($union_id ,$data_tables))
                {
                  apps_mod::drop_table($addons_fieldata,$table_array,$addons_name);
                  $array['table'] = addslashes(json_encode($table_array));
                }else{
                  apps_db::alter_table($addons_name,$addons_sql_array);
                }
              }
            }

            iDB::update('apps', $array, array('id'=>$id));
            $msg = "应用编辑完成!";
        }
        apps::cache();
        iUI::success($msg,'url:'.APP_URI);
    }

    public function do_update(){
        if($this->id){
            $args = admincp::update_args($_GET['_args']);
            $args && iDB::update("apps",$args,array('id'=>$this->id));
            apps::cache();
            iUI::success('操作成功!','js:1');
        }
    }
    public function do_iCMS(){
      // if($_GET['keywords']) {
		    // $sql=" WHERE `keyword` REGEXP '{$_GET['keywords']}'";
      // }
      $orderby    =$_GET['orderby']?$_GET['orderby']:"id DESC";
      $maxperpage = $_GET['perpage']>0?(int)$_GET['perpage']:50;
      $total      = iCMS::page_total_cache("SELECT count(*) FROM `#iCMS@__apps` {$sql}","G");
      iUI::pagenav($total,$maxperpage,"个应用");
      $rs     = iDB::all("SELECT * FROM `#iCMS@__apps` {$sql} order by {$orderby} LIMIT ".iUI::$offset." , {$maxperpage}");
      $_count = count($rs);

      //分组
      foreach ($rs as $key => $value) {
        $apps_type_group[$value['type']][$key] = $value;
      }
    	include admincp::view("apps.manage");
    }
    // public function do_manage(){
    //   apps::scan('config/app.json');
    //   $rs = apps::config('iApp.json');
    //   include admincp::view("apps.json.manage");
    // }

    public function do_del($id = null,$dialog=true){
    	$id===null && $id=$this->id;
  		$id OR iUI::alert('请选择要删除的应用!');
      $rs   = iDB::row("SELECT `name` FROM `#iCMS@__apps` WHERE `id`='$id' LIMIT 1;");
      $name = $rs->name;
      iDB::query("DROP TABLE `#iCMS@__{$name}`; ");

  		iDB::query("DELETE FROM `#iCMS@__apps` WHERE `id` = '$id'");
  		apps::cache();
  		$dialog && iUI::success('应用已经删除','js:parent.$("#tr'.$id.'").remove();');
    }
    public function do_batch(){
        $idArray = (array)$_POST['id'];
        $idArray OR iUI::alert("请选择要操作的应用");
        $ids     = implode(',',$idArray);
        $batch   = $_POST['batch'];
      	switch($batch){
      		case 'dels':
  				  iUI::$break	= false;
  	    		foreach($idArray AS $id){
  	    			$this->do_del($id,false);
  	    		}
  	    		iUI::$break	= true;
  				  iUI::success('应用全部删除完成!','js:1');
      		break;
  		  }

	  }
    public function do_cache(){
      apps::cache();
      iUI::success('更新完成');
    }
    /**
     * [钩子管理]
     * @return [type] [description]
     */
    public function do_hooks(){
        configAdmincp::app($this->appid,'hooks');
    }
    /**
     * [保存钩子]
     * @return [type] [description]
     */
    public function do_hooks_save(){
        $hooks = array();
        foreach ((array)$_POST['hooks']['method'] as $key => $method) {
          $h_app   = $_POST['hooks']['app'][$key];
          $h_field = $_POST['hooks']['field'][$key];
          if($method && $h_app && $h_field){
            $hooks[$h_app][$h_field][]= explode("::", $method);
          }
        }
        $_POST['config'] = $hooks;
        configAdmincp::save($this->appid,'hooks');
    }
    /**
     * [应用市场]
     * @return [type] [description]
     */
    public function do_store(){
      include admincp::view("apps.store");
    }
    /**
     * [应用市场数据]
     * @return [type] [description]
     */
    public function do_store_json(){
      $url  = apps_store::STORE_URL.'/store.json';
      $json = iHttp::remote($url);
      echo $json;
    }
    /**
     * [从应用市场安装应用]
     * @return [type] [description]
     */
    public function do_store_install(){
      $sid  = $_GET['sid'];
      $time = time();
      $host = $_SERVER['HTTP_HOST'];
      $key  = md5(iPHP_KEY.$host.uniqid(true));
      $array= compact(array('sid','key','host','time'));
      $url  = apps_store::STORE_URL.'/store.get?'.http_build_query($array);
      $json = iHttp::remote($url);
      var_dump($json);
      if($json){
        $array = json_decode($json);
        if($array->premium){
          iUI::$break               = false;
          iUI::$dialog['ok']        = true;
          iUI::$dialog['cancel']    = true;
          iUI::$dialog['ok:js']     =
          iUI::$dialog['cancel:js'] = '
            top.clear_pay_notify_timer();
          ';
          iUI::dialog('
            此应用为付费版,请先付费后安装!<br />
            请使用微信扫描下面二维码<br />
            <p style="text-align: center;">
            <img alt="模式一扫码支付"
            src="http://paysdk.weixin.qq.com/example/qrcode.php?data='.$array->pay.'"/>
            </p>
          ','js:1',1000000);
          echo '<script type="text/javascript">
            top.pay_notify("'.$key.'","'.$sid.'",d);
          </script>';
          exit;
        }else{
          apps_store::download($array->url,$array->name);
        }
      }

    }
    /**
     * [卸载应用]
     * @return [type] [description]
     */
    public function do_uninstall(){
      $app = apps::get($this->id);
      if($app['type'] && $app['apptype']){
        apps::uninstall($this->id);
        apps::cache();
        iUI::alert('应用已经删除');
      }else{
        iUI::alert('应用已被禁止删除');
      }
    }
    /**
     * [安装应用]
     * @return [type] [description]
     */
    public function do_install(){
      apps_store::$zipName  = "markerTest.zip";
      apps_store::$app_name = "markerTest";
      // apps_store::$test = true;
      $this->msg = apps_store::install();
      if(apps_store::$next){
        $this->msg.= apps_store::setup();//安装数据库
      }
      $this->msg.= '更新应用缓存<iCMS>';
      apps::cache();
      $this->msg.= '更新菜单缓存<iCMS>';
      menu::cache();
      $this->msg.= '应用安装完成<iCMS>';
      include admincp::view("install");
    }
    /**
     * [本地安装应用]
     * @return [type] [description]
     */
    public function do_local_app(){
      $zipfile  = $_POST['zipfile'];
      // $app = preg_replace('/.*iCMS\.APP\.(\w+)-v.*?\.zip/is', '$1', $zipfile);
      // iDB::value("SELECT `id` FROM `#iCMS@__apps` where `app` ='$app'") && iUI::alert('该应用已经存在!');

      iPHP::import(iPHP_LIB . '/pclzip.class.php'); //加载zip操作类
      $zip = new PclZip($zipfile);
      if (false == ($archive_files = $zip->extract(PCLZIP_OPT_EXTRACT_AS_STRING))) {
          iUI::alert("ZIP包错误");
      }

      if (0 == count($archive_files)) {
          iUI::alert("空的ZIP文件");
      }
      foreach ($archive_files AS $key => $file) {
        $filename = basename($file['filename']);
        if($filename=="iCMS.APP.DATA.php"){
          $content = get_php_content($file['content']);
          $content = base64_decode($content);
          $array   = unserialize($content);

          iDB::value("
            SELECT `id` FROM `#iCMS@__apps`
            WHERE `app` ='".$array['app']."'
          ") && iUI::alert('该应用已经存在!');

          if($array['table']){
            $tableArray = apps::table_item($array['table']);
            foreach ($tableArray AS $value) {
              apps_db::check_table($value['table']) && iUI::alert('['.$value['table'].']数据表已经存在!');
            }
          }
          $array['addtime'] = time();
          $array = array_map('addslashes', $array);
          // $appid = iDB::insert("apps",$array);
          unset($archive_files[$key]);
          break;
        }
      }

      foreach ($archive_files AS $key => $file) {
        $filename = basename($file['filename']);
        if($filename=="iCMS.APP.TABLE.php"){
          $content = get_php_content($file['content']);
          // $content && apps_db::multi_query($content);
          unset($archive_files[$key]);
          break;
        }
      }
      print_r($archive_files);

    //     iFile::$check_data        = false;
    //     iFile::$cloud_enable      = false;
    //     iFS::$config['allow_ext'] = 'zip';

    //     $F    = iFS::upload('upfile');
    //     $path = $F['RootPath'];
    //     if($path){
    //       apps_store::$app_name = preg_replace('/iCMS\.APP\.(\w+)-v.*?\.zip/is', '$1', $F['oname']);
    //       print_r(apps_store::$app_name);

    //       @unlink($path);
    //       // iUI::success('应用安装完成,请重新设置规则','js:1');
    //     }
    }
    /**
     * [打包下载应用]
     * @return [type] [description]
     */
    public function do_pack(){
      $rs = iDB::row("SELECT * FROM `#iCMS@__apps` where `id`='".$this->id."'",ARRAY_A);
      $appdir = iPHP_APP_DIR.'/'.$rs['app'];
      unset($rs['id']);
      $data     = base64_encode(serialize($rs));
      $config   = json_decode($rs['config'],true);
      $filename = 'iCMS.APP.'.$rs['app'].'-'.$config['version'];
      if(iFS::ex($appdir)) {
        $remove_path = iPHP_APP_DIR;
      }else{
        $appdir = iPHP_APP_CACHE.'/pack.app/'.$rs['app'];
        $remove_path = iPHP_APP_CACHE.'/pack.app/';
        iFS::mkdir($appdir);
      }
      $app_data_file = $appdir.'/iCMS.APP.DATA.php';
      put_php_file($app_data_file, $data);
      if($rs['table']){
        $app_table_file = $appdir.'/iCMS.APP.TABLE.php';

        put_php_file(
          $app_table_file,
          apps_db::create_table_sql($rs['table'])
        );
      }
      $zipfile = apps::get_zip($filename,$appdir,$remove_path);
      filesApp::attachment($zipfile);
      iFS::rm($zipfile);
      iFS::rm($app_data_file);
      $app_table_file && iFS::rm($app_table_file);

      if($remove_path != iPHP_APP_DIR){
        iFS::rmdir($remove_path);
      }

    }
}
