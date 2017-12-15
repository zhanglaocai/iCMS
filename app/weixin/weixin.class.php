<?php

define('iCMS_WEIXIN_COMPONENT',"http://wx.idreamsoft.com");//iCMS微信第三方平台

class weixin {
    public static $debug       = true;
    public static $component   = false;
    public static $accessToken = null;
    public static $config      = array();

    protected static $appId          = null;
    protected static $appSecret      = null;
    protected static $accessTokenKey = 'weixin/ACCESS_TOKEN';
    protected static $apiUrl         = 'https://api.weixin.qq.com/cgi-bin';

    public static function init($config=null){
        $config && self::$config = $config;
        if(self::$config){
            self::$appId     = self::$config['appid'];
            self::$appSecret = self::$config['appsecret'];
        }

        if(self::$component){
            self::$apiUrl = iCMS_WEIXIN_COMPONENT.'/cgi-bin';
            return;
        }
        self::$accessTokenKey = 'weixin/ACCESS_TOKEN_'.md5(self::$appId.self::$appSecret);
        self::$accessToken===null && self::$accessToken = iCache::get(self::$accessTokenKey,null,0);
        self::$accessToken OR self::get_access_token();

        return;
    }
    public static function get_access_token(){
        $url = self::$apiUrl.'/token?grant_type=client_credential'.
        '&appid='.self::$appId.
        '&secret='.self::$appSecret;
        $response = self::http($url);
        if($response->errcode){
            self::error($response,__METHOD__);
        }
        self::$accessToken = $response->access_token;
        iCache::set(self::$accessTokenKey,self::$accessToken,$response->expires_in);
    }
    public static function error($e,$method=''){
        //if(self::$debug){
            die("<p>errcode:".$e->errcode." errmsg:".$e->errmsg.' IN '.$method."</p>\n");
        //}
    }
    public static function url($uri,$query=null){
        $url = self::$apiUrl.'/'.$uri.'?access_token='.self::$accessToken;
        if(self::$component){
            $url.= '&appid='.self::$appId;
        }
        $query && $url.= '&'.http_build_query((array)$query);
        self::$debug && var_dump($url);
        return $url;
    }
    public static function setMenu($param=null){
        $param===null && $param = weixin::$config['menu'];
        $param    = array('button'=>self::cn_urlencode($param));
        $param    = json_encode($param);
        $param    = urldecode($param);
        $url      = self::url('menu/create');
        $response = self::http($url,$param);
        // if($response->errcode){
        //     self::error($response,__METHOD__);
        // }
        return $response;
    }
    protected static function cn_urlencode($variable){
        foreach ((array)$variable as $i => $param) {
            foreach ((array)$param as $key => $value) {
                if($key=='name'){
                    $value = trim($value);
                    if(empty($value)){
                        unset($variable[$i]);
                        continue;
                    }

                    $variable[$i][$key] = urlencode(trim($value));
                }
                if($key=='sub_button'){
                    $variable[$i][$key] = self::cn_urlencode($value);
                }
            }
        }
        return $variable;
    }
    public static function getMenu(){
        $url      = self::url('menu/get');
        $response = self::http($url);
        // if($response->errcode=="46003"){
        //     return false;
        // }else if($response->errcode){
        //     self::error($response,__METHOD__);
        // }
        return $response;
    }
    /**
     * [get_media_list 获取素材列表]
     * @param  integer $offset [从全部素材的该偏移位置开始返回，0表示从第一个素材 返回]
     * @param  integer $count  [返回素材的数量，取值在1到20之间]
     * @param  string  $type   [素材的类型，图片（image）、视频（video）、语音 （voice）、图文（news）]
     * @return [array]  [永久图文消息素材列表]
     */
    public static function mediaList($type='news',$offset=0,$count=20){
        $url   = self::url('material/batchget_material');
        $param = array(
            'type'   => $type,
            'offset' => $offset,
            'count'  => $count,
        );
        $cache_name = 'weixin/media_list';
        $post_data  = json_encode($param);
        $response   = iCache::get($cache_name);
        if(empty($response)){
            $response  = self::http($url,$post_data);
            iCache::set($cache_name,$response,30);
        }
// print_r($response);
        if($response->errcode){
            self::error($response,__METHOD__);
        }
        if($response->total_count){
            $media_list_array = array();
            $media_list_array['total_count'] = $response->total_count;
            $media_list_array['item_count']  = $response->item_count;
            $items = array();
            foreach ($response->item as $key => $value) {
                $items[$key]['media_id']    = $value->media_id;
                $items[$key]['update_time'] = $value->update_time;
                $items[$key]['content']     = self::media_item($value->content->news_item);
                // foreach ($value->content->news_item as $key2 => $value2) {
                //     $items[$key]['content'][$key2] = (array)$value2;
                // }
            }
            $media_list_array['items']  = $items;
            return $media_list_array;
        }
        return $response;
    }



    public static function http($url, $POSTFIELDS=null) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);

        if($POSTFIELDS){
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $POSTFIELDS);
        }

        $response = curl_exec ($ch);
        self::$debug && var_dump($response);
        curl_close ($ch);

        if(empty($response)){
            return '-100000';
        }
        return json_decode($response);
    }
    public static function msg_xml($text,$FromUserName,$ToUserName){
        $CreateTime = time();
        echo "<xml>
        <ToUserName><![CDATA[".$FromUserName."]]></ToUserName>
        <FromUserName><![CDATA[".$ToUserName."]]></FromUserName>
        <CreateTime>".$CreateTime."</CreateTime>
        <MsgType><![CDATA[text]]></MsgType>
        <Content><![CDATA[".$text."]]></Content>
        <FuncFlag>0</FuncFlag>
        </xml>";
        exit;
    }
    public static  function checkSignature(){
        // you must define TOKEN by yourself
        if (!self::$config['token']) {
            throw new Exception('TOKEN is not defined!');
        }

        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce     = $_GET["nonce"];

        $token  = self::$config['token'];
        $tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
    public static  function input(){
        $input = file_get_contents("php://input");
        if ($input){
        	libxml_disable_entity_loader(true);
            $data = simplexml_load_string($input, 'SimpleXMLElement', LIBXML_NOCDATA);
            iS::slashes($data);
            waf::check_data($data);
            return $data;
        }else{
            return false;
        }
    }
}
