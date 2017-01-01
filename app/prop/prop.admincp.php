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
class propAdmincp{
    public function __construct() {
        $this->pid         = (int)$_GET['pid'];
        $this->categoryApp = new categoryAdmincp('all');
    }
    public function do_add(){
        $this->pid && $rs = iDB::row("SELECT * FROM `#iCMS@__prop` WHERE `pid`='$this->pid' LIMIT 1;",ARRAY_A);
        if($_GET['act']=="copy"){
            $this->pid = 0;
            $rs['val'] = '';
        }
        if(empty($rs)){
            $_GET['_app'] && $rs['app']  = iSecurity::escapeStr($_GET['_app']);
            $_GET['field']&& $rs['field'] = iSecurity::escapeStr($_GET['field']);
        }
        include admincp::view("prop.add");
    }
    public function do_save(){
        $pid      = (int)$_POST['pid'];
        $cid      = (int)$_POST['cid'];
        $sortnum = (int)$_POST['sortnum'];
        $field    = iSecurity::escapeStr($_POST['field']);
        $name     = iSecurity::escapeStr($_POST['name']);
        $app     = iSecurity::escapeStr($_POST['app']);
        $val      = iSecurity::escapeStr($_POST['val']);

		($field=='pid'&& !is_numeric($val)) && iUI::alert('pid字段的值只能用数字');
        $field OR iUI::alert('属性字段不能为空!');
        $name OR iUI::alert('属性名称不能为空!');
        $app OR iUI::alert('所属应用不能为空!');

		$field=='pid' && $val=(int)$val;

        $fields = array('rootid','cid','field','app','sortnum', 'name', 'val');
        $data   = compact ($fields);

		if($pid){
            iDB::update('prop', $data, array('pid'=>$pid));
			$msg="属性更新完成!";
		}else{
	        iDB::value("SELECT `pid` FROM `#iCMS@__prop` where `app` ='$app' AND `val` ='$val' AND `field` ='$field' AND `cid` ='$cid'") && iUI::alert('该类型属性值已经存在!请另选一个');
            iDB::insert('prop',$data);
	        $msg="新属性添加完成!";
		}
		$this->cache();
        iUI::success($msg,'url:'.APP_URI);
    }
    public function do_update(){
    	foreach((array)$_POST['pid'] as $tk=>$pid){
            iDB::query("update `#iCMS@__prop` set `app` = '".$_POST['app'][$tk]."', `name` = '".$_POST['name'][$tk]."', `value` = '".$_POST['value'][$tk]."' where `pid` = '$pid';");
    	}
    	$this->cache();
    	iUI::alert('更新完成');
    }
    public function do_del($id = null,$dialog=true){
    	$id===null && $id=$this->pid;
    	$id OR iUI::alert('请选择要删除的属性!');
		iDB::query("DELETE FROM `#iCMS@__prop` WHERE `pid` = '$id';");
    	$this->cache();
    	$dialog && iUI::success("已经删除!",'url:'.APP_URI);
    }
    public function do_batch(){
        $idArray = (array)$_POST['id'];
        $idArray OR iUI::alert("请选择要操作的属性");
        $ids     = implode(',',$idArray);
        $batch   = $_POST['batch'];
    	switch($batch){
    		case 'dels':
				iUI::$break	= false;
	    		foreach($idArray AS $id){
	    			$this->do_del($id,false);
	    		}
	    		iUI::$break	= true;
				iUI::success('属性全部删除完成!','js:1');
    		break;
    		case 'refresh':
    			$this->cache();
    			iUI::success('属性缓存全部更新完成!','js:1');
    		break;
		}
	}

    public function do_iCMS(){
        $sql			= " where 1=1";
//        $cid			= (int)$_GET['cid'];
//
//        if($cid) {
//	        $cids	= $_GET['sub']?categoryApp::get_cids($cid,true):$cid;
//	        $cids OR $cids	= $vars['cid'];
//	        $sql.= iSQL::where($cids,'cid');
//        }

        $_GET['field']&& $sql.=" AND `field`='".$_GET['field']."'";
        $_GET['field']&& $uri.='&field='.$_GET['field'];

        $_GET['_app'] && $sql.=" AND `app`='".$_GET['_app']."'";
        $_GET['_app'] && $uri.='&app='.$_GET['_app'];

        $_GET['cid']  && $sql.=" AND `cid`='".$_GET['cid']."'";
        $_GET['cid']  && $uri.='&cid='.$_GET['cid'];

        $maxperpage = $_GET['perpage']>0?(int)$_GET['perpage']:20;
        $total		= iPHP::page_total_cache("SELECT count(*) FROM `#iCMS@__prop` {$sql}","G");
        iUI::pagenav($total,$maxperpage,"个属性");
        $rs     = iDB::all("SELECT * FROM `#iCMS@__prop` {$sql} order by pid DESC LIMIT ".iUI::$offset." , {$maxperpage}");
        $_count = count($rs);
    	include admincp::view("prop.manage");
    }
    public function do_cache(){
        $this->cache();
        iUI::success('缓存更新完成!','js:1');
    }
    public static function cache(){
    	$rs	= iDB::all("SELECT * FROM `#iCMS@__prop`");
    	foreach((array)$rs AS $row) {
            $type_field_id[$row['app'].'/'.$row['field']][$row['pid']] =
            $type_field_val[$row['app']][$row['field']][$row['val']]   = $row;
    	}
        // prop/article/author
        foreach((array)$type_field_id AS $key=>$a){
            iCache::set('iCMS/prop/'.$key,$a,0);
        }
        // prop/article
    	foreach((array)$type_field_val AS $k=>$a){
    		iCache::set('iCMS/prop/'.$k,$a,0);
    	}
    }
    public static function btn_group($field, $app = null,$target = null){
        $app OR $app = admincp::$APP_NAME;
        $propArray = iCache::get("iCMS/prop/{$app}/{$field}");
        $target OR $target = $field;
        echo '<div class="btn-group">'.
        '<a class="btn dropdown-toggle iCMS-default" data-toggle="dropdown" tabindex="-1"> <span class="caret"></span> 选择</a>'.
        '<ul class="dropdown-menu">';
        foreach ((array)$propArray as $prop) {
            echo '<li><a href="javascript:;" data-toggle="insert" data-target="#' . $target . '" data-value="' . $prop['val'] . '">' . $prop['name'] . '</a></li>';
        }
        echo '<li><a class="btn" href="'.__ADMINCP__.'=prop&do=add&_app='.$app.'&field='.$field.'" target="_blank">添加常用属性</a></li>';
        echo '</ul></div>';
    }
    public static function get($field, $valArray = NULL,/*$default=array(),*/$out = 'option', $url="",$app = "") {
        $app OR $app = admincp::$APP_NAME;
        $propArray = iCache::get("iCMS/prop/{$app}/{$field}");
        is_array($valArray) OR $valArray  = explode(',', $valArray);
        $opt = array();
        foreach ((array)$propArray AS $k => $P) {
            if ($out == 'option') {
                $opt[]="<option value='{$P['val']}'" . (isset($valArray[$P['val']]) ? " selected='selected'" : '') . ">{$P['name']}[{$field}='{$P['val']}'] </option>";
            } elseif ($out == 'array') {
                $opt[$P['val']] = $P['name'];
            } elseif ($out == 'text') {
                // if (array_search($P['val'],$valArray)!==FALSE) {
                if(isset($valArray[$P['val']])){
                    $flag = '<i class="fa fa-flag"></i> '.$P['name'];
                    $opt[]= ($url?'<a href="'.str_replace('{PID}',$P['val'],$url).'">'.$flag.'</a>':$flag).'<br />';
                }
            }
        }
        if($out == 'array'){
            return $opt;
        }
        // $opt.='</select>';
        return implode('', $opt);
    }
    public static function flag($pids,$data,$url=null) {
        $pidArray = explode(',',$pids);
        foreach ((array)$pidArray as $key => $pid) {
            $name = $data[$pid];
            if($pid!='0'){
                $flag = '<i class="fa fa-flag"></i> '.$name;
                echo ($url?'<a href="'.str_replace('{PID}',$pid,$url).'">'.$flag.'</a>':$flag).'<br />';
            }
        }
    }
    public static function del_app_data($appid=null){
        if($appid){
            iDB::query("DELETE FROM `#iCMS@__prop` WHERE `appid` = '".$appid."'");
            iDB::query("DELETE FROM `#iCMS@__prop_map` WHERE `appid` = '".$appid."';");
        }
    }
}
