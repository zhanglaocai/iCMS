<?php
/**
* iCMS - i Content Management System
* Copyright (c) 2007-2012 idreamsoft.com iiimon Inc. All rights reserved.
*
* @author coolmoo <idreamsoft@qq.com>
* @site http://www.idreamsoft.com
* @licence http://www.idreamsoft.com/license.php
* @version 6.0.0
* @$Id: marker.app.php 2369 2014-03-13 16:16:29Z coolmoo $
*/
class markerAdmincp{
    function __construct() {
        $this->categoryApp = iPHP::app('category.admincp','all');
        $this->id         = (int)$_GET['id'];
    }
    function do_add(){
        $this->id && $rs = iDB::row("SELECT * FROM `#iCMS@__marker` WHERE `id`='$this->id' LIMIT 1;",ARRAY_A);
        if($_GET['act']=="copy"){
            $this->id   = 0;
            $rs['name'] = '';
            $rs['key']  = '';
        }
        if(empty($rs)){
            $rs['status'] = '1';
        }
        include admincp::view("marker.add");
    }
    function do_save(){
        $id     = (int)$_POST['id'];
        $cid    = (int)$_POST['cid'];
        $pid    = (int)$_POST['pid'];

        $name   = iS::escapeStr($_POST['name']);
        $key    = iS::escapeStr($_POST['key']);
        $data   = $_POST['data'];
        $status = (int)$_POST['status'];

        $name OR iPHP::alert('标记名称不能为空!');
        // $key OR iPHP::alert('标记key值不能为空!');
        $key OR $key = pinyin($name);

        $fields = array('cid','pid','name','key','data','status');
        $data   = compact ($fields);

		if($id){
            iDB::update('marker', $data, array('id'=>$id));
			$msg="标记更新完成!";
		}else{
	        iDB::value("SELECT `id` FROM `#iCMS@__marker` where `key` ='$key'") && iPHP::alert('该标记已经存在!请另选一个');
            $id = iDB::insert('marker',$data);
	        $msg="新标记添加完成!";
		}
		$this->cache($id);
        iPHP::success($msg,'url:'.APP_URI);
    }
    function do_update(){
        if($this->id){
            $data = admincp::fields($_GET['iDT']);
            $data && iDB::update("marker",$data,array('id'=>$this->id));
            $this->cache($this->id);
            iPHP::success('操作成功!','js:1');
        }
    }
    function do_del($id = null,$dialog=true){
    	$id===null && $id=$this->id;
    	$id OR iPHP::alert('请选择要删除的标记!');
		iDB::query("DELETE FROM `#iCMS@__marker` WHERE `id` = '$id';");
    	$this->cache($id);
    	$dialog && iPHP::success("已经删除!",'url:'.APP_URI);
    }
    function do_batch(){
        $idArray = (array)$_POST['id'];
        $idArray OR iPHP::alert("请选择要操作的标记");
        $ids     = implode(',',$idArray);
        $batch   = $_POST['batch'];
    	switch($batch){
    		case 'dels':
				iPHP::$break	= false;
	    		foreach($idArray AS $id){
	    			$this->do_del($id,false);
	    		}
	    		iPHP::$break	= true;
				iPHP::success('标记全部删除完成!','js:1');
    		break;
    		case 'refresh':
                foreach($idArray AS $id){
    			 $this->cache($id);
                }
    			iPHP::success('标记缓存全部更新完成!','js:1');
    		break;
		}
	}

    function do_iCMS(){
        $sql			= " where 1=1";
        $_GET['pid'] && $sql.=" AND `pid`='".$_GET['pid']."'";
        $_GET['pid'] && $uri.='&pid='.$_GET['pid'];

        $_GET['cid']  && $sql.=" AND `cid`='".$_GET['cid']."'";
        $_GET['cid']  && $uri.='&cid='.$_GET['cid'];

        $maxperpage = $_GET['perpage']>0?(int)$_GET['perpage']:20;
        $total		= iPHP::total(false,"SELECT count(*) FROM `#iCMS@__marker` {$sql}","G");
        iPHP::pagenav($total,$maxperpage,"个标记");
        $rs     = iDB::all("SELECT * FROM `#iCMS@__marker` {$sql} order by id DESC LIMIT ".iPHP::$offset." , {$maxperpage}");
        $_count = count($rs);
    	include admincp::view("marker.manage");
    }
    function do_cache(){
        $this->cache($this->id);
        iPHP::success('缓存更新完成!','js:1');
    }
    function cache($id=null){
        $id && $sql = " AND `id`='$id'";
    	$rs	= iDB::all("SELECT * FROM `#iCMS@__marker` WHERE `status`='1' {$sql} ");
    	foreach((array)$rs AS $row) {
            iCache::set('marker/'.$row['key'],$row['data'],0);
    	}
    }

}
