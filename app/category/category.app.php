<?php
/**
 * @package iCMS
 * @copyright 2007-2016, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
define('CACHE_CATEGORY_ID',        'iCMS/category/');
define('CACHE_CATEGORY_DIR2CID',   'iCMS/category/dir2cid');
define('CACHE_CATEGORY_ROOTID',    'iCMS/category/rootid');

class categoryApp{
	public $methods	= array('iCMS','category');
    public function __construct($appid = iCMS_APP_ARTICLE) {
    	$this->appid = iCMS_APP_ARTICLE;
    	$appid && $this->appid = $appid;
    	$_GET['appid'] && $this->appid	= (int)$_GET['appid'];
    }
    public function do_iCMS($tpl = 'index') {
        $cid = (int)$_GET['cid'];
        $dir = iSecurity::escapeStr($_GET['dir']);
		if(empty($cid) && $dir){
			$cid = iCache::get(CACHE_CATEGORY_DIR2CID,$dir);
            $cid OR iPHP::throw404('运行出错！找不到该栏目<b>dir:'.$dir.'</b> 请更新栏目缓存或者确认栏目是否存在', 20002);
		}
    	return $this->category($cid,$tpl);
    }

    public function API_iCMS(){
        return $this->do_iCMS();
    }

    public function category($id,$tpl='index') {
        $category = iCache::get(CACHE_CATEGORY_ID.$id);
        if(empty($category) && $tpl){
            iPHP::throw404('运行出错！找不到该栏目<b>cid:'. $id.'</b> 请更新栏目缓存或者确认栏目是否存在', 20001);
        }
        if($category['status']==0) return false;
        $iurl = $category['iurl'];
        if($tpl){
            if(iPHP::$iVIEW=="html" && (strstr($category['rule']['index'],'{PHP}')||$category['outurl']||!$category['mode']) ) return false;
            $category['outurl'] && iPHP::redirect($category['outurl']);
            $category['mode']=='1' && iCMS::gotohtml($iurl['path'],$iurl['href']);
        }

        if($category['hasbody']){
           $category['body'] = iCache::get(CACHE_CATEGORY_ID.$category['cid'].'.body');
           $category['body'] && $category['body'] = stripslashes($category['body']);
        }

        $category['param'] = array(
            "sappid" => $category['sappid'],
            "appid"  => $category['appid'],
            "iid"    => $category['cid'],
            "cid"    => $category['rootid'],
            "suid"   => $category['userid'],
            "title"  => $category['name'],
            "url"    => $category['url']
        );

        if($tpl) {
            $category['mode'] && iPHP::set_page_url($iurl);

            iPHP::assign('category',$category);
            if(isset($_GET['tpl'])){
                $tpl = iSecurity::escapeStr($_GET['tpl']);
                if(strpos($tpl, '..') !== false){
                    exit('what the fuck!!');
                }else{
                    $tpl = $tpl.'.htm';
                }
            }
            if(strpos($tpl, '.htm')!==false){
            	return iPHP::view($tpl,'category');
            }
            $GLOBALS['page']>1 && $tpl='list';
            $html = iPHP::view($category['template'][$tpl],'category.'.$tpl);
            if(iPHP::$iVIEW=="html") return array($html,$category);
        }else{
        	return $category;
        }
    }
    public function get_lite($category){
        $keyArray = array(
            'sortnum','password','mode','domain',
            'isexamine','issend','isucshow',
            'pubdate'
        );
        foreach ($keyArray as $i => $key) {
            if(is_array($category[$key])){
                $category[$key] = $this->get_lite($category[$key]);
            }else{
                unset($category[$key]);
            }
        }
        return $category;
    }
    public function get_cids($cid = "0",$all=true,$root_array=null) {
        $root_array OR $root_array = iCache::get(CACHE_CATEGORY_ROOTID);
        $cids = array();
        is_array($cid) OR $cid = explode(',', $cid);
        foreach($cid AS $_id) {
            $cids+=(array)$root_array[$_id];
        }
        if($all){
            foreach((array)$cids AS $_cid) {
                $root_array[$_cid] && $cids+= $this->get_cids($_cid,$all,$root_array);
            }
        }
        $cids = array_unique($cids);
        $cids = array_filter($cids);
        return $cids;
    }
}
