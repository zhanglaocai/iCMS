<?php
defined('iPHP') OR exit('Access Denied');
return array (
  'site' =>
  array (
    'name' => 'iCMS',
    'seotitle' => '给我一套程序，我能搅动互联网',
    'keywords' => 'iCMS,idreamsoft,艾梦软件,iCMS内容管理系统,文章管理系统,PHP文章管理系统',
    'description' => 'iCMS 是一套采用 PHP 和 MySQL 构建的高效简洁的内容管理系统,为您的网站提供一个完美的开源解决方案',
    'icp' => '',
  ),
  'router' =>
  array (
    'url' => 'http://www.idreamsoft.com',
    404 => 'http://www.idreamsoft.com/public/404.htm',
    'public' => 'http://www.idreamsoft.com/public',
    'user' => 'http://www.idreamsoft.com/user',
    'dir' => '/',
    'ext' => '.html',
    'speed' => '5',
    'rewrite' => '0',
  ),
  'cache' =>
  array (
    'engine' => 'file',
    'host' => '',
    'time' => '300',
    'compress' => '1',
    'page_total' => '300',
  ),
  'FS' =>
  array (
    'url' => 'http://www.idreamsoft.com/res/',
    'dir' => 'res',
    'dir_format' => 'Y/m-d/H',
    'allow_ext' => 'gif,jpg,rar,swf,jpeg,png,zip',
    'cloud' =>
    array (
      'enable' => '0',
      'local' => '0',
      'sdk' =>
      array (
        'QiNiuYun' =>
        array (
          'domain' => '',
          'Bucket' => '',
          'AccessKey' => '',
          'SecretKey' => '',
        ),
        'TencentYun' =>
        array (
          'domain' => '',
          'AppId' => '',
          'Bucket' => '',
          'AccessKey' => '',
          'SecretKey' => '',
        ),
      ),
    ),
  ),
  'thumb' =>
  array (
    'size' => '',
  ),
  'watermark' =>
  array (
    'enable' => '0',
    'width' => '140',
    'height' => '140',
    'allow_ext' => 'jpg,jpeg,png',
    'pos' => '9',
    'x' => '10',
    'y' => '10',
    'img' => 'watermark.png',
    'text' => 'iCMS',
    'font' => '',
    'fontsize' => '24',
    'color' => '#000000',
    'transparent' => '80',
  ),
  'user' =>
  array (
    'register' =>
    array (
      'enable' => '1',
      'seccode' => '1',
      'interval' => '86400',
    ),
    'login' =>
    array (
      'enable' => '1',
      'seccode' => '1',
      'interval' => '3600',
    ),
    'post' =>
    array (
      'seccode' => '1',
      'interval' => '10',
    ),
    'agreement' => '',
    'coverpic' => '/ui/coverpic.jpg',
    'open' =>
    array (
      'WX' =>
      array (
        'appid' => '',
        'appkey' => '',
        'redirect' => '',
      ),
      'QQ' =>
      array (
        'appid' => '',
        'appkey' => '',
        'redirect' => '',
      ),
      'WB' =>
      array (
        'appid' => '',
        'appkey' => '',
        'redirect' => '',
      ),
      'TB' =>
      array (
        'appid' => '',
        'appkey' => '',
        'redirect' => '',
      ),
    ),
  ),
  'publish' =>
  array (
  ),
  'comment' =>
  array (
    'enable' => '1',
    'examine' => '0',
    'seccode' => '1',
    'plugin' =>
    array (
      'changyan' =>
      array (
        'enable' => '0',
        'appid' => '',
        'appkey' => '',
      ),
    ),
  ),
  'debug' =>
  array (
    'php' => '1',
    'php_trace' => '0',
    'tpl' => '1',
    'tpl_trace' => '0',
    'db' => '0',
    'db_trace' => '0',
    'db_explain' => '0',
  ),
  'time' =>
  array (
    'zone' => 'Asia/Shanghai',
    'cvtime' => '0',
    'dateformat' => 'Y-m-d H:i:s',
  ),
  'apps' =>
  array (
    'article' => '1',
    'category' => '2',
    'tag' => '3',
    'push' => '4',
    'comment' => '5',
    'prop' => '6',
    'message' => '7',
    'favorite' => '8',
    'user' => '9',
    'weixin' => '10',
    'keywords' => '12',
    'links' => '13',
    'marker' => '14',
    'search' => '15',
    'public' => '16',
    'database' => '17',
    'html' => '18',
    'index' => '19',
    'admincp' => '20',
    'apps' => '21',
    'group' => '22',
    'config' => '23',
    'members' => '24',
    'files' => '25',
    'menu' => '26',
    'editor' => '27',
    'patch' => '28',
    'template' => '29',
    'filter' => '30',
    'cache' => '31',
    'spider' => '32',
    'content' => '33',
    'plugin' => '34',
    'form' => '35',
    'ceshi' => '110',
  ),
  'other' =>
  array (
    'py_split' => '',
    'sidebar_enable' => '1',
    'sidebar' => '1',
  ),
  'system' =>
  array (
    'patch' => '1',
  ),
  'sphinx' =>
  array (
    'host' => '127.0.0.1:9312',
    'index' => 'iCMS_article iCMS_article_delta',
  ),
  'open' =>
  array (
  ),
  'template' =>
  array (
    'index' =>
    array (
      'mode' => '0',
      'rewrite' => '0',
      'tpl' => '{iTPL}/index.htm',
      'name' => 'index',
    ),
    'desktop' =>
    array (
      'tpl' => 'www/desktop',
    ),
    'mobile' =>
    array (
      'agent' => 'WAP,Smartphone,Mobile,UCWEB,Opera Mini,Windows CE,Symbian,SAMSUNG,iPhone,Android,BlackBerry,HTC,Mini,LG,SonyEricsson,J2ME,MOT',
      'domain' => 'http://www.idreamsoft.com',
      'tpl' => 'www/mobile',
    ),
  ),
  'api' =>
  array (
    'baidu' =>
    array (
      'sitemap' =>
      array (
        'site' => '',
        'access_token' => '',
        'sync' => '0',
      ),
    ),
  ),
  'mail' =>
  array (
    'host' => '',
    'secure' => '',
    'port' => '25',
    'username' => '',
    'password' => '',
    'setfrom' => '',
    'replyto' => '',
  ),
  'article' =>
  array (
    'pic_center' => '0',
    'pic_next' => '0',
    'pageno_incr' => '',
    'markdown' => '0',
    'autoformat' => '0',
    'catch_remote' => '0',
    'remote' => '0',
    'autopic' => '0',
    'autodesc' => '1',
    'descLen' => '100',
    'autoPage' => '0',
    'AutoPageLen' => '',
    'repeatitle' => '0',
    'showpic' => '0',
    'filter' => '0',
  ),
  'category' =>
  array (
    'domain' => NULL,
  ),
  'tag' =>
  array (
    'url' => 'http://www.idreamsoft.com',
    'rule' => '{TKEY}',
    'dir' => '/tag/',
    'tpl' => '{iTPL}/tag.htm',
  ),
  'weixin' =>
  array (
    'menu' =>
    array (
      0 =>
      array (
        'type' => 'view',
        'name' => '手册',
        'url' => 'http://www.idreamsoft.com/doc/iCMS/',
      ),
      1 =>
      array (
        'type' => 'view',
        'name' => '社区',
        'url' => 'http://www.idreamsoft.com/feedback/',
      ),
      2 =>
      array (
        'type' => 'click',
        'name' => '',
        'key' => '',
      ),
    ),
  ),
  'keywords' =>
  array (
    'limit' => '-1',
  ),
  'hooks' =>
  array (
    'article' =>
    array (
      'body' =>
      array (
        0 =>
        array (
          0 => 'keywordsApp',
          1 => 'HOOK_run',
        ),
        1 =>
        array (
          0 => 'plugin_taoke',
          1 => 'HOOK',
        ),
        2 =>
        array (
          0 => 'plugin_textad',
          1 => 'HOOK',
        ),
        3 =>
        array (
          0 => 'plugin_download',
          1 => 'HOOK',
        ),
      ),
    ),
  ),
  'iurl' =>
  array (
    'article' =>
    array (
      'rule' => '2',
      'primary' => 'id',
      'page' => 'p',
    ),
    'category' =>
    array (
      'rule' => '1',
      'primary' => 'cid',
    ),
    'tag' =>
    array (
      'rule' => '3',
      'primary' => 'id',
    ),
    'push' => NULL,
    'comment' => NULL,
    'prop' => NULL,
    'message' => NULL,
    'favorite' => NULL,
    'user' => NULL,
    'weixin' => NULL,
    'keywords' => NULL,
    'links' => NULL,
    'marker' => NULL,
    'search' => NULL,
    'public' => NULL,
    'database' => NULL,
    'html' => NULL,
    'index' =>
    array (
      'rule' => '0',
      'primary' => '',
    ),
    'admincp' => NULL,
    'apps' => NULL,
    'group' => NULL,
    'config' => NULL,
    'members' => NULL,
    'files' => NULL,
    'menu' => NULL,
    'editor' => NULL,
    'patch' => NULL,
    'template' => NULL,
    'filter' => NULL,
    'cache' => NULL,
    'spider' => NULL,
    'content' => NULL,
    'plugin' => NULL,
    'form' => NULL,
    'ceshi' =>
    array (
      'rule' => '4',
      'primary' => 'id',
      'page' => 'p',
    ),
  ),
);
