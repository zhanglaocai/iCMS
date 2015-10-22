<?php

/**
 * @package iCMS
 * @copyright 2007-2015, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 * @$Id: apps.class.php 141 2013-03-13 14:01:43Z coolmoo $
 */
class APPS {
    public static function table($appId){
        $appMap = array(
            '1'  => 'article',   //文章
            '2'  => 'category',  //分类
            '3'  => 'tags',      //标签
            '4'  => 'push',      //推送
            '5'  => 'comment',   //评论
            '6'  => 'prop',      //属性
            '7'  => 'message',   //私信
            '8'  => 'favorite',  //收藏
            '9'  => 'user',      //用户
            '10' => 'weixin',    //微信
            '11' => 'download',  //下载
        );
        return $appMap[$appId];
    }

}
