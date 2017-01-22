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
    public $sql_appid = null;

    public function __construct($appid=null) {
        $this->appid = $appid;
        $this->appid===null OR $this->sql_appid=" AND `appid`='$this->appid'";
    }

    public function rootid($rootids=null) {
        if($rootids===null) return array();

        list($rootids,$is_multi)  = iSQL::multi_var($rootids);

        $sql  = iSQL::in($rootids,'rootid',false,true);
        $sql OR $sql = '1 = 1';
        $data = array();
        $rs   = iDB::all("SELECT `cid`,`rootid` FROM `#iCMS@__category` where {$sql} {$this->sql_appid}",OBJECT);
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

        list($cids,$is_multi)  = iSQL::multi_var($cids);
        $sql  = iSQL::in($cids,'cid',false,true);
        $sql OR $sql = '1 = 1';
        $data = array();
        $rs   = iDB::all("SELECT {$field} FROM `#iCMS@__category` where {$sql} {$this->sql_appid}",OBJECT);
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
    public function cid_array($rootid=null,$where=null) {
        $sql = " 1=1 ";
        $rootid===null OR $sql.= " AND `rootid`='$rootid'";
        $sql.= iSQL::where($where,true);
        $variable = iDB::all("SELECT `cid` FROM `#iCMS@__category` WHERE {$sql} {$this->sql_appid} ORDER BY `sortnum`  ASC",ARRAY_A);
        $category = array();
        foreach ((array)$variable as $key => $value) {
            $category[] = $value['cid'];
        }
        return $category;
    }
    public function cache($one=false) {
        $rs  = iDB::all("SELECT * FROM `#iCMS@__category` WHERE 1=1".$this->sql_appid.' ORDER BY `sortnum`  ASC');
        $hidden = array();
        foreach((array)$rs AS $C) {
            $this->cahce_one($C);

            if($C['domain']){
                $domain_array[] = $C['cid'];
            }
            $C['status'] OR $hidden[]        = $C['cid'];
            $dir2cid[$C['dir']]              = $C['cid'];
            $parent[$C['cid']]               = $C['rootid'];
            $rootid[$C['rootid']][$C['cid']] = $C['cid'];
            $app[$C['appid']][$C['cid']]     = $C['cid'];
        }

        foreach ((array)$app as $appid => $value) {
            iCache::set('category/appid.'.$appid,$value,0);
        }
        iCache::set('category/rootid',$rootid,0);
        iCache::set('category/dir2cid',$dir2cid,0);
        iCache::set('category/hidden', $hidden,0);
        iCache::set('category/parent',$parent,0);
        iCache::set('category/domain',$this->domain_array($domain_array),0);

        foreach((array)$rs AS $C) {
            $C = $this->data($C);
            $this->cahce_one($C,'C');
        }

        unset(
            $rootid,$parent,$dir2cid,
            $hidden,$domain_array,
            $app,$rs,$C
        );
        gc_collect_cycles();
    }
    public static function cache_get($cid="0") {
        $data = iCache::get('category/'.$cid);
        return $data;
    }
    public function cahce_one($C=null,$fix=null){
        if(!is_array($C)){
            $C = iDB::row("SELECT * FROM `#iCMS@__category` where `cid`='$C' LIMIT 1;",ARRAY_A);
        }
        iCache::set('category/'.$fix.$C['cid'],$C,0);
    }

    public function cache_all($offset,$maxperpage) {
        $sql = "WHERE 1=1".$this->sql_appid;
        $ids_array  = iDB::all("
            SELECT `cid`
            FROM `#iCMS@__category` {$sql} ORDER BY cid
            LIMIT {$offset},{$maxperpage};
        ");
        $ids   = iSQL::values($ids_array,'cid');
        $ids   = $ids?$ids:'0';
        $rs  = iDB::all("SELECT * FROM `#iCMS@__category` WHERE `cid` IN({$ids});");
        foreach((array)$rs AS $C) {
            $C = $this->data($C);
            $this->cahce_one($C,'C');
        }
        unset($$rs,$C,$ids_array);
    }
    public function cahce_del($cid=null){
        if(empty($cid)){
            return;
        }
        iCache::delete('category/'.$cid);
    }

    public function domain($cid="0",$akey='dir') {
        $ii       = new stdClass();
        $C        = (array)$this->cache_get($cid);
        $rootid   = $C['rootid'];
        $ii->sdir = $C[$akey];
        if($rootid && empty($C['domain'])) {
            $dm         = $this->domain($rootid);
            $ii->pd     = $dm->pd;
            $ii->domain = $dm->domain;
            $ii->pdir   = $dm->pdir.'/'.$C[$akey];
            $ii->dmpath = $dm->dmpath.'/'.$C[$akey];
        }else {
            $ii->pd     = $ii->pdir   = ltrim(iFS::path(iCMS::$config['router']['dir'].$ii->sdir),'/') ;
            $ii->dmpath = $ii->domain = iFS::checkHttp($C['domain'])?$C['domain']:'http://'.$C['domain'];
        }
        return $ii;
    }
    public function domain_array($array){
        $domain    = array();
        $ROOTARRAY = iCache::get('category/rootid');
        foreach ((array)$array as $akey => $cid) {
            $rootData = $ROOTARRAY[$cid];
            if($rootData){
                $domain+=$this->domain_array($rootData);
            }
            $domain[$cid] = $this->domain($cid);
        }
        return $domain;
    }
    public static function domain_config($domain=null){
        if(empty($domain)){
            $rs  = iDB::all("
                SELECT `cid`,`domain`
                FROM `#iCMS@__category`
                WHERE `domain`!='' and `status`='1'");
            foreach((array)$rs AS $C) {
                $domain[$C['domain']] = $C['cid'];
            }
        }
        configAdmincp::set(array('domain'=>$domain),'category',0,false);
        configAdmincp::cache();
    }
    public function data($C){
        if($C['url']){
            $C['iurl']   = array('href'=>$C['url']);
            $C['outurl'] = $C['url'];
        }else{
            $C['iurl'] = (array) iURL::get('category',$C);
        }
        $C['url']    = $C['iurl']['href'];
        $C['link']   = "<a href='{$C['url']}'>{$C['name']}</a>";
        $C['sname']  = $C['subname'];
        $C['subid']  = iCache::get('category/rootid',$C['cid']);

        $C['child']  = $C['subid']?true:false;
        $C['subids'] = implode(',',(array)$C['subid']);
        $C['dirs']   = $this->data_dirs($C['cid']);
        $C['self:appid'] = iCMS_APP_CATEGORY;

        $C = $this->data_pic($C);
        $C = $this->data_parent($C);
        $C = $this->data_nav($C);

        is_string($C['rule'])    && $C['rule']     = json_decode($C['rule'],true);
        is_string($C['template'])&& $C['template'] = json_decode($C['template'],true);
	    is_string($C['metadata'])&& $C['metadata'] = metadata($C['metadata']);

		return $C;
    }
    public function data_dirs($cid="0") {
        $C = $this->cache_get($cid);
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
        $C['rootid'] && $C['parent'] = $this->data($this->cache_get($C['rootid']));
        return $C;
    }
    public function data_nav($C){
        $C['nav'] = '';
        $C['navArray'] = $this->data_nav_array($C);
        krsort($C['navArray']);
        if($C['navArray']){
            foreach ($C['navArray'] as $key => $value) {
                $C['nav'].="<li><a href='{$value['url']}'>{$value['name']}</a><span class=\"divider\">".iUI::lang('iCMS:navTag')."</span></li>";
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
                $rc = (array)$this->cache_get($C['rootid']);
                $rc['iurl'] = (array) iURL::get('category',$rc);
                $this->data_nav_array($rc,$navArray);
            }
        }
        return $navArray;
    }

}
