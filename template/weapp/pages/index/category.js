var app = getApp();
var iCMS = require('../../utils/iCMS.js');
Page({
    data: {
        hidden: false,
        loading: true
    },
    //事件处理函数
    categoryTo: function(e) {
        // console.log(e.currentTarget);
        var cid = e.currentTarget.id;
        wx.navigateTo({
          url: '../category/category?cid='+cid
        })
    },
    tagTo: function(e) {
        // console.log(e.currentTarget);
        var id = e.currentTarget.id;
        wx.navigateTo({
          url: '../tag/tag?id='+id
        })
    },
    getCategoryList() {
        this.setData({
            loading: false
        });
        var that = this;
        iCMS.GET(iCMS.API.index + '&tpl=index.category', function(json) {
            wx.setNavigationBarTitle({
                title: json.data.site.title+' - '+json.data.site.seotitle
            });
            that.setData({
                loading: true,
                hidden: true,
                tags: json.data.tags,
                categorys: json.data.category
            });
        });
    },
    refresh: function () {
    },
    loadMore: function () {
    },
    onLoad() {
        var that = this;
        this.getCategoryList();
    }
})
