<?php
/**
 * @package iCMS
 * @copyright 2007-2015, iDreamSoft
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
        $cid = (int)$_GET['cid'];
        $dir = iS::escapeStr($_GET['dir']);
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


        // if($category['password']){
        //     $category_auth        = iPHP::get_cookie('category_auth_'.$id);
        //     list($ca_cid,$ca_psw) = explode('#=iCMS!=#',authcode($category_auth,'DECODE'));
        // 	if($ca_psw!=md5($category['password'])){
        // 		iPHP::assign('forward',__REF__);
	       //  	iPHP::view('{iTPL}/category.password.htm','category.password');
	       //  	exit;
        // 	}
        // }
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
    public function get_lite($C){
        $C['iurl'] OR $C['iurl'] = (array)iURL::get('category',$C);

        $category                = array();
        $category['name']        = $C['name'];
        $category['description'] = $C['description'];
        $category['subname']     = $C['subname'];
        $category['sname']       = $C['subname'];
        // $category['pic']         = $C['pic'];
        $category['navArray']    = $this->get_nav($C);
        $category['url']         = $C['iurl']['href'];
        $category['link']        = "<a href='{$C['url']}'>{$C['name']}</a>";
        $category['pic']         = is_array($C['pic'])?$C['pic']:get_pic($C['pic']);
        $category['mpic']        = is_array($C['mpic'])?$C['mpic']:get_pic($C['mpic']);
        $category['spic']        = is_array($C['spic'])?$C['spic']:get_pic($C['spic']);

        if($C['rootid']){
            $_parent            = iCache::get('iCMS/category/'.$C['rootid']);
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
