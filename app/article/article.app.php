<?php
/**
* iCMS - i Content Management System
* Copyright (c) 2007-2017 iCMSdev.com. All rights reserved.
*
* @author icmsdev <master@icmsdev.com>
* @site https://www.icmsdev.com
* @licence https://www.icmsdev.com/LICENSE.html
*/
class articleApp extends appsApp {
	public $methods = array('iCMS','clink', 'hits','vote', 'good', 'bad', 'like_comment', 'comment');
    public static $config  = null;
	public function __construct() {
		parent::__construct('article');
		self::$config = iCMS::$config[$this->app];
	}
	public function article($fvar,$page = 1,$field='id', $tpl = true) {
		$article = iDB::row("
			SELECT * FROM `#iCMS@__article`
			WHERE `".$field."`='".$fvar. "'
			AND `status` ='1' LIMIT 1;",
		ARRAY_A);

		$article OR iPHP::error_404('找不到相关文章<b>'.$field.':' . $fvar . '</b>', 10001);
		$id = $article['id'];

		if ($article['url']) {
			if (iView::$gateway == "html") {
				return false;
			} else {
				$this->API_hits($id);
				iPHP::redirect($article['url']);
			}
		}

		if ($article['chapter']) {
			$all = iDB::all("SELECT id,subtitle FROM `#iCMS@__article_data` WHERE aid='" . (int) $id . "';", ARRAY_A);
			foreach ($all as $akey => $value) {
				$article_data[] = $value;
			}
			unset($all);
			ksort($article_data);
		} else {
			$article_data = iDB::row("SELECT body,subtitle FROM `#iCMS@__article_data` WHERE aid='" . (int) $id . "' LIMIT 1;", ARRAY_A);
		}

		$vars = array(
			'tag'  => true,
			'user' => true,
		);
		$article = $this->value($article, $article_data, $vars, $page, $tpl);
		if ($article === false) {
			return false;
		}

		$article+=(array)apps_meta::data('article',$id);
        $app = apps::get_app('article');
        $app['fields'] && formerApp::data($article['id'],$app,'article',$article,$vars,$article['category']);

		unset($article_data);
		self::hooked($article);
		return apps_common::render($article,'article',$tpl);
	}
	public static function value($article, $data = "", $vars = array(), $page = 1, $tpl = false) {

		$article['appid'] = iCMS_APP_ARTICLE;

		$category = categoryApp::category($article['cid'], false);

		if ($tpl) {
			$category OR iPHP::error_404('找不到该文章的栏目缓存<b>cid:' . $article['cid'] . '</b> 请更新栏目缓存或者确认栏目是否存在', 10002);
		} else {
			if (empty($category)) {
				return false;
			}

		}

		if ($category['status'] == 0) {
			return false;
		}
        if(iCMS::check_view_html($tpl,$category,'article')){
            return false;
        }

		$article['iurl'] = (array)iURL::get('article', array($article, $category));
		$article['url'] = $article['iurl']['href'];

		($tpl && $category['mode'] == '1') && iCMS::redirect_html($article['iurl']);

		$article['category'] = categoryApp::get_lite($category);

		if ($data) {
			$pkey = intval($page - 1);
			if ($article['chapter']) {
				$chapterArray = $data;
				$count = count($chapterArray);
				$adid = $chapterArray[$pkey]['id'];
				$data = iDB::row("SELECT body,subtitle FROM `#iCMS@__article_data` WHERE aid='" . (int) $article['id'] . "' AND id='" . (int) $adid . "' LIMIT 1;", ARRAY_A);
			}


			$article['pics'] = filesApp::get_content_pics($data['body'],$pic_array);

			if ($article['chapter']) {
				$article['body'] = $data['body'];
			} else {
				$body = explode('#--iCMS.PageBreak--#', $data['body']);
				$count = count($body);
				$article['body'] = $body[$pkey];
				unset($body);
			}

			$article['subtitle'] = $data['subtitle'];
			unset($data);
			$total = $count + intval(self::$config['pageno_incr']);
			$article['page'] = iUI::page_content($article,$page,$total,$count,$category['mode'],$chapterArray);
			$article['PAGES'] = $article['page']['PAGES'];unset($article['page']['PAGES']);
			is_array($article['page']['next'])&& $next_url = $article['page']['next']['url'];
			$pic_array[0] && $article['body'] = self::body_pics_page($pic_array,$article,$page,$total,$next_url);
		}

		$vars['tag'] && tagApp::get_array($article,$category['name'],'tags');

        apps_common::init($article,'article',$vars);
        apps_common::link();
        apps_common::text2link();
        apps_common::user();
        apps_common::comment();
        apps_common::pic();
        apps_common::hits();
        apps_common::param();

		return $article;
	}

	public static function data($aids=0){
		if(empty($aids)) return array();

		list($aids,$is_multi)  = iSQL::multi_var($aids);
		$sql  = iSQL::in($aids,'aid',false,true);
		$data = array();
		$rs   = iDB::all("SELECT * FROM `#iCMS@__article_data` where {$sql}");
		if($rs){
			$_count = count($rs);
	        for ($i=0; $i < $_count; $i++) {
	        	$data[$rs[$i]['aid']]= $rs[$i];
	        }
	        $is_multi OR $data = $data[$aids];
		}
        if(empty($data)){
            return;
        }
	   	return $data;
	}

	public static function body_pics_page($pic_array,$article,$page,$total,$next_url){
		$img_array = array_unique($pic_array[0]);
		foreach ($img_array as $key => $img) {
			if(!self::$config['img_title']){
				$img = preg_replace('@title\s*=\s*(["\']?).*?\\1\s*@is', '', $img);
				$img = preg_replace('@alt\s*=\s*(["\']?).*?\\1\s*@is', '', $img);
				$img = str_replace('<img', '<img title="' . addslashes($article['title']) . '" alt="' . addslashes($article['title']) . '"', $img);
			}
			if (self::$config['pic_center']) {
                $img_replace[$key] = '<p class="article_pic">'.$img.'</p>';
			} else {
				$img_replace[$key] = $img;
			}
            if(self::$config['pic_next'] && $total>1){
                $clicknext = '<a href="'.$next_url.'"><b>'.iUI::lang('article:clicknext').' ('.$page.'/'.$total.')</b></a>';
				$clickimg = '<a href="' . $next_url . '" title="' . $article['title'] . '" class="img">' . $img . '</a>';
				if (self::$config['pic_center']) {
                    $img_replace[$key] = '<p class="click2next">'.$clicknext.'</p>';
                    $img_replace[$key].= '<p class="article_pic">'.$clickimg.'</p>';
				} else {
					$img_replace[$key] = '<p>' . $clicknext . '</p>';
					$img_replace[$key] .= '<p>' . $clickimg . '</p>';
				}
			}
		}
		return str_replace($img_array, $img_replace, $article['body']);
	}

}
