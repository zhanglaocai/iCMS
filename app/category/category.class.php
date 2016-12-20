<?php
/**
 * @package iCMS
 * @copyright 2007-2017, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
class category {
    public $category  = array();
    public $_array    = array();
    public $rootid    = array();
    public $rs        = array();
    public $appid_sql = null;

    public function __construct($appid=null) {
        $this->appid = $appid;
        $this->appid !== '-1' && $this->appid_sql=" AND `appid`='$this->appid'";
    }
    public function init($appid=null,$rootid=null) {
        $sql         = "WHERE 1=1";
        $rootid===null OR $sql.=" AND `rootid`='$rootid'";

        $this->rs = iDB::all("SELECT * FROM `#iCMS@__category` {$sql} {$this->appid_sql} ORDER BY `ordernum` ASC");

        foreach((array)$this->rs AS $C) {
            $C['iurl']  = iURL::get('category',$C);
            $this->category[$C['cid']]             = $C;
            $this->_array[$C['rootid']][$C['cid']] = $C;
        }
        foreach ((array)$this->_array as $rootid => $_array) {
            uasort($_array, "order_num");
            $this->_array[$rootid] = $_array;
        }
    }
    public function rootid($rootids) {
        if(empty($rootids)) return array();

        list($is_multi,$rootids)  = iPHP::multi_ids($rootids);

        $sql  = iPHP::where($rootids,'rootid',false,true);
        $data = array();
        $rs   = iDB::all("SELECT `cid`,`rootid` FROM `#iCMS@__category` where {$sql} {$this->appid_sql}",OBJECT);
        if($rs){
            $_count = count($rs);
            for ($i=0; $i < $_count; $i++) {
                if($is_multi){
                    $data[$rs[$i]->rootid][$rs[$i]->cid]= $rs[$i]->cid;
                }else{
                    $data[]= $rs[$i]->cid;
                }
            }
        }
        if(empty($data)){
            return;
        }
        return $data;
    }
    public function rootid_check($rootid="0"){
        $check = iDB::value("SELECT `cid` FROM `#iCMS@__category` where `rootid`='$rootid'");
        return $check?true:false;
    }
    public function get($cids,$callback=null) {
        if(empty($cids)) return array();

        $field = '*';
        if(isset($callback['field'])){
            $field = $callback['field'];
        }

        list($is_multi,$cids)  = iPHP::multi_ids($cids);
        $sql  = iPHP::where($cids,'cid',false,true);
        $data = array();
        $rs   = iDB::all("SELECT {$field} FROM `#iCMS@__category` where {$sql} {$this->appid_sql}",OBJECT);
        if($rs){
            if($is_multi){
                $_count = count($rs);
                for ($i=0; $i < $_count; $i++) {
                    $data[$rs[$i]->cid]= $this->item($rs[$i],$callback);
                }
            }else{
                if(isset($callback['field'])){
                    return $rs[0];
                }else{
                    $data = $this->item($rs[0],$callback);
                }
            }
        }
        if(empty($data)){
            return;
        }
        return $data;
    }
    public function item($category,$callback=null) {
        $category->iurl     = iURL::get('category',(array)$category);
        $category->href     = $category->iurl->href;
        $category->CP_ADD   = admincp::CP($category->cid,'a')?true:false;
        $category->CP_EDIT  = admincp::CP($category->cid,'e')?true:false;
        $category->CP_DEL   = admincp::CP($C->cid,'d')?true:false;
        $category->rule     = json_decode($category->rule);
        $category->template = json_decode($category->template);

        if($callback){
           $category = call_user_func_array($callback,array($category));
        }
        return $category;
    }
    public function cid_array($rootid) {
        $variable = iDB::all("SELECT `cid` FROM `#iCMS@__category` where `rootid`='$rootid' {$this->appid_sql} ORDER BY `ordernum`  ASC",ARRAY_A);
        $category = array();
        foreach ((array)$variable as $key => $value) {
            $category[] = $value['cid'];
        }
        return $category;
    }
    public function domain($cid="0",$akey='dir') {
        $ii       = new stdClass();
        $C        = (array)$this->get($cid,array('field'=>'`rootid`,`dir`,`domain`'));
        $rootid   = $C['rootid'];
        $ii->sdir = $C[$akey];
        if($rootid && empty($C['domain'])) {
            $dm         = $this->domain($rootid);
            $ii->pd     = $dm->pd;
            $ii->domain = $dm->domain;
            $ii->pdir   = $dm->pdir.'/'.$C[$akey];
            $ii->dmpath = $dm->dmpath.'/'.$C[$akey];
        }else {
            $ii->pd     = $ii->pdir   = ltrim(iFS::path(iCMS::$config['router']['html_dir'].$ii->sdir),'/') ;
            $ii->dmpath = $ii->domain = iFS::checkHttp($C['domain'])?$C['domain']:'http://'.$C['domain'];
        }
        return $ii;
    }
    public function domain_array($array){
        $domain    = array();
        $ROOTARRAY = (array)$this->rootid($array);
        foreach ((array)$array as $akey => $cid) {
            $rootData = $ROOTARRAY[$cid];
            if($rootData){
                $domain+=$this->domain_array($rootData);
            }
            $domain[$cid] = $this->domain($cid);
        }
        return $domain;
    }
    public function domain_setting($domain=null){
        if(empty($domain)){
            $rs  = iDB::all("
                SELECT `cid`,`domain`
                FROM `#iCMS@__category`
                WHERE `domain`!='' and `status`='1'");
            foreach((array)$rs AS $C) {
                $domain[$C['domain']] = $C['cid'];
            }
        }
        $setting = iPHP::app('admincp.setting.app');
        $setting->set(array('domain'=>$domain),'category',0,false);
        $setting->cache();
        unset($setting);
    }

    public function cache($one=false) {
        $sql = "WHERE 1=1".$this->appid_sql;
        $rs  = iDB::all("
            SELECT `rootid`,`cid`,`dir`,`status`,`domain`
            FROM `#iCMS@__category`
            WHERE 1=1".$this->appid_sql);

        $hidden = array();
        $_count = count($rs);
        foreach((array)$rs AS $C) {
            $dir2cid[$C['dir']]       = $C['cid'];
            $C['status'] OR $hidden[] = $C['cid'];

            if($C['domain']){
                $domain_array[] = $C['cid'];
            }
            $rootid[$C['rootid']][$C['cid']] = $C['cid'];
            $parent[$C['cid']] = $C['rootid'];
        }

        $domain = $this->domain_array($domain_array);

        iCache::set('iCMS/category/rootid',$rootid,0);
        iCache::set('iCMS/category/dir2cid',$dir2cid,0);
        iCache::set('iCMS/category/hidden', $hidden,0);
        iCache::set('iCMS/category/domain',$domain,0);
        iCache::set('iCMS/category/parent',$parent,0);

        unset($rootid,$parent,$dir2cid,$hidden,$domain,$domain_array,$domaincid,$rs,$C);
        gc_collect_cycles();
        return $_count;
    }
    public function cache_all($offset,$maxperpage) {
        $sql = "WHERE 1=1".$this->appid_sql;
        $ids_array  = iDB::all("
            SELECT `cid`
            FROM `#iCMS@__category` {$sql} ORDER BY cid
            LIMIT {$offset},{$maxperpage};
        ");
        $ids   = iPHP::values($ids_array,'cid');
        $ids   = $ids?$ids:'0';
        $rs  = iDB::all("SELECT * FROM `#iCMS@__category` WHERE `cid` IN({$ids});");
        foreach((array)$rs AS $C) {
            $this->cahce_one($C);
        }
        unset($$rs,$C,$ids_array);
        // gc_collect_cycles();
    }
    public function cahce_one($C=null){
    	if(!is_array($C)){
    		$C = iDB::row("SELECT * FROM `#iCMS@__category` where `cid`='$C' LIMIT 1;",ARRAY_A);
    	}
        $C = $this->data($C);
		iCache::delete('iCMS/category/'.$C['cid']);
		iCache::set('iCMS/category/'.$C['cid'],$C,0);
    }
    public function cahce_del($cid=null){
        if(empty($cid)){
            return;
        }
        iCache::delete('iCMS/category/'.$cid);
    }
    public function data($C){
        if($C['url']){
            $C['iurl']   = array('href'=>$C['url']);
            $C['outurl'] = $C['url'];
        }else{
            $C['iurl'] = (array) iURL::get('category',$C);
        }
        $C['url']  = $C['iurl']['href'];
        $C['link'] = "<a href='{$C['url']}'>{$C['name']}</a>";

        $C['sname']  = $C['subname'];
        $C['subid']  = $this->rootid($C['cid']);
        $C['child']  = $C['subid']?true:false;
        $C['subids'] = implode(',',(array)$C['subid']);
        $C['dirs']   = $this->data_dirs($C['cid']);
        $C['self:appid'] = iCMS_APP_CATEGORY;

        $C = $this->data_pic($C);
        $C = $this->data_parent($C);
        $C = $this->data_nav($C);

        is_string($C['rule'])    && $C['rule']      = json_decode($C['rule'],true);
        is_string($C['template'])&&  $C['template'] = json_decode($C['template'],true);

	    if($C['metadata']){
	    	$mdArray	= array();
	    	$_metadata	= json_decode($C['metadata'],true);
	    	foreach((array)$_metadata as $key => $value){
	    		$mdArray[$key] = $value;
	    	}
	    	$C['metadata'] = $mdArray;
	    }
		return $C;
    }
    public function data_dirs($cid="0") {
        $C = (array)$this->get($cid,array('field'=>'`rootid`,`dir`'));
        $C['rootid'] && $dir.=$this->data_dirs($C['rootid']);
        $dir.='/'.$C['dir'];
        return $dir;
    }
    public function data_pic($C){
        $C['pic']  = is_array($C['pic'])?$C['pic']:get_pic($C['pic']);
        $C['mpic'] = is_array($C['mpic'])?$C['mpic']:get_pic($C['mpic']);
        $C['spic'] = is_array($C['spic'])?$C['spic']:get_pic($C['spic']);
        return $C;
    }
    public function data_parent($C){
        if($C['rootid']){
            $_parent = (array)$this->get($C['rootid']);
            $C['parent'] = $this->data($_parent);
            unset($_parent);
        }
        return $C;
    }
    public function data_nav($C){
        $C['nav'] = '';
        $C['navArray'] = $this->data_nav_array($C);
        krsort($C['navArray']);
        if($C['navArray']){
            foreach ($C['navArray'] as $key => $value) {
                $C['nav'].="<li><a href='{$value['url']}'>{$value['name']}</a><span class=\"divider\">".iPHP::lang('iCMS:navTag')."</span></li>";
            }
        }
        return $C;
    }
    public function data_nav_array($C,&$navArray = array()) {
        if($C) {
            $navArray[] = array(
                'name' => $C['name'],
                'url'  => $C['iurl']['href'],
            );
            if($C['rootid']){
                $rc = (array)$this->get($C['rootid']);
                $rc['iurl'] = (array) iURL::get('category',$rc);
                $this->data_nav_array($rc,$navArray);
            }
        }
        return $navArray;
    }

}
