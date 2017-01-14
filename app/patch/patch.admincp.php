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

class patchAdmincp{

	public function __construct() {
		$this->msg		= "";
		$this->patch	= patch::init(isset($_GET['force'])?true:false);
	}
    public function do_check(){
		if(empty($this->patch)){
			if($_GET['ajax']){
				iUI::json(array('code'=>0));
			}else{
				iUI::success("您使用的 iCMS 版本,目前是最新版本<hr />当前版本：iCMS ".iCMS_VERSION." [".iCMS_RELEASE."]",0,"5");
			}
		}else{
	    	switch(iCMS::$config['system']['patch']){
	    		case "1"://自动下载,安装时询问
					$this->msg = patch::download($this->patch[1]);
					$json      = array(
						'code' => "1",
						'url'  => __ADMINCP__.'=patch&do=install',
						'msg'  => "发现iCMS最新版本<br /><span class='label label-warning'>iCMS ".$this->patch[0]." [".$this->patch[1]."]</span><br />".$this->patch[3]."<hr />您当前使用的版本<br /><span class='label label-info'>iCMS ".iCMS_VERSION." [".iCMS_RELEASE."]</span><br /><br />新版本已经下载完成!! 是否现在更新?",
		    		);
	    		break;
	    		case "2"://不自动下载更新,有更新时提示
		    		$json	= array(
						'code' => "2",
						'url'  => __ADMINCP__.'=patch&do=update',
						'msg'  => "发现iCMS最新版本<br /><span class='label label-warning'>iCMS ".$this->patch[0]." [".$this->patch[1]."]</span><br />".$this->patch[3]."<hr />您当前使用的版本<br /><span class='label label-info'>iCMS ".iCMS_VERSION." [".iCMS_RELEASE."]</span><br /><br />请马上更新您的iCMS!!!",
		    		);
	    		break;
	    	}
	    	if($_GET['ajax']){
	    		iUI::json($json,true);
	    	}
		    $moreBtn=array(
		            array("text"=>"马上更新","url"=>$json['url']),
		            array("text"=>"以后在说","js" =>'return true'),
		    );
    		iUI::dialog('success:#:check:#:'.$json['msg'],0,30,$moreBtn);
		}
    }
    public function do_install(){
		$this->msg.= patch::update();//更新文件
		if(patch::$next){
			$this->msg.= patch::run();//数据库升级
		}
		include admincp::view("patch");
    }
    public function do_update(){
		$this->msg	= patch::download();//下载文件包
		include admincp::view("patch");
    }
}
