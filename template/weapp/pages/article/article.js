var app = getApp();
var iCMS = require('../../utils/iCMS.js');
var WxParse = require('../../utils/wxParse/wxParse.js');
Page({
    data: {
        article: [],
        hidden: false
    },
    article: function(id) {
        var that = this;
        iCMS.GET(iCMS.API.article + '&id=' + id, function(json) {
            // console.log(json);
            wx.setNavigationBarTitle({
                title: json.data.category.name+' - '+json.data.site.name
            });
            WxParse.wxParse('body', 'html', json.data.article.body, that, 5);
            that.setData({
                article: json.data.article,
                category: json.data.category,
                hidden: true
            });
        });
    },
    onLoad: function(options) {
        this.article(options.id);
        // 页面初始化 options为页面跳转所带来的参数
    },
    onReady: function() {
        // 页面渲染完成
    },
    onShow: function() {
        // 页面显示
    },
    onHide: function() {
        // 页面隐藏
    },
    onUnload: function() {
        // 页面关闭
    }
})
