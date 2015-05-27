<?php
/**
* iCMS - i Content Management System
* Copyright (c) 2007-2012 idreamsoft.com iiimon Inc. All rights reserved.
*
* @author coolmoo <idreamsoft@qq.com>
* @site http://www.idreamsoft.com
* @licence http://www.idreamsoft.com/license.php
* @version 6.0.0
* @$Id: iCMS.func.php 2412 2014-05-04 09:52:07Z coolmoo $
*/

function small($sfp,$w='',$h='',$scale=true) {
    if(empty($sfp)){
        echo iCMS_FS_URL.'1x1.gif';
        return;
    }
    if(strpos($sfp,'_')!==false){
        if(preg_match('|.+\d+x\d+\.jpg$|is', $sfp)!=0){
            echo $sfp;
            return;
        }
    }
    $uri = parse_url(iCMS_FS_URL);
    if(stripos($sfp,$uri['host']) === false){
        echo $sfp;
        return;
    }

    if(empty(iCMS::$config['thumb']['size'])){
        echo $sfp;
        return;
    }

    $size_map = explode("\n", iCMS::$config['thumb']['size']);
    $size_map = array_map('trim', $size_map);
    $size_map = array_flip($size_map);
    $size     = $w.'x'.$h;
    if(!isset($size_map[$size])){
        echo $sfp;
        return;
    }

    if(iCMS::$config['FS']['yun']['enable']){
        if(iCMS::$config['FS']['yun']['QiNiu']['Bucket']){
            echo $sfp.'?imageView2/1/w/'.$w.'/h/'.$h;
            return;
        }
    }
    echo $sfp.'_'.$size.'.jpg';
}

function baidu_ping($urls) {
    $site          = iCMS::$config['api']['baidu']['sitemap']['site'];
    $access_token  = iCMS::$config['api']['baidu']['sitemap']['access_token'];
    if(empty($site)||empty($access_token)){
        return false;
    }
    $api     ='http://data.zz.baidu.com/urls?site='.$site.'&token='.$access_token;
    $ch      = curl_init();
    $options =  array(
        CURLOPT_URL            => $api,
        CURLOPT_POST           => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS     => implode("\n",(array)$urls),
        CURLOPT_HTTPHEADER     => array('Content-Type: text/plain'),
    );
    curl_setopt_array($ch, $options);
    $result = curl_exec($ch);
    $json   = json_decode($result);
    if($json->success){
        return true;
    }
    return $json;
}
function get_pic($src,$size=0,$thumb=0){
    if(empty($src)) return array();

    $data = array(
        'src' => $src,
        'url' => iFS::fp($src,'+http'),
    );
    if($size){
        $data['width']  = $size['w'];
        $data['height'] = $size['h'];
    }
    if($size && $thumb){
        $data+= bitscale(array(
            "tw" => (int)$thumb['width'],
            "th" => (int)$thumb['height'],
            "w" => (int)$size['w'],
            "h" => (int)$size['h'],
        ));
    }
    return $data;
}
function get_twh($width=null,$height=null){
    $ret    = array();
    $width  ===null OR $ret['width'] = $width;
    $height ===null OR $ret['height'] = $height;
    return $ret;
}
function autoformat($html){
    $html = stripslashes($html);
    $html = preg_replace(array(
    '/on(\w+)="[^"]+"/is',
    '/<script[^>]*?>.*?<\/script>/si',
    '/<style[^>]*?>.*?<\/style>/si',
    '/style=[" ]?([^"]+)[" ]/is',
    '/<br[^>]*>/i',
    '/<div[^>]*>(.*?)<\/div>/is',
    '/<p[^>]*>(.*?)<\/p>/is',
    '/<img[^>]+src=[" ]?([^"]+)[" ]?[^>]*>/is'
    ),array('','','','',"\n","$1\n","$1\n","\n[img]$1[/img]"),$html);

    if (stripos($html,'<embed') !== false){
        preg_match_all("/<embed[^>]*>/is", $html, $embed_match);
        foreach ((array)$embed_match[0] as $key => $value) {
            preg_match("/.*?src\s*=[\"|'|](.*?)[\"|'|]/is", $value, $src_match);
            preg_match("/.*?class\s*=[\"|'|](.*?)[\"|'|]/is", $value, $class_match);
            preg_match("/.*?width\s*=[\"|'|](\d+)[\"|'|]/is", $value, $width_match);
            preg_match("/.*?height\s*=[\"|'|](\d+)[\"|'|]/is", $value, $height_match);
            $embed_width = $width_match[1];
            $embed_height = $height_match[1];
            if($class_match[1]=='edui-faked-music'){
                empty($embed_width) && $embed_width = "400";
                empty($embed_height) && $embed_height = "95";
                $html = str_replace($value,'[music='.$embed_width.','.$embed_height.']'.$src_match[1].'[/music]',$html);
            }else{
                empty($embed_width) && $embed_width = "500";
                empty($embed_height) && $embed_height = "450";
                $html = str_replace($value,'[video='.$embed_width.','.$embed_height.']'.$src_match[1].'[/video]',$html);
            }
        }
    }
    $html = str_replace(array("&nbsp;","　"),'',$html);
    $html = preg_replace(array(
    '/<b[^>]*>(.*?)<\/b>/i',
    '/<strong[^>]*>(.*?)<\/strong>/i'
    ),"[b]$1[/b]",$html);

    $html = preg_replace('/<[\/\!]*?[^<>]*?>/is','',$html);
    $html = ubb2html($html);
    $html = nl2p($html);
    return addslashes($html);
}
function ubb2html($content){
    return preg_replace(array(
    '/\[img\](.*?)\[\/img\]/is',
    '/\[b\](.*?)\[\/b\]/is',
    '/\[url=([^\]]+)\](.*?)\[\/url\]/is',
    '/\[url=([^\]|#]+)\](.*?)\[\/url\]/is',
    '/\[music=(\d+),(\d+)\](.*?)\[\/music\]/is',
    '/\[video=(\d+),(\d+)\](.*?)\[\/video\]/is',
    ),array(
    '<img src="$1" />','<strong>$1</strong>','<a target="_blank" href="$1">$2</a>','$2',
    '<embed type="application/x-shockwave-flash" class="edui-faked-music" pluginspage="http://www.macromedia.com/go/getflashplayer" src="$3" width="$1" height="$2" wmode="transparent" play="true" loop="false" menu="false" allowscriptaccess="never" allowfullscreen="true"/>',
    '<embed type="application/x-shockwave-flash" class="edui-faked-video" pluginspage="http://www.macromedia.com/go/getflashplayer" src="$3" width="$1" height="$2" wmode="transparent" play="true" loop="false" menu="false" allowscriptaccess="never" allowfullscreen="true"/>'
    ),$content);
}
function nl2p($html){
    $_htmlArray = explode("\n",$html);
    $_htmlArray = array_map("trim", $_htmlArray);
    $_htmlArray = array_filter($_htmlArray);
    if(empty($_htmlArray)){
        return false;
    }
    $isempty    = false;
    $emptycount = 0;
    foreach($_htmlArray as $hkey=>$_html){
        if(empty($_html)){
            $emptycount++;
            $isempty  = true;
            $emptykey = $hkey;
        }else{
            if($emptycount>1 && !$pbkey){
                $brkey = $emptykey;
                $isbr  = true;
                $htmlArray[$emptykey]='<p><br /></p>';
            }
            $emptycount = 0;
            $emptykey   = 0;
            $isempty    = false;
            $pbkey      = false;
            $htmlArray[$hkey]   = '<p>'.$_html.'</p>';
        }
        if($_html=="#--iCMS.PageBreak--#"){
            unset($htmlArray[$brkey]);
            $pbkey            = $hkey;
            $htmlArray[$hkey] = $_html;
        }
    }
    reset ($htmlArray);
    if(current($htmlArray)=="<p><br /></p>"){
        array_shift($htmlArray);
        //$fkey = key($htmlArray);
        //unset($htmlArray[$fkey]);
    }
    $html = implode('',$htmlArray);
    $html = preg_replace('/<p[^>]*>\s+<\/p>/i','',$html);
    return $html;
}
function cnum($subject){
    $searchList = array(
        array('ⅰ','ⅱ','ⅲ','ⅳ','ⅴ','ⅵ','ⅶ','ⅷ','ⅸ','ⅹ'),
        array('㈠','㈡','㈢','㈣','㈤','㈥','㈦','㈧','㈨','㈩'),
        array('①','②','③','④','⑤','⑥','⑦','⑧','⑨','⑩'),
        array('一','二','三','四','五','六','七','八','九','十'),
        array('零','壹','贰','叁','肆','伍','陆','柒','捌','玖','拾'),
        array('Ⅰ','Ⅱ','Ⅲ','Ⅳ','Ⅴ','Ⅵ','Ⅶ','Ⅷ','Ⅸ','Ⅹ','Ⅺ','Ⅻ'),
        array('⑴','⑵','⑶','⑷','⑸','⑹','⑺','⑻','⑼','⑽','⑾','⑿','⒀','⒁','⒂','⒃','⒄','⒅','⒆','⒇'),
        array('⒈','⒉','⒊','⒋','⒌','⒍','⒎','⒏','⒐','⒑','⒒','⒓','⒔','⒕','⒖','⒗','⒘','⒙','⒚','⒛')
    );
    $replace = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20);
    foreach ($searchList as $key => $search) {
        $subject = str_replace($search, $replace, $subject);
    }

    return $subject;
}
function _archive_date($date){
    $limit = time() - $date;
    if($limit <= 86400){
        return '今天';
    }else if($limit > 86400 && $limit<=172800){
        return '昨天';
    }else{
        //return get_date($date,'dm');
        return '<span class="day">'.get_date($date,'d').'</span><span class="mon">'.get_date($date,'m').'月</span>';
    }
}
