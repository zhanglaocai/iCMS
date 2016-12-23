<?php
/**
 * @package iCMS
 * @copyright 2007-2016, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
class articleApp {
	public $taoke   = false;
	public $methods = array('iCMS', 'article', 'clink', 'hits','vote', 'good', 'bad', 'like_comment', 'comment');
	public $pregimg = "/<img.*?src\s*=[\"|'|\s]*(http:\/\/.*?\.(gif|jpg|jpeg|bmp|png)).*?>/is";
	public $config  = null;
	public function __construct() {
		$this->config = iCMS::$config['article'];
	}

	public function do_iCMS($a = null) {
		return $this->article((int) $_GET['id'], isset($_GET['p']) ? (int) $_GET['p'] : 1);
	}
	public function do_clink($a = null) {
		$clink = iSecurity::escapeStr($_GET['clink']);
		$id = iDB::value("SELECT `id` FROM `#iCMS@__article` WHERE `clink`='" . $clink . "' AND `status` ='1';");
		return $this->article((int) $id, isset($_GET['p']) ? (int) $_GET['p'] : 1);
	}
	public function API_iCMS() {
		return $this->do_iCMS();
	}
	public function API_clink() {
		return $this->do_clink();
	}
	public function API_hits($id = null) {
		$id === null && $id = (int) $_GET['id'];
		if ($id) {
			$sql = iCMS::hits_sql();
			iDB::query("UPDATE `#iCMS@__article` SET {$sql} WHERE `id` ='$id'");
		}
	}
	public function ACTION_vote() {
		$type = $_POST['type'];
		$this->vote($type);
		// $type=='up' && $this->vote('good');
		// $type=='down' && $this->vote('bad');
	}
	public function API_comment() {
		$appid = (int) $_GET['appid'];
		$cid = (int) $_GET['cid'];
		$iid = (int) $_GET['iid'];
		$this->article($iid, 1, '{iTPL}/article.comment.htm');
	}
	private function vote($type) {
		// iPHP::app('user.class','static');
		// user::get_cookie() OR iPHP::code(0,'iCMS:!login',0,'json');

		$aid = (int) $_POST['iid'];
		$aid OR iPHP::code(0, 'iCMS:article:empty_id', 0, 'json');

		$ackey = 'article_' . $type . '_' . $aid;
		$vote = iPHP::get_cookie($ackey);
		$vote && iPHP::code(0, 'iCMS:article:!' . $type, 0, 'json');

		if ($type == 'good') {
			$sql = '`good`=good+1';
		} else {
			$sql = '`bad`=bad+1';
		}
		iDB::query("UPDATE `#iCMS@__article` SET {$sql} WHERE `id` ='{$aid}' limit 1");
		iPHP::set_cookie($ackey, time(), 86400);
		iPHP::code(1, 'iCMS:article:' . $type, 0, 'json');

	}
	public function article($id, $page = 1, $tpl = true) {
		$article = iDB::row("SELECT * FROM `#iCMS@__article` WHERE id='" . (int) $id . "' AND `status` ='1' LIMIT 1;", ARRAY_A);
		$article OR iPHP::throw404('运行出错！找不到文章: <b>ID:' . $id . '</b>', 10001);
		if ($article['url']) {
			if (iPHP::$iVIEW == "html") {
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
			'tag'           => true,
			'user'          => true,
			'meta'          => true,
			'prev_next'     => true,
			'category_lite' => true,
		);
		$article = $this->value($article, $article_data, $vars, $page, $tpl);

		unset($article_data);
		if ($article === false) {
			return false;
		}

		if ($tpl) {
			$article_tpl = empty($article['tpl']) ? $article['category']['template']['article'] : $article['tpl'];
			strstr($tpl, '.htm') && $article_tpl = $tpl;
			iPHP::assign('category', $article['category']);
			unset($article['category']);
			iPHP::assign('article', $article);
			$html = iPHP::view($article_tpl, 'article');
			if (iPHP::$iVIEW == "html") {
				return array($html, $article);
			}

		} else {
			return $article;
		}
	}
	public function value($article, $art_data = "", $vars = array(), $page = 1, $tpl = false) {

		$article['appid'] = iCMS_APP_ARTICLE;

		$categoryApp = iPHP::app("category");
		$category = $categoryApp->category($article['cid'], false);

		if ($tpl) {
			$category OR iPHP::throw404('运行出错！找不到该文章的栏目缓存<b>cid:' . $article['cid'] . '</b> 请更新栏目缓存或者确认栏目是否存在', 10002);
		} else {
			if (empty($category)) {
				return false;
			}

		}

		if ($category['status'] == 0) {
			return false;
		}

		if (iPHP::$iVIEW == "html" && $tpl && (strstr($category['contentRule'], '{PHP}') || $category['outurl'] || $category['mode'] == "0")) {
			return false;
		}

		$_iurlArray = array($article, $category);
		$article['iurl'] = iURL::get('article', $_iurlArray, $page);
		$article['url'] = $article['iurl']->href;
		$article['link'] = "<a href='{$article['url']}'>{$article['title']}</a>";

		($tpl && $category['mode'] == '1') && iCMS::gotohtml($article['iurl']->path, $article['iurl']->href);

		$article['category'] = $categoryApp->get_lite($category);

		$this->taoke = false;
		if ($art_data) {
			$pkey = intval($page - 1);
			if ($article['chapter']) {
				$chapterArray = $art_data;
				$count = count($chapterArray);
				$adid = $chapterArray[$pkey]['id'];
				$art_data = iDB::row("SELECT body,subtitle FROM `#iCMS@__article_data` WHERE aid='" . (int) $article['id'] . "' AND id='" . (int) $adid . "' LIMIT 1;", ARRAY_A);
			}


			$article['pics'] = $this->body_pics($art_data['body'],$pic_array);

			if ($article['chapter']) {
				$article['body'] = $art_data['body'];
			} else {
				$body = explode('#--iCMS.PageBreak--#', $art_data['body']);
				$count = count($body);
				$article['body'] = $body[$pkey];
				unset($body);
			}

			$article['body']     = iPHP::app("keywords.app")->run($article['body']);
			$article['body']     = $this->body_ad($article['body']);
			$article['body']     = $this->taoke($article['body']);
			$article['taoke']    = $this->taoke;
			$article['subtitle'] = $art_data['subtitle'];
			unset($art_data);
			$total = $count + intval($this->config['pageno_incr']);
			$article['page'] = $this->page($article,$page,$total,$count,$category['mode']);
			is_array($article['page']['next'])&& $next_url = $article['page']['next']['url'];
			$pic_array[0] && $article['body'] = $this->body_pics_page($pic_array,$article,$page,$total,$next_url);
		}

		if ($vars['tag']) {
			$article['tags_fname'] = $category['name'];
			if ($article['tags']) {
				$tagApp    = iPHP::app("tag.app");
				$multi_tag =$tagApp->multi_tag(array($article['id']=>$article['tags']));
				$article+=(array)$multi_tag[$article['id']];
			}
			if(is_array($article['tags_array'])){
				$tags_fname            = array_slice ($article['tags_array'],0,1);
				$article['tags_fname'] = $tags_fname[0]['name'];
			}
			unset($tagApp, $multi_tag, $tags_fname);
		}

		if ($vars['meta']) {
			if ($article['metadata']) {
				$article['meta'] = unserialize($article['metadata']);
				unset($article['metadata']);
			}
		}
		if ($vars['user']) {
			iPHP::app('user.class', 'static');
			if ($article['postype']) {
				$article['user'] = user::empty_info($article['userid'], '#' . $article['editor']);
			} else {
				$article['user'] = user::info($article['userid'], $article['author']);
			}
		}
		$article['source'] = $this->text2link($article['source']);
		$article['author'] = $this->text2link($article['author']);

		$article['hits'] = array(
			'script' => iCMS_API . '?app=article&do=hits&cid=' . $article['cid'] . '&id=' . $article['id'],
			'count' => $article['hits'],
			'today' => $article['hits_today'],
			'yday' => $article['hits_yday'],
			'week' => $article['hits_week'],
			'month' => $article['hits_month'],
		);
		$article['comment'] = array(
			'url' => iCMS_API . "?app=article&do=comment&appid={$article['appid']}&iid={$article['id']}&cid={$article['cid']}",
			'count' => $article['comments'],
		);

		if ($article['picdata']) {
			$picdata = unserialize($article['picdata']);
		}
		unset($article['picdata']);

		$article['pic']  = get_pic($article['pic'], $picdata['b'], get_twh($vars['btw'], $vars['bth']));
		$article['mpic'] = get_pic($article['mpic'], $picdata['m'], get_twh($vars['mtw'], $vars['mth']));
		$article['spic'] = get_pic($article['spic'], $picdata['s'], get_twh($vars['stw'], $vars['sth']));
		$article['param'] = array(
			"appid" => $article['appid'],
			"iid"   => $article['id'],
			"cid"   => $article['cid'],
			"suid"  => $article['userid'],
			"title" => $article['title'],
			"url"   => $article['url'],
		);
		return $article;
	}
	public function data($aids=0){
		if(empty($aids)) return array();

		list($aids,$is_multi)  = iPHP::multi_ids($aids);
		$sql  = iPHP::where($aids,'aid',false,true);
		$data = array();
		$rs   = iDB::all("SELECT * FROM `#iCMS@__article_data` where {$sql}",OBJECT);
		if($rs){
			if($is_multi){
				$_count = count($rs);
		        for ($i=0; $i < $_count; $i++) {
		        	$data[$rs[$i]->aid]= $rs[$i];
		        }
			}else{
				$data = $rs[0];
			}
		}
        if(empty($data)){
            return;
        }
	   	return $data;
	}
	public function page($article,$page,$total,$count,$mode=null){
		$pageArray = array();
		$pageurl = $article['iurl']->pageurl;
		if ($total > 1) {
			iPHP::core("Pages");
			$_GLOBALS_iPage = $GLOBALS['iPage'];
			$mode && iPHP::set_page_url($article['iurl']);
			$pageconf = array(
				'page_name' => 'p',
				'url' => $pageurl,
				'total' => $total,
				'perpage' => 1,
				'nowindex' => (int) $_GET['p'],
				'lang' => iPHP::lang(iPHP_APP . ':page'),
			);
			if ($article['chapter']) {
				foreach ((array) $chapterArray as $key => $value) {
					$pageconf['titles'][$key + 1] = $value['subtitle'];
				}
			}
			$iPages = new iPages($pageconf);
			unset($GLOBALS['iPage']);
			$GLOBALS['iPage'] = $_GLOBALS_iPage;
			unset($_GLOBALS_iPage);

			$pageArray['list']  = $iPages->list_page();
			$pageArray['index'] = $iPages->first_page('array');
			$pageArray['prev']  = $iPages->prev_page('array');
			$pageArray['next']  = $iPages->next_page('array');
			$pageArray['endof'] = $iPages->last_page('array');
			$pagenav = $iPages->show(0);
			$pagetext = $iPages->show(10);
		}
		$article_page = array(
			'pn'      => $page,
			'total'   => $total, //总页数
			'count'   => $count, //实际页数
			'current' => $page,
			'nav'     => $pagenav,
			'pageurl' => $pageurl,
			'text'    => $pagetext,
			'PAGES'   => $iPages,
			'args'    => iSecurity::escapeStr($_GET['pageargs']),
			'first'   => ($page == "1" ? true : false),
			'last'    => ($page == $count ? true : false), //实际最后一页
			'end'     => ($page == $total ? true : false)
		) + $pageArray;
		unset($pagenav, $pagetext, $iPages, $pageArray);
		return $article_page;
	}
	public function text2link($text=null){
		if (strpos($text, '||') !== false) {
			list($title, $url) = explode('||', $text);
			return '<a href="' . $url . '" target="_blank">' . $title . '</a>';
		}else{
			return $text;
		}
	}

	public function taoke($content) {
		preg_match_all('/<[^>]+>((http|https):\/\/(item|detail)\.(taobao|tmall)\.com\/.+)<\/[^>]+>/isU', $content, $taoke_array);
		if ($taoke_array[1]) {
			$tk_array = array_unique($taoke_array[1]);
			foreach ($tk_array as $tkid => $tk_url) {
				$tk_url = htmlspecialchars_decode($tk_url);
				$tk_parse = parse_url($tk_url);
				parse_str($tk_parse['query'], $tk_item_array);
				$itemid = $tk_item_array['id'];
				$tk_data[$tkid] = $this->taoke_tpl($itemid, $tk_url);
			}
			$content = str_replace($tk_array, $tk_data, $content);
			$this->taoke = true;
		}
		return $content;
	}
	public function taoke_tpl($itemid, $url, $title = null) {
		iPHP::assign('taoke', array(
			'itemid' => $itemid,
			'title' => $title,
			'url' => $url,
		));
		return iPHP::fetch('iCMS://taoke.tpl.htm');
	}
	public function pnTitle($pn, $chapterArray, $chapter) {
		$title = $pn;
		$chapter && $title = $chapterArray[$pn - 1]['subtitle'];
		return $title;
	}
	public function body_pics($body,&$pic_array=array()){
        preg_match_all($this->pregimg,$body,$pic_array);
		$array = array_unique($pic_array[1]);
		$pics =  array();
		foreach ((array)$array as $key => $_pic) {
				$pics[$key] = trim($_pic);
		}
		return $pics;
	}
	public function body_pics_page($pic_array,$article,$page,$total,$next_url){
		$img_array = array_unique($pic_array[0]);
		foreach ($img_array as $key => $img) {
			$img = str_replace('<img', '<img title="' . $article['title'] . '" alt="' . $article['title'] . '"', $img);
			if ($this->config['pic_center']) {
                $img_replace[$key] = '<p class="article_pic">'.$img.'</p>';
			} else {
				$img_replace[$key] = $img;
			}
            if($this->config['pic_next'] && $total>1){
                $clicknext = '<a href="'.$next_url.'"><b>'.iPHP::lang('iCMS:article:clicknext').' ('.$page.'/'.$total.')</b></a>';
				$clickimg = '<a href="' . $next_url . '" title="' . $article['title'] . '" class="img">' . $img . '</a>';
				if ($this->config['pic_center']) {
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
    public function body_ad($content){
        $pieces    = 1000;
        $html      = str_replace('</p>', "</p>\n", $content);
        $htmlArray = explode("\n", $html);
        $resource  = array();
        //计算长度
        preg_match_all($this->pregimg,$content,$img_array);
        $len = strlen($content)+(count($img_array[1])*300);

        if($len<($pieces*1.5)){
            return $content;
        }
        $i = 0;
        foreach ($htmlArray as $key => $phtm) {
            $pLen += strlen($phtm);
            if(strpos($phtm, '<img')!==false){
                $pLen +=100;
            }
            $llen = $len-$pLen;
            // if($_GET['debug']){
            //     var_dump(substr($phtm, 0,30));
            //     var_dump($pLen,$llen,floor($llen/$pieces),'=========');
            // }
            if($pLen>$pieces && floor($llen/$pieces)>=1){
                // if($_GET['debug']){
                //     var_dump('---------------------------');
                // }
                $ad = '<script>if(typeof showBodyUI==="function")showBodyUI("body.'.$i.'");</script>';
                $resource[$i].= $phtm.$ad;
                $pLen = 0;
                $len = $llen;
                $i++;
            }else{
                $resource[$i].= $phtm;
            }
        }
        unset($html,$htmlArray);
        return implode('', (array)$resource);
    }
}
