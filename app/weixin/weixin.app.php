<?php
/**
 * @package iCMS
 * @copyright 2007-2017, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
defined('iPHP') OR exit('What are you doing?');

ini_set('display_errors', 'ON');
error_reporting(E_ALL & ~E_NOTICE);
$input = file_get_contents("php://input");

weixinApp::DEBUG($_SERVER['REQUEST_URI'],'input');
weixinApp::DEBUG($input,'input');

class weixinApp {
    public $methods = array('interface');
    public $FromUserName = null;
    public $ToUserName   = null;
    public $encrypt_type = null;
    public function __construct($config=null) {
        $config===null && $config = iCMS::$config['weixin'];
        weixin::$config = $config;
    }

    public function API_interface(){
        // if(iPHP_DEBUG){
            ob_start();
            iDB::$show_errors = true;
        // }

        if ($_GET["api_token"]!=weixin::$config['token']) {
            throw new Exception('TOKEN is error!');
        }

        if($_GET["echostr"] && !$_GET['msg_signature']){
            if(weixin::checkSignature()){
                echo $_GET["echostr"];
                exit;
            }
        }
        $signature     = $_GET["signature"];
        $timestamp     = $_GET["timestamp"];
        $nonce         = $_GET["nonce"];
        $openid        = $_GET["openid"];
        $encrypt_type  = $_GET["encrypt_type"];
        $msg_signature = $_GET["msg_signature"];

        $input = null;
        if($encrypt_type=="aes"){
            weixin_crypt::$token     = weixin::$config['token'];
            weixin_crypt::$aeskey    = weixin::$config['AESKey'];
            weixin_crypt::$appId     = weixin::$config['appid'];
            weixin_crypt::$timeStamp = $timestamp;
            weixin_crypt::$nonce     = $nonce;
            $errCode = weixin_crypt::decrypt($msg_signature, $input);
            if ($errCode == 0) {
            } else {
                exit($errCode . "\n");
            }
        }
        $xml = weixin::input($input);
        if (is_object($xml)){
			$FromUserName = $this->FromUserName = $xml->FromUserName;
			$ToUserName   = $this->ToUserName   = $xml->ToUserName;
			$content      = trim($xml->Content);
			$msgType      = $xml->MsgType;
			$event        = $xml->Event;
			$eventKey     = $xml->EventKey;

            $this->api_log($xml);

            //接收信息类型
            // if($msgType=="text"){ //text 文符串
            //     iPHP::callback(array($this,'msg_text'),array($xml));
            // }elseif($msgType=="event"){//事件
            //     iPHP::callback(array($this,'event_'.strtolower($event)),array($xml));
            // }elseif($msgType=="location"){//发送位置
            //     iPHP::callback(array($this,'msg_location'),array($xml));
            // }elseif($msgType=="image"){//直接发送图片或者事件里的图片
            //     iPHP::callback(array($this,'msg_image'),array($xml));
            // }elseif($msgType=="voice"){//直接发送语音消息
            //     iPHP::callback(array($this,'msg_voice'),array($xml));
            // }elseif($msgType=="video"){//直接发送视频消息
            //     iPHP::callback(array($this,'msg_video'),array($xml));
            // }elseif($msgType=="shortvideo"){//小视频消息
            //     iPHP::callback(array($this,'msg_shortvideo'),array($xml));
            // }elseif($msgType=="link"){//链接消息
            //     iPHP::callback(array($this,'msg_link'),array($xml));
            // }
            //
            //接收信息类型
            if($msgType=="event"){//事件
                iPHP::callback(array($this,'event_'.strtolower($event)),array($xml));
            }else{
                iPHP::callback(array($this,'msg_'.strtolower($msgType)),array($xml));
            }
            self::DEBUG();
            //查找空白事件
            $this->get_event('null','keyword','eq');
            //默认回复
            $this->send('对不起，没找到相关内容');
        }
        self::DEBUG();
    }
    public function event_scancode_push($xml){
    }
    public function event_scancode_waitmsg($xml){
    }
    public function event_pic_sysphoto($xml){
    }
    public function event_pic_photo_or_album($xml){
    }
    public function event_pic_weixin($xml){
    }
    /**
     * [上报地理位置]
     */
    public function event_location_select($xml){
    }
    public function event_media_id($xml){
    }
    public function event_view_limited($xml){
    }
    /**
     * [跳转URL]
     */
    public function event_view($xml){
    }
    /**
     * [点击事件]
     */
    public function event_click($xml){
        $eventkey = $xml->EventKey;
        $rs = $this->get(array(
            'eventype' =>'click',
            'eventkey' =>$eventkey,
        ),'msg');
        $rs['msg'] && $this->send($rs['msg']);
    }
    /**
     * [关注]
     */
    public function event_subscribe(){
        $rs = $this->get(array(
            'eventype'=>'subscribe'
        ),'msg');
        $rs['msg'] && $this->send($rs['msg']);
    }
    /**
     * [取消关注]
     */
    public function event_unsubscribe(){
        $msg = $this->get(array(
            'eventype'=>'unsubscribe'
        ),'msg');
        $msg && $this->send($msg);
    }
    /**
     * [关键词]
     */
    public function msg_text($xml){
        $content = trim($xml->Content);
        $this->get_event($content);

        if (in_array($content,array("1", "2", "3", "？","?","你好"))) {
            $site_name = addslashes(iCMS::$config['site']['name']);
            $site_desc = addslashes(iCMS::$config['site']['description']);
            $site_key  = addslashes(iCMS::$config['site']['keywords']);
            $site_host = str_replace('http://', '', iCMS_URL);
            $this->send($site_name.' ('.$site_host.') '.$site_desc."\n回复:".$site_key.' 将会收到我们最新为您准备的信息');
        }
        // iView::assign('weixin',$data);
        // iView::render("iCMS://weixin.api.htm");
    }
    public function get_event($eventkey=null,$eventype='keyword',$operator=null){
        $rs = $this->get(array(
            'operator' =>'eq', //完全匹配模式
            'eventype' =>$eventype,
            'eventkey' =>$eventkey,
        ),'msg');
        $rs['msg'] && $this->send($rs['msg']);
        if($operator=='eq'){
            return;
        }
        //所有关键词
        $event = iDB::all("
            SELECT `msg`,`operator`,`eventkey`
            FROM `#iCMS@__weixin_event`
            WHERE `eventype`='".$eventype."'
            AND `operator`!='eq'
            ORDER BY `id` DESC
        ");

        if($event)foreach ($event as $key => $value) {
            $value['msg'] = $this->msg_decode($value['msg']);
            if($value['operator']=='in'){
                if (stripos($eventkey, $value['eventkey']) !== false) {
                    $this->send($value['msg']);
                }
            }
            if($value['operator']=='re'){
                $value['eventkey'] = str_replace('@', '\@', $value['eventkey']);
                if (preg_match('@'.$value['eventkey'].'@is',$eventkey)) {
                    $this->send($value['msg']);
                }
            }
        }
    }
    public function get($vars,$field="*",$orderby='id DESC'){
        $sql = iSQL::where($vars,false);
        $sql.= 'order by '.$orderby;
        $row = iDB::row("SELECT {$field} FROM `#iCMS@__weixin_event` where {$sql}",ARRAY_A);
        $row['msg'] = $this->msg_decode($row['msg']);
        return $row;
    }

    public function send($content=null){
        weixin::msg_xml($content,$this->FromUserName,$this->ToUserName);
        exit;
    }
    public function msg_decode($msg=null){
        $msg && $msg = json_decode($msg,true);
        return $msg;
    }
    public function api_log($xml){
        $data = array(
            'ToUserName'   => $xml->ToUserName,
            'FromUserName' => $xml->FromUserName,
            'CreateTime'   => $xml->CreateTime,
            'content'      => $xml->Content,
            'dayline'      => get_date(null,'Y-m-d H:i:s'),
        );
        $array  = self::object2array($xml);
        unset($array['ToUserName'],$array['FromUserName'],$array['CreateTime']);
        $data['content'] = json_encode($array);
        iDB::insert('weixin_api_log',$data);
    }
    public static function DEBUG($output=null,$name='debug'){
    // if(iPHP_DEBUG){
        if($output===null){
            $output = ob_get_contents();
            ob_end_clean();
        }
        // file_put_contents(iPHP_APP_CACHE.'/weixin.api.'.$name.'.log',$output,FILE_APPEND);
        iFS::write(iPHP_APP_CACHE.'/weixin.api.'.$name.'.log',$output."\n",1,'ab+');
    // }
    }
    public static function object2array($object) {
        return @json_decode(@json_encode($object),true);
    }
}
