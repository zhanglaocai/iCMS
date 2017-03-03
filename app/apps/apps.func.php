<?php
/**
 * @package iCMS
 * @copyright 2007-2017, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
class appsFunc{
    public static function apps_data($vars){
        return apps::get_app($vars['id']);
    }
}
