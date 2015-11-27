<?php
/**
* iCMS - i Content Management System
* Copyright (c) 2007-2012 idreamsoft.com iiimon Inc. All rights reserved.
*
* @author coolmoo <idreamsoft@qq.com>
* @site http://www.idreamsoft.com
* @licence http://www.idreamsoft.com/license.php
* @version 6.0.0
* @$Id: spider.app.php 634 2013-04-03 06:02:53Z coolmoo $
*/
defined('iPHP') OR exit('What are you doing?');

class spiderData extends spider{

    public static function crawl() {
        ini_get('safe_mode') OR set_time_limit(0);
        $sid = spider::$sid;
        if ($sid) {
            $sRs   = iDB::row("SELECT * FROM `#iCMS@__spider_url` WHERE `id`='$sid' LIMIT 1;");
            $title = $sRs->title;
            $cid   = $sRs->cid;
            $pid   = $sRs->pid;
            $url   = $sRs->url;
            $rid   = $sRs->rid;
       } else {
            $rid   = spider::$rid;
            $pid   = spider::$pid;
            $title = spider::$title;
            $url   = spider::$url;
        }

        if($pid){
            $project        = spider::project($pid);
            $prule_list_url = $project['list_url'];
        }

        $ruleA           = spider::rule($rid);
        $rule            = $ruleA['rule'];
        $dataArray       = $rule['data'];

        if($prule_list_url){
            $rule['list_url']   = $prule_list_url;
        }

        if (spider::$dataTest) {
            echo "<b>抓取规则信息</b><pre>";
            print_r(iS::escapeStr($ruleA));
            print_r(iS::escapeStr($project));
            echo "</pre><hr />";
        }

        spider::$curl_proxy = $rule['proxy'];
        $responses = array();
        $html      = spiderTools::remote($url);
        if(empty($html)){
            $msg = '错误:001..采集 ' . $url . '文件内容为空!请检查采集规则';
            if(spider::$work=='shell'){
                echo "{$msg}\n";
                return false;
            }else{
                iPHP::alert($msg);
            }
        }

//      $http   = spider::check_content_code($html);
//
//      if($http['match']==false){
//          return false;
//      }
//      $content        = $http['content'];
        spider::$allHtml = "";
        $rule['__url__']    = spider::$url;
        $responses['reurl'] = spider::$url;
        $responses['title'] = $title;
        foreach ((array)$dataArray AS $key => $data) {

            $content_html = $html;
            $dname = $data['name'];

            /**
             * [DATA:name]
             * 把之前[name]处理完的数据当作原始数据
             * 如果之前有数据会叠加
             * 用于数据多次处理
             * @var string
             */
            if (strpos($dname,'DATA:')!== false){
                $_dname = str_replace('DATA:', '', $dname);
                $content_html = $responses[$_dname];
                unset($responses[$dname]);
            }
            /**
             * [PRE:name]
             * 把PRE:name采集到的数据 当做原始数据
             * 一般用于下载内容
             * @var string
             */
            $pre_dname = 'PRE:'.$dname;
            if(isset($responses[$pre_dname])){
                $content_html = $responses[$pre_dname];
                unset($responses[$pre_dname]);
            }
            /**
             * [EMPTY:name]
             * 如果[name]之前抓取结果数据为空使用这个数据项替换
             * @var string
             */
            if (strpos($dname,'EMPTY:')!== false){
                $_dname = str_replace('EMPTY:', '', $dname);
                if(empty($responses[$_dname])){
                    $dname = $_dname;
                }else{
                    //有值不执行抓取
                    continue;
                }
            }
            $content = spiderContent::crawl($content_html,$data,$rule,$responses);

            unset($content_html);

            /**
             * [name.xxx]
             * 采集内容做为数组
             */
            if (strpos($dname,'.')!== false){
                $f_key = substr($dname,0,stripos($dname, "."));
                $s_key = substr(strrchr($dname, "."), 1);
                if(isset($responses[$f_key][$s_key])){
                    if(is_array($responses[$f_key][$s_key])){
                        $responses[$f_key][$s_key] = array_merge($responses[$f_key][$s_key],$content);
                    }else{
                        $responses[$f_key][$s_key].= $content;
                    }
                }else{
                    $responses[$f_key][$s_key] = $content;
                }
            }else{
                /**
                 * 多个name 内容合并
                 */
                if(isset($responses[$dname])){
                    if(is_array($responses[$dname])){
                        $responses[$dname] = array_merge($responses[$dname],$content);
                    }else{
                        $responses[$dname].= $content;
                    }
                }else{
                    $responses[$dname] = $content;
                }
            }
            /**
             * 对匹配多条的数据去重过滤
             */
            if(!is_array($responses[$dname]) && $data['multi']){
                if(strpos($responses[$dname], ',')!==false){
                    $_dnameArray = explode(',', $responses[$dname]);
                    $dnameArray  = array();
                    foreach ((array)$_dnameArray as $key => $value) {
                        $value = trim($value);
                        $value && $dnameArray[]=$value;
                    }
                    $dnameArray = array_filter($dnameArray);
                    $dnameArray = array_unique($dnameArray);
                    $responses[$dname] = implode(',', $dnameArray);
                    unset($dnameArray,$_dnameArray);
                }
            }

            gc_collect_cycles();
        }

        spider::$allHtml = null;
        unset($html);

        gc_collect_cycles();

        if (spider::$dataTest) {
            echo "<pre style='width:99%;word-wrap: break-word;'>";
            print_r(iS::escapeStr($responses));
            echo "</pre><hr />";
        }

        iFS::$CURLOPT_ENCODING        = '';
        iFS::$CURLOPT_REFERER         = '';
        iFS::$watermark_config['pos'] = iCMS::$config['watermark']['pos'];
        iFS::$watermark_config['x']   = iCMS::$config['watermark']['x'];
        iFS::$watermark_config['y']   = iCMS::$config['watermark']['y'];
        iFS::$watermark_config['img'] = iCMS::$config['watermark']['img'];

        $rule['fs']['encoding'] && iFS::$CURLOPT_ENCODING = $rule['fs']['encoding'];
        $rule['fs']['referer']  && iFS::$CURLOPT_REFERER  = $rule['fs']['referer'];
        if($rule['watermark_mode']){
            iFS::$watermark_config['pos'] = $rule['watermark']['pos'];
            iFS::$watermark_config['x']   = $rule['watermark']['x'];
            iFS::$watermark_config['y']   = $rule['watermark']['y'];
            $rule['watermark']['img'] && iFS::$watermark_config['img'] = $rule['watermark']['img'];
        }

        return $responses;
    }
}
