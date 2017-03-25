<?php
/**
* iCMS - i Content Management System
* Copyright (c) 2007-2017 idreamsoft.com iiimon Inc. All rights reserved.
*
* @author coolmoo <idreamsoft@qq.com>
* @site http://www.idreamsoft.com
* @licence http://www.idreamsoft.com/license.php
*/
class filterAdmincp{
    public function __construct() {
        $this->appid = iCMS_APP_FILTER;
    }
    public function do_iCMS(){
        $this->do_config();
    }
    public function do_config(){
        configAdmincp::app('999999');
    }
    public function do_save_config(){
        $filter  = explode("\n",$_POST['config']['filter']);
        $disable = explode("\n",$_POST['config']['disable']);
        $_POST['config']['filter']  = array_unique($filter);
        $_POST['config']['disable'] = array_unique($disable);

        configAdmincp::save('999999',null,array($this,'cache'));
    }
    public static function cache($config=null){
        if($config===null){
            $config  = configAdmincp::app('999999',null,true);
        }
    	iCache::set('filter/array',$config['filter'],0);
    	iCache::set('filter/disable',$config['disable'],0);
    }
}
