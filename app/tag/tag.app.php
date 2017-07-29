<?php
/**
* iCMS - i Content Management System
* Copyright (c) 2007-2017 iCMSdev.com. All rights reserved.
*
* @author icmsdev <master@icmsdev.com>
* @site https://www.icmsdev.com
* @licence https://www.icmsdev.com/LICENSE.html
*/
class tagApp {
    public $methods = array('iCMS');
    public function __construct() {}
    public function API_iCMS(){
        return $this->do_iCMS();
    }
    public function do_iCMS($a = null) {
        if ($_GET['name']) {
            $name  = iSecurity::encoding($_GET['name']);
            $val   = iSecurity::escapeStr($name);
            $field = 'name';
        } elseif ($_GET['tkey']) {
            $field = 'tkey';
            $val   = iSecurity::escapeStr($_GET['tkey']);
        } elseif ($_GET['id']) {
            $field = 'id';
            $val   = (int)$_GET['id'];
        }else{
            iPHP::error_404('标签请求出错', 30001);
        }
        return $this->tag($val, $field);
    }
    public static function hooked(&$data){
        iPHP::hook('tag',$data,iCMS::$config['hooks']['tag']);
    }
    public function tag($val, $field = 'name', $tpl = 'tag') {
        $val OR iPHP::error_404('TAG不能为空', 30002);
        is_array($val) OR $tag = iDB::row("SELECT * FROM `#iCMS@__tag` where `$field`='$val' AND `status`='1'  LIMIT 1;", ARRAY_A);

        if(empty($tag)){
            if($tpl){
                iPHP::error_404('找不到标签: <b>'.$field.':'. $val.'</b>', 30003);
            }else{
                return false;
            }
        }
        $tag = $this->value($tag);
        $tag+=(array)apps_meta::data('tag',$tag['id']);

        $app = apps::get_app('tag');
        $app['fields'] && formerApp::data($tag['id'],$app,'tag',$tag,$vars,$tag['category']);

        self::hooked($tag);

        $tag['param'] = array(
            "appid" => $tag['appid'],
            "iid"   => $tag['id'],
            "cid"   => $tag['cid'],
            "suid"  => $tag['uid'],
            "title" => $tag['name'],
            "url"   => $tag['url']
        );

        iView::set_iVARS($tag['iurl'],'iURL');

        if ($tpl) {
            $tag_tpl = $tag['category']['template']['tag'];
            $tag_tpl OR $tag_tpl = $tag['tag_category']['template']['tag'];
            $tag_tpl OR $tag_tpl = iCMS::$config['tag']['tpl'];
            $tag_tpl OR $tag_tpl = '{iTPL}/tag.htm';

            iView::assign('app', apps::get_app_lite($app));
            iView::assign('category',$tag['category']);
            iView::assign('tag_category',$tag['tag_category']);
            unset($tag['category'],$tag['tag_category']);
            iView::assign("tag", $tag);
            if (strstr($tpl, '.htm')) {
                return iView::render($tpl, 'tag');
            }
            $view = iView::render($tag_tpl,'tag');
            if($view) return array($view,$tag);
        }else{
            return $tag;
        }
    }
    public static function value($tag,$vars=null) {
        if($tag['cid']){
            $category        = categoryApp::category($tag['cid'],false);
            $tag['category'] = categoryApp::get_lite($category);
        }
        if($tag['tcid']){
            $tag_category        = categoryApp::category($tag['tcid'],false);
            $tag['tag_category'] = categoryApp::get_lite($tag_category);
        }

        $tag['iurl'] = (array)iURL::get('tag', array($tag, $category, $tag_category));
        if($vars['url']=='self'){
            $fkey = 'tids';
            $vars['field'] && $fkey = $vars['field'];
            $nurl = iURL::make(array($fkey=>$tag['id']),null);
            $tag['iurl']['href'] = $nurl;
            $tag['iurl']['url'] = $nurl;
            foreach ($tag['iurl'] as $key => $value) {
                is_array($value) && $tag['iurl'][$key]['url'] = $nurl;
            }
        }
        $tag['url'] OR $tag['url'] = $tag['iurl']['href'];
        $tag['link']  = '<a href="'.$tag['url'].'" class="tag" target="_blank">'.$tag['name'].'</a>';

        if($category['mode'] && stripos($tag['url'], '.php?')===false){
            iURL::page_url($tag['iurl']);
        }
        $tag['related']  && $tag['relArray'] = explode(',', $tag['related']);
        $tag['appid'] = iCMS_APP_TAG;
        $tag['pic']  = filesApp::get_pic($tag['pic']);
        $tag['bpic'] = filesApp::get_pic($tag['bpic']);
        $tag['mpic'] = filesApp::get_pic($tag['mpic']);
        $tag['spic'] = filesApp::get_pic($tag['spic']);
        return $tag;
    }
    public static function get_array(&$rs=array(),$fname=null,$key='tags',$value=null,$id='id') {
            $rs[$key.'_fname'] = $fname;
            $value===null && $value = $rs[$key];
            if ($value) {
                $multi_tag = self::multi_tag(array($rs[$id]=>$value),$key);
                $rs+=(array)$multi_tag[$rs[$id]];
            }
            if(is_array($rs[$key.'_array'])){
                $tags_fname = array_slice ($rs[$key.'_array'],0,1);
                $rs[$key.'_fname'] = $tags_fname[0]['name'];
            }
            unset($multi_tag, $tags_fname);
    }

    public static function all($array) {
        if(empty($array)){
            return;
        }
        $sql  = iSQL::in($array,'name',false,true);

        if(empty($sql)){
            return;
        }
        $rs = iDB::all("SELECT * FROM `#iCMS@__tag` where {$sql} AND `status`='1'", ARRAY_A);
        foreach ((array)$rs as $key => $tag) {
            $tag && $tagArray[$tag['id']] = self::value($tag);
        }
        return $tagArray;
    }
    public static function multi_tag($tags=null,$tkey='tags'){
        if(empty($tags)) return array();

        if(!is_array($tags) && strpos($tags, ',') !== false){
            $tags = explode(',', $tags);
        }
        $multi = array();
        foreach ($tags as $id => $value) {
            if($value){
                $a = explode(',', $value);
                foreach ($a as $ak => $av) {
                    $tMap[$av][] = 't:'.$id; //self::map 中array_merge 必需以字符串合并 才不会重建索引
                    $tArray[$av] = $av;
                }
            }
        }
        if($tArray){
            $tagArray = self::all($tArray);
            $tagArray = self::map($tagArray,$tMap);
            $tagArray = self::tpl_var($tagArray,$tkey);
            return $tagArray;
        }
        return false;
    }
    private static function tpl_var($array,$tkey){
        $tArray = array();
        foreach ((array) $array AS $iid => $tag) {
            $iid = substr($iid, 2);
            foreach ($tag as $key => $value) {
                $tArray[$iid][$tkey.'_array'][$value['id']] = $value;
                $tArray[$iid][$tkey.'_link'].= $value['link'];
            }
        }
        return $tArray;
    }
    private static function map($tagArray,$tMap){
        $array = array();
        foreach ((array)$tagArray as $tid => $tag) {
            $iidArray = $tMap[$tag['name']];
            if(is_array($iidArray)){
                $a = array_fill_keys($iidArray,array($tid=>$tag));
                $array = array_merge_recursive($array,$a);
                unset($a);
            }
        }
        return $array;
    }

}
