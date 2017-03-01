/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `#iCMS@__apps` */

DROP TABLE IF EXISTS `#iCMS@__apps`;

CREATE TABLE `#iCMS@__apps` (
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

/*Data for the table `#iCMS@__apps` */


/*Table structure for table `#iCMS@__config` */

DROP TABLE IF EXISTS `#iCMS@__config`;

CREATE TABLE `#iCMS@__config` (
  `appid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `value` mediumtext NOT NULL,
  PRIMARY KEY (`appid`,`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#iCMS@__config` */

/*Table structure for table `#iCMS@__article` */

DROP TABLE IF EXISTS `#iCMS@__article`;

CREATE TABLE `#iCMS@__article` (
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

/*Data for the table `#iCMS@__article` */


/*Table structure for table `#iCMS@__article_data` */

DROP TABLE IF EXISTS `#iCMS@__article_data`;

CREATE TABLE `#iCMS@__article_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `aid` int(10) unsigned NOT NULL DEFAULT '0',
  `subtitle` varchar(255) NOT NULL DEFAULT '',
  `body` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `aid` (`aid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#iCMS@__article_data` */


/*Table structure for table `#iCMS@__category` */

DROP TABLE IF EXISTS `#iCMS@__category`;

CREATE TABLE `#iCMS@__category` (
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

/*Data for the table `#iCMS@__category` */


/*Table structure for table `#iCMS@__category_map` */

DROP TABLE IF EXISTS `#iCMS@__category_map`;

CREATE TABLE `#iCMS@__category_map` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `node` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'category cid',
  `iid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '内容ID',
  `appid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '应用ID',
  PRIMARY KEY (`id`),
  KEY `idx` (`appid`,`node`,`iid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#iCMS@__category_map` */


/*Table structure for table `#iCMS@__comment` */

DROP TABLE IF EXISTS `#iCMS@__comment`;

CREATE TABLE `#iCMS@__comment` (
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

/*Data for the table `#iCMS@__comment` */



/*Table structure for table `#iCMS@__favorite` */

DROP TABLE IF EXISTS `#iCMS@__favorite`;

CREATE TABLE `#iCMS@__favorite` (
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

/*Data for the table `#iCMS@__favorite` */


/*Table structure for table `#iCMS@__favorite_data` */

DROP TABLE IF EXISTS `#iCMS@__favorite_data`;

CREATE TABLE `#iCMS@__favorite_data` (
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

/*Data for the table `#iCMS@__favorite_data` */


/*Table structure for table `#iCMS@__favorite_follow` */

DROP TABLE IF EXISTS `#iCMS@__favorite_follow`;

CREATE TABLE `#iCMS@__favorite_follow` (
  `fid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '收藏夹ID',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '关注者',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '收藏夹标题',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '关注者ID',
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#iCMS@__favorite_follow` */


/*Table structure for table `#iCMS@__file_data` */

DROP TABLE IF EXISTS `#iCMS@__file_data`;

CREATE TABLE `#iCMS@__file_data` (
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

/*Data for the table `#iCMS@__file_data` */


/*Table structure for table `#iCMS@__file_map` */

DROP TABLE IF EXISTS `#iCMS@__file_map`;

CREATE TABLE `#iCMS@__file_map` (
  `fileid` int(10) unsigned NOT NULL,
  `userid` int(10) unsigned NOT NULL,
  `appid` int(10) unsigned NOT NULL,
  `indexid` int(10) unsigned NOT NULL,
  `addtimes` int(10) unsigned NOT NULL,
  PRIMARY KEY (`fileid`,`appid`,`indexid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#iCMS@__file_map` */


/*Table structure for table `#iCMS@__group` */

DROP TABLE IF EXISTS `#iCMS@__group`;

CREATE TABLE `#iCMS@__group` (
  `gid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `sortnum` int(10) unsigned NOT NULL DEFAULT '0',
  `power` mediumtext NOT NULL,
  `cpower` mediumtext NOT NULL,
  `type` enum('1','0') NOT NULL DEFAULT '0',
  PRIMARY KEY (`gid`),
  KEY `type` (`type`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `#iCMS@__group` */


/*Table structure for table `#iCMS@__keywords` */

DROP TABLE IF EXISTS `#iCMS@__keywords`;

CREATE TABLE `#iCMS@__keywords` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `keyword` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `times` smallint(6) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`,`keyword`),
  UNIQUE KEY `keyword` (`keyword`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#iCMS@__keywords` */


/*Table structure for table `#iCMS@__links` */

DROP TABLE IF EXISTS `#iCMS@__links`;

CREATE TABLE `#iCMS@__links` (
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

/*Data for the table `#iCMS@__links` */


/*Table structure for table `#iCMS@__marker` */

DROP TABLE IF EXISTS `#iCMS@__marker`;

CREATE TABLE `#iCMS@__marker` (
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

/*Data for the table `#iCMS@__marker` */


/*Table structure for table `#iCMS@__members` */

DROP TABLE IF EXISTS `#iCMS@__members`;

CREATE TABLE `#iCMS@__members` (
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

/*Data for the table `#iCMS@__members` */


/*Table structure for table `#iCMS@__message` */

DROP TABLE IF EXISTS `#iCMS@__message`;

CREATE TABLE `#iCMS@__message` (
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

/*Data for the table `#iCMS@__message` */


/*Table structure for table `#iCMS@__prop` */

DROP TABLE IF EXISTS `#iCMS@__prop`;

CREATE TABLE `#iCMS@__prop` (
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

/*Data for the table `#iCMS@__prop` */


/*Table structure for table `#iCMS@__prop_map` */

DROP TABLE IF EXISTS `#iCMS@__prop_map`;

CREATE TABLE `#iCMS@__prop_map` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `node` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'prop id',
  `iid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '内容ID',
  `appid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'iCMS.define.php',
  PRIMARY KEY (`id`),
  KEY `idx` (`appid`,`node`,`iid`)
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;

/*Data for the table `#iCMS@__prop_map` */


/*Table structure for table `#iCMS@__push` */

DROP TABLE IF EXISTS `#iCMS@__push`;

CREATE TABLE `#iCMS@__push` (
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

/*Data for the table `#iCMS@__push` */


/*Table structure for table `#iCMS@__search_log` */

DROP TABLE IF EXISTS `#iCMS@__search_log`;

CREATE TABLE `#iCMS@__search_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `search` varchar(200) NOT NULL DEFAULT '',
  `times` int(10) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `search_times` (`search`,`times`),
  KEY `search_id` (`search`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#iCMS@__search_log` */


/*Table structure for table `#iCMS@__sph_counter` */

DROP TABLE IF EXISTS `#iCMS@__sph_counter`;

CREATE TABLE `#iCMS@__sph_counter` (
  `counter_id` int(11) NOT NULL,
  `max_doc_id` int(11) NOT NULL,
  PRIMARY KEY (`counter_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#iCMS@__sph_counter` */


/*Table structure for table `#iCMS@__spider_post` */

DROP TABLE IF EXISTS `#iCMS@__spider_post`;

CREATE TABLE `#iCMS@__spider_post` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `app` varchar(255) NOT NULL DEFAULT '',
  `post` text NOT NULL,
  `fun` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `#iCMS@__spider_post` */


/*Table structure for table `#iCMS@__spider_project` */

DROP TABLE IF EXISTS `#iCMS@__spider_project`;

CREATE TABLE `#iCMS@__spider_project` (
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

/*Data for the table `#iCMS@__spider_project` */


/*Table structure for table `#iCMS@__spider_rule` */

DROP TABLE IF EXISTS `#iCMS@__spider_rule`;

CREATE TABLE `#iCMS@__spider_rule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `rule` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#iCMS@__spider_rule` */


/*Table structure for table `#iCMS@__spider_url` */

DROP TABLE IF EXISTS `#iCMS@__spider_url`;

CREATE TABLE `#iCMS@__spider_url` (
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

/*Data for the table `#iCMS@__spider_url` */


/*Table structure for table `#iCMS@__tags` */

DROP TABLE IF EXISTS `#iCMS@__tags`;

CREATE TABLE `#iCMS@__tags` (
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

/*Data for the table `#iCMS@__tags` */


/*Table structure for table `#iCMS@__tags_map` */

DROP TABLE IF EXISTS `#iCMS@__tags_map`;

CREATE TABLE `#iCMS@__tags_map` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `node` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '标签ID',
  `iid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '内容ID',
  `appid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '应用ID',
  PRIMARY KEY (`id`),
  KEY `tid_index` (`appid`,`node`,`iid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#iCMS@__tags_map` */


/*Table structure for table `#iCMS@__user` */

DROP TABLE IF EXISTS `#iCMS@__user`;

CREATE TABLE `#iCMS@__user` (
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

/*Data for the table `#iCMS@__user` */


/*Table structure for table `#iCMS@__user_category` */

DROP TABLE IF EXISTS `#iCMS@__user_category`;

CREATE TABLE `#iCMS@__user_category` (
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

/*Data for the table `#iCMS@__user_category` */


/*Table structure for table `#iCMS@__user_data` */

DROP TABLE IF EXISTS `#iCMS@__user_data`;

CREATE TABLE `#iCMS@__user_data` (
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

/*Data for the table `#iCMS@__user_data` */


/*Table structure for table `#iCMS@__user_follow` */

DROP TABLE IF EXISTS `#iCMS@__user_follow`;

CREATE TABLE `#iCMS@__user_follow` (
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '关注者ID',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '关注者',
  `fuid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '被关注者ID',
  `fname` varchar(255) NOT NULL DEFAULT '' COMMENT '被关注者',
  KEY `uid` (`uid`,`fuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#iCMS@__user_follow` */


/*Table structure for table `#iCMS@__user_openid` */

DROP TABLE IF EXISTS `#iCMS@__user_openid`;

CREATE TABLE `#iCMS@__user_openid` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `openid` varchar(255) NOT NULL,
  `platform` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1:wx,2:qq,3:wb,4:tb',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#iCMS@__user_openid` */


/*Table structure for table `#iCMS@__user_report` */

DROP TABLE IF EXISTS `#iCMS@__user_report`;

CREATE TABLE `#iCMS@__user_report` (
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

/*Data for the table `#iCMS@__user_report` */


/*Table structure for table `#iCMS@__weixin_api_log` */

DROP TABLE IF EXISTS `#iCMS@__weixin_api_log`;

CREATE TABLE `#iCMS@__weixin_api_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ToUserName` varchar(255) NOT NULL DEFAULT '',
  `FromUserName` varchar(255) NOT NULL DEFAULT '',
  `CreateTime` int(11) NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  `dayline` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#iCMS@__weixin_api_log` */

/*Table structure for table `#iCMS@__weixin_event` */

DROP TABLE IF EXISTS `#iCMS@__weixin_event`;

CREATE TABLE `#iCMS@__weixin_event` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `eventype` varchar(255) NOT NULL DEFAULT '' COMMENT '事件类型',
  `eventkey` varchar(255) NOT NULL DEFAULT '' COMMENT '事件KEY值/关键词',
  `msgtype` varchar(255) NOT NULL DEFAULT '' COMMENT '回复类型',
  `operator` varchar(10) NOT NULL DEFAULT '' COMMENT '匹配模式',
  `msg` mediumtext NOT NULL COMMENT '消息内容包含格式',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

INSERT  INTO `#iCMS@__apps`(`id`,`app`,`name`,`apptype`,`type`,`table`,`config`,`fields`,`addtimes`,`status`) values
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
  (11,'software','软件',0,1,'{\"software\":[\"software\",\"id\",\"\",\"\\u4fe1\\u606f\"],\"software_data\":[\"software_data\",\"sid\",\"\",\"\\u56fe\\u7247\\/\\u4ecb\\u7ecd\"],\"software_list\":[\"software_list\",\"sid\",\"\",\"\\u5217\\u8868\"]}','{\"info\":\"\\u8f6f\\u4ef6\\u7cfb\\u7edf\",\"template\":[\"iCMS:software:list\",\"iCMS:software:items\",\"iCMS:software:search\",\"$software\"],\"router\":\"1\",\"menu\":\"main\"}','',1488366650,1),
  (12,'keywords','内链',0,2,'[[\"keywords\",\"id\",\"\\u5185\\u94fe\"]]','{\"info\":\"\\u5185\\u94fe\\u7cfb\\u7edf\",\"menu\":\"main\"}','',1482587894,1),
  (13,'links','友情链接',0,1,'[[\"links\",\"id\",\"\\u53cb\\u60c5\\u94fe\\u63a5\"]]','{\"info\":\"\\u53cb\\u60c5\\u94fe\\u63a5\\u7a0b\\u5e8f\",\"template\":[\"iCMS:links:list\"],\"menu\":\"main\"}','',1482587722,1),
  (14,'marker','标记',0,1,'[[\"marker\",\"id\",\"\\u6807\\u8bb0\"]]','{\"iFormer\":\"1\",\"info\":\"\\u6807\\u8bb0\\u7ba1\\u7406\\u7cfb\\u7edf\",\"template\":[\"iCMS:marker:html\"],\"menu\":\"main\"}','',1482587726,1),
  (15,'search','搜索',0,1,'[[\"search_log\",\"id\",\"\\u641c\\u7d22\\u8bb0\\u5f55\"]]','{\"info\":\"\\u6587\\u7ae0\\u641c\\u7d22\\u7cfb\\u7edf\",\"template\":[\"iCMS:search:list\",\"iCMS:search:url\",\"$search\"],\"menu\":\"main\"}','',1482587729,1),
  (16,'public','公共',0,1,'0','{\"info\":\"\\u516c\\u5171\\u901a\\u7528\\u6807\\u7b7e\",\"template\":[\"iCMS:public:ui\",\"iCMS:public:seccode\",\"iCMS:public:crontab\",\"iCMS:public:qrcode\"],\"menu\":\"main\",\"admincp\":\"null\"}','',1483236548,1),
  (17,'database','数据库管理',0,1,'0','{\"info\":\"\\u540e\\u53f0\\u7b80\\u6613\\u6570\\u636e\\u5e93\\u7ba1\\u7406\",\"menu\":\"main\",\"admincp\":\"database&do=backup\"}','',1482587932,1),
  (18,'html','静态系统',0,1,'0','{\"info\":\"\\u9759\\u6001\\u6587\\u4ef6\\u751f\\u6210\\u7a0b\\u5e8f\",\"menu\":\"main\",\"admincp\":\"html&do=index\"}','',1482588133,1),
  (19,'index','首页系统',0,1,'0','{\"info\":\"\\u9996\\u9875\\u7a0b\\u5e8f\",\"menu\":\"main\",\"admincp\":\"null\"}','',1482588076,1),
  (20,'admincp','后台系统',0,0,'0','{\"info\":\"\\u57fa\\u7840\\u7ba1\\u7406\\u7cfb\\u7edf\",\"menu\":\"main\",\"admincp\":\"__SELF__\"}','',1482587926,1),
  (21,'apps','应用管理',0,0,'[[\"apps\",\"id\",\"\\u5e94\\u7528\"]]','{\"info\":\"\\u5e94\\u7528\\u7ba1\\u7406\",\"menu\":\"main\"}','',1482588934,1),
  (22,'group','角色系统',0,0,'[[\"group\",\"gid\",\"\\u89d2\\u8272\"]]','{\"info\":\"\\u89d2\\u8272\\u6743\\u9650\\u7cfb\\u7edf\",\"menu\":\"main\"}','',1482623597,1),
  (23,'config','系统配置',0,0,'[[\"config\",\"appid\",\"\\u7cfb\\u7edf\\u914d\\u7f6e\"]]','{\"info\":\"\\u7cfb\\u7edf\\u914d\\u7f6e\",\"menu\":\"main\"}','',1482626798,1),
  (24,'members','管理员',0,0,'[[\"members\",\"uid\",\"\\u7ba1\\u7406\\u5458\"]]','{\"info\":\"\\u7ba1\\u7406\\u5458\\u7cfb\\u7edf\",\"menu\":\"main\"}','',1482623563,1),
  (25,'files','文件管理',0,0,'[[\"file_data\",\"id\",\"\\u6587\\u4ef6\"],[\"file_map\",\"fileid\",\"fileid\",\"\\u6587\\u4ef6\\u6620\\u5c04\"]]','{\"info\":\"\\u6587\\u4ef6\\u7ba1\\u7406\\u7cfb\\u7edf\",\"menu\":\"main\"}','',1482623525,1),
  (26,'menu','后台菜单',0,0,'0','{\"info\":\"\\u540e\\u53f0\\u83dc\\u5355\\u7ba1\\u7406\",\"menu\":\"main\"}','',1482728434,1),
  (27,'editor','后台编辑器',0,0,'0','{\"info\":\"\\u540e\\u53f0\\u7f16\\u8f91\\u5668\",\"menu\":\"main\"}','',1482728399,1),
  (28,'patch','升级程序',0,0,'0','{\"info\":\"\\u7528\\u4e8e\\u5347\\u7ea7\\u7cfb\\u7edf\",\"menu\":\"main\"}','',1482728309,1),
  (29,'template','模板管理',0,0,'0','{\"info\":\"\\u6a21\\u677f\\u7ba1\\u7406\",\"menu\":\"main\"}','',1482728448,1),
  (30,'filter','过滤系统',0,1,'0','{\"info\":\"\\u5173\\u952e\\u8bcd\\u8fc7\\u6ee4\\/\\u8fdd\\u7981\\u8bcd\\u7cfb\\u7edf\",\"menu\":\"main\"}','',1482728551,1),
  (31,'cache','缓存更新',0,1,'0','{\"info\":\"\\u7528\\u4e8e\\u66f4\\u65b0\\u5e94\\u7528\\u7a0b\\u5e8f\\u7f13\\u5b58\",\"menu\":\"main\"}','',1482728476,1),
  (32,'spider','采集系统',0,1,'[[\"spider_post\",\"id\",\"\\u53d1\\u5e03\"],[\"spider_project\",\"id\",\"\\u65b9\\u6848\"],[\"spider_rule\",\"id\",\"\\u89c4\\u5219\"],[\"spider_url\",\"id\",\"\\u91c7\\u96c6\\u7ed3\\u679c\"]]','{\"info\":\"\\u91c7\\u96c6\\u7cfb\\u7edf\",\"menu\":\"main\",\"admincp\":\"spider&do=project\"}','',1482588092,1),
  (33,'app','自定义程序',0,1,'0','{\"info\":\"\\u81ea\\u5b9a\\u4e49\\u7a0b\\u5e8f\\u63a5\\u53e3\",\"template\":[\"iCMS:app:list\",\"$app\"],\"router\":\"1\"}','',1488364566,1),
  (34,'plugin','插件',0,1,'0','{\"info\":\"\\u63d2\\u4ef6\\u7a0b\\u5e8f\"}','',1488364462,1);

INSERT  INTO `#iCMS@__config`(`appid`,`name`,`value`) values
  (0,'site','{\"name\":\"iCMS\",\"seotitle\":\"\\u7ed9\\u6211\\u4e00\\u5957\\u7a0b\\u5e8f\\uff0c\\u6211\\u80fd\\u6405\\u52a8\\u4e92\\u8054\\u7f51\",\"keywords\":\"iCMS,idreamsoft,\\u827e\\u68a6\\u8f6f\\u4ef6,iCMS\\u5185\\u5bb9\\u7ba1\\u7406\\u7cfb\\u7edf,\\u6587\\u7ae0\\u7ba1\\u7406\\u7cfb\\u7edf,PHP\\u6587\\u7ae0\\u7ba1\\u7406\\u7cfb\\u7edf\",\"description\":\"iCMS \\u662f\\u4e00\\u5957\\u91c7\\u7528 PHP \\u548c MySQL \\u6784\\u5efa\\u7684\\u9ad8\\u6548\\u7b80\\u6d01\\u7684\\u5185\\u5bb9\\u7ba1\\u7406\\u7cfb\\u7edf,\\u4e3a\\u60a8\\u7684\\u7f51\\u7ad9\\u63d0\\u4f9b\\u4e00\\u4e2a\\u5b8c\\u7f8e\\u7684\\u5f00\\u6e90\\u89e3\\u51b3\\u65b9\\u6848\",\"icp\":\"\"}'),
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
  (0,'sphinx','{\"host\":\"127.0.0.1:9312\",\"index\":\"iCMS_article iCMS_article_delta\"}'),
  (0,'open','a:4:{s:2:\"WX\";a:3:{s:5:\"appid\";s:0:\"\";s:6:\"appkey\";s:0:\"\";s:8:\"redirect\";s:0:\"\";}s:2:\"QQ\";a:3:{s:5:\"appid\";s:0:\"\";s:6:\"appkey\";s:0:\"\";s:8:\"redirect\";s:0:\"\";}s:2:\"WB\";a:3:{s:5:\"appid\";s:0:\"\";s:6:\"appkey\";s:0:\"\";s:8:\"redirect\";s:0:\"\";}s:2:\"TB\";a:3:{s:5:\"appid\";s:0:\"\";s:6:\"appkey\";s:0:\"\";s:8:\"redirect\";s:0:\"\";}}'),
  (0,'template','{\"index\":{\"mode\":\"0\",\"rewrite\":\"0\",\"tpl\":\"{iTPL}\\/index.htm\",\"name\":\"index\"},\"desktop\":{\"tpl\":\"www\\/desktop\"},\"mobile\":{\"agent\":\"WAP,Smartphone,Mobile,UCWEB,Opera Mini,Windows CE,Symbian,SAMSUNG,iPhone,Android,BlackBerry,HTC,Mini,LG,SonyEricsson,J2ME,MOT\",\"domain\":\"http:\\/\\/www.idreamsoft.com\",\"tpl\":\"www\\/mobile\"}}'),
  (0,'api','{\"baidu\":{\"sitemap\":{\"site\":\"\",\"access_token\":\"\",\"sync\":\"0\"}}}'),
  (0,'mail','{\"host\":\"\",\"secure\":\"\",\"port\":\"25\",\"username\":\"\",\"password\":\"\",\"setfrom\":\"\",\"replyto\":\"\"}'),
  (1,'article','{\"pic_center\":\"0\",\"pic_next\":\"0\",\"pageno_incr\":\"\",\"markdown\":\"0\",\"autoformat\":\"0\",\"catch_remote\":\"0\",\"remote\":\"0\",\"autopic\":\"0\",\"autodesc\":\"1\",\"descLen\":\"100\",\"autoPage\":\"0\",\"AutoPageLen\":\"\",\"repeatitle\":\"0\",\"showpic\":\"0\",\"filter\":\"0\"}'),
  (2,'category','{\"domain\":null}'),
  (3,'tag','{\"url\":\"http:\\/\\/www.idreamsoft.com\",\"rule\":\"{TKEY}\",\"dir\":\"\\/tag\\/\",\"tpl\":\"{iTPL}\\/tag.htm\"}'),
  (5,'comment','{\"enable\":\"1\",\"examine\":\"0\",\"seccode\":\"1\",\"plugin\":{\"changyan\":{\"enable\":\"0\",\"appid\":\"\",\"appkey\":\"\"}}}'),
  (9,'user','{\"register\":{\"enable\":\"1\",\"seccode\":\"1\",\"interval\":\"86400\"},\"login\":{\"enable\":\"1\",\"seccode\":\"1\",\"interval\":\"3600\"},\"post\":{\"seccode\":\"1\",\"interval\":\"10\"},\"agreement\":\"\",\"coverpic\":\"\\/ui\\/coverpic.jpg\",\"open\":{\"WX\":{\"appid\":\"\",\"appkey\":\"\",\"redirect\":\"\"},\"QQ\":{\"appid\":\"\",\"appkey\":\"\",\"redirect\":\"\"},\"WB\":{\"appid\":\"\",\"appkey\":\"\",\"redirect\":\"\"},\"TB\":{\"appid\":\"\",\"appkey\":\"\",\"redirect\":\"\"}}}'),
  (10,'weixin','{\"menu\":[{\"type\":\"view\",\"name\":\"\\u624b\\u518c\",\"url\":\"http:\\/\\/www.idreamsoft.com\\/doc\\/iCMS\\/\"},{\"type\":\"view\",\"name\":\"\\u793e\\u533a\",\"url\":\"http:\\/\\/www.idreamsoft.com\\/feedback\\/\"},{\"type\":\"click\",\"name\":\"\",\"key\":\"\"}]}'),
  (12,'keywords','{\"limit\":\"-1\"}'),
  (21,'hooks','{\"article\":{\"body\":[[\"keywordsApp\",\"HOOK_run\"],[\"plugin_taoke\",\"HOOK\"],[\"plugin_textad\",\"HOOK\"]]}}'),
  (999999,'filter','{\"disable\":[\"\"],\"filter\":[\"\"]}');

INSERT  INTO `#iCMS@__group`(`gid`,`name`,`sortnum`,`power`,`cpower`,`type`) values
  (1,'超级管理员',1,'','','1'),
  (2,'编辑',2,'','','1'),
  (3,'会员',1,'','','0');

INSERT  INTO `#iCMS@__members`(`uid`,`gid`,`username`,`password`,`nickname`,`realname`,`gender`,`info`,`power`,`cpower`,`regtime`,`lastip`,`lastlogintime`,`logintimes`,`post`,`type`,`status`) values (1,1,'admin','e10adc3949ba59abbe56e057f20f883e','iCMS','',0,'','','',0,'127.0.0.1',1488363851,265,0,1,1);

INSERT  INTO `#iCMS@__prop`(`pid`,`rootid`,`cid`,`field`,`appid`,`app`,`sortnum`,`name`,`val`) values
  (1,0,0,'pid',0,'',0,'头条','1'),
  (2,0,0,'pid',0,'',0,'首页推荐','2'),
  (3,0,0,'pid',0,'',0,'推荐栏目','1'),
  (4,0,0,'pid',0,'',0,'热门标签','1'),
  (5,0,0,'pid',0,'',0,'推荐用户','1');

INSERT  INTO `#iCMS@__spider_post`(`id`,`name`,`app`,`post`,`fun`) values
  (1,'直接发布','article','status=1\r\npostype=1\r\nremote=true\r\nautopic=true','do_save'),
  (2,'采集到草稿','article','status=0\r\npostype=1\r\nremote=true\r\nautopic=true','do_save'),
  (3,'采集到草稿 不采图','article','status=1\r\npostype=1','do_save');

