iCMS模板标签
====

##用户列表
```html
<!--{iCMS:user:list
  loop    = "true"
  row     = "10"
  cache   = "true"
  time    = ""
  gid     = "1"
  type    = ""
  pid     = ""
  pids    = ""
  by      = "ASC|DESC"
  orderby = "id|article|comments|follow|fans|hits"
  data    = "true"

  as    = ""
  start = "0"
  step  = ""
  max   = ""
}-->
<!--{/iCMS}-->
```
###使用范围
- 所有模板

###属性介绍
|属性|可选值|说明
|-|-|-|
|loop|true|循环标记
|row|10|返回行数
|gid|1|用户组ID
|type|1|糊弄
|pid|属性值|属性值
|pids|多属性值|多属性值
|orderby|id|id:最新 <br /> article:文章数 <br /> comments:评论数 <br /> follow:关注数 <br /> fans:粉丝数 <br /> hits:点击数
|cache|true|启用缓存
|time|3600|缓存时间
|data|true|用户其它数据
|as|无|变量别名
|start|0|开始索引号
|step|1|步进值
|max|无|最大索引值

###标签内部变量
> *为系统变量

```
* <!--{$user_list.total}-->    总条数
* <!--{$user_list.prev}-->     上一条行号 (从1开始)
* <!--{$user_list.next}-->     下一条行号 (从1开始)
* <!--{$user_list.rownum}-->   行号 (从1开始)
* <!--{$user_list.index}-->    索引号 (从0开始)
* <!--{$user_list.first}-->    第一条为true 否则flase
* <!--{$user_list.last}-->     最后一条为true 否则flase


<!--{$user_list.url}-->          用户主页
<!--{$user_list.nickname}-->     用户昵称
<!--{$user_list.urls}-->         用户网址(数组)
<!--{$user_list.avatar}-->       头像
<!--{$user_list.at}-->           带用户名前@
<!--{$user_list.link}-->         链接
<!--{$user_list.gender}-->       性别
<!--{$user_list.data}-->         用户详细数据(数组)

```

```
<!--{$user_list|print_r}-->      查看所有内部变量
```

- page = "true" 时  可调用分页标签

```
<!--{$iCMS.PAGE.NAV}-->
```

###常用示例
- 获取 10个最新用户

```
<!--{iCMS:user:list loop="true" row="10"}-->
 <a href="<!--{$user_list.url}-->"><!--{$user_list.nickname}--></a>
<!--{/iCMS}-->
```

- 获取 10个粉丝最多的用户

```
<!--{iCMS:user:list loop="true" row="10" orderby="fans"}-->
 <a href="<!--{$user_list.url}-->"><!--{$user_list.nickname}--></a>
<!--{/iCMS}-->
```


