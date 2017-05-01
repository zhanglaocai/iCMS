var app = getApp();
var iCMS = require('../../utils/iCMS.js');
Page({
    data: {
        subTitle:'最新文章',
        scrollH:0,
        hidden: false,
        duration: 2000,
        indicatorDots: true,
        autoplay: true,
        interval: 3000,
        loading: true,
        pageNum:1,
        pageLast:false,
        news:[]
    },
    getList() {
        if(this.data.pageLast) return;
        this.setData({
            loading: false
        });
        var that = this;
        iCMS.GET(iCMS.API.index + '&page=' + this.data.pageNum, function(json) {
            wx.setNavigationBarTitle({
                title: json.data.site.title+' - '+json.data.site.seotitle
            });
            that.setData({
                loading: true,
                hidden: true,
                hots: json.data.hots,
                news: that.data.news.concat(json.data.news)
            });
            if(json.data.PAGE){
                that.setData({
                    pageLast: json.data.PAGE.LAST
                });
            }
        });
    },
    refresh: function () {
      // console.log('refresh');
      // this.data.pageNum = 0;
      // this.getList();
        // this.enableRefresh && this.getTopics(1, true);
    },
    loadMore: function () {
      // console.log('loadMore');
      ++this.data.pageNum;
      this.getList();
    },
    onLoad() {
        var that = this;
        wx.getSystemInfo({
            success(res) {
                that.setData({
                    scrollH: res.windowHeight
                });
            }
        })
        this.getList();
    }
})
