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
class keywordsAdmincp{
    public function __construct() {
        $this->appid = iCMS_APP_KEYWORDS;
    	$this->id	= (int)$_GET['id'];
    }
    public function do_config(){
        configAdmincp::app($this->appid);
    }
    public function do_save_config(){
        configAdmincp::save($this->appid);
    }
    public function do_add(){
        if($this->id) {
            $rs			= iDB::row("SELECT * FROM `#iCMS@__keywords` WHERE `id`='$this->id' LIMIT 1;",ARRAY_A);
        }else{
        	$rs['keyword']	= $_GET['keyword'];
        	$rs['url']		= $_GET['url'];
        }
        include admincp::view("keywords.add");
    }
    public function do_save(){
		$id		= (int)$_POST['id'];
		$keyword= iSecurity::escapeStr($_POST['keyword']);
		$url	= iSecurity::escapeStr($_POST['url']);
		$times	= (int)$_POST['times'];

        $keyword OR iUI::alert('关键词不能为空!');
        $url 	OR iUI::alert('链接不能为空!');
        $fields = array('keyword', 'url', 'times');
        $data   = compact ($fields);

        if(empty($id)) {
            iDB::value("SELECT `id` FROM `#iCMS@__keywords` where `keyword` ='$keyword'") && iUI::alert('该关键词已经存在!');
            iDB::insert('keywords',$data);
            $this->cache();
            $msg="关键词添加完成!";
        }else {
            iDB::value("SELECT `id` FROM `#iCMS@__keywords` where `keyword` ='$keyword' AND `id` !='$id'") && iUI::alert('该关键词已经存在!');
            iDB::update('keywords', $data, array('id'=>$id));
            $this->cache();
            $msg="关键词编辑完成!";
        }
        iUI::success($msg,'url:'.APP_URI);
    }

    public function do_iCMS(){
        if($_GET['keywords']) {
			$sql=" WHERE `keyword` REGEXP '{$_GET['keywords']}'";
        }
        $orderby	=$_GET['orderby']?$_GET['orderby']:"id DESC";
        $maxperpage = $_GET['perpage']>0?(int)$_GET['perpage']:20;
		$total		= iCMS::page_total_cache("SELECT count(*) FROM `#iCMS@__keywords` {$sql}","G");
        iUI::pagenav($total,$maxperpage,"个关键词");
        $rs     = iDB::all("SELECT * FROM `#iCMS@__keywords` {$sql} order by {$orderby} LIMIT ".iUI::$offset." , {$maxperpage}");
        $_count = count($rs);
    	include admincp::view("keywords.manage");
    }
    public function do_del($id = null,$dialog=true){
    	$id===null && $id=$this->id;
		$id OR iUI::alert('请选择要删除的关键词!');
		iDB::query("DELETE FROM `#iCMS@__keywords` WHERE `id` = '$id'");
		$this->cache();
		$dialog && iUI::success('关键词已经删除','js:parent.$("#tr'.$id.'").remove();');
    }
    public function do_batch(){
        $idArray = (array)$_POST['id'];
        $idArray OR iUI::alert("请选择要操作的关键词");
        $ids     = implode(',',$idArray);
        $batch   = $_POST['batch'];
    	switch($batch){
    		case 'dels':
				iUI::$break	= false;
	    		foreach($idArray AS $id){
	    			$this->do_del($id,false);
	    		}
	    		iUI::$break	= true;
				iUI::success('关键词全部删除完成!','js:1');
    		break;
		}
	}
    public static function cache(){
    	$rs	= iDB::all("SELECT * FROM `#iCMS@__keywords` ORDER BY CHAR_LENGTH(`keyword`) DESC");
        // iCache::delete('iCMS/keywords');
        if($rs){
            foreach($rs AS $i=>$val) {
                if($val['times']>0) {
                    $search[]  = $val['keyword'];
                    $replace[] = '<a class="keyword" target="_blank" href="'.$val['url'].'">'.$val['keyword'].'</a>';
                }
            }
            iCache::set('iCMS/keywords.search',$search,0);
            iCache::set('iCMS/keywords.replace',$replace,0);
        }
    }
}
