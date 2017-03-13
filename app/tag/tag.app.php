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
    public function hooked($data){
        return iPHP::hook('tag',$data,iCMS::$config['hooks']['tag']);
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
        $tag = $this->hooked($tag);

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
            $tag_tpl OR $tag_tpl = '{iTPL}/tag.htm';

            iView::assign('category',$tag['category']);
            iView::assign('tag_category',$tag['tag_category']);
            unset($tag['category'],$tag['tag_category']);
            iView::assign("tag", $tag);
            if (strstr($tpl, '.htm')) {
                return iView::render($tpl, 'tag');
            }
            $html = iView::render($tag_tpl,'tag');
            if(iView::$gateway=="html") return array($html,$tag);
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
        $sql  = iSQL::in($array,'name',false,true);

        if(empty($sql)){
            return;
        }
        $rs = iDB::all("SELECT * FROM `#iCMS@__tags` where {$sql} AND `status`='1'", ARRAY_A);
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
                    $tMap[$av] = $id;
                    $tArray[]  = $av;
                }
            }
        }
        $tagArray = self::multi($tArray);
        $tagArray = self::map($tagArray,$tMap);
        $tagArray = self::tags($tagArray,$tkey);
        return $tagArray;
    }
    private static function tags($array,$tkey,$id=null){
        $tArray = array();
        foreach ((array) $array AS $_id => $tag) {
            if(isset($tag['id'])){
                $id===null && $id = $_id;
                $tArray[$id][$tkey.'_array'][$tag['id']] = $tag;
                $tArray[$id][$tkey.'_link'].= $tag['link'];
            }else{
                $tArray+=(array)self::tags($tag,$tkey,$_id);
            }
        }
        return $tArray;
    }
    private static function map($tagArray,$tMap){
        $array = array();
        $map   = $tMap;
        foreach ((array)$tagArray as $tid => $tag) {
            $id = $tMap[$tag['name']];
            unset($map[$tag['name']]);
            $akey = array_search($id, $map);
            $map = $tMap;
            if($akey){
                $array[$id][$tid]= $tag;
            }else{
                $array[$id] = $tag;
            }
        }
        return $array;
    }

}
