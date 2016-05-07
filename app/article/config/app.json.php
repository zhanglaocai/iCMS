<?php defined('iPHP') OR exit('What are you doing?');?>
{
    "appid": "1",
    "app": "article",
    "title": "\u6587\u7ae0",
    "description": "\u4e00\u4e2a\u8fd8\u884c\u7684\u6587\u7ae0\u7cfb\u7edf\u5427",
    "table": ["article", "article_data"],
    "template": ["iCMS:article:list", "iCMS:article:search", "iCMS:article:data", "iCMS:article:prev", "iCMS:article:next", "$article"],
    "menu": ["main", "sidebar"],
    "category": {
        "cid": "cid",
        "text": "\u680f\u76ee",
        "template": {
            "index": "{iTPL}\/category.index.htm",
            "list": "{iTPL}\/category.list.htm",
            "content": "{iTPL}\/article.htm"
        },
        "rule": {
            "index": "\/{CDIR}\/",
            "page": "\/{CDIR}\/index_{P}.html",
            "content": "\/{CDIR}\/{YYYY}\/{MM}{DD}\/{ID}.html"
        }
    },
    "status": "0"
}
