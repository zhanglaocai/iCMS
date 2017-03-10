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
        $variable = propApp::value($app,$field);

        $offset = $vars['start']?$vars['start']:0;
		$vars['row'] && $variable = array_slice($variable,$offset, $vars['row']);

        foreach ($variable as $key => $value) {
            if($field){
                $value['url'] = propApp::url($vars['url'],$value);
            }else{
                foreach ($value as $k => $v) {
                    $v['url'] = propApp::url($vars['url'],$v);
                    $value[$k] = $v;
                }
            }
            $variable[$key] = $value;
        }
		return $variable;
	}
}
