let $APP = getApp();
let $wxaCMS = $APP.util.extend(true,{},$APP.iCMS);

$wxaCMS.data.inputShowed = false;
$wxaCMS.data.inputVal = null;

$wxaCMS.showInput = function () {
    this.setData({
        inputShowed: true
    });
}
$wxaCMS.hideInput = function () {
    this.setData({
        inputVal: "",
        inputShowed: false
    });
}
$wxaCMS.clearInput = function () {
    this.setData({
        inputVal: ""
    });
}
$wxaCMS.searchAction = function (e) {
    this.setData({
        inputVal: e.detail.value
    });
    wx.navigateTo({
        url: '../search/search?q=' + e.detail.value
    })
}


// let $wxaCMS = $APP.util.extend($wxaCMS, inputAction);

$wxaCMS.onLoad = function (options) {
    wx.setNavigationBarTitle({
        title: '搜索 - ' + $APP.globalData.site.name
    });
    var that = this;
    $APP.iCMS.GET(
        $APP.iCMS.API.search_index,
        function (json) {
            that.setData({
                loading: true,
                hidden: true,
                tag_list: json.tag_list
            });
        }
    );
    this.setData({
        site: $APP.globalData.site
    });
}
$wxaCMS.run();
