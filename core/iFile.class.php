<?php
/**
 * @package iCMS
 * @copyright 2007-2017, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 *
 * CREATE TABLE `iPHP_file_data` (
 *   `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 *   `userid` int(10) unsigned NOT NULL DEFAULT '0',
 *   `filename` varchar(255) NOT NULL DEFAULT '',
 *   `ofilename` varchar(255) NOT NULL DEFAULT '',
 *   `path` varchar(255) NOT NULL DEFAULT '',
 *   `intro` varchar(255) NOT NULL DEFAULT '',
 *   `ext` varchar(10) NOT NULL DEFAULT '',
 *   `size` int(10) unsigned NOT NULL DEFAULT '0',
 *   `time` int(10) unsigned NOT NULL DEFAULT '0',
 *   `type` tinyint(1) NOT NULL DEFAULT '0',
 *   PRIMARY KEY (`id`),
 *   KEY `ext` (`ext`),
 *   KEY `path` (`path`),
 *   KEY `ofilename` (`ofilename`),
 *   KEY `fn_userid` (`filename`,`userid`)
 * ) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
 *
 * CREATE TABLE `iPHP_file_map` (
 * `fileid` int(10) unsigned NOT NULL,
 * `appid` int(10) NOT NULL,
 * `indexid` int(10) NOT NULL,
 * `addtimes` int(10) NOT NULL,
 * PRIMARY KEY (`fileid`),
 * UNIQUE KEY `unique` (`fileid`,`appid`,`indexid`)
 * ) ENGINE=MyISAM DEFAULT CHARSET=utf8
 *
 */

class iFile {
    public static $TABLE_DATA = null;
    public static $TABLE_MAP = null;
    public static $userid = 0;

    private static $_data_table = null;
    private static $_map_table = null;

    public static function init($config,$table = array()) {
        $config['table'] && $table = $config['table'];
        list(self::$TABLE_DATA,self::$TABLE_MAP) = $table;
        self::$_data_table = iPHP_DB_PREFIX . self::$TABLE_DATA;
        self::$_map_table  = iPHP_DB_PREFIX . self::$TABLE_MAP;
    }
    public static function index_fileid($indexid,$appid='1'){
        $rs      = iDB::all("SELECT `fileid` FROM " . self::$_map_table . " WHERE indexid = '{$indexid}'  AND appid = '{$appid}' ");
        $fileid0 = iPHP::values($rs,'fileid','array',null);
        $result  = array();
        if($fileid0){
            $rs = iDB::all("SELECT `fileid` FROM " . self::$_map_table . " WHERE `fileid` IN(".implode(',', $fileid0).") and indexid <> '{$indexid}'");
            $fileid1 = iPHP::values($rs,'fileid','array',null);
            if($fileid1){
                $result  = array_diff((array)$fileid0 , (array)$fileid1);
            }else{
                $result  = $fileid0;
            }
        }
        return $result;
    }
    public static function delete_file($ids){
        if(empty($ids)) return array();

        $ids  = iPHP::multi_ids($ids,true);
        $sql  = iPHP::where($ids,'id',false,true);
        $rs   = iDB::all("SELECT * FROM ".self::$_data_table." where {$sql}");
        $ret  = array();
        foreach ((array)$rs as $key => $value) {
            $path = self::path($value);
            $filepath = iFS::fp($path,'+iPATH');
            iFS::del(iFS::fp($filepath,'+iPATH'));
            $ret[] = $path;
        }
        return $ret;
    }
    public static function delete_fdb($ids,$indexid,$appid='1'){
        if(empty($ids)) return array();

        $ids  = iPHP::multi_ids($ids,true);
        $sql  = iPHP::where($ids,'id',false,true);
        $sql && iDB::query("DELETE FROM ".self::$_data_table." where {$sql}");
        $msql = iPHP::where($ids,'fileid');
        $msql && iDB::query("DELETE FROM ".self::$_map_table." where indexid = '{$indexid}'  AND appid = '{$appid}' {$msql}");
    }
    public static function del_app_data($appid=null){
        if($appid){
            iDB::query("
                DELETE FROM ".self::$_data_table." where `id` IN(
                    SELECT `fileid` FROM ".self::$_map_table." WHERE `appid` = '{$appid}'
                )
            ");
            iDB::query("DELETE FROM ".self::$_map_table." where `appid` = '{$appid}'");
        }
    }

    public static function path($F,$root=false){
        $path = $F['path'].$F['filename'].'.'.$F['ext'];
        $root&& $path = iFS::fp($path,'+iPATH');
        return $path;
    }
    public static function insert($data, $type = 0,$status=1) {
        $userid = self::$userid === false ? 0 : self::$userid;
        $data['userid'] = $userid;
        $data['time']   = time();
        $data['type']   = $type;
        $data['status'] = $status;
        iDB::insert(self::$TABLE_DATA, $data);
        return iDB::$insert_id;
    }
    public static function update($data, $fid = 0) {
        if (empty($fid)) {
            return;
        }

        $userid = self::$userid === false ? 0 : self::$userid;
        $data['userid'] = $userid;
        $data['time'] = time();
        iDB::update(self::$TABLE_DATA, $data, array('id' => $fid));
    }
    public static function get($f, $v,$s='*') {

        $sql = self::$userid === false ? '' : " AND `userid`='" . self::$userid . "'";
        $rs = iDB::row("SELECT {$s} FROM " . self::$_data_table. " WHERE `$f`='$v' {$sql} LIMIT 1");

        if ($rs&&$s=='*') {
            $rs->filepath = $rs->path . $rs->filename . '.' . $rs->ext;
            if ($f == 'ofilename') {
                $filepath = self::fp($rs->filepath, '+iPATH');
                if (is_file($filepath)) {
                    return $rs;
                } else {
                    return false;
                }
            }
        }
        return $rs;
    }
    public static function set_map($appid,$indexid,$value,$field='id'){
        switch ($field) {
            case 'path':
                $filename = iFS::filename($value);
            case 'filename':
                $info     = self::get('filename',$filename,'id');
                $fileid   = $info->id;
            break;
            case 'id':
                $fileid   = $value;
            break;
        }

        if($fileid){
            $userid   = self::$userid;
            $addtimes = time();
            $data     = compact('fileid','userid','appid','indexid','addtimes');
            self::idb_map($data);
        }
    }

    public static function idb_map($data,$where=null) {
        if($where){
            return iDB::update(self::$TABLE_MAP, $data,$where);
        }
        return iDB::insert(self::$TABLE_MAP, $data,true);
    }
}
