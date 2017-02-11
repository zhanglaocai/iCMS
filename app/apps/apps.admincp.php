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
      $data  = $_POST['data'];
      $app   = apps::get($appid);

      foreach ($app['fields'] as $key => $value) {
        $output = array();
        if($value!='UI:BR'){
            parse_str($value,$output);
            $field_data[$key] = $output;
        }
      }

      foreach ($data as $key => $value) {
        print_r($field_data[$key]);
        $field = $field_data[$key]['field'];
        $type  = $field_data[$key]['type'];
        if(in_array($field, array('BIGINT','INT','MEDIUMINT','SMALLINT','TINYINT'))){
          $data[$key] = (int)$value;
        }elseif(in_array($type, array('date','datetime'))){
          $data[$key] = str2time($value);
        }elseif($type=='editor'){
          $data[$key] = $value;
        }elseif(isset($field_data[$key]['multiple'])){
          $data[$key] = implode(',', (array)$value);
        }else{
          $data[$key] = iSecurity::escapeStr($value);
        }
      }
      print_r($data);
      $table = reset($app['table']);
      $id    = $data[$table['primary']];
      foreach ($data as $key => $value) {
          if($key[0]=='_'){
            unset($data[$key]);
          }
      }
      if(empty($id)) {
          // iDB::value("SELECT `id` FROM `#iCMS@__keywords` where `keyword` ='$keyword'") && iUI::alert('该关键词已经存在!');
          iDB::insert($table['name'],$data);
      }else {
          // iDB::value("SELECT `id` FROM `#iCMS@__keywords` where `keyword` ='$keyword' AND `id` !='$id'") && iUI::alert('该关键词已经存在!');
          iDB::update($table['name'], $data, array('id'=>$id));
      }
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
        iFormer::$app = $app;
        iFormer::$gateway = 'admincp';

        foreach ($app['fields'] as $key => $value) {
          $output = array();
          if($value=='UI:BR'){
              $output = array('type'=>'br');
          }else{
              parse_str($value,$output);
          }
          $html.= iFormer::render($output,$rs[$output['name']]);
          $submit.= iFormer::validate($output);
          $script.= iFormer::script($output['javascript']);
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
        if(empty($rs['fields'])){
          $fields_json = include iPHP_APP_DIR.'/apps/etc/fields.json.php';
          $rs['fields'] = json_decode($fields_json,true);
        }
        include admincp::view("apps.add");
    }

    public function do_save(){
        $id       = (int)$_POST['_id'];
        $name     = iSecurity::escapeStr($_POST['_name']);
        $app      = iSecurity::escapeStr($_POST['_app']);
        $type     = (int)$_POST['type'];
        $status   = (int)$_POST['status'];

        $table    = $_POST['table'];
        $config   = $_POST['config'];
        $fieldata = $_POST['fields'];

        $name OR iUI::alert('应用名称不能为空!');
        empty($app) && $app = iPinyin::get($name);


        if($table){
          $table  = array_filter($table);
          $table  = addslashes(json_encode($table));
        }

        if($config['template']){
          $config['template'] = explode("\n", $config['template']);
          $config['template'] = array_map('trim', $config['template']);
        }
        $config = array_filter($config);
        $config = addslashes(json_encode($config));
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
            }
          }
          $fields = addslashes(json_encode($field_array));
        }

        $addtimes = time();
        $array    = compact(array('app','name','table','config','fields','addtimes','type','status'));

        if(empty($id)) {
            iDB::value("SELECT `id` FROM `#iCMS@__apps` where `app` ='$app'") && iUI::alert('该应用已经存在!');
            //创建基本表
            $table = apps_db::create_table($array['app'],$fieldata);
            array_push ($table,$array['name']);
            $array['table'] = addslashes(json_encode(array($array['app']=>$table)));

            iDB::insert('apps',$array);
            $msg = "应用创建完成!";
        }else {
            iDB::value("SELECT `id` FROM `#iCMS@__apps` where `app` ='$app' AND `id` !='$id'") && iUI::alert('该应用已经存在!');
            $_fields = iDB::value("SELECT `fields` FROM `#iCMS@__apps` where `id` ='$id'");
            $sql_array = apps_db::make_alter_sql($fields,$_fields,$_POST['origin']);
            $sql_array && apps_db::alter_table($array['app'],$sql_array);

            iDB::update('apps', $array, array('id'=>$id));
            $msg = "应用编辑完成!";
        }
        // iUI::success($msg,'url:'.APP_URI);
        iUI::success($msg,'js:1');
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
