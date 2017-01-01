<?php defined('iPHP') OR exit('What are you doing?');?>
[
    {
        "id": "admincp",
        "sort": "-9999999",
        "caption": "管理",
        "icon": "home",
        "href": "iPHP_SELF"
    },
    {
        "caption": "-",
        "sort": "9999995"
    },
    {
        "id": "members",
        "sort": "9999996",
        "caption": "用户",
        "icon": "user",
        "children": []
    },
    {
        "id": "assist",
        "sort": "9999997",
        "caption": "辅助",
        "icon": "gavel",
        "children": []
    },
    {
        "id": "tools",
        "sort": "9999998",
        "caption": "工具",
        "icon": "gavel",
        "children": []
    },
    {
        "id": "system",
        "sort": "9999999",
        "caption": "系统",
        "icon": "cog",
        "children": [
            {
                "caption": "-"
            },
            {
                "caption": "检查更新",
                "href": "patch&do=check&force=1",
                "target": "iPHP_FRAME",
                "icon": "repeat"
            },
            {
                "caption": "-"
            },
            {
                "caption": "官方网站",
                "href": "http://www.idreamsoft.com",
                "target": "_blank",
                "icon": "star"
            },
            {
                "caption": "帮助文档",
                "href": "http://www.idreamsoft.com/help/",
                "target": "_blank",
                "icon": "question-circle"
            }
        ]
    }
]
