<?php
/**
* iCMS - i Content Management System
* Copyright (c) 2007-2017 iCMSdev.com. All rights reserved.
*
* @author icmsdev <master@icmsdev.com>
* @site https://www.icmsdev.com
* @licence https://www.icmsdev.com/LICENSE.html
*/
define("WXA_PLATFORM",5);

class weixin_wxapp{
    public static $config = array();

    protected static $appId     = null;
    protected static $appSecret = null;
    protected static $API_URL   = 'https://api.weixin.qq.com/sns';

    public static function init($config=null){
        $config && self::$config = $config;
        if(self::$config){
            self::$appId     = self::$config['appid'];
            self::$appSecret = self::$config['appsecret'];
        }
    }
    public static function callback(){
        $app = $_GET['_app'];
        $do  = $_GET['_do'];
        $_POST['action'] = $_POST['_action'];
        $openid = $_POST['openid'];
        $_userid = $_POST['userid'];
        if($openid){
            $userid = user::openid($openid, WXA_PLATFORM);
            if($_userid==$userid){
                $user = user::get($userid, false);
                user::$COOKIE = array(
                    'uid' => $userid,
                    'username' => $user->username,
                    'password' => $user->password,
                    'nickname' => $user->nickname,
                    'status' => $user->status,
                );
                iPHP::$app = null;
                return iPHP::run($app,$do);
            }
        }
        iUI::json(array("code"=>0,"msg"=>"failure"));
    }
    public static function session(){
        $url = self::$API_URL.'/jscode2session?grant_type=authorization_code'.
        '&js_code='.$_POST['loginCode'].
        '&appid='.self::$appId.
        '&secret='.self::$appSecret;
        $response = weixin::http($url);

        if($response){
            $openid = $response->openid;
            $userid = user::openid($openid, WXA_PLATFORM);
            $res = array('openid'=>$openid,'userid'=>$userid);
            if ($userid) {

            } else {
                $userInfo = json_decode(stripslashes($_POST['rawData']),true);

                empty($userInfo['nickName']) && $userInfo['nickName'] = substr($openid, 0,8);

                $nickname = "wxa_".$userInfo['nickName'];
                $username = "wxa_".$userInfo['nickName'];
                $province = $userInfo['province'];
                $city     = $userInfo['city'];
                $gender   = $userInfo['gender'];
                $password = md5($openid);
                $regip    = iPHP::get_ip();
                $regdate  = time();
                $gid      = 0;
                $pid      = 0;
                $status   = 1;
                $type     = WXA_PLATFORM;
                $fields = array(
                    'gid', 'pid', 'username', 'nickname', 'password','gender',
                    'regip', 'regdate','type', 'status',
                );
                $data = compact($fields);
                $userid = iDB::insert('user', $data);

                iDB::insert('user_openid',array(
                    'uid'      => $userid,
                    'openid'   => $openid,
                    'platform' => WXA_PLATFORM,
                ));
                $res['userid'] = $userid;
            }
        }
        iUI::json($res);
    }
    public static function input(){
        $input = file_get_contents("php://input");
        if($input){
            $data = json_decode($input,true);
            iSecurity::_addslashes($data);
            iWAF::check_data($data);
            return $data;
        }
    }
}
