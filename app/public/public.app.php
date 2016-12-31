<?php
/**
 * @package iCMS
 * @copyright 2007-2017, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
class publicApp {
	public $methods = array('weixin', 'sitemapindex', 'sitemap', 'seccode', 'agreement', 'crontab', 'time', 'qrcode');
	public function API_agreement() {
		iPHP::view('{iTPL}/user/agreement.htm');
	}
	public function API_sitemapindex() {
		header("Content-type:text/xml");
		iPHP::view('/tools/sitemap.index.htm');
	}
	public function API_sitemap() {
		header("Content-type:text/xml");
		iPHP::assign('cid', (int) $_GET['cid']);
		iPHP::view('/tools/sitemap.baidu.htm');
	}
	/**
	 * [API_weixin 向下兼容]
	 */
	public function API_weixin() {
		$weixinApp = iPHP::app("weixin");
		$weixinApp->API_interface();
	}
	public function API_crontab() {
		$sql =iSQL::update_hits(false,0);
		if ($sql) {
			//点击初始化
			iDB::query("UPDATE `#iCMS@__article` SET {$sql}");
			iDB::query("UPDATE `#iCMS@__user` SET {$sql}");
		}
	}
	public function API_seccode() {
		$_GET['pre'] && $pre = iSecurity::escapeStr($_GET['pre']);
		iSeccode::run($pre);
	}

	public function API_qrcode() {
		$url = iSecurity::escapeStr($_GET['url']);
		iPHP::vendor('QRcode', $url);
	}
}
