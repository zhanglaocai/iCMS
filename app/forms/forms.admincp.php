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

class formsAdmincp{
    public function __construct() {
      $this->appid = iCMS_APP_FORMS;
      $this->id = (int)$_GET['id'];
      $_GET['form_id'] && $this->form_id = (int)$_GET['form_id'];
    }
    public function form_init(){
      $this->app = forms::get($this->form_id);
    }
    /**
     * [添加表单内容]
     * @return [type] [description]
     */
    public function do_submit(){
      $this->form_init();
      $rs = forms::get_data($this->app,$this->id);
      iPHP::callback(array("formerApp","add"),array($this->app,$rs));
      include admincp::view('forms.submit');
    }
    /**
     * [保存表单数据]
     * @return [type] [description]
     */
    public function do_savedata(){
      $this->form_id = (int)$_POST['form_id'];
      $this->form_init();
      $update = iPHP::callback(array("formerApp","save"),array($this->app));
      $REFERER_URL = $_POST['REFERER'];
      if(empty($REFERER_URL)||strstr($REFERER_URL, '=form_save')){
          $REFERER_URL= APP_URI.'&do=form_manage&form_id='.$this->form_id;
      }
      if($update){
          iUI::success($this->app['name'].'编辑完成!<br />3秒后返回'.$this->app['name'].'列表','url:'.$REFERER_URL);
      }else{
          iUI::success($this->app['name'].'添加完成!<br />3秒后返回'.$this->app['name'].'列表','url:'.$REFERER_URL);
      }
    }
    /**
     * [表单数据查看]
     * @param  string $stype [description]
     * @return [type]        [description]
     */
    public function do_data($stype='normal') {
        $this->form_init();
        $table_array = apps::get_table($this->app);
        $table       = $table_array['table'];
        $primary     = $table_array['primary'];

        $sql = "WHERE 1=1";

        if($_GET['keywords']) {
          $sql.=" AND title REGEXP '{$_GET['keywords']}'";
        }

        isset($_GET['keywords'])&& $uri.='&keyword='.$_GET['keywords'];

        $orderby    = $_GET['orderby']?$_GET['orderby']:"{$primary} DESC";
        $maxperpage = $_GET['perpage']>0?(int)$_GET['perpage']:20;
        $total      = iCMS::page_total_cache("SELECT count(*) FROM `{$table}` {$sql}","G");
        iUI::pagenav($total,$maxperpage,"条记录");

        $rs = iDB::all("SELECT * FROM `{$table}` {$sql} order by {$orderby} LIMIT ".iUI::$offset." , {$maxperpage}");
        $_count = count($rs);
        if($this->app['fields']){
            $fields = former::fields($this->app['fields']);
        }
        include admincp::view('forms.data');
    }
    /**
     * [创建表单]
     * @return [type] [description]
     */
    public function do_create(){
        $this->id && $rs = forms::get($this->id);
        if(empty($rs)){
          $rs['type']   = "1";
          $rs['status'] = "1";
          $rs['fields'] = forms::base_fields_json();
          $rs['fields'] = json_decode($rs['fields'],true);
          $base_fields  = forms::base_fields_array();
        }
        $rs['app'] = ltrim($rs['app'],'forms_');
        include admincp::view("forms.create");
    }
  /**
   * [保存表单]
   * @return [type] [description]
   */
    public function do_save(){
        $id      = (int)$_POST['_id'];
        $name    = iSecurity::escapeStr($_POST['_name']);
        $title   = iSecurity::escapeStr($_POST['_title']);
        $app     = iSecurity::escapeStr($_POST['_app']);
        $type    = (int)$_POST['type'];
        $status  = (int)$_POST['status'];

        $fieldata     = $_POST['fields'];
        $config_array = $_POST['config'];
        $table_array  = $_POST['table'];


        $name OR iUI::alert('表单名称不能为空!');
        empty($app) && $app = iPinyin::get($name);
        empty($title) && $title = $name;
        $app = 'forms_'.ltrim($app,'forms_');

        if($table_array){
          $table_array  = array_filter($table_array);
          $table  = addslashes(json_encode($table_array));
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

            iDB::value("SELECT `id` FROM `#iCMS@__forms` where `app` ='$app'") && iUI::alert('该表单已经存在!');
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
              forms::base_fields_index()//索引
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

            $array['table'] = addslashes(json_encode($table_array));
            $array['config'] = addslashes(json_encode($config_array));

            $id = iDB::insert('forms',$array);

            $msg = "表单创建完成!";
        }else {
            iDB::value("SELECT `id` FROM `#iCMS@__forms` where `app` ='$app' AND `id` !='$id'") && iUI::alert('该表单已经存在!');
            $_fields     = iDB::value("SELECT `fields` FROM `#iCMS@__forms` where `id` ='$id'");//json
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
                //删除自定义表单的表
                //不存在附加表数据 直接删除附加表 返回 table的json值 $table_array为引用参数
                apps_mod::drop_table($addons_fieldata,$table_array,$addons_name);
                $array['table'] = addslashes(json_encode($table_array));
            }

            iDB::update('forms', $array, array('id'=>$id));
            $msg = "表单编辑完成!";
        }
        apps::cache();
        iUI::success($msg,'url:'.APP_URI);
    }

    public function do_update(){
        if($this->id){
            $args = admincp::update_args($_GET['_args']);
            $args && iDB::update("forms",$args,array('id'=>$this->id));
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
      $total      = iCMS::page_total_cache("SELECT count(*) FROM `#iCMS@__forms` {$sql}","G");
      iUI::pagenav($total,$maxperpage,"个表单");
      $rs     = iDB::all("SELECT * FROM `#iCMS@__forms` {$sql} order by {$orderby} LIMIT ".iUI::$offset." , {$maxperpage}");
    	include admincp::view("forms.manage");
    }

    public function do_batch(){
        $idArray = (array)$_POST['id'];
        $idArray OR iUI::alert("请选择要操作的表单");
        $ids     = implode(',',$idArray);
        $batch   = $_POST['batch'];
      	switch($batch){
  		  }

	  }
    public function do_cache(){
      apps::cache();
      iUI::success('更新完成');
    }

    /**
     * [卸载表单]
     * @return [type] [description]
     */
    public function do_del($id = null,$dialog=true){
      $id===null && $id=$this->id;
      $id OR iUI::alert('请选择要删除的表单!');
      $forms = forms::get($id);
      forms::delete($this->id);
      $dialog && iUI::success("表单已经删除!",'url:'.APP_URI);
    }
    /**
     * [本地安装表单]
     * @return [type] [description]
     */
    public function do_local_app(){
      if(strpos($_POST['zipfile'], '..') !== false){
        iUI::alert('What the fuck!!');
      }
      apps_store::$zipFile  = trim($_POST['zipfile'],"\0\n\r\t\x0B");
      apps_store::$msg_mode = 'alert';
      apps_store::install();
      iUI::success('表单安装完成','js:1');
    }
    /**
     * [打包下载表单]
     * @return [type] [description]
     */
    public function do_pack(){
      $rs = iDB::row("SELECT * FROM `#iCMS@__forms` where `id`='".$this->id."'",ARRAY_A);
      unset($rs['id']);
      $data     = base64_encode(serialize($rs));
      $filename = 'iCMS.FORMS.'.$rs['app'];
      //自定义表单
      $appdir = iPHP_APP_CACHE.'/pack.forms/'.$rs['app'];
      $remove_path = iPHP_APP_CACHE.'/pack.forms/';
      iFS::mkdir($appdir);

      //表单数据
      $app_data_file = $appdir.'/iCMS.APP.DATA.php';
      put_php_file($app_data_file, $data);

      //数据库结构
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
      iFS::rmdir($remove_path);
    }
    public function setup_zipurl($url,$name,$zipname=null){
          // apps_store::$test = true;
        $msg = apps_store::download($url,$name,$zipname);
        $msg.= apps_store::install();
        $msg = str_replace('<iCMS>', '<br />', $msg);
        if(apps_store::$app_id){
          iUI::dialog($msg,'url:'.APP_URI."&do=add&id=".apps_store::$app_id,10);
        }else{
          iUI::dialog($msg,'js:1',3);
        }
    }
}
