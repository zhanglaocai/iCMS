<?php
/**
 * @package iCMS
 * @copyright 2007-2017, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
class searchApp {
	public $methods	= array('iCMS');
	public function API_iCMS(){
        return $this->search();
	}
    // public function hooked($data){
    //     return iPHP::hook('search',$data,iCMS::$config['hooks']['search']);
    // }
    public function search($tpl=false) {
        $q  = htmlspecialchars(rawurldecode($_GET['q']));
        $encode = mb_detect_encoding($q, array("ASCII","UTF-8","GB2312","GBK","BIG5"));
        if(strtoupper($encode)!='UTF-8'){
            if (function_exists('iconv')) {
                $q  = iconv($encode,'UTF-8//IGNORE', $q);
            } elseif (function_exists('mb_convert_encoding')) {
                $q  = mb_convert_encoding($q,'UTF-8//IGNORE',$encode);
            }
        }
        $q  = iSecurity::escapeStr($q);

        $fwd = iPHP::callback(array("filterApp","run"),array(&$q));
        $fwd && iPHP::error_404('非法搜索词!', 60002);

        $search['title']   = stripslashes($q);
        $search['keyword'] = $q;
        $tpl===false && $tpl = '{iTPL}/search.htm';
        $q && $this->slog($q);
        $iurl =  new stdClass();
        $iurl->href = iURL::router('api');
        $iurl->href.= '?app=search&q='.$q;
        $iurl->pageurl = $iurl->href.'&page={P}';
        iURL::page_url($iurl);
        iView::assign("search",$search);
        return iView::render($tpl,'search');
    }
    private function slog($search){
        $sid    = iDB::value("SELECT `id` FROM `#iCMS@__search_log` WHERE `search` = '$search' LIMIT 1");
        if($sid){
            iDB::query("UPDATE `#iCMS@__search_log` SET `times` = times+1 WHERE `id` = '$sid';");
        }else{
            iDB::query("INSERT INTO `#iCMS@__search_log` (`search`, `times`, `addtime`) VALUES ('$search', '1', '".time()."');");
        }
    }
}
