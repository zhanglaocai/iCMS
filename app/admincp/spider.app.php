<?php

/**
 * iCMS - i Content Management System
 * Copyright (c) 2007-2012 idreamsoft.com iiimon Inc. All rights reserved.
 *
 * @author coolmoo <idreamsoft@qq.com>
 * @site http://www.idreamsoft.com
 * @licence http://www.idreamsoft.com/license.php
 * @version 6.0.0
 * @$Id: spider.app.php 156 2013-03-22 13:40:07Z coolmoo $
 */
defined('iPHP') OR exit('What are you doing?');

iPHP::import(iPHP_APP_CORE .'/iSpider.Autoload.php');

class spiderApp {

    function __construct() {
        spider::$cid   = $this->cid   = (int) $_GET['cid'];
        spider::$rid   = $this->rid   = (int) $_GET['rid'];
        spider::$pid   = $this->pid   = (int) $_GET['pid'];
        spider::$sid   = $this->sid   = (int) $_GET['sid'];
        spider::$title = $this->title = $_GET['title'];
        spider::$url   = $this->url   = $_GET['url'];
        spider::$work  = $this->work  = false;
        $this->poid    = (int) $_GET['poid'];
    }
    function do_update(){
        if($this->sid){
            $data = iACP::fields($_GET['iDT']);
            $data && iDB::update("spider_url",$data,array('id'=>$this->sid));
        }
        iPHP::success('操作成功!','js:1');
    }
    function do_batch(){
        $idArray = (array)$_POST['id'];
        $idArray OR iPHP::alert("请选择要删除的项目");
        $ids     = implode(',',$idArray);
        $batch   = $_POST['batch'];
    	switch($batch){
    		case 'delurl':
				iDB::query("delete from `#iCMS@__spider_url` where `id` IN($ids);");
    		break;
    		case 'delpost':
				iDB::query("delete from `#iCMS@__spider_post` where `id` IN($ids);");
    		break;
    		case 'delproject':
				iDB::query("delete from `#iCMS@__spider_project` where `id` IN($ids);");
    		break;
    		case 'delrule':
 				iDB::query("delete from `#iCMS@__spider_rule` where `id` IN($ids);");
   			break;
            default:
                if(strpos($batch, '#')!==false){
                    list($table,$_batch) = explode('#',$batch);
                    if(in_array($table, array('url','post','project','rul'))){
                        if(strpos($_batch, ':')!==false){
                            $data = iACP::fields($_batch);
                            foreach($idArray AS $id) {
                                $data && iDB::update("spider_".$table,$data,array('id'=>$id));
                            }
                            iPHP::success('操作成功!','js:1');
                        }
                    }
                }
                iPHP::alert('参数错误!','js:1');
		}
		iPHP::success('全部删除成功!','js:1');
	}
    function do_delspider() {
    	$this->sid OR iPHP::alert("请选择要删除的项目");
        iDB::query("delete from `#iCMS@__spider_url` where `id` = '$this->sid';");
        iPHP::success('删除完成','js:1');
    }
    function do_manage($doType = null) {
        $categoryApp = iACP::app('category',iCMS_APP_ARTICLE);
        $category    = $categoryApp->category;

        $sql = " WHERE 1=1";
        $_GET['keywords'] && $sql.="  AND `title` REGEXP '{$_GET['keywords']}'";
        $doType == "inbox" && $sql.=" AND `publish` ='0'";
        $_GET['pid'] && $sql.=" AND `pid` ='" . (int) $_GET['pid'] . "'";
        $_GET['rid'] && $sql.=" AND `rid` ='" . (int) $_GET['rid'] . "'";
        $_GET['starttime'] && $sql.=" AND `addtime`>=UNIX_TIMESTAMP('".$_GET['starttime']." 00:00:00')";
        $_GET['endtime']   && $sql.=" AND `addtime`<=UNIX_TIMESTAMP('".$_GET['endtime']." 23:59:59')";

        $sql.=$categoryApp->search_sql($this->cid);

        $ruleArray = $this->rule_opt(0, 'array');
        $postArray = $this->post_opt(0, 'array');
        $orderby = $_GET['orderby'] ? $_GET['orderby'] : "id DESC";
        $maxperpage = $_GET['perpage']>0?(int)$_GET['perpage']:20;
        $total = iPHP::total(false, "SELECT count(*) FROM `#iCMS@__spider_url` {$sql}", "G");
        iPHP::pagenav($total, $maxperpage, "个网页");
        $rs = iDB::all("SELECT * FROM `#iCMS@__spider_url` {$sql} order by {$orderby} LIMIT " . iPHP::$offset . " , {$maxperpage}");
        $_count = count($rs);
        include iACP::view("spider.manage");
    }

    function do_inbox() {
        $this->do_manage("inbox");
    }

    function do_testdata() {
        spider::$dataTest = true;
        spiderData::crawl();
    }

    function do_testrule() {
        spider::$ruleTest = true;
        spiderUrls::crawl('WEB@AUTO');
    }

    function do_listpub() {
        $responses = spiderUrls::crawl('WEB@MANUAL');
        extract($responses);
        include iACP::view("spider.lists");
    }

    function do_markurl() {
        $hash  = md5($this->url);
        $title = iS::escapeStr($_GET['title']);
        iDB::insert('spider_url',array(
            'cid'     => $this->cid,
            'rid'     => $this->rid,
            'pid'     => $this->pid,
            'title'   => addslashes($title),
            'url'     => $this->url,
            'hash'    => $hash,
            'status'  => '2',
            'addtime' => time(),
            'publish' => '2',
            'indexid' => '0',
            'pubdate' => '0'
        ));
        iPHP::success("移除成功!", 'js:parent.$("#' . $hash . '").remove();');
    }
    function do_dropurl() {
    	$this->pid OR iPHP::alert("请选择要删除的项目");

    	$type	= $_GET['type'];
    	if($type=="0"){
    		$sql=" AND `publish`='0'";
    	}
        iDB::query("delete from `#iCMS@__spider_url` where `pid` = '$this->pid'{$sql};");
        iPHP::success('数据清除完成');
    }
    function do_start() {
        $a	= spiderUrls::crawl('WEB@AUTO');
        $this->do_mpublish($a);
    }
	function do_mpublish($pubArray=array()){
		iPHP::$break	= false;
		if($_POST['pub']){
			foreach((array)$_POST['pub'] as $i=>$a){
				list($cid,$pid,$rid,$url,$title)= explode('|',$a);
				$pubArray[]= array('sid'=>0,'url'=>$url,'title'=>$title,'cid'=>$cid,'rid'=>$rid,'pid'=>$pid);
			}
		}
		if(empty($pubArray)){
			iPHP::$break = true;
			iPHP::alert('暂无最新内容',0,30);
		}
		$_count	= count($pubArray);
        ob_start();
        ob_end_flush();
        ob_implicit_flush(1);
        foreach((array)$pubArray as $i=>$a){
            spider::$sid   = $a['sid'];
            spider::$cid   = $a['cid'];
            spider::$pid   = $a['pid'];
            spider::$rid   = $a['rid'];
            spider::$url   = $a['url'];
            spider::$title = $a['title'];
            $rs          = $this->multipublish();
            $updateMsg   = $i?true:false;
            $timeout     = ($i++)==$_count?'3':false;
			iPHP::dialog($rs['msg'], 'js:'.$rs['js'],$timeout,0,$updateMsg);
            ob_flush();
            flush();
		}
        iDB::update('spider_project',array('lastupdate'=>time()),array('id'=>$this->pid));
		iPHP::dialog('success:#:check:#:采集完成!',0,3,0,true);
	}
	function multipublish(){
		$a		= array();
		$code	= spider::publish('WEB@AUTO');

        if(is_array($code)){
            $label='<span class="label label-success">发布成功!</span>';
        }else{
            $code=="-1" && $label='<span class="label label-warning">该URL的文章已经发布过!请检查是否重复</span>';
        }
        $a['msg'] = '标题:'.spider::$title.'<br />URL:'.spider::$url.'<br />'.$label.'<hr />';
        $a['js']  = 'parent.$("#' . md5(spider::$url) . '").remove();';
		return $a;
	}

    function do_publish($work = null) {
        return spider::publish($work);
    }

    function spider_url($work = NULL,$pid = NULL,$_rid = NULL,$_urls = NULL,$callback = NULL) {
        return spiderUrls::crawl($work,$pid,$_rid,$_urls,$callback);
    }

    function spider_content() {
		return spiderData::crawl();
    }

    function do_rule() {
        if ($_GET['keywords']) {
            $sql = " WHERE `name` REGEXP '{$_GET['keywords']}'";
        }
        $orderby = $_GET['orderby'] ? $_GET['orderby'] : "id DESC";
        $maxperpage = $_GET['perpage']>0?(int)$_GET['perpage']:20;
        $total = iPHP::total(false, "SELECT count(*) FROM `#iCMS@__spider_rule` {$sql}", "G");
        iPHP::pagenav($total, $maxperpage, "个规则");
        $rs = iDB::all("SELECT * FROM `#iCMS@__spider_rule` {$sql} order by {$orderby} LIMIT " . iPHP::$offset . " , {$maxperpage}");
        $_count = count($rs);
        include iACP::view("spider.rule");
    }
    function do_exportrule(){
        $rs   = iDB::row("select `name`, `rule` from `#iCMS@__spider_rule` where id = '$this->rid'");
        $data = array('name'=>addslashes($rs->name),'rule'=>addslashes($rs->rule));
        $data = base64_encode(serialize($data));
        Header("Content-type: application/octet-stream");
        Header("Content-Disposition: attachment; filename=spider.rule.".$rs->name.'.txt');
        echo $data;
    }
    function do_import_rule(){
        iFS::$checkFileData           = false;
        iFS::$config['allow_ext']     = 'txt';
        iFS::$config['yun']['enable'] = false;
        $F    = iFS::upload('upfile');
        $path = $F['RootPath'];
        if($path){
            $data = file_get_contents($path);
            if($data){
                $data = base64_decode($data);
                $data = unserialize($data);
                iDB::insert("spider_rule",$data);
            }
            @unlink($path);
            iPHP::success('规则导入完成','js:1');
        }
    }
    function do_copyrule() {
        iDB::query("insert into `#iCMS@__spider_rule` (`name`, `rule`) select `name`, `rule` from `#iCMS@__spider_rule` where id = '$this->rid'");
        $rid = iDB::$insert_id;
        iPHP::success('复制完成,编辑此规则', 'url:' . APP_URI . '&do=addrule&rid=' . $rid);
    }

    function do_delrule() {
    	$this->rid OR iPHP::alert("请选择要删除的项目");
        iDB::query("delete from `#iCMS@__spider_rule` where `id` = '$this->rid';");
        iPHP::success('删除完成','js:1');
    }

    function do_addrule() {
        $rs = array();
        $this->rid && $rs = spider::rule($this->rid);
        $rs['rule'] && $rule = $rs['rule'];
        if (empty($rule['data'])) {
            $rule['data'] = array(
                array('name' => 'title', 'trim' => true, 'empty' => true),
                array('name' => 'body', 'trim' => true, 'empty' => true, 'format' => true, 'page' => true, 'multi' => true),
            );
        }
        $rule['sort'] OR $rule['sort'] = 1;
        $rule['mode'] OR $rule['mode'] = 1;
        $rule['page_no_start'] OR $rule['page_no_start'] = 1;
        $rule['page_no_end'] OR $rule['page_no_end'] = 5;
        $rule['page_no_step'] OR $rule['page_no_step'] = 1;

        include iACP::view("spider.addrule");
    }

    function do_saverule() {
        $id = (int) $_POST['id'];
        $name = iS::escapeStr($_POST['name']);
        $rule = $_POST['rule'];

        empty($name) && iPHP::alert('规则名称不能为空！');
        //empty($rule['list_area_rule']) 	&& iPHP::alert('列表区域规则不能为空！');
        if($rule['mode']!='2'){
            empty($rule['list_url_rule']) && iPHP::alert('列表链接规则不能为空！');
        }

        $rule   = addslashes(serialize($rule));
        $fields = array('name', 'rule');
        $data   = compact ($fields);
        if ($id) {
            iDB::update('spider_rule', $data, array('id'=>$id));
            iPHP::success('保存成功');
        } else {
            iDB::insert('spider_rule',$data);
            iPHP::success('保存成功!','url:'.APP_URI."&do=addrule&rid=".$id);
        }
    }

    function rule_opt($id = 0, $output = null) {
        $rs = iDB::all("SELECT * FROM `#iCMS@__spider_rule` order by id desc");
        foreach ((array)$rs AS $rule) {
            $rArray[$rule['id']] = $rule['name'];
            $opt.="<option value='{$rule['id']}'" . ($id == $rule['id'] ? " selected='selected'" : '') . ">{$rule['name']}[id='{$rule['id']}'] </option>";
        }
        if ($output == 'array') {
            return $rArray;
        }
        return $opt;
    }

    function do_post() {
        if ($_GET['keywords']) {
            $sql = " WHERE CONCAT(name,app,post) REGEXP '{$_GET['keywords']}'";
        }
        $orderby = $_GET['orderby'] ? $_GET['orderby'] : "id DESC";
        $maxperpage = $_GET['perpage']>0?(int)$_GET['perpage']:20;
        $total = iPHP::total(false, "SELECT count(*) FROM `#iCMS@__spider_post` {$sql}", "G");
        iPHP::pagenav($total, $maxperpage, "个模块");
        $rs = iDB::all("SELECT * FROM `#iCMS@__spider_post` {$sql} order by {$orderby} LIMIT " . iPHP::$offset . " , {$maxperpage}");
        $_count = count($rs);
        include iACP::view("spider.post");
    }
    function do_delpost() {
    	$this->poid OR iPHP::alert("请选择要删除的项目");
        iDB::query("delete from `#iCMS@__spider_post` where `id` = '$this->poid';");
        iPHP::success('删除完成','js:1');
    }
    function do_addpost() {
        $this->poid && $rs = iDB::row("SELECT * FROM `#iCMS@__spider_post` WHERE `id`='$this->poid' LIMIT 1;", ARRAY_A);
        include iACP::view("spider.addpost");
    }

    function do_savepost() {
        $id     = (int) $_POST['id'];
        $name   = trim($_POST['name']);
        $app    = iS::escapeStr($_POST['app']);
        $post   = trim($_POST['post']);
        $fun    = trim($_POST['fun']);

        $fields = array('name','app','fun', 'post');
        $data   = compact ($fields);
        if ($id) {
            iDB::update('spider_post', $data, array('id'=>$id));
        } else {
            iDB::insert('spider_post',$data);
        }
        iPHP::success('保存成功', 'url:' . APP_URI . '&do=post');
    }

    function post_opt($id = 0, $output = null) {
        $rs = iDB::all("SELECT * FROM `#iCMS@__spider_post`");
        foreach ((array)$rs AS $post) {
        	$pArray[$post['id']] = $post['name'];
            $opt.="<option value='{$post['id']}'" . ($id == $post['id'] ? " selected='selected'" : '') . ">{$post['name']}:{$post['app']}[id='{$post['id']}'] </option>";
        }
        if ($output == 'array') {
            return $pArray;
        }
        return $opt;
    }

    function do_copyproject() {
        iDB::query("INSERT INTO `#iCMS@__spider_project` (`name`, `urls`, `cid`, `rid`, `poid`, `sleep`,`checker`,`self`,`auto`, `psleep`) select `name`, `urls`, `cid`, `rid`, `poid`, `sleep`,`checker`,`self`,`auto`,`psleep` from `#iCMS@__spider_project` where id = '$this->pid'");
        $pid = iDB::$insert_id;
        iPHP::success('复制完成,编辑此方案', 'url:' . APP_URI . '&do=addproject&pid=' . $pid.'&copy=1');
    }

    function do_project() {
        $categoryApp = iACP::app('category',iCMS_APP_ARTICLE);
        $category    = $categoryApp->category;

        $sql = "where 1=1";
        if ($_GET['keywords']) {
            $sql.= " and `name` REGEXP '{$_GET['keywords']}'";
        }
        $sql.= $categoryApp->search_sql($this->cid);

        if ($_GET['rid']) {
            $sql.=" AND `rid` ='" . (int) $_GET['rid'] . "'";
        }
        if ($_GET['auto']) {
            $sql.=" AND `auto` ='1'";
        }
        if ($_GET['poid']) {
            $sql.=" AND `poid` ='" . (int) $_GET['poid'] . "'";
        }
        $ruleArray = $this->rule_opt(0, 'array');
        $postArray = $this->post_opt(0, 'array');
        $orderby = $_GET['orderby'] ? $_GET['orderby'] : "id DESC";
        $maxperpage = $_GET['perpage']>0?(int)$_GET['perpage']:20;
        $total = iPHP::total(false, "SELECT count(*) FROM `#iCMS@__spider_project` {$sql}", "G");
        iPHP::pagenav($total, $maxperpage, "个方案");
        $rs = iDB::all("SELECT * FROM `#iCMS@__spider_project` {$sql} order by {$orderby} LIMIT " . iPHP::$offset . " , {$maxperpage}");
        $_count = count($rs);
        include iACP::view("spider.project");
    }
    function do_delproject() {
    	$this->pid OR iPHP::alert("请选择要删除的项目");
        iDB::query("delete from `#iCMS@__spider_project` where `id` = '$this->pid';");
        iPHP::success('删除完成');
    }
    function do_addproject() {
        $rs = array();
        $this->pid && $rs = spider::project($this->pid);
        $cid = empty($rs['cid']) ? $this->cid : $rs['cid'];

        $categoryApp = iACP::app('category',iCMS_APP_ARTICLE);

        $cata_option = $categoryApp->select(false,$cid);
        $rule_option = $this->rule_opt($rs['rid']);
        $post_option = $this->post_opt($rs['poid']);

        //$rs['sleep'] OR $rs['sleep'] = 30;
        include iACP::view("spider.addproject");
    }

    function do_saveproject() {
        $id         = (int) $_POST['id'];
        $name       = iS::escapeStr($_POST['name']);
        $urls       = iS::escapeStr($_POST['urls']);
        $list_url   = $_POST['list_url'];
        $cid        = iS::escapeStr($_POST['cid']);
        $rid        = iS::escapeStr($_POST['rid']);
        $poid       = iS::escapeStr($_POST['poid']);
        $poid       = iS::escapeStr($_POST['poid']);
        $checker    = iS::escapeStr($_POST['checker']);
        $self       = isset($_POST['self'])?'1':'0';
        $sleep      = (int) $_POST['sleep'];
        $auto       = iS::escapeStr($_POST['auto']);
        $psleep     = (int) $_POST['psleep'];
        $lastupdate = $_POST['lastupdate']?iPHP::str2time($_POST['lastupdate']):'';
        empty($name)&& iPHP::alert('名称不能为空！');
        empty($cid) && iPHP::alert('请选择绑定的栏目');
        empty($rid) && iPHP::alert('请选择采集规则');
        //empty($poid)	&& iPHP::alert('请选择发布规则');
        $fields = array('name', 'urls','list_url', 'cid', 'rid', 'poid','checker','self','sleep','auto','lastupdate','psleep');
        $data   = compact ($fields);
        if ($id) {
            iDB::update('spider_project',$data,array('id'=>$id));
        } else {
            iDB::insert('spider_project',$data);
        }
        iPHP::success('完成', 'url:' . APP_URI . '&do=project');
    }
    function do_proxy_test(){
       $a = spiderTools::proxy_test();
       var_dump($a);
    }

}


