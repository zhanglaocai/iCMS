<?php
/**
 * @package iCMS
 * @copyright 2007-2015, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 * @$Id: article.table.php 2408 2014-04-30 18:58:23Z coolmoo $
 */
defined('iPHP') OR exit('What are you doing?');

return array(
  'appid' => '1',
  'app' => 'article',
  'title' => '文章',
  'description' => '一个还行的文章系统吧',
  'table' => array(
      'article',
      'article_data'
  ),
  'template' =>array(
      'iCMS:article:list',
      'iCMS:article:search',
      'iCMS:article:data',
      'iCMS:article:prev',
      'iCMS:article:next',
      '$article'
    ),
  'menu' =>array(
      'main',
      'sidebar'
    ),
  'category' =>array(
      'cid' => 'cid',
      'text' => '栏目',
      'template' =>array(
          'index' => '{iTPL}/category.index.htm',
          'list' => '{iTPL}/category.list.htm',
          'content' => '{iTPL}/article.htm'
      ),
      'rule' =>array(
          'index' => '/{CDIR}/',
          'page' => '/{CDIR}/index_{P}.html',
          'content' => '/{CDIR}/{YYYY}/{MM}{DD}/{ID}.html'
      )
  ),
  'status' => '0',
  );
