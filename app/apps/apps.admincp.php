<?php
/**
* iCMS - i Content Management System
* Copyright (c) 2007-2012 idreamsoft.com iiimon Inc. All rights reserved.
*
* @author coolmoo <idreamsoft@qq.com>
* @site http://www.idreamsoft.com
* @licence http://www.idreamsoft.com/license.php
* @version 6.0.0
*/
defined('iPHP') OR exit('What are you doing?');

class appsAdmincp{
    const STORE_URL = "http://store.idreamsoft.com";
    const STORE_DIR = 'cache/iCMS/store/';

    public function __construct() {
      $this->appid = iCMS_APP_APPS;
    	$this->id = (int)$_GET['id'];
    }
    public function do_app_save(){
      $appid = (int)$_POST['appid'];
      $post  = $_POST[iFormer::$prefix];
      $app   = apps::get($appid);

      $field_array = apps_app::get_field_array($app['fields']);
      $table_array = $app['table'];

      foreach ($post as $key => $value) {
        $field = $field_array[$key]['field'];

        list($type,$type2) = explode(':', $field_array[$key]['type']);

        if(in_array($type, array('date','datetime'))){
          $value = str2time($value);
        }

        if(isset($field_array[$key]['multiple'])||in_array($type, array('checkbox'))){
          $value = implode(',', (array)$value);
        }

        if(in_array($field, array('BIGINT','INT','MEDIUMINT','SMALLINT','TINYINT'))){
          $value = (int)$value;
        }


        if($type=='editor'){
          // $post[$key] = $value;
        }else{
          $value = iSecurity::escapeStr($value);
        }

        $post[$key] = $value;

        if(strpos($key,'_orig_')!==false){
          $orig_post[$key] = $value;
          unset($post[$key]);
        }
        if($field=='MEDIUMTEXT'){
          $addons_post[$key] = $value;
          unset($post[$key]);
        }
      }
      print_r($post);

      $keys     = array_keys($table_array);//返回所有表名
      $values   = compact ('post','addons_post'); //将表单数据存入数组
      //创建一个数组，用一个表名数组的值作为其键名，表单数据的值作为其值
      $variable = array_combine($keys,$values);

      foreach ($variable as $table_name => $data) {
        $table   = $table_array[$table_name];
        $primary = $table['primary'];
        if($table['union']){
          $union  =  reset(compact ($table['union']));
          $union && $data[$table['union']] = $union;
        }
        //union
        if(empty($data[$primary])){ //主键值为空
          unset($data[$primary]);
          $iid = iDB::insert($table_name,$data);
        }else{
          $id = $data[$primary];
          unset($data[$primary]);
          iDB::update($table_name, $data, array($primary=>$id));
        }
        print_r($iid);
      }
      // print_r($app['table']);
      exit;


      // if(empty($id)) {
      //     // iDB::value("SELECT `id` FROM `#iCMS@__keywords` where `keyword` ='$keyword'") && iUI::alert('该关键词已经存在!');
      //     iDB::insert($table['name'],$data);
      // }else {
      //     // iDB::value("SELECT `id` FROM `#iCMS@__keywords` where `keyword` ='$keyword' AND `id` !='$id'") && iUI::alert('该关键词已经存在!');
      //     iDB::update($table['name'], $data, array('id'=>$id));
      // }
    }
    public function do_app_add(){
      $appid = (int)$_GET['appid'];
      $app   = apps::get($appid);
      $rs    = apps_app::get_data($app,$this->id);
      // $fields_json = include iPHP_APP_DIR.'/apps/etc/fields.json.php';
      // $app['fields'] = json_decode($fields_json,true);

      if($app['fields']){
        iFormer::$default = array(
          'userid'   => members::$userid,
          'username' => members::$data->username?members::$data->username:members::$data->nickname,
        );
        iFormer::$config  = $app;
        iFormer::$gateway = 'admincp';

        $fields = apps_app::get_field_array($app['fields'],true);
        foreach ($fields as $key => $value) {
          $html.= iFormer::html($value,$rs[$value['name']]);
          $submit.= iFormer::validate($value);
          $script.= iFormer::script($value['javascript']);
        }
      }

      include admincp::view('app.add');
    }

    public function do_hooks(){
        configAdmincp::app($this->appid,'hooks');
    }
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

    public function do_hooks_app_field_opt(){
      $app = $_GET['_app'];
      echo apps_hook::app_fields($app);
    }
    public function do_store(){
      include admincp::view("apps.store");
    }
    public function do_store_json(){
      $url  = self::STORE_URL.'/store.json.php';
      $json = iHttp::remote($url);
      echo $json;
    }
    public function do_store_install(){
      $sid  = $_GET['sid'];
      $key  = md5(iPHP_KEY.iPHP_SELF.time());
      $url  = self::STORE_URL.'/store.get.php?sid='.$sid.'&key='.$key;
      $json = iHttp::remote($url);
      if($json){
        $array = json_decode($json);
        if($array->premium){
          iUI::$break            = false;
          iUI::$dialog['ok']     = true;
          iUI::$dialog['cancel'] = true;
          iUI::dialog('
            此应用为付费版,请先付费后安装!<br />
            请使用微信扫描下面二维码<br />
            <img alt="模式一扫码支付" src="http://paysdk.weixin.qq.com/example/qrcode.php?data='.$array->pay.'"/>
          ','js:1',1000000);
          echo '<script type="text/javascript">
            top.pay_notify("'.$key.'","'.$sid.'",d);
          </script>';
          exit;
        }
      }

    }

    public function do_uninstall(){
      if($this->id>99){
        apps::uninstall($this->id);
        iUI::alert('应用已经删除');
      }

    }
    public function do_install(){
      $app = iSecurity::escapeStr($_GET['appname']);
      strstr($app,'..')!==false  && iUI::alert('您的应用有问题!');
      $path = apps::installed($app,'path');
      iFS::write($path,'1');
      iUI::success('安装完成!','url:'.APP_URI);
    }

    public function do_add(){
        $this->id && $rs = apps::get($this->id);
        $rs['table'] OR $base_fields = apps_db::base_fields_array();
        // $_fields = $rs['_fields'];
        if(empty($rs['fields'])){
          $_fields = include iPHP_APP_DIR.'/apps/etc/fields.json.php';
          $rs['fields'] = json_decode($_fields,true);
        }
        include admincp::view("apps.add");
    }

    public function do_save(){
        $id       = (int)$_POST['_id'];
        $name     = iSecurity::escapeStr($_POST['_name']);
        $app      = iSecurity::escapeStr($_POST['_app']);
        $type     = (int)$_POST['type'];
        $status   = (int)$_POST['status'];

        $fieldata = $_POST['fields'];
        $config_array = $_POST['config'];
        $table_array  = $_POST['table'];

        $name OR iUI::alert('应用名称不能为空!');
        empty($app) && $app = iPinyin::get($name);

        if($table_array){
          $table_array  = array_filter($table_array);
          $table  = addslashes(json_encode($table_array));
        }

        if($config_array['template']){
          $config_array['template'] = explode("\n", $config_array['template']);
          $config_array['template'] = array_map('trim', $config_array['template']);
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

        $addtimes = time();
        $array    = compact(array('app','name','table','config','fields','addtimes','type','status'));

        if(empty($id)) {
            iDB::value("SELECT `id` FROM `#iCMS@__apps` where `app` ='$app'") && iUI::alert('该应用已经存在!');
            apps_db::check_table(iDB::table($array['app'])) && iUI::alert('['.$array['app'].']数据表已经存在!');
            if($addons_fieldata){
              $addons_name = $array['app'].'_data';
              apps_db::check_table(iDB::table($addons_name)) && iUI::alert('['.$addons_name.']附加表已经存在!');
            }

            //创建基本表
            $tb = apps_db::create_table(
              $array['app'],
              apps_app::get_field_array($fieldata)//获取字段数组
            );
            array_push ($tb,'master');
            array_push ($tb,$array['name']);
            $table_array = array();
            $table_array[$array['app']]= $tb;

            //有MEDIUMTEXT类型字段就创建xxx_data附加表
            if($addons_fieldata){
              $addons_name = $array['app'].'_data';
              $btb = apps_db::create_table(
                $addons_name,
                apps_app::get_field_array($addons_fieldata),//获取字段数组
                false,'data_id',true
              );
              array_push ($btb,'iid');
              array_push ($btb,'正文');
              $table_array[$addons_name]= $btb;
            }

            $array['table'] = addslashes(json_encode($table_array));

            iDB::insert('apps',$array);
            $msg = "应用创建完成!";
        }else {
            iDB::value("SELECT `id` FROM `#iCMS@__apps` where `app` ='$app' AND `id` !='$id'") && iUI::alert('该应用已经存在!');
            $_fields     = iDB::value("SELECT `fields` FROM `#iCMS@__apps` where `id` ='$id'");//json
            $_json_field = apps_db::json_field($_fields);//旧数据
            $json_field  = apps_db::json_field($fields); //新数据

            // print_r($_json_field);
            // print_r($json_field);

            /**
             * 找出字段数据中的 MEDIUMTEXT类型字段
             * @var array
             */
            $_addons_json_field = array();
            foreach ($_json_field as $key => $value) {
                $a = json_decode($value,true);
                if(strtoupper($a['field'])=="MEDIUMTEXT"){
                  $_addons_json_field[$key] = $value;
                  unset($_json_field[$key]);//不参与基本表比较
                }
            }
            /**
             * 找出字段数据中的 MEDIUMTEXT类型字段
             * @var array
             */
            $addons_json_field = array();
            foreach ($json_field as $key => $value) {
                $a = json_decode($value,true);
                if(strtoupper($a['field'])=="MEDIUMTEXT"){
                  $addons_json_field[$key] = $value;
                  unset($json_field[$key]);//不参与基本表比较
                }
            }
            // print_r($_addons_json_field);
            // print_r($addons_json_field);

            //基本表 新旧数据计算交差集 origin 为旧字段名
            $sql_array = apps_db::make_alter_sql($json_field,$_json_field,$_POST['origin']);
            $sql_array && apps_db::alter_table($array['app'],$sql_array);

            //MEDIUMTEXT类型字段 新旧数据计算交差集 origin 为旧字段名
            $addons_sql_array = apps_db::make_alter_sql($addons_json_field,$_addons_json_field,$_POST['origin']);


            if($addons_sql_array){
              //附加表名
              $addons_name = $array['app'].'_data';
              //检测附加表是否存在
              if($table_array[$addons_name] && apps_db::check_table(iDB::table($addons_name))){
                //表存在执行 alter
                apps_db::alter_table($addons_name,$addons_sql_array);
              }else{
                // 不存在 用新数据创建
                if($addons_fieldata){
                  apps_db::check_table(iDB::table($addons_name)) && iUI::alert('['.$addons_name.']附加表已经存在!');
                  //有MEDIUMTEXT类型字段创建xxx_data附加表
                  $btb = apps_db::create_table(
                    $addons_name,
                    apps_app::get_field_array($addons_fieldata),//获取字段数组
                    false,'data_id',true
                  );
                  array_push ($btb,'iid');
                  array_push ($btb,'大数据表');
                  $table_array[$addons_name]= $btb;
                  $array['table'] = addslashes(json_encode($table_array));
                }

              }
            }
            // exit;
            iDB::update('apps', $array, array('id'=>$id));
            $msg = "应用编辑完成!";
        }
        iUI::success($msg,'url:'.APP_URI);
    }
    public function do_update(){
        if($this->id){
            $args = admincp::update_args($_GET['_args']);
            $args && iDB::update("apps",$args,array('id'=>$this->id));
            iUI::success('操作成功!','js:1');
        }
    }
    public function do_iCMS(){
      if($_GET['keywords']) {
		   $sql=" WHERE `keyword` REGEXP '{$_GET['keywords']}'";
      }
      $orderby    =$_GET['orderby']?$_GET['orderby']:"id DESC";
      $maxperpage = $_GET['perpage']>0?(int)$_GET['perpage']:50;
      $total      = iCMS::page_total_cache("SELECT count(*) FROM `#iCMS@__apps` {$sql}","G");
      iUI::pagenav($total,$maxperpage,"个应用");
      $rs     = iDB::all("SELECT * FROM `#iCMS@__apps` {$sql} order by {$orderby} LIMIT ".iUI::$offset." , {$maxperpage}");
      $_count = count($rs);
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
  		$this->cache();
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
      $this->cache();
      iUI::success('更新完成');
    }
    public function cache(){
    	apps::cache();
    }

}
