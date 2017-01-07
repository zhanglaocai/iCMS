<?php
/**
* iCMS - i Content Management System
* Copyright (c) 2007-2012 idreamsoft.com iiimon Inc. All rights reserved.
*
* @author coolmoo <idreamsoft@qq.com>
* @site http://www.idreamsoft.com
* @licence http://www.idreamsoft.com/license.php
* @version 6.0.0
*/
class filterApp{
    public function __construct() {
        $this->appid = iCMS_APP_FILTER;
    }
    //禁止
    public static function HOOK_disable(&$content){
        $disable = iCache::get('iCMS/filter.disable');  //disable禁止
        //禁止关键词
        $subject = $content;
        $pattern = '/(~|`|!|@|\#|\$|%|\^|&|\*|\(|\)|\-|=|_|\+|\{|\}|\[|\]|;|:|"|\'|<|>|\?|\/|,|\.|\s|\n|。|，|、|；|：|？|！|…|-|·|ˉ|ˇ|¨|‘|“|”|々|～|‖|∶|＂|＇|｀|｜|〃|〔|〕|〈|〉|《|》|「|」|『|』|．|〖|〗|【|】|（|）|［|］|｛|｝|°|′|″|＄|￡|￥|‰|％|℃|¤|￠|○|§|№|☆|★|○|●|◎|◇|◆|□|■|△|▲|※|→|←|↑|↓|〓|＃|＆|＠|＾|＿|＼|№|)*/i';
        $subject = preg_replace($pattern, '', $subject);
        foreach ((array)$disable AS $val) {
            $val = trim($val);
            if(strpos($val,'::')!==false){
                list($tag,$start,$end) = explode('::',$val);
                if($tag=='NUM'){
                    $subject = cnum($subject);
                    if (preg_match('/\d{'.$start.','.$end.'}/i', $subject)) {
                        return $val;
                    }
                }
            }else{
                if ($val && preg_match("/".preg_quote($val, '/')."/i", $subject)) {
                    return $val;
                }
            }
        }
    }
    //过滤
    public static function HOOK_filter($content){
        $filter  = iCache::get('iCMS/filter.array');    //filter过滤
        if($filter){
            //过滤关键词
            foreach ((array)$filter AS $k =>$val) {
                $val = trim($val);
                if($val){
                    $exp = explode("=", $val);
                    empty($exp[1]) && $exp[1] = '***';
                    $search[$k]  = '/'.preg_quote($exp[0], '/').'/i';
                    $replace[$k] = $exp[1];
                }

            }
            $search && $content = preg_replace($search,$replace,$content);
        }
        return $content;
    }
}
