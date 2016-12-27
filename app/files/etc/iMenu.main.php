<?php defined('iPHP') OR exit('What are you doing?');?>
[{
    "id": "tools",
    "sort": "9999998",
    "caption": "工具",
    "icon": "gavel",
    "children": [{
        "caption": "文件管理",
        "sort": "-999",
        "href": "files",
        "icon": "folder"
    }, {
        "caption": "上传文件",
        "sort": "-998",
        "href": "files&do=multi&from=modal",
        "icon": "upload",
        "data-toggle": "modal",
        "data-meta": "{\"width\":\"85%\",\"height\":\"640px\"}"
    }, {
        "-": "-",
        "sort": "-997"
    }]
}]
