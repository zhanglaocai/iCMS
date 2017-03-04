<?php
/**
 * @package iCMS
 * @copyright 2007-2017, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
class plugin_download{
    /**
     * [插件:正文文件下载]
     * @param [type] $content  [参数]
     * @param [type] $resource [返回替换过的内容]
     */
    public static function HOOK($content) {
        preg_match_all('#<a\s*class="attachment".*ext=".*?"\s*fid=".*?"\s*path="(.*?)"\s*href="(.*?)"\s*title=".*?">.*?</a>#is',
            $content, $variable);
        foreach ((array)$variable[1] as $key => $path) {
           $info = pathinfo($path);
           $urlArray[$key]= filesApp::get_url($info['filename']);
        }
        if($urlArray){
            $content = str_replace($variable[2], $urlArray, $content);
        }
        return $content;
    }
}
