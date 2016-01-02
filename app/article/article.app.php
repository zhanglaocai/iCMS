<?php
/**
 * @package iCMS
 * @copyright 2007-2015, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 * @$Id: article.app.php 2408 2014-04-30 18:58:23Z coolmoo $
 */
class articleApp {
	public $methods	= array('iCMS','article','clink','hits','good','bad','like_comment','comment');
    public function __construct() {}

    public function do_iCMS($a = null) {
    	return $this->article((int)$_GET['id'],isset($_GET['p'])?(int)$_GET['p']:1);
    }
    public function do_clink($a = null) {
        $clink = iS::escapeStr($_GET['clink']);
        $id    = iDB::value("SELECT `id` FROM `#iCMS@__article` WHERE `clink`='".$clink."' AND `status` ='1';");
        return $this->article((int)$id,isset($_GET['p'])?(int)$_GET['p']:1);
    }
    public function API_iCMS(){
        return $this->do_iCMS();
    }
    public function API_clink(){
        return $this->do_clink();
    }
    public function API_hits($id = null){
        $id===null && $id = (int)$_GET['id'];
        if($id){
            $sql = iCMS::hits_sql();
            iDB::query("UPDATE `#iCMS@__article` SET {$sql} WHERE `id` ='$id'");
        }
    }
    public function API_good(){
        $this->vote('good');
    }
    public function API_bad(){
        $this->vote('bad');
    }
    public function API_comment(){
        $appid = (int)$_GET['appid'];
        $cid   = (int)$_GET['cid'];
        $iid   = (int)$_GET['iid'];
        $this->article($iid,1,'{iTPL}/article.comment.htm');
    }
    private function vote($_do){
        // iPHP::app('user.class','static');
        // user::get_cookie() OR iPHP::code(0,'iCMS:!login',0,'json');

        $aid = (int)$_GET['iid'];
        $aid OR iPHP::code(0,'iCMS:article:empty_id',0,'json');

        $ackey = 'article_'.$_do.'_'.$aid;
        $vote  = iPHP::get_cookie($ackey);
        $vote && iPHP::code(0,'iCMS:article:!'.$_do,0,'json');

        if($_do=='good'){
            $sql = '`good`=good+1';
        }else{
            $sql = '`bad`=bad+1';
        }
        iDB::query("UPDATE `#iCMS@__article` SET {$sql} WHERE `id` ='{$aid}' limit 1");
        iPHP::set_cookie($ackey,time(),86400);
        iPHP::code(1,'iCMS:article:'.$_do,0,'json');

    }
    public function article($id,$page=1,$tpl=true){
        $article = iDB::row("SELECT * FROM `#iCMS@__article` WHERE id='".(int)$id."' AND `status` ='1' LIMIT 1;",ARRAY_A);
        $article OR iPHP::throw404('运行出错！找不到文章: <b>ID:'. $id.'</b>', 10001);
        if($article['url']) {
            if(iPHP::$iTPL_MODE=="html") {
                return false;
            }else {
            	$this->API_hits($id);
                iPHP::gotourl($article['url']);
            }
        }
        if(iCMS_ARTICLE_DATA==="TEXT"){
            iPHP::app('article.table');
            $article_data = articleTable::get_text($id);
        }else{
            if($article['chapter']){
                $all = iDB::all("SELECT id,subtitle FROM `#iCMS@__article_data` WHERE aid='".(int)$id."';",ARRAY_A);
                foreach ($all as $akey => $value) {
                    $article_data[] = $value;
                }
                unset($all);
                ksort($article_data);
            }else{
                $article_data = iDB::row("SELECT body,subtitle FROM `#iCMS@__article_data` WHERE aid='".(int)$id."' LIMIT 1;",ARRAY_A);
            }
        }
        $vars = array(
            'tags'          =>true,
            'user'          =>true,
            'meta'          =>true,
            'prev_next'     =>true,
            'category_lite' =>false,
        );
        $article = $this->value($article,$article_data,$vars,$page,$tpl);

        unset($article_data);
        if($article===false) return false;

        if($tpl) {
            iCMS::hooks('enable_comment',true);
            $article_tpl = empty($article['tpl'])?$article['category']['contentTPL']:$article['tpl'];
            strstr($tpl,'.htm') && $article_tpl	= $tpl;
            iPHP::assign('category',$article['category']);
            unset($article['category']);
            iPHP::assign('article',$article);
            $html	= iPHP::view($article_tpl,'article');
            if(iPHP::$iTPL_MODE=="html") return array($html,$article);
        }else{
            return $article;
        }
    }
    public function value($article,$art_data="",$vars=array(),$page=1,$tpl=false){

        $article['appid'] = iCMS_APP_ARTICLE;

        $categoryApp = iPHP::app("category");
        $category    = $categoryApp->category($article['cid'],false);

        if($tpl){
            $category OR iPHP::throw404('运行出错！找不到该文章的栏目缓存<b>cid:'. $article['cid'].'</b> 请更新栏目缓存或者确认栏目是否存在', 10002);
        }else{
            if(empty($category)) return false;
        }

        if($category['status']==0) return false;

        if(iPHP::$iTPL_MODE=="html" && $tpl && (strstr($category['contentRule'],'{PHP}')||$category['outurl']||$category['mode']=="0")) return false;

        $_iurlArray      = array($article,$category);
        $article['iurl'] = iURL::get('article',$_iurlArray,$page);
        $article['url']  = $article['iurl']->href;
        $article['link'] = "<a href='{$article['url']}'>{$article['title']}</a>";

        ($tpl && $category['mode']=='1') && iCMS::gotohtml($article['iurl']->path,$article['iurl']->href);

        if($vars['category_lite']){
            $article['category'] = $categoryApp->get_lite($category);
        }else{
            $article['category'] = $category;
        }
        $this->taoke = false;
        if($art_data){
            $pkey    = intval($page-1);
            $pageurl = $article['iurl']->pageurl;
            if($article['chapter']){
                $chapterArray = $art_data;
                $count        = count($chapterArray);
                $adid         = $chapterArray[$pkey]['id'];
                $art_data = iDB::row("SELECT body,subtitle FROM `#iCMS@__article_data` WHERE aid='".(int)$article['id']."' AND id='".(int)$adid."' LIMIT 1;",ARRAY_A);
            }

            $art_data['body'] = $this->ubb($art_data['body']);

            preg_match_all("/<img.*?src\s*=[\"|'|\s]*(http:\/\/.*?\.(gif|jpg|jpeg|bmp|png)).*?>/is",$art_data['body'],$pic_array);
            $p_array = array_unique($pic_array[1]);
            if($p_array)foreach($p_array as $key =>$_pic) {
                $article['pics'][$key] = trim($_pic);
            }
            if(substr($art_data['body'], 0,19)=='#--iCMS.Markdown--#'){
                // $art_data['body']    = iPHP::Markdown($art_data['body']);
                $art_data['body']    = substr($art_data['body'], 19);
                $article['markdown'] = ture;
            }

            if($article['chapter']){
                $article['body'] = $art_data['body'];
            }else{
                $body            = explode('#--iCMS.PageBreak--#',$art_data['body']);
                $count           = count($body);
                $article['body'] = $body[$pkey];
            }

            $total = $count+intval(iCMS::$config['article']['pageno_incr']);
            $article['body']     = $this->keywords($article['body']);
            $article['body']     = $this->taoke($article['body']);
            $article['taoke']    = $this->taoke;
            $article['subtitle'] = $art_data['subtitle'];
            unset($body,$art_data);
            $pageArray = array();

            if($total>1) {
                $flag    = 0;
                for($i=1;$i<=$total;$i++) {
                    $pagea = array(
                        'pn'    => $i,
                        'url'   => iPHP::p2num($pageurl,$i),
                        'title' => $this->pnTitle($i,$chapterArray,$article['chapter'])
                    );
                    $pagea['link'] = "<a href='".$pagea['url']."'>".$pagea['title']."</a>";
                    $pageArray['list'][] = $pagea;
                }
                $pageArray['index']         = array('url'=> $article['url'],'title' => iPHP::lang('iCMS:page:index'));
                $pageArray['index']['link'] = "<a href='".$pageArray['index']['url']."'>".$pageArray['index']['title']."</a>";
                $pageArray['prev']          = array('url' => iPHP::p2num($pageurl,($page-1>1)?$page-1:1),'title' => iPHP::lang('iCMS:page:prev'));
                $pageArray['prev']['link']  = "<a href='".$pageArray['prev']['url']."'>".$pageArray['prev']['title']."</a>";
                $pageArray['next']          = array('url' => iPHP::p2num($pageurl,(($total-$page>0)?$page+1:$page)),'title' => iPHP::lang('iCMS:page:next'));
                $pageArray['next']['link']  = "<a href='".$pageArray['next']['url']."'>".$pageArray['next']['title']."</a>";
                $pageArray['endof']         = array('url' => iPHP::p2num($pageurl,$total),'title' => '共'.$total.'页');
                $pageArray['endof']['link'] = "<a href='".$pageArray['endof']['url']."'>".$pageArray['endof']['title']."</a>";

                $length = 3;
                $offset = $page-$length-1;
                if($offset<$length-1){
                    $offset = 0;
                    $length = 6;
                }
                if($offset>=$total-6){
                    $offset = $total-6;
                    $length = 6;
                }
                $output = array_slice ($pageArray['list'],$offset,$length);
                if($length!=6){
                    $output  =  array_merge ((array) $output ,array($pageArray['list'][$page-1]),(array) array_slice ($pageArray['list'],$page,3) );
                }
                $indexprev = $pageArray['index']['link'].$pageArray['prev']['link'];
                $nextendof = $pageArray['next']['link'].$pageArray['endof']['link'];
                $listnav   = '';
                foreach ((array)$output as $key => $value) {
                    if($page==$value['pn']){
                        $listnav.= '<span class="current">'.$value['title'].'</span>';
                    }else{
                        $listnav.= $value['link'];
                    }
                }
                $pagenav  = $indexprev.$listnav.$nextendof;
                $pagetext = $indexprev.'<span class="current">'.$this->pnTitle($page,$chapterArray,$article['chapter']).'</span>'.$nextendof;
                unset($indexprev,$listnav,$nextendof);
            }
            $article['page'] = array(
                'pn'      => $page,
                'total'   => $total,//总页数
                'count'   => $count,//实际页数
                'current' => $page,
                'nav'     => $pagenav,
                'pageurl' => $pageurl,
                'text'    => $pagetext,
                'args'    => iS::escapeStr($_GET['pageargs']),
                'first'   => ($page=="1"?true:false),
                'last'    => ($page==$count?true:false),//实际最后一页
                'end'     => ($page==$total?true:false)
            )+$pageArray;
            $next_url = $pageArray['next']['url'];
            unset($pageArray,$pagea,$output,$pagetext);
            if($pic_array[0]){
                $img_array = array_unique($pic_array[0]);
                foreach($img_array as $key =>$img){
                    $img = str_replace('<img', '<img title="'.$article['title'].'" alt="'.$article['title'].'"', $img);
                    if(iCMS::$config['article']['pic_center']){
                        $img_replace[$key] = '<p align="center">'.$img.'</p>';
                    }else{
                        $img_replace[$key] = $img;
                    }
                    if(iCMS::$config['article']['pic_next'] && $count>1){
                        $clicknext = '<a href="'.$next_url.'"><b>'.iPHP::lang('iCMS:article:clicknext').'</b></a>';
                        $clickimg  = '<a href="'.$next_url.'" title="'.$article['title'].'" class="img">'.$img.'</a>';
                        if(iCMS::$config['article']['pic_center']){
                            $img_replace[$key] = '<p align="center">'.$clicknext.'</p>';
                            $img_replace[$key].= '<p align="center">'.$clickimg.'</p>';
                        }else{
                            $img_replace[$key] = '<p>'.$clicknext.'</p>';
                            $img_replace[$key].= '<p>'.$clickimg.'</p>';
                        }
                    }
                }
                $article['body'] = str_replace($img_array,$img_replace,$article['body']);
            }

        }

        if($vars['tags']){
            $article['tags_fname'] = $category['name'];
            if($article['tags']) {
                $tagApp   = iPHP::app("tag");
                $tagArray = $tagApp->get_array($article['tags']);
                $article['tag_array'] = array();
                foreach((array)$tagArray AS $tk=>$tag) {
                    $article['tag_array'][$tk] = $tag;
                    $article['tags_link'].= $tag['link'];
                    $tag_name_array[] = $tag['name'];
                }
                $tag_name_array && $article['tags_fname'] = $tag_name_array[0];
                unset($tagApp,$tagArray,$tag_name_array);
            }
        }

        if($vars['meta']){
            if($article['metadata']){
                $article['meta'] = unserialize($article['metadata']);
                unset($article['metadata']);
            }
        }
        if($vars['user']){
            iPHP::app('user.class','static');
            if($article['postype']){
                $article['user'] = user::empty_info($article['userid'],'#'.$article['editor']);
            }else{
                $article['user'] = user::info($article['userid'],$article['author']);
            }
        }


        if(strstr($article['source'], '||')){
            list($s_name,$s_url) = explode('||',$article['source']);
            $article['source']   = '<a href="'.$s_url.'" target="_blank">'.$s_name.'</a>';
        }
        if(strstr($article['author'], '||')){
            list($a_name,$a_url) = explode('||',$article['author']);
            $article['author']   = '<a href="'.$a_url.'" target="_blank">'.$a_name.'</a>';
        }

        $article['hits'] = array(
            'script' => iCMS_API.'?app=article&do=hits&cid='.$article['cid'].'&id='.$article['id'],
            'count'  => $article['hits'],
            'today'  => $article['hits_today'],
            'yday'   => $article['hits_yday'],
            'week'   => $article['hits_week'],
            'month'  => $article['hits_month'],
        );
        $article['comment'] = array(
            'url'   => iCMS_API."?app=article&do=comment&appid={$article['appid']}&iid={$article['id']}&cid={$article['cid']}",
            'count' => $article['comments']
        );

        if($article['picdata']){
            $picdata = unserialize($article['picdata']);
        }
        unset($article['picdata']);

        $article['pic']   = get_pic($article['pic'],$picdata['b'],get_twh($vars['btw'],$vars['bth']));
        $article['mpic']  = get_pic($article['mpic'],$picdata['m'],get_twh($vars['mtw'],$vars['mth']));
        $article['spic']  = get_pic($article['spic'],$picdata['s'],get_twh($vars['stw'],$vars['sth']));
        $article['param'] = array(
            "appid" => $article['appid'],
            "iid"   => $article['id'],
            "cid"   => $article['cid'],
            "suid"  => $article['userid'],
            "title" => $article['title'],
            "url"   => $article['url']
        );
        return $article;
    }
    public function ubb($content){
        if(strpos($content, '[img]')!==false){
            $content = stripslashes($content);
            preg_match_all("/\[img\][\"|'|\s]*(http:\/\/.*?\.(gif|jpg|jpeg|bmp|png|webp))\[\/img\]/is",$content,$img_array);
            if($img_array[1]){
                foreach ($img_array[1] as $key => $src) {
                    $imgs[$key] = '<p><img src="'.$src.'" /></p>';
                }
                $content = str_replace($img_array[0],$imgs, $content);
            }
        }
        return $content;
    }
    //内链
    public function keywords($content) {
        if(iCMS::$config['other']['keyword_limit']==0) return $content;

        $keywords = iCache::get('iCMS/keywords');

        if($keywords){
            foreach($keywords AS $i=>$val) {
                if($val['times']>0) {
                    $search[]  = $val['keyword'];
                    $replace[] = '<a class="keyword" target="_blank" href="'.$val['url'].'">'.$val['keyword'].'</a>';
                }
           }
           return iCMS::str_replace_limit($search, $replace,stripslashes($content),iCMS::$config['other']['keyword_limit']);
        }
        return $content;
    }
    public function taoke($content){
        preg_match_all('/<[^>]+>((http|https):\/\/(item|detail)\.(taobao|tmall)\.com\/.+)<\/[^>]+>/isU',$content,$taoke_array);
        if($taoke_array[1]){
            $tk_array = array_unique($taoke_array[1]);
            foreach ($tk_array as $tkid => $tk_url) {
                    $tk_url   = htmlspecialchars_decode($tk_url);
                    $tk_parse = parse_url($tk_url);
                    parse_str($tk_parse['query'], $tk_item_array);
                    $itemid         = $tk_item_array['id'];
                    $tk_data[$tkid] = $this->tmpl($itemid,$tk_url);
            }
            $content = str_replace($tk_array,$tk_data,$content);
            $this->taoke = true;
        }
        return $content;
    }
    public function tmpl($itemid,$url,$title=null){
        iPHP::assign('taoke',array(
            'itemid' => $itemid,
            'title'  => $title,
            'url'    => $url,
        ));
        return iPHP::fetch('iCMS://taoke.tmpl.htm');
    }
    public function pnTitle($pn,$chapterArray,$chapter){
        $title = $pn;
        $chapter && $title = $chapterArray[$pn-1]['subtitle'];
        return $title;
    }
}
