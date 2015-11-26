<?php
/**
* iCMS - i Content Management System
* Copyright (c) 2007-2012 idreamsoft.com iiimon Inc. All rights reserved.
*
* @author coolmoo <idreamsoft@qq.com>
* @site http://www.idreamsoft.com
* @licence http://www.idreamsoft.com/license.php
* @version 6.0.0
* @$Id: spider.app.php 634 2013-04-03 06:02:53Z coolmoo $
*/

class spider{
    public static $cid      = null;
    public static $rid      = null;
    public static $pid      = null;
    public static $sid      = null;
    public static $title    = null;
    public static $url      = null;
    public static $work     = false;
    public static $urlslast = null;
    public static $allHtml  = null;

	public static $dataTest = false;
	public static $ruleTest = false;

	public static $content_right_code = false;
	public static $content_error_code = false;

	public static $referer     = null;
	public static $encoding    = null;
	public static $useragent   = null;
	public static $cookie      = null;
	public static $charset     = null;
	public static $curl_proxy  = false;
	public static $proxy_array = array();

    public static function rule($id) {
        $rs = iDB::row("SELECT * FROM `#iCMS@__spider_rule` WHERE `id`='$id' LIMIT 1;", ARRAY_A);
        $rs['rule'] && $rs['rule'] = stripslashes_deep(unserialize($rs['rule']));
        $rs['user_agent'] OR $rs['user_agent'] = "Mozilla/5.0 (compatible; Baiduspider/2.0; +http://www.baidu.com/search/spider.html)";
        spider::$useragent = $rs['rule']['user_agent'];
        spider::$encoding  = $rs['rule']['curl']['encoding'];
        spider::$referer   = $rs['rule']['curl']['referer'];
        spider::$cookie    = $rs['rule']['curl']['cookie'];
        spider::$charset   = $rs['rule']['charset'];
        return $rs;
    }

    public static function project($id) {
        return iDB::row("SELECT * FROM `#iCMS@__spider_project` WHERE `id`='$id' LIMIT 1;", ARRAY_A);
    }
    public static function postArgs($id) {
        $postRs = iDB::row("SELECT * FROM `#iCMS@__spider_post` WHERE `id`='$id' LIMIT 1;");
        if ($postRs->post) {
            $postArray = explode("\n", $postRs->post);
            $postArray = array_filter($postArray);
            foreach ($postArray AS $key => $pstr) {
                list($pkey, $pval) = explode("=", $pstr);
                $_POST[$pkey] = trim($pval);
            }
            return $postRs;
        }
    }
    public static function update_spider_url_indexid($suid,$indexid){
        iDB::update('spider_url',array(
            //'publish' => '1',
            'indexid' => $indexid,
            //'pubdate' => time()
        ),array('id'=>$suid));
    }

    public static function update_spider_url_publish($suid){
        iDB::update('spider_url',array(
            'publish' => '1',
            'pubdate' => time()
        ),array('id'=>$suid));
    }
    public static function checker($work = null){
        $project = spider::project(spider::$pid);
        $hash    = md5(spider::$url);
        if(($project['checker'] && empty($_GET['indexid'])) || $work=="DATA@RULE"){
            $title = iS::escapeStr(spider::$title);
            $url   = iS::escapeStr(spider::$url);
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
            $project['self'] && $sql.=" AND `pid`='".spider::$pid."'";
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
    public static function publish($work = null) {
        $_POST = spiderData::crawl();
        if(spider::$work){
           // if(empty($_POST['title'])){
           //     echo "标题不能为空\n";
           //     return false;
           // }
           // if(empty($_POST['body'])){
           //     echo "内容不能为空\n";
           //     return false;
           // }
        }
        $checker = spider::checker($work);
        if($checker!==true){
            return $checker;
        }
        $pid          = spider::$pid;
        $project      = spider::project($pid);
        $_POST['cid'] = $project['cid'];
        $postArgs = spider::postArgs($project['poid']);

        if($_GET['indexid']){
            $aid = (int)$_GET['indexid'];
            $_POST['aid']  = $aid;
            $_POST['adid'] = iDB::value("SELECT `id` FROM `#iCMS@__article_data` WHERE aid='$aid'");
        }
        $hash  = md5(spider::$url);
        $title = iS::escapeStr($_POST['title']);
        $url   = iS::escapeStr($_POST['reurl']);
        if(empty(spider::$sid)){
            $spider_url = iDB::row("SELECT `id`,`publish`,`indexid` FROM `#iCMS@__spider_url` where `url`='$url'",ARRAY_A);
            if(empty($spider_url)){
                $spider_url_data = array(
                    'cid'     => $project['cid'],
                    'rid'     => spider::$rid,
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
            $suid = spider::$sid;
        }

        iS::slashes($_POST);
        $app = iACP::app($postArgs->app);
        $fun = $postArgs->fun;
        $app->callback['code'] = '1001';
        /**
         * 主表 回调 更新关联ID
         */
        $app->callback['primary'] = array(
            array('spider','update_spider_url_indexid'),
            array('suid'=>$suid)
        );
        /**
         * 数据表 回调 成功发布
         */
        $app->callback['data'] = array(
            array('spider','update_spider_url_publish'),
            array('suid'=>$suid)
        );

        $callback = $app->$fun();
        if ($callback['code'] == $app->callback['code']) {
            if (spider::$sid) {
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
}
