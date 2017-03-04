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

insert  into `icms_apps`(`id`,`app`,`name`,`apptype`,`type`,`table`,`config`,`fields`,`menu`,`addtimes`,`status`) values
    (1,'article','文章',0,1,'{\"article\":[\"article\",\"id\",\"\",\"\\u6587\\u7ae0\"],\"article_data\":[\"article_data\",\"id\",\"aid\",\"\\u6b63\\u6587\"]}','{\"iFormer\":\"1\",\"info\":\"\\u6587\\u7ae0\\u8d44\\u8baf\\u7cfb\\u7edf\",\"template\":[\"iCMS:article:list\",\"iCMS:article:search\",\"iCMS:article:data\",\"iCMS:article:prev\",\"iCMS:article:next\",\"iCMS:article:array\",\"$article\"],\"router\":\"1\",\"menu\":\"main\"}','','[\r\n    {\r\n        \"id\":\"article\",\r\n        \"sort\":\"2\",\r\n        \"caption\":\"文章\",\r\n        \"icon\":\"pencil-square-o\",\r\n        \"children\":[\r\n            {\r\n                \"caption\":\"文章系统配置\",\r\n                \"href\":\"article&do=config\",\r\n                \"icon\":\"cog\"\r\n            },\r\n            {\r\n                \"caption\":\"-\"\r\n            },\r\n            {\r\n                \"caption\":\"栏目管理\",\r\n                \"href\":\"article_category\",\r\n                \"icon\":\"list-alt\"\r\n            },\r\n            {\r\n                \"caption\":\"添加栏目\",\r\n                \"href\":\"article_category&do=add\",\r\n                \"icon\":\"edit\"\r\n            },\r\n            {\r\n                \"caption\":\"-\"\r\n            },\r\n            {\r\n                \"caption\":\"添加文章\",\r\n                \"href\":\"article&do=add\",\r\n                \"icon\":\"edit\"\r\n            },\r\n            {\r\n                \"caption\":\"文章管理\",\r\n                \"href\":\"article&do=manage\",\r\n                \"icon\":\"list-alt\"\r\n            },\r\n            {\r\n                \"caption\":\"草稿箱\",\r\n                \"href\":\"article&do=inbox\",\r\n                \"icon\":\"inbox\"\r\n            },\r\n            {\r\n                \"caption\":\"回收站\",\r\n                \"href\":\"article&do=trash\",\r\n                \"icon\":\"trash-o\"\r\n            },\r\n            {\r\n                \"caption\":\"-\"\r\n            },\r\n            {\r\n                \"caption\":\"用户文章管理\",\r\n                \"href\":\"article&do=user\",\r\n                \"icon\":\"check-circle\"\r\n            },\r\n            {\r\n                \"caption\":\"审核用户文章\",\r\n                \"href\":\"article&do=examine\",\r\n                \"icon\":\"minus-circle\"\r\n            },\r\n            {\r\n                \"caption\":\"淘汰的文章\",\r\n                \"href\":\"article&do=off\",\r\n                \"icon\":\"times-circle\"\r\n            },\r\n            {\r\n                \"caption\":\"-\"\r\n            },\r\n            {\r\n                \"caption\":\"文章评论管理\",\r\n                \"href\":\"comment&appname=article&appid=1\",\r\n                \"icon\":\"comments\"\r\n            }\r\n        ]\r\n    }\r\n]',1488594570,1),
    (2,'category','分类',0,1,'{\"category\":[\"category\",\"cid\",\"\",\"\\u5206\\u7c7b\"],\"category_map\":[\"category_map\",\"id\",\"node\",\"\\u5206\\u7c7b\\u6620\\u5c04\"]}','{\"iFormer\":\"1\",\"info\":\"\\u901a\\u7528\\u65e0\\u9650\\u7ea7\\u5206\\u7c7b\\u7cfb\\u7edf\",\"template\":[\"iCMS:category:array\",\"iCMS:category:list\",\"$category\"],\"router\":\"1\",\"menu\":\"main\"}','','',1488594584,1),
    (3,'tag','标签',0,1,'{\"tags\":[\"tags\",\"id\",\"\",\"\\u6807\\u7b7e\"],\"tags_map\":[\"tags_map\",\"id\",\"node\",\"\\u6807\\u7b7e\\u6620\\u5c04\"]}','{\"iFormer\":\"1\",\"info\":\"\\u81ea\\u7531\\u591a\\u6837\\u6027\\u6807\\u7b7e\\u7cfb\\u7edf\",\"template\":[\"iCMS:tag:list\",\"iCMS:tag:array\",\"$tag\"],\"router\":\"1\",\"menu\":\"main\"}','','[\r\n    {\r\n        \"id\":\"assist\",\r\n        \"children\":[\r\n            {\r\n                \"id\":\"tag\",\r\n                \"caption\":\"标签\",\r\n                \"icon\":\"tags\",\r\n                \"children\":[\r\n                    {\r\n                        \"caption\":\"标签配置\",\r\n                        \"href\":\"tag&do=config\",\r\n                        \"icon\":\"cog\"\r\n                    },\r\n                    {\r\n                        \"caption\":\"-\"\r\n                    },\r\n                    {\r\n                        \"caption\":\"标签管理\",\r\n                        \"href\":\"tag\",\r\n                        \"icon\":\"tag\"\r\n                    },\r\n                    {\r\n                        \"caption\":\"添加标签\",\r\n                        \"href\":\"tag&do=add\",\r\n                        \"icon\":\"edit\"\r\n                    },\r\n                    {\r\n                        \"caption\":\"-\"\r\n                    },\r\n                    {\r\n                        \"caption\":\"分类管理\",\r\n                        \"href\":\"tag_category\",\r\n                        \"icon\":\"sitemap\"\r\n                    },\r\n                    {\r\n                        \"caption\":\"添加分类\",\r\n                        \"href\":\"tag_category&do=add\",\r\n                        \"icon\":\"edit\"\r\n                    }\r\n                ]\r\n            }\r\n        ]\r\n    }\r\n]',1488594591,1),
    (4,'push','推荐',0,1,'{\"push\":[\"push\",\"id\",\"\",\"\\u63a8\\u8350\"]}','{\"iFormer\":\"1\",\"info\":\"\\u63a8\\u8350\\u7cfb\\u7edf\",\"template\":[\"iCMS:push:list\"],\"menu\":\"main\"}','','[\r\n    {\r\n        \"id\":\"assist\",\r\n        \"children\":[\r\n            {\r\n                \"id\":\"push\",\r\n                \"caption\":\"推荐\",\r\n                \"icon\":\"thumb-tack\",\r\n                \"children\":[\r\n                    {\r\n                        \"caption\":\"推荐管理\",\r\n                        \"href\":\"push\",\r\n                        \"icon\":\"thumb-tack\"\r\n                    },\r\n                    {\r\n                        \"caption\":\"添加推荐\",\r\n                        \"href\":\"push&do=add\",\r\n                        \"icon\":\"edit\"\r\n                    },\r\n                    {\r\n                        \"caption\":\"-\"\r\n                    },\r\n                    {\r\n                        \"caption\":\"块管理\",\r\n                        \"href\":\"push_category\",\r\n                        \"icon\":\"sitemap\"\r\n                    },\r\n                    {\r\n                        \"caption\":\"添加块\",\r\n                        \"href\":\"push_category&do=add\",\r\n                        \"icon\":\"edit\"\r\n                    }\r\n                ]\r\n            }\r\n        ]\r\n    }\r\n]',1488594606,1),
    (5,'comment','评论',0,1,'{\"comment\":[\"comment\",\"id\",\"\",\"\\u8bc4\\u8bba\"]}','{\"iFormer\":\"1\",\"info\":\"\\u901a\\u7528\\u8bc4\\u8bba\\u7cfb\\u7edf\",\"template\":[\"iCMS:comment:array\",\"iCMS:comment:list\",\"iCMS:comment:form\"],\"menu\":\"main\"}','','[\r\n    {\r\n        \"id\":\"assist\",\r\n        \"children\":[\r\n            {\r\n                \"id\":\"comment\",\r\n                \"caption\":\"评论管理\",\r\n                \"icon\":\"comments\",\r\n                \"href\":\"comment\",\r\n                \"children\":[\r\n                    {\r\n                        \"caption\":\"评论系统配置\",\r\n                        \"href\":\"comment&do=config\",\r\n                        \"icon\":\"cog\"\r\n                    },\r\n                    {\r\n                        \"caption\":\"-\"\r\n                    },\r\n                    {\r\n                        \"caption\":\"评论管理\",\r\n                        \"href\":\"comment\",\r\n                        \"icon\":\"comments\"\r\n                    }\r\n                ]\r\n            }\r\n        ]\r\n    }\r\n]',1488594610,1),
    (6,'prop','属性',0,1,'{\"prop\":[\"prop\",\"pid\",\"\",\"\\u5c5e\\u6027\"],\"prop_map\":[\"prop_map\",\"id\",\"node\",\"\\u5c5e\\u6027\\u6620\\u5c04\"]}','{\"info\":\"\\u901a\\u7528\\u5c5e\\u6027\\u7cfb\\u7edf\",\"template\":[\"iCMS:prop:array\"],\"menu\":\"main\"}','','[\r\n    {\r\n        \"id\":\"assist\",\r\n        \"children\":[\r\n            {\r\n                \"id\":\"prop\",\r\n                \"caption\":\"属性\",\r\n                \"icon\":\"puzzle-piece\",\r\n                \"children\":[\r\n                    {\r\n                        \"caption\":\"属性管理\",\r\n                        \"href\":\"prop\",\r\n                        \"icon\":\"puzzle-piece\"\r\n                    },\r\n                    {\r\n                        \"caption\":\"添加属性\",\r\n                        \"href\":\"prop&do=add\",\r\n                        \"icon\":\"edit\"\r\n                    }\r\n                ]\r\n            }\r\n        ]\r\n    }\r\n]',1488594613,1),
    (7,'message','私信',0,1,'[[\"message\",\"id\",\"\\u79c1\\u4fe1\"]]','{\"info\":\"\\u7528\\u6237\\u79c1\\u4fe1\\u7cfb\\u7edf\"}','','',1482588814,1),
    (8,'favorite','收藏',0,1,'[[\"favorite\",\"id\",\"\\u6536\\u85cf\\u4fe1\\u606f\"],[\"favorite_data\",\"fid\",\"\\u6536\\u85cf\\u6570\\u636e\"],[\"favorite_follow\",\"id\",\"fid\",\"\\u6536\\u85cf\\u5173\\u6ce8\"]]','{\"info\":\"\\u7528\\u6237\\u6536\\u85cf\\u7cfb\\u7edf\",\"template\":[\"iCMS:favorite:list\",\"iCMS:favorite:data\",\"$favorite\"],\"menu\":\"main\"}','','',1482587706,1),
    (9,'user','用户',0,1,'{\"user\":[\"user\",\"uid\",\"\",\"\\u7528\\u6237\"],\"user_category\":[\"user_category\",\"cid\",\"uid\",\"\\u7528\\u6237\\u5206\\u7c7b\"],\"user_data\":[\"user_data\",\"uid\",\"uid\",\"\\u7528\\u6237\\u6570\\u636e\"],\"user_follow\":[\"user_follow\",\"uid\",\"uid\",\"\\u7528\\u6237\\u5173\\u6ce8\"],\"user_openid\":[\"user_openid\",\"uid\",\"uid\",\"\\u7b2c\\u4e09\\u65b9\"],\"user_report\":[\"user_report\",\"id\",\"userid\",\"\\u4e3e\\u62a5\"]}','{\"iFormer\":\"1\",\"info\":\"\\u7528\\u6237\\u7cfb\\u7edf\",\"template\":[\"iCMS:user:data\",\"iCMS:user:list\",\"iCMS:user:category\",\"iCMS:user:follow\",\"iCMS:user:stat\",\"iCMS:user:inbox\"],\"router\":\"1\",\"menu\":\"main\"}','','[{\n    \"id\": \"members\",\n    \"children\": [{\n        \"caption\": \"会员设置\",\n        \"href\": \"user&do=config\",\n        \"icon\": \"cog\"\n    }, {\n        \"caption\": \"-\"\n    },{\n        \"caption\": \"会员管理\",\n        \"href\": \"user\",\n        \"icon\": \"list-alt\"\n    }, {\n        \"caption\": \"添加会员\",\n        \"href\": \"user&do=add\",\n        \"icon\": \"user\"\n    }]\n}]\n',1488116809,1),
    (10,'weixin','微信',0,1,'[[\"weixin_api_log\",\"id\",\"\\u8bb0\\u5f55\"],[\"weixin_event\",\"id\",\"\\u4e8b\\u4ef6\"]]','{\"info\":\"\\u5fae\\u4fe1\\u516c\\u4f17\\u5e73\\u53f0\\u63a5\\u53e3\\u7a0b\\u5e8f\",\"menu\":\"main\",\"admincp\":\"weixin&do=menu\"}','','[{\n    \"id\": \"weixin\",\n    \"sort\": \"3\",\n    \"caption\": \"微信\",\n    \"icon\": \"weixin\",\n    \"children\": [{\n        \"caption\": \"自定义菜单\",\n        \"href\": \"weixin&do=menu\",\n        \"icon\": \"bars\"\n    }, {\n        \"caption\": \"配置接口\",\n        \"href\": \"weixin&do=config\",\n        \"icon\": \"cog\"\n    }, {\n        \"caption\": \"-\"\n    },{\n        \"caption\": \"事件管理\",\n        \"href\": \"weixin&do=event\",\n        \"icon\": \"cubes\"\n    }, {\n        \"caption\": \"添加事件\",\n        \"href\": \"weixin&do=event_add\",\n        \"icon\": \"plus\"\n    }]\n}]\n',1482587917,1),
    (12,'keywords','内链',0,2,'[[\"keywords\",\"id\",\"\\u5185\\u94fe\"]]','{\"info\":\"\\u5185\\u94fe\\u7cfb\\u7edf\",\"menu\":\"main\"}','','[{\n    \"id\": \"assist\",\n    \"children\": [{\n        \"id\": \"keywords\",\n        \"sort\": \"-9995\",\n        \"caption\": \"内链\",\n        \"icon\": \"paperclip\",\n        \"children\": [{\n            \"caption\": \"内链设置\",\n            \"href\": \"keywords&do=config\",\n            \"icon\": \"cog\"\n        }, {\n            \"caption\": \"-\"\n        }, {\n            \"caption\": \"内链管理\",\n            \"href\": \"keywords\",\n            \"icon\": \"paperclip\"\n        }, {\n            \"caption\": \"添加内链\",\n            \"href\": \"keywords&do=add\",\n            \"icon\": \"edit\"\n        }]\n    }]\n}]\n',1482587894,1),
    (13,'links','友情链接',0,1,'[[\"links\",\"id\",\"\\u53cb\\u60c5\\u94fe\\u63a5\"]]','{\"info\":\"\\u53cb\\u60c5\\u94fe\\u63a5\\u7a0b\\u5e8f\",\"template\":[\"iCMS:links:list\"],\"menu\":\"main\"}','','[{\n    \"id\": \"assist\",\n    \"children\": [{\n        \"id\": \"links\",\n        \"caption\": \"友情链接\",\n        \"icon\": \"link\",\n        \"children\": [{\n            \"caption\": \"链接管理\",\n            \"href\": \"links\",\n            \"icon\": \"link\"\n        }, {\n            \"caption\": \"添加链接\",\n            \"href\": \"links&do=add\",\n            \"icon\": \"edit\"\n        }]\n    }]\n}]\n',1482587722,1),
    (14,'marker','标记',0,1,'[[\"marker\",\"id\",\"\\u6807\\u8bb0\"]]','{\"iFormer\":\"1\",\"info\":\"\\u6807\\u8bb0\\u7ba1\\u7406\\u7cfb\\u7edf\",\"template\":[\"iCMS:marker:html\"],\"menu\":\"main\"}','','[{\n    \"id\": \"assist\",\n    \"children\": [{\n        \"id\": \"marker\",\n        \"sort\": \"-9997\",\n        \"caption\": \"标记\",\n        \"icon\": \"bookmark\",\n        \"children\": [{\n            \"caption\": \"标记管理\",\n            \"href\": \"marker\",\n            \"icon\": \"bookmark\"\n        }, {\n            \"caption\": \"添加标记\",\n            \"href\": \"marker&do=add\",\n            \"icon\": \"edit\"\n        }]\n    }]\n}]\n',1482587726,1),
    (15,'search','搜索',0,1,'[[\"search_log\",\"id\",\"\\u641c\\u7d22\\u8bb0\\u5f55\"]]','{\"info\":\"\\u6587\\u7ae0\\u641c\\u7d22\\u7cfb\\u7edf\",\"template\":[\"iCMS:search:list\",\"iCMS:search:url\",\"$search\"],\"menu\":\"main\"}','','[{\n    \"id\": \"assist\",\n    \"children\": [{\n        \"caption\": \"搜索统计\",\n        \"href\": \"search\",\n        \"icon\": \"search\"\n    }]\n}]\n',1482587729,1),
    (16,'public','公共',0,1,'0','{\"info\":\"\\u516c\\u5171\\u901a\\u7528\\u6807\\u7b7e\",\"template\":[\"iCMS:public:ui\",\"iCMS:public:seccode\",\"iCMS:public:crontab\",\"iCMS:public:qrcode\"],\"menu\":\"main\",\"admincp\":\"null\"}','','',1483236548,1),
    (17,'database','数据库管理',0,1,'0','{\"info\":\"\\u540e\\u53f0\\u7b80\\u6613\\u6570\\u636e\\u5e93\\u7ba1\\u7406\",\"menu\":\"main\",\"admincp\":\"database&do=backup\"}','','[{\n    \"id\": \"tools\",\n    \"children\": [{\n        \"caption\": \"-\"\n    },{\n        \"id\": \"database\",\n        \"caption\": \"数据库管理\",\n        \"icon\": \"database\",\n        \"children\": [{\n            \"caption\": \"数据库备份\",\n            \"href\": \"database&do=backup\",\n            \"icon\": \"cloud-download\"\n        }, {\n            \"caption\": \"备份管理\",\n            \"href\": \"database&do=recover\",\n            \"icon\": \"upload\"\n        }, {\n            \"caption\": \"-\"\n        }, {\n            \"caption\": \"修复优化\",\n            \"href\": \"database&do=repair\",\n            \"icon\": \"gavel\"\n        }, {\n            \"caption\": \"性能优化\",\n            \"href\": \"database&do=sharding\",\n            \"icon\": \"puzzle-piece\"\n        }, {\n            \"caption\": \"-\"\n        }, {\n            \"caption\": \"数据替换\",\n            \"href\": \"database&do=replace\",\n            \"icon\": \"retweet\"\n        }]\n    }]\n}]\n',1482587932,1),
    (18,'html','静态系统',0,1,'0','{\"info\":\"\\u9759\\u6001\\u6587\\u4ef6\\u751f\\u6210\\u7a0b\\u5e8f\",\"menu\":\"main\",\"admincp\":\"html&do=index\"}','','[{\n    \"id\": \"tools\",\n    \"children\": [{\n        \"id\": \"html\",\n        \"sort\":\"-992\",\n        \"caption\": \"生成静态\",\n        \"icon\": \"file\",\n        \"children\": [{\n            \"caption\": \"首页静态化\",\n            \"href\": \"html&do=index\",\n            \"icon\": \"refresh\"\n        }, {\n            \"caption\": \"-\"\n        }, {\n            \"caption\": \"栏目静态化\",\n            \"href\": \"html&do=category\",\n            \"icon\": \"refresh\"\n        }, {\n            \"caption\": \"文章静态化\",\n            \"href\": \"html&do=article\",\n            \"icon\": \"refresh\"\n        }, {\n            \"caption\": \"-\"\n        }, {\n            \"caption\": \"全站生成静态\",\n            \"href\": \"html&do=all\",\n            \"icon\": \"refresh\"\n        }, {\n            \"caption\": \"-\"\n        }, {\n            \"caption\": \"静态设置\",\n            \"href\": \"config&tab=url\",\n            \"icon\": \"cog\"\n        }]\n    }]\n}]\n',1482588133,1),
    (19,'index','首页系统',0,1,'0','{\"info\":\"\\u9996\\u9875\\u7a0b\\u5e8f\",\"menu\":\"main\",\"admincp\":\"null\"}','','',1482588076,1),
    (20,'admincp','后台系统',0,0,'0','{\"info\":\"\\u57fa\\u7840\\u7ba1\\u7406\\u7cfb\\u7edf\",\"menu\":\"main\",\"admincp\":\"__SELF__\"}','','[\n    {\n        \"id\": \"admincp\",\n        \"sort\": \"-9999999\",\n        \"caption\": \"管理\",\n        \"icon\": \"home\",\n        \"href\": \"iPHP_SELF\"\n    },\n    {\n        \"caption\": \"-\",\n        \"sort\": \"9999995\"\n    },\n    {\n        \"id\": \"members\",\n        \"sort\": \"9999996\",\n        \"caption\": \"用户\",\n        \"icon\": \"user\",\n        \"children\": []\n    },\n    {\n        \"id\": \"assist\",\n        \"sort\": \"9999997\",\n        \"caption\": \"辅助\",\n        \"icon\": \"gavel\",\n        \"children\": []\n    },\n    {\n        \"id\": \"tools\",\n        \"sort\": \"9999998\",\n        \"caption\": \"工具\",\n        \"icon\": \"gavel\",\n        \"children\": []\n    },\n    {\n        \"id\": \"system\",\n        \"sort\": \"9999999\",\n        \"caption\": \"系统\",\n        \"icon\": \"cog\",\n        \"children\": [\n            {\n                \"caption\": \"-\"\n            },\n            {\n                \"caption\": \"检查更新\",\n                \"href\": \"patch&do=check&force=1\",\n                \"target\": \"iPHP_FRAME\",\n                \"icon\": \"repeat\"\n            },\n            {\n                \"caption\": \"-\"\n            },\n            {\n                \"caption\": \"官方网站\",\n                \"href\": \"http://www.idreamsoft.com\",\n                \"target\": \"_blank\",\n                \"icon\": \"star\"\n            },\n            {\n                \"caption\": \"帮助文档\",\n                \"href\": \"http://www.idreamsoft.com/help/\",\n                \"target\": \"_blank\",\n                \"icon\": \"question-circle\"\n            }\n        ]\n    }\n]\n',1482587926,1),
    (21,'apps','应用管理',0,1,'{\"apps\":[\"apps\",\"id\",\"\",\"\\u5e94\\u7528\"]}','{\"info\":\"\\u5e94\\u7528\\u7ba1\\u7406\",\"menu\":\"main\"}','','[\r\n    {\r\n        \"id\":\"system\",\r\n        \"children\":[\r\n            {\r\n                \"id\":\"apps\",\r\n                \"caption\":\"应用管理\",\r\n                \"icon\":\"code\",\r\n                \"sort\":\"0\",\r\n                \"children\":[\r\n                    {\r\n                        \"caption\":\"应用管理\",\r\n                        \"href\":\"apps\",\r\n                        \"icon\":\"code\"\r\n                    },\r\n                    {\r\n                        \"caption\":\"添加应用\",\r\n                        \"href\":\"apps&do=add\",\r\n                        \"icon\":\"pencil-square-o\"\r\n                    },\r\n                    {\r\n                        \"caption\":\"-\"\r\n                    },\r\n                    {\r\n                        \"caption\":\"钩子管理\",\r\n                        \"href\":\"apps&do=hooks\",\r\n                        \"icon\":\"plug\"\r\n                    },\r\n                    {\r\n                        \"caption\":\"-\"\r\n                    },\r\n                    {\r\n                        \"caption\":\"应用市场\",\r\n                        \"href\":\"apps&do=store\",\r\n                        \"icon\":\"bank\"\r\n                    },\r\n                    {\r\n                        \"caption\":\"-\"\r\n                    },\r\n                    {\r\n                        \"caption\":\"表单管理\",\r\n                        \"href\":\"form\",\r\n                        \"icon\":\"building\"\r\n                    },\r\n                    {\r\n                        \"caption\":\"添加表单\",\r\n                        \"href\":\"form&do=add\",\r\n                        \"icon\":\"pencil-square-o\"\r\n                    }\r\n                ]\r\n            }\r\n        ]\r\n    }\r\n]',1488450204,1),
    (22,'group','角色系统',0,0,'[[\"group\",\"gid\",\"\\u89d2\\u8272\"]]','{\"info\":\"\\u89d2\\u8272\\u6743\\u9650\\u7cfb\\u7edf\",\"menu\":\"main\"}','','',1482623597,1),
    (23,'config','系统配置',0,0,'[[\"config\",\"appid\",\"\\u7cfb\\u7edf\\u914d\\u7f6e\"]]','{\"info\":\"\\u7cfb\\u7edf\\u914d\\u7f6e\",\"menu\":\"main\"}','','[{\n    \"id\": \"system\",\n    \"children\": [{\n            \"id\": \"config\",\n            \"caption\": \"系统设置\",\n            \"href\": \"config\",\n            \"icon\": \"cog\",\n            \"sort\": \"-999\",\n            \"children\": [{\n                \"caption\": \"网站设置\",\n                \"href\": \"config&tab=base\",\n                \"icon\": \"cog\"\n            }, {\n                \"caption\": \"模板设置\",\n                \"href\": \"config&tab=tpl\",\n                \"icon\": \"cog\"\n            }, {\n                \"caption\": \"URL设置\",\n                \"href\": \"config&tab=url\",\n                \"icon\": \"cog\"\n            }, {\n                \"caption\": \"缓存设置\",\n                \"href\": \"config&tab=cache\",\n                \"icon\": \"cog\"\n            }, {\n                \"caption\": \"附件设置\",\n                \"href\": \"config&tab=file\",\n                \"icon\": \"cog\"\n            }, {\n                \"caption\": \"缩略图设置\",\n                \"href\": \"config&tab=thumb\",\n                \"icon\": \"cog\"\n            }, {\n                \"caption\": \"水印设置\",\n                \"href\": \"config&tab=watermark\",\n                \"icon\": \"cog\"\n            }, {\n                \"caption\": \"其它设置\",\n                \"href\": \"config&tab=other\",\n                \"icon\": \"cog\"\n            }, {\n                \"caption\": \"更新设置\",\n                \"href\": \"config&tab=patch\",\n                \"icon\": \"cog\"\n            }, {\n                \"caption\": \"高级设置\",\n                \"href\": \"config&tab=grade\",\n                \"icon\": \"cog\"\n            }]\n        },{\n            \"caption\": \"-\",\n            \"sort\": \"-998\"\n        }]\n}]\n',1482626798,1),
    (24,'members','管理员',0,0,'[[\"members\",\"uid\",\"\\u7ba1\\u7406\\u5458\"]]','{\"info\":\"\\u7ba1\\u7406\\u5458\\u7cfb\\u7edf\",\"menu\":\"main\"}','','[{\n    \"id\": \"members\",\n    \"children\": [{\n        \"caption\": \"管理员列表\",\n        \"href\": \"members\",\n        \"icon\": \"list-alt\"\n    }, {\n        \"caption\": \"添加管理员\",\n        \"href\": \"members&do=add\",\n        \"icon\": \"user\"\n    }, {\n        \"caption\": \"-\"\n    }, {\n        \"caption\": \"角色管理\",\n        \"href\": \"group\",\n        \"icon\": \"list-alt\"\n    }, {\n        \"caption\": \"添加角色\",\n        \"href\": \"group&do=add\",\n        \"icon\": \"group\"\n    }, {\n        \"caption\": \"-\"\n    }]\n}]\n',1482623563,1),
    (25,'files','文件管理',0,0,'[[\"file_data\",\"id\",\"\\u6587\\u4ef6\"],[\"file_map\",\"fileid\",\"fileid\",\"\\u6587\\u4ef6\\u6620\\u5c04\"]]','{\"info\":\"\\u6587\\u4ef6\\u7ba1\\u7406\\u7cfb\\u7edf\",\"menu\":\"main\"}','','[{\n    \"id\": \"tools\",\n    \"children\": [{\n        \"caption\": \"文件管理\",\n        \"sort\": \"-999\",\n        \"href\": \"files\",\n        \"icon\": \"folder\"\n    }, {\n        \"caption\": \"上传文件\",\n        \"sort\": \"-998\",\n        \"href\": \"files&do=multi&from=modal\",\n        \"icon\": \"upload\",\n        \"data-toggle\": \"modal\",\n        \"data-meta\": \"{\\\"width\\\":\\\"85%\\\",\\\"height\\\":\\\"640px\\\"}\"\n    }, {\n        \"caption\": \"-\",\n        \"sort\": \"-997\"\n    }]\n}]\n',1482623525,1),
    (26,'menu','后台菜单',0,0,'0','{\"info\":\"\\u540e\\u53f0\\u83dc\\u5355\\u7ba1\\u7406\",\"menu\":\"main\"}','','',1482728434,1),
    (27,'editor','后台编辑器',0,0,'0','{\"info\":\"\\u540e\\u53f0\\u7f16\\u8f91\\u5668\",\"menu\":\"main\"}','','',1482728399,1),
    (28,'patch','升级程序',0,0,'0','{\"info\":\"\\u7528\\u4e8e\\u5347\\u7ea7\\u7cfb\\u7edf\",\"menu\":\"main\"}','','',1482728309,1),
    (29,'template','模板管理',0,0,'0','{\"info\":\"\\u6a21\\u677f\\u7ba1\\u7406\",\"menu\":\"main\"}','','[{\n    \"id\": \"tools\",\n    \"children\": [{\n        \"caption\": \"模板管理\",\n        \"sort\": \"-996\",\n        \"href\": \"template\",\n        \"icon\": \"desktop\"\n    }, {\n        \"caption\": \"-\",\n        \"sort\": \"-995\"\n    }]\n}]\n',1482728448,1),
    (30,'filter','过滤系统',0,1,'0','{\"info\":\"\\u5173\\u952e\\u8bcd\\u8fc7\\u6ee4\\/\\u8fdd\\u7981\\u8bcd\\u7cfb\\u7edf\",\"menu\":\"main\"}','','[{\n    \"id\": \"assist\",\n    \"children\": [{\n        \"id\":\"filter\",\n        \"caption\": \"关键词过滤\",\n        \"href\": \"filter\",\n        \"icon\": \"filter\"\n    }]\n}]\n',1482728551,1),
    (31,'cache','缓存更新',0,1,'0','{\"info\":\"\\u7528\\u4e8e\\u66f4\\u65b0\\u5e94\\u7528\\u7a0b\\u5e8f\\u7f13\\u5b58\",\"menu\":\"main\"}','','[\r\n    {\r\n        \"id\":\"tools\",\r\n        \"children\":[\r\n            {\r\n                \"caption\":\"-\"\r\n            },\r\n            {\r\n                \"id\":\"cache\",\r\n                \"caption\":\"清理缓存\",\r\n                \"icon\":\"refresh\",\r\n                \"children\":[\r\n                    {\r\n                        \"caption\":\"更新所有缓存\",\r\n                        \"href\":\"cache&do=all\",\r\n                        \"icon\":\"refresh\",\r\n                        \"target\":\"iPHP_FRAME\"\r\n                    },\r\n                    {\r\n                        \"caption\":\"-\"\r\n                    },\r\n                    {\r\n                        \"caption\":\"更新系统设置\",\r\n                        \"href\":\"cache&acp=configAdmincp\",\r\n                        \"icon\":\"refresh\",\r\n                        \"target\":\"iPHP_FRAME\"\r\n                    },\r\n                    {\r\n                        \"caption\":\"更新菜单缓存\",\r\n                        \"href\":\"cache&do=menu\",\r\n                        \"icon\":\"refresh\",\r\n                        \"target\":\"iPHP_FRAME\"\r\n                    },\r\n                    {\r\n                        \"caption\":\"清除模板缓存\",\r\n                        \"href\":\"cache&do=tpl\",\r\n                        \"icon\":\"refresh\",\r\n                        \"target\":\"iPHP_FRAME\"\r\n                    },\r\n                    {\r\n                        \"caption\":\"-\"\r\n                    },\r\n                    {\r\n                        \"caption\":\"更新所有分类缓存\",\r\n                        \"href\":\"cache&do=allcategory\",\r\n                        \"icon\":\"refresh\",\r\n                        \"target\":\"iPHP_FRAME\"\r\n                    },\r\n                    {\r\n                        \"caption\":\"更新文章栏目缓存\",\r\n                        \"href\":\"cache&do=category\",\r\n                        \"icon\":\"refresh\",\r\n                        \"target\":\"iPHP_FRAME\"\r\n                    },\r\n                    {\r\n                        \"caption\":\"更新推送版块缓存\",\r\n                        \"href\":\"cache&do=pushcategory\",\r\n                        \"icon\":\"refresh\",\r\n                        \"target\":\"iPHP_FRAME\"\r\n                    },\r\n                    {\r\n                        \"caption\":\"更新标签分类缓存\",\r\n                        \"href\":\"cache&do=tagcategory\",\r\n                        \"icon\":\"refresh\",\r\n                        \"target\":\"iPHP_FRAME\"\r\n                    },\r\n                    {\r\n                        \"caption\":\"-\"\r\n                    },\r\n                    {\r\n                        \"caption\":\"更新属性缓存\",\r\n                        \"href\":\"cache&acp=propAdmincp\",\r\n                        \"icon\":\"refresh\",\r\n                        \"target\":\"iPHP_FRAME\"\r\n                    },\r\n                    {\r\n                        \"caption\":\"更新内链缓存\",\r\n                        \"href\":\"cache&acp=keywordsAdmincp\",\r\n                        \"icon\":\"refresh\",\r\n                        \"target\":\"iPHP_FRAME\"\r\n                    },\r\n                    {\r\n                        \"caption\":\"更新过滤缓存\",\r\n                        \"href\":\"cache&acp=filterAdmincp\",\r\n                        \"icon\":\"refresh\",\r\n                        \"target\":\"iPHP_FRAME\"\r\n                    },\r\n                    {\r\n                        \"caption\":\"-\"\r\n                    },\r\n                    {\r\n                        \"caption\":\"重计栏目文章数\",\r\n                        \"href\":\"cache&do=article_count\",\r\n                        \"icon\":\"refresh\",\r\n                        \"target\":\"iPHP_FRAME\"\r\n                    }\r\n                ]\r\n            }\r\n        ]\r\n    }\r\n]',1488599075,1),
    (32,'spider','采集系统',0,1,'[[\"spider_post\",\"id\",\"\\u53d1\\u5e03\"],[\"spider_project\",\"id\",\"\\u65b9\\u6848\"],[\"spider_rule\",\"id\",\"\\u89c4\\u5219\"],[\"spider_url\",\"id\",\"\\u91c7\\u96c6\\u7ed3\\u679c\"]]','{\"info\":\"\\u91c7\\u96c6\\u7cfb\\u7edf\",\"menu\":\"main\",\"admincp\":\"spider&do=project\"}','','[{\n    \"id\": \"tools\",\n    \"children\": [{\n        \"id\": \"spider\",\n        \"sort\":\"-994\",\n        \"caption\": \"采集管理\",\n        \"href\": \"spider\",\n        \"icon\": \"magnet\",\n        \"children\": [{\n            \"caption\": \"采集列表\",\n            \"href\": \"spider&do=manage\",\n            \"icon\": \"list-alt\"\n        }, {\n            \"caption\": \"未发文章\",\n            \"href\": \"spider&do=inbox\",\n            \"icon\": \"inbox\"\n        }, {\n            \"caption\": \"-\"\n        }, {\n            \"caption\": \"采集方案\",\n            \"href\": \"spider&do=project\",\n            \"icon\": \"magnet\"\n        }, {\n            \"caption\": \"添加方案\",\n            \"href\": \"spider&do=addproject\",\n            \"icon\": \"edit\"\n        }, {\n            \"caption\": \"-\"\n        }, {\n            \"caption\": \"采集规则\",\n            \"href\": \"spider&do=rule\",\n            \"icon\": \"magnet\"\n        }, {\n            \"caption\": \"添加规则\",\n            \"href\": \"spider&do=addrule\",\n            \"icon\": \"edit\"\n        }, {\n            \"caption\": \"-\"\n        }, {\n            \"caption\": \"发布模块\",\n            \"href\": \"spider&do=post\",\n            \"icon\": \"magnet\"\n        }, {\n            \"caption\": \"添加发布\",\n            \"href\": \"spider&do=addpost\",\n            \"icon\": \"edit\"\n        }]\n    }, {\n        \"caption\": \"-\",\"sort\":\"-993\"\n    }]\n}]\n',1482588092,1),
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
    (0,'router','{\"url\":\"http:\\/\\/icms62.idreamsoft.com\",\"404\":\"http:\\/\\/icms62.idreamsoft.com\\/public\\/404.htm\",\"public\":\"http:\\/\\/icms62.idreamsoft.com\\/public\",\"user\":\"http:\\/\\/icms62.idreamsoft.com\\/user\",\"dir\":\"\\/\",\"ext\":\".html\",\"speed\":\"5\",\"rewrite\":\"0\"}'),
    (0,'cache','{\"engine\":\"file\",\"host\":\"\",\"time\":\"300\",\"compress\":\"1\",\"page_total\":\"300\"}'),
    (0,'FS','{\"url\":\"http:\\/\\/icms62.idreamsoft.com\\/res\\/\",\"dir\":\"res\",\"dir_format\":\"Y\\/m-d\\/H\",\"allow_ext\":\"gif,jpg,rar,swf,jpeg,png,zip\",\"cloud\":{\"enable\":\"0\",\"local\":\"0\",\"sdk\":{\"QiNiuYun\":{\"domain\":\"\",\"Bucket\":\"\",\"AccessKey\":\"\",\"SecretKey\":\"\"},\"TencentYun\":{\"domain\":\"\",\"AppId\":\"\",\"Bucket\":\"\",\"AccessKey\":\"\",\"SecretKey\":\"\"}}}}'),
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
    (0,'template','{\"index\":{\"mode\":\"0\",\"rewrite\":\"0\",\"tpl\":\"{iTPL}\\/index.htm\",\"name\":\"index\"},\"desktop\":{\"tpl\":\"www\\/desktop\"},\"mobile\":{\"agent\":\"WAP,Smartphone,Mobile,UCWEB,Opera Mini,Windows CE,Symbian,SAMSUNG,iPhone,Android,BlackBerry,HTC,Mini,LG,SonyEricsson,J2ME,MOT\",\"domain\":\"http:\\/\\/icms62.idreamsoft.com\",\"tpl\":\"www\\/mobile\"}}'),
    (0,'api','{\"baidu\":{\"sitemap\":{\"site\":\"\",\"access_token\":\"\",\"sync\":\"0\"}}}'),
    (0,'mail','{\"host\":\"\",\"secure\":\"\",\"port\":\"25\",\"username\":\"\",\"password\":\"\",\"setfrom\":\"\",\"replyto\":\"\"}'),
    (1,'article','{\"pic_center\":\"0\",\"pic_next\":\"0\",\"pageno_incr\":\"\",\"markdown\":\"0\",\"autoformat\":\"0\",\"catch_remote\":\"0\",\"remote\":\"0\",\"autopic\":\"0\",\"autodesc\":\"1\",\"descLen\":\"100\",\"autoPage\":\"0\",\"AutoPageLen\":\"\",\"repeatitle\":\"0\",\"showpic\":\"0\",\"filter\":\"0\"}'),
    (2,'category','{\"domain\":null}'),
    (3,'tag','{\"url\":\"http:\\/\\/icms62.idreamsoft.com\",\"rule\":\"{TKEY}\",\"dir\":\"\\/tag\\/\",\"tpl\":\"{iTPL}\\/tag.htm\"}'),
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

insert  into `icms_group`(`gid`,`name`,`sortnum`,`power`,`cpower`,`type`) values
    (1,'超级管理员',1,'','','1'),
    (2,'编辑',2,'','','1'),
    (3,'会员',1,'','','0');

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

insert  into `icms_members`(`uid`,`gid`,`username`,`password`,`nickname`,`realname`,`gender`,`info`,`power`,`cpower`,`regtime`,`lastip`,`lastlogintime`,`logintimes`,`post`,`type`,`status`) values
    (1,1,'admin','e10adc3949ba59abbe56e057f20f883e','iCMS','',0,'','','',0,'127.0.0.1',1488373614,266,0,1,1);

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
    (1,'直接发布','article','status=1\r\npostype=1\r\nremote=true\r\nautopic=true','do_save'),
    (2,'采集到草稿','article','status=0\r\npostype=1\r\nremote=true\r\nautopic=true','do_save'),
    (3,'采集到草稿 不采图','article','status=1\r\npostype=1','do_save');

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
