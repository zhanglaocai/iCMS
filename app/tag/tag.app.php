<?php

/**
 * @package iCMS
 * @copyright 2007-2017, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
class tagApp {
    public $methods = array('iCMS');
    public function __construct() {}
    public function API_iCMS(){
        return $this->do_iCMS();
    }
    public function do_iCMS($a = null) {
        if ($_GET['name']) {
            $name   = $_GET['name'];
            $encode = mb_detect_encoding($name, array("ASCII","UTF-8","GB2312","GBK","BIG5"));
            if(strtoupper($encode)!='UTF-8'){
                if (function_exists('iconv')) {
                    $name  = iconv($encode,'UTF-8//IGNORE', $name);
                } elseif (function_exists('mb_convert_encoding')) {
                    $name  = mb_convert_encoding($name,'UTF-8//IGNORE',$encode);
                }
            }
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

    public function tag($val, $field = 'name', $tpl = 'tag') {
        $val OR iPHP::error_404('TAG不能为空', 30002);
        is_array($val) OR $tag = iDB::row("SELECT * FROM `#iCMS@__tags` where `$field`='$val' AND `status`='1'  LIMIT 1;", ARRAY_A);

        if(empty($tag)){
            if($tpl){
                iPHP::error_404('找不到标签: <b>'.$field.':'. $val.'</b>', 30003);
            }else{
                return false;
            }
        }
        $tag = $this->value($tag);
        $tag['param'] = array(
            "appid" => $tag['appid'],
            "iid"   => $tag['id'],
            "cid"   => $tag['cid'],
            "suid"  => $tag['uid'],
            "title" => $tag['name'],
            "url"   => $tag['url']
        );

        if ($tpl) {
            $tag_tpl = $tag['category']['template']['tag'];
            $tag_tpl OR $tag_tpl = $tag['tag_category']['template']['tag'];
            $tag_tpl OR $tag_tpl = iCMS::$config['tag']['tpl'];
            $tag_tpl OR $tag_tpl = '{iTPL}/tag.index.htm';

            iPHP::assign('category',$tag['category']);
            iPHP::assign('tag_category',$tag['tag_category']);
            unset($tag['category'],$tag['tag_category']);
            iPHP::assign("tag", $tag);
            if (strstr($tpl, '.htm')) {
                return iPHP::view($tpl, 'tag');
            }
            $html = iPHP::view($tag_tpl,'tag');
            if(iPHP::$iVIEW=="html") return array($html,$tag);
        }else{
            return $tag;
        }
    }
    public static function value($tag) {
        if($tag['cid']){
            $category        = categoryApp::category($tag['cid'],false);
            $tag['category'] = categoryApp::get_lite($category);
        }
        if($tag['tcid']){
            $tag_category        = categoryApp::category($tag['tcid'],false);
            $tag['tag_category'] = categoryApp::get_lite($tag_category);
        }

        $tag['iurl'] = iURL::get('tag', array($tag, $category, $tag_category));
        $tag['url'] OR $tag['url'] = $tag['iurl']->href;
        $tag['link']  = '<a href="'.$tag['url'].'" class="tag" target="_blank">'.$tag['name'].'</a>';

        if($category['mode'] && stripos($tag['url'], '.php?')===false){
            iPHP::set_page_url($tag['iurl']);
        }
        $tag['metadata'] && $tag['meta'] = json_decode($tag['metadata']);
        $tag['related']  && $tag['relArray'] = explode(',', $tag['related']);
        $tag['appid'] = iCMS_APP_TAG;
        $tag['pic']  = get_pic($tag['pic']);
        $tag['bpic'] = get_pic($tag['bpic']);
        $tag['mpic'] = get_pic($tag['mpic']);
        $tag['spic'] = get_pic($tag['spic']);
        return $tag;
    }
    public function get_array($tags) {
        if(empty($tags)){
            return;
        }
        $array  = explode(',', $tags);
        foreach ($array as $key => $tag) {
            $tag && $tag_array[$key] = $this->tag($tag,'name',false);
        }
        return $tag_array;
    }
    public static function multi($array) {
        if(empty($array)){
            return;
        }
        $sql  = iSQL::where($array,'name',false,true);

        if(empty($sql)){
            return;
        }
        $rs = iDB::all("SELECT * FROM `#iCMS@__tags` where {$sql} AND `status`='1'", ARRAY_A);
        foreach ((array)$rs as $key => $tag) {
            $tag && $tagArray[$tag['id']] = self::value($tag);
        }
        return $tagArray;
    }
    public static function multi_tag($tags=0){
        if(empty($tags)) return array();

        if(!is_array($tags) && strpos($tags, ',') !== false){
            $tags = explode(',', $tags);
        }
        $multi = array();
        foreach ($tags as $aid => $value) {
            if($value){
                $a = explode(',', $value);
                foreach ($a as $ak => $av) {
                    $tMap[$av] = $aid;
                    $tArray[]  = $av;
                }
            }
        }
        $tagArray = self::multi($tArray);
        $tagArray = self::map($tagArray,$tMap);
        $tagArray = self::app_tag($tagArray);
        return $tagArray;
    }
    public static function app_tag($array,$aid=null){
        $tArray = array();
        foreach ((array) $array AS $_aid => $tag) {
            if(isset($tag['id'])){
                $aid===null && $aid = $_aid;
                $tArray[$aid]['tags_array'][$tag['id']] = $tag;
                $tArray[$aid]['tags_link'].= $tag['link'];
            }else{
                $tArray+=(array)self::app_tag($tag,$_aid);
            }
        }
        return $tArray;
    }
    public static function map($tagArray,$tMap){
        $array = array();
        $map   = $tMap;
        foreach ((array)$tagArray as $tid => $tag) {
            $aid = $tMap[$tag['name']];
            unset($map[$tag['name']]);
            $akey = array_search($aid, $map);
            $map = $tMap;
            if($akey){
                $array[$aid][$tid]= $tag;
            }else{
                $array[$aid] = $tag;
            }
        }
        return $array;
    }

}
