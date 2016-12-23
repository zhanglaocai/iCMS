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
    public function do_iCMS(){
        $this->do_config();
    }
    public function do_config(){
        $setting = admincp::app('setting');
        $setting->app('999999');
    }
    public function do_save_config(){
        $filter  = explode("\n",$_POST['config']['filter']);
        $disable = explode("\n",$_POST['config']['disable']);
        $_POST['config']['filter']  = array_unique($filter);
        $_POST['config']['disable'] = array_unique($disable);

        $setting = admincp::app('setting');
        $setting->save('999999',null,array($this,'cache'));
    }
    public function cache($config=null){
        if($config===null){
            $setting = admincp::app('setting');
            $config  = $setting->app('999999',null,true);
        }
    	iCache::set('iCMS/filter.array',$config['filter'],0);
    	iCache::set('iCMS/filter.disable',$config['disable'],0);
    }
    //过滤
    public function run(&$content){
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
    }
}
