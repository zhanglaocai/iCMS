<?php
/**
 * @package iCMS
 * @copyright 2007-2017, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
class propApp {
	public $methods = array('iCMS');
	public static function value($app,$key=null) {
        $pieces[] = $app;
        $key && $pieces[] = $key;
        $keys = implode('/', $pieces);

		$propArray 	= iCache::get("prop/{$keys}");
		$propArray && sort($propArray);
        return $propArray;
	}

}
