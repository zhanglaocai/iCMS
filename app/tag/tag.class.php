<?php
/**
 * @package iCMS
 * @copyright 2007-2017, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */

class tag {
    public static $appid      = '1';
    public static $remove     = true;
    public static $add_status = '1';

	public static function data($fv=0,$field='name',$limit=0){
		$sql      = $fv ? "where `$field`='$fv'":'';
		$limitSQL = $limit ? "LIMIT $limit ":'';
	    return iDB::all("SELECT * FROM `#iCMS@__tags` {$sql} order by id DESC {$limitSQL}");
	}
	public static function cache($value=0,$field='id'){
        return;
		$rs     = self::data($value,$field);
		$_count = count($rs);
	    for($i=0;$i<$_count;$i++) {
			$C              = iCache::get('iCMS/category/'.$rs[$i]['cid']);
			$TC             = iCache::get('iCMS/category/'.$rs[$i]['tcid']);
			$rs[$i]['iurl'] = iURL::get('tag',array($rs[$i],$C,$TC));
			$rs[$i]['url']  = $rs[$i]['iurl']->href;
			$tkey           = self::tkey($rs[$i]['cid']);
	        iCache::set($tkey,$rs[$i],0);
	    }
	}
    public static function tkey($cid){
		$ncid = abs(intval($cid));
		$ncid = sprintf("%08d", $ncid);
		$dir1 = substr($ncid, 0, 2);
		$dir2 = substr($ncid, 2, 3);
		$tkey = $dir1.'/'.$dir2.'/'.$cid;
        return 'iCMS/tags/'.$tkey;
    }
    public static function getag($key='tags',&$array,$C,$TC){
    	if(empty($array[$key])) return;

		$strLink	= '';
        $strArray	= explode(',',$array[$key]);

        foreach($strArray AS $k=>$name){
        	$name				= trim($name);
        	$_cache				= self::get_cache($name,$C['cid'],$TC['cid']);
			$strA[$k]['name']	= $name;
			$strA[$k]['url']	= $_cache['url']?$_cache['url']:iCMS_PUBLIC_URL.'/search.php?q='.$name;
			$strLink.='<a href="'.$strA[$k]['url'].'" target="_self">'.$strA[$k]['name'].'</a> ';
        }
        $search	= $C['name'];

        $sstrA	= $strArray;
        count($strArray)>3 && $sstrA = array_slice($strArray,0,3);
        $sstr	= implode(',',$sstrA);
        $sstr && $search = $sstr;

        $array[$key.'link']		= $strLink;
        $array[$key.'_array']	= $strA;
        $array['search'][$key]	= $search;

        return array(
        	$key.'link'		=> $strLink,
        	$key.'_array'	=> $strA,
        	'search'		=> array($key=>$search)
        );
    }

	public static function get_cache($tid){
		$tkey	= self::tkey($tid);
		return iCache::get($tkey);
	}

    public static function del_cache($tid) {
		$ids = implode(',',(array)$tid);
		iDB::query("DELETE FROM `#iCMS@__tags` WHERE `id` in ($ids) ");
		$c   = count($tid);
        for($i=0;$i<$c;$i++) {
			$tkey = self::tkey($tid[$i]);
			iCache::delete($tkey);
        }
    }
    public static function map_iid($var=array(),$iid=0){
    	foreach ((array)$var as $key => $t) {
    		iDB::query("UPDATE `#iCMS@__tags_map` SET `iid` = '$iid' WHERE `id` = '".$t[4]."'");
    	}
    }

	public static function add($tags,$uid="0",$iid="0",$cid='0',$tcid='0') {
		$a        = explode(',',$tags);
		$c        = count($a);
		$tag_array = array();
	    for($i=0;$i<$c;$i++) {
	        $tag_array[$i] = self::update($a[$i],$uid,$iid,$cid,$tcid);
	    }
	    return implode(',', (array)$tag_array);
	}
	public static function update($name,$uid="0",$iid="0",$cid='0',$tcid='0') {
	    if(empty($name)) return;
        $name = trim($name,"\0\n\r\t\x0B");
	    $name = htmlspecialchars_decode($name);
	    $name = preg_replace('/<[\/\!]*?[^<>]*?>/is','',$name);
	    $tid = iDB::value("SELECT `id` FROM `#iCMS@__tags` WHERE `name`='$name'");
	    if($tid) {
	        $tlid = iDB::value("
                SELECT `id` FROM `#iCMS@__tags_map`
                WHERE `iid`='$iid' and `node`='$tid' and `appid`='".self::$appid."'");
	        if(empty($tlid)) {
                $tlid = iDB::insert('tags_map',array(
                    'node'  => $tid,
                    'iid'   => $iid,
                    'appid' => self::$appid,
                ));
	            iDB::query("
                    UPDATE `#iCMS@__tags`
                    SET  `count`=count+1,`pubdate`='".time()."'
                    WHERE `id`='$tid'");
	        }
	    }else {
			$tkey   = iPinyin::get($name,iCMS::$config['other']['py_split']);
			$fields = array('uid', 'cid', 'tcid', 'pid', 'tkey', 'name', 'seotitle', 'subtitle', 'keywords', 'description', 'haspic', 'pic', 'url', 'related', 'count', 'weight', 'tpl', 'sortnum', 'pubdate', 'postime', 'status');
			$data   = compact ($fields);
            $data['pid']     = '0';
            $data['count']   = '1';
            $data['weight']  = '0';
            $data['sortnum'] = '0';
            $data['pubdate'] = time();
            $data['postime'] = $data['pubdate'];
            $data['status']  = self::$add_status;

			$tid = iDB::insert('tags',$data);
            $tmid = iDB::insert('tags_map',array(
                'node'  => $tid,
                'iid'   => $iid,
                'appid' => self::$appid,
            ));
	    }
	    return $name;
	}
	public static function diff($Ntags,$Otags,$uid="0",$iid="0",$cid='0',$tcid='0') {
		$N        = explode(',', $Ntags);
		$O        = explode(',', $Otags);
		$diff     = array_diff_values($N,$O);
		$tag_array = array();
	    foreach((array)$N AS $i=>$tag) {//新增
            $tag_array[$i] = self::update($tag,$uid,$iid,$cid,$tcid);
		}
	    foreach((array)$diff['-'] AS $tag) {//减少
	        $ot	= iDB::row("
                SELECT `id`,`count`
                FROM `#iCMS@__tags`
                WHERE `name`='$tag' LIMIT 1;");
	        if($ot->count<=1) {
	        	//$iid && $sql="AND `iid`='$iid'";
	            iDB::query("DELETE FROM `#iCMS@__tags`  WHERE `name`='$tag'");
	            iDB::query("DELETE FROM `#iCMS@__tags_map` WHERE `node`='$ot->id'");
	        }else {
	            iDB::query("
                    UPDATE `#iCMS@__tags`
                    SET  `count`=count-1,`pubdate`='".time()."'
                    WHERE `name`='$tag' and `count`>0");
	            iDB::query("
                    DELETE FROM `#iCMS@__tags_map`
                    WHERE `iid`='$iid'
                    AND `node`='$ot->id'
                    AND `appid`='".self::$appid."'");
	        }
	   }
	   return implode(',', (array)$tag_array);
	}
	public static function del($tags,$field='name',$iid=0){
	    $tag_array	= explode(",",$tags);
	    $iid && $sql="AND `iid`='$iid'";
	    foreach($tag_array AS $k=>$v) {
	    	$tag	= iDB::row("SELECT * FROM `#iCMS@__tags` WHERE `$field`='$v' LIMIT 1;");
	    	$tRS	= iDB::all("SELECT `iid` FROM `#iCMS@__tags_map` WHERE `node`='$tag->id' AND `appid`='".self::$appid."' {$sql}");
	    	foreach((array)$tRS AS $TL) {
	    		$idA[]=$TL['iid'];
	    	}
	    	if($idA){
	    		$ids = iSQL::values($idA,null);
                if($ids){
                    $table = apps::table(self::$appid);
                    iDB::query("
                        UPDATE `#iCMS@__$table`
                        SET `tags`= REPLACE(tags, '$tag->name,',''),
                        `tags`= REPLACE(tags, ',$tag->name','')
                        WHERE id IN($ids)
                    ");
                }
	    	}
            self::$remove && iDB::query("DELETE FROM `#iCMS@__tags`  WHERE `$field`='$v'");
            iDB::query("
                DELETE FROM
                `#iCMS@__tags_map`
                WHERE `node`='$tag->id'
                AND `appid`='".self::$appid."' {$sql}");
            $ckey = self::tkey($tag->cid);
            // iCache::delete($ckey);
	    }
	}
}
