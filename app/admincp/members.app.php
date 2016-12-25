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
// class membersApp extends admincp{
class membersApp{
    public function __construct() {
        $this->uid      = (int)$_GET['id'];
        $this->groupApp = admincp::app('group',1);
    }

    public function do_job(){
		require_once iPHP_APP_CORE.'/iJob.class.php';
		$job	= new JOB;
        $this->uid OR $this->uid = iMember::$userid;
		$job->count_post($this->uid);
        $month  = $job->month();
        $pmonth = $job->month($job->pmonth['start']);
        $rs     = iDB::row("SELECT * FROM `#iCMS@__members` WHERE `uid`='$this->uid' LIMIT 1;");
		include admincp::view("members.job");
    }
    public function do_edit(){
        $this->uid = iMember::$userid;
        $this->do_add();
    }
    public function do_add(){
        if($this->uid) {
            $rs = iDB::row("SELECT * FROM `#iCMS@__members` WHERE `uid`='$this->uid' LIMIT 1;");
            $rs->info && $rs->info = unserialize($rs->info);
            $rs->info = (array)$rs->info;
        }
        include admincp::view("members.add");
    }
    public function do_iCMS(){
    	if($_GET['job']){
    		require_once iPHP_APP_CORE.'/iJob.class.php';
    		$job	=new JOB;
    	}
    	$sql	= "WHERE 1=1";
    	//isset($this->type)	&& $sql.=" AND `type`='$this->type'";
		$_GET['gid'] && $sql.=" AND `gid`='{$_GET['gid']}'";
        $orderby    = $_GET['orderby']?$_GET['orderby']:"uid DESC";
        $maxperpage = $_GET['perpage']>0?(int)$_GET['perpage']:20;
        $total      = iPHP::total(false,"SELECT count(*) FROM `#iCMS@__members` {$sql}","G");
        iUI::pagenav($total,$maxperpage,"个用户");
        $rs         = iDB::all("SELECT * FROM `#iCMS@__members` {$sql} order by {$orderby} LIMIT ".iUI::$offset." , {$maxperpage}");
        $_count		= count($rs);
    	include admincp::view("members.manage");
    }
    public function do_save(){
        $uid      = (int)$_POST['uid'];
        $gender   = (int)$_POST['gender'];
        $type     = $_POST['type'];
        $username = iSecurity::escapeStr($_POST['uname']);
        $nickname = iSecurity::escapeStr($_POST['nickname']);
        $realname = iSecurity::escapeStr($_POST['realname']);
        $power    = $_POST['power']?json_encode($_POST['power']):'';
        $cpower   = $_POST['cpower']?json_encode($_POST['cpower']):'';
        $gid      = 0;
        $info     = array();
        $info['icq']       = iSecurity::escapeStr($_POST['icq']);
        $info['home']      = iSecurity::escapeStr($_POST['home']);
        $info['year']      = intval($_POST['year']);
        $info['month']     = intval($_POST['month']);
        $info['day']       = intval($_POST['day']);
        $info['from']      = iSecurity::escapeStr($_POST['from']);
        $info['signature'] = iSecurity::escapeStr($_POST['signature']);
        $info              = addslashes(serialize($info));
        $_POST['pwd'] && $password = md5($_POST['pwd']);

        $username OR iUI::alert('账号不能为空');

        if(admincp::is_superadmin()){
            $gid = (int)$_POST['gid'];
        }else{
            isset($_POST['gid']) && iUI::alert('您没有权限更改角色');
        }

        $fields = array('gid','gender','username','nickname','realname','power', 'cpower','info');
        $data   = compact ($fields);
        if(empty($uid)) {
            iDB::value("SELECT `uid` FROM `#iCMS@__members` where `username` ='$username' LIMIT 1") && iUI::alert('该账号已经存在');
            $_data = compact(array('password','regtime', 'lastip', 'lastlogintime', 'logintimes', 'post', 'type', 'status'));
            $_data['regtime']       = time();
            $_data['lastip']        = iPHP::get_ip();
            $_data['lastlogintime'] = time();
            $_data['status']        = '1';
            $data = array_merge($data, $_data);
            iDB::insert('members',$data);
            $msg="账号添加完成!";
        }else {
            iDB::value("SELECT `uid` FROM `#iCMS@__members` where `username` ='$username' AND `uid` !='$uid' LIMIT 1") && iUI::alert('该账号已经存在');
            iDB::update('members', $data, array('uid'=>$uid));
            $password && iDB::query("UPDATE `#iCMS@__members` SET `password`='$password' WHERE `uid` ='".$uid."'");
            $msg="账号修改完成!";
        }
        iUI::success($msg,'url:'.APP_URI);
    }
    public function do_batch(){
    	$idA	= (array)$_POST['id'];
    	$idA OR iUI::alert("请选择要操作的用户");
    	$ids	= implode(',',(array)$_POST['id']);
    	$batch	= $_POST['batch'];
    	switch($batch){
    		case 'dels':
                iUI::$break = false;
	    		foreach($idA AS $id){
	    			$this->do_del($id,false);
	    		}
                iUI::$break = true;
				iUI::success('用户全部删除完成!','js:1');
    		break;
		}
	}
    public function do_del($uid = null,$dialog=true){
    	$uid===null && $uid=$this->uid;
		$uid OR iUI::alert('请选择要删除的用户');
		$uid=="1" && iUI::alert('不能删除超级管理员');
		iDB::query("DELETE FROM `#iCMS@__members` WHERE `uid` = '$uid'");
		$dialog && iUI::success('用户删除完成','js:parent.$("#tr'.$uid.'").remove();');
    }
}
