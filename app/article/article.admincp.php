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

iPHP::app('article.class');

class articleAdmincp{
    public $callback = array();
    public $chapter  = false;
    public $config   = null;

    function __construct() {
        $this->appid       = iCMS_APP_ARTICLE;
        $this->id          = (int)$_GET['id'];
        $this->dataid      = (int)$_GET['dataid'];
        $this->categoryApp = iPHP::app('category.admincp',$this->appid);
        $this->_postype    = '1';
        $this->_status     = '1';
        $this->config      = iCMS::$config['article'];

        define('TAG_APPID',$this->appid);

    }
    function category($cid){
        return $this->categoryApp->get($cid);
    }
    function do_config(){
        $setting = admincp::app('setting');
        $setting->app($this->appid);
    }
    function do_save_config(){
        $setting = admincp::app('setting');
        $setting->save($this->appid);
    }

    function do_add(){
        $_GET['cid'] && admincp::CP($_GET['cid'],'ca','page');//添加权限
        $rs      = array();
        if($this->id){
            list($rs,$adRs) = article::data($this->id,$this->dataid);
            admincp::CP($rs['cid'],'ce','page');//编辑权限
            if($adRs){
                if($rs['chapter']){
                    foreach ($adRs as $key => $value) {
                        $adIdArray[$key] = $value['id'];
                        $cTitArray[$key] = $value['subtitle'];
                        $bodyArray[$key] = $value['body'];
                    }
                }else{
                    $adRs['body'] = htmlspecialchars($adRs['body']);
                    if($rs['markdown']){
                        $this->config['editor'] = true;
                        $adRs['body'] = substr($adRs['body'], 19);
                    }
                    $adIdArray = array($adRs['id']);
                    $bodyArray = explode('#--iCMS.PageBreak--#',$adRs['body']);
                }
            }
        }

        $bodyCount = count($bodyArray);
        $bodyCount OR $bodyCount = 1;
        $cid                 = empty($rs['cid'])?(int)$_GET['cid']:$rs['cid'];
        $cata_option         = $this->categoryApp->select('ca',$cid);

        //$metadata          = array_merge((array)$contentprop,(array)$rs['metadata']);
        $rs['pubdate']       = get_date($rs['pubdate'],'Y-m-d H:i:s');
        $rs['metadata'] && $rs['metadata'] = unserialize($rs['metadata']);
        $rs['markdown'] &&  $this->config['markdown'] = "1";
        if(empty($this->id)){
            $rs['status']  = "1";
            $rs['postype'] = "1";
            $rs['editor']  = empty(iMember::$data->nickname)?iMember::$data->username:iMember::$data->nickname;
            $rs['userid']  = iMember::$userid;
		}

        $strpos   = strpos(__REF__,'?');
        $REFERER  = $strpos===false?'':substr(__REF__,$strpos);
        $defArray = iCache::get('iCMS/defaults');
        $propApp  = iPHP::app('prop.admincp');
        if($this->config['markdown']){
            include admincp::view("article.markdown");
        }else{
            include admincp::view("article.add");
        }
    }
    function do_update(){
    	$data = admincp::fields($_GET['iDT']);
        if($data){
            if(isset($data['pid'])){
                iCMS::core('Map');
                iMap::init('prop',$this->appid);
                $_pid = article::value('pid',$this->id);
                iMap::diff($data['pid'],$_pid,$this->id);
            }
            article::update($data,array('id'=>$this->id));
        }
    	iPHP::success('操作成功!','js:1');
    }
    function do_updateorder(){
        foreach((array)$_POST['ordernum'] as $ordernum=>$id){
            article::update(compact('ordernum'),compact('id'));
        }
    }
    function do_batch(){
    	$_POST['id'] OR iPHP::alert("请选择要操作的文章");
    	$ids	= implode(',',(array)$_POST['id']);
    	$batch	= $_POST['batch'];
    	switch($batch){
    		case 'order':
		        foreach((array)$_POST['ordernum'] AS $id=>$ordernum) {
                    article::update(compact('ordernum'),compact('id'));
		        }
		        iPHP::success('排序已更新!','js:1');
    		break;
            case 'baiduping':
                foreach((array)$_POST['id'] AS $id) {
                    $this->do_baiduping($id,false);
                }
                iPHP::success('推送完成!','js:1');
            break;
    		case 'move':
		        $_POST['cid'] OR iPHP::alert("请选择目标栏目!");
                iCMS::core('Map');
                iMap::init('category',$this->appid);
                $cid = (int)$_POST['cid'];
                admincp::CP($cid,'ca','alert');
		        foreach((array)$_POST['id'] AS $id) {
                    $_cid = article::value('cid',$id);
                    article::update(compact('cid'),compact('id'));
		            if($_cid!=$cid) {
                        iMap::diff($cid,$_cid,$id);
                        $this->categoryApp->update_count_one($_cid,'-');
                        $this->categoryApp->update_count_one($cid);
		            }
		        }
		        iPHP::success('成功移动到目标栏目!','js:1');
            break;
            case 'scid':
                //$_POST['scid'] OR iPHP::alert("请选择目标栏目!");
                iCMS::core('Map');
                iMap::init('category',$this->appid);
                $scid = implode(',', (array)$_POST['scid']);
                foreach((array)$_POST['id'] AS $id) {
                    $_scid = article::value('scid',$id);
                    article::update(compact('scid'),compact('id'));
                    iMap::diff($scid,$_scid,$id);
                }
                iPHP::success('文章副栏目设置完成!','js:1');
            break;
            case 'prop':
                iCMS::core('Map');
                iMap::init('prop',$this->appid);
                $pid = implode(',', (array)$_POST['pid']);
                foreach((array)$_POST['id'] AS $id) {
                    $_pid = article::value('pid',$id);
                    article::update(compact('pid'),compact('id'));
                    iMap::diff($pid,$_pid,$id);
                }
                iPHP::success('文章属性设置完成!','js:1');
    		break;
    		case 'weight':
                $data = array('weight'=>$_POST['mweight']);
    		break;
    		case 'keyword':
    			if($_POST['pattern']=='replace') {
                    $data = array('keywords'=>iS::escapeStr($_POST['mkeyword']));
    			}elseif($_POST['pattern']=='addto') {
		        	foreach($_POST['id'] AS $id){
                        $keywords = article::value('keywords',$id);
                        $keywords = $keywords?$keywords.','.iS::escapeStr($_POST['mkeyword']):iS::escapeStr($_POST['mkeyword']);
                        article::update(compact('keywords'),compact('id'));
		        	}
		        	iPHP::success('文章关键字更改完成!','js:1');
    			}
    		break;
    		case 'tag':
    			iPHP::app('tag.class','static');
		     	foreach($_POST['id'] AS $id){
                    $art  = article::row($id,'tags,cid');
                    $mtag = iS::escapeStr($_POST['mtag']);
			        if($_POST['pattern']=='replace') {
			        }elseif($_POST['pattern']=='addto') {
			        	$art['tags'] && $mtag = $art['tags'].','.$mtag;
			        }
			        $tags = tag::diff($mtag,$art['tags'],iMember::$userid,$id,$art['cid']);
                    $tags = addslashes($tags);
                    article::update(compact('tags'),compact('id'));
		    	}
		    	iPHP::success('文章标签更改完成!','js:1');
    		break;
    		case 'thumb':
		        foreach((array)$_POST['id'] AS $id) {
		            $body	= article::body($id);
                    $picurl = $this->remotepic($body,'autopic',$id);
                    $this->set_pic($picurl,$id);
		        }
		        iPHP::success('成功提取缩略图!','js:1');
    		break;
    		case 'dels':
    			iPHP::$break	= false;
    			ob_implicit_flush();
    			$_count	= count($_POST['id']);
				foreach((array)$_POST['id'] AS $i=>$id) {
			     	$msg= $this->del($id);
			        $msg.= $this->del_msg('文章删除完成!');
					$updateMsg	= $i?true:false;
					$timeout	= ($i++)==$_count?'3':false;
					iPHP::dialog($msg,'js:parent.$("#id'.$id.'").remove();',$timeout,0,$updateMsg);
		        	ob_end_flush();
	   			}
	   			iPHP::$break	= true;
				iPHP::success('文章全部删除完成!','js:1',3,0,true);
    		break;
    		default:
				$data = admincp::fields($batch);
    	}
        $data && article::batch($data,$ids);
		iPHP::success('操作成功!','js:1');
    }
    function do_baiduping($id = null,$dialog=true){
        $id===null && $id=$this->id;
        $id OR iPHP::alert('请选择要推送的文章!');
        $rs   = article::row($id);
        $C    = $this->category($rs['cid']);
        $iurl = iURL::get('article',array($rs,$C));
        $url  = $iurl->href;
        $res  = baidu_ping($url);
        if($res===true){
            $dialog && iPHP::success('推送完成','js:1');
        }else{
            iPHP::alert('推送失败！['.$res->message.']','js:1');
        }
    }
    function do_getjson(){
        $id = (int)$_GET['id'];
        $rs = article::row($id);
        iPHP::json($rs);
    }
    function do_getmeta(){
        $cid = $_GET['cid'];
    }
	function do_updatetitle(){
        $id          = (int)$_POST['id'];
        $cid         = (int)$_POST['cid'];
        $pid         = (int)$_POST['pid'];
        $source      = iS::escapeStr($_POST['source']);
        $title       = iS::escapeStr($_POST['title']);
        $tags        = iS::escapeStr($_POST['tags']);
        $description = iS::escapeStr($_POST['description']);

		$art = article::row($id,'tags,cid');
		if($tags){
			iPHP::app('tag.class','static');
			$tags = tag::diff($tags,$art['tags'],iMember::$userid,$id,$art['cid']);
		    $tags = addslashes($tags);
        }
        $data = compact('cid','pid','title','tags','description');
		if($_POST['status']=="1"){
            $data['status'] = 1;
		}
		if($_POST['statustime']=="1"){
            $data['status']  = 1;
            $data['pubdate'] = time();
		}
        article::update($data ,compact('id'));
		exit('1');
	}
    function do_findpic(){
        $content = article::body($this->id);
        if($content){
            $content = stripslashes($content);
            preg_match_all("/<img.*?src\s*=[\"|'](.*?)[\"|']/is", $content, $match);
            $array  = array_unique($match[1]);
            $uri    = parse_url(iCMS_FS_URL);
            $fArray = array();
            foreach ($array as $key => $value) {
                $value = trim($value);
                // echo $value.PHP_EOL;
                if (stripos($value,$uri['host']) !== false){
                    $filepath = iFS::fp($value,'-http');
                    $rpath    = iFS::fp($value,'http2iPATH');
                   if($filepath){
                        $pf   = pathinfo($filepath);
                        $rs[] = array(
                            'id'       => 'path@'.$filepath,
                            'path'     => rtrim($pf['dirname'],'/').'/',
                            'filename' => $pf['filename'],
                            'size'     => @filesize($rpath),
                            'time'     => @filectime($rpath),
                            'ext'      => $pf['extension']
                        );
                    }
                }
                // echo "<hr />";
            }
            $_count = count($rs);
        }
        include admincp::view("files.manage","admincp");
    }
    function do_preview(){
		echo article::body($this->id);
    }
    function do_iCMS(){
    	admincp::$APP_DO="manage";
    	$this->do_manage();
    }
    function do_inbox(){
    	$this->do_manage("inbox");
    }
    function do_trash(){
        $this->_postype = 'all';
    	$this->do_manage("trash");
    }
    function do_user(){
        $this->_postype = 0;
        $this->do_manage();
    }
    function do_examine(){
        $this->_postype = 0;
        $this->do_manage("examine");
    }
    function do_off(){
        $this->_postype = 0;
        $this->do_manage("off");
    }

    function do_manage($stype='normal') {
        $cid = (int)$_GET['cid'];
        $pid = $_GET['pid'];
        //$stype OR $stype = admincp::$app_do;
        $stype_map = array(
            'inbox'   =>'0',//草稿
            'normal'  =>'1',//正常
            'trash'   =>'2',//回收站
            'examine' =>'3',//待审核
            'off'     =>'4',//未通过
        );
        $map_where = array();
        //status:[0:草稿][1:正常][2:回收][3:待审核][4:不合格]
        //postype: [0:用户][1:管理员]
        $stype && $this->_status = $stype_map[$stype];
        if(isset($_GET['pt']) && $_GET['pt']!=''){
            $this->_postype = (int)$_GET['pt'];
        }
        if(isset($_GET['status'])){
            $this->_status = (int)$_GET['status'];
        }
        $sql = "WHERE `status`='{$this->_status}'";
        $this->_postype==='all' OR $sql.= " AND `postype`='{$this->_postype}'";

        if(admincp::MP("ARTICLE.VIEW")){
            $_GET['userid'] && $sql.= iPHP::where($_GET['userid'],'userid');
        }else{
            $sql.= iPHP::where(iMember::$userid,'userid');
        }

        if(isset($_GET['pid']) && $pid!='-1'){
            $uri_array['pid'] = $pid;
            if(empty($_GET['pid'])){
                $sql.= " AND `pid`=''";
            }else{
                iCMS::core('Map');
                iMap::init('prop',$this->appid);
                $map_where+=iMap::where($pid);
            }
        }

        $cp_cids = admincp::CP('__CID__','cs');//取得所有有权限的栏目ID

        if($cp_cids) {
            if(is_array($cp_cids)){
                if($cid){
                    array_search($cid,$cp_cids)===false && admincp::permission_msg('栏目[cid:'.$cid.']',$ret);
                }else{
                    $cids = $cp_cids;
                }
            }else{
                $cids = $cid;
            }
            if($_GET['sub'] && $cid){
                $cids = iPHP::app("category")->get_ids($cid,true);
                array_push ($cids,$cid);
            }
            if($_GET['scid'] && $cid){
                iCMS::core('Map');
                iMap::init('category',$this->appid);
                $map_where+= iMap::where($cids);
            }else{
                $sql.= iPHP::where($cids,'cid');
            }
        }else{
            $sql.= iPHP::where('-1','cid');
        }

        if($_GET['keywords']) {
            $kws = $_GET['keywords'];
            switch ($_GET['st']) {
                case "title": $sql.=" AND `title` REGEXP '{$kws}'";break;
                case "tag":   $sql.=" AND `tags` REGEXP '{$kws}'";break;
                case "source":$sql.=" AND `source` REGEXP '{$kws}'";break;
                case "weight":$sql.=" AND `weight`='{$kws}'";break;
                case "id":
                $kws = str_replace(',', "','", $kws);
                $sql.=" AND `id` IN ('{$kws}')";
                break;
                case "tkd":   $sql.=" AND CONCAT(title,keywords,description) REGEXP '{$kws}'";break;
            }
        }
        $_GET['title']     && $sql.=" AND `title` like '%{$_GET['title']}%'";
        $_GET['tag']       && $sql.=" AND `tags` REGEXP '[[:<:]]".preg_quote(rawurldecode($_GET['tag']),'/')."[[:>:]]'";
        $_GET['starttime'] && $sql.=" AND `pubdate`>='".iPHP::str2time($_GET['starttime']." 00:00:00")."'";
        $_GET['endtime']   && $sql.=" AND `pubdate`<='".iPHP::str2time($_GET['endtime']." 23:59:59")."'";
        $_GET['post_starttime'] && $sql.=" AND `postime`>='".iPHP::str2time($_GET['post_starttime']." 00:00:00")."'";
        $_GET['post_endtime']   && $sql.=" AND `postime`<='".iPHP::str2time($_GET['post_endtime']." 23:59:59")."'";
        isset($_GET['pic'])&& $sql.=" AND `haspic` ='".($_GET['pic']?1:0)."'";

        isset($_GET['userid']) && $uri_array['userid']  = (int)$_GET['userid'];
        isset($_GET['keyword'])&& $uri_array['keyword'] = $_GET['keyword'];
        isset($_GET['tag'])    && $uri_array['tag']	    = $_GET['tag'];
        isset($_GET['pt'])     && $uri_array['pt']      = $_GET['pt'];
        isset($_GET['cid'])    && $uri_array['cid']     = $_GET['cid'];
		$uri_array	&& $uri = http_build_query($uri_array);

        $orderby    = $_GET['orderby']?$_GET['orderby']:"id DESC";
        $maxperpage = $_GET['perpage']>0?(int)$_GET['perpage']:20;

        if($map_where){
            $map_sql = iCMS::map_sql($map_where);
            $sql     = ",({$map_sql}) map {$sql} AND `id` = map.`iid`";
        }

        $total = iPHP::total(false,article::count_sql($sql),"G");
        iPHP::pagenav($total,$maxperpage,"篇文章");

        $limit = 'LIMIT '.iPHP::$offset.','.$maxperpage;

        if($map_sql||iPHP::$offset){
            // if($map_sql){
                $ids_array = iDB::all("
                    SELECT `id` FROM `#iCMS@__article` {$sql}
                    ORDER BY {$orderby} {$limit}
                ");
                $ids = iPHP::values($ids_array);
                $ids = $ids?$ids:'0';
                $sql = "WHERE `id` IN({$ids})";
            // }else{
                // $sql = ",(
                    // SELECT `id` AS aid FROM `#iCMS@__article` {$sql}
                    // ORDER BY {$orderby} {$limit}
                // ) AS art WHERE `id` = art.aid ";
            // }
            $limit = '';
        }
        $rs = iDB::all("SELECT * FROM `#iCMS@__article` {$sql} ORDER BY {$orderby} {$limit}");
        $_count = count($rs);
        $propArray = admincp::prop_get("pid",null,'array');
        include admincp::view("article.manage");
    }
    function do_save(){
        $aid         = (int)$_POST['aid'];
        $cid         = (int)$_POST['cid'];
        admincp::CP($cid,($aid?'ce':'ca'),'alert');


        $userid      = (int)$_POST['userid'];
        $scid        = implode(',', (array)$_POST['scid']);
        $pid         = implode(',', (array)$_POST['pid']);
        $status      = (int)$_POST['status'];
        $chapter     = (int)$_POST['chapter'];
        $ordernum    = (int)$_POST['ordernum'];
        $weight      = (int)$_POST['weight'];

        $hits        = (int)$_POST['hits'];
        $hits_today  = (int)$_POST['hits_today'];
        $hits_yday   = (int)$_POST['hits_yday'];
        $hits_week   = (int)$_POST['hits_week'];
        $hits_month  = (int)$_POST['hits_month'];
        $favorite    = (int)$_POST['favorite'];
        $comments    = (int)$_POST['comments'];
        $good        = (int)$_POST['good'];
        $bad         = (int)$_POST['bad'];

        $_cid        = iS::escapeStr($_POST['_cid']);
        $_pid        = iS::escapeStr($_POST['_pid']);
        $_scid       = iS::escapeStr($_POST['_scid']);
        $_tags       = iS::escapeStr($_POST['_tags']);
        $title       = iS::escapeStr($_POST['title']);
        $stitle      = iS::escapeStr($_POST['stitle']);
        $pic         = iS::escapeStr($_POST['pic']);
        $mpic        = iS::escapeStr($_POST['mpic']);
        $spic        = iS::escapeStr($_POST['spic']);
        $source      = iS::escapeStr($_POST['source']);
        $author      = iS::escapeStr($_POST['author']);
        $editor      = iS::escapeStr($_POST['editor']);
        $description = iS::escapeStr($_POST['description']);
        $keywords    = iS::escapeStr($_POST['keywords']);
        $tags        = str_replace('，', ',',iS::escapeStr($_POST['tags']));
        $clink       = iS::escapeStr($_POST['clink']);
        $url         = iS::escapeStr($_POST['url']);
        $tpl         = iS::escapeStr($_POST['tpl']);
        $metadata    = iS::escapeStr($_POST['metadata']);
        $metadata    = $metadata?addslashes(serialize($metadata)):'';
        $body        = (array)$_POST['body'];
        $creative    = (int)$_POST['creative'];
        $markdown    = (int)$_POST['markdown'];

        empty($title)&& iPHP::alert('标题不能为空！');
        empty($cid)  && iPHP::alert('请选择所属栏目');
        empty($body) && empty($url) && iPHP::alert('文章内容不能为空！');

        $pubdate   = iPHP::str2time($_POST['pubdate']);
        $postype   = $_POST['postype']?$_POST['postype']:0;
        isset($_POST['inbox']) && $status = "0";
        $userid OR $userid = iMember::$userid;
        $tags && $tags = preg_replace('/<[\/\!]*?[^<>]*?>/is','',$tags);

        if($this->callback['code']){
            $fwd = iCMS::filter($title);
            if($fwd){
                echo '标题中包含【'.$fwd.'】被系统屏蔽的字符，请重新填写。';
                return false;
            }
        }

        if($this->config['filter']) {
            $fwd = iCMS::filter($title);
            $fwd && iPHP::alert('标题中包含被系统屏蔽的字符，请重新填写。');
            $fwd = iCMS::filter($description);
            $fwd && iPHP::alert('简介中包含被系统屏蔽的字符，请重新填写。');
            // $fwd = iCMS::filter($body);
            // $fwd && iPHP::alert('内容中包含被系统屏蔽的字符，请重新填写。');
        }

        if(empty($aid) && $this->config['repeatitle']) {
            article::check_title($title) && iPHP::alert('该标题的文章已经存在!请检查是否重复');
        }
        $category = $this->category($cid);
        if(strstr($category->rule->article,'{LINK}')!==false){
            empty($clink) && $clink = strtolower(pinyin($title));
            if(empty($aid) && $clink) {
                article::check_clink($clink) && iPHP::alert('该文章自定义链接已经存在!请检查是否重复');
            }
        }


        if(empty($description) && empty($url)) {
            $description = $this->autodesc($body);
        }

        (iFS::checkHttp($pic)  && !isset($_POST['pic_http']))  && $pic  = iFS::http($pic);
        (iFS::checkHttp($mpic) && !isset($_POST['mpic_http'])) && $mpic = iFS::http($mpic);
        (iFS::checkHttp($spic) && !isset($_POST['spic_http'])) && $spic = iFS::http($spic);


        $haspic   = empty($pic)?0:1;

        $SELFURL = __SELF__.$_POST['REFERER'];
        if(empty($_POST['REFERER'])||strstr($_POST['REFERER'], '=save')){
        	$SELFURL= __SELF__.'?app=article&do=manage';
        }

        $editor OR	$editor	= empty(iMember::$data->nickname)?iMember::$data->username:iMember::$data->nickname;

        iCMS::core('Map');
        $picdata = '';
        $ucid    = 0;

        $fields  = article::fields($aid);

        if(empty($aid)) {
            $postime = $pubdate;
            $chapter = 0;
            $mobile  = 0;

            $aid  = article::insert(compact($fields));

            admincp::callback($aid,$this,'primary');

            if($tags){

                iPHP::app('tag.class','static');
                if(isset($_POST['tag_status'])){
                    tag::$addStatus = $_POST['tag_status'];
                }
                tag::add($tags,$userid,$aid,$cid);
                //article::update(compact('tags'),array('id'=>$aid));
            }

            iMap::init('prop',$this->appid);
            $pid && iMap::add($pid,$aid);

            iMap::init('category',$this->appid);
            iMap::add($cid,$aid);
            $scid && iMap::add($scid,$aid);

            $tagArray && tag::map_iid($tagArray,$aid);

            $url OR $this->article_data($body,$aid,$haspic);
            $this->categoryApp->update_count_one($cid);

            $article_url = iURL::get('article',array(array(
                'id'      =>$aid,
                'url'     =>$url,
                'cid'     =>$cid,
                'pubdate' =>$pubdate
            ),(array)$category))->href;

            if($status && iCMS::$config['api']['baidu']['sitemap']['sync']){
                baidu_ping($article_url);
            }

            if($this->callback['code']){
                return array(
                    "code"    => $this->callback['code'],
                    'indexid' => $aid
                );
            }
            if($_GET['callback']=='json'){
                echo json_encode(array(
                    "code"    => '1001',
                    'indexid' => $aid
                ));
                exit;
            }
            $moreBtn = array(
                    array("text" =>"查看该文章","target"=>'_blank',"url"=>$article_url,"close"=>false),
                    array("text" =>"编辑该文章","url"=>APP_URI."&do=add&id=".$aid),
                    array("text" =>"继续添加文章","url"=>APP_URI."&do=add&cid=".$cid),
                    array("text" =>"返回文章列表","url"=>$SELFURL),
                    array("text" =>"查看网站首页","url"=>iCMS_URL,"target"=>'_blank')
            );
            iPHP::$dialog['lock']	= true;
            iPHP::dialog('success:#:check:#:文章添加完成!<br />10秒后返回文章列表','url:'.$SELFURL,10,$moreBtn);
        }else{
            isset($_POST['ischapter']) OR $chapter = 0;
			if($tags){
				iPHP::app('tag.class','static');
	            tag::diff($tags,$_tags,iMember::$userid,$aid,$cid);
            }
            $picdata = $this->picdata($pic,$mpic,$spic);

            article::update(compact($fields),array('id'=>$aid));

            admincp::callback($aid,$this,'primary');

            iMap::init('prop',$this->appid);
            iMap::diff($pid,$_pid,$aid);

            iMap::init('category',$this->appid);
            iMap::diff($cid,$_cid,$aid);
            iMap::diff($scid,$_scid,$aid);

            $url OR $this->article_data($body,$aid,$haspic);

            if($_cid!=$cid) {
                $this->categoryApp->update_count_one($_cid,'-');
                $this->categoryApp->update_count_one($cid);
            }
            if($this->callback['code']){
                return array(
                    "code"    => $this->callback['code'],
                    'indexid' => $aid
                );
            }

            iPHP::success('文章编辑完成!<br />3秒后返回文章列表','url:'.$SELFURL);
        }
    }
    function do_del(){
        $msg = $this->del($this->id);
        $msg.= $this->del_msg('文章删除完成!');
        $msg.= $this->del_msg('10秒后返回文章列表!');
        iPHP::$dialog['lock'] = true;
        iPHP::dialog($msg,'js:1');
    }
    function del_msg($str){
        return iPHP::msg('success:#:check:#:'.$str.'<hr />',true);
    }
    function del_pic($pic){
        //$thumbfilepath    = gethumb($pic,'','',false,true,true);
        iFS::del(iFS::fp($pic,'+iPATH'));
        $msg    = $this->del_msg($pic.'删除');
//      if($thumbfilepath)foreach($thumbfilepath as $wh=>$fp) {
//              iFS::del(iFS::fp($fp,'+iPATH'));
//              $msg.= $this->del_msg('缩略图 '.$wh.' 文件删除');
//      }
        $filename   = iFS::info($pic)->filename;
        article::del_filedata($filename,'filename');
        $msg.= $this->del_msg($pic.'数据删除');
        return $msg;
    }
    function del($id,$uid='0',$postype='1') {
        $id = (int)$id;
        $id OR iPHP::alert("请选择要删除的文章");
        $uid && $sql="and `userid`='$uid' and `postype`='$postype'";
        $art = article::row($id,'cid,pic,tags',$sql);
        admincp::CP($art['cid'],'cd','alert');

        $fids   = iFile::index_fileid($id,$this->appid);
        $pieces = iFile::delete_file($fids);
        iFile::delete_fdb($fids,$id,$this->appid);
        $msg.= $this->del_msg(implode('<br />', $pieces).' 文件删除');
        $msg.= $this->del_msg('相关文件数据删除');

        if($art['tags']){
            iPHP::app('tag.class','static');
            tag::$remove = false;
            $msg.= tag::del($art['tags'],'name',$aid);
        }

        iDB::query("DELETE FROM `#iCMS@__category_map` WHERE `iid` = '$id' AND `appid` = '".$this->appid."';");
        iDB::query("DELETE FROM `#iCMS@__prop_map` WHERE `iid` = '$id' AND `appid` = '".$this->appid."' ;");

        article::del_comment($id);
        $msg.= $this->del_msg('评论数据删除');
        article::del($id);
        article::del_data($id);
        $msg.= $this->del_msg('文章数据删除');
        $this->categoryApp->update_count_one($art['cid'],'-');
        $msg.= $this->del_msg('栏目数据更新');
        $msg.= $this->del_msg('删除完成');
        return $msg;
    }
    function chapter_count($aid){
        article::chapter_count($aid);
    }
    function article_data($bodyArray,$aid=0,$haspic=0){
        if(isset($_POST['ischapter']) || is_array($_POST['adid'])){
            $adidArray    = $_POST['adid'];
            $chaptertitle = $_POST['chaptertitle'];
            $chapter      = count($bodyArray);
            foreach ($bodyArray as $key => $body) {
                $adid     = (int)$adidArray[$key];
                $subtitle = iS::escapeStr($chaptertitle[$key]);
                $this->body($body,$subtitle,$aid,$adid,$haspic);
            }
            article::update(compact('chapter'),array('id'=>$aid));
        }else{
            $adid     = (int)$_POST['adid'];
            $subtitle = iS::escapeStr($_POST['subtitle']);
            $body     = implode('#--iCMS.PageBreak--#',$bodyArray);
            $this->body($body,$subtitle,$aid,$adid,$haspic);
        }
        admincp::callback($aid,$this,'data');
    }
    function body($body,$subtitle,$aid=0,$id=0,&$haspic=0){

        $body = preg_replace(array('/<script.+?<\/script>/is','/<form.+?<\/form>/is'),'',$body);
        isset($_POST['dellink']) && $body = preg_replace("/<a[^>].*?>(.*?)<\/a>/si", "\\1",$body);

        if($_POST['markdown']){
            $body = $body;
        }else{
            $this->config['autoformat'] && $body = addslashes(autoformat($body));
        }

        article::$ID = $aid;

        $fields = article::data_fields($id);
        $data   = compact ($fields);

        if($id){
            article::data_update($data,compact('id'));
        }else{
            $id = article::data_insert($data);
        }

        $_POST['isredirect'] && iFS::$redirect  = true;
        $_POST['iswatermark']&& iFS::$watermark = false;

        if(isset($_POST['remote'])){
            $body = $this->remotepic($body,true,$aid);
            $body = $this->remotepic($body,true,$aid);
            $body = $this->remotepic($body,true,$aid);
            if($body && $id){
                article::data_update(array('body'=>$body),compact('id'));
            }
        }

        if(isset($_POST['autopic']) && empty($haspic)){
            if($picurl = $this->remotepic($body,'autopic',$aid)){
                $this->set_pic($picurl,$aid);
                $haspic = true;
            }
        }
        $this->body_pic_indexid($body,$aid);
    }
    function autodesc($body){
        if($this->config['autodesc'] && $this->config['descLen']) {
            is_array($body) && $bodyText   = implode("\n",$body);
            $bodyText   = str_replace('#--iCMS.PageBreak--#',"\n",$bodyText);
            $bodyText   = str_replace('</p><p>', "</p>\n<p>", $bodyText);

            $textArray = explode("\n", $bodyText);
            $pageNum   = 0;
            $resource  = array();
            foreach ($textArray as $key => $p) {
                $text      = preg_replace(array('/<[\/\!]*?[^<>]*?>/is','/\s*/is'),'',$p);
                // $pageLen   = strlen($resource);
                // $output    = implode('',array_slice($textArray,$key));
                // $outputLen = strlen($output);
                $output    = implode('',$resource);
                $outputLen = strlen($output);
                if($outputLen>$this->config['descLen']){
                    // $pageNum++;
                    // $resource[$pageNum] = $p;
                    break;
                }else{
                    $resource[]= $text;
                }
            }
            $description = implode("\n", $resource);
            // $description = csubstr($body_text,$this->config['descLen']);
            // $description = addslashes(trim($description));
            // $description = str_replace('#--iCMS.PageBreak--#','',$description);
            unset($bodyText);
            return $description;
        }
    }
    function set_pic($picurl,$aid){
        $uri = parse_url(iCMS_FS_URL);
        if (stripos($picurl,$uri['host']) !== false){
            $picdata = (array)article::value('picdata',$aid);
            $picdata && $picdata = @unserialize($picdata);
            $pic = iFS::fp($picurl,'-http');
            list($width, $height, $type, $attr) = @getimagesize(iFS::fp($pic,'+iPATH'));
            $picdata['b'] = array('w'=>$width,'h'=>$height);
            $picdata = addslashes(serialize($picdata));
            $haspic  = 1;
            article::update(compact('haspic','pic','picdata'),array('id'=>$aid));
            iFile::set_map($this->appid,$aid,$pic,'path');
        }
    }
    function remotepic($content,$remote = false) {
        if (!$remote) return $content;

        iFS::$forceExt = "jpg";
        $content = stripslashes($content);
        preg_match_all('@<img[^>]+src=(["\']?)(.*?)\\1[^>]*?>@is', $content, $match);
        $array  = array_unique($match[2]);
        $uri    = parse_url(iCMS_FS_URL);
        $fArray = array();
        foreach ($array as $key => $value) {
            $value = trim($value);
            if (stripos($value,$uri['host']) === false){
                $filepath = iFS::http($value);
                $rootfilpath = iFS::fp($filepath, '+iPATH');
                list($owidth, $oheight, $otype) = @getimagesize($rootfilpath);

                if($filepath && !iFS::checkHttp($filepath) && $otype){
                    $value = iFS::fp($filepath,'+http');
                }else{
                    if($this->DELETE_ERROR_PIC){
                        iFS::del($rootfilpath);
                        $array[$key]  = $match[0][$key];
                        $value = '';
                    }
                }
                $fArray[$key] = $value;
            }else{
                unset($array[$key]);
                $rootfilpath = iFS::fp($value, 'http2iPATH');
                list($owidth, $oheight, $otype) = @getimagesize($rootfilpath);
                if($this->DELETE_ERROR_PIC && empty($otype)){
                    iFS::del($rootfilpath);
                    $array[$key]  = $match[0][$key];
                    $fArray[$key] = '';
                }
            }
            if($remote==="autopic" && $key==0){
                return $value;
            }
        }
        if($remote==="autopic" && empty($array)){
            return;
        }
        if($array && $fArray){
            krsort($array);
            krsort($fArray);
            $content = str_replace($array, $fArray, $content);
        }
        return addslashes($content);
    }
    function body_pic_indexid($content,$indexid) {
        if(empty($content)){
            return;
        }
        $content = stripslashes($content);
        preg_match_all("/<img.*?src\s*=[\"|'](.*?)[\"|']/is", $content, $match);
        $array  = array_unique($match[1]);
        foreach ($array as $key => $value) {
            iFile::set_map($this->appid,$indexid,$value,'path');
        }
    }

    function picdata($pic='',$mpic='',$spic=''){
        $picdata = array();
        if($pic){
            list($width, $height, $type, $attr) = @getimagesize(iFS::fp($pic,'+iPATH'));
            $picdata['b'] = array('w'=>$width,'h'=>$height);
        }
        if($mpic){
            list($width, $height, $type, $attr) = @getimagesize(iFS::fp($mpic,'+iPATH'));
            $picdata['m'] = array('w'=>$width,'h'=>$height);
        }
        if($spic){
            list($width, $height, $type, $attr) = @getimagesize(iFS::fp($spic,'+iPATH'));
            $picdata['s'] = array('w'=>$width,'h'=>$height);
        }
        return $picdata?addslashes(serialize($picdata)):'';
    }
    function check_pic($body,$aid=0){
        // global $status;
        // if($status!='1'){
        //     return;
        // }
        $body = stripcslashes($body);
        preg_match_all('@<img[^>]+src=(["\']?)(.*?)\\1[^>]*?>@is',$body,$pic_array);
        $p_array = array_unique($pic_array[2]);

        foreach((array)$p_array as $key =>$url) {
            $url = trim($url);
            $filpath = iFS::fp($url, 'http2iPATH');
            // var_dump($filpath);
            list($owidth, $oheight, $otype) = @getimagesize($filpath);
            if(empty($otype)){
                // var_dump($filpath,$otype);
                if($aid){
                    iDB::update('article',array('status'=>'0'),array('id'=>$aid));
                    echo $aid." status:2\n";
                }
                return true;
            }
        }
        return false;
    }
}
