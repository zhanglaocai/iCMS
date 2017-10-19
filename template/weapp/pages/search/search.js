let $APP = getApp();
let $wxaCMS = $APP.util.extend(true,{},$APP.iCMS);

$wxaCMS.data.q = null;

$wxaCMS.getList = function () {
    if (this.data.pageLast) return;
    this.setData({
        loading: false
    });
    var that = this;
    $APP.iCMS.GET(
        $APP.iCMS.API.search + '&q=' + this.data.q + '&page=' + this.data.pageNum,
        function (json) {
            wx.setNavigationBarTitle({
                title: json.search.title + ' - ' + $APP.globalData.site.seotitle
            });
            that.setData({
                loading: true,
                hidden: true,
                subTitle: json.search.title,
                search: json.search,
                article_list: that.data.article_list.concat(json.article_list),
                article_hot: json.article_hot,
                pageLast: json.PAGE?json.PAGE.LAST:false
            });
        });
};

$wxaCMS.onLoad = function (options) {
    var that = this;
    wx.getSystemInfo({
        success(res) {
            that.setData({
                scrollH: res.windowHeight
            });
        }
    });
    this.data.q = options.q;
    this.getList();
}

$wxaCMS.run();
