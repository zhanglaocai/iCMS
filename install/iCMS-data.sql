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
/*Data for the table `icms_apps` */

LOCK TABLES `icms_apps` WRITE;

insert  into `icms_apps`(`id`,`app`,`name`,`apptype`,`type`,`table`,`config`,`fields`,`menu`,`addtime`,`status`) values
    (1,'article','文章',0,1,'{\"article\":[\"article\",\"id\",\"\",\"\\u6587\\u7ae0\"],\"article_data\":[\"article_data\",\"id\",\"aid\",\"\\u6b63\\u6587\"]}','{\"iFormer\":\"1\",\"info\":\"\\u6587\\u7ae0\\u8d44\\u8baf\\u7cfb\\u7edf\",\"template\":[\"iCMS:article:list\",\"iCMS:article:search\",\"iCMS:article:data\",\"iCMS:article:prev\",\"iCMS:article:next\",\"iCMS:article:array\",\"$article\"],\"router\":\"1\",\"menu\":\"main\"}','','[{\"id\":\"article\",\"sort\":\"2\",\"caption\":\"文章\",\"icon\":\"pencil-square-o\",\"children\":[{\"caption\":\"文章系统配置\",\"href\":\"article&do=config\",\"icon\":\"cog\"},{\"caption\":\"-\"},{\"caption\":\"栏目管理\",\"href\":\"article_category\",\"icon\":\"list-alt\"},{\"caption\":\"添加栏目\",\"href\":\"article_category&do=add\",\"icon\":\"edit\"},{\"caption\":\"-\"},{\"caption\":\"添加文章\",\"href\":\"article&do=add\",\"icon\":\"edit\"},{\"caption\":\"文章管理\",\"href\":\"article&do=manage\",\"icon\":\"list-alt\"},{\"caption\":\"草稿箱\",\"href\":\"article&do=inbox\",\"icon\":\"inbox\"},{\"caption\":\"回收站\",\"href\":\"article&do=trash\",\"icon\":\"trash-o\"},{\"caption\":\"-\"},{\"caption\":\"用户文章管理\",\"href\":\"article&do=user\",\"icon\":\"check-circle\"},{\"caption\":\"审核用户文章\",\"href\":\"article&do=examine\",\"icon\":\"minus-circle\"},{\"caption\":\"淘汰的文章\",\"href\":\"article&do=off\",\"icon\":\"times-circle\"},{\"caption\":\"-\"},{\"caption\":\"文章评论管理\",\"href\":\"comment&appname=article&appid=1\",\"icon\":\"comments\"}]}]',1488594570,1),
    (2,'category','分类',0,1,'{\"category\":[\"category\",\"cid\",\"\",\"\\u5206\\u7c7b\"],\"category_map\":[\"category_map\",\"id\",\"node\",\"\\u5206\\u7c7b\\u6620\\u5c04\"]}','{\"iFormer\":\"1\",\"info\":\"\\u901a\\u7528\\u65e0\\u9650\\u7ea7\\u5206\\u7c7b\\u7cfb\\u7edf\",\"template\":[\"iCMS:category:array\",\"iCMS:category:list\",\"$category\"],\"router\":\"1\",\"menu\":\"main\"}','','',1488594584,1),
    (3,'tag','标签',0,1,'{\"tags\":[\"tags\",\"id\",\"\",\"\\u6807\\u7b7e\"],\"tags_map\":[\"tags_map\",\"id\",\"node\",\"\\u6807\\u7b7e\\u6620\\u5c04\"]}','{\"iFormer\":\"1\",\"info\":\"\\u81ea\\u7531\\u591a\\u6837\\u6027\\u6807\\u7b7e\\u7cfb\\u7edf\",\"template\":[\"iCMS:tag:list\",\"iCMS:tag:array\",\"$tag\"],\"router\":\"1\",\"menu\":\"main\"}','','[{\"id\":\"assist\",\"children\":[{\"id\":\"tag\",\"caption\":\"标签\",\"icon\":\"tags\",\"children\":[{\"caption\":\"标签配置\",\"href\":\"tag&do=config\",\"icon\":\"cog\"},{\"caption\":\"-\"},{\"caption\":\"标签管理\",\"href\":\"tag\",\"icon\":\"tag\"},{\"caption\":\"添加标签\",\"href\":\"tag&do=add\",\"icon\":\"edit\"},{\"caption\":\"-\"},{\"caption\":\"分类管理\",\"href\":\"tag_category\",\"icon\":\"sitemap\"},{\"caption\":\"添加分类\",\"href\":\"tag_category&do=add\",\"icon\":\"edit\"}]}]}]',1488594591,1),
    (4,'push','推荐',0,1,'{\"push\":[\"push\",\"id\",\"\",\"\\u63a8\\u8350\"]}','{\"iFormer\":\"1\",\"info\":\"\\u63a8\\u8350\\u7cfb\\u7edf\",\"template\":[\"iCMS:push:list\"],\"menu\":\"main\"}','','[{\"id\":\"assist\",\"children\":[{\"id\":\"push\",\"caption\":\"推荐\",\"icon\":\"thumb-tack\",\"children\":[{\"caption\":\"推荐管理\",\"href\":\"push\",\"icon\":\"thumb-tack\"},{\"caption\":\"添加推荐\",\"href\":\"push&do=add\",\"icon\":\"edit\"},{\"caption\":\"-\"},{\"caption\":\"块管理\",\"href\":\"push_category\",\"icon\":\"sitemap\"},{\"caption\":\"添加块\",\"href\":\"push_category&do=add\",\"icon\":\"edit\"}]}]}]',1488594606,1),
    (5,'comment','评论',0,1,'{\"comment\":[\"comment\",\"id\",\"\",\"\\u8bc4\\u8bba\"]}','{\"iFormer\":\"1\",\"info\":\"\\u901a\\u7528\\u8bc4\\u8bba\\u7cfb\\u7edf\",\"template\":[\"iCMS:comment:array\",\"iCMS:comment:list\",\"iCMS:comment:form\"],\"menu\":\"main\"}','','[{\"id\":\"assist\",\"children\":[{\"id\":\"comment\",\"caption\":\"评论管理\",\"icon\":\"comments\",\"href\":\"comment\",\"children\":[{\"caption\":\"评论系统配置\",\"href\":\"comment&do=config\",\"icon\":\"cog\"},{\"caption\":\"-\"},{\"caption\":\"评论管理\",\"href\":\"comment\",\"icon\":\"comments\"}]}]}]',1488594610,1),
    (6,'prop','属性',0,1,'{\"prop\":[\"prop\",\"pid\",\"\",\"\\u5c5e\\u6027\"],\"prop_map\":[\"prop_map\",\"id\",\"node\",\"\\u5c5e\\u6027\\u6620\\u5c04\"]}','{\"info\":\"\\u901a\\u7528\\u5c5e\\u6027\\u7cfb\\u7edf\",\"template\":[\"iCMS:prop:array\"],\"menu\":\"main\"}','','[{\"id\":\"assist\",\"children\":[{\"id\":\"prop\",\"caption\":\"属性\",\"icon\":\"puzzle-piece\",\"children\":[{\"caption\":\"属性管理\",\"href\":\"prop\",\"icon\":\"puzzle-piece\"},{\"caption\":\"添加属性\",\"href\":\"prop&do=add\",\"icon\":\"edit\"}]}]}]',1488594613,1),
    (7,'message','私信',0,1,'[[\"message\",\"id\",\"\\u79c1\\u4fe1\"]]','{\"info\":\"\\u7528\\u6237\\u79c1\\u4fe1\\u7cfb\\u7edf\"}','','',1482588814,1),
    (8,'favorite','收藏',0,1,'[[\"favorite\",\"id\",\"\\u6536\\u85cf\\u4fe1\\u606f\"],[\"favorite_data\",\"fid\",\"\\u6536\\u85cf\\u6570\\u636e\"],[\"favorite_follow\",\"id\",\"fid\",\"\\u6536\\u85cf\\u5173\\u6ce8\"]]','{\"info\":\"\\u7528\\u6237\\u6536\\u85cf\\u7cfb\\u7edf\",\"template\":[\"iCMS:favorite:list\",\"iCMS:favorite:data\",\"$favorite\"],\"menu\":\"main\"}','','',1482587706,1),
    (9,'user','用户',0,1,'{\"user\":[\"user\",\"uid\",\"\",\"\\u7528\\u6237\"],\"user_category\":[\"user_category\",\"cid\",\"uid\",\"\\u7528\\u6237\\u5206\\u7c7b\"],\"user_data\":[\"user_data\",\"uid\",\"uid\",\"\\u7528\\u6237\\u6570\\u636e\"],\"user_follow\":[\"user_follow\",\"uid\",\"uid\",\"\\u7528\\u6237\\u5173\\u6ce8\"],\"user_openid\":[\"user_openid\",\"uid\",\"uid\",\"\\u7b2c\\u4e09\\u65b9\"],\"user_report\":[\"user_report\",\"id\",\"userid\",\"\\u4e3e\\u62a5\"]}','{\"iFormer\":\"1\",\"info\":\"\\u7528\\u6237\\u7cfb\\u7edf\",\"template\":[\"iCMS:user:data\",\"iCMS:user:list\",\"iCMS:user:category\",\"iCMS:user:follow\",\"iCMS:user:stat\",\"iCMS:user:inbox\"],\"router\":\"1\",\"menu\":\"main\"}','','[{\"id\": \"members\",\"children\": [{\"caption\": \"会员设置\",\"href\": \"user&do=config\",\"icon\": \"cog\"}, {\"caption\": \"-\"},{\"caption\": \"会员管理\",\"href\": \"user\",\"icon\": \"list-alt\"}, {\"caption\": \"添加会员\",\"href\": \"user&do=add\",\"icon\": \"user\"}]\n}]\n',1488116809,1),
    (10,'weixin','微信',0,1,'[[\"weixin_api_log\",\"id\",\"\\u8bb0\\u5f55\"],[\"weixin_event\",\"id\",\"\\u4e8b\\u4ef6\"]]','{\"info\":\"\\u5fae\\u4fe1\\u516c\\u4f17\\u5e73\\u53f0\\u63a5\\u53e3\\u7a0b\\u5e8f\",\"menu\":\"main\",\"admincp\":\"weixin&do=menu\"}','','[{\"id\": \"weixin\",\"sort\": \"3\",\"caption\": \"微信\",\"icon\": \"weixin\",\"children\": [{\"caption\": \"自定义菜单\",\"href\": \"weixin&do=menu\",\"icon\": \"bars\"}, {\"caption\": \"配置接口\",\"href\": \"weixin&do=config\",\"icon\": \"cog\"}, {\"caption\": \"-\"},{\"caption\": \"事件管理\",\"href\": \"weixin&do=event\",\"icon\": \"cubes\"}, {\"caption\": \"添加事件\",\"href\": \"weixin&do=event_add\",\"icon\": \"plus\"}]\n}]\n',1482587917,1),
    (12,'keywords','内链',0,2,'[[\"keywords\",\"id\",\"\\u5185\\u94fe\"]]','{\"info\":\"\\u5185\\u94fe\\u7cfb\\u7edf\",\"menu\":\"main\"}','','[{\"id\": \"assist\",\"children\": [{\"id\": \"keywords\",\"sort\": \"-9995\",\"caption\": \"内链\",\"icon\": \"paperclip\",\"children\": [{\"caption\": \"内链设置\",\"href\": \"keywords&do=config\",\"icon\": \"cog\"}, {\"caption\": \"-\"}, {\"caption\": \"内链管理\",\"href\": \"keywords\",\"icon\": \"paperclip\"}, {\"caption\": \"添加内链\",\"href\": \"keywords&do=add\",\"icon\": \"edit\"}]}]\n}]\n',1482587894,1),
    (13,'links','友情链接',0,1,'[[\"links\",\"id\",\"\\u53cb\\u60c5\\u94fe\\u63a5\"]]','{\"info\":\"\\u53cb\\u60c5\\u94fe\\u63a5\\u7a0b\\u5e8f\",\"template\":[\"iCMS:links:list\"],\"menu\":\"main\"}','','[{\"id\": \"assist\",\"children\": [{\"id\": \"links\",\"caption\": \"友情链接\",\"icon\": \"link\",\"children\": [{\"caption\": \"链接管理\",\"href\": \"links\",\"icon\": \"link\"}, {\"caption\": \"添加链接\",\"href\": \"links&do=add\",\"icon\": \"edit\"}]}]\n}]\n',1482587722,1),
    (14,'marker','标记',0,1,'[[\"marker\",\"id\",\"\\u6807\\u8bb0\"]]','{\"iFormer\":\"1\",\"info\":\"\\u6807\\u8bb0\\u7ba1\\u7406\\u7cfb\\u7edf\",\"template\":[\"iCMS:marker:html\"],\"menu\":\"main\"}','','[{\"id\": \"assist\",\"children\": [{\"id\": \"marker\",\"sort\": \"-9997\",\"caption\": \"标记\",\"icon\": \"bookmark\",\"children\": [{\"caption\": \"标记管理\",\"href\": \"marker\",\"icon\": \"bookmark\"}, {\"caption\": \"添加标记\",\"href\": \"marker&do=add\",\"icon\": \"edit\"}]}]\n}]\n',1482587726,1),
    (15,'search','搜索',0,1,'[[\"search_log\",\"id\",\"\\u641c\\u7d22\\u8bb0\\u5f55\"]]','{\"info\":\"\\u6587\\u7ae0\\u641c\\u7d22\\u7cfb\\u7edf\",\"template\":[\"iCMS:search:list\",\"iCMS:search:url\",\"$search\"],\"menu\":\"main\"}','','[{\"id\": \"assist\",\"children\": [{\"caption\": \"搜索统计\",\"href\": \"search\",\"icon\": \"search\"}]\n}]\n',1482587729,1),
    (16,'public','公共',0,1,'0','{\"info\":\"\\u516c\\u5171\\u901a\\u7528\\u6807\\u7b7e\",\"template\":[\"iCMS:public:ui\",\"iCMS:public:seccode\",\"iCMS:public:crontab\",\"iCMS:public:qrcode\"],\"menu\":\"main\",\"admincp\":\"null\"}','','',1483236548,1),
    (17,'database','数据库管理',0,1,'0','{\"info\":\"\\u540e\\u53f0\\u7b80\\u6613\\u6570\\u636e\\u5e93\\u7ba1\\u7406\",\"menu\":\"main\",\"admincp\":\"database&do=backup\"}','','[{\"id\": \"tools\",\"children\": [{\"caption\": \"-\"},{\"id\": \"database\",\"caption\": \"数据库管理\",\"icon\": \"database\",\"children\": [{\"caption\": \"数据库备份\",\"href\": \"database&do=backup\",\"icon\": \"cloud-download\"}, {\"caption\": \"备份管理\",\"href\": \"database&do=recover\",\"icon\": \"upload\"}, {\"caption\": \"-\"}, {\"caption\": \"修复优化\",\"href\": \"database&do=repair\",\"icon\": \"gavel\"}, {\"caption\": \"性能优化\",\"href\": \"database&do=sharding\",\"icon\": \"puzzle-piece\"}, {\"caption\": \"-\"}, {\"caption\": \"数据替换\",\"href\": \"database&do=replace\",\"icon\": \"retweet\"}]}]\n}]\n',1482587932,1),
    (18,'html','静态系统',0,1,'0','{\"info\":\"\\u9759\\u6001\\u6587\\u4ef6\\u751f\\u6210\\u7a0b\\u5e8f\",\"menu\":\"main\",\"admincp\":\"html&do=index\"}','','[{\"id\": \"tools\",\"children\": [{\"id\": \"html\",\"sort\":\"-992\",\"caption\": \"生成静态\",\"icon\": \"file\",\"children\": [{\"caption\": \"首页静态化\",\"href\": \"html&do=index\",\"icon\": \"refresh\"}, {\"caption\": \"-\"}, {\"caption\": \"栏目静态化\",\"href\": \"html&do=category\",\"icon\": \"refresh\"}, {\"caption\": \"文章静态化\",\"href\": \"html&do=article\",\"icon\": \"refresh\"}, {\"caption\": \"-\"}, {\"caption\": \"全站生成静态\",\"href\": \"html&do=all\",\"icon\": \"refresh\"}, {\"caption\": \"-\"}, {\"caption\": \"静态设置\",\"href\": \"config&tab=url\",\"icon\": \"cog\"}]}]\n}]\n',1482588133,1),
    (19,'index','首页系统',0,1,'0','{\"info\":\"\\u9996\\u9875\\u7a0b\\u5e8f\",\"menu\":\"main\",\"admincp\":\"null\"}','','',1482588076,1),
    (20,'admincp','后台系统',0,0,'0','{\"info\":\"\\u57fa\\u7840\\u7ba1\\u7406\\u7cfb\\u7edf\",\"menu\":\"main\",\"admincp\":\"__SELF__\"}','','[{\"id\": \"admincp\",\"sort\": \"-9999999\",\"caption\": \"管理\",\"icon\": \"home\",\"href\": \"iPHP_SELF\"},{\"caption\": \"-\",\"sort\": \"9999995\"},{\"id\": \"members\",\"sort\": \"9999996\",\"caption\": \"用户\",\"icon\": \"user\",\"children\": []},{\"id\": \"assist\",\"sort\": \"9999997\",\"caption\": \"辅助\",\"icon\": \"gavel\",\"children\": []},{\"id\": \"tools\",\"sort\": \"9999998\",\"caption\": \"工具\",\"icon\": \"gavel\",\"children\": []},{\"id\": \"system\",\"sort\": \"9999999\",\"caption\": \"系统\",\"icon\": \"cog\",\"children\": [{\"caption\": \"-\"},{\"caption\": \"检查更新\",\"href\": \"patch&do=check&force=1\",\"target\": \"iPHP_FRAME\",\"icon\": \"repeat\"},{\"caption\": \"-\"},{\"caption\": \"官方网站\",\"href\": \"http://www.idreamsoft.com\",\"target\": \"_blank\",\"icon\": \"star\"},{\"caption\": \"帮助文档\",\"href\": \"http://www.idreamsoft.com/help/\",\"target\": \"_blank\",\"icon\": \"question-circle\"}]}\n]\n',1482587926,1),
    (21,'apps','应用管理',0,1,'{\"apps\":[\"apps\",\"id\",\"\",\"\\u5e94\\u7528\"]}','{\"info\":\"\\u5e94\\u7528\\u7ba1\\u7406\",\"menu\":\"main\"}','','[{\"id\":\"system\",\"children\":[{\"id\":\"apps\",\"caption\":\"应用管理\",\"icon\":\"code\",\"sort\":\"0\",\"children\":[{\"caption\":\"应用管理\",\"href\":\"apps\",\"icon\":\"code\"},{\"caption\":\"添加应用\",\"href\":\"apps&do=add\",\"icon\":\"pencil-square-o\"},{\"caption\":\"-\"},{\"caption\":\"钩子管理\",\"href\":\"apps&do=hooks\",\"icon\":\"plug\"},{\"caption\":\"-\"},{\"caption\":\"应用市场\",\"href\":\"apps&do=store\",\"icon\":\"bank\"},{\"caption\":\"-\"},{\"caption\":\"表单管理\",\"href\":\"form\",\"icon\":\"building\"},{\"caption\":\"添加表单\",\"href\":\"form&do=add\",\"icon\":\"pencil-square-o\"}]}]}]',1488450204,1),
    (22,'group','角色系统',0,0,'[[\"group\",\"gid\",\"\\u89d2\\u8272\"]]','{\"info\":\"\\u89d2\\u8272\\u6743\\u9650\\u7cfb\\u7edf\",\"menu\":\"main\"}','','',1482623597,1),
    (23,'config','系统配置',0,0,'[[\"config\",\"appid\",\"\\u7cfb\\u7edf\\u914d\\u7f6e\"]]','{\"info\":\"\\u7cfb\\u7edf\\u914d\\u7f6e\",\"menu\":\"main\"}','','[{\"id\": \"system\",\"children\": [{\"id\": \"config\",\"caption\": \"系统设置\",\"href\": \"config\",\"icon\": \"cog\",\"sort\": \"-999\",\"children\": [{\"caption\": \"网站设置\",\"href\": \"config&tab=base\",\"icon\": \"cog\"}, {\"caption\": \"模板设置\",\"href\": \"config&tab=tpl\",\"icon\": \"cog\"}, {\"caption\": \"URL设置\",\"href\": \"config&tab=url\",\"icon\": \"cog\"}, {\"caption\": \"缓存设置\",\"href\": \"config&tab=cache\",\"icon\": \"cog\"}, {\"caption\": \"附件设置\",\"href\": \"config&tab=file\",\"icon\": \"cog\"}, {\"caption\": \"缩略图设置\",\"href\": \"config&tab=thumb\",\"icon\": \"cog\"}, {\"caption\": \"水印设置\",\"href\": \"config&tab=watermark\",\"icon\": \"cog\"}, {\"caption\": \"其它设置\",\"href\": \"config&tab=other\",\"icon\": \"cog\"}, {\"caption\": \"更新设置\",\"href\": \"config&tab=patch\",\"icon\": \"cog\"}, {\"caption\": \"高级设置\",\"href\": \"config&tab=grade\",\"icon\": \"cog\"}]},{\"caption\": \"-\",\"sort\": \"-998\"}]\n}]\n',1482626798,1),
    (24,'members','管理员',0,0,'[[\"members\",\"uid\",\"\\u7ba1\\u7406\\u5458\"]]','{\"info\":\"\\u7ba1\\u7406\\u5458\\u7cfb\\u7edf\",\"menu\":\"main\"}','','[{\"id\": \"members\",\"children\": [{\"caption\": \"管理员列表\",\"href\": \"members\",\"icon\": \"list-alt\"}, {\"caption\": \"添加管理员\",\"href\": \"members&do=add\",\"icon\": \"user\"}, {\"caption\": \"-\"}, {\"caption\": \"角色管理\",\"href\": \"group\",\"icon\": \"list-alt\"}, {\"caption\": \"添加角色\",\"href\": \"group&do=add\",\"icon\": \"group\"}, {\"caption\": \"-\"}]\n}]\n',1482623563,1),
    (25,'files','文件管理',0,0,'[[\"file_data\",\"id\",\"\\u6587\\u4ef6\"],[\"file_map\",\"fileid\",\"fileid\",\"\\u6587\\u4ef6\\u6620\\u5c04\"]]','{\"info\":\"\\u6587\\u4ef6\\u7ba1\\u7406\\u7cfb\\u7edf\",\"menu\":\"main\"}','','[{\"id\": \"tools\",\"children\": [{\"caption\": \"文件管理\",\"sort\": \"-999\",\"href\": \"files\",\"icon\": \"folder\"}, {\"caption\": \"上传文件\",\"sort\": \"-998\",\"href\": \"files&do=multi&from=modal\",\"icon\": \"upload\",\"data-toggle\": \"modal\",\"data-meta\": \"{\\\"width\\\":\\\"85%\\\",\\\"height\\\":\\\"640px\\\"}\"}, {\"caption\": \"-\",\"sort\": \"-997\"}]\n}]\n',1482623525,1),
    (26,'menu','后台菜单',0,0,'0','{\"info\":\"\\u540e\\u53f0\\u83dc\\u5355\\u7ba1\\u7406\",\"menu\":\"main\"}','','',1482728434,1),
    (27,'editor','后台编辑器',0,0,'0','{\"info\":\"\\u540e\\u53f0\\u7f16\\u8f91\\u5668\",\"menu\":\"main\"}','','',1482728399,1),
    (28,'patch','升级程序',0,0,'0','{\"info\":\"\\u7528\\u4e8e\\u5347\\u7ea7\\u7cfb\\u7edf\",\"menu\":\"main\"}','','',1482728309,1),
    (29,'template','模板管理',0,0,'0','{\"info\":\"\\u6a21\\u677f\\u7ba1\\u7406\",\"menu\":\"main\"}','','[{\"id\": \"tools\",\"children\": [{\"caption\": \"模板管理\",\"sort\": \"-996\",\"href\": \"template\",\"icon\": \"desktop\"}, {\"caption\": \"-\",\"sort\": \"-995\"}]\n}]\n',1482728448,1),
    (30,'filter','过滤系统',0,1,'0','{\"info\":\"\\u5173\\u952e\\u8bcd\\u8fc7\\u6ee4\\/\\u8fdd\\u7981\\u8bcd\\u7cfb\\u7edf\",\"menu\":\"main\"}','','[{\"id\": \"assist\",\"children\": [{\"id\":\"filter\",\"caption\": \"关键词过滤\",\"href\": \"filter\",\"icon\": \"filter\"}]\n}]\n',1482728551,1),
    (31,'cache','缓存更新',0,1,'0','{\"info\":\"\\u7528\\u4e8e\\u66f4\\u65b0\\u5e94\\u7528\\u7a0b\\u5e8f\\u7f13\\u5b58\",\"menu\":\"main\"}','','[{\"id\":\"tools\",\"children\":[{\"caption\":\"-\"},{\"id\":\"cache\",\"caption\":\"清理缓存\",\"icon\":\"refresh\",\"children\":[{\"caption\":\"更新所有缓存\",\"href\":\"cache&do=all\",\"icon\":\"refresh\",\"target\":\"iPHP_FRAME\"},{\"caption\":\"-\"},{\"caption\":\"更新系统设置\",\"href\":\"cache&acp=configAdmincp\",\"icon\":\"refresh\",\"target\":\"iPHP_FRAME\"},{\"caption\":\"更新菜单缓存\",\"href\":\"cache&do=menu\",\"icon\":\"refresh\",\"target\":\"iPHP_FRAME\"},{\"caption\":\"清除模板缓存\",\"href\":\"cache&do=tpl\",\"icon\":\"refresh\",\"target\":\"iPHP_FRAME\"},{\"caption\":\"-\"},{\"caption\":\"更新所有分类缓存\",\"href\":\"cache&do=allcategory\",\"icon\":\"refresh\",\"target\":\"iPHP_FRAME\"},{\"caption\":\"更新文章栏目缓存\",\"href\":\"cache&do=category\",\"icon\":\"refresh\",\"target\":\"iPHP_FRAME\"},{\"caption\":\"更新推送版块缓存\",\"href\":\"cache&do=pushcategory\",\"icon\":\"refresh\",\"target\":\"iPHP_FRAME\"},{\"caption\":\"更新标签分类缓存\",\"href\":\"cache&do=tagcategory\",\"icon\":\"refresh\",\"target\":\"iPHP_FRAME\"},{\"caption\":\"-\"},{\"caption\":\"更新属性缓存\",\"href\":\"cache&acp=propAdmincp\",\"icon\":\"refresh\",\"target\":\"iPHP_FRAME\"},{\"caption\":\"更新内链缓存\",\"href\":\"cache&acp=keywordsAdmincp\",\"icon\":\"refresh\",\"target\":\"iPHP_FRAME\"},{\"caption\":\"更新过滤缓存\",\"href\":\"cache&acp=filterAdmincp\",\"icon\":\"refresh\",\"target\":\"iPHP_FRAME\"},{\"caption\":\"-\"},{\"caption\":\"重计栏目文章数\",\"href\":\"cache&do=article_count\",\"icon\":\"refresh\",\"target\":\"iPHP_FRAME\"}]}]}]',1488599075,1),
    (32,'spider','采集系统',0,1,'[[\"spider_post\",\"id\",\"\\u53d1\\u5e03\"],[\"spider_project\",\"id\",\"\\u65b9\\u6848\"],[\"spider_rule\",\"id\",\"\\u89c4\\u5219\"],[\"spider_url\",\"id\",\"\\u91c7\\u96c6\\u7ed3\\u679c\"]]','{\"info\":\"\\u91c7\\u96c6\\u7cfb\\u7edf\",\"menu\":\"main\",\"admincp\":\"spider&do=project\"}','','[{\"id\": \"tools\",\"children\": [{\"id\": \"spider\",\"sort\":\"-994\",\"caption\": \"采集管理\",\"href\": \"spider\",\"icon\": \"magnet\",\"children\": [{\"caption\": \"采集列表\",\"href\": \"spider&do=manage\",\"icon\": \"list-alt\"}, {\"caption\": \"未发文章\",\"href\": \"spider&do=inbox\",\"icon\": \"inbox\"}, {\"caption\": \"-\"}, {\"caption\": \"采集方案\",\"href\": \"spider&do=project\",\"icon\": \"magnet\"}, {\"caption\": \"添加方案\",\"href\": \"spider&do=addproject\",\"icon\": \"edit\"}, {\"caption\": \"-\"}, {\"caption\": \"采集规则\",\"href\": \"spider&do=rule\",\"icon\": \"magnet\"}, {\"caption\": \"添加规则\",\"href\": \"spider&do=addrule\",\"icon\": \"edit\"}, {\"caption\": \"-\"}, {\"caption\": \"发布模块\",\"href\": \"spider&do=post\",\"icon\": \"magnet\"}, {\"caption\": \"添加发布\",\"href\": \"spider&do=addpost\",\"icon\": \"edit\"}]}, {\"caption\": \"-\",\"sort\":\"-993\"}]\n}]\n',1482588092,1),
    (33,'content','内容管理',0,1,'0','{\"info\":\"\\u81ea\\u5b9a\\u4e49\\u7a0b\\u5e8f\\u5185\\u5bb9\\u7ba1\\u7406\\/\\u63a5\\u53e3\",\"template\":[\"iCMS:content:list\",\"iCMS:content:prev\",\"iCMS:content:next\",\"iCMS:content:array\",\"$content\"],\"router\":\"1\"}','','',1488604799,1),
    (34,'plugin','插件',0,1,'0','{\"info\":\"\\u63d2\\u4ef6\\u7a0b\\u5e8f\"}','','',1488419365,1);

UNLOCK TABLES;

/*Data for the table `icms_article` */

LOCK TABLES `icms_article` WRITE;

UNLOCK TABLES;

/*Data for the table `icms_article_data` */

LOCK TABLES `icms_article_data` WRITE;

UNLOCK TABLES;

/*Data for the table `icms_category` */

LOCK TABLES `icms_category` WRITE;

UNLOCK TABLES;

/*Data for the table `icms_category_map` */

LOCK TABLES `icms_category_map` WRITE;

UNLOCK TABLES;

/*Data for the table `icms_comment` */

LOCK TABLES `icms_comment` WRITE;

UNLOCK TABLES;

/*Data for the table `icms_config` */

LOCK TABLES `icms_config` WRITE;

insert  into `icms_config`(`appid`,`name`,`value`) values
    (0,'site','{\"name\":\"iCMS\",\"seotitle\":\"\\u7ed9\\u6211\\u4e00\\u5957\\u7a0b\\u5e8f\\uff0c\\u6211\\u80fd\\u6405\\u52a8\\u4e92\\u8054\\u7f51\",\"keywords\":\"iCMS,idreamsoft,\\u827e\\u68a6\\u8f6f\\u4ef6,iCMS\\u5185\\u5bb9\\u7ba1\\u7406\\u7cfb\\u7edf,\\u6587\\u7ae0\\u7ba1\\u7406\\u7cfb\\u7edf,PHP\\u6587\\u7ae0\\u7ba1\\u7406\\u7cfb\\u7edf\",\"description\":\"iCMS \\u662f\\u4e00\\u5957\\u91c7\\u7528 PHP \\u548c MySQL \\u6784\\u5efa\\u7684\\u9ad8\\u6548\\u7b80\\u6d01\\u7684\\u5185\\u5bb9\\u7ba1\\u7406\\u7cfb\\u7edf,\\u4e3a\\u60a8\\u7684\\u7f51\\u7ad9\\u63d0\\u4f9b\\u4e00\\u4e2a\\u5b8c\\u7f8e\\u7684\\u5f00\\u6e90\\u89e3\\u51b3\\u65b9\\u6848\",\"icp\":\"\"}'),
    (0,'router','{\"url\":\"http:\\/\\/www.idreamsoft.com\",\"404\":\"http:\\/\\/www.idreamsoft.com\\/public\\/404.htm\",\"public\":\"http:\\/\\/www.idreamsoft.com\\/public\",\"user\":\"http:\\/\\/www.idreamsoft.com\\/user\",\"dir\":\"\\/\",\"ext\":\".html\",\"speed\":\"5\",\"rewrite\":\"0\"}'),
    (0,'cache','{\"engine\":\"file\",\"host\":\"\",\"time\":\"300\",\"compress\":\"1\",\"page_total\":\"300\"}'),
    (0,'FS','{\"url\":\"http:\\/\\/www.idreamsoft.com\\/res\\/\",\"dir\":\"res\",\"dir_format\":\"Y\\/m-d\\/H\",\"allow_ext\":\"gif,jpg,rar,swf,jpeg,png,zip\",\"cloud\":{\"enable\":\"0\",\"local\":\"0\",\"sdk\":{\"QiNiuYun\":{\"domain\":\"\",\"Bucket\":\"\",\"AccessKey\":\"\",\"SecretKey\":\"\"},\"TencentYun\":{\"domain\":\"\",\"AppId\":\"\",\"Bucket\":\"\",\"AccessKey\":\"\",\"SecretKey\":\"\"}}}}'),
    (0,'thumb','{\"size\":\"\"}'),
    (0,'watermark','{\"enable\":\"0\",\"width\":\"140\",\"height\":\"140\",\"allow_ext\":\"jpg,jpeg,png\",\"pos\":\"9\",\"x\":\"10\",\"y\":\"10\",\"img\":\"watermark.png\",\"text\":\"iCMS\",\"font\":\"\",\"fontsize\":\"24\",\"color\":\"#000000\",\"transparent\":\"80\"}'),
    (0,'user','{\"register\":{\"enable\":\"1\",\"seccode\":\"1\",\"interval\":\"86400\"},\"login\":{\"enable\":\"1\",\"seccode\":\"1\",\"interval\":\"3600\"},\"post\":{\"seccode\":\"1\",\"interval\":\"10\"},\"agreement\":\"\",\"coverpic\":\"\\/ui\\/coverpic.jpg\",\"open\":{\"WX\":{\"appid\":\"\",\"appkey\":\"\",\"redirect\":\"\"},\"QQ\":{\"appid\":\"\",\"appkey\":\"\",\"redirect\":\"\"},\"WB\":{\"appid\":\"\",\"appkey\":\"\",\"redirect\":\"\"},\"TB\":{\"appid\":\"\",\"appkey\":\"\",\"redirect\":\"\"}}}'),
    (0,'publish','[]'),
    (0,'comment','{\"enable\":\"1\",\"examine\":\"0\",\"seccode\":\"1\",\"plugin\":{\"changyan\":{\"enable\":\"0\",\"appid\":\"\",\"appkey\":\"\"}}}'),
    (0,'debug','{\"php\":\"1\",\"php_trace\":\"0\",\"tpl\":\"1\",\"tpl_trace\":\"0\",\"db\":\"0\",\"db_trace\":\"0\",\"db_explain\":\"0\"}'),
    (0,'time','{\"zone\":\"Asia\\/Shanghai\",\"cvtime\":\"0\",\"dateformat\":\"Y-m-d H:i:s\"}'),
    (0,'apps','[]'),
    (0,'other','{\"py_split\":\"\",\"sidebar_enable\":\"1\",\"sidebar\":\"1\"}'),
    (0,'system','{\"patch\":\"1\"}'),
    (0,'sphinx','{\"host\":\"127.0.0.1:9312\",\"index\":\"iCMS_article iCMS_article_delta\"}'),
    (0,'open','[]'),
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
    (21,'hooks','{\"article\":{\"body\":[[\"keywordsApp\",\"HOOK_run\"],[\"plugin_taoke\",\"HOOK\"],[\"plugin_textad\",\"HOOK\"],[\"plugin_download\",\"HOOK\"]]}}'),
    (999999,'filter','{\"disable\":[\"\"],\"filter\":[\"\"]}');

UNLOCK TABLES;

/*Data for the table `icms_favorite` */

LOCK TABLES `icms_favorite` WRITE;

UNLOCK TABLES;

/*Data for the table `icms_favorite_data` */

LOCK TABLES `icms_favorite_data` WRITE;

UNLOCK TABLES;

/*Data for the table `icms_favorite_follow` */

LOCK TABLES `icms_favorite_follow` WRITE;

UNLOCK TABLES;

/*Data for the table `icms_file_data` */

LOCK TABLES `icms_file_data` WRITE;

UNLOCK TABLES;

/*Data for the table `icms_file_map` */

LOCK TABLES `icms_file_map` WRITE;

UNLOCK TABLES;

/*Data for the table `icms_group` */

LOCK TABLES `icms_group` WRITE;

insert  into `icms_group`(`gid`,`name`,`sortnum`,`config`,`type`) values
    (1,'超级管理员',1,'','1'),
    (2,'编辑',2,'','1'),
    (3,'会员',1,'','0');

UNLOCK TABLES;

/*Data for the table `icms_keywords` */

LOCK TABLES `icms_keywords` WRITE;

UNLOCK TABLES;

/*Data for the table `icms_links` */

LOCK TABLES `icms_links` WRITE;

UNLOCK TABLES;

/*Data for the table `icms_marker` */

LOCK TABLES `icms_marker` WRITE;

UNLOCK TABLES;

/*Data for the table `icms_members` */

LOCK TABLES `icms_members` WRITE;

insert  into `icms_members`(`uid`,`gid`,`username`,`password`,`nickname`,`realname`,`gender`,`info`,`config`,`regtime`,`lastip`,`lastlogintime`,`logintimes`,`post`,`type`,`status`) values
    (1,1,'admin','e10adc3949ba59abbe56e057f20f883e','iCMS','',0,'','',0,'127.0.0.1',1488373614,266,0,1,1);

UNLOCK TABLES;

/*Data for the table `icms_message` */

LOCK TABLES `icms_message` WRITE;

UNLOCK TABLES;

/*Data for the table `icms_prop` */

LOCK TABLES `icms_prop` WRITE;

insert  into `icms_prop`(`pid`,`rootid`,`cid`,`field`,`appid`,`app`,`sortnum`,`name`,`val`) values
    (1,0,0,'pid',0,'',0,'头条','1'),
    (2,0,0,'pid',0,'',0,'首页推荐','2'),
    (3,0,0,'pid',0,'',0,'推荐栏目','1'),
    (4,0,0,'pid',0,'',0,'热门标签','1'),
    (5,0,0,'pid',0,'',0,'推荐用户','1');

UNLOCK TABLES;

/*Data for the table `icms_prop_map` */

LOCK TABLES `icms_prop_map` WRITE;

UNLOCK TABLES;

/*Data for the table `icms_push` */

LOCK TABLES `icms_push` WRITE;

UNLOCK TABLES;

/*Data for the table `icms_search_log` */

LOCK TABLES `icms_search_log` WRITE;

UNLOCK TABLES;

/*Data for the table `icms_sph_counter` */

LOCK TABLES `icms_sph_counter` WRITE;

UNLOCK TABLES;

/*Data for the table `icms_spider_post` */

LOCK TABLES `icms_spider_post` WRITE;

insert  into `icms_spider_post`(`id`,`name`,`app`,`post`,`fun`) values
    (1,'直接发布','article','status=1postype=1remote=trueautopic=true','do_save'),
    (2,'采集到草稿','article','status=0postype=1remote=trueautopic=true','do_save'),
    (3,'采集到草稿 不采图','article','status=1postype=1','do_save');

UNLOCK TABLES;

/*Data for the table `icms_spider_project` */

LOCK TABLES `icms_spider_project` WRITE;

UNLOCK TABLES;

/*Data for the table `icms_spider_rule` */

LOCK TABLES `icms_spider_rule` WRITE;

UNLOCK TABLES;

/*Data for the table `icms_spider_url` */

LOCK TABLES `icms_spider_url` WRITE;

UNLOCK TABLES;

/*Data for the table `icms_tags` */

LOCK TABLES `icms_tags` WRITE;

UNLOCK TABLES;

/*Data for the table `icms_tags_map` */

LOCK TABLES `icms_tags_map` WRITE;

UNLOCK TABLES;

/*Data for the table `icms_user` */

LOCK TABLES `icms_user` WRITE;

UNLOCK TABLES;

/*Data for the table `icms_user_category` */

LOCK TABLES `icms_user_category` WRITE;

UNLOCK TABLES;

/*Data for the table `icms_user_data` */

LOCK TABLES `icms_user_data` WRITE;

UNLOCK TABLES;

/*Data for the table `icms_user_follow` */

LOCK TABLES `icms_user_follow` WRITE;

UNLOCK TABLES;

/*Data for the table `icms_user_openid` */

LOCK TABLES `icms_user_openid` WRITE;

UNLOCK TABLES;

/*Data for the table `icms_user_report` */

LOCK TABLES `icms_user_report` WRITE;

UNLOCK TABLES;

/*Data for the table `icms_weixin_api_log` */

LOCK TABLES `icms_weixin_api_log` WRITE;

UNLOCK TABLES;

/*Data for the table `icms_weixin_event` */

LOCK TABLES `icms_weixin_event` WRITE;

UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
