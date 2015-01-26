<?php
/**
* iCMS - i Content Management System
* Copyright (c) 2007-2012 idreamsoft.com iiimon Inc. All rights reserved.
*
* @author coolmoo <idreamsoft@qq.com>
* @site http://www.idreamsoft.com
* @licence http://www.idreamsoft.com/license.php
* @version 6.0.0
* @$Id: iPatch.class.php 1686 2013-06-22 09:35:59Z coolmoo $
*/
/**
 * 自动更新类
 *
 * @author coolmoo
 */
defined('iPHP') OR exit('What are you doing?');

define('PATCH_URL',"http://patch.idreamsoft.com");//自动更新服务器
define('PATCH_DIR',iPATH.'cache/iCMS/patch/');//临时文件夹
class iPatch {
	public static $version	= '';
	public static $release	= '';
	public static $zipName	= '';
    public static function init($force=false){
		$info = self::getVersion($force);
    	if($info->app==iPHP_APP &&
    		version_compare($info->version,iCMS_VER,'>=') &&
    		$info->release > iCMS_RELEASE)
    	{
			self::$version = $info->version;
			self::$release = $info->release;
			self::$zipName = 'iCMS.'.self::$version.'.patch.'.self::$release.'.zip';
    		return array(self::$version,self::$release,$info->update,$info->changelog);
    	}
    }
    public static function getVersion($force=false) {
    	iFS::mkdir(PATCH_DIR);
	    $tFilePath = PATCH_DIR.'version.json';//临时文件夹
	    if(iFS::ex($tFilePath) && time()-iFS::mtime($tFilePath) < 3600 && !$force){
	    	$FileData = iFS::read($tFilePath);
	    }else{
			$url      = PATCH_URL.'/version.'.iPHP_APP.'.'.iCMS_VER.'.patch.'.iCMS_RELEASE.'?t='.time();
			$FileData = iFS::remote($url);
	    	iFS::write($tFilePath,$FileData);
	    }
    	return json_decode($FileData);//版本列表
    }
    public static function download(){
		$zipFile = PATCH_DIR.self::$zipName;//临时文件
		$zipHttp = PATCH_URL.'/'.self::$zipName;
		$msg     = '正在下载 ['.self::$release.'] 更新包 '.$zipHttp.'<iCMS>下载完成....<iCMS>';
	    if(iFS::ex($zipFile)){
	    	return $msg;
	    }
    	$FileData	= iFS::remote($zipHttp);
    	if($FileData){
	    	iFS::write($zipFile,$FileData);//下载更新包
			return $msg;
	    }
    }
    public static function update(){
		@set_time_limit(0);
		// Unzip uses a lot of memory
		@ini_set('memory_limit', '256M');
		require iPHP_CORE.'/pclzip.class.php';//加载zip操作类
	    $zipFile	= PATCH_DIR.'/'.self::$zipName;//临时文件
		$msg		= '正在对 ['.self::$zipName.'] 更新包进行解压缩<iCMS>';
		$zip		= new PclZip($zipFile);
		if ( false == ($archive_files = $zip->extract(PCLZIP_OPT_EXTRACT_AS_STRING))) exit("ZIP包错误");

		if ( 0 == count($archive_files) ) exit("空的ZIP文件");

		$msg.= '解压完成开始更新程序#<iCMS>';
		$bakDir	= iPATH.self::$release.'bak';
		iFS::mkdir($bakDir);
		foreach ($archive_files as $file) {
			$folder	= $file['folder'] ? $file['filename'] : dirname($file['filename']);
			$dp		= iPATH.$folder;
			if(!iFS::ex($dp)){
				$msg.= '创建 ['.$dp.'] 文件夹<iCMS>';
				//self::mkdir($path.'/'.$folder);
			}
			if (empty($file['folder'])){
				$fp	= iPATH.$file['filename'];
				$bfp= $bakDir.'/'.$file['filename'];
				iFS::mkdir(dirname($bfp));
				if(iFS::ex($fp)){
					$msg.= '备份 ['.$fp.'] 文件 到 ['.$bfp.']<iCMS>';
					rename($fp,$bfp);//备份旧文件
				}
				$msg.= '更新 ['.$fp.'] 文件<iCMS>';
				iFS::write($fp, $file['content']);
				$msg.= '['.$fp.'] 更新完成!#<iCMS>';
			}
		}
     	$msg.= '清除临时文件!<iCMS>注:原文件备份在 ['.$bakDir.'] 目录<iCMS>如没有特殊用处请删除此目录!#<iCMS>';
    	iFS::rmdir(PATCH_DIR,true,'version.txt');
		return $msg;
   }
   public static function run(){
   	   $updateFile	= iPATH.'update.'.self::$release.'.php';
   	   if(iFS::ex($updateFile)){
   	   	   require $updateFile;
   	   	   $msg= '执行升级程序<iCMS>';
   	   	   $msg.= updatePatch();
   	   	   $msg.= '升级顺利完成!<iCMS>删除升级程序!';
   	   	   iFS::del($updateFile);
   	   }else{
   	   	   $msg= '升级顺利完成!';
   	   }
   	   return $msg;
   }
}
