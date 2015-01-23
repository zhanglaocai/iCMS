iCMS 使用问题集合
==========

##iCMS 运行出错.找不到"iCMS"网站的配置文件!(code:002)

解决办法:

出现这个的原因是 您还未安装iCMS

请访问 http://您的网站域名/install/index.php

安装本程序


## PHP 5.4以上报 Strict Notice: Non-static method ******()等错误
## 添加文章报错误 Field '******' doesn't have a default value]
## 后台关闭缓存后，程序出错，提示：Fatal error: Class 'iFC' not found in

解决办法:

http://www.idreamsoft.com/download/release/iCMS/v6.0.0

下载最新版本 覆盖

或者 http://www.idreamsoft.com/download/ 下载补丁

如果问题还存在 请发邮件(idreamsoft@qq.com) 或者加QQ:471334865 或者加Q群:51747677 反馈BUG

我们将及时处理


## 我模板修改了怎么没效果

解决办法:系统->清理缓存->清除模板缓存

![清除模板缓存][1]

##文章评论区 报错
##为什么刷新文章页，评论会随机报错

解决办法: 系统 -> 系统设置 -> 网站设置

关闭错误提示

程序测试完成 上线后 一定关闭

![程序错误提示][2]

##打开栏目或者文章报错
##iPHP Error: iCMS 运行出错！找不到该栏目cid:xxx 请更新栏目缓存或者确认栏目是否存在(20001)

解决办法:系统->清理缓存->更新所有分类缓存

![更新所有分类缓存][3]


##我建了栏目 但是用户中不显示

解决办法:栏目管理 -> 编辑 -> 用户设置

用户中心 选项 开启

然后更新栏目缓存

![用户中心][4]

##发表文章了 为什么首页没有显示

首页默认模板栏目区只显示有图片的文章

请自行修改首页模板标签

坐标:/template/default/index.htm

##我设置了栏目静态访问 怎么就打不开
##我开启了为静态后 网页找不到

栏目设置成静态访问模板 请手动生成HTML

![静态生成][5]

##为什么我的栏目不能生成静态提示错误
## 栏目[cid:xxx] URL规则设置问题! 此栏目不能生成静态

解决办法:栏目管理 -> 编辑 -> URL规则设置

![不能生成静态][6]

##我设置了栏目伪静态访问 怎么就打不开

设置伪静态后 请添加对应的rewrite规则

http://www.idreamsoft.com/a/16.html

##管理员账密码正确 后台登录不了 也没有提示

解决办法:
/config.php
找到

define('iPHP_COOKIE_DOMAIN','.您的域名');

改成

define('iPHP_COOKIE_DOMAIN','');

##后台怎么访问

有安装目录的

http://您的网站域名/安装目录/admincp.php

没有安装目录的

http://您的网站域名/admincp.php

##广告位怎么添加

用百度广告管理家

然后直接把广告代码 插模板里

##支持火车头 ET 采集器吗

支持

火车头v8 发布模块

http://www.idreamsoft.com/a/21.html

##能生成二维码吗

可以
/public/api.php?app=public&do=qrcode&url=网址或文本都行不要太长就可以了


##我内容很多后台会不会卡死打不开啊

已知线上使用中有40W条文章数据 后台打开3秒内

测试过100W 也差不多3秒内

##页面打开 显示500错误
##页面打开 直接跳转404页

解决办法: 系统 -> 系统设置 -> 网站设置

打开错误提示

根据提示处理 如果在官方 或者文档说明中 没找到解决办法

请加群:51747677

![程序错误提示][2]

## PHP Parse error:  syntax error, unexpected 'var' (T_VAR) in

解决办法: 打开php.ini，找到 short_open_tag 修改为Off

不懂的 百度 google php 关闭短标签

##支不支持单页

理论上支持 可以用栏目指定特定模板 在使用栏目中的大文本字段


##有没有自定义模型、自定义表单、自定义字段、下载、图片、等等不同的模型

没有 现在只有文章模型

##iCMS 收费吗

不收费

我们是开源软件 完全免费免费免费免费


[1]: http://www.idreamsoft.com/doc/img/2015-01-23_140235.jpg
[2]: http://www.idreamsoft.com/doc/img/2015-01-23_142554.jpg
[3]: http://www.idreamsoft.com/doc/img/2015-01-23_142940.jpg
[4]: http://www.idreamsoft.com/doc/img/2015-01-23_144102.jpg
[5]: http://www.idreamsoft.com/doc/img/2015-01-23_145954.jpg
[6]: http://www.idreamsoft.com/doc/img/2015-01-23_150252.jpg


