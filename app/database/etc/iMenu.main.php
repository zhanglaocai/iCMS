<?php defined('iPHP') OR exit('What are you doing?');?>
[{
    "id": "tools",
    "children": [{
        "id": "database",
        "caption": "数据库管理",
        "icon": "database",
        "children": [{
            "caption": "数据库备份",
            "href": "database&do=backup",
            "icon": "cloud-download"
        }, {
            "caption": "备份管理",
            "href": "database&do=recover",
            "icon": "upload"
        }, {
            "-": "-"
        }, {
            "caption": "修复优化",
            "href": "database&do=repair",
            "icon": "gavel"
        }, {
            "caption": "性能优化",
            "href": "database&do=sharding",
            "icon": "puzzle-piece"
        }, {
            "-": "-"
        }, {
            "caption": "数据替换",
            "href": "database&do=replace",
            "icon": "retweet"
        }]
    }]
}]
