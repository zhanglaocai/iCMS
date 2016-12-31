<?php
/**
 * @package iCMS
 * @copyright 2007-2017, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
defined('iPHP') OR exit('What are you doing?');


class weixinApp {
    public $methods = array('interface');
    public function __construct($config=null) {
        $config===null && $config = iCMS::$config['weixin'];
        weixin::$config = $config;
    }

    public static function API_interface(){
        if(iPHP_DEBUG){
            // ob_start();
            // iDB::$show_errors = true;
        }

        if ($_GET["api_token"]!=weixin::$config['token']) {
            throw new Exception('TOKEN is error!');
        }

        if($_GET["echostr"] && !$_GET['msg_signature']){
            if(weixin::checkSignature()){
                echo $_GET["echostr"];
                exit;
            }
        }
        $xml = weixin::input();
        if ($xml){
			$FromUserName = $xml->FromUserName;
			$ToUserName   = $xml->ToUserName;
			$content      = trim($xml->Content);
			$msgType      = $xml->MsgType;
			$event        = $xml->Event;
			$eventKey     = $xml->EventKey;

			$CreateTime   = time();
			$dayline      = get_date('','Y-m-d H:i:s');

			if($event=='LOCATION'){
				$Latitude  = $xml->Latitude; //地理位置纬度
				$Longitude = $xml->Longitude;//地理位置经度
				$Precision = $xml->Precision;//地理位置精度
			}
			if($event=='SCAN'){
				$Ticket = $xml->Ticket; //二维码的ticket，可用来换取二维码图片
			}
			if($msgType=='voice'){
				$MediaId     = $xml->MediaId; //语音消息媒体id，可以调用多媒体文件下载接口拉取该媒体
				$Format      = $xml->Format; //语音格式：amr
				$Recognition = $xml->Recognition; //语音识别结果，UTF8编码
				$MsgID       = $xml->MsgID; //消息id，64位整型
			}

            if($msgType!="text"){
                $content = $event;
            }

            $fields       = array('ToUserName', 'FromUserName', 'CreateTime', 'content', 'dayline');
            $data         = compact($fields);
            $content && iDB::insert('weixin_api_log',$data);

            $site_name = addslashes(iCMS::$config['site']['name']);
            $site_desc = addslashes(iCMS::$config['site']['description']);
            $site_key  = addslashes(iCMS::$config['site']['keywords']);
            $site_host = str_replace('http://', '', iCMS_URL);

            if (in_array($event,array('subscribe','unsubscribe'))) {
                if ($event=='subscribe') {
                	$subscribe_msg = $site_name.' ('.$site_host.') '.$site_desc."\n\n回复:".$site_key.' 将会收到我们最新为您准备的信息';
                	weixin::$config['subscribe'] && $subscribe_msg = weixin::$config['subscribe'];
                	//$subscribe_msg = str_replace(array('{site.name}'), replace, subject)
	                weixin::msg_xml($subscribe_msg,$FromUserName,$ToUserName);
                }
                if ($event=='unsubscribe') {
                	$subscribe_msg = "非常感谢您一直以来对我们【".weixin::$config['name']."】的支持！我们会继续努力，做出更好的内容！\n";
                	weixin::$config['unsubscribe'] && $subscribe_msg = weixin::$config['unsubscribe'];
                	//$subscribe_msg = str_replace(array('{site.name}'), replace, subject)
	                weixin::msg_xml($subscribe_msg,$FromUserName,$ToUserName);
                }
            }

            if (in_array($content,array("1", "2", "3", "？","?","你好"))) {
                weixin::msg_xml($site_name.' ('.$site_host.') '.$site_desc."\n\n回复:".$site_key.' 将会收到我们最新为您准备的信息',$FromUserName,$ToUserName);
            }


            iPHP::assign('weixin',$data);
            iPHP::view("iCMS://weixin.api.htm");
        }
        if(iPHP_DEBUG){
            // $output = ob_get_contents();
            // ob_end_clean();
            // echo $output;
            // iFS::write('weixin.api.debug.log',$output,1,'ab+');
        }
    }

}
