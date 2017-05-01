var app = getApp();
var iCMS = require('../../utils/iCMS.js');
Page({
    data: {
        subTitle:null,
        scrollH:0,
        hidden: false,
        duration: 2000,
        indicatorDots: true,
        autoplay: true,
        circular: true,
        interval: 3000,
        loading: true,
        pageNum:1,
        pageLast:false,
        news:[],
        TID:null
    },
    getList() {
        if(this.data.pageLast) return;
        this.setData({
            loading: false
        });
        var that = this;
        iCMS.GET(iCMS.API.tag + '&id=' + this.data.TID+'&page=' + this.data.pageNum, function(json) {
            wx.setNavigationBarTitle({
                title: json.data.tag.name+' - '+json.data.site.seotitle
            });
            that.setData({
                loading: true,
                hidden: true,
                subTitle:json.data.tag.name,
                tag:json.data.tag,
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
    refresh: function () {},
    loadMore: function () {
      // console.log('loadMore');
      ++this.data.pageNum;
      this.getList();
    },
    onLoad(options) {
        var that = this;
        wx.getSystemInfo({
            success(res) {
                that.setData({
                    scrollH: res.windowHeight
                });
            }
        });
        //console.log(options);
        this.data.TID = options.id;
        this.getList();
    }
})
