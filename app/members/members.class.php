<?php
/**
* iCMS - i Content Management System
* Copyright (c) 2007-2012 idreamsoft.com iiimon Inc. All rights reserved.
*
* @author coolmoo <idreamsoft@qq.com>
* @site http://www.idreamsoft.com
* @licence http://www.idreamsoft.com/license.php
*/
class members{
    const SUPERADMIN_UID ="1";
    const SUPERADMIN_GID ="1";

    public static $userid       = 0;
    public static $data         = array();
    public static $nickname     = NULL;
    public static $group        = array();
    public static $priv         = array();
    public static $AJAX         = false;
    public static $AUTH         = 'iCMS_AUTH';
    public static $LOGIN_PAGE   = 'Login.php';
    private static $LOGIN_COUNT = 0;

    public static function check($a,$p) {
    	if(empty($a) && empty($p)) {
        	self::LoginPage();
    	}

        self::$data = iDB::row("SELECT * FROM `#iCMS@__members` WHERE `username`='{$a}' AND `password`='{$p}' AND `status`='1' LIMIT 1;");
        self::$data OR self::LoginPage();
        unset(self::$data->password);
        self::$userid   = self::$data->uid;
        self::$nickname = self::$data->nickname?self::$data->nickname:self::$data->username;

        self::$data->info   = json_decode(self::$data->info);
        self::$data->config = json_decode(self::$data->config);

        self::$group  = iDB::row("SELECT * FROM `#iCMS@__group` WHERE `gid`='".self::$data->gid."' LIMIT 1;");
        self::$group->config = json_decode(self::$group->config);

        self::$priv['menu']     = self::prior_priv(self::$data->config->mpriv,self::$group->config->mpriv);
        self::$priv['app']      = self::prior_priv(self::$data->config->apriv,self::$group->config->apriv);
        self::$priv['category'] = self::prior_priv(self::$data->config->cpriv,self::$group->config->cpriv);
        return self::$data;
    }
    //登陆验证
    public static function check_login() {
//        self::$LOGIN_COUNT = (int)authcode(get_cookie('iCMS_LOGIN_COUNT'),'DECODE');
//        if(self::$LOGIN_COUNT>iCMS_LOGIN_COUNT) exit();

        $a   = iSecurity::escapeStr($_POST['username']);
        $p   = iSecurity::escapeStr($_POST['password']);
        $ip  = iPHP::get_ip();
        $sep = iPHP_AUTH_IP?'#=iCMS['.$ip.']=#':'#=iCMS=#';
        if(empty($a) && empty($p)) {
            $auth       = iPHP::get_cookie(self::$AUTH);
            list($a,$p) = explode($sep,authcode($auth,'DECODE'));
            return self::check($a,$p);
        }else {
            $p   = md5($p);
            $crs = self::check($a,$p);
            iDB::query("UPDATE `#iCMS@__members` SET `lastip`='".$ip."',`lastlogintime`='".time()."',`logintimes`=logintimes+1 WHERE `uid`='".self::$userid."'");
            iPHP::set_cookie(self::$AUTH,authcode($a.$sep.$p,'ENCODE'));
        	self::$AJAX && iUI::json(array('code'=>1));
            return $crs;
        }
    }

	//登陆页
	public static function LoginPage(){
		self::$AJAX && iUI::json(array('code'=>0));
        iPHP::set_cookie(self::$AUTH,'',-31536000);
		include self::$LOGIN_PAGE;
		exit;
	}
	//注销
	public static function logout(){
		iPHP::set_cookie(self::$AUTH,'',-31536000);
	}
	private static function prior_priv($p1,$p2){
        if($p1){ //用户独立权限优先
            return $p1;
        }elseif($p2){
            return $p2;
        }
        return array();
	}
    public static function is_superadmin() {
        return (members::$data->gid === self::SUPERADMIN_GID);
    }
    public static function check_priv($p=null, $ret = '') {
        if (members::is_superadmin()) {
            return true;
        }
        // if(is_array($p)){
        //     isset($p['priv']) && $p = $p['priv'];
        // }
        if (stripos($p, '?') !==false){
            $parse = parse_url($p);
            parse_str($parse['query'], $output);
            $pieces = array($output['app']);
            $output['do'] && $pieces['do']='do='.$output['do'];
            $pp = implode('&', $pieces);
            $priv = iPHP::check_priv($pp,self::$priv['menu']);
        }else{
            $priv = iPHP::check_priv($p,self::$priv['menu']);
        }
        $priv OR self::permission($p, $ret);
        return $priv?true:false;
    }
    public static function permission($p=null, $ret = '') {
        $title = $p;
        if (stripos($p, '?') !==false){
            $priv = iCache::get('app/priv');
            $p = preg_replace('@app=(\w+)_category@is', 'app=category', $p);
            $priv[$p] && $title = $priv[$p];
        }
        iUI::permission($title, $ret);
        // include self::view("members.permission",'members');
    }
}

