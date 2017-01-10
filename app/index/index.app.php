<?php
/**
 * @package iCMS
 * @copyright 2007-2017, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
class indexApp {
	public $methods	= array('iCMS','index');
    public function __construct() {}
    public function do_iCMS($a = null) {
        if(iView::$gateway!="html"){
            $domain = $this->domain();
            if($domain) return;
        }
        return $this->index($a);
    }
    public function API_iCMS(){
        return $this->do_iCMS();
    }
    public function index($a = null){
        $index_name = $a[1]?$a[1]:iCMS::$config['template']['index_name'];
        $index_tpl  = $a[0]?$a[0]:iCMS::$config['template']['index'];
        $index_name OR $index_name = 'index';
        $iurl = iURL::get('index',array('rule'=>$index_name.iCMS::$config['router']['html_ext']));
        if(iCMS::$config['template']['index_mode'] && iPHP_DEVICE=="desktop"){
            iCMS::redirect_html($iurl->path,$iurl->href);
        }
        if(iView::$gateway=="html" || iCMS::$config['template']['index_rewrite']){
            iURL::page_url($iurl);
        }
        $html = iView::render($index_tpl);
        if(iView::$gateway=="html") return array($html,$iurl);
    }
    public function domain(){
        $domain = iCMS::$config['category']['domain'];
        if($domain){
            $host = iSecurity::escapeStr($_GET['host']);
            empty($host) && $host = $_SERVER['HTTP_HOST'];
            $cid  = (int)$domain[$host];
            if($cid){
                categoryApp::category($cid);
                return true;
            }
        }
        return false;
    }
}
