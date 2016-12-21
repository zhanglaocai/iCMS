<?php defined('iPHP') OR exit('What are you doing?');?>
{
    "appid": "1",
    "app": "article",
    "title": "文章系统",
    "description": "文章资讯系统",
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
    "status": "1"
}
