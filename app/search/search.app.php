<?php
/**
* iCMS - i Content Management System
* Copyright (c) 2007-2017 iCMSdev.com. All rights reserved.
*
* @author icmsdev <master@icmsdev.com>
* @site https://www.icmsdev.com
* @licence https://www.icmsdev.com/LICENSE.html
*/
class searchApp {
	public $methods	= array('iCMS');
	public function API_iCMS(){
        return $this->search();
	}
    // public function hooked(&$data){
    //     iPHP::hook('search',$data,iCMS::$config['hooks']['search']);
    // }
    public function search($tpl=false) {
        $q  = htmlspecialchars(rawurldecode($_GET['q']));
        $q  = iSecurity::encoding($q);
        $q  = iSecurity::escapeStr($q);

        $fwd = iPHP::callback(array("filterApp","run"),array(&$q),false);
        $fwd && iPHP::error_404('非法搜索词!', 60002);

        $search['title']   = stripslashes($q);
        $search['keyword'] = $q;

        $q && $this->search_log($q);

        $iURL           =  new stdClass();
        $iURL->url      = iURL::make('app=search&q='.$q,'router::api');
        $iURL->pageurl  = iURL::make('page={P}',$iURL->url);
        $iURL->href     = $iURL->url;
        $search['iurl'] = $iURL;

        iURL::page_url($iURL);
        $tpl===false && $tpl = '{iTPL}/search.htm';
        apps_common::render($search,'search',$tpl);
    }
    private function search_log($search){
        $sid = iDB::value("SELECT `id` FROM `#iCMS@__search_log` WHERE `search` = '$search' LIMIT 1");
        if($sid){
            iDB::query("
                UPDATE `#iCMS@__search_log`
                SET `times` = times+1
                WHERE `id` = '$sid';
            ");
        }else{
            iDB::query("
                INSERT INTO `#iCMS@__search_log` (`search`, `times`, `addtime`)
                VALUES ('$search', '1', '".time()."');
            ");
        }
    }
}
