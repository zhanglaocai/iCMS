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
defined('iPHP') OR exit('What are you doing?');

class iCMS {
    public static $config = array();

	public static function init(){
        self::$config = iPHP::config();
        define('iCMS_DIR',       self::$config['router']['DIR']);
        define('iCMS_URL',       self::$config['router']['URL']);
        define('iCMS_PUBLIC_URL',self::$config['router']['public_url']);
        define('iCMS_FS_URL',    self::$config['FS']['url']);
        define('iCMS_API',       iCMS_PUBLIC_URL.'/api.php');
        define('iCMS_API_URL',   iCMS_API.'?app=');
        iURL::init(self::$config);
	}
    /**
     * 运行应用程序
     * @param string $app 应用程序名称
     * @param string $do 动作名称
     */
    public static function run($app = NULL,$do = NULL,$args = NULL,$prefix="do_") {
        iDevice::init(self::$config);
        iTemplate::init();
        iPHP::$iTPL->_iVARS = array(
            'VERSION' => iCMS_VER,
            'API'     => iCMS_API,
            'SAPI'    => iCMS_API_URL,
            'APPID'   => array(
                'ARTICLE'  => iCMS_APP_ARTICLE,
                'CATEGORY' => iCMS_APP_CATEGORY,
                'TAG'      => iCMS_APP_TAG,
                'PUSH'     => iCMS_APP_PUSH,
                'COMMENT'  => iCMS_APP_COMMENT,
                'PROP'     => iCMS_APP_PROP,
                'MESSAGE'  => iCMS_APP_MESSAGE,
                'FAVORITE' => iCMS_APP_FAVORITE,
                'USER'     => iCMS_APP_USER,
            )
        );
        self::send_access_control();
        self::assign_site();

        return iPHP::run($app,$do,$args,$prefix);
    }

    public static function API($app = NULL,$do = NULL) {
        $app OR $app = iSecurity::escapeStr($_GET['app']);
        return self::run($app,null,null,'API_');
    }
    public static function send_access_control() {
        header("Access-Control-Allow-Origin: " . self::$config['router']['URL']);
        header('Access-Control-Allow-Headers: X-Requested-With,X_Requested_With');
    }
    public static function hooks($key,$array){
        self::$hooks[$key]  = $array;
    }
    public static function loader($name){
        return iPHP::loader($name,iPHP_APP_CORE);
    }

    public static function assign_site(){
        $site          = self::$config['site'];
        $site['title'] = self::$config['site']['name'];
        $site['404']   = iPHP_URL_404;
        $site['url']   = iCMS_URL;
        $site['tpl']   = iPHP_DEFAULT_TPL;
        $site['urls']  = array(
            "tpl"    => iCMS_URL.'/template/'.iPHP_DEFAULT_TPL,
            "public" => iCMS_PUBLIC_URL,
            "user"   => iPHP_ROUTER_USER,
            "res"    => iCMS_FS_URL,
            "ui"     => iCMS_PUBLIC_URL.'/ui',
            "avatar" => iCMS_FS_URL.'avatar/',
            "mobile" => self::$config['template']['mobile']['domain'],
        );
        iPHP::assign('site',$site);
        iUI::$dialog['title']  = self::$config['site']['name'];
    }

    //------------------------------------
    public static function gotohtml($fp,$url='') {
        if(iPHP::$iVIEW=='html'||empty($url)||stristr($url, '.php?')||iPHP_DEVICE!='desktop') return;

        @is_file($fp) && iPHP::redirect($url);
    }
    public static function iFile_init(){
        iFile::init(iFS::$config['table'],array('file_data','file_map'));
        iFS::$CALLABLE = array(
            'insert' => array('iFile','insert'),
            'update' => array('iFile','update'),
            'get'    => array('iFile','get')
        );
    }
}
