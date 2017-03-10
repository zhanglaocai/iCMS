<?php
/**
 * @package iCMS
 * @copyright 2007-2017, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
class propApp {
	public $methods = array('iCMS');
	public static function value($app,$field=null) {
        $pieces[] = $app;
        $field && $pieces[] = $field;
        $keys = implode('/', $pieces);
		$propArray 	= iCache::get("prop/{$keys}");
		$propArray && sort($propArray);
        return $propArray;
	}
    public static function url($url,$value=null) {
        $url = $url?$url:$_SERVER['REQUEST_URI'];
        $query = array();
        $query[$value['field']] = $value['val'];
        return buildurl($url,$query);
    }
}
