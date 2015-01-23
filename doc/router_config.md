router.config.php对应的REWRITE规则
==========

程序后台配置如图
![配置如图][1]

如果您的配置跟图中有不同之处

下面规则请做相应该的修改

你要原封不动复制粘贴，造成访问不了。

那就好自为之吧，我只能帮你到这了

到于什么是Rewrite，APACHE/nginx怎么设置Rewrite 请自行google

##APACHE版

```html
RewriteEngine on
RewriteBase /

RewriteRule ^u/(\d+)/$ 					user.php?do=home&uid=$1 [QSA,L]
RewriteRule ^u/(\d+)/(\d+)/$ 			user.php?do=home&uid=$1&cid=$2 [L]
RewriteRule ^u/(\d+)/(\w+)/$ 			user.php?do=$2&uid=$1 [L]

RewriteRule ^user$ 						user.php [QSA,L]
RewriteRule ^user/home$ 				user.php?do=home [L]
RewriteRule ^user/([^\/]\w+)$ 			user.php?do=manage&pg=$1 [QSA,L]
RewriteRule ^user/manage/(\w+)$ 		user.php?do=manage&pg=$1 [QSA,L]
RewriteRule ^user/inbox/(\d+)$ 			user.php?do=manage&pg=inbox&user=$1 [L]
RewriteRule ^user/profile/(\w+)$ 		user.php?do=profile&pg=$1 [L]

RewriteRule ^api$ 						public/api.php  [QSA,L]
RewriteRule ^api/([^\/]\w+)$ 			public/api.php?app=$1 [QSA,L]
RewriteRule ^api/(\w+)/(\w+)$ 			public/api.php?app=$1&do=$2 [QSA,L]
RewriteRule ^api/user/login/(\w+)$ 		public/api.php?app=user&do=login&sign=$1 [L]

```
##nginx版

```html

rewrite "^/u/(\d+)/$" 				/user.php?do=home&uid=$1 last;
rewrite "^/u/(\d+)/(\d+)/$" 		/user.php?do=home&uid=$1&cid=$2 last;
rewrite "^/u/(\d+)/(\w+)/$" 		/user.php?do=$2&uid=$1 last;

rewrite "^/user$" 					/user.php last;
rewrite "^/user/home$" 				/user.php?do=home last;
rewrite "^/user/([^\/]\w+)$" 		/user.php?do=manage&pg=$1 last;
rewrite "^/user/manage/(\w+)$" 		/user.php?do=manage&pg=$1 last;
rewrite "^/user/inbox/(\d+)$" 		/user.php?do=manage&pg=inbox&user=$1 last;
rewrite "^/user/profile/(\w+)$" 	/user.php?do=profile&pg=$1 last;

rewrite "^/api$"					/public/api.php  last;
rewrite "^/api/([^\/]\w+)$" 		/public/api.php?app=$1 last;
rewrite "^/api/(\w+)/(\w+)$" 		/public/api.php?app=$1&do=$2 last;
rewrite "^/api/user/login/(\w+)$" 	/public/api.php?app=user&do=login&sign=$1 last;

```


[1]: http://www.idreamsoft.com/doc/img/2015-01-23_162710.jpg
