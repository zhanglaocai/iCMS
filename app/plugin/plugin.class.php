<?php
/**
 * @package iCMS
 * @copyright 2007-2017, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
class plugin{
    public static function library($file) {
        $path = iPHP_APP_DIR . '/'.__CLASS__.'/library/'.$file;
        iPHP::import($path);
    }
    public static function import($file) {
        $path = iPHP_APP_DIR . '/'.__CLASS__.'/'.$file;
        iPHP::import($path);
    }
}

