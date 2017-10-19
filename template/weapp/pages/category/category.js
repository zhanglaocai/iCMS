let $APP = getApp();
let $wxaCMS = $APP.util.extend(true,{},$APP.iCMS);

$wxaCMS.data.cid = 0;

$wxaCMS.getList = function () {
    if (this.data.pageLast) return;
    this.setData({
        loading: false
    });
    var that = this;
    $APP.iCMS.GET(
        $APP.iCMS.API.category + '&cid=' + this.data.cid + '&page=' + this.data.pageNum,
        function (json) {
            wx.setNavigationBarTitle({
                title: json.category.name + ' - ' + $APP.globalData.site.seotitle
            });
            that.setData({
                loading: true,
                hidden: true,
                subTitle: json.category.name,
                category: json.category,
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
    this.data.cid = options.cid;
    this.getList();
}

$wxaCMS.run();
