let $APP = getApp();
let $wxaCMS = $APP.util.extend(true,{},$APP.iCMS);

$wxaCMS.data.tid = null;

$wxaCMS.getList = function () {
    if (this.data.pageLast) return;
    this.setData({
        loading: false
    });
    var that = this;
    $APP.iCMS.GET(
        $APP.iCMS.API.tag + '&id=' + this.data.tid + '&page=' + this.data.pageNum,
        function (json) {
            wx.setNavigationBarTitle({
                title: json.tag.name + ' - ' + $APP.globalData.site.name
            });
            that.setData({
                loading: true,
                hidden: true,
                subTitle: json.tag.name,
                tag: json.tag,
                article_list: that.data.article_list.concat(json.article_list),
                article_hot: json.article_hot,
                pageLast: json.PAGE?json.PAGE.LAST:false
            });
        });
},

$wxaCMS.onLoad = function (options) {
    var that = this;
    wx.getSystemInfo({
        success(res) {
            that.setData({
                scrollH: res.windowHeight
            });
        }
    });
    this.data.tid = options.id;
    this.getList();
}

$wxaCMS.run();
