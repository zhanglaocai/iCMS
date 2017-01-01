<?php defined('iPHP') OR exit('What are you doing?');?>
[{
    "id": "assist",
    "children": [{
        "id": "tag",
        "caption": "标签",
        "icon": "tags",
        "children": [{
            "caption": "标签配置",
            "href": "tag&do=config",
            "icon": "cog"
        },{
            "caption": "-"
        },{
            "caption": "标签管理",
            "href": "tag",
            "icon": "tag"
        }, {
            "caption": "添加标签",
            "href": "tag&do=add",
            "icon": "edit"
        }, {
            "caption": "-"
        }, {
            "caption": "分类管理",
            "href": "tag_category",
            "icon": "sitemap"
        }, {
            "caption": "添加分类",
            "href": "tag_category&do=add",
            "icon": "edit"
        }]
    }]
}]
