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

class spiderTools extends spider{
    public static $listArray = array();

    /**
     * 在数据项里调用之前采集的数据[DATA@name][DATA@name.key]
     */
    public static function getDATA($responses,$content){
        preg_match_all('#\[DATA@(.*?)\]#is', $content,$data_match);
        $_data_replace = array();
        foreach ((array)$data_match[1] as $_key => $_name) {
            $_nameKeys = explode('.', $_name);
            $_content  = $responses[$_nameKeys[0]];
            if(count($_nameKeys)>1) foreach ((array)$_nameKeys as $kk => $nk) {
                $kk && $_content = $_content[$nk];
            }
            $_data_replace[$_key]=$_content;
        }
        if($_data_replace){
            if(count($data_match[0])>1||!is_array($_data_replace[0])){
                $content = str_replace($data_match[0], $_data_replace, $content);
            }else{
                $content = $_data_replace[0];
            }
        }
        unset($data_match,$_data_replace,$_content);
        return $content;
    }
    public static function domAttr($DOM,$selectors,$fun='text'){
        $selectors = str_replace('DOM::','',$selectors);
        list($selector,$attr) = explode("@", $selectors);
        if($attr){
            return $DOM[$selector]->attr($attr);
        }else{
            return $DOM[$selector]->$fun();
        }
    }
    public static function title_url($row,$rule,$baseUrl=null){
        spiderTools::$listArray = array();
        $responses = array();
        if(strpos($rule['list_url_rule'], '<%url%>')!==false){
            $responses = $row;
        }else if(is_object($row)){
            $list_url_rule = explode("\n", $rule['list_url_rule']);
            $DOM       = phpQuery::pq($row);
            $keyMap = array('title','url');
            foreach ($list_url_rule as $key => $value) {
                $dom_rule = trim($value);
                if(strpos($dom_rule, '@@')!==false){
                    list($dom_key,$dom_rule) = explode("@@", $dom_rule);
                }else{
                    $dom_key = $keyMap[$key];
                }
                $content = '';
                if(strpos($dom_rule, 'DOM::')!==false){
                    $content = spiderTools::domAttr($DOM,$dom_rule);
                }else{
                    if($dom_key=='url'){
                        $dom_rule OR $dom_rule = 'href';
                    }
                    if($dom_key=='title'){
                        $dom_rule OR $dom_rule = 'text';
                    }
                    if($dom_rule=='text'){
                        $content = $DOM->text();
                    }else{
                        $content = $DOM->attr($dom_rule);
                    }
                }
                $responses[$dom_key] = str_replace('&nbsp;','',trim($content));
            }
            unset($DOM);
        }
        $title = $responses['title'];
        $url   = $responses['url'];
        $title = trim($title);
        $url   = trim($url);
        $url   = str_replace('<%url%>',$url, $rule['list_url']);
        if(strpos($url, 'AUTO::')!==false && $baseUrl){
            $url = str_replace('AUTO::','',$url);
            $url = spiderTools::url_complement($baseUrl,$url);
        }
        $rule['list_url_clean'] && $url = spiderTools::dataClean($rule['list_url_clean'],$url);
        $title = preg_replace('/<[\/\!]*?[^<>]*?>/is', '', $title);

        unset($responses['title'],$responses['url']);
        if($responses){
            foreach ($responses as $key => $value) {
                if(!is_numeric($key) && strpos($key, 'var_')===false){
                    spiderTools::$listArray[$key] = $value;
                }
            }
            unset($responses);
        }
        return array($title,$url);
    }

    public static function pregTag($rule) {
        $rule = trim($rule);
        if(empty($rule)){
            return false;
        }
        $rule = str_replace("%>", "%>\n", $rule);
        preg_match_all("/<%(.+)%>/i", $rule, $matches);
        $pregArray = array_unique($matches[0]);
        $pregflip = array_flip($pregArray);

        foreach ((array)$pregflip AS $kpreg => $vkey) {
            $pregA[$vkey] = "###iCMS_PREG_" . rand(1, 1000) . '_' . $vkey . '###';
        }
        $rule = str_replace($pregArray, $pregA, $rule);
        $rule = preg_quote($rule, '|');
        $rule = str_replace($pregA, $pregArray, $rule);
        $rule = str_replace("%>\n", "%>", $rule);
        $rule = preg_replace('|<%(\w{3,20})%>|i', '(?<\\1>.*?)', $rule);
        $rule = str_replace(array('<%', '%>'), '', $rule);
        unset($pregArray,$pregflip,$matches);
        gc_collect_cycles();
        return $rule;
    }
    public static function dataClean($rules, $content) {
        iPHP::import(iPHP_LIB.'/phpQuery.php');
        $ruleArray = explode("\n", $rules);
        foreach ($ruleArray AS $key => $rule) {
            $rule = trim($rule);
            $rule = str_replace('<BR>', "\n", $rule);
            if(strpos($rule, 'BEFOR::')!==false){
              $rule = str_replace('BEFOR::','', $rule);
              $content = $rule.$content;
              continue;
            }
            if(strpos($rule, 'AFTER::')!==false){
              $rule = str_replace('AFTER::','', $rule);
              $content = $rule.$content;
              continue;
            }
            if(strpos($rule, 'CUT::')!==false){
              $len = str_replace('CUT::','', $rule);
              $content = csubstr($content,$len);
              continue;
            }
            if(strpos($rule, '<%SELF%>')!==false){
              $content = str_replace('<%SELF%>',$content, $rule);
              continue;
            }

            list($_pattern, $_replacement) = explode("==", $rule);
            $_pattern     = trim($_pattern);
            $_replacement = trim($_replacement);
            $_replacement = str_replace('\n', "\n", $_replacement);
            if(strpos($_pattern, 'NEED::')!==false){
                $need = str_replace('NEED::','', $_pattern);
                if(strpos($content,$need)===false){
                    return false;
                }
            }
            if(strpos($_pattern, 'NOT::')!==false){
                $not = str_replace('NOT::','', $_pattern);
                if(strpos($content,$not)!==false){
                    return false;
                }
            }
            if(strpos($_pattern, 'LEN::')!==false){
                $len        = str_replace('LEN::','', $_pattern);
                $len_content = preg_replace(array('/<[\/\!]*?[^<>]*?>/is','/\s*/is'),'',$content);
                if(cstrlen($len_content)<$len){
                    return false;
                }
            }
            if(strpos($_pattern, 'IMG::')!==false){
                $img_count = str_replace('IMG::','', $_pattern);
                preg_match_all("/<img.*?src\s*=[\"|'](.*?)[\"|']/is", $content, $match);
                $img_array  = array_unique($match[1]);
                if(count($img_array)<$img_count){
                    return false;
                }
            }

            if(strpos($_pattern, 'DOM::')!==false){
                iPHP::import(iPHP_LIB.'/phpQuery.php');
                $doc      = phpQuery::newDocumentHTML($content,'UTF-8');
                //echo 'dataClean:getDocumentID:'.$doc->getDocumentID()."\n";
                $_pattern = str_replace('DOM::','', $_pattern);
                list($pq_dom, $pq_fun,$pq_attr) = explode("::", $_pattern);
                $pq_array = phpQuery::pq($pq_dom);
                foreach ($pq_array as $pq_key => $pq_val) {
                    if($pq_fun){
                        if($pq_attr){
                            $pq_content = phpQuery::pq($pq_val)->$pq_fun($pq_attr);
                        }else{
                            $pq_content = phpQuery::pq($pq_val)->$pq_fun();
                        }
                    }else{
                        $pq_content = (string)phpQuery::pq($pq_val);
                    }
                    $pq_pattern[$pq_key]     = $pq_content;
                    $pq_replacement[$pq_key] = $_replacement;
                }
                phpQuery::unloadDocuments($doc->getDocumentID());
                //var_dump(array_map('htmlspecialchars', $pq_pattern));
                $content = str_replace($pq_pattern,$pq_replacement, $content);
                unset($doc,$pq_array);
            }else{
                if($_pattern=='~SELF~'){
                    $_pattern = $content;
                }
                if(strpos($_replacement, '~SELF~')!==false){
                    $_replacement = str_replace('~SELF~',$content, $_replacement);
                }
                if(strpos($_replacement, '~S~')!==false){
                    $_replacement = str_replace('~S~',' ', $_replacement);
                }

                $replacement[$key] = $_replacement;
                $pattern[$key] = '|' . self::pregTag($_pattern) . '|is';
            }
        }
        if($pattern){
            return preg_replace($pattern, $replacement, $content);
        }else{
            return $content;
        }
    }
    public static function charsetTrans($html,$content_charset,$encode, $out = 'UTF-8') {
        if (spider::$dataTest || spider::$ruleTest) {
            echo '<b>规则设置编码:</b>'.$encode . '<br />';
        }

        $encode == 'auto' && $encode = null;
        /**
         * 检测http返回的编码
         */
        if($content_charset){
            $content_charset = rtrim($content_charset,';');
            if(empty($encode)){
                $encode = $content_charset;
            }else if(strtoupper($encode)!=strtoupper($content_charset)){
                $encode = $content_charset;
            }
            if(strtoupper($encode)==$out){
                if (spider::$dataTest || spider::$ruleTest) {
                    echo '<b>检测页面编码:</b>'.$encode . '<br />';
                }
                return $html;
            }
        }
        /**
         * 检测页面编码
         */
        if(empty($encode)){
            preg_match('/<meta[^>]*?charset=(["\']?)([a-zA-z0-9\-\_]+)(\1)[^>]*?>/is', $html, $charset);
            $encode = str_replace(array('"',"'"),'', trim($charset[2]));
        }
        if(function_exists('mb_detect_encoding') && empty($encode)) {
            $encode = mb_detect_encoding($html, array("ASCII","UTF-8","GB2312","GBK","BIG5"));
        }

        if (spider::$dataTest || spider::$ruleTest) {
            echo '<b>检测页面编码:</b>'.$encode . '<br />';
        }
        if(strtoupper($encode)==$out){
            return $html;
        }
        $html = preg_replace('/(<meta[^>]*?charset=(["\']?))[a-z\d_\-]*(\2[^>]*?>)/is', "\\1$out\\3", $html,1);
        if (function_exists('mb_convert_encoding')) {
            return mb_convert_encoding($html,$out,$encode);
        } elseif (function_exists('iconv')) {
            return iconv($encode,$out, $html);
        } else {
            iPHP::throwException('charsetTrans failed, no function');
        }
    }

    public static function check_content_code($content) {
        if (spider::$content_right_code) {
            if(strpos(spider::$content_right_code, 'DOM::')!==false){
                iPHP::import(iPHP_LIB.'/phpQuery.php');
                $doc     = phpQuery::newDocumentHTML($content,'UTF-8');
                $pq_dom  = str_replace('DOM::','', spider::$content_right_code);
                $matches = (bool)(string)phpQuery::pq($pq_dom);
                phpQuery::unloadDocuments($doc->getDocumentID());
                unset($doc,$content);
            }else{
                $matches = strpos($content, spider::$content_right_code);
                unset($content);
            }
	        if ($matches===false) {
	            return false;
	        }
        }
        if (spider::$content_error_code) {
            if(strpos(spider::$content_error_code, 'DOM::')!==false){
                iPHP::import(iPHP_LIB.'/phpQuery.php');
                $doc      = phpQuery::newDocumentHTML($content,'UTF-8');
                $pq_dom   = str_replace('DOM::','', spider::$content_error_code);
                $_matches = (bool)(string)phpQuery::pq($pq_dom);
                phpQuery::unloadDocuments($doc->getDocumentID());
                unset($doc,$content);
            }else{
                $_matches = strpos($content, spider::$content_error_code);
                unset($content);
            }
            if ($_matches!==false) {
                return false;
            }
        }
        return true;
    }
    public static function mkurls($url,$format,$begin,$num,$step,$zeroize,$reverse) {
        $urls = "";
        $start = (int)$begin;
        if($format==0){
            $num = $num-1;
            if($num<0){
                $num = 1;
            }
            $end = $start+$num;
        }else if($format==1){
            $end = $start*pow($step,$num-1);
        }else if($format==2){
            $start = ord($begin);
            $end   = ord($num);
            $step  = 1;
        }
        $zeroize = ($zeroize=='true'?true:false);
        $reverse = ($reverse=='true'?true:false);
        //var_dump($url.','.$format.','.$begin.','.$num.','.$step,$zeroize,$reverse);
        if($reverse){
            for($i=$end;$i>=$start;){
                $id = $i;
                if($format==2){
                    $id = chr($i);
                }
                if($zeroize){
                    $len = strlen($end);
                    //$len==1 && $len=2;
                    $id  = sprintf("%0{$len}d", $i);
                }
                $urls[]=str_replace('<*>',$id,$url);
                if($format==1){
                  $i=$i/$step;
                }else{
                  $i=$i-$step;
                }
            }
        }else{
            for($i=$start;$i<=$end;){
                $id = $i;
                if($format==2){
                    $id = chr($i);
                }
                if($zeroize){
                    $len = strlen($end);
                    //$len==1 && $len=2;
                    $id  = sprintf("%0{$len}d", $i);
                }
                $urls[]=str_replace('<*>',$id,$url);
                if($format==1){
                  $i=$i*$step;
                }else{
                  $i=$i+$step;
                }
            }
        }
        return $urls;
    }

    public static function url_complement($baseUrl,$href){
        $href = trim($href);
        if (iFS::checkHttp($href)){
            return $href;
        }else{
            if ($href[0]=='/'){
                $base_uri  = parse_url($baseUrl);
                $base_host = $base_uri['scheme'].'://'.$base_uri['host'];
                return $base_host.'/'.ltrim($href,'/');
            }else{
                if(substr($baseUrl, -1)!='/'){
                    $info = pathinfo($baseUrl);
                    $info['extension'] && $baseUrl = $info['dirname'];
                }
                $baseUrl = rtrim($baseUrl,'/');
                return iFS::path($baseUrl.'/'.ltrim($href,'/'));
            }
        }
    }
    public static function checkpage(&$newbody, $bodyA, $_count = 1, $nbody = "", $i = 0, $k = 0) {
        $ac = count($bodyA);
        $nbody.= $bodyA[$i];
        preg_match_all("/<img.*?src\s*=[\"|'|\s]*(http:\/\/.*?\.(gif|jpg|jpeg|bmp|png)).*?>/is", $nbody, $picArray);
        $pA = array_unique($picArray[1]);
        $pA = array_filter($pA);
        $_pcount = count($pA);
        //	print_r($_pcount);
        //	echo "\n";
        //	print_r('_count:'.$_count);
        //	echo "\n";
        //	var_dump($_pcount>$_count);
        if ($_pcount >= $_count) {
            $newbody[$k] = $nbody;
            $k++;
            $nbody = "";
        }
        $ni = $i + 1;
        if ($ni <= $ac) {
            self::checkpage($newbody, $bodyA, $_count, $nbody, $ni, $k);
        } else {
            $newbody[$k] = $nbody;
        }
    }
    public static function mergePage($content){
        $_content = $content;
        preg_match_all("/<img.*?src\s*=[\"|'|\s]*(http:\/\/.*?\.(gif|jpg|jpeg|bmp|png)).*?>/is", $_content, $picArray);
        $pA = array_unique($picArray[1]);
        $pA = array_filter($pA);
        $_pcount = count($pA);
        if ($_pcount < 4) {
            $content = str_replace('#--iCMS.PageBreak--#', "", $content);
        } else {
            $contentA = explode("#--iCMS.PageBreak--#", $_content);
            $newcontent = array();
            self::checkpage($newcontent, $contentA, 4);
            if (is_array($newcontent)) {
                $content = array_filter($newcontent);
                $content = implode('#--iCMS.PageBreak--#', $content);
                //$content      = addslashes($content);
            } else {
                //$content      = addslashes($newcontent);
                $content = $newcontent;
            }
            unset($newcontent,$contentA);
        }
        unset($_content);
        return $content;
    }
    public static function autoBreakPage($content,$pageBit = '15000',$pageBreak='#--iCMS.PageBreak--#'){
        $text      = str_replace('</p><p>', "</p>\n<p>", $content);
        $textArray = explode("\n", $text);
        $pageNum   = 0;
        $resource  = array();
        // $_count         = count($textArray);
        foreach ($textArray as $key => $p) {
            $text      = preg_replace(array('/<[\/\!]*?[^<>]*?>/is','/\s*/is'),'',$p);
            $pageLen   = strlen($resource[$pageNum]);
            $output    = implode('',array_slice($textArray,$key));
            $outputLen = strlen($output);
            if($pageLen>$pageBit && $outputLen>$pageBit){
                $pageNum++;
                $resource[$pageNum] = $p;
            }else{
                $resource[$pageNum].= $p;
            }
        }
        unset($text,$textArray,$output);
        return implode($pageBreak, (array)$resource);
    }

    public static function remote($url, $_count = 0) {
        $url = str_replace('&amp;', '&', $url);
        if(empty(spider::$referer)){
            $uri = parse_url($url);
            spider::$referer = $uri['scheme'] . '://' . $uri['host'];
        }

        $options = array(
            CURLOPT_URL                  => $url,
            CURLOPT_ENCODING             => spider::$encoding,
            CURLOPT_REFERER              => spider::$referer,
            CURLOPT_USERAGENT            => spider::$useragent,
            CURLOPT_TIMEOUT              => 10,
            CURLOPT_CONNECTTIMEOUT       => 10,
            CURLOPT_RETURNTRANSFER       => 1,
            CURLOPT_FAILONERROR          => 1,
            CURLOPT_HEADER               => 0,
            CURLOPT_NOSIGNAL             => true,
            CURLOPT_DNS_USE_GLOBAL_CACHE => true,
            CURLOPT_DNS_CACHE_TIMEOUT    => 86400,
            CURLOPT_SSL_VERIFYPEER       => false,
            CURLOPT_SSL_VERIFYHOST       => false
            // CURLOPT_FOLLOWLOCATION => 1,// 使用自动跳转
            // CURLOPT_MAXREDIRS => 7,//查找次数，防止查找太深
        );
        spider::$cookie && $options[CURLOPT_COOKIE] = spider::$cookie;
        if(spider::$curl_proxy){
            $proxy   = spiderTools::proxy_test();
            $proxy && $options = spiderTools::proxy($options,$proxy);
        }
        $ch = curl_init();
        curl_setopt_array($ch,$options);
        $responses = curl_exec($ch);
        $info = curl_getinfo($ch);
        if (spider::$dataTest || spider::$ruleTest) {
            echo "<b>{$url} 头信息:</b><pre>";
            print_r($info);
            echo '</pre><hr />';
            if($_GET['breakinfo']){
            	exit();
            }
        }
        if (in_array($info['http_code'],array(301,302)) && $_count < 5) {
            $_count++;
            $newurl = $info['redirect_url'];
	        if(empty($newurl)){
		    	curl_setopt($ch, CURLOPT_HEADER, 1);
		    	$header		= curl_exec($ch);
		    	preg_match ('|Location: (.*)|i',$header,$matches);
		    	$newurl 	= ltrim($matches[1],'/');
			    if(empty($newurl)) return false;

		    	if(!strstr($newurl,'http://')){
			    	$host	= $uri['scheme'].'://'.$uri['host'];
		    		$newurl = $host.'/'.$newurl;
		    	}
	        }
	        $newurl	= trim($newurl);
			curl_close($ch);
			unset($responses,$info);
            return spiderTools::remote($newurl, $_count);
        }
        if (in_array($info['http_code'],array(404,500))) {
			curl_close($ch);
			unset($responses,$info);
            return false;
        }

        if ((empty($responses)||$info['http_code']!=200) && $_count < 5) {
            $_count++;
            if (spider::$dataTest || spider::$ruleTest) {
                echo $url . '<br />';
                echo "获取内容失败,重试第{$_count}次...<br />";
            }
			curl_close($ch);
			unset($responses,$info);
            return spiderTools::remote($url, $_count);
        }
        $pos = stripos($info['content_type'], 'charset=');
        $pos!==false && $content_charset = trim(substr($info['content_type'], $pos+8));
        $responses = spiderTools::charsetTrans($responses,$content_charset,spider::$charset);
		curl_close($ch);
		unset($info);
        if (spider::$dataTest || spider::$ruleTest) {
            echo '<pre>';
            print_r(htmlspecialchars(substr($responses,0,800)));
            echo '</pre><hr />';
        }
        spider::$url = $url;
        return $responses;
    }
    public static function proxy_test(){
        $options = array(
            CURLOPT_URL                  => 'http://www.baidu.com',
            CURLOPT_REFERER              => 'http://www.baidu.com',
            CURLOPT_USERAGENT            => 'Mozilla/5.0 (compatible; Baiduspider/2.0; +http://www.baidu.com/search/spider.html)',
            CURLOPT_TIMEOUT              => 10,
            CURLOPT_CONNECTTIMEOUT       => 8,
            CURLOPT_RETURNTRANSFER       => 1,
            CURLOPT_HEADER               => 0,
            CURLOPT_NOSIGNAL             => true,
            CURLOPT_DNS_USE_GLOBAL_CACHE => true,
            CURLOPT_DNS_CACHE_TIMEOUT    => 86400,
            CURLOPT_SSL_VERIFYPEER       => false,
            CURLOPT_SSL_VERIFYHOST       => false
            // CURLOPT_FOLLOWLOCATION => 1,// 使用自动跳转
            // CURLOPT_MAXREDIRS => 7,//查找次数，防止查找太深
        );
        if(empty(spider::$proxy_array)){
            if(empty(spider::$curl_proxy)){
                return false;
            }
            spider::$proxy_array = explode("\n", spider::$curl_proxy); // socks5://127.0.0.1:1080@username:password
        }
        if(empty(spider::$proxy_array)){
            return false;
        }
        $rand_keys   = array_rand(spider::$proxy_array,1);
        $proxy       = spider::$proxy_array[$rand_keys];
        $proxy       = trim($proxy);
        $options     = spiderTools::proxy($options,$proxy);

        $ch        = curl_init();
        curl_setopt_array($ch,$options);
        curl_exec($ch);
        $info      = curl_getinfo($ch);
        curl_close($ch);
        if($info['http_code']==200){
            return $proxy;
        }else{
            unset(spider::$proxy_array[$rand_keys]);
            return spiderTools::proxy_test();
        }
    }
    public static function proxy($options='',$proxy){
        if($proxy){
            // $proxy_array = explode("\n", $this->proxy); // socks5://127.0.0.1:1080@username:password
            // $rand_keys   = array_rand($proxy_array,1);
            // $proxy       = $proxy_array[$rand_keys];
            // if(empty($proxy)){
            //     return $options;
            // }
            //foreach ($proxy_array as $key => $proxy) {
                $proxy   = trim($proxy);
                $matches = strpos($proxy,'socks5://');
                if($matches===false){
                    // $options[CURLOPT_HTTPPROXYTUNNEL] = true;//HTTP代理开关
                    $options[CURLOPT_PROXYTYPE] = CURLPROXY_HTTP;//使用http代理模式
                }else{
                    $options[CURLOPT_PROXYTYPE] = CURLPROXY_SOCKS5;
                }
                list($url,$auth) = explode('@', $proxy);
                $url = str_replace(array('http://','socks5://'), '', $url);
                $options[CURLOPT_PROXY] = $url;
                $auth && $options[CURLOPT_PROXYUSERPWD] = $auth;//代理验证格式  username:password
                $options[CURLOPT_PROXYAUTH] = CURLAUTH_BASIC; //代理认证模式
            //}
        }

        return $options;
    }

	public static function str_cut($str, $start, $end) {
	    $content = strstr($str, $start);
	    $content = substr($content, strlen($start), strpos($content, $end) - strlen($start));
	    return $content;
	}

	public static function utf8_num_decode($entity) {
	    $convmap = array(0x0, 0x10000, 0, 0xfffff);
	    return mb_decode_numericentity($entity, $convmap, 'UTF-8');
	}
	public static function utf8_entity_decode($entity) {
	    $entity  = '&#'.hexdec($entity).';';
	    $convmap = array(0x0, 0x10000, 0, 0xfffff);
	    return mb_decode_numericentity($entity, $convmap, 'UTF-8');
	}
}
