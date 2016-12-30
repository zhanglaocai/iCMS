<?php
/**
 * @package iCMS
 * @copyright 2007-2017, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
class keywordsApp {
    public $methods = array('iCMS');
    public function do_iCMS(){
    }
    public function API_iCMS(){
    }
    //内链
    public static function run($content) {
        if (iCMS::$config['keywords']['limit'] == 0) {
            return $content;
        }
        $search  = iCache::get('iCMS/keywords.search');
        $replace = iCache::get('iCMS/keywords.replace');
        if($search && $replace){
            return self::replace($search, $replace,stripslashes($content),iCMS::$config['keywords']['limit']);
        }
        return $content;
    }
    public static function replace($search, $replace, $subject, $limit=-1) {
        preg_match_all ("/<a[^>]*?>(.*?)<\/a>/si", $subject, $matches);//链接不替换
        $linkArray  = array_unique($matches[0]);
        $linkArray && $linkflip = array_flip($linkArray);
        foreach((array)$linkflip AS $linkHtml=>$linkkey){
            $linkA[$linkkey]='###iCMS_LINK_'.rand(1,1000).'_'.$linkkey.'###';
        }
        $subject = str_replace($linkArray,$linkA,$subject);

        preg_match_all ("/<[\/\!]*?[^<>]*?>/si", $subject, $matches);
        $htmArray   = (array)array_unique($matches[0]);
        $htmArray && $htmflip = array_flip($htmArray);
        foreach((array)$htmflip AS $kHtml=>$vkey){
            $htmA[$vkey]="###iCMS_HTML_".rand(1,1000).'_'.$vkey.'###';
        }
        $subject = str_replace($htmArray,$htmA,$subject);

        // constructing mask(s)...
        if (is_array($search)) {
            foreach ($search as $k=>$v) {
                $search[$k] = '`' . preg_quote($search[$k],'`') . '`i';
            }
        }else {
            $search = '`' . preg_quote($search,'`') . '`';
        }
        // replacement
        $replace && $replaceflip = array_flip($replace);
        foreach((array)$replaceflip AS $rk=>$replacekey){
            $replaceA[$replacekey]="###iCMS_REPLACE_".rand(1,1000).'_'.$replacekey.'###';
        }
        // replacement
        $subject = preg_replace($search, $replaceA, $subject, $limit);
        $subject = str_replace($replaceA,$replace,$subject);
//        $subject = preg_replace($search, $replace, $subject, $limit);
        $subject = str_replace($htmA,$htmArray,$subject);
        $subject = str_replace($linkA,$linkArray,$subject);
        return $subject;
    }
}
