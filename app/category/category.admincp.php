<?php
/**
* iCMS - i Content Management System
* Copyright (c) 2007-2012 idreamsoft.com iiimon Inc. All rights reserved.
*
* @author coolmoo <idreamsoft@qq.com>
* @site http://www.idreamsoft.com
* @licence http://www.idreamsoft.com/license.php
* @version 6.0.0
* @$Id: category.app.php 2406 2014-04-28 02:24:46Z coolmoo $
*/
defined('iPHP') OR exit('What are you doing?');
iPHP::app('category.class','include');
class categoryAdmincp extends category{
    public $callback         = array();
    protected $category_uri  = APP_URI;
    protected $category_furi = APP_FURI;
    protected $category_name = "节点";
    /**
     *  模板
     */
    protected $category_template = array(
        'index'   =>array('首页','{iTPL}/category.index.htm'),
        'list'    =>array('列表','{iTPL}/category.list.htm'),
    );
    /**
     *  URL规则
     */
    protected $category_rule = array(
        'index'   => array('首页','/{CDIR}/index{EXT}','{CID},{0xCID},{CDIR}'),
        'list'    => array('列表','/{CDIR}/index_{P}{EXT}','{CID},{0xCID},{CDIR}'),
    );
    /**
     *  URL规则选项
     */
    protected $category_rule_list = array();

    protected $_app              = 'content';
    protected $_app_name         = '内容';
    protected $_app_table        = 'content';
    protected $_app_cid          = 'cid';
    protected $_view_add          = 'category.add';
    protected $_view_manage       = 'category.manage';

    function __construct($appid = null,$dir=null) {
        $this->cid       = (int)$_GET['cid'];
        $this->appid     = '-1';
        $appid          && $this->appid = $appid;
        $_GET['appid']  && $this->appid = (int)$_GET['appid'];
        parent::__construct($this->appid);
        $this->_view_tpl_dir = $dir;
    }

    function do_add(){
        if($this->cid) {
            admincp::CP($this->cid,'e','page');
            $rs		= iDB::row("SELECT * FROM `#iCMS@__category` WHERE `cid`='$this->cid' LIMIT 1;",ARRAY_A);
            $rootid	= $rs['rootid'];
            $rs['rule']     = json_decode($rs['rule'],true);
            $rs['template'] = json_decode($rs['template'],true);
            $rs['metadata'] = json_decode($rs['metadata'],true);
            $rs['body'] = iCache::get('iCMS/category/'.$this->cid.'.body');
            $rs['body'] && $rs['body'] = stripslashes($rs['body']);

        }else {
            $rootid = (int)$_GET['rootid'];
            $rootid && admincp::CP($rootid,'a','page');
        }
        if(empty($rs)) {
            $rs = array(
                'pid'       => '0',
                'status'    => '1',
                'isexamine' => '1',
                'issend'    => '1',
                'hasbody'   => '2',
                'ordernum'  => '0',
                'mode'      => '0',
                'htmlext'   => iCMS::$config['router']['html_ext'],
                'metadata'  => ''
            );
	        if($rootid){
                $rootRs = iDB::row("SELECT * FROM `#iCMS@__category` WHERE `cid`='".$rootid."' LIMIT 1;",ARRAY_A);
                $rs['htmlext']  = $rootRs['htmlext'];
                $rs['rule']     = json_decode($rootRs['rule'],true);
                $rs['template'] = json_decode($rootRs['template'],true);
	        }
        }
        include admincp::view($this->_view_add,$this->_view_tpl_dir);
    }
    function do_save(){
        $appid        = $this->appid;
        $cid          = (int)$_POST['cid'];
        $rootid       = (int)$_POST['rootid'];
        $status       = (int)$_POST['status'];
        $isucshow     = (int)$_POST['isucshow'];
        $issend       = (int)$_POST['issend'];
        $isexamine    = (int)$_POST['isexamine'];
        $ordernum     = (int)$_POST['ordernum'];
        $mode         = (int)$_POST['mode'];
        $pid          = implode(',', (array)$_POST['pid']);
        $_pid         = iS::escapeStr($_POST['_pid']);
        $_rootid_hash = iS::escapeStr($_POST['_rootid_hash']);
        $name         = iS::escapeStr($_POST['name']);
        $subname      = iS::escapeStr($_POST['subname']);
        $domain       = iS::escapeStr($_POST['domain']);
        $htmlext      = iS::escapeStr($_POST['htmlext']);
        $url          = iS::escapeStr($_POST['url']);
        $password     = iS::escapeStr($_POST['password']);
        $pic          = iS::escapeStr($_POST['pic']);
        $mpic         = iS::escapeStr($_POST['mpic']);
        $spic         = iS::escapeStr($_POST['spic']);
        $dir          = iS::escapeStr($_POST['dir']);
        $title        = iS::escapeStr($_POST['title']);
        $keywords     = iS::escapeStr($_POST['keywords']);
        $description  = iS::escapeStr($_POST['description']);
        $rule         = iS::escapeStr($_POST['rule']);
        $template     = iS::escapeStr($_POST['template']);
        $metadata     = iS::escapeStr($_POST['metadata']);
        $body         = $_POST['body'];
        $hasbody      = (int)$_POST['hasbody'];
        $hasbody OR $hasbody = $body?1:0;

        // if($_rootid_hash){
        //     $_rootid = authcode($_rootid_hash);
        //     if($rootid!=$_rootid){
        //         iPHP::alert('非法数据提交!');
        //     }else{
        //         admincp::CP($_rootid,'a','alert');
        //         exit;
        //     }
        // }
        ($cid && $cid==$rootid) && iPHP::alert('不能以自身做为上级'.$this->category_name);
        empty($name) && iPHP::alert($this->category_name.'名称不能为空!');
		if($metadata){
	        $md	= array();
            if(is_array($metadata['key'])){
    			foreach($metadata['key'] AS $_mk=>$_mval){
    				!preg_match("/[a-zA-Z0-9_\-]/",$_mval) && iPHP::alert($this->category_name.'附加属性名称只能由英文字母、数字或_-组成(不支持中文)');
    				$md[$_mval] = $metadata['value'][$_mk];
    			}
            }else if(is_array($metadata)){
                $md = $metadata;
            }
            $metadata = addslashes(json_encode($md));
		}
        if($mode=="2"){
            foreach ($rule as $key => $value) {
                $CR = $this->category_rule[$key];
                $CRKW = explode(',', $CR[2]);
                $cr_check = true;
                foreach ($CRKW as $i => $crk) {
                    if(strpos($value,$crk) !== FALSE){
                        $cr_check = false;
                    }
                }
                $cr_check && iPHP::alert('伪静态模式'.$CR[0].'规则必需要有'.$CR[2].'其中之一');
            }
        }

        $rule     = addslashes(json_encode($rule));
        $template = addslashes(json_encode($template));

        iPHP::import(iPHP_APP_CORE .'/iMAP.class.php');
        map::init('prop',iCMS_APP_CATEGORY);

        $fields = array('rootid','pid','appid','ordernum','name','subname','password','title','keywords','description','dir',
            'mode','domain','url','pic','mpic','spic','htmlext',
            'rule','template','metadata',
            'hasbody','isexamine','issend','isucshow','status');
        $data   = compact ($fields);

        if(empty($cid)) {
            admincp::CP($rootid,'a','alert');
            $nameArray = explode("\n",$name);
            $_count    = count($nameArray);
        	foreach($nameArray AS $nkey=>$_name){
        		$_name	= trim($_name);
                if(empty($_name)) continue;

                if($_count=="1"){
                    if(empty($dir) && empty($url)) {
                        $dir = strtolower(pinyin($_name));
                    }
                }else{
                    empty($url) && $dir = strtolower(pinyin($_name));
                }
                $mode=="2" && $this->check_dir($dir,$appid,$url);
                $data['name']       = $_name;
                $data['dir']        = $dir;
                $data['userid']     = iMember::$userid;
                $data['creator']    = iMember::$nickname;
                $data['pubdate'] = time();
                $data['count']      = '0';
                $data['comments']   = '0';
                $data['ordernum']   = $nkey;

                $cid = iDB::insert('category',$data);
                $pid && map::add($pid,$cid);
	            $this->cache(false,$this->appid);
	            $this->cahce_one($cid);
            }
            $msg = $this->category_name."添加完成!";
        }else {
            if(empty($dir) && empty($url)) {
                $dir = strtolower(pinyin($name));
            }
            admincp::CP($cid,'e','alert');
            $mode=="2" && $this->check_dir($dir,$appid,$url,$cid);

            $data['dir'] = $dir;
            iDB::update('category', $data, array('cid'=>$cid));
            map::diff($pid,$_pid,$cid);
            $this->cahce_one($cid);
            $msg = $this->category_name."编辑完成!";
        }
        $hasbody && iCache::set('iCMS/category/'.$cid.'.body',$body,0);

        admincp::callback($cid,$this);
        if($this->callback['code']){
            return array(
                "code"    => $this->callback['code'],
                'indexid' => $cid
            );
        }

        iPHP::success($msg,'url:'.$this->category_uri);
    }

    function do_update(){
    	foreach((array)$_POST['name'] as $cid=>$name){
    		$name	= iS::escapeStr($name);
			iDB::query("UPDATE `#iCMS@__category` SET `name` = '$name',`ordernum` = '".(int)$_POST['ordernum'][$cid]."' WHERE `cid` ='".(int)$cid."' LIMIT 1");
	    	$this->cahce_one($cid);
    	}
    	iPHP::success('更新完成');
    }
    function do_batch(){
        $_POST['id'] OR iPHP::alert("请选择要操作的".$this->category_name);
        $id_array = (array)$_POST['id'];
        $ids      = implode(',',$id_array);
        $batch    = $_POST['batch'];
        switch($batch){
            case 'move':
                $tocid = (int)$_POST['tocid'];
                $key   = array_search($tocid,$id_array);
                if($tocid) unset($id_array[$key]);//清除同ID
                foreach($id_array as $k=>$cid){
                    iDB::query("UPDATE `#iCMS@__category` SET `rootid` ='$tocid' WHERE `cid` ='$cid'");
                }
                $this->cache(true,$this->appid);
                iPHP::success('更新完成!','js:1');
            break;
            case 'merge':
                $tocid = (int)$_POST['tocid'];
                $key   = array_search($tocid,$id_array);
                unset($id_array[$key]);//清除同ID
                foreach($id_array as $k=>$cid){
                    $this->mergecontent($tocid,$cid);
                    $this->do_del($cid,false);
                }
                $this->update_count($tocid);
                $this->cache(true,$this->appid);
                iPHP::success('更新完成!','js:1');
            break;
            case 'dir':
                $mdir = iS::escapeStr($_POST['mdir']);
                if($_POST['pattern']=='replace') {
                    $sql = "`dir` = '$dir'";
                }
                if($_POST['pattern']=='addtobefore'){
                    $sql = "`dir` = CONCAT('{$mdir}',dir)";
                }
                if($_POST['pattern']=='addtoafter'){
                    $sql = "`dir` = CONCAT(dir,'{$mdir}')";
                }
                foreach($id_array as $k=>$cid){
                    $sql && iDB::query("UPDATE `#iCMS@__category` SET {$sql} WHERE `cid` ='".(int)$cid."' LIMIT 1");
                }
                iPHP::success('目录更改完成!','js:1');
            break;
            case 'mkdir':
                foreach($id_array as $k=>$cid){
                    $name = iS::escapeStr($_POST['name'][$cid]);
                    $dir  = pinyin($name);
                    iDB::query("UPDATE `#iCMS@__category` SET `dir` = '$dir' WHERE `cid` ='".(int)$cid."' LIMIT 1");
                }
                iPHP::success('更新完成!','js:1');
            break;
            case 'name':
                foreach($id_array as $k=>$cid){
                    $name   = iS::escapeStr($_POST['name'][$cid]);
                    iDB::query("UPDATE `#iCMS@__category` SET `name` = '$name' WHERE `cid` ='".(int)$cid."' LIMIT 1");
                    $this->cahce_one($cid);
                }
                iPHP::success('更新完成!','js:1');
            break;
            case 'status':
                $val = (int)$_POST['status'];
                $sql ="`status` = '$val'";
            break;
            case 'mode':
                $val = (int)$_POST['mode'];
                $sql ="`mode` = '$val'";
            break;
            case 'categoryRule':
                $val = iS::escapeStr($_POST['categoryRule']);;
                $sql ="`categoryRule` = '$val'";
            break;
            case 'contentRule':
                $val = iS::escapeStr($_POST['contentRule']);;
                $sql ="`contentRule` = '$val'";
            break;
            case 'urlRule':
                $val = iS::escapeStr($_POST['urlRule']);;
                $sql ="`urlRule` = '$val'";
            break;
            case 'indexTPL':
                $val = iS::escapeStr($_POST['indexTPL']);;
                $sql ="`indexTPL` = '$val'";
            break;
            case 'listTPL':
                $val = iS::escapeStr($_POST['listTPL']);;
                $sql ="`listTPL` = '$val'";
            break;
            case 'contentTPL':
                $val = iS::escapeStr($_POST['contentTPL']);;
                $sql ="`contentTPL` = '$val'";
            break;
            case 'recount':
                foreach($id_array as $k=>$cid){
                    $this->update_count($cid);
                }
                iPHP::success('操作成功!','js:1');
            break;
            case 'dels':
                iPHP::$break    = false;
                foreach($id_array AS $cid){
                    admincp::CP($cid,'d','alert');
                    $this->do_del($cid,false);
                    $this->cahce_one($cid);
                }
                iPHP::$break    = true;
                iPHP::success('全部删除完成!','js:1');
            break;
       }
        $sql && iDB::query("UPDATE `#iCMS@__category` SET {$sql} WHERE `cid` IN ($ids)");
        $this->cache(true,$this->appid);
        iPHP::success('操作成功!','js:1');
    }
    function do_updateorder(){
    	foreach((array)$_POST['ordernum'] as $ordernum=>$cid){
            iDB::query("UPDATE `#iCMS@__category` SET `ordernum` = '".intval($ordernum)."' WHERE `cid` ='".intval($cid)."' LIMIT 1");
	    	$this->cahce_one($cid);
    	}
    }
    function do_iCMS(){
        $tabs = iPHP::get_cookie(admincp::$APP_NAME.'_tabs');
        $tabs=="list"?$this->do_list():$this->do_tree();
    }
    function do_tree() {
        admincp::$menu->url = __ADMINCP__.'='.admincp::$APP_NAME;
        admincp::$APP_DO = 'tree';
        include admincp::view($this->_view_manage,$this->_view_tpl_dir);
    }
    function do_list(){
        admincp::$menu->url = __ADMINCP__.'='.admincp::$APP_NAME;
        admincp::$APP_DO = 'list';
        $sql  = " where `appid`='{$this->appid}'";
        $cids = admincp::CP('__CID__');
        $sql.= iPHP::where($cids,'cid');

        if($_GET['keywords']) {
            if($_GET['st']=="name") {
                $sql.=" AND `name` REGEXP '{$_GET['keywords']}'";
            }else if($_GET['st']=="cid") {
                $sql.=" AND `cid` REGEXP '{$_GET['keywords']}'";
            }else if($_GET['st']=="tkd") {
                $sql.=" AND CONCAT(name,title,keywords,description) REGEXP '{$_GET['keywords']}'";
            }
        }
        if(isset($_GET['rootid']) &&$_GET['rootid']!='-1') {
            $sql.=" AND `rootid`='{$_GET['rootid']}'";
        }
        $orderby    = $_GET['orderby']?$_GET['orderby']:"cid DESC";
        $maxperpage = $_GET['perpage']>0?(int)$_GET['perpage']:20;
        $total      = iPHP::total(false,"SELECT count(*) FROM `#iCMS@__category` {$sql}","G");
        iPHP::pagenav($total,$maxperpage);
        $rs     = iDB::all("SELECT * FROM `#iCMS@__category` {$sql} order by {$orderby} LIMIT ".iPHP::$offset." , {$maxperpage}");
        $_count = count($rs);
        include admincp::view($this->_view_manage,$this->_view_tpl_dir);
    }
    function do_copy(){
        iDB::query("
            INSERT INTO `#iCMS@__category` (
                `name`,`dir`,
               `rootid`, `pid`, `appid`, `userid`, `creator`,
               `subname`, `ordernum`, `password`, `title`, `keywords`, `description`,
               `url`, `pic`, `mpic`, `spic`, `count`, `mode`, `domain`, `htmlext`,
               `rule`, `template`, `metadata`, `hasbody`, `comments`, `isexamine`, `issend`,
               `isucshow`, `status`, `pubdate`
            ) SELECT
                CONCAT(`name`,'副本'),CONCAT(`dir`,'fuben'),
                `rootid`, `pid`, `appid`, `userid`, `creator`,
                `subname`, `ordernum`, `password`, `title`, `keywords`, `description`,
                `url`, `pic`, `mpic`, `spic`, `count`, `mode`, `domain`, `htmlext`,
                `rule`, `template`, `metadata`, `hasbody`, `comments`, `isexamine`, `issend`,
                `isucshow`, `status`, `pubdate`
            FROM `#iCMS@__category`
            WHERE cid = '$this->cid'");
        $cid = iDB::$insert_id;
        iPHP::success('克隆完成,编辑此'.$this->category_name, 'url:' . APP_URI . '&do=add&cid=' . $cid);

    }
    function do_del($cid = null,$dialog=true){
        $cid===null && $cid=(int)$_GET['cid'];
        admincp::CP($cid,'d','alert');
        $msg    = '请选择要删除的'.$this->category_name.'!';

        if(empty($this->_array[$cid])) {
            $this->del_content($cid);
            iDB::query("DELETE FROM `#iCMS@__category` WHERE `cid` = '$cid'");
            iDB::query("DELETE FROM `#iCMS@__category_map` WHERE `node` = '$cid' AND `appid` = '".$this->appid."';");
            iDB::query("DELETE FROM `#iCMS@__prop_map` WHERE `iid` = '$cid' AND `appid` = '".iCMS_APP_CATEGORY."' ;");
            $this->del_cahce($cid);
            $msg = '删除成功!';
        }else {
            $msg = '请先删除本'.$this->category_name.'下的子'.$this->category_name.'!';
        }
        $this->do_cache(false);
        $dialog && iPHP::success($msg,'js:parent.$("#'.$cid.'").remove();');
    }
    function do_ajaxtree(){
		$expanded=$_GET['expanded']?true:false;
	 	echo $this->tree((int)$_GET["root"],$expanded);
    }
    function do_cache($dialog=true){
        $this->cache(true,$this->appid);
        $dialog && iPHP::success('更新完成');
    }
    function search_sql($cid,$field='cid'){
        if($cid){
            $cids  = (array)$cid;
            $_GET['sub'] && $cids+=iPHP::app("category")->get_ids($cid,true);
            $sql= iPHP::where($cids,$field);
        }
        return $sql;
    }
    function power_tree($cid=0){
        $li   = '';
        foreach((array)$this->_array[$cid] AS $root=>$C) {
            $li.= '<li>';
            $li.= $this->power_holder($C);
            if($this->_array[$C['cid']]){
                $li.= '<ul>';
                $li.= $this->power_tree($C['cid']);
                $li.= '</ul>';
            }
            $li.= '</li>';
        }
        return $li;
    }
    function power_holder($C) {
        $app_array = array(
            iCMS_APP_ARTICLE =>'<i class="fa fa-file-text"></i>',
            iCMS_APP_TAG     =>'<i class="fa fa-tags"></i>',
            iCMS_APP_PUSH    =>'<i class="fa fa-thumb-tack"></i>',
        );
        $div = '
        <div class="input-prepend input-append li2">
            <span class="add-on">'.$app_array[$C['appid']].'</span>
            <span class="add-on">'.$C['name'].'</span>
            <span class="add-on"><input type="checkbox" name="cpower[]" value="'.$C['cid'].'"> 查询</span>
            <span class="add-on tip" title="添加子'.$this->category_name.'的权限"><input type="checkbox" name="cpower[]" value="'.$C['cid'].':a" /> 添加</span>
            <span class="add-on"><input type="checkbox" name="cpower[]" value="'.$C['cid'].':e" /> 编辑</span>
            <span class="add-on"><input type="checkbox" name="cpower[]" value="'.$C['cid'].':d" /> 删除</span>
        </div>';
        $C['appid']==='1' && $div.= ' <div class="input-prepend input-append li2"><span class="add-on">内容权限</span>
            <span class="add-on"><input type="checkbox" class="checkbox" name="cpower[]" value="'.$C['cid'].':cs" /> 查询</span>
            <span class="add-on"><input type="checkbox" name="cpower[]" value="'.$C['cid'].':ca" /> 添加</span>
            <span class="add-on"><input type="checkbox" name="cpower[]" value="'.$C['cid'].':ce" /> 编辑</span>
            <span class="add-on"><input type="checkbox" name="cpower[]" value="'.$C['cid'].':cd" /> 删除</span>
        </div>';
        return $div;
    }
    public static function tree_unset($C){
        unset($C->rule,
            $C->template,
            $C->description,
            $C->keywords,
            $C->metadata,
            $C->mpic,
            $C->password,
            $C->spic,
            $C->title,
            $C->subname,
            $C->iurl,
            $C->dir,
            $C->hasbody,
            $C->htmlext,
            $C->isexamine,
            $C->issend,
            $C->isucshow,
            $C->comments
        );
        return $C;
    }
    function tree($cid = 0,$expanded=false,$ret=false){
        $html = array();
        $cidArray = (array)$this->_array($cid);
        $CARRAY= (array)$this->get($cidArray,array('categoryAdmincp','tree_unset'));
        foreach($cidArray AS $root=>$_cid) {
            // if(!admincp::CP($C['cid'])){
            //     if($this->_array[$C['cid']]){
            //         $a    = $this->tree($C['cid'],true,true);
            //         $html = array_merge($html,$a);
            //     }
            // }else{
                $C = (array)$CARRAY[$_cid];
                $a = array('id'=>$C['cid'],'data'=>$C);
                if($this->_array($C['cid'])){
                    if($expanded){
                        $a['hasChildren'] = false;
                        $a['expanded']    = true;
                        $a['children']    = $this->tree($C['cid'],$expanded,$ret);
                    }else{
                        $a['hasChildren'] = true;
                    }
                }
                $a && $html[] = $a;
            // }
        }
        if($ret||($expanded && $cid!='source')){
            return $html;
        }

        //var_dump($html);
        return $html?json_encode($html):'[]';
    }

    function check_dir($dir,$appid,$url,$cid=0){
        $sql ="SELECT `dir` FROM `#iCMS@__category` where `dir` ='$dir' AND `appid`='$appid'";
        $cid && $sql.=" AND `cid` !='$cid'";
        iDB::value($sql) && empty($url) && iPHP::alert('该'.$this->category_name.'静态目录已经存在!<br />请重新填写(URL规则设置->静态目录)');
    }

    function recount(){
        $rs = iDB::all("SELECT `cid` FROM `#iCMS@__category` where `appid`='$this->appid'");
        foreach ((array)$rs as $key => $value) {
            $this->update_count($value['cid']);
        }
    }

    function select($permission='',$select_cid="0",$cid="0",$level = 1,$url=false) {
        $cidArray  = (array)$this->_array($cid);
        $CARRAY    = (array)$this->get($cidArray,array('categoryAdmincp','tree_unset'));
        $ROOTARRAY = (array)$this->rootid($cidArray);

        foreach($cidArray AS $root=>$_cid) {
            $C = (array)$CARRAY[$_cid];
            if(admincp::CP($_cid,$permission) && $C['status']) {
                $tag      = ($level=='1'?"":"├ ");
                $selected = ($select_cid==$_cid)?"selected":"";
                $text     = str_repeat("│　", $level-1).$tag.$C['name']."[cid:{$_cid}][pid:{$C['pid']}]".($C['url']?"[∞]":"");
                ($C['url'] && !$url) && $selected ='disabled';
                $option.="<option value='{$_cid}' $selected>{$text}</option>";
            }
            $ROOTARRAY[$_cid] && $option.=$this->select($permission,$select_cid,$C['cid'],$level+1,$url);
        }
        return $option;
    }

    //接口
    function del_content($cid){

    }
    function merge($tocid,$cid){
        iDB::query("UPDATE `#iCMS@__".$this->_app_table."` SET `".$this->_app_cid."` ='$tocid' WHERE `".$this->_app_cid."` ='$cid'");
        iDB::query("UPDATE `#iCMS@__tags` SET `cid` ='$tocid' WHERE `cid` ='$cid'");
        //iDB::query("UPDATE `#iCMS@__push` SET `cid` ='$tocid' WHERE `cid` ='$cid'");
        iDB::query("UPDATE `#iCMS@__prop` SET `cid` ='$tocid' WHERE `cid` ='$cid'");
    }
    function update_count($cid){
        $cc = iDB::value("SELECT count(*) FROM `#iCMS@__".$this->_app_table."` where `".$this->_app_cid."`='$cid'");
        iDB::query("UPDATE `#iCMS@__category` SET `count` ='$cc' WHERE `cid` ='$cid'");
    }
    function batchbtn(){
        return '<li><a data-toggle="batch" data-action="mode"><i class="fa fa-cogs"></i> 访问模式</a></li>
                <li class="divider"></li>
                <li><a data-toggle="batch" data-action="categoryRule"><i class="fa fa-link"></i> '.$this->category_name.'规则</a></li>
                <li><a data-toggle="batch" data-action="contentRule"><i class="fa fa-link"></i> 内容规则</a></li>
                <li><a data-toggle="batch" data-action="urlRule"><i class="fa fa-link"></i> 其它规则</a></li>
                <li class="divider"></li>
                <li><a data-toggle="batch" data-action="indexTPL"><i class="fa fa-columns"></i> 首页模板</a></li>
                <li><a data-toggle="batch" data-action="listTPL"><i class="fa fa-columns"></i> 列表模板</a></li>
                <li><a data-toggle="batch" data-action="contentTPL"><i class="fa fa-columns"></i> 内容模板</a></li>';
    }
}
