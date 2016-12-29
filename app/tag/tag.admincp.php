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

iPHP::app('tag.class','static');
class tagAdmincp{
    public $callback = array();
    public function __construct() {
        $this->appid       = iCMS_APP_TAG;
        $this->id          = (int)$_GET['id'];
        $this->categoryApp = iPHP::app('category.admincp');
        $this->tagcategory = iPHP::app('category.admincp',$this->appid);
    }
    public function do_config(){
        $configApp = iPHP::app('config.admincp');
        $configApp->app($this->appid);
    }
    public function do_save_config(){
        $configApp = iPHP::app('config.admincp');
        $_POST['config']['url'] = trim($_POST['config']['url'],'/');
        $_POST['config']['dir'] = rtrim($_POST['config']['dir'],'/').'/';
        $configApp->save($this->appid);
    }

    public function do_add(){
        $this->id && $rs = iDB::row("SELECT * FROM `#iCMS@__tags` WHERE `id`='$this->id' LIMIT 1;",ARRAY_A);
        $rs['metadata'] && $rs['metadata']=json_decode($rs['metadata']);
        include admincp::view('tag.add');
    }
    public function do_update(){
        if($this->id){
            $data = admincp::update_args($_GET['_args']);
            $data && iDB::update("tags",$data,array('id'=>$this->id));
            tag::cache($this->id,'id');
            iUI::success('操作成功!','js:1');
        }
    }
    public function do_iCMS(){
    	admincp::$APP_METHOD="domanage";
    	$this->do_manage();
    }
    public function do_manage(){
        $sql  = " where 1=1";
        $cid    = (int)$_GET['cid'];
        $tcid   = (int)$_GET['tcid'];
        $pid    = (int)$_GET['pid'];
        $rootid = (int)$_GET['rootid'];

        $_GET['keywords'] && $sql.=" AND CONCAT(name,seotitle,subtitle,keywords,description) REGEXP '{$_GET['keywords']}'";

        $sql.= $this->categoryApp->search_sql($cid);
        $sql.= $this->tagcategory->search_sql($tcid,'tcid');
        $_GET['starttime'] && $sql.=" AND `pubdate`>='".str2time($_GET['starttime']." 00:00:00")."'";
        $_GET['endtime']   && $sql.=" AND `pubdate`<='".str2time($_GET['endtime']." 23:59:59")."'";
        $_GET['post_starttime'] && $sql.=" AND `postime`>='".str2time($_GET['post_starttime']." 00:00:00")."'";
        $_GET['post_endtime']   && $sql.=" AND `postime`<='".str2time($_GET['post_endtime']." 23:59:59")."'";

        isset($_GET['pic']) && $sql.=" AND `haspic` ='".($_GET['pic']?1:0)."'";
        if(isset($_GET['pid']) && $pid!='-1'){
            $uri_array['pid'] = $pid;
            if($_GET['pid']==0){
                $sql.= " AND `pid`=''";
            }else{
                iCMS::core('Map');
                iMap::init('prop',$this->appid);
                $map_where = iMap::where($pid);
            }
        }
        if($map_where){
            $map_sql = iCMS::map_sql($map_where);
            $sql     = ",({$map_sql}) map {$sql} AND `id` = map.`iid`";
        }

        $orderby	= $_GET['orderby']?$_GET['orderby']:"id DESC";
        $maxperpage = $_GET['perpage']>0?(int)$_GET['perpage']:20;
        $total		= iPHP::total(false,"SELECT count(*) FROM `#iCMS@__tags` {$sql}","G");
        iUI::pagenav($total,$maxperpage,"个标签");
        $limit  = 'LIMIT '.iUI::$offset.','.$maxperpage;
        if($map_sql||iUI::$offset){
            $ids_array = iDB::all("
                SELECT `id` FROM `#iCMS@__tags` {$sql}
                ORDER BY {$orderby} {$limit}
            ");
            $ids   = iPHP::values($ids_array);
            $ids   = $ids?$ids:'0';
            $sql   = "WHERE `id` IN({$ids})";
            $limit = '';
        }
        $rs     = iDB::all("SELECT * FROM `#iCMS@__tags` {$sql} ORDER BY {$orderby} {$limit}");
        $_count = count($rs);
        $propArray = iPHP::app('prop.admincp')->get("pid",null,'array');
    	include admincp::view("tag.manage");
    }
    public function do_import(){
        $_POST['cid'] OR iUI::alert('请选择标签所属栏目！');
        iFS::$checkFileData           = false;
        iFS::$config['allow_ext']     = 'txt';
        iFS::$config['yun']['enable'] = false;
        $F    = iFS::upload('upfile');
        $path = $F['RootPath'];
        if($path){
            $contents = file_get_contents($path);
            $encode   = mb_detect_encoding($contents, array("ASCII","UTF-8","GB2312","GBK","BIG5"));
            if(strtoupper($encode)!='UTF-8'){
                if (function_exists('mb_convert_encoding')) {
                    $contents = mb_convert_encoding($contents,'UTF-8',$encode);
                } elseif (function_exists('iconv')) {
                    $contents = iconv($encode,'UTF-8', $contents);
                }else{
                    iUI::alert('请把文件编码转换成UTF-8！');
                }
            }
            if($contents){
                iCMS::core('Map');
                $fields   = array('uid', 'cid', 'tcid', 'pid', 'tkey', 'name', 'seotitle', 'subtitle', 'keywords', 'description', 'metadata','haspic', 'pic', 'url', 'related', 'count', 'weight', 'tpl', 'sortnum', 'pubdate', 'status');
                $cid      = implode(',', (array)$_POST['cid']);
                $tcid     = implode(',', (array)$_POST['tcid']);
                $pid      = implode(',', (array)$_POST['pid']);
                $variable = explode("\n", $contents);
                $msg = array();
                foreach ($variable as $key => $name) {
                    $name = trim($name);
                    if(empty($name)){
                        $msg['empty']++;
                        continue;
                    }
                    $name = preg_replace('/<[\/\!]*?[^<>]*?>/is','',$name);
                    $name = addslashes($name);
                    if(iDB::value("SELECT `id` FROM `#iCMS@__tags` where `name` = '$name'")){
                        $msg['has']++;
                        continue;
                    }
                    $tkey    = strtolower(pinyin($name));
                    $uid     = iMember::$userid;
                    $haspic  = '0';
                    $status  = '1';
                    $pubdate = time();
                    $data    = compact ($fields);
                    $id = iDB::insert('tags',$data);

                    iMap::init('prop',$this->appid);
                    $pid && iMap::add($pid,$id);

                    iMap::init('category',$this->appid);
                    $cid  && iMap::add($cid,$id);
                    $tcid && iMap::add($tcid,$id);
                    $msg['success']++;
                }
            }
            @unlink($path);
            iUI::success('标签导入完成<br />空标签:'.(int)$msg['empty'].'个<br />已经存在标签:'.(int)$msg['has'].'个<br />成功导入标签:'.(int)$msg['success'].'个');
        }
    }
    public function do_save(){
        $id          = (int)$_POST['id'];
        $uid         = (int)$_POST['uid'];
        $rootid      = (int)$_POST['rootid'];
        $cid         = implode(',', (array)$_POST['cid']);
        $tcid        = implode(',', (array)$_POST['tcid']);
        $pid         = implode(',', (array)$_POST['pid']);
        $_cid        = iSecurity::escapeStr($_POST['_cid']);
        $_tcid       = iSecurity::escapeStr($_POST['_tcid']);
        $_pid        = iSecurity::escapeStr($_POST['_pid']);
        $name        = iSecurity::escapeStr($_POST['name']);
        $subtitle    = iSecurity::escapeStr($_POST['subtitle']);
        $tkey        = iSecurity::escapeStr($_POST['tkey']);
        $seotitle    = iSecurity::escapeStr($_POST['seotitle']);
        $keywords    = iSecurity::escapeStr($_POST['keywords']);
        $pic         = iSecurity::escapeStr($_POST['pic']);
        $bpic        = iSecurity::escapeStr($_POST['bpic']);
        $mpic        = iSecurity::escapeStr($_POST['mpic']);
        $spic        = iSecurity::escapeStr($_POST['spic']);
        $description = iSecurity::escapeStr($_POST['description']);
        $url         = iSecurity::escapeStr($_POST['url']);
        $related     = iSecurity::escapeStr($_POST['related']);
        $tpl         = iSecurity::escapeStr($_POST['tpl']);
        $weight      = (int)$_POST['weight'];
        $sortnum    = (int)$_POST['sortnum'];
        $status      = (int)$_POST['status'];
        $haspic      = $pic?'1':'0';
        $pubdate     = time();
        $metadata    = $_POST['metadata'];

        $uid OR $uid= iMember::$userid;

        if($callback){
            if(empty($name)){
                echo '标签名称不能为空！';
                return false;
            }
        }
        $name OR iUI::alert('标签名称不能为空！');
        // $cid OR iUI::alert('请选择标签所属栏目！');

        if($metadata){
            if($metadata['key']){
                $md = array();
                foreach($metadata['key'] AS $_mk=>$_mval){
                    !preg_match("/[a-zA-Z0-9_\-]/",$_mval) && iUI::alert($this->name_text.'附加属性名称只能由英文字母、数字或_-组成(不支持中文)');
                    $md[$_mval] = $metadata['value'][$_mk];
                }
            }else{
                $md = $metadata;
            }
            $metadata = addslashes(json_encode($md));
        }

		if(empty($id)) {
            $hasNameId = iDB::value("SELECT `id` FROM `#iCMS@__tags` where `name` = '$name'");
            if($hasNameId){
                if(isset($_POST['spider_update'])){
                    $id = $hasNameId;
                }else{
                    iUI::alert('该标签已经存在!请检查是否重复');
                }
            }
		}
		if(empty($tkey) && $url){
			$tkey = substr(md5($url),8,16);
			$hasTkey = iDB::value("SELECT `id` FROM `#iCMS@__tags` where `tkey` = '$tkey'");
            if($hasTkey){
                if(isset($_POST['spider_check_tkey'])){
                    echo '该自定义链接已经存在!请检查是否重复';
                    return false;
                }else{
                    iUI::alert('该自定义链接已经存在!请检查是否重复');
                }
            }
		}

		$tkey OR $tkey = strtolower(pinyin($name));

        iFS::$forceExt = "jpg";
        iFS::checkHttp($pic) && $pic = iFS::http($pic);
        iFS::checkHttp($bpic)&& $bpic = iFS::http($bpic);
        iFS::checkHttp($mpic)&& $mpic = iFS::http($mpic);
        iFS::checkHttp($spic)&& $spic = iFS::http($spic);

		iCMS::core('Map');

        $fields = array('uid','rootid', 'cid', 'tcid', 'pid', 'tkey', 'name', 'seotitle', 'subtitle', 'keywords', 'description', 'metadata','haspic', 'pic','bpic','mpic','spic', 'url', 'related', 'count', 'weight', 'tpl', 'sortnum', 'pubdate', 'status');
        $data   = compact ($fields);

		if(empty($id)){
            $data['postime']  = $pubdate;
            $data['count']    = '0';
            $data['comments'] = '0';
            $id = iDB::insert('tags',$data);
			tag::cache($id,'id');

            iMap::init('prop',$this->appid);
            $pid && iMap::add($pid,$id);

            iMap::init('category',$this->appid);
            iMap::add($cid,$id);
            $tcid && iMap::add($tcid,$id);

            $msg ='标签添加完成';
		}else{
            if(isset($_POST['spider_update'])){
                // $data = array();
                $hasTag = iDB::row("SELECT * FROM `#iCMS@__tags` where `id` = '$id'",ARRAY_A);
                $this->check_spider_data($data,$hasTag,'subtitle',$subtitle);
                $this->check_spider_data($data,$hasTag,'description',$description);
                $this->check_spider_data($data,$hasTag,'seotitle',$seotitle);
                $this->check_spider_data($data,$hasTag,'keywords',$keywords);
                $this->check_spider_data($data,$hasTag,'related',$related);

                ($hasTag['cid'] && $cid)    && $data['cid']=$cid;   $_cid = $hasTag['cid'];
                ($hasTag['tcid'] && $tcid)  && $data['tcid']=$tcid; $_tcid = $hasTag['tcid'];
                ($hasTag['pid'] && $pid)    && $data['pid']=$pid;   $_pid = $hasTag['pid'];

            }

            unset($data['count'],$data['comments']);
            iDB::update('tags', $data, array('id'=>$id));
			tag::cache($id,'id');

            iMap::init('prop',$this->appid);
            iMap::diff($pid,$_pid,$id);

            iMap::init('category',$this->appid);
            iMap::diff($cid,$_cid,$id);
            iMap::diff($tcid,$_tcid,$id);
            $msg = '标签更新完成';
		}
        admincp::callback($id,$this);
        if($this->callback['code']){
            return array(
                "code"    => $this->callback['code'],
                'indexid' => $id
            );
        }
        iUI::success($msg,"url:".APP_URI);
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
    public function check_spider_data(&$data,$old,$key,$value){
        if($old[$key]){
            if($value){
                $data[$key] = $value;
            }else{
                unset($data[$key]);
            }
        }
    }
    public function do_cache(){
    	tag::cache($this->id,'id');
    	iUI::success("标签缓存更新成功");
    }
    public function do_del($id = null,$dialog=true){
    	$id===null && $id=$this->id;
        iDB::query("DELETE FROM `#iCMS@__category_map` WHERE `iid` = '$id' AND `appid` = '".$this->appid."';");
        iDB::query("DELETE FROM `#iCMS@__prop_map` WHERE `iid` = '$id' AND `appid` = '".$this->appid."' ;");

    	tag::del($id,'id');
    	$dialog && iUI::success("标签删除成功",'js:parent.$("#tr'.$id.'").remove();');
    }
    public function do_batch(){
        $idArray = (array)$_POST['id'];
        $idArray OR iUI::alert("请选择要操作的标签");
        $ids     = implode(',',$idArray);
        $batch   = $_POST['batch'];
    	switch($batch){
    		case 'dels':
				iUI::$break	= false;
	    		foreach($idArray AS $id){
	    			$this->do_del($id,false);
	    		}
	    		iUI::$break	= true;
				iUI::success('标签全部删除完成!','js:1');
    		break;
    		case 'move':
		        $_POST['cid'] OR iUI::alert("请选择目标栏目!");
                iCMS::core('Map');
                iMap::init('category',$this->appid);
		        $cid = (int)$_POST['cid'];
		        foreach($idArray AS $id) {
                    $_cid = iDB::value("SELECT `cid` FROM `#iCMS@__tags` where `id` ='$id'");
                    iDB::update("tags",compact('cid'),compact('id'));
		            if($_cid!=$cid) {
                        iMap::diff($cid,$_cid,$id);
                        $this->categoryApp->update_count_one($_cid,'-');
                        $this->categoryApp->update_count_one($cid);
		            }
		        }
		        iUI::success('成功移动到目标栏目!','js:1');
    		break;
    		case 'mvtcid':
		        $_POST['tcid'] OR iUI::alert("请选择目标分类!");
                iCMS::core('Map');
                iMap::init('category',$this->appid);
		        $tcid = (int)$_POST['tcid'];
		        foreach($idArray AS $id) {
                    $_tcid = iDB::value("SELECT `tcid` FROM `#iCMS@__tags` where `id` ='$id'");
                    iDB::update("tags",compact('tcid'),compact('id'));
		            if($_tcid!=$tcid) {
                        iMap::diff($tcid,$_tcid,$id);
                        $this->categoryApp->update_count_one($_tcid,'-');
                        $this->categoryApp->update_count_one($tcid);
		            }
		        }
		        iUI::success('成功移动到目标分类!','js:1');
    		break;
    		case 'prop':
                iCMS::core('Map');
                iMap::init('prop',$this->appid);
                $pid = implode(',', (array)$_POST['pid']);
                foreach((array)$_POST['id'] AS $id) {
                    $_pid = iDB::value("SELECT pid FROM `#iCMS@__tags` WHERE `id`='$id'");;
                    iDB::update("tags",compact('pid'),compact('id'));
                    iMap::diff($pid,$_pid,$id);
                }
                iUI::success('属性设置完成!','js:1');
    		break;
    		case 'weight':
		        $weight	=(int)$_POST['mweight'];
		        $sql	="`weight` = '$weight'";
    		break;
            case 'tpl':
                $tpl = iSecurity::escapeStr($_POST['mtpl']);
                $sql = "`tpl` = '$tpl'";
            break;
    		case 'keyword':
    			if($_POST['pattern']=='replace') {
    				$sql	="`keywords` = '".iSecurity::escapeStr($_POST['mkeyword'])."'";
    			}elseif($_POST['pattern']=='addto') {
		        	foreach($idArray AS $id){
                        $keywords = iDB::value("SELECT keywords FROM `#iCMS@__tags` WHERE `id`='$id'");
                        $sql      ="`keywords` = '".($keywords?$keywords.','.iSecurity::escapeStr($_POST['mkeyword']):iSecurity::escapeStr($_POST['mkeyword']))."'";
				        iDB::query("UPDATE `#iCMS@__tags` SET {$sql} WHERE `id`='$id'");
		        	}
		        	iUI::success('关键字更改完成!','js:1');
    			}
    		break;
    		case 'tag':
    			if($_POST['pattern']=='replace') {
    				$sql	="`related` = '".iSecurity::escapeStr($_POST['mtag'])."'";
    			}elseif($_POST['pattern']=='addto') {
		        	foreach($idArray AS $id){
		        		$keywords	= iDB::value("SELECT related FROM `#iCMS@__tags` WHERE `id`='$id'");
		        		$sql		="`related` = '".($keywords?$keywords.','.iSecurity::escapeStr($_POST['mtag']):iSecurity::escapeStr($_POST['mtag']))."'";
				        iDB::query("UPDATE `#iCMS@__tags` SET {$sql} WHERE `id`='$id'");
		        	}
		        	iUI::success('相关标签更改完成!','js:1');
    			}
    		break;
    		default:
                if(strpos($batch, ':')){
                    $data = admincp::update_args($batch);
                    foreach($idArray AS $id) {
                        $data && iDB::update("tags",$data,array('id'=>$id));
                    }
                    iUI::success('操作成功!','js:1');
                }else{
                    iUI::alert('请选择要操作项!','js:1');
                }

		}
        $sql && iDB::query("UPDATE `#iCMS@__tags` SET {$sql} WHERE `id` IN ($ids)");
		iUI::success('操作成功!','js:1');
	}
}
