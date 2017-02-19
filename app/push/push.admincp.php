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
class pushAdmincp{
    public static $appid = null;
    public $callback = array();
    public function __construct() {
        $this->appid       = iCMS_APP_PUSH;
        $this->id          = (int)$_GET['id'];
        category::$appid = $this->appid;
    }
    public function do_add(){
        $id = (int)$_GET['id'];
        $rs = array();
        $_GET['title'] 	&& $rs['title']	= $_GET['title'];
        $_GET['pic'] 	&& $rs['pic']	= $_GET['pic'];
        $_GET['url'] 	&& $rs['url']	= $_GET['url'];

        $_GET['title2']	&& $rs['title2']= $_GET['title2'];
        $_GET['pic2'] 	&& $rs['pic2']	= $_GET['pic2'];
        $_GET['url2'] 	&& $rs['url2']	= $_GET['url2'];

        $_GET['title3']	&& $rs['title3']= $_GET['title3'];
        $_GET['pic3'] 	&& $rs['pic3']	= $_GET['pic3'];
        $_GET['url3'] 	&& $rs['url3']	= $_GET['url3'];

        $id && $rs	= iDB::row("SELECT * FROM `#iCMS@__push` WHERE `id`='$id' LIMIT 1;",ARRAY_A);
        empty($rs['editor']) && $rs['editor']=empty(members::$data->nickname)?members::$data->username:members::$data->nickname;
        empty($rs['userid']) && $rs['userid']=members::$userid;
        $rs['addtime']	= $id?get_date(0,"Y-m-d H:i:s"):get_date($rs['addtime'],'Y-m-d H:i:s');
        $cid			= empty($rs['cid'])?(int)$_GET['cid']:$rs['cid'];
        $cata_option	= category::select('ca',$cid);

        empty($rs['userid']) && $rs['userid']=members::$userid;
        $strpos 	= strpos(iPHP_REFERER,'?');
        $REFERER 	= $strpos===false?'':substr(iPHP_REFERER,$strpos);

        apps::former_create($this->appid,$rs);

    	include admincp::view("push.add");
    }

    public function do_iCMS(){
    	admincp::$APP_METHOD="domanage";
    	$this->do_manage();
    }
    public function do_manage($doType=null) {
        $cid        = (int)$_GET['cid'];
        $sql        = " where ";
        switch($doType){ //status:[0:草稿][1:正常][2:回收][3:审核][4:不合格]
        	case 'inbox'://草稿
        		$sql.="`status` ='0'";
        		// if(members::$data->gid!=1){
        		// 	$sql.=" AND `userid`='".members::$userid."'";
        		// }
        		$position="草稿";
        	break;
         	case 'trash'://回收站
        		$sql.="`status` ='2'";
        		$position="回收站";
        	break;
         	case 'examine'://审核
        		$sql.="`status` ='3'";
        		$position="已审核";
        	break;
         	case 'off'://未通过
        		$sql.="`status` ='4'";
        		$position="未通过";
        	break;
       		default:
	       		$sql.=" `status` ='1'";
		       	$cid && $position=category::get($cid)->name;
		}

        if($_GET['keywords']) {
			$sql.=" AND CONCAT(title,title2,title3) REGEXP '{$_GET['keywords']}'";
        }

        $sql.=category::search_sql($cid);

        isset($_GET['nopic'])&& $sql.=" AND `haspic` ='0'";
        $_GET['starttime']   && $sql.=" and `addtime`>=UNIX_TIMESTAMP('".$_GET['starttime']." 00:00:00')";
        $_GET['endtime']     && $sql.=" and `addtime`<=UNIX_TIMESTAMP('".$_GET['endtime']." 23:59:59')";


        isset($_GET['userid']) && $uri.='&userid='.(int)$_GET['userid'];
        isset($_GET['keyword'])&& $uri.='&keyword='.$_GET['keyword'];
        isset($_GET['pid'])    && $uri.='&pid='.$_GET['pid'];
        isset($_GET['cid'])    && $uri.='&cid='.$_GET['cid'];
        (isset($_GET['pid']) && $_GET['pid']!='-1') && $uri.='&pid='.$_GET['at'];

        $orderby    =$_GET['orderby']?$_GET['orderby']:"id DESC";
        $maxperpage = $_GET['perpage']>0?(int)$_GET['perpage']:20;
        $total      = iCMS::page_total_cache("SELECT count(*) FROM `#iCMS@__push` {$sql}","G");
        iUI::pagenav($total,$maxperpage,"条记录");
        $rs     = iDB::all("SELECT * FROM `#iCMS@__push` {$sql} order by {$orderby} LIMIT ".iUI::$offset." , {$maxperpage}");
        $_count = count($rs);
        include admincp::view("push.manage");
    }
    public function do_save(){
        $id       = (int)$_POST['id'];
        $cid      = (int)$_POST['cid'];
        $_cid     = (int)$_POST['_cid'];
        $userid   = (int)$_POST['userid'];
        $pid      = (int)$_POST['pid'];
        $editor   = iSecurity::escapeStr($_POST['editor']);
        $sortnum = (int)$_POST['sortnum'];
        $addtime  = str2time($_POST['addtime']);



        $title		= iSecurity::escapeStr($_POST['title']);
        $pic		= $this->getpic($_POST['pic']);
        $description= iSecurity::escapeStr($_POST['description']);
        $url		= iSecurity::escapeStr($_POST['url']);

        $title2		= iSecurity::escapeStr($_POST['title2']);
        $pic2		= $this->getpic($_POST['pic2']);
        $description2= iSecurity::escapeStr($_POST['description2']);
        $url2		= iSecurity::escapeStr($_POST['url2']);

        $title3		= iSecurity::escapeStr($_POST['title3']);
        $pic3		= $this->getpic($_POST['pic3']);
        $description3= iSecurity::escapeStr($_POST['description3']);
        $url3		= iSecurity::escapeStr($_POST['url3']);

        $metadata	= iSecurity::escapeStr($_POST['metadata']);
        $metadata	= $metadata?addslashes(serialize($metadata)):'';

		empty($userid) && $userid=members::$userid;
        empty($title) && iUI::alert('1.标题必填');
        empty($cid) && iUI::alert('请选择所属栏目');

        $haspic	= empty($pic)?0:1;

        $status	= 1;
        $fields = array('cid', 'rootid', 'pid', 'haspic', 'editor', 'userid', 'title', 'pic', 'url', 'description', 'title2', 'pic2', 'url2', 'description2', 'title3', 'pic3', 'url3', 'description3', 'sortnum', 'metadata', 'addtime','hits', 'status');
        $data   = compact ($fields);

        apps::former_data($this->appid,$data,'push');

        if(empty($id)) {
            iDB::insert('push',$data);
            iDB::query("UPDATE `#iCMS@__category` SET `count` = count+1 WHERE `cid` ='$cid' LIMIT 1 ");
            $msg = '推送完成';
        }else{
			iDB::update('push', $data, array('id'=>$id));
            if($_cid!=$cid) {
                iDB::query("UPDATE `#iCMS@__category` SET `count` = count-1 WHERE `cid` ='{$_cid}' and `count`>0 LIMIT 1 ");
                iDB::query("UPDATE `#iCMS@__category` SET `count` = count+1 WHERE `cid` ='$cid' LIMIT 1 ");
            }
            $msg = '编辑完成!';
        }
        admincp::callback($id,$this);
        if($this->callback['code']){
            return array(
                "code"    => $this->callback['code'],
                'indexid' => $id
            );
        }
        iUI::success($msg,'url:'.APP_URI);
    }
    public function __callback($id){
        if ($this->callback['primary']) {
            $PCB = $this->callback['primary'];
            $handler = $PCB[0];
            $params  = (array)$PCB[1]+array('indexid'=>$id);
            if (is_callable($handler)){
                call_user_func_array($handler,$params);
            }
        }
        if ($this->callback['data']) {
            $DCB     = $this->callback['data'];
            $handler = $DCB[0];
            $params  = (array)$DCB[1];
            if (is_callable($handler)){
                call_user_func_array($handler,$params);
            }
        }
    }
	public function getpic($path){
		$uri 	= parse_url(iCMS_FS_URL);
        $pic	= iSecurity::escapeStr($path);

	    if(stripos($pic,$uri['host'])===false){
            stripos($pic, 'http://')===false OR $pic = iFS::http($pic);
	    }else{
            $pic = iFS::fp($pic,"-http");
		}
		return $pic;
	}
    public function do_del($id = null,$dialog=true){
    	$id===null && $id=$this->id;
		$id OR iUI::alert('请选择要删除的推送');
		iDB::query("DELETE FROM `#iCMS@__push` WHERE `id` = '$id'");
		$dialog && iUI::success('推送删除完成','js:parent.$("#tr'.$id.'").remove();');
    }
    public function do_batch(){
        $idArray = (array)$_POST['id'];
        $idArray OR iUI::alert("请选择要删除的推送");
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
}
