<?php

$a = array(
  'appid' => '1',
  'app' => 'article',
  'title' => '文章',
  'description' => '一个还行的文章系统吧',
  'table' => array(
      0 => 'article',
      1 => 'article_data'
  ),
  'template' =>array(
      0 => 'iCMS:article:list',
      1 => 'iCMS:article:search',
      2 => 'iCMS:article:data',
      3 => 'iCMS:article:prev',
      4 => 'iCMS:article:next',
      5 => '$article'),
  'menu' =>array(
      0 => 'main',
      1 => 'sidebar'),
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
echo json_encode($a);
