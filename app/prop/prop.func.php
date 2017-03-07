<?php
/**
 * @package iCMS
 * @copyright 2007-2017, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
class propFunc{
	public static function prop_array($vars){
		$app	= $vars['sapp'];
		$field	= $vars['field'];
        $app && $pieces[] = $app;
        $field && $pieces[] = $field;
        $keys = implode('/', $pieces);

		$propArray 	= iCache::get("prop/{$keys}");
		$propArray && sort($propArray);
		$offset		= $vars['start']?$vars['start']:0;
		$vars['row'] && $propArray = array_slice($propArray, 0, $vars['row']);
		return $propArray;
	}
}
