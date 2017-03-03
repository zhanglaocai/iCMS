<?php
/**
 * @package iCMS
 * @copyright 2007-2017, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
class filesApp {
    public $methods = array('iCMS','download');

    public function do_iCMS(){}
    public function API_iCMS(){}

    public function do_download(){
        $file = iSecurity::escapeStr($_GET['file']);
        var_dump($file);
    }
    public function API_download(){
        $this->do_download();
    }
    public static function get_url($value,$type='download'){
        $url = iCMS_API.'?app=files&do='.$type.'&file='.$value.'&t='.$_SERVER['REQUEST_TIME'];
        return $url;
    }
    public static function get_pic($src,$size=0,$thumb=0){
        if(empty($src)) return array();

        if(stripos($src, '://')!== false){
            return array(
                'src' => $src,
                'url' => $src,
                'width' => 0,
                'height' => 0,
            );
        }

        $data = array(
            'src' => $src,
            'url' => iFS::fp($src,'+http'),
        );
        if($size){
            $data['width']  = $size['w'];
            $data['height'] = $size['h'];
        }
        if($size && $thumb){
            $data+= bitscale(array(
                "tw" => (int)$thumb['width'],
                "th" => (int)$thumb['height'],
                "w" => (int)$size['w'],
                "h" => (int)$size['h'],
            ));
        }
        return $data;
    }
    public static function get_twh($width=null,$height=null){
        $ret    = array();
        $width  ===null OR $ret['width'] = $width;
        $height ===null OR $ret['height'] = $height;
        return $ret;
    }

}
