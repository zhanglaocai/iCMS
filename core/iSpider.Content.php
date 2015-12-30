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

class spiderContent extends spider{

    /**
     * 抓取资源
     * @param  [string] $html      [抓取结果]
     * @param  [array] $data      [数据项]
     * @param  [array] $rule      [规则]
     * @param  [array] $responses [已经抓取资源]
     * @return [array]           [返回处理结果]
     */
    public static function crawl($html,$data,$rule,$responses) {
        if(trim($data['rule'])===''){
            return '';
        }
        $name = $data['name'];
        if (spider::$dataTest) {
            print_r('<b>['.$name.']规则:</b>'.iS::escapeStr($data['rule']));
            echo "<hr />";
        }
        /**
         * 在数据项里调用之前采集的数据[DATA@name][DATA@name.key]
         */
        if(strpos($data['rule'], '[DATA@')!==false){
            $html = spiderTools::getDATA($responses,$data['rule']);
        }
        /**
         * 在数据项里调用之前采集的数据RULE@规则id@@url
         */
        if(strpos($data['rule'], 'RULE@')!==false){
            var_dump($data['rule'],$html);
            list(spider::$rid,$_urls) = explode('@@', str_replace('RULE@', '',$data['rule']));
            var_dump(spider::$rid,$_urls);
            empty($_urls) && $_urls = trim($html);
            var_dump($_urls);
            exit;
            if (spider::$dataTest) {
                print_r('<b>使用[rid:'.spider::$rid.']规则抓取</b>:'.$_urls);
                echo "<hr />";
            }
            return spiderUrls::crawl('DATA@RULE',false,spider::$rid,$_urls);
        }
        /**
         * RAND@10,0
         * 返回随机数
         */
        if(strpos($data['rule'], 'RAND@')!==false){
            $random = str_replace('RAND@', '',$data['rule']);
            list($length,$numeric) = explode(',', $random);
            return random($length, empty($numeric)?0:1);
        }
        $contentArray       = array();
        $contentHash        = array();
        $_content           = null;
        $_content           = spiderContent::match($html,$data,$rule);
        $cmd5               = md5($_content);
        $contentArray[]     = $_content;
        $contentHash[$cmd5] = true;

        if ($data['page']) {
            if(empty($rule['page_url'])){
                $rule['page_url'] = $rule['list_url'];
            }
            if (empty(spider::$allHtml)) {
                $page_url_array = array();
                $page_area_rule = trim($rule['page_area_rule']);
                if($page_area_rule){
                    if(strpos($page_area_rule, 'DOM::')!==false){
                        iPHP::import(iPHP_LIB.'/phpQuery.php');
                        $doc      = phpQuery::newDocumentHTML($html,'UTF-8');
                        $pq_dom   = str_replace('DOM::','', $page_area_rule);
                        $pq_array = phpQuery::pq($pq_dom);
                        foreach ($pq_array as $pn => $pq_val) {
                            $href = phpQuery::pq($pq_val)->attr('href');
                            if($href){
                                if($rule['page_url_rule']){
                                    if(strpos($rule['page_url_rule'], '<%')!==false){
                                        $page_url_rule = spiderTools::pregTag($rule['page_url_rule']);
                                        if (!preg_match('|' . $page_url_rule . '|is', $href)){
                                            continue;
                                        }
                                    }else{
                                        $cleanhref = spiderTools::dataClean($rule['page_url_rule'],$href);
                                        if($cleanhref){
                                            $href = $cleanhref;
                                            unset($cleanhref);
                                        }else{
                                            continue;
                                        }
                                    }
                                }
                                $href = str_replace('<%url%>',$href, $rule['page_url']);
                                $page_url_array[$pn] = spiderTools::url_complement($rule['__url__'],$href);
                            }
                        }
                        phpQuery::unloadDocuments($doc->getDocumentID());
                    }else{
                        $page_area_rule = spiderTools::pregTag($page_area_rule);
                        if ($page_area_rule) {
                            preg_match('|' . $page_area_rule . '|is', $html, $matches, $PREG_SET_ORDER);
                            $page_area = $matches['content'];
                        } else {
                            $page_area = $html;
                        }
                        if($rule['page_url_rule']){
                            $page_url_rule = spiderTools::pregTag($rule['page_url_rule']);
                            preg_match_all('|' .$page_url_rule. '|is', $page_area, $page_url_matches, PREG_SET_ORDER);
                            foreach ($page_url_matches AS $pn => $row) {
                                $href = str_replace('<%url%>', $row['url'], $rule['page_url']);
                                $page_url_array[$pn] = spiderTools::url_complement($rule['__url__'],$href);
                                gc_collect_cycles();
                            }
                        }
                        unset($page_area);
                    }
                }else{ // 逻辑方式
                    if($rule['page_url_parse']=='<%url%>'){
                        $page_url = str_replace('<%url%>',$rule['__url__'],$rule['page_url']);
                    }else{
                        $page_url_rule = spiderTools::pregTag($rule['page_url_parse']);
                        preg_match('|' . $page_url_rule . '|is', $rule['__url__'], $matches, $PREG_SET_ORDER);
                        $page_url = str_replace('<%url%>', $matches['url'], $rule['page_url']);
                    }
                    if (stripos($page_url,'<%step%>') !== false){
                        for ($pn = $rule['page_no_start']; $pn <= $rule['page_no_end']; $pn = $pn + $rule['page_no_step']) {
                            $page_url_array[$pn] = str_replace('<%step%>', $pn, $page_url);
                            gc_collect_cycles();
                        }
                    }
                }
                //URL去重清理
                if($page_url_array){
                    $page_url_array = array_filter($page_url_array);
                    $page_url_array = array_unique($page_url_array);
                    $puk = array_search($rule['__url__'],$page_url_array);
                    if($puk!==false){
                        unset($page_url_array[$puk]);
                    }
                }

                if (spider::$dataTest) {
                    echo "<b>内容页网址:</b>".$rule['__url__'] . "<br />";
                    echo "<b>分页:</b>".$rule['page_url'] . "<br />";
                    echo iS::escapeStr($page_url_rule);
                    echo "<hr />";
                }
                if(spider::$dataTest){
                    echo "<b>分页列表:</b><pre>";
                    print_r($page_url_array);
                    echo "</pre><hr />";
                }

                spider::$content_right_code = trim($rule['page_url_right']);
                spider::$content_error_code = trim($rule['page_url_error']);
                spider::$curl_proxy = $rule['proxy'];

                $pageurl = array();

                foreach ($page_url_array AS $pukey => $purl) {
                    //usleep(100);
                    $phtml = spiderTools::remote($purl);
                    if (empty($phtml)) {
                        break;
                    }
                    $md5 = md5($phtml);
                    if($pageurl[$md5]){
                        break;
                    }
                    $check_content = spiderTools::check_content_code($phtml);
                    if ($check_content === false) {
                        unset($check_content,$phtml);
                        break;
                    }

                    $_content = spiderContent::match($phtml,$data,$rule);
                    $cmd5     = md5($_content);
                    if($contentHash[$cmd5]){
                        break;
                    }
                    $contentArray[]  = $_content;
                    $contentHash[$cmd5]    = true;
                    $pageurl[$md5]         = $purl;
                    spider::$allHtml[$md5] = $phtml;
                }
                gc_collect_cycles();
                unset($check_content,$phtml);

                if (spider::$dataTest) {
                    echo "<b>最终分页列表:</b><pre>";
                    print_r($pageurl);
                    echo "</pre><hr />";
                }
            }else{
                foreach ((array)spider::$allHtml as $ahkey => $phtml) {
                    $contentArray[] = spiderContent::match($phtml,$data,$rule);
                }
            }
        }
        $content = implode('#--iCMS.PageBreak--#', $contentArray);
        $html    = null;
        unset($html,$contentArray,$contentHash,$_content);
        $content = stripslashes($content);
        if (spider::$dataTest) {
            print_r('<b>['.$name.']匹配结果:</b>'.htmlspecialchars($content));
            echo "<hr />";
        }
        if ($data['cleanbefor']) {
            $content = spiderTools::dataClean($data['cleanbefor'], $content);
        }

        if ($data['cleanhtml']) {
            $content = stripslashes($content);
            $content = preg_replace('/<[\/\!]*?[^<>]*?>/is', '', $content);
        }
        if ($data['format'] && $content) {
            $content = autoformat($content);
        }

        if ($data['img_absolute'] && $content) {
            // $content = stripslashes($content);
            preg_match_all("/<img.*?src\s*=[\"|'](.*?)[\"|']/is", $content, $img_match);
            if($img_match[1]){
                $_img_array = array_unique($img_match[1]);
                $_img_urls  = array();
                foreach ((array)$_img_array as $_img_key => $_img_src) {
                    $_img_urls[$_img_key] = spiderTools::url_complement($rule['__url__'],$_img_src);
                }
               $content = str_replace($_img_array, $_img_urls, $content);
            }
            unset($img_match,$_img_array,$_img_urls,$_img_src);
        }
        if ($data['trim']) {
            $content = trim($content);
        }
        if ($data['capture']) {
            // $content = stripslashes($content);
            $content = spiderTools::remote($content);
        }
        if ($data['download']) {
            // $content = stripslashes($content);
            $content = iFS::http($content);
        }

        if ($data['cleanafter']) {
            $content = spiderTools::dataClean($data['cleanafter'], $content);
            // $content = stripslashes($content);
        }
        if ($data['autobreakpage']) {
            $content = spiderTools::autoBreakPage($content);
        }
        if ($data['mergepage']) {
            $content = spiderTools::mergePage($content);
        }
        if ($data['empty'] && empty($content)) {
            $emptyMsg = '['.$name.']规则设置了不允许为空.当前抓取结果为空!请检查,规则是否正确!';
            if(spider::$dataTest){
                exit('<h1>'.$emptyMsg.'</h1>');
            }
            if(spider::$work){
                echo "\n{$emptyMsg}\n";
                return false;
            }else{
                iPHP::alert($emptyMsg);
            }
        }
        if ($data['json_decode']) {
            $content = json_decode($content,true);
        }
        if (spider::$callback['content'] && is_callable(spider::$callback['content'])) {
            $content = call_user_func_array(spider::$callback['content'],array($content));
        }

        if($data['array']){
            if(strpos($content, '#--iCMS.PageBreak--#')!==false){
                $content = explode('#--iCMS.PageBreak--#', $content);
            }
            return (array)$content;
        }

        return $content;
    }
    public static function match($html,$data,$rule){
        $match_hash = array();
        if($data['dom']){
            iPHP::import(iPHP_LIB.'/phpQuery.php');
            spider::$dataTest && $_GET['pq_debug'] && phpQuery::$debug =1;
            $doc = phpQuery::newDocumentHTML($html,'UTF-8');
            if(strpos($data['rule'], '@')!==false){
                list($content_dom,$content_attr) = explode("@", $data['rule']);
                $content_fun = 'attr';
            }else{
                list($content_dom,$content_fun,$content_attr) = explode("\n", $data['rule']);
            }
            $content_dom  = trim($content_dom);
            $content_fun  = trim($content_fun);
            $content_attr = trim($content_attr);
            $content_fun OR $content_fun = 'html';
            if ($data['multi']) {
                $conArray = array();
                $_content = null;
                foreach ($doc[$content_dom] as $doc_key => $doc_value) {
                    if($content_attr){
                        $_content = phpQuery::pq($doc_value)->$content_fun($content_attr);
                    }else{
                        $_content = phpQuery::pq($doc_value)->$content_fun();
                    }
                    $cmd5 = md5($_content);
                    if($match_hash[$cmd5]){
                        break;
                    }
                    if ($data['trim']) {
                        $_content = trim($_content);
                    }
                    $conArray[$doc_key]  = $_content;
                    $match_hash[$cmd5] = true;
                }
                if (spider::$dataTest) {
                    echo "<b>多条匹配结果:</b><pre>";
                    print_r($match_hash);
                    echo "</pre><hr />";
                }
                $content = implode('#--iCMS.PageBreak--#', $conArray);
                unset($conArray,$_content,$match_hash);
            }else{
                if($content_attr){
                    $content = $doc[$content_dom]->$content_fun($content_attr);
                }else{
                    $content = $doc[$content_dom]->$content_fun();
                }
            }

            phpQuery::unloadDocuments($doc->getDocumentID());
            unset($doc);
        }else{
            if(trim($data['rule'])=='<%content%>'){
                $content = $html;
            }else{
                $data_rule = spiderTools::pregTag($data['rule']);
                if (preg_match('/(<\w+>|\.\*|\.\+|\\\d|\\\w)/i', $data_rule)) {
                    if ($data['multi']) {
                        preg_match_all('|' . $data_rule . '|is', $html, $matches, PREG_SET_ORDER);
                        $conArray = array();
                        foreach ((array) $matches AS $mkey => $mat) {
                            $cmd5 = md5($mat['content']);
                            if($match_hash[$cmd5]){
                                break;
                            }
                            if ($data['trim']) {
                                $mat['content'] = trim($mat['content']);
                            }
                            $conArray[$mkey]     = $mat['content'];
                            $match_hash[$cmd5] = true;
                        }
                        if (spider::$dataTest) {
                            echo "<b>多条匹配结果:</b><pre>";
                            print_r($match_hash);
                            echo "</pre><hr />";
                        }
                        $content = implode('#--iCMS.PageBreak--#', $conArray);
                        unset($conArray,$match_hash);
                    } else {
                        preg_match('|' . $data_rule . '|is', $html, $matches, $PREG_SET_ORDER);
                        $content = $matches['content'];
                    }
                } else {
                    $content = $data_rule;
                }
            }
        }
        return $content;
    }
}
