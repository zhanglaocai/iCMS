<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
class category {
    public $category = array();
    public $_array   = array();
    public $rootid   = array();
    public $rs       = array();

    public function __construct() {}

    public function init($appid=null) {
        $this->appid = $appid;
        $sql         = "WHERE `appid`='$this->appid'";
        $this->appid === '-1' && $sql='';
        $this->rs    = iDB::all("SELECT * FROM `#iCMS@__category` {$sql} ORDER BY `ordernum` , `cid` ASC");

        foreach((array)$this->rs AS $C) {
            $C['iurl']  = iURL::get('category',$C);
            $this->category[$C['cid']] = $C;
            $this->parent[$C['cid']]   = $C['rootid'];
            $this->_array[$C['rootid']][$C['cid']] = $C;
            $this->rootid[$C['rootid']][$C['cid']] = $C['cid'];
        }
        foreach ((array)$this->_array as $rootid => $_array) {
            uasort($_array, "order_num");
            $this->_array[$rootid] = $_array;
        }
    }
    public function cache($one=false,$appid=null) {
        $this->init($appid);

        $hidden = array();
        foreach((array)$this->rs AS $C) {
	        $C = $this->data($C);
			$one && $this->cahce_one($C);

            $appidArray[$C['appid']]  = $C['appid'];
            $dir2cid[$C['dir']]       = $C['cid'];
            $C['status'] OR $hidden[] = $C['cid'];

            $cache[$C['appid']][$C['cid']]               = $C;
            $array[$C['appid']][$C['rootid']][$C['cid']] = $C;

            if($C['domain']){
                $domain_array[]          = $C['cid'];
                $domaincid[$C['domain']] = $C['cid'];
            }
    	}

    	if($appid===null){
	    	foreach((array)$appidArray AS $_appid) {
		        iCache::set('iCMS/category.'.$_appid.'/cache',$cache[$_appid],0);
		        iCache::set('iCMS/category.'.$_appid.'/array',$array[$_appid],0);
	    	}
    	}else{
	        iCache::set('iCMS/category.'.$appid.'/cache',$cache[$appid],0);
	        iCache::set('iCMS/category.'.$appid.'/array',$array[$appid],0);
    	}
        $domain = $this->domain_array($domain_array);
        $this->domain_setting($domaincid);

        iCache::set('iCMS/category/rootid',	$this->rootid,0);
        iCache::set('iCMS/category/parent',	$this->parent,0);
        iCache::set('iCMS/category/dir2cid',$dir2cid,0);
        iCache::set('iCMS/category/hidden', $hidden,0);
        iCache::set('iCMS/category/domain',$domain,0);
    }
    public function del_cahce($cid=null){
        if(empty($cid)){
            return;
        }
        iCache::delete('iCMS/category/'.$cid);
    }
    public function domain_array($array){
        $domain = array();
        foreach ((array)$array as $akey => $cid) {
            $rootData = $this->rootid[$cid];
            if($rootData){
                $domain+=$this->domain_array($rootData);
            }
            $domain[$cid] = $this->domain($cid);
        }
        return $domain;
    }
    public function domain_setting($domain){
        $setting = iPHP::app('admincp.setting.app');
        $setting->set(array('domain'=>$domain),'category',0,false);
        $setting->cache();
        unset($setting);
    }

    public function domain($cid="0",$akey='dir') {
        $ii       = new stdClass();
        $C        = $this->category[$cid];
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
    public function cahce_one($C=null){
    	if(!is_array($C)){
    		$C = iDB::row("SELECT * FROM `#iCMS@__category` where `cid`='$C' LIMIT 1;",ARRAY_A);
			$C = $this->data($C);
    	}
		iCache::delete('iCMS/category/'.$C['cid']);
		iCache::set('iCMS/category/'.$C['cid'],$C,0);
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
        $C['subid']  = $this->rootid[$C['cid']];
        $C['child']  = $C['subid']?true:false;
        $C['subids'] = implode(',',(array)$C['subid']);
        $C['dirs']   = $this->data_dirs($C['cid']);
        $C['self:appid'] = iCMS_APP_CATEGORY;

        $C = $this->data_pic($C);
        $C = $this->data_parent($C);
        $C = $this->data_nav($C);

        $C['rule']     = json_decode($C['rule'],true);
        $C['template'] = json_decode($C['template'],true);

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
        $C = $this->category[$cid];
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
            $_parent = $this->category[$C['rootid']];
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
                $rc = $this->category[$C['rootid']];
                $rc['iurl'] = (array) iURL::get('category',$rc);
                $this->data_nav_array($rc,$navArray);
            }
        }
        return $navArray;
    }
    public function rootid($cid="0"){
    	$rootid = $this->parent[$cid];
    	return $rootid?$this->rootid($rootid):$cid;
    }
    public function update_count_one($cid,$math='+'){
        $math=='-' && $sql = " AND `count`>0";
        iDB::query("UPDATE `#iCMS@__category` SET `count` = count".$math."1 WHERE `cid` ='$cid' {$sql}");
    }

}
