<?php
/**
* iCMS - i Content Management System
* Copyright (c) 2007-2017 iCMSdev.com. All rights reserved.
*
* @author icmsdev <master@icmsdev.com>
* @site https://www.icmsdev.com
* @licence https://www.icmsdev.com/LICENSE.html
*/

class appsApp {
    public $app  = null;
    public static $s_app  = null;
    public function __construct($app=null) {
        empty($app) && trigger_error('$app is empty',E_USER_ERROR);

        $this->app   = $app;
        self::$s_app = $app;
    }
    public function do_iCMS($a = null) {
        list($v,$p,$f) = apps_common::getting();
        $func = $this->app;
        return $this->$func($v,$p,$f);
    }
    public function do_clink($a = null) {
        return $this->do_iCMS($a);
    }
    public function API_iCMS() {
        return $this->do_iCMS();
    }
    public function API_clink() {
        return $this->do_clink();
    }
    public function API_search($a = null) {
        $app = iPHP::app("search");
        return $app->search('{iTPL}/'.$this->app.'.search.htm');
    }
    public function API_hits($id = null) {
        apps_common::api_hits($this->app,$id);
    }
    public function ACTION_vote() {
        apps_common::action_vote($this->app);
    }
    public function API_comment() {
        $appid = (int) $_GET['appid'];
        $cid = (int) $_GET['cid'];
        $iid = (int) $_GET['iid'];
        $func = $this->app;
        $this->$func($iid,1,'id','{iTPL}/'.$this->app.'.comment.htm');
    }
    public static function hooked(&$data){
        iPHP::hook(self::$s_app,$data,iCMS::$config['hooks'][self::$s_app]);
    }
}
