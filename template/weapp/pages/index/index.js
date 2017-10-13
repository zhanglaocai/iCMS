var $APP = getApp();
var $wxaCMS = $APP.util.extend({},$APP.iCMS);

$wxaCMS.getList = function () {
    if (this.data.pageLast) return;

    this.setData({
        loading: false
    });

    var that = this;

    $APP.iCMS.GET(
        $APP.iCMS.API.index + '&page=' + this.data.pageNum,
        function (json) {
            console.log(json);
            that.setData({
                loading: true,
                hidden: true,
                article_list: that.data.article_list.concat(json.article_list),
                article_hot: json.article_hot,
                pageLast: json.PAGE?json.PAGE.LAST:false
            });
        });
};

$wxaCMS.onLoad = function () {
    var that = this;
    this.setData({
        subTitle: '最新文章'
    });
    wx.getSystemInfo({
        success(res) {
            that.setData({
                scrollH: res.windowHeight
            });
        }
    })
    this.getList();
}

$wxaCMS.run();
