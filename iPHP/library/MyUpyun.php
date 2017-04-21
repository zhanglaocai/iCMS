<?php 
require __DIR__ . '/upyun3/vendor/autoload.php';
use Upyun\Config;
use Upyun\Signature;
use Upyun\Util;
use Upyun\Upyun;

class MyUpYun extends Upyun
{
	public $conf;
	
	public function __construct($conf)
	{
		$this->conf = $conf;
		
		$bucketConfig = new Config($conf['Bucket'], $conf['AccessKey'], $conf['SecretKey']);
		parent::__construct($bucketConfig);
	}
	
	public function uploadFile($filePath,$bucket,$key=null)
	{
		
		$result = $this->write($key, file_get_contents($filePath));
		
		if(!empty($result['x-upyun-file-type']))
		{
			$url = '';
			$url  =  $this->getFileUrl($filePath);
		
			return json_encode(array(
					'url'=>$url,
					'code'=>1,
					'error'=>'1',
			));
		}
		return $result;
	}
	
	private function getFileUrl($path)
	{
		if (empty($this->conf['domain']))
		{
			return "http://" . $this->conf['Bucket'] . ".b0.upaiyun.com/" . ltrim($path, '/');
		}
		else
		{
			return "http://" . $this->conf['domain'] .  $path;
		}
	}
}
