<?php defined('iPHP') OR exit('What are you doing?');?>
[{
    "id": "tools",
    "children": [{
        "id": "html",
        "sort":"-992",
        "caption": "生成静态",
        "icon": "file",
        "children": [{
            "caption": "首页静态化",
            "href": "html&do=index",
            "icon": "refresh"
        }, {
            "-": "-"
        }, {
            "caption": "栏目静态化",
            "href": "html&do=category",
            "icon": "refresh"
        }, {
            "caption": "文章静态化",
            "href": "html&do=article",
            "icon": "refresh"
        }, {
            "-": "-"
        }, {
            "caption": "全站生成静态",
            "href": "html&do=all",
            "icon": "refresh"
        }, {
            "-": "-"
        }, {
            "caption": "静态设置",
            "href": "config&tab=url",
            "icon": "cog"
        }]
    }, {
        "-": "-","sort":"-991"
    }]
}]
