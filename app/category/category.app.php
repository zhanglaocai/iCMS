<?php
/**
 * @package iCMS
 * @copyright 2007-2016, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 * @$Id: category.app.php 2412 2014-05-04 09:52:07Z coolmoo $
 */
class categoryApp{
	public $methods	= array('iCMS','category');
    public function __construct($appid = iCMS_APP_ARTICLE) {
    	$this->appid = iCMS_APP_ARTICLE;
    	$appid && $this->appid = $appid;
    	$_GET['appid'] && $this->appid	= (int)$_GET['appid'];
    }
    public function do_iCMS($tpl = 'index') {
        $cid    = (int)$_GET['cid'];
        $domain = iS::escapeStr($_GET['domain']);
        $dir    = iS::escapeStr($_GET['dir']);
		if(empty($cid) && $dir){
			$cid = iCache::get('iCMS/category/dir2cid',$dir);
            $cid OR iPHP::throw404('运行出错！找不到该栏目<b>dir:'.$dir.'</b> 请更新栏目缓存或者确认栏目是否存在', 20002);
		}
    	return $this->category($cid,$tpl);
    }
    public function API_iCMS(){
        return $this->do_iCMS();
    }

    public function category($id,$tpl='index') {
        $category = iCache::get('iCMS/category/'.$id);
        if(empty($category) && $tpl){
            iPHP::throw404('运行出错！找不到该栏目<b>cid:'. $id.'</b> 请更新栏目缓存或者确认栏目是否存在', 20001);
        }
        if($category['status']==0) return false;

        $iurl = iURL::get('category',$category);

        if($tpl){
            if(iPHP::$iTPL_MODE=="html"&&
                (
                    strstr($category['contentRule'],'{PHP}')
                    ||$category['outurl']
                    ||empty($category['mode'])
                )
            ) {return false;}

            $category['url'] && iPHP::gotourl($category['url']);
            $category['mode']=='1' && iCMS::gotohtml($iurl->path,$iurl->href);
        }
        $category['iurl']   = (array)$iurl;
        $category['subid']  = iCache::get('iCMS/category/rootid',$id);
        $category['subids'] = implode(',',(array)$category['subid']);
        $category  = array_merge($category,$this->get_lite($category));

        if($category['hasbody']){
           $category['body'] = iCache::get('iCMS/category/'.$category['cid'].'.body');
           $category['body'] && $category['body'] = stripslashes($category['body']);
        }
        $category['appid']  = iCMS_APP_CATEGORY;
        $category['param'] = array(
            "appid" => $category['appid'],
            "iid"   => $category['cid'],
            "cid"   => $category['rootid'],
            "suid"  => $category['userid'],
            "title" => $category['name'],
            "url"   => $category['url']
        );

        if($tpl) {
            $category['mode'] && iCMS::set_html_url($iurl);
            iCMS::hooks('enable_comment',true);
            iPHP::assign('category',$category);
            if(isset($_GET['tpl'])){
                $tpl = iS::escapeStr($_GET['tpl']);
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
            $html = iPHP::view($category[$tpl.'TPL'],'category.'.$tpl);
            if(iPHP::$iTPL_MODE=="html") return array($html,$category);
        }else{
        	return $category;
        }
    }
    public function get_nav($C,&$navArray = array()) {
        if($C) {
            $iurl       = (array)$C['iurl'];
            $navArray[] = array(
                'name' => $C['name'],
                'url'  => $iurl['href'],
            );
            if($C['rootid']){
                $rc = iCache::get('iCMS/category/'.$C['rootid']);
                $rc['iurl'] = (array)iURL::get('category',$rc);
                $this->get_nav($rc,$navArray);
            }
        }
        return $navArray;
    }
    public function get_lite($category){
        $category['iurl'] OR $category['iurl'] = (array)iURL::get('category',$category);
        $category['sname']    = $category['subname'];
        $category['navArray'] = $this->get_nav($category);
        $category['url']      = $category['iurl']['href'];
        $category['link']     = "<a href='{$category['url']}'>{$category['name']}</a>";
        $category['pic']      = is_array($category['pic'])?$category['pic']:get_pic($category['pic']);
        $category['mpic']     = is_array($category['mpic'])?$category['mpic']:get_pic($category['mpic']);
        $category['spic']     = is_array($category['spic'])?$category['spic']:get_pic($category['spic']);

        if($category['rootid']){
            $_parent = iCache::get('iCMS/category/'.$category['rootid']);
            $category['parent'] = $this->get_lite($_parent);
            unset($_parent);
        }

        $category['nav'] = '';
        krsort($category['navArray']);
        if($category['navArray']){
            foreach ($category['navArray'] as $key => $value) {
                $category['nav'].="<li><a href='{$value['url']}'>{$value['name']}</a><span class=\"divider\">".iPHP::lang('iCMS:navTag')."</span></li>";
            }
        }
        return $category;
    }
}
