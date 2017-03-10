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
class contentAdmincp{
    public $appid = null;
    public $app = null;
    public $callback = array();

    public function __construct($data) {
        $this->app       = $data;
        $this->appid     = $data['id'];
        $_GET['appid'] && $this->appid = (int)$_GET['appid'];

        $this->id        = (int)$_GET['id'];
        $this->_postype  = '1';
        $this->_status   = '1';

        category::$appid = $this->appid;
    }
    public function do_add(){
      $rs = apps_mod::get_data($this->app,$this->id);
      apps::former_create($this->appid,$rs);
      include admincp::view('content.add');
    }

    public function do_iCMS(){
    	admincp::$APP_METHOD="domanage";
    	$this->do_manage();
    }
    public function do_inbox(){
        $this->do_manage("inbox");
    }
    public function do_trash(){
        $this->_postype = 'all';
        $this->do_manage("trash");
    }
    public function do_user(){
        $this->_postype = 0;
        $this->do_manage();
    }
    public function do_examine(){
        $this->_postype = 0;
        $this->do_manage("examine");
    }
    public function do_off(){
        $this->_postype = 0;
        $this->do_manage("off");
    }
    public function do_manage($stype='normal') {
        $table_array = apps::get_table($this->app);
        $table       = $table_array['table'];
        $primary     = $table_array['primary'];

        $cid = (int)$_GET['cid'];
        $stype_map = array(
            'inbox'   =>'0',//草稿
            'normal'  =>'1',//正常
            'trash'   =>'2',//回收站
            'examine' =>'3',//待审核
            'off'     =>'4',//未通过
        );
        //status:[0:草稿][1:正常][2:回收][3:待审核][4:不合格]
        //postype: [0:用户][1:管理员]
        $stype && $this->_status = $stype_map[$stype];
        if(isset($_GET['pt']) && $_GET['pt']!=''){
            $this->_postype = (int)$_GET['pt'];
        }
        if(isset($_GET['status'])){
            $this->_status = (int)$_GET['status'];
        }
        $sql = "WHERE `status`='{$this->_status}'";

        $this->_postype==='all' OR $sql.= " AND `postype`='{$this->_postype}'";

        if($_GET['keywords']) {
			$sql.=" AND title REGEXP '{$_GET['keywords']}'";
        }

        $sql.= category::search_sql($cid);

        isset($_GET['nopic'])&& $sql.=" AND `haspic` ='0'";
        $_GET['starttime']   && $sql.=" and `pubdate`>=UNIX_TIMESTAMP('".$_GET['starttime']."')";
        $_GET['endtime']     && $sql.=" and `pubdate`<=UNIX_TIMESTAMP('".$_GET['endtime']."')";


        isset($_GET['userid']) && $uri.='&userid='.(int)$_GET['userid'];
        isset($_GET['keywords'])&& $uri.='&keyword='.$_GET['keywords'];
        isset($_GET['pid'])    && $uri.='&pid='.$_GET['pid'];
        isset($_GET['cid'])    && $uri.='&cid='.$_GET['cid'];
        (isset($_GET['pid']) && $_GET['pid']!='-1') && $uri.='&pid='.$_GET['at'];

        $orderby    = $_GET['orderby']?$_GET['orderby']:"{$primary} DESC";
        $maxperpage = $_GET['perpage']>0?(int)$_GET['perpage']:20;

        $total      = iCMS::page_total_cache("SELECT count(*) FROM `{$table}` {$sql}","G");
        iUI::pagenav($total,$maxperpage,"条记录");

        $rs = iDB::all("SELECT * FROM `{$table}` {$sql} order by {$orderby} LIMIT ".iUI::$offset." , {$maxperpage}");
        $_count = count($rs);

        if($this->app['fields']){
            $fields = iFormer::fields($this->app['fields']);
        }
        $list_fields = array('title','cid','pubdate');

        include admincp::view('content.manage');
    }
    public function do_save(){
        list($variable,$tables,$orig_post,$imap) = iFormer::post($this->app);

        if(!$variable){
            iCMS::alert("表单数据处理出错!");
        }
        // $fieldArray = iFormer::fields($this->app['fields']);

        // foreach ($imap_array as $key => $value) {
        //     iMap::init($key,$this->app['id']);
        // }

        $update = false;
        foreach ($variable as $table_name => $data) {
            if(empty($data)){
              continue;
            }
            //当表数据
            $table   = $this->app['table'][$table_name];
            //当前表主键
            $primary = $table['primary'];
            //关联字符 && 关联数据
            if($table['union'] && $union_data){
              $data[$table['union']] = $union_data[$table['union']];
            }
            $union_id = apps_mod::data_union_id($table_name);
            $id = $data[$primary];
            unset($data[$primary]);//主键不更新
            if(empty($id)){ //主键值为空
                $id = iDB::insert($table_name,$data);
            }else{
                $update = true;
                iDB::update($table_name, $data, array($primary=>$id));
            }
            empty($table['union']) && $union_data[$union_id] = $id;
        }
var_dump($imap);

        if($imap)foreach ($imap as $key => $value) {
            iMap::init($value[0],$this->app['id']);
            if($update){
                $orig = $orig_post[$key];
                iMap::diff($value[1],$orig,$id);
            }else{
                iMap::add($value[1],$id);
            }
        }
        $REFERER_URL = $_POST['REFERER'];
        if(empty($REFERER_URL)||strstr($REFERER_URL, '=save')){
            $REFERER_URL= APP_URI.'&do=manage';
        }
        if($update){
            iUI::success($this->app['name'].'编辑完成!<br />3秒后返回'.$this->app['name'].'列表','url:'.$REFERER_URL);
        }else{
            iUI::success($this->app['name'].'添加完成!<br />3秒后返回'.$this->app['name'].'列表','url:'.$REFERER_URL);
        }
    }

    public function do_del($id = null,$dialog=true){
    	$id===null && $id=$this->id;
		$id OR iUI::alert("请选择要删除的{$this->app['name']}");

        $table_array = apps::get_table($this->app);
        $table       = $table_array['table'];
        $primary     = $table_array['primary'];

		iDB::query("DELETE FROM `{$table}` WHERE `{$primary}` = '$id'");
		$dialog && iUI::success("{$this->app['name']}删除完成",'js:parent.$("#tr'.$id.'").remove();');
    }
    public function do_batch(){
        $idArray = (array)$_POST['id'];
        $idArray OR iUI::alert("请选择要删除的{$this->app['name']}");
        $ids     = implode(',',$idArray);
        $batch   = $_POST['batch'];
    	switch($batch){
    		case 'dels':
				iUI::$break	= false;
	    		foreach($idArray AS $id){
	    			$this->do_del($id,false);
	    		}
	    		iUI::$break	= true;
				iUI::success('全部删除完成!','js:1');
    		break;
		}
	}
    // public static function menu($menu){
    //     $path     = iPHP_APP_DIR.'/apps/etc/content.menu.json.php';
    //     $json     = file_get_contents($path);
    //     $json     = str_replace("<?php defined('iPHP') OR exit('What are you doing?');? >\n", '', $json);
    //     $variable = array();
    //     $array    = apps::get_array(array("apptype"=>'2'));
    //     if($array)foreach ($array as $key => $value) {
    //         if($value['config']['menu']){
    //             $sort = 200000+$key;

    //             $json = str_replace(
    //                 array('{appid}','{app}','{name}','{sort}'),
    //                 array($value['id'],$value['app'],$value['name'],$sort), $json);

    //             if($value['config']['menu']!='main'){
    //                 $json = '[{"id": "'.$value['config']['menu'].'","children":[{"caption": "-"},'.$json.']}]';
    //             }else{
    //                 $json = '['.$json.']';
    //             }

    //             $array  = json_decode($json,ture);
    //             if($array){
    //                 $array = $menu::mid($array,$sort);
    //                 $variable[] = $array;
    //             }
    //         }
    //     }
    //     return $variable;
    // }
}
