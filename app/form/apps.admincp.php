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

iPHP::app('apps.class','include');

class appsAdmincp{
    public function __construct() {
    	$this->id = (int)$_GET['id'];
      $this->cache();

    }
    public function do_add(){
        if($this->id) {
            $rs = iDB::row("SELECT * FROM `#iCMS@__app` WHERE `id`='$this->id' LIMIT 1;",ARRAY_A);
            $rs['field'] && $rs['field'] = json_decode($rs['field'],true);
        }
        $BASE_FIELDS = $this->BASE_FIELDS();
        include admincp::view("apps.add");
    }

    public function do_save(){

        $id          = (int)$_POST['id'];
        $title       = iSecurity::escapeStr($_POST['title']);
        $name        = iSecurity::escapeStr($_POST['name']);
        $description = iSecurity::escapeStr($_POST['description']);
        $field       = $_POST['fields'];

        $title OR iUI::alert('应用名称不能为空!');
        empty($name) && $name = pinyin($title);
        $table_array = array(array($name,'id'));
        $table       = json_encode($table_array);

        $fields = array('title', 'name','table','field','description');
        $data   = compact ($fields);

        if(is_array($field)){
          foreach ($field as $key => $value) {
            $output = array();
            parse_str($value,$output);
            $output['label'] OR iUI::alert('发现自定义字段中空字段名称!');
            $fname = $output['fname'];
            $fname OR iUI::alert('发现自定义字段中有空字段名!');
            $field_array[$fname] = $value;
          }
          $data['field'] = addslashes(json_encode($field_array));
        }

        if(empty($id)) {
            iDB::value("SELECT `id` FROM `#iCMS@__app` where `name` ='$name'") && iUI::alert('该应用已经存在!');
            iDB::insert('app',$data);
            $this->CREATE_TABLE($data['name'],$field);
            $this->cache();
            $msg = "应用添加完成!";
        }else {
            iDB::value("SELECT `id` FROM `#iCMS@__app` where `name` ='$name' AND `id` !='$id'") && iUI::alert('该应用已经存在!');
            $_field = iDB::value("SELECT `field` FROM `#iCMS@__app` where `id` ='$id'");
            if($_field){
              $_field_array = json_decode($_field);
              $diff = array_diff_values($field_array,$_field_array);
              if($diff['+']){
                foreach ($diff['+'] as $key => $value) {
                  if($diff['-'][$key]){
                    $this->FIELD_CHANGE($value);
                  }else{
                    $this->FIELD_ADD_COLUMN($value);
                  }
                }
              }
            }

            iDB::update('app', $data, array('id'=>$id));
            $this->cache();
            $msg = "应用编辑完成!";
        }
        iUI::success($msg,'url:'.APP_URI);
    }

    public function do_iCMS(){
      if($_GET['keywords']) {
		   $sql=" WHERE `keyword` REGEXP '{$_GET['keywords']}'";
      }
      $orderby    =$_GET['orderby']?$_GET['orderby']:"id DESC";
      $maxperpage = $_GET['perpage']>0?(int)$_GET['perpage']:20;
      $total      = iPHP::total(false,"SELECT count(*) FROM `#iCMS@__app` {$sql}","G");
      iUI::pagenav($total,$maxperpage,"个应用");
      $rs     = iDB::all("SELECT * FROM `#iCMS@__app` {$sql} order by {$orderby} LIMIT ".iUI::$offset." , {$maxperpage}");
      $_count = count($rs);
    	include admincp::view("apps.manage");
    }
    public function do_del($id = null,$dialog=true){
    	$id===null && $id=$this->id;
  		$id OR iUI::alert('请选择要删除的应用!');
      $rs   = iDB::row("SELECT `name` FROM `#iCMS@__app` WHERE `id`='$id' LIMIT 1;");
      $name = $rs->name;
      iDB::query("DROP TABLE `#iCMS@__{$name}`; ");

  		iDB::query("DELETE FROM `#iCMS@__app` WHERE `id` = '$id'");
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
				iPHP::$break	= false;
	    		foreach($idArray AS $id){
	    			$this->do_del($id,false);
	    		}
	    		iPHP::$break	= true;
				iUI::success('应用全部删除完成!','js:1');
    		break;
		}
	}
    public function cache(){
    	APPS::cache();
    }
    public function field_html($fid='{fid}',$fname='{fname}',$param='{param}'){
      return '<div class="row-fluid" id="'.$fid.'">'.
              '<div class="input-prepend input-append">'.
              '<span class="add-on"><i class="fa fa-text-width"></i></span>'.
              '<span name="fname" class="input-medium span3 uneditable-input">'.$fname.'</span>'.
              '<input name="fields[]" type="hidden" value="'.$param.'" />'.
              '<a name="editor" class="btn btn-inverse">编辑</a>'.
              '<a name="delete" class="btn btn-danger">删除</a>'.
              '</div>'.
              '<span class="help-inline"></span>'.
              '<div class="clearfloat mb10"></div></div>';
    }
    public function MAKE_SQL($vars=null){
      $type    = $vars['type'];  //字段类型
      $label   = $vars['label']; //字段名称
      $fname    = $vars['fname'];  //字 段 名
      $default = $vars['default']; //默 认 值
      $len     = $vars['length']; //数据长度
      //程序
      $holder   = $vars['holder'];  //默认提示
      $tip      = $vars['tip'];     //字段说明
      $style    = $vars['style'];   //字段样式
      $validate = $vars['validate'];//数据验证
      $error    = $vars['error'];   //错误提示
      $fun      = $vars['fun'];     //数据处理
      $foreign  = $vars['foreign']; //关联应用

      empty($fname) && $fname = pinyin($label);

      switch ($type) {
        case 'varchar':
        case 'multivarchar':
          $data_type = 'varchar';
          $data_len  = '('.$len.')';
        break;
        case 'tinyint':
          $data_type = 'tinyint';
          $data_len  = '(1)';
          $default   = (int)$default;
        break;
        case 'int':
        case 'time':
          $data_type = 'INT';
          $data_len  = '(10)';
          $default   = (int)$default;
        break;
        case 'bigint':
          $data_type = 'bigint';
          $data_len  = '(20)';
          $default   = (int)$default;
        break;
        case 'radio':
        case 'select':
          $data_type = 'smallint';
          $data_len  = '(6)';
        break;
        case 'checkbox':
        case 'multiselect':
          $data_type = 'varchar';
          $data_len  = '(255)';
        break;
        case 'image':
        case 'file':
          $data_type = 'varchar';
          $data_len  = '(255)';
        break;
        case 'multiimage':
        case 'multifile':
          $data_type = 'text';
          $data_len  = '(10240)';
        break;
        case 'text':
        case 'mediumtext':
        case 'editor':
          $data_type = 'mediumtext';
        break;
        default:
         $data_type = 'varchar';
         $data_len  = '(255)';
        break;
      }

      return "`$fname` $data_type$data_len NOT NULL DEFAULT '$default' COMMENT '$label'";
      // return "ADD COLUMN `$name` $data_type$data_len DEFAULT '$default' NOT NULL  COMMENT '$label'";
    }
    public function BASE_FIELDS(){
      $sql = $this->CREATE_TABLE('test',null,true);
      preg_match_all("@`(.+)`\s(.+)\sDEFAULT\s'(.*?)'\sCOMMENT\s'(.+)',@", $sql, $matches);
      return $matches;
      //print_r($matches);
    }
    public function CREATE_TABLE($name,$fields=null,$sql=false){
      $CREATE_SQL = "CREATE TABLE `#iCMS@__{$name}` (";
      $CREATE_SQL.= "
        `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
        `cid` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '栏目id',
        `ucid` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户分类',
        `pid` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '属性',
        `sortnum` SMALLINT(6) UNSIGNED NOT NULL DEFAULT '0' COMMENT '排序',
        `title` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '标题',
        `editor` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '编辑 用户名',
        `userid` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户ID',
        `pubdate` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '发布时间',
        `postime` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '提交时间',
        `tpl` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '模板',
        `hits` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '总点击数',
        `hits_today` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '当天点击数',
        `hits_yday` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '昨天点击数',
        `hits_week` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '周点击',
        `hits_month` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '月点击',
        `favorite` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '收藏数',
        `comments` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '评论数',
        `good` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '顶',
        `bad` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '踩',
        `creative` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '内容类型 1原创 0转载',
        `weight` SMALLINT(6) NOT NULL DEFAULT '0' COMMENT '权重',
        `mobile` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '1手机发布 0 pc',
        `postype` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '类型 0用户 1管理员',
        `status` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '[[0:草稿],[1:正常],[2:回收],[3:审核],[4:不合格]]',
      ";
      if($fields){
        $fsql_array = array();
        foreach ($fields as $key => $_field) {
          $output = array();
          parse_str($_field,$output);
          $output && $fsql_array[] = $this->MAKE_SQL($output);
        }
        $fsql_array && $CREATE_SQL.= implode(',', $fsql_array).',';
      }
      $CREATE_SQL.="
        PRIMARY KEY (`id`),
        KEY `id` (`status`,`id`),
        KEY `hits` (`status`,`hits`),
        KEY `pubdate` (`status`,`pubdate`),
        KEY `hits_week` (`status`,`hits_week`),
        KEY `hits_month` (`status`,`hits_month`),
        KEY `cid_hits` (`status`,`cid`,`hits`)
      ) ENGINE=MYISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8
      ";
     if($sql){
        return $CREATE_SQL;
     }
     return iDB::query($CREATE_SQL);
    }
}
