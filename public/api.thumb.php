<?php
//error_reporting(E_ALL ^ E_NOTICE); //调试
error_reporting(0);

//图片目录绝对路径 最后带 /
define('iPHP_RES_PAHT','/data/www/ooxx.com/res/');

strpos($_GET['fp'],'..') === false OR exit('What are you doing?');

$fp  = $_GET['fp'];
$tw  = (int)$_GET['w'];
$th  = (int)$_GET['h'];
$src = iPHP_RES_PAHT.$fp;


if (!class_exists('Gmagick')) {
	class Gmagick {
		public $width  = 0;
		public $height = 0;
		public $type   = 0;
		public $image  = null;
		public $im     = null;
		function __construct(){}
		function readImage($src){
			if(empty($src)){
				return;
			}
			list($this->width,$this->height,$this->type) = @getimagesize($src);
			$this->image = $this->imagecreate($this->type,$src);
		}
		function getImageWidth(){
			return $this->width;
		}
		function getImageHeight(){
			return $this->height;
		}
		function resizeImage($width,$height,$filter=null,$blur=0){
			$this->im = imagecreatetruecolor($width,$height);
			imagecopyresampled($this->im,$this->image, 0, 0, 0, 0,$width,$height,$this->width,$this->height);
		}
		function cropImage($width,$height,$x,$y){
			if($this->im){
				$this->image  = $this->im;
				$this->width  = imagesx($this->image);
				$this->height = imagesy($this->image);
			}
			$this->im = imagecreatetruecolor($width,$height);
			imagecopyresampled($this->im,$this->image, 0, 0, $x, $y,$this->width,$this->height,$this->width,$this->height);
		}

	    function current() {
	    	switch($this->type){
	    		case 1:
		    		header('Content-Type: image/gif');
		    		imagegif($this->im,null);
		    		break;
	    		case 2:
		    		header('Content-Type: image/jpeg');
		    		imagejpeg($this->im,null,100);
		    		break;
	    		case 3:
		    		header('Content-Type: image/png');
		    		imagepng($this->im,null);
		    		break;
	    	}
	        imagedestroy($this->im);
	        exit();
	    }
	    function imagecreate($type,$src) {
	    	switch($type){
	    		case 1:$res = imagecreatefromgif($src);break;
	    		case 2:$res = imagecreatefromjpeg($src);break;
	    		case 3:$res = imagecreatefrompng($src);break;
	    	}
	        return $res;
	    }
	}
}

if(fsexists($src)){
	$image = new Gmagick();
	$image->readImage($src);
	$scale = array(
			"tw" => $tw,
			"th" => $th,
			"w"  => $image->getImageWidth(),
			"h"  => $image->getImageHeight()
	);
	if($tw>0 && $th>0){
		$im = scale($scale);
		$image->resizeImage($im['w'],$im['h'], null, 1);
	  	$x = $y = 0;
		$im['w']>$im['tw'] && $x = ceil(($im['w']-$im['tw'])/3);
		$im['h']>$im['th'] && $y = ceil(($im['h']-$im['th'])/3);
		$image->cropImage($tw,$th,$x,$y);
	}else{
		empty($scale['th']) && $scale['th']=9999999;
		$im = bitscale($scale);
		$image->resizeImage($im['w'],$im['h'], null, 1);
	}

	$expires = 31536000;
	header("Cache-Control: maxage=".$expires);
	header('Last-Modified: '.gmdate('D, d M Y H:i:s',$_SERVER['REQUEST_TIME']).' GMT');
	header('Expires: '.gmdate('D, d M Y H:i:s',$_SERVER['REQUEST_TIME']+$expires).' GMT');

	$srcData = $image->current();
}else{
	//1x1.gif
	$srcData = 'R0lGODlhAQABAIAAAAAAAP///yH5BAEHAAEALAAAAAABAAEAAAICTAEAOw==';
	//nopic.gif
	$srcData = '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
	FxLJ9EJ0oH0YpaVAMJAAA7';
	// header('HTTP/1.1 404 Not Found');
	header('Content-type: image/gif');
	echo base64_decode($srcData);
	exit;
}

header('Content-type: image/jpeg');
echo $srcData;
fastcgi_finish_request();

if (!function_exists('fastcgi_finish_request')) {
	function fastcgi_finish_request(){
		return false;
	}
}

function fsexists($file) {
	return @stat($file)===false?false:true;
}

function scale($a,$reSize=true) {
	if($reSize){
		if($a['w'] > $a['h'] ||$a['w'] == $a['h']){
			$s = ($a['h'] > $a['th'])? $a['th']/$a['h'] : $a['h']/$a['th'];
			$a['w'] = ceil($s * $a['w']);
			$a['h'] = ($a['h'] > $a['th'])? $a['th'] : $a['h'];
		}else if($a['h'] > $a['w']){
			$s = ($a['w'] > $a['tw']) ? $a['tw']/$a['w'] : $a['w']/$a['tw'];
			$a['h'] = ceil($s * $a['h']);
			$a['w'] = ($a['w'] > $a['tw']) ? $a['tw'] : $a['w'];
		}
	}
    return $a;
}
function bitscale($a,$reSize=true) {
	if($reSize){
		if( $a['w']/$a['h'] > $a['tw']/$a['th']  && $a['w'] >$a['tw'] ){
			$a['h'] = ceil($a['h'] * ($a['tw']/$a['w']));
			$a['w'] = $a['tw'];
		}else if( $a['w']/$a['h'] <= $a['tw']/$a['th'] && $a['h'] >$a['th']){
			$a['w'] = ceil($a['w'] * ($a['th']/$a['h']));
			$a['h'] = $a['th'];
		}
	}
    return $a;
}
