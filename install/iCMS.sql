/*
SQLyog Ultimate v12.09 (64 bit)
MySQL - 5.5.53 : Database - icms62
*********************************************************************
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `icms_apps` */

DROP TABLE IF EXISTS `icms_apps`;

CREATE TABLE `icms_apps` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '应用ID appid',
  `app` varchar(100) NOT NULL DEFAULT '' COMMENT '应用标识',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '应用名',
  `apptype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '类型 0官方 1本地 2自定义',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '应用类型',
  `table` text NOT NULL COMMENT '应用表',
  `config` text NOT NULL COMMENT '应用配置',
  `fields` text NOT NULL COMMENT '应用自定义字段',
  `addtimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '应用状态',
  PRIMARY KEY (`id`),
  KEY `idx_name` (`app`)
) ENGINE=MyISAM AUTO_INCREMENT=100 DEFAULT CHARSET=utf8;

/*Data for the table `icms_apps` */

INSERT  INTO `icms_apps`(`id`,`app`,`name`,`apptype`,`type`,`table`,`config`,`fields`,`addtimes`,`status`) values
(1,'article','文章',0,1,'[[\"article\",\"id\",\"\\u6587\\u7ae0\"],[\"article_data\",\"id\",\"aid\",\"\\u6b63\\u6587\"]]','{\"iFormer\":\"1\",\"info\":\"\\u6587\\u7ae0\\u8d44\\u8baf\\u7cfb\\u7edf\",\"template\":[\"iCMS:article:list\",\"iCMS:article:search\",\"iCMS:article:data\",\"iCMS:article:prev\",\"iCMS:article:next\",\"$article\"],\"menu\":\"main\"}','',1482587647,1),
(2,'category','分类',0,1,'[[\"category\",\"cid\",\"\\u5206\\u7c7b\"],[\"category_map\",\"id\",\"node\",\"\\u5206\\u7c7b\\u6620\\u5c04\"]]','{\"iFormer\":\"1\",\"info\":\"\\u901a\\u7528\\u65e0\\u9650\\u7ea7\\u5206\\u7c7b\\u7cfb\\u7edf\",\"template\":[\"iCMS:category:list\",\"iCMS:category:array\",\"$category\"],\"menu\":\"main\"}','',1482587669,1),
(3,'tag','标签',0,1,'[[\"tags\",\"id\",\"\\u6807\\u7b7e\"],[\"tags_map\",\"id\",\"node\",\"\\u6807\\u7b7e\\u6620\\u5c04\"]]','{\"iFormer\":\"1\",\"info\":\"\\u81ea\\u7531\\u591a\\u6837\\u6027\\u6807\\u7b7e\\u7cfb\\u7edf\",\"template\":[\"iCMS:tag:list\",\"iCMS:tag:array\",\"$tag\"],\"menu\":\"main\"}','',1482587692,1),
(4,'push','推荐',0,1,'[[\"push\",\"id\",\"\\u63a8\\u8350\"]]','{\"iFormer\":\"1\",\"info\":\"\\u63a8\\u8350\\u7cfb\\u7edf\",\"template\":[\"iCMS:push:list\"],\"menu\":\"main\"}','',1482587695,1),
(5,'comment','评论',0,1,'[[\"comment\",\"id\",\"\\u8bc4\\u8bba\"]]','{\"iFormer\":\"1\",\"info\":\"\\u901a\\u7528\\u8bc4\\u8bba\\u7cfb\\u7edf\",\"template\":[\"iCMS:comment:list\",\"iCMS:comment:form\",\"iCMS:comment:array\"],\"menu\":\"main\"}','',1482587699,1),
(6,'prop','属性',0,1,'[[\"prop\",\"pid\",\"\\u5c5e\\u6027\"],[\"prop_map\",\"id\",\"node\",\"\\u5c5e\\u6027\\u6620\\u5c04\"]]','{\"info\":\"\\u901a\\u7528\\u5c5e\\u6027\\u7cfb\\u7edf\",\"template\":[\"iCMS:prop:array\"],\"menu\":\"main\"}','',1482587702,1),
(7,'message','私信',0,1,'[[\"message\",\"id\",\"\\u79c1\\u4fe1\"]]','{\"info\":\"\\u7528\\u6237\\u79c1\\u4fe1\\u7cfb\\u7edf\"}','',1482588814,1),
(8,'favorite','收藏',0,1,'[[\"favorite\",\"id\",\"\\u6536\\u85cf\\u4fe1\\u606f\"],[\"favorite_data\",\"fid\",\"\\u6536\\u85cf\\u6570\\u636e\"],[\"favorite_follow\",\"id\",\"fid\",\"\\u6536\\u85cf\\u5173\\u6ce8\"]]','{\"info\":\"\\u7528\\u6237\\u6536\\u85cf\\u7cfb\\u7edf\",\"template\":[\"iCMS:favorite:list\",\"iCMS:favorite:data\",\"$favorite\"],\"menu\":\"main\"}','',1482587706,1),
(9,'user','用户',0,1,'{\"user\":[\"user\",\"uid\",\"\",\"\\u7528\\u6237\"],\"user_category\":[\"user_category\",\"cid\",\"uid\",\"\\u7528\\u6237\\u5206\\u7c7b\"],\"user_data\":[\"user_data\",\"uid\",\"uid\",\"\\u7528\\u6237\\u6570\\u636e\"],\"user_follow\":[\"user_follow\",\"uid\",\"uid\",\"\\u7528\\u6237\\u5173\\u6ce8\"],\"user_openid\":[\"user_openid\",\"uid\",\"uid\",\"\\u7b2c\\u4e09\\u65b9\"],\"user_report\":[\"user_report\",\"id\",\"userid\",\"\\u4e3e\\u62a5\"]}','{\"iFormer\":\"1\",\"info\":\"\\u7528\\u6237\\u7cfb\\u7edf\",\"template\":[\"iCMS:user:data\",\"iCMS:user:list\",\"iCMS:user:category\",\"iCMS:user:follow\",\"iCMS:user:stat\",\"iCMS:user:inbox\"],\"router\":\"1\",\"menu\":\"main\"}','',1488116809,1),
(10,'weixin','微信',0,1,'[[\"weixin_api_log\",\"id\",\"\\u8bb0\\u5f55\"],[\"weixin_event\",\"id\",\"\\u4e8b\\u4ef6\"]]','{\"info\":\"\\u5fae\\u4fe1\\u516c\\u4f17\\u5e73\\u53f0\\u63a5\\u53e3\\u7a0b\\u5e8f\",\"menu\":\"main\",\"admincp\":\"weixin&do=menu\"}','',1482587917,1),
(11,'software','下载',0,1,'[[\"software\",\"id\",\"\\u4fe1\\u606f\"],[\"software_data\",\"sid\",\"\\u56fe\\u7247\\/\\u4ecb\\u7ecd\"],[\"software_list\",\"sid\",\"\\u5217\\u8868\"]]','{\"info\":\"\\u4e0b\\u8f7d\\u7cfb\\u7edf\",\"menu\":\"main\"}','',1482588906,1),
(12,'keywords','内链',0,2,'[[\"keywords\",\"id\",\"\\u5185\\u94fe\"]]','{\"info\":\"\\u5185\\u94fe\\u7cfb\\u7edf\",\"menu\":\"main\"}','',1482587894,1),
(13,'links','友情链接',0,1,'[[\"links\",\"id\",\"\\u53cb\\u60c5\\u94fe\\u63a5\"]]','{\"info\":\"\\u53cb\\u60c5\\u94fe\\u63a5\\u7a0b\\u5e8f\",\"template\":[\"iCMS:links:list\"],\"menu\":\"main\"}','',1482587722,1),
(14,'marker','标记',0,1,'[[\"marker\",\"id\",\"\\u6807\\u8bb0\"]]','{\"iFormer\":\"1\",\"info\":\"\\u6807\\u8bb0\\u7ba1\\u7406\\u7cfb\\u7edf\",\"template\":[\"iCMS:marker:html\"],\"menu\":\"main\"}','',1482587726,1),
(15,'search','搜索',0,1,'[[\"search_log\",\"id\",\"\\u641c\\u7d22\\u8bb0\\u5f55\"]]','{\"info\":\"\\u6587\\u7ae0\\u641c\\u7d22\\u7cfb\\u7edf\",\"template\":[\"iCMS:search:list\",\"iCMS:search:url\",\"$search\"],\"menu\":\"main\"}','',1482587729,1),
(16,'public','公共',0,1,'0','{\"info\":\"\\u516c\\u5171\\u901a\\u7528\\u6807\\u7b7e\",\"template\":[\"iCMS:public:ui\",\"iCMS:public:seccode\",\"iCMS:public:crontab\",\"iCMS:public:qrcode\"],\"menu\":\"main\",\"admincp\":\"null\"}','',1483236548,1),
(17,'admincp','后台系统',0,0,'0','{\"info\":\"\\u57fa\\u7840\\u7ba1\\u7406\\u7cfb\\u7edf\",\"menu\":\"main\",\"admincp\":\"__SELF__\"}','',1482587926,1),
(18,'index','首页系统',0,1,'0','{\"info\":\"\\u9996\\u9875\\u7a0b\\u5e8f\",\"menu\":\"main\",\"admincp\":\"null\"}','',1482588076,1),
(19,'html','静态系统',0,1,'0','{\"info\":\"\\u9759\\u6001\\u6587\\u4ef6\\u751f\\u6210\\u7a0b\\u5e8f\",\"menu\":\"main\",\"admincp\":\"html&do=index\"}','',1482588133,1),
(20,'database','数据库管理',0,1,'0','{\"info\":\"\\u540e\\u53f0\\u7b80\\u6613\\u6570\\u636e\\u5e93\\u7ba1\\u7406\",\"menu\":\"main\",\"admincp\":\"database&do=backup\"}','',1482587932,1),
(21,'apps','应用管理',0,0,'[[\"apps\",\"id\",\"\\u5e94\\u7528\"]]','{\"info\":\"\\u5e94\\u7528\\u7ba1\\u7406\",\"menu\":\"main\"}','',1482588934,1),
(22,'spider','采集系统',0,1,'[[\"spider_post\",\"id\",\"\\u53d1\\u5e03\"],[\"spider_project\",\"id\",\"\\u65b9\\u6848\"],[\"spider_rule\",\"id\",\"\\u89c4\\u5219\"],[\"spider_url\",\"id\",\"\\u91c7\\u96c6\\u7ed3\\u679c\"]]','{\"info\":\"\\u91c7\\u96c6\\u7cfb\\u7edf\",\"menu\":\"main\",\"admincp\":\"spider&do=project\"}','',1482588092,1),
(23,'group','角色系统',0,0,'[[\"group\",\"gid\",\"\\u89d2\\u8272\"]]','{\"info\":\"\\u89d2\\u8272\\u6743\\u9650\\u7cfb\\u7edf\",\"menu\":\"main\"}','',1482623597,1),
(24,'config','系统配置',0,0,'[[\"config\",\"appid\",\"\\u7cfb\\u7edf\\u914d\\u7f6e\"]]','{\"info\":\"\\u7cfb\\u7edf\\u914d\\u7f6e\",\"menu\":\"main\"}','',1482626798,1),
(25,'members','管理员',0,0,'[[\"members\",\"uid\",\"\\u7ba1\\u7406\\u5458\"]]','{\"info\":\"\\u7ba1\\u7406\\u5458\\u7cfb\\u7edf\",\"menu\":\"main\"}','',1482623563,1),
(26,'files','文件管理',0,0,'[[\"file_data\",\"id\",\"\\u6587\\u4ef6\"],[\"file_map\",\"fileid\",\"fileid\",\"\\u6587\\u4ef6\\u6620\\u5c04\"]]','{\"info\":\"\\u6587\\u4ef6\\u7ba1\\u7406\\u7cfb\\u7edf\",\"menu\":\"main\"}','',1482623525,1),
(27,'filter','过滤系统',0,1,'0','{\"info\":\"\\u5173\\u952e\\u8bcd\\u8fc7\\u6ee4\\/\\u8fdd\\u7981\\u8bcd\\u7cfb\\u7edf\",\"menu\":\"main\"}','',1482728551,1),
(28,'cache','缓存更新',0,1,'0','{\"info\":\"\\u7528\\u4e8e\\u66f4\\u65b0\\u5e94\\u7528\\u7a0b\\u5e8f\\u7f13\\u5b58\",\"menu\":\"main\"}','',1482728476,1),
(29,'template','模板管理',0,0,'0','{\"info\":\"\\u6a21\\u677f\\u7ba1\\u7406\",\"menu\":\"main\"}','',1482728448,1),
(30,'menu','后台菜单',0,0,'0','{\"info\":\"\\u540e\\u53f0\\u83dc\\u5355\\u7ba1\\u7406\",\"menu\":\"main\"}','',1482728434,1),
(31,'editor','后台编辑器',0,0,'0','{\"info\":\"\\u540e\\u53f0\\u7f16\\u8f91\\u5668\",\"menu\":\"main\"}','',1482728399,1),
(32,'patch','升级程序',0,0,'0','{\"info\":\"\\u7528\\u4e8e\\u5347\\u7ea7\\u7cfb\\u7edf\",\"menu\":\"main\"}','',1482728309,1),
(33,'app','自定义程序',0,0,'0','{\"info\":\"\\u7528\\u4e8e\\u5347\\u7ea7\\u7cfb\\u7edf\",\"menu\":\"main\"}','',1482728309,1);

/*Table structure for table `icms_article` */

DROP TABLE IF EXISTS `icms_article`;

CREATE TABLE `icms_article` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文章ID',
  `cid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '栏目id',
  `scid` varchar(255) NOT NULL DEFAULT '' COMMENT '副栏目',
  `ucid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户分类',
  `pid` varchar(255) NOT NULL DEFAULT '' COMMENT '属性',
  `sortnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `stitle` varchar(255) NOT NULL DEFAULT '' COMMENT '短标题',
  `clink` varchar(255) NOT NULL DEFAULT '' COMMENT '自定义链接',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '外部链接',
  `source` varchar(255) NOT NULL DEFAULT '' COMMENT '出处',
  `author` varchar(255) NOT NULL DEFAULT '' COMMENT '作者',
  `editor` varchar(255) NOT NULL DEFAULT '' COMMENT '编辑',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `haspic` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否有缩略图',
  `pic` varchar(255) NOT NULL DEFAULT '' COMMENT '缩略图',
  `mpic` varchar(255) NOT NULL DEFAULT '' COMMENT '缩略图2',
  `spic` varchar(255) NOT NULL DEFAULT '' COMMENT '缩略图3',
  `picdata` varchar(255) NOT NULL DEFAULT '' COMMENT '图片数据',
  `keywords` varchar(255) NOT NULL DEFAULT '' COMMENT '关键词',
  `tags` varchar(255) NOT NULL DEFAULT '' COMMENT '标签',
  `description` varchar(5120) NOT NULL DEFAULT '' COMMENT '摘要',
  `related` text NOT NULL COMMENT '相关',
  `pubdate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '发布时间',
  `postime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '提交时间',
  `tpl` varchar(255) NOT NULL DEFAULT '' COMMENT '模板',
  `hits` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '总点击数',
  `hits_today` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '当天点击数',
  `hits_yday` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '昨天点击数',
  `hits_week` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '周点击',
  `hits_month` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '月点击',
  `favorite` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '收藏数',
  `comments` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '评论数',
  `good` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '顶',
  `bad` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '踩',
  `creative` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '文章类型 1原创 0转载',
  `chapter` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '章节',
  `weight` int(10) NOT NULL DEFAULT '0' COMMENT '权重',
  `markdown` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'markdown标识',
  `mobile` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1手机发布 0 pc',
  `postype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '类型 0用户 1管理员',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '[[0:草稿],[1:正常],[2:回收],[3:审核],[4:不合格]]',
  PRIMARY KEY (`id`),
  KEY `id` (`status`,`id`),
  KEY `hits` (`status`,`hits`),
  KEY `pubdate` (`status`,`pubdate`),
  KEY `hits_week` (`status`,`hits_week`),
  KEY `hits_month` (`status`,`hits_month`),
  KEY `cid_hits` (`status`,`cid`,`hits`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `icms_article_data` */

DROP TABLE IF EXISTS `icms_article_data`;

CREATE TABLE `icms_article_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `aid` int(10) unsigned NOT NULL DEFAULT '0',
  `subtitle` varchar(255) NOT NULL DEFAULT '',
  `body` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `aid` (`aid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `icms_category` */

DROP TABLE IF EXISTS `icms_category`;

CREATE TABLE `icms_category` (
  `cid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rootid` int(10) unsigned NOT NULL DEFAULT '0',
  `pid` varchar(255) NOT NULL DEFAULT '',
  `appid` smallint(6) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `creator` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `subname` varchar(255) NOT NULL DEFAULT '',
  `sortnum` int(10) unsigned NOT NULL DEFAULT '0',
  `password` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `keywords` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `dir` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `pic` varchar(255) NOT NULL DEFAULT '',
  `mpic` varchar(255) NOT NULL DEFAULT '',
  `spic` varchar(255) NOT NULL DEFAULT '',
  `count` int(10) unsigned NOT NULL DEFAULT '0',
  `mode` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `domain` varchar(255) NOT NULL DEFAULT '',
  `htmlext` varchar(10) NOT NULL DEFAULT '',
  `rule` text NOT NULL,
  `template` text NOT NULL,
  `comments` int(10) unsigned NOT NULL DEFAULT '0',
  `isexamine` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `issend` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `isucshow` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `pubdate` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`cid`),
  KEY `dir` (`dir`),
  KEY `s_o_cid` (`status`,`sortnum`,`cid`),
  KEY `t_o_cid` (`appid`,`sortnum`,`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `icms_category_map` */

DROP TABLE IF EXISTS `icms_category_map`;

CREATE TABLE `icms_category_map` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `node` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'category cid',
  `iid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '内容ID',
  `appid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '应用ID',
  PRIMARY KEY (`id`),
  KEY `idx` (`appid`,`node`,`iid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


/*Table structure for table `icms_comment` */

DROP TABLE IF EXISTS `icms_comment`;

CREATE TABLE `icms_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `appid` int(10) unsigned NOT NULL DEFAULT '0',
  `cid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '被评论内容分类',
  `iid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '被评论内容ID',
  `suid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '被评论内容用户ID',
  `title` varchar(255) NOT NULL DEFAULT '',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '评论者ID',
  `username` varchar(255) NOT NULL DEFAULT '' COMMENT '评论者',
  `content` text NOT NULL,
  `reply_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '回复 评论ID',
  `reply_uid` int(11) unsigned NOT NULL DEFAULT '0',
  `reply_name` varchar(255) NOT NULL DEFAULT '',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `up` int(10) unsigned NOT NULL DEFAULT '0',
  `down` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(20) NOT NULL DEFAULT '',
  `quote` int(10) unsigned NOT NULL DEFAULT '0',
  `floor` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_iid` (`appid`,`status`,`iid`,`id`),
  KEY `idx_uid` (`status`,`userid`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


/*Table structure for table `icms_config` */

DROP TABLE IF EXISTS `icms_config`;

CREATE TABLE `icms_config` (
  `appid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `value` mediumtext NOT NULL,
  PRIMARY KEY (`appid`,`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `icms_config` */

insert  into `icms_config`(`appid`,`name`,`value`) values
  (0,'site','{\"name\":\"\\u827e\\u68a6\\u8f6f\\u4ef6\",\"seotitle\":\"\\u7ed9\\u6211\\u4e00\\u5957\\u7a0b\\u5e8f\\uff0c\\u6211\\u80fd\\u6405\\u52a8\\u4e92\\u8054\\u7f51\",\"keywords\":\"iCMS,idreamsoft,\\u827e\\u68a6\\u8f6f\\u4ef6,iCMS\\u5185\\u5bb9\\u7ba1\\u7406\\u7cfb\\u7edf,\\u6587\\u7ae0\\u7ba1\\u7406\\u7cfb\\u7edf,PHP\\u6587\\u7ae0\\u7ba1\\u7406\\u7cfb\\u7edf\",\"description\":\"iCMS \\u662f\\u4e00\\u5957\\u91c7\\u7528 PHP \\u548c MySQL \\u6784\\u5efa\\u7684\\u9ad8\\u6548\\u7b80\\u6d01\\u7684\\u5185\\u5bb9\\u7ba1\\u7406\\u7cfb\\u7edf,\\u4e3a\\u60a8\\u7684\\u7f51\\u7ad9\\u63d0\\u4f9b\\u4e00\\u4e2a\\u5b8c\\u7f8e\\u7684\\u5f00\\u6e90\\u89e3\\u51b3\\u65b9\\u6848\",\"icp\":\"\"}'),
  (0,'router','{\"url\":\"http:\\/\\/www.idreamsoft.com\",\"404\":\"http:\\/\\/www.idreamsoft.com\\/public\\/404.htm\",\"public\":\"http:\\/\\/www.idreamsoft.com\\/public\",\"user\":\"http:\\/\\/www.idreamsoft.com\\/user\",\"dir\":\"\\/\",\"ext\":\".html\",\"speed\":\"5\",\"rewrite\":\"0\"}'),
  (0,'cache','{\"engine\":\"file\",\"host\":\"\",\"time\":\"300\",\"compress\":\"1\",\"page_total\":\"300\"}'),
  (0,'FS','{\"url\":\"http:\\/\\/www.idreamsoft.com\\/res\\/\",\"dir\":\"..\\/res\",\"dir_format\":\"Y\\/m-d\\/H\",\"allow_ext\":\"gif,jpg,rar,swf,jpeg,png,zip\",\"cloud\":{\"enable\":\"0\",\"local\":\"0\",\"sdk\":{\"QiNiuYun\":{\"domain\":\"\",\"Bucket\":\"\",\"AccessKey\":\"\",\"SecretKey\":\"\"},\"TencentYun\":{\"domain\":\"\",\"AppId\":\"\",\"Bucket\":\"\",\"AccessKey\":\"\",\"SecretKey\":\"\"}}}}'),
  (0,'thumb','{\"size\":\"\"}'),
  (0,'watermark','{\"enable\":\"0\",\"width\":\"140\",\"height\":\"140\",\"allow_ext\":\"jpg,jpeg,png\",\"pos\":\"9\",\"x\":\"10\",\"y\":\"10\",\"img\":\"watermark.png\",\"text\":\"iCMS\",\"font\":\"\",\"fontsize\":\"24\",\"color\":\"#000000\",\"transparent\":\"80\"}'),
  (0,'user','a:5:{s:8:\"register\";a:3:{s:6:\"enable\";s:1:\"1\";s:7:\"seccode\";s:1:\"1\";s:8:\"interval\";s:5:\"86400\";}s:5:\"login\";a:3:{s:6:\"enable\";s:1:\"1\";s:7:\"seccode\";s:1:\"1\";s:8:\"interval\";s:3:\"600\";}s:4:\"post\";a:2:{s:7:\"seccode\";s:1:\"1\";s:8:\"interval\";s:1:\"0\";}s:9:\"agreement\";s:0:\"\";s:8:\"coverpic\";s:16:\"/ui/coverpic.jpg\";}'),
  (0,'publish','a:10:{s:10:\"autoformat\";s:1:\"0\";s:12:\"catch_remote\";s:1:\"0\";s:6:\"remote\";s:1:\"0\";s:7:\"autopic\";s:1:\"1\";s:8:\"autodesc\";s:1:\"1\";s:7:\"descLen\";s:3:\"100\";s:8:\"autoPage\";s:1:\"0\";s:11:\"AutoPageLen\";s:4:\"1000\";s:10:\"repeatitle\";s:1:\"0\";s:7:\"showpic\";s:1:\"1\";}'),
  (0,'comment','a:4:{s:6:\"enable\";s:1:\"1\";s:7:\"examine\";s:1:\"0\";s:7:\"seccode\";s:1:\"1\";s:6:\"plugin\";a:1:{s:8:\"changyan\";a:3:{s:6:\"enable\";s:1:\"0\";s:5:\"appid\";s:0:\"\";s:6:\"appkey\";s:0:\"\";}}}'),
  (0,'debug','{\"php\":\"0\",\"php_trace\":\"0\",\"tpl\":\"0\",\"tpl_trace\":\"0\",\"db\":\"0\",\"db_trace\":\"0\",\"db_explain\":\"0\"}'),
  (0,'time','{\"zone\":\"Asia\\/Shanghai\",\"cvtime\":\"0\",\"dateformat\":\"Y-m-d H:i:s\"}'),
  (0,'apps','a:11:{i:0;s:5:\"index\";i:1;s:7:\"article\";i:2;s:3:\"tag\";i:3;s:6:\"search\";i:4;s:6:\"usercp\";i:5;s:8:\"category\";i:6;s:7:\"comment\";i:7;s:8:\"favorite\";i:8;s:6:\"public\";i:9;s:4:\"user\";i:10;s:6:\"weixin\";}'),
  (0,'other','{\"py_split\":\"\",\"sidebar_enable\":\"1\",\"sidebar\":\"1\"}'),
  (0,'system','{\"patch\":\"1\"}'),
  (0,'sphinx','{\"host\":\"\",\"index\":\"\"}'),
  (0,'open','a:4:{s:2:\"WX\";a:3:{s:5:\"appid\";s:0:\"\";s:6:\"appkey\";s:0:\"\";s:8:\"redirect\";s:0:\"\";}s:2:\"QQ\";a:3:{s:5:\"appid\";s:0:\"\";s:6:\"appkey\";s:0:\"\";s:8:\"redirect\";s:0:\"\";}s:2:\"WB\";a:3:{s:5:\"appid\";s:0:\"\";s:6:\"appkey\";s:0:\"\";s:8:\"redirect\";s:0:\"\";}s:2:\"TB\";a:3:{s:5:\"appid\";s:0:\"\";s:6:\"appkey\";s:0:\"\";s:8:\"redirect\";s:0:\"\";}}'),
  (0,'template','{\"index\":{\"mode\":\"0\",\"rewrite\":\"0\",\"tpl\":\"{iTPL}\\/index.htm\",\"name\":\"index\"},\"desktop\":{\"tpl\":\"www\\/desktop\"},\"mobile\":{\"agent\":\"WAP,Smartphone,Mobile,UCWEB,Opera Mini,Windows CE,Symbian,SAMSUNG,iPhone,Android,BlackBerry,HTC,Mini,LG,SonyEricsson,J2ME,MOT\",\"domain\":\"http:\\/\\/www.idreamsoft.com\",\"tpl\":\"www\\/mobile\"}}'),
  (0,'api','{\"baidu\":{\"sitemap\":{\"site\":\"\",\"access_token\":\"\",\"sync\":\"0\"}}}'),
  (0,'mail','{\"host\":\"\",\"secure\":\"\",\"port\":\"25\",\"username\":\"\",\"password\":\"\",\"setfrom\":\"\",\"replyto\":\"\"}'),
  (1,'article','{\"pic_center\":\"1\",\"pic_next\":\"1\",\"pageno_incr\":\"\",\"markdown\":\"0\",\"autoformat\":\"0\",\"catch_remote\":\"0\",\"remote\":\"1\",\"autopic\":\"1\",\"autodesc\":\"1\",\"descLen\":\"50\",\"autoPage\":\"0\",\"AutoPageLen\":\"\",\"repeatitle\":\"0\",\"showpic\":\"0\",\"filter\":\"0\"}'),
  (2,'category','a:1:{s:6:\"domain\";N;}'),
  (3,'tag','{\"url\":\"http:\\/\\/www.idreamsoft.com\",\"rule\":\"{TKEY}\",\"dir\":\"\\/tag\\/\",\"tpl\":\"{iTPL}\\/tag.htm\"}'),
  (5,'comment','{\"enable\":\"1\",\"examine\":\"0\",\"seccode\":\"1\",\"plugin\":{\"changyan\":{\"enable\":\"0\",\"appid\":\"\",\"appkey\":\"\"}}}'),
  (9,'user','{\"register\":{\"enable\":\"1\",\"seccode\":\"1\",\"interval\":\"86400\"},\"login\":{\"enable\":\"1\",\"seccode\":\"1\",\"interval\":\"3600\"},\"post\":{\"seccode\":\"1\",\"interval\":\"10\"},\"agreement\":\"\",\"coverpic\":\"\\/ui\\/coverpic.jpg\",\"open\":{\"WX\":{\"appid\":\"\",\"appkey\":\"\",\"redirect\":\"\"},\"QQ\":{\"appid\":\"\",\"appkey\":\"\",\"redirect\":\"\"},\"WB\":{\"appid\":\"\",\"appkey\":\"\",\"redirect\":\"\"},\"TB\":{\"appid\":\"\",\"appkey\":\"\",\"redirect\":\"\"}}}'),
  (10,'weixin','{\"menu\":[{\"type\":\"view\",\"name\":\"\\u624b\\u518c\",\"url\":\"http:\\/\\/www.idreamsoft.com\\/doc\\/iCMS\\/\"},{\"type\":\"view\",\"name\":\"\\u793e\\u533a\",\"url\":\"http:\\/\\/www.idreamsoft.com\\/feedback\\/\"},{\"type\":\"click\",\"name\":\"\",\"key\":\"\"}]}'),
  (999999,'filter','a:2:{s:7:\"disable\";a:1461:{i:0;s:10:\"樊甲山\r\";i:1;s:4:\"★\r\";i:2;s:10:\"红二代\r\";i:3;s:7:\"动乱\r\";i:4;s:10:\"群体性\r\";i:5;s:7:\"暴乱\r\";i:6;s:7:\"天朝\r\";i:7;s:13:\"党内斗争\r\";i:8;s:10:\"房峰辉\r\";i:9;s:7:\"艾未\r\";i:10;s:10:\"胡耀邦\r\";i:11;s:10:\"李长春\r\";i:12;s:10:\"赵紫阳\r\";i:13;s:10:\"杜导正\r\";i:14;s:13:\"炎黄春秋\r\";i:15;s:7:\"民运\r\";i:16;s:13:\"中国之春\r\";i:17;s:13:\"中共特工\r\";i:18;s:10:\"江锦恒\r\";i:19;s:10:\"蒋宏坤\r\";i:20;s:5:\"isis\r\";i:21;s:10:\"温家宝\r\";i:22;s:10:\"曾庆红\r\";i:23;s:10:\"胡锦涛\r\";i:24;s:10:\"江泽民\r\";i:25;s:10:\"共产党\r\";i:26;s:7:\"总理\r\";i:27;s:7:\"书记\r\";i:28;s:10:\"国务院\r\";i:29;s:10:\"政治局\r\";i:30;s:7:\"中共\r\";i:31;s:7:\"政府\r\";i:32;s:7:\"官员\r\";i:33;s:7:\"贪污\r\";i:34;s:7:\"腐败\r\";i:35;s:7:\"省长\r\";i:36;s:7:\"市长\r\";i:37;s:7:\"省委\r\";i:38;s:7:\"市委\r\";i:39;s:7:\"记委\r\";i:40;s:7:\"腐化\r\";i:41;s:7:\"六四\r\";i:42;s:10:\"天安门\r\";i:43;s:10:\"习近平\r\";i:44;s:7:\"马凯\r\";i:45;s:10:\"王岐山\r\";i:46;s:10:\"王沪宁\r\";i:47;s:10:\"刘云山\r\";i:48;s:10:\"刘延东\r\";i:49;s:10:\"刘奇葆\r\";i:50;s:10:\"许其亮\r\";i:51;s:10:\"孙春兰\r\";i:52;s:10:\"孙政才\r\";i:53;s:10:\"李克强\r\";i:54;s:10:\"李建国\r\";i:55;s:10:\"李源潮\r\";i:56;s:7:\"汪洋\r\";i:57;s:10:\"张春贤\r\";i:58;s:10:\"张高丽\r\";i:59;s:10:\"张德江\r\";i:60;s:10:\"范长龙\r\";i:61;s:10:\"孟建柱\r\";i:62;s:10:\"赵乐际\r\";i:63;s:10:\"胡春华\r\";i:64;s:10:\"俞正声\r\";i:65;s:10:\"栗战书\r\";i:66;s:10:\"郭金龙\r\";i:67;s:7:\"韩正\r\";i:68;s:10:\"杜青林\r\";i:69;s:10:\"赵洪祝\r\";i:70;s:10:\"于广洲\r\";i:71;s:7:\"马飚\r\";i:72;s:10:\"马兴瑞\r\";i:73;s:10:\"马晓天\r\";i:74;s:10:\"王三运\r\";i:75;s:10:\"王万宾\r\";i:76;s:10:\"王玉普\r\";i:77;s:10:\"王正伟\r\";i:78;s:10:\"王东明\r\";i:79;s:10:\"王光亚\r\";i:80;s:10:\"王伟光\r\";i:81;s:10:\"王安顺\r\";i:82;s:10:\"王志刚\r\";i:83;s:10:\"王国生\r\";i:84;s:10:\"王学军\r\";i:85;s:10:\"王建平\r\";i:86;s:10:\"王胜俊\r\";i:87;s:10:\"王洪尧\r\";i:88;s:10:\"王宪魁\r\";i:89;s:10:\"王冠中\r\";i:90;s:10:\"王家瑞\r\";i:91;s:10:\"王教成\r\";i:92;s:10:\"王新宪\r\";i:93;s:10:\"王儒林\r\";i:94;s:10:\"支树平\r\";i:95;s:7:\"尤权\r\";i:96;s:7:\"车俊\r\";i:97;s:10:\"尹蔚民\r\";i:98;s:13:\"巴音朝鲁\r\";i:99;s:10:\"巴特尔\r\";i:100;s:10:\"卢展工\r\";i:101;s:10:\"叶小文\r\";i:102;s:10:\"田修思\r\";i:103;s:13:\"白玛赤林\r\";i:104;s:10:\"白春礼\r\";i:105;s:10:\"令计划\r\";i:106;s:10:\"吉炳轩\r\";i:107;s:10:\"朱小丹\r\";i:108;s:10:\"朱福熙\r\";i:109;s:10:\"全哲洙\r\";i:110;s:7:\"刘鹏\r\";i:111;s:7:\"刘源\r\";i:112;s:7:\"刘鹤\r\";i:113;s:10:\"刘亚洲\r\";i:114;s:10:\"刘成军\r\";i:115;s:10:\"刘伟平\r\";i:116;s:10:\"刘晓江\r\";i:117;s:10:\"刘家义\r\";i:118;s:10:\"刘粤军\r\";i:119;s:10:\"刘福连\r\";i:120;s:10:\"许达哲\r\";i:121;s:10:\"许耀元\r\";i:122;s:10:\"孙怀山\r\";i:123;s:10:\"孙建国\r\";i:124;s:10:\"孙思敬\r\";i:125;s:10:\"苏树林\r\";i:126;s:10:\"杜金才\r\";i:127;s:10:\"杜恒岩\r\";i:128;s:10:\"李从军\r\";i:129;s:10:\"李东生\r\";i:130;s:10:\"李立国\r\";i:131;s:10:\"李纪恒\r\";i:132;s:10:\"李学勇\r\";i:133;s:10:\"李建华\r\";i:134;s:10:\"李鸿忠\r\";i:135;s:7:\"杨晶\r\";i:136;s:10:\"杨传堂\r\";i:137;s:10:\"杨金山\r\";i:138;s:10:\"杨栋梁\r\";i:139;s:10:\"杨洁篪\r\";i:140;s:10:\"杨焕宁\r\";i:141;s:7:\"肖钢\r\";i:142;s:7:\"肖捷\r\";i:143;s:10:\"吴昌德\r\";i:144;s:10:\"吴胜利\r\";i:145;s:10:\"吴爱英\r\";i:146;s:10:\"吴新雄\r\";i:147;s:10:\"何毅亭\r\";i:148;s:7:\"冷溶\r\";i:149;s:10:\"汪永清\r\";i:150;s:10:\"沈跃跃\r\";i:151;s:10:\"沈德咏\r\";i:152;s:10:\"宋大涵\r\";i:153;s:10:\"宋秀岩\r\";i:154;s:7:\"张茅\r\";i:155;s:10:\"张又侠\r\";i:156;s:10:\"张仕波\r\";i:157;s:10:\"张庆伟\r\";i:158;s:10:\"张庆黎\r\";i:159;s:10:\"张志军\r\";i:160;s:10:\"张国清\r\";i:161;s:10:\"张宝顺\r\";i:162;s:10:\"张海阳\r\";i:163;s:10:\"张裔炯\r\";i:164;s:7:\"陆昊\r\";i:165;s:10:\"陈全国\r\";i:166;s:10:\"陈求发\r\";i:167;s:10:\"陈宝生\r\";i:168;s:10:\"陈政高\r\";i:169;s:10:\"陈敏尔\r\";i:170;s:7:\"努尔\r\";i:171;s:10:\"白克力\r\";i:172;s:7:\"苗圩\r\";i:173;s:10:\"林左鸣\r\";i:174;s:10:\"尚福林\r\";i:175;s:10:\"罗志军\r\";i:176;s:10:\"罗保铭\r\";i:177;s:7:\"周济\r\";i:178;s:7:\"周强\r\";i:179;s:10:\"周本顺\r\";i:180;s:10:\"周生贤\r\";i:181;s:10:\"郑卫平\r\";i:182;s:10:\"孟学农\r\";i:183;s:10:\"项俊波\r\";i:184;s:7:\"赵实\r\";i:185;s:10:\"赵正永\r\";i:186;s:10:\"赵克石\r\";i:187;s:10:\"赵克志\r\";i:188;s:10:\"赵宗岐\r\";i:189;s:10:\"胡泽君\r\";i:190;s:10:\"姜大明\r\";i:191;s:10:\"姜异康\r\";i:192;s:10:\"骆惠宁\r\";i:193;s:10:\"秦光荣\r\";i:194;s:10:\"袁纯清\r\";i:195;s:10:\"袁贵仁\r\";i:196;s:10:\"耿惠昌\r\";i:197;s:10:\"聂卫国\r\";i:198;s:10:\"贾廷安\r\";i:199;s:10:\"夏宝龙\r\";i:200;s:7:\"铁凝\r\";i:201;s:10:\"徐守盛\r\";i:202;s:10:\"徐绍史\r\";i:203;s:10:\"徐粉林\r\";i:204;s:10:\"高虎城\r\";i:205;s:10:\"郭声琨\r\";i:206;s:10:\"郭庚茂\r\";i:207;s:10:\"郭树清\r\";i:208;s:10:\"黄兴国\r\";i:209;s:10:\"黄奇帆\r\";i:210;s:10:\"黄树贤\r\";i:211;s:10:\"曹建明\r\";i:212;s:10:\"戚建国\r\";i:213;s:10:\"常万全\r\";i:214;s:10:\"鹿心社\r\";i:215;s:7:\"彭勇\r\";i:216;s:10:\"彭清华\r\";i:217;s:10:\"蒋定之\r\";i:218;s:10:\"蒋建国\r\";i:219;s:10:\"蒋洁敏\r\";i:220;s:10:\"韩长赋\r\";i:221;s:10:\"焦焕成\r\";i:222;s:10:\"谢伏瞻\r\";i:223;s:7:\"强卫\r\";i:224;s:10:\"楼继伟\r\";i:225;s:10:\"解振华\r\";i:226;s:10:\"褚益民\r\";i:227;s:7:\"蔡武\r\";i:228;s:10:\"蔡名照\r\";i:229;s:10:\"蔡英挺\r\";i:230;s:10:\"蔡赴朝\r\";i:231;s:10:\"雒树刚\r\";i:232;s:7:\"魏亮\r\";i:233;s:10:\"魏凤和\r\";i:234;s:10:\"白手套\r\";i:235;s:7:\"情妇\r\";i:236;s:10:\"被调查\r\";i:237;s:7:\"被查\r\";i:238;s:7:\"出事\r\";i:239;s:7:\"双规\r\";i:240;s:7:\"纪委\r\";i:241;s:7:\"被捕\r\";i:242;s:7:\"潜逃\r\";i:243;s:7:\"贪腐\r\";i:244;s:7:\"受贿\r\";i:245;s:7:\"贿赂\r\";i:246;s:7:\"渎职\r\";i:247;s:7:\"行贿\r\";i:248;s:7:\"境外\r\";i:249;s:7:\"权贵\r\";i:250;s:7:\"迫害\r\";i:251;s:7:\"兵变\r\";i:252;s:7:\"政变\r\";i:253;s:7:\"叛变\r\";i:254;s:13:\"真人娱乐\r\";i:255;s:19:\"真人视讯娱乐\r\";i:256;s:13:\"真钱赌博\r\";i:257;s:7:\"申博\r\";i:258;s:10:\"太阳城\r\";i:259;s:13:\"投注平台\r\";i:260;s:13:\"皇冠娱乐\r\";i:261;s:13:\"真钱娱乐\r\";i:262;s:13:\"皇冠足球\r\";i:263;s:13:\"体育投注\r\";i:264;s:7:\"葡京\r\";i:265;s:10:\"赌球网\r\";i:266;s:13:\"皇冠现金\r\";i:267;s:19:\"篮球比分直播\r\";i:268;s:10:\"百家乐\r\";i:269;s:19:\"彩票电话投注\r\";i:270;s:7:\"淫荡\r\";i:271;s:7:\"催眠\r\";i:272;s:7:\"春药\r\";i:273;s:7:\"好色\r\";i:274;s:7:\"妓女\r\";i:275;s:4:\"屄\r\";i:276;s:7:\"幼女\r\";i:277;s:13:\"应召女郎\r\";i:278;s:7:\"应招\r\";i:279;s:7:\"情网\r\";i:280;s:7:\"情色\r\";i:281;s:13:\"成人教育\r\";i:282;s:13:\"成人电影\r\";i:283;s:7:\"成人\r\";i:284;s:13:\"成人网站\r\";i:285;s:7:\"偷窥\r\";i:286;s:7:\"小穴\r\";i:287;s:7:\"换妻\r\";i:288;s:10:\"性伴侣\r\";i:289;s:10:\"性服务\r\";i:290;s:7:\"口交\r\";i:291;s:7:\"屁眼\r\";i:292;s:7:\"乳交\r\";i:293;s:7:\"肉棒\r\";i:294;s:7:\"阴户\r\";i:295;s:7:\"阴唇\r\";i:296;s:7:\"兽交\r\";i:297;s:7:\"犬交\r\";i:298;s:7:\"群交\r\";i:299;s:7:\"足交\r\";i:300;s:7:\"奶子\r\";i:301;s:7:\"泡友\r\";i:302;s:13:\"母子乱伦\r\";i:303;s:7:\"无码\r\";i:304;s:13:\"一夜激情\r\";i:305;s:7:\"嫩逼\r\";i:306;s:7:\"骚女\r\";i:307;s:7:\"厕奴\r\";i:308;s:13:\"有偿服务\r\";i:309;s:10:\"性伙伴\r\";i:310;s:7:\"幼交\r\";i:311;s:6:\"看JJ\r\";i:312;s:7:\"裸聊\r\";i:313;s:7:\"女奴\r\";i:314;s:7:\"男奴\r\";i:315;s:7:\"卖身\r\";i:316;s:13:\"女士服务\r\";i:317;s:7:\"口淫\r\";i:318;s:7:\"性息\r\";i:319;s:13:\"夫妻交换\r\";i:320;s:7:\"找女\r\";i:321;s:7:\"找男\r\";i:322;s:7:\"开苞\r\";i:323;s:7:\"性奴\r\";i:324;s:5:\"h漫\r\";i:325;s:5:\"h图\r\";i:326;s:16:\"电子解码器\r\";i:327;s:10:\"发帖机\r\";i:328;s:16:\"信息群发器\r\";i:329;s:13:\"代办证件\r\";i:330;s:7:\"办证\r\";i:331;s:13:\"各种代考\r\";i:332;s:7:\"提携\r\";i:333;s:13:\"电视解密\r\";i:334;s:13:\"电视共享\r\";i:335;s:7:\"监视\r\";i:336;s:7:\"监听\r\";i:337;s:7:\"针孔\r\";i:338;s:7:\"窃听\r\";i:339;s:13:\"隐型耳机\r\";i:340;s:10:\"群发器\r\";i:341;s:7:\"弓弩\r\";i:342;s:10:\"迷情粉\r\";i:343;s:10:\"三唑仑\r\";i:344;s:7:\"催情\r\";i:345;s:7:\"迷昏\r\";i:346;s:7:\"迷魂\r\";i:347;s:7:\"迷药\r\";i:348;s:7:\"迷幻\r\";i:349;s:7:\"迷情\r\";i:350;s:16:\"盐酸氯胺酮\r\";i:351;s:10:\"老虎机\r\";i:352;s:4:\"赌\r\";i:353;s:7:\"扑克\r\";i:354;s:10:\"六合彩\r\";i:355;s:7:\"彩票\r\";i:356;s:10:\"隐形笔\r\";i:357;s:10:\"仿真枪\r\";i:358;s:10:\"监听王\r\";i:359;s:16:\"考试作弊器\r\";i:360;s:16:\"无抵押贷款\r\";i:361;s:10:\"曲马多\r\";i:362;s:13:\"司法考试\r\";i:363;s:19:\"专业销售气枪\r\";i:364;s:19:\"喷雾昏迷类药\r\";i:365;s:10:\"电击棍\r\";i:366;s:19:\"出售各种气枪\r\";i:367;s:13:\"各式气枪\r\";i:368;s:13:\"高压气枪\r\";i:369;s:22:\"手槍猎槍麻醉槍\r\";i:370;s:16:\"盐酸羟亚胺\r\";i:371;s:16:\"手机切听器\r\";i:372;s:13:\"代考雅思\r\";i:373;s:19:\"成人高考助考\r\";i:374;s:22:\"手机监听定位器\r\";i:375;s:16:\"春晚节目单\r\";i:376;s:13:\"二类GHB水\r\";i:377;s:10:\"拍肩药\r\";i:378;s:13:\"军用手枪\r\";i:379;s:7:\"汽狗\r\";i:380;s:16:\"指纹打卡机\r\";i:381;s:10:\"指纹套\r\";i:382;s:16:\"出售套牌车\r\";i:383;s:13:\"代开发票\r\";i:384;s:19:\"出售一元硬币\r\";i:385;s:7:\"冰毒\r\";i:386;s:5:\"K粉\r\";i:387;s:7:\"麻古\r\";i:388;s:10:\"摇头丸\r\";i:389;s:16:\"无界浏览器\r\";i:390;s:25:\"大陆高干子弟名单\r\";i:391;s:10:\"法轮功\r\";i:392;s:10:\"转法轮\r\";i:393;s:7:\"法轮\r\";i:394;s:13:\"台湾轮盘\r\";i:395;s:10:\"沁园春\r\";i:396;s:10:\"自由门\r\";i:397;s:13:\"绿坝克星\r\";i:398;s:13:\"警花王菲\r\";i:399;s:13:\"人民公社\r\";i:400;s:10:\"大跃进\r\";i:401;s:10:\"大饥荒\r\";i:402;s:13:\"社会矛盾\r\";i:403;s:13:\"和谐社会\r\";i:404;s:13:\"社会和谐\r\";i:405;s:10:\"邓小平\r\";i:406;s:13:\"政府官员\r\";i:407;s:16:\"群体性事件\r\";i:408;s:13:\"群体事件\r\";i:409;s:13:\"社会不公\r\";i:410;s:13:\"报复社会\r\";i:411;s:13:\"社会报复\r\";i:412;s:4:\"gfw\r\";i:413;s:10:\"茉莉花\r\";i:414;s:10:\"做证件\r\";i:415;s:13:\"做原子弹\r\";i:416;s:10:\"做爱小\r\";i:417;s:7:\"唑仑\r\";i:418;s:13:\"作硝化甘\r\";i:419;s:13:\"作各种证\r\";i:420;s:10:\"作弊器\r\";i:421;s:13:\"左转是政\r\";i:422;s:10:\"尊爵粉\r\";i:423;s:10:\"醉乙醚\r\";i:424;s:10:\"醉迷药\r\";i:425;s:10:\"醉钢枪\r\";i:426;s:13:\"最牛公安\r\";i:427;s:13:\"足球玩法\r\";i:428;s:13:\"总会美女\r\";i:429;s:10:\"自由亚\r\";i:430;s:10:\"自由圣\r\";i:431;s:10:\"自慰用\r\";i:432;s:13:\"自己找枪\r\";i:433;s:10:\"字牌汽\r\";i:434;s:13:\"梓健特药\r\";i:435;s:10:\"资料泄\r\";i:436;s:10:\"资格證\r\";i:437;s:13:\"姿不对死\r\";i:438;s:10:\"着涛哥\r\";i:439;s:16:\"着护士的胸\r\";i:440;s:10:\"装消音\r\";i:441;s:10:\"装枪套\r\";i:442;s:10:\"装弹甲\r\";i:443;s:13:\"赚钱资料\r\";i:444;s:13:\"转是政府\r\";i:445;s:10:\"专业助\r\";i:446;s:13:\"专业代写\r\";i:447;s:10:\"专业代\r\";i:448;s:13:\"专业办理\r\";i:449;s:10:\"助考网\r\";i:450;s:7:\"助考\r\";i:451;s:13:\"住英国房\r\";i:452;s:10:\"主席忏\r\";i:453;s:10:\"昼将近\r\";i:454;s:13:\"宙最高法\r\";i:455;s:10:\"州三箭\r\";i:456;s:13:\"州大批贪\r\";i:457;s:10:\"州惨案\r\";i:458;s:10:\"众像羔\r\";i:459;s:13:\"种学历证\r\";i:460;s:13:\"种公务员\r\";i:461;s:13:\"中国不强\r\";i:462;s:10:\"中共黑\r\";i:463;s:13:\"中的班禅\r\";i:464;s:13:\"制作证件\r\";i:465;s:13:\"制证定金\r\";i:466;s:10:\"制手枪\r\";i:467;s:10:\"制服诱\r\";i:468;s:13:\"志不愿跟\r\";i:469;s:13:\"至国家高\r\";i:470;s:10:\"指纹膜\r\";i:471;s:13:\"指纹考勤\r\";i:472;s:10:\"殖器护\r\";i:473;s:10:\"植物冰\r\";i:474;s:7:\"證件\r\";i:475;s:10:\"政论区\r\";i:476;s:10:\"政府操\r\";i:477;s:13:\"证一次性\r\";i:478;s:10:\"证书办\r\";i:479;s:13:\"证生成器\r\";i:480;s:13:\"证件集团\r\";i:481;s:10:\"证件办\r\";i:482;s:13:\"证到付款\r\";i:483;s:13:\"震其国土\r\";i:484;s:16:\"震惊一个民\r\";i:485;s:13:\"真实资格\r\";i:486;s:13:\"真实文凭\r\";i:487;s:10:\"真善忍\r\";i:488;s:13:\"真钱投注\r\";i:489;s:13:\"真钱斗地\r\";i:490;s:13:\"侦探设备\r\";i:491;s:10:\"针刺死\r\";i:492;s:10:\"针刺事\r\";i:493;s:10:\"针刺伤\r\";i:494;s:10:\"针刺案\r\";i:495;s:16:\"找政法委副\r\";i:496;s:10:\"找援交\r\";i:497;s:10:\"找枪手\r\";i:498;s:10:\"张春桥\r\";i:499;s:10:\"炸药制\r\";i:500;s:10:\"炸药配\r\";i:501;s:13:\"炸药的制\r\";i:502;s:10:\"炸立交\r\";i:503;s:10:\"炸广州\r\";i:504;s:13:\"炸弹遥控\r\";i:505;s:10:\"炸弹教\r\";i:506;s:10:\"曾道人\r\";i:507;s:10:\"择油录\r\";i:508;s:7:\"武警\r\";i:509;s:7:\"警察\r\";i:510;s:10:\"遭到警\r\";i:511;s:7:\"便衣\r\";i:512;s:10:\"韵徐娘\r\";i:513;s:10:\"晕倒型\r\";i:514;s:10:\"袁腾飞\r\";i:515;s:10:\"原装弹\r\";i:516;s:13:\"一九五七\r\";i:517;s:5:\"1957\r\";i:518;s:7:\"血案\r\";i:519;s:7:\"凶杀\r\";i:520;s:7:\"砍杀\r\";i:521;s:10:\"发生砍\r\";i:522;s:7:\"惨案\r\";i:523;s:10:\"鸳鸯洗\r\";i:524;s:10:\"冤民大\r\";i:525;s:13:\"育部女官\r\";i:526;s:10:\"玉蒲团\r\";i:527;s:7:\"与狗\r\";i:528;s:7:\"愚民\r\";i:529;s:10:\"愚民同\r\";i:530;s:13:\"娱乐透视\r\";i:531;s:10:\"幼齿类\r\";i:532;s:13:\"右转是政\r\";i:533;s:13:\"有奶不一\r\";i:534;s:10:\"游精佑\r\";i:535;s:10:\"幽谷三\r\";i:536;s:10:\"用手枪\r\";i:537;s:7:\"咏妓\r\";i:538;s:10:\"婴儿命\r\";i:539;s:7:\"子弹\r\";i:540;s:13:\"隐形喷剂\r\";i:541;s:10:\"隐形耳\r\";i:542;s:7:\"淫穴\r\";i:543;s:7:\"淫水\r\";i:544;s:10:\"淫兽学\r\";i:545;s:7:\"淫兽\r\";i:546;s:10:\"淫騷妹\r\";i:547;s:7:\"淫肉\r\";i:548;s:10:\"淫情女\r\";i:549;s:10:\"淫魔舞\r\";i:550;s:7:\"陰戶\r\";i:551;s:7:\"陰道\r\";i:552;s:7:\"陰唇\r\";i:553;s:13:\"阴间来电\r\";i:554;s:10:\"关注组\r\";i:555;s:10:\"蚁力神\r\";i:556;s:10:\"遗情书\r\";i:557;s:13:\"一小撮别\r\";i:558;s:10:\"液体炸\r\";i:559;s:10:\"夜激情\r\";i:560;s:10:\"要泄了\r\";i:561;s:10:\"要射了\r\";i:562;s:13:\"要射精了\r\";i:563;s:10:\"要人权\r\";i:564;s:13:\"姚明进去\r\";i:565;s:10:\"恙虫病\r\";i:566;s:7:\"颜射\r\";i:567;s:10:\"盐酸曲\r\";i:568;s:10:\"言论罪\r\";i:569;s:13:\"言被劳教\r\";i:570;s:10:\"严晓玲\r\";i:571;s:10:\"烟感器\r\";i:572;s:13:\"丫与王益\r\";i:573;s:10:\"學生妹\r\";i:574;s:10:\"学位證\r\";i:575;s:10:\"学骚乱\r\";i:576;s:10:\"徐玉元\r\";i:577;s:10:\"胸主席\r\";i:578;s:13:\"性推广歌\r\";i:579;s:10:\"性福情\r\";i:580;s:10:\"性爱日\r\";i:581;s:10:\"幸运码\r\";i:582;s:10:\"姓忽悠\r\";i:583;s:10:\"型手枪\r\";i:584;s:13:\"形透视镜\r\";i:585;s:13:\"行长王益\r\";i:586;s:10:\"星上门\r\";i:587;s:13:\"兴中心幼\r\";i:588;s:13:\"信接收器\r\";i:589;s:13:\"信访专班\r\";i:590;s:10:\"新唐人\r\";i:591;s:10:\"新金瓶\r\";i:592;s:10:\"新疆限\r\";i:593;s:10:\"新疆叛\r\";i:594;s:10:\"新建户\r\";i:595;s:13:\"泄漏的内\r\";i:596;s:10:\"写两会\r\";i:597;s:10:\"协晃悠\r\";i:598;s:10:\"校骚乱\r\";i:599;s:10:\"硝化甘\r\";i:600;s:13:\"香港总彩\r\";i:601;s:13:\"香港一类\r\";i:602;s:13:\"香港马会\r\";i:603;s:13:\"香港论坛\r\";i:604;s:10:\"相自首\r\";i:605;s:10:\"陷害罪\r\";i:606;s:10:\"陷害案\r\";i:607;s:10:\"限制言\r\";i:608;s:13:\"线透视镜\r\";i:609;s:13:\"现金投注\r\";i:610;s:13:\"现大地震\r\";i:611;s:13:\"先烈纷纷\r\";i:612;s:10:\"喜贪赃\r\";i:613;s:10:\"洗澡死\r\";i:614;s:13:\"席指着护\r\";i:615;s:13:\"席临终前\r\";i:616;s:10:\"席复活\r\";i:617;s:10:\"习晋平\r\";i:618;s:10:\"习进平\r\";i:619;s:7:\"希脏\r\";i:620;s:13:\"西服进去\r\";i:621;s:10:\"西藏限\r\";i:622;s:10:\"雾型迷\r\";i:623;s:13:\"务员考试\r\";i:624;s:13:\"务员答案\r\";i:625;s:13:\"武警已增\r\";i:626;s:10:\"武警殴\r\";i:627;s:10:\"武警暴\r\";i:628;s:10:\"午夜极\r\";i:629;s:10:\"午夜电\r\";i:630;s:10:\"五套功\r\";i:631;s:10:\"无码专\r\";i:632;s:13:\"无耻语录\r\";i:633;s:10:\"乌蝇水\r\";i:634;s:13:\"我搞台独\r\";i:635;s:13:\"我的西域\r\";i:636;s:7:\"瓮安\r\";i:637;s:10:\"闻封锁\r\";i:638;s:13:\"闻被控制\r\";i:639;s:10:\"纹了毛\r\";i:640;s:7:\"文强\r\";i:641;s:10:\"文凭证\r\";i:642;s:10:\"瘟假饱\r\";i:643;s:10:\"瘟加饱\r\";i:644;s:10:\"溫家寶\r\";i:645;s:10:\"温影帝\r\";i:646;s:13:\"温切斯特\r\";i:647;s:10:\"温家堡\r\";i:648;s:13:\"谓的和谐\r\";i:649;s:10:\"委坐船\r\";i:650;s:10:\"维权谈\r\";i:651;s:10:\"维权人\r\";i:652;s:10:\"维权基\r\";i:653;s:10:\"维汉员\r\";i:654;s:13:\"围攻上海\r\";i:655;s:10:\"围攻警\r\";i:656;s:13:\"微型摄像\r\";i:657;s:10:\"网民诬\r\";i:658;s:13:\"网民获刑\r\";i:659;s:10:\"网民案\r\";i:660;s:10:\"王益案\r\";i:661;s:10:\"王立军\r\";i:662;s:13:\"万人骚动\r\";i:663;s:13:\"万能钥匙\r\";i:664;s:10:\"湾版假\r\";i:665;s:13:\"外围赌球\r\";i:666;s:13:\"外透视镜\r\";i:667;s:10:\"袜按摩\r\";i:668;s:10:\"瓦斯手\r\";i:669;s:10:\"脱衣艳\r\";i:670;s:10:\"推油按\r\";i:671;s:13:\"突破网路\r\";i:672;s:13:\"突破封锁\r\";i:673;s:10:\"秃鹰汽\r\";i:674;s:10:\"透视仪\r\";i:675;s:10:\"透视药\r\";i:676;s:13:\"透视眼镜\r\";i:677;s:10:\"透视器\r\";i:678;s:10:\"透视扑\r\";i:679;s:10:\"透视镜\r\";i:680;s:13:\"透视功能\r\";i:681;s:10:\"头双管\r\";i:682;s:10:\"偷偷贪\r\";i:683;s:10:\"偷听器\r\";i:684;s:10:\"偷肃贪\r\";i:685;s:10:\"偷電器\r\";i:686;s:13:\"通钢总经\r\";i:687;s:13:\"庭审直播\r\";i:688;s:10:\"庭保养\r\";i:689;s:10:\"田停工\r\";i:690;s:10:\"田田桑\r\";i:691;s:10:\"田罢工\r\";i:692;s:13:\"天推广歌\r\";i:693;s:13:\"天鹅之旅\r\";i:694;s:10:\"天朝特\r\";i:695;s:10:\"替人体\r\";i:696;s:7:\"替考\r\";i:697;s:13:\"体透视镜\r\";i:698;s:10:\"特上门\r\";i:699;s:7:\"特码\r\";i:700;s:10:\"特工资\r\";i:701;s:13:\"涛一样胡\r\";i:702;s:10:\"涛共产\r\";i:703;s:10:\"探测狗\r\";i:704;s:13:\"贪官也辛\r\";i:705;s:10:\"泰州幼\r\";i:706;s:13:\"泰兴镇中\r\";i:707;s:10:\"泰兴幼\r\";i:708;s:13:\"太王四神\r\";i:709;s:10:\"蹋纳税\r\";i:710;s:13:\"酸羟亚胺\r\";i:711;s:10:\"速取证\r\";i:712;s:10:\"速代办\r\";i:713;s:10:\"素女心\r\";i:714;s:13:\"诉讼集团\r\";i:715;s:13:\"苏家屯集\r\";i:716;s:10:\"四小码\r\";i:717;s:13:\"四大扯个\r\";i:718;s:10:\"四博会\r\";i:719;s:13:\"死要见毛\r\";i:720;s:13:\"死法分布\r\";i:721;s:10:\"司法黑\r\";i:722;s:13:\"司长期有\r\";i:723;s:10:\"丝足按\r\";i:724;s:10:\"丝袜网\r\";i:725;s:10:\"丝袜妹\r\";i:726;s:10:\"丝袜美\r\";i:727;s:10:\"丝袜恋\r\";i:728;s:10:\"丝袜保\r\";i:729;s:10:\"丝情侣\r\";i:730;s:10:\"丝护士\r\";i:731;s:10:\"水阎王\r\";i:732;s:10:\"双管平\r\";i:733;s:10:\"双管立\r\";i:734;s:10:\"术牌具\r\";i:735;s:10:\"书办理\r\";i:736;s:10:\"售左轮\r\";i:737;s:10:\"售子弹\r\";i:738;s:13:\"售一元硬\r\";i:739;s:10:\"售信用\r\";i:740;s:10:\"售五四\r\";i:741;s:10:\"售手枪\r\";i:742;s:10:\"售三棱\r\";i:743;s:10:\"售热武\r\";i:744;s:10:\"售枪支\r\";i:745;s:10:\"售冒名\r\";i:746;s:10:\"售麻醉\r\";i:747;s:10:\"售氯胺\r\";i:748;s:10:\"售猎枪\r\";i:749;s:10:\"售军用\r\";i:750;s:10:\"售健卫\r\";i:751;s:10:\"售假币\r\";i:752;s:10:\"售火药\r\";i:753;s:10:\"售虎头\r\";i:754;s:10:\"售狗子\r\";i:755;s:10:\"售防身\r\";i:756;s:13:\"售弹簧刀\r\";i:757;s:10:\"售单管\r\";i:758;s:10:\"售纯度\r\";i:759;s:10:\"售步枪\r\";i:760;s:13:\"守所死法\r\";i:761;s:7:\"手槍\r\";i:762;s:10:\"手木仓\r\";i:763;s:10:\"手拉鸡\r\";i:764;s:10:\"手机追\r\";i:765;s:10:\"手机窃\r\";i:766;s:10:\"手机监\r\";i:767;s:10:\"手机跟\r\";i:768;s:7:\"手狗\r\";i:769;s:10:\"手答案\r\";i:770;s:10:\"手变牌\r\";i:771;s:10:\"是躲猫\r\";i:772;s:10:\"视解密\r\";i:773;s:10:\"式粉推\r\";i:774;s:13:\"士康事件\r\";i:775;s:13:\"实学历文\r\";i:776;s:10:\"实体娃\r\";i:777;s:13:\"实毕业证\r\";i:778;s:13:\"十七大幕\r\";i:779;s:13:\"十类人不\r\";i:780;s:13:\"十个预言\r\";i:781;s:10:\"十大禁\r\";i:782;s:10:\"十大谎\r\";i:783;s:10:\"十八等\r\";i:784;s:10:\"狮子旗\r\";i:785;s:10:\"失意药\r\";i:786;s:10:\"失身水\r\";i:787;s:7:\"尸博\r\";i:788;s:13:\"盛行在舞\r\";i:789;s:13:\"圣战不息\r\";i:790;s:13:\"生肖中特\r\";i:791;s:7:\"踩踏\r\";i:792;s:7:\"被砍\r\";i:793;s:13:\"神韵艺术\r\";i:794;s:10:\"神七假\r\";i:795;s:10:\"深喉冰\r\";i:796;s:13:\"涉嫌抄袭\r\";i:797;s:10:\"射网枪\r\";i:798;s:10:\"韶关旭\r\";i:799;s:10:\"韶关玩\r\";i:800;s:10:\"韶关斗\r\";i:801;s:10:\"烧瓶的\r\";i:802;s:10:\"公安局\r\";i:803;s:10:\"上门激\r\";i:804;s:13:\"煽动群众\r\";i:805;s:13:\"煽动不明\r\";i:806;s:10:\"山涉黑\r\";i:807;s:10:\"杀指南\r\";i:808;s:10:\"色小说\r\";i:809;s:10:\"色视频\r\";i:810;s:10:\"色妹妹\r\";i:811;s:10:\"色电影\r\";i:812;s:13:\"扫了爷爷\r\";i:813;s:7:\"骚嘴\r\";i:814;s:7:\"骚穴\r\";i:815;s:7:\"骚浪\r\";i:816;s:7:\"骚妇\r\";i:817;s:7:\"三唑\r\";i:818;s:10:\"三网友\r\";i:819;s:10:\"三秒倒\r\";i:820;s:7:\"三挫\r\";i:821;s:10:\"赛后骚\r\";i:822;s:13:\"软弱的国\r\";i:823;s:10:\"如厕死\r\";i:824;s:7:\"肉棍\r\";i:825;s:7:\"肉洞\r\";i:826;s:10:\"柔胸粉\r\";i:827;s:13:\"任于斯国\r\";i:828;s:10:\"认牌绝\r\";i:829;s:10:\"人真钱\r\";i:830;s:13:\"人在云上\r\";i:831;s:10:\"人游行\r\";i:832;s:10:\"人体艺\r\";i:833;s:10:\"人权律\r\";i:834;s:10:\"惹的国\r\";i:835;s:13:\"绕过封锁\r\";i:836;s:13:\"群体性事\r\";i:837;s:13:\"群起抗暴\r\";i:838;s:10:\"群奸暴\r\";i:839;s:10:\"全真证\r\";i:840;s:10:\"娶韩国\r\";i:841;s:13:\"区的雷人\r\";i:842;s:10:\"琼花问\r\";i:843;s:7:\"请愿\r\";i:844;s:10:\"请示威\r\";i:845;s:10:\"请集会\r\";i:846;s:10:\"氰化钠\r\";i:847;s:10:\"氰化钾\r\";i:848;s:10:\"情自拍\r\";i:849;s:10:\"情视频\r\";i:850;s:10:\"情妹妹\r\";i:851;s:13:\"情聊天室\r\";i:852;s:10:\"清純壆\r\";i:853;s:13:\"清除负面\r\";i:854;s:10:\"氢弹手\r\";i:855;s:10:\"勤捞致\r\";i:856;s:13:\"禽流感了\r\";i:857;s:10:\"窃听器\r\";i:858;s:10:\"切听器\r\";i:859;s:13:\"抢其火炬\r\";i:860;s:13:\"强硬发言\r\";i:861;s:13:\"强权政府\r\";i:862;s:10:\"枪子弹\r\";i:863;s:10:\"枪械制\r\";i:864;s:10:\"枪销售\r\";i:865;s:10:\"枪手网\r\";i:866;s:10:\"枪手队\r\";i:867;s:7:\"枪模\r\";i:868;s:13:\"枪决现场\r\";i:869;s:13:\"枪决女犯\r\";i:870;s:10:\"枪货到\r\";i:871;s:10:\"枪的制\r\";i:872;s:10:\"枪的结\r\";i:873;s:10:\"枪的分\r\";i:874;s:10:\"枪的参\r\";i:875;s:10:\"枪出售\r\";i:876;s:13:\"钱三字经\r\";i:877;s:7:\"铅弹\r\";i:878;s:7:\"氣槍\r\";i:879;s:7:\"汽枪\r\";i:880;s:7:\"气枪\r\";i:881;s:7:\"气狗\r\";i:882;s:13:\"骑单车出\r\";i:883;s:10:\"奇淫散\r\";i:884;s:13:\"奇迹的黄\r\";i:885;s:10:\"期货配\r\";i:886;s:10:\"普通嘌\r\";i:887;s:13:\"仆不怕饮\r\";i:888;s:13:\"平叫到床\r\";i:889;s:10:\"平惨案\r\";i:890;s:7:\"嫖鸡\r\";i:891;s:10:\"嫖俄罗\r\";i:892;s:7:\"喷尿\r\";i:893;s:10:\"配有消\r\";i:894;s:10:\"陪考枪\r\";i:895;s:13:\"炮的小蜜\r\";i:896;s:10:\"牌技网\r\";i:897;s:10:\"牌分析\r\";i:898;s:10:\"拍肩型\r\";i:899;s:13:\"拍肩神药\r\";i:900;s:10:\"鸥之歌\r\";i:901;s:10:\"女上门\r\";i:902;s:13:\"女任职名\r\";i:903;s:13:\"女人和狗\r\";i:904;s:10:\"女技师\r\";i:905;s:10:\"女激情\r\";i:906;s:16:\"女被人家搞\r\";i:907;s:13:\"怒的志愿\r\";i:908;s:7:\"浓精\r\";i:909;s:10:\"妞上门\r\";i:910;s:16:\"娘两腿之间\r\";i:911;s:10:\"拟涛哥\r\";i:912;s:13:\"你的西域\r\";i:913;s:13:\"泥马之歌\r\";i:914;s:7:\"嫩阴\r\";i:915;s:7:\"嫩穴\r\";i:916;s:10:\"南充针\r\";i:917;s:7:\"内射\r\";i:918;s:10:\"幕前戲\r\";i:919;s:13:\"幕没有不\r\";i:920;s:10:\"木齐针\r\";i:921;s:10:\"母乳家\r\";i:922;s:10:\"摩小姐\r\";i:923;s:13:\"铭记印尼\r\";i:924;s:10:\"明慧网\r\";i:925;s:10:\"民抗议\r\";i:926;s:13:\"民九亿商\r\";i:927;s:10:\"民储害\r\";i:928;s:10:\"灭绝罪\r\";i:929;s:7:\"蜜穴\r\";i:930;s:10:\"谜奸药\r\";i:931;s:7:\"迷藥\r\";i:932;s:10:\"迷情药\r\";i:933;s:10:\"迷情水\r\";i:934;s:10:\"迷奸药\r\";i:935;s:10:\"迷魂藥\r\";i:936;s:10:\"迷魂药\r\";i:937;s:10:\"迷魂香\r\";i:938;s:10:\"迷昏藥\r\";i:939;s:10:\"迷昏药\r\";i:940;s:10:\"迷昏口\r\";i:941;s:10:\"迷幻藥\r\";i:942;s:10:\"迷幻药\r\";i:943;s:10:\"迷幻型\r\";i:944;s:10:\"蒙汗药\r\";i:945;s:10:\"氓培训\r\";i:946;s:10:\"門服務\r\";i:947;s:10:\"门保健\r\";i:948;s:10:\"门按摩\r\";i:949;s:10:\"妹上门\r\";i:950;s:10:\"妹按摩\r\";i:951;s:13:\"美艳少妇\r\";i:952;s:13:\"每周一死\r\";i:953;s:13:\"媒体封锁\r\";i:954;s:10:\"毛一鲜\r\";i:955;s:13:\"猫眼工具\r\";i:956;s:10:\"忙爱国\r\";i:957;s:10:\"漫步丝\r\";i:958;s:10:\"卖自考\r\";i:959;s:13:\"卖银行卡\r\";i:960;s:10:\"卖发票\r\";i:961;s:13:\"卖地财政\r\";i:962;s:13:\"蟆叫专家\r\";i:963;s:10:\"麻醉藥\r\";i:964;s:10:\"麻醉槍\r\";i:965;s:10:\"麻醉枪\r\";i:966;s:10:\"麻醉狗\r\";i:967;s:10:\"麻将透\r\";i:968;s:10:\"麻果丸\r\";i:969;s:10:\"麻果配\r\";i:970;s:10:\"落霞缀\r\";i:971;s:10:\"裸舞视\r\";i:972;s:10:\"裸聊网\r\";i:973;s:13:\"罗斯小姐\r\";i:974;s:10:\"论文代\r\";i:975;s:10:\"轮手枪\r\";i:976;s:7:\"轮功\r\";i:977;s:10:\"伦理片\r\";i:978;s:10:\"伦理毛\r\";i:979;s:13:\"伦理电影\r\";i:980;s:10:\"伦理大\r\";i:981;s:7:\"亂倫\r\";i:982;s:10:\"乱伦小\r\";i:983;s:10:\"乱伦类\r\";i:984;s:7:\"乱奸\r\";i:985;s:10:\"氯胺酮\r\";i:986;s:10:\"陆同修\r\";i:987;s:10:\"陆封锁\r\";i:988;s:10:\"隆手指\r\";i:989;s:13:\"龙湾事件\r\";i:990;s:13:\"六月联盟\r\";i:991;s:10:\"六四事\r\";i:992;s:7:\"六死\r\";i:993;s:10:\"流血事\r\";i:994;s:10:\"领土拿\r\";i:995;s:7:\"獵槍\r\";i:996;s:7:\"猎槍\r\";i:997;s:10:\"猎枪销\r\";i:998;s:13:\"猎好帮手\r\";i:999;s:13:\"了件渔袍\r\";i:1000;s:10:\"聊斋艳\r\";i:1001;s:10:\"聊视频\r\";i:1002;s:13:\"两会又三\r\";i:1003;s:10:\"两会代\r\";i:1004;s:13:\"两岸才子\r\";i:1005;s:10:\"炼大法\r\";i:1006;s:10:\"聯繫電\r\";i:1007;s:10:\"连发手\r\";i:1008;s:10:\"利他林\r\";i:1009;s:10:\"丽媛离\r\";i:1010;s:10:\"力月西\r\";i:1011;s:13:\"力骗中央\r\";i:1012;s:13:\"理做帐报\r\";i:1013;s:10:\"理证件\r\";i:1014;s:13:\"理是影帝\r\";i:1015;s:13:\"理各种证\r\";i:1016;s:10:\"李咏曰\r\";i:1017;s:10:\"李洪志\r\";i:1018;s:10:\"黎阳平\r\";i:1019;s:13:\"类准确答\r\";i:1020;s:13:\"雷人女官\r\";i:1021;s:7:\"浪穴\r\";i:1022;s:13:\"狼全部跪\r\";i:1023;s:10:\"拦截器\r\";i:1024;s:10:\"来福猎\r\";i:1025;s:13:\"拉开水晶\r\";i:1026;s:10:\"拉登说\r\";i:1027;s:13:\"矿难不公\r\";i:1028;s:10:\"快速办\r\";i:1029;s:10:\"骷髅死\r\";i:1030;s:10:\"口手枪\r\";i:1031;s:10:\"控制媒\r\";i:1032;s:13:\"控诉世博\r\";i:1033;s:10:\"孔摄像\r\";i:1034;s:13:\"空和雅典\r\";i:1035;s:10:\"克透视\r\";i:1036;s:10:\"克千术\r\";i:1037;s:10:\"克分析\r\";i:1038;s:7:\"磕彰\r\";i:1039;s:13:\"考中答案\r\";i:1040;s:13:\"考研考中\r\";i:1041;s:10:\"考试枪\r\";i:1042;s:13:\"考试联盟\r\";i:1043;s:13:\"考试机构\r\";i:1044;s:13:\"考试答案\r\";i:1045;s:10:\"考试保\r\";i:1046;s:13:\"考试包过\r\";i:1047;s:10:\"考设备\r\";i:1048;s:10:\"考前付\r\";i:1049;s:13:\"考前答案\r\";i:1050;s:10:\"考前答\r\";i:1051;s:10:\"考联盟\r\";i:1052;s:10:\"考考邓\r\";i:1053;s:10:\"考机构\r\";i:1054;s:13:\"考后付款\r\";i:1055;s:10:\"考答案\r\";i:1056;s:10:\"康跳楼\r\";i:1057;s:13:\"康没有不\r\";i:1058;s:10:\"砍伤儿\r\";i:1059;s:10:\"砍杀幼\r\";i:1060;s:7:\"開票\r\";i:1061;s:7:\"開碼\r\";i:1062;s:13:\"开锁工具\r\";i:1063;s:10:\"开邓选\r\";i:1064;s:10:\"军用手\r\";i:1065;s:10:\"军品特\r\";i:1066;s:7:\"军刺\r\";i:1067;s:13:\"军长发威\r\";i:1068;s:10:\"绝食声\r\";i:1069;s:13:\"据说全民\r\";i:1070;s:10:\"举国体\r\";i:1071;s:10:\"就要色\r\";i:1072;s:10:\"就爱插\r\";i:1073;s:13:\"酒像喝汤\r\";i:1074;s:13:\"酒象喝汤\r\";i:1075;s:10:\"九评共\r\";i:1076;s:13:\"九龙论坛\r\";i:1077;s:13:\"究生答案\r\";i:1078;s:10:\"敬请忍\r\";i:1079;s:10:\"径步枪\r\";i:1080;s:10:\"警用品\r\";i:1081;s:13:\"警方包庇\r\";i:1082;s:13:\"警车雷达\r\";i:1083;s:13:\"警察说保\r\";i:1084;s:13:\"警察殴打\r\";i:1085;s:13:\"警察的幌\r\";i:1086;s:10:\"警察被\r\";i:1087;s:13:\"精子射在\r\";i:1088;s:13:\"经典谎言\r\";i:1089;s:13:\"京要地震\r\";i:1090;s:10:\"京地震\r\";i:1091;s:13:\"进来的罪\r\";i:1092;s:10:\"津地震\r\";i:1093;s:13:\"津大地震\r\";i:1094;s:10:\"金钟气\r\";i:1095;s:10:\"金扎金\r\";i:1096;s:10:\"姐上门\r\";i:1097;s:10:\"姐兼职\r\";i:1098;s:10:\"姐服务\r\";i:1099;s:10:\"姐包夜\r\";i:1100;s:10:\"揭贪难\r\";i:1101;s:10:\"叫自慰\r\";i:1102;s:10:\"蒋彦永\r\";i:1103;s:7:\"疆獨\r\";i:1104;s:10:\"江贼民\r\";i:1105;s:10:\"江系人\r\";i:1106;s:10:\"江太上\r\";i:1107;s:13:\"江胡内斗\r\";i:1108;s:10:\"简易炸\r\";i:1109;s:10:\"监听器\r\";i:1110;s:13:\"兼职上门\r\";i:1111;s:10:\"奸成瘾\r\";i:1112;s:10:\"甲流了\r\";i:1113;s:10:\"甲虫跳\r\";i:1114;s:13:\"家属被打\r\";i:1115;s:13:\"家一样饱\r\";i:1116;s:13:\"佳静安定\r\";i:1117;s:10:\"擠乳汁\r\";i:1118;s:10:\"挤乳汁\r\";i:1119;s:10:\"集体腐\r\";i:1120;s:13:\"集体打砸\r\";i:1121;s:10:\"急需嫖\r\";i:1122;s:10:\"级答案\r\";i:1123;s:10:\"级办理\r\";i:1124;s:10:\"激情炮\r\";i:1125;s:10:\"激情妹\r\";i:1126;s:10:\"激情短\r\";i:1127;s:10:\"激情电\r\";i:1128;s:13:\"绩过后付\r\";i:1129;s:13:\"基本靠吼\r\";i:1130;s:13:\"机屏蔽器\r\";i:1131;s:10:\"机卡密\r\";i:1132;s:10:\"机号卫\r\";i:1133;s:10:\"机号定\r\";i:1134;s:13:\"机定位器\r\";i:1135;s:13:\"火车也疯\r\";i:1136;s:10:\"活不起\r\";i:1137;s:13:\"浑圆豪乳\r\";i:1138;s:7:\"黄冰\r\";i:1139;s:13:\"皇冠投注\r\";i:1140;s:13:\"环球证件\r\";i:1141;s:13:\"还看锦涛\r\";i:1142;s:13:\"还会吹萧\r\";i:1143;s:10:\"划老公\r\";i:1144;s:13:\"化学扫盲\r\";i:1145;s:10:\"华门开\r\";i:1146;s:10:\"华国锋\r\";i:1147;s:10:\"虎头猎\r\";i:1148;s:10:\"湖淫娘\r\";i:1149;s:10:\"胡适眼\r\";i:1150;s:10:\"胡錦濤\r\";i:1151;s:10:\"胡紧套\r\";i:1152;s:13:\"胡江内斗\r\";i:1153;s:10:\"紅色恐\r\";i:1154;s:13:\"红外透视\r\";i:1155;s:13:\"红色恐怖\r\";i:1156;s:13:\"黑火药的\r\";i:1157;s:10:\"和狗做\r\";i:1158;s:10:\"和狗性\r\";i:1159;s:10:\"和狗交\r\";i:1160;s:13:\"号屏蔽器\r\";i:1161;s:10:\"豪圈钱\r\";i:1162;s:10:\"海访民\r\";i:1163;s:13:\"哈药直销\r\";i:1164;s:10:\"國內美\r\";i:1165;s:16:\"国一九五七\r\";i:1166;s:10:\"国库折\r\";i:1167;s:13:\"国家吞得\r\";i:1168;s:13:\"国家软弱\r\";i:1169;s:10:\"国家妓\r\";i:1170;s:13:\"国际投注\r\";i:1171;s:13:\"滚圆大乳\r\";i:1172;s:10:\"跪真相\r\";i:1173;s:13:\"光学真题\r\";i:1174;s:13:\"官因发帖\r\";i:1175;s:13:\"官也不容\r\";i:1176;s:10:\"官商勾\r\";i:1177;s:10:\"乖乖粉\r\";i:1178;s:13:\"鼓动一些\r\";i:1179;s:13:\"狗屁专家\r\";i:1180;s:10:\"共王储\r\";i:1181;s:7:\"共狗\r\";i:1182;s:13:\"攻官小姐\r\";i:1183;s:13:\"公开小姐\r\";i:1184;s:13:\"公安网监\r\";i:1185;s:13:\"公安错打\r\";i:1186;s:10:\"工力人\r\";i:1187;s:13:\"工程吞得\r\";i:1188;s:10:\"跟踪器\r\";i:1189;s:13:\"各类文凭\r\";i:1190;s:13:\"各类考试\r\";i:1191;s:13:\"格证考试\r\";i:1192;s:10:\"告洋状\r\";i:1193;s:10:\"告长期\r\";i:1194;s:10:\"搞媛交\r\";i:1195;s:10:\"高莺莺\r\";i:1196;s:10:\"高考黑\r\";i:1197;s:13:\"高就在政\r\";i:1198;s:10:\"港鑫華\r\";i:1199;s:10:\"港馬會\r\";i:1200;s:13:\"港澳博球\r\";i:1201;s:10:\"钢珠枪\r\";i:1202;s:10:\"钢针狗\r\";i:1203;s:10:\"岡本真\r\";i:1204;s:13:\"肛门是邻\r\";i:1205;s:7:\"肛交\r\";i:1206;s:10:\"冈本真\r\";i:1207;s:10:\"感扑克\r\";i:1208;s:13:\"改号软件\r\";i:1209;s:13:\"富婆给废\r\";i:1210;s:10:\"富民穷\r\";i:1211;s:13:\"复印件制\r\";i:1212;s:13:\"复印件生\r\";i:1213;s:10:\"附送枪\r\";i:1214;s:10:\"妇销魂\r\";i:1215;s:13:\"府集中领\r\";i:1216;s:10:\"府包庇\r\";i:1217;s:10:\"福香巴\r\";i:1218;s:13:\"福娃頭上\r\";i:1219;s:13:\"福娃的預\r\";i:1220;s:13:\"福尔马林\r\";i:1221;s:10:\"佛同修\r\";i:1222;s:10:\"封锁消\r\";i:1223;s:10:\"费私服\r\";i:1224;s:10:\"诽谤罪\r\";i:1225;s:10:\"仿真证\r\";i:1226;s:13:\"房贷给废\r\";i:1227;s:13:\"防身药水\r\";i:1228;s:13:\"防电子眼\r\";i:1229;s:10:\"方迷香\r\";i:1230;s:10:\"范燕琼\r\";i:1231;s:10:\"反屏蔽\r\";i:1232;s:13:\"反雷达测\r\";i:1233;s:13:\"反测速雷\r\";i:1234;s:10:\"法正乾\r\";i:1235;s:13:\"法院给废\r\";i:1236;s:10:\"法一轮\r\";i:1237;s:10:\"法维权\r\";i:1238;s:10:\"法轮佛\r\";i:1239;s:10:\"法伦功\r\";i:1240;s:10:\"法车仑\r\";i:1241;s:7:\"發票\r\";i:1242;s:10:\"发票销\r\";i:1243;s:10:\"发票代\r\";i:1244;s:10:\"发票出\r\";i:1245;s:10:\"发牌绝\r\";i:1246;s:10:\"二奶大\r\";i:1247;s:10:\"儿园凶\r\";i:1248;s:10:\"儿园杀\r\";i:1249;s:10:\"儿园砍\r\";i:1250;s:10:\"儿园惨\r\";i:1251;s:10:\"恩氟烷\r\";i:1252;s:13:\"恶势力插\r\";i:1253;s:13:\"恶势力操\r\";i:1254;s:10:\"俄羅斯\r\";i:1255;s:10:\"躲猫猫\r\";i:1256;s:10:\"多美康\r\";i:1257;s:13:\"对日强硬\r\";i:1258;s:10:\"短信截\r\";i:1259;s:13:\"独立台湾\r\";i:1260;s:10:\"毒蛇钻\r\";i:1261;s:13:\"都进中央\r\";i:1262;s:13:\"都当小姐\r\";i:1263;s:10:\"都当警\r\";i:1264;s:13:\"洞小口紧\r\";i:1265;s:10:\"東京熱\r\";i:1266;s:10:\"东京热\r\";i:1267;s:10:\"东复活\r\";i:1268;s:13:\"东北独立\r\";i:1269;s:10:\"顶花心\r\";i:1270;s:10:\"丁子霖\r\";i:1271;s:10:\"丁香社\r\";i:1272;s:10:\"蝶舞按\r\";i:1273;s:10:\"甸果敢\r\";i:1274;s:7:\"电鸡\r\";i:1275;s:10:\"电话监\r\";i:1276;s:7:\"电狗\r\";i:1277;s:13:\"点数优惠\r\";i:1278;s:10:\"递纸死\r\";i:1279;s:13:\"帝国之梦\r\";i:1280;s:10:\"地震哥\r\";i:1281;s:13:\"地下先烈\r\";i:1282;s:13:\"地产之歌\r\";i:1283;s:10:\"邓玉娇\r\";i:1284;s:13:\"邓爷爷转\r\";i:1285;s:13:\"等人手术\r\";i:1286;s:13:\"等人是老\r\";i:1287;s:13:\"等人老百\r\";i:1288;s:10:\"等屁民\r\";i:1289;s:10:\"等级證\r\";i:1290;s:10:\"灯草和\r\";i:1291;s:10:\"的同修\r\";i:1292;s:10:\"得财兼\r\";i:1293;s:10:\"到花心\r\";i:1294;s:10:\"导小商\r\";i:1295;s:10:\"导人最\r\";i:1296;s:13:\"导人的最\r\";i:1297;s:10:\"导叫失\r\";i:1298;s:13:\"导的情人\r\";i:1299;s:13:\"刀架保安\r\";i:1300;s:13:\"党前干劲\r\";i:1301;s:10:\"党后萎\r\";i:1302;s:10:\"党的官\r\";i:1303;s:4:\"党\r\";i:1304;s:13:\"当官在于\r\";i:1305;s:13:\"当官要精\r\";i:1306;s:13:\"当代七整\r\";i:1307;s:10:\"戴海静\r\";i:1308;s:7:\"贷开\r\";i:1309;s:10:\"贷借款\r\";i:1310;s:7:\"贷办\r\";i:1311;s:10:\"代写论\r\";i:1312;s:10:\"代写毕\r\";i:1313;s:10:\"代您考\r\";i:1314;s:13:\"代理票据\r\";i:1315;s:13:\"代理发票\r\";i:1316;s:7:\"代考\r\";i:1317;s:7:\"代開\r\";i:1318;s:10:\"代表烦\r\";i:1319;s:7:\"代辦\r\";i:1320;s:10:\"代办制\r\";i:1321;s:10:\"代办学\r\";i:1322;s:10:\"代办文\r\";i:1323;s:10:\"代办各\r\";i:1324;s:13:\"代办发票\r\";i:1325;s:10:\"大嘴歌\r\";i:1326;s:10:\"大肉棒\r\";i:1327;s:13:\"大批贪官\r\";i:1328;s:10:\"大奶子\r\";i:1329;s:10:\"大揭露\r\";i:1330;s:10:\"大纪元\r\";i:1331;s:10:\"大雞巴\r\";i:1332;s:10:\"大鸡巴\r\";i:1333;s:13:\"打砸办公\r\";i:1334;s:10:\"打死人\r\";i:1335;s:13:\"打死经过\r\";i:1336;s:13:\"打飞机专\r\";i:1337;s:10:\"打错门\r\";i:1338;s:10:\"打标语\r\";i:1339;s:13:\"答案提供\r\";i:1340;s:10:\"答案包\r\";i:1341;s:13:\"达毕业证\r\";i:1342;s:7:\"挫仑\r\";i:1343;s:10:\"催情藥\r\";i:1344;s:10:\"催情药\r\";i:1345;s:10:\"催情粉\r\";i:1346;s:10:\"催眠水\r\";i:1347;s:13:\"次通过考\r\";i:1348;s:10:\"纯度黄\r\";i:1349;s:10:\"纯度白\r\";i:1350;s:13:\"春水横溢\r\";i:1351;s:13:\"穿透仪器\r\";i:1352;s:10:\"出售军\r\";i:1353;s:13:\"出售发票\r\";i:1354;s:13:\"出成绩付\r\";i:1355;s:13:\"抽着芙蓉\r\";i:1356;s:13:\"抽着大中\r\";i:1357;s:10:\"冲凉死\r\";i:1358;s:10:\"充气娃\r\";i:1359;s:10:\"惩贪难\r\";i:1360;s:10:\"惩公安\r\";i:1361;s:10:\"城管灭\r\";i:1362;s:10:\"成人小\r\";i:1363;s:10:\"成人文\r\";i:1364;s:10:\"成人图\r\";i:1365;s:10:\"成人视\r\";i:1366;s:10:\"成人片\r\";i:1367;s:10:\"成人聊\r\";i:1368;s:13:\"成人卡通\r\";i:1369;s:10:\"成人电\r\";i:1370;s:10:\"车牌隐\r\";i:1371;s:10:\"拆迁灭\r\";i:1372;s:10:\"察象蚂\r\";i:1373;s:10:\"插屁屁\r\";i:1374;s:13:\"策没有不\r\";i:1375;s:10:\"操嫂子\r\";i:1376;s:10:\"操了嫂\r\";i:1377;s:7:\"藏獨\r\";i:1378;s:10:\"藏春阁\r\";i:1379;s:10:\"苍蝇水\r\";i:1380;s:10:\"苍山兰\r\";i:1381;s:10:\"踩踏事\r\";i:1382;s:10:\"采花堂\r\";i:1383;s:13:\"财众科技\r\";i:1384;s:16:\"才知道只生\r\";i:1385;s:13:\"部是这样\r\";i:1386;s:13:\"部忙组阁\r\";i:1387;s:13:\"布卖淫女\r\";i:1388;s:13:\"不思四化\r\";i:1389;s:10:\"不查全\r\";i:1390;s:10:\"不查都\r\";i:1391;s:13:\"博园区伪\r\";i:1392;s:13:\"博会暂停\r\";i:1393;s:10:\"博彩娱\r\";i:1394;s:10:\"波推龙\r\";i:1395;s:13:\"冰在火上\r\";i:1396;s:10:\"冰淫传\r\";i:1397;s:10:\"冰火漫\r\";i:1398;s:13:\"冰火九重\r\";i:1399;s:10:\"冰火佳\r\";i:1400;s:10:\"冰火毒\r\";i:1401;s:13:\"辩词与梦\r\";i:1402;s:10:\"变牌绝\r\";i:1403;s:10:\"毕业證\r\";i:1404;s:10:\"本无码\r\";i:1405;s:13:\"本公司担\r\";i:1406;s:10:\"被中共\r\";i:1407;s:13:\"被指抄袭\r\";i:1408;s:10:\"被打死\r\";i:1409;s:13:\"北省委门\r\";i:1410;s:10:\"爆发骚\r\";i:1411;s:13:\"报复执法\r\";i:1412;s:13:\"保过答案\r\";i:1413;s:16:\"宝在甘肃修\r\";i:1414;s:13:\"磅遥控器\r\";i:1415;s:13:\"磅解码器\r\";i:1416;s:13:\"谤罪获刑\r\";i:1417;s:7:\"辦證\r\";i:1418;s:10:\"辦毕业\r\";i:1419;s:10:\"半刺刀\r\";i:1420;s:7:\"办怔\r\";i:1421;s:10:\"办文凭\r\";i:1422;s:13:\"办理资格\r\";i:1423;s:13:\"办理证书\r\";i:1424;s:13:\"办理真实\r\";i:1425;s:13:\"办理文凭\r\";i:1426;s:13:\"办理票据\r\";i:1427;s:13:\"办理各种\r\";i:1428;s:13:\"办理本科\r\";i:1429;s:10:\"办本科\r\";i:1430;s:10:\"败培训\r\";i:1431;s:13:\"白黄牙签\r\";i:1432;s:10:\"罢工门\r\";i:1433;s:13:\"把学生整\r\";i:1434;s:13:\"把邓小平\r\";i:1435;s:13:\"把病人整\r\";i:1436;s:13:\"八九政治\r\";i:1437;s:10:\"八九学\r\";i:1438;s:10:\"八九民\r\";i:1439;s:13:\"案的准确\r\";i:1440;s:10:\"安眠藥\r\";i:1441;s:10:\"安门事\r\";i:1442;s:13:\"安局豪华\r\";i:1443;s:16:\"安局办公楼\r\";i:1444;s:10:\"安街逆\r\";i:1445;s:13:\"爱液横流\r\";i:1446;s:13:\"挨了一炮\r\";i:1447;s:7:\"阿賓\r\";i:1448;s:7:\"阿宾\r\";i:1449;s:13:\"阿扁推翻\r\";i:1450;s:7:\"局长\r\";i:1451;s:7:\"处长\r\";i:1452;s:7:\"警方\r\";i:1453;s:10:\"杨受成\r\";i:1454;s:13:\"水工帮主\r\";i:1455;s:10:\"李大鸟\r\";i:1456;s:10:\"乔老爷\r\";i:1457;s:10:\"小木匠\r\";i:1458;s:10:\"朱太公\r\";i:1459;s:10:\"刘元帅\r\";i:1460;s:12:\"古月老大\";}s:6:\"filter\";a:1:{i:0;s:0:\"\";}}');

/*Table structure for table `icms_favorite` */

DROP TABLE IF EXISTS `icms_favorite`;

CREATE TABLE `icms_favorite` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `nickname` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `follow` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关注数',
  `count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '收藏数',
  `mode` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1 公开 0私密',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


/*Table structure for table `icms_favorite_data` */

DROP TABLE IF EXISTS `icms_favorite_data`;

CREATE TABLE `icms_favorite_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '收藏者ID',
  `appid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '应用ID',
  `fid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '收藏夹ID',
  `iid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '内容ID',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '内容URL',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '内容标题',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx` (`uid`,`fid`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `icms_favorite_follow` */

DROP TABLE IF EXISTS `icms_favorite_follow`;

CREATE TABLE `icms_favorite_follow` (
  `fid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '收藏夹ID',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '关注者',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '收藏夹标题',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '关注者ID',
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `icms_favorite_follow` */

/*Table structure for table `icms_file_data` */

DROP TABLE IF EXISTS `icms_file_data`;

CREATE TABLE `icms_file_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `filename` varchar(255) NOT NULL DEFAULT '',
  `ofilename` varchar(255) NOT NULL DEFAULT '',
  `path` varchar(255) NOT NULL DEFAULT '',
  `intro` varchar(255) NOT NULL DEFAULT '',
  `ext` varchar(10) NOT NULL DEFAULT '',
  `size` int(10) unsigned NOT NULL DEFAULT '0',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ext` (`ext`),
  KEY `path` (`path`),
  KEY `ofilename` (`ofilename`),
  KEY `fn_userid` (`filename`,`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `icms_file_map` */

DROP TABLE IF EXISTS `icms_file_map`;

CREATE TABLE `icms_file_map` (
  `fileid` int(10) unsigned NOT NULL,
  `userid` int(10) unsigned NOT NULL,
  `appid` int(10) unsigned NOT NULL,
  `indexid` int(10) unsigned NOT NULL,
  `addtimes` int(10) unsigned NOT NULL,
  PRIMARY KEY (`fileid`,`appid`,`indexid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `icms_file_map` */

/*Table structure for table `icms_group` */

DROP TABLE IF EXISTS `icms_group`;

CREATE TABLE `icms_group` (
  `gid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `sortnum` int(10) unsigned NOT NULL DEFAULT '0',
  `power` mediumtext NOT NULL,
  `cpower` mediumtext NOT NULL,
  `type` enum('1','0') NOT NULL DEFAULT '0',
  PRIMARY KEY (`gid`),
  KEY `type` (`type`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `icms_group` */

insert  into `icms_group`(`gid`,`name`,`sortnum`,`power`,`cpower`,`type`) values
  (1,'超级管理员',1,'','','1'),
  (2,'编辑',2,'','','1'),
  (3,'会员',1,'','','0');

/*Table structure for table `icms_keywords` */

DROP TABLE IF EXISTS `icms_keywords`;

CREATE TABLE `icms_keywords` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `keyword` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `times` smallint(6) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`,`keyword`),
  UNIQUE KEY `keyword` (`keyword`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `icms_keywords` */

/*Table structure for table `icms_links` */

DROP TABLE IF EXISTS `icms_links`;

CREATE TABLE `icms_links` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `logo` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `desc` text NOT NULL,
  `sortnum` int(10) unsigned NOT NULL DEFAULT '0',
  UNIQUE KEY `id` (`id`),
  KEY `s_o_id` (`cid`,`sortnum`,`id`),
  KEY `ordernum` (`sortnum`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `icms_links` */

/*Table structure for table `icms_marker` */

DROP TABLE IF EXISTS `icms_marker`;

CREATE TABLE `icms_marker` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(10) unsigned NOT NULL DEFAULT '0',
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `key` varchar(255) NOT NULL DEFAULT '',
  `data` mediumtext NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `marker` (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `icms_marker` */

/*Table structure for table `icms_members` */

DROP TABLE IF EXISTS `icms_members`;

CREATE TABLE `icms_members` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `username` varchar(50) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `nickname` varchar(255) NOT NULL DEFAULT '',
  `realname` varchar(255) NOT NULL DEFAULT '',
  `gender` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `info` mediumtext NOT NULL,
  `power` mediumtext NOT NULL,
  `cpower` mediumtext NOT NULL,
  `regtime` int(10) unsigned DEFAULT '0',
  `lastip` varchar(15) NOT NULL DEFAULT '',
  `lastlogintime` int(10) unsigned NOT NULL DEFAULT '0',
  `logintimes` smallint(5) unsigned NOT NULL DEFAULT '0',
  `post` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`),
  KEY `username` (`username`),
  KEY `groupid` (`gid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `icms_members` */

insert  into `icms_members`(`uid`,`gid`,`username`,`password`,`nickname`,`realname`,`gender`,`info`,`power`,`cpower`,`regtime`,`lastip`,`lastlogintime`,`logintimes`,`post`,`type`,`status`) values
  (1,1,'admin','e10adc3949ba59abbe56e057f20f883e','iCMS','',0,'','','',0,'127.0.0.1',1488111587,264,0,1,1);


/*Table structure for table `icms_message` */

DROP TABLE IF EXISTS `icms_message`;

CREATE TABLE `icms_message` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '发送者ID',
  `friend` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '接收者ID',
  `send_uid` int(10) DEFAULT '0' COMMENT '发送者ID',
  `send_name` varchar(255) NOT NULL DEFAULT '' COMMENT '发送者名称',
  `receiv_uid` int(10) DEFAULT '0' COMMENT '接收者ID',
  `receiv_name` varchar(255) NOT NULL DEFAULT '' COMMENT '接收者名称',
  `content` text NOT NULL COMMENT '内容',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '信息类型',
  `sendtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '发送时间',
  `readtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '读取时间',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '信息状态 参考程序注释',
  PRIMARY KEY (`id`),
  KEY `idx` (`status`,`userid`,`friend`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


/*Table structure for table `icms_prop` */

DROP TABLE IF EXISTS `icms_prop`;

CREATE TABLE `icms_prop` (
  `pid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rootid` int(10) unsigned NOT NULL,
  `cid` int(10) unsigned NOT NULL DEFAULT '0',
  `field` varchar(255) NOT NULL DEFAULT '',
  `appid` int(10) unsigned NOT NULL DEFAULT '0',
  `app` varchar(255) NOT NULL DEFAULT '',
  `sortnum` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `val` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`pid`),
  KEY `field` (`field`),
  KEY `cid` (`cid`),
  KEY `type` (`app`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `icms_prop` */

insert  into `icms_prop`(`pid`,`rootid`,`cid`,`field`,`appid`,`app`,`sortnum`,`name`,`val`) values
  (1,0,0,'pid',0,'',0,'头条','1'),
  (2,0,0,'pid',0,'',0,'首页推荐','2'),
  (3,0,0,'pid',0,'',0,'推荐栏目','1'),
  (4,0,0,'pid',0,'',0,'热门标签','1'),
  (5,0,0,'pid',0,'',0,'推荐用户','1');

/*Table structure for table `icms_prop_map` */

DROP TABLE IF EXISTS `icms_prop_map`;

CREATE TABLE `icms_prop_map` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `node` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'prop id',
  `iid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '内容ID',
  `appid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'iCMS.define.php',
  PRIMARY KEY (`id`),
  KEY `idx` (`appid`,`node`,`iid`)
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;


/*Table structure for table `icms_push` */

DROP TABLE IF EXISTS `icms_push`;

CREATE TABLE `icms_push` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sortnum` int(10) unsigned NOT NULL DEFAULT '0',
  `cid` int(10) unsigned NOT NULL DEFAULT '0',
  `rootid` int(10) unsigned NOT NULL DEFAULT '0',
  `pid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `haspic` tinyint(1) NOT NULL DEFAULT '0',
  `editor` varchar(100) NOT NULL DEFAULT '',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `pic` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `title2` varchar(255) NOT NULL DEFAULT '',
  `pic2` varchar(255) NOT NULL DEFAULT '',
  `url2` varchar(255) NOT NULL DEFAULT '',
  `description2` text NOT NULL,
  `title3` varchar(255) NOT NULL DEFAULT '',
  `pic3` varchar(255) NOT NULL DEFAULT '',
  `url3` varchar(255) NOT NULL DEFAULT '',
  `description3` text NOT NULL,
  `metadata` mediumtext NOT NULL,
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `pid_order` (`pid`,`sortnum`),
  KEY `pid_id` (`pid`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `icms_push` */

/*Table structure for table `icms_search_log` */

DROP TABLE IF EXISTS `icms_search_log`;

CREATE TABLE `icms_search_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `search` varchar(200) NOT NULL DEFAULT '',
  `times` int(10) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `search_times` (`search`,`times`),
  KEY `search_id` (`search`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `icms_sph_counter` */

DROP TABLE IF EXISTS `icms_sph_counter`;

CREATE TABLE `icms_sph_counter` (
  `counter_id` int(11) NOT NULL,
  `max_doc_id` int(11) NOT NULL,
  PRIMARY KEY (`counter_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `icms_sph_counter` */

/*Table structure for table `icms_spider_post` */

DROP TABLE IF EXISTS `icms_spider_post`;

CREATE TABLE `icms_spider_post` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `app` varchar(255) NOT NULL DEFAULT '',
  `post` text NOT NULL,
  `fun` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `icms_spider_post` */

insert  into `icms_spider_post`(`id`,`name`,`app`,`post`,`fun`) values
  (1,'直接发布','article','status=1\r\npostype=1\r\nremote=true\r\nautopic=true','do_save'),
  (2,'采集到草稿','article','status=0\r\npostype=1\r\nremote=true\r\nautopic=true','do_save');
  (3,'采集到草稿 不采图','article','status=0\r\npostype=1','do_save');

/*Table structure for table `icms_spider_project` */

DROP TABLE IF EXISTS `icms_spider_project`;

CREATE TABLE `icms_spider_project` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `urls` text NOT NULL,
  `list_url` varchar(255) NOT NULL,
  `cid` int(10) unsigned NOT NULL,
  `rid` int(10) unsigned NOT NULL,
  `poid` int(10) unsigned NOT NULL,
  `sleep` int(10) unsigned NOT NULL,
  `checker` tinyint(1) unsigned NOT NULL,
  `self` tinyint(1) unsigned NOT NULL,
  `auto` tinyint(1) unsigned NOT NULL,
  `lastupdate` int(10) unsigned NOT NULL,
  `psleep` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


/*Table structure for table `icms_spider_rule` */

DROP TABLE IF EXISTS `icms_spider_rule`;

CREATE TABLE `icms_spider_rule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `rule` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


/*Table structure for table `icms_spider_url` */

DROP TABLE IF EXISTS `icms_spider_url`;

CREATE TABLE `icms_spider_url` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(10) unsigned NOT NULL,
  `pid` int(10) unsigned NOT NULL,
  `rid` int(10) unsigned NOT NULL,
  `indexid` int(10) NOT NULL,
  `hash` char(32) NOT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(500) NOT NULL,
  `publish` tinyint(1) NOT NULL,
  `addtime` int(10) NOT NULL,
  `pubdate` int(10) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `hash` (`hash`),
  KEY `title` (`title`),
  KEY `url` (`url`(255))
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Da
/*Table structure for table `icms_tags` */

DROP TABLE IF EXISTS `icms_tags`;

CREATE TABLE `icms_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `rootid` int(10) unsigned NOT NULL DEFAULT '0',
  `cid` int(10) unsigned NOT NULL DEFAULT '0',
  `tcid` varchar(255) NOT NULL DEFAULT '',
  `pid` varchar(255) NOT NULL DEFAULT '',
  `tkey` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `seotitle` varchar(255) NOT NULL DEFAULT '',
  `subtitle` varchar(255) NOT NULL DEFAULT '',
  `keywords` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `haspic` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `pic` varchar(255) NOT NULL DEFAULT '',
  `bpic` varchar(255) NOT NULL DEFAULT '',
  `mpic` varchar(255) NOT NULL DEFAULT '',
  `spic` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `related` varchar(255) NOT NULL DEFAULT '',
  `comments` int(10) unsigned NOT NULL DEFAULT '0',
  `count` int(10) unsigned NOT NULL DEFAULT '0',
  `weight` smallint(6) NOT NULL DEFAULT '0',
  `tpl` varchar(255) NOT NULL DEFAULT '',
  `sortnum` int(10) unsigned NOT NULL DEFAULT '0',
  `pubdate` int(10) unsigned NOT NULL DEFAULT '0',
  `postime` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`,`id`),
  KEY `idx_order` (`status`,`sortnum`),
  KEY `name` (`name`),
  KEY `tkey` (`tkey`),
  KEY `idx_count` (`status`,`count`),
  KEY `pid_count` (`pid`,`count`),
  KEY `cid_count` (`cid`,`count`),
  KEY `pid_id` (`pid`,`id`),
  KEY `cid_id` (`cid`,`id`),
  KEY `rootid` (`rootid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


/*Table structure for table `icms_tags_map` */

DROP TABLE IF EXISTS `icms_tags_map`;

CREATE TABLE `icms_tags_map` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `node` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '标签ID',
  `iid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '内容ID',
  `appid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '应用ID',
  PRIMARY KEY (`id`),
  KEY `tid_index` (`appid`,`node`,`iid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


/*Table structure for table `icms_user` */

DROP TABLE IF EXISTS `icms_user`;

CREATE TABLE `icms_user` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gid` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '用户组ID',
  `pid` varchar(255) NOT NULL DEFAULT '' COMMENT '属性ID',
  `username` varchar(255) NOT NULL DEFAULT '' COMMENT '用户名/email',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '昵称',
  `password` char(32) NOT NULL DEFAULT '' COMMENT '密码',
  `gender` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '性别',
  `fans` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '粉丝数',
  `follow` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关注数',
  `comments` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '评论数',
  `article` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文章数',
  `share` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享数',
  `credit` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '积分',
  `regip` varchar(20) NOT NULL DEFAULT '' COMMENT '注册IP',
  `regdate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册日期',
  `lastloginip` varchar(20) NOT NULL DEFAULT '' COMMENT '最后登陆IP',
  `lastlogintime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登陆时间',
  `hits` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '总点击数',
  `hits_today` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '当天点击数',
  `hits_yday` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '昨天点击数',
  `hits_week` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '周点击',
  `hits_month` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '月点击',
  `setting` varchar(1024) NOT NULL DEFAULT '' COMMENT '其它设置',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '用户类型',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '账号状态',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username` (`nickname`),
  KEY `email` (`username`),
  KEY `nickname` (`nickname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `icms_user_category` */

DROP TABLE IF EXISTS `icms_user_category`;

CREATE TABLE `icms_user_category` (
  `cid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `count` int(10) unsigned NOT NULL DEFAULT '0',
  `mode` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1 公开 2私密',
  `appid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`cid`),
  KEY `uid` (`uid`,`appid`,`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


/*Table structure for table `icms_user_data` */

DROP TABLE IF EXISTS `icms_user_data`;

CREATE TABLE `icms_user_data` (
  `uid` int(11) unsigned NOT NULL,
  `realname` varchar(255) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `mobile` varchar(255) NOT NULL DEFAULT '' COMMENT '联系电话',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT '街道地址',
  `province` varchar(255) NOT NULL DEFAULT '' COMMENT '省份',
  `city` varchar(255) NOT NULL DEFAULT '' COMMENT '城市',
  `year` varchar(255) NOT NULL DEFAULT '' COMMENT '生日-年',
  `month` varchar(255) NOT NULL DEFAULT '' COMMENT '生日-月',
  `day` varchar(255) NOT NULL DEFAULT '' COMMENT '生日-日',
  `constellation` varchar(255) NOT NULL DEFAULT '' COMMENT '星座',
  `profession` varchar(255) NOT NULL DEFAULT '' COMMENT '职业',
  `personstyle` varchar(255) NOT NULL DEFAULT '' COMMENT '个人标签',
  `slogan` varchar(512) NOT NULL DEFAULT '' COMMENT '自我介绍',
  `unickEdit` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '昵称修改次数',
  `meta` text NOT NULL COMMENT '其它数据',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `icms_user_follow` */

DROP TABLE IF EXISTS `icms_user_follow`;

CREATE TABLE `icms_user_follow` (
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '关注者ID',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '关注者',
  `fuid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '被关注者ID',
  `fname` varchar(255) NOT NULL DEFAULT '' COMMENT '被关注者',
  KEY `uid` (`uid`,`fuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `icms_user_openid` */

DROP TABLE IF EXISTS `icms_user_openid`;

CREATE TABLE `icms_user_openid` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `openid` varchar(255) NOT NULL,
  `platform` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1:wx,2:qq,3:wb,4:tb',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `icms_user_openid` */

/*Table structure for table `icms_user_report` */

DROP TABLE IF EXISTS `icms_user_report`;

CREATE TABLE `icms_user_report` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `appid` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '应用ID',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '举报者',
  `iid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '内容ID',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '被举报者',
  `reason` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `content` varchar(255) NOT NULL DEFAULT '',
  `ip` varchar(20) NOT NULL DEFAULT '',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;


/*Table structure for table `icms_weixin_api_log` */

DROP TABLE IF EXISTS `icms_weixin_api_log`;

CREATE TABLE `icms_weixin_api_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ToUserName` varchar(255) NOT NULL DEFAULT '',
  `FromUserName` varchar(255) NOT NULL DEFAULT '',
  `CreateTime` int(11) NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  `dayline` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
