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

iPHP::import(iPHP_APP_CORE .'/iSpider.class.php');

class spiderApp {

    function __construct() {
        $this->cid   = (int) $_GET['cid'];
        $this->rid   = (int) $_GET['rid'];
        $this->pid   = (int) $_GET['pid'];
        $this->sid   = (int) $_GET['sid'];
        $this->poid  = (int) $_GET['poid'];
        $this->title = $_GET['title'];
        $this->url   = $_GET['url'];
        $this->work  = false;
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

    function do_testcont() {
        spider::$contTest = $this->contTest = true;
        $this->spider_content();
    }

    function do_testrule() {
        spider::$ruleTest = $this->ruleTest = true;
        $this->spider_url('WEB@AUTO');
    }

    function do_listpub() {
        $this->spider_url('WEB@MANUAL');
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
        $a	= $this->spider_url('WEB@AUTO');
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
            $this->sid   = $a['sid'];
            $this->cid   = $a['cid'];
            $this->pid   = $a['pid'];
            $this->rid   = $a['rid'];
            $this->url   = $a['url'];
            $this->title = $a['title'];
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
		$code	= $this->do_publish('WEB@AUTO');

        if(is_array($code)){
            $label='<span class="label label-success">发布成功!</span>';
        }else{
            $code=="-1" && $label='<span class="label label-warning">该URL的文章已经发布过!请检查是否重复</span>';
        }
        $a['msg'] = '标题:'.$this->title.'<br />URL:'.$this->url.'<br />'.$label.'<hr />';
        $a['js']  = 'parent.$("#' . md5($this->url) . '").remove();';
		return $a;
	}
    function checker($work = null){
        $project = spider::project($this->pid);
        $hash    = md5($this->url);
        if(($project['checker'] && empty($_GET['indexid'])) || $work=="DATA@RULE"){
            $title = iS::escapeStr($this->title);
            $url   = iS::escapeStr($this->url);
            $project_checker = $project['checker'];
            $work=="DATA@RULE" && $project_checker = '1';
            switch ($project_checker) {
                case '1'://按网址检查
                    $sql ="`url` = '$url'";
                    $msg ='该网址的文章已经发布过!请检查是否重复';
                break;
                case '2'://按标题检查
                    $sql ="`title` = '$title'";
                    $msg ='该标题的文章已经发布过!请检查是否重复';
                break;
                case '3'://网址和标题
                    $sql ="`url` = '$url' AND `title` = '$title'";
                    $msg ='该网址和标题的文章已经发布过!请检查是否重复';
                break;
            }
            $project['self'] && $sql.=" AND `pid`='$this->pid'";
            $checker = iDB::value("SELECT `id` FROM `#iCMS@__spider_url` where $sql AND `publish` in(1,2)");
            if($checker){
                $work===NULL && iPHP::alert($msg, 'js:parent.$("#' . $hash . '").remove();');
                if($work=='shell'){
                    echo $msg."\n";
                    return false;
                }
                if($work=="WEB@AUTO"){
                    return '-1';
                }
                return false;
            }else{
                return true;
            }
        }
        return true;
    }
    function do_publish($work = null) {
        $_POST = $this->spider_content();
        if($this->work){
           // if(empty($_POST['title'])){
           //     echo "标题不能为空\n";
           //     return false;
           // }
           // if(empty($_POST['body'])){
           //     echo "内容不能为空\n";
           //     return false;
           // }
        }
        $checker = $this->checker($work);
        if($checker!==true){
            return $checker;
        }
        $pid          = $this->pid;
        $project      = spider::project($pid);
        $_POST['cid'] = $project['cid'];
        $postArgs = spider::postArgs($project['poid']);

        if($_GET['indexid']){
            $aid = (int)$_GET['indexid'];
            $_POST['aid']  = $aid;
            $_POST['adid'] = iDB::value("SELECT `id` FROM `#iCMS@__article_data` WHERE aid='$aid'");
        }
        $hash  = md5($this->url);
        $title = iS::escapeStr($_POST['title']);
        $url   = iS::escapeStr($_POST['reurl']);
        if(empty($this->sid)){
            $spider_url = iDB::row("SELECT `id`,`publish`,`indexid` FROM `#iCMS@__spider_url` where `url`='$url'",ARRAY_A);
            if(empty($spider_url)){
                $spider_url_data = array(
                    'cid'     => $project['cid'],
                    'rid'     => $this->rid,
                    'pid'     => $pid,
                    'title'   => addslashes($title),
                    'url'     => $url,
                    'hash'    => $hash,
                    'status'  => '1',
                    'addtime' => time(),
                    'publish' => '0',
                    'indexid' => '0',
                    'pubdate' => ''
                );
                $suid = iDB::insert('spider_url',$spider_url_data);
            }else{
                if($spider_url['indexid']){
                    $_POST['aid']  = $spider_url['indexid'];
                    $_POST['adid'] = iDB::value("SELECT `id` FROM `#iCMS@__article_data` WHERE aid='".$spider_url['indexid']."'");
                }
                $suid = $spider_url['id'];
            }
        }else{
            $suid = $this->sid;
        }

        iS::slashes($_POST);
        $app = iACP::app($postArgs->app);
        $fun = $postArgs->fun;

        $app->callback['primary'] = array(
            array($this,'update_spider_url_indexid'),
            array('suid'=>$suid)
        );//主表 回调 更新关联ID

        $app->callback['data'] = array(
            array($this,'update_spider_url_publish'),
            array('suid'=>$suid)
        );//数据表 回调 成功发布

        $callback = $app->$fun("1001");
        if ($callback['code'] == "1001") {
            if ($this->sid) {
                $work===NULL && iPHP::success("发布成功!",'js:1');
            } else {
		        $work===NULL && iPHP::success("发布成功!", 'js:parent.$("#' . $hash . '").remove();');
            }
        }
        if($work=="shell"||$work=="WEB@AUTO"){
            $callback['work']=$work;
        	return $callback;
        }
    }


    function spider_url($work = NULL,$pid = NULL,$_rid = NULL,$_urls=null,$callback=null) {
        $pid === NULL && $pid = $this->pid;

        if ($pid) {
            $project = spider::project($pid);
            $cid = $project['cid'];
            $rid = $project['rid'];
            $prule_list_url = $project['list_url'];
            $lastupdate     = $project['lastupdate'];
        } else {
            $cid = $this->cid;
            $rid = $this->rid;
        }

        if(empty($rid) && $_rid !== NULL) $rid = $_rid;

        if($work=='shell'){
            $lastupdate = $project['lastupdate'];
            if($project['psleep']){
                if(time()-$lastupdate<$project['psleep']){
                    echo '采集方案['.$pid."]:".format_date($lastupdate)."刚采集过了,请".($project['psleep']/3600)."小时后在继续采集\n";
                    return;
                }
            }
            echo "\033[32m开始采集方案[".$pid."] 采集规则[".$rid."]\033[0m\n";
        }
        $ruleA = spider::rule($rid);
        $rule = $ruleA['rule'];
        $urls = $rule['list_urls'];
        $project['urls'] && $urls = $project['urls'];
        $_urls && $urls = $_urls;

        $urlsArray  = explode("\n", $urls);
        $urlsArray  = array_filter($urlsArray);
        $_urlsArray = $urlsArray;
        $urlsList   = array();
        if($work=='shell'){
            // echo "$urls\n";
            print_r($urlsArray);
        }
        foreach ($_urlsArray AS $_key => $_url) {
            $_url = htmlspecialchars_decode($_url);
            /**
             * RULE@rid@url
             * url使用[rid]规则采集并返回列表结果
             */
            if(strpos($_url, 'RULE@')!==false){
                list($___s,$_rid,$_urls) = explode('@', $_url);
                if ($this->ruleTest) {
                    print_r('<b>使用[rid:'.$_rid.']规则抓取列表</b>:'.$_urls);
                    echo "<hr />";
                }
                $urlsList = $this->spider_url($work,false,$_rid,$_urls,'CALLBACK@URL');
            }else{
                preg_match('|.*<(.*)>.*|is',$_url, $_matches);
                if($_matches){
                    list($format,$begin,$num,$step,$zeroize,$reverse) = explode(',',$_matches[1]);
                    $url = str_replace($_matches[1], '*',trim($_matches[0]));
                    $_urlsList = spiderTools::mkurls($url,$format,$begin,$num,$step,$zeroize,$reverse);
                    unset($urlsArray[$_key]);
                    $urlsList = array_merge($urlsList,$_urlsList);
                }
            }
        }
        $urlsList && $urlsArray = array_merge($urlsArray,$urlsList);
        unset($_urlsArray,$_key,$_url,$_matches,$_urlsList,$urlsList);
        $urlsArray  = array_unique($urlsArray);

        // $this->useragent = $rule['user_agent'];
        // $this->encoding  = $rule['curl']['encoding'];
        // $this->referer   = $rule['curl']['referer'];
        // $this->charset   = $rule['charset'];

        if(empty($urlsArray)){
            if($work=='shell'){
                echo "采集列表为空!请填写!\n";
                return false;
            }
            iPHP::alert('采集列表为空!请填写!', 'js:parent.window.iCMS_MODAL.destroy();');
        }

//    	if($this->ruleTest){
//	    	echo "<pre>";
//	    	print_r(iS::escapeStr($project));
//	    	print_r(iS::escapeStr($rule));
//	    	echo "</pre>";
//	    	echo "<hr />";
//		}
        if($rule['mode']=="2"){
            iPHP::import(iPHP_LIB.'/phpQuery.php');
            $this->ruleTest && $_GET['pq_debug'] && phpQuery::$debug =1;
        }

        $pubArray         = array();
        $pubCount         = array();
        $pubAllCount      = array();
        spider::$curl_proxy = $rule['proxy'];
        $this->urlslast   = null;
        foreach ($urlsArray AS $key => $url) {
            $url = trim($url);
            $this->urlslast = $url;
            if($work=='shell'){
                echo '开始采集列表:'.$url."\n";
            }
            if ($this->ruleTest) {
                echo '<b>抓取列表:</b>'.$url . "<br />";
            }
            $html = spiderTools::remote($url);
            if(empty($html)){
                continue;
            }
            if($rule['mode']=="2"){
                $doc       = phpQuery::newDocumentHTML($html,'UTF-8');
                $list_area = $doc[trim($rule['list_area_rule'])];
                // if(strpos($rule['list_area_format'], 'DOM::')!==false){
                //     $list_area = spiderTools::dataClean($rule['list_area_format'], $list_area);
                // }

                if($rule['list_area_format']){
                    $list_area_format = trim($rule['list_area_format']);
                    if(strpos($list_area_format, 'ARRAY::')!==false){
                        $list_area_format = str_replace('ARRAY::', '', $list_area_format);
                        $lists = array();
                        foreach ($list_area as $la_key => $la) {
                            $lists[] = phpQuery::pq($list_area_format,$la);
                        }
                    }else{
                        $lists = phpQuery::pq($list_area_format,$list_area);
                    }
                }else{
                    $lists = $list_area;
                }

                // $lists = $list_area;
                //echo 'list:getDocumentID:'.$lists->getDocumentID()."\n";
            }else{
                $list_area_rule = spiderTools::pregTag($rule['list_area_rule']);
                if ($list_area_rule) {
                    preg_match('|' . $list_area_rule . '|is', $html, $matches, $PREG_SET_ORDER);
                    $list_area = $matches['content'];
                } else {
                    $list_area = $html;
                }

    			$html = null;
                unset($html);

                if ($this->ruleTest) {
                    echo iS::escapeStr($rule['list_area_rule']);
    //    			echo iS::escapeStr($list_area);
                    echo "<hr />";
                }
                if ($rule['list_area_format']) {
                    $list_area = spiderTools::dataClean($rule['list_area_format'], $list_area);
                }

                preg_match_all('|' . spiderTools::pregTag($rule['list_url_rule']) . '|is', $list_area, $lists, PREG_SET_ORDER);

                $list_area = null;
                unset($list_area);
                if ($rule['sort'] == "1") {
                    //arsort($lists);
                } elseif ($rule['sort'] == "2") {
                    asort($lists);
                } elseif ($rule['sort'] == "3") {
                    shuffle($lists);
                }
            }

            if ($this->ruleTest) {
                echo '<b>列表区域规则:</b>'.iS::escapeStr($rule['list_area_rule']);
                echo "<hr />";
                echo '<b>列表区域抓取结果:</b>'.iS::escapeStr($list_area);
                echo "<hr />";
                echo '<b>列表链接规则:</b>'.iS::escapeStr($rule['list_url_rule']);
                echo "<hr />";
                echo '<b>网址合成规则:</b>'.iS::escapeStr($rule['list_url']);
                echo "<hr />";
            }
			if($prule_list_url){
				$rule['list_url']	= $prule_list_url;
			}

            //PID@xx 返回URL列表
            if($callback=='CALLBACK@URL'){
                $cbListUrl = array();
                foreach ($lists AS $lkey => $row) {
                    list($this->title,$this->url) = spiderTools::title_url($row,$rule,$url);
                    if($this->url===false){
                        continue;
                    }
                    if($this->checker($work)===true){
                        $cbListUrl[] = $this->url;
                    }
                }
                return $cbListUrl;
            }
            if($work=="shell"){
                $pubCount[$url]['count'] = count($lists);
                $pubAllCount['count']+=$pubCount[$url]['count'];
                echo "开始采集:".$url." 列表 ".$pubCount[$url]['count']."条记录\n";
                foreach ($lists AS $lkey => $row) {
                    list($this->title,$this->url) = spiderTools::title_url($row,$rule,$url);
                    if($this->url===false){
                        continue;
                    }
                    $hash  = md5($this->url);
                    echo "title:".$this->title."\n";
                    echo "url:".$this->url."\n";
                    $this->rid = $rid;
                    $checker = $this->checker($work);
                    if($checker===true){
                        echo "开始采集....";
                        $callback  = $this->do_publish("shell");
                        if ($callback['code'] == "1001") {
                            $pubCount[$url]['success']++;
                            $pubAllCount['success']++;
                            echo "....√\n";
                            if($project['sleep']){
                                echo "sleep:".$project['sleep']."s\n";
                                if($rule['mode']!="2"){
                                    unset($lists[$lkey]);
                                }
                                gc_collect_cycles();
                                sleep($project['sleep']);
                            }else{
                                //sleep(1);
                            }
                        }else{
                            $pubCount[$url]['error']++;
                            $pubAllCount['error']++;
                            echo "error\n\n";
                            continue;
                        }
                    }
                    $pubCount[$url]['published']++;
                    $pubAllCount['published']++;
                }
                if($rule['mode']=="2"){
                    phpQuery::unloadDocuments($doc->getDocumentID());
                }else{
                    unset($lists);
                }
            }
            if($work=="WEB@MANUAL"){
                $listsArray[$url] = $lists;
            }
            if($work=="WEB@AUTO"||$work=='DATA@RULE'){
                foreach ($lists AS $lkey => $row) {
                    list($this->title,$this->url) = spiderTools::title_url($row,$rule,$url);
                    if($this->url===false){
                        continue;
                    }
                    $hash  = md5($this->url);
                    if ($this->ruleTest) {
                        echo '<b>列表抓取结果:</b>'.$lkey.'<br />';
                        echo $this->title . ' (<a href="' . APP_URI . '&do=testcont&url=' . urlencode($this->url) . '&rid=' . $rid . '&pid=' . $pid . '&title=' . urlencode($title) . '" target="_blank">测试内容规则</a>) <br />';
                        echo $this->url . "<br />";
                        echo $hash . "<br /><hr />";
                    } else {
                        if($this->checker($work)===true||$this->contTest){
                            $suData = array(
                                'sid'   => 0,
                                'url'   => $this->url,'title' => $this->title,
                                'cid'   => $cid,'rid' => $rid,'pid' => $pid,
                                'hash'  => $hash
                            );
                            switch ($work) {
                                case 'DATA@RULE':
                                    $contentArray[$lkey] = $this->spider_content();
                                    // $contentArray[$lkey] = $this->spider_url($work,$_pid);
                                    unset($suData['sid']);
                                    $suData['title'] = addslashes($suData['title']);
                                    $suData+= array(
                                        'addtime' => time(),
                                        'status'  => '2','publish' => '2',
                                        'indexid' => '0','pubdate' => '0'
                                    );
                                    $this->contTest OR $suid = iDB::insert('spider_url',$suData);
                                    $contentArray[$lkey]['spider_url'] = $suid;
                                break;
                                case 'WEB@AUTO':
                                    $pubArray[] = $suData;
                                break;
                            }
                        }
                    }
                }
            }
        }
        $lists = null;
        unset($lists);
        gc_collect_cycles();

        switch ($work) {
            case 'WEB@AUTO':
                return $pubArray;
            break;
            case 'DATA@RULE':
                return $contentArray;
            break;
            case 'WEB@MANUAL':
                return include iACP::view("spider.lists");
            break;
            case "shell":
                echo "采集数据统结果:\n";
                print_r($pubCount);
                print_r($pubAllCount);
                echo "全部采集完成....\n";
                iDB::update('spider_project',array('lastupdate'=>time()),array('id'=>$pid));
            break;
        }
    }

    function spider_content() {
		ini_get('safe_mode') OR set_time_limit(0);
        $sid = $this->sid;
        if ($sid) {
            $sRs   = iDB::row("SELECT * FROM `#iCMS@__spider_url` WHERE `id`='$sid' LIMIT 1;");
            $title = $sRs->title;
            $cid   = $sRs->cid;
            $pid   = $sRs->pid;
            $url   = $sRs->url;
            $rid   = $sRs->rid;
       } else {
            $rid   = $this->rid;
            $pid   = $this->pid;
            $title = $this->title;
            $url   = $this->url;
        }

		if($pid){
            $project        = spider::project($pid);
            $prule_list_url = $project['list_url'];
		}

        $ruleA           = spider::rule($rid);
        $rule            = $ruleA['rule'];
        $dataArray       = $rule['data'];

		if($prule_list_url){
			$rule['list_url']	= $prule_list_url;
		}

        if ($this->contTest) {
            echo "<b>抓取规则信息</b><pre>";
            print_r(iS::escapeStr($ruleA));
            print_r(iS::escapeStr($project));
            echo "</pre><hr />";
        }

        spider::$curl_proxy = $rule['proxy'];
        $responses = array();
        $html      = spiderTools::remote($url);
        if(empty($html)){
            $msg = '错误:001..采集 ' . $url . '文件内容为空!请检查采集规则';
            if($this->work=='shell'){
                echo "{$msg}\n";
                return false;
            }else{
                iPHP::alert($msg);
            }
        }

//    	$http	= $this->check_content_code($html);
//
//    	if($http['match']==false){
//    		return false;
//    	}
//		$content		= $http['content'];
        $this->allHtml = "";
        $responses['reurl'] = $this->url;
        $rule['__url__']	= $this->url;
        $callBackData       = false;
        foreach ((array)$dataArray AS $key => $data) {
            $content_html = $html;
            $dname = $data['name'];

            /**
             * [DATA:name]
             * 把之前[name]处理完的数据当作原始数据
             * 如果之前有数据会叠加
             * 用于数据多次处理
             * @var string
             */
            if (strpos($dname,'DATA:')!== false){
                $dname = str_replace('DATA:', '', $dname);
                $content_html = $responses[$dname];
                unset($responses[$dname]);
            }
            /**
             * [PRE:name]
             * 把PRE:name采集到的数据 当做原始数据
             * 一般用于下载内容
             * @var string
             */
            $url_dkey = 'PRE:'.$dname;
            if(isset($responses[$url_dkey])){
                $content_html = $responses[$url_dkey];
                unset($responses[$url_dkey]);
            }

            $content = $this->content($content_html,$data,$rule,$responses);

            unset($content_html);
            /**
             * [name.xxx]
             * 采集内容做为数组
             */
            if (strpos($dname,'.')!== false){
                $f_key = substr($dname,0,stripos($dname, "."));
                $s_key = substr(strrchr($dname, "."), 1);
                if(isset($responses[$f_key][$s_key])){
                    if(is_array($responses[$f_key][$s_key])){
                        $responses[$f_key][$s_key] = array_merge($responses[$f_key][$s_key],$content);
                    }else{
                        $responses[$f_key][$s_key].= $content;
                    }
                }else{
                    $responses[$f_key][$s_key] = $content;
                }
            }else{
                /**
                 * 多个name 内容合并
                 */
                if(isset($responses[$dname])){
                    if(is_array($responses[$dname])){
                        $responses[$dname] = array_merge($responses[$dname],$content);
                    }else{
                        $responses[$dname].= $content;
                    }
                }else{
                    $responses[$dname] = $content;
                }
            }
            /**
             * 对匹配多条的数据去重过滤
             */
            if(!is_array($responses[$dname]) && $data['multi']){
                if(strpos($responses[$dname], ',')!==false){
                    $_dnameArray = explode(',', $responses[$dname]);
                    $dnameArray  = array();
                    foreach ((array)$_dnameArray as $key => $value) {
                        $value = trim($value);
                        $value && $dnameArray[]=$value;
                    }
                    $dnameArray = array_filter($dnameArray);
                    $dnameArray = array_unique($dnameArray);
                    $responses[$dname] = implode(',', $dnameArray);
                    unset($dnameArray,$_dnameArray);
                }
            }

            gc_collect_cycles();
        }
        if(empty($responses['title']) && $responses['title']!==false){
            $responses['title'] = $title;
        }

        unset($this->allHtml,$html);

        gc_collect_cycles();

        if ($this->contTest) {
            echo "<pre style='width:99%;word-wrap: break-word;'>";
            print_r(iS::escapeStr($responses));
            echo "</pre><hr />";
        }

        iFS::$CURLOPT_ENCODING        = '';
        iFS::$CURLOPT_REFERER         = '';
        iFS::$watermark_config['pos'] = iCMS::$config['watermark']['pos'];
        iFS::$watermark_config['x']   = iCMS::$config['watermark']['x'];
        iFS::$watermark_config['y']   = iCMS::$config['watermark']['y'];
        iFS::$watermark_config['img'] = iCMS::$config['watermark']['img'];

        $rule['fs']['encoding'] && iFS::$CURLOPT_ENCODING = $rule['fs']['encoding'];
        $rule['fs']['referer']  && iFS::$CURLOPT_REFERER  = $rule['fs']['referer'];
        if($rule['watermark_mode']){
            iFS::$watermark_config['pos'] = $rule['watermark']['pos'];
            iFS::$watermark_config['x']   = $rule['watermark']['x'];
            iFS::$watermark_config['y']   = $rule['watermark']['y'];
            $rule['watermark']['img'] && iFS::$watermark_config['img'] = $rule['watermark']['img'];
        }

        return $responses;
    }
    /**
     * 抓取资源
     * @param  [string] $html      [抓取结果]
     * @param  [array] $data      [数据项]
     * @param  [array] $rule      [规则]
     * @param  [array] $responses [已经抓取资源]
     * @return [array]           [返回处理结果]
     */
    function content($html,$data,$rule,$responses) {
        if(trim($data['rule'])===''){
            return;
        }
        $name = $data['name'];
        if ($this->contTest) {
            print_r('<b>['.$name.']规则:</b>'.iS::escapeStr($data['rule']));
            echo "<hr />";
        }
        if(strpos($data['rule'], 'RULE@')!==false){
            $this->rid  = str_replace('RULE@', '',$data['rule']);
            $_urls = trim($html);
            if ($this->contTest) {
                print_r('<b>使用[rid:'.$this->rid.']规则抓取</b>:'.$_urls);
                echo "<hr />";
            }
            return $this->spider_url('DATA@RULE',false,$this->rid,$_urls);
        }

        if ($data['page']) {
        	if(empty($rule['page_url'])){
        		$rule['page_url'] = $rule['list_url'];
        	}
            if (empty($this->allHtml)) {
                $page_url_array = array();
                $page_area_rule = trim($rule['page_area_rule']);
                if($page_area_rule){
                    if(strpos($page_area_rule, 'DOM::')!==false){
                        iPHP::import(iPHP_LIB.'/phpQuery.php');
                        $doc      = phpQuery::newDocumentHTML($html,'UTF-8');
                        $pq_dom   = str_replace('DOM::','', $page_area_rule);
                        $pq_array = phpQuery::pq($pq_dom);
                        foreach ($pq_array as $pn => $pq_val) {
                            $href = phpQuery::pq($pq_val)->attr('href');
                            if($href){
                                if($rule['page_url_rule']){
                                    if(strpos($rule['page_url_rule'], '<%')!==false){
                                        $page_url_rule = spiderTools::pregTag($rule['page_url_rule']);
                                        if (!preg_match('|' . $page_url_rule . '|is', $href)){
                                            continue;
                                        }
                                    }else{
                                        $cleanhref = spiderTools::dataClean($rule['page_url_rule'],$href);
                                        if($cleanhref){
                                            $href = $cleanhref;
                                            unset($cleanhref);
                                        }else{
                                            continue;
                                        }
                                    }
                                }
                                $href = str_replace('<%url%>',$href, $rule['page_url']);
                                $page_url_array[$pn] = spiderTools::url_complement($rule['__url__'],$href);
                            }
                        }
                        phpQuery::unloadDocuments($doc->getDocumentID());
                    }else{
                        $page_area_rule = spiderTools::pregTag($page_area_rule);
                        if ($page_area_rule) {
                            preg_match('|' . $page_area_rule . '|is', $html, $matches, $PREG_SET_ORDER);
                            $page_area = $matches['content'];
                        } else {
                            $page_area = $html;
                        }
                        if($rule['page_url_rule']){
                            $page_url_rule = spiderTools::pregTag($rule['page_url_rule']);
                            preg_match_all('|' .$page_url_rule. '|is', $page_area, $page_url_matches, PREG_SET_ORDER);
                            foreach ($page_url_matches AS $pn => $row) {
                                $href = str_replace('<%url%>', $row['url'], $rule['page_url']);
                                $page_url_array[$pn] = spiderTools::url_complement($rule['__url__'],$href);
                                gc_collect_cycles();
                            }
                        }
                        unset($page_area);
                    }
                }else{ // 逻辑方式
                    if($rule['page_url_parse']=='<%url%>'){
                        $page_url = str_replace('<%url%>',$rule['__url__'],$rule['page_url']);
                    }else{
                		$page_url_rule = spiderTools::pregTag($rule['page_url_parse']);
    					preg_match('|' . $page_url_rule . '|is', $rule['__url__'], $matches, $PREG_SET_ORDER);
    			        $page_url = str_replace('<%url%>', $matches['url'], $rule['page_url']);
			        }
                    if (stripos($page_url,'<%step%>') !== false){
                        for ($pn = $rule['page_no_start']; $pn <= $rule['page_no_end']; $pn = $pn + $rule['page_no_step']) {
                            $page_url_array[$pn] = str_replace('<%step%>', $pn, $page_url);
                            gc_collect_cycles();
                        }
                    }
            	}
                //URL去重清理
                if($page_url_array){
                    $page_url_array = array_filter($page_url_array);
                    $page_url_array = array_unique($page_url_array);
                    $puk = array_search($rule['__url__'],$page_url_array);
                    if($puk!==false){
                        unset($page_url_array[$puk]);
                    }
                }

		        if ($this->contTest) {
		            echo $rule['__url__'] . "<br />";
		            echo $rule['page_url'] . "<br />";
		            echo iS::escapeStr($page_url_rule);
		            echo "<hr />";
		        }
				if($this->contTest){
					echo "<pre>";
					print_r($page_url_array);
					echo "</pre><hr />";
				}

		        spider::$content_right_code = trim($rule['page_url_right']);
		        spider::$content_error_code = trim($rule['page_url_error']);
                spider::$curl_proxy = $rule['proxy'];

                $pcon     = '';
                $pageurl  = array();
                foreach ($page_url_array AS $pukey => $purl) {
                    //usleep(100);
                    $phtml = spiderTools::remote($purl);
                    if (empty($phtml)) {
                        break;
                    }
                    $md5 = md5($phtml);
                    if($pageurl[$md5]){
                        break;
                    }
                    $phttp = spiderTools::check_content_code($phtml);
                    if ($phttp['match'] === false) {
                        break;
                    }
                    $pageurl[$md5] = $purl;
                    $pcon.= $phttp['content'];
                }
                gc_collect_cycles();
                $html.= $pcon;
                unset($pcon,$phttp);
                $this->allHtml = $html;

                if ($this->contTest) {
                    echo "<pre>";
                    print_r($pageurl);
                    echo "</pre><hr />";
                }
            }else{
                $html = $this->allHtml;
            }
        }
        if($data['dom']){
            iPHP::import(iPHP_LIB.'/phpQuery.php');
            $this->contTest && $_GET['pq_debug'] && phpQuery::$debug =1;
            $doc = phpQuery::newDocumentHTML($html,'UTF-8');
            if(strpos($data['rule'], '@')!==false){
                list($content_dom,$content_attr) = explode("@", $data['rule']);
                $content_fun = 'attr';
            }else{
                list($content_dom,$content_fun,$content_attr) = explode("\n", $data['rule']);
            }
            $content_dom  = trim($content_dom);
            $content_fun  = trim($content_fun);
            $content_attr = trim($content_attr);
            $content_fun OR $content_fun = 'html';
            if ($data['multi']) {
                $conArray = array();
                foreach ($doc[$content_dom] as $doc_key => $doc_value) {
                    if($content_attr){
                        $conArray[] = phpQuery::pq($doc_value)->$content_fun($content_attr);
                    }else{
                        $conArray[] = phpQuery::pq($doc_value)->$content_fun();
                    }
                }
                $content = implode('#--iCMS.PageBreak--#', $conArray);
                unset($conArray);
            }else{
                if($content_attr){
                    $content = $doc[$content_dom]->$content_fun($content_attr);
                }else{
                    $content = $doc[$content_dom]->$content_fun();
                }
            }

            phpQuery::unloadDocuments($doc->getDocumentID());
            unset($doc);
        }else{
            if(trim($data['rule'])=='<%content%>'){
                $content = $html;
            }else{
                $data_rule = spiderTools::pregTag($data['rule']);
                if (preg_match('/(<\w+>|\.\*|\.\+|\\\d|\\\w)/i', $data_rule)) {
                    if ($data['multi']) {
                        preg_match_all('|' . $data_rule . '|is', $html, $matches, PREG_SET_ORDER);
                        $conArray = array();
                        foreach ((array) $matches AS $mkey => $mat) {
                            $conArray[] = $mat['content'];
                        }
                        $content = implode('#--iCMS.PageBreak--#', $conArray);
                        unset($conArray);
                    } else {
                        preg_match('|' . $data_rule . '|is', $html, $matches, $PREG_SET_ORDER);
                        $content = $matches['content'];
                    }
                } else {
                    $content = $data_rule;
                }
            }
        }
		$html = null;
        unset($html);
        $content = stripslashes($content);
        if ($this->contTest) {
            print_r('<b>['.$name.']匹配结果:</b>'.htmlspecialchars($content));
            echo "<hr />";
        }
        if ($data['cleanbefor']) {
            $content = spiderTools::dataClean($data['cleanbefor'], $content);
        }
        /**
         * 在数据项里调用之前采集的数据[DATA@name][DATA@name.key]
         */
        if(strpos($content, '[DATA@')!==false){
            $content = spiderTools::getDATA($responses,$content);
        }
        if ($data['cleanhtml']) {
            $content = stripslashes($content);
            $content = preg_replace('/<[\/\!]*?[^<>]*?>/is', '', $content);
        }
        if ($data['format'] && $content) {
            $content = autoformat($content);
        }

        if ($data['img_absolute'] && $content) {
            // $content = stripslashes($content);
            preg_match_all("/<img.*?src\s*=[\"|'](.*?)[\"|']/is", $content, $img_match);
            if($img_match[1]){
                $_img_array = array_unique($img_match[1]);
                $_img_urls  = array();
                foreach ((array)$_img_array as $_img_key => $_img_src) {
                    $_img_urls[$_img_key] = spiderTools::url_complement($rule['__url__'],$_img_src);
                }
               $content = str_replace($_img_array, $_img_urls, $content);
            }
            unset($img_match,$_img_array,$_img_urls,$_img_src);
        }
        if ($data['trim']) {
            $content = trim($content);
        }
        if ($data['capture']) {
            // $content = stripslashes($content);
            $content = spiderTools::remote($content);
        }
        if ($data['download']) {
            // $content = stripslashes($content);
            $content = iFS::http($content);
        }

        if ($data['cleanafter']) {
            $content = spiderTools::dataClean($data['cleanafter'], $content);
            // $content = stripslashes($content);
        }
        if ($data['autobreakpage']) {
            $content = spiderTools::autoBreakPage($content);
        }
        if ($data['mergepage']) {
            $content = spiderTools::mergePage($content);
        }
        if ($data['empty'] && empty($content)) {
            $emptyMsg = '['.$name.']规则设置了不允许为空.当前抓取结果为空!请检查,规则是否正确!';
            if($this->contTest){
                exit('<h1>'.$emptyMsg.'</h1>');
            }
            if($this->work){
                echo "\n{$emptyMsg}\n";
                return false;
            }else{
                iPHP::alert($emptyMsg);
            }
        }
        if ($data['json_decode']) {
            $content = json_decode($content,true);
        }
        if($data['array']){
        	return (array)$content;
        }
        return $content;
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
    function update_spider_url_indexid($suid,$indexid){
        iDB::update('spider_url',array(
            //'publish' => '1',
            'indexid' => $indexid,
            //'pubdate' => time()
        ),array('id'=>$suid));
    }

    function update_spider_url_publish($suid){
        iDB::update('spider_url',array(
            'publish' => '1',
            'pubdate' => time()
        ),array('id'=>$suid));
    }


}


