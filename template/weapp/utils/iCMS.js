var CONFIG = require('../iCMS.config.js');

var iCMS = {
    HOST: CONFIG.HOST,
    DEVICE: CONFIG.DEVICE,
    CONFIG: CONFIG,
    getURL: function (uri, tpl) {
        var url = iCMS.HOST + "public/api.php?device=" + iCMS.DEVICE + "&app=" + uri;
        if (tpl) {
            url += '&tpl=' + tpl;
        }
        return url;
    },
    GET: function (url, callback) {
        wx.request({
            url: url,
            header: {
                'content-type': 'application/json'
            },
            success(res) {
                if (typeof (callback) === "function") {
                    callback(res.data);
                }
            },
            fail(res) {
                console.log(res);
            }
        })
    },
    POST: function (url,data,callback) {
        wx.request({
            url: url,
            data:data,
            method: 'POST',
            header: {
                "content-type": "application/x-www-form-urlencoded"
            },
            success(res) {
                if (typeof (callback) === "function") {
                    callback(res.data);
                }
            },
            fail(res) {
                console.log(res);
            }
        })
    },
    data: {
        site: null,
        subTitle: null,
        hidden: false,
        loading: true,
        pageNum: 1,
        pageLast: false,
        scrollH: false,
        article_list:[],
        article_hot:[],
    },
    categoryTo: function (e) {
        // console.log(e.currentTarget);
        var cid = e.currentTarget.id;
        wx.navigateTo({
            url: '../category/category?cid=' + cid
        })
    },
    tagTo: function (e) {
        // console.log(e.currentTarget);
        var id = e.currentTarget.id;
        wx.navigateTo({
            url: '../tag/tag?id=' + id
        })
    },
    articleTo: function (e) {
        // console.log(e.currentTarget);
        var id = e.currentTarget.id;
        wx.navigateTo({
            url: '../article/article?id=' + id
        })
    },
    success: function (title,duration) {
        wx.showToast({
          title: title,
          icon: 'success',
          duration: duration||1500
        });
    },
    alert: function (content,callback,title) {
        wx.showModal({
          title: title||'iCMS提示',
          showCancel:false,
          content: content,
          success: function(res) {
                if (typeof (callback) === "function") {
                    callback(res);
                }
          }
        })
    },
    getList: function () {
    },
    loadMore: function () {
        ++this.data.pageNum;
        this.getList();
    },
    onLoad: function () {},
    refresh: function () {},
    run:function(){
        Page(this);
    }
}
iCMS.API = {
    site: iCMS.getURL('index', 'app.site'),
    index: iCMS.getURL('index'),
    category_index: iCMS.getURL('index', 'category.index'),
    category: iCMS.getURL('category', 'category.list'),
    tag: iCMS.getURL('tag', 'tag'),
    search_index: iCMS.getURL('index', 'search.index'),
    search: iCMS.getURL('search', 'search'),
    article: iCMS.getURL('article', 'article'),
    user: iCMS.getURL('user'),
    weixin: iCMS.getURL('weixin'),
    favorite: iCMS.getURL('favorite'),
}

module.exports = iCMS;
